<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp_templates extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }

    private function getCurrentActor()
    {
        $actor = $this->session->userdata('super_admin_session');
        return [
            'id' => $actor['id'] ?? null,
            'name' => $actor['user_name'] ?? $actor['full_name'] ?? '',
            'email' => $actor['email'] ?? '',
            'role' => $this->session->userdata('role_as') ?? 'super_admin'
        ];
    }

    private function logActivity($action, $record_id, $details = '')
    {
        $actor = $this->getCurrentActor();
        $this->Common_model->insertActivityLog([
            'module' => 'whatsapp_templates',
            'record_id' => $record_id,
            'action' => $action,
            'details' => $details,
            'actor_id' => $actor['id'],
            'actor_name' => $actor['name'],
            'actor_email' => $actor['email'],
            'actor_role' => $actor['role'],
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function jsonResponse($response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    private function payload($includeCreated = false)
    {
        $property_id = decrypt_id($this->input->post('property_id'));
        $data = [
            'property_id' => $property_id,
            'template_name' => trim($this->input->post('template_name')),
            'orai_template_code' => trim($this->input->post('orai_template_code')),
            'api_key' => trim($this->input->post('api_key')),
            'api_endpoint' => trim($this->input->post('api_endpoint')),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validatePayload($data)
    {
        if (empty($data['property_id'])) {
            return 'Please select property';
        }

        if ($data['template_name'] === '' || $data['orai_template_code'] === '' || $data['api_key'] === '' || $data['api_endpoint'] === '') {
            return 'Please fill all required WhatsApp template details';
        }

        if (!in_array((string) $data['status'], ['0', '1'])) {
            return 'Invalid WhatsApp template status';
        }

        return '';
    }

    public function manage()
    {
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', "");

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/whatsapp_templates/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_whatsapp_templates_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [0 => 'wt.id', 1 => 'p.hotel_name', 2 => 'wt.template_name', 3 => 'wt.orai_template_code', 4 => 'wt.status', 5 => 'wt.created_at', 6 => 'wt.updated_at'];
        $order = $columns[$inputs['order'][0]['column']] ?? 'wt.id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTWhatsappTemplates($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $status = ((string) $row->status === '1') ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            $data[] = [
                $i++,
                htmlspecialchars($row->hotel_name ?? '-'),
                htmlspecialchars($row->template_name ?? '-'),
                htmlspecialchars($row->orai_template_code ?? '-'),
                $status,
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-template" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-template" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTWhatsappTemplatesAll(),
            'recordsFiltered' => $this->objdt->DTWhatsappTemplatesFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function add()
    {
        $data = $this->payload(true);
        $validationError = $this->validatePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $record_id = $this->Comman_model->insertData('whatsapp_templates', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created WhatsApp template '.$data['template_name']);
        }

        $this->jsonResponse(['status' => (bool) $record_id, 'message' => $record_id ? 'WhatsApp template added successfully' : 'Failed to add WhatsApp template', 'record_id' => $record_id ? encrypt_id($record_id) : '']);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $this->db->select('wt.*, p.hotel_name');
        $this->db->from('whatsapp_templates wt');
        $this->db->join('hotel_admin p', 'p.hotel_id = wt.property_id', 'left');
        $this->db->where('wt.id', $id);
        $row = $this->db->get()->row();

        if (empty($id) || empty($row)) {
            $this->jsonResponse(['status' => false, 'message' => 'WhatsApp template not found']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'property_id' => encrypt_id($row->property_id),
                'property_name' => $row->hotel_name,
                'template_name' => $row->template_name,
                'orai_template_code' => $row->orai_template_code,
                'api_key' => $row->api_key,
                'api_endpoint' => $row->api_endpoint,
                'status' => $row->status
            ],
            'id' => encrypt_id($row->id)
        ]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid WhatsApp template record']);
            return;
        }

        $data = $this->payload();
        $validationError = $this->validatePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('whatsapp_templates', $data, ['id' => $id]);

        if ($updated) {
            $this->logActivity('update', $id, 'Updated WhatsApp template '.$data['template_name']);
        }

        $this->jsonResponse(['status' => true, 'message' => 'WhatsApp template updated successfully', 'record_id' => encrypt_id($id)]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid WhatsApp template record']);
            return;
        }

        $row = $this->Common_model->getdata('whatsapp_templates', ['id' => $id]);
        $deleted = $this->Comman_model->Deletedata('whatsapp_templates', ['id' => $id]);

        if ($deleted) {
            $this->logActivity('delete', $id, isset($row->template_name) ? 'Deleted WhatsApp template '.$row->template_name : 'Deleted WhatsApp template ID '.$id);
        }

        $this->jsonResponse(['status' => (bool) $deleted, 'message' => $deleted ? 'WhatsApp template deleted successfully' : 'Failed to delete WhatsApp template']);
    }
}

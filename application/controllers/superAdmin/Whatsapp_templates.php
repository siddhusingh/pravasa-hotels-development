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

        $property = $this->Common_model->getdata('hotel_admin', array(
            'hotel_id' => $data['property_id'],
            'is_deleted' => 0
        ));

        if (empty($property)) {
            return 'Selected property is unavailable';
        }

        return '';
    }

    public function manage()
    {
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', array('is_deleted' => 0));

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

        $columns = [0 => 'wt.id', 1 => 'p.hotel_name', 2 => 'wt.template_name', 3 => 'wt.orai_template_code', 4 => 'wt.status', 5 => 'wt.created_at'];
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
        $row = $this->Common_model->getdata('whatsapp_templates', array(
            'id' => $id,
            'is_deleted' => 0
        ));

        if (empty($id) || empty($row)) {
            $this->jsonResponse(['status' => false, 'message' => 'WhatsApp template not found or already deleted']);
            return;
        }

        $property = $this->Common_model->getdata('hotel_admin', array(
            'hotel_id' => $row->property_id,
            'is_deleted' => 0
        ));

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'property_id' => !empty($property) ? encrypt_id($row->property_id) : '',
                'property_name' => $property->hotel_name ?? '',
                'property_unavailable' => empty($property),
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

        $existing_template = $this->Common_model->getdata('whatsapp_templates', array(
            'id' => $id,
            'is_deleted' => 0
        ));

        if (empty($existing_template)) {
            $this->jsonResponse(['status' => false, 'message' => 'WhatsApp template not found or already deleted']);
            return;
        }

        $data = $this->payload();
        $validationError = $this->validatePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('whatsapp_templates', $data, array(
            'id' => $id,
            'is_deleted' => 0
        ));
        $affected_rows = $this->db->affected_rows();

        if (!$updated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update WhatsApp template']);
            return;
        }

        if ($affected_rows === 0) {
            $still_active = $this->Common_model->getdata('whatsapp_templates', array(
                'id' => $id,
                'is_deleted' => 0
            ));

            if (empty($still_active)) {
                $this->jsonResponse(['status' => false, 'message' => 'WhatsApp template not found or already deleted']);
                return;
            }
        }

        if ($affected_rows > 0) {
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

        $where = array(
            'id' => $id,
            'is_deleted' => 0
        );
        $row = $this->Common_model->getdata('whatsapp_templates', $where);

        if (empty($row)) {
            $this->jsonResponse(['status' => false, 'message' => 'WhatsApp template not found or already deleted']);
            return;
        }

        $delete_query = $this->Comman_model->UpdateRecord('whatsapp_templates', array(
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ), $where);
        $deleted = $delete_query && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity('delete', $id, isset($row->template_name) ? 'Deleted WhatsApp template '.$row->template_name : 'Deleted WhatsApp template ID '.$id);
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted
                ? 'WhatsApp template deleted successfully'
                : ($delete_query ? 'WhatsApp template not found or already deleted' : 'Unable to delete WhatsApp template')
        ]);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SlotType extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->helper('secure');

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
            'module' => 'slot_types',
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
        $data = [
            'slot_name' => trim($this->input->post('slot_name')),
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
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
        if ($data['slot_name'] === '' || empty($data['start_time']) || empty($data['end_time'])) {
            return 'Please fill all required slot type details';
        }

        if (!in_array((string) $data['status'], ['0', '1'])) {
            return 'Invalid slot type status';
        }

        return '';
    }

    public function manage()
    {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/slot_type/manage_forms');
        $this->load->view('super_admin/include/footer');
    }

    public function get_slot_types_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [0 => 'id', 1 => 'slot_name', 2 => 'start_time', 3 => 'end_time', 4 => 'status', 5 => 'created_at', 6 => 'updated_at'];
        $order = $columns[$inputs['order'][0]['column']] ?? 'id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTSlotTypes($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $status = ((string) $row->status === '1') ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            $data[] = [
                $i++,
                htmlspecialchars($row->slot_name ?? '-'),
                !empty($row->start_time) ? date('h:i A', strtotime($row->start_time)) : '-',
                !empty($row->end_time) ? date('h:i A', strtotime($row->end_time)) : '-',
                $status,
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-slot-type" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-slot-type" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTSlotTypesAll(),
            'recordsFiltered' => $this->objdt->DTSlotTypesFiltered($search),
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

        $record_id = $this->Comman_model->insertData('slot_types', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created slot type '.$data['slot_name']);
        }

        $this->jsonResponse(['status' => (bool) $record_id, 'message' => $record_id ? 'Slot Type added successfully' : 'Failed to add slot type', 'record_id' => $record_id ? encrypt_id($record_id) : '']);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $row = $this->Common_model->getdata('slot_types', ['id' => $id]);

        if (empty($id) || empty($row)) {
            $this->jsonResponse(['status' => false, 'message' => 'Slot Type not found']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => ['slot_name' => $row->slot_name, 'start_time' => $row->start_time, 'end_time' => $row->end_time, 'status' => $row->status],
            'id' => encrypt_id($row->id)
        ]);
    }

    public function getAll()
    {
        $slots = $this->db->where('status', 1)->order_by('start_time', 'ASC')->get('slot_types')->result();
        foreach ($slots as $slot) {
            $slot->id = encrypt_id($slot->id);
        }
        $this->jsonResponse(['status' => true, 'data' => $slots]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid slot type record']);
            return;
        }

        $data = $this->payload();
        $validationError = $this->validatePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('slot_types', $data, ['id' => $id]);

        if ($updated) {
            $this->logActivity('update', $id, 'Updated slot type '.$data['slot_name']);
        }

        $this->jsonResponse(['status' => true, 'message' => 'Slot Type updated successfully', 'record_id' => encrypt_id($id)]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid slot type record']);
            return;
        }

        $row = $this->Common_model->getdata('slot_types', ['id' => $id]);
        $deleted = $this->Comman_model->Deletedata('slot_types', ['id' => $id]);

        if ($deleted) {
            $this->logActivity('delete', $id, isset($row->slot_name) ? 'Deleted slot type '.$row->slot_name : 'Deleted slot type ID '.$id);
        }

        $this->jsonResponse(['status' => (bool) $deleted, 'message' => $deleted ? 'Slot Type deleted successfully' : 'Failed to delete slot type']);
    }
}

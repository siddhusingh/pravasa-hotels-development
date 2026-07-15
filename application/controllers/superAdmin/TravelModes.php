<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TravelModes extends MY_Controller
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
            'module' => 'travel_modes',
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
            'travel_mode_name' => trim($this->input->post('travel_mode_name')),
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
        if ($data['travel_mode_name'] === '') {
            return 'Please enter travel mode name';
        }

        if (!in_array((string) $data['status'], ['0', '1'])) {
            return 'Invalid travel mode status';
        }

        return '';
    }

    public function manage()
    {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/travelmode/manage_forms');
        $this->load->view('super_admin/include/footer');
    }

    public function get_travel_modes_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [0 => 'id', 1 => 'travel_mode_name', 2 => 'status', 3 => 'created_at', 4 => 'updated_at'];
        $order = $columns[$inputs['order'][0]['column']] ?? 'id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTTravelModes($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $status = ((string) $row->status === '1') ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            $data[] = [
                $i++,
                htmlspecialchars($row->travel_mode_name ?? '-'),
                $status,
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-travel-mode" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-travel-mode" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTTravelModesAll(),
            'recordsFiltered' => $this->objdt->DTTravelModesFiltered($search),
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

        $record_id = $this->Comman_model->insertData('travel_modes', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created travel mode '.$data['travel_mode_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'Travel Mode added successfully' : 'Database error',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $row = $this->Common_model->getdata('travel_modes', array(
            'id' => $id,
            'is_deleted' => 0
        ));

        if (empty($id) || empty($row)) {
            $this->jsonResponse(['status' => false, 'message' => 'Travel mode not found or already deleted']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => ['travel_mode_name' => $row->travel_mode_name, 'status' => $row->status],
            'id' => encrypt_id($row->id)
        ]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid travel mode record']);
            return;
        }

        $existing_travel_mode = $this->Common_model->getdata('travel_modes', array(
            'id' => $id,
            'is_deleted' => 0
        ));

        if (empty($existing_travel_mode)) {
            $this->jsonResponse(['status' => false, 'message' => 'Travel mode not found or already deleted']);
            return;
        }

        $data = $this->payload();
        $validationError = $this->validatePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('travel_modes', $data, array(
            'id' => $id,
            'is_deleted' => 0
        ));
        $affected_rows = $this->db->affected_rows();

        if (!$updated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update travel mode']);
            return;
        }

        if ($affected_rows === 0) {
            $still_active = $this->Common_model->getdata('travel_modes', array(
                'id' => $id,
                'is_deleted' => 0
            ));

            if (empty($still_active)) {
                $this->jsonResponse(['status' => false, 'message' => 'Travel mode not found or already deleted']);
                return;
            }
        }

        if ($affected_rows > 0) {
            $this->logActivity('update', $id, 'Updated travel mode '.$data['travel_mode_name']);
        }

        $this->jsonResponse(['status' => true, 'message' => 'Travel Mode updated successfully', 'record_id' => encrypt_id($id)]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid travel mode record']);
            return;
        }

        $where = array(
            'id' => $id,
            'is_deleted' => 0
        );
        $row = $this->Common_model->getdata('travel_modes', $where);

        if (empty($row)) {
            $this->jsonResponse(['status' => false, 'message' => 'Travel mode not found or already deleted']);
            return;
        }

        $delete_query = $this->Comman_model->UpdateRecord('travel_modes', array(
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ), $where);
        $deleted = $delete_query && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity('delete', $id, isset($row->travel_mode_name) ? 'Deleted travel mode '.$row->travel_mode_name : 'Deleted travel mode ID '.$id);
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted
                ? 'Travel Mode deleted successfully'
                : ($delete_query ? 'Travel mode not found or already deleted' : 'Unable to delete travel mode')
        ]);
    }
}

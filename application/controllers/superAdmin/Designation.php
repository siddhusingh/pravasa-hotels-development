<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Designation extends MY_Controller
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
            'module' => 'designations',
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

    private function designationPayload($includeCreated = false)
    {
        $data = [
            'designation_name' => trim($this->input->post('designation_name')),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateDesignationPayload($data)
    {
        if ($data['designation_name'] === '') {
            return 'Please enter designation name';
        }

        if (!in_array((string) $data['status'], ['0', '1'])) {
            return 'Invalid designation status';
        }

        return '';
    }

    public function manage()
    {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/designation/manage_forms');
        $this->load->view('super_admin/include/footer');
    }

    public function get_designations_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'id',
            1 => 'designation_name',
            2 => 'created_at',
            3 => 'updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']] ?? 'id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTDesignations($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $data[] = [
                $i++,
                htmlspecialchars($row->designation_name ?? '-'),
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-designation" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-designation" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTDesignationsAll(),
            'recordsFiltered' => $this->objdt->DTDesignationsFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function add()
    {
        $data = $this->designationPayload(true);
        $validationError = $this->validateDesignationPayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $record_id = $this->Comman_model->insertData('designations', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created designation '.$data['designation_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'Designation added successfully' : 'Database error',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $designation = $this->Common_model->getdata('designations', array(
            'id' => $id,
            'is_deleted' => 0
        ));

        if (empty($id) || empty($designation)) {
            $this->jsonResponse(['status' => false, 'message' => 'Designation not found or already deleted']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'designation_name' => $designation->designation_name,
                'status' => $designation->status
            ],
            'id' => encrypt_id($designation->id)
        ]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid designation record']);
            return;
        }

        $existing_designation = $this->Common_model->getdata('designations', array(
            'id' => $id,
            'is_deleted' => 0
        ));

        if (empty($existing_designation)) {
            $this->jsonResponse(['status' => false, 'message' => 'Designation not found or already deleted']);
            return;
        }

        $data = $this->designationPayload();
        $validationError = $this->validateDesignationPayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('designations', $data, array(
            'id' => $id,
            'is_deleted' => 0
        ));
        $affected_rows = $this->db->affected_rows();

        if (!$updated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update designation']);
            return;
        }

        if ($affected_rows === 0) {
            $still_active = $this->Common_model->getdata('designations', array(
                'id' => $id,
                'is_deleted' => 0
            ));

            if (empty($still_active)) {
                $this->jsonResponse(['status' => false, 'message' => 'Designation not found or already deleted']);
                return;
            }
        }

        if ($affected_rows > 0) {
            $this->logActivity('update', $id, 'Updated designation '.$data['designation_name']);
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Designation updated successfully',
            'record_id' => encrypt_id($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid designation record']);
            return;
        }

        $where = array(
            'id' => $id,
            'is_deleted' => 0
        );
        $designation = $this->Common_model->getdata('designations', $where);

        if (empty($designation)) {
            $this->jsonResponse(['status' => false, 'message' => 'Designation not found or already deleted']);
            return;
        }

        $delete_query = $this->Comman_model->UpdateRecord('designations', array(
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ), $where);
        $deleted = $delete_query && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                isset($designation->designation_name) ? 'Deleted designation '.$designation->designation_name : 'Deleted designation ID '.$id
            );
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted
                ? 'Designation deleted successfully'
                : ($delete_query ? 'Designation not found or already deleted' : 'Unable to delete designation')
        ]);
    }
}

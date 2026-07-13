<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DepartmentManagment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');

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
            'module' => 'departments',
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

    private function departmentPayload($includeCreated = false)
    {
        $data = [
            'department_name' => trim($this->input->post('department_name')),
            'escalation_level_1' => trim($this->input->post('escalation_level_1')),
            'escalation_level_2' => trim($this->input->post('escalation_level_2')),
            'escalation_level_3' => trim($this->input->post('escalation_level_3')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateDepartmentPayload($data)
    {
        if ($data['department_name'] === '' || $data['escalation_level_1'] === '' || $data['escalation_level_2'] === '' || $data['escalation_level_3'] === '') {
            return 'Please fill all required department details';
        }

        if (!is_numeric($data['escalation_level_1']) || !is_numeric($data['escalation_level_2']) || !is_numeric($data['escalation_level_3'])) {
            return 'Escalation levels must be numeric';
        }

        return '';
    }

    public function index()
    {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/managedepartment');
        $this->load->view('super_admin/include/footer');
    }

    public function get_departments_table()
    {
        $inputs = $this->input->post();

        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'department_id',
            1 => 'department_name',
            2 => 'escalation_level_1',
            3 => 'escalation_level_2',
            4 => 'escalation_level_3',
            5 => 'created_at',
            6 => 'updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']] ?? 'department_id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTDepartments($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->department_id);
            $data[] = [
                $i++,
                $row->department_name,
                $row->escalation_level_1 . ' Hours',
                $row->escalation_level_2 . ' Hours',
                $row->escalation_level_3 . ' Hours',
                date('d M Y', strtotime($row->created_at)),
                date('d M Y', strtotime($row->updated_at)),
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-department" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-department ms-2" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>
                <a href="'.base_url('manage-facebook-forms/'.$row->department_id).'" class="btn btn-sm btn-primary ms-2">Forms</a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTDepartmentsAll(),
            'recordsFiltered' => $this->objdt->DTDepartmentsFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function insert()
    {
        $department_data = $this->departmentPayload(true);
        $validationError = $this->validateDepartmentPayload($department_data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $record_id = $this->Comman_model->insertData('departments', $department_data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created department '.$department_data['department_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'New department has been added successfully' : 'Failed to add department',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function edit()
    {
        $id = decrypt_id($this->input->post('id'));
        $result = $this->Common_model->getdata('departments', ['department_id' => $id]);

        if (empty($id) || empty($result)) {
            $this->jsonResponse(['status' => false, 'message' => 'Department record not found']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'department_name' => $result->department_name,
                'escalation_level_1' => $result->escalation_level_1,
                'escalation_level_2' => $result->escalation_level_2,
                'escalation_level_3' => $result->escalation_level_3
            ],
            'id' => encrypt_id($result->department_id)
        ]);
    }

    public function update()
    {
        $record_id = decrypt_id($this->input->post('record_id'));

        if (empty($record_id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid department record']);
            return;
        }

        $department_data = $this->departmentPayload();
        $validationError = $this->validateDepartmentPayload($department_data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('departments', $department_data, ['department_id' => $record_id]);

        if ($updated) {
            $this->logActivity('update', $record_id, 'Updated department '.$department_data['department_name']);
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Department data has been updated successfully',
            'record_id' => encrypt_id($record_id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid department record']);
            return;
        }

        $department = $this->Common_model->getdata('departments', ['department_id' => $id]);
        $deleted = $this->Comman_model->Deletedata('departments', ['department_id' => $id]);

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                isset($department->department_name) ? 'Deleted department '.$department->department_name : 'Deleted department ID '.$id
            );
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted ? 'Department deleted successfully' : 'Something went wrong'
        ]);
    }
}

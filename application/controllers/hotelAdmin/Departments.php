<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Departments extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('hotel_admin_session'))) {
            redirect('hotel-admin-login');
        }
    }

    private function getCurrentActor()
    {
        $actor = $this->session->userdata('hotel_admin_session');
        $hotelId = (int) ($actor['id'] ?? 0);
        $admin = $this->Common_model->getdata('hotel_admins', [
            'hotel_id' => $hotelId,
            'name' => $actor['user_name'] ?? '',
            'is_deleted' => 0
        ]);

        return [
            'id' => $admin->id ?? ($actor['id'] ?? null),
            'name' => $admin->name ?? ($actor['user_name'] ?? ''),
            'email' => $admin->email ?? '',
            'role' => $this->session->userdata('role_as') ?? 'admin'
        ];
    }

    private function logActivity($action, $recordId, $details = '')
    {
        $actor = $this->getCurrentActor();
        $this->Common_model->insertActivityLog([
            'module' => 'departments',
            'record_id' => $recordId,
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

    private function jsonResponse(array $response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    private function departmentPayload($includeCreated = false)
    {
        $data = [
            'department_name' => trim((string) $this->input->post('department_name')),
            'escalation_level_1' => trim((string) $this->input->post('escalation_level_1')),
            'escalation_level_2' => trim((string) $this->input->post('escalation_level_2')),
            'escalation_level_3' => trim((string) $this->input->post('escalation_level_3')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;
        }

        return $data;
    }

    private function validateDepartmentPayload(array $data)
    {
        if ($data['department_name'] === '' || $data['escalation_level_1'] === '' || $data['escalation_level_2'] === '' || $data['escalation_level_3'] === '') {
            return 'Please fill all required department details';
        }

        foreach (['escalation_level_1', 'escalation_level_2', 'escalation_level_3'] as $field) {
            if (!is_numeric($data[$field])) {
                return 'Escalation levels must be numeric';
            }

            if ((float) $data[$field] < 0) {
                return 'Escalation levels cannot be negative';
            }
        }

        return '';
    }

    private function getActiveDepartment($recordId)
    {
        if (empty($recordId)) {
            return null;
        }

        return $this->Common_model->getdata('departments', [
            'department_id' => $recordId,
            'is_deleted' => 0
        ]);
    }

    public function index()
    {
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/managedepartment');
        $this->load->view('hotel_admin/include/footer');
    }

    public function get_departments_table()
    {
        $inputs = (array) $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = max(1, min(100, (int) ($inputs['length'] ?? 10)));
        $search = trim((string) ($inputs['search']['value'] ?? ''));

        $columns = [
            0 => 'department_id',
            1 => 'department_name',
            2 => 'escalation_level_1',
            3 => 'escalation_level_2',
            4 => 'escalation_level_3'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'department_id';
        $direction = strtolower((string) ($inputs['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
        $list = $this->objdt->DTDepartments($length, $start, $search, $order, $direction);

        $data = [];
        $number = $start + 1;
        foreach ($list as $row) {
            $encryptedId = encrypt_id($row->department_id);
            $data[] = [
                $number++,
                html_escape($row->department_name),
                html_escape($row->escalation_level_1).' Hours',
                html_escape($row->escalation_level_2).' Hours',
                html_escape($row->escalation_level_3).' Hours',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-department" data-record_id="'.$encryptedId.'" aria-label="Edit department"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-department ms-2" data-record_id="'.$encryptedId.'" aria-label="Delete department"><i class="fa fa-trash"></i></a>
                <a href="'.base_url('view-leads?department='.urlencode($encryptedId)).'" class="text-fade hover-primary ms-2" aria-label="View leads for '.html_escape($row->department_name).'"><i class="fa fa-phone"></i> Leads</a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTDepartmentsAll(),
            'recordsFiltered' => $this->objdt->DTDepartmentsFiltered($search),
            'data' => $data
        ]);
    }

    public function insert()
    {
        $data = $this->departmentPayload(true);
        $validationError = $this->validateDepartmentPayload($data);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $recordId = $this->Comman_model->insertData('departments', $data);
        if ($recordId) {
            $this->logActivity('create', $recordId, 'Created department '.$data['department_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $recordId,
            'message' => $recordId ? 'New department has been added successfully' : 'Failed to add department',
            'record_id' => $recordId ? encrypt_id($recordId) : ''
        ]);
    }

    public function edit()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $department = $this->getActiveDepartment($recordId);
        if (empty($department)) {
            $this->jsonResponse(['status' => false, 'message' => 'Department record not found or already deleted']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'department_name' => $department->department_name,
                'escalation_level_1' => $department->escalation_level_1,
                'escalation_level_2' => $department->escalation_level_2,
                'escalation_level_3' => $department->escalation_level_3
            ],
            'id' => encrypt_id($department->department_id)
        ]);
    }

    public function update()
    {
        $recordId = decrypt_id($this->input->post('record_id'));
        if (empty($this->getActiveDepartment($recordId))) {
            $this->jsonResponse(['status' => false, 'message' => 'Department record not found or already deleted']);
            return;
        }

        $data = $this->departmentPayload();
        $validationError = $this->validateDepartmentPayload($data);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $where = ['department_id' => $recordId, 'is_deleted' => 0];
        $updated = $this->Comman_model->UpdateRecord('departments', $data, $where);
        $activeDepartment = $updated ? $this->Common_model->getdata('departments', $where) : null;
        if (empty($activeDepartment)) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update department']);
            return;
        }

        $this->logActivity('update', $recordId, 'Updated department '.$data['department_name']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Department data has been updated successfully',
            'record_id' => encrypt_id($recordId)
        ]);
    }

    public function delete()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $department = $this->getActiveDepartment($recordId);
        if (empty($department)) {
            $this->jsonResponse(['status' => false, 'message' => 'Department record not found or already deleted']);
            return;
        }

        $deleted = $this->Comman_model->UpdateRecord('departments', [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], [
            'department_id' => $recordId,
            'is_deleted' => 0
        ]);

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $recordId, 'Soft deleted department '.$department->department_name);
            $this->jsonResponse(['status' => true, 'message' => 'Department deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Department record not found or already deleted']);
    }
}

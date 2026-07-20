<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FacebookformController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Facebookform_model');
        $this->load->model('Common_model');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }

    private function jsonResponse($response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
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

    private function logActivity($action, $recordId, $details = '')
    {
        $actor = $this->getCurrentActor();

        $this->Common_model->insertActivityLog([
            'module' => 'facebook_forms',
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

    private function getDepartmentFromToken($token)
    {
        if (!is_string($token) || trim($token) === '') {
            return null;
        }

        $departmentId = decrypt_id($token);
        if (empty($departmentId) || !ctype_digit((string) $departmentId)) {
            return null;
        }

        return $this->Common_model->getdata('departments', [
            'department_id' => (int) $departmentId,
            'is_deleted' => 0
        ]);
    }

    private function getFormFromToken($token, $departmentId)
    {
        if (!is_string($token) || trim($token) === '') {
            return null;
        }

        $formRecordId = decrypt_id($token);
        if (empty($formRecordId) || !ctype_digit((string) $formRecordId)) {
            return null;
        }

        return $this->db
            ->where('id', (int) $formRecordId)
            ->where('department_id', (int) $departmentId)
            ->where('is_deleted', 0)
            ->get('facebook_forms')
            ->row();
    }

    private function formPayload()
    {
        return [
            'form_id' => trim((string) $this->input->post('form_id')),
            'form_name' => trim((string) $this->input->post('form_name')),
            'status' => trim((string) $this->input->post('status'))
        ];
    }

    private function validateFormPayload($data, $departmentId, $excludeId = null)
    {
        if ($data['form_id'] === '') {
            return 'Please enter Facebook Form ID';
        }

        if (!preg_match('/^[A-Za-z0-9_-]{1,255}$/', $data['form_id'])) {
            return 'Facebook Form ID may contain only letters, numbers, hyphens and underscores';
        }

        if ($data['form_name'] === '') {
            return 'Please enter Facebook Form Name';
        }

        $nameLength = function_exists('mb_strlen') ? mb_strlen($data['form_name'], 'UTF-8') : strlen($data['form_name']);
        if ($nameLength < 2 || $nameLength > 255) {
            return 'Facebook Form Name must be between 2 and 255 characters';
        }

        if (strip_tags($data['form_name']) !== $data['form_name'] || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $data['form_name'])) {
            return 'Facebook Form Name contains invalid content';
        }

        if (!in_array($data['status'], ['0', '1'], true)) {
            return 'Please select a valid status';
        }

        $this->db
            ->from('facebook_forms')
            ->where('department_id', (int) $departmentId)
            ->where('is_deleted', 0)
            ->where('form_id', $data['form_id']);

        if ($excludeId !== null) {
            $this->db->where('id !=', (int) $excludeId);
        }

        if ($this->db->count_all_results() > 0) {
            return 'This Facebook Form ID already exists for the department';
        }

        return '';
    }

    public function manage($departmentToken)
    {
        $department = $this->getDepartmentFromToken($departmentToken);
        if (empty($department)) {
            show_404();
            return;
        }

        $data = [
            'department_token' => $departmentToken,
            'department_info' => $department
        ];

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/facebook_forms/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_forms_table()
    {
        $department = $this->getDepartmentFromToken((string) $this->input->post('department'));
        if (empty($department)) {
            $this->jsonResponse([
                'draw' => (int) $this->input->post('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'status' => false,
                'message' => 'Invalid department'
            ]);
            return;
        }

        $inputs = $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = max(1, min(100, (int) ($inputs['length'] ?? 10)));
        $search = trim($inputs['search']['value'] ?? '');
        $columns = [
            0 => 'id',
            1 => 'form_id',
            2 => 'form_name',
            3 => 'status',
            4 => 'created_at',
            5 => 'updated_at'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'id';
        $direction = strtolower($inputs['order'][0]['dir'] ?? '') === 'asc' ? 'ASC' : 'DESC';
        $departmentId = (int) $department->department_id;

        $recordsTotal = $this->db
            ->where('department_id', $departmentId)
            ->where('is_deleted', 0)
            ->count_all_results('facebook_forms');

        $applySearch = function () use ($search) {
            if ($search !== '') {
                $this->db->group_start()
                    ->like('form_id', $search)
                    ->or_like('form_name', $search)
                    ->group_end();
            }
        };

        $this->db
            ->from('facebook_forms')
            ->where('department_id', $departmentId)
            ->where('is_deleted', 0);
        $applySearch();
        $recordsFiltered = $this->db->count_all_results();

        $this->db
            ->from('facebook_forms')
            ->where('department_id', $departmentId)
            ->where('is_deleted', 0);
        $applySearch();
        $forms = $this->db
            ->order_by($order, $direction)
            ->limit($length, $start)
            ->get()
            ->result();

        $rows = [];
        $serialNumber = $start + 1;
        foreach ($forms as $form) {
            $encryptedId = encrypt_id($form->id);
            $active = (string) $form->status === '1';
            $rows[] = [
                $serialNumber++,
                html_escape($form->form_id),
                html_escape($form->form_name),
                $active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>',
                !empty($form->created_at) ? date('d M Y', strtotime($form->created_at)) : '-',
                !empty($form->updated_at) ? date('d M Y', strtotime($form->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-facebook-form" data-record_id="'.$encryptedId.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-facebook-form ms-2" data-record_id="'.$encryptedId.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows
        ]);
    }

    public function addForm()
    {
        $department = $this->getDepartmentFromToken((string) $this->input->post('department'));
        if (empty($department)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid department']);
            return;
        }

        $data = $this->formPayload();
        $validationError = $this->validateFormPayload($data, $department->department_id);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $data['department_id'] = (int) $department->department_id;
        $data['is_deleted'] = 0;
        $data['deleted_at'] = null;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $inserted = $this->db->insert('facebook_forms', $data);
        $recordId = $inserted ? $this->db->insert_id() : 0;

        if ($recordId) {
            $this->logActivity('create', $recordId, 'Created Facebook form '.$data['form_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $recordId,
            'message' => $recordId ? 'Facebook form added successfully' : 'Failed to add Facebook form'
        ]);
    }

    public function getFormDetails()
    {
        $department = $this->getDepartmentFromToken((string) $this->input->post('department'));
        $form = !empty($department)
            ? $this->getFormFromToken((string) $this->input->post('id'), $department->department_id)
            : null;

        if (empty($form)) {
            $this->jsonResponse(['status' => false, 'message' => 'Facebook form not found']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'form_id' => $form->form_id,
                'form_name' => $form->form_name,
                'status' => (string) $form->status
            ],
            'id' => encrypt_id($form->id)
        ]);
    }

    public function updateForm()
    {
        $department = $this->getDepartmentFromToken((string) $this->input->post('department'));
        $form = !empty($department)
            ? $this->getFormFromToken((string) $this->input->post('id'), $department->department_id)
            : null;

        if (empty($form)) {
            $this->jsonResponse(['status' => false, 'message' => 'Facebook form not found']);
            return;
        }

        $data = $this->formPayload();
        $validationError = $this->validateFormPayload($data, $department->department_id, $form->id);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');
        $updated = $this->db
            ->where('id', $form->id)
            ->where('department_id', $department->department_id)
            ->where('is_deleted', 0)
            ->update('facebook_forms', $data);

        if ($updated && $this->db->affected_rows() > 0) {
            $this->logActivity('update', $form->id, 'Updated Facebook form '.$data['form_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $updated,
            'message' => $updated ? 'Facebook form updated successfully' : 'Failed to update Facebook form'
        ]);
    }

    public function deleteForm()
    {
        $department = $this->getDepartmentFromToken((string) $this->input->post('department'));
        $form = !empty($department)
            ? $this->getFormFromToken((string) $this->input->post('id'), $department->department_id)
            : null;

        if (empty($form)) {
            $this->jsonResponse(['status' => false, 'message' => 'Facebook form not found']);
            return;
        }

        $deletedAt = date('Y-m-d H:i:s');
        $deleted = $this->db
            ->where('id', $form->id)
            ->where('department_id', $department->department_id)
            ->where('is_deleted', 0)
            ->update('facebook_forms', [
                'is_deleted' => 1,
                'deleted_at' => $deletedAt,
                'updated_at' => $deletedAt
            ]);

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $form->id, 'Soft deleted Facebook form '.$form->form_name);
            $this->jsonResponse(['status' => true, 'message' => 'Facebook form deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Failed to delete Facebook form']);
    }

    public function fetchForms()
    {
        $department = $this->getDepartmentFromToken((string) $this->input->post('department'));
        if (empty($department)) {
            show_404();
            return;
        }

        $data['forms'] = $this->Facebookform_model->getFormsByDepartment($department->department_id);
        $this->load->view('super_admin/facebook_forms/_forms_table', $data);
    }
}

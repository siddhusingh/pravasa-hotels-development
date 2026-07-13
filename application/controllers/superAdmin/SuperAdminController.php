<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuperAdminController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('SuperAdmin_model');
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
            'module' => 'super_admins',
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

    public function index()
    {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/managesuperAdmin');
        $this->load->view('super_admin/include/footer');
    }

    public function fetchAdmins()
    {
        $data['admins'] = $this->SuperAdmin_model->getAll();
        $this->load->view('super_admin/superAdminTable', $data);
    }

    public function get_super_admins_table()
    {
        $inputs = $this->input->post();

        $draw = $inputs['draw'] ?? 1;
        $start = $inputs['start'] ?? 0;
        $length = $inputs['length'] ?? 10;
        $search = $inputs['search']['value'] ?? '';

        $columns = [
            0 => 'id',
            1 => 'full_name',
            2 => 'email',
            3 => 'phone',
            4 => 'status',
            5 => 'created_at',
            6 => 'updated_at'
        ];

        $orderIndex = $inputs['order'][0]['column'] ?? 0;
        $order = $columns[$orderIndex] ?? 'id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTSuperAdmins($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $statusClass = ($row->status === 'active') ? 'success' : 'danger';

            $data[] = [
                $i++,
                htmlspecialchars($row->full_name ?? ''),
                htmlspecialchars($row->email ?? ''),
                htmlspecialchars($row->phone ?? ''),
                '<span class="badge bg-'.$statusClass.'">'.htmlspecialchars($row->status ?? '').'</span>',
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-admin" data-record_id="'.$encrypted.'">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-admin" data-record_id="'.$encrypted.'">
                    <i class="fa fa-trash"></i>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTSuperAdminsAll(),
            'recordsFiltered' => $this->objdt->DTSuperAdminsFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    private function validateAdminPayload($requirePassword = true)
    {
        $errors = [];
        $fullName = trim($this->input->post('full_name'));
        $email = trim($this->input->post('email'));
        $phone = trim($this->input->post('phone'));
        $password = $this->input->post('password');
        $status = $this->input->post('status');

        if ($fullName === '' || strlen($fullName) < 3) {
            $errors['full_name'] = 'Full name must be at least 3 characters';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email';
        }

        if ($phone === '' || !preg_match('/^[0-9]{10}$/', $phone)) {
            $errors['phone'] = 'Phone number must be 10 digits';
        }

        if ($requirePassword && ($password === '' || strlen($password) < 6)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!$requirePassword && $password !== '' && strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!$requirePassword && !in_array($status, ['active', 'inactive'], true)) {
            $errors['status'] = 'Please select a valid status';
        }

        return [
            'errors' => $errors,
            'data' => [
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'status' => $status
            ]
        ];
    }

    public function addAdmin()
    {
        $validated = $this->validateAdminPayload(true);
        if (!empty($validated['errors'])) {
            echo json_encode([
                'status' => 'error',
                'errors' => $validated['errors'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $payload = $validated['data'];
        $data = [
            'full_name' => $payload['full_name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'password' => md5($payload['password']),
            'user_role' => 2,
            'status' => 'active'
        ];

        $insert = $this->SuperAdmin_model->insert($data);
        $record_id = $insert ? $this->db->insert_id() : null;

        if ($insert) {
            $this->logActivity(
                'create',
                $record_id,
                "Created super admin {$data['full_name']} ({$data['email']})"
            );
        }

        echo json_encode([
            'status' => $insert ? 'success' : 'error',
            'record_id' => $record_id ? encrypt_id($record_id) : '',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function editAdmin()
    {
        $id = decrypt_id($this->input->post('id'));
        if (empty($id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid super admin selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $admin = $this->SuperAdmin_model->getById($id);
        if (empty($admin)) {
            echo json_encode([
                'status' => false,
                'message' => 'Super admin not found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        unset($admin['password']);
        echo json_encode([
            'status' => true,
            'data' => $admin,
            'id' => encrypt_id($id),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function updateAdmin()
    {
        $id = decrypt_id($this->input->post('id'));
        if (empty($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid super admin selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $validated = $this->validateAdminPayload(false);
        if (!empty($validated['errors'])) {
            echo json_encode([
                'status' => 'error',
                'errors' => $validated['errors'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $payload = $validated['data'];
        $updateData = [
            'full_name' => $payload['full_name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'status' => $payload['status']
        ];

        if ($payload['password'] !== '') {
            $updateData['password'] = md5($payload['password']);
        }

        $updated = $this->SuperAdmin_model->update($id, $updateData);

        if ($updated) {
            $this->logActivity(
                'update',
                $id,
                "Updated super admin {$updateData['full_name']} ({$updateData['email']})"
            );
        }

        echo json_encode([
            'status' => $updated ? 'success' : 'error',
            'record_id' => encrypt_id($id),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function deleteAdmin()
    {
        $id = decrypt_id($this->input->post('id'));
        if (empty($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid super admin selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $admin = $this->SuperAdmin_model->getById($id);
        $deleted = $this->SuperAdmin_model->delete($id);

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                $admin ? "Deleted super admin {$admin['full_name']} ({$admin['email']})" : "Deleted super admin record {$id}"
            );
        }

        echo json_encode([
            'status' => $deleted ? 'success' : 'error',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }
}

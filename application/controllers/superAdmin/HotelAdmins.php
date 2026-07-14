<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HotelAdmins extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');

        // Super Admin Access Only
        if (
            empty($this->session->userdata('super_admin_session')) ||
            $this->session->userdata('role_as') != 'super_admin' ||
            $this->session->userdata('user_role') != 1
        ) {
            redirect('super-admin-login');
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

        $logData = [
            'module' => 'hotel_admins',
            'record_id' => $record_id,
            'action' => $action,
            'details' => $details,
            'actor_id' => $actor['id'],
            'actor_name' => $actor['name'],
            'actor_email' => $actor['email'],
            'actor_role' => $actor['role'],
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->Common_model->insertActivityLog($logData);
    }

    /**
     * List Hotel Admins
     */
    public function index()
    {
        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', ['is_deleted' => 0]);

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/hotelAdmins', $data);
        $this->load->view('super_admin/include/footer');
    }

    private function validateHotelAdminPayload($requirePassword = true)
    {
        $errors = [];
        $name = trim($this->input->post('name'));
        $email = trim($this->input->post('email'));
        $phone = trim($this->input->post('phone'));
        $password = $this->input->post('password');
        $hotelId = decrypt_id($this->input->post('hotel_id'));
        $status = $this->input->post('status');

        if ($name === '' || strlen($name) < 3) {
            $errors['name'] = 'Full name must be at least 3 characters';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email';
        }

        if ($phone === '' || !preg_match('/^[0-9]{10}$/', $phone)) {
            $errors['phone'] = 'Phone number must be 10 digits';
        }

        if (empty($hotelId)) {
            $errors['hotel_id'] = 'Please select a hotel';
        } else {
            $hotel = $this->Common_model->getdata('hotel_admin', [
                'hotel_id' => $hotelId,
                'is_deleted' => 0
            ]);

            if (empty($hotel)) {
                $errors['hotel_id'] = 'Selected hotel is unavailable';
            }
        }

        if ($requirePassword && ($password === '' || strlen($password) < 6)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!$requirePassword && $password !== '' && strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!$requirePassword && $status !== null && !in_array((string)$status, ['0', '1'], true)) {
            $errors['status'] = 'Please select a valid status';
        }

        return [
            'errors' => $errors,
            'data' => [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'hotel_id' => $hotelId,
                'status' => ($status === null || $status === '') ? 1 : (int)$status
            ]
        ];
    }

    public function get_hotel_admins_table()
    {
        $inputs = $this->input->post();

        $draw = $inputs['draw'] ?? 1;
        $start = $inputs['start'] ?? 0;
        $length = $inputs['length'] ?? 10;
        $search = $inputs['search']['value'] ?? '';

        $columns = [
            0 => 'ha.id',
            1 => 'ha.name',
            2 => 'ha.email',
            3 => 'ha.phone',
            4 => 'h.hotel_name',
            5 => 'ha.status',
            6 => 'ha.created_at'
        ];

        $orderIndex = $inputs['order'][0]['column'] ?? 0;
        $order = $columns[$orderIndex] ?? 'ha.id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTHotelAdmins($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $checked = ((int)$row->status === 1) ? 'checked' : '';

            $data[] = [
                $i++,
                htmlspecialchars($row->name ?? ''),
                htmlspecialchars($row->email ?? ''),
                htmlspecialchars($row->phone ?? ''),
                htmlspecialchars($row->hotel_name ?? '-'),
                '<label class="switch">
                    <input type="checkbox" class="status-toggle" data-record_id="'.$encrypted.'" '.$checked.'>
                    <span class="slider round"></span>
                </label>',
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTHotelAdminsAll(),
            'recordsFiltered' => $this->objdt->DTHotelAdminsFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    /**
     * Insert Hotel Admin
     */
    public function insert()
    {
        $validated = $this->validateHotelAdminPayload(true);
        if (!empty($validated['errors'])) {
            echo json_encode([
                'status' => false,
                'message' => 'Please correct the highlighted fields',
                'errors' => $validated['errors'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $payload = $validated['data'];

        // Hash password securely
        $hashed_password = md5($payload['password']);

        $insertData = array(
            'hotel_id'   => $payload['hotel_id'],
            'name'       => $payload['name'],
            'email'      => $payload['email'],
            'phone'      => $payload['phone'],
            'password'   => $hashed_password,
            'is_primary' => 0,
            'status'     => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $record_id = $this->Comman_model->insertData('hotel_admins', $insertData);

        if ($record_id) {
            $this->logActivity(
                'create',
                $record_id,
                "Created hotel admin {$payload['name']} ({$payload['email']}) for hotel ID {$payload['hotel_id']}"
            );
        }

        echo json_encode([
            'status' => (bool)$record_id,
            'message' => $record_id ? 'Hotel admin added successfully' : 'Failed to add hotel admin',
            'record_id' => $record_id ? encrypt_id($record_id) : '',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    /**
     * Edit Hotel Admin
     */
    public function edit()
    {
        $id = decrypt_id($this->input->post('id'));
        if (empty($id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid hotel admin selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $result = $this->Common_model->getdata('hotel_admins', [
            'id' => $id,
            'is_deleted' => 0
        ]);
        if (empty($result)) {
            echo json_encode([
                'status' => false,
                'message' => 'Hotel admin not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $hotel = $this->Common_model->getdata('hotel_admin', [
            'hotel_id' => $result->hotel_id,
            'is_deleted' => 0
        ]);
        unset($result->password);
        $result->hotel_id = !empty($hotel) ? encrypt_id($result->hotel_id) : '';
        $result->hotel_name = $hotel->hotel_name ?? '';
        $result->hotel_unavailable = empty($hotel);

        echo json_encode([
            'status' => true,
            'data' => $result,
            'id' => encrypt_id($id),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    /**
     * Update Hotel Admin
     */
    public function update()
    {
        $record_id = decrypt_id($this->input->post('record_id'));
        if (empty($record_id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid hotel admin selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $existingHotelAdmin = $this->Common_model->getdata('hotel_admins', [
            'id' => $record_id,
            'is_deleted' => 0
        ]);

        if (empty($existingHotelAdmin)) {
            echo json_encode([
                'status' => false,
                'message' => 'Hotel admin not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $validated = $this->validateHotelAdminPayload(false);
        if (!empty($validated['errors'])) {
            echo json_encode([
                'status' => false,
                'message' => 'Please correct the highlighted fields',
                'errors' => $validated['errors'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $payload = $validated['data'];

        $updateData = array(
            'name'       => $payload['name'],
            'email'      => $payload['email'],
            'phone'      => $payload['phone'],
            'hotel_id'   => $payload['hotel_id'],
            'status'     => $payload['status'],
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Update password only if provided
        if ($payload['password'] !== '') {
            $updateData['password'] = md5($payload['password']);
        }

        $updated = $this->Comman_model->UpdateRecord(
            'hotel_admins',
            $updateData,
            [
                'id' => $record_id,
                'is_deleted' => 0
            ]
        );
        $affectedRows = $this->db->affected_rows();

        if (!$updated) {
            echo json_encode([
                'status' => false,
                'message' => 'Unable to update hotel admin',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        if ($affectedRows === 0 && empty($this->Common_model->getdata('hotel_admins', [
            'id' => $record_id,
            'is_deleted' => 0
        ]))) {
            echo json_encode([
                'status' => false,
                'message' => 'Hotel admin not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        if ($affectedRows > 0) {
            $this->logActivity(
                'update',
                $record_id,
                "Updated hotel admin {$updateData['name']} ({$updateData['email']})"
            );
        }

        echo json_encode([
            'status' => true,
            'message' => 'Hotel admin updated successfully',
            'record_id' => encrypt_id($record_id),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function update_status()
    {
        $id     = decrypt_id($this->input->post('id'));
        $status = $this->input->post('status');

        if (!$id || !in_array((string)$status, ['0', '1'], true)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid request',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $hotelAdmin = $this->Common_model->getdata('hotel_admins', [
            'id' => $id,
            'is_deleted' => 0
        ]);

        if (empty($hotelAdmin)) {
            echo json_encode([
                'status' => false,
                'message' => 'Hotel admin not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $updated = $this->Common_model->UpdateRecord(
            'hotel_admins',
            [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => $id,
                'is_deleted' => 0
            ]
        );
        $affectedRows = $this->db->affected_rows();

        if ($updated && $affectedRows === 1) {
            $this->logActivity(
                'status_change',
                $id,
                "Changed hotel admin status to {$status}"
            );

            echo json_encode([
                'status' => true,
                'message' => 'Status updated  successfully',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Failed to update status',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        }
    }



    /**
     * Delete Hotel Admin
     */
    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));
        if (empty($id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid hotel admin selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $where = [
            'id' => $id,
            'is_deleted' => 0
        ];
        $hotelAdmin = $this->Common_model->getdata('hotel_admins', $where);

        if (empty($hotelAdmin)) {
            echo json_encode([
                'status' => false,
                'message' => 'Hotel admin not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $deleteQuery = $this->Comman_model->UpdateRecord(
            'hotel_admins',
            [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            $where
        );
        $deleted = $deleteQuery && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                "Deleted hotel admin {$hotelAdmin->name} ({$hotelAdmin->email})"
            );

            $response = [
                'status' => true,
                'message' => 'Hotel admin deleted successfully',
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        } else {
            $response = [
                'status' => false,
                'message' => $deleteQuery
                    ? 'Hotel admin not found or already deleted'
                    : 'Unable to delete hotel admin',
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        }

        echo json_encode($response);
    }
}

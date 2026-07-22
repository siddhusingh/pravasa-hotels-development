<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('hotel_admin_session'))) {
            redirect('hotel-admin-login');
        }
    }

    private function jsonResponse(array $response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    private function getHotelId()
    {
        $session = $this->session->userdata('hotel_admin_session');
        return (int) ($session['id'] ?? 0);
    }

    private function getAssignedHotel()
    {
        return $this->Common_model->getdata('hotel_admin', [
            'hotel_id' => $this->getHotelId(),
            'status' => 'active',
            'is_deleted' => 0
        ]);
    }

    private function getCurrentActor()
    {
        $actor = $this->session->userdata('hotel_admin_session');
        $hotelId = $this->getHotelId();
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
            'module' => 'staff_members',
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

    private function getDepartments()
    {
        return $this->db
            ->select('department_id, department_name')
            ->from('departments')
            ->where('is_deleted', 0)
            ->order_by('department_name', 'ASC')
            ->get()
            ->result();
    }

    private function getScopedStaff($staffId)
    {
        if (empty($staffId)) {
            return null;
        }

        return $this->db
            ->select('staff_members.*')
            ->from('staff_members')
            ->join('staff_hotel_department_mapping mapping', 'mapping.staff_id = staff_members.id', 'inner')
            ->where('staff_members.id', (int) $staffId)
            ->where('mapping.hotel_id', $this->getHotelId())
            ->where('staff_members.is_deleted', 0)
            ->group_by('staff_members.id')
            ->get()
            ->row();
    }

    private function validateStaffPayload($requirePassword = true)
    {
        $errors = [];
        $name = trim((string) $this->input->post('name'));
        $email = trim((string) $this->input->post('email'));
        $phone = trim((string) $this->input->post('phone'));
        $password = (string) $this->input->post('password');
        $departmentTokens = json_decode((string) $this->input->post('departments'), true);
        $levels = json_decode((string) $this->input->post('levels'), true);
        $passwordPattern = '/^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/';

        if ($name === '' || strlen($name) < 3) {
            $errors['name'] = 'Full name must be at least 3 characters';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email';
        }
        if ($phone === '' || !preg_match('/^[0-9]{10}$/', $phone)) {
            $errors['phone'] = 'Phone number must be 10 digits';
        }
        if ($requirePassword && ($password === '' || !preg_match($passwordPattern, $password))) {
            $errors['password'] = 'Password must be at least 6 characters long, contain at least one number and one special character';
        }
        if (!$requirePassword && $password !== '' && !preg_match($passwordPattern, $password)) {
            $errors['password'] = 'Password must be at least 6 characters long, contain at least one number and one special character';
        }

        if (!is_array($departmentTokens) || !is_array($levels) || empty($departmentTokens) || count($departmentTokens) !== count($levels)) {
            $errors['mapping'] = 'Please select a department and escalation level for each row';
        }

        $departments = [];
        if (empty($errors['mapping'])) {
            foreach ($departmentTokens as $index => $departmentToken) {
                $departmentId = decrypt_id($departmentToken);
                $department = $departmentId ? $this->Common_model->getdata('departments', [
                    'department_id' => $departmentId,
                    'is_deleted' => 0
                ]) : null;

                if (empty($department)) {
                    $errors['mapping'] = 'Selected department is unavailable';
                    break;
                }
                if (!in_array((string) $levels[$index], ['1', '2', '3'], true)) {
                    $errors['mapping'] = 'Invalid escalation level selected';
                    break;
                }
                $departments[] = (int) $departmentId;
            }
        }

        return [
            'errors' => $errors,
            'data' => [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'departments' => $departments,
                'levels' => is_array($levels) ? $levels : []
            ]
        ];
    }

    private function staffListQuery($search = '')
    {
        $this->db
            ->from('staff_members staff')
            ->join('staff_hotel_department_mapping mapping', 'mapping.staff_id = staff.id', 'inner')
            ->where('mapping.hotel_id', $this->getHotelId())
            ->where('staff.is_deleted', 0);

        if ($search !== '') {
            $this->db->group_start()
                ->like('staff.name', $search)
                ->or_like('staff.email', $search)
                ->or_like('staff.phone', $search)
                ->group_end();
        }
    }

    private function countScopedStaff($search = '')
    {
        $this->staffListQuery($search);
        $row = $this->db
            ->select('COUNT(DISTINCT staff.id) AS total', false)
            ->get()
            ->row();

        return (int) ($row->total ?? 0);
    }

    public function index()
    {
        $hotel = $this->getAssignedHotel();
        if (empty($hotel)) {
            show_error('The assigned hotel is inactive or unavailable.', 403);
            return;
        }

        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/managestaff', [
            'hotel' => $hotel,
            'departments' => $this->getDepartments()
        ]);
        $this->load->view('hotel_admin/include/footer');
    }

    public function get_staff_table()
    {
        if (empty($this->getAssignedHotel())) {
            $this->jsonResponse(['draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'status' => false, 'message' => 'The assigned hotel is inactive or unavailable.']);
            return;
        }

        $inputs = (array) $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = max(1, min(100, (int) ($inputs['length'] ?? 10)));
        $search = trim((string) ($inputs['search']['value'] ?? ''));
        $columns = [
            0 => 'staff.id',
            1 => 'staff.name',
            2 => 'staff.email',
            3 => 'staff.phone',
            4 => 'staff.created_at',
            5 => 'staff.updated_at'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'staff.id';
        $direction = strtolower((string) ($inputs['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';

        $this->staffListQuery($search);
        $rows = $this->db
            ->select('staff.id, staff.name, staff.email, staff.phone, staff.created_at, staff.updated_at')
            ->group_by('staff.id')
            ->order_by($order, $direction)
            ->limit($length, $start)
            ->get()
            ->result();

        $data = [];
        $serialNumber = $start + 1;
        foreach ($rows as $row) {
            $encryptedId = encrypt_id($row->id);
            $escapedName = html_escape($row->name);
            $data[] = [
                $serialNumber++,
                $escapedName,
                html_escape($row->email),
                html_escape($row->phone),
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary view-staff" data-record_id="'.$encryptedId.'" data-name="'.$escapedName.'" aria-label="View staff details"><i class="fa fa-eye"></i> View</a>
                <a href="'.base_url('view-leads?staff_id='.urlencode($encryptedId)).'" class="text-fade hover-primary ms-2" aria-label="View leads for '.$escapedName.'"><i class="fa fa-phone"></i> Leads</a>
                <a href="javascript:void(0)" class="text-fade hover-primary edit-staff ms-2" data-record_id="'.$encryptedId.'" aria-label="Edit staff"><i class="fa fa-edit"></i> Edit</a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-staff ms-2" data-record_id="'.$encryptedId.'" aria-label="Delete staff"><i class="fa fa-trash"></i> Delete</a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->countScopedStaff(),
            'recordsFiltered' => $this->countScopedStaff($search),
            'data' => $data
        ]);
    }

    public function insert()
    {
        $hotel = $this->getAssignedHotel();
        if (empty($hotel)) {
            $this->jsonResponse(['status' => false, 'message' => 'The assigned hotel is inactive or unavailable.']);
            return;
        }

        $validated = $this->validateStaffPayload(true);
        if (!empty($validated['errors'])) {
            $this->jsonResponse(['status' => false, 'message' => 'Please correct the highlighted fields', 'errors' => $validated['errors']]);
            return;
        }

        $payload = $validated['data'];
        $now = date('Y-m-d H:i:s');
        $this->db->trans_begin();

        $staffId = $this->Comman_model->insertData('staff_members', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'password' => md5($payload['password']),
            'status' => 1,
            'is_deleted' => 0,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        if ($staffId) {
            foreach ($payload['departments'] as $index => $departmentId) {
                $this->Comman_model->insertData('staff_hotel_department_mapping', [
                    'staff_id' => $staffId,
                    'hotel_id' => (int) $hotel->hotel_id,
                    'department_id' => $departmentId,
                    'level' => (int) $payload['levels'][$index],
                    'created_at' => $now
                ]);
            }
        }

        if (!$staffId || $this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->jsonResponse(['status' => false, 'message' => 'Unable to create staff member.']);
            return;
        }

        $this->db->trans_commit();
        $this->logActivity('create', $staffId, 'Created staff member '.$payload['name'].' for hotel '.$hotel->hotel_name);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Staff member added successfully.',
            'staff_id' => encrypt_id($staffId)
        ]);
    }

    public function get_staff_details()
    {
        $staffId = decrypt_id($this->input->post('staff_id'));
        $staff = $this->getScopedStaff($staffId);
        if (empty($staff)) {
            $this->jsonResponse(['status' => false, 'message' => 'Staff member not found for this hotel.']);
            return;
        }

        $mappings = $this->db
            ->select('mapping.department_id, mapping.level')
            ->from('staff_hotel_department_mapping mapping')
            ->where('mapping.staff_id', (int) $staffId)
            ->where('mapping.hotel_id', $this->getHotelId())
            ->order_by('mapping.id', 'ASC')
            ->get()
            ->result();

        $departmentTokens = [];
        $departmentOptions = [];
        foreach ($this->getDepartments() as $department) {
            $token = encrypt_id($department->department_id);
            $departmentTokens[$department->department_id] = $token;
            $departmentOptions[] = [
                'id' => $token,
                'text' => $department->department_name
            ];
        }
        foreach ($mappings as $mapping) {
            $mapping->department_id = $departmentTokens[$mapping->department_id] ?? null;
        }

        unset($staff->password);
        $staff->id = encrypt_id($staff->id);
        $this->jsonResponse([
            'status' => true,
            'staff' => $staff,
            'mappings' => $mappings,
            'department_options' => $departmentOptions
        ]);
    }

    public function get_mapping_details()
    {
        $staffId = decrypt_id($this->input->post('staff_id'));
        $staff = $this->getScopedStaff($staffId);
        if (empty($staff)) {
            $this->jsonResponse(['status' => false, 'message' => 'Staff member not found for this hotel.']);
            return;
        }

        $mappings = $this->db
            ->select('hotel.hotel_name, departments.department_name, mapping.level')
            ->from('staff_hotel_department_mapping mapping')
            ->join('hotel_admin hotel', 'hotel.hotel_id = mapping.hotel_id', 'left')
            ->join('departments', 'departments.department_id = mapping.department_id', 'left')
            ->where('mapping.staff_id', (int) $staffId)
            ->where('mapping.hotel_id', $this->getHotelId())
            ->get()
            ->result();

        $this->jsonResponse([
            'status' => !empty($mappings),
            'message' => empty($mappings) ? 'No mapping data found' : '',
            'data' => $mappings
        ]);
    }

    public function update()
    {
        $hotel = $this->getAssignedHotel();
        $staffId = decrypt_id($this->input->post('record_id'));
        $staff = $this->getScopedStaff($staffId);
        if (empty($hotel) || empty($staff)) {
            $this->jsonResponse(['status' => false, 'message' => 'Staff member not found for this hotel.']);
            return;
        }

        $validated = $this->validateStaffPayload(false);
        if (!empty($validated['errors'])) {
            $this->jsonResponse(['status' => false, 'message' => 'Please correct the highlighted fields', 'errors' => $validated['errors']]);
            return;
        }

        $payload = $validated['data'];
        $now = date('Y-m-d H:i:s');
        $staffData = [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'updated_at' => $now
        ];
        if ($payload['password'] !== '') {
            $staffData['password'] = md5($payload['password']);
        }

        $this->db->trans_begin();
        $this->Comman_model->UpdateRecord('staff_members', $staffData, ['id' => (int) $staffId, 'is_deleted' => 0]);
        $this->db->where('staff_id', (int) $staffId)->where('hotel_id', (int) $hotel->hotel_id)->delete('staff_hotel_department_mapping');

        foreach ($payload['departments'] as $index => $departmentId) {
            $this->Comman_model->insertData('staff_hotel_department_mapping', [
                'staff_id' => (int) $staffId,
                'hotel_id' => (int) $hotel->hotel_id,
                'department_id' => $departmentId,
                'level' => (int) $payload['levels'][$index],
                'created_at' => $now
            ]);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update staff member.']);
            return;
        }

        $this->db->trans_commit();
        $this->logActivity('update', $staffId, 'Updated staff member '.$payload['name'].' for hotel '.$hotel->hotel_name);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Staff member updated successfully.',
            'staff_id' => encrypt_id($staffId)
        ]);
    }

    public function delete()
    {
        $staffId = decrypt_id($this->input->post('id'));
        $staff = $this->getScopedStaff($staffId);
        if (empty($staff)) {
            $this->jsonResponse(['status' => false, 'message' => 'Staff member not found for this hotel.']);
            return;
        }

        $hotelId = $this->getHotelId();
        $this->db->trans_begin();
        $this->db->where('staff_id', (int) $staffId)->where('hotel_id', $hotelId)->delete('staff_hotel_department_mapping');
        $remainingMappings = $this->db->where('staff_id', (int) $staffId)->count_all_results('staff_hotel_department_mapping');

        if ($remainingMappings === 0) {
            $this->Comman_model->UpdateRecord('staff_members', [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => (int) $staffId, 'is_deleted' => 0]);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->jsonResponse(['status' => false, 'message' => 'Unable to delete staff member.']);
            return;
        }

        $this->db->trans_commit();
        $this->logActivity('delete', $staffId, 'Removed staff member '.$staff->name.' from hotel '.$hotelId);
        $this->jsonResponse(['status' => true, 'message' => 'Staff member deleted successfully.']);
    }
}

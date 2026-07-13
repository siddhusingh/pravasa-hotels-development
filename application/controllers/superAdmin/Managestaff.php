<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class Managestaff extends MY_Controller
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
            'module' => 'staff_members',
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

        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', '');
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/managestaff', $data);
        $this->load->view('super_admin/include/footer');
    }

    private function validateStaffPayload($requirePassword = true)
    {
        $errors = [];
        $name = trim($this->input->post('name'));
        $email = trim($this->input->post('email'));
        $phone = trim($this->input->post('phone'));
        $password = $this->input->post('password');
        $hotels = json_decode($this->input->post('hotels'), true);
        $departments = json_decode($this->input->post('departments'), true);
        $levels = json_decode($this->input->post('levels'), true);

        if ($name === '' || strlen($name) < 3) {
            $errors['name'] = 'Full name must be at least 3 characters';
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

        if (!is_array($hotels) || !is_array($departments) || !is_array($levels)) {
            $errors['mapping'] = 'Invalid mapping data';
        } elseif (empty($hotels) || count($hotels) !== count($departments) || count($hotels) !== count($levels)) {
            $errors['mapping'] = 'Please select hotel, department and level for each row';
        }

        $decryptedHotels = [];
        $decryptedDepartments = [];
        if (empty($errors['mapping'])) {
            foreach ($hotels as $hotelToken) {
                $hotelId = decrypt_id($hotelToken);
                if (empty($hotelId)) {
                    $errors['mapping'] = 'Invalid hotel selected';
                    break;
                }
                if (!$this->Common_model->getdata('hotel_admin', ['hotel_id' => $hotelId])) {
                    $errors['mapping'] = 'Selected hotel does not exist';
                    break;
                }
                $decryptedHotels[] = $hotelId;
            }

            foreach ($departments as $departmentToken) {
                $departmentId = decrypt_id($departmentToken);
                if (empty($departmentId)) {
                    $errors['mapping'] = 'Invalid department selected';
                    break;
                }
                if (!$this->Common_model->getdata('departments', ['department_id' => $departmentId])) {
                    $errors['mapping'] = 'Selected department does not exist';
                    break;
                }
                $decryptedDepartments[] = $departmentId;
            }

            foreach ($levels as $level) {
                if (!in_array((string)$level, ['1', '2', '3'], true)) {
                    $errors['mapping'] = 'Invalid escalation level selected';
                    break;
                }
            }
        }

        return [
            'errors' => $errors,
            'data' => [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'hotels' => $decryptedHotels,
                'departments' => $decryptedDepartments,
                'levels' => $levels ?: []
            ]
        ];
    }

    public function get_staff_table()
    {
        $inputs = $this->input->post();
        $draw = (int)($inputs['draw'] ?? 1);
        $start = max(0, (int)($inputs['start'] ?? 0));
        $length = (int)($inputs['length'] ?? 10);
        $length = $length > 0 ? min($length, 100) : 10;
        $search = $inputs['search']['value'] ?? '';

        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'phone',
            4 => 'created_at',
            5 => 'updated_at'
        ];

        $orderIndex = $inputs['order'][0]['column'] ?? 0;
        $order = $columns[$orderIndex] ?? 'id';
        $dir = strtoupper($inputs['order'][0]['dir'] ?? 'DESC');
        $dir = in_array($dir, ['ASC', 'DESC'], true) ? $dir : 'DESC';

        $list = $this->objdt->DTStaffMembers($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $name = htmlspecialchars($row->name ?? '');

            $data[] = [
                $i++,
                $name,
                htmlspecialchars($row->email ?? ''),
                htmlspecialchars($row->phone ?? ''),
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary view-staff" data-record_id="'.$encrypted.'" data-name="'.$name.'">
                    <i class="fa fa-eye"></i> View
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary edit-staff" data-record_id="'.$encrypted.'">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-staff" data-record_id="'.$encrypted.'">
                    <i class="fa fa-trash"></i> Delete
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTStaffMembersAll(),
            'recordsFiltered' => $this->objdt->DTStaffMembersFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }


    //  insert a audit tracker start here

    public function insert()
    {
        $validated = $this->validateStaffPayload(true);
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
        $name = $payload['name'];
        $email = $payload['email'];
        $phone = $payload['phone'];
        $password = md5($payload['password']);
        $hotels = $payload['hotels'];
        $departments = $payload['departments'];
        $levels = $payload['levels'];

        $created_on = date('Y-m-d H:i:s');

        // Insert into staff_members table
        $staff_data = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'status' => 1,
            'created_at' => $created_on
        );

        $staff_id = $this->Comman_model->insertData('staff_members', $staff_data);

        if (!$staff_id) {
            echo json_encode([
                'status' => false,
                'message' => 'Failed to create staff member.',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $inserted_mappings = [];

        // Insert mapping records for each hotel-department-level combination
        for ($i = 0; $i < count($hotels); $i++) {
            $mapping_data = array(
                'staff_id' => $staff_id,
                'hotel_id' => $hotels[$i],
                'department_id' => $departments[$i],
                'level' => $levels[$i],
                'created_at' => $created_on
            );

            $mapping_id = $this->Comman_model->insertData('staff_hotel_department_mapping', $mapping_data);
            if ($mapping_id) {
                $inserted_mappings[] = $mapping_id;
            }
        }

        // Check if mappings were inserted
        if (count($inserted_mappings) > 0) {
            $this->logActivity(
                'create',
                $staff_id,
                "Created staff member {$name} ({$email}) with " . count($inserted_mappings) . " mapping(s)."
            );

            echo json_encode([
                'status' => true,
                'message' => 'New staff member and mappings added successfully.',
                'staff_id' => encrypt_id($staff_id),
                'mapping_ids' => array_map('encrypt_id', $inserted_mappings),
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        } else {
            $this->logActivity(
                'create',
                $staff_id,
                "Created staff member {$name} ({$email}) but no mappings were inserted."
            );

            echo json_encode([
                'status' => false,
                'message' => 'Staff created, but mappings failed.',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        }
    }






    public function update()
    {
        $record_id = decrypt_id($this->input->post('record_id'));
        if (empty($record_id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid staff member selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $validated = $this->validateStaffPayload(false);
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
        $name = $payload['name'];
        $email = $payload['email'];
        $phone = $payload['phone'];
        $password_raw = $payload['password'];
        $hotels = $payload['hotels'];
        $departments = $payload['departments'];
        $levels = $payload['levels'];
        $updated_on = date('Y-m-d H:i:s');

        // Build staff update data
        $staff_data = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'updated_at' => $updated_on
        );

        if (!empty($password_raw)) {
            $staff_data['password'] = md5($password_raw); // Hash only if password is sent
        }

        // Update staff_members table
        $update_success = $this->Comman_model->UpdateRecord('staff_members', $staff_data, ['id' => $record_id]);

        if (!$update_success) {
            $existing = $this->Common_model->getdata('staff_members', ['id' => $record_id]);
            if (empty($existing)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to update staff member.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
                return;
            }
        }

        $this->logActivity(
            'update',
            $record_id,
            "Updated staff member {$name} ({$email})"
        );

        // Delete old mappings
        $this->db->where('staff_id', $record_id);
        $this->db->delete('staff_hotel_department_mapping');

        // Insert new mappings
        $inserted_mappings = [];
        for ($i = 0; $i < count($hotels); $i++) {
            $mapping_data = array(
                'staff_id' => $record_id,
                'hotel_id' => $hotels[$i],
                'department_id' => $departments[$i],
                'level' => $levels[$i],
                'created_at' => $updated_on
            );

            $mapping_id = $this->Comman_model->insertData('staff_hotel_department_mapping', $mapping_data);
            if ($mapping_id) {
                $inserted_mappings[] = $mapping_id;
            }
        }

        // Return response
        if (count($inserted_mappings) > 0) {
            echo json_encode([
                'status' => true,
                'message' => 'Staff updated successfully with new mappings.',
                'staff_id' => encrypt_id($record_id),
                'mapping_ids' => array_map('encrypt_id', $inserted_mappings),
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'status' => true,
                'message' => 'Staff updated, but no mappings inserted.',
                'staff_id' => encrypt_id($record_id),
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        }
    }


    public function delete()
    {

        // $this->check_login();

        $id = decrypt_id($this->input->post('id'));
        if (empty($id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid staff member selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $staff = $this->Common_model->getdata('staff_members', ['id' => $id]);

        $table = 'staff_members';
        $where = array('id' => $id);

        $data = $this->Comman_model->Deletedata('staff_members', array('id' => $id));

        //   $data = $this->Comman_model->Deletedata('staff_members', array('id' => $id));



        if ($data) {
            $this->logActivity(
                'delete',
                $id,
                $staff ? "Deleted staff member {$staff->name} ({$staff->email})" : "Deleted staff member record {$id}"
            );

            $response['status'] = true;
            $response['message'] = "staff_members Deleted successfully";
        } else {
            $response['status'] = false;
            $response['message'] = "Something Went wrong";
        }
        $response['csrfHash'] = $this->security->get_csrf_hash();

        echo json_encode($response);
    }


    public function get_mapping_details()
    {
        $staff_id = decrypt_id($this->input->post('staff_id'));

        if (!$staff_id) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid Staff ID',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Fetch mapping data with hotel & department names
        $this->db->select('h.hotel_name, d.department_name, m.level');
        $this->db->from('staff_hotel_department_mapping m');
        $this->db->join('hotel_admin h', 'm.hotel_id = h.hotel_id ', 'left');
        $this->db->join('departments d', 'm.department_id = d.department_id', 'left');
        $this->db->where('m.staff_id', $staff_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            echo json_encode([
                'status' => true,
                'data' => $query->result(),
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'No mapping data found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        }
    }

    // Fetch staff details for edit modal
    public function get_staff_details()
    {
        $staff_id = decrypt_id($this->input->post('staff_id'));
        if (empty($staff_id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid Staff ID',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }




        // Get staff basic details

        $staff = $this->Common_model->getdata('staff_members', array('id' => $staff_id));


        // Get staff mappings (    hotels, departments, levels)
        //$mappings = $this->Staff_model->get_staff_mappings($staff_id);
        $mappings = $this->Common_model->getAllData('staff_hotel_department_mapping', array('staff_id' => $staff_id));


        if (!empty($staff)) {
            unset($staff->password);
            $staff->id = encrypt_id($staff->id);
        }

        foreach ($mappings as $mapping) {
            $mapping->hotel_id = encrypt_id($mapping->hotel_id);
            $mapping->department_id = encrypt_id($mapping->department_id);
        }

        echo json_encode([
            'status' => true,
            'staff' => $staff,
            'mappings' => $mappings,
            'id' => encrypt_id($staff_id),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesUsers extends MY_Controller
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

    public function index()
    {
        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', '');
        $data['states'] = $this->Common_model->getAllData('state', '');
        $data['countries'] = $this->Common_model->getAllData('country', '');
        $data['team_groups'] = $this->Common_model->getAllData('team_groups', '');
        $data['team_group'] = $data['team_groups'];
        $data['cities'] = $this->Comman_model->getcityData();

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/salesUsers', $data);
        $this->load->view('super_admin/include/footer');
    }

    private function decryptCsvIds($value)
    {
        $ids = [];
        foreach (array_filter(explode(',', (string)$value)) as $token) {
            $id = decrypt_id(trim($token));
            if (!empty($id)) {
                $ids[] = $id;
            }
        }

        return implode(',', $ids);
    }

    private function encryptCsvIds($value)
    {
        $ids = [];
        foreach (array_filter(explode(',', (string)$value)) as $id) {
            $ids[] = encrypt_id(trim($id));
        }

        return implode(',', $ids);
    }

    private function labelsForCsv($table, $idColumn, $labelColumn, $csv)
    {
        $ids = array_filter(explode(',', (string)$csv));
        if (empty($ids)) {
            return [];
        }

        $rows = $this->db->select($idColumn.', '.$labelColumn)
            ->where_in($idColumn, $ids)
            ->get($table)
            ->result();

        $labels = [];
        foreach ($rows as $row) {
            $labels[] = [
                'id' => encrypt_id($row->{$idColumn}),
                'label' => $row->{$labelColumn}
            ];
        }

        return $labels;
    }

    private function labelsTextForCsv($table, $idColumn, $labelColumn, $csv)
    {
        $labels = $this->labelsForCsv($table, $idColumn, $labelColumn, $csv);
        if (empty($labels)) {
            return '-';
        }

        return implode(', ', array_column($labels, 'label'));
    }

    private function csvIdsExist($table, $idColumn, $csv)
    {
        $ids = array_values(array_unique(array_filter(explode(',', (string)$csv))));
        if (empty($ids)) {
            return false;
        }

        $count = $this->db->where_in($idColumn, $ids)->count_all_results($table);
        return $count === count($ids);
    }

    private function validatePayload($requirePassword = true)
    {
        $errors = [];
        $fullName = trim($this->input->post('full_name'));
        $email = trim($this->input->post('email'));
        $phone = trim($this->input->post('phone'));
        $password = $this->input->post('password');
        $userRole = $this->input->post('user_role');
        $hotelTokens = array_filter(explode(',', (string)$this->input->post('hotel_id')));
        $teamGroupTokens = array_filter(explode(',', (string)$this->input->post('team_group')));
        $assignedHotels = $this->decryptCsvIds(implode(',', $hotelTokens));
        $teamGroup = $this->decryptCsvIds(implode(',', $teamGroupTokens));
        $assignedHotelIds = array_filter(explode(',', $assignedHotels));
        $teamGroupIds = array_filter(explode(',', $teamGroup));
        $city = decrypt_id($this->input->post('city'));
        $stateId = decrypt_id($this->input->post('state_id'));
        $zipcode = trim($this->input->post('zipcode'));
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

        if (!in_array($userRole, ['RSO', 'Sales Manager', 'Sales Executive'], true)) {
            $errors['user_role'] = 'Please select a valid user role';
        }

        if ($assignedHotels === '') {
            $errors['hotel_id'] = 'Please select at least one hotel';
        } elseif (count($assignedHotelIds) !== count($hotelTokens)) {
            $errors['hotel_id'] = 'Invalid hotel selected';
        } elseif (!$this->csvIdsExist('hotel_admin', 'hotel_id', $assignedHotels)) {
            $errors['hotel_id'] = 'Please select valid hotels';
        }

        if ($teamGroup === '') {
            $errors['team_group'] = 'Please select at least one team group';
        } elseif (count($teamGroupIds) !== count($teamGroupTokens)) {
            $errors['team_group'] = 'Invalid team group selected';
        } elseif (!$this->csvIdsExist('team_groups', 'id', $teamGroup)) {
            $errors['team_group'] = 'Please select valid team groups';
        }

        if (empty($city)) {
            $errors['city'] = 'Please select city';
        } elseif (!$this->Common_model->getdata('city', ['city_id' => $city])) {
            $errors['city'] = 'Please select a valid city';
        }

        if (empty($stateId)) {
            $errors['state_id'] = 'Please select state';
        } elseif (!$this->Common_model->getdata('state', ['state_id' => $stateId])) {
            $errors['state_id'] = 'Please select a valid state';
        }

        if (!empty($city) && !empty($stateId)) {
            $cityState = $this->Common_model->getdata('city', ['city_id' => $city, 'state_id' => $stateId]);
            if (empty($cityState)) {
                $errors['city'] = 'Selected city does not belong to the selected state';
            }
        }

        if ($zipcode !== '' && !preg_match('/^[0-9]{4,10}$/', $zipcode)) {
            $errors['zipcode'] = 'Please enter a valid zip code';
        }

        if (!in_array((string)$status, ['0', '1'], true)) {
            $errors['status'] = 'Please select a valid status';
        }

        return [
            'errors' => $errors,
            'data' => [
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'user_role' => $userRole,
                'assigned_hotels' => $assignedHotels,
                'team_group' => $teamGroup,
                'city_id' => $city,
                'state_id' => $stateId,
                'zipcode' => $zipcode,
                'company' => trim($this->input->post('company')),
                'address' => trim($this->input->post('address')),
                'status' => (int)$status
            ]
        ];
    }

    public function get_sales_users_table()
    {
        $inputs = $this->input->post();
        $draw = (int)($inputs['draw'] ?? 1);
        $start = max(0, (int)($inputs['start'] ?? 0));
        $length = (int)($inputs['length'] ?? 10);
        $length = $length > 0 ? min($length, 100) : 10;
        $search = $inputs['search']['value'] ?? '';

        $columns = [
            0 => 'su.id',
            1 => 'su.full_name',
            2 => 'su.email',
            3 => 'su.phone',
            4 => 'su.user_role',
            7 => 'city.city_name',
            8 => 'state.state_name',
            9 => 'su.status',
            10 => 'su.created_at',
            11 => 'su.updated_at'
        ];

        $orderIndex = $inputs['order'][0]['column'] ?? 0;
        $order = $columns[$orderIndex] ?? 'su.id';
        $dir = strtoupper($inputs['order'][0]['dir'] ?? 'DESC');
        $dir = in_array($dir, ['ASC', 'DESC'], true) ? $dir : 'DESC';

        $list = $this->objdt->DTSalesUsers($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $status = ((int)$row->status === 1)
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $data[] = [
                $i++,
                htmlspecialchars($row->full_name ?? ''),
                htmlspecialchars($row->email ?? ''),
                htmlspecialchars($row->phone ?? ''),
                htmlspecialchars($row->user_role ?? ''),
                htmlspecialchars($this->labelsTextForCsv('team_groups', 'id', 'team_group_name', $row->team_group)),
                htmlspecialchars($this->labelsTextForCsv('hotel_admin', 'hotel_id', 'hotel_name', $row->assigned_hotels)),
                htmlspecialchars($row->city_name ?? '-'),
                htmlspecialchars($row->state_name ?? '-'),
                $status,
                !empty($row->created_at) ? date('d-m-Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d-m-Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-danger delete" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTSalesUsersAll(),
            'recordsFiltered' => $this->objdt->DTSalesUsersFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function insert()
    {
        $validated = $this->validatePayload(true);
        if (!empty($validated['errors'])) {
            echo json_encode([
                'status' => false,
                'message' => 'Please correct the highlighted fields',
                'errors' => $validated['errors'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $data = $validated['data'];
        $password = $data['password'];
        unset($data['password']);
        $data['password'] = md5($password);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $recordId = $this->Comman_model->insertData('sales_users', $data);

        echo json_encode([
            'status' => (bool)$recordId,
            'message' => $recordId ? 'Sales user has been added successfully' : 'Failed to add sales user',
            'record_id' => $recordId ? encrypt_id($recordId) : '',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function edit()
    {
        $id = decrypt_id($this->input->post('id'));
        if (empty($id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid sales user selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $result = $this->Common_model->getdata('sales_users', ['id' => $id]);
        if (empty($result)) {
            echo json_encode([
                'status' => false,
                'message' => 'Sales user not found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        unset($result->password);
        $city = $this->Common_model->getdata('city', ['city_id' => $result->city_id]);
        $state = $this->Common_model->getdata('state', ['state_id' => $result->state_id]);

        $result->id = encrypt_id($result->id);
        $result->team_group = $this->encryptCsvIds($result->team_group);
        $result->assigned_hotels = $this->encryptCsvIds($result->assigned_hotels);
        $result->city_id = encrypt_id($result->city_id);
        $result->state_id = encrypt_id($result->state_id);
        $result->selected_team_groups = $this->labelsForCsv('team_groups', 'id', 'team_group_name', $this->Common_model->getdata('sales_users', ['id' => $id])->team_group);
        $result->selected_hotels = $this->labelsForCsv('hotel_admin', 'hotel_id', 'hotel_name', $this->Common_model->getdata('sales_users', ['id' => $id])->assigned_hotels);
        $result->city_name = $city->city_name ?? '';
        $result->state_name = $state->state_name ?? '';

        echo json_encode([
            'status' => true,
            'data' => $result,
            'id' => encrypt_id($id),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function update()
    {
        $recordId = decrypt_id($this->input->post('record_id'));
        if (empty($recordId)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid sales user selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $validated = $this->validatePayload(false);
        if (!empty($validated['errors'])) {
            echo json_encode([
                'status' => false,
                'message' => 'Please correct the highlighted fields',
                'errors' => $validated['errors'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $data = $validated['data'];
        $password = $data['password'];
        unset($data['password']);
        if ($password !== '') {
            $data['password'] = md5($password);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');

        $update = $this->Comman_model->UpdateRecord('sales_users', $data, ['id' => $recordId]);

        echo json_encode([
            'status' => (bool)$update,
            'message' => $update ? 'Sales user has been updated successfully' : 'No changes detected or update failed',
            'record_id' => encrypt_id($recordId),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));
        if (empty($id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid sales user selected',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $deleted = $this->Comman_model->Deletedata('sales_users', ['id' => $id]);
        echo json_encode([
            'status' => (bool)$deleted,
            'message' => $deleted ? 'Sales user deleted successfully' : 'Something went wrong',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }
}

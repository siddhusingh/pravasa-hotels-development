<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesUsers extends MY_Controller
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
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    private function getHotelId()
    {
        $session = $this->session->userdata('hotel_admin_session');
        return (int) ($session['id'] ?? 0);
    }

    private function getHotel()
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
        return [
            'id' => $actor['id'] ?? null,
            'name' => $actor['user_name'] ?? '',
            'email' => $actor['email'] ?? '',
            'role' => $this->session->userdata('role_as') ?? 'admin'
        ];
    }

    private function logActivity($action, $recordId, $details = '')
    {
        $actor = $this->getCurrentActor();
        $this->Common_model->insertActivityLog([
            'module' => 'hotel_sales_users',
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

    private function decryptCsvIds($value)
    {
        $ids = [];
        foreach (array_filter(explode(',', (string) $value)) as $token) {
            $id = decrypt_id(trim($token));
            if (!empty($id)) {
                $ids[] = (int) $id;
            }
        }
        return implode(',', array_values(array_unique($ids)));
    }

    private function labelsForCsv($table, $idColumn, $labelColumn, $csv)
    {
        $ids = array_filter(explode(',', (string) $csv));
        if (empty($ids)) {
            return [];
        }
        $rows = $this->db->select($idColumn.', '.$labelColumn)
            ->where_in($idColumn, $ids)->where('is_deleted', 0)->get($table)->result();
        $labels = [];
        foreach ($rows as $row) {
            $labels[] = ['id' => encrypt_id($row->{$idColumn}), 'label' => $row->{$labelColumn}];
        }
        return $labels;
    }

    private function labelsTextForCsv($table, $idColumn, $labelColumn, $csv)
    {
        $labels = $this->labelsForCsv($table, $idColumn, $labelColumn, $csv);
        return empty($labels) ? '-' : implode(', ', array_column($labels, 'label'));
    }

    private function csvIdsExist($table, $idColumn, $csv)
    {
        $ids = array_values(array_unique(array_filter(explode(',', (string) $csv))));
        if (empty($ids)) {
            return false;
        }
        return (int) $this->db->where_in($idColumn, $ids)->where('is_deleted', 0)
            ->count_all_results($table) === count($ids);
    }

    private function scopedSalesUser($id)
    {
        if (empty($id)) {
            return null;
        }
        return $this->db->from('sales_users su')->where('su.id', (int) $id)
            ->where('su.is_deleted', 0)
            ->where('FIND_IN_SET('.$this->getHotelId().", REPLACE(su.assigned_hotels, ' ', '')) >", 0, false)
            ->get()->row();
    }

    private function validatePayload($requirePassword = true, $recordId = null)
    {
        $errors = [];
        $fullName = trim((string) $this->input->post('full_name'));
        $email = trim((string) $this->input->post('email'));
        $phone = trim((string) $this->input->post('phone'));
        $password = (string) $this->input->post('password');
        $userRole = (string) $this->input->post('user_role');
        $teamGroupTokens = array_filter(explode(',', (string) $this->input->post('team_group')));
        $teamGroup = $this->decryptCsvIds(implode(',', $teamGroupTokens));
        $city = decrypt_id($this->input->post('city'));
        $stateId = decrypt_id($this->input->post('state_id'));
        $zipcode = trim((string) $this->input->post('zipcode'));
        $status = (string) $this->input->post('status');

        if ($fullName === '' || strlen($fullName) < 3) $errors['full_name'] = 'Full name must be at least 3 characters';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email';
        if ($phone === '' || !preg_match('/^[0-9]{10}$/', $phone)) $errors['phone'] = 'Phone number must be 10 digits';
        if ($requirePassword && ($password === '' || strlen($password) < 6)) $errors['password'] = 'Password must be at least 6 characters';
        if (!$requirePassword && $password !== '' && strlen($password) < 6) $errors['password'] = 'Password must be at least 6 characters';
        if (!in_array($userRole, ['RSO', 'Sales Manager', 'Sales Executive'], true)) $errors['user_role'] = 'Please select a valid user role';

        if ($teamGroup === '') {
            $errors['team_group'] = 'Please select at least one team group';
        } elseif (count(array_filter(explode(',', $teamGroup))) !== count($teamGroupTokens) || !$this->csvIdsExist('team_groups', 'id', $teamGroup)) {
            $errors['team_group'] = 'Please select active team groups';
        }

        if (empty($city) || !$this->Common_model->getdata('city', ['city_id' => $city, 'is_deleted' => 0])) $errors['city'] = 'Please select an active city';
        if (empty($stateId) || !$this->Common_model->getdata('state', ['state_id' => $stateId, 'is_deleted' => 0])) $errors['state_id'] = 'Please select an active state';
        if (!empty($city) && !empty($stateId) && !$this->Common_model->getdata('city', ['city_id' => $city, 'state_id' => $stateId, 'is_deleted' => 0])) {
            $errors['city'] = 'Selected city does not belong to the selected state';
        }
        if ($zipcode !== '' && !preg_match('/^[0-9]{4,10}$/', $zipcode)) $errors['zipcode'] = 'Please enter a valid zip code';
        if (!in_array($status, ['0', '1'], true)) $errors['status'] = 'Please select a valid status';

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->db->from('sales_users')->where('email', $email)->where('is_deleted', 0);
            if (!empty($recordId)) $this->db->where('id !=', (int) $recordId);
            if ($this->db->count_all_results() > 0) $errors['email'] = 'This email is already in use';
        }

        return ['errors' => $errors, 'data' => [
            'full_name' => $fullName, 'email' => $email, 'phone' => $phone, 'password' => $password,
            'user_role' => $userRole, 'team_group' => $teamGroup, 'city_id' => $city,
            'state_id' => $stateId, 'zipcode' => $zipcode, 'company' => trim((string) $this->input->post('company')),
            'address' => trim((string) $this->input->post('address')), 'status' => (int) $status
        ]];
    }

    private function listQuery($search = '')
    {
        $this->db->from('sales_users su')->join('city', 'city.city_id = su.city_id', 'left')
            ->join('state', 'state.state_id = su.state_id', 'left')->where('su.is_deleted', 0)
            ->where('FIND_IN_SET('.$this->getHotelId().", REPLACE(su.assigned_hotels, ' ', '')) >", 0, false);
        if ($search !== '') {
            $this->db->group_start()->like('su.full_name', $search)->or_like('su.email', $search)
                ->or_like('su.phone', $search)->or_like('su.user_role', $search)
                ->or_like('city.city_name', $search)->or_like('state.state_name', $search)->group_end();
        }
    }

    private function countRows($search = '')
    {
        $this->listQuery($search);
        return (int) $this->db->count_all_results();
    }

    public function index()
    {
        $hotel = $this->getHotel();
        if (empty($hotel)) {
            show_error('The assigned hotel is inactive or unavailable.', 403);
            return;
        }
        $data = [
            'hotels' => [$hotel],
            'states' => $this->Common_model->getAllData('state', ['is_deleted' => 0]),
            'countries' => $this->Common_model->getAllData('country', ['is_deleted' => 0]),
            'team_groups' => $this->Common_model->getAllData('team_groups', ['is_deleted' => 0]),
            'cities' => $this->Common_model->getAllData('city', ['is_deleted' => 0]),
            'sales_user_context' => 'hotel_admin',
            'sales_user_role_label' => 'Hotel Admin',
            'sales_user_page_title' => 'Manage Sales Executives',
            'sales_user_fixed_hotel' => encrypt_id($hotel->hotel_id),
            'sales_user_routes' => [
                'table' => 'hotel-admin/get-sales-users-table', 'insert' => 'hotel-admin/insert-sales-user',
                'edit' => 'hotel-admin/edit-sales-user', 'update' => 'hotel-admin/update-sales-user',
                'delete' => 'hotel-admin/delete-sales-user'
            ]
        ];
        $data['team_group'] = $data['team_groups'];
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('super_admin/salesUsers', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    public function get_sales_users_table()
    {
        if (empty($this->getHotel())) {
            $this->jsonResponse(['draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'status' => false, 'message' => 'The assigned hotel is inactive or unavailable.']);
            return;
        }
        $inputs = (array) $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0); $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = max(1, min(100, (int) ($inputs['length'] ?? 10))); $search = trim((string) ($inputs['search']['value'] ?? ''));
        $columns = [0 => 'su.id', 1 => 'su.full_name', 2 => 'su.email', 3 => 'su.phone', 4 => 'su.user_role', 7 => 'city.city_name', 8 => 'state.state_name', 9 => 'su.status', 10 => 'su.created_at', 11 => 'su.updated_at'];
        $order = $columns[(int) ($inputs['order'][0]['column'] ?? 0)] ?? 'su.id';
        $direction = strtolower((string) ($inputs['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
        $this->listQuery($search);
        $rows = $this->db->select('su.*, city.city_name, state.state_name')->order_by($order, $direction)->limit($length, $start)->get()->result();
        $data = []; $number = $start + 1; $hotel = $this->getHotel();
        foreach ($rows as $row) {
            $id = encrypt_id($row->id);
            $data[] = [$number++, html_escape($row->full_name), html_escape($row->email), html_escape($row->phone), html_escape($row->user_role),
                html_escape($this->labelsTextForCsv('team_groups', 'id', 'team_group_name', $row->team_group)), html_escape($hotel->hotel_name),
                html_escape($row->city_name ?? '-'), html_escape($row->state_name ?? '-'),
                (int) $row->status === 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>',
                !empty($row->created_at) ? date('d-m-Y', strtotime($row->created_at)) : '-', !empty($row->updated_at) ? date('d-m-Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit" data-record_id="'.$id.'" aria-label="Edit sales user"><i class="fa fa-edit"></i> Edit</a> <a href="javascript:void(0)" class="text-fade hover-danger delete" data-record_id="'.$id.'" aria-label="Remove sales user from this hotel"><i class="fa fa-trash"></i> Delete</a>'];
        }
        $this->jsonResponse(['draw' => $draw, 'recordsTotal' => $this->countRows(), 'recordsFiltered' => $this->countRows($search), 'data' => $data]);
    }

    public function insert()
    {
        $hotel = $this->getHotel();
        if (empty($hotel)) { $this->jsonResponse(['status' => false, 'message' => 'The assigned hotel is inactive or unavailable.']); return; }
        $validated = $this->validatePayload(true);
        if (!empty($validated['errors'])) { $this->jsonResponse(['status' => false, 'message' => 'Please correct the highlighted fields', 'errors' => $validated['errors']]); return; }
        $data = $validated['data']; $password = $data['password']; unset($data['password']);
        $data['password'] = md5($password); $data['assigned_hotels'] = (string) $hotel->hotel_id;
        $data['is_deleted'] = 0; $data['created_at'] = date('Y-m-d H:i:s'); $data['updated_at'] = $data['created_at'];
        $id = $this->Comman_model->insertData('sales_users', $data);
        if ($id) $this->logActivity('create', $id, 'Created sales user '.$data['full_name'].' for '.$hotel->hotel_name);
        $this->jsonResponse(['status' => (bool) $id, 'message' => $id ? 'Sales user has been added successfully' : 'Failed to add sales user', 'record_id' => $id ? encrypt_id($id) : '']);
    }

    public function edit()
    {
        $id = decrypt_id($this->input->post('id')); $result = $this->scopedSalesUser($id);
        if (empty($result)) { $this->jsonResponse(['status' => false, 'message' => 'Sales user not found for this hotel']); return; }
        unset($result->password);
        $originalTeamGroup = $result->team_group;
        $city = $this->Common_model->getdata('city', ['city_id' => $result->city_id, 'is_deleted' => 0]);
        $state = $this->Common_model->getdata('state', ['state_id' => $result->state_id, 'is_deleted' => 0]);
        $hotel = $this->getHotel();
        $result->id = encrypt_id($result->id); $result->team_group = implode(',', array_column($this->labelsForCsv('team_groups', 'id', 'team_group_name', $originalTeamGroup), 'id'));
        $result->city_id = $city ? encrypt_id($city->city_id) : ''; $result->state_id = $state ? encrypt_id($state->state_id) : '';
        $result->selected_team_groups = $this->labelsForCsv('team_groups', 'id', 'team_group_name', $originalTeamGroup);
        $result->selected_hotels = [['id' => encrypt_id($hotel->hotel_id), 'label' => $hotel->hotel_name]];
        $result->assigned_hotels = encrypt_id($hotel->hotel_id); $result->city_name = $city->city_name ?? ''; $result->state_name = $state->state_name ?? '';
        $result->has_unavailable_assignments = empty($city) || empty($state);
        $this->jsonResponse(['status' => true, 'data' => $result, 'id' => encrypt_id($id)]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('record_id')); $existing = $this->scopedSalesUser($id);
        if (empty($existing)) { $this->jsonResponse(['status' => false, 'message' => 'Sales user not found for this hotel']); return; }
        $validated = $this->validatePayload(false, $id);
        if (!empty($validated['errors'])) { $this->jsonResponse(['status' => false, 'message' => 'Please correct the highlighted fields', 'errors' => $validated['errors']]); return; }
        $data = $validated['data']; $password = $data['password']; unset($data['password']);
        if ($password !== '') $data['password'] = md5($password);
        $data['assigned_hotels'] = $existing->assigned_hotels; $data['updated_at'] = date('Y-m-d H:i:s');
        $updated = $this->Comman_model->UpdateRecord('sales_users', $data, ['id' => (int) $id, 'is_deleted' => 0]);
        if ($updated) $this->logActivity('update', $id, 'Updated sales user '.$data['full_name'].' for hotel '.$this->getHotelId());
        $this->jsonResponse(['status' => (bool) $updated, 'message' => $updated ? 'Sales user has been updated successfully' : 'Unable to update sales user']);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id')); $salesUser = $this->scopedSalesUser($id);
        if (empty($salesUser)) { $this->jsonResponse(['status' => false, 'message' => 'Sales user not found for this hotel']); return; }
        $hotelId = $this->getHotelId();
        $remaining = array_values(array_filter(array_map('trim', explode(',', (string) $salesUser->assigned_hotels)), function ($assignedId) use ($hotelId) { return (int) $assignedId !== $hotelId; }));
        $data = ['updated_at' => date('Y-m-d H:i:s')];
        if (empty($remaining)) $data['is_deleted'] = 1; else $data['assigned_hotels'] = implode(',', $remaining);
        $deleted = $this->Comman_model->UpdateRecord('sales_users', $data, ['id' => (int) $id, 'is_deleted' => 0]);
        if ($deleted) $this->logActivity('delete', $id, 'Removed sales user '.$salesUser->full_name.' from hotel '.$hotelId);
        $this->jsonResponse(['status' => (bool) $deleted, 'message' => $deleted ? 'Sales user removed from this hotel successfully' : 'Unable to remove sales user']);
    }
}

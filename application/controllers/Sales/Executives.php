<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Sales_Controller.php';

class Executives extends Sales_Controller
{
    private const EXECUTIVE_ROLE = 'Sales Executive';

    public function __construct()
    {
        parent::__construct();
        $this->requireSalesRole(['Sales Manager']);
        $this->load->model('Common_model');
        $this->load->helper('secure');
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index()
    {
        $data['hotels'] = $this->Common_model->getAllData(
            'hotel_admin',
            ['is_deleted' => 0]
        );
        $data['states'] = $this->Common_model->getAllData(
            'state',
            ['is_deleted' => 0]
        );
        $data['cities'] = $this->Common_model->getAllData(
            'city',
            ['is_deleted' => 0]
        );
        $data['team_groups'] = $this->Common_model->getAllData(
            'team_groups',
            ['is_deleted' => 0]
        );

        $this->renderSalesPage('sales/executives/index', $data);
    }

    public function table()
    {
        $this->requirePost();

        $inputs = $this->input->post();
        $draw = (int)($inputs['draw'] ?? 1);
        $start = max(0, (int)($inputs['start'] ?? 0));
        $length = (int)($inputs['length'] ?? 10);
        $length = $length > 0 ? min($length, 100) : 10;
        $search = trim((string)($inputs['search']['value'] ?? ''));
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
        $orderIndex = (int)($inputs['order'][0]['column'] ?? 0);
        $orderColumn = $columns[$orderIndex] ?? 'su.id';
        $orderDirection = strtoupper(
            (string)($inputs['order'][0]['dir'] ?? 'DESC')
        );
        if (!in_array($orderDirection, ['ASC', 'DESC'], true)) {
            $orderDirection = 'DESC';
        }

        $recordsTotal = (int)$this->db
            ->where('user_role', self::EXECUTIVE_ROLE)
            ->where('is_deleted', 0)
            ->count_all_results('sales_users');

        $this->executiveTableQuery($search);
        $recordsFiltered = (int)$this->db->count_all_results();

        $this->executiveTableQuery($search);
        $rows = $this->db
            ->select(
                'su.*, city.city_name, state.state_name'
            )
            ->order_by($orderColumn, $orderDirection)
            ->limit($length, $start)
            ->get()
            ->result();

        $teamGroupLabels = $this->labelsMap(
            'team_groups',
            'id',
            'team_group_name'
        );
        $hotelLabels = $this->labelsMap(
            'hotel_admin',
            'hotel_id',
            'hotel_name'
        );
        $tableData = [];

        foreach ($rows as $index => $row) {
            $encryptedId = html_escape(encrypt_id($row->id));
            $status = (int)$row->status === 1
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';
            $actions =
                '<a href="javascript:void(0)" ' .
                'class="text-fade hover-primary edit-executive" ' .
                'data-id="' . $encryptedId . '" title="Edit">' .
                '<svg xmlns="http://www.w3.org/2000/svg" width="22" ' .
                'height="22" viewBox="0 0 24 24" fill="none" ' .
                'stroke="currentColor" stroke-width="2" ' .
                'stroke-linecap="round" stroke-linejoin="round" ' .
                'class="feather feather-edit-2">' .
                '<polygon points="16 3 21 8 8 21 3 21 3 16 16 3">' .
                '</polygon></svg></a> ' .
                '<a href="javascript:void(0)" ' .
                'class="text-fade hover-danger delete-executive" ' .
                'data-id="' . $encryptedId . '" title="Delete">' .
                '<svg xmlns="http://www.w3.org/2000/svg" width="22" ' .
                'height="22" viewBox="0 0 24 24" fill="none" ' .
                'stroke="currentColor" stroke-width="2" ' .
                'stroke-linecap="round" stroke-linejoin="round" ' .
                'class="feather feather-trash">' .
                '<polyline points="3 6 5 6 21 6"></polyline>' .
                '<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6' .
                'm3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">' .
                '</path></svg></a>';

            $tableData[] = [
                $start + $index + 1,
                html_escape($row->full_name),
                html_escape($row->email),
                html_escape($row->phone),
                self::EXECUTIVE_ROLE,
                html_escape(
                    $this->labelsForCsv($teamGroupLabels, $row->team_group)
                ),
                html_escape(
                    $this->labelsForCsv($hotelLabels, $row->assigned_hotels)
                ),
                html_escape($row->city_name ?: '-'),
                html_escape($row->state_name ?: '-'),
                $status,
                !empty($row->created_at)
                    ? date('d-m-Y', strtotime($row->created_at))
                    : '-',
                !empty($row->updated_at)
                    ? date('d-m-Y', strtotime($row->updated_at))
                    : '-',
                $actions
            ];
        }

        return $this->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $tableData
        ]);
    }

    public function create()
    {
        $this->requirePost();
        $validated = $this->validatePayload(true);

        if (!empty($validated['errors'])) {
            return $this->json([
                'status' => false,
                'message' => 'Please correct the highlighted fields.',
                'errors' => $validated['errors']
            ]);
        }

        $data = $validated['data'];
        if ($this->emailExists($data['email'])) {
            return $this->json([
                'status' => false,
                'message' => 'Please correct the highlighted fields.',
                'errors' => ['email' => 'This email address is already in use']
            ]);
        }

        $data['password'] = md5($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        if (!$this->db->insert('sales_users', $data)) {
            return $this->json([
                'status' => false,
                'message' => 'Unable to add the Sales Executive.'
            ]);
        }

        $recordId = (int)$this->db->insert_id();
        $this->logActivity(
            'create',
            $recordId,
            'Created Sales Executive ' . $data['full_name']
        );

        return $this->json([
            'status' => true,
            'message' => 'Sales Executive added successfully.'
        ]);
    }

    public function details()
    {
        $this->requirePost();
        $record = $this->findExecutive(
            decrypt_id((string)$this->input->post('id'))
        );

        if (empty($record)) {
            return $this->json([
                'status' => false,
                'message' => 'Sales Executive not found.'
            ]);
        }

        unset($record->password);
        $record->id = encrypt_id($record->id);
        $record->selected_hotels = $this->selectedLabels(
            'hotel_admin',
            'hotel_id',
            'hotel_name',
            $record->assigned_hotels
        );
        $record->selected_team_groups = $this->selectedLabels(
            'team_groups',
            'id',
            'team_group_name',
            $record->team_group
        );

        $city = $this->db
            ->where('city_id', (int)$record->city_id)
            ->where('is_deleted', 0)
            ->get('city')
            ->row();
        $state = $this->db
            ->where('state_id', (int)$record->state_id)
            ->where('is_deleted', 0)
            ->get('state')
            ->row();

        $record->city_id = $city ? encrypt_id($city->city_id) : '';
        $record->city_name = $city->city_name ?? '';
        $record->city_state_id = $city->state_id ?? '';
        $record->state_id = $state ? encrypt_id($state->state_id) : '';
        $record->state_name = $state->state_name ?? '';

        return $this->json([
            'status' => true,
            'data' => $record
        ]);
    }

    public function update()
    {
        $this->requirePost();
        $recordId = decrypt_id(
            (string)$this->input->post('record_id')
        );
        $record = $this->findExecutive($recordId);

        if (empty($record)) {
            return $this->json([
                'status' => false,
                'message' => 'Sales Executive not found.'
            ]);
        }

        $validated = $this->validatePayload(false);
        if (!empty($validated['errors'])) {
            return $this->json([
                'status' => false,
                'message' => 'Please correct the highlighted fields.',
                'errors' => $validated['errors']
            ]);
        }

        $data = $validated['data'];
        $emailChanged = strcasecmp(
            trim((string)$record->email),
            $data['email']
        ) !== 0;
        if ($emailChanged &&
            $this->emailExists($data['email'], (int)$recordId)) {
            return $this->json([
                'status' => false,
                'message' => 'Please correct the highlighted fields.',
                'errors' => ['email' => 'This email address is already in use']
            ]);
        }

        if ($data['password'] !== '') {
            $data['password'] = md5($data['password']);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');

        $updated = $this->db
            ->where('id', (int)$recordId)
            ->where('user_role', self::EXECUTIVE_ROLE)
            ->where('is_deleted', 0)
            ->update('sales_users', $data);

        if (!$updated) {
            return $this->json([
                'status' => false,
                'message' => 'Unable to update the Sales Executive.'
            ]);
        }

        $this->logActivity(
            'update',
            (int)$recordId,
            'Updated Sales Executive ' . $data['full_name']
        );

        return $this->json([
            'status' => true,
            'message' => 'Sales Executive updated successfully.'
        ]);
    }

    public function delete()
    {
        $this->requirePost();
        $recordId = decrypt_id((string)$this->input->post('id'));
        $record = $this->findExecutive($recordId);

        if (empty($record)) {
            return $this->json([
                'status' => false,
                'message' => 'Sales Executive not found or already deleted.'
            ]);
        }

        $deleted = $this->db
            ->where('id', (int)$recordId)
            ->where('user_role', self::EXECUTIVE_ROLE)
            ->where('is_deleted', 0)
            ->update('sales_users', [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if (!$deleted || $this->db->affected_rows() !== 1) {
            return $this->json([
                'status' => false,
                'message' => 'Unable to delete the Sales Executive.'
            ]);
        }

        $this->logActivity(
            'delete',
            (int)$recordId,
            'Deleted Sales Executive ' . $record->full_name
        );

        return $this->json([
            'status' => true,
            'message' => 'Sales Executive deleted successfully.'
        ]);
    }

    private function validatePayload($passwordRequired)
    {
        $errors = [];
        $fullName = trim((string)$this->input->post('full_name'));
        $email = trim((string)$this->input->post('email'));
        $phone = trim((string)$this->input->post('phone'));
        $password = (string)$this->input->post('password');
        $zipcode = trim((string)$this->input->post('zipcode'));
        $status = (string)$this->input->post('status');
        $hotelTokens = $this->postedArray('hotel_id');
        $teamTokens = $this->postedArray('team_group');
        $hotelIds = $this->decryptTokens($hotelTokens);
        $teamIds = $this->decryptTokens($teamTokens);
        $cityId = decrypt_id((string)$this->input->post('city_id'));
        $stateId = decrypt_id((string)$this->input->post('state_id'));

        if (strlen($fullName) < 3) {
            $errors['full_name'] = 'Full name must be at least 3 characters';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            $errors['phone'] = 'Phone number must be exactly 10 digits';
        }
        if ($passwordRequired && strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        } elseif (!$passwordRequired && $password !== '' &&
            strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        if (!$this->validActiveIds(
            'hotel_admin',
            'hotel_id',
            $hotelTokens,
            $hotelIds
        )) {
            $errors['hotel_id'] = 'Please select at least one active hotel';
        }
        if (!$this->validActiveIds(
            'team_groups',
            'id',
            $teamTokens,
            $teamIds
        )) {
            $errors['team_group'] =
                'Please select at least one active team group';
        }

        $state = $stateId
            ? $this->db->where([
                'state_id' => (int)$stateId,
                'is_deleted' => 0
            ])->get('state')->row()
            : null;
        $city = ($cityId && $stateId)
            ? $this->db->where([
                'city_id' => (int)$cityId,
                'state_id' => (int)$stateId,
                'is_deleted' => 0
            ])->get('city')->row()
            : null;

        if (!$state) {
            $errors['state_id'] = 'Please select an active state';
        }
        if (!$city) {
            $errors['city_id'] =
                'Please select a city belonging to the selected state';
        }
        if ($zipcode !== '' &&
            !preg_match('/^[0-9]{4,10}$/', $zipcode)) {
            $errors['zipcode'] = 'Zip code must contain 4 to 10 digits';
        }
        if (!in_array($status, ['0', '1'], true)) {
            $errors['status'] = 'Please select a valid status';
        }

        return [
            'errors' => $errors,
            'data' => [
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'user_role' => self::EXECUTIVE_ROLE,
                'assigned_hotels' => implode(',', $hotelIds),
                'team_group' => implode(',', $teamIds),
                'city_id' => (int)$cityId,
                'state_id' => (int)$stateId,
                'zipcode' => $zipcode,
                'company' => trim(
                    (string)$this->input->post('company')
                ),
                'address' => trim(
                    (string)$this->input->post('address')
                ),
                'status' => (int)$status
            ]
        ];
    }

    private function postedArray($key)
    {
        $value = $this->input->post($key);
        if (is_array($value)) {
            return array_values(array_filter($value, 'strlen'));
        }
        return array_values(array_filter(
            explode(',', (string)$value),
            'strlen'
        ));
    }

    private function decryptTokens(array $tokens)
    {
        $ids = [];
        foreach ($tokens as $token) {
            $id = decrypt_id((string)$token);
            if ($id) {
                $ids[] = (int)$id;
            }
        }
        return array_values(array_unique($ids));
    }

    private function validActiveIds(
        $table,
        $idColumn,
        array $tokens,
        array $ids
    ) {
        if (empty($tokens) || count($tokens) !== count($ids)) {
            return false;
        }

        return (int)$this->db
            ->where_in($idColumn, $ids)
            ->where('is_deleted', 0)
            ->count_all_results($table) === count($ids);
    }

    private function findExecutive($id)
    {
        if (!$id) {
            return null;
        }

        return $this->db
            ->where('id', (int)$id)
            ->where('user_role', self::EXECUTIVE_ROLE)
            ->where('is_deleted', 0)
            ->get('sales_users')
            ->row();
    }

    private function emailExists($email, $exceptId = 0)
    {
        $this->db
            ->where(
                'LOWER(email) = ' .
                $this->db->escape(strtolower($email)),
                null,
                false
            )
            ->where('is_deleted', 0);
        if ($exceptId > 0) {
            $this->db->where('id !=', $exceptId);
        }
        return $this->db->count_all_results('sales_users') > 0;
    }

    private function executiveTableQuery($search)
    {
        $this->db
            ->from('sales_users su')
            ->join('city', 'city.city_id = su.city_id', 'left')
            ->join('state', 'state.state_id = su.state_id', 'left')
            ->where('su.user_role', self::EXECUTIVE_ROLE)
            ->where('su.is_deleted', 0);

        if ($search !== '') {
            $this->db
                ->group_start()
                ->like('su.full_name', $search)
                ->or_like('su.email', $search)
                ->or_like('su.phone', $search)
                ->or_like('city.city_name', $search)
                ->or_like('state.state_name', $search)
                ->group_end();
        }

        return $this->db;
    }

    private function labelsMap($table, $idColumn, $labelColumn)
    {
        $map = [];
        foreach ($this->db->get($table)->result() as $row) {
            $map[(int)$row->{$idColumn}] = $row->{$labelColumn};
        }
        return $map;
    }

    private function selectedLabels(
        $table,
        $idColumn,
        $labelColumn,
        $csv
    ) {
        $ids = array_values(array_filter(explode(',', (string)$csv)));
        if (empty($ids)) {
            return [];
        }

        $rows = $this->db
            ->select($idColumn . ', ' . $labelColumn)
            ->where_in($idColumn, $ids)
            ->where('is_deleted', 0)
            ->get($table)
            ->result();
        $selected = [];
        foreach ($rows as $row) {
            $selected[] = [
                'id' => encrypt_id($row->{$idColumn}),
                'raw_id' => (int)$row->{$idColumn},
                'label' => $row->{$labelColumn}
            ];
        }
        return $selected;
    }

    private function labelsForCsv(array $map, $csv)
    {
        $labels = [];
        foreach (array_filter(explode(',', (string)$csv)) as $id) {
            if (isset($map[(int)$id])) {
                $labels[] = $map[(int)$id];
            }
        }
        return empty($labels) ? '-' : implode(', ', $labels);
    }

    private function requirePost()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_error('Method Not Allowed', 405);
        }
    }

    private function json(array $payload)
    {
        $payload['csrfHash'] = $this->security->get_csrf_hash();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }

    private function logActivity($action, $recordId, $details)
    {
        $this->Common_model->insertActivityLog([
            'module' => 'sales_users',
            'record_id' => $recordId,
            'action' => $action,
            'details' => $details,
            'actor_id' => $this->salesUserId,
            'actor_name' => $this->salesUser->full_name,
            'actor_email' => $this->salesUser->email,
            'actor_role' => $this->salesUser->user_role,
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}

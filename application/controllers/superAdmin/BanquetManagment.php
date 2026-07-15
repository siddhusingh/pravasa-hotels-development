<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BanquetManagment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
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
            'module' => 'banquets',
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

    private function jsonResponse(array $response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    private function getActiveHotel($hotel_id)
    {
        if (empty($hotel_id)) {
            return null;
        }

        return $this->Common_model->getdata('hotel_admin', [
            'hotel_id' => $hotel_id,
            'is_deleted' => 0
        ]);
    }

    private function banquetPayload($include_created = false)
    {
        $data = [
            'hotel_id' => decrypt_id($this->input->post('hotel_id')),
            'banquet_name' => trim($this->input->post('banquet_name')),
            'banquet_code' => trim($this->input->post('banquet_code')),
            'capacity' => trim($this->input->post('capacity')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($include_created) {
            $data['is_deleted'] = 0;
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateBanquet(array $data)
    {
        if (empty($data['hotel_id']) || $data['banquet_name'] === '' || $data['banquet_code'] === '' || $data['capacity'] === '') {
            return 'Please fill all required banquet details';
        }

        if (empty($this->getActiveHotel($data['hotel_id']))) {
            return 'Selected hotel is unavailable';
        }

        if (filter_var($data['capacity'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
            return 'Capacity must be a positive whole number';
        }

        return '';
    }

    public function index()
    {
        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', ['is_deleted' => 0]);

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageBanquet', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_banquets_table()
    {
        $inputs = $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = (int) ($inputs['length'] ?? 10);
        $length = ($length > 0 && $length <= 100) ? $length : 10;
        $search = trim($inputs['search']['value'] ?? '');

        $columns = [
            0 => 'banquet.banquet_id',
            1 => 'hotel_admin.hotel_name',
            2 => 'banquet.banquet_code',
            3 => 'banquet.banquet_name',
            4 => 'banquet.capacity'
        ];
        $order_index = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$order_index] ?? 'banquet.banquet_id';
        $direction = strtolower($inputs['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
        $list = $this->objdt->DTBanquets($length, $start, $search, $order, $direction);

        $data = [];
        $number = $start + 1;
        foreach ($list as $row) {
            $encrypted = encrypt_id($row->banquet_id);
            $data[] = [
                $number++,
                html_escape($row->hotel_name ?? '-'),
                html_escape($row->banquet_code ?? '-'),
                html_escape($row->banquet_name ?? '-'),
                html_escape($row->capacity ?? '-'),
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-banquet" data-record_id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-danger delete-banquet" data-record_id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4h6v2"></path></svg>
                </a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTBanquetsAll(),
            'recordsFiltered' => $this->objdt->DTBanquetsFiltered($search),
            'data' => $data
        ]);
    }

    public function insert()
    {
        $data = $this->banquetPayload(true);
        $validation_error = $this->validateBanquet($data);
        if ($validation_error !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validation_error]);
            return;
        }

        $record_id = $this->Comman_model->insertData('banquet', $data);
        if (!$record_id) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to add banquet']);
            return;
        }

        $this->logActivity('create', $record_id, 'Created banquet ' . $data['banquet_name']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'New banquet has been added successfully',
            'record_id' => encrypt_id($record_id)
        ]);
    }

    public function getByHotel()
    {
        $hotel_id = $this->input->post('hotel_id');
        if (!is_numeric($hotel_id)) {
            $hotel_id = decrypt_id($hotel_id);
        }

        if (empty($this->getActiveHotel($hotel_id))) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Selected hotel is unavailable', 'data' => []]);
            return;
        }

        $banquets = $this->db
            ->where('hotel_id', $hotel_id)
            ->where('is_deleted', 0)
            ->get('banquet')
            ->result();

        $this->jsonResponse(['status' => 'success', 'data' => $banquets]);
    }

    public function edit()
    {
        $id = decrypt_id($this->input->post('id'));
        $result = !empty($id) ? $this->Common_model->getdata('banquet', [
            'banquet_id' => $id,
            'is_deleted' => 0
        ]) : null;

        if (empty($result)) {
            $this->jsonResponse(['status' => false, 'message' => 'Banquet record not found or already deleted']);
            return;
        }

        $hotel = $this->getActiveHotel($result->hotel_id);
        if (empty($hotel)) {
            $this->jsonResponse(['status' => false, 'message' => 'Banquet parent hotel is unavailable']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'hotel_id' => encrypt_id($result->hotel_id),
                'hotel_name' => $hotel->hotel_name,
                'banquet_name' => $result->banquet_name,
                'banquet_code' => $result->banquet_code,
                'capacity' => $result->capacity
            ],
            'id' => encrypt_id($result->banquet_id)
        ]);
    }

    public function update()
    {
        $record_id = decrypt_id($this->input->post('record_id'));
        $old_data = !empty($record_id) ? $this->Common_model->getdata('banquet', [
            'banquet_id' => $record_id,
            'is_deleted' => 0
        ]) : null;

        if (empty($old_data)) {
            $this->jsonResponse(['status' => false, 'message' => 'Banquet record not found or already deleted']);
            return;
        }

        $data = $this->banquetPayload();
        $validation_error = $this->validateBanquet($data);
        if ($validation_error !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validation_error]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('banquet', $data, [
            'banquet_id' => $record_id,
            'is_deleted' => 0
        ]);
        $active_banquet = $updated ? $this->Common_model->getdata('banquet', [
            'banquet_id' => $record_id,
            'is_deleted' => 0
        ]) : null;

        if (empty($active_banquet)) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update banquet']);
            return;
        }

        $this->logActivity('update', $record_id, 'Updated banquet ' . $data['banquet_name']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Banquet data has been updated successfully',
            'record_id' => encrypt_id($record_id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));
        $banquet = !empty($id) ? $this->Common_model->getdata('banquet', [
            'banquet_id' => $id,
            'is_deleted' => 0
        ]) : null;

        if (empty($banquet)) {
            $this->jsonResponse(['status' => false, 'message' => 'Banquet record not found or already deleted']);
            return;
        }

        $deleted = $this->Comman_model->UpdateRecord('banquet', [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], [
            'banquet_id' => $id,
            'is_deleted' => 0
        ]);

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $id, 'Soft deleted banquet ' . $banquet->banquet_name);
            $this->jsonResponse(['status' => true, 'message' => 'Banquet deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Banquet record not found or already deleted']);
    }
}

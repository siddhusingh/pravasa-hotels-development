<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BanquetManagement extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('hotel_admin_session'))) {
            redirect('hotel-admin-login');
        }
    }

    private function getHotelId()
    {
        $session = $this->session->userdata('hotel_admin_session');
        return (int) ($session['id'] ?? 0);
    }

    private function getAssignedHotel()
    {
        $hotelId = $this->getHotelId();
        if ($hotelId < 1) {
            return null;
        }

        return $this->Common_model->getdata('hotel_admin', [
            'hotel_id' => $hotelId,
            'is_deleted' => 0
        ]);
    }

    private function getCurrentActor()
    {
        $actor = $this->session->userdata('hotel_admin_session');
        $admin = $this->Common_model->getdata('hotel_admins', [
            'hotel_id' => $this->getHotelId(),
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
            'module' => 'banquets',
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

    private function banquetPayload($includeCreated = false)
    {
        $data = [
            'hotel_id' => $this->getHotelId(),
            'banquet_name' => trim((string) $this->input->post('banquet_name')),
            'banquet_code' => trim((string) $this->input->post('banquet_code')),
            'capacity' => trim((string) $this->input->post('capacity')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['is_deleted'] = 0;
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateBanquet(array $data)
    {
        if ($data['banquet_name'] === '' || $data['banquet_code'] === '' || $data['capacity'] === '') {
            return 'Please fill all required banquet details';
        }

        if (empty($this->getAssignedHotel())) {
            return 'Assigned hotel is unavailable';
        }

        if (filter_var($data['capacity'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
            return 'Capacity must be a positive whole number';
        }

        return '';
    }

    private function getScopedBanquet($recordId)
    {
        if (empty($recordId)) {
            return null;
        }

        return $this->db
            ->where('banquet_id', $recordId)
            ->where('hotel_id', $this->getHotelId())
            ->where('is_deleted', 0)
            ->get('banquet')
            ->row();
    }

    public function index()
    {
        $hotel = $this->getAssignedHotel();
        if (empty($hotel)) {
            show_error('Assigned hotel is unavailable', 403);
            return;
        }

        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/manageBanquet', ['hotel' => $hotel]);
        $this->load->view('hotel_admin/include/footer');
    }

    public function get_banquets_table()
    {
        if (empty($this->getAssignedHotel())) {
            $this->jsonResponse([
                'draw' => (int) $this->input->post('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'status' => false,
                'message' => 'Assigned hotel is unavailable'
            ]);
            return;
        }

        $inputs = (array) $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = (int) ($inputs['length'] ?? 10);
        $length = ($length > 0 && $length <= 100) ? $length : 10;
        $search = trim((string) ($inputs['search']['value'] ?? ''));
        $hotelId = $this->getHotelId();

        $columns = [
            0 => 'banquet.banquet_id',
            1 => 'hotel_admin.hotel_name',
            2 => 'banquet.banquet_code',
            3 => 'banquet.banquet_name',
            4 => 'banquet.capacity'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'banquet.banquet_id';
        $direction = strtolower((string) ($inputs['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
        $list = $this->objdt->DTBanquets($length, $start, $search, $order, $direction, $hotelId);

        $data = [];
        $number = $start + 1;
        foreach ($list as $row) {
            $encryptedId = encrypt_id($row->banquet_id);
            $data[] = [
                $number++,
                html_escape($row->hotel_name ?? '-'),
                html_escape($row->banquet_code ?? '-'),
                html_escape($row->banquet_name ?? '-'),
                html_escape($row->capacity ?? '-'),
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-banquet" data-record_id="'.$encryptedId.'" aria-label="Edit banquet"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-banquet" data-record_id="'.$encryptedId.'" aria-label="Delete banquet"><i class="fa fa-trash"></i></a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTBanquetsAll($hotelId),
            'recordsFiltered' => $this->objdt->DTBanquetsFiltered($search, $hotelId),
            'data' => $data
        ]);
    }

    public function insert()
    {
        $data = $this->banquetPayload(true);
        $validationError = $this->validateBanquet($data);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $inserted = $this->db->insert('banquet', $data);
        $recordId = $inserted ? $this->db->insert_id() : 0;
        if (!$recordId) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to add banquet']);
            return;
        }

        $this->logActivity('create', $recordId, 'Created banquet '.$data['banquet_name']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'New banquet has been added successfully',
            'record_id' => encrypt_id($recordId)
        ]);
    }

    public function edit()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $banquet = $this->getScopedBanquet($recordId);
        if (empty($banquet) || empty($this->getAssignedHotel())) {
            $this->jsonResponse(['status' => false, 'message' => 'Banquet record not found or already deleted']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'banquet_name' => $banquet->banquet_name,
                'banquet_code' => $banquet->banquet_code,
                'capacity' => $banquet->capacity
            ],
            'id' => encrypt_id($banquet->banquet_id)
        ]);
    }

    public function update()
    {
        $recordId = decrypt_id($this->input->post('record_id'));
        $oldData = $this->getScopedBanquet($recordId);
        if (empty($oldData)) {
            $this->jsonResponse(['status' => false, 'message' => 'Banquet record not found or already deleted']);
            return;
        }

        $data = $this->banquetPayload();
        $validationError = $this->validateBanquet($data);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $where = [
            'banquet_id' => $recordId,
            'hotel_id' => $this->getHotelId(),
            'is_deleted' => 0
        ];
        $updated = $this->db->where($where)->update('banquet', $data);
        $activeBanquet = $updated ? $this->db->where($where)->get('banquet')->row() : null;
        if (empty($activeBanquet)) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update banquet']);
            return;
        }

        $this->logActivity('update', $recordId, 'Updated banquet '.$data['banquet_name']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Banquet data has been updated successfully',
            'record_id' => encrypt_id($recordId)
        ]);
    }

    public function delete()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $banquet = $this->getScopedBanquet($recordId);
        if (empty($banquet) || empty($this->getAssignedHotel())) {
            $this->jsonResponse(['status' => false, 'message' => 'Banquet record not found or already deleted']);
            return;
        }

        $deleted = $this->db
            ->where('banquet_id', $recordId)
            ->where('hotel_id', $this->getHotelId())
            ->where('is_deleted', 0)
            ->update('banquet', [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $recordId, 'Soft deleted banquet '.$banquet->banquet_name);
            $this->jsonResponse(['status' => true, 'message' => 'Banquet deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Banquet record not found or already deleted']);
    }
}

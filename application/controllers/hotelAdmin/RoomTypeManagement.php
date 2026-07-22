<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RoomTypeManagement extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
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
            'module' => 'roomtypes',
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

    private function roomTypePayload($includeCreated = false)
    {
        $data = [
            'hotel_id' => $this->getHotelId(),
            'roomtype_name' => trim((string) $this->input->post('roomtype_name')),
            'roomtype_code' => trim((string) $this->input->post('roomtype_code')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateRoomTypePayload(array $data)
    {
        if ($data['roomtype_name'] === '' || $data['roomtype_code'] === '') {
            return 'Please fill all required room type details';
        }

        if (empty($this->getAssignedHotel())) {
            return 'Assigned hotel is unavailable';
        }

        return '';
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
        $this->load->view('hotel_admin/manageRoomType', ['hotel' => $hotel]);
        $this->load->view('hotel_admin/include/footer');
    }

    public function get_roomtypes_table()
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
        $search = trim((string) ($inputs['search']['value'] ?? ''));
        $hotelId = $this->getHotelId();

        $columns = [
            0 => 'roomtype.roomtype_id',
            1 => 'hotel_admin.hotel_name',
            2 => 'roomtype.roomtype_code',
            3 => 'roomtype.roomtype_name',
            4 => 'roomtype.created_at',
            5 => 'roomtype.updated_at'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'roomtype.roomtype_id';
        $dir = strtolower((string) ($inputs['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
        $list = $this->objdt->DTRoomTypes($length, $start, $search, $order, $dir, $hotelId);
        $data = [];
        $serial = $start + 1;

        foreach ($list as $row) {
            $encryptedId = encrypt_id($row->roomtype_id);
            $data[] = [
                $serial++,
                htmlspecialchars($row->hotel_name ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->roomtype_code ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->roomtype_name ?? '-', ENT_QUOTES, 'UTF-8'),
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-roomtype" data-record_id="'.$encryptedId.'" aria-label="Edit room type">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-roomtype" data-record_id="'.$encryptedId.'" aria-label="Delete room type">
                    <i class="fa fa-trash"></i>
                </a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTRoomTypesAll($hotelId),
            'recordsFiltered' => $this->objdt->DTRoomTypesFiltered($search, $hotelId),
            'data' => $data
        ]);
    }

    public function insert()
    {
        $data = $this->roomTypePayload(true);
        $validationError = $this->validateRoomTypePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $recordId = $this->Comman_model->insertData('roomtype', $data);

        if ($recordId) {
            $this->logActivity('create', $recordId, 'Created room type '.$data['roomtype_name'].' ('.$data['roomtype_code'].')');
        }

        $this->jsonResponse([
            'status' => (bool) $recordId,
            'message' => $recordId ? 'New room type has been added successfully' : 'Failed to add room type',
            'record_id' => $recordId ? encrypt_id($recordId) : ''
        ]);
    }

    public function edit()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $roomType = $this->Common_model->getdata('roomtype', [
            'roomtype_id' => $recordId,
            'hotel_id' => $this->getHotelId(),
            'is_deleted' => 0
        ]);

        if (empty($recordId) || empty($roomType) || empty($this->getAssignedHotel())) {
            $this->jsonResponse(['status' => false, 'message' => 'Room type not found or already deleted']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'roomtype_name' => $roomType->roomtype_name,
                'roomtype_code' => $roomType->roomtype_code
            ],
            'id' => encrypt_id($roomType->roomtype_id)
        ]);
    }

    public function update()
    {
        $recordId = decrypt_id($this->input->post('record_id'));
        $where = [
            'roomtype_id' => $recordId,
            'hotel_id' => $this->getHotelId(),
            'is_deleted' => 0
        ];
        $existingRoomType = $this->Common_model->getdata('roomtype', $where);

        if (empty($recordId) || empty($existingRoomType)) {
            $this->jsonResponse(['status' => false, 'message' => 'Room type not found or already deleted']);
            return;
        }

        $data = $this->roomTypePayload();
        $validationError = $this->validateRoomTypePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('roomtype', $data, $where);
        $affectedRows = $this->db->affected_rows();

        if (!$updated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update room type']);
            return;
        }

        if ($affectedRows > 0) {
            $this->logActivity('update', $recordId, 'Updated room type '.$data['roomtype_name'].' ('.$data['roomtype_code'].')');
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Room type data has been updated successfully',
            'record_id' => encrypt_id($recordId)
        ]);
    }

    public function delete()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $where = [
            'roomtype_id' => $recordId,
            'hotel_id' => $this->getHotelId(),
            'is_deleted' => 0
        ];
        $roomType = $this->Common_model->getdata('roomtype', $where);

        if (empty($recordId) || empty($roomType) || empty($this->getAssignedHotel())) {
            $this->jsonResponse(['status' => false, 'message' => 'Room type not found or already deleted']);
            return;
        }

        $deleteQuery = $this->Comman_model->UpdateRecord('roomtype', [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], $where);
        $deleted = $deleteQuery && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity('delete', $recordId, 'Deleted room type '.$roomType->roomtype_name.' ('.$roomType->roomtype_code.')');
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted ? 'Room type deleted successfully' : 'Unable to delete room type'
        ]);
    }
}

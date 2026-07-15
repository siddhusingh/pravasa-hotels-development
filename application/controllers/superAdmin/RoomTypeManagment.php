<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RoomTypeManagment extends MY_Controller
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
            'module' => 'roomtypes',
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

    private function jsonResponse($response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    private function roomTypePayload($includeCreated = false)
    {
        $data = [
            'hotel_id' => decrypt_id($this->input->post('hotel_id')),
            'roomtype_name' => trim($this->input->post('roomtype_name')),
            'roomtype_code' => trim($this->input->post('roomtype_code')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateRoomTypePayload($data)
    {
        if (empty($data['hotel_id']) || $data['roomtype_name'] === '' || $data['roomtype_code'] === '') {
            return 'Please fill all required room type details';
        }

        $hotel = $this->Common_model->getdata('hotel_admin', array(
            'hotel_id' => $data['hotel_id'],
            'is_deleted' => 0
        ));

        if (empty($hotel)) {
            return 'Selected hotel is unavailable';
        }

        return '';
    }

    public function index()
    {
        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', array('is_deleted' => 0));

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageRoomType', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_roomtypes_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'roomtype.roomtype_id',
            1 => 'hotel_admin.hotel_name',
            2 => 'roomtype.roomtype_code',
            3 => 'roomtype.roomtype_name',
            4 => 'roomtype.created_at',
            5 => 'roomtype.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']] ?? 'roomtype.roomtype_id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTRoomTypes($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->roomtype_id);
            $data[] = [
                $i++,
                htmlspecialchars($row->hotel_name ?? '-'),
                htmlspecialchars($row->roomtype_code ?? '-'),
                htmlspecialchars($row->roomtype_name ?? '-'),
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-roomtype" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-roomtype" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTRoomTypesAll(),
            'recordsFiltered' => $this->objdt->DTRoomTypesFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
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

        $record_id = $this->Comman_model->insertData('roomtype', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created room type '.$data['roomtype_name'].' ('.$data['roomtype_code'].')');
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'New room type has been added successfully' : 'Failed to add room type',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function getByHotel()
    {
        $hotel_id = decrypt_id($this->input->post('hotel_id'));
        $roomtype = $this->db->where('hotel_id', $hotel_id)->get('roomtype')->result();

        $this->jsonResponse([
            'status' => true,
            'data' => $roomtype
        ]);
    }

    public function edit()
    {
        $id = decrypt_id($this->input->post('id'));
        $result = $this->Common_model->getdata('roomtype', array(
            'roomtype_id' => $id,
            'is_deleted' => 0
        ));

        if (empty($id) || empty($result)) {
            $this->jsonResponse(['status' => false, 'message' => 'Room type not found or already deleted']);
            return;
        }

        $hotel = $this->Common_model->getdata('hotel_admin', array(
            'hotel_id' => $result->hotel_id,
            'is_deleted' => 0
        ));

        if (empty($hotel)) {
            $this->jsonResponse(['status' => false, 'message' => 'Room type cannot be edited because its hotel is unavailable']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'hotel_id' => encrypt_id($result->hotel_id),
                'hotel_name' => $hotel->hotel_name ?? '',
                'roomtype_name' => $result->roomtype_name,
                'roomtype_code' => $result->roomtype_code
            ],
            'id' => encrypt_id($result->roomtype_id)
        ]);
    }

    public function update()
    {
        $record_id = decrypt_id($this->input->post('record_id'));

        if (empty($record_id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid room type record']);
            return;
        }

        $existing_roomtype = $this->Common_model->getdata('roomtype', array(
            'roomtype_id' => $record_id,
            'is_deleted' => 0
        ));

        if (empty($existing_roomtype)) {
            $this->jsonResponse(['status' => false, 'message' => 'Room type not found or already deleted']);
            return;
        }

        $data = $this->roomTypePayload();
        $validationError = $this->validateRoomTypePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('roomtype', $data, array(
            'roomtype_id' => $record_id,
            'is_deleted' => 0
        ));
        $affected_rows = $this->db->affected_rows();

        if (!$updated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update room type']);
            return;
        }

        if ($affected_rows === 0) {
            $still_active = $this->Common_model->getdata('roomtype', array(
                'roomtype_id' => $record_id,
                'is_deleted' => 0
            ));

            if (empty($still_active)) {
                $this->jsonResponse(['status' => false, 'message' => 'Room type not found or already deleted']);
                return;
            }
        }

        if ($affected_rows > 0) {
            $this->logActivity('update', $record_id, 'Updated room type '.$data['roomtype_name'].' ('.$data['roomtype_code'].')');
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Room type data has been updated successfully',
            'record_id' => encrypt_id($record_id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid room type record']);
            return;
        }

        $where = array(
            'roomtype_id' => $id,
            'is_deleted' => 0
        );
        $roomtype = $this->Common_model->getdata('roomtype', $where);

        if (empty($roomtype)) {
            $this->jsonResponse(['status' => false, 'message' => 'Room type not found or already deleted']);
            return;
        }

        $delete_query = $this->Comman_model->UpdateRecord('roomtype', array(
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ), $where);
        $deleted = $delete_query && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                isset($roomtype->roomtype_name) ? 'Deleted room type '.$roomtype->roomtype_name.' ('.$roomtype->roomtype_code.')' : 'Deleted room type ID '.$id
            );
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted
                ? 'Room type deleted successfully'
                : ($delete_query ? 'Room type not found or already deleted' : 'Unable to delete room type')
        ]);
    }
}

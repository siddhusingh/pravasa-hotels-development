<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ManageRestaurants extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->helper('secure');
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
            'module' => 'hotel_restaurants',
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

    private function uploadRestaurantImage()
    {
        if (empty($_FILES['restaurant_image']['name'])) {
            return ['success' => true, 'file_name' => ''];
        }

        $uploadPath = FCPATH . 'uploads/restaurant_images/';
        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
            return ['success' => false, 'message' => 'Unable to create the restaurant image directory'];
        }

        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path' => $uploadPath,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => true,
            'remove_spaces' => true
        ]);

        if (!$this->upload->do_upload('restaurant_image')) {
            return [
                'success' => false,
                'message' => strip_tags($this->upload->display_errors('', ''))
            ];
        }

        $uploadData = $this->upload->data();
        return ['success' => true, 'file_name' => $uploadData['file_name']];
    }

    private function deleteRestaurantImage($fileName)
    {
        if (empty($fileName) || basename($fileName) !== $fileName) {
            return;
        }

        $path = FCPATH . 'uploads/restaurant_images/' . $fileName;
        if (is_file($path)) {
            unlink($path);
        }
    }

    private function restaurantPayload($image, $includeCreated = false)
    {
        $data = [
            'hotel_id' => $this->getHotelId(),
            'restaurant_name' => trim((string) $this->input->post('restaurant_name')),
            'restaurant_code' => trim((string) $this->input->post('restaurant_code')),
            'contact_number' => trim((string) $this->input->post('contact_number')),
            'email' => trim((string) $this->input->post('email')),
            'status' => (string) $this->input->post('status') === '0' ? 0 : 1,
            'restaurant_image' => $image,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['is_deleted'] = 0;
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateRestaurant(array $data)
    {
        if ($data['restaurant_name'] === '' || $data['restaurant_code'] === '') {
            return 'Please fill all required restaurant details';
        }

        if (empty($this->getAssignedHotel())) {
            return 'Assigned hotel is unavailable';
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address';
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
        $this->load->view('hotel_admin/manageRestaurants', ['hotel' => $hotel]);
        $this->load->view('hotel_admin/include/footer');
    }

    public function get_restaurants_table()
    {
        if (empty($this->getAssignedHotel())) {
            $this->jsonResponse([
                'draw' => (int) $this->input->post('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'status' => 'error',
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
            0 => 'r.id',
            1 => 'h.hotel_name',
            2 => 'r.restaurant_name',
            3 => 'r.restaurant_code',
            4 => 'r.contact_number',
            5 => 'r.email',
            6 => 'r.status'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'r.id';
        $direction = strtolower((string) ($inputs['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
        $list = $this->objdt->DTRestaurants($length, $start, $search, $order, $direction, $hotelId);

        $data = [];
        $number = $start + 1;
        foreach ($list as $row) {
            $encryptedId = encrypt_id($row->id);
            $status = ((int) $row->status === 1)
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $data[] = [
                $number++,
                html_escape($row->hotel_name ?? '-'),
                html_escape($row->restaurant_name ?? '-'),
                html_escape($row->restaurant_code ?? '-'),
                html_escape($row->contact_number ?? '-'),
                html_escape($row->email ?? '-'),
                $status,
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-restaurant" data-record_id="'.$encryptedId.'" aria-label="Edit restaurant">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-restaurant" data-record_id="'.$encryptedId.'" aria-label="Delete restaurant">
                    <i class="fa fa-trash"></i>
                </a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTRestaurantsAll($hotelId),
            'recordsFiltered' => $this->objdt->DTRestaurantsFiltered($search, $hotelId),
            'data' => $data
        ]);
    }

    public function insert()
    {
        $upload = $this->uploadRestaurantImage();
        if (!$upload['success']) {
            $this->jsonResponse(['status' => 'error', 'message' => $upload['message']]);
            return;
        }

        $data = $this->restaurantPayload($upload['file_name'], true);
        $validationError = $this->validateRestaurant($data);
        if ($validationError !== '') {
            $this->deleteRestaurantImage($upload['file_name']);
            $this->jsonResponse(['status' => 'error', 'message' => $validationError]);
            return;
        }

        $inserted = $this->db->insert('hotel_restaurants', $data);
        $recordId = $inserted ? $this->db->insert_id() : 0;
        if (!$recordId) {
            $this->deleteRestaurantImage($upload['file_name']);
            $this->jsonResponse(['status' => 'error', 'message' => 'Unable to add restaurant']);
            return;
        }

        $this->logActivity('create', $recordId, 'Created restaurant '.$data['restaurant_name']);
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Restaurant added successfully',
            'record_id' => encrypt_id($recordId)
        ]);
    }

    public function edit()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $restaurant = !empty($recordId) ? $this->db
            ->where('id', $recordId)
            ->where('hotel_id', $this->getHotelId())
            ->where('is_deleted', 0)
            ->get('hotel_restaurants')
            ->row() : null;

        if (empty($restaurant) || empty($this->getAssignedHotel())) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant not found or already deleted']);
            return;
        }

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'restaurant_name' => $restaurant->restaurant_name,
                'restaurant_code' => $restaurant->restaurant_code,
                'contact_number' => $restaurant->contact_number,
                'email' => $restaurant->email,
                'status' => $restaurant->status,
                'restaurant_image' => $restaurant->restaurant_image
            ],
            'id' => encrypt_id($restaurant->id)
        ]);
    }

    public function update()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $where = [
            'id' => $recordId,
            'hotel_id' => $this->getHotelId(),
            'is_deleted' => 0
        ];
        $oldData = !empty($recordId) ? $this->db->where($where)->get('hotel_restaurants')->row() : null;

        if (empty($oldData)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant not found or already deleted']);
            return;
        }

        $upload = $this->uploadRestaurantImage();
        if (!$upload['success']) {
            $this->jsonResponse(['status' => 'error', 'message' => $upload['message']]);
            return;
        }

        $oldImage = $oldData->restaurant_image ?? '';
        $image = $upload['file_name'] !== '' ? $upload['file_name'] : $oldImage;
        $data = $this->restaurantPayload($image);
        $validationError = $this->validateRestaurant($data);
        if ($validationError !== '') {
            $this->deleteRestaurantImage($upload['file_name']);
            $this->jsonResponse(['status' => 'error', 'message' => $validationError]);
            return;
        }

        $updated = $this->db->where($where)->update('hotel_restaurants', $data);
        $activeRestaurant = $updated ? $this->db->where($where)->get('hotel_restaurants')->row() : null;

        if (empty($activeRestaurant)) {
            $this->deleteRestaurantImage($upload['file_name']);
            $this->jsonResponse(['status' => 'error', 'message' => 'Unable to update restaurant']);
            return;
        }

        if ($upload['file_name'] !== '' && $upload['file_name'] !== $oldImage) {
            $this->deleteRestaurantImage($oldImage);
        }

        $this->logActivity('update', $recordId, 'Updated restaurant '.$data['restaurant_name']);
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Restaurant updated successfully',
            'record_id' => encrypt_id($recordId)
        ]);
    }

    public function delete()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $where = [
            'id' => $recordId,
            'hotel_id' => $this->getHotelId(),
            'is_deleted' => 0
        ];
        $restaurant = !empty($recordId) ? $this->db->where($where)->get('hotel_restaurants')->row() : null;

        if (empty($restaurant) || empty($this->getAssignedHotel())) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant not found or already deleted']);
            return;
        }

        $deleted = $this->db->where($where)->update('hotel_restaurants', [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $recordId, 'Soft deleted restaurant '.$restaurant->restaurant_name);
            $this->jsonResponse(['status' => 'success', 'message' => 'Restaurant deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant not found or already deleted']);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Restaurants extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->helper('secure');

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
            'module' => 'hotel_restaurants',
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

    private function uploadRestaurantImage()
    {
        if (empty($_FILES['restaurant_image']['name'])) {
            return ['success' => true, 'file_name' => ''];
        }

        $upload_path = FCPATH . 'uploads/restaurant_images/';
        if (!is_dir($upload_path) && !mkdir($upload_path, 0755, true)) {
            return ['success' => false, 'message' => 'Unable to create the restaurant image directory'];
        }

        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path' => $upload_path,
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

        $upload_data = $this->upload->data();
        return ['success' => true, 'file_name' => $upload_data['file_name']];
    }

    private function deleteRestaurantImage($file_name)
    {
        if (empty($file_name) || basename($file_name) !== $file_name) {
            return;
        }

        $path = FCPATH . 'uploads/restaurant_images/' . $file_name;
        if (is_file($path)) {
            unlink($path);
        }
    }

    private function restaurantPayload($image, $include_created = false)
    {
        $data = [
            'hotel_id' => decrypt_id($this->input->post('hotel_id')),
            'restaurant_name' => trim($this->input->post('restaurant_name')),
            'restaurant_code' => trim($this->input->post('restaurant_code')),
            'contact_number' => trim($this->input->post('contact_number')),
            'email' => trim($this->input->post('email')),
            'status' => (string) $this->input->post('status') === '0' ? 0 : 1,
            'restaurant_image' => $image,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        foreach (['restaurant_type', 'opening_time', 'closing_time', 'capacity'] as $optional_field) {
            $value = $this->input->post($optional_field);
            if ($value !== null) {
                $data[$optional_field] = $value;
            }
        }

        if ($include_created) {
            $data['is_deleted'] = 0;
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateRestaurant(array $data)
    {
        if (empty($data['hotel_id']) || $data['restaurant_name'] === '' || $data['restaurant_code'] === '') {
            return 'Please fill all required restaurant details';
        }

        if (empty($this->getActiveHotel($data['hotel_id']))) {
            return 'Selected hotel is unavailable';
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address';
        }

        return '';
    }

    public function manage()
    {
        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', ['is_deleted' => 0]);

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/hotel_restaurants/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function add()
    {
        $upload = $this->uploadRestaurantImage();
        if (!$upload['success']) {
            $this->jsonResponse(['status' => 'error', 'message' => $upload['message']]);
            return;
        }

        $data = $this->restaurantPayload($upload['file_name'], true);
        $validation_error = $this->validateRestaurant($data);
        if ($validation_error !== '') {
            $this->deleteRestaurantImage($upload['file_name']);
            $this->jsonResponse(['status' => 'error', 'message' => $validation_error]);
            return;
        }

        $inserted = $this->db->insert('hotel_restaurants', $data);
        $record_id = $inserted ? $this->db->insert_id() : 0;
        if (!$record_id) {
            $this->deleteRestaurantImage($upload['file_name']);
            $this->jsonResponse(['status' => 'error', 'message' => 'Unable to add restaurant']);
            return;
        }

        $this->logActivity('create', $record_id, 'Created restaurant ' . $data['restaurant_name']);
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Restaurant added successfully',
            'record_id' => encrypt_id($record_id)
        ]);
    }

    public function get_restaurants_table()
    {
        $inputs = $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = (int) ($inputs['length'] ?? 10);
        $length = ($length > 0 && $length <= 100) ? $length : 10;
        $search = trim($inputs['search']['value'] ?? '');

        $columns = [
            0 => 'r.id',
            1 => 'h.hotel_name',
            2 => 'r.restaurant_name',
            3 => 'r.restaurant_code',
            4 => 'r.contact_number',
            5 => 'r.email',
            6 => 'r.status'
        ];
        $order_index = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$order_index] ?? 'r.id';
        $direction = strtolower($inputs['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
        $list = $this->objdt->DTRestaurants($length, $start, $search, $order, $direction);

        $data = [];
        $number = $start + 1;
        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
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
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-restaurant" data-record_id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-restaurant" data-record_id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTRestaurantsAll(),
            'recordsFiltered' => $this->objdt->DTRestaurantsFiltered($search),
            'data' => $data
        ]);
    }

    public function fetch()
    {
        $this->db->select('r.*, h.hotel_name');
        $this->db->from('hotel_restaurants r');
        $this->db->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'inner');
        $this->db->where('r.is_deleted', 0);
        $this->db->where('h.is_deleted', 0);
        $this->db->order_by('r.id', 'DESC');
        $data['hotel_restaurants'] = $this->db->get()->result();

        echo $this->load->view('super_admin/hotel_restaurants/_forms_table', $data, true);
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

        $restaurants = $this->db
            ->where('hotel_id', $hotel_id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get('hotel_restaurants')
            ->result();

        foreach ($restaurants as $restaurant) {
            $restaurant->id = encrypt_id($restaurant->id);
        }

        $this->jsonResponse(['status' => 'success', 'data' => $restaurants]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $restaurant = !empty($id) ? $this->db
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->get('hotel_restaurants')
            ->row() : null;

        if (empty($restaurant)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant not found or already deleted']);
            return;
        }

        $hotel = $this->getActiveHotel($restaurant->hotel_id);
        if (empty($hotel)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant parent hotel is unavailable']);
            return;
        }

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'hotel_id' => encrypt_id($restaurant->hotel_id),
                'hotel_name' => $hotel->hotel_name,
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
        $id = decrypt_id($this->input->post('id'));
        $old_data = !empty($id) ? $this->db
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->get('hotel_restaurants')
            ->row() : null;

        if (empty($old_data)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant not found or already deleted']);
            return;
        }

        $upload = $this->uploadRestaurantImage();
        if (!$upload['success']) {
            $this->jsonResponse(['status' => 'error', 'message' => $upload['message']]);
            return;
        }

        $old_image = $old_data->restaurant_image ?? '';
        $image = $upload['file_name'] !== '' ? $upload['file_name'] : $old_image;
        $data = $this->restaurantPayload($image);
        $validation_error = $this->validateRestaurant($data);
        if ($validation_error !== '') {
            $this->deleteRestaurantImage($upload['file_name']);
            $this->jsonResponse(['status' => 'error', 'message' => $validation_error]);
            return;
        }

        $updated = $this->db
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->update('hotel_restaurants', $data);
        $active_restaurant = $updated ? $this->db
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->get('hotel_restaurants')
            ->row() : null;

        if (empty($active_restaurant)) {
            $this->deleteRestaurantImage($upload['file_name']);
            $this->jsonResponse(['status' => 'error', 'message' => 'Unable to update restaurant']);
            return;
        }

        if ($upload['file_name'] !== '' && $upload['file_name'] !== $old_image) {
            $this->deleteRestaurantImage($old_image);
        }

        $this->logActivity('update', $id, 'Updated restaurant ' . $data['restaurant_name']);
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Restaurant updated successfully',
            'record_id' => encrypt_id($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));
        $restaurant = !empty($id) ? $this->db
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->get('hotel_restaurants')
            ->row() : null;

        if (empty($restaurant)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant not found or already deleted']);
            return;
        }

        $deleted = $this->db
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->update('hotel_restaurants', [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $id, 'Soft deleted restaurant ' . $restaurant->restaurant_name);
            $this->jsonResponse(['status' => 'success', 'message' => 'Restaurant deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => 'error', 'message' => 'Restaurant not found or already deleted']);
    }
}

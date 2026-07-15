<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HotelManagment extends MY_Controller
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
            'module' => 'hotels',
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

    private function uploadHotelImage()
    {
        if (empty($_FILES['hotel_image']['name'])) {
            return ['success' => true, 'file_name' => ''];
        }

        $upload_path = FCPATH . 'uploads/hotel_images/';
        if (!is_dir($upload_path) && !mkdir($upload_path, 0755, true)) {
            return ['success' => false, 'message' => 'Unable to create the hotel image directory'];
        }

        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path' => $upload_path,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => true,
            'remove_spaces' => true
        ]);

        if (!$this->upload->do_upload('hotel_image')) {
            return [
                'success' => false,
                'message' => strip_tags($this->upload->display_errors('', ''))
            ];
        }

        $upload_data = $this->upload->data();
        return ['success' => true, 'file_name' => $upload_data['file_name']];
    }

    private function deleteHotelImage($file_name)
    {
        if (empty($file_name) || basename($file_name) !== $file_name) {
            return;
        }

        $path = FCPATH . 'uploads/hotel_images/' . $file_name;
        if (is_file($path)) {
            unlink($path);
        }
    }

    private function formatFacebookPage($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '-';
        }

        $url = preg_match('/^www\./i', $value) ? 'https://' . $value : $value;
        $is_http_url = preg_match('#^https?://#i', $url) && filter_var($url, FILTER_VALIDATE_URL);
        $escaped_value = html_escape($value);

        if ($is_http_url) {
            return '<a class="hotel-facebook-link" href="' . html_escape($url) . '" target="_blank" rel="noopener noreferrer" title="' . $escaped_value . '">
                <i class="fa fa-facebook-square" aria-hidden="true"></i>
                <span>View Facebook</span>
            </a>';
        }

        return '<span class="hotel-table-ellipsis" title="' . $escaped_value . '">' . $escaped_value . '</span>';
    }

    private function getActiveLocation($country_id, $state_id, $city_id)
    {
        $country = $this->Common_model->getdata('country', array(
            'country_id' => $country_id,
            'is_deleted' => 0
        ));
        $state = $this->Common_model->getdata('state', array(
            'state_id' => $state_id,
            'country_id' => $country_id,
            'is_deleted' => 0
        ));
        $city = $this->Common_model->getdata('city', array(
            'city_id' => $city_id,
            'state_id' => $state_id,
            'country_id' => $country_id,
            'is_deleted' => 0
        ));

        if (empty($country) || empty($state) || empty($city)) {
            return null;
        }

        return array(
            'country' => $country,
            'state' => $state,
            'city' => $city
        );
    }


    public function index()
    {

        $data['countries'] = $this->Common_model->getAllData('country', array('is_deleted' => 0));

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageHotel', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_hotels_table()
    {
        $inputs = $this->input->post();

        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = (int) ($inputs['length'] ?? 10);
        $length = ($length > 0 && $length <= 100) ? $length : 10;
        $search = trim($inputs['search']['value'] ?? '');

        $columns = [
            0 => 'hotel_admin.hotel_id',
            1 => 'hotel_admin.hotel_code',
            2 => 'hotel_admin.hotel_name',
            3 => 'country.country_name',
            4 => 'state.state_name',
            5 => 'city.city_name',
            6 => 'hotel_admin.facebook_page_id'
        ];

        $order_index = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$order_index] ?? 'hotel_admin.hotel_id';
        $dir = strtolower($inputs['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

        $list = $this->objdt->DTHotels($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->hotel_id);
            $lead_link = '<a href="'.base_url('get-lead-by-form/') . md5($row->hotel_id) . '/' . md5(2).'" target="_blank"><button class="btn btn-primary btn-sm">Lead link</button></a>';

            $data[] = [
                $i++,
                html_escape(!empty($row->hotel_code) ? $row->hotel_code : 'NA'),
                html_escape($row->hotel_name ?? '-'),
                html_escape($row->country_name ?? '-'),
                html_escape($row->state_name ?? '-'),
                html_escape($row->city_name ?? '-'),
                $this->formatFacebookPage($row->facebook_page_id ?? ''),
                $lead_link,
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-hotel" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-hotel" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $this->objdt->DTHotelsAll(),
            "recordsFiltered" => $this->objdt->DTHotelsFiltered($search),
            "data" => $data,
            "csrfHash" => $this->security->get_csrf_hash()
        ]);
    }

    public function get_states_by_country()
    {
        $country_id = decrypt_id($this->input->post('country_id'));
        $selected_state_token = $this->input->post('selected_state_id');
        $selected_state_id = !empty($selected_state_token) ? decrypt_id($selected_state_token) : null;

        $country = !empty($country_id) ? $this->Common_model->getdata('country', array(
            'country_id' => $country_id,
            'is_deleted' => 0
        )) : null;

        if (empty($country)) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Selected country is unavailable',
                'data' => []
            ]);
            return;
        }

        $states = [];
        foreach ($this->Common_model->getAllData('state', array(
            'country_id' => $country_id,
            'is_deleted' => 0
        )) as $state) {
            $states[] = [
                'state_id' => (!empty($selected_state_id) && $selected_state_id == $state->state_id) ? $selected_state_token : encrypt_id($state->state_id),
                'state_name' => $state->state_name
            ];
        }

        $this->jsonResponse([
            'status' => true,
            'data' => $states
        ]);
    }

    public function get_cities_by_state()
    {
        $state_id = decrypt_id($this->input->post('state_id'));
        $selected_city_token = $this->input->post('selected_city_id');
        $selected_city_id = !empty($selected_city_token) ? decrypt_id($selected_city_token) : null;

        $state = !empty($state_id) ? $this->Common_model->getdata('state', array(
            'state_id' => $state_id,
            'is_deleted' => 0
        )) : null;
        $country = !empty($state) ? $this->Common_model->getdata('country', array(
            'country_id' => $state->country_id,
            'is_deleted' => 0
        )) : null;

        if (empty($state) || empty($country)) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Selected state is unavailable',
                'data' => []
            ]);
            return;
        }

        $cities = [];
        foreach ($this->Common_model->getAllData('city', array(
            'state_id' => $state_id,
            'country_id' => $state->country_id,
            'is_deleted' => 0
        )) as $city) {
            $cities[] = [
                'city_id' => (!empty($selected_city_id) && $selected_city_id == $city->city_id) ? $selected_city_token : encrypt_id($city->city_id),
                'city_name' => $city->city_name
            ];
        }

        $this->jsonResponse([
            'status' => true,
            'data' => $cities
        ]);
    }


    public function insert()
    {
        $country_id = decrypt_id($this->input->post('country_id'));
        $state_id = decrypt_id($this->input->post('state_id'));
        $city_id = decrypt_id($this->input->post('city_id'));
        $hotel_code = trim($this->input->post('hotel_code'));
        $hotel_name = trim($this->input->post('hotel_name'));
        $hotel_contact = trim($this->input->post('hotel_contact'));
        $facebook_page_id = trim($this->input->post('facebook_page_id'));
        $hotel_address = trim($this->input->post('hotel_address'));

        if (empty($country_id) || empty($state_id) || empty($city_id) || $hotel_name === '' || $hotel_code === '' || $hotel_contact === '' || $facebook_page_id === '') {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Please fill all required hotel details'
            ]);
            return;
        }

        if (empty($this->getActiveLocation($country_id, $state_id, $city_id))) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Selected country, state, or city is unavailable'
            ]);
            return;
        }

        $upload = $this->uploadHotelImage();
        if (!$upload['success']) {
            $this->jsonResponse(['status' => false, 'message' => $upload['message']]);
            return;
        }

        $now = date('Y-m-d H:i:s');
        $hotel_data = array(
            'country_id' => $country_id,
            'state_id' => $state_id,
            'city_id' => $city_id,
            'hotel_name' => $hotel_name,
            'hotel_code' => $hotel_code,
            'hotel_contact' => $hotel_contact,
            'facebook_page_id' => $facebook_page_id,
            'hotel_address' => $hotel_address,
            'hotel_image' => $upload['file_name'],
            'status' => 'active',
            'is_deleted' => 0,
            'created_at' => $now,
            'updated_at' => $now
        );

        $record_id = $this->Comman_model->insertData('hotel_admin', $hotel_data);
        if (!$record_id) {
            $this->deleteHotelImage($upload['file_name']);
            $this->jsonResponse(['status' => false, 'message' => 'Unable to add hotel']);
            return;
        }

        $this->logActivity('create', $record_id, 'Created hotel ' . $hotel_name);
        $this->jsonResponse([
            'status' => true,
            'message' => 'New hotel has been added successfully',
            'record_id' => encrypt_id($record_id)
        ]);
    }


    public function edit()
    {

        $id = decrypt_id($this->input->post('id'));
        $result = $this->Common_model->getdata('hotel_admin', array(
            'hotel_id' => $id,
            'is_deleted' => 0
        ));

        if (empty($id) || empty($result)) {
            echo json_encode([
                'status' => false,
                'message' => 'Hotel record not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $location = $this->getActiveLocation($result->country_id, $result->state_id, $result->city_id);

        if (empty($location)) {
            echo json_encode([
                'status' => false,
                'message' => 'Hotel cannot be edited because its country, state, or city is unavailable',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $country = $location['country'];
        $state = $location['state'];
        $city = $location['city'];

        echo json_encode([
            'status' => true,
            'data' => [
                'country_id' => encrypt_id($result->country_id),
                'country_name' => $country->country_name ?? '',
                'state_id' => encrypt_id($result->state_id),
                'state_name' => $state->state_name ?? '',
                'city_id' => encrypt_id($result->city_id),
                'city_name' => $city->city_name ?? '',
                'hotel_name' => $result->hotel_name,
                'hotel_code' => $result->hotel_code,
                'hotel_contact' => $result->hotel_contact,
                'facebook_page_id' => $result->facebook_page_id,
                'hotel_address' => $result->hotel_address
            ],
            'id' => encrypt_id($result->hotel_id),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }


    public function update()
    {
        $record_id = decrypt_id($this->input->post('record_id'));

        $country_id = decrypt_id($this->input->post('country_id'));
        $state_id = decrypt_id($this->input->post('state_id'));
        $city_id = decrypt_id($this->input->post('city_id'));
        $hotel_name = trim($this->input->post('hotel_name'));
        $hotel_code = trim($this->input->post('hotel_code'));
        $hotel_contact = trim($this->input->post('hotel_contact'));
        $facebook_page_id = trim($this->input->post('facebook_page_id'));
        $hotel_address = trim($this->input->post('hotel_address'));

        if (empty($record_id) || empty($country_id) || empty($state_id) || empty($city_id) || $hotel_name === '' || $hotel_code === '' || $hotel_contact === '' || $facebook_page_id === '') {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Please fill all required hotel details'
            ]);
            return;
        }

        $old_data = $this->Common_model->getdata('hotel_admin', array(
            'hotel_id' => $record_id,
            'is_deleted' => 0
        ));

        if (empty($old_data)) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Hotel record not found or already deleted'
            ]);
            return;
        }

        if (empty($this->getActiveLocation($country_id, $state_id, $city_id))) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Selected country, state, or city is unavailable'
            ]);
            return;
        }

        $old_image = !empty($old_data->hotel_image) ? $old_data->hotel_image : '';
        $upload = $this->uploadHotelImage();
        if (!$upload['success']) {
            $this->jsonResponse(['status' => false, 'message' => $upload['message']]);
            return;
        }
        $hotel_image = $upload['file_name'] !== '' ? $upload['file_name'] : $old_image;

        $hotel_data = array(
            'country_id' => $country_id,
            'state_id' => $state_id,
            'city_id' => $city_id,
            'hotel_name' => $hotel_name,
            'hotel_code' => $hotel_code,
            'hotel_contact' => $hotel_contact,
            'facebook_page_id' => $facebook_page_id,
            'hotel_address' => $hotel_address,
            'hotel_image' => $hotel_image,
            'status' => 'active',
            'updated_at' => date('Y-m-d H:i:s')
        );

        $updated = $this->Comman_model->UpdateRecord('hotel_admin', $hotel_data, array(
            'hotel_id' => $record_id,
            'is_deleted' => 0
        ));
        $affected_rows = $this->db->affected_rows();

        if (!$updated) {
            $this->deleteHotelImage($upload['file_name']);
            $this->jsonResponse([
                'status' => false,
                'message' => 'Unable to update hotel'
            ]);
            return;
        }

        if ($affected_rows === 0) {
            $still_active = $this->Common_model->getdata('hotel_admin', array(
                'hotel_id' => $record_id,
                'is_deleted' => 0
            ));

            if (empty($still_active)) {
                $this->deleteHotelImage($upload['file_name']);
                $this->jsonResponse([
                    'status' => false,
                    'message' => 'Hotel record not found or already deleted'
                ]);
                return;
            }
        }

        if ($upload['file_name'] !== '' && $old_image !== $upload['file_name']) {
            $this->deleteHotelImage($old_image);
        }

        $this->logActivity('update', $record_id, 'Updated hotel ' . $hotel_name);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Hotel data has been updated successfully',
            'record_id' => encrypt_id($record_id)
        ]);
    }

    public function delete()
    {

        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid hotel record'
            ]);
            return;
        }

        $where = array(
            'hotel_id' => $id,
            'is_deleted' => 0
        );
        $hotel = $this->Common_model->getdata('hotel_admin', $where);

        if (empty($hotel)) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Hotel record not found or already deleted'
            ]);
            return;
        }

        $data = $this->Comman_model->UpdateRecord('hotel_admin', array(
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ), $where);

        if ($data && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $id, 'Soft deleted hotel ' . $hotel->hotel_name);
            $this->jsonResponse(['status' => true, 'message' => 'Hotel deleted successfully']);
            return;
        }

        $this->jsonResponse([
            'status' => false,
            'message' => $data ? 'Hotel record not found or already deleted' : 'Unable to delete hotel'
        ]);
    }
}

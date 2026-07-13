<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class HotelManagment extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables','objdt');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }


    public function index()
    {

        $data['countries'] = $this->Common_model->getAllData('country', "");

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageHotel', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_hotels_table()
    {
        $inputs = $this->input->post();

        $draw   = $inputs['draw'];
        $start  = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'hotel_admin.hotel_id',
            1 => 'country.country_name',
            2 => 'state.state_name',
            3 => 'city.city_name',
            4 => 'hotel_admin.facebook_page_id',
            5 => 'hotel_admin.hotel_code',
            6 => 'hotel_admin.hotel_name',
            7 => 'hotel_admin.created_at',
            8 => 'hotel_admin.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']];
        $dir = $inputs['order'][0]['dir'];

        $list = $this->objdt->DTHotels($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->hotel_id);
            $lead_link = '<a href="'.base_url('get-lead-by-form/') . md5($row->hotel_id) . '/' . md5(2).'" target="_blank"><button class="btn btn-primary btn-sm">Lead link</button></a>';

            $data[] = [
                $i++,
                $row->country_name ?? '-',
                $row->state_name ?? '-',
                $row->city_name ?? '-',
                $row->facebook_page_id,
                !empty($row->hotel_code) ? $row->hotel_code : 'NA',
                $row->hotel_name,
                date('d M Y', strtotime($row->created_at)),
                date('d M Y', strtotime($row->updated_at)),
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
        $states = [];

        if (!empty($country_id)) {
            foreach ($this->Common_model->getAllData('state', ['country_id' => $country_id]) as $state) {
                $states[] = [
                    'state_id' => (!empty($selected_state_id) && $selected_state_id == $state->state_id) ? $selected_state_token : encrypt_id($state->state_id),
                    'state_name' => $state->state_name
                ];
            }
        }

        echo json_encode([
            'status' => true,
            'data' => $states,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function get_cities_by_state()
    {
        $state_id = decrypt_id($this->input->post('state_id'));
        $selected_city_token = $this->input->post('selected_city_id');
        $selected_city_id = !empty($selected_city_token) ? decrypt_id($selected_city_token) : null;
        $cities = [];

        if (!empty($state_id)) {
            foreach ($this->Common_model->getAllData('city', ['state_id' => $state_id]) as $city) {
                $cities[] = [
                    'city_id' => (!empty($selected_city_id) && $selected_city_id == $city->city_id) ? $selected_city_token : encrypt_id($city->city_id),
                    'city_name' => $city->city_name
                ];
            }
        }

        echo json_encode([
            'status' => true,
            'data' => $cities,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }


    //  insert a audit tracker start here

    public function insert()
    {
        $country_id = decrypt_id($this->input->post('country_id'));
        $state_id = decrypt_id($this->input->post('state_id'));
        $city_id = decrypt_id($this->input->post('city_id'));
        $hotel_code = trim($this->input->post('hotel_code'));
        $hotel_name = trim($this->input->post('hotel_name'));
        $hotel_contact = trim($this->input->post('hotel_contact'));
        $facebook_page_id = trim($this->input->post('facebook_page_id'));
        $hotel_address = $this->input->post('hotel_address');

        if (empty($country_id) || empty($state_id) || empty($city_id) || $hotel_name == '' || $hotel_code == '' || $hotel_contact == '' || $facebook_page_id == '') {
            echo json_encode([
                'status' => false,
                'message' => 'Please fill all required hotel details',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $status = 'active';
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        // ✅ Image Upload Logic Start
        $hotel_image = '';

        if (!empty($_FILES['hotel_image']['name'])) {

            // ✅ define path
            $upload_path = FCPATH . 'uploads/hotel_images/';

            // ✅ create folder if not exists
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            // ✅ optional: ensure writable
            if (!is_writable($upload_path)) {
                chmod($upload_path, 0777);
            }

            // ✅ config
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size']      = 2048;
            $config['file_name']     = time() . '_' . $_FILES['hotel_image']['name'];

            // ✅ load + initialize properly
            $this->load->library('upload');
            $this->upload->initialize($config);

            // ✅ upload
            if ($this->upload->do_upload('hotel_image')) {

                $uploadData  = $this->upload->data();
                $hotel_image = $uploadData['file_name'];
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => strip_tags($this->upload->display_errors()),
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
                return;
            }
        }

        $hotel_data = array(
            'country_id' => $country_id,
            'state_id' => $state_id,
            'city_id' => $city_id,
            'hotel_name' => $hotel_name,
            'hotel_code' => $hotel_code,
            'hotel_contact' => $hotel_contact,
            'facebook_page_id' => $facebook_page_id,
            'hotel_address' => $hotel_address,
            'hotel_image' => $hotel_image, // ✅ added
            'status' => $status,
            'created_at' => $created_on,
            'updated_at' => $updated_on
        );

        $record_id = $this->Comman_model->insertData('hotel_admin', $hotel_data);

        $response_json['status'] = true;
        $response_json['message'] = "New hotel has been added successfully";
        $response_json['record_id'] = encrypt_id($record_id);
        $response_json['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response_json);
    }


    public function edit()
    {

        $id = decrypt_id($this->input->post('id'));
        $result = $this->Common_model->getdata('hotel_admin', array('hotel_id' => $id));

        if (empty($id) || empty($result)) {
            echo json_encode([
                'status' => false,
                'message' => 'Hotel record not found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $country = $this->Common_model->getdata('country', ['country_id' => $result->country_id]);
        $state = $this->Common_model->getdata('state', ['state_id' => $result->state_id]);
        $city = $this->Common_model->getdata('city', ['city_id' => $result->city_id]);

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
                'hotel_address' => $result->hotel_address,
                'hotel_image' => $result->hotel_image
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
        $hotel_address = $this->input->post('hotel_address');

        if (empty($record_id) || empty($country_id) || empty($state_id) || empty($city_id) || $hotel_name == '' || $hotel_code == '' || $hotel_contact == '' || $facebook_page_id == '') {
            echo json_encode([
                'status' => false,
                'message' => 'Please fill all required hotel details',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $status = 'active';
        $updated_on = date('Y-m-d h:i:s');

        // ✅ Get old image
        $old_data = $this->Common_model->getdata('hotel_admin', array('hotel_id' => $record_id));

        $old_image = !empty($old_data->hotel_image) ? $old_data->hotel_image : '';
        $hotel_image = $old_image;

        if (!empty($_FILES['hotel_image']['name'])) {

            $upload_path = FCPATH . 'uploads/hotel_images/';

            // 🔥 auto create folder
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size']      = 2048;
            $config['file_name']     = time() . '_' . $_FILES['hotel_image']['name'];

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('hotel_image')) {

                $uploadData  = $this->upload->data();
                $hotel_image = $uploadData['file_name'];

                // delete old image
                if (!empty($old_image) && file_exists($upload_path . $old_image)) {
                    unlink($upload_path . $old_image);
                }
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => strip_tags($this->upload->display_errors()),
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
                return;
            }
        }

        $hotel_data = array(
            'country_id' => $country_id,
            'state_id' => $state_id,
            'city_id' => $city_id,
            'hotel_name' => $hotel_name,
            'hotel_code' => $hotel_code,
            'hotel_contact' => $hotel_contact,
            'facebook_page_id' => $facebook_page_id,
            'hotel_address' => $hotel_address,
            'hotel_image' => $hotel_image, // ✅ added
            'status' => $status,
            'updated_at' => $updated_on
        );

        $this->Comman_model->UpdateRecord('hotel_admin', $hotel_data, array('hotel_id' => $record_id));

        $response_json['status'] = true;
        $response_json['message'] = "hotel data has been updated successfully";
        $response_json['record_id'] = encrypt_id($record_id);
        $response_json['csrfHash'] = $this->security->get_csrf_hash();

        echo json_encode($response_json);
    }

    public function delete()
    {

        // $this->check_login();

        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid hotel record',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $table = 'hotel';
        $where = array('hotel_id' => $id);

        $data = $this->Comman_model->Deletedata('hotel_admin', array('hotel_id' => $id));
        if ($data) {
            $response['status'] = true;
            $response['message'] = "hotel Deleted successfully";
        } else {
            $response['status'] = false;
            $response['message'] = "Something Went wrong";
        }

        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }
}

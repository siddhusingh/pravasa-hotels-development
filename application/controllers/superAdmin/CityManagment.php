<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class CityManagment extends MY_Controller
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
            'module' => 'cities',
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

    public function index()
    {

        $data['countries'] = $this->Common_model->getAllData('country', array('is_deleted' => 0));

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageCity', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_cities_table()
    {
        $inputs = $this->input->post();

        $draw   = $inputs['draw'];
        $start  = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'city.city_id',
            1 => 'country.country_name',
            2 => 'state.state_name',
            3 => 'city.city_name',
            4 => 'city.created_at',
            5 => 'city.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']];
        $dir = $inputs['order'][0]['dir'];

        $list = $this->objdt->DTCities(
            $length,
            $start,
            $search,
            $order,
            $dir
        );

        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->city_id);

            $data[] = [
                $i++,
                $row->country_name,
                $row->state_name,
                $row->city_name,
                date('d M Y', strtotime($row->created_at)),
                date('d M Y', strtotime($row->updated_at)),
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-city" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>

                <a href="javascript:void(0)" class="text-fade hover-primary delete-city" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $this->objdt->DTCitiesAll(),
            "recordsFiltered" => $this->objdt->DTCitiesFiltered($search),
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
            $country = $this->Common_model->getdata('country', array(
                'country_id' => $country_id,
                'is_deleted' => 0
            ));

            if (!empty($country)) {
                $state_rows = $this->Common_model->getAllData('state', array(
                    'country_id' => $country_id,
                    'is_deleted' => 0
                ));
                foreach ($state_rows as $state) {
                    $states[] = [
                        'state_id' => (!empty($selected_state_id) && $selected_state_id == $state->state_id) ? $selected_state_token : encrypt_id($state->state_id),
                        'state_name' => $state->state_name
                    ];
                }
            }
        }

        echo json_encode([
            'status' => true,
            'data' => $states,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }


    //  insert a audit tracker start here

    public function insert()
    {

        // $this->check_login();
        $city_name = trim($this->input->post('city_name'));
        $country_id = decrypt_id($this->input->post('country_id'));
        $state_id = decrypt_id($this->input->post('state_id'));
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        if ($city_name == '' || empty($country_id) || empty($state_id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Please fill all required city details',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $country = $this->Common_model->getdata('country', array(
            'country_id' => $country_id,
            'is_deleted' => 0
        ));
        $state = $this->Common_model->getdata('state', array(
            'state_id' => $state_id,
            'country_id' => $country_id,
            'is_deleted' => 0
        ));

        if (empty($country) || empty($state)) {
            echo json_encode([
                'status' => false,
                'message' => 'Selected country or state is unavailable',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $city_data = array(
            'city_name' => $city_name,
            'country_id' => $country_id,
            'state_id' => $state_id,
            'created_at' => $created_on,
            'updated_at' => $updated_on
        );

        $record_id = $this->Comman_model->insertData('city', $city_data);
        if ($record_id) {
            $this->logActivity(
                'create',
                $record_id,
                "Created city {$city_name} for state ".($state->state_name ?? '')." and country ".($country->country_name ?? '')
            );
        }

        $response_json['status'] = true;
        $response_json['message'] = "New city has been added successfully";
        $response_json['record_id'] = encrypt_id($record_id);
        $response_json['csrfHash'] = $this->security->get_csrf_hash();

        echo json_encode($response_json);
    }


    public function edit()
    {

        $id = decrypt_id($this->input->post('id'));
        $result = $this->Common_model->getdata('city', array(
            'city_id' => $id,
            'is_deleted' => 0
        ));

        if (empty($result)) {
            echo json_encode([
                'status' => false,
                'message' => 'City record not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $country = $this->Common_model->getdata('country', array(
            'country_id' => $result->country_id,
            'is_deleted' => 0
        ));
        $state = $this->Common_model->getdata('state', array(
            'state_id' => $result->state_id,
            'country_id' => $result->country_id,
            'is_deleted' => 0
        ));

        if (empty($country) || empty($state)) {
            echo json_encode([
                'status' => false,
                'message' => 'City cannot be edited because its country or state is unavailable',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'data' => [
                'country_id' => encrypt_id($result->country_id),
                'country_name' => $country->country_name ?? '',
                'state_id' => encrypt_id($result->state_id),
                'state_name' => $state->state_name ?? '',
                'city_name' => $result->city_name
            ],
            'csrfHash' => $this->security->get_csrf_hash(),
            'id' => encrypt_id($result->city_id)
        ]);
    }


    public function update()
    {

        $record_id = decrypt_id($this->input->post('record_id'));

        $city_name = trim($this->input->post('city_name'));
        $country_id = decrypt_id($this->input->post('country_id'));
        $state_id = decrypt_id($this->input->post('state_id'));

        if (empty($record_id) || $city_name == '' || empty($country_id) || empty($state_id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Please fill all required city details',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $existing_city = $this->Common_model->getdata('city', array(
            'city_id' => $record_id,
            'is_deleted' => 0
        ));
        $country = $this->Common_model->getdata('country', array(
            'country_id' => $country_id,
            'is_deleted' => 0
        ));
        $state = $this->Common_model->getdata('state', array(
            'state_id' => $state_id,
            'country_id' => $country_id,
            'is_deleted' => 0
        ));

        if (empty($existing_city)) {
            echo json_encode([
                'status' => false,
                'message' => 'City record not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        if (empty($country) || empty($state)) {
            echo json_encode([
                'status' => false,
                'message' => 'Selected country or state is unavailable',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        $city_data = array(
            'city_name' => $city_name,
            'country_id' => $country_id,
            'state_id' => $state_id,
            'updated_at' => $updated_on,

        );


        $datas = $this->Comman_model->UpdateRecord('city', $city_data, array(
            'city_id' => $record_id,
            'is_deleted' => 0
        ));
        $affected_rows = $this->db->affected_rows();

        if (!$datas) {
            echo json_encode([
                'status' => false,
                'message' => 'Unable to update city',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        if ($affected_rows === 0) {
            $still_active = $this->Common_model->getdata('city', array(
                'city_id' => $record_id,
                'is_deleted' => 0
            ));

            if (empty($still_active)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'City record not found or already deleted',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
                return;
            }
        }

        if ($affected_rows > 0) {
            $this->logActivity(
                'update',
                $record_id,
                "Updated city {$city_name} for state ".($state->state_name ?? '')." and country ".($country->country_name ?? '')
            );
        }

        $response_json['status'] = true;
        $response_json['message'] = "City data has been updated successfully";
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
                'message' => 'Invalid city record',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $where = array(
            'city_id' => $id,
            'is_deleted' => 0
        );
        $result = $this->Common_model->getdata('city', $where);

        if (empty($result)) {
            echo json_encode([
                'status' => false,
                'message' => 'City record not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $data = $this->Comman_model->UpdateRecord('city', array(
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ), $where);

        if ($data && $this->db->affected_rows() === 1) {
            $this->logActivity(
                'delete',
                $id,
                isset($result->city_name) ? "Deleted city {$result->city_name} (state_id={$result->state_id}, country_id={$result->country_id})" : "Deleted city ID {$id}"
            );

            $response['status'] = true;
            $response['message'] = "City deleted successfully";
        } else {
            $response['status'] = false;
            $response['message'] = $data
                ? 'City record not found or already deleted'
                : 'Unable to delete city';
        }

        $response['csrfHash'] = $this->security->get_csrf_hash();

        echo json_encode($response);
    }
}

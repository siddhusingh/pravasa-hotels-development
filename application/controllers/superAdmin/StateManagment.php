<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class StateManagment extends MY_Controller
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
            'module' => 'states',
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

        $data['states'] = $this->Comman_model->getStateData();
        $data['countries'] = $this->Common_model->getAllData('country', array('is_deleted' => 0));



        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageState', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_states_table()
    {
        $inputs = $this->input->post();

        $draw   = $inputs['draw'];
        $start  = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'state.state_id',
            1 => 'country.country_name',
            2 => 'state.state_name',
            3 => 'state.created_at',
            4 => 'state.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']];
        $dir = $inputs['order'][0]['dir'];

        $list = $this->objdt->DTStates(
            $length,
            $start,
            $search,
            $order,
            $dir
        );

        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->state_id);

            $data[] = [
                $i++,
                $row->country_name,
                $row->state_name,
                date('d M Y', strtotime($row->created_at)),
                date('d M Y', strtotime($row->updated_at)),
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-state" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>

                <a href="javascript:void(0)" class="text-fade hover-primary delete-state" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $this->objdt->DTStatesAll(),
            "recordsFiltered" => $this->objdt->DTStatesFiltered($search),
            "data" => $data,
            "csrfHash" => $this->security->get_csrf_hash()
        ]);
    }


    //  insert a audit tracker start here

    public function insert()
    {

        // $this->check_login();
        $state_name = $this->input->post('state_name');
        $country_id = decrypt_id($this->input->post('country_id'));
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        $country = $this->Common_model->getdata('country', array(
            'country_id' => $country_id,
            'is_deleted' => 0
        ));

        if (empty($country)) {
            echo json_encode([
                'status' => false,
                'message' => 'Selected country is unavailable',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $state_data = array(
            'state_name' => $state_name,
            'country_id' => $country_id,
            'created_at' => $created_on,
            'updated_at' => $updated_on
        );

        $record_id = $this->Comman_model->insertData('state', $state_data);
        $country_name = $country->country_name ?? '';

        if ($record_id) {
            $this->logActivity(
                'create',
                $record_id,
                "Created state {$state_name} for country {$country_name}"
            );
        }

        $response_json['status'] = true;
        $response_json['message'] = "New state has been added successfully";
        $response_json['record_id'] = encrypt_id($record_id);
        $response_json['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response_json);
    }


    public function edit()
    {

        $id = decrypt_id($this->input->post('id'));
        $result = $this->Common_model->getdata('state', array(
            'state_id' => $id,
            'is_deleted' => 0
        ));

        if (empty($id) || empty($result)) {
            echo json_encode([
                'status' => false,
                'message' => 'State record not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $country = $this->Common_model->getdata('country', array(
            'country_id' => $result->country_id,
            'is_deleted' => 0
        ));

        if (empty($country)) {
            echo json_encode([
                'status' => false,
                'message' => 'State cannot be edited because its country is unavailable',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'data' => [
                'country_code' => $country->country_code,
                'state_name' => $result->state_name
            ],
            'csrfHash' => $this->security->get_csrf_hash(),
            'id' => encrypt_id($result->state_id)
        ]);
    }


    public function update()
    {

        $record_id = decrypt_id($this->input->post('record_id'));

        //  $this->check_login();
        $state_name = $this->input->post('state_name');
        $country_id = decrypt_id($this->input->post('country_id'));
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        $country = $this->Common_model->getdata('country', array(
            'country_id' => $country_id,
            'is_deleted' => 0
        ));

        if (empty($country)) {
            echo json_encode([
                'status' => false,
                'message' => 'Selected country is unavailable',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        if (empty($record_id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid state record',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $existing_state = $this->Common_model->getdata('state', array(
            'state_id' => $record_id,
            'is_deleted' => 0
        ));

        if (empty($existing_state)) {
            echo json_encode([
                'status' => false,
                'message' => 'State record not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $state_data = array(
            'state_name' => $state_name,
            'country_id' => $country_id,
            'updated_at' => $updated_on,

        );


        $datas = $this->Comman_model->UpdateRecord('state', $state_data, array(
            'state_id' => $record_id,
            'is_deleted' => 0
        ));
        $affected_rows = $this->db->affected_rows();
        $country_name = $country->country_name ?? '';

        if (!$datas) {
            echo json_encode([
                'status' => false,
                'message' => 'Unable to update state',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        if ($affected_rows === 0) {
            $still_active = $this->Common_model->getdata('state', array(
                'state_id' => $record_id,
                'is_deleted' => 0
            ));

            if (empty($still_active)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'State record not found or already deleted',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
                return;
            }
        }

        if ($affected_rows > 0) {
            $this->logActivity(
                'update',
                $record_id,
                "Updated state {$state_name} for country {$country_name}"
            );
        }

        $response_json['status'] = true;
        $response_json['message'] = "State data has been updated successfully";
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
                'message' => 'Invalid state record',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $where = array(
            'state_id' => $id,
            'is_deleted' => 0
        );
        $state = $this->Common_model->getdata('state', $where);

        if (empty($state)) {
            echo json_encode([
                'status' => false,
                'message' => 'State record not found or already deleted',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $data = $this->Comman_model->UpdateRecord('state', array(
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ), $where);

        if ($data && $this->db->affected_rows() === 1) {
            $country_name = '';
            if (!empty($state)) {
                $country = $this->Common_model->getdata('country', ['country_id' => $state->country_id]);
                $country_name = $country->country_name ?? '';
            }
            $this->logActivity(
                'delete',
                $id,
                isset($state->state_name) ? "Deleted state {$state->state_name} for country {$country_name}" : "Deleted state ID {$id}"
            );
            $response['status'] = true;
            $response['message'] = "State deleted successfully";
        } else {
            $response['status'] = false;
            $response['message'] = $data
                ? 'State record not found or already deleted'
                : 'Unable to delete state';
        }

        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }
}

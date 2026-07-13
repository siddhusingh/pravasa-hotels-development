<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class CountryManagment extends MY_Controller
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
            'module' => 'countries',
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

        $data['countries'] = $this->Common_model->getAllData('country', "");
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageCountry', $data);
        $this->load->view('super_admin/include/footer');
    }
    
    // Datatable module
    
    public function get_countries_table()
    {
        $inputs = $this->input->post();
        
        $draw   = $inputs['draw'];
        $start  = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];
        
        $columns = [
            0 => 'country_id',
            1 => 'country_code',
            2 => 'country_name',
            3 => 'created_at',
            4 => 'updated_at'
        ];
        
        $order = $columns[$inputs['order'][0]['column']];
        $dir = $inputs['order'][0]['dir'];
    
        $list = $this->objdt->DTCountries(
            $length,
            $start,
            $search,
            $order,
            $dir
        );
        
        $data = [];
        $i = $start + 1;
        
        foreach ($list as $row) {
            $encrypted = encrypt_id($row->country_id);
    
            $data[] = [
    
                $i++,
    
                $row->country_code,
    
                $row->country_name,
    
                date('d M Y', strtotime($row->created_at)),
    
                date('d M Y', strtotime($row->updated_at)),
    
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-country" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
    
                <a href="javascript:void(0)" class="text-fade hover-primary delete-country" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $this->objdt->DTCountriesAll(),
            "recordsFiltered" => $this->objdt->DTCountriesFiltered($search),
            "data" => $data,
            "csrfHash" => $this->security->get_csrf_hash()
        ]);
    }


    //  insert a audit tracker start here

    public function insert()
    {

        // $this->check_login();
        $country_name = $this->input->post('country_name');
        $country_code = $this->input->post('country_code');
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        $country_data = array(
            'country_name' => $country_name,
            'country_code' => $country_code,
            'created_at' => $created_on,
            'updated_at' => $updated_on
        );

        $record_id = $this->Comman_model->insertData('country', $country_data);

        if ($record_id) {
            $this->logActivity(
                'create',
                $record_id,
                "Created country {$country_name} ({$country_code})"
            );
        }
        
        $output = array(
            "status"=>true,
            "message"=>"New country added successfully",
            "csrfHash"=>$this->security->get_csrf_hash(),
        );
        echo json_encode($output);
    }


    public function edit()
    {
        $id = $this->input->post('id');
        $record_id = decrypt_id($id);
        $result = $this->Common_model->getdata('country', array('country_id' => $record_id));

        if (empty($record_id) || empty($result)) {
            echo json_encode([
                "status" => false,
                "message" => "Country record not found",
                "csrfHash" => $this->security->get_csrf_hash()
            ]);
            return;
        }
        
        $output = array(
            "status"=>true,
            'data' => [
                'country_code' => $result->country_code,
                'country_name' => $result->country_name
            ],
            'csrfHash' => $this->security->get_csrf_hash(),
            'id'=>encrypt_id($result->country_id),
        );
        echo json_encode($output);
    }


    public function update()
    {
        $record_id = decrypt_id($this->input->post('record_id'));
        //  $this->check_login();
        $country_name = $this->input->post('country_name');
        $country_code = $this->input->post('country_code');
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        if (empty($record_id)) {
            echo json_encode([
                "status" => false,
                "message" => "Invalid country record",
                "csrfHash" => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $country_data = array(
            'country_name' => $country_name,
            'country_code' => $country_code,
            'updated_at' => $updated_on,

        );

        $datas = $this->Comman_model->UpdateRecord('country', $country_data, array('country_id' => $record_id));

        if ($datas) {
            $this->logActivity(
                'update',
                $record_id,
                "Updated country {$country_name} ({$country_code})"
            );
        }
        
        $output = array(
            "status"=>true,
            "message"=>"Country updated successfully",
            "csrfHash"=>$this->security->get_csrf_hash(),
            "Data"=>$country_data
        );
        echo json_encode($output);
    }

    public function delete()
    {

        // $this->check_login();
        
        $status = false;
        $message = "";

        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            echo json_encode([
                "status" => false,
                "message" => "Invalid country record",
                "csrfHash" => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $table = 'country';
        $where = array('country_id' => $id);

        $result = $this->Common_model->getdata('country', array('country_id' => $id));
        $data = $this->Comman_model->Deletedata('country', array('country_id' => $id));

        if ($data) {
            $this->logActivity(
                'delete',
                $id,
                isset($result->country_name) ? "Deleted country {$result->country_name} ({$result->country_code})" : "Deleted country ID {$id}"
            );

            $status = true;
            $message = "Country Deleted successfully";
        } else {
            $status = false;
            $message = "Something Went wrong";
        }

        $output = array(
            "status"=>$status,
            "message"=>$message,
            "csrfHash"=>$this->security->get_csrf_hash(),
        );
        echo json_encode($output);
    }
}

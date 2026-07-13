<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class ManageRestaurants extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('hotel_admin_session'))) {
            return redirect('hotel-admin-login');
        }
    }


    public function index()
    {
        $hotel_admin_id = $this->session->userdata('hotel_admin_session')['id'];
        $data['restaurants'] = $this->Common_model->getAllData('restaurants', array('hotel_admin_id' => $hotel_admin_id));


        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/manageRestaurants', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    //  insert a audit tracker start here

    public function insert()
    {

        // $this->check_login();
        $restaurants_name = $this->input->post('restaurants_name');
        $daily_budget = $this->input->post('daily_budget');
        $weekly_budget = $this->input->post('weekly_budget');
        $monthly_budget = $this->input->post('monthly_budget');
        $yearly_budget = $this->input->post('yearly_budget');
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');
        $hotel_admin_id = $this->session->userdata('hotel_admin_session')['id'];


        $restaurants_data = array(
            'restaurants_name' => $restaurants_name,
            'daily_budget' => $daily_budget,
            'weekly_budget' => $weekly_budget,
            'monthly_budget' => $monthly_budget,
            'yearly_budget' => $yearly_budget,
            'created_at' => $created_on,
            'updated_at' => $updated_on,
            'hotel_admin_id' => $hotel_admin_id
        );

        $record_id = $this->Comman_model->insertData('restaurants', $restaurants_data);

        $response_json['status'] = true;
        $response_json['message'] = "New restaurants has been added successfully";
        $response_json['record_id'] = $record_id;

        $this->session->set_flashdata('restaurants_success_msg', "Restaurant $name has been added successfully.");
        echo json_encode($response_json);
    }


    public function edit()
    {

        $id = $this->input->post('id');
        $result = $this->Common_model->getdata('restaurants', array('restaurants_id' => $id));
        echo json_encode($result);
    }


    public function update()
    {

        $record_id = $this->input->post('record_id');

        $restaurants_name = $this->input->post('restaurants_name');
        $daily_budget = $this->input->post('daily_budget');
        $weekly_budget = $this->input->post('weekly_budget');
        $monthly_budget = $this->input->post('monthly_budget');
        $yearly_budget = $this->input->post('yearly_budget');

        $updated_on = date('Y-m-d h:i:s');
        $hotel_admin_id = $this->session->userdata('hotel_admin_session')['id'];


        $restaurants_data = array(
            'restaurants_name' => $restaurants_name,
            'daily_budget' => $daily_budget,
            'weekly_budget' => $weekly_budget,
            'monthly_budget' => $monthly_budget,
            'yearly_budget' => $yearly_budget,

            'updated_at' => $updated_on,
            'hotel_admin_id' => $hotel_admin_id
        );


        $datas = $this->Comman_model->UpdateRecord('restaurants', $restaurants_data, array('restaurants_id' => $record_id));


        $response_json['status'] = true;
        $response_json['message'] = "Restaurant data has been updated successfully";
        $response_json['record_id'] = $record_id;

        $this->session->set_flashdata('restaurants_success_msg', "Restaurant $name has been updated successfully.");


        echo json_encode($response_json);
    }

    public function delete()
    {

        // $this->check_login();

        $id = $this->input->post('id');

        $table = 'restaurants';
        $where = array('restaurants_id' => $id);

        $data = $this->Comman_model->Deletedata('restaurants', array('restaurants_id' => $id));
        if ($data) {
            $response['status'] = true;
            $response['message'] = "Country Deleted successfully";
        } else {
            $response['status'] = false;
            $response['message'] = "Something Went wrong";
        }


        $this->session->set_flashdata('restaurants_success_msg', "Restaurant  has been Deleted successfully.");


        echo json_encode($response);
    }
}

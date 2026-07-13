<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class RegionalManager extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }


    public function index()
    {

        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', "");

        $data['regional_managers'] = $this->Common_model->getAllData('regional_managers', "");
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/regionalManager', $data);
        $this->load->view('super_admin/include/footer');
    }


    //  insert a audit tracker start here

    public function insert()
    {

        // $this->check_login();

        $full_name = $this->input->post('full_name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $password = md5($this->input->post('password'));
        $assigned_hotels = $this->input->post('hotel_id');

        $status = '1';
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        $regional_managers_data = array(
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'assigned_hotels' => $assigned_hotels,
            'status' => $status,
            'created_at' => $created_on,
            'updated_at' => $updated_on
        );

        $record_id = $this->Comman_model->insertData('regional_managers', $regional_managers_data);

        $response_json['status'] = true;
        $response_json['message'] = "New Regional Manager has been added successfully";
        $response_json['record_id'] = $record_id;

        $this->session->set_flashdata('regional_managers_success_msg', "New Regional Manager $name has been added successfully.");
        echo json_encode($response_json);
    }


    public function edit()
    {

        $id = $this->input->post('id');
        $result = $this->Common_model->getdata('regional_managers', array('id' => $id));
        echo json_encode($result);
    }


    public function update()
    {
        $record_id = $this->input->post('record_id');
        $full_name = $this->input->post('full_name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $assigned_hotels = $this->input->post('hotel_id');
        $password = md5($this->input->post('password'));

        $status = '1';
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        $regional_managers_data = array(
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'assigned_hotels' => $assigned_hotels,
            'status' => $status,
            'updated_at' => $updated_on
        );





        if (!empty($password)) {
            $regional_managers_data['password'] = $password;
        }




        $datas = $this->Comman_model->UpdateRecord('regional_managers', $regional_managers_data, array('id' => $record_id));


        $response_json['status'] = true;
        $response_json['message'] = "regional_managers data has been updated successfully";
        $response_json['record_id'] = $record_id;

        $this->session->set_flashdata('regional_managers_success_msg', "Regional Manager $name has been updated successfully.");


        echo json_encode($response_json);
    }

    public function delete()
    {

        // $this->check_login();

        $id = $this->input->post('id');

        $table = 'regional_managers';
        $where = array('id' => $id);

        $data = $this->Comman_model->Deletedata('regional_managers', array('id' => $id));
        if ($data) {
            $response['status'] = true;
            $response['message'] = "regional_managers Deleted successfully";
        } else {
            $response['status'] = false;
            $response['message'] = "Something Went wrong";
        }


        $this->session->set_flashdata('regional_managers_success_msg', "Regional Manager  has been Deleted successfully.");


        echo json_encode($response);
    }
}

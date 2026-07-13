<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class ManageSeniors extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }


    public function index()
    {

        $data['smngrs'] = $this->Common_model->getAllData('super_admin', array('user_role' => 2));
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageSenior', $data);
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

        $status = 'active';
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        $senior_managers_data = array(
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'status' => $status,
            'user_role' => 2,
            'created_at' => $created_on,
            'updated_at' => $updated_on
        );

        $record_id = $this->Comman_model->insertData('super_admin', $senior_managers_data);

        $response_json['status'] = true;
        $response_json['message'] = "New senior_managers has been added successfully";
        $response_json['record_id'] = $record_id;

        $this->session->set_flashdata('senior_managers_success_msg', "senior_managers $name has been added successfully.");
        echo json_encode($response_json);
    }


    public function edit()
    {

        $id = $this->input->post('id');
        $result = $this->Common_model->getdata('super_admin', array('id' => $id));
        echo json_encode($result);
    }


    public function update()
    {
        $record_id = $this->input->post('record_id');
        $full_name = $this->input->post('full_name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $password = md5($this->input->post('password'));

        $status = 'active';
        $created_on = date('Y-m-d h:i:s');
        $updated_on = date('Y-m-d h:i:s');

        $senior_managers_data = array(
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'user_role' => 2,
            'status' => $status,
            'updated_at' => $updated_on
        );





        if (!empty($password)) {
            $senior_managers_data['password'] = $password;
        }




        $datas = $this->Comman_model->UpdateRecord('super_admin', $senior_managers_data, array('id' => $record_id));


        $response_json['status'] = true;
        $response_json['message'] = "senior_managers data has been updated successfully";
        $response_json['record_id'] = $record_id;

        $this->session->set_flashdata('senior_managers_success_msg', "senior manager $name has been updated successfully.");


        echo json_encode($response_json);
    }

    public function delete()
    {

        // $this->check_login();

        $id = $this->input->post('id');

        $table = 'senior_managers';
        $where = array('senior_managers_id' => $id);

        $data = $this->Comman_model->Deletedata('senior_management', array('senior_managers_id' => $id));
        if ($data) {
            $response['status'] = true;
            $response['message'] = "senior_managers Deleted successfully";
        } else {
            $response['status'] = false;
            $response['message'] = "Something Went wrong";
        }


        $this->session->set_flashdata('senior_managers_success_msg', "senior_managers  has been Deleted successfully.");


        echo json_encode($response);
    }
}

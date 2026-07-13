<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Home extends MY_Controller
{
    public function __construct()
    {

        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');
    }





    public function check_login()
    {


        if (empty($this->session->userdata('admin_session'))) {

            $this->load->view('home/include/header');
            $this->load->view('home/401');
            $this->load->view('home/include/footer');


            $this->output->_display(); // Ensures the output is sent
            exit;
        } else {
            if ($this->session->userdata('role_as') == "guest_user") {
                redirect(base_url('/'));
            }
        }
    }


    public function validated_pm_or_dm($project_id)
    {


        $is_valid = $this->Comman_model->validated_pm_or_dm($project_id);

        $user_role = $this->session->userdata('role_as');

        if ($user_role == 'project_manager') {

            if (!$is_valid) {

                $this->load->view('home/include/header');
                $this->load->view('403');
                $this->load->view('home/include/footer');
                $this->output->_display(); // Ensures the output is sent
                exit;
            }
        }
    }


    public function index()
    {

        return redirect(base_url('superAdmin/login'));
    }
}

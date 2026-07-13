<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class Departments extends MY_Controller
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

        $data['countries'] = $this->Common_model->getAllData('departments', "");
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/managedepartment', $data);
        $this->load->view('hotel_admin/include/footer');
    }
}

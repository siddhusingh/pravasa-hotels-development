<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class Staff extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');

        if (empty($this->session->userdata('hotel_admin_session'))) {
            return redirect('hotel-admin-login');
        }
    }


    public function index()
    {

        $data['members'] = $this->Comman_model->gethotelmembersData();
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', '');


        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/managestaff', $data);
        $this->load->view('hotel_admin/include/footer');
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include('MyController');
class Guestcontactbook extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');

        if (empty($this->session->userdata('super_admin_session'))) {
            return redirect('super-admin-login');
        }

        $this->load->model('LeadModel'); // Load Model

    }


    public function index()
    {

        $data['guestcontactBook'] = $this->LeadModel->guestcontactBook();

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/guestcontactBook', $data);
        $this->load->view('super_admin/include/footer');
    }
}

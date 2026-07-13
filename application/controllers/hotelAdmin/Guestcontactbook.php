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
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('hotel_admin_session'))) {
            return redirect('hotel-admin-login');
        }

        $this->load->model('LeadModel'); // Load Model

    }


    public function index()
    {

        $hotel_session = $this->session->userdata('hotel_admin_session');

        $property = $hotel_session['id'];


        $data['guestcontactBook'] = $this->LeadModel->guestcontactBookAdmin($property);

        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/guestcontactBook', $data);
        $this->load->view('hotel_admin/include/footer');
    }
}

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
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $data['members'] = $this->db
            ->select('staff_members.*')
            ->from('staff_members')
            ->join('staff_hotel_department_mapping mapping', 'mapping.staff_id = staff_members.id', 'inner')
            ->where('mapping.hotel_id', $property)
            ->where('staff_members.is_deleted', 0)
            ->group_by('staff_members.id')
            ->order_by('staff_members.name', 'ASC')
            ->get()
            ->result();
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', '');


        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/managestaff', $data);
        $this->load->view('hotel_admin/include/footer');
    }
}

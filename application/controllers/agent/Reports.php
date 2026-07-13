<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

require_once FCPATH . 'vendor/autoload.php';  // Correct path to autoload.php


class Reports extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeadModel'); // Load Model
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');
    }



    public function index()
    {

        if (empty($this->session->userdata('agent_session'))) {
            return redirect('agent-login');
        }


        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        $property = $this->session->userdata('selected_hotel_id');
        $department = $this->session->userdata('selected_department_id');

        // echo "<pre>";
        // echo  $department;
        // die();

        // Get filter values from GET request
        $filters = [
            'city' => $this->input->get('city'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => [$department],
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'disposition' => $this->input->get('disposition'), // 🆕 Added Stage filter

        ];






        // Clean filters (remove empty values)
        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        // Fetch filtered leads
        $data['leads'] = $this->LeadModel->get_leads_for_reports($activeFilters);





        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');
        $data['cities'] = $this->Common_model->getAllData('city', '');

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');






        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/reports/custom_report', $data);
        $this->load->view('agent/include/footer');
    }
}

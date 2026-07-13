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

        if (empty($this->session->userdata('hotel_admin_session'))) {
            return redirect('hotel-admin-login');
        }
    }



    public function index()
    {




        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        $hotel_session = $this->session->userdata('hotel_admin_session');


        $property = $hotel_session['id'];




        // Get filter values from GET request
        $filters = [
            'city' => $this->input->get('city'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->get('department'),
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






        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/custom_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    public function lead_by_dispostion()
    {




        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];


        $data['leads'] = $this->LeadModel->get_disposition_report($filters);





        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/lead_by_dispositons', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    public function lead_by_department()
    {




        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];


        $data['leads'] = $this->LeadModel->get_department_report($filters);






        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/lead_by_department', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    public function lead_by_source()
    {




        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];


        $data['leads'] = $this->LeadModel->get_user_channel_report($filters);






        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/lead_by_source', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    public function hotel_department_status_report()
    {
        // Get filters from POST

        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];


        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['departments'] = $this->Common_model->getAllData('departments', ['department_id <>' => 14]);

        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $data['report_data']  = $this->LeadModel->get_hotel_department_status_report($filters);




        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/get_hotel_department_status_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    public function summary_report()
    {
        // Get filters from POST
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $data['report_data']  = $this->LeadModel->get_property_department_channel_report($filters);




        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/summary_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    public function filter_lead_by_dispostion()
    {







        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['leads'] = $this->LeadModel->get_disposition_report($filters);

        $html = $this->load->view('hotel_admin/reports/filter_lead_by_disposition', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true

        ]);
    }


    public function filter_lead_by_department()
    {









        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['leads'] = $this->LeadModel->get_department_report($filters);

        $html = $this->load->view('hotel_admin/reports/filter_lead_by_department', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true

        ]);
    }


    public function filter_lead_by_source()
    {




        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');

        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['leads'] = $this->LeadModel->get_user_channel_report($filters);



        $html = $this->load->view('hotel_admin/reports/filter_lead_by_source', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true

        ]);
    }


    public function filter_hotel_department_status_report()
    {
        // Get filters from POST
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['departments'] = $this->Common_model->getAllData('departments', '');

        $data['departments'] = $this->Common_model->getAllData('departments', ['department_id <>' => 14]);




        $data['report_data']  = $this->LeadModel->get_hotel_department_status_report($filters);




        $html = $this->load->view('hotel_admin/reports/filter_get_hotel_department_status_report', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true

        ]);
    }


    public function filter_summary_report()
    {
        // Get filters from POST
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];






        $data['report_data']  = $this->LeadModel->get_property_department_channel_report($filters);


        $html = $this->load->view('hotel_admin/reports/filter_summary_report', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true

        ]);
    }

    public function filter_custom_report()
    {

        // Get filters from POST
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = $hotel_session['id'];


        // Get filter values from GET request
        $filters = [
            'city' => $this->input->post('city'),
            'property'   => $this->input->post('property') ?: [$property],  // ✅ force array
            'department' => $this->input->post('department'),
            'status' => $this->input->post('status'),
            'channel' => $this->input->post('channel'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'disposition' => $this->input->post('disposition'), // 🆕 Added Stage filter
            'business_type' => $this->input->post('business_type'), // 🆕 Added Stage filter


        ];




        // Fetch filtered leads
        $data['leads'] = $this->LeadModel->get_leads_for_reports($filters);


        $html = $this->load->view('hotel_admin/reports/filter_custom_report', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true

        ]);
    }
}

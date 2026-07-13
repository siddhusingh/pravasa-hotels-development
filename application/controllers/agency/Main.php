<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Dashboard_model');
        $this->load->model('LeadModel');






        $this->load->model('LeadModel');
    }

    public function index()
    {



        $agency_session = $this->session->userdata('agency_session');

        if (!empty($agency_session)) {

            $agency_id = $this->session->userdata('agency_session')['id'];



            // Load all data to send to view
            $data = [

                'lead_status_counts'   => [
                    'Open'        => $this->LeadModel->get_lead_count_by_status_agency('Open', "", "", "", "",  $agency_id),
                    'In Progress' => $this->LeadModel->get_lead_count_by_status_agency('In Progress', "", "", "", "",  $agency_id),
                    'On Hold'     => $this->LeadModel->get_lead_count_by_status_agency('On Hold', "", "", "", "",  $agency_id),
                    'Closed'      => $this->LeadModel->get_lead_count_by_status_agency('Closed',  "", "", "", "", $agency_id),
                    'Reservation'        => $this->LeadModel->get_lead_count_by_disposition_agency('Reservation', "", "", "", "",  $agency_id),
                    'followup' => $this->LeadModel->get_lead_count_by_disposition_agency('Shopping - Follow up', "", "", "", "",  $agency_id),
                    'Information'     => $this->LeadModel->get_lead_count_by_disposition_agency('Information/Enquiry', "", "", "", "",  $agency_id),
                    'Denied'      => $this->LeadModel->get_lead_count_by_disposition_agency('Denied', "", "", "", "",  $agency_id),
                ],
            ];


            $agency_id = $this->session->userdata('agency_session')['id'];


            $data['total_leads'] = $this->Common_model->count_all('leads', ['created_by' => $agency_id]);




            $data['properties'] = $this->Common_model->get_properties_by_agency($agency_id);



            $data['departments'] = $this->Common_model->getAllData('departments', '');

            $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');


            $this->load->view('agency/include/header');
            $this->load->view('agency/include/sidebar');
            $this->load->view('agency/dashboard', $data);
            $this->load->view('agency/include/footer');
        } else {
            $this->load->view('agency/login');
        }
    }




    public function dashboard_top_filter()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $property = $this->session->userdata('selected_hotel_id');
        $department = $this->session->userdata('selected_department_id');


        // Use the filters in your model accordingly
        // Load all data to send to view
        $data = [

            'Open'        => $this->LeadModel->get_lead_count_by_status_agency('Open', $agency_id),
            $start_date,
            $end_date,
            'In Progress' => $this->LeadModel->get_lead_count_by_status_agency('In Progress', $agency_id, $start_date, $end_date),
            'On Hold'     => $this->LeadModel->get_lead_count_by_status_agency('On Hold', $agency_id, $start_date, $end_date),
            'Closed'      => $this->LeadModel->get_lead_count_by_status_agency('Closed', $agency_id, $start_date, $end_date),
            'Reservation'        => $this->LeadModel->get_lead_count_by_disposition_agency('Reservation', $agency_id, $start_date, $end_date),
            'followup' => $this->LeadModel->get_lead_count_by_disposition_agency('Shopping - Follow up', $agency_id, $start_date, $end_date),
            'Information'     => $this->LeadModel->get_lead_count_by_disposition_agency('Information/Enquiry', $agency_id, $start_date, $end_date),
            'Denied'      => $this->LeadModel->get_lead_count_by_disposition_agency('Denied', $agency_id, $start_date, $end_date),
            'total_calls' => $this->Common_model->count_all_lead_calls(
                'calls',
                array('property' => $property, 'type' => $department, 'overall_call_status' => ''),
                $start_date,
                $end_date
            ),
            'total_answered_calls' => $this->Common_model->count_all_lead_calls(
                'calls',
                array('overall_call_status' => 'Answered', 'property' => $property, 'type' => $department),
                $start_date,
                $end_date
            ),
            'total_missed_calls' => $this->Common_model->count_all_lead_calls(
                'calls',
                array('overall_call_status' => 'Missed', 'property' => $property, 'type' => $department),
                $start_date,
                $end_date
            ),
            'total_revenue' => $this->Common_model->get_total_revenue_from_leads(
                'leads',
                array('property' => $property, 'type' => $department),
                $start_date,
                $end_date
            )

        ];

        $data['total_leads'] = $this->Common_model->count_all('leads', ['property' => $property, 'type' => $department], $start_date, $end_date);

        echo json_encode($data);
    }



    // Chart Data Endpoints
    public function department_chart_data()
    {
        $filters = $this->input->get();




        $this->send_chart_data('get_leads_by_department', 'departments', 'department_id', 'department_id', 'department_name', $filters);
    }




    public function disposition_chart_data()
    {
        $filters = $this->input->get();

        $data = $this->Dashboard_model->get_leads_grouped_by('disposition', $filters);
        $this->send_static_chart_data($data, 'disposition');
    }

    public function template_chart_data()
    {
        $filters = $this->input->get();

        $data = $this->Dashboard_model->get_leads_grouped_by('template_name', $filters);
        $this->send_static_chart_data($data, 'template_name');
    }

    public function source_chart_data()
    {
        $filters = $this->input->get();

        $data = $this->Dashboard_model->get_leads_grouped_by('user_channel', $filters);
        $this->send_static_chart_data($data, 'user_channel');
    }

    public function status_chart_data()
    {
        $filters = $this->input->get();

        $data = $this->Dashboard_model->get_leads_grouped_by('status', $filters);
        $this->send_static_chart_data($data, 'status');
    }

    public function guest_type_chart_data()
    {
        $filters = $this->input->get();

        $data = $this->Dashboard_model->get_guest_type_data($filters);

        // Format it like [{ label: 'New Guest', count: 10 }, { label: 'Repeat Guest', count: 5 }]
        $response = [
            ['label' => 'New Guest', 'count' => $data['normal']],
            ['label' => 'Repeat Guest', 'count' => $data['repeat']]
        ];

        echo json_encode($response);
    }











    // Reusable method for grouped charts
    private function send_chart_data($model_method, $table, $where_col, $group_col, $label_col, $filters)
    {
        $data = $this->Dashboard_model->$model_method($filters);
        $formatted = [];




        foreach ($data as $row) {
            $id = $row->$group_col;
            $label = $this->Common_model->get_field_value($table, $where_col, $id, $label_col);
            $formatted[] = [
                'label' => $label ?: 'NA',
                'count' => $row->total
            ];
        }

        echo json_encode($formatted);
    }

    // For status which doesn't use reference tables
    private function send_static_chart_data($data, $field)
    {
        $formatted = [];

        foreach ($data as $row) {
            $label = $row->$field ?: 'NA';
            $formatted[] = [
                'label' => ucfirst($label),
                'count' => $row->total
            ];
        }

        echo json_encode($formatted);
    }







    /*load user profile detail*/
    public function profile()
    {
        $agency_session = $this->session->userdata('agency_session');
        $login_id = $agency_session['id'];

        // Fetch agent profile
        $data['profile_data'] = $this->Comman_model->get_single_record('agencies', ['id' => $login_id]);





        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/profile', $data);
        $this->load->view('agency/include/footer');
    }


    /*load user profile detail*/
    public function account_settings()
    {
        $login_id = $this->session->userdata('agency_session')['id'];
        $data['profile_data'] = $this->Comman_model->get_single_record('staff_members', ['id' => $login_id]);
        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/account_settings', $data);
        $this->load->view('agency/include/footer');
    }

    /*load user profile detail*/
    public function update_profile()
    {

        $id = $this->input->post('id');
        $full_name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');

        $password = md5($this->input->post('password'));

        $this->session->set_userdata('logged_in_username', $full_name);

        $table = 'agencies';
        $data = array(
            'contact_person' => $full_name,
            'email' => $email,
            'phone' => $phone,

        );

        if (!empty($this->input->post('password'))) {
            $data['password'] = $password;
        }


        $result = $this->Comman_model->UpdateRecord($table, $data, array('id' => $id));

        $response_json['status'] = true;
        $response_json['message'] = "senior_managers data has been updated successfully";
        $response_json['record_id'] = $record_id;

        $this->session->set_flashdata('profile_success', "Your profile has been updated successfully.");


        echo json_encode($response_json);
    }





    /*Main Class Ending*/
}

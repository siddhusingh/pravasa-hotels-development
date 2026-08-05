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
            return redirect('agency-login');
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
        if (empty($agency_session['id']) || $this->session->userdata('role_as') !== 'agency') {
            return redirect('agency-login');
        }

        $login_id = (int) $agency_session['id'];

        $data['profile_data'] = $this->Comman_model->get_single_record('agencies', ['id' => $login_id]);
        if (empty($data['profile_data'])) {
            show_404();
            return;
        }

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
        if ($this->input->method() !== 'post') {
            return $this->output
                ->set_status_header(405)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Method not allowed.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $agency_session = $this->session->userdata('agency_session');
        if (empty($agency_session['id']) || $this->session->userdata('role_as') !== 'agency') {
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Your session has expired.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $id = (int) $agency_session['id'];
        $full_name = trim((string) $this->input->post('name', true));
        $phone = trim((string) $this->input->post('phone', true));
        $email = trim((string) $this->input->post('email', true));
        $password = (string) $this->input->post('password');
        $errors = [];

        if ($full_name === '') {
            $errors['name'] = 'Please Enter Full Name';
        }
        if ($email === '') {
            $errors['email'] = 'Please Enter Email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please Enter a Valid Email Address';
        }
        if ($phone === '') {
            $errors['phone'] = 'Please Enter Phone Number';
        }
        if ($password !== '' && !preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/', $password)) {
            $errors['password'] = 'Password must be at least 6 characters long, contain at least one number and one special character';
        }

        if (!empty($errors)) {
            return $this->output
                ->set_status_header(422)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Please correct the highlighted fields.',
                    'errors' => $errors,
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $data = [
            'contact_person' => $full_name,
            'email' => $email,
            'phone' => $phone
        ];

        // An empty password intentionally preserves the existing password.
        if ($password !== '') {
            $data['password'] = md5($password);
        }

        $result = $this->Comman_model->UpdateRecord('agencies', $data, ['id' => $id]);
        if (!$result) {
            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Unable to update your profile. Please try again.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $this->session->set_userdata('logged_in_username', $full_name);
        $this->session->set_flashdata('profile_success', 'Your profile has been updated successfully.');

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Your profile has been updated successfully.',
                'record_id' => $id,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }





    /*Main Class Ending*/
}

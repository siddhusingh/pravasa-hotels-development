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



        $agent_session = $this->session->userdata('agent_session');

        if (!empty($agent_session) && !empty($this->session->userdata('selected_hotel_id'))) {

            $property = $this->session->userdata('selected_hotel_id');
            $department = $this->session->userdata('selected_department_id');




            // Load all data to send to view
            $data = [

                'lead_status_counts'   => [
                    'Open' => $this->LeadModel->get_lead_count_by_status('Open', $property),
                    'In Progress' => $this->LeadModel->get_lead_count_by_status('In Progress', $property),
                    'On Hold' => $this->LeadModel->get_lead_count_by_status('On Hold', $property),
                    'Closed' => $this->LeadModel->get_lead_count_by_status('Closed', $property),
                    'Not_Assigned' => $this->LeadModel->get_lead_count_for_not_assigned('', $property),
                    'Not_Contacted' => $this->LeadModel->get_lead_count_by_disposition('Not Contacted', $property),
                    'Quotation_Sent' => $this->LeadModel->get_lead_count_by_disposition('Quotation Sent', $property),
                    'Negotiations' => $this->LeadModel->get_lead_count_by_disposition('Negotiations', $property),
                    'Contract_Done' => $this->LeadModel->get_lead_count_by_disposition('Contract Done', $property),
                    'Advance_Received' => $this->LeadModel->get_lead_count_by_disposition('Advance Received', $property),
                    'Lead_Won' => $this->LeadModel->get_lead_count_by_disposition('Lead Won', $property),
                    'Lead_Lost' => $this->LeadModel->get_lead_count_by_disposition('Lead Lost', $property),


                ],
                'lead_revenue' => [

                    'Not_Contacted' => $this->LeadModel->get_lead_revenue_by_disposition('Not Contacted', $property),

                    'Quotation_Sent' => $this->LeadModel->get_lead_revenue_by_disposition('Quotation Sent', $property),

                    'Negotiations' => $this->LeadModel->get_lead_revenue_by_disposition('Negotiations', $property),

                    'Contract_Done' => $this->LeadModel->get_lead_revenue_by_disposition('Contract Done', $property),

                    'Advance_Received' => $this->LeadModel->get_lead_revenue_by_disposition('Advance Received', $property),

                    'Lead_Won' => $this->LeadModel->get_lead_revenue_by_disposition('Lead Won', $property),

                    'Lead_Lost' => $this->LeadModel->get_lead_revenue_by_disposition('Lead Lost', $property)

                ]
            ];



            $data['total_leads'] = $this->Common_model->count_all('leads', ['property' => $property]);

            $data['total_revenue'] = $this->Common_model->get_total_revenue_from_leads('leads', ['property' => $property]);





            $data['all_assignable_users'] = $this->LeadModel->get_all_assignable_users('hotel_admin', '');


            $data['creators'] = $this->LeadModel->get_active_creators();

            $data['assigned_users'] = $this->LeadModel->get_active_assigned_users();


            $data['departments'] = $this->Common_model->getAllData('departments', '');
            $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');
            $data['cities'] = $this->Common_model->getAllData('city', '');
            $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', [
                'property' => $property,
                'is_deleted' => 0
            ]);













            $this->load->view('agent/include/header');
            $this->load->view('agent/include/sidebar');
            $this->load->view('agent/dashboard', $data);
            $this->load->view('agent/include/footer');
        } else {
            $this->load->view('agent/login');
        }
    }




    public function dashboard_top_filter()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $property = $this->session->userdata('selected_hotel_id');
        $department = '';

        $created_id    = $this->input->post('created_id');
        $created_role  = $this->input->post('created_role');

        $assigned_id   = $this->input->post('assigned_id');
        $assigned_role = $this->input->post('assigned_role');

        $channel = $this->input->post('channel');
        $disposition = $this->input->post('disposition');



        // Use the filters in your model accordingly
        // Load all data to send to view

        $data = [

            /* ===============================
       STATUS COUNTS
    =============================== */

            'Open' => $this->LeadModel->get_lead_count_by_status(
                'Open',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition

            ),

            'In Progress' => $this->LeadModel->get_lead_count_by_status(
                'In Progress',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition

            ),

            'On Hold' => $this->LeadModel->get_lead_count_by_status(
                'On Hold',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition

            ),

            'Closed' => $this->LeadModel->get_lead_count_by_status(
                'Closed',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition

            ),

            'Not_Assigned' => $this->LeadModel->get_lead_count_for_not_assigned(
                '',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition

            ),


            /* ===============================
       DISPOSITION COUNTS
    =============================== */

            'Not_Contacted' => $this->LeadModel->get_lead_count_by_disposition(
                'Not Contacted',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Quotation_Sent' => $this->LeadModel->get_lead_count_by_disposition(
                'Quotation Sent',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Negotiations' => $this->LeadModel->get_lead_count_by_disposition(
                'Negotiations',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Contract_Done' => $this->LeadModel->get_lead_count_by_disposition(
                'Contract Done',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Advance_Received' => $this->LeadModel->get_lead_count_by_disposition(
                'Advance Received',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Lead_Won' => $this->LeadModel->get_lead_count_by_disposition(
                'Lead Won',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Lead_Lost' => $this->LeadModel->get_lead_count_by_disposition(
                'Lead Lost',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),


            /* ===============================
       REVENUE
    =============================== */

            'Not_Contacted_Revenue' => $this->LeadModel->get_lead_revenue_by_disposition(
                'Not Contacted',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Quotation_Sent_Revenue' => $this->LeadModel->get_lead_revenue_by_disposition(
                'Quotation Sent',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Negotiations_Revenue' => $this->LeadModel->get_lead_revenue_by_disposition(
                'Negotiations',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Contract_Done_Revenue' => $this->LeadModel->get_lead_revenue_by_disposition(
                'Contract Done',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Advance_Received_Revenue' => $this->LeadModel->get_lead_revenue_by_disposition(
                'Advance Received',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Lead_Won_Revenue' => $this->LeadModel->get_lead_revenue_by_disposition(
                'Lead Won',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'Lead_Lost_Revenue' => $this->LeadModel->get_lead_revenue_by_disposition(
                'Lead Lost',
                $property,
                $start_date,
                $end_date,
                $department,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),


            /* ===============================
       OVERALL
    =============================== */

            'total_leads' => $this->Common_model->count_all(
                'leads',
                [
                    'property' => $property,
                    'type'     => $department
                ],
                $start_date,
                $end_date,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            ),

            'total_revenue' => $this->Common_model->get_total_revenue_from_leads(
                'leads',
                [
                    'property' => $property,
                    'type'     => $department
                ],
                $start_date,
                $end_date,
                $created_id,
                $created_role,
                $assigned_id,
                $assigned_role,
                $channel,
                $disposition
            )

        ];



        echo json_encode($data);
    }



    // Chart Data Endpoints
    public function department_chart_data()
    {
        $filters = $this->get_agent_chart_filters();




        $this->send_chart_data('get_leads_by_department', 'departments', 'department_id', 'department_id', 'department_name', $filters);
    }




    public function disposition_chart_data()
    {
        $filters = $this->get_agent_chart_filters();

        $data = $this->Dashboard_model->get_leads_grouped_by('disposition', $filters);
        $this->send_static_chart_data($data, 'disposition');
    }

    public function template_chart_data()
    {
        $filters = $this->get_agent_chart_filters();

        $data = $this->Dashboard_model->get_leads_grouped_by('template_name', $filters);
        $this->send_static_chart_data($data, 'template_name');
    }

    public function source_chart_data()
    {
        $filters = $this->get_agent_chart_filters();

        $data = $this->Dashboard_model->get_leads_grouped_by('user_channel', $filters);
        $this->send_static_chart_data($data, 'user_channel');
    }

    public function status_chart_data()
    {
        $filters = $this->get_agent_chart_filters();

        $data = $this->Dashboard_model->get_leads_grouped_by('status', $filters);
        $this->send_static_chart_data($data, 'status');
    }

    public function guest_type_chart_data()
    {
        $filters = $this->get_agent_chart_filters();

        $data = $this->Dashboard_model->get_guest_type_data($filters);

        // Format it like [{ label: 'New Guest', count: 10 }, { label: 'Repeat Guest', count: 5 }]
        $response = [
            ['label' => 'New Guest', 'count' => $data['normal']],
            ['label' => 'Repeat Guest', 'count' => $data['repeat']]
        ];

        echo json_encode($response);
    }

    private function get_agent_chart_filters()
    {
        $filters = $this->input->get();
        $filters['property'] = $this->session->userdata('selected_hotel_id');
        $filters['type'] = '';

        return $filters;
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
        $agent_session = $this->session->userdata('agent_session');
        $login_id = $agent_session['id'];
        $selected_hotel_id = $this->session->userdata('selected_hotel_id');

        // Fetch agent profile
        $data['profile_data'] = $this->Comman_model->get_single_record('staff_members', ['id' => $login_id]);

        // Fetch selected hotel name
        if (!empty($selected_hotel_id)) {
            $hotel_data = $this->Comman_model->get_single_record('hotel_admin', ['hotel_id' => $selected_hotel_id]);
            $data['selected_hotel_name'] = !empty($hotel_data) ? $hotel_data->hotel_name : '';
        } else {
            $data['selected_hotel_name'] = '';
        }



        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/profile', $data);
        $this->load->view('agent/include/footer');
    }


    /*load user profile detail*/
    public function account_settings()
    {
        $login_id = $this->session->userdata('agent_session')['id'];
        $data['profile_data'] = $this->Comman_model->get_single_record('staff_members', ['id' => $login_id]);
        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/account_settings', $data);
        $this->load->view('agent/include/footer');
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

        $table = 'staff_members';
        $data = array(
            'name' => $full_name,
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

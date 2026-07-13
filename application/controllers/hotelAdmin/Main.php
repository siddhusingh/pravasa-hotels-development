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





        if (empty($this->session->userdata('hotel_admin_session'))) {
            return redirect('hotel-admin-login');
        }
    }

    public function index()
    {

        if (!empty($this->session->userdata('hotel_admin_session'))) {


            $this->load->model('LeadModel');

            $hotel_session = $this->session->userdata('hotel_admin_session');


            $property = $hotel_session['id'];



            $this->load->model('LeadModel');



            // Get lead counts



            // Load all data to send to view



            // Load all data to send to view
            $data = [

                'lead_status_counts'   => [
                    'Open'        => $this->LeadModel->get_lead_count_by_status('Open', $property),
                    'In Progress' => $this->LeadModel->get_lead_count_by_status('In Progress', $property),
                    'On Hold'     => $this->LeadModel->get_lead_count_by_status('On Hold', $property),
                    'Closed'      => $this->LeadModel->get_lead_count_by_status('Closed', $property),
                    'Not_Assigned'      => $this->LeadModel->get_lead_count_for_not_assigned(''),
                    'Not_Contacted'        => $this->LeadModel->get_lead_count_by_disposition('Not Contacted', $property),
                    'Quotation_Sent' => $this->LeadModel->get_lead_count_by_disposition('Quotation Sent', $property),
                    'Negotiations'     => $this->LeadModel->get_lead_count_by_disposition('Negotiations', $property),
                    'Contract_Done'      => $this->LeadModel->get_lead_count_by_disposition('Contract Done', $property),
                    'Advance_Received'      => $this->LeadModel->get_lead_count_by_disposition('Advance Received', $property),
                    'Lead_Won'      => $this->LeadModel->get_lead_count_by_disposition('Lead Won', $property),
                    'Lead_Lost'      => $this->LeadModel->get_lead_count_by_disposition('Lead Lost', $property),


                ],
                'lead_revenue' => [

                    'Not_Contacted'   => $this->LeadModel->get_lead_revenue_by_disposition('Not Contacted', $property),

                    'Quotation_Sent'  => $this->LeadModel->get_lead_revenue_by_disposition('Quotation Sent', $property),

                    'Negotiations'    => $this->LeadModel->get_lead_revenue_by_disposition('Negotiations', $property),

                    'Contract_Done'   => $this->LeadModel->get_lead_revenue_by_disposition('Contract Done', $property),

                    'Advance_Received' => $this->LeadModel->get_lead_revenue_by_disposition('Advance Received', $property),

                    'Lead_Won'        => $this->LeadModel->get_lead_revenue_by_disposition('Lead Won', $property),

                    'Lead_Lost'       => $this->LeadModel->get_lead_revenue_by_disposition('Lead Lost', $property)

                ]
            ];














            $data['total_leads'] = $this->Common_model->count_all('leads', ['property' => $property]);

            $data['total_revenue'] = $this->Common_model->get_total_revenue_from_leads('leads', ['property' => $property]);



            $data['departments'] = $this->Common_model->getAllData('departments', '');
            $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');
            $data['cities'] = $this->Common_model->getAllData('city', '');
            $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');

            $data['all_assignable_users'] = $this->LeadModel->get_all_assignable_users('hotel_admin', '');


            $data['creators'] = $this->LeadModel->get_active_creators();

            $data['assigned_users'] = $this->LeadModel->get_active_assigned_users();









            $this->load->view('hotel_admin/include/header');
            $this->load->view('hotel_admin/include/sidebar');
            $this->load->view('hotel_admin/dashboard', $data);
            $this->load->view('hotel_admin/include/footer');
        } else {

            $this->load->view('hotel_admin/login');
        }
    }






    public function dashboard_top_filter()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $department = $this->input->post('department');




        $hotel_session = $this->session->userdata('hotel_admin_session');

        $channel = $this->input->post('channel');
        $disposition = $this->input->post('disposition');

        $created_id    = $this->input->post('created_id');
        $created_role  = $this->input->post('created_role');

        $assigned_id   = $this->input->post('assigned_id');
        $assigned_role = $this->input->post('assigned_role');



        $property = $hotel_session['id'];

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
        $login_id = $this->session->userdata('hotel_admin_session')['id'];
        $data['profile_data'] = $this->Comman_model->get_single_record('hotel_admins', ['hotel_id' => $login_id]);
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/profile', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    /*load user profile detail*/
    public function account_settings()
    {
        $login_id = $this->session->userdata('hotel_admin_session')['id'];
        $data['profile_data'] = $this->Comman_model->get_single_record('hotel_admin', ['id' => $login_id]);
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/account_settings', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    /*load user profile detail*/
    public function update_profile()
    {

        $id = $this->input->post('id');
        $full_name = $this->input->post('full_name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');

        $password = md5($this->input->post('password'));

        $this->session->set_userdata('logged_in_username', $full_name);

        $table = 'hotel_admins';

        $data = array(
            'name' => $full_name,
            'email' => $email,
            'phone' => $phone,

        );

        if (!empty($this->input->post('password'))) {
            $data['password'] = $password;
        }

        $this->session->set_userdata('logged_in_username', $full_name);

        $session_data = [
            'id' => $id,
            'logged_in' => true,
            'user_name' => $full_name,
            'phone' => $phone
        ];





        $this->session->set_userdata('hotel_admin_session', $session_data);



        $result = $this->Comman_model->UpdateRecord($table, $data, array('id' => $id));

        $response_json['status'] = true;
        $response_json['message'] = "senior_managers data has been updated successfully";
        $response_json['record_id'] = $record_id;

        $this->session->set_flashdata('profile_success', "Your profile has been updated successfully.");


        echo json_encode($response_json);
    }


    /*code for download resume file*/
    public function download_file($id)
    {

        /*if id exist*/
        if ($id) {

            $fileArray = $this->Comman_model->getdata('instructor', array('id' => $id));

            /*if file not blank*/
            if (!empty($fileArray) && isset($fileArray->resume_file)) {

                $file_name = FCPATH . '/uploads/documents/resume_file/' . $fileArray->resume_file;

                if (file_exists($file_name)) {

                    $this->load->helper('download');

                    $pth    =   file_get_contents($file_name);
                    $nme    =  "instructor_resume_" . $fileArray->resume_file;
                    force_download($nme, $pth);
                } else {

                    redirect(base_url('admin/instructor/view_instructor/') . $id);
                }


                /*else redirect*/
            } else {

                redirect(base_url('admin/main'));
            }

            /*else redirect*/
        } else {

            redirect(base_url('admin/main'));
        }
    }




    /*show terms and conditions*/
    public function manageTermsAndCondition()
    {

        $admin_id =  $this->session->userdata('admin_session')['admin_id'];

        $data['termsconditions'] = $this->Comman_model->get_column_rec('terms_conditions', ['managed_by' => $admin_id], ['t_and_c_data']);

        $this->load->view('admin/termsConditions/terms-conditions', $data);
    }

    public function edit_terms_conditions()
    {

        $admin_id =  $this->session->userdata('admin_session')['admin_id'];

        $data['termsconditions'] = $this->Comman_model->get_column_rec('terms_conditions', ['managed_by' => $admin_id], ['t_and_c_data']);

        $this->load->view('admin/termsConditions/edit_terms_and_conditions', $data);
    }

    public function update_terms_conditions()
    {

        $postData = $this->input->post();

        if (!empty($postData)) {

            $terms_conditions = $postData['terms_and_conditions'];

            $updateStatus = $this->Comman_model->UpdateRecord('terms_conditions', ['t_and_c_data' => $terms_conditions], ['id' => 1]);


            /*if updated success*/
            if ($updateStatus) {

                $this->session->set_flashdata('actionResponse', true);
                redirect('admin/main/manageTermsAndCondition');
            } else {
                /*else return fail*/

                $this->session->set_flashdata('actionResponse', false);
                redirect('admin/main/manageTermsAndCondition');
            }
        } else {

            return redirect(base_url());
        }
    }



    /*show terms and conditions*/
    public function manage_referral()
    {

        $data['reff_amount'] = $this->Comman_model->getAllData('referral_tbl', $where = "", $oderBy = "id")[0];

        $this->load->view('admin/manage_referral', $data);
    }



    /*show terms and conditions*/
    public function update_referralAmount()
    {

        if ($this->input->post()) {

            $refAmount = $this->input->post('referral_amount');
            $update_id = $this->input->post('update_id');

            $updateStatus = $this->Comman_model->UpdateRecord('referral_tbl', ['referral_amount' => $refAmount], ['id' => $update_id]);

            /*if data upated*/
            if ($updateStatus) {

                $this->session->set_flashdata('actionResponse', true);
                redirect('admin/main/manage_referral');
            } else {

                /*else not updated */
                $this->session->set_flashdata('actionResponse', false);
                redirect('admin/main/manage_referral');
            }
        } else {

            /*if data not posted*/
            if ($updateStatus) {

                $data['actionResponse'] = false;
                redirect('admin/main/manage_referral', $data);
            }
        }
    }


    function raisedTickets()
    {

        $this->load->view('admin/raisedTickets/index');
    }


    /*Main Class Ending*/
}

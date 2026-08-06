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
        if (empty($agency_session['id']) || $this->session->userdata('role_as') !== 'agency') {
            return redirect('agency-login');
        }

        $agencyId = (int) $agency_session['id'];
        $filters = [];
        $data = [
            'lead_status_counts' => [
                'Open' => $this->countAgencyLeads($agencyId, $filters, 'status', 'Open'),
                'In Progress' => $this->countAgencyLeads($agencyId, $filters, 'status', 'In Progress'),
                'On Hold' => $this->countAgencyLeads($agencyId, $filters, 'status', 'On Hold'),
                'Closed' => $this->countAgencyLeads($agencyId, $filters, 'status', 'Closed'),
                'Reservation' => $this->countAgencyLeads($agencyId, $filters, 'disposition', 'Reservation'),
                'followup' => $this->countAgencyLeads($agencyId, $filters, 'disposition', 'Shopping - Follow up'),
                'Information' => $this->countAgencyLeads($agencyId, $filters, 'disposition', 'Information/Enquiry'),
                'Denied' => $this->countAgencyLeads($agencyId, $filters, 'disposition', 'Denied')
            ],
            'total_leads' => $this->countAgencyLeads($agencyId, $filters),
            'properties' => $this->Common_model->get_properties_by_agency($agencyId),
            'departments' => $this->Common_model->getAllData('departments', ['is_deleted' => 0]),
            'user_channel' => $this->Common_model->getAlluser_channel('leads', [
                'is_deleted' => 0,
                'created_by' => $agencyId,
                'creator_user_role' => 'Agency'
            ])
        ];

        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/dashboard', $data);
        $this->load->view('agency/include/footer');
    }




    public function dashboard_top_filter()
    {
        $agencyId = $this->requireAgencyJson();
        if (!$agencyId) {
            return;
        }

        $filters = $this->agencyDashboardFiltersFromPost($agencyId);
        if ($filters === null) {
            return;
        }

        $data = [
            'Open' => $this->countAgencyLeads($agencyId, $filters, 'status', 'Open'),
            'In Progress' => $this->countAgencyLeads($agencyId, $filters, 'status', 'In Progress'),
            'On Hold' => $this->countAgencyLeads($agencyId, $filters, 'status', 'On Hold'),
            'Closed' => $this->countAgencyLeads($agencyId, $filters, 'status', 'Closed'),
            'Reservation' => $this->countAgencyLeads($agencyId, $filters, 'disposition', 'Reservation'),
            'followup' => $this->countAgencyLeads($agencyId, $filters, 'disposition', 'Shopping - Follow up'),
            'Information' => $this->countAgencyLeads($agencyId, $filters, 'disposition', 'Information/Enquiry'),
            'Denied' => $this->countAgencyLeads($agencyId, $filters, 'disposition', 'Denied'),
            'total_calls' => $this->countAgencyCalls($agencyId, $filters),
            'total_answered_calls' => $this->countAgencyCalls($agencyId, $filters, 'Answered'),
            'total_missed_calls' => $this->countAgencyCalls($agencyId, $filters, 'Missed'),
            'total_revenue' => $this->getAgencyRevenue($agencyId, $filters),
            'total_leads' => $this->countAgencyLeads($agencyId, $filters)
        ];

        $this->jsonResponse($data);
    }



    // Chart Data Endpoints
    public function department_chart_data()
    {
        $agencyId = $this->requireAgencyJson();
        if (!$agencyId) {
            return;
        }

        $filters = $this->agencyDashboardFiltersFromPost($agencyId);
        if ($filters === null) {
            return;
        }

        $data = $this->Dashboard_model->get_agency_leads_by_department($agencyId, $filters);
        $this->send_chart_data($data, 'departments', 'department_id', 'department_id', 'department_name');
    }




    public function disposition_chart_data()
    {
        [$agencyId, $filters] = $this->agencyChartRequest();
        if (!$agencyId) {
            return;
        }
        $data = $this->Dashboard_model->get_agency_leads_grouped_by('disposition', $agencyId, $filters);
        $this->send_static_chart_data($data, 'disposition');
    }

    public function template_chart_data()
    {
        [$agencyId, $filters] = $this->agencyChartRequest();
        if (!$agencyId) {
            return;
        }
        $data = $this->Dashboard_model->get_agency_leads_grouped_by('template_name', $agencyId, $filters);
        $this->send_static_chart_data($data, 'template_name');
    }

    public function source_chart_data()
    {
        [$agencyId, $filters] = $this->agencyChartRequest();
        if (!$agencyId) {
            return;
        }
        $data = $this->Dashboard_model->get_agency_leads_grouped_by('user_channel', $agencyId, $filters);
        $this->send_static_chart_data($data, 'user_channel');
    }

    public function status_chart_data()
    {
        [$agencyId, $filters] = $this->agencyChartRequest();
        if (!$agencyId) {
            return;
        }
        $data = $this->Dashboard_model->get_agency_leads_grouped_by('status', $agencyId, $filters);
        $this->send_static_chart_data($data, 'status');
    }

    public function guest_type_chart_data()
    {
        [$agencyId, $filters] = $this->agencyChartRequest();
        if (!$agencyId) {
            return;
        }
        $data = $this->Dashboard_model->get_agency_guest_type_data($agencyId, $filters);

        // Format it like [{ label: 'New Guest', count: 10 }, { label: 'Repeat Guest', count: 5 }]
        $response = [
            ['label' => 'New Guest', 'count' => $data['normal']],
            ['label' => 'Repeat Guest', 'count' => $data['repeat']]
        ];

        $this->jsonResponse($response);
    }











    // Reusable method for grouped charts
    private function send_chart_data($data, $table, $where_col, $group_col, $label_col)
    {
        $formatted = [];




        foreach ($data as $row) {
            $id = $row->$group_col;
            $label = $this->Common_model->get_field_value($table, $where_col, $id, $label_col);
            $formatted[] = [
                'label' => $label ?: 'NA',
                'count' => $row->total
            ];
        }

        $this->jsonResponse($formatted);
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

        $this->jsonResponse($formatted);
    }

    private function agencyChartRequest()
    {
        $agencyId = $this->requireAgencyJson();
        if (!$agencyId) {
            return [0, []];
        }

        $filters = $this->agencyDashboardFiltersFromPost($agencyId);
        if ($filters === null) {
            return [0, []];
        }

        return [$agencyId, $filters];
    }

    private function requireAgencyJson()
    {
        if ($this->input->method() !== 'post') {
            $this->jsonResponse(['message' => 'Method not allowed.'], 405);
            return 0;
        }

        $agencySession = $this->session->userdata('agency_session');
        if (empty($agencySession['id']) || $this->session->userdata('role_as') !== 'agency') {
            $this->jsonResponse(['message' => 'Your session has expired.'], 401);
            return 0;
        }

        return (int) $agencySession['id'];
    }

    private function agencyDashboardFiltersFromPost($agencyId)
    {
        $property = (int) $this->input->post('property', true);
        $departmentInput = $this->input->post('department', true);
        if ($departmentInput === null) {
            $departmentInput = $this->input->post('type', true);
        }
        $department = (int) $departmentInput;
        $startDate = trim((string) $this->input->post('start_date', true));
        $endDate = trim((string) $this->input->post('end_date', true));

        $properties = $this->Common_model->get_properties_by_agency($agencyId);
        $allowedPropertyIds = array_map(static function ($item) {
            return (int) $item->hotel_id;
        }, $properties);

        if ($property && !in_array($property, $allowedPropertyIds, true)) {
            $this->jsonResponse(['message' => 'The selected property is not assigned to this agency.'], 422);
            return null;
        }

        if ($department) {
            $activeDepartment = $this->Common_model->getdata('departments', [
                'department_id' => $department,
                'is_deleted' => 0
            ]);
            if (!$activeDepartment) {
                $this->jsonResponse(['message' => 'Please select a valid department.'], 422);
                return null;
            }
        }

        foreach ([$startDate, $endDate] as $date) {
            if ($date !== '') {
                $parsedDate = DateTime::createFromFormat('!Y-m-d', $date);
                if (!$parsedDate || $parsedDate->format('Y-m-d') !== $date) {
                    $this->jsonResponse(['message' => 'Please enter a valid date range.'], 422);
                    return null;
                }
            }
        }

        if ($startDate !== '' && $endDate !== '' && $startDate > $endDate) {
            $this->jsonResponse(['message' => 'Start date cannot be later than end date.'], 422);
            return null;
        }

        return [
            'property' => $property,
            'department' => $department,
            'type' => $department,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }

    private function applyAgencyLeadFilters($agencyId, $filters)
    {
        $this->db->where('leads.is_deleted', 0);
        $this->db->where('leads.created_by', $agencyId);
        $this->db->where('leads.creator_user_role', 'Agency');

        if (!empty($filters['property'])) {
            $this->db->where('leads.property', $filters['property']);
        }
        if (!empty($filters['department'])) {
            $this->db->where('leads.type', $filters['department']);
        }
        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }
    }

    private function countAgencyLeads($agencyId, $filters, $field = null, $value = null)
    {
        $this->db->from('leads');
        $this->applyAgencyLeadFilters($agencyId, $filters);
        if ($field !== null && $value !== null) {
            $this->db->where('leads.' . $field, $value);
        }
        return $this->db->count_all_results();
    }

    private function countAgencyCalls($agencyId, $filters, $callStatus = '')
    {
        $this->db->from('calls');
        $this->db->join('leads', 'leads.id = calls.leadid');
        $this->applyAgencyLeadFilters($agencyId, $filters);
        if ($callStatus !== '') {
            $this->db->where('calls.overall_call_status', $callStatus);
        }
        return $this->db->count_all_results();
    }

    private function getAgencyRevenue($agencyId, $filters)
    {
        $this->db->select_sum('leads.amount');
        $this->db->from('leads');
        $this->applyAgencyLeadFilters($agencyId, $filters);
        $result = $this->db->get()->row();
        return (float) ($result->amount ?? 0);
    }

    private function jsonResponse($data, $status = 200)
    {
        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'data' => $data,
                'csrfHash' => $this->security->get_csrf_hash()
            ], JSON_UNESCAPED_UNICODE));
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

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leads extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeadModel'); // Load Model
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Airtel_config_model');
        $this->load->helper('download');


        if (empty($this->session->userdata('agent_session')) || ($this->session->userdata('role_as') != 'agent')) {
            return redirect('agent-login');
        }
    }

    /**
     * Resolve the selected hotel from the authenticated agent's hotel mapping.
     * Never trust a property sent by the browser for agent pages.
     */
    private function get_agent_scope()
    {
        $agent = $this->session->userdata('agent_session');
        $hotel_id = (int) $this->session->userdata('selected_hotel_id');

        if (empty($agent['id']) || $hotel_id <= 0) {
            return null;
        }

        $mapping = $this->db
            ->select('hotel_id')
            ->from('staff_hotel_department_mapping')
            ->where('staff_id', (int) $agent['id'])
            ->where('hotel_id', $hotel_id)
            ->get()
            ->row_array();

        if (empty($mapping)) {
            return null;
        }

        return [
            'hotel_id' => (int) $mapping['hotel_id']
        ];
    }

    private function normalise_multi_filter($value)
    {
        if ($value === null || $value === '') {
            return [];
        }

        $values = is_array($value) ? $value : [$value];

        return array_values(array_filter($values, static function ($item) {
            return is_scalar($item) && trim((string) $item) !== '';
        }));
    }



    public function index()
    {

        $scope = $this->get_agent_scope();
        if ($scope === null) {
            return redirect('agent-dashboard');
        }

        $property = $scope['hotel_id'];
        $disposition = $this->input->get('disposition');


        $filters = [
            'status' => $this->input->get('status'),
            'disposition' => $disposition,

            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'phone' => $this->input->get('phone'),


        ];


        if (empty($filters['status']) && count(array_filter($filters)) === 0) {
            $filters['status'] = 'Open';
        }


        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        $data['leads'] = $this->LeadModel->get_hotel_leads($property, $activeFilters);

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', [
            'property' => $property,
            'is_deleted' => 0
        ]);

        $data['departments'] = $this->db
            ->select('d.*')
            ->distinct()
            ->from('departments d')
            ->join('staff_hotel_department_mapping shdm', 'shdm.department_id = d.department_id')
            ->where('shdm.staff_id', (int) $this->session->userdata('agent_session')['id'])
            ->where('shdm.hotel_id', $property)
            ->get()
            ->result();

        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');

        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');


        $data['all_assignable_users'] = $this->LeadModel->get_all_assignable_users('hotel_admin', '');




        $activeFilters['property'] = $property;

        $data['properties'] = $this->db
            ->where('hotel_id', $property)
            ->get('hotel_admin')
            ->result();

        $data['lead_status_counts'] = $this->LeadModel->get_lead_counts_grouped_by_status($activeFilters);



        $data['airtel_config'] = $this->Airtel_config_model->get_runtime_config();
        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/lead_report', $data);
        $this->load->view('agent/include/footer');
    }

    /**
     * Agent-only lead feed. The selected hotel is taken from the authenticated
     * session and cannot be overridden by POST data.
     */
    public function fetch_leads_ajax()
    {
        if ($this->input->method() !== 'post') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(['status' => false, 'message' => 'Method not allowed']));
        }

        $scope = $this->get_agent_scope();
        if ($scope === null) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode(['status' => false, 'message' => 'No hotel access is available for this agent.']));
        }

        $limit = (int) $this->input->post('limit');
        $offset = max(0, (int) $this->input->post('offset'));
        $limit = $limit > 0 ? min($limit, 100) : 50;

        $business_type = $this->input->post('business_type');
        if (is_array($business_type)) {
            $business_type = reset($business_type);
        }

        $filters = [
            'property' => [$scope['hotel_id']],
            'status' => $this->normalise_multi_filter($this->input->post('status')),
            'channel' => $this->normalise_multi_filter($this->input->post('channel')),
            'disposition' => $this->normalise_multi_filter($this->input->post('disposition')),
            'start_date' => $this->input->post('start_date', true),
            'end_date' => $this->input->post('end_date', true),
            'search' => trim((string) $this->input->post('search', true)),
            'phone' => $this->input->post('phone', true),
            'business_type' => in_array($business_type, ['business', 'non_business'], true) ? $business_type : '',
            'showfollowupleads' => $this->input->post('showfollowupleads') === 'yes' ? 'yes' : 'no'
        ];

        $leads = $this->LeadModel->get_filtered_leads($filters, $limit, $offset);
        $total_counts = $this->LeadModel->get_leads_status_counts($filters);
        $html = $this->load->view('agent/ajax_leads_cards', ['leads' => $leads], true);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'html' => $html,
                'count' => count($leads),
                'totalCounts' => $total_counts,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }


    public function followups()
    {
        $scope = $this->get_agent_scope();
        if ($scope === null) {
            return redirect('agent-dashboard');
        }

        $property = $scope['hotel_id'];
        $agent = $this->session->userdata('agent_session');

        $filters = [
            'property' => [$property],
            'status' => $this->normalise_multi_filter($this->input->get('status')),
            'disposition' => $this->normalise_multi_filter($this->input->get('disposition')),
            'channel' => $this->normalise_multi_filter($this->input->get('channel')),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'phone' => $this->input->get('phone'),
            'showfollowupleads' => 'yes'
        ];

        $data['leads'] = $this->LeadModel->get_filtered_leads($filters, 50, 0);

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', [
            'property' => $property,
            'is_deleted' => 0
        ]);

        $data['departments'] = $this->db
            ->select('d.*')
            ->distinct()
            ->from('departments d')
            ->join('staff_hotel_department_mapping shdm', 'shdm.department_id = d.department_id')
            ->where('shdm.staff_id', (int) $agent['id'])
            ->where('shdm.hotel_id', $property)
            ->get()
            ->result();

        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');

        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');

        $data['all_assignable_users'] = $this->LeadModel->get_all_assignable_users('hotel_admin', '');

        $data['properties'] = $this->db
            ->where('hotel_id', $property)
            ->get('hotel_admin')
            ->result();

        $data['lead_status_counts'] = $this->LeadModel->get_leads_status_counts($filters);
        $data['showfollowupleads'] = 'yes';

        $data['airtel_config'] = $this->Airtel_config_model->get_runtime_config();
        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/lead_report', $data);
        $this->load->view('agent/include/footer');
    }


    public function customer_lead_history_agent()
    {



        $property = $this->session->userdata('selected_hotel_id');

        $filters = [
            'department' => $this->session->userdata('selected_department_id'),
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'disposition' => $this->input->get('disposition') // 🆕 Added Stage filter

        ];

        $phone = $this->uri->segment('2');






        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        $data['leads'] = $this->LeadModel->get_leads_history_hotel($property, $activeFilters, $phone);

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');

        $department = $this->session->userdata('selected_department_id');

        // Load all data to send to view
        $data['lead_status_counts'] = [
            'Open'        => $this->LeadModel->get_lead_count_by_status_agent('Open', $property, $department),
            'In Progress' => $this->LeadModel->get_lead_count_by_status_agent('In Progress', $property, $department),
            'On Hold'     => $this->LeadModel->get_lead_count_by_status_agent('On Hold', $property, $department),
            'Closed'      => $this->LeadModel->get_lead_count_by_status_agent('Closed', $property, $department)

        ];



        $data['airtel_config'] = $this->Airtel_config_model->get_runtime_config();
        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/lead_report', $data);
        $this->load->view('agent/include/footer');
    }

    public function add_lead()
    {
        $scope = $this->get_agent_scope();
        if ($scope === null) {
            return redirect('agent-dashboard');
        }

        $property = $scope['hotel_id'];

        $data['leads'] = $this->LeadModel->get_leads();
        $data['departments'] = $this->db
            ->select('d.*')
            ->distinct()
            ->from('departments d')
            ->join('staff_hotel_department_mapping shdm', 'shdm.department_id = d.department_id')
            ->where('shdm.staff_id', (int) $this->session->userdata('agent_session')['id'])
            ->where('shdm.hotel_id', $property)
            ->get()
            ->result();
        $data['hotel_admin'] = $this->db
            ->where('hotel_id', $property)
            ->get('hotel_admin')
            ->result();

        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');

        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');


        $data['all_assignable_users'] = $this->LeadModel->get_all_assignable_users('hotel_admin', '');





        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/add_lead', $data);
        $this->load->view('agent/include/footer');
    }

    private function getActiveAssignableUser($userId, $role)
    {
        $userId = (int) $userId;

        if ($userId <= 0) {
            return null;
        }

        if ($role === 'admin') {
            return $this->db
                ->select("id, name, email, 'admin' AS user_role", false)
                ->where(['id' => $userId, 'status' => 1])
                ->get('hotel_admins')
                ->row_array();
        }

        if ($role === 'agent') {
            return $this->db
                ->select("id, name, email, 'agent' AS user_role", false)
                ->where(['id' => $userId, 'status' => 1])
                ->get('staff_members')
                ->row_array();
        }

        if ($role === 'super_admin') {
            return $this->db
                ->select("id, full_name AS name, email, 'super_admin' AS user_role", false)
                ->where(['id' => $userId, 'status' => 'active'])
                ->get('super_admin')
                ->row_array();
        }

        return null;
    }

    private function validateLeadInput($departmentName)
    {
        $errors = [];
        $value = function ($field) {
            return trim((string) $this->input->post($field, true));
        };

        $phone = substr(preg_replace('/\D+/', '', $value('phone_number')), -10);
        $disposition = $value('disposition');
        $department = strtolower(trim((string) $departmentName));

        if ($department === 'restaurants') {
            $department = 'restaurant';
        } elseif ($department === 'banquets') {
            $department = 'banquet';
        }

        if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
            $errors['phone_number'] = 'Enter a valid 10-digit Indian mobile number.';
        }
        if ($disposition !== 'Not Contacted' && $value('user_name') === '') {
            $errors['username'] = 'Guest name is required.';
        }
        if ($value('email') !== '' && !filter_var($value('email'), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }

        $required = [
            'type' => 'Please select a department.',
            'user_channel' => 'Please select a lead source.',
            'disposition' => 'Please select a stage.',
            'status' => 'Please select a lead status.',
            'query' => 'Query is required.'
        ];

        foreach ($required as $field => $message) {
            if ($value($field) === '') {
                $errors[$field === 'status' ? 'lead_status' : $field] = $message;
            }
        }

        if ($disposition === 'Lead Lost' && $value('reason') === '') {
            $errors['reason'] = 'Please select a reason.';
        }

        if ($disposition === 'Quotation Sent') {
            if (in_array($department, ['rooms', 'wedding'], true) && $value('meal_plan') === '') {
                $errors['meal_plan'] = 'Please select a meal plan.';
            }
            if (in_array($department, ['banquet', 'wedding'], true) && $value('banquet_id') === '') {
                $errors['banquet_id'] = 'Please select a banquet.';
            }
            if ($department === 'restaurant') {
                $restaurantRequired = [
                    'restaurant_id' => 'Please select a restaurant.',
                    'slot_type_id' => 'Please select a slot type.',
                    'time_slot_id' => 'Please select a time slot.',
                    'table_category_id' => 'Please select a table category.',
                    'table_id' => 'Please select a table.'
                ];
                foreach ($restaurantRequired as $field => $message) {
                    if ($value($field) === '') {
                        $errors[$field] = $message;
                    }
                }
            }
        }

        return $errors;
    }

    public function insert_lead()
    {
        if ($this->input->method() !== 'post') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(['status' => false, 'message' => 'Method not allowed.']));
        }

        $scope = $this->get_agent_scope();
        if ($scope === null) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'The selected hotel is not assigned to this agent.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $agentSession = $this->session->userdata('agent_session');
        $property = (int) $scope['hotel_id'];
        $type = (int) $this->input->post('type', true);
        $hotel = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $department = $this->Common_model->getdata('departments', ['department_id' => $type]);
        $errors = $this->validateLeadInput($department->department_name ?? '');

        if (!$hotel) {
            $errors['property'] = 'The selected hotel is unavailable.';
        }
        if (!$department) {
            $errors['type'] = 'Please select a valid department.';
        }

        $departmentMapping = $this->db
            ->where('staff_id', (int) ($agentSession['id'] ?? 0))
            ->where('hotel_id', $property)
            ->where('department_id', $type)
            ->get('staff_hotel_department_mapping')
            ->row_array();
        if (!$departmentMapping) {
            $errors['type'] = 'This department is not assigned to your account for the selected hotel.';
        }

        $assignedUser = null;
        $assignedTo = trim((string) $this->input->post('assigned_to', true));
        $assignedRole = trim((string) $this->input->post('assigned_person_user_role', true));
        if ($assignedTo !== '') {
            $assignedUser = $this->getActiveAssignableUser($assignedTo, $assignedRole);
            if (!$assignedUser) {
                $errors['assigned_to'] = 'Please select an active assignable user.';
            }
        }

        if (!empty($errors)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Please correct the highlighted fields.',
                    'errors' => $errors,
                    'csrfHash' => $this->security->get_csrf_hash()
                ], JSON_UNESCAPED_UNICODE));
        }

        $phone = substr(preg_replace('/\D+/', '', (string) $this->input->post('phone_number', true)), -10);
        $now = date('Y-m-d H:i:s');
        $leadData = [
            'user_name' => trim((string) $this->input->post('user_name', true)),
            'phone_number' => $phone,
            'email' => trim((string) $this->input->post('email', true)),
            'user_channel' => $this->input->post('user_channel', true),
            'property' => $property,
            'type' => $type,
            'status' => $this->input->post('status', true),
            'disposition' => $this->input->post('disposition', true),
            'created_at' => $now,
            'query' => $this->input->post('query', true),
            'remark' => $this->input->post('remark', true),
            'lead_type' => $this->input->post('lead_type', true),
            'purpose' => $this->input->post('purpose', true),
            'reason' => $this->input->post('reason', true),
            'promotional_offers' => $this->input->post('promotional_offers', true),
            'template_name' => 'Phone',
            'city' => $hotel->city_id,
            'created_by' => (int) $agentSession['id'],
            'creator_user_role' => 'agent'
        ];

        $escalationHours = (float) ($department->escalation_level_1 ?? 0);
        $leadData['esc_next_followup_at'] = date('Y-m-d H:i:s', strtotime('+' . ($escalationHours * 60) . ' minutes'));
        $leadData['esc_follow_up_level'] = 1;

        $optionalFields = [
            'booking_date', 'followup_date', 'second_followup_date', 'arrival_time',
            'checkin_date', 'checkout_date', 'roomtype', 'number_of_rooms', 'pax',
            'adults', 'kids', 'meal_plan', 'revenue_fnb', 'revenue_other',
            'revenue_room', 'amount', 'banquet_id', 'restaurant_id', 'slot_type_id',
            'time_slot_id', 'table_category_id', 'table_id', 'special_occasion',
            'special_request'
        ];
        foreach ($optionalFields as $field) {
            $fieldValue = $this->input->post($field, true);
            if ($fieldValue !== null && $fieldValue !== '') {
                $leadData[$field] = $fieldValue;
            }
        }

        if ($assignedUser) {
            $leadData['is_assigned'] = 1;
            $leadData['assigned_to'] = (int) $assignedUser['id'];
            $leadData['assigned_person_user_role'] = $assignedUser['user_role'];
            $leadData['assigned_person_email'] = $assignedUser['email'];
        }

        if ($leadData['status'] === 'Closed') {
            $leadData['completed_time'] = $now;
        } else {
            $leadData['responded_time'] = $now;
        }

        $existingLead = $this->db
            ->where("RIGHT(phone_number, 10) =", $phone, false)
            ->where('property', $property)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-2 hours')))
            ->where('status !=', 'Closed')
            ->where('is_deleted', 0)
            ->order_by('id', 'DESC')
            ->get('leads')
            ->row();

        if ($existingLead) {
            $wasReassigned = $assignedUser && (
                (int) $existingLead->assigned_to !== (int) $assignedUser['id'] ||
                (string) $existingLead->assigned_person_user_role !== (string) $assignedUser['user_role']
            );

            unset($leadData['created_at']);
            $saved = $this->db
                ->where('id', (int) $existingLead->id)
                ->where('property', $property)
                ->update('leads', $leadData);

            if ($saved) {
                $this->triggerAssignedLeadEmail(
                    (int) $existingLead->id,
                    $phone,
                    $wasReassigned ? 'reassigned' : 'updated'
                );
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => (bool) $saved,
                    'message' => $saved
                        ? 'Duplicate detected: Existing lead updated successfully.'
                        : 'Failed to update the existing lead.',
                    'duplicate' => true,
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $insertId = $this->LeadModel->insert_lead($leadData);
        if (!$insertId) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed to insert lead.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $this->triggerAssignedLeadEmail((int) $insertId, $phone, 'created');

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Lead created successfully.',
                'leadId' => (int) $insertId,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }

    public function get_call_history()
    {

        $id = $this->input->post('lead_id');
        $result = $this->Common_model->getAllData('calls', array('lead_id' => $id), '');
        echo json_encode($result);
    }



    public function update_status()
    {
        $leadId = $this->input->post('id');
        $status = $this->input->post('status');
        $remark = $this->input->post('remark');

        $data = ['status' => $status];

        if ($status == "Closed") {
            $data['remark'] = $remark;
        }



        $datas = $this->Comman_model->UpdateRecord('leads', $data, array('id' => $leadId));

        if ($datas) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function edit_lead($id)
    {
        $lead = $this->Comman_model->getData('leads', ['id' => $id]);



        $data['lead'] = $lead;
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', '');

        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/edit_lead', $data);
        $this->load->view('agent/include/footer');
    }

    public function get_lead_details()
    {
        if ($this->input->method() !== 'post') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Method not allowed.']));
        }

        $scope = $this->get_agent_scope();
        $leadId = (int) $this->input->post('lead_id');
        $agent = $this->session->userdata('agent_session');

        if ($scope === null || $leadId <= 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid lead request.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $lead = $this->db
            ->where('id', $leadId)
            ->where('property', (int) $scope['hotel_id'])
            ->where('is_deleted', 0)
            ->get('leads')
            ->row();

        if (!$lead) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Lead not found for the selected hotel.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        if (
            (int) $lead->is_assigned === 1 &&
            $lead->assigned_person_user_role === 'agent' &&
            (int) $lead->assigned_to !== (int) $agent['id']
        ) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'This lead is assigned to another agent.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'data' => $lead,
                'csrfHash' => $this->security->get_csrf_hash()
            ], JSON_UNESCAPED_UNICODE));
    }

    public function update_lead()
    {
        if ($this->input->method() !== 'post') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(['status' => false, 'message' => 'Method not allowed.']));
        }

        $scope = $this->get_agent_scope();
        $agent = $this->session->userdata('agent_session');
        $leadId = (int) $this->input->post('lead_id');

        if ($scope === null || $leadId <= 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Invalid agent hotel scope.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $lead = $this->db
            ->where('id', $leadId)
            ->where('property', (int) $scope['hotel_id'])
            ->where('is_deleted', 0)
            ->get('leads')
            ->row();

        if (!$lead) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Lead not found for the selected hotel.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        if (
            (int) $lead->is_assigned === 1 &&
            $lead->assigned_person_user_role === 'agent' &&
            (int) $lead->assigned_to !== (int) $agent['id']
        ) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'This lead is assigned to another agent.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $property = (int) $scope['hotel_id'];
        $type = (int) $this->input->post('type', true);
        $department = $this->Common_model->getdata('departments', ['department_id' => $type]);
        $errors = $this->validateLeadInput($department->department_name ?? '');

        $departmentMapping = $this->db
            ->where('staff_id', (int) $agent['id'])
            ->where('hotel_id', $property)
            ->where('department_id', $type)
            ->get('staff_hotel_department_mapping')
            ->row_array();

        if (!$department || !$departmentMapping) {
            $errors['type'] = 'Please select a department assigned to this hotel.';
        }

        $assignedUser = null;
        $assignedTo = trim((string) $this->input->post('assigned_to', true));
        $assignedRole = trim((string) $this->input->post('assigned_person_user_role', true));
        if ($assignedTo !== '') {
            $assignedUser = $this->getActiveAssignableUser($assignedTo, $assignedRole);
            if (!$assignedUser) {
                $errors['assigned_to'] = 'Please select an active assignable user.';
            }
        }

        if (!empty($errors)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Please correct the highlighted fields.',
                    'errors' => $errors,
                    'csrfHash' => $this->security->get_csrf_hash()
                ], JSON_UNESCAPED_UNICODE));
        }

        $phone = substr(preg_replace('/\D+/', '', (string) $this->input->post('phone_number', true)), -10);
        $status = $this->input->post('status', true);
        $now = date('Y-m-d H:i:s');
        $leadData = [
            'user_name' => trim((string) $this->input->post('user_name', true)),
            'phone_number' => $phone,
            'email' => trim((string) $this->input->post('email', true)),
            'user_channel' => $this->input->post('user_channel', true),
            'type' => $type,
            'status' => $status,
            'disposition' => $this->input->post('disposition', true),
            'query' => $this->input->post('query', true),
            'remark' => $this->input->post('remark', true),
            'lead_type' => $this->input->post('lead_type', true),
            'purpose' => $this->input->post('purpose', true),
            'reason' => $this->input->post('reason', true),
            'promotional_offers' => $this->input->post('promotional_offers', true),
            'updated_on' => $now
        ];

        $optionalFields = [
            'booking_date', 'followup_date', 'second_followup_date', 'arrival_time',
            'checkin_date', 'checkout_date', 'roomtype', 'number_of_rooms', 'pax',
            'adults', 'kids', 'meal_plan', 'revenue_fnb', 'revenue_other',
            'revenue_room', 'amount', 'banquet_id', 'restaurant_id', 'slot_type_id',
            'time_slot_id', 'table_category_id', 'table_id', 'special_occasion',
            'special_request'
        ];
        foreach ($optionalFields as $field) {
            $fieldValue = $this->input->post($field, true);
            if ($fieldValue !== null) {
                $leadData[$field] = $fieldValue;
            }
        }

        if ($assignedUser) {
            $leadData['is_assigned'] = 1;
            $leadData['assigned_to'] = (int) $assignedUser['id'];
            $leadData['assigned_person_user_role'] = $assignedUser['user_role'];
            $leadData['assigned_person_email'] = $assignedUser['email'];
        }

        if ($status === 'Closed') {
            $leadData['completed_time'] = $now;
        } else {
            $leadData['responded_time'] = $now;
        }

        $updated = $this->db
            ->where('id', $leadId)
            ->where('property', $property)
            ->update('leads', $leadData);

        if (!$updated) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed to update lead.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $wasReassigned = $assignedUser && (
            (int) $lead->assigned_to !== (int) $assignedUser['id'] ||
            (string) $lead->assigned_person_user_role !== (string) $assignedUser['user_role']
        );

        $this->triggerAssignedLeadEmail(
            $leadId,
            $phone,
            $wasReassigned ? 'reassigned' : 'updated'
        );

        $updatedLead = $this->LeadModel->get_lead_by_id_with_joins($leadId);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Lead updated successfully.',
                'data' => $updatedLead ? (array) $updatedLead : $leadData,
                'csrfHash' => $this->security->get_csrf_hash()
            ], JSON_UNESCAPED_UNICODE));
    }

    /**
     * Trigger the shared dynamic-SMTP notification without making lead saves
     * depend on the mail server response.
     */
    private function triggerAssignedLeadEmail($leadId, $phone, $notificationType)
    {
        $valuableGuest = $this->db
            ->select('id')
            ->from('leads')
            ->where('is_deleted', 0)
            ->where("RIGHT(phone_number, 10) =", $phone, false)
            ->where('LOWER(disposition)', 'reservation')
            ->where('amount >', 0)
            ->limit(1)
            ->get()
            ->row();

        $emailUrl = base_url(
            'EmailWorker/sendLeadEmailToassigned_person_email/' .
            (int) $leadId . '/' .
            ($valuableGuest ? '1' : '0') . '/' .
            rawurlencode($notificationType)
        );

        $emailRequest = curl_init();
        curl_setopt($emailRequest, CURLOPT_URL, $emailUrl);
        curl_setopt($emailRequest, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($emailRequest, CURLOPT_CONNECTTIMEOUT_MS, 100);
        curl_setopt($emailRequest, CURLOPT_TIMEOUT_MS, 100);
        curl_setopt($emailRequest, CURLOPT_NOSIGNAL, 1);
        $requested = curl_exec($emailRequest);

        if ($requested === false) {
            log_message(
                'error',
                'Assigned lead email worker could not be started for lead ID ' .
                (int) $leadId . ': ' . curl_error($emailRequest)
            );
        }

        curl_close($emailRequest);
    }

}

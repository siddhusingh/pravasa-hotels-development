<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Sales_Controller.php';

class WeeklyPlanner extends Sales_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireSalesRole(['Sales Executive', 'Sales Manager']);

        $this->load->model('Common_model');
        $this->load->helper('secure');
    }

    public function index()
    {
        $data['companies'] = $this->Common_model->getAllData(
            'companies',
            ['status' => 1, 'is_deleted' => 0]
        );
        $data['is_planner_manager'] =
            $this->salesUser->user_role === 'Sales Manager';

        $this->renderSalesPage('sales/weekly_planner/index', $data);
    }

    public function fetch()
    {
        $data['planners'] = $this->plannerQuery()->get()->result();
        $data['is_planner_manager'] =
            $this->salesUser->user_role === 'Sales Manager';

        return $this->load->view(
            'sales/weekly_planner/_table',
            $data
        );
    }

    public function calendar()
    {
        $plans = $this->plannerQuery()
            ->select(
                'wp.id, wp.planner_date, wp.activity_type, ' .
                'wp.description, wp.approval_status, su.full_name'
            )
            ->get()
            ->result();
        $events = [];

        foreach ($plans as $plan) {
            $title = $plan->activity_type === 'visit'
                ? 'Visit'
                : 'Other Activity';
            if (!empty($plan->description)) {
                $title .= ' - ' . substr($plan->description, 0, 30);
            }
            if ($this->isManager()) {
                $title = ($plan->full_name ?: 'Sales Executive') .
                    ' | ' . $title;
            }

            $events[] = [
                'id' => encrypt_id($plan->id),
                'title' => $title,
                'start' => $plan->planner_date,
                'allDay' => true,
                'color' => $plan->approval_status === 'pending'
                    ? '#f39c12'
                    : '#00a65a'
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($events));
    }

    public function create()
    {
        if (!$this->isPost() || $this->isManager()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'You are not authorized to create a planner'
            ]);
        }

        $data = $this->plannerPayload(true);
        $validationError = $this->validatePlanner($data);
        if ($validationError !== '') {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => $validationError
            ]);
        }

        $data['sales_user_id'] = $this->salesUserId;
        $data['approval_status'] = 'pending';
        $data['approved_by'] = null;
        $data['approved_at'] = null;

        if (!$this->db->insert('weekly_planner', $data)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Unable to submit weekly planner'
            ]);
        }

        $plannerId = (int)$this->db->insert_id();
        $this->logActivity(
            'submit',
            $plannerId,
            'Submitted weekly planner for manager approval'
        );

        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Weekly planner submitted for manager approval'
        ]);
    }

    public function details()
    {
        $plannerId = $this->decodeId(
            $this->input->get('id') ?: $this->input->post('id')
        );
        if (empty($plannerId)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid planner record'
            ]);
        }

        $planner = $this->db
            ->select('wp.*, c.company_name')
            ->from('weekly_planner wp')
            ->join(
                'companies c',
                'c.company_id = wp.company_id',
                'left'
            )
            ->where('wp.id', $plannerId)
            ->where('wp.sales_user_id', $this->salesUserId)
            ->where('wp.approval_status', 'approved')
            ->where('wp.is_deleted', 0)
            ->get()
            ->row();

        if (empty($planner) || $this->isManager()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Planner not found'
            ]);
        }

        $planner->id = encrypt_id($planner->id);
        $planner->company_id = !empty($planner->company_id)
            ? encrypt_id($planner->company_id)
            : '';
        $planner->contact_id = !empty($planner->contact_id)
            ? encrypt_id($planner->contact_id)
            : '';

        return $this->jsonResponse([
            'status' => 'success',
            'data' => $planner
        ]);
    }

    public function update()
    {
        if (!$this->isPost() || $this->isManager()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'You are not authorized to update this planner'
            ]);
        }

        $plannerId = $this->decodeId($this->input->post('id'));
        $planner = $this->ownedApprovedPlanner($plannerId);
        if (empty($planner)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Planner not found'
            ]);
        }

        $data = $this->plannerPayload(false);
        $validationError = $this->validatePlanner($data);
        if ($validationError !== '') {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => $validationError
            ]);
        }

        $data['approval_status'] = 'pending';
        $data['approved_by'] = null;
        $data['approved_at'] = null;

        $updated = $this->db
            ->where('id', $plannerId)
            ->where('sales_user_id', $this->salesUserId)
            ->where('approval_status', 'approved')
            ->where('is_deleted', 0)
            ->update('weekly_planner', $data);

        if (!$updated || $this->db->affected_rows() !== 1) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Unable to update weekly planner'
            ]);
        }

        $this->logActivity(
            'resubmit',
            $plannerId,
            'Updated and resubmitted weekly planner for approval'
        );

        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Weekly planner updated and resubmitted for approval'
        ]);
    }

    public function delete()
    {
        if (!$this->isPost() || $this->isManager()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'You are not authorized to delete this planner'
            ]);
        }

        $plannerId = $this->decodeId($this->input->post('id'));
        $planner = $this->ownedApprovedPlanner($plannerId);
        if (empty($planner)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Planner not found'
            ]);
        }

        $deleted = $this->db
            ->where('id', $plannerId)
            ->where('sales_user_id', $this->salesUserId)
            ->where('approval_status', 'approved')
            ->where('is_deleted', 0)
            ->update('weekly_planner', [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if (!$deleted || $this->db->affected_rows() !== 1) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Unable to delete weekly planner'
            ]);
        }

        $this->logActivity(
            'delete',
            $plannerId,
            'Deleted approved weekly planner'
        );

        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Weekly planner deleted successfully'
        ]);
    }

    public function approve()
    {
        if (!$this->isPost() || !$this->isManager()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Only a Sales Manager can approve planners'
            ]);
        }

        $plannerId = $this->decodeId($this->input->post('id'));
        $planner = $this->db
            ->where('id', $plannerId)
            ->where('sales_user_id IS NOT NULL', null, false)
            ->where('approval_status', 'pending')
            ->where('is_deleted', 0)
            ->get('weekly_planner')
            ->row();

        if (empty($plannerId) || empty($planner)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Pending planner not found'
            ]);
        }

        $approved = $this->db
            ->where('id', $plannerId)
            ->where('approval_status', 'pending')
            ->where('is_deleted', 0)
            ->update('weekly_planner', [
                'approval_status' => 'approved',
                'approved_by' => $this->salesUserId,
                'approved_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if (!$approved || $this->db->affected_rows() !== 1) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Unable to approve weekly planner'
            ]);
        }

        $this->logActivity(
            'approve',
            $plannerId,
            'Approved weekly planner for sales user ID ' .
                $planner->sales_user_id
        );

        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Weekly planner approved successfully'
        ]);
    }

    public function contacts()
    {
        if (!$this->isPost() || $this->isManager()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Method not allowed',
                'data' => []
            ]);
        }

        $companyId = $this->decodeId($this->input->post('company_id'));
        $selectedToken = trim(
            (string)$this->input->post('selected_contact_id')
        );
        $selectedId = $this->decodeId($selectedToken);
        $company = $this->db
            ->where('company_id', $companyId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get('companies')
            ->row();

        if (empty($companyId) || empty($company)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid company',
                'data' => []
            ]);
        }

        $contacts = $this->db
            ->select('contact_id, first_name, last_name, mobile_number')
            ->from('company_contacts')
            ->where('company_id', $companyId)
            ->where('status', 'Active')
            ->where('is_deleted', 0)
            ->order_by('first_name', 'ASC')
            ->get()
            ->result();

        foreach ($contacts as $contact) {
            $contact->contact_id = (
                !empty($selectedId) &&
                (int)$selectedId === (int)$contact->contact_id
            )
                ? $selectedToken
                : encrypt_id($contact->contact_id);
        }

        return $this->jsonResponse([
            'status' => !empty($contacts) ? 'success' : 'error',
            'message' => !empty($contacts) ? '' : 'No contacts found',
            'data' => $contacts
        ]);
    }

    private function plannerQuery()
    {
        $query = $this->db
            ->select(
                'wp.*, c.company_name, cc.first_name, cc.last_name, ' .
                'su.full_name AS sales_user_name, ' .
                'approver.full_name AS approver_name'
            )
            ->from('weekly_planner wp')
            ->join(
                'companies c',
                'c.company_id = wp.company_id',
                'left'
            )
            ->join(
                'company_contacts cc',
                'cc.contact_id = wp.contact_id',
                'left'
            )
            ->join(
                'sales_users su',
                'su.id = wp.sales_user_id',
                'left'
            )
            ->join(
                'sales_users approver',
                'approver.id = wp.approved_by',
                'left'
            )
            ->where('wp.sales_user_id IS NOT NULL', null, false)
            ->where('wp.is_deleted', 0);

        if ($this->isManager()) {
            $query->order_by(
                "FIELD(wp.approval_status, 'pending', 'approved')",
                '',
                false
            );
        } else {
            $query
                ->where('wp.sales_user_id', $this->salesUserId)
                ->where('wp.approval_status', 'approved');
        }

        return $query
            ->order_by('wp.planner_date', 'DESC')
            ->order_by('wp.id', 'DESC');
    }

    private function plannerPayload($includeCreated)
    {
        $activityType = trim(
            (string)$this->input->post('activity_type', true)
        );
        $accountType = $activityType === 'visit'
            ? trim((string)$this->input->post('account_type', true))
            : null;

        $data = [
            'planner_date' => trim(
                (string)$this->input->post('planner_date', true)
            ),
            'activity_type' => $activityType,
            'account_type' => $accountType,
            'company_id' => (
                $activityType === 'visit' &&
                $accountType === 'existing'
            )
                ? $this->decodeId($this->input->post('company_id'))
                : null,
            'contact_id' => (
                $activityType === 'visit' &&
                $accountType === 'existing'
            )
                ? $this->decodeId($this->input->post('contact_id'))
                : null,
            'new_person_name' => (
                $activityType === 'visit' &&
                $accountType === 'new'
            )
                ? trim(
                    (string)$this->input->post('new_person_name', true)
                )
                : null,
            'new_person_mobile' => (
                $activityType === 'visit' &&
                $accountType === 'new'
            )
                ? trim(
                    (string)$this->input->post('new_person_mobile', true)
                )
                : null,
            'other_activity' => $activityType === 'other'
                ? trim(
                    (string)$this->input->post('other_activity', true)
                )
                : null,
            'description' => trim(
                (string)$this->input->post('description', true)
            ),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;
        }

        return $data;
    }

    private function validatePlanner(array $data)
    {
        $date = DateTime::createFromFormat(
            'Y-m-d',
            $data['planner_date']
        );
        if (
            !$date ||
            $date->format('Y-m-d') !== $data['planner_date']
        ) {
            return 'Please select a valid planner date';
        }

        if (!in_array($data['activity_type'], ['visit', 'other'], true)) {
            return 'Please select a valid activity type';
        }

        if ($data['activity_type'] === 'other') {
            $activities = [
                'Fairs & Marts',
                'In House',
                'Others',
                'Sales Blitz',
                'Tele Calling'
            ];

            return in_array(
                $data['other_activity'],
                $activities,
                true
            )
                ? ''
                : 'Please select a valid activity';
        }

        if (!in_array($data['account_type'], ['existing', 'new'], true)) {
            return 'Please select an account type';
        }

        if ($data['account_type'] === 'new') {
            if (
                $data['new_person_name'] === '' ||
                $data['new_person_mobile'] === ''
            ) {
                return 'Person name and mobile number are required';
            }

            return preg_match(
                '/^[0-9]{10,15}$/',
                $data['new_person_mobile']
            )
                ? ''
                : 'Please enter a valid mobile number';
        }

        if (empty($data['company_id']) || empty($data['contact_id'])) {
            return 'Please select a company and contact';
        }

        $validContact = $this->db
            ->from('company_contacts cc')
            ->join(
                'companies c',
                'c.company_id = cc.company_id',
                'inner'
            )
            ->where('cc.contact_id', $data['contact_id'])
            ->where('cc.company_id', $data['company_id'])
            ->where('cc.status', 'Active')
            ->where('cc.is_deleted', 0)
            ->where('c.status', 1)
            ->where('c.is_deleted', 0)
            ->count_all_results() === 1;

        return $validContact ? '' : 'Invalid company contact selection';
    }

    private function ownedApprovedPlanner($plannerId)
    {
        if (empty($plannerId)) {
            return null;
        }

        return $this->db
            ->where('id', $plannerId)
            ->where('sales_user_id', $this->salesUserId)
            ->where('approval_status', 'approved')
            ->where('is_deleted', 0)
            ->get('weekly_planner')
            ->row();
    }

    private function decodeId($value)
    {
        $token = trim((string)$value);
        if ($token === '') {
            return null;
        }

        if (ctype_digit($token)) {
            return (int)$token;
        }

        $decoded = decrypt_id($token);
        return $decoded ? (int)$decoded : null;
    }

    private function isManager()
    {
        return $this->salesUser->user_role === 'Sales Manager';
    }

    private function isPost()
    {
        return strtoupper($this->input->method()) === 'POST';
    }

    private function jsonResponse(array $payload)
    {
        $payload['csrfHash'] = $this->security->get_csrf_hash();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }

    private function logActivity($action, $recordId, $details)
    {
        $this->Common_model->insertActivityLog([
            'module' => 'weekly_planner',
            'record_id' => $recordId,
            'action' => $action,
            'details' => $details,
            'actor_id' => $this->salesUserId,
            'actor_name' => $this->salesUser->full_name,
            'actor_email' => $this->salesUser->email,
            'actor_role' => $this->salesUser->user_role,
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}

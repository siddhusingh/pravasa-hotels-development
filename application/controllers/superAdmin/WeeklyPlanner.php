<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WeeklyPlanner extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->helper('secure');
        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }

    private function decodeId($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return $value;
        }

        $decoded = decrypt_id($value);
        return $decoded !== false ? $decoded : null;
    }

    private function jsonResponse(array $payload)
    {
        $payload['csrfHash'] = $this->security->get_csrf_hash();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }

    private function getCurrentActor()
    {
        $actor = $this->session->userdata('super_admin_session');

        return [
            'id' => $actor['id'] ?? null,
            'name' => $actor['user_name'] ?? $actor['full_name'] ?? '',
            'email' => $actor['email'] ?? '',
            'role' => $this->session->userdata('role_as') ?? 'super_admin'
        ];
    }

    private function logActivity($action, $recordId, $details = '')
    {
        $actor = $this->getCurrentActor();
        $this->Common_model->insertActivityLog([
            'module' => 'weekly_planner',
            'record_id' => $recordId,
            'action' => $action,
            'details' => $details,
            'actor_id' => $actor['id'],
            'actor_name' => $actor['name'],
            'actor_email' => $actor['email'],
            'actor_role' => $actor['role'],
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function plannerPayload($includeCreated = false)
    {
        $activityType = trim((string) $this->input->post('activity_type', true));
        $accountType = $activityType === 'visit'
            ? trim((string) $this->input->post('account_type', true))
            : null;

        $data = [
            'planner_date' => trim((string) $this->input->post('planner_date', true)),
            'activity_type' => $activityType,
            'account_type' => $accountType,
            'company_id' => ($activityType === 'visit' && $accountType === 'existing')
                ? $this->decodeId($this->input->post('company_id'))
                : null,
            'contact_id' => ($activityType === 'visit' && $accountType === 'existing')
                ? $this->decodeId($this->input->post('contact_id'))
                : null,
            'new_person_name' => ($activityType === 'visit' && $accountType === 'new')
                ? trim((string) $this->input->post('new_person_name', true))
                : null,
            'new_person_mobile' => ($activityType === 'visit' && $accountType === 'new')
                ? trim((string) $this->input->post('new_person_mobile', true))
                : null,
            'other_activity' => $activityType === 'other'
                ? trim((string) $this->input->post('other_activity', true))
                : null,
            'description' => trim((string) $this->input->post('description', true)),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;
        }

        return $data;
    }

    private function validatePlannerPayload(array $data)
    {
        $date = DateTime::createFromFormat('Y-m-d', $data['planner_date']);
        if (!$date || $date->format('Y-m-d') !== $data['planner_date']) {
            return 'Please select a valid planner date';
        }

        if (!in_array($data['activity_type'], ['visit', 'other'], true)) {
            return 'Please select a valid activity type';
        }

        if ($data['activity_type'] === 'other') {
            $activities = ['Fairs & Marts', 'In House', 'Others', 'Sales Blitz', 'Tele Calling'];
            return in_array($data['other_activity'], $activities, true)
                ? ''
                : 'Please select a valid activity';
        }

        if (!in_array($data['account_type'], ['existing', 'new'], true)) {
            return 'Please select an account type';
        }

        if ($data['account_type'] === 'new') {
            if ($data['new_person_name'] === '' || $data['new_person_mobile'] === '') {
                return 'Person name and mobile number are required';
            }

            return preg_match('/^[0-9]{10,15}$/', $data['new_person_mobile'])
                ? ''
                : 'Please enter a valid mobile number';
        }

        if (empty($data['company_id']) || empty($data['contact_id'])) {
            return 'Please select a company and contact';
        }

        $validRelationship = $this->db
            ->from('company_contacts cc')
            ->join('companies c', 'c.company_id = cc.company_id', 'inner')
            ->where('cc.contact_id', $data['contact_id'])
            ->where('cc.company_id', $data['company_id'])
            ->where('cc.status', 1)
            ->where('cc.is_deleted', 0)
            ->where('c.status', 1)
            ->where('c.is_deleted', 0)
            ->count_all_results() === 1;

        return $validRelationship ? '' : 'Invalid company contact selection';
    }

    /* =======================
       LOAD MAIN VIEW
    ======================= */
    public function manage()
    {

        $data['companies'] = $this->Common_model->getAllData('companies', ['status' => 1, 'is_deleted' => 0]);


        $data['planners'] = $this->Common_model->getAllData('weekly_planner', ['is_deleted' => 0]);

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/weekly_planner/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    /* =======================
       FETCH LIST (AJAX)
    ======================= */
    public function fetch()
    {

        $data['planners'] = $this->Common_model->getAllData('weekly_planner', ['is_deleted' => 0]);

        $this->load->view('super_admin/weekly_planner/_forms_table', $data);
    }


    public function getCalendarPlans()
    {
        $plans = $this->db
            ->select('id, planner_date, activity_type, description')
            ->where('is_deleted', 0)
            ->get('weekly_planner')
            ->result();

        $events = [];

        foreach ($plans as $row) {

            $title = ($row->activity_type === 'visit')
                ? 'Visit'
                : 'Other Activity';

            if (!empty($row->description)) {
                $title .= ' - ' . substr($row->description, 0, 30);
            }

            $events[] = [
                'id'    => encrypt_id($row->id),
                'title' => $title,
                'start' => $row->planner_date,
                'allDay' => true
            ];
        }

        echo json_encode($events);
    }


    /* =======================
       ADD WEEKLY PLANNER
    ======================= */
    public function add()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $insertData = $this->plannerPayload(true);
        $validationError = $this->validatePlannerPayload($insertData);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => 'error', 'message' => $validationError]);
            return;
        }

        if (!$this->db->insert('weekly_planner', $insertData)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Unable to add weekly planner']);
            return;
        }

        $plannerId = $this->db->insert_id();
        $this->logActivity('create', $plannerId, 'Created weekly planner for '.$insertData['planner_date']);

        $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Weekly planner added successfully'
        ]);
    }

    /* =======================
       GET DETAILS (EDIT)
    ======================= */
    public function getDetails()
    {
        $id = $this->decodeId($this->input->post('id') ?: $this->input->get('id'));

        $this->db->select('*');
        $this->db->from('weekly_planner');
        $this->db->where('id', $id);
        $this->db->where('is_deleted', 0);
        $data = $this->db->get()->row();

        if ($data) {
            $companyName = '';
            if (!empty($data->company_id)) {
                $company = $this->Common_model->getdata('companies', ['company_id' => $data->company_id]);
                $companyName = $company->company_name ?? '';
            }

            $data->id = encrypt_id($data->id);
            $data->company_id = !empty($data->company_id) ? encrypt_id($data->company_id) : '';
            $data->contact_id = !empty($data->contact_id) ? encrypt_id($data->contact_id) : '';
            $data->company_name = $companyName;

            $this->jsonResponse([
                'status' => 'success',
                'data'   => $data
            ]);
        } else {
            $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Planner not found'
            ]);
        }
    }

    /* =======================
       UPDATE WEEKLY PLANNER
    ======================= */
    public function update()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $id = $this->decodeId($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid planner record'
            ]);
            return;
        }

        $planner = $this->db->where('id', $id)->where('is_deleted', 0)->get('weekly_planner')->row();
        if (!$planner) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Planner not found']);
            return;
        }

        $updateData = $this->plannerPayload(false);
        $validationError = $this->validatePlannerPayload($updateData);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => 'error', 'message' => $validationError]);
            return;
        }

        $updated = $this->db
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->update('weekly_planner', $updateData);

        if (!$updated) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Unable to update weekly planner']);
            return;
        }

        $this->logActivity('update', $id, 'Updated weekly planner for '.$updateData['planner_date']);

        $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Weekly planner updated successfully'
        ]);
    }

    /* =======================
       DELETE WEEKLY PLANNER
    ======================= */
    public function delete()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $id = $this->decodeId($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid planner record'
            ]);
            return;
        }

        $planner = $this->db->where('id', $id)->where('is_deleted', 0)->get('weekly_planner')->row();
        if (!$planner) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Planner not found']);
            return;
        }

        $deleted = $this->db
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->update('weekly_planner', [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if (!$deleted) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Unable to delete weekly planner']);
            return;
        }

        $this->logActivity('delete', $id, 'Soft deleted weekly planner for '.$planner->planner_date);

        $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Weekly planner deleted successfully'
        ]);
    }
}

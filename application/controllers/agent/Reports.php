<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
    }

    private function is_agent_logged_in()
    {
        return !empty($this->session->userdata('agent_session'))
            && $this->session->userdata('role_as') === 'agent';
    }

    /**
     * Resolve report access from the logged-in agent only. Request parameters are
     * deliberately not used for the hotel or the permitted department list.
     */
    private function get_agent_scope()
    {
        $agent = $this->session->userdata('agent_session');
        $hotel_id = (int) $this->session->userdata('selected_hotel_id');

        if (empty($agent['id']) || $hotel_id <= 0) {
            return null;
        }

        $mappings = $this->db
            ->select('hotel_id, department_id')
            ->distinct()
            ->from('staff_hotel_department_mapping')
            ->where('staff_id', (int) $agent['id'])
            ->where('hotel_id', $hotel_id)
            ->get()
            ->result_array();

        if (empty($mappings)) {
            return null;
        }

        return [
            'hotel_id' => $hotel_id,
            'department_ids' => array_values(array_unique(array_map('intval', array_column($mappings, 'department_id'))))
        ];
    }

    private function json_response(array $payload, $status = 200)
    {
        $payload['csrfHash'] = $this->security->get_csrf_hash();

        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE));
    }

    private function clean_filter($value)
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }

    private function clean_multi_filter($value)
    {
        if ($value === null || $value === '') {
            return [];
        }

        $values = is_array($value) ? $value : [$value];
        $values = array_map(function ($item) {
            return is_scalar($item) ? trim((string) $item) : '';
        }, $values);

        return array_values(array_unique(array_filter($values, function ($item) {
            return $item !== '';
        })));
    }

    private function report_filters(array $scope)
    {
        $requested_departments = array_map('intval', $this->clean_multi_filter($this->input->post('department', true)));
        $departments = array_values(array_intersect($requested_departments, $scope['department_ids']));

        $start_date = $this->clean_filter($this->input->post('start_date', true));
        $end_date = $this->clean_filter($this->input->post('end_date', true));

        if ($start_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date)) {
            $start_date = '';
        }
        if ($end_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
            $end_date = '';
        }

        return [
            'department' => $departments,
            'status' => $this->clean_multi_filter($this->input->post('status', true)),
            'channel' => $this->clean_multi_filter($this->input->post('channel', true)),
            'disposition' => $this->clean_multi_filter($this->input->post('disposition', true)),
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
    }

    private function build_report_query(array $scope, array $filters = [], $search = '')
    {
        $this->db
            ->from('leads')
            ->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left')
            ->join('departments', 'leads.type = departments.department_id', 'left')
            ->join('city', 'hotel_admin.city_id = city.city_id', 'left')
            ->where('leads.is_deleted', 0)
            ->where('leads.property', $scope['hotel_id'])
            ->where_in('leads.type', $scope['department_ids']);

        if (!empty($filters['department'])) {
            $this->db->where_in('leads.type', $filters['department']);
        }
        if (!empty($filters['status'])) {
            $this->db->where_in('leads.status', $filters['status']);
        }
        if (!empty($filters['channel'])) {
            $this->db->where_in('leads.user_channel', $filters['channel']);
        }
        if (!empty($filters['disposition'])) {
            $this->db->where_in('leads.disposition', $filters['disposition']);
        }
        if ($filters['start_date'] ?? '') {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }
        if ($filters['end_date'] ?? '') {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }

        // Preserve the report's existing default range when no dates are selected.
        if (empty($filters['start_date']) && empty($filters['end_date']) && !empty($filters)) {
            $this->db->where('DATE(leads.created_at) >=', date('Y-m-d', strtotime('-15 days')));
        }

        if ($search !== '') {
            $this->db->group_start()
                ->like('leads.id', $search)
                ->or_like('leads.user_name', $search)
                ->or_like('leads.phone_number', $search)
                ->or_like('leads.email', $search)
                ->or_like('leads.status', $search)
                ->or_like('leads.disposition', $search)
                ->or_like('leads.user_channel', $search)
                ->or_like('hotel_admin.hotel_name', $search)
                ->or_like('departments.department_name', $search)
                ->or_like('city.city_name', $search)
                ->group_end();
        }

        return $this->db;
    }

    private function scoped_distinct_values(array $scope, $field)
    {
        $allowed = ['status', 'user_channel', 'disposition'];
        if (!in_array($field, $allowed, true)) {
            return [];
        }

        $rows = $this->db
            ->select($field)
            ->distinct()
            ->from('leads')
            ->where('is_deleted', 0)
            ->where('property', $scope['hotel_id'])
            ->where_in('type', $scope['department_ids'])
            ->where($field . ' IS NOT NULL', null, false)
            ->where($field . ' !=', '')
            ->order_by($field, 'ASC')
            ->get()
            ->result_array();

        return array_column($rows, $field);
    }

    private function format_date($value, $with_time = true)
    {
        if (empty($value) || !strtotime($value)) {
            return 'NA';
        }

        return date($with_time ? 'd M Y, h:i A' : 'd M Y', strtotime($value));
    }

    private function escape($value)
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    public function index()
    {
        if (!$this->is_agent_logged_in()) {
            return redirect('agent-login');
        }

        $scope = $this->get_agent_scope();
        if ($scope === null) {
            return redirect('agent-dashboard');
        }

        $data['property'] = $this->db
            ->where('hotel_id', $scope['hotel_id'])
            ->get('hotel_admin')
            ->row();

        $data['departments'] = $this->db
            ->select('d.department_id, d.department_name')
            ->distinct()
            ->from('departments d')
            ->where_in('d.department_id', $scope['department_ids'])
            ->order_by('d.department_name', 'ASC')
            ->get()
            ->result();

        $data['statuses'] = $this->scoped_distinct_values($scope, 'status');
        $data['channels'] = $this->scoped_distinct_values($scope, 'user_channel');
        $data['dispositions'] = $this->scoped_distinct_values($scope, 'disposition');

        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/reports/custom_report', $data);
        $this->load->view('agent/include/footer');
    }

    public function data_table()
    {
        if ($this->input->method() !== 'post') {
            return $this->json_response(['status' => false, 'message' => 'Method not allowed.'], 405);
        }
        if (!$this->is_agent_logged_in()) {
            return $this->json_response(['status' => false, 'message' => 'Your session has expired.'], 401);
        }

        $scope = $this->get_agent_scope();
        if ($scope === null) {
            return $this->json_response(['status' => false, 'message' => 'No hotel access is assigned to this agent.'], 403);
        }

        $draw = max(0, (int) $this->input->post('draw'));
        $start = max(0, (int) $this->input->post('start'));
        $length = (int) $this->input->post('length');
        $length = $length < 1 ? 10 : min($length, 200);
        $filters = $this->report_filters($scope);

        $search_input = $this->input->post('search');
        $search = is_array($search_input) ? $this->clean_filter($search_input['value'] ?? '') : '';

        $this->build_report_query($scope);
        $records_total = (int) $this->db->count_all_results();

        $this->build_report_query($scope, $filters, $search);
        $records_filtered = (int) $this->db->count_all_results();

        $order_columns = [
            'leads.id', 'city.city_name', 'hotel_admin.hotel_name', 'departments.department_name',
            'leads.user_name', 'leads.phone_number', 'leads.email', 'leads.status',
            'leads.disposition', 'leads.user_channel', 'leads.created_at', 'leads.responded_time',
            'leads.completed_time', 'leads.booking_enquiry_date', 'leads.checkin_date',
            'leads.checkout_date', 'leads.followup_date', 'leads.second_followup_date',
            'leads.pax', 'leads.query', 'leads.remark', 'leads.amount', 'leads.id'
        ];
        $order_input = $this->input->post('order');
        $order_column = is_array($order_input) ? (int) ($order_input[0]['column'] ?? 10) : 10;
        $order_direction = is_array($order_input) && strtolower($order_input[0]['dir'] ?? '') === 'asc' ? 'ASC' : 'DESC';
        $order_by = $order_columns[$order_column] ?? 'leads.created_at';

        $this->build_report_query($scope, $filters, $search);
        $rows = $this->db
            ->select('leads.*, hotel_admin.hotel_name, departments.department_name, city.city_name')
            ->order_by($order_by, $order_direction)
            ->limit($length, $start)
            ->get()
            ->result_array();

        $data = [];
        foreach ($rows as $row) {
            $materialized = strtolower((string) ($row['disposition'] ?? '')) === 'reservation'
                && strtolower((string) ($row['status'] ?? '')) === 'closed';

            $data[] = [
                $this->escape($row['id'] ?? ''),
                $this->escape($row['city_name'] ?? ''),
                $this->escape($row['hotel_name'] ?? ''),
                $this->escape($row['department_name'] ?? ''),
                $this->escape($row['user_name'] ?? ''),
                $this->escape($row['phone_number'] ?? ''),
                $this->escape($row['email'] ?? ''),
                $this->escape($row['status'] ?? ''),
                $this->escape($row['disposition'] ?? ''),
                $this->escape($row['user_channel'] ?? ''),
                $this->format_date($row['created_at'] ?? null),
                $this->format_date($row['responded_time'] ?? null),
                $this->format_date($row['completed_time'] ?? null),
                $this->format_date($row['booking_enquiry_date'] ?? null),
                $this->format_date($row['checkin_date'] ?? null),
                $this->format_date($row['checkout_date'] ?? null),
                $this->format_date($row['followup_date'] ?? null, false),
                $this->format_date($row['second_followup_date'] ?? null, false),
                $this->escape($row['pax'] ?: 'NA'),
                nl2br($this->escape($row['query'] ?? '')),
                nl2br($this->escape($row['remark'] ?? '')),
                number_format((float) ($row['amount'] ?? 0), 2),
                $materialized ? 'Yes' : 'No'
            ];
        }

        return $this->json_response([
            'draw' => $draw,
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_filtered,
            'data' => $data
        ]);
    }
}

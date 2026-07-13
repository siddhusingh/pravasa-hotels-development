<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ActivityLogs extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }
    }

    public function index()
    {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/activity_logs');
        $this->load->view('super_admin/include/footer');
    }

    public function get_activity_logs_table()
    {
        $inputs = $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = (int) ($inputs['length'] ?? 10);
        $length = ($length > 0 && $length <= 100) ? $length : 10;
        $search = trim($inputs['search']['value'] ?? '');
        $startDate = trim($inputs['start_date'] ?? '');
        $endDate = trim($inputs['end_date'] ?? '');

        $columns = ['id', 'module', 'action', 'actor_name', 'actor_role', 'ip_address', 'created_at', 'details'];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 6);
        $order = $columns[$orderIndex] ?? 'created_at';
        $direction = strtolower($inputs['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

        $applyFilters = function () use ($search, $startDate, $endDate) {
            if ($search !== '') {
                $this->db->group_start()
                    ->like('module', $search)
                    ->or_like('action', $search)
                    ->or_like('actor_name', $search)
                    ->or_like('actor_role', $search)
                    ->or_like('ip_address', $search)
                    ->or_like('details', $search)
                    ->group_end();
            }
            if ($startDate !== '') {
                $this->db->where('created_at >=', $startDate . ' 00:00:00');
            }
            if ($endDate !== '') {
                $this->db->where('created_at <=', $endDate . ' 23:59:59');
            }
        };

        $recordsTotal = $this->db->count_all('activity_logs');
        $this->db->from('activity_logs');
        $applyFilters();
        $recordsFiltered = $this->db->count_all_results();

        $this->db->from('activity_logs');
        $applyFilters();
        $this->db->order_by($order, $direction)->limit($length, $start);
        $logs = $this->db->get()->result();

        $data = [];
        $number = $start + 1;
        foreach ($logs as $log) {
            $details = !empty($log->details) ? mb_substr($log->details, 0, 200) : '-';
            if (!empty($log->details) && mb_strlen($log->details) > 200) {
                $details .= '...';
            }
            $data[] = [
                $number++,
                html_escape($log->module ?: 'N/A'),
                html_escape($log->action ?: 'N/A'),
                html_escape($log->actor_name ?: 'Unknown'),
                html_escape($log->actor_role ?: 'Unknown'),
                html_escape($log->ip_address ?: 'N/A'),
                !empty($log->created_at) ? date('d-m-Y H:i:s', strtotime($log->created_at)) : 'N/A',
                nl2br(html_escape($details))
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }
}

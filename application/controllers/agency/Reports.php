<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

require_once FCPATH . 'vendor/autoload.php';

class Reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeadModel');
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');
    }

    public function index()
    {
        $agencySession = $this->session->userdata('agency_session');
        if (empty($agencySession['id']) || $this->session->userdata('role_as') !== 'agency') {
            return redirect('agency-login');
        }

        $agencyId = (int) $agencySession['id'];

        // POST submissions are validated by CodeIgniter's configured CSRF protection.
        $filterValues = [
            'property' => trim((string) $this->input->post('property', true)),
            'department' => trim((string) $this->input->post('department', true)),
            'status' => trim((string) $this->input->post('status', true)),
            'channel' => trim((string) $this->input->post('channel', true)),
            'start_date' => trim((string) $this->input->post('start_date', true)),
            'end_date' => trim((string) $this->input->post('end_date', true)),
            'disposition' => trim((string) $this->input->post('disposition', true))
        ];

        $filters = [
            'property' => $filterValues['property'] !== '' ? [$filterValues['property']] : [],
            'department' => $filterValues['department'] !== '' ? [$filterValues['department']] : [],
            'status' => $filterValues['status'] !== '' ? [$filterValues['status']] : [],
            'channel' => $filterValues['channel'] !== '' ? [$filterValues['channel']] : [],
            'start_date' => $filterValues['start_date'],
            'end_date' => $filterValues['end_date'],
            'disposition' => $filterValues['disposition'] !== '' ? [$filterValues['disposition']] : [],
            // This scope is server-controlled and cannot be overridden by form input.
            'created_id' => $agencyId,
            'created_role' => 'Agency'
        ];

        $data['departments'] = $this->Common_model->getAllData('departments', ['is_deleted' => 0]);
        $data['hotel_admin'] = $this->Common_model->get_properties_by_agency($agencyId);
        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', [
            'is_deleted' => 0,
            'created_by' => $agencyId,
            'creator_user_role' => 'Agency'
        ]);
        $data['filters'] = $filterValues;
        $data['leads'] = $this->LeadModel->get_leads_for_reports($filters);

        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/reports/custom_report', $data);
        $this->load->view('agency/include/footer');
    }
}

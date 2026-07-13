<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

require_once FCPATH . 'vendor/autoload.php';  // Correct path to autoload.php


class Reports extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeadModel'); // Load Model
        $this->load->model('Comman_model');
        $this->load->model('Common_model');


        $this->load->helper('download');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }
    }



    public function index()
    {


        $data['creators'] = $this->LeadModel->get_active_creators();

        $data['assigned_users'] = $this->LeadModel->get_active_assigned_users();





        // Get filter values from GET request
        $filters = [
            'city' => $this->input->get('city'),
            'property' => $this->input->get('property'),
            'department' => $this->input->get('department'),
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'disposition' => $this->input->get('disposition'), // 🆕 Added Stage filter

        ];






        // Clean filters (remove empty values)
        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        // Fetch filtered leads
        $data['leads'] = $this->LeadModel->get_leads_for_reports($activeFilters);







        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');
        $data['cities'] = $this->Common_model->getAllData('city', '');

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');






        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/reports/custom_report', $data);
        $this->load->view('super_admin/include/footer');
    }


    public function lead_by_dispostion()
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }




        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property'), // array
            'department' => $this->input->post('department') // array
        ];

        $data['leads'] = $this->LeadModel->get_disposition_report($filters);


        // echo "<pre>";
        // print_r($data);
        // die();



        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/reports/lead_by_dispositons', $data);
        $this->load->view('super_admin/include/footer');
    }




    public function lead_by_department()
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }




        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property'), // array
            'department' => $this->input->post('department') // array
        ];

        $data['leads'] = $this->LeadModel->get_department_report($filters);






        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/reports/lead_by_department', $data);
        $this->load->view('super_admin/include/footer');
    }


    public function lead_by_source()
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }




        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property'), // array
            'department' => $this->input->post('department') // array
        ];

        $data['leads'] = $this->LeadModel->get_user_channel_report($filters);






        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/reports/lead_by_source', $data);
        $this->load->view('super_admin/include/footer');
    }


    public function hotel_department_status_report()
    {
        // Get filters from POST
        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?? [],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];



        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['departments'] = $this->Common_model->getAllData('departments', ['department_id <>' => 14]);

        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $data['report_data']  = $this->LeadModel->get_hotel_department_status_report($filters);




        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/reports/get_hotel_department_status_report', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function summary_report()
    {
        // Get filters from POST
        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?? [],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];



        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $data['report_data']  = $this->LeadModel->get_property_department_channel_report($filters);






        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/reports/summary_report', $data);
        $this->load->view('super_admin/include/footer');
    }



    public function filter_lead_by_dispostion()
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }





        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property'), // array
            'department' => $this->input->post('department') // array
        ];

        $data['leads'] = $this->LeadModel->get_disposition_report($filters);

        $html = $this->load->view('super_admin/reports/filter_lead_by_disposition', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true,
            'csrfHash' => $this->security->get_csrf_hash()

        ]);
    }


    public function filter_lead_by_department()
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }







        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property'), // array
            'department' => $this->input->post('department') // array
        ];

        $data['leads'] = $this->LeadModel->get_department_report($filters);

        $html = $this->load->view('super_admin/reports/filter_lead_by_department', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true,
            'csrfHash' => $this->security->get_csrf_hash()

        ]);
    }


    public function filter_lead_by_source()
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }




        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');


        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property'), // array
            'department' => $this->input->post('department') // array
        ];

        $data['leads'] = $this->LeadModel->get_user_channel_report($filters);



        $html = $this->load->view('super_admin/reports/filter_lead_by_source', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true,
            'csrfHash' => $this->security->get_csrf_hash()

        ]);
    }


    public function filter_hotel_department_status_report()
    {
        // Get filters from POST
        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?? [],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['departments'] = $this->Common_model->getAllData('departments', ['department_id <>' => 14]);




        $data['report_data']  = $this->LeadModel->get_hotel_department_status_report($filters);




        $html = $this->load->view('super_admin/reports/filter_get_hotel_department_status_report', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true,
            'csrfHash' => $this->security->get_csrf_hash()

        ]);
    }


    public function filter_summary_report()
    {
        // Get filters from POST
        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => $this->input->post('property') ?? [],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];






        $data['report_data']  = $this->LeadModel->get_property_department_channel_report($filters);


        $html = $this->load->view('super_admin/reports/filter_summary_report', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true,
            'csrfHash' => $this->security->get_csrf_hash()

        ]);
    }

    public function filter_custom_report()
    {


        // Get filter values from GET request
        $filters = [
            'city' => $this->input->post('city'),
            'property' => $this->input->post('property'),
            'department' => $this->input->post('department'),
            'status' => $this->input->post('status'),
            'channel' => $this->input->post('channel'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'disposition' => $this->input->post('disposition'), // 🆕 Added Stage filter
            'business_type' => $this->input->post('business_type'), // 🆕 Added Stage filter
            // ✅ ADD THESE (IMPORTANT)
            'created_id'    => $this->input->post('created_id'),
            'created_role'  => $this->input->post('created_role'),
            'assigned_id'   => $this->input->post('assigned_id'),
            'assigned_role' => $this->input->post('assigned_role'),


        ];

        if ($this->input->post('draw') !== null) {
            $draw = (int) $this->input->post('draw');
            $start = max(0, (int) $this->input->post('start'));
            $length = (int) $this->input->post('length');
            $length = ($length > 0 && $length <= 100) ? $length : 10;
            $searchInput = $this->input->post('search');
            $search = strtolower(trim(is_array($searchInput) ? ($searchInput['value'] ?? '') : ''));

            $leads = $this->LeadModel->get_leads_for_reports($filters);
            $recordsTotal = $this->db->count_all('leads');

            if ($search !== '') {
                $searchFields = ['id', 'city_name', 'hotel_name', 'department_name', 'user_name', 'phone_number',
                    'email', 'status', 'disposition', 'user_channel', 'creator_name', 'assigned_person_name',
                    'query', 'remark', 'reason'];
                $leads = array_values(array_filter($leads, function ($lead) use ($search, $searchFields) {
                    foreach ($searchFields as $field) {
                        if (strpos(strtolower((string) ($lead[$field] ?? '')), $search) !== false) return true;
                    }
                    return false;
                }));
            }

            $recordsFiltered = count($leads);
            $orderInput = $this->input->post('order');
            $orderIndex = (int) (is_array($orderInput) ? ($orderInput[0]['column'] ?? 12) : 12);
            $direction = is_array($orderInput) && strtolower($orderInput[0]['dir'] ?? 'desc') === 'asc' ? 1 : -1;
            $orderFields = ['id', 'city_name', 'hotel_name', 'department_name', 'user_name', 'phone_number', 'email',
                'status', 'disposition', 'user_channel', 'creator_name', 'assigned_person_name', 'created_at',
                'responded_time', 'completed_time', 'booking_enquiry_date', 'checkin_date', 'checkout_date',
                'followup_date', 'second_followup_date', 'pax', 'query', 'amount', 'disposition', 'remark', 'reason'];
            $orderField = $orderFields[$orderIndex] ?? 'created_at';
            usort($leads, function ($a, $b) use ($orderField, $direction) {
                return (($a[$orderField] ?? '') <=> ($b[$orderField] ?? '')) * $direction;
            });

            $formatDate = function ($value, $withTime = true) {
                return !empty($value) && strtotime($value)
                    ? date($withTime ? 'd M Y, h:i A' : 'd M Y', strtotime($value)) : 'NA';
            };
            $data = [];
            foreach (array_slice($leads, $start, $length) as $lead) {
                $safe = function ($field, $fallback = 'NA') use ($lead) {
                    return html_escape(isset($lead[$field]) && $lead[$field] !== '' ? $lead[$field] : $fallback);
                };
                $materialized = strtolower((string) ($lead['disposition'] ?? '')) === 'reservation'
                    && strtolower((string) ($lead['status'] ?? '')) === 'closed' ? 'Yes' : 'No';
                $data[] = [$safe('id'), $safe('city_name'), $safe('hotel_name'), $safe('department_name'),
                    $safe('user_name'), $safe('phone_number'), $safe('email'), $safe('status'), $safe('disposition'),
                    $safe('user_channel'), $safe('creator_name'), $safe('assigned_person_name'),
                    $formatDate($lead['created_at'] ?? null), $formatDate($lead['responded_time'] ?? null),
                    $formatDate($lead['completed_time'] ?? null), $formatDate($lead['booking_enquiry_date'] ?? null),
                    $formatDate($lead['checkin_date'] ?? null), $formatDate($lead['checkout_date'] ?? null),
                    $formatDate($lead['followup_date'] ?? null, false), $formatDate($lead['second_followup_date'] ?? null, false),
                    $safe('pax'), nl2br($safe('query')), number_format((float) ($lead['amount'] ?? 0), 2),
                    $materialized, nl2br($safe('remark')), nl2br($safe('reason'))];
            }

            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'draw' => $draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered,
                'data' => $data, 'csrfHash' => $this->security->get_csrf_hash()
            ]));
        }




        // Fetch filtered leads
        $data['leads'] = $this->LeadModel->get_leads_for_reports($filters);








        $html = $this->load->view('super_admin/reports/filter_custom_report', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true,
            'csrfHash' => $this->security->get_csrf_hash()

        ]);
    }
}

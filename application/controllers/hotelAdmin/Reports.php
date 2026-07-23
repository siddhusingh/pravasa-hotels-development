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

        if (empty($this->session->userdata('hotel_admin_session'))) {
            return redirect('hotel-admin-login');
        }
    }



    public function index()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        // Get filter values from GET request
        $filters = [
            'city' => $this->input->get('city'),
            'property'   => [$property],
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
        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['properties'] = $fixed_property ? [$fixed_property] : [];
        $data['cities'] = $this->Common_model->getAllData('city', '');

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');






        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/custom_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    public function lead_by_dispostion()
    {




        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Populate dropdown values
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['properties'] = $fixed_property ? [$fixed_property] : [];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];


        $data['leads'] = $this->LeadModel->get_disposition_report($filters);





        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/lead_by_dispositons', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    public function lead_by_department()
    {




        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        // Populate dropdown values while keeping Hotel Property fixed.
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['properties'] = $fixed_property ? [$fixed_property] : [];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];


        $data['leads'] = $this->LeadModel->get_department_report($filters);






        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/lead_by_department', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    public function lead_by_source()
    {




        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        // Populate dropdown values while keeping Hotel Property fixed.
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['properties'] = $fixed_property ? [$fixed_property] : [];

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];


        $data['leads'] = $this->LeadModel->get_user_channel_report($filters);






        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/lead_by_source', $data);
        $this->load->view('hotel_admin/include/footer');
    }


    public function hotel_department_status_report()
    {
        // Get filters from POST

        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];


        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['departments'] = $this->Common_model->getAllData('departments', ['department_id <>' => 14]);

        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['properties'] = $fixed_property ? [$fixed_property] : [];


        $data['report_data']  = $this->LeadModel->get_hotel_department_status_report($filters);




        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/get_hotel_department_status_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    public function summary_report()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['properties'] = $fixed_property ? [$fixed_property] : [];


        $data['report_data']  = $this->LeadModel->get_property_department_channel_report($filters);




        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/reports/summary_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    public function filter_lead_by_dispostion()
    {







        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['leads'] = $this->LeadModel->get_disposition_report($filters);

        $html = $this->load->view('hotel_admin/reports/filter_lead_by_disposition', $data, true);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'html' => $html,
                'status' => true,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }


    public function filter_lead_by_department()
    {









        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['leads'] = $this->LeadModel->get_department_report($filters);

        $html = $this->load->view('hotel_admin/reports/filter_lead_by_department', $data, true);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'html' => $html,
                'status' => true,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }


    public function filter_lead_by_source()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['leads'] = $this->LeadModel->get_user_channel_report($filters);



        $html = $this->load->view('hotel_admin/reports/filter_lead_by_source', $data, true);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'html' => $html,
                'status' => true,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }


    public function filter_hotel_department_status_report()
    {
        // Get filters from POST
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];

        $data['departments'] = $this->Common_model->getAllData('departments', '');

        $data['departments'] = $this->Common_model->getAllData('departments', ['department_id <>' => 14]);




        $data['report_data']  = $this->LeadModel->get_hotel_department_status_report($filters);




        $html = $this->load->view('hotel_admin/reports/filter_get_hotel_department_status_report', $data, true);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'html' => $html,
                'status' => true,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }


    public function filter_summary_report()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date'   => $this->input->post('end_date'),
            'property'   => [$property],
            'department' => $this->input->post('department') ?? [],
            'status'     => $this->input->post('status') ?? [],
        ];






        $data['report_data']  = $this->LeadModel->get_property_department_channel_report($filters);


        $html = $this->load->view('hotel_admin/reports/filter_summary_report', $data, true);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'html' => $html,
                'status' => true,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }

    public function filter_custom_report()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        $filters = [
            'city' => $this->input->post('city'),
            'property' => [$property],
            'department' => $this->input->post('department'),
            'status' => $this->input->post('status'),
            'channel' => $this->input->post('channel'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'disposition' => $this->input->post('disposition'),
            'business_type' => $this->input->post('business_type'),
        ];

        if ($this->input->post('draw') !== null) {
            $draw = (int) $this->input->post('draw');
            $start = max(0, (int) $this->input->post('start'));
            $length = (int) $this->input->post('length');
            $length = ($length > 0 && $length <= 100) ? $length : 10;
            $search_input = $this->input->post('search');
            $search = strtolower(trim(is_array($search_input) ? ($search_input['value'] ?? '') : ''));

            $leads = $this->LeadModel->get_leads_for_reports($filters);
            $records_total = count($this->LeadModel->get_leads_for_reports(['property' => [$property]]));

            if ($search !== '') {
                $search_fields = [
                    'id', 'city_name', 'hotel_name', 'department_name', 'user_name',
                    'phone_number', 'email', 'status', 'disposition', 'user_channel',
                    'creator_name', 'assigned_person_name', 'query', 'remark', 'reason'
                ];
                $leads = array_values(array_filter($leads, function ($lead) use ($search, $search_fields) {
                    foreach ($search_fields as $field) {
                        if (strpos(strtolower((string) ($lead[$field] ?? '')), $search) !== false) {
                            return true;
                        }
                    }
                    return false;
                }));
            }

            $records_filtered = count($leads);
            $order_input = $this->input->post('order');
            $order_index = (int) (is_array($order_input) ? ($order_input[0]['column'] ?? 12) : 12);
            $direction = is_array($order_input) && strtolower($order_input[0]['dir'] ?? 'desc') === 'asc' ? 1 : -1;
            $order_fields = [
                'id', 'city_name', 'hotel_name', 'department_name', 'user_name', 'phone_number',
                'email', 'status', 'disposition', 'user_channel', 'creator_name', 'assigned_person_name',
                'created_at', 'responded_time', 'completed_time', 'booking_enquiry_date', 'checkin_date',
                'checkout_date', 'followup_date', 'second_followup_date', 'pax', 'query', 'amount',
                'disposition', 'remark', 'reason'
            ];
            $order_field = $order_fields[$order_index] ?? 'created_at';
            usort($leads, function ($a, $b) use ($order_field, $direction) {
                return (($a[$order_field] ?? '') <=> ($b[$order_field] ?? '')) * $direction;
            });

            $format_date = function ($value, $with_time = true) {
                return !empty($value) && strtotime($value)
                    ? date($with_time ? 'd M Y, h:i A' : 'd M Y', strtotime($value))
                    : 'NA';
            };
            $rows = [];
            foreach (array_slice($leads, $start, $length) as $lead) {
                $safe = function ($field, $fallback = 'NA') use ($lead) {
                    return html_escape(isset($lead[$field]) && $lead[$field] !== '' ? $lead[$field] : $fallback);
                };
                $materialized = strtolower((string) ($lead['disposition'] ?? '')) === 'reservation'
                    && strtolower((string) ($lead['status'] ?? '')) === 'closed' ? 'Yes' : 'No';
                $rows[] = [
                    $safe('id'), $safe('city_name'), $safe('hotel_name'), $safe('department_name'),
                    $safe('user_name'), $safe('phone_number'), $safe('email'), $safe('status'),
                    $safe('disposition'), $safe('user_channel'), $safe('creator_name'),
                    $safe('assigned_person_name'), $format_date($lead['created_at'] ?? null),
                    $format_date($lead['responded_time'] ?? null), $format_date($lead['completed_time'] ?? null),
                    $format_date($lead['booking_enquiry_date'] ?? null), $format_date($lead['checkin_date'] ?? null),
                    $format_date($lead['checkout_date'] ?? null), $format_date($lead['followup_date'] ?? null, false),
                    $format_date($lead['second_followup_date'] ?? null, false), $safe('pax'), nl2br($safe('query')),
                    number_format((float) ($lead['amount'] ?? 0), 2), $materialized,
                    nl2br($safe('remark')), nl2br($safe('reason'))
                ];
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'draw' => $draw,
                    'recordsTotal' => $records_total,
                    'recordsFiltered' => $records_filtered,
                    'data' => $rows,
                    'csrfHash' => $this->security->get_csrf_hash(),
                ]));
        }

        $data['leads'] = $this->LeadModel->get_leads_for_reports($filters);
        $html = $this->load->view('hotel_admin/reports/filter_custom_report', $data, true);

        echo json_encode([
            'html' => $html,
            'status' => true,
            'csrfHash' => $this->security->get_csrf_hash(),
        ]);
    }
}

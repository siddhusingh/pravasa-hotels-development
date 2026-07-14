<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

require_once FCPATH . 'vendor/autoload.php';  // Correct path to autoload.php


class LeadController extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeadModel'); // Load Model
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');
    }

    public function receive_lead()
    {

        // Handle preflight OPTIONS request first
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
            http_response_code(200);
            exit();
        }

        // Actual CORS headers for other requests
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        // Now continue with your logic
        $input_data = json_decode(file_get_contents('php://input'), true);

        if (!$input_data) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => false, 'message' => 'Invalid JSON Data']));
        }

        $fields = [
            'user_name',
            'phone_number',
            'email',
            'date',
            'user_channel',
            'type',
            'status',
            'disposition',
            'created_at',
            'query',
            'remark',
            'time',
            'status_code',
            'last_query',
            'location_address',
            'ip_address',
            // These will be mapped to IDs:
            'property',
            'city',
            'state',
            'country',
            'template_name',
            'checkin_date',
            'checkout_date',
            'booking_date',
            'pax',
            'amount',

        ];


        $lead_data = [];

        foreach ($fields as $field) {

            if ($field == 'type') {

                // type must NEVER be null; initially store department name
                $lead_data['type'] = (
                    isset($input_data['type']) &&
                    trim((string)$input_data['type']) !== ''
                ) ? trim((string)$input_data['type']) : 'Restaurants';
            } else {

                $lead_data[$field] = isset($input_data[$field])
                    ? trim((string)$input_data[$field])
                    : null;
            }
        }

        // Convert names to IDs
        $lead_data['city']     = $this->LeadModel->get_city_id($lead_data['city']);
        $lead_data['state']    = $this->LeadModel->get_state_id($lead_data['state']);
        $lead_data['country']  = $this->LeadModel->get_country_id($lead_data['country']);
        $lead_data['property'] = $this->LeadModel->get_property_id($lead_data['property']);

        // --- Resolve department_id from department name in type ---
        $department_id = $this->LeadModel->get_department_id($lead_data['type']);

        // Fallback safety if department_id not found
        if (empty($department_id)) {
            $department_id = 2; // default department
        }

        // Overwrite type with department_id for saving in DB
        $lead_data['type'] = $department_id;

        // Fetch department config for escalation
        $department_data = $this->Common_model->getdata(
            'departments',
            ['department_id' => $department_id]
        );

        // Safety check for escalation hours
        $decimal_hours = (float) ($department_data->escalation_level_1 ?? 1);

        // Convert hours → minutes
        $minutes_to_add = (int) ($decimal_hours * 60);

        // Calculate follow-up time
        $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $current_time->modify("+{$minutes_to_add} minutes");

        $lead_data['esc_next_followup_at'] = $current_time->format('Y-m-d H:i:s');
        $lead_data['esc_follow_up_level']  = 1;






        // Optional: Validate required fields
        if (empty($lead_data['phone_number']) || empty($lead_data['property'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode(['status' => false, 'message' => 'Required fields missing']));
        }

        $lead_data['status'] = 'Open';

        $lead_data['created_at'] = date('Y-m-d H:i:s');

        $phone = $lead_data['phone_number'];
        $last10Digits = substr($phone, -10); // always extract last 10 digits
        $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));

        $existingLead = $this->db
            ->where("RIGHT(phone_number, 10) =", $last10Digits, false)
            ->where('created_at >=', $twoHoursAgo)
            ->where('status !=', 'Closed')
            ->order_by('id', 'DESC')
            ->get('leads')
            ->row();



        $phone = $lead_data['phone_number'];

        // Check if any previous lead has reservation with revenue > 0
        $valuableGuest = $this->db
            ->select('id, disposition, amount')  // revenue_amount = your amount column
            ->from('leads')
            ->where('phone_number', $phone)
            ->where('LOWER(disposition)', 'reservation')   // case-insensitive match
            ->where('amount >', 0)                // has revenue
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if ($valuableGuest) {
            $IsvaluableGuest = true;
        } else {
            $IsvaluableGuest = false;
        }




        if (!empty($existingLead)) {

            // Do not change original creation timestamp
            unset($lead_data['created_at']);

            // Update existing lead with fresh details
            $this->db->where('id', $existingLead->id)
                ->update('leads', $lead_data);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => true, 'message' => 'Lead saved successfully']));
        }


        // Insert into DB
        $insert_id = $this->LeadModel->insert_lead($lead_data);

        $lead_data['created_at'] = date('Y-m-d H:i:s');





        if ($insert_id) {

            $url = base_url("EmailWorker/sendLeadEmail/$insert_id/$IsvaluableGuest");

            // Fire & Forget HTTP Request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, false); // GET request
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // We don't care about the response
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 100); // Fast timeout
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100); // Don't wait for response
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1); // For timeout under 1s (only on Unix)
            curl_exec($ch);
            curl_close($ch);



            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => true, 'message' => "Lead saved successfully $email_subject"]));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to save lead']));
        }
    }


    public function receive_iframe_lead()
    {
        /* -------------------------------
     *  CORS HANDLING
     * ----------------------------- */
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
            http_response_code(200);
            exit();
        }

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        /* -------------------------------
     *  READ INPUT
     * ----------------------------- */
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Invalid JSON payload'
                ]));
        }

        /* -------------------------------
     *  REQUIRED FIELDS
     * ----------------------------- */
        if (
            empty($input['name']) ||
            empty($input['phone']) ||
            empty($input['property']) ||
            empty($input['department'])
        ) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Name, Phone, Property and Department are required'
                ]));
        }

        /* -------------------------------
     *  MAP IFRAME FIELDS → DB FIELDS
     * ----------------------------- */
        $lead_data = [
            'user_name'      => trim($input['name']),
            'email'          => trim($input['email'] ?? ''),
            'phone_number'   => trim($input['phone']),
            'query'          => trim($input['comments'] ?? ''),
            'property'       => trim($input['property']),
            'type'           => trim($input['department']), // department name for now
            'user_channel' => trim($input['user_channel'] ?? 'Website Contact Form'),
            'status'         => 'Open',
            'created_at'     => date('Y-m-d H:i:s'),
            'date'           => date('Y-m-d'),
            'time'           => date('H:i:s'),
            'ip_address'     => $this->input->ip_address()
        ];

        /* -------------------------------
     *  PROPERTY → ID
     * ----------------------------- */
        $lead_data['property'] = $this->LeadModel->get_property_id($lead_data['property']);

        if (empty($lead_data['property'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Invalid property selected'
                ]));
        }

        /* -------------------------------
     *  DEPARTMENT NAME → ID
     * ----------------------------- */
        $allowedDepartments = ['Rooms', 'Restaurant', 'Banquets'];

        if (!in_array($lead_data['type'], $allowedDepartments)) {
            $lead_data['type'] = 'Rooms'; // default
        }


        $departmentName = $lead_data['type'];

        // $assigned_to = null;

        // if ($departmentName == 'Rooms' || $departmentName == 'Waterpark') {
        //     $assigned_to = 12; // crs1
        // } elseif ($departmentName == 'Banquets') {
        //     $assigned_to = 19; // crs2
        // } elseif ($departmentName == 'Restaurant' || $departmentName == 'Spa') {
        //     $assigned_to = 20; // crs3
        // }

        // // Assign values if matched
        // if ($assigned_to !== null) {
        //     $lead_data['assigned_to'] = $assigned_to;
        //     $lead_data['assigned_person_user_role'] = 'super_admin';
        //     $lead_data['is_assigned'] = 1;
        // } else {
        //     // Optional: handle unknown department
        //     $lead_data['assigned_to'] = null;
        //     $lead_data['assigned_person_user_role'] = null;
        //     $lead_data['is_assigned'] = 0;
        // }





        $department_id = $this->LeadModel->get_department_id($lead_data['type']);
        if (empty($department_id)) {
            $department_id = 2; // fallback
        }

        $lead_data['type'] = $department_id;

        /* -------------------------------
     *  ESCALATION LOGIC (REUSED)
     * ----------------------------- */
        $department_data = $this->Common_model->getdata(
            'departments',
            ['department_id' => $department_id]
        );

        $decimal_hours  = (float) ($department_data->escalation_level_1 ?? 1);
        $minutes_to_add = (int) ($decimal_hours * 60);

        $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $current_time->modify("+{$minutes_to_add} minutes");

        $lead_data['esc_next_followup_at'] = $current_time->format('Y-m-d H:i:s');
        $lead_data['esc_follow_up_level']  = 1;

        /* -------------------------------
     *  DUPLICATE CHECK (2 HOURS)
     * ----------------------------- */
        $last10Digits = substr($lead_data['phone_number'], -10);
        $twoHoursAgo  = date('Y-m-d H:i:s', strtotime('-2 hours'));

        $existingLead = $this->db
            ->where("RIGHT(phone_number, 10) =", $last10Digits, false)
            ->where('created_at >=', $twoHoursAgo)
            ->where('status !=', 'Closed')
            ->order_by('id', 'DESC')
            ->get('leads')
            ->row();

        if (!empty($existingLead)) {

            unset($lead_data['created_at']);

            $this->db->where('id', $existingLead->id)
                ->update('leads', $lead_data);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Lead updated successfully'
                ]));
        }

        /* -------------------------------
     *  INSERT LEAD
     * ----------------------------- */
        $insert_id = $this->LeadModel->insert_lead($lead_data);


        $this->sendwhatsappMessageToAgnent($insert_id);


        if (!$insert_id) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed to save lead'
                ]));
        }

        /* -------------------------------
     *  CHECK VALUABLE GUEST
     * ----------------------------- */
        $valuableGuest = $this->db
            ->select('id')
            ->from('leads')
            ->where('phone_number', $lead_data['phone_number'])
            ->where('LOWER(disposition)', 'reservation')
            ->where('amount >', 0)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $IsvaluableGuest = !empty($valuableGuest);

        /* -------------------------------
     *  FIRE EMAIL WORKER (ASYNC)
     * ----------------------------- */
        // $url = base_url("EmailWorker/sendLeadEmail/$insert_id/$IsvaluableGuest");

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 100);
        // curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        // curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        // curl_exec($ch);
        // curl_close($ch);

        /* -------------------------------
     *  FINAL RESPONSE
     * ----------------------------- */
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Lead submitted successfully'
            ]));
    }



    public function followups()
    {
        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }

        $data['all_assignable_users'] = $this->LeadModel->get_all_assignable_users('hotel_admin', '');
        $data['creators'] = $this->LeadModel->get_active_creators();
        $data['assigned_users'] = $this->LeadModel->get_active_assigned_users();

        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        // Get filter values from GET request
        $filters = [
            'city' => $this->input->get('city'),
            'property' => $this->input->get('property'),
            'department' => $this->input->get('department'),
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'disposition' => $this->input->get('disposition'),
            'phone' => $this->input->get('phone')
        ];

        // Clean filters (remove empty values)
        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        $activeFilters['showfollowupleads'] = 'yes';

        // Fetch filtered leads with follow-up date condition
        $data['leads'] = $this->LeadModel->get_followup_leads($activeFilters);


        // echo "<pre>";
        // print_r($data['leads']);
        // die();
        // Populate dropdown values


        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');
        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');
        $data['cities'] = $this->Common_model->getAllData('city', '');
        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');

        // Load all data to send to view
        $data['lead_status_counts'] = $this->LeadModel->get_lead_counts_grouped_by_status($activeFilters);

        $data['showfollowupleads'] = ($this->uri->segment(1) === 'followups') ? 'yes' : 'no';



        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/lead_report', $data);
        $this->load->view('super_admin/include/footer');
    }


    function testEmails()
    {

        $hotel_id = 1;
        $department_id = 1;
        // Join staff with mapping to get all emails
        $this->db->select('staff_members.email');
        $this->db->from('staff_members');
        $this->db->join('staff_hotel_department_mapping', 'staff_members.id = staff_hotel_department_mapping.staff_id');
        $this->db->where('staff_hotel_department_mapping.level', 1);
        $this->db->where('staff_hotel_department_mapping.hotel_id', $hotel_id);
        $this->db->where('staff_hotel_department_mapping.department_id', $department_id);

        $query = $this->db->get();
        $result = $query->result();

        $emails = array_column($result, 'email'); // extract email list
        print_r($emails);
        die();
    }

    function receive_lead_from_fb()
    {

        // Handle preflight OPTIONS request first
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
            http_response_code(200);
            exit();
        }

        // Actual CORS headers for other requests
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        // Now continue with your logic
        $input_data = json_decode(file_get_contents('php://input'), true);

        if (!$input_data) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => false, 'message' => 'Invalid JSON Data']));
        }

        $fields = [
            'user_name',
            'phone_number',
            'email',
            'date',
            'user_channel',
            'type',
            'status',
            'disposition',
            'created_at',
            'query',
            'remark',
            'time',
            'status_code',
            'last_query',
            'location_address',
            'ip_address',
            // These will be mapped to IDs:
            'property',
            'city',
            'state',
            'country',
            'template_name',
            'checkin_date',
            'checkout_date',
            'booking_date',
            'pax',
            'amount',

        ];


        $lead_data = [];
        foreach ($fields as $field) {
            $lead_data[$field] = isset($input_data[$field]) ? trim($input_data[$field]) : null;
        }

        //  print_r($lead_data);die();

        // Convert to IDs
        $lead_data['city'] = $this->LeadModel->get_city_id($lead_data['city']);
        $lead_data['state'] = $this->LeadModel->get_state_id($lead_data['state']);
        $lead_data['country'] = $this->LeadModel->get_country_id($lead_data['country']);
        $lead_data['property'] = $this->LeadModel->get_property_id_using_fb_page_id($lead_data['property']);

        $lead_data['type'] = $this->LeadModel->get_department_id_using_form_id($lead_data['type']); // "type" = department

        $department_id = $this->LeadModel->get_department_id_using_form_id($lead_data['type']);



        // Fallback safety
        if (empty($department_id)) {
            $department_id = 2; // default department
        }

        $lead_data['type'] = $department_id;

        // Fetch department config
        $department_data = $this->Common_model->getdata(
            'departments',
            ['department_id' => $department_id]
        );

        // Safety check
        $decimal_hours = (float) ($department_data->escalation_level_1 ?? 0);

        // If escalation hours missing, apply default
        // if ($decimal_hours <= 0) {
        //     $decimal_hours = 1; // ⏱ default 1 hour
        // }

        // Convert hours → minutes
        $minutes_to_add = (int) ($decimal_hours * 60);

        // Calculate follow-up time
        $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $current_time->modify("+{$minutes_to_add} minutes");

        $lead_data['esc_next_followup_at'] = $current_time->format('Y-m-d H:i:s');
        $lead_data['esc_follow_up_level'] = 1;


        // Optional: Validate required fields
        if (empty($lead_data['phone_number']) || empty($lead_data['property'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode(['status' => false, 'message' => 'Required fields missing']));
        }

        $lead_data['status'] = 'Open';

        $lead_data['created_at'] = date('Y-m-d H:i:s');


        $phone = $lead_data['phone_number'];
        $last10Digits = substr($phone, -10); // always extract last 10 digits
        $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));

        $existingLead = $this->db
            ->where("RIGHT(phone_number, 10) =", $last10Digits, false)
            ->where('created_at >=', $twoHoursAgo)
            ->where('status !=', 'Closed')
            ->order_by('id', 'DESC')
            ->get('leads')
            ->row();

        if (!empty($existingLead)) {

            // Do not change original creation timestamp
            unset($lead_data['created_at']);

            // Update existing lead with fresh details
            $this->db->where('id', $existingLead->id)
                ->update('leads', $lead_data);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => true, 'message' => 'Lead saved successfully']));
        }


        // Insert into DB
        $insert_id = $this->LeadModel->insert_lead($lead_data);

        $lead_data['created_at'] = date('Y-m-d H:i:s');


        $phone = $lead_data['phone_number'];

        // Check if any previous lead has reservation with revenue > 0
        $valuableGuest = $this->db
            ->select('id, disposition, amount')  // revenue_amount = your amount column
            ->from('leads')
            ->where('phone_number', $phone)
            ->where('LOWER(disposition)', 'reservation')   // case-insensitive match
            ->where('amount >', 0)                // has revenue
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if ($valuableGuest) {
            $IsvaluableGuest = true;
        } else {
            $IsvaluableGuest = false;
        }






        if ($insert_id) {

            $url = base_url("EmailWorker/sendLeadEmail/$insert_id/$IsvaluableGuest");

            // Fire & Forget HTTP Request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, false); // GET request
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // We don't care about the response
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 100); // Fast timeout
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100); // Don't wait for response
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1); // For timeout under 1s (only on Unix)
            curl_exec($ch);
            curl_close($ch);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => true, 'message' => 'Lead saved successfully']));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to save lead']));
        }
    }





    public function view_leads()
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }

        $data['all_assignable_users'] = $this->LeadModel->get_all_assignable_users('hotel_admin', '');


        $data['creators'] = $this->LeadModel->get_active_creators();

        $data['assigned_users'] = $this->LeadModel->get_active_assigned_users();








        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');

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
            'phone' => $this->input->get('phone')

        ];

        if (empty($filters['status']) && count(array_filter($filters)) === 0) {
            $filters['status'] = 'Open';
        }





        // Clean filters (remove empty values)
        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        // Fetch filtered leads
        $data['leads'] = $this->LeadModel->get_leads($activeFilters);




        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');

        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');

        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');

        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');
        $data['cities'] = $this->Common_model->getAllData('city', '');

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');


        // Load all data to send to view
        $data['lead_status_counts'] = $this->LeadModel->get_lead_counts_grouped_by_status($activeFilters);

        // echo "<pre>";
        // print_r($data['lead_status_counts']);
        // die();






        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/lead_report', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function customer_lead_history()
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }
        // Load models if not autoloaded
        $this->load->model('LeadModel');
        $this->load->model('Common_model');
        $phone = $this->uri->segment('2');

        // Get filter values from GET request
        $filters = [
            'city' => $this->input->get('city'),
            'property' => $this->input->get('property'),
            'department' => $this->input->get('department'),
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),

        ];





        // Clean filters (remove empty values)
        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        // Fetch filtered leads
        $data['leads'] = $this->LeadModel->get_leads_history($phone);

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['properties'] = $this->Common_model->getAllData('hotel_admin', '');
        $data['cities'] = $this->Common_model->getAllData('city', '');
        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');

        // Load all data to send to view
        $data['lead_status_counts'] = [
            'Open'        => $this->LeadModel->get_lead_count_by_status('Open'),
            'In Progress' => $this->LeadModel->get_lead_count_by_status('In Progress'),
            'On Hold'     => $this->LeadModel->get_lead_count_by_status('On Hold'),
            'Closed'      => $this->LeadModel->get_lead_count_by_status('Closed')
        ];


        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/lead_report', $data);
        $this->load->view('super_admin/include/footer');
    }


    public function getByHotel()
    {
        $hotel_id = $this->input->post('hotel_id');

        $restaurants = $this->db
            ->where('hotel_id', $hotel_id)
            ->where('status', 1)
            ->get('hotel_restaurants')
            ->result();

        $response = [
            'status' => 'success',
            'data'   => $restaurants
        ];

        // Send JSON safely in CI3
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

        return;
    }

    public function add_lead()
    {
        $data['leads'] = $this->LeadModel->get_leads();

        $property_id   = $this->input->get('property_id');
        $department_id = $this->input->get('department_id');

        $data['selected_property']   = $property_id;
        $data['selected_department'] = $department_id;


        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', '');

        $data['all_assignable_users'] = $this->LeadModel->get_all_assignable_users('hotel_admin', '');







        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');

        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/add_lead', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function insert_lead()
    {
        if ($this->input->method() === 'post') {



            $property = $this->input->post('property');
            $type = $this->input->post('type', true);

            // Combine both hotel and department data in a single go
            $hotel_data = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
            $department_data = $this->Common_model->getdata('departments', ['department_id' => $type]);





            $escalation_level_1 = $department_data->escalation_level_1;



            $decimal_hours = $escalation_level_1;
            $minutes_to_add = $decimal_hours * 60;

            // Get current time
            $current_time = new DateTime();

            // Add minutes
            $current_time->modify("+$minutes_to_add minutes");

            // Format follow-up time
            $follow_up_time = $current_time->format('Y-m-d H:i:s');
            $follow_up_level = 1;

            $assigned_role = $this->session->userdata('role_as');

            if ($assigned_role == 'super_admin') {
                $userId = $this->session->userdata('super_admin_session')['id'];
            } else if ($assigned_role == 'admin') {
                $userId = $this->session->userdata('hotel_admin_session')['id'];
            } else {
                $userId = $this->session->userdata('agent_session')['id'];
            }

            // Build lead data
            $leadData = [
                'user_name'       => $this->input->post('user_name', true),
                'phone_number'    => $this->input->post('phone_number', true),
                'email'           => $this->input->post('email', true),
                'date'            => $this->input->post('date', true),
                'time'            => $this->input->post('time', true),
                'user_channel'    => $this->input->post('user_channel', true),
                'property'        => $property,
                'type'            => $type,
                'status'          => $this->input->post('status', true),
                'disposition'     => $this->input->post('disposition', true),
                'created_at'      => date('Y-m-d H:i:s'),
                'query'           => $this->input->post('query', true),
                'remark'          => $this->input->post('remark', true),
                'lead_type'          => $this->input->post('lead_type', true),

                'city'            => $hotel_data->city_id ?? null,
                'esc_next_followup_at' => $follow_up_time,
                'esc_follow_up_level' => $follow_up_level,
                'template_name' => 'Phone',
                'created_by' => $userId,
                'creator_user_role' => $assigned_role


            ];





            $assigned_role = $this->session->userdata('role_as');

            if ($assigned_role == 'super_admin') {
                $userId = $this->session->userdata('super_admin_session')['id'];
            } else if ($assigned_role == 'admin') {
                $userId = $this->session->userdata('hotel_admin_session')['id'];
            } else {
                $userId = $this->session->userdata('agent_session')['id'];
            }

            $status = $this->input->post('status', true);
            $disposition = $this->input->post('disposition', true);
            $department = $this->input->post('leadDepartment', true);
            $tableIds = $this->input->post('table_id');
            $table_reservation_status = $this->input->post('table_reservation_status', true) ?: '';





            /** Time tracking */
            if ($status === 'Closed') {
                $leadData['completed_time'] = date('Y-m-d H:i:s');
                $leadData['responded_time'] = date('Y-m-d H:i:s');
            } else {
                $leadData['responded_time'] = date('Y-m-d H:i:s');
            }

            $department  = $department_data->department_name;
            $normalized_department = strtolower(trim($department));

            $leadData['promotional_offers'] =  $this->input->post('promotional_offers');

            $leadData['reason'] =  $this->input->post('reason');

            $leadData['purpose'] =  $this->input->post('purpose');
            $myCloudBookingResponse = null;













            /** Reservation Closed */
            if (
                ($disposition === 'Quotation Sent')
                && strtolower($status) === 'in progress'
            ) {
                $department = strtolower($department);

                if ($department === 'rooms') {



                    $dates       = $this->input->post('rate_date');   // dd-mm-YYYY
                    $rate_types  = $this->input->post('rate_type');
                    $room_prices = $this->input->post('room_price');
                    $leadData['meal_plan'] =  $this->input->post('meal_plan');;



                    $room_rates = [];

                    if (!empty($dates)) {
                        foreach ($dates as $key => $dt) {

                            // Convert dd-mm-YYYY → YYYY-mm-dd
                            $date_parts = explode('-', $dt);
                            $formatted_date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];

                            $room_rates[] = [
                                "date"           => $formatted_date,
                                "rate_type_code" => isset($rate_types[$key]) ? $rate_types[$key] : "",
                                "room_price"     => isset($room_prices[$key]) ? $room_prices[$key] : "0"
                            ];
                        }
                    }

                    // Now you can send this array to your model or API
                    $insert_data_room_rates = $room_rates;

                    $confirmationNumber = $this->generateConfirmationNumber();

                    $hotel_code_Data = $this->LeadModel->getHotelCodeByProperty($property);
                    $hotel_code = $hotel_code_Data->hotel_code;

                    $requestBody = [
                        "chain_code"          => "E0701",
                        "hotel_code"          => "E0701",
                        "confirmation_number" => $confirmationNumber,
                        "confirmation_type"   => "EXT",
                        "booking_id"          => "",
                        "booking_status"      => "CREATE",

                        "bookings" => [
                            [
                                "booking_sequence_no"  => "1",
                                "date_arrive"          => $this->input->post('checkin_date'),
                                "time_arrive"          => $this->input->post('checkin_time'),
                                "date_depart"          => $this->input->post('checkout_date'),
                                "time_depart"          => $this->input->post('checkout_time'),
                                "adults"               => $this->input->post('adults'),
                                "youths"               => $this->input->post('adults'),
                                "kids"                 => $this->input->post('kids'),
                                "room_type_code"       => $this->input->post('roomtype'),
                                "room_number"          => "",
                                "number_of_rooms"      => $this->input->post('number_of_rooms'),
                                "currency"             => "INR",
                                "rate_type_code"       => "CP",
                                "channel_code"         => "BWEB",
                                "market_segment_code"  => "",
                                "business_source_code" => "",
                                "release_date"         => "1900-01-01",
                                "remarks"              => "Booking From LMS",
                                "billing_instructions" => "Bill To Campany 1",

                                "profile" => [
                                    "title"                    => "",
                                    "name_first"               => $this->input->post('user_name'),
                                    "name_last"                => $this->input->post('user_name'),
                                    "email_id"                 => $this->input->post('email'),
                                    "contact_number"           => $this->input->post('phone_number'),
                                    "address_1"                => "",
                                    "address_2"                => "",
                                    "address_3"                => "",
                                    "city"                     => "",
                                    "state"                    => "",
                                    "zip"                      => "",
                                    "country"                  => "",
                                    "nationality_code"         => "",
                                    "gender"                   => "",
                                    "date_of_birth"            => "",
                                    "designation"              => "",
                                    "profile_image"            => "",
                                    "identity_type"            => "",
                                    "identity_number"          => "",
                                    "identity_date_issue"      => "",
                                    "identity_date_expiry"     => "",
                                    "identity_issue_authority" => "",
                                    "identity_image"           => "",
                                    "visa_type"                => "",
                                    "visa_number"              => "",
                                    "visa_date_issue"          => "",
                                    "visa_date_expiry"         => ""
                                ],

                                "room_rates" => $insert_data_room_rates,

                                "chargeable_services" => [],

                                "accompanying_guests" => [
                                    [
                                        "guest_sequence_no" => "1",
                                        "category"          => "Adult",
                                        "profile" => [
                                            "title"                    => "",
                                            "name_first"               => "",
                                            "name_last"                => "ACC. EXTERNAL 1 ",
                                            "email_id"                 => "test@xyz.com",
                                            "nationality_code"         => "",
                                            "date_of_birth"            => "2019-11-24",
                                            "age"                      => "1",
                                            "profile_image"            => "",
                                            "identity_type"            => "N",
                                            "identity_number"          => "",
                                            "identity_date_issue"      => "",
                                            "identity_date_expiry"     => "",
                                            "identity_issue_authority" => "",
                                            "identity_image"           => "",
                                            "visa_type"                => "",
                                            "visa_number"              => "",
                                            "visa_date_issue"          => "",
                                            "visa_date_expiry"         => ""
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ];

                    if (!empty($hotel_code)) {

                        $myCloudResponse = $this->send_booking_to_mycloud($requestBody);

                        $myCloudResponse = json_decode($myCloudResponse['response'], true); // convert to 

                        if ($myCloudResponse['status_code'] == "200") {

                            $leadData['confirmation_number'] = $myCloudResponse['bookings'][0]['pms_confirmation_number'];

                            $myCloudBookingResponse = $myCloudResponse;
                            $leadData['booking_id'] = $myCloudResponse['bookings'][0]['booking_id'];
                        } else {

                            echo json_encode([
                                'status' => false,
                                'error_code' => $myCloudResponse['error']['ErrorCode'],
                                'error_message' => $myCloudResponse['error']['ErrorDescription'],
                                'csrfHash' => $this->security->get_csrf_hash()
                            ]);
                            return;
                        }
                    }







                    $leadData['completed_time'] = date('Y-m-d H:i:s');
                    $leadData['checkin_date'] = $this->input->post('checkin_date');
                    $leadData['checkin_time'] = $this->input->post('checkin_time');
                    $leadData['checkout_date'] = $this->input->post('checkout_date');
                    $leadData['checkout_time'] = $this->input->post('checkout_time');
                    $leadData['roomtype'] = $this->input->post('roomtype');
                    $leadData['number_of_rooms'] = $this->input->post('number_of_rooms');
                    $leadData['pax'] = $this->input->post('pax');
                    $leadData['adults'] = $this->input->post('adults');
                    $leadData['kids'] = $this->input->post('kids');
                    $leadData['meal_plan'] = $this->input->post('meal_plan');
                    $leadData['revenue_fnb']       = $this->input->post('revenue_fnb');

                    $leadData['revenue_other']       = $this->input->post('revenue_other');

                    $leadData['revenue_room']       = $this->input->post('revenue_room');
                    $leadData['amount']       = $this->input->post('amount');
                }

                if ($department === 'wedding') {


                    $leadData['completed_time'] = date('Y-m-d H:i:s');
                    $leadData['checkin_date'] = $this->input->post('checkin_date');
                    $leadData['checkin_time'] = $this->input->post('checkin_time');
                    $leadData['checkout_date'] = $this->input->post('checkout_date');
                    $leadData['checkout_time'] = $this->input->post('checkout_time');
                    $leadData['roomtype'] = $this->input->post('roomtype');
                    $leadData['number_of_rooms'] = $this->input->post('number_of_rooms');
                    $leadData['pax'] = $this->input->post('pax');
                    $leadData['adults'] = $this->input->post('adults');
                    $leadData['kids'] = $this->input->post('kids');
                    $leadData['meal_plan'] = $this->input->post('meal_plan');
                    $leadData['revenue_fnb']       = $this->input->post('revenue_fnb');

                    $leadData['revenue_other']       = $this->input->post('revenue_other');

                    $leadData['revenue_room']       = $this->input->post('revenue_room');
                    $leadData['amount']       = $this->input->post('amount');

                    $leadData['booking_date']  = $this->input->post('booking_date');
                    $leadData['booking_month']  = $this->input->post('booking_month');

                    $leadData['pax']           = $this->input->post('pax');
                    $leadData['amount']        = $this->input->post('amount');
                    $leadData['sitting_style'] = $this->input->post('sitting_style');
                    $leadData['audio_visual'] = $this->input->post('audio_visual');
                    $leadData['btr'] = $this->input->post('btr');
                    $leadData['banquet_id'] = $this->input->post('banquet_id');
                }

                if ($department === 'restaurants') {
                    $leadData['booking_date'] = $this->input->post('booking_date');
                    $leadData['booking_month']  = $this->input->post('booking_month');

                    $leadData['pax']          = $this->input->post('pax');
                    $leadData['amount']       = $this->input->post('amount');
                    $leadData['arrival_time']       = $this->input->post('arrival_time');
                    $leadData['restaurant_id']       = $this->input->post('restaurant_id');
                    $leadData['slot_type_id']       = $this->input->post('slot_type_id');
                    $leadData['special_occasion']       = $this->input->post('special_occasion');
                    $leadData['special_request']       = $this->input->post('special_request');
                    $leadData['slot_type_id']      = $this->input->post('slot_type_id');
                    $leadData['time_slot_id']      = $this->input->post('time_slot_id');

                    $leadData['restaurant_id']     = $this->input->post('restaurant_id');
                    $leadData['table_category_id'] = $this->input->post('table_category_id');
                    $leadData['table_reservation_status'] = $table_reservation_status;

                    // Convert table_id array to comma-separated string
                    if (is_array($tableIds) && count($tableIds) > 0) {
                        $leadData['table_id'] = implode(',', $tableIds);
                    } else {
                        $leadData['table_id'] = $tableIds;
                    }

                    $leadData['special_request']   = $this->input->post('special_request');
                }

                if ($department === 'banquets') {
                    $leadData['booking_date']  = $this->input->post('booking_date');
                    $leadData['booking_month']  = $this->input->post('booking_month');

                    $leadData['pax']           = $this->input->post('pax');
                    $leadData['amount']        = $this->input->post('amount');
                    $leadData['sitting_style'] = $this->input->post('sitting_style');
                    $leadData['audio_visual'] = $this->input->post('audio_visual');
                    $leadData['btr'] = $this->input->post('btr');
                    $leadData['banquet_id'] = $this->input->post('banquet_id');
                }


                if ($department === 'spa') {
                    $leadData['booking_date']  = $this->input->post('booking_date');
                    $leadData['booking_month']  = $this->input->post('booking_month');
                    $leadData['pax']           = $this->input->post('pax');
                    $leadData['amount']        = $this->input->post('amount');
                    $leadData['special_request']       = $this->input->post('special_request');
                }
            } else {
                $leadData['amount']       = 0;
            }

            /** Shopping - Follow up - In Progress */

            $fields = [
                'booking_enquiry_date',
                'booking_date',
                'followup_date',
                'second_followup_date',
                'followup_remark',
                'arrival_time',
                'checkin_date',
                'checkout_date',
                'special_request'
            ];

            foreach ($fields as $field) {
                $value = $this->input->post($field);

                if ($value !== null && $value !== '') {
                    $leadData[$field] = $value;
                }
            }








            // find last lead created in last 2 hours with same phone number
            $phone = $leadData['phone_number'];
            $last10Digits = substr($phone, -10); // always extract last 10 digits
            $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));

            $existingLead = $this->db
                ->where("RIGHT(phone_number, 10) =", $last10Digits, false)
                ->where('created_at >=', $twoHoursAgo)

                ->order_by('id', 'DESC')
                ->get('leads')
                ->row();

            if (!empty($existingLead)) {

                // Do not change original creation timestamp
                unset($leadData['created_at']);

                // Update existing lead with fresh details
                $this->db->where('id', $existingLead->id)
                    ->update('leads', $leadData);

                $response = [
                    'status'    => true,
                    'message'   => 'Duplicate detected: Existing lead updated successfully.',
                    'duplicate' => true,
                    'csrfHash'  => $this->security->get_csrf_hash()
                ];

                // Send JSON safely in CI3
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

                return;
            }



            $assigned_person_user_role = $this->input->post('assigned_person_user_role');
            $assigned_person_email = $this->input->post('assigned_person_email');
            $assigned_to = $this->input->post('assigned_to');

            if ($assigned_to != '') {
                $leadData['is_assigned'] = 1;
                $leadData['assigned_to'] = $assigned_to;
                $leadData['assigned_person_user_role'] = $assigned_person_user_role;
                $leadData['assigned_person_email'] = $assigned_person_email;
            }




            // Insert into DB - single lead with comma-separated table IDs if multiple
            $insert_id = $this->LeadModel->insert_lead($leadData);





            $leadData['created_at'] = date('Y-m-d H:i:s');


            $phone = $leadData['phone_number'];

            // Check if any previous lead has reservation with revenue > 0
            $valuableGuest = $this->db
                ->select('id, disposition, amount')  // revenue_amount = your amount column
                ->from('leads')
                ->where('phone_number', $phone)
                ->where('LOWER(disposition)', 'reservation')   // case-insensitive match
                ->where('amount >', 0)                // has revenue
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()
                ->row();

            if ($valuableGuest) {
                $IsvaluableGuest = true;
            } else {
                $IsvaluableGuest = false;
            }






            if ($insert_id) {


                if (!empty($myCloudBookingResponse)) {
                    $this->saveLeadBookings($insert_id, $myCloudBookingResponse);
                }

                $isRestaurantLead = in_array($normalized_department, ['restaurant', 'restaurants'], true);

                if ($isRestaurantLead && $table_reservation_status == 'Reserved') {
                    $this->send_restaurant_booking_confirmation($insert_id);
                }

                /*
    |--------------------------------------------------------------------------
    | Completed → Feedback Link
    |--------------------------------------------------------------------------
    */

                if ($isRestaurantLead && $table_reservation_status == 'Completed') {
                    $this->send_restaurant_feedback_link($insert_id);
                }



                $url = base_url("EmailWorker/sendLeadEmailToassigned_person_email/$insert_id/$IsvaluableGuest");


                $this->sendwhatsappMessageToAgnent($insert_id);


                // Fire & Forget HTTP Request
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, false); // GET request
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // We don't care about the response
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 100); // Fast timeout
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100); // Don't wait for response
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1); // For timeout under 1s (only on Unix)
                curl_exec($ch);
                curl_close($ch);
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        'status'  => false,
                        'message' => 'Failed to insert lead.',
                        'csrfHash' => $this->security->get_csrf_hash()
                    ]));
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => 'Lead created successfully.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        } else {
            show_404();
        }
    }




    public function send_restaurant_booking_confirmation($lead_id)
    {
        $lead = $this->db
            ->select('
            l.*,
            h.hotel_name,
            r.restaurant_name
        ')
            ->from('leads l')
            ->join('hotel_admin h', 'h.hotel_id = l.property', 'left')
            ->join('hotel_restaurants r', 'r.id = l.restaurant_id', 'left')
            ->where('l.id', $lead_id)
            ->get()
            ->row();

        if (empty($lead)) {
            return false;
        }

        $payload = array(
            "recipients" => array(
                array(
                    "phone" => '91' . substr(
                        preg_replace('/\D/', '', $lead->phone_number),
                        -10
                    ),

                    "guest_name"       => $lead->user_name,
                    "hotel_name"       => $lead->hotel_name,
                    "restaurant_name"  => $lead->restaurant_name,
                    "no_of_guests"     => (string) $lead->pax,
                    "booking_date"     => !empty($lead->booking_date)
                        ? date('d-m-Y', strtotime($lead->booking_date))
                        : '',
                    "booking_time"     => !empty($lead->arrival_time)
                        ? date('g:iA', strtotime($lead->arrival_time))
                        : ''
                )
            )
        );




        return $this->send_booking_confirmation_whatsapp($payload);
    }



    public function send_restaurant_feedback_link($lead_id)
    {
        $lead = $this->db
            ->select('
            l.*,
            h.hotel_name,
            r.restaurant_name
        ')
            ->from('leads l')
            ->join('hotel_admin h', 'h.hotel_id = l.property', 'left')
            ->join('hotel_restaurants r', 'r.id = l.restaurant_id', 'left')
            ->where('l.id', $lead_id)
            ->get()
            ->row();

        if (empty($lead)) {
            return false;
        }


        $payload = array(
            "res_name"       => $lead->restaurant_name,
            "reservation_id" => !empty($lead->reservation_id)
                ? $lead->reservation_id
                : "optional",

            "res_id"         => (string) $lead->restaurant_id,
            "guest_name"     => $lead->user_name,

            "mobile"         => substr(
                preg_replace('/\D/', '', $lead->phone_number),
                -10
            ),

            "lead_id"        => (string) $lead->id,
            "property_id"    => (string) $lead->property,
            "hotel_name"     => $lead->hotel_name,
            "property_name"  => $lead->hotel_name,

            "type"           => "room"
        );

        return $this->send_feedback_whatsapp($payload);
    }


    private function send_booking_confirmation_whatsapp($payload)
    {
        $url = 'https://api.wisemelon.ai/api/trigger/invoke/6a164ce9019eb5986ffa18f6';



        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            )
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function send_feedback_whatsapp($payload)
    {
        $url = 'https://api.wisemelon.ai/api/trigger/invoke/6a0542dbef712b98bb2207a2';



        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            )
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    public function sendwhatsappMessageToAgnent($leadID = 0)
    {
        if (empty($leadID)) {
            show_404();
        }

        $this->load->model('Comman_model');

        // Get Lead Data
        $leadData = $this->Comman_model->getdata('leads', ['id' => $leadID]);


        if (!$leadData) {
            log_message('error', 'Lead not found for ID: ' . $leadID);
            show_404();
        }

        // Get Hotel & Department Data
        $hotel      = $this->Comman_model->getdata('hotel_admin', ['hotel_id' => $leadData->property]);
        $department = $this->Comman_model->getdata('departments', ['department_id' => $leadData->type]);

        $hotel_id       = $leadData->property;
        $department_id  = $leadData->type;

        $property_name   = !empty($hotel->hotel_name) ? $hotel->hotel_name : 'N/A';
        $department_name = !empty($department->department_name) ? $department->department_name : 'N/A';

        $guest_name   = !empty($leadData->user_name) ? $leadData->user_name : 'Guest';
        $guest_phone  = !empty($leadData->phone_number) ? $leadData->phone_number : '';
        $guest_query  = !empty($leadData->query) ? $leadData->query : 'No Query';

        // Get Assigned Agents
        $this->db->select('staff_members.name, staff_members.phone');
        $this->db->from('staff_members');
        $this->db->join(
            'staff_hotel_department_mapping',
            'staff_members.id = staff_hotel_department_mapping.staff_id'
        );
        $this->db->where('staff_hotel_department_mapping.level', 1);
        $this->db->where('staff_hotel_department_mapping.hotel_id', $hotel_id);
        $this->db->where('staff_hotel_department_mapping.department_id', $department_id);

        $query  = $this->db->get();
        $result = $query->result();

        if (empty($result)) {
            log_message('error', 'No Agent Found For Lead ID: ' . $leadID);
            return false;
        }

        foreach ($result as $each_agent) {

            $agent_name  = $each_agent->name;
            $agent_phone = $each_agent->phone;

            $postData = [
                "agent_name"       => $agent_name,
                "agent_phone"      => $agent_phone,
                "department_name"  => $department_name,
                "property_name"    => $property_name,
                "guest_name"       => $guest_name,
                "guest_phone"      => $guest_phone,
                "guest__query"     => $guest_query
            ];




            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.wisemelon.ai/api/trigger/invoke/69edbc8e654385236649a5af",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ],
                CURLOPT_TIMEOUT => 30
            ]);

            $response = curl_exec($ch);
            $error    = curl_error($ch);

            curl_close($ch);

            if ($error) {
                log_message('error', 'WhatsApp API Error: ' . $error);
            } else {
                log_message('error', 'WhatsApp Sent To: ' . $agent_phone . ' Response: ' . $response);
            }
        }

        return true;
    }




    public function insert_lead_google_business()
    {
        if ($this->input->method() === 'post') {

            $property = $this->input->post('property');
            $type = $this->input->post('type');

            $property = $this->Comman_model->get_id_by_md5($property, 'hotel_admin', 'hotel_id');
            $type = $this->Comman_model->get_id_by_md5($type, 'departments', 'department_id');

            // Combine both hotel and department data in a single go
            $hotel_data = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
            $department_data = $this->Common_model->getdata('departments', ['department_id' => $type]);

            // Build lead data
            $leadData = [
                'user_name'       => $this->input->post('user_name', true),
                'phone_number'    => $this->input->post('phone_number', true),
                'email'           => $this->input->post('email', true),
                'date'            => $this->input->post('date', true),
                'time'            => $this->input->post('time', true),
                'user_channel'    => $this->input->post('user_channel', true),
                'property'        => $property,
                'type'            => $type,
                'status'          => $this->input->post('status', true),
                'disposition'     => $this->input->post('disposition', true),
                'created_at'      => date('Y-m-d H:i:s'),
                'query'           => $this->input->post('query', true),
                'remark'          => $this->input->post('remark', true),
                'city'            => $hotel_data->city_id ?? null
            ];

            // Insert lead
            $leadID = $this->Comman_model->insertData('leads', $leadData);

            if ($leadID) {
                // Prepare email (do not send yet)
                $hotel_name = $hotel_data->hotel_name ?? 'N/A';
                $department_name = $department_data->department_name ?? 'N/A';

                $subject = 'New Lead Registered: ' . $leadData['user_name'] . ' from ' . $leadData['user_channel'] . ' - ' . date('d M Y', strtotime($leadData['created_at']));

                $message = '
                    <div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; background-color: #f9f9f9;">
                        <h2 style="color: #2c3e50;">📥 New Lead Notification</h2>
                        <p>A new lead has been registered with the following details:</p>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                            <tr><td><strong>Name</strong></td><td>' . htmlspecialchars($leadData['user_name']) . '</td></tr>
                            <tr><td><strong>Phone</strong></td><td>' . htmlspecialchars($leadData['phone_number']) . '</td></tr>
                            <tr><td><strong>Email</strong></td><td>' . htmlspecialchars($leadData['email']) . '</td></tr>
                            <tr><td><strong>Query</strong></td><td>' . nl2br(htmlspecialchars($leadData['query'])) . '</td></tr>
                            <tr><td><strong>Property</strong></td><td>' . htmlspecialchars($hotel_name) . '</td></tr>
                            <tr><td><strong>Department</strong></td><td>' . htmlspecialchars($department_name) . '</td></tr>
                            <tr><td><strong>Created At</strong></td><td>' . date('d M Y, h:i A', strtotime($leadData['created_at'])) . '</td></tr>
                        </table>
                        <p style="margin-top: 20px; font-size: 13px; color: #888;">This is an automated message generated by the lead management system.</p>
                    </div>';

                // // Send mail in background if possible (or defer using queue/crontab)
                // ignore_user_abort(true);
                // fastcgi_finish_request(); // End response here for faster UX

                // $this->load->model('Mail_model');
                // $this->Mail_model->sendMailSMTP_uv('Umesh', 'umeshvishwakarma6192@gmail.com', $subject, $message);
            }

            $response = [
                'status'  => true,
                'message' => 'Lead created successfully.'
            ];

            // Send JSON safely in CI3
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

            return;
        } else {
            show_404();
        }
    }


    public function download_sample_file()
    {
        $this->load->helper('download');

        $file_path = FCPATH . 'assets/sample_leads_import.xlsx';

        if (file_exists($file_path)) {
            $data = file_get_contents($file_path); // Read the file content
            $name = 'sample_leads_import.xlsx';    // File name for download

            force_download($name, $data);
        } else {
            show_error('The requested file does not exist.', 404);
        }
    }








    public function import_leads_data()
    {
        $this->load->model('LeadModel');

        $upload_dir = FCPATH . "uploads/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

            $allowed_types = ['xls', 'xlsx'];
            if (!in_array($file_extension, $allowed_types)) {
                echo "Invalid file type. Only XLS and XLSX files are allowed.";
                return;
            }

            $new_file_name = time() . '_' . $file_name;
            $target_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $target_path)) {
                try {
                    $spreadsheet = IOFactory::load($target_path);
                } catch (Exception $e) {
                    echo "Error loading file: " . $e->getMessage();
                    return;
                }

                foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                    $highestRow = $sheet->getHighestRow();

                    for ($row = 2; $row <= $highestRow; $row++) {
                        $getVal = function ($colIndex) use ($sheet, $row) {
                            $cellAddress = Coordinate::stringFromColumnIndex($colIndex) . $row;
                            return $sheet->getCell($cellAddress)->getValue();
                        };

                        $lead_data = [
                            'user_name' => $getVal(2),
                            'phone_number' => $getVal(3),
                            'email' => $getVal(4),
                            'date' => $getVal(5),
                            'user_channel' => $getVal(6),
                            'property' => $this->LeadModel->get_property_id($getVal(7)),
                            'type' => $this->LeadModel->get_department_id($getVal(8)),
                            'status' => $getVal(9),
                            'disposition' => $getVal(10),
                            'created_at' => $getVal(11),
                            'query' => $getVal(12),
                            'remark' => $getVal(13),
                            'city' => $this->LeadModel->get_city_id($getVal(14)),
                            'state' => $this->LeadModel->get_state_id($getVal(15)),
                            'country' => $this->LeadModel->get_country_id($getVal(16)),
                            'time' => $getVal(17),
                            'status_code' => $getVal(18),
                            'last_query' => $getVal(19),
                            'location_address' => $getVal(20),
                            'ip_address' => $getVal(21)
                        ];


                        $inserted = $this->LeadModel->insert_lead($lead_data);

                        if (!$inserted) {
                            error_log("Row $row: Failed to insert lead.");
                        }
                    }
                }

                redirect('manage-leads');
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "No file uploaded or file upload error: " . $_FILES['file']['error'];
        }
    }








    public function get_call_history()
    {
        $leadId = $this->input->post('lead_id');

        if (!$leadId) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing lead_id',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Get lead details to fetch correlation_ids
        $this->load->model('Common_model');


        // Fetch calls for all correlation IDs
        $this->db->where('leadid', $leadId);
        $query = $this->db->get('calls');
        $callHistory = $query->result();

        // Send JSON safely in CI3
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($callHistory, JSON_UNESCAPED_UNICODE));

        return;
    }


    public function get_lead_details()
    {
        $leadId = $this->input->post('lead_id');

        if (!$leadId) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing lead_id',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Load model
        $this->load->model('Common_model');

        // Logged-in user details
        $logged_in_role = $this->session->userdata('role_as'); // e.g. 'admin', 'agent', 'manager'

        // Fetch lead
        $this->db->where('id', $leadId);
        $query = $this->db->get('leads');
        $lead = $query->row();

        if (!$lead) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lead not found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        /*
        CONDITIONS:
        1. If user is AGENT:
            - If is_assigned = 1 AND assigned_to != logged_in_user_id → Block
            - Else → allow
    */

        if ($this->session->userdata('user_role') != 1) {

            if ($this->session->userdata('agent_session')) {
                $logged_in_user_id = $this->session->userdata('agent_session')['id'];
                $logged_in_role = 'agent';
            } elseif ($this->session->userdata('super_admin_session')) {
                $logged_in_user_id = $this->session->userdata('super_admin_session')['id'];
                $logged_in_role = 'super_admin';
            } elseif ($this->session->userdata('hotel_admin_session')) {
                $logged_in_user_id = $this->session->userdata('hotel_admin_session')['id'];
                $logged_in_role = 'admin';
            } else {
                $response = [
                    'status'  => 'error',
                    'message' => 'User session not found.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ];

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            }

            // Main condition
            if (
                $lead->is_assigned == 1 &&
                $lead->assigned_to != $logged_in_user_id &&
                $logged_in_role == $lead->assigned_person_user_role
            ) {
                $response = [
                    'status'  => 'error',
                    'message' => 'This lead is already assigned to another Person. You do not have access to update details.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ];

                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

                return;
            }
        }




        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'data'   => $lead,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));

        return;
    }


    public function get_lead_details_new()
    {
        $leadId = $this->input->post('lead_id');

        if (!$leadId) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing lead_id',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Load model
        $this->load->model('Leadmodel');

        // Fetch lead with hotel name and department name
        $leadDetails = $this->Leadmodel->get_lead_by_id_with_joins($leadId);

        if ($leadDetails) {

            // Convert DB object to array (VERY important)
            $leadData = (array) $leadDetails;

            // Add assigned_to_name safely
            $leadData['assigned_to_name'] = $this->Leadmodel->get_assigned_to_name($leadDetails);
            $leadData['created_by_name'] = $this->Leadmodel->get_created_to_name($leadDetails);


            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => 'success',
                    'data'   => $leadData,
                    'csrfHash' => $this->security->get_csrf_hash()
                ], JSON_UNESCAPED_UNICODE));
        } else {

            $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status'  => 'error',
                    'message' => 'Lead not found',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        return;
    }






    public function update_status()
    {
        $leadId = $this->input->post('lead_id');
        $status = $this->input->post('status');
        $remark = $this->input->post('remark');
        $disposition = $this->input->post('disposition');
        $department = $this->input->post('leaddepartment'); // Make sure it's included in the form as hidden field

        $assigned_role = $this->session->userdata('role_as');

        if ($assigned_role == 'super_admin') {
            $userId = $this->session->userdata('super_admin_session')['id'];
        } else if ($assigned_role == 'admin') {
            $userId = $this->session->userdata('hotel_admin_session')['id'];
        } else {
            $userId = $this->session->userdata('agent_session')['id'];
        }

        // Base update data
        $data = [
            'status' => $status,
            'remark' => $remark,
            'is_assigned' => 1,
            'disposition' => $disposition,
            'assigned_person_user_role' => $assigned_role,
            'assigned_to' => $userId,
            'updated_on' => date('Y-m-d H:i:s'),
        ];

        // Time tracking
        if ($status === 'Closed') {
            $data['completed_time'] = date('Y-m-d H:i:s');
        } else {
            $data['responded_time'] = date('Y-m-d H:i:s');
        }

        // Additional fields for Reservation Closed
        if ($disposition === 'Reservation' && strtolower($status) === 'closed') {
            if ($department === 'Rooms') {
                $data['checkin_date'] = $this->input->post('checkin_date');
                $data['checkin_time'] = $this->input->post('checkin_time');
                $data['checkout_date'] = $this->input->post('checkout_date');
                $data['checkout_time'] = $this->input->post('checkout_time');
                $data['roomtype'] = $this->input->post('roomtype');
                $data['number_of_rooms'] = $this->input->post('number_of_rooms');
                $data['pax'] = $this->input->post('pax');
                $data['adults'] = $this->input->post('adults');
                $data['kids'] = $this->input->post('kids');


                $requestBody = [
                    "chain_code"          => "E0701",
                    "hotel_code"          => "E0701",
                    "confirmation_number" => "testbyDev",
                    "confirmation_type"   => "EXT",
                    "booking_id"          => "",
                    "booking_status"      => "CREATE",

                    "bookings" => [
                        [
                            "booking_sequence_no"  => "1",
                            "date_arrive"          => $this->input->post('checkin_date'),
                            "time_arrive"          => $this->input->post('checkin_time'),
                            "date_depart"          => $this->input->post('checkout_date'),
                            "time_depart"          => $this->input->post('checkout_time'),
                            "adults"               => $this->input->post('adults'),
                            "youths"               => $this->input->post('adults'),
                            "kids"                 => $this->input->post('kids'),
                            "room_type_code"       => $this->input->post('roomtype'),
                            "room_number"          => "",
                            "number_of_rooms"      => $this->input->post('number_of_rooms'),
                            "currency"             => "INR",
                            "rate_type_code"       => "RACK",
                            "channel_code"         => "LIFH",
                            "market_segment_code"  => "",
                            "business_source_code" => "LIFH",
                            "release_date"         => "1900-01-01",
                            "remarks"              => "Booking From LMS",
                            "billing_instructions" => "Bill To Campany 1",

                            "profile" => [
                                "title"                    => "",
                                "name_first"               => "Singh",
                                "name_last"                => "EXTERNAL 1",
                                "email_id"                 => "",
                                "contact_number"           => "",
                                "address_1"                => "",
                                "address_2"                => "",
                                "address_3"                => "",
                                "city"                     => "",
                                "state"                    => "",
                                "zip"                      => "",
                                "country"                  => "",
                                "nationality_code"         => "",
                                "gender"                   => "",
                                "date_of_birth"            => "",
                                "designation"              => "",
                                "profile_image"            => "",
                                "identity_type"            => "",
                                "identity_number"          => "",
                                "identity_date_issue"      => "",
                                "identity_date_expiry"     => "",
                                "identity_issue_authority" => "",
                                "identity_image"           => "",
                                "visa_type"                => "",
                                "visa_number"              => "",
                                "visa_date_issue"          => "",
                                "visa_date_expiry"         => ""
                            ],

                            "room_rates" => [
                                [
                                    "date"           => "2020-03-01",
                                    "rate_type_code" => "RACK",
                                    "room_price"     => "3200.0000"
                                ],
                                [
                                    "date"           => "2020-03-02",
                                    "rate_type_code" => "RACK",
                                    "room_price"     => "3400.0000"
                                ],
                                [
                                    "date"           => "2020-03-03",
                                    "rate_type_code" => "RACK",
                                    "room_price"     => "3600.0000"
                                ]
                            ],

                            "chargeable_services" => [],

                            "accompanying_guests" => [
                                [
                                    "guest_sequence_no" => "1",
                                    "category"          => "Adult",
                                    "profile" => [
                                        "title"                    => "",
                                        "name_first"               => "",
                                        "name_last"                => "ACC. EXTERNAL 1 ",
                                        "email_id"                 => "test@xyz.com",
                                        "nationality_code"         => "",
                                        "date_of_birth"            => "2019-11-24",
                                        "age"                      => "1",
                                        "profile_image"            => "",
                                        "identity_type"            => "N",
                                        "identity_number"          => "",
                                        "identity_date_issue"      => "",
                                        "identity_date_expiry"     => "",
                                        "identity_issue_authority" => "",
                                        "identity_image"           => "",
                                        "visa_type"                => "",
                                        "visa_number"              => "",
                                        "visa_date_issue"          => "",
                                        "visa_date_expiry"         => ""
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }

            if ($department === 'Restaurants') {
                $data['booking_date'] = $this->input->post('booking_date');
                $data['pax'] = $this->input->post('pax');
                $data['amount'] = $this->input->post('amount');
                $data['fnb_email'] = $this->input->post('fnb_email');
            }

            if ($department === 'Banquets') {
                $data['booking_date'] = $this->input->post('booking_date');
                $data['pax'] = $this->input->post('pax');
                $data['amount'] = $this->input->post('amount');
                $data['banquet_email'] = $this->input->post('banquet_email');
            }
        }

        // Shopping - Follow up - In Progress
        if (strpos(strtolower($disposition), 'shopping - follow up') !== false && strtolower($status) === 'in progress') {
            $data['booking_enquiry_date'] = $this->input->post('booking_enquiry_date');
            $data['followup_date'] = $this->input->post('followup_date');
            $data['second_followup_date'] = $this->input->post('second_followup_date');
            $data['followup_remark'] = $this->input->post('followup_remark');

            if ($department === 'Banquets') {
                $data['transfer_to_manager'] = $this->input->post('transfer_to_manager');
            }
        }

        // Update leads table
        $updated = $this->Comman_model->UpdateRecord('leads', $data, ['id' => $leadId]);

        // Insert into lead_status_history


        $assigned_role = $this->session->userdata('role_as');

        if ($assigned_role == 'super_admin') {
            $userId = $this->session->userdata('super_admin_session')['id'];
        } else if ($assigned_role == 'admin') {
            $userId = $this->session->userdata('hotel_admin_session')['id'];
        } else {
            $userId = $this->session->userdata('agent_session')['id'];
        }


        if ($updated) {
            $historyData = [
                'lead_id' => $leadId,
                'status' => $status,
                'remark' => $remark,
                'changed_by' => $assigned_role,
                'changed_at' => date('Y-m-d H:i:s'),
                'assigned_to' => $userId
            ];
            $this->db->insert('lead_status_history', $historyData);
            echo "success";
        } else {
            echo "error";
        }
    }


    public function get_status_history()
    {
        $leadId = $this->input->post('lead_id');

        $this->db->where('lead_id', $leadId);
        $this->db->order_by('changed_at', 'ASC');
        $history = $this->db->get('lead_status_history')->result();

        $data = [];

        foreach ($history as $row) {

            $changed_by_name = 'Unknown';

            // changed_by = user id
            // changed_by_role = admin / agent / super_admin

            if ($row->changed_by == 'admin') {

                $user = $this->db->select('name')
                    ->from('hotel_admins')
                    ->where('id', $row->assigned_to)
                    ->get()
                    ->row();

                if ($user) {
                    $changed_by_name = $user->name;
                }
            } elseif ($row->changed_by == 'agent') {

                $user = $this->db->select('name')
                    ->from('staff_members')
                    ->where('id', $row->assigned_to)
                    ->get()
                    ->row();

                if ($user) {
                    $changed_by_name = $user->name;
                }
            } elseif ($row->changed_by == 'super_admin') {

                $user = $this->db->select('full_name')
                    ->from('super_admin')
                    ->where('id', $row->assigned_to)
                    ->get()
                    ->row();

                if ($user) {
                    $changed_by_name = $user->full_name;
                }
            }

            $data[] = [
                'status'     => $row->status,
                'remark'     => $row->remark,
                'changed_by' => ucfirst($changed_by_name),
                'changed_at' => date('D, M d, Y h:i A', strtotime($row->changed_at))
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));

        return;
    }


    public function update_correlation_id()
    {
        $leadId = $this->input->post('lead_id');
        $newCorrelationId = $this->input->post('correlation_id');

        if ($leadId && $newCorrelationId) {
            $this->load->model('LeadModel');
            $updated = $this->LeadModel->append_correlation_id($leadId, $newCorrelationId);

            if ($updated) {
                echo json_encode([
                    'status' => 'success',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
            } else {
                echo json_encode([
                    'status' => 'fail',
                    'message' => 'Update failed',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing parameters',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        }
    }

    public function view_lead_details($leadId)
    {

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin')) {
            return redirect('super-admin-login');
        }

        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        $leadID  = $this->uri->segment('2');

        // Fetch filtered leads
        $data['lead_details'] = $this->LeadModel->get_lead_details($leadID);

        // echo "<pre>";
        // print_r($data);
        // die();



        if (empty($data['lead_details'])) {
            redirect($_SERVER['HTTP_REFERER']);
        }


        $this->db->where('lead_id', $leadId);
        $this->db->order_by('changed_at', 'ASC');
        $change_history = $this->db->get('lead_status_history')->result();


        if (!empty($change_history)) {
            // Format response
            $data['change_history'] = [];
            foreach ($change_history as $row) {
                $data['change_history'][] = [
                    'status'      => $row->status,
                    'remark'      => $row->remark,
                    'changed_by'  => ucfirst($row->changed_by),
                    'changed_at'  => date('D, M d, Y h:i A', strtotime($row->changed_at))
                ];
            }
        } else {
            $data['change_history'] = [];
        }




        if (!$leadId) {
            echo json_encode(['status' => 'error', 'message' => 'Missing lead_id']);
            return;
        }



        $this->db->where('leadid', $leadID);
        $query = $this->db->get('calls');
        $callHistory = $query->result_array();

        if (!$callHistory) {
            $data['call_history'] = [];
        } else {
            $data['call_history'] = $callHistory;
        }



        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/view_lead_details', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function view_lead_details_admin($leadId)
    {

        if (empty($this->session->userdata('hotel_admin_session'))) {
            return redirect('hotel-admin-login');
        }

        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        $leadID  = $this->uri->segment('2');

        // Fetch filtered leads
        $data['lead_details'] = $this->LeadModel->get_lead_details($leadID);



        if (empty($data['lead_details'])) {
            redirect($_SERVER['HTTP_REFERER']);
        }


        $this->db->where('lead_id', $leadId);
        $this->db->order_by('changed_at', 'ASC');
        $change_history = $this->db->get('lead_status_history')->result();


        if (!empty($change_history)) {
            // Format response
            $data['change_history'] = [];
            foreach ($change_history as $row) {
                $data['change_history'][] = [
                    'status'      => $row->status,
                    'remark'      => $row->remark,
                    'changed_by'  => ucfirst($row->changed_by),
                    'changed_at'  => date('D, M d, Y h:i A', strtotime($row->changed_at))
                ];
            }
        } else {
            $data['change_history'] = [];
        }




        if (!$leadId) {
            echo json_encode(['status' => 'error', 'message' => 'Missing lead_id']);
            return;
        }


        $this->db->where('leadid', $leadID);
        $query = $this->db->get('calls');
        $callHistory = $query->result_array();

        if (!$callHistory) {
            $data['call_history'] = [];
        } else {
            $data['call_history'] = $callHistory;
        }


        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/view_lead_details', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    public function view_lead_details_agent($leadId)
    {

        if (empty($this->session->userdata('agent_session'))) {
            return redirect('agent-login');
        }

        $this->load->model('LeadModel');
        $this->load->model('Common_model');

        $leadID  = $this->uri->segment('2');

        // Fetch filtered leads
        $data['lead_details'] = $this->LeadModel->get_lead_details($leadID);



        if (empty($data['lead_details'])) {
            redirect($_SERVER['HTTP_REFERER']);
        }


        $this->db->where('lead_id', $leadId);
        $this->db->order_by('changed_at', 'ASC');
        $change_history = $this->db->get('lead_status_history')->result();


        if (!empty($change_history)) {
            // Format response
            $data['change_history'] = [];
            foreach ($change_history as $row) {
                $data['change_history'][] = [
                    'status'      => $row->status,
                    'remark'      => $row->remark,
                    'changed_by'  => ucfirst($row->changed_by),
                    'changed_at'  => date('D, M d, Y h:i A', strtotime($row->changed_at))
                ];
            }
        } else {
            $data['change_history'] = [];
        }




        if (!$leadId) {
            echo json_encode(['status' => 'error', 'message' => 'Missing lead_id']);
            return;
        }


        $this->db->where('leadid', $leadID);
        $query = $this->db->get('calls');
        $callHistory = $query->result_array();

        if (!$callHistory) {
            $data['call_history'] = [];
        } else {
            $data['call_history'] = $callHistory;
        }



        $this->load->view('agent/include/header');
        $this->load->view('agent/include/sidebar');
        $this->load->view('agent/view_lead_details', $data);
        $this->load->view('agent/include/footer');
    }


    public function get_lead_by_form($hotel_id, $department_id)
    {
        $hotel_id = $this->Comman_model->get_id_by_md5($hotel_id, 'hotel_admin', 'hotel_id');

        $result = $this->Common_model->getdata('hotel_admin', array('hotel_id' => $hotel_id));
        $data['hotel_info'] = $result;
        if (empty($result)) {

            show_404(); // Built-in CodeIgniter function

        }

        $this->load->view('super_admin/get_lead_by_form', $data);
    }

    public function get_last_lead_by_cli()
    {
        $cli = $this->input->post('cli');

        if (!$cli) {
            echo json_encode(['status' => 'error', 'message' => 'CLI missing', 'csrfHash' => $this->security->get_csrf_hash()]);
            return;
        }

        $this->load->model('LeadModel');
        $lead = $this->LeadModel->get_last_lead_by_phone($cli);

        if ($lead) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'user_name' => $lead->user_name,
                    'phone_number' => $lead->phone_number,
                    'query' => $lead->query,
                    'email' => $lead->email,
                ],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No data found', 'csrfHash' => $this->security->get_csrf_hash()]);
        }
    }


    // edit lead and update lead code is here 

    public function edit_lead($id)
    {
        $lead = $this->Comman_model->getData('leads', ['id' => $id]);



        $data['lead'] = $lead;
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', '');

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/edit_lead', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function update_lead()
    {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $id   = $this->input->post('lead_id');
        $lead = $this->Comman_model->getData('leads', ['id' => $id]);

        if (!$lead) {
            echo json_encode([
                'status' => false,
                'message' => 'Lead not found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }


        $older_assignee_id = $lead->assigned_to;
        $order_assignee_user_role = $lead->assigned_person_user_role;
        $older_is_assigned = $lead->is_assigned;


        $property   = $this->input->post('property');
        $type       = $this->input->post('type', true);
        $lead_type       = $this->input->post('lead_type', true);

        $department       = $this->input->post('department', true);

        $status     = $this->input->post('status', true);
        $disposition = $this->input->post('disposition', true);

        $hotel_data      = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $department_data = $this->Common_model->getdata('departments', ['department_id' => $department]);

        $lead_type       = $this->input->post('lead_type', true);

        $department       = $this->input->post('department', true);

        $leadData = [
            'user_name'    => $this->input->post('user_name', true),
            'phone_number' => $this->input->post('phone_number', true),
            'email'        => $this->input->post('email', true),
            'date'         => $this->input->post('date', true),
            'time'         => $this->input->post('time', true),
            'property'     => $property,
            'type'         => $department,
            'status'       => $status,
            'disposition'  => $disposition,
            'query'        => $this->input->post('query', true),
            'remark'       => $this->input->post('remark', true),
            'city'         => $hotel_data->city_id ?? null,
            'updated_on'   => date('Y-m-d H:i:s'),
            'lead_type' => $lead_type
        ];

        $assigned_role = $this->session->userdata('role_as');


        if ($assigned_role === 'agent') {

            $leadData = [
                'user_name'    => $this->input->post('user_name', true),
                'phone_number' => $this->input->post('phone_number', true),
                'email'        => $this->input->post('email', true),
                'date'         => $this->input->post('date', true),
                'time'         => $this->input->post('time', true),
                'status'       => $status,
                'disposition'  => $disposition,
                'query'        => $this->input->post('query', true),
                'remark'       => $this->input->post('remark', true),
                'city'         => $hotel_data->city_id ?? null,
                'updated_on'   => date('Y-m-d H:i:s'),
                'lead_type' => $lead_type
            ];
        }


        // /** Get logged-in user ID based on role */
        // $assigned_role = $this->session->userdata('role_as');
        // if ($assigned_role === 'super_admin') {
        //     $userId = $this->session->userdata('super_admin_session')['id'];
        //     $leadData['assigned_person_user_role'] = 'super_admin';
        //     $leadData['is_assigned']             = 1;
        //     $leadData['assigned_to']             = $userId;
        // } elseif ($assigned_role === 'admin') {
        //     $userId = $this->session->userdata('hotel_admin_session')['id'];
        //     $leadData['assigned_person_user_role'] = 'admin';
        //     $leadData['is_assigned']             = 1;
        //     $leadData['assigned_to']             = $userId;
        // } elseif ($assigned_role === 'agent') {
        //     $userId = $this->session->userdata('agent_session')['id'];
        //     $leadData['assigned_person_user_role'] = 'agent';
        //     $leadData['is_assigned']             = 1;
        //     $leadData['assigned_to']             = $userId;
        // }



        /** Time tracking */
        if ($status === 'Closed') {
            $leadData['completed_time'] = date('Y-m-d H:i:s');
            //$leadData['responded_time'] = date('Y-m-d H:i:s');
        } else {
            $leadData['responded_time'] = date('Y-m-d H:i:s');
        }

        $department  = $department_data->department_name;

        $leadData['promotional_offers'] =  $this->input->post('promotional_offers');

        $leadData['reason'] =  $this->input->post('reason');

        $leadData['purpose'] =  $this->input->post('purpose');



        /** Reservation Closed */
        if (
            ($disposition === 'Quotation Sent' || $disposition === 'Contacted')
            && strtolower($status) === 'in progress'
        ) {
            $department = strtolower($department);

            if ($department === 'rooms') {





                $dates       = $this->input->post('rate_date');   // dd-mm-YYYY
                $rate_types  = $this->input->post('rate_type');
                $room_prices = $this->input->post('room_price');


                $room_rates = [];

                if (!empty($dates)) {
                    foreach ($dates as $key => $dt) {

                        // Convert dd-mm-YYYY → YYYY-mm-dd
                        $date_parts = explode('-', $dt);
                        $formatted_date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];

                        $room_rates[] = [
                            "date"           => $formatted_date,
                            "rate_type_code" => isset($rate_types[$key]) ? $rate_types[$key] : "",
                            "room_price"     => isset($room_prices[$key]) ? $room_prices[$key] : "0"
                        ];
                    }
                }

                // Now you can send this array to your model or API
                $insert_data_room_rates = $room_rates;

                $confirmationNumber = $this->generateConfirmationNumber();

                $hotel_code_Data = $this->LeadModel->getHotelCodeByProperty($property);
                $hotel_code = $hotel_code_Data->hotel_code;

                $requestBody = [
                    "chain_code"          => "E0701",
                    "hotel_code"          => "E0701",
                    "confirmation_number" => $confirmationNumber,
                    "confirmation_type"   => "EXT",
                    "booking_id"          => "",
                    "booking_status"      => "CREATE",

                    "bookings" => [
                        [
                            "booking_sequence_no"  => "1",
                            "date_arrive"          => $this->input->post('checkin_date'),
                            "time_arrive"          => $this->input->post('checkin_time'),
                            "date_depart"          => $this->input->post('checkout_date'),
                            "time_depart"          => $this->input->post('checkout_time'),
                            "adults"               => $this->input->post('adults'),
                            "youths"               => $this->input->post('adults'),
                            "kids"                 => $this->input->post('kids'),
                            "room_type_code"       => $this->input->post('roomtype'),
                            "room_number"          => "",
                            "number_of_rooms"      => $this->input->post('number_of_rooms'),
                            "currency"             => "INR",
                            "rate_type_code"       => "CP",
                            "channel_code"         => "EXP",
                            "market_segment_code"  => "",
                            "business_source_code" => "",
                            "release_date"         => "1900-01-01",
                            "remarks"              => "Booking From LMS",
                            "billing_instructions" => "Bill To Campany 1",

                            "profile" => [
                                "title"                    => "",
                                "name_first"               => $this->input->post('user_name'),
                                "name_last"                => $this->input->post('user_name'),
                                "email_id"                 => $this->input->post('email'),
                                "contact_number"           => $this->input->post('phone_number'),
                                "address_1"                => "",
                                "address_2"                => "",
                                "address_3"                => "",
                                "city"                     => "",
                                "state"                    => "",
                                "zip"                      => "",
                                "country"                  => "",
                                "nationality_code"         => "",
                                "gender"                   => "",
                                "date_of_birth"            => "",
                                "designation"              => "",
                                "profile_image"            => "",
                                "identity_type"            => "",
                                "identity_number"          => "",
                                "identity_date_issue"      => "",
                                "identity_date_expiry"     => "",
                                "identity_issue_authority" => "",
                                "identity_image"           => "",
                                "visa_type"                => "",
                                "visa_number"              => "",
                                "visa_date_issue"          => "",
                                "visa_date_expiry"         => ""
                            ],

                            "room_rates" => $insert_data_room_rates,

                            "chargeable_services" => [],

                            "accompanying_guests" => [
                                [
                                    "guest_sequence_no" => "1",
                                    "category"          => "Adult",
                                    "profile" => [
                                        "title"                    => "",
                                        "name_first"               => "",
                                        "name_last"                => "ACC. EXTERNAL 1 ",
                                        "email_id"                 => "test@xyz.com",
                                        "nationality_code"         => "",
                                        "date_of_birth"            => "2019-11-24",
                                        "age"                      => "1",
                                        "profile_image"            => "",
                                        "identity_type"            => "N",
                                        "identity_number"          => "",
                                        "identity_date_issue"      => "",
                                        "identity_date_expiry"     => "",
                                        "identity_issue_authority" => "",
                                        "identity_image"           => "",
                                        "visa_type"                => "",
                                        "visa_number"              => "",
                                        "visa_date_issue"          => "",
                                        "visa_date_expiry"         => ""
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

                if (!empty($hotel_code)) {

                    $myCloudResponse = $this->send_booking_to_mycloud($requestBody);

                    $myCloudResponse = json_decode($myCloudResponse['response'], true); // convert to 

                    if ($myCloudResponse['status_code'] == "200") {

                        $leadData['confirmation_number'] = $myCloudResponse['bookings'][0]['pms_confirmation_number'];


                        $savebooking = $this->saveLeadBookings($id, $myCloudResponse);
                        $leadData['booking_id'] = $myCloudResponse['bookings'][0]['booking_id'];
                    } else {

                        echo json_encode([
                            'status' => false,
                            'error_code' => $myCloudResponse['error']['ErrorCode'],
                            'error_message' => $myCloudResponse['error']['ErrorDescription'],
                            'csrfHash' => $this->security->get_csrf_hash()
                        ]);
                        return;
                    }
                }







                $leadData['completed_time'] = date('Y-m-d H:i:s');
                $leadData['checkin_date'] = $this->input->post('checkin_date');
                $leadData['checkin_time'] = $this->input->post('checkin_time');
                $leadData['checkout_date'] = $this->input->post('checkout_date');
                $leadData['checkout_time'] = $this->input->post('checkout_time');
                $leadData['roomtype'] = $this->input->post('roomtype');
                $leadData['number_of_rooms'] = $this->input->post('number_of_rooms');
                $leadData['pax'] = $this->input->post('pax');
                $leadData['adults'] = $this->input->post('adults');
                $leadData['kids'] = $this->input->post('kids');
                $leadData['meal_plan'] = $this->input->post('meal_plan');
                $leadData['revenue_fnb']       = $this->input->post('revenue_fnb');

                $leadData['revenue_other']       = $this->input->post('revenue_other');

                $leadData['revenue_room']       = $this->input->post('revenue_room');
                $leadData['amount']       = $this->input->post('amount');
            }


            if ($department === 'wedding') {


                $leadData['completed_time'] = date('Y-m-d H:i:s');
                $leadData['checkin_date'] = $this->input->post('checkin_date');
                $leadData['checkin_time'] = $this->input->post('checkin_time');
                $leadData['checkout_date'] = $this->input->post('checkout_date');
                $leadData['checkout_time'] = $this->input->post('checkout_time');
                $leadData['roomtype'] = $this->input->post('roomtype');
                $leadData['number_of_rooms'] = $this->input->post('number_of_rooms');
                $leadData['pax'] = $this->input->post('pax');
                $leadData['adults'] = $this->input->post('adults');
                $leadData['kids'] = $this->input->post('kids');
                $leadData['meal_plan'] = $this->input->post('meal_plan');
                $leadData['revenue_fnb']       = $this->input->post('revenue_fnb');

                $leadData['revenue_other']       = $this->input->post('revenue_other');

                $leadData['revenue_room']       = $this->input->post('revenue_room');
                $leadData['amount']       = $this->input->post('amount');

                $leadData['booking_date']  = $this->input->post('booking_date');
                $leadData['booking_month']  = $this->input->post('booking_month');

                $leadData['pax']           = $this->input->post('pax');
                $leadData['sitting_style'] = $this->input->post('sitting_style');
                $leadData['audio_visual'] = $this->input->post('audio_visual');
                $leadData['btr'] = $this->input->post('btr');
                $leadData['banquet_id'] = $this->input->post('banquet_id');
            }

            if ($department === 'restaurants') {
                $leadData['booking_date'] = $this->input->post('booking_date');
                $leadData['booking_month']  = $this->input->post('booking_month');
                $table_reservation_status = $this->input->post('table_reservation_status');
                $leadData['table_reservation_status'] = $this->input->post('table_reservation_status');


                $leadData['pax']          = $this->input->post('pax');
                $leadData['amount']       = $this->input->post('amount');
                $leadData['arrival_time']       = $this->input->post('arrival_time');
                $leadData['restaurant_id']       = $this->input->post('restaurant_id');
                $leadData['slot_type_id']       = $this->input->post('slot_type_id');
                $leadData['special_occasion']       = $this->input->post('special_occasion');
                $leadData['special_request']       = $this->input->post('special_request');


                $leadData['slot_type_id']       = $this->input->post('slot_type_id');
                $leadData['special_occasion']       = $this->input->post('special_occasion');
                $leadData['special_request']       = $this->input->post('special_request');
                $leadData['slot_type_id']      = $this->input->post('slot_type_id');
                $leadData['time_slot_id']      = $this->input->post('time_slot_id');

                $leadData['restaurant_id']     = $this->input->post('restaurant_id');
                $leadData['table_category_id'] = $this->input->post('table_category_id');

                $tableIds = $this->input->post('table_id');
                if (is_array($tableIds) && count($tableIds) > 0) {
                    $leadData['table_id'] = implode(',', $tableIds);
                } else {
                    $leadData['table_id'] = $tableIds;
                }
            }

            if ($department === 'banquets') {
                $leadData['booking_date']  = $this->input->post('booking_date');
                $leadData['booking_month']  = $this->input->post('booking_month');

                $leadData['pax']           = $this->input->post('pax');
                $leadData['amount']        = $this->input->post('amount');
                $leadData['sitting_style'] = $this->input->post('sitting_style');
                $leadData['audio_visual'] = $this->input->post('audio_visual');
                $leadData['btr'] = $this->input->post('btr');
                $leadData['banquet_id'] = $this->input->post('banquet_id');
            }


            if ($department === 'spa') {
                $leadData['booking_date']  = $this->input->post('booking_date');
                $leadData['booking_month']  = $this->input->post('booking_month');
                $leadData['pax']           = $this->input->post('pax');
                $leadData['amount']        = $this->input->post('amount');
                $leadData['special_request']       = $this->input->post('special_request');
            }
        } else {
        }

        /** Shopping - Follow up - In Progress */

        $fields = [
            'booking_enquiry_date',
            'booking_date',
            'followup_date',
            'second_followup_date',
            'followup_remark',
            'arrival_time',
            'checkin_date',
            'checkout_date',
            'special_request'
        ];

        foreach ($fields as $field) {
            $value = $this->input->post($field);

            if ($value !== null && trim($value) !== '') {
                $leadData[$field] = $value;
            }
        }


        $amount = $this->input->post('amount');

        if (!empty($amount) && $amount != 0) {
            $leadData['amount'] = $amount;
        }




        $assigned_person_user_role = $this->input->post('assigned_person_user_role');
        $assigned_person_email = $this->input->post('assigned_person_email');
        $assigned_to = $this->input->post('assigned_to');



        $older_assignee_id         = $lead->assigned_to;
        $older_assignee_role       = $lead->assigned_person_user_role;
        $older_is_assigned         = $lead->is_assigned;

        $send_email = false;

        if (!empty($assigned_to)) {

            // Case 1: No one was assigned earlier
            if (empty($older_assignee_id)) {

                $leadData['is_assigned'] = 1;
                $leadData['assigned_to'] = $assigned_to;
                $leadData['assigned_person_user_role'] = $assigned_person_user_role;
                $leadData['assigned_person_email'] = $assigned_person_email;

                $send_email = true;
            }

            // Case 2: Someone was assigned but changed to a different user or role
            elseif (
                $older_assignee_id != $assigned_to ||
                $older_assignee_role != $assigned_person_user_role
            ) {

                $leadData['assigned_to'] = $assigned_to;
                $leadData['assigned_person_user_role'] = $assigned_person_user_role;
                $leadData['assigned_person_email'] = $assigned_person_email;

                $send_email = true;
            }
        }

        /* Send Email Notification only if new person assigned */

        $phone = $leadData['phone_number'];

        // Check if any previous lead has reservation with revenue > 0
        $valuableGuest = $this->db
            ->select('id, disposition, amount')  // revenue_amount = your amount column
            ->from('leads')
            ->where('phone_number', $phone)
            ->where('LOWER(disposition)', 'reservation')   // case-insensitive match
            ->where('amount >', 0)                // has revenue
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if ($valuableGuest) {
            $IsvaluableGuest = true;
        } else {
            $IsvaluableGuest = false;
        }



        /** Update record */
        $updated = $this->Comman_model->UpdateRecord('leads', $leadData, ['id' => $id]);


        if ($updated) {

            /*
    |--------------------------------------------------------------------------
    | Restaurant Notifications (Only On Status Change)
    |--------------------------------------------------------------------------
    */

            if (
                $department
                == 'restaurants'
            ) {


                $old_table_reservation_status = !empty($lead->table_reservation_status)
                    ? trim($lead->table_reservation_status)
                    : '';

                $table_reservation_status = trim(
                    $this->input->post('table_reservation_status')
                );

                /*
        |--------------------------------------------------------------------------
        | Reserved → Booking Confirmation
        |--------------------------------------------------------------------------
        */

                if (
                    $table_reservation_status == 'Reserved'
                    &&
                    $old_table_reservation_status != 'Reserved'
                ) {

                    $this->send_restaurant_booking_confirmation($id);
                }

                /*
        |--------------------------------------------------------------------------
        | Completed → Feedback Link
        |--------------------------------------------------------------------------
        */

                if (
                    $table_reservation_status == 'Completed'
                    &&
                    $old_table_reservation_status != 'Completed'
                ) {

                    $this->send_restaurant_feedback_link($id);
                }
            }
        }


        $assigned_role = $this->session->userdata('role_as');

        if ($assigned_role == 'super_admin') {
            $userId = $this->session->userdata('super_admin_session')['id'];
        } else if ($assigned_role == 'admin') {
            $userId = $this->session->userdata('hotel_admin_session')['id'];
        } else {
            $userId = $this->session->userdata('agent_session')['id'];
        }

        if ($updated) {
            $historyData = [
                'lead_id' => $id,
                'status' => $status,
                'remark' => $this->input->post('remark', true),
                'changed_by' => $assigned_role,
                'changed_at' => date('Y-m-d H:i:s'),
                'assigned_to' => $userId
            ];

            $this->db->insert('lead_status_history', $historyData);
        }


        if ($send_email) {



            $url = base_url("EmailWorker/sendLeadEmailToassigned_person_email/$id/$IsvaluableGuest");

            // Fire & Forget HTTP Request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, false); // GET request
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // We don't care about the response
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 100); // Fast timeout
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100); // Don't wait for response
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1); // For timeout under 1s (only on Unix)
            curl_exec($ch);
            curl_close($ch);
        }

        $this->db->select('leads.*, departments.department_name');
        $this->db->from('leads');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->where('leads.id', $id);
        $updatedLead = $this->db->get()->row();


        echo json_encode([
            'status' => true,
            'message' => 'Lead updated successfully.',
            'data' => $updatedLead,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    function generateConfirmationNumber()
    {
        return time() . mt_rand(100, 999);
    }


    public function search_suggestions()
    {
        $query = $this->input->post('query', true);

        $this->db->select('user_name, phone_number');
        $this->db->group_start()
            ->like('user_name', $query)
            ->or_like('phone_number', $query)
            ->group_end();
        $this->db->group_by(['user_name', 'phone_number']);
        $this->db->limit(10);

        $result = $this->db->get('leads')->result_array();

        echo json_encode($result);
    }


    public function fetch_leads_ajax()
    {
        header('Content-Type: application/json');

        $offset = $this->input->post('offset');
        $limit = $this->input->post('limit');

        $filters = [
            'city' => $this->input->post('city'),
            'property' => $this->input->post('property'),
            'department' => $this->input->post('department'),
            'status' => $this->input->post('status'),
            'channel' => $this->input->post('channel'),
            'disposition' => $this->input->post('disposition'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'search' => $this->input->post('search'),
            'phone' => $this->input->post('phone'),
            'business_type' => $this->input->post('business_type'),



            // ✅ ADD THESE (IMPORTANT)
            'created_id'    => $this->input->post('created_id'),
            'created_role'  => $this->input->post('created_role'),
            'assigned_id'   => $this->input->post('assigned_id'),
            'assigned_role' => $this->input->post('assigned_role'),

            'showfollowupleads' => $this->input->post('showfollowupleads'),

        ];

        $this->load->model('Leadmodel');

        // ✅ PASS FILTERS (now includes creator + assigned)
        $leads = $this->Leadmodel->get_filtered_leads($filters, $limit, $offset);



        $totalCounts = $this->Leadmodel->get_leads_status_counts($filters);

        $data['leads'] = $leads;
        $html = $this->load->view('ajax_leads_cards', $data, true);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'html'        => $html,
                'count'       => count($leads),
                'totalCounts' => $totalCounts,
                'csrfHash'    => $this->security->get_csrf_hash()
            ]));

        exit;
    }


    public function fetch_leads_ajax_agency()
    {
        $offset = $this->input->post('offset');
        $limit = $this->input->post('limit');

        $agency_id = $this->session->userdata('agency_session')['id'];




        $filters = [
            'city' => $this->input->post('city'),
            'property' => $this->input->post('property'),
            'department' => $this->input->post('department'),
            'status' => $this->input->post('status'),
            'channel' => $this->input->post('channel'),
            'disposition' => $this->input->post('disposition'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'search' => $this->input->post('search'),
            'created_by' => $agency_id,
            'business_type' => $this->input->post('business_type') // single value: 'business' or 'non_business'
        ];

        $this->load->model('Leadmodel');

        // Get leads with limit/offset
        $leads = $this->Leadmodel->get_filtered_leads($filters, $limit, $offset);

        // Get overall counts based on applied filters
        $totalCounts = $this->Leadmodel->get_leads_status_counts($filters);

        $data['leads'] = $leads;
        $html = $this->load->view('ajax_leads_cards_agency', $data, true);

        $response = [
            'html'        => $html,
            'count'       => count($leads),
            'totalCounts' => $totalCounts
        ];

        // Send JSON safely in CI3
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

        return;
    }


    public function get_agents_by_department()
    {
        $department_id      = $this->input->post('department_id', true);
        $transfer_property  = $this->input->post('property_id', true);

        if (empty($department_id) || empty($transfer_property)) {
            echo '<option value="">No Agents Found</option>';
            return;
        }

        // JOIN: staff_hotel_department_mapping → staff_members
        $this->db->select('s.id, s.name');
        $this->db->from('staff_hotel_department_mapping as m');
        $this->db->join('staff_members as s', 's.id = m.staff_id', 'left');
        $this->db->where('m.hotel_id', $transfer_property);
        $this->db->where('m.department_id', $department_id);
        $this->db->where('s.status', 1);  // only active staff
        $this->db->group_by('s.id');       // remove duplicates

        $query = $this->db->get();
        $agents = $query->result_array();

        if (empty($agents)) {
            echo '<option value="">No Agents Available</option>';
            return;
        }

        // Build dropdown HTML
        $html = '<option value="">Select Agent</option>';
        foreach ($agents as $a) {
            $html .= '<option value="' . $a['id'] . '">' . $a['name'] . '</option>';
        }

        echo $html;
    }


    public function transfer_lead()
    {
        $lead_id       = $this->input->post('lead_id');
        $property_id   = $this->input->post('property_id');
        $department_id = $this->input->post('department_id');
        $agent_id      = $this->input->post('agent_id');
        $remark        = trim($this->input->post('remark'));

        // Basic validation
        if (!$lead_id || !$property_id || !$department_id || !$agent_id || $remark == "") {
            echo json_encode([
                'status'  => 'error',
                'message' => 'All fields are required.',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Build update data
        $updateData = [
            'assigned_to'   => $agent_id,
            'is_assigned'   => 1,
            'property'   => $property_id,
            'type' => $department_id,
            'remark' => $remark,
            'updated_on' => date("Y-m-d H:i:s")
        ];

        // Update lead table
        $updated = $this->Common_model->UpdateRecord("leads", $updateData, ['id' => $lead_id]);




        if ($updated) {

            if ($updated) {
                $historyData = [
                    'lead_id' => $lead_id,
                    'status' => 'Open',
                    'remark' => $this->input->post('remark', true),
                    'changed_by' => $assigned_role,
                    'changed_at' => date('Y-m-d H:i:s'),
                    'assigned_to' => $userId
                ];
                $this->db->insert('lead_status_history', $historyData);
            }

            $response = [
                'status'  => 'success',
                'message' => 'Lead transferred successfully.',
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        } else {
            $response = [
                'status'  => 'error',
                'message' => 'Unable to transfer lead. Please try again.',
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        }

        // Send JSON safely in CI3
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

        return;
    }

    public function deleteLead()
    {
        $id = $this->input->post('id');

        if ($id) {
            $this->db->where('id', $id)->delete('leads');

            $response = [
                'status' => true,
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        } else {
            $response = [
                'status' => false,
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        }

        // Send JSON response safely in CI3
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

        return;
    }


    public function send_booking_to_mycloud($requestBody)
    {

        // Convert PHP array to JSON
        $jsonBody = json_encode($requestBody);



        // MyCloud API URL
        $url = "https://live2.mycloudhospitality.com/mycloud_WebAPI2.0/api/pms/reservation/processbookings";

        // Required headers
        $headers = [
            "Content-Type: application/json",
            "authCODE: lfgLTH0m25gFGYStsAEBAJ9j6Vg45kk",
            "clientID: SAYAJI"
        ];

        // cURL setup
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        // Optional logging (recommended)
        file_put_contents(APPPATH . 'logs/mycloud_request.log', $jsonBody . PHP_EOL, FILE_APPEND);
        file_put_contents(APPPATH . 'logs/mycloud_response.log', $response . PHP_EOL, FILE_APPEND);

        if ($error) {
            return [
                "status"  => false,
                "error"   => $error
            ];
        }

        return [
            "status"   => true,
            "response" => $response
        ];
    }

    public function search_bookings($booking_id = "100528")
    {
        // === Request body ===
        $requestBody = [
            "chain_code"                => "E0701",
            "hotel_code"                => "E0701",
            "booking_status"            => "CHECKEDOUT",
            "confirmation_number"       => "",
            "booking_id"                => "100326",
            "room_type_code"            => "",
            "room_number"               => "",
            "guest_name"                => "",
            "date_arrive"               => "",
            "date_depart"               => "",
            "contact_number"            => "",
            "email_id"                  => "",
            "calculate_revenues"        => "Y",
            "booking_type_indicator"    => "",
            "booking_type_date_from"    => "",
            "booking_type_date_to"      => "",
            "booking_stay_indicator"    => "",
            "booking_stay_date_from"    => "",
            "booking_stay_date_to"      => "",
            "calculate_bookings_summary" => "Y"
        ];

        $jsonData = json_encode($requestBody, JSON_UNESCAPED_SLASHES);

        // === API credentials ===
        $url        = "https://live2.mycloudhospitality.com/mycloud_WebAPI2.0/api/pms/reservation/searchbookings";
        $authCode   = "lfgLTH0m25gFGYStsAEBAJ9j6Vg45kk";
        $clientId   = "SAYAJI";

        // === cURL Setup ===
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $jsonData,
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "authCODE: $authCode",
                "clientID: $clientId"
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // cURL ERROR handling
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);

            echo json_encode([
                "status"  => "error",
                "message" => "Curl Error: " . $error_msg
            ], JSON_PRETTY_PRINT);
            return;
        }

        curl_close($curl);

        // HTTP status error
        if ($httpCode !== 200) {
            echo json_encode([
                "status"  => "error",
                "message" => "API returned HTTP Status $httpCode",
                "raw"     => $response
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Convert API response to array
        $decoded = json_decode($response, true);

        // Response validation
        if (empty($decoded)) {
            echo json_encode([
                "status"  => "error",
                "message" => "Invalid JSON response",
                "raw"     => $response
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Everything is OK, return data to frontend

        echo "<pre>";

        $booking_info = $decoded['bookings'][0];

        $booking_data = [
            'name_last' => $booking_info['name_last'],
            'name_first' => $booking_info['name_first'],
            'date_arrive' => $booking_info['date_arrive'],
            'time_arrive' => $booking_info['time_arrive'],
            'date_depart' => $booking_info['date_depart'],
            'time_depart' => $booking_info['time_depart'],
            'adults' => $booking_info['adults'],
            'room_type' => $booking_info['room_type'],
            'pms_confirmation_number' => $booking_info['pms_confirmation_number'],
            'booking_status' => $booking_info['booking_status'],
            'booking_status_description' => $booking_info['booking_status_description'],
            'created_at' => $booking_info['created_at'],
            'payment_method_code' => $booking_info['payment_method_code'],
            'payment_method_description' => $booking_info['payment_method_description'],
            'date_modified' => $booking_info['date_modified'],
            'revenue_room' => $booking_info['revenue_room'],
            'tax_room' => $booking_info['tax_room'],
            'total_room' => $booking_info['total_room'],
            'revenue_fnb' => $booking_info['revenue_fnb'],
            'tax_fnb' => $booking_info['tax_fnb'],
            'total_fnb' => $booking_info['total_fnb'],
            'revenue_other' => $booking_info['revenue_other'],
            'tax_other' => $booking_info['tax_other'],
            'total_other' => $booking_info['total_other'],
            'deposits' => $booking_info['deposits'],
            'deposit_required_amount' => $booking_info['deposit_required_amount']
        ];



        echo "<pre>";
        print_r($booking_data);
    }


    public function saveLeadBookings($lead_id, $data)
    {


        if (!isset($data['bookings'])) {
            return false;
        }

        foreach ($data['bookings'] as $booking) {

            $insertData = [
                "lead_id"                   => $lead_id,
                "booking_sequence_no"       => $booking['booking_sequence_no'],
                "pms_confirmation_number"   => $booking['pms_confirmation_number'],
                "booking_id"                => $booking['booking_id'],
                "booking_status"            => $booking['booking_status'],
                "booking_status_description" => $booking['booking_status_description'],
                "created_at"                => date("Y-m-d H:i:s")
            ];

            $this->db->insert("lead_pms_bookings", $insertData);
        }

        return true;
    }






    public function getRoomRateAvailabilityAjax()
    {
        $requestData = $this->input->post(); // data from AJAX

        $apiResponse = $this->getRoomRateAvailability($requestData); // your previous function

        // Convert JSON string → array
        $responseArray = json_decode($apiResponse);

        $response = [
            "status" => true,
            "data"   => $responseArray,
            "csrfHash" => $this->security->get_csrf_hash()
        ];

        // Send JSON safely in CI3
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

        return;
    }



    public function getRoomRateAvailability($requestData)
    {
        // Convert array to JSON
        $jsonData = json_encode($requestData);

        // API URL
        $url = "https://live2.mycloudhospitality.com/mycloud_WebAPI2.0/api/pms/reservation/getroomrateavailability";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "authCODE: lfgLTH0m25gFGYStsAEBAJ9j6Vg45kk",
                "clientID: SAYAJI"
            ),
        ));

        $response = curl_exec($curl);
        $error    = curl_error($curl);

        curl_close($curl);

        // Handle possible cURL error
        if ($error) {
            return json_encode([
                "status" => false,
                "message" => "Curl error: " . $error
            ]);
        }

        return $response; // returns API response as-is
    }


    public function getwhatsappTempByProperty()
    {
        $property_id = $this->input->post('property_id');

        $templates = $this->db
            ->where('property_id', $property_id)
            ->where('status', 1)
            ->get('whatsapp_templates')
            ->result();

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'data'   => is_object($templates) ? (array) $templates : $templates,
                'csrfHash' => $this->security->get_csrf_hash()
            ], JSON_UNESCAPED_UNICODE));

        return;
    }



    public function sendwhatsappMessage()
    {
        $template_id  = $this->input->post('template_id');
        $phone_number = $this->input->post('phone_number');
        $user_name    = $this->input->post('user_name');
        $created_at   = $this->input->post('created_at');

        if (!$template_id || !$phone_number) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $phone_number = preg_replace('/\D/', '', $phone_number);
        $phone_number = substr($phone_number, -10);
        $to = '91' . $phone_number;

        $template = $this->db
            ->where('id', $template_id)
            ->get('whatsapp_templates')
            ->row();

        if (!$template) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Template not found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $api_endpoint        = $template->api_endpoint;
        $api_key             = $template->api_key;
        $orai_template_code  = $template->orai_template_code;

        $date = date('d/m/Y', strtotime($created_at ?? date('Y-m-d')));
        $time = date('h:i A');

        $payload = [
            "type" => "template",
            "messaging_product" => "whatsapp",
            "to" => $to,
            "template" => [
                "namespace" => "af16001f_0c50_4871_8cc1_4684cbf5b878",
                "language" => [
                    "policy" => "deterministic",
                    "code" => "en_US"
                ],
                "name" => $orai_template_code,
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => $user_name
                            ],
                            [
                                "type" => "text",
                                "text" => $date
                            ],
                            [
                                "type" => "text",
                                "text" => $time
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $api_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                "API-KEY: {$api_key}",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30
        ]);

        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($curlErr) {
            echo json_encode([
                'status' => 'error',
                'message' => 'cURL Error: ' . $curlErr,
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $apiResponse = json_decode($response, true);

        if ($httpCode !== 200 && $httpCode !== 201) {
            echo json_encode([
                'status' => 'error',
                'message' => 'WhatsApp API failed',
                'response' => $apiResponse,
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status'  => 'success',
                'message' => 'WhatsApp message sent successfully',
                'csrfHash' => $this->security->get_csrf_hash()
            ], JSON_UNESCAPED_UNICODE));

        return;
    }


    public function getRestaurantsByHotel()
    {
        $hotel_id = $this->input->post('hotel_id');

        $restaurants = $this->db
            ->where('hotel_id', $hotel_id)
            ->where('status', 1)
            ->get('hotel_restaurants')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $restaurants,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function getBanquetsByHotel()
    {
        $hotel_id = $this->input->post('hotel_id');

        if (empty($hotel_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Hotel ID missing',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $banquets = $this->db
            ->where('hotel_id', $hotel_id)
            ->get('banquet')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $banquets,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function getMealPlans()
    {
        $meal_plans = $this->db
            ->get('meal_plans')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $meal_plans,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function getPromotionalOffersByDepartment()
    {
        $department_id = $this->input->post('department_id');

        if (empty($department_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Department ID missing',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $promotional_offers = $this->db
            ->where('department_id', $department_id)
            ->get('promotional_offers')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $promotional_offers,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function getRoomTypesByHotel()
    {
        $hotel_id = $this->input->post('hotel_id');

        if (empty($hotel_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Hotel ID missing',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $roomtypes = $this->db
            ->where('hotel_id', $hotel_id)
            ->get('roomtype')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $roomtypes,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function getSlotTypes()
    {
        $slots = $this->db
            ->where('status', 1)
            ->order_by('start_time', 'ASC')
            ->get('slot_types')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $slots,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function getTimeSlots()
    {
        $slot_type_id = $this->input->post('slot_type_id');

        if (!$slot_type_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Slot Type ID missing',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $slots = $this->db
            ->where('slot_type_id', $slot_type_id) // 🔥 IMPORTANT relation
            ->where('status', 1)
            ->order_by('start_time', 'ASC')
            ->get('time_slots')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $slots,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function getTableCategories()
    {
        $restaurant_id = $this->input->post('restaurant_id');

        if (!$restaurant_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Restaurant ID missing',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $categories = $this->db
            ->where('restaurant_id', $restaurant_id) // 🔥 relation
            ->where('status', 1)
            ->order_by('category_name', 'ASC')
            ->get('table_categories')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $categories,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }



    public function getTables()
    {
        $restaurant_id = $this->input->post('restaurant_id');
        $category_id   = $this->input->post('category_id');

        if (!$restaurant_id || !$category_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing parameters',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $tables = $this->db
            ->where('restaurant_id', $restaurant_id)
            ->where('category_id', $category_id)
            ->where('status', 1)
            ->order_by('table_name', 'ASC')
            ->get('tables')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $tables,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }
}

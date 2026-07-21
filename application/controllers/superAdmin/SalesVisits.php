<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesVisits extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeadModel'); // Load Model
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Mycloud_config_model');
        $this->load->helper('secure');


        if (
            empty($this->session->userdata('super_admin_session')) ||
            ($this->session->userdata('role_as') != 'super_admin') ||
            ($this->session->userdata('user_role') != 1)
        ) {
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

    private function jsonResponse($payload)
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
            'module' => 'sales_visits',
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

    private function validateSalesVisitFields()
    {
        $reportDate = trim((string) $this->input->post('report_date'));
        $discussion = trim((string) $this->input->post('discussion_summary'));
        $parsedDate = DateTime::createFromFormat('Y-m-d', $reportDate);

        if ($reportDate === '' || !$parsedDate || $parsedDate->format('Y-m-d') !== $reportDate) {
            return 'Please enter a valid report date';
        }

        if ($discussion === '') {
            return 'Discussion summary is required';
        }

        foreach (['kms_run', 'rate_per_km', 'parking_charges', 'lunch', 'entertainment', 'total_amount'] as $field) {
            $value = $this->input->post($field);
            if ($value !== null && $value !== '' && (!is_numeric($value) || (float) $value < 0)) {
                return 'Conveyance amounts must be valid non-negative numbers';
            }
        }

        return '';
    }

    /* ================= MANAGE PAGE ================= */
    public function index()
    {
        $data['companies'] = $this->Common_model->getAllData('companies', ['status' => 1, 'is_deleted' => 0]);

        $this->db->select('
    sv.*,
    c.company_name,
    cc.first_name,
    cc.last_name,
    su.full_name AS sales_user_name
');
        $this->db->from('sales_visits sv');
        $this->db->join('companies c', 'c.company_id = sv.company_id', 'left');
        $this->db->join('company_contacts cc', 'cc.contact_id = sv.person_met', 'left');
        $this->db->join('sales_users su', 'su.id = sv.user_id', 'left');
        $this->db->where('sv.status', 1);
        $this->db->where('sv.is_deleted', 0);
        $this->db->order_by('sv.report_date', 'DESC');

        $data['sales_visits'] = $this->db->get()->result();



        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/sales_visits/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }


    public function add()
    {
        $data['departments'] = $this->Common_model->getAllData('departments', ['is_deleted' => 0]);
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', ['is_deleted' => 0]);
        $data['companies'] = $this->Common_model->getAllData('companies', ['status' => 1, 'is_deleted' => 0]);


        $this->db->select('a.*, 
                       su1.full_name as primary_user_name, 
                       su2.full_name as secondary_user_name,
                       s.state_name');

        $this->db->from('areas a');
        $this->db->join('sales_users su1', 'su1.id = a.primary_user_id', 'left');
        $this->db->join('sales_users su2', 'su2.id = a.secondary_user_id', 'left');
        $this->db->join('state s', 's.state_id = a.state_id', 'left'); // Assuming you have a states table
        $this->db->where('a.is_deleted', 0);
        $query = $this->db->get();
        $data['areas'] = $query->result();

        $data['roomtype'] = $this->Common_model->getAllData('roomtype', ['is_deleted' => 0]);
        $data['travel_modes'] = $this->Common_model->getAllData('travel_modes', ['is_deleted' => 0]);




        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/sales_visits/add', $data);
        $this->load->view('super_admin/include/footer');
    }

    /* ================= ADD SALES VISIT ================= */
    public function insert()
    {
        $validationError = $this->validateSalesVisitFields();
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        /* ===================== BASIC INPUTS ===================== */
        $property   = $this->decodeId($this->input->post('property'));
        $type       = $this->decodeId($this->input->post('type', true));
        $companyId  = $this->decodeId($this->input->post('company_id'));
        $personMet  = $this->decodeId($this->input->post('person_met'));
        $travelMode = $this->decodeId($this->input->post('travel_mode'));




        /* ===================== PERSON MET DETAILS ===================== */
        $personMetdata = $this->Common_model->getdata(
            'company_contacts',
            ['contact_id' => $personMet, 'company_id' => $companyId, 'status' => 'Active', 'is_deleted' => 0]
        );

        $hotel_data = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property, 'is_deleted' => 0]);
        $department_data = $this->Common_model->getdata('departments', ['department_id' => $type, 'is_deleted' => 0]);
        $company = $this->Common_model->getdata('companies', ['company_id' => $companyId, 'status' => 1, 'is_deleted' => 0]);
        $travelModeData = empty($travelMode) ? true : $this->Common_model->getdata('travel_modes', ['id' => $travelMode, 'is_deleted' => 0]);

        if (empty($property) || empty($type) || empty($companyId) || empty($personMet) || empty($personMetdata) || empty($hotel_data) || empty($department_data) || empty($company) || empty($travelModeData)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid sales visit selection']);
            return;
        }

        $name          = trim($personMetdata->first_name . ' ' . $personMetdata->last_name);
        $mobile_number = $personMetdata->mobile_number;
        $email         = $personMetdata->email;

        /* ===================== POST CHECK ===================== */
        if ($this->input->method() === 'post') {

            /* ===================== HOTEL & DEPARTMENT ===================== */
            /* ===================== ESCALATION ===================== */
            $escalation_level_1 = $department_data->escalation_level_1;

            $minutes_to_add = $escalation_level_1 * 60;
            $current_time   = new DateTime();
            $current_time->modify("+$minutes_to_add minutes");

            $follow_up_time  = $current_time->format('Y-m-d H:i:s');
            $follow_up_level = 1;

            /* ===================== USER ROLE ===================== */
            $assigned_role = $this->session->userdata('role_as');

            if ($assigned_role == 'super_admin') {
                $userId = $this->session->userdata('super_admin_session')['id'];
            } elseif ($assigned_role == 'admin') {
                $userId = $this->session->userdata('hotel_admin_session')['id'];
            } else {
                $userId = $this->session->userdata('agent_session')['id'];
            }

            /* ===================== LEAD DATA ===================== */
            $leadData = [
                'user_name'         => $name,
                'phone_number'      => $mobile_number,
                'email'             => $email,
                'date'              => date('d-m-Y'),
                'time'              => $this->input->post('time', true),
                'user_channel'      => $this->input->post('user_channel', true),
                'property'          => $property,
                'type'              => $type,
                'status'            => $this->input->post('status', true),
                'disposition'       => $this->input->post('disposition', true),
                'query'             => $this->input->post('query', true),
                'remark'            => $this->input->post('remarks', true),
                'lead_type'         => $this->input->post('lead_type', true),

                'city'              => $hotel_data->city_id ?? null,
                'follow_up_time'    => $follow_up_time,
                'follow_up_level'   => $follow_up_level,
                'template_name'     => 'Phone',
                'created_by'        => $userId,
                'creator_user_role' => $assigned_role,
                'created_at'        => date('Y-m-d H:i:s')
            ];

            /* ===================== STATUS LOGIC ===================== */
            $status      = $this->input->post('status', true);
            $disposition = $this->input->post('disposition', true);
            $department  = $this->input->post('leadDepartment', true);

            if ($status === 'Closed') {
                $leadData['completed_time'] = date('Y-m-d H:i:s');
            } else {
                $leadData['responded_time'] = date('Y-m-d H:i:s');
            }

            /** Reservation Closed */
            if ($disposition === 'Reservation' && strtolower($status) === 'closed') {
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
                        "chain_code"          => $this->Mycloud_config_model->get_runtime_config()->chain_code,
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
                                "remark"              => "Booking From LMS",
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
                        } else {

                            $this->jsonResponse([
                                'status' => false,
                                'error_code' => $myCloudResponse['error']['ErrorCode'],
                                'error_message' => $myCloudResponse['error']['ErrorDescription']
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
                }

                if ($department === 'restaurants') {
                    $leadData['booking_date'] = $this->input->post('booking_date');
                    $leadData['pax']          = $this->input->post('pax');
                    $leadData['amount']       = $this->input->post('amount');
                    $leadData['arrival_time']       = $this->input->post('arrival_time');
                    $leadData['restaurant_id']       = $this->decodeId($this->input->post('restaurant_id'));
                    $leadData['slot_type_id']       = $this->decodeId($this->input->post('slot_type_id'));
                }

                if ($department === 'banquets') {
                    $leadData['booking_date']  = $this->input->post('booking_date');
                    $leadData['pax']           = $this->input->post('pax');
                    $leadData['amount']        = $this->input->post('amount');
                    $leadData['banquet_email'] = $this->input->post('banquet_email');
                }
            } else {
                $leadData['amount']       = 0;
            }

            /** Shopping - Follow up - In Progress */
            if (strpos(strtolower($disposition), 'shopping - follow up') !== false && strtolower($status) === 'in progress') {
                $leadData['booking_enquiry_date']  = $this->input->post('booking_enquiry_date');
                $leadData['followup_date']         = $this->input->post('followup_date');
                $leadData['second_followup_date']  = $this->input->post('second_followup_date');
                $leadData['followup_remark']       = $this->input->post('followup_remark');

                if ($department === 'banquets') {
                    $leadData['transfer_to_manager'] = $this->input->post('transfer_to_manager');
                }
            }

            if ($disposition === 'Denied' && strtolower($status) === 'closed') {
                $leadData['checkin_date']       = $this->input->post('checkin_date');
                $leadData['checkout_date']      = $this->input->post('checkout_date');
            }




            /* ===================== INSERT LEAD ===================== */
            $insert_id = $this->LeadModel->insert_lead($leadData);



            /* ===================== SALES VISIT DATA ===================== */
            $data = [
                'user_id'            => $userId,
                'report_date'        => $this->input->post('report_date'),
                'company_id'         => $companyId,
                'person_met'         => $personMet,
                'agenda'             => $this->input->post('agenda'),
                'discussion_summary' => $this->input->post('discussion_summary'),
                'conclusion'         => $this->input->post('conclusion'),

                'area_covered'       => $this->input->post('area_covered'),
                'travel_mode'        => $travelMode,
                'kms_run'            => $this->input->post('kms_run'),
                'rate_per_km'        => $this->input->post('rate_per_km'),
                'parking_charges'    => $this->input->post('parking_charges'),
                'lunch'              => $this->input->post('lunch'),
                'entertainment'      => $this->input->post('entertainment'),
                'total_amount'       => $this->input->post('total_amount'),

                'property'           => $property,
                'type'               => $type,
                'remarks'            => $this->input->post('remarks'),
                'lead_id_againts_visit' => $insert_id,
                'creator_user_role' => $assigned_role,

                'status'             => 1,
                'is_deleted'         => 0,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s')
            ];

            $insert = $this->db->insert('sales_visits', $data);
            $visitId = $insert ? $this->db->insert_id() : 0;

            if ($visitId) {
                $this->logActivity('create', $visitId, 'Created sales visit for company ID '.$companyId);
            }

            /* ===================== VALUABLE GUEST CHECK ===================== */
            $valuableGuest = $this->db
                ->select('id, disposition, amount')
                ->from('leads')
                ->where('phone_number', $mobile_number)
                ->where('LOWER(disposition)', 'reservation')
                ->where('amount >', 0)
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()
                ->row();

            $IsvaluableGuest = $valuableGuest ? true : false;

            /* ===================== EMAIL TRIGGER ===================== */
            if ($insert_id) {

                $url = base_url("EmailWorker/sendLeadEmail/$insert_id/$IsvaluableGuest");

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 100);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_exec($ch);
                curl_close($ch);
            }

            $this->jsonResponse([
                'status' => (bool) $visitId,
                'message' => $visitId ? 'Sales visit created successfully.' : 'Unable to create sales visit.'
            ]);
        } else {
            show_404();
        }
    }



    public function edit($visit_id)
    {
        $visit_id = $this->decodeId($visit_id);

        if (empty($visit_id)) {
            show_404();
        }

        $data['departments'] = $this->Common_model->getAllData('departments', ['is_deleted' => 0]);
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', ['is_deleted' => 0]);
        $data['companies'] = $this->Common_model->getAllData('companies', ['status' => 1, 'is_deleted' => 0]);
        $data['roomtype'] = $this->Common_model->getAllData('roomtype', ['is_deleted' => 0]);
        $data['travel_modes'] = $this->Common_model->getAllData('travel_modes', ['is_deleted' => 0]);

        // Keep lead-specific fields available, but let the sales visit values win
        // when both tables contain columns such as property, type or status.
        $this->db->select('
    l.*,
    sv.*,
    l.status AS lead_status,
    c.company_name,
    cc.first_name,
    cc.last_name,
    su.full_name AS sales_user_name
');

        $this->db->from('sales_visits sv');

        $this->db->join('companies c', 'c.company_id = sv.company_id', 'left');
        $this->db->join('company_contacts cc', 'cc.contact_id = sv.person_met', 'left');
        $this->db->join('sales_users su', 'su.id = sv.user_id', 'left');

        /* 🔗 Leads Join */
        $this->db->join('leads l', 'l.id = sv.lead_id_againts_visit', 'left');

        $this->db->where('sv.status', 1);
        $this->db->where('sv.is_deleted', 0);
        $this->db->where('sv.visit_id', $visit_id);
        $this->db->order_by('sv.report_date', 'DESC');

        $data['sales_visit'] = $this->db->get()->row();

        if (empty($data['sales_visit'])) {
            show_404();
            return;
        }



        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/sales_visits/edit', $data);
        $this->load->view('super_admin/include/footer');
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


    public function send_booking_to_mycloud($requestBody)
    {

        $config = $this->Mycloud_config_model->get_runtime_config();
        if (!$config->is_ready) {
            return $this->mycloudRequestError('MYCLOUD_CONFIG_MISSING', 'MyCloud PMS configuration is incomplete.');
        }

        // Convert PHP array to JSON
        $jsonBody = json_encode($requestBody);



        // MyCloud API URL
        $url = $this->Mycloud_config_model->get_endpoint('processbookings');

        // Required headers
        $headers = [
            "Content-Type: application/json",
            "authCODE: {$config->auth_code}",
            "clientID: {$config->client_id}"
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
            return $this->mycloudRequestError('MYCLOUD_CONNECTION_ERROR', $error);
        }

        $decoded_response = json_decode($response, true);
        if (!is_array($decoded_response) || !isset($decoded_response['status_code'])) {
            return $this->mycloudRequestError('MYCLOUD_INVALID_RESPONSE', 'MyCloud returned an invalid response.');
        }

        if ((string) $decoded_response['status_code'] === '200' && empty($decoded_response['bookings'][0])) {
            return $this->mycloudRequestError('MYCLOUD_INVALID_RESPONSE', 'MyCloud did not return booking details.');
        }

        if ((string) $decoded_response['status_code'] !== '200' && empty($decoded_response['error']['ErrorDescription'])) {
            return $this->mycloudRequestError('MYCLOUD_API_ERROR', 'MyCloud rejected the booking request.');
        }

        return [
            "status"   => true,
            "response" => $response
        ];
    }

    private function mycloudRequestError($code, $message)
    {
        return [
            'status' => false,
            'error' => $message,
            'response' => json_encode([
                'status_code' => '500',
                'bookings' => [],
                'error' => [
                    'ErrorCode' => $code,
                    'ErrorDescription' => $message
                ]
            ])
        ];
    }

    public function search_bookings($booking_id = "100528")
    {
        // === Request body ===
        $requestBody = [
            "chain_code"                => $this->Mycloud_config_model->get_runtime_config()->chain_code,
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
        $config = $this->Mycloud_config_model->get_runtime_config();
        if (!$config->is_ready) {
            echo json_encode([
                'status' => 'error',
                'message' => 'MyCloud PMS configuration is incomplete.'
            ]);
            return;
        }

        $url        = $this->Mycloud_config_model->get_endpoint('searchbookings');
        $authCode   = $config->auth_code;
        $clientId   = $config->client_id;

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

        $config = $this->Mycloud_config_model->get_runtime_config();
        if (!$config->is_ready) {
            echo json_encode([
                'status' => false,
                'message' => 'MyCloud PMS configuration is incomplete. Please contact the administrator.'
            ]);
            return;
        }

        $requestData['chain_code'] = $config->chain_code;

        $apiResponse = $this->getRoomRateAvailability($requestData); // your previous function

        // Convert JSON string → array
        $responseArray = json_decode($apiResponse);

        echo json_encode([
            "status" => true,
            "data" => $responseArray
        ]);
        exit;
    }



    public function getRoomRateAvailability($requestData)
    {
        $config = $this->Mycloud_config_model->get_runtime_config();
        if (!$config->is_ready) {
            return json_encode([
                'status_code' => '500',
                'message' => 'MyCloud PMS configuration is incomplete.'
            ]);
        }

        // Convert array to JSON
        $jsonData = json_encode($requestData);

        // API URL
        $url = $this->Mycloud_config_model->get_endpoint('getroomrateavailability');

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
                "authCODE: {$config->auth_code}",
                "clientID: {$config->client_id}"
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


    /* ================= FETCH SALES VISITS ================= */
    public function fetch()
    {
        $this->db->select('sv.*, c.company_name');
        $this->db->from('sales_visits sv');
        $this->db->join('companies c', 'c.company_id = sv.company_id', 'left');
        $this->db->where('sv.status', 1);
        $this->db->where('sv.is_deleted', 0);
        $this->db->order_by('sv.visit_id', 'DESC');

        $data['sales_visits'] = $this->db->get()->result();

        $html = $this->load->view('super_admin/sales_visits/_forms_table', $data, true);
        echo $html;
    }

    /* ================= GET SALES VISIT DETAILS ================= */
    public function getDetails()
    {
        $id = $this->input->post('id');

        $this->db->where('visit_id', $id);
        $this->db->where('status', 1);
        $this->db->where('is_deleted', 0);
        $visit = $this->db->get('sales_visits')->row();

        if ($visit) {
            echo json_encode([
                'status' => 'success',
                'data'   => $visit
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Sales visit not found'
            ]);
        }
    }

    /* ================= UPDATE SALES VISIT ================= */
    /* ================= UPDATE SALES VISIT ================= */
    public function update($visit_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $validationError = $this->validateSalesVisitFields();
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $visit_id = $this->decodeId($visit_id);
        $sales_visit = $this->db
            ->where('visit_id', $visit_id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get('sales_visits')
            ->row();

        if (!$sales_visit) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid sales visit']);
            return;
        }

        $property   = $this->decodeId($this->input->post('property')) ?: $sales_visit->property;
        $type       = $this->decodeId($this->input->post('type', true)) ?: $sales_visit->type;
        $companyId  = $this->decodeId($this->input->post('company_id')) ?: $sales_visit->company_id;
        $personMet  = $this->decodeId($this->input->post('person_met')) ?: $sales_visit->person_met;
        $travelMode = $this->decodeId($this->input->post('travel_mode')) ?: $sales_visit->travel_mode;

        $personMetdata = $this->Common_model->getdata(
            'company_contacts',
            ['contact_id' => $personMet, 'company_id' => $companyId, 'status' => 'Active', 'is_deleted' => 0]
        );

        $hotel_data = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property, 'is_deleted' => 0]);
        $department_data = $this->Common_model->getdata('departments', ['department_id' => $type, 'is_deleted' => 0]);
        $company = $this->Common_model->getdata('companies', ['company_id' => $companyId, 'status' => 1, 'is_deleted' => 0]);
        $travelModeData = empty($travelMode) ? true : $this->Common_model->getdata('travel_modes', ['id' => $travelMode, 'is_deleted' => 0]);

        if (empty($property) || empty($type) || empty($companyId) || empty($personMet) || empty($personMetdata) || empty($hotel_data) || empty($department_data) || empty($company) || empty($travelModeData)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid sales visit selection']);
            return;
        }

        $name          = trim($personMetdata->first_name . ' ' . $personMetdata->last_name);
        $mobile_number = $personMetdata->mobile_number;
        $email         = $personMetdata->email;

        $lead_id = $sales_visit->lead_id_againts_visit;

        $escalation_level_1 = $department_data->escalation_level_1;

        $minutes_to_add = $escalation_level_1 * 60;
        $current_time   = new DateTime();
        $current_time->modify("+$minutes_to_add minutes");

        $follow_up_time  = $current_time->format('Y-m-d H:i:s');
        $follow_up_level = 1;

        $assigned_role = $this->session->userdata('role_as');

        if ($assigned_role == 'super_admin') {
            $userId = $this->session->userdata('super_admin_session')['id'];
        } elseif ($assigned_role == 'admin') {
            $userId = $this->session->userdata('hotel_admin_session')['id'];
        } else {
            $userId = $this->session->userdata('agent_session')['id'];
        }

        $leadData = [
            'user_name'         => $name,
            'phone_number'      => $mobile_number,
            'email'             => $email,
            'date'              => date('d-m-Y'),
            'time'              => $this->input->post('time', true),
            'user_channel'      => $this->input->post('user_channel', true),
            'property'          => $property,
            'type'              => $type,
            'status'            => $this->input->post('status', true),
            'disposition'       => $this->input->post('disposition', true),
            'query'             => $this->input->post('query', true),
            'remark'            => $this->input->post('remarks', true),
            'lead_type'         => $this->input->post('lead_type', true),
            'city'              => $hotel_data->city_id ?? null,
            'follow_up_time'    => $follow_up_time,
            'follow_up_level'   => $follow_up_level,
            'template_name'     => 'Phone',
            'created_by'        => $userId,
            'creator_user_role' => $assigned_role,
            'created_at'        => date('Y-m-d H:i:s')
        ];

        $status      = $this->input->post('status', true);
        $disposition = $this->input->post('disposition', true);
        $department  = $this->input->post('leadDepartment', true);

        if ($status === 'Closed') {
            $leadData['completed_time'] = date('Y-m-d H:i:s');
        } else {
            $leadData['responded_time'] = date('Y-m-d H:i:s');
        }

        if ($disposition === 'Reservation' && strtolower($status) === 'closed') {

            $department = strtolower($department);

            if ($department === 'rooms') {

                $dates       = $this->input->post('rate_date');
                $rate_types  = $this->input->post('rate_type');
                $room_prices = $this->input->post('room_price');

                $room_rates = [];

                if (!empty($dates)) {
                    foreach ($dates as $key => $dt) {
                        $date_parts     = explode('-', $dt);
                        $formatted_date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];

                        $room_rates[] = [
                            "date"           => $formatted_date,
                            "rate_type_code" => isset($rate_types[$key]) ? $rate_types[$key] : "",
                            "room_price"     => isset($room_prices[$key]) ? $room_prices[$key] : "0"
                        ];
                    }
                }

                $insert_data_room_rates = $room_rates;

                $confirmationNumber = $this->generateConfirmationNumber();

                $hotel_code_Data = $this->LeadModel->getHotelCodeByProperty($property);
                $hotel_code      = $hotel_code_Data->hotel_code;

                if (!empty($hotel_code)) {

                    $myCloudResponse = $this->send_booking_to_mycloud($requestBody);
                    $myCloudResponse = json_decode($myCloudResponse['response'], true);

                    if ($myCloudResponse['status_code'] == "200") {
                        $leadData['confirmation_number'] =
                            $myCloudResponse['bookings'][0]['pms_confirmation_number'];

                        $savebooking = $this->saveLeadBookings($id, $myCloudResponse);
                    } else {
                        $this->jsonResponse([
                            'status'        => false,
                            'error_code'    => $myCloudResponse['error']['ErrorCode'],
                            'error_message' => $myCloudResponse['error']['ErrorDescription']
                        ]);
                        return;
                    }
                }

                $leadData['completed_time'] = date('Y-m-d H:i:s');
                $leadData['checkin_date']   = $this->input->post('checkin_date');
                $leadData['checkin_time']   = $this->input->post('checkin_time');
                $leadData['checkout_date']  = $this->input->post('checkout_date');
                $leadData['checkout_time']  = $this->input->post('checkout_time');
                $leadData['roomtype']       = $this->input->post('roomtype');
                $leadData['number_of_rooms'] = $this->input->post('number_of_rooms');
                $leadData['pax']            = $this->input->post('pax');
                $leadData['adults']         = $this->input->post('adults');
                $leadData['kids']           = $this->input->post('kids');
            }

            if ($department === 'restaurants') {
                $leadData['booking_date']   = $this->input->post('booking_date');
                $leadData['pax']            = $this->input->post('pax');
                $leadData['amount']         = $this->input->post('amount');
                $leadData['arrival_time']   = $this->input->post('arrival_time');
                $leadData['restaurant_id']  = $this->decodeId($this->input->post('restaurant_id'));
                $leadData['slot_type_id']   = $this->decodeId($this->input->post('slot_type_id'));
            }

            if ($department === 'banquets') {
                $leadData['booking_date']  = $this->input->post('booking_date');
                $leadData['pax']           = $this->input->post('pax');
                $leadData['amount']        = $this->input->post('amount');
                $leadData['banquet_email'] = $this->input->post('banquet_email');
            }
        } else {
            $leadData['amount'] = 0;
        }

        if (
            strpos(strtolower($disposition), 'shopping - follow up') !== false &&
            strtolower($status) === 'in progress'
        ) {
            $leadData['booking_enquiry_date'] = $this->input->post('booking_enquiry_date');
            $leadData['followup_date']        = $this->input->post('followup_date');
            $leadData['second_followup_date'] = $this->input->post('second_followup_date');
            $leadData['followup_remark']      = $this->input->post('followup_remark');

            if ($department === 'banquets') {
                $leadData['transfer_to_manager'] =
                    $this->input->post('transfer_to_manager');
            }
        }

        if ($disposition === 'Denied' && strtolower($status) === 'closed') {
            $leadData['checkin_date']  = $this->input->post('checkin_date');
            $leadData['checkout_date'] = $this->input->post('checkout_date');
        }

        $this->db->where('id', $lead_id)->update('leads', $leadData);

        $visitData = [
            'user_id'            => $userId,
            'report_date'        => $this->input->post('report_date'),
            'company_id'         => $companyId,
            'person_met'         => $personMet,
            'agenda'             => $this->input->post('agenda'),
            'discussion_summary' => $this->input->post('discussion_summary'),
            'conclusion'         => $this->input->post('conclusion'),
            'area_covered'       => $this->input->post('area_covered'),
            'travel_mode'        => $travelMode,
            'kms_run'            => $this->input->post('kms_run'),
            'rate_per_km'        => $this->input->post('rate_per_km'),
            'parking_charges'    => $this->input->post('parking_charges'),
            'lunch'              => $this->input->post('lunch'),
            'entertainment'      => $this->input->post('entertainment'),
            'total_amount'       => $this->input->post('total_amount'),
            'property'           => $property,
            'type'               => $type,
            'remarks'            => $this->input->post('remarks'),
            'updated_at'         => date('Y-m-d H:i:s')
        ];

        $visitUpdated = $this->db
            ->where('visit_id', $visit_id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->update('sales_visits', $visitData);

        if (!$visitUpdated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update sales visit']);
            return;
        }

        $this->logActivity('update', $visit_id, 'Updated sales visit for company ID '.$companyId);

        $this->jsonResponse([
            'status'  => true,
            'message' => 'Sales visit & lead updated successfully'
        ]);
    }



    /* ================= DELETE SALES VISIT ================= */
    public function delete()
    {
        $id = $this->decodeId($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid visit ID'
            ]);
            return;
        }

        $visit = $this->db
            ->where('visit_id', $id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get('sales_visits')
            ->row();

        if (empty($visit)) {
            $this->jsonResponse(['status' => false, 'message' => 'Sales visit not found or already deleted']);
            return;
        }

        $deleteQuery = $this->db
            ->where('visit_id', $id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->update('sales_visits', ['is_deleted' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
        $deleted = $deleteQuery && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity('delete', $id, 'Soft deleted sales visit for company ID '.$visit->company_id);
            $this->jsonResponse([
                'status' => true,
                'message' => 'Sales visit deleted successfully'
            ]);
        } else {
            $this->jsonResponse([
                'status' => false,
                'message' => $deleteQuery ? 'Sales visit not found or already deleted' : 'Unable to delete sales visit'
            ]);
        }
    }

    public function get_company_contacts()
    {
        $company_id = $this->decodeId($this->input->post('company_id'));
        $selectedContactToken = $this->input->post('selected_contact_id');
        $selectedContactId = $this->decodeId($selectedContactToken);

        $company = !empty($company_id)
            ? $this->Common_model->getdata('companies', [
                'company_id' => $company_id,
                'status' => 1,
                'is_deleted' => 0
            ])
            : null;

        if (empty($company_id) || empty($company)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid Company'
            ]);
            return;
        }

        $contacts = $this->db
            ->select('contact_id, first_name, last_name,mobile_number')
            ->from('company_contacts')
            ->where('company_id', $company_id)
            ->where('is_deleted', 0)
            ->where('status', 'Active')
            ->order_by('first_name', 'ASC')
            ->get()
            ->result();

        foreach ($contacts as $contact) {
            $contact->contact_id = (!empty($selectedContactId) && $selectedContactId == $contact->contact_id)
                ? $selectedContactToken
                : encrypt_id($contact->contact_id);
        }

        if (!empty($contacts)) {
            $this->jsonResponse([
                'status' => 'success',
                'data'   => $contacts
            ]);
        } else {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'No contacts found'
            ]);
        }
    }


    public function getCalendarVisits()
    {
        $visits = $this->LeadModel->getCalendarVisits();

        $events = [];

        foreach ($visits as $v) {
            $events[] = [
                'id'    => encrypt_id($v->visit_id),
                'title' => 'Visit | ' . $v->company_name,
                'start' => date('Y-m-d', strtotime($v->report_date)),
                'color' => '#00a65a'
            ];
        }

        echo json_encode($events);
    }


    public function getVisitDetails()
    {
        $visit_id = $this->decodeId($this->input->post('visit_id') ?: $this->input->get('visit_id'));




        $this->db->select('
    sv.*,

    c.company_name,

    cc.first_name,
    cc.last_name,

    su.full_name AS sales_user_name,

    l.*,
   
');

        $this->db->from('sales_visits sv');

        $this->db->join('companies c', 'c.company_id = sv.company_id', 'left');
        $this->db->join('company_contacts cc', 'cc.contact_id = sv.person_met', 'left');
        $this->db->join('sales_users su', 'su.id = sv.user_id', 'left');

        /* 🔗 Leads Join */
        $this->db->join('leads l', 'l.id = sv.lead_id_againts_visit', 'left');

        $this->db->where('sv.status', 1);
        $this->db->where('sv.is_deleted', 0);
        $this->db->where('sv.visit_id', $visit_id);
        $this->db->order_by('sv.report_date', 'DESC');

        $data['visit'] = $this->db->get()->row();

        $this->load->view('super_admin/sales_visits/visit_details_modal', $data);
    }
}

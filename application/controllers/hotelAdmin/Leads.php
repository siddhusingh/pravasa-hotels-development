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

        if (empty($this->session->userdata('hotel_admin_session'))) {
            return redirect('hotel-admin-login');
        }
    }



    public function index()
    {

        $hotel_session = $this->session->userdata('hotel_admin_session');

        $property = (int) ($hotel_session['id'] ?? 0);
        $staff_id = max(0, (int) $this->input->get('staff_id'));
        $scoped_staff = $staff_id > 0 ? $this->getHotelStaffMember($staff_id, $property) : null;

        if ($staff_id > 0 && !$scoped_staff) {
            return show_error('The selected staff member is not assigned to this hotel.', 403);
        }

        $filters = [
            'department' => $this->input->get('department'),
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'disposition' => $this->input->get('disposition'), // 🆕 Added Stage filter

            'phone' => $this->input->get('phone') // 🆕 Added Stage filter


        ];


        if ($staff_id === 0 && empty($filters['status']) && count(array_filter($filters)) === 0) {
            $filters['status'] = 'Open';
        }



        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        if ($scoped_staff) {
            $activeFilters['assigned_id'] = $staff_id;
            $activeFilters['assigned_role'] = 'agent';
        }

        $data['leads'] = $this->LeadModel->get_hotel_leads($property, $activeFilters);

        // Render the hotel-owned report UI while keeping this account scoped to its hotel.
        $data['hotel_lead_view'] = true;
        $data['fixed_property_id'] = (int) $property;
        $data['scoped_staff_id'] = $staff_id;
        $data['scoped_staff_name'] = $scoped_staff['name'] ?? '';
        $data['creators'] = $this->LeadModel->get_active_creators();
        $data['assigned_users'] = $this->LeadModel->get_active_assigned_users();

        if ($scoped_staff) {
            $staff_in_options = false;
            foreach ($data['assigned_users'] as $assigned_user) {
                if ((int) $assigned_user->id === $staff_id && $assigned_user->role === 'agent') {
                    $staff_in_options = true;
                    break;
                }
            }
            if (!$staff_in_options) {
                $data['assigned_users'][] = (object) [
                    'id' => $staff_id,
                    'role' => 'agent',
                    'name' => $scoped_staff['name'],
                ];
            }
        }

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');

        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['properties'] = $fixed_property ? [$fixed_property] : [];

        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');

        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');


        $data['all_assignable_users'] = $this->getActiveAssignableUsers();

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');

        $activeFilters['property'] = $property;
        $data['lead_status_counts'] = $this->LeadModel->get_lead_counts_grouped_by_status($activeFilters);





        $data['airtel_config'] = $this->Airtel_config_model->get_runtime_config();
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/lead_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    /**
     * Load report cards for the logged-in hotel's fixed property.
     * Never trust a posted property ID on a hotel-admin request.
     */
    public function fetch_leads_ajax()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        if ($property <= 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['status' => false, 'message' => 'Hotel session expired.']));
        }

        $staff_id = max(0, (int) $this->input->post('staff_id'));
        $scoped_staff = $staff_id > 0 ? $this->getHotelStaffMember($staff_id, $property) : null;

        if ($staff_id > 0 && !$scoped_staff) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'The selected staff member is not assigned to this hotel.',
                    'csrfHash' => $this->security->get_csrf_hash(),
                ]));
        }

        $filters = [
            'property' => [$property],
            'department' => $this->input->post('department'),
            'status' => $this->input->post('status'),
            'channel' => $this->input->post('channel'),
            'disposition' => $this->input->post('disposition'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'search' => $this->input->post('search', true),
            'phone' => $this->input->post('phone'),
            'business_type' => $this->input->post('business_type'),
            'created_id' => $this->input->post('created_id'),
            'created_role' => $this->input->post('created_role'),
            'assigned_id' => $scoped_staff ? $staff_id : $this->input->post('assigned_id'),
            'assigned_role' => $scoped_staff ? 'agent' : $this->input->post('assigned_role'),
            'showfollowupleads' => $this->input->post('showfollowupleads')
        ];

        // Staff/department drill-downs start with all statuses; explicit status filters still win.
        if (empty($filters['status']) && ($scoped_staff || !empty($filters['department']))) {
            $status_query = $this->db
                ->distinct()
                ->select('status')
                ->from('leads')
                ->where('property', $property)
                ->where('is_deleted', 0);

            if ($scoped_staff) {
                $status_query
                    ->where('assigned_to', $staff_id)
                    ->where('assigned_person_user_role', 'agent');
            }

            if (!empty($filters['department'])) {
                $departments = is_array($filters['department'])
                    ? $filters['department']
                    : [$filters['department']];
                $status_query->where_in('type', $departments);
            }

            $status_rows = $status_query->get()->result_array();
            $filters['status'] = array_values(array_filter(array_column($status_rows, 'status')));
        }

        $limit = min(max((int) $this->input->post('limit'), 1), 100);
        $offset = max((int) $this->input->post('offset'), 0);
        $leads = $this->LeadModel->get_filtered_leads($filters, $limit, $offset);
        $total_counts = $this->LeadModel->get_leads_status_counts($filters);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'html' => $this->load->view('ajax_leads_cards', ['leads' => $leads], true),
                'count' => count($leads),
                'totalCounts' => $total_counts,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }


    public function followups()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);

        if ($property <= 0) {
            return redirect('hotel-admin-login');
        }

        $filters = [
            'department' => $this->input->get('department'),
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'disposition' => $this->input->get('disposition'),
            'phone' => $this->input->get('phone')
        ];

        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });
        $activeFilters['showfollowupleads'] = 'yes';
        $activeFilters['property'] = [$property];

        // Use the same due-follow-up query as the AJAX report, fixed to this hotel.
        $data['leads'] = $this->LeadModel->get_filtered_leads($activeFilters, 50, 0);

        // Render the hotel-owned lead UI and routes.
        $data['hotel_lead_view'] = true;
        $data['fixed_property_id'] = $property;
        $data['creators'] = $this->LeadModel->get_active_creators();
        $data['assigned_users'] = $this->LeadModel->get_active_assigned_users();

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');

        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['properties'] = $fixed_property ? [$fixed_property] : [];

        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');

        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');

        $data['all_assignable_users'] = $this->getActiveAssignableUsers();

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');

        $data['lead_status_counts'] = $this->LeadModel->get_leads_status_counts($activeFilters);
        $data['showfollowupleads'] = 'yes';

        $data['airtel_config'] = $this->Airtel_config_model->get_runtime_config();
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/lead_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    public function customer_lead_history_hotel()
    {

        $hotel_session = $this->session->userdata('hotel_admin_session');

        $property = $hotel_session['id'];

        $filters = [
            'department' => $this->input->get('department'),
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
        ];

        $phone = $this->uri->segment('2');






        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        $data['leads'] = $this->LeadModel->get_leads_history_hotel($property, $activeFilters, $phone);

        // Populate dropdown values
        $data['departments'] = $this->Common_model->getAllData('departments', '');



        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');

        $activeFilters['property'] = $property;
        $data['lead_status_counts'] = $this->LeadModel->get_lead_counts_grouped_by_status($activeFilters);







        $data['airtel_config'] = $this->Airtel_config_model->get_runtime_config();
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/lead_report', $data);
        $this->load->view('hotel_admin/include/footer');
    }



    public function getByHotel()
    {
        $hotel_id = $this->input->post('hotel_id');

        $restaurants = $this->db
            ->where('hotel_id', $hotel_id)
            ->where('status', 1)
            ->get('hotel_restaurants')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $restaurants
        ]);
    }


    public function add_lead()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');

        $property = $hotel_session['id'];
        $department_id = $this->input->get('department_id');
        $data['leads'] = $this->LeadModel->get_leads();
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $fixed_property = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $data['hotel_admin'] = $fixed_property ? [$fixed_property] : [];
        $data['selected_property'] = (int) $property;
        $data['selected_department'] = $department_id;

        $data['roomtype'] = $this->Common_model->getAllData('roomtype', '');

        $data['ratetype'] = $this->Common_model->getAllData('ratetype', '');

        $data['all_assignable_users'] = $this->getActiveAssignableUsers();


        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/add_lead', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    /**
     * Active users from all assignable roles. Deliberately not property-filtered.
     */
    private function getHotelStaffMember($staffId, $property)
    {
        return $this->db
            ->select('staff_members.id, staff_members.name, staff_members.email, staff_members.phone')
            ->from('staff_members')
            ->join('staff_hotel_department_mapping mapping', 'mapping.staff_id = staff_members.id', 'inner')
            ->where('staff_members.id', (int) $staffId)
            ->where('mapping.hotel_id', (int) $property)
            ->where('staff_members.is_deleted', 0)
            ->group_by('staff_members.id')
            ->get()
            ->row_array();
    }

    private function getActiveAssignableUsers()
    {
        $hotel_admins = $this->db
            ->select("id, name, email, 'admin' AS user_role", false)
            ->from('hotel_admins')
            ->where('status', 1)
            ->get()
            ->result_array();

        $staff_members = $this->db
            ->select("id, name, email, 'agent' AS user_role", false)
            ->from('staff_members')
            ->where('status', 1)
            ->get()
            ->result_array();

        $super_admins = $this->db
            ->select("id, full_name AS name, email, 'super_admin' AS user_role", false)
            ->from('super_admin')
            ->where('status', 'active')
            ->get()
            ->result_array();

        $all_users = array_merge($hotel_admins, $staff_members, $super_admins);
        usort($all_users, static function ($first, $second) {
            return strcasecmp($first['name'], $second['name']);
        });

        return $all_users;
    }

    /**
     * Resolve an assignee from the same active-only role rules used by the form.
     */
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

        $phone = preg_replace('/\D+/', '', $value('phone_number'));
        $phone = substr($phone, -10);
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
                $restaurant_required = [
                    'restaurant_id' => 'Please select a restaurant.',
                    'slot_type_id' => 'Please select a slot type.',
                    'time_slot_id' => 'Please select a time slot.',
                    'table_category_id' => 'Please select a table category.',
                    'table_reservation_status' => 'Please select a reservation status.'
                ];
                foreach ($restaurant_required as $field => $message) {
                    if ($value($field) === '') {
                        $errors[$field] = $message;
                    }
                }

                $table_ids = $this->input->post('table_id');
                if (empty($table_ids) || (is_array($table_ids) && count(array_filter($table_ids)) === 0)) {
                    $errors['table_id'] = 'Please select at least one table.';
                }
            }
        }

        return $errors;
    }

    public function insert_lead()
    {
        if ($this->input->method() === 'post') {


            $hotel_session = $this->session->userdata('hotel_admin_session');

            $property = $hotel_session['id'];



            $result = $this->Common_model->getdata('hotel_admin', array('hotel_id' => $property));
            $type = $this->input->post('type', true);
            $department_data = $this->Common_model->getdata('departments', ['department_id' => $type]);
            $errors = $this->validateLeadInput($department_data->department_name ?? '');

            if (!$result) {
                $errors['property'] = 'The Hotel assigned to this account is unavailable.';
            }
            if (!$department_data) {
                $errors['type'] = 'Please select a valid department.';
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

            $assigned_role = $this->session->userdata('role_as');

            if ($assigned_role == 'super_admin') {
                $userId = $this->session->userdata('super_admin_session')['id'];
            } else if ($assigned_role == 'admin') {
                $userId = $this->session->userdata('hotel_admin_session')['id'];
            } else {
                $userId = $this->session->userdata('agent_session')['id'];
            }


            // Collect each field from POST manually
            $leadData = [
                'user_name'       => $this->input->post('user_name', true),
                'phone_number'   => $this->input->post('phone_number', true),
                'email'          => $this->input->post('email', true),
                'date'           => $this->input->post('date', true),
                'time'           => $this->input->post('time', true),
                'user_channel'   => $this->input->post('user_channel', true),
                'property'       => $property,
                'type'           => $type,
                'status'    => $this->input->post('status', true),
                'disposition'    => $this->input->post('disposition', true),
                'created_at'   =>  date('Y-m-d H:i:s'),
                'query'          => $this->input->post('query', true),
                'lead_type'          => $this->input->post('lead_type', true),

                'purpose'        => $this->input->post('purpose', true),
                'reason'         => $this->input->post('reason', true),
                'promotional_offers' => $this->input->post('promotional_offers'),

                'remark'         => $this->input->post('remark', true),
                'template_name' => 'Phone',
                'created_by' => $userId,
                'creator_user_role' => $assigned_role,

                'city' => $result->city_id
            ];

            $escalation_hours = (float) ($department_data->escalation_level_1 ?? 0);
            $leadData['esc_next_followup_at'] = date('Y-m-d H:i:s', strtotime('+' . ($escalation_hours * 60) . ' minutes'));
            $leadData['esc_follow_up_level'] = 1;

            // Keep the dynamic field support aligned with the current add-lead form.
            $optional_fields = [
                'booking_enquiry_date', 'booking_date', 'booking_month',
                'followup_date', 'second_followup_date', 'followup_remark',
                'arrival_time', 'checkin_date', 'checkin_time', 'checkout_date',
                'checkout_time', 'roomtype', 'number_of_rooms', 'pax', 'adults',
                'kids', 'meal_plan', 'revenue_fnb', 'revenue_other', 'revenue_room',
                'amount', 'reservation_number', 'reservation_email', 'fnb_email',
                'banquet_email', 'sitting_style', 'audio_visual', 'btr', 'banquet_id',
                'restaurant_id', 'slot_type_id', 'time_slot_id', 'table_category_id',
                'table_reservation_status', 'special_occasion', 'special_request',
                'transfer_to_manager'
            ];

            foreach ($optional_fields as $field) {
                $field_value = $this->input->post($field);
                if ($field_value !== null && $field_value !== '') {
                    $leadData[$field] = $field_value;
                }
            }

            $table_ids = $this->input->post('table_id');
            if (is_array($table_ids)) {
                $table_ids = array_filter($table_ids, static function ($value) {
                    return $value !== null && $value !== '';
                });
                if (!empty($table_ids)) {
                    $leadData['table_id'] = implode(',', $table_ids);
                }
            } elseif ($table_ids !== null && $table_ids !== '') {
                $leadData['table_id'] = $table_ids;
            }

            $assigned_to = $this->input->post('assigned_to', true);
            if ($assigned_to !== null && $assigned_to !== '') {
                $leadData['is_assigned'] = 1;
                $leadData['assigned_to'] = $assigned_to;
                $leadData['assigned_person_user_role'] = $this->input->post('assigned_person_user_role', true);
                $leadData['assigned_person_email'] = $this->input->post('assigned_person_email', true);
            }


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


            // Time tracking
            if ($status === 'Closed') {
                $leadData['completed_time'] = date('Y-m-d H:i:s');
            } else {
                $leadData['responded_time'] = date('Y-m-d H:i:s');
            }

            // Additional fields for Reservation Closed
            if ($disposition === 'Reservation' && strtolower($status) === 'closed') {
                $department = strtolower($department); // convert everything to lowercase

                if ($department === 'rooms') {
                    $leadData['checkin_date'] = $this->input->post('checkin_date');
                    $leadData['checkout_date'] = $this->input->post('checkout_date');
                    $leadData['pax'] = $this->input->post('pax');
                    $leadData['amount'] = $this->input->post('amount');
                    $leadData['reservation_number'] = $this->input->post('reservation_number');
                    $leadData['reservation_email'] = $this->input->post('reservation_email');

                    // Handle file upload
                    if (!empty($_FILES['bill_attachment']['name'])) {
                        $uploadPath = FCPATH . 'uploads/bills/';

                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }

                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                        $fileName = $_FILES['bill_attachment']['name'];
                        $tmpName = $_FILES['bill_attachment']['tmp_name'];
                        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        if (!in_array($extension, $allowedExtensions)) {
                            echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, PDF allowed.']);
                            return;
                        }

                        $newFileName = 'bill_' . time() . '.' . $extension;
                        $targetPath = $uploadPath . $newFileName;

                        if (move_uploaded_file($tmpName, $targetPath)) {
                            $leadData['bill_attachment'] = $newFileName;
                        } else {
                            echo json_encode(['error' => 'Failed to move uploaded file.']);
                            return;
                        }
                    }
                }

                if ($department === 'restaurants') {
                    $leadData['booking_date'] = $this->input->post('booking_date');
                    $leadData['pax'] = $this->input->post('pax');
                    $leadData['amount'] = $this->input->post('amount');
                    $leadData['fnb_email'] = $this->input->post('fnb_email');
                }

                if ($department === 'banquets') {
                    $leadData['booking_date'] = $this->input->post('booking_date');
                    $leadData['pax'] = $this->input->post('pax');
                    $leadData['amount'] = $this->input->post('amount');
                    $leadData['banquet_email'] = $this->input->post('banquet_email');
                }
            }

            // Shopping - Follow up - In Progress
            if (strpos(strtolower($disposition), 'shopping - follow up') !== false && strtolower($status) === 'in progress') {
                $leadData['booking_enquiry_date'] = $this->input->post('booking_enquiry_date');
                $leadData['followup_date'] = $this->input->post('followup_date');
                $leadData['second_followup_date'] = $this->input->post('second_followup_date');
                $leadData['followup_remark'] = $this->input->post('followup_remark');

                if ($department === 'banquets') {
                    $leadData['transfer_to_manager'] = $this->input->post('transfer_to_manager');
                }
            }



            $phone = $this->input->post('phone_number', true);
            $last_10_digits = substr(preg_replace('/\D+/', '', (string) $phone), -10);
            $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));

            // find last lead created in last 2 hours with same phone number
            $existingLead = $this->db
                ->where("RIGHT(phone_number, 10) =", $last_10_digits, false)
                ->where('property', $property)
                ->where('created_at >=', $twoHoursAgo)
                ->where('status !=', 'Closed')
                ->where('is_deleted', 0)
                ->order_by('id', 'DESC')
                ->get('leads')
                ->row();

            if (!empty($existingLead)) {

                $wasReassigned = $assigned_to !== null && $assigned_to !== '' && (
                    (int) $existingLead->assigned_to !== (int) $assigned_to ||
                    (string) $existingLead->assigned_person_user_role !== (string) $this->input->post('assigned_person_user_role', true)
                );

                // Do not change original creation timestamp
                unset($leadData['created_at']);

                // Update existing lead with fresh details
                $updated = $this->db->where('id', $existingLead->id)
                    ->update('leads', $leadData);

                if ($updated) {
                    $this->triggerAssignedLeadEmail(
                        (int) $existingLead->id,
                        $last_10_digits,
                        $wasReassigned ? 'reassigned' : 'updated'
                    );
                }

                echo json_encode([
                    'status' => true,
                    'message' => 'Duplicate detected: Existing lead updated successfully.',
                    'duplicate' => true,
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
                return; // stop here, no email, no new lead creation
            }

            // Insert data into DB
            // Insert into DB
            $insert_id = $this->LeadModel->insert_lead($leadData);

            $leadData['created_at'] = date('Y-m-d H:i:s');


            $phone = $leadData['phone_number'];

            // Check if any previous lead has reservation with revenue > 0
            $valuableGuest = $this->db
                ->select('id, disposition, amount')  // revenue_amount = your amount column
                ->from('leads')
                ->where('is_deleted', 0)
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
                echo json_encode([
                    'status' => true,
                    'message' => 'Lead created successfully.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to insert lead.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]);
            }
        } else {
            show_404();
        }
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

    /**
     * Return edit-modal data only when the lead belongs to the logged-in hotel.
     */
    public function get_lead_details()
    {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property = (int) ($hotel_session['id'] ?? 0);
        $lead_id = (int) $this->input->post('lead_id');

        if ($property <= 0 || $lead_id <= 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'A valid lead is required.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $lead = $this->db
            ->where('id', $lead_id)
            ->where('property', $property)
            ->where('is_deleted', 0)
            ->get('leads')
            ->row();

        if (!$lead) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Lead not found for this hotel.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'data' => $lead,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }

    public function edit_lead($id)
    {
        $lead = $this->Comman_model->getData('leads', ['id' => $id]);



        $data['lead'] = $lead;
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', '');

        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/edit_lead', $data);
        $this->load->view('hotel_admin/include/footer');
    }

    public function update_lead()
    {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $hotel_session = $this->session->userdata('hotel_admin_session');
        $session_property = (int) ($hotel_session['id'] ?? 0);
        $id = (int) $this->input->post('lead_id');

        $lead = $this->db
            ->where('id', $id)
            ->where('property', $session_property)
            ->where('is_deleted', 0)
            ->get('leads')
            ->row();

        if (!$lead) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Lead not found for this hotel.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        // Property is fixed by the hotel session and is never taken from POST.
        $property = $session_property;
        $type = (int) $this->input->post('type');
        $status = trim((string) $this->input->post('status', true));
        $disposition = trim((string) $this->input->post('disposition', true));

        $hotel_data = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $department_data = $this->Common_model->getdata('departments', ['department_id' => $type]);
        $errors = $this->validateLeadInput($department_data->department_name ?? '');

        if (!$hotel_data) {
            $errors['property'] = 'The Hotel assigned to this account is unavailable.';
        }
        if (!$department_data) {
            $errors['type'] = 'Please select a valid department.';
        }

        $assigned_to = trim((string) $this->input->post('assigned_to', true));
        $assigned_role = trim((string) $this->input->post('assigned_person_user_role', true));
        $active_assignee = null;

        if ($assigned_to !== '') {
            $active_assignee = $this->getActiveAssignableUser($assigned_to, $assigned_role);
            if (!$active_assignee) {
                $errors['assigned_to'] = 'Please select an active user.';
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

        $leadData = [
            'user_name' => $this->input->post('user_name', true),
            'phone_number' => $this->input->post('phone_number', true),
            'email' => $this->input->post('email', true),
            'user_channel' => $this->input->post('user_channel', true),
            'property' => $property,
            'type' => $type,
            'status' => $status,
            'disposition' => $disposition,
            'query' => $this->input->post('query', true),
            'remark' => $this->input->post('remark', true),
            'lead_type' => $this->input->post('lead_type', true),
            'purpose' => $this->input->post('purpose', true),
            'reason' => $this->input->post('reason', true),
            'promotional_offers' => $this->input->post('promotional_offers'),
            'city' => $hotel_data->city_id ?? null,
            'updated_on' => date('Y-m-d H:i:s')
        ];

        foreach (['date', 'time'] as $field) {
            $field_value = $this->input->post($field);
            if ($field_value !== null) {
                $leadData[$field] = $field_value;
            }
        }

        $optional_fields = [
            'booking_enquiry_date', 'booking_date', 'booking_month',
            'followup_date', 'second_followup_date', 'followup_remark',
            'arrival_time', 'checkin_date', 'checkin_time', 'checkout_date',
            'checkout_time', 'roomtype', 'number_of_rooms', 'pax', 'adults',
            'kids', 'meal_plan', 'revenue_fnb', 'revenue_other', 'revenue_room',
            'amount', 'reservation_number', 'reservation_email', 'fnb_email',
            'banquet_email', 'sitting_style', 'audio_visual', 'btr', 'banquet_id',
            'restaurant_id', 'slot_type_id', 'time_slot_id', 'table_category_id',
            'table_reservation_status', 'special_occasion', 'special_request',
            'transfer_to_manager'
        ];

        foreach ($optional_fields as $field) {
            $field_value = $this->input->post($field);
            if ($field_value !== null) {
                $leadData[$field] = $field_value;
            }
        }

        $table_ids = $this->input->post('table_id');
        if (is_array($table_ids)) {
            $leadData['table_id'] = implode(',', array_filter($table_ids, static function ($value) {
                return $value !== null && $value !== '';
            }));
        } elseif ($table_ids !== null) {
            $leadData['table_id'] = $table_ids;
        }

        if ($active_assignee) {
            $leadData['is_assigned'] = 1;
            $leadData['assigned_to'] = $active_assignee['id'];
            $leadData['assigned_person_user_role'] = $active_assignee['user_role'];
            $leadData['assigned_person_email'] = $active_assignee['email'];
        }

        if ($status === 'Closed') {
            $leadData['completed_time'] = date('Y-m-d H:i:s');
        } else {
            $leadData['responded_time'] = date('Y-m-d H:i:s');
        }

        if (!empty($_FILES['bill_attachment']['name'])) {
            $uploadPath = FCPATH . 'uploads/bills/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $extension = strtolower(pathinfo($_FILES['bill_attachment']['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'pdf'], true)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(422)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Invalid bill attachment.',
                        'errors' => ['bill_attachment' => 'Only JPG, PNG, or PDF files are allowed.'],
                        'csrfHash' => $this->security->get_csrf_hash()
                    ]));
            }

            $newFileName = 'bill_' . time() . '.' . $extension;
            if (!move_uploaded_file($_FILES['bill_attachment']['tmp_name'], $uploadPath . $newFileName)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Unable to upload the bill attachment.',
                        'csrfHash' => $this->security->get_csrf_hash()
                    ]));
            }
            $leadData['bill_attachment'] = $newFileName;
        }

        $updated = $this->Comman_model->UpdateRecord('leads', $leadData, [
            'id' => $id,
            'property' => $session_property
        ]);

        if (!$updated) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Unable to update lead.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $was_reassigned = $active_assignee && (
            (int) $lead->assigned_to !== (int) $active_assignee['id'] ||
            (string) $lead->assigned_person_user_role !== (string) $active_assignee['user_role']
        );

        $phone = substr(preg_replace('/\D+/', '', (string) $leadData['phone_number']), -10);
        $this->triggerAssignedLeadEmail(
            $id,
            $phone,
            $was_reassigned ? 'reassigned' : 'updated'
        );

        $updated_lead = $this->db
            ->select('leads.*, departments.department_name')
            ->from('leads')
            ->join('departments', 'leads.type = departments.department_id', 'left')
            ->where('leads.id', $id)
            ->where('leads.property', $session_property)
            ->where('leads.is_deleted', 0)
            ->get()
            ->row();

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Lead updated successfully.',
                'data' => $updated_lead,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }

    /**
     * Start the shared saved-SMTP notification without making a successful
     * Hotel Admin lead save depend on the mail server response.
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
                'Hotel Admin assigned lead email worker could not be started for lead ID ' .
                (int) $leadId . ': ' . curl_error($emailRequest)
            );
        }

        curl_close($emailRequest);
    }

}

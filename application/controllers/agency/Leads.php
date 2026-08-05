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


        if (empty($this->session->userdata('agency_session'))) {
            return redirect('agent-login');
        }
    }



    public function index()
    {


        $agency_id = $this->session->userdata('agency_session')['id'];

        $data['properties'] = $this->Common_model->get_properties_by_agency($agency_id);








        $data['departments'] = $this->Common_model->getAllData('departments', '');


        $data['airtel_config'] = $this->Airtel_config_model->get_runtime_config();
        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/lead_report', $data);
        $this->load->view('agency/include/footer');
    }


    public function customer_lead_history_agent()
    {



        $property = $this->session->userdata('selected_hotel_id');

        $filters = [
            'department' => $this->session->userdata('selected_department_id'),
            'status' => $this->input->get('status'),
            'channel' => $this->input->get('channel'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'disposition' => $this->input->get('disposition') // 🆕 Added Stage filter

        ];

        $phone = $this->uri->segment('2');






        $activeFilters = array_filter($filters, function ($val) {
            return $val !== null && $val !== '';
        });

        $data['leads'] = $this->LeadModel->get_leads_history_hotel($property, $activeFilters, $phone);

        $data['user_channel'] = $this->Common_model->getAlluser_channel('leads', '');

        $department = $this->session->userdata('selected_department_id');

        // Load all data to send to view
        $data['lead_status_counts'] = [
            'Open'        => $this->LeadModel->get_lead_count_by_status_agent('Open', $property, $department),
            'In Progress' => $this->LeadModel->get_lead_count_by_status_agent('In Progress', $property, $department),
            'On Hold'     => $this->LeadModel->get_lead_count_by_status_agent('On Hold', $property, $department),
            'Closed'      => $this->LeadModel->get_lead_count_by_status_agent('Closed', $property, $department)

        ];



        $data['airtel_config'] = $this->Airtel_config_model->get_runtime_config();
        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/lead_report', $data);
        $this->load->view('agency/include/footer');
    }

    public function add_lead()
    {
        $agencySession = $this->session->userdata('agency_session');
        $agencyId = (int) ($agencySession['id'] ?? 0);
        $properties = $this->Common_model->get_properties_by_agency($agencyId);
        $selectedProperty = (int) $this->session->userdata('selected_hotel_id');

        $allowedPropertyIds = array_map(static function ($property) {
            return (int) $property->hotel_id;
        }, $properties);

        if (!in_array($selectedProperty, $allowedPropertyIds, true)) {
            $selectedProperty = !empty($allowedPropertyIds) ? $allowedPropertyIds[0] : 0;
        }

        $data['leads'] = $this->LeadModel->get_leads();
        $data['departments'] = $this->Common_model->getAllData('departments', ['is_deleted' => 0]);
        $data['hotel_admin'] = $properties;
        $data['selected_property'] = $selectedProperty;
        $data['selected_department'] = null;
        $data['all_assignable_users'] = [];
        $data['lead_form_role_label'] = 'Agency';
        $data['lead_form_submit_url'] = base_url('insert-lead-agency');
        $data['lead_form_redirect_url'] = base_url('view-agency-leads');

        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/add_lead', $data);
        $this->load->view('agency/include/footer');
    }

    private function validateAgencyLeadInput()
    {
        $errors = [];
        $value = function ($field) {
            return trim((string) $this->input->post($field, true));
        };
        $phone = substr(preg_replace('/\D+/', '', $value('phone_number')), -10);
        $disposition = $value('disposition');
        $allowedStages = [
            'Not Contacted',
            'General Information',
            'Negotiations',
            'Contract Done',
            'Advance Received',
            'Lead Won',
            'Lead Lost'
        ];

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
            'property' => 'Please select a hotel.',
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

        if ($disposition === 'Quotation Sent') {
            $errors['disposition'] = 'Quotation Sent is not available for agency users.';
        } elseif ($disposition !== '' && !in_array($disposition, $allowedStages, true)) {
            $errors['disposition'] = 'Please select a valid stage.';
        }
        if ($disposition === 'Lead Lost' && $value('reason') === '') {
            $errors['reason'] = 'Please select a reason.';
        }

        return $errors;
    }

    public function insert_lead()
    {
        if ($this->input->method() !== 'post') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(['status' => false, 'message' => 'Method not allowed.']));
        }

        $agencySession = $this->session->userdata('agency_session');
        $agencyId = (int) ($agencySession['id'] ?? 0);
        $property = (int) $this->input->post('property', true);
        $type = (int) $this->input->post('type', true);
        $hotel = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $department = $this->Common_model->getdata('departments', [
            'department_id' => $type,
            'is_deleted' => 0
        ]);
        $errors = $this->validateAgencyLeadInput();

        $propertyMapping = $this->db
            ->where('agency_id', $agencyId)
            ->where('property_id', $property)
            ->get('agency_property_mapping')
            ->row_array();
        if (!$hotel || !$propertyMapping) {
            $errors['property'] = 'The selected hotel is not assigned to this agency.';
        }
        if (!$department) {
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

        $phone = substr(preg_replace('/\D+/', '', (string) $this->input->post('phone_number', true)), -10);
        $now = date('Y-m-d H:i:s');
        $leadData = [
            'user_name' => trim((string) $this->input->post('user_name', true)),
            'phone_number' => $phone,
            'email' => trim((string) $this->input->post('email', true)),
            'user_channel' => $this->input->post('user_channel', true),
            'property' => $property,
            'type' => $type,
            'status' => $this->input->post('status', true),
            'disposition' => $this->input->post('disposition', true),
            'created_at' => $now,
            'query' => $this->input->post('query', true),
            'remark' => $this->input->post('remark', true),
            'lead_type' => $this->input->post('lead_type', true),
            'purpose' => $this->input->post('purpose', true),
            'reason' => $this->input->post('reason', true),
            'template_name' => 'Agency',
            'city' => $hotel->city_id,
            'created_by' => $agencyId,
            'creator_user_role' => 'Agency'
        ];

        foreach (['booking_date', 'followup_date', 'second_followup_date', 'amount'] as $field) {
            $fieldValue = $this->input->post($field, true);
            if ($fieldValue !== null && $fieldValue !== '') {
                $leadData[$field] = $fieldValue;
            }
        }

        $escalationHours = (float) ($department->escalation_level_1 ?? 0);
        $leadData['esc_next_followup_at'] = date('Y-m-d H:i:s', strtotime('+' . ($escalationHours * 60) . ' minutes'));
        $leadData['esc_follow_up_level'] = 1;

        if ($leadData['status'] === 'Closed') {
            $leadData['completed_time'] = $now;
        } else {
            $leadData['responded_time'] = $now;
        }

        $existingLead = $this->db
            ->where("RIGHT(phone_number, 10) =", $phone, false)
            ->where('property', $property)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-2 hours')))
            ->where('status !=', 'Closed')
            ->where('is_deleted', 0)
            ->order_by('id', 'DESC')
            ->get('leads')
            ->row();

        if ($existingLead) {
            unset($leadData['created_at']);
            $saved = $this->db
                ->where('id', (int) $existingLead->id)
                ->where('property', $property)
                ->update('leads', $leadData);

            if ($saved) {
                $this->triggerAssignedLeadEmail((int) $existingLead->id, $phone, 'updated');
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => (bool) $saved,
                    'message' => $saved ? 'Existing lead updated successfully.' : 'Failed to update the existing lead.',
                    'duplicate' => true,
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $insertId = $this->LeadModel->insert_lead($leadData);
        if (!$insertId) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed to insert lead.',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }

        $this->triggerAssignedLeadEmail((int) $insertId, $phone, 'created');

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Lead created successfully.',
                'leadId' => (int) $insertId,
                'csrfHash' => $this->security->get_csrf_hash()
            ]));
    }

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
            log_message('error', 'Agency lead email worker could not be started for lead ID ' . (int) $leadId . ': ' . curl_error($emailRequest));
        }
        curl_close($emailRequest);
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

    public function edit_lead($id)
    {
        $lead = $this->Comman_model->getData('leads', ['id' => $id]);



        $data['lead'] = $lead;
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', '');

        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/edit_lead', $data);
        $this->load->view('agency/include/footer');
    }

    public function update_lead()
    {


        $id   = $this->input->post('lead_id');
        $lead = $this->Comman_model->getData('leads', ['id' => $id]);

        if (!$lead) {
            echo json_encode(['status' => false, 'message' => 'Lead not found']);
            return;
        }

        $property   = $this->input->post('property');
        $type       = $this->input->post('type', true);
        $status     = $this->input->post('status', true);
        $disposition = $this->input->post('disposition', true);
        $department  = $this->input->post('leadDepartment', true);

        $hotel_data      = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $property]);
        $department_data = $this->Common_model->getdata('departments', ['department_id' => $type]);

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

        /** Get logged-in user ID based on role */
        $assigned_role = $this->session->userdata('role_as');
        if ($assigned_role === 'super_admin') {
            $userId = $this->session->userdata('super_admin_session')['id'];
        } elseif ($assigned_role === 'admin') {
            $userId = $this->session->userdata('hotel_admin_session')['id'];
        } else {
            $userId = $this->session->userdata('agency_session')['id'];
        }

        /** Time tracking */
        if ($status === 'Closed') {
            $leadData['completed_time'] = date('Y-m-d H:i:s');
        } else {
            $leadData['responded_time'] = date('Y-m-d H:i:s');
        }

        /** Reservation Closed */
        if ($disposition === 'Reservation' && strtolower($status) === 'closed') {
            $department = strtolower($department);

            if ($department === 'rooms') {
                $leadData['checkin_date']       = $this->input->post('checkin_date');
                $leadData['checkout_date']      = $this->input->post('checkout_date');
                $leadData['pax']                = $this->input->post('pax');
                $leadData['amount']             = $this->input->post('amount');
                $leadData['reservation_number'] = $this->input->post('reservation_number');
                $leadData['reservation_email']  = $this->input->post('reservation_email');

                // File upload
                if (!empty($_FILES['bill_attachment']['name'])) {
                    $uploadPath = FCPATH . 'uploads/bills/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                    $fileName   = $_FILES['bill_attachment']['name'];
                    $tmpName    = $_FILES['bill_attachment']['tmp_name'];
                    $extension  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if (!in_array($extension, $allowedExtensions)) {
                        echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, PDF allowed.']);
                        return;
                    }

                    $newFileName = 'bill_' . time() . '.' . $extension;
                    $targetPath  = $uploadPath . $newFileName;

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
                $leadData['pax']          = $this->input->post('pax');
                $leadData['amount']       = $this->input->post('amount');
                $leadData['fnb_email']    = $this->input->post('fnb_email');
            }

            if ($department === 'banquets') {
                $leadData['booking_date']  = $this->input->post('booking_date');
                $leadData['pax']           = $this->input->post('pax');
                $leadData['amount']        = $this->input->post('amount');
                $leadData['banquet_email'] = $this->input->post('banquet_email');
            }
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

        /** Update record */
        $this->Comman_model->UpdateRecord('leads', $leadData, ['id' => $id]);

        echo json_encode(['status' => true, 'message' => 'Lead updated successfully.']);
    }
}

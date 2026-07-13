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



        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/lead_report', $data);
        $this->load->view('agency/include/footer');
    }

    public function add_lead()
    {

        $data['leads'] = $this->LeadModel->get_leads();
        $data['departments'] = $this->Common_model->getAllData('departments', '');
        $agency_id = $this->session->userdata('agency_session')['id'];

        $data['hotel_admin'] = $this->Common_model->get_properties_by_agency($agency_id);

        $this->load->view('agency/include/header');
        $this->load->view('agency/include/sidebar');
        $this->load->view('agency/add_lead', $data);
        $this->load->view('agency/include/footer');
    }

    public function insert_lead()
    {
        if ($this->input->method() === 'post') {


            $agency_id = $this->session->userdata('agency_session')['id'];



            // Collect each field from POST manually
            $leadData = [
                'user_name'       => $this->input->post('user_name', true),
                'phone_number'   => $this->input->post('phone_number', true),
                'email'          => $this->input->post('email', true),
                'date'           => $this->input->post('date', true),
                'time'           => $this->input->post('time', true),
                'user_channel'   => 'Agency',
                'property'       => $this->input->post('property', true),
                'type'           => $this->input->post('type', true),
                'status'    => $this->input->post('status', true),
                'disposition'    => $this->input->post('disposition', true),
                'created_at'   =>  date('Y-m-d H:i:s'),
                'query'          => $this->input->post('query', true),
                'remark'         => $this->input->post('remark', true),
                'lead_type'          => $this->input->post('lead_type', true),
                'created_by' => $agency_id,
                'template_name' => 'Agency',
                'city' => $result->city_id,
                'creator_user_role' => 'Agency'
            ];



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
            $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));

            // find last lead created in last 2 hours with same phone number
            $existingLead = $this->db
                ->where('phone_number', $phone)
                ->where('created_at >=', $twoHoursAgo)
                ->where('status !=', 'Closed')
                ->order_by('id', 'DESC')
                ->get('leads')
                ->row();

            if (!empty($existingLead)) {

                // Do not change original creation timestamp
                unset($leadData['created_at']);

                // Update existing lead with fresh details
                $this->db->where('id', $existingLead->id)
                    ->update('leads', $leadData);

                echo json_encode([
                    'status' => true,
                    'message' => 'Duplicate detected: Existing lead updated successfully.',
                    'duplicate' => true
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
                echo json_encode(['status' => true, 'message' => 'Lead created successfully.']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Failed to insert lead.']);
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

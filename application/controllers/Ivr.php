<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ivr extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ivr_model');
    }


    public function webhook()
    {


        // Step 1: Get POST data


        $data = json_decode(file_get_contents('php://input'), true);

        // Step 2: Decode JSON

        if (!$data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
            return;
        }

        // Step 3: Prevent duplicate entry
        $exists = $this->Ivr_model->check_exists($data['id']);
        if ($exists) {
            echo json_encode(['status' => 'duplicate']);
            return;
        }

        // Step 4: Convert users string → array
        $users_array = json_decode($data['users'], true);

        $agent_number = '';
        if (!empty($users_array) && isset($users_array[0])) {
            $agent_number = $this->format_mobile($users_array[0]);
        }

        // Step 5: Prepare data
        $insertData = [
            'ivr_id' => $data['id'] ?? '',
            'uid' => $data['uid'] ?? '',
            'reference_id' => $data['reference_id'] ?? '',
            'company_id' => $data['company_id'] ?? '',

            'clid_raw' => $data['clid_raw'] ?? '',
            'clid' => $data['clid'] ?? '',
            'rdnis' => $data['rdnis'] ?? '',

            'call_state' => $data['call_state'] ?? 0,
            'event' => $data['event'] ?? 0,
            'status' => $data['status'] ?? '',

            'agent_numbers' => $agent_number,

            'created_at_ivr' => !empty($data['created']) ? date('Y-m-d H:i:s', strtotime($data['created'])) : null,
            'call_time' => !empty($data['call_time']) ? date('Y-m-d H:i:s', strtotime($data['call_time'])) : null,

            'is_sent' => $data['is_sent'] ?? 0,

            'department_name' => $data['department_name'] ?? '',
            'department_extension' => $data['department_extension'] ?? 0,

            'public_ivr_id' => $data['public_ivr_id'] ?? '',
            'client_ref_id' => $data['client_ref_id'] ?? '',
            'job_id' => $data['job_id'] ?? '',

            'notification_status' => $data['notification_status'] ?? 0,

            'raw_payload' => $rawData
        ];

        // Step 6: Insert into DB

        if ($data['call_state'] == 6) {
            $insert_id = $this->Ivr_model->insert_log($insertData);
        }

        // $insert_id = $this->Ivr_model->insert_log($insertData);

        // OPTIONAL: create lead here if needed
        // $this->Ivr_model->create_lead($insertData);

        echo json_encode([
            'status' => 'success',
            'insert_id' => $insert_id
        ]);
    }


    private function format_mobile($number)
    {
        // Remove non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Return last 10 digits
        return substr($number, -10);
    }


    private function format_agent_mobile($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        return substr($number, -10);
    }




    public function get_latest_call()
    {
        $agent_number = $this->input->get('agent_number');

        $this->db->where('agent_numbers LIKE', '%' . $agent_number . '%');
        $this->db->where('is_popped', 0);

        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-60 seconds')));
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $call = $this->db->get('ivr_call_logs')->row();

        if ($call) {

            // Get IVR text (change field if needed)
            $ivr_text = strtolower(trim($call->campaign_name ?? ''));

            $property   = '';
            $department = '';

            // Mapping logic
            if (strpos($ivr_text, 'banquet bookings goa') !== false) {
                $property = '50';
                $department = '3';
            } elseif (strpos($ivr_text, 'banquet bookings indore') !== false) {
                $property = '1';
                $department = '3';
            } elseif (strpos($ivr_text, 'reservations indore') !== false) {
                $property = '1';
                $department = '1';
            } elseif (strpos($ivr_text, 'reservations sehore') !== false) {
                $property = '16';
                $department = '1';
            } elseif (strpos($ivr_text, 'table reservations indore') !== false) {
                $property = '1';
                $department = '2';
            }

            // Mark as popped
            $this->db->where('id', $call->id);
            $this->db->update('ivr_call_logs', ['is_popped' => 1]);

            echo json_encode([
                'status' => 'success',
                'data' => $call,
                'property_id' => $property,
                'department_id' => $department
            ]);
        } else {
            echo json_encode(['status' => 'no_data']);
        }
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CallApiController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('CallApiModel');
    }

    public function capture_call_data()
    {
        // Get JSON input

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
            return;
        }


        if ($data['leadid'] == "") {
            $destination_number = strval($data['Destination_Number']);

            // Load the model if not already loaded

            // Fetch the last inserted call with this destination_number
            $last_call = $this->CallApiModel->get_last_call_by_destination($destination_number);

            if ($last_call && !empty($last_call->leadid)) {
                $data['leadid'] = $last_call->leadid;
            }
        }


        // Insert call data into DB
        $call_id = $this->CallApiModel->insert_call($data);

        log_message('error', 'INSERT CALL DATA: ' . $this->db->last_query());


        // Insert participants if available
        if (isset($data['participants']) && is_array($data['participants'])) {
            foreach ($data['participants'] as $participant) {
                $this->CallApiModel->insert_participant($call_id, $participant);
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Call data saved successfully']);
    }


    public function check_lead_id()
    {
        $destination_number = '07389910990';

        // Load the model if not already loaded

        // Fetch the last inserted call with this destination_number
        $last_call = $this->CallApiModel->get_last_call_by_destination($destination_number);

        if ($last_call && !empty($last_call->leadid)) {
            $data['leadid'] = $last_call->leadid;
        }

        echo $last_call->leadid;

        print_r($data);
    }


    public function return_agent_mobile_for_inbound()
    {
        // Get JSON input

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);


        $destination_number = $data['callingParticipant'];

        if (!$data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
            return;
        }


        $last_call = $this->CallApiModel->get_last_call_by_client_correlation_id($destination_number);

        if ($last_call && !empty($last_call->leadid)) {

            if ($last_call->lead_status == 'Closed') {

                $mobileNumber = '9300051616';
            } else {
                $mobileNumber = $last_call->caller_number;
            }

            $response = [
                "client_add_participant" => [
                    "participants" => [
                        [
                            "participantName"     => "customername",
                            "participantAddress"  => $mobileNumber,
                            "callerId"            => $last_call->caller_id,
                            "maxRetries"          => 1,
                            "audioId"             => 0,
                            "maxTime"             => 0,
                            "enableEarlyMedia"    => "false",
                            "leadid"           => $last_call->leadid
                        ]
                    ],
                    "mergingStrategy" => "SEQUENTIAL",
                    "maxTime"         => 0
                ]
            ];




            echo json_encode(['status' => 'success', 'message' => 'Call data Fetched successfully', 'data' => $response]);
        } else {

            echo json_encode(['status' => 'error', 'message' => 'Something went wrong']);
        }
    }
}

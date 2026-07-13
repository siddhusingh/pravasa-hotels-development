<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CallApiModel extends CI_Model
{

    public function insert_call($data)
    {


        if (empty($data['leadid'])) {
            $destination_number = 'landmera';
        }


        $call_data = [
            'overall_call_status' => $data['Overall_Call_Status'] ?? null,
            'caller_id' => $data['Caller_ID'] ?? null,
            'customer_name' => $data['Customer_Name'] ?? null,
            'client_correlation_id' => $data['Client_Correlation_Id'] ?? null,
            'caller_operator_name' => $data['Caller_Operator_Name'] ?? null,
            'time' => $data['Time'] ?? null,
            'caller_circle_name' => $data['Caller_Circle_Name'] ?? null,
            'destination_circle_name' => $data['Destination_Circle_Name'] ?? null,
            'pulse_count' => $data['Pulse_Count'] ?? 0,
            'call_type' => $data['callType'] ?? null,
            'caller_waiting_time' => $data['Caller_Waiting_Time'] ?? null,
            'destination_name' => $data['Destination_Name'] ?? null,
            'duration' => $data['duration'] ?? 0,
            'billable_duration' => $data['Billable_Duration'] ?? null,
            'conversation_duration' => $data['conversationDuration'] ?? 0,
            'overall_call_duration' => $data['Overall_Call_Duration'] ?? null,
            'customer_id' => $data['customerId'] ?? null,
            'start_time' => $data['startTime'] ?? null,
            'session_id' => $data['Session_ID'] ?? null,
            'destination_retry_count' => $data['Destination_Retry_Count'] ?? 0,
            'caller_status' => $data['Caller_Status'] ?? null,
            'destination_status' => $data['Destination_Status'] ?? null,
            'timestamp' => $data['timestamp'] ?? null,
            'conversation_duration_str' => $data['Conversation_Duration'] ?? null,
            'hangup_cause' => $data['Hangup_Cause'] ?? null,
            'caller_retry_count' => $data['Caller_Retry_Count'] ?? 0,
            'destination_cli' => $data['Destination_CLI'] ?? null,
            'caller_duration' => $data['Caller_Duration'] ?? null,
            'call_date' => isset($data['Date']) ? date('Y-m-d', strtotime($data['Date'])) : null,
            'caller_status_detail' => $data['Caller_Status_Detail'] ?? null,
            'call_type_str' => $data['Call_Type'] ?? null,
            'destination_status_detail' => $data['Destination_Status_Detail'] ?? null,
            'caller_name' => $data['Caller_Name'] ?? null,
            'caller_number' => $data['Caller_Number'] ?? null,
            'recording_url' => $data['Recording'] ?? null,
            'end_time' => $data['endTime'] ?? null,
            'destination_number' => $data['Destination_Number'] ?? null,
            'destination_operator_name' => $data['Destination_Operator_Name'] ?? null,
            'leadid' => $data['leadid'] ?? null


        ];



        $this->db->insert('calls', $call_data);


        return $this->db->insert_id();
    }



    public function get_last_call_by_destination($destination_number)
    {
        return $this->db
            ->where('caller_number', $destination_number)
            ->order_by('id', 'DESC') // Assuming `id` is auto-increment
            ->limit(1)
            ->get('calls')
            ->row(); // returns object of the last matching row
    }

    public function get_last_call_by_client_correlation_id($destination_number)
    {
        return $this->db
            ->select('calls.*, leads.status as lead_status') // select all call fields + lead status
            ->from('calls')
            ->join('leads', 'leads.id = calls.leadid', 'left') // join leads table
            ->where('calls.destination_number', $destination_number)
            ->order_by('calls.id', 'DESC')
            ->limit(1)
            ->get()
            ->row();
    }






    public function insert_participant($call_id, $participant)
    {
        $participant_data = [
            'call_id' => $call_id,
            'participant_address' => $participant['participantAddress'] ?? null,
            'participant_name' => $participant['participantName'] ?? null,
            'participant_type' => $participant['participantType'] ?? null,
            'participant_number_type' => $participant['participantNumberType'] ?? null,
            'participant_country_code' => $participant['participantNumberCountryCode'] ?? null,
            'caller_id_type' => $participant['callerIdType'] ?? null,
            'caller_id_circle' => $participant['callerIdCircle'] ?? null,
            'caller_id_country_code' => $participant['callerIdCountryCode'] ?? null,
            'caller_id' => $participant['callerId'] ?? null,
            'retry_count' => $participant['retryCount'] ?? 0,
            'start_time' => $participant['startTime'] ?? null,
            'end_time' => $participant['endTime'] ?? null,
            'call_answer_time' => $participant['callAnswerTime'] ?? null,
            'duration' => $participant['duration'] ?? 0,
            'status' => $participant['status'] ?? null

        ];

        $this->db->insert('participants', $participant_data);
    }

    public function get_calls()
    {
        $this->db->select('*');
        $this->db->from('calls');
        $this->db->order_by('timestamp', 'DESC'); // Order by latest call
        return $this->db->get()->result_array();
    }
}

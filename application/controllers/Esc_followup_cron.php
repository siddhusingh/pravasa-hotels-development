<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Esc_followup_cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeadModel');
        $this->load->library('email');
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        date_default_timezone_set('Asia/Kolkata');
    }


    public function index()
    {
        $now = date('Y-m-d H:i:s');



        $leads = $this->LeadModel->get_escalation_leads($now);

        if (empty($leads)) {
            echo "No escalation leads found";
            return;
        }



        foreach ($leads as $lead) {
            switch ((int)$lead->esc_follow_up_level) {

                case 1:
                    $this->send_followup_one($lead->id);
                    break;

                case 2:
                    $this->send_followup_two($lead->id);
                    break;

                case 3:
                    $this->send_followup_three($lead->id);
                    break;
            }
        }
    }



    public function send_followup_one($lead_id)
    {
        $lead = $this->LeadModel->get_lead_with_property_and_department($lead_id);
        if (!$lead) return;

        if (!$this->send_level_one_email($lead->id)) {
            log_message('error', "Level 1 escalation status was not advanced because the email failed. Lead ID: $lead_id");
            return false;
        }

        $next_time = $this->calculate_next_followup_time($lead->type, 2);

        $this->LeadModel->update_followup($lead->id, [
            'esc_follow_up_level'      => 2,
            'esc_followup_one_status' => 1,
            'esc_next_followup_at'    => $next_time
        ]);

        return true;
    }




    public function send_followup_two($lead_id)
    {
        $lead = $this->LeadModel->get_lead_with_property_and_department($lead_id);
        if (!$lead) return;

        if (!$this->send_level_two_email($lead->id)) {
            log_message('error', "Level 2 escalation status was not advanced because the email failed. Lead ID: $lead_id");
            return false;
        }

        $next_time = $this->calculate_next_followup_time($lead->type, 3);

        $this->LeadModel->update_followup($lead->id, [
            'esc_follow_up_level'      => 3,
            'esc_followup_two_status' => 1,
            'esc_next_followup_at'    => $next_time
        ]);

        return true;
    }




    public function send_followup_three($lead_id)
    {
        $lead = $this->LeadModel->get_lead_with_property_and_department($lead_id);
        if (!$lead) return;

        if (!$this->send_level_three_email($lead->id)) {
            log_message('error', "Level 3 escalation status was not advanced because the email failed. Lead ID: $lead_id");
            return false;
        }

        $this->LeadModel->update_followup($lead->id, [
            'esc_follow_up_level'        => 4,
            'esc_followup_three_status' => 1,
            'esc_next_followup_at'      => NULL
        ]);

        return true;
    }



    public function calculate_next_followup_time($department_id, $level)
    {
        // Fetch department escalation config
        $department = $this->Common_model->getdata('departments', [
            'department_id' => $department_id
        ]);

        if (empty($department)) {
            return false;
        }

        // Get escalation hours based on level
        switch ($level) {
            case 1:
                $decimal_hours = $department->escalation_level_1;
                break;

            case 2:
                $decimal_hours = $department->escalation_level_2;
                break;

            case 3:
                $decimal_hours = $department->escalation_level_3;
                break;

            default:
                return false;
        }

        // Convert hours to minutes
        $minutes_to_add = $decimal_hours * 60;

        // Calculate next follow-up time
        $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $current_time->modify("+$minutes_to_add minutes");

        return $current_time->format('Y-m-d H:i:s');
    }



    public function send_level_one_email($lead_id)
    {
        $url = base_url("EmailWorker/sendEscalationL1/$lead_id");
        return $this->runEmailWorker($url);
    }

    public function send_level_two_email($lead_id)
    {
        $url = base_url("EmailWorker/sendEscalationL2/$lead_id");
        return $this->runEmailWorker($url);
    }

    public function send_level_three_email($lead_id)
    {
        $url = base_url("EmailWorker/sendEscalationL3/$lead_id");
        return $this->runEmailWorker($url);
    }

    private function runEmailWorker($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 60
        ]);
        $response = curl_exec($ch);
        $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $http_code < 200 || $http_code >= 300) {
            log_message('error', 'Escalation email worker request failed: ' . ($curl_error ?: 'HTTP ' . $http_code));
            return false;
        }

        return true;
    }
}

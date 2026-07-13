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

        // Async trigger (fire & forget)
        $this->send_level_one_email($lead->id);

        $next_time = $this->calculate_next_followup_time($lead->type, 2);

        $this->LeadModel->update_followup($lead->id, [
            'esc_follow_up_level'      => 2,
            'esc_followup_one_status' => 1,
            'esc_next_followup_at'    => $next_time
        ]);
    }




    public function send_followup_two($lead_id)
    {
        $lead = $this->LeadModel->get_lead_with_property_and_department($lead_id);
        if (!$lead) return;

        $this->send_level_two_email($lead->id);

        $next_time = $this->calculate_next_followup_time($lead->type, 3);

        $this->LeadModel->update_followup($lead->id, [
            'esc_follow_up_level'      => 3,
            'esc_followup_two_status' => 1,
            'esc_next_followup_at'    => $next_time
        ]);
    }




    public function send_followup_three($lead_id)
    {
        $lead = $this->LeadModel->get_lead_with_property_and_department($lead_id);
        if (!$lead) return;

        $this->send_level_three_email($lead->id);

        $this->LeadModel->update_followup($lead->id, [
            'esc_follow_up_level'        => 4,
            'esc_followup_three_status' => 1,
            'esc_next_followup_at'      => NULL
        ]);
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
        $this->fireAndForget($url);
    }

    public function send_level_two_email($lead_id)
    {
        $url = base_url("EmailWorker/sendEscalationL2/$lead_id");
        $this->fireAndForget($url);
    }

    public function send_level_three_email($lead_id)
    {
        $url = base_url("EmailWorker/sendEscalationL3/$lead_id");
        $this->fireAndForget($url);
    }

    public function fireAndForget($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_CONNECTTIMEOUT_MS => 100,
            CURLOPT_TIMEOUT_MS => 100,
            CURLOPT_NOSIGNAL => 1
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}

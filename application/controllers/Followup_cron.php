<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Followup_cron extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeadModel');
        $this->load->library('email');
        date_default_timezone_set('Asia/Kolkata');
    }

    // ----------------------------------------------------------------
    // ✅ MAIN INDEX METHOD (CRON RUNS ONLY THIS)
    // ----------------------------------------------------------------
    public function index()
    {
        $today = date('Y-m-d');   // Example: 2025-11-03

        // Fetch all leads
        $leads = $this->LeadModel->get_all_today_followups($today);

        if (empty($leads)) {
            echo "No follow-ups for today";
            return;
        }

        foreach ($leads as $lead) {



            // ✅ SEND FOLLOW-UP 1 only if follow-up 1 date is today
            if ($lead->followup_date == $today && $lead->followup_one_status == 0) {
                $this->send_followup_one($lead->id);


                continue;  // avoid sending follow-up 2 on the same day accidentally
            }

            // ✅ SEND FOLLOW-UP 2 only if follow-up 2 date is today
            if (
                $lead->second_followup_date == $today
                && $lead->followup_one_status == '1'
                && $lead->followup_two_status == '0'
            ) {
                $this->send_followup_two($lead->id);
                continue;
            }
        }
    }

    // ----------------------------------------------------------------
    // ✅ FIRST FOLLOW-UP EMAIL
    // ----------------------------------------------------------------
    public function send_followup_one($lead_id)
    {

        $this->load->model('Leadmodel');

        // Fetch full lead details with joins
        $lead = $this->Leadmodel->get_lead_by_id_with_joins($lead_id);





        if (!$lead) return;

        // Get agent email
        $agent_email = $this->Leadmodel->get_agent_email($lead->assigned_to)->email;
        $agent_name = $this->Leadmodel->get_agent_email($lead->assigned_to)->name;

        $lead->agent_name = $agent_name;


        $subject = "First Reminder: Follow-Up Required for Lead #{$lead->id} on {$lead->followup_one_date}";



        // Load dynamic email template
        $message = $this->load->view(
            'emails/followup_one_template',
            ['lead' => $lead],
            TRUE
        );


        $this->load->model('Mail_model');
        $this->Mail_model->sendMailSMTP_uv(
            $agent_name,
            [$agent_email],
            $subject,
            $message,
            ['enquiry@sayajigroup.com']
        );






        // ✅ Send + Log
        $this->LeadModel->update_followup_one_status($lead_id, 1);
    }


    // ----------------------------------------------------------------
    // ✅ SECOND FOLLOW-UP EMAIL
    // ----------------------------------------------------------------
    public function send_followup_two($lead_id)
    {

        $this->load->model('Leadmodel');

        // Fetch full lead details with joins
        $lead = $this->Leadmodel->get_lead_by_id_with_joins($lead_id);





        if (!$lead) return;

        // Get agent email
        $agent_email = $this->Leadmodel->get_agent_email($lead->assigned_to)->email;
        $agent_name = $this->Leadmodel->get_agent_email($lead->assigned_to)->name;

        $lead->agent_name = $agent_name;


        $subject = "Second Reminder: Urgent Follow-Up Required for Lead #{$lead->id} on {$lead->followup_two_date}";



        // Load dynamic email template
        $message = $this->load->view(
            'emails/followup_two_template',
            ['lead' => $lead],
            TRUE
        );


        $this->load->model('Mail_model');
        $this->Mail_model->sendMailSMTP_uv(
            $agent_name,
            [$agent_email],
            $subject,
            $message,
            ['enquiry@sayajigroup.com']
        );






        // ✅ Send + Log

        $this->LeadModel->update_followup_two_status($lead_id, 1);
    }
}

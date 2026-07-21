<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Job_scheduler extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Mail_model');
        $this->load->model('LeadModel');
    }


    public function index()
    {

        $leadsData = $this->Common_model->getAllData("leads", [
            "status" => 'Open',
        ]);





        foreach ($leadsData as $each_lead) {

            $lead_id = $each_lead->id;


            $follow_up_time = strtotime($each_lead->follow_up_time); // from DB
            $current_time = time();

            if ($current_time >= $follow_up_time) {

                $follow_up_level = $each_lead->follow_up_level;

                $department_data = $this->Common_model->getdata('departments', ['department_id' => $each_lead->type]);


                // first level of follow up

                if ($follow_up_level == 1) {

                    // now if the level of follow up is 1 then we will update the level to 2 and will update next run date and last run date

                    $escalation_level_2 = $department_data->escalation_level_2;

                    $decimal_hours = $escalation_level_2;
                    $minutes_to_add = $decimal_hours * 60;

                    // Get current time
                    $current_time = new DateTime();

                    // Add minutes
                    $current_time->modify("+$minutes_to_add minutes");

                    // Format follow-up time
                    $follow_up_time = $current_time->format('Y-m-d H:i:s');
                    $follow_up_level = 2;

                    $follow_up_data['follow_up_level'] = $follow_up_level;
                    $follow_up_data['follow_up_time'] = $follow_up_time;


                    if ($this->level_1_follow_up($each_lead->id)) {
                        $this->Common_model->UpdateRecord('leads', $follow_up_data, array('id' => $each_lead->id));
                    }


                    // second level of follow up

                } else if ($follow_up_level == 2) {


                    $escalation_level_3 = $department_data->escalation_level_3;

                    $decimal_hours = $escalation_level_3;
                    $minutes_to_add = $decimal_hours * 60;

                    // Get current time
                    $current_time = new DateTime();

                    // Add minutes
                    $current_time->modify("+$minutes_to_add minutes");

                    // Format follow-up time
                    $follow_up_time = $current_time->format('Y-m-d H:i:s');
                    $follow_up_level = 3;

                    $follow_up_data['follow_up_level'] = $follow_up_level;
                    $follow_up_data['follow_up_time'] = $follow_up_time;


                    if ($this->level_2_follow_up($each_lead->id)) {
                        $this->Common_model->UpdateRecord('leads', $follow_up_data, array('id' => $each_lead->id));
                    }


                    // third level of follow up

                } else if ($follow_up_level == 3) {


                    $escalation_level_3 = $department_data->escalation_level_3;

                    $decimal_hours = $escalation_level_3;
                    $minutes_to_add = $decimal_hours * 60;

                    // Get current time
                    $current_time = new DateTime();

                    // Add minutes
                    $current_time->modify("+$minutes_to_add minutes");

                    // Format follow-up time
                    $follow_up_time = $current_time->format('Y-m-d H:i:s');
                    $follow_up_level = 4;

                    $follow_up_data['follow_up_level'] = $follow_up_level;
                    $follow_up_data['follow_up_time'] = $follow_up_time;


                    if ($this->level_3_follow_up($each_lead->id)) {
                        $this->Common_model->UpdateRecord('leads', $follow_up_data, array('id' => $each_lead->id));
                    }

                    // fourth level of follow up

                }
            }
        }
    }

    // Example method for Level 1 Follow-up
    public function level_1_follow_up($lead_id)
    {
        $lead = $this->LeadModel->get_leads_for_scheduler($lead_id);


        if (!$lead) {
            echo "No lead found for Level 1.";
            return;
        }

        $subject = " Level 1 Reminder: Lead Follow-up - {$lead->user_name}";
        $message = $this->compose_email_template($lead, 1);
        return $this->Mail_model->sendMailSMTP_uv('Manager', ['umeshvishwakarma6192@gmail.com'], $subject, $message);
    }

    public function level_2_follow_up($lead_id)
    {
        $lead = $this->LeadModel->get_leads_for_scheduler($lead_id);

        if (!$lead) {
            echo "No lead found for Level 2.";
            return;
        }

        $subject = " Level 2 Escalation: Lead Requires Action - {$lead->user_name}";
        $message = $this->compose_email_template($lead, 2);
        return $this->Mail_model->sendMailSMTP_uv('Manager', ['umeshvishwakarma6192@gmail.com'], $subject, $message);
    }

    public function level_3_follow_up($lead_id)
    {
        $lead = $this->LeadModel->get_leads_for_scheduler($lead_id);

        if (!$lead) {
            echo "No lead found for Level 3.";
            return;
        }

        $subject = " Level 3 Urgent Escalation: Lead Still Pending - {$lead->user_name}";
        $message = $this->compose_email_template($lead, 3);
        return $this->Mail_model->sendMailSMTP_uv('Manager', ['umeshvishwakarma6192@gmail.com'], $subject, $message);
    }

    public function level_4_follow_up($lead_id)
    {
        $lead = $this->LeadModel->get_leads_for_scheduler($lead_id);

        if (!$lead) {
            echo "No lead found for Level 4.";
            return;
        }

        $subject = "🚨 Level 4 Critical Escalation: Immediate Action Required - {$lead->user_name}";
        $message = $this->compose_email_template($lead, 4);
        return $this->Mail_model->sendMailSMTP_uv('Senior Manager', ['umeshvishwakarma6192@gmail.com'], $subject, $message);
    }


    // Common email template builder
    private function compose_email_template($lead, $level)
    {
        $level_titles = [
            1 => [" Lead Follow-up Reminder: Level 1", "#3498db", "#f9f9f9"],
            2 => ["⚠️ Follow-up Required: Level 2 Escalation", "#f39c12", "#fff8e1"],
            3 => ["⏰ Urgent: Level 3 Escalation – Lead Still Pending", "#e67e22", "#fff3e0"],
            4 => ["🚨 Critical Escalation: Level 4 – Management Attention Needed", "#c0392b", "#fdecea"]
        ];

        list($title, $title_color, $bg_color) = $level_titles[$level];

        return '
        <div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #ccc; padding: 20px; background: ' . $bg_color . ';">
            <h2 style="color: ' . $title_color . ';">' . $title . '</h2>
            <p>Dear ' . ($level == 4 ? 'Senior Manager' : 'Manager') . ',</p>
            <p>This is a level ' . $level . ' escalation regarding the following lead that remains unaddressed:</p>

            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <tr><td style="padding: 8px; border: 1px solid #ccc;"><strong>Name</strong></td><td style="padding: 8px; border: 1px solid #ccc;">' . htmlspecialchars($lead->user_name) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #ccc;"><strong>Phone</strong></td><td style="padding: 8px; border: 1px solid #ccc;">' . htmlspecialchars($lead->phone_number) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #ccc;"><strong>Email</strong></td><td style="padding: 8px; border: 1px solid #ccc;">' . htmlspecialchars($lead->email) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #ccc;"><strong>Query</strong></td><td style="padding: 8px; border: 1px solid #ccc;">' . nl2br(htmlspecialchars($lead->query)) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #ccc;"><strong>Property</strong></td><td style="padding: 8px; border: 1px solid #ccc;">' . htmlspecialchars($lead->hotel_name) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #ccc;"><strong>Department</strong></td><td style="padding: 8px; border: 1px solid #ccc;">' . htmlspecialchars($lead->department_name) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #ccc;"><strong>Created At</strong></td><td style="padding: 8px; border: 1px solid #ccc;">' . date('d M Y, h:i A', strtotime($lead->created_at)) . '</td></tr>
            </table>

            <p>Please take the necessary action at the earliest.</p>
            <p>Regards,<br><strong>Lead Escalation System</strong></p>
        </div>';
    }
}

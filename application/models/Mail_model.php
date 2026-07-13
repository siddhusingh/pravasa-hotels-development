<?php
class Mail_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function sendMailSMTP_uv($name = '', $to = "", $subject = "", $message = "", $cc_emails = "", $IsvaluableGuest = "")
    {


        // Include PHPMailer classes from controllers/PHPMailer/
        require_once(APPPATH . 'controllers/PHPMailer/Exception.php');
        require_once(APPPATH . 'controllers/PHPMailer/PHPMailer.php');
        require_once(APPPATH . 'controllers/PHPMailer/SMTP.php');

        // Create PHPMailer instance
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {


            $greeting = "";
            $final_msg = $greeting . $message;




            // SMTP Configuration
            // $mail->isSMTP();
            // $mail->Host       = 'smtp.gmail.com';
            // $mail->SMTPAuth   = true;
            // $mail->Username   = 'umeshvishakarma3@gmail.com'; // your Gmail
            // $mail->Password   = 'prxf jilz jjej ahnb'; // app password
            // $mail->SMTPSecure = 'tls';
            // $mail->Port       = 587;





            $mail->isSMTP();
            $mail->Host = "mail.g9leads.com"; // cPanel SMTP hostname
            $mail->SMTPAuth = true;
            $mail->Username = "info@g9leads.com"; // Your cPanel email
            $mail->Password = "-m5]Im1qX$&2dJQT"; // Your email password
            $mail->SMTPSecure = "tls"; // Encryption (use 'ssl' for port 465)
            $mail->Port = 587; // Port for TLS

            // // Optional alternate settings if TLS doesn't work:
            // $mail->SMTPSecure = "ssl";
            // $mail->Port = 465;

            $mail->setFrom("info@g9leads.com", "Crescent Leads"); // Sender info

            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;









            foreach ($to as $email) {
                $cleaned_email = trim($email);
                if (!empty($cleaned_email)) {
                    $mail->addAddress($cleaned_email);
                }
            }

            // Add CC emails only if array is not empty
            if (!empty($cc_emails) && is_array($cc_emails)) {
                foreach ($cc_emails as $email) {
                    $cleaned_email = trim($email);
                    if (!empty($cleaned_email)) {
                        $mail->addCC($cleaned_email);
                    }
                }
            }






            // Optional: CC, BCC
            // $mail->addCC('someone@example.com');
            // $mail->addBCC('another@example.com');

            if ($IsvaluableGuest) {
                // High priority email headers (PHPMailer)
                $mail->Priority = 1;  // 1 = High priority
                $mail->addCustomHeader('Importance: High');
                $mail->addCustomHeader('X-MSMail-Priority: High');
                $mail->addCustomHeader('X-Priority: 1');
            }



            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $final_msg;

            $mail->send();
            return true;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

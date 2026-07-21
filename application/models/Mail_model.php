<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mail_model extends CI_Model
{
    private $last_error = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Settings_model');
        $this->load->library('phpmailer_lib');
    }

    /**
     * Send an HTML email using the SMTP configuration saved in Software Settings.
     *
     * The existing method signature is intentionally retained because lead,
     * follow-up and escalation modules already use it.
     */
    public function sendMailSMTP_uv($name = '', $to = '', $subject = '', $message = '', $cc_emails = '', $IsvaluableGuest = '')
    {
        $this->last_error = '';
        $settings = $this->Settings_model->get_settings();

        if (!$this->has_complete_smtp_settings($settings)) {
            return $this->fail('SMTP configuration is incomplete. Please update Software Settings.');
        }

        $to_addresses = $this->normalize_recipients($to);
        $cc_addresses = $this->normalize_recipients($cc_emails);

        // Do not add the same address to both To and CC.
        if (!empty($to_addresses) && !empty($cc_addresses)) {
            $to_lookup = array_fill_keys(array_map('strtolower', $to_addresses), true);
            $cc_addresses = array_values(array_filter($cc_addresses, function ($email) use ($to_lookup) {
                return !isset($to_lookup[strtolower($email)]);
            }));
        }

        if (empty($to_addresses) && empty($cc_addresses)) {
            return $this->fail('Email was not sent because no valid recipient was provided.');
        }

        $mail = $this->phpmailer_lib->load();

        try {
            $mail->isSMTP();
            $mail->Host = trim((string) $settings->smtp_host);
            $mail->SMTPAuth = true;
            $mail->Username = trim((string) $settings->smtp_user);
            $mail->Password = (string) $settings->smtp_pass;
            $mail->Port = (int) $settings->smtp_port;
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 30;

            $encryption = strtolower(trim((string) $settings->smtp_encryption));
            if (in_array($encryption, ['ssl', 'tls'], true)) {
                $mail->SMTPSecure = $encryption;
            } else {
                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = false;
            }

            $mail->setFrom(
                trim((string) $settings->smtp_from_email),
                trim((string) $settings->smtp_from_name)
            );

            foreach ($to_addresses as $email) {
                $mail->addAddress($email);
            }

            foreach ($cc_addresses as $email) {
                $mail->addCC($email);
            }

            if ($IsvaluableGuest) {
                $mail->Priority = 1;
                $mail->addCustomHeader('Importance: High');
                $mail->addCustomHeader('X-MSMail-Priority: High');
                $mail->addCustomHeader('X-Priority: 1');
            }

            $mail->isHTML(true);
            $mail->Subject = (string) $subject;
            $mail->Body = (string) $message;
            $mail->AltBody = trim(preg_replace('/\s+/', ' ', strip_tags((string) $message)));

            if (!$mail->send()) {
                return $this->fail($mail->ErrorInfo ?: 'The SMTP server did not accept the email.');
            }

            return true;
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function get_last_error()
    {
        return $this->last_error;
    }

    private function has_complete_smtp_settings($settings)
    {
        if (!$settings) {
            return false;
        }

        foreach (['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'smtp_from_email', 'smtp_from_name'] as $field) {
            if (!isset($settings->{$field}) || trim((string) $settings->{$field}) === '') {
                return false;
            }
        }

        $port = filter_var($settings->smtp_port, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 65535]
        ]);

        return $port !== false
            && filter_var($settings->smtp_from_email, FILTER_VALIDATE_EMAIL) !== false
            && in_array(strtolower((string) $settings->smtp_encryption), ['ssl', 'tls', 'none'], true);
    }

    private function normalize_recipients($recipients)
    {
        if (is_string($recipients)) {
            $recipients = preg_split('/[;,]+/', $recipients);
        } elseif (!is_array($recipients)) {
            $recipients = [];
        }

        $valid = [];
        foreach ($recipients as $email) {
            $email = trim((string) $email);
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $valid[strtolower($email)] = $email;
            }
        }

        return array_values($valid);
    }

    private function fail($message)
    {
        $this->last_error = trim((string) $message);
        log_message('error', 'Email sending failed: ' . $this->last_error);
        return false;
    }
}

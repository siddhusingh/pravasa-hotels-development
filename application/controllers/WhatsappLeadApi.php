<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WhatsappLeadApi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('WhatsappLead_model');
        date_default_timezone_set('Asia/Kolkata');
    }

    /**
     * WhatsAppJet hotel-reservation confirmed webhook.
     * POST api/whatsapp/save-lead
     */
    public function save_lead()
    {
        $this->apply_cors_headers();

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json_response(false, 'Only POST is allowed', [], 405);
        }

        if (!$this->is_bearer_authorized()) {
            return $this->json_response(false, 'Unauthorized', [], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            return $this->json_response(false, 'Invalid JSON payload', [], 400);
        }

        $name = trim((string) ($input['name'] ?? ''));
        $phone = trim((string) ($input['phone'] ?? ''));
        if ($phone === '') {
            $phone = trim((string) ($input['contact_wa_id'] ?? ''));
        }
        $location = trim((string) ($input['location'] ?? ''));
        $property_name = trim((string) ($input['property'] ?? ''));
        $service = trim((string) ($input['service'] ?? ''));
        if ($service === '') {
            $service = 'Restaurants';
        }

        if ($name === '' || $phone === '' || $location === '' || $property_name === '') {
            return $this->json_response(
                false,
                'name, phone, location and property are required',
                [],
                422
            );
        }

        $city = $this->WhatsappLead_model->find_city_by_name($location);
        if (empty($city)) {
            return $this->json_response(false, 'Location not found or inactive', [], 422);
        }

        $property = $this->WhatsappLead_model->find_property_by_name($property_name);
        if (empty($property)) {
            return $this->json_response(false, 'Property not found or inactive', [], 422);
        }

        if ((int) $property->city_id !== (int) $city->city_id) {
            return $this->json_response(
                false,
                'Property does not belong to the selected location',
                [],
                422
            );
        }

        $department = $this->WhatsappLead_model->find_department_by_name($service);
        if (empty($department)) {
            $department = $this->WhatsappLead_model->get_department_by_id(2);
        }
        if (empty($department)) {
            return $this->json_response(false, 'Department not found or inactive', [], 422);
        }

        $guests = isset($input['guests']) ? (int) $input['guests'] : 0;
        $booking_date = trim((string) ($input['date'] ?? ''));
        $time_label = trim((string) ($input['time'] ?? ''));
        $reservation_uid = trim((string) ($input['reservation_uid'] ?? ''));

        $query_parts = [];
        if ($booking_date !== '') {
            $query_parts[] = 'Date: ' . $booking_date;
        }
        if ($time_label !== '') {
            $query_parts[] = 'Time: ' . $time_label;
        }
        if ($guests > 0) {
            $query_parts[] = 'Guests: ' . $guests;
        }
        if ($reservation_uid !== '') {
            $query_parts[] = 'Reservation UID: ' . $reservation_uid;
        }
        $query = implode(' | ', $query_parts);

        $escalation = $this->WhatsappLead_model->build_escalation_fields($department);

        $lead_data = [
            'user_name' => $name,
            'phone_number' => $phone,
            'email' => null,
            'property' => (int) $property->hotel_id,
            'city' => (int) $city->city_id,
            'state' => (int) $city->state_id,
            'country' => (int) $city->country_id,
            'type' => (int) $department->department_id,
            'user_channel' => 'WhatsApp',
            'pax' => $guests > 0 ? $guests : null,
            'booking_date' => $booking_date !== '' ? $booking_date : null,
            'time' => $time_label !== '' ? $time_label : null,
            'query' => $query !== '' ? $query : null,
            'remark' => $reservation_uid !== '' ? $reservation_uid : null,
            'status' => 'Open',
            'created_at' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d'),
            'ip_address' => $this->input->ip_address(),
            'esc_next_followup_at' => $escalation['esc_next_followup_at'],
            'esc_follow_up_level' => $escalation['esc_follow_up_level'],
        ];

        $existing_lead = $this->WhatsappLead_model->find_recent_duplicate_lead($phone);
        if (!empty($existing_lead)) {
            unset($lead_data['created_at']);
            $updated = $this->WhatsappLead_model->update_lead($existing_lead->id, $lead_data);

            if (!$updated) {
                return $this->json_response(false, 'Failed to update lead', [], 500);
            }

            return $this->json_response(true, 'Lead updated successfully', [
                'lead_id' => (int) $existing_lead->id,
                'action' => 'updated',
            ]);
        }

        $insert_id = $this->WhatsappLead_model->insert_lead($lead_data);
        if ($insert_id <= 0) {
            return $this->json_response(false, 'Failed to save lead', [], 500);
        }

        $is_valuable_guest = $this->WhatsappLead_model->is_valuable_guest($phone) ? 1 : 0;
        $this->trigger_lead_email($insert_id, $is_valuable_guest);

        return $this->json_response(true, 'Lead saved successfully', [
            'lead_id' => $insert_id,
            'action' => 'created',
        ]);
    }

    private function apply_cors_headers()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Content-Type: application/json');
    }

    private function is_bearer_authorized()
    {
        $expected = trim((string) getenv('WHATSAPP_LEAD_API_TOKEN'));
        if ($expected === '' && isset($_ENV['WHATSAPP_LEAD_API_TOKEN'])) {
            $expected = trim((string) $_ENV['WHATSAPP_LEAD_API_TOKEN']);
        }

        if ($expected === '') {
            return false;
        }

        $header = $this->get_authorization_header();
        if ($header === '') {
            return false;
        }

        if (!preg_match('/^Bearer\s+(\S+)$/i', $header, $matches)) {
            return false;
        }

        $provided = trim($matches[1]);
        if ($provided === '') {
            return false;
        }

        return hash_equals($expected, $provided);
    }

    private function get_authorization_header()
    {
        $header = $this->input->get_request_header('Authorization', true);
        if (!empty($header)) {
            return trim((string) $header);
        }

        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            return trim((string) $_SERVER['HTTP_AUTHORIZATION']);
        }

        if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            return trim((string) $_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        }

        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'authorization') {
                    return trim((string) $value);
                }
            }
        }

        return '';
    }

    private function trigger_lead_email($lead_id, $is_valuable_guest)
    {
        $url = base_url('EmailWorker/sendLeadEmail/' . (int) $lead_id . '/' . (int) $is_valuable_guest);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 100);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        @curl_exec($ch);
        curl_close($ch);
    }

    private function json_response($status, $message, array $data = [], $http_code = 200)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($http_code)
            ->set_output(json_encode([
                'status' => (bool) $status,
                'message' => $message,
                'data' => $data,
            ]));
    }
}

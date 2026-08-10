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
     * GET api/whatsapp/catalog/locations-with-properties
     */
    public function locations_with_properties()
    {
        if (!$this->prepare_catalog_request('GET')) {
            return;
        }

        $data = $this->WhatsappLead_model->get_locations_with_properties();

        return $this->json_response(true, 'OK', $data);
    }

    /**
     * GET api/whatsapp/catalog/departments
     * No property_id query — global WhatsJet department list.
     */
    public function departments()
    {
        if (!$this->prepare_catalog_request('GET')) {
            return;
        }

        $departments = $this->WhatsappLead_model->get_catalog_departments();

        return $this->json_response(true, 'OK', $departments);
    }

    /**
     * GET api/whatsapp/catalog/restaurants?property_id={lms_property_id}
     */
    public function restaurants()
    {
        if (!$this->prepare_catalog_request('GET')) {
            return;
        }

        $property_id = (int) $this->input->get('property_id');
        if ($property_id <= 0) {
            return $this->json_response(false, 'property_id is required', [], 422);
        }

        $restaurants = $this->WhatsappLead_model->get_catalog_restaurants_by_property($property_id);
        if ($restaurants === null) {
            return $this->json_response(false, 'Property not found or inactive', [], 422);
        }

        return $this->json_response(true, 'OK', $restaurants);
    }

    /**
     * GET api/whatsapp/catalog/dining-schedule
     * Nested slot types with active time slots.
     */
    public function dining_schedule()
    {
        if (!$this->prepare_catalog_request('GET')) {
            return;
        }

        $data = $this->WhatsappLead_model->get_catalog_dining_schedule();

        return $this->json_response(true, 'OK', $data);
    }

    /**
     * WhatsAppJet hotel-reservation confirmed webhook.
     * POST api/whatsapp/save-lead
     */
    public function save_lead()
    {
        $this->apply_cors_headers(['POST', 'OPTIONS']);

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

        $location_id = (int) ($input['location_id'] ?? 0);
        $location_name = trim((string) ($input['location'] ?? ''));
        $property_id = (int) ($input['property_id'] ?? 0);
        $property_name = trim((string) ($input['property'] ?? ''));
        $department_id = (int) ($input['department_id'] ?? 0);
        $service = trim((string) ($input['service'] ?? ''));
        $restaurant_id = (int) ($input['restaurant_id'] ?? 0);
        $restaurant_name = trim((string) ($input['restaurant'] ?? ''));

        if ($name === '' || $phone === '') {
            return $this->json_response(false, 'name and phone are required', [], 422);
        }

        if ($location_id <= 0 && $location_name === '') {
            return $this->json_response(false, 'location_id or location is required', [], 422);
        }

        if ($property_id <= 0 && $property_name === '') {
            return $this->json_response(false, 'property_id or property is required', [], 422);
        }

        // ID-first resolution (live/local DB ids), name fallback.
        $city = null;
        if ($location_id > 0) {
            $city = $this->WhatsappLead_model->find_city_by_id($location_id);
            if (empty($city)) {
                return $this->json_response(false, 'Location not found or inactive', [], 422);
            }
        } else {
            $city = $this->WhatsappLead_model->find_city_by_name($location_name);
            if (empty($city)) {
                return $this->json_response(false, 'Location not found or inactive', [], 422);
            }
        }

        $property = null;
        if ($property_id > 0) {
            $property = $this->WhatsappLead_model->find_property_by_id($property_id);
            if (empty($property)) {
                return $this->json_response(false, 'Property not found or inactive', [], 422);
            }
        } else {
            $property = $this->WhatsappLead_model->find_property_by_name($property_name);
            if (empty($property)) {
                return $this->json_response(false, 'Property not found or inactive', [], 422);
            }
        }

        if ((int) $property->city_id !== (int) $city->city_id) {
            return $this->json_response(
                false,
                'Property does not belong to the selected location',
                [],
                422
            );
        }

        $department = null;
        if ($department_id > 0) {
            $department = $this->WhatsappLead_model->get_department_by_id($department_id);
            if (empty($department)) {
                return $this->json_response(false, 'Department not found or inactive', [], 422);
            }
        } else {
            if ($service === '') {
                $service = 'Restaurants';
            }
            $department = $this->WhatsappLead_model->find_department_by_name($service);
            if (empty($department)) {
                $department = $this->WhatsappLead_model->get_department_by_id(2);
            }
            if (empty($department)) {
                return $this->json_response(false, 'Department not found or inactive', [], 422);
            }
        }

        $restaurant = null;
        if ($restaurant_id > 0) {
            $restaurant = $this->WhatsappLead_model->find_restaurant_by_id_for_property(
                $restaurant_id,
                $property->hotel_id
            );
            if (empty($restaurant)) {
                return $this->json_response(
                    false,
                    'Restaurant not found for the selected property',
                    [],
                    422
                );
            }
        } elseif ($restaurant_name !== '') {
            $restaurant = $this->WhatsappLead_model->find_restaurant_by_name_for_property(
                $restaurant_name,
                $property->hotel_id
            );
            if (empty($restaurant)) {
                return $this->json_response(
                    false,
                    'Restaurant not found for the selected property',
                    [],
                    422
                );
            }
        }

        $guests = isset($input['guests']) ? (int) $input['guests'] : 0;
        $booking_date = trim((string) ($input['date'] ?? ''));
        $checkin_date = trim((string) ($input['checkin_date'] ?? ''));
        $checkout_date = trim((string) ($input['checkout_date'] ?? ''));
        $time_label = trim((string) ($input['time'] ?? ''));
        $meal = trim((string) ($input['meal'] ?? ''));
        $occasion = trim((string) ($input['occasion'] ?? ''));
        $special_request = trim((string) ($input['special_requests'] ?? ''));
        if ($special_request === '') {
            $special_request = trim((string) ($input['special_requirement'] ?? ''));
        }
        $extra_queries = trim((string) ($input['queries'] ?? ''));
        $reservation_uid = trim((string) ($input['reservation_uid'] ?? ''));

        if ($booking_date === '' && $checkin_date !== '') {
            $booking_date = $checkin_date;
        }

        $restaurant_title = $restaurant
            ? (string) $restaurant->restaurant_name
            : $restaurant_name;

        $query_parts = [];
        if ($booking_date !== '') {
            $query_parts[] = 'Date: ' . $booking_date;
        }
        if ($time_label !== '') {
            $query_parts[] = 'Time: ' . $time_label;
        }
        if ($meal !== '') {
            $query_parts[] = 'Meal: ' . $meal;
        }
        if ($guests > 0) {
            $query_parts[] = 'Guests: ' . $guests;
        }
        if ($restaurant_title !== '') {
            $query_parts[] = 'Restaurant: ' . $restaurant_title;
        }
        if ($occasion !== '') {
            $query_parts[] = 'Occasion: ' . $occasion;
        }
        if ($special_request !== '') {
            $query_parts[] = 'Special request: ' . $special_request;
        }
        if ($extra_queries !== '') {
            $query_parts[] = 'Queries: ' . $extra_queries;
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
            'checkin_date' => $checkin_date !== '' ? $checkin_date : null,
            'checkout_date' => $checkout_date !== '' ? $checkout_date : null,
            'time' => $time_label !== '' ? $time_label : null,
            'arrival_time' => $time_label !== '' ? $time_label : null,
            'restaurant_id' => $restaurant ? (int) $restaurant->id : null,
            'special_occasion' => $occasion !== '' ? $occasion : null,
            'special_request' => $special_request !== '' ? $special_request : null,
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

    /**
     * Shared GET catalog guard: CORS, method, Bearer auth.
     * Returns false when a response was already sent.
     */
    private function prepare_catalog_request($allowed_method)
    {
        $this->apply_cors_headers(['GET', 'OPTIONS']);

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== $allowed_method) {
            $this->json_response(false, 'Only ' . $allowed_method . ' is allowed', [], 405);
            return false;
        }

        if (!$this->is_bearer_authorized()) {
            $this->json_response(false, 'Unauthorized', [], 401);
            return false;
        }

        return true;
    }

    private function apply_cors_headers(array $methods = ['GET', 'POST', 'OPTIONS'])
    {
        $methods[] = 'OPTIONS';
        $methods = array_values(array_unique($methods));

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: ' . implode(', ', $methods));
        header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept, X-Requested-With');
        header('Content-Type: application/json');
        header('Accept: application/json');
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

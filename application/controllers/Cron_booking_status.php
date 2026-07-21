<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron_booking_status extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('LeadModel'); // Load Model
        $this->load->model('Mycloud_config_model');

    }


    public function update_all_confirmed_bookings()
    {
        $confirmedBookings = $this->LeadModel->getConfirmedBookings();

        // echo "<pre>";
        // print_r($confirmedBookings);
        // die();

        foreach ($confirmedBookings as $booking) {

            $booking_id = $booking->booking_id;

            $booking_status = $booking->booking_status_description;



            $latestData = $this->search_bookings($booking_id, $booking_status);





            if ($latestData) {
                // Update database with new PMS data
                $this->LeadModel->updateBookingInfo($booking_id, $latestData);
            } else {
                echo "Failed to update Booking ID: $booking_id<br>";
            }
        }
    }


    public function search_bookings($booking_id, $booking_status)
    {
        $config = $this->Mycloud_config_model->get_runtime_config();
        if (!$config->is_ready) {
            log_message('error', 'MyCloud booking status sync skipped because the PMS configuration is incomplete.');
            return [];
        }

        // === Request body ===
        $requestBody = [
            "chain_code"                => $config->chain_code,
            "hotel_code"                => "E0701",
            "booking_status"            => "$booking_status",
            "confirmation_number"       => "",
            "booking_id"                => $booking_id,
            "room_type_code"            => "",
            "room_number"               => "",
            "guest_name"                => "",
            "date_arrive"               => "",
            "date_depart"               => "",
            "contact_number"            => "",
            "email_id"                  => "",
            "calculate_revenues"        => "Y",
            "booking_type_indicator"    => "",
            "booking_type_date_from"    => "",
            "booking_type_date_to"      => "",
            "booking_stay_indicator"    => "",
            "booking_stay_date_from"    => "",
            "booking_stay_date_to"      => "",
            "calculate_bookings_summary" => "Y"
        ];

        $jsonData = json_encode($requestBody, JSON_UNESCAPED_SLASHES);

        // === API credentials ===
        $url        = $this->Mycloud_config_model->get_endpoint('searchbookings');
        $authCode   = $config->auth_code;
        $clientId   = $config->client_id;

        // === cURL Setup ===
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $jsonData,
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "authCODE: $authCode",
                "clientID: $clientId"
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // cURL ERROR handling
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);

            echo json_encode([
                "status"  => "error",
                "message" => "Curl Error: " . $error_msg
            ], JSON_PRETTY_PRINT);
            return;
        }

        curl_close($curl);

        // HTTP status error
        if ($httpCode !== 200) {
            echo json_encode([
                "status"  => "error",
                "message" => "API returned HTTP Status $httpCode",
                "raw"     => $response
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Convert API response to array
        $decoded = json_decode($response, true);

        // Response validation
        if (empty($decoded)) {
            echo json_encode([
                "status"  => "error",
                "message" => "Invalid JSON response",
                "raw"     => $response
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Everything is OK, return data to frontend


        if (!isset($decoded['bookings'][0])) {
            return [];
        }

        $booking_info = $decoded['bookings'][0];

        return [
            'name_last' => $booking_info['name_last'] ?? null,
            'name_first' => $booking_info['name_first'] ?? null,
            'date_arrive' => $booking_info['date_arrive'] ?? null,
            'time_arrive' => $booking_info['time_arrive'] ?? null,
            'date_depart' => $booking_info['date_depart'] ?? null,
            'time_depart' => $booking_info['time_depart'] ?? null,
            'adults' => $booking_info['adults'] ?? 0,
            'room_type' => $booking_info['room_type'] ?? null,
            'pms_confirmation_number' => $booking_info['pms_confirmation_number'] ?? null,
            'booking_status' => $booking_info['booking_status'] ?? null,
            'booking_status_description' => $booking_info['booking_status_description'] ?? null,
            'created_at' => $booking_info['created_at'] ?? null,
            'payment_method_code' => $booking_info['payment_method_code'] ?? null,
            'payment_method_description' => $booking_info['payment_method_description'] ?? null,
            'date_modified' => $booking_info['date_modified'] ?? null,
            'revenue_room' => (float)($booking_info['revenue_room'] ?? 0),
            'tax_room' => (float)($booking_info['tax_room'] ?? 0),
            'total_room' => (float)($booking_info['total_room'] ?? 0),
            'revenue_fnb' => (float)($booking_info['revenue_fnb'] ?? 0),
            'tax_fnb' => (float)($booking_info['tax_fnb'] ?? 0),
            'total_fnb' => (float)($booking_info['total_fnb'] ?? 0),
            'revenue_other' => (float)($booking_info['revenue_other'] ?? 0),
            'tax_other' => (float)($booking_info['tax_other'] ?? 0),
            'total_other' => (float)($booking_info['total_other'] ?? 0),
            'deposits' => (float)($booking_info['deposits'] ?? 0),
            'deposit_required_amount' => (float)($booking_info['deposit_required_amount'] ?? 0),
            'revenue_grand_total' =>
            (float)($booking_info['total_room'] ?? 0) +
                (float)($booking_info['total_fnb'] ?? 0) +
                (float)($booking_info['total_other'] ?? 0),
        ];
    }
}

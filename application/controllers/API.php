<?php
defined('BASEPATH') or exit('No direct script access allowed');

class API extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('API_Model');


        $this->load->model('LeadModel');


        header('Content-Type: application/json');
    }

    /**
     * Common Response Function
     */
    private function response($status, $message, $data = [])
    {
        echo json_encode([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    /**
     * 1. Property List API
     */
    public function property_list()
    {
        $data = $this->API_Model->get_property_list();

        if (!empty($data)) {

            foreach ($data as $key => $row) {

                if (!empty($row['hotel_image'])) {
                    $data[$key]['hotel_image_url'] = base_url('uploads/hotel_images/' . $row['hotel_image']);
                } else {
                    $data[$key]['hotel_image_url'] = '';
                }
            }

            $this->response(true, 'Property list fetched successfully', $data);
        } else {
            $this->response(false, 'No property found');
        }
    }




    public function getPropertyByCity()
    {

        $input = json_decode(file_get_contents('php://input'), true);

        $city_id = $input['city_id'];

        // validation
        if (empty($city_id)) {

            $this->response(
                false,
                'City ID is required'
            );

            return;
        }

        $data = $this->API_Model
            ->getPropertyByCity($city_id);

        if (!empty($data)) {

            foreach ($data as $key => $row) {

                if (!empty($row['hotel_image'])) {

                    $data[$key]['hotel_image_url'] =
                        base_url(
                            'uploads/hotel_images/' .
                                $row['hotel_image']
                        );
                } else {

                    $data[$key]['hotel_image_url'] = '';
                }
            }

            $this->response(
                true,
                'Property list fetched successfully',
                $data
            );
        } else {

            $this->response(
                false,
                'No property found'
            );
        }
    }
    /**
     * 2. Department List API (for future use)
     */
    public function department_list()
    {

        $data = $this->API_Model->get_department_list();

        if (!empty($data)) {
            $this->response(true, 'Department list fetched successfully', $data);
        } else {
            $this->response(false, 'No department found');
        }
    }

    public function getCities()
    {
        $data = $this->API_Model->getCities();

        if (!empty($data)) {

            $this->response(
                true,
                'Cities fetched successfully',
                $data
            );
        } else {

            $this->response(
                false,
                'No cities found'
            );
        }
    }



    /**
     * Get Restaurant List by Hotel ID
     */
    public function restaurant_list_by_hotel()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        // Get hotel_id
        $hotel_id = $input['hotel_id'] ?? '';

        if (empty($hotel_id)) {
            $this->response(false, 'hotel_id is required');
        }

        $hotel_id = (int) $hotel_id;

        // Fetch data
        $data = $this->API_Model->get_restaurants_by_hotel($hotel_id);

        if (!empty($data)) {

            foreach ($data as $key => $row) {

                // ✅ safe array handling
                $image = isset($row['restaurant_image']) ? $row['restaurant_image'] : '';

                $data[$key]['restaurant_image_url'] = !empty($image)
                    ? base_url('uploads/restaurant_images/' . $image)
                    : '';
            }

            $this->response(true, 'Restaurant list fetched successfully', $data);
        } else {
            $this->response(false, 'No restaurants found', []);
        }
    }


    /**
     * Get Time Slots List
     */
    public function time_slots()
    {

        $data = $this->API_Model->get_time_slots();

        if (!empty($data)) {
            $this->response(true, 'Time slots fetched successfully', $data);
        } else {
            $this->response(false, 'No time slots found', []);
        }
    }




    public function save_lead()
    {
        /* -------------------------------
     *  CORS
     * ----------------------------- */
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            exit(0);
        }

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        /* -------------------------------
     *  READ INPUT (JSON)
     * ----------------------------- */
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {
            return $this->response(false, 'Invalid JSON payload', [], 400);
        }

        /* -------------------------------
     *  VALIDATION
     * ----------------------------- */
        $required = ['name', 'phone', 'property', 'department'];

        foreach ($required as $field) {
            if (empty($input[$field])) {
                return $this->response(false, "$field is required", [], 422);
            }
        }

        /* -------------------------------
     *  PREPARE DATA
     * ----------------------------- */
        $lead_data = [
            'user_name'    => trim($input['name']),
            'email'        => trim($input['email'] ?? ''),
            'phone_number' => trim($input['phone']),
            'query'        => trim($input['query'] ?? ''),
            'property'     => trim($input['property']),
            'type'         => trim($input['department']),
            'user_channel' => trim($input['user_channel'] ?? 'Website'),
            'pax'          => (int) ($input['pax'] ?? 0),
            'booking_date' => $input['booking_date'] ?? null,
            'restaurant_id' => $input['restaurant_id'] ?? null,
            'time_slot_id' => $input['time_slot_id'] ?? null,
            'checkin_date' => $input['checkin_date'] ?? null,
            'checkout_date' => $input['checkout_date'] ?? null,
            'special_request' => $input['special_request'] ?? null,
            'special_occasion' => $input['special_occasion'] ?? null,
            'arrival_time' => $input['arrival_time'] ?? null,

            'status'       => 'Open',
            'created_at'   => date('Y-m-d H:i:s'),
            'date'         => date('Y-m-d'),
            'time'         => date('H:i:s'),
            'ip_address'   => $this->input->ip_address()
        ];





        /* -------------------------------
     *  DUPLICATE CHECK
     * ----------------------------- */
        $last10 = substr($lead_data['phone_number'], -10);
        $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));

        $existing = $this->db
            ->where("RIGHT(phone_number,10) =", $last10, false)
            ->where('created_at >=', $twoHoursAgo)
            ->where('status !=', 'Closed')
            ->get('leads')
            ->row();

        if ($existing) {
            unset($lead_data['created_at']);

            $this->db->where('id', $existing->id)->update('leads', $lead_data);

            return $this->response(true, 'Lead updated successfully');
        }

        /* -------------------------------
     *  INSERT
     * ----------------------------- */
        $insert_id = $this->LeadModel->insert_lead($lead_data);

        if (!$insert_id) {
            return $this->response(false, 'Failed to save lead', [], 500);
        }

        $this->sendwhatsappMessageToAgnent($insert_id);

        return $this->response(true, 'Lead submitted successfully');
    }



    public function save_meta_leads()
    {
        /* -------------------------------
     *  CORS
     * ----------------------------- */
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            exit(0);
        }

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        /* -------------------------------
     *  READ INPUT (JSON)
     * ----------------------------- */
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {
            return $this->response(false, 'Invalid JSON payload', [], 400);
        }

        /* -------------------------------
     *  VALIDATION
     * ----------------------------- */
        $required = ['name', 'phone', 'property', 'department'];

        foreach ($required as $field) {
            if (empty($input[$field])) {
                return $this->response(false, "$field is required", [], 422);
            }
        }

        /* -------------------------------
     *  PREPARE DATA
     * ----------------------------- */
        $lead_data = [
            'user_name'    => trim($input['name']),
            'email'        => trim($input['email'] ?? ''),
            'phone_number' => trim($input['phone']),
            'query'        => trim($input['query'] ?? ''),
            'property'     => trim($input['property']),
            'type'         => trim($input['department']),
            'user_channel' => trim($input['user_channel'] ?? 'Website'),
            'pax'          => (int) ($input['pax'] ?? 0),
            'booking_date' => $input['booking_date'] ?? null,
            'booking_month' => $input['booking_month'] ?? null,
            'number_of_rooms' => $input['number_of_rooms'] ?? null,
            'status'       => 'Open',
            'created_at'   => date('Y-m-d H:i:s'),
            'date'         => date('Y-m-d'),
            'time'         => date('H:i:s'),
            'ip_address'   => $this->input->ip_address()
        ];



        // $assigned_to = 9; // crs3


        // // Assign values if matched
        // if ($assigned_to !== null) {
        //     $lead_data['assigned_to'] = $assigned_to;
        //     $lead_data['assigned_person_user_role'] = 'super_admin';
        //     $lead_data['is_assigned'] = 1;
        // }



        /* -------------------------------
     *  DUPLICATE CHECK
     * ----------------------------- */
        $last10 = substr($lead_data['phone_number'], -10);
        $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));

        $existing = $this->db
            ->where("RIGHT(phone_number,10) =", $last10, false)
            ->where('created_at >=', $twoHoursAgo)
            ->where('status !=', 'Closed')
            ->get('leads')
            ->row();

        if ($existing) {
            unset($lead_data['created_at']);

            $this->db->where('id', $existing->id)->update('leads', $lead_data);

            return $this->response(true, 'Lead updated successfully');
        }

        /* -------------------------------
     *  INSERT
     * ----------------------------- */
        $insert_id = $this->LeadModel->insert_lead($lead_data);

        if (!$insert_id) {
            return $this->response(false, 'Failed to save lead', [], 500);
        }

        return $this->response(true, 'Lead submitted successfully');
    }



    public function sendwhatsappMessageToAgnent($leadID = 0)
    {
        if (empty($leadID)) {
            show_404();
        }

        $this->load->model('Comman_model');

        // Get Lead Data
        $leadData = $this->Comman_model->getdata('leads', ['id' => $leadID]);


        if (!$leadData) {
            log_message('error', 'Lead not found for ID: ' . $leadID);
            show_404();
        }

        // Get Hotel & Department Data
        $hotel      = $this->Comman_model->getdata('hotel_admin', ['hotel_id' => $leadData->property]);
        $department = $this->Comman_model->getdata('departments', ['department_id' => $leadData->type]);

        $hotel_id       = $leadData->property;
        $department_id  = $leadData->type;

        $property_name   = !empty($hotel->hotel_name) ? $hotel->hotel_name : 'N/A';
        $department_name = !empty($department->department_name) ? $department->department_name : 'N/A';

        $guest_name   = !empty($leadData->user_name) ? $leadData->user_name : 'Guest';
        $guest_phone  = !empty($leadData->phone_number) ? $leadData->phone_number : '';
        $guest_query  = !empty($leadData->query) ? $leadData->query : 'No Query';

        // Get Assigned Agents
        $this->db->select('staff_members.name, staff_members.phone');
        $this->db->from('staff_members');
        $this->db->join(
            'staff_hotel_department_mapping',
            'staff_members.id = staff_hotel_department_mapping.staff_id'
        );
        $this->db->where('staff_hotel_department_mapping.level', 1);
        $this->db->where('staff_hotel_department_mapping.hotel_id', $hotel_id);
        $this->db->where('staff_hotel_department_mapping.department_id', $department_id);

        $query  = $this->db->get();
        $result = $query->result();

        if (empty($result)) {
            log_message('error', 'No Agent Found For Lead ID: ' . $leadID);
            return false;
        }

        foreach ($result as $each_agent) {

            $agent_name  = $each_agent->name;
            $agent_phone = $each_agent->phone;

            $postData = [
                "agent_name"       => $agent_name,
                "agent_phone"      => $agent_phone,
                "department_name"  => $department_name,
                "property_name"    => $property_name,
                "guest_name"       => $guest_name,
                "guest_phone"      => $guest_phone,
                "guest__query"     => $guest_query
            ];




            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.wisemelon.ai/api/trigger/invoke/69edbc8e654385236649a5af",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ],
                CURLOPT_TIMEOUT => 30
            ]);

            $response = curl_exec($ch);
            $error    = curl_error($ch);

            curl_close($ch);

            if ($error) {
                log_message('error', 'WhatsApp API Error: ' . $error);
            } else {
                log_message('error', 'WhatsApp Sent To: ' . $agent_phone . ' Response: ' . $response);
            }
        }

        return true;
    }
}

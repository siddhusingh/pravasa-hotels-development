<?php

class Feedback extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Feedback_model');
        $this->load->model('Common_model');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');
    }

    /**
     * WhatsApp Webhook Handler
     */

    // this is for reservation feedback and table vacant feedback
    public function save_feedback()
    {
        // Get raw JSON
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        // Basic validation
        if (
            !$data ||
            empty($data['lead_id']) ||
            empty($data['restaurant_id']) ||
            empty($data['mobile']) ||
            empty($data['rating'])
        ) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing required fields'
            ]);
            return;
        }

        // Prepare data
        $insertData = [
            'res_id'         => $data['restaurant_id'],
            'lead_id'         => $data['lead_id'],
            'guest_name'     => $data['guest_name'] ?? null,
            'restaurant_name'     => $data['restaurant_name'] ?? null,
            'mobile'         => $data['mobile'],
            'rating'         => (int)$data['rating'],
            'remark'         => $data['remark'] ?? null
        ];

        // Save
        $this->Feedback_model->insert_feedback($insertData);

        echo json_encode(
            [
                'status' => 'success',
                'message' => 'Feedback saved successfully'
            ]
        );
    }


    // this is for banquet feedback
    public function save_banquet_feedback()
    {
        header('Content-Type: application/json');

        // Get raw JSON
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        // Validation
        if (
            !$data ||
            empty($data['lead_id']) ||
            empty($data['mobile'])
        ) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing required fields'
            ]);
            return;
        }

        // Prepare insert data
        $insertData = [

            'lead_id'                  => $data['lead_id'],
            'property_id'              => $data['property_id'] ?? null,
            'guest_name'               => $data['guest_name'] ?? null,
            'mobile'                   => $data['mobile'],

            'overall_experience'       => $data['overall_experience'] ?? null,
            'reservation_experience'   => $data['reservation_experience'] ?? null,
            'event_experience'         => $data['event_experience'] ?? null,
            'decor_ambience'           => $data['decor_ambience'] ?? null,
            'lighting_air_condition'   => $data['lighting_air_condition'] ?? null,
            'food_beverage_quality'    => $data['food_beverage_quality'] ?? null,
            'staff_service'            => $data['staff_service'] ?? null,

            'recommendation_score'     => $data['recommendation_score'] ?? null,
            'comment'                  => $data['comment'] ?? null
        ];

        // Save data
        $feedback_id = $this->Feedback_model->insert_banquet_feedback($insertData);

        if ($feedback_id) {

            echo json_encode([
                'status' => 'success',
                'message' => 'Banquet feedback saved successfully',
                'feedback_id' => $feedback_id
            ]);
        } else {

            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong'
            ]);
        }
    }


    public function save_room_feedback()
    {
        header('Content-Type: application/json');

        // Get raw JSON
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        // Validation
        if (
            !$data ||
            empty($data['lead_id']) ||
            empty($data['mobile'])
        ) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Missing required fields'
            ]);
            return;
        }

        // Prepare insert data
        $insertData = [

            'lead_id'               => $data['lead_id'],
            'property_id'           => $data['property_id'] ?? null,
            'guest_name'            => $data['guest_name'] ?? null,
            'mobile'                => $data['mobile'],

            'room_stay_experience'  => $data['room_stay_experience'] ?? null,
            'food_quality'          => $data['food_quality'] ?? null,
            'staff_service'         => $data['staff_service'] ?? null,
            'cleanliness_hygiene'   => $data['cleanliness_hygiene'] ?? null,
            'overall_satisfaction'  => $data['overall_satisfaction'] ?? null,

            'recommendation_score'  => $data['recommendation_score'] ?? null,
            'comment'               => $data['comment'] ?? null
        ];

        // Save feedback
        $feedback_id = $this->Feedback_model->insert_room_feedback($insertData);

        if ($feedback_id) {

            echo json_encode([
                'status'       => 'success',
                'message'      => 'Room feedback saved successfully',
                'feedback_id'  => $feedback_id
            ]);
        } else {

            echo json_encode([
                'status'  => 'error',
                'message' => 'Something went wrong'
            ]);
        }
    }






    public function feedback_list()
    {
        // Get filters (POST or GET)
        $filters = [
            'res_id'     => $this->input->get_post('res_id'),
            'rating'     => $this->input->get_post('rating'),
            'start_date' => $this->input->get_post('start_date'),
            'end_date'   => $this->input->get_post('end_date'),
        ];

        $data = $this->Feedback_model->get_feedback_list($filters);

        echo json_encode([
            'status' => 'success',
            'data'   => $data
        ]);
    }


    public function feedback_list_view()
    {
        $this->render_feedback_page('restaurant', 'Restaurant Feedback');
    }

    private function render_feedback_page($module, $title)
    {
        $data['module'] = $module;
        $data['page_title'] = $title;

        if ($module === 'restaurant') {
            $data['items'] = $this->Common_model->getAllData('hotel_restaurants', "", 'restaurant_name');
        } else {
            $data['items'] = $this->Common_model->getAllData('hotel_admin', "", 'hotel_name');
        }

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/feedback_list', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function restaurant_feedback_list_view()
    {
        $this->render_feedback_page('restaurant', 'Restaurant Feedback');
    }

    public function banquet_feedback_list_view()
    {
        $this->render_feedback_page('banquet', 'Banquet Feedback');
    }

    public function room_feedback_list_view()
    {
        $this->render_feedback_page('room', 'Room Feedback');
    }

    public function restaurant_feedback_list()
    {
        if ($this->input->post('draw') !== null) {
            $inputs = $this->input->post();
            $draw = (int) ($inputs['draw'] ?? 0);

            if (!$this->db->table_exists('reservation_feedback')) {
                return $this->output->set_content_type('application/json')->set_output(json_encode([
                    'draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [],
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
            }

            $start = max(0, (int) ($inputs['start'] ?? 0));
            $length = (int) ($inputs['length'] ?? 10);
            $length = ($length > 0 && $length <= 100) ? $length : 10;
            $search = trim($inputs['search']['value'] ?? '');
            $restaurantId = $inputs['res_id'] ?? '';
            $rating = $inputs['rating'] ?? '';
            $startDate = trim($inputs['start_date'] ?? '');
            $endDate = trim($inputs['end_date'] ?? '');

            $columns = ['rf.id', 'hr.restaurant_name', 'rf.guest_name', 'rf.mobile', 'rf.rating', 'rf.remark', 'rf.created_at'];
            $orderIndex = (int) ($inputs['order'][0]['column'] ?? 6);
            $order = $columns[$orderIndex] ?? 'rf.created_at';
            $direction = strtolower($inputs['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

            $applyFilters = function () use ($search, $restaurantId, $rating, $startDate, $endDate) {
                if ($restaurantId !== '') $this->db->where('rf.res_id', $restaurantId);
                if ($rating !== '') $this->db->where('rf.rating', $rating);
                if ($startDate !== '') $this->db->where('rf.created_at >=', $startDate . ' 00:00:00');
                if ($endDate !== '') $this->db->where('rf.created_at <=', $endDate . ' 23:59:59');
                if ($search !== '') {
                    $this->db->group_start()->like('hr.restaurant_name', $search)
                        ->or_like('rf.guest_name', $search)->or_like('rf.mobile', $search)
                        ->or_like('rf.remark', $search)->group_end();
                }
            };

            $recordsTotal = $this->db->count_all('reservation_feedback');
            $this->db->from('reservation_feedback rf')->join('hotel_restaurants hr', 'hr.id = rf.res_id', 'left');
            $applyFilters();
            $recordsFiltered = $this->db->count_all_results();

            $this->db->select('rf.*, hr.restaurant_name')->from('reservation_feedback rf')
                ->join('hotel_restaurants hr', 'hr.id = rf.res_id', 'left');
            $applyFilters();
            $rows = $this->db->order_by($order, $direction)->limit($length, $start)->get()->result();

            $data = [];
            $number = $start + 1;
            foreach ($rows as $row) {
                $value = function ($field, $fallback = '-') use ($row) {
                    return html_escape(isset($row->$field) && $row->$field !== '' ? $row->$field : $fallback);
                };
                $stars = '';
                for ($i = 1; $i <= 5; $i++) $stars .= $i <= (int) $row->rating ? '⭐' : '☆';
                $data[] = [$number++, $value('restaurant_name', 'N/A'), $value('guest_name', 'N/A'),
                    $value('mobile', 'N/A'), $stars, $value('remark'),
                    !empty($row->created_at) ? date('d-m-Y H:i:s', strtotime($row->created_at)) : '-'];
            }

            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'draw' => $draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered,
                'data' => $data, 'csrfHash' => $this->security->get_csrf_hash()
            ]));
        }

        $filters = [
            'res_id'     => $this->input->get_post('res_id'),
            'rating'     => $this->input->get_post('rating'),
            'start_date' => $this->input->get_post('start_date'),
            'end_date'   => $this->input->get_post('end_date'),
        ];

        $data = $this->Feedback_model->get_feedback_list($filters);

        echo json_encode([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    public function banquet_feedback_list()
    {
        if ($this->input->post('draw') !== null) {
            $inputs = $this->input->post();
            $draw = (int) ($inputs['draw'] ?? 0);

            if (!$this->db->table_exists('banquet_feedback')) {
                return $this->output->set_content_type('application/json')->set_output(json_encode([
                    'draw' => $draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
            }

            $start = max(0, (int) ($inputs['start'] ?? 0));
            $length = (int) ($inputs['length'] ?? 10);
            $length = ($length > 0 && $length <= 100) ? $length : 10;
            $search = trim($inputs['search']['value'] ?? '');
            $propertyId = $inputs['property_id'] ?? '';
            $startDate = trim($inputs['start_date'] ?? '');
            $endDate = trim($inputs['end_date'] ?? '');

            $columns = ['bf.id', 'ha.hotel_name', 'bf.guest_name', 'bf.mobile', 'bf.overall_experience',
                'bf.reservation_experience', 'bf.event_experience', 'bf.decor_ambience',
                'bf.lighting_air_condition', 'bf.food_beverage_quality', 'bf.staff_service',
                'bf.recommendation_score', 'bf.comment', 'bf.created_at'];
            $orderIndex = (int) ($inputs['order'][0]['column'] ?? 13);
            $order = $columns[$orderIndex] ?? 'bf.created_at';
            $direction = strtolower($inputs['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

            $applyFilters = function () use ($search, $propertyId, $startDate, $endDate) {
                if ($propertyId !== '') $this->db->where('bf.property_id', $propertyId);
                if ($startDate !== '') $this->db->where('bf.created_at >=', $startDate . ' 00:00:00');
                if ($endDate !== '') $this->db->where('bf.created_at <=', $endDate . ' 23:59:59');
                if ($search !== '') {
                    $this->db->group_start()->like('ha.hotel_name', $search)
                        ->or_like('bf.guest_name', $search)->or_like('bf.mobile', $search)
                        ->or_like('bf.comment', $search)->group_end();
                }
            };

            $recordsTotal = $this->db->count_all('banquet_feedback');
            $this->db->from('banquet_feedback bf')->join('hotel_admin ha', 'ha.hotel_id = bf.property_id', 'left');
            $applyFilters();
            $recordsFiltered = $this->db->count_all_results();

            $this->db->select('bf.*, ha.hotel_name')->from('banquet_feedback bf')
                ->join('hotel_admin ha', 'ha.hotel_id = bf.property_id', 'left');
            $applyFilters();
            $rows = $this->db->order_by($order, $direction)->limit($length, $start)->get()->result();

            $data = [];
            $number = $start + 1;
            foreach ($rows as $row) {
                $value = function ($field, $fallback = '-') use ($row) {
                    return html_escape(isset($row->$field) && $row->$field !== '' ? $row->$field : $fallback);
                };
                $data[] = [$number++, $value('hotel_name', 'N/A'), $value('guest_name', 'N/A'),
                    $value('mobile', 'N/A'), $value('overall_experience'), $value('reservation_experience'),
                    $value('event_experience'), $value('decor_ambience'), $value('lighting_air_condition'),
                    $value('food_beverage_quality'), $value('staff_service'), $value('recommendation_score'),
                    $value('comment'), !empty($row->created_at) ? date('d-m-Y H:i:s', strtotime($row->created_at)) : '-'];
            }

            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'draw' => $draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered,
                'data' => $data, 'csrfHash' => $this->security->get_csrf_hash()
            ]));
        }

        $filters = [
            'property_id' => $this->input->get_post('property_id'),
            'start_date'  => $this->input->get_post('start_date'),
            'end_date'    => $this->input->get_post('end_date'),
        ];

        $data = $this->Feedback_model->get_banquet_feedback_list($filters);

        echo json_encode([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    public function room_feedback_list()
    {
        if ($this->input->post('draw') !== null) {
            $inputs = $this->input->post();
            $draw = (int) ($inputs['draw'] ?? 0);

            if (!$this->db->table_exists('room_feedback')) {
                return $this->output->set_content_type('application/json')->set_output(json_encode([
                    'draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [],
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
            }

            $start = max(0, (int) ($inputs['start'] ?? 0));
            $length = (int) ($inputs['length'] ?? 10);
            $length = ($length > 0 && $length <= 100) ? $length : 10;
            $search = trim($inputs['search']['value'] ?? '');
            $propertyId = $inputs['property_id'] ?? '';
            $startDate = trim($inputs['start_date'] ?? '');
            $endDate = trim($inputs['end_date'] ?? '');

            $columns = ['rf.id', 'ha.hotel_name', 'rf.guest_name', 'rf.mobile', 'rf.room_stay_experience',
                'rf.food_quality', 'rf.staff_service', 'rf.cleanliness_hygiene', 'rf.overall_satisfaction',
                'rf.recommendation_score', 'rf.comment', 'rf.created_at'];
            $orderIndex = (int) ($inputs['order'][0]['column'] ?? 11);
            $order = $columns[$orderIndex] ?? 'rf.created_at';
            $direction = strtolower($inputs['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

            $applyFilters = function () use ($search, $propertyId, $startDate, $endDate) {
                if ($propertyId !== '') $this->db->where('rf.property_id', $propertyId);
                if ($startDate !== '') $this->db->where('rf.created_at >=', $startDate . ' 00:00:00');
                if ($endDate !== '') $this->db->where('rf.created_at <=', $endDate . ' 23:59:59');
                if ($search !== '') {
                    $this->db->group_start()->like('ha.hotel_name', $search)
                        ->or_like('rf.guest_name', $search)->or_like('rf.mobile', $search)
                        ->or_like('rf.comment', $search)->group_end();
                }
            };

            $recordsTotal = $this->db->count_all('room_feedback');
            $this->db->from('room_feedback rf')->join('hotel_admin ha', 'ha.hotel_id = rf.property_id', 'left');
            $applyFilters();
            $recordsFiltered = $this->db->count_all_results();

            $this->db->select('rf.*, ha.hotel_name')->from('room_feedback rf')
                ->join('hotel_admin ha', 'ha.hotel_id = rf.property_id', 'left');
            $applyFilters();
            $rows = $this->db->order_by($order, $direction)->limit($length, $start)->get()->result();

            $data = [];
            $number = $start + 1;
            foreach ($rows as $row) {
                $value = function ($field, $fallback = '-') use ($row) {
                    return html_escape(isset($row->$field) && $row->$field !== '' ? $row->$field : $fallback);
                };
                $data[] = [$number++, $value('hotel_name', 'N/A'), $value('guest_name', 'N/A'),
                    $value('mobile', 'N/A'), $value('room_stay_experience'), $value('food_quality'),
                    $value('staff_service'), $value('cleanliness_hygiene'), $value('overall_satisfaction'),
                    $value('recommendation_score'), $value('comment'),
                    !empty($row->created_at) ? date('d-m-Y H:i:s', strtotime($row->created_at)) : '-'];
            }

            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'draw' => $draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered,
                'data' => $data, 'csrfHash' => $this->security->get_csrf_hash()
            ]));
        }

        $filters = [
            'property_id' => $this->input->get_post('property_id'),
            'start_date'  => $this->input->get_post('start_date'),
            'end_date'    => $this->input->get_post('end_date'),
        ];

        $data = $this->Feedback_model->get_room_feedback_list($filters);

        echo json_encode([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * Create feedback entry (call after reservation completed / table vacant)
     */
    public function create_feedback_entry()
    {
        $reservation_id = $this->input->post('reservation_id');
        $res_id         = $this->input->post('res_id'); // restaurant_id
        $name           = $this->input->post('guest_name');
        $mobile         = $this->input->post('mobile');
        $restaurant     = $this->input->post('restaurant_name');

        $data = [
            'reservation_id' => $reservation_id,
            'res_id'         => $res_id,
            'guest_name'     => $name,
            'mobile'         => $mobile,
            'restaurant_name' => $restaurant,
            'feedback_status' => 'pending'
        ];

        $this->Feedback_model->insert_feedback($data);

        // Send initial WhatsApp message
        $msg = "Hi $name,\n\nThanks for visiting $restaurant 😊\n\nPlease rate your experience:\n\n1 ⭐\n2 ⭐⭐\n3 ⭐⭐⭐\n4 ⭐⭐⭐⭐\n5 ⭐⭐⭐⭐⭐\n\nReply with a number.";

        $this->send_message($mobile, $msg);

        echo json_encode(['status' => 'success']);
    }




    /**
     * WhatsApp Send Function (Replace with API)
     */
    private function send_message($mobile, $message)
    {
        // Replace with your WhatsApp provider API (Gupshup / Twilio / Meta)

        log_message('error', "Sending message to $mobile: $message");

        // Example curl integration can go here
    }
}

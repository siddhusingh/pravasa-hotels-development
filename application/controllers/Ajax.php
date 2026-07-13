<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model'); // Load common model once
    }

    // Example: Get states based on country_id
    public function get_states_by_country()
    {
        $country_id = $this->input->post('country_id');
        if ($country_id) {
            $states = $this->Common_model->getAllData('state', ['country_id' => $country_id]);
            echo json_encode($states);
        } else {
            echo json_encode([]);
        }
    }

    public function get_cities_by_state()
    {
        $state_id = $this->input->post('state_id');
        if ($state_id) {
            $cities = $this->Common_model->getAllData('city', ['state_id' => $state_id]);
            echo json_encode($cities);
        } else {
            echo json_encode([]);
        }

        // You can add more AJAX functions here for other modules
    }
}

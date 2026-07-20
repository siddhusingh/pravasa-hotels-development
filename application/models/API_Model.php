<?php

class API_Model extends CI_Model
{

    /*=====Insert Records =====*/

    function insert_data($tbl, $data)
    {
        $this->db->insert($tbl, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    /*=====Get Single Record =====*/


    public function getPropertyByCity($city_id)
    {
        $this->db->select('
        hotel_id,
        hotel_name,
        hotel_code,
        city_id,
        hotel_image
    ');

        $this->db->from('hotel_admin');

        $this->db->where(
            'city_id',
            $city_id
        );

        // optional if active hotels only
        // $this->db->where('status', 1);

        $this->db->order_by(
            'hotel_name',
            'ASC'
        );

        return $this->db
            ->get()
            ->result_array();
    }

    function get_single_record($table, $where)
    {
        $query = $this->db->get_where($table, $where);
        return $query->row();
    }
    function getItemDetail($pass_id)
    {
        $sql = "SELECT * FROM `passes` WHERE `id`='$pass_id'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    /*=====Get All Record =====*/
    public function get_all_records($table, $where, $oderBy = '')
    {
        $this->db->select('*');
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($oderBy)) {
            $this->db->from($table);
            $this->db->order_by($oderBy, 'DESC');
            $query = $this->db->get();
            return $query->result();
        } else {
            $query = $this->db->get($table)->result();
            return $query;
        }
    }


    public function getCities()
    {
        $this->db->select('
        c.city_id,
        c.city_name
    ');

        $this->db->from('hotel_admin ha');

        $this->db->join(
            'city c',
            'c.city_id = ha.city_id',
            'inner'
        );

        // remove duplicate cities
        $this->db->distinct();

        // optional if active hotels only
        // $this->db->where('ha.status', 1);

        $this->db->order_by('c.city_name', 'ASC');

        return $this->db->get()->result_array();
    }

    /*=====Update Record =====*/

    function update_data($tbl, $data, $where)
    {
        $this->db->where($where);
        if ($this->db->update($tbl, $data)) {
            return TRUE;
        }
    }

    function get_purchased_pass($user_id)
    {
        $this->db->select('bought_passes.*,passes.pass_name,passes.pass_price,passes.image,passes.image,passes.pass_type');
        $this->db->from('passes');
        $this->db->join('bought_passes', 'passes.id=bought_passes.pass_id');
        $this->db->where('bought_passes.user_id', $user_id);
        $this->db->order_by('bought_passes.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }


    function get_payment_history($user_id)
    {
        $this->db->select('*');
        $this->db->from('passes_payments');
        $this->db->join('passes', 'passes.id=passes_payments.pass_id');
        $this->db->where('passes_payments.user_id', $user_id);
        $this->db->order_by('passes_payments.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }



    /**
     * Property List
     */
    public function get_property_list()
    {

        $this->db->select('
            hotel_id AS property_id,
            hotel_name AS property_name,
            hotel_contact AS property_phone,
            hotel_image
        ');
        $this->db->from('hotel_admin');
        $this->db->where('status', 'active'); // optional
        $this->db->where('is_deleted', 0);

        return $this->db->get()->result_array();
    }

    /**
     * Department List (Sample)
     */
    public function get_department_list()
    {

        $this->db->select('
            department_id,
            department_name
        ');
        $this->db->from('departments');
        $this->db->where('is_deleted', 0);

        return $this->db->get()->result_array();
    }

    /**
     * Get Restaurants by Hotel ID
     */
    public function get_restaurants_by_hotel($hotel_id)
    {

        $this->db->select('
        id as restaurant_id,
        restaurant_name,
        contact_number,
        email,
        restaurant_image
    ');
        $this->db->from('hotel_restaurants');
        $this->db->where('hotel_id', $hotel_id);
        $this->db->where('status', 1); // optional if exists

        return $this->db->get()->result_array();
    }


    /**
     * Get Time Slots
     */
    public function get_time_slots()
    {

        $this->db->select('
        id AS slot_id,
        start_time,
        end_time
    ');
        $this->db->from('time_slots');
        $this->db->where('status', 1); // optional

        return $this->db->get()->result_array();
    }
























    /*========Main Class Ending========*/
}

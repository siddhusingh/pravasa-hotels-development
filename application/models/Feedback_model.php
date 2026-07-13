<?php




class Feedback_model extends CI_Model
{
    // Get latest active feedback by mobile + restaurant
    public function get_active_feedback($mobile, $res_id)
    {
        return $this->db
            ->where('mobile', $mobile)
            ->where('res_id', $res_id)
            ->where_in('feedback_status', ['pending', 'rated'])
            ->order_by('id', 'DESC')
            ->get('reservation_feedback')
            ->row();
    }

    public function update_rating($id, $rating)
    {
        return $this->db->where('id', $id)->update('reservation_feedback', [
            'rating' => $rating,
            'feedback_status' => 'rated'
        ]);
    }

    public function update_remark($id, $remark)
    {
        return $this->db->where('id', $id)->update('reservation_feedback', [
            'remark' => $remark,
            'feedback_status' => 'completed'
        ]);
    }

    // Insert feedback entry (called after reservation)
    public function insert_feedback($data)
    {
        return $this->db->insert('reservation_feedback', $data);
    }

    public function get_feedback_list($filters = [])
    {
        $this->db->select("
            rf.id,
            rf.guest_name,
            rf.mobile,
            rf.rating,
            rf.remark,
            rf.created_at,
            hr.restaurant_name
        ");

        $this->db->from('reservation_feedback rf');
        $this->db->join('hotel_restaurants hr', 'hr.id = rf.res_id', 'left');

        // Optional Filters
        if (!empty($filters['res_id'])) {
            $this->db->where('rf.res_id', $filters['res_id']);
        }

        if (!empty($filters['rating'])) {
            $this->db->where('rf.rating', $filters['rating']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(rf.created_at) >=', $filters['start_date']);
            $this->db->where('DATE(rf.created_at) <=', $filters['end_date']);
        }

        $this->db->order_by('rf.id', 'DESC');

        return $this->db->get()->result();
    }

    public function get_banquet_feedback_list($filters = [])
    {
        $this->db->select("
            bf.id,
            bf.lead_id,
            bf.guest_name,
            bf.mobile,
            bf.overall_experience,
            bf.reservation_experience,
            bf.event_experience,
            bf.decor_ambience,
            bf.lighting_air_condition,
            bf.food_beverage_quality,
            bf.staff_service,
            bf.recommendation_score,
            bf.comment,
            bf.created_at,
            ha.hotel_name
        ");

        $this->db->from('banquet_feedback bf');
        $this->db->join('hotel_admin ha', 'ha.hotel_id = bf.property_id', 'left');

        if (!empty($filters['property_id'])) {
            $this->db->where('bf.property_id', $filters['property_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(bf.created_at) >=', $filters['start_date']);
            $this->db->where('DATE(bf.created_at) <=', $filters['end_date']);
        }

        $this->db->order_by('bf.id', 'DESC');

        return $this->db->get()->result();
    }

    public function get_room_feedback_list($filters = [])
    {
        $this->db->select("
            rf.id,
            rf.lead_id,
            rf.guest_name,
            rf.mobile,
            rf.room_stay_experience,
            rf.food_quality,
            rf.staff_service,
            rf.cleanliness_hygiene,
            rf.overall_satisfaction,
            rf.recommendation_score,
            rf.comment,
            rf.created_at,
            ha.hotel_name
        ");

        $this->db->from('room_feedback rf');
        $this->db->join('hotel_admin ha', 'ha.hotel_id = rf.property_id', 'left');

        if (!empty($filters['property_id'])) {
            $this->db->where('rf.property_id', $filters['property_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(rf.created_at) >=', $filters['start_date']);
            $this->db->where('DATE(rf.created_at) <=', $filters['end_date']);
        }

        $this->db->order_by('rf.id', 'DESC');

        return $this->db->get()->result();
    }

    // saving banquet feedback

    public function insert_banquet_feedback($data)
    {
        $this->db->insert('banquet_feedback', $data);

        return $this->db->insert_id();
    }


    // saving room feedback


    public function insert_room_feedback($data)
    {
        $this->db->insert('room_feedback', $data);

        return $this->db->insert_id();
    }
}

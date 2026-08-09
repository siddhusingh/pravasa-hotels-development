<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WhatsappLead_model extends CI_Model
{
    /**
     * Resolve active city by location/city name and return geo IDs.
     */
    public function find_city_by_name($location_name)
    {
        $location_name = trim((string) $location_name);
        if ($location_name === '') {
            return null;
        }

        return $this->db
            ->select('city_id, state_id, country_id, city_name')
            ->from('city')
            ->where('city_name', $location_name)
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Resolve active property/hotel by exact hotel name.
     */
    public function find_property_by_name($property_name)
    {
        $property_name = trim((string) $property_name);
        if ($property_name === '') {
            return null;
        }

        return $this->db
            ->select('hotel_id, hotel_name, city_id, state_id, country_id')
            ->from('hotel_admin')
            ->where('hotel_name', $property_name)
            ->where('status', 'active')
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Resolve active department by name. Returns null when not found.
     */
    public function find_department_by_name($department_name)
    {
        $department_name = trim((string) $department_name);
        if ($department_name === '') {
            return null;
        }

        return $this->db
            ->select('department_id, department_name, escalation_level_1')
            ->from('departments')
            ->where('department_name', $department_name)
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Load department row by id (active only).
     */
    public function get_department_by_id($department_id)
    {
        $department_id = (int) $department_id;
        if ($department_id <= 0) {
            return null;
        }

        return $this->db
            ->select('department_id, department_name, escalation_level_1')
            ->from('departments')
            ->where('department_id', $department_id)
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Find a recent duplicate lead by last-10 phone digits within 2 hours.
     */
    public function find_recent_duplicate_lead($phone_number)
    {
        $last10 = substr(preg_replace('/\D+/', '', (string) $phone_number), -10);
        if ($last10 === '' || strlen($last10) < 10) {
            return null;
        }

        $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));

        return $this->db
            ->from('leads')
            ->where('RIGHT(phone_number, 10) =', $last10, false)
            ->where('created_at >=', $twoHoursAgo)
            ->where('status !=', 'Closed')
            ->where('is_deleted', 0)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * True when this phone has a prior reservation lead with revenue.
     */
    public function is_valuable_guest($phone_number)
    {
        $phone_number = trim((string) $phone_number);
        if ($phone_number === '') {
            return false;
        }

        $row = $this->db
            ->select('id')
            ->from('leads')
            ->where('is_deleted', 0)
            ->where('phone_number', $phone_number)
            ->where('LOWER(disposition)', 'reservation')
            ->where('amount >', 0)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        return !empty($row);
    }

    public function insert_lead(array $lead_data)
    {
        $this->db->insert('leads', $lead_data);
        return (int) $this->db->insert_id();
    }

    public function update_lead($lead_id, array $lead_data)
    {
        $lead_id = (int) $lead_id;
        if ($lead_id <= 0) {
            return false;
        }

        $this->db->where('id', $lead_id);
        return (bool) $this->db->update('leads', $lead_data);
    }

    /**
     * Build escalation fields from department level-1 hours.
     */
    public function build_escalation_fields($department)
    {
        $decimal_hours = (float) ($department->escalation_level_1 ?? 1);
        if ($decimal_hours <= 0) {
            $decimal_hours = 1;
        }

        $minutes_to_add = (int) ($decimal_hours * 60);
        $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $current_time->modify("+{$minutes_to_add} minutes");

        return [
            'esc_next_followup_at' => $current_time->format('Y-m-d H:i:s'),
            'esc_follow_up_level' => 1,
        ];
    }
}

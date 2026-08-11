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
     * Resolve active city by stable city_id.
     */
    public function find_city_by_id($city_id)
    {
        $city_id = (int) $city_id;
        if ($city_id <= 0) {
            return null;
        }

        return $this->db
            ->select('city_id, state_id, country_id, city_name')
            ->from('city')
            ->where('city_id', $city_id)
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Active restaurant under a property (hotel).
     */
    public function find_restaurant_by_id_for_property($restaurant_id, $property_id)
    {
        $restaurant_id = (int) $restaurant_id;
        $property_id = (int) $property_id;
        if ($restaurant_id <= 0 || $property_id <= 0) {
            return null;
        }

        return $this->db
            ->select('id, restaurant_name, hotel_id')
            ->from('hotel_restaurants')
            ->where('id', $restaurant_id)
            ->where('hotel_id', $property_id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Active restaurant by name under a property (fallback).
     */
    public function find_restaurant_by_name_for_property($restaurant_name, $property_id)
    {
        $restaurant_name = trim((string) $restaurant_name);
        $property_id = (int) $property_id;
        if ($restaurant_name === '' || $property_id <= 0) {
            return null;
        }

        return $this->db
            ->select('id, restaurant_name, hotel_id')
            ->from('hotel_restaurants')
            ->where('restaurant_name', $restaurant_name)
            ->where('hotel_id', $property_id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Active slot type by id (soft-delete aware).
     */
    public function find_slot_type_by_id($slot_type_id)
    {
        $slot_type_id = (int) $slot_type_id;
        if ($slot_type_id <= 0) {
            return null;
        }

        return $this->db
            ->select('id, slot_name')
            ->from('slot_types')
            ->where('id', $slot_type_id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Active time slot by id under a slot type (soft-delete aware).
     */
    public function find_time_slot_by_id_for_type($time_slot_id, $slot_type_id)
    {
        $time_slot_id = (int) $time_slot_id;
        $slot_type_id = (int) $slot_type_id;
        if ($time_slot_id <= 0 || $slot_type_id <= 0) {
            return null;
        }

        return $this->db
            ->select('id, slot_type_id, start_time, end_time')
            ->from('time_slots')
            ->where('id', $time_slot_id)
            ->where('slot_type_id', $slot_type_id)
            ->where('status', 'active')
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

    /**
     * Active property/hotel by stable hotel_id.
     */
    public function find_property_by_id($property_id)
    {
        $property_id = (int) $property_id;
        if ($property_id <= 0) {
            return null;
        }

        return $this->db
            ->select('hotel_id, hotel_name, city_id, state_id, country_id, hotel_address, hotel_image')
            ->from('hotel_admin')
            ->where('hotel_id', $property_id)
            ->where('status', 'active')
            ->where('is_deleted', 0)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Locations (cities) with nested active properties for WhatsJet catalog.
     *
     * @return array
     */
    public function get_locations_with_properties()
    {
        $rows = $this->db
            ->select('
                city.city_id,
                city.city_name,
                hotel_admin.hotel_id,
                hotel_admin.hotel_name,
                hotel_admin.hotel_address,
                hotel_admin.hotel_image
            ')
            ->from('city')
            ->join(
                'hotel_admin',
                'hotel_admin.city_id = city.city_id
                 AND hotel_admin.status = "active"
                 AND hotel_admin.is_deleted = 0',
                'inner'
            )
            ->where('city.is_deleted', 0)
            ->order_by('city.city_name', 'ASC')
            ->order_by('hotel_admin.hotel_name', 'ASC')
            ->get()
            ->result();

        $locations = [];

        foreach ($rows as $row) {
            $city_id = (string) $row->city_id;

            if (!isset($locations[$city_id])) {
                $locations[$city_id] = [
                    'id' => $city_id,
                    'name' => (string) $row->city_name,
                    'properties' => [],
                ];
            }

            $image_url = null;
            $hotel_image = trim((string) ($row->hotel_image ?? ''));
            if ($hotel_image !== '') {
                $image_url = base_url('uploads/hotel_images/' . $hotel_image);
            }

            $locations[$city_id]['properties'][] = [
                'id' => (string) $row->hotel_id,
                'title' => (string) $row->hotel_name,
                'description' => trim((string) ($row->hotel_address ?? '')),
                'image_url' => $image_url,
            ];
        }

        return array_values($locations);
    }

    /**
     * Active restaurants for a property (hotel), WhatsJet catalog shape.
     * Returns null when property_id is not an active hotel.
     *
     * @return array|null
     */
    public function get_catalog_restaurants_by_property($property_id)
    {
        if (empty($this->find_property_by_id($property_id))) {
            return null;
        }

        $rows = $this->db
            ->select('id, restaurant_name')
            ->from('hotel_restaurants')
            ->where('hotel_id', (int) $property_id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->order_by('restaurant_name', 'ASC')
            ->get()
            ->result();

        $restaurants = [];
        foreach ($rows as $row) {
            $restaurants[] = [
                'id' => (string) $row->id,
                'title' => (string) $row->restaurant_name,
            ];
        }

        return $restaurants;
    }

    /**
     * Global WhatsJet departments (no property filter).
     * Names must stay exactly Rooms / Restaurants / Banquets.
     *
     * @return array
     */
    public function get_catalog_departments()
    {
        $allowed_names = ['Rooms', 'Restaurants', 'Banquets'];

        $rows = $this->db
            ->select('department_id, department_name')
            ->from('departments')
            ->where_in('department_name', $allowed_names)
            ->where('is_deleted', 0)
            ->order_by('FIELD(department_name, "Rooms", "Restaurants", "Banquets")', '', false)
            ->get()
            ->result();

        $departments = [];
        foreach ($rows as $row) {
            $name = (string) $row->department_name;
            $departments[] = [
                'id' => (string) $row->department_id,
                'name' => $name,
                'code' => strtolower($name),
            ];
        }

        return $departments;
    }

    /**
     * Nested dining schedule: active slot types with active time slots.
     * No invented code field. Soft-deleted and inactive rows excluded.
     *
     * @return array
     */
    public function get_catalog_dining_schedule()
    {
        $types = $this->db
            ->select('id, slot_name, start_time')
            ->from('slot_types')
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->order_by('start_time', 'ASC')
            ->order_by('id', 'ASC')
            ->get()
            ->result();

        if (empty($types)) {
            return [];
        }

        $type_ids = [];
        foreach ($types as $type) {
            $type_ids[] = (int) $type->id;
        }

        $slots_by_type = [];
        if (!empty($type_ids)) {
            $slots = $this->db
                ->select('id, slot_type_id, start_time, end_time')
                ->from('time_slots')
                ->where_in('slot_type_id', $type_ids)
                ->where('status', 'active')
                ->where('is_deleted', 0)
                ->order_by('start_time', 'ASC')
                ->order_by('id', 'ASC')
                ->get()
                ->result();

            foreach ($slots as $slot) {
                $type_key = (string) $slot->slot_type_id;
                if (!isset($slots_by_type[$type_key])) {
                    $slots_by_type[$type_key] = [];
                }

                $start = $this->normalize_time_value($slot->start_time);
                $end = $this->normalize_time_value($slot->end_time);

                $slots_by_type[$type_key][] = [
                    'id' => (string) $slot->id,
                    'title' => $this->format_slot_title($start, $end),
                    'start_time' => $start,
                    'end_time' => $end,
                ];
            }
        }

        $schedule = [];
        foreach ($types as $type) {
            $type_id = (string) $type->id;
            $schedule[] = [
                'id' => $type_id,
                'title' => (string) $type->slot_name,
                'slots' => isset($slots_by_type[$type_id]) ? $slots_by_type[$type_id] : [],
            ];
        }

        return $schedule;
    }

    /**
     * Normalize DB time to HH:MM:SS.
     */
    private function normalize_time_value($time)
    {
        $time = trim((string) $time);
        if ($time === '') {
            return '00:00:00';
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            return $time;
        }

        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time . ':00';
        }

        $timestamp = strtotime($time);
        if ($timestamp === false) {
            return '00:00:00';
        }

        return date('H:i:s', $timestamp);
    }

    /**
     * Slot display title: "06:00 AM - 07:00 AM"
     */
    private function format_slot_title($start_time, $end_time)
    {
        $start_ts = strtotime($start_time);
        $end_ts = strtotime($end_time);

        $start_label = $start_ts !== false ? date('h:i A', $start_ts) : $start_time;
        $end_label = $end_ts !== false ? date('h:i A', $end_ts) : $end_time;

        return $start_label . ' - ' . $end_label;
    }
}

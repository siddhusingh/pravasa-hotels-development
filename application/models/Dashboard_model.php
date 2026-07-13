<?php
class Dashboard_model extends CI_Model
{

    public function get_leads_by_department($filters)
    {
        $this->db->select('type as department_id, COUNT(*) as total');

        if (!empty($filters['city'])) {
            $this->db->where('city', $filters['city']);
        }
        if (!empty($filters['property'])) {
            $this->db->where('property', $filters['property']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('type', $filters['type']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(created_at) <=', $filters['end_date']);
        }

        $this->db->group_by('type');



        return $this->db->get('leads')->result();
    }


    public function get_leads_by_department_hotel()
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property_id = $hotel_session['id']; // Assuming hotel_admin.id = leads.property

        $this->db->from('leads');
        $this->db->join('hotel_admin', 'hotel_admin.hotel_id = leads.property', 'left');
        $this->db->where('leads.property', $property_id); // Filter by current hotel

        $this->db->select('type as department_id, COUNT(*) as total');
        $this->db->group_by('type');

        return $this->db->get()->result();
    }


    public function get_leads_by_status()
    {
        $this->db->select('status, COUNT(*) as total');
        $this->db->group_by('status');
        return $this->db->get('leads')->result();
    }

    public function get_leads_by_property()
    {
        $this->db->select('property as hotel_id, COUNT(*) as total');
        $this->db->group_by('property');
        return $this->db->get('leads')->result();
    }

    public function get_leads_by_disposition()
    {
        $this->db->select('disposition_id, COUNT(*) as total');
        $this->db->group_by('disposition_id');
        return $this->db->get('leads')->result();
    }

    public function get_leads_by_source()
    {
        $this->db->select('lead_source_id, COUNT(*) as total');
        $this->db->group_by('lead_source_id');
        return $this->db->get('leads')->result();
    }

    public function get_leads_grouped_by($column, $filters)
    {
        $this->db->select("$column, COUNT(*) as total");
        if (!empty($filters['city'])) {
            $this->db->where('city', $filters['city']);
        }
        if (!empty($filters['property'])) {
            $this->db->where('property', $filters['property']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('type', $filters['type']);
        }




        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(created_at) <=', $filters['end_date']);
        }

        $this->db->group_by($column);
        return $this->db->get('leads')->result();
    }

    public function get_leads_grouped_by_hotel($column)
    {
        $hotel_session = $this->session->userdata('hotel_admin_session');
        $property_id = $hotel_session['id']; // assuming this is the hotel_admin.id

        $this->db->select("leads.$column, COUNT(*) as total");
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'hotel_admin.hotel_id = leads.property', 'left');
        $this->db->where('leads.property', $property_id); // Filter by current hotel
        $this->db->group_by("leads.$column");

        return $this->db->get()->result();
    }


    public function get_leads_grouped_by_hotel_agent($column)
    {
        $property = $this->session->userdata('selected_hotel_id');
        $department = $this->session->userdata('selected_department_id');


        $this->db->select("leads.$column, COUNT(*) as total");
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'hotel_admin.hotel_id = leads.property', 'left');
        $this->db->join('departments', 'departments.department_id = leads.type', 'left');

        $this->db->where('leads.property', $property); // Filter by current hotel
        $this->db->where('leads.type', $department); // Filter by current hotel

        $this->db->group_by("leads.$column");

        return $this->db->get()->result();
    }


    public function get_guest_type_data($filters)
    {
        $this->db->select('phone_number, COUNT(*) as lead_count');
        $this->db->from('leads');

        if (!empty($filters['city'])) {
            $this->db->where('city', $filters['city']);
        }
        if (!empty($filters['property'])) {
            $this->db->where('property', $filters['property']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('type', $filters['type']);
        }


        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(created_at) <=', $filters['end_date']);
        }



        $this->db->group_by('phone_number');
        $query = $this->db->get();

        $normal = 0;
        $repeat = 0;

        foreach ($query->result() as $row) {
            if ($row->lead_count == 1) {
                $normal++;
            } else {
                $repeat++;
            }
        }

        return ['normal' => $normal, 'repeat' => $repeat];
    }
}

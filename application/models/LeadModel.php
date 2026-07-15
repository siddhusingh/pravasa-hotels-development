<?php
class LeadModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert_lead($data)
    {
        $this->db->insert('leads', $data);
        return $this->db->insert_id(); // Return last inserted ID
    }

    public function get_leads($filters = [])
    {
        $this->db->select('
        leads.*, 
        hotel_admin.hotel_name, 
        departments.department_name, 
        city.city_name,
        (SELECT COUNT(*) FROM leads as l2 WHERE l2.phone_number = leads.phone_number) > 1 AS is_repeatative
    ');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'hotel_admin.city_id = city.city_id', 'left');

        if (!empty($filters['city'])) {
            $this->db->where('leads.city', $filters['city']);
        }

        if (!empty($filters['property'])) {
            $this->db->where('leads.property', $filters['property']);
        }

        if (!empty($filters['department'])) {
            $this->db->where('leads.type', $filters['department']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('leads.status', $filters['status']);
        }

        if (!empty($filters['channel'])) {
            $this->db->where('leads.user_channel', $filters['channel']);
        }

        if (!empty($filters['disposition'])) {
            $this->db->where('leads.disposition', $filters['disposition']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['phone'])) {

            $ten = null;
            if (!empty($filters['phone'])) {
                $digits = preg_replace('/\D+/', '', (string)$filters['phone']);
                $ten = substr($digits, -10);
            }

            if ($ten !== null) {
                // use function in WHERE, and disable escaping
                $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE);
            }
        }




        // Newest records first
        $this->db->order_by('leads.created_at', 'DESC');

        // ✅ Limit to 100 records
        $this->db->limit(200);

        return $this->db->get()->result_array();
    }


    public function get_leads_for_reports($filters = [])
    {
        $this->db->select('
    leads.*, 
    hotel_admin.hotel_name, 
    departments.department_name, 
    city.city_name,

    (SELECT COUNT(*) FROM leads as l2 WHERE l2.phone_number = leads.phone_number) > 1 AS is_repeatative,

    -- Creator Name
    COALESCE(
        CASE 
            WHEN leads.creator_user_role = "super_admin" THEN sa_creator.full_name
            WHEN leads.creator_user_role = "hotel_admin" THEN ha_creator.name
            WHEN leads.creator_user_role = "agent" THEN sm_creator.name
        END, 
        "N/A"
    ) AS creator_name,

    -- Assigned Person Name
    COALESCE(
        CASE 
            WHEN leads.assigned_person_user_role = "super_admin" THEN sa_assigned.full_name
            WHEN leads.assigned_person_user_role = "admin" THEN ha_assigned.name
            WHEN leads.assigned_person_user_role = "agent" THEN sm_assigned.name
        END, 
        "Unassigned"
    ) AS assigned_person_name
');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'hotel_admin.city_id = city.city_id', 'left');

        // Creator joins
        $this->db->join('super_admin sa_creator', 'sa_creator.id = leads.created_by AND leads.creator_user_role = "super_admin"', 'left');
        $this->db->join('hotel_admins ha_creator', 'ha_creator.id = leads.created_by AND leads.creator_user_role = "hotel_admin"', 'left');
        $this->db->join('staff_members sm_creator', 'sm_creator.id = leads.created_by AND leads.creator_user_role = "agent"', 'left');

        // Assigned person joins
        $this->db->join('super_admin sa_assigned', 'sa_assigned.id = leads.assigned_to AND leads.assigned_person_user_role = "super_admin"', 'left');
        $this->db->join('hotel_admins ha_assigned', 'ha_assigned.id = leads.assigned_to AND leads.assigned_person_user_role = "admin"', 'left');
        $this->db->join('staff_members sm_assigned', 'sm_assigned.id = leads.assigned_to AND leads.assigned_person_user_role = "agent"', 'left');


        // Multi-select filters
        if (!empty($filters['city']) && is_array($filters['city'])) {
            $this->db->where_in('leads.city', $filters['city']);
        }

        if (!empty($filters['property']) && is_array($filters['property'])) {
            $this->db->where_in('leads.property', $filters['property']);
        }



        if (!empty($filters['department']) && is_array($filters['department'])) {
            $this->db->where_in('leads.type', $filters['department']);
        }

        if (!empty($filters['status']) && is_array($filters['status'])) {
            $this->db->where_in('leads.status', $filters['status']);
        }

        if (!empty($filters['channel']) && is_array($filters['channel'])) {
            $this->db->where_in('leads.user_channel', $filters['channel']);
        }

        if (!empty($filters['disposition']) && is_array($filters['disposition'])) {
            $this->db->where_in('leads.disposition', $filters['disposition']);
        }


        if (!empty($filters['created_id']) && !empty($filters['created_role'])) {
            $this->db->where('leads.created_by', $filters['created_id']);
            $this->db->where('leads.creator_user_role', $filters['created_role']);
        }

        if (!empty($filters['assigned_id']) && !empty($filters['assigned_role'])) {
            $this->db->where('leads.assigned_to', $filters['assigned_id']);
            $this->db->where('leads.assigned_person_user_role', $filters['assigned_role']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }

        // Business/Non-Business filter (single value)
        if (!empty($filters['business_type'])) {
            if ($filters['business_type'] === 'business') {
                $this->db->where_not_in('leads.disposition', ['Information/Enquiry', 'Trash', 'Denied']);
            } elseif ($filters['business_type'] === 'non_business') {
                $this->db->where_in('leads.disposition', ['Information/Enquiry', 'Trash', 'Denied']);
            }
        }

        // ✅ Default to last 7 days if no custom date range applied
        if (empty($filters['start_date']) && empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) >=', date('Y-m-d', strtotime('-15 days')));
        }

        // Newest records first
        $this->db->order_by('leads.created_at', 'DESC');



        return $this->db->get()->result_array();
    }






    public function get_leads_history($phone)
    {
        // normalize to last 10 digits (digits only)
        $ten = null;
        if (!empty($phone)) {
            $digits = preg_replace('/\D+/', '', (string)$phone);
            $ten = substr($digits, -10);
        }

        $this->db->select("
        leads.*, 
        hotel_admin.hotel_name, 
        departments.department_name, 
        city.city_name,
        CASE 
            WHEN (
                SELECT COUNT(*) 
                FROM leads AS l2 
                WHERE RIGHT(l2.phone_number, 10) = RIGHT(leads.phone_number, 10)
            ) > 1 THEN 1 ELSE 0 
        END AS is_repeatative
    ", FALSE); // <- don't escape this complex select

        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'leads.city = city.city_id', 'left');

        $ten = null;
        if (!empty($phone)) {
            $digits = preg_replace('/\D+/', '', (string)$phone);
            $ten = substr($digits, -10);
        }

        if ($ten !== null) {
            // use function in WHERE, and disable escaping
            $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE);
        }

        $this->db->order_by('leads.created_at', 'DESC');
        return $this->db->get()->result_array();
    }




    public function get_leads_history_hotel($hotel_id, $filters, $phone)
    {
        // normalize to last 10 digits
        $ten = null;
        if (!empty($phone)) {
            $digits = preg_replace('/\D+/', '', (string)$phone);
            $ten = substr($digits, -10);
        }

        $this->db->select("
        leads.*,
        hotel_admin.hotel_name,
        departments.department_name,
        city.city_name,
        CASE 
            WHEN (
                SELECT COUNT(*) 
                FROM leads AS l2 
                WHERE RIGHT(l2.phone_number, 10) = RIGHT(leads.phone_number, 10)
            ) > 1 THEN 1 ELSE 0 
        END AS is_repeatative
    ", FALSE); // ✅ don't escape

        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'leads.city = city.city_id', 'left');

        $this->db->where('leads.property', $hotel_id);

        if ($ten !== null) {
            $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE); // ✅ match on last 10 digits only
        }

        if (!empty($filters['department'])) {
            $this->db->where('leads.type', $filters['department']);
        }

        $this->db->order_by('leads.created_at', 'DESC');

        return $this->db->get()->result_array();
    }




    public function get_lead_details($leadId)
    {
        $this->db->select('
        leads.*, 
        hotel_admin.hotel_name, 
        departments.department_name, 
        city.city_name,
        (
            SELECT COUNT(*) 
            FROM leads as l2 
            WHERE l2.phone_number = leads.phone_number
        ) > 1 AS is_repeatative
    ', false); // 'false' prevents CI from escaping the subquery

        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'hotel_admin.city_id = city.city_id', 'left');
        $this->db->where('leads.id', $leadId);

        return $this->db->get()->row(); // Fetches single row
    }


    public function get_hotel_leads($hotel_id, $filters = [], $phone = "")
    {
        $this->db->select('
        leads.*,
        hotel_admin.hotel_name,
        departments.department_name,
        city.city_name,
        (SELECT COUNT(*) FROM leads as l2 WHERE l2.phone_number = leads.phone_number) > 1 AS is_repeatative
    ');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'leads.city = city.city_id', 'left');
        $this->db->where('leads.property', $hotel_id);

        if (!empty($filters['department'])) {
            $this->db->where('leads.type', $filters['department']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('leads.status', $filters['status']);
        }

        if (!empty($filters['channel'])) {
            $this->db->where('leads.user_channel', $filters['channel']);
        }

        if (!empty($filters['disposition'])) {
            $this->db->where('leads.disposition', $filters['disposition']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['phone'])) {

            $ten = null;
            if (!empty($filters['phone'])) {
                $digits = preg_replace('/\D+/', '', (string)$filters['phone']);
                $ten = substr($digits, -10);
            }

            if ($ten !== null) {
                // use function in WHERE, and disable escaping
                $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE);
            }
        }

        // Newest first
        $this->db->order_by('leads.created_at', 'DESC');

        // ✅ Limit to 100 records
        $this->db->limit(200);

        return $this->db->get()->result_array();
    }


    public function get_agency_leads($agency_id, $filters = [], $phone = "")
    {
        $this->db->select('
        leads.*,
        hotel_admin.hotel_name,
        departments.department_name,
        city.city_name,
        (SELECT COUNT(*) FROM leads as l2 WHERE l2.phone_number = leads.phone_number) > 1 AS is_repeatative
    ');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'leads.city = city.city_id', 'left');
        $this->db->where('leads.property', $hotel_id);

        if (!empty($filters['department'])) {
            $this->db->where('leads.type', $filters['department']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('leads.status', $filters['status']);
        }

        if (!empty($filters['channel'])) {
            $this->db->where('leads.user_channel', $filters['channel']);
        }

        if (!empty($filters['disposition'])) {
            $this->db->where('leads.disposition', $filters['disposition']);
        }

        if (!empty($agency_id)) {
            $this->db->where('created_by', $agency_id);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['phone'])) {

            $ten = null;
            if (!empty($filters['phone'])) {
                $digits = preg_replace('/\D+/', '', (string)$filters['phone']);
                $ten = substr($digits, -10);
            }

            if ($ten !== null) {
                // use function in WHERE, and disable escaping
                $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE);
            }
        }

        // Newest first
        $this->db->order_by('leads.created_at', 'DESC');

        // ✅ Limit to 100 records
        $this->db->limit(200);

        return $this->db->get()->result_array();
    }




    public function get_leads_for_scheduler($id)
    {
        $this->db->select('
    leads.*,
    hotel_admin.hotel_name,
    departments.department_name,
    city.city_name
');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'leads.city = city.city_id', 'left');
        $this->db->order_by('leads.created_at', 'DESC');
        $this->db->where("leads.id", $id);

        return $this->db->get()->row();
    }

    public function get_last_lead_by_phone($phone)
    {
        $last10 = substr(preg_replace('/\D/', '', $phone), -10);

        $this->db->where('RIGHT(phone_number, 10) =', $last10);
        $this->db->order_by('id', 'DESC'); // Assuming higher ID = latest
        $query = $this->db->get('leads', 1);
        return $query->row();
    }



    public function get_city_id($name)
    {
        return $this->db->select('city_id')->from('city')->where('city_name', $name)->get()->row('city_id');
    }

    public function get_state_id($name)
    {
        return $this->db->select('state_id')->from('state')->where('state_name', $name)->get()->row('state_id');
    }

    public function get_country_id($name)
    {
        return $this->db->select('country_id')->from('country')->where('country_name', $name)->get()->row('country_id');
    }

    public function get_property_id($name)
    {
        return $this->db->select('hotel_id')->from('hotel_admin')->where('hotel_name', $name)->get()->row('hotel_id');
    }

    public function get_property_id_using_fb_page_id($name)
    {
        return $this->db->select('hotel_id')->from('hotel_admin')->where('facebook_page_id', $name)->get()->row('hotel_id');
    }

    public function get_department_id_using_form_id($name)
    {
        return $this->db->select('department_id')->from('facebook_forms')->where('form_id', $name)->get()->row('department_id');
    }



    public function get_department_id($name)
    {
        return $this->db->select('department_id')->from('departments')->where('department_name', $name)->get()->row('department_id');
    }

    public function append_correlation_id($leadId, $newCorrelationId)
    {
        // Get current correlation_id value
        $this->db->select('correlation_id');
        $this->db->from('leads');
        $this->db->where('id', $leadId);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $existing = $row->correlation_id;

            // Append new correlation ID if it's not already present
            if ($existing) {
                $correlationIds = explode(',', $existing);
                if (!in_array($newCorrelationId, $correlationIds)) {
                    $updatedValue = $existing . ',' . $newCorrelationId;
                } else {
                    $updatedValue = $existing; // Avoid duplication
                }
            } else {
                $updatedValue = $newCorrelationId;
            }

            // Update the record
            $this->db->where('id', $leadId);
            return $this->db->update('leads', ['correlation_id' => $updatedValue]);
        }

        return false;
    }


    public function get_total_leads_last_30_days()
    {
        $date_30_days_ago = date('Y-m-d', strtotime('-30 days'));
        $today = date('Y-m-d');
        return $this->db
            ->where('created_at >=', $date_30_days_ago)
            ->where('created_at <=', $today)
            ->count_all_results('leads');
    }

    public function get_total_leads_previous_30_days()
    {
        $date_30_days_ago = date('Y-m-d', strtotime('-30 days'));
        $date_60_days_ago = date('Y-m-d', strtotime('-60 days'));
        return $this->db
            ->where('created_at >=', $date_60_days_ago)
            ->where('created_at <', $date_30_days_ago)
            ->count_all_results('leads');
    }




    public function get_lead_count_by_status(
        $status,
        $property = null,
        $start_date = null,
        $end_date = null,
        $department = "",
        $created_id = "",
        $created_role = "",
        $assigned_id = "",
        $assigned_role = "",
        $channel = "",
        $disposition = ""
    ) {
        $this->db->from('leads');
        $this->db->where('status', $status);

        if (!empty($property)) {
            $this->db->where('property', $property);
        }

        if (!empty($department)) {
            $this->db->where('type', $department);
        }

        if (!empty($disposition)) {
            $this->db->where('disposition', $disposition);
        }

        if (!empty($channel)) {
            $this->db->where('user_channel', $channel);
        }



        if (!empty($created_id) && !empty($created_role)) {
            $this->db->where('leads.created_by', $created_id);
            $this->db->where('leads.creator_user_role', $created_role);
        }

        if (!empty($assigned_id) && !empty($assigned_role)) {
            $this->db->where('leads.assigned_to', $assigned_id);
            $this->db->where('leads.assigned_person_user_role', $assigned_role);
        }

        if (!empty($start_date)) {
            $this->db->where('DATE(leads.created_at) >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('DATE(leads.created_at) <=', $end_date);
        }

        return $this->db->count_all_results();
    }


    public function get_lead_count_for_not_assigned(
        $status,
        $property = null,
        $start_date = null,
        $end_date = null,
        $department = "",
        $created_id = "",
        $created_role = "",
        $assigned_id = "",
        $assigned_role = "",
        $channel = "",
        $disposition = ""
    ) {
        $this->db->from('leads');

        if (!empty($status)) {

            $this->db->where('status', $status);
        }

        $this->db->where('is_assigned', 0);




        if (!empty($property)) {
            $this->db->where('property', $property);
        }

        if (!empty($department)) {
            $this->db->where('type', $department);
        }

        if (!empty($disposition)) {
            $this->db->where('disposition', $disposition);
        }

        if (!empty($channel)) {
            $this->db->where('user_channel', $channel);
        }

        if (!empty($created_id) && !empty($created_role)) {
            $this->db->where('leads.created_by', $created_id);
            $this->db->where('leads.creator_user_role', $created_role);
        }

        if (!empty($assigned_id) && !empty($assigned_role)) {
            $this->db->where('leads.assigned_to', $assigned_id);
            $this->db->where('leads.assigned_person_user_role', $assigned_role);
        }

        if (!empty($start_date)) {
            $this->db->where('DATE(leads.created_at) >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('DATE(leads.created_at) <=', $end_date);
        }

        return $this->db->count_all_results();
    }

    public function get_lead_count_by_status_for_lead_report($status)
    {
        $this->db->from('leads');
        $this->db->where('status', $status);



        return $this->db->count_all_results();
    }



    public function get_lead_count_by_disposition(
        $disposition,
        $property = null,
        $start_date = null,
        $end_date = null,
        $department = "",
        $created_id = "",
        $created_role = "",
        $assigned_id = "",
        $assigned_role = "",
        $channel = "",
        $disposition_Z = ""
    ) {
        $this->db->from('leads');
        $this->db->where('disposition', $disposition);



        if (!empty($disposition_Z)) {
            $this->db->where('disposition', $disposition_Z);
        }

        if (!empty($channel)) {
            $this->db->where('user_channel', $channel);
        }

        if (!empty($property)) {
            $this->db->where('property', $property);
        }

        if (!empty($department)) {
            $this->db->where('type', $department);
        }


        if (!empty($created_id) && !empty($created_role)) {
            $this->db->where('leads.created_by', $created_id);
            $this->db->where('leads.creator_user_role', $created_role);
        }

        if (!empty($assigned_id) && !empty($assigned_role)) {
            $this->db->where('leads.assigned_to', $assigned_id);
            $this->db->where('leads.assigned_person_user_role', $assigned_role);
        }



        if (!empty($start_date)) {
            $this->db->where('created_at >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('created_at <=', $end_date);
        }

        return $this->db->count_all_results();
    }


    public function get_lead_revenue_by_disposition(
        $disposition,
        $property = null,
        $start_date = null,
        $end_date = null,
        $department = "",
        $created_id = "",
        $created_role = "",
        $assigned_id = "",
        $assigned_role = "",
        $channel = "",
        $disposition_Z = ""
    ) {
        $this->db->select_sum('amount'); // replace revenue with your actual column name
        $this->db->from('leads');
        $this->db->where('disposition', $disposition);



        if (!empty($property)) {
            $this->db->where('property', $property);
        }


        if (!empty($disposition_Z)) {
            $this->db->where('disposition', $disposition_Z);
        }

        if (!empty($channel)) {
            $this->db->where('user_channel', $channel);
        }


        if (!empty($department)) {
            $this->db->where('type', $department);
        }

        if (!empty($created_id) && !empty($created_role)) {
            $this->db->where('leads.created_by', $created_id);
            $this->db->where('leads.creator_user_role', $created_role);
        }

        if (!empty($assigned_id) && !empty($assigned_role)) {
            $this->db->where('leads.assigned_to', $assigned_id);
            $this->db->where('leads.assigned_person_user_role', $assigned_role);
        }

        if (!empty($start_date)) {
            $this->db->where('created_at >=', $start_date . ' 00:00:00');
        }

        if (!empty($end_date)) {
            $this->db->where('created_at <=', $end_date . ' 23:59:59');
        }

        $query = $this->db->get()->row();

        return !empty($query->amount) ? $query->amount : 0;
    }


    public function get_lead_count_by_disposition_agent($disposition, $property = null, $department_id = "", $start_date = null, $end_date = null)
    {
        $this->db->from('leads');
        $this->db->where('disposition', $disposition);

        if (!empty($property)) {
            $this->db->where('property', $property);
        }

        if (!empty($department_id)) {
            $this->db->where('type', $department_id);
        }




        if (!empty($start_date)) {
            $this->db->where('created_at >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('created_at <=', $end_date);
        }

        return $this->db->count_all_results();
    }


    public function get_lead_count_by_disposition_agency($disposition, $property = null, $department_id = "", $start_date = null, $end_date = null, $agency_id)
    {
        $this->db->from('leads');
        $this->db->where('disposition', $disposition);

        if (!empty($property)) {
            $this->db->where('property', $property);
        }


        if (!empty($department_id)) {
            $this->db->where('type', $department_id);
        }

        if (!empty($agency_id)) {
            $this->db->where('created_by', $agency_id);
        }




        if (!empty($start_date)) {
            $this->db->where('created_at >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('created_at <=', $end_date);
        }

        return $this->db->count_all_results();
    }



    public function get_total_leads_last_30_days_agent($hotel_id, $department_id, $start_date = null, $end_date = null)
    {
        $date_30_days_ago = date('Y-m-d', strtotime('-30 days'));
        $today = date('Y-m-d');

        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->where('hotel_admin.hotel_id', $hotel_id);
        $this->db->where('departments.department_id', $department_id);

        return $this->db->count_all_results();
    }

    public function get_total_leads_last_30_days_hotel($hotel_id)
    {
        $date_30_days_ago = date('Y-m-d', strtotime('-30 days'));
        $today = date('Y-m-d');

        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->where('hotel_admin.hotel_id', $hotel_id);
        // $this->db->where('DATE(leads.created_at) >=', $date_30_days_ago);
        //$this->db->where('DATE(leads.created_at) <=', $today);

        return $this->db->count_all_results();
    }





    public function get_lead_count_by_status_agent($status, $hotel_id, $department_id, $start_date = null, $end_date = null)
    {
        $this->db->from('leads');

        $this->db->where('status', $status);
        $this->db->where('property', $hotel_id);

        $this->db->where('type', $department_id);

        if (!empty($start_date)) {
            $this->db->where('DATE(leads.created_at) >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('DATE(leads.created_at) <=', $end_date);
        }

        return $this->db->count_all_results();
    }


    public function get_lead_count_by_status_agency(
        $status = null,
        $hotel_id = null,
        $department_id = null,
        $start_date = null,
        $end_date = null,
        $agency_id = null
    ) {
        $this->db->from('leads');

        // Apply filters only if values exist
        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        if (!empty($hotel_id)) {
            $this->db->where('property', $hotel_id);
        }

        if (!empty($department_id)) {
            $this->db->where('type', $department_id);
        }

        if (!empty($agency_id)) {
            $this->db->where('created_by', $agency_id);
        }

        if (!empty($start_date)) {
            $this->db->where('DATE(leads.created_at) >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('DATE(leads.created_at) <=', $end_date);
        }

        return $this->db->count_all_results();
    }



    public function get_lead_count_by_status_hotel($status, $hotel_id)
    {
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->where('leads.status', $status);
        $this->db->where('hotel_admin.hotel_id', $hotel_id);

        return $this->db->count_all_results();
    }




    public function get_total_hotels()
    {
        return $this->db->count_all('hotel_admin');
    }

    public function get_total_staff()
    {
        return $this->db->count_all('staff_members');
    }

    public function get_total_departments()
    {
        return $this->db->count_all('departments');
    }

    public function get_hotel_leads_for_agent_dashboard($hotel_id, $filters = [])
    {
        $this->db->select('
    leads.*,

    hotel_admin.hotel_name,
    departments.department_name,
    city.city_name
');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'leads.city = city.city_id', 'left');
        $this->db->where('leads.property', $hotel_id);
        $this->db->limit(5);

        if (!empty($filters['department'])) {
            $this->db->where('leads.type', $filters['department']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('leads.status', $filters['status']);
        }


        return $this->db->get()->result_array();
    }

    public function guestcontactBook()
    {
        $this->db->select('
        RIGHT(leads.phone_number, 10) AS phone_number,
        MAX(leads.user_name) AS user_name,
        MAX(hotel_admin.hotel_name) AS hotel_name,
        MAX(city.city_name) AS city_name,
        COUNT(leads.id) AS total_leads
    ');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('city', 'leads.city = city.city_id', 'left');
        $this->db->group_by('RIGHT(leads.phone_number, 10)'); // ✅ group by normalized 10-digit number
        $this->db->order_by('total_leads', 'DESC');
        return $this->db->get()->result();
    }



    public function guestcontactBookAdmin($property)
    {
        $this->db->select('
        RIGHT(leads.phone_number, 10) AS phone_number,
        MIN(leads.phone_number) AS original_phone, -- keep one original sample
        MAX(leads.user_name) AS user_name,
        MAX(hotel_admin.hotel_name) AS hotel_name,
        MAX(city.city_name) AS city_name,
        COUNT(leads.id) AS total_leads
    ');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('city', 'leads.city = city.city_id', 'left');
        $this->db->where('leads.property', $property);
        $this->db->group_by('RIGHT(leads.phone_number, 10)'); // ✅ group only by normalized number
        $this->db->order_by('total_leads', 'DESC');
        return $this->db->get()->result();
    }

    public function get_lead_counts_grouped_by_status($filters = [])
    {
        $this->db->select('status, COUNT(*) as count');
        $this->db->from('leads');

        // Apply filters
        if (!empty($filters['city'])) {
            $this->db->where('leads.city', $filters['city']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('leads.status', $filters['status']);
        }

        if (!empty($filters['property'])) {
            $this->db->where('leads.property', $filters['property']);
        }

        if (!empty($filters['department'])) {
            $this->db->where('leads.type', $filters['department']);
        }

        if (!empty($filters['channel'])) {
            $this->db->where('leads.user_channel', $filters['channel']);
        }

        if (!empty($filters['disposition'])) {
            $this->db->where('leads.disposition', $filters['disposition']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['phone'])) {

            $ten = null;
            if (!empty($filters['phone'])) {
                $digits = preg_replace('/\D+/', '', (string)$filters['phone']);
                $ten = substr($digits, -10);
            }

            if ($ten !== null) {
                // use function in WHERE, and disable escaping
                $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE);
            }
        }

        $this->db->group_by('status');
        $query = $this->db->get();

        $result = [];
        foreach ($query->result() as $row) {
            $result[$row->status] = $row->count;
        }

        return $result;
    }


    private function apply_due_followup_filter()
    {
        $today = date('Y-m-d');

        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('leads.followup_date IS NOT NULL', null, false);
        $this->db->where('leads.followup_date !=', '');
        $this->db->where('DATE(leads.followup_date) <=', $today);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('leads.second_followup_date IS NOT NULL', null, false);
        $this->db->where('leads.second_followup_date !=', '');
        $this->db->where('DATE(leads.second_followup_date) <=', $today);
        $this->db->group_end();
        $this->db->group_end();
    }

    function get_filtered_leads($filters = [], $limit = 50, $offset = 0)
    {
        $this->db->select('
        leads.*, 
        hotel_admin.hotel_name, 
        departments.department_name, 
        city.city_name,
        (SELECT COUNT(*) FROM leads as l2 WHERE l2.phone_number = leads.phone_number) > 1 AS is_repeatative
    ');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'hotel_admin.city_id = city.city_id', 'left');

        // Multi-select filters
        if (!empty($filters['city']) && is_array($filters['city'])) {
            $this->db->where_in('leads.city', $filters['city']);
        }

        if (!empty($filters['property']) && is_array($filters['property'])) {
            $this->db->where_in('leads.property', $filters['property']);
        }



        if (!empty($filters['department']) && is_array($filters['department'])) {
            $this->db->where_in('leads.type', $filters['department']);
        }


        if (!empty($filters['status']) && is_array($filters['status'])) {

            $statusArr = $filters['status'];

            $hasNotAssigned = in_array('Not Assigned', $statusArr);

            // Remove Not Assigned from status array
            $statusArr = array_diff($statusArr, ['Not Assigned']);

            // Start grouping
            $this->db->group_start();

            // ✅ Case 1: Not Assigned
            if ($hasNotAssigned) {
                $this->db->group_start();
                $this->db->where('leads.is_assigned', 0);
                $this->db->group_start();
                $this->db->where('leads.assigned_to IS NULL', null, false);
                $this->db->or_where('leads.assigned_to', '');
                $this->db->group_end();
                $this->db->group_end();
            }

            // ✅ Case 2: Other Statuses
            if (!empty($statusArr)) {
                if ($hasNotAssigned) {
                    $this->db->or_group_start();
                }

                $this->db->where_in('leads.status', $statusArr);

                if ($hasNotAssigned) {
                    $this->db->group_end();
                }
            }

            // End main group
            $this->db->group_end();
        } else if (empty($filters['showfollowupleads']) || $filters['showfollowupleads'] !== 'yes') {
            $this->db->where('leads.status', 'open');
        }



        if (!empty($filters['channel']) && is_array($filters['channel'])) {
            $this->db->where_in('leads.user_channel', $filters['channel']);
        }

        if (!empty($filters['disposition']) && is_array($filters['disposition'])) {
            $this->db->where_in('leads.disposition', $filters['disposition']);
        }

        // Single value filters
        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['created_id']) && !empty($filters['created_role'])) {
            $this->db->where('leads.created_by', $filters['created_id']);
            $this->db->where('leads.creator_user_role', $filters['created_role']);
        }

        if (!empty($filters['assigned_id']) && !empty($filters['assigned_role'])) {
            $this->db->where('leads.assigned_to', $filters['assigned_id']);
            $this->db->where('leads.assigned_person_user_role', $filters['assigned_role']);
        }


        if (!empty($filters['showfollowupleads']) && $filters['showfollowupleads'] === 'yes') {
            $this->apply_due_followup_filter();
        }


        // Business/Non-Business filter (single value)
        if (!empty($filters['business_type'])) {
            if ($filters['business_type'] === 'business') {
                $this->db->where_not_in('leads.disposition', ['Information/Enquiry', 'Trash', 'Denied']);
            } elseif ($filters['business_type'] === 'non_business') {
                $this->db->where_in('leads.disposition', ['Information/Enquiry', 'Trash', 'Denied']);
            }
        }


        // Search filter (search by name or phone)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('leads.user_name', $search);
            $this->db->or_like('leads.phone_number', $search);
            $this->db->group_end();
        }

        if (!empty($filters['phone'])) {
            $digits = preg_replace('/\D+/', '', (string)$filters['phone']);
            $ten = substr($digits, -10);
            if ($ten !== null) {
                $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE);
            }
        }



        // Order newest first
        $this->db->order_by('leads.created_at', 'DESC');

        // Limit & offset for pagination / load more
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }


    public function get_leads_status_counts($filters = [])
    {
        $this->db->select('
        COUNT(*) AS total,
COALESCE(SUM(CASE WHEN leads.status = "Open" THEN 1 ELSE 0 END), 0) AS open,
COALESCE(SUM(CASE WHEN leads.status = "In Progress" THEN 1 ELSE 0 END), 0) AS in_progress,
COALESCE(SUM(CASE WHEN leads.status = "Closed" THEN 1 ELSE 0 END), 0) AS closed,
COALESCE(SUM(CASE WHEN leads.is_assigned = 0 THEN 1 ELSE 0 END), 0) AS not_assigned
    ');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'hotel_admin.city_id = city.city_id', 'left');

        // Apply filters
        if (!empty($filters['city']) && is_array($filters['city'])) {
            $this->db->where_in('leads.city', $filters['city']);
        }
        if (!empty($filters['property']) && is_array($filters['property'])) {
            $this->db->where_in('leads.property', $filters['property']);
        }
        if (!empty($filters['department']) && is_array($filters['department'])) {
            $this->db->where_in('leads.type', $filters['department']);
        }



        if (!empty($filters['showfollowupleads']) && $filters['showfollowupleads'] === 'yes') {
            $this->apply_due_followup_filter();
        }



        // if (!empty($filters['status']) && is_array($filters['status'])) {

        //     $statusArr = $filters['status'];

        //     $hasNotAssigned = in_array('Not Assigned', $statusArr);

        //     // Remove Not Assigned from status array
        //     $statusArr = array_diff($statusArr, ['Not Assigned']);

        //     // Start grouping
        //     $this->db->group_start();

        //     // ✅ Case 1: Not Assigned
        //     if ($hasNotAssigned) {
        //         $this->db->group_start();
        //         $this->db->where('leads.is_assigned', 0);
        //         $this->db->group_start();
        //         $this->db->where('leads.assigned_to IS NULL', null, false);
        //         $this->db->or_where('leads.assigned_to', '');
        //         $this->db->group_end();
        //         $this->db->group_end();
        //     }

        //     // ✅ Case 2: Other Statuses
        //     // if (!empty($statusArr)) {
        //     //     if ($hasNotAssigned) {
        //     //         $this->db->or_group_start();
        //     //     }

        //     //     $this->db->where_in('leads.status', $statusArr);

        //     //     if ($hasNotAssigned) {
        //     //         $this->db->group_end();
        //     //     }
        //     // }

        //     // End main group
        //     $this->db->group_end();
        // } else {
        //     $this->db->where('leads.status', 'open');
        // }


        if (!empty($filters['channel']) && is_array($filters['channel'])) {
            $this->db->where_in('leads.user_channel', $filters['channel']);
        }
        if (!empty($filters['disposition']) && is_array($filters['disposition'])) {
            $this->db->where_in('leads.disposition', $filters['disposition']);
        }

        if (!empty($filters['created_by'])) {
            $this->db->where('created_by', $filters['created_by']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }


        // ✅ NEW: Created By Filter (ID + ROLE)
        if (!empty($filters['created_id']) && !empty($filters['created_role'])) {
            $this->db->where('leads.created_by', $filters['created_id']);
            $this->db->where('leads.creator_user_role', $filters['created_role']);
        }

        // ✅ NEW: Assigned To Filter (ID + ROLE)
        if (!empty($filters['assigned_id']) && !empty($filters['assigned_role'])) {
            $this->db->where('leads.assigned_to', $filters['assigned_id']);
            $this->db->where('leads.assigned_person_user_role', $filters['assigned_role']);
        }


        // Business/Non-Business filter (single value)
        if (!empty($filters['business_type'])) {
            if ($filters['business_type'] === 'business') {
                $this->db->where_not_in('leads.disposition', ['Information/Enquiry', 'Trash']);
            } elseif ($filters['business_type'] === 'non_business') {
                $this->db->where_in('leads.disposition', ['Information/Enquiry', 'Trash']);
            }
        }

        if (!empty($filters['phone'])) {
            $digits = preg_replace('/\D+/', '', (string)$filters['phone']);
            $ten = substr($digits, -10);
            if ($ten !== null) {
                $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE);
            }
        }

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('leads.user_name', $search);
            $this->db->or_like('leads.phone_number', $search);
            $this->db->group_end();
        }

        return $this->db->get()->row_array(); // Returns single row with all counts
    }


    public function get_followup_leads($filters = [])
    {
        $this->db->select('
        leads.*, 
        hotel_admin.hotel_name, 
        departments.department_name, 
        city.city_name,
        (SELECT COUNT(*) FROM leads as l2 WHERE l2.phone_number = leads.phone_number) > 1 AS is_repeatative
    ');
        $this->db->from('leads');
        $this->db->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left');
        $this->db->join('departments', 'leads.type = departments.department_id', 'left');
        $this->db->join('city', 'hotel_admin.city_id = city.city_id', 'left');

        // Main filter: due follow-up dates
        $this->apply_due_followup_filter();

        if (!empty($filters['city'])) {
            $this->db->where('leads.city', $filters['city']);
        }

        if (!empty($filters['property'])) {
            $this->db->where('leads.property', $filters['property']);
        }

        if (!empty($filters['department'])) {
            $this->db->where('leads.type', $filters['department']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('leads.status', $filters['status']);
        }

        if (!empty($filters['channel'])) {
            $this->db->where('leads.user_channel', $filters['channel']);
        }

        if (!empty($filters['disposition'])) {
            $this->db->where('leads.disposition', $filters['disposition']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(leads.created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(leads.created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['phone'])) {
            $ten = null;
            if (!empty($filters['phone'])) {
                $digits = preg_replace('/\D+/', '', (string)$filters['phone']);
                $ten = substr($digits, -10);
            }

            if ($ten !== null) {
                $this->db->where("RIGHT(leads.phone_number, 10) =", $ten, FALSE);
            }
        }

        // Newest records first
        $this->db->order_by('leads.created_at', 'DESC');

        // Limit to 200 records
        $this->db->limit(200);

        return $this->db->get()->result_array();
    }



    public function get_lead_by_id_with_joins($leadId)
    {
        return $this->db
            ->select('leads.*, 
            hotel_admin.hotel_name, 
            departments.department_name,
            city.city_name,
            hotel_restaurants.restaurant_name,
            roomtype.roomtype_name,
            meal_plans.plan,
            banquet.banquet_name,
            promotional_offers.offer_name,
            slot_types.slot_name,

            time_slots.start_time,
            time_slots.end_time,

            table_categories.category_name,

            tables.table_name,
            tables.table_number,
            tables.capacity
        ')
            ->from('leads')

            ->join('hotel_admin', 'leads.property = hotel_admin.hotel_id', 'left')
            ->join('departments', 'leads.type = departments.department_id', 'left')
            ->join('city', 'hotel_admin.city_id = city.city_id', 'left')

            ->join('hotel_restaurants', 'leads.restaurant_id = hotel_restaurants.id', 'left')
            ->join('promotional_offers', 'leads.promotional_offers = promotional_offers.id', 'left')
            ->join('meal_plans', 'leads.meal_plan = meal_plans.id', 'left')
            ->join('roomtype', 'leads.roomtype = roomtype.roomtype_id', 'left')
            ->join('banquet', 'leads.banquet_id = banquet.banquet_id', 'left')

            // ✅ Slot Type
            ->join('slot_types', 'leads.slot_type_id = slot_types.id', 'left')

            // ✅ Time Slot
            ->join('time_slots', 'leads.time_slot_id = time_slots.id', 'left')

            // ✅ Table Category
            ->join('table_categories', 'leads.table_category_id = table_categories.id', 'left')

            // ✅ Table
            ->join('tables', 'leads.table_id = tables.id', 'left')

            ->where('leads.id', $leadId)
            ->get()
            ->row();
    }



    // public function get_all_assignable_users()
    // {
    //     // Hotel Admins
    //     $hotel_admins = $this->db->select("id, name, email, 'admin' as user_role")
    //         ->from('hotel_admins')
    //         ->get()
    //         ->result_array();

    //     // Staff Members
    //     $staff_members = $this->db->select("id, name, email, 'agent' as user_role")
    //         ->from('staff_members')
    //         ->get()
    //         ->result_array();

    //     // Super Admins (full_name mapped as name)
    //     $super_admins = $this->db->select("id, full_name as name, email, 'super_admin' as user_role")
    //         ->from('super_admin')
    //         ->get()
    //         ->result_array();

    //     // Merge all users
    //     $all_users = array_merge($hotel_admins, $staff_members, $super_admins);

    //     return $all_users;
    // }

    public function get_all_assignable_users()
    {
        // Hotel Admins
        $hotel_admins = $this->db->select("id, name, email, 'admin' as user_role")
            ->from('hotel_admins')
            ->get()
            ->result_array();

        // Staff Members
        $staff_members = $this->db->select("id, name, email, 'agent' as user_role")
            ->from('staff_members')
            ->get()
            ->result_array();

        // Super Admins
        $super_admins = $this->db->select("id, full_name as name, email, 'super_admin' as user_role")
            ->from('super_admin')
            ->get()
            ->result_array();

        // Merge all users
        $all_users = array_merge($hotel_admins, $staff_members, $super_admins);

        // ✅ Sort by name (A-Z)
        usort($all_users, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return $all_users;
    }


    public function get_assigned_to_name($lead)
    {
        if (!empty($lead->assigned_person_user_role) && !empty($lead->assigned_to)) {
            if ($lead->assigned_person_user_role == 'agent') {
                return $this->db->select('name')->from('staff_members')->where('id', $lead->assigned_to)->get()->row()->name ?? 'NA';
            } else if ($lead->assigned_person_user_role == 'admin') {
                return $this->db->select('name')->from('hotel_admins')->where('id', $lead->assigned_to)->get()->row()->username ?? 'NA';
            } else if ($lead->assigned_person_user_role == 'super_admin') {
                return $this->db->select('full_name')->from('super_admin')->where('id', $lead->assigned_to)->get()->row()->full_name ?? 'NA';
            }
        }
        return 'NA';
    }

    public function get_created_to_name($lead)
    {
        if (!empty($lead->creator_user_role) && !empty($lead->created_by)) {
            if ($lead->creator_user_role == 'agent') {
                return $this->db->select('name')->from('staff_members')->where('id', $lead->created_by)->get()->row()->name ?? 'NA';
            } else if ($lead->creator_user_role == 'admin') {
                return $this->db->select('name')->from('hotel_admins')->where('id', $lead->created_by)->get()->row()->username ?? 'NA';
            } else if ($lead->creator_user_role == 'super_admin') {
                return $this->db->select('full_name')->from('super_admin')->where('id', $lead->created_by)->get()->row()->full_name ?? 'NA';
            }
        }
        return 'NA';
    }

    public function get_all_today_followups($date)
    {
        return $this->db->where('followup_date', $date)
            ->get('leads')
            ->result();
    }


    public function get_agent_email($agent_id)
    {
        return $this->db->select('name,email')
            ->where('id', $agent_id)
            ->get('staff_members')
            ->row() ?? null;
    }

    public function update_followup_one_status($lead_id, $status)
    {
        if (empty($lead_id)) {
            return false;
        }

        $data = [
            'followup_one_status' => $status,
        ];

        $this->db->where('id', $lead_id);
        return $this->db->update('leads', $data);
    }

    public function update_followup_two_status($lead_id, $status)
    {
        if (empty($lead_id)) {
            return false;
        }

        $data = [
            'followup_two_status' => $status,
        ];

        $this->db->where('id', $lead_id);
        return $this->db->update('leads', $data);
    }

    public function get_disposition_report($filters = [])
    {
        $this->db->select('disposition, COUNT(*) as total');
        $this->db->from('leads');

        // Date Range Filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(created_at) >=', $filters['start_date']);
            $this->db->where('DATE(created_at) <=', $filters['end_date']);
        }

        // Hotel / Property Filter
        if (!empty($filters['property'])) {
            $this->db->where_in('property', $filters['property']);
        }

        // Department Filter
        if (!empty($filters['department'])) {
            $this->db->where_in('type', $filters['department']);
        }

        $this->db->group_by('disposition');
        return $this->db->get()->result_array();
    }

    public function get_department_report($filters = [])
    {
        $this->db->select('d.department_name, d.department_id AS department_id, COUNT(l.id) AS total');
        $this->db->from('departments d');
        $this->db->join('leads l', 'l.type = d.department_id', 'left');  // type = department ID

        // Date Range Filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(l.created_at) >=', $filters['start_date']);
            $this->db->where('DATE(l.created_at) <=', $filters['end_date']);
        }

        // Hotel / Property Filter
        if (!empty($filters['property'])) {
            $this->db->where_in('l.property', $filters['property']);
        }

        // Department Filter (filter only selected departments)
        if (!empty($filters['department'])) {
            $this->db->where_in('d.department_id', $filters['department']);
        }

        $this->db->group_by('d.department_id');
        $this->db->order_by('d.department_name', 'ASC');

        return $this->db->get()->result_array();
    }



    public function get_user_channel_report($filters = [])
    {
        $this->db->select('user_channel, COUNT(*) as total');
        $this->db->from('leads');

        // Date Range Filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(created_at) >=', $filters['start_date']);
            $this->db->where('DATE(created_at) <=', $filters['end_date']);
        }

        // Hotel / Property Filter
        if (!empty($filters['property'])) {
            $this->db->where_in('property', $filters['property']);
        }

        // Department Filter
        if (!empty($filters['department'])) {
            $this->db->where_in('type', $filters['department']);
        }

        $this->db->group_by('user_channel');
        return $this->db->get()->result_array();
    }


    public function get_hotel_department_status_report($filters = [])
    {
        $nonBusinessStages = ['Information/Enquiry', 'Trash', 'Denied'];

        $this->db->select("
        h.hotel_id AS hotel_id,
        h.hotel_name,
        d.department_id AS department_id,
        d.department_name,
        l.status,
        l.disposition,
        COUNT(l.id) AS total_leads
    ");

        $this->db->from('leads l');
        $this->db->join('hotel_admin h', 'h.hotel_id = l.property', 'left');
        $this->db->join('departments d', 'd.department_id = l.type', 'left');

        // ---- FILTERS ---- //
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(l.created_at) >=', $filters['start_date']);
            $this->db->where('DATE(l.created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['property'])) {
            $this->db->where_in('l.property', $filters['property']);
        }

        if (!empty($filters['department'])) {
            $this->db->where_in('l.type', $filters['department']);
        }

        if (!empty($filters['status'])) {
            $this->db->where_in('l.status', $filters['status']);
        }

        // Exclude leads without hotel
        $this->db->where('l.property IS NOT NULL');
        $this->db->where('l.property !=', '');



        $this->db->group_by([
            'hotel_id',
            'h.hotel_name',
            'd.department_id',
            'd.department_name',
            'l.status',
            'l.disposition'
        ]);

        $this->db->order_by('h.hotel_name', 'ASC');
        $this->db->order_by('d.department_name', 'ASC');
        $this->db->order_by('l.status', 'ASC');

        // --- make sure non-business dispositions list is normalized (lowercase, trimmed)
        $nonBusinessStages = array_map('trim', $nonBusinessStages);
        $nonBusinessStages = array_map('strtolower', $nonBusinessStages);

        $rows = $this->db->get()->result_array();

        // ---- BUILD NESTED ARRAY ---- //
        $data = [];

        foreach ($rows as $row) {

            $hotel_id = $row['hotel_id'];
            $dept_id  = $row['department_id'];

            // normalize status & disposition
            $status_raw = isset($row['status']) ? (string)$row['status'] : '';
            $disp_raw   = isset($row['disposition']) ? (string)$row['disposition'] : '';

            $status = strtolower(trim($status_raw));
            $disp   = strtolower(trim($disp_raw));

            // cast count
            $count = (int) ($row['total_leads'] ?? 0);

            // Initialize hotel
            if (!isset($data[$hotel_id])) {
                $data[$hotel_id] = [
                    'hotel_id' => $hotel_id,
                    'hotel_name' => $row['hotel_name'],
                    'departments' => []
                ];
            }

            // Initialize department
            if (!isset($data[$hotel_id]['departments'][$dept_id])) {
                $data[$hotel_id]['departments'][$dept_id] = [
                    'department_id' => $dept_id,
                    'department_name' => $row['department_name'],
                    'status_counts' => [],
                    'open_leads' => 0,
                    'business_leads' => 0,
                    'non_business_leads' => 0,
                    'materialized_leads' => 0,
                ];
            }

            // ---- Status count ---- //
            // store status using the original string for readability (use trimmed original)
            $status_label = trim((string)($row['status'] ?? 'Unknown'));
            if ($status_label === '') $status_label = 'Unknown';

            if (!isset($data[$hotel_id]['departments'][$dept_id]['status_counts'][$status_label])) {
                $data[$hotel_id]['departments'][$dept_id]['status_counts'][$status_label] = 0;
            }
            $data[$hotel_id]['departments'][$dept_id]['status_counts'][$status_label] += $count;

            // ---- Open Leads count ---- //
            if ($status === 'open') {
                $data[$hotel_id]['departments'][$dept_id]['open_leads'] += $count;
            }

            // ---- Business / Non-business ---- //
            if ($disp === '') {
                // treat empty disposition as non-business OR business? 
                // (choose one - here we treat empty as non-business)
                $is_non_business = true;
            } else {
                $is_non_business = in_array($disp, $nonBusinessStages, true);
            }

            if ($is_non_business) {
                $data[$hotel_id]['departments'][$dept_id]['non_business_leads'] += $count;
            } else {
                $data[$hotel_id]['departments'][$dept_id]['business_leads'] += $count;
            }

            // ---- Materialized Leads ---- //
            if ($disp === 'reservation' && $status === 'closed') {
                $data[$hotel_id]['departments'][$dept_id]['materialized_leads'] += $count;
            }
        }

        return array_values($data);
    }

    public function get_property_department_channel_report($filters = [])
    {
        $nonBusinessStages = ['Information/Enquiry', 'Trash', 'Denied'];
        $nonBusinessStages = array_map('trim', $nonBusinessStages);
        $nonBusinessStages = array_map('strtolower', $nonBusinessStages);

        // ✅ Fetch department + channel + status + disposition level summary
        $this->db->select("
        d.department_id,
        d.department_name,
        l.user_channel,
        l.status,
        l.disposition,
        SUM(COALESCE(l.amount,0)) AS revenue_sum,
        COUNT(l.id) AS total_leads
    ");

        $this->db->from('leads l');
        $this->db->join('departments d', 'd.department_id = l.type', 'left');

        // ✅ FILTERS
        if (!empty($filters['property'])) {
            $this->db->where_in('l.property', $filters['property']);
        }

        if (!empty($filters['department'])) {
            $this->db->where_in('l.type', $filters['department']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(l.created_at) >=', $filters['start_date']);
            $this->db->where('DATE(l.created_at) <=', $filters['end_date']);
        }

        // ✅ Grouping
        $this->db->group_by([
            'd.department_id',
            'd.department_name',
            'l.user_channel',
            'l.status',
            'l.disposition'
        ]);

        $this->db->order_by('d.department_name', 'ASC');
        $this->db->order_by('l.user_channel', 'ASC');

        $rows = $this->db->get()->result_array();

        //✅ Final Data Output
        $data = [];

        foreach ($rows as $row) {

            $dept_id = $row['department_id'];
            $dept_name = $row['department_name'];
            $channel = $row['user_channel'] ?: 'Unknown';

            $status = strtolower(trim($row['status']));
            $disp   = strtolower(trim($row['disposition']));
            $count  = (int)$row['total_leads'];
            $rev    = (float)$row['revenue_sum'];

            // ✅ Init department
            if (!isset($data[$dept_id])) {
                $data[$dept_id] = [
                    'department_id' => $dept_id,
                    'department_name' => $dept_name,
                    'channels' => []
                ];
            }

            // ✅ Init channel
            if (!isset($data[$dept_id]['channels'][$channel])) {
                $data[$dept_id]['channels'][$channel] = [
                    'total_leads' => 0,
                    'business' => 0,
                    'non_business' => 0,
                    'materialized' => 0,
                    'revenue_sum' => 0
                ];
            }

            // ✅ Total leads
            $data[$dept_id]['channels'][$channel]['total_leads'] += $count;

            // ✅ Business / Non-Business
            if (in_array($disp, $nonBusinessStages)) {
                $data[$dept_id]['channels'][$channel]['non_business'] += $count;
            } else {
                $data[$dept_id]['channels'][$channel]['business'] += $count;
            }

            // ✅ Materialized = reservation + closed
            if ($disp === 'reservation' && $status === 'closed') {
                $data[$dept_id]['channels'][$channel]['materialized'] += $count;
            }

            // ✅ Revenue Sum
            $data[$dept_id]['channels'][$channel]['revenue_sum'] += $rev;
        }

        return array_values($data);
    }


    public function getHotelCodeByProperty($property_id)
    {
        return $this->db
            ->select('hotel_code')
            ->from('hotel_admin')
            ->where('hotel_id', $property_id)
            ->get()
            ->row(); // returns object or null
    }


    public function getConfirmedBookings()
    {
        $this->db->where('booking_status_description!=', 'CHECKEDOUT');
        return $this->db->get('lead_pms_bookings')->result();
    }

    public function updateBookingInfo($booking_id, $data)
    {
        $this->db->where('booking_id', $booking_id);
        return $this->db->update('lead_pms_bookings', $data);
    }


    public function getCalendarVisits()
    {
        return $this->db
            ->select('sv.visit_id, sv.report_date, c.company_name')
            ->from('sales_visits sv')
            ->join('companies c', 'c.company_id = sv.company_id', 'left')
            ->where('sv.status', 1)
            ->where('sv.is_deleted', 0)
            ->get()
            ->result();
    }

    public function get_escalation_leads($now)
    {
        return $this->db
            ->where('status', 'Open')          // ✅ only open leads
            ->where('property', 1)
            ->where('esc_next_followup_at <=', $now)
            ->where('esc_follow_up_level <', 4)
            ->get('leads')
            ->result();
    }


    public function update_followup($lead_id, $data = [])
    {
        if (empty($lead_id) || empty($data)) {
            return false;
        }

        // $data['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', $lead_id);
        return $this->db->update('leads', $data);
    }


    public function get_lead_with_property_and_department($lead_id)
    {
        return $this->db
            ->select('
            l.*,
            h.hotel_name,
            h.email as hotel_admin_email,
            d.department_name
        ')
            ->from('leads l')
            ->join('hotel_admin h', 'h.hotel_id = l.property', 'left')
            ->join('departments d', 'd.department_id = l.type', 'left')
            ->where('l.id', $lead_id)
            ->get()
            ->row();
    }

    public function get_active_creators()
    {
        $sql = "
        SELECT DISTINCT 
            l.created_by as id,
            l.creator_user_role as role,

            CASE 
                WHEN l.creator_user_role = 'super_admin' THEN sa.full_name
                WHEN l.creator_user_role = 'hotel_admin' THEN ha.name
                WHEN l.creator_user_role = 'agent' THEN sm.name
            END as name

        FROM leads l

        LEFT JOIN super_admin sa 
            ON sa.id = l.created_by 
            AND l.creator_user_role = 'super_admin'

        LEFT JOIN hotel_admins ha 
            ON ha.id = l.created_by 
            AND l.creator_user_role = 'hotel_admin'

        LEFT JOIN staff_members sm 
            ON sm.id = l.created_by 
            AND l.creator_user_role = 'agent'

        WHERE l.created_by IS NOT NULL

        ORDER BY name ASC
    ";

        return $this->db->query($sql)->result();
    }

    public function get_active_assigned_users()
    {
        $sql = "
        SELECT DISTINCT 
            l.assigned_to as id,
            l.assigned_person_user_role as role,

            CASE 
                WHEN l.assigned_person_user_role = 'super_admin' THEN sa.full_name
                WHEN l.assigned_person_user_role = 'admin' THEN ha.name
                WHEN l.assigned_person_user_role = 'agent' THEN sm.name
            END as name

        FROM leads l

        LEFT JOIN super_admin sa 
            ON sa.id = l.assigned_to 
            AND l.assigned_person_user_role = 'super_admin'

        LEFT JOIN hotel_admins ha 
            ON ha.id = l.assigned_to 
            AND l.assigned_person_user_role = 'admin'

        LEFT JOIN staff_members sm 
            ON sm.id = l.assigned_to 
            AND l.assigned_person_user_role = 'agent'

        WHERE l.assigned_to IS NOT NULL

        ORDER BY name ASC
    ";

        return $this->db->query($sql)->result();
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Datatables extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function DTCountries($limit, $start, $search = "", $orderColumn = "country_id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('country');
    
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('country_name', $search);
            $this->db->or_like('country_code', $search);
            $this->db->group_end();
        }
    
        $this->db->order_by($orderColumn, $orderDir);
        
        $this->db->limit($limit, $start);
    
        return $this->db->get()->result();
    }
    
    public function DTCountriesAll()
    {
        return $this->db->count_all('country');
    }
    
    public function DTCountriesFiltered($search = "")
    {
        $this->db->from('country');
    
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('country_name', $search);
            $this->db->or_like('country_code', $search);
            $this->db->group_end();
        }
    
        return $this->db->count_all_results();
    }

    public function DTStates($limit, $start, $search = "", $orderColumn = "state.state_id", $orderDir = "DESC")
    {
        $this->db->select('state.*, country.country_name');
        $this->db->from('state');
        $this->db->join('country', 'state.country_id = country.country_id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('state.state_name', $search);
            $this->db->or_like('country.country_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTStatesAll()
    {
        return $this->db->count_all('state');
    }

    public function DTStatesFiltered($search = "")
    {
        $this->db->from('state');
        $this->db->join('country', 'state.country_id = country.country_id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('state.state_name', $search);
            $this->db->or_like('country.country_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTCities($limit, $start, $search = "", $orderColumn = "city.city_id", $orderDir = "DESC")
    {
        $this->db->select('city.*, country.country_name, state.state_name');
        $this->db->from('city');
        $this->db->join('country', 'city.country_id = country.country_id');
        $this->db->join('state', 'city.state_id = state.state_id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('city.city_name', $search);
            $this->db->or_like('state.state_name', $search);
            $this->db->or_like('country.country_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTCitiesAll()
    {
        return $this->db->count_all('city');
    }

    public function DTCitiesFiltered($search = "")
    {
        $this->db->from('city');
        $this->db->join('country', 'city.country_id = country.country_id');
        $this->db->join('state', 'city.state_id = state.state_id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('city.city_name', $search);
            $this->db->or_like('state.state_name', $search);
            $this->db->or_like('country.country_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTHotels($limit, $start, $search = "", $orderColumn = "hotel_admin.hotel_id", $orderDir = "DESC")
    {
        $this->db->select('hotel_admin.*, country.country_name, state.state_name, city.city_name');
        $this->db->from('hotel_admin');
        $this->db->join('country', 'hotel_admin.country_id = country.country_id', 'left');
        $this->db->join('state', 'hotel_admin.state_id = state.state_id', 'left');
        $this->db->join('city', 'hotel_admin.city_id = city.city_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('hotel_admin.hotel_name', $search);
            $this->db->or_like('hotel_admin.hotel_code', $search);
            $this->db->or_like('hotel_admin.facebook_page_id', $search);
            $this->db->or_like('country.country_name', $search);
            $this->db->or_like('state.state_name', $search);
            $this->db->or_like('city.city_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTHotelsAll()
    {
        return $this->db->count_all('hotel_admin');
    }

    public function DTHotelsFiltered($search = "")
    {
        $this->db->from('hotel_admin');
        $this->db->join('country', 'hotel_admin.country_id = country.country_id', 'left');
        $this->db->join('state', 'hotel_admin.state_id = state.state_id', 'left');
        $this->db->join('city', 'hotel_admin.city_id = city.city_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('hotel_admin.hotel_name', $search);
            $this->db->or_like('hotel_admin.hotel_code', $search);
            $this->db->or_like('hotel_admin.facebook_page_id', $search);
            $this->db->or_like('country.country_name', $search);
            $this->db->or_like('state.state_name', $search);
            $this->db->or_like('city.city_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTRestaurants($limit, $start, $search = "", $orderColumn = "r.id", $orderDir = "DESC")
    {
        $this->db->select('r.*, h.hotel_name');
        $this->db->from('hotel_restaurants r');
        $this->db->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('r.restaurant_name', $search);
            $this->db->or_like('r.restaurant_code', $search);
            $this->db->or_like('r.contact_number', $search);
            $this->db->or_like('r.email', $search);
            $this->db->or_like('h.hotel_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTRestaurantsAll()
    {
        return $this->db->count_all('hotel_restaurants');
    }

    public function DTRestaurantsFiltered($search = "")
    {
        $this->db->from('hotel_restaurants r');
        $this->db->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('r.restaurant_name', $search);
            $this->db->or_like('r.restaurant_code', $search);
            $this->db->or_like('r.contact_number', $search);
            $this->db->or_like('r.email', $search);
            $this->db->or_like('h.hotel_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTBanquets($limit, $start, $search = "", $orderColumn = "banquet.banquet_id", $orderDir = "DESC")
    {
        $this->db->select('banquet.*, hotel_admin.hotel_name');
        $this->db->from('banquet');
        $this->db->join('hotel_admin', 'hotel_admin.hotel_id = banquet.hotel_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('banquet.banquet_name', $search);
            $this->db->or_like('banquet.banquet_code', $search);
            $this->db->or_like('banquet.capacity', $search);
            $this->db->or_like('hotel_admin.hotel_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTBanquetsAll()
    {
        return $this->db->count_all('banquet');
    }

    public function DTBanquetsFiltered($search = "")
    {
        $this->db->from('banquet');
        $this->db->join('hotel_admin', 'hotel_admin.hotel_id = banquet.hotel_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('banquet.banquet_name', $search);
            $this->db->or_like('banquet.banquet_code', $search);
            $this->db->or_like('banquet.capacity', $search);
            $this->db->or_like('hotel_admin.hotel_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTSuperAdmins($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('id, full_name, email, phone, status, created_at, updated_at');
        $this->db->from('super_admin');
        $this->db->where('user_role', 2);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('full_name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('status', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTSuperAdminsAll()
    {
        return $this->db->where('user_role', 2)->count_all_results('super_admin');
    }

    public function DTSuperAdminsFiltered($search = "")
    {
        $this->db->from('super_admin');
        $this->db->where('user_role', 2);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('full_name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('status', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTHotelAdmins($limit, $start, $search = "", $orderColumn = "ha.id", $orderDir = "DESC")
    {
        $this->db->select('ha.id, ha.hotel_id, ha.name, ha.email, ha.phone, ha.status, ha.created_at, ha.updated_at, h.hotel_name');
        $this->db->from('hotel_admins ha');
        $this->db->join('hotel_admin h', 'h.hotel_id = ha.hotel_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ha.name', $search);
            $this->db->or_like('ha.email', $search);
            $this->db->or_like('ha.phone', $search);
            $this->db->or_like('h.hotel_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTHotelAdminsAll()
    {
        return $this->db->count_all('hotel_admins');
    }

    public function DTHotelAdminsFiltered($search = "")
    {
        $this->db->from('hotel_admins ha');
        $this->db->join('hotel_admin h', 'h.hotel_id = ha.hotel_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ha.name', $search);
            $this->db->or_like('ha.email', $search);
            $this->db->or_like('ha.phone', $search);
            $this->db->or_like('h.hotel_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTSalesUsers($limit, $start, $search = "", $orderColumn = "su.id", $orderDir = "DESC")
    {
        $this->db->select('su.*, city.city_name, state.state_name');
        $this->db->from('sales_users su');
        $this->db->join('city', 'city.city_id = su.city_id', 'left');
        $this->db->join('state', 'state.state_id = su.state_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('su.full_name', $search);
            $this->db->or_like('su.email', $search);
            $this->db->or_like('su.phone', $search);
            $this->db->or_like('su.user_role', $search);
            $this->db->or_like('city.city_name', $search);
            $this->db->or_like('state.state_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTSalesUsersAll()
    {
        return $this->db->count_all('sales_users');
    }

    public function DTSalesUsersFiltered($search = "")
    {
        $this->db->from('sales_users su');
        $this->db->join('city', 'city.city_id = su.city_id', 'left');
        $this->db->join('state', 'state.state_id = su.state_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('su.full_name', $search);
            $this->db->or_like('su.email', $search);
            $this->db->or_like('su.phone', $search);
            $this->db->or_like('su.user_role', $search);
            $this->db->or_like('city.city_name', $search);
            $this->db->or_like('state.state_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTStaffMembers($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('id, name, email, phone, created_at, updated_at');
        $this->db->from('staff_members');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTStaffMembersAll()
    {
        return $this->db->count_all('staff_members');
    }

    public function DTStaffMembersFiltered($search = "")
    {
        $this->db->from('staff_members');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTDepartments($limit, $start, $search = "", $orderColumn = "department_id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('departments');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('department_name', $search);
            $this->db->or_like('escalation_level_1', $search);
            $this->db->or_like('escalation_level_2', $search);
            $this->db->or_like('escalation_level_3', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTDepartmentsAll()
    {
        return $this->db->count_all('departments');
    }

    public function DTDepartmentsFiltered($search = "")
    {
        $this->db->from('departments');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('department_name', $search);
            $this->db->or_like('escalation_level_1', $search);
            $this->db->or_like('escalation_level_2', $search);
            $this->db->or_like('escalation_level_3', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTTableCategories($limit, $start, $search = "", $orderColumn = "tc.id", $orderDir = "DESC")
    {
        $this->db->select('tc.*, r.restaurant_name');
        $this->db->from('table_categories tc');
        $this->db->join('hotel_restaurants r', 'r.id = tc.restaurant_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('tc.category_name', $search);
            $this->db->or_like('tc.description', $search);
            $this->db->or_like('tc.status', $search);
            $this->db->or_like('r.restaurant_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTTableCategoriesAll()
    {
        return $this->db->count_all('table_categories');
    }

    public function DTTableCategoriesFiltered($search = "")
    {
        $this->db->from('table_categories tc');
        $this->db->join('hotel_restaurants r', 'r.id = tc.restaurant_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('tc.category_name', $search);
            $this->db->or_like('tc.description', $search);
            $this->db->or_like('tc.status', $search);
            $this->db->or_like('r.restaurant_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTTables($limit, $start, $search = "", $orderColumn = "t.id", $orderDir = "DESC")
    {
        $this->db->select('t.*, r.restaurant_name, c.category_name');
        $this->db->from('tables t');
        $this->db->join('hotel_restaurants r', 'r.id = t.restaurant_id', 'left');
        $this->db->join('table_categories c', 'c.id = t.category_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('t.table_name', $search);
            $this->db->or_like('t.table_number', $search);
            $this->db->or_like('t.capacity', $search);
            $this->db->or_like('t.status', $search);
            $this->db->or_like('r.restaurant_name', $search);
            $this->db->or_like('c.category_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTTablesAll()
    {
        return $this->db->count_all('tables');
    }

    public function DTTablesFiltered($search = "")
    {
        $this->db->from('tables t');
        $this->db->join('hotel_restaurants r', 'r.id = t.restaurant_id', 'left');
        $this->db->join('table_categories c', 'c.id = t.category_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('t.table_name', $search);
            $this->db->or_like('t.table_number', $search);
            $this->db->or_like('t.capacity', $search);
            $this->db->or_like('t.status', $search);
            $this->db->or_like('r.restaurant_name', $search);
            $this->db->or_like('c.category_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTTimeSlots($limit, $start, $search = "", $orderColumn = "ts.id", $orderDir = "DESC")
    {
        $this->db->select('ts.*, st.slot_name as slot_type_name');
        $this->db->from('time_slots ts');
        $this->db->join('slot_types st', 'st.id = ts.slot_type_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('st.slot_name', $search);
            $this->db->or_like('ts.start_time', $search);
            $this->db->or_like('ts.end_time', $search);
            $this->db->or_like('ts.status', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTTimeSlotsAll()
    {
        return $this->db->count_all('time_slots');
    }

    public function DTTimeSlotsFiltered($search = "")
    {
        $this->db->from('time_slots ts');
        $this->db->join('slot_types st', 'st.id = ts.slot_type_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('st.slot_name', $search);
            $this->db->or_like('ts.start_time', $search);
            $this->db->or_like('ts.end_time', $search);
            $this->db->or_like('ts.status', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTRoomTypes($limit, $start, $search = "", $orderColumn = "roomtype.roomtype_id", $orderDir = "DESC")
    {
        $this->db->select('roomtype.*, hotel_admin.hotel_name');
        $this->db->from('roomtype');
        $this->db->join('hotel_admin', 'hotel_admin.hotel_id = roomtype.hotel_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('roomtype.roomtype_name', $search);
            $this->db->or_like('roomtype.roomtype_code', $search);
            $this->db->or_like('hotel_admin.hotel_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTRoomTypesAll()
    {
        return $this->db->count_all('roomtype');
    }

    public function DTRoomTypesFiltered($search = "")
    {
        $this->db->from('roomtype');
        $this->db->join('hotel_admin', 'hotel_admin.hotel_id = roomtype.hotel_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('roomtype.roomtype_name', $search);
            $this->db->or_like('roomtype.roomtype_code', $search);
            $this->db->or_like('hotel_admin.hotel_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTRateTypes($limit, $start, $search = "", $orderColumn = "ratetype_id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('ratetype');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ratetype_name', $search);
            $this->db->or_like('ratetype_code', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTRateTypesAll()
    {
        return $this->db->count_all('ratetype');
    }

    public function DTRateTypesFiltered($search = "")
    {
        $this->db->from('ratetype');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ratetype_name', $search);
            $this->db->or_like('ratetype_code', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTMealPlans($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('meal_plans');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('plan', $search);
            $this->db->or_like('plan_code', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTMealPlansAll()
    {
        return $this->db->count_all('meal_plans');
    }

    public function DTMealPlansFiltered($search = "")
    {
        $this->db->from('meal_plans');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('plan', $search);
            $this->db->or_like('plan_code', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTPromotionalOffers($limit, $start, $search = "", $orderColumn = "po.id", $orderDir = "DESC")
    {
        $this->db->select('po.*, d.department_name');
        $this->db->from('promotional_offers po');
        $this->db->join('departments d', 'd.department_id = po.department_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('po.offer_name', $search);
            $this->db->or_like('d.department_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTPromotionalOffersAll()
    {
        return $this->db->count_all('promotional_offers');
    }

    public function DTPromotionalOffersFiltered($search = "")
    {
        $this->db->from('promotional_offers po');
        $this->db->join('departments d', 'd.department_id = po.department_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('po.offer_name', $search);
            $this->db->or_like('d.department_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTDesignations($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('designations');

        if (!empty($search)) {
            $this->db->like('designation_name', $search);
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTDesignationsAll()
    {
        return $this->db->count_all('designations');
    }

    public function DTDesignationsFiltered($search = "")
    {
        $this->db->from('designations');

        if (!empty($search)) {
            $this->db->like('designation_name', $search);
        }

        return $this->db->count_all_results();
    }

    public function DTTeamGroups($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('team_groups');

        if (!empty($search)) {
            $this->db->like('team_group_name', $search);
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTTeamGroupsAll()
    {
        return $this->db->count_all('team_groups');
    }

    public function DTTeamGroupsFiltered($search = "")
    {
        $this->db->from('team_groups');

        if (!empty($search)) {
            $this->db->like('team_group_name', $search);
        }

        return $this->db->count_all_results();
    }

    public function DTTravelModes($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('travel_modes');

        if (!empty($search)) {
            $this->db->like('travel_mode_name', $search);
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTTravelModesAll()
    {
        return $this->db->count_all('travel_modes');
    }

    public function DTTravelModesFiltered($search = "")
    {
        $this->db->from('travel_modes');

        if (!empty($search)) {
            $this->db->like('travel_mode_name', $search);
        }

        return $this->db->count_all_results();
    }

    public function DTSlotTypes($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('slot_types');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('slot_name', $search);
            $this->db->or_like('start_time', $search);
            $this->db->or_like('end_time', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTSlotTypesAll()
    {
        return $this->db->count_all('slot_types');
    }

    public function DTSlotTypesFiltered($search = "")
    {
        $this->db->from('slot_types');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('slot_name', $search);
            $this->db->or_like('start_time', $search);
            $this->db->or_like('end_time', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTWhatsappTemplates($limit, $start, $search = "", $orderColumn = "wt.id", $orderDir = "DESC")
    {
        $this->db->select('wt.*, p.hotel_name');
        $this->db->from('whatsapp_templates wt');
        $this->db->join('hotel_admin p', 'p.hotel_id = wt.property_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('wt.template_name', $search);
            $this->db->or_like('wt.orai_template_code', $search);
            $this->db->or_like('p.hotel_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTWhatsappTemplatesAll()
    {
        return $this->db->count_all('whatsapp_templates');
    }

    public function DTWhatsappTemplatesFiltered($search = "")
    {
        $this->db->from('whatsapp_templates wt');
        $this->db->join('hotel_admin p', 'p.hotel_id = wt.property_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('wt.template_name', $search);
            $this->db->or_like('wt.orai_template_code', $search);
            $this->db->or_like('p.hotel_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTCompanyGroups($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('company_groups');

        if (!empty($search)) {
            $this->db->like('company_group_name', $search);
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTCompanyGroupsAll()
    {
        return $this->db->count_all('company_groups');
    }

    public function DTCompanyGroupsFiltered($search = "")
    {
        $this->db->from('company_groups');

        if (!empty($search)) {
            $this->db->like('company_group_name', $search);
        }

        return $this->db->count_all_results();
    }

    public function DTAreas($limit, $start, $search = "", $orderColumn = "a.area_id", $orderDir = "DESC")
    {
        $this->db->select('a.*, s.state_name, su1.full_name as primary_user_name, su2.full_name as secondary_user_name');
        $this->db->from('areas a');
        $this->db->join('state s', 's.state_id = a.state_id', 'left');
        $this->db->join('sales_users su1', 'su1.id = a.primary_user_id', 'left');
        $this->db->join('sales_users su2', 'su2.id = a.secondary_user_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.area_name', $search);
            $this->db->or_like('s.state_name', $search);
            $this->db->or_like('su1.full_name', $search);
            $this->db->or_like('su2.full_name', $search);
            $this->db->or_like('a.status', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTAreasAll()
    {
        return $this->db->count_all('areas');
    }

    public function DTAreasFiltered($search = "")
    {
        $this->db->from('areas a');
        $this->db->join('state s', 's.state_id = a.state_id', 'left');
        $this->db->join('sales_users su1', 'su1.id = a.primary_user_id', 'left');
        $this->db->join('sales_users su2', 'su2.id = a.secondary_user_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.area_name', $search);
            $this->db->or_like('s.state_name', $search);
            $this->db->or_like('su1.full_name', $search);
            $this->db->or_like('su2.full_name', $search);
            $this->db->or_like('a.status', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTCompanies($limit, $start, $search = "", $orderColumn = "c.company_id", $orderDir = "DESC")
    {
        $this->db->select('c.*, cg.company_group_name, city.city_name, state.state_name, area.area_name');
        $this->db->from('companies c');
        $this->db->join('company_groups cg', 'cg.id = c.company_group_id', 'left');
        $this->db->join('city', 'city.city_id = c.city_id', 'left');
        $this->db->join('state', 'state.state_id = c.state_id', 'left');
        $this->db->join('areas area', 'area.area_id = c.area_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.company_name', $search);
            $this->db->or_like('c.email', $search);
            $this->db->or_like('c.mobile_number', $search);
            $this->db->or_like('cg.company_group_name', $search);
            $this->db->or_like('city.city_name', $search);
            $this->db->or_like('state.state_name', $search);
            $this->db->or_like('area.area_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTCompaniesAll()
    {
        return $this->db->count_all('companies');
    }

    public function DTCompaniesFiltered($search = "")
    {
        $this->db->from('companies c');
        $this->db->join('company_groups cg', 'cg.id = c.company_group_id', 'left');
        $this->db->join('city', 'city.city_id = c.city_id', 'left');
        $this->db->join('state', 'state.state_id = c.state_id', 'left');
        $this->db->join('areas area', 'area.area_id = c.area_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.company_name', $search);
            $this->db->or_like('c.email', $search);
            $this->db->or_like('c.mobile_number', $search);
            $this->db->or_like('cg.company_group_name', $search);
            $this->db->or_like('city.city_name', $search);
            $this->db->or_like('state.state_name', $search);
            $this->db->or_like('area.area_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTCompanyContacts($limit, $start, $search = "", $orderColumn = "cc.contact_id", $orderDir = "DESC")
    {
        $this->db->select('cc.*, c.company_name, co.country_name, s.state_name, ci.city_name, d.designation_name');
        $this->db->from('company_contacts cc');
        $this->db->join('companies c', 'c.company_id = cc.company_id', 'left');
        $this->db->join('country co', 'co.country_id = cc.country', 'left');
        $this->db->join('state s', 's.state_id = cc.state', 'left');
        $this->db->join('city ci', 'ci.city_id = cc.city', 'left');
        $this->db->join('designations d', 'd.id = cc.designation', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.company_name', $search);
            $this->db->or_like('cc.first_name', $search);
            $this->db->or_like('cc.last_name', $search);
            $this->db->or_like('cc.email', $search);
            $this->db->or_like('cc.mobile_number', $search);
            $this->db->or_like('d.designation_name', $search);
            $this->db->or_like('ci.city_name', $search);
            $this->db->or_like('s.state_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTCompanyContactsAll()
    {
        return $this->db->count_all('company_contacts');
    }

    public function DTCompanyContactsFiltered($search = "")
    {
        $this->db->from('company_contacts cc');
        $this->db->join('companies c', 'c.company_id = cc.company_id', 'left');
        $this->db->join('country co', 'co.country_id = cc.country', 'left');
        $this->db->join('state s', 's.state_id = cc.state', 'left');
        $this->db->join('city ci', 'ci.city_id = cc.city', 'left');
        $this->db->join('designations d', 'd.id = cc.designation', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.company_name', $search);
            $this->db->or_like('cc.first_name', $search);
            $this->db->or_like('cc.last_name', $search);
            $this->db->or_like('cc.email', $search);
            $this->db->or_like('cc.mobile_number', $search);
            $this->db->or_like('d.designation_name', $search);
            $this->db->or_like('ci.city_name', $search);
            $this->db->or_like('s.state_name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function DTAgencies($limit, $start, $search = "", $orderColumn = "id", $orderDir = "DESC")
    {
        $this->db->select('*');
        $this->db->from('agencies');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('agency_name', $search);
            $this->db->or_like('contact_person', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('status', $search);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function DTAgenciesAll()
    {
        return $this->db->count_all('agencies');
    }

    public function DTAgenciesFiltered($search = "")
    {
        $this->db->from('agencies');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('agency_name', $search);
            $this->db->or_like('contact_person', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('status', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
//Class ended 
}

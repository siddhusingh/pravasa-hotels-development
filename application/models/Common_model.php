    <?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    class Common_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
        }
        public function insertData($table, $data)
        {
            $this->db->insert($table, $data);
            $id = $this->db->insert_id();
            if (!empty($id > 0)) {
                return $id;
            } else {
                return false;
            }
        }

        public function insertActivityLog($data)
        {
            $this->db->insert('activity_logs', $data);
            return $this->db->insert_id();
        }

        public function get_audit_exportdata($project_id, $selected_items)

        {
            $query = $this->db->select("projects.project_name,projects.client_name,projects.project_code,projects.portifolio,audit_tracker_new.audit_type,audit_tracker_new.audit_quarter,audit_tracker_new.auditor_name,audit_tracker_new.audit_category,audit_tracker_new.auditee,audit_tracker_new.planned_date,audit_tracker_new.actual_date,audit_tracker_new.audit_report_updated_date,audit_tracker_new.created_on")->where_in('id', $selected_items)->where('projects.project_id', $project_id);

            $query = $this->db->join('projects', 'projects.project_id=audit_tracker_new.project_id');
            $query = $this->db->get('audit_tracker_new');
            $result = $query->result_array();
            return $result;
        }


        public function getHotelAdminsWithHotels()
        {
            $this->db->select("
        ha.id,
        ha.name,
        ha.email,
        ha.phone,
        ha.status,
        ha.created_at,
        h.hotel_name
    ");
            $this->db->from('hotel_admins ha');
            $this->db->join(
                'hotel_admin h',
                'h.hotel_id = ha.hotel_id',
                'left'
            );
            $this->db->order_by('ha.id', 'DESC');

            return $this->db->get()->result();
        }




        public function get_audit_list_of_artifacts_exportdata($project_id, $selected_items)

        {
            $query = $this->db->select("audit_tracker_new.list_of_artifacts")->where_in('id', $selected_items)->where('projects.project_id', $project_id);

            $query = $this->db->join('projects', 'projects.project_id=audit_tracker_new.project_id');
            $query = $this->db->get('audit_tracker_new');
            $result = $query->result_array();
            return $result;
        }


        public function get_audit_notes_exportdata($project_id, $selected_items)

        {
            $query = $this->db->select("audit_tracker_new.audit_notes")->where_in('id', $selected_items)->where('projects.project_id', $project_id);

            $query = $this->db->join('projects', 'projects.project_id=audit_tracker_new.project_id');
            $query = $this->db->get('audit_tracker_new');
            $result = $query->result_array();
            return $result;
        }


        public function get_finding_data_exportdata($project_id, $selected_items)

        {

            $query = "SELECT 
    ROW_NUMBER() OVER (ORDER BY id) AS index_num, 
    `audit_findings`, 
    `category_of_findings`, 
    `target_closure_date`, 
    `clause`, 
    `controls`, 
    `finding_responsbility`, 
    `correction`, 
    `rca`, 
    `corrective_action`, 
    CASE 
        WHEN `finding_status_auditor` = 1 THEN
            CASE 
                WHEN finding_status = 1 THEN 'Open' 
                WHEN finding_status = 2 THEN 'Action Taken' 
                WHEN finding_status = 3 THEN 'Reopen' 
                ELSE 'Closed' 
            END 
        ELSE 'NA' 
    END AS finding_status, 
    `remarks`   
FROM 
    `table_audit_findings`
WHERE 
    `audit_id` IN ($selected_items)";

            $result = $this->db->query($query)->result_array();
            return $result;
        }


        public function reminderable_risks($value = '')
        {
            $this->db->select('*');
            $this->db->from('risk_tracker');
            $this->db->where('DATEDIFF(NOW(), risk_created_date) >=', 30);
            $this->db->or_where('NOW() >= risk_target_date');
            $this->db->where('status_save', 1);
            $this->db->where('risk_target_date!=', '');
            $this->db->where_in('status', ['WIP', 'Open']);
            $query = $this->db->get();

            $result = $query->result();

            return $result;
        }


        public function Product()
        {

            $this->db->select('product.*,categories.category_title');
            $this->db->from('product');
            $query = $this->db->where('product.status', 1);
            $query = $this->db->join("categories", "categories.id=product.category_id");

            $query = $this->db->get();
            $data = $query->result_array();

            return $data;
        }

        public function Product_data()
        {

            $this->db->select('product.*,subcategories.subcategory_title,categories.category_title,childcategories.childcategory_title,colors.color_name,size.size,brands.brand_name');
            $this->db->from('product');
            $query = $this->db->where('product.status', 1);
            $query = $this->db->join("categories", "categories.id=product.category_id");
            $query = $this->db->join("subcategories", "subcategories.sub_id=product.subcategory_id");
            $query = $this->db->join("childcategories", "childcategories.id=product.childcategory_id");
            $query = $this->db->join("size", "sizes.id=product.size_id");
            $query = $this->db->join("colors", "colors.id=product.color_id");
            $query = $this->db->join("brand", "brands.id=product.brand_id");


            $query = $this->db->get();
            $data = $query->result_array();

            return $data;
        }





        function UpdateRecord($TableName, $Data, $WhereData = NULL)
        {
            if ($WhereData != NULL) {
                $this->db->where($WhereData);
            }
            $Result = $this->db->update($TableName, $Data);
            return $Result;
        }
        function Deletedata($table, $where)
        {
            $this->db->delete($table, $where);
            return TRUE;
        }
        public function getdata($table, $where)
        {
            $this->db->select('*');
            if (!empty($where)) {
                $this->db->where($where);
            }
            $query = $this->db->get($table)->row();
            return $query;
        }

        public function getdata_array($table, $where)
        {
            $this->db->select('*');
            if (!empty($where)) {
                $this->db->where($where);
            }
            $this->db->order_by('id', 'desc');

            $query = $this->db->get($table)->result_array();
            return $query;
        }





        function getdata_array_risk($project_id)
        {
            $query = $this->db
                ->select('*')
                ->from('risk_tracker')
                ->where("(project_id = '$project_id' AND status_save = 1)")
                ->or_where("(project_id = '$project_id' AND status_save = 0 AND approval_status = 2)")
                ->get();

            $result = $query->result_array();
            return $result;
        }


        function getdata_array_risk_for_auditee($project_id, $logged_in_user_id)
        {
            $query = $this->db
                ->select('*')
                ->from('risk_tracker')
                ->where("(project_id = '$project_id')")
                ->group_start()
                ->where("(project_id = '$project_id' AND risk_creator_id = '$logged_in_user_id' AND status_save = 0)")
                ->or_group_start()
                ->where("(project_id = '$project_id' AND status_save = 1)")
                ->where("risk_creator_id != '$logged_in_user_id'")
                ->group_end()
                ->or_where("(project_id = '$project_id' AND status_save = 1)")
                ->group_end()
                ->get();

            $result = $query->result_array();
            return $result;
        }


        public function count_all(
            $table,
            $where = "",
            $start_date = null,
            $end_date = null,

            $created_id = "",
            $created_role = "",
            $assigned_id = "",
            $assigned_role = "",
            $channel = "",
            $disposition = ""
        ) {
            if (!empty($where['property'])) {
                $this->db->where('property', $where['property']);
            }


            if (!empty($where['type'])) {
                $this->db->where('type', $where['type']);
            }


            if (!empty($disposition)) {
                $this->db->where('disposition', $disposition);
            }

            if (!empty($channel)) {
                $this->db->where('user_channel', $channel);
            }

            if (!empty($where['created_by'])) {
                $this->db->where('created_by', $where['created_by']);
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
                $this->db->where('DATE(created_at) >=', $start_date); // Remove 'leads.'
            }

            if (!empty($end_date)) {
                $this->db->where('DATE(created_at) <=', $end_date); // Remove 'leads.'
            }


            return $this->db->count_all_results($table);
        }


        public function get_properties_by_agency($agency_id)
        {
            $this->db->select('agency_property_mapping.*, hotel_admin.hotel_id, hotel_admin.hotel_name');
            $this->db->from('agency_property_mapping');
            $this->db->join('hotel_admin', 'agency_property_mapping.property_id = hotel_admin.hotel_id', 'left');
            $this->db->where('agency_property_mapping.agency_id', $agency_id);
            // optional: order by hotel's name or mapping id
            $this->db->order_by('hotel_admin.hotel_name', 'ASC');
            return $this->db->get()->result();
        }






        public function count_all_lead_calls($table, $where = [], $start_date = null, $end_date = null)
        {
            $this->db->from($table);
            $this->db->join('leads', 'leads.id = calls.leadid');

            if (!empty($where['property'])) {
                $this->db->where('property', $where['property']);
            }


            if (!empty($where['type'])) {
                $this->db->where('type', $where['type']);
            }

            if (!empty($where['created_by'])) {
                $this->db->where('created_by', $where['created_by']);
            }

            if (!empty($where['overall_call_status'])) {
                $this->db->where('calls.overall_call_status', $where['overall_call_status']);
            }

            if (!empty($start_date)) {
                $this->db->where('DATE(created_at) >=', $start_date);
            }

            if (!empty($end_date)) {
                $this->db->where('DATE(created_at) <=', $end_date);
            }


            return $this->db->count_all_results();
        }


        public function get_total_revenue_from_leads(
            $table = 'leads',
            $where = [],
            $start_date = null,
            $end_date = null,
            $created_id = "",
            $created_role = "",
            $assigned_id = "",
            $assigned_role = "",
            $channel = "",
            $disposition = ""

        ) {
            $this->db->select_sum('amount');


            if (!empty($where['property'])) {
                $this->db->where('property', $where['property']);
            }


            if (!empty($where['type'])) {
                $this->db->where('type', $where['type']);
            }

            if (!empty($disposition)) {
                $this->db->where('disposition', $disposition);
            }

            if (!empty($channel)) {
                $this->db->where('user_channel', $channel);
            }


            if (!empty($start_date)) {
                $this->db->where('DATE(created_at) >=', $start_date);
            }

            if (!empty($end_date)) {
                $this->db->where('DATE(created_at) <=', $end_date);
            }


            if (!empty($created_id) && !empty($created_role)) {
                $this->db->where('leads.created_by', $created_id);
                $this->db->where('leads.creator_user_role', $created_role);
            }

            if (!empty($assigned_id) && !empty($assigned_role)) {
                $this->db->where('leads.assigned_to', $assigned_id);
                $this->db->where('leads.assigned_person_user_role', $assigned_role);
            }


            $query = $this->db->get($table);
            $result = $query->row();
            return $result->amount ?? 0;
        }






        public function getdata_auditee($table, $where, $project_id)
        {
            $this->db->select('*');
            $this->db->from('user_profile');
            $this->db->where('is_it_auditee', '1');
            $this->db->where("FIND_IN_SET($project_id, REPLACE(`assigned_project`, '!', ',')) > 0");

            $query = $this->db->get();
            $result = $query->result();

            return $result;
        }

        public function getdata_array_projects($table, $where)
        {
            $this->db->select('projects.*,user_profile.full_name')->join('user_profile', 'projects.project_manager_id=user_profile.id');
            if (!empty($where)) {
                $this->db->where($where);
            }

            $query = $this->db->get($table)->result_array();
            return $query;
        }


        public function get_auditee_projects($table, $where, $projects_id_array)
        {
            $this->db->select('projects.*,user_profile.full_name')->join('user_profile', 'projects.project_manager_id=user_profile.id');
            if (!empty($where)) {
                $this->db->where($where);
            }

            $this->db->where_in('projects.project_id', $projects_id_array);

            $query = $this->db->get($table)->result_array();
            return $query;
        }





        public function getcartdata($table, $where)
        {
            $this->db->select('*');
            if (!empty($where)) {
                $this->db->where($where);
            }
            $query = $this->db->get($table)->result();
            return $query;
        }




        public function getAllData($table, $where, $oderBy = '')
        {
            $this->db->select('*');
            if (!empty($where)) {
                $this->db->where($where);
            }
            if (!empty($oderBy)) {
                $this->db->from($table);
                $this->db->order_by($oderBy, "ASC");
                $query = $this->db->get();
                return $query->result();
            } else {
                $query = $this->db->get($table)->result();
                return $query;
            }
        }

        public function get_field_value($table, $where_column, $where_value, $target_column)
        {
            $this->db->select($target_column);
            $this->db->from($table);
            $this->db->where($where_column, $where_value);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row()->$target_column;
            }

            return 'NA';
        }


        public function getAlluser_channel($table, $where, $oderBy = '')
        {
            $this->db->select('user_channel');
            if (!empty($where)) {
                $this->db->where($where);
            }
            $this->db->from($table);
            $this->db->order_by($oderBy, "ASC");
            $this->db->group_by('user_channel');

            $query = $this->db->get();

            return $query->result();
        }


        public function get_findings_for_scheduler($table, $where)
        {
            $this->db->select('*');
            $this->db->from('table_audit_findings');
            $this->db->where('(category_of_findings = "Minor NC" OR category_of_findings = "Major NC")');
            $this->db->where($where);
            $this->db->where(array('finding_status_auditor' => 1));
            $this->db->where('finding_status!=', 0);
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }


        public function get_findings_for_scheduler_observation($table, $where)
        {
            $this->db->select('*');

            $this->db->where('category_of_findings', "Observation");
            //$this->db->or_where('category_of_findings',"Major Non-Conformance");
            $this->db->where($where);
            $this->db->where(array('finding_status_auditor' => 1));
            $this->db->where('finding_status!=', 0);
            $this->db->from($table);
            $this->db->order_by($oderBy, "ASC");
            $query = $this->db->get();
            return $query->result();
        }


        public function resource_stage()
        {
            $this->db->select('resources.*,stages.stage_name');
            $query = $this->db->from('resources');
            $query = $this->db->where('resources.status', 1);
            $query = $this->db->join("stages", "stages.id =resources.stage_id");
            $query = $this->db->order_by('id', "ASC");
            $query = $this->db->get();
            $data = $query->result_array();
            return $data;
        }


        public function subcategory_category_child()
        {
            $this->db->select('childcategories.*,categories.category_title ,subcategories.subcategory_title');
            $query = $this->db->from('childcategories');
            $query = $this->db->where('childcategories.status', 1);
            $query = $this->db->join("categories", "categories.id =childcategories.category_id");
            $query = $this->db->join("subcategories", "subcategories.id =childcategories.subcategory_id");
            $query = $this->db->order_by('id', "ASC");
            $query = $this->db->get();
            $data = $query->result_array();
            return $data;
        }








        public function getAllData_limit($table, $where, $oderBy = '')
        {
            $this->db->select('*');
            if (!empty($where)) {
                $this->db->where($where);
            }
            if (!empty($oderBy)) {
                $this->db->from($table);
                $this->db->order_by($oderBy, "DESC");
                $this->db->limit('7');
                $query = $this->db->get();
                return $query->result();
            } else {
                $this->db->limit('7');
                $query = $this->db->get($table)->result();
                return $query;
            }
        }

        public function get_tabbing_products()
        {
            $this->db->from('categories');
            $this->db->order_by('category_id', "asc");
            $this->db->limit(5);
            $query = $this->db->get();
            $all_catgory = $query->result();
            $response_data = array();
            foreach ($all_catgory as $each_category) {
                $response_data[$each_category->category_id]['category_name'] = $each_category->category_name;
                $response_data[$each_category->category_id]['category_products'] = $this->get_product_by_cat_id($each_category->category_id);
            }

            return $response_data;
        }

        public function get_product_by_cat_id($category_id)
        {
            $this->db->select('*');
            $this->db->where('product_cat', $category_id);
            $query = $this->db->join("categories", "categories.category_id = products.product_cat");
            $query =  $this->db->order_by('product_id', 'desc');
            $query->limit('5');
            $query = $this->db->get('products');
            $result = $query->result();
            return $result;
        }


        function product_get($keyword)
        {
            $this->db->select('*');
            $query = $this->db->join("product_color_size_record", "products.prid = product_color_size_record.pcs_product_rand_id");
            $query = $this->db->join("categories", "categories.category_id = products.product_cat");
            $query = $this->db->join("brands", "products.product_brand = brands.brand_id");
            // $query = $this->db->join("sizes","sizes.size_id = product_color_size_record.pcs_size");
            if (!empty($keyword)) {
                $this->db->like('products.product_type', $keyword, 'both');
            }
            $this->db->where('products.product_status', '1');


            $query =  $this->db->order_by('products.product_id', 'pcs_id');
            $query->limit('15');
            $query = $this->db->get('products');
            // echo $this->db->last_query($query);
            $result = $query->result();
            return $result;
        }


        function get_productById($pid)
        {

            $query = $this->db->query("SELECT * FROM products WHERE product_url='$pid'");
            $result['product_detail'] = $query->result_array();
            for ($i = 0; $i < count($result['product_detail']); $i++) {

                if (!empty($cid)) {
                    $condition = $this->get_rows('wishlist', array(
                        'wish_user_id' => $cid,
                        'wish_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['wishlist'] = 'true';
                    } else {
                        $result['product_detail'][$i]['wishlist'] = 'false';
                    }
                } else {
                    $result['product_detail'][$i]['wishlist'] = 'false';
                }

                if (empty($cid)) {
                    $session_id = $this->session->userdata('session_id');
                    $condition = $this->get_rows('cart', array(
                        'cart_session_id' => $session_id,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                } else {
                    $cid = $this->session->userdata('cid');
                    $condition = $this->get_rows('cart', array(
                        'cart_user_id' => $cid,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                }
            }

            return $result['product_detail'];
        }

        function related_product($catid, $pid)
        {
            $this->db->select('*');

            $query = $this->db->join("categories", "categories.category_id = products.product_cat");
            $query = $this->db->join("sizes", "sizes.size_id = products.product_size");



            $this->db->where('products.product_status', '1');
            $this->db->where('products.product_id != ', $pid);
            $this->db->where('products.product_cat', $catid);

            $query =  $this->db->order_by('products.product_id', 'RANDOM');
            $query = $this->db->limit('6');
            $query = $this->db->get('products');
            // echo $this->db->last_query($query);
            $result = $query->result();
            return $result;
        }































        public function seo_url($url)
        {
            $category_url = str_replace(" ", "-", strtolower(trim($url)));
            return $category_url = str_replace("&", "and", $category_url);
        }


        public function get_product_details($url)
        {
            $lang = $this->session->userdata('site_lang');
            if ($lang == 'english') {
                $query = $this->db->query("SELECT products.product_id,products.product_title,products.product_sdisc,products.product_cat,products.product_subcat,products.product_childcat,products.product_brand,products.product_url,products.prid,products.vendor_id,products.note,products.materials, brands.brand_name, sizes.size_name, colors.color_name, colors.color_id, sizes.size_id, product_color_size_record.* FROM `products`, brands, product_color_size_record, colors, sizes WHERE product_color_size_record.pcs_product_rand_id = products.prid AND brands.brand_id = product_brand AND colors.color_id = product_color_size_record.pcs_color_id AND sizes.size_id = product_color_size_record.pcs_size  AND products.product_status = '1' AND products.product_url = '$url' ");
            } else {
                $query = $this->db->query("SELECT products.product_id,products.ar_product_title as product_title,products.product_sdisc,products.product_cat,products.product_subcat,products.product_childcat,products.product_brand,products.product_url,products.prid,products.vendor_id,products.ar_editor_notes as note,products.ar_materials as materials, brands.ar_brand_name as brand_name, sizes.ar_size_name as size_name, colors.ar_color_name as color_name, colors.color_id, sizes.size_id, product_color_size_record.* FROM `products`, brands, product_color_size_record, colors, sizes WHERE product_color_size_record.pcs_product_rand_id = products.prid AND brands.brand_id = product_brand AND colors.color_id = product_color_size_record.pcs_color_id AND sizes.size_id = product_color_size_record.pcs_size AND products.product_status = '1' AND products.product_url = '$url' ");
            }
            return $query->row();
        }

        function product_bycategory($keyword)
        {
            $this->db->select('*');
            $query = $this->db->join("product_color_size_record", "products.prid = product_color_size_record.pcs_product_rand_id");
            $query = $this->db->join("categories", "categories.category_id = products.product_cat");

            $this->db->where('products.product_status', '1');
            $this->db->where('products.product_cat', $keyword);
            $query = $this->db->group_by('product_color_size_record.pcs_product_rand_id');
            //   $query =  $this->db->order_by('products.product_id', 'RANDOM');
            $query = $this->db->limit('8');
            $query = $this->db->get('products');
            // echo $this->db->last_query($query);
            $result = $query->result();
            return $result;
        }





        function count_allproduct($where)
        {

            $this->db->select('*');
            $query = $this->db->join("product_color_size_record", "products.prid = product_color_size_record.pcs_product_rand_id");
            $query = $this->db->join("categories", "categories.category_id = products.product_cat");
            $this->db->where('products.product_status', '1');
            $this->db->where($where);
            $query = $this->db->group_by('product_color_size_record.pcs_product_rand_id');
            $query = $this->db->limit('6');
            $query = $this->db->get('products');
            $result = $query->result();
            return $result;
        }



        function cart_detail($session, $cid)
        {


            $query = $this->db->query("SELECT * FROM cart JOIN products ON products.product_id=cart.cart_product_id WHERE cart_session_id='$session' OR cart_user_id='$cid' AND cart_user_id!='' ORDER BY cart.cart_price");

            //print_r($query->result_array);die;
            return $query->result_array();
        }




        function wishlist_detail($session, $cid)
        {
            $query = $this->db->query(" SELECT * FROM `wishlist` JOIN products ON products.product_id=wishlist.wish_product_id WHERE `wish_user_id`='$cid'  ");
            return $query->result_array();
        }

        public function loginData($arrPostedData)
        {
            $arrResult = array();
            if ($arrPostedData['email']) {
                $where = "cust_email ='" . $arrPostedData['email'] . "'";
                $this->db->select('*');
                $this->db->where($where);
                $this->db->from('customer');
                $result1 = $this->db->get()->result_array();
                if (!empty($result1)) {
                    if (($arrPostedData['email']) && ($arrPostedData['password'])) {
                        $where = "cust_email ='" . $arrPostedData['email'] . "' AND cust_pass ='" . $arrPostedData['password'] . "' AND cust_status= 1";
                        $this->db->select('*');
                        $this->db->where($where);
                        $this->db->from('customer');
                        $result = $this->db->get()->result_array();
                        if (!empty($result)) {
                            $arrResult['userId'] = $result[0]['cust_id'];
                            $arrResult['emailId'] = $result[0]['cust_email'];
                            $arrResult['cust_name'] = $result[0]['cust_name'];
                            $arrResult['cust_fname'] = $result[0]['cust_fname'];
                            $arrResult['cust_lname'] = $result[0]['cust_lname'];
                            $arrResult['cust_email'] = $result[0]['cust_email'];
                            $arrResult['cust_phone'] = $result[0]['cust_phone'];

                            $arrResult['city'] = $result[0]['city'];
                            $arrResult['state'] = $result[0]['state'];
                            $arrResult['address'] = $result[0]['address'];
                            $arrResult['zip'] = $result[0]['zip'];

                            $arrResult['ship_city'] = $result[0]['ship_city'];
                            $arrResult['ship_state'] = $result[0]['ship_state'];
                            $arrResult['ship_address'] = $result[0]['ship_address'];
                            $arrResult['ship_zip'] = $result[0]['ship_zip'];
                        } else {
                            if ($arrPostedData['email'] != $result1[0]['cust_email']) {
                                $arrResult['emailError'] = 'Please enter a valid email address';
                            }
                            if ($arrPostedData['password'] != $result1[0]['cust_pass']) {
                                $arrResult['passwordError'] = 'Invalid      password';
                            }
                        }
                    }
                } else {
                    $arrResult['error'] = "We can't find an account with this email address.";
                }
            }
            return $arrResult;
        }

        function shop_product($keyword, $catid, $brand, $subcat, $limit = '', $offset = '')
        {
            $this->db->select('*');
            $query = $this->db->join("product_color_size_record", "products.prid = product_color_size_record.pcs_product_rand_id");
            $query = $this->db->join("categories", "categories.category_id = products.product_id");
            $query = $this->db->join("brands", "products.product_brand = brands.brand_id");
            $query = $this->db->join("colors", "colors.color_id = product_color_size_record.pcs_color_id");
            $query = $this->db->join("sizes", "sizes.size_id = product_color_size_record.pcs_size");
            if (!empty($subcat)) {
                $this->db->where('products.product_subcat', $subcat);
            }

            if (!empty($brand)) {
                $this->db->where('products.product_brand', $brand);
            }
            if (!empty($catid)) {
                $this->db->where('products.product_cat', $catid);
            }

            if (!empty($keyword)) {
                $this->db->or_like('products.product_title', $keyword, 'both');
                $this->db->or_like('products.ar_product_title', $keyword, 'both');
                $this->db->or_like('categories.category_name', $keyword, 'both');
                $this->db->or_like('categories.ar_category_name', $keyword, 'both');
                $this->db->or_like('brands.brand_name', $keyword, 'both');
                $this->db->or_like('brands.ar_brand_name', $keyword, 'both');
            }
            $query = $this->db->group_by('product_color_size_record.pcs_product_rand_id');
            if ($limit) {
                $query = $this->db->limit($limit, $offset);
            }
            $query = $this->db->get('products');
            // echo $this->db->last_query($query);
            $result = $query->result();
            return $result;
        }


        public function fetchalldata($column = '', $tblname, $where = '', $where1 = '', $join_ary = array(), $group_by = '', $order_by = '', $limit = '', $offset = '')
        {
            $this->db->select($column);
            $this->db->from($tblname);
            if ($where) {
                $this->db->where($where);
            }
            if ($where1) {
                $this->db->where($where1);
            }
            if ($group_by) {
                $this->db->group_by($group_by);
            }
            if ($order_by) {
                $this->db->order_by($order_by, 'DESC');
            }
            if (is_array($join_ary) && count($join_ary) > 0) {
                foreach ($join_ary as $ky => $vl) {
                    $this->db->join($vl['table'], $vl['condition']);
                }
            }
            if ($limit) {
                $this->db->limit($limit, $offset);
            }
            $result = $this->db->get('')->result();
            // echo $this->db->last_query();
            return $result;
        }
        // model code coded by umesh
        public function get_rows($table, $where)
        {
            $query =  $this->db->select('*')
                ->from($table)

                ->get();
            return $query->num_rows();
        }

        public function getOrders($where)
        {
            $this->db->select('*');
            $query = $this->db->join("products", "order_products.product_id = products.product_id");

            $query = $this->db->join("orders", "order_products.order_id = orders.ord_id");
            if (!empty($where)) {
                $query = $this->db->where($where);
            }
            $query = $this->db->where('orders.ord_user_id', $this->session->userdata('cid'));

            $query =  $this->db->order_by('products.product_id', 'pcs_id');

            $query = $this->db->get('order_products');
            $result = $query->result_array();
            return $result;
        }

        public function order_summary($order_id)
        {
            $this->db->select('*');
            $query = $this->db->join("products", "order_products.product_id = products.product_id");
            $query = $this->db->join("orders", "order_products.order_id = orders.ord_id");
            if (!empty($where)) {
                $query = $this->db->where($where);
            }
            $query = $this->db->where('orders.ord_id', $order_id);

            $query = $this->db->get('order_products');
            $result = $query->result_array();
            return $result;
        }

        public function get_data($table)
        {
            $query =  $this->db->select('*')
                ->from($table)
                ->order_by('id', 'desc')
                ->get();
            return $query->result();
        }
        public function get_categories()
        {
            $query = $this->db->get('product_category');
            $return = array();

            foreach ($query->result() as $category) {
                $return[$category->id] = $category;
                $return[$category->id]->subs = $this->get_sub_categories($category->id); // Get the categories sub categories
            }
            return $return;
        }

        public function get_sub_categories($category_id)
        {
            $this->db->where('category_id', $category_id);
            $query = $this->db->get('sub_category');
            return $query->result();
        }
        public function get_brand($cat_id, $sub_cat_id)
        {
            $this->db->select('b.brand_name,b.ar_brand_name,b.brand_id');
            $this->db->from('products p');
            $this->db->group_by('product_brand');
            $this->db->join('brands  b', 'p.product_brand=b.brand_id', 'left');

            if (empty($sub_cat_id) && !empty($cat_id)) {
                $this->db->where('p.product_cat', $cat_id);
            } elseif (!empty($sub_cat_id) && !empty($cat_id)) {
                $this->db->where(array('p.product_cat' => $cat_id, 'p.product_subcat' => $sub_cat_id));
            }
            $query =   $this->db->get();
            return  $query->result();
        }

        public function get_num_rows($max, $min, $cat_id, $sub_cat_id, $brand, $search)
        {
            $query = $this->db->select('*');
            $query = $this->db->from('products');
            $query = $this->db->join("product_color_size_record", "products.prid = product_color_size_record.pcs_product_rand_id");
            $query = $this->db->join("categories", "categories.category_id = products.product_cat");
            $query = $this->db->join("brands", "products.product_brand = brands.brand_id");
            $query = $this->db->join("sizes", "sizes.size_id = product_color_size_record.pcs_size");
            if (!empty($search)) {
                $query = $this->db->like('products.product_title', $search, 'after');
            }
            $query = $this->db->where('products.product_status', '1');
            $query = $this->db->group_by('product_color_size_record.pcs_product_rand_id');
            if (!empty($min) && !empty($max)) {
                $query =    $this->db->where(array('product_color_size_record.pcs_sale>=' => $min, 'product_color_size_record.pcs_sale<=' => $max));
            }
            if (empty($sub_cat_id) && !empty($cat_id)) {
                $query =    $this->db->where('products.product_cat', $cat_id);
            } elseif (!empty($sub_cat_id) && !empty($cat_id)) {
                $query = $this->db->where(array('products.product_cat' => $cat_id, 'products.product_subcat' => $sub_cat_id));
            }

            if (!empty($brand)) {
                $query = $this->db->where_in('products.product_brand', $brand);
            }
            $query =   $this->db->get();
            return  $query->num_rows();
        }

        public function filter_data($max, $min, $cat_id, $sub_cat_id, $brand, $flavour, $search)
        {
            $query = $this->db->select('*');

            $query = $this->db->where('products.product_status', '1');
            if (!empty($search)) {
                $replaced =  str_replace("'s", "", $search);
                $trimmed =  trim($replaced);
                $where = "product_title LIKE '%$trimmed%'";
                $this->db->where($where);
            }


            if (!empty($min) && !empty($max)) {
                $this->db->where(array('products.product_price_sale>=' => $min, 'products.product_price_sale<=' => $max));
            }
            if (empty($sub_cat_id) && !empty($cat_id)) {
                $this->db->where('products.product_cat', $cat_id);
            } elseif (!empty($sub_cat_id) && !empty($cat_id)) {
                $this->db->where(array('products.product_cat' => $cat_id, 'products.product_subcat' => $sub_cat_id));
            }

            if (!empty($brand)) {
                $this->db->where_in('products.product_brand', $brand);
            }
            if (!empty($flavour)) {
                $this->db->where_in('products.product_flavour', $flavour);
            }

            $query =   $this->db->get('products');
            $cid = $this->session->userdata('cid');
            $result['product_detail'] = $query->result_array();
            for ($i = 0; $i < count($result['product_detail']); $i++) {

                if (!empty($cid)) {
                    $condition = $this->get_rows('wishlist', array(
                        'wish_user_id' => $cid,
                        'wish_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['wishlist'] = 'true';
                    } else {
                        $result['product_detail'][$i]['wishlist'] = 'false';
                    }
                } else {
                    $result['product_detail'][$i]['wishlist'] = 'false';
                }

                if (empty($cid)) {
                    $session_id = $this->session->userdata('session_id');
                    $condition = $this->get_rows('cart', array(
                        'cart_session_id' => $session_id,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                } else {
                    $cid = $this->session->userdata('cid');
                    $condition = $this->get_rows('cart', array(
                        'cart_user_id' => $cid,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                }
            }
            return $result['product_detail'];
        }

        // products with pagination by umesh
        function product_get_paginated($keyword, $limit, $offset, $category_id)
        {
            $this->db->select('*');
            $query = $this->db->join("product_color_size_record", "products.prid = product_color_size_record.pcs_product_rand_id");
            $query = $this->db->join("categories", "categories.category_id = products.product_cat");
            $query = $this->db->join("brands", "products.product_brand = brands.brand_id");
            $query = $this->db->join("sizes", "sizes.size_id = product_color_size_record.pcs_size");
            if (!empty($keyword)) {
                $this->db->like('products.product_title', $keyword, 'after');
            }
            $this->db->where('products.product_status', '1');
            if (!empty($category_id)) {
                $this->db->where('products.product_cat', $category_id);
            }

            $query = $this->db->group_by('product_color_size_record.pcs_product_rand_id');
            $query =  $this->db->order_by('products.product_id', 'pcs_id');
            $query = $this->db->limit($limit, $offset);
            $query = $this->db->get('products');
            // echo $this->db->last_query($query);
            $result['product_detail'] = $query->result_array();
            for ($i = 0; $i < count($result['product_detail']); $i++) {

                if (!empty($cid)) {
                    $condition = $this->get_rows('wishlist', array(
                        'wish_user_id' => $cid,
                        'wish_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['wishlist'] = 'true';
                    } else {
                        $result['product_detail'][$i]['wishlist'] = 'false';
                    }
                } else {
                    $result['product_detail'][$i]['wishlist'] = 'false';
                }

                if (empty($cid)) {
                    $session_id = $this->session->userdata('session_id');
                    $condition = $this->get_rows('cart', array(
                        'cart_session_id' => $session_id,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                } else {
                    $cid = $this->session->userdata('cid');
                    $condition = $this->get_rows('cart', array(
                        'cart_user_id' => $cid,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                }
            }
            return $result['product_detail'];
        }

        public function test($value = '')
        {
            $query = $this->db->select('AVG(rating)')
                ->where(array('product_id' => 15))->get('review');
            $select_rat =   $query->result_array();
            return $select_rat;
        }


        // coded by umesh 
        function get_products($keyword, $limit = "3", $offset = "")
        {
            $this->db->select('*');
            if (!empty($keyword)) {
                $this->db->like('products.product_type', $keyword, 'both');
            }
            $this->db->where('products.product_status', '1');
            $this->db->limit($limit, $offset);
            $query =  $this->db->order_by('products.product_id', 'desc');

            $query = $this->db->get('products');


            if (!empty($this->session->userdata('cid'))) {
                $cid = $this->session->userdata('cid');
            }
            $result['product_detail'] = $query->result_array();
            for ($i = 0; $i < count($result['product_detail']); $i++) {


                $query = $this->db->select('AVG(rating)')
                    ->where(array('product_id' => $result['product_detail'][$i]['product_id']))->get('review');
                $select_rat = $query->result_array();
                $rating = $select_rat[0]['AVG(rating)'];

                $result['product_detail'][$i]['rating'] = $rating;

                if (!empty($cid)) {
                    $condition = $this->get_rows('wishlist', array(
                        'wish_user_id' => $cid,
                        'wish_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['wishlist'] = 'true';
                    } else {
                        $result['product_detail'][$i]['wishlist'] = 'false';
                    }
                } else {
                    $result['product_detail'][$i]['wishlist'] = 'false';
                }

                if (empty($cid)) {
                    $session_id = $this->session->userdata('session_id');
                    $condition = $this->get_rows('cart', array(
                        'cart_session_id' => $session_id,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                } else {
                    $cid = $this->session->userdata('cid');
                    $condition = $this->get_rows('cart', array(
                        'cart_user_id' => $cid,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                }
            }

            $output = array();

            $last_index = count($result['product_detail']) - 1;
            $offset = $result['product_detail']['2']['product_id'];
            $output['first_tab'] = $result['product_detail'];
            $output['second_tab'] = $this->second_tab_products($keyword, $limit, $offset = "");
            return $output;
        }

        public function product_by_keyword($keyword)
        {
            $this->db->select('*');
            if (!empty($keyword)) {
                $this->db->like('products.product_type', $keyword, 'both');
            }
            $this->db->where('products.product_status', '1');

            $query =  $this->db->order_by('products.product_id', 'desc');

            $query = $this->db->get('products');


            if (!empty($this->session->userdata('cid'))) {
                $cid = $this->session->userdata('cid');
            }
            $result['product_detail'] = $query->result_array();
            for ($i = 0; $i < count($result['product_detail']); $i++) {


                $query = $this->db->select('AVG(rating)')
                    ->where(array('product_id' => $result['product_detail'][$i]['product_id']))->get('review');
                $select_rat = $query->result_array();
                $rating = $select_rat[0]['AVG(rating)'];

                $result['product_detail'][$i]['rating'] = $rating;

                if (!empty($cid)) {
                    $condition = $this->get_rows('wishlist', array(
                        'wish_user_id' => $cid,
                        'wish_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['wishlist'] = 'true';
                    } else {
                        $result['product_detail'][$i]['wishlist'] = 'false';
                    }
                } else {
                    $result['product_detail'][$i]['wishlist'] = 'false';
                }

                if (empty($cid)) {
                    $session_id = $this->session->userdata('session_id');
                    $condition = $this->get_rows('cart', array(
                        'cart_session_id' => $session_id,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                } else {
                    $cid = $this->session->userdata('cid');
                    $condition = $this->get_rows('cart', array(
                        'cart_user_id' => $cid,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                }
            }
            return $result['product_detail'];
        }


        public function second_tab_products($keyword, $limit, $offset)
        {
            $this->db->select('*');

            if (!empty($keyword)) {
                $this->db->like('products.product_type', $keyword, 'both');
            }
            $this->db->where('products.product_status', '1');
            $this->db->limit($limit, $offset);
            $query =  $this->db->order_by('products.product_id', 'asc');
            $query = $this->db->get('products');


            if (!empty($this->session->userdata('cid'))) {
                $cid = $this->session->userdata('cid');
            }
            $result['product_detail'] = $query->result_array();
            for ($i = 0; $i < count($result['product_detail']); $i++) {


                $query = $this->db->select('AVG(rating)')
                    ->where(array('product_id' => $result['product_detail'][$i]['product_id']))->get('review');
                $select_rat = $query->result_array();
                $rating = $select_rat[0]['AVG(rating)'];

                $result['product_detail'][$i]['rating'] = $rating;

                if (!empty($cid)) {
                    $condition = $this->get_rows('wishlist', array(
                        'wish_user_id' => $cid,
                        'wish_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['wishlist'] = 'true';
                    } else {
                        $result['product_detail'][$i]['wishlist'] = 'false';
                    }
                } else {
                    $result['product_detail'][$i]['wishlist'] = 'false';
                }

                if (empty($cid)) {
                    $session_id = $this->session->userdata('session_id');
                    $condition = $this->get_rows('cart', array(
                        'cart_session_id' => $session_id,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                } else {
                    $cid = $this->session->userdata('cid');
                    $condition = $this->get_rows('cart', array(
                        'cart_user_id' => $cid,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                }
            }
            return $result['product_detail'];
        }

        function get_products_where($keyword, $category_id)
        {
            $this->db->select('*');
            $query = $this->db->join("product_color_size_record", "products.prid = product_color_size_record.pcs_product_rand_id");
            $query = $this->db->join("categories", "categories.category_id = products.product_cat");
            $query = $this->db->join("brands", "products.product_brand = brands.brand_id");
            $query = $this->db->join("sizes", "sizes.size_id = product_color_size_record.pcs_size");
            if (!empty($keyword)) {
                $this->db->like('products.product_title', $keyword, 'after');
            }
            if (!empty($category_id)) {
                $this->db->where('products.product_cat', $category_id);
            }
            $this->db->where('products.product_status', '1');


            $query = $this->db->group_by('product_color_size_record.pcs_product_rand_id');
            $query =  $this->db->order_by('products.product_id', 'pcs_id');

            $query = $this->db->get('products');
            // echo $this->db->last_query($query);
            if (!empty($this->session->userdata('cid'))) {
                $cid = $this->session->userdata('cid');
            }
            $result['product_detail'] = $query->result_array();
            for ($i = 0; $i < count($result['product_detail']); $i++) {

                if (!empty($cid)) {
                    $condition = $this->get_rows('wishlist', array(
                        'wish_user_id' => $cid,
                        'wish_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['wishlist'] = 'true';
                    } else {
                        $result['product_detail'][$i]['wishlist'] = 'false';
                    }
                } else {
                    $result['product_detail'][$i]['wishlist'] = 'false';
                }

                if (empty($cid)) {
                    $session_id = $this->session->userdata('session_id');
                    $condition = $this->get_rows('cart', array(
                        'cart_session_id' => $session_id,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                } else {
                    $cid = $this->session->userdata('cid');
                    $condition = $this->get_rows('cart', array(
                        'cart_user_id' => $cid,
                        'cart_product_random' => $result['product_detail'][$i]['prid']
                    ));
                    if ($condition) {
                        $result['product_detail'][$i]['cart'] = 'true';
                    } else {
                        $result['product_detail'][$i]['cart'] = 'false';
                    }
                }
            }

            return $result['product_detail'];
        }
        public function get_max($value = '')
        {
            $this->db->select_max('product_price_sale');
            $res1 = $this->db->get('products');
            $data = $res1->result_array();

            $data1['max'] = $data[0]['product_price_sale'];
            $this->db->select_min('product_price_sale');
            $res1 = $this->db->get('products');
            $data = $res1->result_array();

            $data1['min'] = $data[0]['product_price_sale'];
            return $data1;
        }

        public function delete_record($table, $where)
        {
            $query = $this->db->where($where)->delete($table);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    ?>
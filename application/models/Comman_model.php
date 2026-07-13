<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Comman_model extends CI_Model
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







    public function getProcesses_procedures()
    {
        $this->db->select('processes_procedures.*,audit_responsibilities.responsibility');
        $query = $this->db->from('processes_procedures');

        $query = $this->db->join("audit_responsibilities", "processes_procedures.department=audit_responsibilities.id");
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }




    public function getResponsibilitiesWithUsers()
    {
        // Fetch responsibilities along with their users from the database
        $query = $this->db->select('audit_responsibilities.responsibility, audit_responsibile_persons.name, audit_responsibile_persons.email')
            ->join('audit_responsibile_persons', 'audit_responsibilities.id = audit_responsibile_persons.department_id', 'left')
            ->get('audit_responsibilities');

        $responsibilities = array();
        foreach ($query->result() as $row) {
            $responsibility = $row->responsibility;
            if (!isset($responsibilities[$responsibility])) {
                $responsibilities[$responsibility] = array(
                    "responsibility" => $responsibility,
                    "users" => array()
                );
            }
            if (!empty($row->name)) {
                $user = array(
                    "name" => $row->name,
                    "email" => $row->email
                );
                $responsibilities[$responsibility]["users"][] = $user;
            }
        }

        // Convert to array values to reindex the array
        $responsibilities = array_values($responsibilities);

        return $responsibilities;
    }



    public function validated_pm_or_dm($project_id)
    {
        // Get the logged-in user's ID from the session
        $user_session = $this->session->userdata('admin_session');
        $user_id = $user_session['id'];

        // Ensure user_id and project_id are valid
        if (empty($user_id) || empty($project_id)) {
            return false; // Invalid user or project ID
        }

        // Query to fetch the project details
        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('project_id', $project_id);
        // $this->db->where('project_status !=', 'Closed');
        // Ensure the user is either the Project Manager or Delivery Manager
        $this->db->group_start()
            ->where('project_manager_id', $user_id)
            ->or_where('delivery_manager_id', $user_id)
            ->group_end();

        // Execute the query and get the result
        $query = $this->db->get();

        // Check if the project exists and the user has access
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false; // No matching project found or access denied
        }
    }

    public function getStateData()
    {
        $this->db->select('state.*,country.country_name');
        $query = $this->db->from('state');

        $query = $this->db->join("country", "state.country_id=country.country_id");
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }


    public function getDSRItems($type, $report_id)
    {
        $this->db->select("*");
        $query = $this->db->from('daily_sale_items');

        if ($type == 'other_revenue') {

            $query = $this->db->join("other_revenue", "other_revenue.other_revenue_id=daily_sale_items.item_id");
        } else if ($type == "restaurants") {

            $query = $this->db->join("restaurants", "restaurants.restaurants_id=daily_sale_items.item_id");
        } else if ($type == "room_analysis") {

            $query = $this->db->join("room_analysis", "room_analysis.room_analysis_id=daily_sale_items.item_id");
        }


        $query = $this->db->where('reportUid', $report_id);

        $query = $this->db->where('item_type', $type);



        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }







    public function getcityData()
    {
        $this->db->select('city.*,country.country_name,state.state_name');
        $query = $this->db->from('city');

        $query = $this->db->join("country", "city.country_id=country.country_id");
        $query = $this->db->join("state", "city.state_id=state.state_id");
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }

    public function getmembersData()
    {
        $this->db->select('*');
        $query = $this->db->from('staff_members');


        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }

    public function gethotelmembersData()
    {
        $this->db->select('*');
        $query = $this->db->from('staff_members');


        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }




    public function getHotelData()
    {
        $this->db->select('hotel_admin.*,country.country_name,state.state_name,city.city_name');
        $query = $this->db->from('hotel_admin');

        $query = $this->db->join("country", "hotel_admin.country_id=country.country_id");
        $query = $this->db->join("state", "hotel_admin.state_id=state.state_id");
        $query = $this->db->join("city", "hotel_admin.city_id=city.city_id");
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }






    function getProjectRAGprojects()
    {

        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('project_status !=', 'Closed');
        $this->db->where('project_manager_id IS NOT NULL', null, false);
        $this->db->where('project_manager_id !=', '');
        $this->db->where('project_manager_id REGEXP', '^[0-9]+$');
        $this->db->where('portifolio!=', 'Support_Div');
        $this->db->where('client_name!=', 'Indium Soft');
        $this->db->where('rag_status_dm!=', '1');

        $this->db->where('mail30thStatus!=', '1');
        $this->db->limit('100');


        // Execute the query and get the result
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function getProjectRAGprojectsForFollowUp()
    {

        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('project_status !=', 'Closed');
        $this->db->where('project_manager_id IS NOT NULL', null, false);
        $this->db->where('project_manager_id !=', '');
        $this->db->where('project_manager_id REGEXP', '^[0-9]+$');
        $this->db->where('rag_status_pm!=', '1');
        $this->db->where('portifolio!=', 'Support_Div');
        $this->db->where('client_name!=', 'Indium Soft');
        $this->db->where('mailStatusFollowUpOne!=', '1');
        $this->db->limit('100');
        // Execute the query and get the result
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }


    public function get_id_by_md5($md5_hash, $table_name, $coloum_name)
    {
        // Select project_id based on MD5 hash comparison
        $query = $this->db->select("$coloum_name")
            ->where("MD5($coloum_name)", $md5_hash) // Match MD5 hash of the project_id
            ->get("$table_name");

        if ($query->num_rows() > 0) {
            // Return the correct column project_id, not id
            return $query->row()->$coloum_name;
        } else {
            return FALSE; // No match found
        }
    }

    function getProjectRAGprojectsForFollowUpMissed()
    {

        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('project_status !=', 'Closed');
        $this->db->where('project_manager_id IS NOT NULL', null, false);
        $this->db->where('project_manager_id !=', '');
        $this->db->where('project_manager_id REGEXP', '^[0-9]+$');
        $this->db->where('rag_status_pm!=', '1');
        $this->db->where('portifolio!=', 'Support_Div');
        $this->db->where('client_name!=', 'Indium Soft');
        $this->db->where('mailStatusFollowUpOne!=', '1');
        $this->db->limit('100');

        // Execute the query and get the result
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function getProjectRAGprojectsForSecondFollowup()
    {

        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('project_status !=', 'Closed');
        $this->db->where('project_manager_id IS NOT NULL', null, false);
        $this->db->where('project_manager_id !=', '');
        $this->db->where('project_manager_id REGEXP', '^[0-9]+$');
        $this->db->where('rag_status_pm!=', '1');
        $this->db->where('portifolio!=', 'Support_Div');
        $this->db->where('client_name!=', 'Indium Soft');
        $this->db->where('mailStatusFollowUpOne!=', '1');
        $this->db->limit('100');


        // Execute the query and get the result
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }


    function getProjectRAGprojectsForThirdFollowup()
    {

        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('project_status !=', 'Closed');
        $this->db->where('project_manager_id IS NOT NULL', null, false);
        $this->db->where('project_manager_id !=', '');
        $this->db->where('project_manager_id REGEXP', '^[0-9]+$');
        $this->db->where('rag_status_pm!=', '1');
        $this->db->where('portifolio!=', 'Support_Div');
        $this->db->where('client_name!=', 'Indium Soft');
        $this->db->where('mailStatusFollowUpOne!=', '1');
        $this->db->limit('100');



        // Execute the query and get the result
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function getProjectRAGprojectsForFourthFollowup()
    {

        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('project_status !=', 'Closed');
        $this->db->where('project_manager_id IS NOT NULL', null, false);
        $this->db->where('project_manager_id !=', '');
        $this->db->where('project_manager_id REGEXP', '^[0-9]+$');
        $this->db->where('rag_status_pm!=', '1');
        $this->db->where('portifolio!=', 'Support_Div');
        $this->db->where('client_name!=', 'Indium Soft');
        $this->db->where('mailStatusFollowUpOne!=', '1');
        $this->db->limit('100');



        // Execute the query and get the result
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }




    function UpdateRecord($TableName, $Data, $WhereData = NULL)
    {
        if ($WhereData != NULL) {
            $this->db->where($WhereData);
        }
        $Result = $this->db->update($TableName, $Data);
        return $Result;
    }

    function UpdateRecords($TableName, $Data, $WhereData = NULL)
    {
        if ($WhereData != NULL) {
            $this->db->where($WhereData);
        }
        $Result = $this->db->set($TableName, $Data);
        return $Result;
    }


    /*=====Get Single Record =====*/
    function get_single_record($table, $where)
    {
        $query = $this->db->get_where($table, $where);
        return $query->row();
    }

    function Deletedata($table, $where)
    {
        return $this->db->delete($table, $where);
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


    public function select_data($id)
    {
        $this->db->select('risk_tracker.*,projects.project_name');
        $query = $this->db->from('risk_tracker');
        $query = $this->db->where('risk_tracker.project_id', $id);

        $query = $this->db->join("projects", "projects.project_id=risk_tracker.project_id");
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }

    //  public function select_audit_data($id){
    // $this->db->select('audit_tracker.*,projects.project_name');
    // $query=$this->db->from('audit_tracker');
    //  $query=$this->db->where('audit_tracker.project_id',$id);

    // $query=$this->db->join("projects","projects.project_id=audit_tracker.project_id"); 
    // $query=$this->db->get();
    // $data=$query->result_array();
    // return $data;
    //           }



    public function getdata_array($table, $where)
    {
        $this->db->select('*');
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get($table)->result_array();
        return $query;
    }

    public function getdata_array_project_resources($table, $where, $or_where)
    {
        $this->db->select('*');
        if (!empty($where)) {
            $this->db->where($where);
            $this->db->or_where($or_where);
        }
        $query = $this->db->get($table)->result_array();
        return $query;
    }


    public function get_responsbile_persons()
    {
        $this->db->select('audit_responsibile_persons.*, audit_responsibilities.responsibility');
        $this->db->from('audit_responsibile_persons');
        $this->db->join('audit_responsibilities', 'audit_responsibilities.id = audit_responsibile_persons.department_id');
        $query = $this->db->get()->result();
        return $query;
    }


    public function get_reporting_managers()
    {
        $this->db->select('department_managers.*, audit_responsibilities.responsibility');
        $this->db->from('department_managers');
        $this->db->join('audit_responsibilities', 'audit_responsibilities.id = department_managers.department_id');
        $query = $this->db->get()->result();
        return $query;
    }



    public function getdata_array_order_by($table, $where, $sequence)
    {
        $this->db->select('*');
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->order_by('sequence', "ASC");
        $query = $this->db->get($table)->result_array();
        return $query;
    }



    public function job_and_department_data()
    {
        $this->db->select('jobs.*,job_department.department_name');
        $query = $this->db->from('jobs');
        $query = $this->db->where('jobs.status', 1);
        $query = $this->db->join("job_department", "job_department.id =jobs.department_id");
        $query = $this->db->order_by('id', "ASC");
        $query = $this->db->get();
        $data = $query->result();
        echo  $data;
        return $data;
    }

    public function counsellors_demoBookingData()
    {
        $this->db->select('*');
        $query = $this->db->from('cons_demo_management');
        $query = $this->db->join("counsellor", "counsellor.id =cons_demo_management.cons_id");
        $query = $this->db->order_by('id', "ASC");
        $query = $this->db->get();
        $data = $query->result();
        echo  $data;
        return $data;
    }

    public function instructors_demoBookingData()
    {
        $this->db->select('*');
        $query = $this->db->from('demo_management');
        $query = $this->db->join("instructor", "instructor.id =demo_management.ins_id");
        $query = $this->db->order_by('id', "ASC");
        $query = $this->db->get();
        $data = $query->result();
        echo  $data;
        return $data;
    }

    public function instructors_incentive_data()
    {
        $this->db->select('instructors_incentive.*,instructor.f_name');
        $query = $this->db->from('instructors_incentive');
        $query = $this->db->where('instructors_incentive.status', 1);
        $query = $this->db->join("instructor", "instructor.id =instructors_incentive.instructor_id");
        $query = $this->db->order_by('id', "ASC");
        $query = $this->db->get();
        $data = $query->result();
        echo  $data;
        return $data;
    }


    public function resource_stage()
    {
        $this->db->select('*');
        $query = $this->db->from('resources');
        $query = $this->db->where('resources.status', 1);

        $query = $this->db->order_by('id', "ASC");
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
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

    public function getAllData($table, $where, $orderBy = '')
    {
        $this->db->select('*');
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($orderBy)) {
            $this->db->from($table);
            $this->db->order_by($orderBy, "ASC");
            $query = $this->db->get();
            return $query->result();
        } else {
            $query = $this->db->get($table)->result();
            return $query;
        }
    }



    public function get_stage_for_vertical($vertical)
    {
        $this->db->select('*');
        $this->db->where('vertical_id', $vertical);
        $this->db->from('stage_vertical_mapping');
        $this->db->join('stages', 'stage_vertical_mapping.stage_id=stages.id');
        $this->db->order_by('squence', "ASC");
        $query = $this->db->get();
        return $query->result();
    }







    public function get_count($table, $where, $orderBy = '')
    {
        $this->db->select('*');
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($orderBy)) {
            $this->db->from($table);
            $this->db->order_by($orderBy, "ASC");
            $query = $this->db->get();
            return $query->result();
        } else {
            $query = $this->db->get($table)->num_rows();
            return $query;
        }
    }


    public function get_related_programs($table, $not_equal, $course_id, $orderBy = '')
    {
        $this->db->select('*');

        $this->db->where('id!=', $not_equal);

        $this->db->where('course_id', $course_id);

        if (!empty($orderBy)) {
            $this->db->from($table);
            $this->db->order_by($orderBy, "DESC");
            $query = $this->db->get();
            return $query->result();
        } else {
            $query = $this->db->get($table)->result_array();
            return $query;
        }
    }




    function updateMedia($image, $folder, $height = 768, $width = 1024, $path = FALSE)
    {
        $this->makedirs($folder);
        $realpath = $path ? '../uploads/' : 'uploads/';
        $allowed_types = "jpg|png|jpeg|mp4|avi|mov";
        $img_name = $this->authToken(); //generate random string for image name
        $img_sizes_arr = $this->image_sizes($folder); //predefined sizes in model
        //print_r($img_sizes_arr); die;
        //We will set min height and width according to thumbnail size
        $min_width = $img_sizes_arr['thumbnail']['width'];
        $min_height = $img_sizes_arr['thumbnail']['height'];
        $config = array(
            'upload_path' => $realpath . $folder,
            'allowed_types' => $allowed_types,
            //'max_size' => "10240", // File size limitation, initially w'll set to 10mb (Can be changed)
            //'max_height' => "4000", // max height in px
            //'max_width' => "4000", // max width in px
            //'min_width' => $min_width, // min width in px
            //'min_height' => $min_height, // min height in px
            'file_name' => $img_name,
            'overwrite' => FALSE,
            'remove_spaces' => TRUE,
            'quality' => '100%',
        );
        $this->load->library('upload'); //upload library
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($image)) {
            $error = array('error' => $this->upload->display_errors());
            return $error; //error in upload

        }
        //image uploaded successfully - We will now resize and crop this image
        $image_data = $this->upload->data(); //get uploaded image data
        $this->load->library('image_lib'); //Load image manipulation library
        $thumb_img = '';
        foreach ($img_sizes_arr as $k => $v) {
            // create resize sub-folder
            $sub_folder = $folder . $v['folder'];
            $this->makedirs($sub_folder);
            $real_path = realpath(FCPATH . $realpath . $folder);
            $resize['image_library'] = 'gd2';
            $resize['source_image'] = $image_data['full_path'];
            $resize['new_image'] = $real_path . $v['folder'] . '/' . $image_data['file_name'];
            $resize['maintain_ratio'] = TRUE; //maintain original image ratio
            $resize['width'] = $v['width'];
            $resize['height'] = $v['height'];
            $resize['quality'] = '100%';
            // We need to know whether to use width or height edge as the hard-value.
            // After the original image has been resized, either the original image width’s edge or
            // the height’s edge will be the same as the container
            $dim = (intval($image_data["image_width"]) / intval($image_data["image_height"])) - ($v['width'] / $v['height']);
            $resize['master_dim'] = ($dim > 0) ? "height" : "width";
            $this->image_lib->initialize($resize);
            $is_resize = $this->image_lib->resize(); //create resized copies
            //image resizing maintaining it's aspect ratio is done. Now we will start cropping the resized image
            $source_img = $real_path . $v['folder'] . '/' . $image_data['file_name'];
            if ($is_resize && file_exists($source_img)) {
                $source_image_arr = getimagesize($source_img);
                $source_image_width = $source_image_arr[0];
                $source_image_height = $source_image_arr[1];
                $source_ratio = $source_image_width / $source_image_height;
                $new_ratio = $v['width'] / $v['height'];
                if ($source_ratio != $new_ratio) {
                    //image cropping config
                    $crop_config['image_library'] = 'gd2';
                    $crop_config['source_image'] = $source_img;
                    $crop_config['new_image'] = $source_img;
                    $crop_config['quality'] = "100%";
                    //$crop_config['maintain_ratio'] = FALSE;
                    $crop_config['maintain_ratio'] = TRUE;
                    $crop_config['width'] = $v['width'];
                    $crop_config['height'] = $v['height'];
                    if ($new_ratio > $source_ratio || (($new_ratio == 1) && ($source_ratio < 1))) {
                        $crop_config['y_axis'] = round(($source_image_width - $crop_config['width']) / 2);
                        $crop_config['x_axis'] = 0;
                    } else {
                        $crop_config['x_axis'] = round(($source_image_height - $crop_config['height']) / 2);
                        $crop_config['y_axis'] = 0;
                    }
                    //$crop_config['x_axis'] = 0;
                    //$crop_config['y_axis'] = 0;
                    $this->image_lib->initialize($crop_config);
                    $this->image_lib->crop();
                    $this->image_lib->clear();
                }
            }
        }
        if (empty($thumb_img)) $thumb_img = $image_data['file_name'];
        return $thumb_img;
    } // End Function

    function updateMediaWithoutSubFolder($image, $folder, $height = 768, $width = 1024, $path = FALSE)
    {

        $this->makedirs($folder);
        $realpath = $path ? '../uploads/' : 'uploads/';
        $allowed_types = "*";
        $img_name = $this->authToken(); //generate random string for image name
        $img_sizes_arr = $this->image_sizes($folder); //predefined sizes in model
        $config = array(
            'upload_path' => $realpath . $folder,
            'allowed_types' => $allowed_types,
            'file_name' => $img_name,
            'overwrite' => FALSE,
            'remove_spaces' => TRUE,
            'quality' => '100%',
        );
        $this->load->library('upload'); //upload library
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($image)) {
            $error = array('error' => $this->upload->display_errors());
            return $error; //error in upload

        }
        //image uploaded successfully - We will now resize and crop this image
        $image_data = $this->upload->data(); //get uploaded image data

        if (empty($thumb_img)) $thumb_img = $image_data['file_name'];
        return $thumb_img;

        /*stop for now no need to resize*/
        die();
    } // End Function


    function authToken()
    {
        return 'file_' . strtoupper(md5(base64_encode(rand()))); //.'_'.time();

    }
    function makedirs($folder = '', $mode = DIR_WRITE_MODE, $defaultFolder = 'uploads/')
    {
        if (!@is_dir(FCPATH . $defaultFolder)) {
            mkdir(FCPATH . $defaultFolder, $mode);
        }
        if (!empty($folder)) {
            if (!@is_dir(FCPATH . $defaultFolder . '/' . $folder)) {
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode, true);
            }
        }
    } //End Function
    function image_sizes($folder)
    {
        $img_sizes = array();
        switch ($folder) {
            case 'admin_blog_img':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
                break;
            case 'admin_programs_img':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
                break;
            case 'admin_profile_img':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
                break;
            case 'instructor_profile_img':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
                break;
            case 'counsellor_profile_img':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
                break;
                // case 'student_profile':
                //     // $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                //     // $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
                // break;
        }
        return $img_sizes;
    }
    function uploadPDF($profile_image, $folder)
    {
        $this->makedirs($folder);
        $config = array(
            'upload_path' => FCPATH . 'uploads/' . $folder,
            'allowed_types' => "*",
            'overwrite' => false,
            'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            'encrypt_name' => TRUE,
            'remove_spaces' => TRUE
        );
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($profile_image)) {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        } else {
            $pdf = $this->upload->data(); //upload the image
            return $pdf['file_name'];
        }
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
        $query = $this->db->join("product_color_size_record", "products.prid = product_color_size_record.pcs_product_rand_id");
        $query = $this->db->join("categories", "categories.category_id = products.product_cat");
        $query = $this->db->join("sizes", "sizes.size_id = product_color_size_record.pcs_size");
        $query = $this->db->join("brands", "products.product_brand = brands.brand_id");


        $this->db->where('products.product_status', '1');
        $this->db->where('products.product_id != ', $pid);
        $this->db->where('products.product_cat', $catid);
        $query = $this->db->group_by('product_color_size_record.pcs_product_rand_id');
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



    // products with pagination by umesh
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
        // $query =$this->db->limit($limit,$offset);
        $query = $this->db->get('products');

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


    public function delete_record($table, $where)
    {
        $query = $this->db->where($where)->delete($table);
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function get_column_rec($table, $where, $column)
    {

        $this->db->select($column);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get($table)->row();
        return $query;
    }



    public function get_column_rec_array($table, $where, $column)
    {
        // print_r($where);
        // die();
        $this->db->select($column);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get($table)->result();
        return $query;
    }


    public function getDataByLimit($table, $where, $orderAs = '', $limit = '')
    {

        if (!empty($where)) {
            $this->db->where($where);
        }
        $responseToPass = $this->db->order_by('id', $orderAs)
            ->limit($limit)
            ->get($table)
            ->result();

        return $responseToPass;
    }


    public function getDataByLimitArray($table, $where, $orderAs = '', $limit = '')
    {

        if (!empty($where)) {
            $this->db->where($where);
        }

        if (!empty($limit)) {
            $this->db->limit($limit);
        }
        $responseToPass = $this->db->order_by('id', $orderAs)->get($table)->result_array();

        return $responseToPass;
    }

    function get_random_data($table, $no)
    {
        $this->db->order_by('id', 'RANDOM');
        // or
        // $this->db->order_by('rand()');
        $this->db->limit($no);
        $this->db->where(['status' => 1]);
        $query = $this->db->get($table);
        return $query->result_array();
    }



    /*check exist*/
    public function checkExistInDb($table, $where, $column)
    {

        $this->db->select($column);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get($table)->row();
        return $query;
    }

    /*get last record of table*/
    public function get_last_record_data($table, $column)
    {

        $row = $this->db->select($column)->limit(1)->order_by('id', "DESC")->get($table)->row();
        echo $row->id; //it will provide latest or last record id.

    }


    /*get Student unique id*/
    public function generate_student_id()
    {

        $row = $this->db->select("*")->limit(1)->order_by('id', "DESC")->get("student")->row();
        return "FB-000" . $row->id; //it will provide latest or last record id.

    }



    function searchByParam($table, $param)
    {

        if (empty($param))
            return array();

        $result = $this->db->like('student_id', $param)
            ->get($table);

        return $result->result();
    }


    public function get_total($table, $coloum)
    {

        $query = $this->db->select("SUM($coloum) as total")->get($table);
        $total =   $query->result_array();
        return $total[0]['total'];
    }


    // ================get change stage=====================
    public function get_change_stage($stage_id, $vertical)
    {
        $this->db->select('*');
        $query = $this->db->join("template_vertical_stage_mapping", "template_vertical_stage_mapping.template_id=resources.id");

        $query = $this->db->where('template_vertical_stage_mapping.stage_id', $stage_id);
        $query = $this->db->where('template_vertical_stage_mapping.vertical_id', $vertical);

        $query = $this->db->get('resources');
        $data = $query->result_array();
        // echo "<pre>";
        // print_r($data);
        // die();
        return $data;
    }


    //  public function project_folder(){
    //  $this->db->select('project_folder_file.*,projects.project_name,project_folder.	project_folder_name');
    //  $query=$this->db->from('project_folder_file');
    //  $query=$this->db->where('project_folder_file');
    //  $query=$this->db->join("projects","projects.id =project_folder_file.	project_id"); 
    //   $query=$this->db->join("project_folder","project_folder.id =project_folder_file.project_folder_id");

    // $query=$this->db->order_by('id', "ASC");
    // $query=$this->db->get();
    // $data=$query->result();
    // return $data;
    //           }









}

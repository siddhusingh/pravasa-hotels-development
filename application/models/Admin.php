<?php
class Admin extends CI_Model
{
    
    /*=====Insert Record =====*/
    
    function insert_data($tbl,$post_data)
    {
        $this->db->insert($tbl,$post_data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    
    /*=====Get Single Record =====*/
    function get_single_record($table,$where)
    {
        $query = $this->db->get_where($table,$where);
        return $query->row();
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
    
    /*=====Update Record =====*/
    function update_records($tbl,$data,$where)
    {
        $this->db->where($where);
        if($this->db->update($tbl,$data))
        {
            return TRUE;
        }
    }
    
    function delete_record($tbl,$where)
    {
        if($this->db->delete($tbl,$where))
        {
            return TRUE;
        }
    }
    
    public function updateMedia($image, $folder,$file_prefix, $height = 768, $width = 1024, $path = FALSE)
    {
        $this->makedirs($folder);
        $realpath = $path ? '../uploads/' : 'uploads/';
        $allowed_types = "*";
        $img_name = $this->authToken($file_prefix);
        $img_sizes_arr = $this->image_sizes($folder); 
        $min_width = $img_sizes_arr['thumbnail']['width'];
        $min_height = $img_sizes_arr['thumbnail']['height'];
        $config = array('upload_path' => $realpath . $folder, 'allowed_types' => $allowed_types,'file_name' => $img_name, 'overwrite' => FALSE, 'remove_spaces' => TRUE, 'quality' => '100%',);
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($image)) {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        }
        $image_data = $this->upload->data();
        $this->load->library('image_lib');
        $thumb_img = '';
        foreach ($img_sizes_arr as $k => $v) {
            $sub_folder = $folder . $v['folder'];
            $this->makedirs($sub_folder);
            $real_path = realpath(FCPATH . $realpath . $folder);
            $resize['image_library'] = 'gd2';
            $resize['source_image'] = $image_data['full_path'];
            $resize['new_image'] = $real_path . $v['folder'] . '/' . $image_data['file_name'];
            $resize['maintain_ratio'] = TRUE; 
            $resize['width'] = $v['width'];
            $resize['height'] = $v['height'];
            $resize['quality'] = '100%';
            $dim = (intval($image_data["image_width"]) / intval($image_data["image_height"])) - ($v['width'] / $v['height']);
            $resize['master_dim'] = ($dim > 0) ? "height" : "width";
            $this->image_lib->initialize($resize);
            $is_resize = $this->image_lib->resize();
            $source_img = $real_path . $v['folder'] . '/' . $image_data['file_name'];
            if ($is_resize && file_exists($source_img)) {
                $source_image_arr = getimagesize($source_img);
                $source_image_width = $source_image_arr[0];
                $source_image_height = $source_image_arr[1];
                $source_ratio = $source_image_width / $source_image_height;
                $new_ratio = $v['width'] / $v['height'];
                if ($source_ratio != $new_ratio) {
                    $crop_config['image_library'] = 'gd2';
                    $crop_config['source_image'] = $source_img;
                    $crop_config['new_image'] = $source_img;
                    $crop_config['quality'] = "100%";
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
                    $this->image_lib->initialize($crop_config);
                    $this->image_lib->crop();
                    $this->image_lib->clear();
                }
            }
        }
        if (empty($thumb_img)) $thumb_img = $image_data['file_name'];
        return $thumb_img;
    }

    public function makedirs($folder = '', $mode = DIR_WRITE_MODE, $defaultFolder = 'uploads/') 
    {
        if (!@is_dir(FCPATH . $defaultFolder)) {
            mkdir(FCPATH . $defaultFolder, $mode);
        }
        if (!empty($folder)) {
            if (!@is_dir(FCPATH . $defaultFolder . '/' . $folder)) {
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode, true);
            }
        }
    } 

    public function authToken($file_prefix) 
    {
        return $file_prefix.'_'.strtoupper(md5(base64_encode(rand())));
    }

    public function image_sizes($folder) 
    {
        $img_sizes = array();
        switch ($folder) {
            case 'admin':
                $img_sizes['thumbnail'] = array('width' => 50, 'height' => 50, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
            case 'categories':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
            case 'subcategory':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
            case 'banner':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
            case 'posts':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
            case 'brands':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
            case 'fcat':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
            case 'gallery':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
            case 'NoticesDoc':
                $img_sizes['thumbnail'] = array('width' => 100, 'height' => 100, 'folder' => '/thumb');
                $img_sizes['medium'] = array('width' => 250, 'height' => 250, 'folder' => '/medium');
            break;
        }
        return $img_sizes;
    }
    
    public function get_in_report(){
        $this->db->select('t1.id,t1.booking_date,t1.created_on,t2.user_name,t3.bus_name,t4.city_name,t5.time as stime,t6.pass_name,t6.x_in,t6.x_out,t7.destination_name');
        $this->db->from('book_in_bookings as t1');
        $this->db->join('users as t2','t1.user_id = t2.user_id', 'left');
        $this->db->join('buses as t3','t1.bus_id = t3.id', 'left');
        $this->db->join('cities as t4','t1.location_id = t4.id', 'left');
        $this->db->join('time_slots as t5','t1.time_id = t5.id', 'left');
        $this->db->join('passes as t6','t1.pass_id = t6.id', 'left');
        $this->db->join('book_in_destinations as t7','t1.destination_id = t7.id', 'left');
        $this->db->order_by('t1.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_out_report(){
        $this->db->select('t1.id,t1.booking_date,t1.created_on,t2.user_name,t4.city_name,t5.time,t6.pass_name,t7.destination_name');
        $this->db->from('book_out_booking as t1');
        $this->db->join('users as t2','t1.user_id = t2.user_id', 'left');
        $this->db->join('cities as t4','t1.location_id = t4.id', 'left');
        $this->db->join('time_slots as t5','t1.time_id = t5.id', 'left');
        $this->db->join('passes as t6','t1.pass_id = t6.id', 'left');
        $this->db->join('book_in_destinations as t7','t1.destination_id = t7.id', 'left');
        $this->db->order_by('t1.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_bookin_report($where){
        $this->db->select('t1.id,t1.booking_date,t1.created_on,t2.user_name,t3.bus_name,t4.city_name,t5.time as stime,t6.pass_name,t6.x_in,t6.x_out,t7.destination_name');
        $this->db->from('book_in_bookings as t1');
        $this->db->join('users as t2','t1.user_id = t2.user_id', 'left');
        $this->db->join('buses as t3','t1.bus_id = t3.id', 'left');
        $this->db->join('cities as t4','t1.location_id = t4.id', 'left');
        $this->db->join('time_slots as t5','t1.time_id = t5.id', 'left');
        $this->db->join('passes as t6','t1.pass_id = t6.id', 'left');
        $this->db->join('book_in_destinations as t7','t1.destination_id = t7.id', 'left');
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->order_by('t1.id', 'DESC');
        $query = $this->db->get();
        return $query->row();
    }
    
    public function get_bookout_report($where){
        $this->db->select('t1.id,t1.booking_date,t1.created_on,t2.user_name,t4.city_name,t5.time as stime,t6.pass_name,t6.x_in,t6.x_out,t7.destination_name');
        $this->db->from('book_out_booking as t1');
        $this->db->join('users as t2','t1.user_id = t2.user_id', 'left');
        $this->db->join('cities as t4','t1.location_id = t4.id', 'left');
        $this->db->join('time_slots as t5','t1.time_id = t5.id', 'left');
        $this->db->join('passes as t6','t1.pass_id = t6.id', 'left');
        $this->db->join('book_in_destinations as t7','t1.destination_id = t7.id', 'left');
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->order_by('t1.id', 'DESC');
        $query = $this->db->get();
        return $query->row();
    }
    
    public function get_all_payment(){
        $this->db->select('t1.*,t2.user_name,t3.pass_name,t3.pass_type');
        $this->db->from('passes_payments as t1');
        $this->db->join('users as t2','t1.user_id = t2.user_id', 'left');
        $this->db->join('passes as t3','t1.pass_id = t3.id', 'left');
        $this->db->order_by('t1.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
/*========Main Class Ending========*/    
}
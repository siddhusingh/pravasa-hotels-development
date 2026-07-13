<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Facebookform_model extends CI_Model
{

    private $table = 'facebook_forms';

    public function getFormsByDepartment($department_id)
    {
        $this->db->where('department_id', $department_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('facebook_forms')->result();
    }

    public function getFormById($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insertForm($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function updateForm($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function deleteForm($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}

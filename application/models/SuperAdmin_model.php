<?php
class SuperAdmin_model extends CI_Model
{

    public function getAll()
    {
        return $this->db->where('user_role', 2)->order_by('id', 'DESC')->get('super_admin')->result();
    }

    public function insert($data)
    {
        return $this->db->insert('super_admin', $data);
    }

    public function getById($id)
    {
        return $this->db->where('id', $id)->get('super_admin')->row_array();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('super_admin', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('super_admin');
    }
}

<?php
class SuperAdmin_model extends CI_Model
{

    public function getAll()
    {
        return $this->db
            ->where('user_role', 2)
            ->where('is_deleted', 0)
            ->order_by('id', 'DESC')
            ->get('super_admin')
            ->result();
    }

    public function insert($data)
    {
        return $this->db->insert('super_admin', $data);
    }

    public function getById($id)
    {
        return $this->db
            ->where('id', $id)
            ->where('user_role', 2)
            ->where('is_deleted', 0)
            ->get('super_admin')
            ->row_array();
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->where('user_role', 2)
            ->where('is_deleted', 0)
            ->update('super_admin', $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', $id)
            ->where('user_role', 2)
            ->where('is_deleted', 0)
            ->update('super_admin', [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }
}

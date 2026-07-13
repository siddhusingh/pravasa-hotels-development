<?php

class Settings_model extends CI_Model
{
    private $table = 'software_settings';

    public function get_settings()
    {
        return $this->db->get($this->table)->row();
    }

    public function update_settings($data)
    {
        if ($this->db->count_all($this->table) == 0) {
            $this->db->insert($this->table, $data);
        } else {
            $this->db->update($this->table, $data, ['id' => 1]);
        }
    }
}

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Common extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function insert_data($tbl, $data) {
        if ($this->db->insert($tbl, $data)) {
            return TRUE;
        }
    }

    function insert_batch_data($tbl, $data) {
        if ($this->db->insert_batch($tbl, $data)) {
            return TRUE;
        }
    }

    function insert_and_return_id($tbl, $data) {
        $this->db->insert($tbl, $data);
        return $this->db->insert_id();
    }

    function add_new_customer($data) {
        $this->db->insert('customers', $data);
        return $this->db->insert_id();
    }
    
    function get_single_record($table, $where) {
        $query = $this->db->get_where($table, $where);
        return $query->row();
    }

    function get_last_record($table, $where) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    function get_all_where($table, $where, $oderBy) {
        $this->db->select('*');
        $this->db->from($table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($oderBy)) {
            $this->db->order_by($oderBy, 'DESC');
        }
        $query = $this->db->get();
        return $query->result();
    }

    function update_records($tbl, $data, $where) {
        $this->db->where($where);
        if ($this->db->update($tbl, $data)) {
            return TRUE;
        }
    }

    function delete_record($tbl, $where) {
        if ($this->db->delete($tbl, $where)) {
            return TRUE;
        }
    }

    function select_columns($table,$columns, $where, $oderBy)
    {
        $this->db->select($columns);
        $this->db->from($table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($oderBy)) {
            $this->db->order_by($oderBy, 'DESC');
        }

        $query = $this->db->get();
        return $query->result();
    }

    function select_columns_row($table,$columns, $where)
    {
        $this->db->select($columns);
        $this->db->from($table);

        if (!empty($where)) {
            $this->db->where($where);
        }

        $query = $this->db->get();
        return $query->row();
    }

    function select_columns_with_sort($table,$columns, $where, $oderBy,$ordtype)
    {
        $this->db->select($columns);
        $this->db->from($table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($oderBy)) {
            $this->db->order_by($oderBy,$ordtype);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function select_columns_single($table,$columns, $where)
    {
        $this->db->select($columns);
        $this->db->from($table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->row();
    }

    function select_where_in_array($table, $col, $where, $oderBy)
    {
        $this->db->select('*');
        $this->db->from($table);
        if (!empty($where)) {
            $this->db->where_in($col, $where);
        }
        if (!empty($oderBy)) {
            $this->db->order_by($oderBy, 'DESC');
        }
        $query = $this->db->get();
        return $query->result();
    }

    


























    
    /*Main Class Ended*/
}

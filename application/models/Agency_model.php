<?php
class Agency_model extends CI_Model
{

    public function get_all_agencies()
    {
        return $this->db->get('agencies')->result();
    }

    public function insert_agency($data)
    {
        $this->db->insert('agencies', $data);
        return $this->db->insert_id();
    }

    public function get_agency($id)
    {
        return $this->db->get_where('agencies', ['id' => $id])->row();
    }

    public function update_agency($id, $data)
    {
        return $this->db->where('id', $id)->update('agencies', $data);
    }

    public function delete_agency($id)
    {
        $deleted = $this->db->where('id', $id)->delete('agencies');
        $this->db->where('agency_id', $id)->delete('agency_property_mapping');
        return $deleted;
    }

    public function save_property_mapping($agency_id, $property_ids)
    {
        if (empty($property_ids)) {
            return;
        }

        foreach ($property_ids as $pid) {
            $this->db->insert('agency_property_mapping', [
                'agency_id' => $agency_id,
                'property_id' => $pid
            ]);
        }
    }

    public function get_agency_properties($agency_id)
    {
        $this->db->select('property_id');
        $this->db->where('agency_id', $agency_id);
        $query = $this->db->get('agency_property_mapping');
        return array_column($query->result_array(), 'property_id');
    }

    public function update_property_mapping($agency_id, $property_ids)
    {
        $this->db->where('agency_id', $agency_id)->delete('agency_property_mapping');
        $this->save_property_mapping($agency_id, $property_ids);
    }


    public function get_property_names_by_ids($ids = [])
    {

        $this->db->select('hotel_name');
        $this->db->where_in('hotel_id', $ids);
        $query = $this->db->get('hotel_admin');

        return array_column($query->result_array(), 'hotel_name');
    }


    public function get_agency_property_ids($agency_id)
    {
        $result = $this->db->select('property_id')
            ->from('agency_property_mapping')
            ->where('agency_id', $agency_id)
            ->get()
            ->result_array();

        return array_column($result, 'property_id'); // returns [1,2,3]
    }
}

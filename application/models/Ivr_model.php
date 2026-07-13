<?php
class Ivr_model extends CI_Model
{

    public function insert_log($data)
    {
        $this->db->insert('ivr_call_logs', $data);
        return $this->db->insert_id();
    }

    public function check_exists($ivr_id)
    {
        return $this->db->get_where('ivr_call_logs', [
            'ivr_id' => $ivr_id
        ])->row();
    }

    // OPTIONAL: Direct Lead Creation
    public function create_lead($ivrData)
    {
        $lead = [
            'phone_number' => $ivrData['clid'],
            'lead_source'  => 'IVR',
            'created_at'   => date('Y-m-d H:i:s')
        ];

        $this->db->insert('leads', $lead);
    }
}

<?php

class Airtel_config_model extends CI_Model
{
    private $table = 'airtel_call_config';

    public function get_config()
    {
        if (!$this->db->table_exists($this->table)) {
            return null;
        }

        return $this->db
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function save_config(array $data)
    {
        if (!$this->db->table_exists($this->table)) {
            return false;
        }

        $config = $this->get_config();

        if ($config) {
            return $this->db->where('id', $config->id)->update($this->table, $data);
        }

        return $this->db->insert($this->table, $data);
    }

    public function get_runtime_config()
    {
        $runtime_config = [
            'api_url' => '',
            'api_key' => '',
            'caller_id' => '',
            'customer_id' => '',
            'call_flow_id' => '',
            'notify_url' => '',
            'is_ready' => false
        ];

        $config = $this->get_config();
        if (!$config) {
            return (object) $runtime_config;
        }

        $this->load->library('encryption');
        $api_key = $this->encryption->decrypt((string) $config->api_key_encrypted);

        $runtime_config['api_url'] = trim((string) $config->api_url);
        $runtime_config['api_key'] = $api_key === false ? '' : (string) $api_key;
        $runtime_config['caller_id'] = trim((string) $config->caller_id);
        $runtime_config['customer_id'] = trim((string) $config->customer_id);
        $runtime_config['call_flow_id'] = trim((string) $config->call_flow_id);
        $runtime_config['notify_url'] = trim((string) $config->notify_url);
        $runtime_config['is_ready'] = !in_array('', [
            $runtime_config['api_url'],
            $runtime_config['api_key'],
            $runtime_config['caller_id'],
            $runtime_config['customer_id'],
            $runtime_config['call_flow_id'],
            $runtime_config['notify_url']
        ], true);

        return (object) $runtime_config;
    }
}

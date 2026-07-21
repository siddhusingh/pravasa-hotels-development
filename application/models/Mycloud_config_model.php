<?php

class Mycloud_config_model extends CI_Model
{
    private $table = 'pms_configurations';
    private $provider_code = 'mycloud';
    private $runtime_config;

    public function get_config()
    {
        if (!$this->db->table_exists($this->table)) {
            return null;
        }

        return $this->db
            ->where('provider_code', $this->provider_code)
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function save_config(array $data)
    {
        if (!$this->db->table_exists($this->table)) {
            return false;
        }

        $data['provider_code'] = $this->provider_code;
        $config = $this->get_config();

        if ($config) {
            $saved = $this->db->where('id', $config->id)->update($this->table, $data);
        } else {
            $saved = $this->db->insert($this->table, $data);
        }

        if ($saved) {
            $this->runtime_config = null;
        }

        return $saved;
    }

    public function get_runtime_config()
    {
        if ($this->runtime_config !== null) {
            return $this->runtime_config;
        }

        $runtime_config = [
            'api_url' => '',
            'auth_code' => '',
            'client_id' => '',
            'chain_code' => '',
            'is_ready' => false
        ];

        $config = $this->get_config();
        if (!$config) {
            $this->runtime_config = (object) $runtime_config;
            return $this->runtime_config;
        }

        $this->load->library('encryption');
        $auth_code = $this->encryption->decrypt((string) $config->auth_code_encrypted);

        $runtime_config['api_url'] = rtrim(trim((string) $config->api_url), '/');
        $runtime_config['auth_code'] = $auth_code === false ? '' : trim((string) $auth_code);
        $runtime_config['client_id'] = trim((string) $config->client_id);
        $runtime_config['chain_code'] = trim((string) $config->chain_code);
        $runtime_config['is_ready'] = !in_array('', [
            $runtime_config['api_url'],
            $runtime_config['auth_code'],
            $runtime_config['client_id'],
            $runtime_config['chain_code']
        ], true);

        $this->runtime_config = (object) $runtime_config;
        return $this->runtime_config;
    }

    public function get_endpoint($operation)
    {
        $config = $this->get_runtime_config();
        return $config->api_url === '' ? '' : $config->api_url . '/' . ltrim($operation, '/');
    }
}

<?php


class Software_settings extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }

    public function index()

    {
        $this->load->model('Settings_model');
        $this->load->model('Airtel_config_model');
        $this->load->model('Mycloud_config_model');
        $data['settings'] = $this->Settings_model->get_settings();
        $data['smtp_has_password'] = !empty($data['settings']->smtp_pass);
        $data['airtel_config'] = $this->Airtel_config_model->get_config();
        $data['airtel_has_api_key'] = !empty($data['airtel_config']->api_key_encrypted);
        $data['mycloud_config'] = $this->Mycloud_config_model->get_config();
        $data['mycloud_has_auth_code'] = !empty($data['mycloud_config']->auth_code_encrypted);
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/software_settings', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function update_basic()
    {
        if ($this->input->method(true) !== 'POST') {
            $this->output->set_status_header(405);
            echo json_encode([
                'status' => false,
                'message' => 'Method not allowed',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $site_title = trim((string) $this->input->post('site_title'));
        $site_tagline = trim((string) $this->input->post('site_tagline'));

        if ($site_title === '') {
            echo json_encode([
                'status' => false,
                'message' => 'Please correct the highlighted fields',
                'errors' => ['site_title' => 'Site title is required'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $this->load->model('Settings_model');
        $this->Settings_model->update_settings([
            'site_title' => $site_title,
            'site_tagline' => $site_tagline
        ]);

        echo json_encode([
            'status' => true,
            'message' => 'Basic information updated successfully',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function update_smtp()
    {
        if ($this->input->method(true) !== 'POST') {
            $this->output->set_status_header(405);
            echo json_encode([
                'status' => false,
                'message' => 'Method not allowed',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $this->load->model('Settings_model');
        $existing_settings = $this->Settings_model->get_settings();

        $smtp_host = trim((string) $this->input->post('smtp_host'));
        $smtp_port = trim((string) $this->input->post('smtp_port'));
        $smtp_user = trim((string) $this->input->post('smtp_user'));
        $smtp_password = (string) $this->input->post('smtp_pass');
        $smtp_encryption = strtolower(trim((string) $this->input->post('smtp_encryption')));
        $smtp_from_email = trim((string) $this->input->post('smtp_from_email'));
        $smtp_from_name = trim((string) $this->input->post('smtp_from_name'));
        $errors = [];

        if ($smtp_host === '') {
            $errors['smtp_host'] = 'SMTP host is required';
        }

        if ($smtp_port === '' || filter_var($smtp_port, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 65535]
        ]) === false) {
            $errors['smtp_port'] = 'Enter a valid port between 1 and 65535';
        }

        if ($smtp_user === '') {
            $errors['smtp_user'] = 'SMTP user is required';
        }

        if ($smtp_password === '' && empty($existing_settings->smtp_pass)) {
            $errors['smtp_pass'] = 'SMTP password is required';
        }

        if ($smtp_from_email === '' || !filter_var($smtp_from_email, FILTER_VALIDATE_EMAIL)) {
            $errors['smtp_from_email'] = 'Enter a valid email';
        }

        if ($smtp_from_name === '') {
            $errors['smtp_from_name'] = 'From name is required';
        }

        if (!in_array($smtp_encryption, ['tls', 'ssl', 'none'], true)) {
            $errors['smtp_encryption'] = 'Select a valid encryption type';
        }

        if (!empty($errors)) {
            echo json_encode([
                'status' => false,
                'message' => 'Please correct the highlighted fields',
                'errors' => $errors,
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $smtp_data = [
            'smtp_host' => $smtp_host,
            'smtp_port' => $smtp_port,
            'smtp_user' => $smtp_user,
            'smtp_encryption' => $smtp_encryption,
            'smtp_from_email' => $smtp_from_email,
            'smtp_from_name' => $smtp_from_name
        ];

        if ($smtp_password !== '') {
            $smtp_data['smtp_pass'] = $smtp_password;
        }

        $this->Settings_model->update_settings($smtp_data);

        echo json_encode([
            'status' => true,
            'message' => 'SMTP settings updated successfully',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function test_smtp()
    {
        if ($this->input->method(true) !== 'POST') {
            $this->output->set_status_header(405);
            echo json_encode([
                'status' => false,
                'message' => 'Method not allowed',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $recipient_email = trim((string) $this->input->post('test_email'));
        if ($recipient_email === '' || !filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'status' => false,
                'message' => 'Please enter a valid email address',
                'errors' => ['test_email' => 'Enter a valid email address'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $this->load->model(['Settings_model', 'Mail_model']);
        $settings = $this->Settings_model->get_settings();
        $email_body = $this->load->view('emails/test-email', [
            'from_name' => $settings ? (string) $settings->smtp_from_name : ''
        ], true);

        if (!$this->Mail_model->sendMailSMTP_uv(
            '',
            [$recipient_email],
            'SMTP Test Email',
            $email_body
        )) {
            echo json_encode([
                'status' => false,
                'message' => 'Unable to send test email. Please verify the saved SMTP settings and recipient address.',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'message' => 'Test email sent successfully to ' . $recipient_email,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function update_airtel()
    {
        if ($this->input->method(true) !== 'POST') {
            $this->output->set_status_header(405);
            echo json_encode([
                'status' => false,
                'message' => 'Method not allowed',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $this->load->model('Airtel_config_model');
        $existing_config = $this->Airtel_config_model->get_config();

        $api_url = trim((string) $this->input->post('api_url'));
        $api_key = trim((string) $this->input->post('api_key'));
        $caller_id = trim((string) $this->input->post('caller_id'));
        $customer_id = trim((string) $this->input->post('customer_id'));
        $call_flow_id = trim((string) $this->input->post('call_flow_id'));
        $notify_url = trim((string) $this->input->post('notify_url'));
        $errors = [];

        if ($api_url === '' || !filter_var($api_url, FILTER_VALIDATE_URL)) {
            $errors['api_url'] = 'Enter a valid API URL';
        }

        if ($api_key === '' && empty($existing_config->api_key_encrypted)) {
            $errors['api_key'] = 'API key is required';
        }

        if ($caller_id === '' || !preg_match('/^\+?[0-9]{6,20}$/', $caller_id)) {
            $errors['caller_id'] = 'Enter a valid caller ID';
        }

        if ($customer_id === '') {
            $errors['customer_id'] = 'Customer ID is required';
        }

        if ($call_flow_id === '') {
            $errors['call_flow_id'] = 'Call flow ID is required';
        }

        if ($notify_url === '' || !filter_var($notify_url, FILTER_VALIDATE_URL)) {
            $errors['notify_url'] = 'Enter a valid notify URL';
        }

        if (!empty($errors)) {
            echo json_encode([
                'status' => false,
                'message' => 'Please correct the highlighted fields',
                'errors' => $errors,
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $airtel_data = [
            'api_url' => $api_url,
            'caller_id' => $caller_id,
            'customer_id' => $customer_id,
            'call_flow_id' => $call_flow_id,
            'notify_url' => $notify_url
        ];

        if ($api_key !== '') {
            $this->load->library('encryption');
            $airtel_data['api_key_encrypted'] = $this->encryption->encrypt($api_key);
        }

        if (!$this->Airtel_config_model->save_config($airtel_data)) {
            echo json_encode([
                'status' => false,
                'message' => 'Unable to save Airtel configuration. Please run the Airtel configuration migration first.',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'message' => 'Airtel configuration saved successfully',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function update_mycloud()
    {
        if ($this->input->method(true) !== 'POST') {
            $this->output->set_status_header(405);
            echo json_encode([
                'status' => false,
                'message' => 'Method not allowed',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $this->load->model('Mycloud_config_model');
        $existing_config = $this->Mycloud_config_model->get_config();

        $api_url = rtrim(trim((string) $this->input->post('mycloud_api_url')), '/');
        $auth_code = trim((string) $this->input->post('mycloud_auth_code'));
        $client_id = trim((string) $this->input->post('mycloud_client_id'));
        $chain_code = trim((string) $this->input->post('mycloud_chain_code'));
        $errors = [];

        $api_url = preg_replace('#/(processbookings|searchbookings|getroomrateavailability)$#i', '', $api_url);

        if ($api_url === '' || !filter_var($api_url, FILTER_VALIDATE_URL) || strtolower((string) parse_url($api_url, PHP_URL_SCHEME)) !== 'https') {
            $errors['mycloud_api_url'] = 'Enter a valid HTTPS API base URL';
        } elseif (strlen($api_url) > 500) {
            $errors['mycloud_api_url'] = 'API Base URL cannot exceed 500 characters';
        }

        if ($auth_code === '' && empty($existing_config->auth_code_encrypted)) {
            $errors['mycloud_auth_code'] = 'Auth Code is required';
        }

        if ($client_id === '') {
            $errors['mycloud_client_id'] = 'Client ID is required';
        } elseif (strlen($client_id) > 150) {
            $errors['mycloud_client_id'] = 'Client ID cannot exceed 150 characters';
        }

        if ($chain_code === '') {
            $errors['mycloud_chain_code'] = 'Chain Code is required';
        } elseif (strlen($chain_code) > 100) {
            $errors['mycloud_chain_code'] = 'Chain Code cannot exceed 100 characters';
        }

        if (!empty($errors)) {
            echo json_encode([
                'status' => false,
                'message' => 'Please correct the highlighted fields',
                'errors' => $errors,
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $mycloud_data = [
            'api_url' => $api_url,
            'client_id' => $client_id,
            'chain_code' => $chain_code
        ];

        if ($auth_code !== '') {
            $this->load->library('encryption');
            $mycloud_data['auth_code_encrypted'] = $this->encryption->encrypt($auth_code);
        }

        if (!$this->Mycloud_config_model->save_config($mycloud_data)) {
            echo json_encode([
                'status' => false,
                'message' => 'Unable to save MyCloud configuration. Please run the PMS configuration migration first.',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'message' => 'MyCloud PMS configuration saved successfully',
            'clientId' => $client_id,
            'chainCode' => $chain_code,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function upload_branding()
    {
        if ($this->input->method(true) !== 'POST') {
            $this->output->set_status_header(405);
            echo json_encode([
                'status' => false,
                'message' => 'Method not allowed',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $allowed_fields = ['logo', 'favicon', 'login_bg_image'];
        $uploaded_fields = [];

        foreach ($allowed_fields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                $uploaded_fields[] = $field;
            }
        }

        if (count($uploaded_fields) !== 1) {
            echo json_encode([
                'status' => false,
                'message' => 'Please select one branding image to upload',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $field = $uploaded_fields[0];
        $upload = $this->_upload_file($field);

        if ($upload['status'] !== true) {
            echo json_encode([
                'status' => false,
                'message' => $upload['error'],
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $this->load->model('Settings_model');
        $this->Settings_model->update_settings([$field => $upload['file']]);

        echo json_encode([
            'status' => true,
            'message' => 'Branding image uploaded successfully',
            'field' => $field,
            'file' => $upload['file'],
            'fileUrl' => base_url($upload['file']),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }


    private function _upload_file($field)
    {
        $config = [
            'upload_path'   => FCPATH . 'uploads/settings/',
            'allowed_types' => 'jpg|jpeg|png|ico|webp',
            'max_size'      => 6048, // 6MB
            'encrypt_name'  => true
        ];

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload');
        $this->upload->initialize($config); // 🔥 VERY IMPORTANT

        if ($this->upload->do_upload($field)) {
            return [
                'status' => true,
                'file'   => 'uploads/settings/' . $this->upload->data('file_name')
            ];
        }

        return [
            'status' => false,
            'error'  => strip_tags($this->upload->display_errors())
        ];
    }
}

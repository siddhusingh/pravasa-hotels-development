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
        $data['settings'] = $this->Settings_model->get_settings();
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

        $smtp_from_email = trim((string) $this->input->post('smtp_from_email'));
        $smtp_encryption = strtolower(trim((string) $this->input->post('smtp_encryption')));
        $errors = [];

        if ($smtp_from_email === '' || !filter_var($smtp_from_email, FILTER_VALIDATE_EMAIL)) {
            $errors['smtp_from_email'] = 'Enter a valid email';
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
            'smtp_host' => trim((string) $this->input->post('smtp_host')),
            'smtp_port' => trim((string) $this->input->post('smtp_port')),
            'smtp_user' => trim((string) $this->input->post('smtp_user')),
            'smtp_encryption' => $smtp_encryption,
            'smtp_from_email' => $smtp_from_email,
            'smtp_from_name' => trim((string) $this->input->post('smtp_from_name'))
        ];

        $smtp_password = (string) $this->input->post('smtp_pass');
        if ($smtp_password !== '') {
            $smtp_data['smtp_pass'] = $smtp_password;
        }

        $this->load->model('Settings_model');
        $this->Settings_model->update_settings($smtp_data);

        echo json_encode([
            'status' => true,
            'message' => 'SMTP settings updated successfully',
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

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

    public function update()
    {
        $this->load->model('Settings_model');

        $post = $this->input->post();
        unset($post[$this->security->get_csrf_token_name()]);

        $upload_fields = ['logo', 'favicon', 'login_bg_image'];

        foreach ($upload_fields as $field) {
            if (!empty($_FILES[$field]['name'])) {

                $upload = $this->_upload_file($field);

                if ($upload['status'] === true) {
                    $post[$field] = $upload['file'];
                } else {
                    echo json_encode([
                        'status' => false,
                        'message' => $upload['error'],
                        'csrfHash' => $this->security->get_csrf_hash()
                    ]);
                    return; // ⛔ stop execution
                }
            } else {
                unset($post[$field]); // ✅ DO NOT overwrite existing file
            }
        }

        $this->Settings_model->update_settings($post);

        echo json_encode([
            'status' => true,
            'message' => 'Settings updated successfully',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }


    private function _upload_file($field)
    {
        $config = [
            'upload_path'   => FCPATH . 'uploads/settings/',
            'allowed_types' => 'jpg|jpeg|png|ico|webp',
            'max_size'      => 6048, // 2MB
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

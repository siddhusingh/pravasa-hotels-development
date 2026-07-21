<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index()
    {
        if (!empty($this->session->userdata('super_admin_session'))) {
            redirect("super-admin-dashbaord");
        } else {

            $this->load->view('super_admin/login');
        }
    }

    public function login_check()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $role_as = $this->input->post('role_as');

        // Initialize response array
        $response = [
            'response_message' => '',
            'redirect_url' => '',
            'csrfHash' => $this->security->get_csrf_hash()
        ];

        // Fetch user data based on email
        $data = $this->Comman_model->get_single_record('super_admin', [
            'email' => $email,
            'is_deleted' => 0
        ]);

        if (!empty($data)) {
            // Verify password
            if ($data->password == md5($password)) {
                // Check for auditee role

                if ($data->status == 'active') {


                    $this->session->set_userdata('role_as', 'super_admin');

                    $this->session->set_userdata('user_role', $data->user_role);







                    $this->session->set_userdata('logged_in_username', $data->name);

                    $session_data = [
                        'id' => $data->id,
                        'logged_in' => true,
                        'user_name' => $data->full_name,
                        'email' => $data->email,
                        'phone' => $data->phone
                    ];


                    //want to set audit or access log for super admin login


                    $this->Common_model->insertActivityLog([
                        'module' => 'super_admin_login',
                        'record_id' => $data->id,
                        'action' => 'login',
                        'details' => 'Super Admin logged in',
                        'actor_id' => $data->id,
                        'actor_name' => $data->full_name,
                        'actor_email' => $data->email,
                        'actor_role' => 'super_admin',
                        'ip_address' => $this->input->ip_address(),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);


                    $this->session->set_userdata('super_admin_session', $session_data);

                    $response['response_message'] = 'logginSCS';
                    $response['redirect_url'] = 'super-admin-dashbaord';
                } else {
                    $response['response_message'] = 'disabled';
                }
            } else {
                $response['response_message'] = 'WRONGPASS';
            }
        } else {
            $response['response_message'] = 'account404';
        }

        // Output the response as JSON
        echo json_encode($response);
    }


    function logout()
    {


        $this->session->unset_userdata('super_admin_session');
        $this->session->sess_destroy();
        redirect(base_url('super-admin-dashbaord'));
    }




    //  for  forget password  

    public function forget_password()
    {

        $this->load->view('super_admin/forget_password');
    }



    public function check_email()
    {
        if ($this->input->method(true) !== 'POST') {
            return redirect('forget-password-super-admin');
        }

        $email = trim((string) $this->input->post('email'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Please enter a valid email address.');
            return redirect('forget-password-super-admin');
        }

        $account = $this->Comman_model->getdata('super_admin', [
            'email' => $email,
            'is_deleted' => 0,
            'status' => 'active'
        ]);

        // Use the same response for unknown accounts to prevent email enumeration.
        if (!$account) {
            $this->session->set_flashdata('success', 'If this email belongs to an active account, a reset link has been sent.');
            return redirect('forget-password-super-admin');
        }

        try {
            $token = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            log_message('error', 'Unable to generate Super Admin password reset token: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Unable to create a password reset request. Please try again.');
            return redirect('forget-password-super-admin');
        }

        $token_hash = hash('sha256', $token);
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $updated = $this->Comman_model->UpdateRecord('super_admin', [
            'reset_token_hash' => $token_hash,
            'reset_token_expires_at' => $expires_at
        ], ['id' => $account->id]);

        if (!$updated) {
            $this->session->set_flashdata('error', 'Unable to create a password reset request. Please try again.');
            return redirect('forget-password-super-admin');
        }

        $this->load->model('Mail_model');
        $reset_url = base_url('reset-password-super-admin/' . rawurlencode($token));
        $message = $this->load->view('emails/super-admin-password-reset', [
            'name' => $account->full_name,
            'reset_url' => $reset_url
        ], true);

        if (!$this->Mail_model->sendMailSMTP_uv(
            $account->full_name,
            [$account->email],
            'Reset Your Super Admin Password',
            $message
        )) {
            $this->Comman_model->UpdateRecord('super_admin', [
                'reset_token_hash' => null,
                'reset_token_expires_at' => null
            ], ['id' => $account->id]);
            $this->session->set_flashdata('error', 'Unable to send the reset email. Please verify the SMTP settings and try again.');
            return redirect('forget-password-super-admin');
        }

        $this->session->set_flashdata('success', 'If this email belongs to an active account, a reset link has been sent.');
        return redirect('forget-password-super-admin');
    }

    public function reset_password($token = '')
    {
        $account = $this->find_account_by_reset_token($token);
        if (!$account) {
            $this->session->set_flashdata('error', 'This password reset link is invalid or has expired.');
            return redirect('forget-password-super-admin');
        }

        $this->load->view('super_admin/reset_password', ['token' => $token]);
    }

    public function update_password()
    {
        if ($this->input->method(true) !== 'POST') {
            return redirect('super-admin-login');
        }

        $token = trim((string) $this->input->post('token'));
        $password = (string) $this->input->post('password');
        $confirm_password = (string) $this->input->post('confirm_password');
        $account = $this->find_account_by_reset_token($token);

        if (!$account) {
            $this->session->set_flashdata('error', 'This password reset link is invalid or has expired.');
            return redirect('forget-password-super-admin');
        }

        if (strlen($password) < 6) {
            $this->session->set_flashdata('error', 'Password must be at least 6 characters.');
            return redirect('reset-password-super-admin/' . rawurlencode($token));
        }

        if ($password !== $confirm_password) {
            $this->session->set_flashdata('error', 'Password confirmation does not match.');
            return redirect('reset-password-super-admin/' . rawurlencode($token));
        }

        $updated = $this->Comman_model->UpdateRecord('super_admin', [
            'password' => md5($password),
            'reset_token_hash' => null,
            'reset_token_expires_at' => null
        ], ['id' => $account->id]);

        if (!$updated) {
            $this->session->set_flashdata('error', 'Unable to update the password. Please try again.');
            return redirect('reset-password-super-admin/' . rawurlencode($token));
        }

        $this->session->set_flashdata('success', 'Password updated successfully. You can now log in.');
        return redirect('super-admin-login');
    }

    private function find_account_by_reset_token($token)
    {
        if (!is_string($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }

        return $this->db
            ->where('reset_token_hash', hash('sha256', $token))
            ->where('reset_token_expires_at >=', date('Y-m-d H:i:s'))
            ->where('is_deleted', 0)
            ->where('status', 'active')
            ->get('super_admin')
            ->row();
    }

    //  end  forgrt password


    //  start change password



    public function change_password()
    {

        $this->load->view('admin/change_password');
    }



    public function confirm_password()
    {


        $id = $this->input->post('id');
        $new_password = $this->input->post('new_password');


        $table = 'admin';
        $where = array(
            'admin_id' => $id
        );

        $data = array(
            'admin_password' => $new_password,


        );
        $result = $this->Comman_model->UpdateRecord($table, $data, $where);

        if ($result) {
            $this->session->set_flashdata('success', "your password has been Changed successfully");
        } else {
            $this->session->set_flashdata('error', "Something went Wrong");
        }
        return redirect('admin-login');
    }










    // start confirm  password


    /*========Main Class Ending========*/
}

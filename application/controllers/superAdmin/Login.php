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
        $email = $this->input->POST('email');
        $userdata = array('email' => $email);


        $result = $this->Comman_model->getdata('user_profile', $userdata);



        if (!empty($result)) {

            $result = $this->Comman_model->getdata('user_profile', $userdata);

            $$email = $result->email;
            $name = $result->full_name;
            $subject = "Forgot Password ";


            $random = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(25 / strlen($x)))), 1, 25);

            $url = base_url('set-password') . '/' . $random;



            $data = $this->Comman_model->UpdateRecord('user_profile', ['security_token' => $random], array('email' => $email));

            $message = "please click on this <a href='$url'>URL </a>,to proceed to change the password.";

            $this->sendMailSTMP($name, $email, $CC_Mails = "", $subject, $message);

            $this->session->set_flashdata('success', " We have sent reset password link to your mail. please check your mail to reset password");
        } else {

            $this->session->set_flashdata('error', "This Email does not exists");
        }
        return redirect('forgot_password');
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

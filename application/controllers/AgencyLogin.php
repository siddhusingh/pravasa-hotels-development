<?php
defined('BASEPATH') or exit('No direct script access allowed');



class AgencyLogin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Agency_model');
    }

    public function index()
    {
        if (!empty($this->session->userdata('agency_session'))) {
            redirect("agency-dashboard");
        } else {

            $this->load->view('agency_login');
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
            'redirect_url' => ''
        ];

        // Fetch user data based on email
        $data = $this->Comman_model->get_single_record('agencies', [
            'email' => $email,
            'is_deleted' => 0
        ]);

        if (!empty($data)) {
            // Verify password
            if ($data->password == md5($password)) {
                // Check for auditee role

                if ($data->status == 'Active') {


                    $this->session->set_userdata('role_as', 'agency');

                    $this->session->set_userdata('logged_in_username', $data->agency_name);


                    // prepare session data
                    $session_data = [
                        'id'        => $data->id,
                        'logged_in' => true,
                        'user_name' => $data->full_name,
                        'phone'     => $data->phone,
                    ];






                    $this->session->set_userdata('agency_session', $session_data);

                    $response['response_message'] = 'logginSCS';
                    $response['redirect_url'] = 'agency-dashboard';
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


    public function select_hotel()
    {
        $hotel_id = $this->input->post('hotel_id');

        $this->session->set_userdata(
            'agency_session',
            array_merge(
                $this->session->userdata('agency_session'),
                ['id' => $hotel_id] // new id value
            )
        );




        redirect($_SERVER['HTTP_REFERER'] ?? 'hotel-admin-dashboard');
    }


    function logout()
    {


        $this->session->unset_userdata('agency_session');
        $this->session->sess_destroy();
        redirect(base_url('agency-dashboard'));
    }




    //  for  forget password  

    public function forget_password()
    {

        $this->load->view('hotel_admin/forget_password');
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

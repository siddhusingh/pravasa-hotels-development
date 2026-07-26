<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common','objcom');
    }

    public function index()
    {
	    if(!empty($this->session->userdata('sales_session')))
        {
            header("Location: ".base_url('sales/dashboard'), true, 301);
        }else{
            header("Location: ".base_url('sales/sign-in'), true, 301);
            $this->load->view('sales/sign-in');
        }
	}

    function sign_in()
    {
        if(!empty($this->session->userdata('sales_session')))
        {
            header("Location: ".base_url('sales/dashboard'), true, 301);
        }else{
            $this->load->view('sales/sign-in');
        }    
    }

    function login_auth()
    {
        $status = 500;
        $response = "";
        $redirect = "";

        $inputs = $this->input->post();
        $plainPassword = (string)($inputs['password'] ?? '');
        $result = $this->objcom->get_single_record('sales_users', array('email' => ($inputs['email'] ?? ''),'is_deleted'=>0));
        if(!empty($result))
        {
            $storedPassword = (string)$result->password;
            $isLegacyPassword = (bool)preg_match('/^[a-f0-9]{32}$/i', $storedPassword);
            $passwordIsValid = $isLegacyPassword
                ? hash_equals(strtolower($storedPassword), md5($plainPassword))
                : password_verify($plainPassword, $storedPassword);

            if($passwordIsValid)
            {
                if($result->status == 0)
                {
                    $response = "disabled";
                }else{
                    if ($isLegacyPassword || password_needs_rehash($storedPassword, PASSWORD_DEFAULT)) {
                        $newPasswordHash = password_hash($plainPassword, PASSWORD_DEFAULT);
                        if ($newPasswordHash !== false) {
                            $this->objcom->update_records(
                                'sales_users',
                                ['password' => $newPasswordHash],
                                ['id' => $result->id]
                            );
                        }
                    }

                    $this->session->sess_regenerate(true);
                    $session_data = array('login_id' =>$result->id, 'login_type' => $result->user_role);
                    $this->session->set_userdata('sales_session', $session_data);
                    $response = "logginSCS";
                    $redirect = base_url('sales/dashboard');
                } 
            }else{
                $response = "WRONGPASS";
            }
        }else{
            $response = "account404";
        }

        $outputs = array(
            'message' =>$response,
            'status'=>$status,
            'redirect_url'=> $redirect,
            'csrfHash'=>$this->security->get_csrf_hash()
        );
        echo json_encode($outputs);
    }

    function logout()
    {
        $this->session->unset_userdata('sales_session');
        redirect('sales/sign-in');
    }
    
//Class Ended
}

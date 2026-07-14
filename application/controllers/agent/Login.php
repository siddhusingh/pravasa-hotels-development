<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
    }



    public function index()
    {
        if (!empty($this->session->userdata('agent_session'))) {
            redirect("agent-dashboard");
        } else {

            $this->load->view('agent/login');
        }
    }

    public function login_check()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $response = [
            'response_message' => '',
            'redirect_url' => '',
            'hotels' => []
        ];

        // Fetch staff member
        $staff = $this->Comman_model->get_single_record('staff_members', [
            'email' => $email,
            'is_deleted' => 0
        ]);

        if (!empty($staff)) {
            if ($staff->password == md5($password)) {

                if ($staff->status == '1') {

                    // Fetch mapped hotels
                    $this->db->select('h.hotel_id, h.hotel_name,shd.department_id');
                    $this->db->from('staff_hotel_department_mapping as shd');
                    $this->db->join('hotel_admin as h', 'h.hotel_id = shd.hotel_id', 'left');
                    $this->db->where('shd.staff_id', $staff->id);
                    $this->db->group_by('h.hotel_id');
                    $hotel_list = $this->db->get()->result();

                    if (!empty($hotel_list)) {
                        if (!empty($hotel_list)) {
                            // Always pick the first hotel (0th index) from the list
                            $hotel = $hotel_list[0]->hotel_id;
                            $department_id = $hotel_list[0]->department_id;

                            $session_data = [
                                'id' => $staff->id,
                                'hotel_id' => $hotel,
                                'logged_in' => true,
                                'user_name' => $staff->name,
                                'phone' => $staff->phone
                            ];

                            $this->session->set_userdata('selected_hotel_id', $hotel);
                            $this->session->set_userdata('selected_department_id', $department_id);

                            $hotel_data = $this->Comman_model->get_single_record('hotel_admin', ['hotel_id' => $hotel]);
                            $this->session->set_userdata('selected_hotel_name', $hotel_data->hotel_name);

                            $this->session->set_userdata('agent_session', $session_data);
                            $this->session->set_userdata('role_as', 'agent');

                            $response['response_message'] = 'logginSCS';
                            $response['redirect_url'] = 'agent-dashboard'; // Change if needed

                            //want to set audit or access log for agent login   

                            $this->Common_model->insertActivityLog([
                                'module' => 'staff_members',
                                'record_id' => $staff->id,
                                'action' => 'login',
                                'details' => 'Agent logged in',
                                'actor_id' => $staff->id,
                                'actor_name' => $staff->name,
                                'actor_email' => $staff->email,
                                'actor_role' => 'agent',
                                'ip_address' => $this->input->ip_address(),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        } else {
                            // No hotel mapped — return error or redirect
                            $response['response_message'] = 'noHotelMapped';
                        }
                    } else {
                        $response['response_message'] = 'noHotels';
                    }
                } else {
                    $response['response_message'] = 'disabled';
                }
            } else {
                $response['response_message'] = 'WRONGPASS';
            }
        } else {
            $response['response_message'] = 'account404';
        }

        echo json_encode($response);
    }



    public function select_hotel()
    {
        $hotel_id = $this->input->post('hotel_id');
        $agent = $this->session->userdata('agent_session');

        $mapping = $this->db
            ->where('staff_id', $agent['id'])
            ->where('hotel_id', $hotel_id)
            ->get('staff_hotel_department_mapping')
            ->row();

        if ($mapping) {
            $hotel_data = $this->Comman_model->get_single_record('hotel_admin', ['hotel_id' => $hotel_id]);

            $this->session->set_userdata('selected_hotel_id', $hotel_id);
            $this->session->set_userdata('selected_hotel_name', $hotel_data->hotel_name);
            $this->session->set_userdata('selected_department_id', $mapping->department_id);
        }

        redirect($_SERVER['HTTP_REFERER'] ?? 'agent-dashboard');
    }



    public function set_selected_hotel()
    {
        $hotel_id = $this->input->post('hotel_id');

        if (!empty($hotel_id)) {

            $staff_hotel_department_mapping_data = $this->Comman_model->get_single_record('staff_hotel_department_mapping', ['hotel_id' => $hotel_id, 'staff_id' => $this->session->userdata('agent_session')['id']]);


            $this->session->set_userdata('selected_hotel_id', $hotel_id);
            $this->session->set_userdata('selected_department_id', $staff_hotel_department_mapping_data->department_id);



            $hotel_data = $this->Comman_model->get_single_record('hotel_admin', ['hotel_id' => $hotel_id]);

            $this->session->set_userdata('selected_hotel_name', $hotel_data->hotel_name);

            echo json_encode([
                'status' => 'success',
                'redirect_url' => 'agent-dashboard' // typo corrected here also
            ]);
        } else {
            echo json_encode([
                'status' => 'error'
            ]);
        }
    }



    function logout()
    {


        $this->session->unset_userdata('agent_session');
        $this->session->sess_destroy();
        redirect(base_url('agent-login'));
    }




    //  for  forget password  

    public function forget_password()
    {

        $this->load->view('agengt/forget_password');
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

        $this->load->view('agent/change_password');
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
        return redirect('agent-login');
    }










    // start confirm  password


    /*========Main Class Ending========*/
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MailController extends CI_Controller
{

    public function index()
    {

        $this->load->model('Mail_model');
        $this->Mail_model->sendMailSMTP_uv();
    }
}

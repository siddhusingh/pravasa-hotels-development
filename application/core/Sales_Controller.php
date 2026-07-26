<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_Controller extends CI_Controller
{
    protected $salesUserId;
    protected $salesUser;
    protected $salesViewData = [];

    public function __construct()
    {
        parent::__construct();

        $salesSession = $this->session->userdata('sales_session');
        $loginId = is_array($salesSession) ? ($salesSession['login_id'] ?? null) : null;

        if (!ctype_digit((string) $loginId) || (int) $loginId <= 0) {
            $this->endSalesSession();
        }

        $this->salesUserId = (int) $loginId;
        $this->load->model('common', 'objcom');
        $this->salesUser = $this->objcom->select_columns_row(
            'sales_users',
            'full_name,email,phone,user_role',
            [
                'id' => $this->salesUserId,
                'status' => 1,
                'is_deleted' => 0
            ]
        );

        $allowedRoles = ['RSO', 'Sales Manager', 'Sales Executive'];
        if (empty($this->salesUser) || !in_array($this->salesUser->user_role, $allowedRoles, true)) {
            $this->endSalesSession();
        }

        $this->salesViewData = [
            'profile_data' => $this->salesUser,
            'sales_role' => $this->salesUser->user_role,
            'is_sales_manager' => $this->salesUser->user_role === 'Sales Manager',
            'is_sales_executive' => $this->salesUser->user_role === 'Sales Executive'
        ];

        $this->load->vars($this->salesViewData);
    }

    protected function renderSalesPage($contentView, array $data = [])
    {
        if (!empty($data)) {
            $this->load->vars($data);
        }

        $this->load->view('sales/include/header');
        $this->load->view('sales/include/sidebar');
        $this->load->view($contentView);
        $this->load->view('sales/include/footer');
    }

    protected function requireSalesRole(array $allowedRoles)
    {
        if (!in_array($this->salesUser->user_role, $allowedRoles, true)) {
            show_error('You are not authorized to access this page.', 403, 'Forbidden');
        }
    }

    private function endSalesSession()
    {
        $this->session->unset_userdata('sales_session');
        redirect('sales/sign-in');
        exit;
    }
}

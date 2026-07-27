<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Sales_Controller.php';

class Profile extends Sales_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireSalesRole(['Sales Executive']);
    }

    public function index()
    {
        $this->renderSalesPage('sales/profile');
    }

    public function update()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_error('Method Not Allowed', 405);
        }

        $fullName = trim((string) $this->input->post('full_name', true));
        $email = strtolower(trim((string) $this->input->post('email', true)));
        $phone = trim((string) $this->input->post('phone', true));
        $password = (string) $this->input->post('password');
        $errors = [];

        if ($fullName === '' || strlen($fullName) < 3 || strlen($fullName) > 100) {
            $errors['full_name'] = 'Full name must be between 3 and 100 characters.';
        }

        if ($email === '' || strlen($email) > 190 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif (
            $this->db
                ->where('email', $email)
                ->where('id !=', $this->salesUserId)
                ->where('is_deleted', 0)
                ->count_all_results('sales_users') > 0
        ) {
            $errors['email'] = 'This email address is already in use.';
        }

        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            $errors['phone'] = 'Phone number must contain exactly 10 digits.';
        }

        if ($password !== '' && (strlen($password) < 9 || strlen($password) > 72)) {
            $errors['password'] = 'Password must be between 9 and 72 characters.';
        }

        if (!empty($errors)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Please correct the highlighted fields.',
                'errors' => $errors
            ], 422);
        }

        $updateData = [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            if ($passwordHash === false) {
                return $this->jsonResponse([
                    'status' => false,
                    'message' => 'Unable to secure the new password. Please try again.',
                    'errors' => []
                ], 500);
            }
            $updateData['password'] = $passwordHash;
        }

        $updated = $this->objcom->update_records(
            'sales_users',
            $updateData,
            [
                'id' => $this->salesUserId,
                'status' => 1,
                'is_deleted' => 0
            ]
        );

        if (!$updated) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Unable to update your profile. Please try again.',
                'errors' => []
            ], 500);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Your profile updated successfully.',
            'errors' => [],
            'profile' => [
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone
            ]
        ]);
    }

    private function jsonResponse(array $payload, $statusCode = 200)
    {
        $payload['csrfHash'] = $this->security->get_csrf_hash();

        return $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }
}

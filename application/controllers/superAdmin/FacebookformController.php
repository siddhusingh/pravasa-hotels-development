<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FacebookformController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Facebookform_model');
        $this->load->model('Common_model');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }

    // Show all forms for a department
    public function manage($department_id)
    {
        $data['department_id'] = $department_id;
        $data['department_info'] = $this->Common_model->getdata('departments', array('department_id' => $department_id));

        $data['forms'] = $this->Facebookform_model->getFormsByDepartment($department_id);
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/facebook_forms/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function addForm()
    {
        $this->load->library('form_validation');



        $data = [
            'form_id' => $this->input->post('form_id'),
            'form_name' => $this->input->post('form_name'),
            'department_id' => $this->input->post('department_id'),
            'status' => $this->input->post('status'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('facebook_forms', $data);

        if ($insert) {
            echo json_encode(['status' => 'success', 'message' => 'Form added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error!']);
        }
    }

    public function fetchForms()
    {
        $department_id = $this->input->post('department_id'); // optional, if filtering by department

        $this->load->model('Facebookform_model');

        if (!empty($department_id)) {
            $data['forms'] = $this->Facebookform_model->getFormsByDepartment($department_id);
        }

        // Return HTML table rows (view partial)
        $html = $this->load->view('super_admin/facebook_forms/_forms_table', $data, true);
        echo $html;
    }


    public function getFormDetails()
    {
        $id = $this->input->post('id');
        $form = $this->db->get_where('facebook_forms', ['id' => $id])->row();

        if ($form) {
            echo json_encode(['status' => 'success', 'data' => $form]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Form not found']);
        }
    }


    public function updateForm()
    {
        $id = $this->input->post('id');
        $data = [
            'form_id' => trim($this->input->post('form_id')),
            'form_name' => trim($this->input->post('form_name')),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (empty($data['form_id']) || empty($data['form_name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
            return;
        }

        $this->db->where('id', $id);
        $update = $this->db->update('facebook_forms', $data);

        if ($update) {
            echo json_encode(['status' => 'success', 'message' => 'Form updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update form']);
        }
    }


    public function deleteForm()
    {
        $form_id = $this->input->post('id');

        if (empty($form_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid form ID']);
            return;
        }

        // Attempt to delete the record
        $deleted = $this->db->where('id', $form_id)->delete('facebook_forms');

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Form deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete form.']);
        }
    }
}

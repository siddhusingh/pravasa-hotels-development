<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->library('upload');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }

    private function getCurrentActor()
    {
        $actor = $this->session->userdata('super_admin_session');
        return [
            'id' => $actor['id'] ?? null,
            'name' => $actor['user_name'] ?? $actor['full_name'] ?? '',
            'email' => $actor['email'] ?? '',
            'role' => $this->session->userdata('role_as') ?? 'super_admin'
        ];
    }

    private function logActivity($action, $record_id, $details = '')
    {
        $actor = $this->getCurrentActor();
        $this->Common_model->insertActivityLog([
            'module' => 'companies',
            'record_id' => $record_id,
            'action' => $action,
            'details' => $details,
            'actor_id' => $actor['id'],
            'actor_name' => $actor['name'],
            'actor_email' => $actor['email'],
            'actor_role' => $actor['role'],
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function jsonResponse($response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function manage()
    {
        $data['company_groups'] = $this->Common_model->getAllData('company_groups', '');
        $data['states'] = $this->Common_model->getAllData('state', '');
        $data['countries'] = $this->Common_model->getAllData('country', '');
        $data['cities'] = $this->Common_model->getAllData('city', '');

        $this->db->select('a.*, su1.full_name as primary_user_name, su2.full_name as secondary_user_name, s.state_name');
        $this->db->from('areas a');
        $this->db->join('sales_users su1', 'su1.id = a.primary_user_id', 'left');
        $this->db->join('sales_users su2', 'su2.id = a.secondary_user_id', 'left');
        $this->db->join('state s', 's.state_id = a.state_id', 'left');
        $data['areas'] = $this->db->get()->result();

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/company/manage_company', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_companies_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'c.company_id',
            1 => 'cg.company_group_name',
            2 => 'c.company_name',
            3 => 'c.email',
            4 => 'area.area_name',
            5 => 'c.mobile_number',
            6 => 'city.city_name',
            7 => 'state.state_name',
            8 => 'c.status',
            9 => 'c.created_at',
            10 => 'c.updated_at'
        ];
        $order = $columns[$inputs['order'][0]['column']] ?? 'c.company_id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTCompanies($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->company_id);
            $status = ((string) $row->status === '1') ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            $data[] = [
                $i++,
                htmlspecialchars($row->company_group_name ?? '-'),
                htmlspecialchars($row->company_name ?? '-'),
                htmlspecialchars($row->email ?? '-'),
                htmlspecialchars($row->area_name ?? '-'),
                htmlspecialchars($row->mobile_number ?? '-'),
                htmlspecialchars($row->city_name ?? '-'),
                htmlspecialchars($row->state_name ?? '-'),
                $status,
                !empty($row->created_at) ? date('d M Y h:i A', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y h:i A', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary editCompany" data-id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary deleteCompany" data-id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTCompaniesAll(),
            'recordsFiltered' => $this->objdt->DTCompaniesFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function save()
    {
        $id = $this->input->post('company_id') ? decrypt_id($this->input->post('company_id')) : '';
        $old = !empty($id) ? $this->Common_model->getdata('companies', ['company_id' => $id]) : null;

        if ($this->input->post('company_id') && (empty($id) || empty($old))) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid company record']);
            return;
        }

        $credit_form_file = $this->uploadCreditForm($old->credit_form_file ?? '');
        $data = $this->companyPayload(empty($id), $credit_form_file);
        $validationError = $this->validateCompany($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        if (empty($id)) {
            $record_id = $this->Comman_model->insertData('companies', $data);
            if ($record_id) {
                $this->logActivity('create', $record_id, 'Created company '.$data['company_name']);
            }
            $this->jsonResponse(['status' => (bool) $record_id, 'message' => $record_id ? 'Company added successfully' : 'Failed to add company', 'record_id' => $record_id ? encrypt_id($record_id) : '']);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('companies', $data, ['company_id' => $id]);
        if ($updated) {
            $this->logActivity('update', $id, 'Updated company '.$data['company_name']);
        }
        $this->jsonResponse(['status' => (bool) $updated, 'message' => $updated ? 'Company updated successfully' : 'Failed to update company', 'record_id' => encrypt_id($id)]);
    }

    public function add()
    {
        $this->save();
    }

    public function update()
    {
        $this->save();
    }

    public function getDetails()
    {
        $company_id = decrypt_id($this->input->post('company_id'));
        $this->db->select('c.*, cg.company_group_name, co.country_name, s.state_name, ci.city_name, a.area_name');
        $this->db->from('companies c');
        $this->db->join('company_groups cg', 'cg.id = c.company_group_id', 'left');
        $this->db->join('country co', 'co.country_id = c.country_id', 'left');
        $this->db->join('state s', 's.state_id = c.state_id', 'left');
        $this->db->join('city ci', 'ci.city_id = c.city_id', 'left');
        $this->db->join('areas a', 'a.area_id = c.area_id', 'left');
        $this->db->where('c.company_id', $company_id);
        $company = $this->db->get()->row();

        if (empty($company_id) || empty($company)) {
            $this->jsonResponse(['status' => false, 'message' => 'Company not found']);
            return;
        }

        $company->company_id = encrypt_id($company->company_id);
        $company->company_group_id = !empty($company->company_group_id) ? encrypt_id($company->company_group_id) : '';
        $company->country_id = !empty($company->country_id) ? encrypt_id($company->country_id) : '';
        $company->state_id = !empty($company->state_id) ? encrypt_id($company->state_id) : '';
        $company->city_id = !empty($company->city_id) ? encrypt_id($company->city_id) : '';
        $company->area_id = !empty($company->area_id) ? encrypt_id($company->area_id) : '';
        $this->jsonResponse(['status' => true, 'data' => $company]);
    }

    public function delete()
    {
        $company_id = decrypt_id($this->input->post('id'));

        if (empty($company_id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid company record']);
            return;
        }

        $company = $this->Common_model->getdata('companies', ['company_id' => $company_id]);
        $deleted = $this->Comman_model->Deletedata('companies', ['company_id' => $company_id]);

        if ($deleted) {
            $this->logActivity('delete', $company_id, isset($company->company_name) ? 'Deleted company '.$company->company_name : 'Deleted company ID '.$company_id);
        }

        $this->jsonResponse(['status' => (bool) $deleted, 'message' => $deleted ? 'Company deleted successfully' : 'Failed to delete company']);
    }

    private function companyPayload($includeCreated, $credit_form_file)
    {
        $data = [
            'company_group_id' => decrypt_id($this->input->post('company_group_id')),
            'company_name' => trim($this->input->post('company_name')),
            'email' => trim($this->input->post('email')),
            'secondary_email' => trim($this->input->post('secondary_email')),
            'phone_number' => trim($this->input->post('phone_number')),
            'mobile_number' => trim($this->input->post('mobile_number')),
            'gst_number' => trim($this->input->post('gst_number')),
            'address' => trim($this->input->post('address')),
            'city_id' => decrypt_id($this->input->post('city_id')),
            'state_id' => decrypt_id($this->input->post('state_id')),
            'country_id' => decrypt_id($this->input->post('country_id')),
            'pincode' => trim($this->input->post('pincode')),
            'area_id' => decrypt_id($this->input->post('area_id')),
            'details' => trim($this->input->post('details')),
            'deals_in' => trim($this->input->post('deals_in')),
            'company_creditibility' => $this->input->post('company_creditibility'),
            'credit_form_file' => $credit_form_file,
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateCompany($data)
    {
        foreach (['company_group_id', 'country_id', 'state_id', 'city_id', 'area_id'] as $field) {
            if (empty($data[$field])) {
                return 'Invalid company selection';
            }
        }

        foreach (['company_name', 'email', 'mobile_number', 'address'] as $field) {
            if ($data[$field] === '' || $data[$field] === null) {
                return 'Please fill all required company fields';
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address';
        }

        if (!in_array((string) $data['status'], ['0', '1'])) {
            return 'Invalid company status';
        }

        return '';
    }

    private function uploadCreditForm($old_file = '')
    {
        $field = !empty($_FILES['credit_form_file']['name']) ? 'credit_form_file' : (!empty($_FILES['credit_form']['name']) ? 'credit_form' : '');
        if ($field === '') {
            return $old_file;
        }

        $config['upload_path'] = './uploads/credit_forms/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx';
        $config['encrypt_name'] = true;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->upload->initialize($config);

        if ($this->upload->do_upload($field)) {
            if ($old_file && file_exists($config['upload_path'].$old_file)) {
                unlink($config['upload_path'].$old_file);
            }
            return $this->upload->data('file_name');
        }

        return $old_file;
    }
}

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

    private function activeState($stateId)
    {
        if (empty($stateId)) {
            return null;
        }

        return $this->db
            ->select('s.state_id, s.state_name, s.country_id')
            ->from('state s')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('s.state_id', $stateId)
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->row();
    }

    private function activeCity($cityId)
    {
        if (empty($cityId)) {
            return null;
        }

        return $this->db
            ->select('ci.city_id, ci.city_name, ci.state_id, ci.country_id')
            ->from('city ci')
            ->join('state s', 's.state_id = ci.state_id', 'inner')
            ->join('country c', 'c.country_id = ci.country_id', 'inner')
            ->where('ci.city_id', $cityId)
            ->where('ci.is_deleted', 0)
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->row();
    }

    private function activeArea($areaId)
    {
        if (empty($areaId)) {
            return null;
        }

        return $this->db
            ->select('a.area_id, a.area_name, a.state_id')
            ->from('areas a')
            ->join('state s', 's.state_id = a.state_id', 'inner')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('a.area_id', $areaId)
            ->where('a.is_deleted', 0)
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->row();
    }

    public function manage()
    {
        $data['company_groups'] = $this->Common_model->getAllData('company_groups', ['is_deleted' => 0]);
        $data['countries'] = $this->Common_model->getAllData('country', ['is_deleted' => 0]);
        $data['states'] = $this->db
            ->select('s.*')
            ->from('state s')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->result();
        $data['cities'] = $this->db
            ->select('ci.*')
            ->from('city ci')
            ->join('state s', 's.state_id = ci.state_id', 'inner')
            ->join('country c', 'c.country_id = ci.country_id', 'inner')
            ->where('ci.is_deleted', 0)
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->result();

        $this->db->select('a.*, su1.full_name as primary_user_name, su2.full_name as secondary_user_name, s.state_name');
        $this->db->from('areas a');
        $this->db->join('sales_users su1', 'su1.id = a.primary_user_id', 'left');
        $this->db->join('sales_users su2', 'su2.id = a.secondary_user_id', 'left');
        $this->db->join('state s', 's.state_id = a.state_id', 'inner');
        $this->db->join('country c', 'c.country_id = s.country_id', 'inner');
        $this->db->where('a.is_deleted', 0);
        $this->db->where('s.is_deleted', 0);
        $this->db->where('c.is_deleted', 0);
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
            8 => 'c.status'
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
                '<a href="javascript:void(0)" class="text-fade hover-primary editCompany" data-id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary deleteCompany" data-id="' . $encrypted . '">
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
        $old = !empty($id) ? $this->Common_model->getdata('companies', [
            'company_id' => $id,
            'is_deleted' => 0
        ]) : null;

        if ($this->input->post('company_id') && (empty($id) || empty($old))) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid company record']);
            return;
        }

        $oldCreditForm = $old->credit_form_file ?? '';
        $data = $this->companyPayload(empty($id), $oldCreditForm);
        $validationError = $this->validateCompany($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $data['credit_form_file'] = $this->uploadCreditForm($oldCreditForm);

        if (empty($id)) {
            $data['is_deleted'] = 0;
            $record_id = $this->Comman_model->insertData('companies', $data);
            if ($record_id) {
                $this->logActivity('create', $record_id, 'Created company ' . $data['company_name']);
            }
            $this->jsonResponse(['status' => (bool) $record_id, 'message' => $record_id ? 'Company added successfully' : 'Failed to add company', 'record_id' => $record_id ? encrypt_id($record_id) : '']);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('companies', $data, [
            'company_id' => $id,
            'is_deleted' => 0
        ]);
        $activeCompany = $updated ? $this->Common_model->getdata('companies', [
            'company_id' => $id,
            'is_deleted' => 0
        ]) : null;

        if (!empty($activeCompany)) {
            $this->logActivity('update', $id, 'Updated company ' . $data['company_name']);
        }
        $this->jsonResponse([
            'status' => !empty($activeCompany),
            'message' => !empty($activeCompany) ? 'Company updated successfully' : 'Company not found or already deleted',
            'record_id' => !empty($activeCompany) ? encrypt_id($id) : ''
        ]);
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
        $this->db->where('c.is_deleted', 0);
        $company = $this->db->get()->row();

        if (empty($company_id) || empty($company)) {
            $this->jsonResponse(['status' => false, 'message' => 'Company not found']);
            return;
        }

        $activeGroup = $this->Common_model->getdata('company_groups', [
            'id' => $company->company_group_id,
            'is_deleted' => 0
        ]);
        $activeCountry = $this->Common_model->getdata('country', [
            'country_id' => $company->country_id,
            'is_deleted' => 0
        ]);
        $activeState = $this->activeState($company->state_id);
        $activeCity = $this->activeCity($company->city_id);
        $activeArea = $this->activeArea($company->area_id);

        if (!empty($activeState) && (int) $activeState->country_id !== (int) $company->country_id) {
            $activeState = null;
        }
        if (!empty($activeCity) && (
            (int) $activeCity->country_id !== (int) $company->country_id
            || (int) $activeCity->state_id !== (int) $company->state_id
        )) {
            $activeCity = null;
        }
        if (!empty($activeArea) && (int) $activeArea->state_id !== (int) $company->state_id) {
            $activeArea = null;
        }

        $unavailableDependencies = [];
        foreach ([
            'company group' => $activeGroup,
            'country' => $activeCountry,
            'state' => $activeState,
            'city' => $activeCity,
            'area' => $activeArea
        ] as $label => $dependency) {
            if (empty($dependency)) {
                $unavailableDependencies[] = $label;
            }
        }

        $company->company_id = encrypt_id($company->company_id);
        $company->company_group_id = !empty($activeGroup) ? encrypt_id($activeGroup->id) : '';
        $company->company_group_name = !empty($activeGroup) ? $activeGroup->company_group_name : '';
        $company->country_id = !empty($activeCountry) ? encrypt_id($activeCountry->country_id) : '';
        $company->country_name = !empty($activeCountry) ? $activeCountry->country_name : '';
        $company->state_id = !empty($activeState) ? encrypt_id($activeState->state_id) : '';
        $company->state_name = !empty($activeState) ? $activeState->state_name : '';
        $company->city_id = !empty($activeCity) ? encrypt_id($activeCity->city_id) : '';
        $company->city_name = !empty($activeCity) ? $activeCity->city_name : '';
        $company->area_id = !empty($activeArea) ? encrypt_id($activeArea->area_id) : '';
        $company->area_name = !empty($activeArea) ? $activeArea->area_name : '';
        $company->unavailable_dependencies = $unavailableDependencies;
        $this->jsonResponse(['status' => true, 'data' => $company]);
    }

    public function delete()
    {
        $company_id = decrypt_id($this->input->post('id'));

        if (empty($company_id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid company record']);
            return;
        }

        $company = $this->Common_model->getdata('companies', [
            'company_id' => $company_id,
            'is_deleted' => 0
        ]);

        if (empty($company)) {
            $this->jsonResponse(['status' => false, 'message' => 'Company not found or already deleted']);
            return;
        }

        $deleted = $this->Comman_model->UpdateRecord(
            'companies',
            [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'company_id' => $company_id,
                'is_deleted' => 0
            ]
        );

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $company_id, 'Soft deleted company ' . $company->company_name);
            $this->jsonResponse(['status' => true, 'message' => 'Company deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Company not found or already deleted']);
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

        $activeGroup = $this->Common_model->getdata('company_groups', [
            'id' => $data['company_group_id'],
            'is_deleted' => 0
        ]);
        $activeCountry = $this->Common_model->getdata('country', [
            'country_id' => $data['country_id'],
            'is_deleted' => 0
        ]);
        $activeState = $this->activeState($data['state_id']);
        $activeCity = $this->activeCity($data['city_id']);
        $activeArea = $this->activeArea($data['area_id']);

        if (empty($activeGroup) || empty($activeCountry) || empty($activeState) || empty($activeCity) || empty($activeArea)) {
            return 'One or more selected company dependencies are unavailable';
        }

        if ((int) $activeState->country_id !== (int) $data['country_id']) {
            return 'Selected state does not belong to the selected country';
        }

        if ((int) $activeCity->country_id !== (int) $data['country_id'] || (int) $activeCity->state_id !== (int) $data['state_id']) {
            return 'Selected city does not belong to the selected state and country';
        }

        if ((int) $activeArea->state_id !== (int) $data['state_id']) {
            return 'Selected area does not belong to the selected state';
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

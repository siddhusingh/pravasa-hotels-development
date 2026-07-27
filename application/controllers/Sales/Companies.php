<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Sales_Controller.php';

class Companies extends Sales_Controller
{
    private $creditFormUploadError = '';

    public function __construct()
    {
        parent::__construct();
        $this->requireSalesRole(['Sales Executive']);

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->library('upload');
    }

    public function index()
    {
        $data['company_groups'] = $this->Common_model->getAllData(
            'company_groups',
            ['is_deleted' => 0]
        );
        $data['countries'] = $this->Common_model->getAllData(
            'country',
            ['is_deleted' => 0]
        );
        $data['states'] = $this->db
            ->select('s.state_id, s.state_name, s.country_id')
            ->from('state s')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->result();
        $data['cities'] = $this->db
            ->select('ci.city_id, ci.city_name, ci.state_id, ci.country_id')
            ->from('city ci')
            ->join('state s', 's.state_id = ci.state_id', 'inner')
            ->join('country c', 'c.country_id = ci.country_id', 'inner')
            ->where('ci.is_deleted', 0)
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->result();
        $data['areas'] = $this->db
            ->select('a.area_id, a.area_name, a.state_id')
            ->from('areas a')
            ->join('state s', 's.state_id = a.state_id', 'inner')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('a.is_deleted', 0)
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->result();

        $this->renderSalesPage('sales/companies/manage', $data);
    }

    public function get_companies_table()
    {
        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $inputs = $this->input->post();
        $draw = (int)($inputs['draw'] ?? 1);
        $start = max(0, (int)($inputs['start'] ?? 0));
        $length = (int)($inputs['length'] ?? 10);
        $length = $length > 0 ? min($length, 100) : 10;
        $search = trim((string)($inputs['search']['value'] ?? ''));

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
        $orderIndex = (int)($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'c.company_id';
        $direction = strtoupper((string)($inputs['order'][0]['dir'] ?? 'DESC'));
        $direction = in_array($direction, ['ASC', 'DESC'], true)
            ? $direction
            : 'DESC';

        $companies = $this->objdt->DTCompanies(
            $length,
            $start,
            $search,
            $order,
            $direction
        );
        $rows = [];
        $serial = $start + 1;

        foreach ($companies as $company) {
            $encryptedId = encrypt_id($company->company_id);
            $safeId = htmlspecialchars($encryptedId, ENT_QUOTES, 'UTF-8');
            $status = (string)$company->status === '1'
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $rows[] = [
                $serial++,
                $this->escape($company->company_group_name ?? '-'),
                $this->escape($company->company_name ?? '-'),
                $this->escape($company->email ?? '-'),
                $this->escape($company->area_name ?? '-'),
                $this->escape($company->mobile_number ?? '-'),
                $this->escape($company->city_name ?? '-'),
                $this->escape($company->state_name ?? '-'),
                $status,
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-company" data-record_id="' . $safeId . '" title="Edit">
                    <i class="fa fa-pencil-square-o fs-20" aria-hidden="true"></i>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-danger delete-company ms-10" data-record_id="' . $safeId . '" title="Delete">
                    <i class="fa fa-trash-o fs-20" aria-hidden="true"></i>
                </a>'
            ];
        }

        return $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTCompaniesAll(),
            'recordsFiltered' => $this->objdt->DTCompaniesFiltered($search),
            'data' => $rows
        ]);
    }

    public function save()
    {
        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $encryptedId = trim((string)$this->input->post('company_id'));
        $companyId = $encryptedId !== '' ? decrypt_id($encryptedId) : null;
        $existingCompany = null;

        if ($encryptedId !== '') {
            if (empty($companyId)) {
                return $this->jsonResponse([
                    'status' => false,
                    'message' => 'Invalid company record'
                ]);
            }

            $existingCompany = $this->Common_model->getdata('companies', [
                'company_id' => $companyId,
                'is_deleted' => 0
            ]);

            if (empty($existingCompany)) {
                return $this->jsonResponse([
                    'status' => false,
                    'message' => 'Company not found or already deleted'
                ]);
            }
        }

        $oldCreditForm = $existingCompany->credit_form_file ?? '';
        $data = $this->companyPayload(empty($companyId), $oldCreditForm);
        $validationError = $this->validateCompany($data);

        if ($validationError !== '') {
            return $this->jsonResponse([
                'status' => false,
                'message' => $validationError
            ]);
        }

        $data['credit_form_file'] = $this->uploadCreditForm($oldCreditForm);
        if ($this->creditFormUploadError !== '') {
            return $this->jsonResponse([
                'status' => false,
                'message' => $this->creditFormUploadError
            ]);
        }
        $newCreditFormUploaded = (
            $data['credit_form_file'] !== '' &&
            $data['credit_form_file'] !== $oldCreditForm
        );

        if (empty($companyId)) {
            $data['is_deleted'] = 0;
            $recordId = $this->Comman_model->insertData('companies', $data);

            if ($recordId) {
                $this->logActivity(
                    'create',
                    $recordId,
                    'Created company ' . $data['company_name']
                );
            } elseif ($newCreditFormUploaded) {
                $this->deleteCreditFormFile($data['credit_form_file']);
            }

            return $this->jsonResponse([
                'status' => (bool)$recordId,
                'message' => $recordId
                    ? 'Company added successfully'
                    : 'Failed to add company',
                'record_id' => $recordId ? encrypt_id($recordId) : ''
            ]);
        }

        $updated = $this->Comman_model->UpdateRecord(
            'companies',
            $data,
            [
                'company_id' => $companyId,
                'is_deleted' => 0
            ]
        );
        $activeCompany = $updated
            ? $this->Common_model->getdata('companies', [
                'company_id' => $companyId,
                'is_deleted' => 0
            ])
            : null;

        if (!empty($activeCompany)) {
            if ($newCreditFormUploaded && $oldCreditForm !== '') {
                $this->deleteCreditFormFile($oldCreditForm);
            }
            $this->logActivity(
                'update',
                $companyId,
                'Updated company ' . $data['company_name']
            );
        } elseif ($newCreditFormUploaded) {
            $this->deleteCreditFormFile($data['credit_form_file']);
        }

        return $this->jsonResponse([
            'status' => !empty($activeCompany),
            'message' => !empty($activeCompany)
                ? 'Company updated successfully'
                : 'Company not found or already deleted',
            'record_id' => !empty($activeCompany)
                ? encrypt_id($companyId)
                : ''
        ]);
    }

    public function getDetails()
    {
        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $companyId = decrypt_id((string)$this->input->post('company_id'));
        if (empty($companyId)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid company record'
            ]);
        }

        $company = $this->db
            ->select(
                'c.company_id, c.company_group_id, c.company_name, c.email, ' .
                'c.secondary_email, c.phone_number, c.mobile_number, c.gst_number, ' .
                'c.address, c.city_id, c.state_id, c.country_id, c.pincode, ' .
                'c.area_id, c.details, c.deals_in, c.company_creditibility, ' .
                'c.credit_form_file, c.status'
            )
            ->from('companies c')
            ->where('c.company_id', $companyId)
            ->where('c.is_deleted', 0)
            ->get()
            ->row();

        if (empty($company)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Company not found or already deleted'
            ]);
        }

        $activeGroup = $this->activeRecord(
            'company_groups',
            ['id' => $company->company_group_id]
        );
        $activeCountry = $this->activeRecord(
            'country',
            ['country_id' => $company->country_id]
        );
        $activeState = $this->activeState($company->state_id);
        $activeCity = $this->activeCity($company->city_id);
        $activeArea = $this->activeArea($company->area_id);

        if (
            !empty($activeState) &&
            (int)$activeState->country_id !== (int)$company->country_id
        ) {
            $activeState = null;
        }
        if (
            !empty($activeCity) &&
            (
                (int)$activeCity->country_id !== (int)$company->country_id ||
                (int)$activeCity->state_id !== (int)$company->state_id
            )
        ) {
            $activeCity = null;
        }
        if (
            !empty($activeArea) &&
            (int)$activeArea->state_id !== (int)$company->state_id
        ) {
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
        $company->company_group_id = !empty($activeGroup)
            ? encrypt_id($activeGroup->id)
            : '';
        $company->company_group_name = $activeGroup->company_group_name ?? '';
        $company->country_id = !empty($activeCountry)
            ? encrypt_id($activeCountry->country_id)
            : '';
        $company->country_name = $activeCountry->country_name ?? '';
        $company->state_id = !empty($activeState)
            ? encrypt_id($activeState->state_id)
            : '';
        $company->state_name = $activeState->state_name ?? '';
        $company->city_id = !empty($activeCity)
            ? encrypt_id($activeCity->city_id)
            : '';
        $company->city_name = $activeCity->city_name ?? '';
        $company->area_id = !empty($activeArea)
            ? encrypt_id($activeArea->area_id)
            : '';
        $company->area_name = $activeArea->area_name ?? '';
        $company->unavailable_dependencies = $unavailableDependencies;

        return $this->jsonResponse([
            'status' => true,
            'data' => $company
        ]);
    }

    public function delete()
    {
        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $companyId = decrypt_id((string)$this->input->post('id'));
        if (empty($companyId)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid company record'
            ]);
        }

        $company = $this->Common_model->getdata('companies', [
            'company_id' => $companyId,
            'is_deleted' => 0
        ]);

        if (empty($company)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Company not found or already deleted'
            ]);
        }

        $deleted = $this->Comman_model->UpdateRecord(
            'companies',
            [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'company_id' => $companyId,
                'is_deleted' => 0
            ]
        );

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity(
                'delete',
                $companyId,
                'Soft deleted company ' . $company->company_name
            );

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Company deleted successfully'
            ]);
        }

        return $this->jsonResponse([
            'status' => false,
            'message' => 'Company not found or already deleted'
        ]);
    }

    private function companyPayload($includeCreated, $creditFormFile)
    {
        $data = [
            'company_group_id' => $this->decryptRequired('company_group_id'),
            'company_name' => trim((string)$this->input->post('company_name', true)),
            'email' => strtolower(trim((string)$this->input->post('email', true))),
            'secondary_email' => strtolower(trim((string)$this->input->post('secondary_email', true))),
            'phone_number' => trim((string)$this->input->post('phone_number', true)),
            'mobile_number' => trim((string)$this->input->post('mobile_number', true)),
            'gst_number' => trim((string)$this->input->post('gst_number', true)),
            'address' => trim((string)$this->input->post('address', true)),
            'city_id' => $this->decryptRequired('city_id'),
            'state_id' => $this->decryptRequired('state_id'),
            'country_id' => $this->decryptRequired('country_id'),
            'pincode' => trim((string)$this->input->post('pincode', true)),
            'area_id' => $this->decryptRequired('area_id'),
            'details' => trim((string)$this->input->post('details', true)),
            'deals_in' => trim((string)$this->input->post('deals_in', true)),
            'company_creditibility' => trim((string)$this->input->post('company_creditibility', true)),
            'credit_form_file' => $creditFormFile,
            'status' => (string)$this->input->post('status', true),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateCompany(array $data)
    {
        foreach (
            ['company_group_id', 'country_id', 'state_id', 'city_id', 'area_id']
            as $field
        ) {
            if (empty($data[$field])) {
                return 'Invalid company selection';
            }
        }

        foreach (
            ['company_name', 'email', 'mobile_number', 'address']
            as $field
        ) {
            if ($data[$field] === '' || $data[$field] === null) {
                return 'Please fill all required company fields';
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address';
        }

        if (
            $data['secondary_email'] !== '' &&
            !filter_var($data['secondary_email'], FILTER_VALIDATE_EMAIL)
        ) {
            return 'Please enter a valid secondary email address';
        }

        foreach ([
            'company_name' => 190,
            'email' => 190,
            'secondary_email' => 190,
            'phone_number' => 30,
            'mobile_number' => 30,
            'gst_number' => 50,
            'pincode' => 20
        ] as $field => $maxLength) {
            if (strlen((string)$data[$field]) > $maxLength) {
                return ucfirst(str_replace('_', ' ', $field)) . ' is too long';
            }
        }

        $activeGroup = $this->activeRecord(
            'company_groups',
            ['id' => $data['company_group_id']]
        );
        $activeCountry = $this->activeRecord(
            'country',
            ['country_id' => $data['country_id']]
        );
        $activeState = $this->activeState($data['state_id']);
        $activeCity = $this->activeCity($data['city_id']);
        $activeArea = $this->activeArea($data['area_id']);

        if (
            empty($activeGroup) ||
            empty($activeCountry) ||
            empty($activeState) ||
            empty($activeCity) ||
            empty($activeArea)
        ) {
            return 'One or more selected company dependencies are unavailable';
        }

        if ((int)$activeState->country_id !== (int)$data['country_id']) {
            return 'Selected state does not belong to the selected country';
        }

        if (
            (int)$activeCity->country_id !== (int)$data['country_id'] ||
            (int)$activeCity->state_id !== (int)$data['state_id']
        ) {
            return 'Selected city does not belong to the selected state and country';
        }

        if ((int)$activeArea->state_id !== (int)$data['state_id']) {
            return 'Selected area does not belong to the selected state';
        }

        if (
            !in_array(
                $data['company_creditibility'],
                ['Credit Not Allowed', 'Credit Allowed'],
                true
            )
        ) {
            return 'Invalid company creditibility';
        }

        if (!in_array((string)$data['status'], ['0', '1'], true)) {
            return 'Invalid company status';
        }

        return '';
    }

    private function uploadCreditForm($oldFile = '')
    {
        $this->creditFormUploadError = '';
        $field = !empty($_FILES['credit_form_file']['name'])
            ? 'credit_form_file'
            : '';

        if ($field === '') {
            return $oldFile;
        }

        $uploadPath = FCPATH . 'uploads/credit_forms/';
        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
            $this->creditFormUploadError = 'Unable to prepare the credit form upload directory';
            return $oldFile;
        }

        $config = [
            'upload_path' => $uploadPath,
            'allowed_types' => 'pdf|jpg|jpeg|png|doc|docx',
            'max_size' => 5120,
            'encrypt_name' => true,
            'remove_spaces' => true
        ];
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            $this->creditFormUploadError = trim(strip_tags(
                $this->upload->display_errors('', '')
            ));
            return $oldFile;
        }

        return $this->upload->data('file_name');
    }

    private function deleteCreditFormFile($fileName)
    {
        if ($fileName === '') {
            return;
        }

        $filePath = FCPATH . 'uploads/credit_forms/' . basename($fileName);
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }

    private function activeRecord($table, array $where)
    {
        if ($this->db->field_exists('is_deleted', $table)) {
            $where['is_deleted'] = 0;
        }

        return $this->Common_model->getdata($table, $where);
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

    private function decryptRequired($field)
    {
        $token = trim((string)$this->input->post($field));
        return $token !== '' ? (decrypt_id($token) ?: null) : null;
    }

    private function logActivity($action, $recordId, $details = '')
    {
        $this->Common_model->insertActivityLog([
            'module' => 'companies',
            'record_id' => $recordId,
            'action' => $action,
            'details' => $details,
            'actor_id' => $this->salesUserId,
            'actor_name' => $this->salesUser->full_name,
            'actor_email' => $this->salesUser->email,
            'actor_role' => $this->salesUser->user_role,
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function isPostRequest()
    {
        return strtoupper($this->input->method()) === 'POST';
    }

    private function escape($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    private function jsonResponse(array $response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}

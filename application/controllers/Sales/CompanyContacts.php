<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Sales_Controller.php';

class CompanyContacts extends Sales_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireSalesRole(['Sales Executive']);

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
    }

    public function index()
    {
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
        $data['companies'] = $this->Common_model->getAllData(
            'companies',
            $this->activeWhere('companies')
        );
        $data['designations'] = $this->Common_model->getAllData(
            'designations',
            ['is_deleted' => 0]
        );

        $this->renderSalesPage('sales/company_contacts/manage', $data);
    }

    public function get_contacts_table()
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
            0 => 'cc.contact_id',
            1 => 'c.company_name',
            2 => 'cc.title',
            3 => 'cc.first_name',
            4 => 'd.designation_name',
            5 => 'cc.email',
            6 => 'cc.mobile_number',
            7 => 'cc.phone_number',
            8 => 'ci.city_name',
            9 => 's.state_name',
            10 => 'cc.status'
        ];
        $orderIndex = (int)($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'cc.contact_id';
        $direction = strtoupper((string)($inputs['order'][0]['dir'] ?? 'DESC'));
        $direction = in_array($direction, ['ASC', 'DESC'], true) ? $direction : 'DESC';

        $list = $this->objdt->DTCompanyContacts(
            $length,
            $start,
            $search,
            $order,
            $direction
        );
        $rows = [];
        $serial = $start + 1;

        foreach ($list as $row) {
            $encryptedId = encrypt_id($row->contact_id);
            $isActive = in_array((string)$row->status, ['1', 'Active'], true);
            $status = $isActive
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';
            $fullName = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''));
            $safeId = htmlspecialchars($encryptedId, ENT_QUOTES, 'UTF-8');

            $rows[] = [
                $serial++,
                htmlspecialchars($row->company_name ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->title ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($fullName !== '' ? $fullName : '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->designation_name ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->email ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->mobile_number ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->phone_number ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->city_name ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->state_name ?? '-', ENT_QUOTES, 'UTF-8'),
                $status,
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-contact" data-record_id="' . $safeId . '" title="Edit">
                    <i class="fa fa-pencil-square-o fs-20" aria-hidden="true"></i>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-danger delete-contact ms-10" data-record_id="' . $safeId . '" title="Delete">
                    <i class="fa fa-trash-o fs-20" aria-hidden="true"></i>
                </a>'
            ];
        }

        return $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTCompanyContactsAll(),
            'recordsFiltered' => $this->objdt->DTCompanyContactsFiltered($search),
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

        $encryptedId = trim((string)$this->input->post('contact_id'));
        $contactId = $encryptedId !== '' ? decrypt_id($encryptedId) : null;
        $existingContact = null;

        if ($encryptedId !== '') {
            if (empty($contactId)) {
                return $this->jsonResponse([
                    'status' => false,
                    'message' => 'Invalid contact record'
                ]);
            }

            $existingContact = $this->Common_model->getdata('company_contacts', [
                'contact_id' => $contactId,
                'is_deleted' => 0
            ]);

            if (empty($existingContact)) {
                return $this->jsonResponse([
                    'status' => false,
                    'message' => 'Contact not found or already deleted'
                ]);
            }
        }

        $data = $this->contactPayload(empty($contactId));
        $validationError = $this->validateContact($data);

        if ($validationError !== '') {
            return $this->jsonResponse([
                'status' => false,
                'message' => $validationError
            ]);
        }

        $contactName = trim($data['first_name'] . ' ' . $data['last_name']);

        if (empty($contactId)) {
            $data['is_deleted'] = 0;
            $recordId = $this->Comman_model->insertData('company_contacts', $data);

            if ($recordId) {
                $this->logActivity(
                    'create',
                    $recordId,
                    'Created company contact ' . $contactName
                );
            }

            return $this->jsonResponse([
                'status' => (bool)$recordId,
                'message' => $recordId
                    ? 'Contact added successfully'
                    : 'Failed to add contact',
                'record_id' => $recordId ? encrypt_id($recordId) : ''
            ]);
        }

        $updated = $this->Comman_model->UpdateRecord(
            'company_contacts',
            $data,
            [
                'contact_id' => $contactId,
                'is_deleted' => 0
            ]
        );
        $activeContact = $updated
            ? $this->Common_model->getdata('company_contacts', [
                'contact_id' => $contactId,
                'is_deleted' => 0
            ])
            : null;

        if (!empty($activeContact)) {
            $this->logActivity(
                'update',
                $contactId,
                'Updated company contact ' . $contactName
            );
        }

        return $this->jsonResponse([
            'status' => !empty($activeContact),
            'message' => !empty($activeContact)
                ? 'Contact updated successfully'
                : 'Contact not found or already deleted',
            'record_id' => !empty($activeContact) ? encrypt_id($contactId) : ''
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

        $contactId = decrypt_id((string)$this->input->post('contact_id'));
        if (empty($contactId)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid contact record'
            ]);
        }

        $contact = $this->db
            ->select(
                'cc.contact_id, cc.company_id, cc.title, cc.first_name, cc.last_name, ' .
                'cc.designation, cc.grade, cc.email, cc.phone_number, cc.mobile_number, ' .
                'cc.address, cc.city, cc.country, cc.state, cc.pincode, cc.date_of_birth, ' .
                'cc.date_of_anniversary, cc.status'
            )
            ->from('company_contacts cc')
            ->where('cc.contact_id', $contactId)
            ->where('cc.is_deleted', 0)
            ->get()
            ->row();

        if (empty($contact)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Contact not found or already deleted'
            ]);
        }

        $activeCompany = $this->activeRecord(
            'companies',
            ['company_id' => $contact->company_id]
        );
        $activeDesignation = !empty($contact->designation)
            ? $this->activeRecord('designations', ['id' => $contact->designation])
            : null;
        $activeCountry = !empty($contact->country)
            ? $this->activeRecord('country', ['country_id' => $contact->country])
            : null;
        $activeState = $this->activeState($contact->state);
        $activeCity = $this->activeCity($contact->city);

        $unavailableDependencies = [];
        foreach ([
            'company' => [!empty($contact->company_id), $activeCompany],
            'designation' => [!empty($contact->designation), $activeDesignation],
            'country' => [!empty($contact->country), $activeCountry],
            'state' => [!empty($contact->state), $activeState],
            'city' => [!empty($contact->city), $activeCity]
        ] as $label => $dependency) {
            if ($dependency[0] && empty($dependency[1])) {
                $unavailableDependencies[] = $label;
            }
        }

        $contact->contact_id = encrypt_id($contact->contact_id);
        $contact->company_id = !empty($activeCompany)
            ? encrypt_id($activeCompany->company_id)
            : '';
        $contact->company_name = $activeCompany->company_name ?? '';
        $contact->designation = !empty($activeDesignation)
            ? encrypt_id($activeDesignation->id)
            : '';
        $contact->designation_name = $activeDesignation->designation_name ?? '';
        $contact->country = !empty($activeCountry)
            ? encrypt_id($activeCountry->country_id)
            : '';
        $contact->country_name = $activeCountry->country_name ?? '';
        $contact->state = !empty($activeState)
            ? encrypt_id($activeState->state_id)
            : '';
        $contact->state_name = $activeState->state_name ?? '';
        $contact->city = !empty($activeCity)
            ? encrypt_id($activeCity->city_id)
            : '';
        $contact->city_name = $activeCity->city_name ?? '';
        $contact->unavailable_dependencies = $unavailableDependencies;

        return $this->jsonResponse([
            'status' => true,
            'data' => $contact
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

        $contactId = decrypt_id((string)$this->input->post('id'));
        if (empty($contactId)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid contact record'
            ]);
        }

        $contact = $this->Common_model->getdata('company_contacts', [
            'contact_id' => $contactId,
            'is_deleted' => 0
        ]);

        if (empty($contact)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Contact not found or already deleted'
            ]);
        }

        $deleted = $this->Comman_model->UpdateRecord(
            'company_contacts',
            [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->salesUser->email
            ],
            [
                'contact_id' => $contactId,
                'is_deleted' => 0
            ]
        );

        if ($deleted && $this->db->affected_rows() === 1) {
            $contactName = trim(
                ($contact->first_name ?? '') . ' ' . ($contact->last_name ?? '')
            );
            $this->logActivity(
                'delete',
                $contactId,
                $contactName !== ''
                    ? 'Soft deleted company contact ' . $contactName
                    : 'Soft deleted company contact ID ' . $contactId
            );

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Contact deleted successfully'
            ]);
        }

        return $this->jsonResponse([
            'status' => false,
            'message' => 'Contact not found or already deleted'
        ]);
    }

    private function contactPayload($includeCreated)
    {
        $data = [
            'company_id' => decrypt_id((string)$this->input->post('company_id')),
            'title' => trim((string)$this->input->post('title', true)),
            'first_name' => trim((string)$this->input->post('first_name', true)),
            'last_name' => trim((string)$this->input->post('last_name', true)),
            'designation' => $this->decryptOptional('designation_id'),
            'grade' => trim((string)$this->input->post('grade', true)),
            'email' => strtolower(trim((string)$this->input->post('email', true))),
            'phone_number' => trim((string)$this->input->post('phone_number', true)),
            'mobile_number' => trim((string)$this->input->post('mobile_number', true)),
            'address' => trim((string)$this->input->post('address', true)),
            'city' => $this->decryptOptional('city'),
            'country' => $this->decryptOptional('country_id'),
            'state' => $this->decryptOptional('state_id'),
            'pincode' => trim((string)$this->input->post('pincode', true)),
            'date_of_birth' => $this->optionalDate('date_of_birth'),
            'date_of_anniversary' => $this->optionalDate('date_of_anniversary'),
            'status' => (string)($this->input->post('status', true) ?: 'Active'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->salesUser->email
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateContact(array $data)
    {
        if (empty($data['company_id'])) {
            return 'Invalid company selection';
        }

        if (empty($this->activeRecord('companies', ['company_id' => $data['company_id']]))) {
            return 'Selected company is unavailable';
        }

        foreach (['title', 'first_name', 'last_name', 'email', 'mobile_number'] as $field) {
            if ($data[$field] === '' || $data[$field] === null) {
                return 'Please fill all required contact fields';
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address';
        }

        foreach ([
            'first_name' => 100,
            'last_name' => 100,
            'email' => 190,
            'mobile_number' => 30,
            'phone_number' => 30,
            'pincode' => 20
        ] as $field => $maxLength) {
            if (strlen((string)$data[$field]) > $maxLength) {
                return ucfirst(str_replace('_', ' ', $field)) . ' is too long';
            }
        }

        $optionalSelections = [
            'designation_id' => ['designations', 'id', 'designation', 'designation'],
            'country_id' => ['country', 'country_id', 'country', 'country']
        ];

        foreach ($optionalSelections as $postField => $selection) {
            $token = $this->input->post($postField);
            if ($token !== null && $token !== '') {
                $selectedId = $data[$selection[2]];
                if (
                    empty($selectedId) ||
                    empty($this->activeRecord($selection[0], [$selection[1] => $selectedId]))
                ) {
                    return 'Selected ' . $selection[3] . ' is unavailable';
                }
            }
        }

        $activeState = null;
        if ($this->input->post('state_id') !== null && $this->input->post('state_id') !== '') {
            $activeState = $this->activeState($data['state']);
            if (empty($activeState)) {
                return 'Selected state is unavailable';
            }

            if (
                !empty($data['country']) &&
                (int)$activeState->country_id !== (int)$data['country']
            ) {
                return 'Selected state does not belong to the selected country';
            }
        }

        if ($this->input->post('city') !== null && $this->input->post('city') !== '') {
            $activeCity = $this->activeCity($data['city']);
            if (empty($activeCity)) {
                return 'Selected city is unavailable';
            }

            if (
                !empty($data['state']) &&
                (int)$activeCity->state_id !== (int)$data['state']
            ) {
                return 'Selected city does not belong to the selected state';
            }

            if (
                !empty($data['country']) &&
                (int)$activeCity->country_id !== (int)$data['country']
            ) {
                return 'Selected city does not belong to the selected country';
            }
        }

        foreach (['date_of_birth', 'date_of_anniversary'] as $dateField) {
            if ($data[$dateField] !== null && !$this->isValidDate($data[$dateField])) {
                return 'Please enter a valid ' . str_replace('_', ' ', $dateField);
            }
        }

        if (!in_array($data['status'], ['Active', 'Inactive', '1', '0'], true)) {
            return 'Invalid contact status';
        }

        return '';
    }

    private function activeWhere($table, array $where = [])
    {
        if ($this->db->field_exists('is_deleted', $table)) {
            $where['is_deleted'] = 0;
        }

        return $where;
    }

    private function activeRecord($table, array $where)
    {
        return $this->Common_model->getdata(
            $table,
            $this->activeWhere($table, $where)
        );
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

    private function decryptOptional($field)
    {
        $token = trim((string)$this->input->post($field));
        return $token !== '' ? (decrypt_id($token) ?: null) : null;
    }

    private function optionalDate($field)
    {
        $date = trim((string)$this->input->post($field, true));
        return $date !== '' ? $date : null;
    }

    private function isValidDate($date)
    {
        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        return $parsed && $parsed->format('Y-m-d') === $date;
    }

    private function logActivity($action, $recordId, $details = '')
    {
        $this->Common_model->insertActivityLog([
            'module' => 'company_contacts',
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

    private function jsonResponse(array $response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}

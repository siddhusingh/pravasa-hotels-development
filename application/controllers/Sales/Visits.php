<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Sales_Controller.php';

class Visits extends Sales_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireSalesRole(['Sales Executive', 'Sales Manager']);

        $this->load->model('LeadModel');
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->helper('secure');
    }

    public function index()
    {
        $data['visit_executives'] = $this->db
            ->select('id, full_name, status')
            ->from('sales_users')
            ->where('user_role', 'Sales Executive')
            ->where('is_deleted', 0)
            ->order_by('full_name', 'ASC')
            ->get()
            ->result();
        $data['visit_types'] = $this->visitFilterValues('visit_type');
        $data['visit_modes'] = $this->visitFilterValues('visit_mode');
        $data['visit_companies'] = $this->db
            ->select('company_id, company_name')
            ->from('companies')
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->order_by('company_name', 'ASC')
            ->get()
            ->result();
        $data['is_visit_manager'] = $this->isManager();
        $data['sales_visit_success'] = $this->session->flashdata(
            'sales_visit_success'
        );
        $this->session->unset_userdata('sales_visit_success');

        $view = $data['is_visit_manager']
            ? 'sales/sales_visits/manager-sales-visits'
            : 'sales/sales_visits/history';

        $this->renderSalesPage($view, $data);
    }

    public function table()
    {
        if (!$this->isPostRequest()) {
            show_error('Method Not Allowed', 405);
        }

        $inputs = $this->input->post();
        $draw = (int)($inputs['draw'] ?? 1);
        $start = max(0, (int)($inputs['start'] ?? 0));
        $length = (int)($inputs['length'] ?? 10);
        $length = $length > 0 ? min($length, 100) : 10;
        $search = trim((string)($inputs['search']['value'] ?? ''));
        $executiveId = $this->managerExecutiveFilter(
            $inputs['executive_id'] ?? ''
        );
        $visitType = $this->managerTextFilter(
            $inputs['visit_type'] ?? ''
        );
        $visitMode = $this->managerTextFilter(
            $inputs['visit_mode'] ?? ''
        );
        $companyId = $this->managerCompanyFilter(
            $inputs['company_id'] ?? ''
        );
        $createdStartDate = $this->managerDateFilter(
            $inputs['created_start_date'] ?? ''
        );
        $createdEndDate = $this->managerDateFilter(
            $inputs['created_end_date'] ?? ''
        );
        $columns = [
            0 => 'sv.visit_id',
            1 => 'c.company_name',
            2 => 'cc.first_name',
            4 => 'sv.visit_type',
            5 => 'sv.visit_mode',
            6 => 'su.full_name',
            7 => 'sv.report_date',
            8 => 'sv.created_at'
        ];
        $orderIndex = (int)($inputs['order'][0]['column'] ?? 7);
        $orderColumn = $columns[$orderIndex] ?? 'sv.report_date';
        $orderDirection = strtoupper(
            (string)($inputs['order'][0]['dir'] ?? 'DESC')
        );
        if (!in_array($orderDirection, ['ASC', 'DESC'], true)) {
            $orderDirection = 'DESC';
        }

        $this->visitTableQuery(
            '',
            null,
            null,
            null,
            null,
            null,
            null
        );
        $recordsTotal = (int)$this->db->count_all_results();

        $this->visitTableQuery(
            $search,
            $executiveId,
            $visitType,
            $visitMode,
            $companyId,
            $createdStartDate,
            $createdEndDate
        );
        $recordsFiltered = (int)$this->db->count_all_results();

        $this->visitTableQuery(
            $search,
            $executiveId,
            $visitType,
            $visitMode,
            $companyId,
            $createdStartDate,
            $createdEndDate
        );
        $visits = $this->db
            ->select(
                'sv.visit_id, sv.report_date, sv.visit_type, sv.visit_mode, ' .
                'sv.agenda, sv.discussion_summary, sv.created_at, ' .
                'c.company_name, cc.first_name, cc.last_name, ' .
                'su.full_name AS sales_user_name'
            )
            ->order_by($orderColumn, $orderDirection)
            ->order_by('sv.visit_id', 'DESC')
            ->limit($length, $start)
            ->get()
            ->result();
        $tableData = [];

        foreach ($visits as $index => $visit) {
            $personMet = trim(
                ($visit->first_name ?? '') . ' ' .
                ($visit->last_name ?? '')
            );
            $discussion =
                '<div><strong>Agenda:</strong> ' .
                html_escape($visit->agenda ?? '-') .
                '</div><div class="mt-1"><strong>Discussion:</strong> ' .
                nl2br(html_escape($visit->discussion_summary ?? '-')) .
                '</div>';

            $tableData[] = [
                $start + $index + 1,
                html_escape($visit->company_name ?? '-'),
                html_escape($personMet !== '' ? $personMet : '-'),
                $discussion,
                html_escape($visit->visit_type ?? '-'),
                html_escape($visit->visit_mode ?? '-'),
                html_escape($visit->sales_user_name ?? '-'),
                !empty($visit->report_date)
                    ? date('d-m-Y', strtotime($visit->report_date))
                    : '-',
                !empty($visit->created_at)
                    ? date('d-m-Y h:i A', strtotime($visit->created_at))
                    : '-',
                $this->visitActionsHtml($visit)
            ];
        }

        return $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $tableData
        ]);
    }

    public function getCalendarVisits()
    {
        $executiveId = $this->managerExecutiveFilter(
            $this->input->get('executive_id')
        );
        $visitType = $this->managerTextFilter(
            $this->input->get('visit_type')
        );
        $visitMode = $this->managerTextFilter(
            $this->input->get('visit_mode')
        );
        $companyId = $this->managerCompanyFilter(
            $this->input->get('company_id')
        );
        $createdStartDate = $this->managerDateFilter(
            $this->input->get('created_start_date')
        );
        $createdEndDate = $this->managerDateFilter(
            $this->input->get('created_end_date')
        );
        $query = $this->db
            ->select('sv.visit_id, sv.report_date, c.company_name')
            ->from('sales_visits sv')
            ->join('companies c', 'c.company_id = sv.company_id', 'left')
            ->where('sv.status', 1)
            ->where('sv.is_deleted', 0)
            ->where('sv.creator_user_role', 'Sales Executive');

        if ($this->isManager()) {
            if ($executiveId !== null) {
                $query->where('sv.user_id', $executiveId);
            }
            if ($visitType !== null) {
                $query->where('sv.visit_type', $visitType);
            }
            if ($visitMode !== null) {
                $query->where('sv.visit_mode', $visitMode);
            }
            if ($companyId !== null) {
                $query->where('sv.company_id', $companyId);
            }
            if ($createdStartDate !== null) {
                $query->where(
                    'sv.created_at >=',
                    $createdStartDate . ' 00:00:00'
                );
            }
            if ($createdEndDate !== null) {
                $query->where(
                    'sv.created_at <=',
                    $createdEndDate . ' 23:59:59'
                );
            }
        } else {
            $query->where('sv.user_id', $this->salesUserId);
        }

        $visits = $query
            ->order_by('sv.report_date', 'ASC')
            ->get()
            ->result();

        $events = [];
        foreach ($visits as $visit) {
            if (empty($visit->report_date)) {
                continue;
            }

            $events[] = [
                'id' => encrypt_id($visit->visit_id),
                'title' => 'Visit | ' . ($visit->company_name ?: 'Company'),
                'start' => date('Y-m-d', strtotime($visit->report_date)),
                'color' => '#00a65a'
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($events));
    }

    public function getVisitDetails()
    {
        $encryptedId = trim((string)$this->input->get('visit_id'));
        $visitId = $encryptedId !== '' ? decrypt_id($encryptedId) : null;

        if (empty($visitId)) {
            return $this->output
                ->set_status_header(400)
                ->set_output(
                    '<div class="alert alert-danger mb-0">' .
                    'Invalid sales visit.' .
                    '</div>'
                );
        }

        $this->db
            ->select(
                'sv.*, c.company_name, cc.first_name, cc.last_name, ' .
                'su.full_name AS sales_user_name'
            )
            ->from('sales_visits sv')
            ->join('companies c', 'c.company_id = sv.company_id', 'left')
            ->join(
                'company_contacts cc',
                'cc.contact_id = sv.person_met',
                'left'
            )
            ->join('sales_users su', 'su.id = sv.user_id', 'left')
            ->where('sv.visit_id', $visitId)
            ->where('sv.status', 1)
            ->where('sv.is_deleted', 0)
            ->where('sv.creator_user_role', 'Sales Executive');

        if (!$this->isManager()) {
            $this->db->where('sv.user_id', $this->salesUserId);
        }

        $data['visit'] = $this->db->get()->row();

        if (empty($data['visit'])) {
            return $this->output
                ->set_status_header(404)
                ->set_output(
                    '<div class="alert alert-warning mb-0">' .
                    'Sales visit not found.' .
                    '</div>'
                );
        }

        return $this->load->view(
            'sales/sales_visits/visit_details_modal',
            $data
        );
    }

    public function add()
    {
        $this->requireSalesRole(['Sales Executive']);

        $data['departments'] = $this->Common_model->getAllData(
            'departments',
            ['is_deleted' => 0]
        );
        $data['hotel_admin'] = $this->Common_model->getAllData(
            'hotel_admin',
            ['is_deleted' => 0]
        );
        $data['companies'] = $this->Common_model->getAllData(
            'companies',
            ['status' => 1, 'is_deleted' => 0]
        );
        $data['company_groups'] = $this->Common_model->getAllData(
            'company_groups',
            ['is_deleted' => 0]
        );
        $data['designations'] = $this->Common_model->getAllData(
            'designations',
            ['is_deleted' => 0]
        );
        $data['countries'] = $this->Common_model->getAllData(
            'country',
            ['is_deleted' => 0]
        );
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
        $data['areas'] = $this->db
            ->select(
                'a.*, su1.full_name AS primary_user_name, ' .
                'su2.full_name AS secondary_user_name, s.state_name'
            )
            ->from('areas a')
            ->join('sales_users su1', 'su1.id = a.primary_user_id', 'left')
            ->join('sales_users su2', 'su2.id = a.secondary_user_id', 'left')
            ->join('state s', 's.state_id = a.state_id', 'inner')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('a.is_deleted', 0)
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->result();
        $data['roomtype'] = $this->Common_model->getAllData(
            'roomtype',
            ['is_deleted' => 0]
        );
        $data['travel_modes'] = $this->Common_model->getAllData(
            'travel_modes',
            ['is_deleted' => 0]
        );
        $this->renderSalesPage('sales/sales_visits/add', $data);
    }

    public function insert()
    {
        $this->requireSalesRole(['Sales Executive']);

        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $validationError = $this->validateBasicFields();
        if ($validationError !== '') {
            return $this->jsonResponse([
                'status' => false,
                'message' => $validationError
            ]);
        }

        $propertyId = $this->decryptRequired('property');
        $departmentId = $this->decryptRequired('type');
        $companyId = $this->decryptRequired('company_id');
        $contactId = $this->decryptRequired('person_met');
        $travelModeId = $this->decryptOptional('travel_mode');

        $hotel = $this->activeRecord(
            'hotel_admin',
            ['hotel_id' => $propertyId]
        );
        $department = $this->activeRecord(
            'departments',
            ['department_id' => $departmentId]
        );
        $company = $this->activeRecord(
            'companies',
            ['company_id' => $companyId, 'status' => 1]
        );
        $contact = $this->activeRecord(
            'company_contacts',
            [
                'contact_id' => $contactId,
                'company_id' => $companyId,
                'status' => 'Active'
            ]
        );
        $travelMode = empty($travelModeId)
            ? true
            : $this->activeRecord(
                'travel_modes',
                ['id' => $travelModeId]
            );

        if (
            empty($propertyId) ||
            empty($departmentId) ||
            empty($companyId) ||
            empty($contactId) ||
            empty($hotel) ||
            empty($department) ||
            empty($company) ||
            empty($contact) ||
            empty($travelMode)
        ) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid sales visit selection'
            ]);
        }

        $stage = trim((string)$this->input->post('disposition', true));
        $departmentName = $this->normalizeDepartment(
            $department->department_name ?? ''
        );
        $dynamicError = $this->validateDynamicFields(
            $stage,
            $departmentName,
            $propertyId,
            $departmentId
        );

        if ($dynamicError !== '') {
            return $this->jsonResponse([
                'status' => false,
                'message' => $dynamicError
            ]);
        }

        $attachment = $this->uploadAttachment();
        if (!$attachment['success']) {
            return $this->jsonResponse([
                'status' => false,
                'message' => $attachment['message']
            ]);
        }

        $attachmentPath = $attachment['path'];
        $contactName = trim(
            ($contact->first_name ?? '') . ' ' . ($contact->last_name ?? '')
        );
        $status = trim((string)$this->input->post('status', true));
        $now = date('Y-m-d H:i:s');
        $escalationHours = max(
            0,
            (float)($department->escalation_level_1 ?? 0)
        );
        $followUpTime = date(
            'Y-m-d H:i:s',
            time() + (int)round($escalationHours * 3600)
        );

        $leadData = [
            'user_name' => $contactName,
            'phone_number' => $contact->mobile_number,
            'email' => $contact->email,
            'date' => date('d-m-Y'),
            'time' => date('H:i:s'),
            'user_channel' => 'Sales Visit',
            'property' => $propertyId,
            'type' => $departmentId,
            'status' => $status,
            'disposition' => $stage,
            'query' => trim((string)$this->input->post('discussion_summary', true)),
            'remark' => trim((string)$this->input->post('remarks', true)),
            'last_query' => trim((string)$this->input->post('discussion_summary', true)),
            'lead_type' => trim((string)$this->input->post('lead_type', true)),
            'city' => $hotel->city_id ?? null,
            'ip_address' => $this->input->ip_address(),
            'correlation_id' => uniqid('sales-visit-', true),
            'follow_up_time' => $followUpTime,
            'follow_up_level' => 1,
            'esc_follow_up_level' => 0,
            'meal_plan' => 0,
            'confirmation_number' => '',
            'template_name' => 'Phone',
            'created_by' => $this->salesUserId,
            'creator_user_role' => $this->salesUser->user_role,
            'created_at' => $now
        ];

        if ($status === 'Closed') {
            $leadData['completed_time'] = $now;
        } else {
            $leadData['responded_time'] = $now;
        }

        $this->addDynamicLeadData(
            $leadData,
            $stage,
            $departmentName
        );

        $kmsRun = $this->numericValue('kms_run');
        $ratePerKm = $this->numericValue('rate_per_km');
        $parking = $this->numericValue('parking_charges');
        $lunch = $this->numericValue('lunch');
        $entertainment = $this->numericValue('entertainment');
        $totalAmount = ($kmsRun * $ratePerKm) +
            $parking +
            $lunch +
            $entertainment;

        $this->db->trans_begin();

        $leadId = $this->LeadModel->insert_lead($leadData);
        $visitId = 0;

        if ($leadId) {
            $visitData = [
                'user_id' => $this->salesUserId,
                'report_date' => trim((string)$this->input->post('report_date', true)),
                'follow_up_1_date' => trim((string)$this->input->post('follow_up_1_date', true)),
                'follow_up_2_date' => trim((string)$this->input->post('follow_up_2_date', true)),
                'visit_type' => trim((string)$this->input->post('visit_type', true)),
                'visit_mode' => trim((string)$this->input->post('visit_mode', true)),
                'company_id' => $companyId,
                'person_met' => $contactId,
                'agenda' => trim((string)$this->input->post('agenda', true)),
                'discussion_summary' => trim((string)$this->input->post('discussion_summary', true)),
                'conclusion' => trim((string)$this->input->post('conclusion', true)),
                'area_covered' => trim((string)$this->input->post('area_covered', true)),
                'travel_mode' => $travelModeId,
                'kms_run' => $kmsRun,
                'rate_per_km' => $ratePerKm,
                'parking_charges' => $parking,
                'lunch' => $lunch,
                'entertainment' => $entertainment,
                'total_amount' => $totalAmount,
                'attachment_image' => $attachmentPath ?: null,
                'latitude' => $this->optionalCoordinate('visit_latitude'),
                'longitude' => $this->optionalCoordinate('visit_longitude'),
                'location_details' => $this->optionalText('visit_location_details'),
                'property' => $propertyId,
                'type' => $departmentId,
                'remarks' => trim((string)$this->input->post('remarks', true)),
                'lead_id_againts_visit' => $leadId,
                'creator_user_role' => $this->salesUser->user_role,
                'status' => 1,
                'is_deleted' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ];

            if ($this->db->insert('sales_visits', $visitData)) {
                $visitId = (int)$this->db->insert_id();
            }
        }

        if (!$leadId || !$visitId || $this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->deleteUploadedAttachment($attachmentPath);

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Unable to create sales visit.'
            ]);
        }

        $this->db->trans_commit();
        $this->logActivity(
            'create',
            $visitId,
            'Created sales visit for company ID ' . $companyId
        );
        $this->session->set_flashdata(
            'sales_visit_success',
            'Sales visit created successfully.'
        );
        $this->triggerLeadEmail(
            $leadId,
            (string)$contact->mobile_number
        );

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Sales visit created successfully.',
            'record_id' => encrypt_id($visitId)
        ]);
    }

    public function edit($encryptedVisitId)
    {
        $this->requireSalesRole(['Sales Executive']);

        $visitId = decrypt_id(trim((string)$encryptedVisitId));
        if (empty($visitId)) {
            show_404();
            return;
        }

        $data['sales_visit'] = $this->db
            ->select(
                'l.*, sv.*, l.status AS lead_status, c.company_name, ' .
                'cc.first_name, cc.last_name, ' .
                'su.full_name AS sales_user_name'
            )
            ->from('sales_visits sv')
            ->join('companies c', 'c.company_id = sv.company_id', 'left')
            ->join(
                'company_contacts cc',
                'cc.contact_id = sv.person_met',
                'left'
            )
            ->join('sales_users su', 'su.id = sv.user_id', 'left')
            ->join(
                'leads l',
                'l.id = sv.lead_id_againts_visit',
                'left'
            )
            ->where('sv.visit_id', $visitId)
            ->where('sv.user_id', $this->salesUserId)
            ->where('sv.status', 1)
            ->where('sv.is_deleted', 0)
            ->get()
            ->row();

        if (empty($data['sales_visit'])) {
            show_404();
            return;
        }

        $data['departments'] = $this->Common_model->getAllData(
            'departments',
            ['is_deleted' => 0]
        );
        $data['hotel_admin'] = $this->Common_model->getAllData(
            'hotel_admin',
            ['is_deleted' => 0]
        );
        $data['companies'] = $this->Common_model->getAllData(
            'companies',
            ['status' => 1, 'is_deleted' => 0]
        );
        $data['roomtype'] = $this->Common_model->getAllData(
            'roomtype',
            ['is_deleted' => 0]
        );
        $data['travel_modes'] = $this->Common_model->getAllData(
            'travel_modes',
            ['is_deleted' => 0]
        );

        $this->renderSalesPage('sales/sales_visits/edit', $data);
    }

    public function update($encryptedVisitId)
    {
        $this->requireSalesRole(['Sales Executive']);

        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $validationError = $this->validateBasicFields();
        if ($validationError !== '') {
            return $this->jsonResponse([
                'status' => false,
                'message' => $validationError
            ]);
        }

        $visitId = decrypt_id(trim((string)$encryptedVisitId));
        $salesVisit = $this->db
            ->where('visit_id', $visitId)
            ->where('user_id', $this->salesUserId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get('sales_visits')
            ->row();

        if (empty($visitId) || empty($salesVisit)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid sales visit'
            ]);
        }

        $propertyId = $this->decryptRequired('property');
        $departmentId = $this->decryptRequired('type');
        $companyId = $this->decryptRequired('company_id');
        $contactId = $this->decryptRequired('person_met');
        $travelModeId = $this->decryptOptional('travel_mode');

        $hotel = $this->activeRecord(
            'hotel_admin',
            ['hotel_id' => $propertyId]
        );
        $department = $this->activeRecord(
            'departments',
            ['department_id' => $departmentId]
        );
        $company = $this->activeRecord(
            'companies',
            ['company_id' => $companyId, 'status' => 1]
        );
        $contact = $this->activeRecord(
            'company_contacts',
            [
                'contact_id' => $contactId,
                'company_id' => $companyId,
                'status' => 'Active'
            ]
        );
        $travelMode = empty($travelModeId)
            ? true
            : $this->activeRecord(
                'travel_modes',
                ['id' => $travelModeId]
            );

        if (
            empty($propertyId) ||
            empty($departmentId) ||
            empty($companyId) ||
            empty($contactId) ||
            empty($hotel) ||
            empty($department) ||
            empty($company) ||
            empty($contact) ||
            empty($travelMode)
        ) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid sales visit selection'
            ]);
        }

        $stage = trim((string)$this->input->post('disposition', true));
        $departmentName = $this->normalizeDepartment(
            $department->department_name ?? ''
        );
        $dynamicError = $this->validateDynamicFields(
            $stage,
            $departmentName,
            $propertyId,
            $departmentId
        );
        if ($dynamicError !== '') {
            return $this->jsonResponse([
                'status' => false,
                'message' => $dynamicError
            ]);
        }

        $attachment = $this->uploadAttachment();
        if (!$attachment['success']) {
            return $this->jsonResponse([
                'status' => false,
                'message' => $attachment['message']
            ]);
        }

        $status = trim((string)$this->input->post('status', true));
        $now = date('Y-m-d H:i:s');
        $contactName = trim(
            ($contact->first_name ?? '') . ' ' . ($contact->last_name ?? '')
        );
        $escalationHours = max(
            0,
            (float)($department->escalation_level_1 ?? 0)
        );

        $leadData = [
            'user_name' => $contactName,
            'phone_number' => $contact->mobile_number,
            'email' => $contact->email,
            'property' => $propertyId,
            'type' => $departmentId,
            'status' => $status,
            'disposition' => $stage,
            'query' => trim(
                (string)$this->input->post('discussion_summary', true)
            ),
            'last_query' => trim(
                (string)$this->input->post('discussion_summary', true)
            ),
            'remark' => trim((string)$this->input->post('remarks', true)),
            'lead_type' => trim(
                (string)$this->input->post('lead_type', true)
            ),
            'city' => $hotel->city_id ?? null,
            'follow_up_time' => date(
                'Y-m-d H:i:s',
                time() + (int)round($escalationHours * 3600)
            ),
            'follow_up_level' => 1,
        ];

        if ($status === 'Closed') {
            $leadData['completed_time'] = $now;
        } else {
            $leadData['responded_time'] = $now;
        }
        $this->addDynamicLeadData($leadData, $stage, $departmentName);

        $kmsRun = $this->numericValue('kms_run');
        $ratePerKm = $this->numericValue('rate_per_km');
        $parking = $this->numericValue('parking_charges');
        $lunch = $this->numericValue('lunch');
        $entertainment = $this->numericValue('entertainment');
        $totalAmount = ($kmsRun * $ratePerKm) +
            $parking +
            $lunch +
            $entertainment;

        $visitData = [
            'report_date' => trim(
                (string)$this->input->post('report_date', true)
            ),
            'follow_up_1_date' => trim(
                (string)$this->input->post('follow_up_1_date', true)
            ),
            'follow_up_2_date' => trim(
                (string)$this->input->post('follow_up_2_date', true)
            ),
            'visit_type' => trim(
                (string)$this->input->post('visit_type', true)
            ),
            'visit_mode' => trim(
                (string)$this->input->post('visit_mode', true)
            ),
            'company_id' => $companyId,
            'person_met' => $contactId,
            'agenda' => trim((string)$this->input->post('agenda', true)),
            'discussion_summary' => trim(
                (string)$this->input->post('discussion_summary', true)
            ),
            'conclusion' => trim(
                (string)$this->input->post('conclusion', true)
            ),
            'area_covered' => trim(
                (string)$this->input->post('area_covered', true)
            ),
            'travel_mode' => $travelModeId,
            'kms_run' => $kmsRun,
            'rate_per_km' => $ratePerKm,
            'parking_charges' => $parking,
            'lunch' => $lunch,
            'entertainment' => $entertainment,
            'total_amount' => $totalAmount,
            'latitude' => $this->optionalCoordinate('visit_latitude'),
            'longitude' => $this->optionalCoordinate('visit_longitude'),
            'location_details' => $this->optionalText(
                'visit_location_details'
            ),
            'property' => $propertyId,
            'type' => $departmentId,
            'remarks' => trim(
                (string)$this->input->post('remarks', true)
            ),
            'updated_at' => $now
        ];

        if ($attachment['path'] !== '') {
            $visitData['attachment_image'] = $attachment['path'];
        }

        $this->db->trans_begin();
        $leadUpdated = $this->db
            ->where('id', $salesVisit->lead_id_againts_visit)
            ->update('leads', $leadData);
        $visitUpdated = $this->db
            ->where('visit_id', $visitId)
            ->where('user_id', $this->salesUserId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->update('sales_visits', $visitData);

        if (
            !$leadUpdated ||
            !$visitUpdated ||
            $this->db->trans_status() === false
        ) {
            $this->db->trans_rollback();
            $this->deleteUploadedAttachment($attachment['path']);

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Unable to update sales visit'
            ]);
        }

        $this->db->trans_commit();
        if (
            $attachment['path'] !== '' &&
            !empty($salesVisit->attachment_image)
        ) {
            $this->deleteUploadedAttachment($salesVisit->attachment_image);
        }

        $this->logActivity(
            'update',
            $visitId,
            'Updated sales visit for company ID ' . $companyId
        );
        $this->session->set_flashdata(
            'sales_visit_success',
            'Sales visit updated successfully.'
        );

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Sales visit & lead updated successfully'
        ]);
    }

    public function delete()
    {
        $this->requireSalesRole(['Sales Executive']);

        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $visitId = decrypt_id(trim((string)$this->input->post('id')));
        if (empty($visitId)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Invalid visit ID'
            ]);
        }

        $visit = $this->db
            ->where('visit_id', $visitId)
            ->where('user_id', $this->salesUserId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get('sales_visits')
            ->row();

        if (empty($visit)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Sales visit not found or already deleted'
            ]);
        }

        $deleted = $this->db
            ->where('visit_id', $visitId)
            ->where('user_id', $this->salesUserId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->update('sales_visits', [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if (!$deleted || $this->db->affected_rows() !== 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => $deleted
                    ? 'Sales visit not found or already deleted'
                    : 'Unable to delete sales visit'
            ]);
        }

        $this->logActivity(
            'delete',
            $visitId,
            'Soft deleted sales visit for company ID ' . $visit->company_id
        );

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Sales visit deleted successfully'
        ]);
    }

    public function get_company_contacts()
    {
        $this->requireSalesRole(['Sales Executive']);

        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Method not allowed'
            ]);
        }

        $companyId = $this->decryptRequired('company_id');
        $selectedContactToken = trim(
            (string)$this->input->post('selected_contact_id')
        );
        $selectedContactId = $selectedContactToken !== ''
            ? decrypt_id($selectedContactToken)
            : null;
        $company = $this->activeRecord(
            'companies',
            ['company_id' => $companyId, 'status' => 1]
        );

        if (empty($companyId) || empty($company)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid company'
            ]);
        }

        $contacts = $this->db
            ->select('contact_id, first_name, last_name, mobile_number')
            ->from('company_contacts')
            ->where('company_id', $companyId)
            ->where('is_deleted', 0)
            ->where('status', 'Active')
            ->order_by('first_name', 'ASC')
            ->get()
            ->result();

        foreach ($contacts as $contact) {
            $contact->contact_id = (
                !empty($selectedContactId) &&
                (int)$selectedContactId === (int)$contact->contact_id
            )
                ? $selectedContactToken
                : encrypt_id($contact->contact_id);
        }

        return $this->jsonResponse([
            'status' => !empty($contacts) ? 'success' : 'error',
            'message' => !empty($contacts) ? '' : 'No contacts found',
            'data' => $contacts
        ]);
    }

    public function get_restaurants()
    {
        $this->requireSalesRole(['Sales Executive']);

        if (!$this->isPostRequest()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Method not allowed',
                'data' => []
            ]);
        }

        $hotelId = $this->decodeFlexibleId($this->input->post('hotel_id'));
        $hotel = $this->activeRecord(
            'hotel_admin',
            ['hotel_id' => $hotelId]
        );

        if (empty($hotel)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid hotel',
                'data' => []
            ]);
        }

        $restaurants = $this->db
            ->select('id, restaurant_name')
            ->from('hotel_restaurants')
            ->where('hotel_id', $hotelId)
            ->where('status', 1)
            ->get()
            ->result();

        return $this->jsonResponse([
            'status' => 'success',
            'data' => $restaurants
        ]);
    }

    public function get_slot_types()
    {
        $this->requireSalesRole(['Sales Executive']);

        $slots = $this->db
            ->select('id, slot_name, start_time, end_time')
            ->from('slot_types')
            ->where('status', 1)
            ->order_by('start_time', 'ASC')
            ->get()
            ->result();

        return $this->jsonResponse([
            'status' => 'success',
            'data' => $slots
        ]);
    }

    private function visitTableQuery(
        $search,
        $executiveId,
        $visitType,
        $visitMode,
        $companyId,
        $createdStartDate,
        $createdEndDate
    )
    {
        $this->db
            ->from('sales_visits sv')
            ->join('companies c', 'c.company_id = sv.company_id', 'left')
            ->join(
                'company_contacts cc',
                'cc.contact_id = sv.person_met',
                'left'
            )
            ->join('sales_users su', 'su.id = sv.user_id', 'left')
            ->where('sv.status', 1)
            ->where('sv.is_deleted', 0)
            ->where('sv.creator_user_role', 'Sales Executive');

        if ($this->isManager()) {
            if ($executiveId !== null) {
                $this->db->where('sv.user_id', $executiveId);
            }
            if ($visitType !== null) {
                $this->db->where('sv.visit_type', $visitType);
            }
            if ($visitMode !== null) {
                $this->db->where('sv.visit_mode', $visitMode);
            }
            if ($companyId !== null) {
                $this->db->where('sv.company_id', $companyId);
            }
            if ($createdStartDate !== null) {
                $this->db->where(
                    'sv.created_at >=',
                    $createdStartDate . ' 00:00:00'
                );
            }
            if ($createdEndDate !== null) {
                $this->db->where(
                    'sv.created_at <=',
                    $createdEndDate . ' 23:59:59'
                );
            }
        } else {
            $this->db->where('sv.user_id', $this->salesUserId);
        }

        if ($search !== '') {
            $this->db
                ->group_start()
                ->like('c.company_name', $search)
                ->or_like('cc.first_name', $search)
                ->or_like('cc.last_name', $search)
                ->or_like('sv.agenda', $search)
                ->or_like('sv.discussion_summary', $search)
                ->or_like('sv.visit_type', $search)
                ->or_like('sv.visit_mode', $search)
                ->or_like('su.full_name', $search)
                ->or_like('sv.report_date', $search)
                ->or_like('sv.created_at', $search)
                ->group_end();
        }

        return $this->db;
    }

    private function managerExecutiveFilter($encryptedId)
    {
        if (!$this->isManager()) {
            return null;
        }

        $encryptedId = trim((string)$encryptedId);
        if ($encryptedId === '') {
            return null;
        }

        $executiveId = decrypt_id($encryptedId);
        if (empty($executiveId)) {
            return 0;
        }

        $exists = $this->db
            ->where('id', (int)$executiveId)
            ->where('user_role', 'Sales Executive')
            ->where('is_deleted', 0)
            ->count_all_results('sales_users');

        return $exists > 0 ? (int)$executiveId : 0;
    }

    private function managerCompanyFilter($encryptedId)
    {
        if (!$this->isManager()) {
            return null;
        }

        $encryptedId = trim((string)$encryptedId);
        if ($encryptedId === '') {
            return null;
        }

        $companyId = decrypt_id($encryptedId);
        if (empty($companyId)) {
            return 0;
        }

        $exists = $this->db
            ->where('company_id', (int)$companyId)
            ->where('is_deleted', 0)
            ->count_all_results('companies');

        return $exists > 0 ? (int)$companyId : 0;
    }

    private function managerTextFilter($value)
    {
        if (!$this->isManager()) {
            return null;
        }

        $value = trim((string)$value);
        return $value !== '' ? mb_substr($value, 0, 100) : null;
    }

    private function managerDateFilter($value)
    {
        if (!$this->isManager()) {
            return null;
        }

        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }

        $date = DateTime::createFromFormat('!Y-m-d', $value);
        $errors = DateTime::getLastErrors();
        if (
            $date === false ||
            ($errors !== false &&
                ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) ||
            $date->format('Y-m-d') !== $value
        ) {
            return null;
        }

        return $value;
    }

    private function visitFilterValues($column)
    {
        $allowedColumns = ['visit_type', 'visit_mode'];
        if (!in_array($column, $allowedColumns, true)) {
            return [];
        }

        $rows = $this->db
            ->select($column)
            ->distinct()
            ->from('sales_visits')
            ->where('creator_user_role', 'Sales Executive')
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->where($column . ' IS NOT NULL', null, false)
            ->where($column . ' !=', '')
            ->order_by($column, 'ASC')
            ->get()
            ->result();

        return array_values(array_filter(array_map(
            static function ($row) use ($column) {
                return $row->{$column} ?? null;
            },
            $rows
        )));
    }

    private function visitActionsHtml($visit)
    {
        $encryptedId = html_escape(encrypt_id($visit->visit_id));

        if ($this->isManager()) {
            return '<a href="javascript:void(0)" ' .
                'class="text-fade hover-primary view-visit" ' .
                'data-record_id="' . $encryptedId . '" ' .
                'title="View Sales Visit" aria-label="View Sales Visit">' .
                '<i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>';
        }

        return '<a href="' .
            base_url('sales/visits/edit/' . $encryptedId) .
            '" class="text-fade hover-primary" ' .
            'title="Edit Sales Visit" aria-label="Edit Sales Visit">' .
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" ' .
            'height="24" viewBox="0 0 24 24" fill="none" ' .
            'stroke="currentColor" stroke-width="2" ' .
            'stroke-linecap="round" stroke-linejoin="round" ' .
            'aria-hidden="true"><polygon points="' .
            '16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg></a> ' .
            '<a href="javascript:void(0)" ' .
            'class="text-fade hover-primary delete-visit ml-2" ' .
            'data-record_id="' . $encryptedId . '" ' .
            'title="Delete Sales Visit" aria-label="Delete Sales Visit">' .
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" ' .
            'height="24" viewBox="0 0 24 24" fill="none" ' .
            'stroke="currentColor" stroke-width="2" ' .
            'stroke-linecap="round" stroke-linejoin="round" ' .
            'aria-hidden="true"><polyline points="3 6 5 6 21 6">' .
            '</polyline><path d="M19 6v14a2 2 0 0 1-2 2H7' .
            'a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 ' .
            '2 2v2"></path></svg></a>';
    }

    private function isManager()
    {
        return $this->salesUser->user_role === 'Sales Manager';
    }

    private function validateBasicFields()
    {
        foreach (
            ['report_date', 'follow_up_1_date', 'follow_up_2_date']
            as $field
        ) {
            $date = trim((string)$this->input->post($field, true));
            $parsed = DateTime::createFromFormat('Y-m-d', $date);
            if (
                $date === '' ||
                !$parsed ||
                $parsed->format('Y-m-d') !== $date
            ) {
                return 'Please enter valid visit and follow-up dates';
            }
        }

        foreach (
            ['property', 'type', 'company_id', 'person_met']
            as $field
        ) {
            if (trim((string)$this->input->post($field)) === '') {
                return 'Please fill all required sales visit selections';
            }
        }

        if (
            trim((string)$this->input->post('discussion_summary', true)) === ''
        ) {
            return 'Discussion summary is required';
        }

        $allowedVisitTypes = [
            'Relationship Visit',
            'Follow-up Visit',
            'Support & Service'
        ];
        if (
            !in_array(
                trim((string)$this->input->post('visit_type', true)),
                $allowedVisitTypes,
                true
            )
        ) {
            return 'Please select a valid visit type';
        }

        $allowedVisitModes = [
            'Physical Visit',
            'Online Meeting',
            'Phone Call',
            'Teams Meeting',
            'Google Meet'
        ];
        if (
            !in_array(
                trim((string)$this->input->post('visit_mode', true)),
                $allowedVisitModes,
                true
            )
        ) {
            return 'Please select a valid visit mode';
        }

        $allowedStages = [
            'Not Contacted',
            'Contacted',
            'Quotation Sent',
            'Negotiations',
            'Contract Done',
            'Advance Received',
            'Lead Won',
            'Lead Lost'
        ];
        if (
            !in_array(
                trim((string)$this->input->post('disposition', true)),
                $allowedStages,
                true
            )
        ) {
            return 'Please select a valid stage';
        }

        if (
            !in_array(
                trim((string)$this->input->post('status', true)),
                ['Open', 'In Progress', 'Closed'],
                true
            )
        ) {
            return 'Please select a valid lead status';
        }

        if (
            !in_array(
                trim((string)$this->input->post('lead_type', true)),
                ['Hot', 'Warm', 'Cold'],
                true
            )
        ) {
            return 'Please select a valid lead type';
        }

        foreach (
            [
                'kms_run',
                'rate_per_km',
                'parking_charges',
                'lunch',
                'entertainment'
            ] as $field
        ) {
            $value = $this->input->post($field);
            if (
                $value !== null &&
                $value !== '' &&
                (!is_numeric($value) || (float)$value < 0)
            ) {
                return 'Conveyance amounts must be valid non-negative numbers';
            }
        }

        $latitude = trim((string)$this->input->post('visit_latitude'));
        $longitude = trim((string)$this->input->post('visit_longitude'));
        if (($latitude === '') !== ($longitude === '')) {
            return 'Please capture a complete visit location';
        }
        if (
            $latitude !== '' &&
            (
                !is_numeric($latitude) ||
                (float)$latitude < -90 ||
                (float)$latitude > 90
            )
        ) {
            return 'Invalid visit latitude';
        }
        if (
            $longitude !== '' &&
            (
                !is_numeric($longitude) ||
                (float)$longitude < -180 ||
                (float)$longitude > 180
            )
        ) {
            return 'Invalid visit longitude';
        }

        return '';
    }

    private function validateDynamicFields(
        $stage,
        $departmentName,
        $propertyId,
        $departmentId
    ) {
        if (
            $stage === 'Lead Lost' &&
            trim((string)$this->input->post('reason', true)) === ''
        ) {
            return 'Please select a lead lost reason';
        }

        if ($stage !== 'Quotation Sent') {
            return '';
        }

        if ($departmentName === 'rooms') {
            if (trim((string)$this->input->post('meal_plan', true)) === '') {
                return 'Please select a meal plan';
            }

            $roomTypeId = $this->decodeFlexibleId(
                $this->input->post('roomtype')
            );
            if (
                !empty($roomTypeId) &&
                empty($this->Common_model->getdata('roomtype', [
                    'roomtype_id' => $roomTypeId,
                    'hotel_id' => $propertyId,
                    'is_deleted' => 0
                ]))
            ) {
                return 'Selected room type is unavailable';
            }
        }

        if ($departmentName === 'banquet') {
            $banquetId = $this->decodeFlexibleId(
                $this->input->post('banquet_id')
            );
            if (empty($banquetId)) {
                return 'Please select a banquet';
            }
            if (empty($this->Common_model->getdata('banquet', [
                'banquet_id' => $banquetId,
                'hotel_id' => $propertyId
            ]))) {
                return 'Selected banquet is unavailable';
            }
        }

        if ($departmentName === 'restaurant') {
            foreach ([
                'restaurant_id' => 'Please select a restaurant',
                'slot_type_id' => 'Please select a slot type',
                'time_slot_id' => 'Please select a time slot',
                'table_category_id' => 'Please select a table category',
                'table_reservation_status' => 'Please select a reservation status'
            ] as $field => $message) {
                if (trim((string)$this->input->post($field, true)) === '') {
                    return $message;
                }
            }

            $restaurantId = $this->decodeFlexibleId(
                $this->input->post('restaurant_id')
            );
            $slotTypeId = $this->decodeFlexibleId(
                $this->input->post('slot_type_id')
            );
            $timeSlotId = $this->decodeFlexibleId(
                $this->input->post('time_slot_id')
            );
            $categoryId = $this->decodeFlexibleId(
                $this->input->post('table_category_id')
            );

            if (empty($this->Common_model->getdata('hotel_restaurants', [
                'id' => $restaurantId,
                'hotel_id' => $propertyId,
                'status' => 1
            ]))) {
                return 'Selected restaurant is unavailable';
            }
            if (empty($this->Common_model->getdata('slot_types', [
                'id' => $slotTypeId,
                'status' => 1
            ]))) {
                return 'Selected slot type is unavailable';
            }
            if (empty($this->Common_model->getdata('time_slots', [
                'id' => $timeSlotId,
                'slot_type_id' => $slotTypeId,
                'status' => 'active'
            ]))) {
                return 'Selected time slot is unavailable';
            }
            if (empty($this->Common_model->getdata('table_categories', [
                'id' => $categoryId,
                'restaurant_id' => $restaurantId,
                'status' => 'active'
            ]))) {
                return 'Selected table category is unavailable';
            }

            $tableIds = $this->input->post('table_id');
            $tableIds = is_array($tableIds)
                ? array_values(array_filter(array_map('intval', $tableIds)))
                : [(int)$tableIds];
            if (empty(array_filter($tableIds))) {
                return 'Please select at least one table';
            }

            $validTableCount = $this->db
                ->from('tables')
                ->where_in('id', $tableIds)
                ->where('restaurant_id', $restaurantId)
                ->where('category_id', $categoryId)
                ->where('status', 'active')
                ->count_all_results();
            if ($validTableCount !== count(array_unique($tableIds))) {
                return 'One or more selected tables are unavailable';
            }
        }

        $offerId = $this->decodeFlexibleId(
            $this->input->post('promotional_offers')
        );
        if (
            !empty($offerId) &&
            empty($this->Common_model->getdata('promotional_offers', [
                'id' => $offerId,
                'department_id' => $departmentId
            ]))
        ) {
            return 'Selected promotional offer is unavailable';
        }

        return '';
    }

    private function addDynamicLeadData(
        array &$leadData,
        $stage,
        $departmentName
    ) {
        $dynamicFields = [];

        if ($stage === 'Lead Lost') {
            $dynamicFields = ['reason'];
        } elseif ($stage === 'Lead Won') {
            $dynamicFields = ['amount'];
        } elseif ($stage === 'Quotation Sent') {
            $dynamicFields = [
                'promotional_offers',
                'followup_date',
                'second_followup_date'
            ];

            if ($departmentName === 'rooms') {
                $dynamicFields = array_merge($dynamicFields, [
                    'roomtype',
                    'meal_plan',
                    'checkin_date',
                    'checkout_date',
                    'number_of_rooms',
                    'pax',
                    'adults',
                    'kids',
                    'revenue_room',
                    'revenue_fnb',
                    'revenue_other',
                    'amount'
                ]);
            } elseif ($departmentName === 'restaurant') {
                $dynamicFields = array_merge($dynamicFields, [
                    'booking_date',
                    'pax',
                    'restaurant_id',
                    'slot_type_id',
                    'time_slot_id',
                    'arrival_time',
                    'table_category_id',
                    'table_reservation_status',
                    'amount',
                    'special_occasion',
                    'special_request'
                ]);
            } elseif ($departmentName === 'banquet') {
                $dynamicFields = array_merge($dynamicFields, [
                    'booking_date',
                    'pax',
                    'banquet_id',
                    'amount'
                ]);
            }
        } elseif (
            in_array(
                $stage,
                ['Negotiations', 'Not Contacted', 'Advance Received'],
                true
            )
        ) {
            $dynamicFields = [
                'booking_date',
                'followup_date',
                'second_followup_date'
            ];
        }

        foreach ($dynamicFields as $field) {
            $value = trim((string)$this->input->post($field, true));
            if ($value !== '') {
                $leadData[$field] = $value;
            }
        }

        if (
            $stage === 'Quotation Sent' &&
            $departmentName === 'restaurant'
        ) {
            $tableIds = $this->input->post('table_id');
            if (is_array($tableIds)) {
                $tableIds = array_values(
                    array_filter(array_map('intval', $tableIds))
                );
                if (!empty($tableIds)) {
                    $leadData['table_id'] = $tableIds[0];
                }
            }
        }
    }

    private function uploadAttachment()
    {
        if (empty($_FILES['visit_attachment']['name'])) {
            return ['success' => true, 'path' => ''];
        }

        $uploadPath = FCPATH . 'uploads/sales_visits/';
        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
            return [
                'success' => false,
                'message' => 'Unable to prepare the attachment directory'
            ];
        }

        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path' => $uploadPath,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 5120,
            'encrypt_name' => true,
            'remove_spaces' => true,
            'file_ext_tolower' => true
        ]);

        if (!$this->upload->do_upload('visit_attachment')) {
            return [
                'success' => false,
                'message' => trim(strip_tags(
                    $this->upload->display_errors('', '')
                ))
            ];
        }

        return [
            'success' => true,
            'path' => 'uploads/sales_visits/' .
                $this->upload->data('file_name')
        ];
    }

    private function deleteUploadedAttachment($relativePath)
    {
        if ($relativePath === '') {
            return;
        }

        $normalizedPath = str_replace(
            ['/', '\\'],
            DIRECTORY_SEPARATOR,
            $relativePath
        );
        $filePath = FCPATH . ltrim($normalizedPath, DIRECTORY_SEPARATOR);
        $uploadsRoot = realpath(FCPATH . 'uploads/sales_visits');
        $fileDirectory = realpath(dirname($filePath));

        if (
            $uploadsRoot !== false &&
            $fileDirectory === $uploadsRoot &&
            is_file($filePath)
        ) {
            @unlink($filePath);
        }
    }

    private function triggerLeadEmail($leadId, $mobileNumber)
    {
        $valuableGuest = $this->db
            ->select('id')
            ->from('leads')
            ->where('phone_number', $mobileNumber)
            ->where('LOWER(disposition)', 'reservation')
            ->where('amount >', 0)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $url = base_url(
            'EmailWorker/sendLeadEmail/' .
            $leadId .
            '/' .
            (!empty($valuableGuest) ? '1' : '0')
        );
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_POST, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT_MS, 100);
        curl_setopt($handle, CURLOPT_TIMEOUT_MS, 100);
        curl_setopt($handle, CURLOPT_NOSIGNAL, 1);
        curl_exec($handle);
        curl_close($handle);
    }

    private function activeRecord($table, array $where)
    {
        if ($this->db->field_exists('is_deleted', $table)) {
            $where['is_deleted'] = 0;
        }

        return $this->Common_model->getdata($table, $where);
    }

    private function normalizeDepartment($department)
    {
        $department = strtolower(trim((string)$department));
        if ($department === 'restaurants') {
            return 'restaurant';
        }
        if ($department === 'banquets') {
            return 'banquet';
        }

        return $department;
    }

    private function decryptRequired($field)
    {
        $token = trim((string)$this->input->post($field));
        return $token !== '' ? (decrypt_id($token) ?: null) : null;
    }

    private function decryptOptional($field)
    {
        $token = trim((string)$this->input->post($field));
        return $token !== '' ? (decrypt_id($token) ?: null) : null;
    }

    private function decodeFlexibleId($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (ctype_digit((string)$value)) {
            return (int)$value;
        }

        $decoded = decrypt_id((string)$value);
        return $decoded ? (int)$decoded : null;
    }

    private function numericValue($field)
    {
        $value = $this->input->post($field);
        return $value === null || $value === '' ? 0 : (float)$value;
    }

    private function optionalCoordinate($field)
    {
        $value = trim((string)$this->input->post($field));
        return $value === '' ? null : (float)$value;
    }

    private function optionalText($field)
    {
        $value = trim((string)$this->input->post($field, true));
        return $value === '' ? null : $value;
    }

    private function logActivity($action, $recordId, $details = '')
    {
        $this->Common_model->insertActivityLog([
            'module' => 'sales_visits',
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

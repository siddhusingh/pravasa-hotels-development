<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RateTypeManagment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');

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
            'module' => 'ratetypes',
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

    private function rateTypePayload($includeCreated = false)
    {
        $data = [
            'ratetype_name' => trim($this->input->post('ratetype_name')),
            'ratetype_code' => trim($this->input->post('ratetype_code')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateRateTypePayload($data)
    {
        if ($data['ratetype_name'] === '' || $data['ratetype_code'] === '') {
            return 'Please fill all required rate type details';
        }

        return '';
    }

    public function index()
    {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageRatetype');
        $this->load->view('super_admin/include/footer');
    }

    public function get_ratetypes_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'ratetype_id',
            1 => 'ratetype_code',
            2 => 'ratetype_name',
            3 => 'created_at',
            4 => 'updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']] ?? 'ratetype_id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTRateTypes($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->ratetype_id);
            $data[] = [
                $i++,
                htmlspecialchars($row->ratetype_code ?? '-'),
                htmlspecialchars($row->ratetype_name ?? '-'),
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-ratetype" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-ratetype" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTRateTypesAll(),
            'recordsFiltered' => $this->objdt->DTRateTypesFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function insert()
    {
        $data = $this->rateTypePayload(true);
        $validationError = $this->validateRateTypePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $record_id = $this->Comman_model->insertData('ratetype', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created rate type '.$data['ratetype_name'].' ('.$data['ratetype_code'].')');
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'New rate type has been added successfully' : 'Failed to add rate type',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function edit()
    {
        $id = decrypt_id($this->input->post('id'));
        $result = $this->Common_model->getdata('ratetype', ['ratetype_id' => $id]);

        if (empty($id) || empty($result)) {
            $this->jsonResponse(['status' => false, 'message' => 'Rate type not found']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'ratetype_name' => $result->ratetype_name,
                'ratetype_code' => $result->ratetype_code
            ],
            'id' => encrypt_id($result->ratetype_id)
        ]);
    }

    public function update()
    {
        $record_id = decrypt_id($this->input->post('record_id'));

        if (empty($record_id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid rate type record']);
            return;
        }

        $data = $this->rateTypePayload();
        $validationError = $this->validateRateTypePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('ratetype', $data, ['ratetype_id' => $record_id]);

        if ($updated) {
            $this->logActivity('update', $record_id, 'Updated rate type '.$data['ratetype_name'].' ('.$data['ratetype_code'].')');
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Rate type data has been updated successfully',
            'record_id' => encrypt_id($record_id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid rate type record']);
            return;
        }

        $ratetype = $this->Common_model->getdata('ratetype', ['ratetype_id' => $id]);
        $deleted = $this->Comman_model->Deletedata('ratetype', ['ratetype_id' => $id]);

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                isset($ratetype->ratetype_name) ? 'Deleted rate type '.$ratetype->ratetype_name.' ('.$ratetype->ratetype_code.')' : 'Deleted rate type ID '.$id
            );
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted ? 'Rate type deleted successfully' : 'Something went wrong'
        ]);
    }
}

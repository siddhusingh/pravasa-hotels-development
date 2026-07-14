<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Areas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');

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
            'module' => 'areas',
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

    private function payload($includeCreated = false)
    {
        $secondary_user = $this->input->post('secondary_user');
        $data = [
            'area_name' => trim($this->input->post('area_name')),
            'area_description' => trim($this->input->post('area_description')),
            'state_id' => decrypt_id($this->input->post('state')),
            'primary_user_id' => decrypt_id($this->input->post('primary_user')),
            'secondary_user_id' => !empty($secondary_user) ? decrypt_id($secondary_user) : null,
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validatePayload($data)
    {
        if ($data['area_name'] === '') {
            return 'Please enter area name';
        }

        if (empty($data['state_id'])) {
            return 'Please select state';
        }

        if (empty($data['primary_user_id'])) {
            return 'Please select primary user';
        }

        $stateExists = $this->db
            ->select('s.state_id')
            ->from('state s')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('s.state_id', $data['state_id'])
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->row();

        if (empty($stateExists)) {
            return 'Selected state is unavailable';
        }

        $primaryUserExists = $this->Common_model->getdata('sales_users', [
            'id' => $data['primary_user_id'],
            'user_role' => 'RSO',
            'is_deleted' => 0
        ]);

        if (empty($primaryUserExists)) {
            return 'Selected primary user is unavailable';
        }

        $secondaryUserToken = $this->input->post('secondary_user');
        if ($secondaryUserToken !== null && $secondaryUserToken !== '') {
            if (empty($data['secondary_user_id'])) {
                return 'Invalid secondary user selected';
            }

            $secondaryUserExists = $this->Common_model->getdata('sales_users', [
                'id' => $data['secondary_user_id'],
                'user_role' => 'Sales Executive',
                'is_deleted' => 0
            ]);

            if (empty($secondaryUserExists)) {
                return 'Selected secondary user is unavailable';
            }
        }

        if (!in_array((string) $data['status'], ['Active', 'Inactive', '1', '0'])) {
            return 'Invalid area status';
        }

        return '';
    }

    private function areaDetails($id)
    {
        $this->db->select('a.*, s.state_name, su1.full_name as primary_user_name, su2.full_name as secondary_user_name');
        $this->db->from('areas a');
        $this->db->join('state s', 's.state_id = a.state_id', 'left');
        $this->db->join('sales_users su1', 'su1.id = a.primary_user_id', 'left');
        $this->db->join('sales_users su2', 'su2.id = a.secondary_user_id', 'left');
        $this->db->where('a.area_id', $id);
        $this->db->where('a.is_deleted', 0);
        return $this->db->get()->row();
    }

    public function manage()
    {
        $data['states'] = $this->db
            ->select('s.*')
            ->from('state s')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->order_by('s.state_name', 'ASC')
            ->get()
            ->result();
        $data['secondary_users'] = $this->Common_model->getAllData('sales_users', [
            'user_role' => 'Sales Executive',
            'is_deleted' => 0
        ]);
        $data['primary_users'] = $this->Common_model->getAllData('sales_users', [
            'user_role' => 'RSO',
            'is_deleted' => 0
        ]);

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/areas/manage_areas', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_areas_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [0 => 'a.area_id', 1 => 'a.area_name', 2 => 's.state_name', 3 => 'su1.full_name', 4 => 'su2.full_name', 5 => 'a.status', 6 => 'a.created_at', 7 => 'a.updated_at'];
        $order = $columns[$inputs['order'][0]['column']] ?? 'a.area_id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTAreas($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->area_id);
            $active = in_array((string) $row->status, ['1', 'Active']);
            $status = $active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            $data[] = [
                $i++,
                htmlspecialchars($row->area_name ?? '-'),
                htmlspecialchars($row->state_name ?? '-'),
                htmlspecialchars($row->primary_user_name ?? '-'),
                htmlspecialchars($row->secondary_user_name ?? '-'),
                $status,
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-area" data-record_id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-area" data-record_id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTAreasAll(),
            'recordsFiltered' => $this->objdt->DTAreasFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function add()
    {
        $data = $this->payload(true);
        $data['is_deleted'] = 0;
        $validationError = $this->validatePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $record_id = $this->Comman_model->insertData('areas', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created area ' . $data['area_name']);
        }

        $this->jsonResponse(['status' => (bool) $record_id, 'message' => $record_id ? 'Area added successfully' : 'Failed to add area', 'record_id' => $record_id ? encrypt_id($record_id) : '']);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $row = $this->areaDetails($id);

        if (empty($id) || empty($row)) {
            $this->jsonResponse(['status' => false, 'message' => 'Area not found']);
            return;
        }

        $activeState = $this->db
            ->select('s.state_id, s.state_name')
            ->from('state s')
            ->join('country c', 'c.country_id = s.country_id', 'inner')
            ->where('s.state_id', $row->state_id)
            ->where('s.is_deleted', 0)
            ->where('c.is_deleted', 0)
            ->get()
            ->row();
        $activePrimaryUser = $this->Common_model->getdata('sales_users', [
            'id' => $row->primary_user_id,
            'user_role' => 'RSO',
            'is_deleted' => 0
        ]);
        $activeSecondaryUser = !empty($row->secondary_user_id)
            ? $this->Common_model->getdata('sales_users', [
                'id' => $row->secondary_user_id,
                'user_role' => 'Sales Executive',
                'is_deleted' => 0
            ])
            : null;

        $unavailableDependencies = [];
        if (empty($activeState)) {
            $unavailableDependencies[] = 'state';
        }
        if (empty($activePrimaryUser)) {
            $unavailableDependencies[] = 'primary user';
        }
        if (!empty($row->secondary_user_id) && empty($activeSecondaryUser)) {
            $unavailableDependencies[] = 'secondary user';
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'area_name' => $row->area_name,
                'area_description' => $row->area_description,
                'state' => !empty($activeState) ? encrypt_id($activeState->state_id) : '',
                'state_name' => !empty($activeState) ? $activeState->state_name : '',
                'primary_user' => !empty($activePrimaryUser) ? encrypt_id($activePrimaryUser->id) : '',
                'primary_user_name' => !empty($activePrimaryUser) ? $activePrimaryUser->full_name : '',
                'secondary_user' => !empty($activeSecondaryUser) ? encrypt_id($activeSecondaryUser->id) : '',
                'secondary_user_name' => !empty($activeSecondaryUser) ? $activeSecondaryUser->full_name : '',
                'status' => in_array((string) $row->status, ['1', 'Active']) ? 'Active' : 'Inactive',
                'unavailable_dependencies' => $unavailableDependencies
            ],
            'id' => encrypt_id($row->area_id)
        ]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid area record']);
            return;
        }

        $existing = $this->Common_model->getdata('areas', [
            'area_id' => $id,
            'is_deleted' => 0
        ]);

        if (empty($existing)) {
            $this->jsonResponse(['status' => false, 'message' => 'Area not found or already deleted']);
            return;
        }

        $data = $this->payload();
        $validationError = $this->validatePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('areas', $data, [
            'area_id' => $id,
            'is_deleted' => 0
        ]);

        $activeArea = $updated
            ? $this->Common_model->getdata('areas', ['area_id' => $id, 'is_deleted' => 0])
            : null;

        if ($updated && !empty($activeArea)) {
            $this->logActivity('update', $id, 'Updated area ' . $data['area_name']);
        }

        $this->jsonResponse([
            'status' => !empty($activeArea),
            'message' => !empty($activeArea) ? 'Area updated successfully' : 'Area not found or already deleted',
            'record_id' => !empty($activeArea) ? encrypt_id($id) : ''
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid area record']);
            return;
        }

        $row = $this->Common_model->getdata('areas', [
            'area_id' => $id,
            'is_deleted' => 0
        ]);

        if (empty($row)) {
            $this->jsonResponse(['status' => false, 'message' => 'Area not found or already deleted']);
            return;
        }

        $deleted = $this->Comman_model->UpdateRecord(
            'areas',
            [
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'area_id' => $id,
                'is_deleted' => 0
            ]
        );

        if ($deleted && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $id, 'Soft deleted area ' . $row->area_name);
            $this->jsonResponse(['status' => true, 'message' => 'Area deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Area not found or already deleted']);
    }
}

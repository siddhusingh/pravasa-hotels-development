<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agency extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Agency_model');
        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        $this->load->library('form_validation');

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
            'module' => 'agencies',
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

    public function index()
    {
        $data['hotel_admin'] = $this->Common_model->getAllData('hotel_admin', ['is_deleted' => 0]);

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/agency/list', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_agencies_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'id',
            1 => 'agency_name',
            2 => 'contact_person',
            3 => 'email',
            4 => 'phone',
            5 => 'status'
        ];
        $order = $columns[$inputs['order'][0]['column']] ?? 'id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTAgencies($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $property_ids = $this->Agency_model->get_agency_property_ids($row->id);
            $assigned_properties = '-';

            if (!empty($property_ids)) {
                $property_names = $this->Agency_model->get_property_names_by_ids($property_ids);
                $assigned_properties = htmlspecialchars(implode(', ', $property_names), ENT_QUOTES, 'UTF-8');
            }

            $status = $row->status === 'Active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';

            $data[] = [
                $i++,
                htmlspecialchars($row->agency_name ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->contact_person ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->email ?? '-', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($row->phone ?? '-', ENT_QUOTES, 'UTF-8'),
                $status,
                $assigned_properties,
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-agency" data-record_id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-agency" data-record_id="' . $encrypted . '">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTAgenciesAll(),
            'recordsFiltered' => $this->objdt->DTAgenciesFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function save()
    {
        $id = $this->input->post('agency_id') ? decrypt_id($this->input->post('agency_id')) : '';
        $existing = !empty($id) ? $this->Agency_model->get_agency($id) : null;

        if ($this->input->post('agency_id') && (empty($id) || empty($existing))) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid agency record']);
            return;
        }

        $data = $this->agencyPayload(empty($id));
        $property_ids = $this->decryptPropertyIds($this->input->post('property_ids'));
        $validationError = $this->validateAgency($data, $property_ids, empty($id));

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        if (empty($id)) {
            $this->db->trans_start();
            $record_id = $this->Agency_model->insert_agency($data);
            if ($record_id) {
                $this->Agency_model->save_property_mapping($record_id, $property_ids);
            }
            $this->db->trans_complete();

            if ($record_id && $this->db->trans_status()) {
                $this->logActivity('create', $record_id, 'Created agency ' . $data['agency_name']);
                $this->jsonResponse(['status' => true, 'message' => 'Agency added successfully', 'record_id' => encrypt_id($record_id)]);
                return;
            }

            $this->jsonResponse(['status' => false, 'message' => 'Failed to add agency', 'record_id' => '']);
            return;
        }

        $this->db->trans_start();
        $updated = $this->Agency_model->update_agency($id, $data);
        $activeAgency = $updated !== false ? $this->Agency_model->get_agency($id) : null;
        if (!empty($activeAgency)) {
            $this->Agency_model->update_property_mapping($id, $property_ids);
        }
        $this->db->trans_complete();

        if ($updated === false || empty($activeAgency) || !$this->db->trans_status()) {
            $this->jsonResponse(['status' => false, 'message' => 'Agency not found or already deleted']);
            return;
        }

        $this->logActivity('update', $id, 'Updated agency ' . $data['agency_name']);
        $this->jsonResponse(['status' => true, 'message' => 'Agency updated successfully', 'record_id' => encrypt_id($id)]);
    }

    public function insert()
    {
        $this->save();
    }

    public function update()
    {
        $this->save();
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $agency = $this->Agency_model->get_agency($id);

        if (empty($id) || empty($agency)) {
            $this->jsonResponse(['status' => false, 'message' => 'Agency not found']);
            return;
        }

        $selected = [];
        $property_ids = $this->Agency_model->get_agency_property_ids($id);
        if (!empty($property_ids)) {
            $properties = $this->db
                ->select('hotel_id, hotel_name')
                ->where_in('hotel_id', $property_ids)
                ->where('is_deleted', 0)
                ->get('hotel_admin')
                ->result();

            foreach ($properties as $property) {
                $selected[] = [
                    'id' => encrypt_id($property->hotel_id),
                    'name' => $property->hotel_name
                ];
            }
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'id' => encrypt_id($agency->id),
                'agency_name' => $agency->agency_name,
                'contact_person' => $agency->contact_person,
                'email' => $agency->email,
                'phone' => $agency->phone,
                'address' => $agency->address,
                'status' => $agency->status,
                'selected_properties' => $selected,
                'unavailable_properties' => count($property_ids) - count($selected)
            ]
        ]);
    }

    public function get($id = '')
    {
        $_POST['id'] = $id;
        $this->getDetails();
    }

    public function delete($legacy_id = '')
    {
        $encrypted = $this->input->post('id') ?: $legacy_id;
        $id = decrypt_id($encrypted);

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid agency record']);
            return;
        }

        $agency = $this->Agency_model->get_agency($id);
        if (empty($agency)) {
            $this->jsonResponse(['status' => false, 'message' => 'Agency not found or already deleted']);
            return;
        }

        $deleted = $this->Agency_model->delete_agency($id);

        if ($deleted) {
            $this->logActivity('delete', $id, 'Soft deleted agency ' . $agency->agency_name);
            $this->jsonResponse(['status' => true, 'message' => 'Agency deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Agency not found or already deleted']);
    }

    private function agencyPayload($includePassword)
    {
        $data = [
            'agency_name' => trim($this->input->post('agency_name')),
            'contact_person' => trim($this->input->post('contact_person')),
            'email' => trim($this->input->post('email')),
            'phone' => trim($this->input->post('phone')),
            'address' => trim($this->input->post('address')),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includePassword) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;
        }

        $password = trim($this->input->post('password'));
        if ($includePassword && $password === '') {
            $data['password'] = '';
        } elseif ($includePassword || $password !== '') {
            $data['password'] = md5($password);
        }

        return $data;
    }

    private function decryptPropertyIds($properties)
    {
        if (empty($properties) || !is_array($properties)) {
            return [];
        }

        $ids = [];
        foreach ($properties as $property) {
            $id = decrypt_id($property);
            if (empty($id)) {
                return [];
            }
            $ids[] = $id;
        }

        return array_values(array_unique($ids));
    }

    private function validateAgency($data, $property_ids, $isCreate)
    {
        foreach (['agency_name', 'contact_person', 'email', 'phone', 'address'] as $field) {
            if ($data[$field] === '') {
                return 'Please fill all required agency fields';
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address';
        }

        if (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
            return 'Please enter a valid 10 digit phone number';
        }

        if ($isCreate && empty($data['password'])) {
            return 'Please enter password';
        }

        if (!in_array((string) $data['status'], ['Active', 'Inactive'])) {
            return 'Invalid agency status';
        }

        if (empty($property_ids)) {
            return 'Please assign at least one property';
        }

        $activePropertyCount = $this->db
            ->where_in('hotel_id', $property_ids)
            ->where('is_deleted', 0)
            ->count_all_results('hotel_admin');

        if ($activePropertyCount !== count($property_ids)) {
            return 'One or more selected properties are unavailable';
        }

        return '';
    }
}

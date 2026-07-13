<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tables extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
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
            'module' => 'tables',
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

    private function tablePayload($includeCreated = false)
    {
        $data = [
            'restaurant_id' => $this->input->post('restaurant_id'),
            'category_id' => $this->input->post('category_id'),
            'table_name' => trim($this->input->post('table_name')),
            'table_number' => trim($this->input->post('table_number')),
            'capacity' => trim($this->input->post('capacity')),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateTablePayload($data)
    {
        if (empty($data['restaurant_id']) || empty($data['category_id']) || $data['table_name'] === '' || $data['table_number'] === '' || $data['capacity'] === '') {
            return 'Please fill all required table details';
        }

        if (!is_numeric($data['capacity'])) {
            return 'Capacity must be numeric';
        }

        if (!in_array($data['status'], ['active', 'inactive'])) {
            return 'Invalid table status';
        }

        return '';
    }

    public function manage()
    {
        $data['restaurants'] = $this->Common_model->getAllData('hotel_restaurants', "");
        $data['categories'] = $this->Common_model->getAllData('table_categories', "");

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/tables/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_tables_table()
    {
        $inputs = $this->input->post();

        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 't.id',
            1 => 'r.restaurant_name',
            2 => 'c.category_name',
            3 => 't.table_name',
            4 => 't.table_number',
            5 => 't.capacity',
            6 => 't.status',
            7 => 't.created_at',
            8 => 't.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']] ?? 't.id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTTables($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $status = ($row->status == 'active')
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $data[] = [
                $i++,
                htmlspecialchars($row->restaurant_name ?? '-'),
                htmlspecialchars($row->category_name ?? '-'),
                htmlspecialchars($row->table_name ?? '-'),
                htmlspecialchars($row->table_number ?? '-'),
                htmlspecialchars($row->capacity ?? '-'),
                $status,
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-table" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-table" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTTablesAll(),
            'recordsFiltered' => $this->objdt->DTTablesFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function add()
    {
        $data = $this->tablePayload(true);
        $validationError = $this->validateTablePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $record_id = $this->Comman_model->insertData('tables', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created table '.$data['table_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'Table added successfully' : 'Database error',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $table = $this->Common_model->getdata('tables', ['id' => $id]);

        if (empty($id) || empty($table)) {
            $this->jsonResponse(['status' => false, 'message' => 'Table not found']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'restaurant_id' => $table->restaurant_id,
                'category_id' => $table->category_id,
                'table_name' => $table->table_name,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'status' => $table->status
            ],
            'id' => encrypt_id($table->id)
        ]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid table record']);
            return;
        }

        $data = $this->tablePayload();
        $validationError = $this->validateTablePayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('tables', $data, ['id' => $id]);

        if ($updated) {
            $this->logActivity('update', $id, 'Updated table '.$data['table_name']);
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Table updated successfully',
            'record_id' => encrypt_id($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid table record']);
            return;
        }

        $table = $this->Common_model->getdata('tables', ['id' => $id]);
        $deleted = $this->Comman_model->Deletedata('tables', ['id' => $id]);

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                isset($table->table_name) ? 'Deleted table '.$table->table_name : 'Deleted table ID '.$id
            );
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted ? 'Table deleted successfully' : 'Failed to delete table'
        ]);
    }
}

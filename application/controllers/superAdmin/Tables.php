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

    private function logActivity($action, $recordId, $details = '')
    {
        $actor = $this->getCurrentActor();
        $this->Common_model->insertActivityLog([
            'module' => 'tables',
            'record_id' => $recordId,
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

    private function jsonResponse(array $response)
    {
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    private function getAvailableRestaurants()
    {
        return $this->db
            ->select('r.id, r.restaurant_name')
            ->from('hotel_restaurants r')
            ->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'inner')
            ->where('r.is_deleted', 0)
            ->where('h.is_deleted', 0)
            ->order_by('r.restaurant_name', 'ASC')
            ->get()
            ->result();
    }

    private function getAvailableCategories()
    {
        return $this->db
            ->select('c.id, c.restaurant_id, c.category_name')
            ->from('table_categories c')
            ->join('hotel_restaurants r', 'r.id = c.restaurant_id', 'inner')
            ->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'inner')
            ->where('c.is_deleted', 0)
            ->where('r.is_deleted', 0)
            ->where('h.is_deleted', 0)
            ->order_by('c.category_name', 'ASC')
            ->get()
            ->result();
    }

    private function validCategoryRelationship($restaurantId, $categoryId)
    {
        return $this->db
            ->from('table_categories c')
            ->join('hotel_restaurants r', 'r.id = c.restaurant_id', 'inner')
            ->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'inner')
            ->where('c.id', $categoryId)
            ->where('c.restaurant_id', $restaurantId)
            ->where('c.is_deleted', 0)
            ->where('r.is_deleted', 0)
            ->where('h.is_deleted', 0)
            ->count_all_results() === 1;
    }

    private function tablePayload($includeCreated = false)
    {
        $data = [
            'restaurant_id' => (int) $this->input->post('restaurant_id'),
            'category_id' => (int) $this->input->post('category_id'),
            'table_name' => trim((string) $this->input->post('table_name')),
            'table_number' => trim((string) $this->input->post('table_number')),
            'capacity' => trim((string) $this->input->post('capacity')),
            'status' => trim((string) $this->input->post('status')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;
        }

        return $data;
    }

    private function validateTablePayload(array $data)
    {
        if ($data['restaurant_id'] <= 0 || $data['category_id'] <= 0 || $data['table_name'] === '' || $data['table_number'] === '' || $data['capacity'] === '') {
            return 'Please fill all required table details';
        }

        if (filter_var($data['capacity'], FILTER_VALIDATE_INT) === false || (int) $data['capacity'] <= 0) {
            return 'Capacity must be a positive whole number';
        }

        if (!in_array($data['status'], ['active', 'inactive'], true)) {
            return 'Invalid table status';
        }

        if (!$this->validCategoryRelationship($data['restaurant_id'], $data['category_id'])) {
            return 'Selected category does not belong to the selected restaurant or is unavailable';
        }

        return '';
    }

    public function manage()
    {
        $data['restaurants'] = $this->getAvailableRestaurants();
        $data['categories'] = $this->getAvailableCategories();

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/tables/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_tables_table()
    {
        $inputs = $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = max(1, min(100, (int) ($inputs['length'] ?? 10)));
        $search = trim($inputs['search']['value'] ?? '');
        $columns = [
            0 => 't.id',
            1 => 'r.restaurant_name',
            2 => 'c.category_name',
            3 => 't.table_name',
            4 => 't.table_number',
            5 => 't.capacity',
            6 => 't.status'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 't.id';
        $direction = strtolower($inputs['order'][0]['dir'] ?? '') === 'asc' ? 'ASC' : 'DESC';

        $rows = $this->objdt->DTTables($length, $start, $search, $order, $direction);
        $data = [];
        $serialNumber = $start + 1;

        foreach ($rows as $row) {
            $encryptedId = encrypt_id($row->id);
            $status = $row->status === 'active'
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $data[] = [
                $serialNumber++,
                html_escape($row->restaurant_name),
                html_escape($row->category_name),
                html_escape($row->table_name),
                html_escape($row->table_number),
                html_escape($row->capacity),
                $status,
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-table" data-record_id="'.$encryptedId.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-table ms-2" data-record_id="'.$encryptedId.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTTablesAll(),
            'recordsFiltered' => $this->objdt->DTTablesFiltered($search),
            'data' => $data
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

        $recordId = $this->Comman_model->insertData('tables', $data);
        if ($recordId) {
            $this->logActivity('create', $recordId, 'Created table '.$data['table_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $recordId,
            'message' => $recordId ? 'Table added successfully' : 'Unable to add table',
            'record_id' => $recordId ? encrypt_id($recordId) : ''
        ]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $table = empty($id) ? null : $this->Common_model->getdata('tables', ['id' => $id, 'is_deleted' => 0]);

        if (empty($table) || !$this->validCategoryRelationship($table->restaurant_id, $table->category_id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Table not found or already deleted']);
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
        $where = ['id' => $id, 'is_deleted' => 0];
        $existing = empty($id) ? null : $this->Common_model->getdata('tables', $where);

        if (empty($existing)) {
            $this->jsonResponse(['status' => false, 'message' => 'Table not found or already deleted']);
            return;
        }

        $data = $this->tablePayload();
        $validationError = $this->validateTablePayload($data);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('tables', $data, $where);
        if (!$updated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update table']);
            return;
        }

        if ($this->db->affected_rows() === 0 && empty($this->Common_model->getdata('tables', $where))) {
            $this->jsonResponse(['status' => false, 'message' => 'Table not found or already deleted']);
            return;
        }

        $this->logActivity('update', $id, 'Updated table '.$data['table_name']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Table updated successfully',
            'record_id' => encrypt_id($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));
        $where = ['id' => $id, 'is_deleted' => 0];
        $table = empty($id) ? null : $this->Common_model->getdata('tables', $where);

        if (empty($table)) {
            $this->jsonResponse(['status' => false, 'message' => 'Table not found or already deleted']);
            return;
        }

        $query = $this->Comman_model->UpdateRecord('tables', [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], $where);
        $deleted = $query && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity('delete', $id, 'Soft deleted table '.$table->table_name);
        }

        $this->jsonResponse([
            'status' => $deleted,
            'message' => $deleted
                ? 'Table deleted successfully'
                : ($query ? 'Table not found or already deleted' : 'Unable to delete table')
        ]);
    }
}

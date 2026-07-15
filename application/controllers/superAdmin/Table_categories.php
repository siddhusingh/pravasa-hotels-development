<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Table_categories extends MY_Controller
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
            'module' => 'table_categories',
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

    private function getActiveRestaurants()
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

    private function activeRestaurantExists($restaurantId)
    {
        return $this->db
            ->from('hotel_restaurants r')
            ->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'inner')
            ->where('r.id', $restaurantId)
            ->where('r.is_deleted', 0)
            ->where('h.is_deleted', 0)
            ->count_all_results() === 1;
    }

    private function categoryPayload($includeCreated = false)
    {
        $data = [
            'restaurant_id' => (int) $this->input->post('restaurant_id'),
            'category_name' => trim((string) $this->input->post('category_name')),
            'description' => trim((string) $this->input->post('description')),
            'status' => trim((string) $this->input->post('status')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;
        }

        return $data;
    }

    private function validateCategoryPayload(array $data)
    {
        if ($data['restaurant_id'] <= 0 || $data['category_name'] === '') {
            return 'Please fill all required table category details';
        }

        if (!in_array($data['status'], ['active', 'inactive'], true)) {
            return 'Invalid category status';
        }

        if (!$this->activeRestaurantExists($data['restaurant_id'])) {
            return 'Selected restaurant is unavailable or has been deleted';
        }

        return '';
    }

    public function manage()
    {
        $data['restaurants'] = $this->getActiveRestaurants();

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/table_categories/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_table_categories_table()
    {
        $inputs = $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = max(1, min(100, (int) ($inputs['length'] ?? 10)));
        $search = trim($inputs['search']['value'] ?? '');
        $columns = [
            0 => 'tc.id',
            1 => 'r.restaurant_name',
            2 => 'tc.category_name',
            3 => 'tc.description',
            4 => 'tc.status'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'tc.id';
        $direction = strtolower($inputs['order'][0]['dir'] ?? '') === 'asc' ? 'ASC' : 'DESC';

        $rows = $this->objdt->DTTableCategories($length, $start, $search, $order, $direction);
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
                html_escape($row->description ?: '-'),
                $status,
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-category" data-record_id="'.$encryptedId.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-category ms-2" data-record_id="'.$encryptedId.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTTableCategoriesAll(),
            'recordsFiltered' => $this->objdt->DTTableCategoriesFiltered($search),
            'data' => $data
        ]);
    }

    public function add()
    {
        $data = $this->categoryPayload(true);
        $validationError = $this->validateCategoryPayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $recordId = $this->Comman_model->insertData('table_categories', $data);
        if ($recordId) {
            $this->logActivity('create', $recordId, 'Created table category '.$data['category_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $recordId,
            'message' => $recordId ? 'Category added successfully' : 'Unable to add category',
            'record_id' => $recordId ? encrypt_id($recordId) : ''
        ]);
    }

    public function getByRestaurant()
    {
        $restaurantId = (int) $this->input->post('restaurant_id');
        if ($restaurantId <= 0 || !$this->activeRestaurantExists($restaurantId)) {
            $this->jsonResponse(['status' => false, 'message' => 'Selected restaurant is unavailable or has been deleted', 'data' => []]);
            return;
        }

        $categories = $this->db
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'active')
            ->where('is_deleted', 0)
            ->order_by('category_name', 'ASC')
            ->get('table_categories')
            ->result();

        $this->jsonResponse(['status' => true, 'data' => $categories]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $category = empty($id) ? null : $this->Common_model->getdata('table_categories', ['id' => $id, 'is_deleted' => 0]);

        if (empty($category) || !$this->activeRestaurantExists($category->restaurant_id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Category not found or already deleted']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'restaurant_id' => $category->restaurant_id,
                'category_name' => $category->category_name,
                'description' => $category->description,
                'status' => $category->status
            ],
            'id' => encrypt_id($category->id)
        ]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));
        $where = ['id' => $id, 'is_deleted' => 0];
        $existing = empty($id) ? null : $this->Common_model->getdata('table_categories', $where);

        if (empty($existing)) {
            $this->jsonResponse(['status' => false, 'message' => 'Category not found or already deleted']);
            return;
        }

        $data = $this->categoryPayload();
        $validationError = $this->validateCategoryPayload($data);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('table_categories', $data, $where);
        if (!$updated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update category']);
            return;
        }

        if ($this->db->affected_rows() === 0 && empty($this->Common_model->getdata('table_categories', $where))) {
            $this->jsonResponse(['status' => false, 'message' => 'Category not found or already deleted']);
            return;
        }

        $this->logActivity('update', $id, 'Updated table category '.$data['category_name']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Category updated successfully',
            'record_id' => encrypt_id($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));
        $where = ['id' => $id, 'is_deleted' => 0];
        $category = empty($id) ? null : $this->Common_model->getdata('table_categories', $where);

        if (empty($category)) {
            $this->jsonResponse(['status' => false, 'message' => 'Category not found or already deleted']);
            return;
        }

        $query = $this->Comman_model->UpdateRecord('table_categories', [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], $where);
        $deleted = $query && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity('delete', $id, 'Soft deleted table category '.$category->category_name);
        }

        $this->jsonResponse([
            'status' => $deleted,
            'message' => $deleted
                ? 'Category deleted successfully'
                : ($query ? 'Category not found or already deleted' : 'Unable to delete category')
        ]);
    }
}

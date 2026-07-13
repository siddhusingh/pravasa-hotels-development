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
            'module' => 'table_categories',
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

    private function categoryPayload($includeCreated = false)
    {
        $data = [
            'restaurant_id' => $this->input->post('restaurant_id'),
            'category_name' => trim($this->input->post('category_name')),
            'description' => trim($this->input->post('description')),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateCategoryPayload($data)
    {
        if (empty($data['restaurant_id']) || $data['category_name'] === '') {
            return 'Please fill all required table category details';
        }

        if (!in_array($data['status'], ['active', 'inactive'])) {
            return 'Invalid category status';
        }

        return '';
    }

    public function manage()
    {
        $data['restaurants'] = $this->Common_model->getAllData('hotel_restaurants', "");

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/table_categories/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_table_categories_table()
    {
        $inputs = $this->input->post();

        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'tc.id',
            1 => 'r.restaurant_name',
            2 => 'tc.category_name',
            3 => 'tc.description',
            4 => 'tc.status',
            5 => 'tc.created_at',
            6 => 'tc.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']] ?? 'tc.id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTTableCategories($length, $start, $search, $order, $dir);
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
                htmlspecialchars($row->category_name),
                htmlspecialchars($row->description ?: '-'),
                $status,
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-category" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-category" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTTableCategoriesAll(),
            'recordsFiltered' => $this->objdt->DTTableCategoriesFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
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

        $record_id = $this->Comman_model->insertData('table_categories', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created table category '.$data['category_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'Category added successfully' : 'Database error',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function getByRestaurant()
    {
        $restaurant_id = $this->input->post('restaurant_id');
        $categories = $this->db
            ->where('restaurant_id', $restaurant_id)
            ->where('status', 'active')
            ->get('table_categories')
            ->result();

        $this->jsonResponse([
            'status' => true,
            'data' => $categories
        ]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $category = $this->Common_model->getdata('table_categories', ['id' => $id]);

        if (empty($id) || empty($category)) {
            $this->jsonResponse(['status' => false, 'message' => 'Category not found']);
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

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid category record']);
            return;
        }

        $data = $this->categoryPayload();
        $validationError = $this->validateCategoryPayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('table_categories', $data, ['id' => $id]);

        if ($updated) {
            $this->logActivity('update', $id, 'Updated table category '.$data['category_name']);
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Category updated successfully',
            'record_id' => encrypt_id($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid category record']);
            return;
        }

        $category = $this->Common_model->getdata('table_categories', ['id' => $id]);
        $deleted = $this->Comman_model->Deletedata('table_categories', ['id' => $id]);

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                isset($category->category_name) ? 'Deleted table category '.$category->category_name : 'Deleted table category ID '.$id
            );
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted ? 'Category deleted successfully' : 'Failed to delete category'
        ]);
    }
}

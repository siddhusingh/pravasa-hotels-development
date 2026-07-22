<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TableCategories extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables', 'objdt');
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('hotel_admin_session'))) {
            redirect('hotel-admin-login');
        }
    }

    private function getHotelId()
    {
        $session = $this->session->userdata('hotel_admin_session');
        return (int) ($session['id'] ?? 0);
    }

    private function getAssignedHotel()
    {
        $hotelId = $this->getHotelId();
        if ($hotelId < 1) {
            return null;
        }

        return $this->Common_model->getdata('hotel_admin', [
            'hotel_id' => $hotelId,
            'is_deleted' => 0
        ]);
    }

    private function getCurrentActor()
    {
        $actor = $this->session->userdata('hotel_admin_session');
        $admin = $this->Common_model->getdata('hotel_admins', [
            'hotel_id' => $this->getHotelId(),
            'name' => $actor['user_name'] ?? '',
            'is_deleted' => 0
        ]);

        return [
            'id' => $admin->id ?? ($actor['id'] ?? null),
            'name' => $admin->name ?? ($actor['user_name'] ?? ''),
            'email' => $admin->email ?? '',
            'role' => $this->session->userdata('role_as') ?? 'admin'
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

    private function getHotelRestaurants()
    {
        return $this->db
            ->select('r.id, r.restaurant_name')
            ->from('hotel_restaurants r')
            ->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'inner')
            ->where('r.hotel_id', $this->getHotelId())
            ->where('r.is_deleted', 0)
            ->where('h.is_deleted', 0)
            ->order_by('r.restaurant_name', 'ASC')
            ->get()
            ->result();
    }

    private function hotelRestaurantExists($restaurantId)
    {
        return $this->db
            ->from('hotel_restaurants r')
            ->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'inner')
            ->where('r.id', (int) $restaurantId)
            ->where('r.hotel_id', $this->getHotelId())
            ->where('r.is_deleted', 0)
            ->where('h.is_deleted', 0)
            ->count_all_results() === 1;
    }

    private function getScopedCategory($recordId)
    {
        if (empty($recordId)) {
            return null;
        }

        return $this->db
            ->select('tc.*')
            ->from('table_categories tc')
            ->join('hotel_restaurants r', 'r.id = tc.restaurant_id', 'inner')
            ->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'inner')
            ->where('tc.id', $recordId)
            ->where('r.hotel_id', $this->getHotelId())
            ->where('tc.is_deleted', 0)
            ->where('r.is_deleted', 0)
            ->where('h.is_deleted', 0)
            ->get()
            ->row();
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

        if (!$this->hotelRestaurantExists($data['restaurant_id'])) {
            return 'Selected restaurant is unavailable for your assigned hotel';
        }

        return '';
    }

    public function manage()
    {
        if (empty($this->getAssignedHotel())) {
            show_error('Assigned hotel is unavailable', 403);
            return;
        }

        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/manageTableCategories', [
            'restaurants' => $this->getHotelRestaurants()
        ]);
        $this->load->view('hotel_admin/include/footer');
    }

    public function get_table_categories_table()
    {
        if (empty($this->getAssignedHotel())) {
            $this->jsonResponse([
                'draw' => (int) $this->input->post('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'status' => false,
                'message' => 'Assigned hotel is unavailable'
            ]);
            return;
        }

        $inputs = (array) $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = max(1, min(100, (int) ($inputs['length'] ?? 10)));
        $search = trim((string) ($inputs['search']['value'] ?? ''));
        $columns = [
            0 => 'tc.id',
            1 => 'r.restaurant_name',
            2 => 'tc.category_name',
            3 => 'tc.description',
            4 => 'tc.status'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'tc.id';
        $direction = strtolower((string) ($inputs['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
        $hotelId = $this->getHotelId();
        $rows = $this->objdt->DTTableCategories($length, $start, $search, $order, $direction, $hotelId);

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
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-category" data-record_id="'.$encryptedId.'" aria-label="Edit table category"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-category ms-2" data-record_id="'.$encryptedId.'" aria-label="Delete table category"><i class="fa fa-trash"></i></a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTTableCategoriesAll($hotelId),
            'recordsFiltered' => $this->objdt->DTTableCategoriesFiltered($search, $hotelId),
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

    public function getDetails()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $category = $this->getScopedCategory($recordId);
        if (empty($category)) {
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
        $recordId = decrypt_id($this->input->post('id'));
        if (empty($this->getScopedCategory($recordId))) {
            $this->jsonResponse(['status' => false, 'message' => 'Category not found or already deleted']);
            return;
        }

        $data = $this->categoryPayload();
        $validationError = $this->validateCategoryPayload($data);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $where = ['id' => $recordId, 'is_deleted' => 0];
        $updated = $this->Comman_model->UpdateRecord('table_categories', $data, $where);
        if (!$updated || empty($this->getScopedCategory($recordId))) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update category']);
            return;
        }

        $this->logActivity('update', $recordId, 'Updated table category '.$data['category_name']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Category updated successfully',
            'record_id' => encrypt_id($recordId)
        ]);
    }

    public function delete()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $category = $this->getScopedCategory($recordId);
        if (empty($category)) {
            $this->jsonResponse(['status' => false, 'message' => 'Category not found or already deleted']);
            return;
        }

        $query = $this->Comman_model->UpdateRecord('table_categories', [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], [
            'id' => $recordId,
            'restaurant_id' => $category->restaurant_id,
            'is_deleted' => 0
        ]);

        if ($query && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $recordId, 'Soft deleted table category '.$category->category_name);
            $this->jsonResponse(['status' => true, 'message' => 'Category deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Category not found or already deleted']);
    }
}

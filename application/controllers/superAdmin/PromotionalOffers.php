<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PromotionalOffers extends MY_Controller
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
            'module' => 'promotional_offers',
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

    private function offerPayload($includeCreated = false)
    {
        $data = [
            'department_id' => decrypt_id($this->input->post('department_id')),
            'offer_name' => trim($this->input->post('offer_name')),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateOfferPayload($data)
    {
        if (empty($data['department_id']) || $data['offer_name'] === '') {
            return 'Please fill all required promotional offer details';
        }

        if (!in_array((string) $data['status'], ['0', '1'])) {
            return 'Invalid promotional offer status';
        }

        $department = $this->Common_model->getdata('departments', array(
            'department_id' => $data['department_id'],
            'is_deleted' => 0
        ));

        if (empty($department)) {
            return 'Selected department is unavailable';
        }

        return '';
    }

    public function manage()
    {
        $data['departments'] = $this->Common_model->getAllData('departments', array('is_deleted' => 0));

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/promotional_offers/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_promotional_offers_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'po.id',
            1 => 'd.department_name',
            2 => 'po.offer_name',
            3 => 'po.status',
            4 => 'po.created_at',
            5 => 'po.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']] ?? 'po.id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTPromotionalOffers($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $status = ((string) $row->status === '1')
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $data[] = [
                $i++,
                htmlspecialchars($row->department_name ?? '-'),
                htmlspecialchars($row->offer_name ?? '-'),
                $status,
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-offer" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-offer" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTPromotionalOffersAll(),
            'recordsFiltered' => $this->objdt->DTPromotionalOffersFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function getByDepartment()
    {
        $department_id = decrypt_id($this->input->post('department_id'));
        $promotional_offers = $this->db->where('department_id', $department_id)->get('promotional_offers')->result();

        $this->jsonResponse([
            'status' => true,
            'data' => $promotional_offers
        ]);
    }

    public function add()
    {
        $data = $this->offerPayload(true);
        $validationError = $this->validateOfferPayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $record_id = $this->Comman_model->insertData('promotional_offers', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created promotional offer '.$data['offer_name']);
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'Promotional Offer added successfully' : 'Failed to add promotional offer',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $offer = $this->Common_model->getdata('promotional_offers', array(
            'id' => $id,
            'is_deleted' => 0
        ));

        if (empty($id) || empty($offer)) {
            $this->jsonResponse(['status' => false, 'message' => 'Offer not found or already deleted']);
            return;
        }

        $department = $this->Common_model->getdata('departments', array(
            'department_id' => $offer->department_id,
            'is_deleted' => 0
        ));

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'department_id' => !empty($department) ? encrypt_id($offer->department_id) : '',
                'department_name' => $department->department_name ?? '',
                'department_unavailable' => empty($department),
                'offer_name' => $offer->offer_name,
                'status' => $offer->status
            ],
            'id' => encrypt_id($offer->id)
        ]);
    }

    public function getAll()
    {
        $offers = $this->db
            ->where('status', 1)
            ->order_by('offer_name', 'ASC')
            ->get('promotional_offers')
            ->result();

        $this->jsonResponse([
            'status' => true,
            'data' => $offers
        ]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid promotional offer record']);
            return;
        }

        $existing_offer = $this->Common_model->getdata('promotional_offers', array(
            'id' => $id,
            'is_deleted' => 0
        ));

        if (empty($existing_offer)) {
            $this->jsonResponse(['status' => false, 'message' => 'Offer not found or already deleted']);
            return;
        }

        $data = $this->offerPayload();
        $validationError = $this->validateOfferPayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('promotional_offers', $data, array(
            'id' => $id,
            'is_deleted' => 0
        ));
        $affected_rows = $this->db->affected_rows();

        if (!$updated) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update promotional offer']);
            return;
        }

        if ($affected_rows === 0) {
            $still_active = $this->Common_model->getdata('promotional_offers', array(
                'id' => $id,
                'is_deleted' => 0
            ));

            if (empty($still_active)) {
                $this->jsonResponse(['status' => false, 'message' => 'Offer not found or already deleted']);
                return;
            }
        }

        if ($affected_rows > 0) {
            $this->logActivity('update', $id, 'Updated promotional offer '.$data['offer_name']);
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Promotional Offer updated successfully',
            'record_id' => encrypt_id($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid promotional offer record']);
            return;
        }

        $where = array(
            'id' => $id,
            'is_deleted' => 0
        );
        $offer = $this->Common_model->getdata('promotional_offers', $where);

        if (empty($offer)) {
            $this->jsonResponse(['status' => false, 'message' => 'Offer not found or already deleted']);
            return;
        }

        $delete_query = $this->Comman_model->UpdateRecord('promotional_offers', array(
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ), $where);
        $deleted = $delete_query && $this->db->affected_rows() === 1;

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                isset($offer->offer_name) ? 'Deleted promotional offer '.$offer->offer_name : 'Deleted promotional offer ID '.$id
            );
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted
                ? 'Promotional Offer deleted successfully'
                : ($delete_query ? 'Offer not found or already deleted' : 'Unable to delete promotional offer')
        ]);
    }
}

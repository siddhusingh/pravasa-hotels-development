<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CompanyContacts extends MY_Controller
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
            'module' => 'company_contacts',
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
        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', '');
        $data['states'] = $this->Common_model->getAllData('state', '');
        $data['countries'] = $this->Common_model->getAllData('country', '');
        $data['cities'] = $this->Common_model->getAllData('city', '');
        $data['companies'] = $this->Common_model->getAllData('companies', '');
        $data['designations'] = $this->Common_model->getAllData('designations', '');

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/company_contacts/manage_company_contacts', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_contacts_table()
    {
        $inputs = $this->input->post();
        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'cc.contact_id',
            1 => 'c.company_name',
            2 => 'cc.title',
            3 => 'cc.first_name',
            4 => 'd.designation_name',
            5 => 'cc.email',
            6 => 'cc.mobile_number',
            7 => 'cc.phone_number',
            8 => 'ci.city_name',
            9 => 's.state_name',
            10 => 'cc.status',
            11 => 'cc.created_at',
            12 => 'cc.updated_at'
        ];
        $order = $columns[$inputs['order'][0]['column']] ?? 'cc.contact_id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTCompanyContacts($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->contact_id);
            $active = in_array((string) $row->status, ['1', 'Active']);
            $status = $active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            $full_name = trim(($row->first_name ?? '').' '.($row->last_name ?? ''));

            $data[] = [
                $i++,
                htmlspecialchars($row->company_name ?? '-'),
                htmlspecialchars($row->title ?? '-'),
                htmlspecialchars($full_name !== '' ? $full_name : '-'),
                htmlspecialchars($row->designation_name ?? '-'),
                htmlspecialchars($row->email ?? '-'),
                htmlspecialchars($row->mobile_number ?? '-'),
                htmlspecialchars($row->phone_number ?? '-'),
                htmlspecialchars($row->city_name ?? '-'),
                htmlspecialchars($row->state_name ?? '-'),
                $status,
                !empty($row->created_at) ? date('d M Y h:i A', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y h:i A', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-contact" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-contact" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTCompanyContactsAll(),
            'recordsFiltered' => $this->objdt->DTCompanyContactsFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function save()
    {
        $id = $this->input->post('contact_id') ? decrypt_id($this->input->post('contact_id')) : '';
        $existing = !empty($id) ? $this->Common_model->getdata('company_contacts', ['contact_id' => $id]) : null;

        if ($this->input->post('contact_id') && (empty($id) || empty($existing))) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid contact record']);
            return;
        }

        $data = $this->contactPayload(empty($id));
        $validationError = $this->validateContact($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $contactName = trim($data['first_name'].' '.$data['last_name']);

        if (empty($id)) {
            $record_id = $this->Comman_model->insertData('company_contacts', $data);
            if ($record_id) {
                $this->logActivity('create', $record_id, 'Created company contact '.$contactName);
            }
            $this->jsonResponse(['status' => (bool) $record_id, 'message' => $record_id ? 'Contact added successfully' : 'Failed to add contact', 'record_id' => $record_id ? encrypt_id($record_id) : '']);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('company_contacts', $data, ['contact_id' => $id]);
        if ($updated) {
            $this->logActivity('update', $id, 'Updated company contact '.$contactName);
        }
        $this->jsonResponse(['status' => (bool) $updated, 'message' => $updated ? 'Contact updated successfully' : 'Failed to update contact', 'record_id' => encrypt_id($id)]);
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
        $id = decrypt_id($this->input->post('contact_id'));
        $this->db->select('cc.*, c.company_name, d.designation_name, co.country_name, s.state_name, ci.city_name');
        $this->db->from('company_contacts cc');
        $this->db->join('companies c', 'c.company_id = cc.company_id', 'left');
        $this->db->join('designations d', 'd.id = cc.designation', 'left');
        $this->db->join('country co', 'co.country_id = cc.country', 'left');
        $this->db->join('state s', 's.state_id = cc.state', 'left');
        $this->db->join('city ci', 'ci.city_id = cc.city', 'left');
        $this->db->where('cc.contact_id', $id);
        $contact = $this->db->get()->row();

        if (empty($id) || empty($contact)) {
            $this->jsonResponse(['status' => false, 'message' => 'Contact not found']);
            return;
        }

        $contact->contact_id = encrypt_id($contact->contact_id);
        $contact->company_id = !empty($contact->company_id) ? encrypt_id($contact->company_id) : '';
        $contact->designation = !empty($contact->designation) ? encrypt_id($contact->designation) : '';
        $contact->country = !empty($contact->country) ? encrypt_id($contact->country) : '';
        $contact->state = !empty($contact->state) ? encrypt_id($contact->state) : '';
        $contact->city = !empty($contact->city) ? encrypt_id($contact->city) : '';
        $this->jsonResponse(['status' => true, 'data' => $contact]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid contact record']);
            return;
        }

        $contact = $this->Common_model->getdata('company_contacts', ['contact_id' => $id]);
        $deleted = $this->Comman_model->Deletedata('company_contacts', ['contact_id' => $id]);

        if ($deleted) {
            $name = !empty($contact) ? trim(($contact->first_name ?? '').' '.($contact->last_name ?? '')) : '';
            $this->logActivity('delete', $id, $name !== '' ? 'Deleted company contact '.$name : 'Deleted company contact ID '.$id);
        }

        $this->jsonResponse(['status' => (bool) $deleted, 'message' => $deleted ? 'Contact deleted successfully' : 'Failed to delete contact']);
    }

    private function contactPayload($includeCreated)
    {
        $data = [
            'company_id' => decrypt_id($this->input->post('company_id')),
            'title' => trim($this->input->post('title')),
            'first_name' => trim($this->input->post('first_name')),
            'last_name' => trim($this->input->post('last_name')),
            'designation' => $this->input->post('designation_id') ? (decrypt_id($this->input->post('designation_id')) ?: null) : null,
            'grade' => trim($this->input->post('grade')),
            'email' => trim($this->input->post('email')),
            'phone_number' => trim($this->input->post('phone_number')),
            'mobile_number' => trim($this->input->post('mobile_number')),
            'address' => trim($this->input->post('address')),
            'city' => $this->input->post('city') ? (decrypt_id($this->input->post('city')) ?: null) : null,
            'country' => $this->input->post('country_id') ? (decrypt_id($this->input->post('country_id')) ?: null) : null,
            'state' => $this->input->post('state_id') ? (decrypt_id($this->input->post('state_id')) ?: null) : null,
            'pincode' => trim($this->input->post('pincode')),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'date_of_anniversary' => $this->input->post('date_of_anniversary'),
            'status' => $this->input->post('status') ?: 'Active',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('email')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateContact($data)
    {
        if (empty($data['company_id'])) {
            return 'Invalid company selection';
        }

        foreach (['title', 'first_name', 'last_name', 'email', 'mobile_number'] as $field) {
            if ($data[$field] === '' || $data[$field] === null) {
                return 'Please fill all required contact fields';
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address';
        }

        if (!in_array((string) $data['status'], ['Active', 'Inactive', '1', '0'])) {
            return 'Invalid contact status';
        }

        return '';
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Time_slots extends MY_Controller
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
            'module' => 'time_slots',
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

    private function timeSlotPayload($includeCreated = false)
    {
        $data = [
            'slot_type_id' => $this->input->post('slot_type_id'),
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function validateTimeSlotPayload($data)
    {
        if (empty($data['slot_type_id']) || empty($data['start_time']) || empty($data['end_time'])) {
            return 'Please fill all required time slot details';
        }

        if (!in_array($data['status'], ['active', 'inactive'])) {
            return 'Invalid time slot status';
        }

        return '';
    }

    public function manage()
    {
        $data['slot_types'] = $this->Common_model->getAllData('slot_types', "");

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/time_slots/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_time_slots_table()
    {
        $inputs = $this->input->post();

        $draw = $inputs['draw'];
        $start = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'ts.id',
            1 => 'st.slot_name',
            2 => 'ts.start_time',
            3 => 'ts.start_time',
            4 => 'ts.end_time',
            5 => 'ts.status',
            6 => 'ts.created_at',
            7 => 'ts.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']] ?? 'ts.id';
        $dir = $inputs['order'][0]['dir'] ?? 'DESC';

        $list = $this->objdt->DTTimeSlots($length, $start, $search, $order, $dir);
        $data = [];
        $i = $start + 1;

        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $time_range = (!empty($row->start_time) && !empty($row->end_time))
                ? date('h:i A', strtotime($row->start_time)).' - '.date('h:i A', strtotime($row->end_time))
                : '-';
            $status = ($row->status == 'active')
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $data[] = [
                $i++,
                htmlspecialchars($row->slot_type_name ?? '-'),
                $time_range,
                !empty($row->start_time) ? date('h:i A', strtotime($row->start_time)) : '-',
                !empty($row->end_time) ? date('h:i A', strtotime($row->end_time)) : '-',
                $status,
                !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : '-',
                !empty($row->updated_at) ? date('d M Y', strtotime($row->updated_at)) : '-',
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-slot" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-slot" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $this->objdt->DTTimeSlotsAll(),
            'recordsFiltered' => $this->objdt->DTTimeSlotsFiltered($search),
            'data' => $data,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function add()
    {
        $data = $this->timeSlotPayload(true);
        $validationError = $this->validateTimeSlotPayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $record_id = $this->Comman_model->insertData('time_slots', $data);

        if ($record_id) {
            $this->logActivity('create', $record_id, 'Created time slot '.$data['start_time'].' - '.$data['end_time']);
        }

        $this->jsonResponse([
            'status' => (bool) $record_id,
            'message' => $record_id ? 'Time slot added successfully' : 'Database error',
            'record_id' => $record_id ? encrypt_id($record_id) : ''
        ]);
    }

    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));
        $slot = $this->Common_model->getdata('time_slots', ['id' => $id]);

        if (empty($id) || empty($slot)) {
            $this->jsonResponse(['status' => false, 'message' => 'Time slot not found']);
            return;
        }

        $this->jsonResponse([
            'status' => true,
            'data' => [
                'slot_type_id' => $slot->slot_type_id,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'status' => $slot->status
            ],
            'id' => encrypt_id($slot->id)
        ]);
    }

    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid time slot record']);
            return;
        }

        $data = $this->timeSlotPayload();
        $validationError = $this->validateTimeSlotPayload($data);

        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $updated = $this->Comman_model->UpdateRecord('time_slots', $data, ['id' => $id]);

        if ($updated) {
            $this->logActivity('update', $id, 'Updated time slot '.$data['start_time'].' - '.$data['end_time']);
        }

        $this->jsonResponse([
            'status' => true,
            'message' => 'Time slot updated successfully',
            'record_id' => encrypt_id($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse(['status' => false, 'message' => 'Invalid time slot record']);
            return;
        }

        $slot = $this->Common_model->getdata('time_slots', ['id' => $id]);
        $deleted = $this->Comman_model->Deletedata('time_slots', ['id' => $id]);

        if ($deleted) {
            $this->logActivity(
                'delete',
                $id,
                !empty($slot) ? 'Deleted time slot '.$slot->start_time.' - '.$slot->end_time : 'Deleted time slot ID '.$id
            );
        }

        $this->jsonResponse([
            'status' => (bool) $deleted,
            'message' => $deleted ? 'Time slot deleted successfully' : 'Failed to delete time slot'
        ]);
    }
}

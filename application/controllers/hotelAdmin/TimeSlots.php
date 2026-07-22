<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TimeSlots extends MY_Controller
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

    private function getCurrentActor()
    {
        $actor = $this->session->userdata('hotel_admin_session');
        $hotelId = (int) ($actor['id'] ?? 0);
        $admin = $this->Common_model->getdata('hotel_admins', [
            'hotel_id' => $hotelId,
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
            'module' => 'time_slots',
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

    private function getAvailableSlotTypes()
    {
        return $this->db
            ->select('id, slot_name')
            ->from('slot_types')
            ->where('is_deleted', 0)
            ->order_by('slot_name', 'ASC')
            ->get()
            ->result();
    }

    private function getAvailableSlotType($slotTypeId)
    {
        return $this->db
            ->select('id, slot_name')
            ->from('slot_types')
            ->where('id', (int) $slotTypeId)
            ->where('is_deleted', 0)
            ->get()
            ->row();
    }

    private function timeSlotPayload($includeCreated = false)
    {
        $data = [
            'slot_type_id' => (int) $this->input->post('slot_type_id'),
            'start_time' => trim((string) $this->input->post('start_time')),
            'end_time' => trim((string) $this->input->post('end_time')),
            'status' => trim((string) $this->input->post('status')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($includeCreated) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;
        }

        return $data;
    }

    private function validateTimeSlotPayload(array $data)
    {
        if ($data['slot_type_id'] <= 0 || $data['start_time'] === '' || $data['end_time'] === '') {
            return 'Please fill all required time slot details';
        }

        $timePattern = '/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/';
        if (!preg_match($timePattern, $data['start_time']) || !preg_match($timePattern, $data['end_time'])) {
            return 'Please enter valid start and end times';
        }

        if (strtotime($data['end_time']) <= strtotime($data['start_time'])) {
            return 'End time must be later than start time';
        }

        if (!in_array($data['status'], ['active', 'inactive'], true)) {
            return 'Invalid time slot status';
        }

        if (empty($this->getAvailableSlotType($data['slot_type_id']))) {
            return 'Selected slot type is unavailable or has been deleted';
        }

        return '';
    }

    private function getActiveTimeSlot($recordId)
    {
        if (empty($recordId)) {
            return null;
        }

        return $this->Common_model->getdata('time_slots', [
            'id' => $recordId,
            'is_deleted' => 0
        ]);
    }

    public function manage()
    {
        $this->load->view('hotel_admin/include/header');
        $this->load->view('hotel_admin/include/sidebar');
        $this->load->view('hotel_admin/manageTimeSlots', [
            'slot_types' => $this->getAvailableSlotTypes()
        ]);
        $this->load->view('hotel_admin/include/footer');
    }

    public function get_time_slots_table()
    {
        $inputs = (array) $this->input->post();
        $draw = (int) ($inputs['draw'] ?? 0);
        $start = max(0, (int) ($inputs['start'] ?? 0));
        $length = max(1, min(100, (int) ($inputs['length'] ?? 10)));
        $search = trim((string) ($inputs['search']['value'] ?? ''));
        $columns = [
            0 => 'ts.id',
            1 => 'st.slot_name',
            2 => 'ts.start_time',
            3 => 'ts.start_time',
            4 => 'ts.end_time',
            5 => 'ts.status'
        ];
        $orderIndex = (int) ($inputs['order'][0]['column'] ?? 0);
        $order = $columns[$orderIndex] ?? 'ts.id';
        $direction = strtolower((string) ($inputs['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
        $rows = $this->objdt->DTTimeSlots($length, $start, $search, $order, $direction);

        $data = [];
        $serialNumber = $start + 1;
        foreach ($rows as $row) {
            $encryptedId = encrypt_id($row->id);
            $timeRange = date('h:i A', strtotime($row->start_time)).' - '.date('h:i A', strtotime($row->end_time));
            $status = $row->status === 'active'
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $data[] = [
                $serialNumber++,
                html_escape($row->slot_type_name),
                $timeRange,
                date('h:i A', strtotime($row->start_time)),
                date('h:i A', strtotime($row->end_time)),
                $status,
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-slot" data-record_id="'.$encryptedId.'" aria-label="Edit time slot"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-slot ms-2" data-record_id="'.$encryptedId.'" aria-label="Delete time slot"><i class="fa fa-trash"></i></a>'
            ];
        }

        $this->jsonResponse([
            'draw' => $draw,
            'recordsTotal' => $this->objdt->DTTimeSlotsAll(),
            'recordsFiltered' => $this->objdt->DTTimeSlotsFiltered($search),
            'data' => $data
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

        $slotType = $this->getAvailableSlotType($data['slot_type_id']);
        $data['slot_name'] = $slotType->slot_name;
        $recordId = $this->Comman_model->insertData('time_slots', $data);
        if ($recordId) {
            $this->logActivity('create', $recordId, 'Created time slot '.$data['start_time'].' - '.$data['end_time']);
        }

        $this->jsonResponse([
            'status' => (bool) $recordId,
            'message' => $recordId ? 'Time slot added successfully' : 'Unable to add time slot',
            'record_id' => $recordId ? encrypt_id($recordId) : ''
        ]);
    }

    public function getDetails()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $slot = $this->getActiveTimeSlot($recordId);
        if (empty($slot) || empty($this->getAvailableSlotType($slot->slot_type_id))) {
            $this->jsonResponse(['status' => false, 'message' => 'Time slot not found or already deleted']);
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
        $recordId = decrypt_id($this->input->post('id'));
        if (empty($this->getActiveTimeSlot($recordId))) {
            $this->jsonResponse(['status' => false, 'message' => 'Time slot not found or already deleted']);
            return;
        }

        $data = $this->timeSlotPayload();
        $validationError = $this->validateTimeSlotPayload($data);
        if ($validationError !== '') {
            $this->jsonResponse(['status' => false, 'message' => $validationError]);
            return;
        }

        $slotType = $this->getAvailableSlotType($data['slot_type_id']);
        $data['slot_name'] = $slotType->slot_name;
        $where = ['id' => $recordId, 'is_deleted' => 0];
        $updated = $this->Comman_model->UpdateRecord('time_slots', $data, $where);
        if (!$updated || empty($this->getActiveTimeSlot($recordId))) {
            $this->jsonResponse(['status' => false, 'message' => 'Unable to update time slot']);
            return;
        }

        $this->logActivity('update', $recordId, 'Updated time slot '.$data['start_time'].' - '.$data['end_time']);
        $this->jsonResponse([
            'status' => true,
            'message' => 'Time slot updated successfully',
            'record_id' => encrypt_id($recordId)
        ]);
    }

    public function delete()
    {
        $recordId = decrypt_id($this->input->post('id'));
        $slot = $this->getActiveTimeSlot($recordId);
        if (empty($slot)) {
            $this->jsonResponse(['status' => false, 'message' => 'Time slot not found or already deleted']);
            return;
        }

        $query = $this->Comman_model->UpdateRecord('time_slots', [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], [
            'id' => $recordId,
            'is_deleted' => 0
        ]);

        if ($query && $this->db->affected_rows() === 1) {
            $this->logActivity('delete', $recordId, 'Soft deleted time slot '.$slot->start_time.' - '.$slot->end_time);
            $this->jsonResponse(['status' => true, 'message' => 'Time slot deleted successfully']);
            return;
        }

        $this->jsonResponse(['status' => false, 'message' => 'Time slot not found or already deleted']);
    }
}

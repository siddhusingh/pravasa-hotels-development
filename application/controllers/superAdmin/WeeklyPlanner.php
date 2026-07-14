<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WeeklyPlanner extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->helper('secure');
        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }

    private function decodeId($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return $value;
        }

        $decoded = decrypt_id($value);
        return $decoded !== false ? $decoded : null;
    }

    private function jsonResponse($payload)
    {
        $payload['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($payload);
    }

    /* =======================
       LOAD MAIN VIEW
    ======================= */
    public function manage()
    {

        $data['companies'] = $this->Common_model->getAllData('companies', ['status' => 1, 'is_deleted' => 0]);


        $data['planners'] = $this->Common_model->getAllData(
            'weekly_planner',
            ''
        );

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/weekly_planner/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    /* =======================
       FETCH LIST (AJAX)
    ======================= */
    public function fetch()
    {

        $data['planners'] = $this->Common_model->getAllData(
            'weekly_planner',
            ''
        );

        $this->load->view('super_admin/weekly_planner/_forms_table', $data);
    }


    public function getCalendarPlans()
    {
        $plans = $this->db
            ->select('id, planner_date, activity_type, description')
            ->get('weekly_planner')
            ->result();

        $events = [];

        foreach ($plans as $row) {

            $title = ($row->activity_type === 'visit')
                ? 'Visit'
                : 'Other Activity';

            if (!empty($row->description)) {
                $title .= ' - ' . substr($row->description, 0, 30);
            }

            $events[] = [
                'id'    => encrypt_id($row->id),
                'title' => $title,
                'start' => $row->planner_date,
                'allDay' => true
            ];
        }

        echo json_encode($events);
    }


    /* =======================
       ADD WEEKLY PLANNER
    ======================= */
    public function add()
    {

        $insertData = [
            'planner_date'        => $this->input->post('planner_date'),
            'activity_type'       => $this->input->post('activity_type'),

            'account_type'        => $this->input->post('account_type'),
            'company_id'          => $this->decodeId($this->input->post('company_id')),
            'contact_id'          => $this->decodeId($this->input->post('contact_id')),

            'new_person_name'     => $this->input->post('new_person_name'),
            'new_person_mobile'   => $this->input->post('new_person_mobile'),

            'other_activity'      => $this->input->post('other_activity'),
            'description'         => $this->input->post('description'),

            'created_at'          => date('Y-m-d H:i:s')
        ];

        $this->Common_model->insertData('weekly_planner', $insertData);

        $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Weekly planner added successfully'
        ]);
    }

    /* =======================
       GET DETAILS (EDIT)
    ======================= */
    public function getDetails()
    {
        $id = $this->decodeId($this->input->post('id') ?: $this->input->get('id'));

        $this->db->select('*');
        $this->db->from('weekly_planner');
        $this->db->where('id', $id);
        $data = $this->db->get()->row();

        if ($data) {
            $companyName = '';
            if (!empty($data->company_id)) {
                $company = $this->Common_model->getdata('companies', ['company_id' => $data->company_id]);
                $companyName = $company->company_name ?? '';
            }

            $data->id = encrypt_id($data->id);
            $data->company_id = !empty($data->company_id) ? encrypt_id($data->company_id) : '';
            $data->contact_id = !empty($data->contact_id) ? encrypt_id($data->contact_id) : '';
            $data->company_name = $companyName;

            echo json_encode([
                'status' => 'success',
                'data'   => $data
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Planner not found'
            ]);
        }
    }

    /* =======================
       UPDATE WEEKLY PLANNER
    ======================= */
    public function update()
    {

        $id = $this->decodeId($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid planner record'
            ]);
            return;
        }

        $updateData = [
            'planner_date'        => $this->input->post('planner_date'),
            'activity_type'       => $this->input->post('activity_type'),

            'account_type'        => $this->input->post('account_type'),
            'company_id'          => $this->decodeId($this->input->post('company_id')),
            'contact_id'          => $this->decodeId($this->input->post('contact_id')),

            'new_person_name'     => $this->input->post('new_person_name'),
            'new_person_mobile'   => $this->input->post('new_person_mobile'),

            'other_activity'      => $this->input->post('other_activity'),
            'description'         => $this->input->post('description'),

            'updated_at'          => date('Y-m-d H:i:s')
        ];

        $this->Common_model->UpdateRecord(
            'weekly_planner',
            $updateData,
            ['id' => $id]
        );

        $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Weekly planner updated successfully'
        ]);
    }

    /* =======================
       DELETE WEEKLY PLANNER
    ======================= */
    public function delete()
    {

        $id = $this->decodeId($this->input->post('id'));

        if (empty($id)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid planner record'
            ]);
            return;
        }

        $this->Common_model->deleteData(
            'weekly_planner',
            ['id' => $id]
        );

        $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Weekly planner deleted successfully'
        ]);
    }
}

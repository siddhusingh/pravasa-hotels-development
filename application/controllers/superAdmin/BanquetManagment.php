<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BanquetManagment extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comman_model');
        $this->load->model('Common_model');
        $this->load->model('Datatables','objdt');
        $this->load->helper('download');
        date_default_timezone_set('Asia/Kolkata');

        if (empty($this->session->userdata('super_admin_session')) || ($this->session->userdata('role_as') != 'super_admin') || ($this->session->userdata('user_role') != 1)) {
            return redirect('super-admin-login');
        }
    }


    public function index()
    {

        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', "");

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/manageBanquet', $data);
        $this->load->view('super_admin/include/footer');
    }

    public function get_banquets_table()
    {
        $inputs = $this->input->post();

        $draw   = $inputs['draw'];
        $start  = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'banquet.banquet_id',
            1 => 'hotel_admin.hotel_name',
            2 => 'banquet.banquet_code',
            3 => 'banquet.banquet_name',
            4 => 'banquet.capacity',
            5 => 'banquet.created_at',
            6 => 'banquet.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']];
        $dir = $inputs['order'][0]['dir'];
        $list = $this->objdt->DTBanquets($length, $start, $search, $order, $dir);

        $data = [];
        $i = $start + 1;
        foreach ($list as $row) {
            $encrypted = encrypt_id($row->banquet_id);
            $data[] = [
                $i++,
                $row->hotel_name ?? '-',
                $row->banquet_code,
                $row->banquet_name,
                $row->capacity,
                date('d M Y', strtotime($row->created_at)),
                date('d M Y', strtotime($row->updated_at)),
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-banquet" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-danger delete-banquet" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                        <path d="M9 6V4h6v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $this->objdt->DTBanquetsAll(),
            "recordsFiltered" => $this->objdt->DTBanquetsFiltered($search),
            "data" => $data,
            "csrfHash" => $this->security->get_csrf_hash()
        ]);
    }


    public function insert()
    {

        $banquet_name = trim($this->input->post('banquet_name'));
        $banquet_code = trim($this->input->post('banquet_code'));
        $capacity = trim($this->input->post('capacity'));
        $hotel_id = decrypt_id($this->input->post('hotel_id'));

        if (empty($hotel_id) || $banquet_name == '' || $banquet_code == '' || $capacity == '') {
            echo json_encode([
                'status' => false,
                'message' => 'Please fill all required banquet details',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $created_on = date('Y-m-d H:i:s');
        $updated_on = date('Y-m-d H:i:s');

        $banquet_data = array(
            'hotel_id' => $hotel_id,
            'banquet_name' => $banquet_name,
            'banquet_code' => $banquet_code,
            'capacity' => $capacity,
            'created_at' => $created_on,
            'updated_at' => $updated_on
        );

        $record_id = $this->Comman_model->insertData('banquet', $banquet_data);

        $response_json['status'] = true;
        $response_json['message'] = "New banquet has been added successfully";
        $response_json['record_id'] = encrypt_id($record_id);
        $response_json['csrfHash'] = $this->security->get_csrf_hash();

        echo json_encode($response_json);
    }



    public function getByHotel()
    {
        $hotel_id = $this->input->post('hotel_id');

        $banquets = $this->db
            ->where('hotel_id', $hotel_id)

            ->get('banquet')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data' => $banquets
        ]);
    }


    public function edit()
    {

        $id = decrypt_id($this->input->post('id'));
        $result = $this->Common_model->getdata('banquet', array('banquet_id' => $id));

        if (empty($id) || empty($result)) {
            echo json_encode([
                'status' => false,
                'message' => 'Banquet record not found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $hotel = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $result->hotel_id]);

        echo json_encode([
            'status' => true,
            'data' => [
                'hotel_id' => encrypt_id($result->hotel_id),
                'hotel_name' => $hotel->hotel_name ?? '',
                'banquet_name' => $result->banquet_name,
                'banquet_code' => $result->banquet_code,
                'capacity' => $result->capacity
            ],
            'id' => encrypt_id($result->banquet_id),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }


    public function update()
    {

        $record_id = decrypt_id($this->input->post('record_id'));

        $banquet_name = trim($this->input->post('banquet_name'));
        $banquet_code = trim($this->input->post('banquet_code'));
        $capacity = trim($this->input->post('capacity'));
        $hotel_id = decrypt_id($this->input->post('hotel_id'));

        if (empty($record_id) || empty($hotel_id) || $banquet_name == '' || $banquet_code == '' || $capacity == '') {
            echo json_encode([
                'status' => false,
                'message' => 'Please fill all required banquet details',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $updated_on = date('Y-m-d H:i:s');

        $banquet_data = array(
            'hotel_id' => $hotel_id,
            'banquet_name' => $banquet_name,
            'banquet_code' => $banquet_code,
            'capacity' => $capacity,
            'updated_at' => $updated_on
        );

        $this->Comman_model->UpdateRecord('banquet', $banquet_data, array('banquet_id' => $record_id));

        $response_json['status'] = true;
        $response_json['message'] = "Banquet data has been updated successfully";
        $response_json['record_id'] = encrypt_id($record_id);
        $response_json['csrfHash'] = $this->security->get_csrf_hash();

        echo json_encode($response_json);
    }


    public function delete()
    {

        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid banquet record',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $data = $this->Comman_model->Deletedata('banquet', array('banquet_id' => $id));

        if ($data) {
            $response['status'] = true;
            $response['message'] = "Banquet deleted successfully";
        } else {
            $response['status'] = false;
            $response['message'] = "Something went wrong";
        }
        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }
}

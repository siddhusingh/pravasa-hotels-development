<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Restaurants extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Datatables','objdt');
        $this->load->helper('secure');

        if (
            empty($this->session->userdata('super_admin_session')) ||
            ($this->session->userdata('role_as') != 'super_admin') ||
            ($this->session->userdata('user_role') != 1)
        ) {
            return redirect('super-admin-login');
        }
    }

    // Show manage hotel_restaurants page
    public function manage()
    {
        $data['hotels'] = $this->Common_model->getAllData('hotel_admin', "");

        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/include/sidebar');
        $this->load->view('super_admin/hotel_restaurants/manage_forms', $data);
        $this->load->view('super_admin/include/footer');
    }

    // Add Restaurant
    public function add()
    {
        // ✅ Image Upload (optional)
        $restaurant_image = '';

        if (!empty($_FILES['restaurant_image']['name'])) {

            $upload_path = FCPATH . 'uploads/restaurant_images/';

            // create folder if not exists
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size']      = 2048;
            $config['file_name']     = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '_', $_FILES['restaurant_image']['name']);

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('restaurant_image')) {
                $uploadData       = $this->upload->data();
                $restaurant_image = $uploadData['file_name'];
            }
            // ❗ No else → do not break existing flow
        }

        // ✅ Existing Data (unchanged)
        $data = [
            'hotel_id'         => decrypt_id($this->input->post('hotel_id')),
            'restaurant_name'  => trim($this->input->post('restaurant_name')),
            'restaurant_code'  => trim($this->input->post('restaurant_code')),
            'restaurant_type'  => $this->input->post('restaurant_type'),
            'opening_time'     => $this->input->post('opening_time'),
            'closing_time'     => $this->input->post('closing_time'),
            'capacity'         => $this->input->post('capacity'),
            'contact_number'   => $this->input->post('contact_number'),
            'email'            => $this->input->post('email'),
            'status'           => $this->input->post('status'),
            'restaurant_image' => $restaurant_image, // ✅ added
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s')
        ];

        if (empty($data['hotel_id']) || $data['restaurant_name'] == '' || $data['restaurant_code'] == '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Please fill all required restaurant details',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $insert = $this->db->insert('hotel_restaurants', $data);

        echo json_encode([
            'status'  => $insert ? 'success' : 'error',
            'message' => $insert ? 'Restaurant added successfully' : 'Database error!',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    public function get_restaurants_table()
    {
        $inputs = $this->input->post();

        $draw   = $inputs['draw'];
        $start  = $inputs['start'];
        $length = $inputs['length'];
        $search = $inputs['search']['value'];

        $columns = [
            0 => 'r.id',
            1 => 'h.hotel_name',
            2 => 'r.restaurant_name',
            3 => 'r.restaurant_code',
            4 => 'r.contact_number',
            5 => 'r.email',
            6 => 'r.status',
            7 => 'r.created_at',
            8 => 'r.updated_at'
        ];

        $order = $columns[$inputs['order'][0]['column']];
        $dir = $inputs['order'][0]['dir'];
        $list = $this->objdt->DTRestaurants($length, $start, $search, $order, $dir);

        $data = [];
        $i = $start + 1;
        foreach ($list as $row) {
            $encrypted = encrypt_id($row->id);
            $data[] = [
                $i++,
                $row->hotel_name ?? '-',
                $row->restaurant_name,
                $row->restaurant_code,
                $row->contact_number,
                $row->email,
                ($row->status == 1) ? 'Active' : 'Inactive',
                date('d M Y h:i A', strtotime($row->created_at)),
                date('d M Y h:i A', strtotime($row->updated_at)),
                '<a href="javascript:void(0)" class="text-fade hover-primary edit-restaurant" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-restaurant" data-record_id="'.$encrypted.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>'
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $this->objdt->DTRestaurantsAll(),
            "recordsFiltered" => $this->objdt->DTRestaurantsFiltered($search),
            "data" => $data,
            "csrfHash" => $this->security->get_csrf_hash()
        ]);
    }

    // Fetch Restaurants (AJAX table refresh)
    public function fetch()
    {
        $this->db->select('r.*, h.hotel_name');
        $this->db->from('hotel_restaurants r');
        $this->db->join('hotel_admin h', 'h.hotel_id = r.hotel_id', 'left');
        $this->db->order_by('r.id', 'DESC');
        $data['hotel_restaurants'] = $this->db->get()->result();

        // Return HTML table rows
        $html = $this->load->view('super_admin/hotel_restaurants/_forms_table', $data, true);
        echo $html;
    }

    public function getByHotel()
    {
        $hotel_id = $this->input->post('hotel_id');
        if (!is_numeric($hotel_id)) {
            $hotel_id = decrypt_id($hotel_id);
        }

        $restaurants = $this->db
            ->where('hotel_id', $hotel_id)
            ->where('status', 1)
            ->get('hotel_restaurants')
            ->result();

        foreach ($restaurants as $restaurant) {
            $restaurant->id = encrypt_id($restaurant->id);
        }

        echo json_encode([
            'status' => 'success',
            'data' => $restaurants,
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }


    // Get Restaurant Details
    public function getDetails()
    {
        $id = decrypt_id($this->input->post('id'));

        $this->db->select('*');
        $this->db->from('hotel_restaurants');
        $this->db->where('id', $id);
        $restaurant = $this->db->get()->row();

        if ($restaurant) {
            $hotel = $this->Common_model->getdata('hotel_admin', ['hotel_id' => $restaurant->hotel_id]);
            echo json_encode([
                'status' => 'success',
                'data'   => [
                    'hotel_id' => encrypt_id($restaurant->hotel_id),
                    'hotel_name' => $hotel->hotel_name ?? '',
                    'restaurant_name' => $restaurant->restaurant_name,
                    'restaurant_code' => $restaurant->restaurant_code,
                    'contact_number' => $restaurant->contact_number,
                    'email' => $restaurant->email,
                    'status' => $restaurant->status,
                    'restaurant_image' => $restaurant->restaurant_image
                ],
                'id' => encrypt_id($restaurant->id),
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Restaurant not found',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        }
    }

    // Update Restaurant
    public function update()
    {
        $id = decrypt_id($this->input->post('id'));

        // ✅ Get old image
        $old_data  = $this->db->where('id', $id)->get('hotel_restaurants')->row();
        $old_image = !empty($old_data->restaurant_image) ? $old_data->restaurant_image : '';

        $restaurant_image = $old_image;

        // ✅ Upload new image (optional)
        if (!empty($_FILES['restaurant_image']['name'])) {

            $upload_path = FCPATH . 'uploads/restaurant_images/';

            // create folder if not exists
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size']      = 2048;
            $config['file_name']     = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '_', $_FILES['restaurant_image']['name']);

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('restaurant_image')) {

                $uploadData       = $this->upload->data();
                $restaurant_image = $uploadData['file_name'];

                // delete old image only if new uploaded
                if (!empty($old_image) && file_exists($upload_path . $old_image)) {
                    unlink($upload_path . $old_image);
                }
            }
            // ❗ No else → don’t break update flow
        }

        // ✅ Existing Update Logic (unchanged)
        $data = [
            'hotel_id'         => decrypt_id($this->input->post('hotel_id')),
            'restaurant_name'  => trim($this->input->post('restaurant_name')),
            'restaurant_code'  => $this->input->post('restaurant_code'),
            'restaurant_type'  => $this->input->post('restaurant_type'),
            'opening_time'     => $this->input->post('opening_time'),
            'closing_time'     => $this->input->post('closing_time'),
            'capacity'         => $this->input->post('capacity'),
            'contact_number'   => $this->input->post('contact_number'),
            'email'            => $this->input->post('email'),
            'status'           => $this->input->post('status'),
            'restaurant_image' => $restaurant_image, // ✅ added
            'updated_at'       => date('Y-m-d H:i:s')
        ];

        if (empty($id) || empty($data['hotel_id']) || $data['restaurant_name'] == '' || $data['restaurant_code'] == '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Please fill all required restaurant details',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $this->db->where('id', $id);
        $update = $this->db->update('hotel_restaurants', $data);

        echo json_encode([
            'status'  => $update ? 'success' : 'error',
            'message' => $update ? 'Restaurant updated successfully' : 'Failed to update restaurant',
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
    }

    // Delete Restaurant
    public function delete()
    {
        $id = decrypt_id($this->input->post('id'));

        if (empty($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid restaurant ID',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $deleted = $this->db->where('id', $id)->delete('hotel_restaurants');

        if ($deleted) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Restaurant deleted successfully',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete restaurant',
                'csrfHash' => $this->security->get_csrf_hash()
            ]);
        }
    }
}

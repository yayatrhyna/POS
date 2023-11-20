<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cstore extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('lstore');
        $this->load->model('Stores');
        $this->load->model('Wearhouses');
        $this->load->model('Variants');
        $this->auth->check_admin_auth();
    }

    //Default loading for store system.
    public function index()
    {
        $content = $this->lstore->store_add_form();
        $this->template->full_admin_html_view($content);
    }

    //Insert store
    public function insert_store()
    {
        $this->form_validation->set_rules('store_name', display('store_name'), 'trim|required');
        $this->form_validation->set_rules('store_address', display('store_address'), 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => display('add_store')
            );
            $content = $this->parser->parse('store/add_store', $data, true);
            $this->template->full_admin_html_view($content);
        } else {
            $data = array(
                'store_id' => $this->auth->generator(15),
                'store_name' => $this->input->post('store_name'),
                'store_address' => $this->input->post('store_address'),
                'default_status' => $this->input->post('default_status')
            );

            $result = $this->Stores->store_entry($data);

            if ($result == TRUE) {

                $this->session->set_userdata(array('message' => display('successfully_added')));

                if (isset($_POST['add-store'])) {
                    redirect('Cstore/manage_store');
                } elseif (isset($_POST['add-store-another'])) {
                    redirect('Cstore');
                }

            } else {
                redirect('Cstore');
            }
        }
    }

    //Manage store
    public function manage_store()
    {
        $content = $this->lstore->store_list();
        $this->template->full_admin_html_view($content);;
    }

    //Store Update Form
    public function store_update_form($store_id)
    {
        $content = $this->lstore->store_edit_data($store_id);
        $this->menu = array('label' => 'Edit store', 'url' => 'Ccustomer');
        $this->template->full_admin_html_view($content);
    }

    // Store Update
    public function store_update($store_id = null)
    {
        $this->form_validation->set_rules('store_name', display('store_name'), 'trim|required');
        $this->form_validation->set_rules('store_address', display('store_address'), 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => display('manage_store')
            );
            $content = $this->parser->parse('store/store', $data, true);
            $this->template->full_admin_html_view($content);
        } else {
            $data = array(
                'store_name' => $this->input->post('store_name'),
                'store_address' => $this->input->post('store_address'),
                'default_status' => $this->input->post('default_status'),
            );

            $result = $this->Stores->update_store($data, $store_id);

            if ($result == TRUE) {
                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect('Cstore/manage_store');
            } else {
                redirect('Cstore/manage_store');
            }
        }
    }

    //Store Product
    // public function store_transfer()
    // {
    //     $content = $this->lstore->store_transfer_form();
    //     $this->template->full_admin_html_view($content);
    // }


    //Store transfer select
    public function store_transfer_select()
    {
        $CI =& get_instance();
        $CI->load->model('Products');
        $store_id = $this->input->post('store_id');
        $product_lists = $CI->Products->get_product_list_by_store($store_id);
        $store_list = $this->Stores->store_select($store_id);


        $stores = '';
        $stores .= "<select class=\"form-control -js-example-basic-single\" id=\"store\" name=\"t_store_id\" required=\"\" >";
        $stores .= "<option>Select Store</option>";
        foreach ($store_list as $store) {
            $stores .= "<option value=" . $store->store_id . ">" . $store->store_name . "</option>";
        }
        $stores .= "</select>";

        $products = '';
        $products .= "<select class=\"form-control \" id=\"product_name\" name=\"product_id\" required=\"\" >";
        $products .= "<option>Select Product</option>";
        foreach ($product_lists as $product_list) {
            $products .= "<option value=" . $product_list['product_id'] . ">" . $product_list['product_name'] . "-(" .
                $product_list['product_model'] . ")"
                . "</option>";
        }
        $products .= "</select>";

        $result['stores'] = $stores;
        $result['products'] = $products;
        echo json_encode($result);

    }


    public function store_transfer()
    {
        // $CI =& get_instance();
		// $CI->load->model('Stores');
		// $CI->load->model('Products');
		// $CI->load->model('Variants');
		// $store_list   = $CI->Stores->store_list();
		// $variant_list = $CI->Variants->variant_list();
		// $data = array(
		// 		'title' 		=> display('store_product_transfer'),
		// 		'store_list' 	=> $store_list,
		// 		'variant_list' 	=> $variant_list,
		// 	);
		// $store_product = $CI->parser->parse('store/store_product_transfer',$data,true);
		// $content = $store_product;

        $CI =& get_instance();
		$CI->load->model('Purchases');
		$CI->load->model('Stores');
		$CI->load->model('Variants');
        
		$all_supplier 	= $CI->Purchases->select_all_supplier();
		$store_list 	= $CI->Stores->store_list();
		$variant_list 	= $CI->Variants->variant_list();

		$data = array(
				'title'			 => display('store_product_transfer'),
				'all_supplier' 	 => $all_supplier,
				'store_list' 	 => $store_list,
				'variant_list'	 => $variant_list,
			);
		
		$storeForm = $CI->parser->parse('store/store_product_transfer2',$data,true);
		$content =  $storeForm;

        $this->template->full_admin_html_view($content);
    }



    public function product_search_by_store(){
        $CI =& get_instance();
        $CI->load->model('Products');
		$store_id = $this->input->post('store_id');			
        $product_info = $CI->Products->get_product_list_by_store($store_id);

      	$list[''] = "";
		foreach ($product_info as $value) {
			$json_product[] = array('label'=>$value['product_name'].'-('.$value['product_model'].')','value'=>$value['product_id']);
		} 
        echo json_encode($json_product);
	}


//get variant by existing purched product
    public function get_variant_by_store()
    {
        $store_id = $this->input->post('store_id');
        $product_id = $this->input->post('product_id');

        $this->db->select('a.*');
        $this->db->from('variant a');
        $this->db->join('transfer b', 'a.variant_id=b.variant_id');
        $this->db->where('b.store_id =', $store_id);
        $this->db->where('b.product_id =', $product_id);
        $this->db->group_by('a.variant_id');
        $variants = $this->db->get()->result();

        echo "<label for=\"variant\" class=\"col-sm-3 col-form-label\">" . display('store') . "<i class=\"text-danger\">*</i></label>
		<div class=\"col-sm-6\">
		<select class=\"form-control \" id=\"variant\" name=\"variant_id\" required=\"\" >";
        foreach ($variants as $variant) {
            echo "<option value=" . $variant->variant_id . ">" . $variant->variant_name . "</option>";
        }
        echo "</select>
		</div>";


    }


    //Insert store product
    public function insert_store_product()
    {
        // $transfer_to = $this->input->post('transfer_to');

        // if ($transfer_to == 2) {
        $quantity = $this->input->post('quantity');

        $data = array(
            'transfer_id' => $this->auth->generator(15),
            'store_id' => $this->input->post('store_id'),
            'product_id' => $this->input->post('product_id'),
            'variant_id' => $this->input->post('variant_id'),
            'quantity' => "-" . $quantity,
            'transfer_by' => $this->session->userdata('user_id'),
            't_store_id' => $this->input->post('t_store_id'),
            'date_time' => date("m-d-Y"),
            'status' => 1,
        );

        $data1 = array(
            'transfer_id' => $this->auth->generator(15),
            'store_id' => $this->input->post('t_store_id'),
            'product_id' => $this->input->post('product_id'),
            'variant_id' => $this->input->post('variant_id'),
            'quantity' => $quantity,
            'transfer_by' => $this->session->userdata('user_id'),
            't_store_id' => $this->input->post('store_id'),
            'date_time' => date("m-d-Y"),
        );

        $result = $this->Stores->store_transfer($data, $data1);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_inserted')));
            if (isset($_POST['add-store'])) {
                // redirect('Cstore/manage_store_product');
                redirect(base_url('Cstore/manage_store_product'));
            } elseif (isset($_POST['add-store-another'])) {
                redirect(base_url('Cstore/store_transfer'));
            }
        } else {
            $this->session->set_userdata(array('error_message' => display('product_is_not_available_please_purchase_product')));
            redirect('Cstore/store_transfer');
        }
        // }elseif ($transfer_to == 1) {
        // 	$quantity = $this->input->post('quantity');

        // 	$data = array(
        // 		'transfer_id'  => $this->auth->generator(15),
        // 		'store_id' 	   => $this->input->post('store_id'),
        // 		'product_id'   => $this->input->post('product_id'),
        // 		'variant_id'   => $this->input->post('variant_id'),
        // 		'quantity' 	   => "-".$quantity,
        // 		'transfer_by'  => $this->session->userdata('user_id'),
        // 		't_warehouse_id' => $this->input->post('t_warehouse_id'),
        // 		'status'		=> 2,
        // 		'date_time'    => date("m-d-Y"),
        // 	);


        // 	$data1 = array(
        // 		'transfer_id'  => $this->auth->generator(15),
        // 		'warehouse_id' => $this->input->post('t_warehouse_id'),
        // 		'product_id'   => $this->input->post('product_id'),
        // 		'variant_id'   => $this->input->post('variant_id'),
        // 		'quantity' 	   => $quantity,
        // 		'transfer_by'  => $this->session->userdata('user_id'),
        // 		't_store_id'   => $this->input->post('store_id'),
        // 		'date_time'    => date("m-d-Y"),
        // 	);

        // 	$result = $this->Wearhouses->wearhouse_to_store_transfer($data,$data1);

        // 	if ($result == TRUE) {
        // 		$this->session->set_userdata(array('message'=>display('successfully_inserted')));
        // 		redirect('Cstore/manage_store_product');
        // 	}else{
        // 		$this->session->set_userdata(array('error_message'=>display('product_is_not_available_please_purchase_product')));
        // 		redirect('Cstore/store_transfer');
        // 	}
        // }
    }


    public function insert_store_product2()
    {
        $quantity = $this->input->post('product_quantity');
        $product_id = $this->input->post('product_id');
        $store_id = $this->input->post('store_id_hidden');
        $variant_id = $this->input->post('variant_id');
        $t_store_id = $this->input->post('t_store_id');

        $total_product = count($product_id);
        for ($i=0; $i < $total_product; $i++) {
            $data = array(
                'transfer_id' => $this->auth->generator(15),
                'store_id' => $store_id,
                'product_id' => $product_id[$i],
                'variant_id' => $variant_id[$i],
                'quantity' => "-" . $quantity[$i],
                'transfer_by' => $this->session->userdata('user_id'),
                't_store_id' => $t_store_id,
                'date_time' => date("m-d-Y"),
                'status' => 1,
            );

            $data1 = array(
                'transfer_id' => $this->auth->generator(15),
                'store_id' => $t_store_id,
                'product_id' => $product_id[$i],
                'variant_id' => $variant_id[$i],
                'quantity' => $quantity[$i],
                'transfer_by' => $this->session->userdata('user_id'),
                't_store_id' => $store_id,
                'date_time' => date("m-d-Y"),
            );

            $result = $this->Stores->store_transfer($data, $data1);
        }

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_inserted')));
            redirect(base_url('Cstore/manage_store_product'));
            if (isset($_POST['add-store'])) {
                redirect(base_url('Cstore/manage_store_product'));
            } elseif (isset($_POST['add-store-another'])) {
                redirect(base_url('Cstore/store_transfer'));
            }
        } else {
            $this->session->set_userdata(array('error_message' => display('product_is_not_available_please_purchase_product')));
            redirect('Cstore/store_transfer');
        }
    }

    //Manage store
    public function manage_store_product()
    {
        $content = $this->lstore->store_product_list();
        $this->template->full_admin_html_view($content);;
    }

    //Store Product Update Form
    public function store_product_update_form($store_product_id)
    {
        $content = $this->lstore->store_product_edit_data($store_product_id);
        $this->template->full_admin_html_view($content);
    }

    // Store Product Update
    public function store_product_update($store_product_id = null)
    {

        $this->form_validation->set_rules('store_name', display('store_name'), 'trim|required');
        $this->form_validation->set_rules('product_name', display('product_name'), 'trim|required');
        $this->form_validation->set_rules('variant', display('variant'), 'trim|required');
        $this->form_validation->set_rules('quantity', display('quantity'), 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => display('add_store')
            );
            $content = $this->parser->parse('store/add_store', $data, true);
            $this->template->full_admin_html_view($content);
        } else {

            $data = array(
                'store_id' => $this->input->post('store_name'),
                'product_id' => $this->input->post('product_name'),
                'variant_id' => $this->input->post('variant'),
                'quantity' => $this->input->post('quantity'),
            );


            $result = $this->Stores->store_product_update($data, $store_product_id);
            if ($result == TRUE) {
                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect('Cstore/manage_store_product_product');
            } else {
                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect('Cstore/manage_store_product_product');
            }
        }
    }

    // Store Delete
    public function store_delete($store_id)
    {
        $result = $this->Stores->delete_store($store_id);
        if ($result == 1) {
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            redirect('Cstore/manage_store');
        } elseif ($result === 'default'){
            $this->session->set_userdata(array('error_message' => display('you_cant_delete_this_is_default_store')));
            redirect('Cstore/manage_store');
        }else {
            $this->session->set_userdata(array('error_message' => display('you_cant_delete_this_is_in_calculate_system')));
            redirect('Cstore/manage_store');
        }
    }

    // store product Delete
    public function store_product_delete()
    {
        $store_product_id = $this->input->post('store_product_id');
        $this->Stores->delete_store_product($store_product_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        return true;
    }

    // Update status of store
    public function update_status($store_id)
    {

                $this->db->set('default_status', 1)
                    ->where('store_id', $store_id)
                    ->update('store_set');

                $this->db->set('default_status', 0)
                    ->where('store_id !=', $store_id)
                    ->update('store_set');

                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect('Cstore/manage_store');

    }

    //Add Store CSV
    public function add_store_csv()
    {
        $CI =& get_instance();
        $data = array(
            'title' => display('import_store_csv')
        );
        $content = $CI->parser->parse('store/add_store_csv', $data, true);
        $this->template->full_admin_html_view($content);
    }

    //CSV Upload File
    function uploadCsv()
    {
        $count = 0;
        $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");

        if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {

            while ($csv_line = fgetcsv($fp, 1024)) {
                //keep this if condition if you want to remove the first row
                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                    $insert_csv = array();
                    $insert_csv['store_name'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                    $insert_csv['store_address'] = (!empty($csv_line[1]) ? $csv_line[1] : null);
                    $insert_csv['status'] = (!empty($csv_line[2]) ? $csv_line[2] : 0);
                }

                $data = array(
                    'store_id' => $this->auth->generator(10),
                    'store_name' => $insert_csv['store_name'],
                    'store_address' => $insert_csv['store_address'],
                    'default_status' => $insert_csv['status'],
                );

                if ($count > 0) {
                    $result = $this->db->select('*')
                        ->from('store_set')
                        ->where('store_name', $data['store_name'])
                        ->get()
                        ->num_rows();

                    if ($result == 0 && !empty($data['store_name']) && $data['default_status'] == 0) {
                        $this->db->insert('store_set', $data);
                        $this->session->set_userdata(array('message' => display('successfully_added')));
                    } else {
                        $this->db->set('store_name', $data['store_name']);
                        $this->db->where('store_name', $data['store_name']);
                        $this->db->update('store_set');
                        $this->session->set_userdata(array('error_message' => display('default_store_already_exists')));
                    }
                }
                $count++;
            }
        }

        fclose($fp) or die("Can't close file");
        if (isset($_POST['add-store'])) {
            redirect(base_url('Cstore/manage_store'));
            exit;
        } elseif (isset($_POST['add-store-another'])) {
            redirect(base_url('Cstore'));
            exit;
        }
    }
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Corder extends CI_Controller {
	
	function __construct() {
		parent::__construct();

	}
	public function index()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('Lorder');
		$content = $CI->lorder->order_add_form();
		$this->template->full_admin_html_view($content);
	}
	//Add new order
	public function new_order()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('Lorder');
		$content = $CI->lorder->order_add_form();
		$this->template->full_admin_html_view($content);
	}
	//Insert product and upload
	public function insert_order()
	{
        $CI =& get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Orders');
        $CI->load->model('website/Homes');
        $customer = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();

        $order_id = $CI->Orders->order_entry();

        $this->session->set_userdata(array('message'=>display('successfully_added')));
        $this->order_inserted_data($order_id);
        $customer_id = $this->session->userdata('customerId');

        $ship_short_address = $this->input->post('customer_name_others_address');
        if($this->input->post('customer_name_others')) {
            $shipping = array(
                'customer_id' => $customer_id,
                'order_id'=>$order_id,
                'customer_name' => $this->input->post('customer_name_others'),
                'first_name' => '',
                'last_name' => '',
                'customer_short_address' => $ship_short_address,
                'customer_address_1' => '',
                'customer_address_2' => '',
                'city' => '',
                'state' => '',
                'country' => '',
                'zip' => '',
                'company' => '',
                'customer_mobile' => '',
                'customer_email' => '',
            );
        }else{
           $customer = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();

            $shipping = array(
                'customer_id' => $customer_id,
                'order_id'=>$order_id,
                'customer_name' => $customer->customer_name,
                'first_name' =>($customer->first_name)? $customer->first_name : '',
                'last_name' => ($customer->last_name) ? $customer->last_name : '',
                'customer_short_address' =>($customer->customer_short_address)? $customer->customer_short_address: '',
                'customer_address_1' => ($customer->customer_address_1)? $customer->customer_address_1 : '',
                'customer_address_2' => ($customer->customer_address_2) ? $customer->customer_address_2 : '',
                'city' => ($customer->city) ? $customer->city : '',
                'state' => ($customer->state) ? $customer->state : '',
                'country' => ($customer->country) ? $customer->state : '',
                'zip' => ($customer->zip)? $customer->zip : '',
                'company' => ($customer->company) ? $customer->company : '',
                'customer_mobile' => ($customer->customer_mobile) ? $customer->customer_mobile :'',
                'customer_email' => ($customer->customer_email) ? $customer->customer_email : '',
            );

        }
        //Shipping information entry for existing customer

        $this->Homes->shipping_entry($shipping);
        $sms_status = $this->db->select('sms_service')->from('soft_setting')->get()->result_array();
        if ($sms_status[0]['sms_service'] == 1) {
            $CI->Homes->send_sms($order_id, $customer_id, 'Order');
        }
		if(isset($_POST['add-order'])){
			redirect(base_url('Corder/manage_order'));
		}elseif(isset($_POST['add-order-another'])){
			redirect(base_url('Corder'));
		}
	}



	//Retrive right now inserted data to cretae html
	public function order_inserted_data($order_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('Lorder');
		$content = $CI->lorder->order_html_data($order_id);	

		$this->template->full_admin_html_view($content);
	}	
	//Retrive right now inserted data to cretae html
	public function order_details_data($order_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('Lorder');
		$content = $CI->lorder->order_details_data($order_id);	
		$this->template->full_admin_html_view($content);
	}
	//Manage order
	public function manage_order()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('Lorder');
		$CI->load->model('Orders');

		$content = $CI->lorder->order_list();
		$this->template->full_admin_html_view($content);
	}

	//order Update Form
	public function order_update_form($order_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('Lorder');
		$content = $CI->lorder->order_edit_data($order_id);
		$this->template->full_admin_html_view($content);
	}
	//Search Inovoice Item
	public function search_inovoice_item()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('Lorder');

		$customer_id = $this->input->post('customer_id');
		$content = $CI->lorder->search_inovoice_item($customer_id);
		$this->template->full_admin_html_view($content);
	}
	// order Update
	public function order_update()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Orders');
        $CI->load->model('Soft_settings');
        $CI->load->library('occational');
        $CI->load->library('Pdfgenerator');
		$order_id = $CI->Orders->update_order();
		//pdf generate start
        $order_detail = $CI->Orders->retrieve_order_html_data($order_id);
        $subTotal_quantity 	= 0;
        $subTotal_cartoon 	= 0;
        $subTotal_discount 	= 0;

        if(!empty($order_detail)){
            foreach($order_detail as $k=>$v){
                $order_detail[$k]['final_date'] = $CI->occational->dateConvert($order_detail[$k]['date']);
                $subTotal_quantity = $subTotal_quantity+$order_detail[$k]['quantity'];
            }
            $i=0;
            foreach($order_detail as $k=>$v){$i++;
                $order_detail[$k]['sl']=$i;
            }
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Orders->retrieve_company();
        $data=array(
            'title'				=>	display('order_details'),
            'order_id'			=>	$order_detail[0]['order_id'],
            'order_no'			=>	$order_detail[0]['order'],
            'customer_address'	=>	$order_detail[0]['customer_short_address'],
            'customer_name'		=>	$order_detail[0]['customer_name'],
            'customer_mobile'	=>	$order_detail[0]['customer_mobile'],
            'customer_email'	=>	$order_detail[0]['customer_email'],
            'final_date'		=>	$order_detail[0]['final_date'],
            'total_amount'		=>	$order_detail[0]['total_amount'],
            'order_discount' 	=>	$order_detail[0]['order_discount'],
            'service_charge' 	=>	$order_detail[0]['service_charge'],
            'paid_amount'		=>	$order_detail[0]['paid_amount'],
            'due_amount'		=>	$order_detail[0]['due_amount'],
            'details'			=>	$order_detail[0]['details'],
            'subTotal_quantity'	=>	$subTotal_quantity,
            'order_all_data' 	=>	$order_detail,
            'company_info'		=>	$company_info,
            'currency' 			=> 	$currency_details[0]['currency_icon'],
            'position' 			=> 	$currency_details[0]['currency_position'],
        );

        $chapterList = $CI->parser->parse('order/order_pdf',$data,true);

        //PDF Generator
        $dompdf = new DOMPDF();
        $dompdf->load_html($chapterList);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('my-assets/pdf/'.$order_id.'.pdf', $output);
        $file_path = 'my-assets/pdf/'.$order_id.'.pdf';
        //File path save to database
        $CI->db->set('file_path',base_url($file_path));
        $CI->db->where('order_id',$order_id);
        $CI->db->update('order');
		//pdf generate end
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect('Corder/manage_order');
		// $this->order_inserted_data($order_id);
	}
	// order paid data
	public function order_paid_data($order_id){
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Orders');
		$order_id = $CI->Orders->order_paid_data($order_id);
		$this->session->set_userdata(array('message'=>display('successfully_added')));
		redirect('Corder/manage_order');
	}

	// retrieve_product_data
	public function retrieve_product_data()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Orders');
		$product_id = $this->input->post('product_id');
		$product_info = $CI->Orders->get_total_product($product_id);
		echo json_encode($product_info);
	}
	// product_delete
	public function order_delete($order_id)
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Orders');
		$result = $CI->Orders->delete_order($order_id);
		if ($result) {
			$this->session->set_userdata(array('message'=>display('successfully_delete')));
			redirect('Corder/manage_order');
		}	
	}
	//AJAX order STOCKs
	public function product_stock_check($product_id)
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Orders');

		$purchase_stocks = $CI->Orders->get_total_purchase_item($product_id);	
		$total_purchase = 0;		
		if(!empty($purchase_stocks)){	
			foreach($purchase_stocks as $k=>$v){
				$total_purchase = ($total_purchase + $purchase_stocks[$k]['quantity']);
			}
		}
		$sales_stocks = $CI->Orders->get_total_sales_item($product_id);
		$total_sales = 0;	
		if(!empty($sales_stocks)){	
			foreach($sales_stocks as $k=>$v){
				$total_sales = ($total_sales + $sales_stocks[$k]['quantity']);
			}
		}

		$final_total = ($total_purchase - $total_sales);
		return $final_total ;
	}

	//Search product by product name and category
	public function search_product(){
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Orders');
		$product_name = $this->input->post('product_name');
		$category_id  = $this->input->post('category_id');
		$product_search = $this->Orders->product_search($product_name,$category_id);
		if ($product_search) {
			foreach ($product_search as $product) {
				echo "<div class=\"col-xs-6 col-sm-4 col-md-2 col-p-3\">";
				echo "<div class=\"panel panel-bd product-panel select_product\">";
				echo "<div class=\"panel-body\">";
				echo "<img src=\"$product->image_thumb\" class=\"img-responsive\" alt=\"\">";
				echo "<input type=\"hidden\" name=\"select_product_id\" class=\"select_product_id\" value='".$product->product_id."'>";
				echo "</div>";
				echo "<div class=\"panel-footer\">$product->product_model - $product->product_name</div>";
				echo "</div>";
				echo "</div>";
			}
		}else{
			echo "420";
		}
	}

	//Insert new customer
	public function insert_customer(){
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Orders');

		$customer_id=$this->auth->generator(15);

	  	//Customer  basic information adding.
		$data=array(
			'customer_id' 		=> $customer_id,
			'customer_name' 	=> $this->input->post('customer_name'),
			'customer_mobile' 	=> $this->input->post('mobile'),
			'customer_email' 	=> $this->input->post('email'),
			'status' 			=> 1
		);

		$result=$this->Orders->customer_entry($data);

		if ($result == TRUE) {		
			$this->session->set_userdata(array('message'=>display('successfully_added')));
			redirect(base_url('Corder/pos_order'));
		}else{
			$this->session->set_userdata(array('error_message'=>display('already_exists')));
			redirect(base_url('Corder/pos_order'));
		}
	}

	//This function is used to Generate Key
	public function generator($lenth)
	{
		$number=array("1","2","3","4","5","6","7","8","9");

		for($i=0; $i<$lenth; $i++)
		{
			$rand_value=rand(0,8);
			$rand_number=$number["$rand_value"];

			if(empty($con))
			{ 
				$con=$rand_number;
			}
			else
			{
				$con="$con"."$rand_number";}
			}
			return $con;
		}
	}
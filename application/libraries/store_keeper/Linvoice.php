<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Linvoice {
	
	//Invoice add form
	public function invoice_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
		$CI->load->model('store_keeper/Stores');
		$CI->load->model('store_keeper/Variants');
		$CI->load->model('store_keeper/Customers');
		$CI->load->model('store_keeper/Shipping_methods');

		$store_list 	    = $CI->Stores->store_list();
		$variant_list 	    = $CI->Variants->variant_list();
		$shipping_methods 	= $CI->Shipping_methods->shipping_method_list();

//		$terminal_list  = $CI->Invoices->terminal_list();
		$customer =$CI->Customers->customer_list();
	
		$data = array(
				'title' 		=> display('new_invoice'),
				'store_list' 	=> $store_list,
				'variant_list' 	=> $variant_list,
//				'terminal_list' => $terminal_list,
				'customer' 		=> $customer[0],
            'shipping_methods'=>$shipping_methods
			);
		$invoiceForm = $CI->parser->parse('invoice/add_invoice_form',$data,true);
		return $invoiceForm;
	}

	//Retrieve  Invoice List
	public function invoice_list()
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
		$CI->load->model('store_keeper/Soft_settings');
		$CI->load->library('store_keeperoccational');
		
		$invoices_list = $CI->Invoices->invoice_list();
		if(!empty($invoices_list)){
			$i=0;
			foreach($invoices_list as $k=>$v){
				$invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);
				$i++;
			   	$invoices_list[$k]['sl']=$i;
			}
		}
		$currency_details = $CI->Soft_settings->retrieve_currency_info();
		$data = array(
				'title' => display('manage_invoice'),
				'invoices_list' => $invoices_list,
				'currency' => $currency_details[0]['currency_icon'],
				'position' => $currency_details[0]['currency_position'],
			);
		$invoiceList = $CI->parser->parse('invoice/invoice',$data,true);
		return $invoiceList;
	}

	//Pos invoice add form
	public function pos_invoice_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
		$CI->load->model('store_keeper/Stores');
		$CI->load->model('store_keeper/Reports');
		$customer_details = $CI->Invoices->pos_customer_setup();
		$product_list 	  = $CI->Invoices->product_list();
		$category_list	  = $CI->Invoices->category_list();
		$customer_list	  = $CI->Invoices->customer_list();
		$store_list   	  = $CI->Invoices->store_list();
//		$terminal_list    = $CI->Invoices->terminal_list();
		$company_info 	  = $CI->Reports->retrieve_company();

		$data = array(
				'title' 		=> display('add_pos_invoice'),
				'sidebar_collapse' => 'sidebar-collapse',
				'product_list' 	=> $product_list,
				'category_list' => $category_list,
				'customer_details' => $customer_details,
				'customer_list' => $customer_list,
				'store_list' 	=> $store_list,
//				'terminal_list' => $terminal_list,
				'company_info'  => $company_info,
				'company_name'  => $company_info[0]['company_name'],
			);
		$invoiceForm = $CI->parser->parse('invoice/add_pos_invoice_form',$data,true);
		return $invoiceForm;
	}

	//Retrieve  Invoice List
	public function search_inovoice_item($customer_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
		$CI->load->library('store_keeperoccational');
		$invoices_list = $CI->Invoices->search_inovoice_item($customer_id);

		if(!empty($invoices_list)){
			foreach($invoices_list as $k=>$v){
				$invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);
			}
			$i=0;
			foreach($invoices_list as $k=>$v){$i++;
			   $invoices_list[$k]['sl']=$i;
			}
		}
		$data = array(
				'title' => display('invoice_search_item'),
				'invoices_list' => $invoices_list
			);
		$invoiceList = $CI->parser->parse('invoice/invoice',$data,true);
		return $invoiceList;
	}

	//Insert invoice
	public function insert_invoice($data)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
        $CI->Invoices->invoice_entry($data);
		return true;
	}

	//Invoice Edit Data
	public function invoice_edit_data($invoice_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
		$CI->load->model('store_keeper/Stores');
		$CI->load->model('store_keeper/Shipping_methods');
		$invoice_detail = $CI->Invoices->retrieve_invoice_editdata($invoice_id);
		$shipping_methods 	= $CI->Shipping_methods->shipping_method_list();
		$store_id 		= $invoice_detail[0]['store_id'];
		$store_list 	= $CI->Stores->store_list();
		$store_list_selected = $CI->Stores->store_list_selected($store_id);
//		$terminal_list  = $CI->Invoices->terminal_list();

		$i=0;
		foreach($invoice_detail as $k=>$v){$i++;
		   $invoice_detail[$k]['sl']=$i;
		}

		$data=array(
			'title'				=>	display('invoice_edit'),
			'invoice_id'		=>	$invoice_detail[0]['invoice_id'],
			'customer_id'		=>	$invoice_detail[0]['customer_id'],
			'store_id'			=>	$invoice_detail[0]['store_id'],
			'invoice'			=>	$invoice_detail[0]['invoice'],
			'customer_name'		=>	$invoice_detail[0]['customer_name'],
			'date'				=>	$invoice_detail[0]['date'],
			'total_amount'		=>	$invoice_detail[0]['total_amount'],
			'paid_amount'		=>	$invoice_detail[0]['paid_amount'],
			'due_amount'		=>	$invoice_detail[0]['due_amount'],
			'total_discount'	=>	$invoice_detail[0]['total_discount'],
			'invoice_discount'	=>	$invoice_detail[0]['invoice_discount'],
			'service_charge'	=>	$invoice_detail[0]['service_charge'],
			'shipping_charge'	=>	$invoice_detail[0]['shipping_charge'],
			'shipping_method_id'	=>	$invoice_detail[0]['shipping_method'],
			'invoice_details'	=>	$invoice_detail[0]['invoice_details'],
			'invoice_status'	=>	$invoice_detail[0]['invoice_status'],
			'invoice_all_data'	=>	$invoice_detail,
			'store_list'		=>	$store_list,
			'store_list_selected'=>	$store_list_selected,
            'shipping_methods'=>$shipping_methods
//			'terminal_list'     =>	$terminal_list,
			);

		$chapterList = $CI->parser->parse('invoice/edit_invoice_form',$data,true);
		return $chapterList;
	}

	//Invoice html Data
	public function invoice_html_data($invoice_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
		$CI->load->model('store_keeper/Soft_settings');
		$CI->load->library('store_keeperoccational');
		$CI->load->model('store_keeper/Shipping_methods');
		$invoice_detail = $CI->Invoices->retrieve_invoice_html_data($invoice_id);

        $shipping_method 	= $CI->Shipping_methods->shipping_method_search_item($invoice_detail[0]['shipping_method']);

		$subTotal_quantity 	= 0;
		$subTotal_cartoon 	= 0;
		$subTotal_discount 	= 0;

		if(!empty($invoice_detail)){
			foreach($invoice_detail as $k=>$v){
				$time_invoice_created = substr($invoice_detail[$k]['invoice_datetime_created'], strpos($invoice_detail[$k]['invoice_datetime_created'], " ") + 1);
				$invoice_detail[$k]['final_date'] = $CI->occational->dateConvert($invoice_detail[$k]['date']) . ' ' . $time_invoice_created;
				$subTotal_quantity = $subTotal_quantity+$invoice_detail[$k]['quantity'];
			}
			$i=0;
			foreach($invoice_detail as $k=>$v){$i++;
			   $invoice_detail[$k]['sl']=$i;
			}
		}

		$currency_details = $CI->Soft_settings->retrieve_currency_info();
		$company_info 	  = $CI->Invoices->retrieve_company();
		$store_code = strtok($invoice_detail[0]['store_name'], '-');

		$data=array(
			'title'				=>	display('invoice_details'),
			'full_invoice_code'	=>	$store_code.'-'.$invoice_detail[0]['invoice'],
			'invoice_id'		=>	$invoice_detail[0]['invoice_id'],
			'invoice_no'		=>	$invoice_detail[0]['invoice'],
			'customer_name'		=>	$invoice_detail[0]['customer_name'],
			'customer_mobile'	=>	$invoice_detail[0]['customer_mobile'],
			'customer_email'	=>	$invoice_detail[0]['customer_email'],
			'customer_address'	=>	$invoice_detail[0]['customer_address_1'],
			'final_date'		=>	$invoice_detail[0]['final_date'],
			'total_amount'		=>	$invoice_detail[0]['total_amount'],
			'total_discount'	=>	$invoice_detail[0]['total_discount'],
			'invoice_discount'	=>	$invoice_detail[0]['invoice_discount'],
			'service_charge'	=>	$invoice_detail[0]['service_charge'],
			'shipping_charge'	=>	$invoice_detail[0]['shipping_charge'],
			'shipping_method'	=>	$shipping_method[0]['method_name'],
			'paid_amount'		=>	$invoice_detail[0]['paid_amount'],
			'due_amount'		=>	$invoice_detail[0]['due_amount'],
			'invoice_details'	=>	$invoice_detail[0]['invoice_details'],
			'subTotal_quantity'	=>	$subTotal_quantity,
			'invoice_all_data'	=>	$invoice_detail,
			'company_info'		=>	$company_info,
			'currency' 			=>  $currency_details[0]['currency_icon'],
			'position' 			=>  $currency_details[0]['currency_position'],
            'ship_customer_short_address'	=>	$invoice_detail[0]['ship_customer_short_address'],
            'ship_customer_name'		=>	$invoice_detail[0]['ship_customer_name'],
            'ship_customer_mobile'	=>	$invoice_detail[0]['ship_customer_mobile'],
            'ship_customer_email'	=>	$invoice_detail[0]['ship_customer_email'],
			);
		$chapterList = $CI->parser->parse('invoice/invoice_html',$data,true);
		return $chapterList;
	}

	//POS invoice html Data
	public function pos_invoice_html_data($invoice_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
		$CI->load->model('store_keeper/Soft_settings');
		$CI->load->library('store_keeperoccational');
		$invoice_detail = $CI->Invoices->retrieve_invoice_html_data($invoice_id);

		$subTotal_quantity = 0;
		$subTotal_cartoon = 0;
		$subTotal_discount = 0;
		if(!empty($invoice_detail)){
			foreach($invoice_detail as $k=>$v){
				$invoice_detail[$k]['final_date'] = $CI->occational->dateConvert($invoice_detail[$k]['date']);
				$subTotal_quantity = $subTotal_quantity+$invoice_detail[$k]['quantity'];
			}
			$i=0;
			foreach($invoice_detail as $k=>$v){$i++;
			   $invoice_detail[$k]['sl']=$i;
			}
		}

		$currency_details = $CI->Soft_settings->retrieve_currency_info();
		$company_info = $CI->Invoices->retrieve_company();
		$data=array(
			'title'				=>	display('invoice_details'),
			'invoice_id'		=>	$invoice_detail[0]['invoice_id'],
			'invoice_no'		=>	$invoice_detail[0]['invoice'],
			'customer_name'		=>	$invoice_detail[0]['customer_name'],
			'customer_address'	=>	$invoice_detail[0]['customer_short_address'],
			'customer_mobile'	=>	$invoice_detail[0]['customer_mobile'],
			'customer_email'	=>	$invoice_detail[0]['customer_email'],
			'final_date'		=>	$invoice_detail[0]['final_date'],
			'total_amount'		=>	$invoice_detail[0]['total_amount'],
			'subTotal_discount'	=>	$invoice_detail[0]['total_discount'],
			'service_charge'	=>	$invoice_detail[0]['service_charge'],
			'shipping_charge'	=>	$invoice_detail[0]['shipping_charge'],
			'paid_amount'		=>	$invoice_detail[0]['paid_amount'],
			'due_amount'		=>	$invoice_detail[0]['due_amount'],
			'invoice_details'	=>	$invoice_detail[0]['invoice_details'],
			'subTotal_quantity'	=>	$subTotal_quantity,
			'invoice_all_data'	=>	$invoice_detail,
			'company_info'		=>	$company_info,
			'currency' 			=> $currency_details[0]['currency_icon'],
			'position' 			=> $currency_details[0]['currency_position'],
			);
		$chapterList = $CI->parser->parse('invoice/pos_invoice_html',$data,true);
		return $chapterList;
	}


	public function pos_invoice_html_data_redirect($invoice_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Invoices');
		$CI->load->model('store_keeper/Soft_settings');
		$CI->load->library('store_keeperoccational');
		$invoice_detail = $CI->Invoices->retrieve_invoice_html_data($invoice_id);

		$subTotal_quantity = 0;
		$subTotal_cartoon = 0;
		$subTotal_discount = 0;
		if(!empty($invoice_detail)){
			foreach($invoice_detail as $k=>$v){
				$time = $invoice_detail[$k]['invoice_datetime_created'];    
				$invoice_time = substr($time, strpos($time, " ") + 1);

				$invoice_detail[$k]['final_date'] = $CI->occational->dateConvert($invoice_detail[$k]['date']);
				$invoice_detail[$k]['invoice_time'] = $invoice_time . ' WIB';
				$subTotal_quantity = $subTotal_quantity+$invoice_detail[$k]['quantity'];
			}
			$i=0;
			foreach($invoice_detail as $k=>$v){$i++;
			   $invoice_detail[$k]['sl']=$i;
			}
		}

		$user = $CI->db->select("CONCAT(first_name, ' ', last_name) as name")->get_where("users", ["user_id"=>$invoice_detail[0]['user_id']])->row_array();

		// get store code
		$store_id = $invoice_detail[0]['store_id'];
		$get_store = $CI->db->select("*")->get_where("store_set", ["store_id"=>$store_id])->row_array();
		$store_code = strtok($get_store['store_name'], '-');
		$store_address = $get_store['store_address'];
		$total_discount = $invoice_detail[0]['total_discount']+$invoice_detail[0]['invoice_discount'];

		$inv_id = $invoice_detail[0]['invoice_id'];
		$get_payment = $CI->db->select("*")->get_where("cardpayment", ["invoice_id"=>$inv_id])->row_array();
		$payment_name = 'Cash';
		$payment_number = null;
		if (!empty($get_payment)) {
			$payment_name = $get_payment['card_type'];
			$payment_number = $get_payment['card_no'];
		}

		$currency_details = $CI->Soft_settings->retrieve_currency_info();
		$company_info = $CI->Invoices->retrieve_company();
		$data=array(
			'title'				=>	display('invoice_details'),
			'store_code'		=>	$store_code,
			'store_address'		=>	$store_address,
			'payment_name'		=>	$payment_name,
			'payment_number'	=>	$payment_number,
			'invoice_id'		=>	$invoice_detail[0]['invoice_id'],
			'invoice_no'		=>	$invoice_detail[0]['invoice'],
			'customer_name'		=>	$invoice_detail[0]['customer_name'],
			'customer_address'	=>	$invoice_detail[0]['customer_short_address'],
			'customer_mobile'	=>	$invoice_detail[0]['customer_mobile'],
			'customer_email'	=>	$invoice_detail[0]['customer_email'],
			'final_date'		=>	$invoice_detail[0]['final_date'],
			'invoice_time'		=>	$invoice_detail[0]['invoice_time'],
			'total_amount'		=>	$invoice_detail[0]['total_amount'],
			'total_discount'	=>	$total_discount,
			'subTotal_discount'	=>	$invoice_detail[0]['total_discount'],
			'service_charge'	=>	$invoice_detail[0]['service_charge'],
			'shipping_charge'	=>	$invoice_detail[0]['shipping_charge'],
			'paid_amount'		=>	$invoice_detail[0]['paid_amount'],
			'due_amount'		=>	$invoice_detail[0]['due_amount'],
			'invoice_details'	=>	$invoice_detail[0]['invoice_details'],
			'subTotal_quantity'	=>	$subTotal_quantity,
			'invoice_all_data'	=>	$invoice_detail,
			'company_info'		=>	$company_info,
			'currency' 			=> $currency_details[0]['currency_icon'],
			'position' 			=> $currency_details[0]['currency_position'],
			'user_name'			=> $user["name"]
		);

		$CI->load->view('invoice/new_invoice',$data);
	}
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lstore_invoice {

	//Invoice add form
	public function invoice_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Store_invoices');
		$CI->load->model('store_keeper/Stores');
		$CI->load->model('store_keeper/Variants');

		$store_list 	= $CI->Stores->store_list();
		$variant_list 	= $CI->Variants->variant_list();
		$terminal_list  = $CI->Store_invoices->terminal_list();

		$data = array(
				'title' 		=> display('new_invoice'),
				'store_list' 	=> $store_list,
				'variant_list' 	=> $variant_list,
				'terminal_list' => $terminal_list,
			);
		$invoiceForm = $CI->parser->parse('store_invoice/add_invoice_form',$data,true);
		return $invoiceForm;
	}

	//Retrieve  Invoice List
	public function invoice_list()
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Store_invoices');
		$CI->load->model('store_keeper/Soft_settings');
		$CI->load->library('store_keeperoccational');

		$store_invoices_list = $CI->Store_invoices->invoice_list();

		if(!empty($store_invoices_list)){
			foreach($store_invoices_list as $k=>$v){
				$store_invoices_list[$k]['final_date'] = $CI->occational->dateConvert($store_invoices_list[$k]['date']);
			}
			$i=0;
			foreach($store_invoices_list as $k=>$v){$i++;
			   $store_invoices_list[$k]['sl']=$i;
			}
		}
		$currency_details = $CI->Soft_settings->retrieve_currency_info();
		$data = array(
				'title' => display('manage_invoice'),
				'store_invoices_list' => $store_invoices_list,
				'currency' => $currency_details[0]['currency_icon'],
				'position' => $currency_details[0]['currency_position'],
			);
		$invoiceList = $CI->parser->parse('store_invoice/invoice',$data,true);
		return $invoiceList;
	}

	//Pos invoice add form
	public function pos_invoice_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Store_invoices');
		$CI->load->model('store_keeper/Stores');
		$CI->load->model('store_keeper/Reports');
		$customer_details = $CI->Store_invoices->pos_customer_setup();
		$product_list 	  = $CI->Store_invoices->product_list();
		$category_list	  = $CI->Store_invoices->category_list();
		$customer_list	  = $CI->Store_invoices->customer_list();
		$store_list   	  = $CI->Store_invoices->store_list();
		$terminal_list    = $CI->Store_invoices->terminal_list();
		$company_info 	  = $CI->Reports->retrieve_company();

		$data = array(
			'title' 		=> display('add_pos_invoice'),
			'sidebar_collapse' => 'sidebar-collapse',
			'product_list' 	=> $product_list,
			'category_list' => $category_list,
			'customer_details' => $customer_details,
			'customer_list' => $customer_list,
			'store_list' 	=> $store_list,
			'terminal_list' => $terminal_list,
			'company_info'  => $company_info,
			'company_name'  => $company_info[0]['company_name'],
		);
		$invoiceForm = $CI->parser->parse('store_invoice/add_pos_invoice_form',$data,true);
		return $invoiceForm;
	}

	//Retrieve  Invoice List
	public function search_inovoice_item($customer_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Store_invoices');
		$CI->load->library('store_keeperoccational');
		$store_invoices_list = $CI->Store_invoices->search_inovoice_item($customer_id);
		if(!empty($store_invoices_list)){
			foreach($store_invoices_list as $k=>$v){
				$store_invoices_list[$k]['final_date'] = $CI->occational->dateConvert($store_invoices_list[$k]['date']);
			}
			$i=0;
			foreach($store_invoices_list as $k=>$v){$i++;
			   $store_invoices_list[$k]['sl']=$i;
			}
		}
		$data = array(
				'title' => display('invoice_search_item'),
				'store_invoices_list' => $store_invoices_list
			);
		$invoiceList = $CI->parser->parse('store_invoice/invoice',$data,true);
		return $invoiceList;
	}

	//Insert invoice
	public function insert_invoice($data)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Store_invoices');
        $CI->Store_invoices->invoice_entry($data);
		return true;
	}

	//Invoice Edit Data
	public function invoice_edit_data($invoice_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Store_invoices');
		$CI->load->model('store_keeper/Stores');
		$invoice_detail = $CI->Store_invoices->retrieve_invoice_editdata($invoice_id);

		$store_id 		= $invoice_detail[0]['store_id'];
		$store_list 	= $CI->Stores->store_list();
		$store_list_selected = $CI->Stores->store_list_selected($store_id);
		$terminal_list  = $CI->Store_invoices->terminal_list();

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
			'invoice_details'	=>	$invoice_detail[0]['invoice_details'],
			'invoice_status'	=>	$invoice_detail[0]['invoice_status'],
			'invoice_all_data'	=>	$invoice_detail,
			'store_list'		=>	$store_list,
			'store_list_selected'=>	$store_list_selected,
			'terminal_list'     =>	$terminal_list,
			);
		$chapterList = $CI->parser->parse('store_invoice/edit_invoice_form',$data,true);
		return $chapterList;
	}

	//Invoice html Data
	public function invoice_html_data($invoice_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Store_invoices');
		$CI->load->model('store_keeper/Soft_settings');
		$CI->load->library('store_keeperoccational');
		$invoice_detail = $CI->Store_invoices->retrieve_invoice_html_data($invoice_id);

		$subTotal_quantity 	= 0;
		$subTotal_cartoon 	= 0;
		$subTotal_discount 	= 0;

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
		$company_info 	  = $CI->Store_invoices->retrieve_company();

		$data=array(
			'title'				=>	display('invoice_details'),
			'invoice_id'		=>	$invoice_detail[0]['invoice_id'],
			'invoice_no'		=>	$invoice_detail[0]['invoice'],
			'customer_name'		=>	$invoice_detail[0]['customer_name'],
			'customer_mobile'	=>	$invoice_detail[0]['customer_mobile'],
			'customer_email'	=>	$invoice_detail[0]['customer_email'],
			'customer_address'	=>	$invoice_detail[0]['customer_address_1'],
			'final_date'		=>	$invoice_detail[0]['final_date'],
			'total_amount'		=>	$invoice_detail[0]['total_amount'],
			'invoice_discount'	=>	$invoice_detail[0]['invoice_discount'],
			'service_charge'	=>	$invoice_detail[0]['service_charge'],
			'paid_amount'		=>	$invoice_detail[0]['paid_amount'],
			'due_amount'		=>	$invoice_detail[0]['due_amount'],
			'invoice_details'	=>	$invoice_detail[0]['invoice_details'],
			'subTotal_quantity'	=>	$subTotal_quantity,
			'invoice_all_data'	=>	$invoice_detail,
			'company_info'		=>	$company_info,
			'currency' 			=>  $currency_details[0]['currency_icon'],
			'position' 			=>  $currency_details[0]['currency_position'],
			);
		$chapterList = $CI->parser->parse('store_invoice/invoice_html',$data,true);
		return $chapterList;
	}

	//POS invoice html Data
	public function pos_invoice_html_data($invoice_id)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Store_invoices');
		$CI->load->model('store_keeper/Soft_settings');
		$CI->load->library('store_keeperoccational');
		$invoice_detail = $CI->Store_invoices->retrieve_invoice_html_data($invoice_id);

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
		$company_info = $CI->Store_invoices->retrieve_company();
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
			'paid_amount'		=>	$invoice_detail[0]['paid_amount'],
			'due_amount'		=>	$invoice_detail[0]['due_amount'],
			'invoice_details'	=>	$invoice_detail[0]['invoice_details'],
			'subTotal_quantity'	=>	$subTotal_quantity,
			'invoice_all_data'	=>	$invoice_detail,
			'company_info'		=>	$company_info,
			'currency' 			=> $currency_details[0]['currency_icon'],
			'position' 			=> $currency_details[0]['currency_position'],
			);
		$chapterList = $CI->parser->parse('store_invoice/pos_invoice_html',$data,true);
		return $chapterList;
	}


	// Stock Report Variant Wise
	public function stock_report_variant_wise($from_date,$to_date,$store_id,$links,$per_page,$page)
	{
		$CI =& get_instance();
		$CI->load->model('store_keeper/Reports');
		$CI->load->model('store_keeper/Products');
		$CI->load->model('store_keeper/Stores');
		$CI->load->model('store_keeper/Soft_settings');
		$CI->load->library('store_keeperoccational');

		$stok_report   = $CI->Store_invoices->stock_report_variant_bydate($from_date,$to_date,$store_id,$per_page,$page);
		$product_list  = $CI->Products->product_list();
		$store_list    = $CI->Stores->store_list();
		$sub_total_in  =0;
		$sub_total_out =0;
		$sub_total_stock=0;

		if(($stok_report)){
			$i=$page;
			foreach($stok_report as $k=>$v){$i++;
			   $stok_report[$k]['sl']=$i;
			}
			foreach($stok_report as $k=>$v){$i++;

				$sales = $CI->db->select("
						sum(quantity) as totalSalesQnty,
						quantity
					")
						->from('invoice_details')
						->where('product_id',$v['product_id'])
						->where('variant_id',$v['variant_id'])
						->where('store_id',$v['store_id'])
						->get()
						->row();

			    $stok_report[$k]['stok_quantity'] = ($stok_report[$k]['totalPrhcsCtn']-$sales->totalSalesQnty);
			    $stok_report[$k]['SubTotalOut'] = ($sub_total_out + $sales->totalSalesQnty);
			    $sub_total_out = $stok_report[$k]['SubTotalOut'];
			    $stok_report[$k]['SubTotalIn'] = ($sub_total_in + $stok_report[$k]['totalPrhcsCtn']);
			    $sub_total_in = $stok_report[$k]['SubTotalIn'];
			    $stok_report[$k]['in_qnty'] = $stok_report[$k]['totalPrhcsCtn'];
			    $stok_report[$k]['out_qnty'] = $sales->totalSalesQnty;
			    $stok_report[$k]['SubTotalStock'] = ($sub_total_stock + $stok_report[$k]['stok_quantity']);
			    $sub_total_stock = $stok_report[$k]['SubTotalStock'];
			}
		}else{
			// $CI->session->set_userdata('error_message',display('stock_not_available'));
			// redirect('Store_invoice');
		}

		$currency_details = $CI->Soft_settings->retrieve_currency_info();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' 		=> display('stock_report_store_wise'),
				'stok_report' 	=> $stok_report,
				'product_model' => @$stok_report[0]['product_model'],
				'links' 		=> $links,
				'date' 			=> '',
				'sub_total_in' 	=> $sub_total_in,
				'sub_total_out' => $sub_total_out,
				'sub_total_stock'=>$sub_total_stock,
				'product_list' 	=> $product_list,
				'store_list' 	=> $store_list,
				'company_info' 	=> $company_info,
				'currency' 		=> $currency_details[0]['currency_icon'],
				'position' 		=> $currency_details[0]['currency_position'],
			);

		$reportList = $CI->parser->parse('store_invoice/stock_report_variant_wise',$data,true);
		return $reportList;
	}
}
?>

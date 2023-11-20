<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_dashboard extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('Soft_settings');
		$this->load->model('Customers');
		$this->load->model('Products');
		$this->load->model('Suppliers');
		$this->load->model('Invoices');
		$this->load->model('Purchases');
		$this->load->model('Reports');
		$this->load->model('Accounts');
		$this->load->model('Users');
		$this->load->library('lreport');
		$this->load->library('occational');
		$this->load->library('luser');
	}
    //Default index page loading
	public function index(){

		if (!$this->auth->is_logged() )
		{
			$this->output->set_header("Location: ".base_url().'admin', TRUE, 302);
		}
		$this->auth->check_admin_store_auth();
		$total_customer 	= $this->Customers->count_customer();
		$total_product 		= $this->Products->count_product();
		$total_suppliers 	= $this->Suppliers->count_supplier();
		$total_sales 		= $this->Invoices->count_invoice();
		$total_store_invoice= $this->Invoices->total_store_invoice();
		$total_purchase 	= $this->Purchases->count_purchase();

		$this->Accounts->accounts_summary(1);
		$total_expese 		= $this->Accounts->sub_total;
		$monthly_sales_report = $this->Reports->monthly_sales_report();
		$sales_report 		= $this->Reports->todays_total_sales_report();
		$purchase_report 	= $this->Reports->todays_total_purchase_report();
		$discount_report 	= $this->Reports->todays_total_discount_report();

		$currency_details 	= $this->Soft_settings->retrieve_currency_info();


		$data = array(
			'title' 			=> display('dashboard'),
			'total_customer' 	=> $total_customer,
			'total_product' 	=> $total_product,
			'total_suppliers' 	=> $total_suppliers,
			'total_sales' 		=> $total_sales,
			'total_purchase' 	=> $total_purchase,
			'total_store_invoice' 	=> $total_store_invoice,
			'sales_amount' 		=> number_format($sales_report[0]['total_sale'], 2, '.', ','),
			'purchase_amount' 	=> number_format($purchase_report[0]['total_purchase'], 2, '.', ','),
			'discount_amount' 	=> number_format($discount_report[0]['total_discount'], 2, '.', ','),
			'total_expese' 		=> $total_expese,
			'monthly_sales_report' => $monthly_sales_report,
			'currency' 			=> $currency_details[0]['currency_icon'],
			'position' 			=> $currency_details[0]['currency_position'],
		);

		$content = $this->parser->parse('include/admin_home',$data,true);
		$this->template->full_admin_html_view($content);
	}

	#==============Todays_sales_report============#
	public function todays_sales_report()
	{
    $this->session->unset_userdata('report_sales_from_date');
    $this->session->unset_userdata('report_sales_to_date');
	$this->session->unset_userdata('filter_paymment');

		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();

		#
        #pagination starts
        #
		$config["base_url"] = base_url('Admin_dashboard/todays_sales_report/');
		$config["total_rows"] = $this->Reports->todays_sales_report_count();
		$config["per_page"] = 10;
		$config["uri_segment"] = 3;
		$config["num_links"] = 5; 
		/* This Application Must Be Used With BootStrap 3 * */
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$links = $this->pagination->create_links();
        #
        #pagination ends
        # 

		$content = $CI->lreport->todays_sales_report($links,$config["per_page"],$page);
		$this->template->full_admin_html_view($content);
	}


  public function export_invoice()
  {
    // $this->load->library('Excel');
    $CI = &get_instance();
    $CI->load->library('occational');
    $trx_status = $this->input->post('trx_status');
    $from_date = $this->input->post('export_from_date');
    $to_date = $this->input->post('export_to_date');

	if ($from_date > $to_date) {
		return redirect('/Admin_dashboard/todays_sales_report');
	}
  
	if ($from_date != '' && $to_date != '') {
		if ($from_date > $to_date) {
			return redirect('/Admin_dashboard/todays_sales_report');
		}
	}

    // this the real one
	$all_data = null;
    $today = date('m-d-Y');
    $this->db->select("a.*,b.customer_id,b.customer_name,c.*,d.*,e.*");
    $this->db->from('invoice a');
    $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
    $this->db->join('store_set c', 'c.store_id = a.store_id');
	$this->db->join('invoice_details d', 'd.invoice_id = a.invoice_id');
	$this->db->join('product_information e', 'e.product_id = d.product_id');
	if ($from_date != '' && $to_date != '') {
		$this->db->where('a.date >=', $from_date);
		$this->db->where('a.date <=', $to_date);
	}
  
	if ($from_date != '' && $to_date == '') {
		$this->db->where('a.date', $from_date);
	}

	if ($from_date == '' && $to_date != '') {
		$this->db->where('a.date', $to_date);
	}
    // $this->db->where('a.date', $today);
    // $this->db->limit($per_page, $page);
    $this->db->order_by('a.invoice', 'desc');
    // $query = $this->db->get();
    // return $query->result_array();


    // $all_data = null;
    // $this->db->select('a.*,b.*,c.order,d.*,u.first_name,u.last_name');
    // $this->db->from('invoice a');
    // $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
    // $this->db->join('order c', 'c.order_id = a.order_id', 'left');
    // $this->db->join('store_set d', 'd.store_id = a.store_id');
    // $this->db->join('users u', 'u.user_id = a.user_id');
    // if ($trx_status != "all") {
    //   $this->db->where('a.invoice_status', $trx_status);
    // }
    // $this->db->order_by('a.invoice', 'desc');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      $all_data = $query->result_array();
    } else {
      return redirect('/Admin_dashboard/todays_sales_report');
    }

	// $sales_amount = 0;
	// if (!empty($all_data)) {
	// 	$i = 0;
	// 	foreach ($all_data as $k => $v) {
	// 		$i++;
	// 		$all_data[$k]['sl'] = $i;
	// 		$time_invoice_created = substr($all_data[$k]['invoice_datetime_created'], strpos($all_data[$k]['invoice_datetime_created'], " ") + 1);
	// 		$all_data[$k]['sales_date'] = $CI->occational->dateConvert($all_data[$k]['date']) . ' ' . $time_invoice_created;
	// 		$all_data[$k]['store_code'] = strtok($all_data[$k]['store_name'], '-');
	// 		$sales_amount = $sales_amount + $all_data[$k]['total_amount'];
	// 	}
	// }
	// var_dump($all_data[0]); die();

    // load excel library
    $this->load->library('excel');
    $objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objPHPExcel->load('my-assets/excel/template/Export_Sales_Detail.xlsx');
    $objPHPExcel->setActiveSheetIndex(0);

    // set Row
    $rowCount = 2;
    $number = 1;
    foreach ($all_data as $data) {
    //   $status = '';
    //   if ($data['invoice_status'] == 1) {
    //     $status = 'Shipped';
    //   } else if ($data['invoice_status'] == 2) {
    //     $status = 'Cancel';
    //   } else if ($data['invoice_status'] == 3) {
    //     $status = 'Pending';
    //   } else if ($data['invoice_status'] == 4) {
    //     $status = 'Complete';
    //   } else if ($data['invoice_status'] == 5) {
    //     $status = 'Processing';
    //   } else if ($data['invoice_status'] == 6) {
    //     $status = 'Return';
    //   }

      $store_code = strtok($data['store_name'], '-');

      $time_invoice_created = substr($data['invoice_datetime_created'], strpos($data['invoice_datetime_created'], " ") + 1);
      $date = $CI->occational->dateConvert($data['date']) . ' ' . $time_invoice_created;

      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $number);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $store_code . '-' . $data['invoice']);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $date);
      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['product_barcode']);
      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['product_model']);
      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $data['product_name']);
      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $data['supplier_rate']);
      $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $data['quantity']);
      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $data['rate']);
	    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $data['total_price']);
      $rowCount++;
      $number++;
    }

    $filename = "Export_Transaksi_Detail_" . date("Y-m-d H-i-s") . ".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
  }


  public function export_invoice_summary()
  {
    // $this->load->library('Excel');
    $CI = &get_instance();
    $CI->load->library('occational');
    $trx_status = $this->input->post('trx_status');
    $from_date = $this->input->post('export_summary_from_date');
    $to_date = $this->input->post('export_summary_to_date');

	if ($from_date > $to_date) {
		return redirect('/Admin_dashboard/todays_sales_report');
	}
  
	if ($from_date != '' && $to_date != '') {
		if ($from_date > $to_date) {
			return redirect('/Admin_dashboard/todays_sales_report');
		}
	}

	$all_data = null;
    $today = date('m-d-Y');
    $this->db->select("a.*,d.*,e.*");
    $this->db->from('invoice a');
	$this->db->join('invoice_details d', 'd.invoice_id = a.invoice_id');
	$this->db->join('product_information e', 'e.product_id = d.product_id');
	if ($from_date != '' && $to_date != '') {
		$this->db->where('a.date >=', $from_date);
		$this->db->where('a.date <=', $to_date);
	}
  
	if ($from_date != '' && $to_date == '') {
		$this->db->where('a.date', $from_date);
	}

	if ($from_date == '' && $to_date != '') {
		$this->db->where('a.date', $to_date);
	}

    $this->db->order_by('e.product_barcode', 'asc');
	$this->db->group_by('e.product_barcode');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      $all_data = $query->result_array();
    } else {
      return redirect('/Admin_dashboard/todays_sales_report');
    }
	//var_dump($all_data); die();

    // load excel library
    $this->load->library('excel');
    $objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objPHPExcel->load('my-assets/excel/template/Export_Sales_Detail_Summary.xlsx');
    $objPHPExcel->setActiveSheetIndex(0);

    // set Row
    $rowCount = 2;
    $number = 1;
    foreach ($all_data as $data) {
    //   $status = '';
    //   if ($data['invoice_status'] == 1) {
    //     $status = 'Shipped';
    //   } else if ($data['invoice_status'] == 2) {
    //     $status = 'Cancel';
    //   } else if ($data['invoice_status'] == 3) {
    //     $status = 'Pending';
    //   } else if ($data['invoice_status'] == 4) {
    //     $status = 'Complete';
    //   } else if ($data['invoice_status'] == 5) {
    //     $status = 'Processing';
    //   } else if ($data['invoice_status'] == 6) {
    //     $status = 'Return';
    //   }

		$this->db->select("a.*,d.*,e.*,SUM(d.quantity) AS total_qty, SUM(d.total_price) AS grand_total_price, SUM(d.discount) AS sum_total_disc");
		$this->db->from('invoice a');
		$this->db->join('invoice_details d', 'd.invoice_id = a.invoice_id');
		$this->db->join('product_information e', 'e.product_id = d.product_id');
		$this->db->where('e.product_barcode', $data['product_barcode']);
		if ($from_date != '' && $to_date != '') {
			$this->db->where('a.date >=', $from_date);
			$this->db->where('a.date <=', $to_date);
		}
	
		if ($from_date != '' && $to_date == '') {
			$this->db->where('a.date', $from_date);
		}

		if ($from_date == '' && $to_date != '') {
			$this->db->where('a.date', $to_date);
		}

		$this->db->order_by('e.product_barcode', 'asc');
		//$this->db->group_by('e.product_barcode');
		$query = $this->db->get();
		$product_data = $query->result_array();
		// var_dump($product_data[0]); die();



      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $number);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['product_barcode']);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $data['product_model']);
      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['product_name']);
      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['supplier_rate']);
      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $product_data[0]['total_qty']);
      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $data['rate']);
	    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $product_data[0]['grand_total_price']);
		// $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $product_data[0]['sum_total_disc']);
      $rowCount++;
      $number++;
    }

    $filename = "Export_Transaksi_Detail_" . date("Y-m-d H-i-s") . ".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
  }


	public function sales_report_store_wise(){

		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();

		#
        #pagination starts
        #
		// $config["base_url"] = base_url('Admin_dashboard/sales_report_store_wise/');
		// $config["total_rows"] = $this->Reports->sales_report_store_wise();
		// $config["per_page"] = 10;
		// $config["uri_segment"] = 3;
		// $config["num_links"] = 5;
		// /* This Application Must Be Used With BootStrap 3 * */
		// $config['full_tag_open'] = "<ul class='pagination'>";
		// $config['full_tag_close'] = "</ul>";
		// $config['num_tag_open'] = '<li>';
		// $config['num_tag_close'] = '</li>';
		// $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		// $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		// $config['next_tag_open'] = "<li>";
		// $config['next_tag_close'] = "</li>";
		// $config['prev_tag_open'] = "<li>";
		// $config['prev_tagl_close'] = "</li>";
		// $config['first_tag_open'] = "<li>";
		// $config['first_tagl_close'] = "</li>";
		// $config['last_tag_open'] = "<li>";
		// $config['last_tagl_close'] = "</li>";
		// /* ends of bootstrap */
		// $this->pagination->initialize($config);
		// $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		// $links = $this->pagination->create_links();
        #
        #pagination ends
        #
		$content = $CI->lreport->sales_report_store_wise(@$links,@$config["per_page"],@$page);
		$this->template->full_admin_html_view($content);
	}

		#============Date wise sales report==============#
	public function retrieve_sales_report_store_wise()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$store_id = $this->input->post('store_id');		
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');	
		$content = $CI->lreport->retrieve_sales_report_store_wise($store_id, $start_date, $end_date);
		$this->template->full_admin_html_view($content);
	}


	#==============Transfer Report============#
	public function transfer_report()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		$this->auth->check_admin_auth();

		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$content = $this->lreport->transfer_report($from_date,$to_date);
		$this->template->full_admin_html_view($content);
	}		

	#==============Store To Store Transfer============#
	public function store_to_store_transfer()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		
		$this->auth->check_admin_auth();
		$from_date = $this->input->post('from_date');
		$to_date 	= $this->input->post('to_date');
		$from_store = $this->input->post('from_store');
		$to_store 	= $this->input->post('to_store');

		$content = $this->lreport->store_to_store_transfer($from_date,$to_date,$from_store,$to_store);
		$this->template->full_admin_html_view($content);
	}		
	#==============Store To Wearhouse============#
	public function store_to_warehouse_transfer()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}

		$this->auth->check_admin_auth();
		$from_date = $this->input->post('from_date');
		$to_date   = $this->input->post('to_date');
		$from_store = $this->input->post('from_store');
		$t_wearhouse 	= $this->input->post('t_wearhouse');

		$content = $this->lreport->store_to_warehouse_transfer($from_date,$to_date,$from_store,$t_wearhouse);
		$this->template->full_admin_html_view($content);
	}		

	#==============Wearhouse To Store============#
	public function warehouse_to_store_transfer()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		
		$this->auth->check_admin_auth();
		$from_date = $this->input->post('from_date');
		$to_date   = $this->input->post('to_date');
		$wearhouse = $this->input->post('wearhouse');
		$t_store   = $this->input->post('t_store');

		$content = $this->lreport->warehouse_to_store_transfer($from_date,$to_date,$wearhouse,$t_store);
		$this->template->full_admin_html_view($content);
	}		
	#==============Wearhouse To Wearhouse============#
	public function warehouse_to_warehouse_transfer()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		
		$this->auth->check_admin_auth();
		$from_date = $this->input->post('from_date');
		$to_date   = $this->input->post('to_date');
		$wearhouse = $this->input->post('wearhouse');
		$t_wearhouse   = $this->input->post('t_wearhouse');

		$content = $this->lreport->warehouse_to_warehouse_transfer($from_date,$to_date,$wearhouse,$t_wearhouse);
		$this->template->full_admin_html_view($content);
	}	
	#==============Tax Report============#
	public function tax_report_product_wise()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}

		$this->auth->check_admin_auth();
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$content = $this->lreport->tax_report($from_date,$to_date);
		$this->template->full_admin_html_view($content);
	}	

	#==============Tax Report Invoice Wise============#
	public function tax_report_invoice_wise()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		
		$this->auth->check_admin_auth();
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$content = $this->lreport->tax_report_invoice_wise($from_date,$to_date);
		$this->template->full_admin_html_view($content);
	}	
	#=============Total profit report===================#
	public function total_profit_report(){
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		
		$this->auth->check_admin_auth();
		#
        #pagination starts
        #
		$config["base_url"] = base_url('Admin_dashboard/total_profit_report/');
		$config["total_rows"] = $this->Reports->total_profit_report_count();
		$config["per_page"] = 10;
		$config["uri_segment"] = 3;
		$config["num_links"] = 5; 
		/* This Application Must Be Used With BootStrap 3 * */
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$links = $this->pagination->create_links();
        #
        #pagination ends
        #  
		$content =$this->lreport->total_profit_report($links,$config["per_page"],$page);

		$this->template->full_admin_html_view($content);
	}
	#==============Date wise profit report=============#
	public function retrieve_dateWise_profit_report()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		
		$this->auth->check_admin_auth();
		$start_date = $this->input->post('from_date');		
		$end_date = $this->input->post('to_date');	
		$content = $this->lreport->retrieve_dateWise_profit_report($start_date,$end_date);
		$this->template->full_admin_html_view($content);
	}
	#============Date wise sales report==============#
	public function retrieve_dateWise_SalesReports()
	{
		// if ($this->session->userdata('user_type') == '2') {
		// 	$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
		// 	redirect('Admin_dashboard');
		// }
		// $CI =& get_instance();
		// $this->auth->check_admin_auth();
		// $CI->load->library('lreport');
		// $from_date = $this->input->post('from_date');		
		// $to_date = $this->input->post('to_date');	
		// $content = $CI->lreport->retrieve_dateWise_SalesReports($from_date,$to_date);
		// $this->template->full_admin_html_view($content);

		

    	if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();

		#
        #pagination starts
        #
    	$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$payment_method = $this->input->post('payment_method');
		
		if ($from_date != null) {
			$this->session->set_userdata('report_sales_from_date', $from_date);
		} else {
			$from_date = $this->session->userdata('report_sales_from_date');
		}

		if ($to_date != null) {
			$this->session->set_userdata('report_sales_to_date', $to_date);
		} else {
			$to_date = $this->session->userdata('report_sales_to_date');
		}

		if ($payment_method != null) {
			$this->session->set_userdata('filter_paymment', $payment_method);
		} else {
			if ($this->session->userdata('filter_paymment')) {
				$payment_method = $this->session->userdata('filter_paymment');
			} else {
				$payment_method = "All";
			}
		}
		
		$get_session_report_sales_from_date = $this->session->userdata('report_sales_from_date');
		$get_session_report_sales_to_date = $this->session->userdata('report_sales_to_date');

		if ($get_session_report_sales_from_date == null && $get_session_report_sales_to_date != null) {
			$from_date = $this->session->userdata('report_sales_to_date');
			$this->session->set_userdata('report_sales_from_date', $from_date);
		}

		if ($get_session_report_sales_from_date != null && $get_session_report_sales_to_date == null) {
			$to_date = $this->session->userdata('report_sales_from_date');
			$this->session->set_userdata('report_sales_to_date', $to_date);
		}

		if ($get_session_report_sales_from_date == null && $get_session_report_sales_to_date == null) {
			redirect('Admin_dashboard/todays_sales_report');
			return false;
		}
    
		$config["base_url"] = base_url('Admin_dashboard/retrieve_dateWise_SalesReports/');
		$config["total_rows"] = $this->Reports->todays_sales_report_count_by_date($from_date, $to_date, $payment_method);
		$config["per_page"] = 10;
		$config["uri_segment"] = 3;
		$config["num_links"] = 5; 
		/* This Application Must Be Used With BootStrap 3 * */
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$links = $this->pagination->create_links();
        #
        #pagination ends
        # 
    
		$content = $CI->lreport->todays_sales_report_by_date($links,$config["per_page"],$page, $from_date, $to_date, $payment_method);
		$this->template->full_admin_html_view($content);
	}
	#================todays_purchase_report========#
	public function todays_purchase_report()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();

		#
        #pagination starts
        #
		$config["base_url"] = base_url('Admin_dashboard/todays_purchase_report/');
		$config["total_rows"] = $this->Reports->todays_sales_report_count();
		$config["per_page"] = 10;
		$config["uri_segment"] = 3;
		$config["num_links"] = 5; 
		/* This Application Must Be Used With BootStrap 3 * */
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$links = $this->pagination->create_links();
        #
        #pagination ends
        # 

		$content = $CI->lreport->todays_purchase_report($links,$config["per_page"],$page);
		$this->template->full_admin_html_view($content);
	}	
	#==============Date wise purchase report=============#
	public function retrieve_dateWise_PurchaseReports()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		
		$this->auth->check_admin_auth();
		$start_date = $this->input->post('from_date');		
		$end_date = $this->input->post('to_date');	
		$content = $this->lreport->retrieve_dateWise_PurchaseReports($start_date,$end_date);
		$this->template->full_admin_html_view($content);
	}
	#==============Product sales search reports============#
	public function product_sales_search_reports()
	{
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
		
		$this->auth->check_admin_auth();
		$from_date = $this->input->post('from_date');		
		$to_date = $this->input->post('to_date');	
		$content = $this->lreport->get_products_search_report( $from_date,$to_date );
		$this->template->full_admin_html_view($content);
	}
	#============User login=========#
	public function login()
	{
		if ($this->auth->is_logged() )
		{
			$this->output->set_header("Location: ".base_url().'Admin_dashboard', TRUE, 302);
		}

		// $data['title'] = "Admin Login Area";
		// $content = $this->parser->parse('user/admin_login_form',$data,true);
		// $this->template->full_admin_html_view($content);

    $this->load->view('auth/login');
	}
	#==============Valid user check=======#
	public function do_login()
	{
		$error = '';
		$setting_detail = $this->Soft_settings->retrieve_setting_editdata();
    // var_dump('b');
		if (($setting_detail[0]['captcha'] == 0) && (!empty($setting_detail[0]['secret_key'])) && (!empty($setting_detail[0]['site_key']))) {
			$this->form_validation->set_rules('g-recaptcha-response', 'recaptcha validation', 'required|callback_validate_captcha');
			$this->form_validation->set_message('validate_captcha', 'Please check the captcha form');
			if ($this->form_validation->run() == FALSE)
			{
        // var_dump('a');
				$this->session->set_userdata(array('error_message'=>display('please_enter_valid_captcha')));
				$this->output->set_header("Location: ".base_url().'admin', TRUE, 302);
			}else{
				$username = $this->input->post('username');
				$password = $this->input->post('password');
        // var_dump('b');
				if ( $username == '' ||  $password == '' || $this->auth->login($username, $password) === FALSE ){
					$error = display('wrong_username_or_password');
				}
				if ( $error != '' ){
					$this->session->set_userdata(array('error_message'=>$error));
					$this->output->set_header("Location: ".base_url().'admin', TRUE, 302);
				}else{
					$this->output->set_header("Location: ".base_url('admin'), TRUE, 302);
				}
			}
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if ( $username == '' || $password == '' || $this->auth->login($username, $password) === FALSE ){

			    $error = display('wrong_username_or_password');
			}
			if ( $error != '' ){
				$this->session->set_userdata(array('error_message'=>$error));
				$this->output->set_header("Location: ".base_url().'admin', TRUE, 302);
			}else{

				$this->output->set_header("Location: ".base_url('admin'), TRUE, 302);
			}
		}
	}

	//Valid captcha check
	function validate_captcha() { 
		$setting_detail = $this->Soft_settings->retrieve_setting_editdata(); 
		$captcha = $this->input->post('g-recaptcha-response'); 
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$setting_detail[0]['secret_key'].".&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']); 
		if ($response . 'success' == false) { return FALSE; } else { return TRUE; } 
	}

	#===============Logout=======#
	public function logout()
	{	
		if ($this->auth->logout())
			$this->output->set_header("Location: ".base_url().'admin', TRUE, 302);
	}

    public function forget_admin_password()
    {

        $this->form_validation->set_rules('admin_email', display('email'), 'required|valid_email|max_length[100]|trim');

        $email = $this->input->post('admin_email');
      if ($this->form_validation->run())
        {
             $admin = $this->get_admin_info($email);

            $ptoken = date('ymdhis');
            if($admin) {
                 $email =$admin[0]['username'];

                $precdat = array(
                    'email'     => $email,
                    'token' => $ptoken,

                );
                $send_email = '';
                if (!empty($email)) {

                    $send_email = $this->send_mail($email,$ptoken);
                    $this->add_token($precdat);
                }
                if($send_email){
                    echo 1;
                }else{
                    echo 2;
                }

            }else{
                echo 3;
            }
        }else{
            echo 4;
        }

    }



//check user exists on the database or not
    public function get_admin_info($email){
        $result= $this->db->select('*')->from('user_login')->where('username =',$email)->get()->result_array();
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function add_token($precdat)
    {
        $this->db->set('token',$precdat['token']);
        $this->db->where('username',$precdat['email']);
        $result = $this->db->update('user_login');

        if($result)
        {
            return true;
        }else{
            return false;
        }
    }
    public function admin_password_update(){
        $data=[
            'token'=>$this->input->post('token'),
            'password'=>	$this->input->post('admin_password')
        ];

        $password = md5("gef".$data['password']);
        $this->db->set('password',$password);
        $this->db->where('token',$data['token']);
        $result = $this->db->update('user_login');

        if($result)
        {
            $this->session->set_userdata(array('message'=>display('successfully_updated')));
            redirect('/admin');
        }else{
            return false;
        }
    }


    public function send_mail($email,$ptoken)
    {


        $setting_detail = $this->Soft_settings->retrieve_email_editdata();

        $subject = display("password_recovery");
        $message = base_url('/admin_password_reset/'.$ptoken);
        $message2= "Click here" ;

        $link = "To Reset your Password <a href='".$message."'>".$message2."</a>";
        $config = Array(
            'protocol' 		=> $setting_detail[0]['protocol'],
            'smtp_host' 	=> $setting_detail[0]['smtp_host'],
            'smtp_port' 	=> $setting_detail[0]['smtp_port'],
            'smtp_user' 	=> $setting_detail[0]['sender_email'],
            'smtp_pass' 	=> $setting_detail[0]['password'],
            'mailtype' 		=> $setting_detail[0]['mailtype'],
            'charset' 		=> 'utf-8'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($setting_detail[0]['sender_email']);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($link);
        // $CI->email->attach($file_path);
// jahangir.bdtask@gmail.com
        //pw: jahangir23255
        if($this->email->send())
        {
            $this->session->set_userdata(array('message'=>display('email_send_to_customer')));
            return true;
        }else{
            return false;
        }
    }


    public function admin_password_reset_form($token)
    {
            $data['token'] = $token;

            $this->load->view('website/admin_password_reset',$data);
    }

    #=============Edit Profile======#
	public function edit_profile()
	{
		$this->auth->check_admin_store_auth();
		$content = $this->luser->edit_profile_form();
		$this->template->full_admin_html_view($content);
	}
	#=============Update Profile========#
	public function update_profile()
	{
		$this->auth->check_admin_store_auth();
		$this->Users->profile_update();
		$this->session->set_userdata(array('message'=> display('successfully_updated')));
		redirect(base_url('Admin_dashboard/edit_profile'));
	}
	#=============Change Password=========# 
	public function change_password_form()
	{
		$this->auth->check_admin_store_auth();
		$content = $this->parser->parse('user/change_password',array('title'=>"Change Password"),true);
		$this->template->full_admin_html_view($content);
	}
	#============Change Password===========#
	public function change_password()
	{
		$this->auth->check_admin_store_auth();
		$error 			= '';
		$email 			= $this->input->post('email');
		$old_password 	= $this->input->post('old_password');
		$new_password 	= $this->input->post('password');
		$repassword 	= $this->input->post('repassword');

		if ( $email == '' || $old_password == '' || $new_password == '')
		{
			$error = display('blank_field_does_not_accept');
		}else if($email != $this->session->userdata('user_email')){
			$error = display('you_put_wrong_email_address');
		}else if(strlen($new_password)<6 ){
			$error = display('new_password_at_least_six_character');
		}else if($new_password != $repassword ){
			$error = display('password_and_repassword_does_not_match');
		}else if($this->Users->change_password($email,$old_password,$new_password) === FALSE ){
			$error = display('you_are_not_authorised_person');
		}

		if ( $error != '' )
		{
			$this->session->set_userdata(array('error_message'=>$error));
			$this->output->set_header("Location: ".base_url().'Admin_dashboard/change_password_form', TRUE, 302);
		}else{
			$this->session->set_userdata(array('message'=>display('successfully_changed_password')));
			$this->output->set_header("Location: ".base_url().'Admin_dashboard/change_password_form', TRUE, 302);
		}
	}
}
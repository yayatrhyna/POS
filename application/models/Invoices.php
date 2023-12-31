<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Invoices extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Customers');
	}
	//Count invoice
	public function count_invoice()
	{
		return $this->db->count_all("invoice");
	}

	//Count store invoice
	public function total_store_invoice()
	{
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->where('store_id', $this->session->userdata('store_id'));
		$query = $this->db->get();
		return $query->num_rows();
	}
	//Invoice List
	public function invoice_list()
	{
		$this->db->select('a.*,b.*,c.order,d.*');
		$this->db->from('invoice a');
		$this->db->join('customer_information b', 'b.customer_id = a.customer_id');
		$this->db->join('order c', 'c.order_id = a.order_id', 'left');
		$this->db->join('store_set d', 'd.store_id = a.store_id');
		$this->db->order_by('a.invoice', 'desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	//POS customer setup
	public function pos_customer_setup()
	{
		$query = $this->db->select('*')
			->from('customer_information')
			->where('customer_name', 'Walking Customer')
			->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	//Customer list
	public function customer_list()
	{
		$query = $this->db->select('*')
			->from('customer_information')
			->where('customer_name !=', 'Walking Customer')
			->order_by('customer_name', 'asc')
			->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	//Stock Report by date
	public function stock_report_bydate($product_id)
	{
		$this->db->select("
				SUM(d.quantity) as 'totalSalesQnty',
				SUM(b.quantity) as 'totalPurchaseQnty',
				(sum(b.quantity) - sum(d.quantity)) as stock
			");

		$this->db->from('product_information a');
		$this->db->join('product_purchase_details b', 'b.product_id = a.product_id', 'left');
		$this->db->join('invoice_details d', 'd.product_id = a.product_id', 'left');
		$this->db->join('product_purchase e', 'e.purchase_id = b.purchase_id', 'left');
		$this->db->group_by('a.product_id');
		$this->db->order_by('a.product_name', 'asc');

		if (empty($product_id)) {
			$this->db->where(array('a.status' => 1));
		} else {
			//Single product information 
			$this->db->where('a.product_id', $product_id);
		}
		$query = $this->db->get();

		return $query->row();
	}

	//Stock Report by date
	public function stock_report_bydate_pos($product_id)
	{
		$purchase = $this->db->select("SUM(quantity) as totalPurchaseQnty")
			->from('product_purchase_details')
			->where('product_id', $product_id)
			->get()
			->row();

		$sales = $this->db->select("SUM(quantity) as totalSalesQnty")
			->from('invoice_details')
			->where('product_id', $product_id)
			->get()
			->row();

		return $stock = $purchase->totalPurchaseQnty - $sales->totalSalesQnty;
	}

	//Invoice entry
	public function invoice_entry()
	{
		//Invoice entry info
		$invoice_id 		= $this->auth->generator(15);
		$quantity 			= $this->input->post('product_quantity');
		$available_quantity = $this->input->post('available_quantity');
		$product_id 		= $this->input->post('product_id');
		
		//Stock availability check
		$result = array();
		foreach ($available_quantity as $k => $v) {
			if ($v < $quantity[$k]) {
				$this->session->set_userdata(array('error_message' => display('you_can_not_buy_greater_than_available_cartoon')));
				redirect('Cinvoice');
			}
		}

		//Product existing check
		if ($product_id == null) {
			$this->session->set_userdata(array('error_message' => display('please_select_product')));
			redirect('Cinvoice');
		}

		//Customer existing check
		if (($this->input->post('customer_name_others') == null) && ($this->input->post('customer_id') == null)) {
			$this->session->set_userdata(array('error_message' => display('please_select_customer')));
			redirect(base_url() . 'Cinvoice');
		}

		//Customer data Existence Check.
		if ($this->input->post('customer_id') == "") {
			$customer_id = $this->auth->generator(15);
			//Customer  basic information adding.
			$data = array(
				'customer_id' 	=> $customer_id,
				'customer_name' => $this->input->post('customer_name_others'),
				'customer_address_1' 	=> $this->input->post('customer_name_others_address'),
				'customer_mobile' 	=> "NONE",
				'customer_email' 	=> "NONE",
				'status' 			=> 1
			);

			$this->Customers->customer_entry($data);
			//Previous balance adding -> Sending to customer model to adjust the data.
			$this->Customers->previous_balance_add(0, $customer_id);
		} else {
			$customer_id = $this->input->post('customer_id');
		}

		//Full or partial Payment record.
		if ($this->input->post('paid_amount') > 0) {
			//Insert to customer_ledger Table 
			$data2 = array(
				'transaction_id'	=>	$this->auth->generator(15),
				'customer_id'		=>	$customer_id,
				'invoice_no'		=>	$invoice_id,
				'receipt_no'		=>	$this->auth->generator(15),
				'date'				=>	$this->input->post('invoice_date'),
				'amount'			=>	$this->input->post('paid_amount'),
				'payment_type'		=>	1,
				'description'		=>	'ITP',
				'status'			=>	1
			);
			$this->db->insert('customer_ledger', $data2);
		}

		//Insert to customer ledger Table 
		$data2 = array(
			'transaction_id'	=>	$this->auth->generator(15),
			'customer_id'		=>	$customer_id,
			'invoice_no'		=>	$invoice_id,
			'date'				=>	$this->input->post('invoice_date'),
			'amount'			=>	$this->input->post('grand_total_price'),
			'status'			=>	1
		);
		$this->db->insert('customer_ledger', $data2);

		date_default_timezone_set('Asia/Jakarta');
		//date('m-d-Y')
		$today_date = date('m-d-Y H:i:s');
		//Data inserting into invoice table
		$data = array(
			'invoice_id'		=>	$invoice_id,
			'customer_id'		=>	$customer_id,
			'date'				=>	$this->input->post('invoice_date'),
			'invoice_datetime_created' => $today_date,
			'total_amount'		=>	$this->input->post('grand_total_price'),
			'invoice'			=>	$this->number_generator(),
			'total_discount' 	=> 	$this->input->post('total_discount'),
			'invoice_discount' 	=> 	$this->input->post('invoice_discount'),
			'user_id'			=>	$this->session->userdata('user_id'),
			'store_id'			=>	$this->input->post('store_id'),
			'paid_amount'		=>	$this->input->post('paid_amount'),
			'due_amount'		=>	$this->input->post('due_amount'),
			'service_charge'	=>	$this->input->post('service_charge'),
			'shipping_charge'	=>	$this->input->post('shipping_charge') ? $this->input->post('shipping_charge') : 0,
			'shipping_method'	=>	$this->input->post('shipping_method'),
			'invoice_details'	=>	$this->input->post('invoice_details'),
			'status'			=>	1,
			'invoice_status'	=>	4
		);
		$this->db->insert('invoice', $data);

		//Insert payment method
		$terminal 	= $this->input->post('terminal');
		$card_type 	= $this->input->post('card_type');
		$card_no 	= $this->input->post('card_no');
		//if ($card_no != null) {
			$data3 = array(
				'cardpayment_id' =>	$this->auth->generator(15),
				'terminal_id'	=> ($terminal ? $terminal : ''),
				'card_type'		=>	$card_type,
				'card_no'		=>	$card_no,
				'amount'		=>	$this->input->post('grand_total_price'),
				'invoice_id'	=>	$invoice_id,
				'date'			=>	$this->input->post('invoice_date'),
			);
			$this->db->insert('cardpayment', $data3);
		//}

		//Invoice details info
		$rate 		= $this->input->post('product_rate');
		$p_id 		= $this->input->post('product_id');
		$total_amount = $this->input->post('total_price');
		$discount 	= $this->input->post('discount');
		$variants 	= $this->input->post('variant_id');

		// var_dump($p_id);
		// die();
		//Invoice details for invoice
		for ($i = 0, $n = count($quantity); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate 	  = $rate[$i];
			$product_id 	  = $p_id[$i];
			$discount_rate    = $discount[$i];
			$total_price      = $total_amount[$i];
			$variant_id       = '';
			$supplier_rate	  = $this->supplier_rate($product_id);

			$this->db->select('default_variant, variants');
			$this->db->from('product_information');
			$this->db->where('product_id', $product_id);
			$result = $this->db->get()->row();

			if (!empty($result)) {
				$variant_id = $result->default_variant;
			}

			$invoice_details = array(
				'invoice_details_id'	=>	$this->auth->generator(15),
				'invoice_id'			=>	$invoice_id,
				'product_id'			=>	$product_id,
				'variant_id'			=>	$variant_id,
				'store_id'				=>	$this->input->post('store_id'),
				'quantity'				=>	$product_quantity,
				'rate'					=>	$product_rate,
				'supplier_rate'         =>	$supplier_rate[0]['supplier_price'],
				'total_price'           =>	$total_price,
				'discount'           	=>	$discount_rate,
				'status'				=>	1
			);

			if (!empty($quantity)) {
				$result = $this->db->select('*')
					->from('invoice_details')
					->where('invoice_id', $invoice_id)
					->where('product_id', $product_id)
					->where('variant_id', $variant_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('quantity', 'quantity+' . $product_quantity, FALSE);
					$this->db->set('total_price', 'total_price+' . $total_price, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('product_id', $product_id);
					$this->db->where('variant_id', $variant_id);
					$this->db->update('invoice_details');
				} else {
					$this->db->insert('invoice_details', $invoice_details);
				}
			}
		}

		//Tax information
		$cgst 	 = $this->input->post('cgst');
		$sgst 	 = $this->input->post('sgst');
		$igst 	 = $this->input->post('igst');
		$cgst_id = $this->input->post('cgst_id');
		$sgst_id = $this->input->post('sgst_id');
		$igst_id = $this->input->post('igst_id');

		//Tax collection summary for three start

		//CGST tax info
		for ($i = 0, $n = count($cgst); $i < $n; $i++) {
			$cgst_tax = $cgst[$i];
			$cgst_tax_id = $cgst_id[$i];
			$cgst_summary = array(
				'tax_collection_id'	=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'tax_amount' 		=> 	$cgst_tax,
				'tax_id' 			=> 	$cgst_tax_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($cgst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_summary')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $cgst_tax_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('tax_amount', 'tax_amount+' . $cgst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $cgst_tax_id);
					$this->db->update('tax_collection_summary');
				} else {
					$this->db->insert('tax_collection_summary', $cgst_summary);
				}
			}
		}

		//SGST tax info
		for ($i = 0, $n = count($sgst); $i < $n; $i++) {
			$sgst_tax = $sgst[$i];
			$sgst_tax_id = $sgst_id[$i];

			$sgst_summary = array(
				'tax_collection_id'	=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'tax_amount' 		=> 	$sgst_tax,
				'tax_id' 			=> 	$sgst_tax_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($sgst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_summary')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $sgst_tax_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('tax_amount', 'tax_amount+' . $sgst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $sgst_tax_id);
					$this->db->update('tax_collection_summary');
				} else {
					$this->db->insert('tax_collection_summary', $sgst_summary);
				}
			}
		}

		//IGST tax info
		for ($i = 0, $n = count($igst); $i < $n; $i++) {
			$igst_tax = $igst[$i];
			$igst_tax_id = $igst_id[$i];

			$igst_summary = array(
				'tax_collection_id'	=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'tax_amount' 		=> 	$igst_tax,
				'tax_id' 			=> 	$igst_tax_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($igst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_summary')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $igst_tax_id)
					->get()
					->num_rows();

				if ($result > 0) {
					$this->db->set('tax_amount', 'tax_amount+' . $igst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $igst_tax_id);
					$this->db->update('tax_collection_summary');
				} else {
					$this->db->insert('tax_collection_summary', $igst_summary);
				}
			}
		}
		//Tax collection summary for three end

		//Tax collection details for three start
		//CGST tax info
		for ($i = 0, $n = count($cgst); $i < $n; $i++) {
			$cgst_tax 	 = $cgst[$i];
			$cgst_tax_id = $cgst_id[$i];
			$product_id  = $p_id[$i];

      $variant_id = '';
      $this->db->select('default_variant, variants');
			$this->db->from('product_information');
			$this->db->where('product_id', $product_id);
			$result = $this->db->get()->row();

			if (!empty($result)) {
				$variant_id = $result->default_variant;
			}

			$cgst_details = array(
				'tax_col_de_id'		=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'amount' 			=> 	$cgst_tax,
				'product_id' 		=> 	$product_id,
				'tax_id' 			=> 	$cgst_tax_id,
				'variant_id' 		=> 	$variant_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($cgst[$i])) {

				$result = $this->db->select('*')
					->from('tax_collection_details')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $cgst_tax_id)
					->where('product_id', $product_id)
					->where('variant_id', $variant_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('amount', 'amount+' . $cgst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $cgst_tax_id);
					$this->db->where('variant_id', $variant_id);
					$this->db->update('tax_collection_details');
				} else {
					$this->db->insert('tax_collection_details', $cgst_details);
				}
			}
		}

		//SGST tax info
		for ($i = 0, $n = count($sgst); $i < $n; $i++) {
			$sgst_tax 	 = $sgst[$i];
			$sgst_tax_id = $sgst_id[$i];
			$product_id  = $p_id[$i];
      
      $variant_id = '';
      $this->db->select('default_variant, variants');
			$this->db->from('product_information');
			$this->db->where('product_id', $product_id);
			$result = $this->db->get()->row();

			if (!empty($result)) {
				$variant_id = $result->default_variant;
			}

			$sgst_summary = array(
				'tax_col_de_id'	=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'amount' 			=> 	$sgst_tax,
				'product_id' 		=> 	$product_id,
				'tax_id' 			=> 	$sgst_tax_id,
				'variant_id' 		=> 	$variant_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($sgst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_details')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $sgst_tax_id)
					->where('product_id', $product_id)
					->where('variant_id', $variant_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('amount', 'amount+' . $sgst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $sgst_tax_id);
					$this->db->where('variant_id', $variant_id);
					$this->db->update('tax_collection_details');
				} else {
					$this->db->insert('tax_collection_details', $sgst_summary);
				}
			}
		}

		// IGST tax info
		for ($i = 0, $n = count($igst); $i < $n; $i++) {
			$igst_tax 	 = $igst[$i];
			$igst_tax_id = $igst_id[$i];
			$product_id  = $p_id[$i];
      
      $variant_id = '';
      $this->db->select('default_variant, variants');
			$this->db->from('product_information');
			$this->db->where('product_id', $product_id);
			$result = $this->db->get()->row();

			if (!empty($result)) {
				$variant_id = $result->default_variant;
			}

			$igst_summary = array(
				'tax_col_de_id'		=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'amount' 			=> 	$igst_tax,
				'product_id' 		=> 	$product_id,
				'tax_id' 			=> 	$igst_tax_id,
				'variant_id' 		=> 	$variant_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($igst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_details')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $igst_tax_id)
					->where('product_id', $product_id)
					->where('variant_id', $variant_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('amount', 'amount+' . $igst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $igst_tax_id);
					$this->db->where('variant_id', $variant_id);
					$this->db->update('tax_collection_details');
				} else {
					$this->db->insert('tax_collection_details', $igst_summary);
				}
			}
		}
		//Tax collection details for three end
		return $invoice_id;
	}

	//Update Invoice
	public function update_invoice()
	{
		//Invoice and customer info
		$invoice_id  = $this->input->post('invoice_id');
		$customer_id = $this->input->post('customer_id');
		$quantity 	 = $this->input->post('product_quantity');
		$available_quantity = $this->input->post('available_quantity');

		//Stock availability check
		$result = array();
		foreach ($available_quantity as $k => $v) {
			if ($v < $quantity[$k]) {
				$this->session->set_userdata(array('error_message' => display('you_can_not_buy_greater_than_available_cartoon')));
				redirect('Cinvoice/manage_invoice');
			}
		}

		if ($invoice_id != '') {
			//Data update into invoice table
			$data = array(
				'invoice_id'		=>	$invoice_id,
				'customer_id'		=>	$customer_id,
				'date'				=>	$this->input->post('invoice_date'),
				'total_amount'		=>	$this->input->post('grand_total_price'),
				'invoice'			=>	$this->input->post('invoice'),
				'total_discount' 	=> 	$this->input->post('total_discount'),
				'invoice_discount' 	=> 	(int)$this->input->post('invoice_discount') + (int)$this->input->post('total_discount'),
				'user_id'			=>	$this->session->userdata('user_id'),
				'store_id'			=>	$this->input->post('store_id'),
				'paid_amount'		=>	$this->input->post('paid_amount'),
				'due_amount'		=>	$this->input->post('due_amount'),
				'service_charge'	=>	$this->input->post('service_charge'),
				'shipping_charge'	=>	$this->input->post('shipping_charge'),
				'shipping_method'	=>	$this->input->post('shipping_method'),
				'invoice_details'   =>	$this->input->post('invoice_details'),
				'invoice_status'   	=>	$this->input->post('invoice_status'),
				'status'			=>	1
			);
			$this->db->where('invoice_id', $invoice_id);
			$result = $this->db->delete('invoice');

			if ($result) {
				$this->db->insert('invoice', $data);
			}

			//Delete old customer ledger data
			$this->db->where('invoice_no', $invoice_id);
			$result = $this->db->delete('customer_ledger');

			//Insert customer ledger data where amount > 0
			if ($this->input->post('paid_amount') > 0) {
				//Insert to customer_ledger Table 
				$data1 = array(
					'transaction_id'	=>	$this->auth->generator(15),
					'customer_id'		=>	$customer_id,
					'invoice_no'		=>	$invoice_id,
					'receipt_no'		=>	$this->auth->generator(15),
					'date'				=>	$this->input->post('invoice_date'),
					'amount'			=>	$this->input->post('paid_amount'),
					'payment_type'		=>	1,
					'description'		=>	'ITP',
					'status'			=>	1
				);
				$this->db->insert('customer_ledger', $data1);
			}

			//Update to customer ledger Table 
			$data2 = array(
				'transaction_id'	=>	$this->auth->generator(15),
				'customer_id'		=>	$customer_id,
				'invoice_no'		=>	$invoice_id,
				'date'				=>	$this->input->post('invoice_date'),
				'amount'			=>	$this->input->post('grand_total_price'),
				'status'			=>	1
			);
			$this->db->insert('customer_ledger', $data2);
		}

		//Insert payment method
		$terminal 	= $this->input->post('terminal');
		$card_type 	= $this->input->post('card_type');
		$card_no 	= $this->input->post('card_no');
		if ($card_no != null) {
			$data3 = array(
				'terminal_id'	=> ($terminal ? $terminal : ''),
				'card_type'		=>	$card_type,
				'card_no'		=>	$card_no,
				'amount'		=>	$this->input->post('grand_total_price'),
				'invoice_id'	=>	$invoice_id,
				'date'			=>	$this->input->post('invoice_date'),
			);
			$this->db->where('invoice_id', $invoice_id);
			$this->db->update('cardpayment', $data3);
		}

		//Delete old invoice info
		if (!empty($invoice_id)) {
			$this->db->where('invoice_id', $invoice_id);
			$this->db->delete('invoice_details');
		}

		//Invoice details for inovoice
		$invoice_d_id 	= $this->input->post('invoice_details_id');
		$rate 			= $this->input->post('product_rate');
		$p_id 			= $this->input->post('product_id');
		$total_amount 	= $this->input->post('total_price');
		$discount 		= $this->input->post('discount');
		$variants 		= $this->input->post('variant_id');

		//Invoice details for invoice
		for ($i = 0, $n = count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate 	  = $rate[$i];
			$product_id 	  = $p_id[$i];
			$discount_rate 	  = $discount[$i];
			$total_price 	  = $total_amount[$i];
			$variant_id 	  = $variants[$i];
			$invoice_detail_id = (!empty($invoice_d_id[$i]) ? $invoice_d_id[$i] : null);
			$supplier_rate    = $this->supplier_rate($product_id);

			$invoice_details = array(
				'invoice_details_id'	=>	$this->auth->generator(15),
				'invoice_id'			=>	$invoice_id,
				'product_id'			=>	$product_id,
				'variant_id'			=>	$variant_id,
				'store_id'				=>	$this->input->post('store_id'),
				'quantity'				=>	$product_quantity,
				'rate'					=>	$product_rate,
				'supplier_rate'         =>	$supplier_rate[0]['supplier_price'],
				'total_price'           =>	$total_price,
				'discount'           	=>	$discount_rate,
				'status'				=>	1
			);

			if (!empty($p_id)) {
				$result = $this->db->select('*')
					->from('invoice_details')
					->where('invoice_id', $invoice_id)
					->where('product_id', $product_id)
					->where('variant_id', $variant_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('quantity', 'quantity+' . $product_quantity, FALSE);
					$this->db->set('total_price', 'total_price+' . $total_price, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('product_id', $product_id);
					$this->db->where('variant_id', $variant_id);
					$this->db->update('invoice_details');
				} else {
					$this->db->insert('invoice_details', $invoice_details);
				}
			}
		}

		//Tax information
		$cgst 	 = $this->input->post('cgst');
		$sgst 	 = $this->input->post('sgst');
		$igst 	 = $this->input->post('igst');
		$cgst_id = $this->input->post('cgst_id');
		$sgst_id = $this->input->post('sgst_id');
		$igst_id = $this->input->post('igst_id');

		//Tax collection summary for three start


		//Delete all tax  from summary
		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('tax_collection_summary');

		//CGST tax info
		for ($i = 0, $n = count($cgst); $i < $n; $i++) {
			$cgst_tax = $cgst[$i];
			$cgst_tax_id = $cgst_id[$i];
			$cgst_summary = array(
				'tax_collection_id'	=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'tax_amount' 		=> 	$cgst_tax,
				'tax_id' 			=> 	$cgst_tax_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($cgst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_summary')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $cgst_tax_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('tax_amount', 'tax_amount+' . $cgst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $cgst_tax_id);
					$this->db->update('tax_collection_summary');
				} else {
					$this->db->insert('tax_collection_summary', $cgst_summary);
				}
			}
		}

		//SGST tax info
		for ($i = 0, $n = count($sgst); $i < $n; $i++) {
			$sgst_tax = $sgst[$i];
			$sgst_tax_id = $sgst_id[$i];

			$sgst_summary = array(
				'tax_collection_id'	=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'tax_amount' 		=> 	$sgst_tax,
				'tax_id' 			=> 	$sgst_tax_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($sgst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_summary')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $sgst_tax_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('tax_amount', 'tax_amount+' . $sgst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $sgst_tax_id);
					$this->db->update('tax_collection_summary');
				} else {
					$this->db->insert('tax_collection_summary', $sgst_summary);
				}
			}
		}

		//IGST tax info
		for ($i = 0, $n = count($igst); $i < $n; $i++) {
			$igst_tax = $igst[$i];
			$igst_tax_id = $igst_id[$i];

			$igst_summary = array(
				'tax_collection_id'	=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'tax_amount' 		=> 	$igst_tax,
				'tax_id' 			=> 	$igst_tax_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($igst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_summary')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $igst_tax_id)
					->get()
					->num_rows();

				if ($result > 0) {
					$this->db->set('tax_amount', 'tax_amount+' . $igst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $igst_tax_id);
					$this->db->update('tax_collection_summary');
				} else {
					$this->db->insert('tax_collection_summary', $igst_summary);
				}
			}
		}
		//Tax collection summary for three end

		//Delete all tax  from summary
		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('tax_collection_details');

		//Tax collection details for three start
		//CGST tax info
		for ($i = 0, $n = count($cgst); $i < $n; $i++) {
			$cgst_tax 	 = $cgst[$i];
			$cgst_tax_id = $cgst_id[$i];
			$product_id  = $p_id[$i];
			$variant_id  = $variants[$i];
			$cgst_details = array(
				'tax_col_de_id'		=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'amount' 			=> 	$cgst_tax,
				'product_id' 		=> 	$product_id,
				'tax_id' 			=> 	$cgst_tax_id,
				'variant_id' 		=> 	$variant_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($cgst[$i])) {

				$result = $this->db->select('*')
					->from('tax_collection_details')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $cgst_tax_id)
					->where('product_id', $product_id)
					->where('variant_id', $variant_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('amount', 'amount+' . $cgst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $cgst_tax_id);
					$this->db->where('variant_id', $variant_id);
					$this->db->update('tax_collection_details');
				} else {
					$this->db->insert('tax_collection_details', $cgst_details);
				}
			}
		}

		//SGST tax info
		for ($i = 0, $n = count($sgst); $i < $n; $i++) {
			$sgst_tax 	 = $sgst[$i];
			$sgst_tax_id = $sgst_id[$i];
			$product_id  = $p_id[$i];
			$variant_id  = $variants[$i];
			$sgst_summary = array(
				'tax_col_de_id'	=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'amount' 			=> 	$sgst_tax,
				'product_id' 		=> 	$product_id,
				'tax_id' 			=> 	$sgst_tax_id,
				'variant_id' 		=> 	$variant_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($sgst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_details')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $sgst_tax_id)
					->where('product_id', $product_id)
					->where('variant_id', $variant_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('amount', 'amount+' . $sgst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $sgst_tax_id);
					$this->db->where('variant_id', $variant_id);
					$this->db->update('tax_collection_details');
				} else {
					$this->db->insert('tax_collection_details', $sgst_summary);
				}
			}
		}

		// IGST tax info
		for ($i = 0, $n = count($igst); $i < $n; $i++) {
			$igst_tax 	 = $igst[$i];
			$igst_tax_id = $igst_id[$i];
			$product_id  = $p_id[$i];
			$variant_id  = $variants[$i];
			$igst_summary = array(
				'tax_col_de_id'		=>	$this->auth->generator(15),
				'invoice_id'		=>	$invoice_id,
				'amount' 			=> 	$igst_tax,
				'product_id' 		=> 	$product_id,
				'tax_id' 			=> 	$igst_tax_id,
				'variant_id' 		=> 	$variant_id,
				'date'				=>	$this->input->post('invoice_date'),
			);
			if (!empty($igst[$i])) {
				$result = $this->db->select('*')
					->from('tax_collection_details')
					->where('invoice_id', $invoice_id)
					->where('tax_id', $igst_tax_id)
					->where('product_id', $product_id)
					->where('variant_id', $variant_id)
					->get()
					->num_rows();
				if ($result > 0) {
					$this->db->set('amount', 'amount+' . $igst_tax, FALSE);
					$this->db->where('invoice_id', $invoice_id);
					$this->db->where('tax_id', $igst_tax_id);
					$this->db->where('variant_id', $variant_id);
					$this->db->update('tax_collection_details');
				} else {
					$this->db->insert('tax_collection_details', $igst_summary);
				}
			}
		}
		//Tax collection details for three end
		return $invoice_id;
	}

	//invoice Search Item
	public function search_inovoice_item($customer_id)
	{
		$this->db->select('a.*,b.customer_name');
		$this->db->from('invoice a');
		$this->db->join('customer_information b', 'b.customer_id = a.customer_id');
		$this->db->where('b.customer_id', $customer_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	//POS invoice entry
	public function pos_invoice_setup($product_id)
	{
		$product_information = $this->db->select('*')
			->from('product_information')
			->where('product_id', $product_id)
			->get()
			->row();

		if ($product_information != null) {

			$this->db->select('SUM(a.quantity) as total_purchase');
			$this->db->from('product_purchase_details a');
			$this->db->where('a.product_id', $product_id);
			$total_purchase = $this->db->get()->row();

			$this->db->select('SUM(b.quantity) as total_sale');
			$this->db->from('invoice_details b');
			$this->db->where('b.product_id', $product_id);
			$total_sale = $this->db->get()->row();

			$data2 = (object)array(
				'total_product' 	=> ($total_purchase->total_purchase - $total_sale->total_sale),
				'supplier_price' 	=> $product_information->supplier_price,
				'price' 			=> $product_information->price,
				'supplier_id' 		=> $product_information->supplier_id,
				'tax' 				=> $product_information->tax,
				'product_id' 		=> $product_information->product_id,
				'product_name' 		=> $product_information->product_name,
				'product_model' 	=> $product_information->product_model,
				'unit' 				=> $product_information->unit,
			);

			return $data2;
		} else {
			return false;
		}
	}
	//Customer entry
	public function customer_entry($data)
	{

		$this->db->select('*');
		$this->db->from('customer_information');
		$this->db->where('customer_name', $data['customer_name']);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return FALSE;
		} else {
			$this->db->insert('customer_information', $data);

			$this->db->select('*');
			$this->db->from('customer_information');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_customer[] = array('label' => $row->customer_name, 'value' => $row->customer_id);
			}
			$cache_file = './my-assets/js/admin_js/json/customer.json';
			$customerList = json_encode($json_customer);
			file_put_contents($cache_file, $customerList);
			return TRUE;
		}
	}

	//Store List
	public function store_list()
	{
		$this->db->select('*');
		$this->db->from('store_set');
		$this->db->order_by('store_name', 'asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	//Terminal List
	public function terminal_list()
	{
		$this->db->select('*');
		$this->db->from('terminal_payment');
		$this->db->order_by('terminal_name', 'asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	//Get Supplier rate of a product
	public function supplier_rate($product_id)
	{
		$this->db->select('supplier_price');
		$this->db->from('product_information');
		$this->db->where(array('product_id' => $product_id));
		$query = $this->db->get();
		return $query->result_array();
	}
	//Retrieve invoice Edit Data
	public function retrieve_invoice_editdata($invoice_id)
	{
		$this->db->select('
				a.*,
				b.customer_name,
				c.*,
				c.product_id,
				d.product_name,
				d.product_model,
				e.unit_short_name as unit,
			');

		$this->db->from('invoice a');
		$this->db->join('customer_information b', 'b.customer_id = a.customer_id');
		$this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
		$this->db->join('product_information d', 'd.product_id = c.product_id');
		$this->db->join('unit e', 'e.unit_id = d.unit', 'left');
		$this->db->where('a.invoice_id', $invoice_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	//Retrieve invoice_html_data
	public function retrieve_invoice_html_data($invoice_id)
	{
		$direct_invoice = $this->db->select('*')->from('invoice')->where('invoice_id', $invoice_id)->get()->result_array();

		$this->db->select('
			a.*,
			b.*,
			c.*,
			d.product_id,
			d.product_name,
			d.product_details,
			d.product_model,d.unit,
			e.unit_short_name,
			f.variant_name,
			g.customer_name as ship_customer_name,
			g.first_name as ship_first_name, g.last_name as ship_last_name,
			g.customer_short_address as ship_customer_short_address,
			g.customer_address_1 as ship_customer_address_1,
			g.customer_address_2 as ship_customer_address_2,
			g.customer_mobile as ship_customer_mobile,
			g.customer_email as ship_customer_email,
			g.city as ship_city,
			g.state as ship_state,
			g.country as ship_country,
			g.zip as ship_zip,
			g.company as ship_company,
			h.*,
			');
		$this->db->from('invoice a');
		$this->db->join('customer_information b', 'b.customer_id = a.customer_id');
		$this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
		if ($direct_invoice[0]['order_id'] == '') {
			$this->db->join('customer_information g', 'g.customer_id = a.customer_id');
		} else {
			$this->db->join('shipping_info g', 'g.customer_id = a.customer_id');
		}
		$this->db->join('product_information d', 'd.product_id = c.product_id');
		$this->db->join('unit e', 'e.unit_id = d.unit', 'left');
		$this->db->join('variant f', 'f.variant_id = c.variant_id', 'left');
		$this->db->join('store_set h', 'h.store_id  = a.store_id ');
		$this->db->where('a.invoice_id', $invoice_id);

		//		$this->db->group_by('d.product_id');
		$this->db->order_by('d.product_name');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	// Delete invoice Item
	public function retrieve_product_data($product_id)
	{
		$this->db->select('supplier_price,price,supplier_id,tax');
		$this->db->from('product_information');
		$this->db->where(array('product_id' => $product_id, 'status' => 1));
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	//Retrieve company Edit Data
	public function retrieve_company()
	{
		$this->db->select('*');
		$this->db->from('company_information');
		$this->db->limit('1');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	// Delete invoice Item
	public function delete_invoice($invoice_id)
	{
		//Delete Invoice table
		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('invoice');
		//Delete invoice_details table
		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('invoice_details');
		//Delete invoice_tax smmary table
		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('tax_collection_summary');
		//Delete invoice_tax details table
		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('tax_collection_details');

		return true;
	}
	public function invoice_search_list($cat_id, $company_id)
	{
		$this->db->select('a.*,b.sub_category_name,c.category_name');
		$this->db->from('invoices a');
		$this->db->join('invoice_sub_category b', 'b.sub_category_id = a.sub_category_id');
		$this->db->join('invoice_category c', 'c.category_id = b.category_id');
		$this->db->where('a.sister_company_id', $company_id);
		$this->db->where('c.category_id', $cat_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	// GET TOTAL PURCHASE PRODUCT
	public function get_total_purchase_item($product_id)
	{
		$this->db->select('SUM(quantity) as total_purchase');
		$this->db->from('product_purchase_details');
		$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	// GET TOTAL SALES PRODUCT
	public function get_total_sales_item($product_id)
	{
		$this->db->select('SUM(quantity) as total_sale');
		$this->db->from('invoice_details');
		$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	//Get total product
	public function get_total_product($product_id)
	{

		$this->db->select('
			product_name,
			product_id,
			supplier_price,
			price,
			supplier_id,
			unit,
			variants,
			default_variant,
			product_model,
			onsale,
			onsale_price,
			unit.unit_short_name
			');
		$this->db->from('product_information');
		$this->db->join('unit', 'unit.unit_id = product_information.unit', 'left');
		$this->db->where(array('product_id' => $product_id));
		$product_information = $this->db->get()->row();
		if ($product_information == null) {
			$this->db->where(array('product_barcode' => $product_id));
			$product_information = $this->db->get()->row();
		}

		$html = "";
		if (!empty($product_information->variants)) {
			$exploded = explode(',', $product_information->variants);
			$html .= "<option>" . display('select_variant') . "</option>";
			foreach ($exploded as $elem) {
				$this->db->select('*');
				$this->db->from('variant');
				$this->db->where('variant_id', $elem);
				$this->db->order_by('variant_name', 'asc');
				$result = $this->db->get()->row();

				$html .= "<option value=" . $result->variant_id . ">" . $result->variant_name . "</option>";
			}
		}

		$this->db->select('tax.*,tax_product_service.product_id,tax_percentage');
		$this->db->from('tax_product_service');
		$this->db->join('tax', 'tax_product_service.tax_id = tax.tax_id', 'left');
		$this->db->where('tax_product_service.product_id', $product_id);
		$tax_information = $this->db->get()->result();

		//New tax calculation for discount
		if (!empty($tax_information)) {
			foreach ($tax_information as $k => $v) {
				if ($v->tax_id == 'H5MQN4NXJBSDX4L') {
					$tax['cgst_tax'] 	= ($v->tax_percentage) / 100;
					$tax['cgst_name']	= $v->tax_name;
					$tax['cgst_id']	 	= $v->tax_id;
				} elseif ($v->tax_id == '52C2SKCKGQY6Q9J') {
					$tax['sgst_tax'] 	= ($v->tax_percentage) / 100;
					$tax['sgst_name']	= $v->tax_name;
					$tax['sgst_id']	 	= $v->tax_id;
				} elseif ($v->tax_id == '5SN9PRWPN131T4V') {
					$tax['igst_tax'] 	= ($v->tax_percentage) / 100;
					$tax['igst_name']	= $v->tax_name;
					$tax['igst_id']		= $v->tax_id;
				}
			}
		}

		$purchase = $this->db->select("SUM(quantity) as totalPurchaseQnty")
			->from('product_purchase_details')
			->where('product_id', $product_id)
			->get()
			->row();

		$sales = $this->db->select("SUM(quantity) as totalSalesQnty")
			->from('invoice_details')
			->where('product_id', $product_id)
			->get()
			->row();
		$stock = $purchase->totalPurchaseQnty - $sales->totalSalesQnty;

		$discount = "";
		if ($product_information->onsale == 1) {
			$discount = ($product_information->price - $product_information->onsale_price);
		}

		$data2 = array(
			'total_product'	=> $stock,
			'supplier_price' => $product_information->supplier_price,
			'price' 		=> $product_information->price,
			'variant_id' 	=> $product_information->variants,
			'default_variant' 	=> $product_information->default_variant,
			'supplier_id' 	=> $product_information->supplier_id,
			'product_name' 	=> $product_information->product_name,
			'product_model' => $product_information->product_model,
			'product_id' 	=> $product_information->product_id,
			'variant' 		=> $html,
			'discount' 		=> $discount,
			'sgst_tax' 		=> (!empty($tax['sgst_tax']) ? $tax['sgst_tax'] : null),
			'cgst_tax' 		=> (!empty($tax['cgst_tax']) ? $tax['cgst_tax'] : null),
			'igst_tax' 		=> (!empty($tax['igst_tax']) ? $tax['igst_tax'] : null),
			'cgst_id' 		=> (!empty($tax['cgst_id']) ? $tax['cgst_id'] : null),
			'sgst_id' 		=> (!empty($tax['sgst_id']) ? $tax['sgst_id'] : null),
			'igst_id' 		=> (!empty($tax['igst_id']) ? $tax['igst_id'] : null),
			'unit' 			=> $product_information->unit_short_name,
		);

		return $data2;
	}

	public function get_total_product2($product_id, $store_id){

		$this->db->select('
			product_name,
			product_id,
			supplier_price,
			price,
			supplier_id,
			unit,
			variants,
			default_variant,
			product_model,
			onsale,
			onsale_price,
			unit.unit_short_name
			');
		$this->db->from('product_information');
		$this->db->join('unit', 'unit.unit_id = product_information.unit', 'left');
		$this->db->where(array('product_id' => $product_id));
		$product_information = $this->db->get()->row();
		if ($product_information == null) {
			$this->db->where(array('product_barcode' => $product_id));
			$product_information = $this->db->get()->row();
		}

		$html = "";
		if (!empty($product_information->variants)) {
			$exploded = explode(',', $product_information->variants);
			$html .= "<option>" . display('select_variant') . "</option>";
			foreach ($exploded as $elem) {
				$this->db->select('*');
				$this->db->from('variant');
				$this->db->where('variant_id', $elem);
				$this->db->order_by('variant_name', 'asc');
				$result = $this->db->get()->row();

				$html .= "<option value=" . $result->variant_id . ">" . $result->variant_name . "</option>";
			}
		}

		$this->db->select('tax.*,tax_product_service.product_id,tax_percentage');
		$this->db->from('tax_product_service');
		$this->db->join('tax', 'tax_product_service.tax_id = tax.tax_id', 'left');
		$this->db->where('tax_product_service.product_id', $product_id);
		$tax_information = $this->db->get()->result();

		//New tax calculation for discount
		if (!empty($tax_information)) {
			foreach ($tax_information as $k => $v) {
				if ($v->tax_id == 'H5MQN4NXJBSDX4L') {
					$tax['cgst_tax'] 	= ($v->tax_percentage) / 100;
					$tax['cgst_name']	= $v->tax_name;
					$tax['cgst_id']	 	= $v->tax_id;
				} elseif ($v->tax_id == '52C2SKCKGQY6Q9J') {
					$tax['sgst_tax'] 	= ($v->tax_percentage) / 100;
					$tax['sgst_name']	= $v->tax_name;
					$tax['sgst_id']	 	= $v->tax_id;
				} elseif ($v->tax_id == '5SN9PRWPN131T4V') {
					$tax['igst_tax'] 	= ($v->tax_percentage) / 100;
					$tax['igst_name']	= $v->tax_name;
					$tax['igst_id']		= $v->tax_id;
				}
			}
		}

		$purchase = $this->db->select("SUM(quantity) as totalPurchaseQnty")
			->from('product_purchase_details')
			->where('product_id', $product_id)
			->where('store_id',$store_id)
			->get()
			->row();

		$sales = $this->db->select("SUM(quantity) as totalSalesQnty")
			->from('invoice_details')
			->where('product_id', $product_id)
			->where('store_id',$store_id)
			->get()
			->row();
		$stock = $purchase->totalPurchaseQnty - $sales->totalSalesQnty;

		$discount = "";
		if ($product_information->onsale == 1) {
			$discount = ($product_information->price - $product_information->onsale_price);
		}

		$data2 = array(
			'total_product'	=> $stock,
			'supplier_price' => $product_information->supplier_price,
			'price' 		=> $product_information->price,
			'variant_id' 	=> $product_information->variants,
			'default_variant' 	=> $product_information->default_variant,
			'supplier_id' 	=> $product_information->supplier_id,
			'product_name' 	=> $product_information->product_name,
			'product_model' => $product_information->product_model,
			'product_id' 	=> $product_information->product_id,
			'variant' 		=> $html,
			'discount' 		=> $discount,
			'sgst_tax' 		=> (!empty($tax['sgst_tax']) ? $tax['sgst_tax'] : null),
			'cgst_tax' 		=> (!empty($tax['cgst_tax']) ? $tax['cgst_tax'] : null),
			'igst_tax' 		=> (!empty($tax['igst_tax']) ? $tax['igst_tax'] : null),
			'cgst_id' 		=> (!empty($tax['cgst_id']) ? $tax['cgst_id'] : null),
			'sgst_id' 		=> (!empty($tax['sgst_id']) ? $tax['sgst_id'] : null),
			'igst_id' 		=> (!empty($tax['igst_id']) ? $tax['igst_id'] : null),
			'unit' 			=> $product_information->unit_short_name,
		);

		return $data2;
	}

	//This function is used to Generate Key
	public function generator($lenth)
	{
		$number = array("1", "2", "3", "4", "5", "6", "7", "8", "9");

		for ($i = 0; $i < $lenth; $i++) {
			$rand_value = rand(0, 8);
			$rand_number = $number["$rand_value"];

			if (empty($con)) {
				$con = $rand_number;
			} else {
				$con = "$con" . "$rand_number";
			}
		}
		return $con;
	}
	//NUMBER GENERATOR
	public function number_generator()
	{
		$this->db->select_max('invoice', 'invoice_no');
		$query = $this->db->get('invoice');
		$result = $query->result_array();
		$invoice_no = $result[0]['invoice_no'];
		if ($invoice_no != '') {
			$invoice_no = $invoice_no + 1;
		} else {
			$invoice_no = 1000;
		}
		return $invoice_no;
	}

	//Product List
	public function product_list()
	{
		$query = $this->db->select('
					supplier_information.*,
					product_information.*,
					product_category.category_name
				')

			->from('product_information')
			->join('supplier_information', 'product_information.supplier_id = supplier_information.supplier_id', 'left')
			->join('product_category', 'product_category.category_id = product_information.category_id', 'left')
			->group_by('product_information.product_id')
			->order_by('product_information.product_name', 'asc')
			->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	//Category List
	public function category_list()
	{
		$this->db->select('*');
		$this->db->from('product_category');
		$this->db->where('status', 1);
		$this->db->order_by('category_name', 'asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	//Product Search
	public function product_search($product_name, $category_id)
	{

		$this->db->select('*');
		$this->db->from('product_information');
		if (!empty($product_name)) {
			$this->db->like('product_name', $product_name, 'both');
			$this->db->or_like('product_model', $product_name, 'both');
		}

		if (!empty($category_id)) {
			$this->db->where('category_id', $category_id);
		}

		$this->db->where('status', 1);
		$this->db->order_by('product_name', 'asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
}

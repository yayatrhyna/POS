<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cqrcode extends CI_Controller {

	function __construct() 
    {
	    parent::__construct();
	    $this->auth->check_admin_auth();
		$this->load->library('ciqrcode');
		$this->load->model('Products');
	    $this->load->model('Invoices');
	    $this->load->model('Soft_settings');
    }
    //QR-Code Generator
	public function qrgenerator($product_id){

		if (!$product_id) {
			$this->session->set_userdata(array('error_message'=>display('please_select_product')));
			redirect('Cproduct/manage_product');
		}

		$config['cacheable']    = true; //boolean, the default is true
		$config['cachedir']     = ''; //string, the default is application/cache/
		$config['errorlog']     = ''; //string, the default is application/logs/
		$config['quality']      = true; //boolean, the default is true
		$config['size']         = '1024'; //interger, the default is 1024
		$config['black']        = array(224,255,255); // array, default is array(255,255,255)
		$config['white']        = array(70,130,180); // array, default is array(0,0,0)
		$this->ciqrcode->initialize($config);
		//Create QR code image create
		$product_info = $this->Products->retrieve_product_editdata($product_id);

		$params['data']  = $product_info[0]['product_barcode'];
		$params['level'] = 'H';
		$params['size']  = 10;
		$image_name = $product_info[0]['product_barcode'].'.png';
		$params['savename'] = FCPATH.'my-assets/image/qr/'.$image_name;
		$this->ciqrcode->generate($params);

		$company_info = $this->Invoices->retrieve_company();
		$currency_details = $this->Soft_settings->retrieve_currency_info();

		$data = array(
				'title' 	 	=> display('print_qrcode'),
				'product_name' 	=> $product_info[0]['product_name'],
				'product_model' => $product_info[0]['product_model'],
				'price' 		=> $product_info[0]['price'],
				'product_details' => $product_info[0]['product_details'],
				'qr_image' 		=> $image_name,
				'company_name' 	=> $company_info[0]['company_name'],
				'currency' 		=> $currency_details[0]['currency_icon'],
				'position' 		=> $currency_details[0]['currency_position'],
			);

		$content = $this->parser->parse('product/barcode_print_page',$data,true);
		$this->template->full_admin_html_view($content);
	}
}
?>
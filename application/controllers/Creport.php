<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Creport extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $CI = &get_instance();
    $CI->load->model('Soft_settings');
  }
  public function index()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->library('lreport');
    $today = date('m-d-Y');

    $product_id = $this->input->post('product_id') ? $this->input->post('product_id') : "";
    $date = $this->input->post('stock_date') ? $this->input->post('stock_date') : $today;
    $limit = 20;
    $start_record = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
    $date = ($this->uri->segment(3)) ? $this->uri->segment(3) : $date;

    $link = $this->pagination($limit, "Creport/index/$date", $date);
    $content = $CI->lreport->stock_report_single_item($product_id, $date, $limit, $start_record, $link);

    $this->template->full_admin_html_view($content);
  }

  public function export_all()
  {
    $all_data = null;
    $CI =& get_instance();

    $this->db->select("
			a.product_name,
      a.product_barcode,
			a.unit,
			a.product_id,
			a.price,
			a.supplier_price,
			a.product_model,
			c.category_name,
			sum(b.quantity) as totalPurchaseQnty,
			e.purchase_date as purchase_date,
			e.purchase_id,
			f.unit_name
			");

    $this->db->from('product_information a');
    $this->db->join('product_purchase_details b', 'b.product_id = a.product_id', 'left');
    $this->db->join('product_category c', 'c.category_id = a.category_id');
    $this->db->join('product_purchase e', 'e.purchase_id = b.purchase_id');
    $this->db->join('unit f', 'f.unit_id = a.unit', 'left');
    $this->db->join('variant g', 'g.variant_id = b.variant_id', 'left');
    $this->db->join('store_set h', 'h.store_id = b.store_id');
    $this->db->where('b.store_id !=', null);
    $this->db->group_by('a.product_id');
    $this->db->order_by('a.product_name', 'asc');
    $this->db->where(array('a.status' => 1));

    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      $all_data = $query->result_array();
    } else {
      return redirect('/Creport/index');
    }

    // load excel library
    $this->load->library('excel');
    $objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objPHPExcel->load('my-assets/excel/template/Export_Product.xlsx');
    $objPHPExcel->setActiveSheetIndex(0);

    // set Row
    $rowCount = 2;
    $number = 1;
    foreach ($all_data as $data) {

      $sales = $CI->db->select("
                sum(quantity) as totalSalesQnty,
                quantity
                ")
        ->from('invoice_details')
        ->where('product_id', $data['product_id'])
        ->get()
        ->row();

      $stok_quantity_cartoon = ($data['totalPurchaseQnty'] - $sales->totalSalesQnty);
      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $number);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['product_name'] . '-' . $data['product_model']);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $data['product_barcode']);
      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['category_name']);
      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['unit_name']);
      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($data['price']));
      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($data['supplier_price']));
      $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($data['totalPurchaseQnty']));
      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($sales->totalSalesQnty));
      $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($stok_quantity_cartoon));
      $rowCount++;
      $number++;
    }

    $filename = "Export_Product_" . date("Y-m-d H-i-s") . ".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
  }
  
  //=====================stock report store wise ====================================
  public function store_wise_product()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->model('Reports');
    $CI->load->library('lreport');
    #
    #pagination starts
    #
    $config["base_url"] = base_url('Creport/store_wise_product/');
    $config["total_rows"] = @count($this->Reports->store_wise_product());
    $config["per_page"] = 20;
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

    $content = $this->lreport->store_wise_product($links, $config["per_page"], $page);
    $this->template->full_admin_html_view($content);
  }

  //Out of stock product
  public function out_of_stock()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->library('lreport');

    $content = $CI->lreport->out_of_stock();

    $this->template->full_admin_html_view($content);
  }

  //Stock report product wise
  public function stock_report_product_wise()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->library('lreport');
    $CI->load->model('Reports');
    $today = date('m-d-Y');

    $product_id = $this->input->post('product_id') ? $this->input->post('product_id') : "";
    $supplier_id = $this->input->post('supplier_id') ? $this->input->post('supplier_id') : "";
    $from_date = $this->input->post('from_date');
    $to_date = $this->input->post('to_date') ? $this->input->post('to_date') : $today;

    #
    #pagination starts
    #
    $config["base_url"] = base_url('Creport/stock_report_product_wise/');
    $config["total_rows"] = $this->Reports->stock_report_product_bydate_count($supplier_id, $supplier_id, $from_date, $to_date);
    $config["per_page"] = 20;
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
    $content = $this->lreport->stock_report_product_wise($product_id, $supplier_id, $from_date, $to_date, $links, $config["per_page"], $page);

    $this->template->full_admin_html_view($content);
  }

  //Stock report supplier report
  public function stock_report_supplier_wise()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->library('lreport');
    $CI->load->model('Reports');
    $today = date('m-d-Y');

    $product_id = $this->input->post('product_id') ? $this->input->post('product_id') : "";
    $supplier_id = $this->input->post('supplier_id') ? $this->input->post('supplier_id') : "";
    $date = $this->input->post('stock_date') ? $this->input->post('stock_date') : $today;

    #
    #pagination starts
    #
    $config["base_url"] = base_url('Creport/stock_report_supplier_wise/');
    $config["total_rows"] = $this->Reports->product_counter_by_supplier($supplier_id, $date);
    $config["per_page"] = 20;
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
    $content = $this->lreport->stock_report_supplier_wise($product_id, $supplier_id, $date, $links, $config["per_page"], $page);
    $this->template->full_admin_html_view($content);
  }

  public function stock_report_store_wise()
  {

    $this->auth->check_admin_auth();
    $CI   = &get_instance();
    $CI->load->library('lreport');
    $CI->load->model('Reports');
    $today   = date('Y-m-d');
    $from_date   = $this->input->get('from_date');
    $to_date      = $this->input->get('to_date');
    $store_id = $this->input->get('store_id');
    if (empty($store_id)) {
      $from_date = date('Y-m-01');
      $to_date = date('Y-m-d');
      $result =  $CI->db->select('store_id')->from('store_set')->where('default_status=', 1)->get()->row();
      $store_id = $result->store_id;
    }
    #
    #pagination starts
    #
    $config["base_url"]   = base_url('Creport/stock_report_store_wise/');
    $config["reuse_query_string"]   = true;
    $config["total_rows"]   = $this->Reports->stock_report_variant_bydate_count($from_date, $to_date, $store_id);
    $config["per_page"]   = 20;
    $config["uri_segment"]   = 3;
    $config["num_links"]   = 5;
    /* This Application Must Be Used With BootStrap 3 * */
    $config['full_tag_open'] = "<ul class='pagination'>";
    $config['full_tag_close'] = "</ul>";
    $config['num_tag_open']  = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open']  = "<li class='disabled'><li class='active'><a href='#'>";
    $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
    $config['next_tag_open'] = "<li>";
    $config['next_tag_close'] = "</li>";
    $config['prev_tag_open'] = "<li>";
    $config['prev_tagl_close'] = "</li>";
    $config['first_tag_open'] = "<li>";
    $config['first_tagl_close'] = "</li>";
    $config['last_tag_open']   = "<li>";
    $config['last_tagl_close'] = "</li>";
    /* ends of bootstrap */
    $this->pagination->initialize($config);
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    $links = $this->pagination->create_links();
    #
    #pagination ends
    #
    $content = $this->lreport->stock_report_variant_wise(
      $from_date,
      $to_date,
      $store_id,
      $links,
      $config["per_page"],
      $page
    );
    $this->template->full_admin_html_view($content);
  }

  //Get product by supplier
  public function get_product_by_supplier()
  {
    $supplier_id = $this->input->post('supplier_id');

    $product_info_by_supplier = $this->db->select('*')
      ->from('product_information')
      ->where('supplier_id', $supplier_id)
      ->get()
      ->result();

    if ($product_info_by_supplier) {
      echo "<select class=\"form-control\" id=\"supplier_id\" name=\"supplier_id\">
			<option value=\"\">" . display('select_one') . "</option>";
      foreach ($product_info_by_supplier as $product) {
        echo "<option value='" . $product->product_id . "'>" . $product->product_name . '-(' . $product->product_model . ')' . " </option>";
      }
      echo " </select>";
    }
  }

  //Get variant by product
  public function retrive_variant_by_product()
  {
    $product_id = $this->input->post('product_id');
    $product_information = $this->db->select('variants')
      ->from('product_information')
      ->where('product_id', $product_id)
      ->get()
      ->row();

    $html = "";
    if ($product_information->variants) {
      $exploded = explode(',', $product_information->variants);
      $html .= "<select id=\"variant_id\" class=\"form-control variant_id\" required=\"\" style=\"width:200px\">
			<option>Select Variant</option>";
      foreach ($exploded as $elem) {
        $this->db->select('*');
        $this->db->from('variant');
        $this->db->where('variant_id', $elem);
        $this->db->order_by('variant_name', 'asc');
        $result = $this->db->get()->row();

        $html .= "<option value=" . $result->variant_id . ">" . $result->variant_name . "</option>";
      }
      $html .= "</select>";
    }
    echo $html;
  }

  #===============Report paggination=============#
  public function pagination($per_page, $page, $date)
  {
    $CI = &get_instance();
    $CI->load->model('Reports');
    $product_id = $this->input->post('product_id');

    $config = array();
    $config["base_url"] = base_url() . $page;
    $config["total_rows"] = $this->Reports->product_counter($product_id, $date);
    $config["per_page"] = $per_page;
    $config["uri_segment"] = 4;
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

    $this->pagination->initialize($config);

    $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
    $limit = $config["per_page"];
    return $links = $this->pagination->create_links();
  }


  public function stock_by_variant($id)
  {
    $CI = &get_instance();
    $CI->load->model('Reports');
    $data = $this->Reports->get_stock_items($id);

    $content = $CI->parser->parse('report/stock_report_by_variant', $data, true);
    $this->template->full_admin_html_view($content);
  }
}

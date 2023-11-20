<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cinvoice extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('website/Homes');
  }

  public function signing()
  {
    $KEY = './assets/signing/key.pem';

    $req = $_POST['request'];
    $privateKey = openssl_get_privatekey(file_get_contents($KEY), null);

    $signature = null;
    openssl_sign($req, $signature, $privateKey, "sha1");

    if ($signature) {
      header("Content-type: text/plain");
      echo base64_encode($signature);
      exit(0);
    }

    echo '<h1>Error signing message</h1>';
    exit(1);
  }

  //Default invoice add from loading
  public function index()
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->library('linvoice');
    $content = $CI->linvoice->invoice_add_form();
    $this->template->full_admin_html_view($content);
  }

  //Add new invoice
  public function new_invoice()
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->library('linvoice');
    $content = $CI->linvoice->invoice_add_form();
    $this->template->full_admin_html_view($content);
  }

  //Manage invoice
  public function manage_invoice()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->library('linvoice');
    $CI->load->model('Invoices');
    $content = $CI->linvoice->invoice_list();
    $this->template->full_admin_html_view($content);
  }

  public function export_invoice()
  {
    // $this->load->library('Excel');
    $CI = &get_instance();
    $CI->load->library('occational');
    $trx_status = $this->input->post('trx_status');
    $from_date = $this->input->post('from_date');
    $to_date = $this->input->post('to_date');

    if ($from_date > $to_date) {
      return redirect('/Cinvoice/manage_invoice');
    }

    if ($from_date != '' && $to_date != '') {
      if ($from_date > $to_date) {
        return redirect('/Cinvoice/manage_invoice');
      }
    }

    $all_data = null;
    $this->db->select('a.*,b.*,c.order,d.*,u.first_name,u.last_name');
    $this->db->from('invoice a');
    $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
    $this->db->join('order c', 'c.order_id = a.order_id', 'left');
    $this->db->join('store_set d', 'd.store_id = a.store_id');
    $this->db->join('users u', 'u.user_id = a.user_id');
    if ($trx_status != "all") {
      $this->db->where('a.invoice_status', $trx_status);
    }

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

    $this->db->order_by('a.invoice', 'desc');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      $all_data = $query->result_array();
    } else {
      return redirect('/Cinvoice/manage_invoice');
    }

    // load excel library
    $this->load->library('excel');
    $objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objPHPExcel->load('my-assets/excel/template/Export_Sales.xlsx');
    $objPHPExcel->setActiveSheetIndex(0);

    // set Row
    $rowCount = 2;
    $number = 1;
    foreach ($all_data as $data) {
      $status = '';
      if ($data['invoice_status'] == 1) {
        $status = 'Shipped';
      } else if ($data['invoice_status'] == 2) {
        $status = 'Cancel';
      } else if ($data['invoice_status'] == 3) {
        $status = 'Pending';
      } else if ($data['invoice_status'] == 4) {
        $status = 'Complete';
      } else if ($data['invoice_status'] == 5) {
        $status = 'Processing';
      } else if ($data['invoice_status'] == 6) {
        $status = 'Return';
      }

      $store_code = strtok($data['store_name'], '-');

      $time_invoice_created = substr($data['invoice_datetime_created'], strpos($data['invoice_datetime_created'], " ") + 1);
      $date = $CI->occational->dateConvert($data['date']) . ' ' . $time_invoice_created;

      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $number);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $store_code . '-' . $data['invoice']);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $date);
      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['customer_name']);
      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $status);
      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $data['first_name'] . ' ' . $data['last_name']);
      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $data['total_amount']);

      $sixty = $data['total_amount'] * 0.6;
      $fourty = $data['total_amount'] * 0.4;
      $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $sixty);
      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $fourty);
      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $fourty);

      $pajak = ($data['total_amount']/1.11) * 0.11;
      $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, round($pajak));
      $rowCount++;
      $number++;
    }

    $filename = "Export_Transaksi_" . date("Y-m-d H-i-s") . ".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
  }

  public function get_invoice_list()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI = &get_instance();
    $this->load->helper('url');
    $CI->load->model('Invoices_new');
    $CI->load->model('Soft_settings');
    $CI->load->library('occational');

    $invoices_list = $CI->Invoices_new->get_datatables();
    if (!empty($invoices_list)) {
      $i = 0;
      foreach ($invoices_list as $k => $v) {
        $time_invoice_created = substr($invoices_list[$k]->invoice_datetime_created, strpos($invoices_list[$k]->invoice_datetime_created, " ") + 1);
        $invoices_list[$k]->final_date = $CI->occational->dateConvert($invoices_list[$k]->date) . ' ' . $time_invoice_created;
        $i++;
        $invoices_list[$k]->sl = $i;
      }
    }

    $currency_details = $CI->Soft_settings->retrieve_currency_info();
    $position = $currency_details[0]['currency_position'];
    $currency = $currency_details[0]['currency_icon'];

    $data = array();
    $i = 1;
    foreach ($invoices_list as $invoice) {
      $row = array();
      $store_code = strtok($invoice->store_name, '-');
      $time_invoice_created = substr($invoice->invoice_datetime_created, strpos($invoice->invoice_datetime_created, " ") + 1);

      $row_invoice = "<a href='invoice_inserted_data/$invoice->invoice_id'>$store_code-$invoice->invoice<i class='fa fa-tasks pull-right' aria-hidden='true'></i></a>";
      $row_customer_name = "<a href='../Ccustomer/customerledger/$invoice->customer_id'>$invoice->customer_name<i class='fa fa-user pull-right' aria-hidden='true'></i></a>";
      if ($position == 0) {
        $row_amount = $currency . ' ' . $invoice->total_amount;
      } else {
        $row_amount = $invoice->total_amount . ' ' . $currency;
      }

      $row_status = "<form action='update_status/$invoice->invoice_id' method='post' id='validate'>";
      $row_status .= "<select class='form-control' id='invoice_status' name='invoice_status' required=''>";
      $row_status .= "<option value=''></option>";
      if ($invoice->invoice_status == 1) {
        $row_status .= "<option value='1' selected>Shipped</option>";
      } else {
        $row_status .= "<option value='1'>Shipped</option>";
      }

      if ($invoice->invoice_status == 2) {
        $row_status .= "<option value='2' selected>Cancel</option>";
      } else {
        $row_status .= "<option value='2'>Cancel</option>";
      }

      if ($invoice->invoice_status == 3 || $invoice->invoice_status == 0) {
        $row_status .= "<option value='3' selected>Pending</option>";
      } else {
        $row_status .= "<option value='3'>Pending</option>";
      }

      if ($invoice->invoice_status == 4) {
        $row_status .= "<option value='4' selected>Complete</option>";
      } else {
        $row_status .= "<option value='4'>Complete</option>";
      }

      if ($invoice->invoice_status == 5) {
        $row_status .= "<option value='5' selected>Processing</option>";
      } else {
        $row_status .= "<option value='5'>Processing</option>";
      }

      if ($invoice->invoice_status == 6) {
        $row_status .= "<option value='6' selected>Return</option>";
      } else {
        $row_status .= "<option value='6'>Return</option>";
      }

      $row_status .= "</select>";
      $row_status .= "
            <button type='button' class='btn btn-success' data-toggle='modal' data-target='#myModal_$i' title='Add Note' /><i class='fa fa-plus' aria-hidden='true'></i></button>

                <input type='hidden' value='$invoice->customer_email' name='customer_email' />
                <input type='hidden' value='$invoice->customer_id' name='customer_id' />
                <input type='hidden' value='$invoice->order' name='order_no' />
                <input type='hidden' value='$invoice->order_id' name='order_id' />

                <div class='modal fade' id='myModal_$i' role='dialog'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                <h1 class='modal-title'>Add Note</h1>
                            </div>
                            <div class='modal-body'>
                                <div class='form-group row'>
                                    <label for='' class='col-sm-4 col-form-label'>Add Note </label>
                                    <div class='col-sm-8'>
                                        <input type='text' name='add_note' class='form-control' id='add_note' placeholder='Add Note' required>
                                    </div>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-success' data-dismiss='modal'>Submit</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <input type='submit' class='btn btn-primary' value='Update' style='position: absolute;margin-left: 5px;' onclick='noteCheck();' />

                <script type='text/javascript'>
                    function noteCheck() {

                        var add_note = $('#add_note').val(); //this data send to the customer email

                        if (add_note) {
                            return true;
                        } else {
                            alert('Please Add Note');
                            return false;
                        }
                    }
                </script>
            </form>
            ";

      $row_aksi = "
            <center>
                        <a href='/Cinvoice/invoice_inserted_data/$invoice->invoice_id' class='btn btn-success btn-sm' data-toggle='tooltip' data-placement='left' title='Invoice'><i class='fa fa-window-restore' aria-hidden='true'></i></a>

                        <a href='/Cinvoice/pos_invoice_inserted_data/$invoice->invoice_id' class='btn btn-warning btn-sm' data-toggle='tooltip' data-placement='left' title='POS Invoice'><i class='fa fa-fax' aria-hidden='true'></i></a>

                        <a href='/Cinvoice/invoice_update_form/$invoice->invoice_id' class='btn btn-info btn-sm' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil' aria-hidden='true'></i></a>

                        <a href='/Cinvoice/invoice_delete/$invoice->invoice_id' class='btn btn-danger btn-sm' onclick='return confirm('are_you_sure_want_to_delete')' data-toggle='tooltip' data-placement='right' title='' data-original-title='Delete'><i class='fa fa-trash-o' aria-hidden='true'></i></a>
                    </center>
                    ";

      $row[] = $invoice->sl;
      $row[] = $row_invoice;
      $row[] = $row_customer_name;
      $row[] = $invoice->final_date;
      $row[] = $row_amount;
      $row[] = $row_status;
      $row[] = $row_aksi;

      $data[] = $row;
      $i++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $CI->Invoices_new->count_all(),
      "recordsFiltered" => $CI->Invoices_new->count_filtered(),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  //Insert new invoice
  public function insert_invoice()
  {

    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->model('Invoices');
    $invoice_id = $CI->Invoices->invoice_entry();
    $this->invoice_inserted_data($invoice_id);
    $this->session->set_userdata(array('message' => display('successfully_added')));
    if ($this->input->post('pos') === 'pos') {
      redirect('Cinvoice/pos_invoice_inserted_data_redirect/' . $invoice_id . '?place=pos');
    }
  }

  // insert new invoice
  public function insert_invoice2()
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->model('Invoices');
    $invoice_id = $CI->Invoices->invoice_entry();
    echo json_encode(["invoice_id" => $invoice_id]);
  }

  //Invoice Update Form
  public function invoice_update_form($invoice_id)
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->library('linvoice');
    $content = $CI->linvoice->invoice_edit_data($invoice_id);
    $this->template->full_admin_html_view($content);
  }

  // Invoice Update
  public function invoice_update()
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->model('Invoices');
    $invoice_id = $CI->Invoices->update_invoice();
    $this->session->set_userdata(array('message' => display('successfully_updated')));
    $this->invoice_inserted_data($invoice_id);
  }

  //Retrive right now inserted data to cretae html
  public function invoice_inserted_data($invoice_id)
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->library('linvoice');
    $content = $CI->linvoice->invoice_html_data($invoice_id);
    $this->template->full_admin_html_view($content);
  }

  //POS invoice page load
  public function pos_invoice()
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->library('linvoice');
    $content = $CI->linvoice->pos_invoice_add_form();
    $this->template->full_admin_html_view($content);
  }

  public function upd_var()
  {
    return 'x';
    // update variants
    $this->db->select('*');
    $this->db->from('product_information');
    $this->db->where('default_variant', null);
    $get_variant = $this->db->get()->result_array();

    foreach ($get_variant as $key) {

      $this->db->select('*');
      $this->db->from('variant');
      $this->db->where('variant_name', $key['variants']);
      $result = $this->db->get()->row();

      if (!empty($result)) {
        $data = array(
          'variants' => $result->variant_id,
          'default_variant' => $result->variant_id
        );

        $this->db->where('product_id', $key['product_id']);
        $this->db->update('product_information', $data);
      }
    }

    return 'xx';
  }

  public function upd_price()
  {
    // temporary update price
    $this->db->select('price, product_id');
    $this->db->from('product_information');
    $get_product = $this->db->get()->result_array();

    $discount = 10 / 100;
    foreach ($get_product as $key) {
      // dd($key);
      $total_discount = ($discount) * $key['price'];
      // dd($total_discount);
      $new_price = $key['price'] - $total_discount;
      $data = array(
        'price' => $new_price,
      );
      $this->db->where('product_id', $key['product_id']);
      $this->db->update('product_information', $data);

      // break;
    }
    return "Success";
  }

  //Insert pos invoice
  public function insert_pos_invoice()
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->model('Invoices');

    $product_id = $this->input->post('product_id');
    $store_id = $this->input->post('store_id');
    if (($this->db->get_where('product_information', ['product_id' => $product_id])->num_rows()) == 0) {
      $product_id = $this->db->get_where('product_information', ['product_barcode' => $product_id])->row_array()['product_id'];
    }

    $stok_report = $CI->Invoices->stock_report_bydate_pos($product_id);
    if ($stok_report > 0) {

      $product_details = $CI->Invoices->get_total_product2($product_id, $store_id);
      $html = "";
      if ($product_details['variant_id']) {
        $exploded = explode(',', $product_details['variant_id']);
        $html .= "<option>Select Variant</option>";
        foreach ($exploded as $elem) {
          $this->db->select('*');
          $this->db->from('variant');
          $this->db->where('variant_id', $elem);
          $this->db->order_by('variant_name', 'asc');
          $result = $this->db->get()->row();

          if ($product_details['default_variant'] == $result->variant_id) {
            $html .= "<option value=" . $result->variant_id . " selected>" . $result->variant_name . "</option>";
          } else {
            $html .= "<option value=" . $result->variant_id . ">" . $result->variant_name . "</option>";
          }
          // $html .= "<option value=" . $result->variant_id . ">" . $result->variant_name . "</option>";
        }
      }

      $tr = " ";
      $order = " ";
      $bill = " ";
      // dd($product_details);
      if ($product_details['total_product'] > 0) {
        //if (!empty($product_details)) {
        $product_id = $this->auth->generator(5);
        $tr .= "<tr id='" . $product_id . "'>
				<th id=\"product_name_" . $product_id . "\">" . $product_details['product_name'] . "</th>

				<td style='display: none !important;'>
				<script>
				$(\"select.form-control:not(.dont-select-me)\").select2({
					placeholder: \"Select option\",
					allowClear: true
					});
					</script>
					<input type=\"hidden\" class=\"sl\" value=" . $product_id . ">
					<input type=\"hidden\" class=\"product_id_" . $product_id . "\" value=" . $product_details['product_id'] . ">
					<select name=\"variant_id[]\" id=\"variant_id_" . $product_id . "\" class=\"form-control variant_id\" style=\"width: 80px\">" . $html . "</select>
					</td>

					<td>
					<input type=\"text\" name=\"available_quantity[]\" id=\"avl_qntt_" . $product_id . "\" 
                class=\"form-control text-right available_quantity_" . $product_id . "\" value=\"0\" readonly=\"readonly\" style=\"width: 100px\"/>
					</td>

					<td width=\"\">
					<input type=\"hidden\" class=\"form-control product_id_" . $product_id . "\" name=\"product_id[]\" value = '" . $product_details['product_id'] . "' id=\"product_id_" . $product_id . "\"/>

					<input type=\"text\" name=\"product_rate[]\" value='" . $product_details['price'] . "' id=\"price_item_" . $product_id . "\" class=\"price_item" . $product_id . " form-control text-right\" min=\"0\" readonly=\"readonly\" style=\"width:100px\"/>

					<input type=\"hidden\" name=\"\" id=\"\" class=\"form-control text-right unit_" . $product_id . "\" value='" . $product_details['unit'] . "' readonly=\"readonly\" />

					<input type=\"hidden\" name=\"discount[]\" id=\"discount_" . $product_id . "\" class=\"form-control text-right\" value ='" . $product_details['discount'] . "' min=\"0\"/>
					</td>

					<td scope=\"row\">
					<input type=\"text\" name=\"product_quantity[]\"   onchange=\"quantity_limit('" . $product_id . "')\" onkeyup=\"quantity_calculate('"
          . $product_id . "');\" onchange=\"quantity_calculate('" . $product_id . "');\" id=\"total_qntt_" . $product_id . "\" class=\"form-control text-right\" value=\"1\" min=\"1\" style=\"width:100px\"/>
					</td>

					<td width=\"\">
					<input class=\"total_price form-control text-right\" type=\"text\" name=\"total_price[]\" id=\"total_price_" . $product_id . "\" value='" . $product_details['price'] . "'  readonly=\"readonly\" style=\"width:100px\"/>
					</td>

					<td width:\"300\">";


        $this->db->select('*');
        $this->db->from('tax');
        $this->db->order_by('tax_name', 'asc');
        $tax_information = $this->db->get()->result();

        if (!empty($tax_information)) {
          foreach ($tax_information as $k => $v) {
            if ($v->tax_id == 'H5MQN4NXJBSDX4L') {
              $tax['cgst_name'] = $v->tax_name;
              $tax['cgst_id'] = $v->tax_id;
              $tax['cgst_status'] = $v->status;
            } elseif ($v->tax_id == '52C2SKCKGQY6Q9J') {
              $tax['sgst_name'] = $v->tax_name;
              $tax['sgst_id'] = $v->tax_id;
              $tax['sgst_status'] = $v->status;
            } elseif ($v->tax_id == '5SN9PRWPN131T4V') {
              $tax['igst_name'] = $v->tax_name;
              $tax['igst_id'] = $v->tax_id;
              $tax['igst_status'] = $v->status;
            }
          }
        }

        if ($tax['cgst_status'] == 1) {

          $tr .= "<input type=\"hidden\" id=\"cgst_" . $product_id . "\" class=\"cgst\" value='" . $product_details['cgst_tax'] . "'/>
						<input type=\"hidden\" id=\"total_cgst_" . $product_id . "\" class=\"total_cgst\" name=\"cgst[]\" value='" . $product_details['cgst_tax'] * $product_details['price'] . "'/>
						<input type=\"hidden\" name=\"cgst_id[]\" id=\"cgst_id_" . $product_id . "\" value='" . $product_details['cgst_id'] . "'/>";
        }
        if ($tax['sgst_status'] == 1) {

          $tr .= "<input type=\"hidden\" id=\"sgst_" . $product_id . "\" class=\"sgst\" value='" . $product_details['sgst_tax'] . "'/>
						<input type=\"hidden\" id=\"total_sgst_" . $product_id . "\" class=\"total_sgst\" name=\"sgst[]\" value='" . $product_details['sgst_tax'] * $product_details['price'] . "'/>
						<input type=\"hidden\" name=\"sgst_id[]\" id=\"sgst_id_" . $product_id . "\" value='" . $product_details['sgst_id'] . "'/>";
        }
        if ($tax['igst_status'] == 1) {

          $tr .= "<input type=\"hidden\" id=\"igst_" . $product_id . "\" class=\"igst\" value='" . $product_details['igst_tax'] . "'/>
						<input type=\"hidden\" id=\"total_igst_" . $product_id . "\" class=\"total_igst\" name=\"igst[]\" value='" . $product_details['igst_tax'] * $product_details['price'] . "'/>
						<input type=\"hidden\" name=\"igst_id[]\" id=\"igst_id_" . $product_id . "\" value='" . $product_details['igst_id'] . "'/>";
        }

        $tr .= "<input type=\"hidden\" id=\"total_discount_" . $product_id . "\" class=\"\" />
					<input type=\"hidden\" id=\"all_discount_" . $product_id . "\" class=\"total_discount\"/>



					<a href=\"#\" class=\"ajax_modal btn btn-primary btn-xs m-r-2\" data-toggle=\"modal\" data-target=\"#myModal\"><i class=\"fa fa-pencil\" data-toggle=\"tooltip\" data-placement=\"left\" title='" . display('edit') . "'></i></a>

					<a href=\"#\" class=\"btn btn-danger btn-xs\" data-toggle=\"tooltip\" data-placement=\"top\" title='" . display('delete') . "' onclick=\"deletePosRow('" . $product_id . "')\"><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i></a>
					</td>
					</tr>";

        $order .= "<tr class='" . $product_id . "' data-item-id='" . $product_id . "'>
					<td>0</td>
					<td>" . $product_details['product_model'] . "-" . $product_details['product_name'] . "</td>
					<td id='quantity_" . $product_id . "'>[ 1 ]</td>
					</tr>";

        $bill .= "<tr class='" . $product_id . "' data-item-id='" . $product_id . "'>
					<td>0</td>
					<td colspan=\"2\" class=\"no-border\">" . $product_details['product_model'] . "-" . $product_details['product_name'] . "</td>
					<td class='qnt_price_" . $product_id . "'>(1 x " . $product_details['price'] . ")</td>
					<td style=\"text-align:right;\" class='total_price_bill_" . $product_id . "'>" . $product_details['price'] . "</td>
					</tr>";

        echo json_encode(array(
          'item' => $tr,
          'order' => $order,
          'bill' => $bill,
          'product_id' => $product_id
        ));
      } else {
        echo json_encode(array(
          'item' => 0
        ));
      }
    } else {
      echo json_encode(array(
        'item' => 0
      ));
    }
  }

  //Retrive right now inserted data to cretae html
  public function pos_invoice_inserted_data($invoice_id)
  {
    $CI = &get_instance();
    $CI->auth->check_admin_auth();
    $CI->load->library('linvoice');
    $content = $CI->linvoice->pos_invoice_html_data($invoice_id);
    $this->template->full_admin_html_view($content);
  }

  public function pos_invoice_inserted_data_redirect($invoice_id)
  {
    $CI = &get_instance();
    //$CI->auth->check_admin_auth();
    $CI->load->library('linvoice');
    $content = $CI->linvoice->pos_invoice_html_data_redirect($invoice_id);
    //$this->template->full_admin_html_view($content);
  }

  // Retrieve product data
  public function retrieve_product_data()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->model('Invoices');
    $product_id = $this->input->post('product_id');
    $product_info = $CI->Invoices->get_total_product($product_id);
    echo json_encode($product_info);
  }

  // Invoice Delete
  public function invoice_delete($invoice_id)
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->model('Invoices');
    $result = $CI->Invoices->delete_invoice($invoice_id);
    if ($result) {
      $this->session->set_userdata(array('message' => display('successfully_delete')));
      redirect('Cinvoice/manage_invoice');
    }
  }

  //AJAX INVOICE STOCK Check
  public function product_stock_check($product_id)
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->model('Invoices');

    $purchase_stocks = $CI->Invoices->get_total_purchase_item($product_id);
    $total_purchase = 0;
    if (!empty($purchase_stocks)) {
      foreach ($purchase_stocks as $k => $v) {
        $total_purchase = ($total_purchase + $purchase_stocks[$k]['quantity']);
      }
    }
    $sales_stocks = $CI->Invoices->get_total_sales_item($product_id);
    $total_sales = 0;
    if (!empty($sales_stocks)) {
      foreach ($sales_stocks as $k => $v) {
        $total_sales = ($total_sales + $sales_stocks[$k]['quantity']);
      }
    }

    $final_total = ($total_purchase - $total_sales);
    return $final_total;
  }

  //Search product by product name and category
  public function search_product()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->model('Invoices');
    $product_name = $this->input->post('product_name');
    $category_id = $this->input->post('category_id');
    $product_search = $this->Invoices->product_search($product_name, $category_id);
    $base_url = base_url();
    if ($product_search) {
      foreach ($product_search as $product) {
        echo "<div class=\"col-xs-6 col-sm-4 col-md-2 col-p-3\">";
        echo "<div class=\"panel panel-bd product-panel select_product\">";
        echo "<div class=\"panel-body\">";
        echo "<img src=\"$base_url$product->image_thumb\" class=\"img-responsive\" alt=\"\">";
        echo "<input type=\"hidden\" name=\"select_product_id\" class=\"select_product_id\" value='" . $product->product_id . "'>";
        echo "</div>";
        echo "<div class=\"panel-footer\">$product->product_name - ($product->product_model)</div>";
        echo "</div>";
        echo "</div>";
      }
    } else {
      echo "420";
    }
  }

  //Insert new customer
  public function insert_customer()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->model('Invoices');

    $email = $this->input->post('email');
    $customer_mobile = $this->input->post('mobile');

    $customer_id = $this->auth->generator(15);

    //Customer  basic information adding.
    $data = array(
      'customer_id' => $customer_id,
      'customer_name' => $this->input->post('customer_name'),
      'customer_mobile' => !empty($customer_mobile) ? $customer_mobile : "000000000000",
      'customer_email' => !empty($email) ? $email : "sample@test.com",
      'status' => 1
    );

    $result = $this->Invoices->customer_entry($data);

    if ($result == TRUE) {
      $this->session->set_userdata(array('message' => display('successfully_added')));
      redirect(base_url('Cinvoice/pos_invoice'));
    } else {
      $this->session->set_userdata(array('error_message' => display('already_exists')));
      redirect(base_url('Cinvoice/pos_invoice'));
    }
  }

  //Update status
  public function update_status($invoice_id)
  {

    $this->auth->check_admin_auth();
    $CI = &get_instance();
    $CI->load->model('Invoices');
    $CI->load->model('Soft_settings');

    $invoice_status = $this->input->post('invoice_status');
    $order_no = $this->input->post('order_no');
    $order_id = $this->input->post('order_id');
    $customer_id = $this->input->post('customer_id');

    if ($invoice_status == 1) {
      $invoice_status_text = "Shipped"; //for send sms

    };

    if ($invoice_status == 5) {
      $invoice_status_text = "Processing"; //for send sms
    };


    if ($invoice_status == 6) { //6== product return
      //Delete order table
      $this->db->where('order_id', $order_id);
      $this->db->delete('order');
      //Delete order_details table
      $this->db->where('order_id', $order_id);
      $this->db->delete('order_details');
      //Order tax summary delete
      $this->db->where('order_id', $order_id);
      $this->db->delete('order_tax_col_summary');
      //Order tax details delete
      $this->db->where('order_id', $order_id);
      $this->db->delete('order_tax_col_details');

      //invoice details delete
      $this->db->where('invoice_id', $invoice_id);
      $this->db->delete('invoice_details');

      //invoice  delete
      $this->db->where('invoice_id', $invoice_id);
      $this->db->delete('invoice');
      //customer ledger
      $this->db->where('invoice_no', $invoice_id);
      $this->db->delete('customer_ledger');

      //tax_collection_summary
      $this->db->where('invoice_id', $invoice_id);
      $this->db->delete('tax_collection_summary');

      //tax_collection_details
      $this->db->where('invoice_id', $invoice_id);
      $this->db->delete('tax_collection_details');
      $CI->session->set_userdata(array('message' => display('successfully_delete')));
      redirect(base_url('Cinvoice/manage_invoice'));
    };


    //Update invoice status
    $this->db->set('invoice_status', $invoice_status);
    $this->db->where('invoice_id', $invoice_id);
    $result = $this->db->update('invoice');

    $setting_detail = $CI->Soft_settings->retrieve_email_editdata();
    $sms_service = $CI->Soft_settings->retrieve_setting_editdata();

    if ($result === true) {
      if ($sms_service[0]['sms_service'] == 1) {
        if ($invoice_status_text == "Processing" || $invoice_status_text == "Shipped") {

          $this->Homes->send_sms($order_no, $customer_id, $invoice_status_text); //$invoice_status is type in send_sms method
        }
      }

      $subject = display("invoice_status");
      $message = $this->input->post('add_note');

      $config = array(
        'protocol' => $setting_detail[0]['protocol'],
        'smtp_host' => $setting_detail[0]['smtp_host'],
        'smtp_port' => $setting_detail[0]['smtp_port'],
        'smtp_user' => $setting_detail[0]['sender_email'],
        'smtp_pass' => $setting_detail[0]['password'],
        'mailtype' => $setting_detail[0]['mailtype'],
        'charset' => 'utf-8'
      );

      $CI->load->library('email', $config);
      $CI->email->set_newline("\r\n");
      $CI->email->from($setting_detail[0]['sender_email']);
      $CI->email->to($this->input->post('customer_email'));
      $CI->email->subject($subject);
      $CI->email->message($message);

      $email = $this->test_input($this->input->post('customer_email'));
      $server_status = $this->serverAliveOrNot();
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($server_status && $CI->email->send()) {
          $CI->session->set_userdata(array('message' => display('email_send_to_customer')));
          redirect(base_url('Cinvoice/manage_invoice'));
        } else {
          $CI->session->set_userdata(array('error_message' => display('email_not_send')));
          redirect(base_url('Cinvoice/manage_invoice'));
        }
      } else {
        $CI->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Cinvoice/manage_invoice'));
      }
    } else {
      $this->session->set_userdata(array('error_message' => display('ooops_something_went_wrong')));
      redirect(base_url('Cinvoice/manage_invoice'));
    }
  }

  // check mail server configured or not
  private function serverAliveOrNot()
  {
    $url = base_url();
    if ($pf = @fsockopen($url, 587)) {
      fclose($pf);
      $_SESSION['serverAliveOrNot'] = true;
      return true;
    } else {
      $_SESSION['serverAliveOrNot'] = false;
      return false;
    }
  }


  //Email testing for email
  public function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  //Search Inovoice Item
  public function search_inovoice_item()
  {
    $CI = &get_instance();
    $this->auth->check_admin_auth();
    $CI->load->library('linvoice');

    $customer_id = $this->input->post('customer_id');
    $content = $CI->linvoice->search_inovoice_item($customer_id);
    $this->template->full_admin_html_view($content);
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
}

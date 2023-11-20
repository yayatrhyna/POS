<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lreport
{
    // Retrieve All Stock Report
    public function stock_report($limit, $page, $links)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');
        $stok_report = $CI->Reports->stock_report($limit, $page);

        if (!empty($stok_report)) {
            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
            }
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['stok_quantity'] = $stok_report[$k]['totalBuyQnty'] - $stok_report[$k]['totalSalesQnty'];
                $stok_report[$k]['totalSalesCtn'] = $stok_report[$k]['totalSalesQnty'] / $stok_report[$k]['cartoon_quantity'];
                $stok_report[$k]['totalPrhcsCtn'] = $stok_report[$k]['totalBuyQnty'] / $stok_report[$k]['cartoon_quantity'];

                $stok_report[$k]['stok_quantity_cartoon'] = $stok_report[$k]['stok_quantity'] / $stok_report[$k]['cartoon_quantity'];

            }
        }
        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('stock_report'),
            'stok_report' => $stok_report,
            'links' => $links,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],

        );
        $reportList = $CI->parser->parse('report/stock_report', $data, true);
        return $reportList;
    }

    //Out of stock
    public function out_of_stock()
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');

        $out_of_stock = $CI->Reports->out_of_stock();

        if (!empty($out_of_stock)) {
            $i = 0;
            foreach ($out_of_stock as $k => $v) {
                $i++;
                $out_of_stock[$k]['sl'] = $i;
            }
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('out_of_stock'),
            'out_of_stock' => $out_of_stock,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],

        );

        $reportList = $CI->parser->parse('report/out_of_stock', $data, true);
        return $reportList;
    }

    // Retrieve Single Item Stock Stock Report
    public function stock_report_single_item($product_id, $date, $limit, $page, $link)
    {

        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->library('store_keeperoccational');
        $stok_report = $CI->Reports->stock_report_bydate($product_id, $date, $limit, $page);

        if (($stok_report)) {
            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
            }

            foreach ($stok_report as $k => $v) {

                $sales = $CI->db->select("
					sum(quantity) as totalSalesQnty,
					quantity
					")
                    ->from('invoice_details')
                    ->where('product_id', $v['product_id'])
                    ->get()
                    ->row();

                $stok_report[$k]['stok_quantity_cartoon'] = ($stok_report[$k]['totalPurchaseQnty'] - $sales->totalSalesQnty);
                $stok_report[$k]['totalSalesCtn'] = $sales->totalSalesQnty;
                $stok_report[$k]['totalPrhcsCtn'] = $stok_report[$k]['totalPurchaseQnty'];
            }
        } 
        // else {
        //     $CI->session->set_userdata('error_message', display('stock_not_available'));
        //     redirect('Creport');
        // }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Reports->retrieve_company();

        $data = array(
            'title' => display('stock_report'),
            'stok_report' => $stok_report,
            'link' => $link,
            'date' => $date,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );

        $reportList = $CI->parser->parse('report/stock_report', $data, true);
        return $reportList;
    }

    // Stock report supplier wise
    public function stock_report_supplier_wise($product_id, $supplier_id, $date, $links, $per_page, $page)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Suppliers');
        $CI->load->library('store_keeperoccational');
        $stok_report = $CI->Reports->stock_report_supplier_bydate($product_id, $supplier_id, $date, $per_page, $page);
        $supplier_list = $CI->Suppliers->supplier_list_report();
        $supplier_info = $CI->Suppliers->retrieve_supplier_editdata($supplier_id);

        $sub_total_in = 0;
        $sub_total_out = 0;
        $sub_total_stock = 0;

        if (($stok_report)) {
            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
            }
            foreach ($stok_report as $k => $v) {
                $i++;

                $sales = $CI->db->select("
					sum(quantity) as totalSalesQnty,
					quantity
					")
                    ->from('invoice_details')
                    ->where('product_id', $v['product_id'])
                    ->get()
                    ->row();

                $stok_report[$k]['stok_quantity'] = ($stok_report[$k]['totalPrhcsCtn'] - $sales->totalSalesQnty);
                $stok_report[$k]['SubTotalOut'] = ($sub_total_out + $sales->totalSalesQnty);
                $sub_total_out = $stok_report[$k]['SubTotalOut'];
                $stok_report[$k]['SubTotalIn'] = ($sub_total_in + $stok_report[$k]['totalPrhcsCtn']);
                $sub_total_in = $stok_report[$k]['SubTotalIn'];
                $stok_report[$k]['in_qnty'] = $stok_report[$k]['totalPrhcsCtn'];
                $stok_report[$k]['out_qnty'] = $sales->totalSalesQnty;
                $stok_report[$k]['SubTotalStock'] = ($sub_total_stock + $stok_report[$k]['stok_quantity']);
                $sub_total_stock = $stok_report[$k]['SubTotalStock'];
            }
        } else {
            $CI->session->set_userdata('error_message', display('stock_not_available'));
            redirect('Creport');
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('stock_report_supplier_wise'),
            'stok_report' => $stok_report,
            'product_model' => $stok_report[0]['product_model'],
            'links' => $links,
            'date' => $date,
            'sub_total_in' => $sub_total_in,
            'sub_total_out' => $sub_total_out,
            'sub_total_stock' => $sub_total_stock,
            'supplier_list' => $supplier_list,
            'supplier_info' => $supplier_info,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );

        $reportList = $CI->parser->parse('report/stock_report_supplier_wise', $data, true);
        return $reportList;
    }

    // Stock Report Product Wise
    public function stock_report_product_wise($product_id, $supplier_id, $from_date, $to_date, $links, $per_page, $page)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Suppliers');
        $CI->load->library('store_keeperoccational');
        $stok_report = $CI->Reports->stock_report_product_bydate($product_id, $supplier_id, $from_date, $to_date, $per_page, $page);

        $supplier_list = $CI->Suppliers->supplier_list_report();
        $supplier_info = $CI->Suppliers->retrieve_supplier_editdata($supplier_id);

        $sub_total_in = 0;
        $sub_total_out = 0;
        $sub_total_stock = 0;
        $sub_total_in_qnty = 0;
        $sub_total_out_qnty = 0;
        $sub_total_in_taka = 0;
        $sub_total_out_taka = 0;
        $stok_quantity_cartoon = 0;

        if (($stok_report)) {
            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
            }

            foreach ($stok_report as $k => $v) {
                $i++;

                $sales = $CI->db->select("
					sum(quantity) as totalSalesQnty,
					quantity,
					rate
					")
                    ->from('invoice_details')
                    ->where('product_id', $v['product_id'])
                    ->get()
                    ->row();

                $stok_report[$k]['stok_quantity_cartoon'] = ($stok_report[$k]['totalPurchaseQnty'] - $sales->totalSalesQnty);
                $stok_report[$k]['totalSalesQnty'] = ($sales->totalSalesQnty);
                $stok_report[$k]['SubTotalStock'] = ($sub_total_stock + $stok_report[$k]['stok_quantity_cartoon']);
                $sub_total_stock = $stok_report[$k]['SubTotalStock'];
                $stok_report[$k]['in_taka'] = $stok_report[$k]['totalPurchaseQnty'] * $stok_report[$k]['supplier_price'];
                $stok_report[$k]['out_taka'] = $sales->totalSalesQnty * $stok_report[$k]['supplier_price'];
                $stok_report[$k]['SubTotalinQnty'] = ($sub_total_in_qnty + $stok_report[$k]['totalPurchaseQnty']);
                $sub_total_in_qnty = $stok_report[$k]['SubTotalinQnty'];
                $stok_report[$k]['SubTotaloutQnty'] = ($sub_total_out_qnty + $sales->totalSalesQnty);
                $sub_total_out_qnty = $stok_report[$k]['SubTotaloutQnty'];
                $stok_report[$k]['SubTotalinTaka'] = ($sub_total_in_taka + $stok_report[$k]['in_taka']);
                $sub_total_in_taka = $stok_report[$k]['SubTotalinTaka'];
                $stok_report[$k]['SubTotalOutTaka'] = ($sub_total_out_taka + $stok_report[$k]['out_taka']);
                $sub_total_out_taka = $stok_report[$k]['SubTotalOutTaka'];
            }
        } else {
            $CI->session->set_userdata('error_message', display('stock_not_available'));
            redirect('Creport');
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('stock_report_product_wise'),
            'stok_report' => $stok_report,
            'product_model' => $stok_report[0]['product_model'],
            'links' => $links,
            'date' => $to_date,
            'sub_total_in' => $sub_total_in,
            'sub_total_out' => $sub_total_out,
            'sub_total_stock' => $sub_total_stock,
            'SubTotalinQnty' => $sub_total_in_qnty,
            'SubTotaloutQnty' => $sub_total_out_qnty,
            'sub_total_in_taka' => number_format($sub_total_in_taka, 2, '.', ','),
            'sub_total_out_taka' => number_format($sub_total_out_taka, 2, '.', ','),
            'supplier_list' => $supplier_list,
            'supplier_info' => $supplier_info,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );

        $reportList = $CI->parser->parse('report/stock_report_product_wise', $data, true);
        return $reportList;
    }

    // Stock Report Variant Wise
    public function stock_report_variant_wise($from_date, $to_date, $store_id, $links, $per_page, $page)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Suppliers');
        $CI->load->model('store_keeper/Products');
        $CI->load->model('store_keeper/Stores');
        $CI->load->library('store_keeperoccational');

        if(empty($store_id)){
           $from_date = date('m-01-Y');
           $to_date = date('m-d-Y');
            $result =  $CI->db->select('store_id')->from('store_set')->where('default_status=',1)->get()->row();
            $store_id = $result->store_id;
        }

        $stok_report = $CI->Reports->stock_report_by_store($from_date, $to_date, $store_id, $per_page, $page);

        $product_list = $CI->Products->product_list();
        $store_list = $CI->Stores->store_list();


        $sub_total_in = 0;
        $sub_total_out = 0;
        $sub_total_stock = 0;

        $i = $page;
        foreach ($stok_report as $k => $v) {
            $i++;
            $stok_report[$k]['sl'] = $i;

        }
        if ($stok_report) {
            foreach ($stok_report as $k => $v) {
                $i++;

                $sales = $CI->db->select("sum(quantity) as totalSalesQnty,quantity")
                    ->from('invoice_details')
                    ->where('product_id', @$v['product_id'])
                    ->where('variant_id', @$v['variant_id'])
                    ->where('store_id', @$v['store_id'])
                    ->get()
                    ->row();

                $purchase = $CI->db->select("sum(b.quantity) as totalPrhcsCtn")
                    ->from('transfer b')
                    ->where('product_id', @$v['product_id'])
                    ->where('variant_id', @$v['variant_id'])
                    ->where('store_id', @$v['store_id'])
                    ->where('t_store_id =', null)
                    ->get()
                    ->row();

                $receive = $CI->db->select("sum(b.quantity) as totalReceive")
                    ->from('transfer b')
                    ->where('product_id', @$v['product_id'])
                    ->where('variant_id', @$v['variant_id'])
                    ->where('store_id', @$v['store_id'])
                    ->where('quantity >', 0)
                    ->where('t_store_id !=', null)
                    ->get()
                    ->row();

                $send = $CI->db->select("sum(b.quantity) as totalSend")
                    ->from('transfer b')
                    ->where('product_id', @$v['product_id'])
                    ->where('variant_id', @$v['variant_id'])
                    ->where('store_id', @$v['store_id'])
                    ->where('quantity <', 0)
                    ->where('t_store_id !=', null)
                    ->get()
                    ->row();


                $stok_report[$k]['stok_quantity'] = (@$purchase->totalPrhcsCtn + @$receive->totalReceive -
                    $sales->totalSalesQnty + @$send->totalSend);

                $stok_report[$k]['SubTotalOut'] = ($sub_total_out + $sales->totalSalesQnty);
                $sub_total_out = $stok_report[$k]['SubTotalOut'];

                $stok_report[$k]['SubTotalIn'] = ($sub_total_in + @$receive->totalReceive + @$purchase->totalPrhcsCtn);
                $sub_total_in = $stok_report[$k]['SubTotalIn'];

                $stok_report[$k]['in_qnty'] = @$purchase->totalPrhcsCtn;
                $stok_report[$k]['out_qnty'] = $sales->totalSalesQnty;
                $stok_report[$k]['rec_qty'] = @$receive->totalReceive;

                $stok_report[$k]['issue_qty'] = @substr($send->totalSend, 1);
                $stok_report[$k]['SubTotalStock'] = ($sub_total_stock + $stok_report[$k]['stok_quantity']);
                $sub_total_stock = $stok_report[$k]['SubTotalStock'];

            }
        } else {
            $CI->session->set_userdata('error_message', display('stock_not_available'));

        }
        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('stock_report_store_wise'),
            'stok_report' => $stok_report,
            'product_model' => @$stok_report[0]['product_model'],
            'links' => $links,
            'date' => '',
            'sub_total_in' => $sub_total_in,
            'sub_total_out' => $sub_total_out,
            'sub_total_stock' => $sub_total_stock,
            'product_list' => $product_list,
            'store_list' => $store_list,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/stock_report_variant_wise', $data, true);
        return $reportList;


    }

// ===========================STORE WISE STOCK REPORT======================
    public function store_wise_product($links = null, $per_page = null, $page = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $stok_report = $CI->Reports->store_wise_product($per_page, $page);
        $i = $page;
        if (!empty($stok_report)) {
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
            }
        }

        $data = [
            'store_product_list' => $stok_report,
            'links' => $links
        ];
        $reportList = $CI->parser->parse('report/stock_report_store_wise', $data, true);
        return $reportList;

    }


//=================sales report store wise==================
    public function sales_report_store_wise($links = null, $per_page = null, $page = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Stores');
        $CI->load->model('store_keeper/Reports');
        $store_list = $CI->Stores->store_list();
        $data = [
            'stores' => $store_list
        ];
        $reportList = $CI->parser->parse('report/sales_report_store_wise', $data, true);
        return $reportList;
    }

    public function retrieve_sales_report_store_wise($store_id, $start_date = null, $end_date = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Stores');
        $store_list = $CI->Stores->store_list();
        $sales_reports = $CI->Reports->retrieve_sales_report_store_wise($store_id, $start_date, $end_date);
        // dd($sales_reports);
        $data = [
            'sales_reports' => $sales_reports,
            'stores' => $store_list,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
        $reportList = $CI->parser->parse('report/sales_report_store_wise', $data, true);
        return $reportList;
    }

    // Retrieve todays_sales_report
    public function todays_sales_report($links = null, $per_page = null, $page = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Web_settings');
        $CI->load->library('store_keeperoccational');

        $total_sales_report_cash = $CI->Reports->todays_all_sales_report_by_payment("Cash");
        $total_sales_report_qris = $CI->Reports->todays_all_sales_report_by_payment("QRIS");
        $total_sales_report_credit_card = $CI->Reports->todays_all_sales_report_by_payment("Credit Card");

        $sales_report = $CI->Reports->todays_sales_report($per_page, $page);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $time_invoice_created = substr($sales_report[$k]['invoice_datetime_created'], strpos($sales_report[$k]['invoice_datetime_created'], " ") + 1);
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']) . ' ' . $time_invoice_created;
                $sales_report[$k]['store_code'] = strtok($sales_report[$k]['store_name'], '-');
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }

        // all sales repoort, not by page
        $all_sales_report = $CI->Reports->todays_all_sales_report();
        $total_sales_amount = 0;
        if (!empty($all_sales_report)) {
            $i = 0;
            foreach ($all_sales_report as $k => $v) {
                $total_sales_amount = $total_sales_amount + $all_sales_report[$k]['total_amount'];
            }
        }


        $currency_details = $CI->Soft_settings->retrieve_currency_info();

        $total_cash_invoice = $CI->Reports->todays_total_invoice_report_by_payment("Cash");
        $total_qris_invoice = $CI->Reports->todays_total_invoice_report_by_payment("QRIS");
        $total_credit_card_invoice = $CI->Reports->todays_total_invoice_report_by_payment("Credit Card");

        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('todays_sales_report'),
            'sales_amount' => number_format($sales_amount, 2, '.', ','),
            'total_sales_amount' => number_format($total_sales_amount, 2, '.', ','),
            'total_sales_report_cash' => number_format($total_sales_report_cash[0]['total_by_payment'], 2, '.', ','),
            'total_sales_report_qris' => number_format($total_sales_report_qris[0]['total_by_payment'], 2, '.', ','),
            'total_sales_report_credit_card' => number_format($total_sales_report_credit_card[0]['total_by_payment'], 2, '.', ','),
            'total_cash_invoice' => number_format($total_cash_invoice, 0, '.', ','),
            'total_qris_invoice' => number_format($total_qris_invoice, 0, '.', ','),
            'total_credit_card_invoice' => number_format($total_credit_card_invoice, 0, '.', ','),
            'sales_report' => $sales_report,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
            'links' => $links,
            'payment' => "All",
        );
        $reportList = $CI->parser->parse('report/sales_report', $data, true);
        return $reportList;
    }

    // Retrieve todays_sales_report by date
    public function todays_sales_report_by_date($links = null, $per_page = null, $page = null, $from_date = null, $to_date = null, $payment = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Web_settings');
        $CI->load->library('store_keeperoccational');
        
        if ($from_date == null) {
          $from_date = $this->session->userdata('report_sales_from_date');
        }
    
        if ($to_date == null) {
          $to_date = $this->session->userdata('report_sales_to_date');
        }

        if ($payment == null) {
            $payment = $this->session->userdata('filter_paymment');
        }

        $total_sales_report_cash = $CI->Reports->todays_all_sales_report_by_payment_and_date("Cash", $from_date, $to_date);
        $total_sales_report_qris = $CI->Reports->todays_all_sales_report_by_payment_and_date("QRIS", $from_date, $to_date);
        $total_sales_report_credit_card = $CI->Reports->todays_all_sales_report_by_payment_and_date("Credit Card", $from_date, $to_date);

        $total_cash_invoice = $CI->Reports->todays_total_invoice_report_by_payment_and_date("Cash", $from_date, $to_date);
        $total_qris_invoice = $CI->Reports->todays_total_invoice_report_by_payment_and_date("QRIS", $from_date, $to_date);
        $total_credit_card_invoice = $CI->Reports->todays_total_invoice_report_by_payment_and_date("Credit Card", $from_date, $to_date);

        $sales_report = $CI->Reports->todays_sales_report_by_date($per_page, $page, $from_date, $to_date, $payment);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $time_invoice_created = substr($sales_report[$k]['invoice_datetime_created'], strpos($sales_report[$k]['invoice_datetime_created'], " ") + 1);
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']) . ' ' . $time_invoice_created;
                $sales_report[$k]['store_code'] = strtok($sales_report[$k]['store_name'], '-');
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }

        // all sales report, not by page
        $all_sales_report = $CI->Reports->todays_all_sales_report_by_date($from_date, $to_date);
        $total_sales_amount = 0;
        if (!empty($all_sales_report)) {
            $i = 0;
            foreach ($all_sales_report as $k => $v) {
                $total_sales_amount = $total_sales_amount + $all_sales_report[$k]['total_amount'];
            }
        }


        $currency_details = $CI->Soft_settings->retrieve_currency_info();

        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('todays_sales_report'),
            'sales_amount' => number_format($sales_amount, 2, '.', ','),
            'total_sales_amount' => number_format($total_sales_amount, 2, '.', ','),
            'total_sales_report_cash' => number_format($total_sales_report_cash[0]['total_by_payment'], 2, '.', ','),
            'total_sales_report_qris' => number_format($total_sales_report_qris[0]['total_by_payment'], 2, '.', ','),
            'total_sales_report_credit_card' => number_format($total_sales_report_credit_card[0]['total_by_payment'], 2, '.', ','),
            'total_cash_invoice' => number_format($total_cash_invoice, 0, '.', ','),
            'total_qris_invoice' => number_format($total_qris_invoice, 0, '.', ','),
            'total_credit_card_invoice' => number_format($total_credit_card_invoice, 0, '.', ','),
            'sales_report' => $sales_report,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
            'links' => $links,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'payment' => $payment,
        );
        $reportList = $CI->parser->parse('report/sales_report', $data, true);
        return $reportList;
    }

    // Retrieve datewise sales report
    public function retrieve_dateWise_SalesReports($start_date, $end_date)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Web_settings');
        $CI->load->library('store_keeperoccational');
        $sales_report = $CI->Reports->retrieve_dateWise_SalesReports($start_date, $end_date);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $currency_details = $CI->Soft_settings->retrieve_currency_info();


        // all sales repoort, not by page
        $all_sales_report = $CI->Reports->todays_all_sales_report();
        $total_sales_amount = 0;
        if (!empty($all_sales_report)) {
            $i = 0;
            foreach ($all_sales_report as $k => $v) {
                $total_sales_amount = $total_sales_amount + $all_sales_report[$k]['total_amount'];
            }
        }

        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('sales_report'),
            'sales_amount' => $sales_amount,
            'total_sales_amount' => number_format($total_sales_amount, 2, '.', ','),
            'sales_report' => $sales_report,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
            'links' => '',
        );
        $reportList = $CI->parser->parse('report/sales_report', $data, true);
        return $reportList;
    }

    // Retrieve todays_purchase_report
    public function todays_purchase_report($links = null, $per_page = null, $page = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Web_settings');
        $CI->load->library('store_keeperoccational');
        $purchase_report = $CI->Reports->todays_purchase_report($per_page, $page);
        $purchase_amount = 0;

        if (!empty($purchase_report)) {
            $i = 0;
            foreach ($purchase_report as $k => $v) {
                $i++;
                $purchase_report[$k]['sl'] = $i;
                $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
                $purchase_amount = $purchase_amount + $purchase_report[$k]['grand_total_amount'];
            }
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('todays_purchase_report'),
            'purchase_amount' => number_format($purchase_amount, 2, '.', ','),
            'purchase_report' => $purchase_report,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
            'links' => $links,
        );
        $reportList = $CI->parser->parse('report/purchase_report', $data, true);
        return $reportList;
    }

    //Total profit report
    public function total_profit_report($links, $per_page, $page)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');
        $total_profit_report = $CI->Reports->total_profit_report($per_page, $page);

        $profit_ammount = 0;
        $SubTotalSupAmnt = 0;
        $SubTotalSaleAmnt = 0;
        if (!empty($total_profit_report)) {
            $i = 0;
            foreach ($total_profit_report as $k => $v) {
                $total_profit_report[$k]['sl'] = $i;
                $total_profit_report[$k]['prchse_date'] = $CI->occational->dateConvert($total_profit_report[$k]['date']);
                $profit_ammount = $profit_ammount + $total_profit_report[$k]['total_profit'];
                $SubTotalSupAmnt = $SubTotalSupAmnt + $total_profit_report[$k]['total_supplier_rate'];
                $SubTotalSaleAmnt = $SubTotalSaleAmnt + $total_profit_report[$k]['total_sale'];
            }
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('total_profit_report'),
            'profit_ammount' => number_format($profit_ammount, 2, '.', ','),
            'total_profit_report' => $total_profit_report,
            'SubTotalSupAmnt' => number_format($SubTotalSupAmnt, 2, '.', ','),
            'SubTotalSaleAmnt' => number_format($SubTotalSaleAmnt, 2, '.', ','),
            'links' => $links,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/profit_report', $data, true);
        return $reportList;
    }

    //Retrive datewise purchase report
    public function retrieve_dateWise_PurchaseReports($start_date, $end_date)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');
        $purchase_report = $CI->Reports->retrieve_dateWise_PurchaseReports($start_date, $end_date);
        $purchase_amount = 0;
        if (!empty($purchase_report)) {
            $i = 0;
            foreach ($purchase_report as $k => $v) {
                $i++;
                $purchase_report[$k]['sl'] = $i;
                $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
                $purchase_amount = $purchase_amount + $purchase_report[$k]['grand_total_amount'];
            }
        }
        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('purchase_report'),
            'purchase_amount' => $purchase_amount,
            'purchase_report' => $purchase_report,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
            'links' => ''
        );
        $reportList = $CI->parser->parse('report/purchase_report', $data, true);
        return $reportList;
    }

    //Retrive date wise total profit report
    public function retrieve_dateWise_profit_report($start_date, $end_date)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');
        $total_profit_report = $CI->Reports->retrieve_dateWise_profit_report($start_date, $end_date);

        $profit_ammount = 0;
        $SubTotalSupAmnt = 0;
        $SubTotalSaleAmnt = 0;
        if (!empty($total_profit_report)) {
            $i = 0;
            foreach ($total_profit_report as $k => $v) {
                $total_profit_report[$k]['sl'] = $i;
                $total_profit_report[$k]['prchse_date'] = $CI->occational->dateConvert($total_profit_report[$k]['date']);
                $profit_ammount = $profit_ammount + $total_profit_report[$k]['total_profit'];
                $SubTotalSupAmnt = $SubTotalSupAmnt + $total_profit_report[$k]['total_supplier_rate'];
                $SubTotalSaleAmnt = $SubTotalSaleAmnt + $total_profit_report[$k]['total_sale'];
            }
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('profit_report'),
            'profit_ammount' => number_format($profit_ammount, 2, '.', ','),
            'total_profit_report' => $total_profit_report,
            'SubTotalSupAmnt' => number_format($SubTotalSupAmnt, 2, '.', ','),
            'SubTotalSaleAmnt' => number_format($SubTotalSaleAmnt, 2, '.', ','),
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/profit_report', $data, true);
        return $reportList;
    }

    // Retrieve transfer report
    public function transfer_report($from_date = null, $to_date = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');

        $store_to_store_transfer = $CI->Reports->store_to_store_transfer($from_date, $to_date);
        $store_to_warehouse_transfer = $CI->Reports->store_to_warehouse_transfer($from_date, $to_date);
        $warehouse_to_store_transfer = $CI->Reports->warehouse_to_store_transfer($from_date, $to_date);
        $warehouse_to_warehouse_transfer = $CI->Reports->warehouse_to_warehouse_transfer($from_date, $to_date);

        $data = array(
            'title' => display('transfer_report'),
            'store_to_store_transfer' => $store_to_store_transfer,
            'store_to_warehouse_transfer' => $store_to_warehouse_transfer,
            'warehouse_to_store_transfer' => $warehouse_to_store_transfer,
            'warehouse_to_warehouse_transfer' => $warehouse_to_warehouse_transfer,
        );
        $reportList = $CI->parser->parse('report/transfer_report', $data, true);
        return $reportList;
    }

    // Retrieve store to store transfer report
    public function store_to_store_transfer($from_date = null, $to_date = null, $from_store = null, $to_store = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Stores');

        $store_to_store_transfer = $CI->Reports->store_to_store_transfer($from_date, $to_date, $from_store, $to_store);
        $store_list = $CI->Stores->store_list();

        $data = array(
            'title' => display('store_to_store_transfer'),
            'store_to_store_transfer' => $store_to_store_transfer,
            'store_list' => $store_list,
        );
        $reportList = $CI->parser->parse('report/store_to_store_transfer', $data, true);
        return $reportList;
    }

    // Store to wearhouse transfer
    public function store_to_warehouse_transfer($from_date = null, $to_date = null, $from_store = null, $t_wearhouse = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Stores');
        $CI->load->model('store_keeper/Wearhouses');

        $store_to_warehouse_transfer = $CI->Reports->store_to_warehouse_transfer($from_date, $to_date, $from_store, $t_wearhouse);
        $store_list = $CI->Stores->store_list();
        $wearhouse_list = $CI->Wearhouses->wearhouse_list();

        $data = array(
            'title' => display('store_to_warehouse_transfer'),
            'store_to_warehouse_transfer' => $store_to_warehouse_transfer,
            'store_list' => $store_list,
            'wearhouse_list' => $wearhouse_list,
        );
        $reportList = $CI->parser->parse('report/store_to_warehouse_transfer', $data, true);
        return $reportList;
    }

    // Wearhouse to store transfer
    public function warehouse_to_store_transfer($from_date = null, $to_date = null, $wearhouse = null, $t_store = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Stores');
        $CI->load->model('store_keeper/Wearhouses');

        $warehouse_to_store_transfer = $CI->Reports->warehouse_to_store_transfer($from_date, $to_date, $wearhouse, $t_store);
        $store_list = $CI->Stores->store_list();
        $wearhouse_list = $CI->Wearhouses->wearhouse_list();

        $data = array(
            'title' => display('warehouse_to_store_transfer'),
            'warehouse_to_store_transfer' => $warehouse_to_store_transfer,
            'store_list' => $store_list,
            'wearhouse_list' => $wearhouse_list,
        );
        $reportList = $CI->parser->parse('report/warehouse_to_store_transfer', $data, true);
        return $reportList;
    }

    // Wearhouse to wearhouse transfer
    public function warehouse_to_warehouse_transfer($from_date = null, $to_date = null, $wearhouse = null, $t_wearhouse = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Wearhouses');

        $warehouse_to_warehouse_transfer = $CI->Reports->warehouse_to_warehouse_transfer($from_date, $to_date, $wearhouse, $t_wearhouse);
        $wearhouse_list = $CI->Wearhouses->wearhouse_list();
        $data = array(
            'title' => display('warehouse_to_warehouse_transfer'),
            'warehouse_to_warehouse_transfer' => $warehouse_to_warehouse_transfer,
            'wearhouse_list' => $wearhouse_list,
        );
        $reportList = $CI->parser->parse('report/warehouse_to_warehouse_transfer', $data, true);
        return $reportList;
    }

    // Retrieve tax report
    public function tax_report($from_date = null, $to_date = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');

        $tax_report_product_wise = $CI->Reports->tax_report_product_wise($from_date, $to_date);
        $Subtotal_tax_amnt = 0;
        if (!empty($tax_report_product_wise)) {
            $i = 0;
            foreach ($tax_report_product_wise as $k => $v) {
                $tax_report_product_wise[$k]['sl'] = $i;
                $tax_report_product_wise[$k]['date'] = $CI->occational->dateConvert($tax_report_product_wise[$k]['date']);
                $Subtotal_tax_amnt = $Subtotal_tax_amnt + $tax_report_product_wise[$k]['amount'];
            }
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('tax_report_product_wise'),
            'tax_report_product_wise' => $tax_report_product_wise,
            'Subtotal_tax_amnt' => $Subtotal_tax_amnt,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/tax_report_product_wise', $data, true);
        return $reportList;
    }

    // Retrieve tax report
    public function tax_report_invoice_wise($from_date = null, $to_date = null)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');

        $tax_report_invoice_wise = $CI->Reports->tax_report_invoice_wise($from_date, $to_date);
        $Subtotal_tax_amnt = 0;
        if (!empty($tax_report_invoice_wise)) {
            $i = 0;
            foreach ($tax_report_invoice_wise as $k => $v) {
                $tax_report_invoice_wise[$k]['sl'] = $i;
                $tax_report_invoice_wise[$k]['date'] = $CI->occational->dateConvert($tax_report_invoice_wise[$k]['date']);
                $Subtotal_tax_amnt = $Subtotal_tax_amnt + $tax_report_invoice_wise[$k]['tax_amount'];
            }
        }


        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('tax_report_invoice_wise'),
            'tax_report_invoice_wise' => $tax_report_invoice_wise,
            'Subtotal_tax_amnt' => $Subtotal_tax_amnt,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/tax_report_invoice_wise', $data, true);
        return $reportList;
    }

    //Products search report
    public function get_products_search_report($from_date, $to_date)
    {
        $CI =& get_instance();
        $CI->load->model('store_keeper/Reports');
        $CI->load->model('store_keeper/Soft_settings');
        $CI->load->library('store_keeperoccational');
        $product_report = $CI->Reports->retrieve_product_search_sales_report($from_date, $to_date);

        if (!empty($product_report)) {
            $i = 0;
            foreach ($product_report as $k => $v) {
                $i++;
                $product_report[$k]['sl'] = $i;
            }
        }
        $sub_total = 0;
        if (!empty($product_report)) {
            foreach ($product_report as $k => $v) {
                $product_report[$k]['sales_date'] = $CI->occational->dateConvert($product_report[$k]['date']);
                $sub_total = $sub_total + $product_report[$k]['total_price'];
            }
        }
        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('sales_report_product_wise'),
            'sub_total' => number_format($sub_total, 2, '.', ','),
            'product_report' => $product_report,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/product_report', $data, true);
        return $reportList;
    }
}

?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Customer js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer.js.php"></script>
<!-- Product invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_invoice.js.php"></script>
<!-- Invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>


<!-- Add new invoice start -->
<style>
  #bank_info_hide {
    display: none;
  }

  #payment_from_2 {
    display: none;
  }

  /*Order and bill table*/
  #order_tbl,
  #bill_tbl {
    display: none;
  }

  #qrmodal.modal {
    text-align: center;
  }

  @media screen and (min-width: 768px) {
    #qrmodal.modal:before {
      display: inline-block;
      vertical-align: middle;
      content: " ";
      height: 100%;
    }
  }

  #qrmodal .modal-dialog {
    display: inline-block;
    text-align: left;
    vertical-align: middle;
  }

  #return_modal.modal {
    text-align: center;
  }

  @media screen and (min-width: 768px) {
    #return_modal.modal:before {
      display: inline-block;
      vertical-align: middle;
      content: " ";
      height: 100%;
    }
  }

  #return_modal .modal-dialog {
    display: inline-block;
    text-align: left;
    vertical-align: middle;
  }

  .product-list,
  .product-grid {
    overflow-y: scroll !important;
  }

  .slimScrollBar {
    display: none !important;
  }
</style>
<link rel="stylesheet" href="<?= base_url("assets/sweetalert2/dist/sweetalert2.min.css") ?>">

<!-- Printable area start -->
<script type="text/javascript">
  function printDiv(divName) {

    var order_tbl = 0;
    $("#order-table tbody tr").each(function(i) {
      order_tbl = order_tbl + 1;
    });

    var bill_tbl = 0;
    $("#bill-table tbody tr").each(function(i) {
      bill_tbl = bill_tbl + 1;
    });

    if (order_tbl < 1) {
      alert('Please add product !');
    } else if (bill_tbl < 1) {
      alert('Please add product !');
    } else {
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      // document.body.style.marginTop="-45px";
      window.print();
      document.body.innerHTML = originalContents;
    }
  }
</script>
<!-- Printable area end -->

<!-- Customer type change by javascript start -->
<script type="text/javascript">
  function bank_info_show(payment_type) {
    if (payment_type.value == "1") {
      document.getElementById("bank_info_hide").style.display = "none";
    } else {
      document.getElementById("bank_info_hide").style.display = "block";
    }
  }

  function active_customer(status) {
    if (status == "payment_from_2") {
      document.getElementById("payment_from_2").style.display = "none";
      document.getElementById("payment_from_1").style.display = "block";
      document.getElementById("myRadioButton_2").checked = false;
      document.getElementById("myRadioButton_1").checked = true;
    } else {
      document.getElementById("payment_from_1").style.display = "none";
      document.getElementById("payment_from_2").style.display = "block";
      document.getElementById("myRadioButton_2").checked = false;
      document.getElementById("myRadioButton_1").checked = true;
    }
  }

  //Payment method toggle
  $(document).ready(function() {
    $(".payment_button").click(function() {
      $(".payment_method").toggle();

      //Select Option
      $("select.form-control:not(.dont-select-me)").select2({
        placeholder: "Select option",
        allowClear: true
      });

      var getPaymentMethod = document.getElementById('card_type').value;
      if (getPaymentMethod == "Credit Card") {
        $("[name='service_charge']").val(3);
        calculateSum()
      }
    });


    $("[name='store_id']").on('change', function() {
      deletePosAllRow();
    });

    $("[name='card_type']").on('change', function() {
      if (this.value == "Credit Card") {
        $("[name='service_charge']").val(3);
        calculateSum()
      } else {
        $("[name='service_charge']").val(0);
        calculateSum()
      }
    });
  });
</script>
<!-- Customer type change by javascript end -->

<!-- Add New Invoice Start -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">

    <?php /*
        <div class="header-icon"><i class="pe-7s-close"></i></div>
        <div class="header-title">            
            <h1><?php echo display('pos_invoice') ?></h1>
            <small><?php echo display('new_pos_invoice') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo display('home') ?></a></li>
                <li class="active"><?php echo display('pos_invoice') ?></li>
            </ol>
        </div>
    */ ?>

  </section>
  <!-- Main content -->
  <div class="content">
    <!-- Alert Message -->
    <?php
    $message = $this->session->userdata('message');
    if (isset($message)) {
    ?>
      <script>
        Swal({
          position: 'center',
          type: 'success',
          title: '<?php echo $message; ?>',
          showConfirmButton: false,
          timer: 3000
        })
      </script>

    <?php
      $this->session->unset_userdata('message');
    }
    $error_message = $this->session->userdata('error_message');
    if (isset($error_message)) {
    ?>
      <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $error_message ?>
      </div>
    <?php
      $this->session->unset_userdata('error_message');
    }
    ?>

    <!-- Print order for customer -->
    <div id="order_tbl">
      <span id="order_span">

      </span>

      <table id="order-table" class="prT table table-striped" style="margin-bottom:0;" width="100%">
        <tbody>

        </tbody>
      </table>
    </div>
    <!-- End Print order for customer -->

    <!-- Print bill for customer -->
    <div id="bill_tbl">
      <span id="bill_span">

      </span>
      <table id="bill-table" width="100%" class="prT table table-striped" style="margin-bottom:0;">
        <tbody>

        </tbody>
      </table>
      <table id="bill-total-table" class="prT table" style="margin-bottom:0;" width="100%">
        <tbody>
          <tr class="bold">
            <td><?php echo display('total_cgst') ?></td>
            <td class="total_cgst_bill" style="text-align:right;">0</td>
          </tr>
          <tr class="bold">
            <td><?php echo display('total_sgst') ?></td>
            <td class="total_sgst_bill" style="text-align:right;">0</td>
          </tr>
          <tr class="bold">
            <td><?php echo display('total_igst') ?></td>
            <td class="total_igst_bill" style="text-align:right;">0</td>
          </tr>
          <tr class="bold">
            <td><?php echo display('total_discount') ?></td>
            <td class="total_discount_bill" style="text-align:right;">0</td>
          </tr>
          <tr class="bold">
            <td class=""><?php echo display('grand_total') ?></td>
            <td class="total_bill" style="text-align:right;">00</td>
          </tr>
          <tr class="bold">
            <td><?php echo display('items') ?></td>
            <td class="item_bill" style="text-align:right;">0</td>
          </tr>
        </tbody>
      </table>
      <span id="bill_footer">
        <p class="text-center"><br><?php echo display('merchant_copy') ?></p>
      </span>
    </div>
    <!-- End Print bill for customer -->


    <?php echo form_open('Cinvoice/insert_customer', array('class' => 'form-vertical', 'id' => 'validate')) ?>
    <div class="modal fade modal-warning" id="client-info" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title"><?php echo display('add_customer') ?></h3>
          </div>

          <div class="modal-body">
            <div class="form-group row">
              <label for="name" class="col-sm-3 col-form-label"><?php echo display('name') ?> <i class="text-danger">*</i></label>
              <div class="col-sm-6">
                <input class="form-control simple-control" name="customer_name" id="name" type="text" placeholder="<?php echo display('customer_name') ?>" required="">
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-sm-3 col-form-label"><?php echo display('email') ?></label>
              <div class="col-sm-6">
                <input class="form-control" name="email" id="email" type="email" placeholder="<?php echo display('customer_email') ?>">
              </div>
            </div>

            <div class="form-group row">
              <label for="mobile" class="col-sm-3 col-form-label"><?php echo display('mobile') ?></label>
              <div class="col-sm-6">
                <input class="form-control" name="mobile" id="mobile" type="number" placeholder="<?php echo display('customer_mobile') ?>" min="0">
              </div>
            </div>

            <div class="form-group row">
              <label for="address " class="col-sm-3 col-form-label"><?php echo display('address') ?></label>
              <div class="col-sm-6">
                <textarea class="form-control" name="address" id="address " rows="3" placeholder="<?php echo display('customer_address') ?>"></textarea>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo display('close') ?> </button>
            <button type="submit" class="btn btn-success"><?php echo display('submit') ?> </button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php echo form_close(); ?>

    <div class="modal fade modal-warning" id="myModal" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title"></h3>
          </div>
          <form id="updateCart" action="#" method="post">
            <div class="modal-body">
              <table class="table table-bordered table-striped">
                <tbody>
                  <tr>
                    <th style="width:25%;"><?php echo display('price') ?></th>
                    <th style="width:25%;"><span id="net_price" class="price"></span></th>
                  </tr>
                </tbody>
              </table>

              <div class="form-group row">
                <label for="available_quantity" class="col-sm-4 col-form-label"><?php echo display('available_quantity') ?></label>
                <div class="col-sm-8">
                  <input class="form-control" type="text" id="available_quantity" placeholder="<?php echo display('available_quantity') ?>" name="available_quantity" readonly="readonly">
                </div>
              </div>

              <div class="form-group row">
                <label for="unit" class="col-sm-4 col-form-label"><?php echo display('unit') ?></label>
                <div class="col-sm-8">
                  <input class="form-control" type="text" id="unit" placeholder="<?php echo display('unit') ?>" name="unit" readonly="readonly">
                </div>
              </div>
              <div class="form-group row">
                <label for="<?php echo display('quantity') ?>" class="col-sm-4 col-form-label"><?php echo display('quantity') ?> <span class="color-red">*</span></label>
                <div class="col-sm-8">
                  <input class="form-control" type="text" id="<?php echo display('quantity') ?>" name="quantity">
                </div>
              </div>
              <div class="form-group row">
                <label for="<?php echo display('rate') ?>" class="col-sm-4 col-form-label"><?php echo display('rate') ?> <span class="color-red">*</span></label>
                <div class="col-sm-8">
                  <input class="form-control" type="text" id="<?php echo display('rate') ?>" name="rate">
                </div>
              </div>
              <div class="form-group row">
                <label for="<?php echo display('discount') ?>" class="col-sm-4 col-form-label"><?php echo display('discount') ?></label>
                <div class="col-sm-8">
                  <input class="form-control" type="text" id="<?php echo display('discount') ?>" placeholder="<?php echo display('discount') ?>" name="discount">
                </div>
              </div>
              <input type="hidden" name="rowID">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo display('close') ?></button>
              <button type="submit" class="btn btn-success"><?php echo display('save_changes') ?></button>
            </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="row">


      <div class="col-sm-7">
        <div class="panel panel-bd">
          <div class="panel-body">
            <div class="form-group">
              <div class="input-group">
                <input type="text" name="product_name" class="form-control" placeholder='<?php echo display('barcode_qrcode_scan_here') ?>' id="add_item">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" id="qrReader" data-toggle="modal" data-target="#qrmodal">
                    <span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span>
                  </button>
                </span>
              </div>
            </div>

            <?php echo form_open_multipart('Cinvoice/insert_invoice2', array('class' => 'form-vertical form_submit', 'id' => 'validatex')) ?>
            <div class="client-add">
              <input type="hidden" name="pos" value="pos">
              <div class="form-group">
                <label for="customer_name"><?php echo display('customer_name') ?> <span class="color-red">*</span></label>
                <select id="customer_name" class="form-control" name="customer_id" required="">
                  <?php
                  if ($customer_details) {
                    foreach ($customer_details as $customer) {
                  ?>
                      <option value="<?php echo $customer->customer_id ?>"><?php echo $customer->customer_name ?></option>
                  <?php
                    }
                  }
                  ?>
                  <optgroup label="Others">
                    <?php
                    if ($customer_list) {
                      foreach ($customer_list as $customer_det) {
                        if (1 == $customer_det->customer_id) {
                    ?>
                          <option selected value="<?php echo $customer_det->customer_id ?>"><?php echo $customer_det->customer_name ?></option>
                        <?php } else {
                        ?>
                          <option value="<?php echo $customer_det->customer_id ?>"><?php echo $customer_det->customer_name ?></option>
                    <?php
                        }
                      }
                    }
                    ?>
                  </optgroup>
                </select>
              </div>
              <a href="#" class="client-add-btn" aria-hidden="true" data-toggle="modal" data-target="#client-info"><i class="ti-plus m-r-2"></i><?php echo display('new_customer') ?> </a>
            </div>

            <div class="form-group">
              <label for="store_id"><?php echo display('store_name') ?> <span class="color-red">*</span></label>
              <select id="store_id" class="form-control" name="store_id" required="">
                <option value=""></option>
                <?php

                if ($store_list) {
                  foreach ($store_list as $store) :
                    if (1 == @$store->default_status) {
                ?>
                      <option selected value="<?php echo $store->store_id ?>"><?php echo $store->store_name ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $store->store_id ?>"><?php echo $store->store_name ?></option>
                <?php
                    }
                  endforeach;
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <?php date_default_timezone_set("Asia/Jakarta");
              $date = date('m-d-Y'); ?>
              <input class="form-control" type="hidden" id="invoice_date" name="invoice_date" required value="<?php echo $date; ?>" />
              <input type="hidden" id="product_value" name="">
            </div>

            <div class="product-list">
              <div class="table-responsive">
                <table class="table table-bordered" border="1" width="100%" id="addinvoice">
                  <thead>
                    <tr>
                      <th><?php echo display('item') ?></th>
                      <th style='display: none !important;'>Variant</th>
                      <th><?php echo display('available_quantity') ?></th>
                      <th><?php echo display('price') ?></th>
                      <th><?php echo display('quantity') ?></th>
                      <th><?php echo display('total') ?></th>
                      <th><?php echo display('action') ?></th>
                    </tr>
                  </thead>
                  <tbody class="itemNumber">

                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>

      </div>
      <div class="col-sm-5">
        <div class="navbar-search">
          <label class="sr-only screen-reader-text" for="search"><?php echo display('search') ?>:</label>
          <div class="input-group">
            <input type="text" id="product_name" class="form-control search-field" dir="ltr" value="" name="s" placeholder="<?php echo display('search_by_product') ?>" />

            <div class="input-group-addon search-categories">
              <select name='category_id' id='category_id' class='postform resizeselect form-control'>
                <option value='0' selected='selected'>All Categories</option>
                <?php
                if ($category_list) {
                  foreach ($category_list as $category) {
                ?>
                    <option value="<?php echo $category->category_id ?>"><?php echo $category->category_name ?></option>
                <?php
                  }
                }
                ?>
              </select>
            </div>
            <div class="input-group-btn">
              <button type="button" class="btn -btn-secondary color4" id="search_button"><i class="ti-search"></i></button>
            </div>
          </div>
        </div>
        <div class="product-grid">
          <div class="row row-m-3" id="product_search">
            <?php
            if ($product_list) {
              foreach ($product_list as $product) {
            ?>
                <div class="col-xs-6 col-sm-4 col-md-4 col-p-3">
                  <div class="panel panel-bd product-panel select_product">
                    <div class="panel-body">
                      <img src="<?php echo base_url() . $product->image_thumb ?>" class="img-responsive" alt="">
                      <input type="hidden" name="select_product_id" class="select_product_id" value="<?php echo $product->product_id ?>">
                    </div>
                    <div class="panel-footer"><?php echo $product->product_name . '-(' . $product->product_model . ')' ?></div>
                  </div>
                </div>
            <?php
              }
            }
            ?>
          </div>
        </div>

        <div class="table-responsive total-price">
          <table class="table">
            <tbody>
              <?php
              //Tax basic info
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
              ?>
              <tr style="display:none">
                <th scope="row"><?php echo display('item') ?></th>
                <td id="item-number">0</td>
                <?php if ($tax['cgst_status'] == 1) { ?>
                  <th><?php echo $tax['cgst_name'] ?></th>
                  <td><input type="text" id="total_cgst" class="form-control text-right" name="total_cgst" value="0.00" readonly="readonly" /></td>
                <?php } ?>
              </tr>
              <tr style="display:none">
                <?php if ($tax['sgst_status'] == 1) { ?>
                  <th scope="row"><?php echo $tax['sgst_name'] ?></th>
                  <td><input type="text" id="total_sgst" class="form-control text-right" name="total_sgst" value="0.00" readonly="readonly" /></td>
                <?php }
                if ($tax['igst_status'] == 1) { ?>
                  <th><?php echo $tax['igst_name'] ?></th>
                  <td><input type="text" id="total_igst" class="form-control text-right" name="total_igst" value="0.00" readonly="readonly" /></td>
                <?php } ?>
              </tr>
              <tr>
                <th scope="row"><?php echo display('total_discount') ?></th>
                <td><input type="text" id="total_discount_ammount" class="form-control text-right" name="total_discount" placeholder="0.00" readonly="readonly" /></td>

                <th><?php echo display('invoice_discount') ?></th>
                <td><input type="text" id="invoice_discount" class="form-control text-right" name="invoice_discount" placeholder="0.00" onkeyup="calculateSum();" onchange="calculateSum();" /></td>
              </tr>
              <tr>
                <th scope="row"><?php echo display('service_charge') ?></th>
                <td>
                  <input type="text" id="service_charge" onkeyup="calculateSum();" class="form-control text-right" name="service_charge" placeholder="0.00" />
                </td>

                <th><?php echo display('grand_total') ?></th>
                <td><input type="text" id="grandTotal" class="form-control text-right" name="grand_total_price" placeholder="0.00" readonly="readonly" /></td>
              </tr>

              <tr>
                <th scope="row"><?php echo display('paid_ammount') ?></th>
                <td>
                  <input type="text" id="paidAmount" onkeyup="invoice_paidamount();" class="form-control text-right" name="paid_amount" placeholder="0.00" />
                </td>

                <th><?php echo display('due') ?></th>
                <td><input type="text" id="dueAmmount" class="form-control text-right" name="due_amount" placeholder="0.00" readonly="readonly" /></td>
              </tr>

              <tr>
                <th>Total Qty of All Products</th>
                <td><input type="text" id="total_qty_product" class="form-control text-right" name="total_qty_product" value="0" placeholder="0" readonly="readonly" /></td>
              </tr>

              <!-- Payment method -->
              <tr class="payment_method" style="display: none">
                <td colspan="7">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group row">
                        <label for="card_type" class="col-sm-4 col-form-label"><?php echo display('card_type') ?>
                          : </label>
                        <div class="col-sm-8">
                          <select class="form-control" name="card_type" id="card_type">
                            <option value="Cash">Cash</option>
                            <option value="Credit Card"><?php echo display('credit_card') ?></option>
                            <option value="Debit Card"><?php echo display('debit_card') ?></option>
                            <option value="QRIS">QRIS</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group row">
                        <label for="card_no" class="col-sm-4 col-form-label"><?php echo display('card_no') ?>
                          :</label>
                        <div class="col-sm-8">
                          <input class="form-control" type="text" name="card_no" id="card_no" placeholder="<?php echo display('card_no') ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
        </div>


        <div class="panel-footer">
          <input class="btn btn-warning  m-b-5" value="<?php echo display('full_paid') ?>" onclick="full_paid();" type="button">
          <button type="button" class="btn -btn-purple color4 color5 m-b-5 payment_button"><?php echo display('payment') ?></button>
          <a href="<?php echo base_url('Cinvoice/manage_invoice') ?>" type="button" class="btn btn-danger m-b-5"><?php echo display('cancel') ?></a>
          <button type="submit" class="btn btn-success m-b-5"><?php echo display('submit') ?></button>
        </div>

      </div>

      <?php echo form_close(); ?>

    </div>
  </div>
  <!-- /.content -->
</div>
<!-- POS Invoice Report End -->

<!-- Modal -->
<div class="modal fade" id="qrmodal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="qr-reader"></div>
        <div id="qr-reader-results"></div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="return_modal" tabindex="-1" role="dialog" aria-labelledby="return_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="text-center" style="font-size: 42px;font-weight: bolder;margin-bottom: 15px;">
          RP. <span id="kembalian">00000000000</span>
        </div>
        <div class="row">
          <div class="col-md-6" style="margin-left:auto">
            <button id="printID" class="btn btn-success btn-block">Print</button>
          </div>
          <div class="col-md-6" style="margin-right:auto">
            <button id="shareID" class="btn btn-success btn-block">Share</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url() ?>assets/qz/rsvp-3.1.0.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/qz/sha-256.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/qz/qz-tray.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/html5-qrcode.min.js" type="text/javascript"></script>
<script src="<?= base_url("assets/sweetalert2/dist/sweetalert2.min.js") ?>"></script>

<script src="<?= base_url() ?>assets/js/html2canvas.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/print/print.min.js" type="text/javascript"></script>

<!-- Modal ajax call start -->
<script type="text/javascript">
  //Delete a pos row from POS table
  function deletePosAllRow() {
    $('#item-number').html('0');
    $(".itemNumber tr").remove();
    $("#paidAmount").val(0);
    $("#total_qty_product").val(0);
    calculateSum();
    invoice_paidamount();
  }

  function get_productr(product_id) {
    var today = new Date();
    var date = (today.getMonth() + 1) + '-' + today.getDate() + '-' + today.getFullYear();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var dateTime = date + ' ' + time;
    var user_name = '<?php echo $this->session->userdata('user_name'); ?>';
    var store_id = $('#store_id').val();
    var new_product_code = $("#add_item").val();
    $.ajax({
      type: "post",
      dataType: "json",
      async: false,
      url: '<?php echo base_url('Cinvoice/insert_pos_invoice') ?>',
      data: {
        product_id: new_product_code,
        store_id: store_id
      },
      success: function(data) {
        if (data.item == 0) {
          alert('Product not available in stock !');
          document.getElementById('add_item').value = '';
          document.getElementById('add_item').focus();
        } else {
          document.getElementById('add_item').value = '';
          document.getElementById('add_item').focus();
          $('#addinvoice tbody').append(data.item);
          $('#order-table tbody').append(data.order);
          $('#bill-table tbody').append(data.bill);

          $("#order_span").empty();
          $("#bill_span").empty();
          var styles = '<style>table, th, td { border-collapse:collapse; border-bottom: 1px solid #CCC; } .no-border { border: 0; } .bold { font-weight: bold; }</style>';

          var pos_head1 = '<span style="text-align:center;"><h3><?php echo $company_name ?></h3><h4>';
          var pos_head2 = '</h4><p class="text-left">C: ' + $('#select2-customer_name-container').text() + '<br>U: ' + user_name + '<br>T: ' + dateTime + '</p></span>';

          $("#order_span").prepend(styles + pos_head1 + 'Order' + pos_head2);

          $("#bill_span").prepend(styles + pos_head1 + 'Bill' + pos_head2);


          var addSerialNumber = function() {
            var i = 1;
            $('#order-table tbody tr').each(function(index) {
              $(this).find('td:nth-child(1)').html('#' + (index + 1));
            });
            $('#bill-table tbody tr').each(function(index) {
              $(this).find('td:nth-child(1)').html('#' + (index + 1));
            });
          };
          addSerialNumber();

          quantity_calculate(data.product_id);
          $("#variant_id_" + data.product_id).val("E94VKEM7TPI82EA").trigger('change');;
        }

        //Total items count
        $('#item-number').html('0');
        $(".itemNumber>tr").each(function(i) {
          $('#item-number').html(i + 1);
          $('.item_bill').html(i + 1);
        });

        $('#item-number').html('0');
        $(".itemNumber>tr").each(function(i) {
          $('#item-number').html(i + 1);
        });

      },
      error: function() {
        alert('Request Failed, Please check your code and try again!');
      }
    });
  }

  var resultContainer = document.getElementById('qr-reader-results');
  var lastResult, countResults = 0;

  function onScanSuccess(decodedText, decodedResult) {
    if (decodedText !== lastResult) {
      ++countResults;
      lastResult = decodedText;
      // Handle on success condition with the decoded message.
      console.log(`Scan result ${decodedText}`, decodedResult);
    }
    get_productr(decodedText);
  }

  var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
    fps: 10,
    qrbox: 250
  });
  html5QrcodeScanner.render(onScanSuccess);

  //INITIAL PRINTTTTTTTT!!!!!!!
  const $device = new RegExp(/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/, "i").test(navigator.userAgent.toLowerCase());
  var $isMobile = $device || window.matchMedia("only screen and (max-width: 760px)");
  var message;

  var forceDownload = function($href) {
    var $link = document.createElement('a');
    $link.download = 'roll' + (Math.random() * (9999 - 1111) + 1111) + '.png';
    $link.href = $href;
    $link.click();
  }
  var forcePrint = function($href) {
    var $link = document.createElement('a');
    $link.href = "rawbt:" + $href;
    $link.click();
  }
  var $jancuk;

  function captureUrl(id) {
    var $iframe = document.createElement('iframe');
    $iframe.id = "iframedownload";
    $iframe.src = '<?= base_url('Cinvoice/pos_invoice_inserted_data_redirect/') ?>' + id;

    var timeriframe = setInterval(function() {
      iframe = document.getElementById('iframedownload');
      var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
      // Check if loading is complete
      if (iframeDoc.readyState == 'complete' || iframeDoc.readyState == 'interactive') {
        console.log(iframe.contentDocument.getElementById("main"));
        html2canvas(iframe.contentDocument.getElementById("main"), {
          scale: 10
        }).then(function($canvas) {
          document.body.removeChild(iframe);
          if ($device) {
            forcePrint($canvas.toDataURL());
          } else printJS({
            printable: $canvas.toDataURL(),
            type: 'image',
            base64: true
          });
        });

        clearInterval(timeriframe);
        return;
      }
    }, 500);

    // just to hide the iframe
    $iframe.style.cssText = 'position: absolute; opacity:0; z-index: -9999';
    document.body.appendChild($iframe);
  }
  qz.security.setCertificatePromise(function(resolve, reject) {
    $.ajax({
      url: "<?= base_url("assets/signing/override.crt") ?>",
      cache: false,
      dataType: "text"
    }).then(resolve, reject);
  });
  qz.security.setSignaturePromise(function(toSign) {
    return function(resolve, reject) {
      $.post("<?= base_url("Cinvoice/signing") ?>", {
        request: toSign
      }).then(resolve, reject);
    };
  });

  function connectAndPrint(id) {
    captureUrl(id);
  }

  function connect() {
    return new RSVP.Promise(function(resolve, reject) {
      if (qz.websocket.isActive()) {
        resolve();
      } else {
        qz.websocket.connect().then(resolve, function retry() {
          window.location.assign("qz:launch");
          qz.websocket.connect({
            retries: 2,
            delay: 1
          }).then(resolve, reject);
        });
      }
    });
  }

  function printArea(id) {
    return [{
      type: 'pixel',
      format: 'html',
      flavor: 'file',
      data: '<?= base_url('Cinvoice/pos_invoice_inserted_data_redirect/') ?>' + id,
      options: {
        language: "ESCPOS"
      }
    }];
  }

  function print(id) {
    var config = qz.configs.create(null);
    return qz.printers.getDefault().then(function(data) {
      config.setPrinter(data);
      config.reconfigure({
        colorType: 'blackwhite',
        interpolation: "nearest-neighbor",
        margin: [0, 0, 0, 0],
        size: {
          width: 83
        },
        units: 'mm',
        rasterize: "false",
        density: 144,
      });
      return qz.print(config, printArea(id));
    });
  }

  function numberWithdot(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  //check variant selected or not
  $("#validatex").on("submit", function(e) {
    // console.log("Checking")
    // console.log($(this).serialize())
    // //return false;
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      dataType: "json",
      async: false,
      success: function(data) {
        $("#kembalian").text(numberWithdot($("#dueAmmount").val()));
        $("#printID").attr("data-id", data["invoice_id"]);
        $("#shareID").attr("data-id", data["invoice_id"]);
        deletePosAllRow();

        $("#total_discount_ammount").val(0);
        $("#invoice_discount").val(0);
        $("#service_charge").val(0);
        $("#grandTotal").val(0);
        $("#paidAmount").val(0);
        $("#dueAmmount").val(0);
        $("#total_qty_product").val(0);
        $("#card_no").val('');
        document.getElementById("card_type").selectedIndex = 0;
        $(".payment_method").toggle();

        $("#return_modal").modal("show");
      }
    });
  });
  $(document).on("click", ":submit", function(e) {
    var variant_value = $('.variant_id').val();y
    if (variant_value === 'Select Variant') {
      alert('<?php echo display('select_variant') ?>');
      return false;
    } else {
      return true;
    }

  });

  $("#printID").click((e) => {
    e.preventDefault();
    connectAndPrint($(e.target).attr("data-id"));
  });

  async function shareCanvas(dataUrl) {
    const blob = await (await fetch(dataUrl)).blob();
    const filesArray = [
      new File(
        [blob],
        'animation.png', {
          type: blob.type,
          lastModified: new Date().getTime()
        }
      )
    ];
    const shareData = {
      files: filesArray,
    };
    navigator.share(shareData);
  }

  $("#shareID").click((e) => {
    e.preventDefault();
    var $iframe = document.createElement('iframe');
    $iframe.id = "iframedownloadZZZ";
    $iframe.src = '<?= base_url('Cinvoice/pos_invoice_inserted_data_redirect/') ?>' + $(e.target).attr("data-id");
    var timeriframe = setInterval(function() {
      iframe = document.getElementById('iframedownloadZZZ');
      var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
      // Check if loading is complete
      if (iframeDoc.readyState == 'complete' || iframeDoc.readyState == 'interactive') {
        html2canvas(iframe.contentDocument.getElementById("main")).then(function($canvas) {
          shareCanvas($canvas.toDataURL());
          document.body.removeChild(iframe);
        });
        clearInterval(timeriframe);
        return;
      }
    }, 500);
    $iframe.style.cssText = 'position: absolute; opacity:0; z-index: -9999';
    document.body.appendChild($iframe);
  });
  // ============================================

  $('#myModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    var rowID = button.parent().parent().attr('id');
    var product_name = $("#product_name_" + rowID).text();
    var rate = $("#price_item_" + rowID).val();
    var quantity = $("#total_qntt_" + rowID).val();
    var available_quantity = $(".available_quantity_" + rowID).val();
    var unit = $(".unit_" + rowID).val();
    var discount = $("#discount_" + rowID).val();

    modal.find('.modal-title').text(product_name);
    modal.find('.modal-body input[name=rowID]').val(rowID);
    modal.find('.modal-body input[name=product_name]').val(product_name);
    modal.find('.modal-body input[name=rate]').val(rate);
    modal.find('.price').text(rate);
    modal.find('.modal-body input[name=quantity]').val(quantity);
    modal.find('.modal-body input[name=available_quantity]').val(available_quantity);
    modal.find('.modal-body input[name=unit]').val(unit);
    modal.find('.modal-body input[name=discount]').val(discount);
  });

  //Update POS cart
  $('#updateCart').on('submit', function(e) {
    e.preventDefault();
    var rate = $(this).find('input[name=rate]').val();
    var quantity = $(this).find('input[name=quantity]').val();
    var discount = $(this).find('input[name=discount]').val();
    var rowID = $(this).find('input[name=rowID]').val();

    $("#price_item_" + rowID).val(rate);
    $("#total_qntt_" + rowID).val(quantity);
    $("#total_price_" + rowID).val(quantity * rate);
    $("#discount_" + rowID).val(discount);
    $("#total_discount_" + rowID).val(discount);
    $("#all_discount_" + rowID).val(discount * quantity);

    quantity_calculate(rowID);

    $('#myModal').modal('hide');
  });
</script>
<!-- Modal ajax call start -->

<script type="text/javascript">
  //Onload filed select
  window.onload = function() {
    var text_input = document.getElementById('add_item');
    text_input.focus();
    text_input.select();
  };
  get_productr
  var barcodeScannerTimer;
  var barcodeString = '';

  //POS Invoice js
  $('#add_item').on('keypress', function(e) {
    //e.preventDefault();
    barcodeString = barcodeString + String.fromCharCode(e.charCode);
    if (e.charCode == 13) {
      barcodeString = barcodeString.trim();
      processBarcode();
    }
  });

  function processBarcode() {

    if (!isNaN(barcodeString) && barcodeString != '') {
      var product_id = barcodeString.trim();

      var today = new Date();
      var date = (today.getMonth() + 1) + '-' + today.getDate() + '-' + today.getFullYear();
      var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
      var dateTime = date + ' ' + time;
      var user_name = '<?php echo $this->session->userdata('user_name'); ?>';

      if (product_id) {
        get_productr(product_id);
      }
    }
    barcodeString = ''; // reset
  };

  //Product search js
  $('body').on('keyup', '#product_name', function() {
    var product_name = $(this).val();
    var category_id = $('#category_id').val();
    $.ajax({
      type: "post",
      async: false,
      url: '<?php echo base_url('Cinvoice/search_product') ?>',
      data: {
        product_name: product_name,
        category_id: category_id
      },
      success: function(data) {
        if (data == '420') {
          $("#product_search").html('Product not found !');
        } else {
          $("#product_search").html(data);
        }
      },
      error: function() {
        alert('Request Failed, Please check your code and try again!');
      }
    });
  });

  //Product search js
  $('body').on('change', '#category_id', function() {
    var product_name = $('#product_name').val();
    var category_id = $('#category_id').val();
    $.ajax({
      type: "post",
      async: false,
      url: '<?php echo base_url('Cinvoice/search_product') ?>',
      data: {
        product_name: product_name,
        category_id: category_id
      },
      success: function(data) {
        if (data == '420') {
          $("#product_search").html('Product not found !');
        } else {
          $("#product_search").html(data);
        }
      },
      error: function() {
        alert('Request Failed, Please check your code and try again!');
      }
    });
  });

  //Product search button js
  $('body').on('click', '#search_button', function() {
    var product_name = $('#product_name').val();
    var category_id = $('#category_id').val();
    $.ajax({
      type: "post",
      async: false,
      url: '<?php echo base_url('Cinvoice/search_product') ?>',
      data: {
        product_name: product_name,
        category_id: category_id
      },
      success: function(data) {
        if (data == '420') {
          $("#product_search").html('Product not found !');
        } else {
          $("#product_search").html(data);
        }
      },
      error: function() {
        alert('Request Failed, Please check your code and try again!');
      }
    });
  });


  //Product search button js
  $('body').on('click', '.select_product', function(e) {
    e.preventDefault();

    var today = new Date();
    var date = (today.getMonth() + 1) + '-' + today.getDate() + '-' + today.getFullYear();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var dateTime = date + ' ' + time;
    var user_name = '<?php echo $this->session->userdata('user_name'); ?>';
    var store_id = $('#store_id').val();
    var panel = $(this);
    var product_id = panel.find('.panel-body input[name=select_product_id]').val();

    $.ajax({
      type: "post",
      dataType: "json",
      async: false,
      url: '<?php echo base_url('Cinvoice/insert_pos_invoice') ?>',
      data: {
        product_id: product_id,
        store_id: store_id
      },
      success: function(data) {

        if (data.item == 0) {
          alert('Product not available in stock !');
          document.getElementById('add_item').value = '';
          //document.getElementById('add_item').focus();
        } else {
          document.getElementById('add_item').value = '';
          //document.getElementById('add_item').focus();
          $('#addinvoice tbody').append(data.item);
          $('#order-table tbody').append(data.order);
          $('#bill-table tbody').append(data.bill);

          $("#order_span").empty();
          $("#bill_span").empty();
          var styles = '<style>table, th, td { border-collapse:collapse; border-bottom: 1px solid #CCC; } .no-border { border: 0; } .bold { font-weight: bold; }</style>';

          var pos_head1 = '<span style="text-align:center;"><h3><?php echo $company_name ?></h3><h4>';
          var pos_head2 = '</h4><p class="text-left">C: ' + $('#select2-customer_name-container').text() + '<br>U: ' + user_name + '<br>T: ' + dateTime + '</p></span>';

          $("#order_span").prepend(styles + pos_head1 + 'Order' + pos_head2);
          $("#bill_span").prepend(styles + pos_head1 + 'Bill' + pos_head2);

          var addSerialNumber = function() {
            var i = 1;
            $('#order-table tbody tr').each(function(index) {
              $(this).find('td:nth-child(1)').html('#' + (index + 1));
            });
            $('#bill-table tbody tr').each(function(index) {
              $(this).find('td:nth-child(1)').html('#' + (index + 1));
            });
          };
          addSerialNumber();

          quantity_calculate(data.product_id);
          $("#variant_id_" + data.product_id).val("E94VKEM7TPI82EA").trigger('change');
        }

        //Total items count
        $('#item-number').html('0');
        $(".itemNumber>tr").each(function(i) {
          $('#item-number').html(i + 1);
          $('.item_bill').html(i + 1);
        });

      },
      error: function() {
        alert('Request Failed, Please check your code and try again!');
      }
    });
  });


  //Select stock by product and variant id
  $('body').on('change', '.variant_id', function() {

    var sl = $(this).parent().parent().find(".sl").val();
    var product_id = $('.product_id_' + sl).val();
    var avl_qntt = $('.available_quantity_' + sl).val();
    var store_id = $('#store_id').val();
    var variant_id = $(this).val();

    if (store_id == 0) {
      alert('Please select store !');
      return false;
    }
    $.ajax({
      type: "post",
      async: false,
      url: '<?php echo base_url('Cpurchase/available_stock') ?>',
      data: {
        product_id: product_id,
        variant_id: variant_id,
        store_id: store_id
      },
      success: function(data) {
        if (data == 0) {
          $('.available_quantity_' + sl).val('');
          alert('Product is not available in stock !');
          return false;
        } else {
          $('.available_quantity_' + sl).val(data);
        }
      },
      error: function() {
        alert('Request Failed, Please check your code and try again!');
      }
    });
  });
</script>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Barcode print js -->
<script type="text/javascript">
  function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    w = window.open();

    w.document.write(printContents);
    w.document.write('<scr' + 'ipt type="text/javascript">' + 'window.onload = function() { window.print(); window.close(); };' + '</sc' + 'ript>');

    w.document.close(); // necessary for IE >= 10
    w.focus(); // necessary for IE >= 10

    return true;
  }
</script>
<style type="text/css" media="print">
  #printableArea {
    margin: 0px !important;
  }
</style>
<!-- Barcode list start -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="header-icon">
      <i class="pe-7s-note2"></i>
    </div>
    <div class="header-title">
      <h1><?php if (empty($qr_image)) {
            echo display('barcode');
          } else {
            echo display('qr_code');
          } ?></h1>
      <small><?php if (empty($qr_image)) {
                echo display('barcode');
              } else {
                echo display('qr_code');
              } ?></small>
      <ol class="breadcrumb">
        <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
        <li><a href="#"><?php echo display('product') ?></a></li>
        <li class="active"><?php if (empty($qr_image)) {
                              echo display('barcode');
                            } else {
                              echo display('qr_code');
                            } ?></li>
      </ol>
    </div>
  </section>

  <section class="content">
    <!-- Product Barcode and QR code -->
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
          <div class="panel-heading">
            <div class="panel-title">
              <h4><?php if (empty($qr_image)) {
                    echo display('barcode');
                  } else {
                    echo display('qr_code');
                  } ?> </h4>
            </div>
          </div>
          <?php echo form_open_multipart('Cproduct/insert_product') ?>
          <div class="panel-body">

            <?php
            if (!empty($product_id) || !empty($qr_image)) {
            ?>
              <div style="float: center">
                <a class="btn btn-info" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                <a class="btn btn-danger" href="<?php echo base_url('Cproduct/manage_product'); ?>"><?php echo display('cancel') ?></a>
              </div>
            <?php
            }
            ?>
            <div class="table-responsive" style="margin-top: 10px">
              <?php
              if (isset($product_id)) {
              ?>
                <!-- barcode -->
                <div id="printableArea">
                  <table id="" class="table table-bordered " style=" border-collapse: collapse;">
                    <?php
                    $counter = 0;
                    $isFirst = true;
                    $isSecondRow = false;
                    for ($i = 0; $i < 60; $i++) {
                    ?>

                    <?php
                    
                    if ($i == 2) {
                      $isSecondRow = true;
                    } else {
                      $isSecondRow = false;
                    }
                    
                    ?>
                      <?php if ($counter == 2) { ?>
                        <?php if ($isSecondRow == true) { ?>
                          <tr>
                            <td style="border-bottom: 0px solid black;">
                              <p style="margin-top: 1mm !important;opacity: 0;">xx</p>
                            </td>
                            </td>
                            <td>
                              <p style="margin-top: 1mm !important;opacity: 0;">xx</p>
                            </td>
                            </td>
                          </tr>

                        <?php } ?>


                        <?php if ($isSecondRow == false && $isFirst == false) { ?>
                          <tr>
                            <td style="border-bottom: 0px solid black;">
                              <p style="margin-bottom: 4mm;margin-top: 2mm;opacity: 0;">XXX2</p>
                            </td>
                            </td>
                            <td>
                              <p style="margin-bottom: 4mm;margin-top: 2mm;opacity: 0;">XXX2</p>
                            </td>
                            </td>
                          </tr>

                        <?php } ?>

                        <tr>
                          <?php $counter = 0; ?>
                        <?php } ?>

                        <?php if ($i % 2 != 0) { ?>
                          <!-- ganjil -->
                          <td style="border-left: 0px solid black; border-right: 0px solid black;">
                            <p style="opacity: 0;">XX</p>
                          </td>
                        <?php } ?>

                        <td style="border: 0px solid black; margin:unset !important;padding:unset !important;">

                          <div class="barcode-inner" style="font-family: 'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;text-align: center; position: relative;">
                            <div class="product-name" style="text-transform: uppercase;line-height: 10px;font-weight: 600;font-size: 7px;">
                              {company_name}
                            </div>
                            <!-- <span class="model-name" style="font-weight: 600;
													font-size: 8px;
													top: 0;
													right: 0;">{product_model}</span> -->
                            <div class="product-name" style="text-transform: uppercase;line-height: 10px;font-weight: 600;font-size: 7px;">
                              {product_model}
                            </div>
                            <img src="<?php echo base_url('Cbarcode/barcode_generator/{product_id}') ?>" class="img-responsive center-block" alt="" style="display: block;margin-left: auto;margin-right: auto;height: 30px;width: 140px;">
                            <div class="product-name-details" style="font-size: 7px;font-weight: 600;text-transform: uppercase;line-height: 8px;">{product_name}</div>
                            <div class="price" style="font-weight: 500;line-height: 10px;font-size: 10px;margin-bottom: 0px;"><?php echo (($position == 0) ? "$currency {price}" : "{price} $currency") ?>

                            </div>
                          </div>

                        </td>

                        <?php if ($counter == 5) { ?>
                        </tr>
                        <?php $counter = 0; ?>
                      <?php } ?>
                      <?php
                        $isFirst = false;
                        ?>
                      <?php $counter++; ?>
                    <?php
                    }
                    ?>
                  </table>
                </div>
              <?php
              } else {
              ?>
                <!-- qr code -->
                <div id="printableArea">
                  <table class="table table-bordered" style=" border-collapse: collapse;">
                    <?php
                    $counter = 0;
                    for ($i = 0; $i < 30; $i++) {
                    ?>
                      <?php if ($counter == 2) { ?>
                        <tr>
                          <?php $counter = 0; ?>
                        <?php } ?>
                        <td style="border: 0px solid black ;padding: 5px">
                          <div class="barcode-inner" style="font-family: 'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;text-align: center; position: relative;">
                            <div class="product-name" style="text-transform: uppercase;line-height: 10px;font-weight: 600;font-size: 12px;margin-bottom: 3px;">
                              {company_name}
                            </div>
                            <div class="product-name" style="text-transform: uppercase;line-height: 10px;font-weight: 600;font-size: 12px;margin-top: 7px;">
                              {product_model}
                            </div>
                            <!-- <span class="model-name" style="font-weight: 600;
												font-size: 8px;
												position: absolute;
												top: 0;
												right: 0;">{product_model}</span> -->
                            <img src="<?php echo base_url('my-assets/image/qr/{qr_image}') ?>" class="img-responsive center-block" alt="" style="display: block;margin-left: auto;margin-right: auto;height:150px">
                            <div class="product-name-details" style="font-size: 11px;letter-spacing: 0.5px;font-weight: 600;text-transform: uppercase;line-height: 8px;">{product_name}</div>
                            <div class="price" style="font-weight: 500;line-height: 10px;margin-top: 7px;"><?php echo (($position == 0) ? "$currency {price}" : "{price} $currency") ?>
                            </div>
                          </div>
                        </td>
                        <?php if ($counter == 5) { ?>
                        </tr>
                        <?php $counter = 0; ?>
                      <?php } ?>
                      <?php $counter++; ?>
                    <?php
                    }
                    ?>
                  </table>
                </div>
              <?php
              }
              ?>
            </div>
          </div>
          <?php echo form_close() ?>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- Barcode list End -->
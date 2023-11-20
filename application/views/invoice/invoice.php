<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Manage Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('manage_invoice') ?></h1>
            <small><?php echo display('manage_your_invoice') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('manage_invoice') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Alert Message -->
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
        ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $message ?>
            </div>
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

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <a href="<?php echo base_url('Cinvoice/new_invoice') ?>" class="btn -btn-info color4 color5 m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('new_invoice') ?></a>
                    <a href="<?php echo base_url('Cinvoice/pos_invoice') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('pos_invoice') ?></a>
                    <br><br>
                    <?php date_default_timezone_set("Asia/Jakarta"); $today = date('m-d-Y'); ?>
                    <form action="<?php echo base_url('Cinvoice/export_invoice') ?>" method="post">
                    <div class="form-group">
                              <label class="" for="from_date"><?php echo display('start_date') ?></label>
                              <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" autocomplete="off">
                          </div>

                          <div class="form-group">
                              <label class="" for="to_date"><?php echo display('end_date') ?></label>
                              <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $today?>" autocomplete="off">
                          </div>
                          <select name="trx_status" id="trx_status">
                        <option value="all">All</option>
                        <option value="1">Shipped</option>
                        <option value="2">Cancel</option>
                        <option value="3">Pending</option>
                        <option value="4">Complete</option>
                        <option value="5">Processing</option>
                        <option value="6">Return</option>
                    </select>
                    <button type="submit" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> Export</button>
                    </form><br>


                </div>
            </div>
        </div>


        <!-- Manage Invoice reportx -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_invoice') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTableExampleMngInv" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl') ?></th>
                                        <th><?php echo display('invoice_no') ?></th>
                                        <th><?php echo display('customer_name') ?></th>
                                        <th><?php echo display('date') ?></th>
                                        <th><?php echo display('total_amount') ?></th>
                                        <th style="width: 25%"><?php echo display('status') ?></th>
                                        <th><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Manage Invoice End -->
<script>
    // $('#trx_status').on('change', function() {
    //     alert( this.value );
    // });
    // location.href = "/content_creator/news/preview/"+id;
</script>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Admin Home Start -->
<div class="content-wrapper color3">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-world"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('dashboard') ?></h1>
            <small><?php echo display('home') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li class="active"><?php echo display('dashboard') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
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
        <!-- Sales -->
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <h2><span class="count-number"><?php echo $sales_amount; ?></span> <span class="slight"><i
                                            class="fa fa-play fa-rotate-270 text-warning"> </i></span></h2>
                            <div class="small todays_report"><?php echo display('todays_report') ?></div>
                            <div class="small"><?php echo display('total_sales') ?> (<?php echo $currency; ?>)</div>
                            <div class="sparkline1 text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($this->auth->is_admin()) { ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="count-number"><?php echo $purchase_amount; ?></span> <span
                                            class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i></span>
                                </h2>
                                <div class="small todays_report"><?php echo display('todays_report') ?></div>
                                <div class="small"><?php echo display('total_purchase') ?> (<?php echo $currency; ?>)</div>
                                <div class="sparkline1 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="count-number"><?php echo $total_suppliers; ?></span> <span
                                            class="slight"><i
                                                class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2>
                                <div class="small todays_report"><?php echo display('todays_report') ?></div>
                                <div class="small"><?php echo display('discount_amount') ?> (<?php echo $currency; ?>)</div>
                                <div class="sparkline1 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($this->session->userdata('user_type') == '4') { ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="count-number"><?php echo $total_store_invoice; ?></span><span
                                            class="slight"> <i
                                                class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2>
                                <div class="small"><?php echo display('total_invoice') ?></div>
                                <div class="sparkline1 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        
        <!-- First Counter -->
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <h2><span class="count-number"><?php echo $total_customer; ?></span> <span class="slight"><i
                                            class="fa fa-play fa-rotate-270 text-warning"> </i></span></h2>
                            <div class="small"><?php echo display('total_customer') ?></div>
                            <div class="sparkline1 text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($this->auth->is_admin()) { ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="count-number"><?php echo $total_product; ?></span> <span
                                            class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i></span>
                                </h2>
                                <div class="small"><?php echo display('total_product') ?></div>
                                <div class="sparkline1 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="count-number"><?php echo $total_suppliers; ?></span> <span
                                            class="slight"><i
                                                class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2>
                                <div class="small"><?php echo display('total_supplier') ?></div>
                                <div class="sparkline1 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="count-number"><?php echo $total_sales; ?></span><span
                                            class="slight"> <i
                                                class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2>
                                <div class="small"><?php echo display('total_invoice') ?></div>
                                <div class="sparkline1 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($this->session->userdata('user_type') == '4') { ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="count-number"><?php echo $total_store_invoice; ?></span><span
                                            class="slight"> <i
                                                class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2>
                                <div class="small"><?php echo display('total_invoice') ?></div>
                                <div class="sparkline1 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <hr>

        <!-- Second Counter -->
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <h2><span class="slight" style="margin-left: 70px;">
                                <img src="<?php echo base_url('my-assets/image/pos_invoice.png'); ?>" height="40"
                                     width="40">
                             </span></h2>
                            <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;">

                                <?php if ($this->auth->is_admin()) { ?>
                                    <a href="<?php echo base_url('Cinvoice/pos_invoice') ?>"><?php echo display('create_pos_invoice') ?></a>
                                <?php } ?>

                                <?php if ($this->session->userdata('user_type') == '4') { ?>
                                    <a href="<?php echo base_url('Store_invoice/pos_invoice') ?>"><?php echo display('create_pos_invoice') ?></a>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <h2><span class="slight" style="margin-left: 70px;"><img
                                            src="<?php echo base_url('my-assets/image/invoice.png'); ?>" height="40"
                                            width="40"> </span></h2>
                            <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;">
                                <?php if ($this->auth->is_admin()) { ?>
                                    <a href="<?php echo base_url('Cinvoice') ?>"><?php echo display('create_new_invoice') ?></a>
                                <?php } ?>

                                <?php if ($this->session->userdata('user_type') == '4') { ?>
                                    <a href="<?php echo base_url('Store_invoice/new_invoice') ?>"><?php echo display('create_new_invoice') ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($this->auth->is_admin()) { ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="slight" style="margin-left: 70px;"><img
                                                src="<?php echo base_url('my-assets/image/product.png'); ?>" height="40"
                                                width="40"> </span></h2>
                                <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a
                                            href="<?php echo base_url('Cproduct') ?>"><?php echo display('add_product') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <h2><span class="slight" style="margin-left: 70px;"><img
                                            src="<?php echo base_url('my-assets/image/customer.png'); ?>" height="40"
                                            width="40"> </span></h2>
                            <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a
                                        href="<?php echo base_url('Ccustomer') ?>"><?php echo display('add_customer') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($this->session->userdata('user_type') == 1) { ?>
            <!-- Third Counter -->
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="slight" style="margin-left: 70px;"><img
                                                src="<?php echo base_url('my-assets/image/sale.png'); ?>" height="40"> </span>
                                </h2>
                                <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a
                                            href="<?php echo base_url('Admin_dashboard/todays_sales_report') ?>"><?php echo display('sales_report') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="slight" style="margin-left: 70px;"><img
                                                src="<?php echo base_url('my-assets/image/purchase.png'); ?>"
                                                height="40"> </span></h2>
                                <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a
                                            href="<?php echo base_url('Admin_dashboard/todays_purchase_report') ?>"><?php echo display('purchase_report') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="slight" style="margin-left: 70px;"><img
                                                src="<?php echo base_url('my-assets/image/stock.png'); ?>" height="40"> </span>
                                </h2>
                                <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a
                                            href="<?php echo base_url('Creport') ?>"><?php echo display('stock_report') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class="slight" style="margin-left: 70px;"><img
                                                src="<?php echo base_url('my-assets/image/account.png'); ?>"
                                                height="40"></span></h2>
                                <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a
                                            href="<?php echo base_url('Caccounts/summary') ?>"><?php echo display('account_summary') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <!-- This month progress -->
                <div class="col-sm-12 col-md-12">
                    <div class="panel panel-bd">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4> <?php echo display('monthly_progress_report') ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <canvas id="lineChart" height="142"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Total Report -->
                <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="panel panel-bd lobidisable">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('todays_report') ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="message_inner">
                                <div class="message_widgets">

                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th><?php echo display('todays_report') ?></th>
                                            <th><?php echo display('money') ?></th>
                                        </tr>
                                        <tr>
                                            <th><?php echo display('total_sales') ?></th>
                                            <td><?php echo(($position == 0) ? "$currency $sales_amount" : "$sales_amount $currency") ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo display('total_purchase') ?></th>
                                            <td><?php echo(($position == 0) ? "$currency $purchase_amount" : "$purchase_amount $currency") ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo display('total_discount') ?></th>
                                            <td><?php echo(($position == 0) ? "$currency $discount_amount" : "$discount_amount $currency") ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        <?php } ?>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
<!-- Admin Home end -->

<!-- ChartJs JavaScript -->
<script src="<?php echo base_url() ?>assets/plugins/chartJs/Chart.min.js" type="text/javascript"></script>

<script type="text/javascript">
    //line chart
    var ctx = document.getElementById("lineChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["<?php echo display('january')?>", "<?php echo display('february')?>", "<?php echo display('march')?>", "<?php echo display('april')?>", "<?php echo display('may')?>", "<?php echo display('june')?>", "<?php echo display('july')?>", "<?php echo display('august')?>", "<?php echo display('september')?>", "<?php echo display('october')?>", "<?php echo display('november')?>", "<?php echo display('december')?>"],
            datasets: [
                {
                    label: "<?php echo display('sales')?>",
                    borderColor: "#2C3136",
                    borderWidth: "1",
                    backgroundColor: "rgba(247,218,38,0.51)",
                    pointHighlightStroke: "rgba(26,179,148,1)",
                    data: [
                        <?php
                        $m = 1;
                        if (!empty($monthly_sales_report[0])) {
                            for ($i = 0; $i < 12; $i++) {
                                if (!empty($monthly_sales_report[0][$i])) {
                                    for ($j = $m; $j <= 12; $j++) {
                                        if (@$monthly_sales_report[0][$i]->month != $j) {
                                            echo "0,";
                                        } else {
                                            echo($monthly_sales_report[0][$i]->total . ",");
                                            $m = $monthly_sales_report[0][$i]->month + 1;
                                            break 1;
                                        }
                                    }
                                } else {
                                    echo "0,";
                                    break;
                                }

                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "<?php echo display('purchase')?>",
                    borderColor: "#73BC4D",
                    borderWidth: "1",
                    backgroundColor: "#73BC4D",
                    pointHighlightStroke: "rgba(26,179,148,1)",
                    data: [
                        <?php
                        $n = 1;
                        if (!empty($monthly_sales_report[1]))
                            for ($k = 0; $k < 12; $k++) {
                                if (!empty($monthly_sales_report[1][$k])) {
                                    for ($l = $n; $l <= 12; $l++) {
                                        if (@$monthly_sales_report[1][$k]->month == $l) {
                                            echo($monthly_sales_report[1][$k]->total_month . ",");
                                            $n = $monthly_sales_report[1][$k]->month + 1;
                                            break 1;
                                        } else {
                                            echo "0,";
                                        }
                                    }
                                } else {
                                    echo "0,";
                                    break;
                                }
                            }
                        ?>
                    ]
                }
            ]
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'nearest',
                intersect: true
            }

        }
    });
</script>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$CI =& get_instance();
$CI->load->model('Soft_settings');
$Soft_settings = $CI->Soft_settings->retrieve_setting_editdata();
?>

<!-- Printable area start -->
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<!-- Printable area end -->

<style>
    .sign {
        border-top: 1px solid #ddd;
        margin: 20px;
        text-align: center;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('invoice_details') ?></h1>
            <small><?php echo display('invoice_details') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('invoice_details') ?></li>
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
        <div class="row">

            <div class="col-sm-12">
                <div class="panel panel-bd">
                    <div id="printableArea">
                        <div class="panel-body">
                            <table border="0" width="38%">
                                <tbody><tr>
                                    <td>
                                        <br><table class="table">
                                            <tbody><tr>
                                                {company_info}
                                                <td colspan="2" align="center" style="border-bottom:2px #333 solid;"><span style="font-size: 17pt; font-weight:bold;">{company_name}</span><br>
                                                    {address}<br>
                                                    {mobile}<br>
                                                    {email}<br>
                                                    {website}
                                                </td>
                                                {/company_info}
                                            </tr>

                                            <tr>
                                                <td align="left" style="width: 50%;">
                                                    <div><b>{customer_name}</b></div>
                                                    <?php if ($customer_address) { ?>
                                                    <div>
                                                        {customer_address}
                                                    </div>
                                                    <?php } ?>

                                                    <?php if ($customer_mobile) { ?>
                                                    <div><b>
                                                                {customer_mobile}
                                                            </b></div>
                                                    <?php }

                                                    if ($customer_email) { ?>
                                                    <div> {customer_email}</div>
                                                    <?php } ?>
                                                </td>
                                                <td align="right" style="width: 50%;">
                                                    <div><strong><?php echo display('invoice_no') ?>
                                                            </strong><br>
                                                        {invoice_no}
                                                    </div>
                                                    <div>
                                                        <strong><?php echo display('date') ?>
                                                            </strong><br>
                                                        {final_date}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody></table>

                                        <table class="table">
                                            <tbody align="right">
                                            <tr>
                                                <th><?php echo display('product_name') ?></th>
                                                <th><?php echo display('quantity') ?></th>
                                                <th><?php echo display('rate') ?></th>
                                                <th><?php echo display('discount') ?></th>
                                                <th><?php echo display('total') ?></th>
                                            </tr>
                                            {invoice_all_data}
                                            <tr>
                                                <th style="width: 110px">{product_name}-({product_model})</th>
                                                <td>{quantity}</td>
                                                <td><?php echo(($position == 0) ? "$currency{rate}" : "{rate}$currency") ?></td>
                                                <td><?php echo(($position == 0) ? "$currency {discount}" : "{discount} $currency") ?></td>
                                                <td><?php echo(($position == 0) ? "$currency {total_price}" : "{total_price} $currency") ?></td>
                                            </tr>
                                            {/invoice_all_data}
                                            
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('service_charge') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? "$currency {service_charge}" : "{service_charge} $currency") ?></nobr></td>
                                            </tr>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('shipping_charge') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? "$currency {shipping_charge}" : "{shipping_charge} $currency") ?></nobr></td>
                                            </tr>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('discount') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? "$currency {subTotal_discount}" : "{subTotal_discount} $currency"); ?></nobr></td>
                                            </tr>
                                            <?php
                                                            $this->db->select('a.*,b.tax_name');
                                                            $this->db->from('tax_collection_summary a');
                                                            $this->db->join('tax b', 'a.tax_id = b.tax_id');
                                                            $this->db->where('a.invoice_id', $invoice_id);
                                                            $this->db->where('a.tax_id', 'H5MQN4NXJBSDX4L');
                                                            $tax_info = $this->db->get()->row();
                                            if ($tax_info) {
                                            ?>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><strong><?php echo $tax_info->tax_name ?></strong></nobr></td>
                                                <td align="right"><nobr><strong> <?php
                                                            if ($tax_info) {
                                                                echo(($position == 0) ? $currency . " " . $tax_info->tax_amount : $tax_info->tax_amount . " " . $currency);
                                                            } else {
                                                                echo(($position == 0) ? "$currency 0" : "0 $currency");
                                                            }
                                                            ?></strong></nobr></td>
                                            </tr>
                                            <?php }
                                            $this->db->select('a.*,b.tax_name');
                                            $this->db->from('tax_collection_summary a');
                                            $this->db->join('tax b', 'a.tax_id = b.tax_id');
                                            $this->db->where('a.invoice_id', $invoice_id);
                                            $this->db->where('a.tax_id', '52C2SKCKGQY6Q9J');
                                            $tax_info = $this->db->get()->row();
                                            if ($tax_info) {
                                            ?>

                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><strong><?php echo $tax_info->tax_name ?></strong></nobr></td>
                                                <td align="right"><nobr><strong><?php
                                                            if ($tax_info) {
                                                                echo(($position == 0) ? $currency . " " . $tax_info->tax_amount : $tax_info->tax_amount . " " . $currency);
                                                            } else {
                                                                echo(($position == 0) ? "$currency 0" : "0 $currency");
                                                            }
                                                            ?></strong></nobr></td>
                                            </tr>
                                            <?php }

                                            $this->db->select('a.*,b.tax_name');
                                            $this->db->from('tax_collection_summary a');
                                            $this->db->join('tax b', 'a.tax_id = b.tax_id');
                                            $this->db->where('a.invoice_id', $invoice_id);
                                            $this->db->where('a.tax_id', '5SN9PRWPN131T4V');
                                            $tax_info = $this->db->get()->row();

                                            if ($tax_info) {
                                            ?>


                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><strong><?php echo $tax_info->tax_name ?></strong></nobr></td>
                                                <td align="right"><nobr><strong><?php
                                                            if ($tax_info) {
                                                                echo(($position == 0) ? $currency . " " . $tax_info->tax_amount : $tax_info->tax_amount . " " . $currency);
                                                            } else {
                                                                echo(($position == 0) ? "$currency 0" : "0 $currency");
                                                            }
                                                            ?></strong></nobr></td>
                                            </tr>
                                            <?php } ?>

                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><strong><?php echo display('grand_total') ?></strong></nobr></td>
                                                <td align="right"><nobr><strong><?php echo(($position == 0) ? "$currency {total_amount}" : "{total_amount} $currency") ?></strong></nobr></td>
                                            </tr>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('paid_ammount') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? "$currency {paid_amount}" : "{paid_amount} $currency") ?></nobr></td>
                                            </tr>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('due') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? "$currency {due_amount}" : "{due_amount} $currency") ?></nobr></td>
                                            </tr>
                                            </tbody></table>
                                        <table width="100%">
                                            <tbody><tr style="margin-top:20px;">
                                                <td>
                                                    <div class="sign" style="border-top: 1px solid #ddd;margin: 20px;text-align: center;"><?php echo display('sign_office') ?></div>
                                                </td>
                                                <td><div class="sign" style="border-top: 1px solid #ddd;margin: 20px;text-align: center;"><?php echo display('customer_sign') ?></div></td>
                                            </tr>
                                            <tr align="center">
                                                <td colspan="2"><?php echo display('thank_you_for_shopping_with_us')  ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                    </div>

                    <div class="panel-footer text-left">
                        <a class="btn btn-danger"
                           href="<?php echo base_url('Cinvoice'); ?>"><?php echo display('cancel') ?></a>
                        <a class="btn btn-info" href="#" onclick="printDiv('printableArea')"><span
                                    class="fa fa-print"></span></a>
                    </div>
                </div>
            </div>

        </div>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->

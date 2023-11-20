<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css';  ?>">

<style>
    .sign {
        border-top: 1px solid #ddd;
        margin: 20px;
        text-align: center;
    }
</style>
</head>
<body>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd">
                    <div id="printableArea">
                        <div class="panel-body">
                            <table border="0" width="38%">
                                <tbody><tr>
                                    <td>
                                        <br><table class="table">
                                            <tbody style="text-align: center;">
                                            <tr>
                                                <td colspan="2" align="center" style="border-bottom:1px #333 dashed;"><span style="font-size: 17pt; font-weight:bold;"><?php echo $company_info[0]['company_name'];?></span><br>
                                                 <?php echo  $company_info[0]['address']; ?>  <br>
                                                    <?php echo  $company_info[0]['mobile']; ?><br>
                                                    <?php echo  $company_info[0]['email']; ?> <br>
                                                    <?php echo  $company_info[0]['website']; ?>
                                                </td>

                                            </tr>

                                            <tr>
                                                <td colspan="2" align="center" style="border-bottom:2px #333 solid;">
                                                    <div><b><?php echo $customer_name;  ?></b></div>
                                                    <?php if ($customer_address) { ?>
                                                    <div>
                                                        <?php echo $customer_address;  ?>
                                                    </div>
                                                    <?php } ?>

                                                    <?php if ($customer_mobile) { ?>
                                                    <div><b>
                                                            <?php echo $customer_mobile;  ?>
                                                            </b></div>
                                                    <?php }

                                                    if ($customer_email) { ?>
                                                    <div><?php echo $customer_email;  ?> </div>
                                                    <?php } ?>
                                                </td>

                                            </tr>
                                            <br>
                                            <tr>
                                                <td align="left" width="50%">
                                                    <div><strong><?php echo display('invoice_no') ?> :
                                                        </strong>
                                                        <?php echo $invoice_no;  ?>
                                                    </div>
                                                </td>
                                                <td align="right" width="50%">
                                                    <div>
                                                        <?php echo $final_date;  ?>
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

                                            <?php foreach ($invoice_all_data as $item){  ?>
                                            <tr>
                                                <th style="width: 110px"><?php echo $item['product_name'].'-'.$item['product_model'] ; ?></th>
                                                <td><?php  echo $item['quantity'];?></td>
                                                <td><?php echo(($position == 0) ? $currency.''.$item['rate'] : $item['rate'].''.$currency) ?></td>
                                                <td><?php echo(($position == 0) ? $currency.''.$item['discount'] : $item['discount'].''.$currency) ?></td>
                                                <td><?php echo(($position == 0) ? $currency.''.$item['total_price'] : $item['total_price'].''.$currency) ?></td>
                                            </tr>
                                            <?php }  ?>

                                            
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('service_charge') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? $currency.''.$service_charge: $service_charge.''.$currency) ?></nobr></td>
                                            </tr>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('shipping_charge') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? $currency.''.$shipping_charge : $shipping_charge .''.$currency) ?></nobr></td>
                                            </tr>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('discount') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? $currency.''.$subTotal_discount : $subTotal_discount.''.$currency); ?></nobr></td>
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
                                                <td align="right"><nobr><strong><?php echo(($position == 0) ? $currency.''.$total_amount : $total_amount.''.$currency) ?></strong></nobr></td>
                                            </tr>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('paid_ammount') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? $currency.''.$paid_amount : $paid_amount.''.$currency) ?></nobr></td>
                                            </tr>
                                            <tr>
                                                <td align="left"><nobr></nobr></td>
                                                <td align="left" colspan="3"><nobr><?php echo display('due') ?></nobr></td>
                                                <td align="right"><nobr><?php echo(($position == 0) ? $currency.''.$due_amount : $due_amount.''.$currency) ?></nobr></td>
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
                </div>
            </div>

        </div>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->

</body>
</html>
<?php

    if(isset($_GET['place'])){
        $place=  $_GET['place'];
    }
    if(!empty($place)){?>
    <script>
        var return_form="<?php echo $place ?>";
        if(return_form === 'pos') {
            var printContents = document.getElementById('printableArea').innerHTML;
            
            var originalContents = document.body.innerHTML;

            //document.body.innerHTML = printContents;
            //window.print();
            //document.body.innerHTML = originalContents;

            /*setInterval(function(){
                window.location.href="<?php echo base_url().'Cinvoice/pos_invoice';  ?>";
            }, 3000);*/
        }
    </script>

<?php }
?>
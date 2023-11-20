<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Payment type select by js start-->
<style>
    #store_hide
    {
        display:none;
    }   
</style>
<script type="text/javascript">
    function bank_info_show(payment_type)
    {
        if(payment_type.value == "1"){
            document.getElementById("store_hide").style.display="none";
            document.getElementById("wearhouse_hide").style.display="block";  
        }else{
            document.getElementById("wearhouse_hide").style.display="none"; 
            document.getElementById("store_hide").style.display="block"; 
        }
    }
</script>
<!-- Payment type select by js end-->

<!-- Add New Purchase Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('store_product_transfer') ?></h1>
            <small><?php echo display('store_product_transfer') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('store_set') ?></a></li>
                <li class="active"><?php echo display('store_product_transfer') ?></li>
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
                <a href="<?php echo base_url('Cstore')?>" class="btn -btn-info color4 color5 m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('add_store')?></a>
                    <a href="<?php echo base_url('Cstore/manage_store')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_store')?></a>
                    <a href="<?php echo base_url('Cstore/manage_store_product')?>" class="btn btn-warning m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_store_product')?></a>

                </div>
            </div>
        </div>

        <!-- Add New Purchase -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('store_product_transfer') ?></h4>
                        </div>
                    </div>

                    <div class="panel-body">
                    <?php echo form_open_multipart('Cstore/insert_store_product2',array('class' => 'form-vertical', 'id' => 'validate','name' => 'insert_purchase'))?>
                        
                        <div class="row">
                            <div class="col-sm-6">
                               <div class="form-group row">
                                    <label for="supplier_sss" class="col-sm-4 col-form-label">Store Name
                                        <i class="text-danger">*</i>
                                    </label>
                                    <input type="hidden" name="store_id_hidden" id="store_id_hidden">
                                    <div class="col-sm-8">
                                    <select class="form-control" id="store_id" name="store_id" required="">
                                        <option value=""></option>
                                        {store_list}
                                        <option value="{store_id}">{store_name}</option>
                                        {/store_list}
                                    </select>
                                    </div>
                                </div> 
                            </div>

                             <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label">Transfer Date
                                        <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-8">
                                        <?php $date = date('m-d-Y'); ?>
                                        <input type="text" tabindex="3" class="form-control datepicker" name="transfer_date" value="<?php echo $date; ?>" id="date" required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6" id="-store_hide">
                                <div class="form-group row">
                                    <label for="store_id" class="col-sm-4 col-form-label"><?php echo display('transfer_to') ?>
                                        <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="transfer" name="t_store_id" required="" >
                                
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="invoice_no" class="col-sm-4 col-form-label"><?php echo display('invoice_no') ?>
                                        <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" tabindex="3" class="form-control" name="invoice_no" placeholder="<?php echo display('invoice_no') ?>" id="invoice_no" required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            
                        </div>

                        <div class="table-responsive" style="margin-top: 10px">
                            <table class="table table-bordered table-hover" id="purchaseTable">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('item_information') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center" width="130">Variant <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('delete') ?> </th>
                                    </tr>
                                </thead>
                                <tbody id="addPurchaseItem">
                                    <tr>
                                        <td>
                                            <input type="text" name="product_name" required class="form-control product_name productSelection" onkeyup="product_pur_or_list(1);" placeholder="<?php echo display('product_name') ?>" id="product_name_1" tabindex="5" >

                                            <input type="hidden" class="autocomplete_hidden_value product_id_1" name="product_id[]" id="SchoolHiddenId"/>

                                            <input type="hidden" class="sl" value="1">
                                        </td>

                                        <td class="text-center">
                                            <select name="variant_id[]" id="variant_id_1" class="form-control variant_id" required="" style="width: 100%">
                                            </select>
                                        </td>

                                        <td class="text-right">
                                            <input type="number" name="product_quantity[]" id="total_qntt_1" onkeyup="calculate_add_purchase('1')" onchange="calculate_add_purchase('1')" class="form-control text-right" placeholder="0" min="0" required="" />
                                        </td>
                                        <td>
                                            <button style="text-align: right;" class="btn btn-danger" type="button" value="<?php echo display('delete')?>" onclick="deleteRow(this)"><?php echo display('delete')?></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <input type="button" id="add-invoice-item" class="btn -btn-info color4 color5" name="add-invoice-item"  onClick="addPurchaseOrderField('addPurchaseItem');" value="<?php echo display('add_new_item') ?>" />

                                            <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url();?>"/>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="submit" id="add-store" class="btn btn-primary btn-large" name="add-store" value="<?php echo display('submit') ?>" />
                                <input type="submit" value="<?php echo display('submit_and_add_another') ?>" name="add-store-another" class="btn btn-large btn-success" id="add-store-another">
                            </div>
                        </div>
                    <?php echo form_close()?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add New Purchase End -->



<script type="text/javascript">
    //Select transfer to
    $('body').on('change',"#store_id",function(event){
        event.preventDefault(); 
        // var transfer_id=$('#transfer_to').val();
        var store_id=$('#store_id').val();
        if ( !store_id ) {
            alert('<?php echo display('please_select_store')?>');
            return false;
        }
        var csrf_test_name=  $("[name=csrf_test_name]").val();
        $.ajax({
            url: '<?php echo base_url('Cstore/store_transfer_select')?>',
            type: 'post',
            data: {store_id:store_id,csrf_test_name:csrf_test_name},
            success: function (result){
               var msg= JSON.parse(result);

                // console.log(result);
                if (msg) {
                    // $("#transfer").css('display','block');
                    $("#transfer").html(msg['stores']);
                    $("#product_name").html(msg['products']);
                }else{
                    $("#transfer").html('');
                    $("#product_name").html('');
                }
            },
            error: function (xhr, desc, err){
                 alert('failed');
            }
        });        
    });
    //Product select by ajax end
</script>

<!-- Product purchase list -->
<script type="text/javascript">

    //Product purchase or list
    function product_pur_or_list(sl) {

        var store_id  = $('#store_id').val();
        $('#store_id_hidden').val(store_id);
        var product_name = $('#product_name_'+sl).val();

        //Supplier id existing check
        if ( store_id == 0) {
            alert('Please select store first');
            $('#product_name_'+sl).val('');
            return false;
        } else {
            $('#store_id').attr('disabled', 'disabled');
        }

        // Auto complete ajax
        var options = {
                minLength: 0,
                source: function( request, response ) {
                $.ajax( {
                    url: "<?php echo base_url('Cstore/product_search_by_store')?>",
                    method: 'post',
                    dataType: "json",
                    data: {
                        term: request.term,
                        store_id:$('#store_id').val(),
                        product_name:product_name,
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            focus: function( event, ui ) {
                $(this).val(ui.item.label);
                return false;
            },
            select: function( event, ui ) {
                $(this).parent().parent().find(".autocomplete_hidden_value").val(ui.item.value); 
                var sl          = $(this).parent().parent().find(".sl").val(); 
                var id          = ui.item.value;
                var dataString  = 'product_id='+ id;
                var avl_qntt    = 'avl_qntt_'+sl;
                var price_item  = 'price_item_'+sl;
                var variant_id  = 'variant_id_'+sl;
             
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Cpurchase/retrieve_product_data')?>",
                    data: dataString,
                    cache: false,
                    success: function(data)
                    {
                        obj = JSON.parse(data);
                        console.log(obj.variant);
                        $('#'+price_item).val(obj.supplier_price);
                        $('#'+avl_qntt).val(obj.total_product);
                        $('#'+variant_id).html(obj.variant).val("E94VKEM7TPI82EA");
                    } 
                });

                $(this).unbind("change");
                return false;
           }
        }
        $('body').on('keydown.autocomplete', '.product_name', function() {
           $(this).autocomplete(options);
        });
    }
</script>

<!-- JS -->
<script type="text/javascript">

    // Counts and limit for purchase order
    var count = 2;
    var limits = 500;

    //Add Purchase Order Field
    function addPurchaseOrderField(divName){

        if (count == limits)  {
            alert("You have reached the limit of adding " + count + " inputs");
        }else{
            var newdiv = document.createElement('tr');
            var tabin  = "product_name_"+count;

            $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });

            newdiv.innerHTML ='<td><input type="text" name="product_name" required class="form-control product_name productSelection" onkeyup="product_pur_or_list('+count+');" placeholder="<?php echo display('product_name') ?>" id="product_name_'+count+'" tabindex="5" ><input type="hidden" class="autocomplete_hidden_value product_id_'+count+'" name="product_id[]" id="SchoolHiddenId"/><input type="hidden" class="sl" value="'+count+'"></td><td class="text-center"><select name="variant_id[]" id="variant_id_'+count+'" class="form-control variant_id" required="" style="width: 100%"><option value=""></option></select></td><td class="text-right"><input type="number" name="product_quantity[]" id="total_qntt_'+count+'" onkeyup="calculate_add_purchase('+count+')" onchange="calculate_add_purchase('+count+')"  class="form-control text-right" placeholder="0" min="0" required/></td><td><button style="text-align: right;" class="btn btn-danger" type="button" value="<?php echo display('delete')?>" onclick="deleteRow(this)"><?php echo display('delete')?></button></td>';
            document.getElementById(divName).appendChild(newdiv);
            document.getElementById(tabin).focus();
            count++;

            $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });
        }
    }

    //Calculate store product
    function calculate_add_purchase(sl) {

        var e = 0;
        var gr_tot = 0;
        var total_qntt   = $("#total_qntt_"+sl).val();
        var price_item   = $("#price_item_"+sl).val();
        var total_price  = total_qntt * price_item;

        $("#total_price_"+sl).val(total_price.toFixed(2));

        //Total Price
        $(".total_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        $("#grandTotal").val(gr_tot.toFixed(2,2));
    }

    //Select stock by product and variant id
    $('body').on('change', '.variant_id', function() {

        var sl            = $(this).parent().parent().find(".sl").val();
        var product_id    = $('.product_id_'+sl).val();
        var purchase_to   = $('#purchase_to').val();
        var wearhouse_id  = $('#wearhouse_id').val();
        var store_id      = $('#store_id').val();
        var variant_id    = $(this).val();

        if (purchase_to == 1) {
            if (wearhouse_id == 0) {
                alert('<?php echo display('please_select_wearhouse')?>');
                return false;
            }
        }

        if (purchase_to == 2) {
            if (store_id == 0) {
                alert('<?php echo display('please_select_store')?>');
                return false;
            }
        }

        $.ajax({
            type: "post",
            async: false,
            url: '<?php echo base_url('Cpurchase/wearhouse_available_stock')?>',
            data: {product_id: product_id,variant_id:variant_id,purchase_to:purchase_to,wearhouse_id:wearhouse_id,store_id:store_id},
            success: function(data) {
                if (data) {
                    $('#avl_qntt_'+sl).val(data);
                }
            },
            error: function() {
                alert('Request Failed, Please check your code and try again!');
            }
        });
    }); 

    //Delete a row from purchase table
    function deleteRow(t) {
        var a = $("#purchaseTable > tbody > tr").length;
        if (1 == a) {
            alert("There only one row you can't delete."); 
            return false;
        }else {
            var e = t.parentNode.parentNode;
            e.parentNode.removeChild(e);
            calculate_add_purchase();
        
        }
        calculate_add_purchase();
        $('#item-number').html('0');
        $(".itemNumber>tr").each(function(i){
            $('#item-number').html(i+1);
            $('.item_bill').html(i+1);
        });
    }
</script>
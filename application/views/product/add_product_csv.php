<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Add Product Report Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Import Product" ?></h1>
            <small><?php echo "Import Product" ?></small>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url('')?>"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="<?php echo base_url('Cproduct')?>"><?php echo display('product') ?></a></li>
                <li class="active"><?php echo "Import Product" ?></li>
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
        <!-- Add Product report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <!-- All modals added here for the demo. You would of course just have one, dynamically created -->
                    <!-- Modal fade in & scale effect -->
                    <div class="md-modal md-effect-1" id="modal-1">
                        <div class="md-content">
                            <h3><?php echo display('add_supplier')?></h3>
                            <div class="n-modal-body">
                                <h4 class="text-success" id="message" style="display: none;text-align: center;"></h4>
                                <h4 class="text-danger" id="error_message" style="display: none;text-align: center;"></h4>
                                <form action="<?php echo base_url('Cproduct/add_supplier')?>" id="add_supplier" method="post">
                                    <div class="panel-body">
                                        <div class="form-group row">
                                            <label for="supplier_name" class="col-sm-4 col-form-label"><?php echo display('supplier_name') ?> <i class="text-danger">*</i></label>
                                            <div class="col-sm-6">
                                                <input class="form-control" name ="supplier_name" id="supplier_name" type="text" placeholder="<?php echo display('supplier_name') ?>"  required="">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="mobile" class="col-sm-4 col-form-label"><?php echo display('supplier_mobile') ?> <i class="text-danger">*</i></label>
                                            <div class="col-sm-6">
                                                <input class="form-control" name="mobile" id="mobile" type="number" placeholder="<?php echo display('supplier_mobile') ?>" required="" min="0">
                                            </div>
                                        </div>
                   
                                        <div class="form-group row">
                                            <label for="address " class="col-sm-4 col-form-label"><?php echo display('supplier_address') ?></label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="address" id="address " rows="3" placeholder="<?php echo display('supplier_address') ?>"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="details" class="col-sm-4 col-form-label"><?php echo display('supplier_details') ?></label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="details" id="details" rows="3" placeholder="<?php echo display('supplier_details') ?>"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                                            <div class="col-sm-6">
                                                <input type="submit" id="add-supplier" class="btn btn-primary btn-large" name="add-supplier" value="<?php echo display('save') ?>" /> 

                                                <input type="button" class="btn btn-success md-close" value="<?php echo display('close') ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="md-modal md-effect-1" id="modal-2">
                        <div class="md-content">
                            <h3><?php echo display('add_category')?></h3>
                            <div class="n-modal-body">
                                <h4 class="text-success" id="message1" style="display: none;text-align: center;"></h4>
                                <h4 class="text-danger" id="error_message1" style="display: none;text-align: center;"></h4>
                                <form action="<?php echo base_url('Cproduct/insert_category')?>" id="add_category" method="post">
                                    <div class="panel-body">
                                        
                                        <div class="form-group row">
                                            <label for="category_name" class="col-sm-4 col-form-label"><?php echo display('category_name')?> <i class="text-danger">*</i></label>
                                            <div class="col-sm-6">
                                                <input class="form-control" name ="category_name" id="category_name" type="text" placeholder="<?php echo display('category_name') ?>"  required="">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                                            <div class="col-sm-6">
                                                <input type="submit" id="add-supplier" class="btn btn-primary btn-large" name="add-supplier" value="<?php echo display('save') ?>" /> 

                                                <input type="button" class="btn btn-success md-close" value="<?php echo display('close') ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success md-trigger m-b-5 m-r-2" data-modal="modal-1">
                    <i class="ti-plus"></i><span> <?php echo display('add_supplier') ?></span></button>

                    <button class="btn -btn-info color4 color5 md-trigger m-b-5 m-r-2" data-modal="modal-2"><i class="ti-plus"></i> <?php echo display('add_category')?></button>

                    <a href="<?php echo base_url('Cproduct/manage_product')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"></i>  <?php echo display('manage_product')?></a>

                    <a href="<?php echo base_url('Cproduct/product_details_single')?>" class="btn btn-warning m-b-5 m-r-2"><i class="ti-align-justify"> </i><?php echo display('product_ledger')?></a>

                    
                    <!-- the overlay element -->
                    <div class="md-overlay"></div>
                </div>
            </div>
        </div>

         <script type="text/javascript">
            $("#add_supplier").submit(function(event)
            { 
                event.preventDefault();  
                var formdata = new FormData($(this)[0]);

                $.ajax({
                    url:  $(this).attr("action"),
                    type: $(this).attr("method"),
                    data: formdata, 
                    processData: false,
                    contentType: false,
                    success: function (data, status)
                    {
                        if (data == '1') {
                            $('#message').css('display','block');
                            $('#message').html('Supplier added successfully');
                            setTimeout(function(){
                                window.location.href = window.location.href;
                            }, 2000);
                        }else if(data == '2'){
                            $('#error_message').css('display','block');
                            $('#error_message').html('Supplier already exist !');
                        }else if(data == '3'){
                            $('#error_message').css('display','block');
                            $('#error_message').html('Supplier name and mobile is required!');
                        }
                    },
                    error: function (xhr, desc, err)
                    {


                    }
                });        
            });
        </script>
        <!-- Add category -->
        <script type="text/javascript">
            $("#add_category").submit(function(event)
            { 
                event.preventDefault();  
                var formdata = new FormData($(this)[0]);

                $.ajax({
                    url:  $(this).attr("action"),
                    type: $(this).attr("method"),
                    data: formdata, 
                    processData: false,
                    contentType: false,
                    success: function (data, status)
                    {
                        if (data == '1') {
                            $('#message1').css('display','block');
                            $('#message1').html('Category added successfully');
                            setTimeout(function(){
                                window.location.href = window.location.href;
                            }, 1000);
                        }else if (data == '2'){
                            $('#error_message1').css('display','block');
                            $('#error_message1').html('Category already exist !');
                        }else if(data == '3'){
                            $('#error_message1').css('display','block');
                            $('#error_message1').html('Category name required!');
                        }
                    },
                    error: function (xhr, desc, err)
                    {


                    }
                });        
            });
        </script>

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <!-- Multiple panels with drag & drop -->
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('csv_file_informaion')?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                       <a href="<?php echo base_url('assets/data/csv/product_sample.xlsx') ?>" class="btn btn-primary  pull-right"><i class="fa fa-download"></i> 
                        <?php echo display('download_sample_file')?>
                       </a>
                            <span class="text-warning">The first line in downloaded samplel file should remain as it is. Please do not change the order of columns.</span><br>The correct column order is <span class="text-info">(supplier_id, category_id, product_name, price, supplier_price, unit, product_model, product_details, image_thumb, brand_id, variants, type, best_sale, onsale, onsale_price, invoice_details, image_large_details,review, description, tag, specification, status separated by vertical bar)</span> &amp; you must follow this.<br><p>The images should be uploaded in <strong>For thumb image: <?php echo base_url('my-assets/image/product/thumb')?></strong> And for details image: <strong><?php echo base_url('my-assets/image/product')?></strong> folder.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo "Import Product" ?></h4>
                        </div>
                    </div>
                     <?php echo form_open_multipart('Cproduct/uploadCsv',array('class' => 'form-vertical', 'id' => 'validate','name' => 'insert_product'))?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="upload_csv_file" class="col-sm-4 col-form-label"><?php echo "Upload File" ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="upload_csv_file" type="file" id="upload_csv_file" placeholder="<?php echo display('upload_csv_file') ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="submit" id="add-product" class="btn btn-primary btn-large" name="add-product" value="<?php echo display('submit') ?>" />
                                <input type="submit" value="<?php echo display('submit_and_add_another') ?>" name="add-product-another" class="btn btn-large btn-success color4" id="add-product-another">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add Product Report End -->




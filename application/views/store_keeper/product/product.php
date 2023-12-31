<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Manage Product Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('manage_product') ?></h1>
	        <small><?php echo display('manage_your_product') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="<?php echo base_url('')?>"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="<?php echo base_url('store_keeper/Cproduct')?>"><?php echo display('product') ?></a></li>
	            <li class="active"><?php echo display('manage_product') ?></li>
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
                </div>
            </div>
        </div>

        <div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
						<form action="<?php echo base_url('store_keeper/Cproduct/product_by_search')?>" class="form-inline" method="post" accept-charset="utf-8">
		                    <label class="select"><?php echo display('product_name')?>:</label>
							<select class="form-control" name="product_id" style="width: 300px;">
								{all_product_list}
                                <option value=""></option>
	                           	<option value="{product_id}">{product_name}-({product_model})</option>
								{/all_product_list}
                            </select>
							<button type="submit" class="btn btn-primary"><?php echo display('search')?></button>
			            </form>		            
			        </div>
		        </div>
		    </div>
	    </div>


		<!-- Manage Product report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('manage_product') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample3" class="table table-bordered table-striped table-hover">
		                        <thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('product_name') ?></th>
										<th><?php echo display('supplier') ?></th>
										<th><?php echo display('category') ?></th>
										<th><?php echo display('unit') ?></th>
										<th><?php echo display('sell_price') ?></th>
										<th><?php echo display('supplier_price') ?></th>
										<th><?php echo display('onsale_price') ?></th>
										<th><?php echo display('image') ?>s</th>
										<th style="width : 130px"><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
								if ($products_list) {

								foreach ($products_list as $v_product_list):

                                    ?>
                                    <tr>
                                        <td><?php echo $v_product_list['sl'] ?></td>
                                        <td>
                                            <a href="<?php echo base_url() . 'store_keeper/Cproduct/product_details/'.$v_product_list['product_id']; ?>">
                                                 <?php echo $v_product_list['product_name']?>-(<?php echo $v_product_list['product_model'] ?>) <i class="fa fa-shopping-bag pull-right" aria-hidden="true"></i></a>
                                        </td>
                                        <td><?php echo $v_product_list['supplier_name'] ?></td>
                                        <td><?php echo $v_product_list['category_name']?></td>
                                        <td><?php echo $v_product_list['unit_short_name']?></td>
                                        <td style="text-align: right;">
                                            <?php echo(($position == 0) ? ($currency.' '.$v_product_list["price"]) : ($v_product_list["price"].' '.$currency)) ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php echo(($position == 0) ? ($currency.' '.$v_product_list["supplier_price"]) : ($v_product_list["supplier_price"].' '.$currency)) ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php echo(($position == 0) ? ($currency.' '.$v_product_list["onsale_price"]) : ($v_product_list["onsale_price"].' '.$currency)) ?>
                                        </td>
                                        <td class="text-center">
                                            <img src="<?php echo base_url() . $v_product_list['image_thumb']; ?>"
                                                 class="img img-responsive center-block" height="50" width="50">
                                        </td>
                                        <td>
                                            <center>
                                                <?php echo form_open() ?>

                                                <a href="<?php echo base_url() . 'store_keeper/Cqrcode/qrgenerator/'.$v_product_list['product_id']; ?>"
                                                   class="btn btn-success btn-sm" data-toggle="tooltip"
                                                   data-placement="left" title="<?php echo display('qr_code') ?>"><i
                                                            class="fa fa-qrcode" aria-hidden="true"></i></a>

                                                <a href="<?php echo base_url() . 'store_keeper/Cbarcode/barcode_print/'.$v_product_list['product_id']; ?>"
                                                   class="btn btn-warning btn-sm" data-toggle="tooltip"
                                                   data-placement="left" title="<?php echo display('barcode') ?>"><i
                                                            class="fa fa-barcode" aria-hidden="true"></i></a>

                                                <?php echo form_close() ?>
                                            </center>
                                        </td>
                                    </tr>

                                    <?php
                                endforeach;
								}
								?>
								</tbody>
		                    </table>
		                    <div class="text-right"><?php echo @$links?></div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Manage Product End -->
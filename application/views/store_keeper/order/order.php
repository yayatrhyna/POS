<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Manage order Start -->
<div class="content-wrapper">
	<section class="content-header">
		<div class="header-icon">
			<i class="pe-7s-note2"></i>
		</div>
		<div class="header-title">
			<h1><?php echo display('manage_order') ?></h1>
			<small><?php echo display('manage_order') ?></small>
			<ol class="breadcrumb">
				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
				<li><a href="#"><?php echo display('order') ?></a></li>
				<li class="active"><?php echo display('manage_order') ?></li>
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
					<a href="<?php echo base_url('Corder/new_order')?>" class="btn -btn-info color4 color5 m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('new_order')?></a>
					<a href="<?php echo base_url('Cinvoice/pos_invoice')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('pos_invoice')?></a>
				</div>
			</div>
		</div>


		<!-- Manage order report -->
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-bd lobidrag">
					<div class="panel-heading">
						<div class="panel-title">
							<h4><?php echo display('manage_order') ?></h4>
						</div>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table id="dataTableExample2" class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('order_no') ?></th>
										<th><?php echo display('customer_name') ?></th>
										<th><?php echo display('date') ?></th>
										<th><?php echo display('total_amount') ?></th>
										<th><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if ($orders_list) {
										foreach ($orders_list as $order) {
											?>
											<tr>
												<td><?php echo $order['sl']?></td>
												<td>
													<a href="<?php echo base_url().'Corder/order_details_data/'.$order['order_id'] ?>">
                                                         <?php echo $order['order']?> <i class="fa fa-tasks pull-right" aria-hidden="true"></i>
												</a>
											</td>
											<td>
												<a href="<?php echo base_url().'Ccustomer/customerledger/'.$order['customer_id']; ?>">
                                                     <?php echo $order['customer_name']?> <i class="fa fa-user pull-right" aria-hidden="true"></i></a>
											</td>
											<td><?php echo $order['final_date']?></td>
											<td style="text-align: right;"><?php echo (($position==0)?$currency.' '.$order['total_amount']:$order['total_amount'].' '.$currency) ?></td>
											<td>
												<center>
													<?php echo form_open()?>
													<?php if ($order['status'] == 1) { ?>
														<a href="<?php echo base_url().'Corder/order_paid_data/'.$order['order_id']; ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('invoice') ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
													<?php }else{ ?>
														<a href="javascript:void(0)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('invoiced') ?>"><i class="fa fa-check" aria-hidden="true"></i></a>
													<?php } ?>
													<a href="<?php echo $order['file_path']?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('download') ?>" download><i class="fa fa-download" aria-hidden="true"></i></a>

													<a href="<?php echo base_url().'Corder/order_update_form/'.$order['order_id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>

													<a href="<?php echo base_url('Corder/order_delete/'.$order['order_id'])?>" class="btn btn-danger btn-sm"  onclick="return confirm('<?php echo display('are_you_sure_want_to_delete')?>');" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
													<?php echo form_close()?>
												</center>
											</td>
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</div>
<!-- Manage order End -->
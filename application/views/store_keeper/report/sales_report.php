<!-- Stock report start -->
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
	document.body.style.marginTop="0px";
    window.print();
    document.body.innerHTML = originalContents;
}
</script>


<!-- Sales Report Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('sales_report') ?></h1>
	        <small><?php echo display('total_sales_report') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="index.html"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('report') ?></a></li>
	            <li class="active"><?php echo display('sales_report') ?></li>
	        </ol>
	    </div>
	</section>

	<section class="content">
		<div class="row">
            <div class="col-sm-12">
                <div class="column">
                  	<a href="<?php echo base_url('Admin_dashboard/todays_purchase_report')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('purchase_report')?> </a>

                </div>
            </div>
        </div>

		<div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body">
						<label for="">Filter Metode Pembayaran</label>
						<select class="form-control" name="filter_payment_method" id="filter_payment_method">
							<option value="All" <?php echo ($payment == "All") ? 'selected' : ''; ?>>Semua</option>
							<option value="Cash" <?php echo ($payment == "Cash") ? 'selected' : ''; ?>>Cash</option>
							<option value="QRIS" <?php echo ($payment == "QRIS") ? 'selected' : ''; ?>>QRIS</option>
							<option value="Credit Card" <?php echo ($payment == "Credit Card") ? 'selected' : ''; ?>><?php echo display('credit_card') ?></option>
							<option value="Debit Card" <?php echo ($payment == "Debit Card") ? 'selected' : ''; ?>><?php echo display('debit_card') ?></option>
						</select>						
		            </div>
		        </div>
		    </div>
	    </div>

		<!-- Sales report -->
		<div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
		                <?php echo form_open('Admin_dashboard/retrieve_dateWise_SalesReports',array('class' => 'form-inline', 'id' => 'form-filter'))?>
		                <?php date_default_timezone_set("Asia/Jakarta"); $today = date('m-d-Y'); ?>

                        <?php if (!isset($from_date)) { ?>
                          <div class="form-group">
                              <label class="" for="from_date"><?php echo display('start_date') ?></label>
                              <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" autocomplete="off">
                          </div>

                          <div class="form-group">
                              <label class="" for="to_date"><?php echo display('end_date') ?></label>
                              <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $today?>" autocomplete="off">
                          </div>
                        <?php } else { ?>
                          <div class="form-group">
                              <label class="" for="from_date"><?php echo display('start_date') ?></label>
                              <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" value="<?php echo $from_date?>" autocomplete="off">
                          </div> 

                          <div class="form-group">
                              <label class="" for="to_date"><?php echo display('end_date') ?></label>
                              <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $to_date?>" autocomplete="off">
                          </div>
                        <?php } ?>
						<input type="hidden" name="payment_method" id="payment_method">
		                    <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
		                    <a  class="btn btn-warning" href="#" onclick="printDiv('purchase_div')"><?php echo display('print') ?></a>
		               <?php echo form_close()?>
					   
		            </div>
		        </div>
		    </div>
	    </div>







		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('sales_report') ?> </h4>
		                </div>
		            </div>
		            <div class="panel-body">
		            	<div id="purchase_div" style="margin-left:2px;">
			            	<div class="text-center">
								{company_info}
								<h3> {company_name} </h3>
								<h4 >{address} </h4>
								{/company_info}
								<h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>

								<h4><b>Total Sales All Page: <?php echo (($position==0)?"$currency {total_sales_amount}":"{total_sales_amount} $currency") ?></b></h4>
							</div>

							<div class="text-left">
								<h5><b>Total Sales via Cash : <?php echo $total_cash_invoice ?> invoice =  <?php echo (($position==0)?"$currency {total_sales_report_cash}":"{total_sales_report_cash} $currency") ?></b></h5>
								<h5><b>Total Sales via QRIS : <?php echo $total_qris_invoice ?> invoice =  <?php echo (($position==0)?"$currency {total_sales_report_qris}":"{total_sales_report_qris} $currency") ?></b></h5>
								<h5><b>Total Sales via Credit Card : <?php echo $total_credit_card_invoice ?> invoice =  <?php echo (($position==0)?"$currency {total_sales_report_credit_card}":"{total_sales_report_credit_card} $currency") ?></b></h5>
							</div><br>

			                <div class="table-responsive">
			                    <table class="table table-bordered table-striped table-hover">
			                        <thead>
			                            <tr>
			                                <th><?php echo display('sales_date') ?></th>
											<th><?php echo display('invoice_no') ?></th>
											<th><?php echo display('customer_name') ?></th>
											<th><?php echo display('total_amount') ?></th>
			                            </tr>
			                        </thead>
			                        <tfoot>
										<tr>
											<td colspan="3" style="text-align: right;"><b><?php echo display('total_seles') ?></b></td>
											<td style="text-align: right;"><b><?php echo (($position==0)?"$currency {sales_amount}":"{sales_amount} $currency") ?></b></td>
										</tr>
									</tfoot>
			                        <tbody>
			                        <?php
			                        	if ($sales_report) {
			                        ?>
			                            {sales_report}
										<tr>
											<td>{sales_date}</td>
											<td>
												<a href="<?php echo base_url().'Cinvoice/invoice_inserted_data/{invoice_id}'; ?>">
												{store_code}-{invoice}  <i class="fa fa-tasks pull-right" aria-hidden="true"></i>
												</a>
											</td>
											<td><a href="<?php echo base_url().'Ccustomer/customerledger/{customer_id}'; ?>"> {customer_name} <i class="fa fa-user pull-right" aria-hidden="true"></i></a></td>
											<td style="text-align: right;"><?php echo (($position==0)?"$currency {total_amount}":"{total_amount} $currency") ?></td>
										</tr>
									{/sales_report}
									<?php
										}
									?>
			                        </tbody>
			                    </table>
			                </div>
			            </div>
			            <div class="text-right"><?php echo $links?></div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
 <!-- Sales Report End -->
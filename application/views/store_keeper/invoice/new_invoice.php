<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<title>Struk</title>
		<link rel="stylesheet" href="<?=base_url("assets/css/styles_print.css")?>">
        <style>
            body {margin:0}
			#qrcode img {
				margin: 15px auto;
			}
			.article-cleans {
				margin-left: 100px; 
				margin-right: 10px; 
			}
			.img-size {
				width: 125px;
			}
			.text-simple {
				font-size: 12px;
				text-align: center;
				font-weight: bold;
			}
			.text-left {
				text-align: left !important;
			}
        </style>
	</head>
	<body>
		<div class="article-cleans" id="main">
			<br><br>
			<div class="container" style="border:none">
				<div class="row">
					<div class="col">
						<h1 class="ticket-title z" style="margin:0 auto 10px auto">
							<img class="img-size" src="<?=base_url("my-assets/image/logo/logo.jpg")?>">
						</h1>
						<div class="heading-contain">
							<h5>No. <?= $store_code ?>-<?= $invoice_no ?></h5>
						</div>
                        <br/><br/>
                        <div class="text-simple"><?=$store_address?></div>
                        <!-- <div class="text-simple"><?=$company_info[0]['mobile']?></div> -->
						<br/>
						<div class="casier_data">
							<div>
								<p style="font-size:12px;">CSR : <?=$user_name?></p>
							</div>
							<div>
								<p style="font-size:12px;"><?=$final_date?></p>
								<p style="font-size:12px; margin-top:-10px;"><?=$invoice_time?></p>
							</div>
						</div>
						<table class="table" cellspacing="0" border="0"> 
							<thead> 
								<tr>
									<th width="7%"><em>#</em></th> 
									<th width="41%" class="text-left">Product</th>
									<th width="7%"><?=display('quantity')?></th>
									<th width="25%"><?=display('rate')?></th>
									<th width="20%" align="right"><?=display('total')?></th> 
								</tr> 
							</thead>
							<tbody>
								<?php 
                                    $total_item_qty = 0; $formated = [];
									if (is_array($invoice_all_data) || is_object($invoice_all_data)){
										foreach ($invoice_all_data as $i=>$item){
											$formated[] = [
												"product"=>$item['product_name'],
												"quantity"=>$item['quantity'],
												"rate"=>$item['rate'],
												"total_price"=>$item['total_price'],
											];
											$total_item_qty += intval($item['quantity']);
											echo '<tr class="items">
												<td style="font-size:11px;">0'.$i.'</td>
												<td style="text-align: left; font-size:11px;">'.$item['product_name'].'</td>
												<td style="font-size:11px;">'.$item['quantity'].'</td>
												<td style="font-size:11px;">'.number_format(intval($item['rate']),0,',','.').'</td>
												<td style="font-size:11px;">'.number_format($item['total_price'],0,',','.').'</td>
											</tr>';
										}
									}
                                ?>
								<tr>
                                    <td style="font-size:11px;" colspan="4" class="left">Total Discount</td>
                                    <td style="font-size:11px;"><?=number_format($total_discount,0,',','.')?></td>
                                </tr>
								<tr>
									<td colspan="2" class="left" style="font-size:11px;">Total Items</td>
									<td style="font-size:11px;"><?=$total_item_qty?></td>
									<td style="font-size:11px;" class="left border-left">Total</td>
									<td style="font-size:11px;"><?=number_format($total_amount,0,',','.')?></td>
								</tr>
								
								
                                <tr>
                                    <td style="font-size:11px;" colspan="4" class="left">Paid Via <?= $payment_name ?></td>
                                    <td style="font-size:11px;"><?=number_format($paid_amount,0,',','.')?></td>
                                </tr>
								<?php if($payment_name != "Cash") { ?>
                                <tr>
                                    <td style="font-size:11px;" colspan="4" class="left">Card Number</td>
                                    <td style="font-size:11px;"><?= $payment_number ?></td>
                                </tr>
								<?php } ?>


								<?php if($due_amount<0) { ?>
                                <tr>
									<?php 
									$change = abs($due_amount);
									?>
                                    <td style="font-size:11px;" colspan="4" class="left">Return Change</td>
                                    <td style="font-size:11px;"><?=number_format($change,0,',','.')?></td>
                                </tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<br>
			<div class="text-simple">Pelanggan Yth.</div>
			<div class="text-simple">Terimakasih telah berbelanja di Kenan Hijab.</div>
			<br>
			<div class="text-simple">Barang Yang Sudah Dibeli Tidak Dapat Ditukar/Dikembalikan</div>
			<br>
			<div id="qrcode" class="text-center"></div>
			<div class="text-simple">WWW.KENANHIJAB.COM</div>
			<br><br><br><br>
		</div>

		<script src="<?php echo base_url("assets/qrcodejs/jquery.min.js")?>"></script>
		<script src="<?php echo base_url("assets/easyqr/dist/easy.qrcode.min.js") ?>"></script>
		<script>
			var qrcode = new QRCode(document.getElementById("qrcode"), {
				text: `{
					title: "<?=$company_info[0]['company_name']?>",
					id: "<?=str_pad($invoice_no, 6, '0', STR_PAD_LEFT)?>",
					casier: "<?=$user_name?>", 
					date: <?=$final_date?>,
					customer: <?=$customer_name?>,
				}`,
				logo: "<?=base_url("my-assets/image/logo/logo.jpg")?>",
				logoWidth: 125,
				logoHeight: 27,
				width: 185,
				height: 185,
				colorDark : "#000000",
				colorLight : "#ffffff",
				correctLevel : QRCode.CorrectLevel.H
			});
		</script>
    </body>
</html>
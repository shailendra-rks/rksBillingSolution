<!DOCTYPE html>
<html lang="en" class="fonfam">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|21|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);
		if($usrPerm['billV'] == 1){
			
			//sanitisation
			$billId = test_input($_POST["billId"]);
			
			require_once 'connection.php';
			
			$sql = "CALL getSiteSettings('".$usrParam['usrId']."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$siteSettings = mysqli_fetch_assoc($result);
			}
			mysqli_next_result($conn);
			
			$sql = "CALL printBillDetails('".$billId."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$billResult = mysqli_fetch_assoc($result);
			}
			mysqli_next_result($conn);
			
			$sql = "CALL printOtherDetails('".$billResult['outId']."','".$billResult['cstmrId']."','".$billResult['vhclId']."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$billDetails = mysqli_fetch_assoc($result);
			}
			mysqli_next_result($conn);
			
			$sql = "CALL printAddress('".$billResult['adrsOut']."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$adrsOut = mysqli_fetch_assoc($result);
			}
			mysqli_next_result($conn);
			
			$sql = "CALL printAddress('".$billResult['adrsCstmr']."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$adrsCstmr = mysqli_fetch_assoc($result);
			}
			mysqli_next_result($conn);
			
			if($billResult['adrsShipp'] != 0){
				$sql = "CALL printAddress('".$billResult['adrsShipp']."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$adrsShipp = mysqli_fetch_assoc($result);
				}
				mysqli_next_result($conn);
			}
			else{
				$adrsShipp = $adrsCstmr;
			}
			
			$sql = "CALL printBillItems('".$billId."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$i = 0 ;
				while($Item = mysqli_fetch_assoc($result)){
					$ItemList[$i] = $Item;
					$i += 1 ;
				}
			}
			mysqli_close($conn);
		}
		else{
			log_msg("DOM Manipulation Detected.|21|26|5");
			send_dashboard("Access Denied !!");
		}
	}
	else{
		log_msg("Incorrect URL Access.|21|30|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|21|35|3");
	send_home("Please, Log In !!");
}
?>
<head>
	<title>RKSBillingSolutions</title>
	<link rel="icon" href="/img/rks_logo.png">																							<!--address tag-->
<?php if($siteSettings['printMode'] == 1): ?>
	<link rel="stylesheet" href="/css/print.css">																						<!--address tag-->
<?php elseIf($siteSettings['printMode'] == 2): ?>
	<link rel="stylesheet" href="/css/print_half.css">																						<!--address tag-->
<?php elseIf($siteSettings['printMode'] == 3): ?>
	<link rel="stylesheet" href="/css/print_compact.css">																						<!--address tag-->
<?php endif; ?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://localhost/GSTBillingSite/clientJs/print.js"></script>																	<!--address tag-->	
</head>
<body>
<script type="text/javascript">
	var siteSettings= <?php echo json_encode($siteSettings); ?>;
</script>
<?php if($siteSettings['printMode'] < 3): ?>
<div class="print_view" id="original">
	<div class="print_section">
		<div class="bill_head">
			<div class="print_row">
				<div class="print_logo">
					<img src="/img/img_avatar.png" alt="profileImg">															<!--address tag-->
					<div class="txt_bold">
						<div class="txt_heading"><?php echo $billDetails['name']; ?></div>
						<div>GSTIN: <?php echo $billDetails['gstin']; ?></div>
						<div>State: <?php echo $adrsOut['state']; ?></div>
					</div>
				</div>
				<div class="txt_bold middle">
					<div class="txt_heading"><?php echo $billResult['billNum']; ?></div>
					<div><?php echo date("d/M/Y", strtotime($billResult['billdate'])); ?></div>
					<div>P.O.N. : -<?php echo $billResult['prchsOrdNum']; ?>-</div>
				</div>
			</div>
			<div class="on_hr middle txt_small txt_bold"><?php echo $adrsOut['address'].", ".$adrsOut['city'].", ".$adrsOut['state'].", ".$adrsOut['country'].", ".$adrsOut['pin'].", ".$billResult['contOut']; ?></div>
			<hr class="on_txt">
			<div class="float_centre"><b>TAX INVOICE</b></div>
			<hr>
			<div class="print_row">
				<div>
					<div><b>Customer Details</b></div>
					<div class="txt_bold">Name</div>
					<div><?php echo $billDetails['fName']; ?></div>
					<div class="txt_bold">GSTIN</div>
					<div><?php echo $billDetails['gstn']; ?></div>
				</div>
				<div class="middle">
					<div><b>Billing Address</b></div>
					<div><?php echo $adrsCstmr['address']; ?></div>
					<div><?php echo $adrsCstmr['city'].", ".$adrsCstmr['state']; ?></div>
					<div><?php echo $adrsCstmr['country'].", ".$adrsCstmr['pin']; ?></div>
					<div><?php echo $billResult['contCstmr']; ?></div>
				</div>
				<div class="last">
					<div><b>Shipping Address</b></div>
					<div><?php echo $adrsShipp['address']; ?></div>
					<div><?php echo $adrsShipp['city'].", ".$adrsShipp['state']; ?></div>
					<div><?php echo $adrsShipp['country'].", ".$adrsShipp['pin']; ?></div>
				</div>
			</div>
			<hr>
			<div class="print_row txt_small">
				<div>
					<div><b>Transport Company</b></div>
					<div><?php echo $billResult['transporter']; ?></div>
				</div>
				<div class="middle">
					<div><b>E-Way Number</b></div>
					<div>-<?php echo $billResult['eway']; ?>-</div>
				</div>
				<div class="last">
					<div><b><?php echo $usrInfo['unqFld1']; ?></b></div>
					<div>-<?php echo $billResult['unqFld1']; ?>-</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="bill_content">	
			<div class="bill_items">
				<table id="bill_table" style="width:100%">
					<thead>
						<tr>
						<?php if($siteSettings['printMode'] == 1): ?>
							<th class="first" style="width:32%">Item</th>
							<th style="width:10%">HSN</th>
							<th style="width:5%">qty</th>
							<th style="width:5%">rate</th>
						<?php elseif($siteSettings['printMode'] == 2): ?>
							<th class="first" style="width:35%">Item</th>
							<th style="width:7%">HSN</th>
							<th style="width:10%">qty*rate</th>
						<?php endif; ?>
							<th style="width:10%">Net</th>
							<th style="width:5%">@tax</th>
							<th style="width:7%">cgst</th>
							<th style="width:7%">sgst</th>
							<th style="width:7%">igst</th>
							<th class="last" style="width:12%">Amount</th>
						</tr>
						<tr>
							<td colspan="100%"><hr></td>
						</tr>
					</thead>
					<tbody>
					<?php $i=1; foreach($ItemList as $Item): ?>
						<tr class="txt_small">
							<td class="first"><?php echo $i.". ".$Item['name']."(".$Item['brand'].")"; ?></td>
							<td><?php echo 1*$Item['hsn']; ?></td>
						<?php if($siteSettings['printMode'] == 1): ?>
							<td><?php echo 1*$Item['qty']; ?></td>
							<td><?php echo 1*$Item['rate']; ?></td>
						<?php elseif($siteSettings['printMode'] == 2): ?>
							<td><?php echo (1*$Item['qty'])."*".(1*$Item['rate']); ?></td>
						<?php endif; ?>
							<td><?php echo 1*$Item['net']; ?></td>
							<td><?php echo 2*$Item['taxslab']; ?>%</td>
							<td><?php echo 1*$Item['cgst']; ?></td>
							<td><?php echo 1*$Item['sgst']; ?></td>
							<td><?php echo 1*$Item['igst']; ?></td>
							<td class="last"><?php echo 1*$Item['gross']; ?></td>
						</tr>
					<?php $i++; ?>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<table style="width:100%">
				<tfoot>
					<tr>
						<td class="first">@Freight Charges</td>
					<?php if($siteSettings['printMode'] == 1): ?>
						<td><?php echo 1*$billResult['fQty']; ?></td>
						<td><?php echo 1*$billResult['fRate']; ?></td>
					<?php elseif($siteSettings['printMode'] == 2): ?>
						<td colspan="2"><?php echo (1*$billResult['fQty'])."*".(1*$billResult['fRate']); ?></td>					
					<?php endif; ?>
						<td><?php echo 1*$billResult['fNet']; ?></td>
						<td><?php echo 2*$billResult['fTaxRt']; ?>%</td>
						<td><?php echo 1*$billResult['fcgst']; ?></td>
						<td><?php echo 1*$billResult['fsgst']; ?></td>
						<td><?php echo 1*$billResult['figst']; ?></td>
						<td class="last"><?php echo 1*$billResult['fgross']; ?></td>
					</tr>
					<tr>
						<td class="first" colspan="8">@Round Off</td>
						<td class="last">-<?php echo 1*$billResult['rndOff']; ?></td>
					</tr>
					<tr>
						<td colspan="100%"><hr></td>
					</tr>
					<tr>
						<th class="first" style="width:42%">Total</th>
						<th style="width:5%"></th>
						<th style="width:5%"></th>
						<th style="width:10%">₹<?php echo 1*($billResult['pdtnet'] + $billResult['fNet']); ?></th>
						<th style="width:5%"></th>
						<th style="width:7%"><?php echo 1*($billResult['pdtcgst'] + $billResult['fcgst']); ?></th>
						<th style="width:7%" ><?php echo 1*($billResult['pdtsgst'] + $billResult['fsgst']); ?></th>
						<th style="width:7%"><?php echo 1*($billResult['pdtigst'] + $billResult['figst']); ?></th>
						<th class="last" style="width:12%">₹<?php echo 1*$billResult['grand']; ?></th>
					</tr>
				</tfoot>
			</table>
			<hr>
			<div class="print_row">
				<div style="width:40%;">
					<div><b>Vehicle Details</b></div>
					<div><?php echo $billDetails['vNo']; ?></div>
					<div><?php echo $billDetails['vRep']; ?></div>
					<div><?php echo $billDetails['contactV']; ?></div>
				</div>
				<div class="txt_bold" style="width:60%;">
				<div class="print_row">
					<div class="first print_row">
						<div class="fixed_width">
							<div>Taxable Amount</div>
							<div>Total Tax</div>
							<div>Invoice Total</div>
						</div>
						<div>
							<div>:</div>
							<div>:</div>
							<div>:</div>
						</div>
					</div>			
					<div class="last">
						<div>₹<?php echo 1*($billResult['pdtnet'] + $billResult['fNet']); ?></div>
						<div>₹<?php echo 1*($billResult['pdtcgst'] + $billResult['fcgst'] + $billResult['pdtsgst'] + $billResult['fsgst'] + $billResult['pdtigst'] + $billResult['figst']); ?></div>
						<div style="display: flex;" class="numeral">₹<p id="numeral"><?php echo 1*$billResult['grand']; ?></p></div>
					</div>
				</div>
				<div class="last txt_small" id="words"></div>
				</div>
			</div>
			<hr>
			<div class="print_row">
				<div class="print_row" style="width:40%;">
					<div class="first print_row">
						<div class="fixed_width">
							<div><b>Bank Details</b></div>
							<div>Bank</div>
							<div>Branch</div>
							<div>IFSC</div>			
							<div>Account No.</div>
						</div>
						<div>
							<div>&nbsp;</div>
							<div>:</div>
							<div>:</div>
							<div>:</div>			
							<div>:</div>
						</div>
					</div>
					<div class="first">
						<div>&nbsp;</div>
						<div><?php echo $billDetails['bankName']; ?></div>
						<div><?php echo $billDetails['brnchName']; ?></div>
						<div><?php echo $billDetails['ifsc']; ?></div>
						<div><?php echo $billDetails['accNum']; ?></div>
					</div>
				</div>
				<div class="last bottom txt_small" style="width:60%;">
					<div>(for outlet name)</div>
					<div>Authorised Signatory</div>
				</div>
			</div>
			<div>&nbsp;</div>
			<div><b>Declaration</b></div>
			<div class="txt_small">1) Error and Omission in this invoice shall be subject to judrisdiction of <?php echo $adrsOut['city']; ?>.</div>
		</div>
		<div class="bill_foot">
			<hr>
			<div class="float_centre">
				<div>www.rksbillingsolutions.com</div>
			</div>
		</div>
	</div>
</div>
<?php elseif($siteSettings['printMode'] == 3): ?>
<div class="compact" id="compact">
<div class="print_view" id="original">
	<div class="print_section">
		<div class="bill_head">
			<div class="print_row">
				<div class="print_logo">
					<img src="/img/img_avatar.png" alt="profileImg">															<!--address tag-->
					<div class="txt_bold">
						<div class="txt_heading"><?php echo $billDetails['name']; ?></div>
						<div>GSTIN: <?php echo $billDetails['gstin']; ?></div>
						<div>State: <?php echo $adrsOut['state']; ?></div>
					</div>
				</div>
				<div class="txt_bold middle">
					<div class="txt_heading"><?php echo $billResult['billNum']; ?></div>
					<div><?php echo date("d/M/Y", strtotime($billResult['billdate'])); ?></div>
					<div>P.O.N. : -<?php echo $billResult['prchsOrdNum']; ?>-</div>
				</div>
			</div>
			<div class="on_hr middle txt_small txt_bold"><?php echo $adrsOut['address'].", ".$adrsOut['city'].", ".$adrsOut['state'].", ".$adrsOut['country'].", ".$adrsOut['pin'].", ".$billResult['contOut']; ?></div>
			<hr class="on_txt">
			<div class="float_centre"><b>TAX INVOICE</b></div>
			<hr>
			<div class="print_row">
				<div>
					<div><b>Customer Details</b></div>
					<div class="txt_bold">Name</div>
					<div><?php echo $billDetails['fName']; ?></div>
					<div class="txt_bold">GSTIN</div>
					<div><?php echo $billDetails['gstn']; ?></div>
				</div>
				<div class="last">
					<div><b>Billing Address</b></div>
					<div><?php echo $adrsCstmr['address']; ?></div>
					<div><?php echo $adrsCstmr['city'].", ".$adrsCstmr['state']; ?></div>
					<div><?php echo $adrsCstmr['country'].", ".$adrsCstmr['pin']; ?></div>
					<div><?php echo $billResult['contCstmr']; ?></div>
				</div>
			</div>
			<hr>
			<div class="print_row">
				<div>
					<div><b>Transport Company</b></div>
					<div><?php echo $billResult['transporter']; ?></div>
					<div><b>E-Way Number</b>-<?php echo $billResult['eway']; ?>-</div>
					<div><b><?php echo $usrInfo['unqFld1']; ?></b>-<?php echo $billResult['unqFld1']; ?>-</div>
				</div>
				<div class="last">
					<div><b>Vehicle Details</b></div>
					<div><?php echo $billDetails['vNo']; ?></div>
					<div><?php echo $billDetails['vRep']; ?></div>
					<div><?php echo $billDetails['contactV']; ?></div>
				</div>
			</div>
		</div>
		<hr>
		<div class="bill_content">	
			<div class="bill_items">
				<table id="bill_table" style="width:100%">
					<thead>
						<tr>
							<th class="first" style="width:58%">Item<label class="txt_mini"> @HSN</label></th>
							<th style="width:10%">qty*rate</th>
							<th style="width:10%">Net</th>
							<th style="width:12%">tax</th>
							<th class="last" style="width:10%">Amount</th>
						</tr>
						<tr>
							<td colspan="100%"><hr></td>
						</tr>
					</thead>
					<tbody>
					<?php $i=1; foreach($ItemList as $Item): ?>
						<tr class="txt_small">
							<td class="first"><?php echo $i.". ".$Item['name']."(".$Item['brand'].")"; ?><label class="txt_mini"> @<?php echo 1*$Item['hsn']; ?></label></td>
							<td><?php echo (1*$Item['qty'])."*".(1*$Item['rate']); ?></td>
							<td><?php echo 1*$Item['net']; ?></td>
							<td><?php echo 1*($Item['cgst'] + $Item['sgst'] + $Item['igst']); ?><label class="txt_mini"> @<?php echo 2*$Item['taxslab']; ?>%</label></td>
							<td class="last"><?php echo 1*$Item['gross']; ?></td>
						</tr>
					<?php $i++; ?>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<table style="width:100%">
				<tfoot>
					<tr>
						<td class="first">@Freight Charges</td>
						<td colspan="2"><?php echo (1*$billResult['fQty'])."*".(1*$billResult['fRate']); ?></td>	
						<td><?php echo 1*$billResult['fNet']; ?></td>
						<td><?php echo 1*($billResult['fcgst'] + $billResult['fsgst'] + $billResult['figst']); ?><label class="txt_mini"> @<?php echo 2*$billResult['fTaxRt']; ?>%</label></td>
						<td class="last"><?php echo 1*$billResult['fgross']; ?></td>
					</tr>
					<tr>
						<td class="first" colspan="5">@Round Off</td>
						<td class="last">-<?php echo 1*$billResult['rndOff']; ?></td>
					</tr>
					<tr>
						<td colspan="100%"><hr></td>
					</tr>
					<tr>
						<th class="first" style="width:58%">Total</th>
						<th style="width:10%"></th>
						<th style="width:10%">₹<?php echo 1*($billResult['pdtnet'] + $billResult['fNet']); ?></th>
						<th style="width:12%"><?php echo 1*($billResult['pdtcgst'] + $billResult['fcgst'] + $billResult['pdtsgst'] + $billResult['fsgst'] + $billResult['pdtigst'] + $billResult['figst']); ?></th>
						<th class="last" style="width:10%">₹<?php echo 1*$billResult['grand']; ?></th>
					</tr>
				</tfoot>
			</table>
			<hr>
			<div class="print_row">
				<div>
					<div><b>Shipping Address</b></div>
					<div><?php echo $adrsShipp['address']; ?></div>
					<div><?php echo $adrsShipp['city'].", ".$adrsShipp['state']; ?></div>
					<div><?php echo $adrsShipp['country'].", ".$adrsShipp['pin']; ?></div>
				</div>
				<div class="txt_bold">
					<div class="print_row txt_bold">
						<div class="first print_row">
							<div>
								<div>Taxable Amount:</div>
								<div>Total Tax:</div>
								<div>Invoice Total:</div>
							</div>
						</div>			
						<div class="last">
							<div>₹<?php echo 1*($billResult['pdtnet'] + $billResult['fNet']); ?></div>
							<div>₹<?php echo 1*($billResult['pdtcgst'] + $billResult['fcgst'] + $billResult['pdtsgst'] + $billResult['fsgst'] + $billResult['pdtigst'] + $billResult['figst']); ?></div>
							<div style="display: flex;" class="numeral">₹<p id="numeral"><?php echo 1*$billResult['grand']; ?></p></div>
						</div>
					</div>
					<div class="last txt_small" id="words"></div>
				</div>
			</div>
			<hr>
			<div class="print_row">
				<div class="first print_row">
					<div>
						<div><b>Bank Details</b></div>
						<div>Bank</div>
						<div>Branch</div>
						<div>IFSC</div>			
						<div>Account No.</div>
					</div>
					<div>
						<div>&nbsp;</div>
						<div>:</div>
						<div>:</div>
						<div>:</div>			
						<div>:</div>
					</div>
				</div>
				<div class="last">
					<div>&nbsp;</div>
					<div><?php echo $billDetails['bankName']; ?></div>
					<div><?php echo $billDetails['brnchName']; ?></div>
					<div><?php echo $billDetails['ifsc']; ?></div>
					<div><?php echo $billDetails['accNum']; ?></div>
				</div>
			</div>
			<div><b>Declaration</b></div>
			<div class="txt_small">1) Error and Omission in this invoice shall be subject to judrisdiction of <?php echo $adrsOut['city']; ?>.</div>
		</div>
		<div class="bill_foot">
			<hr>
			<div class="float_centre">
				<div>www.rksbillingsolutions.com</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php endif; ?>
</body>
</html>
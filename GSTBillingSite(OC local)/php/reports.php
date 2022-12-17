<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|24|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	if($usrPerm['billV'] == 1){
		if($_SESSION['currentPage'] != "reports.php"){
			$_SESSION['currentPage'] = "reports.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
		
		$flag = 0;
		
		require_once 'connection.php';
		
		$sql = "CALL getOutletList('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			$i = 0 ;
			while($out = mysqli_fetch_assoc($result)){
				$outList[$i] = $out;
				$i += 1 ;
			}
		}
		mysqli_next_result($conn);
		
		$sql = "CALL getCstmrList('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			$i = 0 ;
			while($cstmr = mysqli_fetch_assoc($result)){
				$cstmrList[$i] = $cstmr;
				$i += 1 ;
			}
		}
		mysqli_next_result($conn);
		
		$sql = "CALL getPdtList('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			$i = 0 ;
			while($pdt = mysqli_fetch_assoc($result)){
				$pdtList[$i] = $pdt;
				$i += 1 ;
			}
		}
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			//sanitisation		
			$fromDate = isset($_POST["billSDate"]) ? $_POST["billSDate"] : date("Y-m-d", strtotime("-1 month", strtotime(date("Y-m-d"))));
			$toDate = isset($_POST["billEDate"]) ? $_POST["billEDate"] : date("Y-m-d");
			$fromDate = test_input($fromDate);
			$toDate = test_input($toDate);
			$report_type = test_input($_POST["report_type"]);
			$outlet = test_input($_POST["outlet"]);
			$pdt = isset($_POST["pdt"]) ? $_POST["pdt"] : 0;
			$pdt = test_input($pdt);
			$cstmr = isset($_POST["cstmr"]) ? $_POST["cstmr"] : 0;
			$cstmr = test_input($cstmr);
		
			if(strtotime($fromDate) > strtotime($toDate)){			
				$tempDate = $fromDate;
				$fromDate = $toDate;
				$toDate = $tempDate;
			}
			
			foreach($outList as $out){
				if($out['id'] == $outlet ){
					$outname =  $out['name'];
				}
			}			
			$pdtname = "All";
			$cstmrname = "All";
							
			mysqli_next_result($conn);
			
			if($report_type == 1){
				$sql = "CALL getReportsByOutlet('".$usrParam['usrId']."','".$outlet."','".$fromDate."','".$toDate."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$i=0;
					while($billData = mysqli_fetch_assoc($result)){
						$billDataList[$i] = $billData;
						$i += 1 ;
					}
					$flag = 1;
				}
				else{					
					$flag = 3;
				}
			}
			elseif($report_type == 2){
				
				foreach($cstmrList as $item){
					if($item['id'] == $cstmr ){
						$cstmrname =  $item['fname'];
					}
				}
				
				$sql = "CALL getReportsByCustomer('".$usrParam['usrId']."','".$outlet."','".$fromDate."','".$toDate."','".$cstmr."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$i=0;
					while($billData = mysqli_fetch_assoc($result)){
						$billDataList[$i] = $billData;
						$i += 1 ;
					}
					$flag = 1;
				}
				else{					
					$flag = 3;
				}
			}
			elseif($report_type == 3){
				
				foreach($pdtList as $item){
					if($item['id'] == $pdt ){
						$pdtname =  $item['name'];
					}
				}
				
				$sql = "CALL getReportsByProduct('".$usrParam['usrId']."','".$outlet."','".$fromDate."','".$toDate."','".$pdt."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$i=0;
					while($billData = mysqli_fetch_assoc($result)){
						$billDataList[$i] = $billData;
						$i += 1 ;
					}
					$flag = 1;
				}
				else{					
					$flag = 3;
				}
			}
		}
	}
	else{
		log_msg("DOM Manipulation Detected.|24|151|5");
		send_dashboard("Access Denied !!");
	}
}
else{
	log_msg("Unauthrised Access.|24|156|3");
	send_home("Please, Log In !!");
}
?>
<div class="home-section">
<?php if($_SESSION['internalMsg'] != "Logged In"): ?>
	<div class="alertdiv">
		<span id="alert" class="msg position-fixed"><?php echo $_SESSION['internalMsg']; ?></span>
	</div>
<?php endif; ?>
	<div class="pagehead">
		<h3>Generate Reports</h3>
		<p>by selecting report-type and following parameters.</p>
	</div>
	<hr style="margin-block: 2px;">
	<div>
		<form class="date_range" id="getBillsForm" method="post" action="reports.php">
			<div class="inline-flex-justified">
				<div class="fix_input">
					<label for="report_type"><b>Reports By: &nbsp;</b></label>
					<select id="report_type" name="report_type" required onchange="setSelectMenu(this.value)">
						<option value="1"><?php echo $usrInfo['outType']; ?></option>
						<option value="2">Customer</option>
						<option value="3">Product</option>
					</select>
				</div>
				<div class="fix_input">
					<label for="outSelect"><b><?php echo $usrInfo['outType']; ?>: &nbsp;</b></label>
					<select id="outSelect" name="outlet" required>
						<?php if($usrParam['usrType'] == "user"): ?>
							<?php foreach($outList as $out): ?>		
								<?php if($out['id'] == $usrInfo['outletId'] ): ?>
									<option value='<?php echo $out['id']; ?>'><?php echo $out['name']; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
						
						<option value="" readonly selected>Select <?php echo $usrInfo['outType']; ?></option>					
					<!--generating outlet list-->
						<?php foreach($outList as $out): ?>						
							<option value='<?php echo $out['id']; ?>'><?php echo $out['name']; ?></option>
						<?php endforeach; ?>
						
						<?php endif; ?>
					</select>
				</div>
				<div class="fix_input">
					<label for="cstmrSelect"><b>Customer:</b> &nbsp;</label>
					<select id="cstmrSelect" name="cstmr" disabled>
						<option value="" readonly selected>Select Customer</option>
				
					<!--generating cstmr list-->
				
						<?php foreach($cstmrList as $cstmr): ?>
							<option value='<?php echo $cstmr['id']; ?>'><?php echo $cstmr['fname']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="fix_input">
					<label for="pdtSelect"><b>Product:</b> &nbsp;</label>
					<select id="pdtSelect" name="pdt" disabled>
						<option value="" readonly selected>Select Product</option>
		
						<!--generating product list-->
		
						<?php foreach($pdtList as $pdt): ?>
							<option value='<?php echo $pdt['id']; ?>'><?php echo $pdt['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="inline-flex-justified" style="width: 77%">
				<div class="fix_input">
					<label for="billSDate"><b>Date From: &nbsp;</b></label>
					<input type="date" name="billSDate"required>
				</div>
				<div class="fix_input">
					<label for="billEDate"><b> To: &nbsp;</b></label>
					<input type="date" name="billEDate"required>
				</div>
				<div class="tab_top">
					<button type="submit" id="getBill" form="getBillsForm">
						<span class="admin_links">Generate Report</span>
					</button>
				</div>
				<div>&nbsp;</div>
			</div>
			<?php if($flag == 1 && $report_type != 3): ?>
			<hr style="margin-block: 2px;">
			<fieldset class="inline-flex">
				<div class="fix_input">
					<label for=""><b>Report View: &nbsp;</b></label>
				</div>
				<div>
					<input type="radio" name="rView" value="0" checked>
					<label for="Active">Separate Freight and Product Cost</label>
					&nbsp;
					<input type="radio" name="rView" value="1">
					<label for="Inactive">Add Freight and Product Cost</label>
				</div>
			</fieldset>
			<?php endif; ?>
		</form>
	</div>
	<hr style="margin-block: 2px;">
<?php if($flag == 1): ?>
	<div class="table_container">
		<table id="data_table" class="table table-striped order-column hover" style="width:100%">
			<thead>
				<tr>
					<td colspan="16"><b>Report for <?php echo $usrInfo['outType'].": ".$outname.", Customer: ".$cstmrname.", Product: ".$pdtname." from ".$fromDate." to ".$toDate; ?>.</b></td>
				</tr>
				<tr>
					<th hidden></th>
					<th hidden></th>
					<th>Sr.No.</th>
					<th><?php echo $usrInfo['outType'] ?></th>
					<th>Date</th>
					<th>Bill No.</th>
					<th>Customer</th>
					<?php if($usrPerm['vhclV'] == 1): ?>
						<th><?php echo $usrInfo['vType'] ?></th>
					<?php endif; ?>
					<th><?php echo $usrInfo['unqFld1'] ?></th>
					<?php if($usrInfo['biltyCust'] == 1 || $report_type == 3): ?>
						<th>Qty</th>
						<th>Rate</th>
					<?php endif; ?>
					<?php if($report_type != 3): ?>
						<th class="div_data">Freight Cost</th>
						<th class="div_data">F.C.(+tax)</th>
					<?php endif; ?>
					<th class="div_data">Product Cost</th>
					<th class="div_data">P.C.(+tax)</th>
					<?php if($report_type != 3): ?>
						<th class="div_data">Total</th>
						<th class="mix_data">Amount</th>
						<th class="mix_data">Amount(+tax)</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
			<?php $i=1; foreach($billDataList as $billData): ?>
				<tr>
					<td hidden></td>
					<td hidden></td>
					<td><?php echo $i; ?></td>
					<td><?php echo $outname; ?></td>
					<td><?php echo $billData['billdate']; ?></td>
					<td><?php echo $billData['billNum']; ?></td>
					<td><?php echo $billData['ctmrId']; ?></td>
					<?php if($usrPerm['vhclV'] == 1): ?>
						<td><?php echo $billData['vclId']; ?></td>
					<?php endif; ?>
					<td><?php echo $billData['unqFld1']; ?></td>
					<?php if($usrInfo['biltyCust'] == 1 || $report_type == 3): ?>
						<td><?php echo 1*$billData['qty']; ?></td>
						<td><?php echo 1*$billData['rate']; ?></td>
					<?php endif; ?>
					<?php if($report_type != 3): ?>
						<td class="div_data"><?php echo 1*$billData['fnet']; ?></td>
						<td class="div_data"><?php echo 1*$billData['fgross']; ?></td>
					<?php endif; ?>
					<td class="div_data"><?php echo 1*$billData['pdtnet']; ?></td>
					<td class="div_data"><?php echo 1*$billData['pdtgross']; ?></td>
					<?php if($report_type != 3): ?>
						<td class="div_data"><?php echo 1*$billData['grand']; ?></td>
						<td class="mix_data"><?php echo (1*($billData['fnet'] + $billData['pdtnet'])); ?></td>
						<td class="mix_data"><?php echo 1*$billData['grand']; ?></td>
					<?php endif; ?>
				</tr>
			<?php $i++; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php elseif($flag == 0): ?>
	<div class="alertdiv">
		<span class="msg position-fixed">Please Select Above Options.</span>
	</div>
<?php elseif($flag == 3): ?>
	<div class="alertdiv">
		<span class="msg position-fixed">No Records Found!</span>
	</div>
<?php endif; ?>
</div>
</html>
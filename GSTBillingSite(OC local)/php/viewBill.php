<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|21|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	if($usrPerm['billV'] == 1){
		if($_SESSION['currentPage'] != "viewBill.php"){
			$_SESSION['currentPage'] = "viewBill.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
		
		//sanitisation		
		$fromDate = isset($_POST["billSDate"]) ? $_POST["billSDate"] : date("Y-m-d", strtotime("-1 month", strtotime(date("Y-m-d"))));
		$toDate = isset($_POST["billEDate"]) ? $_POST["billEDate"] : date("Y-m-d", strtotime("+1 day", strtotime(date("Y-m-d"))));
		$fromDate = test_input($fromDate);
		$toDate = test_input($toDate);
		
		if(strtotime($fromDate) > strtotime($toDate)){			
			$tempDate = $fromDate;
			$fromDate = $toDate;
			$toDate = $tempDate;
		}
	}
	else{
		log_msg("DOM Manipulation Detected.|21|36|5");
		send_dashboard("Access Denied !!");
	}
}
else{
	log_msg("Unauthrised Access.|21|41|3");
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
		<h3>Manage Bills</h3>
		<p>Add/View/Edit Bills</p>
	</div>
	<hr>
	<form class="date_range inline-flex-justified" id="getBillsForm" method="post" action="viewBill.php">
	<div>
		<label for="billSDate"><b>Select From Date: </b></label>
		<input type="date" name="billSDate"required>
		&nbsp;&nbsp;
		<label for="billEDate"><b> To Date: </b></label>
		<input type="date" name="billEDate"required>
		&nbsp;&nbsp;
		<button type="submit" id="getBill" form="getBillsForm">
			<span class="admin_links">Go</span>
		</button>
	</div>
	<div>
		<button type="submit" id="bulkPrint" form="bulkPrintForm" disabled>
			<span class="admin_links">Bulk Print</span>
		</button>
	</div>
	</form>
	<hr style="margin-block: 2px;">
	<div class="control_group">
<?php if($usrPerm['billC'] == 1): ?>
		<button type="submit" id="addBill" onclick="addBill()">
			<i class="bx bx-plus-circle">
				</i>
			<span class="admin_links">Create</span>
		</button>
<?php endif; ?>
	</div>
	<div class="table_container">
	<table id="data_table" class="table table-striped order-column hover" style="width:100%">
        <thead>
            <tr>
				<th></th>
				<?php if($usrPerm['billE'] == 1): ?>
					<th>Edit</th>
				<?php endif; ?>
				<?php if($usrPerm['billV'] == 1): ?>
					<th>Print</th>
				<?php endif; ?>
                <th>Bill No.</th>
				<th>Date</th>
				<th><?php echo $usrInfo['outType'] ?></th>
				<th>Customer</th>
				<?php if($usrPerm['vhclV'] == 1): ?>
					<th><?php echo $usrInfo['vType'] ?></th>
				<?php endif; ?>
				<th><?php echo $usrInfo['unqFld1'] ?></th>
                <th>Amount</th>
                <th>Incl. All Tax</th>
				<?php if($usrPerm['biltyV'] == 1): ?>
					<th>Bilty</th>
				<?php endif; ?>
                <th>Added By</th>				
            </tr>
        </thead>
        <tbody>
		<?php 
		
		// get bill details for owner
		
		require_once 'connection.php';
		$sql = "CALL getBillInfo('".$usrParam['usrId']."','".$fromDate."','".$toDate."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			while($billData = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
				echo "<td><input type='checkbox' onclick='setBulkPrint(this.checked,".$billData['id'].")'></td>";
				if($usrPerm['billE'] == 1){
					if($billData['editPerm'] == 1){
						echo "<td><a role='button' href='javascript: editBill(".$billData['id'].")' id='editLink[".$billData['id']."]'><i class='bx bx-edit'></i></a></td>";
					}
					else{
						echo "<td></td>";
					}
				}	
				if($usrPerm['billV'] == 1){				
					echo "<td><a role='button' href='javascript: printBill(".$billData['id'].")' id='printLink[".$billData['id']."]'><i class='bx bx-printer'></i></a></td>";
				}
				echo "<td>".$billData['billNum']."</td>";
				echo "<td>".$billData['billdate']."</td>";
				echo "<td>".$billData['otId']."</td>";
				echo "<td>".$billData['ctmrId']."</td>";
				if($usrPerm['vhclV'] == 1){
					echo "<td>".$billData['vclId']."</td>";
				}
				echo "<td>".$billData['unqFld1']."</td>";
				echo "<td>".(1*($billData['pdtnet'] + $billData['fnet']))."</td>";
				echo "<td>".(1*$billData['grand'])."</td>";
				if($usrPerm['biltyV'] == 1){
					echo "<td>".$billData['biltyId']."</td>";
				}
				echo "<td>".$billData['creator']."</td>";
				echo "</tr>";
			}
		}
		mysqli_close($conn);
		?>
		</tbody>
	</table>
	</div>
	<div id="form" style="visibility: hidden;">
		<form id="addBillForm" action="addBill.php" method="post">
			<input type="hidden" id="mode" name="action" value="">
			<input type="hidden" id="refData" name="dataId" value="">
		</form>
		<form id="printBillForm" action="print.php" method="post" target="printResult" onsubmit="window.open('','printResult');">
			<input type="hidden" id="print_bill_num" name="billId" value="">
		</form>
		<form id="bulkPrintForm" action="bulkPrint.php" method="post" target="bulkPrintResult" onsubmit="window.open('','bulkPrintResult');">
			
		</form>
	</div>
</div>
</html>
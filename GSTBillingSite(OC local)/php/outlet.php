<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|15|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	if($usrPerm['Moutlet'] == 1){
		if($_SESSION['currentPage'] != "outlet.php"){
			$_SESSION['currentPage'] = "outlet.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
	}
	else{
		log_msg("DOM Manipulation Detected.|15|25|5");
		send_dashboard("Access Denied !!!");
	}
}
else{
	log_msg("Unauthrised Access.|15|30|3");
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
		<h3>Manage <?php echo $usrInfo['outType']; ?>s</h3>
		<p>Add/View/Edit <?php echo $usrInfo['outType']; ?>s</p>
	</div>	
	<div class="control_group">
<?php if($usrInfo['outCount'] < $usrInfo['numOutlet']): ?>
		<button id="addOutlet">
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
				<th hidden></th>
				<th>Edit</th>
                <th>Active</th>
                <th>Name</th>
                <th>Contact</th>
				<th>Bill Start</th>
				<th>Bill Count</th>
                <th>Address</th>
				<th>District</th>
                <th>State</th>
            </tr>
        </thead>
        <tbody>
		<?php 
		// get vehicle details for owner
		
		require_once 'connection.php';
		$sql = "CALL getOutletInfo('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			while($outletData = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
				echo "<td hidden>".$outletData['id']."</td>";
				echo "<td><a role='button' href='javascript: openEditModal(".$outletData['id'].")' id='editLink[".$outletData['id']."]'><i class='bx bx-edit'></i></a></td>";
				echo status_html($outletData['isActive']);
				echo "<td>".$outletData['name']."</td>";
				echo "<td>".$outletData['contactP']."</td>";
				echo "<td>".$outletData['billStartNum']."</td>";
				echo "<td>".$outletData['grossBillCount']."</td>";
				echo "<td>".$outletData['address']."</td>";
				echo "<td>".$outletData['city']."</td>";
				echo "<td>".$outletData['state']."</td>";
				echo "</tr>";
			}
		}
		mysqli_close($conn);
		?>
		</tbody>
	</table>
	</div>
	<div id="outletModal"></div>
</div>
</html>
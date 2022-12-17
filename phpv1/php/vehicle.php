<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|20|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	if($usrPerm['vhclV'] == 1){
		if($_SESSION['currentPage'] != "vehicle.php"){
			$_SESSION['currentPage'] = "vehicle.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
	}
	else{
		log_msg("DOM Manipulation Detected.|20|25|5");
		send_dashboard("Access Denied !!!");
	}
}
else{
	log_msg("Unauthrised Access.|20|30|3");
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
		<h3>Manage <?php echo $usrInfo['vType'] ?>s</h3>
		<p>Add/View <?php echo $usrInfo['vType'] ?>s</p>
	</div>	
	<div class="control_group">
<?php if($usrPerm['vhclC'] == 1): ?>
		<button id="addVehicle">
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
<?php if($usrPerm['vhclE'] == 1): ?>
				<th>Edit</th>
<?php endif; ?>
                <th>Active</th>
                <th><?php echo $usrInfo['vType'] ?> Number</th>
                <th><?php echo $usrInfo['vType'] ?> Owner/Driver</th>
				<th>Contact</th>
                <th>Added By</th>
            </tr>
        </thead>
        <tbody>
		<?php 
		// get vehicle details for owner
		
		require_once 'connection.php';
		$sql = "CALL getVehicleInfo('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			while($vehicleData = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
				echo "<td hidden>".$vehicleData['id']."</td>";
				if($usrPerm['vhclE'] == 1){
					echo "<td><a role='button' href='javascript: openEditModal(".$vehicleData['id'].")' id='editLink[".$vehicleData['id']."]'><i class='bx bx-edit'></i></a></td>";
				}
				echo status_html($vehicleData['isActive']);
				echo "<td>".$vehicleData['vNo']."</td>";
				echo "<td>".$vehicleData['vRep']."</td>";
				echo "<td>".$vehicleData['contactV']."</td>";
				echo "<td>".$vehicleData['creator']."</td>";
				echo "</tr>";
			}
		}
		mysqli_close($conn);
		?>
		</tbody>
	</table>
	</div>
	<div id="vehicleModal"></div>
</div>
</html>
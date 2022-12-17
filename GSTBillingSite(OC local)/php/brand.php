<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|8|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	if($usrPerm['brandV'] == 1){
		if($_SESSION['currentPage'] != "brand.php"){
			$_SESSION['currentPage'] = "brand.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
	}
	else{
		log_msg("DOM Manipulation Detected.|8|25|5");
		send_dashboard("Access Denied !!!");
	}
}
else{
	log_msg("Unauthrised Access.|8|30|3");
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
		<h3>Manage <?php echo $usrInfo['bType']; ?>s</h3>
		<p>Add/View/Edit <?php echo $usrInfo['bType']; ?>s</p>
	</div>	
	<div class="control_group">
<?php if($usrPerm['brandC'] == 1): ?>
		<button id="addBrand">
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
<?php if($usrPerm['brandE'] == 1): ?>
				<th>Edit</th>
<?php endif; ?>
                <th>Active</th>
                <th>Name</th>
                <th><?php echo $usrInfo['bIdentifier']; ?></th>
                <th>Contact</th>
                <th>Location</th>
                <th>Added By</th>
            </tr>
        </thead>
        <tbody>
		<?php 
		// get vehicle details for owner
		
		require_once 'connection.php';
		$sql = "CALL getBrandInfo('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			while($brandData = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
				echo "<td hidden>".$brandData['id']."</td>";
				if($usrPerm['brandE'] == 1){
					echo "<td><a role='button' href='javascript: openEditModal(".$brandData['id'].")' id='editLink[".$brandData['id']."]'><i class='bx bx-edit'></i></a></td>";
				}
				echo status_html($brandData['isActive']);
				echo "<td>".$brandData['bName']."</td>";
				echo "<td>".$brandData['bIdentity']."</td>";
				echo "<td>".$brandData['bContact']."</td>";
				echo "<td>".$brandData['bPlace']."</td>";
				echo "<td>".$brandData['creator']."</td>";
				echo "</tr>";
			}
		}
		mysqli_close($conn);
		?>
		</tbody>
	</table>
	</div>
	<div id="brandModal"></div>
</div>
</html>
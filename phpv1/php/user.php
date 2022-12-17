<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|19|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	if($usrPerm['Muser'] == 1){
		if($_SESSION['currentPage'] != "user.php"){
			$_SESSION['currentPage'] = "user.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
	}
	else{
		log_msg("DOM Manipulation Detected.|19|25|5");
		send_dashboard("Access Denied !!!");
	}
}
else{
	log_msg("Unauthrised Access.|19|30|3");
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
		<h3>Manage Users</h3>
		<p>View/Edit Users</p>
	</div>	
	<div class="control_group">
<?php if($usrInfo['usrCount'] < $usrInfo['numUser']): ?>
		<button id="addUser">
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
				<th rowspan="2" hidden></th>
				<th rowspan="2">Edit</th>
                <th rowspan="2">Active</th>
                <th rowspan="2">Name</th>
				
				<?php if($usrPerm['billV'] == 1): ?>
					<th colspan="3">Manage Bill</th>
				<?php endif; ?>
				
				<?php if($usrPerm['biltyV'] == 1): ?>
					<th colspan="3">Manage Bilty</th>
				<?php endif; ?>
				
				<th colspan="3">Manage Product</th>
				<th colspan="3">Manage Customer</th>
				
				<?php if($usrPerm['vhclV'] == 1): ?>
					<th colspan="3">Manage <?php echo $usrInfo['vType']; ?></th>
				<?php endif; ?>
				
				<?php if($usrPerm['brandV'] == 1): ?>
					<th colspan="3">Manage <?php echo $usrInfo['bType']; ?></th>
				<?php endif; ?>
            </tr>
			<tr>
				<?php if($usrPerm['billV'] == 1): ?>
					<th><i class='bx bxs-binoculars'></i></th>
					<th><i class='bx bxs-plus-circle'></i></th>
					<th><i class='bx bxs-edit'></i></th>
				<?php endif; ?>
				
				<?php if($usrPerm['biltyV'] == 1): ?>
					<th><i class='bx bxs-binoculars'></i></th>
					<th><i class='bx bxs-plus-circle'></i></th>
					<th><i class='bx bxs-edit'></i></th>
				<?php endif; ?>
				
				<th><i class='bx bxs-binoculars'></i></th>
				<th><i class='bx bxs-plus-circle'></i></th>
				<th><i class='bx bxs-edit'></i></th>
				<th><i class='bx bxs-binoculars'></i></th>
				<th><i class='bx bxs-plus-circle'></i></th>
				<th><i class='bx bxs-edit'></i></th>
				
				<?php if($usrPerm['vhclV'] == 1): ?>
					<th><i class='bx bxs-binoculars'></i></th>
					<th><i class='bx bxs-plus-circle'></i></th>
					<th><i class='bx bxs-edit'></i></th>
				<?php endif; ?>
				
				<?php if($usrPerm['brandV'] == 1): ?>
					<th><i class='bx bxs-binoculars'></i></th>
					<th><i class='bx bxs-plus-circle'></i></th>
					<th><i class='bx bxs-edit'></i></th>
				<?php endif; ?>
			</tr>
        </thead>
        <tbody>
		<?php 
		// get vehicle details for owner
		
		require_once 'connection.php';
		$sql = "CALL getUserInfo('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			while($userData = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
				echo "<td hidden>".$userData['unqId']."</td>";
				echo "<td><a role='button' href='javascript: openEditModal(".$userData['unqId'].")' id='editLink[".$userData['unqId']."]'><i class='bx bx-edit'></i></a></td>";
				echo status_html($userData['isActive']);
				echo "<td>".$userData['name']."</td>";
				
				if($usrPerm['billV'] == 1){
					echo status_html($userData['billV']);
					echo status_html($userData['billC']);
					echo status_html($userData['billE']);
				}
				
				if($usrPerm['biltyV'] == 1){
					echo status_html($userData['biltyV']);
					echo status_html($userData['biltyC']);
					echo status_html($userData['biltyE']);
				}
				
				
				echo status_html($userData['pdtV']);
				echo status_html($userData['pdtC']);
				echo status_html($userData['pdtE']);
				echo status_html($userData['cstmrV']);
				echo status_html($userData['cstmrC']);
				echo status_html($userData['cstmrE']);
				
				if($usrPerm['vhclV'] == 1){
					echo status_html($userData['vhclV']);
					echo status_html($userData['vhclC']);
					echo status_html($userData['vhclE']);
				}
				
				if($usrPerm['brandV'] == 1){
					echo status_html($userData['brandV']);
					echo status_html($userData['brandC']);
					echo status_html($userData['brandE']);
				}
				echo "</tr>";
			}
		}
		mysqli_close($conn);
		?>
		</tbody>
	</table>
	</div>
	<div id="userModal"></div>
</div>
</html>
<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|17|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	if($usrPerm['pdtV'] == 1){
		if($_SESSION['currentPage'] != "product.php"){
			$_SESSION['currentPage'] = "product.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
	}
	else{
		log_msg("DOM Manipulation Detected.|17|25|5");
		send_dashboard("Access Denied !!!");
	}
}
else{
	log_msg("Unauthrised Access.|17|30|3");
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
		<h3>Manage Products</h3>
		<p>Add/View/Edit Products</p>
	</div>	
	<div class="control_group">
<?php if($usrPerm['pdtC'] == 1): ?>
		<button id="addProduct">
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
<?php if($usrPerm['pdtE'] == 1): ?>
				<th>Edit</th>
<?php endif; ?>
                <th>Active</th>
                <th>Name</th>
                <th>HSN Number</th>
                <th><?php echo $usrInfo['bType']; ?></th>
                <th>Rate</th>
				<th>CGST</th>
				<th>SGST</th>
				<th>IGST</th>
                <th>Added By</th>
            </tr>
        </thead>
        <tbody>
		<?php 
		// get vehicle details for owner
		
        require_once 'connection.php';
		$sql = "CALL getProductInfo('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			while($productData = mysqli_fetch_assoc($result))
			{
				$IGST = 2 * $productData['cgst'];
				echo "<tr>";
				echo "<td hidden>".$productData['id']."</td>";
				if($usrPerm['pdtE'] == 1){
					echo "<td><a role='button' href='javascript: openEditModal(".$productData['id'].")' id='editLink[".$productData['id']."]'><i class='bx bx-edit'></i></a></td>";
				}
				echo status_html($productData['isActive']);
				echo "<td>".$productData['name']."</td>";
				echo "<td>".$productData['hsn']."</td>";
				echo "<td>".$productData['brand']."</td>";
				echo "<td>".$productData['rate']."</td>";
				echo "<td>".$productData['cgst']."</td>";
				echo "<td>".$productData['cgst']."</td>";
				echo "<td>".$IGST."</td>";
				echo "<td>".$productData['creator']."</td>";
				echo "</tr>";
			}
		}
		mysqli_close($conn);
		?>
		</tbody>
	</table>
	</div>
	<div id="productModal"></div>
</div>
</html>
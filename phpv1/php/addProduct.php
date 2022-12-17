<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|5|5|3");
	send_home("Please, Log In !!");
	}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);
		
		//sanitisation
		$action = test_input($_POST["action"]);
		$pStatus = test_input($_POST['pStatus']);
		$brand = test_input($_POST["brand"]);
		$pname = test_input($_POST["pname"]);
		$hsn = test_input($_POST['hsn']);
		$rate = test_input($_POST['rate']);
		$cgst = test_input($_POST['cgst']);
	
		if($action == "add" && $usrPerm['pdtC'] == 1){
			require_once 'connection.php';
			$sql = "CALL setProductInfo('".$usrParam['usrId']."','".$pStatus."','".$brand."','".$pname."','".$hsn."','".$rate."','".$cgst."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$brandResult = mysqli_fetch_assoc($result);
				if($brandResult['true']=="true"){
					$_SESSION['internalMsg'] = "Data added successfully !!";
					log_msg("Product data added.|5|30|2");
				}
			}
			else{
				$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
				log_msg("Product data add failed.|5|35|3");
			}
			mysqli_close($conn);
		}
		elseif($action == "edit" && $usrPerm['pdtE'] == 1){
			
			//sanitisation
			$dataId = test_input($_POST["dataId"]);
			
			require_once 'connection.php';
			$sql = "CALL updateProductInfo('".$usrParam['usrId']."','".$dataId."','".$pStatus."','".$brand."','".$pname."','".$hsn."','".$rate."','".$cgst."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$brandResult = mysqli_fetch_assoc($result);
				if($brandResult['true']=="true"){
					$_SESSION['internalMsg'] = "Data updated successfully !!";
					log_msg("Product data updated.|5|51|2");
				}
			}
			else{
				$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
				log_msg("Product data update failed.|5|56|3");
			}
			mysqli_close($conn);
		}
		else{
			$_SESSION['internalMsg'] = "Access Denied !!";
			log_msg("DOM Manipulation Detected.|5|62|5");
		}
		send_to($_SESSION['currentPage']);
	}
	else{
		log_msg("Incorrect URL Access.|5|67|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|5|72|3");
	send_home("Please, Log In !!");
}
?>
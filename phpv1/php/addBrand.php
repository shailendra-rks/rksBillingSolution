<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|2|5|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true"){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);	
		$usrPerm = unserialize($_SESSION['usrPerm']);
		
		//sanitisation
		$action = test_input($_POST["action"]);
		$bStatus = test_input($_POST['bStatus']);
		$bname = test_input($_POST["bname"]);
		$bIdentity = test_input($_POST["bIdentity"]);
		$pcontact = test_input($_POST['pcontact']);
		$location = test_input($_POST['location']);
		
		if($action == "add" && $usrPerm['brandC'] == 1){
			require_once 'connection.php';
			$sql = "CALL setBrandInfo('".$usrParam['usrId']."','".$bStatus."','".$bname."','".$bIdentity."','".$pcontact."','".$location."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$brandResult = mysqli_fetch_assoc($result);
				if($brandResult['true']=="true"){
					$_SESSION['internalMsg'] = "".$usrInfo['bType']." added successfully !!";
					log_msg("Brand data added.|2|30|2");
				}
			}
			else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
					log_msg("Brand data add failed.|2|35|3");
			}
			mysqli_close($conn);
		}
		elseif($action == "edit" && $usrPerm['brandE'] == 1){
			
			//sanitisation
			$dataId = test_input($_POST["dataId"]);
			
			require_once 'connection.php';
			$sql = "CALL updateBrandInfo('".$usrParam['usrId']."','".$dataId."','".$bStatus."','".$bname."','".$bIdentity."','".$pcontact."','".$location."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$brandResult = mysqli_fetch_assoc($result);
				if($brandResult['true']=="true"){
					$_SESSION['internalMsg'] = "".$usrInfo['bType']." updated successfully !!";
					log_msg("Brand data edited.|2|51|2");
				}
			}
			else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
					log_msg("Brand data update failed.|2|56|3");
			}
			mysqli_close($conn);
		}
		else{
			$_SESSION['internalMsg'] = "Access Denied !!";
			log_msg("DOM Manipulation Detected.|2|62|5");
		}
		send_to($_SESSION['currentPage']);	
	}
	else{
		log_msg("Incorrect URL Access.|2|67|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|2|75|3");
	send_home("Please, Log In !!");
	}
?>
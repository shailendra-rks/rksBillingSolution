<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|7|5|3");
	send_home("Please, Log In !!");
	}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);
		
		//sanitisation
		$action = test_input($_POST["action"]);
		$vname = test_input($_POST["vname"]);
		$vrep = test_input($_POST["vrep"]);
		$contact = test_input($_POST['vcontact']);
		$vstatus = test_input($_POST['vStatus']);
			
		if($action == "add" && $usrPerm['vhclC'] == 1){
			require_once 'connection.php';
			$sql = "CALL setVehicleInfo('".$usrParam['usrId']."','".strtoupper($vname)."','".$vrep."','".$contact."','".$vstatus."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$vehicleResult = mysqli_fetch_assoc($result);
				if($vehicleResult['true']=="true"){
					$_SESSION['internalMsg'] = "".$usrInfo['vType']." added successfully !!!";
					log_msg("Vehicle data added.|7|29|2");
				}
			}
			else{
				$_SESSION['internalMsg'] = "Oops! Something went wrong !!!";
				log_msg("Vehicle data add failed.|7|34|3");
			}
			mysqli_close($conn);
		}
		elseif($action == "edit" && $usrPerm['vhclE'] == 1){
			
			//sanitisation
			$dataId = test_input($_POST["dataId"]);
		
			require_once 'connection.php';
			$sql = "CALL updateVehicleInfo('".$usrParam['usrId']."','".$dataId."','".strtoupper($vname)."','".$vrep."','".$contact."','".$vstatus."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$vehicleResult = mysqli_fetch_assoc($result);
				if($vehicleResult['true']=="true"){
					$_SESSION['internalMsg'] = "".$usrInfo['vType']." updated successfully !!!";
					log_msg("Vehicle data updated.|7|50|2");
				}
			}
			else{
				$_SESSION['internalMsg'] = "Oops! Something went wrong !!!";
				log_msg("Vehicle data update failed.|7|55|3");
			}
			mysqli_close($conn);
		}
		else{
			$_SESSION['internalMsg'] = "Access Denied !!";
			log_msg("DOM Manipulation Detected.|7|61|5");
		}
		send_to($_SESSION['currentPage']);
	}
	else{
		log_msg("Incorrect URL Access.|7|66|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|7|71|3");
	send_home("Please, Log In !!");
}
?>
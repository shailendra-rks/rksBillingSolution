<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|3|5|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true" ){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);
		$adrsId = "";
		
		//sanitisation
		$action = test_input($_POST["action"]);
		$adrsStatus = test_input($_POST['adrsStatus']);
		$cStatus = test_input($_POST["cStatus"]);
		$cname = test_input($_POST["cname"]);
		$gstn = test_input($_POST['gstn']);
		$pcontact = test_input($_POST['pcontact']);
		$scontact = test_input($_POST['scontact']);
		$ctype = test_input($_POST['ctype']);
		$address = test_input($_POST['address']);
		$state = test_input($_POST['state']);
		$city = test_input($_POST['city']);
		$pin = test_input($_POST['pin']);
		$country = test_input($_POST['country']);
		
		if($action == "add" && $usrPerm['cstmrC'] == 1){
			require_once 'connection.php';
			
			$sql = "CALL setAddress('".$address."','".$state."','".$city."','".$pin."','".$country."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$adrsResult = mysqli_fetch_assoc($result);
				if($adrsResult['status'] == "true"){
					log_msg("Customer new address added.|3|37|2");
					$adrsId = $adrsResult['id'];
					mysqli_next_result($conn);
		
					$sql = "CALL setCustomerInfo('".$usrParam['usrId']."','".$ctype."','".$cname."','".strtoupper($gstn)."','".$pcontact."','".$scontact."','".$adrsId."','".$cStatus."')";
					$result = mysqli_query($conn, $sql);
					if($result && mysqli_num_rows($result) > 0){
						$customerResult = mysqli_fetch_assoc($result);
						if($customerResult['true']=="true"){
							$_SESSION['internalMsg'] = "Customer/Party added successfully !!";
							log_msg("Customer data added.|3|47|2");
						}
					}
					else{
						$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
						log_msg("Customer data add failed.|3|51|3");
					}
					mysqli_close($conn);
				}
			}
			else{
				log_msg("Customer new address add failed.|3|58|3");
				$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
			}
		}
		elseif($action == "edit" && $usrPerm['cstmrE'] == 1){
			require_once 'connection.php';
			$flag = "";
				
			if($adrsStatus == 1){
				$sql = "CALL setAddress('".$address."','".$state."','".$city."','".$pin."','".$country."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$adrsResult = mysqli_fetch_assoc($result);
					if($adrsResult['status'] == "true"){
						$adrsId = $adrsResult['id'];
						$flag = "true";
						log_msg("Customer new address added.|3|74|2");
					}
					else{
						$flag = "false";
					}
					mysqli_next_result($conn);
				}
				else{
					log_msg("Customer new address add failed.|3|82|3");
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
				}
			}
			elseif($adrsStatus == 0){
				
				//sanitisation
				$adrsId = test_input($_POST["adrsId"]);
					
				$sql = "CALL updateAddress('".$adrsId."','".$address."','".$state."','".$city."','".$pin."','".$country."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$adrsResult = mysqli_fetch_assoc($result);
					if($adrsResult['status'] == "true"){
						$flag = "true";
						log_msg("Customer address updated.|3|97|2");
					}
					else{
						$flag = "false";
					}
					mysqli_next_result($conn);
				}	
				else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
					log_msg("Customer address update failed.|3|106|3");
				}
			}
			if($flag == "true"){
				
				//sanitisation
				$dataId = test_input($_POST["dataId"]);
					
				$sql = "CALL updateCustomerInfo('".$usrParam['usrId']."','".$dataId."','".$ctype."','".$cname."','".strtoupper($gstn)."','".$pcontact."','".$scontact."','".$adrsId."','".$cStatus."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$customerResult = mysqli_fetch_assoc($result);
					if($customerResult['true']=="true"){
						$_SESSION['internalMsg'] = "Data updated successfully !!";
						log_msg("Customer updated.|3|120|2");
					}
				}
				else{
					$_SESSION['internalMsg'] = "Please Contact Administrator.";
					log_msg("MISMATCH DETECTED.|3|125|4");
				}
			}
			elseif($flag == "false"){
				$_SESSION['internalMsg'] = "Oops! Something went wrong !!";					
			}
			mysqli_close($conn);
		}
		else{
			$_SESSION['internalMsg'] = "Access Denied !!";
			log_msg("DOM Manipulation Detected.|3|135|5");
		}
		send_to($_SESSION['currentPage']);
	}
	else{
		log_msg("Incorrect URL Access.|3|140|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|3|145|3");
	send_home("Please, Log In !!");
	}
?>
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|4|5|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true"){
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);		
		
		//sanitisation
		$action = test_input($_POST["action"]);
		$adrsStatus = test_input($_POST['adrsStatus']);
		$oStatus = test_input($_POST["oStatus"]);
		$oname = test_input($_POST["oname"]);
		$gstin = test_input($_POST['gstin']);
		$contP = test_input($_POST['contP']);
		$contS = test_input($_POST['contS']);
		$descp = test_input($_POST['descp']);
		$bank = test_input($_POST['bank']);
		$ifsc = test_input($_POST['ifsc']);
		$accNum = test_input($_POST['accNum']);
		$branch = test_input($_POST['branch']);
		$address = test_input($_POST['address']);
		$state = test_input($_POST['state']);
		$city = test_input($_POST['city']);
		$pin = test_input($_POST['pin']);
		$country = test_input($_POST['country']);
		
		$renew = isset($_POST["renew"]) ? 1 : 0 ;
		if(isset($_POST["billStart"])){
			$billStart = test_input($_POST["billStart"]);
		}
		else{
			$billStart = "";
		}
		
		if($usrPerm['Moutlet'] == 1){
			$adrsId = "";
			if($action == "add" && $usrInfo['outCount'] < $usrInfo['numOutlet']){
				require_once 'connection.php';
				
				$sql = "CALL setAddress('".$address."','".$state."','".$city."','".$pin."','".$country."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$adrsResult = mysqli_fetch_assoc($result);
					if($adrsResult['status'] == "true"){
						log_msg("Outlet new address added.|4|51|2");
						$adrsId = $adrsResult['id'];
						mysqli_next_result($conn);
				
						$sql = "CALL setOutletInfo('".$usrParam['usrId']."','".$oStatus."','".$billStart."','".$oname."','".strtoupper($gstin)."','".$contP."','".$contS."','".$descp."','".$adrsId."','".$bank."',
								'".$ifsc."','".$accNum."','".$branch."')";
						$result = mysqli_query($conn, $sql);
						if($result && mysqli_num_rows($result) > 0){
							$outletResult = mysqli_fetch_assoc($result);
							if($outletResult['true']=="true"){
								$_SESSION['internalMsg'] = "".$usrInfo['outType']." added successfully !!!";
								log_msg("Outlet data added.|4|62|2");
							}
						}	
						else{
							log_msg("Outlet data add failed.|4|66|3");
							$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
						}
						mysqli_next_result($conn);
				
						// generate user informatoin and site parameters
			
						$sql = "CALL getUsrInfo('".$usrParam['usrId']."')";
						$result = mysqli_query($conn, $sql);
						if($result && mysqli_num_rows($result) > 0){
							$usrInfoData = mysqli_fetch_assoc($result);
							$_SESSION['usrInfo'] = serialize($usrInfoData);
							log_msg("userinfo reset.|4|78|1");
						}
						mysqli_close($conn);
					}
				}
				else{
					log_msg("Outlet new address add failed.|4|84|3");
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
				}
			}
			elseif($action == "edit"){
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
							log_msg("Outlet new address added.|4|100|2");
						}
						else{
							$flag = "false";
						}
						mysqli_next_result($conn);
					}	
					else{
						log_msg("Outlet new address add failed.|4|108|3");
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
							log_msg("Outlet address updated.|4|123|2");
						}
						else{
							$flag = "false";
						}
						mysqli_next_result($conn);
					}	
					else{
						$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
						log_msg("Outlet address update failed.|4|132|3");
					}	
				}
				if($flag == "true"){
					
					//sanitisation
					$dataId = test_input($_POST["dataId"]);
					
					$sql = "CALL updateOutletInfo('".$dataId."','".$oStatus."','".$renew."','".$billStart."','".$oname."','".strtoupper($gstin)."','".$contP."','".$contS."','".$descp."','".$adrsId."',
							'".$bank."','".$ifsc."','".$accNum."','".$branch."')";
					$result = mysqli_query($conn, $sql);
					if($result && mysqli_num_rows($result) > 0){
						$outletResult = mysqli_fetch_assoc($result);
						if($outletResult['true']=="true"){
							$_SESSION['internalMsg'] = "".$usrInfo['outType']." updated successfully !!";
							log_msg("Outlet updated.|4|147|2");
						}
					}
					else{
						$_SESSION['internalMsg'] = "Please Contact Administrator.";
						log_msg("MISMATCH DETECTED.|4|152|4");
					}
				}
				elseif($flag == "false"){
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";					
				}
				mysqli_close($conn);			
			}
		}
		else{
			$_SESSION['internalMsg'] = "Access Denied !!";
			log_msg("DOM Manipulation Detected.|4|163|5");
		}
		send_to($_SESSION['currentPage']);
	}
	else{
		log_msg("Incorrect URL Access.|4|168|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|4|173|3");
	send_home("Please, Log In !!");
	}
?>
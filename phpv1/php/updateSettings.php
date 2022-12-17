<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|23|5|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true"){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);	
		$usrPerm = unserialize($_SESSION['usrPerm']);
		
		//sanitisation
		$gstrRule = test_input($_POST["gstrRule"]);
		$printStyle = test_input($_POST['printStyle']);
		$printCopy = test_input($_POST["printCopy"]);
		
		$renew = isset($_POST["renew"]) ? 1 : 0 ;
		if(isset($_POST["resetPsw"])){
			$psw = $_POST["resetPsw"];
		}
		else{
			$psw = "";
		}
		$hash = password_hash( $psw, PASSWORD_DEFAULT );
		
		require_once 'connection.php';
		$sql = "CALL updateSiteSettings('".$usrParam['usrId']."','".$gstrRule."','".$printStyle."','".$printCopy."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			$rslt = mysqli_fetch_assoc($result);
			if($rslt['true']=="true"){
				$_SESSION['internalMsg'] = "Site Settings Updated Successfully !!";
				log_msg("Site Settings Updated.|23|35|2");
			
				if($renew == 1){
					mysqli_next_result($conn);
				
					$sql = "CALL updatePassword('".$usrParam['usrId']."','".$hash."')";
					$result = mysqli_query($conn, $sql);
					if($result && mysqli_num_rows($result) > 0){
						$rslt = mysqli_fetch_assoc($result);
						if($rslt['true']=="true"){
							$_SESSION['internalMsg'] = "Site Settings & Password Updated Successfully !!";
							log_msg("Password Updated.|23|46|2");
						}
					}
					else{
						$_SESSION['internalMsg'] = "Site Settings Updated Successfully. Password Reset Failed!!)";
						log_msg("Password Update failed.|23|51|3");
					}
				}
			}
		}
		else{
			$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
			log_msg("Site Settings Update failed.|23|58|3");
		}
		mysqli_close($conn);
		send_to($_SESSION['currentPage']);	
	}
	else{
		log_msg("Incorrect URL Access.|23|64|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|23|69|3");
	send_home("Please, Log In !!");
	}
?>
<?php
session_start();
include('functions.php');
if(isset($_POST["uname"]) && isset($_POST["psw"])){
	$userId = test_input($_POST["uname"]);
	$userPsw = test_input($_POST["psw"]);
	$_SESSION['isUsrLoggedIn'] = "false";
	
	//sanitisation code
	
	require_once 'connection.php';
	
// validating user credentials
	
	$sql = "CALL authUser('".$userId."')";
	$result = mysqli_query($conn, $sql);
	if($result && mysqli_num_rows($result) > 0){
		$usrData = mysqli_fetch_assoc($result);
		$valid = password_verify ( $userPsw, $usrData['usrPsw'] );
		if($valid){
			$_SESSION['isUsrLoggedIn'] = "true";
			mysqli_next_result($conn);
			
// generate user authentication parameters 
			
			$sql = "CALL getUsrAuth('".$userId."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$usrParamData = mysqli_fetch_assoc($result);
				$_SESSION['usrParam'] = serialize($usrParamData);
				mysqli_next_result($conn);
			}
			
			if($usrParamData['isActive'] == 1){
				// generate user informatoin and site parameters
			
				$sql = "CALL getUsrInfo('".$userId."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$usrInfoData = mysqli_fetch_assoc($result);
					$_SESSION['usrInfo'] = serialize($usrInfoData);
					mysqli_next_result($conn);
				}
			
				// generate user permissions 
			
				$sql = "CALL getUsrPerm('".$userId."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$usrPermData = mysqli_fetch_assoc($result);
					$_SESSION['usrPerm'] = serialize($usrPermData);
				}
				mysqli_close($conn);
				
				log_msg("Logged In.|12|55|0");
				send_dashboard("Logged In");
			}
			else{
				session_unset();
				session_destroy();
				log_msg("Subscription Expired.|12|61|0");
				send_home("Subscription Expired !!");
			}
		}
		else{
			mysqli_close($conn);
			send_home("Invalid Credentials !!");
		}
	}
	else{
		mysqli_close($conn);
		send_home("Invalid Credentials !!");
	}
}
else{
		send_home("Invalid Credentials !!");
	}
?>
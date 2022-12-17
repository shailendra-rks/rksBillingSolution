<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|6|5|3");
	send_home("Please, Log In !!");
	}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);

		//sanitisation
		$action = test_input($_POST["action"]);
		$uStatus = test_input($_POST["uStatus"]);
		$uname = test_input($_POST["uname"]);
		$contP = test_input($_POST['contP']);
		$outlet = test_input($_POST['outlet']);

		//renew password logic
		
		$renew = isset($_POST["renew"]) ? 1 : 0 ;
		if(isset($_POST["psw"])){
			$psw = $_POST["psw"];
		}
		else{
			$psw = "";
		}
		$hash = password_hash( $psw, PASSWORD_DEFAULT );
		
		// set permissions
		
		$billV = isset($_POST["billV"]) ? 1 : 0 ;
		$billC = isset($_POST["billC"]) ? 1 : 0 ;
		$billE = isset($_POST["billE"]) ? 1 : 0 ;
		
		$biltyV = isset($_POST["biltyV"]) ? 1 : 0 ;
		$biltyC = isset($_POST["biltyC"]) ? 1 : 0 ;
		$biltyE= isset($_POST["biltyE"]) ? 1 : 0 ;
		
		$pdtV = isset($_POST["pdtV"]) ? 1 : 0 ;
		$pdtC = isset($_POST["pdtC"]) ? 1 : 0 ;
		$pdtE = isset($_POST["pdtE"]) ? 1 : 0 ;
		
		$cstmrV = isset($_POST["cstmrV"]) ? 1 : 0 ;
		$cstmrC = isset($_POST["cstmrC"]) ? 1 : 0 ;
		$cstmrE = isset($_POST["cstmrE"]) ? 1 : 0 ;
		
		$vhclV = isset($_POST["vhclV"]) ? 1 : 0 ;
		$vhclC = isset($_POST["vhclC"]) ? 1 : 0 ;
		$vhclE = isset($_POST["vhclE"]) ? 1 : 0 ;
		
		$brandV = isset($_POST["vhclC"]) ? 1 : 0 ;
		$brandC = isset($_POST["vhclC"]) ? 1 : 0 ;
		$brandE = isset($_POST["vhclC"]) ? 1 : 0 ;
		
		if($usrPerm['Muser'] == 1){
			if($action == "add" && $usrInfo['usrCount'] < $usrInfo['numUser']){
				$user_name = "user".($usrInfo['usrCount'] + 1)."";
				$logId = $user_name."@".$usrParam['usrId'];
				require_once 'connection.php';
				$sql = "CALL setUserInfo('".$usrParam['usrId']."','".$logId."','".$uStatus."','".$uname."','".$hash."','".$contP."','".$outlet."','".$billV."','".$billC."','".$billE."','".$biltyV."','".$biltyC."',
				'".$biltyE."','".$pdtV."','".$pdtC."','".$pdtE."','".$cstmrV."','".$cstmrC."','".$cstmrE."','".$vhclV."','".$vhclC."','".$vhclE."','".$brandV."','".$brandC."','".$brandE."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$userResult = mysqli_fetch_assoc($result);
					if($userResult['true']=="true"){
						$_SESSION['internalMsg'] = "User added successfully !!";
						log_msg("User data added.|6|70|2");
					}
				}
				else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
					log_msg("User data add failed.|6|75|3");
				}
				mysqli_next_result($conn);
				
				// generate user informatoin and site parameters
				
				$sql = "CALL getUsrInfo('".$usrParam['usrId']."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$usrInfoData = mysqli_fetch_assoc($result);
					$_SESSION['usrInfo'] = serialize($usrInfoData);
					log_msg("userinfo reset.|6|86|1");
				}
				mysqli_close($conn);
			}
			elseif($action == "edit"){
				
				//sanitisation
				$dataId = test_input($_POST["dataId"]);
			
				require_once 'connection.php';
				$sql = "CALL updateUserInfo('".$dataId."','".$uStatus."','".$uname."','".$renew."','".$hash."','".$contP."','".$outlet."','".$billV."','".$billC."','".$billE."','".$biltyV."','".$biltyC."',
				'".$biltyE."','".$pdtV."','".$pdtC."','".$pdtE."','".$cstmrV."','".$cstmrC."','".$cstmrE."','".$vhclV."','".$vhclC."','".$vhclE."','".$brandV."','".$brandC."','".$brandE."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$userResult = mysqli_fetch_assoc($result);
					if($userResult['true']=="true"){
						$_SESSION['internalMsg'] = "User updated successfully !!";
						log_msg("User data updated.|6|103|2");
					}
				}
				else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
					log_msg("User data update failed.|6|108|3");
				}
				mysqli_close($conn);
			}
		}
		else{
			$_SESSION['internalMsg'] = "Access Denied !!";
			log_msg("DOM Manipulation Detected.|6|115|5");
		}
		send_to($_SESSION['currentPage']);
	}
	else{
		log_msg("Incorrect URL Access.|6|120|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|6|125|3");
	send_home("Please, Log In !!");
	}
?>
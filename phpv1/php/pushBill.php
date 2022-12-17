<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	send_home("Please, Log In !!");
	}
elseif($_SESSION['isUsrLoggedIn'] == "true"){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);
		$billId = "";
		$flag = "";
		
		//sanitisation
		$action = test_input($_POST["action"]);
		$shipAdrState = test_input($_POST['shipAdrState']);
		$billDate = test_input($_POST["billDate"]);
		$purOrdNum = test_input($_POST["purOrdNum"]);
		$outlet = test_input($_POST['outlet']);
		$cstmr = test_input($_POST['cstmr']);
		$vhcl = test_input(isset($_POST['vhcl']) ? $_POST['vhcl'] : "");
		$unq1 = test_input($_POST['unq1']);
		$trnsprtr = test_input(isset($_POST['trnsprtr']) ? $_POST['trnsprtr'] : "");
		$ewayNum = test_input(isset($_POST['ewayNum']) ? $_POST['ewayNum'] : "");
		$fqty = test_input($_POST['fqty']);
		$frate = test_input($_POST['frate']);
		$famt = test_input($_POST['famt']);
		$ftaxslab = test_input($_POST['ftaxslab']);
		$fcgst = test_input($_POST['fcgst']);
		$fsgst = test_input($_POST['fsgst']);
		$figst = test_input($_POST['figst']);
		$fgross = test_input($_POST['fgross']);
		$rndOff = test_input($_POST['rndOff']);
		$grand = test_input($_POST['grand']);
		$billgross = test_input($_POST['billgross']);
				
		if($action == "add" && $usrPerm['billC'] == 1){
			if($usrInfo['biltyCust'] == 1){
		
				//sanitisation
				$amt = test_input($_POST['amt']);
				$cgst = test_input($_POST['cgst']);
				$sgst = test_input($_POST['sgst']);
				$igst = test_input($_POST['igst']);
				$pdt = test_input($_POST['pdt']);
				$unit = test_input($_POST['unit']);
				$qty = test_input($_POST['qty']);
				$rate = test_input($_POST['rate']);
				$taxslab = test_input($_POST['taxslab']);
		
				require_once 'connection.php';
			
				$sql = "CALL createBill('".$usrParam['usrId']."','".$billDate."','".$purOrdNum."','".$outlet."','".$cstmr."','".$vhcl."','".$unq1."','".$trnsprtr."','".$ewayNum."','".$amt."','".$cgst."','".$sgst."',
						'".$igst."','".$billgross."','".$fqty."','".$frate."','".$famt."','".$ftaxslab."','".$fcgst."','".$fsgst."','".$figst."','".$fgross."','".$rndOff."','".$grand."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$billResult = mysqli_fetch_assoc($result);
					if($billResult['status'] == "true"){
						$billId = $billResult['id'];
						mysqli_next_result($conn);

						$sql = "CALL setBillItems('".$billId."','".$pdt."','".$unit."','".$qty."','".$rate."','".$amt."','".$taxslab."','".$cgst."','".$sgst."','".$igst."','".$billgross."')";
						$result = mysqli_query($conn, $sql);
						if($result && mysqli_num_rows($result) > 0){
							$customerResult = mysqli_fetch_assoc($result);
							if($customerResult['true']=="true"){
								$_SESSION['internalMsg'] = "Bill created successfully !!";
								$flag = 1;
							}
						}
						else{
							$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
						}
					}
				}
				else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
				}
				mysqli_next_result($conn);				
			}
			
			if($usrInfo['biltyCust'] == 0){
		
				//sanitisation
				$billamt = test_input($_POST['billamt']);
				$billcgst = test_input($_POST['billcgst']);
				$billsgst = test_input($_POST['billsgst']);
				$billigst = test_input($_POST['billigst']);
		
				require_once 'connection.php';
			
				$sql = "CALL createBill('".$usrParam['usrId']."','".$billDate."','".$purOrdNum."','".$outlet."','".$cstmr."','".$vhcl."','".$unq1."','".$trnsprtr."','".$ewayNum."','".$billamt."','".$billcgst."',
						'".$billsgst."','".$billigst."','".$billgross."','".$fqty."','".$frate."','".$famt."','".$ftaxslab."','".$fcgst."','".$fsgst."','".$figst."','".$fgross."','".$rndOff."','".$grand."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$billResult = mysqli_fetch_assoc($result);
					if($billResult['status'] == "true"){
						$billId = $billResult['id'];
						mysqli_next_result($conn);
						
						for($i=0; $i < count($_POST['pdtid']); $i++){
							
							//sanitisation
							$pdtid = test_input($_POST['pdtid'][$i]);
							$pdtunit = test_input($_POST['pdtunit'][$i]);
							$pdtqty = test_input($_POST['qty'][$i]);
							$pdtrate = test_input($_POST['rate'][$i]);
							$pdtamt = test_input($_POST['amt'][$i]);
							$pdttaxslab = test_input($_POST['taxslab'][$i]);
							$pdtcgst = test_input($_POST['cgst'][$i]);
							$pdtsgst = test_input($_POST['sgst'][$i]);
							$pdtigst = test_input($_POST['igst'][$i]);
							$pdtgross = test_input($_POST['gross'][$i]);
							
							$sql = "CALL setBillItems('".$billId."','".$pdtid."','".$pdtunit."','".$pdtqty."','".$pdtrate."','".$pdtamt."','".$pdttaxslab."','".$pdtcgst."','".$pdtsgst."','".$pdtigst."','".$pdtgross."')";
							$result = mysqli_query($conn, $sql);
							if($result && mysqli_num_rows($result) > 0){
								$customerResult = mysqli_fetch_assoc($result);
								if($customerResult['true']=="true"){
									$_SESSION['internalMsg'] = "Bill created successfully !!";
									$flag = 1;
								}
							}
							else{
								$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
							}
							mysqli_next_result($conn);
						}
					}
				}
				else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
				}
				mysqli_next_result($conn);
			}		
		
		}
		elseif($action == "edit" && $usrPerm['billE'] == 1){
			
			//sanitisation
			$dataId = test_input($_POST['dataId']);			
			
			if($usrInfo['biltyCust'] == 1){
		
				//sanitisation
				$amt = test_input($_POST['amt']);
				$cgst = test_input($_POST['cgst']);
				$sgst = test_input($_POST['sgst']);
				$igst = test_input($_POST['igst']);
				$pdt = test_input($_POST['pdt']);
				$unit = test_input($_POST['unit']);
				$qty = test_input($_POST['qty']);
				$rate = test_input($_POST['rate']);
				$taxslab = test_input($_POST['taxslab']);
		
				require_once 'connection.php';
			
				$sql = "CALL updateBill('".$usrParam['usrId']."','".$dataId."','".$billDate."','".$purOrdNum."','".$outlet."','".$cstmr."','".$vhcl."','".$unq1."','".$trnsprtr."','".$ewayNum."','".$amt."','".$cgst."',
						'".$sgst."','".$igst."','".$billgross."','".$fqty."','".$frate."','".$famt."','".$ftaxslab."','".$fcgst."','".$fsgst."','".$figst."','".$fgross."','".$rndOff."','".$grand."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$billResult = mysqli_fetch_assoc($result);
					if($billResult['status'] == "true"){
						$billId = $billResult['id'];
						mysqli_next_result($conn);

						$sql = "CALL updateBillItems('".$billId."','".$dataId."','".$pdt."','".$unit."','".$qty."','".$rate."','".$amt."','".$taxslab."','".$cgst."','".$sgst."','".$igst."','".$billgross."')";
						$result = mysqli_query($conn, $sql);
						if($result && mysqli_num_rows($result) > 0){
							$customerResult = mysqli_fetch_assoc($result);
							if($customerResult['true']=="true"){
								$_SESSION['internalMsg'] = "Bill created successfully !!";
								$flag = 1;
							}
						}
						else{
							$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
						}
					}
				}
				else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
				}
				mysqli_next_result($conn);				
			}
			
			if($usrInfo['biltyCust'] == 0){
		
				//sanitisation
				$billamt = test_input($_POST['billamt']);
				$billcgst = test_input($_POST['billcgst']);
				$billsgst = test_input($_POST['billsgst']);
				$billigst = test_input($_POST['billigst']);
		
				require_once 'connection.php';
			
				$sql = "CALL updateBill('".$usrParam['usrId']."','".$dataId."','".$billDate."','".$purOrdNum."','".$outlet."','".$cstmr."','".$vhcl."','".$unq1."','".$trnsprtr."','".$ewayNum."','".$billamt."','".$billcgst."',
						'".$billsgst."','".$billigst."','".$billgross."','".$fqty."','".$frate."','".$famt."','".$ftaxslab."','".$fcgst."','".$fsgst."','".$figst."','".$fgross."','".$rndOff."','".$grand."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$billResult = mysqli_fetch_assoc($result);
					if($billResult['status'] == "true"){
						$billId = $billResult['id'];
						mysqli_next_result($conn);
						
						for($i=0; $i < count($_POST['pdtid']); $i++){
							
							//sanitisation
							$pdtid = test_input($_POST['pdtid'][$i]);
							$pdtunit = test_input($_POST['pdtunit'][$i]);
							$pdtqty = test_input($_POST['qty'][$i]);
							$pdtrate = test_input($_POST['rate'][$i]);
							$pdtamt = test_input($_POST['amt'][$i]);
							$pdttaxslab = test_input($_POST['taxslab'][$i]);
							$pdtcgst = test_input($_POST['cgst'][$i]);
							$pdtsgst = test_input($_POST['sgst'][$i]);
							$pdtigst = test_input($_POST['igst'][$i]);
							$pdtgross = test_input($_POST['gross'][$i]);
							
							$sql = "CALL updateBillItems('".$billId."','".$dataId."','".$pdtid."','".$pdtunit."','".$pdtqty."','".$pdtrate."','".$pdtamt."','".$pdttaxslab."','".$pdtcgst."','".$pdtsgst."','".$pdtigst."',
									'".$pdtgross."')";
							$result = mysqli_query($conn, $sql);
							if($result && mysqli_num_rows($result) > 0){
								$customerResult = mysqli_fetch_assoc($result);
								if($customerResult['true']=="true"){
									$_SESSION['internalMsg'] = "Bill created successfully !!";
									$flag = 1;
								}
							}
							else{
								$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
							}
							mysqli_next_result($conn);
						}
					}
				}
				else{
					$_SESSION['internalMsg'] = "Oops! Something went wrong !!";
				}
				mysqli_next_result($conn);
			}			
		}
		else{
			$_SESSION['internalMsg'] = "Access Denied !!";
		}
		if($flag == 1 && $shipAdrState == 1){
			
		//sanitisation
		$address = test_input($_POST['address']);
		$state = test_input($_POST['state']);
		$city = test_input($_POST['city']);
		$pin = test_input($_POST['pin']);
		$country = test_input($_POST['country']);		
				
			$sql = "CALL setShippAddress('".$billId."','".$address."','".$state."','".$city."','".$pin."','".$country."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$sAdrsResult = mysqli_fetch_assoc($result);
				if($sAdrsResult['status']=="true"){
					$_SESSION['internalMsg'] = "Bill created successfully !!";
				}
			}
			else{
				$_SESSION['internalMsg'] = "Shipping Address upload Failed !!";
			}
			mysqli_close($conn);
		}
		send_to($_SESSION['currentPage']);	
	}
	else{
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	send_home("Please, Log In !!");
	}
?>
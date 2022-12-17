<!DOCTYPE html>
<html lang="en" class="fonfam">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|21|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);
		if($usrPerm['billV'] == 1){
			
			require_once 'connection.php';
			
			$sql = "CALL getSiteSettings('".$usrParam['usrId']."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$siteSettings = mysqli_fetch_assoc($result);
			}
			mysqli_next_result($conn);
			
			$k = 0;
			foreach($_POST['billIdList'] as $billId){
			
				//sanitisation
				$billId = test_input($billId);
				
				$sql = "CALL printBillDetails('".$billId."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$billResult = mysqli_fetch_assoc($result);			
					$billResultList[$k] = $billResult;
				}
				mysqli_next_result($conn);
				
				$sql = "CALL printOtherDetails('".$billResult['outId']."','".$billResult['cstmrId']."','".$billResult['vhclId']."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$billDetails = mysqli_fetch_assoc($result);
					$billDetailsList[$k] = $billDetails;
				}
				mysqli_next_result($conn);
				
				$sql = "CALL printAddress('".$billResult['adrsOut']."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$adrsOut = mysqli_fetch_assoc($result);
					$adrsOutList[$k] = $adrsOut;
				}
				mysqli_next_result($conn);
				
				$sql = "CALL printAddress('".$billResult['adrsCstmr']."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$adrsCstmr = mysqli_fetch_assoc($result);
					$adrsCstmrList[$k] = $adrsCstmr;
				}
				mysqli_next_result($conn);
				
				if($billResult['adrsShipp'] != 0){
					$sql = "CALL printAddress('".$billResult['adrsShipp']."')";
					$result = mysqli_query($conn, $sql);
					if($result && mysqli_num_rows($result) > 0){
						$adrsShipp = mysqli_fetch_assoc($result);
						$adrsShippList[$k] = $adrsShipp;
					}
					mysqli_next_result($conn);
				}
				else{
					$adrsShipp = $adrsCstmr;
					$adrsShippList[$k] = $adrsShipp;
				}
				
				$sql = "CALL printBillItems('".$billId."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$i = 0 ;
					while($Item = mysqli_fetch_assoc($result)){
						$ItemList[$i] = $Item;
						$i += 1 ;
					}
					$ItemListList[$k] = $ItemList;
					mysqli_next_result($conn);
				}
				
				$k++;
			}
			mysqli_close($conn);
		}
		else{
			log_msg("DOM Manipulation Detected.|21|26|5");
			send_dashboard("Access Denied !!");
		}
	}
	else{
		log_msg("Incorrect URL Access.|21|30|4");
		send_dashboard("Incorrect URL Access.");
	}
}
else{
	log_msg("Unauthrised Access.|21|35|3");
	send_home("Please, Log In !!");
}
?>
<head>
	<title>RKSBillingSolutions</title>
	<link rel="icon" href="/img/rks_logo.png">																							<!--address tag-->
<?php if($siteSettings['printMode'] == 1): ?>
	<link rel="stylesheet" href="/css/print.css">																						<!--address tag-->
<?php elseIf($siteSettings['printMode'] == 2): ?>
	<link rel="stylesheet" href="/css/print_half.css">																						<!--address tag-->
<?php elseIf($siteSettings['printMode'] == 3): ?>
	<link rel="stylesheet" href="/css/print_compact.css">																						<!--address tag-->
<?php endif; ?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
	<script type="text/javascript" src="/clientJs/bulkPrint.js"></script>																	<!--address tag-->	
</head>
<body>
<script type="text/javascript">
	var siteSettings = <?php echo json_encode($siteSettings); ?>;
	var billResult = <?php echo json_encode($billResultList); ?>;
	var billDetails = <?php echo json_encode($billDetailsList); ?>;
	var adrsOut = <?php echo json_encode($adrsOutList); ?>;
	var adrsCstmr = <?php echo json_encode($adrsCstmrList); ?>;
	var adrsShipp = <?php echo json_encode($adrsShippList); ?>;
	var ItemList = <?php echo json_encode($ItemListList); ?>;
	var vhclV = <?php echo json_encode($usrPerm['vhclV']); ?>;
	var unqFld1 = <?php echo json_encode($usrInfo['unqFld1']); ?>;
</script>
<body>
<?php if($siteSettings['printMode'] < 3): ?>
<div class="print_view" id="print_1">
	
</div>

<?php elseif($siteSettings['printMode'] == 3): ?>
<div class="compact" id="print_1">

</div>
<?php endif; ?>
</body>
</html>
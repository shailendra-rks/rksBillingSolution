<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|11|7|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$_SESSION['currentPage'] = "dashboard.php";
	include('header.php');
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	include('sideBar.php');
	include('topNav.php');
}
else{
	log_msg("Unauthrised Access.|11|20|3");
	send_home("Please, Log In !!");
}
?>
<div class="home-section">
<?php if($_SESSION['internalMsg'] != "Logged In"): ?>
	<div class="alertdiv">
		<span id="alert" class="msg position-fixed"><?php echo $_SESSION['internalMsg']; ?></span>
	</div>
<?php endif; ?>
	<div class="text">Welcome <?php echo $usrInfo['name']; ?></div></br>
	<div class="text">Dashboard Graphics Coming Soon</div>
</div>
</html>
<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|21|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	if($usrPerm['billV'] == 1){
		if($_SESSION['currentPage'] != "settings.php"){
			$_SESSION['currentPage'] = "settings.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
		
		require_once 'connection.php';
			$sql = "CALL getSiteSettings('".$usrParam['usrId']."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$siteSettings = mysqli_fetch_assoc($result);
			}
			mysqli_next_result($conn);
	}
	else{
		log_msg("DOM Manipulation Detected.|22|25|5");
		send_dashboard("Access Denied !!");
	}
}
else{
	log_msg("Unauthrised Access.|21|30|3");
	send_home("Please, Log In !!");
}
?>
<div class="home-section">
<?php if($_SESSION['internalMsg'] != "Logged In"): ?>
	<div class="alertdiv">
		<span id="alert" class="msg position-fixed"><?php echo $_SESSION['internalMsg']; ?></span>
	</div>
<?php endif; ?>
	<div class="pagehead">
		<h3>Site Settings</h3>
		<p>Set GST Rule/ Print Mode/ Password</p>
	</div>
	<form id="settingsForm" action="updateSettings.php" method="post">
		<fieldset class="modalrow_single">
			<legend>GST Return Form</legend>
			<div>
				<input type="radio" id="gstr3b" name="gstrRule" value="A" <?php echo ($siteSettings['billEditRule'] == "A" ? "checked":""); ?>>
				<label for="gstr3b">GSTR-3B</label>
				&nbsp;
			</div>
		</fieldset>
		<hr>
		<fieldset class="modalrow_single">
			<legend>Bill Print Style</legend>
			<div>
				<input type="radio" id="fp" name="printStyle" value="1" <?php echo ($siteSettings['printMode'] == "1" ? "checked":""); ?>>
				<label for="fp">Full Page</label>
				&nbsp;
				<input type="radio" id="hp" name="printStyle" value="2" <?php echo ($siteSettings['printMode'] == "2" ? "checked":""); ?>>
				<label for="hp">Half Page</label>
				&nbsp;
				<input type="radio" name="printStyle" value="3" <?php echo ($siteSettings['printMode'] == "3" ? "checked":""); ?>>
				<label for="Active">Compact</label>
			</div>
		</fieldset>
		<fieldset class="modalrow_single">
			<legend>No. of copies</legend>
			<div>
				<input type="radio" name="printCopy" value="1" <?php echo ($siteSettings['printCopy'] == "1" ? "checked":""); ?>>
				<label for="Active">Single</label>
				&nbsp;
				<input type="radio" name="printCopy" value="2" <?php echo ($siteSettings['printCopy'] == "2" ? "checked":""); ?>>
				<label for="Active">Double</label>
				&nbsp;
				<input type="radio" name="printCopy" value="3" <?php echo ($siteSettings['printCopy'] == "3" ? "checked":""); ?>>
				<label for="Active">Triple</label>
			</div>
		</fieldset>
		<hr>
		<div class="modalcell">
		<div>
			<input type="checkbox" name="renew" onclick="renewCheck(this.checked, 'resetPsw')">
			<label class="txt_large" for="renew"> Reset Password</label>
		</div>
		<div class="page_feild">
			<input type="password" id="resetPsw" name="resetPsw" disabled>
		</div>
		</div>
		<div class="last">
			<button type="submit" form="settingsForm" class="btn btn-primary">Save</button>
		</div>
	</form>
</div>
</html>
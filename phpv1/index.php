<!DOCTYPE html>
<html lang="en">
<?php
	session_start();
	if(!isset($_SESSION['isUsrLoggedIn'])){
		$_SESSION["isUsrLoggedIn"] = "false";
		if(!isset($_SESSION['internalMsg']) || $_SESSION['internalMsg'] == "false"){
		$msg = "Welcome !!!";
		}
		else{
			$msg = $_SESSION['internalMsg'];
			session_destroy();
		}
	}
	elseif($_SESSION['isUsrLoggedIn'] == "true") {
			$host = $_SERVER['HTTP_HOST'];
			$extra = 'dashboard.php';
			header("Location: https://$host/php/$extra");									//address tag
	}
	else{
		$_SESSION["isUsrLoggedIn"] = "false";
		if(!isset($_SESSION['internalMsg']) || $_SESSION['internalMsg'] == "false"){
		$msg = "Welcome !!!";
		}
		else{
			$msg = $_SESSION['internalMsg'];
			session_destroy();
		}
	}
	include('php/header.php');																			//address tag
	include('php/topNav.php');																			//address tag
?>
<form action="php/doLogin.php" method="post" id="loginForm" class="login_form">							<!--address tag-->
  <div class="imgcontainer">
    <img src="img/img_avatar.png" alt="Avatar" class="avatar">											<!--address tag-->
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Username" name="uname" autocomplete="off" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Password" name="psw" required>
	<div>
		<span id="alert" class="msg position-fixed"><?php echo $msg; ?></span>
		<!--span class="psw"><a href="#">Forgot password?</a></span-->
	</div>
	<button id="hidden_login" type="submit" hidden>submit</buton>	
  </div>
</form>
</html>
<?php
if(!isset($_SESSION['isUsrLoggedIn'])){
	send_home("Please, Log In !!");
}
else{
	$servername ="localhost";
	$username = "rksbilli_user1";
	$password = "5!t@12am";
	$dbname = "rksbilli_db";
	$conn = mysqli_connect($servername,$username,$password,$dbname);
	if($conn){
		$_SESSION['connectionToDb'] = "Success";
	}
	else{
		$_SESSION['connectionToDb'] = "connection failure".mysqli_connect_error();
		die;
	}
}
?>
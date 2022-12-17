<?php
if(!isset($_SESSION['isUsrLoggedIn'])){
	send_home("Please, Log In !!");
}
else{
	$servername ="localhost";
	$username = "root";
	$password = "";
	$dbname = "gstbillsol";
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
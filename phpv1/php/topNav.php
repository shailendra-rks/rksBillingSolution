<?php
if(!isset($_SESSION['isUsrLoggedIn'])){
	include('functions.php');
	send_home("Please, Log In !!");
}
?>
<!-- Load an icon library to show a hamburger menu (bars) on small screens -->
<link rel="stylesheet" href="/css/topNav.css">																<!--address tag-->
	<div class="topnav" id="myTopnav">
		<a href="javascript:void(0);" class="icon" id="btnbar">
			<i class="bx bx-menu">
		</i>
		</a>
		<?php if($_SESSION["isUsrLoggedIn"] != "true"): ?>
		<a href="#" onclick="initiateLogin()">
			<i class="bx bx-log-in-circle">
		</i>
			<span class="admin_links">Log-In</span>
		</a>
		<?php elseif($_SESSION["isUsrLoggedIn"] == "true"): ?>
		<a href="/php/logout.php" >																			<!--address tag-->
			<i class="bx bx-log-out-circle">
		</i>
			<span class="admin_links">Logout</span>
		</a>
		<?php endif; ?>
		<a href="#">
			<i class="bx bx-envelope">
		</i>
			<span class="admin_links">Contact</span>
		</a>
		<?php if($_SESSION["isUsrLoggedIn"] == "true"): ?>																					<!--CHECK ME BEFORE FINALISED -->
		<!--a href="#">
			<i class='bx bx-home-alt'>
		</i>
			<span class="admin_links">Bulk Print</span>
		</a-->
		<?php endif; ?>
	</div>
	<script type="text/javascript" src="/clientJs/topNav.js"></script>			                       	<!--address tag-->

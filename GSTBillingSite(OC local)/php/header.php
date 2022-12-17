<?php
if(!isset($_SESSION['isUsrLoggedIn'])){
	include('functions.php');
	send_home("Please, Log In !!");
}
?>
<head>
	<title>RKSBillingSolutions</title>
	<link rel="icon" href="/img/rks_logo.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css'>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
	<link rel="stylesheet" href="/css/main.css">																						<!--address tag-->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
	</script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js">
	</script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js">
	</script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"/>
	</script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
	</script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js">
	</script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js">
	</script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js">
	</script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
	</script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js">
	</script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
	</script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js">
	</script>
	<?php if(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "vehicle.php"): ?>
	<script type="text/javascript" src="/clientJs/vehicle.js">
	</script>																																						<!--address tag-->
	<?php elseif(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "customer.php"): ?>
	<script type="text/javascript" src="/clientJs/customer.js">
	</script>																																						<!--address tag-->	
	<script type="text/javascript" src="/clientJs/StateCity.js">
	</script>																																						<!--address tag-->	
	<?php elseif(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "brand.php"): ?>
	<script type="text/javascript" src="/clientJs/brand.js">
	</script>																																						<!--address tag-->	
	<?php elseif(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "product.php"): ?>
	<script type="text/javascript" src="/clientJs/product.js">
	</script>																																						<!--address tag-->	
	<?php elseif(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "outlet.php"): ?>
	<script type="text/javascript" src="/clientJs/outlet.js">
	</script>																																						<!--address tag-->	
	<script type="text/javascript" src="/clientJs/StateCity.js">
	</script>																																						<!--address tag-->	
	<?php elseif(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "user.php"): ?>
	<script type="text/javascript" src="/clientJs/user.js">
	</script>																																						<!--address tag-->
	<?php elseif(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "viewBill.php"): ?>
	<script type="text/javascript" src="/clientJs/bill.js">
	</script>																																						<!--address tag-->	
	<?php elseif(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "addBill.php"): ?>
	<script type="text/javascript" src="/clientJs/addBill.js">
	</script>																																						<!--address tag-->	
	<script type="text/javascript" src="/clientJs/StateCity.js">
	</script>																																						<!--address tag-->	
	<?php elseif(isset($_SESSION['currentPage']) && $_SESSION['currentPage'] == "reports.php"): ?>
	<script type="text/javascript" src="/clientJs/reports.js">
	</script>																																						<!--address tag-->	
	<?php endif; ?>
	<script type="text/javascript" src="/clientJs/main.js">
	</script>																																						<!--address tag-->
</head>
<body>
<div class="headerDiv">
	<div class="container-fluid ">
		<h1>RKS Billing Solutions</h1>
		<p>One Stop Billing Solution by Raj Computers.</p>
	</div>
</div>
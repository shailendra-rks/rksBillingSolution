<?php
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	include('functions.php');
	send_home("Please, Log In !!");
}
?>
<link rel="stylesheet" href="/../css/sideBar.css">															<!--address tag-->
<div class="sidebar">
	<div class="logo-details">
		<i><img src="/../img/rks_logo_alt.png">
		</i>
		<div class="logo_name">RajComputers</div>
		<i class='bx bx-menu' id="btn">
		</i>
	</div>
	<ul class="nav-list">
	<li>
		<a href="dashboard.php">
			<i class='bx bx-grid-alt'>
				</i>
			<span class="links_name">Dashboard</span>
		</a>
		<span class="tooltip">Dashboard</span>
	</li>
	<?php if($usrPerm['billV'] == 1): ?>
	<li>
		<a id="viewBills" href="viewBill.php">																	<!--address tag-->
			<i class='bx bx-receipt'>
			</i>
			<span class="links_name">Bill</span>
		</a>
		<span class="tooltip">Bill</span>
	</li>
	<?php endif; ?>
	<?php if($usrPerm['pdtV'] == 1): ?>
	<li>
		<a href="product.php">																					<!--address tag-->
			<i class='bx bxs-shopping-bags'>
				</i>
			<span class="links_name">Product</span>
		</a>
		<span class="tooltip">Product</span>
	</li>
	<?php endif; ?>
	<?php if($usrPerm['cstmrV'] == 1): ?>
	<li>
		<a href="customer.php">																					<!--address tag-->
			<i class='bx bxs-user-plus'>
				</i>
			<span class="links_name">Customers</span>
		</a>
		<span class="tooltip">Customers</span>
	</li>
	<?php endif; ?>
	<?php if($usrPerm['vhclV'] == 1): ?>
	<li>
		<a  href="vehicle.php">																					<!--address tag-->
			<i class='bx bxs-truck'>
				</i>
			<span class="links_name">Manage <?php echo $usrInfo['vType']; ?>s</span>
		</a>
		<span class="tooltip"><?php echo $usrInfo['vType']; ?>s</span>
	</li>
	<?php endif; ?>
	<?php if($usrPerm['brandV'] == 1): ?>
	<li>
		<a href="brand.php">																					<!--address tag-->
			<i class='bx bxs-factory'/>
				</i>
			<span class="links_name">Manage <?php echo $usrInfo['bType']; ?>s</span>
		</a>
		<span class="tooltip"><?php echo $usrInfo['bType']; ?>s</span>
	</li>
	<?php endif; ?>
	<?php if($usrPerm['Vanltcs'] == 1): ?>
	<li>
		<a href="reports.php">
			<i class='bx bxs-report'>
				</i>
			<span class="links_name">Reports</span>
		</a>
		<span class="tooltip">Reports</span>
	</li>
	<?php endif; ?>
	<?php if($usrPerm['Muser'] == 1): ?>
	<li>
		<a href="user.php">																						<!--address tag-->
			<i class='bx bxs-user-account'/>
				</i>
			<span class="links_name">User Accounts</span>
		</a>
		<span class="tooltip">Users</span>
	</li>
	<?php endif; ?>
	<?php if($usrPerm['Moutlet'] == 1): ?>
	<li>
		<a href="outlet.php">																					<!--address tag-->
			<i class='bx bx-store-alt'/>
				</i>
			<span class="links_name">Manage <?php echo $usrInfo['outType']; ?>s</span>
		</a>
		<span class="tooltip"><?php echo $usrInfo['outType']; ?>s</span>
	</li>
	<?php endif; ?>
	<?php if($usrParam['usrType'] == "control" || $usrParam['usrType'] == "admin"): ?>
	<li>
		<a href="settings.php">
			<i class='bx bx-cog'>
				</i>
			<span class="links_name">Setting</span>
		</a>
		<span class="tooltip">Setting</span>
	</li>
	<?php endif; ?>
	<li class="profile">
		<div class="profile-details">
			<img src="/img/img_avatar.png" alt="profileImg">															<!--address tag-->
			<div class="name_job">
				<div class="name"><?php echo $usrInfo['name']; ?></div>
				<div class="job"><?php echo $usrParam['usrType']; ?></div>
			</div>
		</div>
		<i class='bx bx-log-out' id="log_out">
		</i>
	</li>
	</ul>
</div>
<script src="/../clientJs/sideBar.js">
</script>																																			<!--address tag-->

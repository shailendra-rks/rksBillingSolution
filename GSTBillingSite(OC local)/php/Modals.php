<?php
session_start();
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|14|5|3");
	send_home("Please, Log In !!");
	}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
		$usrInfo = unserialize($_SESSION['usrInfo']);
		$usrParam = unserialize($_SESSION['usrParam']);
		$usrPerm = unserialize($_SESSION['usrPerm']);
		$mode = test_input($_POST["action"]);
		$dataId = test_input($_POST["dataId"]);
		
		//sanitisation code
		
		if($mode == "edit" && $_SESSION['currentPage'] == "customer.php" && $usrPerm['cstmrE'] == 1){
			$currentAdrs = "";
			$linked = "";			
			require_once 'connection.php';
			$sql = "CALL getCustomerDetails('".$dataId."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$custmorDetails = mysqli_fetch_assoc($result);
			}
			if($custmorDetails['type'] == 0){
				$firmType = "checked";
				$custType = "";
				$isReadonly = "required";
			}
			elseif($custmorDetails['type'] == 1){
				$firmType = "";
				$custType = "checked";
				$isReadonly = "readonly";
			}
			if($custmorDetails['isActive'] == 1){
				$cStatActv = "checked";
				$cStatInactv = "";
			}
			elseif($custmorDetails['isActive'] == 0){
				$cStatActv = "";
				$cStatInactv = "checked";
			}
			mysqli_next_result($conn);
			
			$sql = "CALL getAllAddress('".$custmorDetails['id']."',2)";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$i = 0 ;
				while($Adrs = mysqli_fetch_assoc($result)){
					$AddressList[$i] = $Adrs;
					if($Adrs['id'] == $custmorDetails['adrsId']){
						$currentAdrs = $i;
						if($Adrs['linked'] == 1){
							$linked = "readonly";
						}
					}
					$i += 1 ;
				}
			}
			mysqli_close($conn);
		}
		
		elseif($mode == "edit" && $_SESSION['currentPage'] == "brand.php" && $usrPerm['brandE'] == 1){
			require_once 'connection.php';
			$sql = "CALL getBrandDetails('".$dataId."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$brandDetails = mysqli_fetch_assoc($result);
			}
			if($brandDetails['isActive'] == 1){
				$bStatActv = "checked";
				$bStatInactv = "";
			}
			elseif($brandDetails['isActive'] == 0){
				$bStatActv = "";
				$bStatInactv = "checked";
			}
			mysqli_close($conn);
		}
		
		elseif($mode == "edit" && $_SESSION['currentPage'] == "vehicle.php" && $usrPerm['vhclE'] == 1){
			require_once 'connection.php';
			$sql = "CALL getVehicleDetails('".$dataId."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$vehicleDetails = mysqli_fetch_assoc($result);
			}
			if($vehicleDetails['isActive'] == 1){
				$vStatActv = "checked";
				$vStatInactv = "";
			}
			elseif($vehicleDetails['isActive'] == 0){
				$vStatActv = "";
				$vStatInactv = "checked";
			}
			mysqli_close($conn);
		}
		
		elseif($_SESSION['currentPage'] == "product.php"){
			require_once 'connection.php';
			$sql = "CALL getBrandList('".$usrParam['usrId']."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$i = 0 ;
				while($brand = mysqli_fetch_assoc($result)){
					$brandList[$i] = $brand;
					$i += 1 ;
				}
			}
			mysqli_next_result($conn);
		
			if($mode == "edit" && $usrPerm['pdtE'] == 1){
				
				$sql = "CALL getProductDetails('".$dataId."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$productDetails = mysqli_fetch_assoc($result);
				}
				if($productDetails['isActive'] == 1){
					$pStatActv = "checked";
					$pStatInactv = "";
				}
				elseif($productDetails['isActive'] == 0){
					$pStatActv = "";
					$pStatInactv = "checked";
				}
				$cgst = $productDetails['cgst'];
				$sgst = $productDetails['cgst'];
				$igst = 2 * $productDetails['cgst'];
				mysqli_close($conn);
			}
		}
		
		elseif($mode == "edit" && $_SESSION['currentPage'] == "outlet.php" && $usrPerm['Moutlet'] == 1){
			$currentAdrs = "";
			$linked = "";			
			require_once 'connection.php';			
			$sql = "CALL getOutletDetails('".$dataId."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$outletDetails = mysqli_fetch_assoc($result);
			}
			if($outletDetails['isActive'] == 1){
				$oStatActv = "checked";
				$oStatInactv = "";
			}
			elseif($outletDetails['isActive'] == 0){
				$oStatActv = "";
				$oStatInactv = "checked";
			}
			mysqli_next_result($conn);
			
			$sql = "CALL getAllAddress('".$outletDetails['id']."',1)";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$i = 0 ;
				while($Adrs = mysqli_fetch_assoc($result)){
					$AddressList[$i] = $Adrs;
					if($Adrs['id'] == $outletDetails['adrsId']){
						$currentAdrs = $i;
						if($Adrs['linked'] == 1){
							$linked = "readonly";
						}
					}
					$i += 1 ;
				}
			}
			mysqli_close($conn);
		}
		
		elseif($_SESSION['currentPage'] == "user.php" && $usrPerm['Muser'] == 1){
			require_once 'connection.php';
			$sql = "CALL getOutletList('".$usrParam['usrId']."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$i = 0 ;
				while($out = mysqli_fetch_assoc($result)){
					$outList[$i] = $out;
					$i += 1 ;
				}
			}
			mysqli_next_result($conn);
			$user_name = "user".($usrInfo['usrCount'] + 1)."";
			
			if($mode == "edit"){
				
				$sql = "CALL getUserDetails('".$dataId."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					$userDetails = mysqli_fetch_assoc($result);
				}
				if($userDetails['isActive'] == 1){
					$uStatActv = "checked";
					$uStatInactv = "";
				}
				elseif($userDetails['isActive'] == 0){
					$uStatActv = "";
					$uStatInactv = "checked";
				}
				mysqli_close($conn);
			}
		}		
	}
else{
	log_msg("Unauthrised Access.|14|206|5");
	send_home("Please, Log In !!");
}
?>

<!--Modal window markup to add vehicle-->

<?php if(($_SESSION['currentPage'] ==  "vehicle.php"|| $_SESSION['currentPage'] ==  "addBill.php") && $usrPerm['vhclV'] == 1): ?>
<div id="addVehicleModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
		  
<!-- form to be rendered when user is adding data -->	  

<?php if($mode == "add" && $usrPerm['vhclC'] == 1): ?>
      <div class="modal-header">
        <h4 class="modal-title">Create <?php echo $usrInfo['vType'] ?></h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addVehicleForm" action="addVehicle.php" method="post">	
		<fieldset class="modalrow_single">
			<legend>Status</legend>
			<div>
				<input type="radio" name="vStatus" id="Active" value="1" checked>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="vStatus" id="Inactive" value="0">
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		<hr>
		<div class="modalrow_single">
			<label for="vname"><b><?php echo $usrInfo['vType'] ?> Number</b></label>
			<input type="text" class="all_caps" maxlength="10" placeholder="RJ00AA0000" name="vname" pattern="^(([A-Za-z]){2,3}(|-)(?:[0-9]){1,2}(|-)(?:[A-Za-z]){2}(|-)([0-9]){1,4})|(([A-Za-z]){2,3}(|-)([0-9]){1,4})" required>
			<label for="vrep"><b><?php echo $usrInfo['vType'] ?> Owner/Company/Driver</b></label>
			<input type="text" placeholder="text" name="vrep">
			<label for="vcontact"><b>Contact</b></label>
			<input type="text" maxlength="10" placeholder="0123456789" name="vcontact" pattern="\b\d{10}\b">
			<input type="hidden" name="action" value="<?php echo $mode; ?>">
		</div>
	  </form>
      </div>
	  
<!-- form to be rendered when user is editting data -->	

<?php elseif($mode == "edit" && $usrPerm['vhclE'] == 1): ?>
      <div class="modal-header">
        <h4 class="modal-title">Edit <?php echo $usrInfo['vType'] ?></h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addVehicleForm" action="addVehicle.php" method="post">	
		<fieldset class="modalrow_single">
			<legend>Status</legend>
			<div>
				<input type="radio" name="vStatus" id="Active" value="1" <?php echo $vStatActv; ?>>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="vStatus" id="Inactive" value="0" <?php echo $vStatInactv; ?>>
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		<hr>
		<div class="modalrow_single">
			<label for="uname"><b><?php echo $usrInfo['vType'] ?> Number</b></label>
			<input type="text" class="all_caps" maxlength="10" placeholder="RJ00AA0000" name="vname" 
					value="<?php echo $vehicleDetails['vNo']; ?>" pattern="^(([A-Za-z]){2,3}(|-)(?:[0-9]){1,2}(|-)(?:[A-Za-z]){2}(|-)([0-9]){1,4})|(([A-Za-z]){2,3}(|-)([0-9]){1,4})" required>
			<label for="vrep"><b><?php echo $usrInfo['vType'] ?> Owner/Company/Driver</b></label>
			<input type="text" placeholder="text" name="vrep" value="<?php echo $vehicleDetails['vRep']; ?>">
			<label for="vcontact"><b>Contact</b></label>
			<input type="text" maxlength="10" placeholder="0123456789" name="vcontact" pattern="\b\d{10}\b" value="<?php echo $vehicleDetails['contactV']; ?>">
			<input type="hidden" name="action" value="<?php echo $mode; ?>">
			<input type="hidden" name="dataId" value="<?php echo $dataId; ?>">
		</div>
	  </form>
      </div>
<?php endif; ?>

      <div class="modal-footer">
		<button type="submit" form="addVehicleForm" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!--Modal window markup to add customer-->

<?php if(($_SESSION['currentPage'] == "customer.php"|| $_SESSION['currentPage'] == "addBill.php") && $usrPerm['cstmrV'] == 1): ?>
<div id="addCustomerModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <!-- Modal content-->
    <div class="modal-content">
	  
<!-- form to be rendered when user is adding data -->	  

<?php if($mode == "add" && $usrPerm['cstmrC'] == 1): ?>
      <div class="modal-header">
        <h4 class="modal-title">Create Customer</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addCustForm" action="addCustomer.php" method="post">
		<div class="modalrow">
		<fieldset class="modalcell">
			<legend>Type</legend>
			<div>
				<input type="radio" name="ctype" id="firm" value="0" onclick="setValidationCustomer(0)" checked>
				<label for="firm">Firm</label>
				&nbsp;
				<input type="radio" name="ctype" id="cust" value="1" onclick="setValidationCustomer(1)">
				<label for="cust">Customer</label>
			</div>
		</fieldset>
		<div class="modaldiv"></div>
		<fieldset class="modalcell">
			<legend>Status</legend>
			<div>
				<input type="radio" name="cStatus" id="Active" value="1" checked>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="cStatus" id="Inactive" value="0">
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="cname"><b>Name</b></label>
				<input type="text" name="cname" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="gstn"><b>Goods and Services Tax Number</b></label>
				<input type="text" id="gstnumber" class="all_caps" maxlength="15" placeholder="01ABCDE0123A0Z0" name="gstn" 
				pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" required>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pcontact"><b>Contact</b></label>
				<input type="text" maxlength="10" placeholder="0123456789" name="pcontact" pattern="\b\d{10}\b">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="scontact"><b>Contact Other</b></label>
				<input type="text" maxlength="10" placeholder="0123456789" name="scontact">
			</div>
		</div>
		<hr>
		<div class="modalrow_single">
				<label for="address"><b>Address</b></label>
				<input type="text" name="address" required>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="state"><b>State</b></label>
				<select id="stateSelect" name="state" onchange="makeSubmenu(this.value)" required>
					<option value="" readonly selected>Select State</option>
					<option></option>
				</select>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="city"><b>City</b></label>
				<select id="citySelect" name="city" readonly required>
					<option value="" readonly selected>Select City</option>
					<option></option>
				</select>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pin"><b>Pin Code</b></label>
				<input type="text" maxlength="6" placeholder="012345" name="pin" pattern="\b\d{6}\b">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="country"><b>Country</b></label>
				<input type="text" name="country" value="India" readonly>
			</div>
		</div>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
	  </form>
      </div>
	  
<!-- form to be rendered when user is editting data -->	

<?php elseif($mode == "edit" && $usrPerm['cstmrE'] == 1): ?>
      <div class="modal-header">
        <h4 class="modal-title">Edit Customer</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addCustForm" action="addCustomer.php" method="post">
		<div class="modalrow">
		<fieldset class="modalcell">
			<legend>Type</legend>
			<div>
				<input type="radio" name="ctype" id="firm" value="0" onclick="setValidationCustomer(0)" <?php echo $firmType; ?>>
				<label for="firm">Firm</label>
				&nbsp;
				<input type="radio" name="ctype" id="cust" value="1" onclick="setValidationCustomer(1)" <?php echo $custType; ?>>
				<label for="cust">Customer</label>
			</div>
		</fieldset>
		<div class="modaldiv"></div>
		<fieldset class="modalcell">
			<legend>Status</legend>
			<div>
				<input type="radio" name="cStatus" id="Active" value="1" <?php echo $cStatActv; ?>>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="cStatus" id="Inactive" value="0" <?php echo $cStatInactv; ?>>
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="cname"><b>Name</b></label>
				<input type="text" name="cname" value="<?php echo $custmorDetails['fName']; ?>" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="gstn"><b>Goods and Services Tax Number</b></label>
				<input type="text" id="gstnumber" class="all_caps" maxlength="15" placeholder="01ABCDE0123A0Z0" name="gstn" value="<?php echo $custmorDetails['gstn']; ?>" 
				pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" <?php echo $isReadonly; ?>>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pcontact"><b>Contact</b></label>
				<input type="text" maxlength="10" placeholder="0123456789" name="pcontact" value="<?php echo $custmorDetails['contactP']; ?>" pattern="\b\d{10}\b">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="scontact"><b>Contact Other</b></label>
				<input type="text" maxlength="10" placeholder="0123456789" name="scontact" value="<?php echo $custmorDetails['contactS']; ?>">
			</div>
		</div>
		<hr>
		<script type="text/javascript">
			var AdrsArray= <?php echo json_encode($AddressList); ?>;
		</script>
		<div class="modalrow">
			<div class="modalcell">
				<label for="address"><b>Address</b></label>
				<input type="radio" name="adrsStatus" value="0" onclick="setAddressMode(this.value)" checked>
				<label for="existing">Existing</label>
				&nbsp;
				<input type="radio" name="adrsStatus" value="1" onclick="setAddressMode(this.value)">
				<label for="new">New</label>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
			<select id="adrsSelect" onchange="setAdrsContent(this.value)">
					
					<!--generating adrs list-->
					
					<?php $i=1; foreach($AddressList as $Adrs): ?>
						<?php if($Adrs['id'] == $AddressList[$currentAdrs]['id']): ?>
							<option value='<?php echo $i; ?>' selected><?php echo $i; ?></option>
						<?php else: ?>
							<option value='<?php echo $i; ?>' ><?php echo $i; ?></option>
						<?php endif; ?>
						<?php $i++; ?>
					<?php endforeach; ?>
			</select>
			</div>
		</div>
		<div id="currentAdrs">
		<div class="modalrow_single">
			<input type="hidden" id="adrsId" name="adrsId" value="<?php echo $AddressList[$currentAdrs]['id']; ?>">
			<label for="address"><b>Line 1</b></label>
			<input type="text" id="adrs" name="address" value="<?php echo $AddressList[$currentAdrs]['address']; ?>" required <?php echo $linked; ?>>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="state"><b>State</b></label>
				<input type="text" id="state" name="state" value="<?php echo $AddressList[$currentAdrs]['state']; ?>" required <?php echo $linked; ?>>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="city"><b>City</b></label>
			<input type="text" id="city" name="city" value="<?php echo $AddressList[$currentAdrs]['city']; ?>" required <?php echo $linked; ?>>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pin"><b>Pin Code</b></label>
				<input type="text" maxlength="6" placeholder="012345" id="pin" name="pin" value="<?php echo $AddressList[$currentAdrs]['pin']; ?>" pattern="\b\d{6}\b" <?php echo $linked; ?>>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="country"><b>Country</b></label>
				<input type="text" id="cntry" name="country"  value="<?php echo $AddressList[$currentAdrs]['country']; ?>" readonly>
			</div>
		</div>
		</div>
		<div id="newAdrs" disabled hidden>
		<div class="modalrow_single">
			<label for="address"><b>Line 1</b></label>
			<input type="text" name="address" disabled required>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="state"><b>State</b></label>
				<select id="stateSelect" name="state" onchange="makeSubmenu(this.value)" disabled required>
					<option value="" readonly selected>Select State</option>
					<option></option>
				</select>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="city"><b>City</b></label>
				<select id="citySelect" name="city" readonly disabled required>
					<option value="" readonly selected>Select City</option>
					<option></option>
				</select>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pin"><b>Pin Code</b></label>
				<input type="text" maxlength="6" placeholder="012345" name="pin" pattern="\b\d{6}\b" disabled>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="country"><b>Country</b></label>
				<input type="text" name="country" value="India" readonly disabled>
			</div>
		</div>
		</div>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
		<input type="hidden" name="dataId" value="<?php echo $dataId; ?>">
	  </form>
	  </div>
<?php endif; ?>

      <div class="modal-footer">
		<button type="submit" form="addCustForm" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!--Modal window markup to add Brand-->

<?php if($_SESSION['currentPage'] == "brand.php" && $usrPerm['brandV'] == 1): ?>
<div id="addBrandModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
	  
<!-- form to be rendered when user is adding data -->	  
	  
<?php if($mode == "add" && $usrPerm['brandC'] == 1): ?>
	  <div class="modal-header">
        <h4 class="modal-title">Create <?php echo $usrInfo['bType']; ?></h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addBrandForm" action="addBrand.php" method="post">
		<fieldset class="modalrow_single">
			<legend>Status</legend>
			<div>
				<input type="radio" name="bStatus" id="Active" value="1" checked>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="bStatus" id="Inactive" value="0">
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		<hr>
		<div class="modalrow_single">
			<label for="bname"><b>Name</b></label>
			<input type="text" name="bname">
			<label for="bIdentity"><b><?php echo $usrInfo['bIdentifier']; ?></b></label>
			<input type="text" name="bIdentity" required>
			<label for="pcontact"><b>Contact</b></label>
			<input type="text" maxlength="10" placeholder="0123456789" name="pcontact" pattern="\b\d{10}\b" >
			<label for="location"><b>Location</b></label>
			<input type="text" name="location">
		</div>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
	  </form>
	  </div>

<!-- form to be rendered when user is editting data -->	

<?php elseif($mode == "edit" && $usrPerm['brandE'] == 1): ?>
	  <div class="modal-header">
        <h4 class="modal-title">Edit <?php echo $usrInfo['bType']; ?></h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addBrandForm" action="addBrand.php" method="post">
		<fieldset class="modalrow_single">
			<legend>Status</legend>
			<div>
				<input type="radio" name="bStatus" id="Active" value="1" <?php echo $bStatActv; ?>>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="bStatus" id="Inactive" value="0" <?php echo $bStatInactv; ?>>
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		<div class="modalrow_single">
			<label for="bname"><b>Name</b></label>
			<input type="text" name="bname" value="<?php echo $brandDetails['bName']; ?>">
			<label for="bIdentity"><b><?php echo $usrInfo['bIdentifier']; ?></b></label>
			<input type="text" name="bIdentity" value="<?php echo $brandDetails['bIdentity']; ?>" required>
			<label for="pcontact"><b>Contact</b></label>
			<input type="text" maxlength="10" placeholder="0123456789" name="pcontact" value="<?php echo $brandDetails['bContact']; ?>" pattern="\b\d{10}\b" >
			<label for="location"><b>Location</b></label>
			<input type="text" name="location" value="<?php echo $brandDetails['bPlace']; ?>">
		</div>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
		<input type="hidden" name="dataId" value="<?php echo $dataId; ?>">
	  </form>
	  </div>
<?php endif; ?>

      <div class="modal-footer">
		<button type="submit" form="addBrandForm" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!--Modal window markup to add Product-->

<?php if($_SESSION['currentPage'] == "product.php" && $usrPerm['pdtV'] == 1): ?>
<div id="addProductModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <!-- Modal content-->
    <div class="modal-content">
	  
<!-- form to be rendered when user is adding data -->	  
	  
<?php if($mode == "add" && $usrPerm['pdtC'] == 1): ?>
	  <div class="modal-header">
        <h4 class="modal-title">Create Product</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addProductForm" action="addProduct.php" method="post">
	  <div class="modalrow">
		<fieldset class="modalcell">
			<legend>Status</legend>
			<div>
				<input type="radio" name="pStatus" id="Active" value="1" checked>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="pStatus" id="Inactive" value="0">
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		<div class="modaldiv"></div>
		<div class="modalcell">
			<label for="brand"><b><?php echo $usrInfo['bType']; ?></b></label>
			<select id="brandSelect" name="brand">
					<option value="" readonly selected>Select <?php echo $usrInfo['bType']; ?></option>
					
					<!--generating product list-->
					
					<?php foreach($brandList as $brand): ?>
						<option value='<?php echo $brand['id']; ?>'><?php echo $brand['bName']; ?></option>
					<?php endforeach; ?>
			</select>
		</div>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pname"><b>Name</b></label>
				<input type="text" name="pname" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="hsn"><b>HSN Number</b></label>
				<input type="text" maxlength="8" placeholder="01234567" name="hsn" pattern="\b\d{4,}\b" required>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="rate"><b>Rate(₹)</b></label>
				<input type="text" name="rate">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="cgst"><b>CGST</b></label>
				<input type="text" name="cgst" value="0" onkeyup="setGstSlabs(this.value)" required>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="sgst"><b>SGST</b></label>
				<input type="text" id="sgst" name="sgst" value="0" disabled>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="igst"><b>IGST</b></label>
				<input type="text" id="igst" name="igst" value="0" disabled>
			</div>
		</div>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
	  </form>
	  </div>

<!-- form to be rendered when user is editting data -->	

<?php elseif($mode == "edit" && $usrPerm['pdtE'] == 1): ?>
	  <div class="modal-header">
        <h4 class="modal-title">Edit Product</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addProductForm" action="addProduct.php" method="post">
	  <div class="modalrow">
		<fieldset class="modalcell">
			<legend>Status</legend>
			<div>
				<input type="radio" name="pStatus" id="Active" value="1" <?php echo $pStatActv; ?>>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="pStatus" id="Inactive" value="0" <?php echo $pStatInactv; ?>>
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		<div class="modaldiv"></div>
		<div class="modalcell">
			<label for="brand"><b><?php echo $usrInfo['bType']; ?></b></label>
			<select id="brandSelect" name="brand">
					<option value="" readonly>Select State</option>
					
					<!--generating product list-->
					
					<?php foreach($brandList as $brand): ?>
						<?php if($brand['id'] == $productDetails['brandId']): ?>
							<option value='<?php echo $brand['id']; ?>' selected><?php echo $brand['bName']; ?></option>
						<?php else: ?>
							<option value='<?php echo $brand['id']; ?>'><?php echo $brand['bName']; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
		</div>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pname"><b>Name</b></label>
				<input type="text" name="pname" value="<?php echo $productDetails['name']; ?>" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="hsn"><b>HSN Number</b></label>
				<input type="text" maxlength="8" placeholder="01234567" name="hsn" value="<?php echo $productDetails['hsn']; ?>" pattern="\b\d{4,}\b" required>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="rate"><b>Rate(₹)</b></label>
				<input type="text" name="rate" value="<?php echo $productDetails['rate']; ?>">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="cgst"><b>CGST</b></label>
				<input type="text" name="cgst" value="<?php echo $cgst; ?>" onkeyup="setGstSlabs(this.value)" required>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="sgst"><b>SGST</b></label>
				<input id="sgst" type="text" name="sgst" value="<?php echo $sgst; ?>" disabled>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="igst"><b>IGST</b></label>
				<input id="igst" type="text" name="igst" value="<?php echo $igst; ?>" disabled>
			</div>
		</div>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
		<input type="hidden" name="dataId" value="<?php echo $dataId; ?>">
	  </form>
	  </div>
<?php endif; ?>

      <div class="modal-footer">
		<button type="submit" form="addProductForm" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!--Modal window markup to add outlet-->

<?php if($_SESSION['currentPage'] == "outlet.php" && $usrPerm['Moutlet'] == 1): ?>
<div id="addOutletModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <!-- Modal content-->
    <div class="modal-content">
	  
<!-- form to be rendered when user is adding data -->	  
	  
<?php if($mode == "add" && $usrInfo['outCount'] < $usrInfo['numOutlet']): ?>
	  <div class="modal-header">
        <h4 class="modal-title">Create <?php echo $usrInfo['outType']; ?></h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addOutletForm" action="addOutlet.php" method="post">
	  <div class="modalrow">
		<fieldset class="modalcell">
			<legend>Status</legend>
			<div>
				<input type="radio" name="oStatus" id="Active" value="1" checked>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="oStatus" id="Inactive" value="0">
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		<div class="modaldiv"></div>
		<div class="modalcell">
			<label for="billStart"><b>Bill Starts From</b></label>
			<input type="text" name="billStart" required>
		</div>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="oname"><b>Name</b></label>
				<input type="text" name="oname" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="gstin"><b>GSTIN Number</b></label>
				<input type="text" class="all_caps" maxlength="15" placeholder="01ABCDE0123A0Z0" name="gstin" 
				pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" required>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="contP"><b>Contact</b></label>
				<input type="text" name="contP" maxlength="10" placeholder="0123456789" pattern="\b\d{10}\b" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="contS"><b>Contact Other</b></label>
				<input type="text" maxlength="10" placeholder="0123456789" name="contS" pattern="\b\d{10}\b">
			</div>
		</div>
		<div class="modalrow_single">
			<label for="descp"><b>Description</b></label>
			<input type="text" name="descp">
		<hr>
			<label for="address"><b>Address</b></label>
			<input type="text" name="address" required>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="state"><b>State</b></label>
				<select id="stateSelect" name="state" onchange="makeSubmenu(this.value)" required>
					<option value="" readonly selected>Select State</option>
					<option></option>
				</select>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="city"><b>City</b></label>
				<select id="citySelect" name="city" readonly required>
					<option value="" readonly selected>Select City</option>
					<option></option>
				</select>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pin"><b>Pin Code</b></label>
				<input type="text" maxlength="6" placeholder="012345" name="pin" pattern="\b\d{6}\b">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="country"><b>Country</b></label>
				<input type="text" name="country" value="India" readonly>
			</div>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="bank"><b>Bank Name</b></label>
				<input type="text" name="bank">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="ifsc"><b>IFSC</b></label>
				<input type="text" maxlength="11" name="ifsc" placeholder="ABCD0123456" pattern="[A-Z|a-z]{4}[0][\d]{6}$">
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="accNum"><b>Account Number</b></label>
				<input type="text" maxlength="18" name="accNum" pattern="\b\d{9,}\b">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="branch"><b>Branch Name</b></label>
				<input type="text" name="branch">
			</div>
		</div>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
	  </form>
	  </div>

<!-- form to be rendered when user is editting data -->	

<?php elseif($mode == "edit"): ?>
	  <div class="modal-header">
        <h4 class="modal-title">Edit <?php echo $usrInfo['outType']; ?></h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addOutletForm" action="addOutlet.php" method="post">
	  <div class="modalrow">
		<fieldset class="modalcell">
			<legend>Status</legend>
			<div>
				<input type="radio" name="oStatus" id="Active" value="1" <?php echo $oStatActv; ?>>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="oStatus" id="Inactive" value="0" <?php echo $oStatInactv; ?>>
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		<div class="modaldiv"></div>
		<div class="modalcell">
			<label for="billStart"><b>Bill Starts From</b></label>&nbsp
			<input type="checkbox" name="renew" onclick="renewCheck(this.checked, 'billStart')">
			<label for="renew"> Renew Bill Start</label>
			<input type="text" id="billStart" name="billStart" value="<?php echo $outletDetails['billStartNum']; ?>" disabled>
		</div>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="oname"><b>Name</b></label>
				<input type="text" name="oname" value="<?php echo $outletDetails['name']; ?>" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="gstin"><b>GSTIN Number</b></label>
				<input type="text" class="all_caps" maxlength="15" placeholder="01ABCDE0123A0Z0" name="gstin" value="<?php echo $outletDetails['gstin']; ?>" 
				pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" required>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="contP"><b>Contact</b></label>
				<input type="text" name="contP" maxlength="10" placeholder="0123456789" value="<?php echo $outletDetails['contactP']; ?>" pattern="\b\d{10}\b" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="contS"><b>Contact Other</b></label>
				<input type="text" maxlength="10" placeholder="0123456789" name="contS" value="<?php echo $outletDetails['contactS']; ?>" pattern="\b\d{10}\b">
			</div>
		</div>
		<div class="modalrow_single">
			<label for="descp"><b>Description</b></label>
			<input type="text" name="descp" value="<?php echo $outletDetails['dscrb']; ?>">
		</div>
		<hr>
		<script type="text/javascript">
			var AdrsArray= <?php echo json_encode($AddressList); ?>;
		</script>
		<div class="modalrow">
			<div class="modalcell">
				<label for="address"><b>Address</b></label>
				<input type="radio" name="adrsStatus" value="0" onclick="setAddressMode(this.value)" checked>
				<label for="existing">Existing</label>
				&nbsp;
				<input type="radio" name="adrsStatus" value="1" onclick="setAddressMode(this.value)">
				<label for="new">New</label>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
			<select id="adrsSelect" onchange="setAdrsContent(this.value)">
					
					<!--generating adrs list-->
					
					<?php $i=1; foreach($AddressList as $Adrs): ?>
						<?php if($Adrs['id'] == $AddressList[$currentAdrs]['id']): ?>
							<option value='<?php echo $i; ?>' selected><?php echo $i; ?></option>
						<?php else: ?>
							<option value='<?php echo $i; ?>' ><?php echo $i; ?></option>
						<?php endif; ?>
						<?php $i++; ?>
					<?php endforeach; ?>
			</select>
			</div>
		</div>
		<div id="currentAdrs">
		<div class="modalrow_single">
			<input type="hidden" id="adrsId" name="adrsId" value="<?php echo $AddressList[$currentAdrs]['id']; ?>">
			<label for="address"><b>Line 1</b></label>
			<input type="text" id="adrs" name="address" value="<?php echo $AddressList[$currentAdrs]['address']; ?>" required <?php echo $linked; ?>>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="state"><b>State</b></label>
				<input type="text" id="state" name="state" value="<?php echo $AddressList[$currentAdrs]['state']; ?>" required <?php echo $linked; ?>>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="city"><b>City</b></label>
			<input type="text" id="city" name="city" value="<?php echo $AddressList[$currentAdrs]['city']; ?>" required <?php echo $linked; ?>>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pin"><b>Pin Code</b></label>
				<input type="text" maxlength="6" placeholder="012345" id="pin" name="pin" value="<?php echo $AddressList[$currentAdrs]['pin']; ?>" pattern="\b\d{6}\b" <?php echo $linked; ?>>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="country"><b>Country</b></label>
				<input type="text" id="cntry" name="country"  value="<?php echo $AddressList[$currentAdrs]['country']; ?>" readonly>
			</div>
		</div>
		</div>
		<div id="newAdrs" disabled hidden>
		<div class="modalrow_single">
			<label for="address"><b>Line 1</b></label>
			<input type="text" name="address" disabled required>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="state"><b>State</b></label>
				<select id="stateSelect" name="state" onchange="makeSubmenu(this.value)" disabled required>
					<option value="" readonly selected>Select State</option>
					<option></option>
				</select>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="city"><b>City</b></label>
				<select id="citySelect" name="city" disabled readonly required>
					<option value="" readonly selected>Select City</option>
					<option></option>
				</select>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="pin"><b>Pin Code</b></label>
				<input type="text" maxlength="6" placeholder="012345" name="pin" pattern="\b\d{6}\b" disabled>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="country"><b>Country</b></label>
				<input type="text" name="country" value="India" readonly disabled>
			</div>
		</div>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="bank"><b>Bank Name</b></label>
				<input type="text" name="bank" value="<?php echo $outletDetails['bankName']; ?>">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="ifsc"><b>IFSC</b></label>
				<input type="text" maxlength="11" placeholder="ABCD0123456" name="ifsc" value="<?php echo $outletDetails['ifsc']; ?>" pattern="[A-Z|a-z]{4}[0][\d]{6}$">
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="accNum"><b>Account Number</b></label>
				<input type="text" maxlength="18" name="accNum" value="<?php echo $outletDetails['accNum']; ?>" pattern="\b\d{9,}\b">
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="branch"><b>Branch Name</b></label>
				<input type="text" name="branch" value="<?php echo $outletDetails['brnchName']; ?>">
			</div>
		</div>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
		<input type="hidden" name="dataId" value="<?php echo $dataId; ?>">
	  </form>
	  </div>
<?php endif; ?>

      <div class="modal-footer">
		<button type="submit" form="addOutletForm" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!--Modal window markup to add user-->

<?php if($_SESSION['currentPage'] == "user.php" && $usrPerm['Muser'] == 1): ?>
<div id="addUserModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <!-- Modal content-->
    <div class="modal-content">
	  
<!-- form to be rendered when user is adding data -->	  
	  
<?php if($mode == "add"): ?>
	  <div class="modal-header">
        <h4 class="modal-title">Create User</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addUserForm" action="addUser.php" method="post">
	  <div class="modalrow">
		<div class="modalcell">
			<label for="uLogId"><b>LoginId</b></label>
			<div class="inline-flex">
				<input type="text" name="uLogId" value="<?php echo $user_name; ?>" readonly>
				<label><b>@<?php echo $usrParam['usrId']; ?></b></label>
			</div>
		</div>
		<div class="modaldiv"></div>
		<fieldset class="modalcell">
			<legend>Status</legend>
			<div>
				<input type="radio" name="uStatus" id="Active" value="1" checked>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="uStatus" id="Inactive" value="0">
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="uname"><b>Name</b></label>
				<input type="text" name="uname" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="psw"><b>Password</b></label>
				<input type="password" placeholder="Password" name="psw" required>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="contP"><b>Contact</b></label>
				<input type="text" name="contP" maxlength="10" placeholder="0123456789" pattern="\b\d{10}\b" required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="outlet"><b><?php echo $usrInfo['outType']; ?></b></label>
				<select id="brandSelect" name="outlet" required>
					<option value="" readonly selected>Select <?php echo $usrInfo['outType']; ?></option>
					
					<!--generating product list-->
					
					<?php foreach($outList as $out): ?>
						<option value='<?php echo $out['id']; ?>'><?php echo $out['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<hr>
		<?php if($usrPerm['billV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage Bills</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewBill" name="billV" onclick="nestedChecks(this.checked, 'createBill', 'editBill')">
				<label for="billV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['billC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createBill" name="billC" disabled>
				<label for="billC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php if($usrPerm['billE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editBill" name="billE" disabled>
				<label for="billE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['biltyV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage Bilty</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewBilty" name="biltyV" onchange="nestedChecks(this.checked, 'createBilty', 'editBilty')">
				<label for="viewBilty"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['biltyC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createBilty" name="biltyC" disabled>
				<label for="biltyC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['biltyE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editBilty" name="biltyE" disabled>
				<label for="biltyE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['pdtV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage Products</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewPdt" name="pdtV" onchange="nestedChecks(this.checked, 'createPdt', 'editPdt')">
				<label for="pdtV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['pdtC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createPdt" name="pdtC" disabled>
				<label for="pdtC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['pdtE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editPdt" name="pdtE" disabled>
				<label for="pdtE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['cstmrV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage Customers</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewCstmr" name="cstmrV" onchange="nestedChecks(this.checked, 'createCstmr', 'editCstmr')">
				<label for="cstmrV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['cstmrC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createCstmr" name="cstmrC" disabled>
				<label for="cstmrC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['cstmrE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editCstmr" name="cstmrE" disabled>
				<label for="cstmrE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['vhclV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage <?php echo $usrInfo['vType'] ?>s</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewVhcl" name="vhclV" onchange="nestedChecks(this.checked, 'createVhcl', 'editVhcl')">
				<label for="vhclV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['vhclC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createVhcl" name="vhclC" disabled>
				<label for="vhclC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['vhclE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editVhcl" name="vhclE" disabled>
				<label for="vhclE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['brandV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage <?php echo $usrInfo['bType'] ?>s</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewBrand" name="brandV" onchange="nestedChecks(this.checked, 'createBrand', 'editBrand')">
				<label for="brandV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['brandC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createBrand" name="brandC" disabled>
				<label for="brandC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['brandE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editBrand" name="brandE" disabled>
				<label for="brandE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
	  </form>
	  </div>

<!-- form to be rendered when user is editting data -->	

<?php elseif($mode == "edit"): ?>
	  <div class="modal-header">
        <h4 class="modal-title">Edit <?php echo $usrInfo['outType']; ?></h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form id="addUserForm" action="addUser.php" method="post">
	  <div class="modalrow">
		<div class="modalcell">
			<label for="uLogId"><b>LoginId</b></label>
			<div class="inline-flex">
				<input type="text" name="uLogId" value='<?php echo $userDetails['usrId']; ?>' readonly>
			</div>
		</div>
		<div class="modaldiv"></div>
		<fieldset class="modalcell">
			<legend>Status</legend>
			<div>
				<input type="radio" name="uStatus" id="Active" value="1" <?php echo $uStatActv; ?>>
				<label for="Active">Active</label>
				&nbsp;
				<input type="radio" name="uStatus" id="Inactive" value="0" <?php echo $uStatInactv; ?>>
				<label for="Inactive">Inactive</label>
			</div>
		</fieldset>
		</div>
		<hr>
		<div class="modalrow">
			<div class="modalcell">
				<label for="uname"><b>Name</b></label>
				<input type="text" name="uname"  value='<?php echo $userDetails['name']; ?>' required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="psw"><b>Password</b></label>&nbsp
				<input type="checkbox" name="renew" onclick="renewCheck(this.checked, 'usrPsw')">
				<label for="renew"> Renew Password</label>
				<input type="password" id="usrPsw" placeholder="Password" name="psw" disabled>
			</div>
		</div>
		<div class="modalrow">
			<div class="modalcell">
				<label for="contP"><b>Contact</b></label>
				<input type="text" name="contP" maxlength="10" placeholder="0123456789" pattern="\b\d{10}\b" value='<?php echo $userDetails['contactP']; ?>' required>
			</div>
			<div class="modaldiv"></div>
			<div class="modalcell">
				<label for="outlet"><b><?php echo $usrInfo['outType']; ?></b></label>
				<select id="brandSelect" name="outlet" required>
					<option value="" readonly >Select <?php echo $usrInfo['outType']; ?></option>
					
					<!--generating outlet list-->
					
					<?php foreach($outList as $out): ?>
						<?php if($out['id'] == $userDetails['outletId']): ?>
							<option value='<?php echo $out['id']; ?>' selected><?php echo $out['name']; ?></option>
						<?php else: ?>
							<option value='<?php echo $out['id']; ?>'><?php echo $out['name']; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<hr>
		<?php if($usrPerm['billV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage Bills</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewBill" name="billV" onclick="nestedChecks(this.checked, 'createBill', 'editBill')" <?php if($userDetails['billV'] == 1) : echo 'checked'; endif;?>>
				<label for="billV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['billC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createBill" name="billC" <?php if($userDetails['billV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['billC'] == 1) : echo 'checked'; endif;?>>
				<label for="billC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php if($usrPerm['billE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editBill" name="billE" <?php if($userDetails['billV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['billE'] == 1) : echo 'checked'; endif;?>>
				<label for="billE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['biltyV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage Bilty</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewBilty" name="biltyV" onchange="nestedChecks(this.checked, 'createBilty', 'editBilty')" <?php if($userDetails['biltyV'] == 1) : echo 'checked'; endif;?>>
				<label for="viewBilty"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['biltyC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createBilty" name="biltyC" <?php if($userDetails['biltyV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['biltyC'] == 1) : echo 'checked'; endif;?>>
				<label for="biltyC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['biltyE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editBilty" name="biltyE" <?php if($userDetails['biltyV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['biltyE'] == 1) : echo 'checked'; endif;?>>
				<label for="biltyE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['pdtV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage Products</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewPdt" name="pdtV" onchange="nestedChecks(this.checked, 'createPdt', 'editPdt')" <?php if($userDetails['pdtV'] == 1) : echo 'checked'; endif;?>>
				<label for="pdtV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['pdtC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createPdt" name="pdtC" <?php if($userDetails['pdtV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['pdtC'] == 1) : echo 'checked'; endif;?>>
				<label for="pdtC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['pdtE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editPdt" name="pdtE" <?php if($userDetails['pdtV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['pdtE'] == 1) : echo 'checked'; endif;?>>
				<label for="pdtE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['cstmrV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage Customers</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewCstmr" name="cstmrV" onchange="nestedChecks(this.checked, 'createCstmr', 'editCstmr')" <?php if($userDetails['cstmrV'] == 1) : echo 'checked'; endif;?>>
				<label for="cstmrV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['cstmrC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createCstmr" name="cstmrC" <?php if($userDetails['cstmrV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['cstmrC'] == 1) : echo 'checked'; endif;?>>
				<label for="cstmrC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['cstmrE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editCstmr" name="cstmrE" <?php if($userDetails['cstmrV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['cstmrE'] == 1) : echo 'checked'; endif;?>>
				<label for="cstmrE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['vhclV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage <?php echo $usrInfo['vType'] ?>s</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewVhcl" name="vhclV" onchange="nestedChecks(this.checked, 'createVhcl', 'editVhcl')" <?php if($userDetails['vhclV'] == 1) : echo 'checked'; endif;?>>
				<label for="vhclV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['vhclC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createVhcl" name="vhclC" <?php if($userDetails['vhclV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['vhclC'] == 1) : echo 'checked'; endif;?>>
				<label for="vhclC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['vhclE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editVhcl" name="vhclE" <?php if($userDetails['vhclV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['vhclE'] == 1) : echo 'checked'; endif;?>>
				<label for="vhclE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<hr>
		<?php endif; ?>
		<?php if($usrPerm['brandV'] == 1): ?>
		<div class="modalrow">
			<label><b>Manage <?php echo $usrInfo['bType'] ?>s</b></label>
		</div>
		<div class="inline-flex">
			<div class="modalcell">
				<input type="checkbox" id="viewBrand" name="brandV" onchange="nestedChecks(this.checked, 'createBrand', 'editBrand')" <?php if($userDetails['brandV'] == 1) : echo 'checked'; endif;?>>
				<label for="brandV"><b>&nbsp&nbsp<i class='bx bxs-binoculars'></i> View</b></label>
			</div>
			<?php if($usrPerm['brandC'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="createBrand" name="brandC" <?php if($userDetails['brandV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['brandC'] == 1) : echo 'checked'; endif;?>>
				<label for="brandC"><b>&nbsp&nbsp<i class='bx bxs-plus-circle'></i> Create</b></label>
			</div>
			<?php endif; ?>
			<?php if($usrPerm['brandE'] == 1): ?>
			<div class="modalcell">
				<input type="checkbox" id="editBrand" name="brandE" <?php if($userDetails['brandV'] == 0) : echo 'disabled'; endif;?> <?php if($userDetails['brandE'] == 1) : echo 'checked'; endif;?>>
				<label for="brandE"><b>&nbsp&nbsp<i class='bx bxs-edit'></i> Edit</b></label>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<input type="hidden" name="action" value="<?php echo $mode; ?>">
		<input type="hidden" name="dataId" value="<?php echo $dataId; ?>">
	  </form>
	  </div>

<?php endif; ?>
		<div class="modal-footer">
		<button type="submit" form="addUserForm" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
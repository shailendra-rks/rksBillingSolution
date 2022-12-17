<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once 'connection.php';
include('functions.php');
if(!isset($_SESSION['isUsrLoggedIn']) || $_SESSION['isUsrLoggedIn'] == "false"){
	log_msg("Unauthrised Access.|1|8|3");
	send_home("Please, Log In !!");
}
elseif($_SESSION['isUsrLoggedIn'] == "true") {
	$usrInfo = unserialize($_SESSION['usrInfo']);
	$usrParam = unserialize($_SESSION['usrParam']);
	$usrPerm = unserialize($_SESSION['usrPerm']);
	
	if($usrPerm['billC'] == 1){
		if($_SESSION['currentPage'] != "addBill.php"){
			$_SESSION['currentPage'] = "addBill.php";
			$_SESSION['internalMsg'] = "Logged In";
		}
		
		//sanitisation
		$mode = isset($_POST["action"]) ? test_input($_POST["action"]) : "add";
		$dataId = isset($_POST["dataId"]) ? test_input($_POST["dataId"]) : "";
		
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
		
		$sql = "CALL getCstmrList('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			$i = 0 ;
			while($cstmr = mysqli_fetch_assoc($result)){
				$cstmrList[$i] = $cstmr;
				$i += 1 ;
			}
		}
		mysqli_next_result($conn);
		
		$sql = "CALL getVhclList('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			$i = 0 ;
			while($vhcl = mysqli_fetch_assoc($result)){
				$vhclList[$i] = $vhcl;
				$i += 1 ;
			}
		}
		mysqli_next_result($conn);
		
		$sql = "CALL getPdtList('".$usrParam['usrId']."')";
		$result = mysqli_query($conn, $sql);
		if($result && mysqli_num_rows($result) > 0){
			$i = 0 ;
			while($pdt = mysqli_fetch_assoc($result)){
				$pdtList[$i] = $pdt;
				$i += 1 ;
			}
		}
		mysqli_next_result($conn);
		
		if($mode == "edit" && $usrPerm['billE'] == 1){
			
			$sql = "CALL getBillDetails('".$dataId."')";
			$result = mysqli_query($conn, $sql);
			if($result && mysqli_num_rows($result) > 0){
				$billDetails = mysqli_fetch_assoc($result);
			}
			mysqli_next_result($conn);
			
			if($billDetails['editPerm'] == 1){
				$editStatus = billEditPermission(strtotime($billDetails['billdate']), $usrInfo['billEditRule']);
			}
			else{
				$_SESSION['currentPage'] = "viewBill.php";
				$_SESSION['internalMsg'] = "This Bill can not be Edited.";
				log_msg("DOM Manipulation Detected.|1|87|4");
				send_to("viewBill.php");
			}			
			
			if($editStatus == "true"){			
				if($billDetails['adrsShipp'] == 0){
					$badrsstatus = "checked";
					$sadrsstatus = "";
				}
				else{
					$badrsstatus = "";
					$sadrsstatus = "checked";
				
					$adrsDataId = $billDetails['adrsShipp'];
					$sql = "CALL getShippingAddress('".$adrsDataId ."')";
					$result = mysqli_query($conn, $sql);
					if($result && mysqli_num_rows($result) > 0){
						$shippingAdrs = mysqli_fetch_assoc($result);
					}
					mysqli_next_result($conn);
				}
			
				if($usrInfo['biltyCust'] == 1){
					$itemCount = 1;
					$billDataId = $billDetails['id'];
					$sql = "CALL getBillItems('".$billDataId."')";
					$result = mysqli_query($conn, $sql);
					if($result && mysqli_num_rows($result) > 0){
						$billItemList = mysqli_fetch_assoc($result);
					}
					mysqli_close($conn);
				
					$mt = $cuft = $kg = "";
					
					if($billItemList['unit'] == 1){
						$mt = "selected";
					}
					elseif($billItemList['unit'] == 2){
						$cuft = "selected";
					}
					elseif($billItemList['unit'] == 3){
						$kg = "selected";
					}
				}
			
				elseif($usrInfo['biltyCust'] == 0){
					$itemCount = 0;
					$billDataId = $billDetails['id'];
					$sql = "CALL getBillItems('".$billDataId."')";
					$result = mysqli_query($conn, $sql);
					if($result && mysqli_num_rows($result) > 0){
						$i = 0 ;
						while($bill = mysqli_fetch_assoc($result)){
							$BillList[$i] = $bill;
							$i += 1 ;
							$itemCount += 1;
						}
					}
					mysqli_close($conn);
				}		
			}
			elseif($editStatus == "false"){
				$_SESSION['currentPage'] = "viewBill.php";
				$sql = "CALL freezeBill('".$dataId."')";
				$result = mysqli_query($conn, $sql);
				if($result && mysqli_num_rows($result) > 0){
					log_msg("Bill No".$dataId."  was locked.|1|153|2");
				}
				mysqli_close($conn);
				$_SESSION['internalMsg'] = "This Bill can not be Edited.";
				send_to("viewBill.php");
			}
		}
		include('header.php');																					//address tag
		include('topNav.php');																					//address tag
		include('sideBar.php');																					//address tag
	}
	else{
		log_msg("DOM Manipulation Detected.|1|165|5");
		send_dashboard("Access Denied !!");
	}
}
else{
	log_msg("Unauthrised Access.|1|170|3");
	send_home("Please, Log In !!");
}
?>
<div class="home-section">
<?php if($_SESSION['internalMsg'] != "Logged In"): ?>
	<div class="alertdiv">
		<span id="alert" class="msg position-fixed"><?php echo $_SESSION['internalMsg']; ?></span>
	</div>
<?php endif; ?>
<?php if($mode == "add" && $usrPerm['billC'] == 1): ?>
	<div class="pagehead">
		<h3>Create New Bill</h3>
	</div>
	<form id="addBillForm" action="pushBill.php" method="post" class="fullPage">
	<div class="billtop">
		<div class="billleft">
			<div class="inline-flex">
				<div class="pagecell">
					<label for="billDate"><b>Date</b></label>
					<div><input type="date" name="billDate" required></div>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="purOrdNum"><b>Purchase Order No.</b></label>
					<input type="text" name="purOrdNum">
				</div>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="outlet"><b><?php echo $usrInfo['outType']; ?></b></label>
					<select id="outSelect" name="outlet" required>
						<?php if($userParam['usrType'] == "user"): ?>
							<?php foreach($outList as $out): ?>		
								<?php if($out['id'] == $userInfo['outletId'] ): ?>
									<option value='<?php echo $out['id']; ?>'><?php echo $out['name']; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
						
						<option value="" readonly selected>Select <?php echo $usrInfo['outType']; ?></option>					
					<!--generating outlet list-->
						<?php foreach($outList as $out): ?>						
							<option value='<?php echo $out['id']; ?>'><?php echo $out['name']; ?></option>
						<?php endforeach; ?>
						
						<?php endif; ?>
					</select>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="cstmr"><b>Customer</b></label>
					<div class="inline-flex">
						<select id="cstmrSelect" name="cstmr" required>
							<option value="" readonly selected>Select Customer</option>
					
						<!--generating cstmr list-->
					
							<?php foreach($cstmrList as $cstmr): ?>
								<option value='<?php echo $cstmr['id']; ?>'><?php echo $cstmr['fname']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php if($usrPerm['cstmrC'] == 1): ?>
						<button id="addCstmr" class="shrtBt">
							<i class="bx bx-plus-circle"></i>
						</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="inline-flex">
	<?php if($usrPerm['vhclV'] == 1): ?>
				<div class="pagecell">
					<label for="vhcl"><b><?php echo $usrInfo['vType']; ?></b></label>
					<div class="inline-flex">
						<select id="vhclSelect" name="vhcl">
							<option value="" readonly selected>Select <?php echo $usrInfo['vType']; ?></option>
					
						<!--generating vhcl list-->
					
							<?php foreach($vhclList as $vhcl): ?>
								<option value='<?php echo $vhcl['id']; ?>'><?php echo $vhcl['vNo']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php if($usrPerm['vhclC'] == 1): ?>
						<button id="addVhcl" class="shrtBt">
							<i class="bx bx-plus-circle"></i>
						</button>
						<?php endif; ?>
					</div>
				</div>
				<div class="modaldiv"></div>
	<?php endif; ?>
				<div class="pagecell">
					<label for="unq1"><b><?php echo $usrInfo['unqFld1']; ?></b></label>
					<input type="text" name="unq1">
				</div>
			</div>
	<?php if($usrPerm['vhclV'] == 1): ?>
			<div class="inline-flex">
				<div class="pagecell">					
					<label for="trnsprtr"><b>Transport Company</b></label>
					<input type="text" name="trnsprtr">
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="ewayNum"><b>E-Way Bill Number</b></label>
					<input type="text" name="ewayNum">
				</div>
			</div>			
	<?php endif; ?>
		</div>
		<div class="pagediv"></div>
		<div class="billright">
			<fieldset class="minline-flex">
				<legend>Shipping Address</legend>
					<input type="radio" name="shipAdrState" value="0" checked onclick="setBillingAdrs(this.value)">
					<label for="Active">Billing Address</label>
					&nbsp;
					<input type="radio" name="shipAdrState" value="1" onclick="setBillingAdrs(this.value)">
					<label for="Inactive">Other</label>
			</fieldset>
			<div id="adrsSection">
			<div class="pagerow_sigle">
				<label for="address"><b>Line 1</b></label>
				<input type="text" name="address" required disabled>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="state"><b>State</b></label>
					<select id="stateSelect" name="state" onchange="makeSubmenu(this.value)" required disabled>
						<option value="" readonly selected>Select State</option>
						<option></option>
					</select>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="city"><b>City</b></label>
					<select id="citySelect" name="city" readonly required disabled>
						<option value="" readonly selected>Select City</option>
						<option></option>
					</select>
				</div>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="pin"><b>Pin Code</b></label>
					<input type="text" maxlength="6" placeholder="012345" name="pin" pattern="\b\d{6}\b" required disabled>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="country"><b>Country</b></label>
					<input type="text" name="country" value="India" readonly disabled>
				</div>
			</div>
			</div>
		</div>
	</div>
	<hr>
	<script type="text/javascript">
		var pdtArray= <?php echo json_encode($pdtList); ?>;
	</script>
	<div class="billbottom">
		<div class="billItems" id="billItems">
			<table>
				<thead>
					<tr>
						<th style="width:30%;">Product</th>
						<th style="width:5%;">Unit</th>
						<th style="width:5%;">Quantity</th>
						<th style="width:5%;">Rate</th>
						<th style="width:10%;">Amount</th>
						<th style="width:5%;">Tax-Slab</th>
						<th style="width:5%;">CGST</th>
						<th style="width:5%;">SGST</th>
						<th style="width:5%;">IGST</th>
						<th style="width:25%;">Total</th>
					</tr>
			<?php if($usrInfo['biltyCust'] == 0): ?>
					<tr>			<!--repeater row-->
						<td>
							<div class="pTabCell">
								<select id="pdtSelect" onchange="setPdtVals(this.selectedIndex)">
									<option value="" readonly selected>Select Product</option>
					
									<!--generating product list-->
					
									<?php foreach($pdtList as $pdt): ?>
										<option value='<?php echo $pdt['id']; ?>'><?php echo $pdt['name']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</td>
						<td>	
							<div class="pTabCell">
								<select id="unitSelect">
									<option value="1">mt</option>
									<option value="2">cu ft</option>
									<option value="3">kg</option>
								</select>
						</div>
						</td>
						<td>						
							<div class="pTabCell">
								<input type="number" id="qty" value="0" onchange="calcRowAmts(this.value)" step="any" min="0" disabled>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="rate" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="amt" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="taxslab" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="cgst" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="sgst" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="igst" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="inline-flex">
								<div class="pTabBtCell">
									<input type="number" id="gross" value="" step=".01" readonly>										
								</div>
								<button id="addItem" class="rowBt" disabled>
									<span>Add Item</span>
								</button>
							</div>
						</td>
					</tr>
					<tr><td colspan="10"><hr></td></tr>
					<?php endif; ?>
				</thead>
				<tbody id="addListItem">
				
			<?php if($usrInfo['biltyCust'] == 1): ?>
					<tr>			<!--stand alone row-->
						<td>
						<div class="pTabCell">
							<select id="pdtSelect" name="pdt" onchange="setPdtVals(this.selectedIndex)" required>
								<option value="" readonly selected>Select Product</option>
					
								<!--generating product list-->
					
								<?php foreach($pdtList as $pdt): ?>
									<option value='<?php echo $pdt['id']; ?>'><?php echo $pdt['name']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						</td>
						<td>	
						<div class="pTabCell">
							<select id="unitSelect" name="unit" required>
								<option value="1">mt</option>
								<option value="2">cu ft</option>
								<option value="3">kg</option>
							</select>
						</div>
						</td>
						<td>						
						<div class="pTabCell">
							<input type="number" id="qty" name="qty" value="0" onchange="calcAmts(this.value)" step="any" min="0">						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="rate" name="rate" value=""" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="amt" name="amt" value=""" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="taxslab" name="taxslab" value="" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="cgst" name="cgst" value="" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="sgst" name="sgst" value="" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="igst" name="igst" value="" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="billgross" name="billgross" value="" step="any" readonly>						
						</div>
						</td>
					</tr>				
			<?php endif; ?>	
				</tbody>
				<tfoot>
			<?php if($usrInfo['biltyCust'] == 0): ?>
					<tr>				<!--Totalar row-->
						<td colspan="4">
							<div class="pTabCell">
								<b>Bill Item's Total</b>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billamt" name="billamt" value="0" step="any" readonly>
							</div>
						</td>
						<td>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billcgst" name="billcgst" value="0" step="any" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billsgst" name="billsgst" value="0" step="any" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billigst" name="billigst" value="0" step="any" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billgross" name="billgross" value="0" step="any" readonly>
							</div>
						</td>
					</tr>			
			<?php endif; ?>				
					<tr><td colspan="10"><hr></td></tr>
					<tr>				<!--frieght rate row-->
						<td colspan="2">
						<div class="pTabCell">
							<b>Frieght Rate for transporation if applicable.</b>
						</div>
						</td>
						<td>						
						<div class="pTabCell">
							<input type="number" id="fqty" name="fqty" value="0" onchange="calcFrieghtAmts()" step="any" min="0">						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="frate" name="frate" value="0" step="any" min="0" onchange="calcFrieghtAmts()">						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="famt" name="famt" value="" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="ftaxslab" name="ftaxslab" onchange="calcFrieghtAmts()" value="0" step="any" min="0">						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="fcgst" name="fcgst" value="" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="fsgst" name="fsgst" value="" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="figst" name="figst" value="" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="fgross" name="fgross" value="" step="any" readonly>
						</div>
						</td>
					</tr>				
					<tr><td colspan="10"><hr></td></tr>
					<tr>				<!--payble row-->
						<td colspan="6"><b>Amount Payble</b>
						</td>
						<td colspan="3">
						<div class="inline-flex">
							<div class="pagecell">
							<input type="number" id="rndOff" name="rndOff" value="0" pattern="^\d*(\.\d{0,2})?$" step="any" min="0" onchange="paybleAmount()">
							</div>
							<div class="pagecell">
							<button id="round_grand" class="rowBt">
								<b>Round Off</b>
							</button>
							</div>
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="grand" name="grand" value="0" step="any" readonly>
						</div>
						</td>
					</tr>
				</tfoot>				
			</table>
		</div>
	</div>
	<input type="hidden" name="action" value="<?php echo $mode; ?>">
	</form>
	<div class="fullPageFrmBt">
		<button type="submit" id="submt" form="addBillForm" class="btn btn-primary" disabled>Save</button>
        <button type="button" id="bt_back" class="btn btn-secondary" data-dismiss="modal">Close</button>
	</div>
<?php elseif($mode == "edit" && $usrPerm['billE'] == 1): ?>
	<div class="pagehead">
		<h3>Edit Bill Number <label class="billNo"><?php echo $billDetails['billNum']; ?></label></h3>
	</div>
	<form id="addBillForm" action="pushBill.php" method="post" class="fullPage" onchange="editFormTouched()">
	<script>
		setItemCount(<?php echo $itemCount; ?>);
	</script>
	<div class="billtop">
		<div class="billleft">
			<div class="inline-flex">
				<div class="pagecell">
					<label for="billDate"><b>Date</b></label>
					<div><input type="date" name="billDate" value="<?php echo $billDetails['billdate']; ?>" required></div>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="purOrdNum"><b>Purchase Order No.</b></label>
					<input type="text" name="purOrdNum" value="<?php echo $billDetails['prchsOrdNum']; ?>">
				</div>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="outlet"><b><?php echo $usrInfo['outType']; ?></b></label>
					<select id="outSelect" name="outlet" required>
						<?php if($userParam['usrType'] == "user"): ?>
							<?php foreach($outList as $out): ?>		
								<?php if($out['id'] == $userInfo['outletId'] ): ?>
									<option value='<?php echo $out['id']; ?>'><?php echo $out['name']; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
						<?php foreach($outList as $out): ?>	
							<?php if($out['id'] == $billDetails['outId']): ?>
								<option value='<?php echo $out['id']; ?>' selected><?php echo $out['name']; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
						
						<?php endif; ?>
					</select>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="cstmr"><b>Customer</b></label>
					<div class="inline-flex">
						<select id="cstmrSelect" name="cstmr" required>
							<option value="" readonly>Select Customer</option>
					
						<!--generating product list-->
					
							<?php foreach($cstmrList as $cstmr): ?>
								<?php if($cstmr['id'] == $billDetails['cstmrId']): ?>
									<option value='<?php echo $cstmr['id']; ?>' selected><?php echo $cstmr['fname']; ?></option>
								<?php else: ?>
									<option value='<?php echo $cstmr['id']; ?>'><?php echo $cstmr['fname']; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
						<?php if($usrPerm['cstmrC'] == 1): ?>
						<button id="addCstmr" class="shrtBt">
							<i class="bx bx-plus-circle"></i>
						</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="vhcl"><b><?php echo $usrInfo['vType']; ?></b></label>
					<div class="inline-flex">
						<select id="vhclSelect" name="vhcl">
							<option value="" readonly selected>Select <?php echo $usrInfo['vType']; ?></option>
					
						<!--generating product list-->
					
							<?php foreach($vhclList as $vhcl): ?>
								<?php if($vhcl['id'] == $billDetails['vhclId']): ?>
									<option value='<?php echo $vhcl['id']; ?>' selected><?php echo $vhcl['vNo']; ?></option>
								<?php else: ?>
									<option value='<?php echo $vhcl['id']; ?>'><?php echo $vhcl['vNo']; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
						<?php if($usrPerm['vhclC'] == 1): ?>
						<button id="addVhcl" class="shrtBt">
							<i class="bx bx-plus-circle"></i>
						</button>
						<?php endif; ?>
					</div>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="unq1"><b><?php echo $usrInfo['unqFld1']; ?></b></label>
					<input type="text" value="<?php echo $billDetails['unqFld1']; ?>" name="unq1">
				</div>
			</div>
			<div class="inline-flex">
				<div class="pagecell">					
					<label for="trnsprtr"><b>Transport Company</b></label>
					<input type="text" value="<?php echo $billDetails['transporter']; ?>" name="trnsprtr">
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="ewayNum"><b>E-Way Bill Number</b></label>
					<input type="text" value="<?php echo $billDetails['eway']; ?>" name="ewayNum">
				</div>
			</div>			
		</div>
		<div class="pagediv"></div>
		<div class="billright">
			<fieldset class="minline-flex">
				<legend>Shipping Address</legend>
					<input type="radio" name="shipAdrState" onclick="setBillingAdrs(this.value)" value="0" <?php echo $badrsstatus; ?>>
					<label for="Active">Billing Address</label>
					&nbsp;
					<input type="radio" name="shipAdrState" onclick="setBillingAdrs(this.value)" value="1" <?php echo $sadrsstatus; ?>>
					<label for="Inactive">Other</label>
			</fieldset>
			<?php if($billDetails['adrsShipp'] == 0): ?>
			<div id="adrsSection">
			<div class="pagerow_sigle">
				<label for="address"><b>Line 1</b></label>
				<input type="text" name="address" required disabled>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="state"><b>State</b></label>
					<select id="stateSelect" name="state" onchange="makeSubmenu(this.value)" required disabled>
						<option value="" readonly selected>Select State</option>
						<option></option>
					</select>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="city"><b>City</b></label>
					<select id="citySelect" name="city" readonly required disabled>
						<option value="" readonly selected>Select City</option>
						<option></option>
					</select>
				</div>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="pin"><b>Pin Code</b></label>
					<input type="text" maxlength="6" placeholder="012345" name="pin" pattern="\b\d{6}\b" required disabled>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="country"><b>Country</b></label>
					<input type="text" name="country" value="India" readonly disabled>
				</div>
			</div>
			</div>			
			<?php else: ?>
			<div id="adrsSection">
			<div class="pagerow_sigle">
				<label for="address"><b>Line 1</b></label>
				<input type="text" name="address" value="<?php echo $shippingAdrs['address']; ?>" required>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="state"><b>State</b></label>
					<input type="text" id="stateSelect" name="state" value="<?php echo $shippingAdrs['state']; ?>" required>
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="city"><b>City</b></label>
					<input type="text" id="citySelect" name="city" value="<?php echo $shippingAdrs['city']; ?>" required>
				</div>
			</div>
			<div class="inline-flex">
				<div class="pagecell">
					<label for="pin"><b>Pin Code</b></label>
					<input type="text" maxlength="6" placeholder="012345" name="pin" pattern="\b\d{6}\b" value="<?php echo $shippingAdrs['pin']; ?>">
				</div>
				<div class="modaldiv"></div>
				<div class="pagecell">
					<label for="country"><b>Country</b></label>
					<input type="text" name="country" value="India" value="<?php echo $shippingAdrs['country']; ?>" readonly>
				</div>
			</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<hr>
	<script type="text/javascript">
		var pdtArray= <?php echo json_encode($pdtList); ?>;
	</script>
	<div class="billbottom">
		<div class="billItems" id="billItems">
			<table>
				<thead>
					<tr>
						<th style="width:30%;">Product</th>
						<th style="width:5%;">Unit</th>
						<th style="width:5%;">Quantity</th>
						<th style="width:5%;">Rate</th>
						<th style="width:10%;">Amount</th>
						<th style="width:5%;">Tax-Slab</th>
						<th style="width:5%;">CGST</th>
						<th style="width:5%;">SGST</th>
						<th style="width:5%;">IGST</th>
						<th style="width:25%;">Total</th>
					</tr>
			<?php if($usrInfo['biltyCust'] == 0): ?>
					<tr>			<!--repeater row-->
						<td>
							<div class="pTabCell">
								<select id="pdtSelect" onchange="setPdtVals(this.selectedIndex)">
									<option value="" readonly selected>Select Product</option>
					
									<!--generating product list-->
					
									<?php foreach($pdtList as $pdt): ?>
										<option value='<?php echo $pdt['id']; ?>'><?php echo $pdt['name']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</td>
						<td>	
							<div class="pTabCell">
								<select id="unitSelect">
									<option value="1">mt</option>
									<option value="2">cu ft</option>
									<option value="3">kg</option>
								</select>
						</div>
						</td>
						<td>						
							<div class="pTabCell">
								<input type="number" id="qty" value="0" onchange="calcRowAmts(this.value)" step="any" min="0" disabled>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="rate" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="amt" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="taxslab" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="cgst" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="sgst" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="igst" value="" step="any" readonly>						
							</div>
						</td>
						<td>
							<div class="inline-flex">
								<div class="pTabBtCell">
									<input type="number" id="gross" value="" step=".01" readonly>										
								</div>
								<button id="addItem" class="rowBt" disabled>
									<span>Add Item</span>
								</button>
							</div>
						</td>
					</tr>
					<tr><td colspan="10"><hr></td></tr>
			<?php endif; ?>
				</thead>
				<tbody id="addListItem">
			<?php if($usrInfo['biltyCust'] == 0): ?>
				<?php $i = 1; foreach($BillList as $bill): ?>
					<tr id="itemrow_<?php echo $i; ?>">		<!-- old bill records-->
						<td>
							<div class="pTabCell">
								<input type="text" name="pdtid[]" hidden value="<?php echo $bill['pdtId']; ?>">
								<?php foreach($pdtList as $pdt): ?>
									<?php if($pdt['id'] == $bill['pdtId']): ?>
										<input type="text" value="<?php echo $pdt['name']; ?>" readonly>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<select name="pdtunit[]" readonly>
								<?php if($bill['unit'] == 1): ?>
									<option value="1">mt</option>
								<?php elseif($bill['unit'] == 2): ?>
									<option value="2">cu ft</option>
								<?php elseif($bill['unit'] == 3): ?>
									<option value="3">kg</option>
								<?php endif; ?>
								</select>								
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" name="qty[]" value="<?php echo $bill['qty']; ?>" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" name="rate[]" value="<?php echo $bill['rate']; ?>" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="amt_<?php echo $i; ?>" name="amt[]" value="<?php echo $bill['net']; ?>" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" name="taxslab[]" value="<?php echo $bill['taxslab']; ?>" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="cgst_<?php echo $i; ?>" name="cgst[]" value="<?php echo $bill['cgst']; ?>" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" name="sgst[]" value="<?php echo $bill['sgst']; ?>" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="igst_<?php echo $i; ?>" name="igst[]" value="<?php echo $bill['igst']; ?>" readonly>
							</div>
						</td>
						<td>
							<div class="inline-flex">
								<div class="pTabBtCell">
									<input type="number" id="gross_<?php echo $i; ?>" name="gross[]" value="<?php echo $bill['gross']; ?>" readonly>
								</div>
								<button id="<?php echo $i; ?>" class="bt_remove">
									<span>Remove</span>
								</button>
							</div>
						</td>
					</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			<?php endif; ?>	
			<?php if($usrInfo['biltyCust'] == 1): ?>
					<tr>			<!--stand alone row-->
						<td>
						<div class="pTabCell">
							<select id="pdtSelect" name="pdt" onchange="setPdtVals(this.selectedIndex)" required>
								<option value="" readonly>Select Product</option>
					
								<!--generating product list-->
					
								<?php foreach($pdtList as $pdt): ?>
									<?php if($pdt['id'] == $billItemList['pdtId']): ?>
									<option value='<?php echo $pdt['id']; ?>' selected ><?php echo $pdt['name']; ?></option>
									<?php else: ?>
									<option value='<?php echo $pdt['id']; ?>'><?php echo $pdt['name']; ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</div>
						</td>
						<td>	
						<div class="pTabCell">
							<select id="unitSelect" name="unit" required>
								<option value="1" <?php echo $mt; ?>>mt</option>
								<option value="2" <?php echo $cuft; ?>>cu ft</option>
								<option value="3" <?php echo $kg; ?>>kg</option>
							</select>
						</div>
						</td>
						<td>						
						<div class="pTabCell">
							<input type="number" id="qty" name="qty" value="<?php echo $billItemList['qty']; ?>" onchange="calcAmts(this.value)" step="any" min="0" required>				
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="rate" name="rate" value="<?php echo $billItemList['rate']; ?>" step="any" readonly>
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="amt" name="amt" value="<?php echo $billDetails['pdtnet']; ?>" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="taxslab" name="taxslab" value="<?php echo $billItemList['taxslab']; ?>" step="any" readonly>	
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="cgst" name="cgst" value="<?php echo $billDetails['pdtcgst']; ?>" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="sgst" name="sgst" value="<?php echo $billDetails['pdtsgst']; ?>" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="igst" name="igst" value="<?php echo $billDetails['pdtigst']; ?>" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="billgross" name="billgross" value="<?php echo $billDetails['pdtgross']; ?>" step="any" readonly>						
						</div>
						</td>
					</tr>				
			<?php endif; ?>	
				</tbody>
				<tfoot>
			<?php if($usrInfo['biltyCust'] == 0): ?>
					<tr>				<!--Totalar row-->
						<td colspan="4">
							<div class="pTabCell">
								<b>Bill Item's Total</b>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billamt" name="billamt" value="<?php echo $billDetails['pdtnet']; ?>" step="any" readonly>
							</div>
						</td>
						<td>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billcgst" name="billcgst" value="<?php echo $billDetails['pdtcgst']; ?>" step="any" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billsgst" name="billsgst" value="<?php echo $billDetails['pdtsgst']; ?>" step="any" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billigst" name="billigst" value="<?php echo $billDetails['pdtigst']; ?>" step="any" readonly>
							</div>
						</td>
						<td>
							<div class="pTabCell">
								<input type="number" id="billgross" name="billgross" value="<?php echo $billDetails['pdtgross']; ?>" step="any" readonly>
							</div>
						</td>
					</tr>			
			<?php endif; ?>				
					<tr><td colspan="10"><hr></td></tr>
					<tr>				<!--frieght rate row-->
						<td colspan="2">
						<div class="pTabCell">
							<b>Frieght Rate for transporation if applicable.</b>
						</div>
						</td>
						<td>						
						<div class="pTabCell">
							<input type="number" id="fqty" name="fqty" value="<?php echo $billDetails['fQty']; ?>" onchange="calcFrieghtAmts()" step="any" min="0">						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="frate" name="frate" value="<?php echo $billDetails['fRate']; ?>" onchange="calcFrieghtAmts()" step="any" min="0">						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="famt" name="famt" value="<?php echo $billDetails['fNet']; ?>" readonly step="any">						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="ftaxslab" name="ftaxslab" onchange="calcFrieghtAmts()" value="<?php echo $billDetails['fTaxRt']; ?>" step="any" min="0">						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="fcgst" name="fcgst" value="<?php echo $billDetails['fcgst']; ?>" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="fsgst" name="fsgst" value="<?php echo $billDetails['fsgst']; ?>" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="figst" name="figst" value="<?php echo $billDetails['figst']; ?>" step="any" readonly>						
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="fgross" name="fgross" value="<?php echo $billDetails['fgross']; ?>" step="any" readonly>
						</div>
						</td>
					</tr>				
					<tr><td colspan="10"><hr></td></tr>
					<tr>				<!--payble row-->
						<td colspan="6"><b>Amount Payble</b>
						</td>
						<td colspan="3">
						<div class="inline-flex">
							<div class="pagecell">
							<input type="number" id="rndOff" name="rndOff" value="<?php echo $billDetails['rndOff']; ?>" pattern="^\d*(\.\d{0,2})?$" step="any" min="0" onchange="paybleAmount()">
							</div>
							<div class="pagecell">
							<button id="round_grand" class="rowBt">
								<b>Round Off</b>
							</button>
							</div>
						</div>
						</td>
						<td>
						<div class="pTabCell">
							<input type="number" id="grand" name="grand" value="<?php echo $billDetails['grand']; ?>" step="any" readonly>
						</div>
						</td>
					</tr>
				</tfoot>				
			</table>
		</div>
	</div>
	<input type="hidden" name="action" value="<?php echo $mode; ?>">
	<input type="hidden" name="dataId" value="<?php echo $dataId; ?>">
	</form>
	<div class="fullPageFrmBt">
		<button type="submit" id="submt" form="addBillForm" class="btn btn-primary" disabled>Save</button>
        <button type="button" id="bt_back" class="btn btn-secondary" data-dismiss="modal">Close</button>
	</div>
<?php endif; ?>
<div id="billModal"></div>
</div>
</html>
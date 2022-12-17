var Itemcount = 0;
$(document).ready(function() {
	makeStateMenu("India");
	
	$('#addCstmr').click(function(e){
		e.preventDefault();
		
		openModal("#billModal","#addCustomerModal","#addCstmr","add",null);
        
    });
	
	$('#addVhcl').click(function(e){
		e.preventDefault();
		
		openModal("#billModal","#addVehicleModal","#addVhcl","add",null);
        
    });
	
	var count = 0;
	$('#addItem').click(function(e){
		e.preventDefault();
		count++;
		Itemcount++;
		
		var pdtId = $("#pdtSelect").val();
		var pdtName = $("#pdtSelect option:selected").text();
		var pdtUnitId = $("#unitSelect").val();
		var pdtUnit = $("#unitSelect option:selected").text();		
		var pdtqty = $("#qty").val();		
		var pdtrate = $("#rate").val();		
		var pdtamt = $("#amt").val();	
		var taxslab = $("#taxslab").val();	
		var pdtcgst = $("#cgst").val();
		var pdtsgst = $("#sgst").val();
		var pdtigst = $("#igst").val();
		var pdtgross = $("#gross").val();
		
		addToBillTotal(pdtamt, pdtcgst, pdtigst, pdtgross);
				
		$('#addListItem').append('<tr id="itemrow_'+count+'"><td><div class="pTabCell"><input type="text" name="pdtid[]" hidden value="'+pdtId+'"><input type="text" value="'+pdtName+'" readonly></div></td><td><div class="pTabCell"><select name="pdtunit[]" readonly><option value= "'+pdtUnitId+'" >'+pdtUnit+'</option></select></div></td><td><div class="pTabCell"><input type="number" name="qty[]" value="'+pdtqty+'" readonly></div></td><td><div class="pTabCell"><input type="number" name="rate[]" value="'+pdtrate+'" readonly></div></td><td><div class="pTabCell"><input type="number" id="amt_'+count+'" name="amt[]" value="'+pdtamt+'" readonly></div></td><td><div class="pTabCell"><input type="number" name="taxslab[]" value="'+taxslab+'" readonly></div></td><td><div class="pTabCell"><input type="number" id="cgst_'+count+'" name="cgst[]" value="'+pdtcgst+'" readonly></div></td><td><div class="pTabCell"><input type="number" name="sgst[]" value="'+pdtsgst+'" readonly></div></td><td><div class="pTabCell"><input type="number" id="igst_'+count+'" name="igst[]" value="'+pdtigst+'" readonly></div></td><td><div class="inline-flex"><div class="pTabBtCell"><input type="number" id="gross_'+count+'" name="gross[]" value="'+pdtgross+'" readonly></div><button id="'+count+'" class="bt_remove"><span>Remove</span></button></div></td></tr>');
		
		$("#pdtSelect").val("");
		$("#unitSelect").val("");		
		$("#qty").val("");		
		$("#rate").val("");		
		$("#amt").val("");	
		$("#taxslab").val("");	
		$("#cgst").val("");
		$("#sgst").val("");
		$("#igst").val("");
		$("#gross").val("");
		document.getElementById("addItem").setAttribute("disabled","");
		readySubmit();		
	});
	
	$(document).on('click','.bt_remove', function(e){		
		e.preventDefault();
		var bt_id = $(this).attr("id");
		var pdtamt = $("#amt_"+bt_id+"").val();
		var pdtcgst = $("#cgst_"+bt_id+"").val();
		var pdtigst = $("#igst_"+bt_id+"").val();
		var pdtgross = $("#gross_"+bt_id+"").val();
		
		removeFromBillTotal(pdtamt, pdtcgst, pdtigst, pdtgross);
		
		$("#itemrow_"+bt_id+"").remove();
		Itemcount--;
		
		readySubmit();
	});
	
	$('#round_grand').click(function(e){	
		e.preventDefault();
		var slack = Number(document.getElementById("grand").value) % 1;
		var old = Number(document.getElementById("rndOff").value);
		document.getElementById("rndOff").value = old + slack;
		paybleAmount();
	});
	
	$('#bt_back').click(function(e){
		e.preventDefault();		
		$('#viewBills')[0].click();        
    });
});

function setPdtVals(id_pdt){
	id_pdt--;
	if(id_pdt < 0){
		document.getElementById("rate").value = "";
		document.getElementById("taxslab").value = "";
		document.getElementById("qty").setAttribute("disabled","");
	}
	else{
		document.getElementById("rate").value = pdtArray[id_pdt]['rate'];
		document.getElementById("taxslab").value = pdtArray[id_pdt]['cgst'];
		document.getElementById("qty").removeAttribute("disabled","");
	}	
}

function calcRowAmts(val){
	var rate = document.getElementById("rate").value;
	var taxrate = document.getElementById("taxslab").value;
	var amt = val*rate;
	var cgst = amt*taxrate/100;
	var total = amt + cgst + cgst ;
	document.getElementById("amt").value = amt;	
	document.getElementById("cgst").value = cgst;
	document.getElementById("sgst").value = cgst;	
	document.getElementById("gross").value = total;
	if(total > 0){		
		document.getElementById("addItem").removeAttribute("disabled","");
	}
}

function calcAmts(val){
	Itemcount = 1;
	var rate = document.getElementById("rate").value;
	var taxrate = document.getElementById("taxslab").value;
	var amt = val*rate;
	var cgst = amt*taxrate/100;
	var total = amt + cgst + cgst ;
	document.getElementById("amt").value = amt;	
	document.getElementById("cgst").value = cgst;
	document.getElementById("sgst").value = cgst;	
	document.getElementById("billgross").value = total;
	
	resetDiscount();
	readySubmit();
}

function readySubmit(){
	if(Itemcount > 0){
		document.getElementById("submt").removeAttribute("disabled","");		
	}
	else{
		document.getElementById("submt").setAttribute("disabled","");	
	}
}

function addToBillTotal(amt, cgst, igst, gross){
	var gamt = Number(document.getElementById("billamt").value) + Number(amt);
	var gcgst = Number(document.getElementById("billcgst").value) + Number(cgst);
	var gigst = Number(document.getElementById("billigst").value) + Number(igst);
	var total = Number(document.getElementById("billgross").value) + Number(gross);
	
	document.getElementById("billamt").value = gamt;
	document.getElementById("billcgst").value = gcgst;
	document.getElementById("billsgst").value = gcgst;
	document.getElementById("billigst").value = gigst;
	document.getElementById("billgross").value = total;
	
	resetDiscount();
}

function removeFromBillTotal(amt, cgst, igst, gross){
	var gamt = Number(document.getElementById("billamt").value) - Number(amt);
	var gcgst = Number(document.getElementById("billcgst").value) - Number(cgst);
	var gigst = Number(document.getElementById("billigst").value) - Number(igst);
	var total = Number(document.getElementById("billgross").value) - Number(gross);
	
	document.getElementById("billamt").value = gamt;
	document.getElementById("billcgst").value = gcgst;
	document.getElementById("billsgst").value = gcgst;
	document.getElementById("billigst").value = gigst;
	document.getElementById("billgross").value = total;
	
	resetDiscount();
}

function calcFrieghtAmts(){
	var frate = document.getElementById("frate").value;
	var fqty = document.getElementById("fqty").value;
	var ftax = document.getElementById("ftaxslab").value;
	var famt = frate*fqty;
	var fcgst = ftax*famt/100;
	var fgross = famt + (2*fcgst);
	document.getElementById("famt").value = famt;
	document.getElementById("fcgst").value = fcgst;
	document.getElementById("fsgst").value = fcgst;
	document.getElementById("fgross").value = fgross;
	
	resetDiscount();
}

function paybleAmount(){
	var total = Number(document.getElementById("billgross").value)
	var fgross = Number(document.getElementById("fgross").value);
	var rndOff = Number(document.getElementById("rndOff").value);
	var payble = total + fgross - rndOff;
	document.getElementById("grand").value = payble;	
}

function setBillingAdrs(val){
	if(val==0){
		$("#adrsSection input").attr("disabled",true);
		$("#adrsSection select").attr("disabled",true);
	}
	else if(val==1){
		$("#adrsSection input").attr("disabled",false);
		$("#adrsSection select").attr("disabled",false);
	}
}
function resetDiscount(){
	document.getElementById("rndOff").value = 0;
	paybleAmount();
}

function editFormTouched(){
	readySubmit();
}

function setItemCount(val){
	Itemcount = val;
}
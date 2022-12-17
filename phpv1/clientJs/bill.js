function addBill(){
	document.getElementById("mode").value = "add";
	document.getElementById("addBillForm").submit();
}

function printBill(val){
	document.getElementById("print_bill_num").value = val;
	document.getElementById("printBillForm").submit();
}

function editBill(val){
	document.getElementById("mode").value = "edit";	
	document.getElementById("refData").value = val;
	document.getElementById("addBillForm").submit();
}
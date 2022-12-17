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

var count = 0;
function setBulkPrint(val, id){
	html_input = "<input type='hidden' id='print_bill_" + id + "' name='billIdList[]' value='" + id + "'>";
	if(val == true){
		count++;
		$("#bulkPrintForm").append(html_input);
	}
	else if(val == false){
		count--;
		id_text = "#print_bill_" + id ;
		$(id_text).remove();
	}
	
	if(count > 1 && count < 40){
		document.getElementById("bulkPrint").removeAttribute("disabled","");
	}
	else{
		document.getElementById("bulkPrint").setAttribute("disabled","");
	}
}
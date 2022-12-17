$(document).ready(function() {
	
$('#data_table').DataTable({
	"dom":'<"tab_top"f<"controls">B>t<"tab_foot"lip>',
	columnDefs: [
		{ orderable: false, width: 24, targets: [0,1,2] }
	],
	order: [[0, 'desc']],
	buttons: [
        {
            extend:    'print',
            text:      '<i class="bx bxs-printer dt_icons"><span class="dt_links"> Print</span></i>',
            titleAttr: 'Print'
        },
        {
			extend:    'excelHtml5',
            text:      '<i class="bx bxs-file-doc dt_icons"><span class="dt_links"> Excel</span></i>',
            titleAttr: 'Excel'
        },
        {
            extend:    'csvHtml5',
            text:      '<i class="bx bxs-file-doc dt_icons"><span class="dt_links"> CSV</span></i>',
            titleAttr: 'CSV'
        },
        {
            extend:    'pdfHtml5',
            text:      '<i class="bx bxs-file-pdf dt_icons"><span class="dt_links"> PDF</span></i>',
            titleAttr: 'PDF'
        }
	],
	initComplete: function() { 
        var btns = $('.dt-button');
        btns.removeClass('dt-button');
		btns.addClass('dt_buttons');
    }
});

	$('.control_group').appendTo('.controls');
	
	$('#alert').show(0).delay(5000).hide(0);
} );

function openModal(destinationId, modalId, lastElement, mode, refId) {
	
	$.post( "/php/Modals.php",{action: mode, dataId: refId}, function( htmlContents ) {
		
	    $(destinationId).html( htmlContents ); 
		
        //Show the Modal once you get html contents
		
        $(modalId).modal('show');
		$('.modal-open :focusable')
			.addClass("unfocus")
			.attr("tabindex", -1)
			.attr("disabled", true);
			if(modalId == "#addCustomerModal" || modalId == "#addOutletModal" ){
				makeStateMenu("India");
			}
		//make local storage to save lastfocused element
		
		var lastFocusedElement = lastElement;
		
		//to be worked on
    })
	.done(function(){
		$(modalId).on('hidden.bs.modal', function(){
			$('.unfocus').attr("tabindex", 0)
			.removeClass("unfocus")
			.removeAttr("disabled");
		});
	})
	.always(function(){
		
	})	;
}

function setValidationCustomer(value){
	if(value == "0"){
		document.getElementById("gstnumber").removeAttribute("readonly","");
		document.getElementById("gstnumber").setAttribute("required","");
		document.getElementById("gstnumber").setAttribute("pattern","^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$");
	}
	else if(value == "1"){
		document.getElementById("gstnumber").setAttribute("readonly","");
		document.getElementById("gstnumber").removeAttribute("required","");
		document.getElementById("gstnumber").removeAttribute("pattern","");
	}
}

function setGstSlabs(cgst){
	document.getElementById("sgst").value = cgst;
	document.getElementById("igst").value = 2 * cgst;	
}

function renewCheck(val, id){
	if(val == true){
		document.getElementById(id).removeAttribute("disabled","");
		document.getElementById(id).setAttribute("required","");
	}
	else if(val == false){
		document.getElementById(id).removeAttribute("required","");
		document.getElementById(id).setAttribute("disabled","");
	}
}

function setAddressMode(val){
	if(val==0){
		document.getElementById("newAdrs").setAttribute("disabled","");
		document.getElementById("newAdrs").setAttribute("hidden","");
		$("#newAdrs input").attr("disabled",true);
		$("#newAdrs select").attr("disabled",true);
		document.getElementById("adrsSelect").removeAttribute("disabled","");
		document.getElementById("adrsSelect").removeAttribute("hidden","");
		document.getElementById("currentAdrs").removeAttribute("disabled","");
		document.getElementById("currentAdrs").removeAttribute("hidden","");
		$("#currentAdrs input").attr("disabled",false);
		$("#currentAdrs select").attr("disabled",false);
	}
	else if(val==1){		
		document.getElementById("currentAdrs").setAttribute("disabled","");
		document.getElementById("currentAdrs").setAttribute("hidden","");
		$("#currentAdrs input").attr("disabled",true);
		$("#currentAdrs select").attr("disabled",true);
		document.getElementById("adrsSelect").setAttribute("disabled","");
		document.getElementById("adrsSelect").setAttribute("hidden","");
		document.getElementById("newAdrs").removeAttribute("disabled","");
		document.getElementById("newAdrs").removeAttribute("hidden","");
		$("#newAdrs input").attr("disabled",false);
		$("#newAdrs select").attr("disabled",false);
	}
}

function setAdrsContent(val){
	val = val -1;
	document.getElementById("adrs").removeAttribute("readonly","");
	document.getElementById("state").removeAttribute("readonly","");
	document.getElementById("city").removeAttribute("readonly","");
	document.getElementById("pin").removeAttribute("readonly","");
	document.getElementById("adrsId").value = AdrsArray[val]['id'];
	document.getElementById("adrs").value = AdrsArray[val]['address'];
	document.getElementById("state").value = AdrsArray[val]['state'];
	document.getElementById("city").value = AdrsArray[val]['city'];
	document.getElementById("pin").value = AdrsArray[val]['pin'];
	if(AdrsArray[val]['linked'] == 1){
		document.getElementById("adrs").setAttribute("readonly","");
		document.getElementById("state").setAttribute("readonly","");
		document.getElementById("city").setAttribute("readonly","");
		document.getElementById("pin").setAttribute("readonly","");
		
	}
}
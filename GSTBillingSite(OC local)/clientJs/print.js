$(document).ready(function() {
	
	convertToWords();

	var printCopy = siteSettings['printCopy'];
	
	if(printCopy > 1){
		writeFirstCopy();
		if(printCopy > 2){
			writeSecondCopy();
		}
	}

});

function convertToWords(){
	var a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
	var b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];
	
	num = $("#numeral").text();
	num = Math.round(num);
    if ((num = num.toString()).length > 9) return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
    if (!n) return; var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'Only.' : 'Only.';
    $("#words").html("Rs. "+str);
}

function writeFirstCopy(){
	var html_print = $("#original").html();
	html_print = html_print.replace("Original Copy","Customer Copy");
	if(siteSettings['printMode'] == 1){
		var html = "<div class='print_view new' id='firstcopy'></div>";
		$("body").append(html);
		$("#firstcopy").append(html_print);
	}
	else if(siteSettings['printMode'] == 2){
		$("#original").append(html_print);
	}
	else if(siteSettings['printMode'] == 3){
		$("#original").append(html_print);
	}
}

function writeSecondCopy(){
	if(siteSettings['printMode'] == 1){
		var html_print = $("#original").html();
		html_print = html_print.replace("Original Copy","Transporter Copy");
		var html = "<div class='print_view new' id='secondcopy'></div>";
		$("body").append(html);
		$("#secondcopy").append(html_print);
	}
	else if(siteSettings['printMode'] == 2){
		var html_print = $("#original :first-child").html();
		html_print = html_print.replace("Original Copy","Transporter Copy");
		html_print = "<div class='print_section'>" + html_print + "</div>";
		var html = "<div class='print_view new' id='firstcopy'></div>";
		$("body").append(html);
		$("#firstcopy").append(html_print);
	}
	else if(siteSettings['printMode'] == 3){
		var html_print = $("#original :first-child").html();
		html_print = html_print.replace("Original Copy","Transporter Copy");
		html_print = "<div class='print_section'>" + html_print + "</div>";
		var html = "<div class='print_view' id='secondcopy'></div>";
		$("#compact").append(html);
		$("#secondcopy").append(html_print);
	}
}
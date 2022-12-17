var currBill = 0;
var billCount = 0;
var page = 1;
$(document).ready(function() {
	billCount = billResult.length;
	
	while(currBill < billCount){
		createPage();
	}
});

function createPage(){
	if(siteSettings['printMode'] == 1){
		html_print = getFullpageSection();
		if(currBill == 0){
			$("#print_1").append(html_print);
		}
		else{
			var html_page = "<div class='print_view new'>" + html_print + "</div>";
			$("body").append(html_page);
		}
		if(siteSettings['printCopy'] >= 2){
			html_print_Cstmr = html_print.replace("Original Copy","Customer Copy");
			var html_page = "<div class='print_view new'>" + html_print_Cstmr + "</div>";
			$("body").append(html_page);
		}
		if(siteSettings['printCopy'] == 3){
			html_print_trip = html_print.replace("Original Copy","Transporter Copy");
			var html_page = "<div class='print_view new'>" + html_print_trip + "</div>";
			$("body").append(html_page);
		}
		currBill++;
	}
	
	else if(siteSettings['printMode'] == 2){
		set = billCount - currBill;
		html_print = getHalfPage();
		if(set != 1){
			if(page == 1){
				$("#print_1").append(html_print);
			}
			else{
				var html_page = "<div class='print_view new'>" + html_print + "</div>";
				$("body").append(html_page);
			}
			if(siteSettings['printCopy'] >= 2){
				html_print_Cstmr = html_print.replace(/Original Copy/g,"Customer Copy");
				var html_page = "<div class='print_view new'>" + html_print_Cstmr + "</div>";
				$("body").append(html_page);
			}
			if(siteSettings['printCopy'] == 3){
				html_print_trip = html_print.replace(/Original Copy/g,"Transporter Copy");
				var html_page = "<div class='print_view new'>" + html_print_trip + "</div>";
				$("body").append(html_page);
			}
		}
		else{
			if(siteSettings['printCopy'] == 1){
				var html_page = "<div class='print_view new'>" + html_print + "</div>";
				$("body").append(html_page);
			}
			else if(siteSettings['printCopy'] == 2){
				html_print_Cstmr = html_print.replace(/Original Copy/g,"Customer Copy");
				var html_page = "<div class='print_view new'>" + html_print + html_print_Cstmr + "</div>";
				$("body").append(html_page);
			}
			else if(siteSettings['printCopy'] == 3){
				html_print_Cstmr = html_print.replace(/Original Copy/g,"Customer Copy");
				var html_page = "<div class='print_view new'>" + html_print + html_print_Cstmr + "</div>";
				$("body").append(html_page);
				html_print_trip = html_print.replace(/Original Copy/g,"Transporter Copy");
				var html_page = "<div class='print_view new'>" + html_print_trip + "</div>";
				$("body").append(html_page);
			}
		}
		page++;
	}
	
	else if(siteSettings['printMode'] == 3){
		html_print = getCompactPage();
		if(set >= 3){
			if(page == 1){
				$("#print_1").append(html_print);
			}
			else{
				var html_page = "<div class='compact new'>" + html_print + "</div>";
				$("body").append(html_page);
			}
			if(siteSettings['printCopy'] >= 2){
				html_print_Cstmr = html_print.replace(/Original Copy/g,"Customer Copy");
				var html_page = "<div class='compact new'>" + html_print_Cstmr + "</div>";
				$("body").append(html_page);
			}
			if(siteSettings['printCopy'] == 3){
				html_print_trip = html_print.replace(/Original Copy/g,"Transporter Copy");
				var html_page = "<div class='compact new'>" + html_print_trip + "</div>";
				$("body").append(html_page);
			}
		}
		
		else if(set == 2){
			if(siteSettings['printCopy'] == 1){
				if(page == 1){				
					$("#print_1").append(html_print);
				}
				else{
					var html_page = "<div class='compact new'>" + html_print + "</div>";
					$("body").append(html_page);
				}
			}
			if(siteSettings['printCopy'] == 2){
				html_print_Cstmr = html_print.replace(/Original Copy/g,"Customer Copy");
				html_print1 = html_print + html_print_Cstmr;
				if(page == 1){				
					$("#print_1").append(html_print1);
				}
				else{
					var html_page = "<div class='compact new'>" + html_print1 + "</div>";
					$("body").append(html_page);
				}
			}
			else if(siteSettings['printCopy'] == 3){
				html_print_Cstmr = html_print.replace(/Original Copy/g,"Customer Copy");
				html_print1 = html_print + html_print_Cstmr;
				if(page == 1){				
					$("#print_1").append(html_print1);
					html_print_trip = html_print.replace(/Original Copy/g,"Transporter Copy");
					var html_page = "<div class='compact new'>" + html_print_trip + "</div>";
					$("body").append(html_page);
				}
				else{
					var html_page = "<div class='compact new'>" + html_print1 + "</div>";
					$("body").append(html_page);
					html_print_trip = html_print.replace(/Original Copy/g,"Transporter Copy");
					var html_page = "<div class='compact new'>" + html_print_trip + "</div>";
					$("body").append(html_page);
				}
			}
		}
		
		else{
			if(page == 1){
				$("#print_1").append(html_print);
			}
			else{
				var html_page = "<div class='compact new'>" + html_print + "</div>";
				$("body").append(html_page);
			}
		}
		page++;
	}
}

function convertToWords(num){
	var a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
	var b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];
	
	num = Math.round(num);
    if ((num = num.toString()).length > 9) return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
    if (!n) return; var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'Only.' : 'Only.';
	
    return "Rs. "+str;
}

function getHalfPage(){
	set = billCount - currBill;
	if(set != 1){
		html_print = getHalfPageSection();
		currBill++;
		html_print += getHalfPageSection()
		currBill++;
	}
	else{
		html_print = getHalfPageSection();
		currBill++;
	}
	return html_print;
}

function getCompactPage(){
	set = billCount - currBill;
	if(set >= 3){
		html_print = getCompactSection();
		currBill++;
		html_print += getCompactSection()
		currBill++;
		
		html_row1 = "<div class='print_view'>" + html_print + "</div>";
		
		html_print = getCompactSection()
		currBill++;
		if(set >= 4){
			html_print += getCompactSection()
			currBill++;
		}
				
		html_row2 = "<div class='print_view'>" + html_print + "</div>";
		
		html_result = html_row1 + html_row2;
	}
	else if(set == 2){
		html_print = getCompactSection();
		currBill++;
		html_print += getCompactSection()
		currBill++;
		
		html_row1 = "<div class='print_view'>" + html_print + "</div>";
		html_result = html_row1;
	}
	else{
		html_print = getCompactSection()
		currBill++;
		if(siteSettings['printCopy'] == 1){
			html_row1 = "<div class='print_view'>" + html_print + "</div>";
			html_result = html_row1;			
		}
		else if(siteSettings['printCopy'] == 2){
			html_print_Cstmr = html_print.replace(/Original Copy/g,"Customer Copy");
			html_row1 = "<div class='print_view'>" + html_print + html_print_Cstmr + "</div>";
			html_result = html_row1;			
		}
		else if(siteSettings['printCopy'] == 3){
			html_print_Cstmr = html_print.replace(/Original Copy/g,"Customer Copy");
			html_row1 = "<div class='print_view'>" + html_print + html_print_Cstmr + "</div>";
			html_print_trip = html_print.replace(/Original Copy/g,"Transporter Copy");
			html_row2 = "<div class='print_view'>" + html_print_trip + "</div>";
			html_result = html_row1 + html_row2;			
		}
	}
	return html_result;
}

function getFullpageSection(){
	html_print = "<div class='print_section'><div class='bill_head'><div class='print_row'><div class='print_logo'><img src='/img/" + billDetails[currBill]['logo'] + "' alt='profileImg'><div class='txt_bold'>";
	html_print += "<div class='txt_heading'>" + billDetails[currBill]['name'] + "</div><div>GSTIN:&nbsp;" + billDetails[currBill]['gstin'] + "</div><div>State:&nbsp;" + adrsOut[currBill]['state'] + "</div></div></div>";
	html_print += "<div class='txt_bold middle'><div class='txt_heading'>" + billResult[currBill]['billNum'] + "</div><div>" + moment(billResult[currBill]['billdate']).format('DD/MMM/YYYY') + "</div>";
	html_print += "<div>P.O.N. : -" + billResult[currBill]['prchsOrdNum'] + "-</div></div></div><div class='on_hr middle txt_small txt_bold'>" + adrsOut[currBill]['address'] + ", " + adrsOut[currBill]['city']  + ", ";
	html_print += adrsOut[currBill]['state'] + ", " + adrsOut[currBill]['country'] +", " + adrsOut[currBill]['pin'] + ", " + billResult[currBill]['contOut'] + "</div><hr class='on_txt'><div class='float_centre'><b>TAX INVOICE (Original Copy)";
	html_print += "</b></div><hr><div class='print_row'><div style='width:32%;'><div><b>Customer Details</b></div><div class='txt_bold'>Name</div><div>" + billDetails[currBill]['fName'] + "</div><div class='txt_bold'>GSTIN</div>";
	html_print += "<div>" + billDetails[currBill]['gstn'] + "</div></div><div class='middle' style='width:32%;'><div><b>Billing Address</b></div><div>" + adrsCstmr[currBill]['address'] + "</div><div>" + adrsCstmr[currBill]['city'] + ", ";
	html_print += adrsCstmr[currBill]['state'] + "</div><div>" + adrsCstmr[currBill]['country'] + ", " + adrsCstmr[currBill]['pin'] + "</div><div>" + billResult[currBill]['contCstmr'] + "</div></div><div class='last' style='width:32%;'>";
	html_print += "<div><b>Shipping Address</b></div><div>" + adrsShipp[currBill]['address'] + "</div><div>" + adrsShipp[currBill]['city'] + ", " + adrsShipp[currBill]['state'] + "</div><div>" + adrsShipp[currBill]['country'];
	html_print += ", " + adrsShipp[currBill]['pin'] + "</div></div></div><hr><div class='print_row txt_small'>";
	if(vhclV == 1){
		html_print += "<div><div><b>Transport Company</b></div><div>" + billResult[currBill]['transporter'] + "</div></div><div class='middle'><div><b>E-Way Number</b></div><div>-" + billResult[currBill]['eway'] + "-</div></div>";
	}
	html_print += "<div class='last'><div><b>" + unqFld1 + "</b></div><div>-" + billResult[currBill]['unqFld1'] + "-</div></div></div></div><hr><div class='bill_content'><div class='bill_items'>";
	html_print += "<table id='bill_table' style='width:100%'><thead><tr><th class='first' style='width:32%'>Item</th><th style='width:10%'>HSN</th><th style='width:5%'>qty</th><th style='width:5%'>rate</th><th style='width:10%'>Net</th>";
	html_print += "<th style='width:5%'>@tax</th><th style='width:7%'>cgst</th><th style='width:7%'>sgst</th><th style='width:7%'>igst</th><th class='last' style='width:12%'>Amount</th></tr><tr><td colspan='100%'><hr></td></tr></thead><tbody>";
	
	var Item = ItemList[currBill];
	for(i = 0; i < Item.length; i++){
		html_print += "<tr class='txt_small'><td class='first'>" + (i+1) + ". " + Item[i]['name'] + "(" + Item[i]['brand'] + ")</td><td>" + Item[i]['hsn'] + "</td><td>" + 1*Item[i]['qty'];
		html_print += "</td><td>" + 1*Item[i]['rate'] + "</td><td>" + 1*Item[i]['net'] + "</td><td>" + 2*Item[i]['taxslab'] + "%</td><td>" + 1*Item[i]['cgst'] + "</td>";
		html_print += "<td>" + 1*Item[i]['sgst'] + "</td><td>" + 1*Item[i]['igst'] + "</td><td class='last'>" + 1*Item[i]['gross'] + "</td></tr>";
	}
	html_print += "</tbody></table></div><table style='width:100%'><tfoot><tr><td class='first'>@Freight Charges</td><td>" + 1*billResult[currBill]['fQty'] + "</td><td>" + 1*billResult[currBill]['fRate'] + "</td>";
	html_print += "<td>" + 1*billResult[currBill]['fNet'] + "</td><td>" + 2*billResult[currBill]['fTaxRt'] + "%</td><td>" + 1*billResult[currBill]['fcgst'] + "</td><td>" + 1*billResult[currBill]['fsgst'] + "</td>";
	html_print += "<td>" + 1*billResult[currBill]['figst'] + "</td><td class='last'>" + 1*billResult[currBill]['fgross'] + "</td></tr><tr><td class='first' colspan='8'>@Round Off</td><td class='last'>-" + 1*billResult[currBill]['rndOff'] + "</td>";
	html_print += "</tr><tr><td colspan='100%'><hr></td></tr><tr><th class='first' style='width:42%'>Total</th><th style='width:5%'></th><th style='width:5%'></th><th style='width:10%'>₹" + 1*(Number(billResult[currBill]['pdtnet']) + Number(billResult[currBill]['fNet']));
	html_print += "</th><th style='width:5%'></th><th style='width:7%'>" + 1*(Number(billResult[currBill]['pdtcgst']) + Number(billResult[currBill]['fcgst'])) + "</th><th style='width:7%' >" + 1*(Number(billResult[currBill]['pdtsgst']) + Number(billResult[currBill]['fsgst'])) + "</th>";
	html_print += "<th style='width:7%'>" + 1*(Number(billResult[currBill]['pdtigst']) + Number(billResult[currBill]['figst'])) + "</th><th class='last' style='width:12%'>₹" + 1*billResult[currBill]['grand'] + "</th></tr></tfoot></table><hr><div class='print_row'><div style='width:40%;'>";
	if(vhclV == 1){
		html_print += "<div><b>Vehicle Details</b></div><div>" + billDetails[currBill]['vNo'] + "</div><div>" + billDetails[currBill]['vRep'] + "</div><div>" + billDetails[currBill]['contactV'] + "</div>";
	}
	html_print += "</div><div class='txt_bold' style='width:60%;'><div class='print_row'><div class='first print_row'><div class='fixed_width'><div>Taxable Amount</div><div>Total Tax</div><div>Invoice Total</div></div><div>";
	html_print += "<div>:</div><div>:</div><div>:</div></div></div><div class='last'><div>₹" +  1*(Number(billResult[currBill]['pdtnet']) + Number(billResult[currBill]['fNet'])) + "</div>";
	html_print += "<div>₹" +  1*(Number(billResult[currBill]['pdtcgst']) + Number(billResult[currBill]['fcgst']) + Number(billResult[currBill]['pdtsgst']) + Number(billResult[currBill]['fsgst']) + Number(billResult[currBill]['pdtigst']) + Number(billResult[currBill]['figst'])).toFixed(2) + "</div>";
	
	var numeral = 1*billResult[currBill]['grand'];
	inWords = convertToWords(numeral);
	
	html_print += "<div style='display: flex;' class='numeral'>₹<p>" +  1*billResult[currBill]['grand'] + "</p></div></div></div><div class='last txt_small'>" + inWords + "</div></div></div>";
	html_print += "<hr><div class='print_row'><div class='print_row' style='width:60%;'><div class='first print_row'><div class='fixed_width'><div><b>Bank Details</b></div><div>Bank</div><div>Branch</div><div>IFSC</div><div>Account No.</div>";
	html_print += "</div><div><div>&nbsp;</div><div>:</div><div>:</div><div>:</div><div>:</div></div></div><div class='first'><div>&nbsp;</div><div>" + billDetails[currBill]['bankName'] + "</div>";
	html_print += "<div>" + billDetails[currBill]['brnchName'] + "</div><div>" + billDetails[currBill]['ifsc'] + "</div><div>" + billDetails[currBill]['accNum'] + "</div></div></div><div class='last bottom txt_small' style='width:40%;'>";
	html_print += "<div>(" + billDetails[currBill]['name'] + ")</div><div>Authorised Signatory</div></div></div><div>&nbsp;</div><div><b>Declaration</b></div><div class='txt_small'>1) Error and Omission in this invoice shall be subject to judrisdiction of " + adrsOut[currBill]['city'] + ".</div>";
	html_print += "</div><div class='bill_foot'><hr><div class='float_centre'><div>www.rksbillingsolutions.com</div></div></div></div>";
	
	return html_print;
}

function getHalfPageSection(){
	html_print = "<div class='print_section'><div class='bill_head'><div class='print_row'><div class='print_logo'><img src='/img/" + billDetails[currBill]['logo'] + "' alt='profileImg'><div class='txt_bold'>";
	html_print += "<div class='txt_heading'>" + billDetails[currBill]['name'] + "</div><div>GSTIN:&nbsp;" + billDetails[currBill]['gstin'] + "</div><div>State:&nbsp;" + adrsOut[currBill]['state'] + "</div></div></div>";
	html_print += "<div class='txt_bold middle'><div class='txt_heading'>" + billResult[currBill]['billNum'] + "</div><div>" + moment(billResult[currBill]['billdate']).format('DD/MMM/YYYY') + "</div>";
	html_print += "<div>P.O.N. : -" + billResult[currBill]['prchsOrdNum'] + "-</div></div></div><div class='on_hr middle txt_small txt_bold'>" + adrsOut[currBill]['address'] + ", " + adrsOut[currBill]['city']  + ", ";
	html_print += adrsOut[currBill]['state'] + ", " + adrsOut[currBill]['country'] +", " + adrsOut[currBill]['pin'] + ", " + billResult[currBill]['contOut'] + "</div><hr class='on_txt'><div class='float_centre'><b>TAX INVOICE (Original Copy)";
	html_print += "</b></div><hr><div class='print_row'><div style='width:32%;'><div><b>Customer Details</b></div><div class='txt_bold'>Name</div><div>" + billDetails[currBill]['fName'] + "</div><div class='txt_bold'>GSTIN</div>";
	html_print += "<div>" + billDetails[currBill]['gstn'] + "</div></div><div class='middle' style='width:32%;'><div><b>Billing Address</b></div><div>" + adrsCstmr[currBill]['address'] + "</div><div>" + adrsCstmr[currBill]['city'] + ", ";
	html_print += adrsCstmr[currBill]['state'] + "</div><div>" + adrsCstmr[currBill]['country'] + ", " + adrsCstmr[currBill]['pin'] + "</div><div>" + billResult[currBill]['contCstmr'] + "</div></div><div class='last' style='width:32%;'>";
	html_print += "<div><b>Shipping Address</b></div><div>" + adrsShipp[currBill]['address'] + "</div><div>" + adrsShipp[currBill]['city'] + ", " + adrsShipp[currBill]['state'] + "</div><div>" + adrsShipp[currBill]['country'];
	html_print += ", " + adrsShipp[currBill]['pin'] + "</div></div></div><hr><div class='print_row txt_small'>";
	if(vhclV == 1){
		html_print += "<div><div><b>Transport Company</b></div><div>" + billResult[currBill]['transporter'] + "</div></div><div class='middle'><div><b>E-Way Number</b></div><div>-" + billResult[currBill]['eway'] + "-</div></div>";
	}
	html_print += "<div class='last'><div><b>" + unqFld1 + "</b></div><div>-" + billResult[currBill]['unqFld1'] + "-</div></div></div></div><hr><div class='bill_content'><div class='bill_items'>";
	html_print += "<table id='bill_table' style='width:100%'><thead><tr><th class='first' style='width:35%'>Item</th><th style='width:7%'>HSN</th><th style='width:10%'>qtyXrate</th><th style='width:10%'>Net</th>";
	html_print += "<th style='width:5%'>@tax</th><th style='width:7%'>cgst</th><th style='width:7%'>sgst</th><th style='width:7%'>igst</th><th class='last' style='width:12%'>Amount</th></tr><tr><td colspan='100%'><hr></td></tr></thead><tbody>";
	
	var Item = ItemList[currBill];
	for(i = 0; i < Item.length; i++){
		html_print += "<tr class='txt_small'><td class='first'>" + (i+1) + ". " + Item[i]['name'] + "(" + Item[i]['brand'] + ")</td><td>" + Item[i]['hsn'] + "</td><td>" + 1*Item[i]['qty'] + "x" + 1*Item[i]['rate'] + "</td>";
		html_print += "<td>" + 1*Item[i]['net'] + "</td><td>" + 2*Item[i]['taxslab'] + "%</td><td>" + 1*Item[i]['cgst'] + "</td><td>" + 1*Item[i]['sgst'] + "</td><td>" + 1*Item[i]['igst'] + "</td><td class='last'>" + 1*Item[i]['gross'] + "</td></tr>";
	}
	html_print += "</tbody></table></div><table style='width:100%'><tfoot><tr><td class='first'>@Freight Charges</td><td colspan='2'>" + 1*billResult[currBill]['fQty'] + "x" + 1*billResult[currBill]['fRate'] + "</td>";
	html_print += "<td>" + 1*billResult[currBill]['fNet'] + "</td><td>" + 2*billResult[currBill]['fTaxRt'] + "%</td><td>" + 1*billResult[currBill]['fcgst'] + "</td><td>" + 1*billResult[currBill]['fsgst'] + "</td>";
	html_print += "<td>" + 1*billResult[currBill]['figst'] + "</td><td class='last'>" + 1*billResult[currBill]['fgross'] + "</td></tr><tr><td class='first' colspan='8'>@Round Off</td><td class='last'>-" + 1*billResult[currBill]['rndOff'] + "</td>";
	html_print += "</tr><tr><td colspan='100%'><hr></td></tr><tr><th class='first' style='width:42%'>Total</th><th style='width:5%'></th><th style='width:5%'></th><th style='width:10%'>₹" + 1*(Number(billResult[currBill]['pdtnet']) + Number(billResult[currBill]['fNet']));
	html_print += "</th><th style='width:5%'></th><th style='width:7%'>" + 1*(Number(billResult[currBill]['pdtcgst']) + Number(billResult[currBill]['fcgst'])) + "</th><th style='width:7%' >" + 1*(Number(billResult[currBill]['pdtsgst']) + Number(billResult[currBill]['fsgst'])) + "</th>";
	html_print += "<th style='width:7%'>" + 1*(Number(billResult[currBill]['pdtigst']) + Number(billResult[currBill]['figst'])) + "</th><th class='last' style='width:12%'>₹" + 1*billResult[currBill]['grand'] + "</th></tr></tfoot></table><hr><div class='print_row'><div style='width:40%;'>";
	if(vhclV == 1){
		html_print += "<div><b>Vehicle Details</b></div><div>" + billDetails[currBill]['vNo'] + "</div><div>" + billDetails[currBill]['vRep'] + "</div><div>" + billDetails[currBill]['contactV'] + "</div>";
	}
	html_print += "</div><div class='txt_bold' style='width:60%;'><div class='print_row'><div class='first print_row'><div class='fixed_width'><div>Taxable Amount</div><div>Total Tax</div><div>Invoice Total</div></div><div>";
	html_print += "<div>:</div><div>:</div><div>:</div></div></div><div class='last'><div>₹" +  1*(Number(billResult[currBill]['pdtnet']) + Number(billResult[currBill]['fNet'])) + "</div>";
	html_print += "<div>₹" +  1*(Number(billResult[currBill]['pdtcgst']) + Number(billResult[currBill]['fcgst']) + Number(billResult[currBill]['pdtsgst']) + Number(billResult[currBill]['fsgst']) + Number(billResult[currBill]['pdtigst']) + Number(billResult[currBill]['figst'])).toFixed(2) + "</div>";
	
	var numeral = 1*billResult[currBill]['grand'];
	inWords = convertToWords(numeral);
	
	html_print += "<div style='display: flex;' class='numeral'>₹<p>" +  1*billResult[currBill]['grand'] + "</p></div></div></div><div class='last txt_small'>" + inWords + "</div></div></div>";
	html_print += "<hr><div class='print_row'><div class='print_row' style='width:60%;'><div class='first print_row'><div class='fixed_width'><div><b>Bank Details</b></div><div>Bank</div><div>Branch</div><div>IFSC</div><div>Account No.</div>";
	html_print += "</div><div><div>&nbsp;</div><div>:</div><div>:</div><div>:</div><div>:</div></div></div><div class='first'><div>&nbsp;</div><div>" + billDetails[currBill]['bankName'] + "</div>";
	html_print += "<div>" + billDetails[currBill]['brnchName'] + "</div><div>" + billDetails[currBill]['ifsc'] + "</div><div>" + billDetails[currBill]['accNum'] + "</div></div></div><div class='last bottom txt_small' style='width:40%;'>";
	html_print += "<div>(" + billDetails[currBill]['name'] + ")</div><div>Authorised Signatory</div></div></div><div>&nbsp;</div><div><b>Declaration</b></div><div class='txt_small'>1) Error and Omission in this invoice shall be subject to judrisdiction of " + adrsOut[currBill]['city'] + ".</div>";
	html_print += "</div><div class='bill_foot'><hr><div class='float_centre'><div>www.rksbillingsolutions.com</div></div></div></div>";
	
	return html_print;
}

function getCompactSection(){
	html_print = "<div class='print_section'><div class='bill_head'><div class='print_row'><div class='print_logo'><img src='/img/" + billDetails[currBill]['logo'] + "' alt='profileImg'><div class='txt_bold'>";
	html_print += "<div class='txt_heading'>" + billDetails[currBill]['name'] + "</div><div>GSTIN: " + billDetails[currBill]['gstin'] + "</div><div>State: " + adrsOut[currBill]['state'] + "</div></div></div><div class='txt_bold middle'>";
	html_print += "<div class='txt_heading'>" + billResult[currBill]['billNum'] + "</div><div>" + moment(billResult[currBill]['billdate']).format('DD/MMM/YYYY') + "</div><div>P.O.N. : -" + billResult[currBill]['prchsOrdNum'] + "-</div></div>";
	html_print += "</div><div class='on_hr middle txt_small txt_bold'>" + adrsOut[currBill]['address'] + ", " + adrsOut[currBill]['city'] + ", " + adrsOut[currBill]['state'] + ", " + adrsOut[currBill]['country'] + ", " + adrsOut[currBill]['pin'] + ", " + billResult[currBill]['contOut'] + "</div>";
	html_print += "<hr class='on_txt'><div class='float_centre'><b>TAX INVOICE (Original Copy)</b></div><hr><div class='print_row'><div><div><b>Customer Details</b></div><div class='txt_bold'>Name</div><div>" + billDetails[currBill]['fName'] + "</div>";
	html_print += "<div class='txt_bold'>GSTIN</div><div>" + billDetails[currBill]['gstn'] + "</div></div><div class='last'><div><b>Billing Address</b></div><div>" + adrsCstmr[currBill]['address'] + "</div>";
	html_print += "<div>" + adrsCstmr[currBill]['city'] + ", " + adrsCstmr[currBill]['state'] + "</div><div>" + adrsCstmr[currBill]['country'] + ", " + adrsCstmr[currBill]['pin'] + "</div><div>" + billResult[currBill]['contCstmr'] + "</div>";
	html_print += "</div></div><hr><div class='print_row'><div>";
	if(vhclV == 1){
		html_print += "<div><b>Transport Company</b></div><div>" + billResult[currBill]['transporter'] + "</div><div><b>E-Way Number</b>-" + billResult[currBill]['eway'] + "-</div>";
	}
	html_print += "<div><b>" + unqFld1 + "</b>-" + billResult[currBill]['unqFld1'] + "-</div></div>";
	if(vhclV == 1){
		html_print += "<div class='last'><div><b>Vehicle Details</b></div><div>" + billDetails[currBill]['vNo'] + "</div><div>" + billDetails[currBill]['vRep'] + "</div><div>" + billDetails[currBill]['contactV'] + "</div></div>";
	}	
	html_print += "</div></div><hr><div class='bill_content'><div class='bill_items'><table id='bill_table' style='width:100%'><thead><tr><th class='first' style='width:58%'>Item<label class='txt_mini'> @HSN</label></th>";
	html_print += "<th style='width:10%'>qtyXrate</th><th style='width:10%'>Net</th><th style='width:12%'>tax</th><th class='last' style='width:10%'>Amount</th></tr><tr><td colspan='100%'><hr></td></tr></thead><tbody>";
	
	var Item = ItemList[currBill];
	for(i = 0; i < Item.length; i++){
		html_print += "<tr class='txt_small'><td class='first'>" + (i+1) + ". " + Item[i]['name'] + "(" + Item[i]['brand'] + ")" + "<label class='txt_mini'> @" + 1*Item[i]['hsn'] + "</label></td><td>" + 1*Item[i]['qty'] + "x" + 1*Item[i]['rate'] + "</td>";
		html_print += "<td>" +  1*Item[i]['net'] + "</td><td>" +  1*(Number(Item[i]['cgst']) + Number(Item[i]['sgst']) + Number(Item[i]['igst'])) + "<label class='txt_mini'> @" +  2*Item[i]['taxslab'] + "%</label></td><td class='last'>" +  1*Item[i]['gross'] + "</td></tr>";
	}
	html_print += "</tbody></table></div><table style='width:100%'><tfoot><tr><td class='first'>@Freight Charges</td><td colspan='2'>" +  1*billResult[currBill]['fQty'] + "x" + 1*billResult[currBill]['fRate'] + "</td>";
	html_print += "<td>" +  1*billResult[currBill]['fNet'] + "</td><td>" +  1*(Number(billResult[currBill]['fcgst']) + Number(billResult[currBill]['fsgst']) + Number(billResult[currBill]['figst'])) + "<label class='txt_mini'> @" +  2*billResult[currBill]['fTaxRt'] + "%</label></td>";
	html_print += "<td class='last'>" +  1*billResult[currBill]['fgross'] + "</td></tr><tr><td class='first' colspan='5'>@Round Off</td><td class='last'>-" +  1*billResult[currBill]['rndOff'] + "</td></tr><tr><td colspan='100%'><hr></td></tr>";
	html_print += "<tr><th class='first' style='width:58%'>Total</th><th></th><th style='width:10%'></th><th style='width:10%'>₹" +  1*(Number(billResult[currBill]['pdtnet']) + Number(billResult[currBill]['fNet'])) + "</th>";
	html_print += "<th style='width:12%'>" +  1*(Number(billResult[currBill]['pdtcgst']) + Number(billResult[currBill]['fcgst']) + Number(billResult[currBill]['pdtsgst']) + Number(billResult[currBill]['fsgst']) + Number(billResult[currBill]['pdtigst']) + Number(billResult[currBill]['figst'])).toFixed(2) + "</th>";
	html_print += "<th class='last' style='width:10%'>₹" +  1*billResult[currBill]['grand'] + "</th></tr></tfoot></table><hr><div class='print_row'><div style='width: 49%;'><div><b>Shipping Address</b></div><div>" + adrsShipp[currBill]['address'] + "</div>";
	html_print += "<div>" + adrsShipp[currBill]['city'] + ", " + adrsShipp[currBill]['state'] + "</div><div>" + adrsShipp[currBill]['country'] + ", " + adrsShipp[currBill]['pin'] + "</div></div><div class='txt_bold' style='width: 49%;'><div class='print_row'>";
	html_print += "<div class='first print_row'><div><div>Taxable Amount:</div><div>Total Tax:</div><div>Invoice Total:</div></div></div><div class='last'><div>₹" +  1*(Number(billResult[currBill]['pdtnet']) + Number(billResult[currBill]['fNet'])) + "</div>";
	html_print += "<div>₹" +  1*(Number(billResult[currBill]['pdtcgst']) + Number(billResult[currBill]['fcgst']) + Number(billResult[currBill]['pdtsgst']) + Number(billResult[currBill]['fsgst']) + Number(billResult[currBill]['pdtigst']) + Number(billResult[currBill]['figst'])).toFixed(2) + "</div>";
	
	var numeral = 1*billResult[currBill]['grand'];
	inWords = convertToWords(numeral);
	
	html_print += "<div style='display: flex;' class='numeral'>₹<p>" +  1*billResult[currBill]['grand'] + "</p></div></div></div><div class='last txt_small'>" + inWords + "</div></div></div><hr><div class='print_row'><div class='first print_row'>";
	html_print += "<div><div><b>Bank Details</b></div><div>Bank</div><div>Branch</div><div>IFSC</div><div>Account No.</div></div><div><div>&nbsp;</div><div>:</div><div>:</div><div>:</div><div>:</div></div></div><div><div>&nbsp;</div>";
	html_print += "<div>" + billDetails[currBill]['bankName'] + "</div><div>" + billDetails[currBill]['brnchName'] + "</div><div>" + billDetails[currBill]['ifsc'] + "</div><div>" + billDetails[currBill]['accNum'] + "</div></div><div class='last'></div></div><div><b>Declaration</b></div>";
	html_print += "<div class='txt_small'>1) Error and Omission in this invoice shall be subject to judrisdiction of " + adrsOut[currBill]['city'] + ".</div></div><div class='bill_foot'><hr><div class='float_centre'>";
	html_print += "<div>www.rksbillingsolutions.com</div></div></div></div>";
	
	return html_print;
}
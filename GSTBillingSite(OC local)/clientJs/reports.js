function setSelectMenu(val){
	if(val == 1){
		$("#cstmrSelect").attr("disabled",true);
		$("#cstmrSelect").attr("required",false);
		$("#pdtSelect").attr("disabled",true);
		$("#pdtSelect").attr("required",false);
	}
	else if(val == 2){
		$("#pdtSelect").attr("disabled",true);
		$("#pdtSelect").attr("required",false);
		$("#cstmrSelect").attr("disabled",false);
		$("#cstmrSelect").attr("required",true);
	}
	else if(val == 3){
		$("#cstmrSelect").attr("disabled",true);
		$("#cstmrSelect").attr("required",false);
		$("#pdtSelect").attr("disabled",false);
		$("#pdtSelect").attr("required",true);	
	}
}

$(document).ready(function() {
	$('.mix_data').hide();
    $('input:radio[name=rView]').change(function() {
        if (this.value == '0') {
            $('.div_data').show();
			$('.mix_data').hide();
        }
        else if (this.value == '1') {
			$('.mix_data').show();
            $('.div_data').hide();
        }
    });
});
$(document).ready(function() {
    //button modal
    $('#addCustomer').click(function(){
		
		openModal("#customerModal","#addCustomerModal","#addCustomer","add",null)
        
    })
	
});

function openEditModal(refId){
	var lastElement = "#editLink[" + refId + "]";
	openModal("#customerModal","#addCustomerModal",lastElement,"edit",refId);
	return false;
};
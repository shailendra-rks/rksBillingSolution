$(document).ready(function() {
    //button modal
    $('#addOutlet').click(function(){
		
		openModal("#outletModal","#addOutletModal","#addOutlet","add",null)
        
    })
	
});

function openEditModal(refId){
	var lastElement = "#editLink[" + refId + "]";
	openModal("#outletModal","#addOutletModal",lastElement,"edit",refId);
	return false;
};
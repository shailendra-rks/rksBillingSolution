$(document).ready(function() {
    //button modal
    $('#addVehicle').click(function(){
		
		openModal("#vehicleModal","#addVehicleModal","#addVehicle","add",null)
        
    })
	
});

function openEditModal(refId){
	var lastElement = "#editLink[" + refId + "]";
	openModal("#vehicleModal","#addVehicleModal",lastElement,"edit",refId);
	return false;
};
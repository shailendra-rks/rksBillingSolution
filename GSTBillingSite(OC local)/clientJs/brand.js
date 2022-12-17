$(document).ready(function() {
    //button modal
    $('#addBrand').click(function(){
		
		openModal("#brandModal","#addBrandModal","#addBrand","add",null)
        
    })
	
});

function openEditModal(refId){
	var lastElement = "#editLink[" + refId + "]";
	openModal("#brandModal","#addBrandModal",lastElement,"edit",refId);
	return false;
};
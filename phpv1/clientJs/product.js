$(document).ready(function() {
    //button modal
    $('#addProduct').click(function(){
		
		openModal("#productModal","#addProductModal","#addProduct","add",null)
        
    })
	
});

function openEditModal(refId){
	var lastElement = "#editLink[" + refId + "]";
	openModal("#productModal","#addProductModal",lastElement,"edit",refId);
	return false;
};
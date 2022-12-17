$(document).ready(function() {
    //button modal
    $('#addUser').click(function(){
		
		openModal("#userModal","#addUserModal","#addUser","add",null)
        
    })
	
});

function openEditModal(refId){
	var lastElement = "#editLink[" + refId + "]";
	openModal("#userModal","#addUserModal",lastElement,"edit",refId);
	return false;
};

function nestedChecks(val, create, edit){
	if(val == true){
		document.getElementById(create).removeAttribute("disabled","");
		document.getElementById(edit).removeAttribute("disabled","");
	}
	else if(val == false){
		document.getElementById(create).checked = false;
		document.getElementById(create).setAttribute("disabled","");
		document.getElementById(edit).checked = false;
		document.getElementById(edit).setAttribute("disabled","");
	}
}
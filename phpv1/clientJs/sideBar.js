  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");
  let headerDivId = document.querySelector(".headerDiv"); //  handle Header sizing on change in sidebar

  closeBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("open");
	headerDivId.classList.toggle("open");  //  handle Header sizing on change in sidebar
    let topNavDivId = document.querySelector(".topnav");
	topNavDivId.classList.toggle("open");
    menuBtnChange();//calling the function(optional)
  });

  // following are the code to change sidebar button(optional)
  function menuBtnChange() {
   if(sidebar.classList.contains("open")){
     closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the iocns class
   }else {
     closeBtn.classList.replace("bx-menu-alt-right","bx-menu");//replacing the iocns class
   }
  }
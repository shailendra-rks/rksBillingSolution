function initiateLogin() {
	document.getElementById("hidden_login").click();
}

let closeBtnTopNav = document.querySelector("#btnbar");
let topnav = document.querySelector(".topnav");

closeBtnTopNav.addEventListener("click", ()=>{
	topnav.classList.toggle("responsive");
});
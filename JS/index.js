let popup = document.getElementById("formContainer");
let popup2 = document.getElementById("CreateAccount");
let navLinks = document.getElementById("navLinks");

function openPopup() {
    popup.classList.add("open-popup");
}

function closePopup() {
    popup.classList.remove("open-popup");
}

function createAccountPopup() {
    popup2.classList.add("create-popup");
}

function closeAccountPopup() {
    popup2.classList.remove("create-popup");
}

function openNavLinks(){
    navLinks.classList.add("openNavLinks");
}

function closeNavLinks(){
    navLinks.classList.remove("openNavLinks");
}
let toggleButton = document.getElementById("toggle-btn");
let sidebar = document.getElementById("sidebar");

function toggleSidebar() {
  sidebar.classList.toggle("close");
  toggleButton.classList.toggle("rotate");
  Array.from(sidebar.getElementsByClassName("show")).forEach((ul) => {
    ul.classList.remove("show");
    ul.previousElementSibling.classList.remove("rotate");
  });
}

function toggleSubMenu(button) {
  button.nextElementSibling.classList.toggle("show");
  button.classList.toggle("rotate");

  if (sidebar.classList.contains("close")) {
    sidebar.classList.toggle("close");
    toggleButton.classList.toggle("rotate");
  }
}

function deleteUser() {
  document.getElementById("delete").classList.add("show-delete");
}

function closeDelete() {
  document.getElementById("delete").classList.remove("show-delete");
}

function approveUser() {
  document.getElementById("approve").classList.add("show-approve");
}

function closeApprove() {
  document.getElementById("approve").classList.remove("show-approve");
}

function addLesson() {
  document.getElementById("addLesson").classList.add("addLesson");
}

function cancelLesson() {
  document.getElementById("addLesson").classList.remove("addLesson");
}

 // Existing functions
 function addLesson() {
  document.getElementById("addLesson").style.display = "flex";
}

function cancelLesson() {
  document.getElementById("addLesson").style.display = "none";
}

function toggleSubMenu(btn) {
  btn.classList.toggle("active");
  var subMenu = btn.nextElementSibling;
  if (subMenu.style.maxHeight) {
    subMenu.style.maxHeight = null;
  } else {
    subMenu.style.maxHeight = subMenu.scrollHeight + "px";
  }
}

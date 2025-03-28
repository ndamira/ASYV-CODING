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

function approveUser(userId) {
  document.getElementById("approveUserId").value = userId;
  document.getElementById("approve").classList.add("show-approve");
}

function closeApprove() {
  document.getElementById("approve").classList.remove("show-approve");
}

// logic for approving and user
// function approveUser(userId) {
//   document.getElementById("approveUserId").value = userId;
//   document.getElementById("approve").style.display = "block";
// }

// function closeApprove() {
//   document.getElementById("approve").style.display = "none";
// }

function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
      window.location.href = "deleteUser.php?id=" + userId;
  }
}


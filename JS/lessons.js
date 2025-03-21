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

// New functions for edit, delete, and view
function editLesson(lessonId, lessonName) {
  document.getElementById("edit-lesson-id").value = lessonId;
  document.getElementById("edit-lesson-name").value = lessonName;
  document.getElementById("editLesson").style.display = "flex";
}

function cancelEdit() {
  document.getElementById("editLesson").style.display = "none";
}

function deleteLesson(lessonId) {
  document.getElementById("delete-lesson-id").value = lessonId;
  document.getElementById("deleteLesson").style.display = "flex";
}

function cancelDelete() {
  document.getElementById("deleteLesson").style.display = "none";
}

function viewLesson(lessonId, lessonTitle, filePath) {
  document.getElementById("view-lesson-title").innerText = lessonTitle;
  
  // Get file extension
  var fileExtension = filePath.split('.').pop().toLowerCase();
  var contentContainer = document.getElementById("lesson-content-container");
  
  // Clear previous content
  contentContainer.innerHTML = '';
  
  // Display based on file type
  if (fileExtension === 'pdf') {
    contentContainer.innerHTML = '<embed src="' + filePath + '" type="application/pdf" width="100%" height="500px" />';
  } else if (fileExtension === 'txt') {
    // For text files, fetch and display content
    fetch(filePath)
      .then(response => response.text())
      .then(text => {
        contentContainer.innerHTML = '<pre style="white-space: pre-wrap;">' + text + '</pre>';
      })
      .catch(error => {
        contentContainer.innerHTML = '<p>Error loading file: ' + error.message + '</p>';
      });
  } else if (fileExtension === 'doc' || fileExtension === 'docx') {
    contentContainer.innerHTML = '<p>Word document cannot be displayed directly. <a href="' + filePath + '" download>Download the document</a></p>';
  } else {
    contentContainer.innerHTML = '<p>This file type cannot be displayed. <a href="' + filePath + '" download>Download the file</a></p>';
  }
  
  document.getElementById("viewLesson").style.display = "flex";
}

function cancelView() {
  document.getElementById("viewLesson").style.display = "none";
}
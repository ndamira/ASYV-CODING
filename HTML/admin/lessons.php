<?php
// Database connection
require_once '../backend/conn.php';
if(isset($_GET['course_id'])){
  $course_id=$_GET['course_id'];
  // Fetch all courses
  $query = "SELECT * FROM lessons WHERE course_id='$course_id' ORDER BY id DESC ";
  $result = mysqli_query($conn, $query);
}
else{
  header('location:course.php');
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Progress</title>
    <link rel="stylesheet" href="../../CSS/lessons.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: poppins, sans-serif;
        line-height: 1.5rem;
      }

      :root {
        --base-clr: #11121a;
        --line-clr: #42434a;
        --hover-clr: #222533;
        --text-clr: #e6e6ef;
        --accent-clr: rgb(75, 139, 102);
        --secondary-text-clr: #b0b3b1;
      }

      body {
        min-height: 100vh;
        min-height: 100dvh;
        background-color: var(--base-clr);
        color: var(--text-clr);
        display: grid;
        grid-template-columns: auto 1fr;
      }

      #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 250px;
        padding: 5px 1em;
        background-color: var(--accent-clr);
        border-right: 1px solid var(--line-clr);
        transition: 300ms ease-in-out;
        overflow-x: hidden;
        overflow-y: auto;
        z-index: 1000;
        transform: translateX(-100%);
      }

      #sidebar.open {
        transform: translateX(0);
      }

      #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 999;
      }

      #overlay.show {
        display: block;
      }

      #mobile-toggle {
        position: fixed;
        top: 15px;
        left: 15px;
        background: var(--accent-clr);
        border: none;
        color: var(--text-clr);
        padding: 10px;
        border-radius: 5px;
        z-index: 1001;
        display: none;
        cursor: pointer;
      }

      #sidebar ul {
        list-style: none;
      }

      #sidebar > ul > li:first-child {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 16px;
      }

      #sidebar > ul > li:first-child img {
        width: 4em;
        height: 4em;
        cursor: pointer;
        margin-left: auto;
        margin-right: auto;
      }

      #sidebar ul li.active a {
        color: var(--base-clr);
        font-weight: bold;
      }

      #sidebar ul li.active a:hover {
        color: var(--text-clr);
      }

      #sidebar a,
      #sidebar .dropdown-btn,
      #sidebar .logo {
        border-radius: 0.5em;
        padding: 0.85em;
        text-decoration: none;
        color: var(--text-clr);
        display: flex;
        align-items: center;
        gap: 1em;
      }

      .dropdown-btn {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        font: inherit;
        cursor: pointer;
      }

      #sidebar i {
        flex-shrink: 0;
      }

      #sidebar a span,
      #sidebar .dropdown-btn span {
        flex-grow: 1;
      }

      #sidebar a:hover,
      #sidebar .dropdown-btn:hover {
        background-color: var(--hover-clr);
      }

      #sidebar .sub-menu {
        display: grid;
        grid-template-rows: 1fr;
        transition: 300ms ease-in-out;
      }

      #sidebar .sub-menu > div {
        overflow: hidden;
      }

      #sidebar .sub-menu.show {
        grid-template-rows: 0fr;
      }

      .dropdown-btn i:last-child {
        transition: 200ms ease;
      }

      .rotate i:last-child {
        rotate: 180deg;
      }

      #sidebar .sub-menu a {
        padding-left: 2em;
      }

      main {
        padding: min(30px, 7%);
        background: var(--hover-clr);
        width: 100%;
      }

      /* Responsive Breakpoints */
      @media screen and (max-width: 768px) {
        body {
          grid-template-columns: 1fr;
        }

        #mobile-toggle {
          display: block;
        }

        #sidebar {
          width: 250px;
          max-width: 80%;
        }

        main {
          padding: 15px;
        }
      }

      @media screen and (min-width: 769px) {
        #sidebar {
          position: sticky;
          transform: translateX(0);
        }

        #mobile-toggle {
          display: none;
        }
      }

    /* Popup Styles */
    .popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
      overflow-y: auto;
      padding: 20px;
      box-sizing: border-box;
    }

    .popup .content {
      background-color: white;
      padding: 25px;
      border-radius: 8px;
      width: 90%;
      max-width: 550px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      position: relative;
      animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .popup h2 {
      margin-top: 0;
      color: #333;
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .popup hr {
      border: none;
      height: 1px;
      background-color: #e0e0e0;
      margin-bottom: 20px;
    }

    .popup form {
      margin-top: 15px;
    }

    .popup label {
      display: inline-block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #444;
    }

    .popup label span {
      color: #f44336;
      margin-left: 3px;
    }

    .popup input[type="text"],
    .popup textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 0.95rem;
      margin-bottom: 15px;
      box-sizing: border-box;
    }

    .popup input[type="file"] {
      width: 100%;
      padding: 8px 0;
      margin-bottom: 15px;
    }

    .popup .btn {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 20px;
    }

    .popup .btn button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .popup .btn .btn1 {
      background-color: #f0f0f0;
      color: #333;
    }

    .popup .btn .btn1:hover {
      background-color: #e0e0e0;
    }

    .popup .btn .btn2 {
      background-color: #4CAF50;
      color: white;
    }

    .popup .btn .btn2:hover {
      background-color: #45a049;
    }

    .popup .btn .btn3 {
      background-color: #f44336;
      color: white;
    }

    .popup .btn .btn3:hover {
      background-color: #d32f2f;
    }

    /* ------------------------------------View Content popup specific styles--------------------------------------- */
    #content_display {
      max-height: 400px;
      overflow-y: auto;
      padding: 15px;
      background-color: #f9f9f9;
      border-radius: 5px;
      margin-top: 10px;
    }

    /* Responsive adjustments */
    @media screen and (max-width: 768px) {
      .popup .content {
        width: 95%;
        padding: 20px;
      }
      
      .popup .btn button {
        padding: 8px 15px;
      }
    }

    /* For small screens */
    @media screen and (max-width: 480px) {
      .popup .btn {
        flex-direction: column;
        gap: 8px;
      }
      
      .popup .btn button {
        width: 100%;
      }
    }
    .lesson-links {
      margin-top: 15px;
      margin-bottom: 15px;
    }

    .link-input-group {
      display: flex;
      margin-bottom: 8px;
      align-items: center;
    }

    .link-title {
      flex: 1;
      padding: 8px;
      margin-right: 8px;
    }

    .link-url {
      flex: 2;
      padding: 8px;
      margin-right: 8px;
    }

    .remove-link {
      background-color: #ff5757;
      color: white;
      border: none;
      border-radius: 50%;
      width: 25px;
      height: 25px;
      cursor: pointer;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .add-link-btn {
      background-color: #4c5aaf;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 8px 12px;
      cursor: pointer;
      margin-top: 8px;
    }

    .add-link-btn:hover {
      background-color: #3a4694;
    }
    </style>
  </head>
  <body>
    <button id="mobile-toggle" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>

    <div id="overlay" onclick="toggleSidebar()"></div>
    <aside id="sidebar">
      <ul>
        <li>
          <img src="../../IMG/Designer.png" alt="" />
        </li>
        <li >
          <a href="index.php">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <button class="dropdown-btn" onclick="toggleSubMenu(this)">
            <i class="fa-solid fa-users"></i>
            <span>Users</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>
          <ul class="sub-menu">
            <div>
              <li><a href="registeredusers.php">Registered Users</a></li>
              <li><a href="addusers.php">Add User</a></li>
            </div>
          </ul>
        </li>
        <li>
        <button class="dropdown-btn" onclick="toggleSubMenu(this)">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Course</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>
          <ul class="sub-menu">
            <div>
              <li class="active"><a href="courses.php">Add Course</a></li>
              <li><a href="../admin/courseTostudent.php">Course Allocation</a></li>
            </div>
          </ul>
        </li>
        <li>
          <a href="../logout.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </aside>
    <main>
      <div class="title">
        <h2>Lessons</h2>
        <button onclick="addLesson()">
          <i class="fa-solid fa-plus"></i>
          Add Lesson
        </button>
      </div>
      
      <!-- Add Lesson Popup -->
<div class="popup" id="addLessonPopup">
  <div class="content">
    <h2>Add Lesson</h2>
    <hr />
    <form action="../backend/lessonUpload.php" method="POST" enctype="multipart/form-data">
      <div class="lesson-name">
        <label for="name"> Lesson Name<span>*</span> </label> <br />
        <input type="text" name="name" placeholder="name" required />
      </div>
      <input type="hidden" value='<?php echo $course_id; ?>' name="course_id">
      <div class="lesson-content">
        <label for="content">Content<span>*</span> </label>
        <br />
        <input type="file" name="content" class="file-name" required />
      </div>
      
      <!-- Dynamic Link Fields -->
      <div class="lesson-links">
        <label>Additional Resources/Links</label>
          <div id="linkContainer">
            <div class="link-input-group">
              <input type="text" name="link_title[]" placeholder="Link Title" class="link-title" />
              <input type="text" name="link_url[]" placeholder="Link URL (https://...)" class="link-url" />
              <button type="button" class="remove-link" onclick="removeLink(this)">×</button>
            </div>
          </div>
          <button type="button" class="add-link-btn" onclick="addNewLink()">+ Add Another Link</button>
        </div>
        
        <div class="btn">
          <button type="button" class="btn1" onclick="closePopup('addLessonPopup')">
            Cancel
          </button>
          <button type="submit" class="btn2" name="ADD_PURCHASE">
            Add
          </button>
        </div>
      </form>
    </div>
  </div>
      
      <!-- Edit Lesson Popup -->
      <div class="popup edit-lesson" id="editLessonPopup">
        <div class="content">
          <h2>Edit Lesson</h2>
          <hr />
          <form action="../backend/updateLesson.php" method="POST" enctype="multipart/form-data">
            <div class="lesson-name">
              <label for="edit_name"> Lesson Name<span>*</span> </label> <br />
              <input type="text" id="edit_name" name="name" placeholder="name" required />
            </div>
            <input type="hidden" id="edit_lesson_id" name="lesson_id">
            <input type="hidden" id="edit_course_id" value='<?php echo $course_id; ?>' name="course_id">
            <div class="lesson-content">
              <label for="edit_content">Content (Leave empty to keep current file)<span></span> </label>
              <br />
              <input type="file" id="edit_content" name="content" class="file-name" />
            </div>
            <div class="btn">
              <button type="button" class="btn1" onclick="closePopup('editLessonPopup')">
                Cancel
              </button>
              <button type="submit" class="btn2" name="UPDATE_LESSON">
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Delete Lesson Popup -->
      <div class="popup delete-lesson" id="deleteLessonPopup">
        <div class="content">
          <h2>Delete Lesson</h2>
          <hr />
          <p>Are you sure you want to delete this lesson? This action cannot be undone.</p>
          <form action="../backend/deleteLesson.php" method="POST">
            <input type="hidden" id="delete_lesson_id" name="lesson_id">
            <input type="hidden" value='<?php echo $course_id; ?>' name="course_id">
            <div class="btn">
              <button type="button" class="btn1" onclick="closePopup('deleteLessonPopup')">
                Cancel
              </button>
              <button type="submit" class="btn3" name="DELETE_LESSON">
                Delete
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- View Content Popup -->
      <div class="popup view-content" id="viewContentPopup">
        <div class="content">
          <h2 id="view_title">Lesson Content</h2>
          <hr />
          <div id="content_display">
            <p>Loading content...</p>
          </div>
          <div class="btn">
            <button type="button" class="btn1" onclick="closePopup('viewContentPopup')">
              Close
            </button>
          </div>
        </div>
      </div>
      
      <div class="lessons">
      <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="lesson">
          <div class="content">
            <div class="name">
              <h3><?php echo $row['title']; ?></h3>
            </div>
            <div class="btn">
              <button class="btn1" onclick="viewContent(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>')">View Content</button>
              <button class="btn2" onclick="editLesson(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>')">Edit</button>
              <button class="btn3" onclick="deleteLesson(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>')">Delete</button>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
          <p>No lesson found. Add a lesson to get started.</p>
        <?php endif; ?>
      </div>
      <div class="title">
        <h2>Assignment</h2>
        <button onclick="addAssignment()">
          <i class="fa-solid fa-plus"></i>
          Add Assignment
        </button>
      </div>
      
      <!-- Add Assignment Popup -->
      <div class="popup add-assignment" id="addAssignmentPopup">
        <div class="content">
          <h2>Add Assignment</h2>
          <hr />
          <form action="../backend/assignmentUpload.php" method="POST" enctype="multipart/form-data">
            <div class="assignment-name">
              <label for="assignment_name"> Assignment Name<span>*</span> </label> <br />
              <input type="text" name="name" placeholder="name" required />
            </div>
            <input type="hidden" value='<?php echo $course_id; ?>' name="course_id">
            <div class="assignment-description">
              <label for="description">Description<span>*</span> </label>
              <br />
              <textarea name="description" rows="4" required></textarea>
            </div>
            <div class="assignment-file">
              <label for="file">Assignment File (Optional)<span></span> </label>
              <br />
              <input type="file" name="file" class="file-name" />
            </div>
            <div class="btn">
              <button type="button" class="btn1" onclick="closePopup('addAssignmentPopup')">
                Cancel
              </button>
              <button type="submit" class="btn2" name="ADD_ASSIGNMENT">
                Add
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
    
    <script>
      // Function to toggle sidebar submenu
      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const mobileToggle = document.getElementById("mobile-toggle");

        sidebar.classList.toggle("open");
        overlay.classList.toggle("show");
        mobileToggle.classList.toggle("rotate");

        // Close all submenus when sidebar is closed
        if (!sidebar.classList.contains("open")) {
          Array.from(sidebar.getElementsByClassName("show")).forEach((ul) => {
            ul.classList.remove("show");
            ul.previousElementSibling.classList.remove("rotate");
          });
        }
      }

      function toggleSubMenu(button) {
        button.nextElementSibling.classList.toggle("show");
        button.classList.toggle("rotate");

        // Ensure sidebar is open on mobile when submenu is toggled
        const sidebar = document.getElementById("sidebar");
        if (!sidebar.classList.contains("open")) {
          toggleSidebar();
        }
      }

      // Close sidebar when clicking outside on mobile
      document.addEventListener("click", function (event) {
        const sidebar = document.getElementById("sidebar");
        const mobileToggle = document.getElementById("mobile-toggle");
        const overlay = document.getElementById("overlay");

        if (
          window.innerWidth <= 768 &&
          sidebar.classList.contains("open") &&
          !sidebar.contains(event.target) &&
          !mobileToggle.contains(event.target)
        ) {
          toggleSidebar();
        }
      });
      
      // Function to show Add Lesson popup
      function addLesson() {
        document.getElementById("addLessonPopup").style.display = "flex";
      }
      
      // Function to show Edit Lesson popup
      function editLesson(lessonId, lessonTitle) {
        document.getElementById("edit_lesson_id").value = lessonId;
        document.getElementById("edit_name").value = lessonTitle;
        document.getElementById("editLessonPopup").style.display = "flex";
      }
      
      // Function to show Delete Lesson popup
      function deleteLesson(lessonId, lessonTitle) {
        document.getElementById("delete_lesson_id").value = lessonId;
        document.querySelector("#deleteLessonPopup h2").innerText = "Delete Lesson: " + lessonTitle;
        document.getElementById("deleteLessonPopup").style.display = "flex";
      }
      
      // Function to show View Content popup
      function viewContent(lessonId, lessonTitle) {
        document.getElementById("view_title").innerText = lessonTitle;
        document.getElementById("content_display").innerHTML = "<p>Loading content...</p>";
        document.getElementById("viewContentPopup").style.display = "flex";
        
        // Fetch content using AJAX
        fetch(`../backend/getContent.php?lesson_id=${lessonId}`)
          .then(response => response.text())
          .then(data => {
            document.getElementById("content_display").innerHTML = data;
          })
          .catch(error => {
            document.getElementById("content_display").innerHTML = "<p>Error loading content. Please try again.</p>";
          });
      }
      
      // Function to show Add Assignment popup
      function addAssignment() {
        document.getElementById("addAssignmentPopup").style.display = "flex";
      }
      
      // Function to close any popup
      function closePopup(popupId) {
        document.getElementById(popupId).style.display = "none";
      }
      
      // Close popups when clicking outside the content
      window.onclick = function(event) {
        const popups = document.getElementsByClassName("popup");
        for (let i = 0; i < popups.length; i++) {
          if (event.target === popups[i]) {
            popups[i].style.display = "none";
          }
        }
      }

      // for dynamic links
function addNewLink() {
  const linkContainer = document.getElementById('linkContainer');
  const newLinkGroup = document.createElement('div');
  newLinkGroup.className = 'link-input-group';
  newLinkGroup.innerHTML = `
    <input type="text" name="link_title[]" placeholder="Link Title" class="link-title" />
    <input type="text" name="link_url[]" placeholder="Link URL (https://...)" class="link-url" />
    <button type="button" class="remove-link" onclick="removeLink(this)">×</button>
  `;
  linkContainer.appendChild(newLinkGroup);
}

function removeLink(button) {
  const linkGroup = button.parentElement;
  linkGroup.remove();
}

    </script>
  </body>
</html>
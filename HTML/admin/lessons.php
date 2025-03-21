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
  </head>
  <body>
    <aside id="sidebar">
      <ul>
        <li>
          <img src="../../IMG/Designer.png" class="logo" />
          <!-- <span class="logo">ASYV CODING</span>
                <button id="toggle-btn" onclick="toggleSidebar()">
                    <i class="fa-solid fa-angles-left"></i>
                </button> -->
        </li>
        <li>
          <a href="dashboard.html">
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
              <li><a href="users.html">Registered Users</a></li>
              <li><a href="pendingUsers.html">Pending Users</a></li>
            </div>
          </ul>
        </li>
        <li class="active">
          <a href="course.html">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Courses</span>
          </a>
        </li>
        <li>
          <a href="progress.html">
            <i class="fa-solid fa-spinner"></i>
            <span>Progress</span>
          </a>
        </li>
        <li>
          <button class="dropdown-btn" onclick="toggleSubMenu(this)">
            <i class="fa-regular fa-circle-user"></i>
            <span>Profile</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>
          <ul class="sub-menu">
            <div>
              <li><a href="">Setting</a></li>
              <li><a href="">Logout</a></li>
            </div>
          </ul>
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
      <div class="add-lesson" id="addLesson">
        <div class="content">
          <h2>Add Lesson</h2>
          <hr />
          <form action="../../backend/lessonUpload.php" method="POST" enctype="multipart/form-data">
            <div class="lesson-name">
              <label for="name"> Lesson Name<span>*</span> </label> <br />
              <input type="text" name="name" placeholder="name" required />
            </div>
            <input type="hidden" value='<?php echo $course_id; ?>' name="course_id" >
            <div class="lesson-content">
              <label for="content">Content<span>*</span> </label>
              <br />
              <input type="file" name="content" class="file-name" required />
            </div>
            <div class="btn">
              <button type="button" class="btn1" onclick="cancelLesson()">
                Cancel
              </button>
              <button type="submit" class="btn2" name="ADD_PURCHASE">
                Add
              </button>
            </div>
          </form>
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
              <button class="btn1">View Content</button>
              <button class="btn2">Edit</button>
              <button class="btn3">Delete</button>
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
        <button onclick="addCourse()">
          <i class="fa-solid fa-plus"></i>
          Add Assignment
        </button>
      </div>
    </main>
    <script src="../../JS/lessons.js"></script>
  </body>
</html>

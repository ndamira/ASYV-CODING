<?php
// Database connection
session_start();
    // Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.php");
  exit();
}
require_once '../backend/conn.php';

// Check if form is submitted to add a new course
if(isset($_POST['add_course'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Default image path
    $image_path = "../../IMG/course1.jpeg";
    
    // Insert course into database
    $query = "INSERT INTO courses (name, description) 
              VALUES ('$name', '$description')";
    
    if(mysqli_query($conn, $query)) {
        // Redirect to prevent form resubmission
        header("Location: course.php?status=success");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch all courses
$query = "SELECT * FROM courses ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Courses Management</title>
    
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="../../CSS/course.css" />
    <style>
      /* Success and error messages */
      .message {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
      }

      .success {
        background-color: #dff0d8;
        border: 1px solid #d6e9c6;
        color: #3c763d;
      }

      .error {
        background-color: #f2dede;
        border: 1px solid #ebccd1;
        color: #a94442;
      }

      /* Course grid layout */
      .courses {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px 0;
      }

      .course {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
      }

      .course:hover {
        transform: translateY(-5px);
      }

      .course .img {
        height: 150px;
        overflow: hidden;
      }

      .course .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .course .content {
        padding: 15px;
      }

      .course .lessons {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        color: #666;
      }

      .course .name {
        margin-top: 10px;
      }

      .course .name h3 {
        margin: 0;
        font-size: 1.2rem;
      }

      .course .description {
        margin-top: 10px;
        font-size: 0.9rem;
        color: #666;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      /* Add course modal */
      .addcourse {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
      }

      .add-course .content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
      }

      .add-course h2 {
        margin-top: 0;
      }

      .add-course form > div {
        margin-bottom: 15px;
      }

      .add-course label {
        font-weight: bold;
      }

      .add-course label span {
        color: red;
      }

      .add-course input, 
      .add-course textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 5px;
      }

      .add-course textarea {
        height: 100px;
        resize: vertical;
      }

      .add-course .btn {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
      }

      .add-course button {
        padding: 8px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }

      .add-course .btn1 {
        background-color: #f5f5f5;
        color: #333;
      }

      .add-course .btn2 {
        background-color: #4CAF50;
        color: white;
      }

      /* Main content area */
      main {
        padding: 20px;
        margin-left: 250px;
        transition: margin-left 300ms ease-in-out;
      }

      main .title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
      }

      main .title button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
      }

      @media (max-width: 768px) {
        main {
          margin-left: 50px;
        }
        
        #sidebar {
          width: 50px;
          padding: 5px;
        }
        
        #sidebar .logo span,
        #sidebar a span,
        #sidebar .dropdown-btn span {
          display: none;
        }
      }
  </style>
  </head>
  <body>
    <aside id="sidebar">
      <ul>
        <li>
          <img src="../../IMG/Designer.png" alt="" />
          <!-- <span class="logo">ASYV CODING</span>
                <button id="toggle-btn" onclick="toggleSidebar()">
                    <i class="fa-solid fa-angles-left"></i>
                </button> -->
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
              <li><a href="users.php">Registered Users</a></li>
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
              <li class="active"><a href="cou.php">Add Course</a></li>
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
        <h2>Courses</h2>
        <button onclick="addCourse()">Add Course</button>
      </div>

      <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="message success">
          Course added successfully!
        </div>
      <?php endif; ?>

      <?php if(isset($error)): ?>
        <div class="message error">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

    <div class="add-course" id="addCourse">
      <div class="content">
        <h2>Add Course</h2>
        <button onclick="closePopup()" style="float:right; background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
        <hr />
        <form action="" method="POST">
          <div class="course-name">
            <label for="name">Course Name<span>*</span></label>
            <input type="text" id="name" name="name" required />
          </div>
          <div class="course-description">
            <label for="description">Description<span>*</span></label>
            <textarea id="description" name="description" required></textarea>
          </div>
          <div class="btn">
            <button type="button" class="btn1" onclick="closePopup()">Cancel</button>
            <button type="submit" name="add_course" class="btn2">Add Course</button>
          </div>
        </form>
      </div>
    </div>
      
      <div class="courses">
        <?php if(mysqli_num_rows($result) > 0): ?>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <a href="lessons.php?course_id=<?php echo $row['id']; ?>">
              <div class="course">
                <div class="img">
                  <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['course_name']; ?>" />
                </div>
                <div class="content">
                  <div class="lessons">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <?php 
                    // Count lessons for this course
                    $course_id = $row['id'];
                    $lesson_query = "SELECT COUNT(*) as lesson_count FROM lessons WHERE course_id = $course_id";
                    $lesson_result = mysqli_query($conn, $lesson_query);
                    $lesson_count = mysqli_fetch_assoc($lesson_result)['lesson_count'];
                    ?>
                    <p><?php echo $lesson_count; ?> Lessons</p>
                  </div>
                  <div class="name">
                    <h3><?php echo $row['name']; ?></h3>
                  </div>
                  <div class="description">
                    <p><?php echo $row['description']; ?></p>
                  </div>
                </div>
              </div>
            </a>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No courses found. Add a course to get started.</p>
        <?php endif; ?>
      </div>
    </main>
    <script>
  function addCourse() {
    alert('Hello Word');
    document.getElementById("addCourse").style.display = "flex";
  }

  function closePopup() {
    document.getElementById("addCourse").style.display = "none";
  }

  // Close popup when clicking outside of it
  window.onclick = function(event) {
    var modal = document.getElementById("addCourse");
    if (event.target === modal) {
      modal.style.display = "none";
    }
  };
</script>
    <!-- <script src='../../JS/course.js'></script> -->
    
  </body>
</html>
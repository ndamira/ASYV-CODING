<?php
// Database connection
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
    <style>
        * {
        margin: 0;
        padding: 0;
        /* box-sizing: border-box; */
        font-family: poppins, sans-serif;
        line-height: 1.5rem;
        }

        #sidebar img {
        width: 4em;
        height: 4em;
        cursor: pointer;
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
        position: sticky;
        top: 0;
        align-self: start;
        box-sizing: border-box;
        height: 100vh;
        width: 250px;
        padding: 5px 1em;
        text-wrap: nowrap;
        background-color: var(--accent-clr);
        border-right: 1px solid var(--line-clr);
        transition: 300ms ease-in-out;
        overflow: hidden;
        }

        #sidebar.close {
        padding: 5px;
        width: 50px;
        }

        #sidebar ul {
        list-style: none;
        }

        #sidebar > ul > li:first-child {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 16px;
        .logo {
            font-weight: bold;
        }
        }

        #sidebar ul li.active a {
        color: var(--base-clr);
        font-weight: bold;

        i {
            fill: var(--accent-clr);
        }
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
        fill: var(--text-clr);
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

        > div {
            overflow: hidden;
        }

        transition: 300ms ease-in-out;
        }

        #sidebar .sub-menu.show {
        grid-template-rows: 0fr;
        }

        .dropdown-btn i {
        transition: 200ms ease;
        }

        .rotate i:last-child {
        rotate: 180deg;
        }

        #sidebar .sub-menu a {
        padding-left: 2em;
        }

        #sidebar img {
        margin-left: auto;
        margin-right: auto;
        padding: 1em;
        border: none;
        background: none;
        border-radius: 0.5em;
        cursor: pointer;
        color: var(--text-clr);

        i {
            transition: rotate 150ms ease;
        }
        }

        #toggle-btn:hover {
        background-color: var(--hover-clr);
        }

        main {
        padding: min(30px, 7%);
        background: var(--hover-clr);
        }

        main .title {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

main .title h2 {
  color: var(--accent-clr);
}

main .title button {
  background: var(--accent-clr);
  border: none;
  outline: none;
  padding: 5px 6px;
  border-radius: 5px;
  color: var(--text-clr);
  cursor: pointer;
}


        /* ------------------------------------Course grid layout---------------------------------- */
        a{
            text-decoration: none;
            color: var(--text-clr);
        }
        .courses {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .course {
            background: var(--accent-clr);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            padding: 0;
        }

        .course:hover {
            transform: translateY(-5px);
        }

        .course img {
            width: 100%;
            height: 10rem;
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
            color: var(--base-clr);
            font-size: 18px;
        }

        .course .name {
            margin-top: 10px;
        }

        .course .name h3 {
            margin: 0;
            font-size: 1.3rem;
        }

        .course .description {
            margin-top: 10px;
            font-size: 0.9rem;
            color: var(--base-clr);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ------------------------------------------------Add course modal--------------------------------------------------- */
      /* Modal overlay */
      .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        /* Modal container */
        .modal-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.3s ease-out;
        }

        /* Modal content */
        .modal-content {
            padding: 24px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--accent-clr);
            margin-bottom: 16px;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 4px;
        }

        .form-input {
            width: 94%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        textarea.form-input {
            min-height: 100px;
            resize: vertical;
        }

        /* Button group */
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .cancel-button {
            background-color: #e5e7eb;
            color: #1f2937;
            font-weight: 500;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .cancel-button:hover {
            background-color: #d1d5db;
        }

        .submit-button {
            background-color: var(--accent-clr);
            color: white;
            font-weight: 500;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s;
        }

        .submit-button:hover {
            background-color: #2563eb;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
  </head>
  <body>
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
        <h2>Courses</h2>
        <button id="openModalBtn" class="add-button">Add Course</button>
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

    <!-- ADD COURSE POPUP -->
    <div id="modalOverlay" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-content">
                <h2 class="modal-title">Add Course</h2>
                
                <form id="addCourseForm" method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">Course Name</label>
                        <input type="text" id="courseName" class="form-input" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Course Description</label>
                        <textarea id="courseDescription" class="form-input" name="description" required></textarea>
                    </div>
                    
                    <div class="button-group">
                        <button type="button" id="cancelBtn" class="cancel-button">Cancel</button>
                        <button type="submit" class="submit-button" name="add_course">Add Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- <div class="add-course" id="addCourse">
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
    </div> -->
      <div class="courses">
        <?php if(mysqli_num_rows($result) > 0): ?>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <a href="lessons.php?course_id=<?php echo $row['id']; ?>">
              <div class="course">
                <img src="../../IMG/course.jpeg" alt="">
                <!-- <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['course_name']; ?>" /> -->
            
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
        let toggleButton = document.getElementById("toggle-btn");
        let sidebar = document.getElementById("sidebar");


        function toggleSidebar(){
            sidebar.classList.toggle("close");
            toggleButton.classList.toggle("rotate");
            Array.from(sidebar.getElementsByClassName("show")).forEach(ul =>{
                ul.classList.remove("show");
                ul.previousElementSibling.classList.remove("rotate");
            })
        }


        function toggleSubMenu(button){
            button.nextElementSibling.classList.toggle("show");
            button.classList.toggle("rotate");

            if(sidebar.classList.contains("close")){
                sidebar.classList.toggle("close");
                toggleButton.classList.toggle("rotate");
            }
        }

        // Get DOM elements
        const openModalBtn = document.getElementById('openModalBtn');
        const modalOverlay = document.getElementById('modalOverlay');
        const cancelBtn = document.getElementById('cancelBtn');
        const addCourseForm = document.getElementById('addCourseForm');
        const courseNameInput = document.getElementById('courseName');
        const courseDescriptionInput = document.getElementById('courseDescription');

        // Function to open modal
        function openModal() {
            modalOverlay.style.display = 'flex';
        }

        // Function to close modal
        function closeModal() {
            modalOverlay.style.display = 'none';
            // Clear form
            addCourseForm.reset();
        }

        // Add click event to open modal
        openModalBtn.addEventListener('click', openModal);

        // Add click event to cancel button
        cancelBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside modal content
        modalOverlay.addEventListener('click', function(event) {
            if (event.target === modalOverlay) {
                closeModal();
            }
        });

        // Handle form submission
        addCourseForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Get form values
            const courseName = courseNameInput.value.trim();
            const courseDescription = courseDescriptionInput.value.trim();
            
            // Here you would typically send data to server or store it
            console.log('Course added:', { 
                name: courseName, 
                description: courseDescription 
            });
            
            // Close the modal after submission
            closeModal();
        });
    </script>
  </body>
</html>
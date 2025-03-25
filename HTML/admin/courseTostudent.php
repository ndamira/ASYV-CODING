<?php
// Database connection configuration
$host = "localhost";
$dbname = "newplatform";
$username = "root";
$password = "";

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to check if a course allocation already exists
function isAllocationExists($pdo, $user_id, $course_id) {
    $stmt = $pdo->prepare("SELECT id FROM user_courses WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    return $stmt->rowCount() > 0;
}

// Function to allocate a course to a user
function allocateCourse($pdo, $user_id, $course_id) {
    // Check if the allocation already exists
    if (isAllocationExists($pdo, $user_id, $course_id)) {
        return ["success" => false, "message" => "This course is already allocated to this student."];
    }
    
    // Check if the user exists
    $userStmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $userStmt->execute([$user_id]);
    if ($userStmt->rowCount() == 0) {
        return ["success" => false, "message" => "Student does not exist."];
    }
    
    // Check if the course exists
    $courseStmt = $pdo->prepare("SELECT id FROM courses WHERE id = ?");
    $courseStmt->execute([$course_id]);
    if ($courseStmt->rowCount() == 0) {
        return ["success" => false, "message" => "Course does not exist."];
    }
    
    // Perform the allocation
    $stmt = $pdo->prepare("INSERT INTO user_courses (user_id, course_id, allocation_date) VALUES (?, ?, NOW())");
    $result = $stmt->execute([$user_id, $course_id]);
    
    if ($result) {
        return ["success" => true, "message" => "Course allocated successfully."];
    } else {
        return ["success" => false, "message" => "Failed to allocate course."];
    }
}

// Function to update a course allocation
function updateCourseAllocation($pdo, $allocation_id, $new_course_id) {
    // Get current user_id from allocation
    $stmt = $pdo->prepare("SELECT user_id FROM user_courses WHERE id = ?");
    $stmt->execute([$allocation_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        return ["success" => false, "message" => "Allocation not found."];
    }
    
    $user_id = $result['user_id'];
    
    // Check if the new allocation would create a duplicate
    if (isAllocationExists($pdo, $user_id, $new_course_id)) {
        return ["success" => false, "message" => "Student is already allocated to this course."];
    }
    
    // Check if the course exists
    $courseStmt = $pdo->prepare("SELECT id FROM courses WHERE id = ?");
    $courseStmt->execute([$new_course_id]);
    if ($courseStmt->rowCount() == 0) {
        return ["success" => false, "message" => "New course does not exist."];
    }
    
    // Update the allocation
    $updateStmt = $pdo->prepare("UPDATE user_courses SET course_id = ?, updated_at = NOW() WHERE id = ?");
    $result = $updateStmt->execute([$new_course_id, $allocation_id]);
    
    if ($result) {
        return ["success" => true, "message" => "Course allocation updated successfully."];
    } else {
        return ["success" => false, "message" => "Failed to update course allocation."];
    }
}

// Function to unassign a course from a user
function unassignCourse($pdo, $user_id, $course_id) {
    if (!isAllocationExists($pdo, $user_id, $course_id)) {
        return ["success" => false, "message" => "This allocation does not exist."];
    }
    
    $stmt = $pdo->prepare("DELETE FROM user_courses WHERE user_id = ? AND course_id = ?");
    $result = $stmt->execute([$user_id, $course_id]);
    
    if ($result) {
        return ["success" => true, "message" => "Course unassigned successfully."];
    } else {
        return ["success" => false, "message" => "Failed to unassign course."];
    }
}

// Function to get all courses allocated to a specific user
function getUserCourses($pdo, $user_id) {
    $stmt = $pdo->prepare("
        SELECT c.id, c.course_name, c.course_code, uc.allocation_date 
        FROM courses c 
        JOIN user_courses uc ON c.id = uc.course_id 
        WHERE uc.user_id = ?
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all users allocated to a specific course
function getCourseUsers($pdo, $course_id) {
    $stmt = $pdo->prepare("
        SELECT u.id, u.name, u.email, uc.allocation_date 
        FROM users u 
        JOIN user_courses uc ON u.id = uc.user_id 
        WHERE uc.course_id = ?
    ");
    $stmt->execute([$course_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle different form actions
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        // Allocate course
        if ($action == 'allocate') {
            $user_id = $_POST['user_id'];
            $course_id = $_POST['course_id'];
            $result = allocateCourse($pdo, $user_id, $course_id);
            $message = $result['message'];
            $success = $result['success'];
        }
        
        // Update allocation
        else if ($action == 'update') {
            $allocation_id = $_POST['allocation_id'];
            $new_course_id = $_POST['new_course_id'];
            $result = updateCourseAllocation($pdo, $allocation_id, $new_course_id);
            $message = $result['message'];
            $success = $result['success'];
        }
        
        // Unassign course
        else if ($action == 'unassign') {
            $user_id = $_POST['user_id'];
            $course_id = $_POST['course_id'];
            $result = unassignCourse($pdo, $user_id, $course_id);
            $message = $result['message'];
            $success = $result['success'];
        }
    }
}

// Get list of users and courses for dropdown menus
$usersStmt = $pdo->query("SELECT id, first_name, email FROM users ORDER BY first_name");
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

$coursesStmt = $pdo->query("SELECT id, name, name FROM courses ORDER BY name");
$courses = $coursesStmt->fetchAll(PDO::FETCH_ASSOC);

// Get list of current allocations
$allocationsStmt = $pdo->query("
    SELECT uc.id, u.first_name as user_name, c.name, uc.user_id, uc.course_id, uc.allocation_date
    FROM user_courses uc
    JOIN users u ON uc.user_id = u.id
    JOIN courses c ON uc.course_id = c.id
    ORDER BY u.first_name, c.name
");
$allocations = $allocationsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASYV-Coding</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        /* -------------------------------------------COURSE ALLOCATION------------------------------------------- */

        .container {
            max-width: 1200px;
            padding: 20px;
        }

        /* Header Styling */
        h1 {
            color: var(--accent-clr);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--accent-clr);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            overflow: hidden;
            background-color: #fff;
        }

        .card-header {
            background-color: var(--accent-clr);
            border-bottom: 1px solidvar(--accent-clr);
            padding: 15px 20px;
        }

        .card-header h5 {
            margin: 0;
            color: var(--text-clr);
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* Form Styling */
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-select, .form-control {
            width: 100%;
            border-radius: 6px;
            margin-bottom: 10px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--accent-clr);
            outline: 0;
            box-shadow: 0 0 0 0.25rem var(--accent-clr);
        }

        /* Button Styling */
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary {
            background-color:var(--accent-clr);
            border-color: var(--accent-clr);
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: var(--accent-clr);
            border-color:var(--accent-clr);
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
            color: white;
        }

        .btn-warning:hover {
            background-color: #d35400;
            border-color: #d35400;
            color: white;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }

        /* Table Styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table th {
            background-color: #f1f8ff;
            color: var(--accent-clr);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 12px 15px;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            text-align: center;
            color: #000;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }

        /* Alert Styling */
        .alert {
            border-radius: 6px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            
            .col-md-6 {
                width: 100%;
                margin-bottom: 20px;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        
        .card {
            margin-bottom: 20px;
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
              <li><a href="courses.php">Add Course</a></li>
              <li class="active"><a href="../admin/courseTostudent.php">Course Allocation</a></li>
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
    <div class="container">
        <h1 class="mb-4">Course Allocation</h1>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Allocate Course Form -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Allocate Course to Student</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="action" value="allocate">
                            
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Select Student</label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    <option value="">-- Select Student --</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>">
                                            <?php echo htmlspecialchars($user['first_name']) . ' (' . htmlspecialchars($user['email']) . ')'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Select Course</label>
                                <select name="course_id" id="course_id" class="form-select" required>
                                    <option value="">-- Select Course --</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?php echo $course['id']; ?>">
                                            <?php echo htmlspecialchars($course['name']) . ' (' . htmlspecialchars($course['name']) . ')'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Allocate Course</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Update Allocation Form -->
            <!-- <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Update Course Allocation</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="action" value="update">
                            
                            <div class="mb-3">
                                <label for="allocation_id" class="form-label">Select Allocation to Update</label>
                                <select name="allocation_id" id="allocation_id" class="form-select" required>
                                    <option value="">-- Select Allocation --</option>
                                    <?php foreach ($allocations as $allocation): ?>
                                        <option value="<?php echo $allocation['id']; ?>">
                                            <?php echo htmlspecialchars($allocation['name']) . ' - ' . htmlspecialchars($allocation['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_course_id" class="form-label">Select New Course</label>
                                <select name="new_course_id" id="new_course_id" class="form-select" required>
                                    <option value="">-- Select New Course --</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?php echo $course['id']; ?>">
                                            <?php echo htmlspecialchars($course['name']) . ' (' . htmlspecialchars($course['name']) . ')'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-warning">Update Allocation</button>
                        </form>
                    </div>
                </div>
            </div> -->
        </div>
        
        <!-- Unassign Course Form -->
        <!-- <div class="card">
            <div class="card-header">
                <h5>Unassign Course</h5>
            </div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="unassign">
                    
                    <div class="col-md-5">
                        <label for="user_id_unassign" class="form-label">Select Student</label>
                        <select name="user_id" id="user_id_unassign" class="form-select" required>
                            <option value="">-- Select Student --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo htmlspecialchars($user['first_name']) . ' (' . htmlspecialchars($user['email']) . ')'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-5">
                        <label for="course_id_unassign" class="form-label">Select Course</label>
                        <select name="course_id" id="course_id_unassign" class="form-select" required>
                            <option value="">-- Select Course --</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['name']) . ' (' . htmlspecialchars($course['name']) . ')'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger">Unassign Course</button>
                    </div>
                </form>
            </div>
        </div> -->
        
        <!-- Current Allocations Table -->
        <div class="card">
            <div class="card-header">
                <h5>Current Course Allocations</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Course Name</th>
                                <th>Allocation Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($allocations) > 0): ?>
                                <?php foreach ($allocations as $allocation): ?>
                                    <tr>
                                        <td><?php echo $allocation['id']; ?></td>
                                        <td><?php echo htmlspecialchars($allocation['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($allocation['name']); ?></td>
                                        <td><?php echo $allocation['allocation_date']; ?></td>
                                        <td>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="unassign">
                                                <input type="hidden" name="user_id" value="<?php echo $allocation['user_id']; ?>">
                                                <input type="hidden" name="course_id" value="<?php echo $allocation['course_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Unassign</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No allocations found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </main>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
     <script>
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
     </script>
</body>
</html>
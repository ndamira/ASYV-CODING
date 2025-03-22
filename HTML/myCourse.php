<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !='student') {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Database connection
require_once 'backend/conn.php'; // Assuming you have a database connection file

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Query to get assigned courses for this student
$sql = "SELECT c.* FROM courses c 
        INNER JOIN user_courses uc ON c.id = uc.course_id 
        WHERE uc.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);

// Count number of courses
$course_count = count($courses);

// Get user info
$user_sql = "SELECT first_name, last_name FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASYV-My Course</title>
    <link rel="stylesheet" href="../CSS/myCourse.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=close" />
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="myCourse.php">
                    <img src="../IMG/Designer.png" alt="ASYV-CODING lOGO">
                </a>
            </div>
            <div class="search">
                <i class="fa-solid fa-magnifying-glass" onclick="searchCourse()"></i>
                <input type="text" name="search" id="search" onkeyup="searchCourse()" placeholder="Search for Courses">
            </div>
            <div class="profile">
                <div class="notifications">
                    <i class="fa-solid fa-bell" id="bell" onclick="openNotification()"></i>
                    <div class="notice" id="notice">
                        <div class="head">
                            <h3>Notifications</h3>
                            <i class="fa-solid fa-xmark" onclick="closeNotification()"></i>
                        </div>
                        <div class="info">
                            <div class="card">
                                <div class="user">
                                    <img src="../IMG/user.png" alt="">
                                </div>
                                <div class="message">
                                    <h4>March 08th 2025</h4>
                                    <p>Lorem ipsum dolor sit, amet consectetur</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="user">
                                    <img src="../IMG/user.png" alt="">
                                </div>
                                <div class="message">
                                    <h4>March 08th 2025</h4>
                                    <p>Lorem ipsum dolor sit, amet consectetur</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user">
                    <div class="profile">
                        <img src="<?php echo !empty($user_data['profile_image']) ? '../uploads/profiles/' . $user_data['profile_image'] : '../IMG/paccy.jpeg'; ?>" onclick="openLogout()">
                        <div class="info" id="logout">
                            <div class="head">
                                <div class="personInfo">
                                    <div class="left">
                                        <img src="<?php echo !empty($user_data['profile_image']) ? '../uploads/profiles/' . $user_data['profile_image'] : '../IMG/paccy.jpeg'; ?>">
                                        <div class="name">
                                            <h4><?php echo htmlspecialchars($user_data['first_name'] ?? 'Name'); ?></h4>
                                            <p><?php echo htmlspecialchars($user_data['last_name'] ?? 'Username'); ?></p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-xmark" onclick="closeLogout()"></i>
                                </div>
                            </div>
                            <div class="logout">
                                <div class="myProfile">
                                    <i class="fa-regular fa-user"></i>
                                    <p>My Profile</p>
                                </div>
                                <div class="myProfile">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <p><a href="logout.php">Logout</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="ribbon">
            <a href="#"><p class="active">My Courses</p></a>
        </div>
    </header>
    <main>
        <div class="myProgress">
            <h3>My Progress</h3>
            <div class="statistics">
                <div class="numbers">
                    <p><?php echo $course_count; ?></p>
                    <p>Courses</p>
                </div>
                <hr>
                <div class="numbers">
                    <p>0</p>
                    <p>Completed</p>
                </div>
            </div>
        </div>
        <div class="allCourses">
            <h2>Courses (<?php echo $course_count; ?>) </h2>
            <div class="courses">
                <?php if ($course_count > 0): ?>
                    <?php foreach ($courses as $course): ?>
                        <?php 
                        // Get lesson count
                        $lesson_sql = "SELECT COUNT(*) as lesson_count FROM lessons WHERE course_id = ?";
                        $lesson_stmt = $conn->prepare($lesson_sql);
                        $lesson_stmt->bind_param("i", $course['id']);
                        $lesson_stmt->execute();
                        $lesson_result = $lesson_stmt->get_result();
                        $lesson_data = $lesson_result->fetch_assoc();
                        $lesson_count = $lesson_data ? $lesson_data['lesson_count'] : 0;
                        ?>
                        <a href="slessons.php?course_id=<?php echo htmlspecialchars($course['id']); ?>">

                        <div class="course">
                            <img src="<?php echo !empty($course['course_image']) ? '../uploads/courses/' . $course['course_image'] : '../IMG/course1.jpeg'; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                            <div class="content">
                                <div class="lessons">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    <p><?php echo $lesson_count; ?> Lessons</p>
                                </div>
                                <div class="name">
                                    <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                    <p>0% Completed</p>
                                </div>
                            </div>
                        </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No courses assigned yet. Please contact your instructor.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- <div class="completed">
            <h2>Completed Courses (0)</h2>
            <div class="courses">
                <div class="course">
                    <img src="../IMG/course1.jpeg" alt="">
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>0.1 Introduction To HTML</h3>
                            <p> 100% Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </main>
    <footer>
        <p>Powered by ASYV IT Department</p>
    </footer>
    <script src="../JS/myCourse.js"></script>
</body>
</html>
<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
} else if (!isset($_GET['course_id'])) {
    header("Location: myCourse.php");
    exit();
}

// Database connection
include('backend/conn.php');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch course information
$course_id = $_GET['course_id'];
$course_sql = "SELECT name FROM courses WHERE id = ?";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();
$course = $course_result->fetch_assoc();
$course_name = $course ? $course['course_name'] : "Course Not Found";

// Fetch lessons for this course
$lessons_sql = "SELECT id,title, content FROM lessons WHERE course_id = ? ORDER BY id ASC";
$lessons_stmt = $conn->prepare($lessons_sql);
$lessons_stmt->bind_param("i", $course_id);
$lessons_stmt->execute();
$lessons_result = $lessons_stmt->get_result();
$lessons = [];
while ($row = $lessons_result->fetch_assoc()) {
    $lessons[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course_name); ?></title>
    <link rel="stylesheet" href="../CSS/unit1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=close" />
    <style>
        .resources-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .resources-table th, .resources-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .resources-table th {
            background-color: #f2f2f2;
        }
        
        .resources-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .resources-title {
            margin-top: 30px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .resource-link {
            color: #0066cc;
            text-decoration: none;
        }
        
        .resource-link:hover {
            text-decoration: underline;
        }
    </style>
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
                        <img src="../IMG/paccy.jpeg" onclick="openLogout()">
                        <div class="info" id="logout">
                            <div class="head">
                                <div class="personInfo">
                                    <div class="left">
                                        <img src="../IMG/paccy.jpeg">
                                        <div class="name">
                                            <h4><?php echo htmlspecialchars($_SESSION['first_name'] ?? 'User'); ?></h3>
                                            <p><?php echo htmlspecialchars($_SESSION['last_name'] ?? 'username'); ?></p>
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
                                    <p><a href="logout.php" style="text-decoration: none; color: inherit;">Logout</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="sidebar">
            <div class="course-title">
                <a href="myCourse.php"><i class="fa-solid fa-arrow-left"></i></a>
                <h3><?php echo htmlspecialchars($course_name); ?></h3>
            </div>
            <div class="lessons">
                <?php if (count($lessons) > 0): ?>
                    <?php foreach ($lessons as $index => $lesson): ?>
                        <div class="lesson <?php echo $index === 0 ? 'active' : ''; ?>" data-lesson-id="<?php echo $lesson['id']; ?>">
                            <p><?php echo sprintf("%02d", $lesson['id']); ?></p>
                            <p><?php echo htmlspecialchars($lesson['title']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-lessons">
                        <p>No lessons available for this course yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="lesson-content">
            <?php if (count($lessons) > 0): ?>
                <?php foreach ($lessons as $index => $lesson): ?>
                    <div class="information <?php echo $index !== 0 ? 'hide-content' : ''; ?>" id="lesson-<?php echo $lesson['id']; ?>">
                        <div class="title">
                            <div class="name">
                                <h3><?php echo htmlspecialchars($lesson['title']); ?></h3>
                                <p>Lesson <?php echo $lesson['id']; ?></p>
                            </div>
                            <a href="backend/<?php echo htmlspecialchars($lesson['content']); ?>" target="_blank">

                                <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                            </a>
                        </div>
                        <div class="content">
                            <?php if (!empty($lesson['content'])): ?>
                                <iframe src="backend/<?php echo htmlspecialchars($lesson['content']); ?>" style="border: none;" allowfullscreen></iframe>
                            <?php else: ?>
                                <p>No content available for this lesson.</p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Resources Section -->
                        <div class="resources-section">
                            <h3 class="resources-title">Additional Resources</h3>
                            <?php
                            // Fetch resources for this lesson
                            $resources_sql = "SELECT id, title, url
                                            FROM lesson_links
                                            WHERE lesson_id = ? 
                                            ORDER BY id ASC";
                            $resources_stmt = $conn->prepare($resources_sql);
                            $resources_stmt->bind_param("i", $lesson['id']); // Changed from lesson['lesson_id'] to lesson['id']
                            $resources_stmt->execute();
                            $resources_result = $resources_stmt->get_result();
                            
                            if ($resources_result->num_rows > 0):
                            ?>
                            <table class="resources-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Link</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($resource = $resources_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($resource['title']); ?></td>
                                        <td><a href="<?php echo htmlspecialchars($resource['url']); ?>" target="_blank" class="resource-link">View Resource</a></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                                <p>No additional resources available for this lesson.</p>
                            <?php endif; ?>
                        </div>
                        <button>Done</button>
                       
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="information">
                    <div class="title">
                        <div class="name">
                            <h3>No Content Available</h3>
                        </div>
                    </div>
                    <div class="content">
                        <p>There are no lessons available for this course yet. Please check back later.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <script>
        // Function to handle notification display
        function openNotification() {
            document.getElementById('notice').style.display = 'block';
        }
        
        function closeNotification() {
            document.getElementById('notice').style.display = 'none';
        }
        
        // Function to handle logout menu
        function openLogout() {
            document.getElementById('logout').style.display = 'block';
        }
        
        function closeLogout() {
            document.getElementById('logout').style.display = 'none';
        }
        
        // Function to handle lesson switching
        document.addEventListener('DOMContentLoaded', function() {
            const lessonItems = document.querySelectorAll('.lesson');
            
            lessonItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    // Remove active class from all lessons
                    lessonItems.forEach(function(l) {
                        l.classList.remove('active');
                    });
                    
                    // Add active class to clicked lesson
                    this.classList.add('active');
                    
                    // Hide all lesson content
                    const allLessonContent = document.querySelectorAll('.information');
                    allLessonContent.forEach(function(content) {
                        content.classList.add('hide-content');
                    });
                    
                    // Show the selected lesson content
                    const lessonId = this.getAttribute('data-lesson-id');
                    const lessonContent = document.getElementById('lesson-' + lessonId);
                    if (lessonContent) {
                        lessonContent.classList.remove('hide-content');
                    }
                });
            });
            
        
    </script>
</body>
</html>
<?php
// Close database connection
$conn->close();
?>
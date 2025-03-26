<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    // Redirect to login page if not logged in
    header("Location: indexds.php");
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
    <!-- <link rel="stylesheet" href="../CSS/slessons.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=close" />
    <style>
        * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: poppins, sans-serif;
        }

        :root {
        --body--: rgb(71, 128, 95);
        --button--: rgb(243, 156, 69);
        --text--: #fff;
        --black--: #000;
        }

        /* ------------------------------------- HEADER ------------------------------------- */

        header {
        position: fixed;
        top: 0;
        width: 100%;
        height: 60px;
        /* box-shadow: 0 0 10px #ccc; */
        background: var(--text--);
        z-index: 1;
        }

        nav {
        width: 100%;
        height: 60px;
        background: var(--body--);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 3em;
        }

        nav .logo {
        width: 4em;
        height: 4em;
        }

        nav .logo img {
        width: 100%;
        height: 100%;
        }

        nav .search {
        position: relative;
        width: 30%;
        height: 2.2em;
        border-radius: 1.4em;
        background: var(--text--);
        /* padding: 1em; */
        }

        nav .search i {
        position: absolute;
        height: 100%;
        right: 10px;
        font-size: 18px;
        display: flex;
        align-items: center;
        cursor: pointer;
        }

        nav .search input {
        width: 100%;
        height: 100%;
        padding: 0 2.4em 0 1.3em;
        border: none;
        outline: none;
        background: var(--text--);
        border-radius: 1.4em;
        }

        nav .search input::placeholder {
        text-align: center;
        align-items: center;
        font-size: 15px;
        color: #000;
        }

        nav .profile {
        display: flex;
        align-items: center;
        gap: 2em;
        }

        nav .profile .notifications i {
        color: var(--text--);
        font-size: 1.4em;
        cursor: pointer;
        }

        nav .profile .notifications .notice {
        position: absolute;
        width: 30em;
        height: 100vh;
        background: var(--text--);
        box-shadow: 0 0 10px var(--body--);
        top: 3.7em;
        right: -1000px;
        overflow: auto;
        transition: right 0.5s ease;
        z-index: 3;
        }

        nav .profile .notifications .notice.openNotification {
        right: 0;
        }

        nav .profile .notifications .notice .head {
        position: fixed;
        width: 30em;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1em;
        color: var(--text--);
        background: var(--body--);
        }

        nav .profile .notifications .notice .head h3 {
        font-size: 20px;
        }

        nav .profile .notifications .notice .head i {
        font-size: 2em;
        }

        nav .profile .notifications .notice .info {
        margin-top: 60px;
        padding: 10px;
        display: grid;
        gap: 6px;
        grid-template-columns: 1fr;
        }

        nav .profile .notifications .notice .info .card {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 0 4px;
        background: var(--text--);
        border-radius: 4px;
        box-shadow: 0 0 10px #ccc;
        border-left: 5px solid var(--body--);
        cursor: pointer;
        }

        nav .profile .notifications .notice .info .card h4 {
        font-size: 16px;
        }

        nav .profile .notifications .notice .info .card .user img {
        width: 2em;
        height: 2em;
        }

        nav .profile .user .profile img {
        width: 2em;
        height: 2em;
        border-radius: 50%;
        cursor: pointer;
        }

        nav .profile .user .profile .info {
        position: absolute;
        width: 20em;
        min-height: 100vh;
        background: var(--text--);
        box-shadow: 0 0 10px var(--body--);
        top: 3.7em;
        right: -1000px;
        overflow: auto;
        transition: right 0.5s ease;
        }

        nav .profile .user .profile .info.openLogout {
        right: 0;
        }

        nav .profile .user .profile .info .head .personInfo {
        position: fixed;
        width: 20em;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1em;
        color: var(--text--);
        background: var(--body--);
        }

        nav .profile .user .profile .info .head .personInfo .left {
        display: flex;
        gap: 10px;
        align-items: center;
        }

        nav .profile .user .profile .info .head .personInfo .left img {
        width: 3em;
        height: 3em;
        }

        nav .profile .user .profile .info .head .personInfo i {
        font-size: 2em;
        cursor: pointer;
        }

        nav .profile .user .profile .info .logout {
        margin-top: 60px;
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        }

        nav .profile .user .profile .info .logout .myProfile {
        padding: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        border-radius: 3px;
        }

        nav .profile .user .profile .info .logout .myProfile:hover {
        background: #f7f5f5;
        }

        /* ------------------------------------------RIBBON------------------------------------------------*/

        .ribbon {
        width: 100%;
        height: 60px;
        display: flex;
        align-items: center;
        /* align-items: center;
        gap: 1.8em;
        padding: 0 5em;
        font-size: 1.2em; */
        }

        .ribbon .course-title{
            width: 25%;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 10px;
            box-shadow: 0 2px 4px #ccc;
            border-right: 2px solid #ccc;
        }

        .ribbon .course-title a {
            text-decoration: none;
            color: var(--black--);
            font-size: 1.2em;
            cursor: pointer;
        }

        .ribbon .course-title h3 {
            font-size: 1.2em;
            color: var(--body--);
        }

        .ribbon .title {
            width: 75%;
            height: 100%;
            padding: 0 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--text--);
            box-shadow: 0 2px 4px #ccc;
        }

        .ribbon .title button {
            background: var(--body--);
            color: var(--text--);
            border: none;
            outline: none;
            padding: 10px;
            cursor: pointer;
        }

        /* .ribbon a {
        text-decoration: none;
        color: #000;
        }

        .ribbon p {
        padding: 8px;
        cursor: pointer;
        }

        .ribbon p:hover {
        background: #f7f5f5;
        }

        .ribbon .active {
        border-bottom: 2px solid var(--body--);
        } */

        /* ------------------------------------------Main------------------------------------------------*/

        main {
            width: 100%;
            position: relative;
            margin-top: 13px;
            display: grid;
            grid-template-columns: 25% 1fr;
        }

        .sidebar {
            position: fixed;
            width: 25%;
            left: 0;
            height: calc(100vh - 72px);
            border-right: 2px solid #ccc;
            overflow-y: auto;
        }

        .sidebar .course-title {
        width: 100%;
        height: auto;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 1.2em 10px;
        box-shadow: 0 2px 4px #ccc;
        border-right: 1px solid #ccc;
        }

        .sidebar .course-title a {
        text-decoration: none;
        color: var(--black--);
        font-size: 1.2em;
        cursor: pointer;
        }

        .sidebar .course-title h3 {
        font-size: 1.2em;
        color: var(--body--);
        }

        .sidebar .lessons {
        width: 100%;
        height: 80%;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 5px;
        border-right: 1px solid #ccc;
        }

        .sidebar .lessons .lesson {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 10px;
        font-size: 1.1em;
        cursor: pointer;
        transition: background 0.3s ease, border 0.3s ease;
        }

        .sidebar .lessons .lesson.active {
        background: #eeeeee;
        border-left: 4px solid var(--body--);
        }

        .sidebar .lessons .lesson:hover {
        background: #eeeeee;
        }

        .lesson-content {
            width: 1010px;
            margin-left: 340px;
        }

        .lesson-content .information {
            display: block;
            width: 100%;
            transition: display 0.2s ease;
        }

        .lesson-content .information.hide-content {
        display: none;
        }

        /* .lesson-content .information .title { */
        /* position: fixed; */
        /* width: 75%;
        height: 67px;
        padding: 6px 1.5em;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--text--);
        box-shadow: 0 2px 4px #ccc;
        } */

        /* .lesson-content .information .title button {
        background: var(--body--);
        color: var(--text--);
        border: none;
        outline: none;
        padding: 10px;
        cursor: pointer;
        } */

        .lesson-content .information .content {
            width: 100%;
        }

        .lesson-content .information iframe {
            width: 100%;
            height: 1000px;
        }

        .quiz-container {
            width: 80%;
            margin: 0 auto;
        }

        .quiz-container h2 {
            text-align: center;
            color: var(--body--);
            padding: 10px;
        }

        .question {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }

        .question p {
            font-weight: bold;
        }

        label {
            display: block;
            padding: 10px 0;
        }

        input[type="radio"] {
            background: var(--body--);
            margin-right: 5px;
        }

        form button {
            padding: 8px 15px;
            border: none;
            outline: none;
            background: var(--body--);
            color: var(--text--);
            cursor: pointer;
            margin-bottom: 8px;
        }

        /* .lesson-content .information .btn{
            position: fixed;
            width: 64em;
            height: 60px;
            bottom: 0;
            border-top: 1px solid #ccc;
            display: flex;
            align-items: center;
            gap: 1.5em;
            justify-content: flex-end;
            padding: 0 1em;
        }

        .lesson-content .information .btn button{
            padding: 6px 1em;
            border: none;
            outline: none;
            cursor: pointer;
            border-radius: 4px;
            color: var(--text--);
            font-size: 1em;
        }

        .lesson-content .information .btn button.active{
            background: var(--body--);
        } */

        /*----------------------------------------- RESPONSIVE DESIGN --------------------------------------------*/

        @media (max-width: 960px) {
        }

        @media (max-width: 768px) {
        nav .search {
            width: 40%;
        }

        main {
            padding: 0 3em;
        }
        }

        @media (min-width: 451px) and (max-width: 550px) {
        nav .search input::placeholder {
            font-size: 12px;
        }

        nav .profile .notifications .notice {
            width: 100%;
        }

        nav .profile .notifications .notice .head {
            width: 100%;
        }

        nav .profile .user .profile .info {
            width: 100%;
        }

        nav .profile .user .profile .info .head .personInfo {
            width: 100%;
        }
        }

        @media (max-width: 450px) {
        nav .profile .notifications .notice {
            width: 100%;
        }

        nav .profile .notifications .notice .head {
            width: 100%;
        }

        nav .profile .user .profile .info {
            width: 100%;
        }

        nav .profile .user .profile .info .head .personInfo {
            width: 100%;
        }

        nav .search {
            display: none;
        }

        main {
            padding: 0 1em;
        }
        }

        /* -------------------------------------------------RESOURCES--------------------------------------------------------- */

        .resources-section{
            padding: 10px;
        }

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

        /* ----------------------------------- COMPLETE --------------------------------------- */

        .complete{
            padding: 10px;
            display: flex;
            justify-content: flex-end;
        }

        .complete button{
            padding: 10px 15px;
            background: var(--body--);
            color: #fff;
            border: none;
            outline: none;
            border-radius: 5px;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <header>
        <!-- <nav>
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
        </nav> -->
        <div class="ribbon">
            <div class="course-title">
                <a href="myCourse.php"><i class="fa-solid fa-arrow-left"></i></a>
                <h3>Course Name</h3>
            </div>
            <div class="title">
                <div class="name">
                    <h3>Lesson Name</h3>
                    <p>Lesson 1</p>
                </div>
                <!-- <a href="" target="_blank">

                    <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                </a> -->
            </div>
        </div>
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
                        <div class="complete">
                            <button class="complete">Mark as complete</button>
                        </div>
                    
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
    <script src="../JS/slessons.js"></script>
    <script>
        let notice = document.getElementById("notice");
        let logout = document.getElementById("logout");
        // Function to handle notification display
        function openNotification(){
            logout.classList.remove("openLogout");
            notice.classList.toggle("openNotification");
        }

        function closeNotification(){
            notice.classList.remove("openNotification");
        }
        
        // Function to handle logout menu
        function openLogout(){
            notice.classList.remove("openNotification");
            logout.classList.toggle("openLogout");
        }

        function closeLogout(){
            logout.classList.remove("openLogout");
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
        })
        
    </script>
</body>
</html>
<?php
// Close database connection
$conn->close();
?>
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
    <style>

        :root {
        --base-clr: #11121a;
        --line-clr: #42434a;
        --hover-clr: #222533;
        --text-clr: #e6e6ef;
        --accent-clr: rgb(75, 139, 102);
        --secondary-text-clr: #b0b3b1;
        }
        /* -------------------------------------Profile Popup Styles------------------------------ */
        .profile-popup-overlay {
            position: fixed;
            top: 0;
            right: -1000px;
            width: 400px;
            height: 100%;
            background-color: white;
            box-shadow: -4px 0 15px rgba(0,0,0,0.1);
            transition: right 0.3s ease-in-out;
            z-index: 1000;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .profile-popup-overlay .content{
            padding: 30px;
        }

        .profile-popup-overlay.active {
            right: 0;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--accent-clr);
            padding: 10px;
        }

        .popup-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #fff;
        }

        .close-popup {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #fff;
        }

        /* ------------------------------------- */

        .profile-image-container {
            position: relative;
            width: 70px;
            height: 70px;
            margin: 0 auto 10px;
            border-radius: 50%;
            overflow: hidden;
            /* group: all; */
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: filter 0.3s ease;
        }

        .image-update-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .profile-image-container:hover .image-update-overlay {
            background-color: rgba(0,0,0,0.5);
            opacity: 1;
        }

        .profile-image-container:hover img {
            filter: brightness(0.7);
        }

        .image-update-overlay i {
            color: white;
            font-size: 2rem;
            transform: scale(0.7);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .profile-image-container:hover .image-update-overlay i {
            transform: scale(1);
            opacity: 1;
        }

        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .password-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }

        .password-section h3{
            margin-bottom: 10px;
        }

        .save-changes {
            background-color: var(--accent-clr);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .save-changes:hover {
            background-color: var(--accent-clr);
        }

        /* Overlay to block interaction with background */
        .popup-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: none;
            z-index: 999;
        }

        .popup-backdrop.active {
            display: block;
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
                        <img src="<?php echo !empty($user_data['profile_image']) ? '../uploads/profiles/' . $user_data['profile_image'] : '../IMG/profile.png'; ?>" onclick="openLogout()">
                        <div class="info" id="logout">
                            <div class="head">
                                <div class="personInfo">
                                    <div class="left">
                                        <img src="<?php echo !empty($user_data['profile_image']) ? '../uploads/profiles/' . $user_data['profile_image'] : '../IMG/profile.png'; ?>">
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
                                    <p><a href="logout.php" style="text-decoration: none; color: #000;">Logout</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Backdrop for popup -->
                <div class="popup-backdrop" id="popupBackdrop"></div>

                <!-- Profile Popup -->
                <div class="profile-popup-overlay" id="profilePopup">
                    <div class="popup-header">
                        <h2>My Profile</h2>
                        <button class="close-popup" id="closeProfilePopup">&times;</button>
                    </div>
                    <div class="content">
                        <div class="profile-image-container">
                            <img src="<?php echo !empty($user_data['profile_image']) ? '../uploads/profiles/' . $user_data['profile_image'] : '../IMG/profile.png'; ?>" alt="Profile Picture" id="profileImage">
                            <div class="image-update-overlay" onclick="document.getElementById('imageUpload').click()">
                                <i class="fa-solid fa-camera"></i>
                                <input type="file" id="imageUpload" style="display:none;" accept="image/*">
                            </div>
                        </div>

                        <form class="profile-form">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" name="firstName" required>
                            </div>

                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="lastName" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>

                            <div class="password-section">
                                <h3>Change Password</h3>
                                <div class="form-group">
                                    <label for="currentPassword">Current Password</label>
                                    <input type="password" id="currentPassword" name="currentPassword">
                                </div>

                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" id="newPassword" name="newPassword">
                                </div>

                                <div class="form-group">
                                    <label for="confirmPassword">Confirm New Password</label>
                                    <input type="password" id="confirmPassword" name="confirmPassword">
                                </div>
                            </div>

                            <button type="submit" class="save-changes">Save Changes</button>
                        </form>
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
                        <a href="lesson.php?course_id=<?php echo htmlspecialchars($course['id']); ?>">

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
    <script>
        let notice = document.getElementById("notice");
        let logout = document.getElementById("logout");

        function openNotification(){
            logout.classList.remove("openLogout");
            notice.classList.toggle("openNotification");
        }

        function closeNotification(){
            notice.classList.remove("openNotification");
        }

        function openLogout(){
            notice.classList.remove("openNotification");
            logout.classList.toggle("openLogout");
        }

        function closeLogout(){
            logout.classList.remove("openLogout");
        }

        function searchCourse(){
            let input = document.getElementById("search");
            let upperCase = input.value.toUpperCase();
            let allCourses = document.getElementById("allCourses");
            let course = document.getElementsByClassName("course");
            for( let i = 0; i < course.length; i++){
                let courseTitle = course[i].getElementsByTagName("h3")[0];
                let title = courseTitle.textContent || courseTitle.innerText;
                if(title.toUpperCase().indexOf(upperCase) > -1){
                    course[i].style.display = "";
                }
                else{
                    course[i].style.display = "none";
                }
            }
        }

        // ------------------------------------ PROFILE POPUP ----------------------------------------------

        document.addEventListener('DOMContentLoaded', () => {
        const profileTrigger = document.querySelector('.myProfile');
        const profilePopup = document.getElementById('profilePopup');
        const popupBackdrop = document.getElementById('popupBackdrop');
        const closeProfilePopup = document.getElementById('closeProfilePopup');

        // Open popup
        profileTrigger.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent event from bubbling
            profilePopup.classList.add('active');
            popupBackdrop.classList.add('active');
            logout.classList.remove("openLogout");
        });

        // Close popup
        closeProfilePopup.addEventListener('click', () => {
            profilePopup.classList.remove('active');
            popupBackdrop.classList.remove('active');
        });

        // Close popup when clicking backdrop
        popupBackdrop.addEventListener('click', () => {
            profilePopup.classList.remove('active');
            popupBackdrop.classList.remove('active');
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const imageUpload = document.getElementById('imageUpload');
        const profileImage = document.getElementById('profileImage');

        imageUpload.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
    </script>
</body>
</html>
<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Library</title>
    <link rel="stylesheet" href="../CSS/courseLibrary.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=close" />
    
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="myCourse.html">
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
                                            <h4>Name</h3>
                                            <p>Username</p>
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
                                    <p>Logout</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="ribbon">
            <a href="myCourse.html"><p>My Courses</p></a>
            <a href="courseLibrary.html"><p class="active">Course Library</p></a>
        </div>
    </header>
    <main>
        <div class="allCourses" id="allCourses">
            <h2> All Courses (10) </h2>
            <div class="courses">
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course1.jpeg" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To HTML</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img" style="background-image: url(../IMG/course1.jpeg);"></div>
                            <h3>Introduction To HTML</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button id="btn2" class="btn1">Cancel</button>
                            <button class="btn2"><a href="unit1.html">Register</a></button>
                        </div>
                    </div>
                </div>
                <!-- <div class="course">
                    <div class="img">
                        <img src="../IMG/course2.jpeg" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To CSS</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img" style="background-image: url(../IMG/course2.jpeg);"></div>
                            <h3>Introduction To CSS</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course3.avif" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To JS</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img" style="background-image: url(../IMG/course3.avif);"></div>
                            <h3>Introduction To JS</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course1.jpeg" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To HTML</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img" style="background-image: url(../IMG/course1.jpeg);"></div>
                            <h3>Introduction To HTML</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course3.avif" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To CSS</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img">
                                
                            </div>
                            <h3>Introduction To HTML</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course3.avif" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To JS</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img">
                                
                            </div>
                            <h3>Introduction To HTML</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course3.avif" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To HTML</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img">
                                
                            </div>
                            <h3>Introduction To HTML</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course3.avif" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To CSS</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img">
                                
                            </div>
                            <h3>Introduction To HTML</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course3.avif" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To JS</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img">
                                
                            </div>
                            <h3>Introduction To HTML</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="course">
                    <div class="img">
                        <img src="../IMG/course3.avif" alt="">
                    </div>
                    <div class="content">
                        <div class="lessons">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>8 Lessons</p>
                        </div>
                        <div class="name">
                            <h3>Introduction To JS</h3>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="info">
                            <div class="img">
                                
                            </div>
                            <h3>Introduction To HTML</h3>
                            <div class="lessons">
                                <p>01. Lesson 01 name</p>
                                <p>02. Lesson 02 name</p>
                                <p>03. Lesson 03 name</p>
                                <p>04. Lesson 04 name</p>
                                <p>05. Lesson 05 name</p>
                                <p>06. Lesson 06 name</p>
                                <p>07. Lesson 07 name</p>
                                <p>08. Lesson 08 name</p>
                                <p>09. Lesson 09 name</p>
                                <p>10. Lesson 10 name</p>
                                <p>11. Lesson 11 name</p>
                                <p>12. Lesson 12 name</p>
                                <p>13. Lesson 13 name</p>
                                <p>14. Lesson 14 name</p>
                                <p>15. Lesson 15 name</p>
                                <p>16. Lesson 16 name</p>
                                <p>17. Lesson 17 name</p>
                            </div>
                        </div>
                        <div class="btn">
                            <button class="btn1">Cancel</button>
                            <button class="btn2">Register</button>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </main>
    <footer>
        <p>Powered by ASYV IT Department</p>
    </footer>
    <script>

let notice = document.getElementById("notice");
let logout = document.getElementById("logout");
let course = document.querySelector(".course");
let courseDetails = document.querySelector(".course-details");
let cancelBtn = document.querySelector(".btn1");

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

course.addEventListener("click", () =>{
    courseDetails.style.display = "block";
})

// for(let i = 0; i < course.length; i++){
//     course[i].addEventListener("click", () =>{
//         courseDetails[i].classList.add("open-popup");
//     })
// }

document.getElementById('btn2').addEventListener("click", () =>{
    alert('hell')
    courseDetails.style.display = "none";
})

// for(let i = 0; i < cancelBtn.length; i++){
//     cancelBtn.addEventListener("click", () =>{
//         courseDetails.classList.remove("open-popup");
//         console.log(courseDetails[i]);
//     })
// }

    </script>
    <!-- <script src="../JS/courseLibrary.js"></script> -->
</body>
</html>
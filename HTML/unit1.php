<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduction to HTML</title>
    <link rel="stylesheet" href="../CSS/unit1.css">
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
    </header>
    <main>
        <div class="sidebar">
            <div class="course-title">
                <a href="myCourse.html"><i class="fa-solid fa-arrow-left"></i></a>
                <h3>Introduction to HTML && CSS</h3>
            </div>
            <div class="lessons">
                <div class="lesson active">
                    <p>01</p>
                    <p>Introduction to Web Development & Setup</p>
                </div>
                <div class="lesson">
                    <p>02</p>
                    <p>HTML Structure and Elements</p>
                </div>
                <div class="lesson">
                    <p>03</p>
                    <p>Flexbox Layout Design</p>
                </div>
                <div class="lesson">
                    <p>04</p>
                    <p>Lesson name</p>
                </div>
                <div class="lesson">
                    <p>05</p>
                    <p>Lesson name</p>
                </div>
                <div class="lesson">
                    <p>06</p>
                    <p>Lesson name</p>
                </div>
                <div class="lesson">
                    <p>07</p>
                    <p>Lesson name</p>
                </div>
            </div>
        </div>
        <div class="lesson-content">
            <div class="information">
                <div class="title">
                    <div class="name">
                        <h3>Introduction to Web Development & Setup</h3>
                        <p>Lesson 1</p>
                    </div>
                    <a href="../lessons/Introduction to HTML && CSS/Lesson 1.pdf" target="_blank">
                        <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                    </a>
                </div>
                <div class="content">
                    <iframe src="../lessons/Introduction to HTML && CSS/Lesson 1.pdf" style="border: none;" allowfullscreen></iframe>
                    <div class="quiz-container">
                        <h2>Multitple Choice Quiz</h2>
                        <hr>
                        <form action="">
                            <div class="container">
                                <div class="question">
                                    <p> 1. What is the primary role of HTML in web development?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            To add interactivity to web pages
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            To structure the content of a webpage
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            To control the appearance of a webpage
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            To store and manage data
                                        </label>
                                    </div>
                                </div>
                                <div class="question">
                                    <p> 2. Which of the following best describes the difference between frontend and backend development?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            Frontend handles data storage, while backend controls the UI
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            Frontend is for user interactions, while backend processes data and logic
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            Frontend is only about JavaScript, while backend is only about HTML and CSS
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            Frontend developers work with servers, while backend developers work with browsers
                                        </label>
                                    </div>
                                </div>
                                <div class="question">
                                    <p> 3. What is the function of CSS in web development?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            To add styles such as colors, fonts, and layouts
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            To structure the webpage content
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            To process form submissions
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            To create databases for websites
                                        </label>
                                    </div>
                                </div>
                                <div class="question">
                                    <p> 4. Which of the following technologies is used for backend development?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            HTML
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            CSS
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            Python
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            Emmet
                                        </label>
                                    </div>
                                </div>
                                <div class="question">
                                    <p> 5. What is the purpose of the Live Server extension in VS Code?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            To install new programming languages
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            To format HTML and CSS code
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            To automatically refresh the browser when changes are made
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            To store code files on the cloud
                                        </label>
                                    </div>
                                </div>
                                <button type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="btn">
                    <button> <i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <button class="active">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div> -->
            </div>
            <div class="information hide-content">
                <div class="title">
                    <div class="name">
                        <h3>HTML Structure and Elements</h3>
                        <p>Lesson 2</p>
                    </div>
                    <a href="../lessons/Introduction to HTML && CSS/Lesson2.pdf" target="_blank">
                        <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                    </a>
                </div>
                <div class="content">
                    <iframe src="../lessons/Introduction to HTML && CSS/Lesson2.pdf" style="border: none;" allowfullscreen></iframe>
                    <div class="quiz-container">
                        <h2>Multiple Choice Quiz</h2>
                        <hr>
                        <form action="">
                            <div class="container">
                                <div class="question">
                                    <p> 1. What is the primary role of HTML in web development?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            To add interactivity to web pages
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            To structure the content of a webpage
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            To control the appearance of a webpage
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            To store and manage data
                                        </label>
                                    </div>
                                </div>
                                <div class="question">
                                    <p> 2. Which of the following best describes the difference between frontend and backend development?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            Frontend handles data storage, while backend controls the UI
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            Frontend is for user interactions, while backend processes data and logic
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            Frontend is only about JavaScript, while backend is only about HTML and CSS
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            Frontend developers work with servers, while backend developers work with browsers
                                        </label>
                                    </div>
                                </div>
                                <div class="question">
                                    <p> 3. What is the function of CSS in web development?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            To add styles such as colors, fonts, and layouts
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            To structure the webpage content
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            To process form submissions
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            To create databases for websites
                                        </label>
                                    </div>
                                </div>
                                <div class="question">
                                    <p> 4. Which of the following technologies is used for backend development?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            HTML
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            CSS
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            Python
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            Emmet
                                        </label>
                                    </div>
                                </div>
                                <div class="question">
                                    <p> 5. What is the purpose of the Live Server extension in VS Code?</p>
                                    <div class="answers">
                                        <label for="">
                                            <input type="radio" name="q1" value="a">
                                            To install new programming languages
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="b">
                                            To format HTML and CSS code
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="c">
                                            To automatically refresh the browser when changes are made
                                        </label>
                                        <label for="">
                                            <input type="radio" name="q1" value="d">
                                            To store code files on the cloud
                                        </label>
                                    </div>
                                </div>
                                <button type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="btn">
                    <button> <i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <button class="active">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div> -->
            </div>
            <div class="information hide-content">
                <div class="title">
                    <div class="name">
                        <h3></h3>
                        <p>Lesson 3</p>
                    </div>
                    <a href="../lessons/Introduction to HTML && CSS/Lesson 1.pdf" target="_blank">
                        <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                    </a>
                </div>
                <div class="body">
                    Lorem, ipsum dolor.
                </div>
                <!-- <div class="btn">
                    <button> <i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <button class="active">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div> -->
            </div>
            <div class="information hide-content">
                <div class="title">
                    <div class="name">
                        <h3>Building a Simple Webpage with HTML & CSS</h3>
                        <p>Lesson</p>
                    </div>
                    <a href="../lessons/Introduction to HTML && CSS/Lesson 1.pdf" target="_blank">
                        <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                    </a>
                </div>
                <div class="body">
                    Lorem ipsum dolor sit.
                </div>
                <!-- <div class="btn">
                    <button> <i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <button class="active">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div> -->
            </div>
            <div class="information hide-content">
                <div class="title">
                    <div class="name">
                        <h3>Building a Simple Webpage with HTML & CSS</h3>
                        <p>Lesson</p>
                    </div>
                    <a href="../lessons/Introduction to HTML && CSS/Lesson 1.pdf" target="_blank">
                        <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                    </a>
                </div>
                <div class="body">
                    Lorem ipsum dolor sit amet.
                </div>
                <!-- <div class="btn">
                    <button> <i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <button class="active">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div> -->
            </div>
            <div class="information hide-content">
                <div class="title">
                    <div class="name">
                        <h3>Building a Simple Webpage with HTML & CSS</h3>
                        <p>Lesson</p>
                    </div>
                    <a href="../lessons/Introduction to HTML && CSS/Lesson 1.pdf" target="_blank">
                        <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                    </a>
                </div>
                <div class="body">
                    Lorem ipsum dolor sit amet consectetur.
                </div>
                <!-- <div class="btn">
                    <button> <i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <button class="active">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div> -->
            </div>
            <div class="information hide-content">
                <div class="title">
                    <div class="name">
                        <h3>Building a Simple Webpage with HTML & CSS</h3>
                        <p>Lesson</p>
                    </div>
                    <a href="../lessons/Introduction to HTML && CSS/Lesson 1.pdf" target="_blank">
                        <button><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Full Screen</button>
                    </a>
                </div>
                <div class="body">
                    Lorem ipsum dolor sit amet consectetur adipisicing.
                </div>
                
                <!-- <div class="btn">
                    <button> <i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <button class="active">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div> -->
            </div>
        </div>
    </main>
    
    <script src="../JS/unit1.js"></script>
</body>
</html>
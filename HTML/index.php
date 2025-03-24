<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASYV-CODING</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: poppins, sans-serif;
        scroll-behavior: smooth;
      }

      :root {
        --body--: rgb(71, 128, 95);
        --button--: rgb(243, 156, 69);
        --text--: #fff;
      }

      body {
        width: 100%;
        overflow-y: none;
        position: relative;
      }

      .hero {
        max-width: 100%;
        min-height: 100vh;
        background: var(--body--);
      }

      header {
        position: fixed;
        min-width: 100%;
        height: 100px;
        background: var(--body--);
        display: flex;
        justify-content: center;
        padding: 1em 0;
        z-index: 1000;
      }

      nav {
        width: 60%;
        height: 100%;
        background-color: var(--text--);
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.2em;
        border-radius: 2em;
      }

      nav .logo {
        width: 4em;
        height: 4em;
        cursor: pointer;
      }

      nav .logo img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
      }

      nav .links ul {
        list-style: none;
      }

      nav .links ul li {
        display: inline-block;
        padding: 0 10px;
        font-size: 1em;
      }

      nav .links ul li a {
        text-decoration: none;
        color: var(--body--);
      }

      nav .btn button {
        padding: 10px;
        background: var(--button--);
        border: none;
        outline: none;
        border-radius: 10px;
        color: var(--text--);
        font-weight: 550;
        cursor: pointer;
      }

      .menu {
        display: none;
        cursor: pointer;
      }

      .menu i {
        font-size: 2em;
        color: var(--body--);
      }

      .navLinks {
        position: fixed;
        background: var(--body--);
        width: 200px;
        height: 100%;
        top: 0;
        right: -1000px;
        transition: right 0.5s ease;
      }

      .navLinks.openNavLinks {
        right: 0;
      }

      .navLinks i {
        position: absolute;
        right: 0;
        font-size: 2em;
        color: var(--text--);
        padding: 10px 1em 0 0;
        cursor: pointer;
      }

      .navLinks ul {
        list-style: none;
        padding: 4em 0 0 2em;
      }

      .navLinks ul li {
        padding: 10px 0;
      }

      .navLinks ul li a {
        text-decoration: none;
        color: var(--text--);
      }

      .container {
        height: 100%;
        padding: 100px 2em 0;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        color: var(--text--);
      }

      .container .content {

        padding: 2em;
      }

      .container .content h1 {
        font-family: poppins SemiBold, poppins, sans-serif;
        font-size: 60px;
        font-weight: bold;
        line-height: 70px;
        padding-bottom: 0.6em;
      }

      .container .content h1 span {
        color: var(--button--);
      }

      .container .content p {
        font-weight: 400;
        line-height: 30px;
      }

      .container .content .btn button {
        margin-top: 1em;
        padding: 10px 20px;
        background: var(--button--);
        border: none;
        outline: none;
        border-radius: 10px;
        color: var(--text--);
        font-weight: 500;
        font-size: 15px;
        cursor: pointer;
      }

      .container .box-img img {
        width: 30em;
      }

      /************************************************ LOGIN FORM *******************************************/

      .container .login-form {
        width: 400px;
        height: 400px;
        background-color: var(--button--);
        position: fixed;
        top: 0%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.1);
        border-radius: 15px;
        transition: transform 0.4s, top 0.4s;
        visibility: hidden;
      }

      .container .login-form.open-popup {
        top: 50%;
        transform: translate(-50%, -50%) scale(1);
        visibility: visible;
      }

      .container .login-form i {
        color: var(--body--);
        padding: 10px 10px 0 10px;
        cursor: pointer;
      }

      .container .login-form h1 {
        text-align: center;
        padding: 10px 0;
        font-size: 30px;
        color: var(--body--);
      }

      #error-message {
          width: 80%;
          margin: 5px 35px;
          padding: 10px;
          background-color: #ffebee;
          border-left: 4px solid #f44336;
          border-radius: 4px;
          color: #721c24;
          margin-bottom: 15px;
          display: none;
          align-items: center;
          max-width: 400px;
          font-size: 14px;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      }

      .container .login-form .input-group input {
        width: 80%;
        margin: 5px 35px;
        padding: 10px 20px;
        border: none;
        outline: none;
        color: #000;
        font-size: 18px;
        border-radius: 50px;
      }

      .container .login-form .checkbox {
        margin: 5px 40px;
      }

      .container .login-form .checkbox input {
        border: none;
        outline: none;
        size: 30px;
        cursor: pointer;
      }

      .container .login-form .checkbox label {
        font-size: 16px;
      }

      .container .login-form button {
        width: 80%;
        margin: 5px 35px;
        padding: 10px 20px;
        border: none;
        outline: none;
        color: var(--body--);
        background: #fff;
        font-size: 18px;
        font-weight: bold;
        border-radius: 50px;
        transition: 0.3s ease-in-out;
      }

      .container .login-form button:hover {
        box-shadow: 0 0 20px #278370;
        cursor: pointer;
      }

      /************************************** CREATE ACCOUNT FORM *******************************************/

      .container .createaccount-form {
        width: 500px;
        height: 450px;
        background-color: var(--button--);
        position: fixed;
        top: 0%;
        left: 50%;
        border-radius: 15px;
        transform: translate(-50%, -50%) scale(0.1);
        visibility: hidden;
        transition: transform 0.4s, top 0.4s;
      }

      .container .createaccount-form.create-popup {
        top: 55%;
        transform: translate(-50%, -50%) scale(1);
        visibility: visible;
      }

      .container .createaccount-form i {
        color: var(--body--);
        padding: 10px;
        cursor: pointer;
      }

      .container .createaccount-form h1 {
        color: var(--body--);
        margin: 5px 50px;
        font-size: 26px;
      }

      .container .createaccount-form form {
        margin: 10px 50px;
      }

      .container .createaccount-form form .first-last {
        display: flex;
        margin: 5px 0;
      }

      .container .createaccount-form form .first-last label {
        font-size: 18px;
        font-weight: 400;
      }

      .container .createaccount-form form .first-last label span {
        color: var(--body--);
      }

      .container .createaccount-form form .first-last input {
        border: none;
        outline: none;
        padding: 6px 15px;
        color: #000;
        border-radius: 15px;
      }

      .container .createaccount-form form .username {
        margin: 5px 0;
      }

      .container .createaccount-form form .username label {
        font-size: 18px;
        font-weight: 400;
      }

      .container .createaccount-form form .username label span {
        color: var(--body--);
      }

      .container .createaccount-form form .username select {
        width: 100%;
        border: none;
        outline: none;
        padding: 6px 15px;
        color: #000;
        border-radius: 16px;
      }

      .container .createaccount-form form .email {
        margin: 5px 0;
      }

      .container .createaccount-form form .email label {
        font-size: 18px;
        font-weight: 400;
      }

      .container .createaccount-form form .email label span {
        color: var(--body--);
      }

      .container .createaccount-form form .email input {
        width: 100%;
        border: none;
        outline: none;
        padding: 6px 15px;
        color: #000;
        border-radius: 15px;
      }

      .container .createaccount-form form .file {
        margin-top: 20px 0;
        font-size: 16px;
        font-weight: 500;
        width: 100%;
      }

      .container .createaccount-form form .passwords {
        width: 100%;
        display: flex;
        justify-content: space-between;
      }

      .container .createaccount-form form .passwords .password1 {
        margin-right: 12px;
      }
      .container .createaccount-form form .passwords label {
        font-size: 18px;
        font-weight: 400;
      }

      .container .createaccount-form form .passwords label span {
        color: var(--body--);
      }

      .container .createaccount-form form .passwords input {
        border: none;
        outline: none;
        padding: 6px 15px;
        color: #000;
        border-radius: 15px;
      }

      .container .createaccount-form form .btn {
        margin: 15px 0;
      }

      .container .createaccount-form form .btn button {
        width: 100%;
        padding: 2px 0;
        border-radius: 18px;
        background-color: #fff;
        color: var(--body--);
        font-size: 18px;
        font-weight: bold;
        border: none;
        outline: none;
      }

      .container .createaccount-form form .btn button:hover {
        box-shadow: 0 0 20px #278370;
        cursor: pointer;
      }

      @media (max-width: 820px) {
        nav .links {
          display: none;
        }

        .menu {
          display: block;
        }

        .container {
          display: flex;
          flex-direction: column;
        }

        .container .content h1 {
          font-size: 45px;
          line-height: 60px;
        }
      }

      @media (max-width: 600px) {
        .container .content h1 {
          font-size: 35px;
          line-height: 50px;
        }

        .container .content p {
          font-size: 16px;
        }

        .container .box-img img {
          width: 25em;
        }
      }

      @media (max-width: 480px) {
        nav {
          width: 90%;
        }

        .container .content {
          padding: 10px;
        }

        .container .content h1 {
          font-size: 30px;
        }

        .container .content p {
          font-size: 15px;
        }

        .container .box-img img {
          width: 20em;
        }

        /************************************************ LOGIN FORM *******************************************/

        .container .login-form {
          width: 340px;
        }

        .container .login-form.open-popup {
          top: 50%;
        }

        /************************************************ CREATE ACCOUNT FORM *******************************************/

        .container .createaccount-form {
          width: 340px;
          height: 550px;
        }

        .container .createaccount-form.create-popup {
          top: 50%;
        }

        .container .createaccount-form h1 {
          margin: 0 28px;
        }

        .container .createaccount-form form {
          margin: 0 2em;
        }

        .container .createaccount-form form .first-last {
          display: block;
          gap: 5px;
        }

        .container .createaccount-form form .first-last input {
          width: 100%;
          margin-bottom: 8px;
        }

        .container .createaccount-form form .email input {
          margin-bottom: 8px;
        }

        .container .createaccount-form form .passwords {
          display: block;
        }

        .container .createaccount-form form .passwords .password1 {
          margin-right: 0;
        }

        .container .createaccount-form form .passwords input {
          width: 100%;
          margin-bottom: 8px;
        }
      }

      /* ----------------------------------------COURSES--------------------------------------------- */
      .courses {
          padding: 100px 20px 60px;
          background-color: #f8f9fa;
      }

      .section-header {
          text-align: center;
          margin-bottom: 50px;
      }

      .section-header h2 {
          font-size: 2.5rem;
          margin-bottom: 15px;
      }

      .section-header h2 span {
          color: var(--button--); /* Use the same accent color as your hero section */
      }

      .section-header p {
          max-width: 700px;
          margin: 0 auto;
          color: #666;
          font-size: 1.1rem;
      }

      .courses-container {
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          gap: 30px;
          max-width: 1200px;
          margin: 0 auto;
      }

      .course-card {
          background-color: #fff;
          border-radius: 10px;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
          overflow: hidden;
          transition: transform 0.3s ease, box-shadow 0.3s ease;
          width: 340px;
      }

      .course-card:hover {
          transform: translateY(-10px);
          box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
      }

      .course-image {
          height: 200px;
          overflow: hidden;
      }

      .course-image img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          transition: transform 0.5s ease;
      }

      .course-card:hover .course-image img {
          transform: scale(1.1);
      }

      .course-content {
          padding: 25px;
      }

      .course-content h3 {
          font-size: 1.4rem;
          margin-bottom: 10px;
          color: #333;
      }

      .course-content p {
          color: #666;
          margin-bottom: 20px;
          line-height: 1.5;
      }

      .lessons-count {
          display: flex;
          align-items: center;
          margin-bottom: 15px;
          font-weight: 500;
      }

      .lessons-count i {
          color: var(--button--);
          margin-right: 10px;
      }

      .course-details {
          display: flex;
          justify-content: space-between;
          margin-bottom: 20px;
      }

      .detail {
          display: flex;
          align-items: center;
      }

      .detail i {
          color: var(--button--);
          margin-right: 8px;
      }

      .course-btn button {
          background-color: var(--button--);
          color: white;
          border: none;
          padding: 12px 25px;
          border-radius: 5px;
          font-weight: 600;
          cursor: pointer;
          width: 100%;
          transition: background-color 0.3s ease;
      }

      .course-btn button:hover {
          background-color: var(--button--);
      }

      /* Responsive styling */
      @media screen and (max-width: 768px) {
          .courses-container {
              flex-direction: column;
              align-items: center;
          }
          
          .course-card {
              width: 100%;
              max-width: 450px;
          }
      }

    </style>
</head>
<body>
    <section class="hero" id="hero">
        <header>
            <nav>
                <div class="logo">
                    <img src="../IMG/Designer.png" alt="">
                </div>
                <div class="links">
                    <ul>
                        <li><a href="#hero">Home</a></li>
                        <li><a href="">About Us</a></li>
                        <li><a href="#courses">Courses</a></li>
                    </ul>
                </div>
                <div class="btn">
                    <button onclick="openPopup()">Sign In</button>
                </div>
                <div class="menu">
                    <i class="fa-solid fa-bars" onclick="openNavLinks()"></i>
                </div>
            </nav>
        </header>
        <div class="navLinks" id="navLinks">
            <i class="fa-solid fa-xmark fa-3x" onclick="closeNavLinks()"></i>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="">About Us</a></li>
                <li><a href="">Courses</a></li>
            </ul>
        </div>
        <div class="container">
            <div class="content">
                <h1>Empower your<br> <span>future </span> through <br> web development!</h1>
                <p>Our platform serves as a gateway for ASYV students to explore, learn, and master web development.
                    By providing hands-on courses, real-world projects, and a supportive community, we empower young innovators with the skills they need to shape the digital future and create opportunities beyond the classroom.</p>
                <!-- <div class="btn">
                    <button onclick="openPopup()">Sign In</button>
                </div> -->
            </div>
            <div class="box-img">
                <img src="../IMG/Programming-amico.svg">
            </div>
            <div class="login-form" id="formContainer">
                <i id="times" class="fa-solid fa-xmark fa-3x" onclick="closePopup()"></i>
                <h1> Login </h1>
                
                <p id="error-message" style="color: red; font-weight: bold; display: none;"></p>
                
                <form action="backend/createLogin.php" method="POST" id="loginForm">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required> <br>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" name="login"> Login </button>
                </form>
            </div>

            <!-- <div class="createaccount-form" id="CreateAccount">
                <i id="times" class="fa-solid fa-xmark fa-3x" onclick="closeAccountPopup()"></i>
                <h1> Create Account </h1> -->

                <!-- Display create account message dynamically -->
                <!-- <p id="create-message" style="color: red; font-weight: bold; display: none;"></p>

                <form action="backend/createLogin.php" method="POST" enctype="multipart/form-data">
                    <div class="first-last">
                        <div class="firstname">
                            <label for="fname">First name<span>*</span></label>
                            <input type="text" name="fname" required>
                        </div>
                        <div class="lastname">
                            <label for="lname">Last name<span>*</span></label>
                            <input type="text" name="lname" required>
                        </div>
                    </div>
                    <div class="email">
                        <label for="email">Email<span>*</span></label><br>
                        <input type="email" name="email" required>
                    </div>
                    <div class="passwords">
                        <div class="password1">
                            <label for="password">Password<span>*</span></label>
                            <input type="password" name="password" id="password" required>
                        </div>
                        <div class="password2">
                            <label for="cpassword">Confirm password<span>*</span></label>
                            <input type="password" name="cpassword" id="cpassword" required>
                        </div>
                    </div>
                    <div class="btn">
                        <button type="submit" name="createAccount"> Create Account </button>
                    </div>
                </form>
            </div> -->
        </div>
    </section>
    <section class="courses" id="courses">
      <div class="section-header">
          <h2>Available <span>Courses</span></h2>
          <p>Explore our carefully crafted web development courses designed specifically for ASYV students</p>
      </div>
      
      <div class="courses-container">
          <div class="course-card">
              <div class="course-image">
                  <img src="../IMG/course.jpeg" alt="HTML & CSS Fundamentals">
              </div>
              <div class="course-content">
                  <h3>HTML & CSS </h3>
                  <p>Master the building blocks of web development.</p>
                  <div class="lessons-count">
                      <i class="fa-solid fa-book"></i>
                      <span>8 Lessons</span>
                  </div>
                  <div class="course-details">
                      <div class="detail">
                          <i class="fa-solid fa-clock"></i>
                          <span>12 Hours</span>
                      </div>
                      <div class="detail">
                          <i class="fa-solid fa-code"></i>
                          <span>Beginner</span>
                      </div>
                  </div>
                  <div class="course-btn">
                      <button>Explore Course</button>
                  </div>
              </div>
          </div>
          <div class="course-card">
              <div class="course-image">
                  <img src="../IMG/course.jpeg" alt="JavaScript Essentials">
              </div>
              <div class="course-content">
                  <h3>JavaScript Essentials</h3>
                  <p>Learn to add interactivity to your websites</p>
                  <div class="lessons-count">
                      <i class="fa-solid fa-book"></i>
                      <span>10 Lessons</span>
                  </div>
                  <div class="course-details">
                      <div class="detail">
                          <i class="fa-solid fa-clock"></i>
                          <span>16 Hours</span>
                      </div>
                      <div class="detail">
                          <i class="fa-solid fa-code"></i>
                          <span>Intermediate</span>
                      </div>
                  </div>
                  <div class="course-btn">
                      <button>Explore Course</button>
                  </div>
              </div>
          </div>
          <div class="course-card">
              <div class="course-image">
                  <img src="../IMG/course.jpeg" alt="Responsive Web Design">
              </div>
              <div class="course-content">
                  <h3>Responsive Web Design</h3>
                  <p>Create websites that look great on any device.</p>
                  <div class="lessons-count">
                      <i class="fa-solid fa-book"></i>
                      <span>6 Lessons</span>
                  </div>
                  <div class="course-details">
                      <div class="detail">
                          <i class="fa-solid fa-clock"></i>
                          <span>10 Hours</span>
                      </div>
                      <div class="detail">
                          <i class="fa-solid fa-code"></i>
                          <span>Intermediate</span>
                      </div>
                  </div>
                  <div class="course-btn">
                      <button>Explore Course</button>
                  </div>
              </div>
          </div>
      </div>
    </section>
    <footer>
      <p>Powered By: ASYV IT Department</p>
    </footer>
    <script src="../JS/index.js"></script>
    <script>
        // Function to get URL parameters
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Check for login error message
        const errorMessage = getQueryParam('message');
        if (errorMessage && document.getElementById('error-message')) {
            document.getElementById('error-message').innerText = errorMessage;
            document.getElementById('error-message').style.display = 'block';
            openPopup();
        }

        // Function to get create account message
        function getQueryParamC(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Check for create account message
        const errorCreate = getQueryParamC('create_message');
        if (errorCreate && document.getElementById('create-message')) {
            document.getElementById('create-message').innerText = errorCreate;
            document.getElementById('create-message').style.display = 'block';
            createAccountPopup();
        }

</script>
</body>
</html>
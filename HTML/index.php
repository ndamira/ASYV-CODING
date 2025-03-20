<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASYV-CODING</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                        <li><a href="">Home</a></li>
                        <li><a href="">About Us</a></li>
                        <li><a href="">Courses</a></li>
                    </ul>
                </div>
                <div class="btn">
                    <button onclick="createAccountPopup()">Create Account</button>
                </div>
                <div class="menu">
                    <i class="fa-solid fa-bars" onclick="openNavLinks()"></i>
                </div>
            </nav>
        </header>
        <div class="navLinks" id="navLinks">
            <i class="fa-solid fa-xmark fa-3x" onclick="closeNavLinks()"></i>
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="">About Us</a></li>
                <li><a href="">Courses</a></li>
            </ul>
        </div>
        <div class="container">
            <div class="content">
                <h1>Empower your<br> <span>future </span> through <br> web development!</h1>
                <p>Our platform serves as a gateway for ASYV students to explore, learn, and master web development.
                    By providing hands-on courses, real-world projects, and a supportive community, we empower young innovators with the skills they need to shape the digital future and create opportunities beyond the classroom.</p>
                <div class="btn">
                    <button onclick="openPopup()">Sign In</button>
                </div>
            </div>
            <div class="box-img">
                <img src="../IMG/Programming-amico.svg">
            </div>
            <div class="login-form" id="formContainer">
                <i id="times" class="fa-solid fa-xmark fa-3x" onclick="closePopup()"></i>
                <h1> Login </h1>

                <!-- Display error message dynamically -->
                <p id="error-message" style="color: red; font-weight: bold; display: none;"></p>

                <form action="backend/createLogin.php" method="POST" id="loginForm">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required> <br>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" name="login"> Login </button>
                </form>
            </div>

            <div class="createaccount-form" id="CreateAccount">
                <i id="times" class="fa-solid fa-xmark fa-3x" onclick="closeAccountPopup()"></i>
                <h1> Create Account </h1>

                <!-- Display create account message dynamically -->
                <p id="create-message" style="color: red; font-weight: bold; display: none;"></p>

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
            </div>
        </div>

    </section>
    <section class="courses" id="courses">

    </section>
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
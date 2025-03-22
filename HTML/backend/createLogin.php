<?php
session_start();
include('conn.php'); //database connection from backend file

// Registration (Create Account)
if (isset($_POST['createAccount'])) {
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password !== $cpassword) {
        header("Location: ../index.php?create_message=Passwords do not match");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, role, password) VALUES (?, ?, ?, 'student', ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
    
    if ($stmt->execute()) {
        header("Location: ../index.php?create_message=Account created successfully! Please log in.");
    } else {
        header("Location: ../index.php?create_message=Registration failed. Try again.");
    }
    exit();
}

// Login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user from database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    
        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Store user session
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            if($_SESSION['role']=='administrator' || $_SESSION['role']=='teacher'){
                header("Location: ../admin/index.php"); // Redirect to dashboard
            }
            else if($_SESSION['role']=='student'){
                header("Location: ../myCourse.php"); // Redirect to dashboard
            }
           
            exit();
        } else {
            header("Location: ../index.php?message=Invalid email or password");
            exit();
        }
    } else {
        header("Location: ../index.php?message=Invalid email or password");
        exit();
    }
    

    mysqli_stmt_close($stmt);
}
?>

<?php
session_start();
// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Database connection
require_once '../backend/conn.php';

// Check if form is submitted to update a course
if(isset($_POST['edit_course'])) {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Update course in database
    $query = "UPDATE courses SET name = '$name', description = '$description' WHERE id = $course_id";
    
    if(mysqli_query($conn, $query)) {
        // Redirect to prevent form resubmission
        header("Location: courses.php?status=updated");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
        header("Location: courses.php?status=error&message=" . urlencode($error));
        exit();
    }
} else {
    // If someone tries to access this page directly
    header("Location: courses.php");
    exit();
}
?>
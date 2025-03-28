<?php
session_start();
// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Database connection
require_once '../backend/conn.php';

// Check if form is submitted to delete a course
if(isset($_POST['delete_course'])) {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    
    // First, delete all related lessons
    $delete_lessons_query = "DELETE FROM lessons WHERE course_id = $course_id";
    mysqli_query($conn, $delete_lessons_query);
    
    // Then, delete the course
    $delete_course_query = "DELETE FROM courses WHERE id = $course_id";
    
    if(mysqli_query($conn, $delete_course_query)) {
        // Redirect to prevent form resubmission
        header("Location: courses.php?status=deleted");
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
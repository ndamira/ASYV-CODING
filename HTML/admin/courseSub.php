<?php

// Database connection
require_once '../backend/conn.php';

// Check if form is submitted to add a new course
if(isset($_POST['add_course'])) {
    // Validate and sanitize input
    $name = trim($_POST['name']); // Trim whitespace
    $description = trim($_POST['description']); // Trim whitespace

    // Check if name and description are not empty
    if(empty($name) || empty($description)) {
        $error = "Course name and description cannot be empty.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO courses (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        
        if($stmt->execute()) {
            // Redirect to prevent form resubmission
            header("Location: courses.php?status=success");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

?>
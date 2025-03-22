<?php
// Database connection
require_once 'conn.php';

// Check if the form was submitted with the DELETE_LESSON action
if(isset($_POST['DELETE_LESSON'])) {
    // Get form data
    $lesson_id = $_POST['lesson_id'];
    $course_id = $_POST['course_id'];
    
    // Get the file path before deleting the record
    $query = "SELECT content FROM lessons WHERE id = $lesson_id";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $file_path = $row['content'];
        
        // Delete the record from the database
        $delete_query = "DELETE FROM lessons WHERE id = $lesson_id";
        
        if(mysqli_query($conn, $delete_query)) {
            // Delete the associated file if it exists
            if(!empty($file_path) && file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Redirect with success message
            header("Location: ../admin/lessons.php?course_id=$course_id&success=lesson_deleted");
        } else {
            // Redirect with error
            header("Location: ../admin/lessons.php?course_id=$course_id&error=delete_failed");
        }
    } else {
        // Redirect with error if lesson not found
        header("Location: ../admin/lessons.php?course_id=$course_id&error=lesson_not_found");
    }
} else {
    // Redirect if accessed directly without proper form submission
    header("Location: ../admin/course.php");
}
?>
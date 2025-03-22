<?php
// Database connection
require_once 'conn.php';

// Check if the form was submitted with the UPDATE_LESSON action
if(isset($_POST['UPDATE_LESSON'])) {
    // Get form data
    $lesson_id = $_POST['lesson_id'];
    $course_id = $_POST['course_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    // Initialize update query
    $query = "UPDATE lessons SET title = '$name'";
    
    // Check if a new file was uploaded
    if(isset($_FILES['content']) && $_FILES['content']['size'] > 0) {
        // Directory to save uploaded files
        $target_dir = "../uploads/lessons/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Get file extension
        $file_extension = strtolower(pathinfo($_FILES["content"]["name"], PATHINFO_EXTENSION));
        
        // Generate unique filename to prevent overwriting
        $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if file type is allowed
        $allowed_types = array('pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'mp4', 'zip');
        if(!in_array($file_extension, $allowed_types)) {
            // Redirect with error
            header("Location: ../admin/lessons.php?course_id=$course_id&error=invalid_file_type");
            exit();
        }
        
        // Try to upload the file
        if(move_uploaded_file($_FILES["content"]["tmp_name"], $target_file)) {
            // Get current file to delete after successful upload
            $get_current_file = "SELECT file_path FROM lessons WHERE id = $lesson_id";
            $result = mysqli_query($conn, $get_current_file);
            
            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $current_file = $row['file_path'];
                
                // Update query to include new file path
                $query .= ", file_path = '$target_file'";
                
                // Delete old file if it exists
                if(file_exists($current_file)) {
                    unlink($current_file);
                }
            }
        } else {
            // Redirect with error
            header("Location: ../admin/lessons.php?course_id=$course_id&error=upload_failed");
            exit();
        }
    }
    
    // Complete the query and add the WHERE clause
    $query .= ", updated_at = NOW() WHERE id = $lesson_id";
    
    // Execute the update query
    if(mysqli_query($conn, $query)) {
        // Redirect with success message
        header("Location: ../admin/lessons.php?course_id=$course_id&success=lesson_updated");
    } else {
        // Redirect with error
        header("Location: ../admin/lessons.php?course_id=$course_id&error=update_failed");
    }
} else {
    // Redirect if accessed directly without proper form submission
    header("Location: ../admin/course.php");
}
?>
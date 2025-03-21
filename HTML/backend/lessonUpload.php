<?php
include('conn.php');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if (isset($_POST['ADD_PURCHASE'])) {
    // Get lesson name from form
    $lessonName = $_POST['name'];
    $course_id = $_POST['course_id'];
    
    // File upload handling
    if (isset($_FILES['content']) && $_FILES['content']['error'] == 0) {
        $targetDir = "uploads/"; // Directory where files will be stored
        
        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Generate unique filename
        $fileName = basename($_FILES["content"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        // Allow certain file types - modify based on your requirements
        $allowTypes = array('pdf', 'doc', 'docx', 'txt');
        if (in_array($fileType, $allowTypes)) {
            // Upload file
            if (move_uploaded_file($_FILES["content"]["tmp_name"], $targetFilePath)) {
                // Insert into database
                $stmt = $conn->prepare("INSERT INTO lessons (title,content,course_id) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $lessonName, $targetFilePath,$course_id);
                
                if ($stmt->execute()) {
                    echo "<p>Lesson uploaded successfully!</p>";
                } else {
                    echo "<p>Error: Failed to save to database. " . $stmt->error . "</p>";
                }
                $stmt->close();
            } else {
                echo "<p>Error: Failed to upload file.</p>";
            }
        } else {
            echo "<p>Error: Only PDF, DOC, DOCX, and TXT files are allowed.</p>";
        }
    } else {
        echo "<p>Error: " . $_FILES['content']['error'] . "</p>";
    }
}

$conn->close();
?>
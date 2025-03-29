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
        $fileName = time() . '_' . uniqid() . '.' . pathinfo($_FILES["content"]["name"], PATHINFO_EXTENSION);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        // Allow certain file types - modify based on your requirements
        $allowTypes = array('pdf', 'doc', 'docx', 'txt');
        if (in_array($fileType, $allowTypes)) {
            // Upload file
            if (move_uploaded_file($_FILES["content"]["tmp_name"], $targetFilePath)) {
                // Start transaction
                $conn->begin_transaction();
                
                try {
                    // Insert into lessons table
                    $stmt = $conn->prepare("INSERT INTO lessons (title, content, course_id) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $lessonName, $targetFilePath, $course_id);
                    $stmt->execute();
                    
                    // Get the lesson_id of the newly inserted lesson
                    $lesson_id = $conn->insert_id;
                    $stmt->close();
                    
                    // Process link data if any exists
                    if (isset($_POST['link_title']) && isset($_POST['link_url']) && !empty($_POST['link_title'][0])) {
                        $linkTitles = $_POST['link_title'];
                        $linkUrls = $_POST['link_url'];
                        
                        // Prepare statement for inserting links
                        $linkStmt = $conn->prepare("INSERT INTO lesson_links (lesson_id, title, url) VALUES (?, ?, ?)");
                        
                        // Loop through and insert each link
                        for ($i = 0; $i < count($linkTitles); $i++) {
                            if (!empty($linkTitles[$i]) && !empty($linkUrls[$i])) {
                                $linkStmt->bind_param("iss", $lesson_id, $linkTitles[$i], $linkUrls[$i]);
                                $linkStmt->execute();
                            }
                        }
                        
                        $linkStmt->close();
                    }
                    
                    // Commit transaction
                    $conn->commit();
                    
                    // Success message
                    echo "<p class='success'>Lesson and resources uploaded successfully!</p>";
                    // Redirect back to course page
                    echo "<script>
                        setTimeout(function() {
                            window.location.href = '../admin/lessons.php?course_id=" . $course_id . "';
                        }, 10);
                    </script>";
                    
                } catch (Exception $e) {
                    // Roll back in case of error
                    $conn->rollback();
                    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
                }
                
            } else {
                echo "<p class='error'>Error: Failed to upload file.</p>";
            }
        } else {
            echo "<p class='error'>Error: Only PDF, DOC, DOCX, and TXT files are allowed.</p>";
        }
    } else {
        echo "<p class='error'>Error: " . $_FILES['content']['error'] . "</p>";
    }
}

$conn->close();
?>
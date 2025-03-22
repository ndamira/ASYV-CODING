<?php
// Database connection
require_once 'conn.php';

// Check if lesson_id is provided
$base_url = "http://localhost/ASYV-CODING/HTML/backend/";
if(isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id'];
    
    // Fetch lesson details
    $query = "SELECT title, content FROM lessons WHERE id = $lesson_id";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['title'];
        // $file_name = basename($row['content']); // Prevent directory traversal
        // $file_path = "../backend/uploads/" . $file_name;
        // $file_path = $row['content'];
        $file_name = $row['content']; // Assuming it only contains the filename, e.g., "document.pdf"
        $file_path = $base_url . $file_name; // Create absolute URL
        // Check if file exists
        if(@file_get_contents($file_path, false, null, 0, 1)) {
            // Get file extension
            $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
            
            // Display appropriate content based on file type
            switch($file_extension) {
                case 'pdf':
                    echo '<div class="pdf-viewer">
                          <embed src="' . $file_path . '" type="application/pdf" width="100%" height="400px">
                          <p>If the PDF is not displaying properly, <a href="' . $file_path . '" target="_blank">click here to download</a>.</p>
                          </div>';
                    break;
                    
                case 'txt':
                    // Display text file content
                    $content = file_get_contents($file_path);
                    echo '<div class="text-content">
                          <pre>' . htmlspecialchars($content) . '</pre>
                          </div>';
                    break;
                    
                case 'mp4':
                    // Display video player
                    echo '<div class="video-player">
                          <video width="100%" height="auto" controls>
                            <source src="' . $file_path . '" type="video/mp4">
                            Your browser does not support the video tag.
                          </video>
                          </div>';
                    break;
                    
                case 'doc':
                case 'docx':
                case 'ppt':
                case 'pptx':
                    // For Office documents, provide download link
                    echo '<div class="document-viewer">
                          <p>This file type cannot be previewed directly. Please download to view:</p>
                          <a href="' . $file_path . '" class="download-btn" download>Download ' . strtoupper($file_extension) . ' File</a>
                          </div>';
                    break;
                    
                case 'zip':
                    // For zip files, provide download link
                    echo '<div class="zip-viewer">
                          <p>ZIP file cannot be previewed. Please download to extract and view contents:</p>
                          <a href="' . $file_path . '" class="download-btn" download>Download ZIP File</a>
                          </div>';
                    break;
                    
                default:
                    // For other files, provide generic download link
                    echo '<div class="file-viewer">
                          <p>This file type cannot be previewed. Please download to view:</p>
                          <a href="' . $file_path . '" class="download-btn" download>Download File</a>
                          </div>';
            }
            
        } else {
            // File doesn't exist
            echo '<p class="error">Error: The file associated with this lesson could not be found.</p>';
        }
    } else {
        // Lesson not found
        echo '<p class="error">Error: Lesson not found.</p>';
    }
} else {
    // No lesson_id provided
    echo '<p class="error">Error: No lesson specified.</p>';
}
?>
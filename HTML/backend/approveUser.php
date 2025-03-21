<?php
// Database connection
session_start();
include('conn.php'); // Database connection file

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Update user status to "Approved"
    $sql = "UPDATE users SET status = 'Approved' WHERE id = '$userId'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin/pendingUsers.php?message=User approved successfully");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

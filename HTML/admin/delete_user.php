<?php
// delete_user.php
require_once '../backend/conn.php';

// Validate and sanitize input
$userId = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($userId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

// Prepare delete query
$query = "DELETE FROM users WHERE id = $userId";

// Execute the query
if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to delete user: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
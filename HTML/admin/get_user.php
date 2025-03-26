<?php
// get_user.php
require_once '../backend/conn.php';

// Validate and sanitize input
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

// Fetch user details
$query = "SELECT * FROM users WHERE id = $userId";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    // Remove sensitive information
    unset($user['password']);
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
}

mysqli_close($conn);
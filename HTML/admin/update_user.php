<?php
// update_user.php
require_once '../backend/conn.php';

// Validate and sanitize input
$userId = isset($_POST['id']) ? intval($_POST['id']) : 0;
$firstName = isset($_POST['first_name']) ? mysqli_real_escape_string($conn, $_POST['first_name']) : '';
$lastName = isset($_POST['last_name']) ? mysqli_real_escape_string($conn, $_POST['last_name']) : '';
$email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
$grade = isset($_POST['grade']) ? mysqli_real_escape_string($conn, $_POST['grade']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : null;

// Validate inputs
if ($userId <= 0 || empty($firstName) || empty($lastName) || empty($email) || empty($grade)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Prepare update query
$query = "UPDATE users SET 
    first_name = '$firstName', 
    last_name = '$lastName', 
    email = '$email', 
    grade = '$grade'";

// Add password update if provided
if ($password !== null) {
    // Hash the password (use appropriate hashing method)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query .= ", password = '$hashedPassword'";
}

$query .= " WHERE id = $userId";

// Execute the query
if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to update user: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
<?php
// fetch_users.php
require_once '../backend/conn.php';

// Validate and sanitize input
$grade = isset($_POST['grade']) ? mysqli_real_escape_string($conn, $_POST['grade']) : '';
$searchTerm = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';

// Build the query
$query = "SELECT * FROM users WHERE 1=1";

// Add grade filter if specified
if (!empty($grade)) {
    $query .= " AND grade = '$grade'";
}

// Add search filter if search term is provided
if (!empty($searchTerm)) {
    $query .= " AND (first_name LIKE '%$searchTerm%' OR last_name LIKE '%$searchTerm%')";
}

// Execute the query
$result = mysqli_query($conn, $query);

// Fetch results
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($users);

mysqli_close($conn);
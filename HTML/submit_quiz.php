<?php
session_start();
require_once 'backend/conn.php';

// Validate user authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!$input || !isset($input['lesson_id']) || !isset($input['assignment_id']) || !isset($input['answers'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$user_id = $_SESSION['user_id'];
$lesson_id = $input['lesson_id'];
$assignment_id = $input['assignment_id'];
$answers = $input['answers'];

// Check if quiz is already completed
$check_query = "SELECT * FROM user_lesson_completions 
                WHERE user_id = ? AND assignment_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $user_id, $assignment_id);
$stmt->execute();
$existing_result = $stmt->get_result();
if ($existing_result->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Quiz already completed']);
    exit();
}

// Validate and grade quiz
$detailed_results = [];
$total_score = 0;
$max_score = 0;
$passed = true;

foreach ($answers as $question_id => $selected_option_id) {
    // Remove 'q' prefix from question_id
    $clean_question_id = str_replace('q', '', $question_id);
    
    // Check if selected option is correct
    $check_query = "SELECT is_correct FROM assignment_options 
                    WHERE question_id = ? AND id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $clean_question_id, $selected_option_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $option_data = $result->fetch_assoc();
    $is_correct = $option_data['is_correct'] == 1;
    
    // Add to detailed results
    $detailed_results[] = [
        'question_id' => $clean_question_id,
        'user_selected' => ['id' => $selected_option_id],
        'is_correct' => $is_correct
    ];
    
    // Score calculation (adjust as needed)
    $max_score++;
    if ($is_correct) {
        $total_score++;
    } else {
        $passed = false; // Fail if any question is wrong
    }
}

// Calculate pass percentage (e.g., 70% to pass)
$pass_percentage = ($total_score / $max_score) * 100;
$passed = $pass_percentage >= 50;

// Insert completion record
$insert_query = "INSERT INTO user_lesson_completions 
                 (user_id, lesson_id, assignment_id, score, max_score, passed) 
                 VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("iiiids", $user_id, $lesson_id, $assignment_id, $total_score, $max_score, $passed);
$stmt->execute();

// Respond with results
echo json_encode([
    'success' => true,
    'message' => $passed ? 'Congratulations! You passed the quiz.' : 'Sorry, you did not pass. Please try again.',
    'detailed_results' => $detailed_results,
    'passed' => $passed,
    'score' => $total_score,
    'max_score' => $max_score
]);
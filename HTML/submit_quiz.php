<?php
// Enhanced Quiz Submission Handler with Result Saving

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'quiz_submission_errors.log');

session_start();
require_once 'backend/conn.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized: User not logged in', 401);
    }

    $rawInput = file_get_contents('php://input');
    error_log('Raw Quiz Submission Input: ' . $rawInput);

    if (empty($rawInput)) {
        throw new Exception('No input data received', 400);
    }

    $data = json_decode($rawInput, true, 512, JSON_THROW_ON_ERROR);

    $requiredFields = ['lesson_id', 'assignment_id', 'answers'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Missing required field: {$field}", 422);
        }
    }

    $userId = $_SESSION['user_id'];
    $lessonId = filter_var($data['lesson_id'], FILTER_VALIDATE_INT);
    $assignmentId = filter_var($data['assignment_id'], FILTER_VALIDATE_INT);
    $answers = $data['answers'];

    if (!$lessonId || !$assignmentId) {
        throw new Exception('Invalid lesson or assignment ID', 400);
    }

    $detailedResults = [];
    $totalQuestions = count($answers);
    $correctAnswers = 0;

    // Start a transaction for atomic database operations
    $conn->begin_transaction();

    foreach ($answers as $questionKey => $selectedOptionId) {
        // Extract numeric question ID from the key (e.g., 'q3' -> 3)
        if (!preg_match('/^q(\d+)$/', $questionKey, $matches)) {
            throw new Exception("Invalid question key format: {$questionKey}", 400);
        }
        $questionId = intval($matches[1]);

        // Diagnostic query to verify question and assignment relationship
        $diagQuery = "SELECT 
            q.id AS question_id, 
            q.assignment_id,
            q.question_text,
            o.id AS option_id,
            o.option_text,
            o.is_correct
        FROM assignment_questions q
        LEFT JOIN assignment_options o ON q.id = o.question_id
        WHERE q.id = ? AND q.assignment_id = ? AND o.id = ?";

        $stmt = $conn->prepare($diagQuery);
        $stmt->bind_param("iii", $questionId, $assignmentId, $selectedOptionId);
        $stmt->execute();
        $result = $stmt->get_result();

        // If no matching question and option found
        if ($result->num_rows === 0) {
            throw new Exception("No matching question or option found. Question ID: {$questionId}, Option ID: {$selectedOptionId}, Assignment ID: {$assignmentId}", 400);
        }

        $row = $result->fetch_assoc();
        $correctOptionFound = $row['is_correct'] == 1;
        
        $detailedResults[] = [
            'question_id' => $questionId,
            'user_selected' => [
                'id' => $selectedOptionId,
                'text' => $row['option_text']
            ],
            'is_correct' => $correctOptionFound
        ];

        if ($correctOptionFound) {
            $correctAnswers++;
        }
    }

    // Calculate score
    $scorePercentage = ($correctAnswers / $totalQuestions) * 100;
    $isPassed = $scorePercentage >= 70;

    // Prepare statement for inserting quiz result
    $resultStmt = $conn->prepare("
        INSERT INTO user_quiz_results 
        (user_id, assignment_id, lesson_id, total_questions, correct_answers, score_percentage, is_passed) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        total_questions = ?, 
        correct_answers = ?, 
        score_percentage = ?, 
        is_passed = ?
    ");

    $resultStmt->bind_param(
        "iiiidbiiidb", 
        $userId, $assignmentId, $lessonId, 
        $totalQuestions, $correctAnswers, $scorePercentage, $isPassed,
        // Update values
        $totalQuestions, $correctAnswers, $scorePercentage, $isPassed
    );
    $resultStmt->execute();

    // Commit the transaction
    $conn->commit();

    // Prepare and send response
    $response = [
        'success' => true,
        'message' => $isPassed 
            ? 'Congratulations! You passed the quiz.' 
            : 'Quiz completed. Try again to improve your score.',
        'detailed_results' => $detailedResults,
        'stats' => [
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'score_percentage' => round($scorePercentage, 2)
        ],
        'passed' => $isPassed
    ];

    echo json_encode($response);
    exit();

} catch (Exception $e) {
    // Rollback the transaction in case of error
    if (isset($conn) && $conn->in_transaction()) {
        $conn->rollback();
    }

    error_log('Quiz Submission Error: ' . $e->getMessage());
    error_log('Full Trace: ' . $e->getTraceAsString());

    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred',
        'error_details' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
<?php
// backend/assignmentUpload.php
include 'conn.php'; // Your database connection file

if (isset($_POST['CREATE_ASSIGNMENT'])) {
    // Sanitize and validate inputs
    $assignment_name = mysqli_real_escape_string($conn, $_POST['name']);
    $lesson_id = intval($_POST['lesson_id']);
    $questions = $_POST['questions'];

    // Start a transaction to ensure data integrity
    mysqli_begin_transaction($conn);

    try {
        // Insert assignment
        $assignment_query = "INSERT INTO assignments (lesson_id, name) VALUES ('$lesson_id', '$assignment_name')";
        mysqli_query($conn, $assignment_query);
        $assignment_id = mysqli_insert_id($conn);

        // Insert questions and options
        foreach ($questions as $index => $question_data) {
            $question_text = mysqli_real_escape_string($conn, $question_data['text']);
            
            // Insert question
            $question_query = "INSERT INTO assignment_questions (assignment_id, question_text) VALUES ('$assignment_id', '$question_text')";
            mysqli_query($conn, $question_query);
            $question_id = mysqli_insert_id($conn);

            // Prepare correct options array for easier checking
            $correct_options = isset($question_data['correct_options']) ? 
                array_map('intval', $question_data['correct_options']) : [];

            // Insert options
            foreach ($question_data['options'] as $option_index => $option) {
                $option = mysqli_real_escape_string($conn, $option);
                $is_correct = in_array($option_index, $correct_options) ? 1 : 0;

                $option_query = "INSERT INTO assignment_options (question_id, option_text, is_correct) 
                                 VALUES ('$question_id', '$option', '$is_correct')";
                mysqli_query($conn, $option_query);
            }
        }

        // Commit the transaction
        mysqli_commit($conn);

        // Redirect with success message
        header("Location: assignments.php?course_id=$lesson_id&status=success");
        exit();

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        mysqli_rollback($conn);

        // Redirect with error message
        header("Location: assignments.php?course_id=$lesson_id&status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
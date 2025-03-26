<?php
// edit_assignment.php
session_start();
    // Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.php");
  exit();
}
include '../backend/conn.php'; // Your database connection file

// Check if assignment_id and course_id are provided
if (!isset($_GET['assignment_id']) || !isset($_GET['course_id'])) {
    die("Invalid assignment or course ID");
}

$assignment_id = intval($_GET['assignment_id']);
$course_id = intval($_GET['course_id']);

// Handle assignment update
if (isset($_POST['UPDATE_ASSIGNMENT'])) {
    mysqli_begin_transaction($conn);

    try {
        // Update assignment name
        $assignment_name = mysqli_real_escape_string($conn, $_POST['name']);
        mysqli_query($conn, "UPDATE assignments SET name = '$assignment_name' WHERE id = '$assignment_id'");

        // Process questions
        if (isset($_POST['questions'])) {
            foreach ($_POST['questions'] as $question_id => $question_data) {
                // Update question text
                $question_text = mysqli_real_escape_string($conn, $question_data['text']);
                mysqli_query($conn, "UPDATE assignment_questions SET question_text = '$question_text' WHERE id = '$question_id'");

                // Update or add options
                if (isset($question_data['options'])) {
                    // First, delete existing options
                    mysqli_query($conn, "DELETE FROM assignment_options WHERE question_id = '$question_id'");

                    // Prepare correct options array
                    $correct_options = isset($question_data['correct_options']) ? 
                        array_map('intval', $question_data['correct_options']) : [];

                    // Insert new options
                    foreach ($question_data['options'] as $option_index => $option) {
                        $option = mysqli_real_escape_string($conn, $option);
                        $is_correct = in_array($option_index, $correct_options) ? 1 : 0;

                        mysqli_query($conn, "INSERT INTO assignment_options (question_id, option_text, is_correct) 
                                             VALUES ('$question_id', '$option', '$is_correct')");
                    }
                }
            }
        }

        // Commit transaction
        mysqli_commit($conn);

        header("Location: assignments_management.php?course_id=$course_id&status=updated");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error_message = $e->getMessage();
    }
}

// Fetch assignment details
$assignment_query = "SELECT a.id, a.name, l.id as lesson_id, l.title as lesson_title 
                     FROM assignments a
                     JOIN lessons l ON a.lesson_id = l.id
                     WHERE a.id = '$assignment_id'";
$assignment_result = mysqli_query($conn, $assignment_query);
$assignment = mysqli_fetch_assoc($assignment_result);

// Fetch questions and their options
$questions_query = "SELECT aq.id, aq.question_text, 
                           ao.id as option_id, ao.option_text, ao.is_correct
                    FROM assignment_questions aq
                    LEFT JOIN assignment_options ao ON aq.id = ao.question_id
                    WHERE aq.assignment_id = '$assignment_id'
                    ORDER BY aq.id, ao.id";
$questions_result = mysqli_query($conn, $questions_query);

// Organize questions and options
$questions = [];
while ($row = mysqli_fetch_assoc($questions_result)) {
    $question_id = $row['id'];
    if (!isset($questions[$question_id])) {
        $questions[$question_id] = [
            'text' => $row['question_text'],
            'options' => []
        ];
    }
    
    if ($row['option_id']) {
        $questions[$question_id]['options'][] = [
            'text' => $row['option_text'],
            'is_correct' => $row['is_correct']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Assignment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .question-group {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
        }
        .option-input {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        .option-input input[type="text"] {
            flex-grow: 1;
            margin-right: 10px;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Edit Assignment: <?php echo htmlspecialchars($assignment['name']); ?></h1>

    <?php if (isset($error_message)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="assignment-details">
            <div class="assignment-name">
                <label>Assignment Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($assignment['name']); ?>" required />
            </div>

            <div class="questions-container">
                <?php foreach ($questions as $question_id => $question): ?>
                    <div class="question-group">
                        <div class="question-text">
                            <label>Question Text</label>
                            <input type="text" name="questions[<?php echo $question_id; ?>][text]" 
                                   value="<?php echo htmlspecialchars($question['text']); ?>" required />
                        </div>

                        <div class="question-options">
                            <label>Options</label>
                            <?php foreach ($question['options'] as $option_index => $option): ?>
                                <div class="option-input">
                                    <input type="text" 
                                           name="questions[<?php echo $question_id; ?>][options][]" 
                                           value="<?php echo htmlspecialchars($option['text']); ?>" 
                                           required />
                                    <label>
                                        <input type="checkbox" 
                                               name="questions[<?php echo $question_id; ?>][correct_options][]" 
                                               value="<?php echo $option_index; ?>"
                                               <?php echo $option['is_correct'] ? 'checked' : ''; ?> />
                                        Correct
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="actions">
                <button type="submit" name="UPDATE_ASSIGNMENT">Update Assignment</button>
                <a href="assignments_management.php?course_id=<?php echo $course_id; ?>">Cancel</a>
            </div>
        </div>
    </form>
</body>
</html>
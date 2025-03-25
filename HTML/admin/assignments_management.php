<?php
// assignments_management.php
session_start();
include '../backend/conn.php'; // Your database connection file

// Handle deletion of assignment
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['assignment_id'])) {
    $assignment_id = intval($_GET['assignment_id']);
    $course_id = intval($_GET['course_id']);

    // Start a transaction to ensure data integrity
    mysqli_begin_transaction($conn);

    try {
        // Delete related options first
        mysqli_query($conn, "DELETE ao FROM assignment_options ao 
                             JOIN assignment_questions aq ON ao.question_id = aq.id 
                             WHERE aq.assignment_id = '$assignment_id'");

        // Delete questions
        mysqli_query($conn, "DELETE FROM assignment_questions WHERE assignment_id = '$assignment_id'");

        // Delete assignment
        mysqli_query($conn, "DELETE FROM assignments WHERE id = '$assignment_id'");

        mysqli_commit($conn);

        header("Location: assignments_management.php?course_id=$course_id&status=deleted");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: assignments_management.php?course_id=$course_id&status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// Get course_id from URL
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

// Fetch assignments for the course
$assignments_query = "SELECT a.id, a.name, l.title as lesson_title, 
                             COUNT(aq.id) as question_count 
                      FROM assignments a
                      JOIN lessons l ON a.lesson_id = l.id
                      LEFT JOIN assignment_questions aq ON a.id = aq.assignment_id
                      WHERE l.course_id = '$course_id'
                      GROUP BY a.id, a.name, l.title
                      ORDER BY a.id DESC";
$assignments_result = mysqli_query($conn, $assignments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assignments Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .status-message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <h1>Assignments Management</h1>

    <?php
    // Display status messages
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        $message = '';
        
        switch ($status) {
            case 'deleted':
                $message = "Assignment successfully deleted.";
                $class = 'success';
                break;
            case 'error':
                $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : "An error occurred.";
                $class = 'error';
                break;
        }
        
        if ($message) {
            echo "<div class='status-message $class'>$message</div>";
        }
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>Assignment Name</th>
                <th>Lesson</th>
                <th>Total Questions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($assignments_result) > 0): ?>
                <?php while ($assignment = mysqli_fetch_assoc($assignments_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($assignment['name']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['lesson_title']); ?></td>
                        <td><?php echo intval($assignment['question_count']); ?></td>
                        <td class="actions">
                            <a href="edit_assignment.php?assignment_id=<?php echo $assignment['id']; ?>&course_id=<?php echo $course_id; ?>" class="btn-edit">Edit</a>
                            <a href="?course_id=<?php echo $course_id; ?>&assignment_id=<?php echo $assignment['id']; ?>&action=delete" 
                               class="btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this assignment?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No assignments found for this course.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

   
</body>
</html>
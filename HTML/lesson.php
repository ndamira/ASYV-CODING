<?php
session_start();
require_once 'backend/conn.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

// Fetch course details
$course_id = $_GET['course_id'];
$course_query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($course_query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course_result = $stmt->get_result();
$course = $course_result->fetch_assoc();

// Fetch lessons for this course
$lessons_query = "SELECT l.*, 
                         (SELECT COUNT(*) FROM user_lesson_completions ulc 
                          WHERE ulc.lesson_id = l.id AND ulc.user_id = ?) AS is_completed
                  FROM lessons l 
                  WHERE l.course_id = ?";
$stmt = $conn->prepare($lessons_query);
$stmt->bind_param("ii", $_SESSION['user_id'], $course_id);
$stmt->execute();
$lessons_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Existing head content from original document -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lessons</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/x-icon" href="IMG/Designer.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        root {
        --body--: rgb(71, 128, 95);
        --button--: rgb(243, 156, 69);
        --text--: #fff;
        --black--: #000;
        }

        body {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 25%;
            background-color: #f5f5f5;
            border-right: 1px solid #ddd;
            padding: 1rem;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 1em;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #ddd;
        }

        .sidebar-header i{
            color: rgb(71, 128, 95);
            font-size: 1.5rem;
        }
        
        .course-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .course-info {
            font-size: 0.9rem;
            color: #666;
        }
        
        .lesson-list {
            list-style-type: none;
            flex-grow: 1;
            overflow-y: auto;
        }
        
        .lesson-item {
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .lesson-item:hover {
            background-color: #e9e9e9;
        }
        
        .lesson-item.active {
            background-color: #e0f2ff;
            border-left: 3px solid rgb(71, 128, 95);
        }
        
        /* Main Content Styles */
        .content {
            width: 75%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .lesson-header {
            padding: .3rem 1rem 1rem;
            background-color: white;
            border-bottom: 1px solid #ddd;
            z-index: 10;
            position: sticky;
            top: 0;
        }
        
        .lesson-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .lesson-subtitle {
            font-size: 1rem;
            color: #666;
        }
        
        .scrollable-content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0 2rem 2rem;
        }
        
        .lesson-content {
            margin-bottom: 2rem;
            padding-top: 1.5rem;
        }
        
        .content-iframe {
            width: 100%;
            height: 500px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        
        .resources-section {
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #ddd;
        }
        
        .resources-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .resources-table th, .resources-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .resources-table th {
            background-color: #f5f5f5;
            font-weight: 600;
        }
        
        .resource-link {
            color: rgb(71, 128, 95);
            text-decoration: none;
        }
        
        .resource-link:hover {
            text-decoration: underline;
        }
        
        .quiz-section {
            margin-bottom: 2rem;
        }
        
        .quiz-question {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        
        .question-text {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .options-list {
            list-style-type: none;
        }
        
        .option-item {
            margin-bottom: 0.5rem;
        }
        
        .option-label {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .option-label:hover {
            background-color: #e9e9e9;
        }
        
        .submit-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: rgb(71, 128, 95);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
        }
        
        .submit-btn:hover {
            background-color: rgb(71, 128, 95);
        }
        
        /* Lesson Content Containers */
        .lesson-container {
            display: none;
            height: 100%;
        }
        
        .lesson-container.active {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
                overflow-y: auto;
                height: auto;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                max-height: 300px;
                border-right: none;
                border-bottom: 1px solid #ddd;
                position: relative;
            }
            
            .content {
                width: 100%;
                height: auto;
                min-height: calc(100vh - 300px);
            }
            
            .lesson-header {
                position: sticky;
                top: 0;
                /* padding: 1rem; */
            }
            
            .scrollable-content {
                padding: 0 1rem 1rem;
            }
            
            .content-iframe {
                height: 400px;
            }
        }
        
        @media (max-width: 480px) {
            .content-iframe {
                height: 300px;
            }
            
            .lesson-title {
                font-size: 1.5rem;
            }
            
            .section-title {
                font-size: 1.25rem;
            }
        }

        /* answers */
        .quiz-question.answered .option-item {
    position: relative;
    transition: background-color 0.3s ease;
}

.quiz-question .option-item.correct-answer {
    background-color: rgba(0, 255, 0, 0.1);
    border-color: green;
}

.quiz-question .option-item.incorrect-answer {
    background-color: rgba(255, 0, 0, 0.1);
    border-color: red;
}

.quiz-question .option-item.user-selected::after {
    content: '';
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    border-radius: 50%;
}

.quiz-question .option-item.correct-answer.user-selected::after {
    background-color: green;
}

.quiz-question .option-item.incorrect-answer.user-selected::after {
    background-color: red;
}

.submit-btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="myCourse.php"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h1 class="course-title"><?php echo htmlspecialchars($course['name']); ?></h1>
                <p class="course-info">3 Lessons</p>
                <?php 
                // echo $course['total_lessons']; 
                ?>
            </div>
        </div>
        
        <ul class="lesson-list">
            <?php 
            $lesson_number = 1;
            while ($lesson = $lessons_result->fetch_assoc()) { 
            ?>
                <li class="lesson-item <?php echo $lesson_number == 1 ? 'active' : ''; ?> 
                    <?php echo $lesson['is_completed'] > 0 ? 'completed' : ''; ?>" 
                    data-lesson="lesson<?php echo $lesson_number; ?>">
                    Lesson <?php echo $lesson_number; ?>: <?php echo htmlspecialchars($lesson['title']); ?>
                    <?php if ($lesson['is_completed'] > 0): ?>
                        <i class="fa-solid fa-check-circle" style="color: green; margin-left: 10px;"></i>
                    <?php endif; ?>
                </li>
            <?php 
                $lesson_number++; 
            } 
            ?>
        </ul>
    </aside>
    
    <!-- Main Content (same structure as original, dynamically populated) -->
    <main class="content">
        <!-- Dynamic Lesson Content Sections -->
        <?php 
        // Reset lesson result pointer
        $lessons_result->data_seek(0);
        $lesson_number = 1;
        while ($lesson = $lessons_result->fetch_assoc()) { 
            ?>
            <div id="lesson<?php echo $lesson_number; ?>" 
                 class="lesson-container <?php echo $lesson_number == 1 ? 'active' : ''; ?>">
                <header class="lesson-header">
                    <h2 class="lesson-title"><?php echo htmlspecialchars($lesson['title']); ?></h2>
                    <p class="lesson-subtitle"><?php echo htmlspecialchars($lesson['title']); ?></p>
                </header>
                
                <div class="scrollable-content">
                    <!-- Lesson Content PDF -->
                    <section class="lesson-content">
                        <iframe class="content-iframe" 
                                src="backend/<?php echo htmlspecialchars($lesson['content']); ?>" 
                                title="Lesson Content"></iframe>
                    </section>
                    
                    <!-- Resources Section -->
                    <section class="resources-section">
                        <h3 class="section-title">Additional Resources</h3>
                        <table class="resources-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Fetch resources for this lesson
                                $resource_query = "SELECT * FROM lesson_links WHERE lesson_id = ?";
                                $stmt = $conn->prepare($resource_query);
                                $stmt->bind_param("i", $lesson['id']);
                                $stmt->execute();
                                $resources_result = $stmt->get_result();
                                
                                while ($resource = $resources_result->fetch_assoc()) { 
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($resource['title']); ?></td>
                                        <td><?php echo htmlspecialchars($resource['title']); ?></td>
                                        <td><a href="<?php echo htmlspecialchars($resource['url']); ?>" class="resource-link">
                                            <?php echo $resource['title'] == 'Website' ? 'Download' : 'Visit Website'; ?>
                                        </a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                    
                    <!-- Quiz Section -->
                    
                    <?php 
                        // In the quiz section, modify the quiz generation logic
                        $assignment_query = "SELECT a.id AS assignment_id, a.name AS assignment_name,
                                                    ulc.score, ulc.max_score, ulc.completed_at
                                            FROM assignments a
                                            LEFT JOIN user_lesson_completions ulc ON ulc.assignment_id = a.id 
                                            AND ulc.user_id = ?
                                            WHERE a.lesson_id = ?";
                        $stmt = $conn->prepare($assignment_query);
                        $stmt->bind_param("ii", $_SESSION['user_id'], $lesson['id']);
                        $stmt->execute();
                        $assignment_result = $stmt->get_result();

                        if ($assignment = $assignment_result->fetch_assoc()) {
                            $is_completed = !empty($assignment['completed_at']);
                            // Rest of the existing quiz generation code
                        ?>
                            <section class="quiz-section">
                                <h3 class="section-title">Knowledge Check</h3>
                                
                                <?php if ($is_completed) { ?>
                                    <div class="quiz-summary">
                                        <p>Quiz Completed on: <?php echo htmlspecialchars($assignment['completed_at']); ?></p>
                                        <p>Score: <?php echo htmlspecialchars($assignment['score']); ?> / <?php echo htmlspecialchars($assignment['max_score']); ?></p>
                                    </div>
                                <?php } ?>

                                <?php 
                                // Only show quiz if not already completed
                                if (!$is_completed) { 
                                    // Existing quiz generation code
                                ?>
                        
                        <?php 
                        // Fetch assignment for this lesson
                        $assignment_query = "SELECT a.id AS assignment_id, a.name AS assignment_name
                                            FROM assignments a
                                            WHERE a.lesson_id = ?";
                        $stmt = $conn->prepare($assignment_query);
                        $stmt->bind_param("i", $lesson['id']);
                        $stmt->execute();
                        $assignment_result = $stmt->get_result();
                        
                        if ($assignment = $assignment_result->fetch_assoc()) {
                            // Fetch questions for this assignment
                            $quiz_query = "SELECT q.id AS question_id, q.question_text, 
                                                o.id AS option_id, o.option_text, o.is_correct
                                        FROM assignment_questions q
                                        LEFT JOIN assignment_options o ON q.id = o.question_id
                                        WHERE q.assignment_id = ?
                                        ORDER BY q.id, o.id";
                            $stmt = $conn->prepare($quiz_query);
                            $stmt->bind_param("i", $assignment['assignment_id']);
                            $stmt->execute();
                            $quiz_result = $stmt->get_result();
                            
                            // Group questions and their options
                            $quiz_questions = [];
                            while ($row = $quiz_result->fetch_assoc()) {
                                $quiz_questions[$row['question_id']]['text'] = $row['question_text'];
                                $quiz_questions[$row['question_id']]['options'][] = $row;
                            }
                            
                            if (!empty($quiz_questions)) {
                        ?>
                            <div class="assignment-header">
                                <h4><?php echo htmlspecialchars($assignment['assignment_name']); ?></h4>
                            </div>

                            <?php foreach ($quiz_questions as $question_id => $question_data) { ?>
                                <div class="quiz-question">
                                    <p class="question-text"><?php echo htmlspecialchars($question_data['text']); ?></p>
                                    <ul class="options-list">
                                        <?php foreach ($question_data['options'] as $option) { ?>
                                            <li class="option-item">
                                                <label class="option-label">
                                                    <input type="radio" name="q<?php echo $question_id; ?>" 
                                                        value="<?php echo $option['option_id']; ?>">
                                                    <span class="option-text">
                                                        <?php echo htmlspecialchars($option['option_text']); ?>
                                                    </span>
                                                </label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            
                            <button class="submit-btn" 
                                    data-lesson-id="<?php echo $lesson['id']; ?>" 
                                    data-assignment-id="<?php echo $assignment['assignment_id']; ?>">
                                Submit Answers
                            </button>
                        <?php 
                            } 
                        } 
                        ?>
                         <?php } ?>
                    </section>
                    
                    
                </div>
            </div>
        <?php 
            $lesson_number++; 
        }} 
        ?>
    </main>

    <script>
     document.addEventListener('DOMContentLoaded', function() {
    // Lesson Navigation
    const lessonItems = document.querySelectorAll('.lesson-item');
    const lessonContainers = document.querySelectorAll('.lesson-container');
    
    lessonItems.forEach(item => {
        item.addEventListener('click', function() {
            // Get the lesson ID from the data attribute
            const lessonId = this.getAttribute('data-lesson');
            
            // Remove active class from all lessons
            lessonItems.forEach(li => li.classList.remove('active'));
            lessonContainers.forEach(container => container.classList.remove('active'));
            
            // Add active class to the clicked lesson
            this.classList.add('active');
            document.getElementById(lessonId).classList.add('active');
        });
    });

    // Quiz Functionality
    function setupQuizInteraction() {
        const quizSections = document.querySelectorAll('.quiz-section');
        
        quizSections.forEach(quizSection => {
            const submitButton = quizSection.querySelector('.submit-btn');
            const quizQuestions = quizSection.querySelectorAll('.quiz-question');
            
            // Check if quiz is already completed (from server-side rendering)
            const quizSummary = quizSection.querySelector('.quiz-summary');
            if (quizSummary) {
                // Quiz already completed, disable interactions
                if (submitButton) submitButton.remove();
                quizQuestions.forEach(question => {
                    question.querySelectorAll('input[type="radio"]').forEach(radio => {
                        radio.disabled = true;
                    });
                });
                return; // Skip further setup for this quiz section
            }

            // If no submit button, something went wrong
            if (!submitButton) return;

            // Radio button interaction
            quizQuestions.forEach(question => {
                const radioButtons = question.querySelectorAll('input[type="radio"]');
                
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Remove any previous selections in this question
                        radioButtons.forEach(r => r.closest('.option-item').classList.remove('selected'));
                        
                        // Mark the selected option
                        this.closest('.option-item').classList.add('selected');
                        
                        // Enable submit button if all questions are answered
                        checkQuizReadiness(quizSection);
                    });
                });
            });

            // Submit button setup
            submitButton.addEventListener('click', function(event) {
                event.preventDefault();

                // Prevent multiple submissions
                if (this.disabled) return;

                const lessonId = this.getAttribute('data-lesson-id');
                const assignmentId = this.getAttribute('data-assignment-id');
                const selectedAnswers = {};
                
                // Validate all questions are answered
                const unansweredQuestions = [];
                quizQuestions.forEach((question, index) => {
                    const selectedRadio = question.querySelector('input[type="radio"]:checked');
                    
                    if (!selectedRadio) {
                        unansweredQuestions.push(index + 1);
                        question.classList.add('unanswered');
                    } else {
                        selectedAnswers[selectedRadio.name] = selectedRadio.value;
                        question.classList.remove('unanswered');
                    }
                });

                // Check for unanswered questions
                if (unansweredQuestions.length > 0) {
                    alert(`Please answer all questions. Unanswered questions: ${unansweredQuestions.join(', ')}`);
                    return;
                }

                // Disable interaction during submission
                this.disabled = true;
                const originalButtonText = this.textContent;
                this.textContent = 'Submitting...';

                quizQuestions.forEach(question => {
                    question.querySelectorAll('input[type="radio"]').forEach(radio => {
                        radio.disabled = true;
                    });
                });

                // Submit quiz
                fetch('submit_quiz.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        lesson_id: lessonId,
                        assignment_id: assignmentId,
                        answers: selectedAnswers
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'An unknown error occurred');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Create and insert quiz summary
                        const summaryHtml = `
                            <div class="quiz-summary">
                                <h4>${data.passed ? 'Congratulations! 🎉' : 'Quiz Completed'}</h4>
                                <p>Score: ${data.score} / ${data.max_score}</p>
                                <p>Result: ${data.passed ? 'Passed ✅' : 'Not Passed ❌'}</p>
                            </div>
                        `;
                        
                        // Insert summary before the quiz questions
                        quizSection.insertAdjacentHTML('afterbegin', summaryHtml);

                        // Process detailed results
                        data.detailed_results.forEach((result, index) => {
                            const questionElement = quizQuestions[index];
                            
                            questionElement.classList.add('answered');
                            
                            questionElement.querySelectorAll('.option-item').forEach(optionItem => {
                                const radio = optionItem.querySelector('input[type="radio"]');
                                
                                if (!radio) return;
                                
                                // Remove previous classes
                                optionItem.classList.remove('correct-answer', 'incorrect-answer', 'user-selected');
                                
                                // Highlight user's selected answer
                                if (radio.value == result.user_selected.id) {
                                    optionItem.classList.add('user-selected');
                                    
                                    // Check if the selected answer was correct
                                    if (result.is_correct) {
                                        optionItem.classList.add('correct-answer');
                                    } else {
                                        optionItem.classList.add('incorrect-answer');
                                    }
                                }
                                
                                // Permanently disable radio buttons
                                radio.disabled = true;
                            });
                        });

                        // Remove submit button
                        this.remove();
                    } else {
                        throw new Error(data.message || 'Quiz submission failed');
                    }
                })
                .catch(error => {
                    console.error('Quiz Submission Error:', error);
                    alert(`Submission Error: ${error.message}`);
                    
                    // Re-enable inputs if there's an error
                    quizQuestions.forEach(question => {
                        question.querySelectorAll('input[type="radio"]').forEach(radio => {
                            radio.disabled = false;
                        });
                    });

                    // Restore original button state
                    this.disabled = false;
                    this.textContent = originalButtonText;
                });
            });
        });

        // Helper function to check quiz readiness
        function checkQuizReadiness(quizSection) {
            const submitButton = quizSection.querySelector('.submit-btn');
            const quizQuestions = quizSection.querySelectorAll('.quiz-question');
            
            const allQuestionsAnswered = Array.from(quizQuestions).every(question => 
                question.querySelector('input[type="radio"]:checked')
            );

            if (submitButton) {
                submitButton.disabled = !allQuestionsAnswered;
            }
        }
    }

    // Initial setup of quiz interactions
    setupQuizInteraction();

    // Optional: Re-run setup if dynamic content is loaded
    // Useful for single-page applications or dynamic content
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                setupQuizInteraction();
            }
        });
    });

    // Observe the entire document for added nodes
    observer.observe(document.body, { 
        childList: true, 
        subtree: true 
    });
});
    </script>
</body>
</html>
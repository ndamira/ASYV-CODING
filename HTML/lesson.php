<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="myCourse.php"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h1 class="course-title">Web Development 101</h1>
                <p class="course-info">12 Lessons </p>
            </div>
        </div>
        
        <ul class="lesson-list">
            <li class="lesson-item active" data-lesson="lesson1">Lesson 1: Introduction to HTML</li>
            <li class="lesson-item" data-lesson="lesson2">Lesson 2: CSS Fundamentals</li>
            <li class="lesson-item" data-lesson="lesson3">Lesson 3: JavaScript Basics</li>
            <li class="lesson-item" data-lesson="lesson4">Lesson 4: Responsive Design</li>
            <li class="lesson-item" data-lesson="lesson5">Lesson 5: CSS Frameworks</li>
            <li class="lesson-item" data-lesson="lesson6">Lesson 6: JavaScript DOM Manipulation</li>
            <li class="lesson-item" data-lesson="lesson7">Lesson 7: Forms and Validation</li>
            <li class="lesson-item" data-lesson="lesson8">Lesson 8: API Integration</li>
            <li class="lesson-item" data-lesson="lesson9">Lesson 9: Local Storage</li>
            <li class="lesson-item" data-lesson="lesson10">Lesson 10: Optimization Techniques</li>
            <li class="lesson-item" data-lesson="lesson11">Lesson 11: Deployment</li>
            <li class="lesson-item" data-lesson="lesson12">Lesson 12: Final Project</li>
        </ul>
    </aside>
    
    <!-- Main Content -->
    <main class="content">
        <!-- Lesson 1 Content -->
        <div id="lesson1" class="lesson-container active">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 1: Introduction to HTML</h2>
                <p class="lesson-subtitle">Learn the basics of HTML structure and elements</p>
            </header>
            
            <div class="scrollable-content">
                <section class="lesson-content">
                    <iframe class="content-iframe" src="../lessons/Introduction to HTML && CSS/Lesson 1.pdf" alt="PDF Content Placeholder" title="Lesson Content"></iframe>
                </section>
                
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
                            <tr>
                                <td>HTML5 Cheat Sheet</td>
                                <td>PDF</td>
                                <td><a href="#" class="resource-link">Download</a></td>
                            </tr>
                            <tr>
                                <td>HTML Reference Guide</td>
                                <td>Website</td>
                                <td><a href="#" class="resource-link">Visit Website</a></td>
                            </tr>
                            <tr>
                                <td>HTML Practice Exercises</td>
                                <td>ZIP</td>
                                <td><a href="#" class="resource-link">Download</a></td>
                            </tr>
                            <tr>
                                <td>Video Tutorial: HTML Basics</td>
                                <td>Video</td>
                                <td><a href="#" class="resource-link">Watch Video</a></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                
                <section class="quiz-section">
                    <h3 class="section-title">Knowledge Check</h3>
                    
                    <div class="quiz-question">
                        <p class="question-text">1. Which tag is used to define the main content of an HTML document?</p>
                        <ul class="options-list">
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1" value="a">
                                    <span class="option-text">&lt;main&gt;</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1" value="b">
                                    <span class="option-text">&lt;body&gt;</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1" value="c">
                                    <span class="option-text">&lt;content&gt;</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1" value="d">
                                    <span class="option-text">&lt;article&gt;</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="quiz-question">
                        <p class="question-text">2. Which HTML element is used to create a hyperlink?</p>
                        <ul class="options-list">
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q2" value="a">
                                    <span class="option-text">&lt;link&gt;</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q2" value="b">
                                    <span class="option-text">&lt;a&gt;</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q2" value="c">
                                    <span class="option-text">&lt;href&gt;</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q2" value="d">
                                    <span class="option-text">&lt;hyperlink&gt;</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    
                    <button class="submit-btn">Submit Answers</button>
                </section>
            </div>
        </div>
        
        <!-- Lesson 2 Content -->
        <div id="lesson2" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 2: CSS Fundamentals</h2>
                <p class="lesson-subtitle">Learn how to style HTML elements with CSS</p>
            </header>
            
            <div class="scrollable-content">
                <section class="lesson-content">
                    <iframe class="content-iframe" src="../lessons/Introduction to HTML && CSS/Lesson2.pdf" alt="PDF Content Placeholder" title="Lesson Content"></iframe>
                </section>
                
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
                            <tr>
                                <td>CSS Cheat Sheet</td>
                                <td>PDF</td>
                                <td><a href="#" class="resource-link">Download</a></td>
                            </tr>
                            <tr>
                                <td>CSS Reference Guide</td>
                                <td>Website</td>
                                <td><a href="#" class="resource-link">Visit Website</a></td>
                            </tr>
                            <tr>
                                <td>CSS Practice Exercises</td>
                                <td>ZIP</td>
                                <td><a href="#" class="resource-link">Download</a></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                
                <section class="quiz-section">
                    <h3 class="section-title">Knowledge Check</h3>
                    
                    <div class="quiz-question">
                        <p class="question-text">1. Which CSS property is used to change the text color?</p>
                        <ul class="options-list">
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1_css" value="a">
                                    <span class="option-text">text-color</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1_css" value="b">
                                    <span class="option-text">color</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1_css" value="c">
                                    <span class="option-text">font-color</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    
                    <button class="submit-btn">Submit Answers</button>
                </section>
            </div>
        </div>
        
        <!-- Lesson 3 Content -->
        <div id="lesson3" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 3: JavaScript Basics</h2>
                <p class="lesson-subtitle">Learn the fundamentals of JavaScript programming</p>
            </header>
            
            <div class="scrollable-content">
                <section class="lesson-content">
                    <iframe class="content-iframe" src="/api/placeholder/800/500" alt="PDF Content Placeholder" title="Lesson Content"></iframe>
                </section>
                
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
                            <tr>
                                <td>JavaScript Cheat Sheet</td>
                                <td>PDF</td>
                                <td><a href="#" class="resource-link">Download</a></td>
                            </tr>
                            <tr>
                                <td>JavaScript Reference</td>
                                <td>Website</td>
                                <td><a href="#" class="resource-link">Visit Website</a></td>
                            </tr>
                            <tr>
                                <td>JavaScript Practice Exercises</td>
                                <td>ZIP</td>
                                <td><a href="#" class="resource-link">Download</a></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                
                <section class="quiz-section">
                    <h3 class="section-title">Knowledge Check</h3>
                    
                    <div class="quiz-question">
                        <p class="question-text">1. Which function is used to print content to the console?</p>
                        <ul class="options-list">
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1_js" value="a">
                                    <span class="option-text">console.log()</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1_js" value="b">
                                    <span class="option-text">print()</span>
                                </label>
                            </li>
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio" name="q1_js" value="c">
                                    <span class="option-text">console.print()</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    
                    <button class="submit-btn">Submit Answers</button>
                </section>
            </div>
        </div>
        
        <!-- Add more lesson containers as needed -->
        
        <!-- Lessons 4-12 placeholders -->
        <div id="lesson4" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 4: Responsive Design</h2>
                <p class="lesson-subtitle">Learn how to create responsive websites</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 4 would go here...</p>
            </div>
        </div>
        
        <div id="lesson5" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 5: CSS Frameworks</h2>
                <p class="lesson-subtitle">Learn how to use popular CSS frameworks</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 5 would go here...</p>
            </div>
        </div>
        
        <div id="lesson6" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 6: JavaScript DOM Manipulation</h2>
                <p class="lesson-subtitle">Learn how to manipulate the DOM with JavaScript</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 6 would go here...</p>
            </div>
        </div>
        
        <div id="lesson7" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 7: Forms and Validation</h2>
                <p class="lesson-subtitle">Learn how to create and validate forms</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 7 would go here...</p>
            </div>
        </div>
        
        <div id="lesson8" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 8: API Integration</h2>
                <p class="lesson-subtitle">Learn how to integrate with APIs</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 8 would go here...</p>
            </div>
        </div>
        
        <div id="lesson9" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 9: Local Storage</h2>
                <p class="lesson-subtitle">Learn how to use browser storage</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 9 would go here...</p>
            </div>
        </div>
        
        <div id="lesson10" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 10: Optimization Techniques</h2>
                <p class="lesson-subtitle">Learn how to optimize your web applications</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 10 would go here...</p>
            </div>
        </div>
        
        <div id="lesson11" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 11: Deployment</h2>
                <p class="lesson-subtitle">Learn how to deploy your web applications</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 11 would go here...</p>
            </div>
        </div>
        
        <div id="lesson12" class="lesson-container">
            <header class="lesson-header">
                <h2 class="lesson-title">Lesson 12: Final Project</h2>
                <p class="lesson-subtitle">Apply what you've learned in a final project</p>
            </header>
            <div class="scrollable-content">
                <p>Content for Lesson 12 would go here...</p>
            </div>
        </div>
    </main>

    <script>
        // JavaScript for lesson navigation
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Quiz submission handling
            const submitButtons = document.querySelectorAll('.submit-btn');
            submitButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Simple alert for demonstration
                    alert('Answers submitted! This would be connected to a grading system in a real application.');
                });
            });
        });
    </script>
</body>
</html>
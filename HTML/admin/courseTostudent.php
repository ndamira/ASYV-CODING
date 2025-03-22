<?php
// Database connection configuration
$host = "localhost";
$dbname = "newplatform";
$username = "root";
$password = "";

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to check if a course allocation already exists
function isAllocationExists($pdo, $user_id, $course_id) {
    $stmt = $pdo->prepare("SELECT id FROM user_courses WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    return $stmt->rowCount() > 0;
}

// Function to allocate a course to a user
function allocateCourse($pdo, $user_id, $course_id) {
    // Check if the allocation already exists
    if (isAllocationExists($pdo, $user_id, $course_id)) {
        return ["success" => false, "message" => "This course is already allocated to this student."];
    }
    
    // Check if the user exists
    $userStmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $userStmt->execute([$user_id]);
    if ($userStmt->rowCount() == 0) {
        return ["success" => false, "message" => "Student does not exist."];
    }
    
    // Check if the course exists
    $courseStmt = $pdo->prepare("SELECT id FROM courses WHERE id = ?");
    $courseStmt->execute([$course_id]);
    if ($courseStmt->rowCount() == 0) {
        return ["success" => false, "message" => "Course does not exist."];
    }
    
    // Perform the allocation
    $stmt = $pdo->prepare("INSERT INTO user_courses (user_id, course_id, allocation_date) VALUES (?, ?, NOW())");
    $result = $stmt->execute([$user_id, $course_id]);
    
    if ($result) {
        return ["success" => true, "message" => "Course allocated successfully."];
    } else {
        return ["success" => false, "message" => "Failed to allocate course."];
    }
}

// Function to update a course allocation
function updateCourseAllocation($pdo, $allocation_id, $new_course_id) {
    // Get current user_id from allocation
    $stmt = $pdo->prepare("SELECT user_id FROM user_courses WHERE id = ?");
    $stmt->execute([$allocation_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        return ["success" => false, "message" => "Allocation not found."];
    }
    
    $user_id = $result['user_id'];
    
    // Check if the new allocation would create a duplicate
    if (isAllocationExists($pdo, $user_id, $new_course_id)) {
        return ["success" => false, "message" => "Student is already allocated to this course."];
    }
    
    // Check if the course exists
    $courseStmt = $pdo->prepare("SELECT id FROM courses WHERE id = ?");
    $courseStmt->execute([$new_course_id]);
    if ($courseStmt->rowCount() == 0) {
        return ["success" => false, "message" => "New course does not exist."];
    }
    
    // Update the allocation
    $updateStmt = $pdo->prepare("UPDATE user_courses SET course_id = ?, updated_at = NOW() WHERE id = ?");
    $result = $updateStmt->execute([$new_course_id, $allocation_id]);
    
    if ($result) {
        return ["success" => true, "message" => "Course allocation updated successfully."];
    } else {
        return ["success" => false, "message" => "Failed to update course allocation."];
    }
}

// Function to unassign a course from a user
function unassignCourse($pdo, $user_id, $course_id) {
    if (!isAllocationExists($pdo, $user_id, $course_id)) {
        return ["success" => false, "message" => "This allocation does not exist."];
    }
    
    $stmt = $pdo->prepare("DELETE FROM user_courses WHERE user_id = ? AND course_id = ?");
    $result = $stmt->execute([$user_id, $course_id]);
    
    if ($result) {
        return ["success" => true, "message" => "Course unassigned successfully."];
    } else {
        return ["success" => false, "message" => "Failed to unassign course."];
    }
}

// Function to get all courses allocated to a specific user
function getUserCourses($pdo, $user_id) {
    $stmt = $pdo->prepare("
        SELECT c.id, c.course_name, c.course_code, uc.allocation_date 
        FROM courses c 
        JOIN user_courses uc ON c.id = uc.course_id 
        WHERE uc.user_id = ?
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all users allocated to a specific course
function getCourseUsers($pdo, $course_id) {
    $stmt = $pdo->prepare("
        SELECT u.id, u.name, u.email, uc.allocation_date 
        FROM users u 
        JOIN user_courses uc ON u.id = uc.user_id 
        WHERE uc.course_id = ?
    ");
    $stmt->execute([$course_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle different form actions
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        // Allocate course
        if ($action == 'allocate') {
            $user_id = $_POST['user_id'];
            $course_id = $_POST['course_id'];
            $result = allocateCourse($pdo, $user_id, $course_id);
            $message = $result['message'];
            $success = $result['success'];
        }
        
        // Update allocation
        else if ($action == 'update') {
            $allocation_id = $_POST['allocation_id'];
            $new_course_id = $_POST['new_course_id'];
            $result = updateCourseAllocation($pdo, $allocation_id, $new_course_id);
            $message = $result['message'];
            $success = $result['success'];
        }
        
        // Unassign course
        else if ($action == 'unassign') {
            $user_id = $_POST['user_id'];
            $course_id = $_POST['course_id'];
            $result = unassignCourse($pdo, $user_id, $course_id);
            $message = $result['message'];
            $success = $result['success'];
        }
    }
}

// Get list of users and courses for dropdown menus
$usersStmt = $pdo->query("SELECT id, first_name, email FROM users ORDER BY first_name");
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

$coursesStmt = $pdo->query("SELECT id, name, name FROM courses ORDER BY name");
$courses = $coursesStmt->fetchAll(PDO::FETCH_ASSOC);

// Get list of current allocations
$allocationsStmt = $pdo->query("
    SELECT uc.id, u.first_name as user_name, c.name, uc.user_id, uc.course_id, uc.allocation_date
    FROM user_courses uc
    JOIN users u ON uc.user_id = u.id
    JOIN courses c ON uc.course_id = c.id
    ORDER BY u.first_name, c.name
");
$allocations = $allocationsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASYV-Coding</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .container {
            max-width: 1200px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Course Allocation</h1>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Allocate Course Form -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Allocate Course to Student</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="action" value="allocate">
                            
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Select Student</label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    <option value="">-- Select Student --</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>">
                                            <?php echo htmlspecialchars($user['first_name']) . ' (' . htmlspecialchars($user['email']) . ')'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Select Course</label>
                                <select name="course_id" id="course_id" class="form-select" required>
                                    <option value="">-- Select Course --</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?php echo $course['id']; ?>">
                                            <?php echo htmlspecialchars($course['name']) . ' (' . htmlspecialchars($course['name']) . ')'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Allocate Course</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Update Allocation Form -->
            <!-- <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Update Course Allocation</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="action" value="update">
                            
                            <div class="mb-3">
                                <label for="allocation_id" class="form-label">Select Allocation to Update</label>
                                <select name="allocation_id" id="allocation_id" class="form-select" required>
                                    <option value="">-- Select Allocation --</option>
                                    <?php foreach ($allocations as $allocation): ?>
                                        <option value="<?php echo $allocation['id']; ?>">
                                            <?php echo htmlspecialchars($allocation['name']) . ' - ' . htmlspecialchars($allocation['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_course_id" class="form-label">Select New Course</label>
                                <select name="new_course_id" id="new_course_id" class="form-select" required>
                                    <option value="">-- Select New Course --</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?php echo $course['id']; ?>">
                                            <?php echo htmlspecialchars($course['name']) . ' (' . htmlspecialchars($course['name']) . ')'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-warning">Update Allocation</button>
                        </form>
                    </div>
                </div>
            </div> -->
        </div>
        
        <!-- Unassign Course Form -->
        <!-- <div class="card">
            <div class="card-header">
                <h5>Unassign Course</h5>
            </div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="unassign">
                    
                    <div class="col-md-5">
                        <label for="user_id_unassign" class="form-label">Select Student</label>
                        <select name="user_id" id="user_id_unassign" class="form-select" required>
                            <option value="">-- Select Student --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo htmlspecialchars($user['first_name']) . ' (' . htmlspecialchars($user['email']) . ')'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-5">
                        <label for="course_id_unassign" class="form-label">Select Course</label>
                        <select name="course_id" id="course_id_unassign" class="form-select" required>
                            <option value="">-- Select Course --</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['name']) . ' (' . htmlspecialchars($course['name']) . ')'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger">Unassign Course</button>
                    </div>
                </form>
            </div>
        </div> -->
        
        <!-- Current Allocations Table -->
        <div class="card">
            <div class="card-header">
                <h5>Current Course Allocations</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Course Name</th>
                                <th>Allocation Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($allocations) > 0): ?>
                                <?php foreach ($allocations as $allocation): ?>
                                    <tr>
                                        <td><?php echo $allocation['id']; ?></td>
                                        <td><?php echo htmlspecialchars($allocation['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($allocation['name']); ?></td>
                                        <td><?php echo $allocation['allocation_date']; ?></td>
                                        <td>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="unassign">
                                                <input type="hidden" name="user_id" value="<?php echo $allocation['user_id']; ?>">
                                                <input type="hidden" name="course_id" value="<?php echo $allocation['course_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Unassign</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No allocations found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
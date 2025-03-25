<?php
// Database connection
include '../backend/conn.php'; // Your database connection file

// Handle Insert
if (isset($_POST['insert'])) {
    $grade_name = mysqli_real_escape_string($conn, $_POST['grade_name']);
    $query = "INSERT INTO grades (name) VALUES ('$grade_name')";
    if (mysqli_query($conn, $query)) {
        echo "Grade inserted successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle Update
if (isset($_POST['update'])) {
    $grade_id = $_POST['grade_id'];
    $grade_name = mysqli_real_escape_string($conn, $_POST['grade_name']);
    $query = "UPDATE grades SET name='$grade_name' WHERE id='$grade_id'";
    if (mysqli_query($conn, $query)) {
        echo "Grade updated successfully.";
        header('location:grades.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $grade_id = $_GET['delete'];
    $query = "DELETE FROM grades WHERE id='$grade_id'";
    if (mysqli_query($conn, $query)) {
        echo "Grade deleted successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch all grades for display
$query = "SELECT * FROM grades";
$result = mysqli_query($conn, $query);

// Set default action to insert
$action = 'insert';

// Check if an edit request is made
if (isset($_GET['edit'])) {
    $grade_id = $_GET['edit'];
    $query = "SELECT * FROM grades WHERE id='$grade_id'";
    $edit_result = mysqli_query($conn, $query);
    $edit_grade = mysqli_fetch_assoc($edit_result);
    $action = 'update'; // Switch to update mode
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"] {
            padding: 8px;
            margin: 10px 0;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 5px 10px;
            margin: 0 5px;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #FF5733;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h1>Grade Management</h1>

<!-- Form for Insert/Update -->
<form method="POST" action="">
    <label for="grade_name">Grade Name:</label>
    <input type="text" id="grade_name" name="grade_name" value="<?php echo isset($edit_grade) ? htmlspecialchars($edit_grade['name']) : ''; ?>" required>

    <!-- Hidden field for Update -->
    <input type="hidden" name="grade_id" id="grade_id" value="<?php echo isset($edit_grade) ? $edit_grade['id'] : ''; ?>">

    <?php if ($action === 'insert'): ?>
        <input type="submit" name="insert" value="Insert Grade">
    <?php elseif ($action === 'update'): ?>
        <input type="submit" name="update" value="Update Grade">
    <?php endif; ?>
</form>

<!-- Grades Table -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Grade Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <a href="grades.php?delete=<?php echo $row['id']; ?>" class="btn delete-btn">Delete</a>
                    <a href="grades.php?edit=<?php echo $row['id']; ?>" class="btn edit-btn">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

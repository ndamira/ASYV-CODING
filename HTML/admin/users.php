<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.php");
  exit();
}

include('../backend/conn.php'); // Ensure this file contains your DB connection

// Fetch only approved users from the database
$query = "SELECT * FROM users WHERE status = 'Approved'";
$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Users</title>
    <link rel="stylesheet" href="../../CSS/users.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <body>
  <aside id="sidebar">
      <ul>
        <li>
          <img src="../../IMG/Designer.png" alt="" />
          <!-- <span class="logo">ASYV CODING</span>
                <button id="toggle-btn" onclick="toggleSidebar()">
                    <i class="fa-solid fa-angles-left"></i>
                </button> -->
        </li>
        <li>
          <a href="index.php">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <button class="dropdown-btn" onclick="toggleSubMenu(this)">
            <i class="fa-solid fa-users"></i>
            <span>Users</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>
          <ul class="sub-menu">
            <div>
              <li class="active"><a href="users.php">Registered Users</a></li>
              <li><a href="addusers.php">Add User</a></li>
            </div>
          </ul>
        </li>
        <li>
        <button class="dropdown-btn" onclick="toggleSubMenu(this)">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Course</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>
          <ul class="sub-menu">
            <div>
              <li><a href="courses.php">Add Course</a></li>
              <li><a href="../admin/courseTostudent.php">Course Allocation</a></li>
            </div>
          </ul>
        </li>
        <li>
          <a href="../logout.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </aside>
    <main>
      <h2>Registered Users</h2>
      <div class="table">
        <div class="delete" id="delete">
          <div class="content">
            <div class="title">
              <h2>Delete User</h2>
              <i class="fa-solid fa-xmark" onclick="closeDelete()"></i>
            </div>
            <p>Are sure you want to delete this record?</p>
            <form action="backend.php" method="Post">
              <input type="hidden" id="recordId" name="recordId" />
              <div class="btn">
                <button type="submit" class="btn2" name="delete">
                  Yes, delete it
                </button>
              </div>
            </form>
          </div>
        </div>
        <table>
          <thead>
            <tr>
              <td>Id</td>
              <td>First Name</td>
              <td>Last Name</td>
              <td>Email</td>
              <td>Grade</td>
              <td>Role</td>
              <td>Status</td>
              <td>Progress</td>
              <td class="last">Action</td>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['first_name']) ?></td>
              <td><?= htmlspecialchars($row['last_name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td>EY</td>
              <td><?= htmlspecialchars($row['role']) ?></td>
              <td>
                <button class="approved">Approved</button>
              </td>
              <td>
                <button class="approved">View more</button>
              </td>
              <td>
                <button class="delete-btn" onclick="deleteUser(<?= $row['id'] ?>)">Delete</button>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
    <script src="../../JS/users.js"></script>
    <script>
      function deleteUser(recordId) {
          if (confirm("Are you sure you want to delete this user?")) {
              window.location.href = `delete_user.php?id=${recordId}`;
          }
      }
    </script>
  </body>
</html>

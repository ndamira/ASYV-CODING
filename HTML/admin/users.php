<?php
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
  <?php include('sidebar.php'); ?>
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
              <td>Role</td>
              <td>Status</td>
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
              <td><?= htmlspecialchars($row['role']) ?></td>
              <td>
                <button class="approved">Approved</button>
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

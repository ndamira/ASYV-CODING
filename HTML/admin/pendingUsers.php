<?php
// Database connection
include('../backend/conn.php');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch pending users
$sql = "SELECT id, first_name, last_name, email, role, status FROM users WHERE status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pending Users</title>
    <link rel="stylesheet" href="../../CSS/pendingUsers.css" />
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
      <h2>Pending Users</h2>
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
        <div class="approve" id="approve">
            <div class="content">
                <div class="title">
                    <h2>Approve User</h2>
                    <i class="fa-solid fa-xmark" onclick="closeApprove()"></i>
                </div>
                <p>This user is currently not approved. Do you want to approve?</p>
                <form action="../backend/approveUser.php" method="POST">
                    <input type="hidden" id="approveUserId" name="userId">
                    <div class="btn">
                        <button type="submit" class="btn2">Yes, Approve</button>
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
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['role']}</td>
                                <td>
                                    <button class='pending' onclick='approveUser({$row['id']})'>Pending</button>
                                </td>
                                <td>
                                    <button onclick='deleteUser({$row['id']})'>Delete</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No pending users</td></tr>";
                }
                ?>
            </tbody>
        </table>
      </div>
    </main>
    <script src="../../JS/pendingUsers.js"></script>
  </body>
</html>

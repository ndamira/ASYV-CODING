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
    <!-- <link rel="stylesheet" href="../../CSS/pendingUsers.css" /> -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: poppins, sans-serif;
        line-height: 1.5rem;
      }

      :root {
        --base-clr: #11121a;
        --line-clr: #42434a;
        --hover-clr: #222533;
        --text-clr: #e6e6ef;
        --accent-clr: rgb(75, 139, 102);
        --secondary-text-clr: #b0b3b1;
      }

      body {
        min-height: 100vh;
        min-height: 100dvh;
        background-color: var(--base-clr);
        color: var(--text-clr);
        display: grid;
        grid-template-columns: auto 1fr;
      }

      #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 250px;
        padding: 5px 1em;
        background-color: var(--accent-clr);
        border-right: 1px solid var(--line-clr);
        transition: 300ms ease-in-out;
        overflow-x: hidden;
        overflow-y: auto;
        z-index: 1000;
        transform: translateX(-100%);
      }

      #sidebar.open {
        transform: translateX(0);
      }

      #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 999;
      }

      #overlay.show {
        display: block;
      }

      #mobile-toggle {
        position: fixed;
        top: 15px;
        left: 15px;
        background: var(--accent-clr);
        border: none;
        color: var(--text-clr);
        padding: 10px;
        border-radius: 5px;
        z-index: 1001;
        display: none;
        cursor: pointer;
      }

      #sidebar ul {
        list-style: none;
      }

      #sidebar > ul > li:first-child {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 16px;
      }

      #sidebar > ul > li:first-child img {
        width: 4em;
        height: 4em;
        cursor: pointer;
        margin-left: auto;
        margin-right: auto;
      }

      #sidebar ul li.active a {
        color: var(--base-clr);
        font-weight: bold;
      }

      #sidebar ul li.active a:hover {
        color: var(--text-clr);
      }

      #sidebar a,
      #sidebar .dropdown-btn,
      #sidebar .logo {
        border-radius: 0.5em;
        padding: 0.85em;
        text-decoration: none;
        color: var(--text-clr);
        display: flex;
        align-items: center;
        gap: 1em;
      }

      .dropdown-btn {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        font: inherit;
        cursor: pointer;
      }

      #sidebar i {
        flex-shrink: 0;
      }

      #sidebar a span,
      #sidebar .dropdown-btn span {
        flex-grow: 1;
      }

      #sidebar a:hover,
      #sidebar .dropdown-btn:hover {
        background-color: var(--hover-clr);
      }

      #sidebar .sub-menu {
        display: grid;
        grid-template-rows: 1fr;
        transition: 300ms ease-in-out;
      }

      #sidebar .sub-menu > div {
        overflow: hidden;
      }

      #sidebar .sub-menu.show {
        grid-template-rows: 0fr;
      }

      .dropdown-btn i:last-child {
        transition: 200ms ease;
      }

      .rotate i:last-child {
        rotate: 180deg;
      }

      #sidebar .sub-menu a {
        padding-left: 2em;
      }

      main {
        padding: min(30px, 7%);
        background: var(--hover-clr);
        width: 100%;
      }

      /* Responsive Breakpoints */
      @media screen and (max-width: 768px) {
        body {
          grid-template-columns: 1fr;
        }

        #mobile-toggle {
          display: block;
        }

        #sidebar {
          width: 250px;
          max-width: 80%;
        }

        main {
          padding: 15px;
        }
      }

      @media screen and (min-width: 769px) {
        #sidebar {
          position: sticky;
          transform: translateX(0);
        }

        #mobile-toggle {
          display: none;
        }
      }

      /* -------------------------------------------Add User Form Styles------------------------------------- */

      :root {
      --base-clr: #11121a;
      --line-clr: #42434a;
      --hover-clr: #222533;
      --text-clr: #e6e6ef;
      --accent-clr: rgb(75, 139, 102);
      --secondary-text-clr: #b0b3b1;
    }

    .container {
      background-color: var(--text-clr);
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 25px;
      max-width: 800px;
      margin: 0 auto;
    }

    .header {
      margin-bottom: 25px;
      border-bottom: 1px solid #eee;
      padding-bottom: 15px;
    }

    .header h1 {
      font-size: 24px;
      color: var(--accent-clr);
      margin-bottom: 5px;
    }

    .header p {
      color: #666;
      font-size: 14px;
    }

    .form-container {
      padding: 10px 0;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #444;
    }

    .form-group input, 
    .form-group select {
      width: 97%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
      transition: border 0.3s;
    }

    .form-group input:focus, 
    .form-group select:focus {
      border-color: #4a90e2;
      outline: none;
      box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 30px;
    }

    .reset-btn, .submit-btn {
      padding: 10px 20px;
      border-radius: 4px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
    }

    .reset-btn {
      background-color: #f2f2f2;
      color: #666;
      border: 1px solid #ddd;
    }

    .submit-btn {
      background-color: var(--accent-clr);
      color: white;
      border: none;
    }

    .reset-btn:hover {
      background-color: #e6e6e6;
    }

    .submit-btn:hover {
      background-color: #3a7bc8;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      main {
        margin-left: 0;
        padding: 15px;
      }
      
      .container {
        padding: 15px;
      }
      
      .form-actions {
        flex-direction: column;
      }
      
      .reset-btn, .submit-btn {
        width: 100%;
      }
    }
    </style>
  </head>
  <body>
    <button id="mobile-toggle" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div id="overlay" onclick="toggleSidebar()"></div>
    <aside id="sidebar">
      <ul>
        <li>
          <img src="../../IMG/Designer.png" alt="" />
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
              <li><a href="registeredusers.php">Registered Users</a></li>
              <li class="active"><a href="addusers.php">Add User</a></li>
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
    <div class="container">
      <div class="header">
        <h1>Add User</h1>
        <p>Create a new user account</p>
      </div>
      
      <div class="form-container">
        <form action="../backend/add_user_process.php" method="POST">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>
          </div>
          
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>
          </div>
          
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
          </div>
          
          <div class="form-group">
            <label for="grade">Grade</label>
            <input type="text" id="grade" name="grade" required>
          </div>
          
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
          </div>
          
          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
          </div>
          
          <div class="form-actions">
            <button type="reset" class="reset-btn">Reset</button>
            <button type="submit" class="submit-btn">Add User</button>
          </div>
        </form>
      </div>
    </div>







      <!-- <h2>Pending Users</h2>
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
      </div> -->
    </main>
    <!-- <script src="../../JS/pendingUsers.js"></script> -->
     <script>
      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const mobileToggle = document.getElementById("mobile-toggle");

        sidebar.classList.toggle("open");
        overlay.classList.toggle("show");
        mobileToggle.classList.toggle("rotate");

        // Close all submenus when sidebar is closed
        if (!sidebar.classList.contains("open")) {
          Array.from(sidebar.getElementsByClassName("show")).forEach((ul) => {
            ul.classList.remove("show");
            ul.previousElementSibling.classList.remove("rotate");
          });
        }
      }

      function toggleSubMenu(button) {
        button.nextElementSibling.classList.toggle("show");
        button.classList.toggle("rotate");

        // Ensure sidebar is open on mobile when submenu is toggled
        const sidebar = document.getElementById("sidebar");
        if (!sidebar.classList.contains("open")) {
          toggleSidebar();
        }
      }

      // Close sidebar when clicking outside on mobile
      document.addEventListener("click", function (event) {
        const sidebar = document.getElementById("sidebar");
        const mobileToggle = document.getElementById("mobile-toggle");
        const overlay = document.getElementById("overlay");

        if (
          window.innerWidth <= 768 &&
          sidebar.classList.contains("open") &&
          !sidebar.contains(event.target) &&
          !mobileToggle.contains(event.target)
        ) {
          toggleSidebar();
        }
      });
     </script>
  </body>
</html>

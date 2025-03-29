<?php 
  session_start();
  // Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
header("Location: index.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="icon" type="image/x-icon" href="../IMG/Designer.png">
  </head>
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
        height: 100dvh;
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
        grid-template-rows: 0fr;
        transition: 300ms ease-in-out;
      }

      #sidebar .sub-menu > div {
        overflow: hidden;
      }

      #sidebar .sub-menu.show {
        grid-template-rows: 1fr;
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

    /******************************** CONTENT *************************/

      main {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
      }

      /* Title Section */
      .title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
      }

      .title .leftSide h2 {
        color: var(--accent-clr);
        margin-bottom: 5px;
      }

      .title .leftSide p {
        color: var(--secondary-text-clr);
      }

      .title .rightSide button {
        background-color: var(--accent-clr);
        color: var(--text-clr);
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      .title .rightSide button:hover {
        background-color: color-mix(
          in srgb,
          var(--accent-clr) 80%,
          var(--text-clr)
        );
      }

      /* Overview Cards */
      .overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
      }

      .card {
        background-color: var(--text-clr);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
      }

      .card:hover {
        transform: translateY(-5px);
      }

      .card .content {
        display: flex;
        align-items: center;
        padding: 20px;
        gap: 20px;
        color: var(--accent-clr);
      }

      .card .content i {
        color: var(--accent-clr);
        min-width: 60px;
        text-align: center;
      }

      .card .numbers h2 {
        font-size: 1.2rem;
        margin-bottom: 5px;
      }

      .card .numbers p {
        color: var(--accent-clr);
        font-size: 1.5rem;
        font-weight: bold;
      }

      .card a{
        text-decoration: none;
        color: var(--base-clr);
      }

      .card .details {
        background-color: color-mix(in srgb, var(--card-bg) 90%, white);
        padding: 10px 20px;
        /* text-align: right; */
      }

      .card .details a {
        text-decoration: none;
        /* color: var(--accent-clr); */
        text-decoration: none;
      }

      /* Charts Section */
      .charts {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
      }

      .box {
        position: relative;
        background-color: var(--text-clr);
        padding: 10px;
        box-shadow: 0 7px 25px rgba(0, 0, 0, 0.5);
        border-radius: 10px;
      }

      /* Responsive Adjustments */
      @media screen and (max-width: 1024px) {
        .charts {
          grid-template-columns: 1fr;
        }
      }

      @media screen and (max-width: 768px) {
        .title {
          flex-direction: column;
          align-items: flex-start;
        }

        .title .rightSide {
          width: 100%;
        }

        .title .rightSide button {
          width: 100%;
        }

        .overview {
          grid-template-columns: 1fr;
        }
      }
  </style>
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
        <li class="active">
          <a href="dashboard.php">
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
      <div class="title">
        <div class="leftSide">
          <h2>Dashboard</h2>
          <p>A quick data overview</p>
        </div>
        <div class="rightSide">
          <button>Download Report</button>
        </div>
      </div>
      <div class="overview">
        <div class="card">
          <div class="content">
            <i class="fa-solid fa-users fa-3x"></i>
            <div class="numbers">
              <h2>Total Users</h2>
              <p>30</p>
            </div>
          </div>
          <a href="">
            <div class="details">
              <p>View Details</p>
            </div>
          </a>
        </div>
        <div class="card">
          <div class="content">
            <i class="fa-solid fa-school fa-3x"></i>
            <div class="numbers">
              <h2>Total Courses</h2>
              <p>10</p>
            </div>
          </div>
          <a href="">
            <div class="details">
              <p>View Details</p>
            </div>
          </a>
        </div>
        <div class="card">
          <div class="content">
            <i class="fa-regular fa-rectangle-list fa-3x"></i>
            <div class="numbers">
              <h2>Completed Courses</h2>
              <p>20</p>
            </div>
          </div>
          <a href="">
            <div class="details">
              <p>View Details</p>
            </div>
          </a>
        </div>
      </div>

      <div class="charts">
        <div class="box">
          <div>
            <canvas id="myChart" width="400" height="150"></canvas>
          </div>
        </div>
        <div class="box polar">
          <div class="polar-container">
            <canvas id="polarArea"></canvas>
          </div>
        </div>
      </div>
    </main>
    <script src="../JS/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      const ctx = document.getElementById("myChart");

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
          datasets: [
            {
              label: "# of Votes",
              data: [12, 19, 3, 5, 2, 3],
              borderWidth: 1,
              borderRadius: {
                topLeft: 20,
                topRight: 20,
                bottomLeft: 0,
                bottomRight: 0,
              },
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                display: false,
              },
            },
            x: {
              grid: {
                display: false,
              },
            },
          },
        },
      });

      new Chart(document.getElementById("polarArea"), {
        type: "doughnut",
        data: {
          labels: [
            "Course",
            "Completed Course",
            "Yellow",
            "Green",
            "Purple",
            "Orange",
          ],
          datasets: [
            {
              label: "# of Votes",
              data: [12, 19, 3, 5, 2, 3],
              borderWidth: 1,
              borderRadius: {
                topLeft: 20,
                topRight: 20,
                bottomLeft: 0,
                bottomRight: 0,
              },
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "right",
            },
            title: {
              display: true,
              text: "Chart.js Doughnut Chart",
            },
          },
        },
      });
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
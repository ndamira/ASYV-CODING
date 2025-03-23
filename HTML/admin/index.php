<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../CSS/dashboard.css" />
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
        </li>
        <li class="active">
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
          <a href="purchase.php">
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
          <a href="return-outwards.php">
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
          <a href="sales.php">
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
      let toggleButton = document.getElementById("toggle-btn");
      let sidebar = document.getElementById("sidebar");


      function toggleSidebar(){
          sidebar.classList.toggle("close");
          toggleButton.classList.toggle("rotate");
          Array.from(sidebar.getElementsByClassName("show")).forEach(ul =>{
              ul.classList.remove("show");
              ul.previousElementSibling.classList.remove("rotate");
          })
      }


      function toggleSubMenu(button){
          button.nextElementSibling.classList.toggle("show");
          button.classList.toggle("rotate");

          if(sidebar.classList.contains("close")){
              sidebar.classList.toggle("close");
              toggleButton.classList.toggle("rotate");
          }
      }
    </script>
  </body>
</html>

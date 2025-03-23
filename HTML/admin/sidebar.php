<aside id="sidebar">
      <ul>
        <li>
          <img src="../../IMG/Designer.png" alt="" />
          <!-- <span class="logo">ASYV CODING</span>
                <button id="toggle-btn" onclick="toggleSidebar()">
                    <i class="fa-solid fa-angles-left"></i>
                </button> -->
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
              <li><a href="users.php">Registered Users</a></li>
              <li><a href="pendingUsers.php">Pending Users</a></li>
            </div>
          </ul>
        </li>
        <li>
          <a href="course.php">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Courses</span>
          </a>
        </li>
        <li>
          <a href="progress.php">
            <i class="fa-solid fa-spinner"></i>
            <span>Progress</span>
          </a>
        </li>
        <li>
          <button class="dropdown-btn" onclick="toggleSubMenu(this)">
            <i class="fa-regular fa-circle-user"></i>
            <span>Profile</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>
          <ul class="sub-menu">
            <div>
              <li><a href="">Setting</a></li>
              <li><a href="../logout.php    ">Logout</a></li>
            </div>
          </ul>
        </li>
      </ul>
    </aside>
    <script src="../../JS/dashboard.js"></script>
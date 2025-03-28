<?php
session_start();
// Database connection
require_once '../backend/conn.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch grades from database
$gradeQuery = "SELECT DISTINCT `grade` FROM users ORDER BY grade ASC";
$gradeResult = mysqli_query($conn, $gradeQuery);
$grades = [];
while ($row = mysqli_fetch_assoc($gradeResult)) {
    $grades[] = $row['grade'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link rel="icon" type="image/x-icon" href="../IMG/Designer.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* -------------------------------------------MAIN------------------------------------------------ */
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        h1 {
            color: var(--accent-clr);
            margin: 0;
            padding-bottom: 10px;
            font-size: 1.8rem;
        }
        
        .filter-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .filter-row {
            display: flex;
            align-items: flex-end;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4a5568;
        }
        
        .filter-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #fff;
            font-size: 0.95rem;
        }
        
        .filter-btn {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        
        .filter-btn:hover {
            background-color: #2980b9;
        }
        
        .filter-reset {
            background-color: #e74c3c;
        }
        
        .filter-reset:hover {
            background-color: #c0392b;
        }
        
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .users-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .users-table th {
            background-color: #f1f8ff;
            color: var(--accent-clr);
            font-weight: 600;
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .users-table td {
            color: var(--base-clr);
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .users-table tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .progress-container {
            background-color: #e9ecef;
            border-radius: 10px;
            height: 8px;
            width: 100%;
            max-width: 100px;
        }
        
        .progress-bar {
            height: 100%;
            border-radius: 10px;
            background-color: #3498db;
        }
        
        .action-btn {
            background: none;
            border: none;
            color: #3498db;
            cursor: pointer;
            margin-right: 10px;
            font-size: 1rem;
        }
        
        .action-btn:hover {
            color: #2980b9;
        }
        
        .delete-btn {
            color: #e74c3c;
        }
        
        .delete-btn:hover {
            color: #c0392b;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        
        .modal-overlay.active .modal {
            transform: translateY(0);
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            margin: 0;
            color: #2c3e50;
            font-size: 1.25rem;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            border: none;
        }
        
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-col {
            flex: 1;
        }
        
        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                width: 100%;
                margin-bottom: 15px;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .users-table {
                display: block;
                overflow-x: auto;
            }
            
            .form-row {
                flex-direction: column;
            }
            
            .modal-footer {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        /* for the popups */
        /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    /* Modal Container */
    .modal {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        transform: scale(0.7);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .modal-overlay.active .modal {
        transform: scale(1);
        opacity: 1;
    }

    /* Modal Header */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .modal-title {
        margin: 0;
        font-size: 1.25rem;
        color: #333;
        font-weight: 600;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #aaa;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .modal-close:hover {
        color: #333;
    }

    /* Modal Body */
    .modal-body {
        padding: 20px;
    }

    /* Edit Modal Specific Styles */
    #editModal .form-row {
        display: flex;
        gap: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: 500;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    /* Delete Modal Specific Styles */
    #deleteModal .alert {
        display: flex;
        align-items: center;
        background-color: #fff3f3;
        color: #dc3545;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    #deleteModal .alert i {
        margin-right: 10px;
        font-size: 1.5rem;
    }

    /* Modal Footer */
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
        background-color: #f8f9fa;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-left: 10px;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #555f66;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #a71d2a;
    }

    /* Responsive Adjustments */
    @media (max-width: 600px) {
        .modal {
            width: 95%;
            margin: 0 10px;
        }

        #editModal .form-row {
            flex-direction: column;
            gap: 10px;
        }
    }


    </style>
</head>
<body>
    <button id="mobile-toggle" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div id="overlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar remains the same as in the previous code -->
    <aside id="sidebar">
      <ul>
        <li>
          <img src="../IMG/Designer.png" alt="" />
          
        </li>
        <li>
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
              <li class="active"><a href="registeredusers.php">Registered Users</a></li>
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
    <div class="container">
        <div class="header">
            <h1>Registered Users</h1>
        </div>
        
        <div class="filter-section">
            <form id="filterForm">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label" for="gradeFilter">Filter by Grade:</label>
                        <select class="filter-select" id="gradeFilter">
                            <option value="">-- Select Grade --</option>
                            <?php foreach ($grades as $grade): ?>
                                <option value="<?php echo $grade; ?>">Grade <?php echo $grade; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="searchInput">Search Users:</label>
                        <input type="text" class="filter-input" id="searchInput" placeholder="Search by name">
                    </div>
                    <div>
                        <button type="submit" class="filter-btn">Apply Filter</button>
                        <button type="button" id="resetBtn" class="filter-btn filter-reset">Reset</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Grade</th>
                        <th>Role</th>  
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr>
                        <td colspan="9" class="no-data">Loading users...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Edit User</h3>
                <button class="modal-close" data-close-modal>&times;</button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId">
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="editFirstName">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="editLastName">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="editGrade">Grade</label>
                                <select class="form-select" id="editGrade" required>
                                    <?php foreach ($grades as $grade): ?>
                                        <option value="<?php echo $grade; ?>">Grade <?php echo $grade; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="editPassword">Password (Leave blank to keep current)</label>
                                <input type="password" class="form-control" id="editPassword">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-close-modal>Cancel</button>
                <button class="btn btn-primary" id="saveEditBtn">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Deletion</h3>
                <button class="modal-close" data-close-modal>&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Warning: This action cannot be undone.
                </div>
                <p>Are you sure you want to delete the user <strong id="deleteUserName"></strong>?</p>
                <input type="hidden" id="deleteUserId">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-close-modal>Cancel</button>
                <button class="btn btn-danger" id="confirmDeleteBtn">Delete User</button>
            </div>
        </div>
    </div>
    </main>

    <script>
        // Fetch users from the database
         // Fetch users from the database
         async function fetchUsers(grade = '', searchTerm = '') {
            const response = await fetch('fetch_users.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `grade=${encodeURIComponent(grade)}&search=${encodeURIComponent(searchTerm)}`
            });
            return await response.json();
        }

        document.addEventListener('DOMContentLoaded', async function() {
            const tableBody = document.getElementById('usersTableBody');
            const filterForm = document.getElementById('filterForm');
            const gradeFilter = document.getElementById('gradeFilter');
            const searchInput = document.getElementById('searchInput');
            const resetBtn = document.getElementById('resetBtn');
            
            // Fetch and display all users when page loads
            try {
                const users = await fetchUsers();
                
                if (users.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="9" class="no-data">No users found</td></tr>';
                    return;
                }
                
                renderUsersTable(users);
            } catch (error) {
                console.error('Error fetching users:', error);
                tableBody.innerHTML = '<tr><td colspan="9" class="no-data">Error loading users</td></tr>';
            }
            
            // Filter form submission
            filterForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const selectedGrade = gradeFilter.value;
                const searchTerm = searchInput.value;
                
                try {
                    const users = await fetchUsers(selectedGrade, searchTerm);
                    
                    if (users.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="9" class="no-data">No users found</td></tr>';
                        return;
                    }
                    
                    renderUsersTable(users);
                } catch (error) {
                    console.error('Error fetching users:', error);
                    tableBody.innerHTML = '<tr><td colspan="9" class="no-data">Error loading users</td></tr>';
                }
            });
            
            // Reset button click
            resetBtn.addEventListener('click', async function() {
                gradeFilter.value = '';
                searchInput.value = '';
                
                try {
                    // Fetch all users again
                    const users = await fetchUsers();
                    
                    if (users.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="9" class="no-data">No users found</td></tr>';
                        return;
                    }
                    
                    renderUsersTable(users);
                } catch (error) {
                    console.error('Error fetching users:', error);
                    tableBody.innerHTML = '<tr><td colspan="9" class="no-data">Error loading users</td></tr>';
                }
            });
            
            // Reset button click
            resetBtn.addEventListener('click', function() {
                gradeFilter.value = '';
                searchInput.value = '';
                tableBody.innerHTML = '<tr><td colspan="9" class="no-data">Select a grade to view registered users</td></tr>';
            });
            
            // Function to render users in the table
            function renderUsersTable(users) {
                tableBody.innerHTML = '';
                
                users.forEach(user => {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.first_name}</td>
                        <td>${user.last_name}</td>
                        <td>${user.email}</td>
                        <td>${user.grade}</td>
                        <td>${user.role}</td>
                        <td>
                            <button class="action-btn edit-user-btn" data-user-id="${user.id}" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete-btn delete-user-btn" data-user-id="${user.id}" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    `;
                    
                    tableBody.appendChild(row);
                });
                
                // Add event listeners to action buttons
                document.querySelectorAll('.edit-user-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        openEditModal(userId);
                    });
                });
                
                document.querySelectorAll('.delete-user-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        openDeleteModal(userId);
                    });
                });
            }
            
            // Modal functionality
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            const saveEditBtn = document.getElementById('saveEditBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            
            // Close modal when clicking the close button or cancel button
            document.querySelectorAll('[data-close-modal]').forEach(element => {
                element.addEventListener('click', function() {
                    const modal = this.closest('.modal-overlay');
                    closeModal(modal);
                });
            });
            
            // Close modal when clicking outside the modal
            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this);
                    }
                });
            });
            
            // Open edit modal and populate form
            async function openEditModal(userId) {
                try {
                    const response = await fetch(`get_user.php?id=${userId}`);
                    const user = await response.json();
                    
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editFirstName').value = user.first_name;
                    document.getElementById('editLastName').value = user.last_name;
                    document.getElementById('editEmail').value = user.email;
                    document.getElementById('editGrade').value = user.grade;
                    
                    openModal(editModal);
                } catch (error) {
                    console.error('Error fetching user details:', error);
                    alert('Failed to load user details');
                }
            }
            
            // Open delete modal
            async function openDeleteModal(userId) {
                try {
                    const response = await fetch(`get_user.php?id=${userId}`);
                    const user = await response.json();
                    
                    document.getElementById('deleteUserId').value = user.id;
                    document.getElementById('deleteUserName').textContent = `${user.first_name} ${user.last_name}`;
                    
                    openModal(deleteModal);
                } catch (error) {
                    console.error('Error fetching user details:', error);
                    alert('Failed to load user details');
                }
            }
            
            // Function to open a modal
            function openModal(modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }
            
            // Function to close a modal
            function closeModal(modal) {
                modal.classList.remove('active');
                document.body.style.overflow = ''; // Restore scrolling
            }
            
            // Save edit changes
            saveEditBtn.addEventListener('click', async function() {
                const formData = new FormData();
                formData.append('id', document.getElementById('editUserId').value);
                formData.append('first_name', document.getElementById('editFirstName').value);
                formData.append('last_name', document.getElementById('editLastName').value);
                formData.append('email', document.getElementById('editEmail').value);
                formData.append('grade', document.getElementById('editGrade').value);
                
                const password = document.getElementById('editPassword').value;
                if (password) {
                    formData.append('password', password);
                }
                
                try {
                    const response = await fetch('update_user.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        // Close the modal
                        closeModal(editModal);
                        
                        // Re-render the table with the current filter
                        const selectedGrade = gradeFilter.value;
                        const searchTerm = searchInput.value;
                        const users = await fetchUsers(selectedGrade, searchTerm);
                        renderUsersTable(users);
                        
                        alert('User updated successfully!');
                    } else {
                        alert(result.message || 'Failed to update user');
                    }
                } catch (error) {
                    console.error('Error updating user:', error);
                    alert('Failed to update user');
                }
            });
            
            // Confirm delete
            confirmDeleteBtn.addEventListener('click', async function() {
                const userId = document.getElementById('deleteUserId').value;
                
                try {
                    const response = await fetch('delete_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id=${userId}`
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        // Close the modal
                        closeModal(deleteModal);
                        
                        // Re-render the table with the current filter
                        const selectedGrade = gradeFilter.value;
                        const searchTerm = searchInput.value;
                        const users = await fetchUsers(selectedGrade, searchTerm);
                        
                        if (users.length === 0) {
                            tableBody.innerHTML = '<tr><td colspan="9" class="no-data">No users found</td></tr>';
                        } else {
                            renderUsersTable(users);
                        }
                        
                        alert('User deleted successfully!');
                    } else {
                        alert(result.message || 'Failed to delete user');
                    }
                } catch (error) {
                    console.error('Error deleting user:', error);
                    alert('Failed to delete user');
                }
            });
        });

        // Sidebar toggle functions remain the same as in the previous code
         // ------------------------------------SUBMENU SIDEBAR-------------------------------------------------

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
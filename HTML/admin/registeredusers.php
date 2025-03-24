<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
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
                            <option value="9">Grade 9</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
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
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr>
                        <td colspan="9" class="no-data">Select a grade to view registered users</td>
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
                                    <option value="9">Grade 9</option>
                                    <option value="10">Grade 10</option>
                                    <option value="11">Grade 11</option>
                                    <option value="12">Grade 12</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="editRole">Role</label>
                                <select class="form-select" id="editRole" required>
                                    <option value="Student">Student</option>
                                    <option value="Teacher">Teacher</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="editStatus">Status</label>
                                <select class="form-select" id="editStatus" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="editProgress">Progress (%)</label>
                                <input type="number" class="form-control" id="editProgress" min="0" max="100" required>
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
        // Sample data - in a real application, this would come from your backend
        let users = [
            { id: 1, firstName: "John", lastName: "Doe", email: "john.doe@example.com", grade: "12", role: "Student", status: "Active", progress: 75 },
            { id: 2, firstName: "Jane", lastName: "Smith", email: "jane.smith@example.com", grade: "12", role: "Student", status: "Active", progress: 90 },
            { id: 3, firstName: "Michael", lastName: "Johnson", email: "michael.j@example.com", grade: "11", role: "Student", status: "Active", progress: 60 },
            { id: 4, firstName: "Emily", lastName: "Brown", email: "emily.b@example.com", grade: "10", role: "Student", status: "Inactive", progress: 30 },
            { id: 5, firstName: "David", lastName: "Wilson", email: "david.w@example.com", grade: "12", role: "Student", status: "Active", progress: 85 },
            { id: 6, firstName: "Sarah", lastName: "Taylor", email: "sarah.t@example.com", grade: "11", role: "Student", status: "Active", progress: 50 },
            { id: 7, firstName: "James", lastName: "Anderson", email: "james.a@example.com", grade: "9", role: "Student", status: "Inactive", progress: 15 },
            { id: 8, firstName: "Lisa", lastName: "Thomas", email: "lisa.t@example.com", grade: "10", role: "Student", status: "Active", progress: 40 },
            { id: 9, firstName: "Robert", lastName: "Jackson", email: "robert.j@example.com", grade: "12", role: "Student", status: "Active", progress: 95 },
            { id: 10, firstName: "Amanda", lastName: "White", email: "amanda.w@example.com", grade: "9", role: "Student", status: "Active", progress: 25 },
        ];

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('usersTableBody');
            const filterForm = document.getElementById('filterForm');
            const gradeFilter = document.getElementById('gradeFilter');
            const resetBtn = document.getElementById('resetBtn');
            
            // Filter form submission
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const selectedGrade = gradeFilter.value;
                
                if (!selectedGrade) {
                    // If no grade is selected, show the message
                    tableBody.innerHTML = '<tr><td colspan="9" class="no-data">Select a grade to view registered users</td></tr>';
                    return;
                }
                
                // Filter users by selected grade
                const filteredUsers = users.filter(user => user.grade === selectedGrade);
                
                if (filteredUsers.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="9" class="no-data">No users found in grade ' + selectedGrade + '</td></tr>';
                    return;
                }
                
                // Populate table with filtered users
                renderUsersTable(filteredUsers);
            });
            
            // Reset button click
            resetBtn.addEventListener('click', function() {
                gradeFilter.value = '';
                tableBody.innerHTML = '<tr><td colspan="9" class="no-data">Select a grade to view registered users</td></tr>';
            });
            
            // Function to render users in the table
            function renderUsersTable(usersData) {
                tableBody.innerHTML = '';
                
                usersData.forEach(user => {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.firstName}</td>
                        <td>${user.lastName}</td>
                        <td>${user.email}</td>
                        <td>${user.grade}</td>
                        <td>${user.role}</td>
                        <td><span class="status-${user.status.toLowerCase()}">${user.status}</span></td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: ${user.progress}%"></div>
                            </div>
                        </td>
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
            function openEditModal(userId) {
                const user = users.find(user => user.id == userId);
                if (!user) return;
                
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editFirstName').value = user.firstName;
                document.getElementById('editLastName').value = user.lastName;
                document.getElementById('editEmail').value = user.email;
                document.getElementById('editGrade').value = user.grade;
                document.getElementById('editRole').value = user.role;
                document.getElementById('editStatus').value = user.status;
                document.getElementById('editProgress').value = user.progress;
                
                openModal(editModal);
            }
            
            // Open delete modal
            function openDeleteModal(userId) {
                const user = users.find(user => user.id == userId);
                if (!user) return;
                
                document.getElementById('deleteUserId').value = user.id;
                document.getElementById('deleteUserName').textContent = `${user.firstName} ${user.lastName}`;
                
                openModal(deleteModal);
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
            saveEditBtn.addEventListener('click', function() {
                const userId = document.getElementById('editUserId').value;
                const userIndex = users.findIndex(user => user.id == userId);
                
                if (userIndex === -1) return;
                
                // Update user data
                users[userIndex] = {
                    id: parseInt(userId),
                    firstName: document.getElementById('editFirstName').value,
                    lastName: document.getElementById('editLastName').value,
                    email: document.getElementById('editEmail').value,
                    grade: document.getElementById('editGrade').value,
                    role: document.getElementById('editRole').value,
                    status: document.getElementById('editStatus').value,
                    progress: parseInt(document.getElementById('editProgress').value)
                };
                
                // Close the modal
                closeModal(editModal);
                
                // Re-render the table with the current filter
                const selectedGrade = gradeFilter.value;
                if (selectedGrade) {
                    const filteredUsers = users.filter(user => user.grade === selectedGrade);
                    renderUsersTable(filteredUsers);
                }
                
                // Show a success message (in a real app, you might use a toast notification)
                alert('User updated successfully!');
            });
            
            // Confirm delete
            confirmDeleteBtn.addEventListener('click', function() {
                const userId = document.getElementById('deleteUserId').value;
                
                // Remove user from the array
                users = users.filter(user => user.id != userId);
                
                // Close the modal
                closeModal(deleteModal);
                
                // Re-render the table with the current filter
                const selectedGrade = gradeFilter.value;
                if (selectedGrade) {
                    const filteredUsers = users.filter(user => user.grade === selectedGrade);
                    if (filteredUsers.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="9" class="no-data">No users found in grade ' + selectedGrade + '</td></tr>';
                    } else {
                        renderUsersTable(filteredUsers);
                    }
                }
                
                // Show a success message (in a real app, you might use a toast notification)
                alert('User deleted successfully!');
            });
        });

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
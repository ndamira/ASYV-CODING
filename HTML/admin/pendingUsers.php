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
              <h2>Pending User</h2>
              <i class="fa-solid fa-xmark" onclick="closeApprove()"></i>
            </div>
            <p>This user is currently not approved. Do you want to Approve?</p>
            <form action="" method="Post">
              <input type="hidden" id="recordId" name="recordId" />
              <div class="btn">
                <button type="submit" class="btn2" name="delete">
                  Yes, Approve
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
              <td>Status</td>
              <td class="last">Action</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Kevin</td>
              <td>Ishimwe</td>
              <td>ishimwekevin@gmail.com</td>
              <td>Ishema</td>
              <td>
                <button class="pending" onclick="approveUser()">Pending</button>
              </td>
              <td>
                <button onclick="deleteUser()">Delete</button>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Kevin</td>
              <td>Ishimwe</td>
              <td>ishimwekevin@gmail.com</td>
              <td>Ishema</td>
              <td>
                <button class="pending" onclick="approveUser()">Pending</button>
              </td>
              <td>
                <button onclick="deleteUser()">Delete</button>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td>Kevin</td>
              <td>Ishimwe</td>
              <td>ishimwekevin@gmail.com</td>
              <td>Ishema</td>
              <td>
                <button class="pending" onclick="approveUser()">Pending</button>
              </td>
              <td>
                <button onclick="deleteUser()">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
    <script src="../../JS/pendingUsers.js"></script>
  </body>
</html>

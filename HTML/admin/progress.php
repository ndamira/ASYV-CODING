<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Progress</title>
    <link rel="stylesheet" href="../CSS/progress.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="icon" type="image/x-icon" href="../IMG/Designer.png">
  </head>
  <body>
  <?php include('sidebar.php'); ?>
    <main>
      <div class="title">
        <h2>Users Progress</h2>
        <div class="choose">
          <select name="" id="">
            <option value="none">Select Grade</option>
            <option value="ishami">Ishami</option>
            <option value="ijabo">Ijabo</option>
            <option value="ingabo">Ingabo</option>
          </select>
        </div>
      </div>
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
              <td>Grade</td>
              <td>Current Unit</td>
              <td>Progress</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Kevin</td>
              <td>Ishimwe</td>
              <td>Ishema</td>
              <td>Unit 1</td>
              <td>
                <button class="pending" onclick="approveUser()">
                  View More
                </button>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Kevin</td>
              <td>Ishimwe</td>
              <td>Ishema</td>
              <td>Unit 1</td>
              <td>
                <button class="pending" onclick="approveUser()">
                  View More
                </button>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td>Kevin</td>
              <td>Ishimwe</td>
              <td>Ishema</td>
              <td>Unit 1</td>
              <td>
                <button class="pending" onclick="approveUser()">
                  View More
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
    <script src="../../JS/progress.js"></script>
  </body>
</html>

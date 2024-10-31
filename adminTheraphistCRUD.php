<?php
include "generalConnection.php";

// Handle form submission for adding a new user
if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $profile = $_POST['profilepicture'];

    // Use correct column names and remove single quotes around userType
    $sql = "INSERT INTO `userInfo`(`userID`, `name`, `userPassword`, `userEmail`, `userGender`, `userType`, `userProfilePicture`) VALUES (NULL, '$name', '$password', '$email', '$gender', 'doctor', '$profile')";

    $result = mysqli_query($data, $sql); // Use $data instead of $conn

    if ($result) {
        header("Location: adminTheraphistCRUD.php?msg=New record created successfully");
    } else {
        echo "Failed: " . mysqli_error($data);
    }
}
if (isset($_POST["editSubmit"])) {
  $id = $_POST['userID'];
  $name = mysqli_real_escape_string($data, $_POST['name']);
  $password = mysqli_real_escape_string($data, $_POST['password']);
  $email = mysqli_real_escape_string($data, $_POST['email']);
  $gender = mysqli_real_escape_string($data, $_POST['gender']);

  $sql = "UPDATE `userInfo` SET `name`='$name', `userPassword`='$password', `userEmail`='$email', `userGender`='$gender' WHERE userID = $id";

  $result = mysqli_query($data, $sql);

  if ($result) {
      header("Location: adminTheraphistCRUD.php?msg=Data updated successfully");
  } else {
      echo "Failed: " . mysqli_error($data);
  }
}
if (isset($_GET['rem']) && $_GET['rem'] > 0) {
  $remId = (int)$_GET['rem'];

  $stmt = $con->prepare("SELECT * FROM `userInfo` WHERE `userID` = ?");
  $stmt->bind_param("i", $remId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($fetch = $result->fetch_assoc()) {
      $deleteStmt = $con->prepare("DELETE FROM `userInfo` WHERE `userID` = ?");
      $deleteStmt->bind_param("i", $remId);

      if ($deleteStmt->execute()) {
          header("Location: adminTheraphistCRUD.php?success=removed");
      } else {
          header("Location: adminTheraphistCRUD.php?alert=remove_failed");
      }
      $deleteStmt->close();
  } else {
      header("Location: adminTheraphistCRUD.php?alert=not_found");
  }
  $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <title>PHP CRUD Application</title>
  <style>
    /* From Uiverse.io by andrew-demchenk0 */ 
.button {
  position: relative;
  width: 150px;
  height: 40px;
  cursor: pointer;
  display: flex;
  align-items: center;
  border: 1px solid #34974d;
  background-color: #3aa856;
}

.button, .button__icon, .button__text {
  transition: all 0.3s;
}

.button .button__text {
  transform: translateX(30px);
  color: #fff;
  font-weight: 600;
}

.button .button__icon {
  position: absolute;
  transform: translateX(109px);
  height: 100%;
  width: 35px;
  max-width:45px;
  background-color: #34974d;
  display: flex;
  align-items: center;
  justify-content: center;
}

.button .svg {
  width: 30px;
  stroke: #fff;
}

.button:hover {
  background: #34974d;
}

.button:hover .button__text {
  color: transparent;
}

.button:hover .button__icon {
  width: 148px;
  transform: translateX(0);
}

.button:active .button__icon {
  background-color: ##BF0000;
}

.button:active {
  border: 1px solid ##BF0000;
}
/* From Uiverse.io by vinodjangid07 */ 
.Btn {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  width: 45px;
  height: 45px;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition-duration: .3s;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
  background-color: #E32227;
}

/* plus sign */
.sign {
  width: 100%;
  transition-duration: .3s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sign svg {
  width: 17px;
}

.sign svg path {
  fill: white;
}
/* text */
.text {
  position: absolute;
  right: 0%;
  width: 0%;
  opacity: 0;
  color: white;
  font-size: 12px;
  font-weight: 600;
  transition-duration: .3s;
}
/* hover effect on button width */
.Btn:hover {
  width: 125px;
  border-radius: 40px;
  transition-duration: .3s;
}

.Btn:hover .sign {
  width: 30%;
  transition-duration: .3s;
  padding-left: 20px;
}
/* hover effect button's text */
.Btn:hover .text {
  opacity: 1;
  width: 70%;
  transition-duration: .3s;
  padding-right: 10px;
}
/* button click effect*/
.Btn:active {
  transform: translate(2px ,2px);
}
.modal-backdrop {
  opacity: 0.5; /* Adjust the opacity to your liking */
  background-color: black; /* Change the color to your preferred background color */
}

    </style>
</head>

<body>

  <div class="container">
    <nav class="navbar navbar-light bg-light" style="background-color:blue;">
      <form class="form-inline">
          <button type="button" class="btn btn-primary" style="box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);">Primillary Test Upload</button>
          <button type="button" class="btn btn-primary" style="box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);">Pending Account</button>
          <button type="button" class="btn btn-primary" style="box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);">Doctor Account </button>
          <button type="button" class="btn btn-primary" style="box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);">User Account</button>
      </form>
    </nav>

    <?php
    if (isset($_GET["msg"])) {
        $msg = $_GET["msg"];
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        ' . $msg . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    ?>
    <br>

    <!-- Button to trigger modal -->
    <button type="button" class="button" data-bs-toggle="modal" data-bs-target="#addUserModal">
      <span class="button__text">Add New</span>
      <span class="button__icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg">
          <line y2="19" y1="5" x2="12" x1="12"></line>
          <line y2="12" y1="12" x2="19" x1="5"></line>
        </svg>
      </span>
    </button>
<br>
    <!-- Modal Structure -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Add your form fields here -->
            <form action="" method="post">
              <div class="mb-3">
                <label for="userName" class="form-label">Therapist Name</label>
                <input type="text" class="form-control" name="name" id="userName" placeholder="Albert" required>
              </div>
              <div class="mb-3">
                <label for="userEmail" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="userEmail" placeholder="AlbertEinstein@gmail.com" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Einstein" required>
              </div>
              <div class="mb-3">
                <label for="profilepicture" class="form-label">Theraphist Profile Picture</label>
                <input type="file" class="form-control" name="profilepicture" id="profilepicture"  required>
              </div>
              <div class="form-group mb-3">
                <label>Gender:</label>
                <input type="radio" class="form-check-input" name="gender" id="male" value="male">
                <label for="male" class="form-input-label">Male</label>
                <input type="radio" class="form-check-input" name="gender" id="female" value="female">
                <label for="female" class="form-input-label">Female</label>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="submit">Save</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<!-- Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

    <table class="table table-bordered ">
      <thead class="table bg-primary" style ="color:white;">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Gender</th>
          <th>Password</th>
          <th>Profile Picture</th>
          <th>Specialized</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Fetch users from the database
        $sql = "SELECT * FROM userInfo WHERE userType= 'doctor'";
        $result = mysqli_query($data, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr >";
                echo "<td>" . $row['userID'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['userEmail'] . "</td>";
                echo "<td>" . $row['userGender'] . "</td>";
                echo "<td>" . $row['userPassword'] . "</td>";
                echo "<td>" . $row['userProfilePicture'] . "</td>";
                echo "<td>" . $row['specialized'] . "</td>";
                echo "<td>
                <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editUserModal' data-id='" . $row['userID'] . "' data-name='" . htmlspecialchars($row['name']) . "' data-email='" . htmlspecialchars($row['userEmail']) . "' data-gender='" . htmlspecialchars($row['userGender']) . "'>Edit</button>
                <a href='#' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteConfirmationModal' onclick='setDeleteID(" . $row['userID'] . ")'>Delete</a>
            </td>";
      echo "</tr>";
            }
        }
        ?>
      </tbody>
    </table>
    <a href ="logout.php">
      <button class="Btn">
        <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
        <div class="text">Logout</div>
      </button>
      </a>
  </div>
 <!-- Edit User Modal -->
 <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="post">
                        <input type="hidden" name="userID" id="userID">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="editPassword" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Gender:</label>
                            <input type="radio" class="form-check-input" name="gender" id="editMale" value="male" required>
                            <label for="editMale" class="form-input-label">Male</label>
                            <input type="radio" class="form-check-input" name="gender" id="editFemale" value="female" required>
                            <label for="editFemale" class="form-input-label">Female</label>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" name="editSubmit">Update</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        const editUserModal = document.getElementById('editUserModal');
        editUserModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const userID = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const password = button.getAttribute('data-password');
            const gender = button.getAttribute('data-gender');

            const modalUserID = editUserModal.querySelector('#userID');
            const modalName = editUserModal.querySelector('#editName');
            const modalEmail = editUserModal.querySelector('#editEmail');
            const modalPassword = editUserModal.querySelector('#editPassword');
            const modalGenderMale = editUserModal.querySelector('#editMale');
            const modalGenderFemale = editUserModal.querySelector('#editFemale');

            modalUserID.value = userID;
            modalName.value = name;
            modalEmail.value = email;
            modalPassword.value =
            modalGenderMale.checked = (gender === 'male');
            modalGenderFemale.checked = (gender === 'female');
        });
        function setDeleteID(id) {
    document.getElementById('confirmDeleteButton').onclick = function () {
      window.location.href = `adminTheraphistCRUD.php?id=${id}&confirmDeleteButton=true`;
    };
  }
        
    </script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>

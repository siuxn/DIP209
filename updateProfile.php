<?php

include 'generalConnection.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:signin.php');
    exit; // Ensure no further code is executed after the redirect
}

if (isset($_POST['update_profile'])) {
    $message = []; // Initialize the message array

    $update_name = mysqli_real_escape_string($data, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($data, $_POST['update_email']);
    $update_phone = mysqli_real_escape_string($data, $_POST['update_phone']);
    $update_gender = mysqli_real_escape_string($data, $_POST['update_gender']);
    $update_birthdate = mysqli_real_escape_string($data, $_POST['update_birthdate']);

    // Calculate user age from birthdate
    $birth_date = new DateTime($update_birthdate);
    $current_date = new DateTime();
    $age_interval = $current_date->diff($birth_date);
    $calculated_age = $age_interval->y;

    // Update user info (excluding password for now)
    mysqli_query($data, "UPDATE `userInfo` SET name = '$update_name', userEmail = '$update_email', userPhoneNumber = '$update_phone', userGender = '$update_gender', UserBirthdate = '$update_birthdate', userAge = '$calculated_age' WHERE userID = '$user_id'") or die('query failed');

    $old_pass = $_POST['old_pass'];
    $update_pass = $_POST['update_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // Password Update
    if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        // Fetch the current hashed password from the database
        $result = mysqli_query($data, "SELECT userPassword FROM `userInfo` WHERE userID = '$user_id'") or die('query failed');
        $user = mysqli_fetch_assoc($result);

        // Verify old password
        if (!password_verify($update_pass, $user['userPassword'])) {
            $message[] = 'Old password not matched!';
        } elseif ($new_pass !== $confirm_pass) {
            $message[] = 'Confirm password not matched!';
        } else {
            // Hash the new password and update it
            $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            mysqli_query($data, "UPDATE `userInfo` SET userPassword = '$hashed_new_pass' WHERE userID = '$user_id'") or die('query failed');
            $message[] = 'Password updated successfully!';
        }
    }

    // Image Update
    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'profilePics/' . $update_image;

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image is too large';
        } else {
            // Update image path in the database
            mysqli_query($data, "UPDATE `userInfo` SET userProfilePicture = '$update_image' WHERE userID = '$user_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
            $message[] = 'Image updated successfully!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>

    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="update-profile">

   <?php
      $select = mysqli_query($data, "SELECT * FROM `userInfo` WHERE userID = '$user_id'") or die('query failed');
      if (mysqli_num_rows($select) > 0) {
         $fetch = mysqli_fetch_assoc($select);
      }
   ?>

   <form action="" method="post" enctype="multipart/form-data">
      <?php
         if ($fetch['userProfilePicture'] == '') {
            echo '<img src="images/default-avatar.png" alt="Default Avatar">';
         } else {
            echo '<img src="profilePics/' . $fetch['userProfilePicture'] . '" alt="User Avatar">';
         }
         if (isset($message)) {
            foreach ($message as $msg) {
               echo '<div class="message">' . $msg . '</div>';
            }
         }
      ?>
      <div class="flex">
         <div class="inputBox">
            <span>Name:</span>
            <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">
            <span>Your Email:</span>
            <input type="email" name="update_email" value="<?php echo $fetch['userEmail']; ?>" class="box">
            <span>Phone Number:</span>
            <input type="text" name="update_phone" value="<?php echo $fetch['userPhoneNumber']; ?>" class="box">
            <span>Gender:</span>
            <div>
                <input type="radio" name="update_gender" value="Male" <?php echo ($fetch['userGender'] == 'Male') ? 'checked' : ''; ?>> Male
                <input type="radio" name="update_gender" value="Female" <?php echo ($fetch['userGender'] == 'Female') ? 'checked' : ''; ?>> Female
            </div>
            <span>Birthday:</span>
            <input type="date" name="update_birthdate" value="<?php echo $fetch['UserBirthdate']; ?>" class="box">
            <span>Update Your Pic:</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?php echo $fetch['userPassword']; ?>">
            <span>Old Password:</span>
            <input type="password" name="update_pass" placeholder="Enter previous password" class="box">
            <span>New Password:</span>
            <input type="password" name="new_pass" placeholder="Enter new password" class="box">
            <span>Confirm Password:</span>
            <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">
         </div>
      </div>
      <input type="submit" value="Update Profile" name="update_profile" class="btn">
      <a href="userhome.php" class="delete-btn">Go Back</a>
   </form>

</div>

</body>
</html>

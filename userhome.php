<?php

include 'generalConnection.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:signin.php');
    exit; // Ensure no further code is executed after the redirect
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:signin.php');
    exit; // Ensure no further code is executed after the redirect
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- custom css file link  -->
   

</head>
<body>
   
<div class="container">

   <div class="profile">
      <?php
         // Fetch user data from the userInfo table
         $select = mysqli_query($data, "SELECT * FROM `userInfo` WHERE userID = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select) > 0) {
             $fetch = mysqli_fetch_assoc($select);
         }

         // Display user profile picture
         if ($fetch['userProfilePicture'] == '') {
             echo '<img src="images/default-avatar.png">';
         } else {
             echo '<img src="uploaded_img/' . $fetch['userProfilePicture'] . '">';
         }
      ?>
      <h3><?php echo $fetch['name'] ?: '-'; ?></h3>
      <p><strong>Age:</strong> <?php echo $fetch['userAge'] ?: '-'; ?></p>
      <p><strong>Birthdate:</strong> <?php echo ($fetch['UserBirthdate'] == '0000-00-00' || $fetch['UserBirthdate'] == '') ? '-' : $fetch['UserBirthdate']; ?></p>
      <p><strong>Email:</strong> <?php echo $fetch['userEmail'] ?: '-'; ?></p>
      <p><strong>Phone Number:</strong> <?php echo $fetch['userPhoneNumber'] ?: '-'; ?></p>
      <p><strong>Notification Preference:</strong> <?php echo $fetch['userNotificationPreference'] ?: '-'; ?></p>
      <p><strong>Gender:</strong> <?php echo $fetch['userGender'] ?: '-'; ?></p>
      <p><strong>Specialization:</strong> <?php echo $fetch['specialized'] ?: '-'; ?></p>


    </h3>
      <a href="updateProfile.php" class="btn">Update Profile</a>
      <a href="logout.php" class="delete-btn">Logout</a>
   </div>

</div>

</body>
</html>

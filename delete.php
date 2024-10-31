<?php
include "generalConnection.php";
$id = $_GET["id"];
$sql = "DELETE FROM `userInfo` WHERE userID = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: adminTheraphistCRUD.php?msg=Data deleted successfully");
} else {
  echo "Failed: " . mysqli_error($conn);
}

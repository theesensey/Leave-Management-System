<?php

//Creates a session or Resumes the current one based on a session identifier 
session_start();

require_once '../config.php';

//Checking if user is logged in
if (!$_SESSION['email']) {
    //Redirects to approriate page / method
    header('Location: ../login.php ');
}
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Manage Leaves</title>
</head>

<body>
    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="manage_leaves.php">Manage Leaves</a>
        <a href="other.php">Manage System</a>
        <a href="../logout.php">Logout</a>
    </div>
    <br>
    <div class="button-group">
        <a href="filter_leaves.php" class="button">Filter Leaves</a>
        <a href="delete_leaves.php" class="button">Delete Leaves</a>
        <a href="leaverequests.php" class="button">Approve Leave</a>
        <a href="add_leaves.php" class="button">Add Leaves</a>

    </div>
    <br>
    <div class="panel-footer">Go back <a href="index.php">Home</a></div>
</body>

</html>
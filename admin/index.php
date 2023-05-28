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

// Retrieve employees
$sql = "SELECT * FROM employee";
$result = mysqli_query($con, $sql);
$employees = mysqli_num_rows($result);

// Retrieve users who have pending leave requests
$sql = "SELECT * FROM `leave` WHERE status = 'pending'";
$result = mysqli_query($con, $sql);
$num_pending = mysqli_num_rows($result);

// Retrieve users who have accepted leave requests
$sql = "SELECT * FROM `leave` WHERE status = 'approved'";
$result = mysqli_query($con, $sql);
$num_accepted = mysqli_num_rows($result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Welcome to Admin Panel!</title>
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

    <body>
        <div class="container">
            <h1>Leave Management System Admin Panel</h1>
            <div class="buttons">
                <a href="leaverequests.php" class="btn">Leave Requests</a>
                <a href="manage_leaves.php" class="btn">Manage Leaves</a>
                <a href="other.php" class="btn">Manage Employees</a>
            </div>
        </div>
        <div class="dashboard">
            <a href="pendingrequests.php" class="dashboard__card pending">
                <h2><?php echo $num_pending; ?></h2>
                <p>Pending Requests</p>
            </a>
            <a href="acceptedrequests.php" class="dashboard__card accepted">
                <h2><?php echo $num_accepted; ?></h2>
                <p>Approved Requests</p>
            </a>
            <a href="employees.php" class="dashboard__card employee">
                <h2><?php echo $employees; ?></h2>
                <p>Employees</p>
            </a>
        </div>
    </body>

</html>
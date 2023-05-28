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
// Get employee ID
$employee_id = $_SESSION['id'];
// Get user ID
$user_id = $_SESSION['id'];

/// Get leave balance
$sql = "SELECT SUM(DATEDIFF(leave_to, leave_from) + 1) AS leave_balance FROM `leave` WHERE employee_id = $user_id AND status = 'approved'";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $leave_balance = isset($row['leave_balance']) ? $row['leave_balance'] : 0;
} else {
    $leave_balance = 0;
}

// Check if user is on leave
$sql = "SELECT COUNT(*) AS num_on_leave FROM `leave` WHERE employee_id = $user_id AND status = 'approved' AND leave_from <= NOW() AND leave_to >= NOW()";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $num_on_leave = $row['num_on_leave'];
} else {
    $num_on_leave = 0;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Welcome Employee!</title>
</head>

<body>
    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="profile.php">My Profile</a>
        <a href="leavesview.php">My Leaves</a>
        <a href="../logout.php">Logout</a>
    </div>
    <br><br>
    <div class="container">
        <h1>Welcome <?php echo $name  ?> to the Employee Portal!</h1>
        <p>Here you can view your leave balance, apply for leave, and view your leave history.</p>
        <a href="apply.php" class="btn">Apply for Leave</a>
        <a href="leavehistory.php" class="btn">View Leave History</a>
    </div>
    <div class="dashboard">
        <a class="dashboard__card pending">
            <h2><?php echo $leave_balance; ?> Days Left</h2>
            <p><?php // Display dashboard card
                if ($num_on_leave > 0) {
                    echo "<div class='card'>You are currently on leave</div>";
                } else {
                    echo "<div class='card'>You are currently not on leave</div>";
                } ?>
            </p>
        </a>
    </div>
</body>

</html>
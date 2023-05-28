<?php

//Creates a session or Resumes the current one based on a session identifier 
session_start();
require_once '../config.php';
//Checking if user is logged in
if (!$_SESSION['email']) {
    //Redirects to approriate page / method
    header('Location: ../login.php ');
}


$user_id = $_SESSION['id'];
$query = "SELECT leave.id, leave.employee_id, leave.leave_type_id, leave.leave_from, leave.leave_to, leave.description, leave.status, employee.name ,leave_type.l_name 
FROM `leave` 
INNER JOIN employee ON leave.employee_id = employee.id
INNER JOIN leave_type ON leave.leave_type_id = leave_type.id
WHERE leave.employee_id = $user_id;
";

$result = mysqli_query($con, $query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Employee</title>
</head>

<body>
    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="profile.php">My Profile</a>
        <a href="leavesview.php">My Leaves</a>
        <a href="../logout.php">Logout</a>
    </div>
    <br>
    <div class="table">
        <table id="leaves" class="table">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Leave From</th>
                <th>Leave To</th>
                <th>Leave Type</th>
                <th>Reasons</th>
                <th>Leave Status</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['leave_from'])); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['leave_to'])); ?></td>
                    <td><?php echo $row['l_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <br>
    <div class="panel-footer">Go back <a href="index.php">Home</a></div>
</body>

</html>
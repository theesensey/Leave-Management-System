<?php
session_start();
require_once '../config.php';

//Checking if user is logged in
if (!$_SESSION['email']) {
    //Redirects to appropriate page / method
    header('Location: ../login.php ');
}

$user_id = $_SESSION['id'];
$query = "SELECT leave.id, leave.employee_id, leave.leave_type_id, leave.leave_from, leave.leave_to, leave.description, leave.status, employee.name ,leave_type.l_name 
FROM `leave` 
INNER JOIN employee ON leave.employee_id = employee.id
INNER JOIN leave_type ON leave.leave_type_id = leave_type.id
;";

$result = mysqli_query($con, $query);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_leave_id'])) {
    $leave_id = $_POST['delete_leave_id'];
    $query = "DELETE FROM `leave` WHERE id=$leave_id ";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Leave request deleted successfully');</script>";
        header('Location: delete_leaves.php');
    } else {
        echo "<script>alert('Error deleting leave request');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Delete requests</title>
</head>

<body>
    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="manage_leaves.php">Manage Leaves</a>
        <a href="other.php">Manage System</a>
        <a href="../logout.php">Logout</a>
    </div>
    <!-- The main content area -->
    <br>
    <div class="table">
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Leave From</th>
                <th>Leave To</th>
                <th>Leave Type</th>
                <th>Reasons</th>
                <th>Leave Status</th>
                <th>Action</th>
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
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="delete_leave_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="button" onclick="return confirm('Are you sure you want to delete this leave request?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <br>
    <div class="panel-footer">Go back <a href="manage_leaves.php">Manage Leaves</a></div>

</body>

</html>
<?php
session_start();
require_once '../config.php';

//Checking if user is logged in
if (!$_SESSION['email']) {
    //Redirects to appropriate page / method
    header('Location: ../login.php ');
}

$query = "SELECT leave.id, leave.employee_id, leave.leave_type_id, leave.leave_from, leave.leave_to, leave.description, leave.status, employee.name ,leave_type.l_name 
FROM `leave` 
INNER JOIN employee ON leave.employee_id = employee.id
INNER JOIN leave_type ON leave.leave_type_id = leave_type.id
WHERE leave.status = 'pending';";

$result = mysqli_query($con, $query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE `leave` SET status = '$status' WHERE id = $leave_id";
    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        //Redirect to this page to refresh the list of pending requests
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        echo "<script>alert('Failed to update leave status.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Pending requests</title>

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
                        <form method="POST" id="leave-status-form">
                            <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                            <label for="status">Change leave status:</label>
                            <select name="status" id="status">
                                <option value="approved" <?php if ($row['status'] === 'approved') {
                                                                echo 'selected';
                                                            } ?>>Approved</option>
                                <option value="rejected" <?php if ($row['status'] === 'rejected') {
                                                                echo 'selected';
                                                            } ?>>Rejected</option>
                            </select>
                            <button type="submit" name="submit" onclick="return confirm('Are you sure you want to update the leave status?')">Submit</button>
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
<?php
if (isset($_POST['submit'])) {
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE `leave` SET `status`='$status' WHERE `id`=$leave_id";
    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        echo "<script>alert('Leave status updated successfully.')</script>";
    } else {
        echo "<script>alert('Failed to update leave status.')</script>";
    }
}
mysqli_close($con);
?>
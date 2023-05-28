<?php
// Creates a session or resumes the current one based on a session identifier
session_start();

require_once '../config.php';

// Checking if the user is logged in
if (!$_SESSION['email']) {
    // Redirects to the appropriate page/method
    header('Location: ../login.php');
    exit();
}

// Edit Leave Type action
if (isset($_POST['edit_leave_type'])) {
    $leave_type_id = $_POST['leave_type_id'];
    $new_leave_type_name = $_POST['new_leave_type_name'];

    $update_query = "UPDATE leave_type SET l_name = '$new_leave_type_name' WHERE id = $leave_type_id";
    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        echo "<script>alert('Leave type updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating leave type');</script>";
        mysqli_error($con);
    }
}

// Delete Leave Type action
if (isset($_POST['delete_leave_type'])) {
    $leave_type_id = $_POST['leave_type_id'];

    $delete_query = "DELETE FROM leave_type WHERE id = $leave_type_id";
    $delete_result = mysqli_query($con, $delete_query);

    if ($delete_result) {
        echo "<script>alert('Leave type deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting leave type.');</script>";
        mysqli_error($con);
    }
}

$query = "SELECT * FROM leave_type ORDER BY id";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($con)); // Print the error message if there is any query error
}

if (mysqli_num_rows($result) == 0) {
    echo "No Leave Types found."; // Print the message if the result set is empty
} else {
    // Display the data in a table format
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Leave Types</title>
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
                <th>Leave Type</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['l_name']; ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="leave_type_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="new_leave_type_name" value="<?= $row['l_name'] ?>" placeholder="New Leave Type Name">
                            <button type="submit" name="edit_leave_type">Edit</button>
                            <button type="submit" name="delete_leave_type">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <br>
    <div class="panel-footer">Go back <a href="other.php">Manage System</a></div>
</body>
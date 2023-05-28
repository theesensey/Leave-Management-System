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

$name = $_SESSION['name'];
$user_id = $_SESSION['id'];

// Edit Department action
if (isset($_POST['edit_department'])) {
    $department_id = $_POST['department_id'];
    $new_department_name = $_POST['new_department_name'];

    $update_query = "UPDATE department SET name = '$new_department_name' WHERE id = $department_id";
    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        echo "<script>alert('Department updated successfully.');</script>";
    } else {
        echo "<script>alert('Error Updating department.');</script>";
        mysqli_error($con);
    }
}

// Delete Department action
if (isset($_POST['delete_department'])) {
    $department_id = $_POST['department_id'];

    $delete_query = "DELETE FROM department WHERE id = $department_id";
    $delete_result = mysqli_query($con, $delete_query);

    if ($delete_result) {
        echo "<script>alert('Department deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting department:');</script>";
        mysqli_error($con);
    }
}

$query = "SELECT * FROM department ORDER BY id";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($con)); // Print the error message if there is any query error
}

if (mysqli_num_rows($result) == 0) {
    echo "No Departments found."; // Print the message if the result set is empty
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
    <title>Department</title>
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
                <th>Department Name</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="department_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="new_department_name" value="<?= $row['name'] ?>" placeholder="New Department Name">
                            <button type="submit" name="edit_department">Edit</button>
                            <button type="submit" name="delete_department">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <br>
    <div class="panel-footer">Go back <a href="other.php">Manage System</a></div>
</body>
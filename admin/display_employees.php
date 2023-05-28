<?php
// Creates a session or resumes the current one based on a session identifier 
session_start();

require_once '../config.php';

// Checking if user is logged in
if (!$_SESSION['email']) {
    // Redirects to appropriate page/method
    header('Location: ../login.php ');
}

$name = $_SESSION['name'];
$user_id = $_SESSION['id'];
$query = "SELECT e.*, d.name as department_name FROM employee e JOIN department d ON e.department_id = d.id";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($con)); // Print the error message if there is any query error
}

if (mysqli_num_rows($result) == 0) {
    echo "No users found."; // Print the message if the result set is empty
} else {
    // Display the data in a table format
}

// Delete action
if (isset($_POST['delete_employee'])) {
    $employee_id = $_POST['employee_id'];

    $delete_query = "DELETE FROM employee WHERE id = $employee_id";
    $delete_result = mysqli_query($con, $delete_query);

    if ($delete_result) {
        echo "<script>alert('Employee deleted successfully.');</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        echo "<script>alert('Error deleting employee.');</script>";
        mysqli_error($con);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Employees</title>
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
                <th>Email</th>
                <th>Mobile</th>
                <th>Department</th>
                <th>Address</th>
                <th>Date of Birth</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['mobile']; ?></td>
                    <td><?php echo $row['department_name']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['birthday'])); ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <a href="editemployee.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <input type="hidden" name="employee_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_employee" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <br>
    <div class="panel-footer">Go back <a href="other.php">Manage System</a></div>
</body>

</html>
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
                <th>Birthday</th>
                <th>Role</th>
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
                </tr>
            <?php } ?>
        </table>
    </div>
    <br>
    <div class="panel-footer">Go back <a href="index.php">Home</a></div>
</body>
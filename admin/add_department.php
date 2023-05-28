<?php
//Creates a session or Resumes the current one based on a session identifier 
session_start();
require_once '../config.php';

//Checking if user is logged in
if (!$_SESSION['email']) {
    //Redirects to approriate page / method
    header('Location: ../login.php ');
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data and sanitize it
    $name = mysqli_real_escape_string($con, $_POST['name']);

    // SQL query to insert the data into the database
    $sql = "INSERT INTO department (`name`) VALUES('$name')";

    // Execute the query
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Department successfully added.')</script>";
        header('location:display_departments.php');
        exit();
    } else {
        // If the query fails, display an error message
        echo "Error: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Add Department</title>
</head>

<body>
    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="manage_leaves.php">Manage Leaves</a>
        <a href="other.php">Manage System</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="content">
        <h2>Add New Department</h2>
        <div class="container">
            <form name="myForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input id="name" name="name" type="text" class="form-input" placeholder="Name">
                </div>
                <div class="form-control">
                    <button class="form-button" type="submit" class="btn btn-primary">Add New Department</button>
                </div>
                <br>
                <div class="panel-footer">Go back <a href="index.php">Home</a></div>
            </form>
        </div>
    </div>
    <script>
        function validateForm() {
            // Get form elements
            var name = document.forms["myForm"]["name"].value;
            // Validate name field
            if (name == "") {
                alert("Name field cannot be empty");
                document.forms["myForm"]["name"].focus();
                return false;
            }
        }
    </script>

</body>

</html>
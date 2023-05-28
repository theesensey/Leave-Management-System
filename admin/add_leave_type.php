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
    $l_name = mysqli_real_escape_string($con, $_POST['l_name']);

    // SQL query to insert the data into the database
    $sql = "INSERT INTO leave_type (l_name) VALUES ('$l_name')";

    // Execute the query
    if (mysqli_query($con, $sql)) {
        // If the query is successful, redirect to a success page
        header("Location: display_leave_types.php");
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
    <title>Add Leave Name</title>
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
        <h2>Add New Leave Type</h2>
        <div class="container">
            <form name="myForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label class="form-label" for="l_name">Name</label>
                    <input id="l_name" name="l_name" type="text" class="form-input" placeholder="Leave Type Name">
                </div>
                <div class="form-control">
                    <button class="form-button" type="submit" class="btn btn-primary">Add New Leave Type</button>
                </div>
                <br>
                <div class="panel-footer">Go back <a href="index.php">Home</a></div>
            </form>
        </div>
    </div>
    <script>
        function validateForm() {
            // Get form elements
            var name = document.forms["myForm"]["l_name"].value;
            // Validate name field
            if (name == "") {
                alert("Name field cannot be empty");
                document.forms["myForm"]["l_name"].focus();
                return false;
            }
        }
    </script>

</body>

</html>
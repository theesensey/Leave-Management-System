<?php

//Creates a session or Resumes the current one based on a session identifier 
session_start();

//Checking if user is logged in
if (!$_SESSION['email']) {
    //Redirects to approriate page / method
    header('Location: ../login.php ');
}
require_once '../config.php';
$sql = "SELECT * FROM leave_type ORDER BY l_name ASC";
$result = mysqli_query($con, $sql);

$name = $_SESSION['name'];
$employee_id = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $employee_id = $_SESSION['id'];
    $leave_type = mysqli_real_escape_string($con, $_POST['leave_type_id']);
    $leave_from = date('Y-m-d', strtotime($_POST['leave_from']));
    $leave_to = date('Y-m-d', strtotime($_POST['leave_to']));
    $leave_description = mysqli_real_escape_string($con, $_POST['description']);

    $sql = "INSERT INTO `leave` (employee_id, leave_type_id, leave_from, leave_to, description) 
    VALUES ('$employee_id', '$leave_type', '$leave_from', '$leave_to', '$leave_description')";
    mysqli_query($con, $sql);

    echo "<script>alert('Applied Successfully!');</script>";
    header("refresh:0.7; url=leavesview.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Accepted requests</title>
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
    <div class="content">
        <h2><?php echo $name  ?> Please fill your leave or an employee's leave</h2>
        <div class="container">
            <form name="myForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label class="form-label" for="employee_id">Employee Id</label>
                    <input value="<?= $employee_id ?>" id="employee_id" name="employee_id" type="text" class="form-input" placeholder="Employee Id">
                </div>
                <div class="form-group">
                    <label class="form-label" for="leave_type_id">Leave Type</label>
                    <select id="leave_type_id" name="leave_type_id" class="form-input" placeholder="Leave Id">
                        <option value="">Select Leave</option>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['l_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="leave_from">Leave From</label>
                    <input id="leave_from" name="leave_from" type="text" class="form-input" placeholder="Leave From (dd-mm-yyyy)">
                </div>
                <div class="form-group">
                    <label class="form-label" for="leave_to">Leave To</label>
                    <input id="leave_to" name="leave_to" type="text" class="form-input" placeholder="Leave To (dd-mm-yyyy)">
                </div>
                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <input type="description" name="description" class="form-input" placeholder="Description">
                </div>
                <div class="form-control">
                    <button type="submit" name="submit" class="btn btn-primary form-button">Apply</button>
                </div>
                <br>
                <div class="panel-footer">Go back <a href="manage_leaves.php">Manage Leaves</a></div>
            </form>
        </div>
    </div>
    <script>
        function validateForm() {
            var employee_id = document.getElementById("employee_id").value;
            var leave_type = document.getElementById("leave_type_id").value;
            var leaveFrom = document.getElementById("leave_from").value;
            var leaveTo = document.getElementById("leave_to").value;

            if (employee_id == "") {
                alert("Please fill in Employee Id");
                document.getElementById("employee_id").focus();
                return false;
            }
            if (leaveFrom == "" || leaveTo == "") {
                alert("Please fill in both Leave From and Leave To fields.");
                document.getElementById("leave_from").focus();
                document.getElementById("leave_to").focus();
                return false;
            }

            var datePattern = /^\d{2}-\d{2}-\d{4}$/; // regex pattern for dd-mm-yyyy format
            if (!leaveFrom.match(datePattern) || !leaveTo.match(datePattern)) {
                alert("Please enter valid dates in the format dd-mm-yyyy");
                return false;
            }

            var leaveFromDate = new Date(leaveFrom.split("-").reverse().join("-"));
            var leaveToDate = new Date(leaveTo.split("-").reverse().join("-"));
            var today = new Date();
            today.setHours(0, 0, 0, 0); // set the time to 00:00:00:00 to compare dates only
            if (leaveFromDate < today || leaveToDate < today) {
                alert("Leave start and end dates cannot be before today's date.");
                document.getElementById("leave_from").focus();
                return false;
            }
            if (leaveToDate < leaveFromDate) {
                alert("Leave To date cannot be before Leave From date.");
                document.getElementById("leave_to").focus();
                return false;
            }
            return true;
        }
    </script>

</body>

</html>
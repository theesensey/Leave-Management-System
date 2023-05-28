<?php
// Creates a session or resumes the current one based on a session identifier 
session_start();

require_once '../config.php';

// Checking if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirects to the appropriate page/method
    header('Location: ../login.php');
    exit;
}

// Retrieve the employee ID from the URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$employee_id = $_GET['id'];

// Fetch the employee details from the database
$query = "SELECT * FROM employee WHERE id = $employee_id";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}

if (mysqli_num_rows($result) == 0) {
    echo "Employee not found.";
    exit;
}

$row = mysqli_fetch_assoc($result);

$employee_name = $row['name'];
$employee_email = $row['email'];
$employee_mobile = $row['mobile'];
$employee_department_id = $row['department_id'];
$employee_address = $row['address'];
$employee_birthday = $row['birthday'];
$employee_role = $row['role'];


// Update action
if (isset($_POST['update_employee'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $department_id = $_POST['department_id'];
    $address = $_POST['address'];
    $birthday = date('d-m-y', strtotime($_POST['birthday']));
    $role = $_POST['role'];

    $update_query = "UPDATE employee SET name = '$name', email = '$email', mobile = '$mobile', department_id = '$department_id', address = '$address', birthday = '$birthday', role = '$role' WHERE id = $employee_id";
    // Only update the password if a new password is provided
    if (!empty($password)) {
        $update_query .= ", password = '$password'";
    }

    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        echo "<script>alert('Employee updated successfully.');</script>";
        header('refresh:2; url=display_employees.php');
    } else {
        echo "<script>alert('Error updating employee.');</script>";
        echo mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Edit Employee</title>
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
        <h2>Edit Employee</h2>
        <div class="container">
            <form name="myForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input id="name" name="name" type="text" class="form-input" placeholder="Name" value="<?php echo $employee_name; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="text" class="form-input" placeholder="Email" value="<?php echo $employee_email; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="birthday">Date Of Birth</label>
                    <input id="birthday" name="birthday" type="text" class="form-input" placeholder="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($employee_birthday)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="department_id">Department</label>
                    <select id="department_id" name="department_id" class="form-input" required>
                        <option value="">Select Department</option>
                        <?php
                        $res = mysqli_query($con, "SELECT * FROM department ORDER BY name DESC");
                        while ($row = mysqli_fetch_assoc($res)) {
                            if ($employee_department_id == $row['id']) {
                                echo "<option selected='selected' value=" . $row['id'] . ">" . $row['name'] . "</option>";
                            } else {
                                echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="role">User Type</label>
                    <select id="role" name="role" class="form-input">
                        <option value="User" <?php if ($employee_role == 'User') echo 'selected'; ?>>User</option>
                        <option value="Admin" <?php if ($employee_role == 'Admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="mobile">Contact</label>
                    <input id="mobile" name="mobile" type="text" class="form-input" placeholder="Mobile" value="<?php echo $employee_mobile; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="address">Address</label>
                    <input id="address" name="address" type="text" class="form-input" placeholder="Address" value="<?php echo $employee_address; ?>">
                </div>
                <div class="form-control">
                    <button class="form-button" type="submit" class="btn btn-primary" name="update_employee">Edit Employee</button>
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
            var email = document.forms["myForm"]["email"].value;
            var birthday = document.forms["myForm"]["birthday"].value;
            var password = document.forms["myForm"]["password"].value;
            var mobile = document.forms["myForm"]["mobile"].value;
            var address = document.forms["myForm"]["address"].value;

            // Validate name field
            if (name == "") {
                alert("Name field cannot be empty");
                document.forms["myForm"]["name"].focus();
                return false;
            }

            // Validate birthday field
            if (birthday == "") {
                alert("Birthday field cannot be empty");
                document.forms["myForm"]["birthday"].focus();
                return false;
            }

            // Validate mobile field
            if (mobile == "") {
                alert("Mobile field cannot be empty");
                document.forms["myForm"]["mobile"].focus();
                return false;
            }

            // Validate address field
            if (address == "") {
                alert("Address field cannot be empty");
                document.forms["myForm"]["address"].focus();
                return false;
            }

            // Validate email field
            if (email == "") {
                alert("Email field cannot be empty");
                document.forms["myForm"]["email"].focus();
                return false;
            } else {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert("Invalid email format");
                    document.forms["myForm"]["email"].focus();
                    return false;
                }
            }

            // Validate birthday field
            if (birthday == "") {
                alert("Birthday field cannot be empty");
                document.forms["myForm"]["birthday"].focus();
                return false;
            } else {
                var dateRegex = /^([0-9]{2})-([0-9]{2})-([0-9]{4})$/;
                if (!dateRegex.test(birthday)) {
                    alert("Invalid date format. Please use dd-mm-year");
                    document.forms["myForm"]["birthday"].focus();
                    return false;
                }
            }

            // Validate password field
            if (password == "") {
                alert("Password field cannot be empty");
                document.forms["myForm"]["password"].focus();
                return false;
            } else {
                var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
                if (!passwordRegex.test(password)) {
                    alert("Password must contain at least 8 characters, including one uppercase letter, one lowercase letter, and one number");
                    document.forms["myForm"]["password"].focus();
                    return false;
                }
            }

            // Validate mobile field
            if (mobile == "") {
                alert("Mobile field cannot be empty");
                document.forms["myForm"]["mobile"].focus();
                return false;
            } else {
                var mobileRegex = /^[0-9]+$/;
                if (!mobileRegex.test(mobile)) {
                    alert("Mobile field must contain only numbers");
                    document.forms["myForm"]["mobile"].focus();
                    return false;
                }
            }
        }
    </script>

</body>

</html>
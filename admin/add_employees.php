<?php
//Creates a session or Resumes the current one based on a session identifier 
session_start();
require_once '../config.php';

//Checking if user is logged in
if (!$_SESSION['email']) {
    //Redirects to approriate page / method
    header('Location: ../login.php ');
}
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($con, $_POST['role']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $department_id = mysqli_real_escape_string($con, $_POST['department_id']);
    $birthday = date('Y-m-d', strtotime($_POST['birthday']));
    $address = mysqli_real_escape_string($con, $_POST['address']);

    // Hash the password
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO employee(name,email,mobile,password,department_id,address,birthday,role) VALUES('$name','$email','$mobile','$hashed_password','$department_id','$address','$birthday','$role')";
    mysqli_query($con, $sql);
    header('location:display_employees.php');
    die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Add Employee</title>
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
        <h2>My Profile</h2>
        <div class="container">
            <form name="myForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input id="name" name="name" type="text" class="form-input" placeholder="Name">
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="text" class="form-input" placeholder="Email">
                </div>
                <div class="form-group">
                    <label class="form-label" for="birthday">Date Of Birth</label>
                    <input id="birthday" name="birthday" type="text" class="form-input" placeholder="dd-mm-yyyy">
                </div>

                <div class="form-group">
                    <label class="form-label" for="department_id">Department</label>
                    <select id="department_id" name="department_id" class="form-input">
                        <option value="">Select Department</option>
                        <?php
                        $res = mysqli_query($con, "SELECT * FROM department ORDER BY name DESC");
                        while ($row = mysqli_fetch_assoc($res)) {
                            if ($department_id == $row['id']) {
                                echo "<option selected='selected' value=" . $row['id'] . ">" . $row['name'] . "</option>";
                            } else {
                                echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label" for="role">User Type</label>
                    <select id="role" name="role" class="form-input">
                        <option value="User">User</option>
                        <option value="Admin">Admin</option>

                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="mobile">Contact</label>
                    <input id="mobile" name="mobile" type="text" class="form-input" placeholder="mobile">
                </div>
                <div class="form-group">
                    <label class="form-label" for="address">Address</label>
                    <input id="address" name="address" type="text" class="form-input" placeholder="address">
                </div>
                <div class="form-control">
                    <button class="form-button" type="submit" class="btn btn-primary">Add New Employee</button>
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
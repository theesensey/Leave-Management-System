<?php
session_start();
require 'config.php';

if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo "<script>
        alert('Invalid email format!');
        </script>";
        exit;
    }
    // Ignore input that is not string to prevent SQL injection
    $email = mysqli_real_escape_string($con, $email);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Query the employee table to find a matching record
    $query = "SELECT * FROM employee WHERE email='$email'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));

    // Check if a matching record was found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Save user data to session
            $_SESSION["id"] = $row["id"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["role"] = $row["role"];

            // Redirect to appropriate landing page
            if ($row["role"] == "admin") {
                header("Location: admin/index.php");
            } else {
                header("Location: user/index.php");
            }
            exit();
        } else {
            // Show an error message if the email or password is incorrect
            echo "<script>
            alert('Invalid email or password!');
            </script>";
        }
    } else {
        // No matching record found
        $error_message = "User with email $email does not exist.";
        $msg = "Please enter correct login details";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Log In</title>
</head>

<body>
    <h3>LOGIN</h3>
    <br>
    <p>Log in to Leave-Management System</p>
    <form method="post" name="myForm" onsubmit="return validateForm()">
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Email">
        </div><br>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password(min. 6 characters)">
        </div><br>
        <div class="form-group">
            <input type="submit" name="login" value="Login" class="btn btn-primary">
        </div><br>
    </form>
    <div class="panel-footer">Go back to home<a href="index.php"> Home</a> &nbsp&nbsp <a href="#">Forgot Password</a>&nbsp&nbsp
        <a href="signup.php">Sign Up</a>
    </div>

    <script>
        function validateForm() {
            var email = document.forms["myForm"]["email"].value;
            var password = document.forms["myForm"]["password"].value;
            if (email == "" || password == "") {
                alert("Please fill in all fields");
                return false;
            }
            if (password.length < 6) {
                alert('Password must be at least 6 characters long!');
                event.preventDefault();
            }
        }
    </script>
</body>

</html>
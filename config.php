<?php

//Config with parameters
$server = "localhost"; //"Localhost" refers to the local computer that a program is running on
$username = "root";  // Specifies the MySQL username
$password = ""; // Specifies the MySQL password
$dbname = "leavemanagement"; //Specifies the default database to be used

// "mysqli_connect" Opens a new connection to the MySQL server
$con = mysqli_connect($server, $username, $password, $dbname);
if (!$con) {
  echo "Connection failed, Check Configuration!";
}

<?php

include_once "config.php";

if (isset($_SESSION['email'])) {
    $_SESSION['email'] = null;
    session_unset();
    session_destroy();
}

header('Location: login.php');
exit();

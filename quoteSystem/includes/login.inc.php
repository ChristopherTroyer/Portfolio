<?php

if(isset($_POST["submit"])) { // check for existence of var
    $username = $_POST["username"]; // credentials retrieved from form
    $pass = $_POST["pass"];

    require_once 'dbh.inc.php'; // database handler
    require_once 'functions.inc.php'; // utility functions for error checking credentials / login utils

    if(emptyInputLogin($username, $pass) !== false) { // throw error if username or pass are empty
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    loginUser($conn, $username, $pass); // no error was thrown so attempt to log in user with credentials

} else {
    header("location: ../login.php"); // return to login.php, no var was set
    exit();
}

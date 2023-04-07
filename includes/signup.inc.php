<?php

/*
use data passed from form to add user to db, process credentials first, create new user if no errors thrown
*/

if(isset($_POST["submit"])) { // is var set
    $name = $_POST["name"]; // retrieved data to process
    $email = $_POST["email"];
    $username = $_POST["username"];
    $pass = $_POST["pass"];
    $reppass = $_POST["reppass"];

    require_once 'dbh.inc.php'; // database handler
    require_once 'functions.inc.php'; // util functions to process data

    // process data retrieved, exit if error thrown

    if(emptyInputSignup($name, $email, $username, $pass, $reppass) !== false) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }
    if(invalidUsername($username) !== false) {
        header("location: ../signup.php?error=invalidUsername");
        exit();
    }
    if(invalidEmail($email) !== false) {
        header("location: ../signup.php?error=invalidEmail");
        exit();
    }
    if(passwordMatch($pass, $reppass) !== false) {
        header("location: ../signup.php?error=passmissmatch");
        exit();
    }
    if(usernameExists($conn, $username, $email) !== false) {
        header("location: ../signup.php?error=usernametaken");
        exit();
    }

    createUser($conn, $name, $email, $username, $pass); // no errors thrown, attempt to add user to database




} else {
    header("location: ../signup.php"); // var was not set, return to signup.php
    exit();
}

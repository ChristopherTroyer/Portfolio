<?php

/*
use data passed from form to add user to db, process credentials first, create new user if no errors thrown
*/

if(isset($_POST["submit"])) { // is var set

    $first_name = $_POST["first_name"]; // retrieve data to process
    $last_name = $_POST["last_name"];
    $username = $_POST["username"];
    $pass = $_POST["pass"];
    $reppass = $_POST["reppass"];
    $address = $_POST["address"];
    $commission = $_POST["commission"];
    $email = $_POST["email"];
    $permission = $_POST["permission"];


    require_once 'dbh.inc.php'; // database handler
    require_once 'functions.inc.php'; // util functions to process data

    // process data retrieved, exit if error thrown

    if(emptyInputSignup($first_name, $last_name, $username, $pass, $reppass, $address, $commission, $email, $permission) !== false) {
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


    // no errors thrown, attempt to add user to database
    createUser($conn, $first_name, $last_name, $username, $pass, $address, $commission, $email, $permission);




} else {
    header("location: ../signup.php"); // var was not set, return to signup.php
    exit();
}

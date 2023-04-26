<?php

/*
    Error handling for signup.inc.php and utility functions for login.inc.php
*/

function emptyInputSignup($name, $email, $username, $pass, $reppass) {
    /*
    Check if any of the parameters passed in are empty, return true if so
    */
    $result = false;
    if(empty($name) || empty($email) || empty($username) || empty($pass) || empty($reppass)) {
        $result = true;
    } 
    return $result;
}

function invalidUsername($username) {
    /*
    Check if the username is invalid by using an expression
    return true if invalid
    */
    $result = false;
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    }

    return $result;
}

function invalidEmail($email) {
    /*
    Check if provided email is invalid using filter_var and the filter: FILTER_VALIDATE_EMAIL
    return true if email is invalid
    */
    $result = false;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }

    return $result;
}

function passwordMatch($pass, $reppass) {
    /*
    Check if password and confirm password match
    return true if there is a missmatch
    */
    $result = false;
    if($pass !== $reppass) {
        $result = true;
    }

    return $result;
}

function usernameExists($conn, $username, $email) {
    /*
    Check if the username provided exists:
        connect to db and search for a user using username provided or email
        return false when no match found
    */
    //$sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;"; // query for finding a possible match
    $sql = "SELECT * FROM Associate WHERE username = ? OR email = ?;"; // query for finding a possible match
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) { // error checking the query
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt); // execute query

    $resultData = mysqli_stmt_get_result($stmt); // store result from query

    if($row = mysqli_fetch_assoc($resultData)) {
        // match was found, return something other than false
        return $row;
    } else {
        // no matching username was found
        echo "rte";
        $result = false;
        return $result; 
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $first_name, $last_name, $username, $pass, $address, $commission, $email, $usersPerms){
    /*
    Create user using parameters

    Updated to handle Associate table and new permission levels
    */
    $sql = "insert into Associate (First_name, last_name, username, password, address, commission, email, usersPerms) values (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) { // error checking for query
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPass = password_hash($pass, PASSWORD_DEFAULT); // create a password hash using default algorithm

    mysqli_stmt_bind_param($stmt, "sssssdsi",$first_name, $last_name, $username, $hashedPass, $address, $commission,$email, $usersPerms);
    mysqli_stmt_execute($stmt); // execute creation of new user

    mysqli_stmt_close($stmt);

    header("location: ../signup.php?error=none"); // placeholder TODO
    exit();
}

/*
Utils for login.inc.php
*/

function emptyInputLogin($username, $pass) {
    /*
    Check if log credentials are empty
    return true if empty
    */
    $result = false;
    if(empty($username) || empty($pass)) {
        $result = true;
    }

    return $result;
}

function loginUser($conn, $username, $pass) {
    /*
    Log in user using parameters

    Updated to handle new Associate table

    NOTE: Email/username bug seems fixed
    */
    $uidExists = usernameExists($conn, $username, $username); // call function to check for existing username

    if($uidExists === false) { // throw error if user not found
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    // no error so proceed with logging in user
    //$passHashed = $uidExists["usersPass"];
    $passHashed = $uidExists["password"];
    $checkPass = password_verify($pass, $passHashed); // verify that the password user entered matches the hash

    if($checkPass === false) { // no match from pass to hash, throw error
        header("Location: ../login.php=wrongloginpass");
        exit();
    }
    else if($checkPass === true) {
        session_start();
        $_SESSION["userid"] = $uidExists["AssocID"];
        $_SESSION["username"] = $uidExists["username"];
        $_SESSION["name"] = $uidExists["First_name"];
        $_SESSION["perms"] = $uidExists["usersPerms"];

        // handle taking associate to associate page, admin to admin page, hq to hq page

        /*
        go to associate access page
        permission for associate: 0
        */
        if($_SESSION["perms"] == 0) {
            // go to associate page
        }
        else if($_SESSION["perms"] == 1) {
            // go to hq page
        }
        else if($_SESSION["perms"] == 2) {
            // go to admin page
            header("location: ../admin.php");
        }

        //header("location: ../index.php");
        exit();
    }
}

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
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;"; // query for finding a possible match
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

function createUser($conn, $name, $email, $username, $pass){
    /*
    Create user using parameters
    */
    $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPass) VALUES (?, ?, ?, ?);"; // query to create user in db
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) { // error checking for query
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPass = password_hash($pass, PASSWORD_DEFAULT); // create a password hash using default algorithm
    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $name, $hashedPass);
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

    NOTE: can only user EMAIL to log in, wronglogin is thrown with username instead of email
    */
    $uidExists = usernameExists($conn, $username, $username); // call function to check for existing username

    if($uidExists === false) { // throw error if user not found
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    // no error so proceed with logging in user
    $passHashed = $uidExists["usersPass"];
    $checkPass = password_verify($pass, $passHashed); // verify that the password user entered matches the hash

    if($checkPass === false) { // no match from pass to hash, throw error
        header("Location: ../login.php=wrongloginpass");
        exit();
    }
    else if($checkPass === true) {
        session_start();
        $_SESSION["userid"] = $uidExists["usersId"];
        $_SESSION["username"] = $uidExists["usersUid"];
        $_SESSION["name"] = $uidExists["usersName"];
        header("location: ../index.php");
        exit();
    }
}

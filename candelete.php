<?php

// create a table inside the database, it keeps creating it everytime its run
// so it only needs to be run once

$dbhost = "localhost"; //where to connect
$dbuser = "root"; //usename
$dbpass = "password"; // password
$dbname = "testing"; //database name
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if(! $conn) { // throw an error if the connection to the db fails
    echo 'conn failure!';
} else {
    echo 'connected!';
}


/*
    create table in db named "users"
    usersId is the primary key
    usersPerms: 0 = customer, 1 = associate, 2 = hq, 3 = admin
*/
$sql = "CREATE TABLE users (
    usersId int(11) AUTO_INCREMENT,
    usersPerms int(11) NOT NULL DEFAULT 0,
    usersName varchar(128) NOT NULL,
    usersEmail varchar(128) NOT NULL,
    usersUid varchar(128) NOT NULL,
    usersPass varchar(128) NOT NULL,
    primary key(usersId)
    )";

if(mysqli_query($conn, $sql)) { // throw error if the query failed
    echo 'Db created!';
} else {
    echo 'there was some error: '.mysqli_error($conn);
}

mysqli_close($conn); // close connection
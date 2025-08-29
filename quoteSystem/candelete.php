<?php

// create a table inside the database, it keeps creating it everytime its run
// so it only needs to be run once

$dbhost = "localhost"; //where to connect
$dbuser = "debian-sys-maint"; //usename
$dbpass = "vUlvuFil887Af63z"; // password
$dbname = "testing"; //database name
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if(! $conn) { // throw an error if the connection to the db fails
    echo 'conn failure!';
} else {
    echo 'connected!';
}

/*
    runs the Quote_Tables.sql file
*/
$sql = file_get_contents("Quote_Tables.sql");

if(mysqli_multi_query($conn, $sql)) { // throw error if the query failed
    echo 'Db created!';
} else {
    echo 'there was some error: '.mysqli_error($conn);
}

mysqli_close($conn); // close connection
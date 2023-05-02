<?php

/*
 legacy db handler:
 set credential variables and attempt to connect using credentials,
 if it fails to connect it will error out
*/

$serverName = "blitz.cs.niu.edu";
$dbUsername = "student";
$dbPassword = "student";
$dbName = "csci467";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if(!$conn) {
    die("Connection failed: " .mysqli_connect_error());
}
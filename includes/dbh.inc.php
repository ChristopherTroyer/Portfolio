<?php

/*
 db handler:
 set credential variables and attempt to connect using credentials,
 if it fails to connect it will error out
*/

$serverName = "localhost";
$dbUsername = "debian-sys-maint";
$dbPassword = "vUlvuFil887Af63z";
$dbName = "testing";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if(!$conn) {
    die("Connection failed: " .mysqli_connect_error());
}

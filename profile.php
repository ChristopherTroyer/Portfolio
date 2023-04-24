<?php
 include_once 'header.php';
 //pull the user's name to display using the account's userid number
 //$sql = "SELECT usersName FROM users WHERE usersid = '{$_SESSION['usersid']}'";
 echo $_SESSION['name'];
 //use userid to get user's inputted name
?>

<main>This is a test for a user's profile page</main>


<?php
 include_once 'footer.php'
?>

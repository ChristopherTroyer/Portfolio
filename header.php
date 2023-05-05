<?php
 session_start();

?>
<!DOCTYPE html>

<html>
    <head>
        <title>Quotes and Such</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="topnav">
            <?php
            //Show that user is logged in inside header
            if(isset($_SESSION["userid"])) {
                //echo "<a href='profile.php'>Profile</a>";
                echo "<a href='includes/logout.inc.php'>Sign Out</a>";

                // check if associate is admin
                if($_SESSION["perms"] == 2) {
                    // allow access to associate signup
                    echo "<a href='signup.php'>Add user</a>";
                    //allows admin to go back to main page to see assoc or quote
                    echo "<a href='admin.php'>Back</a>";
                }

                if ($_SESSION["perms"] == 1) {
                    echo "<a href='hq_access.php'>Sanction Quotes</a>";
                }

            } else { // user is not logged in
                echo "<a href='login.php'>Log In</a>";
                //echo "<a href='signup.php'>Sign Up</a>";
            }
            ?>
</div>

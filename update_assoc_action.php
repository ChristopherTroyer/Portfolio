<?php
    include 'header.php';
?>

<main> This is test to edit associates</main>

<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';
    require_once 'adminfunctions.php';

    if (isset($_POST['update']))
    {
         //put characters in a string
        $aid = mysqli_real_escape_string($mysqli, $_POST['AssocID']);
        $first = mysqli_real_escape_string($mysqli, $_POST['First_name']);
        $last = mysqli_real_escape_string($mysqli, $_POST['last_name']);
        $username = mysqli_real_escape_string($mysqli, $_POST['username']);
        $password = mysqli_real_escape_string($mysqli, $_POST['password']);
        $addy = mysqli_real_escape_string($mysqli, $_POST['address']);
        $commission = mysqli_real_escape_string($mysqli, $_POST['commission']);
        $permission = mysqli_real_escape_string($mysqli, $_POST['permission']);
        $email = mysqli_real_escape_string($mysqli, $_POST['email']);

        // Check for empty fields
        if (empty($aid) || empty($first) || empty($last) || empty($username) || empty($password) || empty($addy) || empty($commission) || empty($permission) || empty($email) )
        {
            if (empty($aid))
            {
                echo "<font color='red'>Name field is empty.</font><br/>";
            }

            if (empty($first))
            {
                echo "<font color='red'>Age field is empty.</font><br/>";
            }

            if (empty($last))
            {
                echo "<font color='red'>Email field is empty.</font><br/>";
            }
        
            if (empty($username))
            {
                echo "<font color='red'>Name field is empty.</font><br/>";
            }

            if (empty($password))
            {
                echo "<font color='red'>Age field is empty.</font><br/>";
            }

            if (empty($addy))
            {
                echo "<font color='red'>Email field is empty.</font><br/>";
            }
        
            if (empty($commission))
            {
                echo "<font color='red'>Name field is empty.</font><br/>";
            }

            if (empty($permission))
            {
                echo "<font color='red'>Age field is empty.</font><br/>";
            }

            if (empty($email))
            {
                echo "<font color='red'>Email field is empty.</font><br/>";
            }
        }
        else
        {
            // Update the dbh
            $result = mysqli_query($mysqli, "UPDATE users SET `fname` = '$first', `lname` = '$last', `uname` = '$username', `pword` = '$password', `address` = '$addy', `commission` = '$commission', `permission` = '$permission', `email` = '$email' WHERE `id` = $id");

            // Display success
            echo "<p><font color='green'>Data updated successfully!</p>";
            echo "<a href='admin.php'>Back</a>";
        }
    }

?>

<?php
    include 'footer.php';
?>

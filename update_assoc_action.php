<?php
    include 'header.php';
?>

<main> This is test to edit associates</main>

<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';

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

        // Check for empty fields and output errors if the fields are emptiy
        if (empty($aid) || empty($first) || empty($last) || empty($username) || empty($password) || empty($addy) || empty($commission) || empty($permission) || empty($email) )
        {
            if (empty($aid))
            {
                echo "<font color='red'>Associate ID field is empty.</font><br/>"; //associate ID
            }

            if (empty($first))
            {
                echo "<font color='red'>First Name field is empty.</font><br/>"; //first name
            }

            if (empty($last))
            {
                echo "<font color='red'>Last Name field is empty.</font><br/>"; //last name
            }
        
            if (empty($username))
            {
                echo "<font color='red'>Username field is empty.</font><br/>"; //username
            }

            if (empty($password))
            {
                echo "<font color='red'>Password field is empty.</font><br/>"; //password
            }

            if (empty($addy))
            {
                echo "<font color='red'>Address field is empty.</font><br/>"; //address
            }
        
            if (empty($commission))
            {
                echo "<font color='red'>Commission field is empty.</font><br/>"; //commission
            }

            if (empty($permission))
            {
                echo "<font color='red'>Permission field is empty.</font><br/>"; //permission
            }

            if (empty($email))
            {
                echo "<font color='red'>Email field is empty.</font><br/>"; //email
            }
        }
        else
        {
            //if the fields being updated are not empty then update the associates information
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

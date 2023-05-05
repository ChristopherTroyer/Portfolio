<?php
    include 'header.php';
?>

<main>This is a test to edit associate info</main>

<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';
    require_once 'adminfunctions.inc.php';

// Get id from URL
$id = $_GET['AssocID'];

// Select data associated with id
$result = mysqli_query($conn, "SELECT * FROM Associate WHERE AssocID = $id");

// Fetch the next row of a result set as array
$resultData = mysqli_fetch_assoc($result);

$fname = $resultData['First_name'];
$lname = $resultData['last_name'];
$uname = $resultData['username'];
$pword = $resultData['password'];
$addy = $resultData['address'];
$commission = $resultData['commission'];
$permission = $resultData['permission'];
$email = $resultData['email'];

?>

<!--
    HTML covering the fields to edit the associates information
    WARNING: THIS IS NOT TESTED WITH USER DATA.
-->

<html>
<head>
        <title>Edit Associate</title>
</head>

<body>
    <h2>Edit Data</h2>
    <p>
            <a href="admin.php">Back</a>
    </p>

        <form name="edit" method="post" action="update_assoc_action.php">
                <table border="0">
                        <tr>
                                <td>Name</td>
                                <td><input type="text" name="first name" value="<?php echo $fname; ?>"></td>
                        </tr>
                        <tr>
                                <td>Age</td>
                                <td><input type="text" name="last name" value="<?php echo $lname; ?>"></td>
                        </tr>
                        <tr>
                                <td>Email</td>
                                <td><input type="text" name="username" value="<?php echo $uname; ?>"></td>
                        </tr>
                        <tr>
                         <td>Name</td>
                <td><input type="text" name="password" value="<?php echo $pword; ?>"></td>
            </tr>
            <tr>
                <td>Age</td>
                <td><input type="text" name="address" value="<?php echo $addy; ?>"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="comission" value="<?php echo $commission; ?>"></td>
            </tr>
            <tr>
             <td>Name</td>
                <td><input type="text" name="permission" value="<?php echo $permission; ?>"></td>
            </tr>
            <tr>
                <td>Age</td>
                <td><input type="text" name="email" value="<?php echo $email; ?>"></td>
            </tr>
            <tr>
                                <td><input type="hidden" name="id" value=<?php echo $id; ?>></td>
                                <td><input type="submit" name="update" value="Update"></td>
                        </tr>
                </table>
        </form>
</body>
</html>

<?php
    include 'footer.php';
?>

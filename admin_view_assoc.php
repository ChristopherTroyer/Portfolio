<!--
    This allows the admin to view, edit, add, and delete
    associates in the company. 
-->
<?php
    include_once 'header.php';
?>

<main>This is a test for showing associates</main>

<html>
<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';
    require_once 'admin/adminfunctions.inc.php';

    function show_all()
    {
        echo
        ("
            <tablee border=solid>
            <tr>
            <th> Associate ID </th>
            <th> First Name </th>
            <th> Last Name </th>
            <th> Username </th>
            <th> Password </th>
            <th> Email </th>
            <th> Address </th>
            <th> Commission </th>
            <th> Permission </th>
            </tr>
        ");
        $ID = show_all();
        
    }
    catch(PDOexception $e)
    {
        echo "Connection failed: " . &e->getMessage();
    }
?>

</html>

<?php
    include_once 'footer.php';
?>
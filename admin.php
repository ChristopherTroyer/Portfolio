<?php
 include_once 'header.php';
?>

<!--
    This allows the admin to view, edit, add, and delete
    associates in the company.
-->

<br />
<h2>Current Associates</h2>
<br />

<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/dbh.inc.php';
    require_once 'includes/functions.inc.php';
    require_once 'adminfunctions.inc.php';

    function show_all_assoc()
    {
        echo
        ("
            <table border=solid>
            <tr>
            <th> ID </th>
            <th> Username </th>
            <th> First Name </th>
            <th> Last Name </th>
            <th> Commission </th>
            </tr>
        ");
        $ID = associateIDS();
        foreach($ID as $arrID)
        {
            $assoc = associateinfo($arrID);

            echo ("<tr>");
                echo ("<td>" . $arrID . "</td>");
                echo ("<td>" . $assoc['username'] . "</td>");
                echo ("<td>" . $assoc['First_name'] . "</td>");
                echo ("<td>" . $assoc['last_name'] . "</td>");
                echo ("<td>" . $assoc['commission'] . "</td>");
                echo '<td><a href="view_all_info.php='. $arrID.'">View All Info</a></td?';
                echo '<td><a href="update_assoc.php?id='.$arrID.'">Update</a></td>';
                echo '<td><a href="delete_assoc.php?id='.$arrID.'">Delete</a></td>';
            echo ("</tr>");
        }
    }

    try
    {
        show_all_assoc();
    }
    catch(PDOexception $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?>



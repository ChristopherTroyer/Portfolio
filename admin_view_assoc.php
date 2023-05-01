<!--
    This allows the admin to view, edit, add, and delete
    associates in the company.
-->
<?php
    include_once 'header.php';
?>

<br />
<h2>Current Associates</h2>
<br />

<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';
    require_once 'adminfunctions.inc.php'; //I didn't know if I threw functions in the includes if it would be ok
                                           //I dont want to wake people from slumber Mimimimi

    function show_all_assoc()
    {
        //build table for associate preview information
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
        //finds all the associate IDS in the dbh
        $ID = associateIDS();
        foreach($ID as $arrID)
        {
            //gets all information for each assoiate in dbh
            $assoc = associateinfo($arrID);
            
            //small amount like a preview
            echo ("<tr>");
                echo ("<td>" . $arrID . "</td>");
                echo ("<td>" . $assoc['username'] . "</td>");
                echo ("<td>" . $assoc['First_name'] . "</td>");
                echo ("<td>" . $assoc['last_name'] . "</td>");
                echo ("<td>" . $assoc['commission'] . "</td>");
                echo '<td><a href="view_all_info.php?if='.$arrID.'">View</a></td>'; //view all information
                echo '<td><a href="update_assoc.php?id='.$arrID.'">Update</a></td>'; //update and edit associate info
                echo '<td><a href="delete_assoc.php?id='.$arrID.'">Delete</a></td>'; //delete associate from dbh
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

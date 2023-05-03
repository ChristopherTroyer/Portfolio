<!--
    This sets up the home page for administration.
    There will be two buttons to view associates and view quotes
-->


<?php
 include_once 'header.php';
?>

<html>
<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';

    try
    {
//        if(isset($_POST["assoc"]))
//        {
            //this will have everything associate related
//            header("location:admin_view_assoc.php");
//        }
        if(isset($_POST["quote"]))
        {
            //this will have everything quote related
            header("location:search_quotes.php");
        }
    }

    catch(PDOexception $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?>

    <body>
        <form method="POST">
            <br />
            <!-- This button was broken, I brought page to the button -->
<!--            <input type="submit" name="associate" value="Associate"> -->
            <input type="submit" name="quote" value="Quotes">
            <br />
        </form>
    </body>
</html>


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



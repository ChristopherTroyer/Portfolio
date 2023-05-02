<!--
    This sets up the home page for administration.
    There will be two buttons to view associates and view quotes
-->

<?php
 include_once 'header.php'
?>

<html>
<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';

    try
    {
        if(isset($_POST["assoc"]))
        {
            //this will have everything associate related
            header("location:admin_view_assoc.php");
        }
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
            <input type="submit" name="associate" value="Associates">
            <input type="submit" name="quote" value="Quotes">
            <br />
        </form>
    </body>
</html>

<?php
 include_once 'footer.php'
?>


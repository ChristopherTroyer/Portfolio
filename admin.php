<!--
    This sets up the home page for administration.
    There will be two buttons to view associates and view quotes
-->

<?php
 include_once 'header.php';
?>

<main>This is a test for admin page</main>

<?php
    session_start();

    try
    {
        if(isset($_POST["assoc"]))
        {
            //this will have everything associate related
            header("location:admin_view_assoc.php");
        }
        if
        {
            //this will have everything quote related
            header("location:search_quotes.php");
        }
    }

    (PDOexception $e)
    {
        echo "Connection failed: " . &e->getMessage();
    }
?>

<?php
 include_once 'footer.php'
?>


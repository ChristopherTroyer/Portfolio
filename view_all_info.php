<!--
    Shows all information for a selected associate
-->
<?php
    include 'header.php';
?>

<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';
    require_once 'adminfunctions.inc.php';

    try
    {
        show_all(); //grabs information for associate (need to test)
    }
    catch(PDOexception $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?>
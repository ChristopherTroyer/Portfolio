<!--
    Delete a user, does double check to make sure you want to delete them
    In the future I want to compress this into adminfunction.inc.php
    and make this just the single delete button.

    But as bare bones this should work? need to test with associates.
-->
<?php
    include 'header.php';
?>

<h3>WARNING: IF YOU DELETE THE EMPLOYEE WILL BE GONE FOREVER</h3>

<?php
    require_once 'includes/legacydbh.inc.php'; //includes legacy dbh just in case I need to use it
    require_once 'includes/functions.inc.php'; //includes error checking functions and login  checking as well

    try
    {
        //this is used because the legacy db and the dbh are both $conn and I need another name
        //in case I need legacy
        $username = "debian-sys-maint";
        $password = "vUlvuFil887Af63z";
        $dbname = "testing";
        $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password); 
        
        //if its going to be deleted
        if(isset($_POST["DELETED"]))
        {
            //delete all information from the associate which matches the dropdown
            $arr = $pdo->prepare("DELETE FROM Associate WHERE AssocID = ?");
            $arr->execute(array($_GET['AssocID']));
            $remove = $arr->fetchAll(PDO::FETCH_ASSOC);
        }
        //this is you do not want to delete the information
        if(isset($_POST["BACK"]))
        {
            header('location:admin.php');
        }
    }
    catch(PDOexception $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }

?>

<!-- Buttons to double check you want to delete this person -->
<body>
    <div class="buttons">
        <form method="POST">
            <button class="button" name="DELETED">DELETE ASSOCIATE</button>
            <button class="button" name="BACK"> DO NOT DELETE</button>
        </form>
    </div>
</body>


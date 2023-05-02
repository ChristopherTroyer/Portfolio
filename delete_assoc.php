<!--
    Delete a user, does double check to make sure you want to delete them
    In the future I want to compress this into adminfunction.inc.php
    and make this just the single delete button.

    But as bare bones this should work? need to test with associates.
-->
<?php
    include 'header.php';
?>

<h3>WARNING: IF YOU DELETE THE ASSOCIATE WILL BE GONE FOREVER</h3>

<?php
    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';

    try
    {
        $username = "debian-sys-maint";
        $password = "vUlvuFil887Af63z";
        $dbname = "testing";
        $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password); 

        if(isset($_POST["DELETED"]))
        {
            $arr = $pdo->prepare("DELETE FROM Associate WHERE AssocID = ?");
            $arr->execute(array($_GET['AssocID']));
            $remove = $arr->fetchAll(PDO::FETCH_ASSOC);
        }
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

<body>
    <div class="buttons">
        <form method="POST">
            <button class="button" name="DELETED">DELETE ASSOCIATE</button>
            <button class="button" name="BACK"> DO NOT DELETE</button>
        </form>
    </div>
</body>


<!--
    This allows the admin to view, edit, add, and delete
    associates in the company.
-->

<?php
    include 'header.php';
?>

<body> 
    <p>Select an associate to edit information:</p>
    <form action="update_assoc.php" method="post"> 
        <select name="AssocID"> 
            <option value="default">Choose Associate</option>

<?php
    require_once 'includes/dbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; // utility functions for error checking credentials / login utils

    $AssocArray = fillArray($conn, "SELECT * FROM Associate;"); //get all associates in db
    fillDropDown($AssocArray, "AssocID"); //get dropdown full of associate IDs
?>
        </select>
        <input type="submit" value="Submit">
    </form>


    <p>Select an associate to remove from the system:</p>
    <form action="delete_assoc.php" method="post"> 
        <select name="AssocID"> 
            <option value="default">Choose Associate</option>

<?php
    require_once 'includes/dbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; // utility functions for error checking credentials / login utils

    $AssociatesArray = fillArray($conn, "SELECT * FROM Associate;"); //get all associate in db to array
    fillDropDown($customersArray, "AssocID"); //dropdown to delete an associate
?>
        </select>
        <input type="submit" value="Submit">
    </form>
</body>

<?php
    //redid the dbh with something other than conn because I thought I might need legacy
    $username = "debian-sys-maint";
    $password = "vUlvuFil887Af63z";
    $dbname = "testing";
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password); 

    require_once 'includes/legacydbh.inc.php'; //contains legacy login credentials
    require_once 'includes/functions.inc.php'; //contains functiosn for error checking

    //get all the information by associate and sort by ID number

    $sel = $pdo->prepare('SELECT * FROM Associate ORDER BY AssocID');
    $sel->execute();
    $assoc = $sel->fetchAll(PDO::FETCH_ASSOC);

    //if there is associates count the amount there are and make columns
    if($pdo!=false)
    {
        $countassoc = $pdo->query('SELECT COUNT(*) FROM Associate')->fetchColumn();
    }
?>

<!-- make a table of the associate infomation -->
    <h2>Show Associate</h2>
    <table>
    <thead>
        <tr>
            <td>Associate ID</td>
            <td>Name</td>
            <td>Username</td>
            <td>Password</td>
            <td>Email</td>
            <td>Address</td>
            <td>Commission</td>
            <td>Permission</td>
        </tr>
    </thead>
    <!-- Fill the table with all of the information from associates. All information is visivle from this page -->
    <tbody>
        <?php foreach($assoc as $ainfo): ?>
        <tr>
            <td><?=$ainfo['AssocID']?></td>
            <td><?=$ainfo['First_name']. $ainfo['last_name']?></td>
            <td><?=$ainfo['username']?></td>
            <td><?=$ainfo['password']?></td>
            <td><?=$ainfo['email']?></td>
            <td><?=$ainfo['address']?></td>
            <td><?=$ainfo['commission']?></td>
            <td><?=$ainfo['permission']?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>

    </tbody>

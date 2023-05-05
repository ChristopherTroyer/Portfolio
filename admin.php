<!--
    This allows the admin to view, edit, add, and delete
    associates in the company.
-->

<?php
    include 'header.php';
?>

<?php
    $username = "debian-sys-maint";
    $password = "vUlvuFil887Af63z";
    $dbname = "testing";
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password); 

    require_once 'includes/legacydbh.inc.php';
    require_once 'includes/functions.inc.php';

    $sel = $pdo->prepare('SELECT * FROM Associate ORDER BY AssocID');
    $sel->execute();
    $assoc = $sel->fetchAll(PDO::FETCH_ASSOC);

    if($pdo!=false)
    {
        $countassoc = $pdo->query('SELECT COUNT(*) FROM Associate')->fetchColumn();
    }
?>

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
            <td class="other">
                <a href="update_assoc.php?AssocID=<?=$ainfo['AssocID']?>" class="button">Update</a>
                <a href="delete_assoc.php?AssocID=<?=$ainfo['AssocID']?>" class="button">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>

    </tbody>

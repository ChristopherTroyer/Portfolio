<?php
    include 'header.php';
?>

<?php
    $username = "debian-sys-maint";
    $password = "vUlvuFil887Af63z";
    $dbname = "testing";
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password); 

    require_once 'legacydbh.inc.php';
    require_once 'functions.inc.php';

    if(isset($_POST['QuoteID']))
    {
        $qid=$_GET['QuoteID'];
        $qidtotal =$pdo->prepare('SELECT * FROM New_Quote WHERE QuoteID=?');

        $qidtotal->execute([$_POST['QuoteID']]);

        while($info = $qidtotal->fetch(PDO::FETCH_ASSOC))
        {
            $cust = $info["CustID"];
            $date = $info["process_date"];
            $disamnt = $info["discount_amnt"];
        }

        $qidtotal = $conn->prepare('SELECT * FROM customers WHERE id=?');
        $qidtotal->execute([$cust]);

        while($info = $qidtotal->fetch(PDO::FETCH_ASSOC))
        {
            $cname = $info['name'];
            $contact = $info['contact'];
        }

        $statement = $pdo->prepare('SELECT * FROM Line_Items WHERE QuoteID=?');
        $statement->execute([$_POST['ItemID']]);
        $item = $statement->fetchAll(PDO::FETCHASSOC);
        $qidtotal = $pdo->prepare('SELECT * FROM Quote_Note WHERE QuoteID=?');
        $qidtotal->execute([$_POST['NoteID']]);

        $txt ="";

        while($info = $qidtotal->fetch(PDO::FETCH_ASSOC))
        {
            $txt = $info['note'];
            $ntid = $info['NoteID'];
        }
    }
    else
    {
        exit();
    }
?>
    <h2>Quote Details For: <?=$qid?></h2>

    <h3>Order From: <?=$cname?></h3>
    <h3>Order Date: <?=$date?></h3>
    <h3>Contact Info: <?=$contact?></h3>
    <h3>Notes: <?=$txt?></h3>
        <table>
        <thead>
            <tr>
                <td>Item ID</td>
                <td>Item Info</td>
                <td>Price</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($line as $item): ?>
                <tr>
                    <td><?=$item['ItemID']?></td>
                    <td><?=$item['Free_Desc']?></td>
                    <td><?=$item['Price']?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>

<?php
    $totals = 0.00;
    foreach($key as $item)
    {
        $total+=(float)($item['price']);
    }
    $total=$totals-$disamnt;
?>




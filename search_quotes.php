<?php
    include 'header.php';
?>

<?php
    require_once 'includes/legacydbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php';

    $username = "debian-sys-maint";
    $password = "vUlvuFil887Af63z";
    $dbname = "testing";
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);


    $output = $pdo->prepare('SELECT * FROM Associate, New_Quote WHERE New_Quotes.AssocID = AssocID');
    $output->execute();
    $qid = $output->fetchAll(PDO::FETCH_ASSOC);

    $output = $pdo->prepare('SELECT * FROM New_Quote, Line_Item WHERE QuoteID.ItemID=Line_Items.QuoteID');
    $output->execute();
    $litem = $output ->fetchAll(PDO::FETCG_ASSOC);

    $output = $conn->prepare('SELECT * FROM customers');
    $output->execute();
    $cust = $output->fetchAll(PDO::FETCH_ASSOC);
?>

    <h2>Quotes</h2>
        <table>
        <thead>
        <tr>
            <td>ID</td>
            <td>Associate</td>
            <td>Customer</td>
            <td>total</td>
            <td>status</td>
        </tr>
        </thead>
        <tbody>
            <?php foreach($row as $quote): ?>
                <tr>
                    <td><?=$quote['QuoteID']?></td>
                    <td><?=$quote['First_name']. ' ' .$quote['last_name']?></td>
                    <td><?=$quote['CustID']?></td>
                    <td><?=$quote['price']?></td>
                    <td><?=$quote['process_date']?></td>
                    <td class="buttom">
                        <a href="view_quote.php?QuoteID=<?=$quote['QuoteID']?>" class="button"></a>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
        </table>

<?php
    require_once 'includes/legacydbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; //func for error checking

    //this is so I can use another name aside from $conn because thats the same name in legacydbh
    $username = "debian-sys-maint";
    $password = "vUlvuFil887Af63z";
    $dbname = "testing";
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);

    if(isset($_POST['AssocID']))
    {
    //ID from the dropdown
    $id = $_GET['AssocID'];

    // Select data associated with id
    $result = mysqli_query($conn, "SELECT * FROM New_Quote WHERE AssocID = $id");

    $output = $pdo->prepare('SELECT * FROM New_Quote, Line_Item WHERE QuoteID.ItemID=Line_Items.QuoteID');
    $output->execute();
    $litem = $output ->fetchAll(PDO::FETCG_ASSOC);

    $output = $conn->prepare('SELECT * FROM customers');
    $output->execute();
    $cust = $output->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
        exit();
    }
?>

    <!-- CREATES A TABLE FULL OF THE QUOTES. THIS IS ALSO USED IN THE DIFFERENT SEARCHES -->
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
                    <!-- FILL ING THE INFOMATION ON THE TABLE -->
                    <td><?=$quote['QuoteID']?></td>
                    <td><?=$quote['AssocID']?></td>
                    <td><?=$quote['CustID']?></td>
                    <td><?=$quote['price']?></td>
                    <td><?=$quote['process_date']?></td>
                    <td class="buttom">
                        <a href="view_quote.php?QuoteID=<?=$quote['QuoteID']?>" class="button"></a> <!-- MAKES EACH BUTTON ABLE TO BE VIEWED -->
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
        </table>

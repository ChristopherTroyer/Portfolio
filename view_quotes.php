<?php
    include 'header.php';
?>

<?php
    //same reason as before this is essentially what is in dbh.inc.php however it has the same name as
    //$conn in legacy so I made a sep name in case I needed it
    $username = "debian-sys-maint";
    $password = "vUlvuFil887Af63z";
    $dbname = "testing";
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password); 

    require_once 'includes/legacydbh.inc.php'; //holds legacy dbh
    require_once 'includes/functions.inc.php'; //credentials and error checking

    //if a quoteID is chosen from the search list then gather all of the information about the specific
    //quote to be shown
    if(isset($_POST['QuoteID']))
    {
        //get all the information from quote
        $qid=$_GET['QuoteID'];
        $qidtotal =$pdo->prepare('SELECT * FROM New_Quote WHERE QuoteID=?');
        $qidtotal->execute([$_POST['QuoteID']]);
        
        //pull information about the cust ID, process date, discount
        while($info = $qidtotal->fetch(PDO::FETCH_ASSOC))
        {
            $cust = $info["CustID"];
            $date = $info["process_date"];
            $disamnt = $info["discount_amnt"];
        }

        $qidtotal = $conn->prepare('SELECT * FROM customers WHERE id=?');
        $qidtotal->execute([$cust]);

        //get information from the customer side about the cust name and contact
        //so it can be showen in the table of information
        while($info = $qidtotal->fetch(PDO::FETCH_ASSOC))
        {
            $cname = $info['name'];
            $contact = $info['contact'];
        }

        //get all the information from Line_Items to see what is with each quote
        $statement = $pdo->prepare('SELECT * FROM Line_Items WHERE QuoteID=?');
        $statement->execute([$_POST['ItemID']]);
        $item = $statement->fetchAll(PDO::FETCHASSOC);
        
        //get all information from notes of the quote to be seen in the view
        $qidtotal = $pdo->prepare('SELECT * FROM Quote_Note WHERE QuoteID=?');
        $qidtotal->execute([$_POST['NoteID']]);

        //blank text from notes so it can be filled in later
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
    <!-- SHOWS QUOTE DETAILS BASED ON INFORMATION GAINED FROM THE SEARCH-->
    <h2>Quote Details For: <?=$qid?></h2>

    <h3>Order From: <?=$cname?></h3> <!-- cust name -->
    <h3>Order Date: <?=$date?></h3> <!-- process date -->
    <h3>Contact Info: <?=$contact?></h3> <!-- contact information -->
    <h3>Notes: <?=$txt?></h3>
        <table>
        <thead>
            <tr>
                <!-- item information -->
                <td>Item ID</td>
                <td>Item Info</td>
                <td>Price</td>
            </tr>
        </thead>
        <tbody>
            <!-- filler data for the table about the item info -->
            <?php foreach($line as $item): ?>
                <tr>
                    <td><?=$item['ItemID']?></td>
                    <td><?=$item['Free_Desc']?></td>
                    <td><?=$item['Price']?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
<!-- find out the price if there is a discount -->
<?php
    $totals = 0.00;
    foreach($key as $item)
    {
        $total+=(float)($item['price']);
    }
    $total=$totals-$disamnt;
?>

<?php
    include 'footer.php';
?>

<!-- 
    This brings up a list of all quotes, along with a dropdown filled with different things to search by
    depending on what they choose they will be able to search these things.
-->
<?php
    include 'header.php';
?>

<!-- THE ABILITY TO SEARCH BY ASSOCIATE ID -->
<body> 
    <p>Select an Associate Id to Search:</p>
    <form action="search_by_assoc.php" method="get"> 
        <select name="AssocID"> 
            <option value="default">Choose Associate ID</option>

<?php
    require_once 'includes/dbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; // utility functions for error checking credential

    $AssocArray = fillArray($conn, "SELECT * FROM New_Quote;"); //get all associates in db
    fillDropDown($AssocArray, "AssocID"); //get dropdown full of associate IDs
?>
        </select>
        <input type="submit" value="Submit">
    </form>
    
<!-- THE ABILITY TO SEARCH BY CUSTOMER ID -->
    <body> 
    <p>Select a Customer Id to Search:</p>
    <form action="search_by_cust.php" method="get"> 
        <select name="CustID"> 
            <option value="default">Choose Customer Id</option>

<?php
    require_once 'includes/dbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; // utility functions for error checking credential

    $AssocArray = fillArray($conn, "SELECT * FROM New_Quote;"); //get all associates in db
    fillDropDown($AssocArray, "CustID"); //get dropdown full of associate IDs
?>
        </select>
        <input type="submit" value="Submit">
    </form>
       
<!-- THE BILITY TO SEARCH BY STATUS OF THE QUOTES -->
    <p>Select Status to Search:</p>
    <form action="search_by_status.php" method="get"> 
        <select name="status"> 
            <option value="default">Choose Status</option>

<?php
    require_once 'includes/dbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; // utility functions for error checking credential

    $AssocArray = fillArray($conn, "SELECT * FROM New_Quote;"); //get all associates in db
    fillDropDown($AssocArray, "status"); //get dropdown full of associate IDs
?>
        </select>
        <input type="submit" value="Submit">
    </form>
        </body>

<?php
    require_once 'includes/legacydbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; //func for error checking

    //this is so I can use another name aside from $conn because thats the same name in legacydbh
    $username = "debian-sys-maint";
    $password = "vUlvuFil887Af63z";
    $dbname = "testing";
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);

    //gathers all information about each quote
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

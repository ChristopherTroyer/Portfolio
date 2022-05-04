<html>
    <head>
        <title>Group 22's Site</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Group 22's Site</h1>
    <h2>Storefront</h2>
    <nav>
        <ul>
            <li><a href="storefront.php">Storefront</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="checkout.php">Checkout</a></li>
            <li><a href="inventory.php">Inventory</a></li>
            <li><a href="orders.php">Orders</a></li>
        </ul>
    </nav>

    <hr>
    <?php

    //run a query
    function run_query($QRY, $pdo)
    {
        $rs = $pdo->query($QRY);       //run query
        $a = $rs->fetchAll(PDO::FETCH_ASSOC);      //set $a to query
        return $a;
    }

    include 'password.php';
    try { // connect to the database, forms don't do much good if they can't connect
        $pdo = new PDO($dbname, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
        
        $orders = run_query("SELECT * FROM ORDR;", $pdo);
        $customers = run_query("SELECT * FROM CUSTOMER;", $pdo);
        $products = run_query("SELECT * FROM PRODUCT;", $pdo);

        echo "<p style=\"font-size:40\"> Orders </p>\n"; //label

        //begin order table
        echo "<table border=1 cellspacing=1 style=\"font-size:32;margin-left:auto;margin-right:auto;\">";
        echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>User ID</th>";
        echo "<th>User</th>";
        echo "<th>Address</th>";
        echo "<th>Status</th>";
        for ($x = 0; $x < sizeof($orders); $x++)
        {
            echo "<tr>";
            echo "<th>" . $orders[$x]["OID"] . "</th>";
            echo "<th>" . $orders[$x]["USERID"] ."</th>";
            echo "<th>" . $customers[$orders[$x]["USERID"]-1]["NAME"] . "</th>";    //print name -1 to get correct id since id starts with 1 and not 0
            echo "<th>" . $customers[$orders[$x]["USERID"]-1]["ADDR"] . "</th>";    //print address 
            echo "<th>" . $orders[$x]["STATUS"] . "</th>";
            echo "</tr>";
        }
        echo "</tr>";
    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>
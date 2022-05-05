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
            <li><a href="mandir.php">Manager Page</a></li>
            <li><a href="inventory.php">Inventory</a></li>
            <li><a href="orders.php">Orders</a></li>
        </ul>
    </nav>

    <form action=""></form>
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

        $order = run_query("SELECT * FROM ORDR WHERE OID=" . $_POST["OID"] . ";", $pdo);
        $customer = run_query("SELECT * FROM CUSTOMER WHERE USERID=\"" . $order[0]["USERID"] . "\";", $pdo);

        $pdo->query("UPDATE ORDR SET STATUS=\"" . $_POST["status"] . "\" WHERE OID=" . $order[0]["OID"] . ";");

        echo "<h2> Order for " . $customer[0]["NAME"] ." updated.</h2>\n"; //label

        //begin order table
        echo "<table border=1 cellspacing=1 class=\"orderTable\">";
        echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>User ID</th>";
        echo "<th>User</th>";
        echo "<th>Address</th>";
        echo "<th>Status</th>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>" . $order[0]["OID"] . "</th>";
        echo "<th>" . $order[0]["USERID"] ."</th>";
        echo "<th>" . $customer[0]["NAME"] . "</th>";    //print name -1 to get correct id since id starts with 1 and not 0
        echo "<th>" . $customer[0]["ADDR"] . "</th>";    //print address
        echo "<th>" . $_POST["status"] . "</th>";
        echo "</tr>";
        
    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>
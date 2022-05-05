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
            <li><a href="wish.php">WishList</a></li>
            <li><a href="checkout.php">Checkout</a></li>
            <li><a href="custOrders.php">Orders</a></li>
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

        // get userid from SESS tables
        $res = $pdo->query("SELECT USERID FROM SESS");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
          $userId = $fet["USERID"];
        }

        $orders = run_query("SELECT * FROM ORDR WHERE USERID=\"" . $userId ."\";", $pdo);
        $customers = run_query("SELECT * FROM CUSTOMER WHERE USERID=\"" . $userId ."\";", $pdo);

        echo "<h2> Orders </h2>\n"; //label

        //begin order table
        echo "<table border=1 cellspacing=1 class=\"orderTable\">";
        echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>User ID</th>";
        echo "<th>User</th>";
        echo "<th>Address</th>";
        echo "<th>Status</th>";
        echo "</tr>";
        for ($x = 0; $x < sizeof($orders); $x++)
        {
            echo "<tr>";
            echo "<th>" . $orders[$x]["OID"] . "</th>";
            echo "<th>" . $orders[$x]["USERID"] ."</th>";
            echo "<th>" . $customers[0]["NAME"] . "</th>";    //print name -1 to get correct id since id starts with 1 and not 0
            echo "<th>" . $customers[0]["ADDR"] . "</th>";    //print address
            echo "<th>" . $orders[$x]["STATUS"] . "</th>";
            echo "</tr>";
        }
        echo "</table>";
    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>
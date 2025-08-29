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
            <li><a href="cusdir.php">Logout</a></li>
            <li><a href="storefront.php">Storefront</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="wish.php">WishList</a></li>
            <li><a href="checkout.php">Checkout</a></li>
            <li><a href="custOrders.php">Orders</a></li>
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

        $products = run_query("SELECT * FROM PRODUCT;", $pdo);
        $cart = run_query("SELECT * FROM CART WHERE OID=" . $_POST["OID"] .";", $pdo);

        echo "<h2 style=\"text-decoration: underline;\">OrderId: " . $_POST["OID"] ."</h2>";
        //begin order table
        echo '<table style="border:1px solid black;margin-left:auto;margin-right:auto;">';
        echo "<tr>";
        echo "<th class=\"storefrontHeader\">Image</th>";
        echo "<th class=\"storefrontHeader\">Price</th>";
        echo "<th class=\"storefrontHeader\">Product</th>";
        echo "<th class=\"storefrontHeader\">QTY</th>";
        echo "</tr>";
        $sum = 0;
        for ($x = 0; $x < sizeof($cart); $x++)
        {
            echo "<tr>";
            echo '<th>' . "<div class=\"storefrontDiv\"> <img class=\"storefrontImg\"src=\"" . $products[$cart[$x]["PID"]-1]["IMG"] . "\"> </div>" . "</th>";
            echo "<th class=\"storefrontText\">$" . $products[$cart[$x]["PID"]-1]["PRICE"] . "</th>";
            $sum += $products[$cart[$x]["PID"]-1]["PRICE"] * $cart[$x]["NUM"];
            echo "<th class=\"storefrontText\">" . $products[$cart[$x]["PID"]-1]["NAME"] . "</th>";
            echo "<th class=\"storefrontText\">" . $cart[$x]["NUM"] . "</th>";
            echo "</tr>";
        }
        echo "</table>";
        $sum = $sum*1.06;
        echo "<h2>Total: $" . number_format($sum, 2) . "</h2>";
    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>

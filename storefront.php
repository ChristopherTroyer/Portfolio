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
    <h2>Products</h2>
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

        //begin order table
        echo '<table style="border:1px solid black;margin-left:auto;margin-right:auto;">';
        echo "<tr>";
        echo "<th style=\"text-decoration: underline;font-size:50px;\">Image</th>";
        echo "<th style=\"text-decoration: underline;font-size:50px;\">Product</th>";
        echo "<th style=\"text-decoration: underline;font-size:50px;\">Price</th>";
        echo "<th style=\"text-decoration: underline;font-size:50px;\">Rating</th>";
        for ($x = 0; $x < sizeof($products); $x++)
        {
            echo "<tr>";
            //echo '<th>' . "<div id=\"main\" style=\"width:200px; height:200px; background-position:center; background-repeat:no-repeat; background-image:url('" . $products[$x]["IMG"] . "')\"></div>" . "</th>";
            echo '<th>' . "<div style=\"height:200px; width: 200px;\"> <img style=\"max-width:100%; max-height:100%;\"src=\"" . $products[$x]["IMG"] . "\"> </div>" . "</th>";
            echo "<th style=\"font-size:40px;\">" . $products[$x]["NAME"] . "</th>";
            echo "<th style=\"font-size:40px;\">$" . $products[$x]["PRICE"] . "</th>";
            echo "<th style=\"font-size:40px;\">" . $products[$x]["RATING"] . "</th>";
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
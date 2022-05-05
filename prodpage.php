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

        $products = run_query("SELECT * FROM PRODUCT WHERE NAME=\"" . $_POST["name"] . "\";", $pdo);

        //begin product information

        echo "<div style=\"padding: 20px;\">
                <div style=\"width: 48%; float: left; padding: 20px;\">
                    <div style=\"height:600px; width: 600px; float: right;\"><img style=\"width:100%; height:100%;\"src=\"" . $products[0]["IMG"] . "\"></div>
                </div>
                <div style=\" float: left; padding: 20px;\">
                    <div>
                        <h3 style=\"font-size: 40px; float: left;\">" . $products[0]["NAME"] ."</h3><br class=\"prodBRClear\" />
                        <p class=\"prodP30px\">Rating: " . $products[0]["RATING"] . "</p><br class=\"prodBRClear\" />
                        <p class=\"prodP30px\">Price: $" . $products[0]["PRICE"] . "</p><br class=\"prodBRClear\" />";   
        if ($products[0]["QTY"] > 0)        //if qty is greater than 0 print in stock
        {
            echo "<p class=\"prodStockTextG\">In Stock</p><br class=\"prodBRClear\" />";
        }
        else        //else out of stock
        {
            echo "<p class=\"prodStockTextR\">Out of Stock</p><br class=\"prodBRClear\" />";
        }     
        echo                "<p class=\"prodFloat\">About this Item:</p><br class=\"prodBRClear\" />
                        <ul>
                            <li class=\"prodFloat\">" . $products[0]["DESCRIPT"] ."</li><br class=\"prodBRClear\" />
                            <li class=\"prodFloat\">weight: " . $products[0]["WEIGHT"] . " lbs</li>
                        </ul>
                    </div>
                </div>
            </div>";
    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>

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
            <li><a href="inventory.php">Inventory</a></li>
            <li><a href="orders.php">Orders</a></li>
        </ul>
    </nav>

    <hr>
    <!-- <div style="padding: 20px;">
        <div style="width: 48%; float: left; padding: 20px;">
            <div style="height:600px; width: 600px; float: right;"><img style="max-width:100%; max-height:100%;"src="img/4pple_earpods.jpg"></div>
        </div>
        <div style="width: 48%; float: left; padding: 20px;">
            <div>
                <h3 style="font-size: 40px; float: left;">Apple Earpods</h3><br style="clear:both" />
                <p style="font-size: 30px; float: left;">Rating: 4.3 Stars</p><br style="clear:both" />
                <p style="font-size: 30px; float: left;">Price: $15</p><br style="clear:both" />
                <p style="font-size: 30px; float: left;">In Stock</p><br style="clear:both" />
                <p style="float: left;">About this Item:</p><br style="clear:both" />
                <ul>
                    <li style="float: left">description</li><br style="clear:both" />
                    <li style="float: left">weight: 20</li>
                </ul>
            </div>
        </div>
    </div> -->
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

        //print_r($products);

        //begin product information

        echo "<div style=\"padding: 20px;\">
                <div style=\"width: 48%; float: left; padding: 20px;\">
                    <div style=\"height:600px; width: 600px; float: right;\"><img style=\"width:100%; height:100%;\"src=\"" . $products[0]["IMG"] . "\"></div>
                </div>
                <div style=\"width: 48%; float: left; padding: 20px;\">
                    <div>
                        <h3 style=\"font-size: 40px; float: left;\">" . $products[0]["NAME"] ."</h3><br style=\"clear:both\" />
                        <p style=\"font-size: 30px; float: left;\">Rating: " . $products[0]["RATING"] . "</p><br style=\"clear:both\" />
                        <p style=\"font-size: 30px; float: left;\">Price: $" . $products[0]["PRICE"] . "</p><br style=\"clear:both\" />";   
        if ($products[0]["QTY"] > 0)        //if qty is greater than 0 print in stock
        {
            echo "<p style=\"font-size: 30px; color: green; float: left;\">In Stock</p><br style=\"clear:both\" />";
        }
        else        //else out of stock
        {
            echo "<p style=\"font-size: 30px; color: red; float: left;\">Out of Stock</p><br style=\"clear:both\" />";
        }     
        echo                "<p style=\"float: left;\">About this Item:</p><br style=\"clear:both\" />
                        <ul>
                            <li style=\"float: left\">" . $products[0]["DESCRIPT"] ."</li><br style=\"clear:both\" />
                            <li style=\"float: left\">weight: " . $products[0]["WEIGHT"] . " lbs</li>
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

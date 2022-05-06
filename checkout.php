<html>
    <head>
        <title>Group 22's Site</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Group 22's Site</h1>
    <h2>Checkout</h2>
    <nav>
        <ul>
            <li><a href="login.php">Home</a></li>
            <li><a href="storefront.php">Storefront</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="wish.php">WishList</a></li>
            <li><a href="checkout.php">Checkout</a></li>
        </ul>
    </nav>
    <hr>

    <?php
    include 'password.php';
    try {
        $pdo = new PDO($dbname, $user, $pass);
        // get userid from SESS tables
        $res = $pdo->query("SELECT USERID FROM SESS");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
          $userId = $fet["USERID"];
        }
        $cartNum = 0;
        $subtot = 0;

        if($_GET != NULL){
          echo "<h4> - Order is submitted.</h4>";
          echo "<h4> - A new order was added to the Order table.</h4>";
        }

        $res = $pdo->query("SELECT NAME FROM CUSTOMER WHERE USERID=$userId");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
              $name = $fet["NAME"];
        }
        echo "<h3>For user $name</h3>";

        $res = $pdo->query("SELECT NAME, PRODUCT.PID, PRICE, NUM FROM PRODUCT, CART, ORDR
          WHERE PRODUCT.PID = CART.PID AND CART.OID = ORDR.OID AND ORDR.USERID=$userId");
        echo "<h3>Items in Cart.</h3>";
        echo "<table border=0 cellpadding=5 align=center>";
        echo "<tr><th>Item</th><th>Quantity</th></tr>";
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
            echo"<tr>";
            $name = $fet["NAME"];
            $qty = $fet["NUM"];
            $price = $fet["PRICE"];
            $subtot += $qty * $price;
            echo "
            <td>
                $name
            </td>
            <td style='text-align:center'>
                $qty
            </td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<br>";
        $res = $pdo->query("SELECT ADDR FROM CUSTOMER WHERE USERID=$userId");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
              $address = $fet["ADDR"];
        }
        echo "<h3>Shipping Address.</h3>";
        echo $address;
        echo "<h3>Payment Method.</h3>";
        echo "Using DADCOVER Credit Card.";
        echo "<br>";

        echo "<h3>Order Summary.</h3>";
        echo "Subtotal: $ "; echo $subtot; echo "<br>";
        echo "Shipping: Always Free."; echo "<br>";
        echo "Tax: $ "; echo $subtot*0.06; echo "<br>";
        echo "Total: $ "; echo $subtot*1.06;
        echo "<br><br>";

        echo "<form action=\"checkout.php\" method = GET>
        <input type='submit' value='Place Order'>
        </form>";

    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }

    ?>
</body>
</html>

<html>
    <head>
        <title>Group 22's Site</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Group 22's Site</h1>
    <h2>Cart</h2>
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
    include 'password.php';
    try {
        $pdo = new PDO($dbname, $user, $pass);

        if($_POST != NULL){
            $res = $pdo->prepare("UPDATE CART SET NUM = ? WHERE NAME=?");
            $res->execute(array(($_POST["quantity"]), ($_POST["name"])));
            echo "Quantity updated successfully.";
        }

        echo "<h3>User: (shows username)</h3>";
        $res = $pdo->query("SELECT NAME, NUM FROM PRODUCT, CART
          WHERE PRODUCT.PID = CART.PID AND CART.OID = 1");
        echo "<h3>Items in Cart.</h3>";
        echo "<table border=0 cellpadding=5 align=center>";
        echo "<tr><th>Item</th><th>Quantity</th></tr>";
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
            echo"<tr>";
            $name = $fet["NAME"];
            $qty = $fet["NUM"];
            echo "
            <td>
                $name
            </td>
            <td>
                $qty
            </td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<br><br>";
        echo "<form action=\"cart.php\" method = POST>";
        echo "<label for='Name'>Choose Item: </label>";
        echo "<select id='Name' name='Item'>";
        $res = $pdo->query("SELECT NAME, NUM FROM PRODUCT, CART
          WHERE PRODUCT.PID = CART.PID AND CART.OID = 1");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
              $name = $fet["NAME"];
              echo "<option value=".$name.">".$name."</option>";
        }
        echo "</select>";
        echo "
        New Qty: <input type=\"text\" size='1' name=\"qty\" />
        <input type='submit' value='Update'>
        <input type='submit' value='Remove Item'>
        <input type='submit' value='Move to WishList'> </form>";

        echo "<br>";
        $res = $pdo->query("SELECT NAME, NUM FROM PRODUCT, CART
          WHERE PRODUCT.PID = CART.PID AND CART.OID = 1");
        echo "<h3>Items in WishList.</h3>";
        echo "<table border=0 cellpadding=5 align=center>";
        echo "<tr><th>Item</th><th>Quantity</th></tr>";
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
            echo"<tr>";
            $name = $fet["NAME"];
            $qty = $fet["NUM"];
            echo "
            <td>
                $name
            </td>
            <td>
                $qty
            </td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "</pre></body></html>";
    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }

    ?>
</body>
</html>

<html>
    <head>
        <title>Group 22's Site</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Group 22's Site</h1>
    <h2>WishList</h2>
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
    <?php
    include 'password.php';
    try {
        $pdo = new PDO($dbname, $user, $pass);

        if($_GET != NULL){
            $res = $pdo->prepare("UPDATE WISH SET NUM=? WHERE PID=?");
            $res->execute(array(($_GET["qty"]), ($_GET["pid"])));
        }

        $res = $pdo->query("SELECT NAME FROM CUSTOMER WHERE USERID=2");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
              $name = $fet["NAME"];
        }
        echo "<h3>For user $name</h3>";

        $res = $pdo->query("SELECT NAME, NUM FROM PRODUCT, WISH
          WHERE PRODUCT.PID = WISH.PID AND WISH.USERID = 2");
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
            <td style='text-align:center'>
                $qty
            </td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<br><br>";
        echo "<form action=\"wish.php\" method = GET>";
        echo "<label for='Name'>Choose Item: </label>";
        echo "<select id='Name' name='pid'>";
        $res = $pdo->query("SELECT NAME, PRODUCT.PID, NUM FROM PRODUCT, WISH
          WHERE PRODUCT.PID = WISH.PID AND WISH.USERID = 2");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
              $name = $fet["NAME"];
              $pid = $fet["PID"];
              echo "<option value=".$pid.">".$name."</option>";
        }
        echo "</select>";
        echo "
        New Qty: <input type=\"text\" size='1' name=\"qty\" />
        <input type='submit' value='Update Qty'>
        <input type='submit' value='Delete'>
        <input type='submit' value='Move to Cart'> </form>";

        echo "<br>";
        $res = $pdo->query("SELECT NAME, PRODUCT.PID, NUM FROM PRODUCT, CART, ORDR
          WHERE PRODUCT.PID = CART.PID AND CART.OID = ORDR.OID AND ORDR.USERID=2");
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
            <td style='text-align:center'>
                $qty
            </td>";
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

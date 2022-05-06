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
    try {
        $pdo = new PDO($dbname, $user, $pass);

        // get userid from SESS tables
        $res = $pdo->query("SELECT USERID FROM SESS");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
          $userId = $fet["USERID"];
        }
        $cartNum = 0;

        if($userId == null) //redirect to login if not logged in
        {
            header('Location: login.php');
        }

        if(isset($_POST["newProd"]))    //if add to cart button pressed
        {
          //status = SHOPPING, USERID = userId

          $currOrder = run_query("SELECT * FROM ORDR WHERE USERID=\"" . $userId ."\" AND STATUS=\"" . "SHOPPING" ."\";", $pdo);
          $selOrder = run_query("SELECT * FROM CART WHERE OID=" . $currOrder[0]["OID"] ." AND PID=" . $_POST["newProd"] . ";", $pdo); //to check if already in cart

          if($selOrder == "")
          {
            $pdo->query("INSERT INTO CART VALUES ('" . $currOrder[0]["OID"] ."', '" . $_POST["newProd"] . "', '" . 1 ."')" . ";");
          }
          else
          {
            $buffNum = $selOrder[0]["NUM"] + 1;
            $pdo->query("UPDATE CART SET NUM=" . $buffNum . " WHERE OID=" . $currOrder[0]["OID"] ." AND PID=" . $selOrder[0]["PID"] . ";");
          }
        }

        if($_GET != NULL){
          switch ($_GET["subtyp"]){
            case '1':
              $res = $pdo->prepare("UPDATE CART SET NUM=? WHERE PID=?");
              $res->execute(array(($_GET["qty"]), ($_GET["pid"])));
              break;
            case '2':
              $res = $pdo->prepare("DELETE FROM CART WHERE PID=?");
              $res->execute(array($_GET["pid"]));
              break;
            case '3':
              // Get product quantity in cart
              $cartPid = $_GET["pid"];
              $res = $pdo->query("SELECT NUM FROM CART WHERE PID=$cartPid");
              while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
                    $cartNum = $fet["NUM"];
              }
              // Move item to Wishlist
              $res = $pdo->prepare("DELETE FROM CART WHERE PID=?");
              $res->execute(array($_GET["pid"]));
              $res = $pdo->prepare("INSERT INTO WISH VALUES($userId,?,?)");
              $res->execute(array(($_GET["pid"]),$cartNum));
              break;
            default:
              echo "Default";
              break;
          }
        }

        $res = $pdo->query("SELECT NAME FROM CUSTOMER WHERE USERID=$userId");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
              $name = $fet["NAME"];
        }
        echo "<h3>For user $name</h3>";

        $res = $pdo->query("SELECT NAME, PRODUCT.PID, NUM FROM PRODUCT, CART, ORDR
          WHERE PRODUCT.PID = CART.PID AND CART.OID = ORDR.OID AND ORDR.USERID=$userId");
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
        echo "<form action=\"cart.php\" method = GET>";
        echo "<label for='Name'>Choose Item: </label>";
        echo "<select id='Name' name='pid'>";
        $res = $pdo->query("SELECT NAME, PRODUCT.PID, NUM FROM PRODUCT, CART, ORDR
          WHERE PRODUCT.PID = CART.PID AND CART.OID = ORDR.OID AND ORDR.USERID=$userId");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
              $name = $fet["NAME"];
              $pid = $fet["PID"];
              echo "<option value=".$pid.">".$name."</option>";
        }
        echo "</select>";
        echo "
        New Qty: <input type=\"text\" size='1' name=\"qty\" />
        <input type='radio' name='subtyp' value='1' /> Update Qty
        <input type='radio' name='subtyp' value='2' /> Delete Item
        <input type='radio' name='subtyp' value='3' /> Move Item to Wishlist
        <input type='submit' value='Submit'>
        </form>";

        echo "<br>";
        $res = $pdo->query("SELECT NAME, NUM FROM PRODUCT, WISH
          WHERE PRODUCT.PID = WISH.PID AND WISH.USERID = $userId");
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

    }
    catch(PDOexception $e) {
        echo "Connection to database failed: " . $e->getMessage();
    }

    ?>
</body>
</html>

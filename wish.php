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
        $wishNum = 0;

        if($userId == null) //redirect to login if not logged in
        {
            header('Location: login.php');
        }

        if(isset($_POST["newProd"]))    //if add to wishlist button pressed
        {
          //status = SHOPPING, USERID = userId
          $pdo->query("INSERT INTO WISH VALUES ('" . $userId ."', '" . $_POST["newProd"] . "', '" . 1 ."')" . ";");
        }

        if($_GET != NULL){
          switch ($_GET["subtyp"]){
            case '1':
              $res = $pdo->prepare("UPDATE WISH SET NUM=? WHERE PID=?");
              $res->execute(array(($_GET["qty"]), ($_GET["pid"])));
              break;
            case '2':
              $res = $pdo->prepare("DELETE FROM WISH WHERE PID=?");
              $res->execute(array($_GET["pid"]));
              break;
            case '3':
              // Get product quantity in wishlist
              $wishPid = $_GET["pid"];
              $res = $pdo->query("SELECT NUM FROM WISH WHERE PID=$wishPid");
              while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
                    $wishNum = $fet["NUM"];
              }
              // Get oder Id
              $res = $pdo->query("SELECT OID FROM ORDR WHERE USERID=$userId AND STATUS='SHOPPING'");
              while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
                    $Oid = $fet["OID"];
              }
              // Move item to CART
              $res = $pdo->prepare("DELETE FROM WISH WHERE PID=?");
              $res->execute(array($_GET["pid"]));
              echo $userId; echo $_GET["pid"]; echo $wishNum;
              $res = $pdo->prepare("INSERT INTO CART VALUES(?,?,?)");
              $res->execute(array($Oid, $_GET["pid"], $wishNum));
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

        echo "<br><br>";
        echo "<form action=\"wish.php\" method = GET>";
        echo "<label for='Name'>Choose Item: </label>";
        echo "<select id='Name' name='pid'>";
        $res = $pdo->query("SELECT NAME, PRODUCT.PID, NUM FROM PRODUCT, WISH
          WHERE PRODUCT.PID = WISH.PID AND WISH.USERID = $userId");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
              $name = $fet["NAME"];
              $pid = $fet["PID"];
              echo "<option value=".$pid.">".$name."</option>";
        }
        echo "</select>";
        echo "
        New Qty: <input type=\"text\" size='1' name=\"qty\" />
        <input type='radio' name='subtyp' value='1' /> Update Qty
        <input type='radio' name='subtyp' value='2' /> Remove Item
        <input type='radio' name='subtyp' value='3' /> Move Item to Cart
        <input type='submit' value='Submit'></form>";

        echo "<br>";
        $res = $pdo->query("SELECT NAME, PRODUCT.PID, NUM FROM PRODUCT, CART, ORDR
          WHERE PRODUCT.PID = CART.PID AND CART.OID = ORDR.OID AND ORDR.USERID =$userId ");
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

    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }

    ?>
</body>
</html>

<html>
    <head>
        <title>Group 22's Site</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Group 22's Site</h1>
    <h2>Customer Page</h2>
    <nav>
        <ul>
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

        if($_GET != NULL){
          if ($_GET["sub"] == 'Logout'){
            // get userid from SESS tables
            $res = $pdo->query("SELECT USERID FROM SESS");
            while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
                  $userId = $fet["USERID"];
            }
            $res = $pdo->prepare("DELETE FROM SESS WHERE USERID=?");
            $res->execute(array($userId));
            header('location: login.php');
          }
        }

        // get userid from SESS tables
        $res = $pdo->query("SELECT USERID FROM SESS");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
              $userId = $fet["USERID"];
        }
        // get name of userid from CUSTOMER table
        $res = $pdo->query("SELECT NAME FROM CUSTOMER WHERE USERID=$userId");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)) {
              $name = $fet["NAME"];
        }
        echo "<br>";
        echo "<h4> - Order is submitted. Order status was changed to PENDING</h4>";
        echo "<h4> - A new order with status SHOPPING was added to the Order table.</h4>";
        echo "<br>";
        echo "<form action=\"mandir.php\" method = GET>
        <input type='submit' name='sub' value='Logout'>
        </form>";

    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>

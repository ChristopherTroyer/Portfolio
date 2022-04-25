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
        </ul>
    </nav>

    <hr>
    <?php
    include 'password.php';
    try { // connect to the database, forms don't do much good if they can't connect
        $pdo = new PDO($dbname, $user, $pass);        
        $res = $pdo->prepare("SELECT * FROM PRODUCT WHERE NAME=?;");
        $res->execute(array(($_POST["name"])));
        if($fet = $res->fetch(PDO::FETCH_ASSOC)){
            $name = $fet["NAME"];
            echo "<h2>$name</h2>";
            echo $fet["DESCRIPT"];
            echo "</br>Current stock: $fet[QTY]";
            echo "<form action=\"inventory.php\" method = POST>
                    <input type=\"number\" value=0 name=\"quantity\"/></br>
                    <input type=\"hidden\" name=\"name\" value=\"$name\"/>
                    <input type=\"submit\" value=\"Add more stock\"/>
                </form>";
        }
        else{

        }
    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    
    ?>
</body>
</html>
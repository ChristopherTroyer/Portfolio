<html>
    <head>
        <title>Group 22's Site</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Group 22's Site</h1>
    <h2>Inventory</h2>
    <nav>
        <ul>
            <li><a href="storefront.php">Storefront</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="checkout.php">Checkout</a></li>
            <li><a href="inventory.php">Inventory</a></li>
        </ul>
    </nav>

    <hr>
    <h2>Products</h2>
    <?php
    include 'password.php';
    try { // connect to the database, forms don't do much good if they can't connect
        $pdo = new PDO($dbname, $user, $pass);        
        $res = $pdo->query("SELECT NAME, QTY FROM PRODUCT");
        echo "<table border=1>";
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
            echo"<tr>";
            foreach($fet as $data){
                echo "<td>$data</td>";
            }
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
<html>
    <head>
        <title>Group 22's Site</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Group 22's Site</h1>
    <h2>Login</h2>
    <hr>
    <?php
    include 'password.php';
    try {
        $pdo = new PDO($dbname, $user, $pass);

        echo "
        Try 1 & boley for store manager. 3 & agath123 for customer.
        ";

        echo "
        <form action=\"login.php\" method = GET>
        <label for='usrname'>Username</label>
        <input type='text' size='5' id='usrname' name='usrname' required/>
        <label for='psw'>Password</label>
        <input type='password' id='psw' name='psw' required />
        <input type='submit' value='Login'>
        </form>";

        if($_GET != NULL){
          echo $_GET['usrname']; echo $_GET['psw'];
          // Search database for username and Password and usertype

          if ($_GET["usrname"] == '1' && $_GET["psw"] == 'boley') {
            $userId = $_GET["usrname"];
            $Sid = "MANAGER"; // if usertype =1
            $res = $pdo->prepare("INSERT INTO SESS VALUES(?,?)");
            $res->execute(array($userId,$Sid));
            header('location: mandir.php');
          }
          else if ($_GET["usrname"] == '3' && $_GET["psw"] == 'agath123') {
            $userId = $_GET["usrname"];
            $Sid = "CUSTOMER"; // if usertype =2
            $res = $pdo->prepare("INSERT INTO SESS VALUES(?,?)");
            $res->execute(array($userId,$Sid));
            header('location: cusdir.php');
          } else echo "Invalid username and password. Try again!";
        }

    }
    catch(PDOexception $e) {
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>

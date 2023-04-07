<?php
 include_once 'header.php'
?>

<section class="login-form">
    <h2>Log In</h2>
    <form action="includes/login.inc.php" method="post">
        <input type="text" name="username" placeholder="Username..">
        <input type="password" name="pass" placeholder="Password..">
        <button type="submit" name="submit">Log In</button>
    </form>
</section>


<?php
 if(isset($_GET["error"])) { // if error is set
     if($_GET["error"] == "emptyinput") {
         echo "<p>Fill in al fields!</p>"; // show user that they must input all fields
     }
     else if ($_GET["error"] == "wronglogin") {
         echo "<p>Invalid credentials!</p>"; // error is of wronglogin, show user they input invalid credentials
     }

 }
?>


<?php
 include_once 'footer.php'
?>

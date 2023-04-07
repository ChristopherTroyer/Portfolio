<?php
 include_once 'header.php'
?>

<section class="signup-form">
    <h2>Sign Up</h2>
    <form action="includes/signup.inc.php" method="post">
        <input type="text" name="name" placeholder="Full name..">
        <input type="text" name="username" placeholder="Username..">
        <input type="text" name="email" placeholder="Email..">
        <input type="password" name="pass" placeholder="Password..">
        <input type="password" name="reppass" placeholder="Repeat Password..">
        <button type="submit" name="submit">Sign Up</button>
    </form>
</section>

<?php
 if(isset($_GET["error"])) { // throw respective errors
     if($_GET["error"] == "emptyinput") {
         echo "<p>Fill in al fields!</p>";
     }
     else if ($_GET["error"] == "invalidUsername") {
         echo "<p>Choose a proper username!</p>";
     }
     else if ($_GET["error"] == "invalidEmail") {
         echo "<p>Choose a proper email!</p>";
     }
     else if ($_GET["error"] == "passmissmatch") {
         echo "<p>Passwords didn't match!</p>";
     }
     else if ($_GET["error"] == "usernametaken") {
         echo "<p>Username already exists!</p>";
     }
     else if ($_GET["error"] == "stmtfailed") {
         echo "<p>Something went wrong, please try again!</p>";
     }
     else if ($_GET["error"] == "none") {
         echo "<p>You have been signed up</p>";
     }





 }
?>

<?php
 include_once 'footer.php'
?>

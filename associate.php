<?php
 include_once 'header.php';
?>

<main>This is a test for associate page</main>
<br></br>

<body> 
    <p>Select a customer to create a new quote:</p>
    <form> 
        <select> 
            <option selected="selected">Choose Customer</option>

<?php
    require_once 'includes/legacydbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; // utility functions for error checking credentials / login utils

    $customersArray = fillArray($conn, "SELECT * FROM customers;"); //retrieve all customers in legacy db, place into array
    fillDropDown($customersArray, "name"); // populate dropdown with the name column from the array
?>
        </select>
        <input type="submit" value="Submit">
    </form>
</body>

<?php
 include_once 'footer.php'
?>

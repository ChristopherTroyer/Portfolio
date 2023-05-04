<?php
 include_once 'header.php';
?>

<main>This is a test for associate page</main>
<br></br>

<body> 
    <p>Select a customer to create a new quote:</p>
    <form action="create_quote.php" method="post"> 
        <select name="customer"> 
            <option value="default">Choose Customer</option>

<?php
    require_once 'includes/legacydbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; // utility functions for error checking credentials / login utils

    //TODO this needs to be changed to pull from Quotes table

    $customersArray = fillArray($conn, "SELECT * FROM customers;"); //retrieve all customers in legacy db, place into array
    fillDropDown($customersArray, "name"); // populate dropdown with the name column from the array
?>
        </select>
        <input type="submit" value="Submit">
    </form>

    <p>Select a quote to edit:</p>
    <form action="edit_quote.php" method="post"> 
        <select name="quote"> 
            <option value="default">Choose Quote id</option>
    
    <?php //select quote to edit
          // BUG: this breaks the following table for some reason?
        require_once 'includes/dbh.inc.php'; // current database handler
        require_once 'includes/functions.inc.php'; // utility

        $quoteArray = fillArray($conn, "SELECT * FROM New_Quote;"); //gets all quotes
        fillDropDown($quoteArray, "QuoteID");
    ?>
            </select>
        <input type="submit" value="Submit">
    </form>

    <br></br>
    <h1>List of all quotes:</h1>

    <?php
    require 'includes/legacydbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php'; // utility functions for error checking credentials / login utils

    $data_array = fillArray($conn, "SELECT * FROM customers;"); // retrieve all customers in legacy db, place into array

    // all column names to be used for table
    $col_names = array(
        0 => "id",
        1 => "name",
        2 => "city",
        3 => "street",
        4 => "contact",
    );

    echo "<table border='1'>";
    fillTableColumnNames($col_names); // create columns from array
    fillTableRow($data_array, $col_names); //populate table with data from data_array
    echo "</table>";

    ?>

</body>

<?php
 include_once 'footer.php'
?>

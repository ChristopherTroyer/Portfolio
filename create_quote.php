<?php
 include_once 'header.php'
?>

<?php
/*
    Handle creation of new quotes by associates
*/
    $customer_name = ""; // displays who the quote is for
    $input_email = ""; // where to email quote
    $line_itm_desc = array(); // all line item descriptions
    $line_itm_price = array(); // all line item prices
    $secrete_note_ar = array(); // all secret notes
    $discount_amount = "";
    $final_price = "";

    function showQuoteForm() {
        //
        include_once 'includes/functions.inc.php'; // util functions to process data
    
        $selected_customer = $_POST["customer"];
        echo "<h1>Quote for: " . $selected_customer . "</h1>"; // Show who the quote is for
    
        echo "<br></br>";
        decodeEchoString("<form action='create_quote.php' method='post'>"); //begin form
    
        createFormFieldFilled("text", "usr_email", "Email", $input_email); // email to send quote to
        echo "<br></br>";
        // where you enter line items
        createFormField("text", "line_item", "Line Item");
        createFormField("text", "line_item_price", "Price");
        echo "<br></br>";
        decodeEchoString("<button type='submit' name='new_line'>New Line Item</button>");

        // display line items
        echo "<br></br>";
    
        // secret notes
        createFormField("text", "secret_note", "Secret Notes");
        decodeEchoString("<button type='submit' name='new_note'>New Note</button>");
        echo "<br></br>";
    
        //discount amount
        createFormFieldFilled("number", "discount_amt", "Discount Amount", $discount_amount);
        decodeEchoString("<button type='submit' name='calc_price'>Calculate Price</button>");
        decodeEchoString("<input type='radio' name='ramount' id='percent_rbtn' value='percent'/>");
        decodeEchoString("<label for='percent_rbtn'>percent</label>");
        decodeEchoString("<input type='radio' name='ramount' id='ramount_rbtn' value='ramount'/>");
        decodeEchoString("<label for='ramount_rbtn'>Amount</label>");
        echo "<br></br>";
        //create the quote
        decodeEchoString("<button type='submit' name='submit'>Create Quote</button>");
        echo "<br></br>";
    
        //display final price
        decodeEchoString("<p>Final Price: " . $final_price); // display the final quote price
        echo "</form>"; // end of form
    }

if(isset($_POST["customer"])) { // is there a submission from associate.php
    include_once 'includes/functions.inc.php'; // util functions to process data
    //$line_itm_desc = array();
    //$line_itm_price = array();

    $selected_customer = $_POST["customer"];
    echo "<h1>Quote for: " . $selected_customer . "</h1>"; // Show who the quote is for

    echo "<br></br>";
    decodeEchoString("<form action='create_quote.php' method='post'>"); //begin form

    createFormField("text", "usr_email", "Email"); // email to send quote to
    echo "<br></br>";
    // where you enter line items
    createFormField("text", "line_item", "Line Item");
    createFormField("text", "line_item_price", "Price");
    echo "<br></br>";
    /*createFormField("text", "line_item", "Line Item");
    createFormField("text", "line_item_price", "Price");
    echo "<br></br>";
    createFormField("text", "line_item", "Line Item");
    createFormField("text", "line_item_price", "Price");
    echo "<br></br>";*/
    decodeEchoString("<button type='submit' name='new_line'>New Line Item</button>");

/*    if(isset($_POST["new_line"])) {
        //check if line desc is empty or if price is empty
        echo "test";
        if(!empty($_POST["line_item"])) {
            if(!empty($_POST["line_item_price"])) {
                // both line item desc and price are not empty
                array_push($line_itm_desc, $_POST["line_item"]);
                array_push($line_itm_price, $_POST["line_item_price"]);
            }
        }
        echo $line_itm_desc;
        echo $line_itm_price;
    }*/
    
    // display line items
    echo "<br></br>";

    // secret notes
    createFormField("text", "secret_note", "Secret Notes");
    decodeEchoString("<button type='submit' name='new_note'>New Note</button>");
    echo "<br></br>";

    //discount amount
    createFormField("number", "discount_amt", "Discount Amount");
    decodeEchoString("<button type='submit' name='calc_price'>Calculate Price</button>");
    decodeEchoString("<input type='radio' name='ramount' id='percent_rbtn' value='percent'/>");
    decodeEchoString("<label for='percent_rbtn'>percent</label>");
    decodeEchoString("<input type='radio' name='ramount' id='ramount_rbtn' value='ramount'/>");
    decodeEchoString("<label for='ramount_rbtn'>Amount</label>");
    echo "<br></br>";
    //createFormField("number", "price", "Price");
    //echo "<br></br>";
    //createFormField("date", "process_date", "Process Date");
    decodeEchoString("<button type='submit' name='submit'>Create Quote</button>");
    echo "<br></br>";

    //display final price
    decodeEchoString("<p>Final Price: "); // display the final quote price
    echo "</form>"; // end of form
}


// a submit button was pressed, show form again but with 
// previously filled in fields populated

if(isset($_POST["new_line"])) {
    //check if line desc is empty or if price is empty
    echo "test";
    echo $_POST["line_item"];
    if(!empty($_POST["line_item"])) {
        if(!empty($_POST["line_item_price"])) {
            // both line item desc and price are not empty
            $desc = $_POST["line_item"];
            $price = $_POST["line_item_price"];
            array_push($line_itm_desc, $desc);
            array_push($line_itm_price, $price);
        }
    }
    print_r($line_itm_desc);
    print_r($line_itm_price);
}

?>

<?php
include_once 'footer.php'
?>
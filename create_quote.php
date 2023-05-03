<?php
 include_once 'header.php'
?>

<script type='text/javascript'>
    var counter = 1; // counter for line items, starts at 1 because there is a field already there, it cannot be deleted
    var noteCounter = 0; // secret note counter
    var d_btn_id = "";

    function removeLineItem() {
        var container = document.getElementById("line_items"); // where to delete childNodes from

        var toRemove = document.getElementById(this.id); //the delete button

        var thenum = this.id.match(/\d+/)[0] // extract the number from the id
        var lineItem = document.getElementById("lineItem" + thenum); // line item
        var linePrice = document.getElementById("linePrice" + thenum); // line item price
        var br = document.getElementById("br" + thenum);
        

        var deleteLineItem = container.removeChild(lineItem); // remove line item field
        var throwawayNode = container.removeChild(toRemove); // remove the delete button
        var deleteLinePrice = container.removeChild(linePrice); // remove price field
        var deleteBr = container.removeChild(br);
        counter--; // decrement line item counter

    }
    //function to add lineItem_desc and lineItem price input
    function addLineItem() {
        var container = document.getElementById("line_items"); // append to this div
        
        var input = document.createElement("input");
        var price = document.createElement("input");
        var deleteBtn = document.createElement("input");
        var br = document.createElement("br");

        input.setAttribute('id', "lineItem" + counter);
        input.type = "text";
        input.name = "line" + counter;
        price.setAttribute('id', "linePrice" + counter);
        price.type = "number";
        price.name = "price" + counter;
        deleteBtn.setAttribute('id', ("delete_line_item" + counter));
        deleteBtn.type = "button";
        deleteBtn.value = "delete"
        deleteBtn.name = "delete_line_item" + counter;
        deleteBtn.onclick = removeLineItem;

        br.setAttribute('id', ("br" + counter));

        container.appendChild(input);
        container.appendChild(price);
        container.appendChild(deleteBtn);
        container.appendChild(br);

        counter++;
    }

    /*function removeLineItem(clicked_id) {
        var container = document.getElementById("line_items"); // where to delete childNodes from

        var toRemove = document.getElementById(clicked_id);

        var throwawayNode = container.removeChild(toRemove);
    }*/

    function removeSecretNote() {
        var container = document.getElementById("secret_notes"); // where to delete childNodes from

        var toRemove = document.getElementById(this.id); // the delete button
    }

    function addSecretNote() {
        var container = document.getElementById("secret_notes"); // append to this div
        
        var input = document.createElement("input");

        input.type = "text";
        input.name = "secret" + noteCounter;

        container.appendChild(input);
        container.appendChild(document.createElement("br"));

        noteCounter++;
    }
</script>

<?php
/*
    Handle creation of new quotes by associates
*/
    $customer_name = ""; // displays who the quote is for
    $input_email = ""; // where to email quote
    $line_itm_desc = array(); // all line item descriptions
    $line_itm_price = array(); // all line item prices
    $secrete_note_ar = array(); // all secret notes
    $discount_amount = 0;
    $final_price = 0;

    function showQuoteForm($cust_nme, $in_email, $lne_item_desc, $lne_item_price, $new_note, $discount, $final_prce) {
        //
        include_once 'includes/functions.inc.php'; // util functions to process data
    
        //$selected_customer = $_POST["customer"];
        echo "<h1>Quote for: " . $cust_nme . "</h1>"; // Show who the quote is for
    
        echo "<br></br>";
        decodeEchoString("<form action='create_quote.php' method='post'>"); //begin form    
        createFormFieldFilled("text", "usr_email", "Email", $in_email); // email to send quote to
        echo "<br></br>";

        // where you enter line items
        decodeEchoString("<div id='line_items'>"); // use this to append children / line items
        createFormField("text", "line_item", "Line Item");
        createFormField("text", "line_item_price", "Price");
        echo"<br></br>";
        decodeEchoString("</div>"); // div used to append more line items
        echo "<br></br>";
        decodeEchoString("<button type='button' onclick='addLineItem()'>New Line Item</button>"); // button used to add a new line item

        // display line items
        //listLineItems($lne_item_desc, $lne_item_price);
        echo "<br></br>";
    
        // secret notes
        decodeEchoString("<div id='secret_notes'>"); // use this to append children / secret notes
        createFormField("text", "secret_note", "Secret Notes");
        echo "<br></br>";
        decodeEchoString("</div>"); // div used to append secret notes
        decodeEchoString("<button type='button' onclick='addSecretNote()'>New Note</button>");
        echo "<br></br>";
    
        //discount amount
        createFormFieldFilled("number", "discount_amt", "Discount Amount", $discount);
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
        decodeEchoString("<p>Final Price: " . $final_prce); // display the final quote price
        echo "</form>"; // end of form
    }

if(isset($_POST["customer"])) { // is there a submission from associate.php
    $customer_name = $_POST["customer"];
    $_SESSION['customer_name'] = $customer_name;
    showQuoteForm($customer_name, $input_email, $line_itm_desc, $line_itm_price, $secrete_note_ar, $discount_amount, $final_price);
}


// a submit button was pressed, show form again but with 
// previously filled in fields populated

if(isset($_POST["new_line"])) {
    //check if line desc is empty or if price is empty
    echo "test";
    echo $_SESSION['customer_name'];
    //echo $_POST["usr_email"];
    //$cust_nme = $customer_name;
    $in_email = $_POST["usr_email"];
    echo $in_email;
    $lne_item_desc = $_POST["line_item"];
    $lne_item_price = $_POST["line_item_price"];
    $new_note = $_POST["secret_note"];
    $discount = $_POST["discount_amt"];
    $final_prce = "0"; // do something to calc current price


    if(!empty($cust_nme)) {
        //$customer_name = $cust_nme;
    }
    if(!empty($in_email)) {
        $input_email = $in_email;
    }
    if(!empty($lne_item_desc)) {
        array_push($line_itm_desc, $lne_item_desc);
    }
    if(!empty($lne_item_price)) {
        array_push($line_itm_price, $lne_item_price);
    }
    if(!empty($new_note)) {
        array_push($secrete_note_ar, $new_note);
    }
    if(!empty($discount)) {
        $discount_amount = $discount;
    }
    if(!empty($final_prce)) {
        $final_price = $final_prce;
    }

    showQuoteForm($customer_name, $input_email, $line_itm_desc, $line_itm_price, $secrete_note_ar, $discount_amount, $final_price);
}

?>

<?php
include_once 'footer.php'
?>
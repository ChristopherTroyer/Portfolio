<?php
 include_once 'header.php'
?>

<script type='text/javascript'>
    var counter = 1; // counter for line items, starts at 1 because there is a field already there, it cannot be deleted
    var noteCounter = 1; // secret note counter

    function removeLineItem() {
        var container = document.getElementById("line_items"); // where to delete childNodes from

        var toRemove = document.getElementById(this.id); //the delete button

        var thenum = this.id.match(/\d+/)[0]; // extract the number from the id
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

    function removeSecretNote() {
        var container = document.getElementById("secret_notes"); // where to delete childNodes from

        var toRemove = document.getElementById(this.id); // the delete button

        var thenum = this.id.match(/\d+/)[0]; // extract the number from the id
        var noteField = document.getElementById("secret_field" + thenum); // note to remove
        var br = document.getElementById("br" + thenum); // br to remove

        var deleteNote = container.removeChild(noteField); // remove note field
        var removeBtn = container.removeChild(toRemove); // remove delete button
        var removeBr = container.removeChild(br); // remove br tag

        noteCounter--;
    }

    function addSecretNote() {
        var container = document.getElementById("secret_notes"); // append to this div
        
        var input = document.createElement("input"); // new secret note field
        var deleteBtn = document.createElement("input"); // delete button
        var br = document.createElement("br");

        input.type = "text";
        input.name = "secret" + noteCounter;
        input.setAttribute('id', "secret_field" + noteCounter); // assign id to note field
        deleteBtn.setAttribute('id', "delete_secret" + noteCounter); // assign id name to delete button
        deleteBtn.type = "button";
        deleteBtn.value = "delete";
        deleteBtn.onclick = removeSecretNote;

        br.setAttribute('id', "br" + noteCounter); // assign id name to br tag

        container.appendChild(input);
        container.appendChild(deleteBtn);
        container.appendChild(br);

        noteCounter++;
    }

    function calculatePrice() {
        var itemPriceSum = 0;
        //var line_item = document.getElementById("linePrice" + 1); // get specific line item price field
        //alert(line_item.value);
        //var discountAmount = document.getElementById("")

        for(i=1;i<counter;i++) {
            //iterate through all prices to get sum
            var line_item = document.getElementById("linePrice" + i); // get specific line item price field
            itemPriceSum += Number(line_item.value);

        }

        //TODO neeed to change how the line items are implemented in the showQuoteForm()
        // it needs to include and id
        //var first_ln_item = 
        if(document.getElementById("percent_rbtn").checked) {
            // checked percent
            alert(itemPriceSum);
        } else if(document.getElementById("ramount_rbtn")) {
            // checked amount
        }
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
        decodeEchoString("<button type='button' name='calc_price' onclick='calculatePrice()'>Calculate Price</button>");
        decodeEchoString("<input type='radio' name='ramount' id='percent_rbtn' value='percent'/>");
        decodeEchoString("<label for='percent_rbtn'>percent</label>");
        decodeEchoString("<input type='radio' name='ramount' id='ramount_rbtn' value='ramount'/>");
        decodeEchoString("<label for='ramount_rbtn'>Amount</label>");
        echo "<br></br>";

        //create the quote
        decodeEchoString("<button type='submit' name='submit'>Create Quote</button>"); // submission button
        echo "<br></br>";
    
        //display final price
        decodeEchoString("<p>Final Price: " . $final_prce); // display the final quote price
        echo "</form>"; // end of form
    }

if(isset($_POST["customer"])) { // is there a submission from associate.php
    $customer_name = $_POST["customer"];
    $_SESSION['customer_name'] = $customer_name;
    showQuoteForm($customer_name, $input_email, $line_itm_desc, $line_itm_price, $secrete_note_ar, $discount_amount, $final_price); // these are empty vars, if vars are filled in then the appropriate fields will be filled
}

/*
    Handle submission of form: new quote creation
*/

if(isset($_POST["submit"])) { // associate creates a new quote

}

?>

<?php
include_once 'footer.php'
?>
<?php
 include_once 'header.php';
 require_once 'includes/dbh.inc.php';
 require_once 'includes/functions.inc.php';
?>

<br>
<main style="font-size: larger;text-align: center;"> HQ Page </main>
<a href="#finalized_quotes">Finalized Quotes</a>
<a href="#sanctioned_quotes">Sanctioned Quotes</a>
<hr>

<div style="overflow: hidden; overflow-y: none">
<?php

// constants
$COMMISSION_RATE = .33;

//pad: returns n number of whitespaces
function pad($n) { $s = ""; for ($_ = 1; $_ <= $n; $_++) $s = $s . '&nbsp;'; return $s; }

//draw_top: draws the first table
function draw_top($id, $date, $associate, $price, $oldprice, $email)
{
  $associate = htmlspecialchars($associate); // sanitize for safe html usage
  $email = htmlspecialchars($email);
  $text = pad(($id>9) ? 2 : 4) . '(' . $id . ')' . pad(8) . $date . pad(8) . $associate . ' : (' . $email . ')' . pad(16) . '$' . $price . ' (original price: $' . $oldprice . ')';
  echo '
  <div style="background-color: #c8ffa6; height: 20px; width: 1000%">
    <p style="display: inline-block; margin-top: 0px;">' . $text . '</p>
  </div>
  ';
}

//draw_quote_note: draws a quote note
function draw_quote_note($noteid, $desc, $allowchanges)
{
  $desc = htmlspecialchars($desc); // sanitize for safe html usage
  $text = pad((!($allowchanges) ? (($noteid>9) ? 2 : 4) : (($noteid >9) ? 30 : 32))) . '(' . $noteid . ')' . pad(8) . 'Quote Note' . pad(8) . ($desc ? $desc : "null");
  echo '
  <div style="background-color: #e6ffd0; height: 20px; margin-top: -17px; width: 1000%">
    <p>' . $text . '</p>
  </div>
  ' . ($allowchanges ? '
  <div style="margin-top: -20px; margin-left: 5px"><button id="' . $noteid . '" onclick="edit_note(this.id)">Edit</button></div>
  <div style="margin-top: -21px; margin-left: 50px"><button id="' . $noteid . '" onclick="remove_note(this.id)">Remove</button></div>
  ' : '');
}

//draw_line_item: draws a line item
function draw_line_item($itemid, $price, $desc, $allowchanges)
{
  $desc = htmlspecialchars($desc ? $desc : "null"); // sanitize for safe html usage
  $text = pad((!($allowchanges) ? (($itemid>9) ? 2 : 4) : (($itemid>9) ? 30 : 32))) . '(' . $itemid . ')' . pad(8) . 'Line Item' . pad(11) . '$' . $price;
  echo '
  <div style="background-color: #e6ffd0; height: 20px; margin-top: -17px; width: 1000%">
    <p>' . $text . '</p>
    <a style="margin-left: ' . ($allowchanges ? '380' : '270') . 'px;position: absolute;margin-top: -34px;">' . $desc . '</a>
  </div>
  ' . ($allowchanges ? '
  <div style="margin-top: -20px; margin-left: 5px"><button id="' . $itemid . '" onclick="edit_line_item(this.id)">Edit</button></div>
  <div style="margin-top: -21px; margin-left: 50px"><button id="' . $itemid . '" onclick="remove_line_item(this.id)">Remove</button></div>
  ' : '');
}

//draw_bottom: draws the last table
function draw_bottom($id, $allowchanges)
{
  echo '
  <div style="background-color: #8da18d; height: 20px; margin-top: -16px; width: 1000%">
    <p></p>
  </div>
  <div style="margin-top: -35px; padding: 15px 5px">
      <button id="' . $id . '" onclick="add_quote_note(this.id)" ' . ($allowchanges ? '' : 'disabled') . '>Add Quote Note</button>
      <button id="' . $id . '" onclick="add_line_item(this.id)" ' . ($allowchanges ? '' : 'disabled') . '>Add Line Item</button>
  </div>
  <form style="margin-top: -35px; margin-left: 242px;">
    <label for="discount" style="color: white;text-shadow: 2px 2px #000000;">Discount amount ($):</label>
    <input style ="width: 35px; text-align: right;" type="text" id="discount' . $id . '" name="discount"><br><br>
  </form>
  <div style="margin-top: -39px; margin-left: 431px;"><button id="' . $id . '" onclick="apply_discount(this.id)">Apply</button></div>
  <div style="margin-top: -21px; margin-left: 485px;"><button id="' . $id . '"onclick="' . ($allowchanges ? 'sanction' : 'submit_order') . '(this.id)">' . ($allowchanges ? 'Sanction Quote' : 'Submit Order') . '</button></div>
  <br>
  ';
}

//handle_quote_note: handles the action of drawing quote notes
function handle_quote_note($conn, $quoteid, $allowchanges)
{
  // retrieve quote notes from quoteid
  $sql_command = 'SELECT NoteID, note FROM Quote_Note WHERE QuoteID=?';
  $stmt = $conn->prepare($sql_command);
  $stmt->bind_param("i", $quoteid);
  $stmt->execute();
  $result = $stmt->get_result();
 
  while ($item = $result->fetch_assoc()) {
    draw_quote_note($item['NoteID'], $item['note'], $allowchanges);
  }
}

//handle_line_item: handles the action of drawing line items
function handle_line_item($conn, $quoteid, $allowchanges)
{
  // retrieve line items from quoteid
  $sql_command = 'SELECT ItemID, Price, Free_Desc FROM Line_Items WHERE QuoteID=?';
  $stmt = $conn->prepare($sql_command);
  $stmt->bind_param("i", $quoteid);
  $stmt->execute();
  $result = $stmt->get_result();
 
  while ($item = $result->fetch_assoc()) {
    draw_line_item($item['ItemID'], $item['Price'], $item['Free_Desc'], $allowchanges);
  }
}

//handle_finalized_quotes: handles the action of drawing finalized quotes
function handle_finalized_quotes($conn)
{
  // retrieve finalized quotes
  $sql_command = 'SELECT QuoteID, process_date, username, email, discount_amnt, price FROM New_Quote JOIN Associate ON New_Quote.AssocID=Associate.AssocID WHERE status="Finalized"';
  $result = mysqli_query($conn, $sql_command);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

  echo '<div id="finalized_quotes">';
  foreach ($rows as $item)
  {
    $price = $item['price'] - $item['discount_amnt']; // discounted price
    $price = number_format($price, 2, '.', ''); // format 0.00
    draw_top($item['QuoteID'], $item['process_date'], $item['username'], $price, $item['price'], $item['email']);
    handle_quote_note($conn, $item['QuoteID'], true);
    handle_line_item($conn, $item['QuoteID'], true);
    draw_bottom($item['QuoteID'], true);
  }
  echo '</div>';
}

//handle_sanctioned_quotes: handles the action of drawing sanctioned quotes
function handle_sanctioned_quotes($conn)
{
  // retrieve sanctioned quotes
  $sql_command = 'SELECT QuoteID, process_date, username, email, discount_amnt, price FROM New_Quote JOIN Associate ON New_Quote.AssocID=Associate.AssocID WHERE status="Sanctioned"';
  $result = mysqli_query($conn, $sql_command);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

  echo '<div id="sanctioned_quotes">';
  foreach ($rows as $item)
  {
    $price = $item['price'] - $item['discount_amnt']; // discounted price
    $price = number_format($price, 2, '.', ''); // format 0.00
    draw_top($item['QuoteID'], $item['process_date'], $item['username'], $price, $item['price'], $item['email']);
    handle_quote_note($conn, $item['QuoteID'], false);
    handle_line_item($conn, $item['QuoteID'], false);
    draw_bottom($item['QuoteID'], false);
  }
  echo '</div>';
}

//
// get page options and apply necessary query actions
//

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);

if (array_key_exists('_id', $queries))
{
  $id = (int) $queries['_id']; // QuoteID | NoteID | ItemID

  if (array_key_exists('_apply_discount', $queries))
  {
    // update discount_amnt
    $sql_command = 'UPDATE New_Quote SET discount_amnt=? WHERE QuoteID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("di", $queries['_apply_discount'], $id);
    $stmt->execute();
  }
  else if (array_key_exists('_sanction', $queries))
  {
    // update status to sanctioned
    $sql_command = 'UPDATE New_Quote SET status="Sanctioned" WHERE QuoteID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("i", $id);
    $stmt->execute();
  }
  else if (array_key_exists('_submit_order', $queries))
  {
    // complete submitting an order

    // retrieve relevant information about a quote, and then submit an order
    $sql_command = 'SELECT AssocID, discount_amnt, price, CustID FROM New_Quote WHERE QuoteID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
   
    while ($item = $result->fetch_assoc())
    {
      $amount = (float) ($item['price'] - $item['discount_amnt']); // price - discount
      $associate = $item['AssocID'];
      $commission_rate = submitOrder((int) $id, $associate, (int) $item['CustID'], (float) $amount);
      if ($commission_rate == -1)
      {
        echo '<script>alert("Error while submitting order")</script>';
        // refresh the page
        echo'<script>
        window.location.href = location.protocol + "//" + location.host + location.pathname;
        </script>
        ';
      }
      $commission_rate = $commission_rate / 100.;
      break;
    }

    // update associate's commission
    // step 1: get associate's current commission
    $sql_command = 'SELECT commission FROM Associate WHERE AssocID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("i", $associate);
    $stmt->execute();
    $result = $stmt->get_result();
   
    while ($item = $result->fetch_assoc())
      $commission = $item['commission'];

    $add_amount = round($amount * $commission_rate, 2.);
    $new_commission = $commission + $add_amount;

    // step 2: update associate's commission to include order
    $sql_command = 'UPDATE Associate SET commission=? WHERE AssocID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("di", $new_commission, $associate);
    $stmt->execute();

    // show success message
    echo '<script>alert("Order submitted; $' . $add_amount . ' has been added to Associate ' . $associate . '\'s commissions.");</script>';

    // delete quote from db
    $sql_command = 'DELETE FROM Quote_Note WHERE QuoteID=?;';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $sql_command = 'DELETE FROM Line_Items WHERE QuoteID=?;';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $sql_command = 'DELETE FROM New_Quote WHERE QuoteID=?;';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("i", $id);
    $stmt->execute();
  }
  else if (array_key_exists('_edit_note', $queries))
  {
    // edit a quote note's secret note
    $sql_command = 'UPDATE Quote_Note SET note=? WHERE NoteID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("si", $queries['_edit_note'], $id);
    $stmt->execute();
  }
  else if (array_key_exists('_remove_note', $queries))
  {
    // remove a quote note
    $sql_command = 'DELETE FROM Quote_Note WHERE NoteID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("i", $id);
    $stmt->execute();
  }
  else if (array_key_exists('_edit_line_item', $queries) && array_key_exists('_edit_line_item_desc', $queries))
  {
    // edit a line item's description
    $sql_command = 'UPDATE Line_Items SET price=?, Free_Desc=? WHERE ItemID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("dsi", $queries['_edit_line_item'], $queries['_edit_line_item_desc'], $id);
    $stmt->execute();
  }
  else if (array_key_exists('_remove_line_item', $queries))
  {
    // remove a line item
    $sql_command = 'DELETE FROM Line_Items WHERE ItemID=?';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("i", $id);
    $stmt->execute();
  }
  else if (array_key_exists('_add_quote_note', $queries))
  {
    // add a quote note
    $sql_command = 'INSERT INTO Quote_Note(`QuoteID`, `note`) VALUES (?, ?)';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("is", $id, $queries['_add_quote_note']);
    $stmt->execute();
  }
  else if (array_key_exists('_add_line_item', $queries) && array_key_exists('_add_line_item_desc', $queries))
  {
    // add a line item
    $sql_command = 'INSERT INTO Line_Items(`QuoteID`, `Price`, `Free_Desc`) VALUES (?, ?, ?)';
    $stmt = $conn->prepare($sql_command);
    $stmt->bind_param("ids", $id, $queries['_add_line_item'], $queries['_add_line_item_desc']);
    $stmt->execute();
  }

  // refresh the page
  echo'<script>
  window.location.href = location.protocol + "//" + location.host + location.pathname;
  </script>
  ';
}

// draw the tables
echo '<h style="color: black;font-size: larger;font-variant-caps: all-petite-caps;">Finalized Quotes</h><br><br>';
handle_finalized_quotes($conn);
echo '<hr style="border: 1px dotted #dbdbdb;"><h style="color: black;font-size: larger;font-variant-caps: all-petite-caps;">Sanctioned Quotes</h><br><br>';
handle_sanctioned_quotes($conn);

?>
<script>
function s(s) { return s.replaceAll('?','').replaceAll('=','').replaceAll('&',''); }; // clean input for url
function checkprice(p) { if (p < 0) return 0; return p; }; // positive floats only
function refresh(id, options) { window.location.href = location.protocol + "//" + location.host + location.pathname + "?_id=" + id + options; };
function apply_discount(id) { refresh(id, "&_apply_discount=" + s(checkprice(document.getElementById("discount" + id).value))); };
function sanction(id) { if (confirm("Would you like to sanction this quote?")) refresh(id, "&_sanction=1"); };
function submit_order(id) { if (confirm("Would you like to submit this order?")) refresh(id, "&_submit_order=1"); };
function edit_note(id) { refresh(id, "&_edit_note=" + s(prompt("Enter a new description:"))); };
function remove_note(id) { if (confirm("Remove this note?")) refresh(id, "&_remove_note=1"); };
function edit_line_item(id) { refresh(id, "&_edit_line_item=" + s(checkprice(prompt("Enter a new price:"))) + "&_edit_line_item_desc=" + s(prompt("Enter a new description:"))); };
function remove_line_item(id) { if (confirm("Remove this line item?")) refresh(id, "&_remove_line_item=1"); };
function add_quote_note(id) { refresh(id, "&_add_quote_note=" + s(prompt("Enter a description:"))); };
function add_line_item(id) { refresh(id, "&_add_line_item=" + s(checkprice(prompt("Enter a price:")))  + "&_add_line_item_desc=" + s(prompt("Enter a description:"))); };
</script>
</div>

<hr>
<a href="#finalized_quotes">Finalized Quotes</a>
<a href="#sanctioned_quotes">Sanctioned Quotes</a>

<?php
 include_once 'footer.php'
?>

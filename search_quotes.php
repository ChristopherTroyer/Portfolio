<?php
    include 'header.php';
?>

<?php
    require_once 'includes/legacydbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php';
    require_once 'adminfunctions.php';

    function showquotes($table)
    {
        //setting up table build
        echo"<table border='1'>";
        echo "<tr>";

        //shows what each thing is
        foreach($table[0] as $entity => $type)
        {
            echo "<th>$entity</th>";
        }
        echo "</tr>";

        foreach($table as $tables)
        {
            //qoute ID to identify each item
            $qid = '';

            //make new rows to the table since the other ones were
            //set. This will have info
            echo"<tr>";
            foreach($table as $entity => $type)
            {
                echo"<td>$type</td>"; //shows info
            }

            echo'<td><a href ="view_quotes.php/?specQuote=' .$table['QuoteID'].'">View Quote</a></td>';
            echo"</tr>";
        }
    }

    try
    {

        //search by drop down...status
        if(isset($_POST['status']))
        {
            if($_POST['status'] == 'show all')
            {
                $select = $pdo->query("SELECT * FROM New_Quote;");
                $table = $select->fetchAll(PDO::FETCH_ASSOC);
                showquotes($table);
            }
            else
            {
                $stat = $_POST['status'];
                $statinfo = "SELECT * FROM New_Quote WHERE status = '$stat';";
                $temp = $pdo->query($statinfo);
                $row = $temp->fetchAll(PDO::FETCH_ASSOC);

                echo("<br /><h3>" .$stat. "records.</h3>");

                if($row != false)
                {
                    showquotes($row);
                }
                else
                {
                    echo "<h3>No records.</h3>";
                }
            }
        }
        else
        {
            $select = $pdo->query("SELECT * FROM New_Quote;");
            if($select != false)
            {
                $table = $select->fetchAll(PDO::FETCH_ASSOC);
                showquotes($table);
            }
            else
            {
                echo "<h3>No records.</h3>";
            }
        }

        $selectsearch="SELECT DISTINCT status FROM New_Quote";

        echo "<br><form method='post'><select name=status onchange='javascript: submit()' value=''>Status</option>";
        echo"<option value=all>status...</option>";

        foreach($pdo->query($selectsearch) as $stattable)
        {
            echo"<option value=$stattable[status]>$stattable[status]</option>";
        }
        echo"</select></form>";



        //search by associate ID
        if(isset($_POST['AssocID']))
        {
            if($_POST['AssocID'] == 'show all')
            {
                $select = $pdo->query("SELECT * FROM New_Quote;");
                $table = $select->fetchAll(PDO::FETCH_ASSOC);
                showquotes($table);
            }
            else
            {
                $assoc = $_POST['AssocID'];
                $associnfo = "SELECT * FROM New_Quote WHERE AssocID = '$assoc';";
                $temp = $pdo->query($associnfo);
                $row = $temp->fetchAll(PDO::FETCH_ASSOC);

                echo("<br /><h3>" .$assoc. "records.</h3>");

                if($row != false)
                {
                    showquotes($row);
                }
                else
                {
                    echo "<h3>No records.</h3>";
                }
            }
        }
        else
        {
            $select = $pdo->query("SELECT * FROM New_Quote;");
            if($select != false)
            {
                $table = $select->fetchAll(PDO::FETCH_ASSOC);
                showquotes($table);
            }
            else
            {
                echo"<h3>No records.</h3>";
            }
        }

        $assocsearch="SELECT DISTINCT AssocID FROM New_Quote;";

        echo "<br><form method='post'><select name=status onchange='javascript: submit()' value=''>AssocID</option>";
        echo"<option value=all>Associate...</option>";

        foreach($pdo->query($assocsearch) as $assoctable)
        {
            echo"<option value=$assoctable[AssocID]>$stattable[AssocID]</option>";
        }
        echo"</select></form>";



        //search by customer id
        if(isset($_POST['CustID']))
        {
            if($_POST['CustID'] == 'show all')
            {
                $select = $pdo->query("SELECT * FROM New_Quote;");
                $table = $select->fetchAll(PDO::FETCH_ASSOC);
                showquotes($table);
            }
            else
            {
                $cust = $_POST['CustID'];
                $custinfo = "SELECT * FROM New_Quote WHERE CustID = '$cust';";
                $temp = $pdo->query($custinfo);
                $row = $temp->fetchAll(PDO::FETCH_ASSOC);

                echo("<br /><h3>" .$cust. "records.</h3>");

                if($row != false)
                {
                    showquotes($row);
                }
                else
                {
                    echo "<h3>No records.</h3>";
                }
            }
        }
        else
        {
            $select = $pdo->query("SELECT * FROM New_Quote;");

            if($select != false)
            {
                $table = $select->fetchAll(PDO::FETCH_ASSOC);
                showquotes($table);
            }
            else
            {
                 echo "<h3>No records.</h3>";
            }
        }

        $custsearch="SELECT DISTINCT CustID FROM New_Quote;";

        echo "<br><form method='post'><select name=status onchange='javascript: submit()' value=''>CustID</option>";
        echo"<option value=all>Customer ID...</option>";

        foreach($pdo->query($custsearch) as $stattable)
        {
            echo"<option value=$stattable[CustID]>$stattable[CustID]</option>";
        }
        echo"</select></form>";


    }
    catch(PDOexception $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?>

<?php
    include 'footer.php';
?>

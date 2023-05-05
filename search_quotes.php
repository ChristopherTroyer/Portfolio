<?php
    include 'header.php';
?>

<main>This is a test for searching quotes</main>

<?php
    require_once 'includes/legacydbh.inc.php'; // legacy database handler
    require_once 'includes/functions.inc.php';
    require_once 'adminfunctions.inc.php';

    //will end up in adminfunctions
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
    
    //drop downs
    try
    {
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

            }
        }
    }
    catch(PDOexception $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?>

<?php
    include 'footer.php';

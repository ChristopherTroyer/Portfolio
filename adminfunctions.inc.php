<!--
    This has different functions the admin can use that
    I did not put into functions.inc.php because I am scared.
    I don't know why I am scared, I just am.
-->

<?php
    require_once 'includes/dbh.inc.php';
    require_once 'includes/legacydbh.inc.php';

        $username = "debian-sys-maint";
        $password = "vUlvuFil887Af63z";
        $dbname = "testing";
        $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password); 

    //searches through dbh for information and gathers it all
    //to use
    function dbhquery($my_sqli, $arr = null)
    {

        $drop = $pdo->prepare($my_sqli);
        if(isset($arr))
        {
            $drop->execute($arr);
        }
        else
        {
            $drop->execute;
        }
        return $drop->fetchall();
    }

    //finds all associates within the dbh
    function associateIDS()
    {
        $my_sqli = "SELECT AssocID from Associate";
        $aid = dbhquery($my_sqli, []);
        $arr = [];

        foreach($aid as $arrID)
        {
            $arr[] = $arrID['AssocID'];
        }
        return $arr;
    }

    //gathers all information from associate based on ID
    function associateinfo(int $ID)
    {
        global $pdo;
        $my_sqli = "SELECT * FROM Associate WHERE AssocID = :Id";

        $drop = $pdo->prepare($my_sqli);
        $drop->execute(['Id' => $ID]);

        $ainfo = $drop->fetch();

        $arr ['First_Name'] = $ainfo['First_name'];
        $arr ['Last_Name'] = $ainfo['last_name'];
        $arr ['Username'] = $ainfo['username'];
        $arr ['Password'] = $ainfo['password'];
        $arr ['Address'] = $ainfo['address'];
        $arr ['Commission'] = $ainfo['commission'];
        $arr ['Permission'] = $ainfo['permission'];
        $arr ['Email'] = $ainfo['email'];

        return $arr;
    }

    //show all associate information if the assoiate
    //is being updated.
    function show_all_assoc()
    {
        echo
        ("
            <tablee border=solid>
            <tr>
            <th> Associate ID </th>
            <th> Username </th>
            <th> Password </th>
            <th> First Name </th>
            <th> Last Name </th>
            <th> Email </th>
            <th> Address </th>
            <th> Commission </th>
            <th> Permission </th>
            </tr>
        ");
        $ID = associateIDS();
        foreach($ID as $arrID)
        {
            $assoc = associateinfo($arrID);

            echo ("<tr>");
                echo ("<td>" . $arrID . "</td>");
                echo ("<td>" . $assoc['First_name'] . "</td>");
                echo ("<td>" . $assoc['last_name'] . "</td>");
                echo ("<td>" . $assoc['username'] . "</td>");
                echo ("<td>" . $assoc['password'] . "</td>");
                echo ("<td>" . $assoc['address'] . "</td>");
                echo ("<td>" . $assoc['email'] . "</td>");
                echo ("<td>" . $assoc['commission'] . "</td>");
                echo ("<td>" . $assoc['permission'] . "</td>");
            echo ("</tr>");
        }
    }

    //gathers all quotes in the dbh
    function quoteIDS()
    {
        $my_sqli = "SELECT QuoteID from New_Quote";
        $qid = dbhquery($my_sqli, []);
        $arr = [];

        foreach($qid as $arrID)
        {
            $arr = $arrID['QuoteID'];
        }
        return $arr;
    }

    //Gathers information on quote based on ID
    function quoteinfo(int $ID)
    {
        global $pdo;
        $my_sqli = "SELECT * FROM New_Quote WHERE QuoteID = :Id";

        $drop = $pdo->prepare($my_sqli);
        $drop->execute(['Id' => $ID]);

        $qinfo = $drop->fetch();

        $arr ['Customer_ID'] = $qinfo['CustID'];
        $arr ['Associate_ID'] = $qinfo['AssocID'];
        $arr ['Customer_email'] = $qinfo['cust_talk'];
        $arr ['Discount_amount'] = $qinfo['discount_amnt'];
        $arr ['Discount_percent'] = $qinfo['discount_prcn'];
        $arr ['Price'] = $qinfo['price'];
        $arr ['Status'] = $qinfo['status'];
        $arr ['Date'] = $qinfo['process_date'];

        return $arr;
    }
?>

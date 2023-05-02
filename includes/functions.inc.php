<?php

/*
    Error handling for signup.inc.php and utility functions for login.inc.php
*/

function emptyInputSignup($first_name, $last_name, $username, $pass, $reppass, $address, $commission, $email, $permission) {
    /*
    Check if any of the parameters passed in are empty, return true if so
    */
    $result = false;
    if(empty($first_name) || empty($last_name) || empty($username) || empty($pass) || empty($reppass) || empty($address) || empty($commission) || empty($email) || empty($permission)) {
        $result = true;
    } 
    return $result;
}

function invalidUsername($username) {
    /*
    Check if the username is invalid by using an expression
    return true if invalid
    */
    $result = false;
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    }

    return $result;
}

function invalidEmail($email) {
    /*
    Check if provided email is invalid using filter_var and the filter: FILTER_VALIDATE_EMAIL
    return true if email is invalid
    */
    $result = false;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }

    return $result;
}

function passwordMatch($pass, $reppass) {
    /*
    Check if password and confirm password match
    return true if there is a missmatch
    */
    $result = false;
    if($pass !== $reppass) {
        $result = true;
    }

    return $result;
}

function usernameExists($conn, $username, $email) {
    /*
    Check if the username provided exists:
        connect to db and search for a user using username provided or email
        return false when no match found
    */
    //$sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;"; // query for finding a possible match
    $sql = "SELECT * FROM Associate WHERE username = ? OR email = ?;"; // query for finding a possible match
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) { // error checking the query
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt); // execute query

    $resultData = mysqli_stmt_get_result($stmt); // store result from query

    if($row = mysqli_fetch_assoc($resultData)) {
        // match was found, return something other than false
        //return $row;
        $result = $row;
    } else {
        // no matching username was found
        echo "rte ";
        $result = false;
        //return $result; 
    }

    mysqli_stmt_close($stmt);
    
    return $result;
}

function createUser($conn, $first_name, $last_name, $username, $pass, $address, $commission, $email, $usersPerms){
    /*
    Create user using parameters

    Updated to handle Associate table and new permission levels
    */
    $sql = "insert into Associate (First_name, last_name, username, password, address, commission, email, permission) values (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) { // error checking for query
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPass = password_hash($pass, PASSWORD_DEFAULT); // create a password hash using default algorithm

    mysqli_stmt_bind_param($stmt, "sssssdsi",$first_name, $last_name, $username, $hashedPass, $address, $commission,$email, $usersPerms);
    mysqli_stmt_execute($stmt); // execute creation of new user

    mysqli_stmt_close($stmt);

    header("location: ../signup.php?error=none"); // placeholder TODO
    exit();
}

/*
Utils for login.inc.php
*/

function emptyInputLogin($username, $pass) {
    /*
    Check if log credentials are empty
    return true if empty
    */
    $result = false;
    if(empty($username) || empty($pass)) {
        $result = true;
    }

    return $result;
}

function loginUser($conn, $username, $pass) {
    /*
    Log in user using parameters

    Updated to handle new Associate table

    NOTE: Email/username bug seems fixed
    */
    $uidExists = usernameExists($conn, $username, $username); // call function to check for existing username

    if($uidExists === false) { // throw error if user not found
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    // no error so proceed with logging in user
    //$passHashed = $uidExists["usersPass"];
    $passHashed = $uidExists["password"];
    $checkPass = password_verify($pass, $passHashed); // verify that the password user entered matches the hash

    if($checkPass === false) { // no match from pass to hash, throw error
        header("Location: ../login.php=wrongloginpass");
        exit();
    }
    else if($checkPass === true) {
        session_start();
        $_SESSION["userid"] = $uidExists["AssocID"];
        $_SESSION["username"] = $uidExists["username"];
        $_SESSION["name"] = $uidExists["First_name"];
        $_SESSION["perms"] = $uidExists["permission"];

        // handle taking associate to associate page, admin to admin page, hq to hq page

        /*
        go to associate access page
        permission for associate: 0, 
        */
        if($_SESSION["perms"] == "0") { 
            // go to associate page
            header("location: ../associate.php");
        }
        else if($_SESSION["perms"] == "1") {
            // go to hq page
            header("location: ../hq_access.php");
        }
        else if($_SESSION["perms"] == "2") {
            // go to admin page
            header("location: ../admin.php");
        }

        //header("location: ../index.php");
        exit();
    }
}

//Takes in order information and makes request to remote server, extended from professor provided example.
//arguments -> order number, associate id number, custid number, order cost in dollars
//currently just echos whatever return data as debug but confirms that it has successfully submitted
function submitOrder(int $order, $associate, $custid, double $amount)
    {
        $url = 'http://blitz.cs.niu.edu/PurchaseOrder/';
        $data = array(
	        'order' => $order, 
	        'associate' => $associate,
	        'custid' =>  $custid, 
	        'amount' => $amount);
		
        $options = array(
            'http' => array(
                'header' => array('Content-type: application/json', 'Accept: application/json'),
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if (!$result) {
            echo("File_get_contents() failed")
        }
        else {
            echo("Submission to external system successful!")
            echo("Debug return info:")
            echo($result);
        }
    }
/*
 util functions for extracting data from dbs
*/

function fillArray($db_conn, $your_query) {
    //db_conn is the $conn variable from dbh.inc.php or legacydbh.inc.php
    //
    //your_query is where you build your query, EX: "SELECT * FROM Associate;"

    $result = mysqli_query($db_conn, $your_query);

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC); // array containing your data

    return $rows;

    /*foreach($rows as $row) {
        echo  "<p>" . "Customer: " . $row["name"] . "</p>";
    }*/
}

function fillDropDown($array, $column_name) {
    //$array should be the array returned from fillArray()
    //
    // column_name is the name of the column you want to retrieve: EX "name" for the column "name" in legacy db
    foreach($array as $item) {
        $col = $item["". $column_name .""];
        //echo "<option>$col</option>";
        echo "<option" . " value=" . $col . ">" . $col . "</option>";
    }
}

function fillTableColumnNames($array_tbl_col_names) {
    //array_tbl_col_names is an array of strings, names of columns

    // print the column names
    echo "<tr>";
    foreach($array_tbl_col_names as $col) {
        echo "<th>" . $col . "</th>";
    }
    echo "</tr>";

}

function fillTableRow($data_array, $array_tbl_col_names) {
    // data_array is the array you filled using fillArray()
    // $array_tbl_col_names is an array of strings, names of columns for table
    $nbm_of_cols = count($array_tbl_col_names);
    $nbm_of_rows = count($data_array);
    $x = 0; // counter for rows
    $y = 0; // counter for columns

    while($x<$nbm_of_rows) { // for each row
        echo "<tr>";
        $y = 0;
        while($y<$nbm_of_cols){ // for each column
            $item = $data_array[$x]; //get array of data to fill cell
            $obj = $item["" . $array_tbl_col_names[$y] . ""]; // get specific cell
            echo "<td>" . $obj . "</td>"; // print cell
            $y++;
        }
        echo "</tr>";
        $x++;
    }
}

function decodeEchoString($your_string) {
    echo html_entity_decode($your_string);
}

function createFormField($type,$name, $placeholder) {
    //type is the input type for the form
    //name is the name of the field, used when processing form data
    //placeholder is the hint text in the form field

    $str = "<input type='" . $type . "'" . " name='" . $name . "'" . " placeholder='" . $placeholder . "'" . ">";
    //$converted = html_entity_decode($str); // decode string above to make into proper html chars
    //echo $converted;
    decodeEchoString($str);
}

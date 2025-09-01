## Quote System:
  ### Responsibilities: Database planning. Use case planning, wrote most of the use/actor table and related Diagrams.
  #### Languages: PHP, CSS, SQL
  Using an Agile methodology we constructed a website that would generate price quotes for a fictional buisness.
    
  This site was required to be compatable with a locked down legacy database, and send orders via POST requests to a RESTful processing system.
  
  The site had to implement it's own associate and quotes managment (update, view, search, delete) including user accounts for different levels of access to the database.
  
  I also took on setting up and running a LAMP stack on a rented server. I also wrote a setup guide for my group members so they could run a server of their own.<br/>




[Use/Actor table](https://docs.google.com/document/d/1tDHzCVj-peA-vWgy1vFP9yJHyWqzmNMserJfFrErD_Y/)


### Legacy Database
The legacy customer database is a MySQL relational database system that is based on SQL.
The customer information is contained in table "customers" which has 5 columns: id, name, city, street, contact.

This SQL statement was used to create the customers table:

```
 use csci467;
 create table customers (
    id int,
    name varchar(50),
    city varchar(50),
    street varchar(50),
    contact varchar(50)
 );
```

click [here](http://courses.cs.niu.edu/unix/NetTest/ListCust.php) to see the current content of the database.

![image](https://user-images.githubusercontent.com/2314553/228079722-f65a33cb-93e0-4477-bfdf-60f83d7a4c88.png)

### Processing System
The purchase order processing system runs as a RESTful webservice on host blitz.cs.niu.edu.
The URL "http://blitz.cs.niu.edu/PurchaseOrder/" accepts http POST requests that contain parameters in JSON format:
purchase order number, associate id, customer id, amount.
The customer id and purchase order number must be unique. The customer id must be valid.
It will respond in JSON format with the date on which the order will be processed and a commission percentage for the sales person.
If any error is detected, the JSON response will contain a field "errors" with one or more error messages.

Here is an example PHP program that sends a processing request:

```
<?php
$url = 'http://blitz.cs.niu.edu/PurchaseOrder/';
$data = array(
	'order' => 'xyz-987654321-ba', 
	'associate' => 'RE-676732',
	'custid' => '21', 
	'amount' => '7654.32');
		
$options = array(
    'http' => array(
        'header' => array('Content-type: application/json', 'Accept: application/json'),
        'method'  => 'POST',
        'content' => json_encode($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo($result);
?>
```

Click [here](http://courses.cs.niu.edu/unix/NetTest/po.php) to run this php program now.

Click [here](http://blitz.cs.niu.edu/Monitor/#/orders) to see recent successful requests.

<?php

require_once('../../config.php');

$query= $con->prepare("INSERT INTO tagq.orders (`buyer_id`, `item_id`,
`fulfilled`) VALUES
(?,?,?)");

$buyer_id = $_REQUEST['buyer_id'];
$item_id = $_REQUEST['item_id'];
$fulfilled = $_REQUEST['fulfilled'];



$query->bind_param('iis', $buyer_id, $item_id, $fulfilled); //bind the parameters
if ($query->execute()){ //execute query
echo "Query executed.";
header("Location: /app/orders/");
die();
}else{
echo "Error executing query.";
}
?>

<?php

require_once('../../config.php');

$query= $con->prepare("DELETE FROM tagq.orders WHERE order_id=?");
$order_id = $_POST['order_id'];

$query->bind_param('s', $order_id); //bind the parameters

if ($query->execute()){
 echo "Query executed.";
 header("Location: /app/orders/");
die();
}else{
 echo "Error executing query.";
}

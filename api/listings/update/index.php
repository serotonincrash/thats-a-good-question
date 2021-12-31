<?php
require_once('../../config.php');

$query= $con->prepare("UPDATE items SET name=?,description=?,price=? WHERE item_id=?");
$item_id = $_REQUEST["item_id"];
$name = $_REQUEST["item_name"];
$description = $_REQUEST["description"];
$materials = $_REQUEST["materials"];
$metadata = $_REQUEST["metadata"];
$price = $_REQUEST["price"];

$query->bind_param('ssssii', $name, $description, $materials, $metadata, $price, $item_id); //bind the parameters
if ($query->execute()){
    
    die();
}else{
    echo "Error executing query.";
}
?>
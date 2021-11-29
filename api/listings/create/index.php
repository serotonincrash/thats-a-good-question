
<?php
require_once('../../config.php');
mysqli_report(MYSQLI_REPORT_ALL);
$query = $con->prepare("INSERT INTO `items` (`name`, `description`, `materials`, `metadata`, `price`, `vendor_id`) VALUES(?,?,?,?,?,?)");

$item_name = $_REQUEST["item_name"];
$description = $_REQUEST["description"];
$materials = $_REQUEST["materials"];
$metadata = $_REQUEST["metadata"];
$price = $_REQUEST["price"];

session_start();
$vendor_id = $_SESSION["user_id"];

$query->bind_param('ssssii', $item_name, $description, $materials, $metadata, $price, $vendor_id); //bind the parameters

if ($query->execute()){ //execute query
    header("Location: /app/listings/");
    die();
}else{
    echo "Error executing query.";
}
?>

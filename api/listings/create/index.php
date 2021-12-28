<?php
require_once('../../config.php');
mysqli_report(MYSQLI_REPORT_ALL);
$query = $con->prepare("INSERT INTO `items` (`name`, `description`, `price`, `vendor_id`) VALUES(?,?,?,?)");

$item_name = htmlspecialchars($_REQUEST["item_name"]);
$description = htmlspecialchars($_REQUEST["description"]);
$materials = $_REQUEST["materials"];
$metadata = $_REQUEST["metadata"];
$price = htmlspecialchars($_REQUEST["price"]);

$material_values = array_count_values($materials);

// Process metadata


// Process inventory requirements


session_start();
$vendor_id = $_SESSION["user_id"];

$query->bind_param('ssii', $item_name, $description, $price, $vendor_id); //bind the parameters

if ($query->execute()){ //execute query

    // Insert metadata

    // Insert inventory requirements

    
    header("Location: /app/listings/");
    die();
}else{
    echo "Error executing query.";
}
?>

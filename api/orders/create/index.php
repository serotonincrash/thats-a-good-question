<?php
require_once('../../config.php');
require_once("../../session_start.php");
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(405);
    die();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
// assert that user is logged in - regen session id
require_once("../../session_handler.php");
if ($_SESSION['role'] !== 'User' && $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    die("You're not allowed to access this!");
}


if (!isset($_REQUEST['metadata'])) {
    http_response_code(401);
    die("Your metadata isn't encoded properly!");
}

$metadata = json_decode($_REQUEST['metadata'], true);

if (!$metadata) {
    http_response_code(500);
    die("The server encountered an issue parsing your metadata!");
}

if (!isset($_GET['itemID'])) {
    http_response_code(401);
    die("No item ID specified!");
}

foreach ($metadata as $value) {
    if (strlen($value) > 255) {
        http_response_code(400);
        die("Your metadata input should be less than 255 characters!");
    }
}
$item_id = $_GET['itemID'];
$stockRequireQuery = $con->prepare("SELECT item_inventory_usage.part_id as part_id, item_inventory_usage.amount as amount, inventory.stock as stock FROM item_inventory_usage INNER JOIN inventory on inventory.part_id = item_inventory_usage.part_id WHERE item_id = ?");
$stockRequireQuery->bind_param('i', $item_id);

$stocks = [];
if ($stockRequireQuery->execute()) {
    if ($result = $stockRequireQuery->get_result()) {
        while ($row = $result->fetch_assoc()) {
            if ($row['amount'] > $row['stock']) {
                http_response_code(500);
                die("The inventory items used to make this item are out of stock!");
            }
            $stocks[] = $row;
        }
    }
}

// Order information creation
$zero = 0;
$orderQuery = $con->prepare("INSERT INTO orders (buyer_id, item_id, fulfilled) VALUES (?,?,?)");
$orderQuery->bind_param("iii", $_SESSION['user_id'], $item_id, $zero);

if (!$orderQuery->execute()) {
    http_response_code(500);
    die("An error occured whilst creating the order.");
}
// Create metadata
// bulk insert...
// I HATE PHP
$order_id = $orderQuery->insert_id;
$types = str_repeat("isi", count($metadata));
$question = str_repeat("(?,?,?),", count($metadata) - 1) . "(?,?,?)";
$createMetaQuery = $con->prepare("INSERT INTO order_metadata (order_id, value, metadata_id) VALUES ". $question);
$params = [];
foreach ($metadata as $id => $val) {
    $params[] = $order_id;
    $params[] = htmlspecialchars($val, ENT_QUOTES);
    $params[] = $id;
}

$createMetaQuery->bind_param($types, ...$params);
if (!$createMetaQuery->execute()) {
    http_response_code(500);
    die("An error occured whilst creating the metadata.");
}

// Update inventory 
// Generate bulk update
// I hate php
$types = str_repeat("ii", count($stocks)) . str_repeat("i", count($stocks));
$case_types = str_repeat(" WHEN ? THEN ? ", count($stocks));
$updateStockQueryStr = "UPDATE inventory SET stock = (CASE part_id ".$case_types;
$updateStockQueryStr = $updateStockQueryStr . " END) WHERE part_id IN (" . str_repeat("?,", count($stocks) - 1) . "?)";

$params = [];
foreach ($stocks as $stock) {
    $new_stock = $stock['stock'] - $stock['amount'];
    $params[] = $stock['part_id'];
    $params[] = $new_stock;
}

foreach ($stocks as $stock) {
    $params[] = $stock["part_id"];
}

$updateStockQuery = $con->prepare($updateStockQueryStr);
$updateStockQuery->bind_param($types, ...$params);

if (!$updateStockQuery->execute()) {
    http_response_code(500);
    die("An error occured whilst updating stock.");
}

echo "Order created successfully!";

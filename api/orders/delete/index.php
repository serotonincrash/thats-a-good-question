<?php
require_once('../../config.php');
require_once("../../session_start.php");
if ($_SERVER['REQUEST_METHOD'] !== "DELETE") {
    http_response_code(405);
    die();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
// assert that user is logged in - regen session id
require_once("../../session_handler.php");
if (!isset($_SESSION['role'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}

$role = $_SESSION['role'];

if ($role !== 'User' && $role !== 'Vendor' && $role !== "Admin") {
    http_response_code(403);
    die("You're not allowed to do this!");
}

if(!isset($_GET['orderID'])) {
    // partID not an int
    http_response_code(400);
    die("Your order ID is missing!");
}

if (strval($_GET['orderID']) !== strval(intval($_GET['orderID']))) {
    // partID not an int
    http_response_code(400);
    die("Your order ID is not an integer!");
}
$order_id = $_GET['orderID'];

// Get order, make sure it belongs to the user/item belongs to the vendor
$orderQuery = $con->prepare("SELECT orders.item_id as item_id, orders.fulfilled as fulfilled, orders.buyer_id as buyer_id, items.vendor_id as vendor_id FROM orders INNER JOIN items ON orders.item_id = items.item_id WHERE order_id = ?");
$orderQuery->bind_param("i", $order_id);

if (!$orderQuery->execute()) {
    http_response_code(500);
    die("There was an issue querying the database.");
}
$item_id = null;
if ($result = $orderQuery->get_result()) {
    $row = $result->fetch_assoc();
    $user_id = $_SESSION['user_id'];
    if ($user_id !== $row['buyer_id'] && $user_id !== $row['vendor_id']) {
        http_response_code(403);
        die("You can't delete this!");
    }

    if ($row['fulfilled']) {
        http_response_code(403);
        die("You can't delete this!");
    }

    $item_id = $row['item_id'];
}

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

// Replenish stock into the db
// Basically what we did when creating order but reverse
// I still hate php
$types = str_repeat("ii", count($stocks)) . str_repeat("i", count($stocks));
$case_types = str_repeat(" WHEN ? THEN ? ", count($stocks));
$updateStockQueryStr = "UPDATE inventory SET stock = (CASE part_id ".$case_types;
$updateStockQueryStr = $updateStockQueryStr . " END) WHERE part_id IN (" . str_repeat("?,", count($stocks) - 1) . "?)";

$params = [];
foreach ($stocks as $stock) {
    // add instead of subtract - we're restocking the reserved stock
    $new_stock = $stock['stock'] + $stock['amount'];
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

// Delete order
$deleteQuery = $con->prepare("DELETE FROM orders WHERE order_id = ?");
$deleteQuery->bind_param("i", $order_id);

if ($deleteQuery->execute()) {
    echo "Order deleted successfully.";
} else {
    http_response_code(500);
    die("There was an issue querying the database.");
}
?>
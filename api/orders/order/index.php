<?php
require_once('../../config.php');
require_once("../../session_start.php");
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
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

if (!isset($_GET['orderID'])) {
    http_response_code(400);
    die("No item ID was specified!");
}
$order_id = $_GET['orderID'];
$orderQuery = $con->prepare("SELECT orders.fulfilled, users.username, orders.order_id, orders.item_id FROM orders INNER JOIN users ON orders.buyer_id = users.user_id WHERE order_id = ?");
$orderQuery->bind_param("i", $order_id);

if (!$orderQuery->execute()) {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}
$order = [];
if ($result = $orderQuery->get_result()) {
    if ($result->num_rows === 0) {
        http_response_code(404);
        die("This order doesn't exist!");
    }
    $row = $result->fetch_assoc();
    $order['info'] = $row;
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}

$metaQuery = $con->prepare("SELECT order_metadata.value, metadata.metadata_name, metadata.id as metadata_id FROM metadata INNER JOIN order_metadata ON order_metadata.metadata_id = metadata.id WHERE order_metadata.order_id = ?");
$metaQuery->bind_param("i", $order_id);
if (!$metaQuery->execute()) {
    http_response_code(500);
    die("An error occured whilst querying the database.");
} 

if ($result = $metaQuery->get_result()) {
    $metadata = [];
    while ($row = $result->fetch_assoc()) {
        $metadata[] = $row;
    }
    $order['metadata'] = $metadata;
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}

$itemQuery = $con->prepare("SELECT items.name, vendors.username as vendor_name, items.description, items.price FROM items INNER JOIN users AS vendors ON vendors.user_id = items.vendor_id WHERE item_id = ?");
$itemQuery->bind_param("i", $order["info"]["item_id"]);

if (!$itemQuery->execute()) {
    http_response_code(500);
    die("An error occured whilst querying the database.");
} 

if ($result = $itemQuery->get_result()) {
    $row = $result->fetch_assoc();
    $order['item'] = $row;
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}

// Select all reviews that are associated with this order
$reviewQuery = $con->prepare("SELECT * FROM reviews where order_id = ?");
$reviewQuery->bind_param("i", $order_id);
if (!$reviewQuery->execute()) {
    http_response_code(500);
    die("An error occured whilst querying the database.");
} 

if ($result = $reviewQuery->get_result()) {
    // There should only be one review
    $row = $result->fetch_assoc();
    $order['review'] = $row;
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}

echo json_encode($order);
?>
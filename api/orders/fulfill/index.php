<?php
require_once('../../config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
    http_response_code(405);
    die();
}
if (count($_SESSION) === 0) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
// assert that user is logged in - regen session id
require_once("../../session.php");
if (!isset($_SESSION['role'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}

$role = $_SESSION['role'];

if ($role !== 'User') {
    http_response_code(403);
    die("You're not allowed to do this!");
}

if(!isset($_GET['orderID'])) {
    // partID not an int
    http_response_code(400);
    die("Your order ID is missing!");
}

$order_id = $_GET['orderID'];

if (strval($_GET['orderID']) !== strval(intval($_GET['orderID']))) {
    // partID not an int
    http_response_code(400);
    die("Your order ID is not an integer!");
}


// Get order, make sure it belongs to the user/item belongs to the vendor
$orderQuery = $con->prepare("SELECT orders.fulfilled as fulfilled, orders.buyer_id as buyer_id, items.vendor_id as vendor_id FROM orders INNER JOIN items ON orders.item_id = items.item_id WHERE order_id = ?");
$orderQuery->bind_param("i", $order_id);

if (!$orderQuery->execute()) {
    http_response_code(500);
    die("There was an error querying the database.");
}

if ($result = $orderQuery->get_result()) {
    $row = $result->fetch_assoc();
    $user_id = $_SESSION['user_id'];
    if ($user_id !== $row['buyer_id'] && $user_id !== $row['vendor_id']) {
        http_response_code(403);
        die("You can't delete this!");
    }

    if ($row['fulfilled']) {
        http_response_code(403);
        die("You can't fulfill an order that's already fulfilled!");
    }
}

// Update the order
$updateQuery = $con->prepare("UPDATE orders set fulfilled = TRUE WHERE order_id = ?");
$updateQuery->bind_param("i", $order_id);

if (!$updateQuery->execute()) {
    http_response_code(500);
    die("There was an error querying the database.");
}

echo "Updated successfully.";
<?php
require_once("../config.php");
require_once("../session_start.php");
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}

// assert that user is logged in - regen session id
require_once("../session_handler.php");

// Anyone can grab the info
if (!isset($_SESSION['role'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}

$role = $_SESSION['role'];
if ($role == "User") {
    $orderQuery = $con->prepare("SELECT orders.order_id, items.name, users.username, orders.fulfilled, vendors.username as vendorName FROM (orders INNER JOIN users on orders.buyer_id = users.user_id) INNER JOIN (items INNER JOIN users as vendors on items.vendor_id = vendors.user_id) on orders.item_id = items.item_id WHERE orders.buyer_id = ?");
    $orderQuery->bind_param("i", $_SESSION['user_id']);
    if ($orderQuery->execute()) {
        $data = [];
        if ($result = $orderQuery->get_result()) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $data[] = $row;
            }
        }
        echo json_encode($data);
    }
} else {
    $orderQuery = $con->prepare("SELECT orders.order_id, items.name, users.username, orders.fulfilled, vendors.username as vendorName FROM (orders INNER JOIN users on orders.buyer_id = users.user_id) INNER JOIN (items INNER JOIN users as vendors on items.vendor_id = vendors.user_id) ON items.vendor_id = ? AND items.item_id = orders.item_id");
    $orderQuery->bind_param("i", $_SESSION['user_id']);
    if ($orderQuery->execute()) {
        $data = [];
        if ($result = $orderQuery->get_result()) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $data[] = $row;
            }
        }
        echo json_encode($data);
    }
}


?>

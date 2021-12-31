<?php

require_once('../../config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    http_response_code(405);
    die();
  }
  if (count($_SESSION) === 0) {
    http_response_code(401);
    die("You need to be logged in to do that!");
  }
  if ($_SESSION['role'] !== 'Vendor' && $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    die("You're not allowed to access this!");
  }

if (!$_REQUEST['item_id']) {
    http_response_code(400);
    die("No item ID was specified!");
}
$item_id = $_REQUEST['item_id'];

$infoQuery = $con->prepare("SELECT name, description, price FROM items WHERE items.item_id = ?");
$infoQuery->bind_param("i", $item_id);
$data = [];
if ($infoQuery->execute()) {
    if ($result = $infoQuery->get_result()) {
        $result_set = $result->fetch_all(MYSQLI_ASSOC);
        $data['info'] = $result_set;
    }
}

$metaQuery = $con->prepare("SELECT id, metadata_name FROM metadata WHERE item_id = ?");
$metaQuery->bind_param("i", $item_id);
if ($metaQuery->execute()) {
    if ($result = $metaQuery->get_result()) {
        $result_set = $result->fetch_all(MYSQLI_ASSOC);
        $data['metadata'] = $result_set;
    }
}

$invQuery = $con->prepare("SELECT inventory.part_id as part_id, inventory.part_name as name, item_inventory_usage.amount as amount FROM item_inventory_usage INNER JOIN inventory ON inventory.part_id = item_inventory_usage.part_id WHERE item_inventory_usage.item_id = ?");
$invQuery->bind_param("i", $item_id);
if ($invQuery->execute()) {
    if ($result = $invQuery->get_result()) {
        $result_set = $result->fetch_all(MYSQLI_ASSOC);
        $data['materials'] = $result_set;
    }
}

echo json_encode($data);
?>
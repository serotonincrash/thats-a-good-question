<?php

require_once('../../config.php');
require_once("../../session_start.php");
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
  http_response_code(405);
  die();
}

if (!isset($_REQUEST['item_id'])) {
  http_response_code(400);
  die("No item ID was specified!");
}
// assert that user is logged in - regen session id
require_once("../../session_handler.php");
$item_id = $_REQUEST['item_id'];

$infoQuery = $con->prepare("SELECT name, description, price FROM items WHERE items.item_id = ?");
$infoQuery->bind_param("i", $item_id);
$data = [];
if ($infoQuery->execute()) {
  if ($result = $infoQuery->get_result()) {
    if ($result->num_rows === 0) {
      http_response_code(404);
      die("This item doesn't exist!");
    }
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

$reviewQuery = $con->prepare("SELECT reviews.review_id, reviews.body, reviews.rating, users.username FROM reviews INNER JOIN (users INNER JOIN orders ON orders.buyer_id = users.user_id) ON reviews.order_id = orders.order_id WHERE orders.item_id = ?");
$reviewQuery->bind_param("i", $item_id);
if ($reviewQuery->execute()) {
  if ($result = $reviewQuery->get_result()) {
    $result_set = $result->fetch_all(MYSQLI_ASSOC);
    $data['reviews'] = $result_set;
  }
}


echo json_encode($data);

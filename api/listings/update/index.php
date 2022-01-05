<?php
require_once('../../config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
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

if (!isset($_REQUEST["item_name"]) || !isset($_REQUEST["description"]) || !isset($_REQUEST["price"])) {
    http_response_code(400);
    die("One or more parameters is invalid or missing!");
}

$item_name = htmlspecialchars($_REQUEST["item_name"]);
$description = htmlspecialchars($_REQUEST["description"]);
$price = htmlspecialchars($_REQUEST["price"]);
$item_id = $_REQUEST['item_id'];

$itemQuery = $con->prepare('SELECT * FROM items WHERE item_id = ?');
$itemQuery->bind_param('i', $item_id);

if ($itemQuery->execute()) {
    if ($result = $itemQuery->get_result()) {
        if ($result->num_rows === 0) {
            http_response_code(404);
            die("This item does not exist!");
        } else {
            // we assert only one result returned - item_id is unique as it is pri key
            $row = $result->fetch_assoc();
            if ($row['vendor_id'] !== $_SESSION['user_id']) {
                http_response_code(403);
                die("You're not allowed to edit this item!");
            }
        }
    }
}
// Send update for main information 
$updateQuery = $con->prepare('UPDATE items SET name=?, description=?, price=? WHERE item_id = ?');
$updateQuery->bind_param('ssis', $item_name, $description, $price, $item_id);
if ($updateQuery->execute()) {
    echo "Updated successfully.";
} else {
    http_response_code(500);
    die("An error occured sending the query to the database.");
}

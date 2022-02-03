<?php
require_once('../../config.php');
require_once("../../session_start.php");
if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
    http_response_code(405);
    die();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}

// assert that user is logged in - regen session id
require_once("../../session_handler.php");

if ($_SESSION['role'] !== 'Vendor' && $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    die("You're not allowed to access this!");
}

if (!isset($_REQUEST["item_name"]) || !isset($_REQUEST["description"]) || !isset($_REQUEST["price"])) {
    http_response_code(400);
    die("One or more parameters is invalid or missing!");
}

$item_name = htmlspecialchars($_REQUEST["item_name"], ENT_QUOTES);
$description = htmlspecialchars($_REQUEST["description"], ENT_QUOTES);
$price = htmlspecialchars($_REQUEST["price"], ENT_QUOTES);
$item_id = $_REQUEST['item_id'];

// Length check
if (strlen($item_name) > 128) {
    http_response_code(400);
    die("Your item name should be less than 128 characters long!");
}

if (strlen($description) > 1024) {
    http_response_code(400);
    die("Your description should be less than 1024 characters long!");
}

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
$updateQuery = $con->prepare('UPDATE items SET name=?, description=?, price=?, created_date = NOW() WHERE item_id = ?');
$updateQuery->bind_param('ssis', $item_name, $description, $price, $item_id);
if ($updateQuery->execute()) {
    echo "Updated successfully.";
} else {
    http_response_code(500);
    die("An error occured sending the query to the database.");
}

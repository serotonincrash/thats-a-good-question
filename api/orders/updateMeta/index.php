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

    http_response_code(400);
    die("Your order ID is missing!");
}

if (strval($_GET['orderID']) !== strval(intval($_GET['orderID']))) {
    http_response_code(400);
    die("Your order ID is not an integer!");
}
$order_id = $_GET['orderID'];
if (!isset($_POST['metadata'])) {
    http_response_code(400);
    die("No metadata was specified to update!");
}

$metadata = json_decode($_REQUEST['metadata'], true);

if (!$metadata) {
    http_response_code(500);
    die("The server encountered an issue parsing your metadata!");
}

foreach ($metadata as $value) {
    if (mb_strlen($value) > 255) {
        http_response_code(400);
        die("Your metadata input is too long!");
    }
}

// Update metadata
// Generate bulk update, copied from the other statement
// I hate php
$types = str_repeat("is", count($metadata)) . "i";
$case_types = str_repeat(" WHEN ? THEN ? ", count($metadata));
$updateMetaQueryStr = "UPDATE order_metadata SET value = (CASE metadata_id ".$case_types;
$updateMetaQueryStr = $updateMetaQueryStr . " END) WHERE order_id = ?";
var_dump($updateMetaQueryStr);
$updateMetaQuery = $con->prepare($updateMetaQueryStr);

$params = [];
foreach ($metadata as $id => $value) {
    $params[] = $id;
    // Escape any single quotes also
    $params[] = htmlspecialchars($value, ENT_QUOTES);
}
var_dump($params);
$params[] = $order_id;

$updateMetaQuery->bind_param($types, ...$params);

if (!$updateMetaQuery->execute()) {
    http_response_code(500);
    die("There was an error communicating with the database.");
}
 echo "Updated successfully.";

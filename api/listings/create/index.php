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

if (!isset($_REQUEST["item_name"]) || !isset($_REQUEST["description"]) || !isset($_REQUEST["materials"]) || !isset($_REQUEST["metadata"]) || !isset($_REQUEST["price"])) {
    http_response_code(400);
    die("One or more parameters is invalid or missing!");
}

$item_name = htmlspecialchars($_REQUEST["item_name"]);
$description = htmlspecialchars($_REQUEST["description"]);
$materials = $_REQUEST["materials"];
$metadata = $_REQUEST["metadata"];
$price = htmlspecialchars($_REQUEST["price"]);

$material_values = array_count_values($materials);

$vendor_id = $_SESSION["user_id"];
$query = $con->prepare("INSERT INTO `items` (`name`, `description`, `price`, `vendor_id`) VALUES(?,?,?,?)");
$query->bind_param('ssii', $item_name, $description, $price, $vendor_id); 

if ($query->execute()) { //execute query

    $item_id = $query->insert_id;

    // Insert metadata

    // do bulk insert4
    $in = str_repeat('(?,?),', count($metadata) - 1) . '(?,?)';
    $query = "INSERT INTO metadata (item_id, metadata_name) VALUES " . $in;
    $metaQuery = $con->prepare($query);
    $types = str_repeat('is', count($metadata));

    $values_arr = [];

    foreach ($metadata as $index => $name) {
        array_push($values_arr, $item_id, $name);
    }
    $metaQuery->bind_param($types, ...$values_arr);

    if (!$metaQuery->execute()) {
        http_response_code(500);
        die("An error occured whilst inserting metadata. Please delete the listing and try again.");
    }

    // Insert inventory requirements

    // do bulk insert
    // I hate php
    $in    = str_repeat('(?,?,?),', count($material_values) - 1) . '(?,?,?)';
    $query = "INSERT INTO item_inventory_usage (item_id, part_id, amount) VALUES " . $in;
    $invQuery = $con->prepare($query);
    $types = str_repeat('iis', count($material_values));

    $values_arr = [];

    foreach ($material_values as $key => $amount) {
        // since $key is just the inv id we don't sanitise it
        array_push($values_arr, $item_id, $key, $amount);
    }

    // Prepare values
    $invQuery->bind_param($types, ...$values_arr);

    if ($invQuery->execute()) {
        echo "Created successfully.";
    } else {
        echo "An error occured.";
    }
    die();
} else {
    echo "Error executing query.";
}


<?php
require_once("../../config.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
  http_response_code(405);
  die();
}

if (count($_SESSION) === 0) {
  http_response_code(401);
  die("You need to be logged in to do that!");
}
if ($_SESSION['role'] !== 'Vendor') {
  http_response_code(403);
  die("You're not allowed to access this!");
}
if(!$_POST['stock'] || (strval($_POST['stock']) !== strval(intval($_POST['stock'])))) {
  // stock not defined or not an int
  http_response_code(400);
  die("Stock is not a number!");
}

$part_id = $_POST['partID'];
$part_name = $_POST['partName'];
$sku = $_POST['sku'];
$stock = $_POST["stock"];

$updateStatement = $con->prepare("UPDATE tagq.inventory SET part_name = ?, sku = ?, stock = ? WHERE part_id = ?");

$updateStatement->bind_param('ssii', $part_name, $sku, $stock, $part_id);
$updateStatement->execute();

if ($err = $updateStatement->errno) {
  http_response_code(500);
  die("An error occured whilst sending the query to the database.");
} else {
  echo "Inventory updated successfully.";
}
?>

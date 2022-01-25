
<?php
require_once("../../config.php");

require_once("../../session_start.php");

if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
  http_response_code(405);
  die();
}

if (count($_SESSION) === 0) {
  http_response_code(401);
  die("You need to be logged in to do that!");
}
// assert that user is logged in - regen session id
require_once("../../session_handler.php");
if ($_SESSION['role'] !== 'Vendor' && $_SESSION['role'] !== 'Admin') {
  http_response_code(403);
  die("You're not allowed to access this!");
}

if (!isset($_POST['partID']) || !isset($_POST['partName']) || !isset($_POST['sku']) || !isset($_POST['stock'])) {
  http_response_code(400);
  die("One of the fields is missing!");
}

if ((strval($_POST['stock']) !== strval(intval($_POST['stock'])))) {
  // stock not defined or not an int
  http_response_code(400);
  die("Stock is not a number!");
}

$part_id = htmlspecialchars($_POST['partID'], ENT_QUOTES);
$part_name = htmlspecialchars($_POST['partName'], ENT_QUOTES);
$sku = htmlspecialchars($_POST['sku'], ENT_QUOTES);
$stock = htmlspecialchars($_POST["stock"], ENT_QUOTES);

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

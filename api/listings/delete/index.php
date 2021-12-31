<?php
require_once('../../config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] !== "DELETE") {
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

$query= $con->prepare("DELETE FROM `items` WHERE item_id = ?");
$item_id = $_REQUEST["item_id"];

$query->bind_param('i', $item_id); //bind the parameters

if ($query->execute()){
    echo "Item deleted successfully.";
    die();
} else {
    http_response_code(500);
    die("Error executing query.");
}
?>

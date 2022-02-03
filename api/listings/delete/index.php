<?php
require_once('../../config.php');
require_once("../../session_start.php");
if ($_SERVER['REQUEST_METHOD'] !== "DELETE") {
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

$delQuery= $con->prepare("DELETE FROM `items` WHERE item_id = ?");
$item_id = $_REQUEST["item_id"];

$delQuery->bind_param('i', $item_id); //bind the parameters

if ($delQuery->execute()){
    echo "Item deleted successfully.";
    die();
} else {
    http_response_code(500);
    die("Error executing query.");
}

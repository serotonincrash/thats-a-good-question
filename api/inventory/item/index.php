<?php
require_once("../../config.php");

require_once("../../session_start.php");

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
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
if(!isset($_GET['partID'])) {
    // partID not an int
    http_response_code(400);
    die("Your order ID is missing!");
}

if (strval($_GET['partID']) !== strval(intval($_GET['partID']))) {
    // partID not an int
    http_response_code(400);
    die("Your order ID is not an integer!");
}

$partID = $_GET['partID'];
$itemStatement = $con->prepare("SELECT * FROM inventory WHERE part_id = ?");
$itemStatement->bind_param('i', $partID);
$itemStatement->execute();

if ($result = $itemStatement->get_result()) {
    if ($result->num_rows === 0) {
        http_response_code(500);
        die("That item ID could not be found!");
    } else if ($result->num_rows > 1) {
        // This should never happen.
        http_response_code(500);
        die("Multiple of this item exists in the database!");
    }

    $item = $result->fetch_array(MYSQLI_ASSOC);
    echo json_encode($item);
}
?>


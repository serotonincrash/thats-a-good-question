<?php

require_once("../config.php");
session_start();
if (count($_SESSION) === 0) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
// assert that user is logged in - regen session id
require_once("../session.php");
if ($_SESSION['role'] !== 'Vendor' && $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    die("You're not allowed to access this!");
}

$listingQuery = $con->prepare("SELECT * FROM items WHERE vendor_id = ?");
$listingQuery->bind_param("i", $_SESSION["user_id"]);

if ($listingQuery->execute()) {
    $result = $listingQuery->get_result();
    $data = array();
    if ($result->num_rows > 0) {
        while ($row =  $result->fetch_array(MYSQLI_ASSOC)) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
}
?>
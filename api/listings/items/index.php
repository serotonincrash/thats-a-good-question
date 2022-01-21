<?php
require_once('../../config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    http_response_code(405);
    die();
}
// assert that user is logged in - regen session id
require_once("../../session.php");
$infoQuery = $con->prepare("SELECT item_id, name, description, price FROM items");
$data = [];
if ($infoQuery->execute()) {
    if ($result = $infoQuery->get_result()) {
        $result_set = $result->fetch_all(MYSQLI_ASSOC);
        $data = $result_set;
    }
}
echo json_encode($data);
?>
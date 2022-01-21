<?php

require_once('../../config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
    http_response_code(405);
    die();
}
if (count($_SESSION) === 0) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
// assert that user is logged in - regen session id
require_once("../../session.php");

// No one except users can update reviews
if ($_SESSION['role'] !== 'User') {
    http_response_code(403);
    die("You're not allowed to access this!");
}

if (!isset($_GET['reviewID'])) {
    http_response_code(400);
    die("Your review ID is missing!");
}
$review_id = $_GET['reviewID'];

// Check if review ID is an integer

if (strval($review_id) !== strval(intval($review_id))) {
    // partID not an int
    http_response_code(400);
    die("Your order ID is not an integer!");
}

// Check if review belongs to the user


?>

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
// assert that user is logged in - regen session id
require_once("../../session.php");

// No one except users can create reviews
if ($_SESSION['role'] !== 'User') {
    http_response_code(403);
    die("You're not allowed to access this!");
}

// Check if user already has a review

// Create review

?>



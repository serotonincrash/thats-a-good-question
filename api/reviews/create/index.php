<?php
require_once('../../config.php');
require_once("../../session_start.php");
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(405);
    die();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
// assert that user is logged in - regen session id
require_once("../../session_handler.php");
if (!isset($_GET['orderID']) || !isset($_POST['body']) || !isset($_POST['rating'])) {
    http_response_code(400);
    die("One or more parameters is missing!");
}
$order_id = $_GET["orderID"];
$rating = $_POST['rating'];
$body =  htmlspecialchars($_POST['body']);
if (strval($order_id) !== strval(intval($order_id))) {
    http_response_code(400);
    die("Order ID not an integer!");
}
if (strval($rating) !== strval(intval($rating))) {
    http_response_code(400);
    die("Order ID not an integer!");
}

if ($rating < 0 || $rating > 5) {
    http_response_code(400);
    die("Rating must be between 1 and 5!");
}
if (mb_strlen($body) > 256) {
    http_response_code(400);
    die("Review body too long!");
}

// No one except users can create reviews
if ($_SESSION['role'] !== 'User') {
    http_response_code(403);
    die("You're not allowed to access this!");
}

// Check if user already has a review
$checkQuery = $con->prepare("SELECT * FROM reviews INNER JOIN orders on reviews.order_id = orders.order_id WHERE reviews.order_id = ? AND orders.buyer_id = ?");
$checkQuery->bind_param("ii", $order_id, $_SESSION['user_id']);
if ($checkQuery->execute()) {
    if ($result = $checkQuery->get_result()) {
        if ($result->num_rows > 0) {
            // Technically only one review should exist but just check above 0
            http_response_code(500);
            die("You've already reviewed this item!");
        }
    }
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}

// Create review
$createQuery = $con->prepare("INSERT INTO reviews (order_id, body, rating) VALUES (?,?,?)");
$createQuery->bind_param("isi", $order_id, $body, $rating);
if ($createQuery->execute()) {
    echo "Review created successfully.";
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}
?>



<?php
mysqli_report(MYSQLI_REPORT_ALL);
require_once('../../config.php');
require_once("../../session_start.php");
if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
    http_response_code(405);
    die();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
// assert that user is logged in - regen session id
require_once("../../session_handler.php");

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
    die("Your review ID is not an integer!");
}

// Get new review body and rating
if (!isset($_POST['body']) || !isset($_POST['rating'])) {
    http_response_code(400);
    die("Order ID not set!");
}
$rating = $_POST['rating'];
$body =  $_POST['body'];
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

// Check if review belongs to the user
$checkQuery = $con->prepare("SELECT orders.buyer_id as user_id FROM reviews INNER JOIN (orders INNER JOIN users on orders.buyer_id = users.user_id) on reviews.order_id = orders.order_id WHERE reviews.review_id = ?");
$checkQuery->bind_param("i", $review_id);
if ($checkQuery->execute()) {
    if ($result = $checkQuery->get_result()) {
        if ($result->num_rows === 0) {
            http_response_code(404);
            die("No such review exists!");
        }
        // When SELECTING by unique review id there is only one result
        $review = $result->fetch_assoc();
        if ($review['user_id'] !== $_SESSION['user_id']) {
            http_response_code(403);
            die("You can't update this review!");
        }
    }
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}


// Update the review
$updateQuery = $con->prepare("UPDATE reviews SET body = ?, rating = ?, created_date = NOW() WHERE review_id = ?");
$updateQuery->bind_param("sii", $body, $rating, $review_id);
if ($updateQuery->execute()) {
    echo "Review updated successfully.";
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}
?>

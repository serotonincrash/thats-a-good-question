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

// No one except users can delete reviews
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
$checkQuery = $con->prepare("SELECT orders.buyer_id as user_id FROM reviews INNER JOIN orders on reviews.order_id = orders.order_id WHERE reviews.review_id = ?");
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
            die("You can't delete this review!");
        }
    }
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}

// Delete the review
$deleteQuery = $con->prepare("DELETE FROM reviews WHERE review_id = ?");
$deleteQuery->bind_param("i", $review_id);
if ($deleteQuery->execute()) {
    echo "Review deleted successfully.";
} else {
    http_response_code(500);
    die("An error occured whilst querying the database.");
}

?>

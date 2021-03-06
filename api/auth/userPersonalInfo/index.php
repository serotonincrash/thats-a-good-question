<?php
    require_once("../../config.php");

    if ($_SERVER['REQUEST_METHOD'] !== "GET") {
        http_response_code(405);
        die();
    }
    require_once("../../session_start.php");
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        die("You need to be logged in to do that!");
    }

    // assert that user is logged in - regen session id
    require_once("../../session_handler.php");

    if (!isset($_SESSION['user_id'])) {
        http_response_code(500);
        die("An error occured. Please try logging out and logging back in.");
    }
    
    $userID = $_SESSION['user_id'];
    
    $userStatement = mysqli_prepare($con, "SELECT * FROM tagq.personal_info WHERE user_id = ?");

    $userStatement->bind_param("i", $userID);

    $userStatement->execute();

    $data = array();

    if ($result = $userStatement->get_result()) {
        if ($result->num_rows === 0) {
            http_response_code(401);
            die("No such data exists.");
        } else if ($result->num_rows === 1) {
            $user = $result->fetch_array(MYSQLI_ASSOC);
            echo json_encode($user);
        } else {
            http_response_code(500);
            die("An error occured on the web server.");
        }
    }

?>
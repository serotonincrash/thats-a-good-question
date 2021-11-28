<?php
    require_once("../../config.php");

    if ($_SERVER['REQUEST_METHOD'] !== "GET") {
        http_response_code(405);
        die();
    }
    session_start();
    if (count($_SESSION) === 0) {
        http_response_code(401);
        die("You're not logged in!");
    }

    $userID = $_SESSION['user_id'];
    
    $userStatement = mysqli_prepare($con, "SELECT user_id, email, username FROM tagq.users WHERE user_id = ?");

    $userStatement->bind_param("i", $userID);

    $userStatement->execute();

    $data = array();

    if ($result = $userStatement->get_result()) {
        if ($result->num_rows === 0) {
            http_response_code(401);
            die("Your username or password is incorrect.");
        } else if ($result->num_rows === 1) {
            $user = $result->fetch_array(MYSQLI_ASSOC);
            echo json_encode($user);
        } else {
            http_response_code(500);
            die("An error occured on the web server.");
        }
    }

?>

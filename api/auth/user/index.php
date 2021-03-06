<?php

    require_once("../../session_start.php");
    require('../../includes/json.php');

    if ($_SERVER['REQUEST_METHOD'] !== "GET") {
        http_response_code(405);
        die();
    }

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        die("You need to be logged in to do that!");
    }

    // assert that user is logged in - regen session id
    require_once("../../session_handler.php");
    
    if ($_SESSION['username']) {
        // returns session data if there is any
        $json = new json();
        $json->session = $_SESSION;
        $json->send();
    } 
?>
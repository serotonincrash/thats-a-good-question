<?php

    session_start();
    require('../../includes/json.php');

    if ($_SERVER['REQUEST_METHOD'] !== "GET") {
        http_response_code(405);
        die();
    }

    if (count($_SESSION) === 0) {
        http_response_code(401);
        die("You're not logged in!");
    }

    if ($_SESSION['username']) {
        // returns session data if there is any
        $json = new json();
        $json->session = $_SESSION;
        $json->send();
    } 
?>
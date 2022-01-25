<?php

    require_once("../../session_start.php");

    if (isset($_SESSION) && count($_SESSION) > 0) {
        session_destroy();
        die("Logged out successfully.");
    } else {
        http_response_code(401);
        die("You're not logged in!");
    }

?>
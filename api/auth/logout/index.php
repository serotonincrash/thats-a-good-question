<?php

    session_start();

    if ($_SESSION && count($_SESSION) > 0) {
        session_destroy();
        die("Logged out successfully.");
    } else {
        http_response_code(401);
        die("You're not logged in!");
    }

?>
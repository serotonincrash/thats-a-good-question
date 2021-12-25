<?php

require_once("../config.php");
session_start();
if (count($_SESSION) === 0) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
if ($_SESSION['role'] !== 'Vendor') {
    http_response_code(403);
    die("You're not allowed to access this!");
}


?>
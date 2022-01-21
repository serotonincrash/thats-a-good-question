<?php
require_once('../../config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    http_response_code(405);
    die();
}

// Get reviews associated with item
<?php
$TIMEOUT = 30;
if (isset($_SESSION['timeout'])) {
    if ($TIMEOUT < time() - $_SESSION['timeout']) {
        session_destroy();
        http_response_code(401);
        die("Session timed out. Please log in again.");
    }
}
$_SESSION['timeout'] = time();
session_regenerate_id();
?>
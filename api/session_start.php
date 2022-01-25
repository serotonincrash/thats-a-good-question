<?php

// When starting the session, make sure these cookie settings are 
$secure = false; // For testing over HTTP
$httponly = true; // prevent JavaScript access to session cookie
$samesite = 'lax';
$maxlifetime = 86400;

if (PHP_VERSION_ID < 70300) {
    session_set_cookie_params($maxlifetime, '/; samesite='.$samesite, $_SERVER['HTTP_HOST'], $secure, $httponly);
} else {
    session_set_cookie_params([
        'lifetime' => $maxlifetime,
        'path' => '/',
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => $samesite
    ]);
}

session_start();
?>
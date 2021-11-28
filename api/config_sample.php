<?php

$con = new mysqli("localhost", "root", "", "tagq");

if ($con->connect_errno) {
    echo "DB connection failed:" . $con->connect_error;
}

?>
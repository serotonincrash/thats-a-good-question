<?php
    require_once("../../config.php");

    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        http_response_code(405);
        die();
    }
    
    if (!$_SESSION['user_id']) {
        http_response_code(401);
        die("You're not logged in!");
    }
    $id = (int) $_SESSION['user_id'];
    $statement = mysqli_prepare($con, "DELETE FROM tagq.users WHERE user_id = ?");
    $statement->bind_param("i", $id);

    $statement->execute();
    if ($statement->errno) {
        http_response_code(500);
        die("There was an error when executing the database query.");
    } else {
        echo "Account deleted successfully.";
        session_destroy();
    }
?>
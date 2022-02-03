<?php
    require_once("../../config.php");

    require_once("../../session_start.php");

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        die("You need to be logged in to do that!");
    }

    // assert that user is logged in - regen session id
    require_once("../../session_handler.php");
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        http_response_code(405);
        die();
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
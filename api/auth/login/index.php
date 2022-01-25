<?php
    require_once("../../config.php");
    require_once("../../session_start.php");

    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        http_response_code(405);
        die();
    }
    
    if (count($_SESSION) > 0) {
        http_response_code(403);
        die("You're already logged in!");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $userStatement = mysqli_prepare($con, "SELECT user_id, email, username, password, role FROM tagq.users WHERE username = ? OR email = ?");

    $userStatement->bind_param("ss", $username, $username);

    $userStatement->execute();

    if ($result = $userStatement->get_result()) {
        if ($result->num_rows === 0) {
            http_response_code(401);
            echo "Your username or password is incorrect.";
        } else if ($result->num_rows === 1) {
            $user = $result->fetch_array(MYSQLI_ASSOC);
            if (password_verify($password, $user['password'])) {
                echo "Logged in!";
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['timeout'] = time();
            } else {
                http_response_code(401);
                echo "Your username/email or password is incorrect.";
            }
        } else {
            http_response_code(500);
            echo "An error occured on the web server.";
        }
    }
    
?>
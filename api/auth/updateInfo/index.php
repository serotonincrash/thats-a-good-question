<?php
    require_once("../../config.php");
    if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
        var_dump($_SERVER['REQUEST_METHOD']);
        http_response_code(405);
        die();
    }

    require_once("../../session_start.php");
    if (!isset($_SESSION['user_id']) || !isset($_SESSION) || !$_SESSION['user_id']) {
        http_response_code(401);
        die("You're not logged in!");
    }


    // assert that user is logged in - regen session id
    require_once("../../session_handler.php");
    
    $user_id = $_SESSION['user_id'];
    
    // Null check
    if (!isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["newPassword"]) || !isset($_POST["confirmPassword"])) {
        http_response_code(400);
        die("One of the the fields is blank!");
    }

    function remove_specialchars($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }

    // apfd extension parses body into $_POST
    $username = htmlspecialchars(remove_specialchars($_POST["username"]), ENT_QUOTES);
    $password = $_POST["password"];
    $newPassword = $_POST["newPassword"];
    $passwordConfirm = $_POST["confirmPassword"];

    // Password confirm check
    if ($newPassword !== $passwordConfirm) {
        http_response_code(400);
        die("The passwords do not match!");
    }

    // Verify user before updating info
    $verifyStatement = mysqli_prepare($con, "SELECT password FROM tagq.users WHERE user_id = ?");

    $verifyStatement->bind_param("i",$user_id);

    $verifyStatement->execute();

    if ($result = $verifyStatement->get_result()) {
        if ($result->num_rows === 0) {
            // this should also never occur. user id existing in session implies that the user exists
            http_response_code(500);
            die("An error occured on the web server.");
        } else if ($result->num_rows === 1) {
            $user = $result->fetch_array(MYSQLI_ASSOC);
            // check if password is correct
            if (!password_verify($password, $user['password'])) {
                http_response_code(401);
                die("Your password is incorrect!");
            }
        } else {
            // this should NEVER occur. username/email/userid is unique
            http_response_code(500);
            die("An error occured on the web server.");
        }
    }

    // Password security check
    $regex = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/";
    if (!preg_match($regex, $newPassword)) {
        http_response_code(400);
        die("Your password isn't secure enough! It should be more than 8 characters long and contain letters, and at least one number and special character.");
    }

    // Length bounds checking 
    if (mb_strlen($username) > 32) {
        http_response_code(400);
        die("Your username is too long!");
    }
    
    if (mb_strlen($newPassword) > 72) {
        http_response_code(400);
        die("Your password should be less than 72 characters long!");
    }
    // Hash password using bcrypt 
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    // Update user statement
    $userStatement = mysqli_prepare($con, "UPDATE tagq.users SET username = ?, password = ? WHERE user_id = ?");
    $userStatement->bind_param("ssi", $username, $hashedPassword, $user_id);
    $userStatement->execute();

    if ($err = $userStatement->errno) {
        http_response_code(500);
        if ($err === 1062) {
            die("That username is already taken!");
        }
        die("An error occured whilst sending the query to the database.");
    } else {
        echo "User updated successfully.";
        // Log out the user
        session_destroy();
    }
?>
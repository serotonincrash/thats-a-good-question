<?php
    require_once("../../config.php");

    if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
        http_response_code(405);
        die();
    }
    require_once("../../session_start.php");

    if (!isset($_SESSION['user_id']) || !$_SESSION || !isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
        http_response_code(401);
        die("You're not logged in!");
    }

    // assert that user is logged in - regen session id
    require_once("../../session_handler.php");
    
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    if ($role !== "User") {
        http_response_code(403);
        die("You're not allowed to do this!");
    }

    // Null check
    if (!isset($_POST["firstName"]) || !isset($_POST["lastName"]) || !isset($_POST["phoneNumber"]) || !isset($_POST["postalCode"]) || !isset($_POST['password'])) {
        http_response_code(400);
        die("One of the the fields is blank!");
    }

    function remove_specialchars($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }

    // Signup is for user, get personal information
    $password = $_POST['password'];
    $firstName = htmlspecialchars(remove_specialchars($_POST["firstName"]), ENT_QUOTES);
    $lastName = htmlspecialchars(remove_specialchars($_POST["lastName"]), ENT_QUOTES);
    $address = htmlspecialchars($_POST["address"], ENT_QUOTES);
    $phone = htmlspecialchars($_POST["phoneNumber"], ENT_QUOTES);
    $postal = htmlspecialchars($_POST["postalCode"], ENT_QUOTES);
    
    // Length checks
    if (mb_strlen($firstName) > 32) {
        http_response_code(400);
        die("Your first name should be less than 32 characters long!");
    }
    if (mb_strlen($lastName) > 32) {
        http_response_code(400);
        die("Your last name should be less than 32 characters long!");
    }
    if (mb_strlen($address) > 200) {
        http_response_code(400);
        die("Your address should be less than 200 characters long!");
    }
    if (mb_strlen($postal) > 16) {
        http_response_code(400);
        die("Your first name should be less than 16 characters long!");
    }
    if (mb_strlen($phone) > 32) {
        http_response_code(400);
        die("Your phone number should be less than 32 characters long!");
    }
    
    // Verify user before updating info
    $verifyStatement = mysqli_prepare($con, "SELECT password FROM tagq.users WHERE user_id = ?");

    $verifyStatement->bind_param("i",$user_id);

    $verifyStatement->execute();

    if ($result = $verifyStatement->get_result()) {
        if ($result->num_rows === 0) {
            // this should also never occur. user id existing in session implies that the user exists
            http_response_code(500);
            echo "An error occured on the web server.";
        } else if ($result->num_rows === 1) {
            $user = $result->fetch_array(MYSQLI_ASSOC);
            
            // check if password is correct
            if (!password_verify($password, $user['password'])) {
                http_response_code(401);
                echo "Your password is incorrect!";
                die();
            }
        } else {
            // this should NEVER occur. username/email/userid is unique
            http_response_code(500);
            echo "An error occured on the web server.";
        }
    }

    // Update user statement
    $userStatement = mysqli_prepare($con, "UPDATE tagq.personal_info SET first_name = ?, last_name = ?, address = ?, postal_code = ?, phone_number = ? WHERE user_id = ?");
    $userStatement->bind_param("sssssi", $firstName, $lastName, $address, $postal, $phone, $user_id);
    $userStatement->execute();

    if ($err = $userStatement->errno) {
        http_response_code(500);
        
        die("An error occured whilst sending the query to the database.");
    } else {
        echo "User updated successfully.";
    }

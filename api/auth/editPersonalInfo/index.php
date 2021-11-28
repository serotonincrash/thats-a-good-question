<?php
    require_once("../../config.php");

    if ($_SERVER['REQUEST_METHOD'] !== "PUT") {
        http_response_code(405);
        die();
    }
    session_start();

    if (count($_SESSION) === 0 || !$_SESSION || !$_SESSION['role'] || !$_SESSION['user_id']) {
        http_response_code(401);
        die("You're not logged in!");
    }

    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    if ($role !== "User") {
        http_response_code(403);
        die("You're not allowed to do this!");
    }

    // Null check
    if (!$_POST["firstName"] || !$_POST["lastName"] || !$_POST["phoneNumber"] || !$_POST["postalCode"] || !$_POST['password']) {
        http_response_code(400);
        die("One of the the fields is blank!");
    }

    // Signup is for user, get personal information
    $firstName = $_POST["firstName"];
    $password = $_POST['password'];
    $lastName = $_POST["lastName"];
    $address = $_POST["address"];
    $phone = $_POST["phoneNumber"];
    $postal = $_POST["postalCode"];
    
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
?>
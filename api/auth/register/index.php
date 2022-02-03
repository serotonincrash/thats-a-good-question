<?php
    require_once("../../config.php");

    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        http_response_code(405);
        die();
    }
    require_once("../../session_start.php");
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        die("You're already logged in!");
    }

    // Null check
    if (!isset($_POST["role"]) || !isset($_POST["email"]) || !isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["passwordConfirm"])) {
        http_response_code(400);
        die("One of the the fields is blank!");
    }

    $email = htmlspecialchars($_POST["email"], ENT_QUOTES);
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
    $password = $_POST["password"];
    $passwordConfirm = $_POST["passwordConfirm"];
    $role = $_POST["role"];

    

    // Password confirm check
    if ($password !== $passwordConfirm) {
        http_response_code(400);
        die("The passwords do not match!");
    }


    // Password security check
    $regex = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/";
    if (!preg_match($regex, $password)) {
        http_response_code(400);
        die("Your password isn't secure enough!");
    }

    // email verification
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        die("Your email is not valid!");
    }
    
    // Length checks
    if (strlen($email) > 128) {
        http_response_code(400);
        die("Your email is too long! It should be less than 128 characters.");
    }
    
    if (strlen($username) > 32) {
        http_response_code(400);
        die("Your username is too long!");
    }
    
    if (strlen($password) > 72) {
        http_response_code(400);
        die("Your password should be less than 72 characters long!");
    }

    // Role check. No role other than user and vendor should be allowed
    if (!($role === "User" || $role === "Vendor")) {
        http_response_code(400);
        die("Invalid role!");
    }

    if ($role === "User") {
        // Null check
        if (!$_POST["postalCode"] || !$_POST["phoneNumber"] || !$_POST["address"] || !$_POST["lastName"] || !$_POST["firstName"]) {
            http_response_code(400);
            die("One of the the fields is blank!");
        }

        // Signup is for user, store personal information
        $firstName = htmlspecialchars($_POST["firstName"], ENT_QUOTES);
        $lastName = htmlspecialchars($_POST["lastName"], ENT_QUOTES);
        $address = htmlspecialchars($_POST["address"], ENT_QUOTES);
        $phone = htmlspecialchars($_POST["phoneNumber"], ENT_QUOTES);
        $postal = htmlspecialchars($_POST["postalCode"], ENT_QUOTES);

        // Length checks
        if (strlen($firstName) > 32) {
            http_response_code(400);
            die("Your first name should be less than 32 characters long!");
        }
        if (strlen($lastName) > 32) {
            http_response_code(400);
            die("Your last name should be less than 32 characters long!");
        }
        if (strlen($address) > 200) {
            http_response_code(400);
            die("Your address should be less than 200 characters long!");
        }
        if (strlen($postal) > 16) {
            http_response_code(400);
            die("Your first name should be less than 16 characters long!");
        }
        if (strlen($phone) > 32) {
            http_response_code(400);
            die("Your phone number should be less than 32 characters long!");
        }
    }

    // Hash password using bcrypt 
    $password = password_hash($password, PASSWORD_BCRYPT);
    // Create user statement
    $userStatement = mysqli_prepare($con, "INSERT INTO tagq.users(email, username, password, role) VALUES (?,?,?,?)");
    $userStatement->bind_param("ssss", $email, $username, $password, $role);

    $userStatement->execute();
    if ($err = $userStatement->errno) {
        http_response_code(500);
        if ($err === 1062) {
            die("This user already exists!");
        }
        die("An error occured whilst sending the query to the database.");
    }
    $userID = $userStatement->insert_id;
    if ($role === "User") {
        
        $infoStatement = mysqli_prepare($con, "INSERT INTO tagq.personal_info(user_id, first_name, last_name, address, postal_code, phone_number) VALUES (?,?,?,?,?,?)");
        $infoStatement->bind_param("isssss", $userID, $firstName, $lastName, $address, $postal, $phone);
        $infoStatement->execute();

        if ($infoStatement->errno) {
            http_response_code(500);
            die("An error occured whilst sending the query to the database.");
        }
        echo "User created successfully.";
        // Log the user in
        require_once("../../session_start.php");
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userID;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        $_SESSION['timeout'] = time();
    } else {
        echo "Vendor account created succesfully.";
        // Log the user in
        require_once("../../session_start.php");
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userID;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        $_SESSION['timeout'] = time();
    }
?>
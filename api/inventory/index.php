<?php
require_once("../config.php");
session_start();
if (count($_SESSION) === 0) {
    http_response_code(401);
    die("You need to be logged in to do that!");
}
if ($_SESSION['role'] !== 'Vendor' && $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    die("You're not allowed to access this!");
}

$inventoryStatement = $con->prepare("SELECT * FROM inventory"); //SQL statement to read the information
$inventoryStatement->execute(); //execute
if ($result = $inventoryStatement->get_result()) {
    $data = array();
    if ($result->num_rows > 0) {
        while ($row =  $result->fetch_array(MYSQLI_ASSOC)) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
}

?>

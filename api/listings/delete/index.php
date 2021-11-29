<html>
<body>

<?php
require_once('../../config.php');

$query= $con->prepare("DELETE FROM `items` WHERE item_id = ?");
$item_id = $_REQUEST["item_id"];

$query->bind_param('i', $item_id); //bind the parameters

if ($query->execute()){
    header("Location: /");
    die();
}else{
    echo "Error executing query.";
}
?>

</body>
</html>
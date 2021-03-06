<html>

<body>
  <?php
  require_once("../../config.php");

  require_once("../../session_start.php");
  if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(405);
    die();
  }
  if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("You need to be logged in to do that!");
  }

  // assert that user is logged in - regen session id
  require_once("../../session_handler.php");
  if ($_SESSION['role'] !== 'Vendor' && $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    die("You're not allowed to access this!");
  }

  // Validate data

  if (!isset($_POST) || !isset($_POST['partName']) || !isset($_POST['sku']) || !isset($_POST['stock'])) {
    http_response_code(400);
    die("One or more parameters are not present!");
  }

  $part_name = htmlspecialchars($_POST['partName'], ENT_QUOTES);
  $sku = htmlspecialchars($_POST['sku'], ENT_QUOTES);
  $stock = htmlspecialchars($_POST['stock'], ENT_QUOTES);

  if (strval($stock) !== strval(intval($stock))) {
    // stock not defined or not an int
    http_response_code(400);
    die("Stock is not a number!");
  }
  if (mb_strlen($sku) > 64) {
    http_response_code(400);
    die("Your SKU is too long!");
  }

  if (mb_strlen($part_name) > 64) {
    http_response_code(400);
    die("Your part name is too long!");
  }
  
  $createStatement = $con->prepare("INSERT INTO tagq.inventory(part_name, sku, stock) VALUES (?,?,?)");
  $createStatement->bind_param("ssi", $part_name, $sku, $stock);
  $createStatement->execute();
  if ($err = $createStatement->errno) {
    http_response_code(500);
    if ($err == 1062) {
      die("An item with that name already exists!");
    }
    die("An error occured whilst sending the query to the database.");
  } else {
    echo "Item created successfully!";
  }
  ?>
</body>

</html>
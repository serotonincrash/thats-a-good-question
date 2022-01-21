<html>

<body>
  <?php
  require_once("../../config.php");

  session_start();
  if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(405);
    die();
  }
  if (count($_SESSION) === 0) {
    http_response_code(401);
    die("You need to be logged in to do that!");
  }
  if (count($_SESSION) > 0) {
    http_response_code(403);
    die("You're already logged in!");
  }
  // assert that user is logged in - regen session id
  require_once("../../session.php");
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
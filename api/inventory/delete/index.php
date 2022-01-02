<html>

<body>
  <?php
  require_once("../../config.php");

  session_start();
  if ($_SERVER['REQUEST_METHOD'] !== "DELETE") {
    http_response_code(405);
    die();
  }
  if (count($_SESSION) === 0) {
    http_response_code(401);
    die("You need to be logged in to do that!");
  }
  if ($_SESSION['role'] !== 'Admin') {
    // No one can delete except Admin
    http_response_code(403);
    die("You're not allowed to do this!");
  }
  if (!isset($_GET['part_id'])) {
    http_response_code(400);
    die("The item ID is missing!");
  }

  if (strval($_GET['part_id']) !== strval(intval($_GET['part_id']))) {
    // part_id not an int
    http_response_code(400);
    die("Your item ID is not an integer!");
  }

    $id = $_GET['part_id'];
    $deleteStatement = $con->prepare("DELETE FROM tagq.inventory WHERE part_id = ?");
    $deleteStatement->bind_param("i", $id);
    $deleteStatement->execute();
    if ($err = $deleteStatement->errno) {
      http_response_code(500);
        
      die("An error occured whilst sending the query to the database.");
    } else {
      echo "Item deleted successfully.";
    }

  ?>
</body>

</html>
<html>
<body>
<?php
$con = mysqli_connect("localhost","root","","tagq");
if (!$con){
die('Could not connect: ' . mysqli_connect_errno()); 
}
if(isset($_GET['part_id'])){
  $id = $_GET['part_id'];
  $sql = "DELETE FROM tagq.inventory WHERE part_id = $id";

  if($con->query($sql) == true){
    header("Location: /app/inventory/");
    echo "Data deleted";
  }else{
    echo "something went wrong";
  }
}else{
  die('id not provided');
}
?>
</body>
</html>
<html>
<body>
<?php
$con = mysqli_connect("localhost","root","","tagq"); //connect to database
if (!$con){
die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}

$part_name = $_REQUEST['part_name'];
$sku = $_REQUEST['sku'];
$stock = $_REQUEST['stock'];

$sql = "INSERT INTO tagq.inventory(part_name,sku,stock) VALUES ('$part_name', '$sku', '$stock')";
if(mysqli_query($con, $sql)){
  echo "<h3>Data stored in database successfully</h3>";
} 
else {
  echo "ERROR!" . mysqli_error($con);
}
?>
</body>
</html>
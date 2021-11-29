<html>
<body>
<?php
$con = mysqli_connect("localhost","root","","tagq");
if (!$con){
die('Could not connect: ' . mysqli_connect_errno()); 
}
if(isset($_POST['part_id']) && isset($_POST['editForm'])){
  $part_id = $_POST['part_id'];
  $part_name = $_POST['part_name'];
  $sku = $_POST['sku'];
  $stock = $_POST["stock"];

  $sql = "UPDATE tagq.inventory SET
      part_name = '".$part_name."',
      sku = ".$sku.",
      stock = ".$stock.
      " WHERE part_id = ".$part_id;
  if($con->query($sql) === TRUE){
    echo "from edit page";
  }else{
    echo "failed";
  }
}else{
  echo "invalid";
}
?>
</body>
</html>
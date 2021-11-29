<?php
include 'connect.php';

$review_id=$_GET['updateid'];//for update button in display.php


//this is to view specific review_id in update.php so that users don't forget what they write 
$sql="SELECT * from `reviews` WHERE review_id='$review_id'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($result);
$body=$row['body'];
$rating=$row['rating'];
?>
<?php
require_once('../../config.php');

$review_id=$_GET['review_id'];//for update button in display.php


//this is to view specific review_id in update.php so that users don't forget what they write 
$sql="SELECT * from `reviews` WHERE review_id='$review_id'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($result);
$body=$row['body'];
$rating=$row['rating'];


if(isset($_POST['submit'])){
    $body=$_POST['body'];
    $rating=$_POST['rating'];

    
    $sql="UPDATE `reviews` set body='$body', rating='$rating' WHERE review_id='$review_id'"; 
    //WHERE query to prevent duplicate entry for primary key - "review_id"   

    $result=mysqli_query($con,$sql);
    if($result){
        echo "Data updated successfully";
        header('location:/app/reviews/'); //after create will redirect
    }else{
        die(mysqli_error($con));
    }
}

?>

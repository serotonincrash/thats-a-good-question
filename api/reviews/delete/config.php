<?php

require_once('../../config.php');
if(isset($_GET['deleteid'])){
    $review_id=$_GET['deleteid'];

    $sql="DELETE from `reviews` where review_id=$review_id";
    $result=mysqli_query($con,$sql);
    if($result){
        echo "Review has been deleted";
        header('location:display.php'); //after delete will redirect to display.php
    } else{
        die(mysqli_error($con));
    }
}


?>

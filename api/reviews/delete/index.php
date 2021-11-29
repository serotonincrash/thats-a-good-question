<?php

require_once('../../config.php');
if(isset($_GET['delete_id'])){
    $review_id=$_GET['delete_id'];

    $sql="DELETE from `reviews` where review_id=$review_id";
    $result=mysqli_query($con,$sql);
    if($result){
        echo "Review has been deleted";
        header('location:/app/reviews/'); //after delete will redirect 
    } else{
        die(mysqli_error($con));
    }
}


?>

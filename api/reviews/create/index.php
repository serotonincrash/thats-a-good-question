<?php
require_once('../../config.php');

if(isset($_POST['submit'])){

    $order_id=$_POST['order_id'];
    $body=$_POST['body'];
    $rating=$_POST['rating'];

    //$sql="INSERT into `reviews` (order_id,body,rating) values ('','$body','$rating')"; //order_id set as null 
    $sql="INSERT into `reviews` (order_id,body,rating) values ('$order_id','$body','$rating')";
    $result=mysqli_query($con,$sql);
    if($result){
        echo "Data inserted succesfully";
        header('location:/app/reviews/'); //after delete will redirect reviews
    }else{
        die(mysqli_error($con));
    }
}


?>



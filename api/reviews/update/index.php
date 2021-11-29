<?php
include 'connect.php';

$review_id=$_GET['updateid'];//for update button in display.php


//this is to view specific review_id in update.php so that users don't forget what they write 
$sql="SELECT * from `reviews` WHERE review_id='$review_id'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($result);
$body=$row['body'];
$rating=$row['rating'];


if(isset($_POST['submit'])){
    $body=$_POST['body'];
    $rating=$_POST['rating'];

    
    $sql="UPDATE `reviews` set review_id='$review_id', body='$body', rating='$rating' WHERE review_id='$review_id'"; 
    //WHERE query to prevent duplicate entry for primary key - "review_id"   

    $result=mysqli_query($con,$sql);
    if($result){
        echo "Data updated successfully";
        header('location:display.php'); //after create will redirect to display.php
    }else{
        die(mysqli_error($con));
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>REVIEW CRUD</title>
</head>

<body>
    <div class="container my-5">
        <form method="post">
            <div class="form-group">
                <label>Review</label>
                <input type="text" class="form-control" 
                placeholder="Enter your review" name="body" autocomplete="off" value=<?php
                 echo $body;?>>
            </div>
            <div class="container">
        <form method="post">
            <div class="form-group">
                <label>Stars</label>
                <input type="text" class="form-control" 
                placeholder="Give some stars from 1-5" name="rating" autocomplete="off" value=<?php
                 echo $rating;?>>
            </div>
            
            <br>
            <button type="submit" class="btn btn-primary" name="submit">Update</button>
        </form>
    </div>


</body>

</html>
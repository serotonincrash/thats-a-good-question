<?php
require_once('../../config.php');

if(isset($_POST['submit'])){
    $body=$_POST['body'];
    $rating=$_POST['rating'];

    //$sql="INSERT into `reviews` (order_id,body,rating) values ('','$body','$rating')"; //order_id set as null 
    $sql="INSERT into `reviews` (body,rating) values ('$body','$rating')";
    $result=mysqli_query($con,$sql);
    if($result){
        echo "Data inserted succesfully";
        header('location:/app/reviews/'); //after delete will redirect reviews
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>REVIEW CRUD</title>
</head>

<body>
    <div class="container my-5">
        <form method="post">
            <div class="form-group">
                <label>Review</label>
                <input type="text" class="form-control" 
                placeholder="Enter your review" name="body" autocomplete="off">
            </div>
            <div class="container">
            <div class="form-group">
                <label>Stars</label>
                <input type="text" class="form-control" 
                placeholder="Give some stars from 1-5" name="rating" autocomplete="off">
            </div>
            
            <br>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>


</body>

</html>
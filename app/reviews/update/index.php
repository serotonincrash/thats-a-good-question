
<!doctype html>
<html lang="en">
<?php
require_once('../../../api/config.php');
$review_id=$_GET['update_id'];//for update button in display.php


//this is to view specific review_id in update.php so that users don't forget what they write 
$sql="SELECT * from `reviews` WHERE review_id='$review_id'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($result);
$body=$row['body'];
$rating=$row['rating'];
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script defer src="/static/js/navbar.js"></script>
</head>

<body>
    <div id='navbar'></div>
    <div class="container my-5">
        <form action = '/api/reviews/update/?review_id=<?php echo $review_id ?>' method="post">
            <div class="form-group">
                <label>Review</label>
                <input type="text" id='reviewBody' class="form-control" value="<?php echo $body ?>"
                placeholder="Enter your review" name="body" autocomplete="off">
            </div>
            <div class="container">
            <div class="form-group">
                <label>Stars</label>
                <input type="text" id='rating' class="form-control" 
                placeholder="Give some stars from 1-5" name="rating" value=<?php echo $rating ?> autocomplete="off">
            </div>
            
            <br>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>


</body>

</html>
<?php require_once('../../api/config.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script defer src="/static/js/navbar.js"></script>
</head>

<body>
    <div id='navbar'></div>
    <div class="container">
        <button class="btn btn-primary my-5"><a href="create/" class="text-light">Add Review</a>
        </button>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Review</th>
                    <th scope="col">Stars</th>
                    <th scope="col">Update/Delete</th>
                </tr>
            </thead>
            <tbody>


                <?php

                $sql = "SELECT * from `reviews`";
                $result = mysqli_query($con, $sql);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $review_id = $row['review_id'];
                        $body = $row['body'];
                        $rating = $row['rating'];
                        echo '<tr>
                        <th scope="row">' . $review_id . '</th>
                        <td>' . $body . '</td>
                        <td>' . $rating . '</td>
                        <td>
                        <button class="btn btn-primary"><a href="update/?updateid=' . $review_id . '" class="text-light" >Update</a></button>
                        <button class="btn btn-danger"><a href="delete/?deleteid=' . $review_id . '" class="text-light">Delete</a></button>
                    </td>
                    </tr>';
                    }
                }

                //?deleteid='.$review_id.' (have to put this for the href)

                ?>

            </tbody>
        </table>

    </div>

</body>

</html>
<?php require_once('../../api/config.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REVIEW CRUD</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
    <div class="container">
        <button class="btn btn-primary my-5"><a href="/api/reviews/create/" class="text-light">Add Review</a>
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
                        <button class="btn btn-primary"><a href="/api/reviews/update/?updateid='.$review_id.'" class="text-light" >Update</a></button>
                        <button class="btn btn-danger"><a href="/api/reviews/delete/?deleteid='.$review_id.'" class="text-light">Delete</a></button>
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
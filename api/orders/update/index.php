<?php

require_once('../../config.php');

if (isset($_POST['submit'])) {


    $order_id = $_POST['order_id'];
    $fulfilled = $_POST['fulfilled'];

    $sql = "UPDATE orders set fulfilled='$fulfilled' WHERE order_id='$order_id'";
    //WHERE query to prevent duplicate entry for primary key - "order_id"


    $result = mysqli_query($con, $sql);
    if ($result) {
        echo "Data updated successfully";
        header("Location: /app/orders/");
        die();
    } else {
        echo "error";
        die(mysqli_error($con));
    }
}

<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script defer src="/static/js/navbar.js"></script>
</head>

<body>
    <div id='navbar'></div>
    <div class='container'>
        <?php
        require_once('../../api/config.php');

        $query = "SELECT order_id, buyer_id, item_id , fulfilled FROM tagq.orders"; //SQL statement to read the information
        $pQuery = $con->prepare($query); //use prepared statements
        $result = $pQuery->execute(); //execute
        $result = $pQuery->get_result(); //store the results into a variable

        if (!$result) {
            die("SELECT query failed<br>" . $con->error);
        }

        $nrows = $result->num_rows; //calculate number of rows


        if ($nrows > 0) {
            //draw the table header ONCE only
            echo "<table border=1>";
            echo "<tr>";
            echo "<th>order's id</th>";
            echo "<th>user's id</th>";
            echo "<th>item's id</th>";
            echo "<th>fulfilled</th>";

            while ($row = $result->fetch_assoc()) { //fetch assoc allows you to read in a record and allows you to traverse your results row by row
                echo "<tr>";
                echo "<td>";

                echo $row['order_id']; //coresponding record, column's value and prints it out
                echo "</td>";
                echo "<td>";
                echo $row['buyer_id']; //coresponding record, column's value and prints it out
                echo "</td>";
                echo "<td>";
                echo $row['item_id']; //coresponding record, column's value and prints it out
                echo "</td>";
                echo "<td>";
                echo $row['fulfilled']; //coresponding record, column's value and prints it out
                echo "</td>";
                echo "<td>";


                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "0 records<br>";
        }


        ?>
        <a class='btn btn-primary' href="create/">Create an Order</a>
        <a class='btn btn-primary' href="update/">Update an Order</a>
        <a class='btn btn-danger' href="delete/">Delete an Order</a>
    </div>


</body>

</html>
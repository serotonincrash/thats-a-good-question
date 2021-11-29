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
    <form action="/api/inventory/create/" method="post">
        <p>
            <label for="partname">Part_Name:</label>
            <input type="text" name="part_name" id="partname" min="1" max="100">
        </p>
        <p>
            <label for="Sku">SKU:</label>
            <input type="number" name="sku" id="Sku" min="1" max="999999999999">
        </p>
        <p>
            <label for="Stock">Stock:</label>
            <input type="number" name="stock" id="Stock" min="1" max="99999">
        </p>
        <input type="submit" value="Submit">
    </form>
    <?php
    $con = mysqli_connect("localhost", "root", "", "tagq"); //connect to database
    if (!$con) {
        die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
    }
    $query = "SELECT part_id,part_name,sku,stock FROM inventory"; //SQL statement to read the information
    $pQuery = $con->prepare($query); //use prepared statements
    $result = $pQuery->execute(); //execute
    $result = $pQuery->get_result(); //store the results into a variable
    if (!$result) {
        die("SELECT query failed<br>" . $con->error);
    } else {
        echo "Inventory for tagq<br>";
    }

    $nrows = $result->num_rows; //calculate number of rows
    echo "number of rows=$nrows<br>";

    if ($nrows > 0) {
        //draw the table header ONCE only
        echo "<table border=1>";
        echo "<tr>";
        echo "<th>Part_ID</th>";
        echo "<th>Part_Name</th>";
        echo "<th>SKU</th>";
        echo "<th>Stock</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) { //fetch assoc allows you to read in a record and allows you to traverse your results row by row
            echo "<tr>";
            echo "<td>";
            echo $row['part_id']; //coresponding record, column's value and prints it out
            echo "</td>";
            echo "<td>";
            echo $row['part_name']; //coresponding record, column's value and prints it out
            echo "</td>";
            echo "<td>";
            echo $row['sku']; //coresponding record, column's value and prints it out
            echo "</td>";
            echo "<td>";
            echo $row['stock']; //coresponding record, column's value and prints it out
            echo "</td>";
            echo '<td align="center"><a href="/app/inventory/update/?part_id=' . $row['part_id'] . '">edit</a> </td>';
            echo '<td align="center"><a href="#" onclick="myFunction(' . $row['part_id'] . ')">delete</a> </td>';

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 records<br>";
    }


    ?>
    <script>
        function myFunction(part_id) {
            var x = confirm("Are you sure you want to delete this record?");
            if (x == true) {
                window.location.assign("/api/inventory/delete/?part_id=" + part_id);
            }
        }
    </script>
</body>

</html>
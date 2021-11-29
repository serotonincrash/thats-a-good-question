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
<h2><center>Product Details :<center></h2>
<center><table border="3">
  <tr>
    <td>Item ID</td>
    <td>Product Name</td>
    <td>Description</td>
    <td>Materials</td>
    <td>Metadata</td>
    <td>Unit Price</td>
    <td>Vendor ID</td>
  </tr>

<?php

require_once('../../../api/config.php');

$records = mysqli_query($con,"select * FROM items"); // fetch data from database

while($data = mysqli_fetch_array($records))
{
?>
  <tr>
    <td><?php echo $data['item_id']; ?></td>
    <td><?php echo $data['name']; ?></td>
    <td><?php echo $data['description']; ?></td>
    <td><?php echo $data['materials']; ?></td>
    <td><?php echo $data['metadata']; ?></td>
    <td><?php echo $data['price']; ?></td>
    <td><?php echo $data['vendor_id']; ?></td>
  </tr>	
<?php
}
?>
</table></center>

<form action="/api/listings/update/" method="post">
Product ID: <input type="text" name="item_id"><br><br>

Product Name: <input type="text" name="item_name"><br><br>

Description : <textarea name="description" rows="5" cols="40"></textarea><br><br>

Product Materials : <textarea name="materials" rows="5" cols="40"></textarea><br><br>

Metadata : <textarea name="metadata" rows="5" cols="40"></textarea><br><br>

Unit Price : <input type="text" name="price"><br><br>
<input type="submit" value="Update">
</form>
</body>
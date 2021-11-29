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
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3 col-sm-12">
        <form action="/api/inventory/update/" method="POST">
          <h3>Edit Form</h3>
          <?php var_dump($_GET); ?>
          <input type="hidden" value=<?php echo $_GET['part_id']; ?> name="part_id">
          <div class="form-group">
            <label for="partname">Part_Name:</label>
            <input type="text" name="part_name" id="partname" min="1" max="100">
          </div>
          <div class="form-group">
            <label for="Sku">SKU:</label>
            <input type="number" name="sku" id="Sku" min="1" max="999999999999">
          </div>
          <div class="form-group">
            <label for="Stock">Stock:</label>
            <input type="number" name="stock" id="Stock" min="1" max="99999">
          </div>

          <input type="submit" name="editForm" value="Submit">
        </form>
        </div>
        </div>
        </div>
</body>

</html>
<html>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3 col-sm-12">
      <form action="/api/inventory/update/" method="POST">
      <h3>Edit Form</h3>
        <?php var_dump($_GET); ?>
        <input type="hidden" value=<?php echo $_GET['part_id']; ?> name="part_id" >
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
    </dev>
  </dev>
</dev>
</body>
</html>
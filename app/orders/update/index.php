!---
<b><i>Registration form</i></b>
--->

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
    <b>Create list</b><br>
    <form action="/api/orders/update/" method="post">
        <input name='order_id' placeholder='Order ID'>
        <label>Fulfilled:</label>
        <select name='fulfilled'>
            <option value=0>Unfulfilled</option>
            <option value=1>Fulfilled</option>
        </select><br>
        <input type="submit" name='submit' value="Submit">
    </form>

</body>

</html>
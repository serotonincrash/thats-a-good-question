!---
<b><i>Report form</i></b>
--->

<html>
<style>
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>
<body>

<h3>Report Form</h3>

<div>
  <form method = "POST" accept-charset="utf-8" action="sendgrid.php">
    <label for="fname">User ID or Name</label>
    <input type="text" id="fname" name="firstname" placeholder="Your name..">

    <label for="body">Type what you want to report</label>
    <input type="text" id="body" name="body" placeholder="Your report">


    <label for="subject">Regarding what issue?</label>
    <select id="subject" name="subject">
      <option >order problems</option>
      <option>account problems</option>
      <option >inventory problems</option>
    </select>

    <input type="submit" value="Submit">
  </form>
</div>

</body>
</html>
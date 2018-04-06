<?php 
include 'includes/database.php';
//Create the select query
//$query = "SELECT * FROM customer";
//Get results
//$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

if($_POST){
	//echo 'The form was submitted';
	//Get variables from post array
	
	//$name = mysql_real_escape_string($_POST['name']);
	//$address = mysql_real_escape_string($_POST['address']);
	//$email = mysql_real_escape_string($_POST['email']);


	$name = $_POST['name'];
	$address = $_POST['address'];
	$email = $_POST['email'];

	//Create customer query
	$query = "INSERT INTO `customer` (`email`, `name`, `address`) VALUES ('$email', '$name', '$address')";
	//Run query
	$mysqli->query($query);

	//******************
	//$mysqli->insert_id

	$message = "Customer Added";

	header('Location: index.php?msg='.urlencode($message).'');
	exit;

}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  

    <title>Imzoughene Youssef</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/jumbotron-narrow.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation"><a href="index.php">Home</a></li>
            <li role="presentation"><a target="_blank" href="http://imzoughene.bitballoon.com/">Imzoughene Youssef</a></li>
            <li role="presentation" class="active"><a href="add_customer.php">Add Customer</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">Store Cmanager</h3>
      </div>

    

      <div class="row marketing">
        <div class="col-lg-6">
         <h2>Add Customers</h2>
         <form action="add_customer.php" method="post">
          <div class="form-group">
		    <label>Name</label>
		    <input type="text" class="form-control" 
		    placeholder="Name" name="name">
		  </div>
		  <div class="form-group">
		    <label>Email</label>
		    <input type="email" class="form-control" 
		    placeholder="Email" name="email">
		  </div>
		  <div class="form-group">
		    <label>Address</label>
		    <input type="text" class="form-control" 
		    placeholder="Address" name="address">
		  </div>
		  
		  
		  <input type="submit" class="btn btn-default" 
		  value="Add Customer" />
		</form>
        </div>
      </div>

      <footer class="footer">
        <p>&copy; 2018 Imzoughene Youssef.</p>
      </footer>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

<?php 
include 'includes/database.php';
//Assign get variable
$id = $_GET['id'];
//Create customer select query
$query = "SELECT * FROM customer where id = $id";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
if($result = $mysqli->query($query)){
	//Fetch objec array
		while($row = $result->fetch_assoc()){
		$name = $row['name'];
		$email = $row['email'];
		$address = $row['address'];
	}
	//Free Result set
	$result->close();
}

if($_POST){
	$id = $_GET['id'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$email = $_POST['email'];

	//Create customer update
	$query = "UPDATE customer SET `email` = '$email',
	 `name` = '$name', `address` = '$address'
	  WHERE customer.id = $id";

	$mysqli->query($query);

	//******************
	//$mysqli->insert_id

	$message = "Customer Updated";

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
         <h2>Edit Customers</h2>
         <form 
         action="edit_customer.php?id=<?php echo $_GET['id']; ?>" 
         method="post">
          <div class="form-group">
		    <label>Name</label>
		    <input type="text" class="form-control" 
		    placeholder="Name" value="<?php echo $name; ?>" name="name">
		  </div>
		  <div class="form-group">
		    <label>Email</label>
		    <input type="email" class="form-control" 
		    placeholder="Email"
		    value="<?php echo $email; ?>"
		     name="email">
		  </div>
		  <div class="form-group">
		    <label>Address</label>
		    <input type="text" class="form-control" 
		    value="<?php echo $address; ?>"
		    placeholder="Address" name="address">
		  </div>
		  
		  
		  <input type="submit" class="btn btn-default" 
		  value="Update Customer" />
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

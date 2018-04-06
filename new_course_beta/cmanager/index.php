<?php 
include 'includes/database.php';
//Create the select query
$query = "SELECT * FROM customer order by id desc";
//Get results
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
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
            <li role="presentation" class="active"><a href="index.php">Home</a></li>
            <li role="presentation"><a target="_blank" href="http://imzoughene.bitballoon.com/">Imzoughene Youssef</a></li>
            <li role="presentation"><a href="add_customer.php">Add Customer</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">Store Cmanager</h3>
      </div>

    

      <div class="row marketing">
        <div class="col-lg-6">
         
         <?php
         if(isset($_GET['msg'])){
         	?>
         	<div class="alert alert-success" role="alert">
         	<?php echo $_GET['msg']; ?>
         	 </div>
         	<?php
         }
         ?>
        
         <h2>Customers</h2>
         <table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">Customer Name</th>
		      <th scope="col">Email</th>
		      <th scope="col">Address</th>
		      <th scope="col"></th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php 
		  	//Check if at least one row is found
		  	if($result->num_rows > 0){
		  		// Loop through results
		  		while($row = $result->fetch_assoc()){
		  			//Display customer info
		  			?>
		  			    <tr>
					      <th scope="row">
					      	<?php echo $row['name'] ?>
					      		
					      </th>
					      <td>
					      	<?php echo $row['email'] ?>
					      		
					      </td>
					      <td>
					      	<?php echo $row['address'] ?>
					      </td>
					      <td><a href="edit_customer.php?id=<?php echo $row['id'] ?>" class="btn btn-default">Edit</a></td>
					    </tr>
		  			<?php
		  		}
		  	}else{
		  		echo "Sorry ,no customers were found";
		  	}
		  	?>
		   
		    
		   
		  </tbody>
		</table>
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

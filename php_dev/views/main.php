<!DOCTYPE html>
<html>
<head>
	<title>ShareBoard</title>
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>/assets/css/style.css">
</head>
<body>
	<?php //require($view); ?>
	
	

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Shareboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <!--
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
  	  -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo ROOT_URL; ?>">Home</a>
      </li>
      
       <li class="nav-item">
        <a class="nav-link" href="<?php echo ROOT_URL; ?>/Shares">Shares</a>
      </li>
    </ul>
	<ul class="navbar-nav ml-auto">
	   <?php if(isset($_SESSION['is_logged_in'])) : ?>
       <li class="nav-item">
        <a class="nav-link" href="<?php echo ROOT_URL; ?>">
        Welcome <?php echo $_SESSION['user_data']['name']; ?>
    	</a>
       </li>
       <li class="nav-item">
        <a class="nav-link" href="<?php echo ROOT_URL; ?>/users/logout">
        Logout
    	</a>
       </li>
      <?php else : ?>
      	<li class="nav-item">
        <a class="nav-link" href="<?php echo ROOT_URL; ?>/users/login">Login</a>
       </li>
       <li class="nav-item">
        <a class="nav-link" href="<?php echo ROOT_URL; ?>/users/register">Register</a>
      </li>
      <?php endif; ?>
     
    </ul>
    
  </div>
</nav>
    <main role="main" class="container">

      <div class="row">
      	<div class="col-md-12 col-sm-12">
          <?php messages::display(); ?>
      		<?php require($view); ?>
      	</div>   	
      </div>

    </main><!-- /.container -->
</body>
</html>
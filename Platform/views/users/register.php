<div class="card">
  
  <div class="card-body">
  	<h5 class="card-title">Register User</h5>
    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    	<div class="form-group">
    		<label>Name</label>
    		<input type="text" name="name"
    		class="form-control" />

    	</div>
    	<div class="form-group">
    		<label>Email</label>
    		<input  type="email" name="email"
    		class="form-control" />
    		
    	</div>
    	<div class="form-group">
    		<label>Password</label>
    		<input type="password" name="password"
    		class="form-control" />
    		
    	</div>
    	<input class="btn btn-primary" name="submit"
    	type="submit" value="submit">
    	
    </form>
  </div>
</div>
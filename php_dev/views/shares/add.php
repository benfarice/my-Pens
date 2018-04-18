<div class="card">
  
  <div class="card-body">
  	<h5 class="card-title">Share Something</h5>
    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    	<div class="form-group">
    		<label>Share Title</label>
    		<input type="text" name="Title"
    		class="form-control" />

    	</div>
    	<div class="form-group">
    		<label>Body</label>
    		<textarea name="Body"
    		class="form-control"></textarea>
    		
    	</div>
    	<div class="form-group">
    		<label>Link</label>
    		<input type="text" name="Link"
    		class="form-control" />
    		
    	</div>
    	<input class="btn btn-primary" name="submit"
    	type="submit" value="submit">
    	<a href="<?php echo ROOT_URL ?>/shares"
    	 class="btn btn-danger">
    		Cancel
    	</a>
    </form>
  </div>
</div>
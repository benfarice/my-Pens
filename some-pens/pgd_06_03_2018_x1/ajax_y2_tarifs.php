 <label for="article_titre">Article | Désignation</label>
					

<?php
	require_once('connexion.php'); 


	if(isset($_REQUEST['searched_ref'])){


  	$query_article_designation = 
    "select a.Designation from articles a where a.Reference = '$_REQUEST[searched_ref]'";
	$params_article_designation = array();
	$options_article_designation =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result__article_designation = 
	sqlsrv_query($conn,$query_article_designation,$params_article_designation,$options_article_designation) 
	or die( 
	print_r(sqlsrv_errors(), true));
	while($reader_article_designation = sqlsrv_fetch_array($result__article_designation, SQLSRV_FETCH_ASSOC)){
	?>
	<input  class="form-control" name="article_titre" id="article_titre" placeholder="Article | Désignation"  
	  value="<?php echo $reader_article_designation['Designation']; ?>" >

	<?php
	}


 

	}

 <option selected value="tous">Tous</option>
 <?php 
 					require_once('connexion.php'); 
 					if(isset($_REQUEST['inputdepot']) && $_REQUEST['inputdepot'] != "" 
 					&& $_REQUEST['inputdepot'] != "tous" )
			       	$query_vendeur = "select  distinct v.nom +' '+ v.prenom as vendeur ,v.idVendeur from vendeurs v where v.idDepot = $_REQUEST[inputdepot]";
			        else
			       	$query_vendeur = "select  distinct v.nom +' '+ v.prenom as vendeur ,v.idVendeur from vendeurs v";
						$params_query_vendeurt = array();
						$options_query_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
						$stmt_query_vendeur=sqlsrv_query($conn,$query_vendeur,$params_query_vendeurt,$options_query_vendeur);
						$ntRes_query_vendeur = sqlsrv_num_rows($stmt_query_vendeur);
						while($row__query_vendeur = 
							sqlsrv_fetch_array($stmt_query_vendeur, SQLSRV_FETCH_ASSOC)){

						
			         ?>
			        <option value="<?php echo $row__query_vendeur['idVendeur']; ?>">
			        	<?php echo $row__query_vendeur['vendeur']; ?>
			        </option>
			        <?php 
			        }
			         ?>
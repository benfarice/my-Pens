<?php 
	
	require_once('connexion.php');  
	session_start();
	include("lang.php");
//	$IdDepot=1;
$sql = "
		SELECT 
			idFiche as idFiche,numFiche,f.TypeVente, date as dateFiche,Dsg_".$_SESSION['lang']." Dsg
		FROM 
			fichetarifs f
			left join TypeVente  tc on tc.IdType= f.TypeVente
			where idFiche = ?";
	 $params = array($_GET['id_fiche']);
	 $resAff = sqlsrv_query($conn,$sql,$params) or die( print_r( sqlsrv_errors(), true));
	$row = sqlsrv_fetch_array( $resAff, SQLSRV_FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Mise à Jour</title>
	<link rel="stylesheet" type="text/css" href="css_y/bootstrap-4.min.css">
    <link rel="stylesheet" type="text/css" href="css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="css/css_google_font.css">
    
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-4.min.js"></script>
	<script type="text/javascript">
		
		function ajax_func(){
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                document.getElementById('get_data').innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open('GET',"ajax_y_tarifs.php?id_fiche=<?php echo $_GET['id_fiche']; ?>",true);
        xmlhttp.send();
        animate_table();
        }

        function search_ref(){
        xmlhttp2 = new XMLHttpRequest();
        	searched_ref = document.getElementById('select_ref').value;
        	//alert(searched_ref);
        	xmlhttp2.onreadystatechange = function(){
	            if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
	                document.getElementById('afficher_titre_article').innerHTML = xmlhttp2.responseText;
	            }
	        }
	        xmlhttp2.open('GET',"ajax_y2_tarifs.php?searched_ref="+searched_ref,true);
	        xmlhttp2.send();
	       
        }
        function add_tarif(){
        	//alert('ok');
        	$("#myModal_add_tarif").modal('hide');
        	var add_tarif = $("#add_tarif").val(),
        		article_titre = $("#article_titre").val();
			//txt = "You pressed OK!";
			xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				    document.getElementById('get_data').innerHTML = xmlhttp.responseText;
				            }
				        }
		    xmlhttp.open('GET',"ajax_y_tarifs.php?add_tarif="+add_tarif+"&searched_ref="+searched_ref+"&id_fiche=<?php echo $_GET['id_fiche']; ?>",true);
		    xmlhttp.send();
			
			reset_modal_add_tarif();

        }
        function reset_modal_add_tarif(){
        	$("#article_titre").val("");		
        	$("#add_tarif").val("");
        }
        function modal_add_tarif(){
        	var add_tarif = $("#add_tarif").val(),
        		article_titre = $("#article_titre").val();
        	if(add_tarif != "" && article_titre != "" && (!isNaN(add_tarif))  && article_titre != null){
        			var r = "Voulez-Vous Vraiment Ajouter  l\'article "+article_titre+"  ayant le code suivant : "+
        			searched_ref+" avec le tarif "+add_tarif+" DH";
        			$("#txt_add_tarif").html(r);
        			$('#myModal_add_tarif').modal('show');
        	}
        	//alert("here");
        	//$("#myModal_add_tarif").modal('show');
        	 
        }
        function modifier_tarif(id_tarif,tarif,code_a_bare,article_nom){
        	//alert(id_tarif+' '+code_a_bare+' '+tarif+article_nom);
        	$('#txt_update_tarif_article_name').html(article_nom);
        	$('#txt_update_tarif_article_code').html(code_a_bare);
        	$("#update_tarif").val(tarif);
        	$("#id_tarif_for_update").val(id_tarif);
        	$('#myModal_update_tarif').modal('show');
        }
        
        function del_tarif(id_tarif,tarif,code_a_bare,article_nom){
        	//alert("yeah");
        	var r = "Voulez-Vous Vraiment Supprimer  l\'article "+article_nom+"  ayant le code suivant : "+
        			code_a_bare+" avec le tarif "+tarif+" DH";
        			$("#txt_delete_tarif").html(r);
        	$('#id_tarif_to_delete').val(id_tarif);
        	$('#myModal_delete_tarif').modal('show');
        }
        function reset_modal_update_tarif(){
        	$('#txt_update_tarif_article_name').html("...........");
        	$('#txt_update_tarif_article_code').html(".............");
        	$("#update_tarif").val(0);
        	$("#id_tarif_for_update").val(0);
        }
        function update_tarif(){
      	$("#myModal_update_tarif").modal('hide');
        	var update_tarif = $("#update_tarif").val(),
        		id_tarif = $("#id_tarif_for_update").val();
			
			xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				    document.getElementById('get_data').innerHTML = xmlhttp.responseText;
				            }
				        }
		    xmlhttp.open('GET',"ajax_y_tarifs.php?id_tarif="+id_tarif+"&new_tarif="+update_tarif+"&id_fiche=<?php echo $_GET['id_fiche']; ?>",true);
		    xmlhttp.send();
			
			reset_modal_update_tarif();

      }
      function delete_tarif(){
      		$("#myModal_delete_tarif").modal('hide');
        	var id_tarif = $("#id_tarif_to_delete").val();
			
			xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				    document.getElementById('get_data').innerHTML = xmlhttp.responseText;
				            }
				        }
		    xmlhttp.open('GET',"ajax_y_tarifs.php?id_tarif_to_delete="+id_tarif+"&id_fiche=<?php echo $_GET['id_fiche']; ?>",true);
		    xmlhttp.send();
			
			reset_modal_delete_tarif();
      }
      function reset_modal_delete_tarif(){
      		$("#id_tarif_to_delete").val(0);
      		$("#txt_delete_tarif").html('');
      }
     
		
	</script>
</head>
<body onload="ajax_func();animate_table();">
	<div class="container">
		<div class="row" style="font-size: 20px;padding: 25px;">
			<div class="col-md-4 text-center animated fadeInRight">
				<?php echo ucfirst($row["numFiche"]); ?>
			</div>
			<div class="col-md-4 text-center animated fadeInRight">
				<?php echo $row['Dsg']; ?>
			</div>
			<div class="col-md-4 text-center animated fadeInRight">
				<?php echo date_format($row['dateFiche'], 'd/m/Y');?>
			</div>
		</div>
		<br>
		<br>
		<div class="row animated bounce">
			<div class="col-6">
				<form onsubmit="search_ref();return false;">
					<div class="form-group">
					    <label for="select_ref">Code article</label>
					    <input type="text" class="form-control" id="select_ref" name="select_ref"
					     placeholder="Code article">
					</div>
				</form>
			</div>

		</div>
    	<br>
    
    	<div class="row">
    			
				<div class="col-6 animated fadeInRight">			
						<div class="form-group" id="afficher_titre_article">
						    <label for="article_titre">Article | Désignation</label>
						    <input  class="form-control" name="article_titre" id="article_titre" placeholder="Article | Désignation">
						</div>			
				</div>
				<div class="col-3 animated fadeInRight">
					<div class="form-group">
					    <label for="add_tarif">Tarif ( DH )</label>
					    <input type="number" class="form-control" id="add_tarif" name="add_tarif"
					     placeholder="Tarif ( DH )">
					</div>
				</div>
				<div class="col-3 animated fadeInRight">
					<div class="form-group"> 
						<br>  
					    <button class="btn btn-success btn-block" onclick="modal_add_tarif();return false;">Ajouter
					    </button>
					</div>
				</div>
		
		</div>

    	<br>
		<table class="table">
			<thead class="thead-dark">
				<tr class="animated bounce">
					<th scope="col">
						Code article
					</th>
					<th scope="col">
						Article
					</th>
					<th scope="col">
						Tarif ( DH )
					</th>
					<th scope="col">
						Modifier
					</th>
					<th scope="col">
						Supprimer
					</th>
				</tr>			
			</thead>
			<tbody id="get_data">
			<!-- ..................................................... -->
			<!-- ......................
			Get Ajax DaTA HERE
				............................... -->
			<!-- ..................................................... -->
			</tbody>
		</table>
		<!-- Modal -->
		
	</div>
	<div id="myModal_add_tarif" class="modal fade"  data-backdrop="static">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			      	<h4 class="modal-title">Ajouter</h4>
			        <button type="button" class="close" data-dismiss="modal" onclick="reset_modal_add_tarif();">&times;</button>
			        
			      </div>
			      <div class="modal-body">
			        <p id="txt_add_tarif">............</p>
			      </div>
			      <div class="modal-footer">
			      	<button type="button" class="btn btn-success" onclick="add_tarif();">Confirmer</button>
			        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="reset_modal_add_tarif();">Annuler</button>
			      </div>
			    </div>

			  </div>
			</div>
	<div id="myModal_delete_tarif" class="modal fade"  data-backdrop="static">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			      	<h4 class="modal-title">Supprimer</h4>
			        <button type="button" class="close" data-dismiss="modal" onclick="reset_modal_delete_tarif();">&times;</button>
			        
			      </div>
			      <div class="modal-body">
			        <p id="txt_delete_tarif">............</p>
			        <input type="hidden" name="" id="id_tarif_to_delete">
			      </div>
			      <div class="modal-footer">
			      	<button type="button" class="btn btn-success" onclick="delete_tarif();">Confirmer</button>
			        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="reset_modal_delete_tarif();">Annuler</button>
			      </div>
			    </div>

			  </div>
			</div>
	<div id="myModal_update_tarif" class="modal fade" data-backdrop="static">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			      	<h4 class="modal-title">Modifier</h4>
			        <button type="button" class="close" data-dismiss="modal"  
			        onclick="reset_modal_update_tarif();">&times;</button>
			        
			      </div>
			      <div class="modal-body">
			        <p>Article | Désignation : <span id="txt_update_tarif_article_name">............</span></p>
			        <p>Code article : <span id="txt_update_tarif_article_code">............</span></p>
			        <div class="form-group" id="update_tarif_div">
						    <label for="update_tarif">Nouveau Tarif</label>
						    <input  class="form-control" name="update_tarif" id="update_tarif" 
						    placeholder="Nouveau Tarif" type="number">
				    </div>	
				    <input type="hidden" name="" id="id_tarif_for_update">	
			      </div>
			      <div class="modal-footer">
			      	<button type="button" class="btn btn-success" onclick="update_tarif();">Confirmer</button>
			        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="reset_modal_update_tarif();">Annuler</button>
			      </div>
			    </div>

			  </div>
			</div>
	<style type="text/css">
		*{
				 
				 letter-spacing: -.05em;
				 font-weight: 100;
				 font-family: 'Montserrat', sans-serif;
		}
	</style>
	<script type="text/javascript">
		
	</script>
</body>
</html>
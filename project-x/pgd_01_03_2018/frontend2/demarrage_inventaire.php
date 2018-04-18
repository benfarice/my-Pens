<?php
require_once('../connexion.php');

/****/
if(!isset($_SESSION))
{
	session_start();
} 
include("lang.php");
//initialiser le type de vente direct ou prevente 
unset($_SESSION['Vente']);	
/****/
if(isset($_GET['logout'])){
	unset($_SESSION['Vendeur']);
	unset($_SESSION['IdVendeur']);
	unset($_SESSION['IdDepot']);
	?>
	<script language="javascript" > 
	window.location.href = 'login.php';
	</script>
	
<?php
}
if(!isset($_SESSION['IdVendeur'])){
	?>
	<script language="javascript" > 
	window.location.href = 'login.php';
	</script>
	
<?php
}
if(!isset($_SESSION['numero_inventaire'])){
	?>
	<script language="javascript" > 
	window.location.href = 'inventaire_y.php';
	</script>
	
<?php
}

require_once("header_y.php");

//style="float: right;"
?>
<?php

// Check if image file is a actual image or fake image
if(isset($_POST["something"])) {
	$_SESSION['clicked_recup'] = "no";
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["upload_xml_to_update"]["name"]);
	$uploadOk = 1;
	
   

// Check if file already exists

// Check file size
if ($_FILES["upload_xml_to_update"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["upload_xml_to_update"]["tmp_name"], $target_file)) {
      // echo "The file ". basename( $_FILES["upload_xml_to_update"]["name"]). " has been uploaded.";
    	
       $file = file_get_contents('uploads/'.basename( $_FILES["upload_xml_to_update"]["name"]));
       //echo '<pre>' . str_replace('<', '&lt;', $file) . '</pre>';
       //echo $file;
       $xmldata = simplexml_load_string($file);
       foreach($xmldata->Article as $item)
		{
			    //echo (string)$item->nbr_colisage_select;
		        if($item->nbr_colisage_select != -1 || $item->nbr_piece_select != -1){

		        	$query_chekout ="select d.NBR_colisage,d.NBR_piece from
		        	Detail_inventaire_table d 
					where Numero ='$_SESSION[numero_inventaire]' and d.idArticle = (select idArticle from articles
					where Reference = '".$item->RefArticle."')";
					//echo $query_chekout;
					$nbr_pieces_db = -1;
					$nbr_colisage_db = -1;
					$params_query_chekout = array();
					$options_query_chekout =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
					$stmt_query_chekout=sqlsrv_query($conn,$query_chekout,$params_query_chekout,$options_query_chekout);
					$ntRes_select_numero_inventaire = sqlsrv_num_rows($stmt_query_chekout);
					while($row_query_chekout= sqlsrv_fetch_array($stmt_query_chekout, SQLSRV_FETCH_ASSOC)){
							$nbr_colisage_db = $row_query_chekout['NBR_colisage'];
							$nbr_pieces_db = $row_query_chekout['NBR_piece'];
					}
					//if(is_null($nbr_colisage_db)) echo "string nbr is null";
					if(($nbr_pieces_db == -1 && $nbr_colisage_db == -1) ||
				    is_null($nbr_pieces_db) && is_null($nbr_colisage_db)){
					$query_insert = "insert into 
		        	Detail_inventaire_table(Numero,idArticle,NBR_colisage,NBR_piece)
					values('$_SESSION[numero_inventaire]',(select IdArticle from articles where Reference ='".$item->RefArticle."'),"
					.$item->nbr_colisage_select.",".$item->nbr_piece_select.")";
					//echo $query_insert;
					$result_update = sqlsrv_query($conn,$query_insert) or die(sqlsrv_errors());
					//echo $query_insert."<br>";
					}else{
					$query_update_exist = "update Detail_inventaire_table 
					set NBR_colisage = ".$item->nbr_colisage_select.",NBR_piece = 
					".$item->nbr_piece_select." where idArticle = 
					(select r.IdArticle from articles r where r.Reference ='
					".$item->RefArticle."') 
                    and Numero = '$_SESSION[numero_inventaire]'";
                    $result_update_db = sqlsrv_query($conn,$query_update_exist) or die(sqlsrv_errors());
                    //echo $query_update_exist."<br>";
					}		    

		        }
		        
		}
	/*?>
	<script type="text/javascript" src="js/jQuery.print.min.js"></script>
	<script>

	$(function () {

	  $("table").print();
	    var pdf = new jsPDF();
	    pdf.addHTML(document.body,function() {
		    pdf.save('web.pdf');
		});
	 
	});
	</script>

	<?php
	*/

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
}


if(isset($_POST["something_js"])) {
	$_SESSION['clicked_recup'] = "yes";
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["upload_xml_to_update_js"]["name"]);
	$uploadOk = 1;
	
   

// Check if file already exists

// Check file size
if ($_FILES["upload_xml_to_update_js"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["upload_xml_to_update_js"]["tmp_name"], $target_file)) {
      // echo "The file ". basename( $_FILES["upload_xml_to_update"]["name"]). " has been uploaded.";
    	
       $file = file_get_contents('uploads/'.basename( $_FILES["upload_xml_to_update_js"]["name"]));
       //echo '<pre>' . str_replace('<', '&lt;', $file) . '</pre>';
       //echo $file;
       $xmldata = simplexml_load_string($file);
       foreach($xmldata->Article as $item)
		{
			    //echo (string)$item->nbr_colisage_select;
		        if($item->nbr_colisage_select != -1 || $item->nbr_piece_select != -1){

		        	$query_chekout ="select d.NBR_colisage,d.NBR_piece from
		        	Detail_inventaire_table_temp d 
					where Numero ='$_SESSION[numero_inventaire]' and d.id_detail = (select idArticle from articles
					where Reference = '".$item->RefArticle."')";
					$nbr_pieces_db = -1;
					$nbr_colisage_db = -1;
					$params_query_chekout = array();
					$options_query_chekout =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
					$stmt_query_chekout=sqlsrv_query($conn,$query_chekout,$params_query_chekout,$options_query_chekout);
					$ntRes_select_numero_inventaire = sqlsrv_num_rows($stmt_query_chekout);
					while($row_query_chekout= sqlsrv_fetch_array($stmt_query_chekout, SQLSRV_FETCH_ASSOC)){
							$nbr_colisage_db = $row_query_chekout['NBR_colisage'];
							$nbr_pieces_db = $row_query_chekout['NBR_piece'];
					}

					if(($nbr_pieces_db == -1 && $nbr_colisage_db == -1) ||
				    (is_null($nbr_pieces_db) && is_null($nbr_colisage_db))){
					$query_insert = "insert into 
		        	Detail_inventaire_table_temp(Numero,idArticle,NBR_colisage,NBR_piece)
					values('$_SESSION[numero_inventaire]',(select IdArticle from articles where Reference ='".$item->RefArticle."'),"
					.$item->nbr_colisage_select.",".$item->nbr_piece_select.")";
					//echo $query_insert;
					$result_update = sqlsrv_query($conn,$query_insert) or die(sqlsrv_errors());
					}else{
					$query_update_exist = "update Detail_inventaire_table_temp 
					set NBR_colisage = ".$item->nbr_colisage_select.",NBR_piece = 
					".$item->nbr_piece_select." where idArticle = 
					(select r.IdArticle from articles r 
					where r.Reference ='".$item->RefArticle."') 
                    and Numero = '$_SESSION[numero_inventaire]'";
                    $result_update_db = sqlsrv_query($conn,$query_update_exist) or die(sqlsrv_errors());

					}		    

		        }
		        
		}
	

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
}

?>
<div class="container-fluid">
	<div class="Head row">
	<div  class="heaLeft col-6">
		<div class="Info"> 
			<a href="index.php"><img src="../images/home.png"></a>
			<?php echo $trad['index']['Bienvenu'] ;echo $_SESSION['Vendeur'];?>
		</div>
	</div>
	<div  class="headRight col-6">
		<a href="index.php?logout" class="signoutsignout" style="float: right;">
		<div class="signout">
		
		</div>
		</a>
	</div>
	</div>
<?php

/*if(isset($_GET['id_article_insert'])){
	$nbr_colisage = $_POST['input_colisage'];
	$nbr_piece = $_POST['input_piece'];
	
    $query_insert_inventaire_detail ="insert into Detail_inventaire_table(Numero,idArticle,NBR_colisage,
		NBR_piece) values('$_SESSION[numero_inventaire]',$_GET[id_article_insert],
		$nbr_colisage,$nbr_piece)";
	//echo $query_insert_inventaire_detail;
	$result_insert_inventaire_detail = sqlsrv_query($conn,
		$query_insert_inventaire_detail) or die(sqlsrv_errors());
}
*/
$sql_select_depot ="select d.Designation from depots d where d.idDepot =
 $_SESSION[IdDepot]";
$_SESSION['Depot'] = "";
$params_select_depot = array();
$options_select_depot =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_select_depot=sqlsrv_query($conn,$sql_select_depot,$params_select_depot,$options_select_depot);
$ntRes_select_depot = sqlsrv_num_rows($stmt_select_depot);
while($row_select_depot= sqlsrv_fetch_array($stmt_select_depot, SQLSRV_FETCH_ASSOC)){
	$_SESSION['Depot'] = $row_select_depot['Designation'];
}



?>



    <h1 class="text-center" style="margin-top: 20px;">Inventaire</h1>
    <div class="row" style="margin: 10px;font-size: 20px;font-weight: bold">
    	<div class="col-3">Numéro : <?php echo $_SESSION['numero_inventaire']; ?></div>
    	<div class="col-3"> | <?php echo $_SESSION['Depot'] ; ?></div>
    	<div class="col-3"> | Date : <?php echo date('d/m/Y');?></div>
    	<div class="col-3"> | Heure : <?php echo date('H:i');?></div>
    </div>
    <div class="row">
    	<div id="save_xml" class="col-3 text-left">
    	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 459 459" style="enable-background:new 0 0 459 459;" xml:space="preserve">

		<g id="archive">
			<path d="M446.25,56.1l-35.7-43.35C405.45,5.1,395.25,0,382.5,0h-306C63.75,0,53.55,5.1,45.9,12.75L12.75,56.1    C5.1,66.3,0,76.5,0,89.25V408c0,28.05,22.95,51,51,51h357c28.05,0,51-22.95,51-51V89.25C459,76.5,453.9,66.3,446.25,56.1z     M229.5,369.75L89.25,229.5h89.25v-51h102v51h89.25L229.5,369.75z M53.55,51l20.4-25.5h306L402.9,51H53.55z" fill="#006DF0"/>
	    </g>
	    </svg>	    
        </div>
        <div class="col-5"></div>
        <div class="col-2">
        	<form action="demarrage_inventaire.php" method="POST" enctype="multipart/form-data" id="upload_on_change_js" name="my_upload_form_js">
			 <div class="upload-btn-wrapper">
				  <button class="btn_u"  class="btn btn-primary">
                  récupérer 
				  </button>
				  <input type="file"  id="upload_xml_to_update_js"
			      name="upload_xml_to_update_js" />
			      <input type="hidden" name="something_js" value="something_js">
			  </div>
			 </form>


        </div>
        <div class="col-2 text-right">
        	<form action="demarrage_inventaire.php" method="POST" enctype="multipart/form-data" id="upload_on_change" name="my_upload_form">
			 <div class="upload-btn-wrapper">
				  <button class="btn_u"  class="btn btn-primary">
                  Terminer 
				  </button>
				  <input type="file"  id="upload_xml_to_update"
			      name="upload_xml_to_update" />
			      <input type="hidden" name="something" value="something">
			  </div>
			 </form>
        </div>

    </div>
  	<style type="text/css">
  		.upload-btn-wrapper {
		  position: relative;
		  overflow: hidden;
		  display: inline-block;
		}

		.btn_u {
		  border: 2px solid gray;
		  color: gray;
		  background-color: white;
		  padding: 8px 20px;
		  border-radius: 8px;
		  font-size: 20px;
		  font-weight: bold;
		}

		.upload-btn-wrapper input[type=file] {
		  font-size: 100px;
		  position: absolute;
		  left: 0;
		  top: 0;
		  opacity: 0;
		}
  	</style>
  	<script type="text/javascript">
  		document.getElementById("upload_xml_to_update").onchange = function() {
		    document.getElementById("upload_on_change").submit();
		};
		document.getElementById("upload_xml_to_update_js").onchange = function() {
		   document.getElementById("upload_on_change_js").submit();
		   
		};
  	</script>
  	<div class="row">
  		<div class="col-6">
  			 <div class="form-group">
			      <label for="inputmarque">Sélectionner la marque</label>
			      <select id="inputmarque" class="form-control">
			        <option selected value="0">Tous</option>
			        <option>...</option>
			      </select>
			    </div>
  		</div>
  		<div class="col-6">
  			
  		</div>
  	</div>
    <div class="row" style="padding-top: 25px;padding-bottom: 15px;">
    	<div class="col-4">
    	
		  <div class="row">
		  <div class="col-12">
		  <div class="form-group">
		    <label for="select_ref">Ref</label>
		    <input type="text" class="form-control alignRight" id="select_ref" name="select_ref"
		     placeholder="Ref">
		  </div>
		  </div>
		  </div>
	   
    	</div>
    	<div class="col-4">
    	
		  <div class="row">
		  <div class="col-12">
		  <div class="form-group">
		    <label for="select_CB">CB</label>
		    <input type="text" class="form-control" id="select_CB" name="select_CB"
		     placeholder="CB">
		  </div>
		  </div>
		  </div>
	    
    	</div>
    	<div class="col-4">
    	
		  <div class="row">
		  <div class="col-12">
		  <div class="form-group">
		    <label for="select_Designation">Désignation</label>
		    <input type="text" class="form-control" id="select_Designation" 
		    name="select_Designation"
		     placeholder="Désignation">
		  </div>
		  </div>
		  </div>
	    
    	</div>
    </div>
<script type="text/javascript">
	
	document.getElementById("select_CB").focus();
</script>
<div id="myModal_ref" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
	  <div class="modal-dialog modal-lg modal-ku">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	      	<h4 class="modal-title" id="ref_design_select_ref_modal"></h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row">
	      		<div class="col-8">
	      			

   <div class="row">
   				<div class="col-6">
   					<div  id="gamme_marque_select_ref_modal">
	        		</div>
	        		<hr>
	        		<div id="total_existant"
	        		style="font-weight: bold">
	        		Stock Théorique : <br>
	        			Boîtes : 
	        			<span class="total_boites">25</span>
	        			<svg width="20" height="20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">

						<polygon style="fill:#A98258;" points="48,0 10,0 0,16 0,58 58,58 58,16 	"/>
						<polygon style="fill:#DAAE86;" points="10,0 0,16 58,16 48,0 	"/>
						<polygon style="fill:#D8B18B;" points="33,54 29,50 25,54 23,52 23,58 35,58 35,52 	"/>
						<rect x="20" y="30" style="fill:#E8D5B2;" width="18" height="16"/>
						<g>
							<path style="fill:#D4C3A5;" d="M30,41h-6c-0.552,0-1,0.447-1,1s0.448,1,1,1h6c0.552,0,1-0.447,1-1S30.552,41,30,41z"/>
							<path style="fill:#D4C3A5;" d="M34,41h-1c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S34.552,41,34,41z"/>
							<path style="fill:#D4C3A5;" d="M28,35h6c0.552,0,1-0.447,1-1s-0.448-1-1-1h-6c-0.552,0-1,0.447-1,1S27.448,35,28,35z"/>
							<path style="fill:#D4C3A5;" d="M34,37h-2c-0.552,0-1,0.447-1,1s0.448,1,1,1h2c0.552,0,1-0.447,1-1S34.552,37,34,37z"/>
							<path style="fill:#D4C3A5;" d="M24,39h2c0.552,0,1-0.447,1-1s-0.448-1-1-1h-2c-0.552,0-1,0.447-1,1S23.448,39,24,39z"/>
							<path style="fill:#D4C3A5;" d="M24,35h1c0.552,0,1-0.447,1-1s-0.448-1-1-1h-1c-0.552,0-1,0.447-1,1S23.448,35,24,35z"/>
							<path style="fill:#D4C3A5;" d="M28.29,37.29C28.11,37.479,28,37.729,28,38c0,0.27,0.11,0.52,0.29,0.71C28.48,38.89,28.74,39,29,39
								c0.26,0,0.52-0.11,0.71-0.29C29.89,38.52,30,38.26,30,38s-0.11-0.521-0.29-0.71C29.33,36.92,28.66,36.92,28.29,37.29z"/>
						</g>
						<rect x="23" style="fill:#F4D5BD;" width="12" height="16"/>
						<polygon style="fill:#D8B18B;" points="25,21 29,25 33,21 35,23 35,16 23,16 23,23 	"/>

					    </svg>
 
	        			<br>
	        			Pièces : 
	        			<span class="total_pieces">20</span> 
	        			<svg width="20" height="20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 55.667 55.667" style="enable-background:new 0 0 55.667 55.667;" xml:space="preserve">

						<path d="M37.73,40.425l0.006-0.001l-0.099-1.227c-0.168-2.07-0.714-4.244-1.717-6.84c-0.283-0.733-0.441-1.561-0.505-2.397
							c-0.141-1.846,0.223-3.743,1.053-4.816c0.162-0.21,0.296-0.444,0.429-0.679c0.557-0.983,0.868-2.16,0.863-3.409l0-0.001l0-0.001
							c-0.001-0.099-0.005-0.19-0.011-0.282l-0.042-1.417l-0.027,0.007c-0.618-7.23-4.872-9.143-6.275-9.59
							c-0.121-0.211-0.288-0.622-0.401-1.382c-0.008-0.057-0.028-0.108-0.04-0.163c1.083-0.361,1.869-0.965,1.869-1.894v-4
							c0-1.722-2.693-2.333-5-2.333s-5,0.611-5,2.333v4c0,0.929,0.786,1.532,1.868,1.893c-0.013,0.054-0.032,0.106-0.04,0.162
							c-0.113,0.764-0.28,1.174-0.438,1.406c-1.369,0.426-5.621,2.338-6.238,9.569l-0.036-0.01l-0.033,1.432
							c-0.005,0.088-0.009,0.176-0.01,0.271c-0.009,1.548,0.45,3,1.292,4.09c0.753,0.974,1.134,2.629,1.091,4.308
							c-0.017,0.672-0.102,1.347-0.257,1.984c-0.078,0.318-0.173,0.627-0.287,0.921c-0.251,0.649-0.473,1.271-0.668,1.872
							c-0.585,1.803-0.923,3.414-1.049,4.968c-0.304,3.761-0.584,8.979-0.197,12.53c0.036,1.812,1.733,3.939,9.501,3.939
							c6.521,0,10.436-1.471,10.5-3.938C38.181,48.545,37.992,44.034,37.73,40.425z M20.155,38.321c0.027-0.184,0.05-0.367,0.084-0.555
							c0.053-0.297,0.123-0.604,0.194-0.911c0.042-0.184,0.078-0.364,0.126-0.552c0.088-0.342,0.197-0.699,0.307-1.057
							c0.051-0.167,0.093-0.327,0.149-0.498c0.176-0.536,0.373-1.09,0.597-1.67c0.811-2.1,0.867-4.671,0.288-6.76
							c-0.255-0.918-0.619-1.752-1.118-2.397c-0.006-0.008-0.011-0.018-0.017-0.026c-0.12-0.158-0.225-0.331-0.321-0.511
							c-0.03-0.057-0.057-0.116-0.084-0.174c-0.071-0.15-0.133-0.307-0.188-0.469c-0.019-0.056-0.04-0.111-0.057-0.168
							c-0.06-0.204-0.106-0.417-0.14-0.635c2.253,0.473,4.987,0.73,7.859,0.73c2.872,0,5.605-0.257,7.858-0.729
							c-0.034,0.219-0.081,0.431-0.141,0.635c-0.017,0.057-0.038,0.112-0.057,0.168c-0.054,0.162-0.117,0.317-0.187,0.467
							c-0.028,0.059-0.055,0.119-0.085,0.176c-0.101,0.19-0.21,0.372-0.337,0.537c-1.021,1.321-1.546,3.387-1.511,5.489
							c0.021,1.261,0.243,2.535,0.681,3.668c0.225,0.582,0.422,1.136,0.598,1.673c0.055,0.167,0.095,0.322,0.145,0.485
							c0.111,0.362,0.221,0.722,0.31,1.067c0.049,0.191,0.085,0.373,0.128,0.56c0.069,0.303,0.138,0.605,0.191,0.898
							c0.036,0.198,0.06,0.39,0.088,0.584c0.028,0.19,0.051,0.38,0.072,0.567c-2.17,0.489-4.884,0.754-7.753,0.754
							c-2.869,0-5.583-0.266-7.753-0.754C20.102,38.717,20.126,38.52,20.155,38.321z M24.834,2.551C25.15,2.354,26.145,2,27.834,2
							s2.684,0.354,3,0.551v3.564c-0.258,0.16-0.977,0.424-2.15,0.516c-0.553,0.04-1.149,0.04-1.7,0c-1.172-0.092-1.892-0.356-2.15-0.516
							V2.551z M24.819,11.704c0.504-0.157,0.922-0.491,1.177-0.938c0.3-0.522,0.517-1.224,0.708-2.147
							c0.026,0.002,0.056,0.001,0.083,0.003c0.351,0.029,0.703,0.044,1.047,0.044c0.392,0,0.769-0.017,1.192,0.014
							c0.127,0.859,0.344,1.561,0.644,2.084c0.256,0.449,0.675,0.782,1.18,0.939c1.078,0.335,4.454,1.895,4.867,8.178
							c-2.189,0.507-4.948,0.785-7.882,0.785c-2.933,0-5.692-0.278-7.882-0.786C20.364,13.598,23.741,12.039,24.819,11.704z
							 M35.841,51.557l-0.006,0.11c0,0.513-2.093,2-8.501,2c-5.934,0-7.502-1.309-7.502-2l-0.006-0.11
							c-0.327-2.938-0.163-7.161,0.081-10.638c2.269,0.483,5.029,0.748,7.927,0.748s5.658-0.265,7.927-0.748
							C36.004,44.394,36.168,48.615,35.841,51.557z"/>
						<path d="M27.646,4.87c0.553,0,1-0.447,1-1s-0.447-1-1-1h-1c-0.553,0-1,0.447-1,1s0.447,1,1,1H27.646z"/>
						<path d="M26.295,49.658c-1.886,0.074-2.565-0.367-2.768-0.563c-0.176-0.169-0.194-0.327-0.199-0.327c0,0,0,0.001-0.001,0.003
							l0.007-1.104c0-0.553-0.447-1-1-1s-1,0.447-1,1v0.948c-0.031,0.472,0.13,1.198,0.691,1.805c0.768,0.828,2.064,1.246,3.86,1.246
							c0.158,0,0.321-0.003,0.487-0.01c0.552-0.021,0.981-0.486,0.96-1.038C27.311,50.065,26.804,49.635,26.295,49.658z"/>
						<path d="M22.334,45.667c0.553,0,1-0.447,1-1v-1.01c0-0.553-0.447-1-1-1s-1,0.447-1,1v1.01
							C21.334,45.22,21.781,45.667,22.334,45.667z"/>
						<path d="M23.157,18.651c0.061,0.011,0.119,0.016,0.178,0.016c0.476,0,0.896-0.34,0.983-0.823c0.399-2.233,2.371-3.236,2.455-3.278
							c0.494-0.242,0.7-0.838,0.461-1.334c-0.24-0.495-0.834-0.703-1.336-0.465c-0.12,0.059-2.966,1.468-3.549,4.724
							C22.252,18.034,22.614,18.554,23.157,18.651z"/>

					</svg>
					<br>
					Colisage : 
					<span class="colisage_modal">0</span>
	        		</div>
   				</div>
	        	
	        	<div class="col-6">
	        		<img class="img_article_modal"
	        		src="#" style="width:80%;">
	        	</div>
	        
	        </div>
	       
			  <div class="row">
				  <div class="col-6">
				  	   <div class="row">
				  	   	    <div class="col-5">
									
									    

<svg id="check_colisage_for_calc" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="90px" height="120px"
	 viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">

	<polygon style="fill:#A98258;" points="48,0 10,0 0,16 0,58 58,58 58,16 	"/>
	<polygon style="fill:#DAAE86;" points="10,0 0,16 58,16 48,0 	"/>
	<polygon style="fill:#D8B18B;" points="33,54 29,50 25,54 23,52 23,58 35,58 35,52 	"/>
	<rect x="20" y="30" style="fill:#E8D5B2;" width="18" height="16"/>
	
		<path style="fill:#D4C3A5;" d="M30,41h-6c-0.552,0-1,0.447-1,1s0.448,1,1,1h6c0.552,0,1-0.447,1-1S30.552,41,30,41z"/>
		<path style="fill:#D4C3A5;" d="M34,41h-1c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S34.552,41,34,41z"/>
		<path style="fill:#D4C3A5;" d="M28,35h6c0.552,0,1-0.447,1-1s-0.448-1-1-1h-6c-0.552,0-1,0.447-1,1S27.448,35,28,35z"/>
		<path style="fill:#D4C3A5;" d="M34,37h-2c-0.552,0-1,0.447-1,1s0.448,1,1,1h2c0.552,0,1-0.447,1-1S34.552,37,34,37z"/>
		<path style="fill:#D4C3A5;" d="M24,39h2c0.552,0,1-0.447,1-1s-0.448-1-1-1h-2c-0.552,0-1,0.447-1,1S23.448,39,24,39z"/>
		<path style="fill:#D4C3A5;" d="M24,35h1c0.552,0,1-0.447,1-1s-0.448-1-1-1h-1c-0.552,0-1,0.447-1,1S23.448,35,24,35z"/>
		<path style="fill:#D4C3A5;" d="M28.29,37.29C28.11,37.479,28,37.729,28,38c0,0.27,0.11,0.52,0.29,0.71C28.48,38.89,28.74,39,29,39
			c0.26,0,0.52-0.11,0.71-0.29C29.89,38.52,30,38.26,30,38s-0.11-0.521-0.29-0.71C29.33,36.92,28.66,36.92,28.29,37.29z"/>
	
	<rect x="23" style="fill:#F4D5BD;" width="12" height="16"/>
	<polygon style="fill:#D8B18B;" points="25,21 29,25 33,21 35,23 35,16 23,16 23,23 	"/>
    </svg>
							</div>
						     <div class="col-3">
	                             <div id="input_colisage">0</div>	
	                            
	                         </div>
	                         <div class="operatores col-4">                            
									<svg  version="1.1" id="add_more_colisage" xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                                viewBox="0 0 42 42" style="enable-background:new 0 0 42 42;" xml:space="preserve">
									<path style="fill:#23A24D;" d="M37.059,16H26V4.941C26,2.224,23.718,0,21,0s-5,2.224-5,4.941V16H4.941C2.224,16,0,18.282,0,21
									s2.224,5,4.941,5H16v11.059C16,39.776,18.282,42,21,42s5-2.224,5-4.941V26h11.059C39.776,26,42,23.718,42,21S39.776,16,37.059,16z"/>
								    </svg>
								    <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="substract_colisage" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve" width="70px" height="70px">

									<path d="M14,6H2C0.896,6,0,6.896,0,8s0.896,2,2,2h12c1.104,0,2-0.896,2-2S15.104,6,14,6z" fill="#D80027"/>
									</svg>

	                             </div>			
					    </div>
					</div>
				  
				   <div class="col-6">
				   		<div class="row">
				  	   	    <div class="col-5">
									
									   

		
									    	
	<svg id="check_piece_for_calc" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="90px" height="120px"
	 viewBox="0 0 55.667 55.667" style="enable-background:new 0 0 55.667 55.667;" xml:space="preserve">
	<path d="M37.73,40.425l0.006-0.001l-0.099-1.227c-0.168-2.07-0.714-4.244-1.717-6.84c-0.283-0.733-0.441-1.561-0.505-2.397
		c-0.141-1.846,0.223-3.743,1.053-4.816c0.162-0.21,0.296-0.444,0.429-0.679c0.557-0.983,0.868-2.16,0.863-3.409l0-0.001l0-0.001
		c-0.001-0.099-0.005-0.19-0.011-0.282l-0.042-1.417l-0.027,0.007c-0.618-7.23-4.872-9.143-6.275-9.59
		c-0.121-0.211-0.288-0.622-0.401-1.382c-0.008-0.057-0.028-0.108-0.04-0.163c1.083-0.361,1.869-0.965,1.869-1.894v-4
		c0-1.722-2.693-2.333-5-2.333s-5,0.611-5,2.333v4c0,0.929,0.786,1.532,1.868,1.893c-0.013,0.054-0.032,0.106-0.04,0.162
		c-0.113,0.764-0.28,1.174-0.438,1.406c-1.369,0.426-5.621,2.338-6.238,9.569l-0.036-0.01l-0.033,1.432
		c-0.005,0.088-0.009,0.176-0.01,0.271c-0.009,1.548,0.45,3,1.292,4.09c0.753,0.974,1.134,2.629,1.091,4.308
		c-0.017,0.672-0.102,1.347-0.257,1.984c-0.078,0.318-0.173,0.627-0.287,0.921c-0.251,0.649-0.473,1.271-0.668,1.872
		c-0.585,1.803-0.923,3.414-1.049,4.968c-0.304,3.761-0.584,8.979-0.197,12.53c0.036,1.812,1.733,3.939,9.501,3.939
		c6.521,0,10.436-1.471,10.5-3.938C38.181,48.545,37.992,44.034,37.73,40.425z M20.155,38.321c0.027-0.184,0.05-0.367,0.084-0.555
		c0.053-0.297,0.123-0.604,0.194-0.911c0.042-0.184,0.078-0.364,0.126-0.552c0.088-0.342,0.197-0.699,0.307-1.057
		c0.051-0.167,0.093-0.327,0.149-0.498c0.176-0.536,0.373-1.09,0.597-1.67c0.811-2.1,0.867-4.671,0.288-6.76
		c-0.255-0.918-0.619-1.752-1.118-2.397c-0.006-0.008-0.011-0.018-0.017-0.026c-0.12-0.158-0.225-0.331-0.321-0.511
		c-0.03-0.057-0.057-0.116-0.084-0.174c-0.071-0.15-0.133-0.307-0.188-0.469c-0.019-0.056-0.04-0.111-0.057-0.168
		c-0.06-0.204-0.106-0.417-0.14-0.635c2.253,0.473,4.987,0.73,7.859,0.73c2.872,0,5.605-0.257,7.858-0.729
		c-0.034,0.219-0.081,0.431-0.141,0.635c-0.017,0.057-0.038,0.112-0.057,0.168c-0.054,0.162-0.117,0.317-0.187,0.467
		c-0.028,0.059-0.055,0.119-0.085,0.176c-0.101,0.19-0.21,0.372-0.337,0.537c-1.021,1.321-1.546,3.387-1.511,5.489
		c0.021,1.261,0.243,2.535,0.681,3.668c0.225,0.582,0.422,1.136,0.598,1.673c0.055,0.167,0.095,0.322,0.145,0.485
		c0.111,0.362,0.221,0.722,0.31,1.067c0.049,0.191,0.085,0.373,0.128,0.56c0.069,0.303,0.138,0.605,0.191,0.898
		c0.036,0.198,0.06,0.39,0.088,0.584c0.028,0.19,0.051,0.38,0.072,0.567c-2.17,0.489-4.884,0.754-7.753,0.754
		c-2.869,0-5.583-0.266-7.753-0.754C20.102,38.717,20.126,38.52,20.155,38.321z M24.834,2.551C25.15,2.354,26.145,2,27.834,2
		s2.684,0.354,3,0.551v3.564c-0.258,0.16-0.977,0.424-2.15,0.516c-0.553,0.04-1.149,0.04-1.7,0c-1.172-0.092-1.892-0.356-2.15-0.516
		V2.551z M24.819,11.704c0.504-0.157,0.922-0.491,1.177-0.938c0.3-0.522,0.517-1.224,0.708-2.147
		c0.026,0.002,0.056,0.001,0.083,0.003c0.351,0.029,0.703,0.044,1.047,0.044c0.392,0,0.769-0.017,1.192,0.014
		c0.127,0.859,0.344,1.561,0.644,2.084c0.256,0.449,0.675,0.782,1.18,0.939c1.078,0.335,4.454,1.895,4.867,8.178
		c-2.189,0.507-4.948,0.785-7.882,0.785c-2.933,0-5.692-0.278-7.882-0.786C20.364,13.598,23.741,12.039,24.819,11.704z
		 M35.841,51.557l-0.006,0.11c0,0.513-2.093,2-8.501,2c-5.934,0-7.502-1.309-7.502-2l-0.006-0.11
		c-0.327-2.938-0.163-7.161,0.081-10.638c2.269,0.483,5.029,0.748,7.927,0.748s5.658-0.265,7.927-0.748
		C36.004,44.394,36.168,48.615,35.841,51.557z"/>
	<path d="M27.646,4.87c0.553,0,1-0.447,1-1s-0.447-1-1-1h-1c-0.553,0-1,0.447-1,1s0.447,1,1,1H27.646z"/>
	<path d="M26.295,49.658c-1.886,0.074-2.565-0.367-2.768-0.563c-0.176-0.169-0.194-0.327-0.199-0.327c0,0,0,0.001-0.001,0.003
		l0.007-1.104c0-0.553-0.447-1-1-1s-1,0.447-1,1v0.948c-0.031,0.472,0.13,1.198,0.691,1.805c0.768,0.828,2.064,1.246,3.86,1.246
		c0.158,0,0.321-0.003,0.487-0.01c0.552-0.021,0.981-0.486,0.96-1.038C27.311,50.065,26.804,49.635,26.295,49.658z"/>
	<path d="M22.334,45.667c0.553,0,1-0.447,1-1v-1.01c0-0.553-0.447-1-1-1s-1,0.447-1,1v1.01
		C21.334,45.22,21.781,45.667,22.334,45.667z"/>
	<path d="M23.157,18.651c0.061,0.011,0.119,0.016,0.178,0.016c0.476,0,0.896-0.34,0.983-0.823c0.399-2.233,2.371-3.236,2.455-3.278
		c0.494-0.242,0.7-0.838,0.461-1.334c-0.24-0.495-0.834-0.703-1.336-0.465c-0.12,0.059-2.966,1.468-3.549,4.724
		C22.252,18.034,22.614,18.554,23.157,18.651z"/>
    </svg>

							</div>
						     <div class="col-3">
							    <div id="input_piece">0</div>

						     </div>
						     <div class="operatores col-4">
							    	<svg version="1.1" id="add_more_pieces" xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                                viewBox="0 0 42 42" style="enable-background:new 0 0 42 42;" xml:space="preserve">
									<path style="fill:#23A24D;" d="M37.059,16H26V4.941C26,2.224,23.718,0,21,0s-5,2.224-5,4.941V16H4.941C2.224,16,0,18.282,0,21
									s2.224,5,4.941,5H16v11.059C16,39.776,18.282,42,21,42s5-2.224,5-4.941V26h11.059C39.776,26,42,23.718,42,21S39.776,16,37.059,16z"/>
								    </svg>
	                             	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="substract_pieces" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve" width="70px" height="70px">

									<path d="M14,6H2C0.896,6,0,6.896,0,8s0.896,2,2,2h12c1.104,0,2-0.896,2-2S15.104,6,14,6z" fill="#D80027"/>
									</svg>
								


	                             </div>
					    </div>
					  
				  </div>
			</div>
			









	      		</div>
	      		<div class="col-4 cal_btns">
	      			




					<div class="row" >
	        			<div class="col-4">
	        				<button id="number1" 
	        				class="btn btn-primary btn-block btn-lg align-middle">1</button>
	        			</div>
	        			<div class="col-4">
	        				<button  id="number2" 
	        				class="btn btn-primary btn-block btn-lg">2</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number3"
	        				class="btn btn-primary btn-block btn-lg">3</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				<button id="number4"
	        				class="btn btn-primary btn-block btn-lg">4</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number5"
	        				class="btn btn-primary btn-block btn-lg">5</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number6"
	        				class="btn btn-primary btn-block btn-lg">6</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				<button id="number7"
	        				class="btn btn-primary btn-block btn-lg">7</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number8"
	        				class="btn btn-primary btn-block btn-lg">8</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number9"
	        				class="btn btn-primary btn-block btn-lg">9</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				
	        			</div>
	        			<div class="col-4">
	        				<button id="number0"
	        				class="btn btn-primary btn-block btn-lg">0</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="numberx"
	        				class="btn btn-primary btn-block btn-lg">&times;</button>
	        			</div>
	        		</div>
	        	
	      		</div>
	     	</div>
	     	<div class="row">
	        			<br><br>
	        			<div class="col-12" id="ecart_modal" style="color:#c0392b;font-weight: bold;">écart 
	        				
	        				<span id="ecart_pieces">-1</span> pièces 
	        			</div>
	        		  </div>
	     	<div class="row">
				   <div class="col-6">
				   		
					  <div class="form-group">
					  	<br>
					    <button class="form-control btn btn-success large_button" 
					     id="give_new_data_to_xml_ref_modal">
					    	<span>Confirmer</span> 
					    	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 45.102 45.102" style="enable-background:new 0 0 45.102 45.102;" xml:space="preserve">

							<path d="M17.021,44.403c0.427,0.45,0.995,0.698,1.601,0.698c0.641,0,1.25-0.287,1.672-0.791    c8.155-9.735,19.136-21.879,23.248-24.062c0.906-0.482,1.515-1.456,1.515-2.423V1.933C45.056,0.678,44.137,0,43.272,0    c-0.314,0-0.795,0.086-1.283,0.494C38.922,3.05,24.179,15.795,19.271,28.458l-4.691-7.624c-0.63-1.02-2.088-1.386-3.122-0.783    L1.102,26.075c-0.599,0.349-0.981,0.924-1.046,1.579c-0.065,0.654,0.194,1.294,0.714,1.755    C2.909,31.309,13.747,40.952,17.021,44.403z M12.378,22.984l5.636,9.16c0.378,0.613,0.96,0.965,1.597,0.965    c0.613,0,1.437-0.375,1.736-1.432c3.091-10.901,15.443-22.661,20.707-27.29v13.253c-5.462,2.98-18.68,18.348-23.482,24.054    c-3.429-3.461-11.281-10.499-14.85-13.674L12.378,22.984z" fill="#FFFFFF"/>

							</svg>
					    </button>
					  </div>
				  </div>
				   <div class="col-6">
	                  <div class="form-group">
					  	<br>
					    <button class="form-control btn btn-danger large_button" 
					     data-dismiss="modal">
					     Annuler
					     <svg width="50px" height="50px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                     viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
						<polygon style="fill:#FFFFFF;" points="404.176,0 256,148.176 107.824,0 0,107.824 148.176,256 0,404.176 107.824,512 256,363.824 
						404.176,512 512,404.176 363.824,256 512,107.824 "/>
						</svg>
					    </button>
					  </div>
				  </div>
			  </div>
	      </div>
	    
	    </div>

	  </div>
	</div>
	
	<div id="myModal_design" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
	  <div class="modal-dialog modal-lg modal-ku">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	      	<h4 class="modal-title" id="ref_design_select_design_modal">
	      	</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row">
	        <div class="col-8">
	      		
	        		 <div class="row">
	        	<div class="col-6">
	        		<div id="gamme_marque_select_design_modal">
	        		
	        	    </div>
	        	  		<hr>
	        		<div id="total_existant" style="font-weight: bold">Stock Théorique : <br>
	        			Boîtes : 
	        			<span class="total_boites">25</span> 
	        			<svg width="20" height="20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">

						<polygon style="fill:#A98258;" points="48,0 10,0 0,16 0,58 58,58 58,16 	"/>
						<polygon style="fill:#DAAE86;" points="10,0 0,16 58,16 48,0 	"/>
						<polygon style="fill:#D8B18B;" points="33,54 29,50 25,54 23,52 23,58 35,58 35,52 	"/>
						<rect x="20" y="30" style="fill:#E8D5B2;" width="18" height="16"/>
						<g>
							<path style="fill:#D4C3A5;" d="M30,41h-6c-0.552,0-1,0.447-1,1s0.448,1,1,1h6c0.552,0,1-0.447,1-1S30.552,41,30,41z"/>
							<path style="fill:#D4C3A5;" d="M34,41h-1c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S34.552,41,34,41z"/>
							<path style="fill:#D4C3A5;" d="M28,35h6c0.552,0,1-0.447,1-1s-0.448-1-1-1h-6c-0.552,0-1,0.447-1,1S27.448,35,28,35z"/>
							<path style="fill:#D4C3A5;" d="M34,37h-2c-0.552,0-1,0.447-1,1s0.448,1,1,1h2c0.552,0,1-0.447,1-1S34.552,37,34,37z"/>
							<path style="fill:#D4C3A5;" d="M24,39h2c0.552,0,1-0.447,1-1s-0.448-1-1-1h-2c-0.552,0-1,0.447-1,1S23.448,39,24,39z"/>
							<path style="fill:#D4C3A5;" d="M24,35h1c0.552,0,1-0.447,1-1s-0.448-1-1-1h-1c-0.552,0-1,0.447-1,1S23.448,35,24,35z"/>
							<path style="fill:#D4C3A5;" d="M28.29,37.29C28.11,37.479,28,37.729,28,38c0,0.27,0.11,0.52,0.29,0.71C28.48,38.89,28.74,39,29,39
								c0.26,0,0.52-0.11,0.71-0.29C29.89,38.52,30,38.26,30,38s-0.11-0.521-0.29-0.71C29.33,36.92,28.66,36.92,28.29,37.29z"/>
						</g>
						<rect x="23" style="fill:#F4D5BD;" width="12" height="16"/>
						<polygon style="fill:#D8B18B;" points="25,21 29,25 33,21 35,23 35,16 23,16 23,23 	"/>

					    </svg>
						<br>
						Pièces :  
	        			<span class="total_pieces">20</span> 
	        			<svg width="20" height="20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 55.667 55.667" style="enable-background:new 0 0 55.667 55.667;" xml:space="preserve">

						<path d="M37.73,40.425l0.006-0.001l-0.099-1.227c-0.168-2.07-0.714-4.244-1.717-6.84c-0.283-0.733-0.441-1.561-0.505-2.397
							c-0.141-1.846,0.223-3.743,1.053-4.816c0.162-0.21,0.296-0.444,0.429-0.679c0.557-0.983,0.868-2.16,0.863-3.409l0-0.001l0-0.001
							c-0.001-0.099-0.005-0.19-0.011-0.282l-0.042-1.417l-0.027,0.007c-0.618-7.23-4.872-9.143-6.275-9.59
							c-0.121-0.211-0.288-0.622-0.401-1.382c-0.008-0.057-0.028-0.108-0.04-0.163c1.083-0.361,1.869-0.965,1.869-1.894v-4
							c0-1.722-2.693-2.333-5-2.333s-5,0.611-5,2.333v4c0,0.929,0.786,1.532,1.868,1.893c-0.013,0.054-0.032,0.106-0.04,0.162
							c-0.113,0.764-0.28,1.174-0.438,1.406c-1.369,0.426-5.621,2.338-6.238,9.569l-0.036-0.01l-0.033,1.432
							c-0.005,0.088-0.009,0.176-0.01,0.271c-0.009,1.548,0.45,3,1.292,4.09c0.753,0.974,1.134,2.629,1.091,4.308
							c-0.017,0.672-0.102,1.347-0.257,1.984c-0.078,0.318-0.173,0.627-0.287,0.921c-0.251,0.649-0.473,1.271-0.668,1.872
							c-0.585,1.803-0.923,3.414-1.049,4.968c-0.304,3.761-0.584,8.979-0.197,12.53c0.036,1.812,1.733,3.939,9.501,3.939
							c6.521,0,10.436-1.471,10.5-3.938C38.181,48.545,37.992,44.034,37.73,40.425z M20.155,38.321c0.027-0.184,0.05-0.367,0.084-0.555
							c0.053-0.297,0.123-0.604,0.194-0.911c0.042-0.184,0.078-0.364,0.126-0.552c0.088-0.342,0.197-0.699,0.307-1.057
							c0.051-0.167,0.093-0.327,0.149-0.498c0.176-0.536,0.373-1.09,0.597-1.67c0.811-2.1,0.867-4.671,0.288-6.76
							c-0.255-0.918-0.619-1.752-1.118-2.397c-0.006-0.008-0.011-0.018-0.017-0.026c-0.12-0.158-0.225-0.331-0.321-0.511
							c-0.03-0.057-0.057-0.116-0.084-0.174c-0.071-0.15-0.133-0.307-0.188-0.469c-0.019-0.056-0.04-0.111-0.057-0.168
							c-0.06-0.204-0.106-0.417-0.14-0.635c2.253,0.473,4.987,0.73,7.859,0.73c2.872,0,5.605-0.257,7.858-0.729
							c-0.034,0.219-0.081,0.431-0.141,0.635c-0.017,0.057-0.038,0.112-0.057,0.168c-0.054,0.162-0.117,0.317-0.187,0.467
							c-0.028,0.059-0.055,0.119-0.085,0.176c-0.101,0.19-0.21,0.372-0.337,0.537c-1.021,1.321-1.546,3.387-1.511,5.489
							c0.021,1.261,0.243,2.535,0.681,3.668c0.225,0.582,0.422,1.136,0.598,1.673c0.055,0.167,0.095,0.322,0.145,0.485
							c0.111,0.362,0.221,0.722,0.31,1.067c0.049,0.191,0.085,0.373,0.128,0.56c0.069,0.303,0.138,0.605,0.191,0.898
							c0.036,0.198,0.06,0.39,0.088,0.584c0.028,0.19,0.051,0.38,0.072,0.567c-2.17,0.489-4.884,0.754-7.753,0.754
							c-2.869,0-5.583-0.266-7.753-0.754C20.102,38.717,20.126,38.52,20.155,38.321z M24.834,2.551C25.15,2.354,26.145,2,27.834,2
							s2.684,0.354,3,0.551v3.564c-0.258,0.16-0.977,0.424-2.15,0.516c-0.553,0.04-1.149,0.04-1.7,0c-1.172-0.092-1.892-0.356-2.15-0.516
							V2.551z M24.819,11.704c0.504-0.157,0.922-0.491,1.177-0.938c0.3-0.522,0.517-1.224,0.708-2.147
							c0.026,0.002,0.056,0.001,0.083,0.003c0.351,0.029,0.703,0.044,1.047,0.044c0.392,0,0.769-0.017,1.192,0.014
							c0.127,0.859,0.344,1.561,0.644,2.084c0.256,0.449,0.675,0.782,1.18,0.939c1.078,0.335,4.454,1.895,4.867,8.178
							c-2.189,0.507-4.948,0.785-7.882,0.785c-2.933,0-5.692-0.278-7.882-0.786C20.364,13.598,23.741,12.039,24.819,11.704z
							 M35.841,51.557l-0.006,0.11c0,0.513-2.093,2-8.501,2c-5.934,0-7.502-1.309-7.502-2l-0.006-0.11
							c-0.327-2.938-0.163-7.161,0.081-10.638c2.269,0.483,5.029,0.748,7.927,0.748s5.658-0.265,7.927-0.748
							C36.004,44.394,36.168,48.615,35.841,51.557z"/>
						<path d="M27.646,4.87c0.553,0,1-0.447,1-1s-0.447-1-1-1h-1c-0.553,0-1,0.447-1,1s0.447,1,1,1H27.646z"/>
						<path d="M26.295,49.658c-1.886,0.074-2.565-0.367-2.768-0.563c-0.176-0.169-0.194-0.327-0.199-0.327c0,0,0,0.001-0.001,0.003
							l0.007-1.104c0-0.553-0.447-1-1-1s-1,0.447-1,1v0.948c-0.031,0.472,0.13,1.198,0.691,1.805c0.768,0.828,2.064,1.246,3.86,1.246
							c0.158,0,0.321-0.003,0.487-0.01c0.552-0.021,0.981-0.486,0.96-1.038C27.311,50.065,26.804,49.635,26.295,49.658z"/>
						<path d="M22.334,45.667c0.553,0,1-0.447,1-1v-1.01c0-0.553-0.447-1-1-1s-1,0.447-1,1v1.01
							C21.334,45.22,21.781,45.667,22.334,45.667z"/>
						<path d="M23.157,18.651c0.061,0.011,0.119,0.016,0.178,0.016c0.476,0,0.896-0.34,0.983-0.823c0.399-2.233,2.371-3.236,2.455-3.278
							c0.494-0.242,0.7-0.838,0.461-1.334c-0.24-0.495-0.834-0.703-1.336-0.465c-0.12,0.059-2.966,1.468-3.549,4.724
							C22.252,18.034,22.614,18.554,23.157,18.651z"/>

					</svg>
					<br>
					Colisage : 
					<span class="colisage_modal">0</span>
	        		</div>
   			

	        	</div>
	        	
	        	<div class="col-6">
	        		<img class="img_article_modal_design" src="#" 
	        		style="height: 200px;">
	        	</div>
	        
	        </div>
	        
			  <div class="row">
				  <div class="col-6">
				  	   <div class="row">
				  	   	    <div class="col-5">
									
									    

<svg id="check_colisage_for_calc" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="90px" height="120px"
	 viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">

	<polygon style="fill:#A98258;" points="48,0 10,0 0,16 0,58 58,58 58,16 	"/>
	<polygon style="fill:#DAAE86;" points="10,0 0,16 58,16 48,0 	"/>
	<polygon style="fill:#D8B18B;" points="33,54 29,50 25,54 23,52 23,58 35,58 35,52 	"/>
	<rect x="20" y="30" style="fill:#E8D5B2;" width="18" height="16"/>
	
		<path style="fill:#D4C3A5;" d="M30,41h-6c-0.552,0-1,0.447-1,1s0.448,1,1,1h6c0.552,0,1-0.447,1-1S30.552,41,30,41z"/>
		<path style="fill:#D4C3A5;" d="M34,41h-1c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S34.552,41,34,41z"/>
		<path style="fill:#D4C3A5;" d="M28,35h6c0.552,0,1-0.447,1-1s-0.448-1-1-1h-6c-0.552,0-1,0.447-1,1S27.448,35,28,35z"/>
		<path style="fill:#D4C3A5;" d="M34,37h-2c-0.552,0-1,0.447-1,1s0.448,1,1,1h2c0.552,0,1-0.447,1-1S34.552,37,34,37z"/>
		<path style="fill:#D4C3A5;" d="M24,39h2c0.552,0,1-0.447,1-1s-0.448-1-1-1h-2c-0.552,0-1,0.447-1,1S23.448,39,24,39z"/>
		<path style="fill:#D4C3A5;" d="M24,35h1c0.552,0,1-0.447,1-1s-0.448-1-1-1h-1c-0.552,0-1,0.447-1,1S23.448,35,24,35z"/>
		<path style="fill:#D4C3A5;" d="M28.29,37.29C28.11,37.479,28,37.729,28,38c0,0.27,0.11,0.52,0.29,0.71C28.48,38.89,28.74,39,29,39
			c0.26,0,0.52-0.11,0.71-0.29C29.89,38.52,30,38.26,30,38s-0.11-0.521-0.29-0.71C29.33,36.92,28.66,36.92,28.29,37.29z"/>
	
	<rect x="23" style="fill:#F4D5BD;" width="12" height="16"/>
	<polygon style="fill:#D8B18B;" points="25,21 29,25 33,21 35,23 35,16 23,16 23,23 	"/>
    </svg>
							</div>
						     <div class="col-3">
	                             <div id="input_colisage">0</div>
	                               				
	                         </div>
	                         <div class="operatores col-4">
	                             	<svg  version="1.1" id="add_more_colisage" xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                                viewBox="0 0 42 42" style="enable-background:new 0 0 42 42;" xml:space="preserve">
									<path style="fill:#23A24D;" d="M37.059,16H26V4.941C26,2.224,23.718,0,21,0s-5,2.224-5,4.941V16H4.941C2.224,16,0,18.282,0,21
									s2.224,5,4.941,5H16v11.059C16,39.776,18.282,42,21,42s5-2.224,5-4.941V26h11.059C39.776,26,42,23.718,42,21S39.776,16,37.059,16z"/>
								    </svg>
	                             	<svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="substract_colisage" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve" width="70px" height="70px">

									<path d="M14,6H2C0.896,6,0,6.896,0,8s0.896,2,2,2h12c1.104,0,2-0.896,2-2S15.104,6,14,6z" fill="#D80027"/>
									</svg>
								


	                             </div>			
					    </div>
					</div>
				  
				   <div class="col-6">
				   		<div class="row">
				  	   	    <div class="col-5">
									
									   

		
									    	
	<svg id="check_piece_for_calc" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="90px" height="120px"
	 viewBox="0 0 55.667 55.667" style="enable-background:new 0 0 55.667 55.667;" xml:space="preserve">
	<path d="M37.73,40.425l0.006-0.001l-0.099-1.227c-0.168-2.07-0.714-4.244-1.717-6.84c-0.283-0.733-0.441-1.561-0.505-2.397
		c-0.141-1.846,0.223-3.743,1.053-4.816c0.162-0.21,0.296-0.444,0.429-0.679c0.557-0.983,0.868-2.16,0.863-3.409l0-0.001l0-0.001
		c-0.001-0.099-0.005-0.19-0.011-0.282l-0.042-1.417l-0.027,0.007c-0.618-7.23-4.872-9.143-6.275-9.59
		c-0.121-0.211-0.288-0.622-0.401-1.382c-0.008-0.057-0.028-0.108-0.04-0.163c1.083-0.361,1.869-0.965,1.869-1.894v-4
		c0-1.722-2.693-2.333-5-2.333s-5,0.611-5,2.333v4c0,0.929,0.786,1.532,1.868,1.893c-0.013,0.054-0.032,0.106-0.04,0.162
		c-0.113,0.764-0.28,1.174-0.438,1.406c-1.369,0.426-5.621,2.338-6.238,9.569l-0.036-0.01l-0.033,1.432
		c-0.005,0.088-0.009,0.176-0.01,0.271c-0.009,1.548,0.45,3,1.292,4.09c0.753,0.974,1.134,2.629,1.091,4.308
		c-0.017,0.672-0.102,1.347-0.257,1.984c-0.078,0.318-0.173,0.627-0.287,0.921c-0.251,0.649-0.473,1.271-0.668,1.872
		c-0.585,1.803-0.923,3.414-1.049,4.968c-0.304,3.761-0.584,8.979-0.197,12.53c0.036,1.812,1.733,3.939,9.501,3.939
		c6.521,0,10.436-1.471,10.5-3.938C38.181,48.545,37.992,44.034,37.73,40.425z M20.155,38.321c0.027-0.184,0.05-0.367,0.084-0.555
		c0.053-0.297,0.123-0.604,0.194-0.911c0.042-0.184,0.078-0.364,0.126-0.552c0.088-0.342,0.197-0.699,0.307-1.057
		c0.051-0.167,0.093-0.327,0.149-0.498c0.176-0.536,0.373-1.09,0.597-1.67c0.811-2.1,0.867-4.671,0.288-6.76
		c-0.255-0.918-0.619-1.752-1.118-2.397c-0.006-0.008-0.011-0.018-0.017-0.026c-0.12-0.158-0.225-0.331-0.321-0.511
		c-0.03-0.057-0.057-0.116-0.084-0.174c-0.071-0.15-0.133-0.307-0.188-0.469c-0.019-0.056-0.04-0.111-0.057-0.168
		c-0.06-0.204-0.106-0.417-0.14-0.635c2.253,0.473,4.987,0.73,7.859,0.73c2.872,0,5.605-0.257,7.858-0.729
		c-0.034,0.219-0.081,0.431-0.141,0.635c-0.017,0.057-0.038,0.112-0.057,0.168c-0.054,0.162-0.117,0.317-0.187,0.467
		c-0.028,0.059-0.055,0.119-0.085,0.176c-0.101,0.19-0.21,0.372-0.337,0.537c-1.021,1.321-1.546,3.387-1.511,5.489
		c0.021,1.261,0.243,2.535,0.681,3.668c0.225,0.582,0.422,1.136,0.598,1.673c0.055,0.167,0.095,0.322,0.145,0.485
		c0.111,0.362,0.221,0.722,0.31,1.067c0.049,0.191,0.085,0.373,0.128,0.56c0.069,0.303,0.138,0.605,0.191,0.898
		c0.036,0.198,0.06,0.39,0.088,0.584c0.028,0.19,0.051,0.38,0.072,0.567c-2.17,0.489-4.884,0.754-7.753,0.754
		c-2.869,0-5.583-0.266-7.753-0.754C20.102,38.717,20.126,38.52,20.155,38.321z M24.834,2.551C25.15,2.354,26.145,2,27.834,2
		s2.684,0.354,3,0.551v3.564c-0.258,0.16-0.977,0.424-2.15,0.516c-0.553,0.04-1.149,0.04-1.7,0c-1.172-0.092-1.892-0.356-2.15-0.516
		V2.551z M24.819,11.704c0.504-0.157,0.922-0.491,1.177-0.938c0.3-0.522,0.517-1.224,0.708-2.147
		c0.026,0.002,0.056,0.001,0.083,0.003c0.351,0.029,0.703,0.044,1.047,0.044c0.392,0,0.769-0.017,1.192,0.014
		c0.127,0.859,0.344,1.561,0.644,2.084c0.256,0.449,0.675,0.782,1.18,0.939c1.078,0.335,4.454,1.895,4.867,8.178
		c-2.189,0.507-4.948,0.785-7.882,0.785c-2.933,0-5.692-0.278-7.882-0.786C20.364,13.598,23.741,12.039,24.819,11.704z
		 M35.841,51.557l-0.006,0.11c0,0.513-2.093,2-8.501,2c-5.934,0-7.502-1.309-7.502-2l-0.006-0.11
		c-0.327-2.938-0.163-7.161,0.081-10.638c2.269,0.483,5.029,0.748,7.927,0.748s5.658-0.265,7.927-0.748
		C36.004,44.394,36.168,48.615,35.841,51.557z"/>
	<path d="M27.646,4.87c0.553,0,1-0.447,1-1s-0.447-1-1-1h-1c-0.553,0-1,0.447-1,1s0.447,1,1,1H27.646z"/>
	<path d="M26.295,49.658c-1.886,0.074-2.565-0.367-2.768-0.563c-0.176-0.169-0.194-0.327-0.199-0.327c0,0,0,0.001-0.001,0.003
		l0.007-1.104c0-0.553-0.447-1-1-1s-1,0.447-1,1v0.948c-0.031,0.472,0.13,1.198,0.691,1.805c0.768,0.828,2.064,1.246,3.86,1.246
		c0.158,0,0.321-0.003,0.487-0.01c0.552-0.021,0.981-0.486,0.96-1.038C27.311,50.065,26.804,49.635,26.295,49.658z"/>
	<path d="M22.334,45.667c0.553,0,1-0.447,1-1v-1.01c0-0.553-0.447-1-1-1s-1,0.447-1,1v1.01
		C21.334,45.22,21.781,45.667,22.334,45.667z"/>
	<path d="M23.157,18.651c0.061,0.011,0.119,0.016,0.178,0.016c0.476,0,0.896-0.34,0.983-0.823c0.399-2.233,2.371-3.236,2.455-3.278
		c0.494-0.242,0.7-0.838,0.461-1.334c-0.24-0.495-0.834-0.703-1.336-0.465c-0.12,0.059-2.966,1.468-3.549,4.724
		C22.252,18.034,22.614,18.554,23.157,18.651z"/>
    </svg>

							</div>
						     <div class="col-3">
							    <div id="input_piece">0</div>

						     </div>
						     <div class="operatores col-4">
	                             	<svg version="1.1" id="add_more_pieces" xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                                viewBox="0 0 42 42" style="enable-background:new 0 0 42 42;" xml:space="preserve">
									<path style="fill:#23A24D;" d="M37.059,16H26V4.941C26,2.224,23.718,0,21,0s-5,2.224-5,4.941V16H4.941C2.224,16,0,18.282,0,21
									s2.224,5,4.941,5H16v11.059C16,39.776,18.282,42,21,42s5-2.224,5-4.941V26h11.059C39.776,26,42,23.718,42,21S39.776,16,37.059,16z"/>
								    </svg>
	                             	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="substract_pieces" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve" width="70px" height="70px">

									<path d="M14,6H2C0.896,6,0,6.896,0,8s0.896,2,2,2h12c1.104,0,2-0.896,2-2S15.104,6,14,6z" fill="#D80027"/>
									</svg>
									


	                             </div>	
					    </div>
					  
				  </div>
			</div>
			
		
				 
	      
	      </div>
	    	<div class="col-4 cal_btns">
	        		<div class="row">
	        			<div class="col-4">
	        				<button id="number1" 
	        				class="btn btn-primary btn-block">1</button>
	        			</div>
	        			<div class="col-4">
	        				<button  id="number2" 
	        				class="btn btn-primary btn-block">2</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number3"
	        				class="btn btn-primary btn-block">3</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				<button id="number4"
	        				class="btn btn-primary btn-block">4</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number5"
	        				class="btn btn-primary btn-block">5</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number6"
	        				class="btn btn-primary btn-block">6</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				<button id="number7"
	        				class="btn btn-primary btn-block">7</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number8"
	        				class="btn btn-primary btn-block">8</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number9"
	        				class="btn btn-primary btn-block">9</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				
	        			</div>
	        			<div class="col-4">
	        				<button id="number0"
	        				class="btn btn-primary btn-block">0</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="numberx"
	        				class="btn btn-primary btn-block">&times;</button>
	        			</div>
	        		</div>
	        	
	        </div>
	       </div>
	        <div class="col-6">
				   	    <div class="row">
	        			<br><br>
	        			<div class="col-12" id="ecart_modal" style="color: #c0392b;font-weight: bold;">écart 
	        				
	        				<span id="ecart_pieces">-1</span> pièces 
	        			</div>
	        		</div>
                 </div>
	                 	<div class="row">
				   <div class="col-6">
					  <div class="form-group">
					  	<br>
					    <button class="form-control btn btn-success large_button" 
					     id="give_new_data_to_xml_design_modal">
					     Confirmer
					     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 45.102 45.102" style="enable-background:new 0 0 45.102 45.102;" xml:space="preserve">

							<path d="M17.021,44.403c0.427,0.45,0.995,0.698,1.601,0.698c0.641,0,1.25-0.287,1.672-0.791    c8.155-9.735,19.136-21.879,23.248-24.062c0.906-0.482,1.515-1.456,1.515-2.423V1.933C45.056,0.678,44.137,0,43.272,0    c-0.314,0-0.795,0.086-1.283,0.494C38.922,3.05,24.179,15.795,19.271,28.458l-4.691-7.624c-0.63-1.02-2.088-1.386-3.122-0.783    L1.102,26.075c-0.599,0.349-0.981,0.924-1.046,1.579c-0.065,0.654,0.194,1.294,0.714,1.755    C2.909,31.309,13.747,40.952,17.021,44.403z M12.378,22.984l5.636,9.16c0.378,0.613,0.96,0.965,1.597,0.965    c0.613,0,1.437-0.375,1.736-1.432c3.091-10.901,15.443-22.661,20.707-27.29v13.253c-5.462,2.98-18.68,18.348-23.482,24.054    c-3.429-3.461-11.281-10.499-14.85-13.674L12.378,22.984z" fill="#FFFFFF"/>

							</svg>
						   </button>
					  </div>
                    </div>
                    <div class="col-6">
	                  <div class="form-group">
					  	<br>
					    <button class="form-control btn btn-danger large_button" 
					     data-dismiss="modal">
					     Annuler
					     <svg width="50px" height="50px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                     viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
						<polygon style="fill:#FFFFFF;" points="404.176,0 256,148.176 107.824,0 0,107.824 148.176,256 0,404.176 107.824,512 256,363.824 
						404.176,512 512,404.176 363.824,256 512,107.824 "/>
						</svg>
					    </button>
					  </div>
				  </div>
      
				  </div>
	    
	      </div>
	    </div>

	  </div>
	</div>
	
	
<div id="myModal_cb" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
	  <div class="modal-dialog modal-lg modal-ku">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	      	<h4 class="modal-title" id="ref_design_select_cb_modal"></h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row">
	      		<div class="col-8">
	      			

   <div class="row">
   				<div class="col-6">
   					<div  id="gamme_marque_select_cb_modal">
	        		</div>
	        		<hr>
	        		<div id="total_existant" style="font-weight: bold">Stock Théorique : <br>
	        			Boîtes : 
	        			<span class="total_boites">25</span> 
	        			<svg width="20" height="20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">

						<polygon style="fill:#A98258;" points="48,0 10,0 0,16 0,58 58,58 58,16 	"/>
						<polygon style="fill:#DAAE86;" points="10,0 0,16 58,16 48,0 	"/>
						<polygon style="fill:#D8B18B;" points="33,54 29,50 25,54 23,52 23,58 35,58 35,52 	"/>
						<rect x="20" y="30" style="fill:#E8D5B2;" width="18" height="16"/>
						<g>
							<path style="fill:#D4C3A5;" d="M30,41h-6c-0.552,0-1,0.447-1,1s0.448,1,1,1h6c0.552,0,1-0.447,1-1S30.552,41,30,41z"/>
							<path style="fill:#D4C3A5;" d="M34,41h-1c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S34.552,41,34,41z"/>
							<path style="fill:#D4C3A5;" d="M28,35h6c0.552,0,1-0.447,1-1s-0.448-1-1-1h-6c-0.552,0-1,0.447-1,1S27.448,35,28,35z"/>
							<path style="fill:#D4C3A5;" d="M34,37h-2c-0.552,0-1,0.447-1,1s0.448,1,1,1h2c0.552,0,1-0.447,1-1S34.552,37,34,37z"/>
							<path style="fill:#D4C3A5;" d="M24,39h2c0.552,0,1-0.447,1-1s-0.448-1-1-1h-2c-0.552,0-1,0.447-1,1S23.448,39,24,39z"/>
							<path style="fill:#D4C3A5;" d="M24,35h1c0.552,0,1-0.447,1-1s-0.448-1-1-1h-1c-0.552,0-1,0.447-1,1S23.448,35,24,35z"/>
							<path style="fill:#D4C3A5;" d="M28.29,37.29C28.11,37.479,28,37.729,28,38c0,0.27,0.11,0.52,0.29,0.71C28.48,38.89,28.74,39,29,39
								c0.26,0,0.52-0.11,0.71-0.29C29.89,38.52,30,38.26,30,38s-0.11-0.521-0.29-0.71C29.33,36.92,28.66,36.92,28.29,37.29z"/>
						</g>
						<rect x="23" style="fill:#F4D5BD;" width="12" height="16"/>
						<polygon style="fill:#D8B18B;" points="25,21 29,25 33,21 35,23 35,16 23,16 23,23 	"/>

					    </svg>
 
	        			<br>
	        			Pièces : 
	        			<span class="total_pieces">20</span> 
	        			<svg width="20" height="20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 55.667 55.667" style="enable-background:new 0 0 55.667 55.667;" xml:space="preserve">

						<path d="M37.73,40.425l0.006-0.001l-0.099-1.227c-0.168-2.07-0.714-4.244-1.717-6.84c-0.283-0.733-0.441-1.561-0.505-2.397
							c-0.141-1.846,0.223-3.743,1.053-4.816c0.162-0.21,0.296-0.444,0.429-0.679c0.557-0.983,0.868-2.16,0.863-3.409l0-0.001l0-0.001
							c-0.001-0.099-0.005-0.19-0.011-0.282l-0.042-1.417l-0.027,0.007c-0.618-7.23-4.872-9.143-6.275-9.59
							c-0.121-0.211-0.288-0.622-0.401-1.382c-0.008-0.057-0.028-0.108-0.04-0.163c1.083-0.361,1.869-0.965,1.869-1.894v-4
							c0-1.722-2.693-2.333-5-2.333s-5,0.611-5,2.333v4c0,0.929,0.786,1.532,1.868,1.893c-0.013,0.054-0.032,0.106-0.04,0.162
							c-0.113,0.764-0.28,1.174-0.438,1.406c-1.369,0.426-5.621,2.338-6.238,9.569l-0.036-0.01l-0.033,1.432
							c-0.005,0.088-0.009,0.176-0.01,0.271c-0.009,1.548,0.45,3,1.292,4.09c0.753,0.974,1.134,2.629,1.091,4.308
							c-0.017,0.672-0.102,1.347-0.257,1.984c-0.078,0.318-0.173,0.627-0.287,0.921c-0.251,0.649-0.473,1.271-0.668,1.872
							c-0.585,1.803-0.923,3.414-1.049,4.968c-0.304,3.761-0.584,8.979-0.197,12.53c0.036,1.812,1.733,3.939,9.501,3.939
							c6.521,0,10.436-1.471,10.5-3.938C38.181,48.545,37.992,44.034,37.73,40.425z M20.155,38.321c0.027-0.184,0.05-0.367,0.084-0.555
							c0.053-0.297,0.123-0.604,0.194-0.911c0.042-0.184,0.078-0.364,0.126-0.552c0.088-0.342,0.197-0.699,0.307-1.057
							c0.051-0.167,0.093-0.327,0.149-0.498c0.176-0.536,0.373-1.09,0.597-1.67c0.811-2.1,0.867-4.671,0.288-6.76
							c-0.255-0.918-0.619-1.752-1.118-2.397c-0.006-0.008-0.011-0.018-0.017-0.026c-0.12-0.158-0.225-0.331-0.321-0.511
							c-0.03-0.057-0.057-0.116-0.084-0.174c-0.071-0.15-0.133-0.307-0.188-0.469c-0.019-0.056-0.04-0.111-0.057-0.168
							c-0.06-0.204-0.106-0.417-0.14-0.635c2.253,0.473,4.987,0.73,7.859,0.73c2.872,0,5.605-0.257,7.858-0.729
							c-0.034,0.219-0.081,0.431-0.141,0.635c-0.017,0.057-0.038,0.112-0.057,0.168c-0.054,0.162-0.117,0.317-0.187,0.467
							c-0.028,0.059-0.055,0.119-0.085,0.176c-0.101,0.19-0.21,0.372-0.337,0.537c-1.021,1.321-1.546,3.387-1.511,5.489
							c0.021,1.261,0.243,2.535,0.681,3.668c0.225,0.582,0.422,1.136,0.598,1.673c0.055,0.167,0.095,0.322,0.145,0.485
							c0.111,0.362,0.221,0.722,0.31,1.067c0.049,0.191,0.085,0.373,0.128,0.56c0.069,0.303,0.138,0.605,0.191,0.898
							c0.036,0.198,0.06,0.39,0.088,0.584c0.028,0.19,0.051,0.38,0.072,0.567c-2.17,0.489-4.884,0.754-7.753,0.754
							c-2.869,0-5.583-0.266-7.753-0.754C20.102,38.717,20.126,38.52,20.155,38.321z M24.834,2.551C25.15,2.354,26.145,2,27.834,2
							s2.684,0.354,3,0.551v3.564c-0.258,0.16-0.977,0.424-2.15,0.516c-0.553,0.04-1.149,0.04-1.7,0c-1.172-0.092-1.892-0.356-2.15-0.516
							V2.551z M24.819,11.704c0.504-0.157,0.922-0.491,1.177-0.938c0.3-0.522,0.517-1.224,0.708-2.147
							c0.026,0.002,0.056,0.001,0.083,0.003c0.351,0.029,0.703,0.044,1.047,0.044c0.392,0,0.769-0.017,1.192,0.014
							c0.127,0.859,0.344,1.561,0.644,2.084c0.256,0.449,0.675,0.782,1.18,0.939c1.078,0.335,4.454,1.895,4.867,8.178
							c-2.189,0.507-4.948,0.785-7.882,0.785c-2.933,0-5.692-0.278-7.882-0.786C20.364,13.598,23.741,12.039,24.819,11.704z
							 M35.841,51.557l-0.006,0.11c0,0.513-2.093,2-8.501,2c-5.934,0-7.502-1.309-7.502-2l-0.006-0.11
							c-0.327-2.938-0.163-7.161,0.081-10.638c2.269,0.483,5.029,0.748,7.927,0.748s5.658-0.265,7.927-0.748
							C36.004,44.394,36.168,48.615,35.841,51.557z"/>
						<path d="M27.646,4.87c0.553,0,1-0.447,1-1s-0.447-1-1-1h-1c-0.553,0-1,0.447-1,1s0.447,1,1,1H27.646z"/>
						<path d="M26.295,49.658c-1.886,0.074-2.565-0.367-2.768-0.563c-0.176-0.169-0.194-0.327-0.199-0.327c0,0,0,0.001-0.001,0.003
							l0.007-1.104c0-0.553-0.447-1-1-1s-1,0.447-1,1v0.948c-0.031,0.472,0.13,1.198,0.691,1.805c0.768,0.828,2.064,1.246,3.86,1.246
							c0.158,0,0.321-0.003,0.487-0.01c0.552-0.021,0.981-0.486,0.96-1.038C27.311,50.065,26.804,49.635,26.295,49.658z"/>
						<path d="M22.334,45.667c0.553,0,1-0.447,1-1v-1.01c0-0.553-0.447-1-1-1s-1,0.447-1,1v1.01
							C21.334,45.22,21.781,45.667,22.334,45.667z"/>
						<path d="M23.157,18.651c0.061,0.011,0.119,0.016,0.178,0.016c0.476,0,0.896-0.34,0.983-0.823c0.399-2.233,2.371-3.236,2.455-3.278
							c0.494-0.242,0.7-0.838,0.461-1.334c-0.24-0.495-0.834-0.703-1.336-0.465c-0.12,0.059-2.966,1.468-3.549,4.724
							C22.252,18.034,22.614,18.554,23.157,18.651z"/>

					</svg>
					<br>
					Colisage : 
					<span class="colisage_modal">0</span>
	        		</div>
   			
   				</div>
	        	
	        	<div class="col-6">
	        		<img class="img_article_modal"
	        		src="#" style="width:80%;">
	        	</div>
	        
	        </div>
	       
			  <div class="row">
				  <div class="col-6">
				  	   <div class="row">
				  	   	    <div class="col-5">
									
									    

<svg id="check_colisage_for_calc" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="90px" height="120px"
	 viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">

	<polygon style="fill:#A98258;" points="48,0 10,0 0,16 0,58 58,58 58,16 	"/>
	<polygon style="fill:#DAAE86;" points="10,0 0,16 58,16 48,0 	"/>
	<polygon style="fill:#D8B18B;" points="33,54 29,50 25,54 23,52 23,58 35,58 35,52 	"/>
	<rect x="20" y="30" style="fill:#E8D5B2;" width="18" height="16"/>
	
		<path style="fill:#D4C3A5;" d="M30,41h-6c-0.552,0-1,0.447-1,1s0.448,1,1,1h6c0.552,0,1-0.447,1-1S30.552,41,30,41z"/>
		<path style="fill:#D4C3A5;" d="M34,41h-1c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S34.552,41,34,41z"/>
		<path style="fill:#D4C3A5;" d="M28,35h6c0.552,0,1-0.447,1-1s-0.448-1-1-1h-6c-0.552,0-1,0.447-1,1S27.448,35,28,35z"/>
		<path style="fill:#D4C3A5;" d="M34,37h-2c-0.552,0-1,0.447-1,1s0.448,1,1,1h2c0.552,0,1-0.447,1-1S34.552,37,34,37z"/>
		<path style="fill:#D4C3A5;" d="M24,39h2c0.552,0,1-0.447,1-1s-0.448-1-1-1h-2c-0.552,0-1,0.447-1,1S23.448,39,24,39z"/>
		<path style="fill:#D4C3A5;" d="M24,35h1c0.552,0,1-0.447,1-1s-0.448-1-1-1h-1c-0.552,0-1,0.447-1,1S23.448,35,24,35z"/>
		<path style="fill:#D4C3A5;" d="M28.29,37.29C28.11,37.479,28,37.729,28,38c0,0.27,0.11,0.52,0.29,0.71C28.48,38.89,28.74,39,29,39
			c0.26,0,0.52-0.11,0.71-0.29C29.89,38.52,30,38.26,30,38s-0.11-0.521-0.29-0.71C29.33,36.92,28.66,36.92,28.29,37.29z"/>
	
	<rect x="23" style="fill:#F4D5BD;" width="12" height="16"/>
	<polygon style="fill:#D8B18B;" points="25,21 29,25 33,21 35,23 35,16 23,16 23,23 	"/>
    </svg>
							</div>
						     <div class="col-3">
	                             <div id="input_colisage">0</div>	

	                         </div>
	                         <div class="operatores col-4">
	                             	<svg  version="1.1" id="add_more_colisage" xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                                viewBox="0 0 42 42" style="enable-background:new 0 0 42 42;" xml:space="preserve">
									<path style="fill:#23A24D;" d="M37.059,16H26V4.941C26,2.224,23.718,0,21,0s-5,2.224-5,4.941V16H4.941C2.224,16,0,18.282,0,21
									s2.224,5,4.941,5H16v11.059C16,39.776,18.282,42,21,42s5-2.224,5-4.941V26h11.059C39.776,26,42,23.718,42,21S39.776,16,37.059,16z"/>
								    </svg>
	                             	<svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="substract_colisage" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve" width="70px" height="70px">

									<path d="M14,6H2C0.896,6,0,6.896,0,8s0.896,2,2,2h12c1.104,0,2-0.896,2-2S15.104,6,14,6z" fill="#D80027"/>
									</svg>
									


	                             </div>			
					    </div>
					</div>
				  
				   <div class="col-6">
				   		<div class="row">
				  	   	    <div class="col-5">
									
									   

		
									    	
	<svg id="check_piece_for_calc" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="90px" height="120px"
	 viewBox="0 0 55.667 55.667" style="enable-background:new 0 0 55.667 55.667;" xml:space="preserve">
	<path d="M37.73,40.425l0.006-0.001l-0.099-1.227c-0.168-2.07-0.714-4.244-1.717-6.84c-0.283-0.733-0.441-1.561-0.505-2.397
		c-0.141-1.846,0.223-3.743,1.053-4.816c0.162-0.21,0.296-0.444,0.429-0.679c0.557-0.983,0.868-2.16,0.863-3.409l0-0.001l0-0.001
		c-0.001-0.099-0.005-0.19-0.011-0.282l-0.042-1.417l-0.027,0.007c-0.618-7.23-4.872-9.143-6.275-9.59
		c-0.121-0.211-0.288-0.622-0.401-1.382c-0.008-0.057-0.028-0.108-0.04-0.163c1.083-0.361,1.869-0.965,1.869-1.894v-4
		c0-1.722-2.693-2.333-5-2.333s-5,0.611-5,2.333v4c0,0.929,0.786,1.532,1.868,1.893c-0.013,0.054-0.032,0.106-0.04,0.162
		c-0.113,0.764-0.28,1.174-0.438,1.406c-1.369,0.426-5.621,2.338-6.238,9.569l-0.036-0.01l-0.033,1.432
		c-0.005,0.088-0.009,0.176-0.01,0.271c-0.009,1.548,0.45,3,1.292,4.09c0.753,0.974,1.134,2.629,1.091,4.308
		c-0.017,0.672-0.102,1.347-0.257,1.984c-0.078,0.318-0.173,0.627-0.287,0.921c-0.251,0.649-0.473,1.271-0.668,1.872
		c-0.585,1.803-0.923,3.414-1.049,4.968c-0.304,3.761-0.584,8.979-0.197,12.53c0.036,1.812,1.733,3.939,9.501,3.939
		c6.521,0,10.436-1.471,10.5-3.938C38.181,48.545,37.992,44.034,37.73,40.425z M20.155,38.321c0.027-0.184,0.05-0.367,0.084-0.555
		c0.053-0.297,0.123-0.604,0.194-0.911c0.042-0.184,0.078-0.364,0.126-0.552c0.088-0.342,0.197-0.699,0.307-1.057
		c0.051-0.167,0.093-0.327,0.149-0.498c0.176-0.536,0.373-1.09,0.597-1.67c0.811-2.1,0.867-4.671,0.288-6.76
		c-0.255-0.918-0.619-1.752-1.118-2.397c-0.006-0.008-0.011-0.018-0.017-0.026c-0.12-0.158-0.225-0.331-0.321-0.511
		c-0.03-0.057-0.057-0.116-0.084-0.174c-0.071-0.15-0.133-0.307-0.188-0.469c-0.019-0.056-0.04-0.111-0.057-0.168
		c-0.06-0.204-0.106-0.417-0.14-0.635c2.253,0.473,4.987,0.73,7.859,0.73c2.872,0,5.605-0.257,7.858-0.729
		c-0.034,0.219-0.081,0.431-0.141,0.635c-0.017,0.057-0.038,0.112-0.057,0.168c-0.054,0.162-0.117,0.317-0.187,0.467
		c-0.028,0.059-0.055,0.119-0.085,0.176c-0.101,0.19-0.21,0.372-0.337,0.537c-1.021,1.321-1.546,3.387-1.511,5.489
		c0.021,1.261,0.243,2.535,0.681,3.668c0.225,0.582,0.422,1.136,0.598,1.673c0.055,0.167,0.095,0.322,0.145,0.485
		c0.111,0.362,0.221,0.722,0.31,1.067c0.049,0.191,0.085,0.373,0.128,0.56c0.069,0.303,0.138,0.605,0.191,0.898
		c0.036,0.198,0.06,0.39,0.088,0.584c0.028,0.19,0.051,0.38,0.072,0.567c-2.17,0.489-4.884,0.754-7.753,0.754
		c-2.869,0-5.583-0.266-7.753-0.754C20.102,38.717,20.126,38.52,20.155,38.321z M24.834,2.551C25.15,2.354,26.145,2,27.834,2
		s2.684,0.354,3,0.551v3.564c-0.258,0.16-0.977,0.424-2.15,0.516c-0.553,0.04-1.149,0.04-1.7,0c-1.172-0.092-1.892-0.356-2.15-0.516
		V2.551z M24.819,11.704c0.504-0.157,0.922-0.491,1.177-0.938c0.3-0.522,0.517-1.224,0.708-2.147
		c0.026,0.002,0.056,0.001,0.083,0.003c0.351,0.029,0.703,0.044,1.047,0.044c0.392,0,0.769-0.017,1.192,0.014
		c0.127,0.859,0.344,1.561,0.644,2.084c0.256,0.449,0.675,0.782,1.18,0.939c1.078,0.335,4.454,1.895,4.867,8.178
		c-2.189,0.507-4.948,0.785-7.882,0.785c-2.933,0-5.692-0.278-7.882-0.786C20.364,13.598,23.741,12.039,24.819,11.704z
		 M35.841,51.557l-0.006,0.11c0,0.513-2.093,2-8.501,2c-5.934,0-7.502-1.309-7.502-2l-0.006-0.11
		c-0.327-2.938-0.163-7.161,0.081-10.638c2.269,0.483,5.029,0.748,7.927,0.748s5.658-0.265,7.927-0.748
		C36.004,44.394,36.168,48.615,35.841,51.557z"/>
	<path d="M27.646,4.87c0.553,0,1-0.447,1-1s-0.447-1-1-1h-1c-0.553,0-1,0.447-1,1s0.447,1,1,1H27.646z"/>
	<path d="M26.295,49.658c-1.886,0.074-2.565-0.367-2.768-0.563c-0.176-0.169-0.194-0.327-0.199-0.327c0,0,0,0.001-0.001,0.003
		l0.007-1.104c0-0.553-0.447-1-1-1s-1,0.447-1,1v0.948c-0.031,0.472,0.13,1.198,0.691,1.805c0.768,0.828,2.064,1.246,3.86,1.246
		c0.158,0,0.321-0.003,0.487-0.01c0.552-0.021,0.981-0.486,0.96-1.038C27.311,50.065,26.804,49.635,26.295,49.658z"/>
	<path d="M22.334,45.667c0.553,0,1-0.447,1-1v-1.01c0-0.553-0.447-1-1-1s-1,0.447-1,1v1.01
		C21.334,45.22,21.781,45.667,22.334,45.667z"/>
	<path d="M23.157,18.651c0.061,0.011,0.119,0.016,0.178,0.016c0.476,0,0.896-0.34,0.983-0.823c0.399-2.233,2.371-3.236,2.455-3.278
		c0.494-0.242,0.7-0.838,0.461-1.334c-0.24-0.495-0.834-0.703-1.336-0.465c-0.12,0.059-2.966,1.468-3.549,4.724
		C22.252,18.034,22.614,18.554,23.157,18.651z"/>
    </svg>

							</div>
						     <div class="col-3">
							    <div id="input_piece">0</div>
								
						     </div>
						     <div class="operatores col-4">
	                             	<svg version="1.1" id="add_more_pieces" xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                                viewBox="0 0 42 42" style="enable-background:new 0 0 42 42;" xml:space="preserve">
									<path style="fill:#23A24D;" d="M37.059,16H26V4.941C26,2.224,23.718,0,21,0s-5,2.224-5,4.941V16H4.941C2.224,16,0,18.282,0,21
									s2.224,5,4.941,5H16v11.059C16,39.776,18.282,42,21,42s5-2.224,5-4.941V26h11.059C39.776,26,42,23.718,42,21S39.776,16,37.059,16z"/>
								    </svg>
	                             	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" 
	                             	id="substract_pieces" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve" width="70px" height="70px">

									<path d="M14,6H2C0.896,6,0,6.896,0,8s0.896,2,2,2h12c1.104,0,2-0.896,2-2S15.104,6,14,6z" fill="#D80027"/>
									</svg>
								


	                             </div>
					    </div>
					  
				  </div>
			</div>
			









	      		</div>
	      		<div class="col-4 cal_btns">
	      			




					<div class="row" >
	        			<div class="col-4">
	        				<button id="number1" 
	        				class="btn btn-primary btn-block btn-lg">1</button>
	        			</div>
	        			<div class="col-4">
	        				<button  id="number2" 
	        				class="btn btn-primary btn-block btn-lg">2</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number3"
	        				class="btn btn-primary btn-block btn-lg">3</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				<button id="number4"
	        				class="btn btn-primary btn-block btn-lg">4</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number5"
	        				class="btn btn-primary btn-block btn-lg">5</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number6"
	        				class="btn btn-primary btn-block btn-lg">6</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				<button id="number7"
	        				class="btn btn-primary btn-block btn-lg">7</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number8"
	        				class="btn btn-primary btn-block btn-lg">8</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="number9"
	        				class="btn btn-primary btn-block btn-lg">9</button>
	        			</div>
	        		</div>
	        		<br>
	        		<div class="row">
	        			<div class="col-4">
	        				
	        			</div>
	        			<div class="col-4">
	        				<button id="number0"
	        				class="btn btn-primary btn-block btn-lg">0</button>
	        			</div>
	        			<div class="col-4">
	        				<button id="numberx"
	        				class="btn btn-primary btn-block btn-lg">&times;</button>
	        			</div>
	        		</div>
	        		
        	</div>
	     	</div>
	     	<div class="row">
	        			<br><br>
	        			<div class="col-12" id="ecart_modal" style="color: #c0392b;font-weight: bold;">écart 
	        				
	        				<span id="ecart_pieces">-1</span> pièces 
	        			</div>
	        		</div>
	     	<div class="row">
				   <div class="col-6">
				 
					  <div class="form-group">
					  	<br>
					    <button class="form-control btn btn-success large_button" 
					      id="give_new_data_to_xml_cb_modal">
					     Confirmer
					          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 45.102 45.102" style="enable-background:new 0 0 45.102 45.102;" xml:space="preserve">

							<path d="M17.021,44.403c0.427,0.45,0.995,0.698,1.601,0.698c0.641,0,1.25-0.287,1.672-0.791    c8.155-9.735,19.136-21.879,23.248-24.062c0.906-0.482,1.515-1.456,1.515-2.423V1.933C45.056,0.678,44.137,0,43.272,0    c-0.314,0-0.795,0.086-1.283,0.494C38.922,3.05,24.179,15.795,19.271,28.458l-4.691-7.624c-0.63-1.02-2.088-1.386-3.122-0.783    L1.102,26.075c-0.599,0.349-0.981,0.924-1.046,1.579c-0.065,0.654,0.194,1.294,0.714,1.755    C2.909,31.309,13.747,40.952,17.021,44.403z M12.378,22.984l5.636,9.16c0.378,0.613,0.96,0.965,1.597,0.965    c0.613,0,1.437-0.375,1.736-1.432c3.091-10.901,15.443-22.661,20.707-27.29v13.253c-5.462,2.98-18.68,18.348-23.482,24.054    c-3.429-3.461-11.281-10.499-14.85-13.674L12.378,22.984z" fill="#FFFFFF"/>

							</svg>
						 </button>
					  </div>
				  </div>
				   <div class="col-6">
		                <div class="form-group">
						  	<br>
						    <button class="form-control btn btn-danger large_button" 
						     data-dismiss="modal">
						     Annuler
						     <svg width="50px" height="50px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		                     viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
							<polygon style="fill:#FFFFFF;" points="404.176,0 256,148.176 107.824,0 0,107.824 148.176,256 0,404.176 107.824,512 256,363.824 
							404.176,512 512,404.176 363.824,256 512,107.824 "/>
							</svg>
						    </button>
						  </div>
				  </div>
			  </div>
	      </div>
	    
	    </div>

	  </div>
	</div>
	

<style type="text/css">
.red_back td{
	background-color: #e77f67;
}
.green_back td{
	background-color: #2ecc71;
}
#inventaire_dem{
	margin-top: 20px;
}
#inventaire_dem tr{
	border-top:15px solid white;
	border-radius: 15px;
}
#inventaire_dem tbody,#inventaire_dem tfoot{
	font-size: 16px;
	color: #ecf0f1;
	/*color: #34495e;*/
	font-weight: bold;
}
.modal-lg{
	width: 80%;
}
</style>
	<table class="table table-striped" id="inventaire_dem">
	  <thead>
	  	<!--<th>Marque</th>-->
	    <tr>
	      
	      <th>Ref</th>
	      <th>Désignation</th>
	      <th>Col</th>
	      <th>Stock Boîte(s)</th>
	      <th>Stock Piece(s)</th>
	      <th>Boîte(s)</th>
	      <th>Piece(s)</th>
	      <th>écart</th>	      
	      
	    </tr>
	  </thead>
	  <tbody id="our_table_body">
      </tbody>
      <tfoot id="our_tfoot">
      	
      </tfoot>

<?php	

$query_select ="select a.idarticle,c.colisagee,a.designation DsgArticle,a.Reference RefArticle, g.IdGamme from articles a INNER JOIN colisages c ON a.IdArticle=c.idArticle INNER JOIN gammes g ON g.IdGamme=a.IdFamille INNER JOIN marques m ON m.IdMarque=g.IdMarque INNER JOIN sousfamilles sf on 
sf.idSousFamille=g.IdSousFamille INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
INNER JOIN detailMouvements dmo ON dmo.idArticle = a.idArticle inner join mouvements mo 
on dmo.idMouvement = mo.idMouvement where mo.idDepot= $_SESSION[IdDepot] group by a.idarticle ,c.colisagee,a.designation ,
a.Reference , fa.idFamille ,fa.Designation, fa.codeFamille ,sf.codeSousFamille , g.Reference , sf.idSousFamille , 
sf.Designation , g.IdGamme,g.Designation order by DsgArticle ASC";
//echo $query_select;
$params_select_table = array();
$options_select_table =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_select_table=sqlsrv_query($conn,$query_select,$params_select_table,$options_select_table);
$ntRes_select_table = sqlsrv_num_rows($stmt_select_table);
$domtree = new DOMDocument('1.0', 'UTF-8');

    /* create the root element of the xml tree */
$xmlRoot2 = $domtree->createElement("xml");
    /* append it to the document created */
$xmlRoot2 = $domtree->appendChild($xmlRoot2);

$domtree_table = new DOMDocument('1.0', 'UTF-8');

    /* create the root element of the xml tree */
$xmlRoot = $domtree_table->createElement("xml");
    /* append it to the document created */
$xmlRoot = $domtree_table->appendChild($xmlRoot);

$output = ' <table border="1" cellpadding="10">
		<tr>
	      
	      <th>Ref</th>
	      <th colspan="3">Désignation</th>
	      <th>Col</th>
	      <th>Stock Boîte(s)</th>
	      <th>Stock Piece(s)</th>
	      <th>Boîte(s)</th>
	      <th>Piece(s)</th>
	      <th>écart</th>	      
	      
	    </tr>';
$total_ecart = 0;
while($row = sqlsrv_fetch_array($stmt_select_table, SQLSRV_FETCH_ASSOC)){


$currentTrack = $domtree_table->createElement("Article");
$currentTrack = $xmlRoot->appendChild($currentTrack);


//echo $query_select_media_article_infos."<br>";

$query_select_media_article_infos="select a.CB,a.Reference,a.IdArticle,a.Designation,m.url as media, g.Designation as gamme,q.Designation as marque from 
articles a inner join media m on m.idArticle = a.IdArticle inner join gammes g on a.IdFamille = g.IdGamme 
inner join marques q on g.IdMarque = q.idMarque where a.Reference = '$row[RefArticle]'";






$params_select_media_article_infos = array();
$options_select_media_article_infos =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_select_media_article_infos=sqlsrv_query($conn,$query_select_media_article_infos,$params_select_media_article_infos,$options_select_media_article_infos);
$ntRes_select_media_article_infos = sqlsrv_num_rows($stmt_select_media_article_infos);
while($row_select_media_article_infos= sqlsrv_fetch_array($stmt_select_media_article_infos, SQLSRV_FETCH_ASSOC)){
	
	$currentTrack2 = $domtree->createElement("Article");
    $currentTrack2 = $xmlRoot2->appendChild($currentTrack2);

    $currentTrack2->appendChild($domtree->createElement('IdArticle',$row_select_media_article_infos['IdArticle']));
    $currentTrack2->appendChild($domtree->createElement('Reference',$row_select_media_article_infos['Reference']));
    $currentTrack2->appendChild($domtree->createElement('Designation',$row_select_media_article_infos['Designation']));
    $currentTrack2->appendChild($domtree->createElement('media',$row_select_media_article_infos['media']));
     $currentTrack2->appendChild($domtree->createElement('gamme',$row_select_media_article_infos['gamme']));
    $currentTrack2->appendChild($domtree->createElement('marque',$row_select_media_article_infos['marque']));
    $currentTrack2->appendChild($domtree->createElement('CB',$row_select_media_article_infos['CB']));


}
//$domtree->save("youssef.xml");




//---------------------------select qteEntreeGlobal--------------------------------//

$sql="SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
					as QteEntree FROM detailMouvements dm
			INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
			INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=? AND m.type='entree' AND m.idDepot=?";
$params1= array($row['idarticle'],$_SESSION['IdDepot']) ;
$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
//echo $sql."<br>";

sqlsrv_fetch($stmt1) ;
$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
//------------------------

//---select qteChargementGlobal--------------------------------//
$sql2 ="SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
				 as QteSortie FROM detailMouvements dm
				INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
				INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=? AND m.type='sortie' and EtatSotie!=3 and EtatSotie=1 AND m.idDepot=?";
//echo $sql2."<br>";
$params1= array($row['idarticle'],$_SESSION['IdDepot']) ;
$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	

$rowC = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
$qteChargementGlobal = $rowC['QteSortie'];

$qteDispo=$qteEntreeGlobal-$qteChargementGlobal;
//$qteDispo=number_format($qteDispo,0," "," ");	
$qteDispoEnBoite=$qteDispo/ $row['colisagee'];
$qteDispoEnBoite=floor($qteDispoEnBoite);
$qteDispoEnBPcs=$qteDispo % $row['colisagee'];
$qteDispoEnBPcs = floor($qteDispoEnBPcs);
$query_marque = "select m.Designation from marques m where 
idMarque = (select g.IdMarque from gammes g
where IdGamme = $row[IdGamme])";
//echo $query_marque;
$stmt_marque = sqlsrv_query( $conn, $query_marque);
$marque ="";
while($marque_row = sqlsrv_fetch_array($stmt_marque, SQLSRV_FETCH_ASSOC)){
	$marque = $marque_row['Designation'];
}
/*$query_select_inventaire_for_table = "select Numero from inventaire_table 
where Superviseur = $_SESSION[IdVendeur]
and Depot = $_SESSION[IdDepot]";
$stmt_select_inventaire_for_table = sqlsrv_query( 
	$conn, $query_select_inventaire_for_table);
$numero = "";
while($select_inventaire_for_table = sqlsrv_fetch_array($stmt_select_inventaire_for_table, SQLSRV_FETCH_ASSOC)){
	$numero = $select_inventaire_for_table['Numero'];
}*/
$nbr_piece_select = -1;
$nbr_colisage_select = -1;
if($_SESSION['numero_inventaire'] != ""){
$query_select_detail_inventaire="select NBR_piece ,
NBR_colisage from Detail_inventaire_table 
where Numero = '$_SESSION[numero_inventaire]'
and idArticle = $row[idarticle]";
if($_SESSION['clicked_recup'] == "yes")
$query_select_detail_inventaire="select NBR_piece ,
NBR_colisage from Detail_inventaire_table_temp 
where Numero = '$_SESSION[numero_inventaire]'
and idArticle = $row[idarticle]";
else
$query_select_detail_inventaire="select NBR_piece ,
NBR_colisage from Detail_inventaire_table 
where Numero = '$_SESSION[numero_inventaire]'
and idArticle = $row[idarticle]";
//echo $query_select_detail_inventaire."<hr>";
$stmt_select_nbr = sqlsrv_query($conn, $query_select_detail_inventaire);
while($select__detail_inventaire = sqlsrv_fetch_array($stmt_select_nbr, SQLSRV_FETCH_ASSOC)){
	$nbr_piece_select = $select__detail_inventaire['NBR_piece'];
	$nbr_colisage_select = $select__detail_inventaire['NBR_colisage'];
	//echo $select__detail_inventaire['NBR_piece'];
}
}
  $currentTrack->appendChild($domtree_table->createElement('nbr_piece_select',$nbr_piece_select));
   $currentTrack->appendChild($domtree_table->createElement('nbr_colisage_select',$nbr_colisage_select));
   $currentTrack->appendChild($domtree_table->createElement('marque',$marque));
   $currentTrack->appendChild($domtree_table->createElement('RefArticle',$row['RefArticle']));
   $currentTrack->appendChild($domtree_table->createElement('colisagee',
   	$qteDispoEnBoite));
   $currentTrack->appendChild($domtree_table->createElement('qteDispoEnBoite',
   	$qteDispoEnBPcs));
     $currentTrack->appendChild($domtree_table->createElement('DsgArticle',
   	$row['DsgArticle']));
       $currentTrack->appendChild($domtree_table->createElement('nbr_colisage',
   	$row['colisagee']));
      $output .= '<tr>';
      $output .= '<td>'.$row['RefArticle'].'</td>';
      $output .= '<td colspan="3">'.$row['DsgArticle'].'</td>';
      $output .= '<td>'.$row['colisagee'].'</td>';
      if($nbr_piece_select == -1) $nbr_piece_select = 0;
      if($nbr_colisage_select == -1) $nbr_colisage_select =0;
      $output .= '<td>'.$qteDispoEnBoite.'</td>';
      $output .= '<td>'.$qteDispoEnBPcs.'</td>';
      $output .= '<td>'.$nbr_colisage_select.'</td>';
      $output .= '<td>'.$nbr_piece_select.'</td>';
      
      $ecart = ($nbr_colisage_select * $row['colisagee'] + $nbr_piece_select)-($qteDispoEnBoite * 
      	$row['colisagee'] + $qteDispoEnBPcs);
      $total_ecart+=$ecart;
      $output .= '<td>'.$ecart.'</td>';
      $output .= '</tr>';
?>
<?php
}

 $output .='<tr>
<td colspan="9">
Total écart
</td>
<td>'.$total_ecart.'</td>
 </tr>';


$domtree->save("xml/article_details.xml");

$domtree_table->save("xml/table_inventaire.xml");
	
//}
require_once('../TCPDF-master/tcpdf.php');


try {
     $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
				      $obj_pdf->SetCreator(PDF_CREATOR);  
				      $obj_pdf->SetTitle("Plateforme de gestion de distribution");  
				      $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
				      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
				      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
				      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
				      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
				      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
				      $obj_pdf->setPrintHeader(false);  
				      $obj_pdf->setPrintFooter(false);  
				      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
				      //$obj_pdf->SetFont('helvetica', '', 12); 
				      $obj_pdf->SetFont('aealarabiya', '', 8); 
				      $obj_pdf->AddPage();  
					  $output .= '</table>';  
			          $obj_pdf->writeHTML($output); 
			       /*  unlink('C:\xampp\htdocs\PGD_Tuesday6\upload\inventaire.pdf'); 
			          $obj_pdf->Output('C:\xampp\htdocs\PGD_Tuesday6\upload\inventaire.pdf', 'F');*/
					  if(file_exists('uploads\inventaire.pdf')){
						    unlink('uploads\inventaire.pdf');
						}else{
						    //echo 'file not found';
						}
					
			          $obj_pdf->Output( __DIR__ .'\uploads\inventaire.pdf', 'F');
} catch (Exception $e) {
    //echo 'Exception reçue : ',  $e->getMessage(), "\n";
} finally {
   // echo "Première fin.\n";
}


					
			        //$obj_pdf->Save();
 //header("Content-disposition: attachment;filename=$filename");
  //readfile($filename);
if(isset($_POST["something"])) {
?>
<script type="text/javascript">
	window.open("http://pgd.ma/v6/frontend2/uploads/inventaire.pdf");	
	//window.open("http://192.168.1.16/PGD_Tuesday6/upload/inventaire.pdf");
</script>
<?php
}
?>
</div>
<style type="text/css">
	.ui-keyboard-button { height: 3em; min-width: 4em; margin: .1em; cursor: pointer; overflow: hidden; line-height: 2em; -moz-user-focus: ignore; }
	/*.modal{width: 1000px;margin:auto;}*/
	.modal-ku {
	  width: 950px;
	  margin: auto;
	}
	@media (min-width: 992px){
		.modal-lg {
		    max-width: 2000px;
		}
	}
	@media (min-width: 576px){
		.modal-lg {
		    max-width: 2000px;
		}
	}
	@media (min-width: 576px){
		.modal-dialog {
	    max-width: 2000px;
	    margin: 1.75rem auto;
	}
	}
	#myModal_ref #check_colisage_for_calc,#myModal_cb #check_colisage_for_calc
	{
		box-shadow: 5px 5px 2px #3498db;
		border:5px solid #3498db;
	}
	
	#myModal_design #check_colisage_for_calc
	{
		box-shadow: 5px 5px 2px #3498db;
		border:5px solid #3498db;
	}
	#myModal_design #add_more_colisage,#myModal_cb #add_more_colisage{
		box-shadow: 5px 5px 2px #2ecc71;
		border:5px solid #2ecc71;
	}
	#myModal_ref #add_more_colisage{
		box-shadow: 5px 5px 2px #2ecc71;
		border:5px solid #2ecc71;
	}
	#myModal_ref #add_more_pieces,#myModal_cb #add_more_pieces {
		box-shadow: 5px 5px 2px #2ecc71;
		border:5px solid #2ecc71;
	}
	#myModal_design #add_more_pieces{
		box-shadow: 5px 5px 2px #2ecc71;
		border:5px solid #2ecc71;
	}
	#input_colisage,.modal-ku .btn{
		font-size: 50px;
	}
	.modal-ku .btn svg,.modal-ku .btn span{
		vertical-align: baseline;
		
	}
	#input_piece{
		font-size: 50px;
	}
	#gamme_marque_select_ref_modal,#ecart_modal{
		font-size: 25px;
	}
	.cal_btns button,.large_button{
		height: 70px;
	}	
	#input_colisage svg,#input_piece svg{
	    padding: 9px;
	}
	#myModal_ref #total_existant,#myModal_cb #total_existant{
	font-size: 25px;
	}
	#myModal_design #total_existant{
	font-size: 25px;
	}
	/* .operatores{
		display: none;
	}*/
	tfoot tr{
		background-color: #e67e22;
	}
	
</style>


	</table>

</div>
<script type="text/javascript" src="js/canvas-toBlob.js"/></script>
<script  type="text/javascript" src="js/FileSaver.min.js"/></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>-->

<script type="text/javascript" src="js/our_script.js"></script>
<script type="text/javascript">
	//alert("ok");
	var xml_download = document.getElementById("give_new_data_to_xml_ref_modal");
       xml_download.onclick = function(){
       //alert("it_is_clicked");
      var serializer = new XMLSerializer();
      var xmlString = serializer.serializeToString(xmlDoc_first_step);
       //var file = new File([xmlString], "hello world.txt", {type: "text/plain;charset=utf-8"});
      //FileSaver.saveAs(file); 
      var blob = new Blob([xmlString], {type: "text/plain;charset=utf-8"});
      saveAs(blob, "unsaved data.txt");     

    };
    var xml_download_cb = document.getElementById("give_new_data_to_xml_cb_modal");
       xml_download_cb.onclick = function(){
       //alert("it_is_clicked");
      var serializer = new XMLSerializer();
      var xmlString = serializer.serializeToString(xmlDoc_first_step);
       //var file = new File([xmlString], "hello world.txt", {type: "text/plain;charset=utf-8"});
      //FileSaver.saveAs(file); 
      var blob = new Blob([xmlString], {type: "text/plain;charset=utf-8"});
      saveAs(blob, "unsaved data.txt");     

    };

     var xml_download_designation = 
     document.getElementById("give_new_data_to_xml_design_modal");
       xml_download_designation.onclick = function(){
       //alert("it_is_clicked");
      var serializer = new XMLSerializer();
      var xmlString = serializer.serializeToString(xmlDoc_first_step);
       //var file = new File([xmlString], "hello world.txt", {type: "text/plain;charset=utf-8"});
      //FileSaver.saveAs(file); 
      var blob = new Blob([xmlString], {type: "text/plain;charset=utf-8"});
      saveAs(blob, "unsaved data.txt");     

    };

</script>
<!--<table id="for_pdf" style="display: none;">
	
</table>-->
</body>
</html>	

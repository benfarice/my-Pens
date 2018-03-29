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
$no_style_res = 'no';

require_once("header_fact.php");

$s1 = $_SESSION['IdDepot'];
$s2 = $_SESSION['IdVendeur'];
?>
<input type="hidden" value=" <?php echo $s1; ?>" id="s1" name="">
<input type="hidden" value=" <?php echo $s2; ?>" id="s2" name="">
<script type="text/javascript">
	var s1_id_depot = document.getElementById('s1').value;
	var s2_id_vendeur = document.getElementById('s2').value;
	function ajax_func(){
			
				xmlhttp = new XMLHttpRequest();
				
				xmlhttp.onreadystatechange = function (){
					if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
						var inner = xmlhttp.responseText;
						document.getElementById('get_ajax_data').innerHTML = inner;
						if(inner != ""){
							$('#view_data_table_head').show();
							$('#alert_aucune').hide();
						}else{
							$('#alert_aucune').show();
							$('#view_data_table_head').hide();
						}
					}
				}
				xmlhttp.open('GET','ajax_consultation_factures.php?id_depot='+s1_id_depot+'&id_vendeur='+s2_id_vendeur, true);
				xmlhttp.send();
				
	}
	function search_func(){


				var request = 'ajax_consultation_factures.php?id_depot='+s1_id_depot+'&id_vendeur='+s2_id_vendeur;
				var searched_date = document.getElementById('searched_date').value;
				var searched_client = document.getElementById('client_input').value;
				var DateFin = document.getElementById('DateFin').value;
				if(searched_date != "" && searched_client != "" && DateFin != ""){
					request += '&searched_date='+searched_date+'&searched_client='+searched_client
					+'&DateFin='+DateFin;
				}else if(searched_date != "" && searched_client == "" && DateFin != ""){
					request+= '&searched_date='+searched_date+'&DateFin='+DateFin;
				}else if(searched_client != ""){
					request += '&searched_client='+searched_client;
				}
				//console.log(request);
				//xmlhttp = new XMLHttpRequest();
				
				xmlhttp.onreadystatechange = function (){
					if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
						var inner2 =  xmlhttp.responseText;
						document.getElementById('get_ajax_data').innerHTML = inner2;
						if(inner2 != ""){
							$('#view_data_table_head').show();
							$('#alert_aucune').hide();
						}else{
							$('#alert_aucune').show();
							$('#view_data_table_head').hide();
						}
					}
				}
				xmlhttp.open('GET',request, true);
				xmlhttp.send();
				
				$('#client_input').val('');
				
	}
	window.onload=ajax_func;
	function Afficher_details_facture(id_facture){
		//alert(id_facture);
		
		xmlhttp2 = new XMLHttpRequest();
				
				xmlhttp2.onreadystatechange = function (){
					if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
						document.getElementById('ajax_details_here').innerHTML = xmlhttp2.responseText;
					}
				}
				xmlhttp2.open('GET','ajax_consultation_factures_affichage_details.php?id_fac='+id_facture, true);
				xmlhttp2.send();
		$('#myModal_affichage_details').modal('show');
	}
	function Afficher_txt_facture(id_facture){
		//alert(id_facture);
		xmlhttp3 = new XMLHttpRequest();
		xmlhttp3.open('GET','ajax_txt_facture.php?id_fac='+id_facture, true);
		xmlhttp3.onreadystatechange = function (){
					if(xmlhttp3.readyState == 4 && xmlhttp3.status == 200){
						var filename = xmlhttp3.responseText;
						document.location.href=filename;
						console.log(xmlhttp3.responseText);
					}
				}
		xmlhttp3.send();
	}

</script>

<div class="container-fluid">
	<div class="Head row">
	<div  class="heaLeft col-md-6 col-sm-6">
		<div class="Info"> 
			<a href="index.php"><img src="../images/home.png"></a>
			<?php echo $trad['index']['Bienvenu'] ;echo $_SESSION['Vendeur'];?>
		</div>
	</div>
	<div  class="headRight col-md-6 col-sm-6">
		<a href="index.php?logout" class="signoutsignout" style="float: right;">
		<div class="signout">
		
		</div>
		</a>
	</div> 
	</div>
	<div class="row"  style="margin: 20px;">
        		<div class="form-group col-md-3 col-sm-3">
		        	<label>Date De</label>
		            <div class='input-group date' id='datetimepicker6'>
		                <input type='text' id="searched_date" name="searched_date" class="form-control" value="<?php echo date('01/m/Y') ?>" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>
		 	
		        <div class="form-group col-md-3 col-sm-3">
		        	<label>à</label>
		            <div class='input-group date' id='datetimepicker7'>
		                <input type='text' id="DateFin" name="DateFin" class="form-control" value="<?php echo
		                date('t/m/Y') ?>" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>

	<script type="text/javascript">
			 $(function () {
                $('#datetimepicker6').datetimepicker({
		        	format: 'DD/MM/YYYY'
		        });
		        $('#datetimepicker7').datetimepicker({
		            useCurrent: false,
		            format: 'DD/MM/YYYY' //Important! See issue #1075
		        });
		        $("#datetimepicker6").on("dp.change", function (e) {
		            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
		        });
		        $("#datetimepicker7").on("dp.change", function (e) {
		            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
		        });
		    });
		</script>
		<div class="col-md-3 col-sm-3 animated fadeInRight">
			<br>
			<div class="form-group">
				<button id="search_btn" class="btn btn-primary btn-block" onclick="search_func();">
					Rechercher
				</button>
			</div>
		</div>
		<div class="col-md-3 col-sm-3 animated fadeInRight">
			<div class="form-group">
				<label>Client</label>
				<input type="text" id="client_input" class="form-control">
			</div>
		</div>
	</div>
	<div class="row" style="margin: 20px" >
		<div id="alert_aucune" style="display: none;" class="alert alert-danger col-md-12" role="alert">
		Aucun résultat à Afficher</div>
		<table class="table table-striped">
		  <thead class="thead-dark" id="view_data_table_head">
		    <tr class="animated bounce">
		      <th scope="col">Numéro de Facture</th>
		      <th scope="col">Vendeur</th>
		      <th scope="col">Client</th>
		      <th scope="col">Total T.T.C ( DH )</th>
		      <th scope="col">Date de Facture</th>
		      <th scope="col">Afficher</th>
		      <th scope="col">Imprimer</th>
		    </tr>
		  </thead>
		  <tbody id="get_ajax_data"></tbody>
		</table>
		
	</div>
</div>
<div id="myModal_affichage_details" class="modal fade" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class="modal-title">Affichage</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       
      </div>
      <div class="modal-body">
        <table class="table table-striped">
		  <thead class="thead-dark">
		    <tr>
		      <th scope="col">Ref</th>
		      <th scope="col">Désignation</th>
		      <th scope="col">Image</th>
		      <th scope="col">PV</th>
		      <th scope="col">Type</th>
		      <th scope="col">Qte</th>
		      <th scope="col">Total</th>
		    </tr>
		  </thead>
		  <tbody id="ajax_details_here">
		  		<!-- ............................. -->
		    	<!-- ............................. -->
		    	<!-- ............................. -->
		    	<!-- ............. Ajax Here ................ -->
		    	<!-- ............................. -->
		    	<!-- ............................. -->
		    	<!-- ............................. -->
		    	<!-- ............................. -->
		  
		  </tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-lg" data-dismiss="modal">Fermer</button>
      </div>
    </div>

  </div>
</div>
<style type="text/css">
	*{
				 color: #223b3e;;
				 letter-spacing: -.05em;
				 font-weight: 100;
				 font-size: 17px;
				 font-family: 'Montserrat', sans-serif;
				 -webkit-animation-duration: 4s;
  				 -webkit-animation-delay: 0.1s;
  				 -moz-animation-duration: 4s;
  				 -moz-animation-delay: 0.1s;
  				 -o-animation-duration: 4s;
  				 -o-animation-delay: 0.1s;
  				 -ms-animation-duration: 4s;
  				 -ms-animation-delay: 0.1s;
  				 animation-duration: 4s;
  				 animation-delay: 0.1s;
				
			}
		body{
			overflow-x: hidden;
		}
		

		#myModal_affichage_details .modal-body {
		    
		}
		.modal-lg {
		  max-width: 1100px;}
		@media (min-width: 768px) {
		   .modal-lg {
		    width: 800px;
		  } 
		}
		@media (min-width: 992px) {
		  .modal-lg {
		    width: 800px;
		  }
		}
		svg{
			cursor: pointer;
		}
</style>
<?php
include("footer.php");

?>

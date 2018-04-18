<?php
include("php.fonctions.php");
require_once('connexion.php');
session_start();
$tableInser="clients";
if(isset($_GET['goAdd'])){
	$errors="";
//parcourir($_POST);return;
		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( mysqli_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".mysqli_errors() . " <br/> ";
		}
	  
		$reqInser1 = "INSERT INTO ".$tableInser." (nom ,prenom ,intitule ,adresse ,departement,ville,patente,IdFiscale,formeJ,rc,
						longitude,latitude,idVendeur,idDepot,idTypeClient,CodeClient
							) values 	('".addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8'))."','".
							addslashes(mb_strtolower(securite_bdd($_POST['Prenom']), 'UTF-8'))."','".
							addslashes(mb_strtolower(securite_bdd($_POST['Intitule']), 'UTF-8'))."','".
							addslashes(mb_strtolower(securite_bdd($_POST['Adresse']), 'UTF-8'))."','".
							$_POST['Secteur']."','".
							$_POST['Ville']."','".
							addslashes(mb_strtolower(securite_bdd($_POST['Patente']), 'UTF-8'))."','".
							addslashes(mb_strtolower(securite_bdd($_POST['IF']), 'UTF-8'))."','".
							addslashes(mb_strtolower(securite_bdd($_POST['FJ']), 'UTF-8'))."','".
							addslashes(mb_strtolower(securite_bdd($_POST['RC']), 'UTF-8'))."',".
							addslashes(mb_strtolower(securite_bdd($_POST['Lng']), 'UTF-8')).",".
							securite_bdd($_POST['Lat']).",'".
							$_SESSION['IdVendeur']."','".
							$_SESSION['IdDepot']."','".
							$_POST['Type']."','".
							$_POST['Code']."')";

	
echo $reqInser1; 
		$stmt1 = mysqli_query( $conn, $reqInser1 );
		if( $stmt1 === false ) {
		
			$error.="Erreur : ".mysqli_error($conn) . " <br/> ";
		}

		if( $error=="" ) {
			 mysqli_commit( $conn );
		?>
				<script type="text/javascript"> 
					jAlert('L\'ajout a été effectué.',"Message");
					
					document.location.href="index.php";
				
				</script>
		<?php
		} else {
			 mysqli_rollback( $conn );
			 echo "<font style='color:red'>".$error."</font>";
		}
		/********************************************************/	

exit;
}
if(isset($_GET['getMap'])){
	?>
	  <input type="button" onclick=" Fermer() " value="" class="CloseBox"/>
 <input id="pac-input" class="controls" type="text" placeholder="Recherche">
<div id="map" style="width:1200px; height:760px;">
</div>

<script language="javascript" type="text/javascript">
$(document).ready(function() {

		initMap();
});
var map;  
 var geocoder;
var lat=null;var longi=null;
var marker3=null;


function initMap() {
  $('#map').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>');
  //Center = new google.maps.LatLng(lat,longi); 

 //geocoder = new google.maps.Geocoder();
 map = new google.maps.Map(document.getElementById('map'), {          
	 zoom: 17,	 
//	 center: Center, 	 
	 mapTypeId: 'roadmap'        });        
	    
	//Position actuel------------------------------------------------------------------
 var marker3 = new google.maps.Marker({   
			draggable: false, 
			animation: google.maps.Animation.DROP, 
			//label: "mmm",			
			map: map  
			
		}); 
	function watchMyPosition(position) 
	{
//	alert("Your position is: " + position.coords.latitude + ", " + position.coords.longitude + " (Timestamp: "  + position.timestamp + ")<br />");

	  var pos = {
			lat: position.coords.latitude,
			lng: position.coords.longitude
		  };
		     map.setCenter(pos);    
	   marker3.setPosition(pos);  
	}
	
	$.geolocation.get({success:watchMyPosition}); 
 
	// add click to marker
	google.maps.event.addListener(map, 'click', function(event) {
		$("#BoxMap").dialog('close');
		getAddress(event.latLng);	
     });

   function getAddress(latLng) {
    geocoder.geocode( {'latLng': latLng},
          function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
              if(results[0]) {
                document.getElementById("Adresse").value = results[0].formatted_address;

				var s=String(latLng);
				s=s.substring(1, s.length-1);
				var res = s.split(",");
				$("#Lat").val(res[0]);
				$("#Lng").val( res[1]);
				
              }
              else {
                document.getElementById("Adresse").value = "pas de résultat";
              }
            }
            else {
              document.getElementById("Adresse").value = status;
            }
          });
		  
        }
		

// Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });

 

 }  
 

</script>
<?php
//	echo "getMap";
	exit;
}
if(isset($_GET['chargerSecteur'])){

	$Options = '<select multiple="multiple" name="Secteur" id="Secteur" class="Select Secteur"  tabindex="3" style="width:446px" >';
	$sql = "SELECT d.iddepartment,d.codeDepartement,d.Designation FROM departements d where idVille=".$_GET['IdVille'];
		
			$reponse=mysqli_query( $conn, $sql);         
			/*   if( $reponse === false ) {
				 die( print_r( mysqli_errors(), true));
			}*/
			
		$nRes = mysqli_num_rows($reponse);
		
		if($nRes != 0)
		 while ($donnees =  mysqli_fetch_array($reponse))
            {
				$Options.="<option value='".$donnees['iddepartment']."'>".$donnees['Designation']."</option>";			   
			}
		
		$Options.="</select>";
?>
				
	<script language="javascript" type="text/javascript">

$('#Secteur').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Secteur ',single:true,maxHeight: 300,
		      onClick: function(view) {
				
				var Secteur =$('#Secteur').val();
				if(Secteur!="") {
					$('div.Secteur').removeClass('erroer');
					$('div.Secteur button').css("border","1px solid #ccc").css("background","#fff");
				}
}

});		

	</script>
	<?php
			echo $Options;
exit;
}
if (isset($_GET['add'])){ 

$CodeClt= "CL".Increment_Chaine_F("CodeClient","clients","IdClient",$conn,"");	
echo $CodeClt;
?>
<div>
<form id="formAdd" method="post" name="formAdd"> 
<div id="resAdd" style=""></div>	
<div id="tabs">
  <ul>
    <li><a href="#Clt">Client</a></li>
    <li><a href="#DtlClt">Détail client</a></li>
  
  </ul>
  <div class="Clt">
  <div id="Clt">
 
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
		<tr>
			<td width="140" ><div class="etiqForm" id="" > <strong>Code</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Code"  id="Code" readonly  size="30" value="<?php echo $CodeClt;?>" tabindex="1" /> 
            </td> 
			
			<td><div class="etiqForm" id="" > <strong>Ville</strong> : </div>
            </td>
            <td>
            	<select id="Ville" name="Ville" multiple="multiple"  Class="Select Ville" style="width:446px">
					
								 <?php $sql = "select idville, Designation from villes ";
							   $reponse=mysqli_query( $conn, $sql );         
									   while ($donnees =  mysqli_fetch_array($reponse))
									   {
									   ?>
									   <option value="<?php echo $donnees['idville'];?>"><?php echo $donnees['Designation']?></option>
								 <?php
								  }
								 ?>
								 
			</select>
			
            </td> 
			
			</tr>
			<tr>
			<td><div class="etiqForm" id="" > <strong>Intitulé</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Intitule"  id="Intitule" size="30" tabindex="1" /> 
            </td> 
		<td width="120" ><div class="etiqForm" id="" ><strong>Secteur </strong> : </div>
            </td>
            <td>
            <div id="Secteurs" style="width:446px;">			
					<select multiple="multiple" id="Secteur" name="Secteur" Class="Select Secteur" style="width:446px">
					</select>
		</div>
            </td> 
					
          </tr>
		  
		   <tr>
           
			<td><div class="etiqForm" id="" ><strong>Type client </strong> : </div>
            </td>
            <td>
            		<select id="Type" name="Type" multiple="multiple"  Class="Select Type" style="width:446px">
					
								 <?php 	
								 $req="	select IdType,Designation 
									from typeclients t 		";		
							   $reponse=mysqli_query( $conn, $req);         
									   while ($donnees =  mysqli_fetch_array($reponse))
									   {
									   ?>
									   <option value="<?php echo $donnees['IdType'] ?>"><?php echo $donnees['Designation']?></option>
								 <?php
								  }
								 ?>
			</select>
            </td> 			
          </tr> 	
		<tr>
		<td>
			 <div class="etiqForm" id="" >  <strong>Localisation</strong> : </div>
            </td>
            <td colspan="3">
			<textarea rows="2" cols="31" name="Adresse"  id="Adresse"></textarea>
				<input class="btnLocalise" type="button" name="Localisation"  value="" id="Localisation" tabindex="1" /> 
				<input class="FormAdd1" type="hidden" name="Lat"  id="Lat" size="30" tabindex="1" /> 
					<input class="FormAdd1" type="hidden" name="Lng"  id="Lng" size="30" tabindex="1" /> 
            </td>
				
         
		</tr>
		
			  
	
 	  </table>

  </div>
  <div id="DtlClt">
   
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
        <tr>
        	<td>
			 <div class="etiqForm" id="" ><strong>Nom  </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Nom"  id="Nom" size="20" tabindex="1"  />
            </td>
		
			
				<td  width="300">
			 <div class="etiqForm" id="" > <strong>Id fiscale </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="IF"  id="IF" size="20" tabindex="1"  />
            </td>
			
          </tr>

		 <tr>
		 <td><div class="etiqForm" id="" > <strong>Prénom </strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="Prenom"  id="Prenom" size="20" tabindex="1"  />
            </td>
		
			<td><div class="etiqForm" id="" > <strong>Forme juridique </strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="FJ"  id="FJ" size="20" tabindex="1"  />
            </td>			
          </tr>
		  
		  		<tr>
        	
            
		   <td>
			 <div class="etiqForm" id="" >  <strong>RC</strong> : </div>
            </td>
           <td>
            <input class="FormAdd1" type="text" name="RC"  id="RC" size="20" tabindex="1"  />
            </td>
				<td style="vertical-align:text-top " ><div class="etiqForm" id=""><strong>Patente</strong> : </div>
            </td>
            <td colspan="3">
            <input type="text" name="Patente" id="Patente" size="20" >	
            </td> 

        </tr>	  
		
 	  </table>
  </div>
  </div>
  <div class=" boiteBtn">
<div class=" boiteBtn" style="float:right">
<input type="button" value="Ajouter" class="btn"  onclick="AjoutClt()"/>&nbsp;&nbsp;
<input type="reset" value="Annuler"  class="btn" /></div></div>

</div>
</form>
</div><div class="clear"></div><br>
<div id="BoxMap" style="width:1000px; height: 560px;">

</div>
<style>
.pac-container {
    z-index: 100051 !important;
}
</style>

<script language="javascript" type="text/javascript">

 var geocoder;
function getLocation() {
	
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
		//initMap();
		//initMap();
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}
function showPosition(position) {
	//var latlon = "("+position.coords.latitude + ", " + position.coords.longitude+")";	
	var latlon =new google.maps.LatLng(parseFloat(position.coords.latitude ),parseFloat(position.coords.longitude));
	$("#Lat").val( position.coords.latitude);
	$("#Lng").val( position.coords.longitude);
	getAddress(latlon);

}

function getAddress(latLng) {
 var arrDetailAdr;
geocoder = new google.maps.Geocoder();
geocoder.geocode( {'latLng': latLng},
	  function(results, status) {
		if(status == google.maps.GeocoderStatus.OK) {		
			var Secteur="";		
		  if(results[1]) {
			  //get secteur	
			  
			 var arrDetailAdr = results[1].address_components;
			document.getElementById("Adresse").value = results[0].formatted_address;
			 for (ac = 0; ac < arrDetailAdr.length; ac++) {			
						if (arrDetailAdr[ac].types[0] == "locality"){ // cyty
									
									var ville=arrDetailAdr[ac].long_name;
									var state = arrDetailAdr[4].short_name;	
							
						}			
			}
			}
		  else {
			document.getElementById("Adresse").value = "No results";
		  }
		}
		else {
		  document.getElementById("Adresse").value = status;
		}
	  });
	}


$(document).ready(function() {
	getLocation();
		$('#BoxMap').dialog({
					autoOpen		:	false,
					width			:	1200,
					height			:	750,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	''
	
			});

		 $( "#tabs" ).tabs();
});
$('#Ville').multipleSelect({
		  filter: true,placeholder:'S&eacute;lectionnez la Ville ',single:true,maxHeight: 300,
		      onClick: function(view) {
				if(view.checked = 'checked')
				$('#Secteurs').load("clients.php?chargerSecteur&IdVille="+view.value);
				
				var Ville =$('#Ville').val();
				if(Ville!="") {
					$('div.Ville').removeClass('erroer');
					$('div.Ville button').css("border","1px solid #ccc").css("background","#fff");
				}
}});
$('#Type').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Type ',single:true,maxHeight: 300});
$('#Secteur').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Secteur ',single:true,maxHeight: 300});


$('#Localisation').click(function(){
		$('#BoxMap').load('clients.php?getMap').dialog('open');
	//google map appear with grey inside dialogbox add this line to resorve issue
	//google.maps.event.trigger(map, "resize");
	//$.geolocation.get({success:watchMyPosition}); 
	
	});
</script>

<?php
exit;
}
include("header.php"); ?>

<Style>
.ui-widget-content{
background:#fff;}
.Clt{
	border: 1px solid #CCC;
-webkit-border-radius: 5px;
-khtml-border-radius: 5px;
border-radius: 5px;
margin: 10px 20px; 
}
</style>

<div style=" display:flex;align-items:center; padding:2px 0;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
						<div >&nbsp;> <span  Class="TitleHead" onclick="">Gestion des clients</span></div> 
</div>
<div style="clear:both;"></div>

<div id="formRes" ></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<div id="box" ></div>

<?php
include("footer.php");
?>
 <script src="js/jquery.geolocation.js"></script>
<script language="javascript" type="text/javascript">

$(document).ready(function() {

	$('#box').dialog({
					autoOpen		:	false,
					width			:	1260,
					height			:	706,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						},
						"Terminer "	: function() {
							//terminer();
						
						}
					 }
			});
			
		 $('#formRes').load('clients.php?add');

});
function rechercher(){
		
		
		clearForm('formRechF',0);
	}

function AjoutClt(){var form="";
	var act = $('#act').attr('value');
		 act = 'add';
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: { 
                                                Intitule: "required",
												Type:"required",
												Adresse:"required",
												'Ville': "required",
												'Secteur': "required"
												
                                          }    
		});
	var test=$(form).valid();
	verifSelect2('Ville');
	verifSelect2('Secteur');
	verifSelect2('Type');
		if(test==true){		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'clients.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'clients.php?goAdd',
														method			:	'post'
													}); 
													
												
											}
		
					}
				})
		}else {
			  $("#tabs").tabs("option", "active", 0);
		}
}
function Fermer(){

	$("#BoxMap").dialog('close');
}

</script>
<script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&libraries=places"> </script>
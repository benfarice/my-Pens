<?php
include("../php.fonctions.php");
require_once('../connexion.php');

?>
<?php

if (isset($_GET['map'])){ ?>
<DIV style="" class="headVente">
	<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
	<div class="TitleHead">Accueill </div>
</div>
<div style="clear:both;"></div>

<?php
$whereType="";
$whereClass="";

if(isset( $_POST['Type']) && $_POST['Type'] != "")
{
$whereType=" and c.idTypeClient=".$_POST['Type'];
}

if(isset( $_POST['classe']) && $_POST['classe'] != "")
{
$whereClass=" and classe=".$_POST['classe'];
}

$sql = "SELECT c.IdClient,c.nom,c.prenom,c.longitude,c.latitude,tc.Designation  FROM clients c INNER JOIN typeClients tc ON c.idTypeClient=tc.idType  WHERE c.departement=? ". $whereType .$whereClass ;//dc.idColisage *
$params = array($_POST['Secteur']);	
$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}
$nRes = sqlsrv_num_rows($stmt);	//echo $nRes;
$client=array();
if($nRes==0)
{ ?>
	<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
		<br><br><br><br>
		Aucun r&eacute;sultat &agrave; afficher.
	</div>
<?php
return;
}
else
{	
	$i=0;
	$features="";
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{	
		$client[$i]['longitude']=$row['longitude'];
		$client[$i]['latitude']=$row['latitude'];
		$client[$i]['Designation']=$row['Designation'];		
		$i++;
		$features.="{position: new google.maps.LatLng(".$row['latitude'].",".$row['longitude']."),type:'".$row['Designation']."' },";
		
	}
	$features=substr($features, 0, -1);
	// {position: new google.maps.LatLng(33.5777099168704,-7.6415726405868),type: 'Snack'        }, 
	//echo ($features);

?>


<style>     
 #map { 
    width: 1260px;
    margin: 0 auto;
    height: 734px;
    padding: 0; }      
 html, body {        height: 80%;        margin: 0;        padding: 0;      }    
 </style>      
 <div id="map"></div>    
 <!--script src="js/jquery.min.js" type="text/javascript" ></script-->   
 <script>      
 var map;      
 var marker=null;      
 function initMap() {      
 map = new google.maps.Map(document.getElementById('map'), {          
 zoom: 18,		  
 center: new google.maps.LatLng(33.5777099168704,-7.6415726405868),          
 mapTypeId: 'roadmap'        });        
 var iconBase = '';        
 var icons = {
 grossiste: { icon: iconBase + 'Tabac.png'},  
 Snack: { icon: iconBase + 'Snack.png' }, 
 Laitterie: {icon: iconBase + 'Laitterie.png'}, 
 Tabac: { icon: iconBase + 'Tabac.png'          },          
 Epicerie: {icon: iconBase + 'Epicerie.png' }};        
 function addMarker(feature) {			
 var marker = new google.maps.Marker({ 
 position: feature.position,            
 icon: icons[feature.type].icon,            
 map: map          
 });        
 }      
 var features =[<?php echo $features; ?>];
/* [
 {position: new google.maps.LatLng(33.5777099168704,-7.6415726405868),type: 'Snack'        }, 
 {position: new google.maps.LatLng(33.5777099168704,-7.6415726405868),type: 'Snack'       } ];   
*/ 
 for (var i = 0, feature; feature = features[i]; i++) 
 {        
 addMarker(feature);     
 }   
 /*
 function autoUpdate() { 		   
 var tabCor="";			
 jQuery.get('test.txt', function(data) 
 {					
	 tabCor=data;					
	 var tabCordonne = tabCor.split(",");					
	 var newPoint = new google.maps.LatLng(tabCordonne[0],tabCordonne[1]);   					
	 if (marker) {     
	 marker.setPosition(newPoint);    
	 }    
	 else {      
	 marker = new google.maps.Marker({ position: newPoint,map: map}); 
	 }    
	 map.setCenter(newPoint);  	
 });  setTimeout(autoUpdate, 250); }   
 autoUpdate(); */
 }  
 </script>  
 <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&callback=initMap"> </script>

<?php 	}
} ?>
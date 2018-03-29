<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();


if (isset($_GET['infoClient'])){
?>
  <input type="button" value=""  class="close2" onclick="Fermer()" Style="float:right;"/>
<?php
//print_r($_GET);//return;
//echo $_GET['idClient'];
$sql = "SELECT IdClient,nom+ ' ' +c.prenom as nom,c.intitule,c.adresse,
(SELECT ISNULL(sum(factures.totalTTC),0) FROM factures WHERE year(cast(date AS date))=year(getdate()) AND factures.idClient=".$_GET['idClient'].") AS ca ,(SELECT count(*) FROM visites WHERE year(cast(visites.dateFin AS date))=year(getdate()) and idClient=".$_GET['idClient'].") AS nbrVisites
FROM clients c WHERE c.IdClient=".$_GET['idClient'];
$stmt = sqlsrv_query( $conn, $sql );
	if( $stmt === false ) 
	{
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
	//echo $sql ;
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ;
/******************************* Date of last **************************************/
$req = "
SELECT cast([date] AS date)AS d FROM factures f WHERE  f.IdFacture IN (SELECT max(IdFacture) FROM factures WHERE idClient=?)";
$stmt1 = sqlsrv_query( $conn, $req ,array($_GET['idClient']),array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	if( $stmt1 === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}

$nRes = sqlsrv_num_rows($stmt1);
$DateVisite="";
if($nRes != 0 )
{
$rowD = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC ) ;
$DateVisite = date_format($rowD["d"], 'd/m/Y');
}
else
$DateVisite ="aucune visite";

//$date = strtotime($rowD["d"]);

?>


<table  width="93%" cellspacing="10" border="0">
<tr>
<td align="right"><strong><u>Nom Complet </u> :</strong></td>
<td><?php echo  $row["nom"]; ?></td>
<td align="right"><strong><u>Adresse </u> :</strong></td>
<td><?php echo  wordwrap($row['adresse'], 50, "<br />\n", true); ?></td>

</tr>
<tr>
<td align="right"><strong><u>Intitulé </u> :</strong></td>
<td><?php echo  $row["intitule"]; ?></td>
<td align="right"><strong><u>Date Derniére Visite </u>: </strong></td>
<td><?php echo $DateVisite; ?></td>
</tr>
<tr>
<td align="right"><strong><u>CA annuelle </u>: </strong></td>
<td><?php echo number_format($row["ca"], 2, '.', ' ') . "  DH"; ?></td>
<td align="right"><strong><u>Nbr Visites</u>: </strong></td>
<td><?php echo $row["nbrVisites"]; ?></td>
</tr>
</table>
<?php


/************************Derniere Facture du client***************************/
$sql1 = "
SELECT df.IddetailFacture,g.IdGamme,mg.url,g.Designation as gamme,a.Designation as article, (type*qte) as qu,cast(f.[date] AS date) FROM factures f 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
INNER JOIN articles a ON df.idArticle=a.IdArticle 
INNER JOIN gammes g ON a.IdFamille=g.IdGamme
INNER JOIN mediaGammes mg ON g.IdGamme=mg.idGamme
WHERE idClient=".$_GET['idClient']." AND f.IdFacture IN (SELECT max(IdFacture) FROM factures WHERE idClient=".$_GET['idClient'].") 
ORDER BY g.IdGamme,qu desc";
$stmt2=sqlsrv_query($conn,$sql1,array(),array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt2 === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}
//echo $_GET['idClient']. " --" .$sql;
$nRes = sqlsrv_num_rows($stmt2);	
//echo $sql1;

//echo " xxx".$nRes;
if($nRes!=0)
{
$i=0;
		while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)){		
								$key= $row['IdGamme'];
								
							    $i=$i+1;
								
								if (!isset($gamme[$key])) {
									$gamme[$key] = array();
									$gamme[$key]['IdGamme']=$row['IdGamme'];
									$gamme[$key]['url']=$row['url'];
									$gamme[$key]['gamme']=$row['gamme'];									
									$i=0;
								} 
								
										if($gamme[$key]!=""){
												$gamme[$key][$i]['article']= $row['article'];
												$gamme[$key][$i]['qte']= $row['qu'];
										}
		
		}	
?>

	<DIV class="entete">
		<div class="divEntete" Style="width:220px;font-size:23px; vertical-align:middle" valign="middle" align="center">Gamme </div>
		<div class="divEntete" Style="width:600px;font-size:23px; vertical-align:middle" valign="middle" align="center">Article </div>
		<div class="divEntete" Style="width:132px;font-size:23px" align="center">Qte Vendue </div>
	</DIV>


<div  style="overflow-y:scroll;min-height:350px;max-height:350px"><!---->
<?php	$sum_article_qte=0;
foreach($gamme as $u=>$g){	?>
		<div style="background:white; width:1030px;" class="ligne">
			<div class="divText" Style="font-size:26px;"  align="center"><!--width:200px;height:48px;border:2px solid #e7e9ee;-->
				<?php  echo ucfirst($g['gamme']);//echo $g['gamme'];"<img src='../".$g['url']."' width='220' height:'150' title='' />" ?>
			</div>
			<div style="width:640px; display:block;"></div>
		</div>
		   <?php 
		   $sum_gamme_qte=0;
		   foreach($g as $article){	
						    if(is_array($article)){ ?>
							<div class="ligne">	
								<div style="width:240px; display:block;"></div>
								<div class="divText" style="width:600px;"> 
									<span style="margin-right:5px;"><?php  echo wordwrap(ucfirst($article['article']), 60, "<br />\n", true);?></span>
								</div> 
								<div class="divText" style="width:130px;TEXT-align:right;"> 
										<?php  echo $article['qte'];?>
								</div> 	
							</div> 
							
			<?php 
			$sum_gamme_qte+=intval($article['qte']);
			$sum_article_qte+=intval($article['qte']);			
				} 
			}
?>
<div style="TEXT-align:right;width:1030px; margin-bottom:5px;">
<u><strong>Total <?php  echo $g['gamme']; ?>: </strong><?php echo ($sum_gamme_qte); ?></u>
</div>
<?php 
}
?>
</div>
<div style="TEXT-align:right;width:1030px;margin-top:5px;">
<u><strong>TOTAL : </strong><?php echo ($sum_article_qte); ?></u>
</div>
<br/>
<?php
}else
{
echo "<br/><br/><br/><br/>";
}
?>
<div style="float:right; margin-right:25px;" >
<?php //echo $_GET['distance']; 
if(intval($_GET['distance']*1000)<= 100 ){ //en métre 1Km=1000M ?>
<input type="button" value="Démarrer visite" class="btn" onclick='demarrerVisite(<?php echo $_GET['idClient'];?>)' />
<?php } ?>
<input type="button" value="Itinéraire" class="btn" onclick='calculateRoute(<?php echo $_GET['from'];?>,<?php echo $_GET['to'];?> )' />

</div>

<script language="javascript" type="text/javascript">
function Fermer(){
	$("#boxClient").dialog('close');
}
		 function demarrerVisite(idClient)
		 {
		 jConfirm('Voulez-vous vraiment démarer une visite pour ce client', null, function(r) {
				if(r){
						$('#formRes').load('map.php?createVisite&idClt='+idClient);
					}
					});
		 }
		 function calculateRoute(from, to) {
		// alert(from);		 alert(to);
			// Center initialized to Naples, Italy
				var directionsService = new google.maps.DirectionsService();
				var directionsRequest = {
				  origin: from,
				  destination: to,
				  travelMode: google.maps.DirectionsTravelMode.DRIVING
				  //unitSystem: google.maps.UnitSystem.METRIC
				};
				directionsService.route(
				  directionsRequest,
				  function(response, status)
				  {
					if (status == google.maps.DirectionsStatus.OK)
					{
					  new google.maps.DirectionsRenderer({
						map: map,
						directions: response
					  });
					  $('#boxClient').dialog('close');
					}
					else
					  $("#error").append("Unable to retrieve your route<br/>");
				  }
				);

  }
  
</script>
<?php
exit;
}

if (isset($_GET['createVisite'])){
//echo "hereeeeeeeeeeeeeeeee";
$IdClt=$_GET['idClt'];
$dateD=date("d/m/Y");
$Hour=date("H:i");

$error="";
$reqInser1 = "INSERT INTO [dbo].[visites]  ([IdTournee] ,[datedebut]  ,[heureDebut]   ,[idClient] ,[idDepot]) 
				values(?,?,?,?,?)";
	$params1= array($_SESSION['IdTournee'],$dateD,$Hour,$IdClt,1) ;
	$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
	if( $stmt1 === false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
	}
	//---------------------------IdVisite--------------------------------//
$sql = "SELECT max(idvisite) as IdVisite FROM visites";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdVisite : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdVisite = sqlsrv_get_field( $stmt2, 0);

$_SESSION['IdVisite']=$IdVisite;	
	//---------------------------IdGroupClt--------------------------------//
$sql = "SELECT (idTypeClient) as IdGroupClt FROM clients";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdGroupClt : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdGroupClt = sqlsrv_get_field( $stmt2, 0);

$_SESSION['IdGroupe']=$IdGroupClt;

$_SESSION['IdClient']=$IdClt;	
	if( $error!="" ) 
	{
//	$var="";
	?>
		<script type="text/javascript"> 
		jAlert("Veuillez essayer une autre fois.","Message");
		</script>
	<?php
	}
	else
	{
		//echo "Succes";
		//$var="";	
		?>
		<script type="text/javascript"> 
			window.location.href = 'catalogue4.php';
		</script>
	<?php
	//	header("Location: chargementVendeur.php");
	}	
	exit;
}
if(isset($_GET['map'])){ ?>


<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
			
<div>&nbsp;> <span  Class="TitleHead" id="DmrTrn">Démarrer tournée</span></div>&nbsp;> Map</div>

<div style="clear:both;"></div>
<?php
$whereType="";
$whereClass="";
$Types="";
$Classes="";

if(isset($_POST['Type']))
{
foreach($_POST['Type'] as $Type){
$Types.=$Type . ",";
}
$whereType=" and c.idTypeClient in (".rtrim($Types,",").")";
}

if(isset( $_POST['classe']))
{
foreach($_POST['classe'] as $Classe){
$Classes.="'".$Classe . "',";
}
$whereClass=" and classe in (".rtrim($Classes,",").")";
}

$sql = "SELECT c.IdClient,c.nom,c.prenom,c.adresse,c.longitude,c.latitude,tc.Designation  
FROM clients c INNER JOIN typeClients tc ON c.idTypeClient=tc.idType WHERE c.departement=? ". $whereType .$whereClass ;//dc.idColisage *
//echo $sql;return;
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
	$longitude_Secteur="";
	$latitude_Secteur="";
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{	
		$client[$i]['longitude']=$row['longitude'];
		$client[$i]['latitude']=$row['latitude'];
		$client[$i]['Designation']=$row['Designation'];		
		$i++;
		$features.="{position: new google.maps.LatLng(".$row['latitude'].",".$row['longitude']."),type:'".$row['Designation']."',name:'".$row['nom'] ." ".$row['prenom']."',adresse:'".$row['adresse']."',idClient:".$row['IdClient']."},";
		
	}
	
	$features=substr($features, 0, -1);
	
	//--Get Latitude and Longitude of Secteur--------------------------------------------------------------
	$sql2 = "SELECT longitude,latitude FROM departements WHERE iddepartment=".$_POST['Secteur'];
	$params2 = array();	
	$stmt2=sqlsrv_query($conn,$sql2);
	if( $stmt2 === false ) {
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
	$row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC);
	$longitude_Secteur= $row[0];
	$latitude_Secteur= $row[1];
	//echo "heeeeeeeezr : ".$sql2;
	//echo $longitude_Secteur;echo $latitude_Secteur; return;
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
 <script src="js/jquery.geolocation.js"></script>
 <script> 
$("#DmrTrn").click(function(){
$('#formRes').html('<center><br/><br/>Merci de patienter pendant le chargement du map <br/><img src="../images/loading2.gif" /></center>').load('map.php?search');
});	
var pos; var destination="";  
 var marker=null;   var marker2=null; var marker3=null; 
var Center = null;
 function initMap() {   
var Center = new google.maps.LatLng(<?php echo $latitude_Secteur;?>,<?php echo $longitude_Secteur;?> );  
 map = new google.maps.Map(document.getElementById('map'), {          
 zoom: 15,		  
 center: Center,          
 mapTypeId: 'roadmap'        });        
 var iconBase = '';        
 var icons = {
 vendeur:{ icon: iconBase + 'vnd.png'}, 
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
//---------------------------Add MArker-------------------------------------------------------------
 for (var i = 0, feature; feature = features[i]; i++) 
 {        
 //addMarker(feature); 
 var marker = new google.maps.Marker({ 
 position: feature.position,            
 icon: icons[feature.type].icon,            
 map: map          
 }); 

 
 
 google.maps.event.addListener(marker, 'click', (function(marker, feature) {
        return function() {
         // infowindow.setContent(locations[i][0]);
         // infowindow.open(map, marker);
		 // alert("here "+feature.name+"-"+feature.adresse);//+feature[i][2]
		 // jAlert("Voulez-vous démarer une visite pour le client "+feature.name + " dont l'adresse est " +feature.adresse,"Message");

			//alert(distance);
				/*		var ren,ser;			
						ren = new google.maps.DirectionsRenderer({
						'draggable': true
					  });
				  ren.setMap(map);
				  ren.setPanel(document.getElementById("directionsPanel"));
				  ser = new google.maps.DirectionsService();

				  //Cria a rota, o DirectionTravelMode pode ser: DRIVING, WALKING, BICYCLING ou TRANSIT
				  ser.route({
					'origin': pos,
					'destination': feature.position,
					'travelMode': google.maps.DirectionsTravelMode.WALKING
				  }, function(res, sts) {
					if (sts == 'OK') ren.setDirections(res);
					   alert( res.routes[0].legs[0].distance.value ); // the distance in metres
					   
					 //  $('#formRes').load('map.php?createVisite&idClt='+feature.idClient);
				//var distance= calcCrow(pos.lat,pos.lng,marker.getPosition().lat(),marker.getPosition().lng());
				//alert(distance);
				  })*/	
				  	var distance= calcCrow(pos.lat,pos.lng,marker.getPosition().lat(),marker.getPosition().lng()) ;//km---------------------------------------------
	//alert(distance);

var json1 = JSON.stringify( pos );	
var json2 = JSON.stringify( feature.position );

$('#boxClient').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("mapClient.php?infoClient&idClient="+feature.idClient+"&from="+json1+"&to="+json2+"&distance="+distance).dialog('open');

/*	jConfirm('Voulez-vous vraiment démarer une visite pour le client '+feature.name + ' dont l\'adresse est '+feature.adresse, null, function(r) {
			if(r)	{
						
					 $('#formRes').load('map.php?createVisite&idClt='+feature.idClient);
			
									
						}
						else
						{
			calculateRoute(pos,feature.position);	
					}
					})*/
					
					}
				  })(marker, feature));    
 }
    //This function takes in latitude and longitude of two location and returns the distance between them as the crow flies (in km)
    function calcCrow(lat1, lon1, lat2, lon2) 
    {
      var R = 6371; // km
      var dLat = toRad(lat2-lat1);
      var dLon = toRad(lon2-lon1);
      var lat1 = toRad(lat1);
      var lat2 = toRad(lat2);

      var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
      var d = R * c;
      return d;
    }	
	var ren = new google.maps.DirectionsRenderer({
						'draggable': true
					  });
				  ren.setMap(map);
				 
				var  ser = new google.maps.DirectionsService();

	 function calculateRoute(from, to) {
        // Center initialized to Naples, Italy

   var directionsService = new google.maps.DirectionsService();
        var directionsRequest = {
          origin: from,
          destination: to,
          travelMode: google.maps.DirectionsTravelMode.DRIVING
          //unitSystem: google.maps.UnitSystem.METRIC
        };
        directionsService.route(
          directionsRequest,
          function(response, status)
          {
            if (status == google.maps.DirectionsStatus.OK)
            {
              new google.maps.DirectionsRenderer({
                map: map,
                directions: response
              });
            }
            else
              $("#error").append("Unable to retrieve your route<br/>");
          }
        );
	
	
				  //Cria a rota, o DirectionTravelMode pode ser: DRIVING, WALKING, BICYCLING ou TRANSIT
				/*  ser.route({
					'origin': from,
					'destination': to,
					'travelMode': google.maps.DirectionsTravelMode.WALKING
				  }, function(res, sts) {
					if (sts == 'OK') ren.setDirections(res);
					  // alert( res.routes[0].legs[0].distance.value ); // the distance in metres
					   
					 //  $('#formRes').load('map.php?createVisite&idClt='+feature.idClient);
				//var distance= calcCrow(pos.lat,pos.lng,marker.getPosition().lat(),marker.getPosition().lng());
				//alert(distance);
				  })*/
		
      }


    // Converts numeric degrees to radians
    function toRad(Value) 
    {
        return Value * Math.PI / 180;
    }
/* google.maps.event.addListener(marker, "position_changed", function() {
      var position = marker.getPosition();
    });

*/
//Position of Secteur--------------------------------------------------------------
var marker2 = new google.maps.Marker({ 
        position: Center, 
        draggable: false, 
        animation: google.maps.Animation.DROP,           
 map: map          
    }); 
//Position actuel------------------------------------------------------------------
 var marker3 = new google.maps.Marker({   
        draggable: false, 
        animation: google.maps.Animation.DROP, 		
		icon: icons['vendeur'].icon,           
		map: map          
    }); 
/*function watchMyPosition(position) 
{
//alert("-----Your position is: " + position.coords.latitude + ", " + position.coords.longitude + " (Timestamp: "  + position.timestamp + ")<br />");
  
  
  pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
	   marker3.setPosition(pos);    
		
}
	
myPosition = $.geolocation.watch({win: watchMyPosition}); 
*/
/**********************************************************************************************/
function autoUpdate() { 		   
	     if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                     pos = {lat: position.coords.latitude, lng: position.coords.longitude};
                    //infoWindow = new google.maps.InfoWindow({map: map});
                   // infoWindow.setPosition(pos);
                   // infoWindow.setContent("Found your location <br />Lat : "+position.coords.latitude+" </br>Lang :"+ position.coords.longitude);
                   // map.panTo(pos);
				    marker3.setPosition(pos);  
					
			
					//calculateRoute(pos,destination);	
                });
			//	alert("Herrrrrrre Geo");
				}
	 setTimeout(autoUpdate, 250); 
 }   
 autoUpdate();

/*
var id, target, options;

function success(position) {
alert("Your position is: " + position.coords.latitude + ", " + position.coords.longitude + " (Timestamp: "  + position.timestamp + ")<br />");
  
  
  pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
	   marker3.setPosition(pos);    
}

function error(err) {
  console.warn('ERROR(' + err.code + '): ' + err.message);
}

target = {
  latitude : 0,
  longitude: 0
};

options = {
  enableHighAccuracy: false,
  timeout: 5000,
  maximumAge: 0
};

id = navigator.geolocation.watchPosition(success, error, options);
*/
/*
navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
	  alert( position.coords.latitude+ " , "+position.coords.longitude );
	  var pos2 = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);  
	   var marker3 = new google.maps.Marker({ 
        position: pos2, 
        draggable: false, 
        animation: google.maps.Animation.DROP,           
		map: map          
    }); 
	})*/
	
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
 <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&callback=initMap&sensor=true"> </script>
 <!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&libraries=places&callback=initMap"
         async defer></script-->
<?php 	}
exit;
} 

if(isset($_GET['chargerSecteur'])){

	$Options = '<select multiple="multiple" name="Secteur" id="Secteur" class="Select Secteur"  tabindex="3" style="width:280px" >';
	$sql = "SELECT d.iddepartment,d.codeDepartement,d.Designation FROM departements d where idVille=?";
			//echo $sql. $_GET['IdZone']; return;
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['IdVille']), array( "Scrollable" => 'static' ) );         
			/*   if( $reponse === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}*/
			
		$nRes = sqlsrv_num_rows($reponse);
		
		if($nRes != 0)
		 while ($donnees =  sqlsrv_fetch_array($reponse))
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

if(isset($_GET['search'])){ 
$error="";
/* --------------------Begin transaction---------------------- */
			if ( sqlsrv_begin_transaction( $conn ) === false ) {
				$error="Erreur : ".sqlsrv_errors() . " <br/> ";
			}	
			$sql="SELECT IdTournee FROM tournees WHERE idVendeur=? AND dateDebut= convert(NVARCHAR, getdate(), 103) AND datefin IS null";
			$stmt=sqlsrv_query($conn,$sql,array($_SESSION['IdVendeur']),array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
				if( $stmt === false ) 
				{
						$errors = sqlsrv_errors();
						echo "Erreur : ".$errors[0]['message'] . " <br/> ";
						return;
				}
			$nRes = sqlsrv_num_rows($stmt);	
			//echo "herrrrrrrrrrrrre ".$nRes; return;
			if($nRes==0)
			{ 
			//-----------------------------Demarage de tournée----------------------------------------
				$dateD=date("d/m/Y");
				$Hour=date("H:i");
				
				$reqInser1 = "INSERT INTO [dbo].[tournees]  ([dateDebut]  ,[heureDebut]  ,[idDepot] ,[idVendeur],idVehicule) 
								values(?,?,?,?,?)";
					$params1= array($dateD,$Hour,1,$_SESSION['IdVendeur'],0) ;
					
					$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
					if( $stmt1 === false ) {
						$errors = sqlsrv_errors();
						$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
					}
						//echo "INSERT ".$error;
						//return;
				//---------------------------IDTournee--------------------------------//
				$sql = "SELECT max(IdTournee) as IdTournee FROM tournees";
				$stmt2 = sqlsrv_query( $conn, $sql );
				if( $stmt2 === false ) {
					$errors = sqlsrv_errors();
					$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
				}
				//echo "IDTournee".$error;

				sqlsrv_fetch($stmt2) ;
				$IdTournee = sqlsrv_get_field( $stmt2, 0);
				$_SESSION['IdTournee']=$IdTournee;
				//header('Location: map.php');
				//location.href = "map.php";
					
				if($error=="" ) {
					 sqlsrv_commit( $conn );
					
				//unset($_SESSION['lignesCat']);
				} else {
					 sqlsrv_rollback( $conn );
					 echo $error;
				}
			}
			
?>
<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
					
<div  onclick=" " >&nbsp;> <span  Class="TitleHead" >Démarrer tournée</span></div></div>

<div style="clear:both;"></div>
<form id="formAdd" method="post" action="mapp.php" name="formAdd"> 
<div style=" width:800px; height:300px; margin:auto; margin-top:60px;">
<div Style="display: flex;height:100px;">
	
		<div  Style="width:90px; text-align: center;vertical-align: middle;line-height: 65px;font-size:24px;">Ville :</div>
		<div>
			<select id="Ville" name="Ville" multiple="multiple"  Class="Select Ville" style="width:280px">
					
								 <?php $sql = "select idville, Designation from villes ";
							   $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
									   while ($donnees =  sqlsrv_fetch_array($reponse))
									   {
									   ?>
									   <option value="<?php echo $donnees['idville'] ?>"><?php echo $donnees['Designation']?></option>
								 <?php
								  }
								 ?>
			</select>
		</div>


		<div Style="width:140px; text-align: center;vertical-align: middle;line-height: 65px;font-size:24px">Secteur :</div>
		<div id="Secteurs" style="width:300px;">			
					<select multiple="multiple" id="Secteur" name="Secteur" Class="Select Secteur" style="width:280px">
					</select>
		</div>

</div>
<div Style="display: flex;">
	
		<div  Style="width:90px; text-align: center;vertical-align: middle;line-height: 65px;font-size:24px">Type :</div>
		<div>
			<select id="Type" name="Type[]" multiple="multiple"  Class="Select Type" style="width:280px">
					
								 <?php 	
								 $req="	select IdType,Designation 
									from typeclients t 		";		
							   $reponse=sqlsrv_query( $conn, $req, array(), array( "Scrollable" => 'static' ) );         
									   while ($donnees =  sqlsrv_fetch_array($reponse))
									   {
									   ?>
									   <option value="<?php echo $donnees['IdType'] ?>"><?php echo $donnees['Designation']?></option>
								 <?php
								  }
								 ?>
			</select>
		</div>


		<div Style="width:140px; text-align: center;vertical-align: middle;line-height: 65px;font-size:24px">Classe :</div>
		<div>			
					<select multiple="multiple" id="classe" name="classe[]" Class="Select classe" style="width:280px">
					 <option value="a">A</option>
					 <option value="b">B</option>
					 <option value="c">C</option>
					</select>
		</div>

</div>
<div style="float:right; margin-right:5px;margin-top:25px;"><input type="button" value="Rechercher" class="btn"  onclick="Terminer()"/></div>
</div>
</form>
<script language="javascript" type="text/javascript">
$('#Ville').multipleSelect({
		  filter: true,placeholder:'S&eacute;lectionnez la Ville ',single:true,maxHeight: 300,
		      onClick: function(view) {
				if(view.checked = 'checked')
				$('#Secteurs').load("map.php?chargerSecteur&IdVille="+view.value);
				
				var Ville =$('#Ville').val();
				if(Ville!="") {
					$('div.Ville').removeClass('erroer');
					$('div.Ville button').css("border","1px solid #ccc").css("background","#fff");
				}
}});
$('#Type').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Type ',maxHeight: 300});
$('#Secteur').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Secteur ',single:true,maxHeight: 300});		
$('#classe').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez la Classe ',maxHeight: 300});	
</script>
<?php
exit;
}
?>
<?php include("header.php"); ?>



<div id="formRes" ></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<div id="boxClient"> </div>
<?php
include("footer.php");
?>

<script language="javascript" type="text/javascript">

$(document).ready(function() {
		//$.validator.messages.required = '';
		 var map;   
  		$('#formRes').html('<center><br/><br/>Merci de patienter pendant le chargement du map <br/><img src="../images/loading2.gif" /></center>').load('map.php?search');
				$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	1100,
					height			:	700,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Info Client',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						},
						"Terminer "	: function() {
							terminer();
						
						}
					 }
			});
});
function rechercher(){
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'map.php?aff'})
		clearForm('formRechF',0);
	}
function control(index)
{

 alert( "Handler for .blur() called." + $( ".textQte" ).attr("name") + index);

}
/*$( ".text" ).blur(function() {
	
 alert( "Handler for .blur() called." );//+ $( "#qtech" ).attr("name")
  
});	*/

function Terminer(){
	    $('#formAdd').validate({ 	
			errorPlacement: function(error, element) { //just nothing, empty  
					},		
		rules: {
			'Ville': "required",
			'Secteur': "required"
             } 
		});
						

	var test=$('#formAdd').valid();
	verifSelect2('Ville');
	verifSelect2('Secteur');
	
		if(test==true) {
		/*$('#formAdd').submit();
        $('#formAdd').action='mapp.php';
        $('#formAdd').target='mapp.php?map';	*/	
			
							$('#formAdd').ajaxSubmit({
									target			:	'#formRes',
									url				:	'map.php?map',
									method			:	'post'
							}); 
						
							return false;
							
 
			
		//})
	}
}
</script>

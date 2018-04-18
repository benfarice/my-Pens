<?php
include("../php.fonctions.php");
require_once('../connexion.php');
if(!isset($_SESSION))
{
session_start();
} 

?>
<?php
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
<td align="right"><strong><u>Intitulé</u> :</strong></td>
<td><?php echo  $row["intitule"]; ?></td>
<td align="right"><strong><u>Adresse </u> :</strong></td>
<td><?php echo  wordwrap($row['adresse'], 50, "<br />\n", true); ?></td>

</tr>
<tr>

<td align="right"><strong><u>Date Derniére Visite </u>: </strong></td>
<td><?php echo $DateVisite; ?></td>
<td align="right"><strong><u>Nbr Visites</u>: </strong></td>
<td><?php echo $row["nbrVisites"]; ?></td>
</tr>
<tr>
<td align="right"><strong><u>CA annuelle </u>: </strong></td>
<td><strong><u><?php echo number_format($row["ca"], 2, '.', ' ') . "  DH"; ?></u></strong></td>

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
	$params1= array($_SESSION['IdTournee'],$dateD,$Hour,$IdClt,1) ;//$_SESSION['IdTournee']
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

$_SESSION['IdGroupClt']=$IdGroupClt;

$_SESSION['IdClient']=$IdClt;	
	if( $error!="" ) 
	{
	echo "eror : ".$error;
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
if (isset($_GET['map'])){ ?>
<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
					
<div  onclick=" " >&nbsp;> <span  Class="TitleHead" >Démarrer visite</span></div></div>
<div style="clear:both;"></div>
<?php
$whereVille="";
if(isset($_GET['Ville']))
{
$whereVille=" where v.Designation ='".$_GET['Ville']."'";
}
$sql = "SELECT c.IdClient,c.nom,c.prenom,c.adresse,c.longitude,c.latitude,tc.Designation as Type ,v.Designation as Ville FROM clients c INNER JOIN villes v ON c.ville=v.idville INNER JOIN typeClients tc ON c.idTypeClient=tc.idType ". $whereVille ;//dc.idColisage *
//echo $sql;return;
$params = array();	
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
	$clients="";
	$longitude_Secteur="";
	$latitude_Secteur="";
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{	
	/*	$client[$i]['longitude']=$row['longitude'];
		$client[$i]['latitude']=$row['latitude'];
		$client[$i]['Designation']=$row['Designation'];	*/	
		$i++;
		$features.="{position: new google.maps.LatLng(".$row['latitude'].",".$row['longitude']."),type:'".$row['Type']."',name:'".$row['nom'] ." ".$row['prenom']."',adresse:'".$row['adresse']."',idClient:".$row['IdClient'].",lat:".$row['latitude'].",lng:".$row['longitude']."},";
		
	}
	
	$features=substr($features, 0, -1);
	
	//--Get Latitude and Longitude of Secteur--------------------------------------------------------------
	/*$sql2 = "SELECT longitude,latitude FROM departements WHERE iddepartment=".$_POST['Secteur'];
	$params2 = array();	
	$stmt2=sqlsrv_query($conn,$sql2);
	if( $stmt2 === false ) {
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
	$row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC);
	$longitude_Secteur= $row[0];
	$latitude_Secteur= $row[1];*/
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
 html, body { height: 80%; margin: 0; padding: 0;      }    
 </style>      
 <div id="map"></div>    
 <!--script src="js/jquery.min.js" type="text/javascript" ></script-->   
 <script src="js/jquery.geolocation.js"></script>
<script language="javascript" type="text/javascript">   
$(document).ready(function(){
	initMap();
});
     
 var marker=null;   var marker2=null; var marker3=null; 
 var Center = null;
 function initMap() {   

var Center = new google.maps.LatLng(33.566300, -7.629602 );  
 map = new google.maps.Map(document.getElementById('map'), {          
 zoom: 16,		  
//center: Center,          
 mapTypeId: 'roadmap'        });        
 var iconBase = '';        
 var icons = {
				 vendeur:{ icon: iconBase + 'camion.png'},  
				 grossiste: { icon: iconBase + 'Tabac1.png'},   
				 grossiste2: { icon: iconBase + 'Tabac2.png'},  
				 grossiste3: { icon: iconBase + 'Tabac3.png'},  
				 Snack: { icon: iconBase + 'Snack1.png' }, 
				 Snack2: { icon: iconBase + 'Snack2.png' }, 
				 Snack3: { icon: iconBase + 'Snack3.png' }, 
				 Laitterie: {icon: iconBase + 'Laitterie1.png'}, 
				 Laitterie2: {icon: iconBase + 'Laitterie2.png'},
				 Laitterie3: {icon: iconBase + 'Laitterie3.png'},
				 Epicerie: {icon: iconBase + 'Epicerie1.png' },
				 Epicerie2: {icon: iconBase + 'Epicerie2.png' },
				 Epicerie3: {icon: iconBase + 'Epicerie3.png' }}; 
     
 function addMarker(feature) {			
 var marker = new google.maps.Marker({ 
 position: feature.position,            
 //icon: icons[feature.type].icon,            
 map: map          
 });        
 }

//Position actuel------------------------------------------------------------------
/* var marker3 = new google.maps.Marker({   
        draggable: false, 
        animation: google.maps.Animation.DROP, 		
		icon: icons['vendeur'].icon,           
		map: map          
    }); */
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
// Converts numeric degrees to radians
    function toRad(Value) 
    {
        return Value * Math.PI / 180;
    }
	// calculateRoute pour tracer l'itinéraire 
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
  }
//Position actuel------------------------------------------------------------------
 var marker3 = new google.maps.Marker({   
        draggable: false, 
        animation: google.maps.Animation.DROP, 		
		icon: icons['vendeur'].icon,           
		map: map          
    }); 

/**********************************************************************************************/
function autoUpdate() { 		   
	     if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                    pos = {lat: position.coords.latitude, lng: position.coords.longitude};
					// alert(pos.lat);
                    // infoWindow = new google.maps.InfoWindow({map: map});
                    // infoWindow.setPosition(pos);
                    // infoWindow.setContent("Found your location <br />Lat : "+position.coords.latitude+" </br>Lang :"+ position.coords.longitude);
                    // map.panTo(pos);
				    marker3.setPosition(pos);  
					
			
					//calculateRoute(pos,destination);	
                });
			//	alert("Herrrrrrre Geo");
				}
	// actualiser position chaque 250 mille seconde
	 setTimeout(autoUpdate, 250); 
 }   
 autoUpdate();	
/*
function watchMyPosition(position) 
{
//alert("Your position is: " + position.coords.latitude + ", " + position.coords.longitude + " (Timestamp: "  + position.timestamp + ")<br />");

  var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
	   map.setCenter(pos);    
 var marker3 = new google.maps.Marker({ 
        position: pos,   
        draggable: true, 
        animation: google.maps.Animation.DROP, 		
		icon: icons['vendeur'].icon,           
		map: map          
    }); 
}
$.geolocation.get({success:watchMyPosition}); 
*/

 var features =[<?php echo $features; ?>];
/* [
 {position: new google.maps.LatLng(33.5777099168704,-7.6415726405868),type: 'Snack'        }, 
 {position: new google.maps.LatLng(33.5777099168704,-7.6415726405868),type: 'Snack'       } ];   
*/ 
//---------------------------Add MArker-------------------------------------------------------------
 var currentPosition="";
 if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                     currentPosition = {lat: position.coords.latitude, lng: position.coords.longitude}; 
					 map.setCenter(currentPosition);  

 //}
  
 for (var i = 0, feature; feature = features[i]; i++) 
 { 
	var distance= calcCrow(currentPosition.lat,currentPosition.lng,feature.lat,feature.lng);//Km
	//alert(" "+distance);
 //addMarker(feature); 

 if(distance <= 40)//40Km
 {

 var marker = new google.maps.Marker({ 
 position: feature.position,            
 //icon: icons[feature.type].icon,            
 map: map          
 });  
 
 var clt=JSON.parse($.cookie("client"));
if(clt[feature.idClient])
{
	var type=feature.type+clt[feature.idClient];//Consultation
	marker.setIcon(icons[type].icon);
}
else
{
	marker.setIcon(icons[feature.type].icon);
}
 google.maps.event.addListener(marker, 'click', (function(marker, feature) {
        return function() {
         // infowindow.setContent(locations[i][0]);
         // infowindow.open(map, marker);
		 // alert("here "+feature.name+"-"+feature.adresse);//+feature[i][2]
		 // jAlert("Voulez-vous démarer une visite pour le client "+feature.name + " dont l'adresse est " +feature.adresse,"Message");
	var distance= calcCrow(pos.lat,pos.lng,marker.getPosition().lat(),marker.getPosition().lng()) ;//km---------------------------------------------
	//alert(distance);
//*******Cookie******Read And Write****************************************/
var clt=JSON.parse($.cookie("client"));
if(!clt[feature.idClient])
{
var type=feature.type+"2";//Consultation
marker.setIcon(icons[type].icon);
clt[feature.idClient]="2";//Consultation
$.cookie("client",JSON.stringify(clt));
}
//************************************************************************/
var json1 = JSON.stringify( pos );	//position du vendeur actuel
var json2 = JSON.stringify( feature.position );//position du  client selectionner
$('#boxClient').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("mapClient.php?infoClient&idClient="+feature.idClient+"&from="+json1+"&to="+json2+"&distance="+distance).dialog('open');	
	//calculateRoute(pos,feature.position);
	/*jConfirm('Voulez-vous vraiment démarer une visite pour le client '+feature.name + ' dont l\'adresse est '+feature.adresse, null, function(r) {

					if(r){
						$('#formRes').load('map.php?createVisite&idClt='+feature.idClient);
					}
					else
					{
						var distance= calcCrow(pos.lat,pos.lng,marker.getPosition().lat(),marker.getPosition().lng())*1000 ;//Meter
						//alert(distance);
						calculateRoute(pos,feature.position);	
					}
			
		})*/
        }
      })(marker, feature));    
 } 

} 
 }) 
}


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

 <!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&libraries=places&callback=initMap"
         async defer></script-->
<?php 	}
exit;
} 



if(isset($_GET['search'])){ ?>
<DIV style="" class="headVente">
	<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
	<div class="TitleHead">Accueil</div>
</div>
<div style="clear:both;"></div>
<style>     
 #map { 
    width: 1260px;
    margin: 0 auto;
    height: 734px;
    padding: 0; }      
 html, body {        height: 80%;        margin: 0;        padding: 0;      }    
 </style>    
<input type="hidden" value="" name="Ville" id="Ville" /> 
 <div id="map"></div>    
 <!--script src="js/jquery.min.js" type="text/javascript" ></script-->   
 
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
  		//$('#formRes').load('mapClient.php?search');
		 var map; 
		 
		 if(!$.cookie("client"))
		{
			var clt={};
			$.cookie("client",JSON.stringify(clt));
		}
	$('#formRes').html('<center><br/><br/>Merci de patienter pendant le chargement du map... <br/><img src="../images/loading2.gif" /></center>');
	getLocation();
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
   
var geocoder;
function getLocation() {

if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}
function showPosition(position) {
//alert("showPosition");
	
	var latlon =new google.maps.LatLng(parseFloat(position.coords.latitude),parseFloat(position.coords.longitude));
	
//	var latlon =new google.maps.LatLng(parseFloat("33.575273"),parseFloat("-7.6154561"));
	$("#Lat").val( position.coords.latitude);
	$("#Lng").val( position.coords.longitude);
	getAddress(latlon);

}

function getAddress(latLng) {
//alert(latLng);
geocoder = new google.maps.Geocoder();
geocoder.geocode( {'latLng': latLng},
	  function(results, status) {
		if(status == google.maps.GeocoderStatus.OK) {		
			var Secteur="";	
		  if(results[1]) {
			  //get secteur	
			 var arrDetailAdr = results[1].address_components;
						// iterate through address_component array
				  //alert(results[0].formatted_address);		
			//alert(arrDetailAdr);
			//document.getElementById("Adresse").value = results[0].formatted_address;
			
		/*	   jQuery.each(arrDetailAdr, function(i, val) {
					jQuery.each(val, function(i, a) {
					alert( a);          
					})         
				});*/
		    for (ac = 0; ac < arrDetailAdr.length; ac++) {
				
			
					if (arrDetailAdr[ac].types[0] == "locality"){ // city
									//alert(arrDetailAdr[ac].toString());
									var ville=arrDetailAdr[ac].long_name;
									var state = arrDetailAdr[4].short_name;
								    //alert(ville);
									$('#formRes').load('mapClient.php?map&Ville='+ville);
									//document.getElementById("Ville").value = ville;
									
}

					
			}
				 
			
		  }
		  else {
			jAlert("Aucun résultats","Message");
		  }
		}
		else {
		  jAlert(status,"Message");
		}
	  });
	}




function rechercher(){
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'mapClient.php?aff'})
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
									url				:	'mapClient.php?map',
									method			:	'post'
							}); 
						
							return false;
							
 
			
		//})
	}
}
</script>
 <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4"> </script>
<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();
$_SESSION['IdVendeur']="1";
?>
<?php
if (isset($_GET['createVisite'])){
//echo "hereeeeeeeeeeeeeeeee";
$IdClt=$_GET['idClt'];
$dateD=date("d/m/Y");
$Hour=date("H:i");

$error="";
$reqInser1 = "INSERT INTO [dbo].[visites]  ([IdTournee] ,[datedebut]  ,[heureDebut]   ,[idClient] ,[idDepot]) 
				values(?,?,?,?,?)";
	$params1= array("0",$dateD,$Hour,$IdClt,1) ;
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
			window.location.href = 'catalogue3.php';
		</script>
	<?php
	//	header("Location: chargementVendeur.php");
	}	
	exit;
}
if (isset($_GET['map'])){ ?>


<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
			
<div>&nbsp;> <span  Class="TitleHead">Démarrer tournée</span></div>&nbsp;> Map</div>

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
 var map;      
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
		jConfirm('Voulez-vous vraiment démarer une visite pour le client '+feature.name + ' dont l\'adresse est '+feature.adresse, null, function(r) {
					if(r)	{
							// alert("here "+feature.name+"-"+feature.idClient);
							 $('#formRes').load('map.php?createVisite&idClt='+feature.idClient);
							}
			
		})
        }
      })(marker, feature));    
 }  
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
function watchMyPosition(position) 
{
//alert("Your position is: " + position.coords.latitude + ", " + position.coords.longitude + " (Timestamp: "  + position.timestamp + ")<br />");
  
  
  var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
	   marker3.setPosition(pos);    
/* var marker3 = new google.maps.Marker({ 
        position: pos,   
        draggable: true, 
        animation: google.maps.Animation.DROP, 		
		icon: icons['vendeur'].icon,           
		map: map          
    }); */
}
	
myPosition = $.geolocation.watch({win: watchMyPosition}); 



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
 <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&callback=initMap"> </script>
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

if(isset($_GET['search'])){ ?>
<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
					
<div  onclick=" " >&nbsp;> <span  Class="TitleHead">Démarrer tournée</span></div></div>

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

<?php
include("footer.php");
?>

<script language="javascript" type="text/javascript">

$(document).ready(function() {
		//$.validator.messages.required = '';
  		$('#formRes').load('map.php?search');
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

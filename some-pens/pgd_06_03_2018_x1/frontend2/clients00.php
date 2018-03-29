<?php
include("../php.fonctions.php");
require_once('../connexion.php');
if(!isset($_SESSION))
{
session_start();
} 
if (isset($_GET['TypeClt2'])){ ?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<a  onclick="$('#BoxMap').dialog('close');">Fermer</a>&nbsp;&nbsp;
<div Class="TitleHead" onclick="getActivite()" >Activité > </div>
<div Class="TitleHead"  onclick="getType()" >Type</div> >
<div Class="TitleHead"  onclick="getType()" >CSP</div>
</div>		
<div id="tabAct">
  <ul>
    <li><a href="#Activite">Activité</a></li>
    <li><a href="#DtlClt">Détail client</a></li>
  
  </ul>

  <div id="Activite"></div>
  <div id="DtlClt"></div>
</div>
<script language="javascript" type="text/javascript">
  $( "#tabAct" ).tabs();
  
</script>
	<?php

exit;
}
if (isset($_GET['GetCsb'])){ 

?>
<div class="HeadGlo">
	<DIV  class="headContent">
		<span Class="TitleHead" onclick="getActivite()" >Activité </span>> 
<span Class="TitleHead"  onclick="getType()" >Type</span> >
<span Class="TitleHead"  onclick="getCSB_Clt()" >CSP</span>
	</div>
	<DIV  class="closebtn" onclick="$('#BoxMap').dialog('close');" >
		
	</div>
</div>	
<ul class="bxslider" style="margin:0;padding:0;">
	
		  <div class="cadreActiv hvr-grow"  id="CadreCsbA"  onclick="GetCsb('A')">
			<div  class="childActiv"> 
			 <img src="../csp/1star.jpg"  width="222" height="227"/><br>
			
			</div>
		  </div>
		    <div class="cadreActiv hvr-grow" id="CadreCsbB"   onclick="GetCsb('B')">
			<div  class="childActiv"> 
			 <img src="../csp/2stars.jpg"  width="222" height="227"/><br>
				
			</div>
		  </div>
		  
	 <div class="cadreActiv hvr-grow" id="CadreCsbC"   onclick="GetCsb('C')">
			<div  class="childActiv"> 
			 <img src="../csp/3stars.jpg"  width="222" height="227"/><br>
		
			</div>
		  </div>
 <div class="cadreActiv hvr-grow" id="CadreCsbD"   onclick="GetCsb('D')">
			<div  class="childActiv"> 
			 <img src="../csp/4stars.jpg" width="222" height="227"/><br>
	
			</div>
		  </div>
	</ul>			

<script language="javascript" type="text/javascript">
  // initialize bxSlider

  function GetCsb(Csb){	
 		$('#IdCsb').val(Csb);
		$('#Csb').val(Csb);
		$('#BoxMap').html("").dialog("close");
	
	}

	
$(document).ready(function(){	

if(	$('#IdCsb').val() !=""){
			var idActv=$('#IdCsb').val() ;
			$('#CadreCsb'+idActv).addClass("Active");
		}
	
});
</script>
	<?php
	

exit;
}

if (isset($_GET['GetTypeClt2'])){ 

?><div class="HeadGlo">
	<DIV  class="headContent">
		<span Class="TitleHead" onclick="getActivite()" >Activité </span>> 
<span Class="TitleHead"  onclick="getType()" >Type</span> >
<span Class="TitleHead"  onclick="getCSB_Clt()" >CSP</span>
	</div>
	<DIV  class="closebtn" onclick="$('#BoxMap').dialog('close');" >
		
	</div>
</div>	
<?php
$sql="select  * from typeclients";
			 $params = array();	
	//parcourir($params);
	//echo "<br>".$sql;
	//parcourir($params);
			$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
			if( $stmt === false ) {
										$errors = sqlsrv_errors();
										echo "Erreur : ".$errors[0]['message'] . " <br/> ";
										return;
									}
								
			
			//echo $sql;
				$nRes = sqlsrv_num_rows($stmt);	
				if($nRes!=0)
					  { 	
						
						$i=0;		
					 while($row=sqlsrv_fetch_array($stmt)){	
					$groups[$i]['IdType'] =$row['idType'];
					$groups[$i]['Designation'] =$row['Designation'];
						$groups[$i]['UrlImg'] =$row['UrlImg'];
						$i=$i+1;
					 }
					
					}
					
		if( (count($groups)==0)){
?>
<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
								Aucun r&eacute;sultat &agrave; afficher.
							</div>
<?php }
else { ?>				
					
<ul class="bxslider" style="margin:0;padding:0;">
<?php 	foreach($groups as $u=>$v){	?>
		  <div class="cadreActiv hvr-grow"   id="cadreType<?php  echo $v['IdType'];?>" 
		  onclick="AfficheCSP('<?php  echo $v['IdType'];?>','<?php  echo $v['Designation'];?>')">
			<div  class="childActiv"> 
			 <img src="../<?php 	echo ( $v['UrlImg']);?>" width="225" height="226"/><br>
				<div class="titleCadre"><?php 	echo mb_ucfirst( $v['Designation']);?></div>
			</div>
		  </div>
		  
	
<?php } ?>
	</ul>			
<?php }?>	
<script language="javascript" type="text/javascript">
  // initialize bxSlider

  function AfficheCSP(IdType,Dsg){

		$('#IdTypeClt').val(IdType);
		$('#TypeClt').val(Dsg);
		$('#BoxMap').dialog('close');
		//$('#BoxMap').load("clients.php?GetCsb&IdType="+IdType);
	
	}

$(document).ready(function(){	

if(	$('#IdTypeClt').val() !=""){
			var idActv=$('#IdTypeClt').val() ;
			$('#cadreType'+idActv).addClass("Active");
		}
	
});
	
</script>
	<?php
	

exit;
}
if (isset($_GET['GetAct'])){ 

unset($_SESSION['TabTypeClt']);
	if(!isset($_SESSION['TabTypeClt'])){
				
		$sql="select  * from activites";
			 $params = array();	
	//parcourir($params);
	//echo "<br>".$sql;
	//parcourir($params);
			$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
			if( $stmt === false ) {
										$errors = sqlsrv_errors();
										echo "Erreur : ".$errors[0]['message'] . " <br/> ";
										return;
									}
								
			
			//echo $sql;
				$nRes = sqlsrv_num_rows($stmt);	
				if($nRes!=0)
					  { 	
						
						$i=0;		
					 while($row=sqlsrv_fetch_array($stmt)){	
					$groups[$i]['IdActivite'] =$row['IdActivite'];
					$groups[$i]['DsgActivite'] =$row['DsgActivite'];
					$groups[$i]['ImgActivite'] =$row['ImgActivite'];
						$i=$i+1;
					 }
					
					}
					
						$_SESSION['TabTypeClt']=$groups;

			 }// fin bdd plein
// fin if isset session
//	parcourir($_SESSION['TabTypeClt']);return;
	
?><!--DIV style="height:94px" class="headVente">
<a  onclick="$('#BoxMap').dialog('close');" class="close2" style="float:right"></a>&nbsp;&nbsp;
<span Class="TitleHead" onclick="getActivite()" >Activité </span>> 
<span Class="TitleHead"  onclick="getType()" >Type</span> >
<span Class="TitleHead"  onclick="getCSB_Clt()" >CSP</span>
</div-->

<style>
.bx-viewport {
height: auto !important;
}
</style>
<div class="HeadGlo">
	<DIV  class="headContent">
		<span Class="TitleHead" onclick="getActivite()" >Activité </span>> 
<span Class="TitleHead"  onclick="getType()" >Type</span> >
<span Class="TitleHead"  onclick="getCSB_Clt()" >CSP</span>
	</div>
	<DIV  class="closebtn" onclick="$('#BoxMap').dialog('close');" >
		
	</div>
</div>
	

	
<?php
if((!isset($_SESSION['TabTypeClt']) )  || (count($_SESSION['TabTypeClt'])==0)){
?>
<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
								Aucun r&eacute;sultat &agrave; afficher.
							</div>
<?php }
else { ?>
<DIV STYLE="height: 768px;WIDTH:1158px;text-align:center;   padding: 0 0 0 40px;">
<ul class="bxslider" style=" text-align:center;">

<?php 


$k=0;
	$i=1;
	foreach($_SESSION['TabTypeClt'] as $u=>$v){	
		//echo "--------<li>".$k."</li>";
		// recherche pour ne pas dubliquer la couleur du cadre
		
		if( $i==1) echo " <li><div style='text-align:left'>" ;	
	
	?>
		  <div class="cadreActiv hvr-grow" id="cadreActiv<?php  echo $v['IdActivite'];?>" 
		  onclick="AfficheTypeClt('<?php  echo $v['IdActivite'];?>','<?php  echo $v['DsgActivite'];?>')">
			<div  class="childActiv"> 
			 <img src="../<?php echo $v['ImgActivite'];?>"  width="225" height="226"/>
			<div class="titleCadre"><?php 	echo mb_ucfirst($v['DsgActivite']);?></div>
			</div>
		  </div>
		  
		<?php
		//condition pour afficher 4 familles par ligne
		if($i==4) {?> <div class="clear"></div><?php }
		//condition pour afficher 8 familles par page
		if ($i == 9) {  echo " </div></li>" ; $i=1;}
		else {				$i+=1;}

	}
	


?>
	</ul>	
</div>	
<?php } ?>
<script language="javascript" type="text/javascript">
  // initialize bxSlider

  function AfficheTypeClt(idActv,Dsg){
	//	$('#BoxMap').load("clients.php?GetTypeClt2");
		$('#IdActivite').val(idActv);
		$('#Activite').val(Dsg);
		$('#BoxMap').dialog("close");
		
		
	}
$(document).ready(function(){	

if(	$('#IdActivite').val() !=""){
			var idActv=$('#IdActivite').val() ;
			$('#cadreActiv'+idActv).addClass("Active");
		}
});
	var slider = $('.bxslider').bxSlider({
			infiniteLoop: false,
			slideMargin: 50,
			hideControlOnEnd: true,
			touchEnabled: true,
			pager: false,
			pause: 3000,
			speed: 1000,
			controls:true
	});

  // touchSwipe for the win!
		 $('.bxslider').swipe({
			 excludedElements:"button, input, select, textarea, .noSwipe", // rend les champs en écriture
			swipeRight: function(event, direction, distance, duration, fingerCount) {
			
				slider.goToPrevSlide();
							},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {	
						
				slider.goToNextSlide();	
				
					
			},
			threshold: 1200
		});
	
</script>
	<?php
	

exit;
}
if (isset($_GET['getLocation'])){ 

?>


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
var adress;
 var arrDetailAdr;
geocoder = new google.maps.Geocoder();
geocoder.geocode( {'latLng': latLng},
	  function(results, status) {
		if(status == google.maps.GeocoderStatus.OK) {		
			var Secteur="";		
		  if(results[1]) {
			  //get secteur	
			 
			 var arrDetailAdr = results[1].address_components;
			var adressComplet= results[0].formatted_address;
			for (ac = 0; ac < arrDetailAdr.length; ac++) {			
						if (arrDetailAdr[ac].types[0] == "locality"){ // cyty
									// res=adress.concat("test");
									var ville=arrDetailAdr[ac].long_name;
						}		
					if (arrDetailAdr[ac].types[0] == "neighborhood"){
								var Secteur=arrDetailAdr[ac].long_name;
					}					
			}
				var s=String(latLng);
				s=s.substring(1, s.length-1);
				var res = s.split(",");
				var lat=res[0];
				var longu= res[1];
			$('#boxClient').load("clients.php?add&Ville="+ville+"&Adresse="+encodeURIComponent(adressComplet)+"&Secteur="+encodeURIComponent(Secteur)+"&long="+encodeURIComponent(longu)+"&lat="+encodeURIComponent(lat)).dialog('open');
			}
		  else {
		
		  }
		}
		
		else {
		
		}
	  });
	 
	
	}

$('#boxClient').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').dialog('open');
	 	getLocation();
</script>

<?php
exit;
}
if (isset($_GET['getVille']) ){
	echo "mm";
	exit;
}
$tableInser="clients";
if (isset($_GET['rech']) or isset($_GET['aff'])){
	//echo toDateSql($_POST['DateF']);return;
		$where="";
		if(isset($_POST['DateD']) && isset($_POST['DateF'])  )
		{
			if($_POST['DateD'] == $_POST['DateF'])
			{ 
			 	// $where.= " where cast(date_create AS date) = '".($_POST['DateD'])."' ";
				 $where.= " where convert(date,date_create) = convert(date, '".toDateSql($_POST['DateD'])."')";
			}
			else
			{
				 $where.= " where cast(date_create AS date)  between  '".toDateSql($_POST['DateD'])."' and '".toDateSql($_POST['DateF'])."' ";
			}
		}
		else
		{
		$where=" where cast(date_create AS date)='".toDateSql(date('d/m/Y'))."'";
		}
		
		$where.= " and idVendeur=".$_SESSION['IdVendeur'];

$sqlA = " SELECT intitule,adresse,Tel,t.Designation Dsg,a.DsgActivite DsgActivite,c.CSP
FROM clients c
left join typeclients t on c.idTypeClient=t.idType
left join activites a on a.IdActivite=c.IdActivite
".$where;

    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	

	
	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	
	//echo $sqlA  ;echo " num : ".$ntRes; return;
	//
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "IdClient";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "dESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = " ORDER BY $cTri $oTri ";//LIMIT $min,$max ";
	$sql = $sqlA.$sqlC;
	//echo $sql;
//echo $sql."<br>";
/*execSQL($sql);*/
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
	
	$nRes = sqlsrv_num_rows($resAff);
	$nPages = ceil($ntRes / $npp);
	$selPages = '<select name="pact" onChange="filtrer();">';
	for($i=1;$i<=$nPages;$i++){
		if($i==$pact) $s='selected="selected"';
		else $s='';
		$selPages.= '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
	}
	$selPages.= '</select>';
	
	/*	$resAff = mysql_query($reqAff)or die(mysql_error());*/
		if($nRes==0)
		{ ?>
					<div class="resAff"  style="text-align:center;min-height:200px;font-size:26px;">
						<br><br>
						Aucun r&eacute;sultat &agrave; afficher.
					</div>
					<?php
		}
else
{
	?>
<script language="javascript" type="text/javascript">
$('#cont_pages').html('<?php echo $selPages; ?>');
</script>
		<form id="formSelec" method="post">
	<DIV class="entete">
						<div class="divArticle" Style="width:240px;" align="center">Intitulé </div>
						<div class="divPV"  Style="width:440px;" align="center">Adresse</div>
						<!--div class="divArticle" Style="width:110px;" align="center">Téléphone </div-->
						<div class="divArticle" Style="width:240px;" align="center">Activité  </div>
							<div class="divArticle" Style="width:240px;" align="center">Type  </div>
							<div class="divArticle" Style="width:40px;" align="center">CSP  </div>
		</div>

<!--<div id="cList">-->
	<?php
		
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
							?>
							
			<div class="ligne " >
									<div class="  divArticle "  
									style="width:240px" align="center"><?php  echo ucfirst($row['intitule']);?>					
									</div>
									<div class="divPV" style="width:440px;text-align:left;" > <?php 
									echo ucfirst(stripslashes($row['adresse']));?></div> 
									<!--div class="  divArticle "  
									style="width:110px;text-align:right;"><?php  echo ($row['Tel']);?>					
									</div-->
									<div class="  divArticle "  
									style="width:240px;"><?php  echo ucfirst(stripslashes($row['DsgActivite']));?>					
									</div>
									<div class="  divArticle "  
									style="width:240px;"><?php  echo ucfirst($row['Dsg']);?>					
									</div>
									
										<div class="  divArticle "  
									style="width:40px;"><?php  echo ucfirst($row['CSP']);?>					
									</div>
			</div>
		<?php }
	
	?>	

    </form>
    <?php
}
?>
<script language="javascript" type="text/javascript">

	
		function actionSelect(){
				var idSelect = '0';
				var n = 0;
				$(".checkLigne:checked").each(function(){
						n++;
						idSelect +=","+$(this).attr("name");
						//alert($(this).attr("name"));
				});
				if(n>0){
				
					jConfirm('Confirmer la suppression ?', null, function(r) {
						if(r)	{
							$('input#CLETABLE').attr("value",idSelect);
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'ventepararticle.php?delPlusieursArticle',clearForm:false});		
						}
					});
				}			
		}	
	</script>
<?php
exit;
}
if(isset($_GET['goAdd'])){
	//parcourir($_POST);return;
	$errors="";

		$error="";
		$target_path="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	  
	  	  	if(isset($_FILES['file']))
			{
			$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
			$file_extension = end($ext); // Store extensions in the variable.
			$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
			$target_path = "img_magasins/" . $nameFile;     // Set the target path with a new name of image.
			
				if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
					{
					?>
								<script type="text/javascript"> 
									alert('Echec de déplacement de l\'image');
								</script>
					<?php
					$error='Echec de déplacement de l\'image';
					
					}
			}
	 $date_create = date("d/m/Y");
			
		$reqInser1 = "INSERT INTO ".$tableInser." ([nom] ,[prenom] ,[intitule] ,[adresse] ,[departement],[ville],patente,[if],
								formeJ,rc,
						longitude,latitude,idVendeur,idDepot,idTypeClient,CodeClient,ImgMagasin,date_create,tel,Mail,Superficie,
						IdActivite,CSP
							) values 	(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$params1= array(
		addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Prenom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Intitule']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Adresse']), 'UTF-8')),
		$_POST['Secteur'],
		$_POST['Ville'],
			addslashes(mb_strtolower(securite_bdd($_POST['Patente']), 'UTF-8')),
			addslashes(mb_strtolower(securite_bdd($_POST['IF']), 'UTF-8')),
				addslashes(mb_strtolower(securite_bdd($_POST['FJ']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['RC']), 'UTF-8')),
	
		
	
		addslashes(mb_strtolower(securite_bdd($_POST['Lng']), 'UTF-8')),
		securite_bdd($_POST['Lat']),
		$_SESSION['IdVendeur'],
		$_SESSION['IdDepot'],
		$_POST['IdTypeClt'],
		$_POST['Code'],
		$target_path,
		$date_create,
		addslashes(mb_strtolower(securite_bdd($_POST['Tel']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Mail']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Superficie']), 'UTF-8')),
		$_POST['IdActivite'],
		$_POST['IdCsb']) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					jAlert('L\'ajout a été effectué.',"Message");
					rechercher()
					$("#boxClient").dialog('close');
					//document.location.href="index.php";
				
				</script>
		<?php
		} else {
			 sqlsrv_rollback( $conn );
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

$IdSecteur="11";
	$Options = '<select multiple="multiple" name="Secteur" id="Secteur" class="Select Secteur"  tabindex="3" style="width:446px" >';
	$sql = "SELECT d.iddepartment,d.codeDepartement,d.Designation FROM departements d where idVille=?";
		
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['IdVille']), array( "Scrollable" => 'static' ) );         
			/*   if( $reponse === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}*/
			
		$nRes = sqlsrv_num_rows($reponse);
		
		if($nRes != 0)
		 while ($donnees =  sqlsrv_fetch_array($reponse))
            {
				if (isset ($_GET['Secteur'])){
				if(strtolower ($_GET['Secteur'])==strtolower ($donnees['Designation'])) $IdSecteur=$donnees['iddepartment'];
				}
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
$('#Secteur').multipleSelect('setSelects',[<?php echo $IdSecteur;?>]);
	</script>
	<?php
			echo $Options;
exit;
}
if (isset($_GET['add'])){ 

	if (isset($SESSION['TabTypeClt'])) unset($SESSION['TabTypeClt']);
$IdVille=1;
$tabVilles=array();
$CodeClt= "CL".Increment_Chaine_F("CodeClient","clients","IdClient",$conn,"",array());	
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
			<td width="140"  align="right" ><div class="etiqForm" id="" > <strong>Code</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Code"  id="Code" readonly  size="30" value="<?php echo $CodeClt;?>" tabindex="1" /> 
            </td> 
			
			<td  align="right"><div class="etiqForm" id="" > <strong>Ville</strong> : </div>
            </td>
            <td>
            	<select id="Ville" name="Ville" multiple="multiple"  Class="Select Ville" style="width:446px">
					
								 <?php $sql = "select idville, Designation from villes ";
							   $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) ); 
							$i=0;
									   while ($donnees =  sqlsrv_fetch_array($reponse))
									   {
										   if($_GET['Ville']==$donnees['Designation']) $IdVille=$donnees['idville'];
										   $TabVilles[$i]["IdVille"]=$donnees['idville'];
										    $TabVilles[$i]["DsgVille"]=$donnees['Designation'];
												$i++;
										   											
									   ?>
									   <option value="<?php echo $donnees['idville'];?>"><?php echo $donnees['Designation']?></option>
								 <?php
								  }
								  
								 ?>
								 
			</select>

            </td> 
			
			</tr>
			<tr>
			<td  align="right"><div class="etiqForm" id="" > <strong>Intitulé</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Intitule"  id="Intitule" size="30" tabindex="1" /> 
            </td> 
		<td width="120"  align="right"><div class="etiqForm" id="" ><strong>Secteur </strong> : </div>
            </td>
            <td>
            <div id="Secteurs" style="width:446px;">			
					<select multiple="multiple" id="Secteur" name="Secteur" Class="Select Secteur" style="width:446px">
					</select>
		</div>
            </td> 
					
          </tr>
		  <tr>
		<td  align="right">
			 <div class="etiqForm" id="" >  <strong>Localisation</strong> : </div>
            </td>
            <td colspan="2">
			<textarea rows="2" cols="25" name="Adresse"  id="Adresse"><?php echo ($_GET['Adresse']);?></textarea>
				<input class="btnLocalise" type="button" name="Localisation"  value="" id="Localisation" tabindex="1" /> 
				<input class="FormAdd1" type="hidden" name="Lat"  id="Lat" size="30" tabindex="1" value="<?php echo ($_GET['lat']);?>"/> 
					<input class="FormAdd1" type="hidden" name="Lng"  id="Lng" size="30" tabindex="1" value="<?php echo ($_GET['long']);?>" /> 
            </td>
				<td><input type="file"  name="file" id="file" ></td>
         
		</tr>
		   <tr>
           
			<td VALIGN="top">    <input type="button" value="Activité client" onclick="getActivite('open')" class="btnCmdArt">
            </td>
            <td  VALIGN="middle">
			<input type="text" readonly id="Activite" name="Activite"  class="Inputspan">
			<DIV ></DIV>
			
			<span></span>
			
        			<input type="hidden" id="IdActivite" name="IdActivite" >
						<input type="hidden" id="IdCsb" name="IdCsb" >
						<input type="hidden" id="IdTypeClt" name="IdTypeClt">
            </td> 	
		   <td>    <input type="button" value="CSP client" onclick="getCSB_Clt('open')"
									class="btnCmdArt">
            </td>
			<td cols="2">
			<input type="text" readonly id="Csb"  name="Csb"  class="Inputspan">
	
			</td>
          </tr> 	
		
		   <tr>
		   <td>    <input type="button" value="Type client" onclick="getType('open')"
									class="btnCmdArt">
            </td>
			<td cols="2"  VALIGN="middle">
				<input type="text" readonly id="TypeClt" name="TypeClt"  class="Inputspan">
			</td>
		   </tr>
		   
			 <tr>
		
		   </tr> 
	
 	  </table>

  </div>
  <div id="DtlClt">
   
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
        <tr>
        	<td align="right">
			 <div class="etiqForm" id="" ><strong>Nom  </strong> : </div>
            </td>
            <td  >
            <input class="FormAdd1" type="text" name="Nom"  id="Nom" size="20" tabindex="1"  />
            </td>
		<td  align="right"><div class="etiqForm" id="" > <strong>Prénom </strong> : </div>
            </td>
            <td >
         <input class="FormAdd1" type="text" name="Prenom"  id="Prenom" size="20" tabindex="1"  />
            </td>
			
						
          </tr>
	<tr>
        	<td  align="right"><div class="etiqForm " id="" >  <strong>Tél</strong> : </div</td><td>   
				<input class="FormAdd1 " type="text" name="Tel"  id="Tel" size="20" tabindex="1" /> </td>
            
		   <td  align="right">
			 <div class="etiqForm" id="" >  <strong>E-mail</strong> : </div>
            </td>
           <td>
            <input class="FormAdd1 " type="text" name="Mail"  id="Mail" size="20" tabindex="1"  />
            </td>		
        </tr>
		 <tr>
		 
			<td  align="right" >
			 <div class="etiqForm" id="" > <strong>Id fiscale </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="IF"  id="IF" size="20" tabindex="1"  />
            </td>

			<td  align="right"><div class="etiqForm" id="" > <strong>Forme juridique </strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="FJ"  id="FJ" size="20" tabindex="1"  />
            </td>			
          </tr>
		  
		  		<tr>
        	
            
		   <td  align="right">
			 <div class="etiqForm" id="" >  <strong>RC</strong> : </div>
            </td>
           <td>
            <input class="FormAdd1" type="text" name="RC"  id="RC" size="20" tabindex="1"  />
            </td>
				<td  align="right" style="vertical-align:text-top " ><div class="etiqForm" id=""><strong>Patente</strong> : </div>
            </td>
            <td >
            <input type="text" name="Patente" id="Patente" size="20" >	
            </td> 

        </tr>	  
		  	
		<tr>	
	<td valign="middle" align="right" ><div class="etiqForm" id=""><strong>Superficie</strong> : </div>
            </td>
            <td >
            <input type="text" name="Superficie" id="Superficie" size="7" > <span style="font-size:40PX;">m² </span>
            </td> 
			
				</tr>
 	  </table>
  </div>
  </div>
    	<div id="progress-div"><div id="progress-bar"></div></div>
  <div class=" boiteBtn" style="margin-top:20px;margin-bottom: 20px;">
<input type="button" value="Ajouter" class="btn"  onclick="AjoutClt()"/>&nbsp;&nbsp;
<input type="button" value="Annuler" onclick="closeBoxClient()" class="btn" /></div>

</div>
</form>
</div><div class="clear"></div><br>
<div id="BoxMap" style="width:1000px; ">

</div>

<style>
.pac-container {
    z-index: 100051 !important;
}
</style>

<script language="javascript" type="text/javascript">


 $( "#tabs" ).tabs();
function AjoutClt(){
	
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	 var form="";
	var act = $('#act').attr('value');
	  var exts = ['jpg','gif','png'];
		 act = 'add';
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	
	    $("#formAdd").validate({
			
							 
                                 rules: { 
                                                Intitule: "required",
												Type:"required",
												Adresse:"required",
												Activite:"required",
												'Ville': "required",
												'Secteur': "required",
												'TypeClt': "required",
												Tel:{
													 required: false,
													 tel: true
												},

												Mail:{
														"required": false,
														"email": true
													 },
												file:{
													  required: true,
													  accept: exts
													},
													
												
                                          }   ,
								messages : {
												Mail:"Mail Invalide"  ,
												Tel: "Format de téléphone invalide XXXXXXXXXX",												
												file:{
													 accept:"Seuls les images sont acceptés." 
													 }
										    }  
		});
	var test=$("#formAdd").valid();
	
	var files = $(form+' :input[type=file]').get(0).files;
	/*verifSelect2('Ville');
	verifSelect2('Secteur');*/
	if(test==true){		
		for (i = 0; i < files.length; i++)
		{
			alert(files[i].size );
		   if (files[i].size > 2097152 ) /*2mo   2097152 en octet  */ { 
		   jAlert("la taille du fichier ne doit pas dépasser 2MO","Message");
		  return false;}
		}
		
		
	}
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
												$('#progress-div').show();
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														 beforeSubmit: function() {
															$("#progress-bar").width('0%');
														},
														uploadProgress: function (event, position, total, percentComplete){	
															$("#progress-bar").width(percentComplete + '%');
															$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
														},
														success:function (){
															
														},
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
$(document).ready(function() {	
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
// selectionner la ville du vendeur recuperer par geolocalisation
  var Villes = <?php echo json_encode($TabVilles ); ?>;
        for (var i = 0; i < Villes.length; i++) {
	
			if(Villes[i]["DsgVille"]=="<?php echo $_GET['Ville'];?>"){		
					var idVille=Villes[i]["IdVille"];
			//alert(idVille);	
				$('#Ville').multipleSelect("setSelects", [idVille]);
				$('#Secteurs').load("clients.php?chargerSecteur&IdVille="+idVille+"&Secteur="+encodeURIComponent("<?php echo $_GET['Secteur']; ?>"));
			}
        }

		$('#BoxMap').dialog({
					autoOpen		:	false,
					width			:	1200,
					height			:	720,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'',
						closeOnEscape	:	false,
	
			});

	
});



//$('#Secteurs').load("clients.php?chargerSecteur&IdVille=<?php  echo $IdVille ;?>&Secteur="+encodeURIComponent("<?php echo $_GET['Secteur']; ?>"));
$('#Type').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Type ',single:true,maxHeight: 300});
//$('#Secteur').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Secteur ',single:true,maxHeight: 300});


$('#Localisation').click(function(){
		$('#BoxMap').html('<center><br/><br/>Merci de patienter pendant le chargement... <br/><img src="../images/loading2.gif" /></center>').load('clients.php?getMap').dialog('open');
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
margin: 10px 5px; 
}
#ui-datepicker-div {
	font-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif;
	font-size: 2.5em;
}
.ui-widget-header {
    color: #333;
}
</style>

<div style=" display:flex;align-items:center; padding:2px 0;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
						<div >&nbsp;> <span  Class="TitleHead" onclick="">Gestion des clients</span></div> 
</div>
<div style="clear:both;"></div>
	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="100%" border="0" cellpadding="5" cellspacing="10" align="center" >
				<tr>
				<td  align="right" valign="middle">
				<strong>Date :</strong>
				</td>
				<td>
				De
<input class="formTop" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	à
<input name="DATED" type="hidden" value=""/>	
<input class="formTop" g="date" id="DateF" tabindex="2" name="DateF" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	
<input name="DATED" type="hidden" value=""/>	
				</td>
				 <td >	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="Rechercher" class="bouton32" action="rech"
			  title="Rechercher " />
			  					<input name="button3" type="button" title="Ajouter " class="bouton32"
								onClick="ajouter();" value="Ajouter" action="ajout" />
					
					  </td>
				
				</tr>
					  
			 </table>
			 
		 </div>
		
	</form>
<div id="formRes" ></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<div id="box" ></div>
<div id="boxClient" ></div>
<div id="boxActiv" >
</div>

<?php
include("footer.php");
?>
 <script src="js/jquery.geolocation.js"></script>
<script language="javascript" type="text/javascript">
calendrier("DateD");
calendrier("DateF");
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
			$('#boxClient').dialog({
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
		// $('#formRes').load('clients.php?add');
		$('#formRes').load('clients.php?aff');
	

});
function rechercher(){

	$('#formRechF').ajaxSubmit({target:'#formRes',url:'clients.php?rech'})
		}

function Fermer(){

	$("#BoxMap").dialog('close');
}
function closeBoxClient(){

	$("#boxClient").dialog('close');
}

 function ajouter(){
		$('#act').attr('value','add');
	
		var url='clients.php?getLocation';		
		$('#boxClient').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');	
}
function getActivite(){

		$('#BoxMap').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load("clients.php?GetAct").dialog('open');
	}
function getType(open){

	if(open!='undefined ') {
	$('#BoxMap').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load("clients.php?GetTypeClt2").dialog('open');
	}else {
		$('#BoxMap').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load("clients.php?GetTypeClt2");
	}
}
function getCSB_Clt(open){
	if(open!='undefined ') {
		$('#BoxMap').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load("clients.php?GetCsb").dialog('open');
	}else 
		{
		$('#BoxMap').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load("clients.php?GetCsb");
	}

}


</script>
<script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&libraries=places"> </script>
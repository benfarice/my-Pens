<?php
include("../php.fonctions.php");
require_once('../connexion.php');
if(!isset($_SESSION))
{
session_start();
} 
$IdDepot=$_SESSION['IdDepot'];
include("lang.php");
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
    <li ><a href="#DtlClt">Détail client</a></li>  
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
<DIV	class="  col-md-12 col-sm-12 row ">
<ul class="bxslider" style="margin:0;padding:0;text-align:center">
	
		  <div class="col-md-3 col-sm-6  hvr-grow"  id="CadreCsbA"  onclick="GetCsb('A')">
			<div  class="childActiv"> 
			 <img src="../csp/1star.jpg"  width="222" height="227"/><br>			
			</div>
		  </div>
		    <div class="col-md-3   col-sm-6  hvr-grow" id="CadreCsbB"   onclick="GetCsb('B')">
			<div  class="childActiv"> 
			 <img src="../csp/2stars.jpg"  width="222" height="227"/><br>
				
			</div>
		  </div>
		  
	 <div class="col-md-3   col-sm-6  hvr-grow" id="CadreCsbC"   onclick="GetCsb('C')">
			<div  class="childActiv"> 
			 <img src="../csp/3stars.jpg"  width="222" height="227"/><br>
		
			</div>
		  </div>
 <div class="col-md-3  col-sm-6  hvr-grow" id="CadreCsbD"   onclick="GetCsb('D')">
			<div  class="childActiv"> 
			 <img src="../csp/4stars.jpg" width="222" height="227"/><br>
	
			</div>
		  </div>
	</ul>			
</div>
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  function GetCsb(Csb){	
 		$('#IdCsb').val(Csb);
		$('#Csb').val(Csb);
		$('#BoxData').modal('hide');
	
	}
$(document).ready(function(){	
$('#BoxData').modal('show');
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
<DIV	class="  ">				
<ul class="bxslider" style="margin:auto;padding:auto;">
<?php 	foreach($groups as $u=>$v){	?>
		  <div   class="  col-md-6      col-sm-6 hvr-grow"   id="cadreType<?php  echo $v['IdType'];?>" 
		  onclick="AfficheCSP('<?php  echo $v['IdType'];?>','<?php  echo $v['Designation'];?>')">
			<div  class="titleCadre"> 
			 <img src="../<?php 	echo ( $v['UrlImg']);?>" width="225" height="226"/><br>
				<div class="titleCadre"><?php 	echo mb_ucfirst( $v['Designation']);?></div>
			</div>
		  </div>	  
<?php } ?>
	</ul>
	</div>
<?php }?>	
<script language="javascript" type="text/javascript">
  // initialize bxSlider
function AfficheCSP(IdType,Dsg){

		$('#IdTypeClt').val(IdType);
		$('#TypeClt').val(Dsg);
		$('#BoxData').modal('hide');
		//$('#BoxMap').load("clients.php?GetCsb&IdType="+IdType);	
	}
$(document).ready(function(){	
$('#BoxData').modal('show');
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
<?php
if((!isset($_SESSION['TabTypeClt']) )  || (count($_SESSION['TabTypeClt'])==0)){
?>
<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
								Aucun r&eacute;sultat &agrave; afficher.
							</div>
<?php }
else { ?>
<DIV>
<ul class="bxslider " style="margin:auto;padding:auto;" >
<?php 
$k=0;
	$i=1;
	foreach($_SESSION['TabTypeClt'] as $u=>$v){	
		//echo "--------<li>".$k."</li>";
		// recherche pour ne pas dubliquer la couleur du cadre		
		if( $i==1) echo " <li style='list-style:none;'><div style=''>" ;	
	?>
		  <div class="  col-md-4 col-sm-4 hvr-grow" id="cadreActiv<?php  echo $v['IdActivite'];?>" 
		  onclick="AfficheTypeClt('<?php  echo $v['IdActivite'];?>','<?php  echo $v['DsgActivite'];?>')">
			<div  class="titleCadre"> 
			 <img src="../<?php echo $v['ImgActivite'];?>"  width="205" height="206"/>
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
?>	</ul>	
</div>	
<?php } ?>
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  function AfficheTypeClt(idActv,Dsg){
	 
	//	$('#BoxMap').load("clients.php?GetTypeClt2");
		$('#IdActivite').val(idActv);
		$('#Activite').val(Dsg);
		//$('#BoxData').modal("hide");
		CloseBoxData('BoxData');
	}
$(document).ready(function(){	
	if(	$('#IdActivite').val() !=""){
			var idActv=$('#IdActivite').val() ;
			$('#cadreActiv'+idActv).addClass("Active");
		}
		$('#BoxData').modal('show');
});
	/*var slider = $('.bxslider').bxSlider({
			infiniteLoop: false,
			slideMargin: 50,
			hideControlOnEnd: true,
			touchEnabled: true,
			pager: false,
			pause: 3000,
			speed: 1000,
			controls:true,
	
	});*/

  // touchSwipe for the win!
		/* $('.bxslider').swipe({
			 excludedElements:"button, input, select, textarea, .noSwipe", // rend les champs en écriture
			swipeRight: function(event, direction, distance, duration, fingerCount) {
			
				slider.goToPrevSlide();
							},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {	
						
				slider.goToNextSlide();	
				
					
			},
			threshold: 1200
		});*/
	
</script>
<?php	
exit;
}
if (isset($_GET['getLocation'])){
?>
<script language="javascript" type="text/javascript">
 var geocoder;
 function getLocation() {
			var url="clients.php?add";
			alert(url);
				 $.get(url, null, function(data) {
					$("#Box").find('.modal-body').html(data).modal('show');
					
			});
 }
/*
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
	alert('etat avant');
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
					if (arrDetailAdr[ac].types[0] == "locality"){ // city
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
				alert('etat après');
			//	alert("latitude: "+lat+" longtitude : " + longu+" ville : " + ville+" Secteur : " + Secteur+" adressComplet : " + adressComplet);
				var url="clients.php?add&Ville="+ville+"&Adresse="+encodeURIComponent(adressComplet)+"&Secteur="+encodeURIComponent(Secteur)+"&long="+encodeURIComponent(longu)+"&lat="+encodeURIComponent(lat);
			//	alert(url);
				// $.get(url, null, function(data) {
					//$("#Box").find('.modal-body').html(data).modal('show');
			//			$("#Box").find('.modal-body').html(data);
			//});
			//$('#boxClient').load("clients.php?add&Ville="+ville+"&Adresse="+encodeURIComponent(adressComplet)+"&Secteur="+encodeURIComponent(Secteur)+"&long="+encodeURIComponent(longu)+"&lat="+encodeURIComponent(lat)).dialog('open');
			}
		}
	});
	}
	*/
	
	//$("#Box").modal('show');
	/* $.get(url, null, function(data) {
			  $modal.find('.modal-body').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').modal('show');
			});*/
	//$('#boxClient').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').dialog('open');
	 	getLocation();
</script>
<?php
exit;
}
if (isset($_GET['getVille']) ){	
	exit;
}
$tableInser="clients";
if (isset($_GET['rech']) or isset($_GET['aff'])){
	//echo toDateSql($_POST['DateF']);return;
		$where="";
		if(isset($_POST['DateD']) && isset($_POST['DateF']) && ($_POST['DateD']!="") && ($_POST['DateF']!="")  )
		{
			if($_POST['DateD'] == $_POST['DateF'])
			{ 
			 	// $where.= " where cast(date_create AS date) = '".($_POST['DateD'])."' ";
				 $where.= " where convert(date,date_create) = convert(date, '".($_POST['DateD'])."',105)";
			}
			else
			{
				 $where.= " where date_create between  convert(date, '".($_POST['DateD'])."',105 ) and convert(date,  '".($_POST['DateF'])."',105) ";
			}
		}
		else
		{
		//	$where=" where cast(date_create AS date)='".(date('m/d/Y'))."'";
		//$where=" where cast(date_create AS date)='".toDateSql(date('d/m/Y'))."'";
		}
	//	echo "vdr".$_SESSION['IdVendeur']."<br>";
		if($where=="") $where.= " where  idVendeur=".$_SESSION['IdVendeur']."";
		else $where.= " and idVendeur=".$_SESSION['IdVendeur']."";

	$sqlA = " SELECT intitule,adresse,Tel,t.Designation Dsg,a.DsgActivite DsgActivite,c.CSP
	FROM clients c
	left join typeclients t on c.idTypeClient=t.idType
	inner join activites a on a.IdActivite=c.IdActivite
	".$where;

    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	
$sqlD="SELECT intitule,adresse,Tel,t.Designation Dsg,a.DsgActivite DsgActivite,c.CSP FROM clients c left join typeclients t 
on c.idTypeClient=t.idType left join activites a on a.IdActivite=c.IdActivite where convert(date,date_create) 
between convert(date, '08/02/2017' ) and convert(date, '08/02/2017') and idVendeur=5";
//ECHO "<hr>".$sqlA."<br>";
//ECHO "<hr>".$sqlD."<br>";

	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	if( $stmt === false ) {
					$errors = sqlsrv_errors();
					$error="<br>Erreur :  ".$errors[0]['message'] . " <br/> ";
					ECHO $error;
					return;
			}
				
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
					<div class="resAff"  style="text-align:center;min-height:200px;font-size:24px;">
						<br><br>
						<?php echo $trad['msg']['AucunResultat'];?>
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
		
		<div class="responsive-table-line" style="margin:0px auto;max-width:1275px;">
			<table class="table table-bordered table-condensed table-body-center" >
					<thead class="entete">
						<tr >
						<th class="tdTitle"><?php echo $trad['label']['Client'];?></th>
						<th><?php echo $trad['map']['adresse'];?></th>
						<th><?php echo $trad['label']['Activite'];?></th>
						<th><?php echo $trad['label']['type'];?></th>
						<th><?php echo $trad['label']['classe'];?></th>
						</tr>
					</thead><tbody>
				<?php while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
					?>
					<tr>
						<td data-title="Client" class="tdTitle"><?php  echo ucfirst($row['intitule']);?>	</td>
						<td data-title="Adresse"><?php echo ucfirst(stripslashes($row['adresse']));?></td>
						<td data-title="Activité"><?php  echo ucfirst(stripslashes($row['DsgActivite']));?>	</td>
						<td data-title="Type"><?php  echo ucfirst($row['Dsg']);?></td>
						<td data-title="CSP"><?php  echo ucfirst($row['CSP']);?></td>
					</tr>
				<?php } ?>
					 </tbody>
			</table>
</div>

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
									//alert('Echec de déplacement de l\'image');
									jAlert('<?php echo $trad['msg']['ErreurDeplacementImg'];?>',"<?php echo $trad['titre']['Alert'];?>");
								</script>
					<?php
					$error='Echec de déplacement de l\'image';
					
					}
					else {
						$redimOK = fctredimimage(800,500,'img_magasins/',$nameFile,'img_magasins/',$nameFile);
						if ($redimOK == 0) {$error.="Erreur de redimensionnement de l'image" ;}
					}
			}
			
			 $whitelist = array( 'localhost','192.168.1.119'  );
			 echo  $_SERVER['REMOTE_ADDR'];
			if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist) )
				$date_create = date("d/m/Y");
			else $date_create = date("m/d/Y");
		
$date_create = date("d/m/Y");
//$date_create = date("m/d/Y");
			
		$reqInser1 = "INSERT INTO ".$tableInser." ([nom] ,[prenom] ,[intitule] ,[adresse] ,[departement],[ville],patente,[if],
								formeJ,rc,
						longitude,latitude,idVendeur,idDepot,idTypeClient,CodeClient,
						ImgMagasin,date_create,tel,Mail,Superficie,
						IdActivite,CSP
							) values 	(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
							//$_POST['Secteur'] secteur figer,

		$params1= array(
		addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Prenom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Intitule']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Adresse']), 'UTF-8')),
		6 ,
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
//parcourir($params1);
		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					ajaxindicatorstop();
					
					// jAlert('<?php echo $trad['msg']['messageAjoutSucces'];?>',"<?php echo $trad['titre']['Alert'];?>");
						alert("<?php echo  $trad['msg']['messageAjoutSucces']; ?>");
					rechercher();
					CloseBox();
			
					
				//	$("#boxClient").dialog('close');
					//document.location.href="index.php";
				
				</script>
		<?php
		} else {
			//parcourir($params1);
			?>
				<script type="text/javascript"> 
				
					alert("<?php echo $error; ?>");
					ajaxindicatorstop();
					</script>
			<?php
			 sqlsrv_rollback( $conn );
			// echo "<font style='color:red'>".$error."</font>";
		}
		/********************************************************/	

exit;
}
if(isset($_GET['getMap'])){
	?>
<style>
#map {
  margin: 20px 0;
  padding: 0;
  min-height: 400px;
  float: left;
  width: 100%;
}

</style>	
<div class="col-md-12 col-sm-12">
	 <input id="pac-input" class="controlMap"  type="text" placeholder="<?php echo $trad['button']['Rechercher'] ;?> ">
	<div id="map" >
	</div>
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
	 zoom: 15,	 
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
		
		$("#BoxData").modal("hide");
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

   $('#BoxData').on('shown.bs.modal', function () {
              google.maps.event.trigger(map, 'resize');
            });
			
 $("#BoxData").modal("show");

 }  


</script>
<?php
//	echo "getMap";
	exit;
}
if(isset($_GET['chargerSecteur'])){

$IdSecteur="11";
	$Options = '<select multiple="multiple" name="Secteur" id="Secteur" class="Select Secteur"  tabindex="3"  >';
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

$('#Secteur').multipleSelect({filter: true,placeholder:'<?php echo $trad['map']['selectSecteur'] ; ?>',
selectAllText:'<?php echo $trad['label']['selectTous'] ; ?> ',
allSelected:'<?php echo $trad['label']['tousSelect'] ; ?> ',
single:true,maxHeight: 300,
				width:'100%',
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


?>
<style>
.ui-tabs .ui-tabs-panel{
	padding-bottom:0;
}
.ms-choice {
    height: 56px;
}
.ms-choice > span {
    position: absolute;
    top: 15px;
}
.form-group {
    margin-bottom: 10px;
}
	
</style>
<?php
	if (isset($SESSION['TabTypeClt'])) unset($SESSION['TabTypeClt']);
$IdVille=1;
$tabVilles=array();
$CodeClt= "CL".Increment_Chaine_F("CodeClient","clients","IdClient",$conn,"",array());	
?>
<div>
<form id="formAdd" method="post" name="formAdd"> 
<div id="resAdd"></div>	
<div id="tabs" >
  <ul>
    <li><a href="#Clt"><?php  echo $trad['label']['Client'];?></a></li>
    <li><a href="#DtlClt"><?php  echo $trad['label']['DetailClient'];?></a></li>
  
  </ul>
  <div class="Clt col-md-12 col-sm-12  row">
  <div id="Clt">
 
   <div class="form-group row">
   <div class="col-md-5 col-sm-12 form-group">

			<label class="col-md-3 control-label "><?php  echo $trad['label']['Code'];?> :</label>
			
				<div class="col-md-9">
				  <input class="form-control" type="text" name="Code"  id="Code" readonly  value="<?php echo $CodeClt;?>" 
					/> 
			</div>
   </div>
	<div class=" col-md-offset-1 col-md-5 col-sm-12 " style="display:none"  >  
		  
					<label class="col-md-3 control-label "><?php  echo $trad['label']['Ville'];?> :</label>
				<div class="col-md-9">
				    	<select id="Ville" name="Ville" multiple="multiple"  Class="Select Ville"  >
					
								 <?php $sql = "select idville, Designation from villes ";
							   $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) ); 
							$i=0;
									   while ($donnees =  sqlsrv_fetch_array($reponse))
									   {
										 
										   $TabVilles[$i]["IdVille"]=$donnees['idville'];
										    $TabVilles[$i]["DsgVille"]=$donnees['Designation'];
												$i++;
										   											
									   ?>
									   <option value="<?php echo $donnees['idville'];?>"><?php echo $donnees['Designation']?></option>
								 <?php
								  }
								  
								 ?>
								 
			</select>
			</div>
		  </div>
  </div>
 


 <div class="form-group row">
   <div class="col-md-5 col-sm-12 form-group">
			<label class="col-md-3 control-label "><?php  echo $trad['label']['Intitule'];?> :</label>
				<div class="col-md-9">
				   <input class="form-control" type="text" name="Intitule"  id="Intitule" size="30" tabindex="1" /> 
				
			</div>
   </div>
	<!---div class=" col-md-offset-1 col-md-5 col-sm-12  " style="display:none">  
		  
				<label class="col-md-3 control-label "><?php  echo $trad['label']['secteur'] ;?> </label>
				<div class="col-md-9">
				      <div id="Secteurs" >			
					<select multiple="multiple" id="Secteur" name="Secteur" Class="Select Secteur form-control"	>
					</select>
		</div>
			</div>
		  </div-->
  </div>
  
  <div class="row">
   <div class="col-md-7 col-sm-9 ">
				<label class="col-md-3 col-sm-12 control-label "><?php  echo $trad['map']['adresse'] ;?> :</label>
				<!--div class="col-md-5 col-sm-9 form-group ">
				  	<textarea rows="2" cols="23" name="Adresse"  id="Adresse" class="form-control"><?php // echo ($_GET['Adresse']);?></textarea>
				<input class="FormAdd1" type="text" name="Lat"  id="Lat" size="30" tabindex="1" value="<?php //echo ($_GET['lat']);?>"/> 
					<input class="FormAdd1" type="text" name="Lng"  id="Lng" size="30" tabindex="1" value="<?php // echo ($_GET['long']);?>" /> 
			 
				</div-->
				
			<div class="  col-md-3 col-sm-12 form-group">
				&nbsp;<input class="btn btn-primary" type="button" name="Localisation"  value="<?php  echo $trad['button']['Localiser'] ;?>" id="Localisation"
				tabindex="1" /> 
			</div>
	   
   </div>
      
		<div class=" col-md-5 col-sm-12 row form-group  " style="display:none">  
		  
				 <input type="file"  name="file" id="file" class="jfilestyle" data-input="false"  >
			
		  </div>
  </div>
 
 
   <div class="form-group row">
   <div class="col-md-5 col-sm-12  ">
			<div class="col-md-4  col-sm-4 form-group"> 
			<input type="button" value="<?php echo $trad['label']['Activite'];?>" onclick="getActivite('open')" class="btnCmdArt btn btn-primary"></div>
				<div class="col-md-8  col-sm-8 form-group">
					<input type="text" readonly id="Activite" name="Activite"  class="Inputspan form-control">
			</div>
   </div>
	<div class=" col-md-offset-1 col-md-5 col-sm-12 ">  
		  
				<div class="col-md-4 col-sm-4 control-label form-group"> 
				<input type="button" value="<?php echo $trad['label']['classe'];?>" onclick="getCSB_Clt('open')"
									class="btnCmdArt btn btn-primary">
					</div>
				<div class="col-md-8 col-sm-8 form-group">
				<input type="text" readonly id="Csb"  name="Csb"  class="Inputspan form-control">
						<input type="hidden" id="IdActivite" name="IdActivite" >
						<input type="hidden" id="IdCsb" name="IdCsb" >
						<input type="hidden" id="IdTypeClt" name="IdTypeClt">
				</div>
		  </div>
  </div>
  
  <div class="form-group row">
	   <div class="col-md-5 col-sm-12">
				<div class="col-md-4 col-sm-4 form-group"> 
				 <input type="button" value="<?php echo $trad['label']['type'];?>" onclick="getType('open')"
									class="btnCmdArt btn btn-primary">
				</div>
					<div class="col-md-8 col-sm-8">
						<input type="text" readonly id="TypeClt" name="TypeClt"  class="Inputspan form-control ">
				</div>
	   </div>
		<div class=" col-md-offset-1 col-md-5 col-sm-12 ">  
			  
			  </div>
  </div>
  </div>
  <div id="DtlClt">
		
 
   <div class="form-group row">
   <div class="col-md-5 col-sm-12 form-group">

			<label class="col-md-4 control-label "><?php  echo $trad['label']['Nom'] ;?> :</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="Nom"  id="Nom"  	/> 
			</div>
   </div>
		<div class=" col-md-offset-1 col-md-5 col-sm-12form-group ">  
		  
					<label class="col-md-4 control-label "><?php  echo $trad['label']['Prenom'] ;?> :</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="Prenom"  id="Prenom"  	/> 
			</div>
		  </div>
  </div>
 
   <div class="form-group row">
   <div class="col-md-5 col-sm-12 form-group">

			<label class="col-md-4 control-label "><?php  echo $trad['label']['Tel'] ;?> :</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="Tel"  id="Tel"  	/> 
			</div>
   </div>
		<div class=" col-md-offset-1 col-md-5 col-sm-12 ">  
		  
					<label class="col-md-4 control-label "><?php  echo $trad['label']['Mail'] ;?> :</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="Mail"  id="Mail"  	/> 
			</div>
		  </div>
  </div>

<div class="form-group row">
   <div class="col-md-5 col-sm-12 form-group">

			<label class="col-md-4 control-label "><?php  echo $trad['label']['IdFisclae'] ;?> :</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="IF"  id="IF"  	/> 
			</div>
   </div>
		<div class=" col-md-offset-1 col-md-5 col-sm-12 ">  
		  
					<label class="col-md-4 control-label "><?php  echo $trad['label']['FormJuridique'] ;?>  :</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="FJ"  id="FJ"  	/> 
			</div>
		  </div>
  </div>
  <div class="form-group row">
   <div class="col-md-5 col-sm-12 form-group">

			<label class="col-md-4 control-label "><?php  echo $trad['label']['RegistreCommerce'] ;?> :</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="RC"  id="RC"  	/> 
			</div>
   </div>
		<div class=" col-md-offset-1 col-md-5 col-sm-12 ">  
		  
					<label class="col-md-4 control-label "><?php  echo $trad['label']['Patente'] ;?> :</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="Patente"  id="Patente"  	/> 
			</div>
		  </div>
  </div>
  
  <div class="form-group row">
   <div class="col-md-5 col-sm-12">

			<label class="col-md-4 control-label "><?php  echo $trad['label']['Superficie'] ;?>  (m²):</label>
			
				<div class="col-md-8">
				  <input class="form-control" type="text" name="Superficie"  id="Superficie"  	/>
			</div>
   </div>
	
  </div>
  
  </div>
  </div>
  <div class="clear"></div>
    	<!--div id="progress-div"><div id="progress-bar"></div></div-->
 

</div>
</form>
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
												//Type:"required",
												Adresse:"required",
												Activite:"required",
												//'Ville': "required",
												//'Secteur': "required",
												//'TypeClt': "required",
												Tel:{
													 required: false,
													 tel: true
												},

												Mail:{
														"required": false,
														"email": true
													 }/*,
												file:{
													  required: true,
													  accept: exts
													},*/
													
												
                                          }   ,
								messages : {
												Mail:"<?php echo $trad['msg']['EmailInvalide'];?>"  ,
												Tel: "<?php echo $trad['msg']['TelInvalide'];?>",												
												file:{
													 accept:"<?php echo $trad['msg']['ImageSeul'];?>" 
													 }
										    }  
		});
	var test=$("#formAdd").valid();
//verifSelect2('Ville');
//verifSelect2('Secteur');
	/*var files = $(form+' :input[type=file]').get(0).files;

		if(files.length==0){
			jAlert("<?php echo $trad['msg']['FichierObligatoire'];?>","<?php echo $trad['titre']['Alert'];?>");
		  return false;			
		}
	
	if(test==true){		
		for (i = 0; i < files.length; i++)
		{
			
		   if (files[i].size > 2097152 )  { 
		 //  jAlert("la taille du fichier ne doit pas dépasser 2MO","Message");
		 jAlert("<?php echo $trad['msg']['DepasseTailleFicher'];?>","<?php echo $trad['titre']['Alert'];?>");
		  return false;}
		}
		
		
	}*/
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['ConfirmerOperation'];?>', "<?php echo $trad['titre']['Alert'];?>", function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'clients.php?goMod',
														method			:	'post'
													}); 
												
											}else{
												//$('#progress-div').show();
												ajaxindicatorstart('<?php echo $trad['msg']['Patienter'];?>');
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														/* beforeSubmit: function() {
															$("#progress-bar").width('0%');
														},
														uploadProgress: function (event, position, total, percentComplete){	
															$("#progress-bar").width(percentComplete + '%');
															$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
														},
														success:function (){															
														},*/
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
$(":file").jfilestyle({input: false,buttonText: "<img src='img/folder.png' /><?php echo $trad['button']['parcourir'] ; ?>"});				
	ajaxindicatorstop();
	$("#Box").modal('show');
/*	
$('#Ville').multipleSelect({
		  filter: true,placeholder:'<?php echo $trad['map']['selectVille'] ; ?>',single:true,maxHeight: 300,
		  selectAllText:'<?php echo $trad['label']['selectTous'] ; ?> ',
		  allSelected:'<?php echo $trad['label']['tousSelect'] ; ?> ',
		 width:'100%',
		      onClick: function(view) {
				if(view.checked = 'checked')
				$('#Secteurs').load("clients.php?chargerSecteur&IdVille="+view.value);
				
				var Ville =$('#Ville').val();
				if(Ville!="") {
					$('div.Ville').removeClass('erroer');
					$('div.Ville button').css("border","1px solid #ccc").css("background","#fff");
				}
}});*/
// selectionner la ville du vendeur recuperer par geolocalisation
 /* var Villes = <?php echo json_encode($TabVilles ); ?>;
 
        for (var i = 0; i < Villes.length; i++) {
	
			if(Villes[i]["DsgVille"]=="<?php echo $_GET['Ville'];?>"){		
					var idVille=Villes[i]["IdVille"];
			//alert(idVille);	
				$('#Ville').multipleSelect("setSelects", [idVille]);
				//alert("clients.php?chargerSecteur&IdVille="+idVille+"&Secteur="+encodeURIComponent("<?php echo $_GET['Secteur']; ?>"));
				$('#Secteurs').load("clients.php?chargerSecteur&IdVille="+idVille+"&Secteur="+encodeURIComponent("<?php echo $_GET['Secteur']; ?>"));
			}
        }	*/
});
//$('#Secteurs').load("clients.php?chargerSecteur&IdVille=<?php  echo $IdVille ;?>&Secteur="+encodeURIComponent("<?php //echo $_GET['Secteur']; ?>"));
$('#Type').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Type ',single:true,maxHeight: 300});
//$('#Secteur').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Secteur ',single:true,maxHeight: 300});


$('#Localisation').click(function(){
	//	$('#BoxMap').html('<center><br/><br/>Merci de patienter pendant le chargement... <br/><img src="../images/loading2.gif" /></center>').load('clients.php?getMap').dialog('open');
	 var $modal = $('#BoxData');
		var url='clients.php?getMap';
    $.get(url, null, function(data) {
      $modal.find('.modal-body').html(data);
    })

	});
</script>

<?php
exit;
}
include("headerSite.php"); ?>
<script src="js/jquery-filestyle.min.js" type="text/javascript"></script>
<link href="css/jquery-filestyle.css"  rel="stylesheet" />

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

.ui-widget-header {
    color: #333;
}
</style>

 <div   class="row headVente " id="infosGPS"  >
	<div style="display:table-cell; vertical-align:middle;">
		<a href="index.php"><img src= "../images/home.png"  /></a>
		&nbsp; <span style="font-size:20px;top:90%" ><?php echo $trad['index']['GestionClient'] ;?></span> 
	</div>
 </div>
		
		
<div style="clear:both;"></div>

<div  class="row row-centered ">
  <div id="formRech" class="  col-sm-12  col-md-12 col-sm-12 col-centered " >	
  
 <form id="formRechF" method="post" name="formRechF" > 
		
			<div  class="col-md-1 col-sm-12  " >	
			<div class="middleLabel centerLabel"  ><label for="user_lastname"><?php echo $trad['label']['Periode'];?>&nbsp;
			<?php echo $trad['label']['de'];?></label> </div>	 
			</div>
			
			<div  class=" middleLabel col-md-3 col-sm-12 "  >	
		
					<input class="form-control" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" 
							 maxlength="10" onChange="verifier_date(this);" value="<?php //echo date('d/m/Y'); ?>"/>	
					<input name="DATED" type="hidden" value=""/>
			</div>
			<div  class="  col-md-1 col-sm-12  centerLabel" > <label for="user_lastname" Style="margin-top:10px">
			<?php echo $trad['label']['a'];?></label>	
			</div>
			<div  class=" middleLabel   col-md-3 col-sm-12 form-group " >		
					<input  g="date" id="DateF" tabindex="2" name="DateF" type="text"  class="form-control" 
					size="10" maxlength="10" onChange="verifier_date(this);" value="<?php //echo  date('d/m/Y'); ?>"/>	
					<input name="DATED" type="hidden" value=""/>	
			</div>
		
			<div  class="  col-md-4 col-sm-12  centerLabel">				
			&nbsp;<input type="button" value="<?php echo $trad['button']['rechercher'];?>" class="btn btn-primary"  id="rech" action="rech" 
			onclick="rechercher()"; />
			
			&nbsp;<input type="reset" value="<?php echo $trad['button']['Annuler'];?>" class="btn btn-primary"  id="reset" action="effacer" 
			 />
			<input type="button" value="<?php echo $trad['button']['Ajouter'];?>" class="btn btn-primary"   action="ajout" 
			 onclick="ajouter()"
			 />
		
		
		</div>
		
	</form>
	</div>
</div>

<style>
#Box { overflow-y:scroll }
</style>
<div id="formRes" ></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<div id="box" style=""></div>
<div id="boxClient" ></div>
<div id="boxActiv" >
</div>

 	 <div  style="display: none; padding-left: 0;"   data-backdrop="static" class="modal fade"
	 data-keyboard="false" id="Box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  
	 aria-hidden="false">
           <div class="modal-dialog">
             <div class="modal-content">
                  <div class="modal-header">                   
                    <h2 class="modal-title" id="myModalLabel"><?php echo $trad['index']['GestionClient'];?></h2>
                  </div>
                  <div class="modal-body">
          <img src="http://conferoapp.com/icons/preloader.gif" class="progress">

                  </div>
				  <div class="clear"></div>
				  <div class="modal-footer" style="border:none"> 
				  <input type="submit" value="<?php echo $trad['button']['Enregistrer'];?>" id="Terminer"   class="btn btn-primary" onclick="AjoutClt()" name="save" />
				<Input type="button" class="btn btn-primary" onclick="CloseBox('Box')"  value="<?php echo $trad['button']['Fermer'];?> " />
				  
				  </div>
            </div>
          </div>	    
        </div>

<div class="row   col-md-12  col-sm-12" >
 	 <div  style="display: none;"  class="modal fade  col-md-12  col-sm-12"  data-backdrop="static" 
	 data-keyboard="false" id="BoxData"
	 tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="false">
           <div class="modal-dialog">
             <div class="modal-content" >
                  <div class="modal-header HeadGlo">		
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"
							style="color:#fff;opacity:none;"
					>&times;</button>
                  </div>
                  <div class="modal-body " > 

                  </div>
				  <div class="clear"></div>
				    <div class="modal-footer"> 
				
				  </div>
				
            </div>
          </div>	    
        </div>
</div>
<?php
include("footerRes.php");
?>
<script src="js/jquery.geolocation.js"></script>
<script language="javascript" type="text/javascript">
calendrier("DateD");
calendrier("DateF");
$(document).ready(function() {
/*$('#box').dialog({
					autoOpen		:	false,
					width			:	'auto',
					height			:	'auto',
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
			});*/
			/*$('#boxClient').dialog({
					autoOpen		:	false,
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
			});	*/
		// $('#formRes').load('clients.php?add');
		$('#formRes').html('<center><br/><br/><?php echo $trad['msg']['Patienter'];?> <br/><img src="../images/loading2.gif" /></center>').load('clients.php?aff');
	

});

function rechercher(){
	
	$('#formRes').html('<center><br/><br/><?php echo $trad['msg']['Patienter'];?> <br/><img src="../images/loading2.gif" /></center>');
	$('#formRechF').ajaxSubmit({target:'#formRes',url:'clients.php?rech'});
		}

function Fermer(){

	$("#BoxMap").dialog('close');
}
function closeBoxClient(){

	$("#boxClient").dialog('close');
}

 function ajouter(){
		$('#act').attr('value','add');	
		/*var url='clients.php?getLocation';		
		$('#boxClient').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');	*/
		
	ajaxindicatorstart('<?php echo $trad['map']['messageChargementMap'];?>');
    var $modal = $('#Box');
		var url='clients.php?getLocation';
     $.get(url, null, function(data) {
      //$modal.find('.modal-body').html(data);
	   $modal.find('.modal-body').html(data);
    })
}
function getActivite(){
  var $modal = $('#BoxData');
		var url='clients.php?GetAct';
    $.get(url, null, function(data) {
      $modal.find('.modal-body').html(data);
    })
	
		//$('#BoxMap').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load("clients.php?GetAct").dialog('open');
	}
function getType(open){

var $modal = $('#BoxData');
		var url='clients.php?GetTypeClt2';
		
    $.get(url, null, function(data) {
      $modal.find('.modal-body').html(data);
    })
}
function getCSB_Clt(open){
	var $modal = $('#BoxData');
		var url='clients.php?GetCsb';
	if(open!='undefined ') {
		//$('#BoxMap').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load("clients.php?GetCsb").dialog('open');
		$.get(url, null, function(data) {
		  $modal.find('.modal-body').html(data);
		})
	}else 
		{
			$.get(url, null, function(data) {
		  $modal.find('.modal-body').html(data);
			})
//$('#BoxMap').html('<center><br/><br/>Merci de patienter... <br/><img src="../images/loading2.gif" /></center>').load("clients.php?GetCsb");
	}

}
function CloseBox(){
	$("#Box").modal('hide');
	$('body').removeClass('modal-open');
	$('.modal-backdrop').remove();	
}
function CloseBoxData(BoxData){
	$("#"+BoxData).modal('hide');
	/*$('body').removeClass('modal-open');
	$('.modal-backdrop').remove();*/	
}

</script>
<script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&libraries=places<?php echo ($_SESSION['lang'] == 'ar' ) ? '&language=ar' : '&language=en'; ?>"> </script>
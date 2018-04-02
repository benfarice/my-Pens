<?php
include("../php.fonctions.php");
require_once('../connexion.php');
include("fonctionCalcule.php");
if(!isset($_SESSION))
{
session_start();
} 
include("lang.php");
$IdDepot=$_SESSION["IdDepot"];
function cmp($a, $b){
  return strcmp($a['Distance'], $b['Distance']);
}
function extract_value(array $array, array $keys) {
   foreach($keys as $key) {
       if (!isset($array[$key])) return null;
       $array = $array[$key];
   }

   return $array;
}
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}
 if(isset($_GET['ChargerClt'])){
$tabClt=array();
if(!isset($_SESSION['tabClt'])){
$sql = "SELECT   
intitule, c.IdClient,c.nom,c.prenom,c.adresse,c.longitude,c.latitude,tc.Designation as Type ,a.DsgActivite ,a.icone  FROM clients c 
INNER JOIN typeClients tc ON c.idTypeClient=tc.idType 
INNER JOIN  activites a ON c.IdActivite=a.IdActivite
 WHERE (c.longitude!='' and c.latitude!='')  
 and  
 c.departement in
 ( SELECT da.idDepartement FROM affectations a 
	INNER JOIN detailAffectations da ON a.idaffectation=da.idaffectation
	WHERE 
	a.idVendeur=".$_SESSION['IdVendeur'] . " ) 

";

$params = array();	
$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt === false ) 
	{
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
$nRes = sqlsrv_num_rows($stmt);	//echo $nRes;

if($nRes==0)
{ ?>
	<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
		<br><br><br><br>
			<?php echo $trad['msg']['AucunResultat'];?>
	</div>
<?php
return;
}
else
	
{	
	$i=0;
	while($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
					{
								$tabClt[$i]['Distance']=distance($_POST['lat'],$_POST['lng'], $r['latitude'], $r['longitude'], "K");
								$tabClt[$i]['intitule']=$r['intitule'];
								$tabClt[$i]['adresse']=$r['adresse'];
								$tabClt[$i]['latitude']=$r['latitude'];
								$tabClt[$i]['longitude']=$r['longitude'];
								$i++;
								//array_push($tabClt,$r);					
								
					}
				//	parcourir($tabClt);
				usort($tabClt, 'cmp');
	$_SESSION['tabClt']=$tabClt;
	}
}

$tabRech=array();
if (isset($_POST['Intitule']) && $_POST['Intitule']!="")
{


	$array=$_SESSION['tabClt'];
	$keys = array_keys(array_column($_SESSION['tabClt'], 'intitule'), $_POST['Intitule']);
	$tabRech = array_map(function($k) use ($array){return $_SESSION['tabClt'][$k];}, $keys);

	
}else {
	$tabRech=$_SESSION['tabClt'];
}
if(count($tabRech)==0)
{
	?>
					<div class="resAff" style="text-align:center;min-height:300px;">
						<br><br>
							<?php echo $trad['msg']['AucunResultat'];?>
					</div>
					<?php
					return;
}
//parcourir($tabRech);
	?>	
		<table class=" tableFront" width="100%" cellspacing="5" cellpadding="5">
					<thead Class="headTable">
						<tr >
							<th class="tdTitle"><?php echo $trad['label']['Client'];?></th>
							<th><?php echo $trad['map']['adresse'];?></th>
							<th class="tdTitle"><?php echo  $trad['label']['Distance'];?></th>
						</tr>
					</thead><tbody>
				<?php	
				foreach($tabRech as $row)
				{	
				if(distance($_POST['lat'],$_POST['lng'], $row['latitude'], $row['longitude'], "K")<=3){
					?>
					<tr>
						<td data-title="Client" class="tdTitle"><?php  echo ucfirst($row['intitule']);?>	</td>
						<td data-title="Adresse"><?php echo ucfirst(stripslashes($row['adresse']));?></td>
						<td data-title="Adresse"><?php						
						echo distance($_POST['lat'], $_POST['lng'], $row['latitude'], $row['longitude'], "K") . " ";
						//echo ucfirst(stripslashes($row['Distance']));?></td>
					</tr>
				<?php 
				} 
				}?>
					 </tbody>
			</table>
	 <?php
	 exit;
 }	 
if(isset($_GET['RechClt'])){	

$sql = "
  SELECT   
	intitule, c.IdClient,c.nom,c.prenom,c.adresse,c.longitude,c.latitude ,a.DsgActivite ,a.icone  FROM clients c 
	INNER JOIN  activites a ON c.IdActivite=a.IdActivite 
	where c.idVendeur=".$_SESSION['IdVendeur'] . " 
";


if(isset($_POST['Intitule']) && ($_POST['Intitule']!='') )
	{	$sql .=" AND c.intitule  COLLATE Latin1_general_CI_AI Like '%".($_POST['Intitule'])."%' COLLATE Latin1_general_CI_AI " ;
	   $params = array();
	}
//	echo $sql;//return;
$params = array();	
$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt === false ) 
	{
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
$nRes = sqlsrv_num_rows($stmt);	//echo $nRes;
?>
<?php
if($nRes==0)
{ ?>
	<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
		<br><br><br><br>
			<?php echo $trad['msg']['AucunResultat'];?>
	</div>
<?php
return;
}
	?>		<div class="enteteL" style=""  >
			<div  class="divArticleL"  style="width:290px ;text-align:center;"><?php echo $trad['label']['Client'];?> </div>
			<div  class="divArticleL" style="width:382px;text-align:center;"><?php echo $trad['map']['adresse'];?> </div>	
			<div  class="divArticleL" style="width:220px;text-align:center;">  </div>	
			</div>		<DIV class="clear"></div>
				<div style="height:440px;overflow:scroll;border-bottom:1px solid #ebebeb" ><!--height:585px;-->
					<?php
					$k=0;
					while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
					$k++;
					?><div 	class="" style="border-bottom:1px solid #ebebeb;"  >					
								<div  class="divArticleL" style="width:300px;   BORDER:None;  padding: 6px 5px;"  ><?php  echo ucfirst($row['intitule']);?> </div>
								<div  class="divArticleL" style="width:382px; padding: 6px 5px; BORDER:None;"><?php echo ucfirst(stripslashes($row['adresse']));?> </div>	
								<div  class="divArticleL" style="width:140px; padding: 6px 5px;  BORDER:None;">
							<input type="button" value="<?php echo $trad['index']['DemarreVisite'] ; ?>" class="btn"
							onclick='InfoClt(<?php echo $row['IdClient'];?>)' />
								</div>	
					</div>
					<DIV class="clear"></div>

					<?php }  ?>
						</div>
				

<?php

	exit;
}
 if(isset($_GET['ListClt2'])){	 

 ?>
 	 <input type="button" value=""  class="close2" onclick="Fermer()" Style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;"/>
	 <div id="formRech" style="background:#fff;width:80%; text-align:center"  >	  
 <form id="formRechF" method="post" name="formRechF" > 

  
			<?php echo $trad['map']['intitule'];?><input class="form-control"  id="Intitule"name="Intitule" type="text" size="30"/>	
											
			&nbsp;<input type="button" value="<?php //echo $trad['button']['rechercher'];?>" class=" btn-primary"  id="rech" action="rech" 
			onclick="rechercher()" style="border:none;padding:7px 30px 5px 30px;" />	
		
	</form>
</div>	
				<div id="listClt  " class="ListeCmd"  style="BACKGround:#fff;"  >		</div>

<script language="javascript" type="text/javascript">
$(document).ready(function() {	
	rechercher();
});
function rechercher(){
		$('#formRechF').ajaxSubmit({
		target:'.ListeCmd',
		url:'mapClient.php?RechClt',
		method:	'post'
	})
}
 
</script>
	<?php

	exit;
	
}	
 if(isset($_GET['GetImage'])){ ?>

<?php	if($_GET['Chemin']!= "" and file_exists($_GET['Chemin'])){ ?>

<div style="position: relative">  
<input type="button" value="" class="close2" onclick="retour()" Style="position: absolute;<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:0px;"/>
<img src="<?php echo $_GET['Chemin'] ; ?>" alt="" width="950" height="600"  />
</div>

<?php
}
else
{ ?>
<div Style="height:700px;text-align: center;border:1px solid black">
<input type="button" value=""  class="close2" onclick="retour()" Style="position: absolute;<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:0px;"/>
	<span style="font-size:80px;vertical-align: middle;line-height: 460px;">
		<strong><?php echo $trad['map']['imageIntrouvable'] ; ?></strong>
	</span>
</div>
<?php
}
?>
<script language="javascript" type="text/javascript">
function retour(){
	$("#boxImage").dialog('close');
}
</script>

<?php
exit;
}
if (isset($_GET['infoClient'])){
?>

<?php
//print_r($_GET);//return;
//echo $_GET['idClient'];
$sql = "SELECT IdClient,nom+ ' ' +c.prenom as nom,c.intitule,c.adresse,c.ImgMagasin,
(SELECT ISNULL(sum(factures.totalTTC),0) 
FROM factures WHERE year(cast(date AS date))=year(getdate()) AND 
factures.idClient=".$_GET['idClient']." and EtatCmd=2) AS ca  ,(SELECT count(*) 
FROM visites WHERE year(cast(visites.dateFin AS date))=year(getdate()) and idClient=".$_GET['idClient'].") AS nbrVisites
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
?>  
<input type="button" value=""  class="close2" onclick="Fermer()" Style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;"/>
<input type="button" value="" title="Voir image"  class="cam" onclick="OpenImage('<?php echo  $row["ImgMagasin"]; ?>')" Style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;"/>

  <?php
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
	$DateVisite = $trad['map']['aucuneVisite'];//"aucune visite";

//$date = strtotime($rowD["d"]);

?>


<table  width="87%" cellspacing="10" border="0" >
	<tr>
		<td align="<?php echo $_SESSION['align']; ?>"><strong><u><?php echo $trad['map']['intitule']; ?></u> : </strong></td>
		<td><?php echo  $row["intitule"]; ?></td>
		<td align="<?php echo $_SESSION['align']; ?>"><strong><u><?php echo $trad['map']['adresse']; ?> </u> : </strong></td>
		<td style="width:310px;"><?php echo  wordwrap($row['adresse'], 50, "<br />\n", true); ?></td>
	</tr>
	<tr>
		<td align="<?php echo $_SESSION['align']; ?>"><strong><u><?php echo $trad['map']['dateDerVisite']; ?></u> : </strong></td>
		<td><?php echo $DateVisite; ?></td>
		<td align="<?php echo $_SESSION['align']; ?>"><strong><u><?php echo $trad['map']['nbrVisite']; ?></u> : </strong></td>
		<td><?php echo $row["nbrVisites"]; ?></td>
	</tr>
	<tr>
		<td align="<?php echo $_SESSION['align']; ?>"><strong><u><?php echo $trad['map']['ca']; ?></u> : </strong></td>
		<td><div style="direction:ltr;display:inline-block;"><strong><u><?php echo number_format($row["ca"], 2, '.', ' ') ; ?></u></strong></div>
		<div style="display:inline-block;"><strong><?php echo $trad['label']['riyal']; ?></strong></div></td>
			<?php
		$params3= array(				
				$_GET["idClient"],
				$IdDepot				
		) ;
			$CreditClt=creditClient($params3,$conn)[0];
			$Montant=creditClient($params3,$conn)[1];
		
			//ECHO $CreditClt."mmmm".$Montant;
		if((intval($CreditClt)==0) &&  (intval($Montant)!=0)){?>
		<td align="<?php echo $_SESSION['align']; ?>">
		
			<?php  echo $trad['label']['CreditClt'];?> </td>
			<td><div class="Credit">
				
			<?php echo number_format($Montant, 2, '.', ' ');?><?php echo $trad['label']['riyal']; ?>
			 </div>
			 </td>
		<?php } ?>
	</tr>
</table>
<?php


/************************Derniere Facture du client***************************/
$sql1 = "
SELECT df.IddetailFacture,g.IdGamme,mg.url,g.Designation as gamme,a.Designation as article,df.UniteVente, (qte) as qu,cast(f.[date] AS date) FROM factures f 
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
												$gamme[$key][$i]['unite']= $row['UniteVente'];
												$gamme[$key][$i]['qte']= $row['qu'];
										}
		
		}	
?>

	<DIV class="entete">
		<div class="divEntete" Style="width:220px;font-size:23px; vertical-align:middle" valign="middle" align="center"><?php echo $trad['label']['Gamme']; ?> </div>
		<div class="divEntete" Style="width:600px;font-size:23px; vertical-align:middle" valign="middle" align="center"><?php echo $trad['label']['Article']; ?> </div>
		<div class="divEntete" Style="width:132px;font-size:23px" align="center"><?php echo $trad['label']['unite']; ?> </div>
		<div class="divEntete" Style="width:132px;font-size:23px" align="center"><?php echo $trad['label']['qteVendu']; ?> </div>
	</DIV>


<div  style="overflow-y:scroll;min-height:250px;max-height:250px;"><!---->
<?php	$sum_article_qte=0;
foreach($gamme as $u=>$g){	?>
		<div style="background:white; width:950px;" class="ligne">
			<div class="divText" Style="font-size:26px;"  align="center"><!--width:200px;height:48px;border:2px solid #e7e9ee;-->
				<?php  echo ucfirst(stripslashes($g['gamme']));//echo $g['gamme'];"<img src='../".$g['url']."' width='220' height:'150' title='' />" ?>
			</div>
			<div style="width:640px; display:block;"></div>
		</div>
		   <?php 
		   $sum_gamme_qte=0;
		   foreach($g as $article){	
						    if(is_array($article)){ ?>
							<div class="ligne">	
								<div style="width:240px; display:block;"></div>
								<div class="divText" style="width:600px;TEXT-align:<?php echo $_SESSION['align']; ?>"> 
									<span style="margin-right:5px;"><?php  echo wordwrap(ucfirst($article['article']), 60, "<br />\n", true);?></span>
								</div> 
								<div class="divText" style="width:130px;TEXT-align:<?php echo $_SESSION['align']; ?>;"> 
										<?php  echo $trad['label'][$article['unite']];?>
								</div> 	
								<div class="divText" style="width:130px;TEXT-align:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;"> 
										<?php  echo $article['qte'];?>
								</div> 	
							</div> 
							
			<?php 
			$sum_gamme_qte+=intval($article['qte']);
			$sum_article_qte+=intval($article['qte']);			
				} 
			}
?>
<!--div style="border:2px solid red; margin-bottom:5px;float:right;margin-right:50px;">
		<div style="border:1px solid black;display:inline-block">
			<u><strong><?php //echo $trad['label']['total']; ?> </strong></u>
		</div>
		<div style="border:1px solid yellow;display:inline-block"><?php  echo $g['gamme']; ?>  :</div>
		<div style="border:1px solid blue;display:inline-block;text-align:right;float:right;">
			<?php //echo ($sum_gamme_qte); ?>
		</div>
</div-->
<!--div style="margin-bottom:5px;float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;margin-<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:20px;">
		<div style="display:inline-block">
			<u><strong><?php echo $trad['label']['total']; ?> </strong></u>
		</div>
		<div style="display:inline-block"><?php  echo $g['gamme']; ?>  :</div>
		<div style="display:inline-block;text-align:left;float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;">
			<?php echo ($sum_gamme_qte); ?>
		</div>
</div-->
<?php 
}
?>
</div>
<!--div style="TEXT-align:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;width:1130px;margin-top:5px;">
<u><strong> <?php echo $trad['label']['totalGlobal']; ?> : </strong><?php echo ($sum_article_qte); ?></u>
</div-->
<br/>
<?php
}else
{
echo "<br/><br/><br/><br/>";
}
?>
<div style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>; margin-<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:25px;" >
<?php //echo $_GET['distance']; 
//if(intval($_GET['distance']*1000)<= 100 ){ //en métre 1Km=1000M ?><?php //} ?>
<input type="button" value="<?php echo $trad['index']['DemarreVisite'] ; ?>" class="btn" onclick='demarrerVisite(<?php echo $_GET['idClient'];?>)' />
<?php if(isset( $_GET['from'])) {?>
<input type="button" value="<?php echo $trad['map']['itineraire'] ; ?>" class="btn"
 onclick='calculateRoute(<?php echo $_GET['from'];?>,<?php echo $_GET['to'];?> )' />
<?php }?>
</div>
<script language="javascript" type="text/javascript">
function Fermer(){
	$("#boxClient").dialog('close');
}
function OpenImage(chemin){
//alert(chemin);	
$('#boxImage').load("mapClient.php?GetImage&Chemin="+chemin).dialog('open');	
}		
function demarrerVisite(idClient)
{
jConfirm('<?php echo $trad['msg']['ConfirmerDemarrerVisite'] ; ?>', '<?php echo $trad['titre']['Confirm'] ; ?>', function(r) {
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
	$params1= array($_SESSION['IdTournee'],$dateD,$Hour,$IdClt,$IdDepot) ;//$_SESSION['IdTournee']
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

<div style="clear:both;"></div>
<?php
$whereVille="";
/*if(isset($_GET['Ville']))
{
$whereVille=" And v.Designation ='".$_GET['Ville']."'";
}*/
//parcourir($_GET);
$sql = "SELECT c.IdClient,c.nom,c.prenom,c.adresse,c.longitude,c.latitude,a.DsgActivite ,a.icone  FROM clients c  
INNER JOIN  activites a ON c.IdActivite=a.IdActivite 
WHERE (c.longitude!='' and c.latitude!='') and  c.idVendeur=".$_SESSION['IdVendeur'] ;
//. $whereVille ;//dc.idColisage //INNER JOIN typeClients tc ON c.idTypeClient=tc.idType

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
			<?php echo $trad['msg']['AucunResultat'];?>
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
		$features.="{position: new google.maps.LatLng(".$row['latitude'].",".$row['longitude']."),name:'".$row['nom'] ." ".$row['prenom']."',adresse:'".$row['adresse']."',idClient:".$row['IdClient'].",lat:".$row['latitude'].",lng:".$row['longitude'].",activite:'".$row['DsgActivite']."',icon:'".$row['icone']."'},";
		//,type:'".$row['Type']."'
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
    width: 950px;/*1260px;*/
    margin: 0 auto;
    height: 510px;/*734px;*/
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

var Center = new google.maps.LatLng(33.577930, -7.641432 );  
 map = new google.maps.Map(document.getElementById('map'), {          
 zoom: 17,		  
//center: Center,          
 mapTypeId: 'roadmap'        }); 
 var currentPosition="";
 if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                     currentPosition = {lat: position.coords.latitude, lng: position.coords.longitude}; 
					 map.setCenter(currentPosition);  
 }) 
}

 var iconBase = '';        
 var icons = {
				 vendeur:{ icon: iconBase + 'camion.png'}/*,  
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
				 Epicerie3: {icon: iconBase + 'Epicerie3.png' }*/}; 
  //Position actuel------------------------------------------------------------------
 var marker3 = new google.maps.Marker({   
        draggable: false, 
        animation: google.maps.Animation.DROP, 		
		icon: icons['vendeur'].icon,           
		map: map          
    });     
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

 //}
  //ready="yes";
//alert(ready);
 for (var i = 0, feature; feature = features[i]; i++) 
 { 
	//var distance= calcCrow(currentPosition.lat,currentPosition.lng,feature.lat,feature.lng);//Km
	//alert(" "+distance);
 //addMarker(feature); 

 //****if(distance <= 40)//40Km
 //****{

 var marker = new google.maps.Marker({ 
 position: feature.position,            
 //icon: icons[feature.type].icon,            
 map: map          
 });  
 
 var clt=JSON.parse($.cookie("client"));
if(clt[feature.idClient])
{
	var activite=feature.activite+clt[feature.idClient];//Consultation
	//alert(activite);
	var icon="img/"+activite+".png";
	marker.setIcon(icon);
}
else
{//alert(feature.icon);
	marker.setIcon(feature.icon);
}

 google.maps.event.addListener(marker, 'click', (function(marker, feature) {
        return function() {
         // infowindow.setContent(locations[i][0]);
         // infowindow.open(map, marker);
		 // alert("here "+feature.name+"-"+feature.adresse);//+feature[i][2]
		 // jAlert("Voulez-vous démarer une visite pour le client "+feature.name + " dont l'adresse est " +feature.adresse,"Message");
	//var distance= calcCrow(pos.lat,pos.lng,marker.getPosition().lat(),marker.getPosition().lng()) ;//km---------------------------------------------
	//alert(distance);
//*******Cookie******Read And Write****************************************/
var clt=JSON.parse($.cookie("client"));
if(!clt[feature.idClient])
{
var activite=feature.activite+"2";//Consultation
var icon="img/"+activite+".png";
marker.setIcon(icon);
clt[feature.idClient]="2";//Consultation
$.cookie("client",JSON.stringify(clt));
}
//************************************************************************/
var json1 = JSON.stringify( pos );	//position du vendeur actuel
var json2 = JSON.stringify( feature.position );//position du  client selectionner
$('#boxClient').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("mapClient.php?infoClient&idClient="+feature.idClient+"&from="+json1+"&to="+json2).dialog('open');//+"&distance="+distance	
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
//**** } 

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
    width:950px;/*1260px;*/
    margin: 0 auto;
    height: 480px;/*734px;*/
    padding: 0; }      
 html, body {  height: 80%;     margin: 0;     padding: 0;      }    
 </style>    
<input type="hidden" value="" name="Ville" id="Ville" /> 
 <div id="map"></div>    
 <!--script src="js/jquery.min.js" type="text/javascript" ></script-->   
 
<?php



exit;
}
?>
<?php include("header.php"); ?>

<script src="js/jquery-filestyle.min.js" type="text/javascript"></script>
<link href="css/jquery-filestyle.css"  rel="stylesheet" />
<div class="headVente">
<DIV class="mapHead">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="vertical-align:middle" ></a>
					> <span  Class="TitleHead" ><?php echo $trad['index']['DemarreVisite'] ; ?></span>

</div>
<div class="DivlistClt"> 
<img  onclick="ajouter()" src="../images/Add48.png" height="48" width="48" style="vertical-align:middle;display:none" ></a>
<img   onclick='ListClt()'  src="../images/user64.png" height="64" width="64" style="vertical-align:middle" ></a>

</div>
</div>


<div id="formRes" ></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<div id="boxClient"> </div>
<div id="boxImage"> </div>
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
<?php
include("footer.php");
?>

<script language="javascript" type="text/javascript">

  function ajaxindicatorstart(text)
	{
		jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="../images/loading.gif"><div>'+text+'</div></div><div class="bg"></div></div>');
		if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
		}
		
		jQuery('#resultLoading').css({
			'width':'100%',
			'height':'100%',
			'position':'fixed',
			'z-index':'10000000',
			'top':'0',
			'left':'0',
			'right':'0',
			'bottom':'0',
			'margin':'auto'
		});	
		
		jQuery('#resultLoading .bg').css({
			'background':'#000000',
			'opacity':'0.7',
			'width':'100%',
			'height':'100%',
			'position':'absolute',
			'top':'0'
		});
		
		jQuery('#resultLoading>div:first').css({
			'width': '250px',
			'height':'75px',
			'text-align': 'center',
			'position': 'fixed',
			'top':'0',
			'left':'0',
			'right':'0',
			'bottom':'0',
			'margin':'auto',
			'font-size':'16px',
			'z-index':'10',
			'color':'#ffffff'
			
		});

	    jQuery('#resultLoading .bg').height('100%');
        jQuery('#resultLoading').fadeIn(300);
	    jQuery('body').css('cursor', 'wait');
	}

	function ajaxindicatorstop()
	{
	    jQuery('#resultLoading .bg').height('100%');
        jQuery('#resultLoading').fadeOut(300);
	    jQuery('body').css('cursor', 'default');
	}
function ajouter(){	
	window.location = "ajout_client.php";
//$('#formRes').load("ajout_client.php");
/*
ajaxindicatorstart('<?php echo $trad['map']['messageChargementMap'];?>');
    var $modal = $('#Box');
		var url='clients.php?getLocation';
     $.get(url, null, function(data) {
      //$modal.find('.modal-body').html(data);
	   $modal.find('.modal-body').html(data);
    })*/
}
var ready;
function loadWindow()
{
//alert("loadWindow "+ready);
	//if(ready == "no")
	//{
		//alert("reload");
		location.reload();
	//}
}
$(document).ready(function() {
		$('#formRes').html('<center><br/><br/><?php echo $trad['msg']['Patienter'];?> <a onClick="loadWindow()" href="#"><img src="img/rafraichir.png"/></a></center>');
	  if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                    pos = {lat: position.coords.latitude, lng: position.coords.longitude};
			//	alert('mapClient.php?map&lat='+pos.lat+'&lng='+pos.lng);
					 	$('#formRes').load('mapClient.php?map&lat='+pos.lat+'&lng='+pos.lng);
						
                });
	
				}
				
				
//window.setTimeout( loadWindow, 15000 ); //15seconds
		//$.validator.messages.required = '';
  		//$('#formRes').load('mapClient.php?search');
		var map; 
		//ready="no"; 
		 if(!$.cookie("client"))
		{
			var clt={};
			$.cookie("client",JSON.stringify(clt));
		}

					$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	950,/*1100*/
					height			:	600,
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
			$('#boxImage').dialog({
					autoOpen		:	false,
					width			:	950,
					height			:	600,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Image'});
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
			jAlert("No result","Message");
		  }
		}
		else {
		  jAlert(status,"Message");
		}
	  });
	}




function ListClt(){

	/*  if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                    pos = {lat: position.coords.latitude, lng: position.coords.longitude};*/
			//	alert('mapClient.php?map&lat='+pos.lat+'&lng='+pos.lng);
					 
						//var url='mapClient.php?ListClt&lat='+pos.lat+'&lng='+pos.lng;
						var url='mapClient.php?ListClt2';
					$('#boxClient').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
              //  });
	
				//}
				
	
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

function Fermer(){
	$("#boxClient").dialog('close');
}
 
 function InfoClt(idClient){
	 $('#boxClient').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("mapClient.php?infoClient&idClient="+idClient).dialog('open');//+"&distance="+distance	
 }
</script>
 <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4<?php echo ($_SESSION['lang'] == 'ar' ) ? '&language=ar' : '&language=en'; ?>"> </script>
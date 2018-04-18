<?php 
include("php.fonctions.php");
include("class.uploader.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "affectations";
$cleTable = "idaffectation";
$nom_sansDoublons = "Numéro d\'immatriculation";

if(isset($_GET['GetImage'])){ 
 if($_GET['Chemin']!= "" and file_exists($_GET['Chemin'])){ ?>

<div style="position: relative;">  
<img src="<?php echo $_GET['Chemin'] ; ?>" alt="" width="1070" height="540"  />
</div>

<?php
}
else
{ ?>
<div Style="text-align: center;">
<span style="font-size:80px;vertical-align: middle;line-height: 500px;  "><strong><?php echo $trad['map']['imageIntrouvable'] ; ?></strong></span>


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
?>  
<input type="button" value="" title=""  class="cam" onclick="OpenImage('<?php echo  "frontend2/".$row["ImgMagasin"]; ?>')" Style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;"/>

  <?php
/******************************* Date of last **************************************/
/*$req = "
SELECT cast([date] AS date)AS d,heure FROM factures f WHERE  f.IdFacture IN (SELECT max(IdFacture) FROM factures WHERE idClient=?)";
$stmt1 = sqlsrv_query( $conn, $req ,array($_GET['idClient']),array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	if( $stmt1 === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}

$nRes = sqlsrv_num_rows($stmt1);
$DateVisite="";$hour="";
if($nRes != 0 )
{
$rowD = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC ) ;
$DateVisite = date_format($rowD["d"], 'd/m/Y');
$seconds=strtotime($rowD['heure']); 
$hour=HoutToWords($seconds);

}
else
$DateVisite ="aucune visite";
*/
//$date = strtotime($rowD["d"]);

?>


<table  width="85%" style="margin:auto" cellspacing="10" border="0">
<tr>
<td align="<?php $_SESSION['align'] ; ?>"><strong><u><?php echo $trad['map']['intitule']; ?></u> :</strong></td>
<td><?php echo  $row["intitule"]; ?></td>
<td align="<?php $_SESSION['align'] ; ?>"><strong><u><?php echo $trad['map']['adresse']; ?> </u> :</strong></td>
<td><?php echo  wordwrap($row['adresse'], 50, "<br />\n", true); ?></td>
</tr>
<!--tr>
<td align="left"><strong><u>Date Derniére Visite </u>: </strong></td>
<td><?php //echo $DateVisite; ?></td>
<td align="left"><strong><u>Heure Derniére Visite </u>: </strong></td>
<td><?php //echo $hour; ?></td>
</tr-->
<!--tr>
<td align="left"><strong><u>Nbr Visites</u>: </strong></td>
<td><?php //echo $row["nbrVisites"]; ?></td>
<td align="left"><strong><u>CA annuelle </u>: </strong></td>
<td><strong><u><?php //echo number_format($row["ca"], 2, '.', ' ') . "  DH"; ?></u></strong></td>
<td align="left"></td>

</tr-->
</table>
<?php


/************************facture client faite par le vendeur courant***************************/
$sql1 = "
SELECT df.IddetailFacture,g.IdGamme,mg.url,g.Designation as gamme,a.Designation as article, (type*qte) as qu,cast(f.[date] AS date) FROM factures f 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
INNER JOIN articles a ON df.idArticle=a.IdArticle 
INNER JOIN gammes g ON a.IdFamille=g.IdGamme
INNER JOIN mediaGammes mg ON g.IdGamme=mg.idGamme
WHERE f.visite =".$_GET['IdVisite']." 
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


<table width="90%" style="margin:auto" border="0" cellspacing="2" >
<tr class="entete" style="height:40px;">
<td align="center"><?php echo $trad['label']['Gamme']; ?></td>
<td align="center"><?php echo $trad['label']['Article']; ?> </td>
<td align="center"><?php echo $trad['label']['qteVendu']; ?></td>
</tr>

<?php	$sum_article_qte=0;
foreach($gamme as $u=>$g){	?>
		<tr class="pair">
		<td colspan="3" align="<?php $_SESSION['align'] ; ?>" style="background-color:f2f2f2; font-size:18px; height:40px;" class="ligne">
		&nbsp;&nbsp;	<?php  echo ucfirst($g['gamme']);?>
		</td>
		</tr>
		   <?php 
		   $sum_gamme_qte=0;
		   foreach($g as $article){	
						    if(is_array($article)){ ?>
							<tr class="impair" style="">	
								<td style="width:240px; height:40px;"></td>
								<td class="divText" style="width:600px;"> 
									<span style="margin-right:5px;"><?php  echo wordwrap(ucfirst($article['article']), 60, "<br />\n", true);?></span>
								</td> 
								<td class="divText" style="width:130px;TEXT-align:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;"> 
										<?php  echo $article['qte'];?>&nbsp;&nbsp;
								</td> 	
							</tr> 
							
			<?php 
			$sum_gamme_qte+=intval($article['qte']);
			$sum_article_qte+=intval($article['qte']);			
				} 
			}
?>
<tr style="background-color:#f0f9ff">
<td colspan="3" style="TEXT-align:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;width:1010px; margin-top:10px;height:40px;">
<!--u><strong><?php //echo $trad['label']['total']; ?>  <?php // echo $g['gamme']; ?>: </strong><?php //echo ($sum_gamme_qte); ?></u-->

<div style="display:inline-block">
			<u><strong><?php echo $trad['label']['total']; ?> </strong></u>
		</div>
	<div style="display:inline-block"><?php  echo $g['gamme']; ?>  :</div>
		<div style="display:inline-block;text-align:left;float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;">
			<strong><?php echo ($sum_gamme_qte); ?></strong>
		</div>
</td>

</tr>
<?php 
}
?>
</table>
<div style="TEXT-align:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;width:1010px;margin-top:10px;">
<u><strong> <?php echo $trad['label']['totalGlobal']; ?>  :<?php echo ($sum_article_qte); ?></strong></u>
</div>
<br/>
<?php
}else
{
echo "<br/><br/><br/><br/>";
}
?>

<script language="javascript" type="text/javascript">
function Fermer(){
	$("#boxClient").dialog('close');
}
function OpenImage(chemin){
//alert(chemin);	
//chemin='frontend2/img_magasins/48ae5003b471e817276f4e68b0c6fabc.jpg';
$('#boxImage').load("trace.php?GetImage&Chemin="+chemin).dialog('open');	   
}		
  
</script>
<?php
exit;
}
if(isset($_GET['delPlusieursArticle'])){
	$sup = true;
	$ligneSelect = explode(",",$_POST['CLETABLE']);
	foreach($ligneSelect as $a=>$ligne){
		if($ligne!=0){
			$data = explode(",",$ligne);
			$idp = $data[0];
			
			$sqlReq = "update $tableInser set etatSup=0 where $cleTable = ?";
			$params1= array($idp) ;

			$stmt1 = sqlsrv_query( $conn, $sqlReq, $params1 );
			if( $stmt1 === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
				$sup=false;
			}

		}
	}
	if($sup == true){
		?><script language="javascript" > 
				//alert('picesphp');
				alert('Supression de la sélection effectuée.'); 
				rechercher();
		  </script>
		  <?php
	}else{
		?><script language="javascript" > alert('Un ou plusieurs elements de la selection n\'ont pas pu etre supprimes.'); </script><?php
	}

exit;
mysql_close();
}
if(isset($_GET['goMod'])){


$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
/**********************Controle doublon****CB************************/
$sql = "SELECT * FROM affectations WHERE idVendeur=? and idVehicule=? ";
$param= array($_POST['Vendeur'],$_POST['Vehicule']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql , $param , $options );
if( $stmt === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	echo $error;return;
}
$count = sqlsrv_num_rows($stmt);
	if($count >0)
	{
	?>	
							<script type="text/javascript"> 
							//alertK('L\'ajout a été effectué.',1);
							alert('Ce véhicule est déja affecté à ce vendeur .');
							</script>
						
							<?php
							return;
	}
//-----------------Update Affectation----------------//

$reqUpdate1 = "Update ".$tableInser." set [idVendeur]=? ,[idVehicule]=?";

$params1= array($_POST["Vendeur"],$_POST["Vehicule"]) ;

$stmt1 = sqlsrv_query( $conn, $reqUpdate1, $params1 );
if( $stmt1 === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message'] . " <br/> ";
}

if( $error=="" ) {
     sqlsrv_commit( $conn );
?>
		<script type="text/javascript"> 
			alert('le modification a été effectué.');
			$('#boxArticle').dialog('close');
			rechercher();
		</script>
<?php
} else {
     sqlsrv_rollback( $conn );
     echo "<font style='color:red'>".$error."</font>";
}
/********************************************************/	

exit;
	
}
if(isset($_GET['goAdd'])){
/*print_r($_POST);


return;*/

$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
/**********************Controle doublon****CB************************/
$sql = "SELECT idaffectation FROM affectations WHERE idVendeur=? and idVehicule=? ";
$param= array($_POST['Vendeur'],$_POST['Vehicule']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql ,$param,$options);
if( $stmt === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	echo $error;return;
}
$count = sqlsrv_num_rows($stmt);
$IdAffectation ="";
	if($count >0)//*****************Already Exist affectation
	{
      sqlsrv_fetch($stmt) ;
      $IdAffectation = sqlsrv_get_field( $stmt, 0);
	}else{////***********************Add new Affectation
	//-----------------Add Affectation----------------//
		//parcourir($_POST);return;
		$reqInser1 = "INSERT INTO ".$tableInser." ([idVendeur] ,[idVehicule],idDepot) values (?,?,?)";

		$params1= array($_POST["Vendeur"],$_POST["Vehicule"],1) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}
		//---------------------------IDaffectation--------------------------------//
		$sql = "SELECT max(idaffectation) as idaffectation FROM affectations";
		$stmt2 = sqlsrv_query( $conn, $sql );
		if( $stmt2 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
		}
		sqlsrv_fetch($stmt2) ;
		$IdAffectation = sqlsrv_get_field( $stmt2, 0);
	}
	
	
foreach($_POST['Departement'] as $depart){/*********************Pour chaque departement**********************************/
	/*********Verification si il est deja affecté au vendeur sélectionné********/
	$sql = "SELECT * FROM affectations a INNER JOIN vendeurs v ON v.idVendeur = a.idVendeur INNER JOIN detailAffectations da ON da.idaffectation = a.idaffectation INNER JOIN departements d ON d.iddepartment =da.idDepartement INNER JOIN vehicules v2 ON a.idVehicule=v2.idVehicule WHERE v.idvendeur=? and v2.idVehicule=? and d.iddepartment=?";
	$param2= array($_POST['Vendeur'],$_POST['Vehicule'],$depart);
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt3 = sqlsrv_query( $conn, $sql ,$param2,$options);
	if( $stmt3 === false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
		echo $error;return;
	}
	$count = sqlsrv_num_rows($stmt3);
	
		if($count ==0)//*****************Already Exist affectation
		{
			$reqInser3 = "INSERT INTO detailAffectations([idaffectation],[idDepartement],[idDepot] ) values (?,?,?)";
				$params3= array($IdAffectation,$depart,1) ;
				$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3);
				if( $stmt4 === false ) {
					$errors = sqlsrv_errors();
					$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
					break ;
				}
		}
		
}

if( $error=="" ) {
     sqlsrv_commit( $conn );
?>
		<script type="text/javascript"> 
			alert('L\'ajout a été effectué.');
			$('#boxArticle').dialog('close');
			rechercher();
		</script>
<?php
} else {
     sqlsrv_rollback( $conn );
     echo "<font style='color:red'>".$error."</font>";
}


/********************************************************/	

exit;
}

if (isset($_GET['mod'])){	
	$ID= $_GET['ID'] ;
	$sql = "select * from affectations where idaffectation=? ";
	$param= array($ID);
	$stmt = sqlsrv_query( $conn, $sql ,$param);
	if( $stmt === false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
		echo $error;return;
	}

 $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
?>
	<div id="resMod" style="padding:5px;">&nbsp;</div>
	<form id="formMod" action="NULL" method="post" name="formAdd1"> 
		
		<table width="100%" border="0" align="center" cellpadding="5">
       
		 <tr>
		 <td><div class="etiqForm" id="" > <strong>Vendeur </strong> : </div>
            </td>
            <td>
            <!--select  name="Famille" id="Famille"  multiple="multiple" tabindex="3" style="width:220px;" class="Select Famille">
				<option>Famille1</option>
				<option>Famille2</option>
			</select-->
			 	<select  name="Vendeur" id="Vendeur"  multiple="multiple" tabindex="3" class="Select Vendeur" style="display:visible;width:220px;">
		
                         <?php $sql = "SELECT v.idVendeur ,v.nom+ ' ' + prenom AS Nom FROM vendeurs v";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>
			 
          </tr>
		   <tr>
        	
			<td><div class="etiqForm" id="" ><strong>Véhicule </strong> : </div>
            </td>
            <td>
                <select  name="Vehicule" id="Vehicule"  multiple="multiple" tabindex="3" class="Select Vehicule" style="display:visible;width:220px;">
		
                      <?php $sql = "SELECT idVehicule,immatriculation + ' ' + Designation as Lib FROM vehicules";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVehicule'] ?>"><?php echo $donnees['Lib']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>   
			
          </tr> 
		  		
		
		 	  
		<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
	 <script type="text/javascript">
	 		$('#Vehicule').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le véhicule ',single:true,maxHeight: 100
});
$('#Vendeur').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le vendeur ',single:true,maxHeight: 100
});	
	$(document).ready(function(){	
	$("#Vehicule").multipleSelect("setSelects", 	[<?php echo  $row["idVehicule"];?>]);
	$("#Vendeur").multipleSelect("setSelects", 	[<?php echo  $row["idVendeur"];?>]);	
	});
	</script>
    <script src="js/jquery.validerForm.js" type="text/javascript"></script>    
 <?php 
exit;
}

if (isset($_GET['add'])){
?>

<script language="javascript" type="text/javascript">
$('#Vehicule').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le véhicule ',single:true,maxHeight: 100
});
$('#Vendeur').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le vendeur ',single:true,maxHeight: 100
});	
$('#Departement').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le département ',single:false,maxHeight: 100
});	
$(document).ready(function(){
	 $('body').on('change', '#Vendeur', function() {
	 			var Vendeur =$('#Vendeur').val();
				if(Vendeur!="") {
					$('div.Vendeur').removeClass('erroer');
					$('div.Vendeur button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
	$('body').on('change', '#Vehicule', function() {
	 			var Vehicule =$('#Vehicule').val(); <?php //echo $row["IdVille"];?>
				if(Vehicule!="") {
					$('div.Vehicule').removeClass('erroer');
					$('div.Vehicule button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
	$('body').on('change', '#Departement', function() {
	 			var Departement =$('#Departement').val(); <?php //echo $row["IdVille"];?>
				if(Departement!="") {
					$('div.Departement').removeClass('erroer');
					$('div.Departement button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
 });	
	</script>
<div id="resAdd" style="padding:5px"></div>
<form id="formAdd" action="NULL" method="post" enctype="multipart/form-data" name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="5">
       
		 <tr>
		 <td><div class="etiqForm" id="" > <strong>Vendeur </strong> : </div>
            </td>
            <td>
            <!--select  name="Famille" id="Famille"  multiple="multiple" tabindex="3" style="width:220px;" class="Select Famille">
				<option>Famille1</option>
				<option>Famille2</option>
			</select-->
			 	<select  name="Vendeur" id="Vendeur"  multiple="multiple" tabindex="3" class="Select Vendeur" style="display:visible;width:220px;">
		
                         <?php $sql = "SELECT v.idVendeur ,v.nom+ ' ' + prenom AS Nom FROM vendeurs v";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>
			 
          </tr>
		   <tr>
        	
			<td><div class="etiqForm" id="" ><strong>Véhicule </strong> : </div>
            </td>
            <td>
                <select  name="Vehicule" id="Vehicule"  multiple="multiple" tabindex="3" class="Select Vehicule" style="display:visible;width:220px;">
		
                      <?php $sql = "SELECT idVehicule,immatriculation + ' ' + Designation as Lib FROM vehicules";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVehicule'] ?>"><?php echo $donnees['Lib']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>   
			
          </tr> 
		    <tr>
        	
			<td><div class="etiqForm" id="" ><strong>Département </strong> : </div>
            </td>
            <td>
              <?php 
			//	echo ChargerSelect("pointvente","Designation","IdPointVente");?>
					<?php                              
						  $Options = "";
				          	
						/*$req="select IdType,Designation, CodeClient, Nom,Prenom from typeclients t 
								inner join clients clt  on clt.IdType=t.IdType
									";	*/
								
						$req="SELECT v.Designation AS ville , v.idville,d.Designation as departement, d.iddepartment  FROM villes v INNER JOIN departements d ON v.idville=d.idVille";		
			
						   $res = sqlsrv_query( $conn, $req, array(), array( "Scrollable" => 'static' ) );	
						   if( $res === false )  
						{  
							  if( ($errors = sqlsrv_errors() ) != null)  
							  {  
								 foreach( $errors as $error)  
								 {  
									echo "SQLSTATE: ".$error[ 'SQLSTATE']."\n";  
									echo "code: ".$error[ 'code']."\n";  
									echo "message: ".$error[ 'message']."\n";  
								 }  
							  }  
						}  
						   
						   $i=0;$s="";  
						  
						   
				 if(sqlsrv_num_rows($res) !=0){
							   $i=0;
						
						
						$groups = array();
						$i=0;
							 while($row=sqlsrv_fetch_array($res)){
							
								$key = $row['idville'];
								$i=$i+1;
								if (!isset($groups[$key])) {
									$groups[$key] = array();
									$groups[$key]['idville']=$row['idville'];
									$groups[$key]['ville']=$row['ville'];
									
								} //else {
								if($row['idville']!=""){
									$groups[$key][$i]['iddepartment'] = $row['iddepartment'];
									$groups[$key][$i]['departement'] = $row['departement'];
								}
							}

			
				//	parcourir($groups);
					foreach($groups as $u=>$v){
							
								$Options.= '<optgroup label='.$v['ville'].'>';
								
								
									foreach($v as $r){
									if(is_array($r)){
										$Options.= "<option value='".$r['iddepartment']."' >".$r['departement']."</option>";
									}
								}
						$Options.= "</optgroup>";
					 }
			 }
			

                                           ?>
			 <select multiple="multiple" id="Departement" name="Departement[]" Class="Select Departement" style="width:220px">
				<?php echo   $Options;?>
			</select>
            </td>   
			
          </tr> 		
		
		 	  
		<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
	
	<!-- Styles Js -->
	
	
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){
		$where="";
		if(isset($_POST['DateD'])  )
		{
			 	//------- $where= " AND cast(tv.Date AS date) = '".($_POST['DateD'])."' ";
				$where= " AND tv.Date  = '".($_POST['DateD'])."' ";
		}
		else
		{
				//$where=" AND cast(tv.date AS date)='".(date('d/m/Y'))."'";
				$where=" AND tv.date='".(date('d/m/Y'))."'";
		}

/*$sqlA = " SELECT v.cin ,v.nom + ' ' + v.prenom AS 'Nom' ,fa.idFamille,fa.codeFamille,fa.Designation AS famille,a.CB ,a.Reference, a.Designation  AS article,sum((CASE WHEN df.type = '' THEN 1 ELSE df.type END ) * (df.qte)) as qte, sum(df.ttc) AS ttc ,
(SELECT count( DISTINCT visite) FROM factures fa WHERE fa.idVendeur=v.idVendeur) as countVisite
FROM factures f 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture
INNER JOIN vendeurs v ON f.idVendeur=v.idVendeur
INNER JOIN articles a ON a.IdArticle=df.idArticle 
INNER JOIN familles fa ON fa.idFamille=a.IdFamille ".$where;*/

$sql = "SELECT tv.Latitude,tv.Longitude,tv.[Date],tv.Heure,tv.IdVisite,c.IdClient,c.latitude lat , c.longitude lng,a.DsgActivite,a.icone
FROM TraceVendeur tv 
LEFT JOIN clients c ON tv.IdClient=c.IdClient
LEFT JOIN  activites a ON c.IdActivite=a.IdActivite WHERE tv.IdVendeur=? ".$where;
//echo $sql;
//echo $sql."-----".$_POST['CodeVnd'];return;
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

	$params = array($_POST['CodeVnd']);
//echo $_POST['CodeVnd']; return;
	$stmt=sqlsrv_query($conn,$sql,$params,$options);
	$nRes = sqlsrv_num_rows($stmt);

	/*	$resAff = mysql_query($reqAff)or die(mysql_error());*/
		if($nRes==0)
		{ ?>
					<div class="resAff">
						<br><br>
						<?php echo $trad['msg']['AucunResultat']; ?>
						<script language="javascript" type="text/javascript">
							Hide_Load();
						</script>
					</div>
					<?php
		}
else
{
$i=0;
	$features="";
	$clients="";
	$longitude_Secteur="";
	$latitude_Secteur="";
	//echo $nRes;
	$i=0;
	//echo "hereeeeeeeeee";
	/*for($i=0;$i<$nRes;$i++)
	{
	echo $i."<br/>";
	}*/
	$i=0;
	$location=array();
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
	//tv.Latitude 0 ,tv.Longitude 1 ,tv.[Date] 2 ,tv.Heure 3 ,tv.IdVisite 4 ,c.IdClient 5 ,c.latitude lat 6 , c.longitude lng 7 ,a.DsgActivite 8 ,a.icone 9
		$seconds=strtotime($row[3]); 
		$hour=HoutToWords($seconds);
		$location[$i]=array();
		if($row[5]!= "")//Visite client
		{
			$location[$i]['lat']=$row[6];
			$location[$i]['lng']=$row[7];
			$location[$i]['hour']=$trad['label']['lheure']." : ".$hour." ";
			$location[$i]['IdClient']=$row[5];
			$location[$i]['IdVisite']=$row[4];
			$location[$i]['activite']=$row[8];
			$location[$i]['icon']=$row[9];		
		//$features.="{lat:".$row[6].",lng:".$row[7].",hour:' Heure : ".$hour."',IdClient:".$row[5].",IdVisite:".$row[4].",activite:'".$row[8]."',icon:'".$row[9]."'},";
		   // $features.=$i . "<br/>";
		}
		else{
			$location[$i]['lat']=$row[0];
			$location[$i]['lng']=$row[1];
			$location[$i]['hour']= $trad['label']['lheure']." : ".$hour." ";
			$location[$i]['IdClient']="";
			$location[$i]['IdVisite']="";
			$location[$i]['activite']="";
			$location[$i]['icon']="";	
		//$features.="{lat:".$row[0].",lng:".$row[1].",hour:' Heure : ".$hour."',IdClient:'',IdVisite:'',activite:'',icon:''},";
		//	$features.=$i . "<br/>";	
		}
		$i++;
	}
	/*while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{	
	$seconds=strtotime($row['Heure']); 
	$hour=HoutToWords($seconds);
	if($row['IdClient']!= "")
	{
	$features.="{lat:".$row['lat'].",lng:".$row['lng'].",hour:' Heure : ".$hour."',IdClient:".$row['IdClient'].",IdVisite:".$row['IdVisite'].",activite:'".$row['DsgActivite']."',icon:'".$row['icone']."'},";
	}
	else{
	$features.="{lat:".$row['Latitude'].",lng:".$row['Longitude'].",hour:' Heure : ".$hour."',IdClient:'',IdVisite:'',activite:'',icon:''},";
	}
	//echo $i."<br/>";
	//$i++;
	}
$features=substr($features, 0, -1);
*/

//echo $features;
//parcourir($location);
?>
<div id="map" style="width: 100%;height:480px;margin: 0px;padding: 0px"></div>
<script language="javascript" type="text/javascript">

initialize() ;
//$('#loading').dialog('close');
Hide_Load();
//Hide_Load();
/*var MapPoints = '[{"address":{"address":"plac Grzybowski, Warszawa, Polska","lat":"52.2360592","lng":"21.002903599999968"},"title":"Warszawa"},{"address":{"address":"Jana Paw\u0142a II, Warszawa, Polska","lat":"52.2179967","lng":"21.222655600000053"},"title":"Wroc\u0142aw"},{"address":{"address":"Wawelska, Warszawa, Polska","lat":"52.2166692","lng":"20.993677599999955"},"title":"O\u015bwi\u0119cim"}]';*/

var MY_MAPTYPE_ID = 'custom_style';

function initialize() {
 var MapPoints = <?php echo json_encode($location); ?>;//[<?php echo $features; ?>];
    if (jQuery('#map').length > 0) {

        var locations =MapPoints; //jQuery.parseJSON(MapPoints);

        window.map = new google.maps.Map(document.getElementById('map'), {
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        });

        var infowindow = new google.maps.InfoWindow();
        var flightPlanCoordinates = [];
        var bounds = new google.maps.LatLngBounds();

        for (i = 0; i < locations.length; i++) {
		if(locations[i].IdClient != '')
		{
		     marker = new google.maps.Marker({
			    position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
                map: map,
				icon:'frontend2/'+locations[i].icon      
            });
			 flightPlanCoordinates.push(marker.getPosition());
            bounds.extend(marker.position);

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
					if(locations[i].IdClient != '')
					{
					$('#boxClient').html('<center><br/><br/><img src="images/loading2.gif" /></center>').load("trace.php?infoClient&idClient="+locations[i].IdClient+"&IdVisite="+locations[i].IdVisite).dialog('open');
					}
					else
					{
                    infowindow.setContent(locations[i].hour);
                    infowindow.open(map, marker);
					}
                }
            })(marker, i));
		}
		else
		{
            marker = new google.maps.Marker({
			    position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
                map: map
            });
			 flightPlanCoordinates.push(marker.getPosition());
            bounds.extend(marker.position);

         
		}
              google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
                return function () {
					
                    infowindow.setContent(locations[i].hour);
                    infowindow.open(map, marker);
					
                }
            })(marker, i));
            }

            map.fitBounds(bounds);

            var flightPath = new google.maps.Polyline({
                map: map,
                path: flightPlanCoordinates,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

        }
    }

	</script>
<?php
}
exit;
}
include("header.php");
?>
<div class="contenuBack">
<div id="brouillon" style="display:block">  </div> 
<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['statistic']; ?>&nbsp;<img src="images/tri.png" />
    &nbsp;<?php echo $trad['Menu']['trace']; ?>&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="80%" border="0" style="margin:auto" cellpadding="5" cellspacing="10" align="center" >
				<tr>
				<td width="20%" align="right" valign="middle">
				<strong><?php echo $trad['label']['date']; ?> :</strong>
				</td>
				<td width="22%">
<input class="formTop" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	
<input name="DATED" type="hidden" value=""/>	

				</td>
				 <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?> " />
				  <!--input name="button" type="button"  onClick="test();" value="Rechercher" class="bouton32" action="rech"
			  title="test " /-->		  
					  <!--input name="button2" type="reset" onClick="" value="Effacer" class="bouton32" action="effacer" title="Effacer"/--></span><br/></td>
	
				</tr>
				<tr>
					<td  align="right" valign="middle">
					<div class="etiqForm" id="SYMBT" ><strong><?php echo $trad['label']['codeVnd']; ?> :</strong> </div>				</td>
					<td width="30%">
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					
					 	<select  name="CodeVnd" id="CodeVnd"  multiple="multiple" tabindex="3" class="Select CodeVnd" style="display:visible;width:220px;">
		                 <?php $sql = "SELECT v.idVendeur ,v.nom+ ' ' + prenom AS Nom FROM vendeurs v";
                               $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );  
							    $i=0;					   
                                 while ($donnees =  sqlsrv_fetch_array($reponse))
                                {
								if($i==0)
								{
                                ?>
								   <option selected="selected" value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
								 <?php
								}else{
								 ?>
									<option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
								 <?php
								} 
								$i++;
								}
								 ?>
				</select>
			
					</td>
				 
				</tr>			  
			 </table>
			 
		 </div>
		<!--div id="formFiltre" style="">
		<table border="0"  width="100%">
			<tr height="20">
			  <!--td width="23%">
			  <div id="filtreNPP">
			  	R&eacute;sultats par page : <select name="npp" id="npp" onChange="filtrer();">
					<option value="10">10</option>
					<option value="20" >20</option>
					<option value="50" selected="selected">50</option>
					<option value="100">100</option>
				</select>				
			  </div>
			  </td>
			  <td width="12%">Pages : <span id="cont_pages">
			    <select name="pact"><option value=1>1</option></select></span>
		  	  </td-->
			  <!--td width="50%" style="text-align:right">Crit&egrave;re de tri : 
				  <select name="cTri" onChange="filtrer();">
				  <option value="IdArticle">  </option>
				<option value="Immatriculation"> Immatriculation </option>
				<option value="DsgTransport">Désignation d'transport </option>				
				</select>
		  	  </td>
			  <td width="50%">&nbsp;&nbsp;&nbsp;&nbsp; Ordre de tri : 
				  <select name="oTri" onChange="filtrer();">
				<option value="ASC"> Croissant </option>
				<option value="DESC" selected> Decroissant </option>
				</select>
			  </td>

			</tr>
		</table>
	</div-->
	</form>
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>

<div id="formRes" style="overflow-y:scroll;min-height:500px; margin:auto ; width:95%"></div>
<input type="hidden" id="act"/>
</div>

<div id="boxClient"> </div>
<div id="boxImage"> </div>
<div id="loading"> </div>

<script language="javascript" type="text/javascript">

$(document).ready(function(){	
$('#CodeVnd').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le vendeur',single:true,maxHeight: 100
});
		calendrier("DateD");
		calendrier("DateF");
  		//$('#formRes').load('trace.php?aff');
			
			$('#preload').dialog({
					autoOpen		:	false,
					width			:	300,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Info Client'
			});
			$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	1100,
					height			:	600,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'<?php echo $trad['label']['InfoClient']; ?>'
			});
			$('#boxImage').dialog({
					autoOpen		:	false,
					width			:	1100,
					height			:	600,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'<?php echo $trad['label']['image']; ?>'});
				$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	400,
					height			:	380,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Ajout / Modification d\'une affectation',
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

	function filtrer(){	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'trace.php?rech',clearForm:false});
		
		return false;	
	}	
	function test(){
		$('#loading').html('<center><?php echo $trad['map']['messageChargementMap'] ; ?><br/><br/><img src="images/loading.gif" /></center>').dialog('open');
		alert("loading");	
		$('#loading').dialog('close');
	}
function rechercher(){	
$('#preload').html('<center><?php echo $trad['map']['messageChargementMap'] ; ?><br/><br/><img src="images/loading.gif" /></center>').dialog('open');
	//$('#loading').html('<center>Merci de patienter quelques instants<br/><br/><img src="images/loading.gif" /></center>').dialog('open');
	$('#formRechF').ajaxSubmit({target:'#formRes',url:'trace.php?rech'})
		clearForm('formRechF',0);

	}

function ajouter(){
		$('#act').attr('value','add');
		var url='trace.php?add';	
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='trace.php?mod&ID='+id;
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}
/*Designation: "required",
                                                Colisage: "required",
												Codeabarre : "required",
												Pa:"required",
												Unite:"required",
												Fournisseur:"required",
												Famille:"required",
												Tva:"required"*/
function terminer(){
	var form="";
	var act = $('#act').attr('value');
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: { 
                                                Vehicule: "required",
												Vendeur:"required",
												'Departement[]':"required"
                                          }     });	
	var test=$(form).valid();
	verifSelect2('Vehicule');
	verifSelect2('Vendeur');
	verifSelect2('Departement');		
		if(test==true){		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'trace.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'trace.php?goAdd',
														method			:	'post'
													}); 
														//alert('add');
												
											}
		
					}
				})
		}
	}	
	
		function verifSelect(NomSelect){
		//test Ville
		//alert(NomSelect);
		var Ville=$('select[id='+NomSelect).attr('class'); 
				if (Ville.indexOf("error") < 0)
				{$('#'+NomSelect).removeClass('erroer');	
					$('div.'+NomSelect+' button').css("border", "1px solid #ccc").css("background","#fff");
				}
				else {
				
					$('div.'+NomSelect+' button').css("border", "none").css("background","#FFECFF");
					$('.'+NomSelect).addClass('erroer');
				}
		
		
		
	}
	
/*	var map;  
var geocoder;
var lat=null;var longi=null;


function initMap(DivId) {//alert('ffff');
  //$('#map').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>');
  //Center = new google.maps.LatLng(lat,longi); 

 //geocoder = new google.maps.Geocoder();
 map = new google.maps.Map(document.getElementById(DivId), {          
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
		 // alert(pos.lat);
	   map.setCenter(pos);    
	   marker3.setPosition(pos);  

		$("#lat").val(pos.lat);
		$("#lng").val(pos.lng);
		//alert("init"+$("#lat").val());
	}
	
	$.geolocation.get({success:watchMyPosition}); 
 


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
 }
*/
var MY_MAPTYPE_ID = 'custom_style';

function initialize(MapPoints) {

    if (jQuery('#map').length > 0) {

        var locations = jQuery.parseJSON(MapPoints);

        window.map = new google.maps.Map(document.getElementById('map'), {
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        });

        var infowindow = new google.maps.InfoWindow();
        var flightPlanCoordinates = [];
        var bounds = new google.maps.LatLngBounds();

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i].address.lat, locations[i].address.lng),
                map: map
            });
            flightPlanCoordinates.push(marker.getPosition());
            bounds.extend(marker.position);

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infowindow.setContent(locations[i]['title']);
                    infowindow.open(map, marker);
                }
            })(marker, i));
            }

            map.fitBounds(bounds);

            var flightPath = new google.maps.Polyline({
                map: map,
                path: flightPlanCoordinates,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

        }
    } 
</script>



<?php
include("footer.php");
?>
 <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4<?php echo ($_SESSION['lang'] == 'ar' ) ? '&language=ar' : '&language=en'; ?>"> </script>
<?php
include("php.fonctions.php");
require_once('connexion.php');
include("frontend2/fonctionCalcule.php");
session_start();
include("lang.php");

$IdDepot=$_SESSION['IdDepot'];


if (isset($_GET['goAddRetour'])){
//parcourir($_POST['PriceUnite']);return;
//parcourir($_POST);return;
$DateR=date("Y-m-d H:i:s");
$DateR = date_create(date("Y-m-d"));
$HeureR=date("H:i:s");
	//$Date=date_create($DateR);
$error="";
$QteImp="";
$test="";
$TotalPriceArti=0;$TotalFacIm=0;
$nbrRef=0;
$nbrBoite=0;
$nbrPiece=0;
$Imprime="";$enteteFile ="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//----------------------modifier qte chargé detailfacture --------------------------//
//parcourir($_POST['idDetail']);return;
$TotalFac=0;
$reqS="  type like ? ";
$paramsFonc= array('Entree');
$RefEntree= "NE".Increment_Chaine_F("Reference","mouvements","idMouvement",$conn,$reqS,$paramsFonc);

$reqInser1 = "INSERT INTO mouvements ([reference] ,[idOperateur]  ,[fournisseur] ,
				[livreur] ,[date],[heure],[idDepot],[type],IdFacture) values 	(?,?,?,?,?,?,?,?,?)";
$params1= array(
				$RefEntree,
				1,
				'',
				addslashes(mb_strtolower(securite_bdd("Avoir"), 'UTF-8'))
				,$DateR,$HeureR,$IdDepot,'Avoir',$_GET['idFacture']
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout fiche entree avoir ".$errors[0]['message'] . " <br/> ";
}

//---------------------------IDFiche--------------------------------//
$sql = "SELECT max(idMouvement) as IdFiche FROM mouvements ";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdFiche entree: ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFiche = sqlsrv_get_field( $stmt2, 0);
$PriceQteRetour=0;$PricePcs=0;
 for( $i= 0 ; $i < count($_POST['idArticle']) ; $i++ )
{		

	////////////////////////////////////////////// recuperer tarif article en pcs////////////
		if($_POST["PricePcs"][$i]!=0) $PricePcs=$_POST["PricePcs"][$i];
		else  $PricePcs=intval($_POST["PriceBox"][$i])/intval($_POST["Colisage"][$i]);
		
		$nbrRef+=1;
	
	$QteRetourBoxEnpcs=BoxToPcs($_POST["qtRetourBoxs"][$i],$_POST["Colisage"][$i]);
	$totalPcsRetour=$QteRetourBoxEnpcs+intval($_POST["qtRetourPcs"][$i]);
	$TabQteRetour=array();
	
	$TabQteRetour=PcsToBox($totalPcsRetour,$_POST["Colisage"][$i]);
	$QteRetourBox=$TabQteRetour[0];
	$QteRetourPcs=$TabQteRetour[1];
	
	//	echo "Qte Retour en Box $QteRetourBox ,QteRetour Pcs  $QteRetourPcs ,";return;
		
	$QteChargeBoxEnpcs=BoxToPcs($_POST["QteChargeeBoxs"][$i],$_POST["Colisage"][$i]);
	$totalPcsCmde=$QteChargeBoxEnpcs+intval($_POST["QteChargeePcs"][$i]);
	
	//qte  pcs reste en commande après l'avoir
	$QteReelCmd=$totalPcsCmde-$totalPcsRetour;
	
	
	// calculer la qte retourner en box en modifiant la qte pcs en bx +la qte box saisie	
	$QteRestPcsToBox=array();
	$QteRestPcsToBox=PcsToBox($QteReelCmd,$_POST["Colisage"][$i]);
	$QteRBox=$QteRestPcsToBox[0];
	$QteRPcs=$QteRestPcsToBox[1];
	//echo "QteRest en Box $QteRBox ,QteRest Pcs  $QteRPcs ,";return;
	// $QteRPcsToBox[1] c'est le reste du pcs après la convertion en box  en l'ajoutant à la qte pcs saisie
	//$QteRPcs=$QteRPcsToBox[1]+intval($_POST["qtRetourPcs"][$i]);
	
		
			$reqUp ="update  avoir_client  set EtatAvoir=1 , idSupperviseur=".$_SESSION['IdVendeur']."
					where idRetour=".$_GET['IdAvoir'];
						$stmt1 = sqlsrv_query( $conn, $reqUp, array() );
						if( $stmt1 === false ) {
							$errors = sqlsrv_errors();
							$error.="Erreur : modification  etat avoir_client ".$errors[0]['message'] . " <br/> ";
							
						}
						
				$reqUp ="update  detailavoirs  set QteRetour=".$totalPcsRetour."
							where  idRetour=".$_GET['IdAvoir']." and idArticle=".$_POST["idArticle"][$i];
						$stmt1 = sqlsrv_query( $conn, $reqUp, array() );
						if( $stmt1 === false ) {
							$errors = sqlsrv_errors();
							$error.="Erreur : modification  detail retour".$errors[0]['message'] . " <br/> ";							
						}
		//////////////////////////////////////////////////////////////////////////////////////////////
							// recuperer prix achat article
				$sql = "SELECT pa FROM detailMouvements dm
							INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
							WHERE dm.idArticle=".$_POST["idArticle"][$i]."  and m.type='Entree'
							 GROUP BY dm.idArticle,pa";
				$stmt2 = sqlsrv_query( $conn, $sql );
				if( $stmt2 === false ) {
					$error.="Erreur recupération prix achat article: ".sqlsrv_errors() . " <br/> ";
				}
				sqlsrv_fetch($stmt2) ;
			//	echo $IdFiche;return;
				$PrixAchat = sqlsrv_get_field( $stmt2, 0);


		//////////////////////////////////// retourner les articles au stock//////////////////////////////////////////////////////////
			if($QteRetourBox!=0){// $TabQteRetour en box 
				$reqInser2 = "INSERT INTO  detailmouvements([idArticle],[qte],[pa],[idDepot],idMouvement,UniteVente ) values (?,?,?,?,?,?)";
			$params2= array($_POST["idArticle"][$i],$QteRetourBox,$PrixAchat,$IdDepot,$IdFiche,'Colisage') ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {
				$error.="Erreur : Erreur : ajout retour article detailmouvements box".$errors[0]['message']. " <br/> ";
				break ;
				}
				$PriceQteRetour+=floatval($QteRetourBox)*floatval($_POST["Colisage"][$i])*$PricePcs;
				$TotalPriceBoxRet=floatval($QteRetourBox)*floatval($_POST["Colisage"][$i])*$PricePcs;
			}
			if($QteRetourPcs!=0){
					$reqInser2 = "INSERT INTO  detailmouvements([idArticle],[qte],[pa],[idDepot],idMouvement,UniteVente ) values (?,?,?,?,?,?)";
				$params2= array($_POST["idArticle"][$i],$QteRetourPcs,$PrixAchat,$IdDepot,$IdFiche,'Pièce') ;
				$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
					if( $stmt3 === false ) {
						$error.="Erreur : Erreur : ajout retour article detailmouvements piece".$errors[0]['message']. " <br/> ";
						break ;
					}
					$PriceQteRetour+=floatval($QteRetourPcs)*$PricePcs;
					$TotalPricePcsRet=floatval($QteRetourPcs)*$PricePcs;
			}
			
		////////////////////////////////////////////////////modification des factures//////////////////////////////////////////////	//////	
			$sql = "						
						SELECT idDetailFacture from detailFactures d
						where idFacture=? and idArticle=?  and UniteVente='Colisage'";						
				 $params = array($_GET['idFacture'],$_POST["idArticle"][$i]);//
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
					 $resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
					$nRes = sqlsrv_num_rows($resAff);//echo "resultat " . $nRes;
						sqlsrv_fetch($resAff) ;
				//echo $nRes;

				if($nRes!=0){
					

					// verifier si une commande faite en box en modifier la qte s'il ya une retour en box
					if($QteRBox!=0)
						{
							$TotalPriceArti=floatval($QteRBox)*floatval($_POST["PriceBox"][$i]);
							//echo "total price box cmdé $TotalPriceArti";return;
						
						$idDetailFacture = sqlsrv_get_field( $resAff, 0);	
						$reqDel ="update  detailFactures set  qte=".$QteRBox.",ttc=".$TotalPriceArti." where idDetailFacture= $idDetailFacture";
						$stmt1 = sqlsrv_query( $conn, $reqDel, array() );
						if( $stmt1 === false ) {
							$errors = sqlsrv_errors();
							$error.="Erreur : modifier qte box cmdée".$errors[0]['message'] . " <br/> ";
							
						}	
						}
						else {
							$idDetailFacture = sqlsrv_get_field( $resAff, 0);	
							$reqDel ="delete from detailFactures where idDetailFacture=".$idDetailFacture;
							$stmt1 = sqlsrv_query( $conn, $reqDel, array() );
							if( $stmt1 === false ) {
								$errors = sqlsrv_errors();
								$error.="Erreur : Suppression retour article box ".$errors[0]['message'] . " <br/> ";
								
						}	
						}
				
				}else if(($nRes==0)&&($QteRBox!=0))	{
					$TotalPriceArti=floatval($QteRBox)*$PricePcs*intval($_POST["Colisage"][$i]);
					$reqInser2 = "INSERT INTO  detailfactures(idFacture,[idArticle],[qte],tarif,idDepot,idFiche,ttc,UniteVente) 
										values (?,?,?,?,?,?,?,?)";
								$params2= array(
										$_GET['idFacture'],
										$_POST["idArticle"][$i],
										floatval(str_replace(" ","",$QteRBox)),//é/ qte cmd est stockée par unité (box,palette piece)
										$PricePcs*intval($_POST["Colisage"][$i]),// prix de vente par unite (palette ou box/colisage)
										$IdDepot,
										$_POST["IdFicheTarif"][$i],
										$TotalPriceArti,
										'Colisage' 
								
								) ;
								$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
								if( $stmt3 === false ) {

									$errors = sqlsrv_errors();
									$error.="Erreur : Ajout detail facture en box ".$errors[0]['message'] . " <br/> ";
									break ;
								}
				}					
			
			
			// verifier si une commande faite en piece en modifier la qte s'il ya une retour en box		
			$sql = "						
						SELECT idDetailFacture from detailFactures d
						where idFacture=? and idArticle=?  and UniteVente='Pièce'";						
				 $params = array($_GET['idFacture'],$_POST["idArticle"][$i]);//
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
					 $resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
					$nRes = sqlsrv_num_rows($resAff);//echo "resultat " . $nRes;
				//echo $nRes;
				sqlsrv_fetch($resAff) ;
				if($nRes!=0){
					// verifier si une commande faite en piece en modifier la qte 
					if($QteRPcs!=0)
						{
							$TotalPriceArti=floatval($QteRPcs)*floatval($_POST["PricePcs"][$i]);
							
						
						$idDetailFacture = sqlsrv_get_field( $resAff, 0);	
						$reqDel ="update  detailFactures set  qte=".$QteRPcs.",ttc=".$TotalPriceArti." where idDetailFacture= $idDetailFacture";
						$stmt1 = sqlsrv_query( $conn, $reqDel, array() );
						if( $stmt1 === false ) {
							$errors = sqlsrv_errors();
							$error.="Erreur : modifier qte pcs cmdée".$errors[0]['message'] . " <br/> ";
							
						}	
						}
						else {
							$idDetailFacture = sqlsrv_get_field( $resAff, 0);	
							$reqDel ="delete from detailFactures where idDetailFacture=".$idDetailFacture;
							$stmt1 = sqlsrv_query( $conn, $reqDel, array() );
							if( $stmt1 === false ) {
								$errors = sqlsrv_errors();
								$error.="Erreur : Suppression retour article pcs ".$errors[0]['message'] . " <br/> ";
								
						}	
						}
				
				}else if(($nRes==0)&&($QteRPcs!=0))	{
					$TotalPriceArti=floatval($QteRPcs)*$PricePcs;
					//echo "total price pcs cmdé $QteRPcs  et ".$TotalPriceArti;return;
					$reqInser2 = "INSERT INTO  detailfactures(idFacture,[idArticle],[qte],tarif,idDepot,idFiche,ttc,UniteVente) 
										values (?,?,?,?,?,?,?,?)";
								$params2= array(
										$_GET['idFacture'],
										$_POST["idArticle"][$i],
										//$contenu['Qte'],
										floatval(str_replace(" ","",$QteRPcs)),//é/ qte cmd est stockée par unité (box,palette piece)
										$PricePcs,// prix de vente par unite (palette ou box/colisage)
										$IdDepot,
										$_POST["IdFicheTarif"][$i],
										$TotalPriceArti,
										'Pièce' 
								
								) ;
								$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
								if( $stmt3 === false ) {

									$errors = sqlsrv_errors();
									$error.="Erreur : Ajout detail facture en Pcs ".$errors[0]['message'] . " <br/> ";
									break ;
								}
				}		
			
	$TotalFacIm+=$PriceQteRetour;

	if($QteRetourBox!=0){
			$Imprime.=ucwords( $_POST["NomArt"][$i]).PHP_EOL;
	$Imprime.= $trad['label']['Code']." : ".$_POST["Reference"][$i].PHP_EOL;
		$Imprime.=str_pad($QteRetourBox, 5, ' ', STR_PAD_LEFT)." :".str_pad(number_format($PricePcs, 2, '.', ' '), 8, ' ', STR_PAD_LEFT)."  ".str_pad(number_format($TotalPriceBoxRet, 2, '.', ' '), 14, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
	}
	if($QteRetourPcs!=0){
			$Imprime.=ucwords( $_POST["NomArt"][$i]).PHP_EOL;
	$Imprime.= $trad['label']['Code']." : ".$_POST["Reference"][$i].PHP_EOL;
		$Imprime.=str_pad($QteRetourPcs, 5, ' ', STR_PAD_LEFT)." :".str_pad(number_format($PricePcs, 2, '.', ' '), 8, ' ', STR_PAD_LEFT)."  ".str_pad(number_format($TotalPricePcsRet, 2, '.', ' '), 14, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
	}
	$Imprime.=PHP_EOL;
	

}
//	echo "Total retour $TotalFacIm";return;
//----------------------update total facture --------------------------//
		$sql = "SELECT sum(qte * tarif) AS TotalFac FROM detailFactures WHERE   IdFacture=?";
				 $params = array($_GET['idFacture']);//
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
					 $resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
					 	if( $sql === false ) {
							$errors = sqlsrv_errors();
							$error.="Erreur : Recupération total fac ".$errors[0]['message'] . " <br/> ";
							
						}
						sqlsrv_fetch($resAff) ;
							$TotalF = sqlsrv_get_field( $resAff, 0);	
				
			//echo "Total TotalF $TotalF";return;	
		 $reqInserUp = "update factures set TotalTTC=".tofloat($TotalF)." where IdFacture = ? ";
					$paramsUp= array($_GET['idFacture']) ;
					$stmtUp = sqlsrv_query( $conn, $reqInserUp, $paramsUp );
					if( $stmtUp === false ) {
						$errors = sqlsrv_errors();
						$error.="Erreur : modif ttc facture".$errors[0]['message'] . " <br/> ";
						//break ;
					}
//---------------------------Recuperer info impression--------------------------------//
$sql = " SELECT f.IdFacture IdFacture,NumFacture as NumFacture ,v.nom+ ' '+v.prenom Vendeur,c.CodeClient Client, c.intitule IntituleClt,
 v2.Designation Ville,c.Tel,
v.codeVendeur CodeVdr,f.totalTTC,av.NumAvoir
	FROM 
	factures f  INNER JOIN clients c  ON c.IdClient=f.idClient
	inner join avoir_client av on av.IdFacture=f.idFacture
	INNER JOIN vendeurs v ON v.idVendeur = f.idVendeur
	inner join depots d on d.idDepot=c.idDepot
	inner join villes v2 on v2.idVille=d.idVille
	 WHERE f.IdFacture=".$_GET['idFacture']
;

$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur récupération idfacture : ".sqlsrv_errors() . " <br/> ";
}
	 while( $row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {
	$IdFacture = $row["IdFacture"];
	$NumFacture = $row["NumFacture"];
	$NumAvoir = $row["NumAvoir"];
	$Client=$row["Client"];
    $NomVdr=$row["Vendeur"];
	$CodeVdr=$row["CodeVdr"];
	$IntituleClt=$row["IntituleClt"];
	$Ville=$row["Ville"];
     $Tel=$row["Tel"];
	}
$Imprime.=PHP_EOL;
$Imprime.=$trad['label']['TotalRetour']." ".str_pad(number_format($TotalFacIm, 2, '.', ' '), 17, ' ', STR_PAD_LEFT)." ".$trad['label']['riyal'] ." ".PHP_EOL;
$Imprime.=$trad['label']['NbrRef']." ".str_pad($nbrRef, 17, ' ', STR_PAD_LEFT).PHP_EOL;
if(($nbrBoite!=0)&&($nbrPiece!=0)){
	$Imprime.=$trad['label']['NbrBoite']."/".$trad['label']['NbrPiece']." : ".str_pad($nbrBoite, 17, ' ', STR_PAD_LEFT)."/".$nbrPiece.PHP_EOL;

}else if(($nbrBoite!=0)&&($nbrPiece==0)){
	$Imprime.=$trad['label']['Box']." : ".str_pad($nbrBoite, 23, ' ', STR_PAD_LEFT).PHP_EOL;
}else if(($nbrBoite==0)&&($nbrPiece!=0)){
	$Imprime.=$trad['label']['NbrPiece']." : ".str_pad($nbrPiece, 25, ' ', STR_PAD_LEFT).PHP_EOL;
}

$Imprime.="----------------------------------------".PHP_EOL;			
		
if( ($error=="" ) ) {	
	sqlsrv_commit( $conn );
	$enteteFile.="VENDEUR : ".strtoupper($NomVdr).PHP_EOL ;
	$enteteFile.="DATE ET HEURE : ".date_format($DateR, 'd/m/Y')." ".date_format(date_create($HeureR), 'H:i').PHP_EOL;
	$enteteFile.=$NumAvoir.PHP_EOL ;
	$enteteFile.=PHP_EOL ;
	$enteteFile.="CLIENT : ".strtoupper($Client)." - ".strtoupper($IntituleClt).PHP_EOL ;
	if( $Tel!="") $enteteFile.="Tel : ".$Tel.PHP_EOL ;
	$enteteFile.="VILLE : ".strtoupper($Ville).PHP_EOL ;
	$enteteFile.=PHP_EOL ;
	$enteteFile.=PHP_EOL;
	$Imprime=$enteteFile.$Imprime;
	//$name="Livraison ".date('d-m-Y H-i');
	$name="Avoir ".date('d-m-Y H-i');
	$fp = fopen ("frontend2/bon_cmd/".$name.".txt", "w");
	fputs ($fp, $Imprime);
	fclose ($fp);

	$dir="frontend2/bon_cmd/".$name.".txt";
	$filename=$name.".txt";
	$name= urlencode ($name);
     ?>
		<script type="text/javascript"> 
		    var url="frontend2/download.php?fileName=<?php echo $name;?>";	
	    	document.location.href=url;
			alert('<?php echo $trad['msg']['messageAjoutSucces'];?>');
			dialog.dialog('close');
			rechercher();			
		</script>	
		
<?php
} 
else 
{
     sqlsrv_rollback( $conn );
     echo $error;
}
exit;
}
if (isset($_GET['getCommande'])){
//echo $_GET['numCmd'];
?>
<div id="result"></div>
<?php 

/*********** selectionner la cmd d'un vendeur**********************/
$sql = "
			
	SELECT 	 f.idVendeur,f.idFacture,d.idArticle as IdArticle,a.designation as NomArt,d.idFiche IdFicheTarif,
					da.QteRetour,
					c.colisagee Colisage,a.Reference,av.IdRetour IdAvoir,
		sum (CASE WHEN d.UniteVente= 'Pièce' THEN Qte ELSE Qte*c.Colisagee END) AS QteFac	, 
			Max (CASE WHEN d.UniteVente= 'Pièce' THEN tarif ELSE 0 END) AS PricePcs	 ,
			Max (CASE WHEN d.UniteVente= 'Colisage' THEN tarif ELSE 0 END) AS PriceBox			
			from avoir_client av 	
			inner join detailavoirs da on av.idRetour=da.idRetour
			inner join factures f on  av.idFacture=f.idFacture 		
			INNER   join detailFactures d on av.idFacture=d.idFacture AND  d.idFacture=f.IdFacture	  
			AND da.idArticle=d.idArticle
			inner join articles a on a.idArticle=da.idArticle
			inner join colisages c on c.idArticle=da.idArticle
			WHERE av.idFacture=?  group by  f.idVendeur,f.idFacture,d.idArticle ,a.designation ,
					da.QteRetour,
					c.colisagee ,a.Reference ,av.IdRetour,d.idFiche
			";
	
	 $params = array($_GET['numFac']);//
//echo $sql. ' ' .$_GET['numCmd'];return;
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	 $resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
	$nRes = sqlsrv_num_rows($resAff);//echo "resultat " . $nRes;
//echo $nRes;
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
?>

<DIV class="ListeCmd">
	<div class="enteteL" >
			<div  class="divArticleL"  style="width:25%" ><?php echo $trad['label']['Article'];?> </div>
			<div  class=" divQteL" style="width:10%"><?php echo $trad['label']['Colisage'];?>  </div>
			<div  class="divTTC" style="width:25%; text-align:center" ><?php echo $trad['label']['QteCmd'];?>  </div>	
			<div  class="divArticleL" style="width:40%" ><?php echo $trad['label']['QteRetour'];?>  </div>				
	</div>
	<div style="height:380px;overflow:scroll;" ><!--height:585px;-->
	<form id="formAdd" method="post" name="formAdd"> 

<?php 
$k=0;$i=0;
$idVnd="";$idCmd="";$IdAvoir="";$UniteVente="";
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$idCmd=$row['idFacture'];
$IdAvoir=$row['IdAvoir'];
$k++;
if($k%2 == 0) $c = "pairL";
else $c="impairL";
			//$qteDispo=number_format($qteDispo,0," "," ")
	//		if($row['UniteVente']=="Colisage") $UniteVente="Boite";else $UniteVente="Pièce";
	$QteChargee=array();$QteRetour=array();
	$QteChargee=PcsToBox($row['QteFac'],$row['Colisage']);	
	$QteRetour=PcsToBox($row['QteRetour'],$row['Colisage']);	
?>
<input type="hidden" value="<?php  echo $row['IdFicheTarif']; ?>" id="IdClient" name="IdFicheTarif[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['NomArt']; ?>" id="IdClient" name="NomArt[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['Reference']; ?>" id="IdClient" name="Reference[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['PricePcs']; ?>" name="PricePcs[<?php echo $i; ?>]" id="PricePcs<?php echo $i; ?>"/>
<input type="hidden" value="<?php  echo $row['PriceBox']; ?>" name="PriceBox[<?php echo $i; ?>]" id="PriceBox<?php echo $i; ?>"/>
<input type="hidden" name="QteChargeeBoxs[<?php echo $i; ?>]" value="<?php  echo $QteChargee[0] ;?>" id="QteChargeeBox<?php echo $i; ?>" >
<input type="hidden" name="QteChargeePcs[<?php echo $i; ?>]" value="<?php  echo  $QteChargee[1] ?>" id="QteChargeePcs<?php echo $i; ?>" >
<input type="hidden" value="<?php  echo $idCmd ?>" id="idCmd" name="idCmd">
<input type="hidden" value="<?php echo $row['Colisage']; ?>" name="Colisage[<?php echo $i; ?>]" id="Colisage<?php echo $i; ?>"/>
<input type="hidden" value="<?php  echo $row['IdArticle']; ?>" name="idArticle[<?php echo $i; ?>]">
	<div  class="<?php echo $c; ?>" style="padding-top:10px;border:1px solid #cccc">
			<div align="left" class="divArticleLigne" style="width:25%"  ><?php echo $row['NomArt'];?> </div>
					<div align="left" class="divQteL" style="width:10%"  ><?php echo $row['Colisage'];?> </div>
			<div  align="right" class="divQteL" style="width:25%" ><?php				
			echo $QteChargee[0]." ".$trad['label']['Boxs']." ".$QteChargee[1]." ".$trad['label']['NbrPiece'];?>  </div>			
			<div  align="right" class="divArticleLigne" style="width:30%" >	
				<input class="numberOnly" type="text" style=" text-align:right" value="<?php  echo $QteRetour[0] ;?>" 
				size="5" name="qtRetourBoxs[]" onkeypress="return isEntier(event) " />&nbsp;<?php echo $trad['label']['Boxs'];?>
				
					<input class="numberOnly" type="text" style=" text-align:right" value="<?php  echo $QteRetour[1] ;?>" 
				size="5" name="qtRetourPcs[]" onkeypress="return isEntier(event) " />&nbsp;<?php echo $trad['label']['NbrPiece'];?>
				
			</div>	
		</div>


<?php 
	$i++;
 }  ?>

</form>
	</div>
</div>

<div class="btnV" style="margin:20px 10px 0 0; text-align:right;">
	<input type="button" value="<?php echo $trad['button']['Enregistrer'];?>" class="btnSite" onclick="Valider(<?php echo $idCmd; ?>,<?php echo $IdAvoir; ?>)"/>
	<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btnSite" onclick="Fermer('boxArticle')"/>
</div>
<?php 

}
?>
<script language="javascript" type="text/javascript">

function Valider(IdCmd,IdAvoir){

	/******Control Qte Retour********/
	index=0;
	var test=true;
	
	//QteAchargé obligatoire
	/*$("[name^=qtRetour]").each(function () {
		if($(this).val() == "")
		{
		  $(this).css('border', '1px solid red');
		  test=false;
		 
		}
		else
		{
		  $(this).css('border', '1px solid black');
		}
		});*/
		
		if (test == false)
		return;
		
		//QteAchargé ne doit pas dépasser Qte Disponible
		$("[name^=qtRetour]").each(function () {
			//	alert($(this).val());//--------------------------------------Retour
				//alert($("input[name=QteChargee"+index+"]").val());//---------------QteChargee
			
				if(parseInt($(this).val()) > parseInt($("input[id=QteChargee"+index+"]").val())){	
					jAlert("<?php echo $trad['msg']['VerifQteRetour'];?>","<?php echo $trad['titre']['Alert'];?>");
					$(this).css('color', 'red'); //$(this).focus();
					test=false;
				   }
				   else
				   {
					$(this).css('color', 'black');  
				   }
				index++;
    });	
	//alert(test);
			if(test==true) {		
			 jConfirm('<?php echo $trad['msg']['terminerOperation'];?>', "<?php echo $trad['titre']['Alert'];?>", function(r) {
					if(r)	{
							$('#formAdd').ajaxSubmit({
									target			:	'#result',
									url				:	'factures.php?goAddRetour&idFacture='+IdCmd+'&&IdAvoir='+IdAvoir,
									method			:	'post'
							}); 
							return false;
			           }			
		})
	}
}
</script>
<?php

exit;

}  ?>
<?php
if (isset($_GET['aff'])){
?>
<style>
.divArticleL{width:400px;}
.livre{
background: #fcfda2;
	overflow: auto; display: flex;border-top:0;
}
.precommande
{
background: #e5e5e5; 
overflow: auto; display: flex;border-top:0;
}

</style>
<script>
function getcommande(numFac){
	var url='factures.php?getCommande&&numFac='+numFac;	
		dialog.html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	
}
</script>
<?php
/*********** selectionner la cmd d'un vendeur**********************/

$where=" where (EtatCmd=2) ";
if ((isset($_POST['IdDepot'])) && ($_POST['IdDepot']!=""))
{
	$where.=" and f.iddepot=".$_POST['IdDepot'];
}
if ((isset($_POST['NumFac'])) && ($_POST['NumFac']!=""))
{
	$where.=" and NumFacture like '%".$_POST['NumFac']."%'";
}
		if(isset($_POST['DateD']) && isset($_POST['DateF'])  )
		{
	
			if($_POST['DateD'] == $_POST['DateF'])
			{ 	
				 $where.= " and  cast(f.date AS date) = convert(date, '".($_POST['DateD'])."' ,105)";					 
			}
			else
			{
				 $where.= " and  cast(f.date AS date)  between  convert(date, '".($_POST['DateD'])."',105) and convert(date, '".($_POST['DateF'])."' ,105)";
				
			}
		}
$sql = "
		SELECT a.IdFacture IdFacture,NumFacture numCommande,v.nom+ ' ' + v.prenom AS nom,a.[DateR] as DateR ,d.Designation
				,f.idClient IdClient,f.idVendeur,f.TotalTTC,c.intitule FROM
				avoir_client a 
				right join factures f on f.idFacture=a.idFacture
			INNER JOIN vendeurs v ON f.idVendeur=v.idVendeur	
			inner join depots d on d.iddepot=a.IdDepot	
		inner join clients c on c.idClient=f.idclient			
		".$where." group by 
			 a.IdFacture ,NumFacture ,v.nom,v.prenom ,a.DateR 
				,f.idClient ,f.idVendeur,d.Designation,f.TotalTTC,c.intitule
			order by a.DateR desc";
	
	 $params = array($_SESSION['IdDepot']);
//echo $sql;return;
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	 $resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
	$nRes = sqlsrv_num_rows($resAff);//echo "resultat " . $nRes;
//echo $nRes;
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
?>
<!--div class="title"><?php echo $trad['label']['listePreCommande'];?></div-->
<DIV class="ListeCmd" style="">
	<table width="100%" border="0">
      <tr class="entete">
         <td width="10%"  ><?php echo $trad['label']['Depot'];?></td>
	   	<td width="10%"  style="display:none"><?php echo $trad['label']['codeVnd'];?></td>
		<td width="10%"><?php echo $trad['label']['Vendeur'];?></td>
		<td width="10%"><?php echo $trad['map']['intitule'];?></td>
		<td width="10%"><?php echo $trad['label']['Facture'];?></td>
		<td width="10%"><?php echo $trad['label']['TotalTTC'] ;?></td>
        <td width="15%"><?php echo $trad['label']['DateRetour'];?></td>	
		
		  <td width="15%"><?php echo $trad['label']['Action'];?></td>	
  </tr>
  

<?php 
$k=0;
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$k++;
	if($k%2 == 0) $c = "pair";
			else $c="impair";
?>
	<tr class="pair" style="height:50px;font-size:14px;cursor:pointer"
	>
			<td  class=""  ><?php echo $row['Designation'];?> </td>
				<td  class="" ><?php echo $row['nom'];?>  </td>	
								<td  class="" ><?php echo $row['intitule'];?>  </td>	
			<td  class=""  ><?php echo $row['numCommande'];?> </td>
			<td  class="" ><?php echo $row['TotalTTC'];?>  </td>	
			<td  class=""    ><?php 	$newdate = date('d/m/Y', strtotime($row['DateR']));echo $newdate; ?> </td>	
				  <td  align="center"><input 	onclick="getcommande('<?php  echo $row['IdFacture'];?>')" type="button" value="<?php echo $trad['button']['enregistrer'];?>" Class="detail tool"/></td>	
	</tr>
<?php }  ?>
   </table>
</div>
<?php 

exit;
}

}
//}
?>
<?php include("header.php"); ?>



	<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['label']['ValidationRetour'];?> </div>
<div style="clear:both;"></div>
<form id="formRechF" method="post"  name="formRechF"> 
		<div id="formRech" style="">	
			<table width="800" border="0" cellpadding="5" align="center" >
			<tr>			
				<td width="22% " colspan="4" align="center">	<strong><?php echo $trad['label']['date']; ?> :</strong>
				<?php echo $trad['label']['de']; ?> 
<input class="formTop" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" maxlength="10" 
onChange="verifier_date(this);" value="<?php echo '01/12/2017'; ?>"/>	<?php echo $trad['label']['a']; ?> 
<input name="DATED" type="hidden" value=""/>	
<input class="formTop" g="date" id="DateF" tabindex="2" name="DateF" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	
<input name="DATED" type="hidden" value=""/>	
				</td>
				  <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button" onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?> " />
					  <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider']; ?>" class="bouton32" action="effacer" title="<?php echo $trad['label']['vider']; ?>"/></span><br/></td>
			</tr>
				  <tr>
				    <td width="25%"  align="right" >
						<strong><?php echo $trad['label']['Depot']; ?> :</strong>
				  </td> <td width="25%"  >
						<select  name="IdDepot" id="IdDepot"  multiple="multiple" tabindex="3" class="Select Depot" style="display:visible;width:220px;">
					<option value=""><?php echo $trad['label']['selectTous'];?></option>
                         <?php $sql = "select idDepot, Designation from depots ";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idDepot'] ?>"><?php echo $donnees['Designation']?></option>
                         <?php
                          }
                         ?>
					</select>
				  </td>
					<td width="23%" valign="middle">
					<div class="etiqForm" id="SYMBT" ><strong><?php echo $trad['label']['Facture']; ?>:</strong> </div>				</td>
					<td width="30%">
						<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
						<div align="<?php $_SESSION['align'] ; ?>">
							<input class="formTop"  name="NumFac" id="NumFac" type="text" size="25" />	
						</div>
					</td>
			
				
				</tr>			  
			 </table>
			 
		 </div>
	</form>
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>
<div id="formRes" style="overflow-y:scroll;min-height:400px;"></div><!--style="overflow-y:scroll;min-height:280px;"--> 

<?php
include("footer.php");
?>
<div id="boxArticle"></div>
<script language="javascript" type="text/javascript">
function Fermer(){
//	$("#boxArticle").dialog('close');
	dialog.dialog('close');
}
$('#IdDepot').multipleSelect({
	filter: true,placeholder:'<?php echo $trad['label']['selectDepot'] ;?>',single:true,maxHeight: 170,width:220
});
	
	  $("#IdDepot").multipleSelect("uncheckAll");
			var dialog=	$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	950,/*1260,*/
					height			:	575,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Détail facture'
			});
$(document).ready(function() {
		//$.validator.messages.required = '';
  		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('factures.php?aff');
		calendrier("DateD");
		calendrier("DateF");

			
});
function rechercher(){

	$('#formRechF').ajaxSubmit({target:'#formRes',url:'factures.php?aff'});
		patienter('formRes');
		clearForm('formRechF',0);
	//	clearForm('formRechF',0);
	}
function control(index)
{

 alert( "Handler for .blur() called." + $( ".textQte" ).attr("name") + index);

}

$('.numberOnly').on('keydown', function(e){//alert("here");
	
  if(this.selectionStart || this.selectionStart == 0){
	// selectionStart won't work in IE < 9
	
	var key = e.which;
	var prevDefault = true;
	
	var thouSep = " ";  // your seperator for thousands
	var deciSep = ".";  // your seperator for decimals
	var deciNumber = 2; // how many numbers after the comma
	
	var thouReg = new RegExp(thouSep,"g");
	var deciReg = new RegExp(deciSep,"g");
	
	function spaceCaretPos(val, cPos){
		/// get the right caret position without the spaces
		
		if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
		cPos = cPos-1;
		
		if(val.substring(0,cPos).indexOf(thouSep) >= 0){
		cPos = cPos - val.substring(0,cPos).match(thouReg).length;
		}
		
		return cPos;
	}
	
	function spaceFormat(val, pos){
		/// add spaces for thousands
		
		if(val.indexOf(deciSep) >= 0){
			var comPos = val.indexOf(deciSep);
			var int = val.substring(0,comPos);
			var dec = val.substring(comPos);
		} else{
			var int = val;
			var dec = "";
		}
		var ret = [val, pos];
		
		if(int.length > 3){
			
			var newInt = "";
			var spaceIndex = int.length;
			
			while(spaceIndex > 3){
				spaceIndex = spaceIndex - 3;
				newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
				if(pos > spaceIndex) pos++;
			}
			ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
		}
		return ret;
	}
	
	$(this).on('keyup', function(ev){
		
		if(ev.which == 8){
			// reformat the thousands after backspace keyup
			
			var value = this.value;
			var caretPos = this.selectionStart;
			
			caretPos = spaceCaretPos(value, caretPos);
			value = value.replace(thouReg, '');
			
			var newValues = spaceFormat(value, caretPos);
			this.value = newValues[0];
			this.selectionStart = newValues[1];
			this.selectionEnd   = newValues[1];
		}
	});
	
	if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
	   (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
		prevDefault = false;
	
	if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
		e.preventDefault();
		
		if(!e.altKey && !e.shiftKey && !e.ctrlKey){
		
			var value = this.value;
			if((key > 95 && key < 106)||(key > 47 && key < 58) ||
			  (deciNumber > 0 && (key == 110 || key == 188 || key == 190))){
				
				var keys = { // reformat the keyCode
				48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
				96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
				110: deciSep, 188: deciSep, 190: deciSep
				};
				
				var caretPos = this.selectionStart;
				var caretEnd = this.selectionEnd;
				
				if(caretPos != caretEnd) // remove selected text
				value = value.substring(0,caretPos) + value.substring(caretEnd);
				
				caretPos = spaceCaretPos(value, caretPos);
				
				value = value.replace(thouReg, '');
				
				var before = value.substring(0,caretPos);
				var after = value.substring(caretPos);
				var newPos = caretPos+1;
				
				if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
					if(before.indexOf(deciSep) >= 0){ newPos--; }
					before = before.replace(deciReg, '');
					after = after.replace(deciReg, '');
				}
				var newValue = before + keys[key] + after;
				
				if(newValue.substring(0,1) == deciSep){
					newValue = "0"+newValue;
					newPos++;
				}
				
				while(newValue.length > 1 && 
				  newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
					newValue = newValue.substring(1);
					newPos--;
				}
				
				if(newValue.indexOf(deciSep) >= 0){
					var newLength = newValue.indexOf(deciSep)+deciNumber+1;
					if(newValue.length > newLength){
					newValue = newValue.substring(0,newLength);
					}
				}
				
				newValues = spaceFormat(newValue, newPos);
				
				this.value = newValues[0];
				this.selectionStart = newValues[1];
				this.selectionEnd   = newValues[1];
			}
		}
	}
	
	$("#mt").on('blur', function(e){
		
		if(deciNumber > 0){
			var value = this.value;
			
			var noDec = "";
			for(var i = 0; i < deciNumber; i++)
			noDec += "0";
			
			if(value == "0"+deciSep+noDec)
			this.value = ""; //<-- put your default value here
			else
			if(value.length > 0){
				if(value.indexOf(deciSep) >= 0){
					var newLength = value.indexOf(deciSep)+deciNumber+1;
					if(value.length < newLength){
					while(value.length < newLength){ value = value+"0"; }
					this.value = value.substring(0,newLength);
					}
				}
				else this.value = value + deciSep + noDec;
			}
		}
	});
  }
});
</script>

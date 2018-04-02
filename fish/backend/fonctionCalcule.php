<?php
/*
session_start();
if(!isset($_SESSION['username'])){
header('Location: index.php');
exit();
}
*/
function qteDispoArticle($params,$conn,$Type)
{ 
	// recuperer qte entree stock en piece
/*	$sql = "SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
					as QteEntree FROM detailMouvements dm
			INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
			INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=? AND (m.type='entree' or m.type='Avoir') AND m.idDepot=?";
		*/
$sql = "SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
					as QteEntree FROM detailMouvements dm
			INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
			INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=? AND (m.type='entree' or m.type='Avoir') AND m.idDepot=?";
			
			

    $reponse=sqlsrv_query( $conn, $sql, $params, array( "Scrollable" => 'static' ) );	
				if( $reponse === false ) {
					$errors = sqlsrv_errors();
					$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
					return;
				}		
	$rowC = sqlsrv_fetch_array($reponse, SQLSRV_FETCH_ASSOC);
	$QteEntree=$rowC['QteEntree'];	

	// recuperer qte sortie stock en piece	 EtatSorti!=3 càd un sortie d'un article qui est annulé retour au stock   EtatSorti=1 cad vendeu 
	 if($Type=="reel"){
	$sql2 = "SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
				 as QteSortie FROM detailMouvements dm
				INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
				INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=? AND m.type='sortie' and EtatSotie!=3 and EtatSotie=1 AND m.idDepot=?";
	 }
		else {
			$sql2 = "SELECT isnull(sum(
						CASE 
							  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
							  WHEN  UniteVente='Pièce' THEN  (qte)
						END 
							),0)
						 as QteSortie FROM detailMouvements dm
						INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
						INNER JOIN colisages c  ON c.idArticle = dm.idArticle
					WHERE dm.idArticle=? AND m.type='sortie' AND m.idDepot=?";
			 }
			
    $reponse2=sqlsrv_query( $conn, $sql2, $params, array( "Scrollable" => 'static' ) );	
				if( $reponse2 === false ) {
					$errors = sqlsrv_errors();
					$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
					return;
				}		
	$rowD = sqlsrv_fetch_array($reponse2, SQLSRV_FETCH_ASSOC);
	$QteSortie=$rowD['QteSortie'];

	$QteDispo=tofloat($QteEntree)-tofloat($QteSortie);	
	return $QteDispo;
}
function creditClient($params3,$conn){
	$error;
	//Recuperer Credit du clt // etat=1 cad dernier credit
	$sql = "SELECT sum(Avance) Credit FROM Avance WHERE idClient=? AND idDepot=? AND ModePaiement='Credit' and Etat=1"; 
	$stmtR = sqlsrv_query( $conn, $sql,$params3 );
	if( $stmtR === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur recuperation Credit : ".$errors[0]['message'] . " <br/> ";
	}
	sqlsrv_fetch($stmtR) ;
$Credit = sqlsrv_get_field( $stmtR, 0);
//Recuperer avance du clt
	$sql = "SELECT sum(Avance) Avance FROM Avance WHERE idClient=? AND idDepot=? AND ModePaiement!='Credit' "; 
	$stmtR = sqlsrv_query( $conn, $sql,$params3 );
	if( $stmtR === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur recuperation Avance : ".$errors[0]['message'] . " <br/> ";
	}
	sqlsrv_fetch($stmtR) ;
	$AvanceClt = sqlsrv_get_field( $stmtR, 0);

	
//Recuperer Total des montants facture clt

	$sql = "SELECT sum(totalTTC) FROM factures WHERE idClient=? AND idDepot=? and EtatCmd=2 "; 
	$stmtR = sqlsrv_query( $conn, $sql,$params3 );
	if( $stmtR === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur recuperation Montant facture : ".$errors[0]['message'] . " <br/> ";
	}
	sqlsrv_fetch($stmtR) ;
	$TotalMtcFacture = sqlsrv_get_field( $stmtR, 0);
	
	// Total avance  clt
	//$TotalAvanceClt=floatval($AvanceClt)-floatval($Credit);
	//if(floatval($TotalAvanceClt)< floatval($TotalMtcFacture)) return $Credit;
	//else return 1;
	
	//floatval($TotalMtcFacture)+$Credit  montant à payé par le clt (floatval($TotalMtcFacture)+
	
	$TotalAvanceClt=floatval($AvanceClt)-floatval($Credit);

	if(floatval($TotalAvanceClt)<floatval($TotalMtcFacture) ){ return array(0, $Credit);}
	else {
		$ResteAv=floatval($TotalAvanceClt)-floatval($TotalMtcFacture);		
		  return array(1, $ResteAv);
	}
		

}
function BoxToPcs($Qte,$Colisage){
	 $QtePcs=$Qte * $Colisage;
	 return intval($QtePcs);
}
function PcsToBox($Qte,$Colisage){
	 $QteBox=$Qte / $Colisage;
	 $QtePcs=$Qte % $Colisage;
	   return array(intval($QteBox),intval($QtePcs));
}



function qteStock($params,$conn,$Type)
{ 
	
	
$sql = "SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
					as QteEntree FROM detailMouvements dm
			INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
			INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=? AND (m.type='entree' or m.type='Avoir') AND m.idDepot=?";
			
			

    $reponse=sqlsrv_query( $conn, $sql, $params, array( "Scrollable" => 'static' ) );	
				if( $reponse === false ) {
					$errors = sqlsrv_errors();
					$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
					return;
				}		
	$rowC = sqlsrv_fetch_array($reponse, SQLSRV_FETCH_ASSOC);
	$QteEntree=$rowC['QteEntree'];	


	return tofloat($QteEntree);
}
function DeleteCmd_Temp($conn,$IdVdr)
{
	$error="";
		$query_delete = "delete from cmd_temp  where idVendeur = ".$IdVdr;
		$reponse = sqlsrv_query($conn,$query_delete) ;
			if( $reponse === false ) {
					$errors = sqlsrv_errors();
					$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
					
				}	
				return $error;
}
?>
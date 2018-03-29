<?php 

	require_once('../connexion.php');
	include("lang.php");
	
	$ImprimAvance = "";
	$Imprime = "";
	$enteteFile='';

	$query_imprime = "select a.idArticle,f.totalTTC,a.Designation,a.Reference,d.qte,
	c.colisagee,d.ttc
	 from factures f inner join detailFactures d 
	on d.idFacture = f.IdFacture inner join articles a 
	on a.IdArticle = d.idArticle inner join colisages c
	on a.idArticle = c.IdArticle 
	where  f.IdFacture = $_REQUEST[id_fac] and EtatCmd = 2";
	$stmt2_query_imprime = sqlsrv_query( $conn, $query_imprime );
	while( $row = sqlsrv_fetch_array( $stmt2_query_imprime, SQLSRV_FETCH_ASSOC) ) {
				$NomArt = $row["Designation"];
				$Reference = $row["Reference"];
				$QteImp=$row["qte"];
				$PriceUnite = 0;

				$query_tarif = "select t.pvHT from tarifs t where t.idArticle = ".$row['idArticle'];
				//$Imprime.=$query_tarif;
				$stmt2_query_tarif = sqlsrv_query( $conn, $query_tarif );
				while( $row_tarif = sqlsrv_fetch_array( $stmt2_query_tarif, SQLSRV_FETCH_ASSOC) ) {
							$PriceUnite=$row_tarif["pvHT"];
				}
				
				$TotalPriceArti=$row["ttc"];
				$colisage = $row["colisagee"];
				$Imprime.=ucwords( $NomArt).PHP_EOL;
				$Imprime.= $trad['label']['Code']." : ".$Reference.PHP_EOL;
				$Imprime.="(".str_pad($QteImp, 2, ' ', STR_PAD_LEFT)." X ".str_pad($colisage, 2, ' ', STR_PAD_LEFT).") :".str_pad(number_format($PriceUnite, 2, '.', ' '), 8, ' ', STR_PAD_LEFT)."  ".str_pad(number_format($TotalPriceArti, 2, '.', ' '), 14, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
				$Imprime.=PHP_EOL;
				$PriceUnite = 0;
	}

	$totalTTC_query = 0;
	$cheque_query = 0;
	$espece_query = 0;
	$credit_query = 0;
	$query_fac_1 = "select f.totalTTC,f.Cheque,f.Credit,f.Espece from factures f where f.IdFacture = $_REQUEST[id_fac] and EtatCmd = 2";
	$stmt2_query_fac_1 = sqlsrv_query( $conn, $query_fac_1 );
	while( $row = sqlsrv_fetch_array( $stmt2_query_fac_1, SQLSRV_FETCH_ASSOC) ) {
		$totalTTC_query = $row['totalTTC'];
		$cheque_query = $row['Cheque'];
		$espece_query = $row['Espece'];
		$credit_query = $row['Credit'];
	}
	$ImprimAvance.="Espèce :".str_pad(number_format($espece_query, 2, '.', ' '), 14, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
	$ImprimAvance.="Chèque :".str_pad(number_format($cheque_query, 2, '.', ' '), 14, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
	$ImprimAvance.="Crédit :".str_pad(number_format($credit_query, 2, '.', ' '), 14, ' ', STR_PAD_LEFT). " DH".PHP_EOL;

	$nbr_ref = 0;
	$query_fac_2 = "select count(d.IddetailFacture) as total from detailFactures d where d.idFacture = $_REQUEST[id_fac] ";
	$stmt2_query_fac_2 = sqlsrv_query( $conn, $query_fac_2 );
	while( $row = sqlsrv_fetch_array( $stmt2_query_fac_2, SQLSRV_FETCH_ASSOC) ) {
		$nbr_ref = $row['total'];
	}

	//echo $query_fac_2;
	$nbrBoite = 0;
	$query_fac_boites = "select count(d.UniteVente) as total from detailFactures d where d.idFacture = $_REQUEST[id_fac] and d.UniteVente ='Colisage'";
	$stmt2_query_fac_3 = sqlsrv_query( $conn, $query_fac_boites );
	while( $row = sqlsrv_fetch_array( $stmt2_query_fac_3, SQLSRV_FETCH_ASSOC) ) {
		$nbrBoite = $row['total'];
	}

	$nbrPiece = 0;
	$query_fac_nbrPiece = "select count(d.UniteVente) as total from detailFactures d where d.idFacture = $_REQUEST[id_fac]  and d.UniteVente ='Pièce'";
	$stmt2_nbrPiece  = sqlsrv_query( $conn, $query_fac_nbrPiece );
	while( $row = sqlsrv_fetch_array( $stmt2_nbrPiece, SQLSRV_FETCH_ASSOC) ) {
		$nbrPiece = $row['total'];
	}




	$Imprime.=PHP_EOL;
	$Imprime.=$trad['label']['TotalFac']." ".str_pad(number_format($totalTTC_query, 2, '.', ' '), 17, ' ', STR_PAD_LEFT)." ".$trad['label']['riyal'] ." ".PHP_EOL;
	$Imprime.=$trad['label']['NbrRef']." ".str_pad($nbr_ref, 17, ' ', STR_PAD_LEFT).PHP_EOL;
	if(($nbrBoite!=0)&&($nbrPiece!=0)){
		$Imprime.=$trad['label']['Box']."/".$trad['label']['NbrPiece']." : ".str_pad($nbrBoite, 17, ' ', STR_PAD_LEFT)."/".$nbrPiece.PHP_EOL;

	}else if(($nbrBoite!=0)&&($nbrPiece==0)){
		$Imprime.=$trad['label']['Box']." : ".str_pad($nbrBoite, 23, ' ', STR_PAD_LEFT).PHP_EOL;
	}else if(($nbrBoite==0)&&($nbrPiece!=0)){
		$Imprime.=$trad['label']['NbrPiece']." : ".str_pad($nbrPiece, 25, ' ', STR_PAD_LEFT).PHP_EOL;
	}

	$Imprime.="----------------------------------------".PHP_EOL;		





	$sql = " SELECT IdFacture IdFacture,NumFacture as NumFacture ,v.nom+ ' '+v.prenom Vendeur,c.CodeClient Client, c.intitule IntituleClt,
	v2.Designation Ville,c.Tel,
	v.codeVendeur CodeVdr,f.totalTTC
		FROM 
		factures f  INNER JOIN clients c  ON c.IdClient=f.idClient
		INNER JOIN vendeurs v ON v.idVendeur = f.idVendeur
		inner join depots d on d.idDepot=c.idDepot
		inner join villes v2 on v2.idVille=d.idVille
		 WHERE IdFacture=".$_REQUEST['id_fac']." and EtatCmd = 2 ";
	

	$stmt2 = sqlsrv_query( $conn, $sql );
	while( $row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {
				$IdFacture = $row["IdFacture"];
				$NumFacture = $row["NumFacture"];
				$Client=$row["Client"];
				$NomVdr=$row["Vendeur"];
				$CodeVdr=$row["CodeVdr"];
				$IntituleClt=$row["IntituleClt"];
				$Ville=$row["Ville"];
				$Tel=$row["Tel"];


	
	$Date=date_create(date("Y-m-d  H:i"));
	$enteteFile.="VENDEUR : ".strtoupper($NomVdr).PHP_EOL ;
	$enteteFile.="DATE ET HEURE : ".date_format($Date, 'd/m/Y H:i').PHP_EOL;
	$enteteFile.=$NumFacture.PHP_EOL ;
	$enteteFile.=PHP_EOL ;
	$enteteFile.="CLIENT : ".strtoupper($Client)." - ".strtoupper($IntituleClt).PHP_EOL ;
	if( $Tel!="") $enteteFile.="Tel : ".$Tel.PHP_EOL ;
	$enteteFile.="VILLE : ".strtoupper($Ville).PHP_EOL ;
	$enteteFile.=PHP_EOL ;
	$enteteFile.=PHP_EOL;

}
	//$Imprime=$enteteFile.$Imprime.$ImprimAvance;
	//$name="Paiement ".date('d-m-Y H-i');
	$name=date('d-m-Y H-i');
	$fp = fopen ("bon_cmd/".$name.".txt", "w");
	$Imprime=$enteteFile.$Imprime.$ImprimAvance;
	fputs ($fp,$Imprime);
	fclose ($fp);

	$dir="bon_cmd/".$name.".txt";
	$filename=$name.".txt";
	$name= urlencode ($name);
 
$link = "download.php?fileName=".$name;
echo  $link;	
	    

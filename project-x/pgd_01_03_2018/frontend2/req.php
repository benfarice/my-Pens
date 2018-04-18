<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();
$IdDepot=1;
$_SESSION['IdVendeur']=5;
$_SESSION['IdClient']=1;

$timestamp_debut = microtime(true);


$sql="select 
						a.IdArticle	,me.url UrlArticle,a.reference Reference ,CB codeABarre,colisagee Colisage,a.unite,a.TVA,
						fa.Designation DsgFamille,
						sf.Designation as dsgSousFamille,
						m.Designation DsgMarque,
						g.Designation as dsgGamme ,
						a.Designation DsgArticle,

						g.Reference RefG,
						g.idGamme as IdGamme ,
						a.IdArticle	,
						mg.url UrlGamme,
						sf.idSousFamille IdSousFam ,
						t.pvHT PV,
						f.idFiche,
						fa.idFamille IdFamille,fa.codeFamille CodeFamille	,
		m.Chemin UrlMarque,
		m.IdMarque
		from articles a
		inner join media me on me.idArticle=a.IdArticle
		inner join colisages co on co.idArticle=a.idArticle
			INNER JOIN gammes g ON g.IdGamme=a.IdFamille
			INNER JOIN marques m ON m.IdMarque=g.IdMarque
			inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
			INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
			inner join mediaGammes mg on mg.idGamme=g.IdGamme
			INNER JOIN dbo.tarifs t ON t.idArticle=a.IdArticle 
			INNER JOIN dbo.ficheTarifs f ON f.idFiche=t.idFiche
			INNER JOIN dbo.detailChargements dc ON dc.idArticle=a.IdArticle
			INNER JOIN dbo.chargements c ON c.IdChargement=dc.IdChargement
		WHERE 
		c.idVendeur=?  and
		f.idTypeClient=? 
		and f.type='Groupe'
		
		group by 
		a.IdArticle	,a.unite,
		me.url ,a.reference,CB ,colisagee ,a.TVA,
						fa.Designation ,
						sf.Designation ,
						m.Designation,
						g.Designation  ,
						a.Designation,

						g.Reference ,
						g.idGamme  ,
						a.IdArticle	,
						mg.url,
						m.Chemin ,		m.IdMarque,
						sf.idSousFamille  ,
						t.pvHT,
						f.idFiche,
						fa.idFamille ,fa.codeFamille 	
		 order BY   fa.idFamille desc ";
		 

		 $params = array($_SESSION["IdVendeur"],$_SESSION["IdClient"]);	


		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
								}
		$nRes = sqlsrv_num_rows($stmt);	
		
		// timestamp en millisecondes de la fin du script
$timestamp_fin = microtime(true);
 
// différence en millisecondes entre le début et la fin
$difference_ms = $timestamp_fin - $timestamp_debut;
 
// affichage du résultat
echo 'Exécution du script : ' . $difference_ms . ' secondes.';
 
// Astuce pour cacher l'affichage du temps d'execution
echo '<!-- Exécution du script : ' . $difference_ms . ' secondes. -->';



		echo "<br>nombre de lignes ".$nRes ;
		?>
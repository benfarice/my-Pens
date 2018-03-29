<?php

include("../php.fonctions.php");
require_once('../connexion.php');

session_start();
require_once('envoiMail.php');
if (isset($_GET['StockVdr'])){

if(!isset($_SESSION['lignesCatV'])){

	//selectionner les familles des chargements valide
	
	$sql="select 
						a.IdArticle	,
						a.TVA	,
						c.colisagee Colisage,
						a.Reference,
						ma.url UrlArticle,
						fa.Designation DsgFamille,
						sf.Designation as dsgSousFamille,
						m.Designation DsgMarque,
						g.Designation as dsgGamme ,
						a.Designation DsgArticle,
						a.Unite,
						g.Reference RefG,
						g.idGamme as IdGamme ,
						a.IdArticle	,
						mg.url UrlGamme,
						sf.idSousFamille IdSousFam ,
						fa.idFamille IdFamille,fa.codeFamille CodeFamille	,
					t.pvHT PV	,
		m.Chemin UrlMarque,
		m.IdMarque
		from articles a
		INNER JOIN colisages c ON c.idArticle=a.IdArticle
		INNER JOIN media ma ON ma.idArticle=a.IdArticle
			INNER JOIN gammes g ON g.IdGamme=a.IdFamille
			INNER JOIN marques m ON m.IdMarque=g.IdMarque
			inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
			INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
			inner join mediaGammes mg on mg.idGamme=g.IdGamme
			INNER JOIN dbo.tarifs t ON t.idArticle=a.IdArticle 
			 INNER JOIN dbo.ficheTarifs f ON f.idFiche=t.idFiche
			 where f.idTypeClient=1 and f.type like 'Groupe' and f.etat=1
		
		group by 
		a.IdArticle	,
						fa.Designation ,
						sf.Designation ,
						m.Designation,
						g.Designation  ,
						a.Designation,
						g.Reference ,
						g.idGamme  ,
						a.IdArticle	,
						a.TVA	,
						a.Unite,
						c.colisagee ,
						a.Reference,
						ma.url ,
						mg.url,
						m.Chemin ,	
						m.IdMarque,
						sf.idSousFamille  ,
						t.pvHT 	,
						fa.idFamille ,fa.codeFamille 
									
		 order BY   fa.idFamille desc ";
		 
	//echo $sql; 
		 $params = array();	
//parcourir($params);return;

		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));

		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
								}
								
		$ntRes = sqlsrv_num_rows($stmt);
		//echo $ntRes;
			$nRes = sqlsrv_num_rows($stmt);	

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
							$groups = array();
									$i=0;
						
				 while($row=sqlsrv_fetch_array($stmt)){	
				
								/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
												 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
												 
												 
										$key = $row['IdFamille'];
										
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['IdFamille']=$row['IdFamille'];
											$groups[$key]['DsgFamille']=$row['DsgFamille'];
							
										}  
									
												$keySousFam = $row['IdSousFam'];
												if (!isset($groups[$key][$keySousFam])) {
											
														$groups[$key][$keySousFam] = array();
														$groups[$key][$keySousFam]['IdSousFam']=$row['IdSousFam'];
														$groups[$key][$keySousFam]['DsgSousFam']=$row['dsgSousFamille'];
														
													} 
											
													$keyMarque= $row['IdMarque'];
													if (!isset($groups[$key][$keySousFam][$keyMarque])) {
												
															$groups[$key][$keySousFam][$keyMarque] = array();
															$groups[$key][$keySousFam][$keyMarque]['IdMarque']=$row['IdMarque'];
															$groups[$key][$keySousFam][$keyMarque]['DsgMarque']=$row['DsgMarque'];
															$groups[$key][$keySousFam][$keyMarque]['UrlMarque']=$row['UrlMarque'];
															
														} 
														
												$keyGamme= $row['IdGamme'];
													if (!isset($groups[$key][$keySousFam][$keyMarque][$keyGamme])) {
												
															$groups[$key][$keySousFam][$keyMarque][$keyGamme] = array();
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['IdGamme']=$row['IdGamme'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgGamme']=$row['dsgGamme'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['UrlGamme']=$row['UrlGamme'];
															
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgFamille']=$row['DsgFamille'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgSousFam']=$row['dsgSousFamille'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgMarque']=$row['DsgMarque'];
															
														} 
																								
													
																				
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdArticle'] = $row['IdArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['DsgArticle'] = $row['DsgArticle'];									
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['PV'] =$row['PV'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Reference'] =$row['Reference'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Colisage'] =$row['Colisage'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['TVA'] =$row['TVA'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Unite'] =$row['Unite'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdGamme'] =$row['IdGamme'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdMarque'] =$row['IdMarque'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdSousFam'] =$row['IdSousFam'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdFamille'] =$row['IdFamille'];
													
													
											$i=$i+1;		
										
							
										}
				
					$_SESSION['lignesCatV']=$groups;
	

			 }// fin bdd plein
}// fin if isset session

		if(isset($_GET['Vider'])){
	
		// vider liste des articles commandés
		//unset($_SESSION['lignesCmd']);
		unset($_SESSION['StockV']);
		
		}
?>
<DIV>
<div style="float:left;width:1081px; display:flex;align-items:center; padding:2px 0;border-right:none;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
						<div >&nbsp;> <span  Class="TitleHead" onclick="">Commande dépôt</span></div> 
</div>
<div style="float:right;width:195px;" ><input type="button" value="Ajout article" class="btnCmdVendeur hvr-grow"  onclick="AffCatalogue();"/></div>
</div>
<DIV class="haut">
		<div class="divLeft" Style="height:650px">
			<div class="leftStock">
			<div class="StockVe"><span style="text-decoration:underline">Stock véhicule</span>:
			<?php
			
	$sqlVe = "SELECT TOP 1  v.Designation+' '+v.immatriculation  vehicule FROM affectations a
		 INNER JOIN vehicules v ON v.idVehicule = a.idVehicule
		 WHERE a.idVendeur =?
		 ORDER BY a.idaffectation DESC ";
			

	 $paramsV = array($_SESSION['IdVendeur']);	
		$stmt=sqlsrv_query($conn,$sqlVe,$paramsV,array( "Scrollable" => 'static' ) );
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									$errors= "Erreur : ".$errors[0]['message'];
									?>
							<script language="javascript">
							alert('<?php echo $errors; ?>');
							</script>
							<?php	
								}
		$nRes = sqlsrv_num_rows($stmt);	
	if($nRes>0)
	{
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		echo $row['vehicule'];
	}
		?>
		</div>
			<div class="filtre">
			
			<input type="text" id="search" placeholder="Rechercher..." >
			</div>
			
			</div>
			<div class="clear"></div>
		<?php
		
	//	controler si la table session stock a été déjà rempli 
if(!isset($_SESSION['StockV']))  {
//if(count($_SESSION['StockV'])==0)  {

									 
				$sqlAr = "
					 select a.idarticle IdArticle, a.designation DsgArticle,a.Reference RefArticle,
					  fa.idFamille IdFamille,fa.Designation,
					  fa.codeFamille RefFam,sf.codeSousFamille RefSousFam,
					  g.Reference RefGamme,
					  sf.idSousFamille IdSousFam,
					  sf.Designation dsgSousFamille,g.IdGamme,g.Designation dsgGamme,
						stock Stock	,
						t.pvHT PV					 
					 from stockvendeurs s 
					 inner join articles a on a.idarticle=s.idarticle
					 INNER JOIN dbo.tarifs t ON t.idArticle=a.IdArticle 
					 INNER JOIN dbo.ficheTarifs f ON f.idFiche=t.idFiche
					INNER JOIN gammes g ON g.IdGamme=a.IdFamille
					inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
					INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
			
					 where f.idTypeClient=1 and f.type like 'Groupe' and  f.etat=1 and
					  idVendeur=?
					  and  stock>0 
					  order by stock ASC
									 ";
								
									 /*			INNER JOIN detailChargements dc on dc.idarticle=a.IdArticle
			INNER JOIN dbo.chargements c ON c.IdChargement=dc.IdChargement where  AND c.idVendeur=1 */
								 
						$paramsAr = array($_SESSION['IdVendeur']);

						$stmtA=sqlsrv_query($conn,$sqlAr,$paramsAr,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
						if( $stmtA === false ) {
							$errors = sqlsrv_errors();
						
							?>
							<script language="javascript" type="text/javascript">
							jAlert( "Erreur : <?php echo $errors[0]['message'];?>");
							</script>
							<?php 
						}
					else {
		
						$nResA = sqlsrv_num_rows($stmtA);
						//echo $sql;
							$nRes = sqlsrv_num_rows($stmtA);	

						if($nResA==0)
								{ ?>
											<div class="resAff" style="text-align:center;min-height:200px;font-size:26px;">
												<br><br><br><br>		<br><br><br><br>
												Stock est vide.
											
											</div>
											<?php
								}
						else
						{	
								$i=0;
								while($row=sqlsrv_fetch_array($stmtA)){	
												//recuperer la qte du stock de chaque article

											$qteEntreeGlobal =$qteChargementGlobal=0;
												$params= array(
															$row['IdArticle']
															
													) ;
										//---------------------------select qteEntreeGlobal--------------------------------//
													$sql = "SELECT isnull(sum(dmo.qte),0) FROM mouvements mo 
													INNER JOIN detailMouvements dmo ON dmo.idMouvement = mo.idMouvement 
													inner join articles a on a.idArticle=dmo.idArticle
													WHERE mo.type LIKE 'Entree'  and 
													 dmo.IdArticle=? 	 group by dmo.idArticle";
												

													$stmt1 = sqlsrv_query( $conn, $sql, $params );
													if( $stmt1 === false ) {
														$errors = sqlsrv_errors();
														echo "Erreur : qteEntreeGlobal ".$errors[0]['message'] . " <br/> ";
														return;
													}
													sqlsrv_fetch($stmt1) ;
													$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
												//---------------------------select qteChargementGlobal du bdd de l'article à ajouter--------------------------------//
														$sql2 = "SELECT isnull(sum(CASE WHEN dc.reste =0 THEN dc.qte ELSE dc.reste END ),0) as qte,
													idColisage
															FROM chargements ch INNER JOIN detailChargements dc on 
															ch.IdChargement=dc.IdChargement WHERE
															dc.IdArticle=? 	group by dc.IdArticle,idColisage";
													$stmt2 = sqlsrv_query( $conn, $sql2, $params );	
											
													if( $stmt2 === false ) {
														$errors = sqlsrv_errors();
														echo "Erreur : qteChargementGlobal ".$errors[0]['message'] . " <br/> ";
														return;
													}
													//$qteChargementGlobal=0;
													while ($rc =  sqlsrv_fetch_array($stmt2))
													{
														if($rc["idColisage"]==0)
														$qteChargementGlobal += floatval($rc["qte"]);
														else $qteChargementGlobal += (floatval($rc["qte"])*intval($rc["idColisage"]));
													}
												 //}
													//$qteCh		
											$QteStock=$qteEntreeGlobal+$qteChargementGlobal;												
											$_SESSION["StockV"][$i]['IdArticle'] = $row['IdArticle'];
											$_SESSION["StockV"][$i]['DsgArticle']=$row['DsgArticle'];
											// stock article par vendeur
											$_SESSION["StockV"][$i]['Stock']=$row['Stock'];
											$_SESSION["StockV"][$i]['Prix']=$row['PV'];
											// stock article ds le depot
											$_SESSION["StockV"][$i]['StockArticle']=$row['Stock'];
											
											$_SESSION["StockV"][$i]['DsgFamille']=$row['Designation'];
											$_SESSION["StockV"][$i]['DsgSousFam']=$row['dsgSousFamille'];
											$_SESSION["StockV"][$i]['DsgGamme']=$row['dsgGamme'];
											$_SESSION["StockV"][$i]['RefFam']=$row['RefFam'];
											$_SESSION["StockV"][$i]['RefSousFam']=$row['RefSousFam'];
											$_SESSION["StockV"][$i]['RefGamme']=$row['RefGamme'];
											$_SESSION["StockV"][$i]['RefArticle']=$row['RefArticle'];
											
											$i++;
										}
										
						}
						
						}
						
			}
					//parcourir($_SESSION['StockV']);return;
		if( (isset($_SESSION['StockV'])) && (count($_SESSION['StockV'])!=0))  {?>
					
					<DIV class="entete">
						<div class="divArticle" Style="width:335px;" align="center">Article </div>
						<div class="divPV"  Style="width:190px;" align="center">Quantité du stock </div>
					<div class="divPV"  Style="width:210px;" align="center">Valeur(Dhs) </div>
					</div>
						<div class="clear"></div>						
				<div class="DivListArt scrollbar-inner"   style="height:590px">		
				<?php 
				$key="";$c="";
					foreach($_SESSION['StockV'] as $r){						
						if(is_array($r)){						
									?>		
								<div class="ligne test"  id="LigneStock">
								<!--onclick="getArticle('<?php  echo $r['IdArticle'];?>','ActualiserAccueil')" -->
									<div class=" <?php echo $c;?> divArticle" onclick="getArticle('<?php  echo $r['IdArticle'];?>','ActualiserAccueil')"
										style="width:268px" align="center"><?php  echo ucfirst(stripslashes($r['DsgArticle']));?>					
									</div>
									<div class="divPV" style="width:160px" > <?php  echo number_format($r['Stock'], 0, '.', ' '); ?> </div> 
									<div class="divPV" style="width:160px" > <?php  
									$valeur=$r['Prix']*$r['Stock'];
									echo number_format($valeur,2, '.', ' '); ?> </div> 
									<div style="display:none" >
									<?php  echo ucfirst(stripslashes($r['DsgFamille']))." ";?>
									<?php  echo ucfirst(stripslashes($r['DsgSousFam']))." ";?>
									<?php  echo ucfirst(stripslashes($r['DsgGamme']))." ";?>
									<?php  echo ucfirst(stripslashes($r['RefFam']))." ";?>
									<?php  echo ucfirst(stripslashes($r['RefSousFam']))." ";?>
									<?php  echo ucfirst(stripslashes($r['RefGamme']))." ";?>
										<?php  echo ucfirst(stripslashes($r['RefArticle']))." ";?>
									</div>
								</div>
								<div class="clear"></div>
						<?php 
						}
						}?>									
			</div>
		
		<?php } // end isset session stock?>
		</div>
		<div class="divRight" >		
			<!--<div class="titleStock">Liste de commande</div>-->
			<?php
			if(isset($_SESSION['lignesCmd']) && count($_SESSION['lignesCmd']) != 0){	
//parcourir($_SESSION['lignesCmd']);			
						$i=0;
						?>
				<div id="res"></div>	
				<form id="formCmd" method="post" name="formCmd"> 						
				<div style="height:580px; overflow:scroll;" >
					<DIV class="ListeCmd">				
					<DIV class="entete">
						<div class="divArticle" Style="width:460px;" align="center">Article </div>
						<div class="divPV"  Style="width:200px;" align="center">Qte Commandée</div>

					</div>
				<div class="clear"></div>
						<?php
						$k=0;$Total=0;
					foreach($_SESSION['lignesCmd'] as $r){
						
						if(is_array($r)){						
									?>		
								<div class="ligne " >
									<div class=" <?php //echo $c;?> divArticle "  
									onclick="getArticle('<?php  echo $r['IdArticle'];?>','ActualiserAccueil')"
									style="width:440px" align="center"><?php  echo ucfirst($r['NomArt']);?>					
									</div>
									<div class="divPV" style="width:200px" > <?php 

									echo number_format($r['Qte']*$r['Colisage'], 0, '.', ' '); ?> </div> 
									
								</div>
								<div class="clear"></div>
						<?php 
						}
						}?>	
				
				</div>	
					<div class="bottomVente width630">
						<input type="button" value="Valider la commande >>" class="btnCmd " onclick="TerminerCmd()">
					</div>
				</form>	
				<?php
					}else {?>
							<div class="resAffCat" >
												<br><br><br><br><br><br><br>
												Aucun article ajouté.
											
											</div><?php
					}						
				?>
		</div>
		<div class="clear"></div>
		
		</div>

<script language="javascript">
$(document).ready(function(){
 //  $('.scrollbar-inner').scrollbar();
var $rows = $('.test');
$('#search').keyup(function () {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function () {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});

});

</script>
<?php	

exit;
}


if (isset($_GET['TerminerCmd'])){
//parcourir($_SESSION['lignesCmd']);return;
	//parcourir($_POST);return;
	//parcourir($_SESSION['lignesCmd'] );return;
	  $error="";
	  
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}


$NumCmd= "NC".Increment_Chaine_F("numCommande","commandeVendeurs","idCommandeVendeur",$conn,"",array());	
//echo $RefFicheCh;return;
$Date = date_create(date("Y-m-d"));

$Etat="";
//---------------------------aJOUT CMD--------------------------------//
$reqInser1 = "INSERT INTO commandeVendeurs ([numCommande]   ,[idVendeur]  ,[date],etat,idDepot) 
				values 	(?,?,?,?,?)";
		//	echo $reqInser1;
$params1= array(
				$NumCmd,
				$_SESSION["IdVendeur"],
				$Date,
				0,
				$_SESSION["IdDepot"]
				
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout commandeVendeurs ".$errors[0]['message'] . " <br/> ";
}

//---------------------------IdCmd--------------------------------//
$sql = "SELECT max(idCommandeVendeur) as IdCmd FROM commandeVendeurs";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idCommandeVendeur : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdCmd = sqlsrv_get_field( $stmt2, 0);


//----------------------Add Detail Commande vendeur --------------------------//
$idFiche="";

  foreach($_SESSION['lignesCmd'] as $ligne=>$contenu){

	$reqInser2 = "INSERT INTO  detailCommandeVendeurs(idCommandeVendeur,[idArticle],[qte],colisage,idDepot) 
					values (?,?,?,?,?)";
			$params2= array(
					$IdCmd,
					$contenu['IdArticle'],
					$contenu['Qte'],
					$contenu['Colisage'],
					$_SESSION["IdDepot"]
			
			) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout detail Commande ".$errors[0]['message'] . " <br/> ";
				break ;
			}		
		
}

if( envoiCmd($IdCmd,$conn)==false)  $error="Erreur d'envoi de la facture";
if($error=="" ) {
    sqlsrv_commit( $conn );
	 unset($_SESSION['lignesCmd']);
     ?>

	 <script language="javascript" type="text/javascript">	
	//	alert("la cmd a été effectuée");
			//jAlert("La commande été effectuée","Message");
			//si la cmd a été valider par catalogue
			if ($('#box').dialog('isOpen') === true) {			
				$('#box').dialog('close');
			}
			$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').loa
			d('cmd_vendeur1.php?StockVdr');
			Imprimer("<?php echo $IdCmd;?>");	
		</script>
		
<?php 

} else {
     sqlsrv_rollback( $conn );
	      ?>
		<script type="text/javascript"> 
			jAlert("<?php echo $error; ?>");				
		</script>
		
<?php
     
}

	exit;
}


if (isset($_GET['VerifSession'])){
	
	   if( (!isset($_SESSION['lignesCmd'])) || (count($_SESSION['lignesCmd'])==0))  
		   echo "0";
	   else echo "1";
	   exit;
				
}
if (isset($_GET['FermerFenetre'])){
		/*$soap = new SoapClient('http://'.$ip.'/WebServicePos/Service.asmx?wsdl', array('exceptions' => true));
		$result = $soap->ArretProcessus_POSC();
	exit;*/
}
if (isset($_GET['ConsultCmd'])){
	
?>
<input type="button" value=""  class="close2" onclick="Fermer()" Style="float:right;"/>
<div class="clear"></div>
<?php
if(isset($_SESSION['lignesCmd']) && count($_SESSION['lignesCmd']) != 0){
		
			$i=0;
			?><div id="res"></div>
	<form id="formCmd" method="post" name="formCmd"> 		
	
	<DIV Class="title">Validation de la commande </div><br>
	<div style="height:585px; overflow:scroll;" >

	<DIV class="ListeCmd">
	 	 <div class="enteteL" >
        <div  class="divArticleL" style="width:430px" >Article </div>
		   <div  class="divArticleL" style="width:260px"  >Gamme </div>		
        <div class="divQteL" style="width:210px"> Quantité </div>			
		 <div class="divColisageL"> Colisage </div>
		  <div class="divPVL" style="width:210px"> Quantité totale </div>
		
		</div>
  	
			<?php
			$k=0;$Total=0;
			foreach($_SESSION['lignesCmd'] as $ligne=> $row){
				$k++;
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			$Total+=$row["Qte"]*$row["Colisage"]*$row["PV"];
				?>	
				<div class=" <?php echo $c;?>" onclick="getArticle('<?php  echo $row['IdArticle'];?>','list')">
						<div class="divArticleL" align="center"><?php  echo $row['NomArt'];?></div>
						<div class="divArticleL"  style="width:260px"  align="center"><?php  echo $row['Gamme'];?></div>
						<div class="divQteL" style="width:210px" >  <?php echo number_format($row['Qte'], 0, '.', ' '); ?> </div> 											
						<div class="divColisageL" > <?php  echo $row['Colisage'];?> </div> 
						<div class="divPVL" style="width:210px"> <?php echo number_format($row['Qte']*$row['Colisage'], 0, '.', ' '); ?>  </div> 
									
								
				</div>
			<DIV Class="clear"></div>
			
	
			<?php
		}	
	?>
	</div>


</div>
<div class="bottomVente  " style="width:1258px">
<input type="button" value="Valider la commande >>" class="btnCmd" onclick="TerminerCmd()">
		
		</div>
	</form>
<?php
			
	}	

exit();

}

function Total(){
	$Total=0;
	if( (isset($_SESSION['lignesCmd'])) && (count($_SESSION['lignesCmd'])!=0))  {
	foreach($_SESSION['lignesCmd'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
			$Tva=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"] * $contenu["TVA"]) /100;
			
			$Total+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
			}
	}
	return($Total);
}


include("header.php");
?>
<div id="formRes" style="MAX-height:790px;">

</div>


<div id="box"></div><div id="boxArticle"></div>
<div id="filtre"></div>
<script language="javascript" type="text/javascript">	

function ConsultCmd(){

	 var Verif="";
	$.get("cmd_vendeur1.php?VerifSession", function(response) {
      Verif = response;
	  // verifier si le vendeur a ajouté des articles
		if(Verif==1){
		var url='cmd_vendeur1.php?ConsultCmd';	
		$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
		//$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
			}else 
			{
				jAlert("Merci d'ajouter des articles.","Message");
			}
	});		
}

function CmdDepot(){
		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('cmd_vendeur1.php?StockVdr');
}
function getArticle(idArticle,list){

	if (list === undefined || list === null) {
		var url='catalogue_cmd_vendeur1.php?getArticle&&idArticle='+idArticle;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}
	else{
		//pour afficher liste
		var url='catalogue_cmd_vendeur1.php?getArticle&&Actualiser&&idArticle='+idArticle;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}
	
}


$(document).ready(function(){

	//$(":radio").labelauty();	
	$("input[type=button").addClass("hvr-grow");
// code pour prendre enconsideration l'hover quand on met le doigt sur l'ecran
$('body').bind('touchstart', function() {});

	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('cmd_vendeur1.php?StockVdr&&Vider');
$.validator.messages.required = '';
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
		
				$('#boxArticle').dialog({
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
// This button will increment the value
$('input[type=text]').on('focus', function() { 
  console.log($(this).attr('id') + ' just got focus!!');
  window.last_focus = $(this);
});
});

function AfficheGamme1(id){
								//alert('lll');
								var url='catalogue_cmd_vendeur1.php?affGamme&&Id='+id;	
								$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

							}
function AfficheDetailGamme(id){
	//alert('lll');
	var url='catalogue_cmd_vendeur1.php?aff&&Id='+id;	
	//$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');

}
function rechercherFam(){
		//alert('lll');
		var url='catalogue_cmd_vendeur1.php?affFam';	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}
function AfficheSousFam(id){
		//alert(id);
		var url='catalogue_cmd_vendeur1.php?affSousFam&&Id='+id;	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}
function AfficheMarque(id){
		var url='catalogue_cmd_vendeur1.php?affMarque&&Id='+id;	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}

function AffCatalogue(){
	
	var url='catalogue_cmd_vendeur1.php?affFam';	
	$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

}
function Fermer(){
	$("#box").dialog('close');
}
function FermerBoxArt(){
	$("#boxArticle").dialog('close');
}
function FermerFenetre(){
	alert("fermer");
	var url='cmd_vendeur1.php?FermerFenetre';	
	//$("#box").load(url);
}

function Imprimer(IdCmd){
		
			 options = "Width=1280,Height=800" ;
		//	 alert(IdFacture);
		//	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('facture.print.php?IdFacture='+IdFacture).dialog('open');
		  window.open( 'bon_cmd.print.php?IdCmd='+IdCmd, "edition", options ) ;
		
	}

    var TabColor = [ 
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
	];
	
	function TerminerCmd(){
		
		 jConfirm('Voulez-vous vraiment commander l\'article?', null, function(r) {
					if(r)	{
					var url='cmd_vendeur1.php?TerminerCmd';	
				$('#res').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
					}
		 });

	}

</script>

<?php include("footer.php");?>
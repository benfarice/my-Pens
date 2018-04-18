<?php 
require_once('connexion.php');
include("php.fonctions.php");

//sqlsrv_query("SET NAMES UTF8");
SQLSRV_PHPTYPE_STRING('UTF-8') ; 
session_start();
$tableInser = "chargements";
$sansDoublons = "NomCaisse";
$cleTable = "IdChargement";
$nom_sansDoublons = "Nom de la caisse";
$IdDepot="1";
$UserId="1";
$Operateur=1;


if (isset($_GET['ActualisSess'])){
	parcourir($_POST);return;
	exit;
	
}
if (isset($_GET['InfoArticle'])){
	$QteDisp=0;$error="";
	//---------------------------select qteEntreeGlobal--------------------------------//
				$sql = "SELECT isnull(sum(dmo.qte),0) FROM mouvements mo 
				INNER JOIN detailMouvements dmo ON dmo.idMouvement = mo.idMouvement 
				inner join articles a on a.idArticle=dmo.idArticle
				WHERE mo.type LIKE 'Entree'  and 
                 dmo.IdArticle=? 	 group by dmo.idArticle";
				 $params1= array($_GET['IdArticle']) ;

				$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					$error= "Erreur :  ".$errors[0]['message'] . " <br/> ";
					return;
				}
				sqlsrv_fetch($stmt1) ;
				$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
			//---------------------------select qteChargementGlobal--------------------------------//
				$sql2 = "SELECT isnull(sum(CASE WHEN dc.reste =0 THEN dc.qte ELSE dc.reste END ),0) as qte,
				idColisage
						FROM chargements ch INNER JOIN detailChargements dc on 
						ch.IdChargement=dc.IdChargement WHERE
						dc.IdArticle=? 	group by dc.IdArticle,idColisage";
				$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	
		
				if( $stmt2 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteChargementGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				$qteChargementGlobal=0;
				while ($rc =  sqlsrv_fetch_array($stmt2))
				{
					if($rc["idColisage"]==0)
					$qteChargementGlobal += floatval($rc["qte"]);
					else $qteChargementGlobal += (floatval($rc["qte"])*intval($rc["idColisage"]));
				}
				
				// la qte dispo ds le stocke bdd
			
				$QteDisp=floatval($qteEntreeGlobal)-floatval($qteChargementGlobal);
				
			
				$sql3 = "select  colisagee from articles a
						inner join colisages c on c.idArticle=a.idArticle group by colisagee";
		
  	            $reponse=sqlsrv_query( $conn, $sql3, $params1, array( "Scrollable" => 'static' ) );	
				if( $reponse === false ) {
					$errors = sqlsrv_errors();
					$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
					return;
				}
					$Colisage="";	
				while ($r =  sqlsrv_fetch_array($reponse))
				{
					$Colisage.=$r["colisagee"].",";
				}
				if( ($error=="")) {

					?>
				
					<script type="text/javascript"> 	
					$("#QteDispG").val("<?php echo $QteDisp ;?>");					
					$("#ColArt").val("<?php 	echo  substr($Colisage,0,-1 ); ?>");	
						$('#InfoArticle').slideDown("slow");					
					</script>					
			<?php
			
			} else {?>
				<script type="text/javascript"> 					
						$('#InfoArticle').show('show');						
					
					</script>		
				 <?php echo $error;
			}
				
	exit;
}

if(isset($_GET['VideSession'])){
	unset($_SESSION['lignes']);

	exit;
}
if(isset($_GET['Act'])){

	
$error="";
	/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	/************* desactivation de tous les fiches pour le groupe ou clt spécifié**/
	$paramsUpd= array($_GET['idCltGrp']) ;	
	$reqUpdate="update ".$tableInser." set etat=0 where idTypeClient  =(?)";
	$stmtUp = sqlsrv_query( $conn, $reqUpdate, $paramsUpd );
	if( $stmtUp== false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : Modif fiche ".$errors[0]['message'] . " <br/> ";
	}
	/************* activation de la fiche spécifié**/

	$paramsUpdF= array($_GET['ID']) ;	
	$reqUpdateF="update ".$tableInser." set etat=1 where IdFiche  =(?)";
	$stmtUpF = sqlsrv_query( $conn, $reqUpdateF, $paramsUpdF );
	if( $stmtUpF== false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : Modif fiche ".$errors[0]['message'] . " <br/> ";
	}
	if( $error=="" ) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			alert('L\'activation a été effectuée.');
			$('#box').dialog('close');
			rechercher();
		</script>
		
<?php
	sqlsrv_close( $conn );  
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}

exit;
}


///////////////////////////////////////////////on supprime une ligne
if(isset($_GET['supLigne'])){
		
		$ligne = $_GET['supLigne'];
		

	unset($_SESSION['lignes'][$ligne]); // remove item at index 0
	$_SESSION['lignes'] = array_values($_SESSION['lignes']); // 'reindex' array

	//parcourir($_SESSION['lignes']);//return;
			?>
		<script language="javascript">
		$('#listBox').load('chargements.php?list');
				//charger('chargements.php?list');
			</script>
		
		<?php
		
		exit;
}


if(isset($_GET['list'])){

///////////////////////////////////////on liste les lignes
?>
<style>
.ligneEdit:first-child{	border-top:none;}
</style>
<?php
	if(isset($_SESSION['lignes']) && count($_SESSION['lignes']) != 0){
		
			$i=0;
			?>
			
			<table width="100%" id="table1" >
			     <tr class="entete">
					<td width="10%">Code article </td>
					<td width="45%">Article </td>
					<td width="9%">Unite </td>
					<td width="10%"> Quantité</td>
					 <td width="5%"> Colisage </td>
					 	 <td width="5%"> Quantité demandée par pièce</td>
					<td width="10%"> Quantité disponible</td>
					
					
        <td  >
		
		</td>
  </tr>		
	<?php
			$k=0;
			
			foreach($_SESSION['lignes'] as $ligne=> $row){
			
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
					$k++;
				?>
				<div id="session" onDblClick="modLigne('<?php echo $ligne; ?>');" class="ligneEdit" style="margin-bottom:-10px;">
				<form id="formLigne" action="" method="post" name="formLigne" >
			
				<tr  class="<?php //echo $c; ?>">
				<td width="10%"><input type="text" name="CodeBarre[]" readonly value="<?php echo $row['CodeaBarre']; ?>" STYLE="background:none;border:0;" size="10"></td></td>
				<td width="110"><input type="text" name="NomArt[]" readonly  value="<?php echo $row['NomArt']; ?>" STYLE="background:none;border:0;" size="45"></td>
				<td width="9%">
				<input type="text" value="<?php echo $row['Unite']; ?>" name="Unites[]" readonly size="7" STYLE="background:none;border:0;">
				<input type="hidden" value="<?php echo $row['IdArticle']; ?>"	name="IdArticle[]">
				<input type="hidden" value="<?php echo $row['idCommandeVendeur']; ?>" name="idCommandeVendeur[]">
				</td>
				
				
				<td width="10%"  align="right">
				<?php 
				
				if( $row['Colisage']=="0") $col=1;else $col=$row['Colisage'];
				if((floatval($row['Qte'])*floatval($col)) < (floatval($row['QteDisp']) )) 
					
				{
					 $border="1px solid #ccc;";
				}
				else {
					 $border="2px solid red;";
				}?>
					
					<input id="QteCh" action="QteCh" size="7" col="<?php if( $row['Colisage']=="0") echo "1" ; else echo $row['Colisage']; ?>" 
					onblur="VerifQte('<?php echo $k;?>','<?php echo $row['IdArticle']; ?>',<?php echo floatval($row['QteDisp']); ?>,
					<?php echo intval($row['Colisage']); ?>)" 
					class="QteCh<?php echo $k;?>" value="<?php echo $row['Qte']; ?>"

					STYLE="TEXT-align:right;border:<?php echo $border;?>" onkeypress="return isDecimal(event,this)"    
					name="QteCh[]" type="text"  ></td>
				
					<td  align="right" width="5%" style="">
				<input id="Colisage" action="Colisage" size="3"  class="" value="<?php echo $row['Colisage']; ?>" 						
				name="Colisages[]" type="hidden"   >
				<?php if($row['Colisage']!=0) echo $row['Colisage']; ?>
					</td>
					
				<td width="5%"  align="right">
				<input type="text" name="QteDmdParPiece[]" STYLE="TEXT-align:right;background:none;border:0;"  size="7" Id="QteDmdParPiece<?php echo $k;?>"
				value="<?php echo floatval($row['QteDmdParPiece']); ?>" readonly>
				</td>
			
			
				<td   align="right" width="10%" > 
					<input type="text" name="QteDisp[]" STYLE="TEXT-align:right;background:none;border:0;"
					size="7" Id="QteDisp<?php echo $k;?>" value="<?php echo floatval($row['QteDisp']); ?>" readonly>
					
				</td>
				
					<td  align="center">
					<input type="reset" action="supLigne" value="" onClick="supLigne('<?php echo $ligne; ?>');" style="border:0px;width:16px;cursor:pointer"/></td>
				</tr>
		
		</form>
		
			</div>
			<?php
		}	
	?>
	</table>
		<div id="error"></div>
	<DIV id="ActualisSess"></div>
	<?php
			//echo $_SESSION['totalHT'];
			
	}	
	?>
		<script language="javascript">
				function VerifQte(idLigne,id,QteDisp,colisage){
					
					// recupere la qte chargée ds la session et bdd
		
					var qteChar=0; var qteDisp=0;
					var url="chargements.php?GetQteCh&&IdArticle="+id;

					//$('#error').load(url);
					$('#formAdd').ajaxSubmit({
					   url : url,
					   type : 'POST',
					   dataType : 'html', // On désire recevoir du HTML
					   success : function(code_html, statut){ // code_html contient le HTML renvoyé
						   // QteChargeGlobale;
						
						   code_html=code_html.split(",");
						    qteChar=code_html[0];
						    qteDisp=code_html[1];
							//alert(code_html[0]);
						  // alert(qteDisp);
						      //recupere qte charger ET QTE DISP ds session et bdd moins qte du article selectionner
						 if(colisage==""){ colisage=1;}
						
						  qteChar=parseFloat(qteChar)-(parseFloat($(".QteCh"+idLigne).val())*colisage);
						  qteDisp=parseFloat(qteDisp)+(parseFloat($(".QteCh"+idLigne).val())*colisage);
						    
						// alert(parseFloat($(".QteCh"+idLigne).val())*colisage) ;
								 if((parseFloat($(".QteCh"+idLigne).val())*colisage) > qteDisp){
										alert("Attention,la quantité chargée ne doitpas dépasser la quantité disponible");
										$(".QteCh"+idLigne).css("border","2px solid red");
										//(".QteCh"+id).val();
								}else {
									
									
									$(".QteCh"+idLigne).css("border","1px solid #ccc");
									$("#QteDmdParPiece"+idLigne).val((parseFloat($(".QteCh"+idLigne).val())*colisage));
									$("input[id=QteCh]").each(function(index) {					
								
									//ajout qte chargé avant modif input de qte chargé
				
							if(($(this).attr('col')!="") && ($(this).attr('col')!=0) ) {	
								colisage =parseFloat($(this).attr('col'));
								} 
							else { colisage =1;}
							
							 QteCh+=parseFloat($(this).val()) *  colisage ;		

					});

								}
					   },
					   error : function(resultat, statut, erreur){
						alert(erreur);
						}
				});
				
			
				
					
				//	alert($("#QteDispG").val());
						QteChar=0;
				
					QteDispo=$('table#table1 tr:last input[name=QteDisp]').val();
					//QteDispo= QteDisp;
					/*
					$("input[id=QteCh]").each(function(index) {					
								
									//ajout qte chargé avant modif input de qte chargé
				
							if(($(this).attr('col')!="") && ($(this).attr('col')!=0) ) {	
								colisage =parseFloat($(this).attr('col'));
								} 
							else { colisage =1;}
							
							 QteCh+=parseFloat($(this).val()) *  colisage ;		

					});
						if(QteCh > QteDispo){
						alert('La qantité chargée '+QteCh+' ne doit pas dépasser la quantité du stock : '+QteDispo);
						$(".QteCh"+id).css("border","2px solid red");
						//$(".QteCh"+id).val("");
						
					}
					else {
				
						
						
						$('#formLigne'+id).ajaxSubmit({
														target			:	'#ActualisSess',
														url				:	'chargements.php?ActualisSess',
														method			:	'post'
													}); 
					}*/
					
						$(".QteCh"+id).css("border","1px solid #CCC");
						$("#QteDmdParPiece"+id).val(parseFloat($(".QteCh"+id).val()) *  parseFloat($(".QteCh"+id).attr('col')) );
						
						}
				
				
			</script>
		
		<?php	
exit();
}

if (isset($_GET['GetQteCh'])){

$qteChargementGlobal=0;
 //ACTUALISER session avant de recuperer la qte chargé , il se peut que la superviseur a changé la qte dans la grid 
	
	  if(isset($_POST['QteCh'])){
		    unset($_SESSION["lignes"]);
					 for( $i= 0 ; $i < count($_POST['QteCh']) ; $i++ )
					{
						//echo $_POST['IdArticle'][$i]."  ".$_POST['QteCh'][$i]." ".$_POST['Colisage'][$i]."<br>";

								//ECHO $_POST['QteCh'][$i];return;
									$ligneArray["IdLigne"]=$i;
									$ligneArray["IdArticle"]=$_POST['IdArticle'][$i];
									$ligneArray["CodeaBarre"]=$_POST['CodeBarre'][$i];
									$ligneArray["NomArt"]=$_POST['NomArt'][$i];
									$ligneArray["Unite"]=$_POST['Unites'][$i];
									$ligneArray["Qte"]=$_POST['QteCh'][$i];
									// qte demandé par piece et pas par colisage
									$ligneArray["QteDmdParPiece"]=$_POST['QteDmdParPiece'][$i];								
									$ligneArray["QteDisp"]=$_POST['QteDisp'][$i];								
									$ligneArray["Colisage"]=$_POST['Colisages'][$i];	
									$_SESSION['lignes'][$i]= $ligneArray;	
									
					}
	  }
			$qteChargementGlobal=0;
			//---------------------------select qteChargementGlobal du session de l'article à ajouter ds la grid----------//
			 if( (isset($_SESSION['lignes'])) && (count($_SESSION['lignes'])!=0))  {
				
		
				foreach($_SESSION['lignes'] as $l=>$c)
				{ 
			
			
					if($c["IdArticle"]==$_GET['IdArticle']){
					
					if($c["Colisage"]==0){
					$qteChargementGlobal += floatval($c["Qte"]);}
					else {$qteChargementGlobal += (floatval($c["Qte"])*intval($c["Colisage"]));}
					}
				}
				// echo $qteChargementGlobal."<br>";	
				
			 }
		
			/* else {*/
			//---------------------------select qteChargementGlobal du bdd de l'article à ajouter--------------------------------//
				 	$sql2 = "SELECT isnull(sum(CASE WHEN dc.reste =0 THEN dc.qte ELSE dc.reste END ),0) as qte,
				idColisage
						FROM chargements ch INNER JOIN detailChargements dc on 
						ch.IdChargement=dc.IdChargement WHERE
						dc.IdArticle=? 	group by dc.IdArticle,idColisage";
						 $params1= array($_GET['IdArticle']) ;
				$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	
		
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
				//---------------------------select qteEntreeGlobal--------------------------------//
				$sql = "SELECT isnull(sum(dmo.qte),0) FROM mouvements mo 
				INNER JOIN detailMouvements dmo ON dmo.idMouvement = mo.idMouvement 
				inner join articles a on a.idArticle=dmo.idArticle
				WHERE mo.type LIKE 'Entree'  and 
                 dmo.IdArticle=? 	 group by dmo.idArticle";

				$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteEntreeGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				sqlsrv_fetch($stmt1) ;
				$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);

	$QteDisp=$qteEntreeGlobal-($qteChargementGlobal); 
		echo $qteChargementGlobal.",".$QteDisp;
			
				// la qte dispo ds le stocke bdd
			

				
exit;
}

if (isset($_GET['SelectCmd'])){

$capture_field_val="";
$ligneArray=array();
unset($_SESSION['lignes']);
/*********** selectionner la cmd d'un vendeur**********************/
$sql = "
		SELECT 
		
			qte as Qte,Unite,d.idArticle as IdArticle,d.colisage,c.idCommandeVendeur,
			a.designation as NomArt,a.Reference as CodeaBarre
			from detailcommandeVendeurs d 
			inner join commandeVendeurs c on c.idCommandeVendeur=d.idCommandeVendeur
			inner join articles a on a.idArticle=d.idArticle
			
			where c.idDepot=$IdDepot and c.etat=1 and idVendeur = ?";
	
	 $params = array($_GET['idVendeur']);

	 $resAff = sqlsrv_query($conn,$sql,$params) or die( print_r( sqlsrv_errors(), true));

	 	if( $resAff== false ) {
		$errors = sqlsrv_errors();
		ECHO "Erreur : select cmd ".$errors[0]['message'] . " <br/> ";
		return;
	}
		$IndexLigne=0;
		$Colisage=1;
			while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
					// si le colisage n'est pas specifier on affecte 1		
			if( $row['colisage']!="0") {
				$Colisage=$row['colisage'];	
					
			}
				
			//---------------------------select qteEntreeGlobal--------------------------------//
				$sql = "SELECT isnull(sum(dmo.qte),0) FROM mouvements mo 
				INNER JOIN detailMouvements dmo ON dmo.idMouvement = mo.idMouvement 
				WHERE mo.type LIKE 'Entree'  and 
                 dmo.IdArticle=?";
				 $params1= array($row['IdArticle'],$row['colisage']) ;

				$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteEntreeGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				sqlsrv_fetch($stmt1) ;
				$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
			//---------------------------select qteChargementGlobal--------------------------------//
				$sql2 = "SELECT isnull(sum(CASE WHEN dc.reste =0 THEN dc.qte ELSE dc.reste END ),0) as qte,
				idColisage
						FROM chargements ch INNER JOIN detailChargements dc on 
						ch.IdChargement=dc.IdChargement WHERE
						dc.IdArticle=? 	group by dc.IdArticle,idColisage";
				$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	
		
				if( $stmt2 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteChargementGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				$qteChargementGlobal=0;
				while ($rc =  sqlsrv_fetch_array($stmt2))
				{
					if($rc["idColisage"]==0)
					$qteChargementGlobal += floatval($rc["qte"]);
					else $qteChargementGlobal += (floatval($rc["qte"])*intval($rc["idColisage"]));
				}
				
				
				$QteG=floatval($qteEntreeGlobal)-$qteChargementGlobal;

							$ligneArray["IdLigne"]=$IndexLigne;
							$ligneArray["IdArticle"]=$row['IdArticle'];
							$ligneArray["CodeaBarre"]=$row['CodeaBarre'];
							$ligneArray["Unite"]=$row['Unite'];
							$ligneArray["NomArt"]=$row["NomArt"];
							$ligneArray["Qte"]=$row["Qte"];
							$ligneArray["QteDmdParPiece"]=$row["Qte"]*$Colisage;	
							$ligneArray["QteDisp"]=$QteG;
							$ligneArray["Colisage"]=$row["colisage"];
							//type chargement dans table session commande ou chargement par ajout 
							$ligneArray["idCommandeVendeur"]=$row["idCommandeVendeur"];
							$_SESSION['lignes'][$IndexLigne]= $ligneArray;					
							$IndexLigne+=1;
					
		  }
		


	?>
		<script language="javascript">		
		<?php //echo $_SESSION['ligneCourante']; ?>		
			changerLigne();
			$("#QteDispG").val("<?php echo $QteG ;?>");
				</script>
	<?php

exit;
}


if (isset($_GET['goAddLigne0'])){
/*parcourir($_SESSION["lignes"]);
parcourir($_POST);return;*/
$capture_field_val="";
$ligneArray=array();
$a="";

$tabArt=explode(',',$_POST["ListeArt"]);
$Colisage=0;
$Col=1;
$j=0;
$disp=0;
$qteChargementGlobal=0;
				
	if(isset($_POST['Colisage']) && $_POST['Colisage']!="" ) {
		$Colisage=$_POST['Colisage'];
				if($_POST['Colisage']=="0" ) $Col=1;	else $Col=$_POST['Colisage'];		
	}else {
		$Col=1;
	}
	  //ACTUALISER session avant de recuperer la qte chargé , il se peut que la superviseur a changé la qte dans la grid 
	
	  if(isset($_POST['QteCh'])){
		    unset($_SESSION["lignes"]);
					 for( $i= 0 ; $i < count($_POST['QteCh']) ; $i++ )
					{
						//echo $_POST['IdArticle'][$i]."  ".$_POST['QteCh'][$i]." ".$_POST['Colisage'][$i]."<br>";

								//ECHO $_POST['QteCh'][$i];return;
									$ligneArray["IdLigne"]=$i;
									$ligneArray["IdArticle"]=$_POST['IdArticle'][$i];
									$ligneArray["CodeaBarre"]=$_POST['CodeBarre'][$i];
									$ligneArray["NomArt"]=$_POST['NomArt'][$i];
									$ligneArray["Unite"]=$_POST['Unites'][$i];
									$ligneArray["Qte"]=$_POST['QteCh'][$i];
									// qte demandé par piece et pas par colisage
									$ligneArray["QteDmdParPiece"]=$_POST['QteDmdParPiece'][$i];								
									$ligneArray["QteDisp"]=$_POST['QteDisp'][$i];								
									$ligneArray["Colisage"]=$_POST['Colisages'][$i];
				
								$ligneArray["idCommandeVendeur"]=$_POST['idCommandeVendeur'][$i];									
									$_SESSION['lignes'][$i]= $ligneArray;	
									
					}
	  }
		//---------------------------select qteEntreeGlobal--------------------------------//
				$sql = "SELECT isnull(sum(dmo.qte),0) FROM mouvements mo 
				INNER JOIN detailMouvements dmo ON dmo.idMouvement = mo.idMouvement 
				inner join articles a on a.idArticle=dmo.idArticle
				WHERE mo.type LIKE 'Entree'  and 
                 dmo.IdArticle=? 	 group by dmo.idArticle";
				 $params1= array($tabArt[0]) ;

				$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteEntreeGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				sqlsrv_fetch($stmt1) ;
				$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
			//---------------------------select qteChargementGlobal du session de l'article à ajouter ds la grid----------//
			 if( (isset($_SESSION['lignes'])) && (count($_SESSION['lignes'])!=0))  {
				
				$qteChargementGlobal=0;
				foreach($_SESSION['lignes'] as $l=>$c)
				{
					if($c["IdArticle"]==$tabArt[0]){
					
					if($c["Colisage"]==0){
					$qteChargementGlobal += floatval($c["Qte"]);}
					else {$qteChargementGlobal += (floatval($c["Qte"])*intval($c["Colisage"]));}
					}
				}
				// echo $qteChargementGlobal."<br>";	
				
			 }
		
			/* else {*/
			//---------------------------select qteChargementGlobal du bdd de l'article à ajouter--------------------------------//
				 	$sql2 = "SELECT isnull(sum(CASE WHEN dc.reste =0 THEN dc.qte ELSE dc.reste END ),0) as qte,
				idColisage
						FROM chargements ch INNER JOIN detailChargements dc on 
						ch.IdChargement=dc.IdChargement WHERE
						dc.IdArticle=? 	group by dc.IdArticle,idColisage";
				$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	
		
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
			 
		 //echo $qteEntreeGlobal;return;
			
				// la qte dispo ds le stocke bdd
			
		//---------------------------la qte disponible--------------------------------//
				$QteDisp=$qteEntreeGlobal-($qteChargementGlobal);
				
					
	   if( (isset($_SESSION['lignes'])) && (count($_SESSION['lignes'])!=0))  {
			$t=0;	
			
				
			 foreach($_SESSION['lignes'] as $ligne=>$contenu){
				 
						// controler si  table session contient deja la ligne avec  mm article et mm colisage	
					if(($contenu["IdArticle"]==$tabArt[0]) && ($contenu["Colisage"]==$Colisage)  )
					{					
						?>
						<script language="javascript" type="text/javascript">
							alert('Attention,l\'article <?php echo $_GET["NomArt"];?> est déjà ajouté avec même colisage  ');
						</script>
						<?php
						
						$t=0;break;				
					}
					
					else {					
					
						
						$t=$t+1;
					
					}
			  }
				// controler si  la qte saisie avec les qte ajoutées ds la table html	
			
		
	  		
			if($t!=0){
			 if (  (floatval($_POST["Qte"])*$Col) > $QteDisp ) {
						
							?>
						<script language="javascript" type="text/javascript">
							alert('Attention,la quantité chargée  doit être inférieur à la quantité du stock. ');
							
						</script>
						<?php
						$disp=0;
						
			}
			else {					
				$disp+=1;						
			}
		}	
					
			

			  // si l'article avec mm qte n'existe pas on l'ajoute
			  if(($t!=0) && ($disp!=0)){		
			
							$IndexLigne=count($_SESSION['lignes']);							
						// si la qte et Colisage vide en l'ajoute pas				
							$ligneArray["IdLigne"]=$IndexLigne;
							$ligneArray["IdArticle"]=$tabArt[0];
							$ligneArray["CodeaBarre"]=$tabArt[1];
							$ligneArray["NomArt"]=$_GET["NomArt"];
							$ligneArray["Unite"]=$_POST["Unite"];
							$ligneArray["Qte"]=$_POST["Qte"];
							// qte demandé par piece et pas par colisage
							$ligneArray["QteDmdParPiece"]=$_POST["Qte"]*$Col;	
							$ligneArray["idCommandeVendeur"]="";		
							$ligneArray["QteDisp"]=$QteDisp;	
							
							$ligneArray["Colisage"]=$Colisage;
							$_SESSION['lignes'][$IndexLigne]= $ligneArray;	
										
												
				  }
		  }
		  else {// une premiere insertion sans controle
						 //$IndexLigne+=1;
			
					//echo $QteCharg ;RETURN;	
					
					if (  (FLOATval($_POST["Qte"])*$Col) > $QteDisp ) {
							
								?>
							<script language="javascript" type="text/javascript">
								alert('Attention,la quantité chargée  doit être inférieur à la quantité du stock. ');
								changerLigne();
							</script>
							<?php
							$j=0;
						}
						else {					
							$j+=1;						
						}
					
								if($j!=0){
								$IndexLigne=0;
							
								 $ligneArray["IdLigne"]=$IndexLigne;
								$ligneArray["IdArticle"]=$tabArt[0];
								$ligneArray["CodeaBarre"]=$tabArt[1];
								$ligneArray["Unite"]=$_POST["Unite"];
								 $ligneArray["NomArt"]=$_GET["NomArt"];
								$ligneArray["Qte"]=$_POST["Qte"];
									// qte demandé par piece et pas par colisage
									$ligneArray["idCommandeVendeur"]="";	
								$ligneArray["QteDmdParPiece"]=$_POST["Qte"]*$Col;	
								$ligneArray["QteDisp"]=$QteDisp;	
								$ligneArray["Colisage"]=$Colisage;
								$_SESSION['lignes'][$IndexLigne]= $ligneArray;
							
								}
					
		  }
		?>
								<script language="javascript">		
								<?php //echo $_SESSION['ligneCourante']; ?>		
									changerLigne();
									
										</script>
							<?php


		


exit;
}

if (isset($_GET['goAddLigne'])){

$capture_field_val="";
$ligneArray=array();
$a="";

$tabArt=explode(',',$_POST["ListeArt"]);
$Colisage=0;
//---------------------------select qteEntreeGlobal--------------------------------//
				$sql = "SELECT isnull(sum(dmo.qte),0),a.Unite FROM mouvements mo 
				INNER JOIN detailMouvements dmo ON dmo.idMouvement = mo.idMouvement 
				inner join articles a on a.idArticle=dmo.idArticle
				WHERE mo.type LIKE 'Entree'  and 
                 dmo.IdArticle=? 	 group by dmo.idArticle,Unite";
				 $params1= array($tabArt[0]) ;

				$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteEntreeGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				sqlsrv_fetch($stmt1) ;
				$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
			//---------------------------select qteChargementGlobal--------------------------------//
				$sql2 = "SELECT isnull(sum(CASE WHEN dc.reste =0 THEN dc.qte ELSE dc.reste END ),0) as qte ,a.Unite
						FROM chargements ch INNER JOIN detailChargements dc on ch.IdChargement=dc.IdChargement
						inner join articles a on a.idArticle=dc.idArticle
						WHERE dc.IdArticle=?  group by dc.idArticle,Unite";
				$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	
				if( $stmt2 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteChargementGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				$rowC = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
				$qteChargementGlobal = $rowC['qte'];
				$Unite = $rowC['Unite'];
				
	if(isset($_POST['Colisage']) && $_POST['Colisage']!="")  $Colisage=$_POST['Colisage'];		
	   if( (isset($_SESSION['lignes'])) && (count($_SESSION['lignes'])!=0))  {
		$t=0;	
		
			
			 foreach($_SESSION['lignes'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article et mm colisage				
				
					if(($contenu["IdArticle"]==$tabArt[0]) && ($contenu["Colisage"]==$Colisage)  )
					{					
						?>
						<script language="javascript" type="text/javascript">
							alert('Attention,l\'article <?php echo $_GET["NomArt"];?> est déjà ajouté avec même colisage  ');
						</script>
						<?php
						
						$t=0;break;				
					}
					else {					
						$t+=1;						
					}
			  }
			  // si l'article avec mm qte n'existe pas on l'ajoute
			  if($t!=0){
			  			$IndexLigne=count($_SESSION['lignes']);							
						// si la qte et Colisage vide en l'ajoute pas
				
							$ligneArray["IdLigne"]=$IndexLigne;
							$ligneArray["IdArticle"]=$tabArt[0];
							$ligneArray["CodeaBarre"]=$tabArt[1];
							$ligneArray["NomArt"]=$_GET["NomArt"];
							$ligneArray["Unite"]=$_POST["Unite"];
							$ligneArray["Qte"]=$_POST["Qte"];
						
							$ligneArray["QteDisp"]=$qteEntreeGlobal-$qteChargementGlobal;	
							
							$ligneArray["Colisage"]=$Colisage;
							$_SESSION['lignes'][$IndexLigne]= $ligneArray;		
												
				  }
		  }
		  else {// une premiere insertion sans controle
						 //$IndexLigne+=1;
			
								$IndexLigne=0;
							
								 $ligneArray["IdLigne"]=$IndexLigne;
								$ligneArray["IdArticle"]=$tabArt[0];
								$ligneArray["CodeaBarre"]=$tabArt[1];
								$ligneArray["Unite"]=$Unite;
								 $ligneArray["NomArt"]=$_GET["NomArt"];
								$ligneArray["Qte"]=$_POST["Qte"];
						
								$ligneArray["QteDisp"]=$qteEntreeGlobal-$qteChargementGlobal;	
								$ligneArray["Colisage"]=$Colisage;
								$_SESSION['lignes'][$IndexLigne]= $ligneArray;
					
		  }
	
   
	?>
		<script language="javascript">		
		<?php //echo $_SESSION['ligneCourante']; ?>		
			changerLigne();
				</script>
	<?php

exit;
}


if(isset($_GET['goMod'])){


exit;
	
}


if(isset($_GET['goAdd'])){
	   if( (!isset($_SESSION['lignes'])) || (count($_SESSION['lignes'])==0))  {
				     ?>
				<script type="text/javascript"> 
					alert('Merci d\'ajouter les articles ');
					
				</script>
		
		<?php
			   return;}
			   
		$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}

//-----------------Add Sortie du stock dans la table mouvement----------------//

//********* creation du sortie **************/;

$reqS="  type like ? ";
$paramsFonc= array('Sortie');
$RefSortie= "NS".Increment_Chaine_F("Reference","mouvements","idMouvement",$conn,$reqS,$paramsFonc);

$DateSortie=date("Y-m-d H:i:s");
$DateSortie = date_create(date("Y-m-d"));
//echo date_format($DateSortie, 'Y-m-d H:i:s');

$HeureSortie=date("H:i:s");

$reqInserS = "INSERT INTO mouvements ([reference] ,[idOperateur]   ,[date],[heure],
					[idDepot],[type],fournisseur) values 	(?,?,?,?,?,?,?)";
$paramsS= array(
				$RefSortie,
				$Operateur,			
				$DateSortie,$HeureSortie,$IdDepot,'Sortie',''
) ;
$stmtS = sqlsrv_query( $conn, $reqInserS, $paramsS );

if( $stmtS== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout de sortiee ".$errors[0]['message'] . " <br/> ";
}


//-----------------Add Fiche chargement----------------//

//********* creation numFiche **************/;
$RefFicheCh= "NC".Increment_Chaine_F("numChargement",$tableInser,"IdChargement",$conn,"",array());
//echo $RefFicheCh;return;

$reqInser1 = "INSERT INTO ".$tableInser." ([numChargement] ,[operateur]  ,[idVendeur]  ,[date],[idDepot],etat) 
				values 	(?,?,?,?,?,?)";
		//	echo $reqInser1;
$params1= array(
				$RefFicheCh,
				$Operateur,
				$_POST['Vendeur'],
				$DateSortie,$IdDepot,1
				
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout fiche chargement ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IDFiche--------------------------------//
$sql = "SELECT max(IdChargement) as IdFiche FROM chargements";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdFiche chargement: ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFiche = sqlsrv_get_field( $stmt2, 0);
//----------------------Add Detail fiche --------------------------//

 for( $i= 0 ; $i < count($_POST['QteCh']) ; $i++ )
{
	//echo $_POST['IdArticle'][$i]."  ".$_POST['QteCh'][$i]." ".$_POST['Colisage'][$i]."<br>";


	$reqInser2 = "INSERT INTO  detailchargements([idArticle],[qte],[idColisage],IdChargement,ecart,reste,etat ) values (?,?,?,?,?,?,?)";
			$params2= array($_POST['IdArticle'][$i],$_POST['QteCh'][$i],$_POST['Colisages'][$i],$IdFiche,0,0,0) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout de sortiee ".$errors[0]['message'] . " <br/> ";
				break ;
			}			
}
//----------------------modifier etat de la commande du 1 à 0 --------------------------//

 for( $i= 0 ; $i < count($_POST['idCommandeVendeur']) ; $i++ )
 {
	 if($_POST['idCommandeVendeur']!=""){
		 $reqInserUp = "update   commandeVendeurs set etat=0 where idCommandeVendeur = ? ";
			$paramsUp= array($_POST['idCommandeVendeur'][$i]) ;
			$stmtUp = sqlsrv_query( $conn, $reqInserUp, $paramsUp );
			if( $stmtUp === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : modif etat cmd ".$errors[0]['message'] . " <br/> ";
				break ;
			}	
	 }
 }
if( ($error=="" ) && ($RefFicheCh!="Nserror")) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			alert('L\'ajout a été effectué.');
			$('#box').dialog('close');
			Imprimer("<?php echo $IdFiche;?>");
			rechercher();
			
		</script>
		
<?php
unset($_SESSION['lignes']);
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}
/***********************/	
exit;
	
}
if(isset($_GET['goAdd0'])){

return;
	   if( (!isset($_SESSION['lignes'])) || (count($_SESSION['lignes'])==0))  {
				     ?>
				<script type="text/javascript"> 
					alert('Merci d\'ajouter les articles ');
					
				</script>
		
		<?php
			   return;}
			   
		$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}

//-----------------Add Sortie du stock dans la table mouvement----------------//

//********* creation du sortie **************/;

$reqS="  type like ? ";
$paramsFonc= array('Sortie');
$RefSortie= "NS".Increment_Chaine_F("Reference","mouvements","idMouvement",$conn,$reqS,$paramsFonc);

$DateSortie=date("Y-m-d");
$HeureSortie=date("H:i:s");

$reqInserS = "INSERT INTO mouvements ([reference] ,[idOperateur]   ,[date],[heure],
					[idDepot],[type],[fournisseur]) values 	(?,?,?,?,?,?,?)";
					echo $reqInserS;return;
$paramsS= array(
				$RefSortie,
				$Operateur,			
				$DateSortie,$HeureSortie,$IdDepot,'Sortie',''
) ;
$stmtS = sqlsrv_query( $conn, $reqInserS, $paramsS );

if( $stmtS== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout de sortiee ".$errors[0]['message'] . " <br/> ";
}

//-----------------Add Fiche chargement----------------//

//********* creation numFiche **************/;
$RefFicheCh= "NC".Increment_Chaine_F("numChargement",$tableInser,"IdChargement",$conn,"",array());
//echo $RefFicheCh;return;
$DateSortie=date("Y-m-d");
$HeureSortie=date("H:i:s");
$reqInser1 = "INSERT INTO ".$tableInser." ([numChargement] ,[operateur]  ,[idVendeur]  ,[date],[idDepot]) 
				values 	(?,?,?,?,?)";
$params1= array(
				$RefFicheCh,
				$Operateur,
				addslashes(mb_strtolower(securite_bdd($_POST['Vendeur']), 'UTF-8')),
				$DateSortie,$IdDepot
				
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout fiche chargement ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IDFiche--------------------------------//
$sql = "SELECT max(IdChargement) as IdFiche FROM chargements";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdFiche chargement: ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFiche = sqlsrv_get_field( $stmt2, 0);
//----------------------Add Detail fiche --------------------------//

//parcourir($_SESSION['lignes']);return;
 foreach($_SESSION['lignes'] as $ligne=>$contenu)
{

	$reqInser2 = "INSERT INTO  detailchargements([idArticle],[qte],[idColisage],IdChargement ) values (?,?,?,?)";
			$params2= array($contenu["IdArticle"],$contenu["Qte"],$contenu["Colisage"],$IdFiche) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout de sortiee ".$errors[0]['message'] . " <br/> ";
				break ;
			}			
}
if( ($error=="" ) && ($RefFicheCh!="Nserror")) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			alert('L\'ajout a été effectué.');
			$('#box').dialog('close');
			Imprimer("<?php echo $IdFiche;?>");
			rechercher();
			
		</script>
		
<?php
unset($_SESSION['lignes']);
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}
/***********************/	
exit;
	
}
if (isset($_GET['mod'])){
	unset($_SESSION['lignes']);
	$ID= $_GET['ID'] ;
	$sql = "select * from $tableInser where idFiche = '$ID' ";
	//execSQL($sql);
	//echo $sql; return;
	$res= sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	
	$row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
          
?>


<div id="resAdd" style="padding:5px">&nbsp;</div>

<form id="formAddGlo" action="NULL" method="post" name="formAddGlo"> 
			<div id="resAdd"></div>
<table width="101%" border="0" align="center" >
			  <tr>
				<td width="23%" valign="middle" >
				  <input type="hidden" value="<?php echo $ID ;?>" name="IdFiche" />
				<div class="etiqForm" id="SYMBT" > Client/Groupe client : </div>				</td>
				
				<td width="30%">
			
				<div align="left">
			<?php                              
						  $Options = "";
				      
								
						$reqClt="select nom,prenom ,t.idTypeClient,type, designation from ficheColisages t 
						left join clients clt on clt.idClient=t.idTypeClient
						left join typeClients tc on tc.idType=t.idTypeClient 
						where idFiche  =".$ID;		
			
						   $resClt = sqlsrv_query( $conn, $reqClt, array(), array( "Scrollable" => 'static' ) );	
						   $CltGrp="";
						   if( $resClt === false )  
							{  
							  if( ($errors = sqlsrv_errors() ) != null)  
							  {  
								$errors = sqlsrv_errors();
								echo "Erreur : $reqClt ".$errors[0]['message'] . " <br/> ";
							  }  
							} else{
								$rowC = sqlsrv_fetch_array($resClt, SQLSRV_FETCH_ASSOC);
								$CltGrp= $rowC['nom'];
								if($rowC['type']=="Groupe") $CltGrp=$rowC['designation'];
								else $CltGrp=$rowC['nom']." ".$rowC['prenom'];
								
							}
                                     		    ?>
				<input id="GroupeClt" value="<?php echo $rowC['idTypeClient'];?> "name="GroupeClt" type="hidden"/>	
				
				<input id="GroupeClt" value="<?php echo $CltGrp;?> "name="GroupeClt" type="text" />
	
						</div>
				</td>
		 </tr>
		
	 	 </table>
		 </form>
        	<BR>
<DIV class="arti">
<form id="formAdd" action="NULL" method="post" name="formAdd1"> 
			<table border="0" cellpadding="5" cellspacing="5">
			<tr>
					<td  valign="top" style="width:130px"><div class="etiqForm"  style="text-align:left; " 
			id="DATE_PIECE" > <strong>Article</strong> : </div>
            </td>
			</tr>
			<tr>
			<td>
		<div id="grpCaisse" style=" width:300px; float:left;"><select style="width:240px; vertical-align:top"
		multiple="multiple" name="ListeArt" id="ListeArt"  tabindex="3" class="ListeArt" >
		

			  <?php 
				$sql = "select IdArticle, Designation from articles ";
  	            $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
				while ($donnees =  sqlsrv_fetch_array($reponse))
				{
				?>
				<option value="<?php echo $donnees['IdArticle'] ?>"><?php echo $donnees['Designation']?></option>
			  <?php
			   }
			  ?>

			</select>
				</div>      
            </td>
     
          </tr>
	  <!-- debut div Colisage par qte -->
			<tr>	
				    <td valign="top">
					<SPAN class="etiqForm" id="DATE_PIECE" ><strong>Quantité Min:</strong></SPAN>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <SPAN class="etiqForm" id="DATE_PIECE" ><strong>Colisage:</strong></SPAN></td>
				  </tr>
				  <tr>
					<td valign="top" >
			<div class="input_fields_wrap">
			
				<div style="width:auto; float:left; margin-bottom:10px;">
				
				<input id="x1" action="mytext"  class="inputDuree" size="10" type="text"  name="mytext[]" onkeypress="return isEntier(event,this)" /> 
			&nbsp;&nbsp;&nbsp;&nbsp;
			
				 <input id="y1" action="mytext2" size="10"  class=""   onkeypress="return isDecimal(event,this)"  type="text"  name="mytext2[]" /> 
					<div style=" float:RIGHT; width:24px ;margin:0 5px">
					<input type="button" class="add_field_button"/>	
					</div>
				</div>
			</div>
			</td>

			
		</tr>
		<TR><TD>
		  <input name="button3" type="button" title="Ajouter " class="bouton32" onClick="AjoutLigne();" value="Ajouter " 
		  action="ajout" style="width:150px;" />
		</TD>
		</TR>
		</table>
		</form>


</div>
<DIV class="divList">

<h3>Liste des articles</h3>
	<div id="listBox" style="">
	<?php 
				$sqlArt = "	select t.idArticle,qteMin,pvHT, Designation from Colisages t
							inner join articles a on t.idArticle=a.idArticle
							where t.idFiche='$ID'";

  	            $smtA=sqlsrv_query( $conn, $sqlArt, array(), array( "Scrollable" => 'static' ) );	
				
				$IndexLigne=0;
				while ($rowArt =  sqlsrv_fetch_array($smtA, SQLSRV_FETCH_ASSOC))
				{
								$ligneArray["IdLigne"]=$IndexLigne;
								 $ligneArray["IdArticle"]=$rowArt["idArticle"];
								 $ligneArray["NomArt"]=$rowArt["Designation"];
								$ligneArray["Qte"]=$rowArt["qteMin"];
								$ligneArray["Colisage"]=$rowArt["pvHT"];
								$_SESSION['lignes'][$IndexLigne]= $ligneArray;
								$IndexLigne+=1;
				}
				if(isset($_SESSION['lignes']) && count($_SESSION['lignes']) != 0){
		
			$i=0;
			?>
			<table width="100%">
			     <tr class="entete">
					<td>Article </td>
					<td > Quantité </td>
					 <td> Colisage </td>
					<td  colspan="2">
					
					</td>
			  </tr>
			<?php
			$k=0;
			foreach($_SESSION['lignes'] as $ligne=> $row){
				if($i==0){
					$ligne1=' style="border-top:1px solid #778;"';
				}else{
					$ligne1='';
				}
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			
				?>
			<div onDblClick="modLigne('<?php echo $ligne; ?>');" class="ligneEdit" style="margin-bottom:-10px;">
	
			<form id="formLigne" action="" method="post" name="<?php echo $ligne; ?>" >
			
				<tr  class="<?php echo $c; ?>">
				
					<td width="110"><?php echo $row['NomArt']; ?></td>
					<td width="230" align="right"><?php echo $row['Qte']; ?></td>
					<td width="50" align="right" style=""><?php echo $row['Colisage']; ?>	</td>
				
					<td width="" align="center">
					<input type="reset" action="supLigne" value="" onClick="supLigne('<?php echo $ligne; ?>');" style="border:0px;width:16px;cursor:pointer"/></td>
				</tr>
		
			</form>
			</div>
			<?php
		}	
	?>
	</table>
	<?php
			sqlsrv_free_stmt( $smtA );  
			sqlsrv_close( $conn );  
			//echo $_SESSION['totalHT'];
			
	}
	
	?>
	</div>
	
<div class="msgErreur">&nbsp;</div>
</div>

<script language="javascript" type="text/javascript">


$(document).ready(function(){

		/*****************/
  var max_fields      = 8; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var xx = 1; //initlal text box count
    $(add_button).click(function(e){ 
	
//************************************************************************
		var X = [];
$('input:text[action=mytext]').each(function() {
		//alert("valeur : " +this.value);
		X.push({"value":this.value,"id":this.id});//alert("id of "+this.value + " : " + this.id);
		});
;
var Y = [];
$('input:text[action=mytext2]').each(function() {
		//alert("valeur : " +this.value);
		Y.push({"value":this.value,"id":this.id});
		});
;

////////////////////Valeur Vide QUANTITE////////////////////////////

for(i = 0; i < X.length; i++)
	{
		if(X[i].value  == "")
		{
			//var indice=i+1;
			
			$('#'+X[i].id).css({'border':'1px solid red'});
			alert("Veuillez saisir la quantité");
			return;
		}else{
			//	var indice=i+1;
				$('#'+X[i].id).css({'border':'1px solid #ccc'});
				$('#'+Y[i].id).css({'border':'1px solid #ccc'});
		}
		
}
/***************************-------------X Y dupliqué----------*****************************/

var duplicate = find_duplicate_value(X);     
//alert( ( (duplicate === null) ? "Nothing" : duplicate ) + ' is duplicated');
if(duplicate != null) 
{
		var indice=duplicate+1;
		$('#x'+indice).focus();
		$('#x'+indice).css({'border':'1px solid red'});
		
		alert("Quantité dupliquée");
		return;	
}


////////////////////Valeur Vide Colisage////////////////////////////
for(i = 0; i < X.length; i++)
{
	
		if(Y[i].value == "")
		{
			//var indice=i+1;
			$('#'+Y[i].id).css({'border':'1px solid red'});
			alert("Veuillez saisir la Colisage");
			return;
		}else{
				//var indice=i+1;
				$('#'+X[i].id).css({'border':'1px solid #ccc'});
				$('#'+Y[i].id).css({'border':'1px solid #ccc'});
		}
}

/////////////////////////////////////////////////////
	

	//on add input button click
        e.preventDefault();	
        if(xx < max_fields){ //max input box allowed
            xx++; //text box increment
          $(wrapper).append('<div id="ligne" style="width:320px; margin-bottom:10px;clear:both;" > <input id="x'+xx+'" size="10" action="mytext"  class="inputDuree" type="text"  name="mytext[]" onkeypress="return isEntier(event,this)" /> &nbsp;&nbsp;&nbsp;&nbsp; <input id="y'+xx+'"  size="10" onkeypress="return isDecimal(event,this)"  action="mytext2" class="inputDuree" type="text"  name="mytext2[]" />&nbsp;&nbsp;<div style="width:20px; display:inline-block "><input type="button"  class="remove_field" ></input></div> </div> ');
        }


    });
	
	  $(wrapper).on("click",".remove_field", function(e){
	 //user click on remove text
        e.preventDefault(); $(this).parent('div').parent('div').remove(); xx--;
    })
});


 $('#ListeArt').multipleSelect({

	   filter: true,placeholder:'S&eacute;lectionnez l\'article ',single:true,maxHeight: 100
	});

</script>


<?php
	
exit;
}

if (isset($_GET['add'])){
?>
<div id="resAdd" style="padding:5px">&nbsp;</div>

<form id="formAdd" action="NULL" method="post" name="formAdd"> 
			<div id="resAdd"></div>
<table width="51%" border="0" align="center" style="margin:0 auto;">
			  <tr>
				<td  valign="top" >
				<div class="etiqForm" id="SYMBT" > Vendeur: </div>				</td>
				<td>              
					<select style="width:340px; vertical-align:top"
				multiple="multiple" name="Vendeur" id="Vendeur"  tabindex="3" class="Vendeur" >
				

					  <?php 
						$sql = "select idVendeur, nom,prenom from vendeurs ";
						$reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
						while ($donnees =  sqlsrv_fetch_array($reponse))
						{
						?>
						<option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['nom']." ".$donnees['prenom'];?></option>
					  <?php
					   }
					   		sqlsrv_free_stmt( $reponse );  
					
					  ?>

					</select>
			</td>
				
				</tr>
			
		
	 	 </table>	<BR>
		 <DIV class="divList"  style="Width:940px;">

<h3>Liste des articles :</h3>
	<div id="listBox" ></div>
	
<div class="msgErreur">&nbsp;</div>
</div>
	
        	
<DIV class="arti" style="Width:300px;">

			<table border="0" cellpadding="5" cellspacing="5">
			<tr>
					<td  valign="top" colspan="2"><div class="etiqForm"  style="text-align:left; " 
			id="DATE_PIECE" > <strong>Article</strong> : </div>
            </td>
			</tr>
			<tr>
			<td  colspan="2" >
		<div id="grpCaisse" style="  float:left;">
		<?php 
				$sql = "select IdArticle, Designation,Reference as CodeBarre,Unite from articles ";
  	            $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	
				$option="";				
				while ($donnees =  sqlsrv_fetch_array($reponse))
				{
				$option.='<option value='.$donnees['IdArticle'].','.$donnees['CodeBarre'].','.$donnees['Unite'].'>'.$donnees['Designation'].'</option>';
				
			   }
			 
			  ?>
			  <select style="width:240px; vertical-align:top" multiple="multiple" name="ListeArt" id="ListeArt"  tabindex="3" class="ListeArt" >
		
			  <?php echo $option;?>		  

			</select>
			
			<input type="hidden" value="<?php //echo $donnees['Unite']; ?>"  name="Unite" id="Unite">
				</div>      
            </td>
		
     </tr>
          	<tr>
			
				    <td valign="top">
					<SPAN class="etiqForm" id="DATE_PIECE" ><strong>Quantité: </strong></SPAN>
					</td>
							<td>
				  <SPAN class="etiqForm" id="DATE_PIECE" ><strong>Colisage:</strong></SPAN></td>
					   </tr>
          	<tr>
					<td valign="top" >
				<input id="Qte" action="mytext"  class="inputDuree" size="10" type="text"  name="Qte" onkeypress="return isEntier(event,this)" /> 
				</td>
			
			
				
					<td valign="top" >
			   <select  name="Colisage" id="Colisage"  multiple="multiple" tabindex="3" style="width:100px;" 
			   class="Select Colisage">
			   <option value="0"></option>
				<option value="5">5</option>
				<option value="7">7</option>
				<option value="10">10</option>	
				<option value="12">12</option>	
				<option value="16">16</option>	
				<option value="20">20</option>					
				<?php /*mysql_query("SET NAMES UTF8"); 
					echo ChargerSelect("activite","NomActivite","IdActivite");*/?>
			</select>
		
				</td>

			
		</tr>
		<TR><TD  colspan="2">
		  <input name="button3" type="button" title="Ajouter l'article" class="bouton32" onClick="AjoutLigne();" 
		  value="Ajouter l'article" 
		  action="ajout" style="width:150px;" />
		</TD></tr>
		<tr>
			<td colspan="2">
			<div id="resart"></div>
			<div id="InfoArticle">		<table border="0" width="100%">
					<tr><td width="260" align="right" valign="top">La quantité disponible :</td>
					<td valign="top"><strong><input id="QteDispG" value=""></strong></td>
					<tr><td align="left" valign="top">Unité :</td><td valign="top"><strong>
					<input id="UniteArt" value="">
					</strong></td></tr>
					<td align="left" valign="top">Colisage:</td><td valign="top"><strong>
						<input id="ColArt" value="">
						</strong></td>
				</tr>
			</table></div>

			</td>
		</TR>
		</table>
		</form>


</div>
	 </form>
       	
<script language="javascript" type="text/javascript">


$(document).ready(function(){

 $('#Vendeur').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez le vendeur ',single:true,maxHeight: 100,
	   
	     onClick: function(view) {
         //alert(view.label + '(' + view.value + ') ');		
		 	$('#listBox').load('chargements.php?SelectCmd&idVendeur='+view.value);
                  //  (view.checked ? 'checked' : 'unchecked'));
            }
	});
  //$("#Vendeur").multipleSelect("setSelects", [1]);
  
 $('#Colisage').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez le colisage ',single:true,maxHeight: 160
	});

 $('#ListeArt').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez l\'article ',single:true,maxHeight: 200,
	     onClick: function(view) {
			var res = view.value.split(",");		 
			$("#Unite").val(res[2]);
                  //  (view.checked ? 'checked' : 'unchecked'));
			$("#UniteArt").val(res[2]);
				  $("#resart").load('chargements.php?InfoArticle&IdArticle='+res[0]+'&Unite='+res[2]);
            }
			
			
	});
});
</script>


<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){
	
unset($_SESSION['lignes']);
	$IdCltGrp=""; $params = array();
	$sqlA = "
		SELECT IdChargement as idFiche,numChargement as numFiche,v.nom ,v.prenom,date
		as dateFiche FROM chargements 
		t inner join vendeurs v on v.idVendeur=t.idVendeur where t.idDepot=1 
	
			
		";
	/*********** rech par Vendeur *****************/
	if((isset($_POST["Vendeur"])) && ($_POST["Vendeur"]!="")){
		$Vendeur=$_POST["Vendeur"];
			$sqlA.= " and t.idVendeur = ? ";
			 $params = array($Vendeur);
	}

	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes."<br>";
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "IdChargement";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "DESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = " ORDER BY $cTri $oTri ";//LIMIT $min,$max ";

	$sql=$sqlA.$sqlC;

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
					<div class="resAff">
						<br><br>
						Aucun r&eacute;sultat &agrave; afficher.
					</div>
					<?php
		}
else
{
	?>
		<form id="formSelec" method="post">
	<table width="100%" border="0">
      <tr class="entete">
        <td width="20%">Numéro chargement </td>
        <td width="50%"> Vendeur  </td>
		
		<td width=""> Date  </td>
		<td></td>
        <td width="20%" colspan="2" style="display:none">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value="" />
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td>
  </tr>

<!--<div id="cList">-->
	<?php
		$i=0;
	
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
			
		
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $c; ?>">
				<td align="left"  > <?php  echo $row['numFiche']; ?> </td>
				<td align="left" > <?php 	echo stripslashes($row['nom'])." ".stripslashes($row['prenom']);?> </td>
				<td align="center"><?php echo date_format($row['dateFiche'], 'm/d/Y');?></td>	
				<td>    <input type="button" style="display:none" title="Imprimer "   value="" class="Imprimer tool" onClick="Imprimer('<?php echo $row['idFiche']; ?>');" />
				  <input type="button" title="Détails de chargement"   value="détails" class=" tool detail"
				  onClick="Imprimer('<?php echo $row['idFiche']; ?>');" />
				  </td>
			   <td align="center" style="display:none">
				<input type="checkbox" class="checkLigne" name="<?php	echo $row['idFiche']; ?>" value="<?php	echo $row['idFiche']; ?>" />
			  </td>
			  </tr></li>
			 <?php
			$i++;
		}
		
	?>	
    </table>
	<!--</div>-->
    </form>

<?php 
	}
exit;}
?>
<?php include("header.php"); ?>
<div class="pageBack" >


<div id="box"> </div>
<div class="contenuBack">
	<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;Gestion de stock &nbsp;<img src="images/tri.png" />
		&nbsp;Chargements</div>

	

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	<!--Recherche CH -->
		<table width="100%" border="0"  >
			  <tr>
			
				<td  align="right">
				<div class="etiqForm" id="SYMBT" > Vendeur : </div>				</td>
				
				<td width="30%">
				<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
				<div align="left" >
	
			 	<select style="width:240px; vertical-align:top"
				multiple="multiple" name="Vendeur" id="VendeurR"  tabindex="3" class="VendeurR" >
				
<option value="">Tout sélectionner</option>
					  <?php 
						$sql = "select idVendeur, nom,prenom from vendeurs ";
						$reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
						while ($donnees =  sqlsrv_fetch_array($reponse))
						{
						?>
						<option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['nom']." ".$donnees['prenom'];?></option>
					  <?php
					   }
					   		sqlsrv_free_stmt( $reponse );  
					
					  ?>

					</select>
						</div>
				</td>
			
		      <td rowspan="2"  >	<span class="actionForm">      
          <input name="button" type="button" id="Rechercher"  onClick="rechercher();" value="Rechercher" class="bouton32" action="rech" title="Rechercher "//>
			      <input name="button2" type="reset" onClick="" value="Effacer" class="bouton32" action="effacer" title="Effacer"/></span><br/></td>
			  <td width="25%" rowspan="2"   style="" align="center"><span class="actionForm">
			    <input name="button3" type="button" title="Ajouter " class="bouton32" onClick="ajouter();" value="Ajouter" action="ajout" style="width:150px;" />
			  </span></td>
			</tr>
	 	 </table>
      </div>
      <div id="formFiltre" style=" display:none">
		<table border=0 style=" width:400px ; margin:auto;">
			<tr height="20">
			  <td width="23%"><div id="filtreNPP">
			  	R&eacute;sultats par page : <select name="npp" id="npp" onChange="filtrer();">
					<option value="10">10</option>
					<option value="20" >20</option>
					<option value="50" selected="selected">50</option>
					<option value="100">100</option>
				</select>
				
			  </div></td>
			  <td width="12%">Pages : <span id="cont_pages">
			    <select name="pact"><option value=1>1</option></select>
				</span>
		  	  </td>
				<!--<td width="23%">Crit&egrave;re de tri : 
				  <select name="cTri" onChange="filtrer();">
				<option value="NomCaisse"> Nom Caisse </option>
				<option value="Ville"> Ville </option>
      
				
				</select
		  	  </td>-->
			  <!--<td width="36%">Ordre de tri : 
				  <select name="oTri" onChange="filtrer();">
				<option value="ASC"> Croissant </option>
				<option value="DESC" selected> Decroissant </option>
				</select-->
			 
			</tr>
		</table>
	</div>
	</form>
			
		
	
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>

									
						
<div id="brouillon" style="display:block">  </div> 
<div id="formRes"  style="overflow-y:scroll;min-height:280px;width:800px;"></div>
<input type="hidden" id="act"/>
  </div>

	<?php include("footer.php"); ?>
</div>

<script language="javascript" type="text/javascript">
 $('#VendeurR').multipleSelect({

	   filter: true,placeholder:'S&eacute;lectionnez la vendeur ',single:true,maxHeight: 200
	});
	  $("#VendeurR").multipleSelect("uncheckAll");


function suppression(idPos){

			jConfirm('Confirmer la suppression ?', null, function(r) {
						if(r)	{
							$('#act').attr('value','supp'); 
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'chargements.php?suppression&idPos='+idPos,clearForm:false});		
						}
					})
	//	$('#box').load(url).dialog('open');
	
}
function modification(nomPromo){

		$('#act').attr('value','modif'); 
		var url='chargements.php?modification&nompromo='+nomPromo ;
	
		$('#box').load(url).dialog('open');
	
}
	function filtrer(){
	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'chargements.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){

		$('#formRechF').ajaxSubmit({target:'#formRes',url:'chargements.php?rech'});
		patienter('formRes');
			//clearForm('formRechF',0);
	}

function ajouter(){

		$('#act').attr('value','add');
		var url='chargements.php?add';
	
		$('#box').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');
	
}

  $('body').on('keypress', '#ListeCltRech', function(args) {
   if (args.keyCode == 13) {
       $("#Rechercher").click();
       return false;
   }
});

$(document).ready(function(){

	/*$("label[data-group=group_0]").parent(".group").css("border","1px solid red");
		$("label[data-group=group_0]").parent(".group").addClass("selected");*/
	     $("#ListeCltRech").multipleSelect("uncheckAll");
		$('#formRes').html('<center><br/><br/><img src="images/loading.gif" /></center>').load('chargements.php?aff');
				$('#box').dialog({
					autoOpen		:	false,
					width			:	1300,
					height			:	600,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	false,
					draggable		:	false,
					title			:	'Gestion des chargements',
					open: function(event, ui) { 
    //hide close button.
    $(this).parent().children().children('.ui-dialog-titlebar-close').hide();
},
	
					buttons			:	{
						"Fermer"		: function(){
							$('#resG').load('chargements.php?VideSession');
							$('#InfoArticle').show('hide');		
							$(this).dialog('close');
							
						},
						"Terminer "	: function() {
							terminer();
						
						}
					 }
			});
  });
function modifier(id){
		$('#act').attr('value','mod');
		var url='chargements.php?mod&ID='+id;
		$('#box').html('').load(url).dialog('open');	
}
	

function terminer(){

	var form="";
	var act = $('#act').attr('value');
	if(act == 'modif'){ form="#formMod";} else {form="#formAddGlo"; }
form="#formAdd";
	    $(form).validate({
                                              
                                   rules: {
                                               
                                                'Vendeur': "required"
                                           }  
										   
										    });
//alert(document.getElementById("media").value); 
var test=$(form).valid();
verifSelect2('Vendeur');
	
/*******************************************************************/

		if(test==true) {
		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						
				
						
				//	alert($("#ListeClt option").filter(":selected").parent("optgroup").attr("label"));
		
											if(act == 'modif'){	
										
													$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'chargements.php?goUpdate',
														method			:	'post'
													}); 
													return false;
												
											}else{
												
												
													$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'chargements.php?goAdd',
														method			:	'post'
													}); 
													return false;
												
											}
		
					}
				})
		}
		//}//else------------------------------------------------------------------------
	}



function AjoutLigne(){
	var form="";
	var act = $('#act').attr('value');
	if(act == 'modif'){ form="#formMod";} else {form="#formAdd"; }
form="#formAdd";

	    $(form).validate({
                                              
                                   rules: {
                                               
                                                'ListeArt': "required",
												Qte: "required"
                                           }   
										   
										    });
//alert(document.getElementById("media").value); 
var test=$(form).valid();
var t1=verifSelect2('ListeArt');
var t2=verifSelect2('Vendeur');

/*******************************************************************/

		if((test==true) && (t1==true)){
		
			var NomArt= $("#ListeArt").multipleSelect("getSelects", "text");
		

											if(act == 'modif'){	
										
													$('#formMod').ajaxSubmit({
														target			:	'#listBox',
														url				:	'chargements.php?goUpdate',
														method			:	'post'
													}); 
													return false;
												
											}else{									
														
													$('#formAdd').ajaxSubmit({
														target			:	'#listBox',
														url				:	'chargements.php?goAddLigne0&NomArt='+NomArt,
														method			:	'post',
															success:function(){
															}
													}); 
													return false;
												
											}
		
					
		}
}
function changerLigne(prochLigne){
		//'chargements.php?list&ste='+ste+'&type='+type
			$('#listBox').load('chargements.php?list');
			
}
function supLigne(ligne){
	
		var adr 	= 'chargements.php?supLigne='+ligne;
		  jConfirm('Voulez-vous vraiment supprimer cette ligne ?', null, function(r) {
			if(r)	{
				$('#listBox').load(adr);
			}
		  });
}

function Imprimer(IdFiche){
		/*	var adr = 'ficheControle.print.php?IdDmd='+idDmd;
			//alert(adr);
			window.location.href = adr;*/
			 options = "Width=900,Height=900" ;
			window.open( 'chargements.print.php?IdFiche='+IdFiche, "edition", options ) ;
		
	}

	
</script>

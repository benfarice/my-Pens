<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();
$IdDepot=1;

if (isset($_GET['affSousFam'])){
?>	
								<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
<div  onclick="rechercherFam() " >&nbsp;> <span  Class="TitleHead">Familles</span></div>&nbsp;> Sous famille </div>
<div class="clear"></div>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							$key ="";
				if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['lignesFam'] as $v){	//	if(is_array($v)){	parcourir($v);}
								if($v['IdFamille']==$_GET['Id']){
											foreach($v as $r){
												
												if(is_array($r)){											
													if( $i==1) echo " <li><div style='text-align:left'>" ;
													
													
													?>
													  <div class="cadreSouf hvr-shutter-out-horizontal" style="" onclick="AfficheGamme('<?php echo $r['IdSousFam'];?>')">
													  <div  class="childSouFam"> <?php 	echo ucfirst($r['DsgSousFam']);?></div>
													  </div>
													  
													<?php
													if($i==4) {?> <div class="clear"></div><?php }
														//condition pour afficher 4 familles par page
														if ($i == 9) {  echo " </div></li>" ; $i=1;}
														else {				$i+=1;}
												
	 
													}
												}
									}
							}
							?>
						
											<div class="clear"></div>
						
											<?php
														
				}
			?>
		</ul><?php		   
					//$_SESSION['lignesSousFam']=$groups;
				//parcourir($_SESSION['lignesFam']);return;

			// fin bdd plein
//}// fin if isset session


?>	
	
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  
$(document).ready(function () {

	var i = 0;
	$('div.cadreSouf').each(function() {
    /*var rand = back[Math.floor(Math.random() * back.length)];
    console.log(rand);
    $('.cadreSouf').css('background',rand);*/
//	var item =  jQuery.rand(back);
        $(this).css("background-color", TabColor[i]);
		i = (i + 1) ;
    })
})
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

if (isset($_GET['affFam'])){
	
//	unset($_SESSION['lignesCat']);
//$idArticle=$_GET['idArticle'];
if(isset($_GET['VideFam'])){
	unset($_SESSION['VideFam']);
	unset($_SESSION['lignesFam']);
	unset($_SESSION['lignesCat']);
	unset($_SESSION['GammeListe']);
	unset($_SESSION['IdVendeur']);
	unset($_SESSION['IdClient']);
	unset($_SESSION['IdVisite']);
}


$_SESSION['IdVendeur']="1";
$_SESSION['IdClient']="3";
$_SESSION['IdVisite']="1";
if(!isset($_SESSION['lignesFam'])){
	//selectionner les familles des chargements valide
	$sql = "	select 
		g.Reference RefG,g.IdSousFamille as IdGamme,a.Designation DsgArticle ,a.IdArticle	,
		g.Designation as dsgGamme ,mg.url,
		sf.Designation as dsgSousFamille ,sf.idSousFamille IdSousFam ,t.pvHT,f.idFiche,
	 fa.Designation DsgFamille,fa.idFamille IdFamille,fa.codeFamille CodeFamille		
		from articles a
			INNER JOIN gammes g ON g.IdGamme=a.IdFamille
			inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
			INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
			inner join mediaGammes mg on mg.idGamme=g.IdGamme
			INNER JOIN dbo.tarifs t ON t.idArticle=a.IdArticle 
			INNER JOIN dbo.ficheTarifs f ON f.idFiche=t.idFiche
			INNER JOIN dbo.detailChargements dc ON dc.idArticle=a.IdArticle
			INNER JOIN dbo.chargements c ON c.IdChargement=dc.IdChargement
		WHERE 
		c.idVendeur=?   order BY   fa.idFamille desc 
			 ";
	//	echo $sql;
		 $params = array($_SESSION["IdVendeur"]);	


		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
								}
								
		$ntRes = sqlsrv_num_rows($stmt);
		//echo $sql;
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
											$_SESSION['IdFiche']=$row['idFiche'];
										} 
									//	else {
												$keySousFam = $row['IdSousFam'];
												if (!isset($groups[$key][$keySousFam])) {
											
														$groups[$key][$keySousFam] = array();
														$groups[$key][$keySousFam]['IdSousFam']=$row['IdSousFam'];
														$groups[$key][$keySousFam]['DsgSousFam']=$row['dsgSousFamille'];
														
													} 
												//	ELSE {
														$keyGamme= $row['IdGamme'];
															if (!isset($groups[$key][$keySousFam][$keyGamme])) {
														
																	$groups[$key][$keySousFam][$keyGamme] = array();
																	$groups[$key][$keySousFam][$keyGamme]['IdGamme']=$row['IdGamme'];
																	$groups[$key][$keySousFam][$keyGamme]['DsgGamme']=$row['dsgGamme'];
																	
																} 
																	
													$groups[$key][$keySousFam][$keyGamme][$i]['IdArticle'] = $row['IdArticle'];
													$groups[$key][$keySousFam][$keyGamme][$i]['DsgArticle'] = $row['DsgArticle'];									
													$groups[$key][$keySousFam][$keyGamme][$i]['PV'] =$row['pvHT'];	
													//}
											$i=$i+1;		
										//}
										}
				
					$_SESSION['lignesFam']=$groups;
			//	parcourir($_SESSION['lignesFam']);return;

			 }// fin bdd plein
}// fin if isset session


?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>&nbsp;&nbsp;
<div Class="TitleHead" >Liste des familles <?php  //echo date("d/m/y h:i");?></div></div>
<ul class="bxslider" style="margin:0;padding:0;">

<?php 


$k=0;
	$i=1;
	foreach($_SESSION['lignesFam'] as $u=>$v){	
		//echo "--------<li>".$k."</li>";
		// recherche pour ne pas dubliquer la couleur du cadre
		
		if( $i==1) echo " <li><div style='text-align:center'>" ;	
		?>
		  <div class="cadre hvr-shutter-out-horizontal"  onclick="AfficheSousFam('<?php echo $v['IdFamille'];?>')">
			<div  class="child"> <?php 	echo mb_ucfirst($v['DsgFamille']);?></div>
		  </div>
		  
		<?php
		if($i==2) {?> <div class="clear"></div><?php }
			//condition pour afficher 4 familles par page
			if ($i == 4) {  echo " </div></li>" ; $i=1;}
			else {				$i+=1;}
	 //  echo $i;
	}
	


?>
	</ul>			
	
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  
$(document).ready(function () {

	var i = 0;
	$('div.cadre').each(function() {
    /*var rand = back[Math.floor(Math.random() * back.length)];
    console.log(rand);
    $('.cadre').css('background',rand);*/
//	var item =  jQuery.rand(back);

 $(this).css("background-image", "url(../images/transparent.png)");
       $(this).css("background-color", TabColor[i]);
		i = (i + 1) ;
    })
})
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

if (isset($_GET['ChoixTypeReg'])){
?>
<br><br><br>
<form id="formReg" action="" method="post" name="formReg" >
<div id="resReg"></div>
			<TABLE   border="0" width="60%" class="table" cellpadding="15"  align="center" >
				<tr><TD align="right">Montant :</td><td>	
					
					<div class="TotalReg"><?php echo number_format(Total(), 2, '.', ' '); ?></div>					
					
			</td></tr>
			<tr><TD align="right">Type règlement :</td><td>	
					<select style="display:block;width:300px"    tabindex="2" g="select" name="TypeReg" id="TypeReg">
						<option value="Espece" selected>Espèces</option>
					<option value="Cheque"  >Chèque</option> 			
					<option value="Virement">Virement</option>
					</select>
			</td></tr>
				<tr  >
				<TD  align="right" colspan="2">
					<DIV class="bq">
					<table  cellpadding="4"  cellspacing="4" border="0" width="80%" align="center">
						<tr>
								<td align="right">
								Banque :</td>
								<td>	
								<select style="display:block;width:350px" tabindex="2" g="select" name="Bq" id="TYPE2_REG">
									<option value="bp" selected>Banque populaire</option>
								<option value="Cheque">BMCI</option> 			
								<option value="Virement">SOGE</option>
								</select>
								</td>
						</tr
							<tr>
								<td align="right">
								Numéro :</td>
								<td>	
								<input type="text" value=" " class="" id="" size="25" name="Num">
								</td>
						</tr>
					</table>
					</div>
					</td>
				</tr>
				<TR >	
				<td   Valign="top" align="center" colspan="2"><br><br>
				<input type="button" value="Retour"  class="btn" onclick="Fermer()"/>&nbsp;&nbsp;
				<input type="button" value="Terminer" class="btn"  onclick="TerminerCmd()"/>
				
				</td>
				</tr>
</table>
</form>
	<script language="javascript" type="text/javascript">	
		$('#TypeReg').on('change', function() {
		
		  if(this.value=="Espece")
		  {
			  $('.bq').hide(1000);
		  }
		  else {
			  $('.bq').show(1000);
		  }
		});
		
		function TerminerCmd(){
		var test=true;
		var TypeReg = $('#TypeReg').find(":selected").val();
		
		if(TypeReg!="Espece"){
		    $('#formReg').validate({
                                              
                                   rules: {
                                               
                                                'Num': "required"
                                           }   
										   
										    });
		var test=$('#formReg').valid();
		}
		if(test==true){									
		$('#formReg').ajaxSubmit({
							target:'#resReg',
							url:'catalogue2.php?TerminerCmd',
								method			:	'post'
							});
		patienter('resReg');
		}

	}
	</script>	
<?php
	exit;
}
if (isset($_GET['TerminerCmd'])){

	//parcourir($_POST);return;
	//parcourir($_SESSION['lignesCat'] );return;
	  $error="";
	  
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//-----------------Add facture----------------//
/// calcul total ht ,ttc
$TotalHT=0;$TotalTTC=0;$totalTVA=0;$Tva=0;
$EtatReg="R";
$Bq="";$numCheque="";$numVersement="";
if($_POST['TypeReg']=="Cheque") { $Bq=$_POST['Bq'] ;$numCheque=$_POST['Num'];}

else if($_POST['TypeReg']=="Virement") { $Bq=$_POST['Bq'] ;$numVersement=$_POST['Num'];}


	if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {
	foreach($_SESSION['lignesCat'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
			$Tva=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"] * $contenu["TVA"]) /100;
			$totalTVA+=$contenu["TVA"];
			$TotalTTC+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
			$TotalHT+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]);
			}
	}
/*
if(	$_POST['TotalCmdTTC']==0) $EtatReg="NR";
else if($_POST['TotalCmdTTC']<$TotalTTC) $EtatReg="P";
else if($_POST['TotalCmdTTC']==$TotalTTC) $EtatReg="R";
*/

$NumFacture= "NF".Increment_Chaine_F("numFacture","factures","IdFacture",$conn,"",array());	
//echo $RefFicheCh;return;
$Date = date_create(date("Y-m-d"));
$Heure=date("H:i:s");
$Etat="";
$reqInser1 = "INSERT INTO factures ([numFacture] ,[idClient]  ,[idVendeur]  ,visite,[date],[heure],[idDepot],totalHT,totalTVA,totalTTC,etat,reste) 
				values 	(?,?,?,?,?,?,?,?,?,?,?,?)";
		//	echo $reqInser1;
$params1= array(
				$NumFacture,
				$_SESSION["IdClient"],
				$_SESSION["IdVendeur"],
				$_SESSION["IdVisite"],
				$Date,
				$Heure,
				$IdDepot,
				$TotalHT,
				$totalTVA,
				$TotalTTC,
				$EtatReg,
				0
				
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout facture ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IDFacture--------------------------------//
$sql = "SELECT max(IdFacture) as IdFacture FROM factures";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idfacture : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFacture = sqlsrv_get_field( $stmt2, 0);


//----------------------Add Detail facture --------------------------//
$idFiche="";
  foreach($_SESSION['lignesCat'] as $ligne=>$contenu){

	$reqInser2 = "INSERT INTO  detailfactures(idFacture,[idArticle],type,[qte],tarif,idDepot,idFiche,tauxTVA,ht,tva,ttc) 
					values (?,?,?,?,?,?,?,?,?,?,?)";
			$params2= array(
					$IdFacture,
					$contenu['IdArticle'],
					$contenu['Colisage'],
					$contenu['Qte'],
					$contenu['PV'],
					$IdDepot,
					$_SESSION["IdFiche"],
					$contenu['TVA'],
					$contenu['HT'],
					($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"] * $contenu["TVA"]) /100,
					$contenu['TTC']
			
			) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout detail facture ".$errors[0]['message'] . " <br/> ";
				break ;
			}		


				//----------------------modification du stock vendeur --------------------------//
			$qteCmd=$contenu['Qte'];
			$reqUpStock = "update   stockVendeurs set stock=stock-$qteCmd where idVendeur = ? and idArticle=?";
			$paramsUp= array($_SESSION['IdVendeur'],$contenu['IdArticle']) ;
			$stmtUp = sqlsrv_query( $conn, $reqUpStock, $paramsUp );
			if( $stmtUp === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : modification du stock vendeur ".$errors[0]['message'] . " <br/> ";
				break ;
			}
			
			
}

//----------------------Insertion regelement --------------------------//
$reqInser3 = "INSERT INTO reglements ([idClient]  ,[idVendeur] ,[date],[heure],[type],montant,idDepot) 
				values 	(?,?,?,?,?,?,?)";
		//	echo $reqInser1;
$params3= array(				
				$_SESSION["IdClient"],
				$_SESSION["IdVendeur"],
				$Date,
				$Heure,
				"",
				$TotalTTC,
				$IdDepot				
) ;
$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3 );

if( $stmt4== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout regelement ".$errors[0]['message'] . " <br/> ";
}

//---------------------------Recuperer id Regelement--------------------------------//
$sql = "SELECT max(IdReglement) as IdReglement FROM reglements";
$stmtR = sqlsrv_query( $conn, $sql );
if( $stmtR === false ) {
    $error.="Erreur recuperation id Regelement : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmtR) ;
$IdRegelement = sqlsrv_get_field( $stmtR, 0);


//----------------------Insertion detail regelement --------------------------//
$reqInser4 = "INSERT INTO detailReglements (IdReglement  ,montant ,mode,banque,numCheque,numVersement) 
				values 	(?,?,?,?,?,?)";
		//	echo $reqInser1;
$params4= array(				
				$IdRegelement,
				$TotalTTC,
				$_POST['TypeReg'],
				$Bq,
				$numCheque,
				$numVersement
) ;
$stmt5 = sqlsrv_query( $conn, $reqInser4, $params4 );

if( $stmt5== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Insertion detail regelement ".$errors[0]['message'] . " <br/> ";
}
//----------------------Insertion reglementFactures --------------------------//

$reqInser5 = "INSERT INTO reglementFactures (IdReglement  ,idFacture ,idDepot) 
				values 	(?,?,?)";
			//echo $reqInser5;
$params5= array(				
				$IdRegelement,
				$IdFacture,
				$IdDepot
) ;
$stmt6= sqlsrv_query( $conn, $reqInser5, $params5 );

if( $stmt6== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Insertion reglementFactures ".$errors[0]['message'] . " <br/> ";
}



if($error=="" ) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
		//	jAlert("L\'ajout a été effectuée","Message");			
			$('#box').dialog('close');
			Imprimer("<?php echo $IdFacture;?>");
			rechercher();
			
		</script>
		
<?php
unset($_SESSION['lignesCat']);
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}

	exit;
}

if (isset($_GET['VerifStock'])){
	
	$sql = " select sum(stock*idColisage)as Stock
			 
			 from stockvendeurs s where idarticle=? and idVendeur=? ";
				$params= array(
						$_POST['IdArticle'],
						$_SESSION['IdVendeur'],
						//$_POST['Colisage']
						
				) ;
				
		//		echo $sql;
//echo 	$_SESSION['IdVendeur']."br";
//parcourir($params);
  	            $reponse=sqlsrv_query( $conn, $sql, $params, array( "Scrollable" => 'static' ) );	
				if( $reponse === false ) {
					$errors = sqlsrv_errors();
					$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
					return;
				}		
				if(sqlsrv_num_rows($reponse)==0) { echo "0";return;}
					$rowC = sqlsrv_fetch_array($reponse, SQLSRV_FETCH_ASSOC);		
			//	echo ("----".$_POST['Qte']*$_POST['Colisage']."---------".$rowC['Stock']."-----");					
					if(($_POST['Qte']*$_POST['Colisage']) > $rowC['Stock']) echo "0";
					else echo "1";
				

	   exit;
				
}
if (isset($_GET['VerifSession'])){
	
	   if( (!isset($_SESSION['lignesCat'])) || (count($_SESSION['lignesCat'])==0))  
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
<a href="#"  onclick="Fermer()" class="Retour">Retour </a>
<?php
if(isset($_SESSION['lignesCat']) && count($_SESSION['lignesCat']) != 0){
		
			$i=0;
			?>
	<form id="formCmd" method="post" name="formCmd"> 		
	<div id="res"></div>
	<DIV Class="title">Validation de la commande </div>
	<div style="height:585px; overflow:scroll;" >

	<DIV class="ListeCmd">
	 	 <div class="enteteL" >
        <div  class="divArticleL"  >Article </div>
		   <div  class="divArticleL" style="width:230px"  >Gamme </div>		
        <div class="divQteL" > Quantité </div>			
		 <div class="divColisageL"> Colisage </div>
		  <div class="divPVL"> PV HT (Dhs) </div>
		   <div class="divColisageL"> TVA </div>
		     <div class="divTTC"> HT (Dhs) </div>
			 <div class="divTTC"> TTC (Dhs) </div>
		</div>
  	
			<?php
			$k=0;$Total=0;
			foreach($_SESSION['lignesCat'] as $ligne=> $row){
				$k++;
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			$Total+=$row["Qte"]*$row["Colisage"]*$row["PV"];
				?>	
				<div class=" <?php echo $c;?>" onclick="getArticle('<?php  echo $row['IdArticle'];?>','list')">
						<div class="divArticleL" align="center"><?php  echo $row['NomArt'];?></div>
						<div class="divArticleL"  style="width:230px"  align="center"><?php  echo $row['Gamme'];?></div>
						<div class="divQteL" > <?php  echo $row['Qte'];?> </div> 											
						<div class="divColisageL" > <?php  echo $row['Colisage'];?> </div> 
						<div class="divPVL" > <?php echo number_format($row['PV'], 2, '.', ' '); ?>  </div> 
						<div class="divColisageL" > <?php  echo $row['TVA'];?> </div> 						
						<div class="divTTC" > <?php  
										$PVHT=$row["Qte"]*$row["Colisage"]*$row["PV"];
										echo number_format($PVHT, 2, '.', ' ');?> 
							</div> 
							<div class="divTTC" > <?php  
										$Tva=($row["Qte"]*$row["Colisage"]*$row["PV"] * $row["TVA"]) /100;
										$Total=($row["Qte"]*$row["Colisage"]*$row["PV"]) +$Tva;
										echo number_format($Total, 2, '.', ' ');?> 
							</div> 
												
								
				</div>
			<DIV Class="clear"></div>
			
	
			<?php
		}	
	?>
	</div>


</div>
<DIV Style="position:fixed;bottom:0px">
<div class=" divRight">
			<div class="cmd">Total global:
			<input type="text" value=" <?php echo number_format(Total(), 2, '.', ' '); ?> " class="global"  disabled id="txtTotal" size="10"  name="TotalCmdTTC">DHS </div>
		</div>
		<div class="divLeft">
			<input type="button" value="Valider la commande >>" class="btnCmd" onclick="ValideCmdEtape1()">
		</div></div>
	</form>
<?php
			
	}	

exit();

}
if (isset($_GET['goAddArti'])){	
//ECHO $_POST["PV"];return;
$Tva=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"] * $_POST["TVA"]) /100;	
	if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {
	//   echo count($_SESSION["lignesCat"]);
		$t=0;	
		$Total=0;
			  foreach($_SESSION['lignesCat'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
				$Total+=$contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"];
					if(($contenu["IdArticle"]==$_POST["IdArticle"]))
					{
								$IndexLigne=$t;
								$ligneArray["IdArticle"]=$_POST["IdArticle"];
								$ligneArray["NomArt"]=$_POST["NomArt"];
								$ligneArray["Qte"]=$_POST["Qte"];
								$ligneArray["PV"]=$_POST["PV"];
								$ligneArray["Gamme"]=$_POST["Gamme"];
								$ligneArray["Colisage"]=$_POST["Colisage"];
								$ligneArray["TVA"]=$_POST["TVA"];
								$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
								$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
								$_SESSION['lignesCat'][$IndexLigne]= $ligneArray;
								
						$t=0;break;				
					}
					else {					
						$t+=1;						
					}
			  }
			  // si l'article avec mm qte n'existe pas on l'ajoute
			  if($t!=0){
			  			$IndexLigne=count($_SESSION['lignesCat']);							
						// si la qte et tarif vide en l'ajoute pas
						
							$ligneArray["IdLigne"]=$IndexLigne;
							$ligneArray["IdArticle"]=$_POST["IdArticle"];
							$ligneArray["NomArt"]=$_POST["NomArt"];
							$ligneArray["Qte"]=$_POST["Qte"];
							$ligneArray["PV"]=$_POST["PV"];
							$ligneArray["Gamme"]=$_POST["Gamme"];
							$ligneArray["TVA"]=$_POST["TVA"];
							$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
							$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
							$ligneArray["Colisage"]=$_POST["Colisage"];
							$_SESSION['lignesCat'][$IndexLigne]= $ligneArray;
						
						
			  }
		  }
		  else {// une premiere insertion sans controle
						 //$IndexLigne+=1;
		
								$IndexLigne=0;
								$ligneArray["IdArticle"]=$_POST["IdArticle"];
								$ligneArray["NomArt"]=$_POST["NomArt"];
								$ligneArray["Qte"]=$_POST["Qte"];
								$ligneArray["PV"]=$_POST["PV"];
								$ligneArray["Gamme"]=$_POST["Gamme"];
								$ligneArray["TVA"]=$_POST["TVA"];
								$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
								$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
								$ligneArray["Colisage"]=$_POST["Colisage"];
								$_SESSION['lignesCat'][$IndexLigne]= $ligneArray;
					
		  }
		    
		  //	parcourir($_SESSION['lignesCat']);
		 if ((isset($_POST['List'])) && ($_POST['List'])=='List') {	 
		 ?>
			<script language="javascript" type="text/javascript">
						ConsultCmd();
			</script>
			<?php
		 }
?>
<script language="javascript" type="text/javascript">
			$("#txtTotal").val("<?php echo number_format(Total(), 2, '.', ' '); ?>");
		  	$("#box").dialog('close');
</script>
<?php
	exit;
}
function Total(){
	$Total=0;
	if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {
	foreach($_SESSION['lignesCat'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
			$Tva=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"] * $contenu["TVA"]) /100;
			
			$Total+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
			}
	}
	return($Total);
}
if (isset($_GET['getArticle'])){

		$sqlAr = "select 
								a.Designation dsgArticle ,a.IdArticle,a.reference,CB codeABarre,m.url,colisagee colisage,
								g.Designation dsgGamme,
								sf.Designation dsgSousFamille,
								f.Designation dsgFamille,TVA,t.pvHT as PV,a.Unite
								from articles a
								inner join gammes g on g.idGamme=a.IdFamille
								inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
								inner join Familles f on f.idFamille=sf.idFamille
								inner join media m on m.idArticle=a.idArticle
								inner join colisages c on c.idArticle=a.idArticle
								INNER JOIN dbo.tarifs t ON t.idArticle=a.IdArticle 
								WHERE a.idArticle=?
									 ";
									 /*			INNER JOIN detailChargements dc on dc.idarticle=a.IdArticle
			INNER JOIN dbo.chargements c ON c.IdChargement=dc.IdChargement where  AND c.idVendeur=1 */
									 
						$paramsAr = array($_GET['idArticle']);

						$stmtA=sqlsrv_query($conn,$sqlAr,$paramsAr,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
						if( $stmtA === false ) {
							$errors = sqlsrv_errors();
							echo "Erreur : ".$errors[0]['message'] . " <br/> ";
						}
					else {
		
						$nResA = sqlsrv_num_rows($stmtA);
						//echo $sql;
							$nRes = sqlsrv_num_rows($stmtA);	

						if($nResA==0)
								{ ?>
											<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
												<br><br><br><br>
												Aucun r&eacute;sultat &agrave; afficher.
												<a href="#" onclick="Fermer()" class="Retour">Retour </a>
											</div>
											<?php
								}
						else
						{		$row = sqlsrv_fetch_array($stmtA);	
								$IdArticle=$row['IdArticle'];
								$dsgArticle=$row['dsgArticle'];
								$dsgGamme=$row['dsgGamme'];
								$TVA=$row['TVA'];
								$PV="10";
								?>
									<DIV class="haut">
										<div class="divLeftArt">
										<div id="resAdd"></div>
										<form id="formAddArt" method="post" name="formAddArt"> 
											<TABLE   border="0" width="100%" class="table" cellspacing="2" cellpadding="7">
												<tr><TD width="25%">Référence:</td><td>	<?php  echo $row['reference'];?>
												<input type="hidden" value="<?php  echo $IdArticle; ?>" name="IdArticle">
												<input type="hidden" value="<?php  echo $dsgArticle; ?>" name="NomArt">
												<input type="hidden" value="<?php  echo $dsgGamme; ?>" name="Gamme">
												<input type="hidden" value="<?php  echo $TVA; ?>" name="TVA">
												<input type="hidden" value="<?php  echo $row['PV'];?>" name="PV">
												<?php if (isset($_GET['list'])){
													
													$key = array_search($_GET['idArticle'],array_column($_SESSION["lignesCat"], 'IdArticle'));
													$Qte=$_SESSION["lignesCat"][$key]["Qte"];
													
													?>
												
													<input type="hidden" value="List" name="List">
													<?php } else $Qte=1;?>
												</td></tr>
												<tr><TD >Désignation:</td><td>	<?php  echo ucfirst($dsgArticle);?></td></tr>
												<tr><TD>Gamme:</td><td><?php  echo ucfirst($dsgGamme);?></td></tr>
												<tr><TD>Sous famille:</td><td><?php  echo ucfirst($row['dsgSousFamille']);?></td></tr>
												<tr><TD>Famille:</td><td><?php  echo ucfirst($row['dsgFamille']);?></td></tr>
												<tr><TD>PV:</td><td><?php  echo $row['PV'];?></td></tr>
												<tr><TD>Colisage:</td><td height="70">
												<input type="radio" name="Colisage"  value="1" 
												data-labelauty="1<?php  echo $row['Unite'];?>|1<?php  echo $row['Unite'];?>" aria-label="2"  checked />
												<?php 
												if( $row['colisage']!=1){
												?>
												<input type="radio" name="Colisage" value="<?php  echo $row['colisage'];?>" 
												data-labelauty="<?php  echo $row['colisage'];?><?php  echo $row['Unite'];?>|
												<?php  echo $row['colisage'];?><?php  echo $row['Unite'];?>" aria-label="3"/>
												<?php }?>
												</td></tr>
												<tr><TD>Quantité:</td><td Valign="top"> 
												<input type="text" value="<?php echo $Qte;?>" name="Qte" onkeypress="return isEntier(event) " 
												class="Qte" size="4" id="Qte"><br>
												<input type="button" class=" qtyplus"  id="qtyplus" value="+" id="btnp">&nbsp;
												<input type="button" class=" qtyplus" id="qtyminus"  value="-"></td></tr>
												<TR>	
												<td   Valign="top"></td>
												<td>
												
												
												</td>
												</tr>
												<TR>	
												<td   Valign="top" align="center" colspan="2">
												<input type="button" value="Valider" class="btn"  onclick="AjoutArticle()"/>&nbsp;&nbsp;
												<input type="button" value="Fermer"  class="btn" onclick="Fermer()"/>
												</td>
												</tr>
											</table>
											</form> 
										</div>
										
										<div class="divRight">
										<img src="../<?php  echo $row['url'];?>" alt=""  width=" 640" height="760" />	
										</div>
									</div>
									<?php
						}
					}
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){		
	$(":radio").labelauty();
	
  $('#qtyplus').click(function(e){
		var fieldId="Qte";
        // Stop acting like a button
        e.preventDefault();
        var currentVal = parseInt($('input[id='+fieldId+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
            $('input[id='+fieldId+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[id='+fieldId+']').val(1);
        }
    });
	    $('#qtyminus').click(function(e){
		var fieldId="Qte";
        // Stop acting like a button
        e.preventDefault();
        var currentVal = parseInt($('input[id='+fieldId+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
			if((currentVal - 1)>=1){
            $('input[id='+fieldId+']').val(currentVal - 1);
			}
        } else {
            // Otherwise put a 0 there
            $('input[id='+fieldId+']').val(1);
        }
    });
});


function AjoutArticle(){
	    $('#formAddArt').validate({
                                              
                                   rules: {
                                               
                                                'Qte': "required",
												'Colisage': "required"
                                           }  
										   
										    });
											
var test=$('#formAddArt').valid();
		if((test==true) ){		
			 jConfirm('Voulez-vous vraiment commander l\'article?', null, function(r) {
					if(r)	{
							var Verif="";							 
							 // verification du stock vendeur pour l'article selectionné
							 	$('#formAddArt').ajaxSubmit({
									   url : 'catalogue2.php?VerifStock',
									   type : 'POST',
									   dataType : 'html', // On désire recevoir du HTML
									   success : function(code_html, statut){										   
										   Verif=code_html;
											if(Verif==1){
											$('#formAddArt').ajaxSubmit({
																target			:	'#resAdd',
																url				:	'catalogue2.php?goAddArti',
																method			:	'post'
															}); 
															return false;
											}else 
											{
												jAlert("la quantité demandée dépasse la quantité du stock ","Message");
											}
									   }
								})
						   
						   

												
						}
				})
		}
}
</script>
<?php
		exit;
}

if (isset($_GET['aff'])){
	//echo "gammme";return;
//	unset($_SESSION['lignesCat']);
//$idArticle=$_GET['idArticle'];

	$sql = "select 
		g.Reference RefG,g.IdSousFamille as IdSousFamille,a.Designation DsgArticle ,a.IdArticle	,
		g.Designation as dsgGamme ,mg.url,
		sf.Designation as dsgSousFamille ,t.pvHT,f.idFiche,g.IdGamme,
		sf.IdFamille
		from articles a
			INNER JOIN gammes g ON g.IdGamme=a.IdFamille
			inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
			inner join mediaGammes mg on mg.idGamme=g.IdGamme
			INNER JOIN dbo.tarifs t ON t.idArticle=a.IdArticle 
			INNER JOIN dbo.ficheTarifs f ON f.idFiche=t.idFiche
			INNER JOIN dbo.detailChargements dc ON dc.idArticle=a.IdArticle
			INNER JOIN dbo.chargements c ON c.IdChargement=dc.IdChargement
		WHERE 
		c.idVendeur=? and g.IdSousFamille=? AND f.etat=1 order by   g.idGamme Desc , t.pvHT  asc
			 ";

		 $params = array($_SESSION['IdVendeur'],$_GET['Id']);	
		/*	
		if ((isset($_GET['IdArticle'])) and ($_GET['IdArticle']!="") ) {
			$params = array($_GET['idVend'],$_GET['IdArticle']);
			$sql.=" and a.idArticle=? ";
		}else {
			$params = array($_GET['idVend']);
		}*/


		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
								}

		$ntRes = sqlsrv_num_rows($stmt);
		//echo $ntRes;return;
		//echo $sql;
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
												 
								 
										$key = $row['IdGamme'];
										$i=$i+1;
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['IdGamme']=$row['IdGamme'];
											$groups[$key]['RefG']=$row['RefG'];
											$groups[$key]['dsgGamme']=$row['dsgGamme'];
											$groups[$key]['url']=$row['url'];
											$groups[$key]['dsgSousFamille']=$row['dsgSousFamille'];
											$groups[$key]['IdFamille']=$row['IdFamille'];	
											$_SESSION['IdFiche']=$row['idFiche'];
											$idFam=$row['IdFamille'];
										} 
									//	echo "<br>---".$i."---<br>";
										
											$groups[$key][$i]['IdArticle'] = $row['IdArticle'];
											$groups[$key][$i]['DsgArticle'] = $row['DsgArticle'];									
											$groups[$key][$i]['PV'] =$row['pvHT'];
										
										
				}
				//parcourir ($groups);

		?>
<?php }// fin bdd plein





?>

<DIV style="  display:flex;  align-items:center;" class="headVente"><a href="index.php">
<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>
<div >&nbsp;&nbsp;>
<span  class="TitleHead" onclick="rechercherFam()"> Familles</span>
>
<span  class="TitleHead" onclick="AfficheSousFam(<?php echo $idFam;?>)">Sous famille</span>
</div>&nbsp;> Gamme</div>




<ul class="bxslider" style="margin:0;padding:0;">
<?php 
$k=0;

	foreach($groups as $u=>$v){	
		$k=$k+1;

		?>
	 <li>
		<DIV class="haut">
		<div class="divLeft">
			<TABLE   border="0" width="100%" class="table" cellpadding="6" >
			<tr><TD width="20%">Référence:</td><td>	<?php  echo $v['RefG'];?></td></tr>
			<tr><TD>Gamme:</td><td><?php  echo ucfirst($v['dsgGamme']);?></td></tr>
			<tr><TD>Sous Famille:</td><td><?php  echo ucfirst($v['dsgSousFamille']);?></td></tr>
				</table>
		
					<DIV class="entete">
						<div class="divArticle" Style="width:353px;" align="center">Article </div>
						<div class="divPV"  Style="width:70px;" align="center">PV(DH) </div>
						<div class="divPV" Style="width:132px;" align="center">Action </div>
					</div>
						<div class="clear"></div>
				<div class="DivListArt">				
				<?php 
				$key="";$c="";
					foreach($v as $r){
						
						if(is_array($r)){
							if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {
					
								$key = array_search($r['IdArticle'], array_column($_SESSION['lignesCat'], 'IdArticle'));
							}
						/*	echo "--".$key."---";
							if($key==="") {$c="md";}
							else {$c="Cmder";}	*/
							?>		
								<div class="ligne " >
									<div class=" <?php echo $c;?> divArticle " align="center"><?php  echo ucfirst($r['DsgArticle']);?>					
									</div>
									<div class="divPV" > <?php  echo $r['PV'];?> </div> 
									<div class="divCmd"  ><input type="button" value="Commander" onclick="getArticle('<?php  echo $r['IdArticle'];?>')"
									class="btnCmdArt"></div>
								</div>
								<div class="clear"></div>
						<?php 
						}
						}?>									
			</div>
		
		</div>
		<div class="divRight">		
			<img src="../<?php  echo $v['url'];?>" alt=""  width=" 638" height="662" />					
		</div>
		<div class="clear"></div>
		
		</div>
	</li>
	<?php }?>

	</ul>
	
	
<script language="javascript" type="text/javascript">
  // initialize bxSlider

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
			threshold: 200
		});
	
</script>
<?php		


exit;
}
include("header.php");
?>
<div id="formRes" style="MAX-height:790px;">

</div>
<div class="bottomVente">
<div class="divLeft">
			<div class="cmd">
			
			Total global: <input type="text" value=" 0.00" class="global" id="txtTotal" size="6" disabled> DHS</div>
		</div>
		<div class="divRight">
			<input type="button" value="Consulter la commande >>" class="btnCmd" onclick="ConsultCmd()">
		</div>
		</div>
<div id="box"></div>
<script language="javascript" type="text/javascript">		
// Get the modal

	$("#txtTotal").val("<?php echo number_format(Total(), 2, '.', ' '); ?>");
function ConsultCmd(){
	 var Verif="";
	$.get("catalogue2.php?VerifSession", function(response) {
      Verif = response;
	  // verifier si le vendeur a ajouté des articles
		if(Verif==1){
		var url='catalogue2.php?ConsultCmd';	
		$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
		//$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
			}else 
			{
				jAlert("Merci d'ajouter des articles.","Message");
			}
	});		
}
function getArticle(idArticle,list){

	if (list === undefined || list === null) {
		var url='catalogue2.php?getArticle&&idArticle='+idArticle;	
		$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}
	else{
		
		var url='catalogue2.php?getArticle&&list&&idArticle='+idArticle;	
		$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}
	
}


$(document).ready(function(){		
	//$(":radio").labelauty();	
// code pour prendre enconsideration l'hover quand on met le doigt sur l'ecran
$("input[type=button").addClass("hvr-grow");
$('body').bind('touchstart', function() {});


	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('catalogue2.php?affFam&&VideFam');
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
							terminer();
						
						}
					 }
			});


		
		
		 // This button will increment the value
$('input[type=text]').on('focus', function() { 
  console.log($(this).attr('id') + ' just got focus!!');
  window.last_focus = $(this);
});
});

	function AfficheGamme(id){
									//alert('lll');
									var url='catalogue2.php?aff&&Id='+id;	
									$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

								}
function rechercherFam(){
		//alert('lll');
		var url='catalogue2.php?affFam';	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}
function AfficheSousFam(id){
		//alert(id);
		var url='catalogue2.php?affSousFam&&Id='+id;	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}
function Fermer(){
	$("#box").dialog('close');
}

function FermerFenetre(){
	alert("fermer");
	var url='catalogue2.php?FermerFenetre';	
	//$("#box").load(url);
}
function ValideCmdEtape1(){
	/*$('#formCmd').ajaxSubmit({
		target:'#res',
		url:'catalogue2.php?ValideCmd',
			method			:	'post'
		});
		patienter('res');*/
		$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('catalogue2.php?ChoixTypeReg').dialog('open');
		//$('#res').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

}
function Imprimer(IdFacture){
		/*	var adr = 'ficheControle.print.php?IdDmd='+idDmd;
			//alert(adr);
			window.location.href = adr;*/
			 options = "Width=1280,Height=800" ;
		//	 alert(IdFacture);
		//	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('facture.print.php?IdFacture='+IdFacture).dialog('open');

		  window.open( 'facture.print0.php?IdFacture='+IdFacture, "edition", options ) ;
		
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
	
</script>

<?php include("footer.php");?>
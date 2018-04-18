<?php 
include("php.fonctions.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "vendeurs";
$cleTable = "IdVendeur";
if ((!isset($_SESSION['IdDepot'])) || ($_SESSION['IdDepot'] =="")){
	header("login.php");
}
$IdDepot=$_SESSION['IdDepot'];
$nom_sansDoublons = "Numéro d\'immatriculation";
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

/*function modif(){*/
	$tableInser = "transports";
$sansDoublons = "Immatriculation";
$cleTable = "IdTransport";
$nom_sansDoublons = "Numéro d\'immatriculation";
	//on verif si codeF existe deja
			$reqModif = "UPDATE $tableInser SET Immatriculation='".addslashes(mb_strtolower(securite_bdd($_POST['CodeFamille'])))."',";
			$reqModif .=  " DsgTransport='".addslashes(mb_strtolower(securite_bdd($_POST['DsgFamille'])))."'";
			$reqModif .= " WHERE $cleTable='".$_POST[$cleTable]."' ";

				$reqVerif = "select * from $tableInser where etat=1 and $sansDoublons='".mysql_real_escape_string($_POST[$sansDoublons])."' AND $cleTable != '".$_POST[$cleTable]."'";
			mysql_query($reqVerif)or die(mysql_error().$reqVerif);
	if( mysql_query($reqVerif)){  
				if(mysql_num_rows(mysql_query($reqVerif))==0){ //new
						$resModif = mysql_query($reqModif);
					
						
							if($resModif){
							?>
							
							<script type="text/javascript"> 
							//alertK('L\'ajout a été effectué.',1);
							alert('La modification a été effectué.');
									$('#boxClient').dialog('close');
									rechercher();
							</script>
						
							<?php
							
						}else {echo mysql_error(); 
					
						?>
								<script language="javascript">
								//alertK("erreur lors de l'ajout. Contacter l'administrateur",0);
								alert("erreur lors de la modification. Contacter l'administrateur.__<?php echo  mysql_error(); ?>");
								//$('#boxClient').dialog('close');
								</script>
						<?php }
					}else{ //sansDoublons existe
						?><script language="javascript">
									//alertK('La <?php echo $nom_sansDoublons; ?> choisie existe déjà.\nMerci d\'en choisir une autre. ',0);
									alert('Le <?php echo $nom_sansDoublons; ?> choisi existe déjà.\nMerci d\'en choisir un autre. ');
									//$('#boxClient').dialog('close');
							</script>
	<?php				}
	}

exit;
	
}
if(isset($_GET['goAdd'])){

		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	  
/**********************Controle doublon****CIN************************/
$sql = "SELECT * FROM ".$tableInser." WHERE cin=? ";
$param= array($_POST['Cin']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql ,$param,$options);
if( $stmt === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	echo $error;
	return;
}
$count = sqlsrv_num_rows($stmt);
$IdAffectation ="";
	if($count >0)//*****************Already Exist Cin
	{
	?>
				<script type="text/javascript"> 
					alert('Cin déja existant.');
				</script>
	<?php
	return;
	}
		$reqInser1 = "INSERT INTO ".$tableInser." ([nom],[prenom],[telephone],[adresse],[mail],[cin],[idDepot])
		values 	(?,?,?,?,?,?,?)";

		$params1= array(
		addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Prenom']), 'UTF-8')),
		$_POST['Tel'],
		addslashes(mb_strtolower(securite_bdd($_POST['Adresse']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Mail']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Cin']), 'UTF-8')),
		1) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					alert('L\'ajout a été effectué.');
					$('#boxClient').dialog('close');
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
	$sql = "select * from transports where Etat =1 and IdTransport = '$ID' ";
	//execSQL($sql);
	//echo $sql; return;
	$res=mysql_query($sql)or die(mysql_error().$sql);
	$row = mysql_fetch_assoc($res);

?>
	<div id="resMod" style="padding:5px;">&nbsp;</div>
	<form id="formMod" action="NULL" method="post" name="formAdd1"> 
		
		<table width="100%" border="0" align="center" cellpadding="5">
        <tr>
        	<td><div class="etiqForm" id="" > <strong>Code Famille</strong> : </div>
            </td>
            <td>
            <input type="hidden" value="<?php echo $ID ;?>" name="IdTransport" />
            <input class="FormAdd1" type="text" name="CodeFamille"
			id="CodeFamille" value="<?php echo $row["Immatriculation"];?>" size="44" tabindex="1"  />
          </td>
          </tr>
			<tr>
              <td><div class="etiqForm" id="DATE_PIECE" > <strong>Désignation famille</strong> : </div>
            </td>
            <td> 
            <textarea class="FormAdd1"   cols="42" style="resize:None;" rows="1" name="DsgFamille" id="DsgFamille"
			size="100"   tabindex="2"><?php echo $row["DsgTransport"];?></textarea>
        <!--    <input class="FormAdd1" type="text" name="DsgTransport" id="DsgTransport" size="100"
              value="" control="1" tabindex="6"  />-->
            </td>
            </tr>	  
			<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
    <script src="js/jquery.validerForm.js" type="text/javascript"></script>    
 <?php 
exit;
}

if (isset($_GET['add'])){
?>

<div id="resAdd" style="padding:5px;">&nbsp;</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="8">
        <tr>
        	<td>
			 <div class="etiqForm" id="" ><strong>Nom  </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Nom"  id="Nom" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong>Prenom</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Prenom"  id="Prenom" size="30" tabindex="1"  />
            </td>
		</tr>

		 <tr>
		 <td><div class="etiqForm" id="" > <strong>Adresse </strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="Adresse"  id="Adresse" size="30" tabindex="1"  />
            </td>
		<td><div class="etiqForm" id="" > <strong>Cin</strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="Cin"  id="Cin" size="30" tabindex="1"  />
            </td>			
          </tr>
		<tr>
			<td>
			 <div class="etiqForm" id="" > <strong>Téléphone</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Tel"  id="Tel" size="30" tabindex="1"  />
            </td>
		<td><div class="etiqForm" id="" ><strong>Mail </strong> : </div>
            </td>
            <td>
            	<input class="FormAdd1" type="text" name="Mail"  id="Mail" size="30" tabindex="1"  />
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
$where_mouvement="";
$where_chargement="";
$where_facture="";
		if(isset($_POST['DateD']) && isset($_POST['DateF'])  )
		{
			// l'utilisateur à selectionner le depot, on affiche donnée du depot sinon on affiche les données du depot cnté par defaut
				if(isset($_POST['IdDepot']) &&($_POST['IdDepot']!="")  ) $IdDepot=$_POST['IdDepot'];
			if($_POST['DateD'] == $_POST['DateF'])
			{ 
			 	 $where_mouvement= " and m.date = convert(date, '".($_POST['DateD'])."',105) ";
			 	 $where_facture= " and  cast(f.date AS date) = convert(date, '".($_POST['DateD'])."' ,105)";				 
			}
			else
			{
				 $where_mouvement= " and m.date  between  convert(date, '".($_POST['DateD'])."',105) and convert(date, '".($_POST['DateF'])."',105) ";
				 $where_facture= " and  cast(f.date AS date)  between  convert(date, '".($_POST['DateD'])."',105) and convert(date, '".($_POST['DateF'])."' ,105)";
			}
		}
		else
		{
		$where_mouvement="and m.date  between  convert(date, '".('01/01/2017')."',105) and convert(date, '".('01/01/2020')."',105) ";
		$where_facture=" and  cast(f.date AS date) between  convert(date, '".('01/01/2017')."',105) and convert(date, '".('01/01/2020')."',105) ";
			
		}
				// recuperation date dernier inventaire
		if(($IdDepot==2)||($IdDepot==4)){
		$sql = "SELECT  max(date_) FROM  inventaire_table it WHERE it.Depot=$IdDepot AND it.Etat=1";
				}else {
					$sql = "SELECT  max(DateValid) FROM  inventaire_table it WHERE it.Depot=$IdDepot AND it.Etat=1";
				}
		$stmt2 = sqlsrv_query( $conn, $sql );
		if( $stmt2 === false ) {
			$error.="Erreur recupération date inventaire: ".sqlsrv_errors() . " <br/> ";
		}
		sqlsrv_fetch($stmt2) ;
		$DateInv = sqlsrv_get_field( $stmt2, 0);
	
		$where_facture=" and  cast(f.date AS date) >=  convert(date, '".$DateInv."',105) ";
		$where_mouvement=" and  cast( m.date AS date) >=  convert(date, '".$DateInv."',105) ";
	//	echo $where_facture;
			/*if($IdDepot==2){//if(($IdDepot==3)($IdDepot==2)){
				$where_facture=" and  cast(f.date AS date) between  convert(date, '".('29/12/2017')."',105) and convert(date, '".('01/01/2020')."',105) ";
			}*/
	

		/*	if(($IdDepot==1)){
				$where_facture=" and  cast(f.date AS date) between  convert(date, '".('26/01/2018')."',105) and convert(date, '".('01/01/2020')."',105) ";
			}*/
	//		ECHO $where_facture;
		
$sqlA ="SELECT  a.IdArticle,CB,a.Reference, a.Designation AS article,fa.Designation AS famille,col.colisagee Colisage,
		sum(
		CASE 
		   WHEN  dm.UniteVente='Pièce' AND m.type='entree' THEN dm.qte 
		END)
		AS qte_entreePcs,
		sum(
		CASE 
		   WHEN  dm.UniteVente  is null or  ( dm.UniteVente='Colisage'  AND m.type='entree' )THEN dm.qte 
		END)
		AS qte_entree,			
		(SELECT isnull(sum(df.qte),0) 
		FROM factures f INNER JOIN detailFactures df ON f.IdFacture=df.idFacture
		inner join colisages co on co.idArticle=df.idArticle		
		WHERE df.idArticle=a.IdArticle ".$where_facture." and EtatCmd=2 and f.idDepot=".$IdDepot." and df.UniteVente='Colisage'
		) AS qte_vendu,			
		(SELECT isnull(sum(df.qte),0) 
		FROM factures f INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 		
		WHERE df.idArticle=a.IdArticle ".$where_facture." and EtatCmd=2 and df.UniteVente='Pièce' and f.idDepot=$IdDepot ) 
		AS qte_venduPcs,		
		(select SUM(
					df.ttc)
					FROM factures f INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
									
					WHERE df.idArticle=a.IdArticle ".$where_facture." and EtatCmd=2 and f.idDepot=$IdDepot 
					) AS CA,		
		(SELECT isnull(sum(dmo.qte),0) FROM mouvements mo INNER JOIN detailMouvements dmo 
		ON dmo.idMouvement = mo.idMouvement WHERE mo.type LIKE 'Entree' 
	     and dmo.idArticle=a.IdArticle ) AS qteEntreeGlobal,
		AVG(dm.pa) as		 PrixAchat,
	    SUM(
		 CASE 
		   WHEN  dm.UniteVente='Pièce' THEN dm.pa*dm.qte
		   else  dm.pa*dm.qte*col.colisagee
		 END) as Valeur
			FROM mouvements m 
			INNER JOIN detailMouvements dm ON dm.idMouvement = m.idMouvement
			INNER JOIN articles a ON a.IdArticle=dm.idArticle 
			inner join colisages col on col.idArticle=a.idArticle
			INNER JOIN gammes g ON g.IdGamme=a.IdFamille
			INNER JOIN Sousfamilles s ON s.idSousFamille=g.IdSousFamille
			INNER JOIN familles fa ON fa.idFamille=s.idFamille
			WHERE m.idDepot=$IdDepot  and  fa.idFamille=2025  ".$where_mouvement." 
			";		
			
    $params = array();//WHERE m.type LIKE 'Entree' and m.idDepot=$IdDepot " . $where_mouvement." and  fa.idFamille=2025  ";
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	if(isset($_POST['ref']) && ($_POST['ref']!='') )
	{	
	   $sqlA .=" AND a.Reference like ? " ;
	   $params = array("%".$_POST['ref']."%");
	}	
	$sqlA .=" GROUP BY a.IdArticle,CB,a.Reference,a.Designation,fa.Designation,col.colisagee  ORDER BY a.Designation ";
//echo $sqlA;
	$stmt=sqlsrv_query($conn,$sqlA,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes."<br>";
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "Designation";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "DESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	//$sqlC = " ORDER BY $cTri $oTri ";//LIMIT $min,$max ";
	$sql = $sqlA;//.$sqlC;
/*execSQL($sql);*/
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
//echo $sql;
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
						<?php echo $trad['msg']['AucunResultat']; ?>
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
	<table width="100%" border="0">
      <tr class="entete">
		<td width="2%"><?php echo $trad['label']['reference']; ?></td>	  
        <td width="20%"><?php echo $trad['label']['Article']; ?></td>
		<td width="3%"><?php echo $trad['label']['Colisage']; ?></td>
        <!--td width="20%"><?php echo $trad['label']['Famille']; ?></td-->
		<td width="4%"><?php echo $trad['label']['PrixAchat']; ?> (<?php echo $trad['label']['riyal'];?> )</td>
		<td width="12%" style="background:#8bfa9d;color:#333333"><?php echo $trad['label']['qteentree']; ?></td>	
		<td width="8%" style="background:#8bfa9d;color:#333333; display:none;" ><?php echo $trad['label']['Valeur'] ;  ?>(<?php echo $trad['label']['riyal']." ".$trad['label']['ValTTC'];?>)</td>		
		<td width="12%"  style="background:#fc8f8f;color:#333333"><?php echo $trad['label']['qteVendu']; ?></td>
<!--td width="3%" style="background:#fc8f8f;color:#333333">Prix vente</td-->		
		<td width="8%"  style="background:#fc8f8f;color:#333333"><?php echo $trad['label']['ca']; ?> (<?php echo $trad['label']['riyal']." ".$trad['label']['ValTTC'];?>)</td>	
	  <td width="12%" style="background:#92a2f0;color:#333333 "><?php echo $trad['label']['QteReste'];?></td>
	  <td width="8%" style="background:#92a2f0;color:#333333; display:none;"><?php echo $trad['label']['ValeurStock'] ;  ?>(<?php echo $trad['label']['riyal']." ".$trad['label']['ValTTC'];?>)</td>
        <td width="10%" colspan="2" style="display:none">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFACNUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td>
  </tr>
<!--<div id="cList">-->
	<?php
		$i=0;$TotalCA=0;$TotalValEntree=0;$TotalValRest=0;
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
		// convert qte entrée box  en pcs 
		$QtePcsToBox=0;	$QteBoxGloVd=0;$QteRestGloPcs=0;
		$NbrPcs=0;$NbrPcsVd=0;
		
		if ($row['qte_entreePcs']>=$row['Colisage']) {
			$QtePcsToBox=$row['qte_entreePcs']/$row['Colisage'];
			$NbrPcs=$row['qte_entreePcs']%$row['Colisage'];
			$QteBoxGlo=floor($QtePcsToBox+$row['qte_entree']);
		}else {
			$NbrPcs=$row['qte_entreePcs'];
			$QteBoxGlo=$row['qte_entree'];
		}
		
		if ($row['qte_venduPcs']>=$row['Colisage']) {	
			$QtePcsToBoxVd=$row['qte_venduPcs']/$row['Colisage'];	
			$NbrPcsVd=fmod($row['qte_venduPcs'] , $row['Colisage']);//	ECHO $QtePcsToBoxVd+$row['qte_vendu'];
			$QteBoxGloVd=floor($QtePcsToBoxVd+$row['qte_vendu']);
			/*echo " qte_vendu ".$row['qte_vendu']."<br>";
			echo " QtePcsToBoxVd ".$QtePcsToBoxVd."<br>";
			echo " QteBoxGloVd ".$QteBoxGloVd."<br>";*/			
		}else {
			$NbrPcsVd=$row['qte_venduPcs'];
			$QteBoxGloVd=$row['qte_vendu'];
		}
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $c; ?>">
				<td align="<?php $_SESSION['align'] ; ?>"  > <?php echo $row['Reference']; ?> </td>			
				<td align="<?php $_SESSION['align'] ; ?>"  > <?php echo $row['article']; ?> </td>
				<td align="right"  > <?php echo $row['Colisage']; ?> </td>
				<!--td align="<?php $_SESSION['align'] ; ?>" > <?php  echo $row['famille'];?> </td-->
				<td align="right"  > <?php echo number_format($row['PrixAchat'], 2, '.', ' '); ?> </td>					
				<td align="right " style="background:#8bfa9d; font-weight:bold;font-size:14px;"  >
				<?php if ($QteBoxGlo!=0) echo number_format($QteBoxGlo, 0, '.', ' ')." ".$trad['label']['Boxs'] ;?>  
				<?php if ($NbrPcs!=0) echo "   ".number_format($NbrPcs, 0, '.', ' ')." ".$trad['label']['NbrPiece'] ;?>
				</td>
				<td align="right" style="background:#8bfa9d; font-weight:bold;font-size:14px; display:none;">
				<?php 
				$TotalValEntree+=$row['Valeur'];
				echo number_format($row['Valeur'], 2, '.', ' ') ; ?> </td>					
				<td align="right " style="background:#fc8f8f; font-weight:bold;font-size:14px;" >
				<?php if ($QteBoxGloVd!=0)  echo number_format($QteBoxGloVd, 0, '.', ' ')." ".$trad['label']['Boxs'] ;?>  
				<?php if ($NbrPcsVd!=0) echo "   ".number_format($NbrPcsVd, 0, '.', ' ')." ".$trad['label']['NbrPiece'] ;?>
				</td>
				<!--td align="right"  > <?php //echo number_format($row['tarif'], 2, '.', ' '); ?> </td-->
				<td align="right"  style="background:#fc8f8f; font-weight:bold;font-size:14px;" > 
				<?php 
				$TotalCA+=$row['CA'];
				if( $row['CA']!=0) echo number_format($row['CA'], 2, '.', ' '); ?> </td>
				<?php 
							// qte entrée moins qte vendue							
							$QteVdGloEnPcs=(($QteBoxGloVd*$row['Colisage'])+$NbrPcsVd);
							
							$QteStockPcs=(($QteBoxGlo*$row['Colisage'])+$NbrPcs);
							
							$QteRestGloBox=($QteStockPcs-$QteVdGloEnPcs)/$row['Colisage'];
							$QteRestGloPcs=($QteStockPcs-$QteVdGloEnPcs)%$row['Colisage'];
						//	echo $QteRestGloPcs;
												
							//if ($NbrPcs!=0) $QteResterPcs=$NbrPcs-$NbrPcsVd; else $QteRestGloPcs=$NbrPcsVd; 
							
							if (($QteRestGloBox==0)&&($QteRestGloPcs==0)) $color="#fb5b5b"; else $color="#92a2f0";?>
							<td align="right"  style="background:<?php echo $color;?>; font-weight:bold;font-size:14px;" > 							
							<?php if (($QteRestGloBox==0)&&($QteRestGloPcs==0)) echo "0"; else {
								if ($QteRestGloBox!=0)  echo floor($QteRestGloBox)." ".$trad['label']['Boxs'] ;?>  
							<?php if ($QteRestGloPcs!=0) echo "   ".number_format($QteRestGloPcs, 0, '.', ' ')." ".$trad['label']['NbrPiece'] ;
							}?>
							 </td>
							
							<?php
							/*
							if( $NbrPcsVd!=0) $QteRestGloPcs= $row['Colisage']-$NbrPcsVd;
							if (($QteRestGloBox==0)&&($QteRestGloPcs==0)) $color="#fb5b5b"; else $color="#92a2f0";?>
							<td align="right"  style="background:<?php echo $color;?>; font-weight:bold;font-size:14px;" > 							
							<?php if (($QteRestGloBox==0)&&($QteRestGloPcs==0)) echo "0"; else {
								if ($QteRestGloBox!=0)  echo floor($QteRestGloBox)." ".$trad['label']['Boxs'] ;?>  
							<?php if ($QteRestGloPcs!=0) echo "   ".number_format($QteRestGloPcs, 0, '.', ' ')." ".$trad['label']['NbrPiece'] ;
							}?>
							 </td>			
*/
							 ?>
							 <td align="right"  style="background:<?php echo $color;?>; font-weight:bold;font-size:14px; display:none;" > 
							<?php
							$ValRest=((floor($QteRestGloBox)*$row['Colisage'])+$QteRestGloPcs )*$row['PrixAchat'];
							$TotalValRest+=$ValRest;
							echo number_format($ValRest, 2, '.', ' '); ?> </td>
				 </tr>
			 <?php
			$i++;
		}
	?>	
	<tr class="entete">
			<td colspan="5" ALIgn="right" style=' display:none;'>Total valeur Entrée</td>
			<td ALIgn="right"  style=' display:none;'><?php echo number_format($TotalValEntree, 2, '.', ' ') ; ?></td>			
			<td ALIgn="right" colspan="6" ><?php echo $trad['label']['TotalVente']; ?></td>
			<td ALIgn="right"><?php echo number_format($TotalCA, 2, '.', ' ') ; ?></td>
			<td></td>
			<td ALIgn="right" style=' display:none;'> Total dépôt</td><td ALIgn="right" style=' display:none;'>
			<?php echo number_format($TotalValRest, 2, '.', ' ') ; ?></td>
	</tr>
    </table>
	<!--</div>-->
    </form>
    <?php
}
?>
<script language="javascript" type="text/javascript">
		$(document).ready(function(){
			$('input[title]').qtip({				
				style		: {		classes	: 'ui-tooltip-rounded ui-tooltip-shadow'	},
				position	: {
					my : 'bottom center',
					at	: 'top center'
				},
				show		: {
					effect	: function(offset) {	$(this).show('bounce', null, 10);	}
						
				}   		  
			});				
		});	
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'stock.php?delPlusieursArticle',clearForm:false});		
						}
					});
				}			
		}	
	</script>
<?php
	exit;
}
include("header.php");
?>
<div class="contenuBack">
<div id="brouillon" style="display:block">  </div> 
<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['statistic']; ?>&nbsp;<img src="images/tri.png" />
    &nbsp;&nbsp;<?php echo $trad['Menu']['stock']; ?> </div>

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
					<div class="etiqForm" id="SYMBT" ><strong><?php echo $trad['label']['reference']; ?>:</strong> </div>				</td>
					<td width="30%">
						<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
						<div align="<?php $_SESSION['align'] ; ?>">
							<input class="formTop"  name="ref" id="ref" type="text" size="25" />	
						</div>
					</td>
			
				
				</tr>			  
			 </table>
			 
		 </div>
	</form>
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>

<div id="formRes" style="overflow-y:scroll;min-height:300px;width:1200px;"></div>
<input type="hidden" id="act"/>
</div>
<div id="boxClient"> </div>
<script language="javascript" type="text/javascript">
$('#IdDepot').multipleSelect({
	filter: true,placeholder:'<?php echo $trad['label']['selectDepot'] ;?>',single:true,maxHeight: 170,width:220
});
	   
$(document).ready(function(){	
		calendrier("DateD");
		calendrier("DateF");
  		$('#formRes').html('<center><br/><br/><img src="images/loading.gif" /></center>').load('stock.php?aff');
				$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	850,
					height			:	350,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Ajout / Modification du vendeur',
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
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'stock.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'stock.php?rech'});
		patienter('formRes');
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='stock.php?add';	
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='stock.php?mod&ID='+id;
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
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
                                                Nom: "required",
												Prenom:"required",
												Adresse:"required",
												Cin:"required",
												Tel: "required tel",
												Mail:{
														"required": true,
														"email": true
													 }
                                        }  ,
								messages : {
												Mail:" "  
										    }
		});
	var test=$(form).valid();

		if(test==true){		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'stock.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'stock.php?goAdd',
														method			:	'post'
													}); 
													
												
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
	/*$('body').on('keypress', '#Cin', function(args) {alert("keyCode : " + args.keyCode );
   if (args.keyCode == 13) {alert("ggg");
       $("#rechercher").click();
       return false;
   }
   });*/
   $(document).keypress(function(e) {
    if(e.which == 13) {
        rechercher();
    }
	});
</script>



<?php
include("footer.php");
?>
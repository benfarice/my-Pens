<?php 
require_once('connexion.php');
include("php.fonctions.php");
include("frontend2/fonctionCalcule.php");
//sqlsrv_query("SET NAMES UTF8");
SQLSRV_PHPTYPE_STRING('UTF-8') ; 
if(!isset($_SESSION))
{
	session_start();
}

include("lang.php");
$tableInser = "mouvements";
$sansDoublons = "NomCaisse";
$cleTable = "idMouvement";
$IdDepot=$_SESSION['IdDepot'];
$UserId="1";
$Operateur=1;

require('frontend2/fpdf.php');

class PDF extends FPDF
{
// En-tête
function Header()
{
    // Logo
   // $this->Image('../images/logo.png',10,6,30);
    // Police Arial gras 15
    $this->SetFont('Arial','B',15);
    // Décalage à droite
    $this->Cell(80);
    // Titre
    $this->Cell(30,10,'Bon de commande',0,0,'C');
    // Saut de ligne
    $this->Ln(20);
}





// Tableau coloré
function FancyTable($header, $data)
{
    // Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(176,176,176);
    $this->SetTextColor(0,0,0);
    $this->SetDrawColor(176,176,176);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // En-tête

    $w = array(50, 35, 30, 30, 30);
	echo sizeof($header);
    for($i=0;$i<sizeof($header);$i++)
    $this->Cell($w[$i],7,utf8_decode($header[$i]),1,0,'C',true);
    $this->Ln();
    // Restauration des couleurs et de la police
    $this->SetFillColor(233,233,233);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Données
    $fill = true;
	//print_r($data);//return;
    //foreach($data as $row)
  // for ( $i=0; $i<count($data);$i++) {
	foreach($data as $u=>$v){	
	//	echo $data[$i][0]."<br>";
		//Cell(width, height ,valeur, border, int ln [,align ,fond cellule,lien);
			if(is_array($v)){
			$fill = true;// pour application background  SetFillColor sur cellule
		//	$this->SetFillColor(24,105,243);//RGB  color
			$this->Cell($w[0],6,utf8_decode($v['Reference']),1,0,'L',$fill);
			$this->Cell($w[0],6,utf8_decode( $v['NomArt']),1,0,'L',$fill);
			$this->Cell($w[0],6,utf8_decode($v['NbrBox']),1,0,'L',$fill);
		/*	$this->Cell($w[0],6,utf8_decode($v['NbrPcs']),1,0,'L',$fill);
			$this->Cell($w[0],6,utf8_decode(Reference),1,0,'L',$fill);
			$this->Cell($w[0],6,utf8_decode(Reference),1,0,'L',$fill);*/
			 $this->Ln();
		
			/*	foreach($v as $k)
				{	//vendeur
					if(is_array($k)){
						$this->SetFillColor(128,172,247);
						$fill = true;
						$this->Cell($w[0],6,"",1,0,'L',$fill);
						$this->Cell($w[1],6,utf8_decode($k['Vendeur']),1,0,'L',$fill);
						$this->Cell($w[1],6,"",1,0,'L',$fill);
						$this->Cell($w[1],6,"",1,0,'L',$fill);
						$this->Cell($w[1],6,"",1,0,'L',$fill);
						 $this->Ln();
						 foreach($k as $r){
							 if(is_array($r)){
									$this->SetFillColor(255,255,255);
									$fill = true;
									$this->Cell($w[0],6,"",1,0,'L',$fill);
									$this->Cell($w[1],6,"",1,0,'L',$fill);
									$this->Cell($w[1],6,utf8_decode($r['Intitule']),1,0,'L',$fill);
									$this->Cell($w[1],6,utf8_decode($r['CA']),1,0,'L',$fill);
									$this->Cell($w[1],6,utf8_decode($r['CreditClt']),1,0,'L',$fill);
									 $this->Ln();
							 }
						 }
					}
					
				}*/
				 
			
			}
       /* $this->Cell($w[1],6,$data[$i][1],1,0,'L',$fill);
        $this->Cell($w[2],6,($data[$i][2]),1,0,'R',$fill);
        $this->Cell($w[3],6,($data[$i][3]),1,0,'R',$fill);*/
      
    }
//	return;
    // Trait de terminaison
    $this->Cell(array_sum($w),0,'','T');
}
// Pied de page
function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
function SetCol($col)
{
    // Positionnement sur une colonne
    $this->col = $col;
    $x = 10+$col*65;
    $this->SetLeftMargin(5);
    $this->SetX(120);
}
function CorpsChapitre($txt)
{
    // Police
    $this->SetFont('Times','',12);
    // Sortie du texte sur 6 cm de largeur
   // 
 //  $this->MultiCell(60,5,utf8_decode($txt));
   $this->MultiCell(110,3,utf8_decode($txt) ,0,'L',false);
	$x = $this->GetX();
	$y = $this->GetY();
 /*  $this->SetXY($x + 60, $y);
    $this->Ln();*/
    // Mention
    $this->SetFont('','I');
   // $this->Cell(0,5,"(fin de l'extrait)");
    // Retour en première colonne
    $this->SetCol(0);
}

function TitreChapitre($num, $libelle)
{
    // Titre
    $this->SetFont('Arial','',12);
    $this->SetFillColor(200,220,255);
    $this->Cell(0,6,"Chapitre $num : $libelle",0,1,'L',true);
    $this->Ln(4);
    // Sauvegarde de l'ordonnée
    $this->y0 = $this->GetY();
}
function AjouterChapitre($txt,$numCol,$titre)
{
  
  // Lecture du fichier texte
  // $this->TitreChapitre($numCol,$titre);
    $this->CorpsChapitre($txt);
}

}

if (isset($_GET['pdf'])) {

		$sql = "
			SELECT 
			i.id as IdInventaire,i.Numero,i.Superviseur,i.Date_ AS DateInf,i.Heure	,a.Designation NomArt,a.Reference,a.IdArticle,a.Reference,
			t.NBR_colisage NbrBox,t.NBR_piece NbrPcs,c.colisagee Colisage,i.Depot IdDepot,v.nom+' '+v.prenom Vendeur,t.stock_pda QteEncienPcs,
			d.Designation Depot,
			DateValid,i.Heure HeureInv,
				(SELECT pvHT FROM tarifs ta 
			  INNER JOIN ficheTarifs ft ON ft.idFiche = ta.idFiche 
			  WHERE ft.etat=1 AND ta.idDepot=1 AND ft.TypeVente=1 AND ta.idArticle=t.idArticle  ) AS PVGros,
				(SELECT pvHT FROM tarifs ta 
			  INNER JOIN ficheTarifs ft ON ft.idFiche = ta.idFiche 
			  WHERE ft.etat=1 AND ta.idDepot=1 AND ft.TypeVente=2 AND ta.idArticle=t.idArticle  ) AS PVDet
		FROM 
			Detail_inventaire_table  t
			inner join inventaire_table  i on i.Numero=t.Numero
			INNER JOIN articles a ON a.IdArticle = t.idArticle 
			INNER JOIN colisages c ON c.idArticle = a.idArticle
			inner join vendeurs v on v.idVendeur=i.superviseur
			inner join depots d on d.IdDepot=i.Depot
			where i.id = ? AND (i.Etat =1)";
	 $params = array($_GET['IdInventaire']);

//	echo $sql;
//arcourir($params);
		$resAff=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
			$nRes = sqlsrv_num_rows($resAff);
				if($nRes==0)
						{ ?>
							<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
								<?php echo $trad['msg']['AucunResultat'];?>
						</div>
						<?php
						return;
						}
					else {
						$html0 ="";$TitleM="";$html1 ="";$html ="";$html2 ="";$NumInv="";$Vendeur="";$DateInv="";
						
		$html2.='<BR>
					<table width="100%" border="1" id="tableID" align="center" style="border-collapse: collapse; 
					table-layout: fixed;border-color:#000; ">
					  <tr  class="" style=" height:170px;background:#dbeef3;color:#000;" >
									<td width=" 4%" align="center"> '.$trad['label']['Refe'].'</td>
									<td  width="15%" align="center">'.$trad['label']['Dsg'].'</td>
									<td  width="3%" align="center">'.$trad['label']['Col'].'</td>
									<td  width="4%" align="center">'.$trad['label']['PVGros'].'<BR>('.$trad['label']['riyal'].')</td>
									<td  width="4%" align="center">'.$trad['label']['PVDet'].'<BR>('.$trad['label']['riyal'].')</td>
									<td  width="5%" align="center">'.$trad['label']['StockC'].'</td>
									<td  width="5%" align="center">'.$trad['label']['StockP'].'</td>
									<td  width="5%" align="center">'.$trad['label']['InvC'].'</td>
									<td  width="5%" align="center">'.$trad['label']['InvP'].'</td>
									<td  width="5%" align="center">'.$trad['label']['TotalStockPcs'].'</td>
									<td  width="5%" align="center">'.$trad['label']['TotalInvPcs'].'</td>
									<td  width="5%" align="center">'.$trad['label']['Ecart'].'('.$trad['label']['NbrPiece'].')</td>
									<td  width="5%" align="center">'.$trad['label']['ValeurGros'].'('.$trad['label']['riyal'].')</td>
									<td  width="5%" align="center">'.$trad['label']['ValeurDet'].'('.$trad['label']['riyal'].')</td>
					  </tr>';
	$debutScript = microtime(true);$Ecart=0;
	$DsgDepot="";$HeureInv ="";$DateValidInv="";
		$nRes = sqlsrv_num_rows($resAff);
	//	echo $nRes;
		while($row = sqlsrv_fetch_array($resAff))
		{	
				$NumInv=$row['Numero'];$Vendeur=$row['Vendeur'];$DateInv=$row['DateInf'];
				$IdDepot=$row['IdDepot'];
				$DsgDepot=$row['Depot'];
				$DateValidInv=$row['DateValid'];	$HeureInv=$row['HeureInv'];//$HeureValide=$row['HeureValide'];
				$params= array($row['IdArticle'],$IdDepot) ;
				
				$qteTotalInvPcs=  qteDispoArticle($params,$conn,'reel');
				$qteInvBoite=floor($qteTotalInvPcs/ $row['Colisage']);
				$qteInvPcs= $qteTotalInvPcs % $row['Colisage'];
				$IdInventaire=$row['IdInventaire'];
				//$qteStockPcs=BoxToPcs($qteDispoEnBoite,$row['Colisage'])+$qteDispoEnPcs;
				$QteEncienPcsS=PcsToBox($row['QteEncienPcs'],$row['Colisage']);
				$qteEncienStockB=$QteEncienPcsS[0];
				$qteEncienStockPiece=$QteEncienPcsS[1];
	
		//	$qteEncienStockB=QteEncienPcsS;
			//$qteEncienStockPiece=BoxToPcs($row['QteEncienPcs'],$row['Colisage']);
			
				$Ecart=floatval($qteTotalInvPcs)-floatval($row['QteEncienPcs']);
				$ValeurGros=$Ecart*floatval($row['PVGros']);
				$ValeurDet=$Ecart*floatval($row['PVDet']);
				
				
				
			
			//<td align="right"  >&nbsp;&nbsp;'.$Ecart.'</td>
				$html2.='	
						<tr  height="40px" >
								<td align="right" >&nbsp;&nbsp;'. htmlentities($row['Reference']).'</td>
								<td align="left"  > &nbsp;&nbsp;'. htmlentities($row['NomArt']).' </td>
								<td align="right"  >&nbsp;&nbsp;'.$row['Colisage'].'</td>		
								<td align="right"  >&nbsp;&nbsp;'.number_format($row['PVGros'], 2, '.', ' ').'</td>		
								<td align="right"  >&nbsp;&nbsp;'.number_format($row['PVDet'], 2, '.', ' ').'</td>										
								<td align="right"  >&nbsp;&nbsp;'.$qteEncienStockB.'</td>
								<td align="right"  >&nbsp;&nbsp;'.$qteEncienStockPiece.'</td>
								<td align="right"  >&nbsp;&nbsp;'.$qteInvBoite.'</td>										
								<td align="right"  >&nbsp;&nbsp;'.$qteInvPcs.'</td>
								<td align="right"  >&nbsp;&nbsp;'.$row['QteEncienPcs'].'</td>
								<td align="right"  >&nbsp;&nbsp;'.$qteTotalInvPcs.'</td>										
								<td align="right"  >&nbsp;&nbsp;'.$Ecart.'</td>
								<td align="right"  >&nbsp;&nbsp;'.number_format($ValeurGros, 2, '.', ' ').'</td>
								<td align="right"  >&nbsp;&nbsp;'.number_format($ValeurDet, 2, '.', ' ').'</td>
						</tr>';
		}
	$html2 .= '</table><br />				
	<br />
	';
	
	//$head='<html> <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
	//	$head.='<style> body { font-family: arial, sans-serif; }				</style>				</head>				<body>';
	//$date = date_create($DateValidInv);
	$html0.='<div style="text-align:center ; width:100%; margin:0px 0; padding:7px;">
			Situation dépôt 
		<b>'.$DsgDepot.' </b> <br>'.$DateValidInv.'</div>';//
		$head='<html><body style="font-size:12px;font-family:verdana">';
		$html1='<table style="width:100%;" border="0">
		<tr> <td align="left" width="15%">
				Superviseur: </td><td align="left">'.htmlentities(($Vendeur)).'</td>';	
		$html1.='<td align="left" width="10%">Date : </td><td align="left">'.$DateInv.'</td></tr>';
		$html1.='<tr><td align="left">HEURE DEPART : </td><td>' .$HeureInv.'</td>';
		$html1.='<td align="left" width="15%">HEURE FIN : </td><TD align="left"></td></tr></table>';
	//	echo $html1;return;
	
			$html=$html0.$html1.$html2;
			//ECHO $html ;return;
			$content = ($head.$html.'</body></html>');
			$_SESSION['html'] = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
			require("dompdf/dompdf_config.inc.php");
			$dompdf = new DOMPDF();
			$dompdf->load_html(utf8_decode($_SESSION['html']));
			$dompdf->set_paper("a4", "portrait");
			//$dompdf->set_paper("a3", "landscape");
			$dompdf->render();
			//$dompdf->stream("releve.pdf", array("Attachment" => true));
			$name='inventaire '.date('d-m-Y H-i').'.pdf';	
			// Output the generated PDF to Browser
			//$dompdf->stream();
			file_put_contents("inventaire_pdf/".$name, $dompdf->output());	
		echo "exporter";
			?>
				<script language="javascript" type="text/javascript">
					var file="<?php echo $name;?>";
					window.open("download_file.php?DownloadPdf&&File="+file,'_self');				
				</script>
			<?php 
			}
			exit;
}

if(isset($_GET['VideSession'])){
	unset($_SESSION['lignes']);
	exit;
}
if(isset($_GET['goValidStock'])){
//	var_dump($_POST["test"]);
	//echo "d".$_POST["test"]."    ".$_POST["IdInventaire"];return;
//parcourir($_POST);return;

 $error="";
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
/// vider le stock du dépot
	$reqDel ="delete from detailMouvements  where  idMouvement in 
					(SELECT idMouvement FROM mouvements where idDepot=".$_POST["IdDepot"]." AND [date]< convert(date, '01/02/2018',105) ) ";
							$stmt1 = sqlsrv_query( $conn, $reqDel, array() );
							if( $stmt1 === false ) {
								$errors = sqlsrv_errors();
								$error.="Erreur : Suppression mouvements  depot".$errors[0]['message'] . " <br/> ";								
						}	
						
	//$reqDel ="delete from mouvements where idDepot=".$_POST["IdDepot"];
		$reqDel ="delete from mouvements where idDepot=".$_POST["IdDepot"]." AND [date]< convert(date, '01/02/2018',105) " ;
							$stmt1 = sqlsrv_query( $conn, $reqDel, array() );
							if( $stmt1 === false ) {
								$errors = sqlsrv_errors();
								$error.="Erreur : Suppression mouvements  depot".$errors[0]['message'] . " <br/> ";								
						}	
	
$RefEntree= "NE".Increment_Chaine_F("Reference","mouvements","idMouvement",$conn,"",array());
//echo $RefEntree;return;
//$DateEntree=date("Y-m-d");

$DateEntree = date_create(date("Y-m-d"));
$HeureEntree=date("H:i:s");
$reqInser1 = "INSERT INTO mouvements ([reference] ,[idOperateur]  ,[fournisseur] ,[livreur] ,[date],[heure],[idDepot],[type]) 
				values 	(?,?,?,?,?,?,?,?)";
$params1= array(
				$RefEntree,
				$Operateur,
				1,
				"Inventaire frontend",
				$DateEntree,$HeureEntree,$_POST['IdDepot'],'Entree'
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout fiche entree ".$errors[0]['message'] . " <br/> ";
}

	//---------------------------IDFiche--------------------------------//
$sql = "SELECT max(idMouvement) as IdFiche FROM mouvements";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdFiche entree: ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFiche = sqlsrv_get_field( $stmt2, 0);		
		
 for( $i= 0 ; $i < count($_POST['idArticle']) ; $i++ )
{	
//		echo $_POST['idArticle'][$i].'   '.$_POST['QteEncienPcs'][$i];return;
		
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
				if(isset($_POST['NbrBox'][$i])){
				if(($_POST['NbrBox'][$i]!=0) && ($_POST['NbrBox'][$i]!="")&& ($_POST['NbrBox'][$i]!=null))
				{ 
						$reqInser2 = "INSERT INTO  detailmouvements([idArticle],[qte],[pa],[idDepot],idMouvement,UniteVente ) values (?,?,?,?,?,?)";
						$params2= array($_POST["idArticle"][$i],$_POST["NbrBox"][$i],$PrixAchat,$_POST["IdDepot"],$IdFiche,"Colisage") ;
					
					$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
					if( $stmt3 === false ) {

						$errors = sqlsrv_errors();
						$error.="Erreur : Ajout detail entree par box ".$errors[0]['message'] . " <br/> ";
						break ;
					}	
				}
				}
				if(isset($_POST['NbrPcs'][$i])){
					if(($_POST['NbrPcs'][$i]!=0) && ($_POST['NbrPcs'][$i]!=""))
					{ 
							$reqInser2 = "INSERT INTO  detailmouvements([idArticle],[qte],[pa],[idDepot],idMouvement,UniteVente ) values (?,?,?,?,?,?)";
							$params2= array($_POST["idArticle"][$i],$_POST["NbrPcs"][$i],$PrixAchat,$_POST["IdDepot"],$IdFiche,"Pièce") ;
						
						$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
						if( $stmt3 === false ) {

							$errors = sqlsrv_errors();
							$error.="Erreur : Ajout detail entree par pièce ".$errors[0]['message'] . " <br/> ";
							break ;
						}	
					}
				}
		
		// enregistrer encien stock par pcs dans la table detail_inventaire table
		 $reqInserUp = "update Detail_inventaire_table set stock_pda=".$_POST['QteEncienPcs'][$i]." where Numero = ?  and idArticle=".$_POST["idArticle"][$i]."";
		// echo "mmm".$_POST["NumInventaire"];return;
					$paramsUp= array($_POST["NumInventaire"]) ;
					$stmtUp = sqlsrv_query( $conn, $reqInserUp, $paramsUp );
					if( $stmtUp === false ) {
						$errors = sqlsrv_errors();
						$error.="Erreur :  enregistrer encien stock par pcs".$errors[0]['message'] . " <br/> ";
						//break ;
					}
					
}
	
		//echo modifier etat inventaire_table	
	$date=new DateTime(); //this returns the current date time
	$dateValide = date('d/m/Y');
	
	//echo $result;
	
		 $reqInserUp = "update inventaire_table set Etat=1 ,DateValid='".$dateValide."' where id = ? ";
					$paramsUp= array($_POST["IdInventaire"]) ;
					$stmtUp = sqlsrv_query( $conn, $reqInserUp, $paramsUp );
					if( $stmtUp === false ) {
						$errors = sqlsrv_errors();
						$error.="Erreur : modif etat inventaire".$errors[0]['message'] . " <br/> ";
						//break ;
					}
				//	echo "modif".$_POST["IdInventaire"];
				
if( ($error=="" ) ) {	
	sqlsrv_commit( $conn );    ?>
		<script type="text/javascript"> 
			alert('<?php echo $trad['msg']['messageAjoutSucces'];?>');
			$("#box").dialog('close');
			rechercher();			
		</script>	
		
<?php
}else 
{
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
		$('#listBox').load('validation_stock.php?list');
				//charger('validation_stock.php?list');
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
			<table width="100%">
			     <tr class="entete">
					<td><?php echo $trad['label']['CodeArticle'];?> </td>
					<td><?php echo $trad['label']['Article'];?> </td>
					<td > <?php echo $trad['label']['Qte'];?> </td>
					 <td> <?php echo $trad['label']['PrixAchat'];?> </td>
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
				<td width="110"><?php echo $row['CodeaBarre']; ?></td>
					<td width="110">sssssss<?php echo $row['NomArt']; ?></td>
					<td width="230" align="right"><?php echo $row['Qte']; ?></td>
					<td width="50" align="right" style=""><?php echo $row['Tarif']; ?>	</td>
				
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
			//echo $_SESSION['totalHT'];
			
	}		
exit();
}
if (isset($_GET['detail'])){
	//echo $_GET['IdInventaire'];return;
	/*$sql = "
			SELECT 
			i.id as IdInventaire,i.Numero,i.Superviseur,i.Date_ AS DateInf,i.Heure	,a.Designation NomArt,a.Reference,a.IdArticle,a.Reference,
			t.NBR_colisage NbrBox,t.NBR_piece NbrPcs,c.colisagee Colisage,i.Depot IdDepot
		FROM 
			Detail_inventaire_table  t
			inner join inventaire_table  i on i.Numero=t.Numero
			INNER JOIN articles a ON a.IdArticle = t.idArticle 
			INNER JOIN colisages c ON c.idArticle = a.idArticle
			where i.id = ?";*/
		$sql="
				SELECT 
							i.id as IdInventaire,i.Numero,i.Superviseur,i.Date_ AS DateInf,i.Heure	,a.Designation NomArt,a.Reference,a.IdArticle,a.Reference,
							t.NBR_colisage NbrBox,t.NBR_piece NbrPcs,c.colisagee Colisage,i.Depot IdDepot
							,'' QteStock
						FROM 
							Detail_inventaire_table  t
							inner join inventaire_table  i on i.Numero=t.Numero
							inner join  articles a ON a.IdArticle = t.idArticle 
							INNER JOIN colisages c ON c.idArticle = a.idArticle
							where i.id=? 
				UNION
				SELECT 
							'' as IdInventaire,'' Numero,'' Superviseur,'' DateInf,'' Heure	,a.Designation NomArt,a.Reference,a.IdArticle,a.Reference,
							'' NbrBox,'' NbrPcs,c.colisagee Colisage,m.IdDepot IdDepot
							,'' QteStock
						FROM 
							detailMouvements  dm
							INNER JOIN mouvements m ON m.idMouvement=dm.idMouvement
							inner join  articles a ON a.IdArticle = dm.idArticle 
							INNER JOIN colisages c ON c.idArticle = a.idArticle
							where m.idDepot=? 
								and dm.idArticle not in(select idArticle 
									from Detail_inventaire_table di
									inner join inventaire_table  i on i.Numero=di.Numero where i.id=".$_GET['IdInventaire']." ) 								
							GROUP BY
								a.Designation ,a.Reference,a.IdArticle,a.Reference,
									c.colisagee ,m.IdDepot 
						ORDER BY idinventaire DESC 
						";
			
	 $params = array($_GET['IdInventaire'],$_GET['IdDepot']);

	// echo $sql;
//parcourir($params);
		$resAff=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
			$nRes = sqlsrv_num_rows($resAff);
				if($nRes==0)
						{ ?>
							<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
								<?php echo $trad['msg']['AucunResultat'];?>
						</div>
						<?php
						return;
						}
					else {
?>
<div id="res" style="padding:5px">&nbsp;</div>
        	<BR>
<DIV class="ListeCmd">
	<TABLE>
	<TR class="entete">
			<td style="width:10%"><?php echo $trad['label']['Refe'];?> </td>	
			<td  style="width:32%"><?php echo $trad['label']['Article'];?></td>	
			<td  style="width:7%"><?php echo $trad['label']['Colisage'];?></td>	
			<td style="width:20%"><?php echo $trad['label']['QteStoc'];?> </td>	
			<td style="width:15%"><?php echo $trad['label']['QteInvBoite'];?></td>	
			<td style="width:22%"><?php echo $trad['label']['QteInvPcs'];?> </td>	
	</tr>
	</table>
	<div class="clair"></div>
	<div style="height:400px;overflow:scroll;" >
<form id="formAdd" method="post" name="formAdd"> 
<input type="hidden" value="<?php echo $_GET['IdInventaire'];?>" id="test" name="test">

<?php 
$k=0;$i=0;

while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){

$k++;
if($k%2 == 0) $c = "pair";
else $c="impair";
			//$qteDispo=number_format($qteDispo,0," "," ")
	//		if($row['UniteVente']=="Colisage") $UniteVente="Boite";else $UniteVente="Pièce";
			$params= array($row['IdArticle'],$_GET['IdDepot']) ;
	$qteDispo=  qteDispoArticle($params,$conn,'reel');
//	echo $qteDispo;
	$qteDispoEnBoite=floor($qteDispo/ $row['Colisage']);
	$qteDispoEnPcs= $qteDispo % $row['Colisage'];
	if($row['IdInventaire']!=0) {
		$IdInventaire=$row['IdInventaire'];
		$NumInventaire=$row['Numero'];
	}
?>
	<input type="hidden" value="<?php  echo $NumInventaire; ?>" id="NumInventaire" name="NumInventaire" />
	<input type="hidden" value="<?php  echo $_GET['IdInventaire'];?>" id="IdInventaire" name="IdInventaire" />
	<div  class="<?php echo $c; ?> ligne" >
<input type="hidden" value="<?php  echo $row['NomArt']; ?>" id="IdClient" name="NomArt[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['Reference']; ?>" id="IdClient" name="Reference[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $_GET['IdDepot'] ?>" id="IdDepot" name="IdDepot">
<input type="hidden" value="<?php  echo $qteDispo ?>" id="QteEncienPcs[<?php echo $i; ?>]" name="QteEncienPcs[<?php echo $i; ?>]">

<input type="hidden" value="<?php echo $row['Colisage']; ?>" name="Colisage[<?php echo $i; ?>]" id="Colisage<?php echo $i; ?>"/>
<input type="hidden" value="<?php  echo $row['IdArticle']; ?>" name="idArticle[<?php echo $i; ?>]">	

	<div align="left" class="divArticleL" style="width:10%"  ><?php echo $row['Reference'];?></div>
			<div align="left" class="divArticleL" style="width:33%"  ><?php echo $row['NomArt'];?>		</div>
					<div align="left" class="divArticleL" style="width:7%"  ><?php echo $row['Colisage'];?> </div>
			<div  align="right" class="divArticleL" style="width:20%" ><?php				
			echo $qteDispoEnBoite." ".$trad['label']['Boxs']." ".$qteDispoEnPcs." ".$trad['label']['NbrPiece'];?>  </div>			
			<div  align="right" class="divArticleL" style="width:16%" >	
				<?php if(($row['NbrBox']!=0)|| ($row['NbrPcs']!=0)){?>
				<input class="numberOnly" type="text" value="<?php  echo $row['NbrBox'] ;?>"  style="text-align:right;" 
				size="10" name="NbrBox[]" onkeypress="return isEntier(event) " />
				</div>
							<div  align="right" class="divArticleL" style="width:13%" >	
					<input class="numberOnly" type="text" style="text-align:right;" value="<?php  echo $row['NbrPcs'] ;?>" 
				size="10" name="NbrPcs[]" onkeypress="return isEntier(event) " />
				<?php }?>
				</div>	
		</div>
<?php 
	$i++;
 } echo "id inv ".$_GET['IdInventaire']; ?>


</form>
</div>
</div>
<?php }?>     	
<script language="javascript" type="text/javascript">
</script>
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){
	// etat=2 cad inventaire colturer

	$IdCltGrp=""; $params = array();
	$where="";
	$sqlA = "
SELECT 
			i.id as IdInventaire,i.Numero,i.Superviseur,i.Date_ AS DateInv,i.Heure,v.nom+' '+v.prenom Vendeur,i.Etat
			,d.Designation Depot,i.Depot IdDepot
		FROM 
			 inventaire_table i
			 	inner join Detail_inventaire_table  t on i.Numero=t.Numero
			inner join vendeurs v on v.idVendeur=i.superviseur
			inner join depots d on d.idDepot=i.Depot
			where i.Etat<>0
			GROUP BY  		i.id ,i.Numero,i.Superviseur,i.Date_ ,i.Heure,v.nom,v.prenom ,i.Etat
			,d.Designation ,i.Depot 
			
		";
	//	echo $IdDepot ;return;

	if(isset($_POST['IdDepot']) &&($_POST['IdDepot']!="")  )
	{
		$where=" 	and    i.Depot = ".$_POST['IdDepot'];
	}	
	/*********** rech par reference *****************/
	if((isset($_POST["NumFiche"])) && ($_POST["NumFiche"]!="")){
		$reference=$_POST["NumFiche"];
		if($where!="")  $where.=" and i.Numero = '".$_POST['NumFiche']."'";
		else $where=" and   i.Numero = '".$_POST['NumFiche']."'";
	}
	$sqlA=$sqlA.$where;
	//st $sqlA;
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	//$stmt=sqlsrv_query($conn,$sqlA,$params);
		$resAff = sqlsrv_query($conn,$sqlA,$params,$options) or die( print_r( sqlsrv_errors(), true));
	$ntRes = sqlsrv_num_rows($resAff);
	//echo "num : ".$ntRes."<br>";
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "i.id ";
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
//echo $sql;return;
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
							<?php echo $trad['msg']['AucunResultat'];?>
					</div>
					<?php
		}
else
{
	?>
		<form id="formSelec" method="post">
	<table width="100%" border="0">
      <tr class="entete">
	   <td   width="30%" style=""><?php echo $trad['label']['Depot'];?>  
        <td ><?php echo $trad['label']['Ref'];?> </td>
        <td width="30%"> <?php echo $trad['label']['Vendeur'];?>   </td>		
		<td width=""> <?php echo $trad['label']['date'];?> <?php //echo $trad['label']['Heure'];?>   </td>
		       
        <td   style=""><?php echo $trad['label']['Action'];?>           
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
				<td align="<?php echo $_SESSION['align'];?>" > <?php 	echo stripslashes($row['Depot']);?> </td>
				<td align="<?php echo $_SESSION['align'];?>"  > <?php  echo $row['Numero']; ?> </td>
				<td align="<?php echo $_SESSION['align'];?>" > <?php 	echo stripslashes($row['Vendeur']);?> </td>
				<td align="center"><?php 
				/*$date = date_create($row['DateInv']);
				$heure = date_create($row['Heure']);
				echo date_format($date, 'd/m/Y');
				echo " ".date_format($heure, 'H:i');*/
						echo $row['DateInv'];?></td>	
				<td align="center"> 
				<?php if (( $row['Etat']==2)) { ?>
					  <input type="button" title="<?php echo $trad['button']['Valider'];?>"  
					  value="<?php echo $trad['button']['Valider'];?>" class="detail tool"  STyle="width:120px;background:green;"
					  onClick="DetailInv('<?php echo $row['IdInventaire']; ?>','<?php echo $row['IdDepot']; ?>');" />
				<?php } else if ( $row['Etat']==1) {?>
				<input type="button"  style="display:" title="Exporter PDF"   
				value="<?php echo $trad['label']['ExporterPDF'];?>" class="detail tool"
				onClick="Exporter('<?php echo $row['IdInventaire']; ?>','<?php echo $row['IdDepot']; ?>');" />
				<?php }?>
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
<script src="js/jquery.multiple.select2.js" type="text/javascript"></script>
<div class="pageBack" >

<div id="search"> </div>

<div id="box"> </div>
<div class="contenuBack">
	<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['gestionstock'];?> &nbsp;<img src="images/tri.png" />
		&nbsp;<?php echo $trad['Menu']['ValidationStock'];?></div>

	

	<form id="formRechF" method="post" name="formRechF" > 
		<div id="formRech" style="width:900px;">	<!--Recherche CH -->
		<table width="100%" border="0"  >
			  <tr>
			
				<td  align="right">
				<div class="etiqForm" id="SYMBT" > <?php echo $trad['label']['Depot'];?> : </div>				</td>
				
					<td >
				<div align="left" >	
			 <select id="IdDepot" name="IdDepot" Class="Select NumFiche" style="width:250px">
			<option value=""><?php echo $trad['label']['tousSelect'];?></option>
					  <?php 
				$sql = "select idDepot,Designation from depots order by idDepot desc ";
  	            $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
				while ($donnees =  sqlsrv_fetch_array($reponse))
				{
				?>
				<option value="<?php echo $donnees['idDepot'] ?>"><?php echo $donnees['Designation']?></option>
			  <?php
			   }
			  ?> </select>
						</div>
				</td>
				
				<td  align="right">
				<div class="etiqForm" id="SYMBT" > <?php echo $trad['label']['NumInventaire'];?> : </div>				</td>
				<td >
				<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
				<div align="left" >
	
			 <select id="NumFiche" name="NumFiche" Class="Select NumFiche" style="width:250px">
			<option value=""><?php echo $trad['label']['tousSelect'];?></option>
					  <?php 
				$sql = "select Numero from inventaire_table where Etat<>0 order by id desc ";
  	            $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
				while ($donnees =  sqlsrv_fetch_array($reponse))
				{
				?>
				<option value="<?php echo $donnees['Numero'] ?>"><?php echo $donnees['Numero']?></option>
			  <?php
			   }
			  ?> </select>
						</div>
				</td>			
		      <td rowspan="2"  >	<span class="actionForm">      
          <input name="button" type="button" id="Rechercher"  onClick="rechercher();"
		  value="<?php echo $trad['button']['Rechercher'];?>  " class="bouton32" action="rech" title="<?php echo $trad['button']['Rechercher'];?> "
		  />
			      <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider'];?>" style="display:none"
				  class="bouton32" action="effacer" title="<?php echo $trad['label']['vider'];?>"/></span><br/></td>
		
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
<div id="formRes"  style="overflow-y:scroll;min-height:280px"></div>
<input type="hidden" id="act"/>
  </div>

	<?php include("footer.php"); ?>
</div>

<script language="javascript" type="text/javascript">
 $('#NumFiche').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['SelectNumEntree'];?>',single:true,maxHeight: 100
	});
	$('#IdDepot').multipleSelect({
	filter: true,placeholder:'<?php echo $trad['label']['selectDepot'] ;?>',single:true,maxHeight: 170
});
	
	  $("#NumFiche").multipleSelect("uncheckAll");


function suppression(idPos){

			jConfirm('Confirmer la suppression ?', null, function(r) {
						if(r)	{
							$('#act').attr('value','supp'); 
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'validation_stock.php?suppression&idPos='+idPos,clearForm:false});		
						}
					})
	//	$('#box').load(url).dialog('open');
	
}
function modification(nomPromo){

		$('#act').attr('value','modif'); 
		var url='validation_stock.php?modification&nompromo='+nomPromo ;
	
		$('#box').load(url).dialog('open');
	
}
	function filtrer(){
	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'validation_stock.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){

		$('#formRechF').ajaxSubmit({target:'#formRes',url:'validation_stock.php?rech'})
			//clearForm('formRechF',0);
	}

function DetailInv(IdInventaire,IdDepot){

		$('#act').attr('value','add');
		var url='validation_stock.php?detail&&IdInventaire='+IdInventaire+'&IdDepot='+IdDepot;	
		$('#box').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');
	
}

  $('body').on('keypress', '#ListeCltRech', function(args) {
   if (args.keyCode == 13) {
       $("#Rechercher").click();
       return false;
   }
});

$(document).ready(function(){


		$('#search').dialog({
					autoOpen		:	false,
					width			:	600,
					height			:	400,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'<?php echo $trad['button']['Rechercher'];?>',
					buttons			:	{
						"<?php echo $trad['button']['Annuler'];?>"		: function(){
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['Rechercher'];?> "	: function() {
								rechercher();
						
						}
					 }
			});
	/*$("label[data-group=group_0]").parent(".group").css("border","1px solid red");
		$("label[data-group=group_0]").parent(".group").addClass("selected");*/
	     $("#ListeCltRech").multipleSelect("uncheckAll");
		$('#formRes').html('<center><br/><br/><img src="images/loading.gif" /></center>').load('validation_stock.php?aff');
				$('#box').dialog({
					autoOpen		:	false,
					width			:	1000,
					height			:	540,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	false,
					draggable		:	false,
					title			:	'<?php echo $trad['Menu']['entreestock'];?>',
					open: function(event, ui) { 
    //hide close button.
							$(this).parent().children().children('.ui-dialog-titlebar-close').hide();
						},
	
					buttons			:	{
						"<?php echo $trad['button']['Fermer'];?>"		: function(){
						//	$('#resG').load('validation_stock.php?VideSession');
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['Enregistrer'];?> "	: function() {
								ValideStock();
						
						}
					 }
			});
  });

	
function ValideStock(){

var form="#formAdd";
var test=true;

	if(test==true) {		
			 jConfirm('<?php echo $trad['msg']['terminerOperation'];?>', "<?php echo $trad['titre']['Alert'];?>", function(r) {
					if(r)	{
							$('#formAdd').ajaxSubmit({
									target			:	'#res',
									url				:	'validation_stock.php?goValidStock',
									method			:	'post'
							}); 
							return false;
			           }			
		})
	}
}



	


	function Exporter(IdInventaire,IdDepot){
		/*	var adr = 'ficheControle.print.php?IdDmd='+idDmd;
			//alert(adr);
			window.location.href = adr;*/
			 url=	'inventaire_pdf.php?pdf&IdInventaire='+IdInventaire+'&IdDepot='+IdDepot;		
//			 url=	'validation_stock.php?pdf&IdInventaire='+IdInventaire+'&IdDepot='+IdDepot;	
			$('#resG').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url);
			//patienter('formRes');
		
	}
function ouvrirPdf(){
				
					window.open("inventaire_pdf/inventaire.pdf",'_blank');
					return false;
				 
		}
	
</script>

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
require_once('TCPDF-master/tcpdf.php');
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
// create new PDF document
  $obj_pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
  
				      $obj_pdf->SetCreator(PDF_CREATOR);  
				      $obj_pdf->SetTitle("Inventaire");  
				      $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
				      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
				      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
				      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
				      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
				      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
				      $obj_pdf->setPrintHeader(false);  
				      $obj_pdf->setPrintFooter(true);  
				      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
				      //$obj_pdf->SetFont('helvetica', '', 12); 
				      $obj_pdf->SetFont('aealarabiya', '', 8); 
				      $obj_pdf->AddPage();
include("lang.php");
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
			where i.id = ? AND (i.Etat =1) ";
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
						
		$html2.='
					<table width="700px" border="1" id="tableID" align="center"  style="border-collapse: collapse; border-color:#000; " >
					  <tr  class="" style=" height:170px;background-color:#dbeef3;color:#000;"  >
									<td width=" 4%" align="center"> '.$trad['label']['Refe'].'</td>
									<td  width="13%" align="center">'.$trad['label']['Dsg'].'</td>
									<td  width="3%" align="center">'.$trad['label']['Col'].'</td>
									<td  width="6%" align="center">'.$trad['label']['PVGros'].'<BR>('.$trad['label']['riyal'].')</td>
									<td  width="6%" align="center">'.$trad['label']['PVDet'].'<BR>('.$trad['label']['riyal'].')</td>
									<td  width="4%" align="center">'.$trad['label']['StockC'].'</td>
									<td  width="5%" align="center">'.$trad['label']['StockP'].'</td>
									<td  width="4%" align="center">'.$trad['label']['InvC'].'</td>
									<td  width="5%" align="center">'.$trad['label']['InvP'].'</td>
									<td  width="5%" align="center">'.$trad['label']['TotalStockPcs'].'</td>
									<td  width="5%" align="center">'.$trad['label']['TotalInvPcs'].'</td>
									<td  width="5%" align="center">'.$trad['label']['Ecart'].'('.$trad['label']['NbrPiece'].')</td>
									<td  width="5%" align="center">'.$trad['label']['ValeurGros'].'('.$trad['label']['riyal'].')</td>
									<td  width="5%" align="center">'.$trad['label']['ValeurDet'].'('.$trad['label']['riyal'].')</td>
					  </tr>';
	$debutScript = microtime(true);$Ecart=0;
	$DsgDepot="";$HeureInv ="";$DateValidInv="";$TotalEcart=0;$TotalValGros=0;$TotalValDet=0;
		$nRes = sqlsrv_num_rows($resAff);
	//	echo $nRes;
		while($row = sqlsrv_fetch_array($resAff))
		{	
				$NumInv=$row['Numero'];$Vendeur=$row['Vendeur'];$DateInv=$row['DateInf'];
				$IdDepot=$row['IdDepot'];
				$DsgDepot=$row['Depot'];
				$DateValidInv=$row['DateValid'];	$HeureInv=$row['HeureInv'];//$HeureValide=$row['HeureValide'];
				$params= array($row['IdArticle'],$IdDepot) ;
				
				$qteTotalInvPcs=  qteStock($params,$conn,'reel');
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
					$TotalEcart+=$Ecart;
					$TotalValGros+=$ValeurGros;
					$TotalValDet+=$ValeurDet;
			
			//<td align="right"  >&nbsp;&nbsp;'.$Ecart.'</td>
				$html2.='	
						<tr  height="40px" >
								<td align="right" >'. htmlentities($row['Reference']).'</td>
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
		//$html2 .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
		$html2 .= '<tr ><td align="right"  colspan="11">Total</td><td align="right">'.$TotalEcart.'</td><td align="right">'.number_format($TotalValGros, 2, '.', ' ').'</td><td align="right">
						'.number_format($TotalValDet, 2, '.', ' ').'</td></tr>';
		$html2 .= '</table>';

					}
				
	//echo $html2;return;
	//$head='<html> <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
	//	$head.='<style> body { font-family: arial, sans-serif; }				</style>				</head>				<body>';
	//$date = date_create($DateValidInv);
	/*$date = '2017-07-00';
	$dateValue = strtotime($DateInv);                     
	$yr = date("Y", $dateValue) ." "; 
	$mon = date("m", $dateValue)." "; 
	$date = date("d", $dateValue); 
	if($mon<10) $mon=substr($mon,1); 
 echo $mon;
 $monthName = date("F", mktime(0, 0, 0, 1, 10));
 echo $monthName; return;
 
*/
	$html0.='<div style="text-align:center ; width:800px; margin:0px 0; padding:7px;font-weight:400px">
			Situation  
		<b>'.$DsgDepot.' </b> <br>'.$DateInv.'</div>';//
		$head='<html><body style="font-size:12px;font-family:verdana">';
		$html1='<table style="width:800px;" border="0">
		<tr> <td align="left" width="15%">
				Superviseur: </td><td align="left">'.htmlentities(($Vendeur)).'</td>';	
		$html1.='<td align="left" width="10%">Date Inventaire: </td><td align="left">'.$DateInv.'</td></tr>';
		$html1.='<tr><td align="left">HEURE DEPART : </td><td>' .$HeureInv.'</td>';
		$html1.='<td align="left" width="15%"> </td><TD align="left"></td></tr></table><BR><BR>';
	//	echo $html1;return;
	
			$html=$html0.$html1.$html2;

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');


	$obj_pdf->writeHTML($html, true, false, false, false, '');
				//	$obj_pdf->writeHTML($tbl, true, false, false, false, '');
			        //  $obj_pdf->writeHTML($html);
					  
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
		$name='inventaire_val '.date('d-m-Y H-i').'.pdf';	
$obj_pdf->Output( __DIR__ .'\all_file\inventaire_pdf\\'.$name, 'F');
//$pdf->Output('bon_commande.pdf', 'F');
//echo "terminer";
?>
<script type="text/javascript">
var name="<?php echo $name;?>";
	window.open("http://localhost:82/pgd/all_file/inventaire_pdf/"+name);	
		//window.open("http://pgd.ma/v6/all_file/inventaire_pdf/"+name);	

</script>
<?php
//============================================================+
// END OF FILE
//============================================================+
?>
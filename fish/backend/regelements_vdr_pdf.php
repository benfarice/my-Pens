<?php
session_start();
if(!isset($_SESSION['username'])){
header('Location: index.php');
exit();
}
error_reporting(0);
	include_once "init.php";
SQLSRV_PHPTYPE_STRING('UTF-8') ; 

require_once('TCPDF-master/tcpdf.php');
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
	
			// New line in footer
			$this->Ln(8);

			// First line of 3x "sometext"
			$this->MultiCell(55, 10, 'Sometext', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$this->MultiCell(55, 10, 'Sometext', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$this->MultiCell(55, 10, 'Sometext', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    }
}
// create new PDF document
  $obj_pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
  

			$lg = Array();
			$lg['a_meta_charset'] = 'UTF-8';
			$lg['a_meta_dir'] = 'rtl';
			$lg['a_meta_language'] = 'fa';
			$lg['w_page'] = 'Page';

			// set some language-dependent strings (optional)
			$obj_pdf->setLanguageArray($lg);


				      $obj_pdf->SetCreator(PDF_CREATOR);  
				      $obj_pdf->SetTitle("Invoice");  
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
					 $fontname = TCPDF_FONTS::addTTFfont('TCPDF-master/fonts/arial.ttf', 'TrueTypeUnicode', '', 96);
					 //dejavusans
				    //  $obj_pdf->SetFont('Arial'); 
					 $obj_pdf->SetFont($fontname, '', 13); 
				      $obj_pdf->AddPage();

		$sql = "
		  SELECT r.CodeReg,(r.MtEspece+r.MtCheque+r.MtCredit )TotalPaye,r.MtEspece,r.MtCheque ,r.MtCredit ,
		  r.MtCreditFournisseur ,r.DateReg,
 bye.ID_B_S NameSeller ,bye.name_ar NameVdr,fa.Num_facture CodeFac,fa.Total Montant,fa.Date_F DateFac
   FROM reglements r 
   INNER JOIN detailReglements dr  ON dr.IdReg=r.IdReg
    INNER JOIN facture_vendeur fa   ON fa.Id_Fac=dr.IdFac  
   INNER JOIN BUYER_SELLER bye ON bye.ID_B_S=r.CodeBuySel AND bye.TYPE_B_S='SELLER' 
   where r.IdReg=? GROUP
BY r.CodeReg,(r.MtEspece+r.MtCheque+r.MtCredit ),r.MtCredit ,
  r.MtCreditFournisseur ,r.DateReg,fa.Num_facture,fa.Total,fa.Date_F,r.MtEspece,r.MtCheque ,
 bye.ID_B_S  ,bye.name_ar 
		  ";
	 $params = array($_GET['IdReg']);

//echo $sql;return;
//arcourir($params);
		$resAff=sqlsrv_query($con,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
			$nRes = sqlsrv_num_rows($resAff);
				if($nRes==0)
						{ ?>
							<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
							<?php echo lang('AucunResultat');?>
						</div>
						<?php
						return;
						}
					else {
						
						$groups = array();
								$i=0;
								$TotalHT=0;$TotalTTC=0;$TotalTVA=0;
			 while($row=sqlsrv_fetch_array($resAff)){							 
								/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
												 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
												 
												 
										$key = $row['IdReg'];
										$i=$i+1;
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['CodeReg']=$row['CodeReg'];											
											$groups[$key]['NameVdr']=$row['NameVdr'];
											$groups[$key]['TotalPaye']=$row['TotalPaye'];
											$groups[$key]['DateReg']=$row['DateReg'];
											$groups[$key]['NameSeller']=$row['NameSeller'];
											$groups[$key]['NameBye']=$row['NameVdr'];
											$groups[$key]['MtCheque']=$row['MtCheque'];
											$groups[$key]['MtCreditFournisseur']=$row['MtCreditFournisseur'];
											$groups[$key]['MtEspece']=$row['MtEspece'];
											$groups[$key]['MtCredit']=$row['MtCredit'];
									
										} 
									//	echo "<br>---".$i."---<br>";
										
											$groups[$key][$i]['CodeFac'] = $row['CodeFac'];
											$groups[$key][$i]['MontantFac']=$row['Montant'];
												$Date = $row['DateFac']->format('d/m/Y');
											$groups[$key][$i]['DateFac']=$Date;
										/*	$groups[$key][$i]['NumLot'] = $row['NumLot'];									
											$groups[$key][$i]['NameSeller'] =$row['NameSel'];
											$groups[$key][$i]['NameEsp'] =$row['NameEsp'];
											$groups[$key][$i]['PrixUnite'] =$row['PrixUnite'];
											$groups[$key][$i]['TotalPoids'] =$row['TotalPoids'];
											$groups[$key][$i]['TotalPrix'] =$row['TotalPrix'];
											$groups[$key][$i]['DateAdj'] = $row['DateAdj'];*/
										
										
									
			 }
		//	 parcourir($groups);return;
		
						$html0 ="";$TitleM="";$html1 ="";$html ="";$html2 ="";$MtCreditFournisseur=0;$MtCheque=0;$MtEspece=0;$MtCredit=0;
						
		$html2.='
					<table width="100%" border="1" id="tableID" align="center"  style="border-collapse: collapse; border-color:#000; " >
					  <tr  class="" style=" height:170px;background-color:#ebebeb;color:#000;"  >
									<td width=" 35%" align="center"> '.lang('num_facture').'</td>
									<td  width="35%" align="center">'.lang('facture_date_title').'</td>
									<td  width="30%" align="center">'.lang('TotalPrix').'<BR>('.lang('reyal_homany').')</td>
									
					  </tr>';
	$debutScript = microtime(true);$Ecart=0;
	$CodeReg="";$NameSeller ="";$NameBye="";$DateReg=0;$NbrFac=0;$TotalReg=0;$TotalPaye=0;$Tax=0;$ValObll=0;$ValObllLettre="";
		$nRes = sqlsrv_num_rows($resAff);
	//	echo $nRes;
		foreach($groups as $u=>$v){	
		$CodeReg=$v['CodeReg'];
		$NameSeller=$v['NameSeller'];
		$NameBye=$v['NameBye'];
		$DateReg=$v['DateReg'];
		$MtCreditFournisseur=$v['MtCreditFournisseur'];
		$MtCheque=$v['MtCheque'];
		$MtCredit=$v['MtCredit'];
		$MtEspece=$v['MtEspece'];$TotalPaye=$v['TotalPaye'];
					
					$Reste=floatval($TotalPaye)-floatval($MtCreditFournisseur);
		
		foreach($v as $row){
				if(is_array($row)){
					$NbrFac+=1;
				$html2.='	
						<tr  height="40px" >
								<td align="right" >'. htmlentities($row['CodeFac']).'</td>
								<td align="center"  > &nbsp;&nbsp;'. htmlentities($row['DateFac']).' </td>
							<td align="right"  >&nbsp;&nbsp;'.number_format($row['MontantFac'], 3, '.', ' ').'</td>		
						</tr>';
						$TotalReg+=floatval($row['MontantFac'])+floatval($row['MtCredit']);
					
		}}}
		//$html2 .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';

	$ValObllLettre = convertNumber($TotalPaye,false);
		$html2 .= '</table>';

			$html2.='<BR><BR><BR><table width="100%" border="1" id="tableID" align="center" 
			style="border-collapse: collapse; border-color:#000; ">	
						<tr  >
								<td align="right" >'.lang('TotalReg').' ('.lang('reyal_homany').')</td>
								<td align="right"  > &nbsp;&nbsp;'.number_format($TotalReg, 3, '.', ' ').' </td>
							
								
						</tr>
						<tr   >
								<td align="right"  >'.lang('NbrFac').'</td>		
								<td align="right"  >&nbsp;&nbsp;'.$NbrFac.'</td>		
						
						</tr>
						<tr   >
								<td align="right"  >'.lang('Espece').'</td>		
								<td align="right"  >&nbsp;&nbsp;'.number_format($MtEspece, 3, '.', ' ').'</td>		
						
						</tr>
						<tr   >
								<td align="right"  >'.lang('Cheque').'</td>		
								<td align="right"  >&nbsp;&nbsp;'.number_format($MtCheque, 3, '.', ' ').'</td>		
						
						</tr>
						
						<tr   >
								<td align="right" >'.lang('Credit').'</td>		
								
								<td align="right"  >&nbsp;&nbsp;'.number_format($MtCredit, 3, '.', ' ').'</td>
						</tr>
						
						<tr   >
								<td align="right" >'.lang('MtCreditFournisseur').'</td>		
								
								<td align="right"  >&nbsp;&nbsp;'.number_format($MtCreditFournisseur, 3, '.', ' ').'</td>
						</tr>
						
						
						</table><br><br>
						<TABLE ><TR><TD colspan="2">'.lang('Net_dues_in_letters').'</td></tr>
						<tr><td colspan="2" align="left" >Net dues in letters <br><b>'.$ValObllLettre.'</b></td></tr>
						<TR><TD ></td><td   valign="middle"><br><hr></td></tr>
						<TR><TD >ختم تأشيرة رئيس السوق المركزي </td><td></td></tr>
						</table>
						<br><br>
						';
						
		
					}
//	$html0.='<div style="text-align:center ;  margin:0px 0; padding:7px;font-weight:400px">Situation  </div>';//
		$head='<html><body>';
		$html1='<table  width="100%" border="0" id="tableID" align="center"  >
		<tr> <td width="33%" align="right">	<img src="layout/images/atlas.jpg"/></td>
			<TD width="34%" align="center">
			سلطنة عمان <br>
			وزارة الزراعة و الثروة السمكية
			<br>
		ميناء صور | سوق السمك
		</td>
		<td width="33%" align="left">	<img src="layout/images/logo1.png"/>
				</td>
				</tr>';	
		
		
		$html1.='</table><BR><BR>';
		$html1.='<table width="100%"  border="0" >
			<tr>
			<td width="5%" ></td>
			<td width="90%">
				<table WIDTh="100%"  border="0" align="center" >
					<tr> <td width="28%" align="right">أداء البائع رقم </td>
						<TD width="33%">'.$CodeReg.'</td>
						<td width="38%" align="left">Payment Seller Number	</td>
					</tr>
					<tr> <td align="right"> تاريخ الأداء </td>
						<TD>'.$DateReg.'</td>
						<td  align="left">Date	</td>
					</tr>
					<tr> <td align="right">رمز البائع  </td>
						<TD>'.$NameSeller.'</td>
						<td align="left" >Seller Code	</td>
					</tr>
					<tr> <td align="right">إسم البائع</td>
						<TD>'.$NameBye.'</td>
						<td  align="left"> Seller Name	</td>
					</tr>	
				</table>
			</td>
		';
		$html1.='<td width="5%" ></td>
			</tr>
			</table><BR><BR><BR>';
	//	echo $html1;return;
	$html=$html1.$html2;
		//	$html=$html1.$html2;

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');


	$obj_pdf->writeHTML($html, true, false, false, false, '');
				//	$obj_pdf->writeHTML($tbl, true, false, false, false, '');
			        //  $obj_pdf->writeHTML($html);
					  
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
		$name='paiement_vdr_pdf '.date('d-m-Y H-i').'.pdf';	
$obj_pdf->Output( __DIR__ .'\all_file\\'.$name, 'F');
//$pdf->Output('bon_commande.pdf', 'F');
//echo "terminer";
?>
<script type="text/javascript">
var name="<?php echo $name;?>";
	//window.open("http://localhost:82/fish/backend/all_file/"+name);	
	document.location.href="http://localhost/fish/backend/all_file/"+name;
		//window.open("http://pgd.ma/v6/all_file/inventaire_pdf/"+name);	

</script>
<?php
//============================================================+
// END OF FILE
//============================================================+
/*
<tr   >
								<td align="right"  >'.lang('Credit').'</td>		
								<td align="right"  >&nbsp;&nbsp;'.number_format($MtCredit, 3, '.', ' ').'</td>		
						
						</tr>*/
?>
<?php 
include("php.fonctions.php");
include("class.uploader.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "affectations";
$cleTable = "idaffectation";
$nom_sansDoublons = "Numéro d\'immatriculation";



if (isset($_GET['goPdf'])){
		$where="";


$sqlA = "SELECT cast(convert(date,f.[date]) AS varchar(20)) AS dateF, tv.IdType, tv.Dsg_ar,tv.Dsg_en,g.IdGamme,g.Designation,df.UniteVente, sum(df.qte ) qte,sum(df.ttc ) ttc
FROM factures f 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
INNER JOIN TypeVente tv ON f.TypeVente=tv.IdType
INNER JOIN articles a ON a.IdArticle=df.idArticle 
INNER JOIN gammes g ON g.IdGamme=a.IdFamille
where f.idVendeur='".$_GET['Vendeur']. "'  AND month(convert(date,f.[date]))= ".$_GET['Mois'].
" GROUP BY convert(date,f.[date]), tv.IdType,tv.Dsg_ar,tv.Dsg_en,g.IdGamme,g.Designation,df.UniteVente ";
//echo $sqlA ;
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff = sqlsrv_query($conn,$sqlA,$params,$options) or die( print_r( sqlsrv_errors(), true));
	
	$nRes = sqlsrv_num_rows($resAff);
	/*$nPages = ceil($ntRes / $npp);
	$selPages = '<select name="pact" onChange="filtrer();">';
	for($i=1;$i<=$nPages;$i++){
		if($i==$pact) $s='selected="selected"';
		else $s='';
		$selPages.= '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
	}
	$selPages.= '</select>';*/

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
{//echo $sqlA;//return;
$design="";
		if($_SESSION['lang'] == "ar") $design="Dsg_ar"; else $design="Dsg_en";
		//echo "hereeeee";
		
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
			$key_dateFacure = $row['dateF'];
			//echo $key_dateFacure;
				if (!isset($vente[$key_dateFacure])) {
					$vente[$key_dateFacure] = array();
					$vente[$key_dateFacure]['dateF']=$row['dateF'];
					$i=0;
				} 
				 $i=$i+1;
					$key_type=$row[$design];
						if (!isset($vente[$key_dateFacure][$key_type])) {
						$vente[$key_dateFacure][$key_type]['IdType']= $row['IdType'];
						$vente[$key_dateFacure][$key_type]['Desig']= $row[$design];							
						}
							$key_article=$row['Designation'];
								if (!isset($vente[$key_dateFacure][$key_type][$key_article])) {
									$vente[$key_dateFacure][$key_type][$key_article]['IdGamme']= $row['IdGamme'];
									$vente[$key_dateFacure][$key_type][$key_article]['Designation']= $row['Designation'];
								}
									$key_unite=$row['UniteVente'];
										if (!isset($vente[$key_dateFacure][$key_type][$key_article][$key_unite] )) {
											//$vente[$key_dateFacure][$key_type][$key_article][$key_unite]['UniteVente']= $row['UniteVente'];
											$vente[$key_dateFacure][$key_type][$key_article][$key_unite]['qte']= $row['qte'];
											$vente[$key_dateFacure][$key_type][$key_article][$key_unite]['ttc']= $row['ttc'];
										}
											/*if($vente[$key_dateFacure][$key_type][$key_article][$key_unite]!=""){
												$vente[$key_dateFacure][$key_type][$key_article][$key_unite]['qte']= $row['qte'];
											}*/
				/**/
		}

$sql2 = "SELECT DISTINCT g.IdGamme, g.Designation 
from factures f 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
INNER JOIN articles a ON a.IdArticle=df.idArticle 
INNER JOIN gammes g ON g.IdGamme=a.IdFamille where f.idVendeur='".$_GET['Vendeur']. "'  AND month(convert(date,f.[date]))= ".$_GET['Mois'];

    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff2 = sqlsrv_query($conn,$sql2,$params,$options) or die( print_r( sqlsrv_errors(), true));
	
	
	while($row = sqlsrv_fetch_array($resAff2, SQLSRV_FETCH_ASSOC)){		
		$gammes[$row['IdGamme']]=$row['Designation'];
		//$articles[$row['IdArticle']][$i]['idArticle']=$row['IdArticle'];
	}
		
function extract_value(array $array, array $keys) {
    foreach($keys as $key) {
        if (!isset($array[$key])) return null;
        $array = $array[$key];
    }

    return $array;
}
		/*echo "<pre>";	print_r($vente);echo "</pre>";echo $sql2;echo "<pre>";	echo "</pre>";
			$keys=array( "2017-06-09" , "البيع بالجملة" , " h\'ola honey " , "Box","qte" );//print_r($keys);
						$result = extract_value($vente, $keys);
			echo ($result =="")? " - " :  $result["qte"];//print_r($result);//
			return;*/
			
			$html="";
	?>

<style>

table{
direction:rtl;
border-collapse: collapse;
font-size:13px;
}

table.mytable, td, th {
    border: 1px solid black;
	text-align:center;
}
.mytable td.spec_td {

border-top: 1px solid white;
border-bottom: 1px solid white;
}
.mytable td.spec_tr {

border-right: 1px solid white;
border-left: 1px solid white;
}
</style>
		
	<?php	$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	$html .= '<table class="mytable" width="100%" border="0"  cellpadding="5" cellspacing="5">';
	
      $html .= ' <tr style="font-weight:bold;">';
		$html .= '	<td rowspan="2">'. utf8_encode($trad['label']['Date']).'</td> '; 
		$html .= '	<td rowspan="2">'. $trad['label']['typeVente'] .'</td>';
	//<!------------------------------------------Article------------------------------------->		
		foreach($gammes as $id => $designation) {  
		$html .= '	<td colspan="5">'.$designation.'</td>';
		$html .= '	<td  class="spec_td"></td>';
		 } 
		$html .= '<td colspan="5">'.$trad['label']['totalQteVenteRS'].'</td>';
		$html .= '<td  class="spec_td" style="border-left:1px solid white"></td>';
		$html .= '</tr>';
	//<!------------------------------------------Col - Box - Palette------------------------------------->		
	   $html .= '<tr>';
	  
	  foreach($gammes as $id => $designation) { 
	  
	
				$html .= '<td>'. $trad['label']['Colisage'].'</td>';
				$html .= '<td>'. $trad['label']['box'].'</td>';
				$html .= '<td>'. $trad['label']['palette'].'</td>';
				$html .= '<td>'. $trad['label']['qteBox'].'</td>';
				$html .= '<td>'. $trad['label']['totalRS'].'</td>';
				$html .= '<td class="spec_td"></td>';
		
		} 
			
					$html .= '<td>'.  $trad['label']['Colisage'].'</td>';
					$html .= '<td>'.  $trad['label']['box'].'</td>';
					$html .= '<td>'.  $trad['label']['palette'].'</td>';
					$html .= '<td>'.  $trad['label']['qteBox'].'</td>';
					$html .= '<td>'.  $trad['label']['totalRS'].'</td>';
					$html .= '<td  class="spec_td" style="border-left:1px solid white"></td>';
	   $html .= '</tr>';

// $newDate = date("M d, Y", strtotime($v["DateFac"]));	
$month = $_GET['Mois'];
$year = date("Y");
$nbrDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

//-----------------Loop All Days of month
for($i = 1; $i <=  $nbrDays; $i++)
{
$ValeurColDetail=array();
$ValeurColGros=array();
$totalDetail=0;$totalDCol=0;$totalDBox=0;$totalDPal=0;$totalDTtc=0;
$totalGros=0;
$totaux=0;

//<!--  Date --------------------------------------- Ligne 1 Detaillant ----------------------------------------------->
 $html .= '<tr>';

   // add the date to the dates array
   $d=date("Y-m-d", strtotime(date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)));
   $dates[$d] =  date("d F,Y-l", strtotime(date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)));

	//<!--Date -->
	$html .='<td rowspan="3"  style="font-weight:bold;direction:ltr;">'. $dates[$d].'</td>  '; 
	//<!--Type Vente -- Detaillant -->
	$html .='<td>'. $trad['label']['detaillant'].'</td>   ';
	//<!--Article -->
  foreach($gammes as $id => $designation) {  
 $total_gamme_Detaillant=0;$ttc_col=0;$ttc_box=0;$ttc_pal=0;
 $gamme = str_replace(' ', '', $designation);

		
	 // Colisage -- Detaillant
			$keys=array($d,"البقالة",$designation,"Colisage");
			$result = extract_value($vente, $keys);
			$key=$gamme."col";
			if($result =="")
			{
				$html .='<td> - </td>';	//echo " - ";
				$ValeurColDetail[$key]=0;
				$ttc_col=0;
				$totalDCol+=0;
			}
			else
			{
				$html .='<td> '.$result["qte"].' </td>';	//echo $result["qte"];
				$ValeurColDetail[$key]=$result["qte"];
				$ttc_col=$result["ttc"];
				$totalDCol+=intval($result["qte"]);
			}
			
	
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);
		
		 // Box -- Detaillant
			$keys=array($d,"البقالة",$designation,"Box");
			$result = extract_value($vente, $keys);
			$key=$gamme."box";
			if($result =="")
			{
				$html .='<td> - </td>';	//echo " - ";
				$ValeurColDetail[$key]=0;
				$ttc_box=0;
				$totalDBox+=0;
			}
			else
			{
				$html .='<td> '.$result["qte"].' </td>';	//echo $result["qte"];
				$ValeurColDetail[$key]=$result["qte"];
				$ttc_box=$result["ttc"];
				$totalDBox+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);
		
		
		 // Palette -- Detaillant
			$keys=array($d,"البقالة",$designation,"Palette");
			$result = extract_value($vente, $keys);
			$key=$gamme."pal";
			if($result =="")
			{
				$html .='<td> - </td>';	//echo " - ";
				$ValeurColDetail[$key]=0;
				$ttc_pal=0;
				$totalDPal+=0;
			}
			else
			{
				$html .='<td> '.$result["qte"].' </td>';	//echo $result["qte"];
				$ValeurColDetail[$key]=$result["qte"];
				$ttc_pal=$result["ttc"];
				$totalDPal+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result); 
			
		
		$html .='<td> - </td>';
		
		$total_gamme_Detaillant = $ttc_col+$ttc_box+$ttc_pal;
		
		
		$html .='<td> '. (($total_gamme_Detaillant==0) ? " - " : $total_gamme_Detaillant) .'</td>';
		
		$key=$gamme."total";
		$ValeurColDetail[$key]=$total_gamme_Detaillant;
		$totalDTtc+=$total_gamme_Detaillant;
		//$trad['label']['totalRS'];
		
		$html .='<td  class="spec_td"></td>';
 } 
		
					$html .='<td>'.(($totalDCol==0) ?" - " : $totalDCol ).'</td>';//$trad['label']['Colisage']; </td>
					$html .='<td>'. (($totalDBox==0) ?" - " : $totalDBox ).'</td>';//$trad['label']['box']; </td>
					$html .='<td>'. (($totalDPal==0) ?" - " : $totalDPal) .'</td>';//$trad['label']['palette']; </td>
					$html .='<td>'.  " - " .'</td>';//$trad['label']['qteBox'];</td>
					$html .='<td>'. (($totalDTtc==0) ?" - " : $totalDTtc) .'</td>';//$trad['label']['totalRS']; </td>
					$html .='<td  class="spec_td" style="border-left:1px solid white"></td>';
$html .='</tr>';

//<!--  Date -------------------------------------- Ligne 2 GROSSITE - ------------------------------------------>
$html .='<tr>';
 $totalDCol=0;$totalDBox=0;$totalDPal=0;$totalDTtc=0; 	
	//<!--Type Vente GROSSITE-->
	$html .='<td>'. $trad['label']['grossite'].'</td>  '; 
	//<!--Article -->
  foreach($gammes as $id => $designation) {  
	$total_gamme_Grossite=0;$ttc_col=0;$ttc_box=0;$ttc_pal=0;
	$gamme = str_replace(' ', '', $designation);

	
		
	  // Colisage -- GROSSITE

			$keys=array($d,"البيع بالجملة",$designation,"Colisage");//print_r($keys);
			$result = extract_value($vente, $keys);
			$key=$gamme."col";
			if($result =="")
			{
				$html .='<td> - </td>';//echo " - ";
				$ValeurColGros[$key]=0;
				$ttc_col=0;
				$totalDCol+=0;
			}
			else
			{
				$html .='<td> '.$result["qte"].' </td>';//echo $result["qte"];
				$ValeurColGros[$key]=$result["qte"];
				$ttc_col=$result["ttc"];
				$totalDCol+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);
		
		
		
		  // Box -- GROSSITE

			$keys=array($d,"البيع بالجملة",$designation,"Box");//print_r($keys);
			$result = extract_value($vente, $keys);
			$key=$gamme."box";
			if($result =="")
			{
				$html .='<td> - </td>';//echo " - ";
				$ValeurColGros[$key]=0;
				$ttc_box=0;
				$totalDBox+=0;
			}
			else
			{
				$html .='<td> '.$result["qte"].' </td>';//echo $result["qte"];
				$ValeurColGros[$key]=$result["qte"];
				$ttc_box=$result["ttc"];
				$totalDBox+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);

		
	 // Palette -- GROSSITE

			$keys=array($d,"البيع بالجملة",$designation,"Palette");//print_r($keys);
			$result = extract_value($vente, $keys);
			$key=$gamme."pal";
			if($result =="")
			{
				$html .='<td> - </td>';//echo " - ";
				$ValeurColGros[$key]=0;
				$ttc_pal=0;
				$totalDPal+=0;
			}
			else
			{
				$html .='<td> '.$result["qte"].' </td>';//echo $result["qte"];
				$ValeurColGros[$key]=$result["qte"];
				$ttc_pal=$result["ttc"];
				$totalDPal+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);
	
		
		$html .='<td> - </td>';//<td><?php echo  " - ";//$trad['label']['qteBox']; </td>
		
		$total_gamme_Grossite = $ttc_col+$ttc_box+$ttc_pal;
		
		$html .='<td> '. (($total_gamme_Grossite==0) ?" - " : $total_gamme_Grossite).' </td>';//$trad['label']['totalRS']; 
		
		$key=$gamme."total";
		$ValeurColGros[$key]=$total_gamme_Grossite;
		$totalDTtc+=$total_gamme_Grossite;
		
		$html .='<td class="spec_td"></td>';
 }//Gamme - Grossiste 

					$html .='<td>'.(($totalDCol==0) ?" - " : $totalDCol) .'</td>';//$trad['label']['Colisage']; </td>
					$html .='<td>'. (($totalDBox==0) ?" - " : $totalDBox) .'</td>';//$trad['label']['box']; </td>
					$html .='<td>'. (($totalDPal==0) ?" - " : $totalDPal) .'</td>';//$trad['label']['palette']; </td>
					$html .='<td>'.  " - " .'</td>';//$trad['label']['qteBox']; </td>
					$html .='<td>'. (($totalDTtc==0) ?" - " : $totalDTtc) .'</td>';//$trad['label']['totalRS']; </td>
					$html .='<td  class="spec_td" style="border-left:1px solid white"></td>';
$html .='</tr>';

//<!--  Date ---------------------------------------------- Ligne 3 TOTAL ------------------------------------------------->
$html .='<tr>';
$totalDCol=0;$totalDBox=0;$totalDPal=0;$totalDTtc=0; 	
	$html .='<td>'.$trad['label']['totalVente'].' </td>';
	//<!--GAMME -->
foreach($gammes as $id => $designation) {
$gamme = str_replace(' ', '', $designation);  
		
			$key=$gamme . "col";
			$somme=$ValeurColDetail[$key]+$ValeurColGros[$key];
			// echo ($somme==0) ? " - " : $somme;
			$totalDCol+=$somme;
		$html .='<td>'.(($somme==0) ? " - " : $somme).'</td>';//-------------Total Colisage
		
				$key=$gamme . "box";
				$somme=$ValeurColDetail[$key]+$ValeurColGros[$key];
				//echo ($somme==0) ? " - " : $somme;	
				$totalDBox+=$somme;		
		$html .='<td>'.(($somme==0) ? " - " : $somme).'</td>';//-------------Total Box		
			
				$key=$gamme . "pal";
				$somme=$ValeurColDetail[$key]+$ValeurColGros[$key];
				//echo ($somme==0) ? " - " : $somme;	
				$totalDPal+=$somme;				
		$html .='<td>'.(($somme==0) ? " - " : $somme).'</td>';//-------------Total 	
		
		$html .='<td> - </td>';//$trad['label']['qteBox']; </td>
		
				$key=$gamme . "total";
				$somme=$ValeurColDetail[$key]+$ValeurColGros[$key];
		$html .='<td>'. (($somme==0) ? " - " : $somme).'</td>';		
				$totalDTtc+=$somme;
		
		$html .='<td  class="spec_td"></td>';	
 } //
					$html .='<td>'.(($totalDCol==0) ?" - " : $totalDCol) .'</td>';//$trad['label']['Colisage']; </td>
					$html .='<td>'. (($totalDBox==0) ?" - " : $totalDBox) .'</td>';//$trad['label']['box']; </td>
					$html .='<td>'. (($totalDPal==0) ?" - " : $totalDPal) .'</td>';//$trad['label']['palette']; </td>
					$html .='<td>'.  " - " .'</td>';//$trad['label']['qteBox']; </td>
					$html .='<td>'. (($totalDTtc==0) ?" - " : $totalDTtc ).'</td>';//$trad['label']['totalRS']; </td>
					$html .='<td  class="spec_td" style="border-left:1px solid white"></td>';

$html .='</tr>';
$html .='<tr style="border-left:1px solid white" ><td class="spec_tr"></td></tr>';

}//Fin For 

$html .='  </table>';
	$head='<html> <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
	$head.='<style>
				body { font-family: arial, sans-serif; direction:rtl}
				table{
				direction:rtl;
				border-collapse: collapse;
				font-size:13px;
				}

				table.mytable, td, th {
					border: 1px solid black;
					text-align:center;
				}
				.mytable td.spec_td {

				border-top: 1px solid white;
				border-bottom: 1px solid white;
				}
				.mytable td.spec_tr {

				border-right: 1px solid white;
				border-left: 1px solid white;
				}
			</style>
				</head>
				<body>';
//echo $html;
	$content = ($head.$html.'</body></html>');    
	$_SESSION['html'] = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
			require("etats/dompdf/dompdf_config.inc.php");
			$dompdf = new DOMPDF();
			$dompdf->load_html(utf8_decode($_SESSION['html']));
			$dompdf->set_paper("a4", "portrait");
			$dompdf->render();
			//$dompdf->stream("releve.pdf", array("Attachment" => true));
			file_put_contents("etats/RapportVente.pdf", $dompdf->output());	/**/
			?>
				<script language="javascript" type="text/javascript">
					//	window.location.href = 'etats/releve.php';
					ouvrirPdf();
				</script>
			<?php 


}

 ?>


<?php
exit;
}

if (isset($_GET['rech']) or isset($_GET['aff'])){
		$where="";


$sqlA = "SELECT cast(convert(date,f.[date]) AS varchar(20)) AS dateF, tv.IdType, tv.Dsg_ar,tv.Dsg_en,g.IdGamme,g.Designation,df.UniteVente, sum(df.qte ) qte
FROM factures f 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
INNER JOIN TypeVente tv ON f.TypeVente=tv.IdType
INNER JOIN articles a ON a.IdArticle=df.idArticle 
INNER JOIN gammes g ON g.IdGamme=a.IdFamille
where f.idVendeur='".$_POST['Vendeur']. "'  AND month(convert(date,f.[date]))= ".$_POST['Mois'].
" GROUP BY convert(date,f.[date]), tv.IdType,tv.Dsg_ar,tv.Dsg_en,g.IdGamme,g.Designation,df.UniteVente ";

    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff = sqlsrv_query($conn,$sqlA,$params,$options) or die( print_r( sqlsrv_errors(), true));
	
	$nRes = sqlsrv_num_rows($resAff);
	/*$nPages = ceil($ntRes / $npp);
	$selPages = '<select name="pact" onChange="filtrer();">';
	for($i=1;$i<=$nPages;$i++){
		if($i==$pact) $s='selected="selected"';
		else $s='';
		$selPages.= '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
	}
	$selPages.= '</select>';*/
	
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
{//echo $sqlA;//return;
$design="";
		if($_SESSION['lang'] == "ar") $design="Dsg_ar"; else $design="Dsg_en";
		//echo "hereeeee";
		
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
			$key_dateFacure = $row['dateF'];
			//echo $key_dateFacure;
				if (!isset($vente[$key_dateFacure])) {
					$vente[$key_dateFacure] = array();
					$vente[$key_dateFacure]['dateF']=$row['dateF'];
					$i=0;
				} 
				 $i=$i+1;
					$key_type=$row['IdType'];
						if (!isset($vente[$key_dateFacure][$key_type])) {
						$vente[$key_dateFacure][$key_type]['IdType']= $row['IdType'];
						$vente[$key_dateFacure][$key_type]['Desig']= $row[$design];							
						}
							$key_article=$row['IdGamme'];
								if (!isset($vente[$key_dateFacure][$key_type][$key_article])) {
									$vente[$key_dateFacure][$key_type][$key_article]['IdGamme']= $row['IdGamme'];
									$vente[$key_dateFacure][$key_type][$key_article]['Designation']= $row['Designation'];
								}
									$key_unite=$row['UniteVente'];
										if (!isset($vente[$key_dateFacure][$key_type][$key_article][$key_unite] )) {
											$vente[$key_dateFacure][$key_type][$key_article][$key_unite]['UniteVente']= $row['UniteVente'];
										}
											if($vente[$key_dateFacure][$key_type][$key_article][$key_unite]!=""){
												$vente[$key_dateFacure][$key_type][$key_article][$key_unite][$i]['qte']= $row['qte'];
											}
				/**/
		}

$sql2 = "SELECT DISTINCT g.IdGamme, g.Designation 
from factures f 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
INNER JOIN articles a ON a.IdArticle=df.idArticle 
INNER JOIN gammes g ON g.IdGamme=a.IdFamille where f.idVendeur='".$_POST['Vendeur']. "'  AND month(convert(date,f.[date]))= ".$_POST['Mois'];

    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff2 = sqlsrv_query($conn,$sql2,$params,$options) or die( print_r( sqlsrv_errors(), true));
	
	
	while($row = sqlsrv_fetch_array($resAff2, SQLSRV_FETCH_ASSOC)){		
		$articles[$row['IdGamme']]=$row['Designation'];
		//$articles[$row['IdArticle']][$i]['idArticle']=$row['IdArticle'];
	}
	
		//echo "<pre>";	print_r($vente);echo "</pre>";echo $sql2;echo "<pre>";	print_r($articles);echo "</pre>";return;
	
	?>
<script language="javascript" type="text/javascript">
$('#cont_pages').html('<?php echo $selPages; ?>');
</script>
<style>

table{
 border-collapse: collapse;
}

table.mytable, td, th {
    border: 1px solid black;
}
</style>
		<form id="formSelec" method="post">
	<table class="mytable" width="100%" border="0"  cellpadding="0" cellspacing="0">
       <tr>
			<td rowspan="2"><?php echo $trad['label']['Date']; ?></td>  
			<td rowspan="2"><?php echo $trad['label']['typeVente'] ?></td>
			<?php  foreach($articles as $id => $designation) {  ?>
			<td colspan="5">
			<?php echo $designation; ?>
			</td>
			<?php } ?>
			<td colspan="5">
				<?php echo $trad['label']['totalQteVenteRS']; ?>			
			</td>
		</tr>
	   <tr>
	   <?php  foreach($articles as $id => $designation) {  ?>
	
					<td><?php echo $trad['label']['Colisage']; ?></td>
					<td><?php echo $trad['label']['box']; ?></td>
					<td><?php echo $trad['label']['palette']; ?></td>
					<td><?php echo $trad['label']['qteBox']; ?></td>
					<td><?php echo $trad['label']['totalRS']; ?></td>

		<?php } ?>
		
					<td><?php echo $trad['label']['Colisage']; ?></td>
					<td><?php echo $trad['label']['box']; ?></td>
					<td><?php echo $trad['label']['palette']; ?></td>
					<td><?php echo $trad['label']['qteBox']; ?></td>
					<td><?php echo $trad['label']['totalRS']; ?></td>
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'vente_hebdomadaire.php?delPlusieursArticle',clearForm:false});		
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
    &nbsp;&nbsp;<?php echo $trad['label']['etatVenteParVnd']; ?></div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="80%" border="0" cellpadding="5" cellspacing="10" align="center" >
			
				<tr>
					<td width="20%" align="right" valign="middle">
					<div class="etiqForm" id="SYMBT" ><strong> <?php echo $trad['label']['codeVnd']; ?> :</strong> </div>				</td>
					<td >
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					<div align="<?php $_SESSION['align'] ; ?>">
			 	<select  name="Vendeur" id="Vendeur"  multiple="multiple" tabindex="3" class="Select Vendeur" style="display:visible;width:220px;">
		                 <?php $sql = "SELECT v.idVendeur ,v.nom+ ' ' + prenom AS Nom FROM vendeurs v";
                               $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );  
							    $i=0;					   
                                 while ($donnees =  sqlsrv_fetch_array($reponse))
                                {
								if($i==0)
								{
                                ?>
								   <option selected="selected" value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
								 <?php
								}else{
								 ?>
									<option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
								 <?php
								} 
								$i++;
								}
								 ?>
				</select>
				</div>
					</td>
				 	 <td width="40%" rowspan="2">	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['label']['visualiser']; ?>" class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?> " />
			  <input name="button" type="button"  onClick="telecharger();" value="<?php echo $trad['label']['telecharger']; ?>" class="bouton32" action="telecharger" title="<?php echo $trad['button']['rechercher']; ?> "  />
			  <!--input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider']; ?>" class="bouton32" action="effacer" title="<?php echo $trad['label']['vider']; ?>" /--></span><br/></td>
				</tr>	
<tr>
<td>
<div class="etiqForm" id="SYMBT" ><strong> <?php echo $trad['label']['mois']; ?> :</strong> </div>				
</td>
<td>
<select  name="Mois" id="Mois"  multiple="multiple" tabindex="3" class="Select Mois" style="display:visible;width:220px;">
<?php if (date('m')=="01") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="01"><?php echo $trad['label']['Janvier'];?></option>
	<?php if (date('m')=="02") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="02"><?php echo $trad['label']['Février'];?></option>
	<?php if (date('m')=="03") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="03"><?php echo $trad['label']['Mars'];?></option>
	<?php if (date('m')=="04") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="04"><?php echo $trad['label']['Avril'];?></option>
	<?php if (date('m')=="05") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="05"><?php echo $trad['label']['Mai'];?></option>
	<?php if (date('m')=="06") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="06"><?php echo $trad['label']['Juin'];?></option>
	<?php if (date('m')=="07") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="07"><?php echo $trad['label']['Juillet'];?></option>
	<?php if (date('m')=="08") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="08"><?php echo $trad['label']['Août'];?></option>
	<?php if (date('m')=="09") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="09"><?php echo $trad['label']['Septembre'];?></option>
	<?php if (date('m')=="10") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="10"><?php echo $trad['label']['Octobre'];?></option>
	<?php if (date('m')=="11") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="11"><?php echo $trad['label']['Novembre'];?></option>
	<?php if (date('m')=="12") $selected="selected"; else  $selected=""; ?>
	<option <?php echo $selected;?> value="12"><?php echo $trad['label']['Décembre'];?></option>
</select>
</td>
</tr>				
			 </table>
			 
		 </div>
		<!--div id="formFiltre" style="">
		<table border="0"  width="100%">
			<tr height="20">
			  <!--td width="23%">
			  <div id="filtreNPP">
			  	R&eacute;sultats par page : <select name="npp" id="npp" onChange="filtrer();">
					<option value="10">10</option>
					<option value="20" >20</option>
					<option value="50" selected="selected">50</option>
					<option value="100">100</option>
				</select>				
			  </div>
			  </td>
			  <td width="12%">Pages : <span id="cont_pages">
			    <select name="pact"><option value=1>1</option></select></span>
		  	  </td-->
			  <!--td width="50%" style="text-align:right">Crit&egrave;re de tri : 
				  <select name="cTri" onChange="filtrer();">
				  <option value="IdArticle">  </option>
				<option value="Immatriculation"> Immatriculation </option>
				<option value="DsgTransport">Désignation d'transport </option>				
				</select>
		  	  </td>
			  <td width="50%">&nbsp;&nbsp;&nbsp;&nbsp; Ordre de tri : 
				  <select name="oTri" onChange="filtrer();">
				<option value="ASC"> Croissant </option>
				<option value="DESC" selected> Decroissant </option>
				</select>
			  </td>

			</tr>
		</table>
	</div-->
	</form>
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>

<div id="formRes" style="overflow-y:scroll;min-height:480px;"></div>
<input type="hidden" id="act"/>
</div>
<div id="boxArticle"> </div>
<script language="javascript" type="text/javascript">

$(document).ready(function(){	
  		//$('#formRes').load('vente_hebdomadaire.php?aff');
$('#Vendeur').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le vendeur',single:true,maxHeight: 100
});
$('#Mois').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le vendeur',single:true,maxHeight: 100
});
				$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	400,
					height			:	380,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Ajout / Modification d\'une affectation',
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
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'vente_hebdomadaire.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		/*$('#formRechF').ajaxSubmit({target:'#formRes',url:'DetailVenteVnd.print.php?rech'})
		clearForm('formRechF',0);*/
		
		var vendeur=$("select#Vendeur").multipleSelect("getSelects");	
		var mois=$("select#Mois").multipleSelect("getSelects");	//.multipleSelect("getSelects", "text");
		window.open('DetailVenteVnd.print.php?rech&Vendeur='+vendeur+'&Mois='+mois, '_blank');	
	}
function telecharger(){
		/*$('#formRechF').ajaxSubmit({target:'#formRes',url:'DetailVenteVnd.print.php?rech'})
		clearForm('formRechF',0);*/
		
		var vendeur=$("select#Vendeur").multipleSelect("getSelects");	
		var mois=$("select#Mois").multipleSelect("getSelects");	//.multipleSelect("getSelects", "text");
		//window.open('vente_hebdomadaire.php?goPdf&Vendeur='+vendeur+'&Mois='+mois, '_blank');	
		alert("here");
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'vente_hebdomadaire.php?goPdf&Vendeur='+vendeur+'&Mois='+mois})
		clearForm('formRechF',0);
	}
function ajouter(){
		$('#act').attr('value','add');
		var url='vente_hebdomadaire.php?add';	
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='vente_hebdomadaire.php?mod&ID='+id;
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}
/*Designation: "required",
                                                Colisage: "required",
												Codeabarre : "required",
												Pa:"required",
												Unite:"required",
												Fournisseur:"required",
												Famille:"required",
												Tva:"required"*/
		function ouvrirPdf(){
				//alert("ouvrir");
					window.open("etats/RapportVente.pdf",'_self');
					return false;
				 
		}
function terminer(){
	var form="";
	var act = $('#act').attr('value');
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: { 
                                                Vehicule: "required",
												Vendeur:"required",
												'Departement[]':"required"
                                          }     });	
	var test=$(form).valid();
	verifSelect2('Vehicule');
	verifSelect2('Vendeur');
	verifSelect2('Departement');		
		if(test==true){		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'vente_hebdomadaire.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'vente_hebdomadaire.php?goAdd',
														method			:	'post'
													}); 
														//alert('add');
												
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
</script>



<?php
include("footer.php");
?>
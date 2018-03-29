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
if (isset($_GET['rech']) or isset($_GET['aff'])){
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
	?>
<script language="javascript" type="text/javascript">
$('#cont_pages').html('<?php echo $selPages; ?>');
</script>
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
		<form id="formSelec" method="post">
	<table class="mytable" width="100%" border="0"  cellpadding="5" cellspacing="5">
	
       <tr style="font-weight:bold;">
			<td rowspan="2"><?php echo $trad['label']['Date']; ?></td>  
			<td rowspan="2"><?php echo $trad['label']['typeVente'] ?></td>
	<!------------------------------------------Article------------------------------------->		
			<?php  foreach($gammes as $id => $designation) {  ?>
			<td colspan="5">
			<?php echo $designation; ?>
			</td>
			<td  class="spec_td"></td>
			<?php } ?> 
			
			<td colspan="5">
				<?php echo $trad['label']['totalQteVenteRS']; ?>			
			</td>
		   <td  class="spec_td" style="border-left:1px solid white"></td>
		</tr>
		<!------------------------------------------Col - Box - Palette------------------------------------->		
	   <tr>
	   <?php  foreach($gammes as $id => $designation) {  ?>
	
					<td><?php echo $trad['label']['Colisage']; ?></td>
					<td><?php echo $trad['label']['box']; ?></td>
					<td><?php echo $trad['label']['palette']; ?></td>
					<td><?php echo $trad['label']['qteBox']; ?></td>
					<td><?php echo $trad['label']['totalRS']; ?></td>
					<td class="spec_td"></td>
		<?php } ?>
		
					<td><?php echo $trad['label']['Colisage']; ?></td>
					<td><?php echo $trad['label']['box']; ?></td>
					<td><?php echo $trad['label']['palette']; ?></td>
					<td><?php echo $trad['label']['qteBox']; ?></td>
					<td><?php echo $trad['label']['totalRS']; ?></td>
					<td  class="spec_td" style="border-left:1px solid white"></td>
	   </tr>

	   

 
    <?php
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
?>
<!--  Date --------------------------------------- Ligne 1 Detaillant ----------------------------------------------->
<tr>
<?php
   // add the date to the dates array
   $d=date("Y-m-d", strtotime(date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)));
   $dates[$d] =  date("d F,Y-l", strtotime(date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)));
?>
	<!--Date -->
	<td rowspan="3"  style="font-weight:bold;direction:ltr;"><?php echo $dates[$d]; ?></td>   
	<!--Type Vente -- Detaillant -->
	<td><?php echo $trad['label']['detaillant']; ?></td>   
	<!--Article -->
<?php  foreach($gammes as $id => $designation) {  
 $total_gamme_Detaillant=0;$ttc_col=0;$ttc_box=0;$ttc_pal=0;
 $gamme = str_replace(' ', '', $designation);
?>
		<td>
		<?php // Colisage -- Detaillant
			$keys=array($d,"البقالة",$designation,"Colisage");
			$result = extract_value($vente, $keys);
			$key=$gamme."col";
			if($result =="")
			{
				echo " - ";
				$ValeurColDetail[$key]=0;
				$ttc_col=0;
				$totalDCol+=0;
			}
			else
			{
				echo $result["qte"];
				$ValeurColDetail[$key]=$result["qte"];
				$ttc_col=$result["ttc"];
				$totalDCol+=intval($result["qte"]);
			}
			
			
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);?>
		</td>
		<td>		
		<?php // Box -- Detaillant
			$keys=array($d,"البقالة",$designation,"Box");
			$result = extract_value($vente, $keys);
			$key=$gamme."box";
			if($result =="")
			{
				echo " - ";
				$ValeurColDetail[$key]=0;
				$ttc_box=0;
				$totalDBox+=0;
			}
			else
			{
				echo $result["qte"];
				$ValeurColDetail[$key]=$result["qte"];
				$ttc_box=$result["ttc"];
				$totalDBox+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);?>
		</td>
		<td>
		<?php // Palette -- Detaillant
			$keys=array($d,"البقالة",$designation,"Palette");
			$result = extract_value($vente, $keys);
			$key=$gamme."pal";
			if($result =="")
			{
				echo " - ";
				$ValeurColDetail[$key]=0;
				$ttc_pal=0;
				$totalDPal+=0;
			}
			else
			{
				echo $result["qte"];
				$ValeurColDetail[$key]=$result["qte"];
				$ttc_pal=$result["ttc"];
				$totalDPal+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result); ?>
			
		</td>
		<td><?php echo  " - ";//$trad['label']['qteBox']; ?></td>
		<td><?php $total_gamme_Detaillant = $ttc_col+$ttc_box+$ttc_pal;echo ($total_gamme_Detaillant==0) ?" - " : $total_gamme_Detaillant;
		$key=$gamme."total";
		$ValeurColDetail[$key]=$total_gamme_Detaillant;
		$totalDTtc+=$total_gamme_Detaillant;
		//$trad['label']['totalRS']; ?></td>
		<td  class="spec_td"></td>
<?php } ?>
		
					<td><?php echo  ($totalDCol==0) ?" - " : $totalDCol;//$trad['label']['Colisage']; ?></td>
					<td><?php echo  ($totalDBox==0) ?" - " : $totalDBox;//$trad['label']['box']; ?></td>
					<td><?php echo  ($totalDPal==0) ?" - " : $totalDPal;//$trad['label']['palette']; ?></td>
					<td><?php echo  " - ";//$trad['label']['qteBox']; ?></td>
					<td><?php echo  ($totalDTtc==0) ?" - " : $totalDTtc;//$trad['label']['totalRS']; ?></td>
					<td  class="spec_td" style="border-left:1px solid white"></td>
</tr>

<!--  Date -------------------------------------- Ligne 2 GROSSITE - ------------------------------------------>
<tr>
<?php $totalDCol=0;$totalDBox=0;$totalDPal=0;$totalDTtc=0; ?>	
	<!--Type Vente GROSSITE-->
	<td><?php echo $trad['label']['grossite']; ?></td>   
	<!--Article -->
<?php  foreach($gammes as $id => $designation) {  
	$total_gamme_Grossite=0;$ttc_col=0;$ttc_box=0;$ttc_pal=0;
	$gamme = str_replace(' ', '', $designation);
?>
	
		<td>
		<?php  // Colisage -- GROSSITE

			$keys=array($d,"البيع بالجملة",$designation,"Colisage");//print_r($keys);
			$result = extract_value($vente, $keys);
			$key=$gamme."col";
			if($result =="")
			{
				echo " - ";
				$ValeurColGros[$key]=0;
				$ttc_col=0;
				$totalDCol+=0;
			}
			else
			{
				echo $result["qte"];
				$ValeurColGros[$key]=$result["qte"];
				$ttc_col=$result["ttc"];
				$totalDCol+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);
		?>
		</td>
		<td>
		<?php  // Box -- GROSSITE

			$keys=array($d,"البيع بالجملة",$designation,"Box");//print_r($keys);
			$result = extract_value($vente, $keys);
			$key=$gamme."box";
			if($result =="")
			{
				echo " - ";
				$ValeurColGros[$key]=0;
				$ttc_box=0;
				$totalDBox+=0;
			}
			else
			{
				echo $result["qte"];
				$ValeurColGros[$key]=$result["qte"];
				$ttc_box=$result["ttc"];
				$totalDBox+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);
		?>
		</td>
		<td>
		<?php  // Palette -- GROSSITE

			$keys=array($d,"البيع بالجملة",$designation,"Palette");//print_r($keys);
			$result = extract_value($vente, $keys);
			$key=$gamme."pal";
			if($result =="")
			{
				echo " - ";
				$ValeurColGros[$key]=0;
				$ttc_pal=0;
				$totalDPal+=0;
			}
			else
			{
				echo $result["qte"];
				$ValeurColGros[$key]=$result["qte"];
				$ttc_pal=$result["ttc"];
				$totalDPal+=intval($result["qte"]);
			}
			//echo ($result =="")? " - " :  $result["qte"];//print_r($result);
		?>
		</td>
		<td><?php echo  " - ";//$trad['label']['qteBox']; ?></td>
		<td><?php $total_gamme_Grossite = $ttc_col+$ttc_box+$ttc_pal;echo ($total_gamme_Grossite==0) ?" - " : $total_gamme_Grossite;//$trad['label']['totalRS']; 
		$key=$gamme."total";
		$ValeurColGros[$key]=$total_gamme_Grossite;
		$totalDTtc+=$total_gamme_Grossite;
		?></td>
		<td class="spec_td"></td>
<?php } ?>
		
					<td><?php echo  ($totalDCol==0) ?" - " : $totalDCol;//$trad['label']['Colisage']; ?></td>
					<td><?php echo  ($totalDBox==0) ?" - " : $totalDBox;//$trad['label']['box']; ?></td>
					<td><?php echo  ($totalDPal==0) ?" - " : $totalDPal;//$trad['label']['palette']; ?></td>
					<td><?php echo  " - ";//$trad['label']['qteBox']; ?></td>
					<td><?php echo  ($totalDTtc==0) ?" - " : $totalDTtc;//$trad['label']['totalRS']; ?></td>
					<td  class="spec_td" style="border-left:1px solid white"></td>
</tr>

<!--  Date ---------------------------------------------- Ligne 3 TOTAL ------------------------------------------------->
<tr>
<?php $totalDCol=0;$totalDBox=0;$totalDPal=0;$totalDTtc=0; ?>	
	<td><?php echo $trad['label']['totalVente']; ?></td>   
	<!--GAMME -->
<?php  foreach($gammes as $id => $designation) {
$gamme = str_replace(' ', '', $designation);  
?>
	
		<td>
		<?php $key=$gamme . "col";
			  $somme=$ValeurColDetail[$key]+$ValeurColGros[$key];
			  echo ($somme==0) ? " - " : $somme;
			  $totalDCol+=$somme;
		?>
		
		</td>
		<td><?php 
				$key=$gamme . "box";
				$somme=$ValeurColDetail[$key]+$ValeurColGros[$key];
				echo ($somme==0) ? " - " : $somme;	
				$totalDBox+=$somme;		
			?></td>
		<td><?php 
				$key=$gamme . "pal";
				$somme=$ValeurColDetail[$key]+$ValeurColGros[$key];
				echo ($somme==0) ? " - " : $somme;	
				$totalDPal+=$somme;				
		?></td>
		<td><?php echo  " - ";//$trad['label']['qteBox']; ?></td>
		<td><?php 
				$key=$gamme . "total";
				$somme=$ValeurColDetail[$key]+$ValeurColGros[$key];
				echo ($somme==0) ? " - " : $somme;		
				$totalDTtc+=$somme;
		?></td>
		<td  class="spec_td"></td>
<?php } ?>
		
					<td><?php echo  ($totalDCol==0) ?" - " : $totalDCol;//$trad['label']['Colisage']; ?></td>
					<td><?php echo  ($totalDBox==0) ?" - " : $totalDBox;//$trad['label']['box']; ?></td>
					<td><?php echo  ($totalDPal==0) ?" - " : $totalDPal;//$trad['label']['palette']; ?></td>
					<td><?php echo  " - ";//$trad['label']['qteBox']; ?></td>
					<td><?php echo  ($totalDTtc==0) ?" - " : $totalDTtc;//$trad['label']['totalRS']; ?></td>
					<td  class="spec_td" style="border-left:1px solid white"></td>
</tr>
<tr style="border-left:1px solid white" ><td class="spec_tr"></td></tr>
<?php
 
}//Fin For 


/*
echo "<pre>";
print_r($dates);
echo "</pre>";
return;

*/


}
?>
   </table>
	<!--</div>-->
    </form>
<script language="javascript" type="text/javascript">
	
	
		
	</script>
<?php
exit;
}

?>
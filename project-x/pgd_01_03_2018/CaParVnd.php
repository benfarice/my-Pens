<?php 

include("php.fonctions.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "utilisateur";
$sansDoublons = "NomCaisse";
$cleTable = "IdUtilisateur";
$nom_sansDoublons = "Nom de la caisse";


if(isset($_GET['go'])){
//parcourir($_POST);
$And="";
$titre="";

$tri=" DESC";
if(isset($_POST['etat']) && !empty($_POST['etat']))
{
	if($_POST['etat']=="plus")
	{
	$tri=" DESC";	
	}else if($_POST['etat']=="moins")
	{
	$tri=" ASC";		
	}
}
//echo $And; echo "heree";exit;
  
	$where="";
	$Title="Les produits les plus consommés ";
	$ville="";$TitleD="";
	

	 if(isset($_POST['DateD']) && isset($_POST['DateF'])  ){
		if($_POST['DateD'] == $_POST['DateF'])
		{ 
			/*if( $Title=="") $Title="Liste des commandes ";*/
			$where.= " where cast(f.date AS date)= convert(date,'".($_POST['DateD'])."',105) ";
			$TitleD= $trad['label']['titreCaPdtTitreLe']." <b>" .$_POST['DateD']."</b>";
			setcookie("TitleD", $TitleD);
		}
		else {
		/*	if( $Title=="") $Title="Liste des commandes ";*/
			$where.= " where cast(f.date AS date) between  convert(date,'".($_POST['DateD'])."',105) and convert(date,'".($_POST['DateF'])."',105) ";
			 $TitleD= $trad['label']['titreCaPdtTitreDeA']." [ <b>" .$_POST['DateD'] .' '.$trad['label']['a'] .' '.$_POST['DateF'] ."</b> ]";
			setcookie("TitleD", $TitleD);
		}
	 }

	//if(isset($_POST['Pdt']) && ($_POST['Pdt']!='') ) $where .=" AND l.NumPdt like '".$_POST['Pdt']."' " ;
	
	$sql = " select v.idVendeur, v.cin,v.nom+ ' ' + prenom AS nom, sum(f.totalTTC) AS Total FROM factures f 
				INNER JOIN vendeurs v ON f.idVendeur=v.idVendeur   ";
// select magasin 
	//$where.= " and pr.IdPointVente =".$_POST['selectItemMagasin'];

			/*if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
			else $oTri= "DESC";*/
//ECHO $sql.$where." and  EtatCmd=2 AND v.idVendeur=1030";
	$sqlEtat="SELECT TOP 20 * FROM ( ".$sql.$where." and f.idVendeur<>12 and  EtatCmd=2 GROUP BY v.idVendeur,v.cin,v.nom, prenom) as tab  ORDER BY Total $tri ";
	//echo $sqlEtat."<hr>";
	$qteTotal="";
	$TotalG="";
	$html ="";
    
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$resAff = sqlsrv_query($conn,$sqlEtat,$params,$options) or die( print_r( sqlsrv_errors(), true));
	$nRes = sqlsrv_num_rows($resAff);
	if($nRes==0)
		{ ?>
					<div class="resAff">
						<br><br>
							<?php echo $trad['msg']['AucunResultat']; ?>
					</div>
					<?php
		}
		else{
	
	//$nResultats = mysql_num_rows($resEtat) ;
	$CATotal=0;
	$caFamille="";$mFamille="";$noms="";$pctCA="m";
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
		//$caFamille .= "['".$RowVille['Ville']."@+".$RowVille['Qte']."' ], ";
		//$caFamille .= "['".$RowVille['Ville']."@".$RowVille['Qte']."', ".$RowVille['Qte']." ], ";
		$caFamille .="{ name: '".addslashes($row['nom'])."', y: ".$row['Total'].", Total: ".$row['Total']."},";
		$mFamille .= " ".$row['Total'].", ";
		$noms .= " '".addslashes($row['nom'])."', ";
		$CATotal+=$row['Total'];
		//$PointVente=$row['Designation'];
	}
	//echo virgule($caFamille)  ;echo $TitleD;
	$nomPrd=$row['DsgProduit'];

		?> 
		<table width="100%" border="0"  style="direction:ltr;" align="center">
  		<tr>
			<td ><!-- colspan="2"-->
			<div class="titreG"></div>
			
			</td>
		</tr>
		<tr>
			<td width="40%">
            <!--       <input name="button" type="button" style="display:block" class="bouton32" tabindex="10" id="ExportG" value="Exporter" />-->
            <div id="graphG"></div>
			</td>

		</tr>
		
	   </table>
<style>
.highcharts-yaxis-title tspan  {
font-size:20px;
}
.highcharts-title tspan{
font-size:24px;
}
</style>
	<script language="javascript" type="text/javascript">

	$(document).ready(function(){

	 $('#graphG').highcharts('Chart', {
        chart: {
         plotBackgroundColor: null,
			 plotBorderWidth: null,
			 plotShadow: false,
			 type: 'column'
			 
        },
        title: {

		text: '<?php
		   $titre=$TitleD;
		 echo $titre." <br> Le <b>".date('d/m/Y H-i')."</b><br> Total chiffre d\'affaire<br> <b>". number_format($CATotal, 2, '.', ' ') . " ( " . $trad['label']['riyal'] . " )</b> "; 
		?>  ' 
        },
		exporting: {
    		   enabled:true
        },
	    xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: '<?php echo $trad['label']['totalGlobal'] . " ( " . $trad['label']['riyal'] . " ) "; ?>'
            }
        },
      legend: {
            enabled: false
        },
        tooltip: {
          /*  headerFormat: '<span style="font-size:11px">{series.name}</span><br>',*/
            pointFormat:'Total : <b>{point.y:,.1f}</b> DH <br/>' <!--<span style="color:{point.color}">{point.name}</span>-->
        },
		credits: {enabled: false	},
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                  //  format: ' {point.y:,.0f}'
				   formatter: function () {
						return Highcharts.numberFormat(this.y,2);
					}
                }
            }
        },

        series: [{
       			
				 name: '',
				 colorByPoint: true,
				 data: [<?php echo virgule($caFamille); ?>]
				
       	 }]
    });
	  $(document).ready(function () {

    });
	//chart.exportChart(); 
});


	
	   </script>
	   
	   
	<?php 
/*
@mysql_free_result();
mysql_close();
unset($tot);
unset($html);
*/
}
exit;
}
if (isset($_GET['rech']) or isset($_GET['aff'])){

exit;
}
?>
<?php include("header.php");



 ?>

<div class="pageBack" >

<div id="boxpwd"> </div>
<div class="contenuBack">
	<div id="brouillon" style="display:block">  </div> 
	<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['statistic']; ?>&nbsp;<img src="images/tri.png" />
		&nbsp;<?php echo $trad['Menu']['caVendeur']; ?><span style="font-weight: bold"></span> &nbsp;</div>

	

	<form id="formRechF" method="post" name="formRechF"> 
					<div id="formRech" style="">	
		<table width="81%" border="0" cellspacing="10" cellpadding="10" align="center" >
		<TR>	
				</tr>
			  <tr>
		<td Align="right"><?php echo $trad['label']['de']; ?> &nbsp;
<input class="formTop" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" maxlength="10"
 onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	&nbsp;<?php echo $trad['label']['a']; ?> &nbsp;
<input name="DATED" type="hidden" value=""/>	
<input class="formTop" g="date" id="DateF" tabindex="2" name="DateF" type="text" size="10" maxlength="10" onChange="verifier_date(this);" 
value="<?php echo date('d/m/Y'); ?>"/>	
<input name="DATED" type="hidden" value=""/>
				
				</td>
				
				<td> 	
			&nbsp;&nbsp;&nbsp;<b><?php echo $trad['label']['consommation']; ?>   :</b>
			<Input type ='Radio' Name ='etat' checked="checked" value="plus" />  <?php echo $trad['label']['plus']; ?>   <!--onclick="search('encours');"-->
			<Input type ='Radio' Name ='etat' value="moins" /> <?php echo $trad['label']['moins']; ?>   <!--onclick="search('traitee');" -->
		</td>
				</tr>
				<tr >
		      <td   align="CENTER" Colspan=4>	<span class="actionForm">      
          <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?>" id="Rechercher" />
			      <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider']; ?>" class="bouton32" action="effacer" title="<?php echo $trad['label']['vider']; ?>"/>
				  
				    </span>
				</td>
		</tr>
	
			
	 	 </table>
         </div>
 
	</form>
			
		
	
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>
<style>
input[action=ajout]{background:transparent url(images/add24.png) top center no-repeat;}
input[action=down]{background:transparent url(images/down3.png) top center no-repeat;}
input[action=up]{background:transparent url(images/up3.png) top center no-repeat;}
input.button16	{
	cursor:pointer;
	height:24px;
	width:30px;
	opacity:0.6;
	border:0px;	
	text-align:center;
	}
#formRes td{
	padding:7px 2px;
}

</style>
	
<div id="formRes" style="overflow-y:scroll; width:100%; min-height:280px; max-height:600px;"></div>
<input type="hidden" id="act"/>
  </div>

</div>
<style>
label.error  {
color:#990000;
}
</style>
<script language="javascript" type="text/javascript">

$(document).ready(function(){
		calendrier("DateD");
		calendrier("DateF");
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'CaParVnd.php?go'});
		patienter('formRes');
	$('#Magasin').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez le magasin ',single:true,maxHeight: 100
	});
});

function filtrer(){

		$('#formRechF').ajaxSubmit({target:'#formRes',url:'CaParVnd.php?go',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'CaParVnd.php?go'})
			//clearForm('formRechF',0);
	}



	$('body').on('keypress', '#NomComplet', function(args) {
   if (args.keyCode == 13) {
       $("#Rechercher").click();
       return false;
   }
});

</script>

		<?php include("footer.php"); ?>
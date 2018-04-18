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
$year="";
if(isset($_POST['Year']) && !empty($_POST['Year']))
{
$year=$_POST['Year'];
}

//echo $And; echo "heree";exit;
  
	$where="";
    $Title=$trad['label']['venteAnnee'].$year;
	$TitleD="";
	

	//if(isset($_POST['Pdt']) && ($_POST['Pdt']!='') ) $where .=" AND l.NumPdt like '".$_POST['Pdt']."' " ;
	
	$sql = " SELECT * FROM factures 
			WHERE  idDepot<>1 and  EtatCmd=2 and Year(cast(factures.date AS date) )= ".$year;

			
	///$sqlEtat="SELECT TOP 20 * FROM ( ".$sql.$where." GROUP BY v.idVendeur,v.cin,v.nom, prenom) as tab  ORDER BY Total $tri ";
	//echo $sqlEtat."<hr>";
	$qteTotal="";
	$TotalG="";
	$html ="";
    
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
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
	
	$caFamille="";$mFamille="";$noms="";$pctCA="m";
	$current_month=date('n');
	$month=array('',$trad['label']['Janvier'],$trad['label']['Février' ],$trad['label']['Mars'],$trad['label']['Avril'],$trad['label']['Mai'],$trad['label']['Juin'],$trad['label']['Juillet'],$trad['label']['Août'],$trad['label']['Septembre'],$trad['label']['Octobre'],$trad['label']['Novembre'],$trad['label']['Décembre']);
		//	print_r($current_month);	
	$CATotal=0;		
	for($i=1;$i<=12;$i++){
		$req="SELECT sum(factures.totalTTC) FROM factures 
				WHERE  idDepot<>1 and  EtatCmd=2  
				and Year(cast(factures.date AS date) )= ".$year." and 
				Month(cast(factures.date AS date))=".$i;//echo"-- ".$req;
//echo $req."</br>";
		$stmt2 = sqlsrv_query( $conn, $req );
		sqlsrv_fetch($stmt2) ;
		$totalF = sqlsrv_get_field( $stmt2, 0);
		if($totalF=='')
		$totalF="0";		
		$caFamille .="{ name: '".$month[$i]."', y: ".$totalF.", Total: ".$totalF."},";
		$mFamille .= " ".$totalF.", ";		
		$noms .= " '".$month[$i]."', ";
			$CATotal+=$totalF;
	}
	//echo virgule($caFamille)  ;echo $TitleD;
	//$nomPrd=$row['DsgProduit'];

		?> 
		<table width="100%" border="0" style="direction:ltr;" align="center">
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

			//text: '<?php   $titre=$_POST['Year'];	 echo $trad['label']['venteAnnee'] . ' '.$titre; ?>'
			text: '<?php
		   $titre=$TitleD;
		 echo $trad['label']['venteAnnee'] . ' '. $_POST['Year']." <br>  Le <b>".date('d/m/Y H-i')."</b><br> Total chiffre d\'affaire<br> <b>". number_format($CATotal, 2, '.', ' ') . " ( " . $trad['label']['riyal'] . " )</b> "; 
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
           // pointFormat: ''/*'Total : <b>{point.y}</b> DH <br/>' */<!--<span style="color:{point.color}">{point.name}</span>-->
		     pointFormat:'Total : <b>{point.y:,.1f}</b> DH <br/>'
        },credits: {enabled: false	},
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    formatter: function () {
						return Highcharts.numberFormat(this.y,2);
					}
                }
            }
        },

        series: [{
       			
				 name: 'Les produits les plus vendus',
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
		&nbsp;<?php echo $trad['Menu']['venteMois']; ?><span style="font-weight: bold"></span> &nbsp;</div>

	

	<form id="formRechF" method="post" cellspacing="10" cellpadding="10" name="formRechF"> 
					<div id="formRech" style="">	
		<table width="50%" border="0" cellspacing="10" style="margin:auto" cellpadding="10" align="center" >
		
			  <tr>
			<td width="20%" valign="middle">
			<div class="etiqForm" id="DATE_PIECE" > <strong><?php echo $trad['label']['annee']; ?> :</strong></div>
			</td>			  
			  <td width="25%" valign="middle"><!--  -->
			  <select  name="Year" id="Year"  multiple="multiple" tabindex="3"    class="Select Year">
			 
					 <option value="2017">2017</option>    
					 <option  selected="selected" value="2018">2018</option>   
			  </select>
			  </td>
				
		      <td   align="CENTER" Colspan=4>	<span class="actionForm">      
          <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?>" id="Rechercher" />
				  
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

		$('#Year').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez l\'année ',single:true,maxHeight: 100,width:200
	});
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'VenteParMois.php?go',clearForm:false});
		patienter('formRes');
});

function filtrer(){

		$('#formRechF').ajaxSubmit({target:'#formRes',url:'VenteParMois.php?go',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'VenteParMois.php?go'})
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
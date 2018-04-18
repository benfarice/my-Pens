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
//parcourir($_POST); return;
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
			WHERE  Year(cast(factures.date AS date) ) between  ".$_POST['YearD']." and ".$_POST['YearF'];

	
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
	$month=array('',$trad['label']['Janvier'],$trad['label']['Février' ],$trad['label']['Mars'],$trad['label']['Avril'],
	$trad['label']['Mai'],$trad['label']['Juin'],$trad['label']['Juillet'],$trad['label']['Août'],$trad['label']['Septembre'],
	$trad['label']['Octobre'],$trad['label']['Novembre'],$trad['label']['Décembre']);
			//print_r($current_month);return;	
			
	for($i=1;$i<=$current_month;$i++){
		$req="SELECT sum(factures.totalTTC) FROM factures 
				WHERE idDepot<>1 and EtatCmd=2  and Month(cast(factures.date AS date))=".$i;//echo"-- ".$req;
		
		$stmt2 = sqlsrv_query( $conn, $req );
		sqlsrv_fetch($stmt2) ;
		$totalF = sqlsrv_get_field( $stmt2, 0);
		if($totalF=='')
		$totalF="0";		
		$caFamille .="{ name: '".$month[$i]."', y: ".$totalF.", Total: ".$totalF."},";
		$mFamille .= " ".$totalF.", ";
		$noms .= " '".$month[$i]."', ";
	}
	for($year=$_POST['YearD'];$year<=$_POST['YearF'];$i++){
		for($i=$_POST['MonthD'];$i<=$_POST['MonthF'];$i++){
		$req="SELECT sum(factures.totalTTC) FROM factures 
				WHERE idDepot<>1 and EtatCmd=2  Year(cast(factures.date AS date) ) =  ".$year." and ".$_POST['YearF'];
		
		$stmt2 = sqlsrv_query( $conn, $req );
		sqlsrv_fetch($stmt2) ;
		$totalF = sqlsrv_get_field( $stmt2, 0);
		if($totalF=='')
		$totalF="0";		
		$caFamille .="{ name: '".$month[$i]."', y: ".$totalF.", Total: ".$totalF."},";
		$mFamille .= " ".$totalF.", ";
		$noms .= " '".$month[$i]."', ";
		}
	}
	
	//echo virgule($caFamille)  ;echo $TitleD;
	//$nomPrd=$row['DsgProduit'];
$Serie="{
            name: '2016',
            data:[".virgule($caFamille)."]
        },
		{
            name: '2017',
            data:[".virgule($caFamille)."]
        },
		{
            name: '2018',
            data:[". virgule($caFamille)."]
        }";
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
$('#graphG').highcharts('Chart', { chart: {
        type: 'spline'
    },
    title: {
        text: 'Monthly Average Temperature'
    },
    subtitle: {
        text: 'Source: WorldClimate.com'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Temperature'
        },
        labels: {
            formatter: function () {
                return this.value + '°';
            }
        }
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
   /* series: [{
        name: 'Tokyo',
        marker: {
            symbol: 'square'
        },
        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2,26.5, 23.3, 18.3, 13.9, 9.6]

    }, {
        name: 'London',
        marker: {
            symbol: 'diamond'
        },
        data: [ 3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
    }]*/
	   series: [<?php echo $Serie;?>]
});
	/* $('#graphG').highcharts('Chart', {
        chart: {
         plotBackgroundColor: null,
			 plotBorderWidth: null,
			 plotShadow: false,
			 type: 'column'
			 
        },
        title: {

text: '<?php   $titre=$_POST['Year'];	 echo $trad['label']['venteAnnee'] . ' '.$titre; ?>'
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
        },credits: {enabled: false	},
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
            }
        },

        series: [{
       			
				 name: 'Les produits les plus vendus',
				 colorByPoint: true,
				 data: [<?php echo virgule($caFamille); ?>]

				
       	 }]
    });
	 */
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
		<table width="700" border="0" cellspacing="10" style="margin:auto" cellpadding="10" align="center" >
		
			  <tr>
			<td  align="right" width="130"><strong><?php echo $trad['label']['date']; ?> :</strong></td>
		<td  colspan="4" align="LEFT">	
				<?php echo $trad['label']['de']; ?> 
<input class="formTop" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="20" maxlength="20" 
onChange="verifier_date(this);" 	>&nbsp;<?php echo $trad['label']['a']; ?> 
<input name="DATED" type="hidden" value=""/>	
<input name="MonthD" id="MonthD"  type="hidden" value=""/>	
<input name="YearD" id="YearD" type="hidden" value=""/>	

<input name="DATED" type="hidden" value=""/>	
<input class="formTop" g="date" id="DateF" tabindex="2" name="DateF" type="text" size="20" maxlength="20"
 onChange="verifier_date(this);" />	
<input name="DATEF" type="hidden" value=""/>	
<input name="MonthF" id="MonthF" type="hidden" value=""/>	
<input name="YearF" id="YearF" type="hidden" value=""/>	

				</td>
				<!--td width="20%" valign="middle">
			<div class="etiqForm" id="DATE_PIECE" > <strong><?php echo $trad['label']['annee']; ?> :</strong></div>
			</td>			  
	
		  <td width="25%" valign="middle">

			  <select  name="Year" id="Year"  multiple="multiple" tabindex="3"    class="Select Year">
			 
					 <option selected="selected" value="2017">2017</option>    
					 <option value="2018">2018</option>   
			  </select>
			  </td !-->
				
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

.ui-datepicker-calendar {
    display: none;
    }

</style>
<script language="javascript" type="text/javascript">

$(document).ready(function(){
		//calendrier("DateD");
	//calendierMonthYear("DateF");
	//calendierMonthYear("DateD");

	$('#DateD').datepicker({
		 changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
		dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
		weekHeader: 'Sem.',
		yearRange: '2017:'+(new Date).getFullYear() ,
		onClose: function(dateText, inst) {			
			month= parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val())+1;
			year= parseInt($("#ui-datepicker-div .ui-datepicker-year :selected").val());
			  $("#MonthD").val(month);
            $("#YearD").val(year);		
			$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
	});
	
		$('#DateF').datepicker({
		 changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
		dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
		weekHeader: 'Sem.',
		yearRange: '2017:'+(new Date).getFullYear() ,
		onClose: function(dateText, inst) {			
			month= parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val())+1;
			year= parseInt($("#ui-datepicker-div .ui-datepicker-year :selected").val());
             $("#MonthF").val(month);
			 $("#YearF").val(year);		
			$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
	});
	
	
		$('#Year').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez l\'année ',single:true,maxHeight: 100,width:200
	});
	rechercher();
});

function filtrer(){

		$('#formRechF').ajaxSubmit({target:'#formRes',url:'VenteParMois.php?go',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'VenteParMois.php?go'});
			patienter('formRes');
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
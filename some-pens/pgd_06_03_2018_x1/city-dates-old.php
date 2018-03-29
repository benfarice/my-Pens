<?php
require_once('connexion.php');
include("lang.php");
include("header_y.php");?>
<?php 
$a_date_d ='01/12/2017';
$a_date_f = date('d/m/Y');
if(isset($_POST['DateD']) && isset($_POST['DateF'])  ){
	$a_date_d = $_POST['DateD'];
	$a_date_f = $_POST['DateF'];
} 
?>
<form id="formRechF" method="post" name="formRechF" action="city-dates.php"> 
          <div id="formRech" style=""> 
          <table border="0" cellspacing="10" 
          cellpadding="10" align="center" >
          <tr>
	          <td Align="right"><?php echo $trad['label']['de']; ?> &nbsp;
		          <input class="formTop" g="date" id="DateD" tabindex="2" 
		          name="DateD" type="text" size="10" maxlength="10"
		          onChange="verifier_date(this);"
		          value="<?php echo $a_date_d ; ?>"/> 
		          &nbsp;<?php echo $trad['label']['a']; ?> &nbsp;
		          <input name="DATED" type="hidden" value=""/>  
		          <input class="formTop" g="date" id="DateF" tabindex="2"
		          name="DateF" type="text" size="10" maxlength="10"
		          onChange="verifier_date(this);" value="<?php echo $a_date_f; ?>"/>  
		          <input name="DATED" type="hidden" value=""/>
	          </td>
	       </tr>
	       <tr>
	          <td align="CENTER" Colspan=4> 
	          <span class="actionForm">      
	          <input name="button" type="submit"  
	          value="<?php echo $trad['button']['rechercher']; ?>" 
	          class="bouton32" action="rech" 
	          title="<?php echo $trad['button']['rechercher']; ?>" id="Rechercher" />
	          <input name="button2" type="reset" onClick=""
	          value="<?php echo $trad['label']['vider']; ?>" 
	          class="bouton32" action="effacer" 
	          title="<?php echo $trad['label']['vider']; ?>"/>
	          </span>
	          </td>
          </tr>
          <tr>
          	<td>
          		<div class="input-group">
				  <div class="input-group-prepend">
				    <div class="input-group-text">
				    <input type="radio" aria-label="Radio button for following text input" name="select_view">
				    </div>
				  </div>
				  <input type="text" class="form-control" aria-label="Text input with radio button"
				  value="chart" readonly="readonly">
				</div>
			</td>
			<td>
				<div class="input-group">
				  <div class="input-group-prepend">
				    <div class="input-group-text">
				    <input type="radio" aria-label="Radio button for following text input" name="select_view">
				    </div>
				  </div>
				  <input type="text" class="form-control" aria-label="Text input with radio button"
				  value="chart" readonly="readonly">
				</div>
          	</td>
          </tr>
          </table>
          </div>

         
</form>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
    calendrier("DateD");
    calendrier("DateF");
});
</script>
<style type="text/css">
	.see_here_more_class{
		display: none;
	}

</style>
<?php
$where="";
if(isset($_POST['DateD']) && isset($_POST['DateF'])  )
		{
			if($_POST['DateD'] == $_POST['DateF'])
			{ 
			 	 $where.= "  cast(fa.date AS date) = convert(date,'".($_POST['DateD'])."',105) ";
			}
			else
			{
				 $where.= "  cast(fa.date AS date)  between  convert(date,'".($_POST['DateD'])."',105) and convert(date,'".($_POST['DateF'])."',105) ";
			}
		}
		else
		{
		$where="  cast(fa.date AS date)=convert(date,'".(date('d/m/Y'))."',105)";
		}
//echo $where;
$query_get_cities = "select  v.Designation as city ,sum(dtf.ttc) as 
	 total  from  detailFactures dtf
	 inner join factures fa on dtf.idFacture =
	  fa.IdFacture and EtatCmd = 2 
	 inner join depots dpt on dpt.idDepot = fa.idDepot  and dpt.idDepot <> 1 
	 inner join villes v on v.idville = dpt.IdVille
	  where $where group by v.Designation";
$params0 = array();
//echo $query_get_cities."<br>";
$options0 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt0=sqlsrv_query($conn,$query_get_cities,$params0,$options0);
$ntRes0 = sqlsrv_num_rows($stmt0);
if($ntRes0==0)
		{ ?>
					<div class="resAff">
						<br><br>
						<?php echo $trad['msg']['AucunResultat']; ?>
						<?php for($i = 1;$i < 20;$i++) {?>
						<br>
						<?php } ?>
					</div>
					<?php
		}
else{
while($row = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){	


//echo $row['city']."<br>";
$query_marque = "select m.Designation as mar,sum(dtf.ttc)
as total  from detailFactures dtf 
inner join factures fa on fa.IdFacture = dtf.idFacture and EtatCmd = 2 
inner join depots dpt on dpt.idDepot = dtf.idDepot and dpt.idDepot <> 1 
inner join villes v on v.idville = dpt.IdVille 
inner join articles a on dtf.idArticle = a.IdArticle 
inner join gammes g on a.IdFamille = g.IdGamme 
inner join marques m on g.IdMarque = m.idMarque 
where  $where and v.Designation = '$row[city]' 
group by m.Designation";
//echo $query_marque."<br>" ;
$params_2 = array();
$options_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marque=sqlsrv_query($conn,$query_marque,$params_2,$options_2);
$ntRes_marques = sqlsrv_num_rows($stmt_marque);
?> 
<div class="jumbotron">
	<p class="text-center" style="padding: 4px;font-size: 50px;
	color:#3498db;text-transform: capitalize;">
		<?php 
		$y_total = number_format($row['total'], 2, ',', ' '); 
		echo $row['city']." - ".$y_total ; ?>	
		DH TTC
	</p>

</div>

<?php 
while($reader_marque = sqlsrv_fetch_array($stmt_marque, SQLSRV_FETCH_ASSOC)){	

$sqlA = "SELECT a.Reference as ref,a.Designation as 
 article,df.qte AS qte 
 ,df.ttc AS ttc FROM factures fa 
  INNER JOIN detailFactures df ON fa.IdFacture=df.idFacture and EtatCmd = 2 
 INNER JOIN articles a ON a.IdArticle=df.idArticle 
 INNER JOIN depots dps on fa.idDepot = dps.idDepot and dps.idDepot <> 1 
 inner join villes v on dps.IdVille = v.idville 
 inner join gammes g on a.IdFamille = g.IdGamme 
 inner join marques m on m.idMarque = g.IdMarque 
    where
    v.Designation = '$row[city]' and m.Designation ='$reader_marque[mar]' and ".$where;
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

//echo $sqlA;
$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
$ntRes = sqlsrv_num_rows($stmt);
//echo $sqlA;
?>
<div class="row" style="border: 1px solid gray;padding: 15px;background: #34495e;color: #ecf0f1">
		<div class="col-md-6 text-left"  >
			<strong>
				<?php echo $reader_marque['mar']; ?>
			</strong>
	    </div>
		<div class="col-md-6 text-center">
			<strong>
				CA: <?php 
				$x_total =  $reader_marque['total'];
				echo number_format($x_total, 2, ',', ' '); 
				?>
			</strong>
			<img class="see_more_class" src="images\add24.png">
		</div>
</div>

<div class="row see_here_more_class">
		
		<div class="col-md-12 col-xs-12">
	      <table class="table table-striped">
		  	<thead class="thead-inverse">
	   		<tr>
	   			<th><?php echo $trad['label']['reference']; ?></th>
		        <th>Désignation</th>
				<th><?php echo $trad['label']['qteVendu']; ?> </th>
		        <th>
		        	<?php echo $trad['label']['ValTTC'] . 
		        	'('.$trad['label']['riyal'] .')'; ?> 
		        </th>
         	</tr>
         	</thead>
	   		<tbody>

         	
	   		

<?php
while($reader = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){	
	//echo $reader['ville']."<br>";
?> 			<tr>
         		<td><?php echo $reader['ref']; ?></td>
         		<td><?php echo $reader['article']; ?></td>
         		<td><?php echo $reader['qte']; ?></td>
         		<td><?php echo $reader['ttc']; ?></td>
         	</tr>
<?php
} ?>
			<tr>
				<td colspan="2"></td>
				<td colspan="2" class="text-right">
					<?php echo $row['city']." - ". $row['total']; ?>
				</td>

			</tr>
			</tbody>
          </table>
	</div>
</div>
<?php
}
}
}
?>
<style type="text/css">
	.see_here_more_class{
		display: none;
	}

</style>
<script type="text/javascript">
$( document ).ready(function() {
  $( ".see_more_class" ).click(function() {
  	$(this).parent().parent().next().toggle();
  });
});

</script>
<style type="text/css">
	 *{
	 	letter-spacing: -.05em;
		font-weight: bold;
		font-family: 'Montserrat', sans-serif;
		}
</style>
<script type="text/javascript">
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Browser market shares. January, 2015 to May, 2015'
    },
    subtitle: {
        text: 'Click the columns to view versions. Source: <a href="http://netmarketshare.com">netmarketshare.com</a>.'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total percent market share'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'Microsoft Internet Explorer',
            y: 56.33,
            drilldown: 'Microsoft Internet Explorer'
        }, {
            name: 'Chrome',
            y: 24.03,
            drilldown: 'Chrome'
        }, {
            name: 'Firefox',
            y: 10.38,
            drilldown: 'Firefox'
        }, {
            name: 'Safari',
            y: 4.77,
            drilldown: 'Safari'
        }, {
            name: 'Opera',
            y: 0.91,
            drilldown: 'Opera'
        }, {
            name: 'Proprietary or Undetectable',
            y: 0.2,
            drilldown: null
        }]
    }],
    drilldown: {
        series: [{
            name: 'Microsoft Internet Explorer',
            id: 'Microsoft Internet Explorer',
            data: [
                [
                    'v11.0',
                    24.13
                ],
                [
                    'v8.0',
                    17.2
                ],
                [
                    'v9.0',
                    8.11
                ],
                [
                    'v10.0',
                    5.33
                ],
                [
                    'v6.0',
                    1.06
                ],
                [
                    'v7.0',
                    0.5
                ]
            ]
        }, {
            name: 'Chrome',
            id: 'Chrome',
            data: [
                [
                    'v40.0',
                    5
                ],
                [
                    'v41.0',
                    4.32
                ],
                [
                    'v42.0',
                    3.68
                ],
                [
                    'v39.0',
                    2.96
                ],
                [
                    'v36.0',
                    2.53
                ],
                [
                    'v43.0',
                    1.45
                ],
                [
                    'v31.0',
                    1.24
                ],
                [
                    'v35.0',
                    0.85
                ],
                [
                    'v38.0',
                    0.6
                ],
                [
                    'v32.0',
                    0.55
                ],
                [
                    'v37.0',
                    0.38
                ],
                [
                    'v33.0',
                    0.19
                ],
                [
                    'v34.0',
                    0.14
                ],
                [
                    'v30.0',
                    0.14
                ]
            ]
        }, {
            name: 'Firefox',
            id: 'Firefox',
            data: [
                [
                    'v35',
                    2.76
                ],
                [
                    'v36',
                    2.32
                ],
                [
                    'v37',
                    2.31
                ],
                [
                    'v34',
                    1.27
                ],
                [
                    'v38',
                    1.02
                ],
                [
                    'v31',
                    0.33
                ],
                [
                    'v33',
                    0.22
                ],
                [
                    'v32',
                    0.15
                ]
            ]
        }, {
            name: 'Safari',
            id: 'Safari',
            data: [
                [
                    'v8.0',
                    2.56
                ],
                [
                    'v7.1',
                    0.77
                ],
                [
                    'v5.1',
                    0.42
                ],
                [
                    'v5.0',
                    0.3
                ],
                [
                    'v6.1',
                    0.29
                ],
                [
                    'v7.0',
                    0.26
                ],
                [
                    'v6.2',
                    0.17
                ]
            ]
        }, {
            name: 'Opera',
            id: 'Opera',
            data: [
                [
                    'v12.x',
                    0.34
                ],
                [
                    'v28',
                    0.24
                ],
                [
                    'v27',
                    0.17
                ],
                [
                    'v29',
                    0.16
                ]
            ]
        }]
    }
});
</script>
<?php include 'footer.php' ?>
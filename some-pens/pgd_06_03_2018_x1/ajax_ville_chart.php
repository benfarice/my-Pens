 <?php
require_once('connexion.php'); 
include("lang.php"); 
$where="";
if(isset($_REQUEST['DateD']) && isset($_REQUEST['DateFin'])  )
		{
			if($_REQUEST['DateD'] == $_REQUEST['DateFin'])
			{ 
			 	 $where.= "  cast(fa.date AS date) = convert(date,'".($_REQUEST['DateD'])."',105) ";
			}
			else
			{
				 $where.= "  cast(fa.date AS date)  between  convert(date,'".($_REQUEST['DateD'])."',105) and convert(date,'".($_REQUEST['DateFin'])."',105) ";
			}
		}
		else
		{
		$where="  cast(fa.date AS date) between '".date('01/m/Y')."' and convert(date,'".date('t/m/Y')."',105)";
		}
//echo $where;
$query_get_cities = "select  v.Designation as city ,sum(dtf.ttc) as 
	 total  from  detailFactures dtf
	 inner join factures fa on dtf.idFacture =
	  fa.IdFacture and EtatCmd = 2 
	 inner join depots dpt on dpt.idDepot = fa.idDepot  and dpt.idDepot <> 1 
	 inner join villes v on v.idville = dpt.IdVille
	  where $where group by v.Designation order by total desc";
$params0 = array();
//echo $query_get_cities."<br>";
$options0 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt0=sqlsrv_query($conn,$query_get_cities,$params0,$options0);
$ntRes0 = sqlsrv_num_rows($stmt0);
if($ntRes0==0)
		{ ?>
					
						<br><br>
						<div class="alert alert-danger col-md-12" role="alert">Aucun résultat à Afficher
						</div>
						<?php for($i = 1;$i < 20;$i++) {?>
						<br>
						<?php } ?>
				
					<?php
		}
else{
while($row = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){	



$query_marge = "select  sum(dtf.qte) as s_qte,dtf.UniteVente,dtf.idArticle from detailFactures dtf inner join factures fa on dtf.idFacture = fa.IdFacture
 and EtatCmd = 2 inner join depots dpt on dpt.idDepot = fa.idDepot and dpt.idDepot <> 1 
  inner join villes v on v.idville = dpt.IdVille  where $where and v.Designation = '$row[city]'
   group by dtf.UniteVente,dtf.idArticle ";

//echo $query_marge.'<br>';
$params_marge = array();
$options_marge =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marge=sqlsrv_query($conn,$query_marge,$params_marge,$options_marge);
$ntRes_marge = sqlsrv_num_rows($stmt_marge);
 
// echo $query_marge;
$total_under_marge = 0;
$les_marque = [];
$les_marques_titles = [];
while($reader_marge = sqlsrv_fetch_array($stmt_marge, SQLSRV_FETCH_ASSOC)){

	$query_marge_marque = "select m.Designation from articles a  inner join gammes g
	on g.IdGamme = a.idFamille inner join marques
	m on m.idMarque = g.IdMarque where a.IdArticle = $reader_marge[idArticle]";
	$marque_title = "";
	$params_query_marge_marque = array();
	$options_query_marge_marque =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt_query_marge_marque=sqlsrv_query($conn,$query_marge_marque,$params_query_marge_marque,$options_query_marge_marque);
	$ntRes_query_marge_marque = sqlsrv_num_rows($stmt_query_marge_marque);
	while($reader_query_marge_marque = sqlsrv_fetch_array($stmt_query_marge_marque, SQLSRV_FETCH_ASSOC)){
		$marque_title =  $reader_query_marge_marque['Designation'];
		if (!in_array($marque_title, $les_marques_titles)){
			$les_marque[$marque_title] = 0;
		}
	}
}

$stmt_marge2=sqlsrv_query($conn,$query_marge,$params_marge,$options_marge);
$ntRes_marge = sqlsrv_num_rows($stmt_marge2);
while($reader_marge = sqlsrv_fetch_array($stmt_marge2, SQLSRV_FETCH_ASSOC)){
	$query_pa = "select a.PA,c.colisagee from articles a inner join colisages c
	on c.idArticle = a.IdArticle where a.IdArticle = $reader_marge[idArticle]";

	
	$query_marge_marque = "select m.Designation from articles a  inner join gammes g
	on g.IdGamme = a.idFamille inner join marques
	m on m.idMarque = g.IdMarque where a.IdArticle = $reader_marge[idArticle]";
	$marque_title = "";
	$params_query_marge_marque = array();
	$options_query_marge_marque =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt_query_marge_marque=sqlsrv_query($conn,$query_marge_marque,$params_query_marge_marque,$options_query_marge_marque);
	$ntRes_query_marge_marque = sqlsrv_num_rows($stmt_query_marge_marque);
	while($reader_query_marge_marque = sqlsrv_fetch_array($stmt_query_marge_marque, SQLSRV_FETCH_ASSOC)){
		$marque_title =  $reader_query_marge_marque['Designation'];
		
	}
	
	//echo $query_pa;
	
	$params_query_pa = array();
	$options_query_pa =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt_query_pa=sqlsrv_query($conn,$query_pa,$params_query_pa,$options_query_pa);
	$ntRes_query_pa = sqlsrv_num_rows($stmt_query_pa);

	while($reader_query_pa = sqlsrv_fetch_array($stmt_query_pa, SQLSRV_FETCH_ASSOC)){
		if($reader_marge['UniteVente'] == "Colisage"){
			$a = $reader_query_pa['PA'];
			$b =  $reader_query_pa['colisagee'];
			$c = $reader_marge['s_qte'];
			$total_under_marge += $reader_query_pa['PA'] * $reader_query_pa['colisagee'] *
			$reader_marge['s_qte'];
			$old_value = 0;
			foreach($les_marque as $k => $id){
			    //echo $k."=>".$id;
			    if($k==$marque_title){
			    	$old_value = $id;
			    }
			}
			$les_marque[$marque_title] =  $reader_query_pa['PA'] * $reader_query_pa['colisagee'] *
			$reader_marge['s_qte']  + $old_value;
			$test =  $a * $b * $c ;
			//echo "<br>".$les_marque[$marque_title]." |$old_value | $marque_title  | $total_under_marge | $test<br>";
		}else{
			$total_under_marge += $reader_query_pa['PA']  * $reader_marge['s_qte'];
			//echo "<br>".$les_marque[$marque_title]."<br>";
			$les_marque[$marque_title] +=  $reader_query_pa['PA'] * $reader_marge['s_qte'];
			//echo "<br>".$les_marque[$marque_title]." | $marque_title  | $total_under_marge<br>";
		}
	}


}
//print_r($les_marque);
//echo $total_under_marge;
//echo $row['city']."<br>";


$query_marque = "select m.Designation as mar,m.idMarque,sum(dtf.ttc)
as total  from detailFactures dtf 
inner join factures fa on fa.IdFacture = dtf.idFacture and EtatCmd = 2 
inner join depots dpt on dpt.idDepot = dtf.idDepot and dpt.idDepot <> 1 
inner join villes v on v.idville = dpt.IdVille 
inner join articles a on dtf.idArticle = a.IdArticle 
inner join gammes g on a.IdFamille = g.IdGamme 
inner join marques m on g.IdMarque = m.idMarque 
where  $where and v.Designation = '$row[city]' 
group by  m.Designation,m.idMarque order by total desc";
//echo $query_marque."<br>" ;
$params_2 = array();
$options_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marque=sqlsrv_query($conn,$query_marque,$params_2,$options_2);
$ntRes_marques = sqlsrv_num_rows($stmt_marque);
?> 
<div class="result_one_ville">
	

<div class="jumbotron calc_result">
	<p class="text-center" style="padding: 4px;font-size: 50px;
	color:#3498db;text-transform: capitalize;">
		<span class="city_total" style="display: none;"><?php echo $row['total']; ?></span>
		<span  class="marge_under_total" style="display: none;"><?php echo $total_under_marge; ?></span>
		<?php 
		$y_total = number_format($row['total'], 2, ',', ' '); 
		?>
		<span class="city_name"><?php echo $row['city']; ?></span> | 
		<span><?php echo $y_total; ?></span>
			
		DH TTC
	</p>
	<div id="les_marques_marge" style="display: none;">
	<?php
			 foreach($les_marque as $k => $id){
			?>
			<div class="marque_marge_one">
				<div class="marque_name"><?php echo $k; ?></div>
				<div class="marque_marge"><?php echo $id; ?></div>
			</div>			  	
			<?php
			}
			?>
</div>
</div>

<?php 
?>

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
		<div class="col-md-6 text-left marque_info"  >
			<strong>
			    <span class="get_marque"><?php echo $reader_marque['mar']; ?></span>	
			    <span class="get_total_marque" style="display: none;"><?php echo $reader_marque['total']; ?>
			    	
			    </span>

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
?>
</div>
<?php
}
}
?>
<script type="text/javascript">
		$( document ).ready(function() {
		  $( ".see_more_class" ).click(function() {
		    $(this).parent().parent().next().toggle();
		  });
		});

		</script>
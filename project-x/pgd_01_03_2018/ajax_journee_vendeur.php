<?php 
require_once('connexion.php');
include("lang.php");
$date_j = date('Y-m-01');
$second_date = date('Y-m-t');
$query_get_ca_id_v_nbr_clients = "";
$input_depot = "";
if(isset($_REQUEST['DateJ']) && $_REQUEST['DateJ'] != "" && isset($_REQUEST['second_date'])
 && $_REQUEST['second_date'] != ""){
	$date_j = str_replace("/","-", $_REQUEST['DateJ']);
	$second_date = str_replace("/","-", $_REQUEST['second_date']);
	//echo $date_j;
	//echo $second_date;
	$date_j = date("Y-m-d", strtotime($date_j));
	$second_date = date("Y-m-d", strtotime($second_date));
	$input_depot = $_REQUEST['input_depot'];
	if($input_depot == "tous")
		$input_depot = "";
	else
		$input_depot = " and f.idDepot = $input_depot ";
	
	
	
}	
$query_get_ca_id_v_nbr_clients = "select v.idVendeur as id ,f.date,sum(dtf.ttc) 
as CA,count(distinct f.idClient) as NBRclients 
 from detailFactures dtf inner join factures f 
on dtf.idFacture = f.IdFacture  and EtatCmd = 2 
inner join vendeurs v on v.idVendeur = f.idVendeur and f.idDepot <> 1 where 
cast(f.date as date) between '".$date_j."' and '".$second_date."' $input_depot group by v.idVendeur,f.date";




$params0 = array();
echo "<br>".$query_get_ca_id_v_nbr_clients."<br>";
//echo $query_get_cities;
$options0 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt0=sqlsrv_query($conn,$query_get_ca_id_v_nbr_clients,$params0,$options0);
$ntRes0 = sqlsrv_num_rows($stmt0);
if($ntRes0==0)
		{ ?>
					
						<br><br>
						<div class="alert alert-danger" role="alert">
						  Aucun résultat à Afficher
						</div>
						<?php for($i=1;$i<20;$i++) {?>
						<br>
						<?php } ?>
				
					<?php
		}
else{
while($row = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){	
//echo $query_get_ca_id_v_nbr_clients;

//echo $row['city']."<br>";

$query_site = "select distinct v.Designation as city 
 from detailFactures dtf inner join 
factures f on dtf.idFacture = f.IdFacture inner join 
depots dpt on dpt.idDepot = f.idDepot inner join villes v 
on v.idville = dpt.IdVille where cast(f.date as date) between '".$date_j."' and '".$second_date."'
  and f.idVendeur = $row[id] and  f.idDepot <> 1 and EtatCmd = 2 $input_depot";

// echo $query_site;
 $params_site = array();
$options_site =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_site =sqlsrv_query($conn,$query_site,$params_site,$options_site);

 //echo $query_site;
$query_vendeur = " select v.nom +' '+v.prenom as vendeur from vendeurs v
 where v.idVendeur = $row[id]";
//echo $query_marque ;
$params_2 = array();
$options_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marque=sqlsrv_query($conn,$query_vendeur,$params_2,$options_2);
$ntRes_marques = sqlsrv_num_rows($stmt_marque);
//echo $query_vendeur;
while($reader_vendeur = sqlsrv_fetch_array($stmt_marque, SQLSRV_FETCH_ASSOC)){	
//echo $query_vendeur;
?> 
<div id="my_print_div">
<div style="border: 5px solid black;margin-top: 10px;padding: 15px;">
<div class="row">
<div class="col-6">
<p style="font-size: 23px;margin:10px;">
	<?php 
	$timestamp = strtotime($row['date']);

	$date_f_x = date("d/m/Y", $timestamp);
	?>
	Date : <?php echo $date_f_x." | Heure : ".date('G:i'); ?>
</p>
<?php
while($reader_site = sqlsrv_fetch_array($stmt_site, SQLSRV_FETCH_ASSOC)){	
?>

	


</div> 
<div class="col-6">
<p class="text-right" style="font-size: 23px;margin:10px;">
	Site : <?php echo ucwords($reader_site['city']); ?>
</p>
<?php
}?>
</div>
</div>
<table class="table table-striped" style="font-size: 20px;">
		<thead>
			<tr>
				<th>Vendeur : </th>
				<th colspan="2"><?php  echo $reader_vendeur['vendeur'];  ?></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>CA : </td>
			<td colspan="2">
			<?php 
			$y_total = number_format($row['CA'], 2, ',', ' '); 
			echo $y_total ; ?> DH TTC
			</td>
		</tr>
	
<!--</div>-->
<?php 

$sql_espece ="select isnull(sum(f.Espece),0) as espece from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) between '".$date_j."' and '".$second_date."' and EtatCmd = 2 and  f.idDepot <> 1 $input_depot";


//echo $sql_espece."<br>";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt=sqlsrv_query($conn,$sql_espece,$params,$options);
$ntRes = sqlsrv_num_rows($stmt);


$sql_Cheque ="select isnull(sum(f.Cheque),0) as cheque from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) between '".$date_j."' 
and '".$second_date."'  and  f.idDepot <> 1 and EtatCmd = 2 $input_depot";


//echo $sql_Cheque."<br>";
$params_Cheque = array();
$options_Cheque =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Cheque=sqlsrv_query($conn,$sql_Cheque,$params_Cheque,$options_Cheque);
$ntRes_Cheque = sqlsrv_num_rows($stmt_Cheque);


$sql_credit = "select isnull(sum(f.Credit),0) as credit from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) between '".$date_j."' and '".$second_date."'
   and  f.idDepot <> 1 and EtatCmd = 2 $input_depot";


 $params_Credit = array();
$options_Credit =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Credit=sqlsrv_query($conn,$sql_credit,$params_Credit,$options_Credit);
$ntRes_Credit = sqlsrv_num_rows($stmt_Credit);
//echo $sql_credit."<br>";


$sql_fact ="select count(f.IdFacture) as nbr_f from factures f
where f.idVendeur = $row[id] and cast(f.date as date) between '".$date_j."' and '".$second_date."' 
  and  f.idDepot <> 1 and EtatCmd = 2 $input_depot";

$params_fact = array();
$options_fact =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_fact =sqlsrv_query($conn,$sql_fact,$params_fact,$options_fact);
$ntRes_fact  = sqlsrv_num_rows($stmt_fact);
//echo $sql_fact;

$sql_marque = "select m.Designation as mar,
sum(dtf.ttc) as total ,
COUNT(a.IdArticle) as nbr_ref from detailFactures dtf  inner join
  articles a on dtf.idArticle = a.IdArticle inner join 
  gammes g on a.IdFamille = g.IdGamme inner join
   marques m on g.IdMarque = m.idMarque inner join
   factures f on f.IdFacture = dtf.idFacture
 where f.idVendeur = $row[id]  and  f.idDepot <> 1 and EtatCmd = 2  $input_depot and 
 cast(f.date as date) between '".$date_j."' and '".$second_date."'
  group by m.Designation";

//echo $sql_marque;
$params_marq = array();
$options_marq =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marq =sqlsrv_query($conn,$sql_marque,$params_marq,$options_marq);
$ntRes_marq = sqlsrv_num_rows($stmt_marq);
?>
	  

         	
<tr>   		

<?php
while($reader = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){	
	
?> 			
        
        <td>Espece : <?php echo $reader['espece']; ?></td>
<?php
} 
?>
<?php
while($reader_c = sqlsrv_fetch_array($stmt_Cheque, SQLSRV_FETCH_ASSOC)){	
	
?> 			
     
       <td>Cheque : <?php echo $reader_c['cheque']; ?></td>
<?php
} 
?>
<?php
while($reader_d = sqlsrv_fetch_array($stmt_Credit, SQLSRV_FETCH_ASSOC)){	
?> 	
        <td>Credit : <?php echo $reader_d['credit']; ?></td>
<?php
} 
?>
</tr><tr>
<?php
while($reader_f = sqlsrv_fetch_array($stmt_fact, SQLSRV_FETCH_ASSOC)){	
	
?> 		
		
        <td colspan="2">
        	<strong>Nombre de factures : </strong>
        	<?php echo $reader_f['nbr_f']; ?></td> 		
         
<?php
} 
?>
			<td>Nombre de clients : 
			 <?php  echo $row['NBRclients'];  ?>	</td> 
		</tr>
	    </tbody>
</table>

      <table class="table table-striped" style="font-size: 19px;">
      	<tr>
      		<th>Marque</th>
      		<th>Nbr de Ref</th>
      		<th class="text-left">CA ( DH TTC )</th>
      	</tr>
<?php
while($reader_marq = sqlsrv_fetch_array($stmt_marq, SQLSRV_FETCH_ASSOC)){	
	
?> 		
			<tr>
				<td><?php  echo $reader_marq['mar'];  ?></td>
				<td><?php  echo $reader_marq['nbr_ref'];  ?></td>
				<td class="text-left"><?php   
					$t = number_format($reader_marq['total'], 2, ',', ' '); 
					echo $t;
					?>
				</td>
				
			</tr>
<?php
} 
?>	
	  </table>
</div>

<?php
}
}
}
?>
</div>
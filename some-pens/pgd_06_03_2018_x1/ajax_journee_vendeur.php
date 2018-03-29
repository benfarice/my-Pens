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
$query_get_ca_id_v_nbr_clients = "select v.idVendeur as id ,sum(dtf.ttc) 
as CA,count(distinct f.idClient) as NBRclients 
 from detailFactures dtf inner join factures f 
on dtf.idFacture = f.IdFacture  and EtatCmd = 2 
inner join vendeurs v on v.idVendeur = f.idVendeur and f.idDepot <> 1 where 
cast(f.date as date) between '".$date_j."' and '".$second_date."' $input_depot group by v.idVendeur
 order by CA desc";




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
<div style="border: 5px solid black;margin-top: 10px;padding: 15px;" class="one_data_vendeur">
<div class="row">
<div class="col-6">
<p style="font-size: 23px;margin:10px;">
	<?php 
	
	?>
	Date De <?php echo $_REQUEST['DateJ']." à ".$_REQUEST['second_date']; ?>
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
				<th colspan="2" class="vendeur_name"><?php  echo $reader_vendeur['vendeur'];  ?></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>CA : </td>
			<span class="vendeur_ca" style="display: none;"><?php echo $row['CA']; ?></span>
			<td colspan="2">
			<?php 
			$y_total = number_format($row['CA'], 2, ',', ' '); 

			echo $y_total ; ?> DH TTC
			</td>
		</tr>
	
<!--</div>-->
<?php 

$sql_espece ="select isnull(sum(f.Espece),0) as espece from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) 
between '".$date_j."' and '".$second_date."' and EtatCmd = 2 and  f.idDepot <> 1 $input_depot";


echo $sql_espece."<br>";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt=sqlsrv_query($conn,$sql_espece,$params,$options);
$ntRes = sqlsrv_num_rows($stmt);


$sql_Cheque ="select isnull(sum(f.Cheque),0) as cheque from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) 
between '".$date_j."' and '".$second_date."'  and  f.idDepot <> 1 and EtatCmd = 2 $input_depot";


echo $sql_Cheque."<br>";
$params_Cheque = array();
$options_Cheque =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Cheque=sqlsrv_query($conn,$sql_Cheque,$params_Cheque,$options_Cheque);
$ntRes_Cheque = sqlsrv_num_rows($stmt_Cheque);


$sql_credit = "select isnull(sum(f.Credit),0) as credit from  factures f
where f.idVendeur = $row[id] and cast(f.date as date)
 between '".$date_j."' and '".$second_date."' and  f.idDepot <> 1 and EtatCmd = 2 $input_depot";


 $params_Credit = array();
$options_Credit =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Credit=sqlsrv_query($conn,$sql_credit,$params_Credit,$options_Credit);
$ntRes_Credit = sqlsrv_num_rows($stmt_Credit);
echo $sql_credit."<br>";


$sql_fact ="select count(f.IdFacture) as nbr_f from factures f
where f.idVendeur = $row[id] and cast(f.date as date) 
between '".$date_j."' and '".$second_date."' and  f.idDepot <> 1 and EtatCmd = 2 $input_depot";

$params_fact = array();
$options_fact =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_fact =sqlsrv_query($conn,$sql_fact,$params_fact,$options_fact);
$ntRes_fact  = sqlsrv_num_rows($stmt_fact);
//echo $sql_fact;

$sql_marque = "select m.Designation as mar,m.idMarque,
sum(dtf.ttc) as total ,
COUNT(a.IdArticle) as nbr_ref from detailFactures dtf  inner join
  articles a on dtf.idArticle = a.IdArticle inner join 
  gammes g on a.IdFamille = g.IdGamme inner join
   marques m on g.IdMarque = m.idMarque inner join
   factures f on f.IdFacture = dtf.idFacture
 where f.idVendeur = $row[id]  and  f.idDepot <> 1 and EtatCmd = 2  $input_depot and 
 cast(f.date as date) 
 between '".$date_j."' and '".$second_date."' group by m.Designation,m.idMarque order by total desc";

//echo $sql_marque;
$params_marq = array();
$options_marq =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marq =sqlsrv_query($conn,$sql_marque,$params_marq,$options_marq);
$ntRes_marq = sqlsrv_num_rows($stmt_marq);


$query_gros = "select isnull(sum(dtf.ttc),0) as CA from detailFactures dtf 
inner join factures f on dtf.idFacture = f.IdFacture and EtatCmd = 2 
inner join vendeurs v on v.idVendeur = f.idVendeur and f.idDepot <> 1 
where cast(f.date as date) between '".$date_j."' and '".$second_date."' $input_depot
and f.idVendeur = $row[id] and f.TypeVente = 1 order by CA desc";

$params_gros = array();
$options_gros =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_gros =sqlsrv_query($conn,$query_gros,$params_gros,$options_gros);
$ntRes_gros = sqlsrv_num_rows($stmt_gros);


$query_details = "select isnull(sum(dtf.ttc),0) as CA from detailFactures dtf 
inner join factures f on dtf.idFacture = f.IdFacture and EtatCmd = 2 
inner join vendeurs v on v.idVendeur = f.idVendeur and f.idDepot <> 1 
where cast(f.date as date) between '".$date_j."' and '".$second_date."' $input_depot
and f.idVendeur = $row[id] and f.TypeVente = 2 order by CA desc";

$params_details = array();
$options_details =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_details=sqlsrv_query($conn,$query_details,$params_details,$options_details);
$ntRes_details = sqlsrv_num_rows($stmt_details);



$query_client_details_factures = "select isnull(sum(dtf.ttc),0) as CA
 ,cl.intitule as [client],f.heure,f.TypeVente
 from detailFactures dtf 
inner join factures f on dtf.idFacture = f.IdFacture and EtatCmd = 2 
inner join vendeurs v on v.idVendeur = f.idVendeur and f.idDepot <> 1 
inner join clients cl on cl.IdClient = f.idClient
where cast(f.date as date) between '".$date_j."' and '".$second_date."' 
and f.idVendeur = $row[id] group by cl.intitule,f.heure,f.TypeVente order by CA desc";

$params_client_details_factures = array();
$options_client_details_factures =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_client_details_factures=sqlsrv_query($conn,$query_client_details_factures,$params_client_details_factures,$options_client_details_factures);
$ntRes_client_details_factures = sqlsrv_num_rows($stmt_client_details_factures);
?>
	  

         	
<tr>   		

<?php
while($reader = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){	
	
?> 			
        
        <td>Espece : <?php 
        $espece = $reader['espece'];
        $espece = number_format($espece, 2, ',', ' ');
        echo $espece; ?></td>
<?php
} 
?>
<?php
while($reader_c = sqlsrv_fetch_array($stmt_Cheque, SQLSRV_FETCH_ASSOC)){	
	
?> 			
     
       <td>Cheque : <?php 
       $cheque = $reader_c['cheque'];
       $cheque = number_format($cheque, 2, ',', ' ');
       echo $cheque; ?></td>
<?php
} 
?>
<?php
while($reader_d = sqlsrv_fetch_array($stmt_Credit, SQLSRV_FETCH_ASSOC)){	
?> 	
        <td>Credit : <?php 
        $credit = $reader_d['credit'];
        $credit = number_format($credit, 2, ',', ' ');
        echo  $credit; ?></td>
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
      		<th>Total Gros</th>
      		<th>Total Détail</th>
      	</tr>
<?php
while($reader_marq = sqlsrv_fetch_array($stmt_marq, SQLSRV_FETCH_ASSOC)){	
	


$query_gros_marque="select isnull(sum(dtf.ttc),0) as total from detailFactures dtf inner join articles a 
on dtf.idArticle = a.IdArticle inner join gammes g on a.IdFamille = g.IdGamme 
inner join marques m on g.IdMarque = m.idMarque inner join factures f
 on f.IdFacture = dtf.idFacture where f.idVendeur = $row[id] and 
 f.idDepot <> 1 and EtatCmd = 2 and cast(f.date as date) between 
'".$date_j."' and '".$second_date."' and m.idMarque = ".$reader_marq['idMarque']." and f.TypeVente = 1 
 order by total desc";

$params__gros_marque = array();
$options__gros_marque =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt__gros_marque=sqlsrv_query($conn,$query_gros_marque,$params__gros_marque,$options__gros_marque);
$ntRes__gros_marque = sqlsrv_num_rows($stmt__gros_marque);	

$query_detail_marque="select isnull(sum(dtf.ttc),0) as total from detailFactures dtf inner join articles a 
on dtf.idArticle = a.IdArticle inner join gammes g on a.IdFamille = g.IdGamme 
inner join marques m on g.IdMarque = m.idMarque inner join factures f
 on f.IdFacture = dtf.idFacture where f.idVendeur = $row[id] and 
 f.idDepot <> 1 and EtatCmd = 2 and cast(f.date as date) between 
'".$date_j."' and '".$second_date."' and m.idMarque = ".$reader_marq['idMarque']." and f.TypeVente = 2
 order by total desc";

$params_detail_marque = array();
$options_detail_marque =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt__detail_marque=sqlsrv_query($conn,$query_detail_marque,$params_detail_marque,$options_detail_marque);
$ntRes_detail_marque = sqlsrv_num_rows($stmt__detail_marque);	


?> 	
			<tr class="marque_info">
				<td class="get_marque"><?php  echo $reader_marq['mar'];  ?></td>
				<td><?php  echo $reader_marq['nbr_ref'];  ?></td>
				<td class="get_total_marque" style="display: none;">
					<?php echo $reader_marq['total']; ?>
					
				</td>
				<td class="text-left"><?php   
					$t = number_format($reader_marq['total'], 2, ',', ' '); 
					echo $t;
					?>
				</td>
				<?php
				while($reader_gros_marque = sqlsrv_fetch_array($stmt__gros_marque, SQLSRV_FETCH_ASSOC)){		
				?> 
				<td><?php 
				$tgm = $reader_gros_marque['total'];
				if($tgm <= 0)
					$tgm = 0;
				$tgm = number_format($tgm, 2, ',', ' ');
				echo $tgm; ?></td>
				<?php } ?>
				<?php
				while($reader_detail_marque = sqlsrv_fetch_array($stmt__detail_marque, SQLSRV_FETCH_ASSOC)){		
				?> 
				<td><?php 
				$tgm2 = $reader_detail_marque['total'];
				if($tgm2 <= 0)
					$tgm2 = 0;
				$tgm2 = number_format($tgm2, 2, ',', ' ');
				echo $tgm2; ?></td>
				<?php } ?>
			</tr>
<?php
} 
?>	
	  </table>
<?php

//********************************************************************************************
while($reader_gros = sqlsrv_fetch_array($stmt_gros, SQLSRV_FETCH_ASSOC)){	
	
?> 
<div style="background:#2c3e50;color:#ecf0f1;padding: 15px;font-size:29px;border: 1px solid white" class="text-center">
	<?php $ca =  $reader_gros['CA'] ;
	if($ca <= 0) $ca = 0;
	$ca = number_format($ca, 2, ',', ' ');
	?>
	Facturation Tarif Gros <?php echo $ca; ?> DH
</div>
<?php
} 
	  
while($reader_details = sqlsrv_fetch_array($stmt_details, SQLSRV_FETCH_ASSOC)){	
	
?> 
<div style="background:#2c3e50;color:#ecf0f1;padding: 15px;font-size:29px;border: 1px solid white;" class="text-center">
	<?php $ca2 =  $reader_details['CA'] ;
	if($ca2 <= 0) $ca2 = 0;
	$ca2 = number_format($ca2, 2, ',', ' ');
	?>
	Facturation Tarif Détail <?php echo $ca2; ?> DH
</div>
<?php
} 

//stmt_client_details_factures

?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Client</th>
      <th scope="col">Heure</th>
      <th scope="col">Type</th>
      <th scope="col">Total</th>
    </tr>
  </thead>
  <tbody>
  	<?php
  	while($reader_client_details_factures = sqlsrv_fetch_array($stmt_client_details_factures, SQLSRV_FETCH_ASSOC)){	
			
		?> 
		   <tr>
		      <th scope="row"><?php echo $reader_client_details_factures['client']; ?></th>
		      <td><?php echo $reader_client_details_factures['heure']; ?></td>
		      <td><?php 
		      $t = $reader_client_details_factures['TypeVente'];
		      $t_x = "";
		      if($t==1)
		      	$t_x = "Gros";
		      else if($t==2)
		      	$t_x="Détail";
		      echo $t_x; ?></td>
		      <td><?php 
		      $ca = $reader_client_details_factures['CA'];
		      $ca = number_format($ca, 2, ',', ' ');;
		      echo  $ca; ?></td>
		    </tr>
		<?php
		} 

		?>	
  </tbody>
</table>	  
</div>

<?php
}
}
}
//facturation tarif gros *************************************************************************
//facturation tarif détail 
?>
</div>

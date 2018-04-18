<?php 
require_once('connexion.php'); 
include("lang.php");

$_REQUEST['second_date'] = substr($_REQUEST['second_date'], 0, 10); 
$_REQUEST['first_date'] = substr($_REQUEST['first_date'], 0, 10); 




$newDateString1_print = date_format(date_create_from_format('m/d/Y', $_REQUEST['first_date']), 'd/m/Y');
$newDateString2_print = date_format(date_create_from_format('m/d/Y', $_REQUEST['second_date']), 'd/m/Y');
?>
<input type="hidden" id="date_debut" value="<?php echo $newDateString1_print;  ?>" name="">
<input type="hidden" id="date_fin" value="<?php echo $newDateString2_print;  ?>" name="">
<?php

//echo $_REQUEST['first_date']."<hr>";
//echo $_REQUEST['second_date']."<hr>";

$newDateString1 = date_format(date_create_from_format('d/m/Y', $_REQUEST['first_date']), 'Y-m-d');
$newDateString2 = date_format(date_create_from_format('d/m/Y', $_REQUEST['second_date']), 'Y-m-d');

//echo $newDateString1."<hr>";
//echo $newDateString2."<hr>";

$query_get_infos = "select f.Bon,f.DateOperation,f.Details
,f.EntreAutoroute,f.FiltreAir,f.FiltreCarburant,
f.FiltreHuile,f.idDepot,f.IdFrais,f.idVehicule,f.IdVendeur,
f.Km,f.latitude,f.longitude,f.Montant,f.Operation,
f.SortieAutoroute,f.TypeFiltre,f.TypeVidange from frais f where f.DateOperation between
'".$newDateString1."' and '".$newDateString2."'";

if($_REQUEST['inputdepot'] != "tous" && $_REQUEST['inputvendeur'] != "tous" && $_REQUEST['inputdepot'] != 
	"" && $_REQUEST['inputvendeur'] != "")
$query_get_infos = "select f.Bon,f.DateOperation,f.Details
,f.EntreAutoroute,f.FiltreAir,f.FiltreCarburant,
f.FiltreHuile,f.idDepot,f.IdFrais,f.idVehicule,f.IdVendeur,
f.Km,f.latitude,f.longitude,f.Montant,f.Operation,
f.SortieAutoroute,f.TypeFiltre,f.TypeVidange from frais f where f.DateOperation between
'".$newDateString1."' and '".$newDateString2."' and idDepot = ".$_REQUEST['inputdepot']." and IdVendeur = ".$_REQUEST['inputvendeur'];

 if($_REQUEST['inputdepot'] == "tous" && $_REQUEST['inputvendeur'] != "tous"){
 if(isset($_REQUEST['inputvendeur']) && $_REQUEST['inputvendeur']!= "" )
$query_get_infos = "select f.Bon,f.DateOperation,f.Details
,f.EntreAutoroute,f.FiltreAir,f.FiltreCarburant,
f.FiltreHuile,f.idDepot,f.IdFrais,f.idVehicule,f.IdVendeur,
f.Km,f.latitude,f.longitude,f.Montant,f.Operation,
f.SortieAutoroute,f.TypeFiltre,f.TypeVidange from frais f where f.DateOperation between
'".$newDateString1."' and '".$newDateString2."' and IdVendeur = ".$_REQUEST['inputvendeur'];
else
$query_get_infos = "select f.Bon,f.DateOperation,f.Details
,f.EntreAutoroute,f.FiltreAir,f.FiltreCarburant,
f.FiltreHuile,f.idDepot,f.IdFrais,f.idVehicule,f.IdVendeur,
f.Km,f.latitude,f.longitude,f.Montant,f.Operation,
f.SortieAutoroute,f.TypeFiltre,f.TypeVidange from frais f where f.DateOperation between
'".$newDateString1."' and '".$newDateString2."'";
 }
 

if($_REQUEST['inputvendeur'] == "tous" && $_REQUEST['inputdepot'] != "tous" 
 && $_REQUEST['inputdepot'] != "" )
$query_get_infos = "select f.Bon,f.DateOperation,f.Details
,f.EntreAutoroute,f.FiltreAir,f.FiltreCarburant,
f.FiltreHuile,f.idDepot,f.IdFrais,f.idVehicule,f.IdVendeur,
f.Km,f.latitude,f.longitude,f.Montant,f.Operation,
f.SortieAutoroute,f.TypeFiltre,f.TypeVidange from frais f where f.DateOperation between
'".$newDateString1."' and '".$newDateString2."' and idDepot = ".$_REQUEST['inputdepot'];

if(($_REQUEST['inputdepot'] == "tous" && $_REQUEST['inputvendeur'] == "tous")||($_REQUEST['inputdepot'] == "" && $_REQUEST['inputvendeur'] == "") || ($_REQUEST['inputdepot'] == null && $_REQUEST['inputvendeur'] == null))
$query_get_infos = "select f.Bon,f.DateOperation,f.Details
,f.EntreAutoroute,f.FiltreAir,f.FiltreCarburant,
f.FiltreHuile,f.idDepot,f.IdFrais,f.idVehicule,f.IdVendeur,
f.Km,f.latitude,f.longitude,f.Montant,f.Operation,
f.SortieAutoroute,f.TypeFiltre,f.TypeVidange from frais f where f.DateOperation between
'".$newDateString1."' and '".$newDateString2."'";

$total_global = 0 ;
$query_Divers = $query_get_infos;
$query_gasoil = $query_get_infos;
$query_Autoroute = $query_get_infos;
$query_get_infos.="  and f.Operation = 'Vidange' order by f.IdVendeur";

//echo $query_get_infos;
$params0 = array();
//echo $query_get_cities."<br>";
$options0 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt0=sqlsrv_query($conn,$query_get_infos,$params0,$options0);
$ntRes0 = sqlsrv_num_rows($stmt0);

if($ntRes0>0){


?>

<table class="table" id="vidange_table" style="display: none;">
	<thead class="thead-dark">
		
		
		<th>Date</th>
		<th>Montant (DH)</th>
		<th>Kilométrage</th>
		<th>Bon</th>
		<th>Type </th>
		<th>Type filtre</th>
		
	</thead>
	<tbody>

<?php
}
$total_vidange = 0;
$id_row = 1;
$vidange_ancien_vendeur = "";
while($row = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){

$vendeur = "";
$depot = "";

$type_filter="";
$query_vendeur = "select i.nom+' '+i.prenom as vendeur from vendeurs i where i.idVendeur = $row[IdVendeur]";
$params_query_vendeur = array();
//echo $query_get_cities."<br>";
$options_query_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vendeur=sqlsrv_query($conn,$query_vendeur,$params_query_vendeur,$options_query_vendeur);
$ntRes_query_vendeur = sqlsrv_num_rows($stmt_query_vendeur);

while($row_query_vendeur = sqlsrv_fetch_array($stmt_query_vendeur, SQLSRV_FETCH_ASSOC)){
	$vendeur = $row_query_vendeur['vendeur'];
}
//echo "<br>".$query_vendeur."<br>";

/*
$query_vehicule = "select v.Designation from vehicules v where v.idVehicule = $row[idVehicule]";
if($row['idVehicule'] == null)
$query_vehicule = "select v.Designation from vehicules v where v.idVehicule = 0";
$params_query_vehicule = array();
//echo "<br>".$query_vehicule."<br>";
$options_query_vehicule =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vehicule=sqlsrv_query($conn,$query_vehicule,$params_query_vehicule,$options_query_vehicule);
$ntRes_query_vehicule = sqlsrv_num_rows($stmt_query_vehicule);

while($row_query_vehicule = sqlsrv_fetch_array($stmt_query_vehicule, SQLSRV_FETCH_ASSOC)){
	$vehicule = $row_query_vehicule['Designation'];
}
*/
$query_depots ="select d.Designation from depots d where idDepot = $row[idDepot]";
if($row['idDepot'] == null)
$query_depots ="select d.Designation from depots d where idDepot = 0";
//echo "<br>".$query_depots; 
$params_query_depots = array();
//echo $query_get_cities."<br>";
$options_query_depots =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_depots=sqlsrv_query($conn,$query_depots,$params_query_depots,$options_query_depots);
$ntRes_query_depots = sqlsrv_num_rows($stmt_query_depots);

while($row_query__depots = sqlsrv_fetch_array($stmt_query_depots, SQLSRV_FETCH_ASSOC)){
	$depot = $row_query__depots['Designation'];
}





 

if((($depot === NULL)|| ($depot === ""))) {
	$depot = "inconnu";
}



$type_filter =  $row['TypeFiltre'] ;
if((($type_filter === NULL)|| ($type_filter === ""))) {
	$type_filter = "inconnu";
}

$total_vidange += $row['Montant'] ;

$montant = $row['Montant'] ;
if((($montant === NULL)|| ($montant === ""))) {
	$montant = "inconnu";
}
$typevidange = $row['TypeVidange'];
if((($typevidange === NULL)|| ($typevidange === ""))) {
	$typevidange = "inconnu";
}
$km = $row['Km'];
if((($km === NULL)|| ($km === ""))) {
	$km = "inconnu";
}



?>
<?php 
	if($vidange_ancien_vendeur != $vendeur){
		?>
		<tr style="background-color:#1abc9c;">
			<td colspan="3" style="color:#ecf0f1;font-size: 20px;font-weight: bold;"><?php echo $vendeur;?></td>
			<td colspan="3" style="color:#ecf0f1;font-size: 20px;font-weight: bold;"><?php echo $depot; ?> </td>
		</tr>
		<?php
		$vidange_ancien_vendeur = $vendeur;
	}
	 ?>
<tr id="row_vidange<?php echo $id_row;?>">

  	<td><?php echo $row['DateOperation']->format('d/m/Y') ;?></td>
	<?php $montant = number_format($montant, 2, ',', ' '); ?>
	<td><?php echo $montant ;?></td>
	<?php $km = number_format($km, 0, ',', ' '); ?>
	<td><?php echo $km;?> </td>
	<td>
		
		<?php if(!(($row['Bon'] === NULL)|| ($row['Bon'] === "")|| ($row['Bon'] === "v"))) { ?>
		<img id="myImg" class="myImg" width="60px" height="60px" src="frontend2/<?php 
		echo $row['Bon'];?>">
		<?php }else{ ?>






		<svg width="60px" height="60px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 59 59" style="enable-background:new 0 0 59 59;" xml:space="preserve">
		<g>
			<g>
				<g>
					<rect x="1" y="4" style="fill:#ECF0F1;" width="55" height="42"/>
					<path style="fill:#545E73;" d="M57,47H0V3h57V47z M2,45h53V5H2V45z"/>
				</g>
				<g>
					<rect x="5" y="8" style="fill:#545E73;" width="47" height="34"/>
					<path style="fill:#ECF0F1;" d="M53,43H4V7h49V43z M6,41h45V9H6V41z"/>
				</g>
				<circle style="fill:#F3D55A;" cx="15" cy="16.569" r="4.569"/>
				<polygon style="fill:#11A085;" points="51,32.111 50,31 38,20 27.5,31.5 32.983,36.983 37,41 51,41 		"/>
				<polygon style="fill:#26B999;" points="6,41 37,41 32.983,36.983 22.017,26.017 6,40 		"/>
			</g>
			<g>
				<path style="fill:#3D324C;" d="M58.707,54.293l-6.797-6.797l6.483-3.241l-17.637-6.498l6.499,17.637l3.241-6.484l6.797,6.797
					C57.488,55.902,57.744,56,58,56s0.512-0.098,0.707-0.293C59.098,55.316,59.098,54.684,58.707,54.293z"/>
			</g>
		</g>

		</svg>

		<?php } ?>
	</td>
	
	<?php $typevidange = number_format($typevidange, 0, ',', ' '); ?>
    <td><?php echo $typevidange;?></td>
   
    <td><?php echo $type_filter;?> </td>
    <td></td>
</tr>
<?php
$id_row++;


}
?>
<span id="v_tot" style="display: none;"><?php echo $total_vidange; ?></span>
<?php
$total_global += $total_vidange;

$total_vidange = number_format($total_vidange, 2, ',', ' ');

if($ntRes0>0){


?>

		
	</tbody>
</table>

<p id="vidange_ajax">Total : <?php echo $total_vidange; ?>  DH</p>

<?php 
}else{
	?>
<p id="vidange_ajax">nothing to show</p>	
	<?php
}
?>
<?php 
//------------------------------------------------------------------------------
//*******************************************************************************
//---------------------------------------------------------------------------------
$query_gasoil.="  and f.Operation = 'Gasoil' order by f.IdVendeur";
//echo $query_gasoil;
//echo $query_get_infos;
$params_gasoil = array();
//echo $query_get_cities."<br>";
$options_gasoil =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_gasoil =sqlsrv_query($conn,$query_gasoil,$params_gasoil,$options_gasoil);
$ntRes_gasoil = sqlsrv_num_rows($stmt_gasoil);

if($ntRes_gasoil>0){


?>

<table class="table" id="gasoil_table" style="display: none;">
	<thead class="thead-dark">
		
		
		<th>Date</th>
		<th>Montant (DH)</th>
		<th>Kilométrage</th>
		<th>Bon</th>
	
		
	</thead>
	<tbody>

<?php
}
$total_gasoil = 0;
$id_row_gasoil = 1 ;
$gasoil_ancien_vendeur = "";
while($row = sqlsrv_fetch_array($stmt_gasoil, SQLSRV_FETCH_ASSOC)){

$vendeur = "";
$depot = "";


$query_vendeur = "select i.nom+' '+i.prenom as vendeur from vendeurs i where i.idVendeur = $row[IdVendeur]";
$params_query_vendeur = array();
//echo $query_get_cities."<br>";
$options_query_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vendeur=sqlsrv_query($conn,$query_vendeur,$params_query_vendeur,$options_query_vendeur);
$ntRes_query_vendeur = sqlsrv_num_rows($stmt_query_vendeur);

while($row_query_vendeur = sqlsrv_fetch_array($stmt_query_vendeur, SQLSRV_FETCH_ASSOC)){
	$vendeur = $row_query_vendeur['vendeur'];
}
//echo "<br>".$query_vendeur."<br>";

/*
$query_vehicule = "select v.Designation from vehicules v where v.idVehicule = $row[idVehicule]";
if($row['idVehicule'] == null)
$query_vehicule = "select v.Designation from vehicules v where v.idVehicule = 0";
$params_query_vehicule = array();
//echo "<br>".$query_vehicule."<br>";
$options_query_vehicule =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vehicule=sqlsrv_query($conn,$query_vehicule,$params_query_vehicule,$options_query_vehicule);
$ntRes_query_vehicule = sqlsrv_num_rows($stmt_query_vehicule);

while($row_query_vehicule = sqlsrv_fetch_array($stmt_query_vehicule, SQLSRV_FETCH_ASSOC)){
	$vehicule = $row_query_vehicule['Designation'];
}
*/
$query_depots ="select d.Designation from depots d where idDepot = $row[idDepot]";
if($row['idDepot'] == null)
$query_depots ="select d.Designation from depots d where idDepot = 0";
//echo "<br>".$query_depots; 
$params_query_depots = array();
//echo $query_get_cities."<br>";
$options_query_depots =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_depots=sqlsrv_query($conn,$query_depots,$params_query_depots,$options_query_depots);
$ntRes_query_depots = sqlsrv_num_rows($stmt_query_depots);

while($row_query__depots = sqlsrv_fetch_array($stmt_query_depots, SQLSRV_FETCH_ASSOC)){
	$depot = $row_query__depots['Designation'];
}





 

if((($depot === NULL)|| ($depot === ""))) {
	$depot = "inconnu";
}




$total_gasoil += $row['Montant'] ;

$montant = $row['Montant'] ;
if((($montant === NULL)|| ($montant === ""))) {
	$montant = "inconnu";
}

$km = $row['Km'];
if((($km === NULL)|| ($km === ""))) {
	$km = "inconnu";
}

?>
<?php 
	if($gasoil_ancien_vendeur != $vendeur){
		?>
		<tr style="background-color:#1abc9c;">
			<td colspan="2" style="color:#ecf0f1;font-size: 20px;font-weight: bold;"><?php echo $vendeur;?></td>
			<td colspan="2" style="color:#ecf0f1;font-size: 20px;font-weight: bold;"><?php echo $depot; ?> </td>
		</tr>
		<?php
		$gasoil_ancien_vendeur = $vendeur;
	}
	 ?>
<tr id="row_gasoil<?php echo $id_row_gasoil; ?>">

   
  	<td><?php echo $row['DateOperation']->format('d/m/Y') ;?></td>
	<?php $montant = number_format($montant, 2, ',', ' '); ?>
	<td><?php echo $montant ;?></td>
	<?php $km = number_format($km, 0, ',', ' '); ?>
	<td><?php echo $km;?> </td>
	<td>
		
		<?php if(!(($row['Bon'] === NULL)|| ($row['Bon'] === "")|| ($row['Bon'] === "v"))) { ?>
		<img id="myImg" class="myImg" width="60px" height="60px" src="frontend2/<?php 
		echo $row['Bon'];?>">
		<?php }else{ ?>






		<svg width="60px" height="60px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 59 59" style="enable-background:new 0 0 59 59;" xml:space="preserve">
		<g>
			<g>
				<g>
					<rect x="1" y="4" style="fill:#ECF0F1;" width="55" height="42"/>
					<path style="fill:#545E73;" d="M57,47H0V3h57V47z M2,45h53V5H2V45z"/>
				</g>
				<g>
					<rect x="5" y="8" style="fill:#545E73;" width="47" height="34"/>
					<path style="fill:#ECF0F1;" d="M53,43H4V7h49V43z M6,41h45V9H6V41z"/>
				</g>
				<circle style="fill:#F3D55A;" cx="15" cy="16.569" r="4.569"/>
				<polygon style="fill:#11A085;" points="51,32.111 50,31 38,20 27.5,31.5 32.983,36.983 37,41 51,41 		"/>
				<polygon style="fill:#26B999;" points="6,41 37,41 32.983,36.983 22.017,26.017 6,40 		"/>
			</g>
			<g>
				<path style="fill:#3D324C;" d="M58.707,54.293l-6.797-6.797l6.483-3.241l-17.637-6.498l6.499,17.637l3.241-6.484l6.797,6.797
					C57.488,55.902,57.744,56,58,56s0.512-0.098,0.707-0.293C59.098,55.316,59.098,54.684,58.707,54.293z"/>
			</g>
		</g>

		</svg>

		<?php } ?>
	</td>
	

</tr>
<?php
$id_row_gasoil++;


}
?>
<span id="t_gaz" style="display: none;"><?php echo $total_gasoil; ?></span>
<?php
$total_global += $total_gasoil;
$total_gasoil = number_format($total_gasoil, 2, ',', ' ');


if($ntRes_gasoil>0){


?>

		
	</tbody>
</table>

<p id="gasoil_ajax">Total : <?php echo $total_gasoil; ?> DH</p>

<?php 
}else{
	?>
<p id="gasoil_ajax">nothing to show</p>	
	<?php
}

//**************************************************************************
///************************************************************************
//***************************************************************************

$query_Autoroute.="  and f.Operation = 'Autoroute' order by f.IdVendeur";
//echo $query_gasoil;
//echo $query_get_infos;
$params_Autoroute = array();
//echo $query_get_cities."<br>";
$options_Autoroute =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Autoroute =sqlsrv_query($conn,$query_Autoroute,$params_Autoroute,$options_Autoroute);
$ntRes_Autoroute = sqlsrv_num_rows($stmt_Autoroute);

if($ntRes_Autoroute>0){


?>

<table class="table" id="autoroute_table" style="display: none;">
	<thead class="thead-dark">
		
	
		<th>Date</th>
		<th>Montant (DH)</th>
		
		<th>Bon</th>
	
		
	</thead>
	<tbody>

<?php
}
$total_Autoroute = 0;
$autoroute_ancien_vendeur = "";
$id_autoroute_row = 1;
while($row = sqlsrv_fetch_array($stmt_Autoroute, SQLSRV_FETCH_ASSOC)){

$vendeur = "";
$depot = "";


$query_vendeur = "select i.nom+' '+i.prenom as vendeur from vendeurs i where i.idVendeur = $row[IdVendeur]";
$params_query_vendeur = array();
//echo $query_get_cities."<br>";
$options_query_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vendeur=sqlsrv_query($conn,$query_vendeur,$params_query_vendeur,$options_query_vendeur);
$ntRes_query_vendeur = sqlsrv_num_rows($stmt_query_vendeur);

while($row_query_vendeur = sqlsrv_fetch_array($stmt_query_vendeur, SQLSRV_FETCH_ASSOC)){
	$vendeur = $row_query_vendeur['vendeur'];
}
//echo "<br>".$query_vendeur."<br>";

/*
$query_vehicule = "select v.Designation from vehicules v where v.idVehicule = $row[idVehicule]";
if($row['idVehicule'] == null)
$query_vehicule = "select v.Designation from vehicules v where v.idVehicule = 0";
$params_query_vehicule = array();
//echo "<br>".$query_vehicule."<br>";
$options_query_vehicule =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vehicule=sqlsrv_query($conn,$query_vehicule,$params_query_vehicule,$options_query_vehicule);
$ntRes_query_vehicule = sqlsrv_num_rows($stmt_query_vehicule);

while($row_query_vehicule = sqlsrv_fetch_array($stmt_query_vehicule, SQLSRV_FETCH_ASSOC)){
	$vehicule = $row_query_vehicule['Designation'];
}
*/
$query_depots ="select d.Designation from depots d where idDepot = $row[idDepot]";
if($row['idDepot'] == null)
$query_depots ="select d.Designation from depots d where idDepot = 0";
//echo "<br>".$query_depots; 
$params_query_depots = array();
//echo $query_get_cities."<br>";
$options_query_depots =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_depots=sqlsrv_query($conn,$query_depots,$params_query_depots,$options_query_depots);
$ntRes_query_depots = sqlsrv_num_rows($stmt_query_depots);

while($row_query__depots = sqlsrv_fetch_array($stmt_query_depots, SQLSRV_FETCH_ASSOC)){
	$depot = $row_query__depots['Designation'];
}





 

if((($depot === NULL)|| ($depot === ""))) {
	$depot = "inconnu";
}




$total_Autoroute += $row['Montant'] ;

$montant = $row['Montant'] ;
if((($montant === NULL)|| ($montant === ""))) {
	$montant = "inconnu";
}

$km = $row['Km'];
if((($km === NULL)|| ($km === ""))) {
	$km = "inconnu";
}

?>
<?php 
	if($autoroute_ancien_vendeur != $vendeur){
		?>
		<tr style="background-color:#1abc9c;">
			<td colspan="1" style="color:#ecf0f1;font-size: 20px;font-weight: bold;"><?php echo $vendeur;?></td>
			<td colspan="2" style="color:#ecf0f1;font-size: 20px;font-weight: bold;"><?php echo $depot; ?> </td>
		</tr>
		<?php
		$autoroute_ancien_vendeur = $vendeur;
	}
	 ?>
<tr id="autoroute_id_row<?php echo $id_autoroute_row ;?>">

 
  	<td><?php echo $row['DateOperation']->format('d/m/Y') ;?></td>
	<?php $montant = number_format($montant, 2, ',', ' '); ?>
	<td><?php echo $montant ;?></td>

	<td>
		
		<?php if(!(($row['Bon'] === NULL)|| ($row['Bon'] === "")|| ($row['Bon'] === "v"))) { ?>
		<img id="myImg" class="myImg" width="60px" height="60px" src="frontend2/<?php 
		echo $row['Bon'];?>">
		<?php }else{ ?>






		<svg width="60px" height="60px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 59 59" style="enable-background:new 0 0 59 59;" xml:space="preserve">
		<g>
			<g>
				<g>
					<rect x="1" y="4" style="fill:#ECF0F1;" width="55" height="42"/>
					<path style="fill:#545E73;" d="M57,47H0V3h57V47z M2,45h53V5H2V45z"/>
				</g>
				<g>
					<rect x="5" y="8" style="fill:#545E73;" width="47" height="34"/>
					<path style="fill:#ECF0F1;" d="M53,43H4V7h49V43z M6,41h45V9H6V41z"/>
				</g>
				<circle style="fill:#F3D55A;" cx="15" cy="16.569" r="4.569"/>
				<polygon style="fill:#11A085;" points="51,32.111 50,31 38,20 27.5,31.5 32.983,36.983 37,41 51,41 		"/>
				<polygon style="fill:#26B999;" points="6,41 37,41 32.983,36.983 22.017,26.017 6,40 		"/>
			</g>
			<g>
				<path style="fill:#3D324C;" d="M58.707,54.293l-6.797-6.797l6.483-3.241l-17.637-6.498l6.499,17.637l3.241-6.484l6.797,6.797
					C57.488,55.902,57.744,56,58,56s0.512-0.098,0.707-0.293C59.098,55.316,59.098,54.684,58.707,54.293z"/>
			</g>
		</g>

		</svg>

		<?php } ?>
	</td>
	

</tr>
<?php

$id_autoroute_row++;

}

?>
<span id="auto_v" style="display: none;"><?php echo $total_Autoroute; ?></span>
<?php

$total_global += $total_Autoroute;

$total_Autoroute = number_format($total_Autoroute, 2, ',', ' ');

if($ntRes_Autoroute>0){


?>

		
	</tbody>
</table>

<p id="Autoroute_ajax">Total : <?php echo $total_Autoroute; ?>  DH</p>

<?php 
}else{
	?>
<p id="Autoroute_ajax">nothing to show</p>	
	<?php
}


//**************************************************************************
///************************************************************************
//***************************************************************************

$query_Divers.="  and f.Operation = 'Divers' order by f.IdVendeur";
//echo $query_gasoil;
//echo $query_get_infos;
$params_Divers = array();
//echo $query_get_cities."<br>";
$options_Divers =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Divers =sqlsrv_query($conn,$query_Divers,$params_Divers,$options_Divers);
$ntRes_Divers = sqlsrv_num_rows($stmt_Divers);

if($ntRes_Divers>0){


?>

<table class="table" id="divers_table" style="display: none;">
	<thead class="thead-dark">
		
	
		<th>Date</th>
		<th>Montant (DH)</th>
		
		<th>Détails :</th>
	
		
	</thead>
	<tbody>

<?php
}
$total_Divers = 0;
$divers_ancien_vendeur = "";
$divers_id_row = 1;
while($row = sqlsrv_fetch_array($stmt_Divers, SQLSRV_FETCH_ASSOC)){

$vendeur = "";
$depot = "";


$query_vendeur = "select i.nom+' '+i.prenom as vendeur from vendeurs i where i.idVendeur = $row[IdVendeur]";
$params_query_vendeur = array();
//echo $query_get_cities."<br>";
$options_query_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vendeur=sqlsrv_query($conn,$query_vendeur,$params_query_vendeur,$options_query_vendeur);
$ntRes_query_vendeur = sqlsrv_num_rows($stmt_query_vendeur);

while($row_query_vendeur = sqlsrv_fetch_array($stmt_query_vendeur, SQLSRV_FETCH_ASSOC)){
	$vendeur = $row_query_vendeur['vendeur'];
}
//echo "<br>".$query_vendeur."<br>";

/*
$query_vehicule = "select v.Designation from vehicules v where v.idVehicule = $row[idVehicule]";
if($row['idVehicule'] == null)
$query_vehicule = "select v.Designation from vehicules v where v.idVehicule = 0";
$params_query_vehicule = array();
//echo "<br>".$query_vehicule."<br>";
$options_query_vehicule =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vehicule=sqlsrv_query($conn,$query_vehicule,$params_query_vehicule,$options_query_vehicule);
$ntRes_query_vehicule = sqlsrv_num_rows($stmt_query_vehicule);

while($row_query_vehicule = sqlsrv_fetch_array($stmt_query_vehicule, SQLSRV_FETCH_ASSOC)){
	$vehicule = $row_query_vehicule['Designation'];
}
*/
$query_depots ="select d.Designation from depots d where idDepot = $row[idDepot]";
if($row['idDepot'] == null)
$query_depots ="select d.Designation from depots d where idDepot = 0";
//echo "<br>".$query_depots; 
$params_query_depots = array();
//echo $query_get_cities."<br>";
$options_query_depots =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_depots=sqlsrv_query($conn,$query_depots,$params_query_depots,$options_query_depots);
$ntRes_query_depots = sqlsrv_num_rows($stmt_query_depots);

while($row_query__depots = sqlsrv_fetch_array($stmt_query_depots, SQLSRV_FETCH_ASSOC)){
	$depot = $row_query__depots['Designation'];
}





 

if((($depot === NULL)|| ($depot === ""))) {
	$depot = "inconnu";
}




$total_Divers += $row['Montant'] ;

$montant = $row['Montant'] ;
if((($montant === NULL)|| ($montant === ""))) {
	$montant = "inconnu";
}

$Details = $row['Details'];


?>
<?php 
	if($divers_ancien_vendeur != $vendeur){
		?>
		<tr style="background-color:#1abc9c;">
			<td colspan="1" style="color:#ecf0f1;font-size: 20px;font-weight: bold;"><?php echo $vendeur;?></td>
			<td colspan="2" style="color:#ecf0f1;font-size: 20px;font-weight: bold;"><?php echo $depot; ?> </td>
		</tr>
		<?php
		$divers_ancien_vendeur = $vendeur;
	}
	 ?>
<tr id="divers_id_row<?php echo $divers_id_row ;?>">

   
  	<td><?php echo $row['DateOperation']->format('d/m/Y') ;?></td>
	<?php $montant = number_format($montant, 2, ',', ' '); ?>
	<td><?php echo $montant ;?></td>

	<td><?php echo $Details ;?></td>
	

</tr>
<?php

$divers_id_row++;

}

?>
<span id="divers_t" style="display: none;"><?php echo $total_Divers; ?></span>
<?php
$total_global += $total_Divers;

$total_Divers = number_format($total_Divers, 2, ',', ' ');

if($ntRes_Divers>0){


?>

		
	</tbody>
</table>

<p id="Divers_ajax">Total : <?php echo $total_Divers; ?> DH</p>

<?php 
}else{
	?>
<p id="Divers_ajax">nothing to show</p>	
	<?php
}

if($total_global>0){
	$total_global = number_format($total_global, 2, ',', ' ');
	?>
	<p id="total_global_ajax"> <?php echo  $total_global; ?></p>
	<?php
} else{
	?>
	<p id="total_global_ajax">nothing to show</p>
	<?php
}
?>



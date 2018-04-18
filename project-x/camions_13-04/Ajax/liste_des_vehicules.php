<?php 
include "../connect.php";

$query_vehicules = "select c.id,c.designation,c.etat,c.marque,c.matricule from camions c";


if(isset($_REQUEST['searched_value'])){
	$query_vehicules = "select c.id,c.designation,c.etat,c.marque,c.matricule from camions c
where c.designation like '%".$_REQUEST['searched_value']."%' or c.matricule like '%".$_REQUEST['searched_value']."%' or c.marque like '%".$_REQUEST['searched_value']."%'";
}
$params_query_vehicules = array();
$options_query_vehicules =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query__vehicules=sqlsrv_query($con,$query_vehicules,$params_query_vehicules,$options_query_vehicules);
$ntRes_query_vehicules = sqlsrv_num_rows($stmt_query__vehicules);

while($row_query__vehicules = sqlsrv_fetch_array($stmt_query__vehicules, SQLSRV_FETCH_ASSOC)){

$color_class = null;
if($row_query__vehicules['etat']==1){
	$color_class = " vehicule_blue ";
}
if($row_query__vehicules['etat']==2){
	$color_class = " vehicule_jaune ";
}
if($row_query__vehicules['etat']==3){
	$color_class = " vehicule_vert ";
}

?>

	<div class="vehicule text-center mySlides <?php echo $color_class; ?>">
		<p class="vehicule_marque"><?php echo $row_query__vehicules['marque'] ?></p>
		<p class="id_vehicule" style="display: none;"><?php echo $row_query__vehicules['id']; ?></p>
		<p class="vehi_matricule"><?php echo $row_query__vehicules['matricule']; ?></p>
		<p class="desc_vehicule"><?php echo $row_query__vehicules['designation']; ?></p>
	</div>
	<?php



}
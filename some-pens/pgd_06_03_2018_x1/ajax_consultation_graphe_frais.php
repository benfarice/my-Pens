<?php 
require_once('connexion.php');  
include("lang.php");
$date_j = date('Y-m-01');
$second_date = date('Y-m-t');
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
		$input_depot = " and v.idDepot = $input_depot ";
}
$query_frais_vendeur = "select v.idVendeur, sum(f.Montant) as totalfrais from frais f inner join vendeurs v
on f.IdVendeur = v.idVendeur
where f.DateOperation between '".$date_j."' and '".$second_date."' 
$input_depot group by v.idVendeur order by totalfrais DESC";

//echo $query_frais_vendeur;

$params_frais_vendeur  = array();
$options_frais_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_frais_vendeur  =sqlsrv_query($conn,$query_frais_vendeur,$params_frais_vendeur,$options_frais_vendeur);
$ntRes_frais_vendeur   = sqlsrv_num_rows($stmt_frais_vendeur);

while($reader = sqlsrv_fetch_array($stmt_frais_vendeur, SQLSRV_FETCH_ASSOC)){	


$query_get_vendeur_name = "select v.nom+' '+v.prenom as v_name from vendeurs v where v.idVendeur = ".
$reader['idVendeur'];

//echo $query_get_vendeur_name;
$params__get_vendeur  = array();
$options__get_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt__get_vendeur  =sqlsrv_query($conn,$query_get_vendeur_name,$params__get_vendeur,$options__get_vendeur);
$ntRes__get_vendeur   = sqlsrv_num_rows($stmt__get_vendeur);


$query_Autoroute_vendeur = "select  isnull(sum(f.Montant),0) as totalAutoroute from frais f 
inner join vendeurs v
on f.IdVendeur = v.idVendeur
where f.DateOperation between '".$date_j."' and '".$second_date."' 
$input_depot and f.Operation = 'Autoroute' and v.idVendeur = ".$reader['idVendeur'];

//echo $query_Autoroute_vendeur;
$params__Autoroute__vendeur  = array();
$options_Autoroute_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Autoroute_vendeur  =sqlsrv_query($conn,$query_Autoroute_vendeur,$params__Autoroute__vendeur,
	$options_Autoroute_vendeur);
$ntRes__Autoroute_vendeur   = sqlsrv_num_rows($stmt_Autoroute_vendeur);

$query_Gasoil_vendeur = "select isnull(sum(f.Montant),0) as totalGasoil from frais f 
inner join vendeurs v
on f.IdVendeur = v.idVendeur
where f.DateOperation between '".$date_j."' and '".$second_date."' 
$input_depot and f.Operation = 'Gasoil'  and v.idVendeur = ".$reader['idVendeur'];

$params__Gasoil__vendeur  = array();
$options_Gasoil_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Gasoil_vendeur  =sqlsrv_query($conn,$query_Gasoil_vendeur,$params__Gasoil__vendeur,
      $options_Gasoil_vendeur);
$ntRes__Gasoil_vendeur   = sqlsrv_num_rows($stmt_Gasoil_vendeur);

$query_Divers_vendeur = "select isnull(sum(f.Montant),0) as totalDivers from frais f 
inner join vendeurs v
on f.IdVendeur = v.idVendeur
where f.DateOperation between '".$date_j."' and '".$second_date."' 
$input_depot and f.Operation = 'Divers'  and v.idVendeur = ".$reader['idVendeur'];

$params__Divers__vendeur  = array();
$options_Divers_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Divers_vendeur  =sqlsrv_query($conn,$query_Divers_vendeur,$params__Divers__vendeur,
      $options_Divers_vendeur);
$ntRes__Divers_vendeur   = sqlsrv_num_rows($stmt_Divers_vendeur);

$query_Vidange_vendeur = "select isnull(sum(f.Montant),0) as totalVidange from frais f 
inner join vendeurs v
on f.IdVendeur = v.idVendeur
where f.DateOperation between '".$date_j."' and '".$second_date."' 
$input_depot and f.Operation = 'Vidange'  and v.idVendeur = ".$reader['idVendeur'];

$params__Vidange__vendeur  = array();
$options_Vidange_vendeur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Vidange_vendeur  =sqlsrv_query($conn,$query_Vidange_vendeur,$params__Vidange__vendeur,
      $options_Vidange_vendeur);
$ntRes__Vidange_vendeur   = sqlsrv_num_rows($stmt_Vidange_vendeur);
//****************************************************************
//********************************  you stoped here 01-03-2018 ********* *********************
?> 
      <div class="vendeur_frais" style="display: none;">
       	<?php
      	while($reader_get_vendeur = sqlsrv_fetch_array($stmt__get_vendeur, SQLSRV_FETCH_ASSOC)){	
      	?>
      	<div  class="vendeur_name"><?php echo $reader_get_vendeur['v_name']; ?></div>
      	<?php 
      	}
      	 ?>
      	 <?php
      	while($reader_Autoroute_vendeur = sqlsrv_fetch_array($stmt_Autoroute_vendeur, SQLSRV_FETCH_ASSOC)){	
      	?>
      	<div  class="vendeur_Autoroute">
      	<?php 
      	echo $reader_Autoroute_vendeur['totalAutoroute']; ?></div>
      	<?php 
      	}
      	 ?>
            <?php
            while($reader_Gasoil_vendeur = sqlsrv_fetch_array($stmt_Gasoil_vendeur, SQLSRV_FETCH_ASSOC)){   
            ?>
            <div  class="vendeur_Gasoil">
            <?php 
            echo $reader_Gasoil_vendeur['totalGasoil']; ?></div>
            <?php 
            }
             ?>
              <?php
            while($reader_Divers_vendeur = sqlsrv_fetch_array($stmt_Divers_vendeur, SQLSRV_FETCH_ASSOC)){   
            ?>
            <div  class="vendeur_Divers">
            <?php 
            echo $reader_Divers_vendeur['totalDivers']; ?></div>
            <?php 
            }
             ?>
               <?php
            while($reader_Vidange_vendeur = sqlsrv_fetch_array($stmt_Vidange_vendeur, SQLSRV_FETCH_ASSOC)){   
            ?>
            <div  class="vendeur_Vidange">
            <?php 
            echo $reader_Vidange_vendeur['totalVidange']; ?></div>
            <?php 
            }
             ?>
      	<div  class="vendeur_total_frais"><?php echo $reader['totalfrais'] ?></div>
      </div>
<?php
} 


?>
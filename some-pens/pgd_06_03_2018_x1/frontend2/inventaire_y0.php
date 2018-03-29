<?php
require_once('../connexion.php');
require_once('../php.fonctions.php');
/****/
if(!isset($_SESSION))
{
	session_start();
} ;
include("lang.php");
//initialiser le type de vente direct ou prevente  
unset($_SESSION['Vente']);	
/****/
if(isset($_GET['logout'])){
	unset($_SESSION['Vendeur']);
	unset($_SESSION['IdVendeur']);
	unset($_SESSION['IdDepot']);
	?>
	<script language="javascript" > 
	window.location.href = 'login.php';
	</script>
<?php
}
if(!isset($_SESSION['IdVendeur'])){
	?>
	<script language="javascript" > 
	window.location.href = 'login.php';
	</script>
	
<?php
}
$_SESSION['numero_inventaire']="";
$_SESSION['clicked_recup']="";
$_SESSION['etat_numero']="";
$titre = "Démarrage inventaire";
$sql_check_numero ="select i.Etat,i.Numero from inventaire_table i where 
i.Depot = $_SESSION[IdDepot] and i.Superviseur = $_SESSION[IdVendeur] and i.id = (
select max(i2.id) from inventaire_table i2 where 
i2.Depot = $_SESSION[IdDepot] and i2.Superviseur = $_SESSION[IdVendeur])";
//echo $sql_check_numero;
$params_check_numero = array();
$options_check_numero =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_check_numero=sqlsrv_query($conn,$sql_check_numero,$params_check_numero,$options_check_numero);
$ntRes_check_numero = sqlsrv_num_rows($stmt_check_numero);
while($row_check_numero= sqlsrv_fetch_array($stmt_check_numero, SQLSRV_FETCH_ASSOC)){
	$_SESSION['etat_numero']=$row_check_numero['Etat'];
	$_SESSION['numero_inventaire']=$row_check_numero['Numero'];
}


if($_SESSION['etat_numero'] != 0 ){

$v1 = array();
$v2 = array();
$new_numero= "IN".Increment_Chaine_F("Numero","inventaire_table","id",$conn,
	"",$v2);
$query_insert_inventaire_t = "insert into inventaire_table
	(Heure,Date_,Depot,Superviseur,Numero)
    values('".date('H:m:s')."','".date('d/m/Y')."',$_SESSION[IdDepot],
     $_SESSION[IdVendeur],'$new_numero')";
$result_insert_inventaire_t = sqlsrv_query($conn,$query_insert_inventaire_t) or die(sqlsrv_errors());
$query_insert_inventaire_t2 = "insert into inventaire_table_temp
	(Heure,Date_,Depot,Superviseur,Numero)
    values('".date('H:m:s')."','".date('d/m/Y')."',$_SESSION[IdDepot],
     $_SESSION[IdVendeur],'$new_numero')";
$result_insert_inventaire_t2 = sqlsrv_query($conn,$query_insert_inventaire_t2) or die(sqlsrv_errors());
$select_numero_inventaire = "select t.Numero from inventaire_table t where t.id = (
select max(id) from inventaire_table)";



$params_select_numero_inventaire = array();
$options_select_numero_inventaire =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_select_numero_inventaire=sqlsrv_query($conn,$select_numero_inventaire,$params_select_numero_inventaire,$options_select_numero_inventaire);
$ntRes_select_numero_inventaire = sqlsrv_num_rows($stmt_select_numero_inventaire);
while($row_select_numero_inventaire= sqlsrv_fetch_array($stmt_select_numero_inventaire, SQLSRV_FETCH_ASSOC)){
	$_SESSION['numero_inventaire']=$row_select_numero_inventaire['Numero'];
}

}else{
$titre = "Poursuite l'inventaire";
}

$_SESSION['numero_inventaire_cloture']="";

$_SESSION['etat_numero_cloture']="";
if (isset($_GET['cloture_inventaire'])) {
	//echo "you call cloture function ";
	$sql_check_numero ="select i.Etat,i.Numero from inventaire_table i where 
	i.Depot = $_SESSION[IdDepot] and i.Superviseur = $_SESSION[IdVendeur] and i.id = (
	select max(i2.id) from inventaire_table i2 where 
	i2.Depot = $_SESSION[IdDepot] and i2.Superviseur = $_SESSION[IdVendeur])";
	//echo $sql_check_numero;
	$params_check_numero = array();
	$options_check_numero =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt_check_numero=sqlsrv_query($conn,$sql_check_numero,$params_check_numero,$options_check_numero);
	$ntRes_check_numero = sqlsrv_num_rows($stmt_check_numero);
	while($row_check_numero= sqlsrv_fetch_array($stmt_check_numero, SQLSRV_FETCH_ASSOC)){
		$_SESSION['etat_numero_cloture']=$row_check_numero['Etat'];
		$_SESSION['numero_inventaire_cloture']=$row_check_numero['Numero'];
	}
	if($_SESSION['etat_numero_cloture'] == 0 || 
		is_null($_SESSION['etat_numero_cloture'])){
		$sql_cloture_inventaire ="update inventaire_table set Etat = 2
	where Numero = '$_SESSION[numero_inventaire_cloture]' and Depot = $_SESSION[IdDepot] and Superviseur = 
	$_SESSION[IdVendeur]";
	$result_update_cloture = sqlsrv_query($conn,$sql_cloture_inventaire) or die(sqlsrv_errors());
	}
	?>
	<script type="text/javascript">
		window.location.href = 'inventaire_y.php';
	</script>
	<?php
}


require_once("header_y.php");
?>

<div class="container-fluid">
	<div class="Head row">
	<div  class="heaLeft col-6">
		<div class="Info"> 
			<a href="index.php"><img src="../images/home.png"></a>
			<?php echo $trad['index']['Bienvenu'] ;echo $_SESSION['Vendeur'];?>
		</div>
	</div>
	<div  class="headRight col-6">
		<a href="index.php?logout" class="signoutsignout" style="float: right;">
		<div class="signout">
		
		</div>
		</a>
	</div>
	</div>

	<div class="row" style="margin-top: 40px">
		<div class="col-1"></div>	
		<div class="col-4 text-center">
			<a href="demarrage_inventaire.php" target="_blank">
				<img src="../images/Commande_depot.png">
			</a>
			<br><br>
			<h3><?php echo $titre;?></h3>
		</div>
		<div class="col-2"></div>
		<div class="col-4 text-center">
			   <a href="inventaire_y.php?cloture_inventaire=true"
			   style="text-decoration: none;color: black">
			    <img src="../images/Commande_depot.png">
				
				<br><br>
				<h3>clôture inventaire</h3>
			   </a>
		</div>
		<div class="col-1"></div>	
	</div>
</div>
<?php
include("footer.php");

?>

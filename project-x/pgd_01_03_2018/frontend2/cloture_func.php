<?php
require_once('../connexion.php');
require_once('../php.fonctions.php');
/****/
if(!isset($_SESSION))
{
	session_start();
}
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
		//window.location.href = 'http://pgd.ma/v6/fronTEND2/inventaire_y.php';
		//window.location.reload(true);
		window.location.href = 'index.php';
	</script>
	<?php
}

<?php
if(!isset($_SESSION))
{ 
	session_start();
}
require_once('../connexion.php');
require_once('../php.fonctions.php');
/****/

include("lang.php");

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

if($_SESSION['etat_numero'] != 0 ){



$new_numero = 'IN2018'.$_SESSION['IdVendeur'].$_SESSION['IdDepot'].date('Hms');

$query_insert_inventaire_t = "insert into inventaire_table
	(Heure,Date_,Depot,Superviseur,Numero,Etat)
    values('".date('H:m:s')."','".date('d/m/Y')."',$_SESSION[IdDepot],
     $_SESSION[IdVendeur],'$new_numero',0)";
//echo $query_insert_inventaire_t;
$result_insert_inventaire_t = sqlsrv_query($conn,$query_insert_inventaire_t) or die(sqlsrv_errors());
$query_insert_inventaire_t2 = "insert into inventaire_table_temp
	(Heure,Date_,Depot,Superviseur,Numero)
    values('".date('H:m:s')."','".date('d/m/Y')."',$_SESSION[IdDepot],
     $_SESSION[IdVendeur],'$new_numero')";
//echo $query_insert_inventaire_t2;
$result_insert_inventaire_t2 = sqlsrv_query($conn,$query_insert_inventaire_t2) or die(sqlsrv_errors());
$_SESSION['numero_inventaire']= $new_numero;
$_SESSION['etat_numero'] = 0;
}
?>
<script type="text/javascript">
		//window.location.href = 'http://pgd.ma/v6/fronTEND2/inventaire_y.php';
		//window.location.reload(true);


		window.location.href = 'demarrage_inventaire.php';
</script>
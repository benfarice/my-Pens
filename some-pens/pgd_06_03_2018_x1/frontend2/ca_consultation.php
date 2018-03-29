<?php

require_once('../connexion.php');
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
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
<?php
$query_CA_1 = "select isnull(sum(dtf.ttc),0)
as CA 
 from detailFactures dtf inner join factures f 
on dtf.idFacture = f.IdFacture  and EtatCmd = 2
inner join vendeurs v on v.idVendeur = f.idVendeur and v.idDepot <> 1 where 
cast(f.date as date) = '".date('d/m/Y')."' and v.idVendeur = $_SESSION[IdVendeur]";
$Ca1 = 0;
//echo $query_CA_1;

$params_query_CA_1 = array();
$options_query_CA_1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_CA_1=sqlsrv_query($conn,$query_CA_1,$params_query_CA_1,$options_query_CA_1);
$ntRes_query_CA_1 = sqlsrv_num_rows($stmt_query_CA_1);
while($row__query_CA_1 = 
	sqlsrv_fetch_array($stmt_query_CA_1, SQLSRV_FETCH_ASSOC)){
	$Ca1 = $row__query_CA_1['CA'];
}
if($Ca1 <= 0)
	$Ca1 = 0;


$lastday = date('t',strtotime('today'));
$query_CA_2 = "select isnull(sum(dtf.ttc),0) as CA from detailFactures dtf inner join 
factures f on dtf.idFacture = f.IdFacture and EtatCmd = 2 inner join 
vendeurs v on v.idVendeur = f.idVendeur and v.idDepot <> 1 
where cast(f.date as date) between '".date('01/m/Y')."' and '".date("$lastday/m/Y")."' and v.idVendeur = $_SESSION[IdVendeur]	";
$Ca2 = 0;
//echo $query_CA_2;

$params_query_CA_2 = array();
$options_query_CA_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_CA_2=sqlsrv_query($conn,$query_CA_2,$params_query_CA_2,$options_query_CA_2);
$ntRes_query_CA_2 = sqlsrv_num_rows($stmt_query_CA_2);
while($row__query_CA_2 = 
	sqlsrv_fetch_array($stmt_query_CA_2, SQLSRV_FETCH_ASSOC)){
	$Ca2 = $row__query_CA_2['CA'];
}
if($Ca2 <= 0)
	$Ca2 = 0;

$query_frais = "select isnull(sum(f.Montant),0) as frais from Frais f where f.IdVendeur = $_SESSION[IdVendeur]
and f.DateOperation between '".date('01/m/Y')."' and '".date("$lastday/m/Y")."'";
$frais = 0;
//echo $query_CA_2;

$params_query_frais = array();
$options_query_frais =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_frais=sqlsrv_query($conn,$query_frais,$params_query_frais,$options_query_frais);
$ntRes_query_frais = sqlsrv_num_rows($stmt_query_frais);
while($row__query_frais = 
	sqlsrv_fetch_array($stmt_query_frais, SQLSRV_FETCH_ASSOC)){
	$frais = $row__query_frais['frais'];
}
if($frais <= 0)
	$frais = 0;




?>
	<br><br>
	<h2 class="text-center">Consultation CA</h2>
	<br>
	<table class="table">
		<tr>
				<th width="30%">CA</th>
		        <th><?php echo date('d/m/Y') ;?></th>
		</tr>
		<tr>
			    <td colspan="2"><?php echo $Ca1; ?></td>
		</tr>
	</table>
	<table class="table">
		<tr>
				<th width="30%">CA</th>
		        <th><?php setlocale(LC_TIME, "fr_FR"); echo strftime('%B') ;?></th>
		</tr>
		<tr>
			    <td colspan="2"><?php echo $Ca2; ?></td>
		</tr>
	</table>
	<table class="table">
		<tr>
				<th width="30%">Frais</th>
		        <th><?php setlocale(LC_TIME, "fr_FR"); echo strftime('%B') ;?></th>
		</tr>
		<tr>
			    <td colspan="2"><?php echo $frais; ?></td>
		</tr>
	</table>
</div>
<?php
include("footer.php");
?>
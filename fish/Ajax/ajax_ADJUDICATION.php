<?php 
session_start();
$lang = '../includes/languages/';
include_once $lang.$_SESSION['Lang'].'.php';

include "../connect.php";


$num_adjudication_to_print = "V000";
if(isset($_REQUEST['add'])){
	$query_num = "select count(ad.Num_adjudication) as nombre from ADJUDICATION ad";
	$num_adjudication = "V000";
	$i = 0;
	$params_query_adjudication = array();
	$options_query_adjudication =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_adjudication = sqlsrv_query($con,$query_num,$params_query_adjudication,
	$options_query_adjudication);
	while($reader_query_adjudication= sqlsrv_fetch_array($result_query_adjudication, SQLSRV_FETCH_ASSOC)){ 
		$i = $reader_query_adjudication['nombre'];
	}


	$num_adjudication = 'V000'.$i;
	$ntRes_select_num_adjudication2=0;
	do{
	$num_adjudication = 'V000'.$i;
	$i++;
	$query_num_adjudication_2 = "select * from ADJUDICATION where Num_adjudication = '$num_adjudication'";
	//$num_lot = 'L000000';
	//$i = 0;
	//echo $query_num_lot2;
	$params_query_num_adjudication_2 = array();
	$options_query_num_adjudication_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_num__adjudication_2 = sqlsrv_query($con,$query_num_adjudication_2,
		$params_query_num_adjudication_2,
	$options_query_num_adjudication_2);
	$ntRes_select_num_adjudication2 = sqlsrv_num_rows($result_query_num__adjudication_2);
	}while($ntRes_select_num_adjudication2>0);
//***********************************************************************
	$query_add ="insert into ADJUDICATION(Num_adjudication,Date_adjudication,Prix_net,
		Prix_unitaire,Poids_net,Code_Acheteur,num_lot)
		values
		('$num_adjudication','".date('d/m/Y')."',".$_REQUEST['prix_net'].",".$_REQUEST['prix_unitaire'].",".
		$_REQUEST['poids_buy'].",'".$_REQUEST['id_buyer']."','".$_REQUEST['lot_to_buy']."')";

	//echo $query_add;
	$num_adjudication_to_print = $num_adjudication;
	$result_add = sqlsrv_query($con,$query_add);
	$query_update_lot = "update LOT set etat = 2 where Num_lot = '".$_REQUEST['lot_to_buy']."'";
	$result_update_lot = sqlsrv_query($con,$query_update_lot);
}



if(isset($_REQUEST['imprime_buy_nfo'])){

	//$enteteFile = "BVLID".PHP_EOL;
	$Date=date_create(date("Y-m-d  H:i"));
	$enteteFile = lang('operation').$num_adjudication_to_print.PHP_EOL ;
	$enteteFile.= lang('username').$_SESSION['username'].PHP_EOL;
	//$enteteFile.="البائع : ".strtoupper($_REQUEST['seller']).PHP_EOL ;
	$enteteFile.=lang('date_and_time').date_format($Date, 'd/m/Y H:i').PHP_EOL;
	$enteteFile.=lang('lot_id')." : ".$_REQUEST['lot_to_buy'].PHP_EOL;
	$enteteFile.=lang('seller')." : ".$_REQUEST['seller_to_buy_span'].PHP_EOL;
	$enteteFile.=lang('search_buyer')." : ".$_REQUEST['buyer_to_buy_span'].PHP_EOL;
	$enteteFile.=lang('the_type')." : ".$_REQUEST['type_buy_span'].PHP_EOL;
	
	
	$enteteFile.=lang('poids')." : ".$_REQUEST['poids_buy'].PHP_EOL;
	$enteteFile.=lang('price')." : ".$_REQUEST['prix_unitaire'].PHP_EOL;
	$enteteFile.=lang('total')." : ".$_REQUEST['prix_net'].PHP_EOL;
	$footer = "------------------------------------------".PHP_EOL;
	

	$name=date('d-m-Y H-i-s');
	$fp = fopen ("../data/uploads/".$name.".txt","w+");
	$Imprime = $enteteFile.$footer;
	fputs ($fp,$Imprime);
	fclose ($fp);
	$dir= "../data/uploads/".$name.".txt";
	$filename=$name.".txt";
	$name= urlencode ($name);
 
	$link = "Ajax/download.php?fileName=".$name;
	?>
	<input type="hidden" value="<?php echo $link; ?>" name="" id="link_to_print_buy_info">
	<?php
}
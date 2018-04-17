<?php
session_start();
$lang = '../includes/languages/';
include_once $lang.$_SESSION['Lang'].'.php';

include "../connect.php";


$details_lot_txt = "";
if(isset($_REQUEST['del_lot_id'])){
	$delete_from_adjudication = "delete from adjudication where num_lot = '".$_REQUEST['del_lot_id']."'";
	$result_delete_adj = sqlsrv_query($con,$delete_from_adjudication) or die(sqlsrv_errors());

	$delete_from_details_lot = "delete from details_lot where num_lot = '".$_REQUEST['del_lot_id']."'";
	$result_delete_details = sqlsrv_query($con,$delete_from_details_lot) or die(sqlsrv_errors());

	$query_delete = "delete from LOT where Num_lot = '".$_REQUEST['del_lot_id']."'";
	//echo $query_delete;
	$result_delete = sqlsrv_query($con,$query_delete) or die(sqlsrv_errors());

}

$num_lot_to_imprime_txt = 'L000000';
$total_lot_for_print = 0;
$count_lot_for_print = 0;


if(isset($_REQUEST['new_qte'])){
	$query_update = "update LOT set Poids_net = ".$_REQUEST['new_qte']." where
	Num_lot = '".$_REQUEST['up_lot_id']."' and Code_vendeur = '".$_REQUEST['id_vendeur']."'";
	$result_update = sqlsrv_query($con,$query_update) or die(sqlsrv_errors());

}

$new_poids=0;

if(isset($_REQUEST['add_lot'])){

	$query_details_lot_check = "select num_pose,Poids_net,d.num_lot from details_lot d inner join LOT l on
	d.num_lot = l.Num_lot where Code_espece ='$_REQUEST[code_espece]' and l.etat = 1
	and l.Code_vendeur = '$_REQUEST[id_vendeur]'";
	$params_details_lot_check = array();
	$options_details_lot_check =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_details_lot_check = sqlsrv_query($con,$query_details_lot_check,$params_details_lot_check,
	$options_details_lot_check);
	$check_lot_details = sqlsrv_num_rows($result_details_lot_check);
	//echo $check_lot_details.'<hr>';
	if($check_lot_details > 0){

	$calc_poids="select isnull(sum(d.poids),0) as sum_poids from details_lot d inner join LOT l on
	d.num_lot = l.Num_lot where Code_espece ='$_REQUEST[code_espece]' and l.etat = 1
	and l.Code_vendeur = '$_REQUEST[id_vendeur]'";
	//echo $calc_poids.'<hr>';
	$params_calc_poids = array();
	$options_calc_poids =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_calc_poids = sqlsrv_query($con,$calc_poids,$params_calc_poids,
	$options_calc_poids);
	$new_poids=0;
	while($reader_calc_poids= sqlsrv_fetch_array($result_calc_poids, SQLSRV_FETCH_ASSOC)){
		$new_poids = $reader_calc_poids['sum_poids'];
	}
	//echo $new_poids;
	$num_lot_to_update = null;
	while($check_reader = sqlsrv_fetch_array($result_details_lot_check, SQLSRV_FETCH_ASSOC)){
		$num_lot_to_update = $check_reader['num_lot'];
	}
		//***********************

	$new_poids += $_REQUEST['qte'];
	$query_update_lot_exist ="update lot set Poids_net = $new_poids where Num_lot = '$num_lot_to_update'";
	$result_update_lot_exist= sqlsrv_query($con,$query_update_lot_exist);
	//echo $query_update_lot_exist.'<hr>';

	$p = $check_lot_details+1;
	$query_add_details = "insert into details_lot(num_lot,num_pose,poids)
	values('$num_lot_to_update',$p,$_REQUEST[qte])";
	$result_add_details = sqlsrv_query($con,$query_add_details);
	//echo $query_add_details.'<hr>';


	$num_lot_to_imprime_txt = $num_lot_to_update;



	}else{

	$query_num_lot = "select count(Num_lot) as nombre from LOT";
	$num_lot = 'L000000';
	$i = 0;
	$params_query_num_lot = array();
	$options_query_num_lot =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_num_lot = sqlsrv_query($con,$query_num_lot,$params_query_num_lot,
	$options_query_num_lot);
	while($reader_query_num_lot= sqlsrv_fetch_array($result_query_num_lot, SQLSRV_FETCH_ASSOC)){
		$i = $reader_query_num_lot['nombre'];
	}
	//$i+=2;

	$num_lot = 'L00'.$i;
	$ntRes_select_num_lot2=0;
	do{
	$num_lot = 'L00'.$i;
	$i++;
	$query_num_lot2 = "select *  from LOT  where Num_lot = '$num_lot'";
	//$num_lot = 'L000000';
	//$i = 0;
	//echo $query_num_lot2;
	$params_query_num_lot2 = array();
	$options_query_num_lot2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_num_lot2 = sqlsrv_query($con,$query_num_lot2,$params_query_num_lot2,
	$options_query_num_lot2);
	$ntRes_select_num_lot2 = sqlsrv_num_rows($result_query_num_lot2);
	}while($ntRes_select_num_lot2>0);

  //$Date_alpha = date_create(date("Y-m-d H:i"));

/*
	$query_add="insert into LOT(Num_lot,Date_lot,Code_espece,Heure_lot,Code_vendeur,Poids_net,etat)
	values ('$num_lot','".date('Y-m-d H:i')."','$_REQUEST[code_espece]','".date('H:i')."','$_REQUEST[id_vendeur]',$_REQUEST[qte],1)";
*/
$query_add="insert into LOT(Num_lot,Date_lot,Code_espece,Heure_lot,Code_vendeur,Poids_net,etat)
values ('$num_lot','".date('Y-m-d')."','$_REQUEST[code_espece]','".date('H:i')."','$_REQUEST[id_vendeur]',$_REQUEST[qte],1)";


	//echo $query_add.'<hr>';
	$num_lot_to_imprime_txt = $num_lot;
	$result_add = sqlsrv_query($con,$query_add);

	$query_add_details = "insert into details_lot(num_lot,num_pose,poids)
	values('$num_lot',1,$_REQUEST[qte])";
	$result_add_details = sqlsrv_query($con,$query_add_details);
	//echo $query_add_details.'<hr>';
	}




}
$query_select = "select l.Num_lot,l.Code_espece,l.Poids_net from LOT l where l.Code_vendeur = '$_REQUEST[id_vendeur]' and etat = 1";

 if(isset($_REQUEST['report_seller'])){
 	$query_select = "select l.Num_lot,l.Code_espece
	,l.Poids_net from LOT l left join ADJUDICATION ad on ad.num_lot = l.Num_lot
	where l.Code_vendeur = '$_REQUEST[id_vendeur]' and etat = 1
	and ad.Num_adjudication is null and l.Date_lot =
	 convert(date, '".$_REQUEST['selected_date']."',105)";
 }
//echo $query_select;

$params_query_select = array();
	$options_query_select =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_select = sqlsrv_query($con,$query_select,$params_query_select,
	$options_query_select);
	$ntRes_select = sqlsrv_num_rows($result_query_select);
	if($ntRes_select < 1){
	?>
	<div class="alert alert-danger" role="alert">
	 <?php echo lang('without_data');
	 $details_lot_txt.="----------------------------------------------".PHP_EOL;
	 $details_lot_txt .= lang('without_data').PHP_EOL;
	 $details_lot_txt.="----------------------------------------------".PHP_EOL;
	   ?>
	</div>
	 <?php
	  for($x=0;$x<5;$x++){
	  	echo "<br>";
	  }
	  ?>
	<?php
	}else{

	?>
	<table class="table">
						  <thead class="thead-dark">
						    <tr>
						    <?php  if(!isset($_REQUEST['report_seller'])){
						    	?>
						    	<th scope="col"><?php echo lang('update'); ?></th>
						    	<?php
						    }?>

						      <th scope="col"><?php echo lang('lot_id'); ?></th>
						      <?php  if(!isset($_REQUEST['report_seller'])){
						      ?>
						      <th scope="col"><?php echo lang('fish_type'); ?></th>
						      <?php
						      }?>
						      <th scope="col"><?php echo lang('the_type'); ?></th>
						      <th scope="col"><?php echo lang('Qte'); ?></th>
						      <?php  if(!isset($_REQUEST['report_seller'])){
						      ?>
						      <th scope="col"><?php echo lang('buy_operation'); ?></th>
						      <th scope="col"><?php echo lang('delete_operation'); ?></th>
						      <?php
						      }?>
						    </tr>
						  </thead>
						  <tbody >
	<?php
	while($reader_query_select= sqlsrv_fetch_array($result_query_select, SQLSRV_FETCH_ASSOC)){

	$family = lang('inconnu');
	$nom_espece =  lang('inconnu');
	$query_family = "select f.NOM_FAM,e.Nom_espece from ESPECE e
	inner join FAMILLE f on f.CODE_FAM=e.code_famille
	where e.Code_espece = ".$reader_query_select['Code_espece'];

	//echo $query_family;

	$params_query_family = array();
	$options_query_family =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_family = sqlsrv_query($con,$query_family,$params_query_family,
	$options_query_family);
	while($reader_query_family= sqlsrv_fetch_array($result_query_family, SQLSRV_FETCH_ASSOC))
	{
		$family = $reader_query_family['NOM_FAM'];

	}

	$query_espece = "select e.Nom_espece from ESPECE e

	where e.Code_espece = ".$reader_query_select['Code_espece'];

	$params_query_espece = array();
	$options_query_espece =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_espece = sqlsrv_query($con,$query_espece,$params_query_espece,
	$options_query_espece);
	while($reader__query_espece= sqlsrv_fetch_array($result_query_espece, SQLSRV_FETCH_ASSOC))
	{

		$nom_espece =  $reader__query_espece['Nom_espece'];
	}

	?>
	<tr>
							 <?php  if(!isset($_REQUEST['report_seller'])){
						    	?>
						    <th onclick="update_func_lot('<?php echo $reader_query_select["Num_lot"] ?>','<?php echo $reader_query_select["Poids_net"] ?>','<?php
						       echo $nom_espece ?>','<?php echo $family; ?>')">
						    	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="50px" height="50px">
								<path d="M397.736,78.378c6.824,0,12.358-5.533,12.358-12.358V27.027C410.094,12.125,397.977,0,383.08,0H121.641    c-3.277,0-6.42,1.303-8.739,3.62L10.527,105.995c-2.317,2.317-3.62,5.461-3.62,8.738v370.239C6.908,499.875,19.032,512,33.935,512    h349.144c14.897,0,27.014-12.125,27.014-27.027V296.289c0.001-6.824-5.532-12.358-12.357-12.358    c-6.824,0-12.358,5.533-12.358,12.358v188.684c0,1.274-1.031,2.311-2.297,2.311H33.936c-1.274,0-2.311-1.037-2.311-2.311v-357.88    h75.36c14.898,0,27.016-12.12,27.016-27.017V24.716H383.08c1.267,0,2.297,1.037,2.297,2.311V66.02    C385.377,72.845,390.911,78.378,397.736,78.378z M109.285,100.075c0,1.269-1.032,2.301-2.3,2.301H49.107l60.178-60.18V100.075z" fill="#D80027"/>
								<path d="M492.865,100.396l-14.541-14.539c-16.304-16.304-42.832-16.302-59.138,0L303.763,201.28H103.559    c-6.825,0-12.358,5.533-12.358,12.358c0,6.825,5.533,12.358,12.358,12.358h175.488l-74.379,74.379H103.559    c-6.825,0-12.358,5.533-12.358,12.358s5.533,12.358,12.358,12.358h76.392l-0.199,0.199c-1.508,1.508-2.598,3.379-3.169,5.433    l-19.088,68.747h-53.936c-6.825,0-12.358,5.533-12.358,12.358s5.533,12.358,12.358,12.358h63.332c0.001,0,2.709-0.306,3.107-0.41    c0.065-0.017,77.997-21.642,77.997-21.642c2.054-0.57,3.926-1.662,5.433-3.169l239.438-239.435    C509.168,143.228,509.168,116.7,492.865,100.396z M184.644,394.073l10.087-36.326l26.24,26.24L184.644,394.073z M244.69,372.752    l-38.721-38.721l197.648-197.648l38.722,38.721L244.69,372.752z M475.387,142.054l-15.571,15.571l-38.722-38.722l15.571-15.571    c6.669-6.668,17.517-6.667,24.181,0l14.541,14.541C482.054,124.54,482.054,135.388,475.387,142.054z" fill="#D80027"/>
								</svg>

						    </th>
						    <?php
						    }?>
						      <th scope="row">
						      	<?php echo $reader_query_select['Num_lot'];
						      	//$details_lot_txt .= lang('lot_id').$reader_query_select['Num_lot'].PHP_EOL;
						      	$details_lot_txt .= lang('lot_id').$reader_query_select['Num_lot']." - ";
						        ?>

						      </th>
						      <?php  if(!isset($_REQUEST['report_seller'])){
						      ?>
						      <td><?php echo $family; ?></td>
						      <?php
						      }?>
						      <td>
						      	<?php
						      	echo $nom_espece;
						      	//$details_lot_txt .= $nom_espece.PHP_EOL;
						      	$details_lot_txt .= $nom_espece." - ";
						      	 ?>

						      </td>
						      <td style="direction: ltr;">
						      <?php
						      $total_lot_for_print+= $reader_query_select['Poids_net'];
						      $count_lot_for_print++;
						      $nombre_format_francais = number_format($reader_query_select['Poids_net'], 3, ',', ' ');
						      echo $nombre_format_francais;
						      //$details_lot_txt .= $nombre_format_francais." ".lang('kg').PHP_EOL;
						      $details_lot_txt .= $nombre_format_francais." kg ".PHP_EOL;
						      ?>

						      </td>
						       <?php  if(!isset($_REQUEST['report_seller'])){
						    	?>
						       <td>
						       	<button class="btn btn-block btn-lg btn-success"
						       	onclick="buy_func_lot('<?php echo $reader_query_select["Num_lot"] ?>','<?php echo $reader_query_select["Poids_net"] ?>','<?php
						       echo $nom_espece ?>')">
						       		<?php echo lang('buy_operation'); ?>
						       	</button>
						       </td>
						       <td onclick="confirm_delete_lot('<?php echo $reader_query_select["Num_lot"] ?>','<?php echo $reader_query_select["Poids_net"] ?>','<?php
						       echo $nom_espece ?>')">
						       	<svg version="1.1" width="50px" height="50px" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
								<polygon style="fill:#E21B1B;" points="404.176,0 256,148.176 107.824,0 0,107.824 148.176,256 0,404.176 107.824,512 256,363.824
								404.176,512 512,404.176 363.824,256 512,107.824 "/>
								</svg>
						       </td>
						        <?php
						  		  }?>
						    </tr>
	<?php
	}
	?>
	</tbody>
	</table>
	<?php
	}

if(isset($_REQUEST['add_lot'])){

	$enteteFile = "ID".PHP_EOL;
	$Date=date_create(date("Y-m-d  H:i"));
	$enteteFile.=lang('username').$_SESSION['username'].PHP_EOL;
	$enteteFile.=lang('seller')." : ".strtoupper($_REQUEST['seller']).PHP_EOL ;
	$enteteFile.=lang('date_and_time').date_format($Date, 'd/m/Y H:i').PHP_EOL;
	//$enteteFile.= lang('lot_id')." ".$num_lot_to_imprime_txt.PHP_EOL;
	$qte = number_format($_REQUEST['qte'], 3, ',', ' ');
	//$arr = array(,,);


	$query_details_lot="select d.num_pose,d.poids from details_lot d where d.num_lot = '$num_lot_to_imprime_txt'";


	$poids_number = null;


	$params_details_lot = array();
	$options_details_lot =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_details_lot = sqlsrv_query($con,$query_details_lot,$params_details_lot,
	$options_details_lot);
	while($reader_details_lot= sqlsrv_fetch_array($result_details_lot, SQLSRV_FETCH_ASSOC))
	{

		$poids_number =  lang('poids_number')." : ".$reader_details_lot['num_pose']
		/*." | الوزن : ".$reader_details_lot['poids']*//*.PHP_EOL*/;
	}


	/*$t_print = null;
	if($new_poids==0)
		$t_print=$_REQUEST['espece'];
	else
		$t_print=$new_poids;

	*/



	//$enteteFile.=$num_lot_to_imprime_txt.chr(45).$_REQUEST['espece'].chr (45).$qte.PHP_EOL;


	//$enteteFile.="الدفعة ".$num_lot_to_imprime_txt.PHP_EOL.$t_print.PHP_EOL.$qte.PHP_EOL;
	$enteteFile.=lang('lot_id')." ".$num_lot_to_imprime_txt.PHP_EOL.$_REQUEST['espece'].PHP_EOL.$poids_number.PHP_EOL.$qte.PHP_EOL;

	//echo join("-",$arr);
	//$enteteFile.=$qte." ".lang('kg').PHP_EOL;

	$name=date('d-m-Y H-i-s');
	$fp = fopen ("../data/uploads/".$name.".txt","w+");
	$Imprime = $enteteFile;
	fputs ($fp,$Imprime);
	fclose ($fp);
	$dir= "../data/uploads/".$name.".txt";
	$filename=$name.".txt";
	$name= urlencode ($name);

	$link = "Ajax/download.php?fileName=".$name;
	?>
	<input type="hidden" value="<?php echo $link; ?>" name="" id="link_to_imprim">
	<?php
}


if(isset($_REQUEST['imprime_tout'])){

	$enteteFile = "BVLID".PHP_EOL;
	$Date=date_create(date("Y-m-d  H:i"));
	$enteteFile.=lang('username').$_SESSION['username'].PHP_EOL;
	$enteteFile.="البائع : ".strtoupper($_REQUEST['seller']).PHP_EOL ;
	$enteteFile.=lang('date_and_time').date_format($Date, 'd/m/Y H:i').PHP_EOL;

	$footer = "------------------------------------------".PHP_EOL;
	$total_lot_for_print = number_format($total_lot_for_print, 3, ',', ' ');

	//$footer .= "المجموع ".$total_lot_for_print." ".lang('kg').PHP_EOL;
	$footer .= lang('total').$total_lot_for_print." kg ".PHP_EOL;

	$footer.= lang('number_lot_s')." ".$count_lot_for_print.PHP_EOL;

	$name=date('d-m-Y H-i-s');
	$fp = fopen ("../data/uploads/".$name.".txt","w+");
	$Imprime = $enteteFile.$details_lot_txt.$footer;
	fputs ($fp,$Imprime);
	fclose ($fp);
	$dir= "../data/uploads/".$name.".txt";
	$filename=$name.".txt";
	$name= urlencode ($name);

	$link = "Ajax/download.php?fileName=".$name;
	?>
	<input type="hidden" value="<?php echo $link; ?>" name="" id="link_to_imprim_all">
	<?php
}
?>

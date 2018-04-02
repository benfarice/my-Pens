<?php 
session_start();
$lang = '../includes/languages/';
include_once $lang.$_SESSION['Lang'].'.php';

include "../connect.php";


$total_lot_for_print = 0;
$total_money = 0 ;
$count_lot_for_print = 0;
$total_lot_for_print_buyer = 0;
$count_lot_for_print_buyer = 0;
$details_lot_txt = "";
if(isset($_REQUEST['report_seller_lot_has_selled'])){
	$query_select="select l.Num_lot,l.Code_espece,(select e.Nom_espece from ESPECE e where e.Code_espece = l.Code_espece) as esp_n
	,l.Poids_net,ad.Prix_unitaire,ad.Prix_net,ad.Code_Acheteur,
	(select sr.name_ar from BUYER_SELLER sr where sr.ID_B_S = ad.Code_Acheteur) as ach_name 
	 from LOT l inner join ADJUDICATION ad on ad.num_lot = l.Num_lot
	where l.Code_vendeur = '$_REQUEST[id_vendeur]' and etat = 2 and cast(ad.Date_adjudication as date) = '".$_REQUEST['selected_date']."'";



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
					<th scope="col"><?php echo lang('lot_id'); ?></th>
					<th scope="col"><?php echo lang('the_type'); ?></th>
					<th scope="col"><?php echo lang('Qte'); ?></th>

					<th scope="col"><?php echo lang('PrixUnite'); ?></th>
					<th scope="col"><?php echo lang('TotalPrix'); ?></th>
					<th scope="col"><?php echo lang('search_buyer'); ?></th>
			</thead>
			<tbody >
	<?php
	while($reader_query_select= sqlsrv_fetch_array($result_query_select, SQLSRV_FETCH_ASSOC)){ 
		?>
		<tr>
		<td scope="row"><?php echo $reader_query_select['Num_lot'];
		$details_lot_txt .= lang('lot_id').$reader_query_select['Num_lot']." - ";
		?></td>
		<td scope="row"><?php 
		$details_lot_txt .= $reader_query_select['esp_n']." - ";
		echo $reader_query_select['esp_n'];?></td>
		<td scope="row"><?php
		$total_lot_for_print+= $reader_query_select['Poids_net'];
		$count_lot_for_print++;

		$nombre_format_francais = number_format($reader_query_select['Poids_net'], 3, ',', ' ');
		echo $nombre_format_francais; 
		$details_lot_txt .= $nombre_format_francais." kg "." - ";
		?></td>
		<td scope="row"><?php
		echo $reader_query_select['Prix_unitaire'];
		$details_lot_txt .= $reader_query_select['Prix_unitaire']." - ";
		?></td>
		<td scope="row"><?php
		$details_lot_txt .= $reader_query_select['Prix_net']." - ";
		$total_money += $reader_query_select['Prix_net'];
		echo $reader_query_select['Prix_net'];?></td>
		<td scope="row"><?php 
		$details_lot_txt .= $reader_query_select['ach_name'].PHP_EOL;
		echo $reader_query_select['ach_name'];?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	</table>
	<?php
	}
}



if(isset($_REQUEST['report_buyer_lot_has_buyed'])){
	$query_select_buyer_has_buyed ="select ad.num_lot,ad.Num_adjudication,ad.Poids_net,
		ad.Prix_unitaire,ad.Prix_net,
		(select s.Nom_espece from ESPECE s where
		 s.Code_espece=l.Code_espece) as es_name,
		(select bs.name_ar from BUYER_SELLER bs where bs.ID_B_S = l.Code_vendeur) as vendeur
		 from ADJUDICATION ad inner join LOT l on
		 l.Num_lot = ad.num_lot where 
		cast(ad.Date_adjudication as date) = '".$_REQUEST['selected_date']."' and
		ad.Code_Acheteur = '$_REQUEST[id_vendeur]'";




	$params_query_select = array();
	$options_query_select =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_select = sqlsrv_query($con,$query_select_buyer_has_buyed,$params_query_select,
	$options_query_select);
	$ntRes_select = sqlsrv_num_rows($result_query_select);
	if($ntRes_select < 1){
	?>
	<div class="alert alert-danger" role="alert">
	 <?php echo lang('without_data_buyer');
	
	 $details_lot_txt.="----------------------------------------------".PHP_EOL;
	 $details_lot_txt .= lang('without_data_buyer').PHP_EOL;
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
					<th scope="col"><?php echo lang('lot_id'); ?></th>
					<th scope="col"><?php echo lang('the_type'); ?></th>
					<th scope="col"><?php echo lang('Qte'); ?></th>

					<th scope="col"><?php echo lang('PrixUnite'); ?></th>
					<th scope="col"><?php echo lang('TotalPrix'); ?></th>
					<th scope="col"><?php echo lang('seller'); ?></th>
			</thead>
			<tbody >
	<?php
	while($reader_query_select= sqlsrv_fetch_array($result_query_select, SQLSRV_FETCH_ASSOC)){ 
		?>
		<tr>
			<td scope="row">
				<?php echo $reader_query_select['num_lot'];
				$details_lot_txt .= lang('lot_id').$reader_query_select['num_lot']." - ";
				?>
			</td>
			<td scope="row">
				<?php echo $reader_query_select['es_name'];
				$details_lot_txt .= $reader_query_select['es_name']." - ";
				?>
			</td>
			<td scope="row">
				<?php
				$total_lot_for_print_buyer+= $reader_query_select['Poids_net'];
				$count_lot_for_print_buyer++;
				$nombre_format_francais = number_format($reader_query_select['Poids_net'], 3, ',', ' ');
				echo $nombre_format_francais; 
				$details_lot_txt .= $nombre_format_francais." kg "." - ";
				?>
			</td>
			<td scope="row">
				<?php echo $reader_query_select['Prix_unitaire'];
				$details_lot_txt .= $reader_query_select['Prix_unitaire']." - ";
				?>
			</td>
			<td scope="row">
				<?php echo $reader_query_select['Prix_net'];
				$details_lot_txt .= $reader_query_select['Prix_net']." - ";
				$total_money += $reader_query_select['Prix_net'];
				?>
			</td>
			<td scope="row">
				<?php echo $reader_query_select['vendeur'];
				$details_lot_txt .= $reader_query_select['vendeur'].PHP_EOL;
				?>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
	</table>
	<?php
	}
}



















if(isset($_REQUEST['imprime_tout']) && isset($_REQUEST['report_seller_lot_has_selled'])){

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
	$footer.= lang('total_money')." ".$total_money.PHP_EOL;

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


//********************************************************************


if(isset($_REQUEST['imprime_tout']) && isset($_REQUEST['report_buyer_lot_has_buyed'])){

	$enteteFile = "BVLID".PHP_EOL;
	$Date=date_create(date("Y-m-d  H:i"));
	$enteteFile.=lang('username').$_SESSION['username'].PHP_EOL;
	$enteteFile.="المشتري : ".strtoupper($_REQUEST['buyer_selected']).PHP_EOL ;
	$enteteFile.=lang('date_and_time').date_format($Date, 'd/m/Y H:i').PHP_EOL;

	$footer = "------------------------------------------".PHP_EOL;
	$total_lot_for_print_buyer = number_format($total_lot_for_print_buyer, 3, ',', ' ');
	
	//$footer .= "المجموع ".$total_lot_for_print." ".lang('kg').PHP_EOL;
	$footer .= lang('total').$total_lot_for_print_buyer." kg ".PHP_EOL;

	$footer.= lang('number_lot_s')." ".$count_lot_for_print_buyer.PHP_EOL;

	$footer.= lang('total_money')." ".$total_money.PHP_EOL;
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
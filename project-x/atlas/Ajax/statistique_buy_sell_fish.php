<?php
session_start();
$lang = '../includes/languages/';
include_once $lang.$_SESSION['Lang'].'.php';

include "../connect.php";



if(isset($_REQUEST['date_d_selected']) && isset($_REQUEST['date_f_selected'])){

   $date_a = date_format(date_create_from_format('d/m/Y', $_REQUEST['date_d_selected']), 'm/d/Y');
	 $date_a.=' 12:00:00 AM';

	 $date_b = date_format(date_create_from_format('d/m/Y', $_REQUEST['date_f_selected']), 'm/d/Y');
	 $date_b.=' 12:00:00 AM';

	 $query_select = "select sum(Poids_net) as Poids_net, Prix_unitaire,
		sum(Prix_net) as Prix_net,Code_Acheteur,acheteur,the_type
		 from (select ad.Num_adjudication,ad.Poids_net,ad.Prix_unitaire,
		ad.Prix_net,ad.Code_Acheteur,(select bs.name_ar from BUYER_SELLER bs where
		bs.ID_B_S = ad.Code_Acheteur) as acheteur,ad.num_lot,( select e.Nom_espece
		from espece e where code_espece in (select l.Code_espece from LOT l where l.Num_lot = ad.num_lot))
		as the_type from ADJUDICATION ad where
		cast(ad.Date_adjudication as date) between '$date_a' and '$date_b') my_table
		group by the_type,Code_Acheteur,acheteur,Prix_unitaire";
//	echo $query_select;
	$stmt_query_select=sqlsrv_query($con,$query_select);
	$total_poids = 0;
	$total_money = 0;


		?>
			<div class="col-md-12 col-sm-12 col-xs-12 text-center">
			<h2 class="text-primary"><?php echo lang('statistic_sell_fish'); ?></h2>
			</div>
			<table class="table" id="report_bill_buyer_table" class="text-right">
				  <thead class="entete" style="background: #b9c9fe;color: #003399;">
				    <tr>
				      <th scope="col"><?php echo lang('search_buyer'); ?></th>
				      <th scope="col" ><?php echo lang('the_type'); ?></th>
				      <th scope="col"><?php echo lang('poids'); ?></th>
				      <th scope="col"><?php echo lang('price')." ".lang('reyal_homany'); ?></th>
				      <th scope="col"><?php echo lang('total')." ".lang('reyal_homany'); ?></th>
				    </tr>
				  </thead>
				  <tbody style="font-size: 22px;">
		<?php
		while($row_query_select = sqlsrv_fetch_array($stmt_query_select, SQLSRV_FETCH_ASSOC)){
		?>
		   <tr>
				      <th scope="row"><?php echo $row_query_select['acheteur']; ?></th>
				      <td class="the_type_td"><?php
				      if($row_query_select['the_type'] == "" || $row_query_select['the_type'] == null){
				      	 echo lang('inconnu');
				      }
				      else{
				      	echo $row_query_select['the_type'];
				  	 	}
				  	  ?>

				  	  </td>

				      <td style="direction: ltr;"><?php
				      echo number_format($row_query_select['Poids_net'], 3, ',', ' ');
				      $total_poids+= $row_query_select['Poids_net'];
				      ?>

				      <span style="display: none;" class="poids_sell_td"><?php echo $row_query_select['Poids_net']; ?></span>
				      </td>
				      <td style="direction: ltr;"><?php echo number_format($row_query_select['Prix_unitaire'], 3, ',', ' '); ?></td>
				      <td style="direction: ltr;"><?php
				      $total_money+= $row_query_select['Prix_net'];
				      echo number_format($row_query_select['Prix_net'], 3, ',', ' '); ?></td>
			</tr>
		<?php
		}
		?>


	    </tbody>
	    <tfoot>
	    	<tr style="font-weight: bold;font-size: 22px;background: #ebebeb;">
				<td></td>
				<td class="text-primary"><?php echo lang('total').' '.lang('reyal_homany'); ?></td>
				<td class="text-primary" style="direction: ltr;"><?php echo number_format($total_poids, 3, ',', ' ');?></td>
				<td class="text-primary"><?php echo lang('total').' '.lang('reyal_homany'); ?></td>
				<td class="text-primary" style="direction: ltr;"><?php echo number_format($total_money, 3, ',', ' ');?></td>
			</tr>
	    </tfoot>
		</table>
		<?php

}


//***************************************************************************************


if(isset($_REQUEST['date_d_selected']) && isset($_REQUEST['date_f_selected'])){


	$date_a = date_format(date_create_from_format('d/m/Y', $_REQUEST['date_d_selected']), 'm/d/Y');
	$date_a.=' 12:00:00 AM';

	$date_b = date_format(date_create_from_format('d/m/Y', $_REQUEST['date_f_selected']), 'm/d/Y');
	$date_b.=' 12:00:00 AM';


	$query_select_seller = "select count(ad.num_lot)  as nombre_lot,l.Code_espece,l.Code_vendeur,sum(ad.Poids_net)
	 as sum_poids from ADJUDICATION ad inner join LOT l
	on l.Num_lot = ad.num_lot
	where cast(ad.Date_adjudication as date) between '$date_a' and '$date_b'
	group by l.Code_vendeur,l.Code_espece ";
//	echo $query_select;
	$stmt_query_select=sqlsrv_query($con,$query_select_seller);
	$total_poids_seller = 0;



		?>
			<div class="col-md-12 col-sm-12 col-xs-12 text-center">
			<h2 class="text-primary"><?php echo lang('statistic_in_fish'); ?></h2>
			</div>
			<table class="table" id="report_bill_seller_table" class="text-right">
				  <thead class="entete" style="background: #b9c9fe;color: #003399;">
				    <tr>
				      <th scope="col"><?php echo lang('seller'); ?></th>
				      <th scope="col"><?php echo lang('the_type'); ?></th>
				      <th scope="col"><?php echo lang('poids'); ?></th>
				      <th scope="col"><?php echo lang('number_lot_s'); ?></th>

				    </tr>
				  </thead>
				  <tbody style="font-size: 22px;">
		<?php
		while($row_query_select = sqlsrv_fetch_array($stmt_query_select, SQLSRV_FETCH_ASSOC)){
	$seller_name = lang('inconnu');
	$seller_name_query = "select bs.name_ar from BUYER_SELLER bs where
	bs.ID_B_S ='$row_query_select[Code_vendeur]'";
	$stmt_name_query=sqlsrv_query($con,$seller_name_query);
	while($row_name_query = sqlsrv_fetch_array($stmt_name_query, SQLSRV_FETCH_ASSOC)){
		$seller_name = $row_name_query['name_ar'];
	}


	$type_name = lang('inconnu');
	$type_name_query = "select e.Nom_espece from ESPECE e where
	e.Code_espece = '$row_query_select[Code_espece]'";
	$stmt_type_query=sqlsrv_query($con,$type_name_query);
	while($row_type_query = sqlsrv_fetch_array($stmt_type_query, SQLSRV_FETCH_ASSOC)){
		$type_name = $row_type_query['Nom_espece'];
	}

		?>
		   <tr>
				      <th scope="row"><?php echo $seller_name; ?></th>
				      <td class="type_name_in"><?php echo $type_name; ?></td>
				      <td style="direction: ltr;"><?php
				      echo number_format($row_query_select['sum_poids'], 3, ',', ' ');
				      $total_poids_seller+= $row_query_select['sum_poids'];
				      ?>
				      <span class="poids_in" style="display: none;"><?php echo $row_query_select['sum_poids']; ?></span>
				      </td>
				      <td ><?php echo
				      //number_format(, 3, ',', ' ');
				      $row_query_select['nombre_lot']; ?></td>

			</tr>
		<?php
		}
		?>


	    </tbody>
	    <tfoot>
	    	<tr style="font-weight: bold;font-size: 22px;background: #ebebeb;">
				<td></td>
				<td class="text-primary"><?php echo lang('total'); ?></td>
				<td class="text-primary" style="direction: ltr;"><?php echo number_format($total_poids_seller, 3, ',', ' ');?></td>
				<td class="text-primary"><?php echo lang('reyal_homany'); ?></td>

			</tr>
	    </tfoot>
		</table>
		<?php

}

<?php
session_start();
$lang = '../includes/languages/';
include_once $lang.$_SESSION['Lang'].'.php';

include "../connect.php";


if(isset($_REQUEST['print'])){
	?>
	<div class="row" style="margin-bottom: 70px;" id="title_print">

		<div class="col-md-3 col-sm-3 col-xs-3">
			<img src="layout/images/atlas.jpg"   height="150px">
		</div>


		<div class="col-md-6 col-sm-6 col-xs-6 website_title_print text-center" >

			<p>
				<?php echo lang('minister_desc_1'); ?>
			</p>
			<p>
				<?php echo lang('minister_desc_2'); ?>
			</p>

		    <p><?php echo lang('port'); ?></p>
		</div>

		<div class="col-md-3 col-sm-3 col-xs-3 text-left">
			<img src="layout/images/logo1.png"  height="150px">
		</div>


	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12 text-center">
			<h1 class="text-primary"><?php echo lang('report_bill_buyers'); ?></h1>
			<h2 class="text-primary"><?php echo lang('Periode')." ".lang('de')."  ".
			$_REQUEST['date_d_selected']."  ".lang('a')."  ".$_REQUEST['date_f_selected']; ?></h2>
		</div>
	</div>
	<?php
}

if(isset($_REQUEST['date_d_selected']) && isset($_REQUEST['date_f_selected'])){
	$date_a_ = date_format(date_create_from_format('d/m/Y', $_REQUEST['date_d_selected']), 'Y-m-d');
	$date_b_ = date_format(date_create_from_format('d/m/Y', $_REQUEST['date_f_selected']), 'Y-m-d');
/*
$query_select = "select f.CodeFac,f.IdFac,f.codeBye codeBuy
,(select bs.name_ar from
BUYER_SELLER bs where bs.ID_B_S = f.codeBye ) as buyer,
f.Date,f.Montant,isnull((select
d.IdReg from detailReglements d where d.IdFac = f.IdFac),0) as reg_f from factures_acht f
where cast(f.Date as date) between '$_REQUEST[date_d_selected]' and '$_REQUEST[date_f_selected]";
*/
function is_localhost() {
    $whitelist = array( '127.0.0.1', '::1' );
    return in_array( $_SERVER['REMOTE_ADDR'], $whitelist);
}
$query_select = "select f.CodeFac,f.IdFac,f.codebye, f.Date,f.Montant,IdReg from factures_acht f
  inner join detailReglements d on d.IdFac = f.IdFac
  where cast(f.Date as date)
between  convert(date, '$_REQUEST[date_d_selected]',105) and convert(date, '$_REQUEST[date_f_selected]',105)";
/*
$query_select = "select f.CodeFac,f.IdFac,f.codebye,
f.Date,f.Montant,isnull((select
d.IdReg from detailReglements d where d.IdFac = f.IdFac),0) as reg_f from factures_acht f
where cast(f.Date as date) between '$_REQUEST[date_d_selected]' and '$_REQUEST[date_f_selected]'";

$query_select = null;
if(is_localhost()){
	$query_select = "select f.CodeFac,f.IdFac,f.codeBuy,
f.Date,f.Montant,isnull((select
d.IdReg from detailReglements d where d.IdFac = f.IdFac),0) as reg_f from factures_acht f
where cast(f.Date as date) between '$_REQUEST[date_d_selected]' and '$_REQUEST[date_f_selected]'";
}else{
	$query_select = "select f.CodeFac,f.IdFac,f.codeBuy,
f.Date,f.Montant,isnull((select
d.IdReg from detailReglements d where d.IdFac = f.IdFac),0) as reg_f from factures_acht f
where cast(f.Date as date) between '$date_a_' and '$date_b_'";
}
*/

	//echo $query_select;
	$stmt_query_select=sqlsrv_query($con,$query_select);
	$total_f = 0;
	$_total_f_not_pay = 0;
	$_total_f_has_pay = 0;

		?>
			<table class="table" id="report_bill_buyer_table" class="text-right">
				  <thead class="entete">
				    <tr>
				      <th scope="col"><?php echo lang('num_facture'); ?></th>
				      <th scope="col"><?php echo lang('search_buyer'); ?></th>
				      <th scope="col"><?php echo lang('date'); ?></th>
				      <th scope="col"><?php echo lang('total')." ".lang('reyal_homany'); ?></th>
				      <th scope="col"><?php echo lang('state'); ?></th>
				    </tr>
				  </thead>
				  <tbody style="font-size: 22px;">
		<?php
		while($row_query_select = sqlsrv_fetch_array($stmt_query_select, SQLSRV_FETCH_ASSOC)){
$buyer_name= null;
$query_get_buyer_name ="select bs.name_ar from
BUYER_SELLER bs where bs.ID_B_S = '".$row_query_select['codebye']."'";
//echo $query_get_buyer_name;
$stmt_query_select_get_buyer_name=sqlsrv_query($con,$query_get_buyer_name);
while($row_get_buyer_name = sqlsrv_fetch_array($stmt_query_select_get_buyer_name, SQLSRV_FETCH_ASSOC)){
			$buyer_name = $row_get_buyer_name['name_ar'];
		}
		?>
		   <tr>
				      <th scope="row"><?php echo $row_query_select['CodeFac']; ?></th>
				      <td><?php echo $buyer_name; ?></td>
				      <td><?php
					  $timestamp = strtotime($row_query_select['Date']);

					  echo date("d/m/Y", $timestamp); ?></td>
				      <td  style="direction: ltr;"><?php
				      $total_f+=$row_query_select['Montant'];
				      $print_montant = number_format($row_query_select['Montant'], 3, ',', ' ');
				      echo $print_montant; ?></td>
				      <?php
				      if($row_query_select['IdReg'] == 0){
				      	echo "<td class='text-danger'>".lang('non_reg');
				      	$_total_f_not_pay += $row_query_select['Montant'];
				      }else{
				      	echo "<td class='text-success'>".lang('oui_reg');
				      	$_total_f_has_pay+=$row_query_select['Montant'];
				      }
				      ?></td>
			</tr>
		<?php
		}
		?>
		<tr style="font-weight: bold;">
			<td><?php echo lang('total'); ?></td>
			<td></td>
			<td></td>
			<td style="direction: ltr;"><?php echo number_format($total_f, 3, ',', ' ');?></td>
			<td><?php echo lang('reyal_homany'); ?></td>

		</tr>
		<tr style="font-weight: bold;">
			<td class="text-danger"><?php echo lang('total_non_pay'); ?></td>
			<td></td>
			<td></td>
			<td style="direction: ltr;" class="text-right text-danger"><?php echo number_format($_total_f_not_pay, 3, ',', ' ');?></td>
			<td class="text-right text-danger"><?php echo lang('reyal_homany'); ?></td>

		</tr>
		<tr style="font-weight: bold;">
			<td class="text-success"><?php echo lang('total_has_pay'); ?></td>
			<td></td>
			<td></td>
			<td style="direction: ltr;" class="text-right text-success"><?php echo number_format($_total_f_has_pay, 3, ',', ' ');?></td>
			<td class="text-right text-success"><?php echo lang('reyal_homany'); ?></td>

		</tr>
	    </tbody>
		</table>
		<?php

}


if(isset($_REQUEST['print'])){
	?>

	<?php
}
?>

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
			<h1 class="text-primary"><?php echo lang('report_bill_sellers'); ?></h1>
			<h2 class="text-primary"><?php echo lang('Periode')." ".lang('de')."  ".
			$_REQUEST['date_d_selected']."  ".lang('a')."  ".$_REQUEST['date_f_selected']; ?></h2>
		</div>
	</div>
	<?php
}

if(isset($_REQUEST['date_d_selected']) && isset($_REQUEST['date_f_selected'])){

	$query_select = "select f.Num_facture,(select bs.name_ar from BUYER_SELLER bs
	 where bs.ID_B_S = f.Code_Vendeur) as seller,
	f.Date_F,f.Total,isnull((select 
	d.IdReg from detailReglements d where d.IdFac = f.iD_Fac),0) as reg_f ,f.Taxe
	from facture_vendeur f where cast(f.Date_F as date) between '$_REQUEST[date_d_selected]' 
	and '$_REQUEST[date_f_selected]'";
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
				      <th scope="col"><?php echo lang('seller'); ?></th>
				      <th scope="col"><?php echo lang('date'); ?></th>
				      <th scope="col"><?php echo lang('total')." ".lang('reyal_homany'); ?></th>
				      <th scope="col"><?php echo lang('state'); ?></th>
				      <th scope="col"><?php echo lang('Tax'); ?></th>
				      
				    </tr>
				  </thead>
				  <tbody style="font-size: 22px;">
		<?php
		while($row_query_select = sqlsrv_fetch_array($stmt_query_select, SQLSRV_FETCH_ASSOC)){
		?>
		   <tr>
				      <th scope="row"><?php echo $row_query_select['Num_facture']; ?></th>
				      <td><?php echo $row_query_select['seller']; ?></td>
				      <td><?php
				      if(is_a($row_query_select['Date_F'], 'DateTime')){
					      	echo $row_query_select['Date_F']->format('d/m/Y');
					      }else{
					      	echo $row_query_select['Date_F'];
					      }
					  ?>
					 	
					 </td>
				      <td  style="direction: ltr;"><?php 
				      $total_f+=$row_query_select['Total'];
				      $print_montant = number_format($row_query_select['Total'], 3, ',', ' ');
				      echo $print_montant; ?></td>
				      <?php
				      if($row_query_select['reg_f'] == 0){
				      	echo "<td class='text-danger'>".lang('non_reg');
				      	$_total_f_not_pay += $row_query_select['Total'];
				      }else{
				      	echo "<td class='text-success'>".lang('oui_reg');
				      	$_total_f_has_pay+=$row_query_select['Total'];
				      }
				      ?></td>
				      <td><?php echo $row_query_select['Taxe']; ?></td>
			</tr>
		<?php
		}
		?>
		<tr style="font-weight: bold;">
			<td><?php echo lang('total'); ?></td>
			<td></td>
			<td></td>
			<td style="direction: ltr;" class="text-right"><?php echo number_format($total_f, 3, ',', ' ');?></td>
			<td class="text-right"><?php echo lang('reyal_homany'); ?></td>
			<td></td>
		</tr>
		<tr style="font-weight: bold;">
			<td class="text-danger"><?php echo lang('total_non_pay'); ?></td>
			<td></td>
			<td></td>
			<td style="direction: ltr;" class="text-right text-danger"><?php echo number_format($_total_f_not_pay, 3, ',', ' ');?></td>
			<td class="text-right text-danger"><?php echo lang('reyal_homany'); ?></td>
			<td></td>
		</tr>
		<tr style="font-weight: bold;">
			<td class="text-success"><?php echo lang('total_has_pay'); ?></td>
			<td></td>
			<td></td>
			<td style="direction: ltr;" class="text-right text-success"><?php echo number_format($_total_f_has_pay, 3, ',', ' ');?></td>
			<td class="text-right text-success"><?php echo lang('reyal_homany'); ?></td>
			<td></td>
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

				 
				
				
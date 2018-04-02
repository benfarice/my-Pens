<?php 
//session_start();
session_start();
if(!isset($_SESSION['username'])){
header('Location: index.php');
exit();
}
include_once "init.php";
include_once "../includes/functions/func1.php";
//error_reporting(0);
if(isset($_REQUEST['num_facture'])){
	 $query_select_facture = "select f.Date_F,bs.name_ar,f.Total,f.Taxe
	 from facture_vendeur f 
	 inner join BUYER_SELLER bs on bs.ID_B_S = f.Code_Vendeur
	 where f.Num_facture = '$_REQUEST[num_facture]'";

	 $seller = null;
	 $date_f = null;
	 $total_f = null;
	 $taxe_f = null;
	 $num_facture = $_REQUEST['num_facture'];
	

	$params__select_facture = array();
	$options__select_facture =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result__select_facture = sqlsrv_query($con,$query_select_facture,$params__select_facture,
	$options__select_facture);
	
	while($reader__select_facture= sqlsrv_fetch_array($result__select_facture, SQLSRV_FETCH_ASSOC)){ 
		$seller = $reader__select_facture['name_ar'];
		$date_f = $reader__select_facture['Date_F'];

		$total_f = $reader__select_facture['Total'];
		$taxe_f =  $reader__select_facture['Taxe'];
	} 






?>
<!DOCTYPE html>
	<html>
	<head>
			
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<title>ميناء صور | سوق السمك</title>	
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>bootstrap.min.css">
		<!--<link rel="stylesheet" type="text/css" href="<?php //echo $css ?>styles.css">-->
		<script type="text/javascript" src="<?php echo $js ?>jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>jQuery.print.min.js"></script>
		
	</head>
	<body>
		
	
<div class="container-fluid text-right" id="print_page">
	<!--<div class="row">
				<div class="col-md-2 col-sm-2 col-xs-2">
					
				</div>
				<div class="col-md-8 col-sm-8 col-xs-8">
					<h1><?php //echo lang('port'); ?></h1>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-2">
					
				</div>
	</div>
	-->
	<div class="row" style="margin-bottom: 70px;">
		
		<div class="col-md-3 col-sm-3 col-xs-3">
			<img src="layout/images/ATLAS.jpg"   height="150px">
		</div>
		
		
		<div class="col-md-6 col-sm-6 col-xs-6 website_title_print text-center" >
		   
			<p>
				<?php echo lang('minister_desc_1'); ?>
			</p>
			<p>
				<?php echo lang('minister_desc_2'); ?>
			</p>
			<!--
			<p>
				<?php //echo lang('minister_desc_3'); ?>
			</p>
			-->
		    <p><?php echo lang('port'); ?></p>
		</div>

		<div class="col-md-3 col-sm-3 col-xs-3 text-left">
			<img src="layout/images/MAF.jpg"  height="150px">
		</div>
		
		
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-3"></div>
		<div class="col-md-6 col-sm-6 col-xs-6">
			<table width="100%" id="top_d_f_table" cellpadding="10"><!-- class="table" -->
			   <tr>
			    <td class="text-right"><?php echo lang('num_facture_p'); ?></td>
			    <td class="text-center"><?php echo $num_facture; ?></td> 
			    <td class="text-left">Invoice Seller Number</td>
			  </tr>
			  <tr>
			    <td class="text-right"><?php echo lang('facture_date_title'); ?></td>
			    <td class="text-center"><?php echo $date_f->format('d/m/Y'); ?></td> 
			    <td class="text-left">Date</td>
			  </tr>
			  <tr>
			    <td class="text-right"><?php echo lang('seller'); ?></td>
			    <td class="text-center"><?php echo $seller; ?></td> 
			    <td class="text-left">Seller name</td>
			  </tr>
			 
			</table>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-3"></div>
	</div>
	
	<table  id="table_adjudication_print_f" border="1" width="100%" cellpadding="10" style="font-size: 20px;"><!-- class="table" -->
	  <thead>
	    <tr>
	      <th scope="col"><?php echo lang('operation_th'); ?></th>
	      <th scope="col"><?php echo lang('lot_id'); ?></th>
	      <th scope="col"><?php echo lang('the_type'); ?></th>
	      <th scope="col"><?php echo lang('search_buyer'); ?></th>
	      <th scope="col"><?php echo lang('Qte'); ?> (kg)</th>
	      <th scope="col"><?php echo lang('price')." (".lang('reyal_homany').")";  ?></th>
	      <th scope="col"><?php echo lang('Total')." (".lang('reyal_homany').")"; ?></th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php 
	  	$query_select_adjudication_f ="select df.num_adjudication,ad.Code_Acheteur,ad.num_lot,
		ad.Poids_net,ad.Prix_net,ad.Prix_unitaire,
		l.Code_espece,e.Nom_espece,bs.name_ar
		 from details_facture_vendeur df
		 inner join ADJUDICATION ad on ad.Num_adjudication = df.num_adjudication
		 inner join LOT l on ad.num_lot = l.Num_lot
		 inner join BUYER_SELLER bs on bs.ID_B_S = ad.Code_Acheteur
		 inner join ESPECE e on e.Code_espece = l.Code_espece
		  where df.Num_facture ='$_REQUEST[num_facture]'";

		$params_select_adjudication_f = array();
		$options_select_adjudication_f =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
		$result_select_adjudication_f = sqlsrv_query($con,$query_select_adjudication_f,
		$params_select_adjudication_f,
		$options_select_adjudication_f);
		$check_select_adjudication_f = sqlsrv_num_rows($result_select_adjudication_f);
		while($reader_select_adjudication_f = 
			sqlsrv_fetch_array($result_select_adjudication_f, SQLSRV_FETCH_ASSOC)){ 
				?>
				 <tr>
				      <th scope="row"><?php echo $reader_select_adjudication_f['num_adjudication']; ?></th>
				      <td><?php echo $reader_select_adjudication_f['num_lot']; ?></td>
				      <td><?php echo $reader_select_adjudication_f['Nom_espece']; ?></td>
				      <td><?php echo $reader_select_adjudication_f['name_ar']; ?></td>

				      <td  style="direction: ltr;"><?php 
				      $nombre_format_francais = 
				      number_format($reader_select_adjudication_f['Poids_net'], 3, ',', ' ');
				      echo $nombre_format_francais; ?></td>
				      <td  style="direction: ltr;"><?php
				      $nombre_format_francais = 
				      number_format($reader_select_adjudication_f['Prix_unitaire'], 3, ',', ' ');
				       echo $nombre_format_francais;?></td>
				      <td  style="direction: ltr;"><?php 
				      $nombre_format_francais = 
				      number_format($reader_select_adjudication_f['Prix_net'], 3, ',', ' ');
				      echo $nombre_format_francais; ?></td>
				 </tr>
				<?php
			}


	  	 ?>
	   
	  
	  </tbody>
	</table>
	
	<table class="text-right" cellpadding="10" border="1" style="font-size: 20px;" width="100%"><!-- class="table" -->
	  <thead>
	    <tr>
	      <th scope="col"><?php echo lang('Total_sell')." (".lang('reyal_homany').")"; ?></th>
	      <th scope="col"><?php echo lang('count_lot'); ?></th>
	      <th scope="col"><?php echo lang('taxe_sell'); ?></th>
	      <th scope="col"><?php echo lang('taxe_value')." (".lang('reyal_homany').")"; ?></th>
	    </tr>
	  </thead>
	  <tbody>
	    
	    <tr>
	      <th scope="row"  style="direction: ltr;"><?php 
	      $pay_f = $total_f-$taxe_f;
	      $total_f = number_format($total_f, 3, ',', ' ');
	      echo $total_f; ?></th>
	      <td  style="direction: ltr;"><?php echo $check_select_adjudication_f; ?></td>
	      <td>5 %</td>
	      <td style="direction: ltr;"><?php 
	      $nombre_format_francais = number_format($taxe_f, 2, ',', ' ');
	      echo $nombre_format_francais; ?></td>
	    </tr>
	    <tr>
	      
	      <th colspan="3" class="choice_titles"><?php echo lang('get_value')." (".lang('reyal_homany').")"; ?></th>
	      <td style="direction: ltr;">
	     
	      <?php 
	      $pay_f_w = convertNumber($pay_f,false);
	      //echo number_format($pay_f, 0, '', '');
	      $pay_f_ = number_format($pay_f, 3, ',', ' ');
	      echo $pay_f_; ?>
	      
	      
	     </td>
	    </tr>
	  </tbody>
	</table>

	<table style="width:100%;font-size: 20px;" cellpadding="10">
  <tr>
    <th colspan="3"><?php echo lang('Net_dues_in_letters'); ?></th>
  </tr>
  <tr>
    <td colspan="2"><?php echo $pay_f_w; ?></td>
    <td>Net dues in letters</td>
  </tr>
 
</table>

	<div class="row">
		<div class="col-md-2 col-sm-2 col-xs-2 text-right">
			<svg id="click_me_print" width="40px" height="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 		  viewBox="0 0 429.279 429.279" style="enable-background:new 0 0 429.279 429.279;" xml:space="preserve">

					  <rect x="113.161" y="34.717" style="fill:none;" width="202.957" height="114.953"/>
					<path style="fill:none;" d="M405.279,198.475c0-13.677-11.127-24.805-24.805-24.805H48.805C35.127,173.67,24,184.797,24,198.475
					v7.961h381.279V198.475z M384.123,198.542c-2.23,2.23-5.33,3.51-8.48,3.51c-3.16,0-6.25-1.28-8.49-3.51
					c-2.23-2.24-3.51-5.33-3.51-8.49c0-3.16,1.28-6.25,3.51-8.48c2.24-2.24,5.33-3.52,8.49-3.52c3.15,0,6.25,1.28,8.48,3.52
					c2.24,2.23,3.52,5.32,3.52,8.48C387.642,193.212,386.363,196.302,384.123,198.542z"/>
					<path style="fill:none;" d="M110.846,394.563h207.588V266.533H110.846V394.563z M141.998,292.908h140.514c6.627,0,12,5.372,12,12
					c0,6.627-5.373,12-12,12H141.998c-6.627,0-12-5.373-12-12C129.998,298.281,135.371,292.908,141.998,292.908z M141.998,344.189
					h65.641c6.628,0,12,5.373,12,12c0,6.627-5.372,12-12,12h-65.641c-6.627,0-12-5.373-12-12
					C129.998,349.562,135.371,344.189,141.998,344.189z"/>
					<path style="fill:#73D0F4;" d="M24,327.508c0,13.676,11.127,24.803,24.805,24.803h38.041v-97.777c0-6.628,5.372-12,12-12h231.588
					c6.628,0,12,5.372,12,12v97.777h38.041c13.678,0,24.805-11.126,24.805-24.803v-97.072H24V327.508z"/>
					<path style="fill:#3D6889;" d="M380.475,149.67h-40.357V22.717c0-6.627-5.372-12-12-12H101.161c-6.628,0-12,5.373-12,12V149.67
					H48.805C21.893,149.67,0,171.563,0,198.475v129.033c0,26.91,21.893,48.803,48.805,48.803h38.041v30.252c0,6.627,5.372,12,12,12
					h231.588c6.628,0,12-5.373,12-12V376.31h38.041c26.911,0,48.805-21.893,48.805-48.803V198.475
					C429.279,171.563,407.386,149.67,380.475,149.67z M405.279,327.508c0,13.676-11.127,24.803-24.805,24.803h-38.041v-97.777
					c0-6.628-5.372-12-12-12H98.846c-6.628,0-12,5.372-12,12v97.777H48.805C35.127,352.31,24,341.184,24,327.508v-97.072h381.279
					V327.508z M113.161,34.717h202.957V149.67H113.161V34.717z M24,198.475c0-13.677,11.127-24.805,24.805-24.805h331.67
					c13.678,0,24.805,11.127,24.805,24.805v7.961H24V198.475z M318.434,394.563H110.846V266.533h207.588V394.563z"/>
					<path style="fill:#3D6889;" d="M375.642,178.052c-3.16,0-6.25,1.28-8.49,3.52c-2.23,2.23-3.51,5.32-3.51,8.48
					c0,3.16,1.28,6.25,3.51,8.49c2.24,2.23,5.33,3.51,8.49,3.51c3.15,0,6.25-1.28,8.48-3.51c2.24-2.24,3.52-5.33,3.52-8.49
					c0-3.16-1.279-6.25-3.52-8.48C381.892,179.332,378.793,178.052,375.642,178.052z"/>
					<path style="fill:#3D6889;" d="M141.998,316.908h140.514c6.627,0,12-5.373,12-12c0-6.628-5.373-12-12-12H141.998
					c-6.627,0-12,5.372-12,12C129.998,311.536,135.371,316.908,141.998,316.908z"/>
					<path style="fill:#3D6889;" d="M141.998,368.189h65.641c6.628,0,12-5.373,12-12c0-6.627-5.372-12-12-12h-65.641
					c-6.627,0-12,5.373-12,12C129.998,362.817,135.371,368.189,141.998,368.189z"/>

					</svg>
		</div>
		<div class="col-md-2 col-sm-2 col-xs-2"></div>
		<div class="col-md-4 col-sm-4 col-xs-4 text-center">
		<!--<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="90px"
			 viewBox="0 0 511.993 511.993" style="enable-background:new 0 0 511.993 511.993;" xml:space="preserve">
		<path style="fill:#CCD1D9;" d="M394.662,171.482c-5.89,0-10.671-4.765-10.671-10.656V11.493c0-5.891,4.781-10.664,10.671-10.664
			c5.891,0,10.672,4.773,10.672,10.664v149.333C405.334,166.717,400.553,171.482,394.662,171.482z"/>
		<path style="fill:#E6E9ED;" d="M330.663,191.997H74.669c-2.828,0-5.547-1.125-7.547-3.125s-3.125-4.703-3.125-7.531v-31.999
			c0-5.906,4.781-10.672,10.672-10.672h15.625l39.5-59.249c1.984-2.969,5.312-4.75,8.875-4.75h170.667
			c5.891,0,10.655,4.773,10.655,10.664v60.772l19.547,29.312c2.188,3.266,2.391,7.484,0.531,10.953S334.6,191.997,330.663,191.997z"/>
		<path style="fill:#FFCE54;" d="M394.678,171.497c-3.718,0-7.327-1.953-9.296-5.406L300.055,15.938
			c-2.906-5.117-1.125-11.632,4-14.539c5.125-2.914,11.641-1.125,14.546,4l85.327,150.146c2.922,5.125,1.125,11.64-4,14.546
			C398.271,171.044,396.459,171.497,394.678,171.497z"/>
		<path style="fill:#CCD1D9;" d="M330.663,191.997H74.669c-2.828,0-5.547-1.125-7.547-3.125s-3.125-4.703-3.125-7.531v-31.999
			c0-5.906,4.781-10.672,10.672-10.672h15.625l39.5-59.249c1.984-2.969,5.312-4.75,8.875-4.75h170.667
			c5.891,0,10.655,4.773,10.655,10.664v60.772l19.547,29.312c2.188,3.266,2.391,7.484,0.531,10.953S334.6,191.997,330.663,191.997z"/>
		<path style="fill:#E6E9ED;" d="M511.535,157.717c-1.375-4.484-5.516-7.562-10.203-7.562h-106.67c-2.766,0-5.405,1.062-7.39,2.984
			l-18.234,17.53H53.341c-2.828,0-5.547,1.125-7.547,3.125s-3.125,4.703-3.125,7.547v10.656H31.998
			c-5.891,0-10.656,4.781-10.656,10.672v64c0,5.89,4.766,10.655,10.656,10.655h405.335c5.781,0,10.516-4.609,10.656-10.405
			c1.375-57.547,58.702-96.859,59.265-97.234C511.16,167.076,512.894,162.217,511.535,157.717z"/>
		<g>
			<path style="fill:#434A54;" d="M415.99,64.828h-42.655c-5.891,0-10.672-4.781-10.672-10.671c0-5.891,4.781-10.664,10.672-10.664
				h42.655c5.89,0,10.671,4.773,10.671,10.664C426.661,60.047,421.88,64.828,415.99,64.828z"/>
			<path style="fill:#434A54;" d="M319.991,149.342c0-5.906-4.765-10.672-10.655-10.672H138.668c-5.891,0-10.672,4.766-10.672,10.672
				v21.187h21.343v-10.531h21.328v10.531h21.328v-10.531h21.344v11.312h21.328v-11.312h21.328v11.312h21.343v-11.312h21.327v11.312
				h21.327v-21.202h-0.031C319.975,149.858,319.991,149.592,319.991,149.342z"/>
		</g>
		<polygon style="fill:#5D9CEC;" points="97.403,127.998 319.991,127.998 319.991,95.999 118.73,95.999 "/>
		<g>
			<rect x="170.666" y="95.997" style="fill:#4A89DC;" width="21.328" height="31.999"/>
			<rect x="213.336" y="95.997" style="fill:#4A89DC;" width="21.328" height="31.999"/>
		</g>
		<path style="fill:#5D9CEC;" d="M511.988,511.992V265.403l-16.562-8.281c-2.999-1.5-6.53-1.5-9.53,0l-16.562,8.281l-16.578-8.281
			c-3-1.5-6.531-1.5-9.531,0l-16.562,8.281l-16.562-8.281c-3-1.5-6.547-1.5-9.547,0l-16.562,8.281l-16.562-8.281
			c-3-1.5-6.531-1.5-9.531,0l-16.562,8.281l-16.562-8.281c-3.016-1.5-6.547-1.5-9.546,0l-16.562,8.281l-16.562-8.281
			c-3-1.5-6.547-1.5-9.547,0l-16.562,8.281l-16.562-8.281c-3-1.5-6.531-1.5-9.531,0l-16.562,8.281l-16.562-8.281
			c-3.016-1.5-6.547-1.5-9.546,0l-16.562,8.281l-16.562-8.281c-3-1.5-6.547-1.5-9.547,0l-16.566,8.281l-16.562-8.281
			c-3-1.5-6.531-1.5-9.531,0l-16.562,8.281l-16.562-8.281c-3.016-1.5-6.546-1.5-9.546,0l-16.562,8.281l-16.562-8.281
			c-3-1.5-6.531-1.5-9.547,0L0,265.403v246.589H511.988z"/>
		<path style="fill:#F6BB42;" d="M114.668,415.993c0-0.266-0.016-0.516-0.031-0.781c-0.016-0.077-0.016-0.155-0.016-0.233
			c-0.234-2.391-1.25-4.703-3.078-6.531L90.215,387.12c-4.171-4.172-10.921-4.156-15.093,0c-4.156,4.172-4.156,10.922,0,15.094
			l13.796,13.78l-13.796,13.797c-4.156,4.156-4.156,10.922,0,15.078c2.094,2.094,4.812,3.125,7.547,3.125s5.453-1.047,7.546-3.125
			l21.328-21.328c1.828-1.828,2.843-4.141,3.078-6.516c0-0.094,0-0.172,0.016-0.25C114.652,416.509,114.668,416.259,114.668,415.993z"
			/>
		<path style="fill:#FFCE54;" d="M146.667,447.993c-10.984,0-22.843-4.188-35.249-12.453c-8.781-5.859-14.703-11.75-14.953-12
			c-4.172-4.172-4.172-10.921,0-15.093c2.5-2.5,25.171-24.453,50.202-24.453c20.625,0,35.718,4.641,44.874,13.797
			c8.047,8.047,8.453,16.578,8.453,18.202C199.995,417.306,199.401,447.993,146.667,447.993z"/>
		<path style="fill:#434A54;" d="M175.542,397.791c4.172,4.156,4.172,10.922,0,15.078c-4.172,4.171-10.922,4.171-15.078,0
			c-4.172-4.156-4.172-10.922,0-15.078C164.62,393.619,171.37,393.619,175.542,397.791z"/>
		<path style="fill:#F6BB42;" d="M417.739,362.667l13.797-13.797c4.172-4.156,4.172-10.922,0-15.078
			c-4.172-4.172-10.922-4.172-15.077,0l-21.344,21.328c-1.812,1.828-2.844,4.141-3.078,6.516c0,0.094,0,0.172,0,0.25
			c-0.031,0.25-0.047,0.516-0.047,0.781c0,0.25,0.016,0.516,0.047,0.781c0,0.078,0,0.156,0,0.234c0.234,2.39,1.266,4.702,3.078,6.53
			l21.344,21.328c2.077,2.078,4.812,3.125,7.53,3.125c2.734,0,5.469-1.047,7.547-3.125c4.172-4.172,4.172-10.922,0-15.094
			L417.739,362.667z"/>
		<path style="fill:#FFCE54;" d="M359.991,394.666c-52.718,0-53.327-30.702-53.327-31.999c0-1.625,0.406-10.156,8.453-18.219
			c9.155-9.141,24.249-13.781,44.874-13.781l0,0c11,0,22.859,4.188,35.249,12.453c8.781,5.859,14.719,11.75,14.969,12
			c4.156,4.172,4.156,10.921,0,15.093C407.693,372.713,385.022,394.666,359.991,394.666z"/>
		<path style="fill:#434A54;" d="M331.116,344.448c-4.156,4.172-4.156,10.922,0,15.094c4.172,4.156,10.922,4.156,15.094,0
			c4.156-4.172,4.156-10.922,0-15.094C342.038,340.292,335.288,340.292,331.116,344.448z"/>

		</svg>-->

	</div>
	<div class="col-md-4 col-sm-4 col-xs-4">
			
	</div>
	</div>
</div>
<?php
 } 
 ?>
<script type="text/javascript">
	/*
	var doc = new jsPDF();

	var specialElementHandlers = {
      '.out': function(element, renderer){
       return true;
    }
    };
	*/

	$( "#click_me_print" ).click(function() {
	$(this).hide();
    $("body").print();
	$(this).show();	
	/*
	console.log('<table>'+$('#table_adjudication_print_f').html()+'</table>');
	doc.fromHTML('<table>'+$('#table_adjudication_print_f').html()+'</table>', 15, 15, {
	        'width': 170,
	            'elementHandlers': specialElementHandlers
	    });
	    doc.save('sample-file.pdf');
	
	
	*/
	});
</script>
<style type="text/css">
	*,body{
		direction: rtl;
	}
	@media print {
		.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col,
		.col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm,
		.col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md,
		.col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg,
		.col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl,
		.col-xl-auto {
		  position: relative;
		  width: 100%;
		  min-height: 1px;
		  padding-right: 15px;
		  padding-left: 15px;
		}
		.col-md {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .col-md-auto {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: none;
  }
  .col-md-1 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }
  .col-md-2 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-md-3 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .col-md-5 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 41.666667%;
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
  }
  .col-md-6 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-md-7 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
  }
  .col-md-8 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 66.666667%;
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
  }
  .col-md-9 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .col-md-10 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 83.333333%;
    flex: 0 0 83.333333%;
    max-width: 83.333333%;
  }
  .col-md-11 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
  }
  .col-md-12 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  #top_d_f_table{
	font-size: 20px;
}
	#table_adjudication_print_f table thead th,#table_adjudication_print_f table td{
				font-size: 20px;
			}
			#print_page table{
	margin-bottom: 15px;
	margin-top: 15px;
}
	}
</style>
<style type="text/css">
			#table_adjudication_print_f table thead th,#table_adjudication_print_f table td{
				font-size: 20px;
			}
			#print_page table{
	margin-bottom: 15px;
	margin-top: 15px;
}
@media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}
#top_d_f_table{
	font-size: 20px;
}
		</style>

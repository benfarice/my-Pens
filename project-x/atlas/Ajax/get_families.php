<?php 
session_start();
$lang = '../includes/languages/';
include_once $lang.$_SESSION['Lang'].'.php';

include "../connect.php";


if(isset($_REQUEST['add_family'])){
	$query_add = "insert into FAMILLE (CODE_FAM,NOM_FAM,CODE_GROUPE) values((select max(code_fam)+1 from FAMILLE),'$_REQUEST[add_family]','GR_01')";
	$result_query_add_family = sqlsrv_query($con,$query_add);
}


if(isset($_REQUEST['delete_family'])){
	$query_delete = "delete from FAMILLE where CODE_FAM = ".$_REQUEST['delete_family'];
	$result_query_delete_family = sqlsrv_query($con,$query_delete);
}

if(isset($_REQUEST['update_new_value'])){
	$query_update ="update FAMILLE set NOM_FAM = '$_REQUEST[update_new_value]' where CODE_FAM = ".$_REQUEST['update_code_family'];
	$result_query_update_family = sqlsrv_query($con,$query_update);
}






$query_family = "SELECT  CODE_FAM,NOM_FAM FROM FAMILLE ORDER BY CODE_FAM DESC";

$result_query_family = sqlsrv_query($con,$query_family);

//$check_family = sqlsrv_num_rows($result_query_family);
?>
	<table class="table" id="tab_families" style="width:100%">
			  <thead class="entete">
			    <tr>
			       <th scope="col"><?php echo lang('update'); ?></th>
				   <th scope="col"><?php echo lang('fish_type'); ?></th>   
				   <th scope="col"><?php echo lang('delete_operation'); ?></th>
			    </tr>
			  </thead>
			  <tbody id="table_families">
<?php
while($reader_query_family = sqlsrv_fetch_array($result_query_family, SQLSRV_FETCH_ASSOC))
{ 
	?>
	 <tr>
			      <th scope="row" onclick="modifier_family('<?php echo $reader_query_family["CODE_FAM"]; ?>','<?php echo $reader_query_family["NOM_FAM"]; ?>');">
			      	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="50px" height="50px">
					<path d="M397.736,78.378c6.824,0,12.358-5.533,12.358-12.358V27.027C410.094,12.125,397.977,0,383.08,0H121.641    c-3.277,0-6.42,1.303-8.739,3.62L10.527,105.995c-2.317,2.317-3.62,5.461-3.62,8.738v370.239C6.908,499.875,19.032,512,33.935,512    h349.144c14.897,0,27.014-12.125,27.014-27.027V296.289c0.001-6.824-5.532-12.358-12.357-12.358    c-6.824,0-12.358,5.533-12.358,12.358v188.684c0,1.274-1.031,2.311-2.297,2.311H33.936c-1.274,0-2.311-1.037-2.311-2.311v-357.88    h75.36c14.898,0,27.016-12.12,27.016-27.017V24.716H383.08c1.267,0,2.297,1.037,2.297,2.311V66.02    C385.377,72.845,390.911,78.378,397.736,78.378z M109.285,100.075c0,1.269-1.032,2.301-2.3,2.301H49.107l60.178-60.18V100.075z" fill="#D80027"/>
					<path d="M492.865,100.396l-14.541-14.539c-16.304-16.304-42.832-16.302-59.138,0L303.763,201.28H103.559    c-6.825,0-12.358,5.533-12.358,12.358c0,6.825,5.533,12.358,12.358,12.358h175.488l-74.379,74.379H103.559    c-6.825,0-12.358,5.533-12.358,12.358s5.533,12.358,12.358,12.358h76.392l-0.199,0.199c-1.508,1.508-2.598,3.379-3.169,5.433    l-19.088,68.747h-53.936c-6.825,0-12.358,5.533-12.358,12.358s5.533,12.358,12.358,12.358h63.332c0.001,0,2.709-0.306,3.107-0.41    c0.065-0.017,77.997-21.642,77.997-21.642c2.054-0.57,3.926-1.662,5.433-3.169l239.438-239.435    C509.168,143.228,509.168,116.7,492.865,100.396z M184.644,394.073l10.087-36.326l26.24,26.24L184.644,394.073z M244.69,372.752    l-38.721-38.721l197.648-197.648l38.722,38.721L244.69,372.752z M475.387,142.054l-15.571,15.571l-38.722-38.722l15.571-15.571    c6.669-6.668,17.517-6.667,24.181,0l14.541,14.541C482.054,124.54,482.054,135.388,475.387,142.054z" fill="#D80027"/>
					</svg>

			      </th>
			      <td><?php echo $reader_query_family['NOM_FAM']; ?></td>
			     
			      <td onclick="delete_family('<?php echo $reader_query_family["CODE_FAM"]; ?>','<?php echo $reader_query_family["NOM_FAM"]; ?>');"> 
			      		<svg version="1.1" width="50px" height="50px" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
						<polygon style="fill:#E21B1B;" points="404.176,0 256,148.176 107.824,0 0,107.824 148.176,256 0,404.176 107.824,512 256,363.824 
						404.176,512 512,404.176 363.824,256 512,107.824 "/>
			      </td>
			   
			    </tr>
	<?php
		
}
?>
	 </tbody>
			</table>

<?php
session_start();
$lang = '../includes/languages/';
include_once $lang.$_SESSION['Lang'].'.php';

include "../connect.php";


if(isset($_REQUEST['add_fac'])){
	$myArray_num_adjudication = $_REQUEST['les_num_adjudication_array'];
	//print_r($myArray);
	$myArray_valeurs_adjudication = $_REQUEST['les_valeurs_adjudication'];

	$total_f = 0;
	foreach ($myArray_valeurs_adjudication as $value){
    //commandes
	$total_f+=$value;
	}
	$taxe_f = ($total_f/100)*5;

	$query_numero_facture = "select count(f.Num_facture) as nombre from facture_vendeur f";
	$numero_facture = "AV".date('yn');
	$i = 0;
	$params_query_numero_facture = array();
	$options_query_numero_facture =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_numero_facture = sqlsrv_query($con,$query_numero_facture,$params_query_numero_facture,
	$options_query_numero_facture);
	while($reader_numero_facture= sqlsrv_fetch_array($result_query_numero_facture, SQLSRV_FETCH_ASSOC)){
		$i = $reader_numero_facture['nombre'];
	}





	$query_numero_facture = "AV".date('yn')."-".$i;
	$ntRes_select_numero_facture2=0;
	do{
	$query_numero_facture = "AV".date('yn')."-".$i;
	$i++;
	$query_numero_facture_2 = "select f.Num_facture from facture_vendeur f where f.Num_facture = '$query_numero_facture'";
	//$num_lot = 'L000000';
	//$i = 0;
	//echo $query_num_lot2;
	$params_numero_facture_2 = array();
	$options_numero_facture_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result_query_numero_facture_2 = sqlsrv_query($con,$query_numero_facture_2,
		$params_numero_facture_2,
	$options_numero_facture_2);
	$ntRes_select_numero_facture2 = sqlsrv_num_rows($result_query_numero_facture_2);
	}while($ntRes_select_numero_facture2>0);





	$query_insert_facture = "insert into
	facture_vendeur(Code_Vendeur,Date_F,Num_facture,pourcentage_taxe,Taxe,Total)
	values('".$_REQUEST['id_vendeur']."','".date("Y-m-d")."','$query_numero_facture',5,$taxe_f,$total_f)";
	//echo $query_insert_facture;
	$result_add = sqlsrv_query($con,$query_insert_facture);

	foreach ($myArray_num_adjudication as $value){
    //commandes
		$query_add_details_ad_f ="insert into details_facture_vendeur(Num_facture,num_adjudication)
		values('$query_numero_facture','$value')";
		$result__add_details_ad_f = sqlsrv_query($con,$query_add_details_ad_f);
	}
	$query_id_fac ="select f.Id_Fac from facture_vendeur f where f.Num_facture = '$query_numero_facture'";
	$query_id_facture = null;
	$stmt_query_select_id_f=sqlsrv_query($con,$query_id_fac);
	while($row_query_select_id = sqlsrv_fetch_array($stmt_query_select_id_f, SQLSRV_FETCH_ASSOC)){
		$query_id_facture = $row_query_select_id['Id_Fac'];
	}
	echo $query_id_facture;

}else{

$query_select="select d.Num_adjudication,d.num_lot,e.Nom_espece,d.Code_Acheteur,b.name_ar
,d.Poids_net,d.Prix_unitaire,d.Prix_net from adjudication d
inner join LOT l on l.Num_lot = d.num_lot
inner join ESPECE e on e.Code_espece = l.Code_espece
inner join BUYER_SELLER b on b.ID_B_S = d.Code_Acheteur
where  d.Num_adjudication in
(select d.Num_adjudication from ADJUDICATION d left join details_facture_vendeur dv
on dv.num_adjudication = d.Num_adjudication where dv.num_adjudication is null)
 and 1=2
";

if(isset($_REQUEST['id_vendeur']) && isset($_REQUEST['date_selected'])){
	$query_select="select d.Num_adjudication,d.num_lot,e.Nom_espece,d.Code_Acheteur,b.name_ar
	,d.Poids_net,d.Prix_unitaire,d.Prix_net from adjudication d
	inner join LOT l on l.Num_lot = d.num_lot
	inner join ESPECE e on e.Code_espece = l.Code_espece
	inner join BUYER_SELLER b on b.ID_B_S = d.Code_Acheteur
	where cast(d.Date_adjudication as date) =
	convert(date,('".$_REQUEST['date_selected']."'),105) 
	and l.Code_vendeur = '".$_REQUEST['id_vendeur']."' and d.Num_adjudication in
	(select d.Num_adjudication from ADJUDICATION d left join details_facture_vendeur dv
	on dv.num_adjudication = d.Num_adjudication where dv.num_adjudication is null)";
}
//echo $query_select;
$params_query_select = array();
$options_query_select =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_select=sqlsrv_query($con,$query_select,$params_query_select,$options_query_select);
$ntRes_query_select = sqlsrv_num_rows($stmt_query_select);
if($ntRes_query_select<1){
	?>
	<!--<div class="alert alert-info" role="alert" id="you_have_to_search_alert">-->
	<div class="resAff" style="text-align:center;min-height:200px;font-size:24px;padding-top: 50px;">
		<?php echo lang('you_have_to_search'); ?>
	</div>

	<!--</div>-->
	<?php
}
if($ntRes_query_select>0){
?>
<table class="table" id="seller_f_table">
			  <thead class="entete">
			    <tr>
			      <th scope="col" class="text-center">
			      	<div id="select_all_checkbox_container">
					 <div class="pretty p-default">
				        <input type="checkbox"  id="select_all_checkbox" />
				        <div class="state p-primary">
				            <label><!--<?php //echo lang('select_all'); ?>-->&nbsp;</label>
				        </div>
				    </div>
					</div>
			      </th>
			      <th scope="col"><?php echo lang('operation_th'); ?></th>
			      <th scope="col"><?php echo lang('lot_id'); ?></th>
			      <th scope="col"><?php echo lang('the_type'); ?></th>
			      <th scope="col"><?php echo lang('search_buyer'); ?></th>
			      <th scope="col"><?php echo lang('Qte'); ?></th>
			      <th scope="col"><?php echo lang('price'); ?></th>
			      <th scope="col"><?php echo lang('Total'); ?></th>
			    </tr>
			  </thead>
			  <tbody  id="details_f">
<?php
while($row_query_select = sqlsrv_fetch_array($stmt_query_select, SQLSRV_FETCH_ASSOC)){
?>
				 <tr>
			      <th scope="row">
			      	  <div class="pretty p-default">
				        <input type="checkbox" name="select_checkbox_f" />
				        <div class="state p-primary">
				            <label>&nbsp;</label>
				        </div>
				    </div>
			      </th>
			      <td class="n_adjudication_class"><?php echo $row_query_select['Num_adjudication']; ?></td>
			      <td><?php echo $row_query_select['num_lot']; ?></td>
			      <td><?php echo $row_query_select['Nom_espece']; ?></td>
			      <td><?php echo $row_query_select['name_ar']; ?></td>
			      <td style="direction: ltr;"><?php
			      $nombre_format_francais = number_format($row_query_select['Poids_net'], 3, ',', ' ');
			      echo $nombre_format_francais; ?> kg</td>
			      <td style="direction: ltr;"><?php
			      $nombre_format_francais = number_format($row_query_select['Prix_unitaire'], 3, ',', ' ');
			      echo $nombre_format_francais." ".lang('reyal_homany'); ?></td>
			      <td class="value_adjudication" style="display: none;">
			      	<?php echo $row_query_select['Prix_net']; ?>
			      </td>
			      <td  style="direction: ltr;"><?php
			      $nombre_format_francais = number_format($row_query_select['Prix_net'], 3, ',', ' ');
			      echo $nombre_format_francais." ".lang('reyal_homany'); ?></td>

			    </tr>
<?php
}
?>



			  </tbody>
			</table>
<?php
}
}
?>

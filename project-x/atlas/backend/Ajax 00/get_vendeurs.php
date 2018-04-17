<?php 
//echo "yeah";
include "../connect.php";

$query_sellers = "SELECT  ID_B_S ,NAME_OR_CN FROM BUYER_SELLER";

if(isset($_REQUEST['get_families'])){
	$query_sellers = "SELECT  CODE_FAM,NOM_FAM FROM FAMILLE";
}


if(isset($_REQUEST['searched_value'])){
	$query_sellers = "SELECT  ID_B_S ,NAME_OR_CN FROM BUYER_SELLER s where s.LICENCE_NUMBER 
	like '%".$_REQUEST['searched_value']."%' 
	or 
	s.ID_B_S like '%".$_REQUEST['searched_value']."%' 
	or s.NAME_OR_CN like '%".$_REQUEST['searched_value']."%'";
//echo $query_sellers;
}
$params_query_sellers = array();
$options_query_sellers =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_sellers=sqlsrv_query($con,$query_sellers,$params_query_sellers,$options_query_sellers);
$ntRes_query_sellers = sqlsrv_num_rows($stmt_query_sellers);

while($row_query_sellers = sqlsrv_fetch_array($stmt_query_sellers, SQLSRV_FETCH_ASSOC)){

	if(isset($_REQUEST['get_families'])){
	?>
	<div class="family_name_div text-center mySlides">
		<?php echo $row_query_sellers['NOM_FAM']; ?>
		<span class="id_family" style="display: none;"><?php echo $row_query_sellers['CODE_FAM']; ?></span>
	</div>
	<?php
	}else{


	?>
	<div class="vendeur_name_div text-center mySlides">
		<?php echo $row_query_sellers['NAME_OR_CN']; ?>
		<span class="id_seller" style="display: none;"><?php echo $row_query_sellers['ID_B_S']; ?></span>
	</div>
	<?php
    }

}

?>
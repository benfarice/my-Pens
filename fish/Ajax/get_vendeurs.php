<?php 
session_start();
//echo "yeah";
include "../connect.php";

$query_sellers = "SELECT  ID_B_S ,name_".$_SESSION['Lang']." ,bs.LICENCE_NUMBER
  FROM BUYER_SELLER bs where bs.TYPE_B_S='SELLER' and bs.ENABLED = 1";


if(isset($_REQUEST['report_vendeur'])){

$query_sellers = "SELECT  distinct ID_B_S ,name_".$_SESSION['Lang']." ,bs.LICENCE_NUMBER
FROM BUYER_SELLER bs inner join LOT l
on l.Code_vendeur = bs.ID_B_S
where bs.TYPE_B_S='SELLER' and bs.ENABLED = 1
 and l.Date_lot = '".date('d/m/Y')."'";
}

if(isset($_REQUEST['date_selected'])){
	$query_sellers = "SELECT  ID_B_S ,name_".$_SESSION['Lang']." ,bs.LICENCE_NUMBER
    FROM BUYER_SELLER bs where bs.TYPE_B_S='SELLER' and bs.ENABLED = 1
	and ID_B_S in 
	(select l.Code_vendeur from LOT l inner join ADJUDICATION ad 
	on ad.num_lot = l.Num_lot and ad.Num_adjudication not in (select distinct 
	 v.num_adjudication from details_facture_vendeur v ) and cast(ad.Date_adjudication as date)='".$_REQUEST['date_selected']."')";
}

if(isset($_REQUEST['searched_value'])){
	$query_sellers = "SELECT  ID_B_S ,name_".$_SESSION['Lang']." ,s.LICENCE_NUMBER  FROM BUYER_SELLER s 
	 where s.TYPE_B_S='SELLER' and s.ENABLED = 1 and 
	 (s.LICENCE_NUMBER 
	like '%".$_REQUEST['searched_value']."%' 
	or 
	s.ID_B_S like '%".$_REQUEST['searched_value']."%' 
	or s.name_".$_SESSION['Lang']." like '%".$_REQUEST['searched_value']."%' or s.name_ar 
	like '%".$_REQUEST['searched_value']."%')";
	if(isset($_REQUEST['report_vendeur'])){
		$query_sellers="SELECT ID_B_S ,name_ar ,s.LICENCE_NUMBER FROM BUYER_SELLER s  inner join LOT l
		on l.Code_vendeur = s.ID_B_S where s.TYPE_B_S='SELLER' 
		and s.ENABLED = 1 and l.etat=1 and (s.LICENCE_NUMBER like '%s180003%' or 
		s.ID_B_S like '%".$_REQUEST['searched_value']."%' or s.name_ar like '%".$_REQUEST['searched_value']."%' or s.name_ar like '%".$_REQUEST['searched_value']."%')";
	}
//echo $query_sellers;
}
if(isset($_REQUEST['get_families'])){
	$query_sellers = "SELECT  CODE_FAM,NOM_FAM FROM FAMILLE";
}
if(isset($_REQUEST['id_family'])){
	$query_sellers = "SELECT  CODE_FAM,NOM_FAM FROM FAMILLE f where 
	 f.CODE_FAM like '%".$_REQUEST['id_family']."%' 
	 or f.NOM_FAM like '%".$_REQUEST['id_family']."%' 
	 or f.CODE_GROUPE 
	like '%".$_REQUEST['id_family']."%'";
	//echo $query_sellers;
}

if(isset($_REQUEST['get_espece'])){
	//$query_sellers = "select e.Nom_espece,e.Code_espece 
	//from ESPECE e where e.code_famille = ".$_REQUEST['id_family'];
	$query_sellers = "select e.Nom_espece,e.Code_espece 
	from ESPECE e ";
	if(isset($_REQUEST['searched_value_espece']) && $_REQUEST['searched_value_espece']!=""){
		$query_sellers = "select e.Nom_espece,e.Code_espece 
		from ESPECE e where e.Nom_An 
		like '%".$_REQUEST['searched_value_espece']."%'
		or e.Code 
		like '%".$_REQUEST['searched_value_espece']."%' or 
		e.Code_espece like '%".$_REQUEST['searched_value_espece']."%'
		or e.Nom_espece like '%".$_REQUEST['searched_value_espece']."%' 
		or e.SDESC like '%".$_REQUEST['searched_value_espece']."%' ";
	}
	if(isset($_REQUEST['id_family'])){
		$query_sellers = "select e.Nom_espece,e.Code_espece 
	    from ESPECE e where e.code_famille = ".$_REQUEST['id_family'];
	}
}
//echo $query_sellers;

if(isset($_REQUEST['get_data_buyer'])){
	$query_sellers = "SELECT  ID_B_S ,name_".$_SESSION['Lang']." ,bs.LICENCE_NUMBER
  	FROM BUYER_SELLER bs where bs.TYPE_B_S='BUYER' and bs.ENABLED = 1";
  	if(isset($_REQUEST['searched_buyer'])){
  		$query_sellers = "SELECT  ID_B_S ,name_".$_SESSION['Lang']." ,
  		s.LICENCE_NUMBER  FROM BUYER_SELLER s 
	 where s.TYPE_B_S='BUYER' and s.ENABLED = 1 and 
	 (s.LICENCE_NUMBER 
	like '%".$_REQUEST['searched_buyer']."%' 
	or 
	s.ID_B_S like '%".$_REQUEST['searched_buyer']."%' 
	or s.name_".$_SESSION['Lang']." like '%".$_REQUEST['searched_buyer']."%' or s.name_ar 
	like '%".$_REQUEST['searched_buyer']."%')";
  	}
}

if(isset($_REQUEST['report_buyers'])){
	$query_sellers = "SELECT  distinct ID_B_S ,name_ar ,bs.LICENCE_NUMBER
	FROM BUYER_SELLER bs inner join ADJUDICATION adj
	on adj.Code_Acheteur = bs.ID_B_S
	where bs.TYPE_B_S='BUYER' and bs.ENABLED = 1
	and adj.Date_adjudication = '".date('d/m/Y')."'";
	if(isset($_REQUEST['searched_value'])){
		$query_sellers = "SELECT  distinct ID_B_S ,name_ar ,bs.LICENCE_NUMBER
		FROM BUYER_SELLER bs inner join ADJUDICATION adj
		on adj.Code_Acheteur = bs.ID_B_S
		where bs.TYPE_B_S='BUYER' and bs.ENABLED = 1
		and adj.Date_adjudication = '".date('d/m/Y')."'
		 and (bs.name_ar like '%".$_REQUEST['searched_value']."%' or bs.LICENCE_NUMBER 
		 like '%".$_REQUEST['searched_value']."%'
		 or bs.ID_B_S like '%".$_REQUEST['searched_value']."%' or bs.name_en 
		 like '%".$_REQUEST['searched_value']."%')";
	}
}

$params_query_sellers = array();
$options_query_sellers =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_sellers=sqlsrv_query($con,$query_sellers,$params_query_sellers,$options_query_sellers);
$ntRes_query_sellers = sqlsrv_num_rows($stmt_query_sellers);

while($row_query_sellers = sqlsrv_fetch_array($stmt_query_sellers, SQLSRV_FETCH_ASSOC)){

	if(isset($_REQUEST['get_families'])){
	?>
	<div class="family_name_div text-center mySlides spece_name_div">
		<?php echo $row_query_sellers['NOM_FAM']; ?>
		<span class="id_family" style="display: none;"><?php echo $row_query_sellers['CODE_FAM']; ?></span>
	</div>
	<?php
	}else if(isset($_REQUEST['get_espece'])){
	?>
	<div class="family_name_div text-center mySlides_espece">
		<span class="titre_espece"><?php echo $row_query_sellers['Nom_espece']; ?></span>
		
		<span class="Code_espece" style="display: none;"><?php echo $row_query_sellers['Code_espece']; ?></span>
	</div>
	<?php

	}else 
	if(isset($_REQUEST['get_data_buyer']) || isset($_REQUEST['report_buyers'])){
		//for($b=0;$b<20;$b++){
	?>
	<div class="buyer_name_div text-center mySlides">
		<p id="buyer_has_selected">
		<?php 
		if( $_SESSION['Lang'] == 'ar'){
			echo $row_query_sellers['name_ar']/*.$b*/;
		}else{
			echo $row_query_sellers['name_en'];
		}
		 ?>
		</p>
		<p class="id_buyer none" style="/*display: none;*/"><?php echo $row_query_sellers['ID_B_S']; ?></p>
		<p class="none"  style="/*display: none;*/"><?php echo $row_query_sellers['LICENCE_NUMBER']; ?></p>
	</div>
	<?php
		//}
	}
	else
	{


	
	//for($b=0;$b<20;$b++){
	?>
	<div class="vendeur_name_div text-center mySlides">
		<p id="seller_has_selected">
		<?php 
		if( $_SESSION['Lang'] == 'ar'){
			echo $row_query_sellers['name_ar']/*.$b*/;
		}else{
			echo $row_query_sellers['name_en'];
		}
		 ?>
		</p>
		<p class="id_seller none" style="/*display: none;*/"><?php echo $row_query_sellers['ID_B_S']; ?></p>
		<p class="none"  style="/*display: none;*/"><?php echo $row_query_sellers['LICENCE_NUMBER']; ?></p>
	</div>
	<?php
	//}
    }

}

?>
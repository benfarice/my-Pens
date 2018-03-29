<?php require_once('connexion.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Plateforme de gestion de distribution</title>
<link rel="stylesheet" href="css_y/bootstrap.min.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<?php 
if(!isset($_SESSION))
{
session_start();
}
$IdDepot=$_SESSION['IdDepot'];	
if(isset($_GET['ref'])){ 
if(isset($_GET['delete_id'])){
$query_delete = 
"delete from detailMouvements where iddetailMouvement = $_GET[delete_id]";
$result_delete = sqlsrv_query($conn,$query_delete) or die(sqlsrv_errors());
}
$query_select_mov = "select m.type,m.reference,
cast(m.date as date) as date_,m.fournisseur,m.livreur 
from mouvements m where m.reference = '$_GET[ref]'";
//echo $query_select_mov;
$params_select_mov = array();
$options_select_mov =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
$result__select_mov = sqlsrv_query($conn,$query_select_mov,$params_select_mov,
$options_select_mov) or die( 
print_r(sqlsrv_errors(), true));
?>
<div class="row" style="font-size: 20px;padding: 25px;">
<?php
while($reader_select_mov = sqlsrv_fetch_array($result__select_mov, SQLSRV_FETCH_ASSOC)){ 
?>
	<div class="col-3">
		<?php echo $reader_select_mov['type'];?>
	</div>
	<div class="col-3">
		<?php echo $reader_select_mov['reference'];?>
	</div>
	<div class="col-6 text-right">
		<?php echo $reader_select_mov['date_']->format('d/m/Y');?>
	</div>
</div>
<div class="row" style="font-size: 20px;padding: 25px;">
	<div class="col-4">
	<b>fournisseur : </b>
	<?php 

	$query_select_fournisseur = "select f.designation from fournisseurs f
	where f.idFournisseur = ".$reader_select_mov['fournisseur'];
	//echo $query_select_fournisseur;
	$params_select_fournisseur = array();
	$options_select_fournisseur =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result__select_fournisseur = 
	sqlsrv_query($conn,$query_select_fournisseur,$params_select_fournisseur,$options_select_fournisseur) 
	or die( 
	print_r(sqlsrv_errors(), true));
	while($reader_select_fournisseur = sqlsrv_fetch_array($result__select_fournisseur, SQLSRV_FETCH_ASSOC)){
		echo $reader_select_fournisseur['designation'];
	}
?>

	</div>
	<div class="col-4"></div>
	<div class="col-4 text-right">
	<b>livreur :</b>
	<?php echo $reader_select_mov['livreur'];?>
	</div>
</div>

<form action="mise_a_jour_mouvements.php?ref=<?php echo $_GET['ref'];?>"
	method="POST">
  <div class="row">
  <div class="col-6">
  <div class="form-group">
    <label for="select_ref">Ref</label>
    <input type="text" class="form-control" id="select_ref" name="select_ref"
     placeholder="Ref">
  </div>
  </div>
  <div class="col-6"></div>
  </div>
</form>

<?php
if(isset($_POST['ajouter'])){
//$IdDepot
$sql_insert="insert into detailMouvements(idMouvement,idArticle,UniteVente,qte,pa,idDepot)
values((select m.idMouvement from mouvements m where m.reference='$_GET[ref]'),
(select a.IdArticle from articles a where 
a.Designation = '$_POST[article_name]'),'$_POST[optradio_type]',
 $_POST[Qte],$_POST[PA],".$IdDepot.")";
 //echo $sql_insert;
$result_add = sqlsrv_query($conn,$sql_insert) or die(sqlsrv_errors());
}
?>
<!-- Trigger the modal with a button -->


<!-- Modal -->

<form  action="mise_a_jour_mouvements.php?ref=<?php echo $_GET['ref'];?>"
 method="POST">
	<div class="row">
  <div class="form-group col-4">
    <label for="article_name">Article Name</label>

<?php 

if(isset($_POST['select_ref']) && ($_SESSION['searched_ref'] != $_POST['select_ref'])){
	$_SESSION['searched_ref'] = $_POST['select_ref'];
	 $query_select_id_article = "select a.IdArticle from articles a where a.Reference = '$_POST[select_ref]'";
	$id_article = 0;
    $params_select_id_article = array();
	$options_select_id_article =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result__select_id_article = 
	sqlsrv_query($conn,$query_select_id_article,$params_select_id_article,$options_select_id_article) 
	or die( 
	print_r(sqlsrv_errors(), true));
	while($reader_select_id_article = sqlsrv_fetch_array($result__select_id_article, SQLSRV_FETCH_ASSOC)){
		$idArticle=$reader_select_id_article['IdArticle'];
	}


    ?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class="modal-title">déjà existe ! vouliez-vous le modifier ?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <button type="button" class="btn btn-primary" data-dismiss="modal" 
        onClick="MyWindow2=window.open('update_article.php?ref=<?php  echo 
		    	$idArticle; ?>&mov_ref=<?php echo $_GET['ref']?>','MyWindow2','toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=no,resizable=no,width=400,height=330,left=40,top=160');
				return false;"> Modifier
        </button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Ajouter Nouveau
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    <?php
	$sql_check_f_exist = "select count(d.idArticle) as 
	check_ from detailMouvements d where d.idMouvement = (select m.idMouvement 
	from mouvements m where m.reference = '$_GET[ref]') and d.idArticle = 
	(select a.IdArticle from articles a where a.Reference = '$_POST[select_ref]')";
    $params_check_f_exist = array();
    $check = 0;
    //echo $sql_check_f_exist;
	$options_check_f_exist =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result__check_f_exist = 
	sqlsrv_query($conn,$sql_check_f_exist,$params_check_f_exist,$options_check_f_exist) 
	or die( 
	print_r(sqlsrv_errors(), true));
	while($reader_check_f_exist = sqlsrv_fetch_array($result__check_f_exist, SQLSRV_FETCH_ASSOC)){
		$check = $reader_check_f_exist['check_'];
	}
	$boolean = false;
	if($check!=0) {
	?>
	<script type="text/javascript">
		//alert("ok");
		$(document).ready(function(){
		$('#myModal').modal('show');
		});
	</script>
	<?php
	}
	
    
    $query_article_designation = 
    "select a.Designation from articles a where a.Reference = '$_POST[select_ref]'";
	$params_article_designation = array();
	$options_article_designation =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$result__article_designation = 
	sqlsrv_query($conn,$query_article_designation,$params_article_designation,$options_article_designation) 
	or die( 
	print_r(sqlsrv_errors(), true));
	while($reader_article_designation = sqlsrv_fetch_array($result__article_designation, SQLSRV_FETCH_ASSOC)){
?>
<input  class="form-control" name="article_name" id="article_name" 
  value="<?php echo $reader_article_designation['Designation']; ?>">

<?php
}


}
else{
?>
<input  class="form-control" name="article_name" id="article_name" 
  placeholder="Article | Désignation">
  <?php
}

   ?>
  </div>
  <div class="form-group col-2">
    <label for="Qte">Qte</label>
    <input  class="form-control" name="Qte" id="Qte" placeholder="Qte" required="required">
  </div>
  <div class="form-group col-2">
    <label for="PA">PA</label>
    <input  class="form-control" name="PA" id="PA" placeholder="PA" required="required">
  </div>
  <div class="col-2">
 	<div class="radio ">
	  <label><input type="radio" name="optradio_type" value="Colisage" checked="checked">
	  &#32;&#32;Colisage</label>
    </div>
  <div class="radio">
	  <label><input type="radio" name="optradio_type" value="Pièce">
	  &#32;&#32;Pièce</label>
  </div>
  </div>
  <div class="col-2">
  	<button type="submit" name="ajouter" class="btn btn-primary">Ajouter</button>
  </div>
  </div>
</form>




<?php
   
}
$query_select_article_qte_pa = "select d.iddetailMouvement,d.idArticle,d.qte,d.pa,isnull(d.UniteVente,'null')
as type 
from detailMouvements d where 
d.idMouvement = 
(select m.idMouvement from mouvements m where m.reference = '$_GET[ref]')";
//echo $query_select_article_qte_pa;
$params_article_qte_pa = array();
$options_article_qte_pa =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
$result_article_qte_pa = sqlsrv_query($conn,$query_select_article_qte_pa
,$params_article_qte_pa,
$options_article_qte_pa) or die( 
print_r(sqlsrv_errors(), true));
?>
<table class="table">
	<thead class="thead-dark">
		<tr>
		<th scope="col">Réf</th>
		<th scope="col">Désignation</th>
		<th scope="col">Type</th>
		<th scope="col">Qte</th>
		<th scope="col">PA</th>
		<th scope="col">Modifier</th>
		</tr>
	</thead>
	<tbody>
		
	
<?php
while($reader_article_qte_pa = sqlsrv_fetch_array($result_article_qte_pa, SQLSRV_FETCH_ASSOC)){ 
?>
		<tr>
<?php
$query_select_article_ref_desig = "select a.Reference,a.Designation from articles a 
where a.IdArticle = $reader_article_qte_pa[idArticle]";
$params_article_ref_desig = array();
$options_article_ref_desig =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
$result_article_ref_desig = sqlsrv_query($conn,$query_select_article_ref_desig
,$params_article_ref_desig,
$options_article_ref_desig) or die( 
print_r(sqlsrv_errors(), true));
while($reader_article_ref_desig = sqlsrv_fetch_array($result_article_ref_desig, SQLSRV_FETCH_ASSOC)){
?>
			<th scope="row"><?php echo $reader_article_ref_desig['Reference'];?></th>
			<td><?php echo $reader_article_ref_desig['Designation'];?></td>
<?php
}
?>
		    <td><?php echo $reader_article_qte_pa['type'];?>
		    </td>
		    <td><?php echo $reader_article_qte_pa['qte'];?></td>
		    <td><?php echo $reader_article_qte_pa['pa'];?></td>
		    <td>
		    	<a onClick="MyWindow2=window.open('update_article.php?ref=<?php  echo 
		    	$reader_article_qte_pa['idArticle']; ?>&mov_ref=<?php echo $_GET['ref']?>&id_detail_mov=<?php echo $reader_article_qte_pa['iddetailMouvement'] ?>','MyWindow2','toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=no,resizable=no,width=400,height=330,left=40,top=160');
				return false;">
		    	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="20px" height="20px">
				<path d="M397.736,78.378c6.824,0,12.358-5.533,12.358-12.358V27.027C410.094,12.125,397.977,0,383.08,0H121.641    c-3.277,0-6.42,1.303-8.739,3.62L10.527,105.995c-2.317,2.317-3.62,5.461-3.62,8.738v370.239C6.908,499.875,19.032,512,33.935,512    h349.144c14.897,0,27.014-12.125,27.014-27.027V296.289c0.001-6.824-5.532-12.358-12.357-12.358    c-6.824,0-12.358,5.533-12.358,12.358v188.684c0,1.274-1.031,2.311-2.297,2.311H33.936c-1.274,0-2.311-1.037-2.311-2.311v-357.88    h75.36c14.898,0,27.016-12.12,27.016-27.017V24.716H383.08c1.267,0,2.297,1.037,2.297,2.311V66.02    C385.377,72.845,390.911,78.378,397.736,78.378z M109.285,100.075c0,1.269-1.032,2.301-2.3,2.301H49.107l60.178-60.18V100.075z" fill="#D80027"/>
				<path d="M492.865,100.396l-14.541-14.539c-16.304-16.304-42.832-16.302-59.138,0L303.763,201.28H103.559    c-6.825,0-12.358,5.533-12.358,12.358c0,6.825,5.533,12.358,12.358,12.358h175.488l-74.379,74.379H103.559    c-6.825,0-12.358,5.533-12.358,12.358s5.533,12.358,12.358,12.358h76.392l-0.199,0.199c-1.508,1.508-2.598,3.379-3.169,5.433    l-19.088,68.747h-53.936c-6.825,0-12.358,5.533-12.358,12.358s5.533,12.358,12.358,12.358h63.332c0.001,0,2.709-0.306,3.107-0.41    c0.065-0.017,77.997-21.642,77.997-21.642c2.054-0.57,3.926-1.662,5.433-3.169l239.438-239.435    C509.168,143.228,509.168,116.7,492.865,100.396z M184.644,394.073l10.087-36.326l26.24,26.24L184.644,394.073z M244.69,372.752    l-38.721-38.721l197.648-197.648l38.722,38.721L244.69,372.752z M475.387,142.054l-15.571,15.571l-38.722-38.722l15.571-15.571    c6.669-6.668,17.517-6.667,24.181,0l14.541,14.541C482.054,124.54,482.054,135.388,475.387,142.054z" fill="#D80027"/>
				</svg>
				</a>
				<a href="mise_a_jour_mouvements.php?ref=<?php echo $_GET['ref'];?>&delete_id=<?php echo $reader_article_qte_pa['iddetailMouvement'] ?>">
					<svg version="1.1" width="20px" height="20px" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
					<polygon style="fill:#E21B1B;" points="404.176,0 256,148.176 107.824,0 0,107.824 148.176,256 0,404.176 107.824,512 256,363.824 
					404.176,512 512,404.176 363.824,256 512,107.824 "/>
					</svg>
				</a>
		    </td>
		</tr>
<?php
}
?>
	</tbody>
</table>

<?php } else{
	echo "Not Welcome";
}?>
</div>
</body>
</html>
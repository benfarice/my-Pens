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
<?php 
if(isset($_GET['id_detail_mov'])){
if(isset($_POST['submit'])){
$sql="update detailMouvements set UniteVente ='$_POST[optradio_type]',
qte=$_POST[Qte],pa=$_POST[pa] 
where idArticle = $_GET[ref] and idMouvement =
(select m.idMouvement from mouvements m where m.reference like '$_GET[mov_ref]') 
and iddetailMouvement = $_GET[id_detail_mov] ";
$result_update = sqlsrv_query($conn,$sql) or die(sqlsrv_errors());
//echo $sql;
?>
<script type="text/javascript">
  window.close();
</script>
<?php
}
if(isset($_GET['ref'])){
$query_select ="select  d.pa,d.qte,
isnull(d.UniteVente,'Colisage') as type from detailMouvements d where idArticle = $_GET[ref] 
and idMouvement =
(select m.idMouvement from mouvements m where m.reference like '$_GET[mov_ref]')
 and iddetailMouvement = $_GET[id_detail_mov]
";
$params_select = array();
//echo $query_select;
$options_select =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
$result__select = 
sqlsrv_query($conn,$query_select,$params_select,$options_select) 
or die( 
print_r(sqlsrv_errors(), true));
while($reader_select = sqlsrv_fetch_array($result__select, SQLSRV_FETCH_ASSOC)){


?>
<div class="container">
<form style="margin-top: 15px;" action="update_article.php?ref=<?php
	echo  $_GET['ref'] ;?>&mov_ref=<?php echo $_GET['mov_ref'] ?>&id_detail_mov=<?php 
  echo $_GET['id_detail_mov'] ?>" method="POST">
  <div class="form-group">
    <label for="pa">PA</label>
    <input  class="form-control" name="pa" id="pa" placeholder="PA"
    value="<?php echo $reader_select['pa'] ?>" required="required">
  </div>
  <div class="form-group">
    <label for="Qte">Qte</label>
    <input  class="form-control" name="Qte" id="Qte" placeholder="Qte"
    value="<?php echo $reader_select['qte'] ?>" required="required">
  </div>
  <div class="radio">
	  <label><input type="radio" name="optradio_type" value="Colisage"
    <?php if($reader_select['type']=='Colisage'){echo "checked";} ?>>
	  &#32;&#32;Colisage</label>
  </div>
  <div class="radio">
	  <label><input type="radio" name="optradio_type" value="Piece"
       <?php if($reader_select['type']!='Colisage'){echo "checked";} ?>>
	  &#32;&#32;Piece</label>
  </div>
  <button type="submit" name="submit" class="btn btn-primary">Modifier</button>
</form>
</div>
<?php
  }
}
}else{

if(isset($_GET['ref'])){
$query_select ="select  d.iddetailMouvement , d.pa,d.qte,
isnull(d.UniteVente,'Colisage') as type from detailMouvements d where idArticle = $_GET[ref] 
and idMouvement =
(select m.idMouvement from mouvements m where m.reference like '$_GET[mov_ref]')
 
";
$params_select = array();
//echo $query_select;
$options_select =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
$result__select = 
sqlsrv_query($conn,$query_select,$params_select,$options_select) 
or die( 
print_r(sqlsrv_errors(), true));
while($reader_select = sqlsrv_fetch_array($result__select, SQLSRV_FETCH_ASSOC)){
$id_detail_mo = $reader_select['iddetailMouvement'];

?>
<div class="container">
<form style="margin-top: 15px;" action="update_article.php?ref=<?php
  echo  $_GET['ref'] ;?>&mov_ref=<?php echo $_GET['mov_ref'] ?>&id_detail_mov=<?php 
  echo $id_detail_mo?>" method="POST">
  <div class="form-group">
    <label for="pa">PA</label>
    <input  class="form-control" name="pa" id="pa" placeholder="PA"
    value="<?php echo $reader_select['pa'] ?>" required="required">
  </div>
  <div class="form-group">
    <label for="Qte">Qte</label>
    <input  class="form-control" name="Qte" id="Qte" placeholder="Qte"
    value="<?php echo $reader_select['qte'] ?>" required="required">
  </div>
  <div class="radio">
    <label><input type="radio" name="optradio_type" value="Colisage"
    <?php if($reader_select['type']=='Colisage'){echo "checked";} ?>>
    &#32;&#32;Colisage</label>
  </div>
  <div class="radio">
    <label><input type="radio" name="optradio_type" value="Piece"
       <?php if($reader_select['type']!='Colisage'){echo "checked";} ?>>
    &#32;&#32;Piece</label>
  </div>
  <button type="submit" name="submit" class="btn btn-primary">Modifier</button>
</form>
</div>
<?php
  }
}

}
?>
<script>
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>
</body>
</html>
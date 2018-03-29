<?php 
require_once('connexion.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Plateforme de gestion de distribution</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta NAME="author" LANG="fr" CONTENT="AMINA WAHMANE"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery.min.js" type="text/javascript" ></script>
<script src="js/jquery.bxslider.min.js" type="text/javascript"></script>
<link href="css/jquery.bxslider.css" rel="stylesheet" />

</head>
<html>
<body>
<div class="page"   >
<?php 
//$idArticle=$_GET['idArticle'];
$sql = "	select a.idArticle, a.Designation,a.Reference ,url,Unite
		from articles a
			inner join detailchargements d on d.idArticle=a.idArticle
			inner join chargements c on c.IdChargement=d.IdChargement
			inner join media m on m.idArticle=a.idArticle
			where c.idVendeur=  ?
			 ";
		 //$params = array();
	//	 $_GET['idVend']
if ((isset($_GET['IdArticle'])) and ($_GET['IdArticle']!="") ) {
	$params = array(1,$_GET['IdArticle']);
	$sql.=" and a.idArticle=? ";
}else {
	$params = array(1);
}
$sql.="group by a.IdArticle,a.Reference,Unite,url,a.Designation";

     
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	

$sqlB =	"SELECT TOP  10 idMedia, url
FROM media order by idMedia Desc";
//echo  $sqlB;
//echo $sqlB;
      $paramsB= array();
	$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	$ntRes = sqlsrv_num_rows($stmt);
	

	//echo $sql;

	$nRes = sqlsrv_num_rows($stmt);	

		if($nRes==0)
		{ ?>
					<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
						<br><br><br><br>
						Aucun r&eacute;sultat &agrave; afficher.
					</div>
					<?php
		}
else
{	
?><ul class="bxslider" style="margin:0;padding:0"><?php
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		?>  <li >
		<div class="divLeft">
			<TABLE   border="0" width="100%" class="table">
			<tr><TD>Référence:</td><td><?php  echo $row['Reference'];?></td></tr>
			<tr><TD>Désignation:</td><td><?php  echo $row['Designation'];?></td></tr>
				</table>
			<br>
				<TABLE  class="tableTarif"  width="100%">
					<tr><th>Qantité Min</th><th>PV</th></tr>
					<tr><TD>1piece</td><td>1000</td></tr>
				
			<tr>
			<td colspan="2">Colisage:</td><tr>
				<tr>
			<td colspan="2"></td><tr>
			</table>
			
		</div>
		<div style="float:right;width:700px;background:green;">
			<img src="<?php  echo $row['url'];?>" width=" 700" />
		</div>
		
		</li>
	<?php } ?>
	</ul>
	<?php
}
?>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
  $('.bxslider').bxSlider({
	    auto: true, 
		pager: false,
		  pause: 3000,
		speed: 500
  });
});
	</script>

</div>
</body>

<script language="javascript" type="text/javascript">

	   
$(document).ready(function(){	
  	//	$('.page').load('imgArticles.php?aff');
		
  });
	
</script>
</html>
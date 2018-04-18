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
$sql = "select a.idArticle, a.Designation,a.Reference ,url,Unite
		from articles a
			inner join detailchargements d on d.idArticle=a.idArticle
			inner join chargements c on c.IdChargement=d.IdChargement
			inner join media m on m.idArticle=a.idArticle
			where c.idVendeur=  ?
			 ";
		 //$params = array();
if ((isset($_GET['IdArticle'])) and ($_GET['IdArticle']!="") ) {
	$params = array($_GET['idVend'],$_GET['IdArticle']);
	$sql.=" and a.idArticle=? ";
}else {
	$params = array($_GET['idVend']);
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
		<DIV class="haut">
		<div class="divLeft">
			<TABLE   border="0" width="100%" class="table">
			<tr>
			<th colspan="2">Détail produit:</th><tr>
			<tr><TD>Référence:</td><td><?php  echo $row['Reference'];?></td></tr>
			<tr><TD>Désignation:</td><td><?php  echo $row['Designation'];?></td></tr>
				</table>
			<br>
				<TABLE  class="tableTarif"  width="100%">
				<?php
				$sqlB = "	select qteMin,pvHT from tarifs t
							inner join fichetarifs f on f.idFiche=t.idFiche
							where etat=1 and idArticle=4
							ORDER BY qteMin
					";
				?>
					<tr><th>Qantité Min</th><th>PV(DH)</th></tr>
					<tr><TD>1 Pcs</td><td>15.00</td></tr>
					<tr><TD>10 Pcs</td><td>12.00</td></tr>
					<tr><TD>30 Pcs</td><td>10.00</td></tr>
					<tr><TD>100 Pcs</td><td>8.00</td></tr>
				
			
			</table>
			
		</div>
		<div style="float:right;width:780px;">
			<img src="<?php  echo $row['url'];?>" width=" 780" />
				<div class="clear"></div>
						<div class="cmd">
						<table BORDER=1  width="100%">
						<tr><TD rowspan="3" width="30%">
						Colisage :
						<div class="col">
						<img src="images/col1.png" style="display:inline"> 
						<input type="radio" name="fb" value="small" /><label for="check2">5 Pcs</label> 
						</div>
							<div class="col">
						<img src="images/col1.png" style="display:inline"> <input type="radio" name="fb" value="small" /> 5 Pcs
						</div>
							<div class="col">
						<img src="images/col1.png" style="display:inline"> <input type="radio" name="fb" value="small" /> 5 Pcs
						</div>
							<div class="col">
						<img src="images/col1.png" style="display:inline"> <input type="radio" name="fb" value="small" /> 5 Pcs
						</div>
						</td>
						<td align="right" valign="top">Qantité : </td><td valign="top"><input type="text" size="30">
						&nbsp;
						<input type="button" class="plus"> <input type="button" class="moins"></td></tr>
						
						<tr><td colspan="3" align="right">Sous total : <input type="text" value=" 1 100.00"> </td>
						</table>
						
						</div>
					
		</div>
		</div>
		<div class="clear"></div>

		</li>
			total:<input type="text" value="2 100.00" >
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

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
<script src="js/jquery-labelauty.js"></script>
<script src="js/jquery.touchSwipe.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-labelauty.css">


	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="fancy/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="fancy/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	
</head>
<html>
<body>
<div class="page"   >
<?php 

require_once('connexion.php');


//$idArticle=$_GET['idArticle'];
$sql = "select a.idArticle, a.Designation,a.Reference ,url,Unite,f.Designation as Famille
		from articles a
		inner join familles  f on  f.idFamille=a.IdFamille
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
$sql.="group by a.IdArticle,a.Reference,Unite,url,a.Designation,f.Designation ";  



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

?>
<form name="FormAdd">
<ul class="bxslider" style="margin:0;padding:0;">

<?php
	$i=0;
	while($row = sqlsrv_fetch_array($stmt)){
		$i+=1;
		?>  <li >
		<DIV class="haut">
		<div class="divLeft">
			<TABLE   border="0" width="100%" class="table">
			<tr><TD width="20%">Référence:</td><td>	<?php  echo $row['Reference'];?></td></tr>
			<tr><TD>Gamme:</td><td><?php  echo $row['Designation'];?></td></tr>
			<tr><TD>Famille:</td><td><?php  echo $row['Famille'];?></td></tr>
				</table>
			<br>
				<TABLE  class="tableTarif"  width="100%" cellspacing="2" cellpadding="5">
				<?php
			/*	$sqlB = "	select qteMin,pvHT from tarifs t
							inner join fichetarifs f on f.idFiche=t.idFiche
							where etat=1 and idArticle=4
							ORDER BY qteMin
					";*/
				?>
					<tr><th width="295">Article</th><th width="70">PV(DH)</th><th width="50" align="center">Qte </th>
					<th width="200">Colisage</th></tr>
					<tr>  <td align="left">bimo ositioning ositioning </td><td align="right">105.00</td><td align="center">
					<input type="text" class="inputTable " value=" 0.00" size="6"> </td>
					<td align="left" height="70">
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="1pcs|1pcs" aria-label="2"/>
							
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="135pcs|135pcs" aria-label="3"/>
					</td>
					</tr>
					<tr>  <td align="left">bimo bsdsd  sdqsdqsd</td><td align="right">15.00</td><td align="right">
					<input type="text" class="inputTable"  value=" 0.00" size="6"> </td>
					<td align="left" height="70">
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="1pcs|1pcs" aria-label="2"/>
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="135pcs|135pcs" aria-label="3"/>
					</td>
					</tr>
					<tr>  <td align="left" >positioning to the wrapping div ta</td><td align="right">15.00</td>
					<td align="right"><input type="text" class="inputTable"  value=" 0.00" size="6"> </td>
					<td align="left" height="70">
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="1pcs|1pcs" aria-label="2"/>
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="135pcs|135pcs" aria-label="3"/>
					</td>
					</tr>
					<tr>  <td align="left">positioning to the wrapping div ta</td><td align="right">15.00</td><td align="right"><input type="text" class="inputTable"  value=" 0.00" size="6"> </td>
					<td align="left" height="70">
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="1pcs|1pcs" aria-label="2"/>
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="135pcs|135pcs" aria-label="3"/>
					</td>
					</tr>
					<tr>  <td align="left">positioning to the wrapping div ta</td><td align="right">15.00</td><td align="right"><input type="text" class="inputTable"  value=" 0.00" size="6"> </td>
					<td align="left" height="70">
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="1pcs|1pcs" aria-label="2"/>
							<input type="radio" name="radio-input" value="10 pcs" data-labelauty="135pcs|135pcs" aria-label="3"/>
					</td></tr>
				
			<TR><td  COLspan="3" align="center">
			<input type="button" class="plus" value="">  <input type="button" class="moins" value="">
			</td>
			<td colspan="3" class="global">	
						 Total global: <input type="text" value=" 1 100.00 DHS" class="global" size="15" disabled> 
						
						</td>
			</tr>
			</table>
	
		</div>
		<div style="float:right;width:640px;background:red;">
			
			
			<a class="fancybox"  rel="group<?php  echo $i;?>" href="<?php  echo $row['url'];?>" data-fancybox-group="gallery" 
			title="Lorem ipsum dolor sit amet"><img src="<?php  echo $row['url'];?>" alt=""  width=" 640" height="800" /></a>
			
				<div class="clear"></div>
				
					
		</div>
		</div>
		<div class="clear"></div>
	
					
		</li>
		
	<?php } ?>

	</ul>
		</form>
	<?php
}
?>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
		
		$(":radio").labelauty();
	

  $('.fancybox').fancybox({
	  'width':1280,
	  'padding':false,
	  'height':500,
	  'autoScale': false,
                    'autoDimensions': false,
                    'scrolling'     : 'no',
                    'transitionIn'  : 'none',
                    'transitionOut' : 'none',
                    'type': 'iframe' ,
					 'iframe': {'scrolling': 'no'}
  });
   

  // initialize bxSlider
	var slider = $('.bxslider').bxSlider({
		infiniteLoop: false,
		slideMargin: 50,
		hideControlOnEnd: true,
		touchEnabled: false,
			pager: false,
		  pause: 3000,
		speed: 500,
		controls:false
	});

  // touchSwipe for the win!
		 $('.bxslider').swipe({
			swipeRight: function(event, direction, distance, duration, fingerCount) {
			
				slider.goToPrevSlide();
				
			},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {
				
				slider.goToNextSlide();
	
			},
			threshold: 100
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

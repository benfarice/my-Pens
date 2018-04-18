<?php 

$serverName = "192.168.1.118"; //serverName\instanceName//"CHARIFA-PC\INSTANCE2014";//
$connectionInfo = array( "Database"=>"Base_distribution5" , "UID"=>"sa", "PWD"=>"sa","CharacterSet" => "UTF-8");//, "UID"=>"sa", "PWD"=>"sa","CharacterSet" => "UTF-8");
global $conn;
 $conn = sqlsrv_connect( $serverName, $connectionInfo);//, $connectionInfo
if( $conn ) {
   // echo "Connexion établie.<br />";
}else{
   // echo "La connexion n'a pu être établie.<br />";
   header('Location: erreur.php');      
   // die( print_r( sqlsrv_errors(), true));
}



if (isset($_GET['rech']) or (isset($_GET['aff']))){
	
exit;
}

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
$idArticle=40;
$sql = "SELECT url  FROM Media where idArticle like ?";
		 //$params = array();
     $params = array($idArticle);
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	

$sqlB =	"SELECT TOP  10 idMedia, url
FROM media order by idMedia Desc";
//echo  $sqlB;
//echo $sqlB;
      $paramsB= array();
	$stmt=sqlsrv_query($conn,$sqlB,$paramsB,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
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
		?>  <li ><img src="<?php  echo $row['url'];?>"  /></li>
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
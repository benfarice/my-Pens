<?php
@session_start();
function isLoginSessionExpired() {
	$login_session_duration = 3600; //1h
	$current_time = time(); 
	if(isset($_SESSION['loggedin_time']) and isset($_SESSION["IdVendeur"])){  
		if(((time() - $_SESSION['loggedin_time']) > $login_session_duration)){ 
			return true; 
		} 
	}
	return false;
}

if(!isset($_SESSION['IdVendeur']) ||(isLoginSessionExpired()) ){
	?>
	<script language="javascript" > 
	window.location.href = 'login.php';
	</script>
	<?php
	exit;
}
?>
<!DOCTYPE html>

<html>
<head>
<title>Plateforme de gestion de distribution</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta NAME="author" LANG="fr" CONTENT="AMINA WAHMANE"> 
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes"  ><!-- user-scalable pour zoom d'utilisateur 
 “width=device-width” sert à spécifier que la largeur affichée est bien celle de l’écran-->

<!--<link href="css/jquery.bxslider.css" rel="stylesheet" />-->
<link href="css/update_style.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

<script src="js/jquery.min.js" type="text/javascript" ></script>
<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!--<script  src="src/FileSaver.js"></script>-->







<!-- Add fancyBox main JS and CSS files -->


<link href="css/multiple-select.css" rel="stylesheet"/>



</head>
<body>
<div id="preload" style=""></div>
<div class="page">
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	<?php if($_SESSION['lang']=='ar'){?>
		$.alerts.okButton = 'نعم';
		$.alerts.cancelButton = 'لا';
	<?php } ?>
});
</script>
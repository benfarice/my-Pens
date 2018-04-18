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
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />

<script src="js/moment.min.js"></script> 
<link href="css/multiple-select.css" rel="stylesheet"/>
<?php if($_SESSION['lang']=="ar") { ?>
<link type="text/css" rel="stylesheet" href="css/alert2ResAr.css" media="screen" />
<link href="css/stylesResAr.css" rel="stylesheet" />
<link href="css/bootstrap-arabic.css" rel="stylesheet" />
<link href="css/jquery-ui-ar.css" rel="stylesheet" />
<?php } else { ?>
<link type="text/css" rel="stylesheet" href="css/alert2 for responsive.css" media="screen" />

 <link type="text/css" rel="stylesheet" href="css/jquery-ui.css" media="screen" />
 <?php if(isset($no_style_res)){}else{ ?>
<link href="css/stylesRes.css" rel="stylesheet" />
<?php } } ?>
<link href="css/hover-min.css" rel="stylesheet" />
<script src="js/jquery113.min.js" type="text/javascript" ></script>
<script src="js/jquery-ui.js" type="text/javascript"></script>
<script src="js/jquery.validate.min.js" type="text/javascript"></script>
<script  src="js/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="js/jquery.touchSwipe.min.js"></script>
<script  src="js/jquery.alerts.js" type="text/javascript"></script>
<script src="js/jquery.form.js" type="text/javascript"></script>
<script src="js/fonctions.js" type="text/javascript"></script>
<script src="js/fct_menu.js" type="text/javascript"></script>
<script src="js/bootstrap.js" type="text/javascript"></script>
<script src="js/jquery.multiple.select.js" type="text/javascript"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>


<!-- Add fancyBox main JS and CSS files -->


<link href="css/multiple-select.css" rel="stylesheet"/>
<link href="css/animate.min.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap-glyphicons.css">
<!--<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">-->
<link href="css/css_google_font.css" rel="stylesheet">
<!--<script src="js/zabuto_calendar.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-glyphicons.css">
<link rel="stylesheet" type="text/css" href="css/zabuto_calendar.min.css">-->


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
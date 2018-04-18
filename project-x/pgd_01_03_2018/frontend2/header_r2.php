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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Plateforme de gestion de distribution</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta NAME="author" LANG="fr" CONTENT="AMINA WAHMANE"> 
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes"  ><!-- user-scalable pour zoom d'utilisateur 
 “width=device-width” sert à spécifier que la largeur affichée est bien celle de l’écran-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link href="css/css_google_font.css" rel="stylesheet">
<script src="js/jquery.min.js" type="text/javascript" ></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<link href="css/animate.min.css" rel="stylesheet"/>
<script src="js/jquery.bxslider.min.js" type="text/javascript"></script>
<link href="css/jquery.bxslider.css" rel="stylesheet" />
<link href="css/hover-min.css" rel="stylesheet" />
<script src="js/jquery-labelauty.js"></script>
<script src="js/jquery.touchSwipe.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-labelauty.css">
<link rel="stylesheet" type="text/css" href="css/jquery.scrollbar.css">
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="fancy/source/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="fancy/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<link type="text/css" rel="stylesheet" href="css/jquery-ui.css" media="screen" />
<script src="js/jquery-ui.js" type="text/javascript"></script>
<script src="js/jquery.validate.min.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="css/alert2.css" media="screen" />
<script  src="js/jquery.alerts.js" type="text/javascript"></script>
<script src="js/jquery.form.js" type="text/javascript"></script>
<script src="js/fonctions.js" type="text/javascript"></script>
<script src="js/fct_menu.js" type="text/javascript"></script>
<script src="js/jquery.scrollbar.js" type="text/javascript"></script>
<link href="css/multiple-select.css" rel="stylesheet"/>
<script src="js/jquery.multiple.select.js" type="text/javascript"></script>
<script src="js/jquery.cookie.js" type="text/javascript"></script>
<?php if($_SESSION['lang']=="ar") { ?>
<link href="css/stylesAr.css" rel="stylesheet" />
<?php } else { ?>
<link href="css/styleEn.css" rel="stylesheet" />
<?php } ?>
<style>
.ui-dialog { z-index: 18000 !important ;}
.ui-dialog .ui-dialog-content,.ui-dialog{
	padding:0;
}
.ui-dialog .ui-dialog-titlebar{
	display:none;
}
	
.ui-dialog .ui-dialog-content{
overflow:hidden ;}
.ui-dialog .ui-dialog-buttonpane{
	display:none;
}
</style>
<script language="javascript" type="text/javascript">
$(document).ready(function(){

	$.validator.messages.required = '';	
		$.validator.addMethod("tel", function(value, element) 
		{ 
			return this.optional(element) || /^\(?([0-9]{8})\)?[-. ]?([0-9]{2})$/i.test(value); 
		}, " ");
});
</script>
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
<?php
@session_start();
include("lang.php");

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
 <link href="css/bootstrap.css" rel="stylesheet"/>
<link type="text/css" rel="stylesheet" href="css/jquery-ui.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/alert2.css" media="screen" />
<!--link href="css/font-awesome.min.css" rel="stylesheet" /-->
<link href="css/multiple-select.css" rel="stylesheet"/>
<link href="css/stylesRes.css" rel="stylesheet" />
<link href="css/hover-min.css" rel="stylesheet" />
<?php if($_SESSION['lang']=="ar") { ?>
<link href="css/stylesAr.css" rel="stylesheet" />
<?php } else { ?>
<link href="css/styleEn.css" rel="stylesheet" />
<?php } ?>
<script src="js/jquery.min.js" type="text/javascript" ></script>
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
<div class="container" id="page">

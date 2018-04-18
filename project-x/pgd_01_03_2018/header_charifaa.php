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

if(isLoginSessionExpired() ){
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
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.css" rel="stylesheet"> 
<link type="text/css" rel="stylesheet" href="css/jquery-ui-1.7.2.custom.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/jquery.qtip.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/alert.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/styles.css"  media="screen" />
<link rel="stylesheet" href="css/tabs.css" type="text/css" media="screen, projection"/>
<link rel="stylesheet" type="text/css" href="css/menu1.css">
<script src="js/jquery.min.js" type="text/javascript" ></script>
<script src="js/bootstrap.js" type="text/javascript"></script> 
<script src="js/jquery-migrate-1.1.1.js"></script>
<script  src="js/jquery.alerts.js" type="text/javascript"></script>
<script src="js/fct_menu.js" type="text/javascript"></script>
<script src="js/fonctions.js" type="text/javascript"></script>
<!--<script src="js/jquery.dropdown.js" type="text/javascript"></script>
<script src="js/jquery.dropdownPlain.js" type="text/javascript"></script>-->
<script src="js/hoverIntent.js" type="text/javascript"></script>
<script src="js/jquery.form.js" type="text/javascript"></script>
<script src="js/jquery.autocomplete.js" type="text/javascript"></script>
<!--<script src="js/jquery.tooltip2.js" type="text/javascript"></script>-->
<script src="js/jquery.tools.min.js" type="text/javascript"></script>
<script src="js/jquery.contextMenu.js" type="text/javascript"></script>
<script src="js/jquery.date.js" type="text/javascript"></script>
<script src="js/jquery.qtip.js" type="text/javascript"></script>
<!--<script src="js/jquery.qtip.call.js" type="text/javascript"></script>-->
<script src="js/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>
<script src="js/jquery.validate.min.js" type="text/javascript"></script>
<link href="css/multiple-select.css" rel="stylesheet"/>
<script src="js/jquery.multiple.select.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.ui.timepicker.js?v=0.3.3"></script>

	<script src="js/highstock.js"></script>

<link href="css/jquery.ui.timepicker.css" rel="stylesheet"/>
<?php if($_SESSION['lang']=="ar") { ?>
<link href="css/stylesAr.css" rel="stylesheet" />
<?php } else { ?>
<link href="css/styleEn.css" rel="stylesheet" />
<?php } ?>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
		$.validator.messages.required = '';

		$.validator.addMethod("pwd", function(value, element) 
		{ 
			return this.optional(element) || /^(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};\\|,.<>\/?]).{8,15}$/i.test(value); 
		}, "<P  style='clear:both'>Mot de passe doit contenir : <br><br> * Minimum 8 caractères. <br> * Au moins un chiffre. <br> * Au moins un caractére.<br> * Au moins un caractére spécial.<br></p>");
		$.validator.addMethod('IP4Checker', function(value) {

		   var ip="^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\." +
		   "([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\." +
		   "([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\." +
		   "([01]?\\d\\d?|2[0-4]\\d|25[0-5])$";
			   return value.match(ip);
		   }, 'Invalid IP address');

		$.validator.addMethod("tel", function(value, element) 
		{ 
			return this.optional(element) || /^\(?([0-9]{8})\)?[-. ]?([0-9]{2})$/i.test(value); 
		}, " ");

});
</script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	<?php if($_SESSION['lang']=='ar'){?>
		$.alerts.okButton = 'نعم';
		$.alerts.cancelButton = 'لا';
	<?php } ?>
	
	$('#preload').dialog({
					autoOpen: false,
					width: 300,
					bgiframe:true,
					modal:true,
					resizable:false,
					closeOnEscape:false,
					draggable:false,
					title:'<?php //echo $trad['titre']['Alert'];  ?>',
					stack:true,
					zindex:1000,
					position:'center'
				});
});
</script>
</head>


<div id="preload" style=""></div>
<div class="header">
	<div class="head" >

</div>
<div class="page">	
	<?php include("menu2.php");?>
	<div class="clear"></div>
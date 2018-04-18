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



<!--link href="css/font-awesome.min.css" rel="stylesheet" /-->
<link href="css/multiple-select.css" rel="stylesheet"/>
<?php if($_SESSION['lang']=="ar") { ?>
<link type="text/css" rel="stylesheet" href="css/alert2ResAr.css" media="screen" />
<link href="css/stylesResAr.css" rel="stylesheet" />
<link href="css/bootstrap-arabic.css" rel="stylesheet" />
<link href="css/jquery-ui-ar.css" rel="stylesheet" />
<?php } else { ?>
<link type="text/css" rel="stylesheet" href="css/alert2 for responsive.css" media="screen" />
 <link href="css/bootstrap.css" rel="stylesheet">
 <link type="text/css" rel="stylesheet" href="css/jquery-ui.css" media="screen" />
<link href="css/stylesRes.css" rel="stylesheet" />
<?php } ?>


<link href="css/hover-min.css" rel="stylesheet" />

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


  function ajaxindicatorstart(text)
	{
		jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="../images/loading.gif"><div>'+text+'</div></div><div class="bg"></div></div>');
		if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
		}
		
		jQuery('#resultLoading').css({
			'width':'100%',
			'height':'100%',
			'position':'fixed',
			'z-index':'10000000',
			'top':'0',
			'left':'0',
			'right':'0',
			'bottom':'0',
			'margin':'auto'
		});	
		
		jQuery('#resultLoading .bg').css({
			'background':'#000000',
			'opacity':'0.7',
			'width':'100%',
			'height':'100%',
			'position':'absolute',
			'top':'0'
		});
		
		jQuery('#resultLoading>div:first').css({
			'width': '250px',
			'height':'75px',
			'text-align': 'center',
			'position': 'fixed',
			'top':'0',
			'left':'0',
			'right':'0',
			'bottom':'0',
			'margin':'auto',
			'font-size':'16px',
			'z-index':'10',
			'color':'#ffffff'
			
		});

	    jQuery('#resultLoading .bg').height('100%');
        jQuery('#resultLoading').fadeIn(300);
	    jQuery('body').css('cursor', 'wait');
	}

	function ajaxindicatorstop()
	{
	    jQuery('#resultLoading .bg').height('100%');
        jQuery('#resultLoading').fadeOut(300);
	    jQuery('body').css('cursor', 'default');
	}
	
$(document).ready(function(){
	<?php if($_SESSION['lang']=='ar'){?>
		$.alerts.okButton = 'نعم';
		$.alerts.cancelButton = 'لا';
	<?php } ?>
});
	
 /* jQuery(document).ajaxStart(function () {
   		//show ajax indicator
		ajaxindicatorstart('En cours de chargement, merci de patienter..');
  }).ajaxStop(function () {
  
		//hide ajax indicator
		ajaxindicatorstop();
  });*/
  
</script>
</head>
<body>
<div class="container" id="page">

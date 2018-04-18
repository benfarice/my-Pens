<!DOCTYPE html>
<html>
<head>
<title>Plateforme de gestion de distribution</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta NAME="author" LANG="fr" CONTENT="AMINA WAHMANE"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css_y/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
<script
  src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>

<link href="css/bootstrap.css" rel="stylesheet"  type="text/css" /> 
<link type="text/css" rel="stylesheet" href="css/jquery-ui-1.7.2.custom.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/jquery.qtip.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/alert.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css_y/styles.css"  media="screen" />
<link rel="stylesheet" href="css/tabs.css" type="text/css" media="screen, projection"/>
<link rel="stylesheet" type="text/css" href="css/menu1.css">
<script src="js/jquery.min.js" type="text/javascript" ></script>
<script src="js/bootstrap.js" type="text/javascript"></script> 
<script src="js/jQuery.print.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>
<script src="js/jquery-migrate-1.1.1.js"></script>
<script  src="js/jquery.alerts.js" type="text/javascript"></script>
<script src="js/fct_menu.js" type="text/javascript"></script>
<script src="js/fonctions.js" type="text/javascript"></script>
<!--<script src="js/jquery.dropdown.js" type="text/javascript"></script>
<script src="js/jquery.dropdownPlain.js" type="text/javascript"></script>-->
<script src="js/hoverIntent.js" type="text/javascript"></script>
<script src="js/jquery.form.js" type="text/javascript"></script>
<!--script src="js/jquery.autocomplete.js" type="text/javascript"></script>
<!--<script src="js/jquery.tooltip2.js" type="text/javascript"></script>-->
<script src="js/jquery.tools.min.js" type="text/javascript"></script>
<script src="js/jquery.contextMenu.js" type="text/javascript"></script>
<script src="js/jquery.date.js" type="text/javascript"></script>
<script src="js/jquery.qtip.js" type="text/javascript"></script>
<!--<script src="css/here.js" type="text/javascript"></script>-->
<!--<script src="js/jquery.qtip.call.js" type="text/javascript"></script>-->
<script src="js/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>
<script src="js/jquery.validate.min.js" type="text/javascript"></script>
<link href="css/multiple-select.css" rel="stylesheet"/>
<script src="js/jquery.multiple.select.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.ui.timepicker.js?v=0.3.3"></script>
<script src="js/highstock.js"></script>
<link href="css/jquery.ui.timepicker.css" rel="stylesheet"/>
<?php if($_SESSION['lang']=="ar") { ?>
<link href="css_y/stylesAr.css" rel="stylesheet" />
<link href="autoc/dist/easy-autocomplete-ar.min.css" rel="stylesheet" type="text/css" />
<?php } else { ?>
<link href="css_y/styleEn.css" rel="stylesheet" />
<link href="autoc/dist/easy-autocomplete.min.css" rel="stylesheet" type="text/css" />
<?php } ?>
<link rel="stylesheet" href="autoc/dist/easy-autocomplete.themes.min.css" /> 
	<script src="autoc/dist/jquery.easy-autocomplete.min.js" type="text/javascript" ></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
		$.validator.messages.required = '';
		$.validator.addMethod("pwd", function(value, element) 
		{ 
			return this.optional(element) || /^(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};\\|,.<>\/?]).{8,15}$/i.test(value); 
		}, "<P  style='clear:both'><?php echo $trad['msg']['PwdInvalid']; ?></p>");
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
	
  function ajaxindicatorstart(text)
	{
		jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="images/loading.gif"><div>'+text+'</div></div><div class="bg"></div></div>');
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
	
	$('#preload').dialog({
					autoOpen: false,
					width: 300,
					bgiframe:true,
					modal:true,
					resizable:false,
					closeOnEscape:false,
					draggable:false,
					title:'<?php echo $trad['titre']['Alert'];  ?>',
					stack:true,
					zindex:1000,
					position:'center'
				});
});
</script>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-12">
			<div id="preload" style=""></div>
				<div class="header"></div>
				<div class="head" ></div>
     	</div>
		<div class="col-12">	
			<?php include("menu_y.php");?>
			<div class="clear"></div>
		</div>
	</div>	



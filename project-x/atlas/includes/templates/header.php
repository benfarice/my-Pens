<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title><?php getTitle();?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>bootstrap.min.css">
		
		
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>fontawesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>fontawesome-all.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>Google-Font.css">
		<link rel="stylesheet" type="text/css" href="backend/layout/css/bootstrapValidator.min.css">
			<link rel="stylesheet" type="text/css" href="backend/layout/css/alert.css">
		<link href="<?php echo $css ?>jquery-ui.css" rel="stylesheet">
		<link href="<?php echo $css ?>pretty-checkbox.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>front-end.css">

		<script type="text/javascript" src="<?php echo $js ?>jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>bootstrap.min.js"></script>
		
		
				<script src="backend/layout/js/jquery.form.js" type="text/javascript"></script>
		<script src="backend/layout/js/bootstrapValidator.min.js" type="text/javascript"></script>
		<script src="backend/layout/js/jquery.alerts.js" type="text/javascript"></script>
		
		<!--<script src="external/jquery/jquery.js"></script>-->
		<script src="<?php echo $js ?>jquery-ui.js"></script>
	
<script language="javascript" type="text/javascript">
/*
$(document).ready(function(){
	$.validator.messages.required = '';	
		$.validator.addMethod("tel", function(value, element) 
		{ 
			return this.optional(element) || /^\(?([0-9]{8})\)?[-. ]?([0-9]{2})$/i.test(value); 
		}, " ");
});
*/

function ajaxindicatorstart(text)
	{
		jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="layout/images/loading.gif"><div>'+text+'</div></div><div class="bg"></div></div>');
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
	function CloseBox(BoxData){
		$("#"+BoxData).modal('hide');
	}
	</script>
	</head>
	
	<body>
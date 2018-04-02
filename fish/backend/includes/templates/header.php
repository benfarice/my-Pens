<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">		
		<title><?php echo "ميناء صور / سوق السمك";?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>bootstrap.min.css">
		<!--link rel="stylesheet" type="text/css" href="<?php //echo $css ?>compiled.min.css"-->
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>bootstrapValidator.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>alert.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>fontawesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>fontawesome-all.css">
			<link rel="stylesheet" type="text/css" href="<?php echo $css ?>font-awesome.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>menu.css">
		  <link rel="stylesheet" href="<?php echo $css ?>bootstrap-select.css">
		    <link rel="stylesheet" href="<?php echo $css ?>ionicons.min.css" />   
			<link rel="stylesheet" href="<?php echo $css ?>flexisel.css" />
			<link rel="stylesheet" href="<?php echo $css ?>fontawesome.min.css" />
			
				<link rel="stylesheet" type="text/css" href="<?php echo $css ?>pretty-checkbox.min.css">
				
						<link rel="stylesheet" type="text/css" href="<?php echo $css ?>daterangepicker.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>styles.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>backend_ar.css">
		
		<script type="text/javascript" src="<?php echo $js ?>jquery-3.3.1.min.js"></script>
		
		<script type="text/javascript" src="<?php echo $js ?>bootstrap.min.js"></script>
		<script src="<?php echo $js ?>jquery.form.js" type="text/javascript"></script>
		<script src="<?php echo $js ?>bootstrapValidator.min.js" type="text/javascript"></script>
		<script src="<?php echo $js ?>jquery.alerts.js" type="text/javascript"></script>
		<script src="<?php echo $js ?>datatables.min.js" type="text/javascript"></script>    

		<script type="text/javascript" src="<?php echo $js ?>moment.min.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>daterangepicker.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>highcharts.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>highcharts-3d.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>exporting.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>fonctions.js"></script>
		<script src="<?php echo $js ?>bootstrap-select.js" defer></script>
		<script src="<?php echo $js ?>bootstrap-confirmation.min.js" defer></script>

				
				
				<link rel="stylesheet" href="owl-carousel/owl.carousel.css">
 <script type="text/javascript" src="<?php echo $js ?>jQuery.print.min.js"></script>
<!-- Default Theme -->
<link rel="stylesheet" href="owl-carousel/owl.theme.css">
 

 
<!-- Include js plugin -->
<script src="owl-carousel/owl.carousel.min.js"></script>


	</head>
	<body>
		
	<div class="page col-md-12 col-sm-12 col-xs-12"  >
		<div class="head col-md-12 col-sm-12 col-xs-12"  >
		<h2 class="">ميناء صور / سوق السمك</h2>
		<!--مرحبا <span><?php //echo $_SESSION['username'] ; ?></span>-->
		</div>

	
	
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
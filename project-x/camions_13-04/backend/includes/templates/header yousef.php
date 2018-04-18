<!DOCTYPE html>
	<html>
	<head>
			
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">	
		<title><?php echo "Gestion du marché de gros";?></title>

		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>bootstrap.min.css">
		<!--link rel="stylesheet" type="text/css" href="<?php //echo $css ?>compiled.min.css"-->
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>bootstrapValidator.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>alert.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>fontawesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>fontawesome-all.css">
			<link rel="stylesheet" type="text/css" href="<?php echo $css ?>font-awesome.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>menu.css">
		    <link rel="stylesheet" href="<?php echo $css ?>ionicons.min.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>styles.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>backend_ar.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>pretty-checkbox.min.css">
		<script type="text/javascript" src="<?php echo $js ?>jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>bootstrap.min.js"></script>
		<script src="<?php echo $js ?>jquery.form.js" type="text/javascript"></script>
		<script src="<?php echo $js ?>bootstrapValidator.min.js" type="text/javascript"></script>
		<script src="<?php echo $js ?>jquery.alerts.js" type="text/javascript"></script>
		<script src="<?php echo $js ?>datatables.min.js" type="text/javascript"></script>    
		<link rel="stylesheet" type="text/css" href="<?php echo $css ?>daterangepicker.css" />
		<script type="text/javascript" src="<?php echo $js ?>moment.min.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>daterangepicker.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>highcharts.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>highcharts-3d.js"></script>
		<script type="text/javascript" src="<?php echo $js ?>exporting.js"></script>

		<script type="text/javascript" src="<?php echo $js ?>jQuery.print.min.js"></script>
		<!--
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
		-->
	</head>
	<body>
	
	<div class="page container-fluid text-right">	
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-3">
			<img src="layout/images/ATLAS.jpg"  width="200px" height="200px">
		</div>
		<div class="col-md-6 col-sm-6 col-xs-6 text-center" >
		  <h1 id="website_title"><?php echo lang('port'); ?></h1>
		
		</div>
		<div class="col-md-3 col-sm-3 col-xs-3 text-left">
			<img src="layout/images/logo1.png" width="200px" height="200px">
		</div>
	</div>
	
	
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	/*
	$.validator.messages.required = '';	
		$.validator.addMethod("tel", function(value, element) 
		{ 
			return this.optional(element) || /^\(?([0-9]{8})\)?[-. ]?([0-9]{2})$/i.test(value); 
		}, " ");

	*/
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
	function CloseBox(BoxData){
		$("#"+BoxData).modal('hide');
	}
	</script>
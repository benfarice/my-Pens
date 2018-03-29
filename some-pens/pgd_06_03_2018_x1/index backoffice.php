<?php

if(isset($_GET['conect'])){

session_destroy();
		?>
				<SCRIPT LANGUAGE="JavaScript">
					document.location.href="Vente.php" /* vous pouvez aussi mettre http://www.monsite.com */
					</SCRIPT>
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

<link href="css/jquery.ui.timepicker.css" rel="stylesheet"/>

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
</head>


<div id="preload" style=""></div>
<div class="header">
	<div class="head" ></div>
</div>
<div class="page">	
    
<div id="preload" style=""></div>
<div class="page">

		<div class="clear"></div>
		<div id="corpsContenu" class="CContenu">
		<br>	<br>	<br>	<br>	<br><br>
		<form method="post"  id="frmconnect" NAME="frmconnect">
					
					<div class="logincontainer">
					
					   
					 <fieldset class="control-group">

	    <div class="control-group">
		    <label class="control-label" for="username">Login:</label>
			<div class="controls">
			    <input  name="Login" id="Login" type="text"  style="width:280px;"/>
			</div>
		</div>

		<div class="control-group">
		    <label class="control-label" for="password">Mot de passe:</label>
			<div class="controls">
			    <input  name="Pwd" id="Pwd" type="password" style="width:280px;"/>
			</div>
		</div>

        <div align="center">
		    <div id="formcon" ></div>	<br>
		  <div class="loginbtn"><input type="button" class="btn"  id="cnte" value="Connexion"  onClick="terminer();"/></div>
          <div class="rememberme chpinvisible "><input type="checkbox" name="rememberme"   value="lll"/> 
          <strong>Se rappeler de moi</strong></div>
          <br />
          <br />
          <p><a href="#"  class="lienA" >Pour r&eacute;nitialiser votre mot de passe, <u>cliquez ici</u>.</a>
		  <div id="progress-div"><div id="progress-bar"></div></div>
		  </p>
        </div>

	</fieldset>
					</div>
				
				</form>
				
	</div>
	

<script language="javascript">
   $('#Login').focus();

	$('#cnte').keydown(function(event){
		
			if(event.keyCode == 13){

				$('#cnte').click();
			}
	});
	
 
function terminer(){

		  $('#frmconnect').ajaxSubmit({target:'#formcon',								
										
										url:'index.php?conect'})
	}
</script>
	<?php include("footer.php");?>
		  
	

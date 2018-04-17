<?php
include_once "init.php";
if(isset($_GET["GetLng"])){

$_SESSION['lang']=$_GET["lng"];
//echo "------".$_SESSION['lang'] . ' ------------------ ';
?>
<script language="javascript">
//alert(<?php echo $_SESSION['lang']; ?>);
window.location.reload();
</script>
<?php

exit;
}



if(isset($_GET["conect"])){
//parcourir( $_POST) ;
//echo "hereee";return;

$login=false;
$msg="";
	$sql = "SELECT nom ,prenom  ,login ,pwd,idDepot
			 FROM utilisateurs 
			 where Login like  '".$_POST["username"]."' ";
			//echo $sql;
		$params = array();	
		$stmt=sqlsrv_query($conn,$sql,$params,array( "Scrollable" => 'static' ) );
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									//echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									?>
							<script language="javascript">
							 $('#formcon').html('');	
							 $('#formcon').html('<?php echo "Erreur : ".$errors[0]['message'] . " <br/> "; ?>');				 
							 $('#formcon').show("slow");
							</script>
							<?php	
							
									return;
								}
	$nRes = sqlsrv_num_rows($stmt);	
	if($nRes>0)//--------------Login Exist
	{   
	    $login=true;
	    $msg="";
	 	
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	    if($row['pwd']!= $_POST['password'] ){//if ($row['pwd'] != crypt($_POST['password'], $row['pwd'])) {//-----Incorect Password
			$login=false;
	        $msg=$trad['login']['MsgErrorPwd'] ;//"Mot de passe incorrect. Veuillez réessayer.";
			
		}
		else
		{//echo "Mot de passe correct";	
			$login=true;
	        $msg="";

		}
	
	}
	else //------------------Login doesn't exist
	{
		$login=false;
	    $msg=$trad['login']['MsgErrorLogin'] ;//"Login incorrect. Veuillez réessayer.";
		
	}
	//echo $msg . ' hereeaaaaaaaaaaaaaaaaaaae '; return;
		if($login==true)
		{

				$_SESSION['loggedin_time'] = time();  
				$_SESSION['IdDepot'] = $row['idDepot']; 
			//echo "here ".$_SESSION['IdDepot'];return;
			?>
			<script language="javascript">
			$('#formcon').html('');
			$('#formcon').hide( 1000 );
			window.location.href = 'vente.php';
			</script>
			<?php
			
		}
		else
		{
			?>
			<script language="javascript">
			 $('#formcon').html('');	
			 //alert(<?php echo $msg; ?>);
			 $('#formcon').html('<?php echo $msg; ?>');				 
			 $('#formcon').show("slow");
			 
			</script>
			<?php		
		}
	
				

exit;
}
/*
if(isset($_GET['conect'])){

session_destroy();
		?>
				<SCRIPT LANGUAGE="JavaScript">
					document.location.href="Vente.php"
					</SCRIPT>
				<?php
		
exit;
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<meta charset="utf-8">		
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
<link href="css/jquery.ui.timepicker.css" rel="stylesheet"/>
<?php if($_SESSION['Lang']=="ar") { ?>
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
<style>


input[type=radio]{
		display:none;
	}

input[type=radio] + label{
		display:inline-block;
		font-weight:bold;
		width: 100px;
height: 50px;
line-height: 50px;
		margin-bottom: 0;
		
		color: #333;
		text-align: center;
		text-shadow: 0 1px 1px rgba(255,255,255,0.75);
		vertical-align: middle;
		cursor: pointer;
		background-color: #f5f5f5;
		background-image: -moz-linear-gradient(top,#fff,#e6e6e6);
		background-image: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));
		background-image: -webkit-linear-gradient(top,#fff,#e6e6e6);
		background-image: -o-linear-gradient(top,#fff,#e6e6e6);
		background-image: linear-gradient(to bottom,#fff,#e6e6e6);
		background-repeat: repeat-x;
		border: 1px solid #ccc;
		border-color: #e6e6e6 #e6e6e6 #bfbfbf;
		border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
		border-bottom-color: #b3b3b3;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
		filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
		-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
		-moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
		box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
	}

	 input[type=radio]:checked + label{
		background-image: none;
		outline: 0;
		-webkit-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
		-moz-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
		box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
		background-color:#e0e0e0;
	}
</style>
</head>

<body>
<div id="preload" style=""></div>
<div class="header">

<div class="head" >
<!--div style=" text-align:right;color:#0988bf; padding-top:10px;padding-right:10px; font-weight:bold">
<a href="#" class="lng">Français</a> | English | عربي
</div-->
</div>
</div>

<div class="page" >

		<div class="clear"></div>
		<div id="corpsContenu"  style="width:100%" class="CContenu">
		
		<br>	<br>	<br><br>
		<form method="post"  id="frmconnect" NAME="frmconnect">
					
					<div style="direction:rtl" class="logincontainer">				
					 <fieldset class="control-group">
	    <div class="control-group">
		    <label class="control-label" for="username"><?php echo $trad['login']['name'] ; ?>:</label>
			<div class="controls">
			    <input value="admin"  name="username" id="username" type="text"  style="width:280px;"/>
			</div>
		</div>

		<div class="control-group">
		    <label class="control-label" for="password"><?php echo $trad['login']['pwd'] ; ?>:</label>
			<div class="controls">
			    <input value="123"  name="password" id="password" type="password" style="width:280px;"/>
			</div>
		</div>

        <div align="center">
		  <div id="formcon" ></div>	<br>
		  <div class="loginbtn"><input type="button" class="btn"  id="cnte" value="<?php echo $trad['login']['ok'] ; ?>"  onClick="terminer();"/></div>
          <div class="rememberme chpinvisible "><input type="checkbox" name="rememberme"   value="lll"/> 
          <strong>Se rappeler de moi</strong></div>
          <br />
          <br />
          <!--p><a href="#"  class="lienA" >Pour r&eacute;nitialiser votre mot de passe, <u>cliquez ici</u>.</a>
		  <div id="progress-div"><div id="progress-bar"></div></div>
		  </p-->
		  				
        </div>

	</fieldset>
					</div>
				
				</form>
				<div style="text-align:center; direction:ltr">        
							<input id="radio1" <?php echo ($_SESSION['lang'] == 'fr') ?  'checked' : '' ; ?> type="radio" name="lng" value="francais" onclick="getLng('fr')" >
							<label  for="radio1"><strong><?php echo $trad['login']['fr'] ; ?></strong></label>
							<input id="radio2" type="radio" <?php echo ($_SESSION['lang'] == 'en') ?  'checked' : '' ; ?> name="lng" value="anglais" onclick="getLng('en')"  >
							<label  for="radio2"><?php echo $trad['login']['en'] ; ?></label>
							<input id="radio3" type="radio" <?php echo ($_SESSION['lang'] == 'ar') ?  'checked' : '' ; ?> name="lng" value="arabe" onclick="getLng('ar')"  >
							<label  for="radio3"><?php echo $trad['login']['ar'] ; ?></label>
								</div>
	</div>
	

<script language="javascript">
   $('#Login').focus();

	$('#cnte').keydown(function(event){
		
			if(event.keyCode == 13){

				$('#cnte').click();
			}
	});
	
 function getLng(lng)
{
//alert(lng);
$('#formcon').load("login.php?GetLng&lng="+lng);
}
function terminer(){

		/*  $('#frmconnect').ajaxSubmit({target:'#formcon',								
										
										url:'index.php?conect'})*/
								$("#frmconnect").validate({
									rules: {
													username: "required",
													password: "required"
										   }  
								 });
		var test=$("#frmconnect").valid();						 

		if(test==true){
		  $('#frmconnect').ajaxSubmit({target:'#formcon',url:'login.php?conect'})
	    //	clearForm('frmconnect',0);
		}else{
		 $('#formcon').css('display','none');
		 $('#formcon').html('');	
		}
	}
	  $(document).keypress(function(e) {
			if(e.which == 13) {
				terminer();
			}
		});
</script>
	<?php include $tpl ."footer.php";?>
		  
	

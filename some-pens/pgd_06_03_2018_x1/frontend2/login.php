<?php
include("../php.fonctions.php");
SESSION_START();
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
include("lang.php");

require_once('../connexion.php');



if(isset($_GET["conect"])){
//parcourir( $_POST) ;
//echo "hereee";return;

$login=false;
$msg="";
	$sql = "SELECT Login,Password as Pwd,IdVendeur,nom,prenom,idDepot,superviseur
			 FROM vendeurs t 
			 where Login like  '".$_POST["username"]."' ";
			

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
	 	
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);	;//echo $row['Pwd'];
/*ECHO $row['Pwd'];
echo "<br>".crypt($_POST['password'], $row['Pwd']);return;*/
	    if ($row['Pwd'] != crypt($_POST['password'], $row['Pwd'])) {//------------Incorect Password
			//echo "Mot de passe incorrect";	
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
		//--------------Get tournee ouverte--------------------
		$sql="SELECT IdTournee FROM tournees WHERE idVendeur=? AND dateDebut= convert(NVARCHAR, getdate(), 103) AND datefin IS null  ORDER BY IdTournee desc";
		$stmt=sqlsrv_query($conn,$sql,array($row['IdVendeur']),array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
				if( $stmt === false ) 
				{
						$errors = sqlsrv_errors();
						echo "Erreur : ".$errors[0]['message'] . " <br/> ";
						return;
				}
			$nRes = sqlsrv_num_rows($stmt);	
			//echo "herrrrrrrrrrrrre ".$sql; return;
			if($nRes==0)
			{ 
				$_SESSION['IdTournee']="";
			}
			else
			{
				sqlsrv_fetch($stmt) ;
				$IdTournee = sqlsrv_get_field( $stmt, 0);//echo "heree :  " . $IdTournee;
				$_SESSION['IdTournee']=$IdTournee;
			}
			//echo $_SESSION['IdTournee'];return;
		//-------------------------------------------------------	
				//$resT = mysql_fetch_assoc(mysql_query($sql2)) or die( mysql_error());
				//$_SESSION['IdDepot']=$row['idDepot'] ;
				$_SESSION['IdDepot']=$row['idDepot'] ;
				$_SESSION['superviseur']=$row['superviseur'] ;				
				$_SESSION['IdVendeur'] = $row['IdVendeur'] ; 
				$_SESSION['Vendeur'] = $row['nom']."  ".$row['prenom'] ; 
				//$_SESSION['Vendeur'] = $row['nom'] ; 
				$_SESSION['loggedin_time'] = time();  
				$_SESSION['test']="bonjour";
				//echo "here -> ";	echo $_SESSION['IdDepot']; return;
			?>
			<script language="javascript">
			$('#formcon').html('');
			$('#formcon').hide( 1000 );
			window.location.href = 'index.php';
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


include("headerRes.php"); ?>
<script src="js/screenfull.js"></script>

<!--
 <header class="row">
<div class="  headVente col-lg-12 col-md-12" >
							<a href="index.php"><img src="images/home.png" height="64" width="64" style="float:left;"> </a>
						&nbsp;> <span  Class="TitleHead" onclick="">Gestion des clients</span>
</div>
</header>
-->
<style>
.form-control {
    height: 64px;
    font-size: 24px;
}
.form-box {
    margin-top: 5px;
}
body{
	background:#055d9d;
}
@media (min-width: 1200px) {
body{
	font-size: 24px;
}
.form-control {
	padding: 22px 12px;
	font-size: 24px;
	height: 74px;
}
input.btn {
    height: 90px;
	font-size: 34px;
    padding: 0 20px;
}
.readonly{
	background:#fff
}

}
input[type=radio]{
		display:none;
	}

input[type=radio] + label{
		display:inline-block;
		font-weight:bold;
		/*padding: 38px 58px;line-height: 20px;*/
		width: 216px;
height: 100px;
line-height: 100px;
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
   <div class="row" id="">
                        <div class="  col-md-8  col-md-offset-2   col-sm-10 col-sm-offset-1   form-box">
					
                        	<div class="form-top">
                        		<div style="width:85%;float:left;text-align:center">
									<img src="../images/logo_print.png" />
                        		</div>
								<div style="width:15%;float:right;text-align:RIGHT">
					
									<a href="http://electroprotect.ma/pgd/app-release.apk" >
									<img src="../images/download.jpg" width="100" height="100" /></a>
                        		</div>
                        		<div class="form-top-left">
                        			<h3><?php echo $trad['login']['connect'] ; ?></h3>
								</div>
                            </div>
                            <div class="form-bottom">
			                    <form role="form"  id="frmconnect" action="" method="post" class="login-form">
			                    	<div class="form-group">
			                    		<label class="sr-only" for="form-username"><?php echo $trad['login']['name'] ; ?></label>
			                        	<input type="text" name="username" value=""  placeholder="<?php echo $trad['login']['name'] ; ?>" class="form-username form-control readonly" id="form-username">
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="form-password"><?php echo $trad['login']['pwd'] ; ?></label>
			                        	<input type="password"  value=""  name="password" placeholder="<?php echo $trad['login']['pwd'] ; ?>" class="form-password form-control" id="form-password">
			                        </div>
									<br>
			                        <input  type="button" class="btn" onClick="terminer()" value="<?php echo $trad['login']['ok'] ; ?>" />
			                    </form>
								<br>
								<div style="text-align:center; direction :ltr;">        
							<input id="radio1" <?php echo ($_SESSION['lang'] == 'fr') ?  'checked' : '' ; ?> type="radio" name="lng" value="francais" onclick="getLng('fr')" >
							<label  for="radio1"><strong><?php echo $trad['login']['fr'] ; ?></strong></label>
							<input id="radio2" type="radio" <?php echo ($_SESSION['lang'] == 'en') ?  'checked' : '' ; ?> name="lng" value="anglais" onclick="getLng('en')"  >
							<label  for="radio2"><?php echo $trad['login']['en'] ; ?></label>
							<input id="radio3" type="radio" <?php echo ($_SESSION['lang'] == 'ar') ?  'checked' : '' ; ?> name="lng" value="arabe" onclick="getLng('ar')"  >
							<label  for="radio3"><?php echo $trad['login']['ar'] ; ?></label>
								</div>
								<div style="text-align:<?php echo $_SESSION['align']; ?>;direction:<?php echo $_SESSION['dir']; ?>" id="formcon"></div>
		                    </div>

                        </div>
                    </div>
					
<script language="javascript" type="text/javascript">
function getLng(lng)
{
//alert(lng);
$('#formcon').load("login.php?GetLng&lng="+lng);
}
function terminer(){
		$("#frmconnect").validate({
									rules: {
													username: "required",
													password: "required"
										   }  
								 });
		var test=$("#frmconnect").valid();						 

		if(test==true){
		
			
		 $('#frmconnect').ajaxSubmit({target:'#formcon',url:'login.php?conect'});
		
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
<?php
include("footerRes.php");
?>



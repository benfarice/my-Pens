<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();

if(isset($_GET["conect"])){
//parcourir( $_POST) ;return;

$login=false;
$msg="";
	$sql = "SELECT Login,Password as Pwd,IdVendeur,nom,prenom
			 FROM vendeurs t 
			 where Login like  '".$_POST["username"]."' ";
			

	 $params = array();	
		$stmt=sqlsrv_query($conn,$sql,$params,array( "Scrollable" => 'static' ) );
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
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
	        $msg="Mot de passe incorrect. Veuillez réessayer.";
		}
		else
		{
			$login=true;
	        $msg="";

		}
	
	}
	else //------------------Login doesn't exist
	{
		$login=false;
	    $msg="Login incorrect. Veuillez réessayer.";
		
	}
	
		if($login==true)
		{
		//--------------Get tournee--------------------
		$sql="SELECT IdTournee FROM tournees WHERE idVendeur=? AND dateDebut= convert(NVARCHAR, getdate(), 103) AND datefin IS null";
		$stmt=sqlsrv_query($conn,$sql,array($row['IdVendeur']),array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
				if( $stmt === false ) 
				{
						$errors = sqlsrv_errors();
						echo "Erreur : ".$errors[0]['message'] . " <br/> ";
						return;
				}
			$nRes = sqlsrv_num_rows($stmt);	
			//echo "herrrrrrrrrrrrre ".$nRes; return;
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
		//-------------------------------------------------------	
				//$resT = mysql_fetch_assoc(mysql_query($sql2)) or die( mysql_error());
				$_SESSION['IdDepot']=1;
				$_SESSION['IdVendeur'] = $row['IdVendeur'] ; 
					$_SESSION['Vendeur'] = $row['nom']."  ".$row['prenom'] ; 
				$_SESSION['loggedin_time'] = time();  

			
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
			$('#formcon').html('<?php echo $msg; ?>');				 
			 $('#formcon').show("slow");
			 
			</script>
			<?php		
		}
	
				

exit;
}

include("headerRes.php"); ?>


<!--
 <header class="row">
<div class="  headVente col-lg-12 col-md-12" >
							<a href="index.php"><img src="images/home.png" height="64" width="64" style="float:left;"> </a>
						&nbsp;> <span  Class="TitleHead" onclick="">Gestion des clients</span>
</div>
</header>
-->
<style>
body{background:#152836;}
@media (min-width: 1200px) {
	body{font-size: 24px;}
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
</style>
   <div class="row">
                        <div class="  col-md-8  col-md-offset-2   col-sm-10 col-sm-offset-1   form-box">
					
                        	<div class="form-top">
                        			<div class="form-top-right">
									<img src="../images/logo_print.png" />
                        		</div>
                        		
								<div class="form-top-left">
                        			<h3>Se connecter à l'application:</h3>
                        
                        		</div>
                            </div>
                            <div class="form-bottom">
			                    <form role="form"  id="frmconnect" action="" method="post" class="login-form">
			                    	<div class="form-group">
			                    		<label class="sr-only" for="form-username">Login</label>
			                        	<input type="text" name="username" placeholder="Login..." class="form-username form-control" id="form-username">
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="form-password">Mot de passe</label>
			                        	<input type="password" name="password" placeholder="Mot de passe..." class="form-password form-control" id="form-password">
			                        </div>
									<br>
			                        <input  type="button" class="btn" onClick="terminer()" value="Connexion" />
			                    </form>
								<br>
								<div id="formcon"></div>
		                    </div>

                        </div>
                    </div>
	<script language="javascript" type="text/javascript">

function terminer(){

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


</script>
<?php
include("footerRes.php");
?>



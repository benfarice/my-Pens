<?php
require_once('../connexion.php');
require_once('../php.fonctions.php');

/****/

if(!isset($_SESSION))
{
session_start();
} ;

	
/****/
if(isset($_GET['logout'])){
	unset($_SESSION['Vendeur']);
	unset($_SESSION['IdVendeur']);
	unset($_SESSION['IdDepot']);
	?>
	<script language="javascript" > 
	window.location.href = 'login.php';
	</script>
<?php

exit;}
$disabled="";
/*unset($_SESSION['IdVendeur']);
unset($_SESSION['IdVisite']);
$_SESSION['IdVendeur']="5";
$_SESSION['IdDepot']="1";*/
/*$_SESSION['IdClient']="1";
$_SESSION['IdGroupe']="1";*/

// vider catalogue d'un vendeur ---- stock vendeur
unset($_SESSION['lignesFam']);
// vider catalogue de cmd_vendeur --- stock depot
unset($_SESSION['lignesCatV']);
// vider commande vendeur 
unset($_SESSION['lignesCmd']);
// vider commande client
unset($_SESSION['lignesCat']);


if(isset($_GET['map'])){

				$error="";


			if($_SESSION['IdTournee']=="")
			{ 
			//-----------------------------Demarage de tournée----------------------------------------
				$dateD=date("d/m/Y");
				$Hour=date("H:i");
				
				$reqInser1 = "INSERT INTO [dbo].[tournees]  ([dateDebut]  ,[heureDebut]  ,[idDepot] ,[idVendeur],idVehicule) 
								values(?,?,?,?,?)";
					$params1= array($dateD,$Hour,1,$_SESSION['IdVendeur'],0) ;
					
					$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
					if( $stmt1 === false ) {
						$errors = sqlsrv_errors();
						$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
					}
						//echo "INSERT ".$error;
						//return;
				//---------------------------IDTournee--------------------------------//
				$sql = "SELECT max(IdTournee) as IdTournee FROM tournees";
				$stmt2 = sqlsrv_query( $conn, $sql );
				if( $stmt2 === false ) {
					$errors = sqlsrv_errors();
					$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
				}
				//echo "IDTournee".$error;

				sqlsrv_fetch($stmt2) ;
				$IdTournee = sqlsrv_get_field( $stmt2, 0);
				//	ECHO "id session".$_SESSION['IdTournee'];return;
				$_SESSION['IdTournee']=$IdTournee;
			
				//header('Location: map.php');
				//location.href = "map.php";
				 ?>
					<script type="text/javascript"> 
					window.location = "map.php";
					</script>
					
				<?php	
				if($error=="" ) {
					 sqlsrv_commit( $conn );
					
				//unset($_SESSION['lignesCat']);
				} else {
					 sqlsrv_rollback( $conn );
					 echo $error;
				}
			}
			else
			{
				/*sqlsrv_fetch($stmt) ;
				$IdTournee = sqlsrv_get_field( $stmt, 0);//echo "heree :  " . $IdTournee;
				$_SESSION['IdTournee']=$IdTournee;*/
				//header('Location: mapClient.php');
				 ?>
					<script type="text/javascript"> 
					window.location = "mapClient.php";
					</script>
					
				<?php
			}
	
exit;
}


if(isset($_GET['Clot'])){

			$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}	
			//---------------------------Recuperer id tourner--------------------------------//
		$sql = "SELECT max(IdTournee) as IdTournee FROM tournees where idVendeur=?";
		$params= array($_SESSION['IdVendeur']);
		$stmtR = sqlsrv_query( $conn, $sql , $params);
		if( $stmtR === false ) {
			$error.="Erreur recuperation id tournee : ".sqlsrv_errors() . " <br/> ";
		}
		sqlsrv_fetch($stmtR) ;
		$IdTournee = sqlsrv_get_field( $stmtR, 0);
		//echo "dddd : ".$IdTournee;
		//$Date = date_create(date("Y-m-d"));
		$Date = (date("Y-m-d"));
		$Heure=date("H:i:s");
			//----------------------Cloturer la tournée--------------------------//

		$reqUpVi= "update   tournees set dateFin=$Date, heureFin='$Heure' where IdTournee = ? ";
		$paramsV= array($IdTournee) ;
		$stmtV = sqlsrv_query( $conn, $reqUpVi, $paramsV );
		if( $stmtV === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : modification cloture tourner  ".$errors[0]['message'] . " <br/> ";

		}
	
		if($error=="" ) {
			 sqlsrv_commit( $conn );
			 
			 ?>
				<script type="text/javascript"> 

					jAlert("La tournée a été clôturée","Message");			
				
					$.removeCookie('client');
				</script>
				
		<?php
		$_SESSION['IdTournee']="";
		//unset($_SESSION['IdTournee']);
		//unset($_SESSION['lignesCat']);
		} else {
			 sqlsrv_rollback( $conn );
			 echo $error;
			
			
		} 
		exit;	
}


	//parcourir($_SESSION['lignesFam']);return;
	include("header.php");
?>


<div class="Head">
	<DIV  class="heaLeft">
		<DIV class="Info">Bonjour <?php echo $_SESSION['Vendeur'];?></div>
	</div>
	<DIV  class="headRight">
		<a href="index.php?logout" style="float:right; padding-right:15px">
		<DIV class="signout">
		
		</div>
		</a>
	</div>
</div>
	

<DIV id="FormRes">

<a  href="#" id="Chg" class="cadreIndex hvr-grow  <?php //echo $disabled;href="chargementVendeur.php"?>" >
 
		<div class="childIndex"> 
		 <img src="../images/Demarrer_visite.png" >
		</div>
		<!--div class="titleIndex">Démarrer visite</div-->
	
</a>

<?php   
$page="chargementVendeur.php";
$sql = "select * from chargements c WHERE  c.idVendeur=? and c.etat=0";//dc.idColisage *

$params = array($_SESSION['IdVendeur']);	
$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}
$nRes = sqlsrv_num_rows($stmt);	
//echo $nRes;
if($nRes==0)
{ 
$page="cmd_vendeur.php";	
}
?>

<a href="<?php echo $page; ?>" class="cadreIndex hvr-grow " >
  
		<div  class="childIndex"> 
		<img src="../images/Commande_depot.png" >
		</div>

</a>	

	
<a href="#" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/depliants.png" >
		</div>
</a>
<a href="clients.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/Gestion_client.png" >
		</div>

</a>	
<a href="frais.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/Gestion_des_frais.png" >
		</div>

</a>
<a href="#" class="cadreIndex hvr-grow clot_tournee" id="Clot">
  
		<div  class="childIndex"> 
		<img src="../images/Cloturer_tournee.png" >
		</div>
	
</a>

	</div>
	
	<div id="res"></div>
<!--input type="hidden" id="IdTournee" value="<?php //if (isset( $_SESSION['IdTournee'])) echo  $_SESSION['IdTournee'];?>" /-->
<script language="javascript" type="text/javascript">	
$(document).ready(function(){
	 
	var i = 0;

	$('a.cadreIndex').each(function() {

     //  $(this).css("background-color", TabColor[i]);
		i = (i + 1) ;
    })
	
$("#Clot").click(function(){
	//alert('Clot');
	
			$('#res').load("index.php?Clot");
		
});
$("#Chg").click(function(){
/*var IdTournee=$("#IdTournee").val();
if(IdTournee!=""){
	window.location = "mapClient.php";
}else {
	
	window.location = "map.php";}*/

$('#res').load("index.php?map");


			
});
})
    var TabColor = [ 
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
	];
</script>

<?php
include("footer.php");
?>

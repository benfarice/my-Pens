<?php
require_once('../connexion.php');
require_once('../php.fonctions.php');

/****/

if(!isset($_SESSION))
{
session_start();
} ;
include("lang.php");
	
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
//echo "hereeee".$_SESSION['IdDepot']. " ".$_SESSION['superviseur'];

if(isset($_GET['map'])){

				$error="";


			if($_SESSION['IdTournee']=="")
			{ 
			//-----------------------------Demarage de tournée----------------------------------------
				$dateD=date("d/m/Y");
				$Hour=date("H:i");
				
				$reqInser1 = "INSERT INTO [dbo].[tournees]  ([dateDebut]  ,[heureDebut]  ,[idDepot] ,[idVendeur],idVehicule) 
								values(?,?,?,?,?)";
					$params1= array($dateD,$Hour,$_SESSION['IdDepot'],$_SESSION['IdVendeur'],0) ;
					
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
					//window.location = "map.php";
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
			 ?>
					<script type="text/javascript"> 
					window.location = "mapClient.php";
					</script>
					
				<?php
			/*
			else
			{
				/*sqlsrv_fetch($stmt) ;
				$IdTournee = sqlsrv_get_field( $stmt, 0);//echo "heree :  " . $IdTournee;
				$_SESSION['IdTournee']=$IdTournee;
				//header('Location: mapClient.php');
				 ?>
					<script type="text/javascript"> 
					window.location = "mapClient.php";
					</script>
					
				<?php
			}*/
	
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
			$error.= $trad['msg']['Erreur']." ".sqlsrv_errors() . " <br/> ";
		}
		sqlsrv_fetch($stmtR) ;
		$IdTournee = sqlsrv_get_field( $stmtR, 0);
		//echo "dddd : ".$IdTournee;
		//$Date = date_create(date("Y-m-d"));
		$Date = (date("Y-m-d"));
		$Heure=date("H:i:s");
			//----------------------Cloturer la tournée--------------------------//

		$reqUpVi= "update  tournees set dateFin=$Date, heureFin='$Heure' where IdTournee = ? ";
		$paramsV= array($IdTournee) ;
		$stmtV = sqlsrv_query( $conn, $reqUpVi, $paramsV );
		if( $stmtV === false ) {
			$errors = sqlsrv_errors();
			$error.= $trad['msg']['Erreur']." ".$errors[0]['message'] . " <br/> ";

		}
	
		if($error=="" ) {
			 sqlsrv_commit( $conn );			 
			 ?>
				<script type="text/javascript"> 

					//jAlert("La tournée a été clôturée","Message");			
				jAlert('<?php echo $trad['msg']['TourneeCloturer'];?>', '<?php echo $trad['titre']['Alert'];?>');
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
		<DIV class="Info"> <?php echo $trad['index']['Bienvenu'] ;echo $_SESSION['Vendeur'];?></div>
	</div>
	<DIV  class="headRight">
		<a href="index.php?logout" class="signoutsignout">
		<DIV class="signout">
		
		</div>
		</a>
	</div>
</div>
	

<DIV id="FormRes" STYLE="text-align:left">
<?php if($_SESSION['superviseur'] == 1){ ?>
<a  href="#" id="Chg" class="cadreIndex hvr-grow  <?php //echo $disabled;href="chargementVendeur.php"?>" >
 
		<div class="childIndex"> 
		 <img src="../images/demarrer_visite1.png" style="width:100px; height:101" >
		 	<div class="titleIndex1"> <?php echo $trad['index']['DemarreVisite'];?></div>
		</div>
		<!--div class="titleIndex">Démarrer visite</div-->
	
</a>
<?php } else {?>

<a  href="#" id="ChgPrev" class="cadreIndex hvr-grow  <?php //echo $disabled;href="chargementVendeur.php"?>" >
 
		<div class="childIndex"> 
		 <img src="../images/demarrer_visite1.png" style="width:100px; height:101" >
		 	<div class="titleIndex1"> <?php echo $trad['index']['DemarreVisite'];?></div>
		</div>
		<!--div class="titleIndex">Démarrer visite</div-->
	
</a>
<?php } ?>
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
		<img src="../images/Commande_depot.png" style="width:100px; height:101px" >
			<div class="titleIndex1"> <?php echo $trad['index']['CmdDepot'];?></div>
		</div>

</a>	

	
<a href="type_vente_depliant.php" class="cadreIndex hvr-grow ">  
		<div  class="childIndex"> 
		<img src="../images/depliants.png" style="width:100px; height:101px" >
		<div class="titleIndex1"> <?php echo $trad['index']['Depliant'];?></div>
		</div>
</a>
	
<a href="inventaire.php" class="cadreIndex hvr-grow clot_tournee" >  
		<div  class="childIndex"> 
		<img src="../images/invetory.png" style="width:100px; height:101px" >
		<div class="titleIndex1"> <?php echo $trad['index']['enqueteClient'];?></div>
		</div>	
</a>
<a href="frais.php" class="cadreIndex hvr-grow ">  
		<div  class="childIndex"> 
		<img src="../images/Gestion_des_frais.png" style="width:100px; height:101px" >
		<div class="titleIndex1"> <?php echo $trad['frais']['gestionFrais'];?></div>
		</div>

</a>
<a href="clients.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/Gestion_client.png" style="width:100px; height:101px" >
		<div class="titleIndex1"> <?php echo $trad['index']['GestionClient'];?></div>
		</div>

</a>
<a href="#" class="cadreIndex hvr-grow clot_tournee" id="Clot">
  
		<div  class="childIndex"> 
		<img src="../images/Cloturer_tournee.png" style="width:100px; height:101px" >
		<div class="titleIndex1"> <?php echo $trad['index']['ClotureTourne'];?></div>
		</div>
	
</a>
<?php 
// Si Vendeur connecté est superviseur
if($_SESSION['superviseur'] == 1){ ?>
<a href="commandeSuperviseur.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/cmdvdr_depot.png" style="width:100px; height:101px" >
		<div class="titleIndex1"> <?php echo $trad['label']['commandeAttente'];?></div>
		</div>
	
</a>
<a href="precommande.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/precommande.png" style="width:100px; height:101px" >
		<div class="titleIndex1"> <?php echo $trad['label']['Precommande'];?></div>
		</div>
	
</a>


<?php } ?>
	</div>
	
	<div id="res"></div>
<!--input type="hidden" id="IdTournee" value="<?php //if (isset( $_SESSION['IdTournee'])) echo  $_SESSION['IdTournee'];?>" /-->
<style>
.cadreIndex {
margin: 2px 17px;
}
</style>
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
$("#inventaire").click(function(){
	//alert('inventaire');
	
			$('#res').load("index.php?inventaire");
		
});
$("#Chg").click(function(){
	$('#res').load("index.php?map");
	});
	$("#ChgPrev").click(function(){
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

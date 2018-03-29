<?php
require_once('../connexion.php');
require_once('../php.fonctions.php');
/****/
if(!isset($_SESSION))
{
	session_start();
} ;
include("lang.php");
//initialiser le type de vente direct ou prevente
unset($_SESSION['Vente']);	
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

// vider catalogue d'un vendeur ---- stock vendeur
unset($_SESSION['lignesFam']);
// vider catalogue de cmd_vendeur --- stock depot
unset($_SESSION['lignesCatV']);
// vider commande vendeur 
unset($_SESSION['lignesCmd']);
// vider commande client
unset($_SESSION['lignesCat']);
// vider stock depot
unset($_SESSION['Stock']);
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
			if($error==""){
				 $_SESSION['Vente']=$_GET['Vente'];
			 ?>
					<script type="text/javascript"> 
					window.location = "mapClient.php";
					</script>
					
				<?php
			}
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
		<a href="indext.php?logout" class="signoutsignout">
		<DIV class="signout">
		
		</div>
		</a>
	</div>
</div>
	

<DIV id="FormRes" STYLE="text-align:left">
<?php if($_SESSION['superviseur'] == 1){ ?>


<a  href="#" id="ChgPrev" class="cadreIndex hvr-grow  <?php //echo $disabled;href="chargementVendeur.php"?>" >
 
		<div class="childIndex"> 
		 <img src="../images/demarrer_visite1.png" style="width:100px; height:101" >
		 	<div class="titleIndex1"> <?php echo $trad['index']['DemarreVisite'];?></div>
		</div>
		<!--div class="titleIndex">Démarrer visite</div-->
	
</a>
<!--a  href="#" id="Chg" class=" chpinvisible cadreIndex hvr-grow   " >
 
		<div class="childIndex"> 
		 <img src="../images/demarrer_visite1.png" style="width:100px; height:101" >
		 	<div class="titleIndex1"> <?php echo $trad['index']['VenteDirect'];?></div>
		</div>
	
</a-->

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
<a href="type_vente_depliant.php" class="cadreIndex hvr-grow ">  
		<div  class="childIndex"> 
		<img src="../images/depliants.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['index']['Depliant'];?></div>
		</div>
</a>


	<a href="clients.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/Gestion_client.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['index']['GestionClient'];?></div>
		</div>

</a>
<a href="encaissement_credit.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/encaissement_credit.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['label']['EncaissementCredit'];?></div>
		</div>
	
</a>
	<?php if($_SESSION['superviseur'] == 1){ ?>

<a href="precommande.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/precommande.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['label']['Precommande'];?></div>
		</div>
	
</a>
<a href="stockDepot.php" class="cadreIndex hvr-grow ">
		<div  class="childIndex"> 
		<img src="../images/Commande_depot.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['label']['stockDepot'];?></div>
		</div>	
</a>
	<?php }?>
<div  id="RetourFacture" class="cadreIndex hvr-grow clot_tournee" >  
		<div  class="childIndex"> 
		<img src="../images/invetory.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['label']['RetourFacture'];?></div>
		</div>	
</div>

<a href="inventaire.php" class="cadreIndex hvr-grow clot_tournee" >  
		<div  class="childIndex"> 
		<img src="../images/invetory.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['index']['enqueteClient'];?></div>
		</div>	
</a>
<a href="frais.php" class="cadreIndex hvr-grow ">  
		<div  class="childIndex"> 
		<img src="../images/Gestion_des_frais.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['frais']['gestionFrais'];?></div>
		</div>

</a>

<a href="<?php echo $page; ?>" class="cadreIndex hvr-grow " >
  
		<div  class="childIndex"> 
		<img src="../images/Commande_depot.png" style="width:100px; height:100px" >
			<div class="titleIndex1"> <?php echo $trad['index']['CmdDepot'];?></div>
		</div>

</a>	

<?php 
// Si Vendeur connecté est superviseur
if($_SESSION['superviseur'] == 1){ ?>
<a href="commandeSuperviseur.php" class="cadreIndex hvr-grow ">
  
		<div  class="childIndex"> 
		<img src="../images/cmdvdr_depot.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['label']['commandeAttente'];?></div>
		</div>
	
</a>
<?php } ?>
<a href="#" class="cadreIndex hvr-grow clot_tournee" id="Clot">
  
		<div  class="childIndex"> 
		<img src="../images/Cloturer_tournee.png" style="width:100px; height:100px" >
		<div class="titleIndex1"> <?php echo $trad['index']['ClotureTourne'];?></div>
		</div>
	
</a>
</div>
<div id="res"></div>
<div id="boxR" STYLE="text-align:center;background:#fff; padding-top:20px">
		<a  href="retour_fac.php" class="cadreIndex hvr-grow clot_tournee" >  
				<div  class="childIndex" STYLE="width:200px"> 
				<img src="../images/retour.png" style="width:100px; height:100px" >
				<div class="titleIndex1"> <?php echo $trad['label']['RetourFacture'];?></div>
				</div>	
		</a>
		<a  href="validation_retour.php" class="cadreIndex hvr-grow clot_tournee" >  
				<div  class="childIndex"> 
					<img src="../images/precommande.png" style="width:100px; height:100px" >
					<div class="titleIndex1"> <?php echo $trad['label']['ValidationRetour'];?></div>
				</div>	
		</a>
		<div class="btnV" style="text-align:center;margin-top:40px">
	<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="Fermer('boxR')"/>
	
	</div>
</div>
<!--input type="hidden" id="IdTournee" value="<?php //if (isset( $_SESSION['IdTournee'])) echo  $_SESSION['IdTournee'];?>" /-->
<style>
.cadreIndex {
	margin: 2px 19px;
}
</style>
<script language="javascript" type="text/javascript">	
function Fermer(box){
	$("#"+box).dialog('close');
}
$(document).ready(function(){
		$('#boxR').dialog({
					autoOpen		:	false,
					width			:	620,/*1100*/
					height			:	320,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Info Client',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						},
						
					 }
			});	

			
	var i = 0;
	$('a.cadreIndex').each(function() {
     //  $(this).css("background-color", TabColor[i]);
		i = (i + 1) ;
    })
	
$("#RetourFacture").click(function(){
	var id=<?php echo $_SESSION['superviseur'];?>;
	if (id==1){
		$('#boxR').dialog('open');
	}else {
		document.location.href="retour_fac.php";
	}
});

	$("#Clot").click(function(){
	//alert('Clot');
	$('#res').load("indext.php?Clot");
		
});
$("#inventaire").click(function(){
	//alert('inventaire');	
	$('#res').load("indext.php?inventaire");
		
});
$("#Chg").click(function(){
	//Vente=1 càd c'est vente direct
		$('#res').load("indext.php?map&&Vente=1");
	});
$("#ChgPrev").click(function(){
		//Vente=0 càd c'est prevente  
	$('#res').load("indext.php?map&&Vente=0");
});
})

/*
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
	];*/
</script>

<?php
include("footer.php");
?>

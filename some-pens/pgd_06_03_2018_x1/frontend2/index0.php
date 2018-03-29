<?php
require_once('../connexion.php');
require_once('../php.fonctions.php');
include("header.php");
session_start();
$disabled="";
$_SESSION['IdVendeur']="2";
//$_SESSION['IdClient']="3";
//$_SESSION['IdVisite']="1";
$_SESSION['IdDepot']="1";
// vider catalogue d'un vendeur ---- stock vendeur
unset($_SESSION['lignesFam']);
// vider catalogue de cmd_vendeur --- stock depot
unset($_SESSION['lignesCatV']);

// vider commande vendeur 
unset($_SESSION['lignesCmd']);
// vider commande client
unset($_SESSION['lignesCat']);


unset($_SESSION['IdClient']);
unset($_SESSION['IdGroupe']);
//verifier si vendeur a des chargmeent
$sql = "select * from chargements c 
WHERE  c.idVendeur=? and c.etat=0";//dc.idColisage *
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
$disabled="disabled";
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
		
			
		</script>
		
<?php
//unset($_SESSION['lignesCat']);
} else {
     sqlsrv_rollback( $conn );
	 echo $error;
	
    
}
exit;	
}


	//parcourir($_SESSION['lignesFam']);return;
?>
<a href="chargementVendeur.php" id="Chg" class="cadreIndex hvr-grow <?php //echo $disabled;?>" >
		<div  class="childIndex"> 
		CHARGEMENT DEPOT
		</div>
	
</a>
	
	

<a href="cmd_vendeur.php" class="cadreIndex hvr-grow" >
  
		<div  class="childIndex"> 
		COMMANDE DEPOT
		</div>

</a>	

<a href="#" class="cadreIndex hvr-grow" id="Clot">
  
		<div  class="childIndex"> 
		CLOTURER TOURNEE
		</div>
	
</a>	
<a href="clients.php" class="cadreIndex hvr-grow">
  
		<div  class="childIndex"> 
		GESTION CLIENT
		</div>

</a>	
<a href="frais.php" class="cadreIndex hvr-grow">
  
		<div  class="childIndex"> 
		GESTION DES FRAIS
		</div>

</a>	
	<div id="res"></div>
<script language="javascript" type="text/javascript">	
$(document).ready(function(){
	 
	var i = 0;

	$('a.cadreIndex').each(function() {

       $(this).css("background-color", TabColor[i]);
		i = (i + 1) ;
    })
	
$("#Clot").click(function(){
	//alert('Clot');
			$('#res').load("index.php?Clot");
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

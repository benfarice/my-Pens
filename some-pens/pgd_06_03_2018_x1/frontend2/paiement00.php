<?php
require_once('../connexion.php');
include("fonctionCalcule.php");


if(!isset($_SESSION))
{
session_start();
}
function Total(){
	$Total=0;
	$test="";
	if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {
	foreach($_SESSION['lignesCat'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
		
			//$Total+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
		//	$Total+=($contenu["Qte"]*$contenu["PriceUnite"]) ;
		if($contenu['UniteVente']=='Box'){
		$Total+=$contenu['NbrColisage']*str_replace(" ","",$contenu['Qte'])*str_replace(" ","",$contenu['PriceUnite']);
		}else 
		{
				$Total+=str_replace(" ","",$contenu['Qte'])*str_replace(" ","",$contenu['PriceUnite']);
		}
     // $test=$test." ".$contenu['NbrColisage'];
			}
	}
	return($Total);
}
$IdDepot=$_SESSION['IdDepot'];
include("lang.php");
include("header.php");
if (isset($_GET['ChoixTypeReg'])){

	if (isset($_GET['Precommande'])){
		$IdClient=$_GET["IdClient"];
		$TotalFac=$_GET["TotalFac"];
	}else {
		$IdClient=$_SESSION["IdClient"];
		$TotalFac=Total();
	}

?>
<style>
input[type="checkbox"]{
  width: 160px; /*Desired width*/
  height: 50px; /*Desired height*/
vertical-align: bottom;
  position: relative;
  top: 3px;
  *overflow: hidden;
 
}
</style>

<?php 
//---------------------------Recuperer Credit Client--------------------------------//
/*$sql = "SELECT MontantC Credit FROM Credit  WHERE idClient=? AND IdDepot=?"; 
$params3= array(				
				$_SESSION["IdClient"],
				$IdDepot				
) ;*/
/*
parcourir($params3);
echo $sql;*/
/*

$sql = "SELECT sum(Avance) Avance FROM Avance WHERE idClient=? AND idDepot=? AND ModePaiement!='Credit'"; 
$params3= array(				
				$_SESSION["IdClient"],
				$IdDepot				
) ;

$stmtR = sqlsrv_query( $conn, $sql,$params3 );
if( $stmtR === false ) {
    $error.="Erreur recuperation Avance : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmtR) ;
$Avance = sqlsrv_get_field( $stmtR, 0);

*/

/*

$sql = "SELECT sum(Avance) Credit FROM Avance WHERE idClient=? AND idDepot=? AND ModePaiement='Credit'"; 
$params3= array(				
				$_SESSION["IdClient"],
				$IdDepot				
) ;

$stmtR = sqlsrv_query( $conn, $sql,$params3 );
if( $stmtR === false ) {
    $error.="Erreur recuperation Avance : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmtR) ;
$CreditClt = sqlsrv_get_field( $stmtR, 0);
*/

	$params3= array(				
				$IdClient,
				$IdDepot				
		) ;
		
			$CreditClt=creditClient($params3,$conn)[0];
			$Montant=creditClient($params3,$conn)[1];
			
// recuperer plafond credit vdr
/*
$sql = "SELECT plafond FROM vendeurs WHERE  idDepot=? AND IdVendeur=?"; 
$params3= array(
				$IdDepot,
				$_SESSION["IdVendeur"]
) ;

$stmtR = sqlsrv_query( $conn, $sql,$params3 );
if( $stmtR === false ) {
    $error.="Erreur recuperation Plafond vdr : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmtR) ;
$Plafond = sqlsrv_get_field( $stmtR, 0);


// recuperer  les credits faitent par le vendeur

$sql = "SELECT sum(Avance) Credit FROM Avance WHERE IdVendeur=? AND idDepot=? AND ModePaiement='Credit'";
$params3= array(
				$_SESSION["IdVendeur"],
				$IdDepot
) ;

$stmtR = sqlsrv_query( $conn, $sql,$params3 );
if( $stmtR === false ) {
    $error.="Erreur recuperation credit vdr : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmtR) ;
$CreditVdr = sqlsrv_get_field( $stmtR, 0);
//le reste du credit autorisé par le vendeur x
$RestCreditVdr=floatval($Plafond)-floatVal($CreditVdr);*/
?>
	<!---input type="hidden" id="RestCreditVdr"  name="RestCreditVdr" value="<?php echo $RestCreditVdr; ?>"/-->
<div class="Fac">
<?php echo $trad['label']['TotalFac']; ?>
	<span id="Fac"><?php // montant de la cmd actuelle seulement
	echo number_format($TotalFac, 2, '.', ' ');?>
	</span>
</div>
<?php /*
ECHO "floatval(CreditClt)".$CreditClt;
ECHO "floatval(Avance)".$Avance;return;*/

if(($CreditClt)!=1) 
{ ?>
<div class="CreditClt Fac">
<?php  echo $trad['label']['CreditClt'];?> <div class="Credit">
	<input type="hidden" id="EncienCredit"  name="EncienCredit" value="<?php echo $Montant; ?>"/>
<?php echo number_format($Montant, 2, '.', ' ');?><?php echo $trad['label']['riyal']; ?>
 </div>
 <span >
 <input type="checkbox" id="CheckboxC" name="CheckboxC" >
 </span>
</div>
<?php 
}else {
	if($Montant!=0)// si l'avance n'est pas nulle on l'affiche{
		{?>
		<div class="CreditClt Fac" style="width:300px;">
		Avance Client :<SPAN class="">
			<input type="hidden" id="EncienAvance"  name="EncienAvance" value="<?php echo $Montant; ?>"/>
		<?php echo number_format($Montant, 2, '.', ' ');?><?php echo $trad['label']['riyal']; ?>
		 </SPAN>
		 
		</div>
		<?php 
		}else {?><input type="hidden" id="EncienAvance"  name="EncienAvance" value="0"/>
		<?php }
}?>
<div class="clair"></div>
<div id="Espece" class="CadreT">
<!-- totalFac designe total de la cmd + credit s'il à cocher le credit -->
	<input type="hidden" id="TotalFac" value="<?php echo $TotalFac; ?>" name="TotalFac"/><br>
	<div class="inputLabel" ><input type="button" value="<?php // echo $trad['label']['Espece'];?>" id="BtnEspece" width="500"></div>
	<div class="inputData"><input type="text" id="MtEspece" name="MtEspece"  onblur="CalculReste()" value="<?php echo number_format($TotalFac, 2, '.', ' '); ?>" class="ConvertDecimal nbr" onkeypress="return isEntier(event) " size="20" name="Num"></div>
</div>


<div id="Cheque" class="CadreT">
	<div class="inputLabel" ><input type="button"  value="<?php // echo $trad['label']['Cheque'];?>" id="BtnCheque"></div>
	<div class="inputData"><input type="text" value=" " name="MtCheque"  class="" onblur="CalculReste()" id="MtCheque" size="20" name="Num">
	<input  class="jfilestyle" data-input="false" type="file" name="file" control="1" id="file" size="10" tabindex="1" value="" />
	</div>
</div>  


<div id="Credit"  class="CadreT">
	<div class="inputLabel" ><input type="button" value="<?php  //echo $trad['label']['Credit'];?>" id="BtnCredit"></div>
	<div class="inputData"><input type="text" value=" " class="" id="MtCredit" name="MtCredit" size="20" name="Num" readonly></div>
</div>  


<script src="js/jquery-filestyle.min.js" type="text/javascript"></script>
<link href="css/jquery-filestyle.css"  rel="stylesheet" />
<script language="javascript" type="text/javascript">

function TerminerCmd(){
		var test=true;	
/*	var files = $('#formReg:input[type=file]').get(0).files;

		if(files.length==0){
			jAlert("<?php echo $trad['msg']['FichierObligatoire'];?>","<?php echo $trad['titre']['Alert'];?>");
			test=false;
		  return false;			
		}*/
		if(test==true){									
		$('#formReg').ajaxSubmit({
							target:'#resReg',
							url:'catalogue4.php?TerminerCmd',
								method			:	'post',
							success: function(data) {  	
							//alert(data);							
								//	Imprimer(data);									
						
							  }
							});
		//patienter('resReg');
		}

	}
function Imprimer(IdFacture){
			 options = "Width=1280,Height=800" ;
		  window.open( 'facture.print.php?IdFacture='+IdFacture, "edition", options ) ;
		
	}
$(document).ready(function() {
	$(":file").jfilestyle({input: false,buttonText: "<img src='img/folder.png' /><?php echo $trad['button']['parcourir'] ; ?>"});
});

	$("#CheckboxC").click(function() {	
		var TotalFac=$("#TotalFac").val().replace(" ","");
		if($("#CheckboxC").is(':checked')){		
	
			var som=parseFloat(TotalFac)+parseFloat($("#EncienCredit").val());
			$("#TotalFac").val(number_format(som, 2, ".", " "));
			$("#MtEspece").val($("#TotalFac").val());
				$("#MtCredit").val("");
				$("#MtCheque").val("");
		} else {
				var som=parseFloat(TotalFac)-$("#EncienCredit").val();
			$("#TotalFac").val(number_format(som, 2, ".", " "));
			$("#MtEspece").val($("#TotalFac").val());
				$("#MtCredit").val("");
				$("#MtCheque").val("");
		
		}
	});
	$("#BtnEspece").click(function() {
		var TotalFac=$("#TotalFac").val().replace(" ","");
		//$("#MtEspece").val($("#TotalFac").val());
		$("#MtEspece").val(number_format(TotalFac, 2, ".", " ") );
		$("#MtCredit").val("");
		$("#MtCheque").val("");
	});
	$("#BtnCheque").click(function() {
			var TotalFac=$("#TotalFac").val().replace(" ","");
	//	$("#MtCheque").val($("#TotalFac").val());
		$("#MtCheque").val(number_format(TotalFac, 2, ".", " ") );
		$("#MtCredit").val("");
		$("#MtEspece").val("");
	
	});
	$("#BtnCredit").click(function() {
		
		var MtCheque=$("#MtCheque").val().replace(" ","");
		
		if (MtCheque!=""){
			var TotalFac=$("#TotalFac").val().replace(" ","");
			//$("#MtCredit").val($("#TotalFac").val() );
			$("#MtCredit").val(number_format(TotalFac, 2, ".", " ") );
			$("#MtEspece").val("");
			$("#MtCheque").val("");
		}
	});
	
 function CalculReste(){


	var TotalFac=$("#TotalFac").val().replace(" ","");
		if(($("#MtEspece").val()!=" ") && ($("#MtEspece").val()!=""))var MtEspece=$("#MtEspece").val().replace(" ","");
		else var MtEspece=0;
		
		if(($("#MtCheque").val()!=" ") &&($("#MtCheque").val()!=""))var mtCheque=$("#MtCheque").val().replace(" ","");
		else var mtCheque=0;
		
	var MtAvance=0;
	if ( $("#EncienAvance").length ) { // si l'avance existe
		var MtAvance=$("#EncienAvance").val().replace(" ","");
		MtAvance=number_format(MtAvance, 2, ".", " ");
	}
	/*mtCheque=number_format(mtCheque, 2, ".", " ");
	MtEspece=number_format(MtEspece, 2, ".", " ");*/
	
	 var mtSaisi=parseFloat(MtEspece)+parseFloat(mtCheque)+parseFloat(MtAvance);	 
//	alert(parseFloat(MtEspece));alert((mtCheque));alert(parseFloat(MtAvance));
	 if(parseFloat(mtSaisi)<parseFloat(TotalFac)){
		
		 var diff=parseFloat(TotalFac)-parseFloat(mtSaisi);

			$("#MtCredit").val(diff);
	 }else {
			$("#MtCredit").val("");
	 }
 }
  function CalculReste2(){
	var MtEspece=$("#MtEspece").val().replace(" ","");
	var TotalFac=$("#TotalFac").val().replace(" ","");
	var mtCheque=$("#MtCheque").val().replace(" ","");
	var mtCredit=$("#MtCredit").val().replace(" ","");
	if(MtEspece=="") MtEspece=0;
	if(mtCredit=="") mtCredit=0;
		var mtSaisi=parseFloat(MtEspece)+parseFloat(mtCheque);

	if(parseFloat(mtSaisi)<parseFloat(TotalFac)){
	//	alert(mtSaisi);
		var ResteaPaye=TotalFac-mtSaisi;
		 $("#MtCredit").val(number_format(ResteaPaye, 2, ".", " "));
		 $("#Credit").css("display","block");
	}else {
		//if(parseFloat(mtCheque)==parseFloat(TotalFac)){
			 // $("#MtEspece").val("");
			  $("#MtCredit").val("");
			 // $("#Credit").css("display","none");
		//}
	}
  }
	
	</script>

<?php 
exit;
} ?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>&nbsp;&nbsp;
<div Class="TitleHead" >
<a href="catalogue4.php?affGamme.php"><?php  echo $trad['label']['Gamme'];?> </a>&nbsp;&nbsp;>>&nbsp;&nbsp;
<?php  echo $trad['label']['Paiement'];?> <?php  //echo date("d/m/y h:i");?></div></div>
<div id="formRes" style="MAX-height:790px; padding:10px;">

<form id="formReg" action="" method="post" name="formReg">
	
	<div id="FormPaiement" STYLE="height:430px;">
	</div>
	<DIV style="width:100%;text-align:center">
	<input type="button" value="<?php  echo $trad['button']['Valider'];?>" class="btn"  onclick="TerminerCmd()"/>
	</div>
</form>
<div id="resReg"></div>
</div>
<div class="clear"></div>

<div class="bottomVente">

</div>
<script language="javascript" type="text/javascript">

$(document).ready(function () {
	var url='paiement.php?ChoixTypeReg';
	$('#FormPaiement').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url)
});


	</script>	
	
	
	
	
	
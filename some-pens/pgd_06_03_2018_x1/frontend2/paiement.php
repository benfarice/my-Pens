<?php
require_once('../connexion.php');
include("fonctionCalcule.php");
if(!isset($_SESSION))
{
	session_start();
}
$IdDepot=$_SESSION['IdDepot'];
include("lang.php");
function tofloat($num) {
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
   
    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    } 

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
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
if (isset($_GET['ChoixTypeReg'])){
	?>
	<link rel="stylesheet" href="css/jquery.keypad.css">
<link href="css/jquery-filestyle.css"  rel="stylesheet" />
<style>
	.keypad-popup, .keypad-inline, .keypad-key, .keypad-special { font-size: 30px; }
	.keypad-special { width: 6.25em; margin: 0.125em; }
	.keypad-spacebar { width: 15.25em; margin: 0.145em; }
	.keypad-key, .keypad-tab, .keypad-space { width: 4em; margin: 0.145em; }
	.keypad-half-space { width: 3em; margin-left: 0.145em; }
input[type="checkbox"]{
  width: 160px; /*Desired width*/
  height: 50px; /*Desired height*/
vertical-align: bottom;
  position: relative;
  top: 3px;
  *overflow: hidden;
 
}
div.jfilestyle label {
    display: inline-block;
    border: 1px solid #c0c0c0;
    background: #ffffff;
    padding: 10px 50px;
    color: #0662ba;
}
</style>
	<?php
	//regelement du paiement par la page precommande du supperviseur
	if (isset($_GET['Precommande'])){
		$IdClient=$_GET["IdClient"];
		$TotalFac=$_GET["TotalFac"];
			$params3= array(				
				$IdClient,
				$IdDepot				
		) ;
		
			$CreditClt=creditClient($params3,$conn)[0];
			$Montant=creditClient($params3,$conn)[1];
	}
	//reglement du paiement par la page encaissement credit
	else if  ( (isset($_GET['Encaissement'])) && ($_GET['Encaissement']=='encaissement')) {
		$IdClient=$_GET["IdClient"];
		$TotalFac=$_GET["TotalFac"];
	
	}//reglement du paiement par la page catalogue4 du vente supperviseur
	else {
		$IdClient=$_SESSION["IdClient"];
		$TotalFac=Total();
			$params3= array(				
				$IdClient,
				$IdDepot				
		) ;
		
			$CreditClt=creditClient($params3,$conn)[0];
			$Montant=creditClient($params3,$conn)[1];
	}


			
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

<?php
if ( (isset($_GET['Encaissement'])) && ($_GET['Encaissement']=='encaissement')) {
?>
<form id="formReg" action="" method="post" name="formReg">
<div id="FormPaiement" STYLE="height:360px;  Width:100%;padding-left:30px;padding-TOP:20px">
<input type="hidden" value="<?php echo $IdClient;?>" name="IdClient">
<?php } ?>
<div class="Fac">

<?php echo $trad['label']['TotalFac']; ?>
	<span id="Fac"><?php // montant de la cmd actuelle seulement
	echo number_format($TotalFac, 2, '.', ' ');?>
	</span>
</div>
<?php 
//si le paiement va etre regler depuis la page encaissement credit alors le clt n'a pas une avance comme il faut pas afficher le credit parce que le total facture contient le crédit total du clt
if  (!isset($_GET['Encaissement'])){ 

	// le casa où le client a une crédit
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
			$TotalFac=tofloat($TotalFac)-tofloat($Montant);
			if($TotalFac<0) $TotalFac=0;
	}
}?>	
<div class="clair"></div>
<div id="Espece" class="CadreT" STYLE=" ">
<!-- totalFac designe total de la cmd + credit s'il à cocher le credit -->
	<input type="hidden" id="TotalFac" value="<?php echo $TotalFac; ?>" name="TotalFac"/>
	<div class="inputLabel" ><input type="button" value="<?php // echo $trad['label']['Espece'];?>" id="BtnEspece" width="500"></div>
	<div class="inputData"><input type="text" id="MtEspece" name="MtEspece"  onblur="" value="<?php echo number_format($TotalFac, 2, '.', ' '); ?>" class="ConvertDecimal nbr" onkeypress="return isEntier(event) " size="20" name="Num"></div>
</div>

<DIV class="clear"></div>
<div id="Cheque" class="CadreT">
	<div class="inputLabel" ><input type="button"  value="<?php // echo $trad['label']['Cheque'];?>" id="BtnCheque"></div>
	<div class="inputData"><input type="text" value=" " name="MtCheque"  class="" onblur="" id="MtCheque" readonly size="20" name="Num">
	<input  class="jfilestyle" data-input="false" type="file" name="file" control="1" id="file" size="10" tabindex="1" value="" />
	</div>
</div>  
<DIV class="clear"></div>

<div id="Credit"  class="CadreT">
	<div class="inputLabel" ><input type="button" value="<?php  //echo $trad['label']['Credit'];?>" id="BtnCredit"></div>
	<div class="inputData"><input type="text" value=" " class="" id="MtCredit" name="MtCredit" size="20" name="Num" readonly></div>
</div>  

<?php if ( (isset($_GET['Encaissement'])) && ($_GET['Encaissement']=='encaissement')) {
?>
</div> <!-- fin div paiement>-->
</form>
<div id="resReg"></div>
<?php } ?>

<script src="js/jquery.plugin.min.js"></script>
<script src="js/jquery.keypad.js"></script>
<script type="text/javascript" src="js/jquery.keypad-fr.js"></script>
<script src="js/jquery-filestyle.min.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
$(function () {

	// quand on met le curseur dans l'input on le vide
	 $("#MtEspece").unbind().click(function() {

			$('#MtCheque').val($('#MtEspece').val()); 
				//if($('#MtEspece').val()!="") $('#MtEspece').val('');
				$('#MtEspece').val('');
	});
 
 	 $("#MtCheque").unbind().click(function() {
				if(($('#TotalFac').val()!="") && ($('#TotalFac').val()!="0") ){
				$('#MtCredit').val($('#MtCheque').val()); 
				$('#MtCheque').val('');}
	});
	$( "#MtEspece" ).blur(function() {
		//$('#MtEspece').val(number_format($('#MtEspece').val(),2,".",",")); 
	});
	  
$.keypad.addKeyDef('UPPER', 'upper', function(inst) { 
        this.val(this.val().toUpperCase()).focus(); 
    }); 
	$('#MtEspece').keypad({
		upperText: 'U/C', 
		upperStatus: 'Convert to upper case', 
		layout: ['123' + $.keypad.CLEAR, 
			'456' + $.keypad.BACK, 
			'789'  + $.keypad.CLOSE , 
			'0.' ] ,
	  onClose: function(value, inst) { 
			OnChangeEspece();
			$('#MtEspece').val(number_format($('#MtEspece').val(),2,"."," ")); 
		} 
	  });
	function char_count(str, letter) 
		{
				 var letter_Count = 0;
				 for (var position = 0; position < str.length; position++) 
				 {
					if (str.charAt(position) == letter) 
					  {
					  letter_Count += 1;
					  }
				  }
				  return letter_Count;
		}
	function OnChangeEspece(){
		
        	var TotalFac=$("#TotalFac").val().replace(" ","");
			//$("#MtEspece").val($("#TotalFac").val());

		var count = char_count($("#MtEspece").val(), '.');
		
	//alert(count);
			if(($("#MtEspece").val()!=" ") && ($("#MtEspece").val()!="")&& (count<=1)&& ($("#MtEspece").val()!=".")) var MtEspece=$("#MtEspece").val().replace(" ","");
			else var MtEspece=0;
	//	alert(MtEspece);
			var Reste=parseFloat(TotalFac)-parseFloat(MtEspece);
			//alert(Reste);
			if(Reste>0) $("#MtCheque").val(number_format(Reste, 2, ".", " ") ); else $("#MtCheque").val("");
			$("#MtCredit").val("");
				$('#MtCheque').keypad({
				upperText: 'U/C', 
				upperStatus: 'Convert to upper case', 
				layout: ['123' + $.keypad.CLEAR, 
					'456' + $.keypad.BACK, 
					'789'   + $.keypad.CLOSE , 
					'0.' ] ,
				onClose: function(value, inst) { 
						OnChangeCheque();	
						$('#MtCheque').val(number_format($('#MtCheque').val(),2,"."," ")); 						
					}
			  });
	}

	function OnChangeCheque(){
		var count = char_count($("#MtEspece").val(), '.');
		var count2 = char_count($("#MtCheque").val(), '.');
						var TotalFac=$("#TotalFac").val().replace(" ","");
						//$("#MtEspece").val($("#TotalFac").val());
						if(($("#MtCheque").val()!=" ") && ($("#MtCheque").val()!="") &&  (count2<=1)&& ($("#MtCheque").val()!="."))var MtCheque=$("#MtCheque").val().replace(" ","");
						else var MtCheque=0;
						
						if(($("#MtEspece").val()!=" ") && ($("#MtEspece").val()!="")&& (count<=1)&& ($("#MtEspece").val()!="."))var MtEspece=$("#MtEspece").val().replace(" ","");
						else var MtEspece=0;
						
						var Reste=parseFloat(TotalFac)-(parseFloat(MtCheque)+parseFloat(MtEspece));
				
						if(Reste>0) $("#MtCredit").val(number_format(Reste, 2, ".", " ") ); else $("#MtCredit").val("");
	}
	
});
	$("#BtnEspece").click(function() {
		var TotalFac=$("#TotalFac").val().replace(" ","");
		$("#MtEspece").val(number_format(TotalFac, 2, ".", " ") );
		$("#MtCredit").val("");
		$("#MtCheque").val("");
	});
	
	$("#BtnCheque").click(function() {
		var TotalFac=$("#TotalFac").val().replace(" ","");
		$("#MtCheque").val(number_format(TotalFac, 2, ".", " ") );
		$("#MtCredit").val("");
		$("#MtEspece").val("");
		
		//on active le clavier pr la saisie ds la zone chèque
			$('#MtCheque').keypad({
				upperText: 'U/C', 
				upperStatus: 'Convert to upper case', 
				layout: ['123' + $.keypad.CLEAR, 
					'456' + $.keypad.BACK, 
					'789'   + $.keypad.CLOSE , 
					'0.' ] ,
				onClose: function(value, inst) { 
						OnChangeCheque();	
						$('#MtCheque').val(number_format($('#MtCheque').val(),2,"."," ")); 						
					}
			  });
	
	});
	
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
						//		window.location='mapClient.php';
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
	</script>
<?php 
exit;
} 
include("header.php");
?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>&nbsp;&nbsp;
<div Class="TitleHead" >
<a href="catalogue4.php?affMarque.php"><?php  echo $trad['label']['Marque'];?> </a>&nbsp;&nbsp;>>&nbsp;&nbsp;
<?php  echo $trad['label']['Paiement'];?> <?php  //echo date("d/m/y h:i");?></div></div>
<div id="formRes" style="MAX-height:790px; padding:10px;">

<form id="formReg" action="" method="post" name="formReg">
	
	<div id="FormPaiement" STYLE="height:360px;">
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
	
	
	
	
	
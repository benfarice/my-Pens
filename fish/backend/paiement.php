<?php
session_start();
if(!isset($_SESSION['username'])){
header('Location: index.php');
exit();
}
require_once('init.php');
include("fonctionCalcule.php");
if(isset($_GET['TerminerReg'])){

	//parcourir($_POST);return;
	  $error="";
	  
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $con ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//echo $RefFicheCh;return;
$Date = date_create(date("Y-m-d H:i"));
$Heure=date("H:i:s");
$i=0;
$target_path="";

	if(isset($_FILES['file']))
			{
				$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
				$file_extension = end($ext); // Store extensions in the variable.
				$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
				if (!file_exists("all_file/")) {
					mkdir("all_file/", 0777, true);
				}
				$target_path = "all_file/" . $nameFile;     // Set the target path with a new name of image.
				
					$error="";
					
					if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
						{
						?>
									<script type="text/javascript"> 
										alert("error file");
									</script>
						<?php
						return;
						}
			}
			else
			{
				$target_path = "";     // Set the target path with a new name of image.
			}
			
			
  		$CodeReg= "NR".Increment_Chaine_F("CodeReg","reglements","IdReg",$con,"",array());	
			  	$reqInser2 = "INSERT INTO  reglements(CodeReg,DateReg,Heure,MtEspece,MtCheque,MtCredit,NumCheque,PhotoCheque
				,BqCheque,NumTransCredit,CodeBuySel,TypeOperator) 
					values (?,?,?,?,?,?,?,?,?,?,?,?)";
			$params2= array(
					$CodeReg,
					date("Y-m-d"),
					$Heure,
					floatval(tofloat($_POST['MtEspece'])),
					floatval(tofloat($_POST['MtCheque'])),
					floatval(tofloat($_POST['MtCredit'])),
					$_POST['NumChq'],
					$target_path,
					$_POST['Bq'],
					$_POST['NumTrans'],
					$_POST['CodeBuySel'],
					$_POST['Type']
			
			) ;
				$stmt3 = sqlsrv_query( $con, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout  reg ".$errors[0]['message'] . " <br/> ";
			
			}	
			//---------------------------ID fiche fac--------------------------------//
			$sql = "SELECT max(IdReg) as IdReg FROM reglements ";
			$stmt2 = sqlsrv_query( $con, $sql );
			if( $stmt2 === false ) {
				$error.="Erreur recupération  IdReg: ".sqlsrv_errors() . " <br/> ";
			}
			sqlsrv_fetch($stmt2) ;
			$IdReg = sqlsrv_get_field( $stmt2, 0);
			$test="";
	$ListeFac = explode(",",$_POST['ListeFac']);
	$i=0;
//	parcourir($_POST);return;
//array_shift($ListeFac);
		foreach($ListeFac as $a=>$ligne){
		
		if($ligne!=0){
				$data = explode(",",$ligne);
				$idp = $data[0];
					$reqInser2 = "INSERT INTO  detailReglements(IdReg,IdFac) 
						values (?,?)";
				$params2= array(
						$IdReg,
						$idp
				
				) ;
				$stmt3 = sqlsrv_query( $con, $reqInser2, $params2 );
				if( $stmt3 === false ) {

					$errors = sqlsrv_errors();
					$error.="Erreur : Ajout detail Reglements ".$errors[0]['message'] . " <br/> ";
					break ;
				}	
				$i+=1;
			  }
	}
		  
if($error=="" ) {
 sqlsrv_commit( $con );

	?>
	<script language="javascript" type="text/javascript">
		rechercher();
	//	alert('success');
		jAlert("<h3><?php  echo lang('messageAjoutSucces'); ?><h3>","<?php  echo lang('operation_th'); ?>");  
			$("#BoxB").modal('hide');
		var IdReg="<?php echo $IdReg;?>";
	//	alert(IdReg);
		window.open ('regelements_pdf.php?IdReg='+IdReg, "_newtab" )
		//document.location.href='facture_acht_pdf.php?IdReg='+IdReg;
	//	window.open ('reglement_acht_pdf.php?IdReg='+IdReg, "_newtab" );

	</script>
	<?php
	
} else {
     sqlsrv_rollback( $con );
     echo $error;
}




exit;
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
	<link rel="stylesheet" href="layout/css/jquery.keypad.css">
<link href="layout/css/jquery-filestyle.css"  rel="stylesheet" />

<style>
	.keypad-popup, .keypad-inline, .keypad-key, .keypad-special { font-size: 30px; }
	.keypad-special { width: 6.25em; margin: 0.125em; }
	.keypad-spacebar { width: 15.25em; margin: 0.145em; }
	.keypad-key, .keypad-tab, .keypad-space { width: 4em; margin: 0.145em; }
	.keypad-half-space { width: 3em; margin-left: 0.145em; }

div.jfilestyle label {
    display: inline-block;
    border: 1px solid #c0c0c0;
    background: #ffffff;
    padding: 10px 50px;
    color: #0662ba;
}
select.form-control {
    font-size: 1.2em;
}

</style>
<script language="javascript" type="text/javascript">
  


	
$(function() {

	   $('#DateChq').daterangepicker({
        singleDatePicker: true,
		 locale: {
            format: 'D/MM/YYYY'
        }
});
})
	</script>
	<?php
	
$TotalReg=$_GET["TotalReg"];
$IdAcht="";//$_SESSION["IdAcht"];

	

?>
<div id="resReg"></div>
<form id="formReg" action="" method="post" name="formReg" class="">
<div id="FormPaiement" STYLE="  Width:100%;">

<input type="hidden" value="<?php echo $_GET['Type'];?> " dir="ltr" name="Type"  class="form-control" />
<input type="hidden" value="<?php echo $_GET['CodeBuy'];?>" dir="ltr" name="CodeBuySel"  class="form-control" />
<input type="hidden" value="<?php echo $_GET['ListeFac'];?>" dir="ltr" name="ListeFac"  class="form-control" />
	
	<input type="text" id="TotalReg" value="<?php echo $TotalReg; ?>" name="TotalReg" class="chpinvisible" />
<div class=" row Fac form-group ">
		<div class=" col-md-6">
		<?php echo lang('search_buyer'); ?>
		<span id="Fac"><?php 
			echo  $_GET['CodeBuy'] . " ".$_GET['NameBuy'];?>
			</span>
		</div>
				<div class=" col-md-6">
		<?php echo lang('TotalReg'); ?>
			<span id="Fac">&#x200E;<?php // montant de la cmd actuelle seulement
			echo number_format($TotalReg, 3, '.', ' ')." (".lang('reyal_homany').')';?>
			</span>
			</div>
</div>

<div class="clair"></div>
<div  CLAss=" form-group row  " id="Espece" class="CadreT" style="background:#E3F6CE;">


	<div class=" col-md-3"  >
		<input type="button" value="<?php // echo $trad['label']['Espece'];?>" id="BtnEspece"  width="500">
	</div>
	<div class=" col-md-9"  >	<BR>
		<div class=" row ">
			<div class=" col-md-2"><label><?php  echo lang('Espece');?>  :</label></div>	
			<div class=" col-md-3"><input type="text" id="MtEspece" 
			style="" name="MtEspece"  dir="ltr" onblur="" value="<?php echo number_format($TotalReg, 3, '.', ' '); ?>" 
			class="ConvertDecimal nbr form-control" onkeypress="return isEntier(event) " size="15" name="Num"></div>
		</div>
	</div>
</div>

<DIV class="clear"></div>
<div id="Cheque" CLAss=" form-group row  " class="CadreT" style="background:#FBEFF5;">


	<div class=" col-md-3 " ><br><input type="button"  value="<?php // echo $trad['label']['Cheque'];?>" id="BtnCheque"></div>

	<div class="  col-md-9  ">
	<BR>
	<div class=" form-group row ">
		<div class=" col-md-2"><label><?php  echo lang('Cheque');?>  :</label></div>
		<div class=" col-md-4">
			<input type="text" value=" " dir="ltr" name="MtCheque"  class="form-control" onblur="" id="MtCheque" readonly size="15" name="Num">
		</div>
		<div class=" col-md-2">
			<label><?php  echo lang('date');?> :</label>
		</div>
		<div class=" col-md-4">
			<input type="text" value="" dir="ltr" name="DateChq"  id="DateChq"	class="form-control" />
		</div>
	</div>
	
	
	<div class="  form-group row">	
	<div class=" col-md-12 row">
	
		<div class=" col-md-2">
				<label><?php  echo lang('Bq');?> :</label>
		</div>
		<div class=" col-md-4">
			
			<select dir="ltr" name="Bq" id="Bq" class="form-control">
			<option value="Banque populaire" >بنك عُمان العربي</option>
			<option value="BOB">بنك برودا</option>
			<option value="BMI">بنك ميلي إيران</option>
			<option value="BSHR">بنك صحار</option>
			<option value="ALHL">البنك الأهلي</option>
			<option value="NBO">البنك الوطني العُماني</option>
			</select>
		</div>
		
		<div class=" col-md-2">
			<label><?php  echo lang('NumChq');?>:</label>
		</div>
		<div class=" col-md-4">
			<input type="text" value=" " dir="ltr" name="NumChq"  class="form-control" />
		</div>
		
		</div>
	
	
	</div>	
		<div class=" form-group row">	
			
			<div class=" col-md-12">
				<input  class="jfilestyle" data-input="false" type="file" name="file" control="1" id="file" size="10" tabindex="1" value="" />
			
			</div>
	
		</div>
	
	
	</div>
</div>  
<DIV class="clear"></div>

<div id="Credit" CLAss="  row  "  class="CadreT" style="background:#CEECF5;">
	<div class=" col-md-3" ><input type="button" value="<?php  //echo $trad['label']['Credit'];?>" id="BtnCredit"></div>
	<div class=" col-md-9">	<BR>
		<div class=" row ">
	
			<div class=" col-md-2"><label><?php  echo lang('Credit');?> :</label></div>
			<div class=" col-md-3">
			<input type="text"  dir="ltr" value=" " class="form-control" id="MtCredit" name="MtCredit" size="10" name="Num" readonly>
			</div>
		<div class=" col-md-2"><label><?php  echo lang('NumTran');?> :</label></div>
		<div class=" col-md-5"><input type="text" value=" " dir="ltr" name="NumTrans"  class="form-control" /></DIV>
		</div>
	</div>
</div>  



</div> <!-- fin div paiement>-->
</form>


<script src="layout/js/jquery.plugin.min.js"></script>
<script src="layout/js/jquery.keypad.js"></script>
<!--script type="text/javascript" src="layout/js/jquery.keypad-fr.js"></script-->
<script src="layout/js/jquery-filestyle.min.js" type="text/javascript"></script>



<script language="javascript" type="text/javascript">
  

	
$(function () {

	function ajaxindicatorstop()
	{
	    jQuery('#resultLoading .bg').height('100%');
        jQuery('#resultLoading').fadeOut(300);
	    jQuery('body').css('cursor', 'default');
	}
	ajaxindicatorstop();
	$("#BoxB").modal('show');

	// quand on met le curseur dans l'input on le vide
	 $("#MtEspece").unbind().click(function() {
	//alert($('#MtEspece').val());
			$('#MtCheque').val($('#MtEspece').val()); 
				//if($('#MtEspece').val()!="") $('#MtEspece').val('');
				$('#MtEspece').val('');
	});
 
 	 $("#MtCheque").unbind().click(function() {
				if(($('#TotalReg').val()!="") && ($('#TotalReg').val()!="0") ){
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
			$('#MtEspece').val(number_format($('#MtEspece').val(),3,"."," ")); 
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
		
        	var TotalReg=$("#TotalReg").val().replace(" ","");
			TotalReg=parseFloat( TotalReg.replace(/[^\d\.]/g,'') );
			//$("#MtEspece").val($("#TotalReg").val());
		//alert(TotalReg);
		var count = char_count($("#MtEspece").val(), '.');
		
	//alert(count);
			if(($("#MtEspece").val()!=" ") && ($("#MtEspece").val()!="")&& (count<=1)&& ($("#MtEspece").val()!=".")) var MtEspece=$("#MtEspece").val().replace(" ","");
			else var MtEspece=0;
	//	alert(MtEspece);
	if(MtEspece!="") MtEspece=parseFloat( MtEspece.replace(/[^\d\.]/g,'') );
			var Reste=parseFloat(TotalReg)-parseFloat(MtEspece);
			//alert(Reste);
			if(Reste>0) $("#MtCheque").val(number_format(Reste, 3, ".", " ") ); else $("#MtCheque").val("");
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
						$('#MtCheque').val(number_format($('#MtCheque').val(),3,"."," ")); 						
					}
			  });
	}

	function OnChangeCheque(){
		var MtCheque=0;var MtEspece=0;
		var count = char_count($("#MtEspece").val(), '.');
		var count2 = char_count($("#MtCheque").val(), '.');
				var TotalReg=$("#TotalReg").val().replace(" ","");
				TotalReg=parseFloat( TotalReg.replace(/[^\d\.]/g,'') );
				//$("#MtEspece").val($("#TotalReg").val());
				if(($("#MtCheque").val()!=" ") && ($("#MtCheque").val()!="") &&  (count2<=1)&& ($("#MtCheque").val()!="."))var MtCheque=$("#MtCheque").val().replace(" ","");
				else var MtCheque=0;
				if(MtEspece!="") MtEspece=parseFloat( MtEspece.replace(/[^\d\.]/g,'') );
				if(MtCheque!="") MtCheque=parseFloat( MtCheque.replace(/[^\d\.]/g,'') );
				if(($("#MtEspece").val()!=" ") && ($("#MtEspece").val()!="")&& (count<=1)&& ($("#MtEspece").val()!="."))var MtEspece=$("#MtEspece").val().replace(" ","");
				else var MtEspece=0;
				
				var Reste=parseFloat(TotalReg)-(parseFloat(MtCheque)+parseFloat(MtEspece));
		
				if(Reste>0) $("#MtCredit").val(number_format(Reste, 3, ".", " ") ); else $("#MtCredit").val("");
	}
	
});
	$("#BtnEspece").click(function() {
		var TotalReg=$("#TotalReg").val().replace(" ","");
		TotalReg=parseFloat( TotalReg.replace(/[^\d\.]/g,'') );
		$("#MtEspece").val(number_format(TotalReg, 3, ".", " ") );
		$("#MtCredit").val("");
		$("#MtCheque").val("");
	});
	
	$("#BtnCheque").click(function() {
		var TotalReg=$("#TotalReg").val().replace(" ","");
			TotalReg=parseFloat( TotalReg.replace(/[^\d\.]/g,'') );
		$("#MtCheque").val(number_format(TotalReg, 3, ".", " ") );
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
	
	$("#BtnCredit").click(function() {
		var TotalReg=$("#TotalReg").val().replace(" ","");
		TotalReg=parseFloat( TotalReg.replace(/[^\d\.]/g,'') );
		$("#MtCredit").val(number_format(TotalReg, 3, ".", " ") );
		$("#MtEspece").val("");
		$("#MtCheque").val("");
	});
	
function TerminerCmd(){
		var test=true;	
/*	var files = $('#formReg:input[type=file]').get(0).files;

		if(files.length==0){
			jAlert("<?php echo $trad['msg']['FichierObligatoire'];?>","<?php echo $trad['titre']['Alert'];?>");
			test=false;
		  return false;			
		}*/
		//alert(test);
		if(test==true){									
		$('#formReg').ajaxSubmit({
							target:'#resReg',
							url:'paiement.php?TerminerReg',
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
function Imprimer(IdRegture){
			 options = "Width=1280,Height=800" ;
		  window.open( 'facture.print.php?IdRegture='+IdRegture, "edition", options ) ;
		
	}
$(document).ready(function() {
	$(":file").jfilestyle({input: false,buttonText: "<img src='layout/images/folder.png' /><?php echo  lang('parcourir')  ; ?>"});
});

	$("#CheckboxC").click(function() {	
		var TotalReg=$("#TotalReg").val().replace(" ","");
		if($("#CheckboxC").is(':checked')){		
	
			var som=parseFloat(TotalReg)+parseFloat($("#EncienCredit").val());
			$("#TotalReg").val(number_format(som, 2, ".", " "));
			$("#MtEspece").val($("#TotalReg").val());
				$("#MtCredit").val("");
				$("#MtCheque").val("");
		} else {
				var som=parseFloat(TotalReg)-$("#EncienCredit").val();
				$("#TotalReg").val(number_format(som, 2, ".", " "));
				$("#MtEspece").val($("#TotalReg").val());
				$("#MtCredit").val("");
				$("#MtCheque").val("");		
		}
	});
	</script>
<?php 
exit;
} 
	
	?>
	

	
	
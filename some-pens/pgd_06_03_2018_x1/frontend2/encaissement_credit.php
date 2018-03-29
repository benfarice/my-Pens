<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();
include("lang.php");

$Operateur=1;
$IdDepot=$_SESSION['IdDepot'];

if (isset($_GET['paiement'])){
	$Imprime="";
	$IdClient=$_POST["IdClient"];
		//	parcourir($_POST);return;
			 $error="";
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
		$target_path="";
		$TotalAvance=0;	
		//----------------------Insertion Avance --------------------------//
		if(tofloat($_POST['MtEspece'])!=0){
		$ModePaiement="Espece";
		//ECHO $_POST['MtEspece'];return;
		$MtAvance=tofloat($_POST['MtEspece']);
		$TotalAvance=$MtAvance;
			$reqInser3 = "INSERT INTO Avance ([IdClient]  ,[IdVendeur] ,[DateAvance],Avance,idDepot,ModePaiement,ImgCheque) 
							values 	(?,?,?,?,?,?,?)";
					//	echo $reqInser1;
				
			$params3= array(				
							$IdClient,
							$_SESSION["IdVendeur"],				
							date("Y-m-d"),
							$MtAvance,
							$IdDepot,
							$ModePaiement,
							$target_path
							
			) ;
		$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3 );
		if( $stmt4== false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : Ajout Avance ".$errors[0]['message'] . " <br/> ";
		}

			$Imprime.= "Espèce : ".str_pad(number_format($MtAvance, 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
			
		}
		else {
			$Imprime.= "Espèce : ".str_pad(number_format("0", 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
		}

		if(tofloat($_POST['MtCheque'])!=0){
			$ModePaiement="Cheque";
			$MtAvance=tofloat($_POST['MtCheque']);
			$TotalAvance+=$MtAvance;
			if(isset($_FILES['file']))
			{
				$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
				$file_extension = end($ext); // Store extensions in the variable.
				$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
				if (!file_exists("imgPaiement/")) {
					mkdir("imgPaiement/", 0777, true);
				}
				$target_path = "imgPaiement/" . $nameFile;     // Set the target path with a new name of image.
				
					$error="";
					
					if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
						{
						?>
									<script type="text/javascript"> 
										alert("<?php echo $trad['msg']['echecDeplacementImage'] ; ?>");
									</script>
						<?php
						return;
						}
			}
			else
			{
				$target_path = "";     // Set the target path with a new name of image.
			}
			//echo $target_path;return;
			
			$reqInser3 = "INSERT INTO Avance ([IdClient]  ,[IdVendeur] ,[DateAvance],Avance,idDepot,ModePaiement,ImgCheque) 
							values 	(?,?,?,?,?,?,?)";
					//	echo $reqInser1;
				
			$params3= array(				
							$IdClient,
							$_SESSION["IdVendeur"],				
							date("Y-m-d"),
							$MtAvance,
							$IdDepot,
							$ModePaiement,
							$target_path
							
			) ;
		//	print_r($params3);return;
		$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3 );
		if( $stmt4== false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : Ajout Avance ".$errors[0]['message'] . " <br/> ";
		}
		$Imprime.= "Chèque : ".str_pad(number_format($MtAvance, 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;

		}
		else {
			$Imprime.= "Chèque : ".str_pad(number_format("0", 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
		}
		if(isset($_POST['CheckboxC'])){
			$MtCredit=tofloat($_POST['MtCredit'])	;
		}else 
		{
			if(isset($_POST['EncienCredit'])){
			$MtCredit=tofloat($_POST['MtCredit'])+tofloat($_POST['EncienCredit'])	;
			}else {
				$MtCredit=tofloat($_POST['MtCredit'])	;
			}
		}
		if($_POST['MtCredit']!=""){
			$Imprime.="Crédit : ".str_pad(number_format($MtCredit, 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
			
			$Imprime.=PHP_EOL;
		}

		// on desactive le dernier credit
				$requpdate = "update Avance set [etat]= 0 where IdClient=? and IdDepot=? and ModePaiement=?";
				$param= array($IdClient,$IdDepot,'Credit') ;
				$stmt1 = sqlsrv_query( $conn, $requpdate, $param );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
					
				}
			// on ajoute le nouveau credit
					$reqInser3 = "INSERT INTO Avance ([IdClient]  ,[IdVendeur] ,[DateAvance],Avance,idDepot,ModePaiement,ImgCheque,Etat) 
							values 	(?,?,?,?,?,?,?,?)";
					//	echo $reqInser1;
				
				$params3= array(				
								$IdClient,
								$_SESSION["IdVendeur"],				
								date("Y-m-d"),
								$MtCredit,
								$IdDepot,
								"Credit",
								$target_path,
								1
								
				) ;
				$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3 );
				if( $stmt4== false ) {
					$errors = sqlsrv_errors();
					$error.="Erreur : Ajout Credit ".$errors[0]['message'] . " <br/> ";
				}
				if($error=="" ) {
					sqlsrv_commit( $conn );
					?>
					<script type="text/javascript"> 
					alert('<?php echo $trad['msg']['messageAjoutSucces'];?>');
					dialog.dialog('close');
					$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('encaissement_credit.php?aff');
				</script>
					<?php
				}
			exit;	
}
if (isset($_GET['aff'])){
?>
<style>
.divArticleL{width:400px;}
.livre{
background: #fcfda2;
	overflow: auto; display: flex;border-top:0;
}
.precommande
{
background: #e5e5e5; 
overflow: auto; display: flex;border-top:0;
}

</style>
<script>
function getPayement(IdClient,Credit){
	var url='paiement.php?ChoixTypeReg&&Encaissement=encaissement&&TotalFac='+Credit+'&&IdClient='+IdClient;
	dialog.dialog('open');
	$("#Paiement").html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
	
		
	
}
</script>
<?php
/*********** selectionner le ca et le credit client**********************/
$sql = "
		SELECT c.IdClient,nom+ ' ' +c.prenom as nom,c.intitule,c.adresse,c.ImgMagasin,
		(SELECT ISNULL(sum(f.totalTTC),0) 
		FROM factures f WHERE f.idClient=c.idClient  and EtatCmd=2  GROUP by idClient) AS ca ,
		(SELECT sum(a.Avance ) FROM Avance a WHERE a.idClient=c.idClient AND ModePaiement='Credit' AND Etat=1 ) as Credit
		FROM clients c 
		WHERE c.idVendeur=? AND c.IdClient IN (SELECT   f.IdClient FROM factures f)
		and c.IdClient IN (SELECT   a.IdClient FROM avance a  where ModePaiement='Credit' AND Etat=1 and Avance!=0 )
		
";
	
	 $params = array($_SESSION['IdVendeur']);
//echo $sql. ' ' .$_SESSION['IdDepot'];
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	 $resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
	$nRes = sqlsrv_num_rows($resAff);//echo "resultat " . $nRes;
//echo $nRes;
if($nRes==0)
{ ?>
	<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
		<br><br><br><br>
		<?php echo $trad['msg']['AucunResultat'];?>
</div>
<?php
return;
}
else
{	
?>
<!--div class="title"><?php echo $trad['label']['EncaissementCredit'];?></div-->
<DIV class="ListeCmd">
	<div class="enteteL" >
			<div  class="divArticleL"  style="width:300px ;text-align:center;"><?php echo $trad['label']['Client'];?> </div>
			<div  class="divArticleL" style="width:292px;text-align:center;"><?php echo $trad['map']['ca']."(".$trad['label']['riyal'].")" ;?>  </div>	
			<div  class="divArticleL" style="width:310px;text-align:center;"><?php echo $trad['label']['Credit']." (".$trad['label']['riyal'].")";?>  </div>	
			
	</div>
	<div style="height:350px;overflow:scroll;" ><!--height:585px;-->
<?php
$k=0;
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$k++;
if(($row['Credit'] == 0) ||($row['Credit'] == null) )  {
	$c = "backGreen"; ?><div 	class=" <?php echo $c;?>">
<?php } else { $c="backRed"; 
?><div 	class=" <?php echo $c;?>"	onclick="getPayement('<?php  echo $row['IdClient'];?>','<?php  echo $row['Credit'];?>')">
<?php }?>
			
			<div  class="divArticleL" style="width:297px; "  ><?php echo $row['intitule'];?> </div>
			<div  class="divArticleL" style="width:290px;text-align:right;"><?php echo number_format($row['ca'], 2, '.', ' '); ?> </div>	
			<div  class="divArticleL" style="width:290px;text-align:right;"> <?php echo number_format($row['Credit'], 2, '.', ' '); ?> </div>	
</div>
<DIV Class="clear"></div>

<?php }  ?>
	</div>
</div>
<?php 

exit;
}

}
//}
?>
<?php include("header.php"); ?>


<div style=" display:flex;align-items:center; padding:2px 0;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
						<div >&nbsp;> <span  Class="TitleHead" onclick=""><?php echo $trad['label']['EncaissementCredit'];?></span></div> 
		
	
</div>

<div style="clear:both;"></div>

<div id="formRes"></div><!--style="overflow-y:scroll;min-height:280px;"--> 

<?php
include("footer.php");
?>
<div id="boxArticle">
	<div id="Paiement" style="margin-left:10px;width"></div>
	<DIV style="width:100%;text-align:center">
	<input type="button" value="<?php  echo $trad['button']['Valider'];?>" class="btn"  onclick="ValiderPaiement()"/>
	<input type="button" value="<?php  echo $trad['button']['Annuler'];?>" class="btn"  onclick="Fermer()"/>
	</div>
</div>
<script language="javascript" type="text/javascript">
function Fermer(){
//	$("#boxArticle").dialog('close');
	dialog.dialog('close');
}

			var dialog=	$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	950,/*1260,*/
					height			:	475,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						},
						"Terminer "	: function() {
							terminer();
						
						}
					 }
			});
$(document).ready(function() {
		//$.validator.messages.required = '';
  		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('encaissement_credit.php?aff');
		
});
function rechercher(){
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'encaissement_credit.php?aff'})
		clearForm('formRechF',0);
	}


$('.numberOnly').on('keydown', function(e){//alert("here");
	
  if(this.selectionStart || this.selectionStart == 0){
	// selectionStart won't work in IE < 9
	
	var key = e.which;
	var prevDefault = true;
	
	var thouSep = " ";  // your seperator for thousands
	var deciSep = ".";  // your seperator for decimals
	var deciNumber = 2; // how many numbers after the comma
	
	var thouReg = new RegExp(thouSep,"g");
	var deciReg = new RegExp(deciSep,"g");
	
	function spaceCaretPos(val, cPos){
		/// get the right caret position without the spaces
		
		if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
		cPos = cPos-1;
		
		if(val.substring(0,cPos).indexOf(thouSep) >= 0){
		cPos = cPos - val.substring(0,cPos).match(thouReg).length;
		}
		
		return cPos;
	}
	
	function spaceFormat(val, pos){
		/// add spaces for thousands
		
		if(val.indexOf(deciSep) >= 0){
			var comPos = val.indexOf(deciSep);
			var int = val.substring(0,comPos);
			var dec = val.substring(comPos);
		} else{
			var int = val;
			var dec = "";
		}
		var ret = [val, pos];
		
		if(int.length > 3){
			
			var newInt = "";
			var spaceIndex = int.length;
			
			while(spaceIndex > 3){
				spaceIndex = spaceIndex - 3;
				newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
				if(pos > spaceIndex) pos++;
			}
			ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
		}
		return ret;
	}
	
	$(this).on('keyup', function(ev){
		
		if(ev.which == 8){
			// reformat the thousands after backspace keyup
			
			var value = this.value;
			var caretPos = this.selectionStart;
			
			caretPos = spaceCaretPos(value, caretPos);
			value = value.replace(thouReg, '');
			
			var newValues = spaceFormat(value, caretPos);
			this.value = newValues[0];
			this.selectionStart = newValues[1];
			this.selectionEnd   = newValues[1];
		}
	});
	
	if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
	   (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
		prevDefault = false;
	
	if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
		e.preventDefault();
		
		if(!e.altKey && !e.shiftKey && !e.ctrlKey){
		
			var value = this.value;
			if((key > 95 && key < 106)||(key > 47 && key < 58) ||
			  (deciNumber > 0 && (key == 110 || key == 188 || key == 190))){
				
				var keys = { // reformat the keyCode
				48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
				96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
				110: deciSep, 188: deciSep, 190: deciSep
				};
				
				var caretPos = this.selectionStart;
				var caretEnd = this.selectionEnd;
				
				if(caretPos != caretEnd) // remove selected text
				value = value.substring(0,caretPos) + value.substring(caretEnd);
				
				caretPos = spaceCaretPos(value, caretPos);
				
				value = value.replace(thouReg, '');
				
				var before = value.substring(0,caretPos);
				var after = value.substring(caretPos);
				var newPos = caretPos+1;
				
				if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
					if(before.indexOf(deciSep) >= 0){ newPos--; }
					before = before.replace(deciReg, '');
					after = after.replace(deciReg, '');
				}
				var newValue = before + keys[key] + after;
				
				if(newValue.substring(0,1) == deciSep){
					newValue = "0"+newValue;
					newPos++;
				}
				
				while(newValue.length > 1 && 
				  newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
					newValue = newValue.substring(1);
					newPos--;
				}
				
				if(newValue.indexOf(deciSep) >= 0){
					var newLength = newValue.indexOf(deciSep)+deciNumber+1;
					if(newValue.length > newLength){
					newValue = newValue.substring(0,newLength);
					}
				}
				
				newValues = spaceFormat(newValue, newPos);
				
				this.value = newValues[0];
				this.selectionStart = newValues[1];
				this.selectionEnd   = newValues[1];
			}
		}
	}
	
	
  }
});
function ValiderPaiement(){
	/******Control Qte Charge********/
	index=0;
	test=true;
//	alert(test);
			if(test==true) {		
			 jConfirm('<?php echo $trad['msg']['terminerOperation'];?>', "<?php echo $trad['titre']['Alert'];?>", function(r) {
					if(r)	{
							$('#formReg').ajaxSubmit({
									target			:	'#resReg',
									url				:	'encaissement_credit.php?paiement',//&idVnd='+IdVnd+'&idCmd='+IdCmd,
									method			:	'post'
							}); 
							return false;
			           }
			
		})
	}
}
</script>

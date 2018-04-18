<?php
include("../php.fonctions.php");
require_once('../connexion.php');
include("fonctionCalcule.php");
session_start();
include("lang.php");

$Operateur=1;
$IdDepot=$_COOKIE['IdDepot'];

?>
<?php

if (isset($_GET['goupdate'])){
//print_r($_POST);return;
$DateSortie=date("Y-m-d H:i:s");
$DateSortie = date_create(date("Y-m-d"));
$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//-----------------Add Fiche chargement----------------//

//********* creation numFiche **************/;
$RefFicheCh= "NC".Increment_Chaine_F("numChargement","chargements","IdChargement",$conn,"",array());
//echo $RefFicheCh;return;

$reqInser1 = "INSERT INTO chargements ([numChargement] ,[operateur]  ,[idVendeur]  ,[date],[idDepot],etat) 
				values 	(?,?,?,?,?,?)";
		//	echo $reqInser1;
$params1= array(
				$RefFicheCh,
				$Operateur,
				$_GET['idVnd'],
				$DateSortie,$IdDepot,0
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout fiche chargement ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IDFiche--------------------------------//
$sql = "SELECT max(IdChargement) as IdFiche FROM chargements";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdFiche chargement: ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFiche = sqlsrv_get_field( $stmt2, 0);
//----------------------Add Detail fiche --------------------------//
//print_r($_COOKIE['lignes']);
 for( $i= 0 ; $i < count($_POST['idDetail']) ; $i++ )
{

	$reqInser2 = "INSERT INTO  detailchargements([idArticle],[qte],[idColisage],IdChargement,ecart,reste,etat ) values (?,?,?,?,?,?,?)";
			$params2= array($_POST['idArticle'][$i],$_POST['qtech'][$i],1,$IdFiche,0,0,0) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout de sortiee ".$errors[0]['message'] . " <br/> ";
				//break ;
			}			
}

//----------------------modifier etat de la commande du 1 à 0 --------------------------//
 $reqInserUp = "update commandeVendeurs set etat=1 where idCommandeVendeur = ? ";
			$paramsUp= array($_GET['idCmd']) ;
			$stmtUp = sqlsrv_query( $conn, $reqInserUp, $paramsUp );
			if( $stmtUp === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : modif etat cmd ".$errors[0]['message'] . " <br/> ";
				//break ;
			}	

			
if( ($error=="" ) && ($RefFicheCh!="Nserror")) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
		//	alert('L\'ajout a été effectué.');
			alert('<?php echo $trad['msg']['messageAjoutSucces'];?>');
			$('#boxArticle').dialog('close');

			rechercher();
			
		</script>
		
<?php

} 
else 
{
     sqlsrv_rollback( $conn );
     echo $error;
}
exit;
}


if (isset($_GET['getCommande'])){
if (isset($_GET['getArticle'])){
?>
<script language="javascript">
$(document).ready(function(){
 //  $('.scrollbar-inner').scrollbar();
var $rows = $('.test');
$('#search').keyup(function () {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function () {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});

});
function Fermer(){
	$("#boxSearchArticle").dialog('close');
}
</script>
<?php
$error="";
$sqlAr = "
						select a.idarticle IdArticle,c.colisagee,a.designation DsgArticle,a.Reference RefArticle,
						fa.idFamille IdFamille,fa.Designation,
						fa.codeFamille RefFam,sf.codeSousFamille RefSousFam,
						g.Reference RefGamme,
						sf.idSousFamille IdSousFam,
						sf.Designation dsgSousFamille,g.IdGamme,g.Designation dsgGamme
						from articles a 
						INNER JOIN colisages c ON a.IdArticle=c.idArticle
						INNER JOIN gammes g ON g.IdGamme=a.IdFamille
						INNER JOIN marques m ON m.IdMarque=g.IdMarque
						INNER JOIN sousfamilles sf on sf.idSousFamille=g.IdSousFamille
						INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
						where (m.IdMarque=17 or m.IdMarque=18 ) 	AND a.idDepot=?		
						order by DsgArticle ASC	 ";
						$paramsAr = array($IdDepot);
						$stmtA=sqlsrv_query($conn,$sqlAr,$paramsAr,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
						if( $stmtA === false ) {
							$errors = sqlsrv_errors();
							//$error= $errors[0]['message'];
							$error= $errors[0]['message'];
							?>
							<script language="javascript" type="text/javascript">					
							
							jAlert("<?php echo $error;?>","<?php echo $trad['titre']['Alert'];?>");
							</script>
							<?php 
						}
					else {
					
					$i=0;
					?>
			<div class="filtre">
			
			<input type="text" id="search" placeholder="<?php echo $trad['button']['Rechercher'];?>..." >
			</div>
			
			</div>
			<div class="clear"></div>
						<DIV class="entete">
						<div class="divArticle divArticleWidth" align="<?php $_COOKIE['align'];?>">
						<?php echo $trad['label']['Article'];?> </div>
					    <div class="divPV"  Style="width:190px;" align="center"><?php echo $trad['label']['Colisage']; ?> </div>
						<div class="divPV"  Style="width:210px;" align="center"><?php echo $trad['label']['QteStock'];
						//.' ('.$trad['label']['Espece'].')'?> </div>

					    </div>
						<div class="clear"></div>						
				       <div class="DivListArt scrollbar-inner"  style=" max-height:350px" >	
					<?php
								
					while($r=sqlsrv_fetch_array($stmtA)){	
					//----------------------------TO GET Qte STOCK-----------------------------------------------------									
//---------------------------select qteEntreeGlobal--------------------------------//
				$sql = "SELECT isnull(sum(dmo.qte),0),a.Unite FROM mouvements mo 
				INNER JOIN detailMouvements dmo ON dmo.idMouvement = mo.idMouvement 
				inner join articles a on a.idArticle=dmo.idArticle
				WHERE mo.type LIKE 'Entree'  and  mo.idDepot=? and dmo.IdArticle=? 	 group by dmo.idArticle,Unite";
				 $params1= array($IdDepot,$r['IdArticle']) ;

				$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteEntreeGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				sqlsrv_fetch($stmt1) ;
				$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
//---------------------------select qteChargementGlobal--------------------------------//
				$sql2 = "SELECT isnull(sum(CASE WHEN dc.reste =0 THEN dc.qte ELSE dc.reste END ),0) as qte ,a.Unite
						FROM chargements ch 
						INNER JOIN detailChargements dc on ch.IdChargement=dc.IdChargement
						INNER JOIN articles a on a.idArticle=dc.idArticle
						WHERE ch.idDepot=? and dc.IdArticle=?   group by dc.idArticle,Unite";
				$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	
				if( $stmt2 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteChargementGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				$rowC = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
				$qteChargementGlobal = $rowC['qte'];
//-----------------------------Quantité disponible par article et depot----------------------------------
			$qteDispo=$qteEntreeGlobal-$qteChargementGlobal;	
			$qteDispo=number_format($qteDispo,0," "," ");		
            if(	$qteDispo > 0){		
								?>
								<div class="ligne test"  id="LigneStock">
								<!--onclick="getArticle('<?php  echo $r['IdArticle'];?>','ActualiserAccueil')" -->
									<div style="width:470px;" class=" divArticle width256" 
onclick="addArticle(<?php echo $r['IdArticle']; ?>,'<?php echo $r['DsgArticle']; ?>','<?php echo $qteDispo; ?>' )"
										 align="center" > <?php /* echo ucfirst(stripslashes($r['DsgGamme']))." ".*/echo ucfirst(stripslashes(
										  wordwrap($r['DsgArticle'], 30, "\n")
										));?>					
									</div>
									<div class="divPV" style="width:190px;direction:ltr;" > 
									
									<?php  
									echo $r['colisagee'] ; 
								//	echo number_format($valeur,2);
									?>
									</div> 
									<div class="divPV" style="width:210px;direction:ltr;" >
									<?php  
									

									
									echo $qteDispo;
										//echo number_format($r['Stock'], 0); 							
									?> </div> 
									<div style="display:none" >
									<?php  echo ucfirst(stripslashes($r['Designation']))." ";?>
									<?php  echo ucfirst(stripslashes($r['dsgSousFamille']))." ";?>
									<?php  echo ucfirst(stripslashes($r['dsgGamme']))." ";?>
									<?php  echo ucfirst(stripslashes($r['RefFam']))." ";?>
									<?php  echo ucfirst(stripslashes($r['RefSousFam']))." ";?>
									<?php  echo ucfirst(stripslashes($r['RefGamme']))." ";?>
										<?php  echo ucfirst(stripslashes($r['RefArticle']))." ";?>
									</div>
								</div>
								<div class="clear"></div>
								
								<?php }
								}
					
					
					
					} ?>
					</div>
<div class="btnV">
	<input type="button" value="<?php echo $trad['button']['Enregistrer'];?>" class="btn" onclick="Fermer()"/>
</div>						
<?php						

exit;
}

//echo $_GET['numCmd'];
?>
<style>
.divArticleL{
width:300px;

}
.ligne{
TEXT-align: center;
overflow: auto;
display: flex;

}
.divArticleLigne{
    width: 300px;
    padding: 12px 10px;
    border-right: 1px solid #ccc;
}
.numberOnly{
padding: 2px 2px;
width:200px;
text-align:right;
}
.btnV{

text-align:right;
}
</style>

<script language="javascript" type="text/javascript">

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
function Valider(IdVnd,IdCmd){
	/******Control Qte Charge********/
	index=0;
	var test=true;
	
	//QteAchargé obligatoire
	$("[name^=qtech]").each(function () {
		if($(this).val() == "")
		{
		  $(this).css('border', '1px solid red');
		  test=false;
		 
		}
		else
		{
		  $(this).css('border', '1px solid black');
		}
		});
		
		if (test == false)
		return;
		
		//QteAchargé ne doit pas dépasser Qte Disponible
		$("[name^=qtech]").each(function () {
        // alert($(this).val());--------------------------------------Retour
		//alert($("input[val=stock"+index+"]").val());---------------QteChargee
	
		if(parseInt($(this).val()) > parseInt($("input[val=qteDispo"+index+"]").val())){
		//   alert("here" + $(this).val() + " > "+ $("input[val=stock"+index+"]").val());
			//jAlert("le retour ne doit pas dépasser la quantité chargée.","Message");
			jAlert("<?php echo $trad['msg']['QteDepasse'];?>","<?php echo $trad['titre']['Alert'];?>");
		    $(this).css('color', 'red'); //$(this).focus();
			test=false;
		   }
		   else
		   {
		    $(this).css('color', 'black');  
		   }
		index++;
    });	
	//alert(test);
			if(test==true) {		
			 jConfirm('<?php echo $trad['msg']['terminerOperation'];?>', "<?php echo $trad['titre']['Alert'];?>", function(r) {
					if(r)	{
							$('#formAdd').ajaxSubmit({
									target			:	'#result',
									url				:	'stockDepot_t.php?goupdate&idVnd='+IdVnd+'&idCmd='+IdCmd,
									method			:	'post'
							}); 
							return false;
			           }
			
		})
	}
}

function addArticle(IdArticle,DsgArticle,qteDispo)
{
//alert(IdArticle);alert(DsgArticle);alert(qteDispo);
$("#listArticle").prepend(
    "<input type='hidden' value='' name='idDetail[]'><input type='hidden'  value='"+IdArticle+"' name='idArticle[]'><input type='hidden'  val='qteDispo' value='"+qteDispo+"' name='qteDispo[]'><div class='pair'><div align='left' class='divArticleLigne'  >"+DsgArticle+"</div><div  align='right' class='divArticleLigne' >0  </div><div  align='right' class='divArticleLigne' >"+qteDispo+"</div>	<div  align='right' class='divArticleLigne' ><input class='numberOnly' type='text' value='0'  size='5' name='qtech[]' onkeypress='return isEntier(event) ' />		</div>	</div>");
}
function getarticle()
{
	var url='stockDepot_t.php?getCommande&getArticle';	
		$('#boxSearchArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');

}
function closepopup(){
	$("#boxArticle").dialog('close');
}
function test()
{
alert("here article");
}
</script>
<input type="button" value=""  class="close2" onclick="closepopup()" Style="float:right;"/>
<div class="clear"></div>
<div id="result"></div>
<?php 
/*********** selectionner la cmd d'un vendeur**********************/
$sql = "
			SELECT 	 c.idVendeur,idDetailCommandeVendeur,c.idCommandeVendeur,d.idArticle as IdArticle,a.designation as NomArt,d.qte as Qte
			from detailcommandeVendeurs d 
			inner join commandeVendeurs c on c.idCommandeVendeur=d.idCommandeVendeur
			inner join articles a on a.idArticle=d.idArticle WHERE c.idCommandeVendeur=? ";
	
	 $params = array($_GET['numCmd']);//
//echo $sql. ' ' .$_GET['numCmd'];
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
<a href="#" onClick="getarticle()" >Ajouter Article</a>

				
<div class="title"><?php echo $trad['label']['listeArticle'];?></div>
<DIV class="ListeCmd">
	<div class="enteteL" >
			<div  class="divArticleL" ><?php echo $trad['label']['Article'];?> </div>
			<div  class="divArticleL" ><?php echo $trad['label']['QteCmd'];?>  </div>	
			<div  class="divArticleL" ><?php echo $trad['label']['QteDisponible'];?>  </div>	
			<div  class="divArticleL" ><?php echo $trad['label']['qtecharge'];?>  </div>				
	</div>	
	<form id="formAdd" method="post" name="formAdd"> 
	
	<div style="height:300px;overflow:scroll;" id="listArticle" ><!--height:585px;-->


<?php 
$k=0;$i=0;
$idVnd="";$idCmd="";
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$idVnd=$row['idVendeur'];
$idCmd=$row['idCommandeVendeur'];
$k++;
if($k%2 == 0) $c = "pair";
else $c="impair";


//---------------------------select qteEntreeGlobal--------------------------------//
				$sql = "SELECT isnull(sum(dmo.qte),0),a.Unite FROM mouvements mo 
				INNER JOIN detailMouvements dmo ON dmo.idMouvement = mo.idMouvement 
				inner join articles a on a.idArticle=dmo.idArticle
				WHERE mo.type LIKE 'Entree'  and  mo.idDepot=? and dmo.IdArticle=? 	 group by dmo.idArticle,Unite";
				 $params1= array($_COOKIE['IdDepot'],$row['IdArticle']) ;

				$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteEntreeGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				sqlsrv_fetch($stmt1) ;
				$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
//---------------------------select qteChargementGlobal--------------------------------//
				$sql2 = "SELECT isnull(sum(CASE WHEN dc.reste =0 THEN dc.qte ELSE dc.reste END ),0) as qte ,a.Unite
						FROM chargements ch 
						INNER JOIN detailChargements dc on ch.IdChargement=dc.IdChargement
						INNER JOIN articles a on a.idArticle=dc.idArticle
						WHERE ch.idDepot=? and dc.IdArticle=?   group by dc.idArticle,Unite";
				$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	
				if( $stmt2 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : qteChargementGlobal ".$errors[0]['message'] . " <br/> ";
					return;
				}
				$rowC = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
				$qteChargementGlobal = $rowC['qte'];
//-----------------------------Quantité disponible par article et depot----------------------------------
			$qteDispo=$qteEntreeGlobal-$qteChargementGlobal;	
			$qteDispo=number_format($qteDispo,0," "," ");
?>

<input type="hidden" value="<?php  echo $row['idDetailCommandeVendeur']; ?>" name="idDetail[]">
<input type="hidden" value="<?php  echo $row['IdArticle']; ?>" name="idArticle[]">
<input type="hidden" val="qteDispo<?php echo $i ; ?>" value="<?php  echo $qteDispo; ?>" name="qteDispo[]">
		<div  class="pair" >
			<div align="left" class="divArticleLigne"  ><?php echo $row['NomArt'];?> </div>
			<div  align="right" class="divArticleLigne" ><?php echo $row['Qte'];?>  </div>	
			<div  align="right" class="divArticleLigne" ><?php echo ($qteDispo);?>  </div>	
			<div  align="right" class="divArticleLigne" >	
				<input class="numberOnly" type="text" value="<?php echo $row['Qte'];?>" size="5" name="qtech[]" onkeypress="return isEntier(event) " />
			</div>	
		</div>


<?php 
	$i++;
 }  ?>	
 </div>
</form>

</div>
<div class="btnV">
	<input type="button" value="<?php echo $trad['button']['Enregistrer'];?>" class="btn" onclick="Valider(<?php echo $idVnd;?>,<?php echo $idCmd; ?>)"/>
</div>
<?php 


exit;
}
}  ?>
<?php
if (isset($_GET['aff'])){
	echo "sss";
?>
<style>
.divArticleL{width:200px;}
.divPv{
background:white;

}

</style>
<script>
$(document).ready(function(){
 //  $('.scrollbar-inner').scrollbar();
var $rows = $('.test');
$('#search').keyup(function () {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function () {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});

});

</script>
<?php
/*********** selectionner la cmd d'un vendeur**********************/
unset($_COOKIE['Stock']);
if(!isset($_COOKIE['Stock'])){
$sqlAr = "
						select a.idarticle IdArticle,c.colisagee,a.designation DsgArticle,a.Reference RefArticle,
						fa.idFamille IdFamille,fa.Designation,
						fa.codeFamille RefFam,sf.codeSousFamille RefSousFam,
						g.Reference RefGamme,
						sf.idSousFamille IdSousFam,
						sf.Designation dsgSousFamille,g.IdGamme,g.Designation dsgGamme
						from articles a 
						INNER JOIN colisages c ON a.IdArticle=c.idArticle
						INNER JOIN gammes g ON g.IdGamme=a.IdFamille
						INNER JOIN marques m ON m.IdMarque=g.IdMarque
						INNER JOIN sousfamilles sf on sf.idSousFamille=g.IdSousFamille
						INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
						INNER JOIN detailMouvements dmo ON dmo.idArticle = a.idArticle 
						inner join mouvements mo on dmo.idMouvement = mo.idMouvement 
						where (m.IdMarque=17 or m.IdMarque=18 or  m.idMarque=1017 ) and mo.idDepot=".$IdDepot."
							AND fa.idFamille=2025	
						group by 
							a.idarticle ,c.colisagee,a.designation ,a.Reference ,
						fa.idFamille ,fa.Designation,
						fa.codeFamille ,sf.codeSousFamille ,
						g.Reference ,
						sf.idSousFamille ,
						sf.Designation ,g.IdGamme,g.Designation 		
						order by DsgArticle ASC	
						";
						$paramsAr = array($IdDepot);
						$stmtA=sqlsrv_query($conn,$sqlAr,$paramsAr,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
						$nRes = sqlsrv_num_rows($stmtA);//echo "resultat " . $nRes;
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
					else {
					$i=0;
							while($row = sqlsrv_fetch_array($stmtA, SQLSRV_FETCH_ASSOC)){			
								$params= array($row['IdArticle'],$IdDepot) ;
								$qteDispo=  qteDispoArticle($params,$conn,'reel');	
							echo $qteDispo;
								$qteDispoAvecReserve=  qteDispoArticle($params,$conn,'reserve');	
								$key= $row['IdGamme'];
													
													$i=$i+1;
													
													if (!isset($gamme[$key])) {
														$gamme[$key] = array();
														$gamme[$key]['IdGamme']=$row['IdGamme'];
														$gamme[$key]['RefGamme']=$row['RefGamme'];									
														$gamme[$key]['dsgGamme']=$row['dsgGamme'];									
														$i=0;
													} 
													
															if($gamme[$key]!=""){
																	$gamme[$key][$i]['RefArticle']= $row['RefArticle'];										
																	$gamme[$key][$i]['DsgArticle']= $row['DsgArticle'];
																	$gamme[$key][$i]['colisage']= $row['colisagee'];												
																	$gamme[$key][$i]['qteStock']= $qteDispo;
																	$gamme[$key][$i]['qteDispoAvecReserve']= $qteDispoAvecReserve;
																	
																	// convert qte dispo en boite 
																	$qteDispoEnBoite=$qteDispo/ $row['colisagee'];
																	$qteDispoEnBoite=floor($qteDispoEnBoite);// arrondi à nbr inférieur
																	$gamme[$key][$i]['qteDispoEnBoite']= $qteDispoEnBoite;
																	
																	// convert qte dispo en boite 
																	$qteDispoEnBoiteRe=$qteDispoAvecReserve/ $row['colisagee'];
																	$qteDispoEnBoiteRe=floor($qteDispoEnBoiteRe);// arrondi à nbr inférieur
																	$gamme[$key][$i]['qteDispoEnBoiteRe']= $qteDispoEnBoiteRe;
			
			
															}
															
							} 
					}
					
						$_COOKIE['Stock']=$gamme;
}// fin if seesion

//parcourir($_COOKIE['Stock']);return;
?>
			<!--div class="filtre">
			
			<input type="text" id="search" placeholder="<?php echo $trad['button']['Rechercher'];?>..." >
			</div-->
<div class="title" style="padding:20px;"><?php echo $trad['label']['ListArticle'];?></div>
<DIV class="ListeCmd">
	<div class="enteteL" >
	
			<div style="width:170px;" class="divArticleL"  ><?php echo $trad['label']['Gamme'];?> </div>
			<div style="width:130px;" class="divArticleL"  ><?php echo $trad['label']['reference'];?> </div>
			<div style="width:400px;" class="divArticleL"  ><?php echo $trad['label']['Article'];?> </div>
			<div style="width:100px;" class="divArticleL" ><?php echo $trad['label']['Colisage'];?>  </div>	
			<div style="width:200px;" class="divArticleL" ><?php echo $trad['label']['QteStock'].'('.$trad['label']['NbrPiece'].")";?>  </div>	
			<div style="width:200px;" class="divArticleL" ><?php echo $trad['label']['StockPrevente'].'('.$trad['label']['NbrPiece'].")";?>  </div>	
			<div style="width:200px;" class="divArticleL" ><?php echo $trad['label']['QteStock'].'('.$trad['label']['NbrBoite'].")";?>  </div>	
			<div style="width:200px;" class="divArticleL" ><?php echo $trad['label']['StockPrevente'].'('.$trad['label']['NbrBoite'].")";?>  </div>	
	</div>
	<div style="height:375px;overflow:scroll;" ><!--height:585px;-->
<?php 
$k=0;
//parcourir($_COOKIE['Stock']);
foreach($_COOKIE['Stock'] as $u=>$g){
$k++;
if($k%2 == 0) $c = "pair";
else $c="impair";
	if(is_array($g)){
?>

<div class="pair test"  style="border:2px solid #ebebeb" >
	<div style="padding:10px;"><?php echo ucfirst($g['dsgGamme']);?> </div>
</div>
<div style="display:none" >
									<?php  echo ucfirst(stripslashes($g['dsgGamme']))." ";?>
									<?php  echo ucfirst(stripslashes($g['RefGamme']))." ";?>
</div>
<?php
 foreach($g as $article){	
		if(is_array($article)){
			//if($article['qteStock']>0){
		?>
								<div class="impair test"  id="LigneStock">
								<div style="padding:10px;width:200px;" > 		</div>
								<div  class="divPv" style="width:130px;" align="left" > <?php  echo $article['RefArticle'];?>					
									</div>
									<div style="padding:10px;width:420px;" class="divPv" align="left" > 
										 <?php 
										 echo ucfirst(stripslashes(	wordwrap($article['DsgArticle'], 30, "\n")));
										  ?>					
									</div>
									<div class="divPv" style="padding:10px;width:50px;direction:ltr;text-align:right" > 									
									<?php  
									echo $article['colisage'] ; 
								//	echo number_format($valeur,2);
									?>
									</div> 
									<div class="divPv" style="padding:10px;width:250px;direction:ltr;text-align:right" >
									<?php  
									echo number_format($article['qteStock'],0," "," ");
										//echo number_format($r['Stock'], 0); 							
									?> </div> 
									<div class="divPv" style="padding:10px;width:250px;direction:ltr;text-align:right" >
									<?php  
									echo number_format($article['qteDispoAvecReserve'],0," "," ");						
									?> </div> 
									
									<div class="divPv" style="padding:10px;width:250px;direction:ltr;text-align:right" >
									<?php  
									echo number_format($article['qteDispoEnBoite'],0," "," ");						
									?> </div> 	
									<div class="divPv" style="padding:10px;width:250px;direction:ltr;text-align:right" >
									<?php  
									echo number_format($article['qteDispoEnBoiteRe'],0," "," ");						
									?> </div> 									
									<div style="display:none" >
										<?php  echo ucfirst(stripslashes($g['dsgGamme']))." ";?>
										<?php  echo ucfirst(stripslashes($article['DsgArticle']))." ";?>
										<?php  echo ucfirst(stripslashes($article['RefArticle']))." ";?>
										<?php  echo ucfirst(stripslashes($article['RefFam']))." ";?>
										<?php  echo ucfirst(stripslashes($g['RefGamme']))." ";?>
									</div>
								</div>
<?php
//}
}
}
	}
}
   ?>
	</div>
</div>
<?php 
exit;
}
//}
?>
<?php include("header.php"); ?>
<div style=" display:flex;align-items:center; padding:2px 0;"  class="headVente">
	<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
	<div >&nbsp;> <span  Class="TitleHead" onclick=""><?php echo $trad['label']['stockDepot'];?></span></div> 
</div>
<div style="clear:both;"></div>
<div id="formRes"></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<?php
include("footer.php");
?>
<div id="boxArticle"></div>
<div id="boxSearchArticle"></div>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
		//$.validator.messages.required = '';
  		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('stockDepot_t.php?aff');		
					$('#boxArticle').dialog({
						autoOpen		:	false,
						width			:	950,/*1260,*/
						height			:	575,
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
					$('#boxSearchArticle').dialog({
							autoOpen		:	false,
							width			:	950,/*1260,*/
							height			:	575,
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
			
});
function rechercher(){
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'stockDepot_t.php?aff'})
		clearForm('formRechF',0);
	}
function control(index)
{

 alert( "Handler for .blur() called." + $( ".textQte" ).attr("name") + index);

}
function Terminer(){
	    $('#formAdd').validate({ //initialise the plugin
		errorPlacement: function(error, element) { //just nothing, empty  
		}
		/*rules: {
                                               
                                                'qtech[]': "required",
												'motif[]': "required"
                                           } */ 
		});

	$("[name^=qtech]").each(function () {
        $(this).rules("add", {
            required: true/*,
            checkValue: true*/
        });
    });		
	var test=$('#formAdd').valid();
	/******Control Qte Charge********/
	index=0;
		$("[name^=qtech]").each(function () {
       // alert($(this).val());--------------------------------------Retour
		//alert($("input[val=stock"+index+"]").val());---------------QteChargee
		if(parseInt($(this).val()) > parseInt($("input[val=stock"+index+"]").val())){
		//   alert("here" + $(this).val() + " > "+ $("input[val=stock"+index+"]").val());
			//jAlert("le retour ne doit pas dépasser la quantité chargée.","Message");
			jAlert("<?php echo $trad['msg']['RetourDepasseCharge'];?>","<?php echo $trad['titre']['Alert'];?>");
		    $(this).css('color', 'red'); //$(this).focus();
			test=false;
		   }
		   else
		   {
		    $(this).css('color', 'black');  
		   }
		index++;
    });	
	
	
	
/*****function control the value of input*****/	
	/*$.validator.addMethod("checkValue", function (value, element) { alert(value);
        var response = ((value >= 0) && (value <= 100)) || ((value == 'test1') || (value == 'test2'));
        return response;
    }, "invalid value");	*/				
						

		if(test==true) {		
			 jConfirm('<?php echo $trad['msg']['terminerOperation'];?>', "<?php echo $trad['titre']['Alert'];?>", function(r) {
					if(r)	{
							$('#formAdd').ajaxSubmit({
									target			:	'#result',
									url				:	'stockDepot_t.php?goupdate',
									method			:	'post'
							}); 
							return false;
			           }
			
		})
	}
}
</script>

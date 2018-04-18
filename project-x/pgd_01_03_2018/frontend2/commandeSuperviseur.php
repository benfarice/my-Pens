<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();
include("lang.php");

$Operateur=1;
$IdDepot=$_SESSION['IdDepot'];
?>
<?php
if (isset($_GET['goupdateee'])){
print_r($_POST); exit;
//echo $_POST['IdArticle'][0]; exit;
/* --------------------Begin transaction---------------------- */
$error="";
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}

$articleVnd=array();
$sql = "SELECT idArticle FROM stockVendeurs sv WHERE idVendeur=? and stock >0";//dc.idColisage *
$params = array($_SESSION['IdVendeur']);	
$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}
$nRes = sqlsrv_num_rows($stmt);	
if($nRes!=0)
{
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{	
		array_push($articleVnd,$row['idArticle']);
	}
}
//echo "hereee : ";print_r($articleVnd);
$i=0;
foreach($_POST['IdArticle'] as $idArticle )
{
//---------------------------Nbr of outer --------------------------------//
$sql = "SELECT box FROM articles a INNER JOIN colisages c ON a.IdArticle=c.idArticle WHERE a.IdArticle=".$idArticle;
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$NbrOuter = sqlsrv_get_field( $stmt2, 0);
//---------------------------------------------------------------//
	$stock=(intval($_POST['Qcharge'][$i]) - intval($_POST['qtech'][$i]));
	$stock*=$NbrOuter;
	if(in_array($idArticle,$articleVnd))
	{	
	//echo "update <br/>";
		$requpdate = "update stockVendeurs set [stock]= [stock]+? where idVendeur=? and idArticle=?";
		$param= array($stock,$_SESSION['IdVendeur'],$idArticle) ;
		$stmt1 = sqlsrv_query( $conn, $requpdate, $param );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
			break;
		}
	}
	else
	{
	//echo "insert <br/>";
		$reqInser = "INSERT INTO stockVendeurs ([idArticle],[idVendeur],[stock]) values (?,?,?)";
		$params1= array($idArticle,$_SESSION['IdVendeur'],$stock) ;
		$stmt2 = sqlsrv_query( $conn, $reqInser, $params1 );
		if( $stmt2 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
			echo $error;
			
			break;
		}
	}
	$i++;
}//return;
$i=0;
foreach($_POST['idDetail'] as $idDetail )
{
	$stock=intval($_POST['Qcharge'][$i]) - intval($_POST['qtech'][$i]);
	$reqUpdate1 = "UPDATE detailChargements SET ecart = ?,reste = ?,etat = 1 ,motif=?
					WHERE IdDetailChargement=".$idDetail . " and idArticle=".$_POST['IdArticle'][$i] ;
	$params1= array($_POST['qtech'][$i],$stock,$_POST['motif'][$i]) ;
	$stmt2 = sqlsrv_query( $conn, $reqUpdate1, $params1 );
	if( $stmt2 === false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		break;
	}
	$i++;
}
	$reqUpdate2 = "UPDATE chargements SET etat = 1 WHERE IdChargement=".$_POST['Idchargement'] ;
	$params1= array() ;
	$stmt3 = sqlsrv_query( $conn, $reqUpdate2, $params1 );
	if( $stmt3 === false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		//break;
	}



if( $error=="" ) {
     sqlsrv_commit( $conn );
?>
		<script type="text/javascript"> 
		//	alert('La validation a été effectué.');
	//jAlert("La validation a été effectué.","Message");
	jAlert("<?php echo $trad['msg']['messageAjoutSucces'];?>","<?php echo $trad['titre']['Alert'];?>");
	document.location.href="index.php";
//	rechercher();
		</script>
<?php
} else {
     sqlsrv_rollback( $conn );
	 echo "<font style='color:red'>".$error."</font>";
}
exit;
}
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
//print_r($_SESSION['lignes']);
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
if(isset($_GET['getArticleee'])){

		$sql = "select IdArticle IdArticle, Designation Designation,Unite,CB FROM
 articles a where Designation like '%".$_GET['phrase']."%'";

	$array=array($_GET['phrase']);

	$reponse=sqlsrv_query( $conn, $sql, $array, array( "Scrollable" => 'static' ) );
	if( $reponse === false ) {
			$errors = sqlsrv_errors();
			$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
			echo $error;
			return;exit;
		}
		
	$nRes = sqlsrv_num_rows($reponse);

	if($nRes != 0)
	{
	$i=0;
		// while ($row =  sqlsrv_fetch_array($reponse)){
				$json = '[';
			 while($row = sqlsrv_fetch_array($reponse, SQLSRV_FETCH_ASSOC)){
				/*$Unite="";
					if(strtolower($row['Unite'])=="kg") $Unite= $trad['label']['kg']; 
					else if(strtolower($row['Unite'])=="piece") $Unite= $trad['label']['Piece']; 
					*/		
		$json .= '{"Designation": "' . $row["Designation"] . '","IdArticle":"'.$row["IdArticle"].'"}';
		$json .= ',';	//,"Unite":"'.$Unite.'","CB":"'.$row["CB"].'"
			}
		
	$json =substr($json, 0, -1);	
	$json .= ']';

echo ($json);return;
		header('Content-Type: application/json');
		echo ($json);
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
						<div class="divArticle divArticleWidth" align="<?php $_SESSION['align'];?>">
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
									url				:	'commandeSuperviseur.php?goupdate&idVnd='+IdVnd+'&idCmd='+IdCmd,
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
	var url='commandeSuperviseur.php?getCommande&getArticle';	
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
				 $params1= array($_SESSION['IdDepot'],$row['IdArticle']) ;

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
?>
<style>
.divArticleL{width:400px;}


</style>
<script>
function getcommande(numCmd){

		var url='commandeSuperviseur.php?getCommande&&numCmd='+numCmd;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	
}
</script>
<?php
/*********** selectionner la cmd d'un vendeur**********************/
$sql = "
		SELECT idCommandeVendeur,cv.numCommande,v.nom+ ' ' + v.prenom AS nom,cv.[date] as datec FROM commandeVendeurs cv 
			INNER JOIN vendeurs v ON cv.idVendeur=v.idVendeur			
			where v.idDepot=?  and etat=0";
	
	 $params = array($_SESSION['IdDepot']);
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
<div class="title"><?php echo $trad['label']['listeCommande'];?></div>
<DIV class="ListeCmd">
	<div class="enteteL" >
			<div  class="divArticleL"  ><?php echo $trad['label']['numCommande'];?> </div>
			<div  class="divArticleL" ><?php echo $trad['label']['Vendeur'];?>  </div>	
			<div  class="divArticleL" ><?php echo $trad['label']['DateCmd'];?>  </div>	
			
	</div>
	<div style="height:350px;overflow:scroll;" ><!--height:585px;-->
<?php 
$k=0;
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$k++;
if($k%2 == 0) $c = "pair";
else $c="impair";
?>
		<div class=" <?php echo $c;?>" onclick="getcommande('<?php  echo $row['idCommandeVendeur'];?>','list')">
			<div  class="divArticleL"  ><?php echo $row['numCommande'];?> </div>
			<div  class="divArticleL" ><?php echo $row['nom'];?>  </div>	
			<div  class="divArticleL" ><?php 

$newdate = date('d-m-Y', strtotime($row['datec']));echo $newdate; 

?>  </div>	
</div>


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
						<div >&nbsp;> <span  Class="TitleHead" onclick=""><?php echo $trad['label']['commandeSuperviseur'];?></span></div> 
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
  		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('commandeSuperviseur.php?aff');
		
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
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'commandeSuperviseur.php?aff'})
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
									url				:	'commandeSuperviseur.php?goupdate',
									method			:	'post'
							}); 
							return false;
			           }
			
		})
	}
}
</script>

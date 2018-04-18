<?php
include("../php.fonctions.php");
require_once('../connexion.php');
include("fonctionCalcule.php");
session_start();
include("lang.php");

$Operateur=1;
$IdDepot=$_SESSION['IdDepot'];
?>
<?php
if (isset($_GET['goupdate2'])){

//print_r($_POST);return;
$DateSortie=date("Y-m-d H:i:s");
$DateSortie = date_create(date("Y-m-d"));
$Imprime="";
$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
$nbrRef=0;
$nbrBoite=0;
$nbrPiece=0;
$TotalFac=0;

//----------------------modifier qte chargé detailfacture --------------------------//
//parcourir($_POST);return;
$QteImp="";
 for( $i= 0 ; $i < count($_POST['idDetail']) ; $i++ )
{
		$nbrRef+=1;
		if($_POST['UniteVente'][$i]=="Colisage")
		{	
			$nbrBoite=$nbrBoite+$_POST["qtech"][$i];
			$QteImp="(".$_POST["qtech"][$i]."X".$_POST["Colisage"][$i].")";
			$QteCmd=tofloat($_POST["Colisage"][$i])*floatval($_POST["qtech"][$i]);
			$TotalPriceArti=floatval($_POST["Colisage"][$i])*floatval($_POST["qtech"][$i])*floatval($_POST["PriceUnite"][$i]);
					//	echo floatval($_POST["NbrBox"]);return;
		}else{
			$nbrPiece=$nbrPiece+$_POST["qtech"][$i];
				$QteImp=$_POST["qtech"][$i];
			$QteCmd=tofloat($_POST["qtech"][$i]);
			$TotalPriceArti=floatval($_POST["qtech"][$i])*floatval($_POST["PriceUnite"][$i]);
		}
		
		
	 $reqUp = "update detailFactures set qte=".$_POST['qtech'][$i]." , ttc=".$TotalPriceArti."  where IddetailFacture = ?  ";
			$paramsUp= array($_POST['idDetail'][$i]) ;
			$stmt3 = sqlsrv_query( $conn, $reqUp, $paramsUp );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : modif etat cmd ".$errors[0]['message'] . " <br/> ";
				//break ;
			}
			$Imprime.=ucwords( $_POST["NomArt"][$i]).PHP_EOL;
			$Imprime.= $trad['label']['Code']." : ".$_POST["Reference"][$i].PHP_EOL;
			$Imprime.=str_pad($QteImp, 5, ' ', STR_PAD_LEFT)." :".str_pad(number_format($_POST["PriceUnite"][$i], 2, '.', ' '), 8, ' ', STR_PAD_LEFT)."  ".str_pad(number_format($TotalPriceArti, 2, '.', ' '), 14, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
			$Imprime.=PHP_EOL;
		
		/*	$reqUpStock = "update   stockVendeurs set stock=stock-$QteCmd where idVendeur = ? and idArticle=?			 ";
			$paramsUp= array($_POST['IdVendeur'][0],$_POST["idArticle"][$i]) ;
			$stmtUp = sqlsrv_query( $conn, $reqUpStock, $paramsUp );
			if( $stmtUp === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : modification du stock vendeur ".$errors[0]['message'] . " <br/> ";
				break ;
			}*/
			
			//----------------------modifier etat de la sortie du stock du 0 à 1 stock réelement sortie  --------------------------//

		$reqUp = "update detailMouvements set qte=".$_POST['qtech'][$i]." , EtatSotie=1  where IdFacture = ? and idArticle=? and UniteVente=?  ";
		$paramsUp= array($_POST['idCmd'],$_POST['idArticle'][$i],$_POST["UniteVente"][$i]) ;
			$stmt3 = sqlsrv_query( $conn, $reqUp, $paramsUp );
			if( $stmt3 === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : modif detail sortie stock ".$errors[0]['message'] . " <br/> ";
				//break ;
			}	
	$TotalFac+=$TotalPriceArti;
}

//----------------------modifier etat de la commande du 1 à 2 cmd validé par superviseur --------------------------//*
$target_path='';
		if(isset($_FILES['file']))
			{
				$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
				$file_extension = end($ext); // Store extensions in the variable.
				$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
				if (!file_exists("imgPaiement/")) {
					mkdir("imgPaiement/", 0777, true);
				}
				$target_path = "imgPaiement/" . $nameFile;     // Set the target path with a new name of image.
				
					
					
					if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
						{
							$error="error";
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
			
 $reqInserUp = "update factures set EtatCmd=2 ,TotalTTC=".tofloat($TotalFac)." ,
 Espece=".tofloat($_POST['MtEspece']).",Cheque=".tofloat($_POST['MtCheque']).",Credit=".tofloat($_POST['MtCredit']).",
 PhotoCheque='".$target_path."'
 where IdFacture = ? ";
// echo $reqInserUp;
			$paramsUp= array($_POST['idCmd']) ;
			$stmtUp = sqlsrv_query( $conn, $reqInserUp, $paramsUp );
			if( $stmtUp === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : modif etat cmd ".$errors[0]['message'] . " <br/> ";
				//break ;
			}	
	$ArrayError=	insertAvanceCredit($conn,$_POST['IdClient'][0],$_POST['IdVendeur'][0]);	
$ErrorAvance=$ArrayError[0];
$ImprimAvance=$ArrayError[1];
			//echo $ErrorAvance;return;

//---------------------------Recuperer info impression--------------------------------//
$sql = " SELECT IdFacture IdFacture,NumFacture as NumFacture ,v.nom+ ' '+v.prenom Vendeur,c.CodeClient Client, c.intitule IntituleClt,
 v2.Designation Ville,c.Tel,
v.codeVendeur CodeVdr,f.totalTTC
	FROM 
	factures f  INNER JOIN clients c  ON c.IdClient=f.idClient
	INNER JOIN vendeurs v ON v.idVendeur = f.idVendeur
	inner join depots d on d.idDepot=c.idDepot
	inner join villes v2 on v2.idVille=d.idVille
	 WHERE IdFacture=".$_POST['idCmd']
;

$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idfacture : ".sqlsrv_errors() . " <br/> ";
}
	 while( $row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {
			$IdFacture = $row["IdFacture"];
			$NumFacture = $row["NumFacture"];
			$Client=$row["Client"];
			$NomVdr=$row["Vendeur"];
			$CodeVdr=$row["CodeVdr"];
			$IntituleClt=$row["IntituleClt"];
			$Ville=$row["Ville"];
			$Tel=$row["Tel"];

	}

$Imprime.=PHP_EOL;
$Imprime.=$trad['label']['TotalFac']." ".str_pad(number_format($TotalFac, 2, '.', ' '), 17, ' ', STR_PAD_LEFT)." ".$trad['label']['riyal'] ." ".PHP_EOL;
$Imprime.=$trad['label']['NbrRef']." ".str_pad($nbrRef, 17, ' ', STR_PAD_LEFT).PHP_EOL;
if(($nbrBoite!=0)&&($nbrPiece!=0)){
	$Imprime.=$trad['label']['Box']."/".$trad['label']['NbrPiece']." : ".str_pad($nbrBoite, 17, ' ', STR_PAD_LEFT)."/".$nbrPiece.PHP_EOL;

}else if(($nbrBoite!=0)&&($nbrPiece==0)){
	$Imprime.=$trad['label']['Box']." : ".str_pad($nbrBoite, 23, ' ', STR_PAD_LEFT).PHP_EOL;
}else if(($nbrBoite==0)&&($nbrPiece!=0)){
	$Imprime.=$trad['label']['NbrPiece']." : ".str_pad($nbrPiece, 25, ' ', STR_PAD_LEFT).PHP_EOL;
}

$Imprime.="----------------------------------------".PHP_EOL;		
$enteteFile="";
if( ($error=="" ) && ($ErrorAvance=="")) {
     sqlsrv_commit( $conn );

	$Date=date_create(date("Y-m-d  H:i"));
	$enteteFile.="VENDEUR : ".strtoupper($NomVdr).PHP_EOL ;
	$enteteFile.="DATE ET HEURE : ".date_format($Date, 'd/m/Y H:i').PHP_EOL;
	$enteteFile.=$NumFacture.PHP_EOL ;
	$enteteFile.=PHP_EOL ;
	$enteteFile.="CLIENT : ".strtoupper($Client)." - ".strtoupper($IntituleClt).PHP_EOL ;
	if( $Tel!="") $enteteFile.="Tel : ".$Tel.PHP_EOL ;
	$enteteFile.="VILLE : ".strtoupper($Ville).PHP_EOL ;
	$enteteFile.=PHP_EOL ;
	$enteteFile.=PHP_EOL;
	$Imprime=$enteteFile.$Imprime.$ImprimAvance;
	//$name="Paiement ".date('d-m-Y H-i');
	$name=date('d-m-Y H-i');
	$fp = fopen ("bon_cmd/".$name.".txt", "w");
	fputs ($fp, $Imprime);
	fclose ($fp);

	$dir="bon_cmd/".$name.".txt";
	$filename=$name.".txt";
	$name= urlencode ($name);
     ?>
		<script type="text/javascript"> 
		    var url="download.php?fileName=<?php echo $name;?>";	
	    	document.location.href=url;
			alert('<?php echo $trad['msg']['messageAjoutSucces'];?>');
			dialog.dialog('close');
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
if (isset($_GET['paiement'])){
//echo $_GET['numCmd'];
?>
<link rel="stylesheet" href="css/step2.css">
<link rel="stylesheet" href="css/jquery.steps.css">
<script src="js/jquery.steps.js"></script>
<script src="js/jquery-filestyle.min.js" type="text/javascript"></script>
<link href="css/jquery-filestyle.css"  rel="stylesheet" />
<script language="javascript" type="text/javascript">
function Valider2(){
	/******Control Qte Charge********/
	index=0;
	test=true;
//	alert(test);
			if(test==true) {		
			 jConfirm('<?php echo $trad['msg']['terminerOperation'];?>', "<?php echo $trad['titre']['Alert'];?>", function(r) {
					if(r)	{
							$('#formAdd').ajaxSubmit({
									target			:	'#result',
									url				:	'precommande.php?goupdate2',//&idVnd='+IdVnd+'&idCmd='+IdCmd,
									method			:	'post'
							}); 
							return false;
			           }
			
		})
	}
}
/*
function TerminerCmd(){
		var test=true;		
	

		if(test==true){									
		$('#formReg').ajaxSubmit({
							target:'#resReg',
							url:'catalogue5.php?TerminerCmd',
								method			:	'post',
							success: function(data) {  							
								//	Imprimer(data);									
								window.location='mapClient.php';
							  }
							});
		patienter('resReg');
		}

	}*/
function Imprimer(IdFacture){
			 options = "Width=1280,Height=800" ;
		  window.open( 'facture.print.php?IdFacture='+IdFacture, "edition", options ) ;
		
	}
$(document).ready(function() {


	$(":file").jfilestyle({input: false,buttonText: "<img src='img/folder.png' /><?php echo $trad['button']['parcourir'] ; ?>"});
	 $("#wizard").steps({
					  labels: {
						
							finish: "Teminer",
							next: "Suivant",
							previous: "Précédent",
							loading: "Chargement ..."
					  },
					  onInit: function (event, current) {
							$('.actions > ul > li:first-child').attr('style', 'display:none');
						},
                        headerTag: "h2",
                        bodyTag: "section",
                        transitionEffect: "slideLeft",
						onFinished: function (event, currentIndex) {
						  Valider2();
						},
						 onStepChanging: function (event, currentIndex, newIndex) {
							var test=true;
							index=0;
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
									var idDepot="<?php echo $IdDepot;?>";
										//alert(idDepot);
									if(idDepot!=6){ // si ce n'est pas le dépot du agadir on effectue le controle du stock
	
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
									}
								if(test==true){
								
								//	alert($("#Reglement").html());
									index=0;TotalFac=0;
									$("[name^=qtech]").each(function () {
									if(parseInt($(this).val()) !=0){
										UniteVente=$("#UniteVente"+index).val();
										PriceUnite=$("#PriceUnite"+index).val();
										if(UniteVente=="Pièce")
										{
											TotalFac=TotalFac+parseFloat($(this).val())*parseFloat(PriceUnite);
										}else if(UniteVente=="Colisage"){
												Colisage=$("#Colisage"+index).val();
												TotalFac=TotalFac+parseFloat($(this).val())*parseFloat(PriceUnite)*parseInt(Colisage);
										}
										
									}
									
									index++;
									});	
									if($("#Reglement").html()==""){
									
										IdClient=$("#IdClient").val();
										$("#Reglement").html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('paiement.php?ChoixTypeReg&&Precommande&&TotalFac='+TotalFac+'&&IdClient='+IdClient);
										$("#TotalFac").val(TotalFac);
										$("#MtEspece").val(TotalFac);
										$("#Fac").text(TotalFac);
									}
									else {
										// s'il a changer la qte cmdé en reinisialiser la formulaire du regelement
									
										if(parseFloat($("#Fac").text())!=parseFloat(TotalFac)){
											$("#TotalFac").val(TotalFac);
											$("#MtEspece").val(TotalFac);
											$("#Fac").text(TotalFac);
											$("#MtCheque").val("");
											$("#MtCredit").val("");
											$('#CheckboxC').attr('checked', false);
											
										}
									}
								
										
										
									}
								 return test;									
						},
						  onStepChanged: function (event, current, next) {
								 if (current > 0) {
									$('.actions > ul > li:first-child').attr('style', '');
									} else {
										$('.actions > ul > li:first-child').attr('style', 'display:none');
									}
							  }
                    });
});

	
</script>
<style>
.wizard > .steps{
	display:none;
}
input[type="checkbox"]{
  width: 160px; /*Desired width*/
  height: 50px; /*Desired height*/
  vertical-align: bottom;
  position: relative;
  top: 3px;
  *overflow: hidden;
 
}
.wizard > .content > .body {
    width: 100%;
	}
</style>
<input type="button" value=""  class="close2" onclick="Fermer()" Style="float:right;"/>
<div class="clear"></div>
<div id="result"></div>

<?php 
/*********** selectionner la cmd d'un vendeur**********************/
$sql = "
			SELECT 	 f.idVendeur,IddetailFacture,f.idClient,f.idFacture,d.idArticle as IdArticle,a.designation as NomArt,d.qte
			as Qte,a.Reference,
			d.UniteVente,c.colisagee Colisage,pvHT
			from detailFactures d 
			inner join factures f on f.idFacture=d.idFacture
			inner join articles a on a.idArticle=d.idArticle
			inner join  tarifs t on t.idArticle=a.idArticle
			INNER JOIN dbo.ficheTarifs fi ON fi.idFiche=t.idFiche
			inner join colisages c on c.idArticle=a.idArticle
			WHERE f.idFacture=? and EtatCmd=?  AND fi.etat=1 AND f.TypeVente=fi.TypeVente";
	
	
	 $params = array($_GET['numCmd'],1);//
//echo $sql. ' ' .$_GET['numCmd'];return;
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
?> <form id="formAdd" method="post" name="formAdd"> 
     <div id="wizard"> 
	
	     <h2></h2>
                <section>
<div class="title"><?php echo $trad['label']['listeArticle'];?></div>
<DIV class="ListeCmd">
	<div class="enteteL" >
			<div  class="divArticleL"  style="width:212px" ><?php echo $trad['label']['Article'];?> </div>
			<div  class=" divQteL" style="width:97px"><?php echo $trad['label']['Colisage'];?>  </div>
			<div  class="divTTC" style="width:188px; text-align:center" ><?php echo $trad['label']['QteCmd'];?>  </div>	
			<div  class="divArticleL" style="width:189px" ><?php echo $trad['label']['QteDisponible'];?> </div>	
			<div  class="divArticleL" style="width:140px" ><?php echo $trad['label']['qtecharge'];?>  </div>					
	</div>
	<div style="height:350px;overflow:scroll;" ><!--height:585px;-->
	

<?php 
$k=0;$i=0;
$idVnd="";$idCmd="";
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$idVnd=$row['idVendeur'];
$idCmd=$row['idFacture'];

$k++;
if($k%2 == 0) $c = "pair";
else $c="impair";
$qteDispo= 0;

//-----------------------------Quantité disponible reel  par article dans le depot----------------------------------
			$params= array($row['IdArticle'],$_SESSION['IdDepot']) ;
			$qteDispo=  qteDispoArticle($params,$conn,'reel');	
			if($row['UniteVente']=="Colisage") $UniteVente="Boite";else $UniteVente="Pièce";
?>
<input type="hidden" value="<?php  echo $row['idClient']; ?>" id="IdClient" name="IdClient[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['NomArt']; ?>" id="IdClient" name="NomArt[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['Reference']; ?>" id="IdClient" name="Reference[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $idVnd; ?>" id="IdVendeur" name="IdVendeur[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $idCmd ?>" id="idCmd" name="idCmd">
<input type="hidden" value="<?php  echo $row['IddetailFacture']; ?>" name="idDetail[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['IdArticle']; ?>" name="idArticle[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['pvHT']; ?>" name="PriceUnite[<?php echo $i; ?>]" id="PriceUnite<?php echo $i; ?>"/>
<input type="hidden" value="<?php  echo $row['UniteVente']; ?>" name="UniteVente[<?php echo $i; ?>]" id="UniteVente<?php echo $i; ?>"/>
<input type="hidden" value="<?php  echo $row['Colisage']; ?>" name="Colisage[<?php echo $i; ?>]" id="Colisage<?php echo $i; ?>"/>

		<div  class="<?php echo $c; ?>" >
			<div align="left" class="divArticleLigne" style="width:220px;" ><?php echo $row['NomArt'];?> </div>
			<div align="left" class="divPVL"  style="width:100px" ><?php echo $row['Colisage'];?> </div>
			<div  align="right" class="divArticleLigne"  style="width:195px" ><?php echo $row['Qte']." ".$UniteVente."";?>  </div>	
			<div  align="right" class="divArticleLigne"  style="width:195px" >
			<?php 
			// convert qte dispo en boite 
		//	echo $qteDispo."<br>";
			if($row['UniteVente']=="Colisage"){ 
			// convert qte dispo en boite 
			$qteDispo=$qteDispo/ $row['Colisage'];
			$qteDispo=floor($qteDispo);// arrondi à nbr inférieur
			echo number_format($qteDispo,0," "," ");}
			else { // echo $qteDispo;}
			echo number_format($qteDispo,0," "," ") ;} 
			?>
			<input type="hidden" val="qteDispo<?php echo $i ; ?>" value="<?php  echo $qteDispo; ?>" 
			name="qteDispo[<?php echo $i; ?>]">
	
	</div>	
			<div  align="right" class="divArticleLigne"  style="width:140px" >	
				<input class="numberOnly" type="text" value="<?php echo $row['Qte'];?> "  
				size="5"  style="width:140px" name="qtech[]" onkeypress="return isEntier(event) " />
			</div>	
		</div>


		
<?php 
	$i++;
 }  ?>
 </div>
 </div>
  </section>
 <h2>Second Step</h2>
     <section>
 <div id="Reglement" STYLE="height:430px;width:100%;padding-left:50px"></div>
  </section>

            </div><!-- fin wizard-->
</form>


<?php 

}
	exit;
}	
if (isset($_GET['goupdateee'])){
//print_r($_POST); exit;
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
//parcourir($_POST['PriceUnite']);return;
$DateSortie=date("Y-m-d H:i:s");
$DateSortie = date_create(date("Y-m-d"));
$error="";
$QteImp="";
$test="";
$TotalPriceArti=0;
$ModePaiement="";
$nbrRef=0;
$nbrBoite=0;
$nbrPiece=0;
$Imprime="";$enteteFile ="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//----------------------modifier qte chargé detailfacture --------------------------//
//parcourir($_POST['idDetail']);return;
$TotalFac=0;$TotalPriceArti2="";
 for( $i= 0 ; $i < count($_POST['idDetail']) ; $i++ )
{		
		$nbrRef+=1;
		if($_POST['UniteVente'][$i]=="Colisage")
		{	
			$nbrBoite=$nbrBoite+$_POST["qtech"][$i];
			$QteImp="(".$_POST["qtech"][$i]."X".$_POST["Colisage"][$i].")";
			$TotalPriceArti=floatval($_POST["Colisage"][$i])*floatval($_POST["qtech"][$i])*floatval($_POST["PriceUnite"][$i]);
					//	echo floatval($_POST["NbrBox"]);return;
		}else{
			$nbrPiece=$nbrPiece+$_POST["qtech"][$i];
				$QteImp=$_POST["qtech"][$i];
			$TotalPriceArti=floatval($_POST["qtech"][$i])*floatval($_POST["PriceUnite"][$i]);
		}
		$TotalPriceArti=floatval($_POST["qtech"][$i])*floatval($_POST["PriceUnite"][$i]);
		$TotalPriceArti2=$TotalPriceArti2.$_POST["Reference"][$i]." ".$TotalPriceArti." ".$_POST['idDetail'][$i]."<br>";
	 $reqUp = "update detailFactures set qte=".$_POST['qtech'][$i]." , ttc=".$TotalPriceArti."  where IddetailFacture = ?  ";
		$paramsUp= array($_POST['idDetail'][$i]) ;
			$stmt3 = sqlsrv_query( $conn, $reqUp, $paramsUp );
			if( $stmt3 === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : modif detail cmd ".$errors[0]['message'] . " <br/> ";
				//break ;
			}	
	$TotalFac+=$TotalPriceArti;
	$Imprime.=ucwords( $_POST["NomArt"][$i]).PHP_EOL;
	$Imprime.= $trad['label']['Code']." : ".$_POST["Reference"][$i].PHP_EOL;
	$Imprime.=str_pad($QteImp, 5, ' ', STR_PAD_LEFT)." :".str_pad(number_format($_POST["PriceUnite"][$i], 2, '.', ' '), 8, ' ', STR_PAD_LEFT)."  ".str_pad(number_format($TotalPriceArti, 2, '.', ' '), 14, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
	$Imprime.=PHP_EOL;
// modification de la qte sortie du stock pour chaque article ,sortie  pas encore vendu alors etat stock reçoit 0
	$reqUp = "update detailMouvements set qte=".$_POST['qtech'][$i]."  where IdFacture = ? and idArticle=?  and UniteVente=?";
		$paramsUp= array($_GET['idCmd'],$_POST['idArticle'][$i],$_POST["UniteVente"][$i]) ;
			$stmt3 = sqlsrv_query( $conn, $reqUp, $paramsUp );
			if( $stmt3 === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : modif detail sortie stock ".$errors[0]['message'] . " <br/> ";
				//break ;
			}	
}
//echo $TotalPriceArti2;return;
//---------------------------Recuperer info impression--------------------------------//
$sql = " SELECT IdFacture IdFacture,NumFacture as NumFacture ,v.nom+ ' '+v.prenom Vendeur,c.CodeClient Client, c.intitule IntituleClt,
 v2.Designation Ville,c.Tel,
v.codeVendeur CodeVdr,f.totalTTC
	FROM 
	factures f  INNER JOIN clients c  ON c.IdClient=f.idClient
	INNER JOIN vendeurs v ON v.idVendeur = f.idVendeur
	inner join depots d on d.idDepot=c.idDepot
	inner join villes v2 on v2.idVille=d.idVille
	 WHERE IdFacture=".$_POST['idCmd']
;

$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur récupération idfacture : ".sqlsrv_errors() . " <br/> ";
}
	 while( $row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {
	$IdFacture = $row["IdFacture"];
	$NumFacture = $row["NumFacture"];
	$Client=$row["Client"];
    $NomVdr=$row["Vendeur"];
	$CodeVdr=$row["CodeVdr"];
	$IntituleClt=$row["IntituleClt"];
	$Ville=$row["Ville"];
     $Tel=$row["Tel"];
	}
$Imprime.=PHP_EOL;
$Imprime.=$trad['label']['TotalFac']." ".str_pad(number_format($TotalFac, 2, '.', ' '), 17, ' ', STR_PAD_LEFT)." ".$trad['label']['riyal'] ." ".PHP_EOL;
$Imprime.=$trad['label']['NbrRef']." ".str_pad($nbrRef, 17, ' ', STR_PAD_LEFT).PHP_EOL;
if(($nbrBoite!=0)&&($nbrPiece!=0)){
	$Imprime.=$trad['label']['NbrBoite']."/".$trad['label']['NbrPiece']." : ".str_pad($nbrBoite, 17, ' ', STR_PAD_LEFT)."/".$nbrPiece.PHP_EOL;

}else if(($nbrBoite!=0)&&($nbrPiece==0)){
	$Imprime.=$trad['label']['Box']." : ".str_pad($nbrBoite, 23, ' ', STR_PAD_LEFT).PHP_EOL;
}else if(($nbrBoite==0)&&($nbrPiece!=0)){
	$Imprime.=$trad['label']['NbrPiece']." : ".str_pad($nbrPiece, 25, ' ', STR_PAD_LEFT).PHP_EOL;
}

$Imprime.="----------------------------------------".PHP_EOL;			
//----------------------modifier etat de la commande du 0 à 1 càd cmd préparée --------------------------//
 $reqInserUp = "update factures set EtatCmd=1 ,TotalTTC=".tofloat($TotalFac)." where IdFacture = ?";
			$paramsUp= array($_GET['idCmd']) ;
			$stmtUp = sqlsrv_query( $conn, $reqInserUp, $paramsUp );
			if( $stmtUp === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : modif etat cmd ".$errors[0]['message'] . " <br/> ";
				//break ;
			}			
if( ($error=="" ) ) {
	
	sqlsrv_commit( $conn );

	$Date=date_create(date("Y-m-d  H:i"));
	$enteteFile.="VENDEUR : ".strtoupper($NomVdr).PHP_EOL ;
	$enteteFile.="DATE ET HEURE : ".date_format($Date, 'd/m/Y H:i').PHP_EOL;
	$enteteFile.=$NumFacture.PHP_EOL ;
	$enteteFile.=PHP_EOL ;
	$enteteFile.="CLIENT : ".strtoupper($Client)." - ".strtoupper($IntituleClt).PHP_EOL ;
	if( $Tel!="") $enteteFile.="Tel : ".$Tel.PHP_EOL ;
	$enteteFile.="VILLE : ".strtoupper($Ville).PHP_EOL ;
	$enteteFile.=PHP_EOL ;
	$enteteFile.=PHP_EOL;
	$Imprime=$enteteFile.$Imprime;
	//$name="Livraison ".date('d-m-Y H-i');
	$name=date('d-m-Y H-i');
	$fp = fopen ("bon_cmd/".$name.".txt", "w");
	fputs ($fp, $Imprime);
	fclose ($fp);

	$dir="bon_cmd/".$name.".txt";
	$filename=$name.".txt";
	$name= urlencode ($name);
     ?>
		<script type="text/javascript"> 
		   /* var url="download.php?fileName=<?php echo $name;?>";	
	    	document.location.href=url;*/
			alert('<?php echo $trad['msg']['messageAjoutSucces'];?>');
			dialog.dialog('close');
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
//echo $_GET['numCmd'];
?>

<script language="javascript" type="text/javascript">

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
		var idDepot="<?php echo $IdDepot;?>";
		//alert(idDepot);
	if(idDepot!=6){ // si ce n'est pas le dépot du agadir on effectue le controle du stock
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
	}
	//alert(test);
			if(test==true) {		
			 jConfirm('<?php echo $trad['msg']['terminerOperation'];?>', "<?php echo $trad['titre']['Alert'];?>", function(r) {
					if(r)	{
							$('#formAdd').ajaxSubmit({
									target			:	'#result',
									url				:	'precommande.php?goupdate&idVnd='+IdVnd+'&idCmd='+IdCmd,
									method			:	'post'
							}); 
							return false;
			           }
			
		})
	}
}
</script>
<div id="result"></div>
<?php 
/*********** selectionner la cmd d'un vendeur**********************/
$sql = "
			SELECT 	 f.idVendeur,IddetailFacture,f.idFacture,d.idArticle as IdArticle,a.designation as NomArt,d.qte as Qte,
			d.UniteVente,c.colisagee Colisage,d.tarif Tarif,a.Reference
			from detailFactures d 
			inner join factures f on f.idFacture=d.idFacture
			inner join articles a on a.idArticle=d.idArticle
			inner join colisages c on c.idArticle=a.idArticle
			WHERE f.idFacture=? ";
	
	 $params = array($_GET['numCmd']);//
//echo $sql. ' ' .$_GET['numCmd'];return;
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
<div class="title"><?php echo $trad['label']['listeArticle'];?></div>
<DIV class="ListeCmd">
	<div class="enteteL" >
			<div  class="divArticleL"  style="width:212px" ><?php echo $trad['label']['Article'];?> </div>
			<div  class=" divQteL" style="width:97px"><?php echo $trad['label']['Colisage'];?>  </div>
			<div  class="divTTC" style="width:188px; text-align:center" ><?php echo $trad['label']['QteCmd'];?>  </div>	
			<div  class="divArticleL" style="width:189px" ><?php echo $trad['label']['QteDisponible'];?> </div>	
			<div  class="divArticleL" style="width:140px" ><?php echo $trad['label']['qtecharge'];?>  </div>				
	</div>
	<div style="height:320px;overflow:scroll;" ><!--height:585px;-->
	<form id="formAdd" method="post" name="formAdd"> 

<?php 
$k=0;$i=0;
$idVnd="";$idCmd="";
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$idVnd=$row['idVendeur'];
$idCmd=$row['idFacture'];
$k++;
if($k%2 == 0) $c = "pair";
else $c="impair";


//-----------------------------Quantité disponible par article dans le depot----------------------------------
			$params= array($row['IdArticle'],$_SESSION['IdDepot']) ;
			$qteDispo=  qteDispoArticle($params,$conn,'reel');	
			//$qteDispo=number_format($qteDispo,0," "," ")
			if($row['UniteVente']=="Colisage") $UniteVente="Boite";else $UniteVente="Pièce";
			
?>

<input type="hidden" value="<?php  echo $row['NomArt']; ?>" id="IdClient" name="NomArt[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['Reference']; ?>" id="IdClient" name="Reference[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $idVnd; ?>" id="IdVendeur" name="IdVendeur[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $idCmd ?>" id="idCmd" name="idCmd">

<input type="hidden" value="<?php  echo $row['Tarif']; ?>" name="PriceUnite[<?php echo $i; ?>]" id="PriceUnite<?php echo $i; ?>"/>
<input type="hidden" value="<?php  echo $row['UniteVente']; ?>" name="UniteVente[<?php echo $i; ?>]" id="UniteVente<?php echo $i; ?>"/>
<input type="hidden" value="<?php  echo $row['IddetailFacture']; ?>" name="idDetail[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['IdArticle']; ?>" name="idArticle[<?php echo $i; ?>]">
<input type="hidden" value="<?php  echo $row['Colisage']; ?>" name="Colisage[<?php echo $i; ?>]" id="Colisage<?php echo $i; ?>"/>
		<div  class="<?php echo $c; ?>" >
			<div align="left" class="divArticleLigne" style="width:220px"  ><?php echo $row['NomArt'];?> </div>
					<div align="left" class="divQteL" style="width:100px"  ><?php echo $row['Colisage'];?> </div>
			<div  align="right" class="divQteL" style="width:195px" ><?php				
			echo $row['Qte']." ".$UniteVente."";?>  </div>	
			<div  align="right" class="divArticleLigne" style="width:195px" ><?php 
			// convert qte dispo en boite 			
			if($row['UniteVente']=="Colisage"){ 
			// convert qte dispo en boite 
			$qteDispo=$qteDispo/ $row['Colisage'];
			$qteDispo=floor($qteDispo);// arrondi à nbr inférieur
			echo number_format($qteDispo,0," "," ");}
			else { echo number_format($qteDispo,0," "," ") ;} 
			?>
			<input type="hidden" val="qteDispo<?php echo $i ; ?>" value="<?php  echo $qteDispo; ?>" name="qteDispo[<?php echo $i; ?>]">
	 </div>	
			<div  align="right" class="divArticleLigne" style="width:140px" >	
				<input class="numberOnly" type="text" style="width:140px" value="<?php echo $row['Qte'];?> "  size="5" name="qtech[]" onkeypress="return isEntier(event) " />
			</div>	
		</div>


<?php 
	$i++;
 }  ?>

</form>
	</div>
</div>

<div class="btnV" style="margin:10px 10px 0 0">

<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="Fermer()"/>
	<input type="button" value="<?php echo $trad['button']['Enregistrer'];?>" class="btn" onclick="Valider(<?php echo $idVnd;?>,<?php echo $idCmd; ?>)"/>
</div>
<?php 

}
exit;

}  ?>
<?php
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
function getcommande(numCmd,EtatCmd,IdClient,IdVdr){
	if(EtatCmd==0){
		// validation de la cmd par supperviseur
		var url='precommande.php?getCommande&&numCmd='+numCmd;	
		dialog.html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}else 
		//paiement de la cmd par supperviseur
			if(EtatCmd==1){
			var url='precommande.php?paiement&&numCmd='+numCmd+'&&IdClient='+IdClient+'&&IdVdr='+IdVdr;	
			dialog.html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
			}
	
}
</script>
<?php
/*********** selectionner la cmd d'un vendeur**********************/
$sql = "
		SELECT IdFacture IdFacture,NumFacture numCommande,v.nom+ ' ' + v.prenom AS nom,f.[date] as datec,EtatCmd 
		,f.idClient IdClient,f.idVendeur,f.totalTTC,c.intitule FROM
				factures f 
			INNER JOIN vendeurs v ON f.idVendeur=v.idVendeur	
			INNER JOIN clients c ON c.IdClient=f.idClient			
			where v.idDepot=?  and (EtatCmd=0 or EtatCmd=1) order by IdFacture desc";
	
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
<!--div class="title"><?php echo $trad['label']['listePreCommande'];?></div-->
<DIV class="ListeCmd">
	<div class="enteteL" >
			<div  class="divArticleL"   style="width:240px ;text-align:left;"><?php echo $trad['label']['numCommande'];?> </div>
			<div  class="divArticleL" style="width:330px; text-align:center;" ><?php echo $trad['label']['Vendeur'];?>  </div>	
			<div  class="divArticleL" style="width:410px; text-align:center;" ><?php echo $trad['label']['Intitule'];?>  </div>	
			<div  class="divArticleL" style="width:300px; text-align:center;" ><?php echo $trad['label']['TotalTTC']." (".$trad['label']['riyal'].")";?>  </div>	
			<div  class="divArticleL"  style="width:228px; text-align:center;"><?php echo $trad['label']['DateCmd'];?>  </div>	
			<div  class="divArticleL"  style="width:305px;text-align:center;" ><?php  echo $trad['label']['Etat'];?>  </div>	
			
	</div>
	<div style="height:350px;overflow:scroll;" ><!--height:585px;-->
<?php 
$k=0;
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$k++;
if($row['EtatCmd'] == 0) $c = "precommande";
else $c="livre";
?>
		<div class=" <?php echo $c;?>"
		onclick="getcommande('<?php  echo $row['IdFacture'];?>','<?php  echo $row['EtatCmd'];?>','<?php  echo $row['IdClient'];?>',
		'<?php  echo $row['idVendeur'];?>')">
			<div  class="divArticleL"  style="width:240px" ><?php echo $row['numCommande'];?> </div>
			<div  class="divArticleL"  style="width:330px;" ><?php echo $row['nom'];?>  </div>	
			<div  class="divArticleL"  style="width:410px;" ><?php echo $row['intitule'];?>  </div>	
			<div  class="divArticleL"  style="width:300px;text-align:right;" ><?php
			echo number_format($row['totalTTC'], 2, '.', ' ');?>  </div>	
			<div  class="divArticleL"  style="width:228px;text-align:right;"  ><?php 
				$newdate = date('d/m/Y', strtotime($row['datec']));echo $newdate; 
				?>  </div>	
		<div  class="divArticleL"  style="width:270px;" ><?php echo  $trad['label'][$c];?>  </div>	
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
						<div >&nbsp;> <span  Class="TitleHead" onclick=""><?php echo $trad['label']['Precommande'];?></span></div> 
</div>

<div style="clear:both;"></div>

<div id="formRes"></div><!--style="overflow-y:scroll;min-height:280px;"--> 

<?php
include("footer.php");
?>
<div id="boxArticle"></div>
<script language="javascript" type="text/javascript">
function Fermer(){
//	$("#boxArticle").dialog('close');
	dialog.dialog('close');
}

			var dialog=	$('#boxArticle').dialog({
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
$(document).ready(function() {
		//$.validator.messages.required = '';
  		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('precommande.php?aff');
		

			
});
function rechercher(){
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'precommande.php?aff'})
		clearForm('formRechF',0);
	}
function control(index)
{

 alert( "Handler for .blur() called." + $( ".textQte" ).attr("name") + index);

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
	
	$("#mt").on('blur', function(e){
		
		if(deciNumber > 0){
			var value = this.value;
			
			var noDec = "";
			for(var i = 0; i < deciNumber; i++)
			noDec += "0";
			
			if(value == "0"+deciSep+noDec)
			this.value = ""; //<-- put your default value here
			else
			if(value.length > 0){
				if(value.indexOf(deciSep) >= 0){
					var newLength = value.indexOf(deciSep)+deciNumber+1;
					if(value.length < newLength){
					while(value.length < newLength){ value = value+"0"; }
					this.value = value.substring(0,newLength);
					}
				}
				else this.value = value + deciSep + noDec;
			}
		}
	});
  }
});
</script>

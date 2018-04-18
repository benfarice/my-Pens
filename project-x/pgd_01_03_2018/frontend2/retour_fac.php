<?php
include("../php.fonctions.php");
require_once('../connexion.php');
include("fonctionCalcule.php");
session_start();
include("lang.php");

$Operateur=1;
$IdDepot=$_SESSION['IdDepot'];


if (isset($_GET['goAddRetour'])){

//parcourir($_POST);return;
$DateR=date("Y-m-d H:i:s");
$DateR = date_create(date("Y-m-d"));
$HeureR=date("H:i:s");
$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//parcourir($_POST['IdArticle']);return;

	/*
	$sql = "SELECT max(idRetour) FROM avoir_client 	 GROUP BY dm.idArticle,pa";
	$stmt2 = sqlsrv_query( $conn, $sql );
	if( $stmt2 === false ) {
		$error.="Erreur recupération NumRetour: ".sqlsrv_errors() . " <br/> ";
	}
	sqlsrv_fetch($stmt2) ;
//	echo $IdFiche;return;
	$NumRetour = sqlsrv_get_field( $stmt2, 0);
	$NumRetour=intval($NumRetour)+1;*/
	$reqS="";
	$paramsFonc= array();
	$NumAvoir= "AV".Increment_Chaine_F("NumAvoir","avoir_client","IdRetour",$conn,$reqS,$paramsFonc);
	// insertion avoir_client
		$reqInser2 = "INSERT INTO  avoir_client(DateR,HeureR,IdFacture,IdDepot,NumAvoir) 
					values (?,?,?,?,?)";
					$params2= array(
							$DateR,
							$HeureR,
							$_GET['idFacture'],
							$IdDepot,
							$NumAvoir
					
					) ;

			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout avoir facture ".$errors[0]['message'] . " <br/> ";
	
			}	
	
//---------------------------IDAvoir--------------------------------//
$sql = "SELECT max(IdRetour) as IdAvoir FROM avoir_client";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdAvoir : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdAvoir = sqlsrv_get_field( $stmt2, 0);		
	//parcourir($_POST['idArticle']);return;
 for( $i= 0 ; $i < count($_POST['idArticle']) ; $i++ )
{	

	//verifier si l'artcile est dega retourné	
				$sql = "						
						SELECT d.idRetour from detailavoirs d
						inner join avoir_client ac on ac.idRetour=d.idRetour
						where idFacture=? and idArticle=? and etatAvoir=0";						
				 $params = array($_GET['idFacture'],$_POST["idArticle"][$i]);//
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
					 $resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
					$nRes = sqlsrv_num_rows($resAff);//echo "resultat " . $nRes;
				//echo $nRes;
				if($nRes!=0)
				{ 
					sqlsrv_fetch($resAff) ;
					$idRetour = sqlsrv_get_field( $resAff, 0);	
						$reqDel ="delete from detailavoirs where idRetour=".$idRetour;
						$stmt1 = sqlsrv_query( $conn, $reqDel, array() );
						if( $stmt1 === false ) {
							$errors = sqlsrv_errors();
							$error.="Erreur : Suppression retour article ".$errors[0]['message'] . " <br/> ";
							
						}	
				}
				$QteChargeBoxEnpcs=BoxToPcs($_POST["QteChargeeBoxs"][$i],$_POST["Colisage"][$i]);
				//echo $QteChargeBoxEnpcs;return;
				$QteRetourBoxEnpcs=BoxToPcs($_POST["qtRetourBoxs"][$i],$_POST["Colisage"][$i]);
				
				$EncienQte=$QteChargeBoxEnpcs+intval($_POST["QteChargeePcs"][$i]);
				$QteRetour=$QteRetourBoxEnpcs+intval($_POST["qtRetourPcs"][$i]);
				
	
			
				$reqInser2 = "INSERT INTO  detailavoirs([IdRetour],IdArticle,[EncienQte],QteRetour) 
					values (?,?,?,?)";
					$params2= array(
							$IdAvoir,
							$_POST["idArticle"][$i],
							floatval(str_replace(" ","",$EncienQte)),//é/ qte cmd est stockée par unité (box,palette piece)
							floatval(str_replace(" ","",$QteRetour))
						
					
					) ;

			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout detail retour  ".$errors[0]['message'] . " <br/> ";
				break ;
			}	
		
		/*	if(	floatval(str_replace(" ","",$_POST["qtRetourPcs"][$i]))!=0){
			
				$reqInser2 = "INSERT INTO  detailavoirs([IdRetour],IdArticle,[EncienQte],QteRetour,UniteVente) 
					values (?,?,?,?,?)";
					$params2= array(
							$IdAvoir,
							$_POST["idArticle"][$i],
							floatval(str_replace(" ","",$_POST["QteChargeePcs"][$i])),//é/ qte cmd est stockée par unité (box,palette piece)
							floatval(str_replace(" ","",$_POST["qtRetourPcs"][$i])),
							'Pièce'
					
					) ;

			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout detail retour  ".$errors[0]['message'] . " <br/> ";
				break ;
			}	
		}*/
}

if( ($error=="" ) ) {	
	sqlsrv_commit( $conn );
	    ?>
		<script type="text/javascript"> 
			alert('<?php echo $trad['msg']['messageAjoutSucces'];?>');
			$('#boxArticle').dialog('close');				
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

<div id="result"></div>
<?php 
/*********** selectionner la cmd d'un vendeur**********************/
$sql = "
		sELECT f.idVendeur,f.idFacture,d.idArticle as IdArticle,a.Reference,a.designation as NomArt,c.colisagee Colisage,
		max (CASE WHEN UniteVente= 'Pièce' THEN Qte ELSE 0 END) AS QtePcs,
		  max(CASE WHEN UniteVente= 'Colisage' THEN Qte ELSE 0 END) AS QteBox
from detailFactures d 
			inner join factures f on f.idFacture=d.idFacture
			inner join articles a on a.idArticle=d.idArticle
			inner join colisages c on c.idArticle=a.idArticle
	WHERE f.idFacture=? 
	GROUP BY f.idVendeur,f.idFacture,d.idArticle ,a.Reference,a.designation ,c.colisagee";
/*$sql = "
			SELECT 	 f.idVendeur,IddetailFacture,f.idFacture,d.idArticle as IdArticle,a.designation as NomArt,d.qte as Qte,
			d.UniteVente,c.colisagee Colisage,d.tarif Tarif,a.Reference
			from detailFactures d 
			inner join factures f on f.idFacture=d.idFacture
			inner join articles a on a.idArticle=d.idArticle
			inner join colisages c on c.idArticle=a.idArticle
			WHERE f.idFacture=? order by d.idArticle";
	*/
	 $params = array($_GET['numCmd']);//
//echo $sql. ' ' .$_GET['numCmd'];return;
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	 $resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
	$nRes = sqlsrv_num_rows($resAff);//echo "resultat " . $nRes;

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
			<div  class="divArticleL"  style="width:295px" ><?php echo $trad['label']['Article'];?> </div>
			<div  class=" divQteL" style="width:67px"><?php echo $trad['label']['Colisage'];?>  </div>
			<div  class="divTTC" style="width:138px; text-align:center" ><?php echo $trad['label']['QteCmd'];?>  </div>	
			<div  class="divArticleL" style="width:180px" ><?php echo $trad['label']['QteRetour'];?>  </div>				
	</div>
	<div style="height:400px;overflow:scroll;" ><!--height:585px;-->
	<form id="formAdd" method="post" name="formAdd"> 

<?php 
$k=0;$i=0;
$idVnd="";$idCmd="";$UniteVente="";
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$idCmd=$row['idFacture'];
$k++;
if($k%2 == 0) $c = "pair";
else $c="impair";
			//$qteDispo=number_format($qteDispo,0," "," ")
	//		if($row['UniteVente']=="Colisage") $UniteVente="Boite";else $UniteVente="Pièce";
			
?>
<input type="hidden" name="QteChargeeBoxs[<?php echo $i; ?>]" value="<?php  echo $row['QteBox'] ?>" id="QteChargeeBox<?php echo $i; ?>" >
<input type="hidden" name="QteChargeePcs[<?php echo $i; ?>]" value="<?php  echo $row['QtePcs'] ?>" id="QteChargeePcs<?php echo $i; ?>" >
<input type="hidden" value="<?php  echo $idCmd ?>" id="idCmd" name="idCmd">
<input type="hidden" value="<?php echo $row['Colisage']; ?>" name="Colisage[<?php echo $i; ?>]" id="Colisage<?php echo $i; ?>"/>
<input type="hidden" value="<?php  echo $row['IdArticle']; ?>" name="idArticle[<?php echo $i; ?>]">
	<div  class="<?php echo $c; ?>" >
			<div align="left" class="divArticleLigne" style="width:321px"  ><?php echo $row['NomArt'];?> </div>
					<div align="left" class="divQteL" style="width:75px"  ><?php echo $row['Colisage'];?> </div>
			<div  align="right" class="divQteL" style="width:148px" ><?php				
			echo $row['QteBox']." ".$trad['label']['Boxs']." ".$row['QtePcs']." ".$trad['label']['NbrPiece'];?>  </div>			
			<div  align="right" class="divArticleLigne" style="width:380px" >	
				<input class="numberOnly" type="text" style="width:100px" value="0" 
				size="5" name="qtRetourBoxs[]" onkeypress="return isEntier(event) " />&nbsp;<?php echo $trad['label']['Boxs'];?>
				
					<input class="numberOnly" type="text" style="width:100px" value="0" 
				size="5" name="qtRetourPcs[]" onkeypress="return isEntier(event) " />&nbsp;<?php echo $trad['label']['NbrPiece'];?>
				
			</div>	
		</div>


<?php 
	$i++;
 }  ?>

</form>
	</div>
</div>

<div class="btnV" style="margin:10px 10px 0 0">
	<input type="button" value="<?php echo $trad['button']['Enregistrer'];?>" class="btn" onclick="Valider(<?php echo $idCmd; ?>)"/>
	<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="Fermer('boxArticle')"/>
</div>
<?php 

}
?>
<script language="javascript" type="text/javascript">

function Valider(IdCmd){

	/******Control Qte Retour********/
	index=0;
	var test=true;
	
	//QteAchargé obligatoire
	/*$("[name^=qtRetour]").each(function () {
		if($(this).val() == "")
		{
		  $(this).css('border', '1px solid red');
		  test=false;
		 
		}
		else
		{
		  $(this).css('border', '1px solid black');
		}
		});*/
		
		if (test == false)
		return;
		
		//QteAchargé ne doit pas dépasser Qte Disponible
		$("[name^=qtRetour]").each(function () {
			//	alert($(this).val());//--------------------------------------Retour
				//alert($("input[name=QteChargee"+index+"]").val());//---------------QteChargee
			
				if(parseInt($(this).val()) > parseInt($("input[id=QteChargee"+index+"]").val())){	
					jAlert("<?php echo $trad['msg']['VerifQteRetour'];?>","<?php echo $trad['titre']['Alert'];?>");
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
									url				:	'retour_fac.php?goAddRetour&idFacture='+IdCmd,
									method			:	'post'
							}); 
							return false;
			           }			
		})
	}
}
</script>
<?php
exit;
} 

if (isset($_GET['FacClt'])){
?>
<div class="title"><?php echo $trad['label']['Client']." : ". $_GET['nomClt'];?></div>
<?php
/*********** selectionner les fac d'un clt**********************/
//EtatAvoir=1  retour facture valider par superviseur
$sql = "
		SELECT f.IdFacture IdFacture,NumFacture numCommande,v.nom+ ' ' + v.prenom AS nom,f.[date] as datec,EtatCmd 
		,f.idClient IdClient,f.idVendeur,totalTTC FROM
				factures f 
			INNER JOIN vendeurs v ON f.idVendeur=v.idVendeur
			
			where v.idDepot=?  and f.idClient=? and EtatCmd=2
			and (idFacture not in( select idFacture from avoir_client ) or 
					idFacture  in( select idFacture from avoir_client where etatAvoir=0 ) ) 
			order by IdFacture desc";
	
	 $params = array($_SESSION['IdDepot'],$_GET['idClient']);
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

<DIV class="ListeCmd">
	<div class="enteteL" >
			<div  class="divArticleL"   style="width:190px"><?php echo $trad['label']['Facture'];?> </div>
			<div  class="divArticleL"  style="width:200px"><?php echo $trad['label']['DateFacture'];?>  </div>				
			<div  class="divArticleL" style="width:200px; text-align:center" ><?php echo $trad['label']['ca']. " ( " . $trad['label']['riyal'] . " )";?>  </div>	
				<div  class="divArticleL" style="width:200px;text-align:center;">  </div>
			
	</div>
	<div style="max-height:320px;overflow:scroll;height:320px" ><!--height:585px;-->
<?php 
$k=0;
while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
$k++;
if($row['EtatCmd'] == 0) $c = "pair";
else $c="impair";
?>
		<div class=" <?php echo $c;?>"
		onclick="getcommande('<?php  echo $row['IdFacture'];?>','<?php  echo $row['EtatCmd'];?>','<?php  echo $row['IdClient'];?>',
		'<?php  echo $row['idVendeur'];?>')">
			<div  class="divArticleL"   style="width:213px" ><?php echo $row['numCommande'];?> </div>
			<div  class="divArticleL"  style="width:212px;"  ><?php 
				$newdate = date('d/m/Y', strtotime($row['datec']));echo $newdate; 
				?>  </div>			
			<div  class="divArticleL"  style="width:220px;text-align:right;" ><?php 
		echo 	number_format($row['totalTTC'], 2, '.', ' ') ;?> 
		</div>	
			<div  class="divArticleL" style="width:240px; padding: 6px 5px;TEXT-align:center;  BORDER:None;">
							<input type="button" value="<?php echo $trad['frais']['details'] ; ?>" class="btn"
							onclick='getcommande(<?php echo $row['IdFacture'];?>)' />
			</div>	
</div>


<?php }  ?>
	</div>
</div>
<div class="btnV" style="margin:10px 10px 0 0;">
	<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="Fermer('box')"/>	
</div>
<?php 
}?>
<script>
function getcommande(numCmd){
			//var url='retour_fac.php?getCmd&&numCmd='+numCmd+'&&IdClient='+IdClient+'&&IdVdr='+IdVdr;	
			var url='retour_fac.php?getCommande&&numCmd='+numCmd;	
			$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
}
</script>
<?php
exit;
}
if (isset($_GET['ListClt'])){
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
	 <div id="formRech" style="background:#fff;width:80%; text-align:center"  >	  
 <form id="formRechF" method="post" name="formRechF" > 

  
			<?php echo $trad['map']['intitule'];?><input class="form-control"  id="Intitule"name="Intitule" type="text" size="30"/>	
											
			&nbsp;<input type="button" value="<?php //echo $trad['button']['rechercher'];?>" class=" btn-primary"  id="rech" action="rech" 
			onclick="rechercher()" style="border:none;padding:7px 30px 5px 30px;" />	
		
	</form>
</div>	
				<div id="listClt  " class="ListeCmd"  style="BACKGround:#fff;"  >		</div>

<script language="javascript" type="text/javascript">
$(document).ready(function() {	
	rechercher();
});
function rechercher(){
		$('#formRechF').ajaxSubmit({
		target:'.ListeCmd',
		url:'retour_fac.php?RechClt',
		method:	'post'
	})
}
 
</script>
	<?php


exit;


}
 
if(isset($_GET['RechClt'])){	

$sql = "
  SELECT   
	intitule, c.IdClient,c.nom,c.prenom,c.adresse,c.longitude,c.latitude ,a.DsgActivite ,a.icone  FROM clients c 
	INNER JOIN  activites a ON c.IdActivite=a.IdActivite 
	where c.idVendeur=".$_SESSION['IdVendeur'] . "  and idClient in (SELECT idClient from factures where EtatCmd=2)
";


if(isset($_POST['Intitule']) && ($_POST['Intitule']!='') )
	{	$sql .=" AND c.intitule  COLLATE Latin1_general_CI_AI Like '%".($_POST['Intitule'])."%' COLLATE Latin1_general_CI_AI " ;
	   $params = array();
	}
//	echo $sql;//return;
$params = array();	
$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt === false ) 
	{
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
$nRes = sqlsrv_num_rows($stmt);	//echo $nRes;
?>
<?php
if($nRes==0)
{ ?>
	<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
		<br><br><br><br>
			<?php echo $trad['msg']['AucunResultat'];?>
	</div>
<?php
return;
}
	?>		<div class="enteteL" style=""  >
			<div  class="divArticleL"  style="width:290px ;text-align:center;"><?php echo $trad['label']['Client'];?> </div>
			<div  class="divArticleL" style="width:382px;text-align:center;"><?php echo $trad['map']['adresse'];?> </div>	
			<div  class="divArticleL" style="width:220px;text-align:center;">  </div>	
			</div>		<DIV class="clear"></div>
				<div style="height:440px;overflow:scroll;border-bottom:1px solid #ebebeb" ><!--height:585px;-->
					<?php
					$k=0;
					while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
					$k++;
					?><div 	class="" style="border-bottom:1px solid #ebebeb;"  >					
								<div  class="divArticleL" style="width:300px;   BORDER:None;  padding: 6px 5px;"  ><?php  echo ucfirst($row['intitule']);?> </div>
								<div  class="divArticleL" style="width:382px; padding: 6px 5px; BORDER:None;"><?php echo ucfirst(stripslashes($row['adresse']));?> </div>	
								<div  class="divArticleL" style="width:140px; padding: 6px 5px;  BORDER:None;">
							<input type="button" value="<?php echo $trad['label']['ListeFactures'] ; ?>" class="btn"
							onclick='FacClient(<?php echo $row['IdClient'];?>,"<?php echo $row['intitule'];?>")' />
								</div>	
					</div>
					<DIV class="clear"></div>

					<?php }  ?>
						</div>
				

<?php

	exit;
}
?>
<?php include("header.php"); ?>


<div style=" display:flex;align-items:center; padding:2px 0;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
						<div >&nbsp;> <span  Class="TitleHead" onclick=""><?php echo $trad['label']['RetourFacture'];?></span></div> 
</div>

<div style="clear:both;"></div>

<div id="formRes"></div><!--style="overflow-y:scroll;min-height:280px;"--> 

<?php
include("footer.php");
?>
<div id="box"> </div>
<div id="boxArticle"></div>
<div id="boxSearchArticle"></div>
<script language="javascript" type="text/javascript">


$(document).ready(function() {
		//$.validator.messages.required = '';
  		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('retour_fac.php?ListClt');
			$('#box').dialog({
					autoOpen		:	false,
					width			:	820,/*1100*/
					height			:	500,
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
						"Terminer "	: function() {
							terminer();
						
						}
					 }
			});		
			$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	950,/*1100*/
					height			:	600,
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
						"Terminer "	: function() {
							terminer();
						
						}
					 }
			});
			
});
function rechercher(){
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'retour_fac.php?aff'})
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
									url				:	'retour_fac.php?goupdate',
									method			:	'post'
							}); 
							return false;
			           }
			
		})
	}
}
function Fermer(box){
	$("#"+box).dialog('close');
}
 function FacClient(idClient,nomClt){
	var url="retour_fac.php?FacClt&idClient="+idClient+"&nomClt="+encodeURI(nomClt);

	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
 }
</script>

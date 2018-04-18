<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();
include("lang.php");
?>
<?php
if (isset($_GET['goupdate'])){
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
//	echo $_POST['colisage'][$i];return;
	$stock=(intval($_POST['Qcharge'][$i]) - intval($_POST['qtech'][$i]))* $_POST['colisage'][$i];
	if(in_array($idArticle,$articleVnd))
	{	
	echo "update <br/>";
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
?>

<?php
if (isset($_GET['aff'])){

//if(!isset($_SESSION['IdVendeur'])){echo "ddddddddddddddd";
$sql = "select c.IdChargement,IdDetailChargement,a.IdArticle,a.Designation,( dc.qte) AS qtecharge, col.colisagee idColisage from chargements c 
INNER JOIN detailChargements dc ON c.IdChargement=dc.IdChargement
INNER JOIN articles a ON dc.idArticle=a.IdArticle
inner join colisages  col on col.idArticle=a.idArticle
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
{ ?>
	<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
		<br><br><br><br>
		<?php echo $trad['msg']['AucunResultat'];?>
			<script type="text/javascript"> 
					document.location.href="map.php";
		</script>
	</div>
<?php
return;
}
else
{	
?>
					<DIV class="entete">
						<div class="divArticle" Style="width:353px;font-size:23px; vertical-align:middle" valign="middle" align="center">
						<?php echo $trad['label']['Article'];?> </div>
						<div class="divPV"  Style="width:132px;font-size:23px" align="center"><?php echo $trad['label']['Colisage'];?></div>					
						<div class="divPV"  Style="width:132px;font-size:23px" align="center"><?php echo $trad['label']['qtecharge'];?> </div>
						<div class="divPV" Style="width:132px;font-size:23px" align="center"><?php echo $trad['label']['Retour'];?>  </div>
						<div class="divPV" Style="width:360px;font-size:23px" align="center"><?php echo $trad['label']['Motif'];?>  </div>						
					</DIV>
<div  style="overflow-y:scroll;min-height:480px;max-height:480px;">
<form id="formAdd" method="post" name="formAdd"> 
<div id="result" style=""></div>
<?php $i=0;
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){		
?>					
<div class="ligne" ><!--Style="height:120px;"-->
									<div class="divArticle" Style="width:268px;height:48px;font-size:26px;"  align="center">
									<?php  echo wordwrap($row['Designation'], 28, "<br />\n", true); ?>
									</div>
									<div class="divtxt"  Style="width:114px;font-size:23px;border:1px solid GREEN;"> 
										<span style="margin-right:5px;"><?php  echo $row['idColisage'];?></span>
									</div> 
									<div class="divtxt"  Style="width:114px;font-size:23px;border:1px solid red;"> 
										<span style="margin-right:5px;"><?php  echo  $row['qtecharge'];?>
									</div> 
									
									<div class="divinput" Style="width:114px;height:84px;" > 
										<input class="textQte" type="text" value="0" onkeypress="return isEntier(event) " size="5" name="qtech[<?php echo $i ; ?>]"  /> 
									</div> 		
									<div class="divinput" Style="width:295px;height:80px;"  >
									 <textarea  class="text" type="text" name="motif[<?php echo $i ; ?>]"  ></textarea>
									</div>
									<input type="hidden" value="<?php  echo $row['IdDetailChargement']; ?>" name="idDetail[<?php echo $i ; ?>]">
									<input type="hidden" value="<?php  echo $row['idColisage']; ?>" name="colisage[<?php echo $i ; ?>]">	
									<input type="hidden" value="<?php  echo $row['IdArticle']; ?>" name="IdArticle[<?php echo $i ; ?>]">
									<input type="hidden" value="<?php  echo $row['IdChargement']; ?>" name="Idchargement">	
									<input type="hidden" val="stock<?php echo $i ; ?>" value="<?php  echo $row['qtecharge']; ?>" name="Qcharge[<?php echo $i ; ?>]">									
</div>	
<?php	
$i++;
}
?>
</form>
</div>

	<div class="ValideCg" >
		<input type="button" value="<?php echo $trad['button']['Enregistrer'];?>" class="btn" 
		onclick="Terminer()"/>
	</div>

<?php	
}

exit;
}
//}
?>
<?php include("header.php"); ?>


<div style=" display:flex;align-items:center; padding:2px 0;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
						<div >&nbsp;> <span  Class="TitleHead" onclick=""><?php echo $trad['Menu']['ChargementVendeur'];?></span></div> 
</div>

<div style="clear:both;"></div>

<div id="formRes"></div><!--style="overflow-y:scroll;min-height:280px;"--> 

<?php
include("footer.php");
?>

<script language="javascript" type="text/javascript">

$(document).ready(function() {
		//$.validator.messages.required = '';
  		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('chargementVendeur.php?aff');
});
function rechercher(){
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'chargementVendeur.php?aff'})
		clearForm('formRechF',0);
	}
function control(index)
{

 alert( "Handler for .blur() called." + $( ".textQte" ).attr("name") + index);

}
/*$( ".text" ).blur(function() {
	
 alert( "Handler for .blur() called." );//+ $( "#qtech" ).attr("name")
  
});	*/

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
									url				:	'chargementVendeur.php?goupdate',
									method			:	'post'
							}); 
							return false;
			           }
			
		})
	}
}
</script>

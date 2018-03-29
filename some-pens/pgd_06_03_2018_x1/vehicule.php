<?php	  
include("php.fonctions.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "vehicules";
$cleTable = "idVehicule";
$IdDepot=$_SESSION['IdDepot'];
$nom_sansDoublons = "Numéro d\'immatriculation";
if(isset($_GET['delPlusieursArticle'])){
	$sup = true;
	$ligneSelect = explode(",",$_POST['CLETABLE']);
	foreach($ligneSelect as $a=>$ligne){
		if($ligne!=0){
			$data = explode(",",$ligne);
			$idp = $data[0];
			
			$sqlReq = "update $tableInser set etatSup=0 where $cleTable = ?";
			$params1= array($idp) ;

			$stmt1 = sqlsrv_query( $conn, $sqlReq, $params1 );
			if( $stmt1 === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
				$sup=false;
			}

		}
	}
	if($sup == true){
		?><script language="javascript" > 
				//alert('picesphp');
				alert('Supression de la sélection effectuée.'); 
				rechercher();
		  </script>
		  <?php
	}else{
		?><script language="javascript" > alert('Un ou plusieurs elements de la selection n\'ont pas pu etre supprimes.'); </script><?php
	}

exit;
mysql_close();
}
if(isset($_GET['goMod'])){

/*function modif(){*/

$sansDoublons = "Immatriculation";

$nom_sansDoublons = "Numéro d\'immatriculation";
	//on verif si codeF existe deja
			$reqModif = "UPDATE $tableInser SET Immatriculation='".addslashes(mb_strtolower(securite_bdd($_POST['Immatriculation'])))."',";
			$reqModif .=  " Designation='".addslashes(mb_strtolower(securite_bdd($_POST['Designation'])))."'";
			$reqModif .= " WHERE $cleTable='".$_POST[$cleTable]."' ";

				$reqVerif = "select * from $tableInser where etat=1 and $sansDoublons='".mysql_real_escape_string($_POST[$sansDoublons])."' AND $cleTable != '".$_POST[$cleTable]."'";
			mysql_query($reqVerif)or die(mysql_error().$reqVerif);
	if( mysql_query($reqVerif)){  
				if(mysql_num_rows(mysql_query($reqVerif))==0){ //new
						$resModif = mysql_query($reqModif);
					
						
							if($resModif){
							?>
							
							<script type="text/javascript"> 
							//alertK('L\'ajout a été effectué.',1);
							alert('La modification a été effectué.');
									$('#boxClient').dialog('close');
									rechercher();
							</script>
						
							<?php
							
						}else {echo mysql_error(); 
					
						?>
								<script language="javascript">
								//alertK("erreur lors de l'ajout. Contacter l'administrateur",0);
								alert("erreur lors de la modification. Contacter l'administrateur.__<?php echo  mysql_error(); ?>");
								//$('#boxClient').dialog('close');
								</script>
						<?php }
					}else{ //sansDoublons existe
						?><script language="javascript">
									//alertK('La <?php echo $nom_sansDoublons; ?> choisie existe déjà.\nMerci d\'en choisir une autre. ',0);
									alert('Le <?php echo $nom_sansDoublons; ?> choisi existe déjà.\nMerci d\'en choisir un autre. ');
									//$('#boxClient').dialog('close');
							</script>
	<?php				}
	}

exit;
	
}
if(isset($_GET['goAdd'])){

		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	  
/**********************Controle doublon****CIN************************/
$sql = "SELECT * FROM ".$tableInser." WHERE immatriculation=? ";
$param= array($_POST['Immatriculation']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql ,$param,$options);
if( $stmt === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	echo $error;
	return;
}
$count = sqlsrv_num_rows($stmt);
$IdAffectation ="";
	if($count >0)//*****************Already Exist Cin
	{
	?>
				<script type="text/javascript"> 
					alert('<?php echo $trad['msg']['immatriculationexist']; ?>');
				</script>
	<?php
	return;
	}
$reqInser1 = "INSERT INTO ".$tableInser." (immatriculation,Designation,idTypeVehicule,[idDepot],DateMiseService,
EcheanceVisiteTechnique,EcheanceVidange,EcheanceAssurance ) values 	(?,?,?,?,?,?,?,?)";

		$params1= array(
		addslashes(mb_strtolower(securite_bdd($_POST['Immatriculation']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Designation']), 'UTF-8')),
		$_POST['TVehicule'],
		$_SESSION['IdDepot'],
		$_POST['DateMS'],
		$_POST['DateVT'],
		$_POST['DateVidange'],
		$_POST['DateAssurance']
		) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					alert('<?php echo $trad['msg']['messageAjoutSucces']; ?>');
					$('#boxClient').dialog('close');
					rechercher();
				</script>
		<?php
		} else {
			 sqlsrv_rollback( $conn );
			 echo "<font style='color:red'>".$error."</font>";
		}
		/********************************************************/	

exit;
}

if (isset($_GET['mod'])){	
	$ID = $_GET['ID'] ;
	$sql = "SELECT immatriculation,Designation,idTypeVehicule,idDepot,DateMiseService,EcheanceVisiteTechnique,
	EcheanceVidange,EcheanceAssurance from ".$tableInser ." where IdVehicule = '$ID' ";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	//ECHO $sql;return;
	$stmt = sqlsrv_query($conn,$sql,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	$row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
	//execSQL($sql);
	//echo $sql; return;
?>
	<div id="resMod" style="padding:5px;">&nbsp;</div>
	<form id="formMod" action="NULL" method="post" name="formAdd1"> 		
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="10">
        <tr>
        	<td>
			 <div class="etiqForm" id="" ><strong>Immatriculation  </strong> : </div>
            </td>
            <td>
    <input class="FormAdd1" type="text" name="Immatriculation" value="<?php echo $row['immatriculation'] ; ?>" id="Immatriculation" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong>Désignation</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text"  value="<?php echo $row['Designation'];?>" name="Designation" id="Designation" size="30" tabindex="1"  />
            </td>
		</tr>

		 <tr>
		 <td><div class="etiqForm" id="" > <strong>Type Véhicule </strong> : </div>
            </td>
            <td>
        	<select  name="TVehicule" id="TVehicule"  multiple="multiple" tabindex="3" class="Select TVehicule" style="display:visible;width:220px;">
		
                         <?php $sql = "SELECT tv.idTypeVehicule,tv.Designation FROM typeVehicules tv";
							$reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idTypeVehicule'] ?>"><?php echo $donnees['Designation'];?></option>
                         <?php
                          }
                         ?>
						   
			</select>
            </td>
			<td>
			 <div class="etiqForm" id="" ><strong>Date de mise en service  </strong> : </div>
            </td>	
			  <td>
            <input class="FormAdd1" type="text" name="DateMS" maxlength="10" id="DateMS" onChange="verifier_date(this);" size="30" tabindex="1" value="<?php echo $row['DateMiseService']; ?>" />
            </td>
          </tr>
		<tr>
			<td>
			 <div class="etiqForm" id="" ><strong>Echéance visite technique </strong> : </div>
            </td>	
			  <td>
            <input class="FormAdd1" type="text" name="DateVT" maxlength="10" onChange="verifier_date(this);"  id="DateVT" size="30" tabindex="1"  value="<?php echo $row['EcheanceVisiteTechnique']; ?>"  />
            </td>
			<td>
			 <div class="etiqForm" id="" ><strong>Echéance vidange</strong> : </div>
            </td>	
			  <td>
            <input class="FormAdd1" type="text" name="DateVidange" maxlength="10" onChange="verifier_date(this);"  id="DateVidange" value="<?php echo $row['EcheanceVidange']; ?>"  size="30" tabindex="1"  />
            </td>
		</tr>
		<tr>
			<td>
			 <div class="etiqForm" id="" ><strong>Echéance assurance</strong> : </div>
            </td>	
			  <td>
            <input class="FormAdd1" type="text" name="DateAssurance" maxlength="10" onChange="verifier_date(this);" id="DateAssurance"  value="<?php echo $row['EcheanceAssurance']; ?>"   size="30" tabindex="1"  />
            </td>

		</tr>
     	<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
<script language="javascript" type="text/javascript">
$('#TVehicule').multipleSelect({
		filter: true,placeholder:'<?php echo $trad['map']['selectType'] ; ?>',single:true,maxHeight: 100
});

$(document).ready(function(){
		calendrier("DateMS");
		calendrier("DateVT");
		calendrier("DateVidange");
		calendrier("DateAssurance");
	 $('body').on('change', '#TVehicule', function() {
	 			var TVehicule =$('#TVehicule').val();
				if(TVehicule!="") {
					$('div.TVehicule').removeClass('erroer');
					$('div.TVehicule button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
	
 $("#TVehicule").multipleSelect("setSelects",[ <?php echo $row['idTypeVehicule'] ; ?>]);
});
</script> <?php 
exit;
}

if (isset($_GET['add'])){
?>

<script language="javascript" type="text/javascript">
$('#TVehicule').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectType'] ; ?>',single:true,maxHeight: 100
});
$(document).ready(function(){
		calendrier("DateMS");
		calendrier("DateVT");
		calendrier("DateVidange");
		calendrier("DateAssurance");
	 $('body').on('change', '#TVehicule', function() {
	 			var TVehicule =$('#TVehicule').val();
				if(TVehicule!="") {
					$('div.TVehicule').removeClass('erroer');
					$('div.TVehicule button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
});
</script>
<div id="resAdd" style="padding:5px;">&nbsp;</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="10">
        <tr>
        	<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['frais']['immatriculation']; ?>  </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Immatriculation"  id="Immatriculation" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['Dsg']; ?> </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Designation"  id="Designation" size="30" tabindex="1"  />
            </td>
		</tr>

		 <tr>
		 <td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['typeVehicule']; ?></strong> : </div>
            </td>
            <td>
        	<select  name="TVehicule" id="TVehicule"  multiple="multiple" tabindex="3" class="Select TVehicule" style="display:visible;width:220px;">
		
                         <?php $sql = "SELECT tv.idTypeVehicule,tv.Designation FROM typeVehicules tv";
							$reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option style="text-align:right;" value="<?php echo $donnees['idTypeVehicule'] ?>"><?php echo $donnees['Designation']?></option>
                         <?php
                          }
                         ?>
						   
			</select>
            </td>
			<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['dateMiseService']; ?></strong> : </div>
            </td>	
			  <td>
            <input class="FormAdd1" type="text" name="DateMS" maxlength="10" id="DateMS" onChange="verifier_date(this);" size="30" tabindex="1"  />
            </td>
          </tr>
		<tr>
			<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['echeanceVisiteTech']; ?></strong> : </div>
            </td>	
			  <td>
            <input class="FormAdd1" type="text" name="DateVT" maxlength="10" onChange="verifier_date(this);"  id="DateVT" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['echeanceVidange']; ?></strong> : </div>
            </td>	
			  <td>
            <input class="FormAdd1" type="text" name="DateVidange" maxlength="10" onChange="verifier_date(this);"  id="DateVidange" size="30" tabindex="1"  />
            </td>
		</tr>
		<tr>
			<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['echeanceAssurance']; ?></strong> : </div>
            </td>	
			  <td>
            <input class="FormAdd1" type="text" name="DateAssurance" maxlength="10" onChange="verifier_date(this);" id="DateAssurance" size="30" tabindex="1"  />
            </td>

		</tr>
     	<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
	
	<!-- Styles Js -->
	
	
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){

$sqlA = "SELECT v.idVehicule, immatriculation,v.Designation as vehicule,tv.Designation as TypeVehicule FROM vehicules v INNER JOIN typeVehicules tv ON tv.idTypeVehicule = v.idTypeVehicule";
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	if(isset($_POST['Immatriculation']) && ($_POST['Immatriculation']!='') )
	{	$sqlA .=" where immatriculation like ? " ;
	   $params = array("%".$_POST['Immatriculation']."%");
	}
	//ECHO $sqlA."<br>";
	$stmt=sqlsrv_query($conn,$sqlA,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes."<br>";
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "idVehicule";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "DESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = " ORDER BY $cTri $oTri ";//LIMIT $min,$max ";
	$sql = $sqlA.$sqlC;
	//echo $sql;

/*execSQL($sql);*/
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
	
	$nRes = sqlsrv_num_rows($resAff);
	$nPages = ceil($ntRes / $npp);
	$selPages = '<select name="pact" onChange="filtrer();">';
	for($i=1;$i<=$nPages;$i++){
		if($i==$pact) $s='selected="selected"';
		else $s='';
		$selPages.= '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
	}
	$selPages.= '</select>';
	
	/*	$resAff = mysql_query($reqAff)or die(mysql_error());*/
		if($nRes==0)
		{ ?>
					<div class="resAff">
						<br><br>
						<?php echo $trad['msg']['AucunResultat']; ?>
					</div>
					<?php
		}
else
{
	?>
<script language="javascript" type="text/javascript">
$('#cont_pages').html('<?php echo $selPages; ?>');
</script>
		<form id="formSelec" method="post">
	<table width="100%" border="0">
      <tr class="entete">
		<td width="30%"><?php echo $trad['frais']['immatriculation']; ?></td>	  
        <td width="30%"><?php echo $trad['frais']['vehicule'] ; ?></td>
        <td width="30%"><?php echo $trad['label']['typeVehicule'] ; ?></td>

        <td width="6%" colspan="2" style="display:none;">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<!--input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" /-->
		</td>
  </tr>
<!--<div id="cList">-->
	<?php
		$i=0;
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $c; ?>">
				<td align="<?php echo $_SESSION['align'] ; ?>"  > <?php echo $row['immatriculation']; ?> </td>			
				<td align="<?php echo $_SESSION['align'] ; ?>"  > <?php echo $row['vehicule']; ?> </td>
				<td align="<?php echo $_SESSION['align'] ; ?>" > <?php 	echo $row['TypeVehicule'];?> </td>
				<td align="center" style="display:none;" >
					<span class="boutons"> 
						<input type="button" title="Modifier" action="mod" class="b" onClick="modifier('<?php echo $row['idVehicule']; ?>');" />  
					</span>
			  </td>			
			  <td colspan="2" align="center" style="display:none">
				<input type="checkbox" class="checkLigne" name="<?php echo $row['idVehicule']; ?>" value="<?php echo $row['idVehicule']; ?>" />
			  </td>
			  </tr>
			 <?php
			$i++;
		}
		
	?>	
    </table>
	<!--</div>-->
    </form>
    <?php
}
?>
<script language="javascript" type="text/javascript">
		$(document).ready(function(){
			$('input[title]').qtip({
				
				style		: {		classes	: 'ui-tooltip-rounded ui-tooltip-shadow'	},
				position	: {
					my : 'bottom center',
					at	: 'top center'
				},
				show		: {
					effect	: function(offset) {	$(this).show('bounce', null, 10);	}
						
				}   		  
			});
				
		});
	
		function actionSelect(){
				var idSelect = '0';
				var n = 0;
				$(".checkLigne:checked").each(function(){
						n++;
						idSelect +=","+$(this).attr("name");
						//alert($(this).attr("name"));
				});
				if(n>0){
				
					jConfirm('Confirmer la suppression ?', null, function(r) {
						if(r)	{
							$('input#CLETABLE').attr("value",idSelect);
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'vehicule.php?delPlusieursArticle',clearForm:false});		
						}
					});
				}			
		}	
	</script>
<?php
exit;
}
include("header.php");
?>
<div class="contenuBack">
<div id="brouillon" style="display:block">  </div> 
<div id="infosGPS" style="border-bottom:1px dashed #778; ">
<div style="display:inline-block">&nbsp;<?php echo $trad['Menu']['parametrage']; ?>&nbsp;<img src="images/tri.png" /></div>
<div style="display:inline-block">&nbsp;<?php echo $trad['frais']['vehicule']; ?>&nbsp;</div>
	</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="101%" border="0" align="center" >
				  <tr>
					<td width="23%" valign="middle">
					<div class="etiqForm" id="SYMBT" ><?php echo $trad['frais']['immatriculation']; ?>: </div>				</td>
					<td width="30%">
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					<div align="<?php echo $_SESSION['align']; ?>">
				<input class="formTop"  name="Immatriculation" id="Immatriculation" type="text" size="30" />	
				</div>
										</td>
				  <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button" onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech"  title="<?php echo $trad['button']['rechercher']; ?> " />
					  <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider']; ?>" class="bouton32" action="effacer" title="<?php echo $trad['label']['vider']; ?>"/></span><br/></td>
				  <td width="25%" rowspan="2"   style="border-<?php echo $_SESSION['align']; ?>:1px solid #778;"><span class="actionForm">
					<input name="button3" type="button" title="<?php echo $trad['button']['ajouter']; ?> " class="bouton32" onClick="ajouter();" value="<?php echo $trad['button']['ajouter']; ?>" action="ajout" />
				  </span></td>	
				</tr>			  
			 </table>
			 
		 </div>
		<!--div id="formFiltre" style="">
		<table border="0"  width="100%">
			<tr height="20">
			  <!--td width="23%">
			  <div id="filtreNPP">
			  	R&eacute;sultats par page : <select name="npp" id="npp" onChange="filtrer();">
					<option value="10">10</option>
					<option value="20" >20</option>
					<option value="50" selected="selected">50</option>
					<option value="100">100</option>
				</select>				
			  </div>
			  </td>
			  <td width="12%">Pages : <span id="cont_pages">
			    <select name="pact"><option value=1>1</option></select></span>
		  	  </td-->
			  <!--td width="50%" style="text-align:right">Crit&egrave;re de tri : 
				  <select name="cTri" onChange="filtrer();">
				  <option value="IdClient">  </option>
				<option value="Immatriculation"> Immatriculation </option>
				<option value="DsgTransport">Désignation d'transport </option>				
				</select>
		  	  </td>
			  <td width="50%">&nbsp;&nbsp;&nbsp;&nbsp; Ordre de tri : 
				  <select name="oTri" onChange="filtrer();">
				<option value="ASC"> Croissant </option>
				<option value="DESC" selected> Decroissant </option>
				</select>
			  </td>

			</tr>
		</table>
	</div-->
	</form>
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>

<div id="formRes" style="overflow-y:scroll;min-height:280px;"></div>
<input type="hidden" id="act"/>
</div>
<div id="boxClient"> </div>
<script language="javascript" type="text/javascript">

	   
$(document).ready(function(){	
  		$('#formRes').load('vehicule.php?aff');
				$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	950,
					height			:	400,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'<?php echo $trad['label']['titreBox']; ?>',
					buttons			:	{
						"<?php echo $trad['button']['Annuler']; ?>"		: function(){
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['enregistrer']; ?>"	: function() {
							terminer();
						
						}
					 }
			});
  });
	function filtrer(){	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'vehicule.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'vehicule.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='vehicule.php?add';	
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='vehicule.php?mod&ID='+id;
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}
/*Designation: "required",
                                                Colisage: "required",
												Codeabarre : "required",
												Pa:"required",
												Unite:"required",
												Fournisseur:"required",
												Famille:"required",
												Tva:"required"*/
function terminer(){
	var form="";
	var act = $('#act').attr('value');
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: { 
                                                Immatriculation: "required",
												Designation:"required",
												TVehicule:"required",
												DateMS:"required",
												DateVT:"required",
												DateVidange:"required",
												DateAssurance:"required"
												
                                        } 
		});
	var test=$(form).valid();

	verifSelect2('TVehicule');
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['terminerOperation']; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {//
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'vehicule.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'vehicule.php?goAdd',
														method			:	'post'
													}); 
													
												
											}
		
					}
				})
		}
	}	
	
		function verifSelect(NomSelect){
		//test Ville
		//alert(NomSelect);
		var Ville=$('select[id='+NomSelect).attr('class'); 
				if (Ville.indexOf("error") < 0)
				{$('#'+NomSelect).removeClass('erroer');	
					$('div.'+NomSelect+' button').css("border", "1px solid #ccc").css("background","#fff");
				}
				else {
				
					$('div.'+NomSelect+' button').css("border", "none").css("background","#FFECFF");
					$('.'+NomSelect).addClass('erroer');
				}
		
		
		
	}
	/*$('body').on('keypress', '#Cin', function(args) {alert("keyCode : " + args.keyCode );
   if (args.keyCode == 13) {alert("ggg");
       $("#rechercher").click();
       return false;
   }
   });*/
</script>



<?php
include("footer.php");
?>
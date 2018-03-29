
<?php 
include("php.fonctions.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "regions";
$cleTable = "idSousFamille";
if(isset($_GET['delPlusieursTransports'])){
	$sup = true;
	$ligneSelect = explode(",",$_POST['CLETABLE']);
	foreach($ligneSelect as $a=>$ligne){
		if($ligne!=0){
			$data = explode(",",$ligne);
			$idp = $data[0];
			//$souche = $data[0];
			
			
				$sqlReq = "update $tableInser set Etat=0 where $cleTable = '$idp'";
				
				if(!mysql_query($sqlReq)) echo "erreur suppression  ...";
				else{	if(!mysql_query($sqlReq)) {echo "ERREUR SUPPRESSION equipement";$sup=false;}		}
			
		}
	}
	if($sup == true){
		?><script language="javascript" > 
				//alert('picesphp');
				alert('Supression de la selection effectuee.'); 
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
	$tableInser = "transports";
$sansDoublons = "Immatriculation";
$cleTable = "codeFamille";
$nom_sansDoublons = "Numéro d\'immatriculation";
	//on verif si codeF existe deja
			$reqModif = "UPDATE $tableInser SET Immatriculation='".addslashes(mb_strtolower(securite_bdd($_POST['CodeFamille'])))."',";
			$reqModif .=  " DsgTransport='".addslashes(mb_strtolower(securite_bdd($_POST['DsgFamille'])))."'";
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
									$('#boxFamille').dialog('close');
									rechercher();
							</script>
						
							<?php
							
						}else {echo mysql_error(); 
					
						?>
								<script language="javascript">
								//alertK("erreur lors de l'ajout. Contacter l'administrateur",0);
								alert("erreur lors de la modification. Contacter l'administrateur.__<?php echo  mysql_error(); ?>");
								//$('#boxFamille').dialog('close');
								</script>
						<?php }
					}else{ //sansDoublons existe
						?><script language="javascript">
									//alertK('La <?php echo $nom_sansDoublons; ?> choisie existe déjà.\nMerci d\'en choisir une autre. ',0);
									alert('Le <?php echo $nom_sansDoublons; ?> choisi existe déjà.\nMerci d\'en choisir un autre. ');
									//$('#boxFamille').dialog('close');
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
			/**********************Controle doublon**********code Sous Famille************************/
$sql = "SELECT * FROM ".$tableInser." WHERE codeRegion=? ";
$param = array($_POST['CodeRegion']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query($conn,$sql,$param,$options);
if( $stmt === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	echo $error;
	return;
}
$count = sqlsrv_num_rows($stmt);
$IdAffectation = "" ;
	if($count >0)//*****************Already Exist code Famille
	{
	?>
				<script type="text/javascript"> 
					alert('<?php echo $trad['msg']['codeRegionexist']; ?>');
				</script>
	<?php
	return;
	}
			
			
	$reqInser = "INSERT INTO ".$tableInser." ( codeRegion,Designation,idZone ) values (?,?,?) ";
	
	$params1= array(addslashes(mb_strtolower(securite_bdd($_POST['CodeRegion']), 'UTF-8')),addslashes(mb_strtolower(securite_bdd($_POST['DsgRegion']), 'UTF-8')),$_POST['Zone']) ;
	$stmt1 = sqlsrv_query( $conn, $reqInser, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					alert('<?php echo $trad['msg']['messageAjoutSucces']; ?>');
					$('#boxFamille').dialog('close');
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
	$ID= $_GET['ID'] ;
	$sql = "select * from transports where Etat =1 and codeFamille = '$ID' ";
	//execSQL($sql);
	//echo $sql; return;
	$res=mysql_query($sql)or die(mysql_error().$sql);
	$row = mysql_fetch_assoc($res);

?>
	<div id="resMod" style="padding:5px;">&nbsp;</div>
	<form id="formMod" action="NULL" method="post" name="formAdd1"> 
		
		<table width="100%" border="0" align="center" cellpadding="5">
        <tr>
        	<td><div class="etiqForm" id="" > <strong>Code Famille</strong> : </div>
            </td>
            <td>
            <input type="hidden" value="<?php echo $ID ;?>" name="codeFamille" />
            <input class="FormAdd1" type="text" name="CodeFamille"
			id="CodeFamille" value="<?php echo $row["Immatriculation"];?>" size="44" tabindex="1"  />
          </td>
          </tr>
			<tr>
              <td><div class="etiqForm" id="DATE_PIECE" > <strong>Désignation famille</strong> : </div>
            </td>
            <td> 
            <textarea class="FormAdd1"   cols="42" style="resize:None;" rows="1" name="DsgFamille" id="DsgFamille"
			size="100"   tabindex="2"><?php echo $row["DsgTransport"];?></textarea>
        <!--    <input class="FormAdd1" type="text" name="DsgTransport" id="DsgTransport" size="100"
              value="" control="1" tabindex="6"  />-->
            </td>
            </tr>	  
			<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
    <script src="js/jquery.validerForm.js" type="text/javascript"></script>    
 <?php 
exit;
}

if (isset($_GET['add'])){
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){

$('#Zone').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['selectZone']; ?>',single:true,maxHeight: 100
});

$('body').on('change', '#Zone', function() {
	 	var Zone =$('#Zone').val(); <?php //echo $row["IdVille"];?>
		if(Zone!="") {
					$('div.Zone').removeClass('erroer');
					$('div.Zone button').css("border","1px solid #ccc").css("background","#fff");
		}
	 });

 });	
	</script>
<div id="resAdd" style="padding:5px;">&nbsp;</div>
<form id="formAdd" action="NULL" method="post" name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="10">
        <tr>
        	<td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['codeRegion']; ?></strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="CodeRegion"  id="CodeRegion" tabindex="1"  style="width:220px"/>
            </td>
          </tr>
			 <tr>
                <td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['Dsg']; ?></strong> : </div>
            </td>
            <td>
          <!--  <input class="FormAdd1" type="text" name="DsgTransport" id="DsgTransport" size="25"  control="1" tabindex="6"  />-->
           <textarea  rows="1" cols="42"  tabindex="2"   class="FormAdd1" name="DsgRegion" style="text-align:<?php echo $_SESSION['align'] ; ?>;resize:None;width:220px" >
</textarea>
            </td>
             </tr>
			 <tr>
			  <td><div class="etiqForm" id="" > <strong><?php echo $trad['Label']['zone']; ?> </strong> : </div>
            </td>
            <td>
            <!--select  name="Famille" id="Famille"  multiple="multiple" tabindex="3" style="width:220px;" class="Select Famille">
				<option>Famille1</option>
				<option>Famille2</option>
			</select-->
			 	<select  name="Zone" id="Zone"  multiple="multiple" tabindex="3" class="Select Zone" style="display:visible;width:220px;">
		
                         <?php $sql = "select IdZone, Designation from zones ";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['IdZone'] ?>"><?php echo $donnees['Designation']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>
			
			 </tr>
			<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){

	$sqlA = "SELECT r.idRegion , r.codeRegion,r.Designation AS Region , z.Designation as Zone FROM regions r INNER JOIN zones z  ON r.idZone=z.idZone";

    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	if(isset($_POST['CodeRegion']) && ($_POST['CodeRegion']!='') )
	{  
	   $sqlA .=" where r.codeRegion like ? " ;
	   $params = array("%".$_POST['CodeRegion']."%");
	}
	//ECHO $sqlA."<br>";
	$stmt=sqlsrv_query($conn,$sqlA,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	$ntRes = sqlsrv_num_rows($stmt);

	
		if(isset($_POST['cTri']))	$cTri= $_POST['cTri'];
		else $cTri= "idRegion";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "DESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = " ORDER BY idRegion desc";//$cTri $oTri ";//LIMIT $min,$max ";
	$sql = $sqlA.$sqlC;//.$sqlB.$sqlC;
	//echo $sql;
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
		if($nRes==0){
			?>
			<div class="resAff">
				<br><br>
				<?php echo $trad['msg']['AucunResultat'];?>
			</div>
			<?php
}else{
	?>
<script language="javascript" type="text/javascript">
$('#cont_pages').html('<?php echo $selPages; ?>');
</script>
		<form id="formSelec" method="post">
	<table width="70%" border="0" style="margin:auto">
      <tr class="entete">
        <td width="30%"><?php echo $trad['label']['codeRegion']; ?></td>
        <td width="50%"><?php echo $trad['Label']['region']; ?></td>
        <td width="50%"> <?php echo $trad['Label']['zone']; ?> </td>		
        <!--td width="20%" colspan="2">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td-->
  </tr>

<!--<div id="cList">-->
	<?php
		$i=0;
	
			while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
				
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $c; ?>">
				<td align="<?php echo $_SESSION['align'] ; ?>"  > <?php echo $row['codeRegion']; ?> </td>
				<td align="<?php echo $_SESSION['align'] ; ?>" > <?php 	echo $row['Region'];?> </td>
				<td align="<?php echo $_SESSION['align'] ; ?>" > <?php 	echo $row['Zone'];?> </td>				
				<!--td align="center">
					<span class="boutons"> 
					<input type="button" title="Modifier" action="mod" class="b" onClick="modifier('<?php echo $row['codeSousFamille']; ?>');" />  
					</span>
			  </td>			
			  <td align="left">
				<input type="checkbox" class="checkLigne" name="<?php	echo $row['codeSousFamille']; ?>" value="<?php	echo $row['codeSousFamille']; ?>" />
			  </td-->
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'region.php?delPlusieursTransports',clearForm:false});		
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
<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['parametrage']; ?>&nbsp;<img src="images/tri.png" />
    &nbsp;<?php echo $trad['Label']['region']; ?>&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="101%" border="0" align="center" >
				  <tr>
					<td width="23%" valign="middle">
					<div class="etiqForm" id="SYMBT" > <?php echo $trad['label']['codeRegion']; ?> : </div>				</td>
					<td width="30%">
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					<div align="left">
				<input class="formTop"  name="CodeRegion" type="text" size="30" />						</div>
										</td>
				  <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech"			  title="<?php echo $trad['button']['rechercher']; ?> " />
					  <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider']; ?>" class="bouton32" action="effacer" title="<?php echo $trad['label']['vider']; ?>"/></span><br/></td>
				  <td width="25%" rowspan="2"   style="border-<?php echo $_SESSION['align'] ; ?>:1px solid #778;"><span class="actionForm">
					<input name="button3" type="button" title="<?php echo $trad['button']['ajouter']; ?>  " class="bouton32" onClick="ajouter();" value="<?php echo $trad['button']['ajouter']; ?> " action="ajout" />
				  </span></td>	
				</tr>			  
			 </table>
			 
		 </div>
		<div id="formFiltre" style="display:none;">
		<table border="0" width="100%">
			<tr height="20">
			  <td width="23%">
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
		  	  </td>
				<td width="30%">Crit&egrave;re de tri : 
				  <select name="cTri" onChange="filtrer();">
				  <option value="codeFamille">  </option>
				<option value="Immatriculation"> Immatriculation </option>
				<option value="DsgTransport">Désignation d'transport </option>				
				</select>
		  	  </td>
			  <td width="22%">Ordre de tri : 
				  <select name="oTri" onChange="filtrer();">
				<option value="ASC"> Croissant </option>
				<option value="DESC" selected> Decroissant </option>
				</select>
				</td>
			  <td width="20%" align="right">
			  <span id="nr" ></span> 
			  </td>
			</tr>
		</table>
	</div>
	</form>
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>

<div id="formRes" style="overflow-y:scroll;min-height:280px;"></div>
<input type="hidden" id="act"/>
</div>
<div id="boxFamille"> </div>
<script language="javascript" type="text/javascript">
$(document).ready(function(){	
  			$('#formRes').load('region.php?aff');
				$('#boxFamille').dialog({
					autoOpen		:	false,
					width			:	600,
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
						"<?php echo $trad['button']['enregistrer']; ?> "	: function() {
							terminer();
						
						}
					 }
			});
  });
	function filtrer(){	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'region.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'region.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='region.php?add';	
		$('#boxFamille').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='region.php?mod&ID='+id;
		$('#boxFamille').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function terminer(){
	var form="";
	var act = $('#act').attr('value');
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: {
                                                DsgRegion: "required",
                                                CodeRegion: "required",
												Zone:"required"
                                                  
                                          }     });						  
		
	 var test=$(form).valid();
	 verifSelect2('Zone');
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['terminerOperation']; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {
					if(r)	{
						if(act == 'mod'){	
												if (controlForm('#formMod')){
													$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'region.php?goMod',
														method			:	'post'
													}); 
													return false;
												}
											}else{
												
												
												if (controlForm('#formAdd')){
												
													$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'region.php?goAdd',
														method			:	'post'
													}); 
													return false;
												}
											}
		
					}
				})
		}
	}	
</script>



<?php
include("footer.php");
?>
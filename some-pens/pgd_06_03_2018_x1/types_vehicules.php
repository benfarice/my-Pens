<?php 
include("php.fonctions.php");
require_once('connexion.php');
mysql_query("SET NAMES UTF8");
session_start();
$tableInser = "transports";
$sansDoublons = "TypeVehicule";
$cleTable = "IdTransport";
$nom_sansDoublons = "Numéro d\'immatriculation";
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
	//on verif si codeF existe deja
			$reqModif = "UPDATE $tableInser SET ";
			$reqModif .=  " TypeVehicule='".addslashes(securite_bdd($_POST['TypeVehicule']))."'";
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
									$('#box').dialog('close');
									rechercher();
							</script>
						
							<?php
							
						}else {echo mysql_error(); 
					
						?>
								<script language="javascript">
								//alertK("erreur lors de l'ajout. Contacter l'administrateur",0);
								alert("erreur lors de la modification. Contacter l'administrateur.__<?php echo  mysql_error(); ?>");
								//$('#box').dialog('close');
								</script>
						<?php }
					}else{ //sansDoublons existe
						?><script language="javascript">
									//alertK('La <?php echo $nom_sansDoublons; ?> choisie existe déjà.\nMerci d\'en choisir une autre. ',0);
									alert('Le <?php echo $nom_sansDoublons; ?> choisi existe déjà.\nMerci d\'en choisir un autre. ');
									//$('#box').dialog('close');
							</script>
	<?php				}
	}

exit;
	
}
if(isset($_GET['goAdd'])){


	$reqInser = "INSERT INTO ".$tableInser." (TypeVehicule) ";
    $reqInser		.=" VALUES (  '".addslashes(securite_bdd($_POST['TypeVehicule']), 'UTF-8')."' )";
	//on verif si codeF existe deja
		$reqVerif = "select * from $tableInser where  Etat=1 and  $sansDoublons='".addslashes($_POST[$sansDoublons])."'";

				if(mysql_num_rows(mysql_query($reqVerif))==0){ //new
						$resInser = mysql_query($reqInser);
						if($resInser){
							?>
							
							<script type="text/javascript"> 
							//alertK('L\'ajout a été effectué.',1);
							alert('L\'ajout a été effectué.');
								$('#box').dialog('close');
									rechercher();
							</script>
						
							<?php
							
						}else {echo mysql_error(); 
						?>
								<script language="javascript">
								//alertK("erreur lors de l'ajout. Contacter l'administrateur",0);
								alert("erreur lors de l'ajout. Contacter l'administrateur");
								//$('#box').dialog('close');
								</script>
						<?php }
					}else{ //sansDoublons existe
						?><script language="javascript">
									//alertK('La <?php echo $nom_sansDoublons; ?> choisie existe déjà.\nMerci d\'en choisir une autre. ',0);
									alert('Le <?php echo $nom_sansDoublons; ?> choisi existe déjà.\nMerci d\'en choisir un autre. ');
									//$('#box').dialog('close');
							</script>
	<?php				}
exit;
}

if (isset($_GET['mod'])){	
	$ID= $_GET['ID'] ;
	$sql = "select * from transports where Etat =1 and IdTransport = '$ID' ";
	//execSQL($sql);
	//echo $sql; return;
	$res=mysql_query($sql)or die(mysql_error().$sql);
	$row = mysql_fetch_assoc($res);

?>
	<div id="resMod" style="padding:5px;">&nbsp;</div>
	<form id="formMod" action="NULL" method="post" name="formAdd1"> 
		
		<table width="100%" border="0" align="center" cellpadding="5">
     
			<tr>
              <td><div class="etiqForm" id="DATE_PIECE" > <strong>Désignation transport</strong> : </div>
            </td>
            <td> 
     
       <input class="FormAdd1" type="text" name="TypeVehicule" id="TypeVehicule" size="35"
              value="<?php echo $row["TypeVehicule"];?>" tabindex="6"  />
            </td>
            </tr>	  
			<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>

 <?php 
exit;
}

if (isset($_GET['add'])){
?>
<div id="resAdd" style="padding:5px;">&nbsp;</div>
<form id="formAdd" action="NULL" method="post" name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="5">
     
			 <tr>
                <td><div class="etiqForm" id="" > <strong>Type véhicule</strong> : </div>
            </td>
            <td>
           <input class="FormAdd1" type="text" name="TypeVehicule" id="TypeVehicule" size="30"   tabindex="6"  />
    
            </td>
             </tr>
			<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){

			$sqlE =" ";
	$sqlA = "
		SELECT 
			*
		FROM 
			$tableInser
		";
//$sqlA =" select * from $fa ";
	$sqlB = " WHERE 1 and etat=1 ";

	if(isset($_POST['TypeVehicule']) && ($_POST['TypeVehicule']!='') )
		$sqlB .=" AND TypeVehicule like '%".$_POST['TypeVehicule']."%' " ;

	/*if($_POST['DATED']!=""){
		$sqlB .=" AND ( DATE$fa BETWEEN '".date_sql($_POST['DATED'])."' AND '".date_sql($_POST['DATEF'])."') ";
	}*/
	//echo $sqlA.$sqlB."";//exit;
	$ntRes = mysql_num_rows(mysql_query($sqlA.$sqlB));
	
		if(isset($_POST['cTri']))	$cTri= $_POST['cTri'];
		else $cTri= "IdTransport";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "DESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = " ORDER BY $cTri $oTri LIMIT $min,$max ";
	$sql = $sqlA.$sqlB.$sqlC;
	
//echo $sql."<br>";
/*execSQL($sql);*/
	$resAff = @mysql_query($sql)or die(mysql_error());
	$nRes = @mysql_num_rows($resAff);
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
				Aucun r&eacute;sultat &agrave; afficher.
			</div>
			<?php
}else{
	?>
<script language="javascript" type="text/javascript">
$('#cont_pages').html('<?php echo $selPages; ?>');
</script>
		<form id="formSelec" method="post">
	<table width="100%" border="0">
      <tr class="entete">

        <td width="50%"> Type de vihécule </td>
        <td width="20%" colspan="2">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td>
  </tr>

<!--<div id="cList">-->
	<?php
		$i=0;
	
		while($row = mysql_fetch_array($resAff)){
				
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $c; ?>">
			
				<td align="left" > <?php 	echo $row['TypeVehicule'];?> </td>
				<td align="center">
					<span class="boutons"> 
					<input type="button" title="Modifier" action="mod" class="b" onClick="modifier('<?php echo $row['IdTransport']; ?>');" />  
					</span>
			  </td>			
			  <td align="left">
				<input type="checkbox" class="checkLigne" name="<?php	echo $row['IdTransport']; ?>" value="<?php	echo $row['IdTransport']; ?>" />
			  </td>
			  </tr></li>
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'types_vehicules.php?delPlusieursTransports',clearForm:false});		
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


<div id="brouillon" style="display:block">  </div> 
<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;Paramètrage&nbsp;<img src="images/tri.png" />
    &nbsp;Gestion des types de véhicules&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
					<div id="formRech" style="">	
		<table width="101%" border="0" align="center" >
			  <tr>
				<td width="23%" valign="middle">
				<div class="etiqForm" id="SYMBT" > Désignation du type : </div>				</td>
				<td width="30%">
				<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
				<div align="left">
			<input class="formTop"  name="TypeVehicule" type="text" size="30" />						</div>
									</td>
		      <td width="22%" rowspan="2" >	<span class="actionForm">      
          <input name="button" type="button"  onClick="rechercher();" value="Rechercher" class="bouton32" action="rech"
		  title="Rechercher " />
			      <input name="button2" type="reset" onClick="" value="Effacer" class="bouton32" action="effacer" title="Effacer"/></span>
				  <br/></td>
			  <td width="25%" rowspan="2"   style="border-left:1px solid #778;"><span class="actionForm">
			    <input name="button3" type="button" title="Ajouter " class="bouton32" onClick="ajouter();" value="Ajouter" action="ajout" />
			  </span></td>		
			</tr>			  
	 	 </table>
         </div>
      <div id="formFiltre" style="display:;">
		<table border=0 width="100%">
			<tr height="20">
			  <td width="23%"><div id="filtreNPP">
			  	R&eacute;sultats par page : <select name="npp" id="npp" onChange="filtrer();">
					<option value="10">10</option>
					<option value="20" >20</option>
					<option value="50" selected="selected">50</option>
					<option value="100">100</option>
				</select>				
			  </div></td>
			  <td width="12%">Pages : <span id="cont_pages">
			    <select name="pact"><option value=1>1</option></select></span>
		  	  </td>
				<td width="30%">Crit&egrave;re de tri : 
				  <select name="cTri" onChange="filtrer();">
				  <option value="IdTransport">  </option>
				<option value="TypeVehicule"> TypeVehicule </option>
				<option value="TypeVehicule">Désignation d'transport </option>				
				</select>
		  	  </td>
			  <td width="22%">Ordre de tri : 
				  <select name="oTri" onChange="filtrer();">
				<option value="ASC"> Croissant </option>
				<option value="DESC" selected> Decroissant </option>
				</select>
			  <td width="20%" align="right"><span id="nr" ></span> </td>
			</tr>
		</table>
	</div>
	</form>
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>

<div id="formRes" style="overflow-y:scroll;min-height:280px;"></div>
<input type="hidden" id="act"/>
<script language="javascript" type="text/javascript">
$(document).ready(function(){	
  			$('#formRes').load('types_vehicules.php?aff');
				$('#box').dialog({
					autoOpen		:	false,
					width			:	560,
					height			:	300,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Ajout / Modification de transport',
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
	function filtrer(){	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'types_vehicules.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'types_vehicules.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='types_vehicules.php?add';	
		$('#box').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='types_vehicules.php?mod&ID='+id;
		$('#box').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function terminer(){
	var form="";
	var act = $('#act').attr('value');
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: {
                                                TypeVehicule: "required"
                                                  
                                          }     });						  
		
	 var test=$(form).valid();
		if(test==true){		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						if(act == 'mod'){	
												
													$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'types_vehicules.php?goMod',
														method			:	'post'
													}); 
												
											}else{
												
													$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'types_vehicules.php?goAdd',
														method			:	'post'
													}); 
												
												
											}
		
					}
				})
		}
	}	
</script>
<?php
include("footer.php");
?>
<?php 
include("php.fonctions.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "vendeurs";
$cleTable = "IdVendeur";
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
	$tableInser = "transports";
$sansDoublons = "Immatriculation";
$cleTable = "IdTransport";
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
						}
						else
						{ //sansDoublons existe
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
//print_r($_POST);return;print_r($_FILES);echo ($_FILES['file']['name']);
if(isset($_POST["Ncnss"]))
{
	$Ncnss=$_POST["Ncnss"];
}else{
	$Ncnss="";
}

$codeVendeur="V".Increment_Chaine_F("codeVendeur","vendeurs","idVendeur",$conn,"","");//echo $codeVendeur;return;
	
	if(isset($_FILES['file']))
	{
	$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
	$file_extension = end($ext); // Store extensions in the variable.
	$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
	$target_path = "imageVendeur/" . $nameFile;     // Set the target path with a new name of image.
	
		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	  	if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
			{
			?>
						<script type="text/javascript"> 
							alert('<?php echo $trad['msg']['echecDeplacementImage'] ; ?>');
						</script>
			<?php
			return;
			}
	}
	else
	{
	$target_path = "images/anonyme.png";     // Set the target path with a new name of image.
	}
/**********************Controle doublon****CIN************************/
$sql = "SELECT * FROM ".$tableInser." WHERE cin=? ";
$param= array($_POST['Cin']);
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
					alert('<?php echo $trad['msg']['codeSecteurexist']; ?>');
				</script>
	<?php
	return;
	}
	$superviseur=0;
	if(isset($_POST['sup']))
	{
	$superviseur=1;
	}
	$plafond=0;
	if($_POST['plafond'] !="")
	{
	$plafond=$_POST['plafond'];
	}
	
		$reqInser1 = "INSERT INTO ".$tableInser." ([nom],[prenom],[telephone],[adresse],[mail],[cin],[idDepot],numPermis,
		categoriePermis,
		dateLivraisonPermis,
		photo,
		codeVendeur,
		dateEmbauche,
		cnss,
		idUtilisateur,
		Login,
		Password,
		superviseur,
		plafond
		)
		values 	(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$params1= array(
		addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Prenom']), 'UTF-8')),
		$_POST['Tel'],
		addslashes(mb_strtolower(securite_bdd($_POST['Adresse']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Mail']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Cin']), 'UTF-8')),
		$_SESSION['IdDepot'],
		$_POST['Npermis'],
		$_POST['Cpermis'],
		$_POST['Dpermis'],
		$target_path,
		$codeVendeur,
		$_POST['Dembauche'],
		$Ncnss	,
		1,
		addslashes(mb_strtolower(securite_bdd($_POST['Login']), 'UTF-8')),
		crypt($_POST['Pwd'], 'aminawahmane'),
		$superviseur,
		$plafond
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
        	<td><div class="etiqForm" id="" > <strong>Code Famille</strong> : </div>
            </td>
            <td>
            <input type="hidden" value="<?php echo $ID ;?>" name="IdTransport" />
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
$('#Cpermis').multipleSelect({
filter: true,placeholder:'<?php echo $trad['label']['selectCatPermis'] ;?>',single:true,maxHeight: 100,width:220
});
$(document).ready(function(){
$(":file").jfilestyle({input: false,buttonText: "<img src='frontend2/img/folder.png' /><?php echo $trad['button']['parcourir'] ; ?>"});
		calendrier("Dpermis");
		calendrier("Dembauche");		
	 $('body').on('change', '#Cpermis', function() {
	 			var Cpermis =$('#Cpermis').val();
				if(Cpermis!="") {
					$('div.Cpermis').removeClass('erroer');
					$('div.Cpermis button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
});

  function readURL(input) {

   if ($('.jfilestyle').val() != '') {
          var file = $('.jfilestyle')[0].files[0];
          var fileName = file.name;
          var fileExt = '.' + fileName.split('.').pop();
        //  alert(fileExt);
        }
    /*    else {
          alert('Merci de selectionner un fichier.')
        }*/
		
        if (input.files && input.files[0]) {
            var reader = new FileReader();
	

			 reader.onload = function (e) {
					 $(".DivFile").html('');
						if (fileExt=='.mp4') {
							$("<video />", {
								 "src": e.target.result,
								 "class": " ",
								 "controls":true,
								"autoplay":false,
								"width":"160"
							 }).appendTo($(".DivFile"));
						}
						else {
							 $("<img />", {
								 "src": e.target.result,
									 "class": "test ",
									 "width":"113",
									 "height":"110"
									 
							 }).appendTo($(".DivFile")).trigger( "contentchange" );
						}
                 }
				 
         reader.readAsDataURL(input.files[0]);
	
        }
    }
</script>

<div id="resAdd" style="padding:5px;">&nbsp;</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"  enctype="multipart/form-data" > 	
	<table width="100%" border="0" align="center" cellpadding="5" cellspacing="10">
        <tr>
        	<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['nom'] ;?>  </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Nom"  id="Nom" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['prenom'] ;?></strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Prenom"  id="Prenom" size="30" tabindex="1"  />
            </td>
		</tr>

		 <tr>
		 <td><div class="etiqForm" id="" > <strong><?php echo $trad['map']['adresse'] ;?> </strong> : </div>
            </td>
            <td>
         <textarea class="FormAdd1" name="Adresse"  id="Adresse"  rows="2" cols="28"   />
            </td>
		<td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['cin'] ;?></strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="Cin" id="Cin" size="30" tabindex="1"  />
            </td>			
          </tr>
		<tr>
			<td>
			 <div class="etiqForm" id="" > <?php echo $trad['label']['Tel'];?> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Tel"  id="Tel" size="30" tabindex="1"  />
            </td>
		<td><div class="etiqForm" id="" ><strong><?php echo $trad['label']['Mail'];?></strong> : </div>
            </td>
            <td>
            	<input class="FormAdd1" type="text" name="Mail"  id="Mail" size="30" tabindex="1"  />
            </td> 
		</tr> 
		<tr>
        	<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['Npermis'] ;?></strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Npermis"  id="Npermis" onkeypress="return isNumberKey(event);" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['catPermis'] ;?></strong> : </div>
            </td>
            <td>
			<select name="Cpermis" id="Cpermis"  multiple="multiple" tabindex="3" class="Select Cpermis" >
			<option>A</option>
			<option>B</option>
			<option>C</option>
			<option>D</option>	
			<option>E</option>				
			</select>
            </td>
		</tr>
		<tr>
        	<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['DLivraisonPermis'] ;?></strong> : </div>
            </td>
            <td>
			 <input class="FormAdd1" type="text" name="Dpermis" maxlength="10" onChange="verifier_date(this);"  id="Dpermis" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['Dembauche'] ;?></strong> : </div>
            </td>
            <td>
			<input class="FormAdd1" type="text" name="Dembauche" maxlength="10" onChange="verifier_date(this);"  id="Dembauche" size="30" tabindex="1"  />
         
            </td>
		</tr>		
		<tr>
        	<td>
			 <div class="etiqForm" id="" ><?php echo $trad['label']['nCnss'] ;?> <strong></strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Ncnss" onkeypress="return isNumberKey(event);" id="Ncnss" size="30" tabindex="1"  />
            </td>
		</tr>
		
		
		
        <tr>		
			<td valign="top">
			 <div class="etiqForm" id="" ><?php echo $trad['label']['photo'] ;?> <strong></strong> : </div>
            </td>
            <td valign="top">
               <input class="jfilestyle" data-input="false" type="file" name="file" control="1" id="file" size="25" tabindex="1" onchange="readURL(this);"  value="" />
			   
			 </td>
			<td colspan="2">		
			<div class="DivFile" style=""  >
				<img src="images/anonyme.png"  class=" tof" height="113" width="108" style="display:inline-block;" />
			</div>
            </td>
		</tr>		
			<tr>
        	<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['login']['name'] ;?> </strong> : </div>
            </td>
            <td>
			 <input class="FormAdd1" type="text" name="Login" id="Login" size="30" tabindex="1" />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['login']['pwd'] ;?> </strong> : </div>
            </td>
            <td>
			<input class="FormAdd1" type="password" name="Pwd"  id="Pwd" tabindex="1"  />
         </td>
		</tr>
		<tr>
		<td> 
			<div class="etiqForm" id="" >
			<strong><?php echo $trad['label']['plafond'] ;?></strong>  </div>
			</td>	
			<td>
			<input class="FormAdd1" type="text" name="plafond" id="plafond" size="30" tabindex="1" />
			</td>	
			
			<td> 
			<div class="etiqForm" id="" ><input type="checkbox" id="sup" name="sup" />
			<strong><?php echo $trad['label']['superviseur'] ;?></strong>  </div>
			</td>
			<td></td>
					
		</tr>
     	<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
	
	<!-- Styles Js -->
	
	
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){

$sqlA = "SELECT * FROM vendeurs";
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$sqlA .=" where IdDepot=?";
	 $params = array($IdDepot);
	if(isset($_POST['codeV']) && ($_POST['codeV']!='') )
	{	$sqlA .=" and codeVendeur like ? " ;
	   $params = array("%".$_POST['codeV']."%",$IdDepot);
	}
	//parcourir( $params );
//	ECHO $sqlA."<br>";
	$stmt=sqlsrv_query($conn,$sqlA,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes."<br>";
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "idVendeur";
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
//	echo $sql;

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
						<?php echo $trad['msg']['AucunResultat'];?>
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
		<td width="10%"><?php echo $trad['label']['codeVendeur'];?></td>	  
        <td width="15%"><?php echo $trad['label']['nom'];?></td>
        <td width="15%"><?php echo $trad['label']['prenom'];?></td>
		<td width="20%"><?php echo $trad['map']['adresse'];?></td>
        <td width="10%"><?php echo $trad['label']['Tel'];?></td>  
		<td width="15%"><?php echo $trad['label']['Mail'];?></td>

        <td width="10%" colspan="2" style="display:none">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
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
				<td align="<?php echo $_SESSION['align'] ; ?>"  > <?php echo $row['codeVendeur']; ?> </td>			
				<td align="<?php echo $_SESSION['align'] ; ?>"  > <?php echo $row['nom']; ?> </td>
				<td align="<?php echo $_SESSION['align'] ; ?>" > <?php 	echo $row['prenom'];?> </td>
				<td align="<?php echo $_SESSION['align'] ; ?>"  > <?php echo $row['adresse']; ?> </td>
				<td align="<?php echo $_SESSION['align'] ; ?>" > <?php 	echo $row['telephone'];?> </td>
				<td align="<?php echo $_SESSION['align'] ; ?>" > <?php 	echo $row['mail'];?> </td>	
				<!--td align="center">
					<span class="boutons"> 
						<input type="button" title="Modifier" action="mod" class="b" onClick="modifier('<?php echo $row['idVendeur']; ?>');" />  
					</span>
			  </td-->			
			  <td colspan="2" align="center" style="display:none">
				<input type="checkbox" class="checkLigne" name="<?php echo $row['idVendeur']; ?>" value="<?php echo $row['idVendeur']; ?>" />
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'vendeur.php?delPlusieursArticle',clearForm:false});		
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
    &nbsp;<?php echo $trad['Menu']['gestionvendeur']; ?>&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="100%" border="0" align="center" >
				  <tr>
					<td width="23%" valign="middle">
					<div class="etiqForm" id="SYMBT" ><?php echo $trad['label']['codeVendeur']; ?>: </div>				</td>
					<td width="30%">
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					<div align="<?php echo $_SESSION['align'] ; ?>">
				<input class="formTop"  name="codeV" id="codeV" type="text" size="30" />	
				</div>
				</td>
			
				  <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button" onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?> " />
					  <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider']; ?>" class="bouton32" action="effacer" title="<?php echo $trad['label']['vider']; ?>"/></span><br/></td>
				  <td width="25%" rowspan="2"   style="border-<?php echo $_SESSION['align'] ; ?>:1px solid #778;"><span class="actionForm">
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
<script src="frontend2/js/jquery-filestyle.min.js" type="text/javascript"></script>
<link href="frontend2/css/jquery-filestyle.css"  rel="stylesheet" />
<Style>
div.jfilestyle label {
	display: inline-block;
	border: 1px solid #c0c0c0;
	background: #ffffff;
	padding: 10px 30px;
	color: #0662ba;
	vertical-align: middle;
	line-height: normal;
	text-align: center;
	margin: 0px;
	font-size: 24px;
	width: auto;
	border-radius: 4px;
    font-weight: bold;
}
</Style>
<script language="javascript" type="text/javascript">

	   
$(document).ready(function(){	
  		$('#formRes').load('vendeur.php?aff');
				$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	1000,
					height			:	650,
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
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'vendeur.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'vendeur.php?rech'});
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='vendeur.php?add';	
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='vendeur.php?mod&ID='+id;
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
  var exts = ['jpg','gif','png'];
	var form="";
	var act = $('#act').attr('value');
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: { 
                                                Nom: "required",
												Prenom:"required",
												Adresse:"required",
												Cin:"required",
												//Tel: "required tel",
												Mail:{
														"required": true,
														"email": true
													 },
												Npermis	 :"required",
												Cpermis:"required",
												Dpermis:"required",
												Dembauche:"required",
												file:{
													  required: false,
													  accept: exts
													},
												Login: "required",
											    Pwd:  "required",
                                        }  ,
								messages : {
												Mail:"<?php echo $trad['msg']['EmailInvalide']; ?>"  ,
												//Tel:"xx xx xx xx xx"  ,												
												file:{
													 accept:"<?php echo $trad['msg']['ImageSeul']; ?>" 
													 }
										    }
		});
	var test=$(form).valid();
	verifSelect2('Cpermis');
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['terminerOperation']; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'vendeur.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'vendeur.php?goAdd',
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
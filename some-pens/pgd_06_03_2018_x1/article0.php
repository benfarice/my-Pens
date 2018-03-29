<?php 
include("php.fonctions.php");
include("class.uploader.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "articles";
$cleTable = "IdArticle";
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
				//alert('Supression de la sélection effectuée.'); 
				alert('<?php echo $trad['msg']['messageAjoutSucces']; ?>');
				rechercher();
		  </script>
		  <?php
	}else{
		?><script language="javascript" >
		//alert('Un ou plusieurs elements de la selection n\'ont pas pu etre supprimes.'); 
		alert('<?php echo $trad['msg']['msgSuppArticleError']; ?>'); 		
		</script><?php
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
									$('#boxArticle').dialog('close');
									rechercher();
							</script>
						
							<?php
							
						}else {echo mysql_error(); 
					
						?>
								<script language="javascript">
								//alertK("erreur lors de l'ajout. Contacter l'administrateur",0);
								alert("erreur lors de la modification. Contacter l'administrateur.__<?php echo  mysql_error(); ?>");
								//$('#boxArticle').dialog('close');
								</script>
						<?php }
					}else{ //sansDoublons existe
						?><script language="javascript">
									//alertK('La <?php echo $nom_sansDoublons; ?> choisie existe déjà.\nMerci d\'en choisir une autre. ',0);
									alert('Le <?php echo $nom_sansDoublons; ?> choisi existe déjà.\nMerci d\'en choisir un autre. ');
									//$('#boxArticle').dialog('close');
							</script>
	<?php				}
	}

exit;
	
}
if(isset($_GET['goAdd'])){
//print_r($_POST['Colisage']);


//return;

$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
/**********************Controle doublon****CB************************/
$sql = "SELECT * FROM articles WHERE CB=? and etatSup=1";
$param= array($_POST['Codeabarre']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql ,$param,$options);
if( $stmt === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	echo $error;return;
}
$count = sqlsrv_num_rows($stmt);
	if($count >0)
	{
	?>	
							<script type="text/javascript"> 
							//alertK('L\'ajout a été effectué.',1);
							alert('<?php echo $trad['msg']['CBexist']; ?>');//alert('CB déja exsitant , veuillez saisir un autre.');
							</script>
						
							<?php
							return;
	}
	/**********************Controle doublon****Reference************************/
$sql = "SELECT * FROM articles WHERE Reference=? and etatSup=1";
$param= array($_POST['ref']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql ,$param,$options);
if( $stmt === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	echo $error;return;
}
$count = sqlsrv_num_rows($stmt);
	if($count >0)
	{
	?>	
							<script type="text/javascript"> 
							//alertK('L\'ajout a été effectué.',1);
							alert('<?php echo $trad['msg']['Refexist']; ?>');//alert('Référence déja exsitante , veuillez saisir une autre.');
							</script>
						
							<?php
							return;
	}
//-----------------Add Article----------------//


/*IdArticle
Designation
Fournisseur
IdFamille
Colisage
PA
CB
Unite
TVA
idDepot*/
$fournisseur="";
$unite="";

if(isset($_POST["Fournisseur"]))
{
	$fournisseur=$_POST["Fournisseur"];
}else{
	$fournisseur=NULL;
}
if(isset($_POST["Unite"]))
{
	$unite=$_POST["Unite"];
}else{
	$unite=NULL;
}

//parcourir($_POST);return;
$reqInser1 = "INSERT INTO ".$tableInser." ([Designation] ,[Fournisseur] ,[IdFamille] ,Reference ,[CB],[Unite],[TVA],[idDepot],etatSup) values 	(?,?,?,?,?,?,?,?,?)";

$params1= array(addslashes(mb_strtolower(securite_bdd($_POST['Designation']), 'UTF-8')),$fournisseur,$_POST['Famille'],
addslashes(mb_strtolower(securite_bdd($_POST['ref']), 'UTF-8')),addslashes(mb_strtolower(securite_bdd($_POST['Codeabarre']), 'UTF-8')),$unite,NULL,1,1) ;

$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
if( $stmt1 === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IDArticle--------------------------------//
$sql = "SELECT max(IdArticle) as IdArticle FROM articles";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdArticle = sqlsrv_get_field( $stmt2, 0);
//----------------------Add MeDia--------------------------//
$files = glob("uploads/*.*");//returns all image uploaded 
$target_file="Media/";
for ($i=0; $i<count($files); $i++)//
{
$path = $files[$i];
/*************Check if file is image***************/
$image_type = mime_content_type($path);
//echo $image_type."<br />";
echo "goAsssssssdd";return;
	if(in_array($image_type , array("image/png" , "image/jpeg" ,"image/jpg" , "image/bmp")))
	{
		echo "goAssssssssssssssssssssdd";return;
		//print $path ."<br />";
		$ext = explode('.', basename($path));   // Explode file name from dot(.)
		$file_extension = end($ext); // Store extensions in the variable.
		$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
		$target_path = "media/" . $nameFile;     // Set the target path with a new name of image.
	//	echo "----New Name : ".$path."<br />"."<br />";

		if(rename($path,$target_path))
		{
			
			$reqInser2 = "INSERT INTO  Media([IdArticle],[Url],[idDepot] ) values (?,?,?)";
			
			$params2= array($IdArticle,$target_path,1) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
				
				break ;
			}
		
		}
		else
		{
			$error.=$trad['msg']['echecDeplacementImage'].$path . " <br/> ";
			break ;
		}
		
		
	}
}
/****************************Colisage*****************************/
if(isset($_POST['Colisage'])){/*
foreach($_POST['Colisage'] as $colisage){
		$reqInser3 = "INSERT INTO colisages([IdArticle],[colisagee],[idDepot] ) values (?,?,?)";
		$params3= array($IdArticle,$colisage,1) ;
		$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3);
		if( $stmt4 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
			break ;
		}
}*/
$reqInser3 = "INSERT INTO colisages([IdArticle],[colisagee],box,palette,[idDepot] ) values (?,?,?,?,?)";
		$params3= array($IdArticle,$_POST['Colisage'],$_POST['box'],$_POST['palette'],1) ;
		$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3);
		if( $stmt4 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	
		}

}
if( $error=="" ) {
     sqlsrv_commit( $conn );
?>
		<script type="text/javascript"> 
			alert('<?php echo $trad['msg']['messageAjoutSucces']; ?>');
			$('#boxArticle').dialog('close');
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
	<script src="js/jquery.filer.min.js" type="text/javascript"></script>
	<!--script src="js/custom.js" type="text/javascript"></script-->
<script language="javascript" type="text/javascript">
$(document).ready(function(){

$("#filer_input2").filer({
		limit: null,
		maxSize: null,
		extensions: ["jpg","jpeg","png","bmp","gif"],
		changeInput: '<div class="jFiler-input-dragDrop" style="width:85px;"><div class="jFiler-input-inner"><div class="jFiler-input-icon"></div><a class="jFiler-input-choose-btn blue"><?php  echo $trad['button']['parcourir'] ;?></a></div></div>',
		showThumbs: true,
		theme: "dragdropbox",
		templates: {
			box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
			item: '<li class="jFiler-item">\
						<div class="jFiler-item-container">\
							<div class="jFiler-item-inner">\
								<div class="jFiler-item-thumb">\
									<div class="jFiler-item-status"></div>\
									<div class="jFiler-item-thumb-overlay">\
										<div class="jFiler-item-info">\
											<div style="display:table-cell;vertical-align: middle;">\
												<span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
											</div>\
										</div>\
									</div>\
									{{fi-image}}\
								</div>\
								<div class="jFiler-item-assets jFiler-row">\
									<ul class="list-inline pull-right">\
										<li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
									</ul>\
								</div>\
							</div>\
						</div>\
					</li>',
			itemAppend: '<li class="jFiler-item">\
							<div class="jFiler-item-container">\
								<div class="jFiler-item-inner">\
									<div class="jFiler-item-thumb">\
										<div class="jFiler-item-status"></div>\
										<div class="jFiler-item-thumb-overlay">\
											<div class="jFiler-item-info">\
												<div style="display:table-cell;vertical-align: middle;">\
													<span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
													<span class="jFiler-item-others">{{fi-size2}}</span>\
												</div>\
											</div>\
										</div>\
										{{fi-image}}\
									</div>\
									<div class="jFiler-item-assets jFiler-row">\
										<ul class="list-inline pull-left">\
											<li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
										</ul>\
										<ul class="list-inline pull-right">\
											<li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
										</ul>\
									</div>\
								</div>\
							</div>\
						</li>',
			progressBar: '<div class="bar"></div>',
			itemAppendToEnd: false,
			canvasImage: true,
			removeConfirmation: true,
			_selectors: {
				list: '.jFiler-items-list',
				item: '.jFiler-item',
				remove: '.jFiler-item-trash-action'
			}
		},
		dragDrop: {
			dragEnter: null,
			dragLeave: null,
			drop: null,
			dragContainer: null,
		},
		uploadFile: {
			url: "./ajax_upload_file2.php",
			data: null,
			type: 'POST',
			enctype: 'multipart/form-data',
			synchron: true,
			beforeSend: function(){},
			success: function(data, itemEl, listEl, boxEl, newInputEl, inputEl, id){
				var parent = itemEl.find(".jFiler-jProgressBar").parent(),
					new_file_name = JSON.parse(data),
					filerKit = inputEl.prop("jFiler");

        		filerKit.files_list[id].name = new_file_name;

				itemEl.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
				});
			},
			error: function(el){
				var parent = el.find(".jFiler-jProgressBar").parent();
				el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
				});
			},
			statusCode: null,
			onProgress: null,
			onComplete: null
		},
		files: null,
		addMore: false,
		allowDuplicates: false,
		clipBoardPaste: true,
		excludeName: null,
		beforeRender: null,
		afterRender: null,
		beforeShow: null,
		beforeSelect: null,
		onSelect: null,
		afterShow: null,
		onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
			var filerKit = inputEl.prop("jFiler"),
		        file_name = filerKit.files_list[id].name;

		    $.post('./ajax_remove_file2.php', {file: file_name});
		},
		onEmpty: null,
		options: null,
		dialogs: {
			alert: function(text) {
				return alert(text);
			},
			confirm: function (text, callback) {
				confirm(text) ? callback() : null;
			}
		},
		captions: {
			button: "Choose Files",
			feedback: "Choose files To Upload",
			feedback2: "files were chosen",
			drop: "Drop file here to Upload",
			removeConfirmation: "<?php echo $trad['msg']['msgSuppImage']; ?>",
			errors: {
				filesLimit: "Vous ne pouvez pas ajouter plus que {{fi-limit}} images.",
				filesType: "Extension non autorisée.",
				filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-maxSize}} MB.",
				filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
			}
		}
	});
	


$('#Unite').multipleSelect({
filter: true,placeholder:'<?php echo $trad['label']['selectUnite']; ?>',single:true,maxHeight: 100
});
$('#Tva').multipleSelect({
filter: true,placeholder:'<?php echo $trad['label']['selectTva']; ?>',single:true,maxHeight: 100
});	
	
	
$('#Famille').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['selectGamme']; ?>',single:true,maxHeight: 100
});


	$('body').on('change', '#Famille', function() {
	 			var Famille =$('#Famille').val(); <?php //echo $row["IdVille"];?>
				if(Famille!="") {
					$('div.Famille').removeClass('erroer');
					$('div.Famille button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
	$('body').on('change', '#Tva', function() {
	 			var Tva =$('#Tva').val(); <?php //echo $row["IdVille"];?>
				if(Tva!="") {
					$('div.Tva').removeClass('erroer');
					$('div.Tva button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
 });	
	</script>
<link href="css/jquery.filer.css" rel="stylesheet">
<!--link href="css/jquery.filer-dragdropbox-theme.css" rel="stylesheet"-->
<style>

.jFiler-input-choose-btn.blue {
    color: #48A0DC;
    border: 1px solid #48A0DC;
}
.jFiler-input-choose-btn {
    display: inline-block;
    padding: 8px 14px;
    outline: none;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    white-space: nowrap;
    font-size: 12px;
    font-weight: bold;
    color: #8d9496;
    border-radius: 3px;
    border: 1px solid #c6c6c6;
    vertical-align: middle;
    box-shadow: 0px 1px 5px rgba(0,0,0,0.05);
    -webkit-transition: all 0.2s;
    -moz-transition: all 0.2s;
    transition: all 0.2s;
}
.jFiler-input-choose-btn.blue:hover {
    background: #48A0DC;
	color: #fff;
	text-decoration: none;
}
.jFiler-items-grid .jFiler-item-trash-action{
color:#999;
}
.jFiler-items-grid .jFiler-item-trash-action{
text-decoration: none;
}
<?php if($_SESSION['lang'] == 'ar' ){ ?>
.jFiler-items-grid .jFiler-item {
    float: right;
}
<?php } else { ?>
.jFiler-items-grid .jFiler-item {
    float: left;
}
<?php }  ?>
</style>


<div id="resAdd" style="padding:5px;">&nbsp;ddddddd</div>
<form id="formAdd" action="NULL" method="post" enctype="multipart/form-data" name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellspacing="10" cellpadding="8">
        <tr>
		<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['reference']; ?></strong> : </div>
            </td>
            <td>
          <!--  <input class="FormAdd1" type="text" name="DsgTransport" id="DsgTransport" size="25"  control="1" tabindex="6"  />-->
            <input class="FormAdd1" type="text" name="ref"  id="ref" size="30" tabindex="1"  />
            </td>
        		<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['codeBarre']; ?> </strong> : </div>
            </td>
            <td>
          <!--  <input class="FormAdd1" type="text" name="DsgTransport" id="DsgTransport" size="25"  control="1" tabindex="6"  />-->
            <input class="FormAdd1" type="text" name="Codeabarre"  id="Codeabarre" size="30" tabindex="1"  />
            </td>
			
          </tr>

		 <tr>
		 <td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['Dsg']; ?> </strong> : </div>
            </td>
            <td>
          <!--  <input class="FormAdd1" type="text" name="DsgTransport" id="DsgTransport" size="25"  control="1" tabindex="6"  />-->
            <input class="FormAdd1" type="text" name="Designation"  id="Designation" size="30" tabindex="1"  />
            </td>
		 <td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['Gamme']; ?> </strong> : </div>
            </td>
            <td>
            <!--select  name="Famille" id="Famille"  multiple="multiple" tabindex="3" style="width:220px;" class="Select Famille">
				<option>Famille1</option>
				<option>Famille2</option>
			</select-->
			 	<select  name="Famille" id="Famille"  multiple="multiple" tabindex="3" class="Select Famille" style="display:visible;width:220px;">
		
                         <?php $sql = "select IdGamme, Designation from gammes ";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['IdGamme'] ?>"><?php echo $donnees['Designation']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>
			
        	
		
          </tr>
		    <tr>
		  
			<td>
			 <div class="etiqForm" id="" > <?php echo $trad['label']['unite']; ?> <strong></strong> : </div>
            </td>
            <td>
          <!--  <input class="FormAdd1" type="text" name="DsgTransport" id="DsgTransport" size="25"  control="1" tabindex="6"  />-->
            <select  name="Unite" id="Unite"  multiple="multiple" tabindex="3" style="width:220px;" class="Select Unite">
				<option value="piece"><?php echo " ".$trad['label']['Piece']; ?> </option>
				<option value="kg"><?php echo " ".$trad['label']['kg']; ?> </option>
				<option value="g"><?php echo " ".$trad['label']['g']; ?> </option>				
			</select>
            </td>
			  <td><div class="etiqForm" id="" ><strong><?php echo $trad['label']['Colisage']; ?> </strong> : </div>
            </td>
            <td >
			 <input class="FormAdd1" type="text" name="Colisage" value="1"  id="Colisage" size="30" tabindex="1"  />
           
            </td>  
          </tr> 

		  <tr>
		
			<td>
				<div class="etiqForm" id="" ><strong><?php echo $trad['label']['box']; ?> </strong> : </div>
			</td>		
			<td> 
				<input class="FormAdd1" type="text" name="box" value="1"  id="box" size="30" tabindex="1"  />
			</td>	
			<td>
				<div class="etiqForm" id="" ><strong><?php echo $trad['label']['palette']; ?> </strong> : </div>
			</td>		
			<td> 
				<input class="FormAdd1" type="text" name="palette" value="1"  id="palette" size="30" tabindex="1"  />
			</td>	
		
			
			</tr>
			<tr>
        	<td style="vertical-align:text-top " ><div class="etiqForm" id=""><?php echo $trad['label']['image']; ?> <strong></strong> : </div>
            </td>
            <td colspan="3" >
             <div style="width:700px;"> <input type="file" name="files[]" id="filer_input2" multiple="multiple">	 </div>
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

$sqlA = "SELECT a.IdArticle,a.Reference,a.CB,a.Designation,g.Designation as Gamme,PA,a.Unite,a.TVA FROM articles a INNER JOIN gammes g ON a.IdFamille=g.IdGamme where etatSup=1 ";
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	if(isset($_POST['ref']) && ($_POST['ref']!='') )
	{	$sqlA .=" AND a.Reference like ? " ;
	   $params = array("%".$_POST['ref']."%");
	}
	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes;
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "IdArticle";
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
//echo $sql."<br>";
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
	  <td width="15%"><?php echo $trad['label']['reference'] ; ?></td>		
        <td width="15%"><?php echo $trad['label']['codeBarre'] ; ?></td>
        <td width="20%"><?php echo $trad['label']['Dsg'] ; ?></td>
		<td width="15%"><?php echo $trad['label']['Gamme'] ; ?></td>
		<td width="10%"><?php echo $trad['label']['unite'] ; ?></td>
        <td width="10%" colspan="2">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="     " onClick="actionSelect();" style="cursor:pointer;border:0px;padding:5px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td>
  </tr>

<!--<div id="cList">-->
	<?php
		$i=0;
	
		//while($row = mysql_fetch_array($resAff)){
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $c; ?>">
				<td align="<?php echo $_SESSION['align'] ; ?>" > <?php 	echo $row['Reference'];?> </td>	
				<td align="<?php echo $_SESSION['align'] ; ?>"  > <?php echo $row['CB']; ?> </td>
									
				<td align="<?php echo $_SESSION['align'] ; ?>" > <?php 	echo $row['Designation'];?> </td>
				<td align="<?php echo $_SESSION['align'] ; ?>"  > <?php echo $row['Gamme']; ?> </td>
		     	<td align="<?php echo $_SESSION['align'] ; ?>"  ><?php 
									if(strtolower($row['Unite'])=="kg") echo $trad['label']['kg']; 
									  else  
									if(strtolower($row['Unite'])=="piece")  echo $trad['label']['Piece']; 
									   else  
									if(strtolower($row['Unite'])=="g")  echo $trad['label']['g']; 
								?>  </td>
					
				<!--td align="center">
					<span class="boutons"> 
						<input type="button" title="Modifier" action="mod" class="b" onClick="modifier('<?php echo $row['IdArticle']; ?>');" />  
					</span>
			  </td-->			
			  <td colspan="2" align="center">
				<input type="checkbox" class="checkLigne" name="<?php echo $row['IdArticle']; ?>" value="<?php echo $row['IdArticle']; ?>" />
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
				
					jConfirm('<?php echo $trad['msg']['msgSuppArticle']; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {
						if(r)	{
							$('input#CLETABLE').attr("value",idSelect);
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'article.php?delPlusieursArticle',clearForm:false});		
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
   <?php echo $trad['Menu']['gestionarticle']; ?> &nbsp;&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="101%" border="0" align="center" >
				  <tr>
					<td width="23%" valign="middle">
					<div class="etiqForm" id="SYMBT" ><?php echo $trad['label']['reference']; ?>  : </div>				</td>
					<td width="30%">
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					<div align="<?php echo $_SESSION['align'] ; ?>">
				<input class="formTop"  name="ref" id="ref" type="text" size="30" />	
				</div>
										</td>
				  <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?> " />
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
				  <option value="IdArticle">  </option>
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
<div id="boxArticle"> </div>
<script language="javascript" type="text/javascript">

	   
$(document).ready(function(){	
  		$('#formRes').load('article.php?aff');
				$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	880,
					height			:	620,
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
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'article.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'article.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='article.php?add';	
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='article.php?mod&ID='+id;
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
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
                                                Designation: "required",
												Famille:"required",
												Codeabarre : "required",
												Colisage: "required",
												box:"required",
												palette:"required",
												ref:"required"
                                          }     });	
	var test=$(form).valid();
	verifSelect2('Famille');
			
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['terminerOperation']; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'article.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'article.php?goAdd',
														method			:	'post'
													}); 
														//alert('add');
												
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
</script>



<?php
include("footer.php");
?>
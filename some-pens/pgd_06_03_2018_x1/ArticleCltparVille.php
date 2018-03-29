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
$IdDepot="1";
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
					alert('Cin déja existant.');
				</script>
	<?php
	return;
	}
		$reqInser1 = "INSERT INTO ".$tableInser." ([nom],[prenom],[telephone],[adresse],[mail],[cin],[idDepot])
		values 	(?,?,?,?,?,?,?)";

		$params1= array(
		addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Prenom']), 'UTF-8')),
		$_POST['Tel'],
		addslashes(mb_strtolower(securite_bdd($_POST['Adresse']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Mail']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Cin']), 'UTF-8')),
		1) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					alert('L\'ajout a été effectué.');
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

<div id="resAdd" style="padding:5px;">&nbsp;</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="8">
        <tr>
        	<td>
			 <div class="etiqForm" id="" ><strong>Nom  </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Nom"  id="Nom" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong>Prenom</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Prenom"  id="Prenom" size="30" tabindex="1"  />
            </td>
		</tr>

		 <tr>
		 <td><div class="etiqForm" id="" > <strong>Adresse </strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="Adresse"  id="Adresse" size="30" tabindex="1"  />
            </td>
		<td><div class="etiqForm" id="" > <strong>Cin</strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="Cin"  id="Cin" size="30" tabindex="1"  />
            </td>			
          </tr>
		<tr>
			<td>
			 <div class="etiqForm" id="" > <strong>Téléphone</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Tel"  id="Tel" size="30" tabindex="1"  />
            </td>
		<td><div class="etiqForm" id="" ><strong>Mail </strong> : </div>
            </td>
            <td>
            	<input class="FormAdd1" type="text" name="Mail"  id="Mail" size="30" tabindex="1"  />
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

/******************************* Info Vendeur + Vehicule + Secteur affectés au vendeur **************************************/
$sql = "SELECT d.iddepartment,d.Designation, count(*) AS NbrClt FROM clients c 
		INNER JOIN departements d ON d.iddepartment=c.departement
		WHERE d.idVille=".$_POST['Ville']."
		GROUP BY d.iddepartment,d.Designation";

	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$params = array();
	$stmtA=sqlsrv_query($conn,$sql,$params,$options);
	$nRes = sqlsrv_num_rows($stmtA);
	
	if($nRes==0)
	{
	?>
		<div class="resAff">
			<br><br>
			<?php echo $trad['msg']['AucunResultat']; ?>
			<script type="text/javascript"> 					
				$('#preload').dialog('close');
			</script>	
		</div>
	<?php
	}
	else
	{
	$Secteurs="";	$DataArticles="";
		while($row = sqlsrv_fetch_array($stmtA, SQLSRV_FETCH_ASSOC)){		
			$Secteurs .="{ name: '".addslashes($row['Designation'])."', y: ".$row['NbrClt'].", drilldown: '"
			.addslashes($row['Designation'])."' },";
			
			
				$req = "SELECT top 10 a.IdArticle,a.Designation,isnull(sum((CASE WHEN df.type = '' THEN 1 ELSE df.type END ) * (df.qte)),0) AS qte 
					FROM factures f 
					INNER JOIN detailFactures df ON f.IdFacture=df.idFacture
					INNER JOIN articles a ON df.idArticle=a.IdArticle
					INNER JOIN visites v ON f.visite=v.idvisite
					INNER JOIN clients c ON c.IdClient=v.idClient
					INNER JOIN departements d ON d.iddepartment=c.departement
					WHERE  d.iddepartment=".$row['iddepartment']."
					GROUP BY a.IdArticle,a.Designation
					ORDER BY qte DESC";
			$stmt1 = sqlsrv_query( $conn, $req ,array(),array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if( $stmt1 === false ) 
			{
											$errors = sqlsrv_errors();
											echo "Erreur : ".$errors[0]['message'] . " <br/> ";
											return;
			}
				$NbrR = sqlsrv_num_rows($stmt1);
				if($NbrR != 0 )
				{
					$DataArticles.=" { name: '".addslashes($row['Designation'])."', id: '".addslashes($row['Designation'])."', data: [ ";
					while($ligne = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){		
						$DataArticles.="['".$ligne['Designation']."',".$ligne['qte']."],";
					}
					$DataArticles=substr($DataArticles, 0, -1);
					$DataArticles.="] },";
				}
			}
		$Secteurs=substr($Secteurs, 0, -1);	
		$DataArticles=substr($DataArticles, 0, -1);	
//echo 		$Secteurs .'<br/>';
//echo 		$DataArticles .'<br/>';
		?>
	
		<script type="text/javascript"> 					
			$('#preload').dialog('close');
		</script>	
		<?php
	

?>
<table width="100%" border="0"  style="direction:ltr;" align="center">
  		<tr>
			<td><!-- colspan="2"-->
			<div class="titreG"></div>
			
			</td>
		</tr>
		<tr>
			<td width="40%">
            <!--       <input name="button" type="button" style="display:block" class="bouton32" tabindex="10" id="ExportG" value="Exporter" />-->
            <div id="graphG"></div>
			</td>

		</tr>
		
	   </table>
<style>
.highcharts-axis-title tspan  {
font-size:20px;
}
.highcharts-button tspan{
font-size:18px;
}
.highcharts-title tspan{
font-size:24px;
}
</style>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
// Create the chart
// Create the chart
  var defaultTitle = "<?php echo $trad['label']['NbrClientParSecteur'] ; ?>";
  var drilldownTitle = "<div><?php echo $trad['label']['articleplusConsommee'] . " " . $trad['label']['le'] . "   </div>". "<br/>"   ; ?>";//"More about ";
  var yaxisDefault="<?php echo $trad['label']['nbrClt'] ; ?>";
  var yaxisdrilldown="<?php echo $trad['label']['Qte'] ; ?>"; 
    Highcharts.setOptions({
        lang: {
            drillUpText: '<?php echo $trad['label']['retour'] ; ?>  '
        }
    });
	
    var chart = new Highcharts.Chart({
        chart: {
		renderTo: 'graphG',
			plotBackgroundColor: null,
			 plotBorderWidth: null,
			 plotShadow: false,
        type: 'column',
		   events: {
                drilldown: function(e) {
                    chart.setTitle({ text: drilldownTitle + e.point.name });
					chart.yAxis[0].axisTitle.attr({ text: yaxisdrilldown });
                },
                drillup: function(e) {
                    chart.setTitle({ text: defaultTitle });
					chart.yAxis[0].axisTitle.attr({ text: yaxisDefault });					
                }
            }
    },
    title: {
        text: defaultTitle
    },
    subtitle: {
        text: ''/*Click the columns to view versions*/
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: yaxisDefault
        }
    },
    legend: {
        enabled: false
    },credits: {enabled: false	},
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
       headerFormat:'',  //'<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/> '//  //of total
    },

    series: [{
        name: '',
        colorByPoint: true,
        data: [<?php echo $Secteurs ; ?>]
    }],
    drilldown: {
        series: [<?php echo $DataArticles ; ?>]
		  
		 /*{name: 'Maarif ancien', 
		 id: 'Maarif ancien', 
		 data: [ ['vanilla cream 60g',1],['digestive 340g',1],['digestive 230g',1],['banana cream 68g',1],['banana cream 45g',1],['banana cream 60g',1]]  }*/
          /*  name: 'Maarif ancien',
            id: 'Maarif ancien',
            data: [ <?php echo $DataArticles ; ?> ]*/
       
	}	
		
});


/*Highcharts.chart('graphG', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Browser market shares. January, 2015 to May, 2015'
    },
    subtitle: {
        text: 'Click the columns to view versions. Source: <a href="http://netmarketshare.com">netmarketshare.com</a>.'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total percent market share'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [<?php echo $secteurs; ?>]
    }],
    drilldown: {
        series: [<?php echo $DataArticles; ?>]
    }
});	*/

});	
	</script>
<?php

}//Else 



exit;
}
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<?php
include("header.php");
?>
<div class="contenuBack">
<div id="brouillon" style="display:block">  </div> 
<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['statistic']; ?>&nbsp;<img src="images/tri.png" />
    &nbsp;&nbsp;<?php echo $trad['Menu']['ArticleClt']; ?> </div>

	<form id="formRechF" method="post"  name="formRechF"> 
		<div id="formRech" style="">	
			<table width="80%" border="0" cellpadding="5" align="center" >
			
				  <tr>
					<td width="23%" valign="middle">
					<div class="etiqForm" id="SYMBT" ><strong><?php echo $trad['label']['ville']; ?>:</strong> </div>				</td>
					<td width="30%">
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					<div align="<?php $_SESSION['align'] ; ?>">
					<select  name="Ville" id="Ville"  multiple="multiple" tabindex="3" class="Select Ville" style="display:visible;width:220px;">
		
                         <?php $sql = "select idville, Designation from villes ";
							   $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );  
							   $i=0;	
							    while ($donnees =  sqlsrv_fetch_array($reponse))
                                {
									if($i==0)
									{
									?>
									   <option style="text-align:right" selected="selected" value="<?php echo $donnees['idville'] ?>"><?php echo $donnees['Designation']?></option>
									 <?php
									}else{
									 ?>
										<option style="text-align:right" value="<?php echo $donnees['idville'] ?>"><?php echo $donnees['Designation']?></option>
									 <?php
									} 
								$i++;
								}	

                         ?>
				</select>

			
				</div>
				</td>
			 <td width="22%" >	
				<span class="actionForm">      
					<input name="button" type="button" onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>"class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?> " />

				</span><br/>
			</td>
				  <!--td width="25%" rowspan="2"   style="border-left:1px solid #778;"><span class="actionForm">
					<input name="button3" type="button" title="Ajouter " class="bouton32" onClick="ajouter();" value="Ajouter" action="ajout" />
				  </span></td-->	
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
	<!--div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div-->

<div id="formRes" style="overflow-y:scroll;width:100%; min-height:400px; max-height:600px;"></div><!--overflow-y:scroll;-->
<input type="hidden" id="act"/>
</div>
<div id="boxClient"> </div>
<script language="javascript" type="text/javascript">

	   
$(document).ready(function(){	
$('#Ville').multipleSelect({
filter: true,placeholder:'<?php echo $trad['label']['selectVille']; ?>',single:true,maxHeight: 100
});

				$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	850,
					height			:	350,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Ajout / Modification du vendeur',
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
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'ArticleCltparVille.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
$('#preload').html('<center><?php echo $trad['msg']['patienter'] ; ?><br/><br/><img src="images/loading.gif" /></center>').dialog('open');
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'ArticleCltparVille.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='ArticleCltparVille.php?add';	
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='ArticleCltparVille.php?mod&ID='+id;
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
                                                Nom: "required",
												Prenom:"required",
												Adresse:"required",
												Cin:"required",
												Tel: "required tel",
												Mail:{
														"required": true,
														"email": true
													 }
                                        }  ,
								messages : {
												Mail:" "  
										    }
		});
	var test=$(form).valid();

		if(test==true){		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'ArticleCltparVille.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'ArticleCltparVille.php?goAdd',
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
<?php 
include("php.fonctions.php");
include("class.uploader.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
session_start();
include("lang.php");
$tableInser = "affectations";
$cleTable = "idaffectation";
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

$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
/**********************Controle doublon****CB************************/
$sql = "SELECT * FROM affectations WHERE idVendeur=? and idVehicule=? ";
$param= array($_POST['Vendeur'],$_POST['Vehicule']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql , $param , $options );
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
							alert('Ce véhicule est déja affecté à ce vendeur .');
							</script>
						
							<?php
							return;
	}
//-----------------Update Affectation----------------//

$reqUpdate1 = "Update ".$tableInser." set [idVendeur]=? ,[idVehicule]=?";

$params1= array($_POST["Vendeur"],$_POST["Vehicule"]) ;

$stmt1 = sqlsrv_query( $conn, $reqUpdate1, $params1 );
if( $stmt1 === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message'] . " <br/> ";
}

if( $error=="" ) {
     sqlsrv_commit( $conn );
?>
		<script type="text/javascript"> 
			alert('le modification a été effectué.');
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
if(isset($_GET['goAdd'])){
/*print_r($_POST);
return;*/

$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
/**********************Controle doublon****CB************************/
$sql = "SELECT idaffectation FROM affectations WHERE idVendeur=? and idVehicule=? ";
$param= array($_POST['Vendeur'],$_POST['Vehicule']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql ,$param,$options);
if( $stmt === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : ".$errors[0]['message']  . " <br/> ";
	echo $error;return;
}
$count = sqlsrv_num_rows($stmt);
$IdAffectation ="";
	if($count >0)//*****************Already Exist affectation
	{
      sqlsrv_fetch($stmt) ;
      $IdAffectation = sqlsrv_get_field( $stmt, 0);
	}else{////***********************Add new Affectation
	//-----------------Add Affectation----------------//
		//parcourir($_POST);return;
		$reqInser1 = "INSERT INTO ".$tableInser." ([idVendeur] ,[idVehicule],idDepot) values (?,?,?)";

		$params1= array($_POST["Vendeur"],$_POST["Vehicule"],$IdDepot) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}
		//---------------------------IDaffectation--------------------------------//
		$sql = "SELECT max(idaffectation) as idaffectation FROM affectations";
		$stmt2 = sqlsrv_query( $conn, $sql );
		if( $stmt2 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
		}
		sqlsrv_fetch($stmt2) ;
		$IdAffectation = sqlsrv_get_field( $stmt2, 0);
	}
	
	
foreach($_POST['Secteur2'] as $depart){/*********************Pour chaque departement**********************************/
	/*********Verification si il est deja affecté au vendeur sélectionné********/
	$sql = "SELECT * FROM affectations a INNER JOIN vendeurs v ON v.idVendeur = a.idVendeur INNER JOIN detailAffectations da ON da.idaffectation = a.idaffectation INNER JOIN departements d ON d.iddepartment =da.idDepartement INNER JOIN vehicules v2 ON a.idVehicule=v2.idVehicule WHERE v.idvendeur=? and v2.idVehicule=? and d.iddepartment=?";
	$param2= array($_POST['Vendeur'],$_POST['Vehicule'],$depart);
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt3 = sqlsrv_query( $conn, $sql ,$param2,$options);
	if( $stmt3 === false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
		echo $error;return;
	}
	$count = sqlsrv_num_rows($stmt3);
	
		if($count ==0)//*****************Already Exist affectation
		{
			$reqInser3 = "INSERT INTO detailAffectations([idaffectation],[idDepartement],[idDepot] ) values (?,?,?)";
				$params3= array($IdAffectation,$depart,$IdDepot) ;
				$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3);
				if( $stmt4 === false ) {
					$errors = sqlsrv_errors();
					$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
					break ;
				}
		}
		
}

if( $error=="" ) {
     sqlsrv_commit( $conn );
?>
		<script type="text/javascript"> 
			alert('<?php echo $trad['msg']['messageAjoutSucces'] ;?>');
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
	$sql = "select * from affectations where idaffectation=? ";
	$param= array($ID);
	$stmt = sqlsrv_query( $conn, $sql ,$param);
	if( $stmt === false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message']  . " <br/> ";
		echo $error;return;
	}

 $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
?>
	<div id="resMod" style="padding:5px;">&nbsp;</div>
	<form id="formMod" action="NULL" method="post" name="formAdd1"> 
		
		<table width="100%" border="0" align="center" cellpadding="5">
       
		 <tr>
		 <td><div class="etiqForm" id="" > <strong>Vendeur </strong> : </div>
            </td>
            <td>
            <!--select  name="Famille" id="Famille"  multiple="multiple" tabindex="3" style="width:220px;" class="Select Famille">
				<option>Famille1</option>
				<option>Famille2</option>
			</select-->
			 	<select  name="Vendeur" id="Vendeur"  multiple="multiple" tabindex="3" class="Select Vendeur" style="display:visible;width:220px;">
		
                         <?php $sql = "SELECT v.idVendeur ,v.nom+ ' ' + prenom AS Nom FROM vendeurs v";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>
			 
          </tr>
		   <tr>
        	
			<td><div class="etiqForm" id="" ><strong>Véhicule </strong> : </div>
            </td>
            <td>
                <select  name="Vehicule" id="Vehicule"  multiple="multiple" tabindex="3" class="Select Vehicule" style="display:visible;width:220px;">
		
                      <?php $sql = "SELECT idVehicule,immatriculation + ' ' + Designation as Lib FROM vehicules";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVehicule'] ?>"><?php echo $donnees['Lib']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>   
			
          </tr> 
		  		
		
		 	  
		<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
	 <script type="text/javascript">
$('#Vehicule').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le véhicule ',single:true,maxHeight: 100
});
$('#Vendeur').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le vendeur ',single:true,maxHeight: 100
});	
	$(document).ready(function(){	
	$("#Vehicule").multipleSelect("setSelects", 	[<?php echo  $row["idVehicule"];?>]);
	$("#Vendeur").multipleSelect("setSelects", 	[<?php echo  $row["idVendeur"];?>]);	
	});
	</script>
    <script src="js/jquery.validerForm.js" type="text/javascript"></script>    
 <?php 
exit;
}
if(isset($_GET['chargerSecteur'])){

	$Options = '<select multiple="multiple" name="Secteur2[]" id="Secteur2" class="Select Secteur2"  tabindex="3" style="width:280px" >';
	$sql = "SELECT d.iddepartment,d.codeDepartement,d.Designation FROM departements d where idVille=?";
			//echo $sql. $_GET['IdZone']; return;
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['IdVille']), array( "Scrollable" => 'static' ) );         
			/*   if( $reponse === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}*/
			
		$nRes = sqlsrv_num_rows($reponse);
		
		if($nRes != 0)
		 while ($donnees =  sqlsrv_fetch_array($reponse))
            {
				$Options.="<option value='".$donnees['iddepartment']."'>".$donnees['Designation']."</option>";			   
			}
		
		$Options.="</select>";
?>
				
	<script language="javascript" type="text/javascript">

		$('#Secteur2').multipleSelect({
		  filter: true,placeholder:'<?php echo $trad['map']['selectSecteur'];?> ',single:false,maxHeight: 100,
		    selectAllText:'<?php echo $trad['label']['selectTous'] ; ?> ',allSelected:'<?php echo $trad['label']['tousSelect'] ; ?> ',
		      onClick: function(view) {
				
				var Secteur2 =$('#Secteur2').val();
				if(Secteur2!="") {
					$('div.Secteur2').removeClass('erroer');
					$('div.Secteur2 button').css("border","1px solid #ccc").css("background","#fff");
				}
            }
		});

	</script>
	<?php
			echo $Options;
exit;
}
if(isset($_GET['chargerVille'])){

			$Options = '<select multiple="multiple" name="Ville2" id="Ville2" class="Select Ville2"  tabindex="3" style="width:280px" >
						';
			$sql = "select idville, Designation from villes where idRegion=?";
			//echo $sql. $_GET['IdZone']; return;
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['idRegion']), array( "Scrollable" => 'static' ) );         
      /*   if( $reponse === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}*/
			
		$nRes = sqlsrv_num_rows($reponse);
		
		if($nRes != 0)
		 while ($donnees =  sqlsrv_fetch_array($reponse))
            {
				$Options.="<option value='".$donnees['idville']."'>".$donnees['Designation']."</option>";			   
			}
		
		$Options.="</select>";
?>
				
	<script language="javascript" type="text/javascript">
$("#Secteur2").multipleSelect("uncheckAll");
$("#Secteur2").multipleSelect("disable");
		$('#Ville2').multipleSelect({
		  filter: true,placeholder:'<?php echo $trad['map']['selectVille'];?> ',single:true,maxHeight: 100,
		      onClick: function(view) {
				if(view.checked = 'checked')
				$('#Secteurs').load("affectation.php?chargerSecteur&IdVille="+view.value);
			   //  alert(view.label + '(' + view.value + ') ' + (view.checked ? 'checked' : 'unchecked'));
		   				
				var Ville2 =$('#Ville2').val();
				if(Ville2!="") {
					$('div.Ville2').removeClass('erroer');
					$('div.Ville2 button').css("border","1px solid #ccc").css("background","#fff");
				}
            }
		});

	</script>
	<?php
			echo $Options;
exit;
}
if(isset($_GET['chargerRegion'])){

			$Options = '<select multiple="multiple" name="Region2" id="Region2" class="Select Region2"  tabindex="3" style="width:280px" >
						';
			$sql = "select idRegion, Designation from regions where idZone=?";
			//echo $sql. $_GET['IdZone']; return;
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['IdZone']), array( "Scrollable" => 'static' ) );         
        /* if( $reponse === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}*/
			
		$nRes = sqlsrv_num_rows($reponse);
		
		if($nRes != 0)
		 while ($donnees =  sqlsrv_fetch_array($reponse))
            {
				$Options.="<option value='".$donnees['idRegion']."'>".$donnees['Designation']."</option>";			   
			}
		
		$Options.="</select>";
?>
				
<script language="javascript" type="text/javascript">
$("#Ville2").multipleSelect("uncheckAll");
$("#Ville2").multipleSelect("disable");
$("#Secteur2").multipleSelect("uncheckAll");
$("#Secteur2").multipleSelect("disable");
		$('#Region2').multipleSelect({
		  filter: true,
		  placeholder:'<?php echo $trad['label']['selectRegion'];?> ',
		  single:true,
		  maxHeight: 100,
		selectAllText:'<?php echo $trad['label']['selectTous'] ; ?> ',allSelected:'<?php echo $trad['label']['tousSelect'] ; ?> ',
		      onClick: function(view) {
				if(view.checked = 'checked')
				$('#Villes').load("affectation.php?chargerVille&idRegion="+view.value);
				
				var Region2 =$('#Region2').val();
				if(Region2!="") {
					$('div.Region2').removeClass('erroer');
					$('div.Region2 button').css("border","1px solid #ccc").css("background","#fff");
				}
			   //  alert(view.label + '(' + view.value + ') ' + (view.checked ? 'checked' : 'unchecked'));
            }
		});
</script>
	<?php
			echo $Options;
exit;
}
if (isset($_GET['add'])){
?>

<script language="javascript" type="text/javascript">
$('#Vehicule').multipleSelect({
filter: true,placeholder:' <?php echo $trad['label']['SelectVehicule'];?> ',single:true,maxHeight: 200
});
$('#Vendeur').multipleSelect({
filter: true,placeholder:'<?php echo $trad['label']['SelectVendeur'];?>  ',single:true,maxHeight: 200
});	

$('#Zone').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['selectZone'];?>',single:true,maxHeight: 100,
	    onClick: function(view) {
				if(view.checked = 'checked')
				$('#Regions').load("affectation.php?chargerRegion&IdZone="+view.value);
			   //  alert(view.label + '(' + view.value + ') ' + (view.checked ? 'checked' : 'unchecked'));

            }
});

$('#Region').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['selectRegion'];?> ',single:true,maxHeight: 100
});
$('#Ville').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['map']['selectVille'];?>  ',single:true,maxHeight: 100
});
$('#Secteur').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectSecteur'];?> ',single:false,maxHeight:180, position: 'top'
});	
$("#Region").multipleSelect("uncheckAll");
$("#Region").multipleSelect("disable");
$("#Ville").multipleSelect("uncheckAll");
$("#Ville").multipleSelect("disable");
$("#Secteur").multipleSelect("uncheckAll");
$("#Secteur").multipleSelect("disable");
$(document).ready(function(){
	 $('body').on('change', '#Vendeur', function() {
	 			var Vendeur =$('#Vendeur').val();
				if(Vendeur!="") {
					$('div.Vendeur').removeClass('erroer');
					$('div.Vendeur button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
	$('body').on('change', '#Vehicule', function() {
	 			var Vehicule =$('#Vehicule').val(); <?php //echo $row["IdVille"];?>
				if(Vehicule!="") {
					$('div.Vehicule').removeClass('erroer');
					$('div.Vehicule button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
	
	$('body').on('change', '#Zone', function() {
	 			var Zone =$('#Zone').val(); <?php //echo $row["IdVille"];?>//alert(Zone);
			
				if(Zone!="") {
					$('div.Zone').removeClass('erroer');
					$('div.Zone button').css("border","1px solid #ccc").css("background","#fff");
				}
				
	 });
	 
 });	
	</script>
<div id="resAdd" style="padding:5px"></div><?php echo $IdDepot;?>
<form id="formAdd" action="NULL" method="post" enctype="multipart/form-data" name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="10" cellspacing="20">
       
		 <tr>
		 <td width="30%"><div class="etiqForm" id="" > <strong><?php echo $trad['label']['Vendeur'];?> </strong> : </div>
            </td>
            <td width="70%">
            <!--select  name="Famille" id="Famille"  multiple="multiple" tabindex="3" style="width:220px;" class="Select Famille">
				<option>Famille1</option>
				<option>Famille2</option>
			</select-->
			  <?php $sql = "SELECT v.idVendeur ,v.nom+ ' ' + prenom AS Nom FROM vendeurs v
						  where idDepot=$IdDepot";?>
			 	<select  name="Vendeur" id="Vendeur"  multiple="multiple" tabindex="3" class="Select Vendeur" style="width:280px;">
                      <?php						  
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td>
			 
          </tr>
		   <tr>
        	
			<td><div class="etiqForm" id="" ><strong><?php echo $trad['frais']['vehicule'];?> </strong> : </div>
            </td>
            <td>
                <select  name="Vehicule" id="Vehicule"  multiple="multiple" tabindex="3" class="Select Vehicule" style="width:280px;">
		
                      <?php $sql = "SELECT idVehicule,immatriculation + ' ' + Designation as Lib FROM vehicules";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVehicule'] ?>"><?php echo $donnees['Lib']?></option>
                         <?php
                          }
                         ?>
				</select>
           </td>   
        </tr> 
		<tr>
        	<td><div class="etiqForm" id="" ><strong><?php echo $trad['Label']['zone'];?> </strong> : </div>
            </td>
            <td>
			<select id="Zone" name="Zone" multiple="multiple"  Class="Select Zone" style="width:280px">
			
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
		<tr>
        	<td><div class="etiqForm" id="" ><strong><?php echo $trad['Label']['region'];?> </strong> : </div>
            </td>
            <td>
			<div id="Regions">
			<select multiple="multiple" id="Region" name="Region" Class="Select Region" style="width:280px">
			</select>
			</div>
			</td>
		</tr>
		<tr>
        	<td><div class="etiqForm" id="" ><strong><?php echo $trad['label']['Ville'];?> </strong> : </div>
            </td>
            <td>
			<div id="Villes">			
			<select multiple="multiple" id="Ville" name="Ville" Class="Select Ville" style="width:280px">
			</select>
			</div>
			</td>
		</tr>
		<tr>
        	<td><div class="etiqForm" id="" ><strong><?php echo $trad['label']['secteur'];?> </strong> : </div>
            </td>
            <td>
			<div id="Secteurs">			
			<select multiple="multiple" id="Secteur" name="Secteur[]" Class="Select Secteur" style="width:280px">
			</select>
			</div>
			</td>
		</tr>
		    <!--tr>
        	
			<td><div class="etiqForm" id="" ><strong>Département </strong> : </div>
            </td>
            <td><?php                              
					/*	  $Options = "";
				          	
					
								
						$req="SELECT v.Designation AS ville , v.idville,d.Designation as departement, d.iddepartment  FROM villes v INNER JOIN departements d ON v.idville=d.idVille";		
			
						   $res = sqlsrv_query( $conn, $req, array(), array( "Scrollable" => 'static' ) );	
						   if( $res === false )  
						{  
							  if( ($errors = sqlsrv_errors() ) != null)  
							  {  
								 foreach( $errors as $error)  
								 {  
									echo "SQLSTATE: ".$error[ 'SQLSTATE']."\n";  
									echo "code: ".$error[ 'code']."\n";  
									echo "message: ".$error[ 'message']."\n";  
								 }  
							  }  
						}  
						   
						   $i=0;$s="";  
						  
						   
				 if(sqlsrv_num_rows($res) !=0){
							   $i=0;
						
						
						$groups = array();
						$i=0;
							 while($row=sqlsrv_fetch_array($res)){
							
								$key = $row['idville'];
								$i=$i+1;
								if (!isset($groups[$key])) {
									$groups[$key] = array();
									$groups[$key]['idville']=$row['idville'];
									$groups[$key]['ville']=$row['ville'];
									
								} //else {
								if($row['idville']!=""){
									$groups[$key][$i]['iddepartment'] = $row['iddepartment'];
									$groups[$key][$i]['departement'] = $row['departement'];
								}
							}

			
				//	parcourir($groups);
					foreach($groups as $u=>$v){
							
								$Options.= '<optgroup label='.$v['ville'].'>';
								
								
									foreach($v as $r){
									if(is_array($r)){
										$Options.= "<option value='".$r['iddepartment']."' >".$r['departement']."</option>";
									}
								}
						$Options.= "</optgroup>";
					 }
			 }
			

                                     */      ?>
			 <select multiple="multiple" id="Departement" name="Departement[]" Class="Select Departement" style="width:280px">
				<?php //echo   $Options;?>
			</select>
            </td>   
			
          </tr--> 		
		
		 	  
		<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
	
	<!-- Styles Js -->
	
	
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){

$sqlA = "SELECT a.idaffectation,v.codeVendeur,v.nom + ' ' + v.prenom as Nom , v2.immatriculation,v2.Designation as vehicule
,d.Designation as departement ,v3.Designation ville,v3.idville
 FROM affectations a 
 INNER JOIN vendeurs v ON v.idVendeur = a.idVendeur 
 INNER JOIN vehicules v2 ON v2.idVehicule = a.idVehicule 
 INNER JOIN detailAffectations da ON da.idaffectation = a.idaffectation 
 INNER JOIN departements d ON d.iddepartment =da.idDepartement 
 INNER JOIN villes v3 ON v3.idville=d.idVille where v.idDepot=".$IdDepot;
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	if(isset($_POST['CodeVendeur']) && ($_POST['CodeVendeur']!='') )
	{	$sqlA .=" AND codeVendeur like ? " ;
	   $params = array("%".$_POST['CodeVendeur']."%");
	}
	
	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes;
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "idaffectation";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "DESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = "  ORDER BY a.idaffectation,d.idVille desc ";//LIMIT $min,$max ";
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
        <td width="9%">		<?php echo $trad['label']['CodeVendeur'];?></td>
        <td width="13%"><?php echo $trad['label']['Vendeur'];?></td>
		<td width="8%"><?php echo $trad['frais']['immatriculation'];?></td>
        <td width="8%"><?php echo $trad['frais']['vehicule'];?></td>
<td width="37%"><?php echo $trad['label']['secteur'];?></td>
        <!--td width="10%" colspan="2">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td-->
  </tr>

<!--<div id="cList">-->
	<?php
		
	
		//while($row = mysql_fetch_array($resAff)){
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
								$key = $row['idaffectation'];
								
								if (!isset($groups[$key])) {
									$groups[$key] = array();
									$groups[$key]['idaffectation']=$row['idaffectation'];
									$groups[$key]['codeVendeur']=$row['codeVendeur'];
									$groups[$key]['Nom']=$row['Nom'];
									$groups[$key]['immatriculation']=$row['immatriculation'];									
									$groups[$key]['vehicule']=$row['vehicule'];
									$i=0;
								} 
								$i=$i+1;								
								$ville=$row['idville'];
								if (!isset($groups[$key][$ville])) {
								$groups[$key][$ville]['ville'] = $row['ville'];		
								}
								if($groups[$key][$ville]!=""){
									$groups[$key][$ville][$i]['departement'] = $row['departement'];	
									/*$groups[$key][$i]['ville'] = $row['ville'];					
									$groups[$key][$i]['region'] = $row['region'];
									$groups[$key][$i]['zone'] = $row['zone'];*/									
								}

		}
	//echo '<pre>';	print_r($groups);echo '</pre>';return;
		foreach($groups as $u=>$vnd){
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr class="<?php echo $c; ?>">
				<td align="<?php echo $_SESSION['align'];?>" valign="top"  > <?php echo $vnd['codeVendeur']; ?> </td>
				<td align="<?php echo $_SESSION['align'];?>" valign="top" > <?php 	echo $vnd['Nom'];?> </td>
				<td align="<?php echo $_SESSION['align'];?>" valign="top"  > <?php echo $vnd['immatriculation']; ?> </td>
				<td align="<?php echo $_SESSION['align'];?>" valign="top" > <?php 	echo $vnd['vehicule'];?> </td>
				<td align="<?php echo $_SESSION['align'];?>" valign="top" > 
				<?php 	
				$villes="";$dep="";
								foreach($vnd as $ville){
									if(is_array($ville)){
									$villes=$ville['ville'];
										foreach($ville as $r){
											if(is_array($r)){
											$dep.=$r['departement']." ; ";
											}
										}	
										$dep=substr(rtrim($dep),0,-1);
								echo "<strong>".$villes."</strong>" .": [ ".$dep." ]" . "<br/>";	
								   }
															   
								}

				?> </td>
			    <!--td align="center">
					<span class="boutons"> 
						<input type="button" title="Modifier" action="mod" class="b" onClick="modifier('<?php echo $row['idaffectation']; ?>');" />  
					</span>
			    </td>			
			    <td colspan="2" align="center">
					<input type="checkbox" class="checkLigne" name="<?php echo $row['idaffectation']; ?>" value="<?php echo $row['idaffectation'];?>" />
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
				
					 jConfirm('<?php echo $trad['msg']['ConfirmerSup'] ;?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
						if(r)	{
							$('input#CLETABLE').attr("value",idSelect);
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'affectation.php?delPlusieursArticle',clearForm:false});		
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
<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['parametrage'] ;?>&nbsp;<img src="images/tri.png" />
    &nbsp;<?php echo $trad['Menu']['affvendeur']; ?>&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="100%" border="0" align="center" >
				  <tr>
					<td valign="middle">
					<div class="etiqForm" id="SYMBT" > <?php echo $trad['label']['CodeVendeur']; ?> : </div>				</td>
					<td>
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					<div align="left">
				<input class="formTop"  name="CodeVendeur" id="CodeVendeur" type="text" size="30" />	
				</div></td>
				  <td  rowspan="2" align="center" >	<span class="actionForm">      
	<input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['Rechercher'] ;?>"
	class="bouton32" action="rech" title="<?php echo $trad['button']['Rechercher'] ;?> " />
				<input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider'] ;?>" class="bouton32" action="effacer" 
				title="<?php echo $trad['label']['vider'] ;?>"/></span><br/></td>
				  <td rowspan="2"   style="border-<?php echo $_SESSION['align'];?>:1px solid #778;"><span class="actionForm">
					<input name="button3" type="button" title="<?php echo $trad['button']['Ajouter'] ;?> "
					class="bouton32" onClick="ajouter();" value="<?php echo $trad['button']['Ajouter'] ;?>" action="ajout" />
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
  		$('#formRes').load('affectation.php?aff');

				$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	600,
					height			:	550,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'<?php echo $trad['titre']['AjoutModifAffection'];?>',
					buttons			:	{
						"<?php echo $trad['button']['Fermer'];?>": function(){
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['Enregistrer'];?> "	: function() {
							terminer();						
						}
					 }
			});
  });
	function filtrer(){	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'affectation.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'affectation.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='affectation.php?add';	
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='affectation.php?mod&ID='+id;
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
                                                Vehicule: "required",
												Vendeur:"required",
												Zone:"required",
												Region2:"required",
												Ville2:"required",
												'Secteur2[]':"required",
												Region:"required",
												Ville:"required",
												"Secteur[]":"required"											
                                          }     });	
	var test=$(form).valid();//alert(test);
	verifSelect2('Vehicule');
	verifSelect2('Vendeur');
	
	if($('select[id=Zone]').length)	verifSelect2('Zone');
	if($('select[id=Region]').length)		verifSelect2('Region');
	if($('select[id=Ville]').length)		verifSelect2('Ville');
	if($('select[id=Secteur]').length)		verifSelect2('Secteur');
	
	if($('select[id=Region2]').length)		verifSelect2('Region2');
	if($('select[id=Ville2]').length)		verifSelect2('Ville2');
	if($('select[id=Secteur2]').length)		verifSelect2('Secteur2');
	
	//verifSelect2('Secteur2');		
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['ConfirmerOperation'];?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'affectation.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'affectation.php?goAdd',
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
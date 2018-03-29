<?php 
include("php.fonctions.php");
include("class.uploader.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
session_start();
$tableInser = "affectations";
$cleTable = "idaffectation";
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

		$params1= array($_POST["Vendeur"],$_POST["Vehicule"],1) ;

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
	
	
foreach($_POST['Departement'] as $depart){/*********************Pour chaque departement**********************************/
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
				$params3= array($IdAffectation,$depart,1) ;
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
			alert('L\'ajout a été effectué.');
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
if (isset($_GET['detail'])){

//print_r($_GET);return;

$sqlA = " SELECT fa.idFamille,fa.codeFamille,fa.Designation AS famille,a.Reference, a.Designation  AS article,sum((CASE WHEN df.type = '' THEN 1 ELSE df.type END ) * (df.qte)) AS qte, sum(df.ttc) AS ttc
FROM frais v 
INNER JOIN factures f ON f.visite=v.idvisite 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture
INNER JOIN articles a ON a.IdArticle=df.idArticle
INNER JOIN familles fa ON fa.idFamille=a.IdArticle
WHERE v.idvisite=".$_GET['idvisite']." 
GROUP BY fa.idFamille,fa.codeFamille,fa.Designation ,a.Reference, a.Designation  
ORDER BY a.Designation Asc";
//, DATEDIFF(mi,v.heureDebut,v.heureFin) AS duree_visite,
    $params = array();
	$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
	$resAff = sqlsrv_query($conn,$sqlA,$params,$options) or die( print_r( sqlsrv_errors(), true));
	?>
<form id="formSelec" method="post">
<br/>
	<table width="100%" style="margin:auto; font-size:14px;" border="0" cellpadding="5" cellspacing="2">
	<tr><td><strong>Client : </strong><br/><br/></td><td colspan="2"><strong><?php echo $_GET['nom']; ?></strong><br/><br/></td><td colspan="2"><strong>Durée :<?php echo $_GET['duree']; ?></strong><br/><br/></td></tr>
      <tr class="entete" style="height:20px;">
		<td width="15%"><strong>Réf.Article</strong></td>  
        <td width="28%" ><strong>Article</strong></td>
		<td width="25%" ><strong>Famille</strong></td>
        <td width="10%" ><strong>Qte</strong></td>
        <td width="22%" ><strong>TTC</strong></td>		
	</tr>
	<?php
		$i=0;
		$sum_qte=0;$sum_ttc=0;
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $c; ?>">
				<td align="left" > <?php echo $row['Reference']; ?> </td>
				<td align="left" > <?php echo $row['article'];?> </td>						
				<td align="left" > <?php echo $row['famille'];?> </td>
				<td align="right" > <?php echo $row['qte']; ?> </td>
		     	<td align="right" > <?php echo $row['ttc']; ?> </td>
			  </tr></li>
			 <?php
			$i++;
			$sum_qte+=intval($row['qte']);
			$sum_ttc+=floatval($row['ttc']);
		}
		?>
		<tr  style="background-color:#f9f8f8">
		<td align="right"  colspan="3"><strong>TOTAL :</strong></td>
		<td align="right" ><strong><?php echo $sum_qte; ?></strong></td>
		<td align="right" ><strong><?php echo DHS($sum_ttc); ?></strong></td>		
		</tr>
	</table>
</form>		
<?php		
exit;
}
if (isset($_GET['add'])){
?>

<script language="javascript" type="text/javascript">
$('#Vehicule').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le véhicule ',single:true,maxHeight: 100
});
$('#Vendeur').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le vendeur ',single:true,maxHeight: 100
});	
$('#Departement').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le département ',single:false,maxHeight: 100
});	
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
	$('body').on('change', '#Departement', function() {
	 			var Departement =$('#Departement').val(); <?php //echo $row["IdVille"];?>
				if(Departement!="") {
					$('div.Departement').removeClass('erroer');
					$('div.Departement button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
 });	
	</script>
<div id="resAdd" style="padding:5px"></div>
<form id="formAdd" action="NULL" method="post" enctype="multipart/form-data" name="formAdd1"> 	
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
		    <tr>
        	
			<td><div class="etiqForm" id="" ><strong>Département </strong> : </div>
            </td>
            <td>
              <?php 
			//	echo ChargerSelect("pointvente","Designation","IdPointVente");?>
					<?php                              
						  $Options = "";
				          	
						/*$req="select IdType,Designation, CodeClient, Nom,Prenom from typeclients t 
								inner join clients clt  on clt.IdType=t.IdType
									";	*/
								
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
			

                                           ?>
			 <select multiple="multiple" id="Departement" name="Departement[]" Class="Select Departement" style="width:220px">
				<?php echo   $Options;?>
			</select>
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
		$where="";
		if(isset($_POST['DateD']) && isset($_POST['DateF'])  )
		{
			if($_POST['DateD'] == $_POST['DateF'])
			{ 
			 	 $where.= " where DateFrais = '".($_POST['DateD'])."' ";
			}
			else
			{
				 $where.= " where DateFrais  between  '".($_POST['DateD'])."' and '".($_POST['DateF'])."' ";
			}
		}
		else
		{
		$where=" where DateFrais='".(date('d/m/Y'))."'";
		}

$sqlA = "SELECT idVisite,convert(varchar(50),f.DateFrais,103) DateFrais,v.heureDebut,v.heureFin,sum(f.Gazoil) Gazoil,
	sum(f.Autoroute) Autoroute,sum(f.Divers) Divers,f.DescripDivers,
	count(v.idvisite) NbrVisite,v.datedebut,v.dateFin,c.nom Nom,c.adresse Adresse,
iddepartment 
	 FROM fraisVendeur f
	INNER JOIN visites v ON convert(date,datedebut)=DateFrais
	INNER JOIN clients c ON c.IdClient=v.idClient
	INNER JOIN departements d ON d.iddepartment=c.departement
	INNER JOIN vendeurs ve ON ve.idVendeur = f.idVendeur
		".$where;

    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	if(isset($_POST['Vendeur']) && ($_POST['Vendeur']!='') )
	{	$sqlA .=" AND ve.cin like ? " ;
	   $params = array("%".$_POST['Vendeur']."%");
	}
	$sqlA .="GROUP BY f.DateFrais,idVisite,v.datedebut,v.dateFin,heureDebut,v.heureFin,iddepartment,f.DescripDivers ,
	c.nom,c.adresse";
/*echo $sqlA."<br>";
parcourir($params);*/
	$resAff = sqlsrv_query($conn,$sqlA,$params,$options) or die( print_r( sqlsrv_errors(), true));
	$nRes = sqlsrv_num_rows($resAff);
	/*$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	

	//
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "idvisite";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "ASC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	//$sqlC = " ORDER BY v.datedebut , v.idvisite";//LIMIT $min,$max ";
	$sql = $sqlA;
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
	//echo $nRes;return;
		if($nRes==0)
		{ ?>
					<div class="resAff">
						<br><br>
						Aucun r&eacute;sultat &agrave; afficher.
					</div>
					<?php
		}
	else
	{
		$groups=array();
		
	//	while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){	
		while($row=sqlsrv_fetch_array($resAff)){				
				$key = $row['DateFrais'];
				if (!isset($groups[$key])) {				
											$groups[$key] = array();									
											$groups[$key]['DateFrais']=$row['DateFrais'];
											$groups[$key]['Autoroute']=$row['Autoroute'];
											$groups[$key]['Gazoil']=$row['Gazoil'];
											$groups[$key]['Divers']=$row['Divers'];
											$groups[$key]['DescripDivers']=$row['DescripDivers'];
											// get nbr visite
											if (!isset($groups[$key]['NbrVisite'])){ 
												$groups[$key]['NbrVisite']=1;}
											else $groups[$key]['NbrVisite']+=1;
											
										
										
						$i=0;
						} 
				if ($groups[$key] != "") {		
					$groups[$key][$i]['DateDebut']=$row['datedebut'];
					$groups[$key][$i]['DateFin']=$row['dateFin'];
					$groups[$key][$i]['HeureDebut']=$row['heureDebut'];
					$groups[$key][$i]['HeureFin']=$row['heureFin'];
					$groups[$key][$i]['Nom']=$row['Nom'];
					$groups[$key][$i]['Adresse']=$row['Adresse'];
					}
				$i=$i+1;
			
		}
	
	//parcourir($groups);return;
		
	?>

		<form id="formSelec" method="post">
		<table width="80%" border="4" cellspacing="2" style="margin:auto; border: 1px solid #CCC;" class="">
		<?php 
		$Circul=0;
	$h=0;
		foreach($groups as $u=>$v){	
		$TotalJourne=$v['Gazoil']+$v['Autoroute']+$v['Divers'];
	//parcourir($v);return;
			
		?>
		<tr class="group">
		<td>Le <?php echo $v['DateFrais'];?> </td> <td> Total frais :<?php echo $TotalJourne;?>Dhs 
		<input type="button" title="Détails" id="plus" 
		onClick="detail('<?php echo $h+1;?>');" />
	
		<div class="box" id="<?php echo $h+1;?>"> 
		<table width="80%" style="margin:auto; font-size:14px;" border="3" cellspacing="2">
		  <tr class="entete" style="height:20px;">
			<td width="10%"><strong>Client</strong></td>  
			<td width="10%" ><strong>Durée (min)</strong></td>
		</tr>
		<?php 
		if(is_array($v)){	
		  echo count($v);return;
			for($j=0;$j<count($v);$j++){
				
						/*echo date_sql($r['DateDebut'])." ".$r["HeureDebut"];
						echo "<br>".date("Y-m-d H:i");*/
						/*$Debut= strtotime(date_sql($r['DateDebut'])." ".$r["HeureDebut"]);
						
						if(date_sql($r['DateDebut'])." ".$r["HeureDebut"]==null) $Fin= strtotime(date("Y-m-d H:i:s"));
						else $Fin=strtotime(date_sql($r['DateFin'])." ".$r["HeureFin"]);
						$Circul+=$Fin-$Debut;*/
				?>
			<tr>
				<td align="left">
				<strong>
					<?php echo $r[$j]['Nom'] ." ".$r[$j]['Adresse']; ?>
				</strong> 
				</td>
				<td align="center"> 
				<strong>
				<?php //echo ($visite[$j]['duree_visite']) . " min ";
				// echo secondsToWords($Fin-$Debut) ;
				
				?>
				</strong>  </td>
			</tr>
			
				<?php
				
				}
			} ?>
			</table>
			
		</div>
		</td>
	
	</tr>
		<tr><td>Gazoil:</td><td> <?php echo $v['Gazoil'];?></td> <tr>
		<td>Autoroute:</td><td>  <?php echo $v['Autoroute'];?></td> <tr>
		<td>Divers:</td><td>  <?php echo $v['Divers'];?></td> <tr>
		<td>Nombre de visite:</td><td>  <?php echo $v['NbrVisite'];?> Viste(s)</td> <tr>
		<td>Nombre de secteur visités:</td><td>  <?php //echo $v['NbrSecteur'];?> Secteur(s)</td> <tr>
		<td>Total circulation:</td><td><?php

	
		
		echo secondsToWords($Circul);
		//echo $v['DateFrais']." ".$v["heureDebut"];
		
	/*
		echo date("Y-m-d H:i")."<br>";
			echo date_sql($v['datedebut'])." ".$v["heureDebut"]."<br>";*/
		
		
	//	echo "$Fin fin <br>";	echo "$Debut debut<br>";echo $Circul."<br>";
			// echo secondsToWords($Circul);
		/*
		
		$Hour2=strtotime($v["heureDebut"]);
		//echo $Hour2."mmmm";
		$Hour1=strtotime($v["heureFin"]); 
		$seconds=$Hour2-$Hour1;*/
	
		?></td> <tr>
		<?php } ?>
		</table>
	
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
					$('.box').dialog({
					autoOpen		:	false,
					width			:	620,
					height			:	380,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Liste Articles',
					buttons			:	{
						"Fermer"		: function(){
							$(this).dialog('close');
							//$('.box').html('');
						}
					 }
			});
		});
	function detail(id){
	//alert('here'+idvisite+ ' nom : ' + nom + ' duree' +duree );
	
		$('.box[id='+id+']').dialog('open');
		//$('#box').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');
	}
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'frais_vendeur.php?delPlusieursArticle',clearForm:false});		
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
<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;Statistiques&nbsp;<img src="images/tri.png" />
    &nbsp;Liste des frais&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="100%" border="0" cellpadding="5" cellspacing="10" align="center" >
				<tr>
				<td width="20%" align="right" valign="middle">
				<strong>Date :</strong>
				</td>
				<td width="22%">
				De
<input class="formTop" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	à
<input name="DATED" type="hidden" value=""/>	
<input class="formTop" g="date" id="DateF" tabindex="2" name="DateF" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	
<input name="DATED" type="hidden" value=""/>	
				</td>
				 <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="Rechercher" class="bouton32" action="rech"
			  title="Rechercher " />
					  <input name="button2" type="reset" onClick="effacer();" value="Effacer" class="bouton32" action="effacer" title="Effacer"/></span><br/></td>
				 
				</tr>
				<tr>
					 <td><div class="etiqForm" id="" > <strong>Vendeur </strong> : </div>
            </td>
            <td>
            <!--select  name="Famille" id="Famille"  multiple="multiple" tabindex="3" style="width:220px;" class="Select Famille">
				<option>Famille1</option>
				<option>Famille2</option>
			</select-->
			 	<select  name="Vendeur" id="Vendeur"  multiple="multiple" tabindex="3" class="Select Vendeur" style="display:visible;width:220px;">
		                 <?php $sql = "SELECT v.cin ,v.nom+ ' ' + prenom AS Nom FROM vendeurs v";
                               $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );  
							    $i=0;					   
                                 while ($donnees =  sqlsrv_fetch_array($reponse))
                                {
								if($i==0)
								{
                                ?>
								   <option selected="selected" value="<?php echo $donnees['cin'] ?>"><?php echo $donnees['Nom']?></option>
								 <?php
								}else{
								 ?>
									<option value="<?php echo $donnees['cin'] ?>"><?php echo $donnees['Nom']?></option>
								 <?php
								} 
								$i++;
								}
								 ?>
				</select>
            </td>
				 
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

<div id="formRes" style="overflow-y:scroll;min-height:900px;"></div>
<input type="hidden" id="act"/>
</div>
<div id="boxArticle"> </div>

<script language="javascript" type="text/javascript">
$('#Vendeur').multipleSelect({
	filter: true,placeholder:'S&eacute;lectionnez le vendeur',single:true,maxHeight: 100
});

$(document).ready(function(){	
		calendrier("DateD");
		calendrier("DateF");
  		//$('#formRes').load('frais_vendeur.php?aff');
rechercher();
				$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	450,
					height			:	380,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Ajout / Modification d\'une affectation',
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
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'frais_vendeur.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'frais_vendeur.php?rech'})
		patienter('formRes');
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='frais_vendeur.php?add';	
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}
function effacer()
	{
	    //$("select#Vendeur").multipleSelect("uncheckAll");
		
	
	}
function modifier(id){
		$('#act').attr('value','mod');
		var url='frais_vendeur.php?mod&ID='+id;
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
												'Departement[]':"required"
                                          }     });	
	var test=$(form).valid();
	verifSelect2('Vehicule');
	verifSelect2('Vendeur');
	verifSelect2('Departement');		
		if(test==true){		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'frais_vendeur.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'frais_vendeur.php?goAdd',
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
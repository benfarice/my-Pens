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

$sqlA = " SELECT g.IdGamme,g.Reference,g.Designation AS gamme,a.Reference, a.Designation AS article,df.UniteVente,sum(df.qte) as qte, sum(df.ttc) AS ttc 
FROM visites v 
INNER JOIN factures f ON f.visite=v.idvisite 
INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
INNER JOIN articles a ON a.IdArticle=df.idArticle 
INNER JOIN gammes g ON a.IdFamille=g.IdGamme 
WHERE v.idvisite=".$_GET['idvisite']."  
GROUP BY g.IdGamme,g.Reference,g.Designation,a.Reference,a.Designation,df.UniteVente
ORDER BY a.Designation Asc";
//echo $sqlA ;
//, DATEDIFF(mi,v.heureDebut,v.heureFin) AS duree_visite,
    $params = array();
	$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
	$resAff = sqlsrv_query($conn,$sqlA,$params,$options) or die( print_r( sqlsrv_errors(), true));
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
								$key= $row['IdGamme'];
								
							 
								
								if (!isset($famille[$key])) {
									$famille[$key] = array();
									$famille[$key]['IdGamme']=$row['IdGamme'];
									$famille[$key]['gamme']=$row['gamme'];									
									
								} 
								$keyArticle= $row['Reference'];
								if (!isset($famille[$key][$keyArticle])) {
									$famille[$key][$keyArticle] = array();	
									$famille[$key][$keyArticle]['Ref']= $row['Reference'];											
									$famille[$key][$keyArticle]['article']= $row['article'];									
									$i=0;
								} 
										if($famille[$key][$keyArticle]!=""){											
												$famille[$key][$keyArticle][$i]['UniteVente']= $row['UniteVente'];
												$famille[$key][$keyArticle][$i]['qte']= $row['qte'];
												$famille[$key][$keyArticle][$i]['ttc']= $row['ttc'];
										}
		   $i=$i+1;
		}
	?>
<form id="formSelec" method="post">
<br/>
	<table width="100%" style="margin:auto; font-size:14px;" border="0" cellpadding="10" cellspacing="4">
	<tr><td><strong><?php echo $trad['label']['client']; ?> : </strong><br/><br/></td><td colspan="2"><strong><?php echo $_GET['nom']; ?></strong><br/><br/></td><td colspan="2"><strong><?php echo $trad['label']['duree']; ?> :<?php echo $_GET['duree']; ?></strong><br/><br/></td></tr>
      <tr class="entete" style="height:20px;">
		<td width="25%" ><strong><?php echo $trad['label']['Gamme'];  ?></strong></td>
		<td width="15%"><strong><?php echo $trad['label']['reference'];  ?></strong></td>  
        <td width="28%" ><strong><?php echo $trad['label']['Article'];  ?></strong></td>
        <td width="10%" ><strong><?php echo $trad['label']['Unite'];  ?></strong></td>		
        <td width="10%" ><strong><?php echo $trad['label']['Qte'];  ?></strong></td>
        <td width="25%" ><strong><?php echo $trad['label']['ValTTC'];  ?></strong></td>		
	</tr>
	<?php
		$i=0;
		$sum_famille_qte=0;$sum_famille_ttc=0;$sum_vnd_qte=0;$sum_vnd_ttc=0;
		foreach($famille as $u=>$fam){
		
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr class="pair" style="height:50px;font-size:14px;">
				<td align="<?php $_SESSION['align'] ; ?>" colspan="6" > <strong> <?php echo $fam['gamme'];?> </strong></td>
			</tr>
				
					    <?php foreach($fam as $article){	
						     if(is_array($article)){ ?>
							<tr style="background-color:#f0f9ff">	
								<td></td>
								<td><?php echo $article['Ref']; ?></td>		
								<td align="<?php $_SESSION['align'] ; ?>" colspan="4"><?php echo $article['article']; ?></td>
								
							</tr>	
							
					    <?php  	foreach($article as $Unite){
									if(is_array($Unite)){	?>
								<tr  style="background-color:#ececec;">
								<td colspan="3"></td>
								<td><?php 
								$unite=($Unite['UniteVente']); 
								
								echo $trad['label'][$unite];
								?></td>	
								<td style="direction: ltr;" align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>">
								<?php echo number_format($Unite['qte'],0," "," "); ?></td>
								<td  style="direction: ltr;" align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>">
								<?php echo number_format($Unite['ttc'],2,"."," "); ?></td>
								</tr>
						<?php	
						$sum_famille_qte+=intval($Unite['qte']);
						$sum_famille_ttc+=floatval($Unite['ttc']);
							}}
						
						}
						} ?>
						<!--Somme Pour chaque famille-->
						<tr style="background-color:#f9f8f8">
						<td colspan="2"></td>
						<td align="right" colspan="2"><strong><?php echo $trad['label']['total']; ?>   <?php echo $fam['gamme']; ?> : </strong></td>
						<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>" style="direction:ltr;"><?php echo number_format($sum_famille_qte,0," "," "); ?></td>	
						<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>" style="direction:ltr;"><?php echo  DHS($sum_famille_ttc); ?></td>						
						</tr>
					<?php $sum_famille_qte=intval(0);$sum_famille_ttc=0;	   ?>

			 <?php 
			$i++;
		}
		//----------------------------------------------------------------------
		?>		
	
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
			 	 //$where.= " where datedebut = convert(date, '".($_POST['DateD'])."',105) ";
				 $where.= " where datedebut = '".($_POST['DateD'])."' ";
			}
			else
			{
				 //$where.= " where datedebut  between  convert(date, '".($_POST['DateD'])."',105) and convert(date, '".($_POST['DateF'])."',105) ";
			$where.= " where datedebut  between  '".($_POST['DateD'])."' and '".($_POST['DateF'])."' ";
			}
		}
		else
		{
		//$where=" where datedebut=convert(date, '".(date('d/m/Y'))."',105)";
		$where=" where datedebut='".(date('d/m/Y'))."'";
		}

$sqlA = " SELECT DISTINCT v.idvisite,v2.cin,v2.idVendeur,c.IdClient,c.intitule as Client, c.adresse ,v.heureDebut,v.heureFin,v.datedebut   FROM visites v 
INNER JOIN factures f ON v.idvisite=f.visite
INNER JOIN vendeurs v2 ON v2.IdVendeur=f.idVendeur
INNER JOIN clients c on v.idClient=c.IdClient ".$where;
//, DATEDIFF(mi,v.heureDebut,v.heureFin) AS duree_visite,
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	if(isset($_POST['Vendeur']) && ($_POST['Vendeur']!='') )
	{	$sqlA .=" AND v2.cin like ? " ;
	   $params = array("%".$_POST['Vendeur']."%");
	}
	
	
	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	
	//echo $sqlA  ;echo " num : ".$ntRes; return;
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
	
	$sqlC = " ORDER BY v.datedebut , v.idvisite";//LIMIT $min,$max ";
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
	<table width="80%" style="margin:auto; font-size:14px; " border="0" cellspacing="2">
      <tr class="entete" style="height:20px;">


	    <td width="10%"><strong><?php echo $trad['label']['client']; ?></strong></td>  
        <td width="10%" ><strong><?php echo $trad['label']['duree']; ?></strong></td>
        <!--td width="10%" colspan="2">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td-->
  </tr>

<!--<div id="cList">-->
	<?php
		$i=0;
	
		//while($row = mysql_fetch_array($resAff)){
		$visite=array();
		$i=0;$nbr=0;
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
		$visite[$i]["Client"]=$row['Client'];
		//$visite[$i]["duree_visite"]=$row['duree_visite'];
		$visite[$i]["heureDebut"]=$row['heureDebut'];	
		$visite[$i]["heureFin"]=$row['heureFin'];	
		$visite[$i]["IdClient"]=$row['IdClient'];
		$visite[$i]["datedebut"]=$row['datedebut'];	
		$visite[$i]["idvisite"]=$row['idvisite'];
		$visite[$i]["adresse"]=$row['adresse'];		
		$i++;
		
		    /*$date=array();
			$key=$visite[$i]["datedebut"];
			if (!isset($date[$key])) {
			$date[$key]=array();
			$date[$key]['datedebut']=$visite[$j]["datedebut"];
			$date[$key]['NbrdeVisite']=$nbr;
			$nbr=0;
			}
			$nbr=++;*/
		}//echo "count : ".$i;
		$date=array();
		$totalDuree=0;
		for($j=0;$j<$i;$j++)
		{
			$key=$visite[$j]["datedebut"];
			if (!isset($date[$key])) {//New Date
			$date[$key]=$visite[$j]["datedebut"];
			 if($j!=0){
			?>
			<tr style="background-color:#797979;color:white">
			<td colspan="2" align="center">
			<strong><?php echo $trad['label']['totalDuree']; ?> : <?php echo  secondsToWords($totalDuree);?></strong>
			</td>
			</tr>	
			<?php $totalDuree=0; } ?>			
			<tr style="background-color:#06709e;color:white">
			<td colspan="2" align="center">
			<strong><?php echo $trad['label']['departJournee']; ?>  <?php echo  $date[$key] ;?></strong>
			</td>
			</tr>
			<?php
			}
				 $HeurD=strtotime($visite[$j]["heureDebut"]);
				 $HeurF=strtotime($visite[$j]["heureFin"]); 
			     $duree=$HeurF-$HeurD;
				 $totalDuree+=intval($duree);
		?>
			<tr>
				<td align="<?php $_SESSION['align'] ; ?>">
				<strong>
					<?php echo $visite[$j]['Client'] ." ".$visite[$j]['adresse']; ?>
					&nbsp;&nbsp;&nbsp;
					<input type="button" title="<?php echo $trad['frais']['details']; ?>" id="plus" 
	onClick="detail('<?php echo $visite[$j]["idvisite"]; ?>',
	'<?php echo urlencode($visite[$j]["Client"]); ?>','<?php echo urlencode(secondsToWords($duree)); ?>');" /> 
				</strong> 
				</td>
				<td align="center"> 
				<strong>
				<?php //echo ($visite[$j]['duree_visite']) . " min ";
				 echo secondsToWords($duree) ;
				
				?>
				</strong>  </td>
			</tr>
		
		<?php if($j <($i-1))
		      {//echo "count " . $j . " " . $i; 
				$Hour2=strtotime($visite[$j+1]["heureDebut"]);
					//echo $Hour2."mmmm";
				$Hour1=strtotime($visite[$j]["heureFin"]); 
				$seconds=$Hour2-$Hour1;
				if($seconds >0)
				{
		?>
			<tr class="pair">
				<td colspan="2" align="center">	 <?php echo $trad['label']['circulation']; ?> :	<?php echo secondsToWords($seconds) ;//. " " .$seconds ; ?></td>
			</tr>
		<?php  }
		     }else if($j ==($i-1)){?>
			 <tr style="background-color:#797979;color:white">
			<td colspan="2" align="center" style="direction:ltr;">
			<strong><?php echo $trad['label']['totalDuree']; ?> : <?php echo  secondsToWords($totalDuree);?></strong>
			</td>
			</tr>	
			 
			<?php } ?>
			
		<?php
		}
		//print_r($date);
		?>			
		<?php 
	
	//	$delaiReel = strtotime($rowM['DateReelRetour']." ".$rowM['HeureReelDepart']) - strtotime($DateDepart." ".$HeureDebutMission);
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'Visite.php?delPlusieursArticle',clearForm:false});		
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
<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['statistic']; ?>&nbsp;<img src="images/tri.png" />
    &nbsp;<?php echo $trad['Menu']['visite']; ?>&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="100%" border="0" cellpadding="5" cellspacing="10" align="center" >
				<tr>
				<td width="20%" align="right" valign="middle">
				<strong><?php echo $trad['label']['date']; ?>  :</strong>
				</td>
				<td width="22%">
				<?php echo $trad['label']['de']; ?>
<input class="formTop" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	<?php echo $trad['label']['a']; ?> 
<input name="DATED" type="hidden" value=""/>	
<input class="formTop" g="date" id="DateF" tabindex="2" name="DateF" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	
<input name="DATED" type="hidden" value=""/>	
				</td>
				 <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech" title="<?php echo $trad['button']['rechercher']; ?> " />
					  <input name="button2" type="reset" onClick="effacer();" value="<?php echo $trad['label']['vider']; ?>" class="bouton32" action="effacer" title="<?php echo $trad['label']['vider']; ?>"/></span><br/></td>
				 
				</tr>
				<tr>
					 <td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['Vendeur']; ?> </strong> : </div>
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
<div id="box"> </div>
<script language="javascript" type="text/javascript">
$('#Vendeur').multipleSelect({
filter: true,placeholder:'S&eacute;lectionnez le vendeur',single:true,maxHeight: 100
});

$(document).ready(function(){	
		calendrier("DateD");
		calendrier("DateF");
  		$('#formRes').load('Visite.php?aff');

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
				$('#box').dialog({
					autoOpen		:	false,
					width			:	820,
					height			:	580,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'<?php echo $trad['label']['listeArticle']; ?>',
					buttons			:	{
						"<?php echo $trad['button']['Fermer']; ?>": function(){
							$(this).dialog('close');
							$('#box').html('');
						}
					 }
			});
  });
	function detail(idvisite,nom,duree){
		//alert('here'+idvisite+ ' nom : ' + nom + ' duree' +duree );
	    $('#act').attr('value','detail'); 
		var url='Visite.php?detail&idvisite='+idvisite+'&nom='+nom+'&duree='+duree;//alert(url);
		$('#box').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');
	}
	function filtrer(){	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'Visite.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'Visite.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='Visite.php?add';	
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}
function effacer()
	{
	    //$("select#Vendeur").multipleSelect("uncheckAll");
		
	
	}
function modifier(id){
		$('#act').attr('value','mod');
		var url='Visite.php?mod&ID='+id;
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
														url				:	'Visite.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'Visite.php?goAdd',
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
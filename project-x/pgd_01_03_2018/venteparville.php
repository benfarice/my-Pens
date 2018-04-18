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
			 	 $where.= " where cast(fa.date AS date) = convert(date,'".($_POST['DateD'])."',105) ";
			}
			else
			{
				 $where.= " where cast(fa.date AS date)  between  convert(date,'".($_POST['DateD'])."',105) and convert(date,'".($_POST['DateF'])."',105) ";
			}
		}
		else
		{
		$where=" where cast(fa.date AS date)=convert(date,'".(date('d/m/Y'))."',105)";
		}

$sqlA = "SELECT v.idville,v.Designation as ville,d.iddepartment, d.Designation as departement,a.IdArticle,a.Reference,a.Designation as article,sum((CASE WHEN df.type = '' THEN 1 ELSE df.type END ) * (df.qte)) AS qte,sum(df.ttc) AS ttc FROM factures fa 
INNER JOIN detailFactures df ON fa.IdFacture=df.idFacture
INNER JOIN articles a ON a.IdArticle=df.idArticle
INNER JOIN clients c ON fa.idClient=c.IdClient
INNER JOIN departements d ON d.iddepartment=c.departement
INNER JOIN villes v ON v.idville=d.idVille ".$where;

    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	/*if(isset($_POST['Cin']) && ($_POST['Cin']!='') )
	{	$sqlA .=" AND v.cin like ? " ;
	   $params = array("%".$_POST['Cin']."%");
	}*/
	$sqlA .=" GROUP BY v.idville,v.Designation,d.iddepartment, d.Designation,a.IdArticle,a.Reference,a.Designation ";
	
	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	
	//echo $sqlA  ;echo " num : ".$ntRes; return;
	//
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "Nom";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "ASC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = " ORDER BY v.Designation, d.Designation,a.Designation Asc";//LIMIT $min,$max ";
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
	<table width="100%" border="0" cellspacing="2">
       <tr class="entete">
	    <td width="10%"><?php echo $trad['label']['ville']; ?></td>  
        <td width="10%"><?php echo $trad['label']['secteur']; ?></td>	
		<td width="15%"><?php echo $trad['label']['reference']; ?></td>
        <td width="15%"><?php echo $trad['label']['Article']; ?></td>
		<td width="10%"><?php echo $trad['label']['qteVendu']; ?> </td>
        <td width="10%"><?php echo $trad['label']['ValTTC'] . '('.$trad['label']['riyal'] .')'; ?> </td>
        <!--td width="10%" colspan="2">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value=""/>
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td-->
       </tr>

<!--<div id="cList">-->
	<?php
		
	//v.cin ,v.nom + ' ' + v.prenom AS 'Nom' ,fa.idFamille,fa.codeFamille,fa.Designation AS famille, a.CB , a.Designation  AS article,sum(df.qte) AS qte, sum(df.ttc) AS ttc ,
		//while($row = mysql_fetch_array($resAff)){
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
								$key_ville = $row['idville'];
//SELECT v.idville,v.Designation as ville,d.iddepartment, d.Designation as departement,a.IdArticle,a.Reference,a.Designation as article,sum(df.qte) AS qte,sum(df.ttc) AS ttc F								
							
								if (!isset($ville[$key_ville])) {
									$ville[$key_ville] = array();
									$ville[$key_ville]['idville']=$row['idville'];
									$ville[$key_ville]['ville']=$row['ville'];
									$i=0;
								} 
								$i=$i+1;
										$key_departement=$row['iddepartment'];
										if (!isset($ville[$key_ville][$key_departement])) {
										$ville[$key_ville][$key_departement]['iddepartment']= $row['iddepartment'];
										$ville[$key_ville][$key_departement]['departement']= $row['departement'];							
										}
											if($ville[$key_ville][$key_departement]!=""){
												$ville[$key_ville][$key_departement][$i]['Ref']= $row['Reference'];
												$ville[$key_ville][$key_departement][$i]['article']= $row['article'];
												$ville[$key_ville][$key_departement][$i]['qte']= $row['qte'];
												$ville[$key_ville][$key_departement][$i]['ttc']= $row['ttc'];
											}
		
		}
	//echo "<pre>";	print_r($vendeur);echo "</pre>";
	$sum_famille_qte=0;$sum_famille_ttc=0;$sum_vnd_qte=0;$sum_vnd_ttc=0;
		foreach($ville as $u=>$vil){
		
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr class="pair" style="height:50px;font-size:14px;">
				<td align="<?php $_SESSION['align'] ; ?>" colspan="7" > <strong> <?php echo $vil['ville'];?> </strong></td>
			</tr>
					<?php foreach($vil as $depart){
						if(is_array($depart)){ ?>
					<tr  class="impair">
						<td></td>	
						<td align="<?php $_SESSION['align'] ; ?>" colspan="6" > <strong><?php echo $depart['departement']; ?></strong></td>					
					</tr>
				
					    <?php foreach($depart as $article){	
						      if(is_array($article)){ ?>
							<tr style="background-color:#f0f9ff">	
								<td colspan="2"></td>
								<td><?php echo $article['Ref']; ?></td>
								<td><?php echo $article['article']; ?></td>
								<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>"><?php echo $article['qte']; ?></td>
								<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>"><?php echo $article['ttc']; ?></td>
							</tr>							
					    <?php  
						$sum_famille_qte+=intval($article['qte']);
						$sum_famille_ttc+=floatval($article['ttc']);
						$sum_vnd_qte+=intval($article['qte']);
						$sum_vnd_ttc+=floatval($article['ttc']);
						}} ?>
						<!--Somme Pour chaque famille-->
						<tr style="background-color:#f9f8f8">
						<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>" colspan="4"><strong><?php echo $trad['label']['total']; ?> <?php echo $trad['label']['secteur']; ?> <?php echo $depart['departement']; ?> : </strong></td>
						<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>"><?php echo $sum_famille_qte; ?></td>	
						<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>" style="direction:ltr;"><?php echo  DHS($sum_famille_ttc); ?></td>						
						</tr>
					<?php $sum_famille_qte=intval(0);$sum_famille_ttc=0;
					    } } ?>
						<tr style="background-color:#e2e2e2">
						<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>" colspan="4" ><strong><?php echo $trad['label']['totalGlobal']; ?> :</strong></td>
						<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>"><?php echo $sum_vnd_qte; ?></td>	
						<td align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>" style="direction:ltr;"><?php echo  DHS($sum_vnd_ttc); ?></td>						
						</tr>
			 <?php $sum_vnd_qte=intval(0);$sum_vnd_ttc=0;
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'venteparville.php?delPlusieursArticle',clearForm:false});		
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
    &nbsp;<?php echo $trad['Menu']['venteVille']; ?>&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="100%" border="0" cellpadding="5" cellspacing="10" align="center" >
				<tr>
				<td width="20%" align="<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>" valign="middle">
				<strong><?php echo $trad['label']['date']; ?> :</strong>
				</td>
				<td width="22%">
				<?php echo $trad['label']['de']; ?>
<input class="formTop" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	<?php echo $trad['label']['a']; ?>
<input name="DATED" type="hidden" value=""/>	
<input class="formTop" g="date" id="DateF" tabindex="2" name="DateF" type="text" size="10" maxlength="10" onChange="verifier_date(this);" value="<?php echo date('d/m/Y'); ?>"/>	
<input name="DATED" type="hidden" value=""/>	
				</td>
				 <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech"	title="<?php echo $trad['button']['rechercher']; ?>" />
					  <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider']; ?>" class="bouton32" action="effacer" title="<?php echo $trad['label']['vider']; ?>"/></span><br/></td>
	
				</tr>
				<!--tr>
					<td  align="right" valign="middle">
					<div class="etiqForm" id="SYMBT" ><strong> Cin :</strong> </div>				</td>
					<td width="30%">
					<div align="left">
				<input class="formTop"  name="Cin" id="Cin" type="text" size="25" />	
				</div>
					</td>
				 
				</tr-->			  
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

<div id="formRes" style="overflow-y:scroll;min-height:480px;"></div>
<input type="hidden" id="act"/>
</div>
<div id="boxArticle"> </div>
<script language="javascript" type="text/javascript">

$(document).ready(function(){	
		calendrier("DateD");
		calendrier("DateF");
  		$('#formRes').load('venteparville.php?aff');

				$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	400,
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
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'venteparville.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'venteparville.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='venteparville.php?add';	
		$('#boxArticle').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='venteparville.php?mod&ID='+id;
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
														url				:	'venteparville.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'venteparville.php?goAdd',
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
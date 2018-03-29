<?php 
include("php.fonctions.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");
session_start();
include("lang.php");
$tableInser = "clients";
$cleTable = "IdClient";
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
//parcourir($_POST);
$Class="";
$idVendeur=5;
if(isset($_POST['Classe']) && ($_POST['Classe']!="")) 	$Class=$_POST['Classe'];
	
		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	    $CodeClt="CL".Increment_Chaine_F("CodeClient","clients","IdClient",$conn,"",array());;
		$reqInser1 = "INSERT INTO ".$tableInser." (CodeClient,[nom] ,[prenom] ,[intitule] ,[adresse] ,[cp],
		[departement],[ville],[rc],patente,[if],formeJ,
						idVendeur,idDepot,idTypeClient,Classe
							) values 	(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$params1= array(
		$CodeClt,
		addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Prenom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Intitule']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Adresse']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['CP']), 'UTF-8')),
		$_POST['Departement'],
		$_POST['Ville'],
		addslashes(mb_strtolower(securite_bdd($_POST['RC']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Patente']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['IF']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['FJ']), 'UTF-8')),
		$idVendeur,
		//addslashes(mb_strtolower(securite_bdd($_POST['Localisation']), 'UTF-8')),
		$IdDepot,
		$_POST['TypeClient'],
		$Class
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
				//	alert('L\'ajout a été effectué.');
				alert('<?php echo $trad['msg']['messageAjoutSucces'] ;?>');
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
$('#Departement').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectSecteur'];?> ',single:true,maxHeight: 100
});
$('#Classe').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectClasse'];?> ',single:true,maxHeight: 100,  position: 'top'
});
$('#Ville').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectVille'];?> ',single:true,maxHeight: 100
});	
$('#TypeClient').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectType'];?>',single:true,maxHeight: 100
});	
	

$(document).ready(function(){

	$('body').on('change', '#Departement', function() {
	 			var Departement =$('#Departement').val(); <?php //echo $row["IdVille"];?>
				if(Departement!="") {
					$('div.Departement').removeClass('erroer');
					$('div.Departement button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
	$('body').on('change', '#Ville', function() {
	 			var Ville =$('#Ville').val(); <?php //echo $row["IdVille"];?>
				if(Ville!="") {
					$('div.Ville').removeClass('erroer');
					$('div.Ville button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
 });	
	</script>
<div id="resAdd" style="padding:5px;">&nbsp;</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="15">
        <tr>
        	<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['Nom'];?>  </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Nom"  id="Nom" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['CodePostal'];?> </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="CP"  id="CP" size="30" tabindex="1"  />
            </td>
			
				<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['IdFisclae'];?> </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="IF"  id="IF" size="30" tabindex="1"  />
            </td>
			
          </tr>

		 <tr>
		 <td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['Prenom'];?> </strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="Prenom"  id="Prenom" size="30" tabindex="1"  />
            </td>
			
        	
		<td><div class="etiqForm" id="" ><strong><?php echo $trad['label']['secteur'];?> </strong> : </div>
            </td>
            <td>
            	<select  name="Departement" id="Departement"   tabindex="3"
				class="Select Departement" style="BORDER:1px solid #ebebeb;display:visible;width:245px;">
		
                         <?php $sql = "select iddepartment, Designation from departements ";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['iddepartment'] ?>"><?php echo $donnees['Designation']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td> 
			<td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['FormJuridique'];?></strong> : </div>
            </td>
            <td>
         <input class="FormAdd1" type="text" name="FJ"  id="FJ" size="30" tabindex="1"  />
            </td>			
          </tr>
		  
		   <tr>
        	<td><div class="etiqForm" id="" > <strong><?php echo $trad['map']['intitule'];?></strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="Intitule"  id="Intitule" size="30" tabindex="1" /> 
            </td>  
			<td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['Ville'];?></strong> : </div>
            </td>
            <td>
             <select  name="Ville" id="Ville"   tabindex="3" style="BORDER:1px solid #ebebeb;display:visible;width:245px;" class="Select Ville">

		
                         <?php $sql = "select idville, Designation from villes ";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idville'] ?>"><?php echo $donnees['Designation']?></option>
                         <?php
                          }
                         ?>
					
			
			</select>
            </td>   
			<td><div class="etiqForm" id="" ><strong><?php echo $trad['label']['type'];?> </strong> : </div>
            </td>
            <td>
            	<select  name="TypeClient" id="TypeClient"   tabindex="3" class="Select TypeClient" 
				style="BORDER:1px solid #ccc;display:visible;width:245px;">
		
                         <?php $sql = "select idType, Designation from typeClients ";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idType'] ?>"><?php echo $donnees['Designation']?></option>
                         <?php
                          }
                         ?>
				</select>
            </td> 
          </tr> 
		  		<tr>
        	<td rowspan="2" valign="top" width="80"><div class="etiqForm" id="" >  <strong><?php echo $trad['map']['adresse'];?></strong> : </div>
            </td>
            <td rowspan="2">
         
			<textarea rows="4" cols="35" name="Adresse"  id="Adresse"></textarea>
            </td>
		   <td>
			 <div class="etiqForm" id="" >  <strong><?php echo $trad['label']['RegistreCommerce'];?></strong> : </div>
            </td>
            <td>
				<input class="FormAdd1" type="text" name="RC"  id="RC" size="30" tabindex="1" /> 
            </td>
				<td style="vertical-align:text-top " ><div class="etiqForm" id=""><strong><?php echo $trad['label']['Patente'];?></strong> : </div>
            </td>
            <td >
            <input type="text" name="Patente" id="Patente" size="30" >	
            </td>  

        </tr>
		<tr>
		<td>
			 <div class="etiqForm" id="" >  <strong><?php echo $trad['label']['Localisation'];?></strong> : </div>
            </td>
            <td>
				<input class="FormAdd1" type="text" name="Localisation"  id="Localisation" size="30" tabindex="1" /> 
            </td>

           			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['classe'];?></strong> : </div>
            </td>
            <td>
			<select name="Classe" id="Classe"  multiple="multiple" tabindex="3" style="width:245px;" class="Select Classe" >
			<option>A</option>
			<option>B</option>
			<option>C</option>
			<option>D</option>	
			<option>E</option>				
			</select>
            </td>
		</tr>
		
		  <tr>
        
          </tr>		  
		<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
	
	<!-- Styles Js -->
	
	
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){

$sqlA = "  SELECT CodeClient,idClient,c.nom,c.prenom,c.adresse,cp,d.designation as departement, c.ville,rc,formeJ,c.[if],
		  t.designation as TypeClient,patente,intitule ,c.ville IdVille,v.Designation as Ville,vd.nom+' '+vd.prenom Vendeur,vd.idVendeur IdVendeur ,
		  vd.codeVendeur,
		  ImgMagasin
		  FROM clients c 
		  INNER JOIN villes v   ON v.idville=c.ville 
		  INNER JOIN typeClients t   ON t.idType=c.idTypeClient 
		  INNER JOIN departements d ON d.iddepartment=c.departement 
		  inner join vendeurs vd on vd.idVendeur=c.idVendeur
			";
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	if(isset($_POST['NomClt']) && ($_POST['NomClt']!='') )
	{	$sqlA .=" AND c.nom like ? " ;
	   $params = array("%".$_POST['NomClt']."%");
	}
	if(isset($_POST['Vendeur']) && ($_POST['Vendeur']!='') )
	{	$sqlA .=" AND c.idVendeur = ?  " ;
	   $params = array($_POST['Vendeur']);
	}
	$sqlA .=" and  vd.idDepot=$IdDepot ";
	//ECHO $sqlA."<br>";
	
	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes."<br>";
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "idClient";
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


/*execSQL($sql);*/
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff = sqlsrv_query($conn,$sql,$params,$options) ;
	if( $resAff === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									?>
							<script language="javascript" type="text/javascript">
									$(document).ready(function(){
										ajaxindicatorstop();
									})
							</script>
							<?php 
							return;
								
								}
	
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
//$('#cont_pages').html('<?php echo $selPages; ?>');
</script>
		<form id="formSelec" method="post">
	<table width="100%" border="0">
      <tr class="entete">
       
	   	<td width="10%"  style="display:none"><?php echo $trad['label']['codeVnd'];?></td>
		<td width="10%"><?php echo $trad['label']['Vendeur'];?></td>
		<td width="10%"><?php echo $trad['label']['Ville'];?></td>
        <td width="15%"><?php echo $trad['label']['Intitule'];?></td>
		<td width="15%"><?php echo $trad['map']['adresse'];?></td>        
		<td width="10%"><?php echo $trad['label']['secteur'];?></td>      
		<td width="10%"><?php echo $trad['label']['type'];?></td>
		<td width="10%"><?php echo $trad['label']['ImgMagasin'];?></td>
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
								$key= $row['IdVendeur'];			
							   								
								if (!isset($Client[$key])) {
									$Client[$key] = array();
									$Client[$key]['IdVendeur']=$row['IdVendeur'];
									$Client[$key]['CodeVendeur']=$row['codeVendeur'];
									$Client[$key]['Vendeur']=$row['Vendeur'];							
								} 							
										
								$keyVille=$row['IdVille'];
								if (!isset($Client[$key][$keyVille])) {
									$Client[$key][$keyVille]= array();
									$Client[$key][$keyVille]['IdVille']= $row['IdVille'];
									$Client[$key][$keyVille]['Ville']= $row['Ville'];							
								}
								
										//if($Client[$key]!=""){
												$Client[$key][$keyVille][$i]['intitule']= $row['intitule'];
												$Client[$key][$keyVille][$i]['adresse']= $row['adresse'];	
												$Client[$key][$keyVille][$i]['departement']= $row['departement'];
												$Client[$key][$keyVille][$i]['TypeClient']= $row['TypeClient'];
												$Client[$key][$keyVille][$i]['idClient']= $row['idClient'];
												$Client[$key][$keyVille][$i]['ImgMagasin']= $row['ImgMagasin'];
												 $i=$i+1;
										//}
		
		}	

		//while($row = mysql_fetch_array($resAff)){
		foreach($Client as $u=>$vdr){	
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr class="pair" style="height:50px;font-size:14px;">
				<td align="<?php echo $_SESSION['align']; ?>"  style="display:none" > <strong><?php echo $vdr['CodeVendeur']; ?> </td>
				<td align="<?php echo $_SESSION['align']; ?>" colspan="7" > <strong> <?php echo $vdr['Vendeur'];?> </strong></td>
			</tr>
			
			 <?php foreach($vdr as $ville){	
						      if(is_array($ville)){ ?>
					<tr  class="impair">
						<td colspan=""></td>	
						<td align="<?php echo $_SESSION['align']; ?>" colspan="6"> <strong><?php echo $ville['Ville']; ?></strong></td>
									
					</tr>
					
			 <?php foreach($ville as $r){	
						      if(is_array($r)){ ?>
			<tr   style="background-color:#f0f9ff">
					<td colspan="2"></td>
				<td align="<?php echo $_SESSION['align']; ?>"  > <?php echo $r['intitule']; ?> </td>
				<td align="<?php echo $_SESSION['align']; ?>"  > <?php echo stripslashes($r['adresse']); ?> </td>	
				<td align="<?php echo $_SESSION['align']; ?>"  > <?php  echo $r['departement']; ?> </td>				
				<td align="<?php echo $_SESSION['align']; ?>" > <?php 	echo $r['TypeClient'];?> </td>	
				<td align="<?php echo $_SESSION['align']; ?>" > <img class="imgClt" src="frontend2/<?php 	echo $r['ImgMagasin'];?> " width="100" height="100" 
				onclick="getImage('frontend2/<?php 	echo $r['ImgMagasin'];?> ') "
				/></td>	
				<!--td align="center">
					<span class="boutons"> 
						<input type="button" title="Modifier" action="mod" class="b" onClick="modifier('<?php echo $r['idClient']; ?>');" />  
					</span>
			  </td-->			
			  <td colspan="2" align="center" style="display:none">
				<input type="checkbox" class="checkLigne" name="<?php echo $r['idClient']; ?>" value="<?php echo $r['idClient']; ?>" />
			  </td>
			  </tr>
			 <?php
			$i++;
							  }
			 }
			 }}
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

			ajaxindicatorstop();
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'clients.php?delPlusieursArticle',clearForm:false});		
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
    &nbsp;<?php echo $trad['index']['GestionClient'] ;?>&nbsp;</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="100%" border="0" align="center" >
				  <tr>
					<td width="23%" valign="middle">
					<div class="etiqForm" id="SYMBT" ><?php echo $trad['label']['Vendeur'] ;?>: </div>				</td>
					<td width="30%">
					<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
					<div align="left">
					<select  name="Vendeur" id="Vendeur"  multiple="multiple" tabindex="3" class="Select Vendeur"
					style="display:visible;width:320px;">
		
                         <?php $sql = "select idVendeur, nom+ ' '+ prenom Vendeur from vendeurs ";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Vendeur']?></option>
                         <?php
                          }
                         ?>
				</select>
				</div>
										</td>
				  <td width="22%" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button"  onClick="rechercher();" value="<?php echo $trad['button']['Rechercher'] ;?>" class="bouton32" action="rech"
			  title="<?php echo $trad['button']['Rechercher'] ;?> " />
					  <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider'] ;?>" 
					  class="bouton32" action="effacer" title="<?php echo $trad['label']['vider'] ;?>"/></span><br/></td>
				  <td width="25%" rowspan="2"   style="border-<?php echo $_SESSION['align'];?>:1px solid #778;"><span class="actionForm">
					<input name="button3" type="button" title="<?php echo $trad['button']['Ajouter'] ;?> " 
					class="bouton32" onClick="ajouter();" value="<?php echo $trad['button']['Ajouter'] ;?>" 
					action="ajout" />
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
<!--
 <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
<div class="modal fade" id="myModal"  style="display:none" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
-->
<div id="formRes" style="overflow-y:scroll;min-height:280px;"></div>
<input type="hidden" id="act"/>
</div>
<div id="boxClient"> </div>
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>

  <!-- Modal Content (The Image) -->
  <div id="imgClt" style="text-align:center"></div>


  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>
<!--div id="imgClt"></div-->
<script language="javascript" type="text/javascript">

	   
$(document).ready(function(){	
$('#Vendeur').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['SelectVendeur'] ;?> ',single:true,maxHeight: 100
});
		ajaxindicatorstart("<?php echo $trad['msg']['Patienter'];?>");
  		$('#formRes').load('clients.php?aff');
				$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	1230,
					height			:	550,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'<?php echo $trad['index']['GestionClient'] ;?>',
					buttons			:	{
						"<?php echo $trad['button']['Annuler'] ;?>"		: function(){
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['Enregistrer'] ;?> "	: function() {
							terminer();
						
						}
					 }
			});
  });
	function filtrer(){	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'clients.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
			ajaxindicatorstart("<?php echo $trad['msg']['Patienter'];?>");
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'clients.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='clients.php?add';	
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='clients.php?mod&ID='+id;
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
												Intitule:"required"
                                          }    
		});
	var test=$(form).valid();

		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['ConfirmerOperation'];?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'clients.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'clients.php?goAdd',
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
	function getImage(src){		
		var obj = new Image();
		  obj.src =src;
		if(src!=""){
			if (obj.complete) {				
				$('#myModal').css('display', "block");
					$('#imgClt').html("<img src ="+src+" />");
				//$('#contentImg').src = src;		
				} else {
				alert("<?php echo $trad['msg']['ImgIntrouvable'];?>");
			}

		}
		
			/*$('#imgClt').dialog({
					autoOpen		:	false,
					width			:	1230,
					height			:	450,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Ajout / Modification de client',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						}
					 }
			});
			$('#imgClt').html("<img src ="+src+" />").dialog('open');*/
	}
</script>



<?php
include("footer.php");
?>
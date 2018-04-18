<?php 
require_once('connexion.php');
include("php.fonctions.php");

//sqlsrv_query("SET NAMES UTF8");
SQLSRV_PHPTYPE_STRING('UTF-8') ; 
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "mouvements";
$sansDoublons = "NomCaisse";
$cleTable = "idMouvement";
$IdDepot=$_SESSION['IdDepot'];
$_SESSION['searched_ref'] = "";
$UserId="1";
$Operateur=1;

if(isset($_GET['VideSession'])){
	unset($_SESSION['lignes']);
	exit;
}


///////////////////////////////////////////////on supprime une ligne
if(isset($_GET['supLigne'])){
		
		$ligne = $_GET['supLigne'];	

	unset($_SESSION['lignes'][$ligne]); // remove item at index 0
	$_SESSION['lignes'] = array_values($_SESSION['lignes']); // 'reindex' array

	//parcourir($_SESSION['lignes']);//return;
			?>
		<script language="javascript">
		$('#listBox').load('entrees_y.php?list');
				//charger('entrees.php?list');
			</script>
		
		<?php
		
		exit;
}


if(isset($_GET['list'])){
///////////////////////////////////////on liste les lignes
?>
<style>
.ligneEdit:first-child{	border-top:none;}
</style>
<?php
	if(isset($_SESSION['lignes']) && count($_SESSION['lignes']) != 0){
		
			$i=0;
			?>
			<table width="100%">
			     <tr class="entete">
					<td><?php echo $trad['label']['CodeArticle'];?> </td>
					<td><?php echo $trad['label']['Article'];?> </td>
					<td > <?php echo $trad['label']['Qte'];?> </td>
					 <td> <?php echo $trad['label']['PrixAchat'];?> </td>
        <td  colspan="2">
		
		</td>
  </tr>
			<?php
			$k=0;
			foreach($_SESSION['lignes'] as $ligne=> $row){
				if($i==0){
					$ligne1=' style="border-top:1px solid #778;"';
				}else{
					$ligne1='';
				}
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			
				?>
			<div onDblClick="modLigne('<?php echo $ligne; ?>');" class="ligneEdit" style="margin-bottom:-10px;">
	
			<form id="formLigne" action="" method="post" name="<?php echo $ligne; ?>" >
			
				<tr  class="<?php echo $c; ?>">
				<td width="110"><?php echo $row['CodeaBarre']; ?></td>
					<td width="110"><?php echo $row['NomArt']; ?></td>
					<td width="230" align="right"><?php echo $row['Qte']; ?></td>
					<td width="50" align="right" style=""><?php echo $row['Tarif']; ?>	</td>
				
					<td width="" align="center">
					<input type="reset" action="supLigne" value="" onClick="supLigne('<?php echo $ligne; ?>');" style="border:0px;width:16px;cursor:pointer"/></td>
				</tr>
		
			</form>
			</div>
			<?php
		}	
	?>
	</table>
	<?php
			//echo $_SESSION['totalHT'];
			
	}		
exit();
}
if (isset($_GET['goAddLigne'])){

$capture_field_val="";
$ligneArray=array();
		  // echo "avant".$IndexLigne."<br>";
$a="";

				$tabArt=explode(',',$_POST["ListeArt"]);	//	parcourir($tabArt);return;
	   if( (isset($_SESSION['lignes'])) && (count($_SESSION['lignes'])!=0))  {
	//   echo count($_SESSION["lignes"]);
		$t=0;	
			  foreach($_SESSION['lignes'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article et mm qte			
			
					if(($contenu["IdArticle"]==$tabArt[0]) )
					{
						?>
						<script language="javascript" type="text/javascript">
							//alert('Attention,l\'article <?php echo $_GET["NomArt"];?> est déjà ajouté  ');
							alert('<?php echo $trad['msg']['Attention'];?>,<?php echo $trad['label']['Article'];?> <?php echo $_GET["NomArt"];?> <?php echo $trad['msg']['DejaAjoute'];?>  ');
						</script>
						<?php
						
						$t=0;break;				
					}
					else {					
						$t+=1;						
					}
			  }
			  // si l'article avec mm qte n'existe pas on l'ajoute
		
			  if($t!=0){
			  			$IndexLigne=count($_SESSION['lignes']);							
						// si la qte et tarif vide en l'ajoute pas
				
							$ligneArray["IdLigne"]=$IndexLigne;
							$ligneArray["IdArticle"]=$tabArt[0];
							$ligneArray["CodeaBarre"]=$tabArt[1];
							//$ligneArray["NomArt"]=$_GET["NomArt"];
							$ligneArray["NomArt"]=$tabArt[3];
							$ligneArray["Qte"]=$_POST["Qte"];
							$ligneArray["Tarif"]=$_POST["Tarif"];
							$_SESSION['lignes'][$IndexLigne]= $ligneArray;
			  }
		  }
		  else {// une premiere insertion sans controle
						 //$IndexLigne+=1;
					
								$IndexLigne=0;
							
								 $ligneArray["IdLigne"]=$IndexLigne;
								$ligneArray["IdArticle"]=$tabArt[0];
								$ligneArray["CodeaBarre"]=$tabArt[1];
								$ligneArray["NomArt"]=$tabArt[3];
								$ligneArray["Qte"]=$_POST["Qte"];
								$ligneArray["Tarif"]=$_POST["Tarif"];
								$_SESSION['lignes'][$IndexLigne]= $ligneArray;
					
		  }
	
	//   echo "apres".$IndexLigne."<br>";

   
	?>
		<script language="javascript">		
		<?php //echo $_SESSION['ligneCourante']; ?>		
			changerLigne();
				</script>
	<?php
	
	// parcourir($_SESSION['lignes']);



exit;
}




if(isset($_GET['goMod'])){


exit;
	
}


if(isset($_GET['goAdd'])){

	   if( (!isset($_SESSION['lignes'])) || (count($_SESSION['lignes'])==0))  {
				     ?>
				<script type="text/javascript"> 
					//alert('Merci d\'ajouter les articles ');
					alert('<?php echo $trad['msg']['AddArticle'];?>');
				</script>
		
		<?php
			   return;}
			   
		$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}

//-----------------Add Fiche entree----------------//

//********* creation reference d'entree**************/;
$reqS="  type like ? ";
$paramsFonc= array('Entree');
$RefEntree= "NE".Increment_Chaine_F("Reference",$tableInser,"idMouvement",$conn,$reqS,$paramsFonc);
//echo $RefEntree;return;
//$DateEntree=date("Y-m-d");
$DateEntree = date_create(date("Y-m-d"));
$HeureEntree=date("H:i:s");
$reqInser1 = "INSERT INTO ".$tableInser." ([reference] ,[idOperateur]  ,[fournisseur] ,[livreur] ,[date],[heure],[idDepot],[type]) values 	(?,?,?,?,?,?,?,?)";
$params1= array(
				$RefEntree,
				$Operateur,
				addslashes(mb_strtolower(securite_bdd($_POST['Fournisseur']), 'UTF-8')),
				addslashes(mb_strtolower(securite_bdd($_POST['Livreur']), 'UTF-8'))
				,$DateEntree,$HeureEntree,$IdDepot,'Entree'
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout fiche entree ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IDFiche--------------------------------//
$sql = "SELECT max(idMouvement) as IdFiche FROM mouvements";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdFiche entree: ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFiche = sqlsrv_get_field( $stmt2, 0);
//----------------------Add Detail fiche --------------------------//

 foreach($_SESSION['lignes'] as $ligne=>$contenu)
{

	$reqInser2 = "INSERT INTO  detailmouvements([idArticle],[qte],[pa],[idDepot],idMouvement ,UniteVente) values (?,?,?,?,?,?)";
			$params2= array($contenu["IdArticle"],$contenu["Qte"],$contenu["Tarif"],$IdDepot,$IdFiche,'Pièce') ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {
				$error.="Erreur : ".parcourir(sqlsrv_errors()) . " <br/> ";
				break ;
			}			
}

if( ($error=="") && ($RefEntree!="NEerror")) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			//alert('L\'ajout a été effectué.');
			alert('<?php echo $trad['msg']['messageAjoutSucces'] ;?>');
			$('#box').dialog('close');
			Imprimer("<?php echo $IdFiche;?>");
			rechercher();
			
		</script>
		
<?php
unset($_SESSION['lignes']);
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}
/***********************/	
exit;
	
}

if (isset($_GET['mod'])){
	unset($_SESSION['lignes']);
	$ID= $_GET['ID'] ;
	$sql = "select * from $tableInser where idFiche = '$ID' ";
	//execSQL($sql);
	//echo $sql; return;
	$res= sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	
	$row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
          
?>


<div id="resAdd" style="padding:5px">&nbsp;</div>

<form id="formAddGlo" action="NULL" method="post" name="formAddGlo"> 
			<div id="resAdd"></div>
<table width="101%" border="0" align="center" >
			  <tr>
				<td width="23%" valign="middle" >
				  <input type="hidden" value="<?php echo $ID ;?>" name="IdFiche" />
				<div class="etiqForm" id="SYMBT" > 	<?php echo $trad['label']['ClientGroupe'];?>: </div>				</td>
				
				<td width="30%">
			
				<div align="left">
			<?php                              
						  $Options = "";
				      
								
						$reqClt="select nom,prenom ,t.idTypeClient,type, designation from ficheTarifs t 
						left join clients clt on clt.idClient=t.idTypeClient
						left join typeClients tc on tc.idType=t.idTypeClient 
						where idFiche  =".$ID;		
			
						   $resClt = sqlsrv_query( $conn, $reqClt, array(), array( "Scrollable" => 'static' ) );	
						   $CltGrp="";
						   if( $resClt === false )  
							{  
							  if( ($errors = sqlsrv_errors() ) != null)  
							  {  
								$errors = sqlsrv_errors();
								echo "Erreur : $reqClt ".$errors[0]['message'] . " <br/> ";
							  }  
							} else{
								$rowC = sqlsrv_fetch_array($resClt, SQLSRV_FETCH_ASSOC);
								$CltGrp= $rowC['nom'];
								if($rowC['type']=="Groupe") $CltGrp=$rowC['designation'];
								else $CltGrp=$rowC['nom']." ".$rowC['prenom'];
								
							}
                                     		    ?>
				<input id="GroupeClt" value="<?php echo $rowC['idTypeClient'];?> "name="GroupeClt" type="hidden"/>	
				
				<input id="GroupeClt" value="<?php echo $CltGrp;?> "name="GroupeClt" type="text" />
	
						</div>
				</td>
		 </tr>
		
	 	 </table>
		 </form>
        	<BR>
<DIV class="arti">
<form id="formAdd" action="NULL" method="post" name="formAdd1"> 
			<table border="0" cellpadding="5" cellspacing="5">
			<tr>
					<td  valign="top" style="width:130px"><div class="etiqForm"  style="text-align:left; " 
			id="DATE_PIECE" > <strong>	<?php echo $trad['label']['Article'];?></strong> : </div>
            </td>
			</tr>
			<tr>
			<td>
		<div id="grpCaisse" style=" width:300px; float:left;"><select style="width:240px; vertical-align:top"
		multiple="multiple" name="ListeArt" id="ListeArt"  tabindex="3" class="ListeArt" >	
	<?php 
				$sql = "select IdArticle, Designation from articles ";
  	            $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
				while ($donnees =  sqlsrv_fetch_array($reponse))
				{
				?>
				<option value="<?php echo $donnees['IdArticle'] ?>"><?php echo $donnees['Designation']?></option>
			  <?php
			   }
			  ?>

			</select>
				</div>      
            </td>
     
          </tr>
	  <!-- debut div tarif par qte -->
			<tr>	
				    <td valign="top">
					<SPAN class="etiqForm" id="DATE_PIECE" ><strong>Quantité Min:</strong></SPAN>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <SPAN class="etiqForm" id="DATE_PIECE" ><strong>Tarif:</strong></SPAN></td>
				  </tr>
				  <tr>
					<td valign="top" >
			<div class="input_fields_wrap">
			
				<div style="width:auto; float:left; margin-bottom:10px;">
				
				<input id="x1" action="mytext"  class="inputDuree" size="10" type="text"  name="mytext[]" onkeypress="return isEntier(event,this)" /> 
			&nbsp;&nbsp;&nbsp;&nbsp;
			
				 <input id="y1" action="mytext2" size="10"  class=""   onkeypress="return isDecimal(event,this)"  type="text"  name="mytext2[]" /> 
					<div style=" float:RIGHT; width:24px ;margin:0 5px">
					<input type="button" class="add_field_button"/>	
					</div>
				</div>
			</div>
			</td>

			
		</tr>
		<TR><TD>
		  <input name="button3" type="button" title="Ajouter entrée" class="bouton32" onClick="AjoutLigne();" value="Ajouter entrée" 
		  action="ajout" style="width:150px;" />
		</TD>
		</TR>
		</table>
		</form>


</div>
<DIV class="divList">

<h3>Liste des articles</h3>
	<div id="listBox" style="">
	<?php 
				$sqlArt = "	select t.idArticle,qteMin,pvHT, Designation from tarifs t
							inner join articles a on t.idArticle=a.idArticle
							where t.idFiche='$ID'";

  	            $smtA=sqlsrv_query( $conn, $sqlArt, array(), array( "Scrollable" => 'static' ) );	
				
				$IndexLigne=0;
				while ($rowArt =  sqlsrv_fetch_array($smtA, SQLSRV_FETCH_ASSOC))
				{
								$ligneArray["IdLigne"]=$IndexLigne;
								 $ligneArray["IdArticle"]=$rowArt["idArticle"];
								 $ligneArray["NomArt"]=$rowArt["Designation"];
								$ligneArray["Qte"]=$rowArt["qteMin"];
								$ligneArray["Tarif"]=$rowArt["pvHT"];
								$_SESSION['lignes'][$IndexLigne]= $ligneArray;
								$IndexLigne+=1;
				}
				if(isset($_SESSION['lignes']) && count($_SESSION['lignes']) != 0){
		
			$i=0;
			?>
			<table width="100%">
			     <tr class="entete">
					<td>Article </td>
					<td > Quantité </td>
					 <td> Tarif </td>
					<td  colspan="2">
					
					</td>
			  </tr>
			<?php
			$k=0;
			foreach($_SESSION['lignes'] as $ligne=> $row){
				if($i==0){
					$ligne1=' style="border-top:1px solid #778;"';
				}else{
					$ligne1='';
				}
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			
				?>
			<div onDblClick="modLigne('<?php echo $ligne; ?>');" class="ligneEdit" style="margin-bottom:-10px;">
	
			<form id="formLigne" action="" method="post" name="<?php echo $ligne; ?>" >
			
				<tr  class="<?php echo $c; ?>">
				
					<td width="110"><?php echo $row['NomArt']; ?></td>
					<td width="230" align="right"><?php echo $row['Qte']; ?></td>
					<td width="50" align="right" style=""><?php echo $row['Tarif']; ?>	</td>
				
					<td width="" align="center">
					<input type="reset" action="supLigne" value="" onClick="supLigne('<?php echo $ligne; ?>');" style="border:0px;width:16px;cursor:pointer"/></td>
				</tr>
		
			</form>
			</div>
			<?php
		}	
	?>
	</table>
	<?php
			sqlsrv_free_stmt( $smtA );  
			sqlsrv_close( $conn );  
			//echo $_SESSION['totalHT'];
			
	}
	
	?>
	</div>
	
<div class="msgErreur">&nbsp;</div>
</div>

<script language="javascript" type="text/javascript">


$(document).ready(function(){


		/*****************/
  var max_fields      = 8; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var xx = 1; //initlal text box count
    $(add_button).click(function(e){ 
	
//************************************************************************
		var X = [];
$('input:text[action=mytext]').each(function() {
		//alert("valeur : " +this.value);
		X.push({"value":this.value,"id":this.id});//alert("id of "+this.value + " : " + this.id);
		});
;
var Y = [];
$('input:text[action=mytext2]').each(function() {
		//alert("valeur : " +this.value);
		Y.push({"value":this.value,"id":this.id});
		});
;

////////////////////Valeur Vide QUANTITE////////////////////////////

for(i = 0; i < X.length; i++)
	{
		if(X[i].value  == "")
		{
			//var indice=i+1;
			
			$('#'+X[i].id).css({'border':'1px solid red'});
			alert("Veuillez saisir la quantité");
			return;
		}else{
			//	var indice=i+1;
				$('#'+X[i].id).css({'border':'1px solid #ccc'});
				$('#'+Y[i].id).css({'border':'1px solid #ccc'});
		}
		
}
/***************************-------------X Y dupliqué----------*****************************/

var duplicate = find_duplicate_value(X);     
//alert( ( (duplicate === null) ? "Nothing" : duplicate ) + ' is duplicated');
if(duplicate != null) 
{
		var indice=duplicate+1;
		$('#x'+indice).focus();
		$('#x'+indice).css({'border':'1px solid red'});
		
		alert("Quantité dupliquée");
		return;	
}


////////////////////Valeur Vide Tarif////////////////////////////
for(i = 0; i < X.length; i++)
{
	
		if(Y[i].value == "")
		{
			//var indice=i+1;
			$('#'+Y[i].id).css({'border':'1px solid red'});
			alert("Veuillez saisir la tarif");
			return;
		}else{
				//var indice=i+1;
				$('#'+X[i].id).css({'border':'1px solid #ccc'});
				$('#'+Y[i].id).css({'border':'1px solid #ccc'});
		}
}

/////////////////////////////////////////////////////
	

	//on add input button click
        e.preventDefault();	
        if(xx < max_fields){ //max input box allowed
            xx++; //text box increment
          $(wrapper).append('<div id="ligne" style="width:320px; margin-bottom:10px;clear:both;" > <input id="x'+xx+'" size="10" action="mytext"  class="inputDuree" type="text"  name="mytext[]" onkeypress="return isEntier(event,this)" /> &nbsp;&nbsp;&nbsp;&nbsp; <input id="y'+xx+'"  size="10" onkeypress="return isDecimal(event,this)"  action="mytext2" class="inputDuree" type="text"  name="mytext2[]" />&nbsp;&nbsp;<div style="width:20px; display:inline-block "><input type="button"  class="remove_field" ></input></div> </div> ');
        }


    });
	
	  $(wrapper).on("click",".remove_field", function(e){
	 //user click on remove text
        e.preventDefault(); $(this).parent('div').parent('div').remove(); xx--;
    })
});


 $('#ListeArt').multipleSelect({

	   filter: true,placeholder:'<?php echo $trad['label']['SelectArticle'];?> ',single:true,maxHeight: 100
	});

</script>


<?php
	
exit;
}


if(isset($_GET['chargerAllArticle'])){

			$Options = '<select multiple="multiple" name="ListeArt" id="ListeArt" class="Select ListeArt"  tabindex="3" 
			style="width:300px; vertical-align:top" >';
			$sql = " select a.IdArticle, a.Designation,a.Reference as Reference,a.Unite,a.CB from articles a
						INNER JOIN gammes g ON g.IdGamme=a.IdFamille
						INNER JOIN marques m ON m.IdMarque=g.IdMarque
						inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
						INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
						where fa.idFamille=2025 
			";
			$reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );
			$nRes = sqlsrv_num_rows($reponse);
		    if($nRes != 0)
		     while ($donnees =  sqlsrv_fetch_array($reponse))
            {
$Options.="<option value=".$donnees['IdArticle'].",".$donnees['Reference'].",".$donnees['Unite'].",".$donnees['CB'].">".strtoupper($donnees['Reference'])."</option>";
			}
		
		$Options.="</select>";
?>
				
	<script language="javascript" type="text/javascript">

 $('#ListeArt').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['SelectArticle'];?> ',single:true,maxHeight: 200,
	     onClick: function(view) {
			 
			var res = view.value.split(",");		 
			$("#Unite").val(res[2]);
                  //  (view.checked ? 'checked' : 'unchecked'));
			$("#UniteArt").val(res[2]);
				  $("#resart").load('entrees_y.php?InfoArticle&IdArticle='+res[0]+'&Unite='+res[2]);
            }
			
			
	});
	
	</script>
	<?php
			echo $Options;
exit;
}

if(isset($_GET['chargerArticle'])){

			$Options = '<select multiple="multiple" name="ListeArt" id="ListeArt" class="Select ListeArt"  tabindex="3" 
			style="width:300px; vertical-align:top" >';
			$sql = "select IdArticle, Designation,Reference as CodeBarre,Unite from articles where IdFamille=?";
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['IdGamme']), array( "Scrollable" => 'static' ) );
			$nRes = sqlsrv_num_rows($reponse);
		    if($nRes != 0)
		     while ($donnees =  sqlsrv_fetch_array($reponse))
            {
$Options.="<option value=".$donnees['IdArticle'].','.$donnees['CodeBarre'].','.$donnees['Unite'].">".$donnees['Designation']."</option>";
			}
		
		$Options.="</select>";
?>
				
	<script language="javascript" type="text/javascript">

 $('#ListeArt').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['SelectArticle'];?> ',single:true,maxHeight: 200,
	     onClick: function(view) {
			var res = view.value.split(",");		 
			$("#Unite").val(res[2]);
                  //  (view.checked ? 'checked' : 'unchecked'));
			$("#UniteArt").val(res[2]);
				  $("#resart").load('entrees_y.php?InfoArticle&IdArticle='+res[0]+'&Unite='+res[2]);
            }
			
			
	});

	</script>
	<?php
			echo $Options;
exit;
}
if(isset($_GET['chargerGamme'])){

			$Options = '<select multiple="multiple" name="Gamme" id="Gamme" class="Select Gamme"  tabindex="3" style="width:250px" >';
			$sql = "select IdGamme, Designation from gammes where IdSousFamille=?";
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['IdSSFamille']), array( "Scrollable" => 'static' ) );
			$nRes = sqlsrv_num_rows($reponse);
		    if($nRes != 0)
		     while ($donnees =  sqlsrv_fetch_array($reponse))
            {
				$Options.="<option value='".$donnees['IdGamme']."'>".stripcslashes($donnees['Designation'])."</option>";
			}
		
		$Options.="</select>";
?>
				
	<script language="javascript" type="text/javascript">

		$('#Gamme').multipleSelect({
		  filter: true,placeholder:'<?php echo $trad['label']['SelectGamme'];?>',single:true,maxHeight: 100

		});

	</script>
	<?php
			echo $Options;
exit;
}
if(isset($_GET['chargerSSFamille'])){

			$Options = '<select multiple="multiple" name="SFamille" id="SFamille" class="Select SFamille"  tabindex="3" style="width:250px" >';
			$sql =$sql = "select IdSousFamille, Designation from sousfamilles where IdFamille=?";
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['IdFamille']), array( "Scrollable" => 'static' ) );
			$nRes = sqlsrv_num_rows($reponse);
		    if($nRes != 0)
		     while ($donnees =  sqlsrv_fetch_array($reponse))
            {
				$Options.="<option value='".$donnees['IdSousFamille']."'>".$donnees['Designation']."</option>";
			}
		
		$Options.="</select>";
?>
				
	<script language="javascript" type="text/javascript">

		$('#SFamille').multipleSelect({
		  filter: true,placeholder:'<?php echo $trad['label']['SelectSousFam'];?> ',single:true,maxHeight: 100,
		      onClick: function(view) {
				if(view.checked = 'checked')
				$('#gammes').load("entrees_y.php?chargerGamme&IdSSFamille="+view.value);
			   //  alert(view.label + '(' + view.value + ') ' + (view.checked ? 'checked' : 'unchecked'));
		   				
				var SFamille =$('#SFamille').val();
				if(SFamille!="") {
					$('div.SFamille').removeClass('erroer');
					$('div.SFamille button').css("border","1px solid #ccc").css("background","#fff");
				}
            }
		});
$("#Gamme").multipleSelect("uncheckAll");
$("#Gamme").multipleSelect("disable");
	</script>
	<?php
			echo $Options;
exit;
}
if (isset($_GET['searchArticle'])){
?>
<form id="formSearch" action="NULL" method="post" name="formSearch"> 
<br/>
<table border="0" width="100%" cellpadding="10" cellspacing="15">
  
  <tr>
	  <td  width="35%" align="right"><strong>   <?php echo $trad['label']['Famille'];?>  : </strong>	</td>
	  <td  width="70%">
		<select name="Famille" id="Famille" multiple="multiple" tabindex="3" class="Select Famille" style="display:visible;width:250px;">
						<?php $sql = "select IdFamille, Designation from familles  where idFamille=2025";
                            $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );  while ($donnees =  sqlsrv_fetch_array($reponse))
                            {
							?>
                               <option value="<?php echo $donnees['IdFamille'] ?>"><?php echo $donnees['Designation']?></option>
                           <?php } ?>
		</select>
	  </td>
	  </tr>
	  <tr>
	  <td align="right"><strong> <?php echo $trad['label']['SousFamille'];?>  :  </strong></td>
<td>
<div id="ssfamilles">
<select name="SFamille" multiple="multiple" id="SFamille" class="Select SFamille" style="width: 250px;"></select>
</div>
</td>
</tr>
<tr>
   <td align="right">  <strong>  <?php echo $trad['label']['Gamme'];?>  :  </strong>	</td><td>	
<div id="gammes">  
	<select style="width: 250px;"  multiple="multiple" name="Gamme" id="Gamme" class="Select Gamme"   tabindex="3" > </select>
</div>
   </td>
</tr>

<tr><td colspan="4"></td></tr>
<!--tr>
    <td colspan="4">
		<input style=" margin-left:10px;" type="button" id="btnSearch" value="Rechercher" class="btn"/>	
		
		<input type="reset" id="btnAnnuler" value="Annuler" class="btn"/> 
	
    </td>
   </tr-->
  </table></form>
<script language="javascript" type="text/javascript">//,single:true
$('#Famille').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['selectFamille'];?> ',single:true,maxHeight: 100,
	    onClick: function(view) {
				if(view.checked = 'checked')
				$('#ssfamilles').load("entrees_y.php?chargerSSFamille&IdFamille="+view.value);
			   //  alert(view.label + '(' + view.value + ') ' + (view.checked ? 'checked' : 'unchecked'));

            }
});
$('#SFamille').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['SelectSousFam'];?> ',single:true,maxHeight: 100
});
$('#Gamme').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['SelectGamme'];?> ',single:true,maxHeight: 100
});

$('body').on('change', '#Famille', function() {
	 	var Famille =$('#Famille').val(); <?php //echo $row["IdVille"];?>
		if(Famille!="") {
					$('div.Famille').removeClass('erroer');
					$('div.Famille button').css("border","1px solid #ccc").css("background","#fff");
		}
});
$("#SFamille").multipleSelect("uncheckAll");
$("#SFamille").multipleSelect("disable");
$("#Gamme").multipleSelect("uncheckAll");
$("#Gamme").multipleSelect("disable");	
 $("#btnClose").click(function(){
 $("div#search").hide(700);
 });
 $("#btnSearch").click(function(){	   
			$('#formAdd').ajaxSubmit({
				target:'#grpCaisse'
				,url:'entrees_y.php?chargerCaisse',
				method:'post',
				clearForm:false
			});	 
			$("div#search").hide(700);
			return false;
  });
   $("#btnAnnuler").click(function(){
  });
</script>
	<?php
exit;
}
if (isset($_GET['add'])){
?>
<div id="resAdd" style="padding:5px">&nbsp;</div>
<form id="formAddGlo" action="NULL" method="post" name="formAddGlo"> 
			<div id="resAdd"></div>
<table width="101%" border="0" align="center" >
			  <tr>
				<td  valign="top" >
				<div class="etiqForm" id="SYMBT" > <?php echo $trad['label']['Fournisseur'];?>: </div>				</td>
				<td>   
           
					<select style="width:240px; vertical-align:top"
				multiple="multiple" name="Fournisseur" id="Fournisseur"  tabindex="3" class="Fournisseur" >
				

					  <?php 
						$sql = "select idFournisseur, Designation from fournisseurs ";
						$reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
						while ($donnees =  sqlsrv_fetch_array($reponse))
						{
						?>
						<option value="<?php echo $donnees['idFournisseur'] ?>"><?php echo $donnees['Designation']?></option>
					  <?php
					   }
					   		sqlsrv_free_stmt( $reponse );  
					
					  ?>

					</select>
			</td>
				<td  valign="top" >
				<div class="etiqForm" id="SYMBT" >  <?php echo $trad['label']['Livreur'];?>: </div>				</td>
				<td>   
            <input class="FormAdd1" type="text" name="Livreur"  id="Livreur" size="30" tabindex="1"  />
			</td>
			
				</tr>
			
		
	 	 </table>
		 </form>
        	<BR>
<DIV class="arti">
<form id="formAdd" action="NULL" method="post" name="formAdd1"> 
			<table border="0" cellpadding="5" cellspacing="5">
			<tr>
					<td  valign="top" colspan="2"><div class="etiqForm"  style="text-align:<?php echo $_SESSION['align'];?>; " 
			id="DATE_PIECE" > <strong><?php echo $trad['label']['Article'];?></strong> : 
			<a class="show" href="#" style="font-weight:bold;text-decoration:underline;"  > 
			<?php echo $trad['label']['RechercheAvance'];?></a></div>
            </td>
			</tr>
			<tr>
			<td  colspan="2" >
		<div id="grpCaisse" style="  float:<?php echo $_SESSION['align'];?>;">
		<select style="width:340px; vertical-align:top"
		multiple="multiple" name="ListeArt" id="ListeArt"  tabindex="3" class="ListeArt" >
		

			  <?php 
			/*	$sql = "select IdArticle, Designation,CB as CodeBarre from articles ";
  	            $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
				while ($donnees =  sqlsrv_fetch_array($reponse))
				{
				?>
				<option value="<?php echo $donnees['IdArticle'].','.$donnees['CodeBarre'] ?>"><?php echo $donnees['Designation']?></option>
			  <?php
			   }
			   sqlsrv_free_stmt( $reponse );  
				sqlsrv_close( $conn ); */
			  ?>

			</select>
				</div>      
            </td>
     </tr>
          	<tr>
			
				<td valign="top">
				<SPAN class="etiqForm" id="DATE_PIECE" ><strong><?php echo $trad['label']['Qte'];?>:</strong></SPAN>
				</td>
						<td>
			  <SPAN class="etiqForm" id="DATE_PIECE" ><strong><?php echo $trad['label']['PrixAchat'];?>:</strong></SPAN></td>
				   </tr>
		<tr>
				<td valign="top" >
			<input id="Qte" action="mytext"  class="inputDuree" size="10" type="text"  name="Qte" onkeypress="return isEntier(event,this)" /> 
			</td>				
				<td valign="top" >
		
			 <input id="Tarif" action="mytext2" size="10"  class=""   onkeypress="return isDecimal(event,this)"  type="text"  name="Tarif" /> 
			</td>

		</tr>
		<TR><TD  colspan="2" align="center" ><BR>
		  <input name="button3" type="button" title="<?php echo $trad['button']['Ajouter'];?>" class="bouton32"
		  onClick="AjoutLigne();" value="" 
		  action="ajoutTarif" style="width:150px;" />
		</TD>
		</TR>
		</table>
		</form>


</div>
<DIV class="divList">

<h3><?php echo $trad['label']['ListArticle'];?> :</h3>
	<div id="listBox" style=""></div>
	
<div class="msgErreur">&nbsp;</div>
</div>
       	
<script language="javascript" type="text/javascript">


$(document).ready(function(){

		$('#grpCaisse').load("entrees_y.php?chargerAllArticle");
 /*$(".show").click(function(){
		  url="entrees_y.php?searchArticle";

	$('div#search').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url);

		});*/
 $('#Fournisseur').multipleSelect({

	   filter: true,placeholder:'<?php echo $trad['label']['SelectFournisseur'];?> ',single:true,maxHeight: 200
	});
		/*****************/
  var max_fields      = 8; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var xx = 1; //initlal text box count
    $(add_button).click(function(e){ 
	
//************************************************************************
		var X = [];
$('input:text[action=mytext]').each(function() {
		//alert("valeur : " +this.value);
		X.push({"value":this.value,"id":this.id});//alert("id of "+this.value + " : " + this.id);
		});
;
var Y = [];
$('input:text[action=mytext2]').each(function() {
		//alert("valeur : " +this.value);
		Y.push({"value":this.value,"id":this.id});
		});
;

////////////////////Valeur Vide QUANTITE////////////////////////////

for(i = 0; i < X.length; i++)
	{
		if(X[i].value  == "")
		{
			//var indice=i+1;
			
			$('#'+X[i].id).css({'border':'1px solid red'});
			alert("Veuillez saisir la quantité");
			return;
		}else{
			//	var indice=i+1;
				$('#'+X[i].id).css({'border':'1px solid #ccc'});
				$('#'+Y[i].id).css({'border':'1px solid #ccc'});
		}
		
}
/***************************-------------X Y dupliqué----------*****************************/

var duplicate = find_duplicate_value(X);     
//alert( ( (duplicate === null) ? "Nothing" : duplicate ) + ' is duplicated');
if(duplicate != null) 
{
		var indice=duplicate+1;
		$('#x'+indice).focus();
		$('#x'+indice).css({'border':'1px solid red'});
		
		alert("Quantité dupliquée");
		return;	
}


////////////////////Valeur Vide Tarif////////////////////////////
for(i = 0; i < X.length; i++)
{
	
		if(Y[i].value == "")
		{
			//var indice=i+1;
			$('#'+Y[i].id).css({'border':'1px solid red'});
			alert("Veuillez saisir la tarif");
			return;
		}else{
				//var indice=i+1;
				$('#'+X[i].id).css({'border':'1px solid #ccc'});
				$('#'+Y[i].id).css({'border':'1px solid #ccc'});
		}
}

/////////////////////////////////////////////////////
	

	//on add input button click
        e.preventDefault();	
        if(xx < max_fields){ //max input box allowed
            xx++; //text box increment
          $(wrapper).append('<div id="ligne" style="width:320px; margin-bottom:10px;clear:both" > <input id="x'+xx+'" size="10" action="mytext"  class="inputDuree" type="text"  name="mytext[]" onkeypress="return isEntier(event,this)" /> &nbsp;&nbsp;&nbsp;&nbsp; <input id="y'+xx+'"  size="10" onkeypress="return isDecimal(event,this)"  action="mytext2" class="inputDuree" type="text"  name="mytext2[]" />&nbsp;&nbsp;<div style="width:20px; display:inline-block "><input type="button"  class="remove_field" ></input></div> </div> ');
        }


    });
	
	  $(wrapper).on("click",".remove_field", function(e){
	 //user click on remove text
        e.preventDefault(); $(this).parent('div').parent('div').remove(); xx--;
    })
});
 $('#ListeClt').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez les clients ',single:true,minHeight: 300,  

			    onOptgroupClick: function(view) {
			 
			           //$("#ListeClt").multipleSelect("uncheckAll");
					
					   
                var values = $.map(view.children, function(child){
                    return child.value;
                }).join(', ');
				//alert(values);
				//recuprer les elements selectionnées ,ListeVal[0] contien l'IdGroup
				var ListeVal = values.split(","); 			
				if(view.checked==true) {
					 $("#GroupeClt").val(ListeVal[0]);
				}
					
            },
			onClick: function(view) {
               $("#GroupeClt").val("");
            }
			
	});


 $('#ListeArt').multipleSelect({
	   filter: true,placeholder:'<?php echo $trad['label']['SelectArticle'];?>',single:true,maxHeight: 200
	});

</script>


<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){
	
unset($_SESSION['lignes']);
	$IdCltGrp=""; $params = array();
	$sqlA = "
		SELECT 
			idMouvement as idFiche,reference,livreur,f.designation as fournisseur,date as dateFiche,heure

		FROM 
			$tableInser t
			inner join fournisseurs f on f.idFournisseur=t.fournisseur
			where t.idDepot=$IdDepot and type like 'Entree'
			
		";
	//	echo $IdDepot ;return;
	/*********** rech par reference *****************/
	if((isset($_POST["NumFiche"])) && ($_POST["NumFiche"]!="")){
		$reference=$_POST["NumFiche"];
			$sqlA.= " and t.reference = ? ";
			 $params = array($reference);
	}
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt=sqlsrv_query($conn,$sqlA,$params,$options);
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes."<br>";
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "idMouvement";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "DESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = " ORDER BY $cTri $oTri ";//LIMIT $min,$max ";

	$sql=$sqlA.$sqlC;

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

<form id="formSelec" method="post">
	<table width="100%" border="0">
      <tr class="entete">
        <td ><?php echo $trad['label']['Ref'];?> </td>
        <td width="30%"> <?php echo $trad['label']['Fournisseur'];?></td>
		<td width="30%"> <?php echo $trad['label']['Livreur'];?>   </td>
		<td width=""> <?php echo $trad['label']['date'];?> /<?php echo $trad['label']['Heure'];?>   </td>
		<td></td>
        <td  colspan="2" style="display:none">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value="" />
			<input type="hidden" id="NUMFAC" name="NUMFAC" value=""/>
			<input type="button" value="S&eacute;lection :    " onClick="actionSelect();" style="cursor:pointer;border:0px;font-weight:bold;font-size:11px; color:#FFFFFF;background:transparent url(images/mini-trash.png) no-repeat right;"/>
            <input type="button" class="bouton16" action="toutSelect" onClick="toggleCheck($('.checkLigne'));" />
		</td>
		<td>Mise à Jour</td>
  </tr>

<!--<div id="cList">-->
	<?php
		$i=0;
	
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
			
		
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $c; ?>">
				<td align="<?php echo $_SESSION['align'];?>"  > <?php  echo $row['reference']; ?> </td>
				<td align="<?php echo $_SESSION['align'];?>" > <?php 	echo stripslashes($row['fournisseur']);?> </td>
				<td align="<?php echo $_SESSION['align'];?>" > <?php 	echo stripslashes($row['livreur']);?> </td>
				<td align="center"><?php echo date_format($row['dateFiche'], 'd/m/Y')." ".($row['heure']);?></td>	
				<td>    <input type="button" style="display:none" title="Imprimer la fiche d'entrée"   value="" class="Imprimer tool" onClick="Imprimer('<?php echo $row['idFiche']; ?>');" />
				  <input type="button" title="<?php echo $trad['frais']['details'];?>"   value="<?php echo $trad['frais']['details'];?>" class="detail tool" onClick="Imprimer('<?php echo $row['idFiche']; ?>');" />
				  </td>
			   <td align="center" style="display:none">
				<input type="checkbox" class="checkLigne" name="<?php	echo $row['idFiche']; ?>" value="<?php	echo $row['idFiche']; ?>" />
			  </td>
			  <td>
			  	<a class="btn btn-success" onClick="MyWindow=window.open('mise_a_jour_mouvements.php?ref=<?php  echo $row['reference']; ?>','MyWindow');
				return false;">
			  	Mise à jour</a>
			  </td>
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
exit;}
?>
<?php include("header_y.php"); ?>
<script src="js/jquery.multiple.select2.js" type="text/javascript"></script>
<div class="pageBack" >

<div id="search"> </div>

<div id="box"> </div>
<div class="contenuBack">
	<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;<?php echo $trad['Menu']['gestionstock'];?> &nbsp;<img src="images/tri.png" />
		&nbsp;<?php echo $trad['Menu']['entreestock'];?></div>

	

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	<!--Recherche CH -->
		<table width="100%" border="0"  >
			  <tr>
			
				<td  align="right">
				<div class="etiqForm" id="SYMBT" > <?php echo $trad['label']['CodeEntree'];?> : </div>				</td>
				
				<td width="30%">
				<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
				<div align="left" >
	
			 <select id="NumFiche" name="NumFiche" Class="Select NumFiche" style="width:350px">
			<option value=""><?php echo $trad['label']['tousSelect'];?></option>
					  <?php 
				$sql = "select reference from mouvements where type like 'Entree' and idDepot=$IdDepot order by idMouvement desc ";
  	            $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );	 
				while ($donnees =  sqlsrv_fetch_array($reponse))
				{
				?>
				<option value="<?php echo $donnees['reference'] ?>"><?php echo $donnees['reference']?></option>
			  <?php
			   }
			  ?> </select>
						</div>
				</td>			
		      <td rowspan="2"  >	<span class="actionForm">      
          <input name="button" type="button" id="Rechercher"  onClick="rechercher();"
		  value="<?php echo $trad['button']['Rechercher'];?>  " class="bouton32" action="rech" title="<?php echo $trad['button']['Rechercher'];?> "
		  />
			      <input name="button2" type="reset" onClick="" value="<?php echo $trad['label']['vider'];?>"
				  class="bouton32" action="effacer" title="<?php echo $trad['label']['vider'];?>"/></span><br/></td>
			  <td width="25%" rowspan="2"   style="" align="center"><span class="actionForm">
			    <input name="button3" type="button" title="<?php echo $trad['button']['Ajouter'];?>"
				class="bouton32" onClick="ajouter();" value="<?php echo $trad['button']['Ajouter'];?>" action="ajout" style="width:150px;" />
			  </span></td>
			</tr>
	 	 </table>
      </div>
      <div id="formFiltre" style=" display:none">
		<table border=0 style=" width:400px ; margin:auto;">
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
			    <select name="pact"><option value=1>1</option></select>
				</span>
		  	  </td>
				<!--<td width="23%">Crit&egrave;re de tri : 
				  <select name="cTri" onChange="filtrer();">
				<option value="NomCaisse"> Nom Caisse </option>
				<option value="Ville"> Ville </option>
      
				
				</select
		  	  </td>-->
			  <!--<td width="36%">Ordre de tri : 
				  <select name="oTri" onChange="filtrer();">
				<option value="ASC"> Croissant </option>
				<option value="DESC" selected> Decroissant </option>
				</select-->
			 
			</tr>
		</table>
	</div>
	</form>
			
		
	
	<div style="margin:10px; text-align:center;">
	<span id="resG" class="vide"></span>
	</div>

									
						
<div id="brouillon" style="display:block">  </div> 
<div id="formRes"  style="overflow-y:scroll;min-height:280px"></div>
<input type="hidden" id="act"/>
  </div>

	<?php include("footer_y.php"); ?>
</div>

<script language="javascript" type="text/javascript">
 $('#NumFiche').multipleSelect({

	   filter: true,placeholder:'<?php echo $trad['label']['SelectNumEntree'];?>',single:true,maxHeight: 100
	});
	  $("#NumFiche").multipleSelect("uncheckAll");


function suppression(idPos){

			jConfirm('Confirmer la suppression ?', null, function(r) {
						if(r)	{
							$('#act').attr('value','supp'); 
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'entrees_y.php?suppression&idPos='+idPos,clearForm:false});		
						}
					})
	//	$('#box').load(url).dialog('open');
	
}
function modification(nomPromo){

		$('#act').attr('value','modif'); 
		var url='entrees_y.php?modification&nompromo='+nomPromo ;
	
		$('#box').load(url).dialog('open');
	
}
	function filtrer(){
	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'entrees_y.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){

		$('#formRechF').ajaxSubmit({target:'#formRes',url:'entrees_y.php?rech'})
			//clearForm('formRechF',0);
	}

function ajouter(){

		$('#act').attr('value','add');
		var url='entrees_y.php?add';
	
		$('#box').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');
	
}

  $('body').on('keypress', '#ListeCltRech', function(args) {
   if (args.keyCode == 13) {
       $("#Rechercher").click();
       return false;
   }
});

$(document).ready(function(){


		$('#search').dialog({
					autoOpen		:	false,
					width			:	600,
					height			:	400,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'<?php echo $trad['button']['Rechercher'];?>',
					buttons			:	{
						"<?php echo $trad['button']['Annuler'];?>"		: function(){
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['Rechercher'];?> "	: function() {
							terminerSearch();
						
						}
					 }
			});
	/*$("label[data-group=group_0]").parent(".group").css("border","1px solid red");
		$("label[data-group=group_0]").parent(".group").addClass("selected");*/
	     $("#ListeCltRech").multipleSelect("uncheckAll");
		$('#formRes').html('<center><br/><br/><img src="images/loading.gif" /></center>').load('entrees_y.php?aff');
				$('#box').dialog({
					autoOpen		:	false,
					width			:	1200,
					height			:	540,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	false,
					draggable		:	false,
					title			:	'<?php echo $trad['Menu']['entreestock'];?>',
					open: function(event, ui) { 
    //hide close button.
							$(this).parent().children().children('.ui-dialog-titlebar-close').hide();
						},
	
					buttons			:	{
						"<?php echo $trad['button']['Fermer'];?>"		: function(){
							$('#resG').load('entrees_y.php?VideSession');
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['Enregistrer'];?> "	: function() {
							terminer();
						
						}
					 }
			});
  });
function modifier(id){
		$('#act').attr('value','mod');
		var url='entrees_y.php?mod&ID='+id;
		$('#box').html('').load(url).dialog('open');	
}
	
function terminerSearch(){

form="#formSearch";
	    $(form).validate({
                               rules: {
                                               'Famille': "required",
												'SFamille':"required",
												'Gamme':"required"
                                           }  
});

var test=$(form).valid();
verifSelect2('Famille');
verifSelect2('SFamille');
verifSelect2('Gamme');
if(test==true) {
	gamme=$("#Gamme").multipleSelect("getSelects", "val");
	//alert("Valid " + gamme);
	$('#grpCaisse').load("entrees_y.php?chargerArticle&IdGamme="+gamme);
	$("#ColisageArticle").html("");
	$("#Qte").val("");
	$('#InfoArticle').hide();
	$('#search').dialog('close');	
	}
}
function terminer(){

	var form="";
	var act = $('#act').attr('value');
	if(act == 'modif'){ form="#formMod";} else {form="#formAddGlo"; }

	    $(form).validate({
                                              
                                   rules: {
                                               
                                                'Fournisseur': "required",
												'Livreur': "required"
                                           }  
										   
										    });
//alert(document.getElementById("media").value); 
var test=$(form).valid();
verifSelect2('Fournisseur');
	
/*******************************************************************/

		if(test==true) {
		
			 jConfirm('<?php echo $trad['msg']['terminerOperation'];?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
					if(r)	{
						
				
						
				//	alert($("#ListeClt option").filter(":selected").parent("optgroup").attr("label"));
		
											if(act == 'modif'){	
										
													$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'entrees_y.php?goUpdate',
														method			:	'post'
													}); 
													return false;
												
											}else{
												
												
													$('#formAddGlo').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'entrees_y.php?goAdd',
														method			:	'post'
													}); 
													return false;
												
											}
		
					}
				})
		}
		//}//else------------------------------------------------------------------------
	}



function AjoutLigne(){
	var form="";
	var act = $('#act').attr('value');
	if(act == 'modif'){ form="#formMod";} else {form="#formAdd"; }
form="#formAdd";

	    $(form).validate({
                                              
                                   rules: {
                                               
                                                'ListeArt': "required",
												Qte: "required",
												Tarif: "required",
                                           }   
										   
										    });
//alert(document.getElementById("media").value); 
var test=$(form).valid();
var t1=verifSelect2('ListeArt');


/****************************************Controle Periode****************************************/
//************************************************************************
//************************************************************************
	
/*******************************************************************/

		if((test==true) && (t1==true)){
			
			var NomArt= $("#ListeArt").multipleSelect("getSelects", "text");
		

											if(act == 'modif'){	
										
													$('#formMod').ajaxSubmit({
														target			:	'#listBox',
														url				:	'entrees_y.php?goUpdate',
														method			:	'post'
													}); 
													return false;
												
											}else{									
														
													$('#formAdd').ajaxSubmit({
														target			:	'#listBox',
														url				:	'entrees_y.php?goAddLigne&NomArt='+NomArt,
														method			:	'post',
															success:function(){
																/*$("div[id=ligne]").remove();*/
																$("input[id=Qte]").val("");
																$("input[id=Tarif]").val("");
															}
													}); 
													return false;
												
											}
		
					
		}
		//}//else------------------------------------------------------------------------
	
	
							
		}
			function changerLigne(prochLigne){
		//'entrees.php?list&ste='+ste+'&type='+type
			$('#listBox').load('entrees_y.php?list');
			
		}
	function supLigne(ligne){
		
			var adr 	= 'entrees_y.php?supLigne='+ligne;
			  jConfirm('<?php echo $trad['msg']['ConfirmerSup'] ;?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
				if(r)	{
					$('#listBox').load(adr);
				}
			  });
	}
	
	

function find_duplicate_value(X) {

    if (X.length == 0) 
        return null;
    
    var tmp = [];
    
    for (var i = 0; i < X.length; i++) {
        var val = X[i].value;
        var pos = tmp.indexOf(val)

    //If value duplicate in array X ,verfiy value in array Y 
        if (pos > -1) 
        { 
			
			   return pos;
			

		}

        tmp.push(val);
    }
    return null;
}
	function Imprimer(IdFiche){
		/*	var adr = 'ficheControle.print.php?IdDmd='+idDmd;
			//alert(adr);
			window.location.href = adr;*/
			 options = "Width=900,Height=900" ;
			window.open( 'entree.print.php?IdFiche='+IdFiche, "edition", options ) ;
		
	}

	
</script>

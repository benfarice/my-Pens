<?php 
include("php.fonctions.php");
require_once('connexion.php');
//sqlsrv_query("SET NAMES UTF8");
SQLSRV_PHPTYPE_STRING('UTF-8') ; 
session_start();
$tableInser = "mouvements";
$sansDoublons = "NomCaisse";
$cleTable = "idMouvement";
$nom_sansDoublons = "Nom de la caisse";
$IdDepot="1";
$UserId="1";


if(isset($_GET['VideSession'])){
	unset($_SESSION['lignes']);
	exit;
}
if(isset($_GET['Act'])){

	
$error="";
	/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	/************* desactivation de tous les fiches pour le groupe ou clt spécifié**/
	$paramsUpd= array($_GET['idCltGrp']) ;	
	$reqUpdate="update ".$tableInser." set etat=0 where idTypeClient  =(?)";
	$stmtUp = sqlsrv_query( $conn, $reqUpdate, $paramsUpd );
	if( $stmtUp== false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : Modif fiche ".$errors[0]['message'] . " <br/> ";
	}
	/************* activation de la fiche spécifié**/

	$paramsUpdF= array($_GET['ID']) ;	
	$reqUpdateF="update ".$tableInser." set etat=1 where IdFiche  =(?)";
	$stmtUpF = sqlsrv_query( $conn, $reqUpdateF, $paramsUpdF );
	if( $stmtUpF== false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : Modif fiche ".$errors[0]['message'] . " <br/> ";
	}
	if( $error=="" ) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			alert('L\'activation a été effectuée.');
			$('#box').dialog('close');
			rechercher();
		</script>
		
<?php
	sqlsrv_close( $conn );  
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}

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
		$('#listBox').load('details_tarifs.php?list');
				//charger('details_tarifs.php?list');
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
			//echo $_SESSION['totalHT'];
			
	}		
exit();
}
if (isset($_GET['goAddLigne'])){

//parcourir($_GET);return;

$capture_field_val="";
$ligneArray=array();
 
		  // echo "avant".$IndexLigne."<br>";
$a="";
if(isset($_POST["mytext"])){    

    for($i=0;$i<count($_POST["mytext"]);$i++){

       $capture_field_val .= $_POST["mytext"][$i] . ",". $_POST["mytext2"][$i] ."|";
	   
					
	   if( (isset($_SESSION['lignes'])) && (count($_SESSION['lignes'])!=0))  {
	//   echo count($_SESSION["lignes"]);
		$t=0;	
		
	
			  foreach($_SESSION['lignes'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article et mm qte
				
					if(($contenu["IdArticle"]==$_POST["ListeArt"]) && ($contenu["Qte"]==$_POST["mytext"][$i]))
					{
						?>
						<script language="javascript" type="text/javascript">
							alert('Attention,la quantité <?php echo $_POST["mytext"][$i];?> est déjà spécifier à l\'article <?php echo $_POST["ListeArt"];?> ');
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
						if(($_POST["mytext"][$i]!="") && ($_POST["mytext2"][$i]!="") )
						{
							$ligneArray["IdLigne"]=$IndexLigne;
							$ligneArray["IdArticle"]=$_POST["ListeArt"];
							$ligneArray["NomArt"]=$_GET["NomArt"];
							$ligneArray["Qte"]=$_POST["mytext"][$i];
							$ligneArray["Tarif"]=$_POST["mytext2"][$i];
							$_SESSION['lignes'][$IndexLigne]= $ligneArray;
						}
						
			  }
		  }
		  else {// une premiere insertion sans controle
						 //$IndexLigne+=1;
			if(($_POST["mytext"][$i]!="") && ($_POST["mytext2"][$i]!="") )
							{
								$IndexLigne=0;
								 $ligneArray["IdLigne"]=$IndexLigne;
								 $ligneArray["IdArticle"]=$_POST["ListeArt"];
								 $ligneArray["NomArt"]=$_GET["NomArt"];
								$ligneArray["Qte"]=$_POST["mytext"][$i];
								$ligneArray["Tarif"]=$_POST["mytext2"][$i];
								$_SESSION['lignes'][$IndexLigne]= $ligneArray;
					}
		  }
	
	//   echo "apres".$IndexLigne."<br>";

    }
	?>
		<script language="javascript">		
		<?php //echo $_SESSION['ligneCourante']; ?>		
			changerLigne();
				</script>
	<?php
	
	// parcourir($_SESSION['lignes']);
}


exit;
}




if(isset($_GET['goMod'])){


exit;
	
}


if(isset($_GET['goAdd'])){
	//parcourir($_POST);RETURN;

	/*echo "<br>";
		parcourir($_SESSION["lignes"]);*/
		$IdCltGrp="";
	if($_POST["GroupeClt"]!=""){// choix d'un groupe de clt
		$IdCltGrp=$_POST["GroupeClt"];
		$TypeCltGrp="Groupe";
	}
	// choix d'un  clt
	else {
		$IdCltGrp=explode(",",$_POST["ListeClt"]);
	
		$IdCltGrp=$IdCltGrp[1];
		$TypeCltGrp="Client";
	}
		//echo $IdCltGrp;
		$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}

//-----------------Verifier si le clt ou grp ont deja une fiche de tarif----------------//
$reqVerf="select idFiche from ".$tableInser." where etat=1 and IdTypeclient =".$IdCltGrp;
$stmtVerif = sqlsrv_query( $conn, $reqVerf, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ) );	

if( $stmtVerif === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Verif fiche $reqVerf ".$errors[0]['message'] . " <br/> ";
}
//-----------------Add Article----------------//

//echo $reqVerf;
$row_count = sqlsrv_num_rows( $stmtVerif );
 
// s 'il a des fiches on modifie l'etat
if($row_count!=0){
	sqlsrv_fetch($stmtVerif) ;
	$IdFicheDes = sqlsrv_get_field($stmtVerif, 0);
	$paramsUpd= array($IdFicheDes) ;	
	$reqUpdate="update ".$tableInser." set etat=0 where IdFiche  =(?)";
	$stmtUp = sqlsrv_query( $conn, $reqUpdate, $paramsUpd );
	if( $stmtUp== false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : Modif fiche ".$errors[0]['message'] . " <br/> ";
	}
}
$DateFiche=date("Y-m-d");
$reqInser1 = "INSERT INTO ".$tableInser." ([idTypeClient] ,[date]  ,[operateur] ,[idDepot] ,[type],[etat]) values 	(?,?,?,?,?,?)";
$params1= array($IdCltGrp,$DateFiche,$UserId,$IdDepot,$TypeCltGrp,1) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );


if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout fiche ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IDFiche--------------------------------//
$sql = "SELECT max(IdFiche) as IdFiche FROM ficheTarifs";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdFiche: ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFiche = sqlsrv_get_field( $stmt2, 0);
//----------------------Add Detail fiche --------------------------//

 foreach($_SESSION['lignes'] as $ligne=>$contenu)
{

	$reqInser2 = "INSERT INTO  tarifs([idArticle],[qteMin],[pvHT],[idDepot],idFiche ) values (?,?,?,?,?)";
			$params2= array($contenu["IdArticle"],$contenu["Qte"],$contenu["Tarif"],$IdDepot,$IdFiche) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {
				$error.="Erreur : ".parcourir(sqlsrv_errors()) . " <br/> ";
				break ;
			}			
}
if( $error=="" ) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			alert('L\'ajout a été effectué.');
			$('#box').dialog('close');
			rechercher();
			 $("#GroupeClt").val("");
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
				<div class="etiqForm" id="SYMBT" > Client/Groupe client : </div>				</td>
				
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
			id="DATE_PIECE" > <strong>Article</strong> : </div>
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
					<SPAN class="etiqForm" id="DATE_PIECE" ><strong>Quantité Min:</strong></SPAN>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
		  <input name="button3" type="button" title="Ajouter tarif" class="bouton32" onClick="AjoutLigne();" value="Ajouter tarif" 
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

	   filter: true,placeholder:'S&eacute;lectionnez l\'article ',single:true,maxHeight: 100
	});

</script>


<?php
	
exit;
}

if (isset($_GET['pageAudios']))
{	
exit();
}


if (isset($_GET['pageVideos']))
{  
}



if (isset($_GET['page']))
{
exit();
} 
if(isset($_GET['chargerSecteur'])){

exit;
}
if(isset($_GET['chargerCaisse'])){
exit;
}
if (isset($_GET['searchCaisse'])){
}
if (isset($_GET['add'])){

?>

<div id="resAdd" style="padding:5px">&nbsp;</div>

<form id="formAddGlo" action="NULL" method="post" name="formAddGlo"> 
			<div id="resAdd"></div>
<table width="101%" border="0" align="center" >
			  <tr>
				<td width="23%" valign="middle" >
				<div class="etiqForm" id="SYMBT" > Client/Groupe client : </div>				</td>
				
				<td width="30%">
				<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
				<div align="left">
		
		<?php 
			//	echo ChargerSelect("pointvente","Designation","IdPointVente");?>
					<?php                              
						  $Options = "";
				          	
						/*$req="select IdType,Designation, CodeClient, Nom,Prenom from typeclients t 
								inner join clients clt  on clt.IdType=t.IdType
									";	*/
								
						$req="	select IdType,Designation, IdTypeclient,idClient, Nom,Prenom 
								from typeclients t 							
								inner join clients clt  on clt.IdTypeclient=t.IdType";		
			
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
						  // echo "mmmm".sqlsrv_num_rows($res) ;   
						   
				 if(sqlsrv_num_rows($res) !=0){
							   $i=0;
						
						
						$groups = array();
						$i=0;
							 while($row=sqlsrv_fetch_array($res)){
							 
						/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
										 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
										 
										 
								$key = $row['IdType'];
								$i=$i+1;
								if (!isset($groups[$key])) {
									$groups[$key] = array();
									$groups[$key]['IdType']=$row['IdType'];
									$groups[$key]['Designation']=$row['Designation'];
										
									
								} //else {
								if($row['IdTypeclient']!=""){
									$groups[$key][$i]['Nom'] = $row['Nom'];
									$groups[$key][$i]['Prenom'] = $row['Prenom'];
									$groups[$key][$i]['idClient'] = $row['idClient'];
								
								}
							}

			
				//	parcourir($groups);
					foreach($groups as $u=>$v){
							
								$Options.= '<optgroup label='.$v['Designation'].'  >';
								
								
									foreach($v as $r){
									if(is_array($r)){
										$Options.= "<option value=".$v['IdType'].",".$r['idClient']." >".$r['Prenom']." ".$r['Nom']."</option>";
									}
								}
						$Options.= "</optgroup>";
					 }
			 }
			

                                           ?>
				<input id="GroupeClt" name="GroupeClt" type="hidden"/>
			 <select id="ListeClt" name="ListeClt" Class="Select ListeClt" style="width:350px">

					<?php echo   $Options;?>
					   </select>
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
			id="DATE_PIECE" > <strong>Article</strong> : </div>
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
					<SPAN class="etiqForm" id="DATE_PIECE" ><strong>Quantité Min:</strong></SPAN>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
		  <input name="button3" type="button" title="Ajouter tarif" class="bouton32" onClick="AjoutLigne();" value="Ajouter tarif" 
		  action="ajout" style="width:150px;" />
		</TD>
		</TR>
		</table>
		</form>


</div>
<DIV class="divList">

<h3>Liste des articles</h3>
	<div id="listBox" style=""></div>
	
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
          $(wrapper).append('<div id="ligne" style="width:320px; margin-bottom:10px;clear:both" > <input id="x'+xx+'" size="10" action="mytext"  class="inputDuree" type="text"  name="mytext[]" onkeypress="return isEntier(event,this)" /> &nbsp;&nbsp;&nbsp;&nbsp; <input id="y'+xx+'"  size="10" onkeypress="return isDecimal(event,this)"  action="mytext2" class="inputDuree" type="text"  name="mytext2[]" />&nbsp;&nbsp;<div style="width:20px; display:inline-block "><input type="button"  class="remove_field" ></input></div> </div> ');
        }


    });
	
	  $(wrapper).on("click",".remove_field", function(e){
	 //user click on remove text
        e.preventDefault(); $(this).parent('div').parent('div').remove(); xx--;
    })
});
 $('#ListeClt').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez les clients ',single:true,maxHeight: 200,  

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

	   filter: true,placeholder:'S&eacute;lectionnez l\'article ',single:true,maxHeight: 100
	});

</script>


<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){

	$IdCltGrp=""; $params = array();
	$sqlA = "
		SELECT 
			idFiche,t.idTypeClient,etat,date as dateFiche,type,nom,prenom,Designation,
			c.idTypeClient
		FROM 
			$tableInser t
			left join typeClients  tc on tc.IdType= t.IdTypeclient
			left join Clients  c on c.IdClient= t.IdTypeclient
			where t.idDepot=$IdDepot
			
		";
		
	if((isset($_POST["GroupeClt"])) && ($_POST["GroupeClt"]!="")){// choix d'un groupe de clt
		$IdCltGrp=$_POST["GroupeClt"];
			$sqlA.= " and t.idTypeClient = ? ";
			 $params = array($IdCltGrp);
	}
	// choix d'un  clt
	else {
				if((isset($_POST["ListeCltRech"])) && ($_POST["ListeCltRech"]!=""))
					{
						$tabC=explode(",",$_POST["ListeCltRech"]);	
						$IdCltGrp=$tabC[1];
						$idGrp=$tabC[0];
						$sqlA.= " and t.idTypeClient = ? and c.idTypeClient= ?";
						$params = array($IdCltGrp,$idGrp);
					
					}
	}
		if($IdCltGrp!="") {
			
		}
	//parcourir($params);	
//$sqlA =" select * from $fa ";
	$sqlC=" order by idFiche Desc " ;
	$sql=$sqlA.$sqlC;
	
	 $resAff=sqlsrv_query( $conn, $sql,$params);  
	 if( $resAff === false) {
  //  die( print_r( sqlsrv_errors(), true) );
			$errors = sqlsrv_errors();
		echo "Erreur : Modif fiche ".$errors[0]['message'] . " <br/> ";
		
}
	?>
		<form id="formSelec" method="post">
	<table width="100%" border="0">
      <tr class="entete">
        <td width="30%">Client/Groupe </td>
        <td width=""> Date  </td>
		<td>Action</td>
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
	
		while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
				
		if($row['etat']=='1') { $cl="act";}
		else {$cl="des"; }
		
			if($i%2 == 0) $c = "pair";
			else $c="impair";
			?>
			<tr  class="<?php echo $cl; ?>">
				<td align="left"  > <?php 
				if($row['type']=='Groupe')  echo $row['Designation'];
				else echo  $row['nom']." ".$row['prenom']; ?> </td>
				<td align="left" > <?php 	echo date_format($row['dateFiche'], 'm/d/Y');?> </td>
				<td align="center">
				
				<?php
					if($cl=="des") { 
					?>
					<input type="button" title="Activer" action="ActiverM" value="Activer"
						class="BtnEtatM" onClick="ActiverM('<?php echo $row['idFiche']; ?>','<?php echo $row['idTypeClient']; ?>');"   />  
					<?php }
					
				?>
				</td>
				<td align="center">
					<span class="boutons"> 
					<input type="button" title="Modifier" action="mod" class="b" onClick="modifier('<?php echo $row['idFiche']; ?>');" />  
					</span>
			  </td>			
			  <td align="center">
				<input type="checkbox" class="checkLigne" name="<?php	echo $row['idFiche']; ?>" value="<?php	echo $row['idFiche']; ?>" />
			  </td>
			  </tr></li>
			 <?php
			$i++;
		}
		
	?>	
    </table>
	<!--</div>-->
    </form>
	<script language="javascript" type="text/javascript">
	 $("#GroupeClt").val("");
	</script>
	<?php 
exit;}
?>
<?php include("header.php"); ?>
<script src="js/jquery.multiple.select2.js" type="text/javascript"></script>
<div class="pageBack" >



<div id="box"> </div>
<div class="contenuBack">
	<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;Paramétrage &nbsp;<img src="images/tri.png" />
		&nbsp;Tarifs</div>

	

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	<!--Recherche CH -->
		<table width="100%" border="0"  >
			  <tr>
			
				<td width="23%" valign="middle" style="display:none" >
				<div class="etiqForm" id="SYMBT" > Client/Groupe client : </div>				</td>
				
				<td width="30%" style="display:none">
				<!---<input class="formTop" name="COLBQ" type="hidden" size="30" value="4"/>-->
				<div align="left" >
		
		<?php 
			//	echo ChargerSelect("pointvente","Designation","IdPointVente");?>
					<?php                              
						  $Options = "<optgroup label='Tout sélectionner' checkd></optgroup>";
				          	
						/*$req="select IdType,Designation, CodeClient, Nom,Prenom from typeclients t 
								inner join clients clt  on clt.IdType=t.IdType
									";	*/
								
						$req="	select IdType,Designation, IdTypeclient,idClient, Nom,Prenom 
								from typeclients t 							
								inner join clients clt  on clt.IdTypeclient=t.IdType";		
			
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
						  // echo "mmmm".sqlsrv_num_rows($res) ;   
						   
				 if(sqlsrv_num_rows($res) !=0){
							   $i=0;
						
						
						$groups = array();
						$i=0;
							 while($row=sqlsrv_fetch_array($res)){
							 
						/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
										 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
										 
										 
								$key = $row['IdType'];
								$i=$i+1;
								if (!isset($groups[$key])) {
									$groups[$key] = array();
									$groups[$key]['IdType']=$row['IdType'];
									$groups[$key]['Designation']=$row['Designation'];
										
									
								} //else {
								if($row['IdTypeclient']!=""){
									$groups[$key][$i]['Nom'] = $row['Nom'];
									$groups[$key][$i]['Prenom'] = $row['Prenom'];
									$groups[$key][$i]['idClient'] = $row['idClient'];
								
								}
							}

			
				//	parcourir($groups);
					foreach($groups as $u=>$v){
							
								$Options.= '<optgroup label='.$v['Designation'].'  >';
								
								
									foreach($v as $r){
									if(is_array($r)){
										$Options.= "<option value=".$v['IdType'].",".$r['idClient']." >".$r['Prenom']." ".$r['Nom']."</option>";
									}
								}
						$Options.= "</optgroup>";
					 }
			 }
			

                                           ?>
				<input id="GroupeCltR" name="GroupeCltR" type="text"/>
			 <select id="ListeCltRech" name="ListeCltRech" Class="Select ListeCltRech" style="width:350px">

					<?php echo   $Options;?>
					   </select>
						</div>
				</td>
			
		      <td rowspan="2" style="display:none"  >	<span class="actionForm">      
          <input name="button" type="button" id="Rechercher"  onClick="rechercher();" value="Rechercher" class="bouton32" action="rech" title="Rechercher "//>
			      <input name="button2" type="reset" onClick="" value="Effacer" class="bouton32" action="effacer" title="Effacer"/></span><br/></td>
			  <td width="25%" rowspan="2"   style="" align="center"><span class="actionForm">
			    <input name="button3" type="button" title="Ajouter tarif" class="bouton32" onClick="ajouter();" value="Ajouter tarif" action="ajout" style="width:150px;" />
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

	<?php include("footer.php"); ?>
</div>

<script language="javascript" type="text/javascript">
 $('#ListeCltRech').multipleSelect({
	   filter: true,placeholder:'S&eacute;lectionnez les clients ',single:true,maxHeight: 200,  

			    onOptgroupClick: function(view) {
			 
			           //$("#ListeClt").multipleSelect("uncheckAll");
					
					   
                var values = $.map(view.children, function(child){
                    return child.value;
                }).join(', ');
				//alert(values);
				//recuprer les elements selectionnées ,ListeVal[0] contien l'IdGroup
				var ListeVal = values.split(","); 			
				if(view.checked==true) {	
					 $("#GroupeCltR").val(ListeVal[0] );
				}
					
            },
			onClick: function(view) {
               $("#GroupeCltR").val("");
            }
			
	});
function ActiverM(id,idCltGrp){
	jConfirm('Confirmer l\'opération ?', null, function(r) {
		if(r)	{
		var url='details_tarifs.php?Act&ID='+id+'&idCltGrp='+idCltGrp;
		$('#resG').html('').load(url);
							}
	});
}

function suppression(idPos){

			jConfirm('Confirmer la suppression ?', null, function(r) {
						if(r)	{
							$('#act').attr('value','supp'); 
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'details_tarifs.php?suppression&idPos='+idPos,clearForm:false});		
						}
					})
	//	$('#box').load(url).dialog('open');
	
}
function modification(nomPromo){

		$('#act').attr('value','modif'); 
		var url='details_tarifs.php?modification&nompromo='+nomPromo ;
	
		$('#box').load(url).dialog('open');
	
}
	function filtrer(){
	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'details_tarifs.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){

		$('#formRechF').ajaxSubmit({target:'#formRes',url:'details_tarifs.php?rech'})
			//clearForm('formRechF',0);
	}

function ajouter(){

		$('#act').attr('value','add');
		var url='details_tarifs.php?add';
	
		$('#box').html('load').load(url).dialog('open');
	
}

  $('body').on('keypress', '#ListeCltRech', function(args) {
   if (args.keyCode == 13) {
       $("#Rechercher").click();
       return false;
   }
});

$(document).ready(function(){

	/*$("label[data-group=group_0]").parent(".group").css("border","1px solid red");
		$("label[data-group=group_0]").parent(".group").addClass("selected");*/
	     $("#ListeCltRech").multipleSelect("uncheckAll");
		$('#formRes').load('details_tarifs.php?aff');
				$('#box').dialog({
					autoOpen		:	false,
					width			:	1000,
					height			:	540,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	false,
					draggable		:	false,
					title			:	'Gestion des tarifs',
					open: function(event, ui) { 
    //hide close button.
    $(this).parent().children().children('.ui-dialog-titlebar-close').hide();
},
	
					buttons			:	{
						"Fermer"		: function(){
							$('#resG').load('details_tarifs.php?VideSession');
							$(this).dialog('close');
						},
						"Terminer "	: function() {
							terminer();
						
						}
					 }
			});
  });
function modifier(id){
		$('#act').attr('value','mod');
		var url='details_tarifs.php?mod&ID='+id;
		$('#box').html('').load(url).dialog('open');
	
}
	

function terminer(){

	var form="";
	var act = $('#act').attr('value');
	if(act == 'modif'){ form="#formMod";} else {form="#formAddGlo"; }

	    $(form).validate({
                                              
                                   rules: {
                                               
                                                'ListeClt': "required"
                                           }  
										   
										    });
//alert(document.getElementById("media").value); 
var test=$(form).valid();
var t1=verifSelect2('ListeClt');


/****************************************Controle Periode****************************************/
//************************************************************************
//************************************************************************
	
/*******************************************************************/

		if((test==true) && (t1==true)){
		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						
				
						
				//	alert($("#ListeClt option").filter(":selected").parent("optgroup").attr("label"));
		
											if(act == 'modif'){	
										
													$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'details_tarifs.php?goUpdate',
														method			:	'post'
													}); 
													return false;
												
											}else{
												
												
													$('#formAddGlo').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'details_tarifs.php?goAdd',
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
                                               
                                                'ListeArt': "required"
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
														url				:	'details_tarifs.php?goUpdate',
														method			:	'post'
													}); 
													return false;
												
											}else{									
												
													$('#formAdd').ajaxSubmit({
														target			:	'#listBox',
														url				:	'details_tarifs.php?goAddLigne&NomArt='+NomArt,
														method			:	'post',
															success:function(){
																$("div[id=ligne]").remove();
																$("input[id=x1]").val("");
																$("input[id=y1]").val("");
															}
													}); 
													return false;
												
											}
		
					
		}
		//}//else------------------------------------------------------------------------
	
	
							
		}
			function changerLigne(prochLigne){
		//'details_tarifs.php?list&ste='+ste+'&type='+type
			$('#listBox').load('details_tarifs.php?list');
			
		}
	function supLigne(ligne){
		
			var adr 	= 'details_tarifs.php?supLigne='+ligne;
			  jConfirm('Voulez-vous vraiment supprimer cette ligne ?', null, function(r) {
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


	
</script>

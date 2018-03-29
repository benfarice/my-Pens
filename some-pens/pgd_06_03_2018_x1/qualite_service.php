<?php	  
include("php.fonctions.php");
require_once('connexion.php');
//mysql_query("SET NAMES UTF8");

if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
$tableInser = "vehicules";
$cleTable = "idVehicule";
$UserId="1";
$IdDepot="1";
$nom_sansDoublons = "Numéro d\'immatriculation";


if (isset($_GET['VerifPeriod'])){
//-----------------Verifier periode deja occupé----------------//
$paramsVerif= array() ;
$reqVerf="select IdQuestionnaire from questionnaires
 where cast(DateD AS date))>=convert(date, '".($_GET['DateF'])."',105) or 
 cast(DateF)<=convert(date, '".($_GET['DateD'])."',105)
 ";
 
 $reqVerf="select * from questionnaires 
		 where (
		 convert(date, '".($_GET['DateD'])."',105) >= convert(date, DateD,105) AND convert(date, '".($_GET['DateD'])."',105) <= convert(date, DateF,105) 
		 OR  convert(date, '".($_GET['DateF'])."',105) <=convert(date, DateF,105) AND convert(date, '".($_GET['DateF'])."',105) >= convert(date, DateD,105) 
		   OR convert(date, '".($_GET['DateD'])."',105) <=convert(date, DateD,105) AND convert(date, '".($_GET['DateF'])."',105) >= convert(date, DateF,105) 
		  )
		and Etat=1 and IdDepot=".$IdDepot."
		 AND IdVille=".$_GET['IdVille'];
if ((isset($_GET['IdQuestionnaire'])) && ($_GET['IdQuestionnaire']!="") ){
	 $reqVerf.= " and IdQuestionnaire <>".$_GET['IdQuestionnaire'];
 }

 //echo $reqVerf;return;

$stmtVerif = sqlsrv_query( $conn, $reqVerf, $paramsVerif, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ) );	

if( $stmtVerif === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Verif  $reqVerf ".$errors[0]['message'] . " <br/> ";
}
$row_count = sqlsrv_num_rows( $stmtVerif );
if($row_count!=0){

	//echo "periode deja defini $row_count";    
	 echo "0";
	return;
}else if($row_count==0){	
echo "1";
}
else {	
echo "-1";
}
	  /* if( (!isset($_SESSION['lignesCmd'])) || (count($_SESSION['lignesCmd'])==0))  
		   echo "0";
	   else echo "1";*/
 exit;
				
}

///////////////////////////////////////////////on supprime une ligne
if(isset($_GET['supLigne'])){
		
		$ligne = $_GET['supLigne'];
		

	unset($_SESSION['questions'][$ligne]); // remove item at index 0
	$_SESSION['questions'] = array_values($_SESSION['questions']); // 'reindex' array

	//parcourir($_SESSION['lignes']);//return;
			?>
		<script language="javascript">
	//	$('#listQues').load('qualite_service.php?listQues');
		 changerLigne();
		$("#nbrQuestion").val(<?php echo count($_SESSION['questions']);?>);
				//charger('details_tarifs.php?list');
			</script>
		
		<?php
		
		exit;
}


if(isset($_GET['listQues'])){
///////////////////////////////////////on liste les lignes
?>
<style>
.ligneEdit:first-child{	border-top:none;}
</style>
<?php
	if(isset($_SESSION['questions']) && count($_SESSION['questions']) != 0){		
			$i=0;
			?>
			<table width="100%" cellpadding="10" cellspacing="1" style="border:1px solid #ebebeb">
			    <tr class="entete">       
					<!-- <td > Quantité </td>-->
					<td> <?php echo $trad['label']['QuestionEn'];?> </td>
					<td><?php echo $trad['label']['QuestionFr'];?> </td>
					<td><?php echo $trad['label']['QuestionAr'];?> </td>
					<td  colspan="2"></td>
				</tr>
			<?php
			$k=0;
			$c=0;
			foreach($_SESSION['questions'] as $ligne=> $row){
				if($i==0){
					$ligne1=' style="border-top:1px solid #778;"';
				}else{
					$ligne1='';
				}
				$k++;
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			
				?>
			<div onDblClick="modLigne('<?php echo $ligne; ?>');" class="ligneEdit" style="margin-bottom:-10px;">
	
			<form id="formLigne" action="" method="post" name="<?php echo $ligne; ?>" >			
				<tr  class="<?php echo $c; ?>">				
					<td width="230" valign="top" align="left"><?php echo $row['QuestionEn']; ?></td>
					<td width="50"  valign="top" align="left" style=""><?php echo $row['QuestionFr']; ?>	</td>				
					<td width="110" valign="top"  align="right"><?php echo $row['QuestionAr']; ?></td>
					<td width="" align="center">
					<input type="button" action="" class="sup32" value="" onClick="supLigne('<?php echo $ligne; ?>');"
					style="border:0px;cursor:pointer"/></td>
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
	else {
		echo "<center>".stripcslashes($trad['msg']['AjoutQuestion'])."</center>"; 
	}
exit();
}
if (isset($_GET['goAddQuestion'])){
$ligneArray=array(); 
		//parcourir($_POST);
if((isset($_POST["QuestionAr"])) && (isset($_POST["QuestionFr"])) && (isset($_POST["QuestionEn"]))) {    

		
	   if( (isset($_SESSION['questions'])) && (count($_SESSION['questions'])!=0))  {
	 //  echo count($_SESSION["lignes"]);
					$IndexLigne=count($_SESSION['questions']);		
							$ligneArray["IdLigne"]=$IndexLigne;
							 $ligneArray["QuestionAr"]=$_POST["QuestionAr"];
							 $ligneArray["QuestionEn"]=$_POST["QuestionEn"];
							 $ligneArray["QuestionFr"]=$_POST["QuestionFr"];
							$_SESSION['questions'][$IndexLigne]= $ligneArray;
					
			  
		  }
		  else {// une premiere insertion sans controle
						 //$IndexLigne+=1;
		
								 $IndexLigne=0;
								 $ligneArray["IdLigne"]=$IndexLigne;
								 $ligneArray["QuestionAr"]=$_POST["QuestionAr"];
								 $ligneArray["QuestionEn"]=$_POST["QuestionEn"];
								 $ligneArray["QuestionFr"]=$_POST["QuestionFr"];
								$_SESSION['questions'][$IndexLigne]= $ligneArray;
		  }
	//   echo "apres".$IndexLigne."<br>"; 
	?>
		<script language="javascript">		
		<?php //echo $_SESSION['ligneCourante']; ?>		
			changerLigne();
			
			$("#nbrQuestion").val(<?php echo count($_SESSION['questions']);?>);
			$("#boxQuestion").dialog('close');
		</script>
	<?php
}
exit;
}

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

			   if( (!isset($_SESSION['questions'])) || (count($_SESSION['questions'])==0))  {
				     ?>
				<script type="text/javascript"> 
					//alert('Merci d\'ajouter les articles ');
					jAlert("<?php echo $trad['msg']['AjoutQuestion'];?>","<?php echo $trad['titre']['Alert'];?>");
				</script>
		
		<?php
			   return;}
		
		//echo $IdCltGrp;
		$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//-----------------Verifier periode deja occupé----------------//
$paramsVerif= array() ;
$reqVerf="select IdQuestionnaire from questionnaires
 where cast(DateD AS date))>=convert(date, '".($_POST['DateF'])."',105) or 
 cast(DateF)<=convert(date, '".($_POST['DateD'])."',105)
 ";
 
 $reqVerf="select IdQuestionnaire from questionnaires 
 where (
 convert(date, '".($_POST['DateD'])."',105) >= convert(date, DateD,105) AND convert(date, '".($_POST['DateD'])."',105) <= convert(date, DateF,105) 
 OR  convert(date, '".($_POST['DateF'])."',105) <=convert(date, DateF,105) AND convert(date, '".($_POST['DateF'])."',105) >= convert(date, DateD,105) 
   OR convert(date, '".($_POST['DateD'])."',105) <=convert(date, DateD,105) AND convert(date, '".($_POST['DateF'])."',105) >= convert(date, DateF,105) 
  )
and IdDepot=".$IdDepot."
 AND IdVille=".$_POST['Ville']." and IdQuestionnaire<>".$_POST['IdQuestionnaire'];
 
// echo $reqVerf;return;

$stmtVerif = sqlsrv_query( $conn, $reqVerf, $paramsVerif, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ) );	

if( $stmtVerif === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Verif  $reqVerf ".$errors[0]['message'] . " <br/> ";
}
$row_count = sqlsrv_num_rows( $stmtVerif );
// si l'opérateur a ajouter des questions dans une mm période pr mm ville on n'annule les précédents
if($row_count!=0){
	while ($donnees =  sqlsrv_fetch_array($stmtVerif, SQLSRV_FETCH_ASSOC))
				{
					$reqUpdate = "update  questionnaires set Etat=0
					where IdDepot=".$IdDepot." and IdVille=".$_POST['Ville']." and IdQuestionnaire=".$donnees['IdQuestionnaire']."
					and IdQuestionnaire<>".$_POST['IdQuestionnaire']; 
						
						$paramsUp= array() ;
						$stmtUp = sqlsrv_query( $conn, $reqUpdate, $paramsUp );


						if( $stmtUp=== false ) {
							$errors = sqlsrv_errors();
							$error.="Error :Replace questionnaire  ".$errors[0]['message'] . " <br/> ";
						}
				}

}
//echo $row_count;

//$DateFiche=date("Y-m-d");
$Date = date("Y-m-d");

						
$reqUpdate=" update questionnaires set 	DateD=?,DateF=?,IdVille=?	
				where  IdQuestionnaire=".$_POST['IdQuestionnaire']; 
	$paramsU= array(
						$_POST["DateD"],
						$_POST["DateF"],
						$_POST["Ville"]
						//$_POST['Colisage']
						
				) ;
$stmt1 = sqlsrv_query( $conn, $reqUpdate,$paramsU );


if( $stmt1=== false ) {
    $errors = sqlsrv_errors();
    $error.="Error :Update questionnaire fiche ".$errors[0]['message'] . " <br/> ";
}


//----------------------Add Detail questionnaires --------------------------//
//parcourir($_SESSION['questions']);return;


/// update etat des question a 0 pour prendre en considération le cas de suppression des questions
$reqUp=" update DetailsQuestionnaire set 	etat=0	
				where  IdQuestionnaire=?"; 
	$paramsU= array(
						$_POST['IdQuestionnaire']
						
				) ;
$stmt3 = sqlsrv_query( $conn, $reqUp,$paramsU );


if( $stmt3=== false ) {
    $errors = sqlsrv_errors();
    $error.="Error :Update ETAT question ".$errors[0]['message'] . " <br/> ";
}
//parcourir($_SESSION['questions']);return;
 foreach($_SESSION['questions'] as $ligne=>$contenu)
{
	//sinon  modifier l'etat de la question que nous avons desactivé avant la boucle
if(isset($contenu["IdQuestion"])){
		$params= array(
						$contenu["IdQuestion"],
						$_POST['IdQuestionnaire']
						//$_POST['Colisage']
						
				) ;
					$reqUpE=" update DetailsQuestionnaire set 	etat=1	
									where   IdDetailQuestion=? and IdQuestionnaire=?"; 
						
					$stmt4 = sqlsrv_query( $conn, $reqUpE,$params );


					if( $stmt4=== false ) {
						$errors = sqlsrv_errors();
						$error.="Error :Update ETAT question to 1 ".$errors[0]['message'] . " <br/> ";
					}
					
}else {
	// si c'est un nouvelle question on l'ajoute
		$reqInser2 = "INSERT INTO  DetailsQuestionnaire(DsgQuestion_ar,DsgQuestion_en,DsgQuestion_fr,[idDepot],IdQuestionnaire )
									values (?,?,?,?,?)";
					$params2= array($contenu["QuestionAr"],$contenu["QuestionEn"],$contenu["QuestionFr"],$IdDepot,$_POST['IdQuestionnaire']) ;
					$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
					if( $stmt3 === false ) {
						$error.="error Insert new question: ".parcourir(sqlsrv_errors()) . " <br/> ";
						
					}	
}
		/*	$sql = " select IdDetailQuestion  from DetailsQuestionnaire  where IdDetailQuestion=? and IdQuestionnaire=?";
				$params= array(
						$contenu["IdQuestion"],
						$_POST['IdQuestionnaire']
						//$_POST['Colisage']
						
				) ;
			//	parcourir($params);echo $sql;return;
  	            $reponse=sqlsrv_query( $conn, $sql, $params, array( "Scrollable" => 'static' ) );	
				if( $reponse === false ) {
					$errors = sqlsrv_errors();
					$error.="Error :  ".$errors[0]['message'] . " <br/> ";
					
				}	
					// si c'est un nouvelle question on l'ajout				
				if(sqlsrv_num_rows($reponse)==0) {
					
					$reqInser2 = "INSERT INTO  DetailsQuestionnaire(DsgQuestion_ar,DsgQuestion_en,DsgQuestion_fr,[idDepot],IdQuestionnaire )
									values (?,?,?,?,?)";
					$params2= array($contenu["QuestionAr"],$contenu["QuestionEn"],$contenu["QuestionFr"],$IdDepot,$_POST['IdQuestionnaire']) ;
					$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
					if( $stmt3 === false ) {
						$error.="error Insert new question: ".parcourir(sqlsrv_errors()) . " <br/> ";
						
					}	
					
				}
				else {
					//sinon on modifier l'etat de la question que nous avons descaativé avant la boucle
					// update question	
					$reqUpE=" update DetailsQuestionnaire set 	etat=1	
									where   IdDetailQuestion=? and IdQuestionnaire=?"; 
						
					$stmt4 = sqlsrv_query( $conn, $reqUpE,$params );


					if( $stmt4=== false ) {
						$errors = sqlsrv_errors();
						$error.="Error :Update ETAT question to 1 ".$errors[0]['message'] . " <br/> ";
					}
				}*/
				
					
					
					
					
}

if( $error=="" ) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			//alert('L\'ajout a été effectué.');
		alert('<?php echo $trad['msg']['messageAjoutSucces'] ;?>');
		$('#boxClient').dialog('close');
			rechercher();
		</script>
		
<?php
unset($_SESSION['questions']);
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}
	
//}// fin else exite
/***********************/	
	exit;	
	
}
if(isset($_GET['goAdd'])){
//parcourir($_POST);return;
			   if( (!isset($_SESSION['questions'])) || (count($_SESSION['questions'])==0))  {
				     ?>
				<script type="text/javascript"> 
					//alert('Merci d\'ajouter les articles ');
					jAlert("<?php echo $trad['msg']['AjoutQuestion'];?>","<?php echo $trad['titre']['Alert'];?>");
				</script>
		
		<?php
			   return;}
		
		//echo $IdCltGrp;
		$error="";
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//-----------------Verifier periode deja occupé----------------//
$paramsVerif= array() ;
$reqVerf="select IdQuestionnaire from questionnaires
 where cast(DateD AS date))>=convert(date, '".($_POST['DateF'])."',105) or 
 cast(DateF)<=convert(date, '".($_POST['DateD'])."',105)
 ";
 
 $reqVerf="select IdQuestionnaire from questionnaires 
 where (
 convert(date, '".($_POST['DateD'])."',105) >= convert(date, DateD,105) AND convert(date, '".($_POST['DateD'])."',105) <= convert(date, DateF,105) 
 OR  convert(date, '".($_POST['DateF'])."',105) <=convert(date, DateF,105) AND convert(date, '".($_POST['DateF'])."',105) >= convert(date, DateD,105) 
   OR convert(date, '".($_POST['DateD'])."',105) <=convert(date, DateD,105) AND convert(date, '".($_POST['DateF'])."',105) >= convert(date, DateF,105) 
  )

 AND IdVille=".$_POST['Ville'];
 
// echo $reqVerf;return;

$stmtVerif = sqlsrv_query( $conn, $reqVerf, $paramsVerif, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ) );	

if( $stmtVerif === false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Verif fiche $reqVerf ".$errors[0]['message'] . " <br/> ";
}
$row_count = sqlsrv_num_rows( $stmtVerif );
// si l'opérateur a ajouter des questions dans une mm période pr mm ville on n'annule les précédents
if($row_count!=0){
	while ($donnees =  sqlsrv_fetch_array($stmtVerif, SQLSRV_FETCH_ASSOC))
				{
					$reqUpdate = "update  questionnaires set Etat=0
					where IdDepot=".$IdDepot." and IdVille=".$_POST['Ville']." and IdQuestionnaire=".$donnees['IdQuestionnaire'] ; 
						
						$paramsUp= array() ;
						$stmtUp = sqlsrv_query( $conn, $reqUpdate, $paramsUp );


						if( $stmtUp=== false ) {
							$errors = sqlsrv_errors();
							$error.="Error :Replace questionnaire  ".$errors[0]['message'] . " <br/> ";
						}
				}

}


//$DateFiche=date("Y-m-d");
$Date = date("Y-m-d");

$reqInser1 = "INSERT INTO questionnaires ([DateD] ,[DateF] ,[IdVille] ,[DateCreation]  ,[Operateur] ,[idDepot] ) 
						values 	(?,?,?,?,?,?)";
$params1= array($_POST["DateD"],$_POST["DateF"],$_POST["Ville"],$Date,$UserId,$IdDepot) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );


if( $stmt1=== false ) {
    $errors = sqlsrv_errors();
    $error.="Error :add questionnaire fiche ".$errors[0]['message'] . " <br/> ";
}

//---------------------------IdQuestionnaire--------------------------------//
$sql = "SELECT max(IdQuestionnaire) as IdQuestionnaire FROM questionnaires";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Error get  IdQuestionnaire: ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdQuestionnaire = sqlsrv_get_field( $stmt2, 0);
//----------------------Add Detail questionnaires --------------------------//

 foreach($_SESSION['questions'] as $ligne=>$contenu)
{

	/*$reqInser2 = "INSERT INTO  DetailsQuestionnaire([idArticle],[qteMin],[pvHT],[idDepot],IdQuestionnaire ,qteMax,controleQte ) values (?,?,?,?,?,?,?)";
			$params2= array($contenu["IdArticle"],$contenu["Qte"],$contenu["Tarif"],$IdDepot,$IdQuestionnaire,0,0) ;*/
			$reqInser2 = "INSERT INTO  DetailsQuestionnaire(DsgQuestion_ar,DsgQuestion_en,DsgQuestion_fr,[idDepot],IdQuestionnaire )
									values (?,?,?,?,?)";
			$params2= array($contenu["QuestionAr"],$contenu["QuestionEn"],$contenu["QuestionFr"],$IdDepot,$IdQuestionnaire) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			//echo $reqInser2;
			//parcourir($params2);
			if( $stmt3 === false ) {
				$error.="error add Detail questionnaires: ".parcourir(sqlsrv_errors()) . " <br/> ";
				break ;
			}			
}

if( $error=="" ) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			//alert('L\'ajout a été effectué.');
		alert('<?php echo $trad['msg']['messageAjoutSucces'] ;?>');
		$('#boxClient').dialog('close');
			rechercher();
		</script>
		
<?php
unset($_SESSION['questions']);
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}
	
//}// fin else exite
/***********************/	
	exit;	
}

if (isset($_GET['mod'])){
	if (isset($_SESSION['questions'])) unset($_SESSION['questions']);
	$ID = $_GET['ID'] ;
		$sqlA = "SELECT q.IdQuestionnaire,q.DateD,q.DateF ,q.IdVille
				FROM Questionnaires q			
				INNER JOIN villes v ON v.idville=q.IdVille
				where  Etat=1 and q.IdDepot=$IdDepot and 
				q.IdQuestionnaire = $ID
				";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt = sqlsrv_query($conn,$sqlA,$params,$options);
//echo $sqlA;
	$rowQ = sqlsrv_fetch_array($stmt);
	//execSQL($sql);
	//echo $sql; 
	
		$sqlB = "SELECT IdDetailQuestion,DsgQuestion_fr,DsgQuestion_en,DsgQuestion_ar
				FROM DetailsQuestionnaire q			
				where  
				IdQuestionnaire = $ID  and Etat=1
				";
	
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmtD = sqlsrv_query($conn,$sqlB,$params,$options);

		while($row=sqlsrv_fetch_array($stmtD)){	
										$key = $row['IdDetailQuestion'];
										
										if (!isset($groups[$key])) {											
											$groups[$key] = array();
											$groups[$key]['IdQuestion']=$row['IdDetailQuestion'];
											$groups[$key]['QuestionAr']=$row['DsgQuestion_ar'];
											$groups[$key]['QuestionEn']=$row['DsgQuestion_en'];
											$groups[$key]['QuestionFr']=$row['DsgQuestion_fr'];
											// etatSup pour differencier les nouvelles lignes et et les lignes supprimées
											
										} 
										}
	$_SESSION['questions']=$groups;
//	parcourir($_SESSION['questions']);
	
?>

<input type="hidden" id="nbrQuestion"/>
	<div id="resMod" style="padding:5px;">&nbsp;</div>
	<form id="formMod" action="NULL" method="post" name="formAdd1"> 
		<input type="hidden" id="IdQuestionnaire" name="IdQuestionnaire" value ="<?php echo $ID;?>" />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
			 <td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['Ville']; ?></strong> : </div>
            </td>
            <td>
        	<select  name="Ville" id="Ville"  multiple="multiple" tabindex="3" class="Select Ville" 
			style="display:visible;width:220px;">
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
        	<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['Periode']; 
			echo " ".$trad['label']['de'].":";
			 ?>  </strong>  </div>
            </td>
            <td>
           <input class="FormAdd1" value="<?php echo $rowQ["DateD"];?>" type="text" name="DateD" maxlength="10" id="DateD" onChange="verifier_date(this);" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['a']; ?> </strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="DateF"
				value="<?php echo $rowQ["DateF"];?>" 	
				maxlength="10" onChange="verifier_date(this);"  id="DateF" size="30" tabindex="1"  />
            </td>
			<td>
			<input name="button3" title="Ajouter " 
			class="bouton32" onclick="addQuestion();" value="" action="ajoutTarif" 
			style="" type="button">
			</td>
		</tr>

	
     	<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	  <div id="listQues">
	  
	  </div>
	</form>

 <?php 
 ?>
<script language="javascript" type="text/javascript">
$('#Ville').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectVille'] ; ?>',single:true,maxHeight: 100
});
 $("#Ville").multipleSelect("setSelects", [<?php echo $rowQ["IdVille"];?>]);
$("#nbrQuestion").val(<?php echo count($_SESSION['questions']);?>);
$(document).ready(function(){

		calendrier("DateD");
		calendrier("DateF");
		
	 $('body').on('change', '#Ville', function() {
	 			var Ville =$('#Ville').val();
				if(Ville!="") {
					$('div.Ville').removeClass('erroer');
					$('div.Ville button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
	  changerLigne();
});
</script>
<?php
exit;
}
if (isset($_GET['addQuestion'])){?>
	<div id="resAddQ" style="padding:5px;">&nbsp;</div>
<form id="formAddQ" action="NULL" method="post" name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
        <tr>	
        	<td valign="top"><div class="etiqForm" id="" > <strong><?php echo $trad['label']['QuestionAr']; ?></strong> : </div>
            </td>
            <td>
			<textarea rows="2" cols="57" name="QuestionAr"  style="text-align:right;font-size:18px" id="QuestionAr"></textarea>
            </td>
          </tr>
			 <tr>
	
        	<td valign="top"><div class="etiqForm" id="" > <strong><?php echo $trad['label']['QuestionEn']; ?></strong> : </div>
            </td>
            <td>
			<textarea rows="2" cols="65" name="QuestionEn" id="QuestionEn" style="text-align:left;"></textarea>
            </td>
          </tr>
		   <tr>
	
        	<td valign="top"><div class="etiqForm" id="" > <strong><?php echo $trad['label']['QuestionFr']; ?></strong> : </div>
            </td>
            <td>
			<textarea rows="2" cols="65" name="QuestionFr" style="text-align:left;" id="QuestionFr"></textarea>
            </td>
          </tr>
			<tr><td colspan="4"  height="10" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	</form>
<?php
exit;
}

if (isset($_GET['add'])){
	if (isset($_SESSION['questions'])) unset($_SESSION['questions']);
?>
<input type="hidden" id="nbrQuestion"/>

<div id="resAdd" style="padding:5px;">&nbsp;</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"> 	
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
			 <td><div class="etiqForm" id="" > <strong><?php echo $trad['label']['Ville']; ?></strong> : </div>
            </td>
            <td>
        	<select  name="Ville" id="Ville"  multiple="multiple" tabindex="3" class="Select Ville" 
			style="display:visible;width:220px;">
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
        	<td>
			 <div class="etiqForm" id="" ><strong><?php echo $trad['label']['Periode']; 
			echo " ".$trad['label']['de']." : &nbsp;";
			 ?>  </strong>  </div>
            </td>
            <td>
           <input class="FormAdd1" type="text" name="DateD" maxlength="10" id="DateD"
		   value="<?php echo date("d/m/Y");?>"
		   onChange="verifier_date(this);" size="30" tabindex="1"  />
            </td>
			<td>
			 <div class="etiqForm" id="" > <strong><?php echo $trad['label']['a']; ?>  : &nbsp;</strong>  </div>
            </td>
            <td>
            <input class="FormAdd1"   value="<?php echo date("d/m/Y");?>"  type="text" name="DateF" maxlength="10" onChange="verifier_date(this);"  id="DateF" size="30" tabindex="1"  />
            </td>
			<td>
			<input name="button3" title="Ajouter " 
			class="bouton32" onclick="addQuestion();" value="" action="ajoutTarif" 
			style="" type="button">
			</td>
		</tr>	
     	<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
 	  </table>
	  <div id="listQues">	  
	  </div>
	</form>
	
	<!-- Styles Js -->
	<script language="javascript" type="text/javascript">
$('#Ville').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectVille'] ; ?>',single:true,maxHeight: 100
});

$(document).ready(function(){
		//calendrier("DateD");
		//calendrier("DateF");
		 $("#DateD").datepicker({
			dateFormat: 'dd/mm/yyyy'		
		});
	
		 $("#DateF").datepicker({
			dateFormat: 'dd/mm/yyyy'		
		});
	
	 $('body').on('change', '#Ville', function() {
	 			var Ville =$('#Ville').val();
				if(Ville!="") {
					$('div.Ville').removeClass('erroer');
					$('div.Ville button').css("border","1px solid #ccc").css("background","#fff");
				}
	 });
});
</script>	
<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){
if (isset($_SESSION['questions'])) unset($_SESSION['questions']);
$sqlA = "
		SELECT 
			q.IdQuestionnaire,q.DateD+'-'+q.DateF AS Periode,
			DsgQuestion_fr,DsgQuestion_en,DsgQuestion_ar,v.Designation
		FROM Questionnaires q
		INNER JOIN DetailsQuestionnaire dq ON q.IdQuestionnaire=dq.IdQuestionnaire
		INNER JOIN villes v ON v.idville=q.IdVille
";
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	if(isset($_POST['IdVille']) && ($_POST['IdVille']!='') )
	{	$sqlA .=" where v.idville like ? " ;
	   $params = array("%".$_POST['IdVille']."%");
	}
	$sqlA .=" and q.Etat=1 and dq.Etat=1 and q.IdDepot=".$IdDepot;
	//ECHO $sqlA."<br>";
	$stmt=sqlsrv_query($conn,$sqlA,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	$ntRes = sqlsrv_num_rows($stmt);
	//echo "num : ".$ntRes."<br>";
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "IdQuestionnaire";
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
	while($row=sqlsrv_fetch_array($resAff)){							 
								/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
												 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
												 
												 
										$key = $row['IdQuestionnaire'];
										
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['IdQuestionnaire']=$row['IdQuestionnaire'];
											$groups[$key]['Periode']=$row['Periode'];
											$groups[$key]['Designation']=$row['Designation'];
										} 
																				
										$groups[$key][$i]['QuestionAr'] = $row['DsgQuestion_ar'];
										$groups[$key][$i]['QuestionEn'] = $row['DsgQuestion_en'];									
										$groups[$key][$i]['QuestionFr'] =$row['DsgQuestion_fr'];
									
											$i=$i+1;		
										
										}
								//		parcourir($groups);
										
	?>
<script language="javascript" type="text/javascript">
$('#cont_pages').html('<?php echo $selPages; ?>');
</script>
		<form id="formSelec" method="post">
	
	<table width="100%" border="0">
      <tr class="entete">
		<td ><?php echo $trad['label']['Periode']; ?></td>	  
        <td ><?php echo $trad['label']['QuestionAr'] ; ?></td>
        <td ><?php echo $trad['label']['QuestionEn']; ?></td>
		<td ><?php echo $trad['label']['QuestionFr']; ?></td>
		<td ></td>
      
  </tr>

	<?php
		$i=0;
		foreach($groups as $u=>$v){
			?>
			<tr style="background:#ebebeb; font-weight:bold;"><td width="190" ><?php echo $v['Periode'];?></td>
			<td colspan="3"></td>
			<td  style="border:1px solid #ccc;" ><span class="boutons"> 
						<input type="button" title="Modifier" action="mod" class="mod48" 
						onClick="modifier('<?php echo $v['IdQuestionnaire']; ?>');" />  
					</span></td>
			</tr>
			<?php 
		
		
			if(is_array($v)){
				foreach($v as $r){
					
					if($i%2 == 0) $c = "pair";
					else $c="impair";	
					$i+=1;
						if(is_array($r)){
			?>
			<tr  class="<?php //echo $c;?>" style="border:1px solid #ebebeb" >
				<td></td>
				<td   align="right" style="font-size:18px;" > <?php echo $r['QuestionAr']; ?> </td>			
				<td  align="left" dir="ltr" > <?php echo $r['QuestionEn']; ?> </td>
				<td  align="left"  dir="ltr" > <?php 	echo $r['QuestionFr'];?> </td>					
			  </tr>
			 <?php
				}}
			}
				
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
				
				style		: {		classes	: 'u , ,i-tooltip-rounded ui-tooltip-shadow'	},
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'qualite_service.php?delPlusieursArticle',clearForm:false});		
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
<div id="infosGPS" style="border-bottom:1px dashed #778; ">
<div style="display:inline-block">&nbsp;<?php echo $trad['Menu']['parametrage']; ?>&nbsp;<img src="images/tri.png" /></div>
<div style="display:inline-block">&nbsp;<?php echo $trad['label']['QualityService']; ?>&nbsp;</div>
	</div>

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	
			<table width="71%" border="0" align="center" >
				  <tr>
					<td  valign="middle">
					<div class="etiqForm" id="SYMBT" ><?php echo $trad['label']['Ville']; ?>: </div>				</td>
					<td >
				<select  name="IdVille" id="IdVille"  multiple="multiple" tabindex="3" class="Select " style="display:visible;width:220px;">
			<?php $sql = "select idville, Designation from villes ";
                       $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option value="<?php echo $donnees['idville'] ?>"><?php echo $donnees['Designation']?></option>
                         <?php
                          }
                         ?>
						   
			</select>			</td>
				  <td width="" rowspan="2" >	<span class="actionForm">      
			  <input name="button" type="button" onClick="rechercher();" value="<?php echo $trad['button']['rechercher']; ?>" class="bouton32" action="rech"  title="<?php echo $trad['button']['rechercher']; ?> " />
					  </td>
				  <td width="25%" rowspan="2"   style="border-<?php echo $_SESSION['align']; ?>:1px solid #778;"><span class="actionForm">
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

<div id="formRes" style="overflow-y:scroll;min-height:340px;width:1180px;"></div>
<input type="hidden" id="act"/>

</div>
<div id="boxClient"> </div><div id="boxQuestion"> </div>
<div id="boxConfirm"> 
<?php echo $trad['msg']['PeriodeDejaDefini'];?>
</div>
<script language="javascript" type="text/javascript">

	   
$(document).ready(function(){	
$('#IdVille').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectVille'] ; ?>',single:true,maxHeight: 100
});
   $("#IdVille").multipleSelect("setSelects", [1]);
  		//$('#formRes').load('qualite_service.php?aff');
		rechercher();
				$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	1200,
					height			:	500,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	false,
					draggable		:	true,
					title			:	'<?php echo $trad['label']['titreBox']; ?>',
					buttons			:	{
						"<?php echo $trad['button']['Annuler']; ?>"		: function(){
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['enregistrer']; ?>"	: function() {
							terminer();
						
						}
					 }
			});
					$('#boxConfirm').dialog({
					autoOpen		:	false,
					width			:	400,
					height			:	200,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	false,
					draggable		:	true,
					title			:	'<?php echo $trad['titre']['Confirm']; ?>',
					buttons			:	{
						"<?php echo $trad['button']['Non']; ?>"		: function(){
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['Oui']; ?>"	: function() {
							terminer('annuler');
						
						}
					 }
			});
		$('#boxQuestion').dialog({
					autoOpen		:	false,
					width			:	800,
					height			:	450,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	false,
					draggable		:	true,
					title			:	'<?php echo $trad['label']['titreBox']; ?>',
					buttons			:	{
						"<?php echo $trad['button']['Annuler']; ?>"		: function(){
							$(this).dialog('close');
						},
						"<?php echo $trad['button']['enregistrer']; ?>"	: function() {
							goAddQuestion();
						
						}
					 }
			});
  });
	function filtrer(){	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'qualite_service.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){
	$('#formRes').html('<center><br/><br/><img src="images/loading.gif" /></center>');
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'qualite_service.php?rech'})
		clearForm('formRechF',0);
	}

function ajouter(){
		$('#act').attr('value','add');
		var url='qualite_service.php?add';	
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}

function modifier(id){
		$('#act').attr('value','mod');
		var url='qualite_service.php?mod&ID='+id;
		$('#boxClient').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
}
function addQuestion(){
	
		var url='qualite_service.php?addQuestion';
		
		$('#boxQuestion').html('<center><br/><br/><img src="images/loading.gif" /></center>').load(url).dialog('open');	
		
}      
/*Designation: "required",²²
                                                Colisage: "required",
												Codeabarre : "required",
												Pa:"required",
												Unite:"required",
												Fournisseur:"required",
												Famille:"required",
												Tva:"required"*/   
function terminer(annuler){
	

	var form="";
	var act = $('#act').attr('value');
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: { 
												Ville:"required",
												DateD:"required",
												DateF:"required"
												
                                        } 
		});
	var test=$(form).valid();
	var nbrQuestion=$("#nbrQuestion").val();
	
	verifSelect2('Ville');
	var DateD=$("#DateD").val();
	var DateF=$("#DateF").val();
if(CompareDateSupEgal(DateD,DateF,"La date de début doit être supérieur à la date de fin")==false){
	test=false;
	jAlert("<?php echo $trad['msg']['CompareDate'];?> ","<?php echo $trad['titre']['Alert'];?>");
}	
		if(test==true){	
		//nbrQuestion=1;
			if(	nbrQuestion>0){	
				 var Verif="";
	
				 var IdVille= $('#Ville').multipleSelect("getSelects");
	if(act == 'mod'){
		var IdQuestionnaire=$("#IdQuestionnaire").val();
		url="qualite_service.php?VerifPeriod&DateD="+DateD+"&DateF="+DateF+"&IdVille="+IdVille+"&IdQuestionnaire="+IdQuestionnaire;} 
	else {url="qualite_service.php?VerifPeriod&DateD="+DateD+"&DateF="+DateF+"&IdVille="+IdVille; }			 
	$.get(url, function(response) {
      Verif = response;	 
	  // verifier si la periode n'existe pas
	/// test if  operator want replace question
	if (typeof(annuler) !== 'undefined') {Verif=1;}
		if(Verif==1){
			if ($('#boxConfirm').dialog('isOpen') === true) {
					$("#boxConfirm").dialog('close');
			} 
			 jConfirm('<?php echo $trad['msg']['terminerOperation']; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {//
									if(r)	{
										
										if(act == 'mod'){	
																$('#formMod').ajaxSubmit({
																		target			:	'#resMod',
																		url				:	'qualite_service.php?goMod',
																		method			:	'post'
																	}); 
																
															}else{
															
																$('#formAdd').ajaxSubmit({
																		target			:	'#resAdd',
																		url				:	'qualite_service.php?goAdd',
																		method			:	'post'
																	}); 
																	
																
															}
						
									}
								});
								
			}else if(Verif==0)
			{
				//jAlert("<?php echo $trad['msg']['PeriodeDejaDefini'];?>","<?php echo $trad['titre']['Alert'];?>");
				$("#boxConfirm").dialog('open');
			}else {
			
				jAlert("<?php echo $trad['msg']['Erreur'];?>","<?php echo $trad['titre']['Alert'];?>");
				
			}
			
			
	});	
					}
					
			else {
				jAlert("<?php echo $trad['msg']['AjoutQuestion'];?>","<?php echo $trad['msg']['Attention'];?>");
			}
		}
	}	
	function goAddQuestion(){
	var form="";
	var act = $('#act').attr('value');
	
	if(act == 'mod'){ form="#formModQ";} else {form="#formAddQ"; }

	    $("#formAddQ").validate({
                                 rules: { 
                                                QuestionAr: "required",
												QuestionEn:"required",
												QuestionFr:"required"
                                        } 
		});
	var test=$("#formAddQ").valid();
	
		if(test==true){		
			/* jConfirm('<?php echo $trad['msg']['terminerOperation']; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {//
					if(r)	{*/
					
											
												$('#formAddQ').ajaxSubmit({
														target			:	'#resAddQ',
														url				:	'qualite_service.php?goAddQuestion',
														method			:	'post'
													}); 
													
												
									
		
					/*}
				})*/
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
   	function changerLigne(){
		//'details_tarifs.php?list&ste='+ste+'&type='+type

			$('#listQues').load('qualite_service.php?listQues');
			
		}
	function supLigne(ligne){

			var adr 	= 'qualite_service.php?supLigne='+ligne;
			  jConfirm('<?php echo $trad['msg']['ConfirmerSup'] ;?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
				if(r)	{
					$('#listQues').load(adr);
				}
			  });
	}
</script>
<br><br><br>
<?php
include("footer.php");
?>
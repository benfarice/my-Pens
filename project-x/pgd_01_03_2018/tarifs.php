<?php 
include("php.fonctions.php");
require_once('connexion.php');
//sqlsrv_query("SET NAMES UTF8");
SQLSRV_PHPTYPE_STRING('UTF-8') ; 
session_start();
$tableInser = "promo";
$sansDoublons = "NomCaisse";
$cleTable = "IdPromo";
$nom_sansDoublons = "Nom de la caisse";


if (isset($_GET['validation'])){
if (isset($_GET['nompublicite']))
$nompromo=$_GET['nompromo'];

	 sqlsrv_query("SET AUTOCOMMIT=0");
	 sqlsrv_query("START TRANSACTION");
	 sqlsrv_query("BEGIN");
$update_oper="reussi";
$resSql = "update promo set validation=1 WHERE NomPromo='".$nompromo."'";
$res= sqlsrv_query($resSql)or die(sqlsrv_errors().$resSql);
		      if(!$res){
							//
						$update_oper="echec";
						echo sqlsrv_errors(); 
						sqlsrv_query("ROLLBACK");
						
						?>
						<script language="javascript">
						//alertK("erreur lors de l'ajout. Contacter l'administrateur",0);
						alert("Erreur lors de la validation. Veuillez répéter l'opération .__<?php echo  sqlsrv_errors(); ?>");
						
						</script>
				<?php 
				exit;
				}else
				{
				 
				 sqlsrv_query("COMMIT");
				}
				
if($update_oper=="reussi")
{	

//-----------------------Write or Re-Write into file XML------------------------------------------
	if(file_exists("Config/ConfigPromo.xml") ) // If File Exist--------------------------------------
    {
		   $xml=simplexml_load_file('Config/ConfigPromo.xml');
		   $rq = "select distinct p.IdPos,NomCaisse
					from promo pub inner join pos p 
					on pub.IdPos=p.IdPos
					Where NomPromo='".$nompromo."'  and pub.etat=1";
					
		   $rs=sqlsrv_query($rq)or die(sqlsrv_errors().$rq);  
		   while($row = mysql_fetch_array($rs)){
		   	   $nodes = $xml->xpath(sprintf('/PROMO/POS[@NomC="%s"]', strtolower($row[1])));
			   if (!empty($nodes)) {
					$nodes[0]->attributes()->Valeur  = "true";
					//$nodes[0]->attributes()->Timer  = "";
				}
				
		   }
		$xml->asXml('Config/ConfigPromo.xml');
	 }//if(file_exists)
}						
if($update_oper=="reussi")
{	?>
							
<script type="text/javascript"> 
alert('La validation a été effectué.');
rechercher();
</script>
						
<?php }
exit();
} 

if (isset($_GET['AffVideo']))
{
	//echo $_GET['url'];
			if(fileExists("MediaPromo/".$_GET['url'])){?>
		<div style=" display:inline-block">
	 <video id="video" class="Video" preload="none" width="660"  autoplay loop controls  >
					<source src="MediaPromo/<?php	echo $_GET['url']; ?>" type="video/mp4">
					Your browser does not support the video tag.				  </video>
			<?php } else { echo "<div style='text-align:center; margin:auto ;font-size:18px;width=100px; 
			padding:100px;color:red'>video introuvable !!!</div>";}?>
				
<?php 
		exit ;
		}

if (isset($_GET['pageVideosUpdate']))
{ 
}



if (isset($_GET['pageImageUpdate']))
{
} 

if (isset($_GET['suppression'])){
} 

if (isset($_GET['modification'])){


	exit();
}
if(isset($_GET['goUpdate'])){
    

exit();
}





if(isset($_GET['goMod'])){


exit;
	
}


if(isset($_GET['goAdd'])){
/*if(isset($_POST['selectItemNomCaisse'])){
  $invite = $_POST['selectItemNomCaisse'];
  print_r($invite);
}
return;*/
if(isset($_POST['selectItemNomCaisse']) && !empty($_POST['selectItemNomCaisse'])){

$NomsCaisse = $_POST['selectItemNomCaisse'];

	 sqlsrv_query("SET AUTOCOMMIT=0");
	 sqlsrv_query("START TRANSACTION");
	 sqlsrv_query("BEGIN");
	 $insert_oper="reussi";
	 
$capture_field_val ="";
if(isset($_POST["mytext"])){    
    for($i=0;$i<count($_POST["mytext"]);$i++){
       $capture_field_val .= $_POST["mytext"][$i] . ",". $_POST["mytext2"][$i] ."|";
    }
}
$capture_field_val2 ="";
if(isset($_POST["mytext"])){    
    for($i=0;$i<count($_POST["mytext"]);$i++){
       $capture_field_val2 .= $_POST["mytext"][$i] . "=>". $_POST["mytext2"][$i] ."|";
    }
}
//echo $capture_field_valX  ; 
/*echo "-- count :  " .  count($_POST["mytext"]);
echo "--  capture_field_val" . $capture_field_val;
return;*/
 $ListPos = array();
/**********************************/				
foreach($NomsCaisse as $selectValue)
{
	 	    // Save name of Pos
			 $rq = "SELECT NomCaisse FROM pos WHERE IdPos='".$selectValue."' and etat=1";
			 $rs =sqlsrv_query($rq)or die(sqlsrv_errors().$rq);
			 $r = mysql_fetch_row($rs);
			 $ListPos[]=$r[0];
			 $resModif = "update pos set Duree='".$capture_field_val2."' , DateModif='".date("Y-m-d H:i:s")."' WHERE IdPos=".$selectValue;
			 $resModif = sqlsrv_query($resModif)or die(sqlsrv_errors().$resModif);
			
			 if(!$resModif){
							//
						$insert_oper="echec";
						echo sqlsrv_errors(); 
						sqlsrv_query("ROLLBACK");
						
						?>
						<script language="javascript">
						//alertK("erreur lors de l'ajout. Contacter l'administrateur",0);
						alert("erreur lors de l'insertion. Veuillez insérer à nouveau.__<?php echo  sqlsrv_errors(); ?>");
						$('#box').dialog('close');
						</script>
				<?php 
				//break 2;
				}else
				{
				 
				 sqlsrv_query("COMMIT");
				}
}
			

if($insert_oper=="reussi")
{	

//-----------------------Write or Re-Write into file XML------------------------------------------
if(file_exists("Config/ConfigPromo.xml") ) // If File Exist------------------------------------------
{

 $xml=simplexml_load_file('Config/ConfigPromo.xml');

  foreach ($ListPos as $fields) {
		 $nodes = $xml->xpath(sprintf('/PROMO/POS[@NomC="%s"]', strtolower($fields)));
		 if (!empty($nodes)) {
			//printf('At least one building named "%s" found', $fields);	
			//echo ( "Valeur : " . $nodes[0]->attributes()->Valeur  . " ---- ");
			 if (!isset($nodes[0]['HeuresPointes']) )
			 {
			  $nodes[0]->addAttribute('HeuresPointes', $capture_field_val);   
			 } 
			 else
			  $nodes[0]->attributes()->HeuresPointes  = $capture_field_val;
			 
			
		} else {
			//printf('No building named "%s" found', $fields);
			  $pos=$xml->addChild('POS');
			  $pos->addAttribute('NomC', strtolower($fields));
			  $pos->addAttribute('Valeur',"false");
 			  $pos->addAttribute('Timer', "");
			  $pos->addAttribute('HeuresPointes', $capture_field_val);   
			    
	  
	
		}
  }
  $xml->asXml('Config/ConfigPromo.xml');
}
else
{
 $doc = new DOMDocument(); 
 $r = $doc->createElement("PROMO"); 
 $doc->appendChild( $r ); 
  foreach ($ListPos as $fields) {
	  $pos = $doc->createElement( "POS" ); 
	 
	  $nomC = $doc->createAttribute("NomC");
	  $nomC->value = strtolower($fields);
	  
	  $valeur = $doc->createAttribute("Valeur");
	  $valeur->value = "false";
	  
	  $delai = $doc->createAttribute("Timer");
	  $delai->value ="";

	  $HeuresPointes = $doc->createAttribute("HeuresPointes");
	  $HeuresPointes->value = $capture_field_val;
	  
	    
	  $pos->appendChild($nomC);
	  $pos->appendChild($valeur);
	  $pos->appendChild($delai);
	  $pos->appendChild($HeuresPointes);
	  $r->appendChild($pos);  
 }
 $doc->save("Config/ConfigPromo.xml");
}
?>
							
<script type="text/javascript"> 
alert('L\'ajout a été effectué.');
$('#box').dialog('close');
rechercher();
</script>
						
<?php }

}

	
exit;
	
}

if (isset($_GET['mod'])){
			
	$ID= $_GET['ID'] ;
	$sql = "select * from pos where IdPos = '$ID' ";
	//execSQL($sql);
	//echo $sql; return;
	$res=sqlsrv_query($sql)or die(sqlsrv_errors().$sql);
	$row = mysql_fetch_assoc($res);

?>

	<div  id="resMod" style="padding:5px">&nbsp;</div>
	<form id="formMod" action="NULL" method="post" name="formAdd1"> 
		
		<table width="100%" border="0" border="0" align="center" cellpadding="5">
        <tr>
        	<td><div class="etiqForm" id="DATE_PIECE" > <strong>Nom caisse :</strong> </div>
            </td>
            <td>
            <input type="hidden" value="<?php echo $ID ;?>" name="IdPos" />
            <input class="FormAdd1" type="text" name="NomCaisse" control="1" id="NomCaisse" value="<?php echo $row["NomCaisse"];?>" size="25" tabindex="1"  />
          </td>
            <td><div class="etiqForm" id="DATE_PIECE" > <strong>Nom magasin</strong> : </div>
            </td>
            <td>
            <input class="FormAdd1" type="text" name="NomMagasin" id="NomMagasin" size="25"
             value="<?php echo utf8_decode($row["NomMagasin"]);?>" control="1" tabindex="2"  />
            </td>
          </tr>
			  <tr>
				<td  valign="top">
					<div class="etiqForm" id="DATE_PIECE" > <strong>Ville </strong> : </div>
					</td>
				<td>
               
                  <select name="Ville" id="Ville" class="FormAdd1" tabindex="3">
						<option value="Casablanca" <?php if ($row['Ville'] == 'ABD') echo "checked =\"checked\"" ; ?>>Casablanca</option>
						<option value="Agadir" <?php if ($row['Ville'] == 'Agadir') echo "checked =\"checked\"" ; ?>>Agadir</option>
						<option value="Rabat" <?php if ($row['Ville'] == 'Rabat') echo "checked =\"checked\"" ; ?>>Rabat</option>
						<option value="Fes" <?php if ($row['Ville'] == 'Fes') echo "checked =\"checked\"" ; ?>>Fes</option>
						</select>
				</td>
                
                <td valign="top">
					<div class="etiqForm" id="DATE_PIECE" > <strong>Téléphone </strong> : </div>				</td>
				<td >
               
                  <input class="FormAdd1" g="date"  id="Tel" control="1" tabindex="4" name="Tel"  type="text" size="12" maxlength="15" value="<?php 
				  echo ($row["Tel"]);?>"/>
				
				</td>
          </tr>
				<tr>
			 <td valign="top"><div class="etiqForm" id="DATE_PIECE"  > <strong>Adresse Magasin</strong> :</div></td>
                <td  height="20" > 
                  <textarea  rows="4" cols="40"  style='text-align:left;resize:None;' control="1"  tabindex="5"
                    class="FormAdd1" name="Adresse"  ><?php 
					echo trim(stripslashes(htmlentities($row["Adresse"], ENT_QUOTES, "UTF-8")));  ?>
                    </textarea>
                </td>
		
                <td valign="top"><div class="etiqForm" id="DATE_PIECE" > <strong>Adresse Ip</strong> :</div></td>
                <td valign="top">
      	<input type="text" class="formTop" id="AdresseIp"value="<?php 
				  echo ($row["AdresseIp"]);?>"  size="25" tabindex="6" name="AdresseIp" />
			      
              
					
				 </div>		 
                </td>
			  </tr>	
				<tr><td colspan="4"  height="20" align="center" > <strong>NB</strong> : Les champs en gras sont obligatoires.</td></tr>		  
			 
			<tr><td colspan="4"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
	
 	  </table>
	</form>

<script language="javascript" type="text/javascript">

function AjoutDelegation(){
	    var DsgSite=$('#DsgSite').attr('value');
	
		var id=$('#IdSite').attr('value');
		var existe=false;
		var i = 1 ;
	
		$('#listDelegation .elt').each(function(){
		
			if($(this).attr('id') == id)	existe=true;
			i++;
		});
		
		if(i >= 1)	$('#listDelegation #vide').hide();
		else $('#listDelegation #vide').show();

		if (!existe){	
			if 	($('#IdSite').attr('value')!=""){	
				var elt="<div  class='elt' onclick='supp("+id+")' id='"+id+"' title='Cilquer pour supprimer'> "+DsgSite+"</div>";
				$('#listDelegation').append(elt);
				$('#DelegationMar').attr('value',id+','+$('#DelegationMar').attr('value'))
				$('#listDelegation').slideDown();
				$('#down').attr('action','up');
				$('#IdSite').attr('value','');
			}
		}
		else alert('Existe  déjà');
			$('#DsgSite').val("");
	
}


function supp(id){
	$(document).ready(function(){
		var idNoFac="";
		var i = 0 ;
		$('#listDelegation .elt[id='+id+']').remove();
		
		$('#listDelegation .elt').each(function(){
			idNoFac	=	idNoFac+','+$(this).attr('id');
			i++;		
		});
		
		if(i != 0)	$('#listDelegation #vide').hide();
		else $('#listDelegation #vide').show();
		
		$('#DelegationMar').attr('value',idNoFac);

	});
}
$('#down').click(function(){
	if($('#listDelegation').css('display') == 'none'){

			$('#listDelegation').slideDown();
			$('#down').attr('action','up');
	}else{
			$('#listDelegation').slideUp();
			$('#down').attr('action','down');
	}
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
<form id="formAdd" action="NULL" method="post" name="formAdd1"> 
		
		<table width="100%" id="tabAdd" border="0" align="center" cellpadding="5">
        <tr >

			<td  valign="top" style="width:130px"><div class="etiqForm"  style="text-align:left; " 
			id="DATE_PIECE" > <strong>Article</strong> : </div>
            </td>
			</tr>
			<tr>
            <td valign="top">
		<div id="grpCaisse" style=" width:300px; float:left;"><select style="width:300px; vertical-align:top"
		multiple="multiple" name="ListeArt" id="ListeArt"  tabindex="3" >
		

			  <?php $sql = "select IdArticle, Designation from articles ";
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
					<SPAN class="etiqForm" id="DATE_PIECE" ><strong>Quantité Min:</strong></SPAN>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <SPAN class="etiqForm" id="DATE_PIECE" ><strong>Tarif:</strong></SPAN></td>
				  </tr>
				  <tr>
					<td valign="top" >
			<div class="input_fields_wrap">
			
			<div style="width:auto; float:left; margin-bottom:10px;">
			
			<input id="x1" action="mytext"  class="inputDuree" size="12" type="text"  name="mytext[]" onkeypress="return isEntier(event,this)" /> 
		&nbsp;&nbsp;&nbsp;&nbsp;
		
			 <input id="y1" action="mytext2" size="12"  class=""   onkeypress="return isDecimal(event,this)"  type="text"  name="tarif" /> 
			<div style=" float:RIGHT; width:24px ;margin:0 5px">
			<input type="button" class="add_field_button"/>	
			</div>
			</td>
			</div>
			<div class="clear"> </div>
		
				  </td>

			
		</tr>
<tr>
<td colspan="2">
<div name="msgPos" id="msgPos" ></div>
</td>
</tr>
<tr><td colspan="2"  height="20" > <div class="msgErreur">&nbsp;</div></td></tr>	  
</table>
</form>


<script language="javascript" type="text/javascript">

 $('#ListeArt').multipleSelect({

	   filter: true,placeholder:'S&eacute;lectionnez l\'article ',single:true,maxHeight: 100
	});

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


$(document).ready(function(){

 $('input[type=radio][name=TypeTarif]').change(function() {
        if (this.value == 'Qte') {
           
				$("#DivCltQte").hide();
				$("#DivClt").hide();
				$("#DivQte").show("slow");
        }
        else if (this.value == 'Clt') {
				$("#DivCltQte").hide();
				$("#DivQte").hide();
				$("#DivClt").show("slow");
        }
		  else if (this.value == 'CltQte') {
				$("#DivClt").hide();
				$("#DivQte").hide();
				$("#DivCltQte").show("slow");
        }
    });
	
	
		/*****************/
  var max_fields      = 4; //maximum input boxes allowed
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
          $(wrapper).append('<div id="ligne" style="width:320px; margin-bottom:10px;" > <input id="x'+xx+'" size="12" action="mytext"  class="inputDuree" type="text"  name="mytext[]" onkeypress="return isEntier(event,this)" /> &nbsp;&nbsp;&nbsp;&nbsp; <input id="y'+xx+'"  size="12" onkeypress="return isDecimal(event,this)"  action="mytext2" class="inputDuree" type="text"  name="mytext2[]" /><div style="width:20px; float:right "><input type="button"  class="remove_field" ></input></div> </div> ');
        }


    });
	
	  $(wrapper).on("click",".remove_field", function(e){
	 //user click on remove text
        e.preventDefault(); $(this).parent('div').parent('div').remove(); xx--;
    })
});
	




</script>


<?php
	exit();
}

if (isset($_GET['rech']) or isset($_GET['aff'])){
exit;}
?>
<?php include("header.php"); ?>

<div class="pageBack" >



<div id="box"> </div>
<div class="contenuBack">
	<div id="infosGPS" style="border-bottom:1px dashed #778; ">&nbsp;Paramétrage &nbsp;<img src="images/tri.png" />
		&nbsp;Tarifs</div>

	

	<form id="formRechF" method="post" name="formRechF"> 
		<div id="formRech" style="">	<!--Recherche CH -->
		<table width="101%" border="0" align="center" >
			  <tr>
				<td width="23%" valign="middle">
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
								
								}
							}

			
				//	parcourir($groups);
					foreach($groups as $u=>$v){
							
								$Options.= '<optgroup label='.$v['Designation'].'>';
								
								
									foreach($v as $r){
									if(is_array($r)){
										$Options.= "<option value='1' >".$r['Prenom']."</option>";
									}
								}
						$Options.= "</optgroup>";
					 }
			 }
			

                                           ?>
			 <select multiple="multiple" id="ListeClt" Class="Select ListeClt" style="width:350px">
			<!-- 	<optgroup label="Group 1">
					<option value="1">Option 1</option>
				</optgroup>
				<optgroup label="Group 3">
					<option value="9">Option 9</option>
				</optgroup>
			</select>!>
          <!--  <select  name="ListTech[]" id="ListTech"   style="width:250px"
		   multiple="multiple" tabindex="3" class="Select ListTech">-->
					<?php echo   $Options;?>
					   </select>
						</div>
				</td>
		      <td width="22%" rowspan="2" >	<span class="actionForm">      
          <input name="button" type="button" id="Rechercher"  onClick="rechercher();" value="Rechercher" class="bouton32" action="rech" title="Rechercher "//>
			      <input name="button2" type="reset" onClick="" value="Effacer" class="bouton32" action="effacer" title="Effacer"/></span><br/></td>
			  <td width="25%" rowspan="2"   style="border-left:1px solid #778;"><span class="actionForm">
			    <input name="button3" type="button" title="Ajouter tarif" class="bouton32" onClick="ajouter();" value="Ajouter tarif" action="ajout" style="width:150px;" />
			  </span></td>
			
	 	 </table>
      </div>
      <div id="formFiltre" style=" ">
		<table border=0 style=" width:400px ; margin:auto">
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
	 <div id="video" style=" display:table-cell; vertical-align:middle"  ></div>
	<?php include("footer.php"); ?>
</div>

<script language="javascript" type="text/javascript">
function suppression(idPos){

			jConfirm('Confirmer la suppression ?', null, function(r) {
						if(r)	{
							$('#act').attr('value','supp'); 
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'tarifs.php?suppression&idPos='+idPos,clearForm:false});		
						}
					})
	//	$('#box').load(url).dialog('open');
	
}
function modification(nomPromo){

		$('#act').attr('value','modif'); 
		var url='tarifs.php?modification&nompromo='+nomPromo ;
	
		$('#box').load(url).dialog('open');
	
}
	function filtrer(){
	
		$('#formRechF').ajaxSubmit({target:'#formRes',url:'tarifs.php?rech',clearForm:false});
		patienter('formRes');
		return false;	
	}
function rechercher(){

		$('#formRechF').ajaxSubmit({target:'#formRes',url:'tarifs.php?rech'})
			//clearForm('formRechF',0);
	}

function ajouter(){

		$('#act').attr('value','add');
		var url='tarifs.php?add';
	
		$('#box').html('load').load(url).dialog('open');
	
}
function next(page){

		$('#act').attr('value','pageImagenext');
		var url='tarifs.php?pageImagenext='+page;
	
		$('#box').html('load').load(url).dialog('open');
	
}
  $('body').on('keypress', '#NomCaisse', function(args) {
   if (args.keyCode == 13) {
       $("#Rechercher").click();
       return false;
   }
});
 $('#ListeClt').multipleSelect({

	   filter: true,placeholder:'S&eacute;lectionnez les clients ',single:false,maxHeight: 100
	});
$(document).ready(function(){
				

		
	
	
		//$('#formRes').load('tarifs.php?aff');
				$('#box').dialog({
					autoOpen		:	false,
					width			:	400,
					height			:	440,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Gestion des tarifs',
					buttons			:	{
						"Fermer"		: function(){
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
		var url='tarifs.php?mod&ID='+id;
		$('#box').html('').load(url).dialog('open');
	
}
	
function find_duplicate_value(X) {

    if (X.length == 0) 
        return null;
    
    var tmp = [];
    
    for (var i = 0; i < X.length; i++) {
        var val = X[i].value;
        var pos = tmp.indexOf(val)

    //If value duplicate in array X ,verfiy value in array Y 
     

        tmp.push(val);
    }
    return null;
}
function terminer(){

	var form="";
	var act = $('#act').attr('value');
	if(act == 'modif'){ form="#formMod";} else {form="#formAdd"; }

	    $(form).validate({
                                              
                                   rules: {
                                               
                                                'ListeArt': "required"
                                           },
									/*messages:{
									  'NomCaisse[]': " Champs Nom Caisse est obligatoire"
									}	      
										*/   
										   
										    });
//alert(document.getElementById("media").value); 
var test=$(form).valid();
var t1=verifSelect2('ListeArt');
alert(t1);

/****************************************Controle Periode****************************************/
//************************************************************************
//************************************************************************
	
/*******************************************************************/

		if((test==true) && (t1==true)){
		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
		
											if(act == 'modif'){	
										
													$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'tarifs.php?goUpdate',
														method			:	'post'
													}); 
													return false;
												
											}else{
												
												
													$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'tarifs.php?goAdd',
														method			:	'post'
													}); 
													return false;
												
											}
		
					}
				})
		}
		//}//else------------------------------------------------------------------------
	}
	function AfficheVideo(url){
		var url="tarifs.php?AffVideo&url="+url;
		$('#video').html('').load(url).dialog('open');
	
}

function pressDuree(element)
{
var t=$(element).attr("value");
var a = parseInt(t);

   // var array=$("").attr("value")
	
	//alert("ezsdzez"+a);
	if( a > 23 ){
		alert("Heure incorrecte ");
		$(element).attr("value","");return;
	}
	
	
	/* for (var i=0; i < arrayOfStrings.length-1; i++)
	 {
		if(arrayOfStrings[i] == x)
		arrayOfStrings.splice(i,1);
		
	 }*/

}	
</script>

<?php
include("../php.fonctions.php");
require_once('../connexion.php');
session_start();
$tableInser="fraisVendeur";
if(isset($_GET['goAdd'])){
	$errors="";

//parcourir($_POST);return;
		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	  
		$reqInser1 = "INSERT INTO ".$tableInser." ([DateFrais] ,[Gazoil] ,[Autoroute]  ,[Divers],[DescripDivers],IdVendeur,IdDepot
							) values 	(?,?,?,?,?,?,?)";

		$params1= array(
		securite_bdd($_POST['Date']),
		securite_bdd($_POST['Gazoil']),
		securite_bdd($_POST['Autoroute']),
		securite_bdd($_POST['Divers']),
		addslashes(mb_strtolower(securite_bdd($_POST['DiversDescription']), 'UTF-8')),
		$_SESSION['IdVendeur'],
		$_SESSION['IdDepot']

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
					jAlert('L\'ajout a été effectué.',"Message");
					
					document.location.href="index.php";
				
				</script>
		<?php
		} else {
			 sqlsrv_rollback( $conn );
			 echo "<font style='color:red'>".$error."</font>";
		}
		/********************************************************/	

exit;
}


if (isset($_GET['add'])){ 

?>
<Style>
.ui-widget {
	font-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif;
	font-size: 3.1em;
}
.ui-widget-header {
    color: #333;
}
</style>
<div>
<form id="formAdd" method="post" name="formAdd"> 
<div id="resAdd" style=""></div>	
<br><br>
  <div class="Clt">
   
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="15">
        <tr>
        	<td align="right">
			 <div class="etiqForm" id="" ><strong>Date de frais  </strong> : </div>
            </td>
            <td>
               <input class="FormAdd1" g="date" id="Date"  name="Date" type="text" 
				  size="12" maxlength="10" onChange="verifier_date(this);" />
            </td>
		
			
				<td   align="right">
			 <div class="etiqForm" id="" > <strong>Divers</strong> : </div>
            </td>
            <td>
             <input class="FormAdd1" id="Divers"    tabindex="4" onkeypress="return isEntier(event) " 
				  name="Divers"  type="text" size="6"   />
            </td>
			
          </tr>

		 <tr>
		 <td align="right"><div class="etiqForm" id="" > <strong>Autoroute </strong> : </div>
            </td>
            <td>
      
                  <input class="FormAdd1" id="Autoroute"    tabindex="4" onkeypress="return isEntier(event) " 
				  name="Autoroute"  type="text" size="6"    />
		
            </td>
		
			<td rowspan="2" align="right" valign="top"><div class="etiqForm" id="" > <strong>Détails divers </strong> : </div>
            </td>
            <td rowspan="2" valign="top">
        	<textarea  rows="5" cols="40"   class="FormAdd1" name="DiversDescription" id="DiversDescription" style="resize:None;" ></textarea>
            </td>			
          </tr>
		  
		  		<tr>
        	
            
		   <td align="right">
			 <div class="etiqForm" id="" >  <strong>Gazoil</strong> : </div>
            </td>
           <td>
             <input class="FormAdd1" id="Gazoil"    tabindex="4" onkeypress="return isEntier(event) " 
				  name="Gazoil"  type="text" size="6"   />
            </td>
			

        </tr>	  
		
		
				<tr>
        	
            


        </tr>	
		
 	  </table>
  </div>
<BR><BR>
  <div class=" boiteBtn">
<div class=" boiteBtn" style="float:right">
<input type="button" value="Ajouter" class="btn"  onclick="AjoutClt()"/>&nbsp;&nbsp;
<input type="reset" value="Annuler"  class="btn" /></div></div>


</form>
</div>


<script language="javascript" type="text/javascript">
	calendrier("Date");

$('#Ville').multipleSelect({
		  filter: true,placeholder:'S&eacute;lectionnez la Ville ',single:true,maxHeight: 300,
		      onClick: function(view) {
				if(view.checked = 'checked')
				$('#Secteurs').load("frais.php?chargerSecteur&IdVille="+view.value);
				
				var Ville =$('#Ville').val();
				if(Ville!="") {
					$('div.Ville').removeClass('erroer');
					$('div.Ville button').css("border","1px solid #ccc").css("background","#fff");
				}
}});
$('#Type').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Type ',single:true,maxHeight: 300});
$('#Secteur').multipleSelect({filter: true,placeholder:'S&eacute;lectionnez le Secteur ',single:true,maxHeight: 300});



</script>

<?php
exit;
}
include("header.php"); ?>

<Style>
.ui-widget-content{
background:#fff;}
.Clt{
	border: 1px solid #CCC;
-webkit-border-radius: 5px;
-khtml-border-radius: 5px;
border-radius: 5px;
margin: 10px 20px; 
}
</style>

<div style=" display:flex;align-items:center; padding:2px 0;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
						<div >&nbsp;> <span  Class="TitleHead" onclick="">Gestion des frais</span></div> 
</div>
<div style="clear:both;"></div>

<div id="formRes" ></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<div id="box" ></div>

<?php
include("footer.php");
?>

<script language="javascript" type="text/javascript">

$(document).ready(function() {
	$('#box').dialog({
					autoOpen		:	false,
					width			:	1260,
					height			:	706,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						},
						"Terminer "	: function() {
							//terminer();
						
						}
					 }
			});
			
		 $('#formRes').load('frais.php?add');

});
function rechercher(){
		
		
		clearForm('formRechF',0);
	}

function AjoutClt(){var form="";
	var act = $('#act').attr('value');
		 act = 'add';
	if(act == 'mod'){ form="#formMod";} else {form="#formAdd"; }
	    $(form).validate({
                                 rules: { 
                                                Date: "required",
												Autoroute:"required",
												Gazoil:"required"
                                          }    
		});
	var test=$(form).valid();
	
		if(test==true){		
			 jConfirm('Voulez-vous vraiment terminer la saisie?', null, function(r) {
					if(r)	{
						if(act == 'mod'){	
												$('#formMod').ajaxSubmit({
														target			:	'#resMod',
														url				:	'frais.php?goMod',
														method			:	'post'
													}); 
												
											}else{
											
												$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'frais.php?goAdd',
														method			:	'post'
													}); 
													
												
											}
		
					}
				})
		}else {
			
		}
}
function Fermer(){

	
}

</script>
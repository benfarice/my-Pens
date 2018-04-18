<?php
session_start();
if(!isset($_SESSION['username'])){
header('Location: index.php');
exit();
}
	include_once "init.php";


	
if(isset($_GET['goMod'])){

	//parcourir($_POST);return;
	//on verif si codeF existe deja

		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $con ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
	
			$reqModif = "UPDATE camions SET Marque='".addslashes(mb_strtolower($_POST['Marque'], 'UTF-8'))."',";
			$reqModif .=  " Matricule='".addslashes(mb_strtolower($_POST['Matricule'], 'UTF-8'))."',";
			$reqModif .=  " Tare='".addslashes(mb_strtolower($_POST['Tare'], 'UTF-8'))."',";
			$reqModif .=  " Designation='".addslashes(mb_strtolower($_POST['Designation'], 'UTF-8'))."'";
			$reqModif .= " where ID='".addslashes(mb_strtolower($_POST['IdTable'], 'UTF-8'))."'";
		
			$stmt1 = sqlsrv_query( $con, $reqModif );
			
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $con );
		?>
				
		<?php
		} else {
			 sqlsrv_rollback( $con );
			 echo "<font style='color:red'>".$error."</font>";
		}
	

exit;
	
}

if (isset($_GET['mod'])){ 
		$ID= $_GET['ID'] ;

	$sql = " 
	SELECT c.marque Marque,c.matricule Matricule, c.designation Designation,c.tare Tare FROM camions c
	where  ID= ".$ID;
	//execSQL($sql);
	//echo $sql; 
$reponse=sqlsrv_query( $con, $sql, array(), array( "Scrollable" => 'static' ) );  
	$row = sqlsrv_fetch_array( $reponse, SQLSRV_FETCH_ASSOC ) ;

?>
	<form id="FormAdd" method="post" name="FormAdd"> 
	<input type="hidden" value='<?php echo $ID;?>' name="IdTable" />
		  <div class="row">
			   <div class="col-md-6 col-sm-12  form-group">
			   			<div class="row">
			   				<div class="col-md-12   "><label><?php  echo lang('Matricule');?> :</label></div>
						
							<div class="col-md-12">
								  <input class="form-control" type="text" name="Matricule"  id="Matricule" value="<?php echo (htmlentities($row['Matricule'])); ?>"  /> 
							</div>
			   			</div>
						
			   </div>
			   
			    <div class="col-md-6 col-sm-12  form-group">
			    	<div class="row">
					<div class="col-md-12   "><label><?php  echo lang('Marque');?> :</label></div>
					
						<div class="col-md-12 col-sm-12">
						  <input class="form-control" type="text" name="Marque"  id="Marque" value="<?php echo utf8_decode(htmlentities($row['Marque'])); ?>" 
							/> 
					</div>
			    	</div>
				</div>
   

  	  </div>
 	  <div class="row ">
			   <div class="col-md-6 col-sm-12 form-group">
			   			<div class="row">
						<div class="col-md-12   "><label><?php  echo lang('Designation');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="text" name="Designation"  id="Designation"  value="<?php echo utf8_decode(htmlentities($row['Designation'])); ?>" /> 
						</div>
					    </div>
			   </div>
			 	   <div class="col-md-6 col-sm-12 form-group">
			   			<div class="row">
						<div class="col-md-12   "><label><?php  echo lang('Tare');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="number" name="Tare" step="0.1" id="Tare"  value="<?php echo utf8_decode(htmlentities($row['Tare'])); ?>" /> 
						</div>
					    </div>
			   </div>  
   

     </div>
	</form>
	<script  language="javascript" type="text/javascript">
$(document).ready(function() {				
	ajaxindicatorstop();
	$("#BoxA").modal('show');
})
	</script>
<?php
	exit;
}

if (isset($_GET['goAdd'])){ 

		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $con ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}

//echo $Code;RETURN;
$reqInser1 = "INSERT INTO camions (Matricule,marque,Designation,Tare) values 	(?,?,?,?)";

		$params1= array(
		$_POST['Matricule'],
		addslashes(mb_strtolower(securite_bdd($_POST['Marque']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['Designation']), 'UTF-8')),
		$_POST['Tare']
		) ;

		$stmt1 = sqlsrv_query( $con, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}
//echo $error."mmmmmmmm";return;
		if( $error=="" ) {
			
			 sqlsrv_commit( $con );
		?>
				
		<?php
		} else {
			 sqlsrv_rollback( $con );
			 echo "<font style='color:red'>".$error."</font>";
		}
		/********************************************************/	

	exit;
}
if (isset($_GET['add'])){ 

?>
	<form id="FormAdd" method="post" name="FormAdd"> 
		
		  <div class="row">
			   <div class="col-md-6 col-sm-12 ">
			   			<div class="row form-group ">
						<div class="col-md-12   "><label class=" control-label  labelRight"><?php  echo lang('Matricule');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="text" name="Matricule"  id="Matricule"  /> 
						</div>
					    </div>
			   </div>
			   <div class="col-md-6 col-sm-12 ">
			      		<div class="row  form-group">
					        <div class="col-md-12   "><label><?php  echo lang('Marque');?> :</label></div>
					
						     <div class="col-md-12 col-sm-12">
						  <input class="form-control" type="text" name="Marque"  id="Marque"  
							/> 
						</div>
					</div>
				</div>
   

     </div>
 	  <div class="row">
			   <div class="col-md-6 col-sm-12 form-group ">
			   			<div class="row">
						<div class="col-md-12    "><label><?php  echo lang('Designation');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="text" name="Designation"  id="Designation"  /> 
						</div>
					    </div>
			   </div>
			   
      <div class="col-md-6 col-sm-12 form-group ">
			   			<div class="row">
						<div class="col-md-12    "><label><?php  echo lang('Tare');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="number" name="Tare" step="0.1"  id="Tare"  /> 
						</div>
					    </div>
			   </div>
			   

  </div>
	</form>
	<script  language="javascript" type="text/javascript">
$(document).ready(function() {				
	ajaxindicatorstop();
	$("#BoxA").modal('show');
})
	</script>
<?php
	exit;
}
if (isset($_GET['rech']) or isset($_GET['aff'])){

	//echo toDateSql($_POST['DateF'));return;
		$where="";
	/*	if(isset($_POST['DateD']) && isset($_POST['DateF']) && ($_POST['DateD']!="") && ($_POST['DateF']!="")  )
		{
			if($_POST['DateD'] == $_POST['DateF'])
			{ 
			 	// $where.= " where cast(date_create AS date) = '".($_POST['DateD'))."' ";
				 $where.= " where convert(date,date_create) = convert(date, '".($_POST['DateD'])."',105)";
			}
			else
			{
				 $where.= " where date_create between  convert(date, '".($_POST['DateD'])."',105 ) and convert(date,  '".($_POST['DateF'])."',105) ";
			}
		}
		else
		{
		//	$where=" where cast(date_create AS date)='".(date('m/d/Y'))."'";
		//$where=" where cast(date_create AS date)='".toDateSql(date('d/m/Y'))."'";
		}
	//	echo "vdr".$_SESSION['IdVendeur')."<br>";
		if($where=="") $where.= " where  idVendeur=".$_SESSION['IdVendeur']."";
		else $where.= " and idVendeur=".$_SESSION['IdVendeur']."";
*/
	$sqlA = "SELECT ID Id,c.marque Marque,c.matricule Matricule, c.designation Designation,c.tare Tare FROM camions c
	".$where." " ;
//echo $sqlA;
    $params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	

//ECHO "<hr>".$sqlA."<br>";
//ECHO "<hr>".$sqlD."<br>";

	$stmt=sqlsrv_query($con,$sqlA,$params,$options);
	if( $stmt === false ) {
					$errors = sqlsrv_errors();
					$error="<br>Erreur :  ".$errors[0]['message'] . " <br/> ";
					ECHO $error;
					return;
			}
				
	$ntRes = sqlsrv_num_rows($stmt);
	
	//echo $sqlA  ;echo " num : ".$ntRes; return;
	//
		if(isset($_POST['cTri'])) $cTri= $_POST['cTri'];
		else $cTri= "ID";
		if(isset($_POST['oTri'])) $oTri= $_POST['oTri'];
		else $oTri= "dESC";
		
		if(isset($_POST['pact'])) $pact = $_POST['pact'];
		else $pact = 1;
		if(isset($_POST['npp'])) $npp = $_POST['npp'];
		else $npp= 20;
		
		$min = $npp*($pact -1);
		$max = $npp;
	
	$sqlC = " ORDER BY $cTri $oTri ";//LIMIT $min,$max ";
	$sql = $sqlA.$sqlC;
//echo $sql;return;
/*execSQL($sql);*/
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff = sqlsrv_query($con,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));
	
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
					<div class="resAff"  style="text-align:center;min-height:200px;font-size:24px;">
						<br><br>
						<?php echo lang('AucunResultat');?>
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
		
		<div class="responsive-table-line" style="margin:0px auto;max-width:1275px;">
			<table class="table table-bordered table-condensed table-body-center" id="table1">
					<thead class="entete">
						<tr >
						<th><?php echo lang('Matricule');?></th>
						<th><?php echo lang('Marque');?></th>
						<th><?php echo lang('Designation');?></th>
						<th><?php echo lang('Tare');?></th>
						<th><?php ?></th>
						</tr>
					</thead><tbody>
				<?php while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
					?>
					<tr>
				
						<td data-title="<?php echo lang('Matricule');?>" align="left"><?php echo ucfirst(stripslashes($row['Matricule']));?></td>
						<td data-title="<?php echo lang('Marque');?>"><?php  echo ucfirst(stripslashes($row['Marque']));?>	</td>
						<td data-title="<?php echo lang('Designation');?>"><?php  echo ucfirst($row['Designation']);?></td>
						<td data-title="<?php echo lang('Tare');?>"><?php  echo ucfirst($row['Tare']);?></td>
						<td data-title="">
						<a onclick="mod(<?php echo $row['Id'];?>)" class="btn btn-primary btn-lg colorWhite"
						data-toggle="modal" >
						  <span class="fas fa-eye-dropper "></span> <?php echo lang('Modifier');?> 
						</a></td>
					</tr>
				<?php } ?>
					 </tbody>
			</table>
</div>

    </form>
    <?php
}
?>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	
    $('#table1').DataTable({

	 paging: true,
	   responsive: true,
	 "bSort": false,
	 /*  "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],*/
	 "searching": true
	 
  });
} );
	
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
							$('#formSelec').ajaxSubmit({target:'#brouillon',url:'ventepararticle.php?delPlusieursArticle',clearForm:false});		
						}
					});
				}			
		}	
	</script>
<?php
exit;

}		

	//print_r($_SESSION);
		//include $lang.'arabic.php';
	//include $func."func1.php";
	include $tpl."header.php";

	//Include Navbar On all pages expect the one with $nonavbar variable

	if(!isset($noNavbar)){
		include $tpl."Navbar.php";
	}
		$pagetitle = 'لوحة التحكم';		
		?>
	
		<div class="container-fluid">
			<br><Center><h2><?php echo lang('gestion_camions');?></h2></center>
		<div  class="row row-centered ">
  <div id="formRech" class="  col-sm-12  col-md-12  col-centered ">	
  
 <form id="formRechF" method="post" name="formRechF" > 
		<div class="row chpinvisible" >
			<div  class="col-md-1 col-sm-12  " >	
			<div class="middleLabel centerLabel"  ><label for="user_lastname"><?php echo lang('Periode');?>&nbsp;
			<?php echo lang('de');?></label> </div>	 
			</div>
			
			<div  class=" middleLabel col-md-3 col-sm-12 "  >	
		
					<input class="form-control" g="date" id="DateD" tabindex="2" name="DateD" type="text" size="10" 
							 maxlength="10" onChange="verifier_date(this);" value="<?php //echo date('d/m/Y'); ?>"/>	
					<input name="DATED" type="hidden" value=""/>
			</div>
			<div  class="  col-md-1 col-sm-12  centerLabel" > <label for="user_lastname" Style="margin-top:10px">
			<?php echo lang('a');?></label>	
			</div>
			<div  class=" middleLabel   col-md-3 col-sm-12 form-group " >		
					<input  g="date" id="DateF" tabindex="2" name="DateF" type="text"  class="form-control" 
					size="10" maxlength="10" onChange="verifier_date(this);" value="<?php //echo  date('d/m/Y'); ?>"/>	
					<input name="DATED" type="hidden" value=""/>	
			</div>
		
			<div  class="  col-md-4 col-sm-12  centerLabel">				
			&nbsp;<input type="button" value="<?php echo lang('rechercher');?>" class="btn btn-primary"  id="rech" action="rech" 
			onclick="rechercher()"; />
			
			&nbsp;<input type="reset" value="<?php echo lang('Annuler');?>" class="btn btn-primary chpinvisible"  id="reset" action="effacer" 
			 />
			<input type="button" value="<?php echo lang('Ajouter');?>" class="btn btn-primary"   action="ajout" 
			 onclick="ajouter()"
			 />
		
		
		</div>
		
		
		</div>
		<div class="row">
			<div  class=" col-md-12 col-sm-12 text-center  centerLabel" Style="text-align:center">				
			&nbsp;<input type="button" value="<?php //echo lang('rechercher');?>" class="btn btn-primary chpinvisible"  id="rech" action="rech" 
			onclick="rechercher()"; />&nbsp;
			
			&nbsp;<input type="reset" value="<?php echo lang('Annuler');?>" class="btn btn-primary chpinvisible"  id="reset" action="effacer" 
			 />&nbsp;&nbsp;
			<a onclick="ajouter()" class="btn btn-primary btn-lg colorWhite"
						data-toggle="modal" >
						  <span class="fas fa-eye-dropper "></span> <?php echo lang('Ajouter');?> 
						</a>
		
		
		</div>
		</div>
	</form>
	</div>
</div>
		</div>
		<div id="formRes" >
		
		</div>
		<input type="hidden" id="act" />
			<div class="row   col-md-6" >
			 <div  style="display: none; margin: 0 auto; width:100%"   data-backdrop="static" class="modal fade "
	 data-keyboard="false" id="BoxA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  
	 aria-hidden="false">
           <div class="modal-dialog modal-lg" >
             <div class="modal-content">
                  <div class="modal-header">                   
                    <h2 class="modal-title" id="myModalLabel"><?php echo lang('gestion_camions');?></h2>
                  </div>
				    <div id="Res"></div>
                  <div class="modal-body">
								<img src="http://conferoapp.com/icons/preloader.gif" class="progress">
                  </div>
				  <div class="clear"></div>
				  <div class="modal-footer" style="border:none"> 
				  <input type="submit" value="<?php echo  lang('Enregistrer');?>" id="Terminer"   class="btn btn-primary" onclick="Terminer()" name="save" />&nbsp;
				<Input type="button" class="btn btn-primary" onclick="CloseBox('BoxA')"  value="<?php echo  lang('Fermer');?> " />
				  
				  </div>
            </div>
          </div>	    
        </div>
		</div>
	<?php
		include $tpl ."footer.php";?>	
<script language="javascript" type="text/javascript">

$(document).ready(function() {
	$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="layout/images/loading.gif" /></center>').load('gestion_camions.php?aff');
});
function rechercher(){
	
	$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="layout/images/loading.gif" /></center>');
	$('#formRechF').ajaxSubmit({target:'#formRes',url:'gestion_camions.php?rech'});
		}
 function ajouter(){
		$('#act').attr('value','add');	
	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='gestion_camions.php?add';
     $.get(url, null, function(data) {
      //$modal.find('.modal-body').html(data);
	   $modal.find('.modal-body').html(data);
    })
}
 function mod(id){
		$('#act').attr('value','mod');	
	
	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='gestion_camions.php?mod&&ID='+id;
	//	alert(url);
     $.get(url, null, function(data) {
      //$modal.find('.modal-body').html(data);
	   $modal.find('.modal-body').html(data);
    })
}
  function Terminer(){
    var found = false;

  
  $('#FormAdd').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		
            fields: {         
				Designation: {
					validators: {
						notEmpty: {
							message: '<?php echo lang('RemplirChp'); ?>'
						}
						
					}
				},
				Marque: {
					validators: {
						notEmpty: {
							message: '<?php echo lang('RemplirChp'); ?>'
						}
						
					}
				},
				Matricule: {
					validators: {
						notEmpty: {
							message: '<?php echo lang('RemplirChp'); ?>'
						}
						
					}
				},
				Tare: {
					validators: {
						notEmpty: {
							message: '<?php echo lang('RemplirChp'); ?>'
						}
					}
				}
			
            }
        })
        .on('success.form.fv', function(e) {
			//alert("succes");
        });
	$('#FormAdd').bootstrapValidator('validate');
 var test = $('#FormAdd').find(".has-error").length;
   //var url = '{{ path("back_client_add", {'id': 'id'}) }}';
   if(test==0){
   	
 jConfirm('<?php  echo lang('terminerOperation'); ?>', '<?php  echo lang('Confirm'); ?>', function(r) {
					if(r)	{
     if($('#act').val()=="add" ){ 
		var url="gestion_camions.php?goAdd";
			 }
    else   if($('#act').val()=="mod" ){ 
		var url="gestion_camions.php?goMod";
			 }
	//alert(url);
	// alert(url);
                $.ajax({
                    method: 'POST',
                    url: url,
					data: $("#FormAdd").serialize(),
                    //success: function (data) {
					success: function(responseData, textStatus, jqXHR) {
					        /*jAlert("L\'opération a été effectuée avec succès. ","Opération");  
							 $("#BoxA").modal('hide');
							   $("#BoxA").find('.modal-body').html(responseData);
							rechercher()	;*/
				
					/*	if(responseData==null){
					        jAlert("L\'opération a été effectuée avec succès. ","Opération");  
							  $("#BoxA").modal('hide');
							 $("#BoxA").find('.modal-body').html("");
						rechercher()	;	
						}	else {
								$("#BoxA").find('#Res').html(responseData);
						}	
*/
					/*	if(responseData!=""){
								$("#BoxA").find('#Res').html(responseData);
						}
						else {*/
								//	$("#BoxA").find('#Res').html(responseData);
						//		 $("#BoxA").find('.modal-body').html(responseData);
							     jAlert("<h3><?php  echo lang('messageAjoutSucces'); ?><h3>","<?php  echo lang('Operation'); ?>");  
							$("#BoxA").modal('hide');
							 $("#BoxA").find('.modal-body').html("");
								rechercher()	;
						//}
						
						
                    },  
                    error: function () {
                        jAlert('l\'opération n\'as pas réussi, merci de réessayer',"Opération");
                    }
			
                })
				}
				})
}
}
</script>

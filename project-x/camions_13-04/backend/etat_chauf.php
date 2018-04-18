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
	
			$reqModif = "UPDATE chauffeurs SET Nom='".addslashes(mb_strtolower($_POST['Nom'], 'UTF-8'))."',";
			$reqModif .=  " Matricule='".addslashes(mb_strtolower($_POST['Matricule'], 'UTF-8'))."',";
			$reqModif .=  " Date_Emb='".addslashes(mb_strtolower($_POST['DateEmb'], 'UTF-8'))."',";
			$reqModif .=  " CIN='".addslashes(mb_strtolower($_POST['CIN'], 'UTF-8'))."'";
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
	SELECT c.nom Nom,c.matricule Matricule, c.CIN CIN,c.DATE_emb DateEmb FROM chauffeurs c
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
					<div class="col-md-12   "><label><?php  echo lang('Nom');?> :</label></div>
					
						<div class="col-md-12 col-sm-12">
						  <input class="form-control" type="text" name="Nom"  id="Nom" value="<?php echo utf8_decode(htmlentities($row['Nom'])); ?>" 
							/> 
					</div>
			    	</div>
				</div>
   

  	  </div>
 	  <div class="row ">
			   <div class="col-md-6 col-sm-12 form-group">
			   			<div class="row">
						<div class="col-md-12   "><label><?php  echo lang('CIN');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="text" name="CIN"  id="CIN"  value="<?php echo utf8_decode(htmlentities($row['CIN'])); ?>" /> 
						</div>
					    </div>
			   </div>
			 	   <div class="col-md-6 col-sm-12 form-group">
			   			<div class="row">
						<div class="col-md-12   "><label><?php  echo lang('DateEmb');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="text" name="DateEmb"  
							  id="DateEmb"  value="<?php  echo  $row['DateEmb']->format('d/m/Y')	;	 ?>" /> 
						</div>
					    </div>
			   </div>  
   

     </div>
	</form>
	<script  language="javascript" type="text/javascript">
$(document).ready(function() {	
	 $('#DateEmb').daterangepicker({
        singleDatePicker: true,
		 locale: {
            format: 'D/MM/YYYY'
        }
    });			
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
$reqInser1 = "INSERT INTO chauffeurs (Matricule,nom,CIN,Date_Emb) values 	(?,?,?,?)";

		$params1= array(
		$_POST['Matricule'],
		addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['CIN']), 'UTF-8')),
		$_POST['DateEmb']
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
					        <div class="col-md-12   "><label><?php  echo lang('Nom');?> :</label></div>
					
						     <div class="col-md-12 col-sm-12">
						  <input class="form-control" type="text" name="Nom"  id="Nom"  
							/> 
						</div>
					</div>
				</div>
   

     </div>
 	  <div class="row">
			   <div class="col-md-6 col-sm-12 form-group ">
			   			<div class="row">
						<div class="col-md-12    "><label><?php  echo lang('CIN');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="text" name="CIN"  id="CIN"  /> 
						</div>
					    </div>
			   </div>
			   
      <div class="col-md-6 col-sm-12 form-group ">
			   			<div class="row">
						<div class="col-md-12    "><label><?php  echo lang('DateEmb');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="text" name="DateEmb" step="0.1"  id="DateEmb"  /> 
						</div>
					    </div>
			   </div>
			   

  </div>
	</form>
	<script  language="javascript" type="text/javascript">
$(document).ready(function() {				
	 $('#DateEmb').daterangepicker({
        singleDatePicker: true,
		 locale: {
            format: 'D/MM/YYYY'
        }
    });
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
	if(isset($_POST['act'])&& ($_POST['act']!="")&& ($_POST['act']!=0)  )
	{
		
		$where=" where etat= ".$_POST['act'];
	}
	$sqlA = "SELECT ID Id,c.Nom Nom,c.matricule Matricule, c.CIN CIN,c.date_emb DateEmb ,Etat Etat FROM chauffeurs c
	".$where." " ;

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
						<th><?php echo lang('Nom');?></th>
						<th><?php echo lang('CIN');?></th>
						<th><?php echo lang('DateEmb');?></th>
				
						</tr>
					</thead><tbody>
				<?php while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
				 $class="";
					if($row['Etat']=="1") $class="libre";
					else if($row['Etat']=="2") $class="affecter"; //affecter
					?>
					<tr class="<?php echo $class;?>">
				
				
						<td data-title="<?php echo lang('Matricule');?>" align="left"><?php echo ucfirst(stripslashes($row['Matricule']));?></td>
						<td data-title="<?php echo lang('Nom');?>"><?php  echo ucfirst(stripslashes($row['Nom']));?>	</td>
						<td data-title="<?php echo lang('CIN');?>"><?php  echo ucfirst($row['CIN']);?></td>
						<td data-title="<?php echo lang('DateEmb');?>">
						<?php  echo  $row['DateEmb']->format('d/m/Y')	;	 ?></td>
						
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
			<br><Center><h2><?php echo lang('EtatChauf');?></h2></center>
		<div  class="row row-centered ">
  <div id="formRech" class="  col-sm-12  col-md-12  col-centered ">	

 <form id="formRechF" method="post" name="formRechF" > 
		
		<input type="HIDDEN" id="act" name="act"  />
		
		<div class="row">
			<div  class=" col-md-3 "></div>
			<div  class=" col-md-2 all cadre ">
				<?php echo lang('Tous');?>
			</div>
			<div  class=" col-md-2 libre cadre">
				<?php echo lang('Libre');?>
			</div>
			<div  class=" col-md-2 affecter cadre">
				<?php echo lang('Affecter');?>
			</div>
		
				
			<div  class=" col-md-3 "></div>
	</div>
	<div class="row">
		<div  class=" col-md-3 "></div>
		<div  class=" col-md-2 col-sm-12 text-center  centerLabel" Style="text-align:center">	



<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" 
y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"  
class="" width="100px" onclick="rechercher(0)" ><g><circle style="fill:#CCCCCC" cx="256" cy="256" r="256" data-original="#FF6262" class="active-path" data-old_color="#cccccc"/><path style="fill:#CCCCCC" d="M220.738,380.195l110.787,120.475c99.09-30.553,172.492-119.643,179.852-226.892L365.623,110.685  L220.738,380.195z" data-original="#FF0F27" class="" data-old_color="#cccccc"/><rect x="213.333" y="292.219" style="fill:#FCD088;" width="85.333" height="93.522" data-original="#FCD088"/><rect x="256.293" y="292.219" style="fill:#DDAB62;" width="42.379" height="93.522" data-original="#DDAB62" class=""/><path style="fill:#172956;" d="M360.867,358.541l-46.599-9.319l-8.235-12.021c-1.541-2.25-4.458-3.098-6.966-2.026l-7.999,7.985  c-19.361,19.328-50.731,19.285-70.039-0.098l-7.839-7.868c-2.489-1.084-5.398-0.271-6.961,1.95l-8.499,12.076l-46.599,9.319  c-23.723,4.748-40.801,25.579-40.801,49.774v58.206C151.681,495.187,201.873,512,256,512s104.319-16.813,145.67-45.48v-58.206  C401.67,384.119,384.591,363.287,360.867,358.541z" data-original="#172956"/><path style="fill:#121149;" d="M401.67,408.314c0-24.195-17.079-45.027-40.803-49.773l-46.599-9.319l-8.235-12.021  c-1.541-2.25-4.458-3.098-6.966-2.026l-7.999,7.985c-9.614,9.599-22.19,14.414-34.78,14.462v154.374  c54.017-0.06,104.103-16.862,145.382-45.477V408.314z" data-original="#121149"/><path style="fill:#6D4711;" d="M314.268,349.222l46.599,9.319c23.724,4.746,40.803,25.578,40.803,49.773v58.206  C360.319,495.187,310.127,512,256,512s-104.319-16.813-145.67-45.48v-58.206c0-24.195,17.079-45.027,40.803-49.773l46.599-9.319  l58.125,153.728L314.268,349.222z" data-original="#6D4711"/><path style="fill:#56340C;" d="M401.67,408.314c0-24.195-17.079-45.027-40.803-49.773l-46.599-9.319l-57.98,152.593v10.181  c54.017-0.06,104.103-16.862,145.382-45.477V408.314z" data-original="#56340C"/><path style="fill:#FFEDB5;" d="M347.531,205.44c0.459-4.244,0.698-8.428,0.698-12.509c0-53.974-41.293-97.728-92.229-97.728  s-92.229,43.753-92.229,97.728c0,4.08,0.24,8.264,0.698,12.509c-8.945,2.326-13.202,16.087-9.506,30.817  c3.708,14.776,14.014,24.921,23.018,22.661c0.821-0.205,1.586-0.538,2.326-0.927c16.667,33.452,44.351,60.594,75.693,60.594  s59.027-27.139,75.693-60.594c0.74,0.39,1.505,0.722,2.326,0.927c9.004,2.26,19.309-7.885,23.018-22.661  C360.732,221.527,356.476,207.763,347.531,205.44z" data-original="#FFEDB5" class=""/><path style="fill:#E8CF89;" d="M347.531,205.44c0.459-4.244,0.698-8.428,0.698-12.509c0-53.872-41.138-97.554-91.941-97.721v223.365  c31.223-0.167,58.79-27.236,75.405-60.585c0.74,0.39,1.505,0.721,2.326,0.927c9.004,2.26,19.309-7.885,23.018-22.661  C360.732,221.527,356.476,207.763,347.531,205.44z" data-original="#E8CF89"/><path style="fill:#494948;" d="M161.127,181.119c0,0,1.724,24.442,6.034,39.253l8.045-0.371c0,0-2.012-19.997,14.941-40.365  S161.127,181.119,161.127,181.119z" data-original="#494948"/><path style="fill:#FEE187;" d="M378.397,140.802c0,31.194-54.936,42.894-122.534,42.894s-122.261-11.7-122.261-42.894  S188.402,59.864,256,59.864S378.397,109.608,378.397,140.802z" data-original="#FEE187" class=""/><path style="fill:#FFC61B;" d="M378.397,140.802c0-31.149-54.644-80.789-122.109-80.93v123.823  C323.691,183.644,378.397,171.929,378.397,140.802z" data-original="#FFC61B" class=""/><path style="fill:#CCCCCC" d="M350.873,181.119c0,0-1.724,24.442-6.034,39.253l-8.045-0.371c0,0,2.012-19.997-14.941-40.365  S350.873,181.119,350.873,181.119z" data-original="#333333" class="" data-old_color="#cccccc"/><path style="fill:#172956;" d="M256,210.161c37.505,0,71.494-9.485,96.308-24.855c1.898-1.176,1.076-4.099-1.157-18.041H160.849  c-2.232,13.941-3.055,16.865-1.157,18.041C184.508,200.676,218.495,210.161,256,210.161z" data-original="#172956"/><path style="fill:#121149;" d="M351.151,167.265h-94.863v42.893c37.392-0.048,71.27-9.519,96.02-24.852  C354.206,184.13,353.383,181.207,351.151,167.265z" data-original="#121149"/><path style="fill:#6D4711;" d="M350.899,185.792c-62.828-8.919-126.561-8.938-189.392-0.057c-1.46,0.207-2.808-0.805-3.013-2.265  c-0.938-6.728-1.874-13.455-2.812-20.183c-0.305-2.189,1.221-4.224,3.41-4.529c64.307-8.975,129.508-8.975,193.815,0  c2.189,0.305,3.715,2.339,3.41,4.529c-0.957,6.863-1.912,13.726-2.869,20.589C353.278,185.11,352.135,185.966,350.899,185.792z" data-original="#6D4711"/><path style="fill:#56340C;" d="M352.908,158.756c-32.058-4.474-64.338-6.715-96.62-6.728v27.062  c31.613,0.014,63.224,2.248,94.61,6.703c1.236,0.176,2.379-0.681,2.551-1.919c0.957-6.863,1.912-13.726,2.869-20.589  C356.624,161.096,355.097,159.061,352.908,158.756z" data-original="#56340C"/><path style="fill:#494948;" d="M273.984,270.6c-12.045-5.847-15.736-3.277-17.984-0.398c-2.248-2.879-5.939-5.449-17.984,0.398  c-12.317,5.98-28.562-0.912-28.562-0.912s8.675,12.622,29.915,11.047c9.099-0.674,14.686-3.463,16.631-6.296  c1.945,2.832,7.532,5.622,16.631,6.296c21.242,1.574,29.915-11.047,29.915-11.047S286.303,276.58,273.984,270.6z" data-original="#494948"/><path style="fill:#CCCCCC" d="M273.984,270.6c-11.552-5.608-15.415-3.472-17.696-0.748v4.925c2.146,2.713,7.608,5.31,16.343,5.958  c21.242,1.576,29.915-11.047,29.915-11.047S286.303,276.58,273.984,270.6z" data-original="#333333" class="" data-old_color="#cccccc"/><g>
	<rect x="220.729" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="276.98" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="234.882" y="119.967" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="249.001" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="263.154" y="119.967" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
</g></g> </svg>


		</div>
		
			<div  class=" col-md-2 col-sm-12 text-center  centerLabel" Style="text-align:center">	

<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" 
y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" 
class=""  width="100px" onclick="rechercher(1)"><g><circle style="fill:#A9E2F3" cx="256" cy="256" r="256" data-original="#FF6262" class="active-path" data-old_color="#A9E2F3"/><path style="fill:#A9E2F3" d="M220.738,380.195l110.787,120.475c99.09-30.553,172.492-119.643,179.852-226.892L365.623,110.685  L220.738,380.195z" data-original="#FF0F27" class="" data-old_color="#A9E2F3"/><rect x="213.333" y="292.219" style="fill:#FCD088;" width="85.333" height="93.522" data-original="#FCD088"/><rect x="256.293" y="292.219" style="fill:#DDAB62;" width="42.379" height="93.522" data-original="#DDAB62" class=""/><path style="fill:#172956;" d="M360.867,358.541l-46.599-9.319l-8.235-12.021c-1.541-2.25-4.458-3.098-6.966-2.026l-7.999,7.985  c-19.361,19.328-50.731,19.285-70.039-0.098l-7.839-7.868c-2.489-1.084-5.398-0.271-6.961,1.95l-8.499,12.076l-46.599,9.319  c-23.723,4.748-40.801,25.579-40.801,49.774v58.206C151.681,495.187,201.873,512,256,512s104.319-16.813,145.67-45.48v-58.206  C401.67,384.119,384.591,363.287,360.867,358.541z" data-original="#172956"/><path style="fill:#121149;" d="M401.67,408.314c0-24.195-17.079-45.027-40.803-49.773l-46.599-9.319l-8.235-12.021  c-1.541-2.25-4.458-3.098-6.966-2.026l-7.999,7.985c-9.614,9.599-22.19,14.414-34.78,14.462v154.374  c54.017-0.06,104.103-16.862,145.382-45.477V408.314z" data-original="#121149"/><path style="fill:#6D4711;" d="M314.268,349.222l46.599,9.319c23.724,4.746,40.803,25.578,40.803,49.773v58.206  C360.319,495.187,310.127,512,256,512s-104.319-16.813-145.67-45.48v-58.206c0-24.195,17.079-45.027,40.803-49.773l46.599-9.319  l58.125,153.728L314.268,349.222z" data-original="#6D4711"/><path style="fill:#56340C;" d="M401.67,408.314c0-24.195-17.079-45.027-40.803-49.773l-46.599-9.319l-57.98,152.593v10.181  c54.017-0.06,104.103-16.862,145.382-45.477V408.314z" data-original="#56340C"/><path style="fill:#FFEDB5;" d="M347.531,205.44c0.459-4.244,0.698-8.428,0.698-12.509c0-53.974-41.293-97.728-92.229-97.728  s-92.229,43.753-92.229,97.728c0,4.08,0.24,8.264,0.698,12.509c-8.945,2.326-13.202,16.087-9.506,30.817  c3.708,14.776,14.014,24.921,23.018,22.661c0.821-0.205,1.586-0.538,2.326-0.927c16.667,33.452,44.351,60.594,75.693,60.594  s59.027-27.139,75.693-60.594c0.74,0.39,1.505,0.722,2.326,0.927c9.004,2.26,19.309-7.885,23.018-22.661  C360.732,221.527,356.476,207.763,347.531,205.44z" data-original="#FFEDB5" class=""/><path style="fill:#E8CF89;" d="M347.531,205.44c0.459-4.244,0.698-8.428,0.698-12.509c0-53.872-41.138-97.554-91.941-97.721v223.365  c31.223-0.167,58.79-27.236,75.405-60.585c0.74,0.39,1.505,0.721,2.326,0.927c9.004,2.26,19.309-7.885,23.018-22.661  C360.732,221.527,356.476,207.763,347.531,205.44z" data-original="#E8CF89"/><path style="fill:#494948;" d="M161.127,181.119c0,0,1.724,24.442,6.034,39.253l8.045-0.371c0,0-2.012-19.997,14.941-40.365  S161.127,181.119,161.127,181.119z" data-original="#494948"/><path style="fill:#FEE187;" d="M378.397,140.802c0,31.194-54.936,42.894-122.534,42.894s-122.261-11.7-122.261-42.894  S188.402,59.864,256,59.864S378.397,109.608,378.397,140.802z" data-original="#FEE187" class=""/><path style="fill:#FFC61B;" d="M378.397,140.802c0-31.149-54.644-80.789-122.109-80.93v123.823  C323.691,183.644,378.397,171.929,378.397,140.802z" data-original="#FFC61B" class=""/><path style="fill:#A9E2F3" d="M350.873,181.119c0,0-1.724,24.442-6.034,39.253l-8.045-0.371c0,0,2.012-19.997-14.941-40.365  S350.873,181.119,350.873,181.119z" data-original="#333333" class="" data-old_color="#A9E2F3"/><path style="fill:#172956;" d="M256,210.161c37.505,0,71.494-9.485,96.308-24.855c1.898-1.176,1.076-4.099-1.157-18.041H160.849  c-2.232,13.941-3.055,16.865-1.157,18.041C184.508,200.676,218.495,210.161,256,210.161z" data-original="#172956"/><path style="fill:#121149;" d="M351.151,167.265h-94.863v42.893c37.392-0.048,71.27-9.519,96.02-24.852  C354.206,184.13,353.383,181.207,351.151,167.265z" data-original="#121149"/><path style="fill:#6D4711;" d="M350.899,185.792c-62.828-8.919-126.561-8.938-189.392-0.057c-1.46,0.207-2.808-0.805-3.013-2.265  c-0.938-6.728-1.874-13.455-2.812-20.183c-0.305-2.189,1.221-4.224,3.41-4.529c64.307-8.975,129.508-8.975,193.815,0  c2.189,0.305,3.715,2.339,3.41,4.529c-0.957,6.863-1.912,13.726-2.869,20.589C353.278,185.11,352.135,185.966,350.899,185.792z" data-original="#6D4711"/><path style="fill:#56340C;" d="M352.908,158.756c-32.058-4.474-64.338-6.715-96.62-6.728v27.062  c31.613,0.014,63.224,2.248,94.61,6.703c1.236,0.176,2.379-0.681,2.551-1.919c0.957-6.863,1.912-13.726,2.869-20.589  C356.624,161.096,355.097,159.061,352.908,158.756z" data-original="#56340C"/><path style="fill:#494948;" d="M273.984,270.6c-12.045-5.847-15.736-3.277-17.984-0.398c-2.248-2.879-5.939-5.449-17.984,0.398  c-12.317,5.98-28.562-0.912-28.562-0.912s8.675,12.622,29.915,11.047c9.099-0.674,14.686-3.463,16.631-6.296  c1.945,2.832,7.532,5.622,16.631,6.296c21.242,1.574,29.915-11.047,29.915-11.047S286.303,276.58,273.984,270.6z" data-original="#494948"/><path style="fill:#A9E2F3" d="M273.984,270.6c-11.552-5.608-15.415-3.472-17.696-0.748v4.925c2.146,2.713,7.608,5.31,16.343,5.958  c21.242,1.576,29.915-11.047,29.915-11.047S286.303,276.58,273.984,270.6z" data-original="#333333" class="" data-old_color="#A9E2F3"/><g>
	<rect x="220.729" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="276.98" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="234.882" y="119.967" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="249.001" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="263.154" y="119.967" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
</g></g> </svg>

		</div>
				<div  class=" col-md-2  "  Style="text-align:center">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" 
y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" 
class=""  width="100px" onclick="rechercher(2)"><g><circle style="fill:#F3F781" cx="256" cy="256" r="256" data-original="#FF6262" 
class="active-path" data-old_color="#F3F781"/><path style="fill:#F3F781" d="M220.738,380.195l110.787,120.475c99.09-30.553,172.492-119.643,179.852-226.892L365.623,110.685  L220.738,380.195z" data-original="#FF0F27" class="" data-old_color="#F3F781"/><rect x="213.333" y="292.219" style="fill:#FCD088;" width="85.333" height="93.522" data-original="#FCD088"/><rect x="256.293" y="292.219" style="fill:#DDAB62;" width="42.379" height="93.522" data-original="#DDAB62" class=""/><path style="fill:#172956;" d="M360.867,358.541l-46.599-9.319l-8.235-12.021c-1.541-2.25-4.458-3.098-6.966-2.026l-7.999,7.985  c-19.361,19.328-50.731,19.285-70.039-0.098l-7.839-7.868c-2.489-1.084-5.398-0.271-6.961,1.95l-8.499,12.076l-46.599,9.319  c-23.723,4.748-40.801,25.579-40.801,49.774v58.206C151.681,495.187,201.873,512,256,512s104.319-16.813,145.67-45.48v-58.206  C401.67,384.119,384.591,363.287,360.867,358.541z" data-original="#172956"/><path style="fill:#121149;" d="M401.67,408.314c0-24.195-17.079-45.027-40.803-49.773l-46.599-9.319l-8.235-12.021  c-1.541-2.25-4.458-3.098-6.966-2.026l-7.999,7.985c-9.614,9.599-22.19,14.414-34.78,14.462v154.374  c54.017-0.06,104.103-16.862,145.382-45.477V408.314z" data-original="#121149"/><path style="fill:#6D4711;" d="M314.268,349.222l46.599,9.319c23.724,4.746,40.803,25.578,40.803,49.773v58.206  C360.319,495.187,310.127,512,256,512s-104.319-16.813-145.67-45.48v-58.206c0-24.195,17.079-45.027,40.803-49.773l46.599-9.319  l58.125,153.728L314.268,349.222z" data-original="#6D4711"/><path style="fill:#56340C;" d="M401.67,408.314c0-24.195-17.079-45.027-40.803-49.773l-46.599-9.319l-57.98,152.593v10.181  c54.017-0.06,104.103-16.862,145.382-45.477V408.314z" data-original="#56340C"/><path style="fill:#FFEDB5;" d="M347.531,205.44c0.459-4.244,0.698-8.428,0.698-12.509c0-53.974-41.293-97.728-92.229-97.728  s-92.229,43.753-92.229,97.728c0,4.08,0.24,8.264,0.698,12.509c-8.945,2.326-13.202,16.087-9.506,30.817  c3.708,14.776,14.014,24.921,23.018,22.661c0.821-0.205,1.586-0.538,2.326-0.927c16.667,33.452,44.351,60.594,75.693,60.594  s59.027-27.139,75.693-60.594c0.74,0.39,1.505,0.722,2.326,0.927c9.004,2.26,19.309-7.885,23.018-22.661  C360.732,221.527,356.476,207.763,347.531,205.44z" data-original="#FFEDB5" class=""/><path style="fill:#E8CF89;" d="M347.531,205.44c0.459-4.244,0.698-8.428,0.698-12.509c0-53.872-41.138-97.554-91.941-97.721v223.365  c31.223-0.167,58.79-27.236,75.405-60.585c0.74,0.39,1.505,0.721,2.326,0.927c9.004,2.26,19.309-7.885,23.018-22.661  C360.732,221.527,356.476,207.763,347.531,205.44z" data-original="#E8CF89"/><path style="fill:#494948;" d="M161.127,181.119c0,0,1.724,24.442,6.034,39.253l8.045-0.371c0,0-2.012-19.997,14.941-40.365  S161.127,181.119,161.127,181.119z" data-original="#494948"/><path style="fill:#FEE187;" d="M378.397,140.802c0,31.194-54.936,42.894-122.534,42.894s-122.261-11.7-122.261-42.894  S188.402,59.864,256,59.864S378.397,109.608,378.397,140.802z" data-original="#FEE187" class=""/><path style="fill:#FFC61B;" d="M378.397,140.802c0-31.149-54.644-80.789-122.109-80.93v123.823  C323.691,183.644,378.397,171.929,378.397,140.802z" data-original="#FFC61B" class=""/><path style="fill:#F3F781" d="M350.873,181.119c0,0-1.724,24.442-6.034,39.253l-8.045-0.371c0,0,2.012-19.997-14.941-40.365  S350.873,181.119,350.873,181.119z" data-original="#333333" class="" data-old_color="#F3F781"/><path style="fill:#172956;" d="M256,210.161c37.505,0,71.494-9.485,96.308-24.855c1.898-1.176,1.076-4.099-1.157-18.041H160.849  c-2.232,13.941-3.055,16.865-1.157,18.041C184.508,200.676,218.495,210.161,256,210.161z" data-original="#172956"/><path style="fill:#121149;" d="M351.151,167.265h-94.863v42.893c37.392-0.048,71.27-9.519,96.02-24.852  C354.206,184.13,353.383,181.207,351.151,167.265z" data-original="#121149"/><path style="fill:#6D4711;" d="M350.899,185.792c-62.828-8.919-126.561-8.938-189.392-0.057c-1.46,0.207-2.808-0.805-3.013-2.265  c-0.938-6.728-1.874-13.455-2.812-20.183c-0.305-2.189,1.221-4.224,3.41-4.529c64.307-8.975,129.508-8.975,193.815,0  c2.189,0.305,3.715,2.339,3.41,4.529c-0.957,6.863-1.912,13.726-2.869,20.589C353.278,185.11,352.135,185.966,350.899,185.792z" data-original="#6D4711"/><path style="fill:#56340C;" d="M352.908,158.756c-32.058-4.474-64.338-6.715-96.62-6.728v27.062  c31.613,0.014,63.224,2.248,94.61,6.703c1.236,0.176,2.379-0.681,2.551-1.919c0.957-6.863,1.912-13.726,2.869-20.589  C356.624,161.096,355.097,159.061,352.908,158.756z" data-original="#56340C"/><path style="fill:#494948;" d="M273.984,270.6c-12.045-5.847-15.736-3.277-17.984-0.398c-2.248-2.879-5.939-5.449-17.984,0.398  c-12.317,5.98-28.562-0.912-28.562-0.912s8.675,12.622,29.915,11.047c9.099-0.674,14.686-3.463,16.631-6.296  c1.945,2.832,7.532,5.622,16.631,6.296c21.242,1.574,29.915-11.047,29.915-11.047S286.303,276.58,273.984,270.6z" data-original="#494948"/><path style="fill:#F3F781" d="M273.984,270.6c-11.552-5.608-15.415-3.472-17.696-0.748v4.925c2.146,2.713,7.608,5.31,16.343,5.958  c21.242,1.576,29.915-11.047,29.915-11.047S286.303,276.58,273.984,270.6z" data-original="#333333" class="" data-old_color="#F3F781"/><g>
	<rect x="220.729" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="276.98" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="234.882" y="119.967" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="249.001" y="105.831" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
	<rect x="263.154" y="119.967" style="fill:#494948;" width="14.288" height="14.288" data-original="#494948"/>
</g></g> </svg>

</div>
				<div  class=" col-md-2 "  Style="text-align:center">
		
			
			</div>
			<div  class=" col-md-3 "></div>
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
                    <h2 class="modal-title" id="myModalLabel"><?php echo lang('EtatChauf');?></h2>
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

		
	$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="layout/images/loading.gif" /></center>').load('etat_chauf.php?aff');
});
function rechercher(Etat){
$('#act').attr('value',Etat);
	
	$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="layout/images/loading.gif" /></center>');
	$('#formRechF').ajaxSubmit({target:'#formRes',url:'etat_chauf.php?rech'});
		}
 function ajouter(){
		$('#act').attr('value','add');	
	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='etat_chauf.php?add';
     $.get(url, null, function(data) {
      //$modal.find('.modal-body').html(data);
	   $modal.find('.modal-body').html(data);
    })
}
 function mod(id){
		$('#act').attr('value','mod');	
	
	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='etat_chauf.php?mod&&ID='+id;
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
				CIN: {
					validators: {
						notEmpty: {
							message: '<?php echo lang('RemplirChp'); ?>'
						}
						
					}
				},
				Nom: {
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
				DateEmb: {
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
		var url="etat_chauf.php?goAdd";
			 }
    else   if($('#act').val()=="mod" ){ 
		var url="etat_chauf.php?goMod";
			 }
	//alert(url);
	// alert(url);
                $.ajax({
                    method: 'POST',
                    url: url,
					data: $("#FormAdd").serialize(),
                    //success: function (data) {
					success: function(responseData, textStatus, jqXHR) {
					     
							// $("#BoxA").find('.modal-body').html(responseData);
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

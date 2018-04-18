<?php
session_start();
if(!isset($_SESSION['username'])){
header('Location: index.php');
exit();
}
	include_once "init.php";



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
	if(isset($_POST['act'])&& ($_POST['act']!="")&& ($_POST['act']!=0)  )
	{

		$where=" where etat= ".$_POST['act'];
	}
	$sqlA = "SELECT ID Id,c.marque Marque,c.matricule Matricule, c.designation Designation,c.tare Tare,Etat Etat FROM camions c
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
						<th><?php echo lang('etat_actuel'); ?></th>
						</tr>
					</thead><tbody>
				<?php while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
				 $class="";
					if($row['Etat']=="1") $class="libre";
					else if($row['Etat']=="2") $class="affecter";
					else if($row['Etat']=="3") $class="demarer";
					?>
					<tr class="<?php echo $class;?>">

						<td data-title="<?php echo lang('Matricule');?>" align="left"><?php echo ucfirst(stripslashes($row['Matricule']));?></td>
						<td data-title="<?php echo lang('Marque');?>"><?php  echo ucfirst(stripslashes($row['Marque']));?>	</td>
						<td data-title="<?php echo lang('Designation');?>"><?php  echo ucfirst($row['Designation']);?></td>
						<td data-title="<?php echo lang('Tare');?>"><?php  echo ucfirst($row['Tare']);?></td>
						<td data-title="<?php echo lang('etat_actuel'); ?>">
							<a class="btn btn-info" href="etat_actuel.php?id_camion=<?php echo $row['Id'] ?>&designation=<?php echo $row['Designation'] ?>&marque=<?php echo $row['Marque'] ?>&matricule=<?php echo $row['Matricule'] ?>">
								<?php echo lang('etat_actuel'); ?>
							</a>
						</td>
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
			<br><Center><h2><?php echo lang('EtatCamion');?></h2></center>
		<div  class="row row-centered ">
  <div id="formRech" class="  col-sm-12  col-md-12  col-centered ">

 <form id="formRechF" method="post" name="formRechF" >

		<input type="HIDDEN" id="act" name="act"  />

		<div class="row">
			<div  class=" col-md-2 "></div>
			<div  class=" col-md-2 all cadre ">
				<?php echo lang('Tous');?>
			</div>
			<div  class=" col-md-2 libre cadre">
				<?php echo lang('Libre');?>
			</div>
			<div  class=" col-md-2 affecter cadre">
				<?php echo lang('Affecter');?>
			</div>
			<div  class=" col-md-2 demarer cadre">
				<?php echo lang('Demarer');?>
			</div>

			<div  class=" col-md-2 "></div>
	</div>
	<div class="row">
		<div  class=" col-md-2 "></div>
		<div  class=" col-md-2 col-sm-12 text-center  centerLabel" Style="text-align:center">

<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px"
viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="100px" onclick="rechercher(0)" class=""><g><polygon style="fill:#626F76" points="441.379,220.69 344.276,220.69 344.276,132.414 423.724,132.414 " data-original="#B4E1FA" class="" data-old_color="#3996CA"/><path style="fill:#707487;" d="M17.655,370.759h476.69c4.875,0,8.828-3.953,8.828-8.828v-61.793H8.828v61.793  C8.828,366.806,12.78,370.759,17.655,370.759z" data-original="#707487" class=""/><polygon style="fill:#5B5D6E;" points="344.276,300.138 344.276,158.897 308.966,158.897 308.966,300.138 8.828,300.138   8.828,335.448 503.172,335.448 503.172,300.138 " data-original="#5B5D6E" class=""/><g>
	<path style="fill:#DBD0B8" d="M8.828,79.448h300.138c4.875,0,8.828,3.953,8.828,8.828v211.862c0,4.875-3.953,8.828-8.828,8.828   H8.828c-4.875,0-8.828-3.953-8.828-8.828V88.276C0,83.401,3.953,79.448,8.828,79.448z" data-original="#FFD782" class="" data-old_color="#FFD782"/>
	<path style="fill:#DBD0B8" d="M490.367,225.858l-41.22-11.777l-12.785-70.339c-3.06-16.793-17.673-28.983-34.741-28.983h-57.345   c-4.879,0-8.828,3.948-8.828,8.828v158.897h167.724v-39.649C503.172,234.952,497.947,228.023,490.367,225.858z M370.759,211.862   c-9.751,0-17.655-7.904-17.655-17.655v-44.138c0-4.875,3.953-8.828,8.828-8.828H410.7c4.271,0,7.93,3.058,8.687,7.262   l10.326,57.292c0.569,3.16-1.859,6.067-5.07,6.067H370.759z" data-original="#FFD782" class="" data-old_color="#FFD782"/>
</g><circle style="fill:#464655;" cx="414.897" cy="379.586" r="52.966" data-original="#464655" class=""/><circle style="fill:#E2DACE" cx="414.897" cy="379.586" r="35.31" data-original="#C7CFE2" class="" data-old_color="#FFC36E"/><circle style="fill:#70788D" cx="414.897" cy="379.586" r="13.241" data-original="#AFB9D2" class="active-path" data-old_color="#484D5A"/><circle style="fill:#464655;" cx="88.276" cy="379.586" r="52.966" data-original="#464655" class=""/><circle style="fill:#E2DACE" cx="88.276" cy="379.586" r="35.31" data-original="#C7CFE2" class="" data-old_color="#FFC36E"/><circle style="fill:#70788D" cx="88.276" cy="379.586" r="13.241" data-original="#AFB9D2" class="active-path" data-old_color="#484D5A"/><path style="fill:#C8BBAA" d="M344.276,361.931h3.372c3.343,0,6.4-1.889,7.895-4.879l19.164-38.326  c2.99-5.982,9.103-9.76,15.791-9.76h48.798c6.687,0,12.801,3.778,15.791,9.76l19.164,38.326c1.495,2.99,4.552,4.879,7.895,4.879  h21.026c4.875,0,8.828-3.953,8.828-8.828V291.31c0-9.751-7.904-17.655-17.655-17.655H335.448v79.448  C335.448,357.978,339.401,361.931,344.276,361.931z" data-original="#FFC36E" class="" data-old_color="#FFC36E"/><path style="fill:#70788D" d="M308.966,388.414H220.69c-4.875,0-8.828-3.953-8.828-8.828v-35.31c0-4.875,3.953-8.828,8.828-8.828  h88.276c4.875,0,8.828,3.953,8.828,8.828v35.31C317.793,384.461,313.841,388.414,308.966,388.414z" data-original="#AFB9D2" class="active-path" data-old_color="#484D5A"/><g>
	<path style="fill:#CDD2E1" d="M247.172,353.103L247.172,353.103c-4.875,0-8.828,3.953-8.828,8.828v26.483H256v-26.483   C256,357.056,252.047,353.103,247.172,353.103z" data-original="#959CB3" class="" data-old_color="#959CB3"/>
	<path style="fill:#CDD2E1" d="M282.483,353.103L282.483,353.103c-4.875,0-8.828,3.953-8.828,8.828v26.483h17.655v-26.483   C291.31,357.056,287.358,353.103,282.483,353.103z" data-original="#959CB3" class="" data-old_color="#959CB3"/>
</g><path style="fill:#C8BBAA" d="M291.31,291.31H26.483c-4.875,0-8.828-3.953-8.828-8.828l0,0c0-4.875,3.953-8.828,8.828-8.828H291.31  c4.875,0,8.828,3.953,8.828,8.828l0,0C300.138,287.358,296.185,291.31,291.31,291.31z" data-original="#FFC36E" class="" data-old_color="#FFC36E"/></g> </svg>

		</div>

			<div  class=" col-md-2 col-sm-12 text-center  centerLabel" Style="text-align:center">

<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px"
viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="100px" onclick="rechercher(1)" class=""><g><polygon style="fill:#B4E1FA;" points="441.379,220.69 344.276,220.69 344.276,132.414 423.724,132.414 " data-original="#B4E1FA" class=""/><path style="fill:#707487;" d="M17.655,370.759h476.69c4.875,0,8.828-3.953,8.828-8.828v-61.793H8.828v61.793  C8.828,366.806,12.78,370.759,17.655,370.759z" data-original="#707487" class=""/><polygon style="fill:#5B5D6E;" points="344.276,300.138 344.276,158.897 308.966,158.897 308.966,300.138 8.828,300.138   8.828,335.448 503.172,335.448 503.172,300.138 " data-original="#5B5D6E" class=""/><g>
	<path style="fill:#629CE2" d="M8.828,79.448h300.138c4.875,0,8.828,3.953,8.828,8.828v211.862c0,4.875-3.953,8.828-8.828,8.828   H8.828c-4.875,0-8.828-3.953-8.828-8.828V88.276C0,83.401,3.953,79.448,8.828,79.448z" data-original="#FFD782" class="" data-old_color="#0072FF"/>
	<path style="fill:#629CE2" d="M490.367,225.858l-41.22-11.777l-12.785-70.339c-3.06-16.793-17.673-28.983-34.741-28.983h-57.345   c-4.879,0-8.828,3.948-8.828,8.828v158.897h167.724v-39.649C503.172,234.952,497.947,228.023,490.367,225.858z M370.759,211.862   c-9.751,0-17.655-7.904-17.655-17.655v-44.138c0-4.875,3.953-8.828,8.828-8.828H410.7c4.271,0,7.93,3.058,8.687,7.262   l10.326,57.292c0.569,3.16-1.859,6.067-5.07,6.067H370.759z" data-original="#FFD782" class="" data-old_color="#0072FF"/>
</g><circle style="fill:#464655;" cx="414.897" cy="379.586" r="52.966" data-original="#464655" class=""/><circle style="fill:#C7CFE2;" cx="414.897" cy="379.586" r="35.31" data-original="#C7CFE2"/><circle style="fill:#AFB9D2;" cx="414.897" cy="379.586" r="13.241" data-original="#AFB9D2"/><circle style="fill:#464655;" cx="88.276" cy="379.586" r="52.966" data-original="#464655" class=""/><circle style="fill:#C7CFE2;" cx="88.276" cy="379.586" r="35.31" data-original="#C7CFE2"/><circle style="fill:#AFB9D2;" cx="88.276" cy="379.586" r="13.241" data-original="#AFB9D2"/><path style="fill:#AFB9D2" d="M344.276,361.931h3.372c3.343,0,6.4-1.889,7.895-4.879l19.164-38.326  c2.99-5.982,9.103-9.76,15.791-9.76h48.798c6.687,0,12.801,3.778,15.791,9.76l19.164,38.326c1.495,2.99,4.552,4.879,7.895,4.879  h21.026c4.875,0,8.828-3.953,8.828-8.828V291.31c0-9.751-7.904-17.655-17.655-17.655H335.448v79.448  C335.448,357.978,339.401,361.931,344.276,361.931z" data-original="#FFC36E" class="active-path" data-old_color="#FFC36E"/><path style="fill:#AFB9D2;" d="M308.966,388.414H220.69c-4.875,0-8.828-3.953-8.828-8.828v-35.31c0-4.875,3.953-8.828,8.828-8.828  h88.276c4.875,0,8.828,3.953,8.828,8.828v35.31C317.793,384.461,313.841,388.414,308.966,388.414z" data-original="#AFB9D2"/><g>
	<path style="fill:#959CB3;" d="M247.172,353.103L247.172,353.103c-4.875,0-8.828,3.953-8.828,8.828v26.483H256v-26.483   C256,357.056,252.047,353.103,247.172,353.103z" data-original="#959CB3"/>
	<path style="fill:#959CB3;" d="M282.483,353.103L282.483,353.103c-4.875,0-8.828,3.953-8.828,8.828v26.483h17.655v-26.483   C291.31,357.056,287.358,353.103,282.483,353.103z" data-original="#959CB3"/>
</g><path style="fill:#AFB9D2" d="M291.31,291.31H26.483c-4.875,0-8.828-3.953-8.828-8.828l0,0c0-4.875,3.953-8.828,8.828-8.828H291.31  c4.875,0,8.828,3.953,8.828,8.828l0,0C300.138,287.358,296.185,291.31,291.31,291.31z" data-original="#FFC36E" class="active-path" data-old_color="#FFC36E"/></g> </svg>

		</div>
				<div  class=" col-md-2  "  Style="text-align:center">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
			id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
			xml:space="preserve" width="100px" onclick="rechercher(2)" class=""><g><polygon style="fill:#B4E1FA;" points="441.379,220.69 344.276,220.69 344.276,132.414 423.724,132.414 " data-original="#B4E1FA" class=""/><path style="fill:#707487;" d="M17.655,370.759h476.69c4.875,0,8.828-3.953,8.828-8.828v-61.793H8.828v61.793  C8.828,366.806,12.78,370.759,17.655,370.759z" data-original="#707487" class=""/><polygon style="fill:#5B5D6E;" points="344.276,300.138 344.276,158.897 308.966,158.897 308.966,300.138 8.828,300.138   8.828,335.448 503.172,335.448 503.172,300.138 " data-original="#5B5D6E" class=""/><g>
	<path style="fill:#F4F453" d="M8.828,79.448h300.138c4.875,0,8.828,3.953,8.828,8.828v211.862c0,4.875-3.953,8.828-8.828,8.828   H8.828c-4.875,0-8.828-3.953-8.828-8.828V88.276C0,83.401,3.953,79.448,8.828,79.448z" data-original="#FFD782" class="active-path" data-old_color="#F3F353"/>
	<path style="fill:#F4F453" d="M490.367,225.858l-41.22-11.777l-12.785-70.339c-3.06-16.793-17.673-28.983-34.741-28.983h-57.345   c-4.879,0-8.828,3.948-8.828,8.828v158.897h167.724v-39.649C503.172,234.952,497.947,228.023,490.367,225.858z M370.759,211.862   c-9.751,0-17.655-7.904-17.655-17.655v-44.138c0-4.875,3.953-8.828,8.828-8.828H410.7c4.271,0,7.93,3.058,8.687,7.262   l10.326,57.292c0.569,3.16-1.859,6.067-5.07,6.067H370.759z" data-original="#FFD782" class="active-path" data-old_color="#F3F353"/>
</g><circle style="fill:#464655;" cx="414.897" cy="379.586" r="52.966" data-original="#464655" class=""/><circle style="fill:#C7CFE2;" cx="414.897" cy="379.586" r="35.31" data-original="#C7CFE2" class=""/><circle style="fill:#AFB9D2;" cx="414.897" cy="379.586" r="13.241" data-original="#AFB9D2"/><circle style="fill:#464655;" cx="88.276" cy="379.586" r="52.966" data-original="#464655" class=""/><circle style="fill:#C7CFE2;" cx="88.276" cy="379.586" r="35.31" data-original="#C7CFE2" class=""/><circle style="fill:#AFB9D2;" cx="88.276" cy="379.586" r="13.241" data-original="#AFB9D2"/><path style="fill:#AFB9D2" d="M344.276,361.931h3.372c3.343,0,6.4-1.889,7.895-4.879l19.164-38.326  c2.99-5.982,9.103-9.76,15.791-9.76h48.798c6.687,0,12.801,3.778,15.791,9.76l19.164,38.326c1.495,2.99,4.552,4.879,7.895,4.879  h21.026c4.875,0,8.828-3.953,8.828-8.828V291.31c0-9.751-7.904-17.655-17.655-17.655H335.448v79.448  C335.448,357.978,339.401,361.931,344.276,361.931z" data-original="#FFC36E" class="" data-old_color="#FFC36E"/><path style="fill:#AFB9D2;" d="M308.966,388.414H220.69c-4.875,0-8.828-3.953-8.828-8.828v-35.31c0-4.875,3.953-8.828,8.828-8.828  h88.276c4.875,0,8.828,3.953,8.828,8.828v35.31C317.793,384.461,313.841,388.414,308.966,388.414z" data-original="#AFB9D2"/><g>
	<path style="fill:#FFFF00" d="M247.172,353.103L247.172,353.103c-4.875,0-8.828,3.953-8.828,8.828v26.483H256v-26.483   C256,357.056,252.047,353.103,247.172,353.103z" data-original="#959CB3" class="" data-old_color="#959CB3"/>
	<path style="fill:#FFFF00" d="M282.483,353.103L282.483,353.103c-4.875,0-8.828,3.953-8.828,8.828v26.483h17.655v-26.483   C291.31,357.056,287.358,353.103,282.483,353.103z" data-original="#959CB3" class="" data-old_color="#959CB3"/>
</g><path style="fill:#AFB9D2" d="M291.31,291.31H26.483c-4.875,0-8.828-3.953-8.828-8.828l0,0c0-4.875,3.953-8.828,8.828-8.828H291.31  c4.875,0,8.828,3.953,8.828,8.828l0,0C300.138,287.358,296.185,291.31,291.31,291.31z" data-original="#FFC36E" class="" data-old_color="#FFC36E"/></g> </svg>
</div>
				<div  class=" col-md-2 "  Style="text-align:center">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
			id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
			xml:space="preserve"  width="100px" onclick="rechercher(3)" class=""><g><polygon style="fill:#B4E1FA;" points="441.379,220.69 344.276,220.69 344.276,132.414 423.724,132.414 " data-original="#B4E1FA" class=""/><path style="fill:#707487;" d="M17.655,370.759h476.69c4.875,0,8.828-3.953,8.828-8.828v-61.793H8.828v61.793  C8.828,366.806,12.78,370.759,17.655,370.759z" data-original="#707487" class=""/><polygon style="fill:#5B5D6E;" points="344.276,300.138 344.276,158.897 308.966,158.897 308.966,300.138 8.828,300.138   8.828,335.448 503.172,335.448 503.172,300.138 " data-original="#5B5D6E" class=""/><g>
	<path style="fill:#25E12D" d="M8.828,79.448h300.138c4.875,0,8.828,3.953,8.828,8.828v211.862c0,4.875-3.953,8.828-8.828,8.828   H8.828c-4.875,0-8.828-3.953-8.828-8.828V88.276C0,83.401,3.953,79.448,8.828,79.448z" data-original="#FFD782" class="active-path" data-old_color="#00FF0C"/>
	<path style="fill:#25E12D" d="M490.367,225.858l-41.22-11.777l-12.785-70.339c-3.06-16.793-17.673-28.983-34.741-28.983h-57.345   c-4.879,0-8.828,3.948-8.828,8.828v158.897h167.724v-39.649C503.172,234.952,497.947,228.023,490.367,225.858z M370.759,211.862   c-9.751,0-17.655-7.904-17.655-17.655v-44.138c0-4.875,3.953-8.828,8.828-8.828H410.7c4.271,0,7.93,3.058,8.687,7.262   l10.326,57.292c0.569,3.16-1.859,6.067-5.07,6.067H370.759z" data-original="#FFD782" class="active-path" data-old_color="#00FF0C"/>
</g><circle style="fill:#464655;" cx="414.897" cy="379.586" r="52.966" data-original="#464655" class=""/><circle style="fill:#C7CFE2;" cx="414.897" cy="379.586" r="35.31" data-original="#C7CFE2" class=""/><circle style="fill:#AFB9D2;" cx="414.897" cy="379.586" r="13.241" data-original="#AFB9D2"/><circle style="fill:#464655;" cx="88.276" cy="379.586" r="52.966" data-original="#464655" class=""/><circle style="fill:#C7CFE2;" cx="88.276" cy="379.586" r="35.31" data-original="#C7CFE2" class=""/><circle style="fill:#AFB9D2;" cx="88.276" cy="379.586" r="13.241" data-original="#AFB9D2"/><path style="fill:#AFB9D2" d="M344.276,361.931h3.372c3.343,0,6.4-1.889,7.895-4.879l19.164-38.326  c2.99-5.982,9.103-9.76,15.791-9.76h48.798c6.687,0,12.801,3.778,15.791,9.76l19.164,38.326c1.495,2.99,4.552,4.879,7.895,4.879  h21.026c4.875,0,8.828-3.953,8.828-8.828V291.31c0-9.751-7.904-17.655-17.655-17.655H335.448v79.448  C335.448,357.978,339.401,361.931,344.276,361.931z" data-original="#FFC36E" class="" data-old_color="#FFC36E"/><path style="fill:#AFB9D2;" d="M308.966,388.414H220.69c-4.875,0-8.828-3.953-8.828-8.828v-35.31c0-4.875,3.953-8.828,8.828-8.828  h88.276c4.875,0,8.828,3.953,8.828,8.828v35.31C317.793,384.461,313.841,388.414,308.966,388.414z" data-original="#AFB9D2"/><g>
	<path style="fill:#FFFF00" d="M247.172,353.103L247.172,353.103c-4.875,0-8.828,3.953-8.828,8.828v26.483H256v-26.483   C256,357.056,252.047,353.103,247.172,353.103z" data-original="#959CB3" class="" data-old_color="#959CB3"/>
	<path style="fill:#FFFF00" d="M282.483,353.103L282.483,353.103c-4.875,0-8.828,3.953-8.828,8.828v26.483h17.655v-26.483   C291.31,357.056,287.358,353.103,282.483,353.103z" data-original="#959CB3" class="" data-old_color="#959CB3"/>
</g><path style="fill:#AFB9D2" d="M291.31,291.31H26.483c-4.875,0-8.828-3.953-8.828-8.828l0,0c0-4.875,3.953-8.828,8.828-8.828H291.31  c4.875,0,8.828,3.953,8.828,8.828l0,0C300.138,287.358,296.185,291.31,291.31,291.31z" data-original="#FFC36E" class="" data-old_color="#FFC36E"/></g> </svg>


			</svg>

			</div>
			<div  class=" col-md-2 "></div>
		</div>
	</form>
	</div>
</div>
		</div>
		<div id="formRes" >

		</div>

			<div class="row   col-md-6" >
			 <div  style="display: none; margin: 0 auto; width:100%"   data-backdrop="static" class="modal fade "
	 data-keyboard="false" id="BoxA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="false">
           <div class="modal-dialog modal-lg" >
             <div class="modal-content">
                  <div class="modal-header">
                    <h2 class="modal-title" id="myModalLabel"><?php echo lang('EtatCamion');?></h2>
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
	$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="layout/images/loading.gif" /></center>').load('etat_camion.php?aff');
});
function rechercher(Etat){
$('#act').attr('value',Etat);
	$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="layout/images/loading.gif" /></center>');
	$('#formRechF').ajaxSubmit({target:'#formRes',url:'etat_camion.php?rech'});
		}
 function ajouter(){
		$('#act').attr('value','add');
	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='etat_camion.php?add';
     $.get(url, null, function(data) {
      //$modal.find('.modal-body').html(data);
	   $modal.find('.modal-body').html(data);
    })
}
 function mod(id){
		$('#act').attr('value','mod');

	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='etat_camion.php?mod&&ID='+id;
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
		var url="etat_camion.php?goAdd";
			 }
    else   if($('#act').val()=="mod" ){
		var url="etat_camion.php?goMod";
			 }
	//alert(url);
	// alert(url);
                $.ajax({
                    method: 'POST',
                    url: url,
					data: $("#FormAdd").serialize(),
                    //success: function (data) {
					success: function(responseData, textStatus, jqXHR) {
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

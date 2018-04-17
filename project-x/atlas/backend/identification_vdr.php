<?php
	session_start();
	if(!isset($_SESSION['username'])){
	header('Location: index.php');
	exit();
	}
	include_once "init.php";

SQLSRV_PHPTYPE_STRING('UTF-8') ;

if(isset($_GET['goMod'])){

	//parcourir($_POST);return;
	//on verif si codeF existe deja

		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $con ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}

			$reqModif = "UPDATE Buyer_seller SET name_ar='".addslashes(mb_strtolower($_POST['NomAr'], 'UTF-8'))."',";
			$reqModif .=  " name_en='".addslashes(mb_strtolower($_POST['NomEn'], 'UTF-8'))."',";
			$reqModif .=  " Licence_Number='".addslashes(mb_strtolower($_POST['Licence'], 'UTF-8'))."'";
			$reqModif .= " where IdSelBuy='".addslashes(mb_strtolower($_POST['IdTable'], 'UTF-8'))."'";

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
		SELECT  ID_B_S CodeSeller , name_ar,name_en , Licence_Number LicenceNumber
	FROM
		BUYER_SELLER t where  IdSelBuy= ".$ID;
	//execSQL($sql);
	//echo $sql;
$reponse=sqlsrv_query( $con, $sql, array(), array( "Scrollable" => 'static' ) );
	$row = sqlsrv_fetch_array( $reponse, SQLSRV_FETCH_ASSOC ) ;

?>
	<form id="FormAdd" method="post" name="FormAdd">
	<input type="hidden" value='<?php echo $ID;?>' name="IdTable" />
		  <div class="row col-md-12 col-sm-12 form-group">
			   <div class="col-md-5 col-sm-12 ">

						<div class="col-md-12 "><?php  echo lang('NomAr');?> :</div>

						<div class="col-md-12">
							  <input class="form-control" type="text" name="NomAr"  id="NomAr" value="<?php echo (htmlentities($row['name_ar'])); ?>"  />
						</div>
			   </div>

			      <div class="col-md-5 col-sm-12 ">

					<div class="col-md-12 "><?php  echo lang('NomEn');?> :</div>

						<div class="col-md-12 col-sm-12">
						  <input class="form-control" type="text" name="NomEn"  id="NomEn" value="<?php echo utf8_decode(htmlentities($row['name_en'])); ?>"
							/>
					</div>
				</div>


  </div>
 	  <div class="row col-md-12 form-group">
			   <div class="col-md-5 col-sm-12 ">

						<div class="col-md-12  "><?php  echo lang('LicenceNumber');?> :</div>

						<div class="col-md-12">
							  <input class="form-control" type="text" name="Licence"  id="Code"  value="<?php echo utf8_decode(htmlentities($row['LicenceNumber'])); ?>" />
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
	/*	if (isset($_POST['BUYER']))
		$Code= "B".Increment_Chaine_F("ID_B_S","BUYER_SELLER","ID_B_S",$con,"","");
	    else $Code= "S".Increment_Chaine_F("ID_B_S","BUYER_SELLER","ID_B_S",$con,"","");*/
$Code= "B".Increment_Chaine_F("ID_B_S","BUYER_SELLER","ID_B_S",$con,"","");
$reqInser1 = "INSERT INTO BUYER_SELLER (ID_B_S,name_ar,name_en,Licence_Number,type_b_s,DateCreate) values 	(?,?,?,?,?,?)";

		$params1= array(
		$Code,
		addslashes(mb_strtolower(securite_bdd($_POST['NomAr']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['NomEn']), 'UTF-8')),
		$_POST['Licence'],
		'BUYER',
		date("Y-m-d")
		) ;

		$stmt1 = sqlsrv_query( $con, $reqInser1, $params1 );
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
		/********************************************************/

	exit;
}
if (isset($_GET['add'])){

?>
	<form id="FormAdd" method="post" name="FormAdd">
		  <div class="row col-md-12 form-group chpinvisible">
	<div class="col-md-12">

								<div class="btn-group" data-toggle="buttons">

									<label class="btn btn-default active form-check-label">
										<input class="form-check-input" type="radio" name="Type" value="SELLER" checked autocomplete="off"><?php  echo lang('Vendeur');?>
									</label>

									<label class="btn btn-default form-check-label">
										<input class="form-check-input" type="radio" name="Type"  value="BUYER" autocomplete="off"> <?php  echo lang('Acheteur');?>
									</label>



								</div>
					</div>

		</div>
		  <div class="row col-md-12 col-sm-12 form-group">
			   <div class="col-md-5 col-sm-12 ">

						<div class="col-md-12 "><?php  echo lang('NomAr');?> :</div>

						<div class="col-md-12">
							  <input class="form-control" type="text" name="NomAr"  id="NomAr"  />
						</div>
			   </div>


			      <div class="col-md-5 col-sm-12 ">

					<div class="col-md-12 "><?php  echo lang('NomEn');?> :</div>

						<div class="col-md-12 col-sm-12">
						  <input class="form-control" type="text" name="NomEn"  id="NomEn"
							/>
					</div>
				</div>


  </div>
 	  <div class="row col-md-12 form-group">
			   <div class="col-md-5 col-sm-12 ">

						<div class="col-md-12  "><?php  echo lang('LicenceNumber');?> :</div>

						<div class="col-md-12">
							  <input class="form-control" type="text" name="Licence"  id="Code"  />
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
$title="";
	//echo toDateSql($_POST['DateF'));return;
		$where=" WHERE bs.TYPE_B_S LIKE 'SELLER' ";
		if(isset($_POST['DateD']) && isset($_POST['DateF']) && ($_POST['DateD']!="") && ($_POST['DateF']!="")  )
		{

			//*********** youssef 11-04-2018*************************
			//$var = '20/04/2012';

			//$date_1 = str_replace('/', '-', );
			//$date_1_ = date('Y-m-d', strtotime($date_1));

		
			//************************************




		if($_POST['DateD'] == $_POST['DateF'])
			{
			 	// $where.= " where cast(date_create AS date) = '".($_POST['DateD'))."' ";
				 $where.= " and convert(date,Date_lot) =  convert(date, '".($_POST['DateD'])."',105)";
				 $title=lang('IdentificationVdrs')." ".$_POST['DateD'] ;
			}
			else
			{
				 $where.= " and  convert(date,Date_lot) between    convert(date, '".($_POST['DateD'])."',105) and convert(date, '".($_POST['DateF'])."',105) ";
				 $title= lang('IdentificationVdrs') ." ". lang('From')." ".$_POST['DateD']." ". lang('To')." ".$_POST['DateF'] ;
			}
		}
		//echo $where;
	/*	else
		{
		//	$where=" where cast(date_create AS date)='".(date('m/d/Y'))."'";
		//$where=" where cast(date_create AS date)='".toDateSql(date('d/m/Y'))."'";
		}
	//	echo "vdr".$_SESSION['IdVendeur')."<br>";
		if($where=="") $where.= " where  idVendeur=".$_SESSION['IdVendeur']."";
		else $where.= " and idVendeur=".$_SESSION['IdVendeur']."";
*/
	$sqlA = "
		SELECT  bs.ID_B_S CodeSeller ,bs.name_".$_SESSION['Lang']." NameSeller,sum(l.Poids_net) AS PoidsNet  ,count(l.Num_lot) NbrLot
		FROM
		BUYER_SELLER bs
		INNER JOIN LOT l ON l.Code_vendeur=bs.ID_B_S
		".$where."
		GROUP BY  bs.ID_B_S  ,bs.name_".$_SESSION['Lang'];
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
		else $cTri= "PoidsNet";
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
//echo $sql;return;
/*execSQL($sql);*/
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resAff = sqlsrv_query($con,$sql,$params,$options) or die( print_r( sqlsrv_errors(), true));

	$nRes = sqlsrv_num_rows($resAff);
	//echo $nRes;
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
if ((isset($_POST['optradio'])) && ($_POST['optradio']=="Tableau")) {?>

		<form id="formSelec" method="post">

		<div class="responsive-table-line" style="margin:0px auto;max-width:1275px;">
			<table class="table table-bordered table-condensed table-body-center" id="table1">
					<thead class="entete">
						<tr >
						<th><?php echo lang('CodeSeller');?></th>
						<th><?php echo lang('NameSeller');?></th>
						<th><?php echo lang('Total')." ".lang('Kg');?></th>
						<th><?php echo lang('NbrLot');?></th>
						</tr>
					</thead><tbody>
				<?php while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
					?>
					<tr>

						<td data-title="<?php echo lang('CodeSeller');?>"><?php echo ucfirst(stripslashes($row['CodeSeller']));?></td>
						<td data-title="<?php echo lang('NameSeller');?>"><?php  echo ucfirst(stripslashes($row['NameSeller']));?>	</td>
						<td data-title="<?php echo lang('Total');?>"><span class="nbr">&#x200E;<?php  echo  number_format($row['PoidsNet'], 3, '.', ' ');?></span></td>
						<td data-title="<?php echo lang('NbrLot');?>"><span class="nbr">&#x200E;<?php  echo ucfirst($row['NbrLot']);?></span></td>
					</tr>
				<?php } ?>
					 </tbody>
			</table>
</div>

    </form>

    <?php

?>
<script language="javascript" type="text/javascript">
$(document).ready(function() {

    $('#table1').DataTable({
   language: {
    "sProcessing":   "جارٍ التحميل...",
    "sLengthMenu":   "أظهر _MENU_ مدخلات",
    "sZeroRecords":  "لم يعثر على أية سجلات",
    "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
    "sInfoEmpty":    "يعرض 0 إلى 0 من أصل 0 سجل",
    "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
    "sInfoPostFix":  "",
    "sSearch":       "ابحث:",
    "sUrl":          "",
    "oPaginate": {
        "sFirst":    "الأول",
        "sPrevious": "السابق",
        "sNext":     "التالي",
        "sLast":     "الأخير"
    }
},
	 paging: true,
	   responsive: true,
	 "bSort": false,
	 /*  "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],*/
	 "searching": false

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
}
else {
$series="";$Poids ="";$noms ="";
$i=0;
	while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){
			if($i<10){
			$series .="{ name: '".ucfirst(stripslashes($row['NameSeller']))."', y: ".$row['PoidsNet'].", Total: ".$row['PoidsNet']."},";
			$Poids .= " ".$row['PoidsNet'].", ";
			$noms .= " '".ucfirst(stripslashes($row['NameSeller']))."', ";
			//	$noms .= " '".utf8_encode($row['NameSeller'])."', ";
			}
	}
	?>    <div id="graphG"></div>
	<style>
	.highcharts-export-menu { z-Index: 1000 }
	</style>

<script language="javascript" type="text/javascript">


	$(document).ready(function(){
		Highcharts.setOptions({
			lang: {
			  resetZoom: 'تكبير',
			  rangeSelectorFrom: 'من',
			  rangeSelectorTo: 'الى',
			  rangeSelectorZoom: 'تكبير',
			  downloadJPEG: "JPEG تحميل صورة",
			  downloadPDF: "PDF تحميل صورة",
			  downloadPNG: "PNG تحميل صورة",
			  downloadSVG: "SVF تحميل صورة",
			 printChart: "طباعة صورة",
			  shortMonths:['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
			  weekdays: [ 'الأحد','الاثنين','الثلاثاء','الأربعاء', 'الخميس', 'الجمعة','السبت']
			}
		});

	 $('#graphG').highcharts('Chart', {
        chart: {
         plotBackgroundColor: null,
			 plotBorderWidth: null,
			 plotShadow: false,
			 type: 'column' ,
			 style: {
				fontSize: '20px'
			}
        },
		exporting: {
			allowHTML: true,
            scale: 1,
			enabled:false,
			buttons: {
				contextButton: {
					theme: {
						 zIndex: 10000
					 }
				}
			}
        },
	    xAxis: {
			  reversed: true,
			  categories:[<?php echo virgule($noms); ?>],

			   labels: {
                style: {
                    fontSize:'18px'
                },
				 useHTML: true
            },
        },
         yAxis: {
			title: {
				text: '<?php echo lang('Poids')." ".lang('Kg');?>',
				useHTML: true
			},
			opposite: true
		},
        title: {
			text: '<?php echo $title ; ?>',
			useHTML: true,

		},
		legend: {
			useHTML: true
		},
       /* tooltip: {
					useHTML: true,
		     pointFormat:'<?php echo lang('Total');?> : <b>&#x200E;{point.y:,.1f}</b>  <?php echo lang('Kg');?> <br/>'
        },*/
		tooltip: {
			 useHTML: true,
			headerFormat: '<span style="font-size:16px">{series.name}</span><br>',
			pointFormat: '<span style="color:#333;font-size:14px;font-weight:bold"> : {point.name}</span> <b> <br/> {point.y:,.3f} <?php echo lang('Kg');?></b> <br/>'
		},
		credits: {enabled: false},
      /*  plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    formatter: function () {
						return Highcharts.numberFormat(this.y,2);
					}
                }
            }
        },*/
		    plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
						  formatter: function () {
								return Highcharts.numberFormat(this.y,3)+' ';
							},
						//format: '{point.y:.3f} <?php echo lang('Kg');?>',
						  style: {
								color: '#666',
								fontSize : '18px'
							}
					}

				}
		},

        series: [{

				 name: '<?php echo lang('TotalIdentification');?>',
				 colorByPoint: true,
				 data: [<?php echo virgule($series); ?>]

       	 }]
    });
	  $(document).ready(function () {

    });
	//chart.exportChart();
});
</script>


<?php

}
}
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
			<br><Center><h2><?php echo lang('IdentificationVdr');?></h2></center>
		<div  class="row row-centered ">
  <div id="formRech" class="  col-sm-12  col-md-12  col-centered ">

 <form id="formRechF" method="post" name="formRechF"  class="bootstrap-iso">
		<div class="row " >
			<div  class="col-md-2 col-sm-12  " >
			<div class="middleLabel centerLabel"  ><label for="user_lastname"><?php echo lang('Periode');?>&nbsp;
			<?php echo lang('de');?></label> </div>
			</div>

			<div  class=" middleLabel col-md-2 col-sm-12 "  >

					<input class="form-control"  id="DateD" tabindex="2" name="DateD" type="text" size="10"
							 maxlength="10"  value="<?php //echo date('d/m/Y'); ?>"/>

			</div>


			<div  class="  col-md-1 col-sm-12  centerLabel" > <label for="user_lastname" Style="margin-top:10px">
			<?php echo lang('a');?></label>
			</div>
			<div  class=" middleLabel   col-md-2 col-sm-12 form-group " >
					<input  id="DateF" tabindex="2" name="DateF" type="text"  class="form-control"
					size="10" maxlength="10"  value="<?php //echo  date('d/m/Y'); ?>"/>

			</div>


			<div  class=" middleLabel   col-md-3 col-sm-12 form-group " style="font-size:19px">
				<div class="radio">
				<label><input type="radio" name="optradio" value="Tableau" checked> <?php echo lang('Tableau');?></label>&nbsp&nbsp&nbsp&nbsp
				  <label><input type="radio" name="optradio" value="Graphe"> <?php echo lang('Graphe');?></label>

				</div>
				<div class="radio">

				</div>

			</div>


			<div  class="  col-md-2 col-sm-12  centerLabel">
			&nbsp;<input type="button" value="<?php //echo lang('rechercher');?>" class="btn btn-primary"  id="rech" action="rech"
			onclick="rechercher()"; />

			&nbsp;<input type="reset" value="<?php echo lang('Annuler');?>" class="btn btn-primary chpinvisible"   id="reset" action="effacer"
			 />



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
                    <h2 class="modal-title" id="myModalLabel"><?php echo lang('IdentificationVdr');?></h2>
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

       $('#DateD').daterangepicker({
        singleDatePicker: true,
		 locale: {
            format: 'D/MM/YYYY'
        }
    });

		     $('#DateF').daterangepicker({
        singleDatePicker: true,
		 locale: {
            format: 'D/MM/YYYY'
        }
    });
rechercher();
	//$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="../images/loading2.gif" /></center>').load('identification_vdr.php?aff');
});
function rechercher(){

	$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="layout/images/loading.gif" /></center>');
	$('#formRechF').ajaxSubmit({target:'#formRes',url:'identification_vdr.php?rech'});
		}
 function ajouter(){
		$('#act').attr('value','add');
	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='identification_vdr.php?add';
     $.get(url, null, function(data) {
      //$modal.find('.modal-body').html(data);
	   $modal.find('.modal-body').html(data);
    })
}
 function mod(id){
		$('#act').attr('value','mod');

	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='identification_vdr.php?mod&&ID='+id;
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
				NomAr: {
					validators: {
						notEmpty: {
							message: 'Merci de saisir le nom'
						}

					}
				},
				Licence: {
					validators: {
						notEmpty: {
							message: 'Merci de saisir le prénom'
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

 jConfirm('<h3><?php  echo lang('terminerOperation'); ?></h3>', '<?php  echo lang('Confirm'); ?>', function(r) {
					if(r)	{
     if($('#act').val()=="add" ){
		var url="identification_vdr.php?goAdd";
			 }
    else   if($('#act').val()=="mod" ){
		var url="identification_vdr.php?goMod";
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
							     jAlert("<h3><?php  echo lang('messageAjoutSucces'); ?><h3>","<?php  echo lang('Operation'); ?>");
							  $("#BoxA").modal('hide');
							 $("#BoxA").find('.modal-body').html("");
								rechercher()
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

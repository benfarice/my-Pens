<?php
	session_start();
	if(!isset($_SESSION['username'])){
	header('Location: index.php');
	exit();
	}
	include_once "init.php";

SQLSRV_PHPTYPE_STRING('UTF-8') ; 
$type="";

if(isset($_GET['sel'])){
	$type="Sel";
	$TypeO="Seller";
	$_SESSION['Type']="Sel";
}else if(isset($_GET['Buy'])) {
	$type="Busssy";
	$TypeO="Buyer";
	$_SESSION['Type']="Buy";
}


if(isset($_GET['TypeReg'])){
 

?>
	<form id="FormAdd" method="post" name="FormAdd"> 
		  <div class="row col-md-12 ">	
			<div class="col-md-4  form-group">
						<IMG src="layout/images/espece.jpg" />		
										
				</div>
			
		<div class="col-md-4 form-group">
								<IMG src="layout/images/cheque.jpg" />				
										
				</div>

		<div class="col-md-4  form-group">
				<IMG src="layout/images/credit.jpg" />		
								
										
		</div>		
		</div>
		  <div class="row col-md-12 col-sm-12 " id="Espece">
			   <div class="col-md-4 col-sm-12 form-group">

						<div class="col-md-12"><label class=" control-label  labelRight"><?php  echo lang('Montant');?> :</label></div>
						
						<div class="col-md-12">
							  <input class="form-control" type="text" name="NomAr"  id="Montant"  /> 
						</div>
			   </div>
			   
			   
		</div>
   


 	  <div class="row col-md-12 form-group">
			   <div class="col-md-5 col-sm-12 ">

						<div class="col-md-12  "><label><?php  echo lang('LicenceNumber');?> :</label></div>
						
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
exit;	
}
if(isset($_GET['goAddFac'])){
//		parcourir($_POST);return;
	  $error="";
	  
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $con ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//echo $RefFicheCh;return;
$Date = date_create(date("Y-m-d H:i"));
$Heure=date("H:i:s");
$i=0;
$Tax=(floatval($_POST['TotalFac'])/100 )*5;
  		$CodeFac= "NF".Increment_Chaine_F("CodeFac","reglement_acht","IdFac",$con,"",array());	
			  	$reqInser2 = "INSERT INTO  reglement_acht(CodeFac,Date,Heure,Montant,TypeReg,Taxe,Pourcentage) 
					values (?,?,?,?,?,?,?)";
			$params2= array(
					$CodeFac,
					date("Y-m-d"),
					$Heure,
					floatval($_POST['TotalFac']),
					'NR',
					$Tax,
					5
			
			) ;
				$stmt3 = sqlsrv_query( $con, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout  facture ".$errors[0]['message'] . " <br/> ";
			
			}	
			//---------------------------ID fiche fac--------------------------------//
			$sql = "SELECT max(IdFac) as IdFac FROM reglement_acht ";
			$stmt2 = sqlsrv_query( $con, $sql );
			if( $stmt2 === false ) {
				$error.="Erreur recupération  IdFac: ".sqlsrv_errors() . " <br/> ";
			}
			sqlsrv_fetch($stmt2) ;
			$IdFac = sqlsrv_get_field( $stmt2, 0);
			$test="";
	$ligneSelect = explode(",",$_POST['CLETABLE']);
	$i=0;
//	parcourir($_POST);return;
array_shift($ligneSelect);
		foreach($ligneSelect as $a=>$contenu){
	
 // for( $i= 0 ; $i < count($_POST['CodeAdj']) ; $i++ ){
			  	$reqInser2 = "INSERT INTO  detailFactures(IdFac,CodeAdj) 
					values (?,?)";
			$params2= array(
					$IdFac,
					$_POST['CodeAdj'][$i]
			
			) ;
			$stmt3 = sqlsrv_query( $con, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout detail facture ".$errors[0]['message'] . " <br/> ";
				break ;
			}	
			$i+=1;
		  }
		  
if($error=="" ) {
 sqlsrv_commit( $con );

	?>
	<script language="javascript" type="text/javascript">
		rechercher();
		var IdFac="<?php echo $IdFac;?>";
		//document.location.href='facture_acht_pdf.php?IdFac='+IdFac;
		window.open ('facture_acht_pdf.php?IdFac='+IdFac, "_newtab" );
	/*	
				var file="<?php echo $name;?>";
					window.open("download_file.php?DownloadPdf&&File="+file,'_self');
					
					
		 options = "Width=900,Height=900" ;
			window.open( 'facture_acht.print.php?IdFac='+IdFac, "edition", options ) ;*/
	</script>
	<?php
	
} else {
     sqlsrv_rollback( $con );
     echo $error;
}
/*	$sup = true;
	$ligneSelect = explode(",",$_POST['CLETABLE']);
	foreach($ligneSelect as $a=>$ligne){
		if($ligne!=0){
			$data = explode(",",$ligne);
			$idp = $data[0];
			//$souche = $data[0];
			
			
				$sqlReq = "update $tableInser set etat=0 where $cleTable = '$idp'";
				
				if(!mysql_query($sqlReq)) echo "erreur suppression  ...";
				else{	if(!mysql_query($sqlReq)) {echo "ERREUR SUPPRESSION MARCHE";$sup=false;}}
			
		}
	}
	if($sup == true){
		?><script language="javascript" > 
				//alert('picesphp');
				alert('Supression de la selection effectuee.'); 
				rechercher();
		  </script>
		  <?php
	}else{
		?><script language="javascript" > alert('Un ou plusieurs elements de la selection n\'ont pas pu etre supprimes.'); </script><?php
	}*/

exit;
}

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
if (isset($_GET['ListAcht'])){
	
?>
	<form id="FormAdd" method="post" name="FormAdd"> 
	<div class="row">
	<div class="col-md-10" >
	<label for="user_lastname">
	<?php
if($_GET['type']=="Buy") 	echo lang('NameBye');
ELSE if($_GET['type']=="Sel") echo lang('NameSeller');  ?></label>
			<input class="form-control"  id="SearchB"name="SearchB" type="text" size="30"/>	
											
			</div>
	<div class="col-md-2" >
	
			&nbsp;<input type="button" value="<?php //echo $trad['button']['rechercher'];?>" 
			class=" btn-primary"  id="rech" action="rech" 
			onclick="RechAcht()" style="border:none;padding:12px 30px;margin-top:22px">
	</div>

</div>

	</form>
	<?php 

	if($_GET['type']=="Sel") {
	$query = "SELECT  ID_B_S CodeBye,name_".$_SESSION['Lang']." NameBuy,bs.LICENCE_NUMBER
  FROM BUYER_SELLER bs where bs.TYPE_B_S='SELLER' and bs.ENABLED = 1 
  and ID_B_S in (SELECT l.Code_vendeur FROM ADJUDICATION adj
	INNER JOIN LOT l ON l.Num_lot = adj.num_lot   where 
  Num_adjudication not IN (select df.num_adjudication from details_facture_vendeur df  ))

  order by IdSelBuy desc";
	}else if($_GET['type']=="Buy") 
	{
$query = "SELECT  ID_B_S CodeBye,name_".$_SESSION['Lang']." NameBuy,bs.LICENCE_NUMBER
  FROM BUYER_SELLER bs where bs.TYPE_B_S='BUYER' and bs.ENABLED = 1 
  and ID_B_S in (SELECT Code_Acheteur FROM ADJUDICATION where Num_adjudication not in(select COdeAdj from detailFactures) ) order by IdSelBuy desc";
	
	}
//echo $query;
$array = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$smt=sqlsrv_query($con,$query,$array,$options);
$ntres = sqlsrv_num_rows($smt);

  ?>
  <hr>
	<div class="col-md-12 "  id="Buyer" dir="ltr">
	
<div id="owl-example" class="owl-carousel">
<?php while($row = sqlsrv_fetch_array($smt, SQLSRV_FETCH_ASSOC)){?>
  <div class="cadreAcht"  onclick="GetId('<?php echo ucfirst(stripslashes($row['CodeBye']));?>','<?php echo ucfirst(stripslashes($row['NameBuy']));?>')"> <?php echo ucfirst(stripslashes($row['NameBuy']));?> 
  <br>
   <?php echo ucfirst(stripslashes($row['CodeBye']));?></div>
<?php }?>
</div></div>
				
	<script  language="javascript" type="text/javascript">
	
$(document).ready(function() {	
$('#owl-example').owlCarousel({
        infinite: true     ,
		 visibleItems: 4,
    
});
      


 // $('.regular').slick('setPosition');
	ajaxindicatorstop();
	$("#BoxA").modal('show');
	
})


function	RechAcht(){
	var val=$("#SearchB").val();
$('#Buyer').load('reglements.php?RechAcht&Search='+val+'&&type=<?php echo $_GET['type'];?>');
}
	</script>
<?php
	exit;
}

if (isset($_GET['RechAcht'])){
	//ECHO $_GET['type'];return;
	if($_GET['type']=="Sel") {
		$query = "SELECT  ID_B_S CodeBye,name_".$_SESSION['Lang']." NameBuy,bs.LICENCE_NUMBER
  FROM BUYER_SELLER bs 
  where bs.TYPE_B_S='SELLER' and bs.ENABLED = 1 
  and ID_B_S in (SELECT l.Code_vendeur FROM ADJUDICATION adj
	INNER JOIN LOT l ON l.Num_lot = adj.num_lot   where 
  Num_adjudication not IN (select df.num_adjudication from details_facture_vendeur df  ))
  and  (ID_B_S like '%".$_GET['Search']."%' or name_".$_SESSION['Lang']." like '%".$_GET['Search']."%') 
  order by IdSelBuy desc";
}else if($_GET['type']=="Buy") {
	  	$query = "SELECT  ID_B_S CodeBye,name_".$_SESSION['Lang']." NameBuy,bs.LICENCE_NUMBER
  FROM BUYER_SELLER bs where bs.TYPE_B_S='BUYER' and bs.ENABLED = 1
  and  (ID_B_S like '%".$_GET['Search']."%' or name_".$_SESSION['Lang']." like '%".$_GET['Search']."%') 
  order by IdSelBuy desc";
  }
//echo $query ;
$array = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$smt=sqlsrv_query($con,$query,$array,$options);
$nRes = sqlsrv_num_rows($smt);
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
	<div id="owl-example" class="owl-carousel">
<?php while($row = sqlsrv_fetch_array($smt, SQLSRV_FETCH_ASSOC)){?>
  <div class="cadreAcht"  onclick="GetId('<?php echo ucfirst(stripslashes($row['CodeBye']));?>','<?php echo ucfirst(stripslashes($row['NameBuy']));?>')"> <?php echo ucfirst(stripslashes($row['NameBuy']));?> 
  <br>
   <?php echo ucfirst(stripslashes($row['CodeBye']));?></div>
<?php }?>
</div>
	<script  language="javascript" type="text/javascript">
	
$(document).ready(function() {
	
$('#owl-example').owlCarousel({
        infinite: true     ,
		 visibleItems: 1
    
})
})</script>
     <?php
}
		exit;
}
if (isset($_GET['rech']) or isset($_GET['aff'])){
$title="";
	//echo toDateSql($_POST['DateF'));return;
		$where="   ";
	
	
	if($_GET['type']=="Sel")  {
	if(isset($_POST['DateD']) && isset($_POST['DateF']) && ($_POST['DateD']!="") && ($_POST['DateF']!="")  )
		{
			if($_POST['DateD'] == $_POST['DateF'])
			{ 
			 	// $where.= " where cast(date_create AS date) = '".($_POST['DateD'))."' ";
				 $where.= " where convert(date,Date_F) = convert(date, '".($_POST['DateD'])."',105)";
			
			}
			else
			{
				 $where.= " where  Date_F between  convert(date, '".($_POST['DateD'])."',105 ) and convert(date,  '".($_POST['DateF'])."',105) ";
				
			}
		}

		if(isset($_POST['CodeBye']) && ($_POST['CodeBye']!=""))
		{
			 $where.= " and  f.Code_Vendeur like '%".$_POST['CodeBye']."%'";
		}
	//	echo $where;
	$sqlA = " 		
				SELECT  f.Id_Fac IdFac,f.Numero_facture CodeFac ,bye.ID_B_S CodeBye,
				bye.name_".$_SESSION['Lang']." NameBye ,f.Total Montant,
					f.Date_F DateFac

				fROM facture_vendeur f
				inner join details_facture_vendeur df on df.IdFac=f.IdFac
				INNER JOIN BUYER_SELLER bye ON bye.ID_B_S=f.CodeBye AND bye.TYPE_B_S='SELLER' 	".$where ."
				and f.Id_Fac not in(select dr.IdFac from detailReglements dr)
				 GROUP BY f.Id_Fac ,f.Numero_facture  ,bye.ID_B_S ,
				bye.name_ar  ,f.Total,
					f.Date_F 
		";
		
	}else if($_GET['type']=="Buy")  {
		if(isset($_POST['DateD']) && isset($_POST['DateF']) && ($_POST['DateD']!="") && ($_POST['DateF']!="")  )
		{
			if($_POST['DateD'] == $_POST['DateF'])
			{ 
			 	// $where.= " where cast(date_create AS date) = '".($_POST['DateD'))."' ";
				 $where.= " where convert(date,Date) = convert(date, '".($_POST['DateD'])."',105)";
			
			}
			else
			{
				 $where.= " where  Date between  convert(date, '".($_POST['DateD'])."',105 ) and convert(date,  '".($_POST['DateF'])."',105) ";
				
			}
		}




		if(isset($_POST['CodeBye']) && ($_POST['CodeBye']!=""))
		{
			 $where.= " and  f.CodeBye like '%".$_POST['CodeBye']."%'";
		}
		echo $where;return;
	$sqlA = " 		
				SELECT  f.IdFac IdFac,f.CodeFac CodeFac ,bye.ID_B_S CodeBye,
				bye.name_".$_SESSION['Lang']." NameBye ,f.Montant,
					f.Date DateFac

				fROM factures_acht f
				inner join detailFactures df on df.IdFac=f.IdFac
				INNER JOIN BUYER_SELLER bye ON bye.ID_B_S=f.CodeBye AND bye.TYPE_B_S='BUYER' 	".$where ."
				and f.IdFac not in(select dr.IdFac from detailReglements dr)
				 GROUP BY f.IdFac ,f.CodeFac  ,bye.ID_B_S ,
				bye.name_ar  ,f.Montant,
					f.Date 
		";
	}
		
//echo $sqlA."<hr>";
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
		else $cTri= "Date";
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
					<script language="javascript" type="text/javascript">
						$(document).ready(function() {
							$('#Save').css("display","none");
						})</script>
	
					<?php
		}
else
{
?>
<div id="brouillon" style="display:block">  </div> 
		<form id="formSelec" method="post">
		<div class="row" >
							<div class="  col-md-3" STYLE="display:inline"></div>
					<div class=" offset-md-3 col-md-6" STYLE="display:inline">
								<label STyle="float:right"><?php  echo lang('TotalReg');?> :	</label>
								<input class="form-control"  type="text" value="0.000" dir="ltr"
								name="TotalReg" id="TotalReg" STyle="font-size:30px; background:#333; color:#fff"/>
					</div><div class="  col-md-3" STYLE="display:inline"></div>
		</div>
		<div class="responsive-table-line" style="margin:0px auto;max-width:1275px;">
	
			<table class="table table-bordered table-condensed table-body-center" id="table1">
					<thead class="entete">
						<tr>
						<th width="8%"><?php echo lang('num_facture');?></th>
						<th width="8%"><?php if($type=="Sel") echo lang('CodeSeller'); else echo lang('CodeBye'); ?></th>
						<th width="12%"><?php if($type=="Sel") echo lang('NameSeller'); else echo lang('NameBye'); ?></th>
						<th width="8%"><?php echo lang('facture_date_title');?></th>
						<th width="12%" ><?php echo lang('TotalPrix')." (".lang('reyal_homany').')';?></th>
				      <th align="center"  width="10%">
			<input type="hidden" id="CLETABLE" name="CLETABLE" value="" />
			<input type="hidden" id="TotalFac" name="TotalFac" value="" />
	
	
	 <div class="pretty p-default p-smooth p-bigge prettyEntete">
				        <input type="checkbox"  id="select_all_checkbox" />
				        <div class="state p-primary">
				            <label id="Entete"><?php echo lang('select_all'); ?></label>
				        </div>
				    </div>
					
			</th>
					
						</tr>
					</thead><tbody>
				<?php $i=0; while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){		
					?>
					<input type="text" class="chpinvisible" name="CodeBuy" id="CodeBuy" value="<?php echo $row['CodeBye'];?>" />
				<input type="text" class="chpinvisible" id="NameBye" name="NameBye" value="<?php echo $row['NameBye'];?>" />
					<tr>				
						<td data-title="<?php echo lang('num_facture');?>"><?php echo ucfirst(stripslashes($row['CodeFac']));?></td>
						<td data-title="<?php  if($type=="Sel") echo lang('CodeSeller'); else echo lang('CodeBye');?>"><?php  echo ucfirst(stripslashes($row['CodeBye']));?>	</td>
						<td data-title="<?php  if($type=="Sel") echo lang('NameSeller'); else echo lang('NameBye'); ?>"><?php echo ucfirst(stripslashes($row['NameBye']));?></td>
						<td data-title="<?php echo lang('facture_date_title');?>"><?php echo  $row['DateFac'];?>
						<td data-title="<?php echo lang('TotalPrix');?>"><span class="nbr">&#x200E;<?php  echo  number_format($row['Montant'], 3, '.', ' ');?></span></td>
					  <td align="center" >
	

					 <div class="pretty p-default">
						<input  type="checkbox" onclick="CaculeTotalReg($('#checkLigne<?php echo $i;?>'));" 
						class="checkLigne" id='checkLigne<?php echo $i;?>' name="<?php	echo $row['IdFac']; ?>" 
						value="<?php	echo $row['Montant']; ?>"  />
						<div class="state">
							<label></label>
						</div>
					</div>
					
			  </td>
					</tr>
				<?php $i++;
				} ?>
					 </tbody>
			</table>
</div>

    </form>
	
    <?php
	
?>
<script language="javascript" type="text/javascript">

$(document).ready(function() {

    $('#Save').css("display","inline");
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
	 paging: false,
	   responsive: true,
	 "bSort": false,
	 /*  "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],*/
	 "searching": false
	 
  });
} );
	
function CaculeTotalReg(obj){
	var TotalReg=0;

	
	if($(obj).is(':checked'))
	{
			//alert($(obj).val());
			var TotalReg=$("#TotalReg").val().replace(" ","");
			TotalReg= parseFloat(TotalReg) + parseFloat($(obj).val());	
		//	alert(TotalReg);
			$('input#TotalReg').attr("value",number_format(TotalReg, 3, ".", " "));
			
	}else
	{
		var TotalReg=$("#TotalReg").val().replace(" ","");
			TotalReg= parseFloat(TotalReg) - parseFloat($(obj).val());	
			$('input#TotalReg').attr("value", number_format(TotalReg, 3, ".", " "));
	}; 
	

}


 $('#select_all_checkbox').click(function () {

    //alert("Thanks for checking me");
    //select_checkbox_f
     if (this.checked) {
		 var TotalReg=0;
      $('.checkLigne').prop('checked', true);
	  $(".checkLigne:checked").each(function(){

					TotalReg+=parseFloat($(this).val().replace(" ",""));							
						
				})
		
		$('input#TotalReg').attr("value", number_format(TotalReg,3, ".", " "));
				
    }else{
      $('.checkLigne').prop('checked', false);
	  $('input#TotalReg').attr("value","0.000");
    }

	});
		function TypeReg(){
				var idSelect = '';var TotalReg=0;
				var n = 0;
				$(".checkLigne:checked").each(function(){
						n++;
						idSelect +=","+$(this).attr("name");
						TotalReg+=parseFloat($(this).val());	
									
						
				});
				if(n>0){
				
			$('input#CLETABLE').attr("value",idSelect);
				
				
			//		ajaxindicatorstart('<?php echo lang('patienter');?>');
					var $modal = $('#BoxB');
						//var url='reglements.php?TypeReg';
						var NameBuy=$("#NameBye").val();
						var CodeBuy=$("#CodeBuy").val();
							var url='paiement.php?ListeFac='+idSelect+'&&Type=<?php echo $TypeO;?>&&ChoixTypeReg&&Precommande&&TotalReg='+TotalReg+'&&CodeBuy='+CodeBuy+'&NameBuy='+encodeURIComponent(NameBuy);
					
					 $.get(url, null, function(data) {
					   $modal.find('.modal-body').html(data);
					})
					//$("#BoxA").modal('show');
					
	
				}			
		}	
				
	</script>
<?php


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
	   

			<br><Center><h2><?php //echo lang('reglement_acht');
				if($type=="Buy") 	echo lang('reglement_acht');
			ELSE if($type=="Sel") echo lang('reglement_vdr');
			?></h2></center>
		<div  class="row row-centered ">
  <div id="formRech" class="  col-sm-12  col-md-11  col-centered ">	
  
 <form id="formRechF" method="post" name="formRechF"  class="bootstrap-iso"> 
		<div class="row " >
			<div  class="col-md-1 col-sm-12  " >	
			<div class="middleLabel centerLabel"  ><label for="user_lastname"><?php echo lang('facture_date_title');?>
			</label> </div>	 
			</div>
			
			<div  class=" middleLabel col-md-2 col-sm-12 "  >	
		
					<input class="form-control"  id="DateD" tabindex="2" name="DateD" type="text" size="10" 
							 maxlength="10"  value="<?php //echo date('d/m/Y'); ?>"/>	
					
			</div>
			

			<div  class="  col-md-1 col-sm-12  centerLabel chpinvisible" > <label for="user_lastname" Style="margin-top:10px">
			<?php echo lang('a');?></label>	
			</div>
			<div  class=" middleLabel   col-md-2 col-sm-12 form-group " >		
					<input  id="DateF" tabindex="2" name="DateF" type="text"  class="form-control" 
					size="10" maxlength="10"  value="<?php //echo  date('d/m/Y'); ?>"/>	
				
			</div>
		
			
			<div  class=" middleLabel   col-md-2 col-sm-12 centerLabel " >		
			<label for="user_lastname" >
			<?php if($type=="Sel") echo lang('CodeSeller'); else echo lang('CodeBye'); ?>
			</label>	</div>
			<div  class=" middleLabel   col-md-2 col-sm-12 centerLabel " >
					<input  id="CodeBye" tabindex="2" name="CodeBye" type="text"  class="form-control" 
					  value=""/>
					 	</div>
						<div  class=" middleLabel   col-md-2 col-sm-12 centerLabel " >
			<input  id="NameBuy" tabindex="2" name="NameBuy" type="text"  class="form-control" 
					  value=""/>						  
				
					</div>
			
			<div  class="  col-md-1 col-sm-12  centerLabel" >				
			<input type="button"  value="<?php //echo lang('rechercher');?>" class="btn btn-primary"  id="rech" action="rech" 
			onclick="ListAcht()"; style="padding:25px" />
		
			</div>
		</div>
		<div class="row " >	
		
			<div  class="  col-md-12 col-sm-12  centerLabel" >				
			&nbsp;<input type="button" value="<?php echo lang('rechercher');?>" class="btn btn-primary btnRech" 
			id="rech"  	onclick="rechercher()" style="width:200px;font-size:30px;margin-top:5px" />
			<input type="button" value="<?php echo lang('CreateReg');?>  " onClick="TypeReg();" 
			 class="btn btn-primary btnRech chpinvisible" id="Save"  style="cursor:pointer;width:200px;font-size:30px;margin-top:5px"
			>
			</div>
			</div>
		
	</form>
	</div>
</div>
		</div>
		
		
		
		<div id="formRes" >
		
		</div>
		<input type="hidden" id="act" />

			<div class="row   col-md-12" >
			 <div  style="display: none; margin: 0 auto; width:100%"   data-backdrop="static" class="modal fade "
	 data-keyboard="false" id="BoxA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  
	 aria-hidden="false">
           <div class="modal-dialog modal-lg" >
             <div class="modal-content">
                  <div class="modal-header">                   
                    <h2 class="modal-title" id="myModalLabel"><?php
			if($type=="Buy") 	echo lang('reglement_acht');
			ELSE if($type=="Sel") echo lang('reglement_vdr');
					?></h2>
                  </div>
				    <div id="Res"></div>
                  <div class="modal-body" style="    text-align: right;">
								<img src="http://conferoapp.com/icons/preloader.gif" class="progress">
                  </div>
				  <div class="clear"></div>
				  <div class="modal-footer" style="border:none;  text-align: right;"> 
				
				<Input type="button" class="btn btn-primary" onclick="CloseBox('BoxA')"  value="<?php echo  lang('Fermer');?> " />
				&nbsp;
				
				  </div>
            </div>
          </div>	    
        </div>
		</div>
		
		<style>
		@media (min-width: 576px) {
		  .modal-dialog { max-width: none; }
		}

		.modal-dialog {
		  width: 98%;

		  padding: 0;
		}

		.modal-content {
		
		}
		</style>
				<div class="row  col-lg-12 col-md-12" >
			 <div  style="display: visible; margin: 0 auto; width:100%"   data-backdrop="static" class="modal fade "
	 data-keyboard="false" id="BoxB" tabindex="-1" role="dialog" aria-labelledby="myModalLabelB"  
	 aria-hidden="false">
           <div class="modal-dialog modal-lg" >
             <div class="modal-content">
                  <div class="modal-header">                   
                    <h2 class="modal-title" id="myModalLabelB"><?php					
			if($type=="Buy") 	echo lang('reglement_acht');
			ELSE if($type=="Sel") echo lang('reglement_vdr');
?></h2>
                  </div>
				    <div id="ResB"></div>
                  <div class="modal-body" style="    text-align: right;">
								<img src="http://conferoapp.com/icons/preloader.gif" class="progress">
                  </div>
				  
				  <div class="modal-footer form-group" style="border:none;  text-align: right;"> 
				
				<Input type="button" class="btn btn-block  btn-primary" onclick="TerminerCmd()" id="create_facture_btn"  value="<?php echo  lang('Enregistrer');?> " />
				&nbsp;<Input type="button" class="btn btn-block  btn-primary" id="create_facture_btn" onclick="CloseBox('BoxB')"  value="<?php echo  lang('Fermer');?> " />
				
				
				  </div>
            </div>
          </div>	    
        </div>
		</div>
		
	<?php
		include $tpl ."footer.php";?>	
		
<script language="javascript" type="text/javascript">

 
	
function TerminerCmdss(){
		var test=true;	
/*	var files = $('#formReg:input[type=file]').get(0).files;

		if(files.length==0){
			jAlert("<?php echo $trad['msg']['FichierObligatoire'];?>","<?php echo $trad['titre']['Alert'];?>");
			test=false;
		  return false;			
		}*/
		alert(test);
		if(test==true){									
		$('#formReg').ajaxSubmit({
							target:'#resReg',
							url:'reglements.php?TerminerReg',
								method			:	'post',
							success: function(data) { 
							
							
							//alert(data);							
								//	Imprimer(data);									
						//		window.location='mapClient.php';
							  }
							});
		//patienter('resReg');
		}

	}
  
$(document).ready(function() {
	

$('#owl-example').owlCarousel({
        infinite: false     
    
});
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
//rechercher();
	//$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="../images/loading2.gif" /></center>').load('reglements.php?aff');
});
function rechercher(){
	
	if($("#CodeBye").val()=="") {
				jAlert("<h4><?php  echo lang('SaisirAchteur'); ?><h4>","<?php  echo lang('Operation'); ?>");  

		
	}
	else {$('#formRes').html('<center><br/><br/><?php echo lang('patienter');?> <br/><img src="layout/images/loading.gif" /></center>');
	$('#formRechF').ajaxSubmit({target:'#formRes',url:'reglements.php?recht&&type=<?php echo $type;?>'});
	}
		}
 function ListAcht(){
	
	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='reglements.php?ListAcht&&type=<?php echo $type;?>';
     $.get(url, null, function(data) {
      //$modal.find('.modal-body').html(data);
	   $modal.find('.modal-body').html(data);
    })
}
 function mod(id){
		$('#act').attr('value','mod');	
	
	ajaxindicatorstart('<?php echo lang('patienter');?>');
    var $modal = $('#BoxA');
		var url='reglements.php?mod&&ID='+id;
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
		var url="reglements.php?goAdd";
			 }
    else   if($('#act').val()=="mod" ){ 
		var url="reglements.php?goMod";
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

function	GetId(CodeBuy,NameBuy){

		$("#BoxA").modal('hide');
		$('#CodeBye').attr('value',CodeBuy);
		$('#NameBuy').attr('value',NameBuy);
		rechercher();		
}
</script>

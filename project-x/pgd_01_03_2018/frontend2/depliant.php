<?php

include("../php.fonctions.php");
require_once('../connexion.php');

if(!isset($_SESSION))
{
session_start();
}
//parcourir($_GET); 

$IdDepot=$_SESSION['IdDepot'];
include("lang.php");
if (isset($_GET['GoRechArti'])) {

	exit;
}
if (isset($_GET['FormRech'])) {

	?>
		<form id="formRechArti" method="post" name="formRech"> 
		<div id="ResRech"></div>
		<table cellpadding="10" cellspacing="10" align="center">
		<tr> <td><strong><?php echo $trad['label']['Ref'] ;?> :<strong></td></tr>
		<tr> <td> <input type="text" value="" class="" id="Ref" name="Ref" > </td></tr>
			<tr> <td align="center"><input type="button" value="<?php  echo $trad['button']['Valider'];?>"
			class="btn"  onclick="RechArticle()"/> 
			<input type="button" value="<?php  echo $trad['button']['Annuler'];?>"
			class="btn"  onclick="$('#boxRech').dialog('close')"/> 
			</td></tr>
		</table>
		</form>
	<?php
	exit;
}
	
if (isset($_GET['IdTypeVente'])) $_SESSION['IdTypeVente']= $_GET['IdTypeVente'];

if (isset($_GET['TypeVente'])){
?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>&nbsp;&nbsp;
<div Class="TitleHead" ><?php  echo $trad['label']['TypeVente'];?> <?php  //echo date("d/m/y h:i");?></div></div>
<div class="clear"></div>
<?php
$sql="select  * from TypeVente";
			 $params = array();	
	//parcourir($params);
	//echo "<br>".$sql;
	//parcourir($params);
			$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
			if( $stmt === false ) {
										$errors = sqlsrv_errors();
										echo "Erreur : ".$errors[0]['message'] . " <br/> ";
										return;
									}							
			
			//echo $sql;
				$nRes = sqlsrv_num_rows($stmt);	
				if($nRes!=0)
					  { 							
			?>
			<div style="  text-align:center;margin-top:20px">
<?php			
					 while($row=sqlsrv_fetch_array($stmt)){					
?>

  <div class="" style="display:inline-block;margin:0 20px;CURsor:pointer" id="cadreType<?php  echo $row['IdType'];?>" 
		  onclick="GetCatalogue('<?php  echo $row['IdType'];?>')">
			<div  class="titleCadre"> 
			 <img src="../<?php 	echo ( $row['UrlImg']);?>" width="225" height="226"/><br>
				<div class="titleCadre"><?php 	echo $row['Dsg_'.$_SESSION['lang']];?></div>
			</div>
		  </div>
<?php
		}					
			?></div>
			
<?php	 }else { ?>
<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
										<?php  echo $trad['msg']['AucunResultat'];?>
							</div>
<?php }		
	exit;
}	
if (isset($_GET['affArti'])){
	$_SESSION['IdGamme']=$_GET['Id'];	

$tabArtilce=array();
$k=0;
$DsgFamille="";$DsgSousFam="";$DsgGamme="";
//parcourir($_SESSION['lignesFam']);return;
foreach($_SESSION['lignesFam'] as $v){//marque
		if(is_array($v)){ 
			if($_SESSION['IdMarque']==$v["IdMarque"]){
					$DsgMarque=$v["DsgMarque"];//echo  $DsgFamille."<br>";return;			
					
								foreach($v as $f){	// sous famille										
									if(is_array($f)){
										if($_SESSION['IdFamille']==$f["IdSousFam"]){
												$DsgSousFam=$f["DsgSousFam"];								
												foreach($f as $g){//gammes						
														if(is_array($g)){
															if ($g['IdGamme']==$_GET['Id']){
															//	parcourir($g);return;	
																//		$DsgSousFam=$d["DsgSousFam"];
																		$DsgGamme=$g["DsgGamme"];				
																	foreach($g as $r){//article				
																		if(is_array($r)){
																			array_push($tabArtilce,$r);
																			//echo  $DsgGamme."<br>";	
																						}
																						?><?php
																	}
																	}	
																}
												}
										}
									}
								}
						//	}
					// }
					//}
														
									
			}
		}
}
	//	parcourir($tabArtilce);return;


?>
<style type="text/css">
	.table td, .table th {
    padding: 7px;
	}
	.qtyplus_y{
		font-size:3em;
		margin-top:4px;
		cursor:pointer;
		width:110px;
		height: 92px;
		padding-bottom: 45px;
		color:#fff;
		border:none;
	}
	.divPRix .nbr{
		color: #ecf0f1;
	}
	.divLeftArt {
     background: white; 
	}
	.anim1{
  		-webkit-animation-delay: 0.1s;
  			
  		-moz-animation-delay: 0.1s;
  		
  		-o-animation-delay: 0.1s;
  		
  		-ms-animation-delay: 0.1s;
  	
  		animation-delay: 0.1s;
	}
	.anim2{
  		-webkit-animation-delay: 0.3s;
  			
  		-moz-animation-delay: 0.3s;
  		
  		-o-animation-delay: 0.3s;
  		
  		-ms-animation-delay: 0.3s;
  	
  		animation-delay: 0.3s;
	}
	.anim3{
  		-webkit-animation-delay: 0.5s;
  			
  		-moz-animation-delay: 0.5s;
  		
  		-o-animation-delay: 0.5s;
  		
  		-ms-animation-delay: 0.5s;
  	
  		animation-delay: 0.5s;
	}
	.anim4{
  		-webkit-animation-delay: 0.7s;
  			
  		-moz-animation-delay: 0.7s;
  		
  		-o-animation-delay: 0.7s;
  		
  		-ms-animation-delay: 0.7s;
  	
  		animation-delay: 0.7s;
	}
	.anim5{
  		-webkit-animation-delay: 0.9s;
  			
  		-moz-animation-delay: 0.9s;
  		
  		-o-animation-delay: 0.9s;
  		
  		-ms-animation-delay: 0.9s;
  	
  		animation-delay: 0.9s;
	}
	.anim6{
  		-webkit-animation-delay: 1.1s;
  			
  		-moz-animation-delay: 1.1s;
  		
  		-o-animation-delay: 1.1s;
  		
  		-ms-animation-delay: 1.1s;
  	
  		animation-delay: 1.1s;
	}
	.anim7{
  		-webkit-animation-delay: 1.3s;
  			
  		-moz-animation-delay: 1.3s;
  		
  		-o-animation-delay: 1.3s;
  		
  		-ms-animation-delay: 1.3s;
  	
  		animation-delay: 1.3s;
	}
	.anim8{
  		-webkit-animation-delay: 1.5s;
  			
  		-moz-animation-delay: 1.5s;
  		
  		-o-animation-delay: 1.5s;
  		
  		-ms-animation-delay: 1.5s;
  	
  		animation-delay: 1.5s;
	}
	.anim9{
  		-webkit-animation-delay: 1.7s;
  			
  		-moz-animation-delay: 1.7s;
  		
  		-o-animation-delay: 1.7s;
  		
  		-ms-animation-delay: 1.7s;
  	
  		animation-delay: 1.7s;
	}
</style>
<div class="clear"></div>					<div id="resAdd"></div>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
					
							$key ="";
		
				
					$i=0;	foreach($tabArtilce as $r){//article												
													if(is_array($r)){
														$i+=1;
																	//	parcourir($r);//	return;
																//	echo $r['DsgGamme'];;
															?>
											<li><?php 
								$IdArticle=$r['IdArticle'];
								$dsgArticle=$r['DsgArticle'];
								$TVA=$r['TVA'];
							
								?>
								<?php 

$qteDispoEnBPcs=0;
$qteDispoEnBoite =0;
$colisagee = 0;
$query_select ="select a.idarticle,c.colisagee,a.designation DsgArticle,a.Reference RefArticle, g.IdGamme from articles a INNER JOIN colisages c ON a.IdArticle=c.idArticle INNER JOIN gammes g ON g.IdGamme=a.IdFamille INNER JOIN marques m ON m.IdMarque=g.IdMarque INNER JOIN sousfamilles sf on 
sf.idSousFamille=g.IdSousFamille INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
INNER JOIN detailMouvements dmo ON dmo.idArticle = a.idArticle inner join mouvements mo 
on dmo.idMouvement = mo.idMouvement where mo.idDepot= $_SESSION[IdDepot] and a.idarticle = $IdArticle ";
//echo $query_select;
$params_select_table = array();
$options_select_table =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_select_table=sqlsrv_query($conn,$query_select,$params_select_table,$options_select_table);
$ntRes_select_table = sqlsrv_num_rows($stmt_select_table);

while($row_s = sqlsrv_fetch_array($stmt_select_table, SQLSRV_FETCH_ASSOC)){

$sql="SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
					as QteEntree FROM detailMouvements dm
			INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
			INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=? AND m.type='entree' AND m.idDepot=?";
$params1= array($IdArticle,$_SESSION['IdDepot']) ;
$stmt1 = sqlsrv_query( $conn, $sql, $params1 );
//echo $sql."<br>";

sqlsrv_fetch($stmt1) ;
$qteEntreeGlobal = sqlsrv_get_field( $stmt1, 0);
//------------------------

//---select qteChargementGlobal--------------------------------//
$sql2 ="SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
				 as QteSortie FROM detailMouvements dm
				INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
				INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=? AND m.type='sortie' and EtatSotie!=3 and EtatSotie=1 AND m.idDepot=?";
//echo $sql2."<br>";
$params1= array($IdArticle,$_SESSION['IdDepot']) ;
$stmt2 = sqlsrv_query( $conn, $sql2, $params1 );	

$rowC = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
$qteChargementGlobal = $rowC['QteSortie'];

$qteDispo=$qteEntreeGlobal-$qteChargementGlobal;
//$qteDispo=number_format($qteDispo,0," "," ");	
$qteDispoEnBoite=$qteDispo/ $row_s['colisagee'];
$qteDispoEnBoite=floor($qteDispoEnBoite);
$qteDispoEnBPcs=$qteDispo % $row_s['colisagee'];
$qteDispoEnBPcs = floor($qteDispoEnBPcs);

}
$query_colisage = "select a.idarticle,c.colisagee,a.designation DsgArticle,a.Reference RefArticle
from articles a INNER JOIN colisages c ON a.IdArticle=c.idArticle 
 where  a.idarticle = $IdArticle";

 $params_select_colisage = array();
$options_select_colisage =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_select_colisage=
sqlsrv_query($conn,$query_colisage,$params_select_colisage,$options_select_colisage);
$ntRes_select_colisage = sqlsrv_num_rows($stmt_select_colisage);

while($row_c = sqlsrv_fetch_array($stmt_select_colisage, SQLSRV_FETCH_ASSOC)){
$colisagee = $row_c['colisagee'];

}
 ?>
									<DIV class="haut">
										<div class="divLeftArt" style="width:655px;"><!--658px;-->
					
										<form id="formAddArt<?php echo $i;?>" method="post" name="formAddArt"> 
											<TABLE  dir="<?php echo $_SESSION["dir"];?>"  border="0" width="100%" class="table" cellspacing="2" cellpadding="7">
												<tr class="animated fadeInUp anim1"><TD width="15%"><?php  echo $trad['label']['Ref'];?>:</td>
												<td align="<?php echo $_SESSION["align"];?>">	<?php  echo $r['Reference'];?>
													<input type="hidden" value="<?php  echo $r['Reference'];?>" name="Reference">
												<input type="hidden" value="<?php  echo $IdArticle; ?>" name="IdArticle">
												<input type="hidden" value="<?php  echo stripcslashes($dsgArticle); ?>" name="NomArt">
												<input type="hidden" value="<?php  echo stripcslashes($DsgGamme); ?>" name="Gamme">
												<input type="hidden" value="<?php  echo $TVA; ?>" name="TVA">
												<input type="hidden" value="<?php  echo $r['PV'];?>" name="PV">
												<?php if (isset($_GET['list'])){
													
													$key = array_search($_GET['idArticle'],array_column($_SESSION["lignesCat"], 'IdArticle'));
													$Qte=$_SESSION["lignesCat"][$key]["Qte"];
													
													?>
												
													<input type="hidden" value="List" name="List">
													<?php } else $Qte=1;?>
												</td></tr>
												<tr class="animated fadeInUp anim2"><td><?php  echo $trad['label']['Dsg'];?>:</td><td  colspan="2" align="<?php echo $_SESSION["align"];?>">	<?php  echo stripcslashes(ucfirst($dsgArticle));?></td></tr>
												<tr class="animated fadeInUp anim3"><td><?php  echo $trad['label']['Gamme'];?>:</td><td colspan="2" align="<?php echo $_SESSION["align"];?>" ><?php  echo stripcslashes(ucfirst($DsgGamme));?></td></tr>
												<tr class="animated fadeInUp anim4"><td><?php  echo $trad['label']['Famille'];?>:</td><td align="<?php echo $_SESSION["align"];?>"><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td>
													<td class="text-danger" style="font-weight:bold !important;font-size: 30px;overflow: visible;">
														Colisagee : <?php echo $colisagee; ?> 
													</td>
												</tr>
												<tr Class="chpinvisible animated fadeInUp anim5"><td><?php  echo $trad['label']['Famille'];?>:</td><td colspan="2" align="<?php echo $_SESSION["align"];?>"><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
												<tr class="animated fadeInUp anim6"><td><?php  echo $trad['label']['PV'];?>:</td><td align="<?php echo $_SESSION["align"];?>">
												<span class="prix">												
												<?php  echo $r['PV']; 
												echo $trad['label']['riyal'];
												echo " (".$trad['label']['PourColisage'].")";
												?>
												</span>												
												</td>
												<td class="text-center">
													<span class="text-danger" style="font-weight:bold !important;font-size: 30px;overflow: visible;">

													Stock
												    </span>
													
												</td>
											</tr>
												<tr class="animated fadeInUp anim7"><TD><?php  echo $trad['label']['unite'];?>:</td><td height="70" align="<?php echo $_SESSION["align"];?>">
												
												<!--input type="radio" IdLigne="<?php echo $i;?>"  Unite="Palette" class="palette" name="Colisage"  value="<?php echo $r['Palette'];?>" 
												data-labelauty="<?php //echo $trad['label']['Palette']." (".$r['Palette'].")";?>|<?php //  echo $trad['label']['Palette']." (".$r['Palette'].")";?>" aria-label="1"   class="chpinvisible" /-->
												
												<input type="radio" IdLigne="<?php echo $i;?>"  Unite="Box" name="Colisage" class="box"  value="<?php echo $r['Box'];?>"  
												data-labelauty="<?php  //echo $trad['label']['Box']." (".$r['Box'].")";?>|<?php //  echo $trad['label']['Box']." (".$r['Box'].")";?>" aria-label="2"  checked />
												
												<input type="radio" IdLigne="<?php echo $i;?>" Unite="Colisage"  class="colisage" name="Colisage"  value="<?php echo $r['Colisage'];?>" 
												data-labelauty="<?php  //echo $trad['label']['Colisage']." (".$r['Colisage'].")";?>|<?php  //echo $trad['label']['Colisage']." (".$r['Colisage'].")";?>" aria-label="3"   />
												<input type="hidden"	value="<?php echo $i;?>" class="index" />
												
												<input type="hidden"
													value="Box"
												class="UniteVente" name="UniteVente" />
												
												<input type="hidden"
													value="<?php echo $r['PV'];?>"
												Id="PrixVente<?php echo $i;?>"  />
												


												<input type="hidden"
													value="<?php echo $r['Palette'];?>"
												Id="NbrPalette<?php echo $i;?>" name="NbrPalette" />
											
												
												<input type="hidden"
													value="<?php echo $r['Box'];?>"
												Id="NbrBox<?php echo $i;?>" name="NbrBox" />
												
												<input type="hidden"
													value="<?php echo $r['Colisage'];?>"
												Id="NbrColisage<?php echo $i;?>" name="NbrColisage" />
												
												

												</td>
												<td>
													<div class="row" style="font-weight:bold !important;font-size: 30px;overflow: visible; margin-top: -25px;">

														<div class="col-6">
															<div><b class="text-danger" style="font-weight:bold !important;font-size: 30px;overflow: visible;">Boîtes</b></div>
															<div><b class="text-danger" style="font-weight:bold !important;font-size: 30px;overflow: visible;">
															<?php echo $qteDispoEnBoite; ?>
															</b></div>
															
														</div>
														<div class="col-6">
															<div><b class="text-danger" style="font-weight:bold !important;font-size: 30px;overflow: visible;">Pieces</b></div>
															<div><b class="text-danger" style="font-weight:bold !important;font-size: 30px;overflow: visible;">
															<?php echo $qteDispoEnBPcs; ?>
															</b></div>
														</div>
													</div>
													 
												</td>
												</tr>
												<tr class="animated fadeInUp anim8"><TD><?php  echo $trad['label']['Qte'];?>:</td><td 
													colspan="2"
													Valign="top" align="<?php echo $_SESSION["align"];?>"> 
												<input type="text" value="<?php echo $Qte;?>" name="Qte" onkeypress="return isEntier(event) "  onblur="CalculPrixArt('<?php echo $i;?>')"
												class="Qte ConvertDecimal nbr" 
												Style="text-align:<?php echo $_SESSION["align"];?>"
												size="8" id="Qte<?php echo $i;?>">
												<input type="button" class="btn-primary qtyplus_y"  style="padding-right:0" id="qtyplus" onclick="Plus('<?php echo $i;?>')"
												value="+" id="btnp">&nbsp;
												<!---  class="btn-primary qtyplus" class=" qtyplus" -->
												<input type="button" class="btn-primary qtyplus_y" style="padding-right:0" id="qtyminus" onclick="Moins('<?php echo $i;?>')" value="-"></td></tr>
												<TR class="animated fadeInUp anim9">	
												<td   Valign="middle" colspan="2" height="70">
												<div class="divPRix<?php echo $i;?> divPRix" style="background-color: #34495e;color: #ecf0f1;height: 92px;">												
												</div>
												</td>
												
												</tr>
											
											</table>
											</form> 
										</div>
										
										<div class="divRight" style="width:300px;height:420px;"><!-- 600px;-->
										<img src="../<?php  echo $r['UrlArticle'];?>" alt=""  width=" 300" height="410" class="animated fadeInLeft" />
<BR>
<TABLE border="0" width="100%">
	<tr class="animated fadeInUp">	
												<td   Valign="top" align="center" colspan="2" >
													<br>
												<input type="button" value="<?php  echo $trad['button']['Fermer'];?>"  
												id="BtnFermer<?php echo $i;?>"
												class="btn-danger btn-block animated fadeInLeft" onclick="AfficheGamme1('<?php echo $_SESSION['IdFamille'];?>','<?php echo $_GET['CurrentSlide'];?>')"/>&nbsp;&nbsp;
												<input type="button" value="<?php  echo $trad['button']['Valider'];?>" class="btn chpinvisible"  onclick="AjoutArticle('<?php echo $i;?>')"/>
												
												</td>
												</tr>
</table>												
										</div>
									</div>
															  </li>
															 
															<?php
																}
		 
														}
														
				
			?>
		</ul><?php		   
					//$_SESSION['lignesSousFam']=$groups;
				//parcourir($_SESSION['lignesFam']);return;

			// fin bdd plein
//}// fin if isset session


?>	
	
<script language="javascript" type="text/javascript">
 
$('input[type="radio"]').click(function(){
	var IdLigne;
    if ($(this).is(':checked'))
    {	
		$(".UniteVente").val($(this).attr("Unite"));
		IdLigne=$(this).attr("IdLigne")
    }

	CalculPrixArt(IdLigne);
  });
$(document).ready(function () {		
	$(":radio").labelauty();

	$(".index").each(function() {
		 	var IdLigne =$(this).val();
			CalculPrixArt(IdLigne);
		});

	
		
	  /* $('#qtyminus').click(function(e){
		var fieldId="Qte";
        // Stop acting like a button
        e.preventDefault();
        var currentVal = parseInt($('input[id='+fieldId+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
			if((currentVal - 1)>=1){
            $('input[id='+fieldId+']').val(currentVal - 1);
			}
        } else {
            // Otherwise put a 0 there
            $('input[id='+fieldId+']').val(1);
        }
    });*/
})


 // initialize bxSlider
  var slider = $('.bxslider').bxSlider({
			infiniteLoop: false,
			slideMargin: 50,
			hideControlOnEnd: true,
			touchEnabled: true,
			pager: false,
			pause: 3000,
			speed: 1000,
			controls:true
	});

		  var slide_count = slider.getSlideCount();
		  
		   $("#BtnFermer"+slide_count).css('display','inline');
		 
	//slider.reloadSlider();
  // touchSwipe for the win!
		 $('.bxslider').swipe({
			 excludedElements:"button, input, select, textarea, .noSwipe", // rend les champs en écriture
			swipeRight: function(event, direction, distance, duration, fingerCount) {
			
				slider.goToPrevSlide();
							},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {	
						
				slider.goToNextSlide();	
				//alert("you clicked me");
					
			},
			threshold: 1200
		});	
	
</script>
	<?php
exit;
}

if (isset($_GET['aff'])){ 
/*echo "DsgGamme<br>";
echo "liste des articles pour la gammes:";
return;*/
$tabArtilce=array();
$k=0;
$DsgFamille="";$DsgSousFam="";$DsgGamme="";
//parcourir($_SESSION['lignesFam']);return;
foreach($_SESSION['lignesFam'] as $v){//famille
	
		if(is_array($v)){ 
		//	parcourir($v);
		if($_SESSION['IdFamille']==$v["IdFamille"]) {
		$DsgFamille=$v["DsgFamille"];//echo  $DsgFamille."<br>";return;	
		
			foreach($v as $d){// sous famille
			
						
			if(is_array($d)){//	parcourir($d);return;
			//		echo $d["IdSousFam"]."<br>";
				if($_SESSION['IdSousFam']==$d["IdSousFam"]) {
					
						$DsgSousFam=$d["DsgSousFam"];
					
						foreach($d as $f){	// marque										
							if(is_array($f)){
						if($_SESSION['IdMarque']==$f["IdMarque"]) {
								//parcourir($f);return;							
								foreach($f as $g){//gammes									
								
									if(is_array($g)){
										if ($g['IdGamme']==$_GET['Id']){
										//	parcourir($g);return;	
													$DsgGamme=$g["DsgGamme"];
												//	$tabArtilce=$g;
												//echo $g["IdArticle"];
										
												?> <?php
											

						//	$tabArtilce=$g;
							//array_push($tabArtilce,$g);
						
												foreach($g as $r){//article												
													if(is_array($r)){
														//print_r($r);
														//echo $r["DsgArticle"];
														array_push($tabArtilce,$r);
														//echo  $DsgGamme."<br>";	
																	}?><?php
												}
												}	
											}
									
							
								}
							}
						}
					}
					}
			}
			}
		}
		}
}
		
	//	parcourir($tabArtilce);return;
			/*foreach($g as $r){//article												
					if(is_array($r)){

														//print_r($r);
													//	echo  $DsgGamme."<br>";	
																	}?><?php
												}
													*/	?>
		<DIV class="haut">
	
			<TABLE   border="0" width="100%" class="table" cellpadding="6" >	
			<tr class="animated fadeInUp"><TD><?php  echo $trad['label']['Gamme'];?>:</td><td><?php echo  stripcslashes(ucfirst($DsgGamme));?></td></tr>
			<tr class="animated fadeInUp"><TD><?php  echo $trad['label']['SousFamille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
			<tr class="animated fadeInUp"><TD> <?php  echo $trad['label']['Famille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
				</table>
		
					<DIV class="entete">
						<div class="divArticle" Style="width:663px;" align="center"><?php  echo $trad['label']['Article'];?> </div>
						<div class="divPV"  Style="width:250px;" align="center"><?php  echo $trad['label']['PV'];?> </div>
						<div class="divPV" Style="width:264px;" align="center"><?php  echo $trad['label']['Action'];?> </div>
					</div>
						<div class="clear"></div>
				<div class="DivListArt">				
				<?php 
				$key="";$c="";

					foreach($tabArtilce as $r){//article												
													if(is_array($r)){
							?>		
								<div class="ligne " >
									<div class=" <?php echo $c;?> divArticle " Style="width:663px;" align="center"><?php  echo stripcslashes(ucfirst($r['DsgArticle']));?>					
									</div>
									<div class="divPV" Style="width:255px;"> <?php  echo $r['PV'];?> </div> 
									<div class="divCmd" Style="width:284px;TEXT-align:center;background:#fff;" ><input type="button" value="Commander" onclick="getArticle('<?php  echo $r['IdArticle'];?>')"
									class="btnCmdArt"></div>
								</div>
								<div class="clear"></div>
				<?php 	}}?>									
			</div>
		
	
	
			<!--img src="../<?php // echo $v['url'];?>" alt=""  width=" 638" height="662" / -->					

		<div class="clear"></div>
		
		</div>
		<div Style="margin:0 auto;text-align:center;">
				<input type="button" value="<?php  echo $trad['button']['Fermer'];?>"  class="btn" onclick="Fermer() "  />
				</div>
	<?php 
													
										

							
?>
	

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	/*var urlGamme=$("#GammeImg1").attr("src");
				$('#box').html('<input type="button" value="Fermer"  class="btn" onclick="Fermer()"/><center><img src="'+urlGamme+'" alt=""  width=" 100%" height="100%" />	</center>').dialog('open');*/
})
</script>
<?php		
exit;
}

if (isset($_GET['affGamme'])){
$_SESSION['IdFamille']=$_GET['Id'];	
$CurrentSlide=0;
if(isset( $_GET['CurrentSlide'])) $CurrentSlide=$_GET['CurrentSlide'];		
?>
<DIV style="   align-items:center;" class="headVente">
	<div style="float:left; width:80%">
		<a href="index.php">	
		<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>
			<div style="padding-top:12px" 	>
				<span  class="TitleHead" onclick="AfficheMarque()"> <?php  echo $trad['label']['Marque'];?></span> &nbsp;>&nbsp;
					<span Class="TitleHead" onclick="rechercherFam(<?php echo $_SESSION['IdMarque'];?>)" >
					<?php  echo $trad['depliant']['liste_des_fam']; ?></span>&nbsp;>&nbsp;
					<?php  echo $trad['titre']['Gamme'];?>
		</div>
	</div>
		<div class="ZoneRech chpinvisible" onclick="AffFormRech()" >  <input type="button"  
				 onclick="AffFormRech()" name="RechRef" value="Rechercher..." class="btnRech1"></div>
</div>
<?php //echo $_SESSION['IdFamille']."___".$_SESSION['IdSousFam']."___".$_SESSION['IdMarque'] ; ?>
<div class="clear"></div>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							//	parcourir($_SESSION['lignesFam']);return;
							$key ="";
				if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {//famille
				//echo $_SESSION['IdFamille'];return;
				
					$i=1;
					foreach($_SESSION['lignesFam'] as $v){// marque
							if(is_array($v)){ 
								if($_SESSION['IdMarque']==$v["IdMarque"]){								
																				
													foreach($v as $f){	//Sous Famille										
														if(is_array($f)){
															if($_GET['Id']==$f["IdSousFam"]){
													//echo count($f);return;
																foreach($f as $r){//gamme		
																		if(is_array($r)){
																				//	parcourir($r);//	return;
																//	echo $r['DsgGamme'];;
																			?>
																				<li>
																	<img src="../<?php  echo $r['UrlGamme'];?>" alt="<?php  echo $r['IdGamme'];?>"  width=" 100%" height="450" class="animated bounce"
																	style="position : relative ;  "/>	
																	<input type="button" class="DetailGamme animated fadeInLeft" value="<?php  echo $trad['button']['VoirArticles'];?>  "  
																	
																			 onclick="AfficheDetailGamme('<?php echo $r['IdGamme'];?>')" />
																			  </li>
																			 
																			<?php 
																		}
				 
																}
															}
														}
													}
													//end if sous fam}
												
											
								
								
							}
						}
					}
							?>
						
											
						
											<?php
														
				}
			?>
		</ul><?php		   
					//$_SESSION['lignesSousFam']=$groups;
				//parcourir($_SESSION['lignesFam']);return;

			// fin bdd plein
//}// fin if isset session


?>	
	
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  
$(document).ready(function () {

	var i = 0;
	$('div.cadreSouf').each(function() {
    /*var rand = back[Math.floor(Math.random() * back.length)];
    console.log(rand);
    $('.cadreSouf').css('background',rand);*/
//	var item =  jQuery.rand(back);
        $(this).css("background-color", TabColor[i]);
		i = (i + 1) ;
    })
})
	var slider = $('.bxslider').bxSlider({
			infiniteLoop: false,
			slideMargin: 50,
			hideControlOnEnd: true,
			touchEnabled: true,
			pager: false,
			pause: 3000,
			speed: 1000,
			controls:true
	});
	slider.goToSlide(<?php echo $CurrentSlide;?>);
	//slider.reloadSlider();
  // touchSwipe for the win!
		 $('.bxslider').swipe({
			 excludedElements:"button, input, select, textarea, .noSwipe", // rend les champs en écriture
			swipeRight: function(event, direction, distance, duration, fingerCount) {
			
				slider.goToPrevSlide();
							},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {	
						
				slider.goToNextSlide();	
				//alert("you clicked me");
					
			},
			threshold: 1200
		});
		
</script>
	<?php
exit;
}


if (isset($_GET['affMarque'])){
	//	parcourir($_SESSION['lignesCat']);return;
	?>
	<DIV style="    align-items:center;" class="headVente">
	<div style="float:left; width:80%">
		<a href="index.php">	
		<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>
			<div style="padding-top:12px" 	>
					&nbsp; >&nbsp; 
				<span style="" class="TitleHead" onclick="rechercherMarque()"> <?php  echo $trad['label']['Marque'];?></span> &nbsp; 
		</div>
	</div>
	<div class="ZoneRech  chpinvisible" onclick="AffFormRech()" >  <input type="button"  
				 onclick="AffFormRech()" name="RechRef" value="Rechercher..." class="btnRech1"></div>
</div>
<div class="clear"></div>
	<?php
		//$_SESSION['IdSousFam']=$_GET['Id'];
unset($_SESSION['IdFamille']);
unset($_SESSION['IdSousFam']);
unset($_SESSION['IdMarque']);
unset($_SESSION['IdGamme']);
$timestamp_debut = microtime(true);
//unset($_SESSION['lignesFam']);

if(!isset($_SESSION['lignesFam'])){
	
	$_GET["IdTypeVente"]=$_SESSION['IdTypeVente'];
	//selectionner les familles des chargements valide
				
	$sql="select 
						g.Designation as dsgGamme ,
						a.Designation DsgArticle,a.IdArticle	,
						a.reference Reference ,CB codeABarre,colisagee Colisage,
						co.palette Palette,co.box Box,
						a.unite,a.TVA,
						me.url UrlArticle,
						fa.UrlFamille UrlFamille,
						fa.Designation DsgFamille,
						sf.Designation as dsgSousFamille,
						 sf.UrlSousFamille UrlSousFamille,
						m.Designation DsgMarque,
						g.Reference RefG,
						g.idGamme as IdGamme ,
						a.IdArticle	,
						mg.url UrlGamme,
						sf.idSousFamille IdSousFam ,
						t.pvHT PV,
						f.idFiche,
						fa.idFamille IdFamille,fa.codeFamille CodeFamille	,
		m.Chemin UrlMarque,
		m.IdMarque
		from articles a
			inner join media me on me.idArticle=a.IdArticle
			inner join colisages co on co.idArticle=a.idArticle
			INNER JOIN gammes g ON g.IdGamme=a.IdFamille
			INNER JOIN marques m ON m.IdMarque=g.IdMarque
			inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
			INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
			inner join mediaGammes mg on mg.idGamme=g.IdGamme
			INNER JOIN dbo.tarifs t ON t.idArticle=a.IdArticle 
			INNER JOIN dbo.ficheTarifs f ON f.idFiche=t.idFiche			
			INNER JOIN detailMouvements dmo ON dmo.idArticle = a.idArticle 
			INNER join mouvements mo on dmo.idMouvement = mo.idMouvement 
		WHERE 
		f.TypeVente=?
		and ( m.idMarque=17 or  m.idMarque=18 or  m.idMarque=1017 ) AND f.etat=1		
		AND fa.idFamille=2025
		group by 
		a.IdArticle	,a.reference,CB ,colisagee 
		,co.palette ,co.box ,a.unite,a.TVA,
		me.url ,
						fa.Designation ,UrlFamille,
						sf.Designation ,UrlSousFamille,
						m.Designation,
						g.Designation  ,
						a.Designation,
						g.Reference ,
						g.idGamme  ,
						a.IdArticle	,
						mg.url,
						m.Chemin ,		m.IdMarque,
						sf.idSousFamille  ,
						t.pvHT,
						f.idFiche,
						fa.idFamille ,fa.codeFamille 	
		 order BY   g.idGamme desc,a.IdArticle Desc ";
		

 $params = array($_GET["IdTypeVente"]);	

/*	ECHO $_SESSION["IdVendeur"]."<br>"; return;
			parcourir($params);*/
		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
								}	
	//	echo $sql;
			$nRes = sqlsrv_num_rows($stmt);	
	
//echo $nRes;return;
				if($nRes!=0)
				{ 	
							$groups = array(); 
									$i=0;
							
				 while($row=sqlsrv_fetch_array($stmt)){							 
								/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
												 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
												 
												 
										$keyMarque = $row['IdMarque'];
										
										if (!isset($groups[$keyMarque])) {
												
															$groups[$keyMarque] = array();
															$groups[$keyMarque]['IdMarque']=$row['IdMarque'];
															$groups[$keyMarque]['DsgMarque']=$row['DsgMarque'];
															$groups[$keyMarque]['UrlMarque']=$row['UrlMarque'];
															
										}
										$key = $row['IdFamille'];

											$_SESSION['IdFiche']=$row['idFiche'];
										
									//	else {
												$keySousFam = $row['IdSousFam'];
												if (!isset($groups[$keyMarque][$keySousFam])) {
											
														$groups[$keyMarque][$keySousFam] = array();
														$groups[$keyMarque][$keySousFam]['IdSousFam']=$row['IdSousFam'];
														$groups[$keyMarque][$keySousFam]['DsgSousFam']=$row['dsgSousFamille'];
														$groups[$keyMarque][$keySousFam]['DsgSousFam']=$row['dsgSousFamille'];
														$groups[$keyMarque][$keySousFam]['UrlSousFamille']=$row['UrlSousFamille'];
														
													} 
												//	ELSE {
												
														
												$keyGamme= $row['IdGamme'];
													if (!isset($groups[$keyMarque][$keySousFam][$keyGamme])) {
												
															$groups[$keyMarque][$keySousFam][$keyGamme] = array();
															$groups[$keyMarque][$keySousFam][$keyGamme]['IdGamme']=$row['IdGamme'];
															$groups[$keyMarque][$keySousFam][$keyGamme]['DsgGamme']=$row['dsgGamme'];
															$groups[$keyMarque][$keySousFam][$keyGamme]['UrlGamme']=$row['UrlGamme'];
															
															$groups[$keyMarque][$keySousFam][$keyGamme]['DsgFamille']=$row['DsgFamille'];
															$groups[$keyMarque][$keySousFam][$keyGamme]['DsgSousFam']=$row['dsgSousFamille'];
															$groups[$keyMarque][$keySousFam][$keyGamme]['DsgMarque']=$row['DsgMarque'];													
														} 
																	
																				
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['IdArticle'] = $row['IdArticle'];
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['DsgArticle'] = $row['DsgArticle'];									
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['PV'] =$row['PV'];	
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['Reference'] =$row['Reference'];	
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['Colisage'] =$row['Colisage'];
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['Palette'] =$row['Palette'];
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['Box'] =$row['Box'];													
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['TVA'] =$row['TVA'];
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['Unite'] =$row['unite'];
													$groups[$keyMarque][$keySousFam][$keyGamme][$i]['UniteVente'] ="";
													//}
											$i=$i+1;		
										//}
										}
				
					$_SESSION['lignesFam']=$groups;


			 }
			 else {
				 ?>
							<div class="resAffCat" >
												<br><br><br>
											<?php echo $trad['cmdVdr']['AucunArticle'];return;?>
					</div><?php
			 }
			 // fin bdd plein
}// fin if isset session
?>	

		<?php 
		$key ="";
if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {?>
<ul class="bxslider" style="margin:0;padding:0;margin-top:30px">
<?php 
$k=0;
	$i=1;
//	parcourir($_SESSION['lignesFam']);return;
	foreach($_SESSION['lignesFam'] as $u=>$v){	
		//echo "--------<li>".$k."</li>";
		// recherche pour ne pas dubliquer la couleur du cadre
		
		if( $i==1) echo " <li><div style='text-align:center'>" ;	
		?>
		  <div class="cadreIndex hvr-grow animated  bounce"   style="width:285px;" onclick="rechercherFam('<?php echo $v['IdMarque'];?>')">
			<div  class="childIndex" style="width:285px; max-width:285px;" >
					<img src="../<?php echo $v['UrlMarque'];?>"   width="285" height="285"/>
					<div  style="padding:0;height:60px" class="titleCadre"><?php 	echo mb_ucfirst($v['DsgMarque']);?></div>
			<?php //	echo mb_ucfirst($v['DsgFamille']);?></div>
		  </div>
		  
		<?php
		if($i==4) {?> <div class="clear"></div><?php }
			//condition pour afficher 4 familles par page
			if ($i == 4) {  echo " </div></li>" ; $i=1;}
			else {				$i+=1;}
	 //  echo $i;
	}

?>
</ul>
	<?php	}?>
	
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  
$(document).ready(function () {

	var i = 0;
	$('div.cadreMarque').each(function() {
    /*var rand = back[Math.floor(Math.random() * back.length)];
    console.log(rand);
    $('.cadreSouf').css('background',rand);*/
//	var item =  jQuery.rand(back);
        $(this).css("background-color", TabColor[i]);
		i = (i + 1) ;
    })
})
	var slider = $('.bxslider').bxSlider({
			infiniteLoop: false,
			slideMargin: 50,
			hideControlOnEnd: true,
			touchEnabled: true,
			pager: false,
			pause: 3000,
			speed: 1000,
			controls:true
	});

  // touchSwipe for the win!
		 $('.bxslider').swipe({
			 excludedElements:"button, input, select, textarea, .noSwipe", // rend les champs en écriture
			swipeRight: function(event, direction, distance, duration, fingerCount) {
			
				slider.goToPrevSlide();
							},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {	
						
				slider.goToNextSlide();	
				//alert("you clicked me");
					
			},
			threshold: 1200
		});
	
</script>
	<?php
exit;
}
/*
if (isset($_GET['affSousFam'])){


	$_SESSION['IdFamille']=$_GET['Id'];
?>	
<DIV style="  display:flex;  align-items:center;"  class="headVente">				

<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
<span  class="TitleHead" onclick="AfficheMarque()"> <?php  echo $trad['label']['Marque'];?></span> &nbsp;>&nbsp;
<span Class="TitleHead"  onclick="rechercherFam(<?php echo $_SESSION['IdMarque'];?>)" ><?php  echo $trad['depliant']['liste_des_fam'];?></span>&nbsp;>&nbsp;
<span Class="TitleHead"  ><?php  echo $trad['label']['SousFamille'];?></span>

 </div>
<div class="clear"></div>
<?php //echo $_SESSION['IdFamille']; ?>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							$key ="";
				if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {
					?>
					<?php	
					$i=1;
				///parcourir($_SESSION['lignesFam']);return;
						foreach($_SESSION['lignesFam'] as $t){	//	parcourir marque
							if(is_array($t)){	
								if($t['IdMarque']==$_SESSION['IdMarque']){							
										foreach($t as $v){		//parcourir famille					
											if(is_array($v)){
												if($v['IdFamille']==$_GET['Id']){										
											//	echo "mmmmmmm".$v['IdFamille']."mmmmmmm".$_GET['Id'];return;
										
													foreach($v as $r){		 //parcourir sous famille										
														if(is_array($r)){
															if( $i==1) echo " <li><div style='text-align:left'>" ;												
													
													?>
													  <div class="cadreIndex hvr-grow"  style="width:250px;max-width: 250px;"  onclick="AfficheGamme1('<?php echo $r['IdSousFam'];?>')">
													  <div  class="childIndex" style="width:250px; max-width: 250px;" > 
													  
													<img src="../<?php echo $r['UrlSousFamille'];?>"  width="250" height="250"/>
													<div  style="padding:0;" class="titleCadre"><?php 	echo mb_ucfirst($r['DsgSousFam']);?></div>
													</div>
													  </div>
													  
													  
													  
													<?php
													if($i==4) {?> <div class="clear"></div><?php }
														//condition pour afficher 4 familles par page
														if ($i == 9) {  echo " </div></li>" ; $i=1;}
														else {				$i+=1;}
																							
															}
														}
												}
											}// fin boucle famille
													}
										}// fin boucle marque
							}
						}
							?>
						
											<div class="clear"></div>
						
											<?php
														
				}
			?>
		</ul><?php		   
					//$_SESSION['lignesSousFam']=$groups;
				//parcourir($_SESSION['lignesFam']);return;

			// fin bdd plein
//}// fin if isset session


?>	
	
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  
$(document).ready(function () {


})
	var slider = $('.bxslider').bxSlider({
			infiniteLoop: false,
			slideMargin: 50,
			hideControlOnEnd: true,
			touchEnabled: true,
			pager: false,
			pause: 3000,
			speed: 1000,
			controls:true
	});

  // touchSwipe for the win!
		 $('.bxslider').swipe({
			 excludedElements:"button, input, select, textarea, .noSwipe", // rend les champs en écriture
			swipeRight: function(event, direction, distance, duration, fingerCount) {
			
				slider.goToPrevSlide();
							},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {	
						
				slider.goToNextSlide();	
				
					
			},
			threshold: 1200
		});
	
</script>
	<?php
exit;
}
*/
if (isset($_GET['affFam'])){
	$_SESSION['IdMarque']=$_GET['Id'];
	
?>

<DIV style="   align-items:center;" class="headVente">
	<div style="float:left; width:80%">
		<a href="index.php">	
		<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>
			<div style="padding-top:12px" 	>
			&nbsp;	<span  class="TitleHead" onclick="AfficheMarque()"> <?php  echo $trad['label']['Marque'];?></span> &nbsp;>&nbsp;
				<span Class="TitleHead" ><?php  echo $trad['depliant']['liste_des_fam'];?> <?php  //echo date("d/m/y h:i");?></span>
		</div>
	</div>
		<div class="ZoneRech chpinvisible" onclick="AffFormRech()" >  <input type="button"  
				 onclick="AffFormRech()" name="RechRef" value="Rechercher..." class="btnRech1"></div>
</div>


<?php
if((!isset($_SESSION['lignesFam']) )  || (count($_SESSION['lignesFam'])==0)){
?>
<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
									<?php  echo $trad['msg']['AucunResultat'];?>
							</div>
<?php }
else { ?>
<DIV id="FormRes" style="">
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							$key ="";
				if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['lignesFam'] as $v){	//// parcourir marque				
							
									if(is_array($v)){
								//		echo "mmmmmmmmmmm".$_GET['Id'];parcourir($v);return;
											if($v['IdMarque']==$_GET['Id']){
												//	parcourir($v);return;
											foreach($v as $r){// parcourir sous famille qui sst considerer comme famille		
												
												if(is_array($r)){												
													if( $i==1) echo " <li><div style='text-align:left'>" ;											
													?>
													  <div class="cadreIndex hvr-grow animated fadeInRight"  style="width:285px;max-width: 285px;"  onclick="AfficheGamme1('<?php echo $r['IdSousFam'];?>')">
													  <div  class="childIndex" style="width:285px; max-width: 285px;" > 
													  
													<img src="../<?php echo $r['UrlSousFamille'];?>"  width="285" height="160"
													style="" />
													<div  style="padding:0;height:60px;	font-size:18px;" class="titleCadre"><?php 	echo mb_ucfirst($r['DsgSousFam']);?></div>
													</div>
													  </div>
													  
													  
													  
													<?php
													if($i==3) {?> <div class="clear"></div><?php }
														//condition pour afficher 4 familles par page
														if ($i == 6) {  echo " </div></li>" ; $i=1;}
														else {				$i+=1;}
												
	 
													}
												}
									}
									}
							}
							?>
						
											<div class="clear"></div>
						
											<?php
														
				}
			?>
		</ul>	
</div>	
<?php } ?>
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  
$(document).ready(function () {

	var i = 0;
/*
	$('div.cadre').each(function() {
	 $(this).css("background-image", "url(../images/transparent.png)");
		   $(this).css("background-color", TabColor[i]);
			i = (i + 1) ;
    })*/
})
	var slider = $('.bxslider').bxSlider({
			infiniteLoop: false,
			slideMargin: 50,
			hideControlOnEnd: true,
			touchEnabled: true,
			pager: false,
			pause: 3000,
			speed: 1000,
			controls:true
	});

  // touchSwipe for the win!
		 $('.bxslider').swipe({
			 excludedElements:"button, input, select, textarea, .noSwipe", // rend les champs en écriture
			swipeRight: function(event, direction, distance, duration, fingerCount) {
			
				slider.goToPrevSlide();
                //alert("you clicked me");
							},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {	
						
				slider.goToNextSlide();	
				//alert("you clicked me");
					
			},
			threshold: 1200
		});
	
</script>
	<?php
	
	// timestamp en millisecondes de la fin du script
		/*$timestamp_fin = microtime(true);		 
		// différence en millisecondes entre le début et la fin
		$difference_ms = $timestamp_fin - $timestamp_debut; 
		// affichage du résultat
		echo 'nombre de ligne du table '.$nRes.'Exécution du script : ' . $difference_ms . ' secondes.';
		//echo '<!-- Exécution du script : ' . $difference_ms . ' secondes. -->';
 */
 
exit;
}

if (isset($_GET['ChoixTypeReg'])){
?>
<br><br><br>
<form id="formReg" action="" method="post" name="formReg" >
<div id="resReg"></div>
			<TABLE   border="0" width="80%" class="table" cellpadding="5"  align="center" >
				<tr class="animated fadeInUp"><TD align="right"><?php  echo $trad['label']['Montant'];?> :</td><td>	
					
					<div class="TotalReg "> <span class="nbr">
					<?php echo number_format(Total(), 2, '.', ' '); ?></span></div>					
					
			</td>
			<td><?php  echo $trad['label']['Observation'];?>:</td></tr>
			<tr class="animated fadeInUp"><TD align="right"><?php  echo $trad['label']['TypeReg'];?> :</td>
			<td>	
					<select style="display:block;width:300px"    tabindex="2" g="select" name="TypeReg" id="TypeReg">
						<option value="Espece" selected><?php  echo $trad['label']['Espece'];?></option>
					<option value="Cheque"  ><?php  echo $trad['label']['Cheque'];?></option> 			
					<option value="Virement"><?php  echo $trad['label']['Virement'];?></option>
					</select>
			</td>
				<td>
				<textarea cols="15" rows="2" style="font-size:28px" name="Observation"></textarea>
				</td>
			</tr>
				<tr class="animated fadeInUp" >
				<TD  align="right" colspan="3">
					<DIV class="bq">
					<table  cellpadding="0"  cellspacing="0" border="0" width="80%" align="center">
						<tr class="animated fadeInUp">
								<td align="right">
								<?php  echo $trad['label']['Banque'];?> :</td>
								<td>	
								<select style="display:block;width:350px" tabindex="2" g="select" name="Bq" id="TYPE2_REG">
									<option value="bp" selected><?php  echo $trad['label']['Populaire'];?></option>
								<option value="Cheque"><?php  echo $trad['label']['BMCI'];?></option> 			
								<option value="Virement"><?php  echo $trad['label']['SOGE'];?></option>
								</select>
								</td>
						</tr class="animated fadeInUp">
							<tr>
								<td align="right">
								<?php  echo $trad['label']['Numero'];?> :</td>
								<td>	
								<input type="text" value=" " class="" id="" size="25" name="Num">
								</td>
						</tr>
					</table>
					</div>
					</td>
				</tr>
				<tr class="animated fadeInUp" >	
				<td   Valign="top" align="center" colspan="3"><br><br>
				<input type="button" value="<?php  echo $trad['button']['Valider'];?>" class="btn"  onclick="TerminerCmd()"/>&nbsp;&nbsp;
				<input type="button" value="<?php  echo $trad['button']['Annuler'];?>"  class="btn" onclick="Fermer()"/>
				
				
				</td>
				</tr>
</table>
</form>
	<script language="javascript" type="text/javascript">	
		$('#TypeReg').on('change', function() {
		
		  if(this.value=="Espece")
		  {
			  $('.bq').hide(1000);
		  }
		  else {
			  $('.bq').show(1000);
		  }
		});
		
		function TerminerCmd(){
		var test=true;
		var TypeReg = $('#TypeReg').find(":selected").val();
		
		if(TypeReg!="Espece"){
		    $('#formReg').validate({
                                              
                                   rules: {
                                               
                                                'Num': "required"
                                           }   
										   
										    });
		var test=$('#formReg').valid();
		}
		if(test==true){									
		$('#formReg').ajaxSubmit({
							target:'#resReg',
							url:'depliant.php?TerminerCmd',
								method			:	'post'
							});
		patienter('resReg');
		}

	}
	</script>	
<?php
	exit;
}

if (isset($_GET['VerifSession'])){
	   if( (!isset($_SESSION['lignesCat'])) || (count($_SESSION['lignesCat'])==0))  
		   echo "0";
	   else echo "1";
	   exit;				
}
if (isset($_GET['FermerFenetre'])){
		/*$soap = new SoapClient('http://'.$ip.'/WebServicePos/Service.asmx?wsdl', array('exceptions' => true));
		$result = $soap->ArretProcessus_POSC();
	exit;*/
}
if (isset($_GET['ConsultCmd'])){
	//parcourir($_SESSION['lignesCat']);return;
?>	
<input type="button" value=""  class="close2" onclick="Fermer()" Style="float:right;"/>
<div class="clear"></div>
<?php
if(isset($_SESSION['lignesCat']) && count($_SESSION['lignesCat']) != 0){		
			$i=0;
			?>
	<form id="formCmd" method="post" name="formCmd"> 		
	<div id="res"></div>
	<DIV Class="title"><?php echo $trad['titre']['ListeArtCmd'];?></div>
	

	<DIV class="ListeCmd">
	<div class="enteteL" >
        <div  class="divArticleL"  ><?php echo $trad['label']['Article'];?> </div>
		<div  class="divArticleL" ><?php echo $trad['label']['Gamme'];?>  </div>	
		<div  class="divColisageL" ><?php echo $trad['label']['unite'];?>  </div>				   
        <div class="divQteL" > <?php echo $trad['label']['Qte'];?>  </div>			
		<div class="divPVL"> <?php echo $trad['label']['PVHT'];?><br/>(<?php echo $trad['label']['riyal'];?> )  </div>
		<div class="divTTC"> <?php echo $trad['label']['ValTTC'];?>  (<?php echo $trad['label']['riyal'];?> ) </div>
	</div>
  	<div style="height:350px;overflow:scroll;" ><!--height:585px;-->
			<?php
			$k=0;$Total=0;
			//parcourir($_SESSION['lignesCat']);return;
			foreach($_SESSION['lignesCat'] as $ligne=> $row){
				$k++;
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			$Total=0;
				?>	
				<div class=" <?php echo $c;?>" onclick="getArticle('<?php  echo $row['IdArticle'];?>','list')">
						<div class="divArticleL" align="center"><?php  echo $row['NomArt'];?></div>
						<div class="divArticleL"   align="center"><?php  echo $row['Gamme'];?></div>
							<div class="divColisageL" > <?php 
							$nbrColisage="";
			if($row['UniteVente']=='Box') { // si il a choisi la vente par colisage
				echo $trad['label']['Box'];
				$nbrColisage=$row['NbrColisage']." X ";
			
			}
			if($row['UniteVente']=='Palette') echo $trad['label']['Palette'];
			if($row['UniteVente']=='Colisage'){  // si il a choisi la vente par piece
				echo $trad['label']['Piece'];
				
			}
			
							?> </div> 
						<div class="divQteL" >  <span class="nbr"><?php  echo $row['Qte'];?> </span></div>
						<div class="divPVL" >  <span class="nbr"><?php echo $nbrColisage.number_format($row['PriceUnite'], 2, '.', ' '); ?> </span> </div> 
						 						
						<div class="divTTC"  > <span class="nbr"><?php 
						if($nbrColisage=="")	
						$Total=str_replace(" ","",$row['Qte'])*str_replace(" ","",$row['PriceUnite']);
					else $Total=str_replace(" ","",$row['Qte'])*str_replace(" ","",$row['PriceUnite'])*$row['NbrColisage'];
										//$Total=$row["Qte"]*$row["PriceUnite"];
									
										echo number_format($Total, 2, '.', ' ');?> </span>
							</div> 
						
												
								
				</div>
			<DIV Class="clear"></div>
			
	
			<?php
		}	
	?>
	</div>


</div>
<DIV class="bottomVente chpinvisible">
<div class=" divRight" Style="width:500px">
			<div class="cmd"><?php  echo $trad['label']['TotalGlobal'];?>:
			<input type="text" value=" <?php  echo    number_format(Total(), 2, '.', ' ');  ?> " class="global"  disabled id="txtTotal" size="10"  name="TotalCmdTTC">
			<?php echo $trad['label']['riyal'];?> </div>
		</div>
		<div class="divLeft" Style="width:450px">
			<input type="button" value="<?php  echo $trad['button']['ValiderCmd'];?>" class="btnCmd" onclick="ValideCmdEtape1()">
		</div></div>
	</form>
<?php
			
	}	

exit();

}
if (isset($_GET['goAddArti'])){	

//parcourir($_POST);return;
$Tva=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"] * $_POST["TVA"]) /100;	
	if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {

		$t=0;	
		$Total=0;
			  foreach($_SESSION['lignesCat'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
				$Total+=$contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"];
					if(($contenu["IdArticle"]==$_POST["IdArticle"]))
					{	
						//echo $_POST["UniteVente"];return;
								$IndexLigne=$t;
								$ligneArray["IdArticle"]=$_POST["IdArticle"];
								$ligneArray["NomArt"]=$_POST["NomArt"];
								$ligneArray["Qte"]=$_POST["Qte"];
								$ligneArray["PV"]=$_POST["PV"];
								$ligneArray["Gamme"]=$_POST["Gamme"];
								$ligneArray["UniteVente"]=$_POST["UniteVente"];
								$ligneArray["NbrBox"]=$_POST["NbrBox"];
								$ligneArray["NbrPalette"]=$_POST["NbrPalette"];
								$ligneArray["NbrColisage"]=$_POST["NbrColisage"];
								$ligneArray["Colisage"]=$_POST["Colisage"];
								$ligneArray["Reference"]=$_POST["Reference"];
								if($_POST["UniteVente"]=="Box")
								{
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"])*tofloat($_POST["NbrBox"]);
											//	echo tofloat($_POST["NbrBox"]);return;
								}else if ($_POST["UniteVente"]=="Palette"){
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"])*$_POST["NbrPalette"]*tofloat($_POST["NbrBox"]);
									
								}else if($_POST["UniteVente"]=="Colisage"){
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"]);
								}
					
								//$ligneArray["TVA"]=$_POST["TVA"];
								//$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
								//$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
								$_SESSION['lignesCat'][$IndexLigne]= $ligneArray;
								
						$t=0;break;				
					}
					else {					
						$t+=1;						
					}
			  }
			  // si l'article avec mm qte n'existe pas on l'ajoute
			  if($t!=0){
				 
			  			$IndexLigne=count($_SESSION['lignesCat']);							
						// si la qte et tarif vide en l'ajoute pas
						
							$ligneArray["IdLigne"]=$IndexLigne;
							$ligneArray["IdArticle"]=$_POST["IdArticle"];
							$ligneArray["NomArt"]=$_POST["NomArt"];
							$ligneArray["Qte"]=$_POST["Qte"];
							$ligneArray["PV"]=$_POST["PV"];
							$ligneArray["Gamme"]=$_POST["Gamme"];
							$ligneArray["UniteVente"]=$_POST["UniteVente"];
								$ligneArray["NbrBox"]=$_POST["NbrBox"];
								$ligneArray["NbrPalette"]=$_POST["NbrPalette"];
								$ligneArray["NbrColisage"]=$_POST["NbrColisage"];
								$ligneArray["Colisage"]=$_POST["Colisage"];
								$ligneArray["Reference"]=$_POST["Reference"];
							/*$ligneArray["TVA"]=$_POST["TVA"];
							$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
							$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;*/
							
								if($_POST["UniteVente"]=="Box")
								{
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"])*tofloat($_POST["NbrBox"]);
									
								}else if ($_POST["UniteVente"]=="Palette"){
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"])*$_POST["NbrPalette"]*tofloat($_POST["NbrBox"]);
									
								}else if($_POST["UniteVente"]=="Colisage"){
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"]);
								}
							$_SESSION['lignesCat'][$IndexLigne]= $ligneArray;
						
						
			  }
		  }
		  else {// une premiere insertion sans controle
						 //$IndexLigne+=1;

								$IndexLigne=0;
								$ligneArray["IdArticle"]=$_POST["IdArticle"];
								$ligneArray["NomArt"]=$_POST["NomArt"];
								$ligneArray["Qte"]=$_POST["Qte"];
								$ligneArray["PV"]=$_POST["PV"];
								$ligneArray["Gamme"]=$_POST["Gamme"];
							/*
							$ligneArray["TVA"]=$_POST["TVA"];
								$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
								$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;*/
								$ligneArray["UniteVente"]=$_POST["UniteVente"];
								$ligneArray["NbrBox"]=$_POST["NbrBox"];
								$ligneArray["NbrPalette"]=$_POST["NbrPalette"];
								$ligneArray["NbrColisage"]=$_POST["NbrColisage"];
								$ligneArray["Colisage"]=$_POST["Colisage"];
								$ligneArray["Reference"]=$_POST["Reference"];
								if($_POST["UniteVente"]=="Box")
								{
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"])*tofloat($_POST["NbrBox"]);
									
								}else if ($_POST["UniteVente"]=="Palette"){
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"])*$_POST["NbrPalette"]*tofloat($_POST["NbrBox"]);
									
								}else if($_POST["UniteVente"]=="Colisage"){
									$ligneArray["PriceUnite"]=tofloat($_POST["PV"]);
								}
								$_SESSION['lignesCat'][$IndexLigne]= $ligneArray;
					
		  }
		    
		  //	parcourir($_SESSION['lignesCat']);
		 if ((isset($_POST['List'])) && ($_POST['List'])=='List') {	 
		 ?>
			<script language="javascript" type="text/javascript">
						ConsultCmd();
						$("#boxArticle").dialog('close');
			</script>
			<?php
		 }else  if ((isset($_POST['Rech'])) && ($_POST['Rech'])=='Rech') {	 
		 ?> 
			 <script language="javascript" type="text/javascript">
			 $("#boxArticle").dialog('close');
			</script>
		<?php } else {?>
				 <script language="javascript" type="text/javascript">
			 slider.goToNextSlide();
            //alert("you clicked me");
			 $('.UniteVente').val('Box');
			</script>
		<?php 
		}
?>
<script language="javascript" type="text/javascript">

			$("#txtTotal").val("<?php echo number_format(Total(), 2, '.', ' '); ?>");
			// 
		  //	$("#boxArticle").dialog('close');
</script>
<?php
	exit;
}
function Total(){
	$Total=0;
	$test="";

	if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {
	foreach($_SESSION['lignesCat'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
		
			//$Total+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
		//	$Total+=($contenu["Qte"]*$contenu["PriceUnite"]) ;
		if($contenu['UniteVente']=='Box'){
		$Total+=$contenu['NbrColisage']*str_replace(" ","",$contenu['Qte'])*str_replace(" ","",$contenu['PV']);
		}else 
		{
				$Total+=str_replace(" ","",$contenu['Qte'])*str_replace(" ","",$contenu['PV']);
		}
     // $test=$test." ".$contenu['NbrColisage'];
			}
	}
	return($Total);
}
if (isset($_GET['getArticle'])){
	$tabArtilce=array();
	/*
foreach($_SESSION['lignesFam'] as $v){//famille
	
		if(is_array($v)){ 
		//	parcourir($v);
		
	//	if($_SESSION['IdFamille']==$v["IdFamille"]) {
		//echo  $DsgFamille."<br>";return;	
		
			foreach($v as $d){// sous famille
			
						
			if(is_array($d)){//	parcourir($d);return;
			//		echo $d["IdSousFam"]."<br>";
		
				//if($_SESSION['IdSousFam']==$d["IdSousFam"]) {
					
						
					
						foreach($d as $f){	// marque										
							if(is_array($f)){
						//if($_SESSION['IdMarque']==$f["IdMarque"]) {
								//parcourir($f);return;							
								foreach($f as $g){//gammes									
								
									if(is_array($g)){
										//if ($g['IdGamme']==$_SESSION['IdGamme']){
										//	parcourir($g);return;	
																							
						
												foreach($g as $r){//article												
													if(is_array($r)){
														
															if ($r['IdArticle']==$_GET['idArticle']){															
																$DsgGamme=$g["DsgGamme"];	
																$DsgSousFam=$d["DsgSousFam"];
																$DsgFamille=$v["DsgFamille"];
																array_push($tabArtilce,$r);
															}
														//echo  $DsgGamme."<br>";	
																	}?><?php
												}
												//}	
											}
									
							
								}
							//}
						}
					}
					//}
			}
			}
		//}
		}
}*/
if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {
if(isset($_GET['Rech'])){

foreach($_SESSION['lignesFam'] as $v){//marque
		if(is_array($v)){ 
			
					$DsgMarque=$v["DsgMarque"];//echo  $DsgFamille."<br>";return;			
					
								foreach($v as $f){	// sous famille										
									if(is_array($f)){
												$DsgSousFam=$f["DsgSousFam"];								
												foreach($f as $g){//gammes						
														if(is_array($g)){
															//	parcourir($g);return;	
																//		$DsgSousFam=$d["DsgSousFam"];
																		$DsgGamme=$g["DsgGamme"];				
																	foreach($g as $r){//article				
																		if(is_array($r)){
																//		echo $r['Reference']."<br>";	return;
																			if ($r['Reference']==$_GET['Ref']){
																			array_push($tabArtilce,$r);
																			
																						}
																						?><?php
																	}
																	}	
																
												}
											}
										//}
								}
						//	}
					// }
					//}
														
									
			
		}
}
}
		}else {
foreach($_SESSION['lignesFam'] as $v){//marque
		if(is_array($v)){ 
		//	if($_SESSION['IdMarque']==$v["IdMarque"]){
					$DsgMarque=$v["DsgMarque"];//echo  $DsgFamille."<br>";return;			
					
								foreach($v as $f){	// sous famille										
									if(is_array($f)){
										//if($_SESSION['IdFamille']==$f["IdSousFam"]){
												$DsgSousFam=$f["DsgSousFam"];								
												foreach($f as $g){//gammes						
														if(is_array($g)){
															//	parcourir($g);return;	
																//		$DsgSousFam=$d["DsgSousFam"];
																		$DsgGamme=$g["DsgGamme"];				
																	foreach($g as $r){//article				
																		if(is_array($r)){
																
																			if ($r['IdArticle']==$_GET['idArticle']){
																			array_push($tabArtilce,$r);
																			//echo  $DsgGamme."<br>";	
																						}
																						?><?php
																	}
																	}	
																
												}
											}
										//}
								}
						//	}
					// }
					//}
														
									
			}
		//}
}
}
}// fin else rech
//echo count($tabArtilce);return;
//parcourir($tabArtilce);return;
					IF(		count($tabArtilce)==0){
						?>
						<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>	<br><br><br><br>	<br><br><br><br>
										<div style="font-size:28px"><?php  echo $trad['msg']['AucunResultat'];?></div>
							</div>	<br><br><br><br>
							<center><input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="FermerBoxArt()"/>
							</center>
						<?php
					}else {						
								$IdArticle=$tabArtilce[0]['IdArticle'];
								$dsgArticle=$tabArtilce[0]['DsgArticle'];
								$dsgGamme=$DsgGamme;
								$TVA=$tabArtilce[0]['TVA'];
								$ColisageCmde="";
								$UniteVente="Box";
								$i=0;
								?>
									<DIV class="haut">
										<div class="divLeftArt" style="width:590px;">
					<div id="resAdd" ></div>
										<form id="formAddArt" method="post" name="formAddArt"> 
											<TABLE  dir="<?php echo $_SESSION["dir"];?>"  border="0" width="100%" class="table" cellspacing="2" cellpadding="7">
												<tr class="animated fadeInUp"><TD width="15%"><?php  echo $trad['label']['Ref'];?>:</td><td align="<?php echo $_SESSION["align"];?>">	<?php  echo $tabArtilce[0]['Reference'];?>
												<input type="hidden" value="<?php  echo $IdArticle; ?>" name="IdArticle">
												<input type="hidden" value="<?php  echo stripcslashes($dsgArticle); ?>" name="NomArt">
												<input type="hidden" value="<?php  echo stripcslashes($DsgGamme); ?>" name="Gamme">
												<input type="hidden" value="<?php  echo $TVA; ?>" name="TVA">
												<input type="hidden" value="<?php  echo $tabArtilce[0]['PV'];?>" name="PV">
												<input type="hidden" value="<?php  echo $tabArtilce[0]['Reference'];?>" name="Reference">
												<?php if (isset($_GET['list'])){
													
													$key = array_search($_GET['idArticle'],array_column($_SESSION["lignesCat"], 'IdArticle'));
													$Qte=$_SESSION["lignesCat"][$key]["Qte"];
													$UniteVente=$_SESSION["lignesCat"][$key]["UniteVente"];
													?>												
													<input type="hidden" value="List" name="List">
													<?php } else 	if (isset($_GET['Rech'])){?>
													<input type="hidden" value="Rech" name="Rech">
													<?php
														$Qte=1;
													}else  $Qte=1;?>
												</td></tr>
												<tr class="animated fadeInUp"><TD ><?php  echo $trad['label']['Dsg'];?>:</td><td  align="<?php echo $_SESSION["align"];?>">	<?php  echo stripcslashes(ucfirst($dsgArticle));?></td></tr>
												<tr class="animated fadeInUp"><TD><?php  echo $trad['label']['Gamme'];?>:</td><td  align="<?php echo $_SESSION["align"];?>" ><?php  echo stripcslashes(ucfirst($DsgGamme));?></td></tr>
												<tr class="animated fadeInUp"><TD><?php  echo $trad['label']['Famille'];?>:</td><td align="<?php echo $_SESSION["align"];?>"><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
												<tr class="animated fadeInUp" style="display:none"><TD><?php  echo $trad['label']['Famille'];?>:</td><td align="<?php echo $_SESSION["align"];?>"><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
												<tr class="animated fadeInUp"><TD><?php  echo $trad['label']['PV'];?>:</td><td align="<?php echo $_SESSION["align"];?>">
												<span class="prix">
												
												<?php  echo $tabArtilce[0]['PV']; 
												echo $trad['label']['riyal'];
												echo " (".$trad['label']['PourColisage'].")";
												?>
												</span>
												</td></tr>
												<tr class="animated fadeInUp"><TD><?php  echo $trad['label']['unite'];?>:</td>
												<td height="70" align="<?php echo $_SESSION["align"];?>">
												
												
												
												<input type="radio" IdLigne="<?php echo $i;?>"  Unite="Box" name="Colisage" class="box action"  value="<?php echo $tabArtilce[0]['Box'];?>"  
												data-labelauty="<?php  //echo $trad['label']['Box']." (".$tabArtilce[0]['Box'].")";?>|<?php //  echo $trad['label']['Box']." (".$tabArtilce[0]['Box'].")";?>" aria-label="2"  checked />
												
												<input type="radio" IdLigne="<?php echo $i;?>" Unite="Colisage"  class="colisage action" name="Colisage"  value="<?php echo $tabArtilce[0]['Colisage'];?>" 
												data-labelauty="<?php  //echo $trad['label']['Colisage']." (".$tabArtilce[0]['Colisage'].")";?>|<?php  //echo $trad['label']['Colisage']." (".$tabArtilce[0]['Colisage'].")";?>" aria-label="3"   />
												<input type="hidden"	value="<?php echo $i;?>" class="index" />
												
												<input type="hidden"
													value="<?php echo $UniteVente;?>"
												class="UniteVente" name="UniteVente" />
												
												<input type="hidden"
													value="<?php echo $tabArtilce[0]['PV'];?>"
												Id="PrixVente<?php echo $i;?>"  />
												
												<input type="hidden"
													value="<?php echo $tabArtilce[0]['Palette'];?>"
												Id="NbrPalette<?php echo $i;?>" name="NbrPalette" />
											
												
												<input type="hidden"
													value="<?php echo $tabArtilce[0]['Box'];?>"
												Id="NbrBox<?php echo $i;?>" name="NbrBox" />
												
												<input type="hidden"
													value="<?php echo $tabArtilce[0]['Colisage'];?>"
												Id="NbrColisage<?php echo $i;?>" name="NbrColisage" />
												
												
												</td></tr>
												<tr class="animated fadeInUp"><TD><?php  echo $trad['label']['Qte'];?>:</td><td Valign="top" align="<?php echo $_SESSION["align"];?>"> 
												<input type="text" value="<?php echo $Qte; ?>" name="Qte" onkeypress="return isEntier(event)" 
												class="Qte ConvertDecimal nbr" Style="text-align:<?php echo $_SESSION["align"];?>"
												size="4" id="Qte<?php echo $i; ?>" >
												<input type="button" class=" qtyplus"   id="qtyplus" onclick="Plus(<?php echo $i;?>)"
												value="+" id="btnp">&nbsp;
												<input type="button" class=" qtyplus" id="qtyminus" onclick="Moins(<?php echo $i;?>)" value="-"></td></tr>
												<tr class="animated fadeInUp">	
												<td   Valign="middle" colspan="2" height="70">
												<div class="divPRix<?php echo $i;?> divPRix">												
												</div>
												</td>
												
												</tr>
											
											</table>
											</form> 
										</div>
										
										<div class="divRight" style="width:350px;height:530px;">
					<img src="../<?php  echo $tabArtilce[0]['UrlArticle'];?>" alt=""  width="350" height="350" /><!--width="640" height="596"-->	
									<br/><br/><br/>	<table width="100%">
											<tr class="animated fadeInUp">	
												<td Valign="top" align="center" colspan="2">
												<input type="button" value="<?php echo $trad['button']['Valider'];?>" class="btn chpinvisible"  onclick="AjoutArticle()"/>&nbsp;&nbsp;
												<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="FermerBoxArt()"/>
												</td>
												</tr>
												</table>
										</div>
									</div>
									<script language="javascript" type="text/javascript">
									$("#boxRech").dialog('close');
									</script>
									<?php
					}
					//	}}
					
	}// fin if session plein
	else {	?>
						<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>	<br><br><br><br>	<br><br><br><br>
										<div style="font-size:28px"><?php  echo $trad['msg']['AucunResultat'];?></div>
							</div>	<br><br><br><br>
							<center><input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="FermerBoxArt()"/>
							</center>
						<?php
					}
?>
<script language="javascript" type="text/javascript">
/*function Plus2(){
		 	var fieldId="Qte";
        // Stop acting like a button
       
        var currentVal = parseInt($('input[id='+fieldId+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
            $('input[id='+fieldId+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[id='+fieldId+']').val(1);
        }
		
		CalculPrixArt(0);
}
function Moins2(){
		var fieldId="Qte";
        // Stop acting like a button
        var currentVal = parseInt($('input[id='+fieldId+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
			if((currentVal - 1)>=1){
            $('input[id='+fieldId+']').val(currentVal - 1);
			}
        } else {
            // Otherwise put a 0 there
            $('input[id='+fieldId+']').val(1);
        }
		CalculPrixArt(0);
}*/
$('input[type="radio"]').click(function(){
    if ($(this).is(':checked'))
    {	
		$(".UniteVente").val($(this).attr("Unite"));
		CalculPrixArt(0);
    }
  });
$(document).ready(function(){
	$('input[Unite="<?php echo $UniteVente;?>"]').attr('checked', 'checked');
	$(".action").labelauty();
	CalculPrixArt(0);

		
});
 

</script>
<?php
		exit;
}

include("header_y_2.php");
?>
<Style>
.cadreIndex{
	margin:10px 15px;
}
.childIndex{
	width:225px;
max-width:226px;
}
</style>
<div id="formRes" style="MAX-height:790px;">

</div>


<div class="clear"></div>
<!--<div class="container">
  <div class="jumbotron">
    <h1 class="animated fadeInLeft">Bootstrap Tutorial</h1> 
   </div>
</div>-->
<div class="bottomVente chpinvisible">
<div class="divLeft">
			<div class="cmd">
			
			<?php echo $trad['label']['TotalGlobal'];?>: <input type="text" value=" 0.000" class="global" id="txtTotal" size="6" disabled> 
			<?php echo $trad['label']['riyal'];?></div>
		</div>
		<div class="divRight" style="width:389PX">
			<input type="button" value="<?php echo $trad['button']['ConsultCmd'];?>" class="btnCmd" onclick="ConsultCmd()">
		</div>
		</div>
<div id="box"></div><div id="boxRech"></div><div id="boxArticle"></div>
<script language="javascript" type="text/javascript">		
// Get the modal

	$("#txtTotal").val("<?php echo number_format(Total(), 2, '.', ' '); ?>");
function ConsultCmd(){
	 var Verif="";
	$.get("depliant.php?VerifSession", function(response) {
      Verif = response;
	  // verifier si le vendeur a ajouté des articles
		if(Verif==1){
		var url='depliant.php?ConsultCmd';	
		$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
		//$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
			}else 
			{
			
				jAlert("<?php echo $trad['msg']['AddArticle'];?>","<?php echo $trad['titre']['Alert'];?>");
			}
	});		
}
function getArticle(idArticle,list){

	if (list === undefined || list === null) {
		var url='depliant.php?getArticle&&idArticle='+idArticle;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}
	else{
		//pour afficher liste
		var url='depliant.php?getArticle&&list&&idArticle='+idArticle;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}
	
}


$(document).ready(function(){



	//$(":radio").labelauty();	
// code pour prendre enconsideration l'hover quand on met le doigt sur l'ecran
$("input[type=button").addClass("hvr-grow");
$('body').bind('touchstart', function() {});

//$("#txtTotal").val("<?php echo number_format("0.00", 2, '.', ' '); ?>");
	//$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('depliant.php?affGamme&&VideFam');
	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('depliant.php?affMarque&&VideFam');
		//$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('depliant.php?TypeVente');
$.validator.messages.required = '';
		$('#box').dialog({
					autoOpen		:	false,
					width			:	950,/*1260,*/
					height			:	590,
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
							terminer();
						
						}
					 }
			});


		$('#boxRech').dialog({
					autoOpen		:	false,
					width			:	450,/*1260,*/
					height			:	290,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	''
					 
			});
				$('#boxArticle').dialog({
					autoOpen		:	false,
					width			:	950,/*1260,*/
					height			:	575,
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
							terminer();
						
						}
					 }
			});
		 // This button will increment the value
$('input[type=text]').on('focus', function() { 
  console.log($(this).attr('id') + ' just got focus!!');
  window.last_focus = $(this);
});
});
/*
function AfficheGamme1(CurrentSlide){
								
				
					if (CurrentSlide === undefined || CurrentSlide === null) {
					var url='depliant.php?affGamme'	}
					else {
							var url='depliant.php?affGamme&CurrentSlide='+CurrentSlide;	
					}			
				$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}*/
function AfficheGamme1(id,CurrentSlide){
								
			
					if (CurrentSlide === undefined || CurrentSlide === null) {
					var url='depliant.php?affGamme&&Id='+id;	}
					else {
							var url='depliant.php?affGamme&Id='+id+'&CurrentSlide='+CurrentSlide;	
					}
			
				$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

							}
function AfficheDetailGamme(id){
	//alert('lll');
	/*var url='depliant.php?aff&&Id='+id;	
	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');*/
	var current = slider.getCurrentSlide();
	var url='depliant.php?affArti&Id='+id+'&CurrentSlide='+current;	
	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

}
function rechercherFam(id){
	
		var url='depliant.php?affFam&&Id='+id	;
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}
function AfficheSousFam(id){
		//alert(id);
		var url='depliant.php?affSousFam&&Id='+id;	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}
	function AfficheMarque(){
		var url='depliant.php?affMarque';	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}
	/*
function AfficheMarque(id){
		var url='depliant.php?affMarque&&Id='+id;	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}*/

function Fermer(){
	$("#box").dialog('close');
}
function FermerBoxArt(){
	$("#boxArticle").dialog('close');
}
function FermerFenetre(){
	alert("fermer");
	var url='depliant.php?FermerFenetre';	
	//$("#box").load(url);
}
function ValideCmdEtape1(){
	/*$('#formCmd').ajaxSubmit({
		target:'#res',
		url:'depliant.php?ValideCmd',
			method			:	'post'
		});
		patienter('res');*/
		//$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('paiement.php').dialog('open');
	
		//$('#res').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load('paiement.php');
/*$('#formRes').append('<form id="formReg" action="" method="post" name="formReg">');
$('#formRes').append('<div id="FormPaiement" STYLE="height:360px;">');
$('#formRes').append('</div>	<DIV style="width:100%;text-align:center">');
$('#formRes').append('<input type="button" value="<?php  echo $trad["button"]["Valider"];?>" class="btn"  onclick="TerminerCmd()"/></div>');
$('#formRes').append('</form><div id="resReg"></div></div>');
$('#formRes').load('paiement.php?ChoixTypeReg');*/


		//$('#box').dialog('close');
		$('#res').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load('depliant.php?TerminerCmd');
}
function Imprimer(IdFacture){
		/*	var adr = 'ficheControle.print.php?IdDmd='+idDmd;
			//alert(adr);
			window.location.href = adr;*/
			 options = "Width=1280,Height=800" ;
		//	 alert(IdFacture);
		//	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('facture.print.php?IdFacture='+IdFacture).dialog('open');

		  window.open( 'facture.print.php?IdFacture='+IdFacture, "edition", options ) ;
		
	}
    var TabColor = [ 
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
					'#fbb1a3 ', '#c0ffc3 ' , '#adedf7 ', '#fcf7ab ','#fcd69f ', '#a0bffa ', '#a0fab9 ', '#fab8c3 ',
	];
	

	function AjoutArticle(id){
	var form="";
		if (id === undefined || id === null)  {  form="#formAddArt";}
	else { form='#formAddArt'+id;}

	    $(form).validate({
                                              
                                   rules: {
                                               
                                                'Qte': "required",
												'Colisage': "required"
                                           }  
										   
										    });
											
var test=$(form).valid();

		if((test==true) ){		
			 jConfirm('<?php echo $trad['msg']['ConfirmerAddAr'];?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
					if(r)	{
							var Verif="";							 
							 // verification du stock vendeur pour l'article selectionné
							 	$(form).ajaxSubmit({
									   url : 'depliant.php?VerifStock',
									   type : 'POST',
									   dataType : 'html', // On désire recevoir du HTML
									   success : function(code_html, statut){										   
										   Verif=code_html;
									//		alert(Verif);
											if(Verif==1){
											$(form).ajaxSubmit({
																target			:	'#resAdd',
																url				:	'depliant.php?goAddArti',
																method			:	'post'
															});
															return false;
											}else 
											{
												
												jAlert("<?php echo $trad['msg']['DepasseStockVendeur'];?> ","<?php echo $trad['titre']['Alert'];?>");
											}
									   }
								})
						   
						   

												
						}
				})
		}
}
function GetCatalogue(TypeVente){			 
	var url='depliant.php?affFam&&VideFam&&IdTypeVente='+TypeVente;
	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}
$(document).on('keyup', '.ConvertDecimal', function(e){
   this.value = this.value.replace(/ /g,'');
    var number = this.value;
    this.value = number.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  });
  function CalculPrixArt(IdLigne){
	  
	var Unite =$('input[idligne='+IdLigne+']:checked').attr("Unite");	
	var txt="";
	var PriceUnite=0;
	var TotalPrice=0;
	var nbrColisage=0;
	
	if(Unite=="Palette"){
		
		nbrColisage=$('input[id=NbrPalette'+IdLigne+']').val()*$('input[id=NbrBox'+IdLigne+']').val()* $("#Qte"+IdLigne).val().replace(" ","");
		//alert(nbrColisage);
		PriceUnite=$("#PrixVente"+IdLigne).val()*$('input[id=NbrPalette'+IdLigne+']').val()*$('input[id=NbrBox'+IdLigne+']').val();
		
		PriceUnite=number_format(PriceUnite, 2, ".", " ");
		
		TotalPrice=PriceUnite* $("#Qte"+IdLigne).val().replace(" ","");
		TotalPrice=number_format(TotalPrice, 2, ".", " ");
		
		if($("#Qte"+IdLigne).val().replace(" ","")>2){
			txt="<br>	<?php  echo $trad['label']['PrixVente'];?> <span class='nbr'>"+$("#Qte"+IdLigne).val()+"</span> <?php  echo $trad['label']['TroisPalette'];?> <span class='nbr'>"+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}else if($("#Qte"+IdLigne).val().replace(" ","")==2){
			txt="<br>	<?php  echo $trad['label']['PrixVente']." ".$trad['label']['DeuxPalette'];?>  <span class='nbr'> "+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}
		
		$(".divPRix"+IdLigne).html("<?php echo  $trad['label']['NbrColisage'] ;?> "+nbrColisage+"<br><?php echo  $trad['label']['PrixVentePalette'] ;?> <span class='nbr'>"+PriceUnite+"</SPAN>  <?php  echo $trad['label']['riyal'];?>"+txt);
		
		
	}if(Unite=="Box"){// box designe cmd par  colisage

		nbrColisage=$('input[id=NbrBox'+IdLigne+']').val()* $("#Qte"+IdLigne).val().replace(" ","");
		PriceUnite=$("#PrixVente"+IdLigne).val()*$('input[id=NbrColisage'+IdLigne+']').val();
		PriceUnite=number_format(PriceUnite, 2, ".", " ");
		
		TotalPrice=PriceUnite* $("#Qte"+IdLigne).val().replace(" ","");
		TotalPrice=number_format(TotalPrice, 2, ".", " ");
		
		if($("#Qte"+IdLigne).val().replace(" ","")>2){
			txt="<?php  echo $trad['label']['PrixVente'];?> <span class='nbr'>"+$("#Qte"+IdLigne).val()+"</span> <?php  echo $trad['label']['TroisBox'];?> <span class='nbr'> "+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}else if($("#Qte"+IdLigne).val().replace(" ","")==2){
			txt="<?php  echo $trad['label']['PrixVente']." ".$trad['label']['DeuxBox'];?> <span class='nbr'> "+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}
			
		$(".divPRix"+IdLigne).html("<?php echo  $trad['label']['nbrBox'] ;?> "+nbrColisage+"<br><?php echo  $trad['label']['PrixVenteBox'] ;?> <span class='nbr'>"+PriceUnite+"</SPAN>  <?php  echo $trad['label']['riyal'];?><br>	"+txt);
		
	}if(Unite=="Colisage"){ //colisage designe cmd par piece
		
		nbrColisage=$("#Qte"+IdLigne).val().replace(" ","");
		
		PriceUnite=$("#PrixVente"+IdLigne).val();
		PriceUnite=number_format(PriceUnite, 2, ".", " ");
		
		TotalPrice=PriceUnite* $("#Qte"+IdLigne).val().replace(" ","");
		TotalPrice=number_format(TotalPrice, 2, ".", " ");
		
		if($("#Qte"+IdLigne).val().replace(" ","")>2){
			txt="<br>	<?php  echo $trad['label']['PrixVente'];?> <span class='nbr'>"+$("#Qte"+IdLigne).val()+"</span> <?php  echo $trad['label']['Pieces'];?> <span class='nbr'>"+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}else if($("#Qte"+IdLigne).val().replace(" ","")==2){
				txt="<br>	<?php  echo $trad['label']['PrixVente']." ".$trad['label']['DeuxPiece'];?><span class='nbr'> "+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}
		
		$(".divPRix"+IdLigne).html("<?php echo  $trad['label']['nbrPiece'] ;?> "+nbrColisage+"<br><?php echo  $trad['label']['PrixVenteColisage'] ;?> <span class='nbr'>"+PriceUnite+"</SPAN>  <?php  echo $trad['label']['riyal'];?>"+txt);
		
	}
	 
 }
 
function Moins(id){

		var fieldId="Qte";
        // Stop acting like a button
      
        var currentVal = parseInt($('input[id='+fieldId+id+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
			if((currentVal - 1)>=1){
            $('input[id='+fieldId+id+']').val(currentVal - 1);
			}
        } else {
            // Otherwise put a 0 there
            $('input[id='+fieldId+id+']').val(1);
        }
		 CalculPrixArt(id);
}
function Plus(id){
	
		var fieldId="Qte";
        // Stop acting like a button
       // e.preventDefault();
        var currentVal = parseInt($('input[id='+fieldId+id+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
            $('input[id='+fieldId+id+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[id='+fieldId+id+']').val(1);
        }
		CalculPrixArt(id);
    }
function RechArticle (){
		var Ref=$("#Ref").val();
		var url='depliant.php?getArticle&&Rech&&Ref='+Ref;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
		
		}
function  AffFormRech(){
	$('#boxRech').load("depliant.php?FormRech").dialog('open');
}	
</script>
<script type="text/javascript">
	//$('table tbody tr').addClass('animated fadeInUp');
</script>
<style type="text/css">
	*{
				 color: #2c3e50; /* #223b3e; # 34495e*/
				 letter-spacing: -.05em;
				 font-weight: 100;
				 font-family: 'Montserrat', sans-serif;
				 -webkit-animation-duration: 4s;
  				 -webkit-animation-delay: 0.1s;
  				 -moz-animation-duration: 4s;
  				 -moz-animation-delay: 0.1s;
  				 -o-animation-duration: 4s;
  				 -o-animation-delay: 0.1s;
  				 -ms-animation-duration: 4s;
  				 -ms-animation-delay: 0.1s;
  				 animation-duration: 4s;
  				 animation-delay: 0.1s;
				
			}

		

</style>
<?php include("footer.php");?>
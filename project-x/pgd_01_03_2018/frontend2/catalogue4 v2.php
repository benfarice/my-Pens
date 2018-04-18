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
$tabArtilce=array();
$k=0;
$DsgFamille="";$DsgSousFam="";$DsgGamme="";
//parcourir($_SESSION['lignesFam']);return;
foreach($_SESSION['lignesFam'] as $v){//famille
	
				
		if(is_array($v)){ 
		//	parcourir($v);
	//	if($_SESSION['IdFamille']==$v["IdFamille"]) {
		$DsgFamille=$v["DsgFamille"];//echo  $DsgFamille."<br>";return;	
		
			foreach($v as $d){// sous famille
			
						
			if(is_array($d)){//	parcourir($d);return;
			//		echo $d["IdSousFam"]."<br>";
			//	if($_SESSION['IdSousFam']==$d["IdSousFam"]) {
					
						
					
						foreach($d as $f){	// marque										
							if(is_array($f)){
						//if($_SESSION['IdMarque']==$f["IdMarque"]) {
								//parcourir($f);return;							
								foreach($f as $g){//gammes								
								
									if(is_array($g)){
										if ($g['IdGamme']==$_GET['Id']){
										//	parcourir($g);return;	
										$DsgSousFam=$d["DsgSousFam"];
													$DsgGamme=$g["DsgGamme"];
												//	$tabArtilce=$g;
												//echo $g["IdArticle"];
										
												?> <?php
											

						//	$tabArtilce=$g;
							//array_push($tabArtilce,$g);
				
												foreach($g as $r){//article					
							
													if(is_array($r)){
														/*$key="";
														//print_r($r);
														//echo $r["DsgArticle"];
														if(isset($tabArtilce)){
														
																$key =  array_search($r['IdArticle'],array_column($tabArtilce["idArticle"], 'IdArticle'));
														}
														echo $key;*/
														
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
					}
					//}
			}
			}
		//}
		}
	//	parcourir($tabArtilce);return;
}

?>
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
									<DIV class="haut">
										<div class="divLeftArt" style="width:658px;">
					
										<form id="formAddArt<?php echo $i;?>" method="post" name="formAddArt"> 
											<TABLE  dir="<?php echo $_SESSION["dir"];?>"  border="0" width="100%" class="table" cellspacing="2" cellpadding="7">
												<tr><TD width="15%"><?php  echo $trad['label']['Ref'];?>:</td><td align="<?php echo $_SESSION["align"];?>">	<?php  echo $r['Reference'];?>
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
												<tr><TD ><?php  echo $trad['label']['Dsg'];?>:</td><td  align="<?php echo $_SESSION["align"];?>">	<?php  echo stripcslashes(ucfirst($dsgArticle));?></td></tr>
												<tr><TD><?php  echo $trad['label']['Gamme'];?>:</td><td  align="<?php echo $_SESSION["align"];?>" ><?php  echo stripcslashes(ucfirst($DsgGamme));?></td></tr>
												<tr><TD><?php  echo $trad['label']['SousFamille'];?>:</td><td align="<?php echo $_SESSION["align"];?>"><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
												<tr Class="chpinvisible"><TD><?php  echo $trad['label']['Famille'];?>:</td><td align="<?php echo $_SESSION["align"];?>"><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
												<tr><TD><?php  echo $trad['label']['PV'];?>:</td><td align="<?php echo $_SESSION["align"];?>">
												<span class="prix">												
												<?php  echo $r['PV']; 
												echo $trad['label']['riyal'];
												echo " (".$trad['label']['PourColisage'].")";
												?>
												</span>												
												</td></tr>
												<tr><TD><?php  echo $trad['label']['unite'];?>:</td><td height="110" align="<?php echo $_SESSION["align"];?>">
												
												<input type="radio" IdLigne="<?php echo $i;?>"  Unite="Palette" class="palette" name="Colisage"  value="<?php echo $r['Palette'];?>" 
												data-labelauty="<?php //echo $trad['label']['Palette']." (".$r['Palette'].")";?>|<?php //  echo $trad['label']['Palette']." (".$r['Palette'].")";?>" aria-label="1"   />
												
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
												
												

												</td></tr>
												<tr><TD><?php  echo $trad['label']['Qte'];?>:</td><td Valign="top" align="<?php echo $_SESSION["align"];?>"> 
												<input type="text" value="<?php echo $Qte;?>" name="Qte" onkeypress="return isEntier(event) "  onblur="CalculPrixArt('<?php echo $i;?>')"
												class="Qte ConvertDecimal nbr" 
												Style="text-align:<?php echo $_SESSION["align"];?>"
												size="8" id="Qte<?php echo $i;?>">
												<input type="button" class=" qtyplus"  style="padding-right:0" id="qtyplus" onclick="Plus('<?php echo $i;?>')"
												value="+" id="btnp">&nbsp;
												<input type="button" class=" qtyplus" style="padding-right:0" id="qtyminus" onclick="Moins('<?php echo $i;?>')" value="-"></td></tr>
												<TR>	
												<td   Valign="middle" colspan="2" height="130">
												<div class="divPRix<?php echo $i;?> divPRix">												
												</div>
												</td>
												
												</tr>
											
											</table>
											</form> 
										</div>
										
										<div class="divRight" style="width:600px;">
										<img src="../<?php  echo $r['UrlArticle'];?>" alt=""  width=" 600" height="496" />
<BR>
<TABLE border=0 width="100%">
	<TR>	
												<td   Valign="top" align="center" colspan="2">
												<input type="button" value="<?php  echo $trad['button']['Fermer'];?>"   style="display:none;"
												id="BtnFermer<?php echo $i;?>"
												class="btn"   onclick="AfficheGamme1('<?php echo $_GET['CurrentSlide'];?>')"/>&nbsp;&nbsp;
												<input type="button" value="<?php  echo $trad['button']['Valider'];?>" class="btn"  onclick="AjoutArticle('<?php echo $i;?>')"/>
												
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
			<tr><TD><?php  echo $trad['label']['Gamme'];?>:</td><td><?php echo  stripcslashes(ucfirst($DsgGamme));?></td></tr>
			<tr><TD><?php  echo $trad['label']['SousFamille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
			<tr><TD> <?php  echo $trad['label']['Famille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
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
			
	?>
	<DIV style="  display:flex;  align-items:center;" class="headVente"><a href="index.php">
<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>&nbsp;> <?php  echo $trad['titre']['Gamme'];?>
</div>
	<?php
//$idArticle=$_GET['idArticle'];
	if(isset($_GET['VideFam'])){			
		// vider liste des articles commandés
		//unset($_SESSION['lignesFam']);
		// vider le catalogue
}
unset($_SESSION['IdFamille']);
unset($_SESSION['IdSousFam']);
unset($_SESSION['IdMarque']);
unset($_SESSION['IdGamme']);
$timestamp_debut = microtime(true);
unset($_SESSION['lignesFam']);

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
			INNER JOIN dbo.detailChargements dc ON dc.idArticle=a.IdArticle
			INNER JOIN dbo.chargements c ON c.IdChargement=dc.IdChargement
		WHERE 
		c.idVendeur=?
		and
		f.TypeVente=?
		AND c.etat=1
		and ( m.idMarque=17 or  m.idMarque=18 ) AND f.etat=1
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
		

		 $params = array($_SESSION["IdVendeur"],$_GET["IdTypeVente"]);	

/*	 echo $sql;
	ECHO $_SESSION["IdVendeur"]."<br>"; return;
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
												 
												 
										$key = $row['IdFamille'];
										
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['IdFamille']=$row['IdFamille'];
											$groups[$key]['DsgFamille']=$row['DsgFamille'];
											$groups[$key]['UrlFamille']=$row['UrlFamille'];
											$_SESSION['IdFiche']=$row['idFiche'];
										} 
									//	else {
												$keySousFam = $row['IdSousFam'];
												if (!isset($groups[$key][$keySousFam])) {
											
														$groups[$key][$keySousFam] = array();
														$groups[$key][$keySousFam]['IdSousFam']=$row['IdSousFam'];
														$groups[$key][$keySousFam]['DsgSousFam']=$row['dsgSousFamille'];
														$groups[$key][$keySousFam]['UrlSousFamille']=$row['UrlSousFamille'];
														
													} 
												//	ELSE {
													$keyMarque= $row['IdMarque'];
													if (!isset($groups[$key][$keySousFam][$keyMarque])) {
												
															$groups[$key][$keySousFam][$keyMarque] = array();
															$groups[$key][$keySousFam][$keyMarque]['IdMarque']=$row['IdMarque'];
															$groups[$key][$keySousFam][$keyMarque]['DsgMarque']=$row['DsgMarque'];
															$groups[$key][$keySousFam][$keyMarque]['UrlMarque']=$row['UrlMarque'];
															
														} 
														
												$keyGamme= $row['IdGamme'];
													if (!isset($groups[$key][$keySousFam][$keyMarque][$keyGamme])) {
												
															$groups[$key][$keySousFam][$keyMarque][$keyGamme] = array();
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['IdGamme']=$row['IdGamme'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgGamme']=$row['dsgGamme'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['UrlGamme']=$row['UrlGamme'];
															
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgFamille']=$row['DsgFamille'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgSousFam']=$row['dsgSousFamille'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgMarque']=$row['DsgMarque'];													
														} 
																	
																				
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdArticle'] = $row['IdArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['DsgArticle'] = $row['DsgArticle'];									
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['PV'] =$row['PV'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Reference'] =$row['Reference'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Colisage'] =$row['Colisage'];
														$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Palette'] =$row['Palette'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Box'] =$row['Box'];													
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['TVA'] =$row['TVA'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Unite'] =$row['unite'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UniteVente'] ="";
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
	//	parcourir($_SESSION['lignesFam']);return;
$CurrentSlide=0;

if(isset( $_GET['CurrentSlide'])) $CurrentSlide=$_GET['CurrentSlide'];
?>	

<?php //echo $_SESSION['IdFamille']."___".$_SESSION['IdSousFam']."___".$_SESSION['IdMarque'] ; ?>
<div class="clear"></div>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							//	parcourir($_SESSION['lignesFam']);return;
							$key ="";
				if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {//famille
				//echo $_SESSION['IdFamille'];return;
				
					$i=1;
						foreach($_SESSION['lignesFam'] as $v){// famille
							if(is_array($v)){ 
							//if($_SESSION['IdFamille']==$v["IdFamille"]){
								foreach($v as $d){//sous famille
										if(is_array($d)){//	parcourir($d);return;
									
											//if( $_SESSION['IdSousFam']==$d["IdSousFam"]){
											
											foreach($d as $f){	//marque										
												if(is_array($f)){
													//echo $_GET['Id'];
												//echo $_SESSION['IdSousFam']."___".$d["IdSousFam"];
										//	if( $f['IdMarque']==$_GET['Id']) {
											
												
														foreach($f as $r){//gamme		
																if(is_array($r)){
																	//	parcourir($r);//	return;
																//	echo $r['DsgGamme'];;
															?>
																<li>
													<img src="../<?php  echo $r['UrlGamme'];?>" alt="<?php  echo $r['IdGamme'];?>"  width=" 100%" height="596" 
													style="position : relative ;  "/>	
																												
															 <input type="button" class="DetailGamme" value="<?php  echo $trad['button']['VoirArticles'];?>  "  
													
															 onclick="AfficheDetailGamme('<?php echo $r['IdGamme'];?>')" />
															  </li>
															 
															<?php
																}
		 
														}
											//} end if marque
												//echo $f['IdMarque'];return;
										
												}
											}
											//end if sous fam}
									}
							}
							//end if famille}
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
				
					
			},
			threshold: 1200
		});
		
</script>
	<?php
exit;
}


if (isset($_GET['affMarque'])){
		$_SESSION['IdSousFam']=$_GET['Id'];
	$idFam="";
?>	
<DIV style="  display:flex;  align-items:center;" class="headVente"><a href="index.php">
<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>
&nbsp;&nbsp; >
<span  class="TitleHead" onclick="rechercherFam()"> <?php  echo $trad['label']['Famille'];?></span> &nbsp; 
>&nbsp; 
<span  class="TitleHead" onclick="AfficheSousFam(<?php echo $_SESSION['IdFamille'];?>)"><?php  echo $trad['label']['SousFamille'];?></span>&nbsp; 
>&nbsp;
<span  class="" ><?php  echo $trad['label']['Marque'];?></span>
</div>
<div class="clear"></div>
<?php //echo $_SESSION['IdFamille']."___".$_SESSION['IdSousFam']; ?>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							$key ="";
				if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['lignesFam'] as $v){	//	famillesif(is_array($v)){	parcourir($v);}
							if(is_array($v)){ }
								if($v['IdFamille']==$_SESSION['IdFamille']){
								foreach($v as $d){//sous famille
										if(is_array($d)){	
										//echo $v['IdFamille'];
											if($d['IdSousFam']==$_GET['Id']){
												
												foreach($d as $r){											
												
													if(is_array($r)){														
														//parcourir($r);																							
														if( $i==1) echo " <li><div style='text-align:left'>" ;									
														
														?>
														  <div class="cadreIndex hvr-grow"
														 
														  onclick="AfficheGamme1('<?php echo $r['IdMarque'];?>')">
														  <div  class="childIndex"> 
														  <img src="../<?php echo $r['UrlMarque'];?>"  width="250" height="250"/>
														  <?php //	echo ucfirst($r['DsgMarque']);?></div>
														  </div>														  
														<?php
														if($i==4) {?> <div class="clear"></div><?php }
															//condition pour afficher 4 familles par page
															if ($i == 9) {  echo " </div></li>" ; $i=1;}
															else {				$i+=1;}
													
		 
														}
													}
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
				
					
			},
			threshold: 1200
		});
	
</script>
	<?php
exit;
}

if (isset($_GET['affSousFam'])){


	$_SESSION['IdFamille']=$_GET['Id'];
?>	
<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
<div  onclick="rechercherFam() " >&nbsp;> <span  Class="TitleHead"><?php  echo $trad['label']['Famille'];?></span></div>&nbsp;> 
<?php  echo $trad['label']['SousFamille'];?> </div>
<div class="clear"></div>
<?php //echo $_SESSION['IdFamille']; ?>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							$key ="";
				if( (isset($_SESSION['lignesFam'])) && (count($_SESSION['lignesFam'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['lignesFam'] as $v){	//	if(is_array($v)){	parcourir($v);}
						
							
									if(is_array($v)){
											if($v['IdFamille']==$_GET['Id']){
												//	parcourir($v);return;
											foreach($v as $r){
												
												if(is_array($r)){
												
													if( $i==1) echo " <li><div style='text-align:left'>" ;
													
													
													?>
													  <div class="cadreIndex hvr-grow"  style="width:250px;max-width: 250px;"  onclick="AfficheMarque('<?php echo $r['IdSousFam'];?>')">
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

	var i = 0;
	/*$('div.cadreSouf').each(function() {
    /*var rand = back[Math.floor(Math.random() * back.length)];
    console.log(rand);
    $('.cadreSouf').css('background',rand);
//	var item =  jQuery.rand(back);
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

if (isset($_GET['affFam'])){

//$idArticle=$_GET['idArticle'];
	if(isset($_GET['VideFam'])){
			
		// vider liste des articles commandés
		//unset($_SESSION['lignesFam']);
		// vider le catalogue
}
unset($_SESSION['IdFamille']);
unset($_SESSION['IdSousFam']);
unset($_SESSION['IdMarque']);
unset($_SESSION['IdGamme']);
$timestamp_debut = microtime(true);
unset($_SESSION['lignesFam']);

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
			INNER JOIN dbo.detailChargements dc ON dc.idArticle=a.IdArticle
			INNER JOIN dbo.chargements c ON c.IdChargement=dc.IdChargement
		WHERE 
		c.idVendeur=?
		and
		f.TypeVente=?
		AND c.etat=1	
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
		 order BY   fa.idFamille desc ";
		

		 $params = array($_SESSION["IdVendeur"],$_GET["IdTypeVente"]);	
	/*ECHO $_SESSION["IdVendeur"]."<br>"; 
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
												 
												 
										$key = $row['IdFamille'];
										
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['IdFamille']=$row['IdFamille'];
											$groups[$key]['DsgFamille']=$row['DsgFamille'];
											$groups[$key]['UrlFamille']=$row['UrlFamille'];
											$_SESSION['IdFiche']=$row['idFiche'];
										} 
									//	else {
												$keySousFam = $row['IdSousFam'];
												if (!isset($groups[$key][$keySousFam])) {
											
														$groups[$key][$keySousFam] = array();
														$groups[$key][$keySousFam]['IdSousFam']=$row['IdSousFam'];
														$groups[$key][$keySousFam]['DsgSousFam']=$row['dsgSousFamille'];
														$groups[$key][$keySousFam]['UrlSousFamille']=$row['UrlSousFamille'];
														
													} 
												//	ELSE {
													$keyMarque= $row['IdMarque'];
													if (!isset($groups[$key][$keySousFam][$keyMarque])) {
												
															$groups[$key][$keySousFam][$keyMarque] = array();
															$groups[$key][$keySousFam][$keyMarque]['IdMarque']=$row['IdMarque'];
															$groups[$key][$keySousFam][$keyMarque]['DsgMarque']=$row['DsgMarque'];
															$groups[$key][$keySousFam][$keyMarque]['UrlMarque']=$row['UrlMarque'];
															
														} 
														
												$keyGamme= $row['IdGamme'];
													if (!isset($groups[$key][$keySousFam][$keyMarque][$keyGamme])) {
												
															$groups[$key][$keySousFam][$keyMarque][$keyGamme] = array();
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['IdGamme']=$row['IdGamme'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgGamme']=$row['dsgGamme'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['UrlGamme']=$row['UrlGamme'];
															
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgFamille']=$row['DsgFamille'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgSousFam']=$row['dsgSousFamille'];
															$groups[$key][$keySousFam][$keyMarque][$keyGamme]['DsgMarque']=$row['DsgMarque'];
															
														} 
																								
													
																				
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdArticle'] = $row['IdArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['DsgArticle'] = $row['DsgArticle'];									
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['PV'] =$row['PV'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Reference'] =$row['Reference'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Colisage'] =$row['Colisage'];
									$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Palette'] =$row['Palette'];
										$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Box'] =$row['Box'];													
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['TVA'] =$row['TVA'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Unite'] =$row['unite'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UniteVente'] ="";
													//}
											$i=$i+1;		
										//}
										}
				
					$_SESSION['lignesFam']=$groups;


			 }// fin bdd plein
}// fin if isset session
	//	parcourir($_SESSION['lignesFam']);return;
	
?>

<DIV style="  display:flex;  align-items:center;" class="headVente">
<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>&nbsp;&nbsp;
<div Class="TitleHead" ><?php  echo $trad['depliant']['liste_des_fam'];?> <?php  //echo date("d/m/y h:i");?></div></div>
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


$k=0;
	$i=1;
	foreach($_SESSION['lignesFam'] as $u=>$v){	
		//echo "--------<li>".$k."</li>";
		// recherche pour ne pas dubliquer la couleur du cadre
		
		if( $i==1) echo " <li><div style='text-align:center'>" ;	
		?>
		  <div class="cadreIndex hvr-grow"   style="width:225px;" onclick="AfficheSousFam('<?php echo $v['IdFamille'];?>')">
			<div  class="childIndex" style="width:225px; max-width: 225px;" >
					<img src="../<?php echo $v['UrlFamille'];?>"  width="225" height="226"/>
					<div  style="padding:0;" class="titleCadre"><?php 	echo mb_ucfirst($v['DsgFamille']);?></div>
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
							},
			swipeLeft: function(event, direction, distance, duration, fingerCount) {	
						
				slider.goToNextSlide();	
				
					
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
			<TABLE   border="0" width="60%" class="table" cellpadding="5"  align="center" >
				<tr><TD align="right"><?php  echo $trad['label']['Montant'];?> :</td><td>	
					
					<div class="TotalReg "> <span class="nbr">
					<?php echo number_format(Total(), 2, '.', ' '); ?></span></div>					
					
			</td>
			<td><?php  echo $trad['label']['Observation'];?>:</td></tr>
			<tr><TD align="right"><?php  echo $trad['label']['TypeReg'];?> :</td>
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
				<tr  >
				<TD  align="right" colspan="3">
					<DIV class="bq">
					<table  cellpadding="0"  cellspacing="0" border="0" width="80%" align="center">
						<tr>
								<td align="right">
								<?php  echo $trad['label']['Banque'];?> :</td>
								<td>	
								<select style="display:block;width:350px" tabindex="2" g="select" name="Bq" id="TYPE2_REG">
									<option value="bp" selected><?php  echo $trad['label']['Populaire'];?></option>
								<option value="Cheque"><?php  echo $trad['label']['BMCI'];?></option> 			
								<option value="Virement"><?php  echo $trad['label']['SOGE'];?></option>
								</select>
								</td>
						</tr>
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
				<TR >	
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
							url:'catalogue4.php?TerminerCmd',
								method			:	'post'
							});
		patienter('resReg');
		}

	}
	</script>	
<?php
	exit;
}
if (isset($_GET['TerminerCmd'])){

	//parcourir($_SESSION['lignesCat'] );return;
	//parcourir($_SESSION['lignesCat'] );return;
	  $error="";
	  
/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
//-----------------Add facture----------------//
/// calcul total ht ,ttc
$TotalHT=0;$TotalTTC=0;$totalTVA=0;$Tva=0;
$EtatReg="R";
$Bq="";$numCheque="";$numVersement="";
if($_POST['TypeReg']=="Cheque") { $Bq=$_POST['Bq'] ;$numCheque=$_POST['Num'];}

else if($_POST['TypeReg']=="Virement") { $Bq=$_POST['Bq'] ;$numVersement=$_POST['Num'];}


	if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {
	foreach($_SESSION['lignesCat'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
				//total prix de facture est par outer
				//$contenu["PriceUnite"] est par outer
				$TotalTTC+=$contenu["Qte"]*$contenu["PriceUnite"];
	
			}
	}


$NumFacture= "NF".Increment_Chaine_F("numFacture","factures","IdFacture",$conn,"",array());	
//echo $RefFicheCh;return;
$Date = date_create(date("Y-m-d"));
$Heure=date("H:i:s");
$Etat="";
$reqInser1 = "INSERT INTO factures ([numFacture] ,[idClient]  ,[idVendeur]  ,visite,[date],[heure],[idDepot],totalTTC,etat,reste,TypeVente,DateLivraison,Observation) 
				values 	(?,?,?,?,?,?,?,?,?,?,?,?,?)";
		//	echo $reqInser1;
$params1= array(
				$NumFacture,
				$_SESSION["IdClient"],
				$_SESSION["IdVendeur"],
				$_SESSION["IdVisite"],
				date("Y-m-d"),
				$Heure,
				$IdDepot,
				$TotalTTC,
				$EtatReg,
				0 ,
				$_SESSION['IdTypeVente'],
				date("Y-m-d"),
				securite_bdd($_POST['Observation'])
				
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout facture ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IDFacture--------------------------------//
$sql = "SELECT max(IdFacture) as IdFacture FROM factures";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idfacture : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdFacture = sqlsrv_get_field( $stmt2, 0);


//----------------------Add Detail facture --------------------------//
$idFiche="";
  foreach($_SESSION['lignesCat'] as $ligne=>$contenu){

	$reqInser2 = "INSERT INTO  detailfactures(idFacture,[idArticle],[qte],tarif,idDepot,idFiche,ttc,UniteVente) 
					values (?,?,?,?,?,?,?,?)";
			$params2= array(
					$IdFacture,
					$contenu['IdArticle'],
					//$contenu['Qte'],
					floatval(str_replace(" ","",$contenu["Qte"])),
					$contenu['PriceUnite'],// prix de vente par unite (palette ou box/colisage)
					$IdDepot,
					$_SESSION["IdFiche"],
					floatval($contenu['PriceUnite'])*floatval(str_replace(" ","",$contenu["Qte"])) ,
					$contenu['UniteVente'] 
			
			) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout detail facture ".$errors[0]['message'] . " <br/> ";
				break ;
			}		


				//----------------------modification du stock vendeur --------------------------//
				// calculer la qte cmdé par outer
				
					if($contenu["UniteVente"]=="Box")
					{
						$QteCmd=floatval($_POST["NbrBox"])*$contenu['Qte'];
								//	echo floatval($_POST["NbrBox"]);return;
					}else if ($contenu["UniteVente"]=="Palette"){
						$QteCmd=$_POST["NbrPalette"]*floatval($_POST["NbrBox"])*$contenu['Qte'];
						
					}else if($contenu["UniteVente"]=="Colisage"){
						$QteCmd=$contenu['Qte'];
					}
		
					
				//qteCmd par outer
		//	$qteCmd=$contenu['Qte']*$contenu["Colisage"];
			$reqUpStock = "update   stockVendeurs set stock=stock-$QteCmd where idVendeur = ? and idArticle=?";
			$paramsUp= array($_SESSION['IdVendeur'],$contenu['IdArticle']) ;
			$stmtUp = sqlsrv_query( $conn, $reqUpStock, $paramsUp );
			if( $stmtUp === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : modification du stock vendeur ".$errors[0]['message'] . " <br/> ";
				break ;
			}
			
			
}

//----------------------Insertion regelement --------------------------//
$reqInser3 = "INSERT INTO reglements ([idClient]  ,[idVendeur] ,[date],[heure],[type],montant,idDepot,DateEcheance) 
				values 	(?,?,?,?,?,?,?,?)";
		//	echo $reqInser1;
$params3= array(				
				$_SESSION["IdClient"],
				$_SESSION["IdVendeur"],
				$Date,
				$Heure,
				"",
				$TotalTTC,
				$IdDepot,
				date("Y-m-d")				
) ;
$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3 );

if( $stmt4== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout regelement ".$errors[0]['message'] . " <br/> ";
}

//---------------------------Recuperer id Regelement--------------------------------//
$sql = "SELECT max(IdReglement) as IdReglement FROM reglements";
$stmtR = sqlsrv_query( $conn, $sql );
if( $stmtR === false ) {
    $error.="Erreur recuperation id Regelement : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmtR) ;
$IdRegelement = sqlsrv_get_field( $stmtR, 0);


//----------------------Insertion detail regelement --------------------------//
$reqInser4 = "INSERT INTO detailReglements (IdReglement  ,montant ,mode,banque,numCheque,numVersement) 
				values 	(?,?,?,?,?,?)";
		//	echo $reqInser1;
$params4= array(				
				$IdRegelement,
				$TotalTTC,
				$_POST['TypeReg'],
				$Bq,
				$numCheque,
				$numVersement
) ;
$stmt5 = sqlsrv_query( $conn, $reqInser4, $params4 );

if( $stmt5== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Insertion detail reglement ".$errors[0]['message'] . " <br/> ";
}
//----------------------Insertion reglementFactures --------------------------//

$reqInser5 = "INSERT INTO reglementFactures (IdReglement  ,idFacture ,idDepot) 
				values 	(?,?,?)";
			//echo $reqInser5;
$params5= array(				
				$IdRegelement,
				$IdFacture,
				$IdDepot
) ;
$stmt6= sqlsrv_query( $conn, $reqInser5, $params5 );

if( $stmt6== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Insertion reglementFactures ".$errors[0]['message'] . " <br/> ";
}

//----------------------Cloturer la visite--------------------------//
$Date = date("Y-m-d");
$Heure=date("H:i");

$reqUpVi= "update  visites set dateFin=?, heureFin=? where idvisite = ? ";

$paramsV= array($Date,$Heure,$_SESSION['IdVisite']) ;
$stmtV = sqlsrv_query( $conn, $reqUpVi, $paramsV );
if( $stmtV === false ) {
	$errors = sqlsrv_errors();
	$error.="Erreur : modification cloture visite ".$errors[0]['message'] . " <br/> ";

}
			

if($error=="" ) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
		//	jAlert("L\'ajout a été effectuée","Message");			
			$('#box').dialog('close');
			Imprimer("<?php echo $IdFacture;?>");
			window.location='mapClient.php';
			//rechercher();
			
		</script>
		
<?php
unset($_SESSION['lignesCat']);
} else {
     sqlsrv_rollback( $conn );
     echo $error;
}

	exit;
}

if (isset($_GET['VerifStock'])){

	/*$sql = " select sum(stock*idColisage)as Stock			 
			 from stockvendeurs s where idarticle=? and idVendeur=? ";*/
			 	$sql = " select stock as Stock			 
			 from stockvendeurs s where idarticle=? and idVendeur=? ";
				$params= array(
						$_POST['IdArticle'],
						$_SESSION['IdVendeur'],
						//$_POST['Colisage']
						
				) ;
				
		//		echo $sql;
//echo 	$_SESSION['IdVendeur']."br";
//parcourir($params);
$QtetStock=0;
// recuperer la qte cmdée par outer et le comparer par qte du table stockvendeur  qui est enregsitrer par outer
					if($_POST["UniteVente"]=="Palette")
					{
						$QtetStock=str_replace(" ","",$_POST["Qte"])* $_POST["NbrPalette"]* $_POST["NbrBox"];
						
					}else if ($_POST["UniteVente"]=="Box"){
						
						$QtetStock=str_replace(" ","",$_POST["Qte"])* $_POST["NbrBox"];
						
					}else if($_POST["UniteVente"]=="Colisage"){
						$QtetStock=str_replace(" ","",$_POST["Qte"]);
					}
								
  	            $reponse=sqlsrv_query( $conn, $sql, $params, array( "Scrollable" => 'static' ) );	
				if( $reponse === false ) {
					$errors = sqlsrv_errors();
					$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
					return;
				}		
				if(sqlsrv_num_rows($reponse)==0) { echo "0";return;}
					$rowC = sqlsrv_fetch_array($reponse, SQLSRV_FETCH_ASSOC);		
			//	echo ("----".$_POST['Qte']*$_POST['Colisage']."---------".$rowC['Stock']."-----");					
					if(($QtetStock) > $rowC['Stock']) echo "0";
					else echo "1";
				

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
	<div style="height:585px; overflow:scroll;" >

	<DIV class="ListeCmd">
	 	 <div class="enteteL" >
        <div  class="divArticleL"  ><?php echo $trad['label']['Article'];?> </div>
		   <div  class="divArticleL" ><?php echo $trad['label']['Gamme'];?>  </div>	
		<div  class="divColisageL" ><?php echo $trad['label']['unite'];?>  </div>				   
        <div class="divQteL" > <?php echo $trad['label']['Qte'];?>  </div>			
		 <div class="divPVL"> <?php echo $trad['label']['PVHT'];?> (<?php echo $trad['label']['riyal'];?> )  </div>
		
			 <div class="divTTC"> <?php echo $trad['label']['ValTTC'];?>  (<?php echo $trad['label']['riyal'];?> ) </div>
		</div>
  	
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
			if($row['UniteVente']=='Box') echo $trad['label']['Box'];
			if($row['UniteVente']=='Palette') echo $trad['label']['Palette'];
			if($row['UniteVente']=='Colisage') echo $trad['label']['Colisage'];
			
							?> </div> 
						<div class="divQteL" >  <span class="nbr"><?php  echo $row['Qte'];?> </span></div>
						<div class="divPVL" >  <span class="nbr"><?php echo number_format($row['PriceUnite'], 2, '.', ' '); ?> </span> </div> 
						 						
						<div class="divTTC"  > <span class="nbr"><?php  
						$Total=str_replace(" ","",$row['Qte'])*str_replace(" ","",$row['PriceUnite']);
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
<DIV class="bottomVente">
<div class=" divRight" Style>
			<div class="cmd"><?php  echo $trad['label']['TotalGlobal'];?>:
			<input type="text" value=" <?php echo number_format(Total(), 2, '.', ' '); ?> " class="global"  disabled id="txtTotal" size="10"  name="TotalCmdTTC">
			<?php echo $trad['label']['riyal'];?> </div>
		</div>
		<div class="divLeft" Style="width:623px">
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
								if($_POST["UniteVente"]=="Box")
								{
									$ligneArray["PriceUnite"]=floatval($_POST["PV"])*floatval($_POST["NbrBox"]);
											//	echo floatval($_POST["NbrBox"]);return;
								}else if ($_POST["UniteVente"]=="Palette"){
									$ligneArray["PriceUnite"]=floatval($_POST["PV"])*$_POST["NbrPalette"]*floatval($_POST["NbrBox"]);
									
								}else if($_POST["UniteVente"]=="Colisage"){
									$ligneArray["PriceUnite"]=floatval($_POST["PV"]);
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
							/*$ligneArray["TVA"]=$_POST["TVA"];
							$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
							$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;*/
							
								if($_POST["UniteVente"]=="Box")
								{
									$ligneArray["PriceUnite"]=floatval($_POST["PV"])*floatval($_POST["NbrBox"]);
									
								}else if ($_POST["UniteVente"]=="Palette"){
									$ligneArray["PriceUnite"]=floatval($_POST["PV"])*$_POST["NbrPalette"]*floatval($_POST["NbrBox"]);
									
								}else if($_POST["UniteVente"]=="Colisage"){
									$ligneArray["PriceUnite"]=floatval($_POST["PV"]);
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
								if($_POST["UniteVente"]=="Box")
								{
									$ligneArray["PriceUnite"]=floatval($_POST["PV"])*floatval($_POST["NbrBox"]);
									
								}else if ($_POST["UniteVente"]=="Palette"){
									$ligneArray["PriceUnite"]=floatval($_POST["PV"])*$_POST["NbrPalette"]*floatval($_POST["NbrBox"]);
									
								}else if($_POST["UniteVente"]=="Colisage"){
									$ligneArray["PriceUnite"]=floatval($_POST["PV"]);
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
		 }else {?>
			 <script language="javascript" type="text/javascript">
			 slider.goToNextSlide();
			</script>
		<?php }
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
	if( (isset($_SESSION['lignesCat'])) && (count($_SESSION['lignesCat'])!=0))  {
	foreach($_SESSION['lignesCat'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
		
			//$Total+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
		//	$Total+=($contenu["Qte"]*$contenu["PriceUnite"]) ;
		$Total+=str_replace(" ","",$contenu['Qte'])*str_replace(" ","",$contenu['PriceUnite']);

			}
	}
	return($Total);
}
if (isset($_GET['getArticle'])){
	$tabArtilce=array();
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
}

//parcourir($tabArtilce);return;
								
								$IdArticle=$tabArtilce[0]['IdArticle'];
								$dsgArticle=$tabArtilce[0]['DsgArticle'];
								$dsgGamme=$DsgGamme;
								$TVA=$tabArtilce[0]['TVA'];
								$ColisageCmde="";
								$UniteVente="";
								$i=0;
								?>
									<DIV class="haut">
										<div class="divLeftArt">
					<div id="resAdd" ></div>
										<form id="formAddArt" method="post" name="formAddArt"> 
											<TABLE  dir="<?php echo $_SESSION["dir"];?>"  border="0" width="100%" class="table" cellspacing="2" cellpadding="7">
												<tr><TD width="15%"><?php  echo $trad['label']['Ref'];?>:</td><td align="<?php echo $_SESSION["align"];?>">	<?php  echo $tabArtilce[0]['Reference'];?>
												<input type="hidden" value="<?php  echo $IdArticle; ?>" name="IdArticle">
												<input type="hidden" value="<?php  echo stripcslashes($dsgArticle); ?>" name="NomArt">
												<input type="hidden" value="<?php  echo stripcslashes($DsgGamme); ?>" name="Gamme">
												<input type="hidden" value="<?php  echo $TVA; ?>" name="TVA">
												<input type="hidden" value="<?php  echo $tabArtilce[0]['PV'];?>" name="PV">
												<?php if (isset($_GET['list'])){
													
													$key = array_search($_GET['idArticle'],array_column($_SESSION["lignesCat"], 'IdArticle'));
													$Qte=$_SESSION["lignesCat"][$key]["Qte"];
													$UniteVente=$_SESSION["lignesCat"][$key]["UniteVente"];
													?>
												
													<input type="hidden" value="List" name="List">
													<?php } else $Qte=1;?>
												</td></tr>
												<tr><TD ><?php  echo $trad['label']['Dsg'];?>:</td><td  align="<?php echo $_SESSION["align"];?>">	<?php  echo stripcslashes(ucfirst($dsgArticle));?></td></tr>
												<tr><TD><?php  echo $trad['label']['Gamme'];?>:</td><td  align="<?php echo $_SESSION["align"];?>" ><?php  echo stripcslashes(ucfirst($DsgGamme));?></td></tr>
												<tr><TD><?php  echo $trad['label']['SousFamille'];?>:</td><td align="<?php echo $_SESSION["align"];?>"><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
												<tr style="display:none"><TD><?php  echo $trad['label']['Famille'];?>:</td><td align="<?php echo $_SESSION["align"];?>"><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
												<tr><TD><?php  echo $trad['label']['PV'];?>:</td><td align="<?php echo $_SESSION["align"];?>">
												<span class="prix">
												
												<?php  echo $tabArtilce[0]['PV']; 
												echo $trad['label']['riyal'];
												echo " (".$trad['label']['PourColisage'].")";
												?>
												</span>
												</td></tr>
												<tr><TD><?php  echo $trad['label']['unite'];?>:</td>
												<td height="110" align="<?php echo $_SESSION["align"];?>">
												
												<input type="radio" IdLigne="<?php echo $i;?>"  Unite="Palette" class="palette action" name="Colisage"  value="<?php echo $tabArtilce[0]['Palette'];?>" 
												data-labelauty="<?php //echo $trad['label']['Palette']." (".$tabArtilce[0]['Palette'].")";?>|<?php //  echo $trad['label']['Palette']." (".$tabArtilce[0]['Palette'].")";?>" aria-label="1"   />
												
												<input type="radio" IdLigne="<?php echo $i;?>"  Unite="Box" name="Colisage" class="box action"  value="<?php echo $tabArtilce[0]['Box'];?>"  
												data-labelauty="<?php  //echo $trad['label']['Box']." (".$tabArtilce[0]['Box'].")";?>|<?php //  echo $trad['label']['Box']." (".$tabArtilce[0]['Box'].")";?>" aria-label="2"  checked />
												
												<input type="radio" IdLigne="<?php echo $i;?>" Unite="Colisage"  class="colisage action" name="Colisage"  value="<?php echo $tabArtilce[0]['Colisage'];?>" 
												data-labelauty="<?php  //echo $trad['label']['Colisage']." (".$tabArtilce[0]['Colisage'].")";?>|<?php  //echo $trad['label']['Colisage']." (".$tabArtilce[0]['Colisage'].")";?>" aria-label="3"   />
												<input type="hidden"	value="<?php echo $i;?>" class="index" />
												
												<input type="hidden"
													value="Box"
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
												<tr><TD><?php  echo $trad['label']['Qte'];?>:</td><td Valign="top" align="<?php echo $_SESSION["align"];?>"> 
												<input type="text" value="<?php echo $Qte;?>" name="Qte" onkeypress="return isEntier(event) " 
												class="Qte ConvertDecimal nbr" 
												Style="text-align:<?php echo $_SESSION["align"];?>"
												size="4" id="Qte<?php echo $i;?>"><br>
												<input type="button" class=" qtyplus"   id="qtyplus" onclick="Plus(<?php echo $i;?>)"
												value="+" id="btnp">&nbsp;
												<input type="button" class=" qtyplus" id="qtyminus" onclick="Moins(<?php echo $i;?>)" value="-"></td></tr>
												<TR>	
												<td   Valign="middle" colspan="2" height="130">
												<div class="divPRix<?php echo $i;?> divPRix">												
												</div>
												</td>
												
												</tr>
											
											</table>
											</form> 
										</div>
										
										<div class="divRight">
										<img src="../<?php  echo $tabArtilce[0]['UrlArticle'];?>" alt=""  width=" 640" height="596" />	
										<table width="100%">
											<TR>	
												<td   Valign="top" align="center" colspan="2">
												<input type="button" value="<?php echo $trad['button']['Valider'];?>" class="btn"  onclick="AjoutArticle()"/>&nbsp;&nbsp;
												<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="FermerBoxArt()"/>
												</td>
												</tr>
												</table>
										</div>
									</div>
									<?php
					//	}}
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

include("header.php");
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
<div class="bottomVente">
<div class="divLeft">
			<div class="cmd">
			
			<?php echo $trad['label']['TotalGlobal'];?>: <input type="text" value=" 0.000" class="global" id="txtTotal" size="6" disabled> 
			<?php echo $trad['label']['riyal'];?></div>
		</div>
		<div class="divRight" style="width:340px">
			<input type="button" value="<?php echo $trad['button']['ConsultCmd'];?>" class="btnCmd" onclick="ConsultCmd()">
		</div>
		</div>
<div id="box"></div><div id="boxArticle"></div>
<script language="javascript" type="text/javascript">		
// Get the modal

	$("#txtTotal").val("<?php echo number_format(Total(), 2, '.', ' '); ?>");
function ConsultCmd(){
	 var Verif="";
	$.get("catalogue4.php?VerifSession", function(response) {
      Verif = response;
	  // verifier si le vendeur a ajouté des articles
		if(Verif==1){
		var url='catalogue4.php?ConsultCmd';	
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
		var url='catalogue4.php?getArticle&&idArticle='+idArticle;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}
	else{
		//pour afficher liste
		var url='catalogue4.php?getArticle&&list&&idArticle='+idArticle;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	}
	
}


$(document).ready(function(){		
	//$(":radio").labelauty();	
// code pour prendre enconsideration l'hover quand on met le doigt sur l'ecran
$("input[type=button").addClass("hvr-grow");
$('body').bind('touchstart', function() {});

//$("#txtTotal").val("<?php echo number_format("0.00", 2, '.', ' '); ?>");
	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('catalogue4.php?affGamme&&VideFam');
		//$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('catalogue4.php?TypeVente');
$.validator.messages.required = '';
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
							terminer();
						
						}
					 }
			});


		
				$('#boxArticle').dialog({
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

function AfficheGamme1(CurrentSlide){
								
				
					if (CurrentSlide === undefined || CurrentSlide === null) {
					var url='catalogue4.php?affGamme'	}
					else {
							var url='catalogue4.php?affGamme&CurrentSlide='+CurrentSlide;	
					}
			
				$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

							}
function AfficheDetailGamme(id){
	//alert('lll');
	/*var url='catalogue4.php?aff&&Id='+id;	
	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');*/
	var current = slider.getCurrentSlide();
	var url='catalogue4.php?affArti&Id='+id+'&CurrentSlide='+current;	
	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

}
function rechercherFam(){
		//alert('lll');
		var url='catalogue4.php?affFam';	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}
function AfficheSousFam(id){
		//alert(id);
		var url='catalogue4.php?affSousFam&&Id='+id;	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}
function AfficheMarque(id){
		var url='catalogue4.php?affMarque&&Id='+id;	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}

function Fermer(){
	$("#box").dialog('close');
}
function FermerBoxArt(){
	$("#boxArticle").dialog('close');
}
function FermerFenetre(){
	alert("fermer");
	var url='catalogue4.php?FermerFenetre';	
	//$("#box").load(url);
}
function ValideCmdEtape1(){
	/*$('#formCmd').ajaxSubmit({
		target:'#res',
		url:'catalogue4.php?ValideCmd',
			method			:	'post'
		});
		patienter('res');*/
		$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('catalogue4.php?ChoixTypeReg').dialog('open');
		//$('#res').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

}
function Imprimer(IdFacture){
		/*	var adr = 'ficheControle.print.php?IdDmd='+idDmd;
			//alert(adr);
			window.location.href = adr;*/
			 options = "Width=1280,Height=800" ;
		//	 alert(IdFacture);
		//	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('facture.print.php?IdFacture='+IdFacture).dialog('open');

		  window.open( 'facture.printV3.php?IdFacture='+IdFacture, "edition", options ) ;
		
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
									   url : 'catalogue4.php?VerifStock',
									   type : 'POST',
									   dataType : 'html', // On désire recevoir du HTML
									   success : function(code_html, statut){										   
										   Verif=code_html;
										 //alert(Verif);
											if(Verif==1){
											$(form).ajaxSubmit({
																target			:	'#resAdd',
																url				:	'catalogue4.php?goAddArti',
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
	var url='catalogue4.php?affFam&&VideFam&&IdTypeVente='+TypeVente;
	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}
$(document).on('keyup', '.ConvertDecimal', function(e){
   this.value = this.value.replace(/ /g,'');
    var number = this.value;
    this.value = number.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  });
  function CalculPrixArt(IdLigne){
	var Unite =$('input[name=Colisage]:checked').attr("Unite");	
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
		
		
	}if(Unite=="Box"){
		nbrColisage=$('input[id=NbrBox'+IdLigne+']').val()* $("#Qte"+IdLigne).val().replace(" ","");
		PriceUnite=$("#PrixVente"+IdLigne).val()*$('input[id=NbrBox'+IdLigne+']').val();
		PriceUnite=number_format(PriceUnite, 2, ".", " ");
		
		TotalPrice=PriceUnite* $("#Qte"+IdLigne).val().replace(" ","");
		TotalPrice=number_format(TotalPrice, 2, ".", " ");
		
		if($("#Qte"+IdLigne).val().replace(" ","")>2){
			txt="<?php  echo $trad['label']['PrixVente'];?> <span class='nbr'>"+$("#Qte"+IdLigne).val()+"</span> <?php  echo $trad['label']['TroisBox'];?> <span class='nbr'> "+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}else if($("#Qte"+IdLigne).val().replace(" ","")==2){
			txt="<?php  echo $trad['label']['PrixVente']." ".$trad['label']['DeuxBox'];?> <span class='nbr'> "+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}
			
		$(".divPRix"+IdLigne).html("<?php echo  $trad['label']['NbrColisage'] ;?> "+nbrColisage+"<br><?php echo  $trad['label']['PrixVenteBox'] ;?> <span class='nbr'>"+PriceUnite+"</SPAN>  <?php  echo $trad['label']['riyal'];?><br>	"+txt);
		
	}if(Unite=="Colisage"){
		
		nbrColisage=$("#Qte"+IdLigne).val().replace(" ","");
		
		PriceUnite=$("#PrixVente"+IdLigne).val();
		PriceUnite=number_format(PriceUnite, 2, ".", " ");
		
		TotalPrice=PriceUnite* $("#Qte"+IdLigne).val().replace(" ","");
		TotalPrice=number_format(TotalPrice, 2, ".", " ");
		
		if($("#Qte"+IdLigne).val().replace(" ","")>2){
			txt="<br>	<?php  echo $trad['label']['PrixVente'];?> <span class='nbr'>"+$("#Qte"+IdLigne).val()+"</span> <?php  echo $trad['label']['Colisage'];?> <span class='nbr'>"+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}else if($("#Qte"+IdLigne).val().replace(" ","")==2){
				txt="<br>	<?php  echo $trad['label']['PrixVente']." ".$trad['label']['DeuxColisage'];?><span class='nbr'> "+TotalPrice+"</span> <?php  echo $trad['label']['riyal'];?>";
		}
		
		$(".divPRix"+IdLigne).html("<?php echo  $trad['label']['NbrColisage'] ;?> "+nbrColisage+"<br><?php echo  $trad['label']['PrixVenteColisage'] ;?> <span class='nbr'>"+PriceUnite+"</SPAN>  <?php  echo $trad['label']['riyal'];?>"+txt);
		
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
	
</script>

<?php include("footer.php");?>
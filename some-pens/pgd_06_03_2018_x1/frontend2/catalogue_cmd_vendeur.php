<?php

include("../php.fonctions.php");
require_once('../connexion.php');
session_start();
include("lang.php");
$IdDepot=$_SESSION['IdDepot'];


if (isset($_GET['aff'])){ 

/*echo "DsgGamme<br>";
echo "liste des articles pour la gammes:";
return;*/
$tabArtilce=array();
$k=0;
$DsgFamille="";$DsgSousFam="";$DsgGamme="";
$_SESSION['IdGamme']=$_GET['Id'];
//parcourir($_SESSION['lignesCatV']);return;

foreach($_SESSION['lignesCatV'] as $v){//famille
	
		if(is_array($v)){ 
		//	parcourir($v);
		$DsgFamille=$v["DsgFamille"];//echo  $DsgFamille."<br>";return;	
		
			foreach($v as $d){// sous famille
			
						
			if(is_array($d)){//	parcourir($d);return;
			//		echo $d["IdSousFam"]."<br>";
	
					
						$DsgSousFam=$d["DsgSousFam"];
					
						foreach($d as $f){	// marque										
							if(is_array($f)){
								//parcourir($f);return;							
								foreach($f as $g){//gammes									
								
									if(is_array($g)){
										if ($g['IdGamme']==$_GET['Id']){
										//	parcourir($g);return;	
													$DsgGamme=$g["DsgGamme"];
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
		
	//	parcourir($tabArtilce);return;
			/*foreach($g as $r){//article												
					if(is_array($r)){

														//print_r($r);
													//	echo  $DsgGamme."<br>";	
																	}?><?php
				
				}
													*/	?>
											<!--		<input type="button" value=""  class="close2" onclick="Fermer()" Style="float:right;"/>
<div class="clear"></div>-->
		<DIV class="haut">
	
			<TABLE   border="0" width="100%" class="table" cellpadding="6" >	
			<tr><TD valign="middle"><?php echo $trad['label']['Gamme'];?>:</td><td valign="middle"><?php echo  stripcslashes(ucfirst($DsgGamme));?>
				<input type="button" value=""  class="close2" onclick="Fermer()" Style="float:right;"/>
			</td></tr>
			<tr><TD><?php echo $trad['label']['SousFamille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
			<tr><TD> <?php echo $trad['label']['Famille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
				</table>
		
					<DIV class="entete">
						<div class="divArticle" Style="width:943px;" align="center"><?php echo $trad['label']['Article'];?> </div>
						<!--<div class="divPV"  Style="width:250px;" align="center">PV(DH) </div>-->
						<div class="divPV" Style="width:264px;" align="center"><?php echo $trad['label']['Action'];?> </div>
					</div>
						<div class="clear"></div>
				<div class="DivListArt">				
				<?php 
				$key="";$c="";

					foreach($tabArtilce as $r){//article												
													if(is_array($r)){
							?>		
								<div class="ligne " >
									<div class=" <?php echo $c;?> divArticle " Style="width:963px;" align="center"><?php  echo stripcslashes(ucfirst($r['DsgArticle']));?>					
									</div>
										<!--<div class="divPV" Style="width:255px;"> <?php  echo $r['PV'];?> </div>  -->
									<div class="divCmd" Style="width:284px;TEXT-align:center;background:#fff;" ><input type="button" value="<?php echo $trad['button']['Commander'];?>" 
									onclick="getArticle('<?php  echo $r['IdArticle'];?>')"
									class="btnCmdArt"></div>
								</div>
								<div class="clear"></div>
				<?php 	}}?>									
			</div>
		
	
	
			<!--img src="../<?php // echo $v['url'];?>" alt=""  width=" 638" height="662" / -->					

		<div class="clear"></div>
		
		</div>
		<div Style="margin:0 auto;text-align:center;">
				<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="Fermer() "  />
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
<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>
<div >&nbsp;&nbsp;
 <span  Class="TitleHead" onclick="CmdDepot();"><?php echo $trad['index']['CmdDepot'];?></span>

</div>&nbsp;> <?php  echo $trad['titre']['Gamme'];?></div>
<?php //echo $_SESSION['IdFamille']."___".$_SESSION['IdSousFam']."___".$_SESSION['IdMarque'] ; ?>
<div class="clear"></div>
	<?php 
							//	parcourir($_SESSION['lignesCatV']);return;
							$key ="";
				if( (isset($_SESSION['lignesCatV'])) && (count($_SESSION['lignesCatV'])!=0))  {//famille?>
<ul class="bxslider" style="margin:0;padding:0;">
					<?php 	
				//echo $_SESSION['IdFamille'];return;
				
					$i=1;
						foreach($_SESSION['lignesCatV'] as $v){// famille
							if(is_array($v)){ 
							
								foreach($v as $d){//sous famille
										if(is_array($d)){//	parcourir($d);return;
									
											foreach($d as $f){	//marque										
												if(is_array($f)){
													//echo $_GET['Id'];
												//echo $_SESSION['IdSousFam']."___".$d["IdSousFam"];
										
														foreach($f as $r){//gamme		
																if(is_array($r)){
																	//	parcourir($r);//	return;
																//	echo $r['DsgGamme'];;
															?>
																<li>
													<img src="../<?php  echo $r['UrlGamme'];?>" alt=""   width=" 100%" height="450" 
													style="position : relative ;  "/>	
																												
															 <input type="button" class="DetailGamme" 
															 value="<?php echo $trad['button']['VoirArticles'];?>  "  
													
															 onclick="AfficheDetailGamme('<?php echo $r['IdGamme'];?>')" />
															  </li>
															 
															<?php
																}
		 
													}
											
												//echo $f['IdMarque'];return;
										
												}
											}
											
									}
							}
							}
							}
							?>
		
</ul>	<?php
														
				}
				else { ?>
<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
										<?php  echo $trad['msg']['AucunResultat'];?>
							</div>
<?php }		
	
	
			?><?php include("bottom_cmd_vdr.php");	?>	
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  
$(document).ready(function () {
	var i = 0;
	$('div.cadreSouf').each(function() {
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
&nbsp;&nbsp; 
 <span  Class="TitleHead" onclick="CmdDepot();"><?php echo $trad['index']['CmdDepot'];?></span>&nbsp;>&nbsp;
<span  class="TitleHead" onclick="rechercherFam()"> <?php  echo $trad['titre']['Famille'];?></span> &nbsp; 
>&nbsp; 
<span  class="TitleHead" onclick="AfficheSousFam(<?php echo $_SESSION['IdFamille'];?>)"><?php  echo $trad['titre']['SousFamille'];?></span>&nbsp; 
>&nbsp;
<span  class="" ><?php  echo $trad['titre']['Marque'];?></span>
</div>
<div class="clear"></div>
<?php //echo $_SESSION['IdFamille']."___".$_SESSION['IdSousFam']; ?>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							$key ="";
				if( (isset($_SESSION['lignesCatV'])) && (count($_SESSION['lignesCatV'])!=0))  {
					?>
					<?php	
					$i=1;
					
						foreach($_SESSION['lignesCatV'] as $v){	//	famillesif(is_array($v)){	parcourir($v);}
							if(is_array($v)){ }
								if($v['IdFamille']==$_SESSION['IdFamille']){
								foreach($v as $d){//sous famille
										if(is_array($d)){	
										//echo $v['IdFamille'];
											if($d['IdSousFam']==$_GET['Id']){
												
												foreach($d as $r){											
												
													if(is_array($r)){														
														//parcourir($r);	return;																						
														if( $i==1) echo " <li><div style='text-align:left'>" ;									
														
														?>
														  <div class="cadreMarque hvr-grow"
														 
														  onclick="AfficheGamme1('<?php echo $r['IdMarque'];?>')">
														  <div  class="childSouFam"> 
														  <img src="../<?php echo $r['UrlMarque'];?>"  width="550" height="250"/>
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
		</ul><?php include("bottom_cmd_vdr.php");	?>
	
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
&nbsp;&nbsp; 
<span  Class="TitleHead" onclick="CmdDepot();"><?php  echo $trad['index']['CmdDepot'];?></span>&nbsp;>
 <span  Class="TitleHead" onclick="rechercherFam() ">&nbsp;<?php  echo $trad['titre']['Famille'];?></span>
&nbsp;> <?php  echo $trad['titre']['SousFamille'];?>
</div></div>
<div class="clear"></div>
<?php //echo $_SESSION['IdFamille']; ?>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							$key ="";
				if( (isset($_SESSION['lignesCatV'])) && (count($_SESSION['lignesCatV'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['lignesCatV'] as $v){	//	if(is_array($v)){	parcourir($v);}
						
							
									if(is_array($v)){
											if($v['IdFamille']==$_GET['Id']){
												//	parcourir($v);return;
											foreach($v as $r){
												
												if(is_array($r)){
												
													if( $i==1) echo " <li><d iv style='text-align:left'>" ;												
													
													?>
													  <div class="cadreSouf hvr-grow" style="" onclick="AfficheMarque('<?php echo $r['IdSousFam'];?>')">
													  <div  class="childSouFam"> <?php 	echo ucfirst($r['DsgSousFam']);?></div>
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
		</ul><?php include("bottom_cmd_vdr.php");	?>
	
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
unset($_SESSION['IdFamille']);
unset($_SESSION['IdSousFam']);
unset($_SESSION['IdMarque']);
unset($_SESSION['IdGamme']);

?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>&nbsp;&nbsp;
<div Class="" >
 <span  Class="TitleHead" onclick="CmdDepot();"><?php echo $trad['index']['CmdDepot'];?></span>&nbsp;>
 <span  Class=""><?php echo $trad['label']['Famille'];?></span>
 </div></div>
<ul class="bxslider" style="margin:0;padding:0;">

<?php 
//echo "mmm";return;

$k=0;
	$i=1;
	foreach($_SESSION['lignesCatV'] as $u=>$v){	
		//echo "--------<li>".$k."</li>";
		// recherche pour ne pas dubliquer la couleur du cadre
		
		if( $i==1) echo " <li><div style='text-align:center'>" ;	
		?>
		  <div class="cadre hvr-grow"  onclick="AfficheSousFam('<?php echo $v['IdFamille'];?>')">
			<div  class="child"> <?php 	echo mb_ucfirst($v['DsgFamille']);?></div>
		  </div>
		  
		<?php
		if($i==2) {?> <div class="clear"></div><?php }
			//condition pour afficher 4 familles par page
			if ($i == 4) {  echo " </div></li>" ; $i=1;}
			else {				$i+=1;}
	 //  echo $i;
	}
?>
	</ul>			
<?php include("bottom_cmd_vdr.php");	?>
<script language="javascript" type="text/javascript">
  // initialize bxSlider  
$(document).ready(function () {
	var i = 0;
	$('div.cadre').each(function() {
    /*var rand = back[Math.floor(Math.random() * back.length)];
    console.log(rand);
    $('.cadre').css('background',rand);*/
//	var item =  jQuery.rand(back);

 $(this).css("background-image", "url(../images/transparent.png)");
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
if (isset($_GET['ChoixTypeReg'])){
?>
<br><br><br>
<form id="formReg" action="" method="post" name="formReg" >
<div id="resReg"></div>
			<TABLE   border="0" width="60%" class="table" cellpadding="15"  align="center" >
				<tr><TD align="right">Montant :</td><td>	
					
					<div class="TotalReg"><?php echo number_format(Total(), 2, '.', ' '); ?></div>					
					
			</td></tr>
			<tr><TD align="right">Type règlement :</td><td>	
					<select style="display:block;width:300px"    tabindex="2" g="select" name="TypeReg" id="TypeReg">
						<option value="Espece" selected>Espèces</option>
					<option value="Cheque"  >Chèque</option> 			
					<option value="Virement">Virement</option>
					</select>
			</td></tr>
				<tr  >
				<TD  align="right" colspan="2">
					<DIV class="bq">
					<table  cellpadding="4"  cellspacing="4" border="0" width="80%" align="center">
						<tr>
								<td align="right">
								Banque :</td>
								<td>	
								<select style="display:block;width:350px" tabindex="2" g="select" name="Bq" id="TYPE2_REG">
									<option value="bp" selected>Banque populaire</option>
								<option value="Cheque">BMCI</option> 			
								<option value="Virement">SOGE</option>
								</select>
								</td>
						</tr
							<tr>
								<td align="right">
								Numéro :</td>
								<td>	
								<input type="text" value=" " class="" id="" size="25" name="Num">
								</td>
						</tr>
					</table>
					</div>
					</td>
				</tr>
				<TR >	
				<td   Valign="top" align="center" colspan="2"><br><br>
				<input type="button" value="Annulé"  class="btn" onclick="FermerBoxArt()"/>&nbsp;&nbsp;
				<input type="button" value="Terminer" class="btn"  onclick="TerminerCmd()"/>
				
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
							url:'catalogue_cmd_vendeur.php?TerminerCmd',
								method			:	'post'
							});
		patienter('resReg');
		}

	}
	</script>	
<?php
	exit;
}

if (isset($_GET['VerifStock'])){
					
			$key = array_search($_POST['IdArticle'],array_column($_SESSION["StockV"], 'IdArticle'));
			$QteStock=$_SESSION["StockV"][$key]["StockArticle"];
			//echo $QteStock;
			if(($_POST['Qte']*$_POST['Colisage']) > $QteStock) echo "0";
			else echo "1";			

	   exit;				
}
if (isset($_GET['VerifSession'])){
	
	   if( (!isset($_SESSION['lignesCmd'])) || (count($_SESSION['lignesCmd'])==0))  
		   echo "0";
	   else echo "1";
	   exit;
				
}
if (isset($_GET['FermerFenetre'])){
		/*$soap = new SoapClient('http://'.$ip.'/WebServicePos/Service.asmx?wsdl', array('exceptions' => true));
		$result = $soap->ArretProcessus_POSC();
	exit;*/
}

if (isset($_GET['goAddArti'])){	
//parcourir($_POST);return;
$Tva=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"] * $_POST["TVA"]) /100;	
	if( (isset($_SESSION['lignesCmd'])) && (count($_SESSION['lignesCmd'])!=0))  {
	//   echo count($_SESSION["lignesCmd"]);
		$t=0;	
		$Total=0;
			  foreach($_SESSION['lignesCmd'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
				$Total+=$contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"];
					if(($contenu["IdArticle"]==$_POST["IdArticle"]))
					{
								$IndexLigne=$t;
								$ligneArray["IdDtlCmdVdr"]=$_POST["IdDtlCmdVdr"];
								$ligneArray["IdLigne"]=$IndexLigne;
								$ligneArray["IdArticle"]=$_POST["IdArticle"];
								$ligneArray["NomArt"]=$_POST["NomArt"];
								$ligneArray["Qte"]=$_POST["Qte"];
								$ligneArray["PV"]=$_POST["PV"];
								$ligneArray["Gamme"]=$_POST["Gamme"];
								$ligneArray["Colisage"]=$_POST["NbrBox"];
								$ligneArray["TVA"]=$_POST["TVA"];
								$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
								$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
								$_SESSION['lignesCmd'][$IndexLigne]= $ligneArray;
								
						$t=0;break;				
					}
					else {					
						$t+=1;						
					}
			  }
			  // si l'article avec mm qte n'existe pas on l'ajoute
			  if($t!=0){
			  			$IndexLigne=count($_SESSION['lignesCmd']);							
						// si la qte et tarif vide en l'ajoute pas
							$ligneArray["IdDtlCmdVdr"]="";
							$ligneArray["IdLigne"]=$IndexLigne;
							$ligneArray["IdArticle"]=$_POST["IdArticle"];
							$ligneArray["NomArt"]=$_POST["NomArt"];
							$ligneArray["Qte"]=$_POST["Qte"];
							$ligneArray["PV"]=$_POST["PV"];
							$ligneArray["Gamme"]=$_POST["Gamme"];
							$ligneArray["TVA"]=$_POST["TVA"];
							$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
							$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
							$ligneArray["Colisage"]=$_POST["NbrBox"];
							$_SESSION['lignesCmd'][$IndexLigne]= $ligneArray;
						
						
			  }
		  }
		  else {// une premiere insertion sans controle
						 //$IndexLigne+=1;
		
								$IndexLigne=0;
								$ligneArray["IdDtlCmdVdr"]="";
								$ligneArray["IdLigne"]=$IndexLigne;
								$ligneArray["IdArticle"]=$_POST["IdArticle"];
								$ligneArray["NomArt"]=$_POST["NomArt"];
								$ligneArray["Qte"]=$_POST["Qte"];
								$ligneArray["PV"]=$_POST["PV"];
								$ligneArray["Gamme"]=$_POST["Gamme"];
								$ligneArray["TVA"]=$_POST["TVA"];
								$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
								$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
								$ligneArray["Colisage"]=$_POST["NbrBox"];
								$_SESSION['lignesCmd'][$IndexLigne]= $ligneArray;
					
		  }
		    
		  //	parcourir($_SESSION['lignesCmd']);
		 if ((isset($_POST['List']))  && ($_POST['List']=="Actualiser")) {
			 ?>
						<script language="javascript" type="text/javascript">
							$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('cmd_vendeur.php?StockVdr');
						</script>
						<?php
			}
		
		 ?>
			<script language="javascript" type="text/javascript">
				if ($('#box').dialog('isOpen') === true) {	
							ConsultCmd();
				}
			</script>
			<?php
		// }
?>
<script language="javascript" type="text/javascript">
		
		  	$("#boxArticle").dialog('close');
</script>
<?php
	exit;
}
function Total(){
	$Total=0;
	
	
	
	
	
	
	if( (isset($_SESSION['lignesCmd'])) && (count($_SESSION['lignesCmd'])!=0))  {
	foreach($_SESSION['lignesCmd'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
			$Tva=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"] * $contenu["TVA"]) /100;
			
			$Total+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
			}
	}
	return($Total);
}
if (isset($_GET['getArticle'])){

	$tabArtilce=array();
	/*echo "id article".$_GET['idArticle'];*/
//parcourir($_SESSION['lignesCatV']);

foreach($_SESSION['lignesCatV'] as $v){//famille
	
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
	//parcourir($_SESSION['lignesCatV']);return;
				$sql2 = "SELECT idDetailCommandeVendeur IdDtlCmdVdr
						FROM detailCommandeVendeurs dc  
						left join commandeVendeurs cv on cv.idCommandeVendeur=dc.idCommandeVendeur
						WHERE
						dc.idArticle=?  and cv.etat=0	 	and dc.idDepot=".$_SESSION['IdDepot']." 
						and cv.idVendeur = ".$_SESSION['IdVendeur']." ";
						$params1= array($tabArtilce[0]['IdArticle']) ;
				$stmt2 = sqlsrv_query( $conn, $sql2, $params1,array( "Scrollable" => SQLSRV_CURSOR_KEYSET ) );	
			//echo $sql2."___".$tabArtilce[0]['IdArticle'];
				if( $stmt2 === false ) {
					$errors = sqlsrv_errors();
					echo "Erreur : get id detail commande ".$errors[0]['message'] . " <br/> ";
					return;
				}else 
				{
					$nbr = sqlsrv_num_rows($stmt2);	
					if ($nbr!=0){
						sqlsrv_fetch($stmt2) ;
						$IdDtlCmdVdr = sqlsrv_get_field( $stmt2, 0);
						}
					else { $IdDtlCmdVdr = "";}
					//$IdDtlCmdVdr=$tabArtilce[0]['IdDtlCmdVdr'];
				}

								
								$IdArticle=$tabArtilce[0]['IdArticle'];
								$dsgArticle=$tabArtilce[0]['DsgArticle'];
								$dsgGamme=$DsgGamme;
								$TVA=$tabArtilce[0]['TVA'];
								$ColisageCmde="";$i=0;$Qte=1;
								?>
									<DIV class="haut">
										<div class="divLeftArt">
										<div id="resAdd"></div>
										<form id="formAddArt" method="post" name="formAddArt"> 
											<TABLE   border="0" width="100%" class="table" cellspacing="2" cellpadding="3">
												<tr><TD width="25%"><?php echo $trad['label']['Ref'];?>:</td><td>	<?php  echo $tabArtilce[0]['Reference'];?>
												<input type="hidden" value="<?php  echo $IdDtlCmdVdr; ?>" name="IdDtlCmdVdr">
												<input type="hidden" value="<?php  echo $IdArticle; ?>" name="IdArticle">
												<input type="hidden" value="<?php  echo stripcslashes($dsgArticle); ?>" name="NomArt">
												<input type="hidden" value="<?php  echo stripcslashes($dsgGamme); ?>" name="Gamme">
												<input type="hidden" value="<?php  echo $TVA; ?>" name="TVA">
												<input type="hidden" value="<?php  echo $tabArtilce[0]['PV'];?>" name="PV">
												<!-- input pour actualiser liste des articles cmdés-->
													<input type="hidden" value="<?php
														if (isset( $_GET['Actualiser'])) echo "Actualiser";?>" name="List">
												<?php //} else {$Qte=1;}?>
												</td></tr>
												<tr><TD ><?php echo $trad['label']['Dsg'];?>:</td><td>	<?php  echo stripcslashes(ucfirst($dsgArticle));?></td></tr>
												<tr><TD><?php echo $trad['label']['Gamme'];?>:</td><td><?php  echo stripcslashes(ucfirst($dsgGamme));?></td></tr>
												<tr><TD><?php echo $trad['label']['SousFamille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
												<tr><TD><?php echo $trad['label']['Famille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
												<tr><TD><?php echo $trad['label']['PV'].' ('.$trad['label']['riyal'].')';?>:</td><td><?php  echo $tabArtilce[0]['PV'];?></td></tr>
												
												<tr class="chpinvisible"><TD><?php  echo $trad['label']['unite'];?>:</td><td height="110" align="<?php echo $_SESSION["align"];?>">
												
											
												<input type="radio" IdLigne="<?php echo $i;?>"  Unite="Box" name="Colisage" class="box"  value="<?php echo $r['Box'];?>"  
												data-labelauty="<?php  //echo $trad['label']['Box']." (".$r['Box'].")";?>|<?php //  echo $trad['label']['Box']." (".$r['Box'].")";?>" aria-label="2"  checked />
												
												<input type="text"	value="<?php echo $i;?>" class="index" />
												
												<input type="text"
													value="Box"
												class="UniteVente" name="UniteVente" />
												
												<input type="text"
													value="<?php echo $tabArtilce[0]['PV'];?>"
												Id="PrixVente<?php echo $i;?>"  />
												
												<input type="text"
													value="<?php echo $tabArtilce[0]['Palette'];?>"
												Id="NbrPalette<?php echo $i;?>" name="NbrPalette" />
											
												
												<input type="text"
													value="<?php echo $tabArtilce[0]['Box'];?>"
												Id="NbrBox<?php echo $i;?>" name="NbrBox" />
												
												<input type="text"
													value="<?php echo $tabArtilce[0]['Colisage'];?>"
												Id="NbrColisage<?php echo $i;?>" name="NbrColisage" />
												
												
												</td></tr>
												
												
												<tr><TD><?php echo $trad['label']['Qte'].' ('.$trad['label']['Box'].')';?>:</td><td Valign="top">
												<?php
												if(isset($_SESSION["lignesCmd"])){
													$key = array_search($_GET['idArticle'],array_column($_SESSION["lignesCmd"], 'IdArticle'));
													
													
														
														if ($key !== false) {
															$Qte=$_SESSION["lignesCmd"][$key]["Qte"];
															$ColisageCmde=$_SESSION["lignesCmd"][$key]["Colisage"];
														} else {
															$Qte=1;
														}

													}
													?>
												<input type="text" value="<?php echo $Qte;?>" name="Qte" onkeypress="return isEntier(event) " 
												class="Qte" size="4" id="Qte<?php echo $i;?>"><br>
												<input type="button" class=" qtyplus"  id="qtyplus" value="+" id="btnp">&nbsp;
												<input type="button" class=" qtyplus" id="qtyminus"  value="-"></td></tr>
												<TR>	
												<td   Valign="middle" colspan="2" height="100">
												<div class="divPRix<?php echo $i;?> divPRix">												
												</div>
												</td>
												
												</tr>
												<TR>	
												<td   Valign="top" align="center" colspan="2">
												<input type="button" value="<?php echo $trad['button']['Valider'];?>" class="btn"  onclick="AjoutArticle()"/>&nbsp;&nbsp;
												<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="FermerBoxArt()"/>
												</td>
												</tr>
											</table>
											</form> 
										</div>
										
										<div class="divRight"><BR><BR><BR>
										<Center><img src="../<?php  echo $tabArtilce[0]['UrlArticle'];?>" alt=""  width=" 350" height="470" />	</center>
										</div>
									</div>
									<?php
						
					
?>
<script language="javascript" type="text/javascript">
$('input[type="radio"]').click(function(){
	var IdLigne;
    if ($(this).is(':checked'))
    {	
		$(".UniteVente").val($(this).attr("Unite"));
		
    }
	CalculPrixArt(0);
  });
  
$(document).ready(function(){		
	$(":radio").labelauty();
		CalculPrixArt(0);
	
  $('#qtyplus').click(function(e){
		var fieldId="Qte0";
        // Stop acting like a button
        e.preventDefault();
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
    });
	    $('#qtyminus').click(function(e){
		var fieldId="Qte0";
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
			CalculPrixArt(0);
    });
});


function AjoutArticle(){

	    $('#formAddArt').validate({
                                              
                                   rules: {
                                               
                                                'Qte': "required",
												'Colisage': "required"
                                           }  
										   
										    });
											
var test=$('#formAddArt').valid();

		if((test==true) ){		
			 jConfirm('<?php echo $trad['msg']['ConfirmerAddAr'];?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
					if(r)	{
							var Verif="";							 
							 	$('#formAddArt').ajaxSubmit({
																target			:	'#resAdd',
																url				:	'catalogue_cmd_vendeur.php?goAddArti',
																method			:	'post'
							}); 
						   
						   

												
						}
				})
		}
}
</script>
<?php
		exit;
}


?>


<script language="javascript" type="text/javascript">		
// Get the modal

	$("#txtTotal").val("<?php echo number_format(Total(), 2, '.', ' '); ?>");


$(document).ready(function(){		
	//$(":radio").labelauty();	
// code pour prendre enconsideration l'hover quand on met le doigt sur l'ecran
$("input[type=button").addClass("hvr-grow");
$('body').bind('touchstart', function() {});

$("#txtTotal").val("<?php echo number_format("0.00", 2, '.', ' '); ?>");
$.validator.messages.required = '';

		 // This button will increment the value
$('input[type=text]').on('focus', function() { 
  console.log($(this).attr('id') + ' just got focus!!');
  window.last_focus = $(this);
});
});


function Fermer(){
	$("#box").dialog('close');
}
function FermerBoxArt(){
	$("#boxArticle").dialog('close');
}
function FermerFenetre(){
	alert("fermer");
	var url='catalogue_cmd_vendeur.php?FermerFenetre';	
	//$("#box").load(url);
}
 
</script>

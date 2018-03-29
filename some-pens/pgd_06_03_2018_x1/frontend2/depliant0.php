<?php

include("../php.fonctions.php");
require_once('../connexion.php');

if(!isset($_SESSION))
{
session_start();
} 
$IdDepot=$_SESSION['IdDepot'];
include("lang.php");
if (isset($_GET['IdTypeVente'])) $_SESSION['IdTypeVenteD']= $_GET['IdTypeVente'];
if (isset($_GET['affArti'])){

/*echo "DsgGamme<br>";
echo "liste des articles pour la gammes:";
return;*/
$tabArtilce=array();
$k=0;
$DsgFamille="";$DsgSousFam="";$DsgGamme="";
//parcourir($_SESSION['Depliant']);return;
foreach($_SESSION['Depliant'] as $v){//famille
	
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
										
									<div class="hautArt">
									<input type="button" value=""   style="float:right"
												id="BtnFermer<?php echo $i;?>"
												class="CloseArt"  
												onclick="AfficheGamme1('<?php echo $_GET['CurrentSlide'];?>')"/>
									</div>
									<div class="clear"></div>
										<div class="divLeftArt" style="width:500px;">
					
										<form id="formAddArt<?php echo $i;?>" method="post" name="formAddArt"> 
											<TABLE   border="0" width="100%" class="table" dir="<?php echo $_SESSION['dir'];?>" cellspacing="3" cellpadding="13">
												<tr><TD width="25%" ><?php echo $trad['label']['Ref'];?>:</td><td align="<?php echo  $_SESSION['align'];?>">	<?php  echo $r['Reference'];?>
												<input type="hidden" value="<?php  echo $IdArticle; ?>" name="IdArticle">
												<input type="hidden" value="<?php  echo stripcslashes($dsgArticle); ?>" name="NomArt">
												<input type="hidden" value="<?php  echo stripcslashes($DsgGamme); ?>" name="Gamme">
												<input type="hidden" value="<?php  echo $TVA; ?>" name="TVA">
												<input type="hidden" value="<?php  echo $r['PV'];?>" name="PV">
												<?php if (isset($_GET['list'])){	
													$key = array_search($_GET['idArticle'],array_column($_SESSION["lignesCat"], 'IdArticle'));
													$Qte=$_SESSION["li gnesCat"][$key]["Qte"];?>
													<input type="hidden" value="List" name="List">
													<?php } else $Qte=1;?>
												</td></tr>
												<tr><TD ><?php echo $trad['label']['Dsg'];?>:</td><td align="<?php echo  $_SESSION['align'];?>">	<?php  echo stripcslashes(ucfirst($dsgArticle));?></td></tr>
												<tr><TD><?php echo $trad['label']['Gamme'];?>:</td><td align="<?php echo  $_SESSION['align'];?>"><?php  echo stripcslashes(ucfirst($DsgGamme));?></td></tr>
												<tr><TD><?php echo $trad['label']['SousFamille'];?>:</td><td align="<?php echo  $_SESSION['align'];?>"><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
												<tr><TD><?php echo $trad['label']['Famille'];?>:</td><td align="<?php echo  $_SESSION['align'];?>"><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
												<tr><TD><?php echo $trad['label']['PV']." (".$trad['label']['riyal'].")";?>:</td><td align="<?php echo  $_SESSION['align'];?>"><?php  echo $r['PV'];?></td></tr>
												<tr><TD><?php  echo $trad['label']['unite'];?>:</td><td height="" align="<?php echo $_SESSION["align"];?>">
												<div style="background:pink; font-size:22px;text-align:center;"><?php //echo $trad['label']['Palette']."<strong> (".$r['Palette'].			")</strong><br>";
												echo $trad['label']['Box']." <strong> (".$r['Box'].") </strong><br>";
												echo $trad['label']['Piece']." <strong> (".$r['Colisage'].")<strong>";
												?>
												</div>
												</td></tr>
												
											</table>
											</form> 
										</div>
										
										<div class="divRight" style="width:450px;">
										
										<img src="../<?php  echo $r['UrlArticle'];?>" alt=""
										Style="margin-top:0px;" width=" 350" height="470" />	
										</div>
									</div>
															  </li>
															 
															<?php
																}
		 
														}
														
				
			?>
		</ul><?php		   
					//$_SESSION['lignesSousFam']=$groups;
				//parcourir($_SESSION['Depliant']);return;

			// fin bdd plein
//}// fin if isset session


?>	
	
<script language="javascript" type="text/javascript">
 

$(document).ready(function () {		
	$(":radio").labelauty();

		
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

    }
	
	
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
//parcourir($_SESSION['Depliant']);return;
foreach($_SESSION['Depliant'] as $v){//famille
	
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
			<tr><TD>Gamme:</td><td><?php echo  stripcslashes(ucfirst($DsgGamme));?></td></tr>
			<tr><TD>Sous Famille:</td><td><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
			<tr><TD> Famille:</td><td><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
				</table>
		
					<DIV class="entete">
						<div class="divArticle" Style="width:663px;" align="center">Article </div>
						<div class="divPV"  Style="width:250px;" align="center">PV(DH) </div>
						<div class="divPV" Style="width:264px;" align="center">Action </div>
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
				<input type="button" value="Fermer"  class="btn" onclick="Fermer() "  />
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

//parcourir($_GET);//return;
$CurrentSlide=0;

//$idArticle=$_GET['idArticle'];
	if(isset($_GET['VideFam'])){
	
}

unset($_SESSION['IdFamille']);
unset($_SESSION['IdSousFam']);
unset($_SESSION['IdMarque']);
unset($_SESSION['IdGamme']);
$timestamp_debut = microtime(true);
//unset($_SESSION['Depliant']);

if(!isset($_SESSION['Depliant'])){

	//selectionner les familles des chargements valide	
	$sql="select 
						a.IdArticle	,me.url UrlArticle,a.reference Reference ,CB codeABarre,colisagee Colisage, co.palette Palette,co.box Box,a.unite,a.TVA,
						fa.Designation DsgFamille,
						fa.UrlFamille UrlFamille,
						sf.Designation as dsgSousFamille,
					   sf.UrlSousFamille UrlSousFamille,
						m.Designation DsgMarque,
						g.Designation as dsgGamme ,
						a.Designation DsgArticle,

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
			where 	
			f.TypeVente=?
			and ( m.idMarque=17 or m.idMarque=18 or  m.idMarque=1017) AND f.etat=1	
		
     	group by 
		a.IdArticle	,a.unite,
		me.url ,a.reference,CB ,colisagee ,a.TVA,
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
						fa.idFamille ,fa.codeFamille 	, co.palette ,co.box 
		 order BY   fa.idFamille ASC";
	
		 $params = array($_SESSION["IdTypeVenteD"]);	

//echo $sql;
		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
								}
		$nRes = sqlsrv_num_rows($stmt);	


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
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['TVA'] =$row['TVA'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Unite'] =$row['unite'];

														$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Palette'] =$row['Palette'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Box'] =$row['Box'];	
													//}
											$i=$i+1;		
										//}
										}
				
					$_SESSION['Depliant']=$groups;


			 }// fin bdd plein
}
if(isset( $_GET['CurrentSlide'])) $CurrentSlide=$_GET['CurrentSlide'];
?>	
<style>
.bxslider{margin-top:0;}
</style>
<DIV style="  display:flex;  align-items:center;" class="headVente"><a href="index.php">
<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>
&nbsp;>  <?php  echo $trad['titre']['Gamme'];?></div>
<?php //echo $_SESSION['IdFamille']."___".$_SESSION['IdSousFam']."___".$_SESSION['IdMarque'] ; ?>
<div class="clear"></div>
<DIV  style="margin:0px;">
<ul class="bxslider">
							<?php 
							//	parcourir($_SESSION['Depliant']);return;
							$key ="";
				if( (isset($_SESSION['Depliant'])) && (count($_SESSION['Depliant'])!=0))  {//famille
				//echo $_SESSION['IdFamille'];return;
				
					$i=1;
						foreach($_SESSION['Depliant'] as $v){// famille
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
													<img src="../<?php  echo $r['UrlGamme'];?>" alt="<?php  echo $r['IdGamme'];?>"  width=" 100%" height="515" 
													style="position : relative ;  "/>	
													<DIV STYle="">											
															 <input type="button" class="DetailGamme" 
															 value="<?php echo $trad['button']['VoirArticles'];?>  "  
													
															 onclick="AfficheDetailGamme('<?php echo $r['IdGamme'];?>')" />
															 </div>
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
						
											
						
											<?php
														
				}
			?>
		</ul>
		</div><?php		   
					//$_SESSION['lignesSousFam']=$groups;
				//parcourir($_SESSION['Depliant']);return;

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
				if( (isset($_SESSION['Depliant'])) && (count($_SESSION['Depliant'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['Depliant'] as $v){	//	famillesif(is_array($v)){	parcourir($v);}
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
														  <img src="../<?php echo $r['UrlMarque'];?>"  width="225" height="225"/>
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
				//parcourir($_SESSION['Depliant']);return;

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
<div  onclick="rechercherFam() " >&nbsp;> <span  Class="TitleHead"><?php  echo $trad['titre']['Famille'];?>
</span></div>&nbsp;><?php  echo $trad['titre']['SousFamille'];?> </div>
<div class="clear"></div>
<?php //echo $_SESSION['IdFamille']; ?>
<ul class="bxslider" style="margin:0;padding:0;">
							<?php 
							$key ="";
				if( (isset($_SESSION['Depliant'])) && (count($_SESSION['Depliant'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['Depliant'] as $v){	//	if(is_array($v)){	parcourir($v);}
						
							
									if(is_array($v)){
											if($v['IdFamille']==$_GET['Id']){
												//	parcourir($v);return;
											foreach($v as $r){
												
												if(is_array($r)){
												
													if( $i==1) echo " <li><div style='text-align:left'>" ;
													
													
													?>
													  <div class="cadreIndex hvr-grow"  style="width:225px;max-width: 226px;"  onclick="AfficheMarque('<?php echo $r['IdSousFam'];?>')">
													  <div  class="childIndex" style="width:225px;max-width: 225px;" > 
													  
													<img src="../<?php echo $r['UrlSousFamille'];?>"  width="225" height="226"/>
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
				//parcourir($_SESSION['Depliant']);return;

			// fin bdd plein
//}// fin if isset session


?>	
	
<script language="javascript" type="text/javascript">
  // initialize bxSlider
  
$(document).ready(function () {

	var i = 0;
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
		//unset($_SESSION['Depliant']);
		// vider le catalogue
}
unset($_SESSION['IdFamille']);
unset($_SESSION['IdSousFam']);
unset($_SESSION['IdMarque']);
unset($_SESSION['IdGamme']);
$timestamp_debut = microtime(true);
unset($_SESSION['Depliant']);

if(!isset($_SESSION['Depliant'])){

	//selectionner les familles des chargements valide	
	$sql="select 
						a.IdArticle	,me.url UrlArticle,a.reference Reference ,CB codeABarre,colisagee Colisage,a.unite,a.TVA,
						fa.Designation DsgFamille,
						fa.UrlFamille UrlFamille,
						sf.Designation as dsgSousFamille,
					   sf.UrlSousFamille UrlSousFamille,
						m.Designation DsgMarque,
						g.Designation as dsgGamme ,
						a.Designation DsgArticle,

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
			where f.idTypeClient=1 and f.type like 'Groupe' and f.etat=1
		
     	group by 
		a.IdArticle	,a.unite,
		me.url ,a.reference,CB ,colisagee ,a.TVA,
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
					
		order BY   fa.idFamille ASC";

		 $params = array();	

//echo $sql;
		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
								}
		$nRes = sqlsrv_num_rows($stmt);	


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
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['TVA'] =$row['TVA'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Unite'] =$row['unite'];
													//}
											$i=$i+1;		
										//}
										}
				
					$_SESSION['Depliant']=$groups;


			 }// fin bdd plein
}// fin if isset session
	//parcourir($_SESSION['Depliant']);return;
	
	
?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>&nbsp;&nbsp;
<div Class="TitleHead" > <?php  echo $trad['depliant']['liste_des_fam'];?></div></div>
<?php
if((!isset($_SESSION['Depliant']) )  || (count($_SESSION['Depliant'])==0)){
?>
<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
								<?php  echo $trad['msg']['AucunResultat'];?>
							</div>
<?php }
else { ?>
<DIV id="" style="">
<ul class="bxslider" style="margin:0;padding:0;">

<?php 


$k=0;
	$i=1;
	foreach($_SESSION['Depliant'] as $u=>$v){	
		//echo "--------<li>".$k."</li>";
		// recherche pour ne pas dubliquer la couleur du cadre
		
		if( $i==1) echo " <li><div style='text-align:center'>" ;	
		?>
		  <div class="cadreIndex hvr-grow"   style="width:225px; max-width: 225px;"" onclick="AfficheSousFam('<?php echo $v['IdFamille'];?>')">
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
				<input type="button" value="Terminer" class="btn"  onclick="TerminerCmd()"/>&nbsp;&nbsp;
				<input type="button" value="Annuler"  class="btn" onclick="Fermer()"/>
				
				
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
if (isset($_GET['TerminerCmd'])){

	//parcourir($_POST);return;
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
			$Tva=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"] * $contenu["TVA"]) /100;
			$totalTVA+=$contenu["TVA"];
			$TotalTTC+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
			$TotalHT+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]);
			}
	}
/*
if(	$_POST['TotalCmdTTC']==0) $EtatReg="NR";
else if($_POST['TotalCmdTTC']<$TotalTTC) $EtatReg="P";
else if($_POST['TotalCmdTTC']==$TotalTTC) $EtatReg="R";
*/

$NumFacture= "NF".Increment_Chaine_F("numFacture","factures","IdFacture",$conn,"",array());	
//echo $RefFicheCh;return;
$Date = date_create(date("Y-m-d"));
$Heure=date("H:i:s");
$Etat="";
$reqInser1 = "INSERT INTO factures ([numFacture] ,[idClient]  ,[idVendeur]  ,visite,[date],[heure],[idDepot],totalHT,totalTVA,totalTTC,etat,reste) 
				values 	(?,?,?,?,?,?,?,?,?,?,?,?)";
		//	echo $reqInser1;
$params1= array(
				$NumFacture,
				$_SESSION["IdClient"],
				$_SESSION["IdVendeur"],
				$_SESSION["IdVisite"],
				$Date,
				$Heure,
				$IdDepot,
				$TotalHT,
				$totalTVA,
				$TotalTTC,
				$EtatReg,
				0
				
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

	$reqInser2 = "INSERT INTO  detailfactures(idFacture,[idArticle],type,[qte],tarif,idDepot,idFiche,tauxTVA,ht,tva,ttc) 
					values (?,?,?,?,?,?,?,?,?,?,?)";
			$params2= array(
					$IdFacture,
					$contenu['IdArticle'],
					$contenu['Colisage'],
					$contenu['Qte'],
					$contenu['PV'],
					$IdDepot,
					$_SESSION["IdFiche"],
					$contenu['TVA'],
					$contenu['HT'],
					($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"] * $contenu["TVA"]) /100,
					$contenu['TTC']
			
			) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout detail facture ".$errors[0]['message'] . " <br/> ";
				break ;
			}		


				//----------------------modification du stock vendeur --------------------------//
				//qteCmd par piece
			$qteCmd=$contenu['Qte']*$contenu["Colisage"];
			$reqUpStock = "update   stockVendeurs set stock=stock-$qteCmd where idVendeur = ? and idArticle=?";
			$paramsUp= array($_SESSION['IdVendeur'],$contenu['IdArticle']) ;
			$stmtUp = sqlsrv_query( $conn, $reqUpStock, $paramsUp );
			if( $stmtUp === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : modification du stock vendeur ".$errors[0]['message'] . " <br/> ";
				break ;
			}
			
			
}

//----------------------Insertion regelement --------------------------//
$reqInser3 = "INSERT INTO reglements ([idClient]  ,[idVendeur] ,[date],[heure],[type],montant,idDepot) 
				values 	(?,?,?,?,?,?,?)";
		//	echo $reqInser1;
$params3= array(				
				$_SESSION["IdClient"],
				$_SESSION["IdVendeur"],
				$Date,
				$Heure,
				"",
				$TotalTTC,
				$IdDepot				
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
    $error.="Erreur : Insertion detail regelement ".$errors[0]['message'] . " <br/> ";
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
  	            $reponse=sqlsrv_query( $conn, $sql, $params, array( "Scrollable" => 'static' ) );	
				if( $reponse === false ) {
					$errors = sqlsrv_errors();
					$error="Erreur :  ".$errors[0]['message'] . " <br/> ";
					return;
				}		
				if(sqlsrv_num_rows($reponse)==0) { echo "0";return;}
					$rowC = sqlsrv_fetch_array($reponse, SQLSRV_FETCH_ASSOC);		
			//	echo ("----".$_POST['Qte']*$_POST['Colisage']."---------".$rowC['Stock']."-----");					
					if(($_POST['Qte']*$_POST['Colisage']) > $rowC['Stock']) echo "0";
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
	<DIV Class="title">Validation de la commande </div>
	<div style="height:585px; overflow:scroll;" >

	<DIV class="ListeCmd">
	 	 <div class="enteteL" >
        <div  class="divArticleL"  >Article </div>
		   <div  class="divArticleL" style="width:200px"  >Gamme </div>		
        <div class="divQteL" > Quantité </div>			
		 <div class="divColisageL"> Colisage </div>
		  <div class="divPVL"> PV HT (Dhs) </div>
		   <div class="divColisageL"> TVA </div>
		     <div class="divTTC"> HT (Dhs) </div>
			 <div class="divTTC"> TTC (Dhs) </div>
		</div>
  	
			<?php
			$k=0;$Total=0;
			foreach($_SESSION['lignesCat'] as $ligne=> $row){
				$k++;
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			$Total+=$row["Qte"]*$row["Colisage"]*$row["PV"];
				?>	
				<div class=" <?php echo $c;?>" onclick="getArticle('<?php  echo $row['IdArticle'];?>','list')">
						<div class="divArticleL" align="center"><?php  echo $row['NomArt'];?></div>
						<div class="divArticleL"  style="width:230px"  align="center"><?php  echo $row['Gamme'];?></div>
						<div class="divQteL" > <?php  echo $row['Qte'];?> </div> 											
						<div class="divColisageL" > <?php  echo $row['Colisage'];?> </div> 
						<div class="divPVL" > <?php echo number_format($row['PV'], 2, '.', ' '); ?>  </div> 
						<div class="divColisageL" > <?php  echo $row['TVA'];?> </div> 						
						<div class="divTTC" > <?php  
										$PVHT=$row["Qte"]*$row["Colisage"]*$row["PV"];
										echo number_format($PVHT, 2, '.', ' ');?> 
							</div> 
							<div class="divTTC" > <?php  
										$Tva=($row["Qte"]*$row["Colisage"]*$row["PV"] * $row["TVA"]) /100;
										$Total=($row["Qte"]*$row["Colisage"]*$row["PV"]) +$Tva;
										echo number_format($Total, 2, '.', ' ');?> 
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
			<div class="cmd">Total global:
			<input type="text" value=" <?php echo number_format(Total(), 2, '.', ' '); ?> " class="global"  disabled id="txtTotal" size="10"  name="TotalCmdTTC">DHS </div>
		</div>
		<div class="divLeft" Style="width:623px">
			<input type="button" value="Valider la commande >>" class="btnCmd" onclick="ValideCmdEtape1()">
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
	//   echo count($_SESSION["lignesCat"]);
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
								$ligneArray["Colisage"]=$_POST["Colisage"];
								$ligneArray["TVA"]=$_POST["TVA"];
								$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
								$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
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
							$ligneArray["TVA"]=$_POST["TVA"];
							$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
							$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
							$ligneArray["Colisage"]=$_POST["Colisage"];
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
								$ligneArray["TVA"]=$_POST["TVA"];
								$ligneArray["HT"]=$_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"];										
								$ligneArray["TTC"]=($_POST["Qte"]*$_POST["Colisage"]*$_POST["PV"]) +$Tva;
								$ligneArray["Colisage"]=$_POST["Colisage"];
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
			$Tva=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"] * $contenu["TVA"]) /100;
			
			$Total+=($contenu["Qte"]*$contenu["Colisage"]*$contenu["PV"]) +$Tva;
			}
	}
	return($Total);
}
if (isset($_GET['getArticle'])){

		/*$sqlAr = "select 
								a.Designation dsgArticle ,a.IdArticle,a.reference,CB codeABarre,m.url,colisagee colisage,
								g.Designation dsgGamme,
								sf.Designation dsgSousFamille,
								f.Designation dsgFamille,TVA,t.pvHT as PV,a.Unite
								from articles a
								inner join gammes g on g.idGamme=a.IdFamille
								inner join sousfamilles sf on sf.idSousFamille=g.IdSousFamille
								inner join Familles f on f.idFamille=sf.idFamille
								inner join media m on m.idArticle=a.idArticle
								inner join colisages c on c.idArticle=a.idArticle
								INNER JOIN dbo.tarifs t ON t.idArticle=a.IdArticle 
								WHERE a.idArticle=?
									 ";
								
									 
						$paramsAr = array($_GET['idArticle']);

						$stmtA=sqlsrv_query($conn,$sqlAr,$paramsAr,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
						if( $stmtA === false ) {
							$errors = sqlsrv_errors();
							echo "Erreur : ".$errors[0]['message'] . " <br/> ";
						}
					else {
		
						$nResA = sqlsrv_num_rows($stmtA);
						//echo $sql;
							$nRes = sqlsrv_num_rows($stmtA);	

						if($nResA==0)
								{ ?>
											<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
												<br><br><br><br>
												Aucun r&eacute;sultat &agrave; d.
												<a href="#" onclick="Fermer()" class="Retour">Retour </a>
											</div>
											<?php
								}
						else
						{
							$row = sqlsrv_fetch_array($stmtA);	
								$IdArticle=$row['IdArticle'];
								$dsgArticle=$row['dsgArticle'];
								$dsgGamme=$row['dsgGamme'];
								$TVA=$row['TVA'];
								$PV="10";*/
								$tabArtilce=array();


foreach($_SESSION['Depliant'] as $v){//famille
	
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

//parcourir($tabArtilce);
								
								$IdArticle=$tabArtilce[0]['IdArticle'];
								$dsgArticle=$tabArtilce[0]['DsgArticle'];
								$dsgGamme=$DsgGamme;
								$TVA=$tabArtilce[0]['TVA'];
								$ColisageCmde="";
								?>
									<DIV class="haut">
										<div class="divLeftArt" style="width:570px;">
										<div id="resAdd"></div>
										<form id="formAddArt" method="post" name="formAddArt"> 
											<TABLE   border="0" width="100%" class="table" cellspacing="2" cellpadding="7">
												<tr><TD width="25%"><?php echo $trad['label']['Ref'];?>:</td><td>	<?php  echo $tabArtilce[0]['Reference'];?>
												<input type="hidden" value="<?php  echo $IdArticle; ?>" name="IdArticle">
												<input type="hidden" value="<?php  echo stripcslashes($dsgArticle); ?>" name="NomArt">
												<input type="hidden" value="<?php  echo stripcslashes($dsgGamme); ?>" name="Gamme">
												<input type="hidden" value="<?php  echo $TVA; ?>" name="TVA">
												<input type="hidden" value="<?php  echo $tabArtilce[0]['PV'];?>" name="PV">
												<?php if (isset($_GET['list'])){
													
													$key = array_search($_GET['idArticle'],array_column($_SESSION["lignesCat"], 'IdArticle'));
													$Qte=$_SESSION["lignesCat"][$key]["Qte"];
														if ($key !== false) {
															$ColisageCmde=$_SESSION["lignesCat"][$key]["Colisage"];
														}
													?>
												
													<input type="hidden" value="List" name="List">
													<?php } else $Qte=1;?>
												</td></tr>
												<tr><TD ><?php echo $trad['label']['Dsg'];?>: <?php echo $ColisageCmde;?></td><td>	<?php  echo stripcslashes(ucfirst($dsgArticle));?></td></tr>
												<tr><TD><?php echo $trad['label']['Gamme'];?>:</td><td><?php  echo stripcslashes(ucfirst($dsgGamme));?></td></tr>
												<tr><TD><?php echo $trad['label']['SousFamille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgSousFam));?></td></tr>
												<tr><TD><?php echo $trad['label']['Famille'];?>:</td><td><?php  echo stripcslashes(ucfirst($DsgFamille));?></td></tr>
												<tr><TD><?php echo $trad['label']['PV'];?>:</td><td><?php  echo $tabArtilce[0]['PV'];?></td></tr>
												<tr><TD><?php echo $trad['label']['Colisage'];?>:</td><td height="70">
											
												<input type="radio" class="action" name="Colisage"  value="1" 
												data-labelauty="1<?php  echo $tabArtilce[0]['Unite'];?>|1<?php  echo $tabArtilce[0]['Unite'];?>" 
												aria-label="2"  
												<?php if( $ColisageCmde==1) echo "checked" ;?>
												/>
												<?php 
												if( $tabArtilce[0]['Colisage']!=1){
												?>
												<input type="radio" class="action" name="Colisage" value="<?php  echo $tabArtilce[0]['Colisage'];?>" 
												data-labelauty="<?php  echo $tabArtilce[0]['Colisage'];?><?php  echo $tabArtilce[0]['Unite'];?>(s)|
												<?php  echo $tabArtilce[0]['Colisage'];?><?php  echo $tabArtilce[0]['Unite'];?>" aria-label="3"
												<?php if( $ColisageCmde!=1) echo "checked" ;?>
												/>
												<?php }?>
												</td></tr>
												<tr><TD>Quantité:</td><td Valign="top"> 
												<input type="text" value="<?php echo $Qte;?>" name="Qte" onkeypress="return isEntier(event) " 
												class="Qte" size="4" id="Qte"><br>
												<input type="button" class=" qtyplus"  onclick="Plus()" id="qtyplus" value="+" id="btnp">&nbsp;
												<input type="button" class=" qtyplus"  onclick="Moins()" id="qtyminus"  value="-">
												
												
												
												</td></tr>
												<TR>	
												<td   Valign="top"></td>
												<td>
												
												
												</td>
												</tr>
												<TR>	
												<td   Valign="top" align="center" colspan="2">
												<input type="button" value="Valider" class="btn"  onclick="AjoutArticle()"/>&nbsp;&nbsp;
												<input type="button" value="Fermer"  class="btn" onclick="FermerBoxArt()"/>
												</td>
												</tr>
											</table>
											</form> 
										</div>
										
										<div class="divRight">
										<img src="../<?php  echo $tabArtilce[0]['UrlArticle'];?>" alt=""  width=" 640" height="760" />	
										</div>
									</div>
									<?php
					//	}}
?>
<script language="javascript" type="text/javascript">
function Plus(){
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
}
function Moins(){
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
}
$(document).ready(function(){
	/*
$('#qtyplus').click(function(e){

		var fieldId="Qte";
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
    });
	    $('#qtyminus').click(function(e){
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
    });	*/
$(".action").labelauty();
	
 
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
<div id="formRes" style="width:959px;">

</div>
<div class="clear"></div>
<div class="bottomVente" style="display:none;">
<div class="divLeft">
			<div class="cmd">
			
			Total global: <input type="text" value=" 0.00" class="global" id="txtTotal" size="6" disabled> DHS</div>
		</div>
		<div class="divRight">
			<input type="button" value="Consulter la commande >>" class="btnCmd" onclick="ConsultCmd()">
		</div>
		</div>
<div id="box"></div><div id="boxArticle"></div>
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
				jAlert("Merci d'ajouter des articles.","Message");
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

$("#txtTotal").val("<?php echo number_format("0.00", 2, '.', ' '); ?>");
	$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('depliant.php?affGamme&&VideFam');
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
					var url='depliant.php?affGamme';	}
					else {
							var url='depliant.php?affGamme&CurrentSlide='+CurrentSlide;	
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
function rechercherFam(){
		//alert('lll');
		var url='depliant.php?affFam';	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}
function AfficheSousFam(id){
		//alert(id);
		var url='depliant.php?affSousFam&&Id='+id;	
		$('#formRes').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}
function AfficheMarque(id){
		var url='depliant.php?affMarque&&Id='+id;	
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
		$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('depliant.php?ChoixTypeReg').dialog('open');
		//$('#res').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

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
			 jConfirm('Voulez-vous vraiment commander l\'article?', null, function(r) {
					if(r)	{
							var Verif="";							 
							 // verification du stock vendeur pour l'article selectionné
							 	$(form).ajaxSubmit({
									   url : 'depliant.php?VerifStock',
									   type : 'POST',
									   dataType : 'html', // On désire recevoir du HTML
									   success : function(code_html, statut){										   
										   Verif=code_html;
											if(Verif==1){
											$(form).ajaxSubmit({
																target			:	'#resAdd',
																url				:	'depliant.php?goAddArti',
																method			:	'post'
															});
															return false;
											}else 
											{
												jAlert("la quantité demandée dépasse la quantité du stock ","Message");
											}
									   }
								})
						   
						   

												
						}
				})
		}
}
</script>

<?php include("footer.php");?>
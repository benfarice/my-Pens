<?php
include("../php.fonctions.php");
require_once('../connexion.php');
  if(!isset($_SESSION))
{
session_start();
}
$IdDepot=$_SESSION['IdDepot'];

include("lang.php");
//echo $_SESSION['IdTournee'];


if (isset($_GET['goAddArti'])){	

//parcourir($_POST);return;
	if( (isset($_SESSION['tabArticle'])) && (count($_SESSION['tabArticle'])!=0))  {
	//   echo count($_SESSION["lignesCmd"]);
		$t=0;	
		$Total=0;
		//parcourir($_SESSION['tabArticle']);return;
			  foreach($_SESSION['tabArticle'] as $ligne=>$contenu){
				// controler si  table session contient deja la ligne avec  mm article 
		
					if(($contenu["IdArticle"]==$_POST["IdArticle"]))
					{
								$IndexLigne=$t;
							//	$ligneArray["IdDtlCmdVdr"]=$_POST["IdDtlCmdVdr"];
							
							
									$ligneArray["IdLigne"]=$IndexLigne;
									$ligneArray['IdArticle'] = $contenu['IdArticle'];
									$ligneArray['DsgArticle'] = $contenu['DsgArticle'];									
									$ligneArray['UrlArticle'] =$contenu['UrlArticle'];
									$ligneArray['PV'] =$contenu['PV'];	
									$ligneArray['Reference'] =$contenu['Reference'];	
									$ligneArray['Colisage'] =$contenu['Colisage'];	
									$ligneArray['TVA'] =$contenu['TVA'];
									$ligneArray['UrlArticle'] =$contenu['UrlArticle'];
									$ligneArray['Unite'] =$contenu['Unite'];
									$ligneArray['Qte'] =$contenu['Qte'];
									$ligneArray['dateDernierVente'] =$contenu['dateDernierVente'];
									$ligneArray['idClient'] =$contenu['idClient'];
									if($_POST['Rupture']=='0'){
										$ligneArray['QteStock'] =$_POST['Qte'];
										$ligneArray['ColisageRup'] =$_POST['Colisage'];
										$ligneArray['Rupture'] ="";
										$ligneArray['NbrJourRupture'] ="";
									}else {	
									$ligneArray['QteStock'] ="";
										$ligneArray['ColisageRup'] ="";
										$ligneArray['Rupture'] =$_POST['Rupture'];
										$ligneArray['NbrJourRupture'] =$_POST['NbrJourRupture'];
									}
										
									
								
									$_SESSION['tabArticle'][$IndexLigne]= $ligneArray;
									
													
						//$t=0;break;				
					}
						$t+=1;	
					/*else {					
										
					}*/
			  }
			
		  }

		//print_r($_SESSION['tabArticle']);return;
		if ((isset($_GET['list']))  && ($_GET['list']=="Actualiser")) {
			 ?>
						<script language="javascript" type="text/javascript">
						//alert('ConsultCmd');
							$('#Inv').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('inventaire.php?ConsultCmd');
							$("#boxArticle").dialog('close');
						</script>
						<?php
			//
			}

		 ?>
			
			<?php
		// }
?>
<script language="javascript" type="text/javascript">
		
		  	//$("#boxArticle").dialog('close');
</script>
<?php
	exit;
}
if (isset($_GET['affArti'])){
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
<?php

$tabArtilce=array();
$k=0;
$DsgFamille="";$DsgSousFam="";$DsgGamme="";
//parcourir($_SESSION['IdMarque']);return;

?>
<div class="clear"></div>			
		<div id="resAdd"></div>
		<div id="Inv">

<?php		   
					//$_SESSION['lignesSousFam']=$groups;
				//parcourir($_SESSION['CatClient']);return;

			// fin bdd plein
//}// fin if isset session


foreach($_SESSION['CatClient'] as $v){//famille
	
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
						if($f['IdMarque']==$_GET["Id"]) {
								//parcourir($f);return;							
								foreach($f as $g){//gammes									
								
									if(is_array($g)){
									
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
		$_SESSION['tabArticle']=$tabArtilce;
		//parcourir($_SESSION['tabArticle']);return;
//parcourir($tabArtilce);return;
}
			$i=0;
			?>
	<form id="formCmd" method="post" name="formCmd"> 		
	<div id="res"></div>
	
	<div style="height:585px; overflow:scroll;" >

	<DIV class="ListeCmd">
	 	 <div class="enteteL" >
        <div  class="divArticleL"  ><?php echo $trad['label']['Article'];?> </div>
		   <div  class="divArticleL"   ><?php echo $trad['label']['DerniereQteVendu'];?>  </div>		
        <div class="divQteL" > <?php echo $trad['label']['DateVente'];?>  </div>			
		 <div class="divColisageL"> <?php echo $trad['label']['SituationActuel'];?>  </div>
		  <div class="divPVL" > <?php echo $trad['label']['Rupture'];?>  </div>
		</div>
  	
			<?php
			$k=0;$Total=0;
			foreach($tabArtilce as $ligne=> $row){
				$k++;
					if($k%2 == 0) $c = "pair";
					else $c="impair";		
				?>	
				<div class=" <?php echo $c;?>" onclick="getArticle('<?php  echo $row['IdArticle'];?>','list')">
						<div class="divArticleL" align="center"><?php  echo $row['DsgArticle'];?></div>
						<div class="divArticleL"   align="center">
						<?php  echo $row['Qte']."X".$row['Colisage'];?></div>
						<div class="divQteL"  > <?php  
		
						$dateDernierVente = $row['dateDernierVente']->format('d/m/Y');
						echo $dateDernierVente;?> </div> 											
						<div class="divColisageL" > <?php  echo $row['Rupture'];?> </div> 
						<div class="divPVL" > <?php  echo $row['NbrJourRupture'];?> </div>
				</div>
			<DIV Class="clear"></div>		
	
			<?php }
		
	?>
	</div>


</div></form>
</div>
<div class="bottomVente  " style="width:1258px">
<input type="button" value="<?php echo $trad['button']['Enregistrer'];?>" class="btnCmd" onclick="EnregisterInv()">
		
		</div>
<?php
			
		
?>
	
	
	
<script language="javascript" type="text/javascript">

</script>
	<?php
exit;
}

if (isset($_GET['aff'])){
	
	?>
	<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>				
<div  onclick=" " >&nbsp;> <span  Class="TitleHead" > <?php echo $trad['label']['QualityService'];?></span></div></div>

<?php


$sql="select IdQuestion,DsgQuestion_".$lang." as DsgQuestion  from Questionnaire";
		$stmt=sqlsrv_query($conn,$sql,array(),array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
								}
		$nRes = sqlsrv_num_rows($stmt);	
		
				if($nRes!=0)
				{ 	
						$i=0;
?>
<div id="res"></div>
<ul class="bxslider" style="margin:0;padding:0;">
<?php						
				 while($row=sqlsrv_fetch_array($stmt)){	
					$i++;
		//echo "--------<li>".$k."</li>";
		// recherche pour ne pas dubliquer la couleur du cadre
		
	 echo " <li><div style='text-align:center'>" ;	
		?>
		<div class="Question">
		<?php echo $row['DsgQuestion'];?></div>
		  <div class="CadeQualit hvr-grow"   style="width:225px;" 
		  onclick="EnregistrerReponse('<?php echo $row['IdQuestion'];?>','Mauvaise')">
			 <img src="img/mauvaise.png"  width="225" height="226"/>
		  </div>
		<div class="CadeQualit  hvr-grow">
		 <img src="img/moyenne.png"  width="225" height="226" 
		 onclick="EnregistrerReponse('<?php echo $row['IdQuestion'];?>','Moyenne')"/>
		</div>
		<div class="CadeQualit  hvr-grow">
		 <img src="img/parfait.png"  width="225" height="226"  
		 onclick="EnregistrerReponse('<?php echo $row['IdQuestion'];?>','Parfait')"/>
		</div>
		  </div>
		
		  <?php if($i==$nRes) {?>  <div class="">
			  <input type="button" value="<?php  echo $trad['button']['Valider'];?>" class="btn"
style="width:100%"			  
			  onclick="Terminer()"/>&nbsp;&nbsp;
			  </div>
		  <?php }
				 ?>
				
		  </li>
		<?php
	/*	if($i==3) {?> <div class="clear"></div><?php }
			//condition pour afficher 4 familles par page
			if ($i == 3) {  echo " </div></li>" ; $i=1;}
			else {				$i+=1;}
			*/
	 //  echo $i;
	}
				
				?>
	</ul>
	<?php }
	else {
		echo "merci d'alimenter la base de donnée par les questions.";
	}

?>
<script language="javascript" type="text/javascript">
  // initialize bxSlider
     tabQuestion = new Array();
  function EnregistrerReponse(idQuestion,Reponse){
	 // alert(idQuestion+" "+Reponse);
	   slider.goToNextSlide();
	 index=tabQuestion.length ;
	//alert(index);

	if(index==0){
				   tabQuestion[index]={};
					  tabQuestion[index]["Question"] = idQuestion;
					  tabQuestion[index]["Reponse"] = Reponse;
	}
//	alert(tabQuestion.length);
var trouver=false;
//chercher si la question est deja ajoutée ds la table
		  for (i=0;i<tabQuestion.length; i++) {		
				  if(tabQuestion[i]["Question"]==idQuestion){
					  tabQuestion[i]["Reponse"] = Reponse;
					  trouver=true;					
				  }
			}
			if(trouver==false){
				// inserer ds la table
					tabQuestion[index]={};
					  tabQuestion[index]["Question"] = idQuestion;
					  tabQuestion[index]["Reponse"] = Reponse;
			}
	
  } 
  function Terminer(){
	  
	jConfirm('<?php echo $trad['msg']['ConfirmerOperation'];?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
	if(r)	{						
	var json1 = JSON.stringify( tabQuestion );
	
	  $('#res').load("qualite_service.php?goAdd&tab="+json1);
					}
	});
  }
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
			controls:false
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
if (isset($_GET['goAdd'])){
 $error="";
	$TabQuestion=json_decode($_GET['tab'], true);
//	echo $_SESSION['IdClt'];
	//	echo $_SESSION['IdVendeur'];
	//print_r($array);
	/* --------------------Begin transaction---------------------- */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    $error="Erreur : ".sqlsrv_errors() . " <br/> ";
}
$Date = date_create(date("Y-m-d"));

$Etat="";
$reqInser1 = "INSERT INTO qualite_service ([IdClt] ,[IdVendeur]  ,[Date]  ,IdDepot) 
				values 	(?,?,?,?)";
		//	echo $reqInser1;
$params1= array(
				$_SESSION['IdClt'],
				$_SESSION["IdVendeur"],
				$Date,
				$IdDepot
				
) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Error :Add quality service  ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IdQltService--------------------------------//
$sql = "SELECT max(IdQltService) as IdQltService FROM qualite_service";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Error get IdQltService : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdQltService = sqlsrv_get_field( $stmt2, 0);


//----------------------Add Detail qualite_service --------------------------//
//print_r($TabQuestion);
/*
foreach ($TabQuestion as $key => $value) {
    foreach ($value as $id => $val) {
        echo $val."<br>";
    }
}return;*/
  foreach($TabQuestion as $ligne=>$contenu){

   
	$reqInser2 = "INSERT INTO  DetailsQualiteService(IdQltService,[IdQuestion],Reponse) 
					values (?,?,?)";
			$params2= array(
					$IdQltService,
					$contenu['Question'],
					$contenu['Reponse']
			
			) ;
			$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
			if( $stmt3 === false ) {

				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout detail qualite_service ".$errors[0]['message'] . " <br/> ";
				break ;
			}		

}

if($error=="" ) {
     sqlsrv_commit( $conn );
	 
     ?>
		<script type="text/javascript"> 
			
			jAlert("<?php echo $trad['msg']['messageAjoutSucces'];?>","<?php echo $trad['titre']['Alert'];?>");
		</script>
		
<?php
} else {
     sqlsrv_rollback( $conn );
	   ?>
		<script type="text/javascript"> 
			jAlert("<?php echo  $error;?>","Message");	
		</script>
		
<?php
     
}

	exit;
}
if (isset($_GET['affGamme'])){
$_SESSION['IdMarque']=$_GET['Id'];
//parcourir($_GET);//return;
$CurrentSlide=0;
if(isset( $_GET['CurrentSlide'])) $CurrentSlide=$_GET['CurrentSlide'];
?>	
<style>
.bxslider{margin-top:0;}
</style>
<DIV style="  display:flex;  align-items:center;" class="headVente"><a href="index.php">
<img src="../images/home.png" height="64" width="64" style="float:left;" /> </a>
<div >&nbsp;&nbsp;>
<span  class="TitleHead" onclick="rechercherFam()"> <?php  echo $trad['titre']['Famille'];?></span>
>
<span  class="TitleHead" onclick="AfficheSousFam(<?php echo $_SESSION['IdFamille'];?>)"><?php  echo $trad['titre']['SousFamille'];?></span>
>
<span  class="TitleHead" onclick="AfficheMarque(<?php echo $_SESSION['IdSousFam'];?>)"><?php  echo $trad['titre']['Marque'];?></span>
</div>&nbsp;>  <?php  echo $trad['titre']['Gamme'];?></div>
<?php //echo $_SESSION['IdFamille']."___".$_SESSION['IdSousFam']."___".$_SESSION['IdMarque'] ; ?>
<div class="clear"></div>
<DIV  style="margin:0px;">
<ul class="bxslider">
							<?php 
							//	parcourir($_SESSION['CatClient']);return;
							$key ="";
				if( (isset($_SESSION['CatClient'])) && (count($_SESSION['CatClient'])!=0))  {//famille
				//echo $_SESSION['IdFamille'];return;
				
					$i=1;
						foreach($_SESSION['CatClient'] as $v){// famille
							if(is_array($v)){ 
							if($_SESSION['IdFamille']==$v["IdFamille"]){
								foreach($v as $d){//sous famille
										if(is_array($d)){//	parcourir($d);return;
									
											if( $_SESSION['IdSousFam']==$d["IdSousFam"])
											{
											foreach($d as $f){	//marque										
												if(is_array($f)){
													//echo $_GET['Id'];
												//echo $_SESSION['IdSousFam']."___".$d["IdSousFam"];
											if( $f['IdMarque']==$_GET['Id']) 
											{
												
														foreach($f as $r){//gamme		
																if(is_array($r)){
																	//	parcourir($r);//	return;
																//	echo $r['DsgGamme'];;
															?>
																<li>
													<img src="../<?php  echo $r['UrlGamme'];?>" alt="<?php  echo $r['IdGamme'];?>"  width=" 100%" height="596" 
													style="position : relative ;  "/>	
																	<DIV STYle="margin-top:63px">											
															 <input type="button" class="DetailGamme" 
															 value="<?php echo $trad['button']['VoirArticles'];?>  "  
													
															 onclick="AfficheDetailGamme('<?php echo $r['IdGamme'];?>')" />
															 </div>
															  </li>
															 
															<?php
																}
		 
														}
											}
												//echo $f['IdMarque'];return;
										
												}
											}
											}
									}
							}}
							}
							}
							?>
						
											
						
											<?php
														
				}
			?>
		</ul>
		</div><?php		   
					//$_SESSION['lignesSousFam']=$groups;
				//parcourir($_SESSION['CatClient']);return;

			// fin bdd plein
//}// fin if isset session


?>	
	
<script language="javascript" type="text/javascript">
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
				if( (isset($_SESSION['CatClient'])) && (count($_SESSION['CatClient'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['CatClient'] as $v){	//	famillesif(is_array($v)){	parcourir($v);}
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
														 
														  onclick="AfficheArticles('<?php echo $r['IdMarque'];?>')">
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
				//parcourir($_SESSION['CatClient']);return;

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
				if( (isset($_SESSION['CatClient'])) && (count($_SESSION['CatClient'])!=0))  {
					?>
					<?php	
					$i=1;
						foreach($_SESSION['CatClient'] as $v){	//	if(is_array($v)){	parcourir($v);}
						
							
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
				//parcourir($_SESSION['CatClient']);return;

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
if (isset($_GET['ConsultCmd'])){
	
	//parcourir($_SESSION['tabArticle']);return;
$i=0;
			?>
	<form id="formCmd" method="post" name="formCmd"> 		
	<div id="res"></div>
	
	<div style="height:585px; overflow:scroll;" >

	<DIV class="ListeCmd">
	 	 <div class="enteteL" >
        <div  class="divArticleL"  ><?php echo $trad['label']['Article'];?> </div>
		   <div  class="divArticleL"   ><?php echo $trad['label']['DerniereQteVendu'];?>  </div>		
        <div class="divQteL" > <?php echo $trad['label']['DateVente'];?>  </div>			
		 <div class="divColisageL"> <?php echo $trad['label']['SituationActuel'];?>  </div>
		  <div class="divPVL"> <?php echo $trad['label']['Rupture'];?>  </div>
		
		</div>
  	
			<?php
			$k=0;
			//	print_r($_SESSION['tabArticle']);return;
			foreach($_SESSION['tabArticle'] as $ligne=> $row){
				$k++;
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
				?>	
				<div class=" <?php echo $c;?>" onclick="getArticle('<?php  echo $row['IdArticle'];?>','list')">
						<div class="divArticleL" align="center"><?php  echo $row['DsgArticle'];?></div>
						<div class="divArticleL"   align="center">
						<?php echo $row['Qte']."X".$row['Colisage'];?></div>
						<div class="divQteL" > <?php  
						$dateDernierVente = $row['dateDernierVente']->format('d/m/Y');
						echo $dateDernierVente;?> </div> 											
						<div class="divColisageL" > <?php if($row['QteStock']!="")  echo $row['QteStock']."X".$row['ColisageRup'];?> </div> 
						<div class="divPVL" > <?php  echo $row['NbrJourRupture'];?> </div>
				</div>
			<DIV Class="clear"></div>
			
	
			<?php
		}
	?>
	</div>


</div>
<?php
			
		
?>
	</form><?php

exit();

}

if (isset($_GET['getArticle'])){

		
								$tabArtilce=array();
//parcourir($_SESSION['tabArticle']);return;

foreach($_SESSION['tabArticle'] as $r){										
													if(is_array($r)){
														
															if ($r['IdArticle']==$_GET['idArticle']){															
															
																array_push($tabArtilce,$r);
															}
														//echo  $DsgGamme."<br>";	
																	}
												}

//parcourir($tabArtilce);
								
								$IdArticle=$tabArtilce[0]['IdArticle'];
								$dsgArticle=$tabArtilce[0]['DsgArticle'];
								$TVA=$tabArtilce[0]['TVA'];
								$ColisageCmde="";
								?>
									<DIV class="haut">
										<form id="formAddArt" method="post" name="formAddArt"> 
										<div class="divLeftArt">
										<div id="resAddArt"></div>
									
											<TABLE   border="0" width="100%" class="table" cellspacing="2" cellpadding="7">
												<tr><TD width="25%"><?php echo $trad['label']['Ref'];?>:</td><td>	<?php  echo $tabArtilce[0]['Reference'];?>
											
												<?php /*if (isset($_GET['list'])){
													
													$key = array_search($_GET['idArticle'],array_column($_SESSION["lignesCat"], 'IdArticle'));
													$Qte=$_SESSION["lignesCat"][$key]["Qte"];
														if ($key !== false) {
															$ColisageCmde=$_SESSION["lignesCat"][$key]["Colisage"];
														}
													?>
												
													<input type="hidden" value="List" name="List">
													<?php } else */ $Qte=1;?>
												</td></tr>
												<tr><TD >
												<input type="hidden" name="IdArticle" value="<?php echo $IdArticle;?>" value="1" />
												<?php echo $trad['label']['Dsg'];?>: <?php echo $ColisageCmde;?></td><td>	<?php  echo stripcslashes(ucfirst($dsgArticle));?></td></tr>
												
												<tr><TD><?php echo $trad['label']['SituationActuel'];?> :</td><td Valign="top"> 
												<input type="text" value="<?php echo $Qte;?>" name="Qte" onkeypress="return isEntier(event) " 
												class="Qte" size="4" id="Qte"><br>
												<input type="button" class=" qtyplus"  onclick="Plus()" id="qtyplus" value="+" id="btnp">&nbsp;
												<input type="button" class=" qtyplus"  onclick="Moins()" id="qtyminus"  value="-">
												</td></tr>
												<tr><TD><?php echo $trad['label']['Colisage'];?>:</td><td height="70">
												<?php 
													$Qte=1;
													$Chek="";
													$Colisage=1;
													$key=-1;
													
													if(isset($_SESSION["lignesCmd"])){
													$key = array_search($_GET['idArticle'],array_column($_SESSION["tabArticle"], 'IdArticle'));
													
														if ($key !== false) {
															$Qte=$_SESSION["tabArticle"][$key]["Qte"];
															$ColisageCmde=$_SESSION["tabArticle"][$key]["Colisage"];
														} else {
															$Qte=1;
														}

													}
													?>
													
												<?php if( $Colisage==1) $Chek="checked" ;?>
												<input type="radio" name="Colisage"  value="1" 
												data-labelauty="<?php  echo $tabArtilce[0]['Unite'];?>|
												<?php  echo $tabArtilce[0]['Unite'];?>" aria-label="2" <?php echo $Chek; ?>  
												<?php if( $ColisageCmde==1) echo "checked" ;?>
												/>
												<?php 
												if( $tabArtilce[0]['Colisage']!=1){
												?>
												<input type="radio" name="Colisage" value="<?php  echo $tabArtilce[0]['Colisage'];?>" 
												data-labelauty="<?php  echo $tabArtilce[0]['Colisage'];?><?php  echo $tabArtilce[0]['Unite'];?>|
												<?php  echo $tabArtilce[0]['Colisage'];?><?php  echo $tabArtilce[0]['Unite'];?>s" aria-label="3" 
												<?php if( $ColisageCmde!=1) 	echo "checked"; ?>
												/>
												<?php }?>
												</td></tr>
												<TR>	
												<td   Valign="top" align="center" colspan="2">
												<input type="button" value="<?php echo $trad['button']['Valider'];?>" class="btn"  onclick="AjoutArticle()"/>&nbsp;&nbsp;
												<input type="button" value="<?php echo $trad['button']['Fermer'];?>"  class="btn" onclick="FermerBoxArt()"/>
												</td>
												</tr>
											</table>
										
										</div>
										
										<div class="divRight">
										<table cellpadding="10" cellpadding="2" cellspacing="0" cellspacing="0" border="0" dir="<?php echo $_SESSION['dir'];?>">
										<tr>
											<TD colspan="3" align="center"><h2>
												<?php echo $trad['label']['RuptureStock'];?>
											</h2></td>											
										
										</tr>
										<tr>
										<td colspan="3" align="center" height="100" >
										<input type="radio" name="Rupture"  value="1" 
												data-labelauty="<?php echo $trad['button']['Oui'];?>|<?php echo $trad['button']['Oui'];?>" aria-label="1"	/>
										<input type="radio" name="Rupture"  value="0" 
												data-labelauty="<?php echo $trad['button']['Non'];?>|<?php echo $trad['button']['Non'];?>" aria-label="0" checked	/>
										</td>
										</tr>
										<tr>
											<TD><?php echo $trad['label']['NbrJourRupture'];?> </td>											
							<TD>	<input  g="date" id="NbrJourRupture" tabindex="2" name="NbrJourRupture" type="text" 
											class="form-control" 
					size="10" maxlength="10"  value=""/>	
					<td><?php echo  $trad['label']['Jour'];?> </td>
				
					</td>
										</tr>
										<!--img src="../<?php  echo $tabArtilce[0]['UrlArticle'];?>" 
										alt=""  width=" 640" height="760" /-->	
										</div>	</form> 
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

$(":radio").labelauty();
	
 
});
 

</script>
<?php
		exit;
}

if (isset($_GET['affFam'])){
	
//$idArticle=$_GET['idArticle'];
	if(isset($_GET['VideFam'])){
			
		// vider liste des articles commandés
		//unset($_SESSION['CatClient']);
		// vider le catalogue
}
unset($_SESSION['tabArtilce']);
unset($_SESSION['IdFamille']);
unset($_SESSION['IdSousFam']);
unset($_SESSION['IdMarque']);
unset($_SESSION['IdGamme']);
$timestamp_debut = microtime(true);
unset($_SESSION['CatClient']);

if(!isset($_SESSION['CatClient'])){
	
$IdClient=$_SESSION['IdClt'];
	//selectionner les familles des chargements valide	
	$sql="select 
				a.IdArticle	,
			df.type Colisage,fac.idClient,
			df.qte Qte,
		
		cast(fac.date AS date) AS dateDernierVente,
				me.url UrlArticle,a.reference Reference ,CB codeABarre,a.unite,a.TVA,
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
			INNER JOIN detailfactures df on df.idArticle=a.idarticle
			INNER JOIN factures fac ON fac.IdQltService=df.IdQltService
			where f.idTypeClient=1 and f.type like 'Groupe'
			AND fac.idClient=".$IdClient." 
			AND fac.IdQltService = (
				SELECT  max(fa.IdQltService) from  factures fa
					INNER JOIN detailfactures def on def.IdQltService=fa.IdQltService
					 WHERE def.idArticle=a.IdArticle
					 AND fa.idClient=".$IdClient."  
				
				)


";

		 $params = array();	


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
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdLigne'] = $i;														
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['IdArticle'] = $row['IdArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['DsgArticle'] = $row['DsgArticle'];									
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['PV'] =$row['PV'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Reference'] =$row['Reference'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Colisage'] =$row['Colisage'];	
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['TVA'] =$row['TVA'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['UrlArticle'] =$row['UrlArticle'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Unite'] =$row['unite'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Qte'] =$row['Qte'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['dateDernierVente'] =$row['dateDernierVente'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['idClient'] =$row['idClient'];
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['QteStock'] ="";
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['Rupture'] ="";
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['NbrJourRupture'] ="";
													$groups[$key][$keySousFam][$keyMarque][$keyGamme][$i]['ColisageRup'] ="";
													//}
											$i=$i+1;		
										//}
										}
				
					$_SESSION['CatClient']=$groups;


			 }// fin bdd plein
}// fin if isset session
	//parcourir($_SESSION['CatClient']);return;
	
?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<div class="closebtn" onclick="Fermer()"></div>&nbsp;&nbsp;
<div Class="TitleHead" > <?php  echo $trad['depliant']['liste_des_fam'];?></div>

</div>
<?php
if((!isset($_SESSION['CatClient']) )  || (count($_SESSION['CatClient'])==0)){
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
	foreach($_SESSION['CatClient'] as $u=>$v){	
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
// /*

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

if (isset($_GET['goadd'])){
$error="";
$Date = date("d-m-Y");
//$Heure=date("H:i:s");

$reqInser1 = "INSERT INTO [Inventaire] ([IdClient] ,[DateInv]) 	VALUES 	(?,?)";
		//	echo $reqInser1;
$params1= array($_SESSION['IdClt'],$Date) ;
$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );

if( $stmt1== false ) {
    $errors = sqlsrv_errors();
    $error.="Erreur : Ajout facture ".$errors[0]['message'] . " <br/> ";
}
//---------------------------IdInventaire--------------------------------//
$sql = "SELECT max(IdInv) as IdInv FROM Inventaire";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdInv : ".sqlsrv_errors() . " <br/> ";
}
$IdInv = "" ;
sqlsrv_fetch($stmt2) ;
$IdInv = sqlsrv_get_field( $stmt2, 0);

 foreach($_SESSION['tabArticle'] as $ligne)
 {	
 /*	echo "<p>";
	print_r($ligne);
	echo "</p><br>";*/
					if($ligne['Rupture']=='')
					{ 
						$reqInser2 = "INSERT INTO [detailsInventaire] ([IdArticle] ,[QteStock] ,[Colisage] ,[IdInv])
						VALUES (?,?,?,?)";
						$params2= array(
						$ligne['IdArticle'],
						$ligne['QteStock'],
						$ligne['ColisageRup'],
						$IdInv) ;	
					}
					else
					{  
						$reqInser2 = "INSERT INTO [detailsInventaire] ([IdArticle] ,[Rupture],[NbrJourRupture] ,[IdInv])
						VALUES (?,?,?,?)";
						$params2= array(
						$ligne['IdArticle'],
						$ligne['Rupture'],
						$ligne['NbrJourRupture'],
						$IdInv) ;
					}
	/*	echo "<p>";
	echo $reqInser2;
	print_r($params2);
	echo "</p><br>";*/
	
				$stmt3 = sqlsrv_query( $conn, $reqInser2, $params2 );
				if( $stmt3 === false ) {

					$errors = sqlsrv_errors();
					$error.="Error Insert to detailsInventaire :  ".$errors[0]['message'] . " <br/> ";
					break ;
				}		
 } 
 
 if($error=="" ) {
	 ?>
 
 <script type="text/javascript"> 

		//jAlert(<?php echo $trad['msg']['messageAjoutSucces']; ?>,<?php echo $trad['titre']['Alert']; ?>);			
		//$('#box').dialog('close');
		var url='inventaire.php?affFam';	
		$('#box').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
</script>
<?php 
unset($_SESSION['tabArticle']);


 }else {
	    ?>
		<script type="text/javascript"> 
			jAlert("<?php echo $error; ?>","<?php echo $trad['titre']['Alert']; ?>");				
		</script><?php
 }

exit;
}
if (isset($_GET['getenquete'])){ //echo $_GET['IdClt']; 
$_SESSION['IdClt']=$_GET['IdClt']; 
?>
 <input type="button" value=""  class="close2" onclick="retour()" Style="position:absolute;<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:0px;"/>
 <div style="height:100%">
 <div style="height:208; width:690px;margin:auto; margin-top:60px;   ">
 
<a href="#" class="cadreIndex hvr-grow " id="enq" onclick='getInventaire()' >
  
		<div  class="childIndex"> 
		<img src="../images/form.png" >
		<div class="titleIndex1"> <?php echo $trad['label']['Inventaire'];?></div>
		</div>
</a>
<a href="#" class="cadreIndex hvr-grow " >
  
		<div  class="childIndex"> 
		<img src="../images/Quality.png" >
		<div class="titleIndex1"> <?php echo $trad['label']['QualityService'];?></div>
		</div>
</a>
 <input id="Clt" type="hidden" value="<?php echo $_GET['IdClt']; ?>" />
 </div> 
 </div>
<script language="javascript" type="text/javascript">
  function retour(){
	$("#boxClient").dialog('close');
}
</script>
<?php
exit;
}
if (isset($_GET['infoClient'])){
?>
  <input type="button" value=""  class="close2" onclick="alert('test');Fermerr()" Style="float:left;"/>
<?php
//print_r($_GET);//return;
//echo $_GET['idClient'];
$sql = "SELECT IdClient,nom+ ' ' +c.prenom as nom,c.intitule,c.adresse,
(SELECT ISNULL(sum(factures.totalTTC),0) FROM factures WHERE year(cast(date AS date))=year(getdate()) AND factures.idClient=".$_GET['idClient'].") AS ca ,(SELECT count(*) FROM visites WHERE year(cast(visites.dateFin AS date))=year(getdate()) and idClient=".$_GET['idClient'].") AS nbrVisites
FROM clients c WHERE c.IdClient=".$_GET['idClient'];
$stmt = sqlsrv_query( $conn, $sql );
	if( $stmt === false ) 
	{
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
	//echo $sql ;
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ;
/******************************* Date of last **************************************/
$req = "
SELECT cast([date] AS date)AS d FROM factures f WHERE  f.IdQltService IN (SELECT max(IdQltService) FROM factures WHERE idClient=?)";
$stmt1 = sqlsrv_query( $conn, $req ,array($_GET['idClient']),array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	if( $stmt1 === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}

$nRes = sqlsrv_num_rows($stmt1);
$DateVisite="";
if($nRes != 0 )
{
$rowD = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC ) ;
$DateVisite = date_format($rowD["d"], 'd/m/Y');
}
else
$DateVisite ="aucune visite";

//$date = strtotime($rowD["d"]);

?>


<table  width="93%" cellspacing="10" border="0">
<tr>
<td align="right"><strong><u>Nom Complet </u> :</strong></td>
<td><?php echo  $row["nom"]; ?></td>
<td align="right"><strong><u>Adresse </u> :</strong></td>
<td><?php echo  wordwrap($row['adresse'], 50, "<br />\n", true); ?></td>

</tr>
<tr>
<td align="right"><strong><u>Intitulé </u> :</strong></td>
<td><?php echo  $row["intitule"]; ?></td>
<td align="right"><strong><u>Date Derniére Visite </u>: </strong></td>
<td><?php echo $DateVisite; ?></td>
</tr>
<tr>
<td align="right"><strong><u>CA annuelle </u>: </strong></td>
<td><?php echo number_format($row["ca"], 2, '.', ' ') . "  DH"; ?></td>
<td align="right"><strong><u>Nbr Visites</u>: </strong></td>
<td><?php echo $row["nbrVisites"]; ?></td>
</tr>
</table>
<?php


/************************Derniere Facture du client***************************/
$sql1 = "
SELECT df.IddetailFacture,g.IdGamme,mg.url,g.Designation as gamme,a.Designation as article, (type*qte) as qu,cast(f.[date] AS date) FROM factures f 
INNER JOIN detailFactures df ON f.IdQltService=df.IdQltService 
INNER JOIN articles a ON df.idArticle=a.IdArticle 
INNER JOIN gammes g ON a.IdFamille=g.IdGamme
INNER JOIN mediaGammes mg ON g.IdGamme=mg.idGamme
WHERE idClient=".$_GET['idClient']." AND f.IdQltService IN (SELECT max(IdQltService) FROM factures WHERE idClient=".$_GET['idClient'].") 
ORDER BY g.IdGamme,qu desc";
$stmt2=sqlsrv_query($conn,$sql1,array(),array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt2 === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}
//echo $_GET['idClient']. " --" .$sql;
$nRes = sqlsrv_num_rows($stmt2);	
//echo $sql1;

//echo " xxx".$nRes;
if($nRes!=0)
{
$i=0;
		while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)){		
								$key= $row['IdGamme'];
								
							    $i=$i+1;
								
								if (!isset($gamme[$key])) {
									$gamme[$key] = array();
									$gamme[$key]['IdGamme']=$row['IdGamme'];
									$gamme[$key]['url']=$row['url'];
									$gamme[$key]['gamme']=$row['gamme'];									
									$i=0;
								} 
								
										if($gamme[$key]!=""){
												$gamme[$key][$i]['article']= $row['article'];
												$gamme[$key][$i]['qte']= $row['qu'];
										}
		
		}	
?>

	<DIV class="entete">
		<div class="divEntete" Style="width:220px;font-size:23px; vertical-align:middle" valign="middle" align="center">Gamme </div>
		<div class="divEntete" Style="width:600px;font-size:23px; vertical-align:middle" valign="middle" align="center">Article </div>
		<div class="divEntete" Style="width:132px;font-size:23px" align="center">Qte Vendue </div>
	</DIV>


<div  style="overflow-y:scroll;min-height:350px;max-height:350px"><!---->
<?php	$sum_article_qte=0;
foreach($gamme as $u=>$g){	?>
		<div style="background:white; width:1030px;" class="ligne">
			<div class="divText" Style="font-size:26px;"  align="center"><!--width:200px;height:48px;border:2px solid #e7e9ee;-->
				<?php  echo ucfirst($g['gamme']);//echo $g['gamme'];"<img src='../".$g['url']."' width='220' height:'150' title='' />" ?>
			</div>
			<div style="width:640px; display:block;"></div>
		</div>
		   <?php 
		   $sum_gamme_qte=0;
		   foreach($g as $article){	
						    if(is_array($article)){ ?>
							<div class="ligne">	
								<div style="width:240px; display:block;"></div>
								<div class="divText" style="width:600px;"> 
									<span style="margin-right:5px;"><?php  echo wordwrap(ucfirst($article['article']), 60, "<br />\n", true);?></span>
								</div> 
								<div class="divText" style="width:130px;TEXT-align:right;"> 
										<?php  echo $article['qte'];?>
								</div> 	
							</div> 
							
			<?php 
			$sum_gamme_qte+=intval($article['qte']);
			$sum_article_qte+=intval($article['qte']);			
				} 
			}
?>
<div style="TEXT-align:right;width:1030px; margin-bottom:5px;">
<u><strong>Total <?php  echo $g['gamme']; ?>: </strong><?php echo ($sum_gamme_qte); ?></u>
</div>
<?php 
}
?>
</div>
<div style="TEXT-align:right;width:1030px;margin-top:5px;">
<u><strong>TOTAL : </strong><?php echo ($sum_article_qte); ?></u>
</div>
<br/>
<?php
}else
{
echo "<br/><br/><br/><br/>";
}
?>
<div style="float:right; margin-right:25px;" >
<?php //echo $_GET['distance']; 
if(intval($_GET['distance']*1000)<= 100 ){ //en métre 1Km=1000M ?>
<input type="button" value="Démarrer visite" class="btn" onclick='demarrerVisite(<?php echo $_GET['idClient'];?>)' />
<?php } ?>
<input type="button" value="Itinéraire" class="btn" onclick='calculateRoute(<?php echo $_GET['from'];?>,<?php echo $_GET['to'];?> )' />

</div>

<script language="javascript" type="text/javascript">
function Fermer(){
	$("#boxClient").dialog('close');
	//markerInClick.setIcon("Tabac2.png");alert(markerInClick);
}
		 function demarrerVisite(idClient)
		 {
		 jConfirm('Voulez-vous vraiment démarer une visite pour ce client', null, function(r) {
				if(r){
						$('#formRes').load('inventaire.php?createVisite&idClt='+idClient);
					}
					});
		 }
		 function calculateRoute(from, to) {
		// alert(from);		 alert(to);
			// Center initialized to Naples, Italy
				var directionsService = new google.maps.DirectionsService();
				var directionsRequest = {
				  origin: from,
				  destination: to,
				  travelMode: google.maps.DirectionsTravelMode.DRIVING
				  //unitSystem: google.maps.UnitSystem.METRIC
				};
				directionsService.route(
				  directionsRequest,
				  function(response, status)
				  {
					if (status == google.maps.DirectionsStatus.OK)
					{
					  new google.maps.DirectionsRenderer({
						map: map,
						directions: response
					  });
					  $('#boxClient').dialog('close');
					}
					else
					  $("#error").append("Unable to retrieve your route<br/>");
				  }
				);

  }
  
</script>
<?php
exit;
}

if(isset($_GET['createVisite'])){
//echo "hereeeeeeeeeeeeeeeee";
$IdClt=$_GET['idClt'];
$dateD=date("d/m/Y");
$Hour=date("H:i");

$error="";
$reqInser1 = "INSERT INTO [dbo].[visites]  ([IdTournee] ,[datedebut]  ,[heureDebut]   ,[idClient] ,[idDepot]) 
				values(?,?,?,?,?)";
	$params1= array($_SESSION['IdTournee'],$dateD,$Hour,$IdClt,1) ;
	$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
	if( $stmt1 === false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
	}
	//---------------------------IdVisite--------------------------------//
$sql = "SELECT max(idvisite) as IdVisite FROM visites";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdVisite : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdVisite = sqlsrv_get_field( $stmt2, 0);

$_SESSION['IdVisite']=$IdVisite;	
	//---------------------------IdGroupClt--------------------------------//
$sql = "SELECT (idTypeClient) as IdGroupClt FROM clients";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération IdGroupClt : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$IdGroupClt = sqlsrv_get_field( $stmt2, 0);

$_SESSION['IdGroupe']=$IdGroupClt;

$_SESSION['IdClient']=$IdClt;	
	if( $error!="" ) 
	{
//	$var="";
	?>
		<script type="text/javascript"> 
		jAlert(<?php echo $trad['msg']['messageEssayerAutreFois']; ?>,<?php echo $trad['titre']['Alert']; ?>);
		</script>
	<?php
	}
	else
	{//echo ($_GET["type"]);
		//echo "Succes";
		//$var="";	
		?>
		<script language="javascript" type="text/javascript">
		
		var idClt=<?php echo ($_GET['idClt']); ?>
		//alert(type);
					//*******Cookie******Read And Write************/
					var clt=JSON.parse($.cookie("client"));
					clt[idClt]="3";//VISITE
					$.cookie("client",JSON.stringify(clt));
					//********************************************/

		window.location.href = 'catalogue4.php';
		</script>
	<?php
	//	header("Location: chargementVendeur.php");
	}	
	exit;
}
if(isset($_GET['map'])){ ?>


<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
			
<div>&nbsp;> <span  Class="TitleHead" id="DmrTrn"><?php echo $trad['index']['enqueteClient'];?></span></div>&nbsp;> <?php echo $trad['map']['map'] ; ?></div>

<div style="clear:both;"></div>
<?php
$whereType="";
$whereClass="";
$Types="";
$Classes="";

if(isset($_POST['Type']))
{
foreach($_POST['Type'] as $Type){
$Types.=$Type . ",";
}
$whereType=" and c.idTypeClient in (".rtrim($Types,",").")";
}

if(isset( $_POST['classe']))
{
foreach($_POST['classe'] as $Classe){
$Classes.="'".$Classe . "',";
}
$whereClass=" and classe in (".rtrim($Classes,",").")";
}

$sql = "SELECT c.IdClient,c.nom,c.prenom,c.adresse,c.longitude,c.latitude,tc.Designation,a.DsgActivite ,a.icone 
FROM clients c INNER JOIN typeClients tc ON c.idTypeClient=tc.idType INNER JOIN  activites a ON c.IdActivite=a.IdActivite WHERE (c.longitude!='' and c.latitude!='') AND c.departement=? ". $whereType .$whereClass ;//dc.idColisage *

//echo $sql;return;
$params = array($_POST['Secteur']);	
$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
	if( $stmt === false ) 
	{
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									return;
	}
$nRes = sqlsrv_num_rows($stmt);	//echo $nRes;
$client=array();
if($nRes==0)
{ ?>
	<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
		<br><br><br><br>
		Aucun r&eacute;sultat &agrave; afficher.
	</div>
<?php
return;
}
else
{	
	$i=0;
	$features="";
	$longitude_Secteur="";
	$latitude_Secteur="";
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{	
		$client[$i]['longitude']=$row['longitude'];
		$client[$i]['latitude']=$row['latitude'];
		$client[$i]['Designation']=$row['Designation'];		
		$i++;
		$features.="{position: new google.maps.LatLng(".$row['latitude'].",".$row['longitude']."),type:'".$row['Designation']."',name:'".$row['nom'] ." ".$row['prenom']."',adresse:'".$row['adresse']."',idClient:".$row['IdClient'].",lat:".$row['latitude'].",lng:".$row['longitude'].",activite:'".$row['DsgActivite']."',icon:'".$row['icone']."'},";
		
	}
	//echo $features;
	$features=substr($features, 0, -1);
	
	//--Get Latitude and Longitude of Secteur--------------------------------------------------------------
	$sql2 = "SELECT longitude,latitude FROM departements WHERE iddepartment=".$_POST['Secteur'];
	$params2 = array();	
	$stmt2=sqlsrv_query($conn,$sql2);
	if( $stmt2 === false ) {
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
	$row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC);
	$longitude_Secteur= $row[0];
	$latitude_Secteur= $row[1];
	//echo "heeeeeeeezr : ".$sql2;
	//echo $longitude_Secteur;echo $latitude_Secteur; return;
	// {position: new google.maps.LatLng(33.5777099168704,-7.6415726405868),type: 'Snack'        }, 

	//echo ($features);

?>


<style>     
 #map { 
    width: 1260px;
    margin: 0 auto;
    height: 734px;
    padding: 0; }      
 html, body {        height: 80%;        margin: 0;        padding: 0;      }    
 .ui-widget-content {
	background:#fff;
}
 </style>      
 <div id="map"></div>    
 <!--script src="js/jquery.min.js" type="text/javascript" ></script-->   
 <script src="js/jquery.geolocation.js"></script>
 <script> 
$("#DmrTrn").click(function(){
$('#formRes').load('inventaire.php?search');
});	
var pos; var destination="";  
 var marker=null;   var marker2=null; var marker3=null; 
var Center = null;
 function initMap() {   
var Center = new google.maps.LatLng(<?php echo $latitude_Secteur;?>,<?php echo $longitude_Secteur;?> );  
 map = new google.maps.Map(document.getElementById('map'), {          
 zoom: 15,		  
 center: Center,          
 mapTypeId: 'roadmap'        });        
 var iconBase = '';    
 var icons = {
				 vendeur:{ icon: iconBase + 'camion.png'}  
				 /*grossiste: { icon: iconBase + 'Tabac1.png'},   
				 grossiste2: { icon: iconBase + 'Tabac2.png'},  
				 grossiste3: { icon: iconBase + 'Tabac3.png'},  
				 Snack: { icon: iconBase + 'Snack1.png' }, 
				 Snack2: { icon: iconBase + 'Snack2.png' }, 
				 Snack3: { icon: iconBase + 'Snack3.png' }, 
				 Laitterie: {icon: iconBase + 'Laitterie1.png'}, 
				 Laitterie2: {icon: iconBase + 'Laitterie2.png'},
				 Laitterie3: {icon: iconBase + 'Laitterie3.png'},
				 Epicerie: {icon: iconBase + 'Epicerie1.png' },
				 Epicerie2: {icon: iconBase + 'Epicerie2.png' },
				 Epicerie3: {icon: iconBase + 'Epicerie3.png' }*/
				 }; 
     
 function addMarker(feature) {			
 var marker = new google.maps.Marker({ 
 position: feature.position,            
 icon: icons[feature.type].icon,            
 map: map          
 });        
 }

 var features =[<?php echo $features; ?>];
/* [
 {position: new google.maps.LatLng(33.5777099168704,-7.6415726405868),type: 'Snack'        }, 
 {position: new google.maps.LatLng(33.5777099168704,-7.6415726405868),type: 'Snack'       } ];   
*/ 
//---------------------------Add MArker-------------------------------------------------------------
 for (var i = 0, feature; feature = features[i]; i++) 
 {        
 //addMarker(feature); 
 var marker = new google.maps.Marker({ 
 position: feature.position,            
 icon: feature.icon,            
 map: map          
 }); 
//var clt=JSON.parse($.cookie("client"));

	//marker.setIcon(feature.icon);

 
 google.maps.event.addListener(marker, 'click', (function(marker, feature) {
        return function() {
    
//var distance= calcCrow(pos.lat,pos.lng,marker.getPosition().lat(),marker.getPosition().lng()) ;//km---------------------------------

//var json1 = JSON.stringify( pos );	
//var json2 = JSON.stringify( feature.position );
//*******Cookie******Read And Write****************************************/
/*var clt=JSON.parse($.cookie("client"));
if(!clt[feature.idClient])
{
var activite=feature.activite+"2";//Consultation
var icon="img/"+activite+".png";
marker.setIcon(icon);
clt[feature.idClient]="2";//Consultation
$.cookie("client",JSON.stringify(clt));
}*/
//************************************************************************/
$('#boxClient').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("inventaire.php?getenquete&IdClt="+feature.idClient).dialog('open');

/*	jConfirm('Voulez-vous vraiment démarer une visite pour le client '+feature.name + ' dont l\'adresse est '+feature.adresse, null, function(r) {
			if(r)	{
						
					 $('#formRes').load('inventaire.php?createVisite&idClt='+feature.idClient);
			
									
						}
						else
						{
			calculateRoute(pos,feature.position);	
					}
					})*/
					
					}
				  })(marker, feature));    
 }
   	
	
/* google.maps.event.addListener(marker, "position_changed", function() {
      var position = marker.getPosition();
    });

*/
//Position of Secteur--------------------------------------------------------------
var marker2 = new google.maps.Marker({ 
        position: Center, 
        draggable: false, 
        animation: google.maps.Animation.DROP,           
 map: map          
    }); 
//Position actuel------------------------------------------------------------------
 var marker3 = new google.maps.Marker({   
        draggable: false, 
        animation: google.maps.Animation.DROP, 		
		icon: icons['vendeur'].icon,           
		map: map          
    }); 
/*function watchMyPosition(position) 
{
//alert("-----Your position is: " + position.coords.latitude + ", " + position.coords.longitude + " (Timestamp: "  + position.timestamp + ")<br />");
  
  
  pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
	   marker3.setPosition(pos);    
		
}
	
myPosition = $.geolocation.watch({win: watchMyPosition}); 
*/
/**********************************************************************************************/
function autoUpdate() { 		   
	     if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                     pos = {lat: position.coords.latitude, lng: position.coords.longitude};
                    //infoWindow = new google.maps.InfoWindow({map: map});
                   // infoWindow.setPosition(pos);
                   // infoWindow.setContent("Found your location <br />Lat : "+position.coords.latitude+" </br>Lang :"+ position.coords.longitude);
                   // map.panTo(pos);
				    marker3.setPosition(pos);  
					
			
					//calculateRoute(pos,destination);	
                });
			//	alert("Herrrrrrre Geo");
				}
	 setTimeout(autoUpdate, 250); 
 }   
 autoUpdate();

 }  
 </script>  
 <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&callback=initMap<?php echo ($_SESSION['lang'] == 'ar' ) ? '&language=ar' : '&language=en'; ?>"> </script>
 <!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4&libraries=places&callback=initMap"
         async defer></script-->
<?php 	}
exit;
} 

if(isset($_GET['chargerSecteur'])){

	$Options = '<select multiple="multiple" name="Secteur" id="Secteur" class="Select Secteur"  tabindex="3" style="width:280px" >';
	$sql = "SELECT d.iddepartment,d.codeDepartement,d.Designation FROM departements d where idVille=?";
			//echo $sql. $_GET['IdZone']; return;
			$reponse=sqlsrv_query( $conn, $sql, array($_GET['IdVille']), array( "Scrollable" => 'static' ) );         
			/*   if( $reponse === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}*/
			
		$nRes = sqlsrv_num_rows($reponse);
		
		if($nRes != 0)
		 while ($donnees =  sqlsrv_fetch_array($reponse))
            {
				$Options.="<option value='".$donnees['iddepartment']."'>".$donnees['Designation']."</option>";			   
			}
		
		$Options.="</select>";
?>
				
	<script language="javascript" type="text/javascript">

$('#Secteur').multipleSelect({filter: true,placeholder:'<?php echo $trad['map']['selectSecteur'] ; ?>',single:true,maxHeight: 300,
		      onClick: function(view) {
				
				var Secteur =$('#Secteur').val();
				if(Secteur!="") {
					$('div.Secteur').removeClass('erroer');
					$('div.Secteur button').css("border","1px solid #ccc").css("background","#fff");
				}
}

});		

	</script>
	<?php
			echo $Options;
exit;
}

if(isset($_GET['search'])){ ?>
<DIV style="  display:flex;  align-items:center;"  class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
					
<div  onclick=" " >&nbsp;> <span  Class="TitleHead" ><?php echo $trad['index']['enqueteClient'] ; ?></span></div></div>

<div style="clear:both;"></div>
<form id="formAdd" method="post" action="mapp.php" name="formAdd"> 
<div style=" width:800px; height:300px; margin:auto; margin-top:60px;">
<div Style="display: flex;height:100px;">
	
	<div  Style="width:90px; text-align: center;vertical-align: middle;line-height: 65px;font-size:24px;"><?php echo $trad['label']['ville'] ; ?></div>
		<div>
			<select id="Ville" name="Ville" multiple="multiple"  Class="Select Ville" style="width:280px">
					
								 <?php $sql = "select idville, Designation from villes ";
							   $reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );         
									   while ($donnees =  sqlsrv_fetch_array($reponse))
									   {
									   ?>
									   <option value="<?php echo $donnees['idville'] ?>"><?php echo $donnees['Designation']?></option>
								 <?php
								  }
								 ?>
			</select>
		</div>


		<div Style="width:140px; text-align: center;vertical-align: middle;line-height: 65px;font-size:24px"><?php echo $trad['label']['secteur'] ; ?></div>
		<div id="Secteurs" style="width:300px;">			
					<select multiple="multiple" id="Secteur" name="Secteur" Class="Select Secteur" style="width:280px">
					</select>
		</div>

</div>
<div Style="display: flex;">
	
		<div  Style="width:90px; text-align: center;vertical-align: middle;line-height: 65px;font-size:24px"><?php echo $trad['label']['type'] ; ?></div>
		<div>
			<select id="Type" name="Type[]" multiple="multiple"  Class="Select Type" style="width:280px">
					
								 <?php 	
								 $req="	select IdType,Designation 
									from typeclients t 		";		
							   $reponse=sqlsrv_query( $conn, $req, array(), array( "Scrollable" => 'static' ) );         
									   while ($donnees =  sqlsrv_fetch_array($reponse))
									   {
									   ?>
									   <option value="<?php echo $donnees['IdType'] ?>"><?php echo $donnees['Designation']?></option>
								 <?php
								  }
								 ?>
			</select>
		</div>


		<div Style="width:140px; text-align: center;vertical-align: middle;line-height: 65px;font-size:24px"><?php echo $trad['label']['classe'] ; ?></div>
		<div>			
					<select multiple="multiple" id="classe" name="classe[]" Class="Select classe" style="width:280px">
					 <option value="a">A</option>
					 <option value="b">B</option>
					 <option value="c">C</option>
					</select>
		</div>

</div>
<div style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>; margin-right:5px;margin-top:25px;"><input type="button" value="<?php echo $trad['button']['rechercher'] ; ?>" class="btn"  onclick="Terminer()"/></div>
</div>
</form>
<script language="javascript" type="text/javascript">
$('#Ville').multipleSelect({
		  filter: true,placeholder:'<?php echo $trad['map']['selectVille'] ; ?>',single:true,maxHeight: 300,
		      onClick: function(view) {
				if(view.checked = 'checked')
				$('#Secteurs').load("inventaire.php?chargerSecteur&IdVille="+view.value);
				
				var Ville =$('#Ville').val();
				if(Ville!="") {
					$('div.Ville').removeClass('erroer');
					$('div.Ville button').css("border","1px solid #ccc").css("background","#fff");
				}
}});
$('#Type').multipleSelect({filter: true,placeholder:'<?php echo $trad['map']['selectType'] ; ?> ',maxHeight: 300,selectAllText:'<?php echo $trad['label']['selectTous'] ; ?> ',allSelected:'<?php echo $trad['label']['tousSelect'] ; ?> '});
$('#Secteur').multipleSelect({filter: true,placeholder:'<?php echo $trad['map']['selectSecteur'] ; ?> ',single:true,maxHeight: 300});		
$('#classe').multipleSelect({filter: true,placeholder:'<?php echo $trad['map']['selectClasse'] ; ?> ',maxHeight: 300,selectAllText:'<?php echo $trad['label']['selectTous'] ; ?> ',allSelected:'<?php echo $trad['label']['tousSelect'] ; ?> '});	
</script>
<?php
exit;
}
?>
<?php include("header.php"); ?>



<div id="formRes"></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<div id="boxClient"> </div>
<div id="boxImage"> </div>
<div id="box"> </div>
<div id="boxArticle"></div>


<script language="javascript" type="text/javascript">

$(document).ready(function() {
  		$('#formRes').load('qualite_service.php?aff');
		//$.validator.messages.required = '';
		

				$('#boxClient').dialog({
					autoOpen		:	false,
					width			:	800,
					height			:	400,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Info Client',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						},
						"Terminer "	: function() {
							terminer();
						
						}
					 }
			});
		$('#boxImage').dialog({
					autoOpen		:	false,
					width			:	1100,
					height			:	700,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'Image'});
});
function rechercher(){
		$('#formAdd').ajaxSubmit({target:'#formRes',url:'inventaire.php?aff'})
		clearForm('formRechF',0);
	}



function Terminer(){
	    $('#formAdd').validate({ 	
			errorPlacement: function(error, element) { //just nothing, empty  
					},		
		rules: {
			'Ville': "required",
			'Secteur': "required"
             } 
		});
						

	var test=$('#formAdd').valid();
	verifSelect2('Ville');
	verifSelect2('Secteur');
	
		if(test==true) {
		/*$('#formAdd').submit();
        $('#formAdd').action='mapp.php';
        $('#formAdd').target='mapp.php?map';	*/	
			
							$('#formAdd').ajaxSubmit({
									target			:	'#formRes',
									url				:	'inventaire.php?map',
									method			:	'post'
							}); 
						
							return false;
							
 
			
		//})
	}
}
/*******************Amina*****************************/
function getInventaire(){
	url="inventaire.php?affFam&VideFam";
$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
$("#boxClient").dialog('close');
}
		
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

	$(document).ready(function(){	
	$.validator.messages.required = '';
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
			});
function AfficheDetailGamme(id){
	//alert('lll');
	/*var url='inventaire.php?aff&&Id='+id;	
	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');*/
	var current = slider.getCurrentSlide();
	var url='inventaire.php?affArti&Id='+id+'&CurrentSlide='+current;	
	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

}
function rechercherFam(){
		//alert('lll');
		var url='inventaire.php?affFam';	
		$('#box').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
}
function AfficheSousFam(id){
		//alert(id);
		var url='inventaire.php?affSousFam&&Id='+id;	
		$('#box').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}
function AfficheMarque(id){
		var url='inventaire.php?affMarque&&Id='+id;	
		$('#box').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

	}	
function Fermer(){
	$("#box").dialog('close');
}	
function AfficheGamme1(id,CurrentSlide){
	if (CurrentSlide === undefined || CurrentSlide === null) {
					var url='inventaire.php?affGamme&&Id='+id;	}
					else {
							var url='inventaire.php?affGamme&Id='+id+'&CurrentSlide='+CurrentSlide;	
					}
			
				$('#box').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

}
function AfficheArticles(idMarque){
	//alert('lll');
	/*var url='inventaire.php?aff&&Id='+id;	
	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');*/
	//var current = slider.getCurrentSlide();
	var url='inventaire.php?affArti&Id='+idMarque;	
	//alert(url);
	$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url);

}
function getArticle(idArticle){	

		//pour afficher liste
		var url='inventaire.php?getArticle&&list&&idArticle='+idArticle;	
		$('#boxArticle').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load(url).dialog('open');
	
	
}

	
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
			 jConfirm('<?php echo $trad['msg']['ConfirmerOperation'];?>', null, function(r) {
					if(r)	{
							
							$(form).ajaxSubmit({
												target			:	'#resAddArt',
												url				:	'inventaire.php?goAddArti&list=Actualiser',
												method			:	'post'
											});
											return false;
						   
						   

												
						}
				})
		}
}
function FermerBoxArt(){

	$("#boxArticle").dialog('close');
}
function Fermer(){
	$("#box").dialog('close');
}	
function EnregisterInv(){

	 jConfirm('<?php echo $trad['msg']['ConfirmerOperation'];?>', '<?php echo $trad['titre']['Confirm'];?>', function(r) {
					if(r)	{
					var url='inventaire.php?goadd';	
				$('#res').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>').load(url);
					}
		 });
}	
</script>
<?php
include("footer.php");
?>
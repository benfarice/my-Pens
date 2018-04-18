<?php	  if(!isset($_SESSION))
{
session_start();
}
include("lang.php");

/****/
if(isset($_GET['logout'])){
	?>
	<script language="javascript" > 
	window.location.href = 'login.php';
	</script>
<?php

exit;

}
?>
	
	<ul id="menu" class="chpinvisible">
	<li>
			<a href=""><?php echo $trad['Menu']['parametrage']; ?></a>
			<ul>
		
			<li><a href="vehicule.php"> <?php echo $trad['frais']['vehicule']; ?></a></li>	
			<li><a href="#"><?php echo $trad['label']['Article']; ?></a>
			
			<ul>
				<li><a href="famille.php"> <?php echo $trad['titre']['Famille']; ?></a></li>	
				<li><a href="sousfamille.php">  <?php echo $trad['titre']['SousFamille']; ?> </a></li>	
				<li><a href="gamme.php">  <?php echo $trad['titre']['Gamme']; ?> </a></li>				
				<li><a href="article.php">  <?php echo $trad['label']['Article']; ?></a></li>
			</ul>
			
			</li>	

			<li><a href="#">  <?php echo $trad['Menu']['gestionsecteur']; ?></a>
				<ul>
					<li><a href="zone.php"> <?php echo $trad['Label']['zone']; ?> </a></li>
					<li><a href="region.php"> <?php echo $trad['Label']['region']; ?> </a></li>
					<li><a href="ville.php">  <?php echo $trad['label']['ville']; ?></a></li>
					<li><a href="secteur.php"> <?php echo $trad['label']['secteur']; ?> </a></li>
				</ul>
			</li>
			
			<li><a href="vendeur.php">  <?php echo $trad['Menu']['gestionvendeur']; ?></a>
		
			</li>
			
			<li><a href="affectation.php"><?php echo $trad['Menu']['affvendeur']; ?> </a></li>
			<li><a href="clients.php"> <?php echo $trad['index']['GestionClient']; ?></a></li>	
			<li><a href="details_tarifs.php"><?php echo $trad['Menu']['tarif']; ?> </a></li>
			<li><a href="qualite_service.php"> <?php echo $trad['label']['QualityService']; ?></a></li>	
	
			</ul>
		</li>
		<li>
			<a href=""><?php echo $trad['Menu']['gestionstock']; ?>  </a>
			<ul>
				<li><a href="entrees_y.php"><?php echo $trad['Menu']['entreestock']; ?>  </a></li>	
				<li><a href="validation_stock.php"><?php echo $trad['Menu']['ValidationStock']; ?>  </a></li>	
			</ul>					
		</li>
				<li><a href="validation_retour.php"><?php echo $trad['label']['ValidationRetour']; ?>  </a></li>		
		<!--li><a href="chargements.php"><?php echo $trad['Menu']['gestionchargement']; ?>  </a></li-->		
		<li class="chpinvisible">
			<a href=""><?php echo $trad['Menu']['statistic']; ?></a>
			<ul>
				<!--li><a href="vente_mensuelle.php"> <?php echo $trad['label']['etatVenteMensuelle']; ?>  </a></li> 		
				<li><a href="DetailVenteVnd.php"> <?php echo $trad['label']['etatVenteParVnd']; ?>  </a></li> 
				<li><a href="stockVnd.php"> <?php echo $trad['Menu']['stockVnd']; ?></a></li-->				
				<li><a href="Vente.php"> <?php echo $trad['Menu']['venteVendeur']; ?>  </a></li>	
				<li><a href="ventepararticle.php"><?php echo $trad['Menu']['venteFamille']; ?>   </a></li>	
				<li><a href="stock.php"> <?php echo $trad['Menu']['stock']; ?> </a></li>		
				<li><a href="Visite.php"> <?php echo $trad['Menu']['visite']; ?></a></li>	
				<li><a href="CaParPdt.php"> <?php echo $trad['Menu']['caArticle']; ?> </a></li>	
				<li><a href="CaParVnd.php"> <?php echo $trad['Menu']['caVendeur']; ?> </a></li>	
				<li><a href="VenteParMois.php"> <?php echo $trad['Menu']['venteMois']; ?> </a></li>	
				<!--li><a href="VenteParVille.php"> <?php //echo $trad['Menu']['venteVille']; ?> </a></li!-->	
				<li><a href="ArticleCltparVille.php"> <?php echo $trad['Menu']['ArticleClt']; ?> </a></li>
				<li><a href="trace.php"> <?php echo $trad['Menu']['trace']; ?></a></li>					
				<!-- youssef -->
				<li><a href="city-dates.php"> <?php echo $trad['Menu']['CAVille']; ?></a></li>	
				<li><a href="date_vendeur_chart.php"> <?php echo $trad['Menu']['CAVdrChart']; ?></a></li>	
				<li><a href="journee-vendeurs.php"> <?php echo $trad['Menu']['CAVdrDay']; ?></a></li>	
				<li><a href="view_frais.php">Gestion des frais</a></li>
			</ul>					
		</li>
			<li>
			<a href=""><?php echo $trad['Menu']['statistic']; ?></a>
			<ul>			
				<li><a href="#">Chiffre d'affaire</a>
						<ul>
								<li><a href="Vente.php"> <?php echo $trad['Menu']['venteVendeur']; ?>  </a></li>
								<li><a href="ventepararticle.php"><?php echo $trad['Menu']['venteFamille']; ?>   </a></li>			
								<li><a href="CaParPdt.php"> <?php echo $trad['Menu']['caArticle']; ?> </a></li>	
								<li><a href="CaParVnd.php"> <?php echo $trad['Menu']['caVendeur']; ?> </a></li>	
								<li><a href="VenteParMois.php"> <?php echo $trad['Menu']['venteMois']; ?> </a></li>			
									<!-- youssef -->
								<li><a href="city-dates.php"> <?php echo $trad['Menu']['CAVille']; ?></a></li>	
								<li><a href="date_vendeur_chart.php"> <?php echo $trad['Menu']['CAVdrChart']; ?></a></li>	
								<li><a href="journee-vendeurs.php"> <?php echo $trad['Menu']['CAVdrDay']; ?></a></li>									
						</ul>
				</li>	
				<li><a href="stock.php"> <?php echo $trad['Menu']['stock']; ?> </a></li>	
				<li><a href="#">Visite</a>
						<ul>
								<li><a href="Visite.php"> <?php echo $trad['Menu']['visite']; ?></a></li>	
								<li><a href="ArticleCltparVille.php"> <?php echo $trad['Menu']['ArticleClt']; ?> </a></li>
								<li><a href="trace.php"> <?php echo $trad['Menu']['trace']; ?></a></li>									
						</ul>
				</li>	
				<li><a href="view_frais.php">Consultation des frais</a></li>
				
			</ul>					
		</li>
		<li><a href="login.php" class="signoutsignout"><?php echo $trad['Menu']['deconnexion']; ?>
		<DIV class="signout">
		
		</div>
		</a></li>		
		
		
		
<!--li style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>;border-right:0;-moz-box-shadow:none;
-webkit-box-shadow: none;"><a href="" class="depot">Dépôt Electroprotect  </a></li-->
	</ul>
<br><br>
<style>
.mn{
		background-color: #0186ba;
	background-image: -moz-linear-gradient(#04acec,  #0186ba);	
	background-image: -webkit-gradient(linear, left top, left bottom, from(#04acec), to(#0186ba));
	background-image: -webkit-linear-gradient(#04acec, #0186ba);
	background-image: -o-linear-gradient(#04acec, #0186ba);
	background-image: -ms-linear-gradient(#04acec, #0186ba);
	background-image: linear-gradient(#04acec, #0186ba);	
	width:100%; height:41px;
	
}
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu .dropdown-menu,dropdown2-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
}
.dropdown{

	float:left;
}
.caret {
    display: inline-block;
    width: 0;
    height: 0;
    margin-left: 2px;
    vertical-align: middle;
    border-top: 4px dashed;
    border-top: 4px solid\9;
    border-right: 4px solid transparent;
    border-left: 4px solid transparent;
}
.li{
	border:none;background:none;
	padding:14px 35px;
	cursor:pointer;
	border-right:1px solid #fff;
    color: #fafafa;
    text-transform: uppercase;
    font: bold 12px Arial, Helvetica;
    text-decoration: none;
    text-shadow: 0 1px 0 #000;
}
.dropdown-menu{
	background:#0186ba;
	color:#fff;
	margin:0;
	padding-top:0;	padding-bottom:0;
	min-width:205px;
}

.dropdown-menu > li > a {
    color:#fff;
    padding:  10px 20px;
	border-bottom:1px solid #fff;
	}
</style>
<div class="mn " >
<div class="dropdown">
    <button class="li" type="button" data-toggle="dropdown"><?php echo $trad['Menu']['parametrage']; ?>
    <span class="caret"></span></button>
    <ul class="dropdown-menu">
      <li><a tabindex="-1" href="vehicule.php">   <?php echo $trad['frais']['vehicule']; ?></a></li>
	   <li class="dropdown-submenu">
			<a class="test" tabindex="-1" href="#">	<?php echo $trad['label']['Article']; ?> <span class="caret"></span></a>
			<ul class="dropdown-menu">		 
				  <li> 	<a href="famille.php"> 	<?php echo $trad['titre']['Famille']; ?>	</a></li>
				  <li>	<a href="sousfamille.php">  <?php echo $trad['titre']['SousFamille']; ?></a>  </li>
				   <li>	<a href="gamme.php">  <?php echo $trad['titre']['Gamme']; ?>  	</a></li>	
				   <li>	<a href="article.php"> 	<?php echo $trad['label']['Article']; ?>		</a> </li>
			</ul>
      </li>
	  
	 <li class="dropdown-submenu">
		<a class="test" tabindex="-1" href="#">	  <?php echo $trad['Menu']['gestionsecteur']; ?><span class="caret"></span></a>
		<ul class="dropdown-menu">		 
			 	<li><a href="zone.php"> <?php echo $trad['Label']['zone']; ?> </a></li>
					<li><a href="region.php"> <?php echo $trad['Label']['region']; ?> </a></li>
					<li><a href="ville.php">  <?php echo $trad['label']['ville']; ?></a></li>
					<li><a href="secteur.php"> <?php echo $trad['label']['secteur']; ?> </a></li>
		</ul>
	</li>
	  <li><a href="vendeur.php">  <?php echo $trad['Menu']['gestionvendeur']; ?>	</a></li>
	  <li><a href="affectation.php"><?php echo $trad['Menu']['affvendeur']; ?></a></li>
	  <li><a href="clients.php"> <?php echo $trad['index']['GestionClient']; ?></a></li>	
		<li><a href="details_tarifs.php"><?php echo $trad['Menu']['tarif']; ?> </a></li>
		<li><a href="clients.php"> <?php echo $trad['index']['GestionClient']; ?></a></li>
		<li>	<a href="qualite_service.php">	<?php echo $trad['label']['QualityService']; ?>		</a>			</li>	
    </ul>
	
	 </div>
 
 
<div class="dropdown">
    <button class="li" type="button" data-toggle="dropdown"><?php echo $trad['Menu']['gestionstock']; ?> 
    <span class="caret"></span></button>
    <ul class="dropdown-menu">
		<li><a href="entrees_y.php"><?php echo $trad['Menu']['entreestock']; ?>  </a></li>	
		<li><a href="validation_stock.php"><?php echo $trad['Menu']['ValidationStock']; ?>  </a></li>	
    </ul>
	
  </div>
  <div class="dropdown">
    <button class="li" type="button" onclick ="window.location.href = 'validation_retour.php'" data-toggle="dropdown"><?php echo $trad['Menu']['gestionstock']; ?> </button>
    </div>
	
	
	<div class="dropdown">
    <button class="li" type="button" data-toggle="dropdown"><?php echo $trad['Menu']['statistic']; ?>
    <span class="caret"></span></button>
    <ul class="dropdown-menu">

	   <li class="dropdown-submenu">
			<a class="test" tabindex="-1" href="#">Chiffre d'affaire <span class="caret"></span></a>
			<ul class="dropdown-menu">		 
								<li><a href="Vente.php"> <?php echo $trad['Menu']['venteVendeur']; ?>  </a></li>
								<li><a href="ventepararticle.php"><?php echo $trad['Menu']['venteFamille']; ?>   </a></li>			
								<li><a href="CaParPdt.php"> <?php echo $trad['Menu']['caArticle']; ?> </a></li>	
								<li><a href="CaParVnd.php"> <?php echo $trad['Menu']['caVendeur']; ?> </a></li>	
								<li><a href="VenteParMois.php"> <?php echo $trad['Menu']['venteMois']; ?> </a></li>			
									<!-- youssef -->
								<li><a href="city-dates.php"> <?php echo $trad['Menu']['CAVille']; ?></a></li>	
								<li><a href="date_vendeur_chart.php"> <?php echo $trad['Menu']['CAVdrChart']; ?></a></li>	
								<li><a href="journee-vendeurs.php"> <?php echo $trad['Menu']['CAVdrDay']; ?></a></li>	
			</ul>
      </li>
	  		<li><a href="stock.php"> <?php echo $trad['Menu']['stock']; ?> </a></li>	
	 <li class="dropdown-submenu">
		<a class="test" tabindex="-1" href="#">	Clients<span class="caret"></span></a>
		<ul class="dropdown-menu">		 
								<li><a href="Visite.php"> <?php echo $trad['Menu']['visite']; ?></a></li>	
								<li><a href="ArticleCltparVille.php"> <?php echo $trad['Menu']['ArticleClt']; ?> </a></li>
								<li><a href="trace.php"> <?php echo $trad['Menu']['trace']; ?></a></li>			
		</ul>
	</li>
	 			<li><a href="view_frais.php">Consultation des frais</a></li>	</li>	
    </ul>
	
	</div>
	  <div class="dropdown">
    <button class="li" type="button" onclick ="window.location.href = 'login.php'" data-toggle="dropdown">
	<?php echo $trad['Menu']['deconnexion']; ?></button>
    </div>
 </div> 
  

<script>
$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});
    $(function() {
		if ($.browser.msie && $.browser.version.substr(0,1)<7)
		{
		$('li').has('ul').mouseover(function(){
			$(this).children('ul').css('visibility','visible');
			}).mouseout(function(){
			$(this).children('ul').css('visibility','hidden');
			})
		}

		/* Mobile */
		$('#menu-wrap').prepend('<div id="menu-trigger">Menu</div>');		
		$("#menu-trigger").on("click", function(){
			$("#menu").slideToggle();
		});

		// iPad
		var isiPad = navigator.userAgent.match(/iPad/i) != null;
		if (isiPad) $('#menu ul').addClass('no-transition');      

    });       

   
	  
</script>

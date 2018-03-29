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
	
	<ul id="menux">
	  <li>
	    <a><span>
	    	<?php echo $trad['Menu']['parametrage']; ?>
	    	</span>
	    </a>
		<ul>
		   <li>
		   	  <a href="vehicule.php">
		   	   <?php echo $trad['frais']['vehicule']; ?>
		   	  </a>
		   </li>	
		   <li>
		   	  <a>
		   	  	<?php echo $trad['label']['Article']; ?>
		   	  </a>
			  <ul>
				 <li>
				 	<a href="famille.php"> 
				 		<?php echo $trad['titre']['Famille']; ?>
				 	</a>
				 </li>	
				 <li>
				 	<a href="sousfamille.php"> 
				 	 <?php echo $trad['titre']['SousFamille']; ?>
				    </a>
				 </li>	
				 <li>
				 	<a href="gamme.php"> 
				 	 <?php echo $trad['titre']['Gamme']; ?> 
				 	</a>
				 </li>				
				 <li>
				 	<a href="article.php">  
				 		<?php echo $trad['label']['Article']; ?>
				 	</a>
				 </li>
			  </ul>
			</li>	
            <li>
            	<a> 
            	  <?php echo $trad['Menu']['gestionsecteur']; ?>
            	</a>
				<ul>
					<li><a href="zone.php"> <?php echo $trad['Label']['zone']; ?> </a></li>
					<li><a href="region.php"> <?php echo $trad['Label']['region']; ?> </a></li>
					<li><a href="ville.php">  <?php echo $trad['label']['ville']; ?></a></li>
					<li><a href="secteur.php"> <?php echo $trad['label']['secteur']; ?> </a></li>
				</ul>
			</li>
			<li>
				<a href="vendeur.php">  
					<?php echo $trad['Menu']['gestionvendeur']; ?>
				</a>
	        </li>
			<li>
				<a href="affectation.php"><?php echo $trad['Menu']['affvendeur']; ?>
				</a>
			</li>
			<li>
				<a href="clients.php"> <?php echo $trad['index']['GestionClient']; ?>
				</a>
			</li>	
			<li>
				<a href="details_tarifs.php"><?php echo $trad['Menu']['tarif']; ?> 
				</a>
			</li>
			<li>
				<a href="qualite_service.php">
				 <?php echo $trad['label']['QualityService']; ?>
				</a>
			</li>	
	      </ul>
	   </li>
	   <li>
			<a><?php echo $trad['Menu']['gestionstock']; ?>  </a>
			<ul>
				<li><a href="entrees.php"><?php echo $trad['Menu']['entreestock']; ?>  </a></li>	
			</ul>					
		</li>
			
		<li><a href="chargements.php"><?php echo $trad['Menu']['gestionchargement']; ?>  </a></li>		
		<li>
			<a><?php echo $trad['Menu']['statistic']; ?></a>
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
				<li><a href="date_vendeur_chart_x5boo4.php"> 
							CA par Vendeur(Date)
				</a></li>					
				<li><a href="VenteParMois.php"> <?php echo $trad['Menu']['venteMois']; ?> </a></li>	
				<!--li><a href="VenteParVille.php"> <?php //echo $trad['Menu']['venteVille']; ?> </a></li!-->	
				<li><a href="ArticleCltparVille.php"> <?php echo $trad['Menu']['ArticleClt']; ?> </a></li>
				<li><a href="trace.php"> <?php echo $trad['Menu']['trace']; ?></a></li>	
				 <li><a href="city-dates.php"> <?php echo $trad['Menu']['CAVille']; ?></a></li>	
				<li><a href="date_vendeur_chart.php"> <?php echo $trad['Menu']['CAVdrChart']; ?></a></li>	
				<li><a href="journee-vendeurs.php"> <?php echo $trad['Menu']['CAVdrDay']; ?></a></li>	
			</ul>					
		</li>
		<li><a href="login.php" class="signoutsignout"><?php echo $trad['Menu']['deconnexion']; ?>
		<DIV class="signout">		
		</div>
		</a></li>
	</ul>

<script>
    $(function() {
		/*if ($.browser.msie && $.browser.version.substr(0,1)<7)
		{
		$('li').has('ul').click(function(){
			$(this).children('ul').css('visibility','visible');
			}).click(function(){
			$(this).children('ul').css('visibility','hidden');
			})
		}

		/* Mobile *//*
		$('#menu-wrap').prepend('<div id="menu-trigger">Menu</div>');		
		$("#menu-trigger").on("click", function(){
			$("#menux").slideToggle();
		});

		// iPad
		var isiPad = navigator.userAgent.match(/iPad/i) != null;
		if (isiPad) $('#menux ul').addClass('no-transition');      
    }); 
    */
    $("#menux > li").click(function() {
       $(this).find(" > ul").show();
       $(this).find(" > ul").css( "opacity",'1');
      
   });
    $("#menux > li ul").mouseleave(function() {
       $(this).hide();
       $(this).css( "opacity",'0');
      
   });

    $("#menux li ul li").click(function() {
       $(this).find(" > ul").toggle();
       $(this).find(" > ul").css( "opacity",'1');
       $(this).parent().show();
      
   });
   /* $('body *:not(#menux)').click(function() {
       $("#menux > ul").hide();
   });
   $(document).on("click",":not(#menux)",function(){
    alert("worky");
	});
   */
  
});

</script>

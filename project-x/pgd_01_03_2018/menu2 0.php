	<ul id="menu">
	<li>
			<a href="">Paramétrage</a>
			<ul>
		
			<li><a href="vehicule.php"> Véhicules</a></li>	
			<li><a href="#"> Gestion des articles </a>
			
			<ul>
				<li><a href="famille.php">Familles</a></li>	
				<li><a href="sousfamille.php"> Sous Familles </a></li>	
			<li><a href="gamme.php"> Gammes </a></li>				
			<li><a href="article.php"> Articles</a></li>
			</ul>
			
			</li>	

			<li><a href="#"> Gestion des secteurs</a>
			<ul>
			<li><a href="zone.php"> Zones</a></li>
			<li><a href="region.php"> Régions</a></li>
			<li><a href="ville.php"> Villes</a></li>
			<li><a href="secteur.php"> Secteurs</a></li>
			</ul>
			</li>
			
			<li><a href="vendeur.php"> Gestion des Vendeurs</a>
			<ul>
			<li><a href=""> Vendeurs</a></li>

			</ul>
			</li>
			
			<li><a href="affectation.php"> Affectation vendeur-véhicule</a></li>
			<li><a href="clients.php"> Clients</a></li>	
			<li><a href="details_tarifs.php"> Tarifs</a></li>
	
			</ul>
		</li>
		<li>
			<a href="">Gestion de stock</a>
			<ul>
				<li><a href="entrees.php">Ajout des entrées</a></li>	
			</ul>					
		</li>
			
		<li><a href="chargements.php">Gestion des chargements</a></li>		
		<li>
			<a href="">Statistiques</a>
			<ul>
				<li><a href="Vente.php"> Ventes par vendeur</a></li>	
				<li><a href="ventepararticle.php"> Ventes par famille</a></li>	
				<li><a href="stock.php"> Stock </a></li>	
				<li><a href="Visite.php"> Vistes</a></li>	
				<li><a href="CaParPdt.php"> CA par article</a></li>	
				<li><a href="CaParVnd.php"> CA par vendeur</a></li>	
				<li><a href="VenteParMois.php"> Ventes par mois</a></li>	
				<li><a href="VenteParVille.php"> Ventes par ville</a></li>	
		<!--	<li><a href="frais_vendeur.php"> Frais vendeur</a></li>				-->
			</ul>					
		</li>
		
				<li style="float:right;border-right:0;-moz-box-shadow:none;
-webkit-box-shadow: none;"><a href="" class="depot">Dépôt Electroprotect  </a></li>
	</ul>

<script>
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

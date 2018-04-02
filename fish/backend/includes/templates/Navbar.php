<nav id="navigation">
<ul id="main-menu">
<li class="current-menu-item">  <a href="#home" class="active"><?php echo lang('Accueil');?></a></li>


<li  class="parent" href="#"> <a><?php echo lang('customize_Profile');?></a>
<ul class="sub-menu">
<li> <a href="gestion_families.php"><?php echo lang('gestion_families');?></a></li>
<li> <a href="gestion_especes.php"><?php echo lang('gestion_especes');?></a></li>
<li>  <a href="seller_buyer.php"><?php echo lang('SellerManage');?></a></li>
<li> <a href="buyer.php"><?php echo lang('BuyerManage');?></a></li>

</ul>
</li>

<li  class="parent" href="#"> <a><?php echo lang('Compta');?></a>
<ul class="sub-menu">
<li> <a href="factures_acht.php" ><?php echo lang('FactureAcht');?></a></li>
<li> <a href="facture_vendeur.php" ><?php echo lang('facture_vendeur');?></a></li>
<li> <a href="print_factures.php" ><?php echo lang('print_facures');?></a></li>
<li> <a href="reglement_acht.php" ><?php echo lang('reglement_acht');?></a></li>
<li> <a href="reglement_vdr.php?sel" ><?php echo lang('reglement_vdr');?></a></li>
</ul>
</li>


<li  class="parent" href="#"> <a><?php echo lang('Statistic');?></a>
<ul class="sub-menu">
<li> <a href="identification_vdr.php" ><?php echo lang('IdentificationVdr');?></a></li>
<li> <a href="identification_esp.php" ><?php echo lang('IdentificationEsp');?></a></li>
<li> <a href="report_bill_buyers.php" ><?php echo lang('report_bill_buyers');?></a></li>
<li> <a href="report_bill_sellers.php" ><?php echo lang('report_bill_sellers');?></a></li>
<li> <a href="statistique_buy_sell_fish.php" ><?php echo lang('statistic_sell_fish');?></a></li>

</ul>
</li>
<li> <a href="buyer.php"><?php echo lang('Contact');?></a></li>
<li> <a href="index.php"><?php echo lang('Logout');?></a></li>
</ul>


</nav>
<br><br>
<div class="clear"></div>
<div class="topnav chpinvisible" id="myTopnav">
  <a href="#home" class="active"><?php echo lang('Accueil');?></a>
  <a href="seller_buyer.php"><?php echo lang('SellerManage');?></a>
   <a href="buyer.php"><?php echo lang('BuyerManage');?></a>
     <a href="buyer.php"><?php echo lang('FamimmeMange');?></a>
  <a href="#contact"><?php echo lang('Contact');?></a>
  <a href="#about"><?php echo lang('Logout');?></a>
  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
</div><div class="clear"></div>
<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
$(document).ready(function() {

	/* MAIN MENU */
	$('#main-menu > li:has(ul.sub-menu)').addClass('parent');
	$('ul.sub-menu > li:has(ul.sub-menu) > a').addClass('parent');

	$('#menu-toggle').click(function() {
		$('#main-menu').slideToggle(300);
		return false;
	});

	$(window).resize(function() {
		if ($(window).width() > 700) {
			$('#main-menu').removeAttr('style');
		}
	});

});
</script>



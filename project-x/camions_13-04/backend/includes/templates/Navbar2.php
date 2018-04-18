<nav id="navigation">
<ul id="main-menu">
<li class="current-menu-item">  <a href="#home" class="active"><?php echo lang('Accueil');?></a></li>


<li  class="parent" href="#"> <a><?php echo lang('customize_Profile');?></a>
<ul class="sub-menu">
<li> <a href="gestion_camions.php"><?php echo lang('gestion_camions');?></a></li>
<li> <a href="gestion_chauf.php"><?php echo lang('gestion_chauf');?></a></li>


</ul>
</li>




<li  class="parent" href="#"> <a><?php echo lang('Statistic');?></a>
<ul class="sub-menu">
<li> <a href="etat_camion.php" ><?php echo lang('EtatCamion');?></a></li>
<li> <a href="etat_chauf.php" ><?php echo lang('EtatChauf');?></a></li>



</ul>
</li>

<li> <a href="logout.php"><?php echo lang('Logout');?></a></li>
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
